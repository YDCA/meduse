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
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div id="featured-products_block_center">
	<div class="slider-product-title">
		<h3 class="title">
			<span>{l s='Featured products' mod='homefeatured'}</span>
		</h3>
	</div>
	{if isset($products) AND $products}
		<div class="product-carousel">
			{assign var='liHeight' value=250}
			{assign var='nbItemsPerLine' value=4}
			{assign var='nbLi' value=$products|@count}
			{math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
			{math equation="nbLines*liHeight" nbLines=$nbLines|ceil liHeight=$liHeight assign=ulHeight}	
			{foreach from=$products item=product name=homeFeaturedProducts}
				{math equation="(total%perLine)" total=$smarty.foreach.homeFeaturedProducts.total perLine=$nbItemsPerLine assign=totModulo}
				{if $totModulo == 0}{assign var='totModulo' value=$nbItemsPerLine}{/if}
				<div class="item ajax_block_product" itemscope itemtype="http://schema.org/Product">
					<div class="product-preview {if $phover == 'image_swap'}image_swap{/if}">
						<div class="preview"> 
							<a href="{$product.link|escape:'html'}" class="preview-image product_img_link image-rollover" data-id-product="{$product.id_product}" itemprop="url">
								<img class="img-responsive product-img1" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html'}" alt="" itemprop="image" />
							</a>
							{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
								<div class="wrapper-label wrapper-label-sale">
									<span class="label label-sale">
										{l s='Sale' mod='homefeatured'}
									</span>
								</div>
							{elseif isset($product.new) && $product.new == 1}
								<div class="wrapper-label">
									<span class="label label-new">
										{l s='New' mod='homefeatured'}
									</span>
								</div>
							{/if}
							<div class="products-box">
								{if isset($product.is_virtual) && !$product.is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
								{hook h="displayProductPriceBlock" product=$product type="weight"}									
								<a data-link="{$product.link|escape:'html':'UTF-8'}" class="quick-view product-btn hidden-xs" title="{l s='Quick View' mod='homefeatured'}">
									<span class="icon icon-basic-link"></span>
								</a>
								{if isset($comparator_max_item) && $comparator_max_item}
								<a class="add_to_compare product-btn" href="{$product.link|escape:'html':'UTF-8'} " data-id-product="{$product.id_product}" title="{l s='Add to Compare' mod='homefeatured'}">
									<span class="icon icon-basic-mixer2"></span>
									<span class="fa fa-spin fa-spinner"></span>
									<span class="fa fa-check"></span>
								</a>
								{/if}
								<a class="addToWishlist product-btn" href="#" onclick="WishlistCart('wishlist_block_list', 'add', '{$product.id_product|escape:'html'}', false, 1); return false;" data-id-product="{$product.id_product|escape:'html'}" title="{l s='Add to Wishlist' mod='homefeatured'}">
									<span class="icon icon-basic-heart"></span>
								</a>
								{if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity == 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}
									{if ($product.quantity > 0 OR $product.allow_oosp)}
									<a class="exclusive ajax_add_to_cart_button cart-button product-btn" data-id-product="{$product.id_product}" href="{$link->getPageLink('cart')|escape:'html':'UTF-8'}?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart'}">
										<span class="icon icon-ecommerce-bag"></span>
										<span class="icon icon-ecommerce-bag-refresh"></span>
										<span class="icon icon-ecommerce-bag-check"></span>
									</a>							
									{else}
										<a href="#" class="disable cart-button product-btn" title="{l s='Out of Stock'}">
											<span class="icon icon-ecommerce-bag icon-disable"></span>
										</a>
									{/if}										
								{/if}	
							</div>	
						</div>
						<div class="product-info clearfix">
							<h3 class="title" itemprop="name">
								<a href="{$product.link|escape:'html'}" itemprop="url">{$product.name|escape:'html':'UTF-8'}</a>
							</h3>
							<div class="rate_box pull-left">
								{hook h='displayProductListReviews' product=$product}
							</div>												
							<div class="content_price pull-right" itemprop="offers" itemscope itemtype="http://schema.org/Offer">			
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
				</div>
			{/foreach}
		</div>
	{else}
		<p>{l s='No featured products' mod='homefeatured'}</p>
	{/if}
</div>