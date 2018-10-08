{*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
jQuery(function ($) {
    "use strict";
	var productCarousel = $(".owl-demo"),
	container = $(".container");	
	if (productCarousel.length > 0) productCarousel.each(function () {
	var items = {$items_num|escape:'htmlall':'UTF-8'},
	    itemsDesktop = {$items_num|escape:'htmlall':'UTF-8'},
	    itemsDesktopSmall = 2,
	    itemsTablet = 2,
	    itemsMobile = 1;	
	$(this).owlCarousel({
	    items: items,
	    itemsDesktop: [1199, itemsDesktop],
	    itemsDesktopSmall: [980, itemsDesktopSmall],
	    itemsTablet: [768, itemsTablet],
	    itemsTabletSmall: false,
	    itemsMobile: [480, itemsMobile],
	    navigation: ({$navigation} == 1) ? true : false,
	    pagination: ({$pagination} == 1) ? true : false,
	    autoPlay:({$auto} == 1) ? true : false,
	    rewindNav: ({$rewindNav} == 1) ? true : false,
	    navigationText: ["", ""],
	    scrollPerPage: ({$scrollPerPage} == 1) ? true : false,
	    slideSpeed: {$speed|escape:'htmlall':'UTF-8'},
	    beforeInit: function rtlSwapItems(el) {
	        if ($("body").hasClass("rtl")) el.children().each(function (i, e) {
	            $(e).parent().prepend($(e))
	        })
	    },
	    afterInit: function afterInit(el) {
	        if ($("body").hasClass("rtl")) this.jumpTo(1000)
	    }
	})
	});
  });
</script>


<div id="boxslider">
<div class="owl-demo">
		{foreach from=$products item=product key=k name=hotdeals}	
					<div class="item">
						<div class="product-preview">
							<div class="preview"> 
								<a href="{$product.link|escape:'htmlall':'UTF-8'}" class="preview-image product_img_link"><img class="img-responsive " src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'htmlall':'UTF-8'}" alt="" /></a>           				
                				
							</div>
							<div class="product-info">	
								<div class="product-name">						
								<h3 class="title"><a href="{$product.link|escape:'htmlall':'UTF-8'}">{$product.name|truncate:25:'...'|escape:'htmlall':'UTF-8'}</a></h3>								
								{hook h='displayProductListReviews' product=$product}
								</div>

								<span class="description_short">
									{displayWtPrice p=$product.description_short}
								</span>	

								<div class="price-product-home">
									{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
									{hook h="displayProductPriceBlock" product=$product type="old_price"}								
									<span class="old-price">
										{displayWtPrice p=$product.price_without_reduction}
									</span>		
									{/if}
									{if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}<span class="price-new ">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>{else}{/if}	
								</div>

								
							

								<div class="countdown" id="countdown-{$hotdeals[$k].id_hotdeals|escape:'htmlall':'UTF-8'}">{$hotdeals[$k].deals_time|escape:'htmlall':'UTF-8'}</div>	


							</div>
						</div>
					</div>
		{/foreach}

</div>						

					{if ($viewsAll == 1)} 
					<div class="view_all">
						<h6 class="view"><a href="{$link->getModuleLink('hotdeals','allproduct')|escape:'htmlall':'UTF-8'}">
							{l s='View all product sale' mod='hotdeals'}
						</a></h6>
					</div>

					{/if}
</div>