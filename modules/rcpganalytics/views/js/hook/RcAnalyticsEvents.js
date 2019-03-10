/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a trade license awared by
 * Garamo Online L.T.D.
 *
 * Any use, reproduction, modification or distribution
 * of this source file without the written consent of
 * Garamo Online L.T.D It Is prohibited.
 *
 *  @author    ReactionCode <info@reactioncode.com>
 *  @copyright 2015-2018 Garamo Online L.T.D
 *  @license   Commercial license
 */

function RcAnalyticsEvents() {

    // reference to this
    var publicValues = this;

    ////////////////////////////////////
    // Private values

    var privateValues = {};

    // get module url from common js var prestashop
    privateValues.moduleUrl = baseDir + 'modules/rcpganalytics/';

    // don't change this value!! used for redirect after hit
    privateValues.redirected = false;
    privateValues.redirectLink = null;

    // products position detected on scroll tracking
    privateValues.productsPosition = {};

    // scroll tracking event
    privateValues.initial = true;
    privateValues.sendProducts = [];
    privateValues.sentProducts = [];
    privateValues.scrollTimeout = null;

    // product page
    privateValues.lastIdProductView = '';

    // don not track
    privateValues.doNotTrack = (
        window.doNotTrack == "1" ||
        navigator.doNotTrack == "yes" ||
        navigator.doNotTrack == "1" ||
        navigator.msDoNotTrack == "1"
    );

    ////////////////////////////////////
    // Public values

    // all tracking features
    publicValues.trackingFeatures = null;

    publicValues.lists = null;

    // cache products
    publicValues.productsListCache = null;

    // remarketing page type
    publicValues.ecommPageType = 'other';

    // checkout
    publicValues.shippingEventName = '';
    publicValues.paymentEventName = '';
    publicValues.opcEventName = '';
    publicValues.pageStep = 1;

    // Theme Events
    publicValues.eventScrollList = eventScrollList;
    publicValues.eventClickProductList = eventClickProductList;
    publicValues.eventAddCartProductList = eventAddCartProductList;
    publicValues.eventProductView = eventProductView;
    publicValues.eventAddCartProductView = eventAddCartProductView;
    publicValues.eventSocialShareProductView = eventSocialShareProductView;
    publicValues.eventWishListProductView = eventWishListProductView;
    publicValues.eventRemoveFromCart = eventRemoveFromCart;
    publicValues.eventCartQuantityUp = eventCartQuantityUp;
    publicValues.eventCartQuantityDown = eventCartQuantityDown;
    publicValues.eventCartQuantityDelete = eventCartQuantityDelete;
    publicValues.eventCheckoutStepThree = eventCheckoutStepThree;
    publicValues.eventCheckoutStepFour = eventCheckoutStepFour;
    publicValues.eventOpcSignUpPrestashop = eventOpcSignUpPrestashop;
    publicValues.eventOpcPrestashop = eventOpcPrestashop;
    publicValues.eventOpcZelarg = eventOpcZelarg;
    publicValues.eventOpcBestKit = eventOpcBestKit;
    publicValues.eventOpcPrestaTeam = eventOpcPrestaTeam;
    publicValues.eventOpcSuperCheckout = eventOpcSuperCheckout;

    // Tracking Methods
    publicValues.onSearchResults = onSearchResults;
    publicValues.onCheckoutProducts = onCheckoutProducts;
    publicValues.onAddOrder = onAddOrder;
    publicValues.onSignUp = onSignUp;

    // GTAG Methods
    publicValues.sendGtagConfig = sendGtagConfig;

    // Common Methods
    publicValues.setClientId = setClientIdInDb;

    // Singleton Pattern
    if (RcAnalyticsEvents.prototype.getInstance) {
        return RcAnalyticsEvents.prototype.getInstance;
    }

    RcAnalyticsEvents.prototype.getInstance = this;
    ///////////////////////////////////////////////

    ///////////////////////////////////////////////
    // TEMPLATE EVENTS

    // PRODUCT LISTS - Scroll
    function eventScrollList() {
        if (!privateValues.initial) {
            clearTimeout(privateValues.scrollTimeout);
            scrollProductDetection();

            privateValues.scrollTimeout = setTimeout(function() {
                if (privateValues.sendProducts.length) {
                    doneScroll();
                }
            }, 800);
        } else {
            privateValues.initial = false;
            scrollProductDetection();
            doneScroll();
        }
    }

    // PRODUCT LISTS - Click on product
    function eventClickProductList(event) {
        var mainSelector = ['.ajax_block_product'];
        var variantSelector = ['.color-list-container a'];
        var eventSelectors = [
            '.ajax_block_product .product_img_link',
            '.ajax_block_product .product-name',
            '.ajax_block_product .quick-view',
            '.ajax_block_product .quick-view-mobile',
            '.ajax_block_product .lnk_view',
            '.ajax_block_product .color_pick',
            '.ajax_block_product .new-box',
            '.ajax_block_product .sale-box'
        ];
        var target = delegateEvents(eventSelectors, event.target);
        var caseClick = 1;
        var classList;
        var link;
        var productNode;
        var variantNode;
        var variantAttribute;
        var idProduct;
        var idProductAttribute;
        var list;

        // Check if Google analytics is blocked by uBlock or similar
        if (event.button === 0 && target && target.nodeName === 'A' && window.ga && window.ga.length) {
            // if click done with ctrl or shift key avoid preventDefault
            if (!event.ctrlKey && !event.shiftKey) {
                // get the target class list
                classList = target.classList;

                // If Quick view event don't get link redirection
                if (!classList.contains('quick-view') && !classList.contains('quick-view-mobile')) {
                    // retrieve the product link.
                    link = target.getAttribute('href');

                    if (link) {
                        // prevent redirection on normal click
                        event.preventDefault();
                    }
                }
            }

            // Get the product node
            productNode = delegateEvents(mainSelector, target);

            // Get variant node
            variantNode = delegateEvents(variantSelector, target);

            if (productNode) {
                idProduct = parseInt(productNode.getAttribute('data-id-product'));
                idProductAttribute = parseInt(productNode.getAttribute('data-id-product-attribute'));
            }

            // Check if any filter is applied
            list = checkFilters();

            if (!isNaN(idProduct)) {
                // If selected color variant
                if (variantNode) {
                    // get the attribute selected
                    variantAttribute = variantNode.getAttribute('data-id-product-attribute');

                    if (variantAttribute) {
                        // if exist update the id product attribute
                        idProductAttribute = variantAttribute;
                    }
                }

                // check if idProductAttribute has valid value
                if (isNaN(idProductAttribute)) {
                    idProductAttribute = 0;
                }

                // add the attribute to idProduct
                idProduct = idProduct + '-' + idProductAttribute;

                // Send data to GA without link redirection
                getData(caseClick, idProduct, list, link, null);
            } else if (link) {
                //If idProduct not detected redirect to product page
                document.location = link;
            }
        }
    }

    // PRODUCT LISTS - Add to cart
    function eventAddCartProductList(event) {
        var mainSelector = ['.ajax_block_product'];
        var eventSelectors = ['.ajax_add_to_cart_button'];
        var target = delegateEvents(eventSelectors, event.target);
        var caseClick = 2;
        var link;
        var productNode;
        var idProduct;
        var idProductAttribute;
        var list;

        if (event.button === 0 && target && target.nodeName === 'A' && window.ga && window.ga.length) {
            // if click done with ctrl or shift key avoid preventDefault
            if (!event.ctrlKey && !event.shiftKey) {
                // retrieve the product link.
                link = target.getAttribute('href');

                if (link) {
                    // prevent redirection on normal click
                    event.preventDefault();
                }
            }

            productNode = delegateEvents(mainSelector, target);

            if (productNode) {
                idProduct = parseInt(productNode.getAttribute('data-id-product'));
                idProductAttribute = parseInt(productNode.getAttribute('data-id-product-attribute'));
            }

            // Check if any filter is applied
            list = checkFilters();

            if (!isNaN(idProduct)) {
                // check if idProductAttribute has valid value
                if (isNaN(idProductAttribute)) {
                    idProductAttribute = 0;
                }

                // add the attribute to idProduct
                idProduct = idProduct + '-' + idProductAttribute;

                // Send data to GA
                getData(caseClick, idProduct, list, link, 1);

            } else {
                // if cartAjax disabled redirect to checkout
                if (link && !publicValues.trackingFeatures.cartAjax) {
                    document.location = link;
                }
            }
        }
    }

    // PRODUCT VIEW - View
    function eventProductView(event) {
        var target = document.body;
        var productViewList = publicValues.lists.productView;
        var idProductNode;
        var idProductAttributeNode;
        var idProductValue;
        var idProductAttributeValue;
        var idProductView;

        // set time out to be sure that executes after product updated
        setTimeout(function(){
            // retrieve document.body related to event to make compatible with iframe
            if (event) {
                // check if event comes from mouse or Content Loaded
                if (event.type === 'click') {
                    target = event.view.window.document.body
                } else {
                    // event from content loaded
                    target = event.target.body;
                }
            }
            idProductNode = target.querySelector('#product_page_product_id');
            idProductValue = idProductNode.value;
            idProductAttributeNode = target.querySelector('#idCombination');
            idProductAttributeValue = idProductAttributeNode.value || '0';

            // normalize id product to track
            idProductView = idProductValue + '-' + idProductAttributeValue;

            if (idProductView !== privateValues.lastIdProductView) {
                // check if is a quick-view window
                if (document.body.id !== 'product') {
                    productViewList = 'quick_view';
                }

                getData(5, idProductView, productViewList, null, null);
                privateValues.lastIdProductView = idProductView;
            }
        });
    }

    // PRODUCT VIEW - Add to cart
    function eventAddCartProductView(event) {
        var eventSelectors = ['#add_to_cart input', '#add_to_cart button'];
        var target = delegateEvents(eventSelectors, event.target);
        var caseClick = 2;
        var productViewList = publicValues.lists.productView;
        var productForm;
        var idProductNode;
        var idProductAttributeNode;
        var quantityWantedNode;
        var idProduct;
        var idProductAttribute;
        var quantityWanted;

        if (event.button === 0 && target && window.ga && window.ga.length) {
            // gets the buy block from product view or quick view
            productForm = event.currentTarget.querySelector('#buy_block');

            if (productForm) {
                idProductNode = productForm.querySelector('#product_page_product_id');
                idProductAttributeNode = productForm.querySelector('#idCombination');
                quantityWantedNode = productForm.querySelector('#quantity_wanted');

                idProduct = parseInt((idProductNode ? idProductNode.value : null));
                idProductAttribute = parseInt((idProductAttributeNode ? idProductAttributeNode.value : null));
                quantityWanted = parseInt((quantityWantedNode ? quantityWantedNode.value : '1'));

                if (!isNaN(idProduct)) {
                    // check if is a quick-view window
                    if (document.body.id !== 'product') {
                        productViewList = 'quick_view';
                    }

                    // check if idProductAttribute has valid value
                    if (isNaN(idProductAttribute)) {
                        idProductAttribute = 0;
                    }

                    // add the attribute to idProduct
                    idProduct = idProduct + '-' + idProductAttribute;

                    getData(caseClick, idProduct, productViewList, null, quantityWanted);
                }
            }
        }
    }

    // PRODUCT VIEW - Social actions
    function eventSocialShareProductView(event) {
        var eventSelectors = ['#send_friend_button', '.social-sharing'];
        var target = delegateEvents(eventSelectors, event.target);
        var network = 'email';

        if (event.button === 0 && target && window.ga && window.ga.length) {
            // check target is social sharing module
            if (target.classList.contains('social-sharing')) {
                network = target.getAttribute('data-type');
            }

            // check network value to avoid bad template
            // customizations on social sharing module
            if (network) {
                onSocialAction(network);
            }
        }
    }

    // PRODUCT VIEW - Wish list
    function eventWishListProductView(event) {
        var eventSelectors = ['#wishlist_button_nopop', '#wishlist_button'];
        var target = delegateEvents(eventSelectors, event.target);
        var caseClick = 4;
        var productViewList = publicValues.lists.productView;
        var productForm;
        var idProductNode;
        var idProductAttributeNode;
        var idProduct;
        var idProductAttribute;

        if (event.button === 0 && target && window.ga && window.ga.length) {
            // gets the buy block from product view or quick view
            productForm = event.currentTarget.querySelector('#buy_block');

            if (productForm) {
                idProductNode = productForm.querySelector('#product_page_product_id');
                idProductAttributeNode = productForm.querySelector('#idCombination');
                idProduct = parseInt((idProductNode ? idProductNode.value : null));
                idProductAttribute = parseInt((idProductAttributeNode ? idProductAttributeNode.value : null));

                if (!isNaN(idProduct)) {
                    // check if is a quick-view window
                    if (document.body.id !== 'product') {
                        productViewList = 'quick_view';
                    }

                    // check if idProductAttribute has valid value
                    if (isNaN(idProductAttribute)) {
                        idProductAttribute = 0;
                    }

                    // add the attribute to idProduct
                    idProduct = idProduct + '-' + idProductAttribute;

                    getData(caseClick, idProduct, productViewList, null, null);
                }
            }
        }
    }

    // BLOCKCART - REMOVE PRODUCT
    function eventRemoveFromCart(event) {
        var eventSelectors = ['.ajax_cart_block_remove_link'];
        var cartProductSelectors = ['[data-id^=cart_block_product_]'];
        var cartCustomizableSelectors = ['[data-id^=deleteCustomizableProduct_]'];
        var target = delegateEvents(eventSelectors, event.target);
        var caseClick = 3;
        var customizationId = 0;
        var idProduct = 0;
        var idProductAttribute = 0;
        var quantityRemoved = 1;
        var list = publicValues.lists.default;
        var firstCut;
        var productNode;
        var customizableProductNode;
        var link;
        var ids;

        if (event.button === 0 && target && target.nodeName === 'A' && window.ga && window.ga.length) {
            // if click done with ctrl or shift key avoid preventDefault
            if (!event.ctrlKey && !event.shiftKey) {
                // retrieve the product link.
                link = target.getAttribute('href');

                if (link) {
                    // prevent redirection on normal click
                    event.preventDefault();
                }
            }

            customizableProductNode = delegateEvents(cartCustomizableSelectors, target);

            // customizable product case
            if (customizableProductNode) {

                firstCut = customizableProductNode.getAttribute('data-id');
                ids = firstCut.split('_');

                if (typeof(ids[1]) !== 'undefined') {

                    customizationId = parseInt(ids[1]);
                    idProduct = parseInt(ids[2]);

                    if (typeof(ids[3]) !== 'undefined') {
                        idProductAttribute = parseInt(ids[3]);
                    }
                }
                // todo: get the quantity removed on customized product
            }

            // Common product management
            if (!customizationId) {

                productNode = delegateEvents(cartProductSelectors, target);

                quantityRemoved = parseInt(productNode.querySelector('.quantity').textContent.trim());

                //retrieve idProduct and idProductAttribute from the displayed product in the block cart
                firstCut = productNode.getAttribute('data-id');
                firstCut = firstCut.replace('cart_block_product_', '');
                firstCut = firstCut.replace('deleteCustomizableProduct_', '');

                ids = firstCut.split('_');

                idProduct = parseInt(ids[0]);

                if (typeof(ids[1]) !== 'undefined') {
                    idProductAttribute = parseInt(ids[1]);
                }
            }

            if (!isNaN(idProduct) && !isNaN(quantityRemoved)) {
                // check if idProductAttribute has valid value
                if (isNaN(idProductAttribute)) {
                    idProductAttribute = 0;
                }

                // add the attribute to idProduct
                idProduct = idProduct + '-' + idProductAttribute;

                if (document.body.id === 'product') {
                    list = publicValues.lists.productView;
                }

                // Get product Data and send to GA
                getData(caseClick, idProduct, list, link, quantityRemoved);
            } else {
                // if cartAjax disabled redirect to checkout
                if (link && !publicValues.trackingFeatures.cartAjax) {
                    document.location = link;
                }
            }
        }
    }

    // SUMMARY CART - Decrease product
    function eventCartQuantityDown(event) {
        var eventSelectors = ['.cart_quantity_down'];
        var target = delegateEvents(eventSelectors, event.target);
        var caseClick = 3;
        var quantityRemoved = 1;
        var firstCut;
        var ids;
        var idProduct;
        var idProductAttribute;

        if (event.button === 0 && target && window.ga && window.ga.length) {
            firstCut = target.getAttribute('id');
            ids = firstCut.split('_');

            idProduct = parseInt(ids[3]);

            if (typeof(ids[4]) !== 'undefined') {
                idProductAttribute = parseInt(ids[4]);
            }

            if (!isNaN(idProduct)) {
                // check if idProductAttribute has valid value
                if (isNaN(idProductAttribute)) {
                    idProductAttribute = 0;
                }

                // add the attribute to idProduct
                idProduct = idProduct + '-' + idProductAttribute;

                getData(caseClick, idProduct, publicValues.lists.default, null, quantityRemoved);
            }
        }
    }

    // SUMMARY CART - Increase product
    function eventCartQuantityUp(event) {
        var eventSelectors = ['.cart_quantity_up'];
        var target = delegateEvents(eventSelectors, event.target);
        var caseClick = 2;
        var quantityWanted = 1;
        var firstCut;
        var ids;
        var idProduct;
        var idProductAttribute;

        if (event.button === 0 && target && window.ga && window.ga.length) {
            firstCut = target.getAttribute('id');
            ids = firstCut.split('_');

            idProduct = parseInt(ids[3]);

            if (typeof(ids[4]) !== 'undefined') {
                idProductAttribute = parseInt(ids[4]);
            }

            if (!isNaN(idProduct)) {
                // check if idProductAttribute has valid value
                if (isNaN(idProductAttribute)) {
                    idProductAttribute = 0;
                }

                // add the attribute to idProduct
                idProduct = idProduct + '-' + idProductAttribute;

                getData(caseClick, idProduct, publicValues.lists.default, null, quantityWanted);
            }
        }
    }

    // SUMMARY CART - Remove product
    function eventCartQuantityDelete(event) {
        var eventSelectors = ['.cart_quantity_delete'];
        var mainNode = ['[id^=product_]'];
        var target = delegateEvents(eventSelectors, event.target);
        var caseClick = 3;
        var firstCut;
        var ids;
        var idProduct;
        var idProductAttribute;
        var quantityRemoved;

        if (event.button === 0 && target && window.ga && window.ga.length) {
            firstCut = target.getAttribute('id');
            ids = firstCut.split('_');

            idProduct = parseInt(ids[0]);

            if (typeof(ids[1]) !== 'undefined') {
                idProductAttribute = parseInt(ids[1]);
            }

            quantityRemoved = delegateEvents(mainNode, target);

            if (quantityRemoved) {
                quantityRemoved = quantityRemoved.querySelector('.cart_quantity input[type=hidden]');
                quantityRemoved = parseInt((quantityRemoved ? quantityRemoved.value : null));
            }

            if (!isNaN(idProduct) && !isNaN(quantityRemoved)) {
                // check if idProductAttribute has valid value
                if (isNaN(idProductAttribute)) {
                    idProductAttribute = 0;
                }

                // add the attribute to idProduct
                idProduct = idProduct + '-' + idProductAttribute;

                // send data to GA
                getData(caseClick, idProduct, publicValues.lists.default, null, quantityRemoved);
            }
        }
    }

    // CHECKOUT - STEP 3 - Carrier selection
    function eventCheckoutStepThree(){
        var cgv = document.querySelector('#cgv');
        var mainCarrierSelector = ['.delivery_option'];

        var shippingOption = '';

        var shippingNode;

        if (!cgv || cgv.checked) {
            // get shipping node
            shippingNode = document.querySelector('.delivery_option_radio:checked');

            // if virtual product don't has any shipping node
            if (shippingNode) {
                shippingNode = delegateEvents(mainCarrierSelector, shippingNode);
                shippingNode = shippingNode.querySelector('tr');

                shippingNode = shippingNode.children[2];

                // get shipping text
                shippingOption = (shippingNode ? shippingNode.textContent.trim() : '');
                shippingOption = normalizeText(shippingOption);
            }

            onCheckoutOptions(shippingOption, publicValues.shippingEventName, null);
        }
    }

    // CHECKOUT - STEP 4 - Payment selection
    function eventCheckoutStepFour(event){
        var eventSelectors = ['#HOOK_PAYMENT a', '#HOOK_PAYMENT input'];
        var advancedPayment = document.querySelector('#HOOK_ADVANCED_PAYMENT');

        var cgv;
        var target;
        var paymentMethod;
        var link;

        if (!advancedPayment) {
            target = delegateEvents(eventSelectors, event.target);
        } else {
            target = document.querySelector('#HOOK_ADVANCED_PAYMENT .payment_selected');
        }

        if (event.button === 0 && target && window.ga && window.ga.length) {
            cgv = document.querySelector('#cgv');

            if (!cgv || cgv.checked) {
                // if click done with ctrl or shift key avoid preventDefault
                if (!event.ctrlKey && !event.shiftKey) {
                    // get link for classic payments
                    link = (target.getAttribute('onclick') ? null : target.getAttribute('href'));

                    if (link) {
                        // prevent redirection on normal click
                        event.preventDefault();
                    }
                }

                // selected payment
                paymentMethod = target.getAttribute('title') || target.textContent.trim();
                paymentMethod = normalizeText(paymentMethod);

                onCheckoutOptions(paymentMethod, publicValues.paymentEventName, link);
            }
        }
    }

    // CHECKOUT - OCP - Customer signup
    function eventOpcSignUpPrestashop(event){
        var eventSelectors = ['#submitAccount', '#submitGuestAccount'];
        var target = delegateEvents(eventSelectors, event.target);

        if (event.button === 0 && target) {
            getSignUpGoal();
        }
    }

    // CHECKOUT - OPC - Options selected
    function eventOpcPrestashop(event){
        var eventSelectors = ['#HOOK_PAYMENT a', '#HOOK_PAYMENT input'];
        var mainCarrierSelector = ['.delivery_option'];
        var advancedPayment = document.querySelector('#HOOK_ADVANCED_PAYMENT');
        var shippingOption = '';

        var target;
        var cgv;
        var shippingNode;
        var paymentMethod;
        var opcOptionSelected;
        var link;

        if (!advancedPayment) {
            target = delegateEvents(eventSelectors, event.target);
        } else {
            target = document.querySelector('#HOOK_ADVANCED_PAYMENT .payment_selected');
        }

        if (event.button === 0 && target && window.ga && window.ga.length) {
            cgv = document.querySelector('#cgv');

            if (!cgv || cgv.checked) {
                // if click done with ctrl or shift key avoid preventDefault
                if (!event.ctrlKey && !event.shiftKey) {
                    // get link for classic payments
                    link = (target.getAttribute('onclick') ? null : target.getAttribute('href'));

                    if (link) {
                        // prevent redirection on normal click
                        event.preventDefault();
                    }
                }

                // get shipping node
                shippingNode = document.querySelector('.delivery_option_radio:checked');

                // if virtual product don't has any shipping node
                if (shippingNode) {
                    shippingNode = delegateEvents(mainCarrierSelector, shippingNode);
                    shippingNode = shippingNode.querySelector('tr');
                    shippingNode = shippingNode.children[2];

                    // get shipping text
                    shippingOption = (shippingNode ? shippingNode.textContent.trim() : '');
                    shippingOption = normalizeText(shippingOption);
                }

                // get selected payment
                paymentMethod = target.getAttribute('title') || target.textContent.trim();
                paymentMethod = normalizeText(paymentMethod);

                opcOptionSelected = paymentMethod + ' / ' + shippingOption;

                onCheckoutOptions(opcOptionSelected, publicValues.opcEventName, link);
            }
        }
    }

    // CHECKOUT - OPC - Options selected
    function eventOpcZelarg(event){

        var eventSelectors = ['.confirm_button', '#HOOK_PAYMENT a', '#HOOK_PAYMENT input'];
        var mainCarrierSelector = ['tr'];
        var mainPaymentSelector = ['tr'];
        var target = delegateEvents(eventSelectors, event.target);

        var shippingOption = '';
        var link = null;

        var cgv;
        var shippingMode;
        var shippingNode;
        var paymentMode;
        var paymentNode;
        var paymentMethod;
        var opcOptionSelected;

        if (event.button === 0 && target && window.ga && window.ga.length) {
            cgv = document.querySelector('#cgv');

            if (!cgv || cgv.checked) {
                shippingMode = document.querySelector('.carrier_name');
                paymentMode = document.querySelector('#HOOK_PAYMENT_PARSED');

                if (shippingMode) {
                    // shipping panel as Zelarg style

                    // get selected shipping node
                    shippingNode = document.querySelector('.carrier_action input:checked');

                    // if virtual product don't has any shipping node
                    if (shippingNode) {
                        shippingNode = delegateEvents(mainCarrierSelector, shippingNode);
                        shippingNode = shippingNode.querySelector('.carrier_name img') || shippingNode.querySelector('.carrier_name label');

                        // get selected shipping option
                        shippingOption = shippingNode.getAttribute('alt') || shippingNode.textContent.trim();
                    }
                } else {
                    // shipping panel as PS style

                    // get shipping node
                    shippingNode = document.querySelector('.delivery_option_radio:checked');

                    if (shippingNode) {
                        shippingNode = delegateEvents(mainCarrierSelector, shippingNode);
                        shippingNode = shippingNode.querySelector('tr');
                        shippingNode = shippingNode.children[2];

                        // get shipping text
                        shippingOption = (shippingNode ? shippingNode.textContent.trim() : '');
                    }
                }

                if (paymentMode) {
                    // payment panel as Zelarg style

                    // get selected payment node
                    paymentNode = document.querySelector('.payment_action input:checked');
                    paymentNode = delegateEvents(mainPaymentSelector, paymentNode);
                    paymentNode = paymentNode.querySelector('.payment_description label');

                    // get selected payment option
                    paymentMethod = (paymentNode ? paymentNode.textContent.trim() : '');
                } else {
                    // payment panel as PS style
                    link = (target.getAttribute('onclick') ? null : target.getAttribute('href'));

                    // get selected payment
                    paymentMethod = target.getAttribute('title') || target.textContent.trim();
                    paymentMethod = normalizeText(paymentMethod);
                }

                // clean text
                shippingOption = normalizeText(shippingOption);
                paymentMethod = normalizeText(paymentMethod);

                // concatenate options
                opcOptionSelected = paymentMethod + ' / ' + shippingOption;

                // if goal enabled check the customer sign up
                if (publicValues.trackingFeatures.goals.signUp) {
                    getSignUpGoal();
                }

                onCheckoutOptions(opcOptionSelected, publicValues.opcEventName, link);
            }
        }
    }

    // CHECKOUT - OPC - Options selected
    function eventOpcBestKit(event){

        var eventSelectors = ['#HOOK_PAYMENT a', '#HOOK_PAYMENT input'];
        var mainCarrierSelector = ['.shipping-delivery-item-opc'];
        var target = delegateEvents(eventSelectors, event.target);

        var shippingOption = '';
        var link = null;

        var cgv;
        var shippingNode;
        var paymentMethod;
        var opcOptionSelected;

        if (event.button === 0 && target && window.ga && window.ga.length) {
            cgv = document.querySelector('#cgv');

            if (!cgv || cgv.checked) {
                // if click done with ctrl or shift key avoid preventDefault
                if (!event.ctrlKey && !event.shiftKey) {
                    // get link for classic payments
                    link = (target.getAttribute('onclick') ? null : target.getAttribute('href'));

                    if (link) {
                        // prevent redirection on normal click
                        event.preventDefault();
                    }
                }

                // get shipping node
                shippingNode = document.querySelector('.delivery_option_radio:checked');

                // if virtual product don't has any shipping node
                if (shippingNode) {
                    shippingNode = delegateEvents(mainCarrierSelector, shippingNode);
                    shippingNode = shippingNode.querySelector('.shipping-title-opc');

                    // get shipping text
                    shippingOption = (shippingNode ? shippingNode.textContent.trim() : '');
                    shippingOption = normalizeText(shippingOption);
                }

                // get selected payment
                paymentMethod = target.getAttribute('title') || target.textContent.trim();
                paymentMethod = normalizeText(paymentMethod);

                opcOptionSelected = paymentMethod + ' / ' + shippingOption;

                // if goal enabled check the customer sign up
                if (publicValues.trackingFeatures.goals.signUp) {
                    getSignUpGoal();
                }

                onCheckoutOptions(opcOptionSelected, publicValues.opcEventName, link);
            }
        }
    }

    // CHECKOUT - OPC - Options selected
    function eventOpcPrestaTeam(event){
        var eventSelectors = ['#btn_place_order'];
        var mainCarrierSelector = ['.delivery_option'];
        var mainPaymentSelector = ['.module_payment_container'];
        var target = delegateEvents(eventSelectors, event.target);

        var shippingOption = '';

        var cgv;
        var shippingNode;
        var paymentNode;
        var paymentMethod;
        var opcOptionSelected;

        if (event.button === 0 && target && window.ga && window.ga.length) {
            cgv = document.querySelector('#cgv');

            if (!cgv || cgv.checked) {
                // get selected shipping node
                shippingNode = document.querySelector('.delivery_option input:checked');

                // if virtual product don't has any shipping node
                if (shippingNode) {
                    shippingNode = delegateEvents(mainCarrierSelector, shippingNode);
                    shippingNode = shippingNode.querySelector('.delivery_option_title');

                    // get selected shipping option
                    shippingOption = (shippingNode ? shippingNode.textContent.trim() : '');
                    shippingOption = normalizeText(shippingOption);
                }

                // get selected payment node
                paymentNode = document.querySelector('.payment_input input:checked');

                if (paymentNode) {
                    // continue only if payment selected
                    paymentNode = delegateEvents(mainPaymentSelector, paymentNode);
                    paymentNode = paymentNode.querySelector('.payment_content span');

                    // get selected payment option
                    paymentMethod = (paymentNode ? paymentNode.textContent.trim() : '');
                    paymentMethod = normalizeText(paymentMethod);

                    opcOptionSelected = paymentMethod + ' / ' + shippingOption;

                    // if goal enabled check the customer sign up
                    if (publicValues.trackingFeatures.goals.signUp) {
                        getSignUpGoal();
                    }

                    onCheckoutOptions(opcOptionSelected, publicValues.opcEventName, null);
                }
            }
        }
    }

    // CHECKOUT - OPC - Options selected
    function eventOpcSuperCheckout(event){
        var eventSelectors = ['#supercheckout_confirm_order'];
        var mainCarrierSelector = ['.highlight'];
        var mainPaymentSelector = ['.highlight'];
        var target = delegateEvents(eventSelectors, event.target);

        var shippingOption = '';

        var cgv;
        var shippingNode;
        var paymentNode;
        var paymentMethod;
        var opcOptionSelected;

        if (event.button === 0 && target && window.ga && window.ga.length) {
            cgv = document.querySelector('#cgv');

            if (!cgv || cgv.checked) {
                // get selected shipping node
                shippingNode = document.querySelector('.supercheckout_shipping_option:checked');

                // if virtual product don't has any shipping node
                if (shippingNode) {
                    shippingNode = delegateEvents(mainCarrierSelector, shippingNode);
                    shippingNode = shippingNode.querySelector('label img') || shippingNode.querySelector('label');

                    // get selected shipping option
                    shippingOption = (shippingNode ? shippingNode.getAttribute('alt') || shippingNode.textContent.trim() : '');
                    shippingOption = normalizeText(shippingOption);
                }

                // get selected payment node
                paymentNode = document.querySelector('#payment-method input:checked');
                paymentNode = delegateEvents(mainPaymentSelector, paymentNode);
                paymentNode = paymentNode.querySelector('label img') || paymentNode.querySelector('label span');

                // get selected payment option
                paymentMethod = (paymentNode ? paymentNode.getAttribute('alt') ||  paymentNode.textContent.trim() : '');
                paymentMethod = normalizeText(paymentMethod);

                opcOptionSelected = paymentMethod + ' / ' + shippingOption;

                // if goal enabled check the customer sign up
                if (publicValues.trackingFeatures.goals.signUp) {
                    // todo: this payment mod has a bad account creation, find a solution
                    getSignUpGoal();
                }

                onCheckoutOptions(opcOptionSelected, publicValues.opcEventName, null);
            }
        }
    }

    /////////////////////////////////////////////
    // TRACKING METHODS

    // SEARCH RESULT - get the search term
    function onSearchResults() {
        var eventName = 'view_search_results';
        var eventParams = {};
        var searchTerm;

        // verify that page is search
        if (document.body.id === 'search') {
            searchTerm = getSearchTerm();
            if (searchTerm) {
                eventParams.search_term = searchTerm;
                sendGtagEvent(eventName, eventParams);
            }
        }
    }

    // PRODUCT LISTS - scroll tracking
    function onScrollTracking(products) {
        var eventName = 'view_item_list';
        var eventParams;
        var eventDimensions;
        var sendNow;

        // check if is an array and is not empty
        if (Array.isArray(products) && products.length) {
            while (products.length > 0) {
                // get products to send
                sendNow = products.splice(0, publicValues.trackingFeatures.productSendRate);

                // init params to avoid send duplicates
                eventParams = {
                    // add non_interaction to fix bounce rates
                    'non_interaction': 1,
                    'items': []
                };

                // parse all products to send
                sendNow.forEach(function (product) {
                    // set product on params
                    eventParams.items.push(productLayer(product));
                });

                // set remarketing dimensions to params
                if (publicValues.trackingFeatures.config.remarketing) {
                    eventDimensions = setRemarketingDimensions(sendNow, publicValues.ecommPageType);
                    // add custom dimensions to params
                    Object.assign(eventParams, eventDimensions);
                }

                // send gtag event
                sendGtagEvent(eventName, eventParams);
            }
        }
    }

    // PRODUCT LISTS - product view click
    function onProductClick(product, link) {
        var eventName = 'select_content';
        var eventParams = {
            'content_type': 'product',
            'items': []
        };

        // set product on params
        eventParams.items.push(productLayer(product));

        if (link) {
            // add redirect to product page.
            privateValues.redirectLink = link;
            eventParams['event_callback'] = callbackWithTimeout(
                function() {
                    redirectLink();
                },
                2000
            );
        }

        // send gtag event
        sendGtagEvent(eventName, eventParams);
    }

    // PRODUCT VIEW - view product on their product page
    function onProductView(product) {
        var eventName = 'view_item';
        var eventParams = {
            'non_interaction': 1,
            'items': []
        };
        var ecommPageType = publicValues.ecommPageType;
        var eventDimensions;

        // set product on params
        eventParams.items.push(productLayer(product));

        if (publicValues.trackingFeatures.config.remarketing) {
            if (product.list_name === 'quick_view') {
                ecommPageType = 'product';
            }
            eventDimensions = setRemarketingDimensions([product], ecommPageType);
            // add custom dimensions to params
            Object.assign(eventParams, eventDimensions);
        }
        // send gtag event
        sendGtagEvent(eventName, eventParams);
    }

    // PRODUCT VIEW - social network action
    function onSocialAction(network) {
        var eventName = 'share';
        var eventParams = {
            'method' : network,
            'event_value' : publicValues.trackingFeatures.eventValues.socialAction
        };

        if (publicValues.trackingFeatures.goals.socialAction) {
            sendGtagEvent(eventName, eventParams);
        }
    }

    // PRODUCT VIEW - wish list
    function onWishList(product) {
        var eventName = 'add_to_wishlist';
        var eventParams = {
            'items' : [],
            'value' : publicValues.trackingFeatures.eventValues.wishList
        };

        // set product on params
        eventParams.items.push(productLayer(product));

        // send gtag event
        sendGtagEvent(eventName, eventParams);
    }

    // COMMON - new customer registration
    function onSignUp() {
        // todo check how interact with AdWords
        var index = publicValues.trackingFeatures.isGuest;
        var customerType = publicValues.trackingFeatures.signUpTypes[index];
        var eventName = 'sign_up';
        var eventParams = {
            'method' : customerType,
            'value' : publicValues.trackingFeatures.eventValues.signUp
        };

        if (publicValues.trackingFeatures.goals.signUp && publicValues.trackingFeatures.isNewSignUp) {
            // send data layer
            sendGtagEvent(eventName, eventParams);

            // reset values to avoid multiple sends
            publicValues.trackingFeatures.isNewSignUp = 0;
            publicValues.trackingFeatures.isGuest = 0;
        }
    }

    // COMMON - add to cart on product click
    function onAddToCart(product, link) {
        var eventName = 'add_to_cart';
        var eventParams = {
            'items': []
        };
        var eventDimensions = {};
        var ecommPageType = 'cart';

        // set product on params
        eventParams.items.push(productLayer(product));

        // set remarketing dimensions to params
        if (publicValues.trackingFeatures.config.remarketing) {
            eventDimensions = setRemarketingDimensions([product], ecommPageType);
            // add custom dimensions to params
            Object.assign(eventParams, eventDimensions);
        }

        // Send data using an event.
        if (!publicValues.trackingFeatures.cartAjax && link) {
            privateValues.redirectLink = link;
            eventParams['event_callback'] = callbackWithTimeout(
                function() {
                    redirectLink();
                },
                2000
            );
        }

        // send gtag event
        sendGtagEvent(eventName, eventParams);
    }

    // COMMON - remove from cart click
    function onRemoveFromCart(product, link) {
        var eventName = 'remove_from_cart';
        var eventParams = {
            'items': []
        };

        // set product on params
        eventParams.items.push(productLayer(product));

        // Send data using an event.
        if (!publicValues.trackingFeatures.cartAjax && link) {
            privateValues.redirectLink = link;
            eventParams['event_callback'] = callbackWithTimeout(
                function() {
                    redirectLink();
                },
                2000
            );
        }

        // send gtag event
        sendGtagEvent(eventName, eventParams);
    }

    // CHECKOUT - send products and actual checkout step
    function onCheckoutProducts(products) {
        var eventName = 'checkout_progress';
        var eventParams;
        var eventDimensions;
        var sendNow;

        if (publicValues.pageStep === 1) {
            eventName = 'begin_checkout'
        }

        // check if is an array and is not empty
        if (Array.isArray(products) && products.length) {
            while (products.length > 0) {
                // get products to send
                sendNow = products.splice(0, publicValues.trackingFeatures.productSendRate);

                // init params to avoid send duplicates
                eventParams = {
                    'checkout_step': publicValues.pageStep,
                    'items': []
                };

                // parse all products to send
                sendNow.forEach(function (product) {
                    // set product on params
                    eventParams.items.push(productLayer(product));
                });

                // set remarketing dimensions to params
                if (publicValues.trackingFeatures.config.remarketing) {
                    eventDimensions = setRemarketingDimensions(sendNow, publicValues.ecommPageType);
                    // add custom dimensions to params
                    Object.assign(eventParams, eventDimensions);
                }

                // send gtag event
                sendGtagEvent(eventName, eventParams);
            }
        }
    }

    // CHECKOUT - option selected by customer
    function onCheckoutOptions(checkoutOption, checkoutValue, link) {
        var eventName = 'set_checkout_option';
        var eventParams = {
            'checkout_step': publicValues.pageStep,
            'checkout_option': checkoutOption,
            'value': checkoutValue,
            'items': []
        };

        if (link !== null) {
            privateValues.redirectLink = link;
            eventParams['event_callback'] = callbackWithTimeout(
                function() {
                    redirectLink();
                },
                2000
            );
        }

        // send gtag event
        sendGtagEvent(eventName, eventParams);
    }

    // CHECKOUT - process order data
    function onAddOrder(order, products, idShop) {
        var eventName = 'purchase';
        var adWordsConversionEventName = 'conversion';
        var adWordsConversionParams = {
            'transaction_id': order.transaction_id,
            'value': order.value,
            'currency': publicValues.trackingFeatures.currency
        };
        var productLength = products.length;
        var firstLoop = 1;
        var eventDimensions;
        var eventParams;
        var sendNow;

        if (Array.isArray(products) && products.length) {
            while (products.length > 0) {
                // get products to send
                sendNow = products.splice(0, publicValues.trackingFeatures.productSendRate);

                // init params to avoid send duplicates
                eventParams = {
                    'items': [],
                    'send_to': publicValues.trackingFeatures.analyticsId
                };

                // add event callback on first loop
                if (firstLoop) {
                    eventParams['event_callback'] = callbackWithTimeout(
                        function() {
                            setOrderInDb(order.transaction_id, idShop);
                        },
                        1000
                    );
                    firstLoop = 0;
                }

                // set order values to params
                Object.keys(order).forEach(function (key) {
                    if (order[key] !== null && order[key] !== false) {
                        // if order is spliced don't save value key
                        if (key === 'value' && productLength > publicValues.trackingFeatures.productSendRate) {
                            // skip value key
                            return;
                        }
                        eventParams[key] = order[key];
                    }
                });

                // parse all products to send
                sendNow.forEach(function (product) {
                    // set product on params
                    eventParams.items.push(productLayer(product));
                });

                // send remarketing dimensions to params
                if (publicValues.trackingFeatures.config.remarketing) {
                    eventDimensions = setRemarketingDimensions(sendNow, publicValues.ecommPageType);
                    // add custom dimensions to params
                    Object.assign(eventParams, eventDimensions);
                }

                // send gtag event
                sendGtagEvent(eventName, eventParams);
            }

            // after process ga transaction send AdWords transaction
            if (publicValues.trackingFeatures.adwordsId && publicValues.trackingFeatures.adwordsCl) {
                // required to process adwords conversion
                adWordsConversionParams['send_to'] =
                    publicValues.trackingFeatures.adwordsId +
                    '/' +
                    publicValues.trackingFeatures.adwordsCl
                ;

                sendGtagEvent(adWordsConversionEventName, adWordsConversionParams);
            }
        }
    }

    // TRACKING - Configure GTAG
    function sendGtagConfig(configId) {
        var configElement = publicValues.trackingFeatures[configId];
        var configFeatures = publicValues.trackingFeatures.config;
        var configParams = {};
        var doNotTrack = (publicValues.trackingFeatures.checkDoNotTrack && privateValues.doNotTrack);

        if (publicValues.trackingFeatures.disableInternalTracking || doNotTrack) {
            window['ga-disable-' + publicValues.trackingFeatures.analyticsId] = true;
        }

        // add params for analytics configuration
        if (configId === 'analyticsId') {
            // add configParams
            configParams['site_speed_sample_rate'] = configFeatures.simpleSpeedSampleRate;
            configParams['anonymize_ip'] = configFeatures.anonymizeIp;
            configParams['link_attribution'] = configFeatures.linkAttribution;

            if (configFeatures.userIdFeature) {
                configParams['user_id'] = configFeatures.userIdValue;
            }

            if (configFeatures.remarketing) {
                configParams['custom_map'] = {};
                configParams.custom_map['dimension' + configFeatures.customDimensions['ecommProdId']] = 'ecomm_prodid';
                configParams.custom_map['dimension' + configFeatures.customDimensions['ecommPageType']] = 'ecomm_pagetype';
                configParams.custom_map['dimension' + configFeatures.customDimensions['ecommTotalValue']] = 'ecomm_totalvalue';
                configParams.custom_map['dimension' + configFeatures.customDimensions['ecommCategory']] = 'ecomm_category';
            } else {
                configParams['allow_display_features'] = configFeatures.remarketing;
            }

            if (Array.isArray(configFeatures.crossDomainList) && configFeatures.crossDomainList.length) {
                configParams['linker'] = {'domains': configFeatures.crossDomainList};
            }

            if (configFeatures.optimizeId) {
                configParams['optimize_id'] = configFeatures.optimizeId;
            }

            // set permanent values
            configParams['currency'] = publicValues.trackingFeatures.currency;
        } else if (configId === 'adwordsId') {
            // avoid send remarketing hit on page view to AdWords
            configParams['send_page_view'] = false;
        }

        if (configElement) {
            gtag('config', configElement, configParams);
        }
    }

    // TRACKING - Send event
    function sendGtagEvent(eventName, eventParams) {
        // send event to analytics
        gtag('event', eventName, eventParams);
    }

    /////////////////////////////////////////////
    // TRACKING TOOLS

    // GENERAL - get product model
    function productLayer(product) {
        var productKeys = [
            'id',
            'name',
            'variant',
            'brand',
            'category',
            'list_name',
            'list_position',
            'quantity',
            'price',
            'coupon'
        ];
        var gaProduct = {};

        // populate the ga productFieldObject
        productKeys.forEach(function(key){
            if (product[key] != null) {
                gaProduct[key] = product[key];
            }
        });

        return gaProduct;
    }

    // GENERAL - get remarketing dimensions
    function setRemarketingDimensions(products, ecommPageType) {
        var remarketingDimensions = {
            'ecomm_prodid': [],
            'ecomm_pagetype': ecommPageType
        };
        var ecomm_totalvalue = 0;
        var productPrice = 0;
        var idProduct;

        products.forEach(function(product){
            // basic id product
            idProduct = product.id;

            // check if product has variant
            if (publicValues.trackingFeatures.merchantVariant && product.id_attribute) {
                // set id product with variant
                idProduct = product.id + publicValues.trackingFeatures.merchantVariant + product.id_attribute;
            }

            // add product dimension
            remarketingDimensions.ecomm_prodid.push(
                publicValues.trackingFeatures.merchantPrefix +
                idProduct +
                publicValues.trackingFeatures.merchantSuffix
            );

            if (ecommPageType === 'cart' ||
                (ecommPageType === 'product' && product.list_name === 'product_page') ||
                (ecommPageType === 'product' && product.list_name === 'quick_view') ||
                ecommPageType === 'purchase'
            ) {
                // set basic product price
                productPrice = product.price;

                // check if product have quantity
                if (product.quantity) {
                    productPrice = productPrice * product.quantity;
                }
                // calc total_value dimension
                ecomm_totalvalue = (ecomm_totalvalue + productPrice);
                // update remarketingDimension and cut ecomm_totalvalue to 2 decimals
                remarketingDimensions.ecomm_totalvalue = parseFloat(ecomm_totalvalue.toFixed(2));
            }

            // set ecomm_category only on category or product pages
            if ((ecommPageType === 'category' ||
                ecommPageType === 'product') &&
                product.category
            ) {
                remarketingDimensions.ecomm_category = product.category;
            }
        });

        return remarketingDimensions;
    }

    // SEARCH RESULT - get the search term
    function getSearchTerm() {
        var searchWordNode;
        var searchTerm;

        if (document.body.id === 'search') {
            searchWordNode = document.querySelector('#search_query_top');
            searchTerm = searchWordNode.value || null;
        }
        return searchTerm;
    }

    /////////////////////////////////////////////
    // AJAX REQUEST

    // AJAX - get Product data and send to GA
    function getData(caseClick, idProducts, list, link, quantityWanted) {
        var req = new XMLHttpRequest();
        var url = privateValues.moduleUrl + 'rcpganalytics-ajax.php';
        var data = {
            'action': 'product',
            'products_position': privateValues.productsPosition,
            'list': list,
            'quantity_wanted': quantityWanted,
            'products_list_cache': publicValues.productsListCache
        };
        var formData;
        var response;
        var type;

        if (typeof idProducts === 'object') {
            // for products lists
            data['id_products'] = idProducts;
        } else {
            // for product page or events
            data['id_products'] = [idProducts];
        }

        formData = new FormData();
        formData.append('data', JSON.stringify(data));
        formData.append('token', publicValues.trackingFeatures.token);

        req.open('POST', url, true);
        req.onreadystatechange = function () {
            try {
                if (req.status === 200) {
                    if (req.readyState === 4) {
                        type = req.getResponseHeader('Content-Type');
                        if (type === 'application/json') {
                            response = JSON.parse(req.responseText);
                            if (typeof response === 'object') {
                                if (caseClick === 0) {
                                    onScrollTracking(response);
                                } else if (caseClick === 1) {
                                    onProductClick(response[0], link);
                                } else if (caseClick === 2) {
                                    onAddToCart(response[0], link);
                                } else if (caseClick === 3) {
                                    onRemoveFromCart(response[0], link);
                                } else if (caseClick === 4) {
                                    onWishList(response[0]);
                                } else if (caseClick === 5) {
                                    onProductView(response[0]);
                                }
                            }
                        } else {
                            throw 'response is not an JSON object';
                        }
                    }
                } else {
                    throw 'Unexpected XHR error';
                }
            } catch (error) {
                console.warn('rcpganalytics: ' + error);
                if (link) {
                    // add redirect to product page.
                    privateValues.redirectLink = link;
                    redirectLink();
                }
            }
        };
        req.send(formData);
    }

    // AJAX - get some goal data
    function getSignUpGoal() {
        var req = new XMLHttpRequest();
        var url = privateValues.moduleUrl + 'rcpganalytics-ajax.php';
        var data = {
            'action' : 'signUp',
            'maxLapse' : publicValues.trackingFeatures.maxLapse
        };
        var formData;
        var response;
        var type;

        // set 1s delay to allow PS create the account
        setTimeout(function(){
                formData = new FormData();
                formData.append('data', JSON.stringify(data));
                formData.append('token', publicValues.trackingFeatures.token);

                req.open('POST', url, true);
                req.onreadystatechange = function () {
                    if (req.readyState === 4 && req.status === 200) {
                        type = req.getResponseHeader('Content-Type');
                        if (type === 'application/json') {
                            response = JSON.parse(req.responseText);
                            if (typeof response === 'object') {
                                if (response.isNewSignUp) {
                                    // set response data to publicValues
                                    publicValues.trackingFeatures.isNewSignUp = response.isNewSignUp;
                                    publicValues.trackingFeatures.isGuest = response.isGuest;

                                    // process signUp goal
                                    onSignUp();
                                }
                            }
                        }
                    }
                };
                req.send(formData);
            }, 1000
        );
    }

    // Ajax Call - after sent transaction to GA set order data in DB
    function setOrderInDb(orderId, idShop) {
        var req = new XMLHttpRequest();
        var url = privateValues.moduleUrl + 'rcpganalytics-ajax.php';
        var data = {
            'action': 'orderComplete',
            'is_order': true,
            'id_order': orderId,
            'id_shop': idShop,
            'id_customer': publicValues.trackingFeatures.config.userIdValue
        };
        var adBlocker = (!window.ga || !window.ga.length);
        var doNotTrack = (publicValues.trackingFeatures.checkDoNotTrack && privateValues.doNotTrack);
        var formData;

        // check if ga is loaded
        if (doNotTrack || adBlocker) {
            data.action = 'abortedTransaction';
            data.doNotTrack = privateValues.doNotTrack;
            data.adBlocker = adBlocker;
        }

        formData = new FormData();
        formData.append('data', JSON.stringify(data));
        formData.append('token', publicValues.trackingFeatures.token);

        req.open('POST', url, true);
        req.send(formData);
    }

    // Ajax Call - check if clientId exist and set to control DB
    function setClientIdInDb() {
        var clientId;
        var trackers;
        var req;
        var url;
        var data;
        var formData;

        // fire if ga is defined
        if (window.ga) {
            ga(function() {
                // get all trackers
                trackers = ga.getAll();
                // check is trackers is an Array and is not empty
                if (Array.isArray(trackers) && trackers.length) {
                    // get clientId of customer
                    clientId = trackers[0].get('clientId');

                    if (clientId && clientId !== publicValues.trackingFeatures.clientId) {
                        req = new XMLHttpRequest();
                        url = privateValues.moduleUrl + 'rcpganalytics-ajax.php';
                        data = {
                            'action': 'clientId',
                            'id_customer': publicValues.trackingFeatures.config.userIdValue,
                            'id_shop': publicValues.trackingFeatures.idShop,
                            'client_id': clientId
                        };

                        formData = new FormData();
                        formData.append('data', JSON.stringify(data));
                        formData.append('token', publicValues.trackingFeatures.token);

                        req.open('POST', url, true);
                        // setRequestHeader breaks the formData object, don't add it
                        req.send(formData);
                    }
                }
            });
        }
    }

    /////////////////////////////////////////////
    // EVENTS - TOOLS

    // SCROLL - Detect products on scroll
    function scrollProductDetection() {
        var products = document.querySelectorAll('.ajax_block_product');
        var idProductNotDetected = false;
        var idProductAttributeNotDetected = false;
        var winHeight = window.innerHeight;
        var winOffset = window.pageYOffset;
        var minY = winOffset;
        var maxY = winOffset + winHeight;
        var itemTop;
        var itemBottom;
        var visibleProduct;
        var elHeight;
        var elComputedStyle;
        var elHeightPadding;
        var rect;
        var idProduct;
        var idProductAttribute;

        products.forEach(function(product){
            // size of inner height including padding
            elHeight = product.clientHeight;

            // if elHeight === 0 means product are not visible
            if (elHeight) {
                // get computed styles to retrieve the real padding applied on css styles.
                elComputedStyle = getComputedStyle(product);

                // sum the top and bottom padding to get the height padding
                elHeightPadding = parseInt(elComputedStyle.paddingTop) + parseInt(elComputedStyle.paddingBottom);

                // calc product display position
                rect = product.getBoundingClientRect();
                itemTop = rect.top + document.body.scrollTop;
                itemBottom = itemTop + (elHeight - elHeightPadding);

                // check if product is inside display
                if ((itemTop >= minY && itemTop < maxY) || (itemBottom >= minY && itemBottom < maxY)) {
                    // get product data
                    idProduct = parseInt(product.getAttribute('data-id-product'));
                    idProductAttribute = parseInt(product.getAttribute('data-id-product-attribute'));

                    if (isNaN(idProductAttribute)) {
                        idProductAttribute = 0;
                        idProductAttributeNotDetected = true;
                    }

                    if (!isNaN(idProduct)) {
                        // set product index format
                        visibleProduct = idProduct + '-' + idProductAttribute;

                        // check that product has not sent and is not a duplicate
                        if (privateValues.sentProducts.indexOf(visibleProduct) === -1 &&
                            privateValues.sendProducts.indexOf(visibleProduct) === -1) {
                            privateValues.sendProducts.push(visibleProduct);
                        }
                    } else {
                        idProductNotDetected = true;
                    }
                }
            }
        });
    }
    // SCROLL - Get initial product position
    function getInitPosition() {
        var pagination;
        var itemsNumber;

        pagination = document.querySelector('#pagination .active span span');
        pagination = (pagination ? pagination.textContent.trim() : 1);

        // PS16 have 2 basic versions
        itemsNumber =
            document.querySelector('#nb_page_items option:checked') ||
            document.querySelector('#nb_item option:checked');

        itemsNumber = (itemsNumber ? itemsNumber.value : 1);

        // get the first product position
        return (parseInt(itemsNumber) * parseInt(pagination)) - parseInt(itemsNumber) + 1;
    }
    // SCROLL - Calc product position
    function scrollProductPositionDetection() {
        // populate productsPosition counting
        // every product with class .ajax_block_product
        var products = document.querySelectorAll('.ajax_block_product');
        var actualPosition = getInitPosition();
        var productKey;
        var idProduct;
        var idProductAttribute;

        products.forEach(function(product){
            idProduct = parseInt(product.getAttribute('data-id-product'));
            idProductAttribute = parseInt(product.getAttribute('data-id-product-attribute'));

            if (isNaN(idProductAttribute)) {
                idProductAttribute = 0;
            }

            if (!isNaN(idProduct)) {
                productKey = idProduct + '-' + idProductAttribute;

                // check if productsPosition has the product ID as key
                if(!privateValues.productsPosition.hasOwnProperty(productKey)) {
                    privateValues.productsPosition[productKey] = actualPosition;
                    actualPosition ++;
                }
            }
        });
    }
    // SCROLL - Launch event
    function doneScroll() {
        var caseClick = 0;
        var list;

        // calculate products position in each scroll for possible lazy loads products
        scrollProductPositionDetection();

        // check if exists new products to send
        if (privateValues.sendProducts.length > 0) {
            list = checkFilters();
            // process data to gtag
            getData(caseClick, privateValues.sendProducts, list, null, null);
            // add new products to sent list
            Array.prototype.push.apply(privateValues.sentProducts, privateValues.sendProducts);
            // reset sendProducts to avoid multiple sends
            privateValues.sendProducts = [];
        }

        clearTimeout(privateValues.scrollTimeout);
    }

    function checkFilters() {
        var list = publicValues.lists.default;
        var isEnabledFilter = document.querySelector('#enabled_filters');
        var pmAdvancedSearch = document.querySelector('.PM_ASResetGroup');

        if (isEnabledFilter || pmAdvancedSearch) {
            list = publicValues.lists.filter;
        } else if (document.body.id === 'search') {
            list = publicValues.lists.search;
            publicValues.ecommPageType = 'searchresults';
        }
        return list;
    }

    ///////////////////////
    // COMMON TOOLS

    // GENERAL - redirect to new location
    function redirectLink() {
        if (!privateValues.redirected) {
            // set flag to avoid multiple redirection
            privateValues.redirected = true;
            window.location = privateValues.redirectLink;
        }
    }

    // GENERAL - timeout method to avoid page blocking
    function callbackWithTimeout(callback, timeout) {
        var called = false;

        function fn() {
            if (!called) {
                called = true;
                callback();
            }
        }
        setTimeout(fn, timeout || 1000);

        return fn;
    }

    // Remove extra spaces
    function normalizeText(text) {
        var filtered = '';

        if (typeof text === 'string') {
            filtered = text.replace(/^\s+|\n+.*/g, '').trim();
        }

        return filtered;
    }

    // Like JQ closest
    function delegateEvents(selectors, target) {
        var matchMode;

        if (target) {
            // get available browser matches function
            matchMode = target.matches || target.webkitMatchesSelector || target.msMatchesSelector;

            // get function name (general browsers || iE9)
            matchMode = matchMode.name || /function\s+([\w\$]+)\s*\(/.exec( matchMode.toString() );

            // on iE9 get the name value, empty value on anonymous fn
            if (typeof matchMode !== 'string') {
                matchMode = matchMode ? matchMode[1] : '';
            }

            // continue only if we get matches selector function
            if (matchMode) {
                while (target.parentNode !== null) {
                    if (target.nodeType === 1) {
                        // iterate all selectors
                        for (var i = 0; i < selectors.length; i++) {
                            // compare if node match with selector
                            if (target[matchMode](selectors[i])) {
                                // if match return target
                                return target;
                            }
                        }
                    }
                    // if no match or nodeType !== 1 go to parent
                    target = target.parentNode;
                }
            }
        }
    }
}
