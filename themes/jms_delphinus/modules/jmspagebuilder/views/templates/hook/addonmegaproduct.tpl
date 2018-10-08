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
	var megaboxCarousel = $(".mega-box-carousel");			
	var items = {if $items_show}{$items_show}{else}4{/if},
	itemsDesktop = {if $items_show}{$items_show}{else}4{/if},
	itemsDesktopSmall = {if $items_show_md}{$items_show_md}{else}3{/if},
	itemsTablet = {if $items_show_sm}{$items_show_sm}{else}2{/if},
	itemsMobile = {if $items_show_xs}{$items_show_xs}{else}1{/if};
	var rtl = false;
	if ($("body").hasClass("rtl")) rtl = true;				
	megaboxCarousel.owlCarousel({
		responsiveClass:true,
		responsive:{			
			1199:{
				items:itemsDesktop
			},
			991:{
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
<div class="mega-box {$box_class|escape:'html'}">
	<div class="box-title">
		<h3>
			<i class="{$icon_class|escape:'html'}"></i>
			<span>{$categoryname|escape:'html'}</span>
		</h3>	
	</div>
	{$subcats = $subcats}		
	<div class="tabpanel">
		<ul class="nav nav-tabs">
		{foreach from = $subcats item = sub key = k}
			{$cat_products = $products[$k]}	
			{if !empty($cat_products)}
			<li {if $k == 0}class="active"{/if}>
				<a href="#{$sub.id_category|escape:'html'}" aria-controls="{$sub.id_category|escape:'html'}" data-toggle="tab">
					{$sub.name|escape:'html'}
				</a>
			</li>	
			{/if}	
		{/foreach}
		</ul>
	</div>
	<div class="mega-box-content">
		<div class="inner">		
			<div class="hor-img">
				{if $hor_img1}
				<img src="{$root_url|escape:'html'}{$hor_img1|escape:'html'}" alt="" class="img-responsive" />
				{/if}
				{if $hor_img2}
				<img src="{$root_url|escape:'html'}{$hor_img2|escape:'html'}" alt="" class="img-responsive" />
				{/if}
			</div>						
			<div class="ver-img">
				<img src="{$root_url|escape:'html'}{$ver_img|escape:'html'}" alt="" class="img-responsive" />
			</div>							
			<div class="category-product-tab tab-content">
				{foreach from = $subcats item = sub key = k}
					{$cat_products = $products[$k]}	
					{if !empty($cat_products)}
					<div class="tab-pane {if $k == 0}active{/if}" id="{$sub.id_category|escape:'html'}">								
						<div class="mega-box-carousel">								
							{foreach from = $cat_products item = product}
							<div class="product-box" itemscope itemtype="http://schema.org/Product">
								<a href="{$product.link|escape:'html':'UTF-8'}" class="product-image{if $jpb_phover == 'image_swap'} image_swap{/if}" data-id-product="{$product.id_product|escape:'html':'UTF-8'}" itemprop="url">
									<img class="img-responsive product-img1" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$product.name|escape:html:'UTF-8'}" itemprop="image" />
								</a>
								<div class="product-info">
									<a href="{$product.link|escape:'html'}" itemprop="url">{$product.name|truncate:25:'...'|escape:'html':'UTF-8'}</a>
									{hook h='displayProductListReviews' product=$product}
									<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
										{if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}<span class="price new" itemprop="price">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>{/if}
										{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
										{hook h="displayProductPriceBlock" product=$product type="old_price"}								
										<span class="old price">
											{displayWtPrice p=$product.price_without_reduction}
										</span>	
										{/if}
										<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
									</div>	
								</div>		 
							</div>
							{/foreach}									
						</div>																		
					</div>
					{/if}
				{/foreach}
			</div>			
		</div>
	</div>
</div>