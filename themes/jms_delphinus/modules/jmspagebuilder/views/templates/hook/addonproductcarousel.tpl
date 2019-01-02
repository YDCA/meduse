{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<script type="text/javascript">
jQuery(function ($) {
    "use strict";
	var productCarousel = $(".product-carousel");
	var items = {if $cols}{$cols}{else}4{/if},
	    itemsDesktop = {if $cols}{$cols}{else}4{/if},
	    itemsDesktopSmall = {if $cols_md}{$cols_md}{else}3{/if},
	    itemsTablet = {if $cols_sm}{$cols_sm}{else}2{/if},
	    itemsMobile = {if $cols_xs}{$cols_xs}{else}1{/if};
	var rtl = false;
	if ($("body").hasClass("rtl")) rtl = true;
	productCarousel.owlCarousel({
		responsiveClass:true,
		responsive:{
			1199:{
				items:itemsDesktop
			},
			992:{
				items:itemsDesktopSmall
			},
			768:{
				items:itemsTablet
			},
			318:{
				items:itemsMobile
			}
		},
		rtl: rtl,
		margin: 30,
	    nav: {if $navigation == '1'}true{else}false{/if},
	    dots: {if $pagination == '1'}true{else}false{/if},
		autoplay:{if $autoplay == '1'}true{else}false{/if},
	    rewindNav: {if $rewind == '1'}true{else}false{/if},
	    navigationText: ["", ""],
	    slideBy: {if $slidebypage == '1'}'page'{else}1{/if},
	    slideSpeed: 200
	});
});
</script>

<div class="addon-title">
	<h3>
	{if $producttype == 'topseller'}
		{l s='TopSeller Products' mod='jmspagebuilder'}
	{elseif $producttype == 'new'}
		{l s='Latest Products' mod='jmspagebuilder'}
	{elseif $producttype == 'onsale'}
		{l s='OnSale Products' mod='jmspagebuilder'}
	{elseif $producttype == 'special'}
		{l s='Special Products' mod='jmspagebuilder'}
	{else}
		{l s='Featured Products' mod='jmspagebuilder'}
	{/if}
	</h3>
</div>
<div class="product-carousel">
	{foreach from = $products_slides item = products_slide}
		<div class="item ajax_block_product" itemscope itemtype="http://schema.org/Product">
			{foreach from = $products_slide item = product}
				<div class="product-preview">
					<div class="preview">
						<a href="{$product.link|escape:'html':'UTF-8'}" class="product-image{if $jpb_phover == 'image_swap'} image_swap{/if}" data-id-product="{$product.id_product|escape:'html':'UTF-8'}" itemprop="url">
							<img class="img-responsive product-img1" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$product.name|escape:html:'UTF-8'}" itemprop="image" />
						</a>
						{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
						<label class="label-box sale-box">
							<span class="sale-label">{l s='Sale' mod='jmspagebuilder'}</span>
						</label>
						{elseif isset($product.new) && $product.new == 1}
							<label class="label-box new-box">
								<span class="new-label">{l s='New' mod='jmspagebuilder'}</span>
							</label>
						{/if}
						<div class="product_action">
							<a class="addToWishlist product-btn" href="#" onclick="WishlistCart('wishlist_block_list', 'add', '{$product.id_product|escape:'html'}', false, 1); return false;" data-id-product="{$product.id_product|escape:'html'}" title="{l s='Add to Wishlist' mod='jmspagebuilder'}">
								<span class="fa fa-heart"></span>
							</a>
							{if isset($comparator_max_item) && $comparator_max_item}
							<a class="add_to_compare product-btn" href="{$product.link|escape:'html':'UTF-8'} " data-id-product="{$product.id_product}" title="{l s='Add to Compare' mod='jmspagebuilder'}">
								<span class="fa fa-exchange"></span>
								<span class="fa fa-spin fa-spinner"></span>
								<span class="fa fa-check"></span>
							</a>
							{/if}
							<a data-link="{$product.link|escape:'html':'UTF-8'}" class="quick-view btn-radius-square product-btn hidden-xs" title="{l s='Quick View' mod='jmspagebuilder'}">
								<span class="fa fa-search"></span>
							</a>
						</div>
						<div class="action">
						{if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity == 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}
							{if ($product.quantity > 0 OR $product.allow_oosp)}
							<a class="product-btn cart-button btn-default active ajax_add_to_cart_button" data-id-product="{$product.id_product}" href="{$link->getPageLink('cart')|escape:'html':'UTF-8'}?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart' mod='jmspagebuilder'}">
								{l s='Add to cart' mod='jmspagebuilder'}
								<span class="fa fa-spin fa-spinner"></span>
								<span class="fa fa-check"></span>
							</a>
							{else}
								<a href="#" class="product-btn cart-button btn-default ajax_add_to_cart_button disable" title="{l s='Out of Stock' mod='jmspagebuilder'}">
									{l s='Add to cart' mod='jmspagebuilder'}
								</a>
							{/if}
						{/if}
						</div>
					</div>
					<div class="product-info">
						<a href="{$product.link|escape:'html'}" itemprop="url">
              {$category->name|escape:'html':'UTF-8'}
              {$product.name|truncate:25:'...'|escape:'html':'UTF-8'}
            </a>			
						<div class="content_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
							{if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
								{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
									{hook h="displayProductPriceBlock" product=$product type="old_price"}
									<span class="old price">
										{displayWtPrice p=$product.price_without_reduction}
									</span>
								{/if}
								<span class="price new" itemprop="price">
									{hook h="displayProductPriceBlock" product=$product type="before_price"}
									{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
								</span>
								{hook h="displayProductPriceBlock" product=$product type="price"}
								{hook h="displayProductPriceBlock" product=$product type="unit_price"}
							{/if}
							<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
						</div>
					</div>
				</div>
			{/foreach}
		</div>
	{/foreach}
</div>
