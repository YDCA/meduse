{*
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
*}
<script type="text/javascript">
    // Instantiate the tracking class
    var rcAnalyticsEvents = new RcAnalyticsEvents();

    // page controller
    var controllerName = '{$controller_name|escape:"html":"UTF-8"}';
    var compliantModuleName = '{$compliant_module_name|escape:"html":"UTF-8"}';
    var isOrder = {$is_order|intval};
    var isCheckout = {$is_checkout|intval};
    var isClientId = {$is_client_id|intval};
    var pageTrack;
    var gaProducts;
    var gaOrder;
    var productAttributesNode;
    var checkoutEvent;
    ////////////////////////////

    // pass tracking features
    rcAnalyticsEvents.trackingFeatures = {$gtag_tracking_features|json_encode nofilter};

    // list names
    rcAnalyticsEvents.lists = {$lists|json_encode};

    // Google remarketing - page type
    rcAnalyticsEvents.ecommPageType = '{$ecomm_pagetype|escape:"html":"UTF-8"}';

    // init checkout values
    rcAnalyticsEvents.shippingEventName = '{$shipping_event|escape:"html":"UTF-8"}';
    rcAnalyticsEvents.paymentEventName = '{$payment_event|escape:"html":"UTF-8"}';
    rcAnalyticsEvents.opcEventName = '{$opc_event|escape:"html":"UTF-8"}';
    rcAnalyticsEvents.pageStep = {$checkout_step|intval};
    pageTrack = '{$page_track|escape:"html":"UTF-8"}';

    {if isset($products_list_cache)}
        // get products list to cache
        rcAnalyticsEvents.productsListCache = {$products_list_cache|json_encode nofilter};
    {/if}

    // Listing products
    {if isset($ga_products)}
    // checkout pages
    gaProducts = {$ga_products|json_encode nofilter};
    {/if}

    {if isset($ga_order)}
    // Order complete
    gaOrder = {$ga_order|json_encode nofilter};
    {/if}
    /////////////////////////////////

    // init gtag
    rcAnalyticsEvents.sendGtagConfig('analyticsId');
    rcAnalyticsEvents.sendGtagConfig('adwordsId');

    ///////////////////////////////////////////////

    // Initialize all user events when DOM ready
    document.addEventListener('DOMContentLoaded', initGaEvents, false);
    window.addEventListener('pageshow', fireEventsOnPageShow, false);
    ///////////////////////////////////////////////
    function initGaEvents() {
        // remove from cart event is visible on all pages
        // Events binded to document.body to avoid firefox fire events on right/central click
        document.body.addEventListener('click', rcAnalyticsEvents.eventRemoveFromCart, false);
        ////////////////////////

        // ALL PAGES EXCEPT CHECKOUT OR ORDER
        if (!isCheckout && !isOrder) {
            // init first scroll action for those products all ready visible on screen
            rcAnalyticsEvents.eventScrollList();
            // bind event to scroll
            window.addEventListener('scroll', rcAnalyticsEvents.eventScrollList.bind(rcAnalyticsEvents), false);

            // init Event Listeners
            document.body.addEventListener('click', rcAnalyticsEvents.eventClickProductList, false);
            document.body.addEventListener('click', rcAnalyticsEvents.eventAddCartProductList, false);

            ////////////////////////
            // SEARCH PAGE
            if (controllerName === 'search') {
                rcAnalyticsEvents.onSearchResults();
            }
            ////////////////////////
            // PRODUCT PAGE
            if (controllerName === 'product') {
                // send product view
                rcAnalyticsEvents.eventProductView();

                productAttributesNode = document.querySelector('#attributes');
                if (productAttributesNode) {
                    productAttributesNode.addEventListener('click', rcAnalyticsEvents.eventProductView, false);
                }

                document.body.addEventListener('click', rcAnalyticsEvents.eventAddCartProductView, false);

                if (rcAnalyticsEvents.trackingFeatures.goals.socialAction) {
                    document.body.addEventListener('click', rcAnalyticsEvents.eventSocialShareProductView, false);
                }
                if (rcAnalyticsEvents.trackingFeatures.goals.wishList) {
                    document.body.addEventListener('click', rcAnalyticsEvents.eventWishListProductView, false);
                }
            }
        }

        ////////////////////////
        // CHECKOUT PAGE
        if (isCheckout) {
            // Common events for checkout process
            if (rcAnalyticsEvents.pageStep === 1 || rcAnalyticsEvents.pageStep === 4) {
                // events on summary Cart
                document.body.addEventListener('click', rcAnalyticsEvents.eventCartQuantityDelete, false);
                document.body.addEventListener('click', rcAnalyticsEvents.eventCartQuantityUp, false);
                document.body.addEventListener('click', rcAnalyticsEvents.eventCartQuantityDown, false);
            }

            // specific events for 5 step checkout
            if (controllerName === 'order') {
                if (rcAnalyticsEvents.pageStep === 3) {
                    // Detect the carrier selected
                    checkoutEvent = document.querySelector('button[name="processCarrier"]');
                    checkoutEvent.addEventListener('click', rcAnalyticsEvents.eventCheckoutStepThree, false);

                } else if (rcAnalyticsEvents.pageStep === 4) {
                    // Detect payment method classic || EU compliance (advanced checkout)
                    checkoutEvent = document.querySelector('#HOOK_PAYMENT') || document.querySelector('#confirmOrder');
                    checkoutEvent.addEventListener('click', rcAnalyticsEvents.eventCheckoutStepFour, false);
                }

            } else if (controllerName === 'orderopc' && !compliantModuleName) {
                // OPC Prestashop
                if (rcAnalyticsEvents.trackingFeatures.goals.signUp) {
                    // event to check signUp
                    document.body.addEventListener('click', rcAnalyticsEvents.eventOpcSignUpPrestashop, false);
                }
                // Detect carrier selected and payment method in OPC
                checkoutEvent = document.querySelector('#HOOK_PAYMENT') || document.querySelector('#confirmOrder');
                // avoid error when cart is empty
                if (checkoutEvent) {
                    checkoutEvent.addEventListener('click', rcAnalyticsEvents.eventOpcPrestashop, false);
                }
            } else if (controllerName === 'orderopc' && compliantModuleName === 'onepagecheckout') {
                // Compatible with OPC by Zelarg
                checkoutEvent = document.querySelectorAll('.confirm_button');

                if (!checkoutEvent.length) {
                    checkoutEvent = document.querySelectorAll('.payment_module');
                }

                checkoutEvent.forEach(function (checkoutElement) {
                    checkoutElement.addEventListener('click', rcAnalyticsEvents.eventOpcZelarg, false);
                });

            } else if (controllerName === 'orderopc' && compliantModuleName === 'onepagecheckoutps') {
                // Compatible with OPC by PresTeam - don't set the button id because this module has a load delay
                document.body.addEventListener('click', rcAnalyticsEvents.eventOpcPrestaTeam, false);

            } else if (controllerName === 'orderopc' && compliantModuleName === 'bestkit_opc') {
                // Compatible with OPC by Best-Kit
                document.body.addEventListener('click', rcAnalyticsEvents.eventOpcBestKit, false);

            } else if (controllerName === 'supercheckout') {
                // Compatible with super-checkout by Knowband
                checkoutEvent = document.querySelector('#supercheckout_confirm_order');
                checkoutEvent.addEventListener('click', rcAnalyticsEvents.eventOpcSuperCheckout, false);
            }
        }
    }

    function fireEventsOnPageShow(event){
        // fixes safari back cache button
        if (event.persisted) {
            window.location.reload()
        }

        // Sign up feature
        if (rcAnalyticsEvents.trackingFeatures.goals.signUp && rcAnalyticsEvents.trackingFeatures.isNewSignUp) {
            rcAnalyticsEvents.onSignUp();
        }

        if (isClientId) {
            rcAnalyticsEvents.setClientId();
        }

        // Checkout and order complete
        if (isCheckout && gaProducts) {
            rcAnalyticsEvents.onCheckoutProducts(gaProducts);
        } else if (isOrder && gaOrder && gaProducts) {
            rcAnalyticsEvents.onAddOrder(gaOrder, gaProducts, rcAnalyticsEvents.trackingFeatures.idShop);
        }
    }
</script>