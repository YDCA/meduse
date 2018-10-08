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
	var testimonialCarousel = $(".hotdeal-carousel");
	var items = 3,
	    itemsDesktop = {if $items_show}{$items_show}{else}1{/if},
	    itemsDesktopSmall = {if $items_show_md}{$items_show_md}{else}1{/if},
	    itemsTablet = {if $items_show_sm}{$items_show_sm}{else}1{/if},
	    itemsMobile = {if $items_show_xs}{$items_show_xs}{else}1{/if};
	var rtl = false;
	if ($("body").hasClass("rtl")) rtl = true;				
	testimonialCarousel.owlCarousel({
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
<div class="jms-hotdeal">
<div class="hotdeal-carousel">
		{foreach from=$products item=product key=k}	
		<div class="item ajax_block_product" itemscope itemtype="http://schema.org/Product">
			<div class="product-preview {if $phover == 'image_swap'}image_swap{/if}">
				<div class="preview">
					<a href="{$product.link|escape:'html':'UTF-8'}" class="product-image{if $jpb_phover == 'image_swap'} image_swap{/if}" data-id-product="{$product.id_product|escape:'html':'UTF-8'}" itemprop="url">
						<img class="img-responsive product-img1" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'large_default')|escape:'html':'UTF-8'}" alt="{$product.name|escape:html:'UTF-8'}" itemprop="image" />
					</a>
				</div>
				<div class="product-info">
					<a href="{$product.link|escape:'html'}" itemprop="url">{$product.name|truncate:25:'...'|escape:'html':'UTF-8'}</a>
					<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
						{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
							{hook h="displayProductPriceBlock" product=$product type="old_price"}								
							<span class="old price">
								{displayWtPrice p=$product.price_without_reduction}
							</span>	
						{/if}
						{if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
							<span class="price new" itemprop="price">
								{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}
								{/if}
							</span>
						{/if}
						<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
					</div>	
					{hook h='displayProductListReviews' product=$product}
					<div class="countdown" id="countdown-{$hotdeals[$k].id_hotdeals|escape:'html'}">{$hotdeals[$k].deals_time|escape:'html'}</div>		
				</div>	
			</div>
		</div>					
		{/foreach}
</div>
{if ($showall_link == '1')} 
<div class="hotdeal-viewall">
<a href="{$link->getModuleLink('hotdeals','allproduct')}">{l s='View All Product' mod='jmspagebuilder'}</a>
</div>
{/if}
</div>
