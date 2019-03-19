{**
 * pm_crosssellingoncart
 *
 * @author    Presta-Module.com <support@presta-module.com> - http://www.presta-module.com
 * @copyright Presta-Module 2018 - http://www.presta-module.com
 * @license   Commercial
 *
 *           ____     __  __
 *          |  _ \   |  \/  |
 *          | |_) |  | |\/| |
 *          |  __/   | |  | |
 *          |_|      |_|  |_|
 *}

{if count($csoc_product_selection) > 0}

<div id="csoc-container" class="{if isset($on_product_page) && $on_product_page}page-product-box{/if} {$csoc_prefix|strtolower|escape:'html':'UTF-8'}">

  <div class="addon-title">
    <h3>{l s='You could also like' mod='pm_crosssellingoncart'}</h3>
  </div>

    {if $csoc_bloc_title}
		{if isset($on_product_page) && $on_product_page}
			<h3 class="page-product-heading">{$csoc_bloc_title|escape:'html':'UTF-8'}</h3>
		{else}
			<h2 class="page-subheading">{$csoc_bloc_title|escape:'html':'UTF-8'}</h2>
		{/if}
	{/if}

	<div id="{$csoc_prefix|escape:'html':'UTF-8'}" class="{if isset($on_product_page) && $on_product_page}bx-wrapper block products_block csoc-block{/if} clearfix">
		{foreach from=$csoc_product_selection item='cartProduct' name=cartProduct}
		<div class="product-container {if isset($on_product_page) && $on_product_page}product-box{/if} image-block item ajax_block_product" itemscope itemtype="http://schema.org/Product">
			{if !empty($csoc_display["{$csoc_prefix}_DISPLAY_IMG"])}
      <div class="product-preview">
			<div class="left-block">
				<div class="preview">
					<a class="product-image product_img_link {if isset($on_product_page) && $on_product_page}product-image product_image{/if}" href="{$link->getProductLink($cartProduct.id_product, $cartProduct.link_rewrite, $cartProduct.category)}" title="{$cartProduct.name|escape:'html':'UTF-8'}">
						{if empty($cartProduct.link_rewrite)}
							<img class="img-responsive product-img1" src="{$link->getImageLink("default", $cartProduct.id_image, $imageSize)}" alt="{$cartProduct.name|escape:'html':'UTF-8'}" />
						{else}
							<img class="img-responsive product-img1" src="{$link->getImageLink($cartProduct.link_rewrite, $cartProduct.id_image, $imageSize)}" alt="{$cartProduct.name|escape:'html':'UTF-8'}" />
						{/if}
						{if isset($cartProduct.new) && $cartProduct.new == 1}
						<span class="new-box">
							<span class="new-label">{l s='New' mod='pm_crosssellingoncart'}</span>
						</span>
						{/if}
						{if isset($cartProduct.on_sale) && $cartProduct.on_sale && isset($cartProduct.show_price) && $cartProduct.show_price && !$PS_CATALOG_MODE}
						<span class="sale-box">
							<span class="sale-label">{l s='Sale!' mod='pm_crosssellingoncart'}</span>
						</span>
						{/if}
					</a>
          {if !empty($csoc_display["{$csoc_prefix}_DISPLAY_BUTTON"])}
  				<div class="button-container product_button action">
  					{if ($cartProduct.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $cartProduct.available_for_order && !isset($restricted_country_mode) && $cartProduct.minimal_quantity <= 1 && $cartProduct.customizable != 2 && !$PS_CATALOG_MODE}
  						{if ($cartProduct.allow_oosp || $cartProduct.quantity > 0)}
  							{if isset($static_token)}
  								<a class="product-btn cart-button btn-default active see_button {if $csoc_prefix == 'PM_MC_CSOC'} button-small{/if} {if isset($on_product_page) && $on_product_page}exclusive{else}btn btn-default{/if}" href="{$link->getProductLink($cartProduct.id_product, $cartProduct.link_rewrite, $cartProduct.category)}" rel="nofollow" title="{l s='See product' mod='pm_crosssellingoncart'}" data-id-product="{$cartProduct.id_product|intval}" data-id-product-attribute="{$cartProduct.id_product_attribute|intval}">
  									{l s="See product" mod='pm_crosssellingoncart'}
  								</a>
  							{else}
  								<a class="product-btn cart-button btn-default active see_button {if $csoc_prefix == 'PM_MC_CSOC'} button-small{/if} {if isset($on_product_page) && $on_product_page}exclusive{else}btn btn-default{/if}" href="{$link->getProductLink($cartProduct.id_product, $cartProduct.link_rewrite, $cartProduct.category)}" rel="nofollow" title="{l s='See product' mod='pm_crosssellingoncart'}" data-id-product="{$cartProduct.id_product|intval}" data-id-product-attribute="{$cartProduct.id_product_attribute|intval}">
  									{l s="See product" mod='pm_crosssellingoncart'}
  								</a>
  							{/if}
  						{else}
  							<span class="product-btn cart-button btn-default active see_button {if $csoc_prefix == 'PM_MC_CSOC'} button-small{/if} disabled {if isset($on_product_page) && $on_product_page}exclusive{else}btn btn-default{/if}">
  								{l s="See product" mod='pm_crosssellingoncart'}
  							</span>
  						{/if}
  					{/if}
  				</div>
  				{/if}
				</div><!-- .product-image-container -->
			</div><!-- .left-block -->
			{/if}
			<div class="product-info {if isset($on_product_page) && $on_product_page}{else}right-block{/if}">
				{if !empty($csoc_display["{$csoc_prefix|escape:'html':'UTF-8'}_DISPLAY_TITLE"])}
				<h5 itemprop="name" class="{if isset($on_product_page) && $on_product_page}product-name{/if}">
					{if isset($cartProduct.pack_quantity) && $cartProduct.pack_quantity}{$cartProduct.pack_quantity|intval|cat:' x '}{/if}
					<a class="product-name" href="{$cartProduct.link|escape:'html':'UTF-8'}" title="{$cartProduct.name|escape:'html':'UTF-8'}">
            {if isset($cartProduct.id_category_default)}
              {assign var='catname' value=Category::getCategoryInformations(array($cartProduct.id_category_default))}{$catname[$cartProduct.id_category_default].name}
            {/if}
            {if isset($on_product_page) && $on_product_page}
							{$cartProduct.name|truncate:20:'...':true|escape:'html':'UTF-8'}
						{else}
							{$cartProduct.name|truncate:45:'...'|escape:'html':'UTF-8'}
						{/if}
					</a>
				</h5>
				{/if}
				{if (!$PS_CATALOG_MODE && !empty($csoc_display["{$csoc_prefix}_DISPLAY_PRICE"]) && ((isset($cartProduct.show_price) && $cartProduct.show_price) || (isset($cartProduct.available_for_order) && $cartProduct.available_for_order)))}
				<div class="content_price">
					{if isset($cartProduct.show_price) && $cartProduct.show_price && !isset($restricted_country_mode)}
						<span class="price {if isset($on_product_page) && $on_product_page}{else}product-price{/if}">
							{if !$priceDisplay}{convertPrice price=$cartProduct.price}{else}{convertPrice price=$cartProduct.price_tax_exc}{/if}
						</span>
						{if isset($cartProduct.specific_prices) && $cartProduct.specific_prices && isset($cartProduct.specific_prices.reduction) && $cartProduct.specific_prices.reduction > 0}
							<span class="old-price {if isset($on_product_page) && $on_product_page}{else}product-price{/if}">
								{displayWtPrice p=$cartProduct.price_without_reduction}
							</span>
							{if $cartProduct.specific_prices.reduction_type == 'percentage'}
								<span class="price-percent-reduction">-{($cartProduct.specific_prices.reduction * 100)|floatval}%</span>
							{/if}
						{/if}
					{else}
						<span class="price {if isset($on_product_page) && $on_product_page}{else}product-price{/if}">&nbsp;</span>
					{/if}
				</div>
				{/if}

				{*
				<div class="product-flags">
					{if (!$PS_CATALOG_MODE AND ((isset($cartProduct.show_price) && $cartProduct.show_price) || (isset($cartProduct.available_for_order) && $cartProduct.available_for_order)))}
						{if isset($cartProduct.online_only) && $cartProduct.online_only}
							<span class="online_only">{l s='Online only' mod='pm_crosssellingoncart'}</span>
						{/if}
					{/if}
					{if isset($cartProduct.on_sale) && $cartProduct.on_sale && isset($cartProduct.show_price) && $cartProduct.show_price && !$PS_CATALOG_MODE}
						{elseif isset($cartProduct.reduction) && $cartProduct.reduction && isset($cartProduct.show_price) && $cartProduct.show_price && !$PS_CATALOG_MODE}
							<span class="discount">{l s='Reduced price!' mod='pm_crosssellingoncart'}</span>
						{/if}
				</div>
				*}
				{if (!$PS_CATALOG_MODE && !empty($csoc_display["{$csoc_prefix}_DISPLAY_AVAILABILITY"]) && ((isset($cartProduct.show_price) && $cartProduct.show_price) || (isset($cartProduct.available_for_order) && $cartProduct.available_for_order)))}
					{if isset($cartProduct.available_for_order) && $cartProduct.available_for_order && !isset($restricted_country_mode)}
						<!-- <span class="availability">
							{if ($cartProduct.allow_oosp || $cartProduct.quantity > 0)}
								<span class="available-now">
									{l s='In Stock' mod='pm_crosssellingoncart'}
								</span>
							{elseif (isset($cartProduct.quantity_all_versions) && $cartProduct.quantity_all_versions > 0)}
								<span class="available-dif">
									{l s='Product available with different options' mod='pm_crosssellingoncart'}
								</span>
							{else}
								<span class="out-of-stock">
									{l s='Out of stock' mod='pm_crosssellingoncart'}
								</span>
							{/if}
						</span> -->
					{/if}
				{/if}
			</div><!-- .right-block -->
    </div>
		</div><!-- .product-container -->
		{/foreach}
	</div>
</div>

<script>
setTimeout(function() {
	if (typeof($csocjqPm) == 'undefined') $csocjqPm = $;
	$csocjqPm(document).ready(function() {
		$csocjqPm("#{$csoc_prefix|escape:'html':'UTF-8'}").pmCSOCOwlCarousel({
			items : {if sizeof($csoc_product_selection) < $csoc_products_quantity}{$csoc_product_selection|sizeof|intval}{else}{$csoc_products_quantity|intval}{/if},
			itemsCustom : false,
			itemsDesktop : false,
			itemsDesktopSmall : false,
			itemsTablet : [768,{$csoc_products_quantity_tablet|intval}],
			itemsTabletSmall : false,
			itemsMobile : [479,{$csoc_products_quantity_mobile|intval}],
			slideSpeed : 200,
			paginationSpeed : 800,
			autoPlay : true,
			stopOnHover : true,
			goToFirstSpeed : 1000,
			navigation : false,
			navigationText : ["prev","next"],
			scrollPerPage : true,
			pagination : true,
			baseClass : "pm-csoc-owl-carousel",
			theme : "pm-csoc-owl-theme",
			mouseDraggable : false,
			responsiveBaseWidth: {if $csoc_prefix == 'PM_CSOC'}window{else}{literal}$csocjqPm('.nyroModalCont, .mfp-content'){/literal}{/if}
		});
		if (typeof(modalAjaxCart) == 'undefined' && typeof(ajaxCart) != 'undefined' && typeof(pm_reloadCartOnAdd) != 'undefined' && typeof(pm_csocLoopInterval) == 'undefined') {
			pm_csocLoopInterval = setInterval(function() {
				pm_reloadCartOnAdd('{$csoc_order_page_link}');
			}, 500);
		}

		if ($csocjqPm('body#product').size() > 0) {
			// Remove product on CSOC
			$csocjqPm(document).on('click', '#PM_CSOC a.ajax_add_to_cart_button', function(e){
				e.preventDefault();
				var owl = $csocjqPm("#{$csoc_prefix|escape:'html':'UTF-8'}").data('pm-csoc-owlCarousel');
				owl.removeItem(owl.currentItem);
				owl.reinit();

				if ($csocjqPm('#PM_CSOC .product-box').length <= 0) {
					$csocjqPm('#csoc-container').remove();
				}
			});
		}
	});
}, 50);
</script>
{/if}
