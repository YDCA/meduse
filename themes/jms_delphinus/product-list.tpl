{*
* 2007-2013 PrestaShop
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
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if isset($products) && $products}
{*define number of products per line in other page for desktop*}
	{if $page_name !='index' && $page_name !='product'}
		{assign var='nbItemsPerLine' value=3}
		{assign var='nbItemsPerLineTablet' value=2}
		{assign var='nbItemsPerLineMobile' value=3}
	{else}
		{assign var='nbItemsPerLine' value=4}
		{assign var='nbItemsPerLineTablet' value=3}
		{assign var='nbItemsPerLineMobile' value=2}
	{/if}
	{*define numbers of product per line in other page for tablet*}
	{assign var='nbLi' value=$products|@count}
	{math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
	{math equation="nbLi/nbItemsPerLineTablet" nbLi=$nbLi nbItemsPerLineTablet=$nbItemsPerLineTablet assign=nbLinesTablet}
<!-- Products list -->
	<div id="product_list" class="product_list products-list-in-column row">
	{$i=0}
	{foreach from=$products item=product name=products}
		{math equation="(total%perLine)" total=$smarty.foreach.products.total perLine=$nbItemsPerLine assign=totModulo}
		{math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$nbItemsPerLineTablet assign=totModuloTablet}
		{math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$nbItemsPerLineMobile assign=totModuloMobile}
		{if $totModulo == 0}{assign var='totModulo' value=$nbItemsPerLine}{/if}
		{if $totModuloTablet == 0}{assign var='totModuloTablet' value=$nbItemsPerLineTablet}{/if}
		{if $totModuloMobile == 0}{assign var='totModuloMobile' value=$nbItemsPerLineMobile}{/if}
		<div class="item ajax_block_product" itemscope itemtype="http://schema.org/Product">
			<div class="product-preview {if $phover == 'image_swap'}image_swap{/if}">
					<div class="preview">
						{if $product.specific_prices.reduction_type == 'percentage'}
							<span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
						{/if}
						<a href="{$product.link|escape:'html':'UTF-8'}" class="product-image" data-id-product="{$product.id_product|escape:'html':'UTF-8'}" itemprop="url">
							<img class="img-responsive product-img1" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$product.name|escape:html:'UTF-8'}" itemprop="image" />
						</a>
						<div class="product_action">
							<a class="addToWishlist product-btn" href="#" onclick="WishlistCart('wishlist_block_list', 'add', '{$product.id_product|escape:'html'}', false, 1); return false;" data-id-product="{$product.id_product|escape:'html'}" title="{l s='Add to Wishlist'}">
								<span class="fa fa-heart"></span>
							</a>
							{if isset($comparator_max_item) && $comparator_max_item}
							<a class="add_to_compare product-btn" href="{$product.link|escape:'html':'UTF-8'} " data-id-product="{$product.id_product}" title="{l s='Add to Compare' mod='jmspagebuilder'}">
								<span class="fa fa-exchange"></span>
								<span class="fa fa-spin fa-spinner"></span>
								<span class="fa fa-check"></span>
							</a>
							{/if}
							<a data-link="{$product.link|escape:'html':'UTF-8'}" class="quick-view btn-radius-square product-btn hidden-xs" title="{l s='Quick View'}">
								<span class="fa fa-search"></span>
							</a>
						</div>
						<div class="action">
						{if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity == 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}
							{if ($product.quantity > 0 OR $product.allow_oosp)}
							<!-- <a class="product-btn cart-button btn-default active ajax_add_to_cart_button" data-id-product="{$product.id_product}" href="{$link->getPageLink('cart')|escape:'html':'UTF-8'}?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart'}"> -->
							<a class="product-btn cart-button btn-default active see_button" data-id-product="{$product.id_product}" href="{$product.link|escape:'html'}" title="{l s='See product'}">
								{l s="See product"}
								<!-- <span class="fa fa-spin fa-spinner"></span>
								<span class="fa fa-check"></span> -->
							</a>
							{else}
								<a href="#" class="product-btn cart-button btn-default see_button disable" title="{l s='Out of Stock' mod="jmspagebuilder"}">
									{l s="Out of Stock"}
								</a>
							{/if}
						{/if}
						</div>
					</div>
					<div class="product-info">
						<h2>
							<a href="{$product.link|escape:'html'}" itemprop="url" itemprop="name">
								{if isset($product.id_category_default)}
									{assign var='catname' value=Category::getCategoryInformations(array($product.id_category_default))}{$catname[$product.id_category_default].name}
								{/if}
								{$product.name|truncate:25:'...'|escape:'html':'UTF-8'}
							</a>
						</h2>
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
						<div class="product-list-info">
							<div class="list_info">
								<!--description-->
								<div class="list_description">
									<p>{$product.description_short|strip_tags:'UTF-8'|truncate:200:'...'}</p>
								</div>
							</div>
							{if isset($product.is_virtual) && !$product.is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
							{hook h="displayProductPriceBlock" product=$product type="weight"}
							<div class="product_button">
								{if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity == 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}
									{if ($product.quantity > 0 OR $product.allow_oosp)}
									<a class="product-btn cart-button btn-default active ajax_add_to_cart_button" data-id-product="{$product.id_product}" href="{$link->getPageLink('cart')|escape:'html':'UTF-8'}?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart'}">
										{l s="Add to cart"}
										<span class="fa fa-spin fa-spinner"></span>
										<span class="fa fa-check"></span>
									</a>
									{else}
										<a href="#" class="product-btn cart-button btn-default ajax_add_to_cart_button disable" title="{l s='Out of Stock' mod="jmspagebuilder"}">
											{l s="Add to cart"}
										</a>
									{/if}
								{/if}
								<a class="addToWishlist product-btn" href="#" onclick="WishlistCart('wishlist_block_list', 'add', '{$product.id_product|escape:'html'}', false, 1); return false;" data-id-product="{$product.id_product|escape:'html'}" title="{l s='Add to Wishlist'}">
									<span class="fa fa-heart"></span>
								</a>
								{if isset($comparator_max_item) && $comparator_max_item}
								<a class="add_to_compare product-btn" href="{$product.link|escape:'html':'UTF-8'} " data-id-product="{$product.id_product}" title="{l s='Add to Compare' mod='jmspagebuilder'}">
									<span class="fa fa-exchange"></span>
									<span class="fa fa-spin fa-spinner"></span>
									<span class="fa fa-check"></span>
								</a>
								{/if}
								<a data-link="{$product.link|escape:'html':'UTF-8'}" class="quick-view btn-radius-square product-btn hidden-xs" title="{l s='Quick View'}">
									<span class="fa fa-search"></span>
								</a>
							</div>
						</div>
					</div>
			</div>
		</div>
		{$i=$i+1}
	{/foreach}
	</div>
	{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
	{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
	{addJsDef comparator_max_item=$comparator_max_item}
	{addJsDef comparedProductsIds=$compared_products}
	<!-- /Products list -->
{/if}
