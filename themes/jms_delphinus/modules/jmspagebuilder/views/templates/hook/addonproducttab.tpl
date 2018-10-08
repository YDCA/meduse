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
	var producttabCarousel = $(".producttab-carousel");			
	var items = {if $cols}{$cols}{else}4{/if},
	itemsDesktop = {if $cols}{$cols}{else}4{/if},
	itemsDesktopSmall = {if $cols_md}{$cols_md}{else}3{/if},
	itemsTablet = {if $cols_sm}{$cols_sm}{else}2{/if},
	itemsMobile = {if $cols_xs}{$cols_xs}{else}1{/if};
	var rtl = false;
	if ($("body").hasClass("rtl")) rtl = true;				
	producttabCarousel.owlCarousel({
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

<div class="jms-tab">
	<ul class="nav nav-tabs" role="tablist">
	{$cf = 0}
		{if $config.show_featured eq '1'}
			<li class="active"><a class="button" data-toggle="tab" href="#featured">{l s='Featured' mod="jmspagebuilder"}</a></li>
		{$cf = $cf + 1}
		{/if}	
		{if $config.show_new eq '1'}
			<li {if $cf eq 0}class="active"{/if}><a class="button" data-toggle="tab" href="#latest">{l s='Latest' mod="jmspagebuilder"}</a></li>
			{$cf = $cf + 1}
		{/if}		
		{if $config.show_topseller eq '1'}
			<li {if $cf eq 0}class="active"{/if}><a class="button" data-toggle="tab" href="#topseller">{l s='Bestseller' mod="jmspagebuilder"}</a></li>
			{$cf = $cf + 1}
		{/if}		
		{if $config.show_special eq '1'}
			<li {if $cf eq 0}class="active"{/if}><a class="button" data-toggle="tab" href="#special">{l s='Special' mod="jmspagebuilder"}</a></li>
			{$cf = $cf + 1}
		{/if}			
		{if $config.show_onsale eq '1'}
			<li {if $cf eq 0}class="active"{/if}><a class="button" data-toggle="tab" href="#onsale">{l s='On Sale' mod="jmspagebuilder"}</a></li>
			{$cf = $cf + 1}
		{/if}			
	</ul>
</div>
<div class="tab-content">
	{$cf = 0}
	{if $config.show_featured eq '1'}
		 <div role="tabpanel" class="tab-pane active" id="featured">
			<div class="producttab-carousel">	
				{foreach from = $featured_products item = products_slide}
					<div class="item">
						{foreach from = $products_slide item = product}
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
				{/foreach}
			</div>
		 </div>
		{$cf = $cf + 1}
	{/if}
	{if $config.show_new eq '1'}
		 <div role="tabpanel" class="tab-pane {if $cf eq 0}active{/if}" id="latest">
			<div class="producttab-carousel">	
				{foreach from = $new_products item = products_slide}
					<div class="item">
						{foreach from = $products_slide item = product}
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
				{/foreach}
			</div>
		 </div>
		{$cf = $cf + 1}
	{/if}
	{if $config.show_topseller eq '1'}
		 <div role="tabpanel" class="tab-pane {if $cf eq 0}active{/if}" id="topseller">
			<div class="producttab-carousel">	
				{foreach from = $topseller_products item = products_slide}
					<div class="item">
						{foreach from = $products_slide item = product}
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
				{/foreach}
			</div>
		 </div>
		{$cf = $cf + 1}
	{/if}
	{if $config.show_special eq '1'}
		 <div role="tabpanel" class="tab-pane {if $cf eq 0}active{/if}" id="special">
			<div class="producttab-carousel">	
				{foreach from = $special_products item = products_slide}
					<div class="item">
						{foreach from = $products_slide item = product}
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
				{/foreach}
			</div>
		 </div>
		{$cf = $cf + 1}
	{/if}
	{if $config.show_onsale eq '1'}
		 <div role="tabpanel" class="tab-pane {if $cf eq 0}active{/if}" id="onsale">
			<div class="producttab-carousel">	
				{foreach from = $onsale_products item = products_slide}
					<div class="item">
						{foreach from = $products_slide item = product}
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
				{/foreach}
			</div>
		 </div>
		{$cf = $cf + 1}
	{/if}
</div>
