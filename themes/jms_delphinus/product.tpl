{*
* 2007-2014 PrestaShop
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
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{include file="$tpl_dir./errors.tpl"}
{if $errors|@count == 0}
	{if !isset($priceDisplayPrecision)}
		{assign var='priceDisplayPrecision' value=2}
	{/if}
	{if !$priceDisplay || $priceDisplay == 2}
		{assign var='productPrice' value=$product->getPrice(true, $smarty.const.NULL, $priceDisplayPrecision)}
		{assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
	{elseif $priceDisplay == 1}
		{assign var='productPrice' value=$product->getPrice(false, $smarty.const.NULL, $priceDisplayPrecision)}
		{assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(true, $smarty.const.NULL)}
	{/if}
	<div class="row" itemscope itemtype="http://schema.org/Product">
		{if isset($adminActionDisplay) && $adminActionDisplay}
			<div id="admin-action">
				<p>{l s='This product is not visible to your customers.'}
					<input type="hidden" id="admin-action-product-id" value="{$product->id}" />
					<input type="submit" value="{l s='Publish'}" name="publish_button" class="exclusive" />
					<input type="submit" value="{l s='Back'}" name="lnk_view" class="exclusive" />
				</p>
				<p id="admin-action-result"></p>
			</div>
		{/if}
		{if isset($confirmation) && $confirmation}
			<p class="confirmation">
				{$confirmation}
			</p>
		{/if}
		<!-- left infos-->
		<div class="pb-left-column col-sm-6 col-md-6 col-lg-6">
			<div class="pd-left-content">
				<!-- product img-->
				<div id="image-block">
					{if $have_image}
						<span id="view_full_size">
							{if $jqZoomEnabled && $have_image && !$content_only}
								<a class="jqzoom" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" rel="gal1" href="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')|escape:'html':'UTF-8'}" itemprop="url">
									<img itemprop="image" src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')|escape:'html':'UTF-8'}" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}"/>
								</a>
							{else}
							<img class="img-responsive" id="bigpic" itemprop="image" src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')|escape:'html':'UTF-8'}" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" />
							{/if}
						</span>
					{else}
						<span id="view_full_size">
							<img class="img-responsive" src="{$img_prod_dir}{$lang_iso}-default-large_default.jpg" id="bigpic" alt="" title="{$product->name|escape:'htmlall':'UTF-8'}" />
							<span class="span_link">{l s='View larger'}</span>
						</span>
					{/if}
				</div>
				{if isset($images) && count($images) > 0}
				<!-- thumbnails -->
					<div id="views_block" class="clearfix {if isset($images) && count($images) < 2}hidden{/if}">
						<div id="thumbs_list">
							<ul id="thumbs_list_frame" class="thumb-carousel">
							{if isset($images)}
								{foreach from=$images item=image name=thumbnails}
									{assign var=imageIds value="`$product->id`-`$image.id_image`"}
									{if !empty($image.legend)}
										{assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
									{else}
										{assign var=imageTitle value=$product->name|escape:'html':'UTF-8'}
									{/if}
									<li id="thumbnail_{$image.id_image}" class="thumb_products">
										<a
											{if $jqZoomEnabled && $have_image && !$content_only}
												href="javascript:void(0);"
												rel="{literal}{/literal}gallery: 'gal1', smallimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'large_default')|escape:'html':'UTF-8'}',largeimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}'{literal}}{/literal}"
											{else}
												href="{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_default')|escape:'html':'UTF-8'}"
												data-fancybox-group="other-views"
												class="fancybox{if $image.id_image == $cover.id_image} shown{/if}"
											{/if}
											title="{$imageTitle}">
											<img id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'medium_default')|escape:'html':'UTF-8'}" alt="{$imageTitle}" title="{$imageTitle}" itemprop="image" />
										</a>
									</li>
								{/foreach}
							{/if}
							</ul>
						</div> <!-- end thumbs_list -->
					</div> <!-- end views-block -->
					<!-- end thumbnails -->
				{/if}
			</div>
		</div> <!-- end pb-left-column -->
		<!-- end left infos-->
		<!-- pb-right-column-->
		<div class="pb-right-column col-sm-6 col-md-6 col-lg-6">
			{include file="$tpl_dir./breadcrumb.tpl"}
			<h2 itemprop="name">{$product->name|escape:'html':'UTF-8'}</h2>
			<div class="extra-product clearfix">
				<div class="price-area" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					{if $product->show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
						{if $priceDisplay >= 0 && $priceDisplay <= 2}
							{hook h="displayProductPriceBlock" product=$product type="old_price"}
							<span id="old_price_display" class="price old">
								{if $productPriceWithoutReduction > $productPrice}{convertPrice price=$productPriceWithoutReduction}{/if}
							</span>
						{/if}
						{if $priceDisplay >= 0 && $priceDisplay <= 2}
							<span id="our_price_display" class="price new" itemprop="price">{convertPrice price=$productPrice}</span>
							<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
							{hook h="displayProductPriceBlock" product=$product type="price"}
						{/if}
					{/if} {*close if for show price*}
					{hook h="displayProductPriceBlock" product=$product type="weight" hook_origin='product_sheet'}
					{hook h="displayProductPriceBlock" product=$product type="after_price"}
				</div>
				<div class="extra-comment">
					{if isset($HOOK_EXTRA_LEFT) && $HOOK_EXTRA_LEFT}{$HOOK_EXTRA_LEFT}{/if}
				</div>
			</div>
			<!-- quantity wanted -->
			<div class="quantity-cart">
				{if !$PS_CATALOG_MODE}
					<div class="option">
						<div id="quantity_wanted_p"{if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if} class="input-group quantity-control">
							<span class="input-group-addon">-</span>
							<input type="text" name="qty" id="quantity_wanted" class="text form-control" value="{if isset($quantityBackup)}{$quantityBackup|intval}{else}{if $product->minimal_quantity > 1}{$product->minimal_quantity}{else}1{/if}{/if}" />
							<span class="input-group-addon">+</span>
						</div>
					</div>
				{/if}
				<div class="product-util">
					<button type="submit" name="Submit" id="add_to_cart" class="btn btn-default active product-btn">
						<span>{l s='Add to cart'}</span>
					</button>
					<a class="addToWishlist product-btn" href="#" onclick="WishlistCart('wishlist_block_list', 'add', '{$product->id_product|escape:'html'}', false, 1); return false;" data-id-product="{$product->id_product|escape:'html'}" title="{l s='Add to Wishlist'}">
						<span class="fa fa-heart"></span>
					</a>
					{if isset($comparator_max_item) && $comparator_max_item}
					<a class="add_to_compare product-btn" href="{$product->link|escape:'html':'UTF-8'} " data-id-product="{$product->id_product}" title="{l s='Add to Compare'}">
						<span class="fa fa-exchange"></span>
						<span class="fa fa-spin fa-spinner"></span>
						<span class="fa fa-check"></span>
					</a>
					{/if}
				</div>
			</div>
			{if $product->description_short}
				<div id="short_description_content" class="rte align_justify" itemprop="description">{$product->description_short}</div>
			{/if}
			<div class="product-description">
					{if ($product->show_price && !isset($restricted_country_mode)) || isset($groups) || $product->reference || (isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS)}
					<!-- add to cart form-->
					<form id="buy_block"{if $PS_CATALOG_MODE && !isset($groups) && $product->quantity > 0} class="hidden"{/if} action="{$link->getPageLink('cart')|escape:'html':'UTF-8'}" method="post">
						<!-- hidden datas -->
						<p class="hidden">
							<input type="hidden" name="token" value="{$static_token}" />
							<input type="hidden" name="id_product" value="{$product->id|intval}" id="product_page_product_id" />
							<input type="hidden" name="add" value="1" />
							<input type="hidden" name="id_product_attribute" id="idCombination" value="" />
						</p>
						{if isset($groups)}
							<!-- attributes -->
							<div id="attributes" class="row">
								{foreach from=$groups key=id_attribute_group item=group}
									{if $group.attributes|@count}
										<fieldset class="attribute_fieldset col-md-12">
											{assign var="groupName" value="group_$id_attribute_group"}
											<div class="attribute_list product-options">
												{if ($group.group_type == 'select')}
												<div class="attribute_arrow">
													<select name="{$groupName}" id="group_{$id_attribute_group|intval}" class="form-control attribute_select no-print">
														{foreach from=$group.attributes key=id_attribute item=group_attribute}
															<option value="{$id_attribute|intval}"{if (isset($smarty.get.$groupName) && $smarty.get.$groupName|intval == $id_attribute) || $group.default == $id_attribute} selected="selected"{/if} title="{$group_attribute|escape:'html':'UTF-8'}">{$group_attribute|escape:'html':'UTF-8'}</option>
																{/foreach}
													</select>
												</div>
												{elseif ($group.group_type == 'color')}
												<ul id="color_to_pick_list" class="clearfix">
													{assign var="default_colorpicker" value=""}
													{foreach from=$group.attributes key=id_attribute item=group_attribute}
														<li{if $group.default == $id_attribute} class="selected"{/if}>
															<a href="{$link->getProductLink($product)|escape:'html':'UTF-8'}" id="color_{$id_attribute|intval}" name="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" class="color_pick{if ($group.default == $id_attribute)} selected{/if}" title="{$colors.$id_attribute.name|escape:'html':'UTF-8'}">
																<span style="background: {$colors.$id_attribute.value|escape:'html':'UTF-8'};">
															{if file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}
																<img src="{$img_col_dir}{$id_attribute|intval}.jpg" alt="{$colors.$id_attribute.name|escape:'html':'UTF-8'}" width="30" height="30" />
															{/if}
																</span>
															</a>
														</li>
														{if ($group.default == $id_attribute)}
															{$default_colorpicker = $id_attribute}
														{/if}
													{/foreach}
												</ul>
													<input type="hidden" class="color_pick_hidden" name="{$groupName|escape:'html':'UTF-8'}" value="{$default_colorpicker|intval}" />
												{elseif ($group.group_type == 'radio')}
													<ul>
													{foreach from=$group.attributes key=id_attribute item=group_attribute}
														<li>
															<input type="radio" class="attribute_radio" name="{$groupName|escape:'html':'UTF-8'}" value="{$id_attribute}" {if ($group.default == $id_attribute)} checked="checked"{/if} />
																<span>{$group_attribute|escape:'html':'UTF-8'}</span>
														</li>
													{/foreach}
													</ul>
												{/if}
											</div> <!-- end attribute_list -->
										</fieldset>
									{/if}
								{/foreach}
							</div> <!-- end attributes -->
						{/if}
						<!-- minimal quantity wanted -->
						<p id="minimal_quantity_wanted_p"{if $product->minimal_quantity <= 1 || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
							{l s='This product is not sold individually. You must select at least'} <b id="minimal_quantity_label">{$product->minimal_quantity}</b> {l s='quantity for this product.'}
						</p>
					</form>
					{/if}
					<div class="other-info">
						{if ($display_qties == 1 && !$PS_CATALOG_MODE && $PS_STOCK_MANAGEMENT && $product->available_for_order)}
						<!-- number of item in stock -->
						<p id="pQuantityAvailable"{if $product->quantity <= 0} style="display: none;"{/if}>
							<label>{l s='Number of item in stock:'}</label>
							<span id="quantityAvailable">{$product->quantity|intval}</span>
							<span {if $product->quantity > 1} style="display: none;"{/if} id="quantityAvailableTxt">{l s='Item'}</span>
							<span {if $product->quantity == 1} style="display: none;"{/if} id="quantityAvailableTxtMultiple">{l s='Items'}</span>
						</p>
						{/if}

						<p id="product_reference"{if empty($product->reference) || !$product->reference} style="display: none;"{/if}>
							<label>{l s='Model'}: </label>
							<span class="editable" itemprop="sku">{if !isset($groups)}{$product->reference|escape:'html':'UTF-8'}{/if}</span>
						</p>
						{if $product->online_only}
						<p class="online_only">{l s='Online only'}</p>
						{/if}
						{capture name=condition}
							{if $product->condition == 'new'}{l s='New'}
							{elseif $product->condition == 'used'}{l s='Used'}
							{elseif $product->condition == 'refurbished'}{l s='Refurbished'}
							{/if}
						{/capture}
						<p id="product_condition"{if !$product->condition} style="display: none;"{/if}>
							<label>{l s='Condition'}: </label>
							<span class="editable" itemprop="itemCondition">{$smarty.capture.condition}</span>
						</p>
						{if $PS_STOCK_MANAGEMENT}
							{hook h="displayProductDeliveryTime" product=$product}
							<p class="warning_inline" id="last_quantities"{if ($product->quantity > $last_qties || $product->quantity <= 0) || $allow_oosp || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none"{/if} >{l s='Warning: Last items in stock!'}</p>
						{/if}
						<p id="availability_date"{if ($product->quantity > 0) || !$product->available_for_order || $PS_CATALOG_MODE || !isset($product->available_date) || $product->available_date < $smarty.now|date_format:'%Y-%m-%d'} style="display: none;"{/if}>
							<span id="availability_date_label">{l s='Availability date:'}</span>
							<span id="availability_date_value">{dateFormat date=$product->available_date full=false}</span>
						</p>
						<!-- Out of stock hook -->
						<div id="oosHook"{if $product->quantity > 0} style="display: none;"{/if}>
							{$HOOK_PRODUCT_OOS}
						</div>
					</div>

					{if isset($accessories) && $accessories}
						<!--Accessories -->
						<section class="slider-products accessories_block">
							<div class="addon-title">
								<h3>
									{l s='Accessories'}
								</h3>
							</div>
							<div class="products_category">
								<div class="accessories-carousel">
									{foreach from=$accessories item=accessory name=accessories_list}
										{if ($accessory.allow_oosp || $accessory.quantity_all_versions > 0 || $accessory.quantity > 0) && $accessory.available_for_order && !isset($restricted_country_mode)}
												{assign var='accessoryLink' value=$link->getProductLink($accessory.id_product, $accessory.link_rewrite, $accessory.category)}
										<div class="item ajax_block_product" itemscope itemtype="http://schema.org/Product">
											<div class="product-preview {if $phover == 'image_swap'}image_swap{/if}">
												<div class="preview">
													<a href="{$accessoryLink|escape:'html':'UTF-8'}" title="{$accessory.legend|escape:'html':'UTF-8'}" class="product-image product_image">
														<img class="img-responsive" src="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{$accessory.legend|escape:'html':'UTF-8'}" width="{$homeSize.width}" height="{$homeSize.height}"/>
													</a>
													<div class="product_action">
														<a class="addToWishlist product-btn" href="#" onclick="WishlistCart('wishlist_block_list', 'add', '{$accessoryLink.id_product|escape:'html'}', false, 1); return false;" data-id-product="{$accessoryLink.id_product|escape:'html'}" title="{l s='Add to Wishlist' mod='jmspagebuilder'}">
															<span class="fa fa-heart"></span>
														</a>
														{if isset($comparator_max_item) && $comparator_max_item}
														<a class="add_to_compare product-btn" href="{$accessoryLink.link|escape:'html':'UTF-8'} " data-id-product="{$accessoryLink.id_product}" title="{l s='Add to Compare' mod='jmspagebuilder'}">
															<span class="fa fa-exchange"></span>
															<span class="fa fa-spin fa-spinner"></span>
															<span class="fa fa-check"></span>
														</a>
														{/if}
														<a data-link="{$accessoryLink|escape:'html':'UTF-8'}" class="quick-view btn-radius-square product-btn hidden-xs" title="{l s='Quick View'}">
															<span class="fa fa-search"></span>
														</a>
													</div>
													<div class="action">
														<a class="product-btn cart-button btn-default active ajax_add_to_cart_button" data-id-product="{$accessory.id_product|intval}" href="{$link->getPageLink('cart', true, NULL, 'qty=1&amp;id_product={$accessory.id_product|intval}&amp;token={$static_token}&amp;add')|escape:'html':'UTF-8'}" title="{l s='Add to cart'}">
															{l s="Add to cart"}
															<span class="fa fa-spin fa-spinner"></span>
															<span class="fa fa-check"></span>
														</a>
														{else}
														<a href="#" class="product-btn cart-button btn-default ajax_add_to_cart_button disable" title="{l s='Out of Stock'}">
															{l s="Add to cart"}
														</a>
														{/if}
													</div>
												</div>
												<div class="product-info">
													<a href="{$accessoryLink|escape:'html':'UTF-8'}" itemprop="url">{$accessory.name|truncate:25:'...':true|escape:'html':'UTF-8'}</a>
													{if !$PS_CATALOG_MODE && ($accessory.allow_oosp || $accessory.quantity > 0)}
													<div class="content_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
														{if isset($accessory.specific_prices) && $accessory.specific_prices && isset($accessory.specific_prices.reduction) && $accessory.specific_prices.reduction > 0}
														{hook h="displayProductPriceBlock" product=$accessoryLink type="old_price"}
														<span class="old price">
															{displayWtPrice p=$accessory.price_without_reduction}
														</span>
														{/if}
														{if $accessory.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}<span class="price new" itemprop="price">{if !$priceDisplay}{convertPrice price=$accessory.price}{else}{convertPrice price=$accessory.price_tax_exc}{/if}</span>{/if}
														<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
													</div>
												</div>
											</div>
										</div>
										{/if}
									{/foreach}
								</div>
							</div>
						</section>
						<!--end Accessories -->
					{/if}

					<div class="social_sharing_product">
						{if isset($HOOK_EXTRA_RIGHT) && $HOOK_EXTRA_RIGHT}{$HOOK_EXTRA_RIGHT}{/if}
					</div>

			</div>
		</div> <!-- end pb-right-column-->
		{if !$content_only}
			<div id="more_info_block">
				<div class="tabs-info">
					<ul class="nav nav-tabs">
						{if $product->description}<li class="active"><a href="#tabs-1" data-toggle="tab">{l s='Description'}</a></li>{/if}
						{if isset($features) && $features}
						<li><a href="#tabs-2" data-toggle="tab">{l s='Specification'}</a></li>
						{/if}
						<li><a href="#tabs-3" data-toggle="tab">{$HOOK_PRODUCT_TAB}</a></li>
					</ul>
				</div>
				<div class="tab-content">
						{if isset($product) && $product->description}
						<div id="tabs-1" class="tab-pane active">{$product->description}</div>
						{/if}
						{if isset($features) && $features}
						<div id="tabs-2" class="tab-pane">
							<!-- Data sheet -->
							<section class="page-product-box">
								<table class="table-data-sheet">
									{foreach from=$features item=feature}
									<tr class="{cycle values="odd,even"}">
										{if isset($feature.value)}
										<td>{$feature.name|escape:'html':'UTF-8'} : </td>
										<td>{$feature.value|escape:'html':'UTF-8'}</td>
										{/if}
									</tr>
									{/foreach}
								</table>
							</section>
							<!--end Data sheet -->
						</div>
						{/if}
						{if isset($HOOK_PRODUCT_TAB_CONTENT) && $HOOK_PRODUCT_TAB_CONTENT}
						<div id="tabs-3" class="tab-pane">{$HOOK_PRODUCT_TAB_CONTENT}</div>
						{/if}
				</div>
			</div>
			{/if}
			<div class="productcategory_block container">
				{if isset($HOOK_PRODUCT_FOOTER) && $HOOK_PRODUCT_FOOTER}{$HOOK_PRODUCT_FOOTER}{/if}
			</div>
	</div> <!-- end primary_block -->

	{if !$content_only}
{if (isset($quantity_discounts) && count($quantity_discounts) > 0)}
			<!-- quantity discount -->
			<section class="page-product-box">
				<h3 class="page-product-heading">{l s='Volume discounts'}</h3>
				<div id="quantityDiscount">
					<table class="std table-product-discounts">
						<thead>
							<tr>
								<th>{l s='Quantity'}</th>
								<th>{if $display_discount_price}{l s='Price'}{else}{l s='Discount'}{/if}</th>
								<th>{l s='You Save'}</th>
							</tr>
						</thead>
						<tbody>
							{foreach from=$quantity_discounts item='quantity_discount' name='quantity_discounts'}
							<tr id="quantityDiscount_{$quantity_discount.id_product_attribute}" class="quantityDiscount_{$quantity_discount.id_product_attribute}" data-discount-type="{$quantity_discount.reduction_type}" data-discount="{$quantity_discount.real_value|floatval}" data-discount-quantity="{$quantity_discount.quantity|intval}">
								<td>
									{$quantity_discount.quantity|intval}
								</td>
								<td>
									{if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
										{if $display_discount_price}
											{convertPrice price=$productPrice-$quantity_discount.real_value|floatval}
										{else}
											{convertPrice price=$quantity_discount.real_value|floatval}
										{/if}
									{else}
										{if $display_discount_price}
											{convertPrice price = $productPrice-($productPrice*$quantity_discount.reduction)|floatval}
										{else}
											{$quantity_discount.real_value|floatval}%
										{/if}
									{/if}
								</td>
								<td>
									<span>{l s='Up to'}</span>
									{if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
										{$discountPrice=$productPrice-$quantity_discount.real_value|floatval}
									{else}
										{$discountPrice=$productPrice-($productPrice*$quantity_discount.reduction)|floatval}
									{/if}
									{$discountPrice=$discountPrice*$quantity_discount.quantity}
									{$qtyProductPrice = $productPrice*$quantity_discount.quantity}
									{convertPrice price=$qtyProductPrice-$discountPrice}
								</td>
							</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
			</section>
		{/if}
		<!-- description & features -->
		{if (isset($product) && $product->description) || (isset($features) && $features) || (isset($accessories) && $accessories) || (isset($HOOK_PRODUCT_TAB) && $HOOK_PRODUCT_TAB) || (isset($attachments) && $attachments) || isset($product) && $product->customizable}
			{if isset($attachments) && $attachments}
			<!--Download -->
			<section class="page-product-box">
				<h3 class="page-product-heading">{l s='Download'}</h3>
				{foreach from=$attachments item=attachment name=attachements}
					{if $smarty.foreach.attachements.iteration %3 == 1}<div class="row">{/if}
						<div class="col-lg-4">
							<h4><a href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">{$attachment.name|escape:'html':'UTF-8'}</a></h4>
							<p class="text-muted">{$attachment.description|escape:'html':'UTF-8'}</p>
							<a class="btn btn-default btn-block" href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">
								<i class="icon-download"></i>
								{l s="Download"} ({Tools::formatBytes($attachment.file_size, 2)})
							</a>
							<hr>
						</div>
					{if $smarty.foreach.attachements.iteration %3 == 0 || $smarty.foreach.attachements.last}</div>{/if}
				{/foreach}
			</section>
			<!--end Download -->
			{/if}
			{if isset($product) && $product->customizable}
			<!--Customization -->
			<section class="page-product-box">
				<h3 class="page-product-heading">{l s='Product customization'}</h3>
				<!-- Customizable products -->
				<form method="post" action="{$customizationFormTarget}" enctype="multipart/form-data" id="customizationForm" class="clearfix">
					<p class="infoCustomizable">
						{l s='After saving your customized product, remember to add it to your cart.'}
						{if $product->uploadable_files}
						<br />
						{l s='Allowed file formats are: GIF, JPG, PNG'}{/if}
					</p>
					{if $product->uploadable_files|intval}
						<div class="customizableProductsFile">
							<h5 class="product-heading-h5">{l s='Pictures'}</h5>
							<ul id="uploadable_files" class="clearfix">
								{counter start=0 assign='customizationField'}
								{foreach from=$customizationFields item='field' name='customizationFields'}
									{if $field.type == 0}
										<li class="customizationUploadLine{if $field.required} required{/if}">{assign var='key' value='pictures_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
											{if isset($pictures.$key)}
												<div class="customizationUploadBrowse">
													<img src="{$pic_dir}{$pictures.$key}_small" alt="" />
														<a href="{$link->getProductDeletePictureLink($product, $field.id_customization_field)|escape:'html':'UTF-8'}" title="{l s='Delete'}" >
															<img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" class="customization_delete_icon" width="11" height="13" />
														</a>
												</div>
											{/if}
											<div class="customizationUploadBrowse form-group">
												<label class="customizationUploadBrowseDescription">
													{if !empty($field.name)}
														{$field.name}
													{else}
														{l s='Please select an image file from your computer'}
													{/if}
													{if $field.required}<sup>*</sup>{/if}
												</label>
												<input type="file" name="file{$field.id_customization_field}" id="img{$customizationField}" class="form-control customization_block_input {if isset($pictures.$key)}filled{/if}" />
											</div>
										</li>
										{counter}
									{/if}
								{/foreach}
							</ul>
						</div>
					{/if}
					{if $product->text_fields|intval}
						<div class="customizableProductsText">
							<h5 class="product-heading-h5">{l s='Text'}</h5>
							<ul id="text_fields">
							{counter start=0 assign='customizationField'}
							{foreach from=$customizationFields item='field' name='customizationFields'}
								{if $field.type == 1}
									<li class="customizationUploadLine{if $field.required} required{/if}">
										<label for ="textField{$customizationField}">
											{assign var='key' value='textFields_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
											{if !empty($field.name)}
												{$field.name}
											{/if}
											{if $field.required}<sup>*</sup>{/if}
										</label>
										<textarea name="textField{$field.id_customization_field}" class="form-control customization_block_input" id="textField{$customizationField}" rows="3" cols="20">{strip}
											{if isset($textFields.$key)}
												{$textFields.$key|stripslashes}
											{/if}
										{/strip}</textarea>
									</li>
									{counter}
								{/if}
							{/foreach}
							</ul>
						</div>
					{/if}
					<p id="customizedDatas">
						<input type="hidden" name="quantityBackup" id="quantityBackup" value="" />
						<input type="hidden" name="submitCustomizedDatas" value="1" />
						<button class="button btn btn-default button button-small" name="saveCustomization">
							<span>{l s='Save'}</span>
						</button>
						<span id="ajax-loader" class="unvisible">
							<img src="{$img_ps_dir}loader.gif" alt="loader" />
						</span>
					</p>
				</form>
				<p class="clear required"><sup>*</sup> {l s='required fields'}</p>
			</section>
			<!--end Customization -->
			{/if}
		{/if}
		{if isset($packItems) && $packItems|@count > 0}
		<section id="blockpack">
			<h3 class="page-product-heading">{l s='Pack content'}</h3>
			{include file="$tpl_dir./product-list.tpl" products=$packItems}
		</section>
		{/if}
	{/if}
{strip}
{if isset($smarty.get.ad) && $smarty.get.ad}
	{addJsDefL name=ad}{$base_dir|cat:$smarty.get.ad|escape:'html':'UTF-8'}{/addJsDefL}
{/if}
{if isset($smarty.get.adtoken) && $smarty.get.adtoken}
	{addJsDefL name=adtoken}{$smarty.get.adtoken|escape:'html':'UTF-8'}{/addJsDefL}
{/if}
{addJsDef allowBuyWhenOutOfStock=$allow_oosp|boolval}
{addJsDef availableNowValue=$product->available_now|escape:'quotes':'UTF-8'}
{addJsDef availableLaterValue=$product->available_later|escape:'quotes':'UTF-8'}
{addJsDef attribute_anchor_separator=$attribute_anchor_separator|escape:'quotes':'UTF-8'}
{addJsDef attributesCombinations=$attributesCombinations}
{addJsDef currentDate=$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}
{if isset($combinations) && $combinations}
	{addJsDef combinations=$combinations}
	{addJsDef combinationsFromController=$combinations}
	{addJsDef displayDiscountPrice=$display_discount_price}
	{addJsDefL name='upToTxt'}{l s='Up to' js=1}{/addJsDefL}
{/if}
{if isset($combinationImages) && $combinationImages}
	{addJsDef combinationImages=$combinationImages}
{/if}
{addJsDef customizationId=$id_customization}
{addJsDef customizationFields=$customizationFields}
{addJsDef default_eco_tax=$product->ecotax|floatval}
{addJsDef displayPrice=$priceDisplay|intval}
{addJsDef ecotaxTax_rate=$ecotaxTax_rate|floatval}
{if isset($cover.id_image_only)}
	{addJsDef idDefaultImage=$cover.id_image_only|intval}
{else}
	{addJsDef idDefaultImage=0}
{/if}
{addJsDef img_ps_dir=$img_ps_dir}
{addJsDef img_prod_dir=$img_prod_dir}
{addJsDef id_product=$product->id|intval}
{addJsDef jqZoomEnabled=$jqZoomEnabled|boolval}
{addJsDef maxQuantityToAllowDisplayOfLastQuantityMessage=$last_qties|intval}
{addJsDef minimalQuantity=$product->minimal_quantity|intval}
{addJsDef noTaxForThisProduct=$no_tax|boolval}
{if isset($customer_group_without_tax)}
	{addJsDef customerGroupWithoutTax=$customer_group_without_tax|boolval}
{else}
	{addJsDef customerGroupWithoutTax=false}
{/if}
{if isset($group_reduction)}
	{addJsDef groupReduction=$group_reduction|floatval}
{else}
	{addJsDef groupReduction=false}
{/if}
{addJsDef oosHookJsCodeFunctions=Array()}
{addJsDef productHasAttributes=isset($groups)|boolval}
{addJsDef productPriceTaxExcluded=($product->getPriceWithoutReduct(true)|default:'null' - $product->ecotax)|floatval}
{addJsDef productPriceTaxIncluded=($product->getPriceWithoutReduct(false)|default:'null' - $product->ecotax * (1 + $ecotaxTax_rate / 100))|floatval}
{addJsDef productBasePriceTaxExcluded=($product->getPrice(false, null, 6, null, false, false) - $product->ecotax)|floatval}
{addJsDef productBasePriceTaxExcl=($product->getPrice(false, null, 6, null, false, false)|floatval)}
{addJsDef productBasePriceTaxIncl=($product->getPrice(true, null, 6, null, false, false)|floatval)}
{addJsDef productReference=$product->reference|escape:'html':'UTF-8'}
{addJsDef productAvailableForOrder=$product->available_for_order|boolval}
{addJsDef productPriceWithoutReduction=$productPriceWithoutReduction|floatval}
{addJsDef productPrice=$productPrice|floatval}
{addJsDef productUnitPriceRatio=$product->unit_price_ratio|floatval}
{addJsDef productShowPrice=(!$PS_CATALOG_MODE && $product->show_price)|boolval}
{addJsDef PS_CATALOG_MODE=$PS_CATALOG_MODE}
{if $product->specificPrice && $product->specificPrice|@count}
	{addJsDef product_specific_price=$product->specificPrice}
{else}
	{addJsDef product_specific_price=array()}
{/if}
{if $display_qties == 1 && $product->quantity}
	{addJsDef quantityAvailable=$product->quantity}
{else}
	{addJsDef quantityAvailable=0}
{/if}
{addJsDef quantitiesDisplayAllowed=$display_qties|boolval}
{if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'percentage'}
	{addJsDef reduction_percent=$product->specificPrice.reduction*100|floatval}
{else}
	{addJsDef reduction_percent=0}
{/if}
{if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'amount'}
	{addJsDef reduction_price=$product->specificPrice.reduction|floatval}
{else}
	{addJsDef reduction_price=0}
{/if}
{if $product->specificPrice && $product->specificPrice.price}
	{addJsDef specific_price=$product->specificPrice.price|floatval}
{else}
	{addJsDef specific_price=0}
{/if}
{addJsDef specific_currency=($product->specificPrice && $product->specificPrice.id_currency)|boolval} {* TODO: remove if always false *}
{addJsDef stock_management=$PS_STOCK_MANAGEMENT|intval}
{addJsDef taxRate=$tax_rate|floatval}
{addJsDefL name=doesntExist}{l s='This combination does not exist for this product. Please select another combination.' js=1}{/addJsDefL}
{addJsDefL name=doesntExistNoMore}{l s='This product is no longer in stock' js=1}{/addJsDefL}
{addJsDefL name=doesntExistNoMoreBut}{l s='with those attributes but is available with others.' js=1}{/addJsDefL}
{addJsDefL name=fieldRequired}{l s='Please fill in all the required fields before saving your customization.' js=1}{/addJsDefL}
{addJsDefL name=uploading_in_progress}{l s='Uploading in progress, please be patient.' js=1}{/addJsDefL}
{addJsDefL name='product_fileDefaultHtml'}{l s='No file selected' js=1}{/addJsDefL}
{addJsDefL name='product_fileButtonHtml'}{l s='Choose File' js=1}{/addJsDefL}
{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
{/strip}
{/if}
