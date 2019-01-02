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
{if count($categoryProducts) > 0 && $categoryProducts !== false}
<section class="page-product-box blockproductscategory">
	<div class="addon-title">
		<h2> {l s='Relatives product' mod='productscategory'}</h2>
	</div>
	<div id="productscategory_list">
		<div class="accessories-carousel">
		{foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}
			<div class="item ajax_block_product" itemscope itemtype="http://schema.org/Product">
				<div class="product-preview {if $phover == 'image_swap'}image_swap{/if}">
					<div class="preview">
						<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" class="lnk_img product-image" title="{$categoryProduct.name|htmlspecialchars}">
							<img src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$categoryProduct.name|htmlspecialchars}" />
						</a>
						<div class="product_action">
							<a class="addToWishlist product-btn" href="#" onclick="WishlistCart('wishlist_block_list', 'add', '{$categoryProduct.id_product|escape:'html'}', false, 1); return false;" data-id-product="{$categoryProduct.id_product|escape:'html'}" title="{l s='Add to Wishlist' mod='productscategory'}">
								<span class="fa fa-heart"></span>
							</a>
							{if isset($comparator_max_item) && $comparator_max_item}
							<a class="add_to_compare product-btn" href="{$categoryProduct.link|escape:'html':'UTF-8'} " data-id-product="{$categoryProduct.id_product}" title="{l s='Add to Compare' mod='productscategory'}">
								<span class="fa fa-exchange"></span>
								<span class="fa fa-spin fa-spinner"></span>
								<span class="fa fa-check"></span>
							</a>
							{/if}
							<a data-link="{$categoryProduct.link|escape:'html':'UTF-8'}" class="quick-view btn-radius-square product-btn hidden-xs" title="{l s='Quick View' mod='productscategory'}">
								<span class="fa fa-search"></span>
							</a>
						</div>
						{if !$PS_CATALOG_MODE && ($categoryProduct.allow_oosp || $categoryProduct.quantity > 0)}
							<div class="action">
								<a class="product-btn cart-button btn-default active ajax_add_to_cart_button" href="{$link->getPageLink('cart', true, NULL, 'qty=1&amp;id_product={$categoryProduct.id_product|intval}&amp;token={$static_token}&amp;add')|escape:'html':'UTF-8'}" data-id-product="{$categoryProduct.id_product|intval}" title="{l s='Add to cart' mod='productscategory'}">
									{l s="Add to cart" mod="productscategory"}
									<span class="fa fa-spin fa-spinner"></span>
									<span class="fa fa-check"></span>
								</a>
							</div>
						{/if}
					</div>
					<div class="product-info">
						<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)|escape:'html':'UTF-8'}" title="{$categoryProduct.name|htmlspecialchars}" itemprop="url" itemprop="name">
							{$category->name|escape:'html':'UTF-8'}
							{$categoryProduct.name|escape:'html':'UTF-8'}
						</a>
						{if $ProdDisplayPrice && $categoryProduct.show_price == 1 && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
						<div class="content_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
							{if isset($categoryProduct.specific_prices) && $categoryProduct.specific_prices
							&& ($categoryProduct.displayed_price|number_format:2 !== $categoryProduct.price_without_reduction|number_format:2)}
								<span class="old price">{displayWtPrice p=$categoryProduct.price_without_reduction}</span>
								<span class="price special-price">{convertPrice price=$categoryProduct.displayed_price}</span>
							{else}
								<span class="price new" itemprop="price">{convertPrice price=$categoryProduct.displayed_price}</span>
							{/if}

						</div>
						{else}
							{/if}
					</div>
				</div>
			</div>
		{/foreach}
		</div>
	</div>
</section>
{/if}
