{*
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from CREATYM
 * Use, copy, modification or distribution of this source file without written
 * license agreement from CREATYM is strictly forbidden.
 * In order to obtain a license, please contact us: info@creatym.fr
 * ...........................................................................
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * concedee par la societe CREATYM
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de la part de CREATYM est
 * expressement interdite.
 * Pour obtenir une licence, veuillez contacter CREATYM a l'adresse: info@creatym.fr
 * ...........................................................................
 *
 * @package   Advanced Date of delivery
 * @author    Benjamin L.
 * @copyright 2016 Créatym <http://modules.creatym.fr>
 * @license   Commercial license
 * Support by mail  :  info@creatym.fr
 * Support on forum :  advanceddateofdelivery
 * Phone : +33.87230110
*}
<script type="text/javascript">
	var advanceddateofdeliverytext = '{if !$adod_product_page_txt}{l s='Delivery date' mod='advanceddateofdelivery'}{else}{$adod_product_page_txt|escape:'htmlall':'UTF-8'}{/if}';
	{literal}
		var productFooterDatesDelivery = {};
		var adod_text_single_date = "{/literal}{l s='Approximate date of delivery on' mod='advanceddateofdelivery'}{literal}";
		var adod_text_dates = "{/literal}{l s='Approximate date of delivery is between' mod='advanceddateofdelivery'}{literal}";
		var adod_text_unavailable_dates = "{/literal}{l s='Estimated delivery not available' mod='advanceddateofdelivery'}{literal}";
		var adod_product_display = "{/literal}{$adod_product_display}{literal}";
		var carrier_delivery_list = "{/literal}{$carrier_delivery_list|@count}{literal}";
	{/literal}
	
	productFooterDatesDelivery[0] = {};
			
	{foreach from=$carrier_delivery_list item=carrier}
		{foreach $carrier.delivery_date as $by_address}
			{foreach $by_address as $product_attribute}
				productFooterDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}][{$product_attribute@key|escape:'htmlall':'UTF-8'}] = {};
				{foreach $product_attribute as $date}
					{if $date && isset($date[0])}
						productFooterDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}][{$product_attribute@key|escape:'htmlall':'UTF-8'}]["{$date@key|escape:'htmlall':'UTF-8'}"] = {};
						productFooterDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}][{$product_attribute@key|escape:'htmlall':'UTF-8'}]["{$date@key|escape:'htmlall':'UTF-8'}"]['minimal'] = ["{$date.0.date_min|escape:'html':'UTF-8'}",{$date.0.time_min|escape:'htmlall':'UTF-8'}];
						productFooterDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}][{$product_attribute@key|escape:'htmlall':'UTF-8'}]["{$date@key|escape:'htmlall':'UTF-8'}"]['maximal'] = ["{$date.1.date_max|escape:'html':'UTF-8'}",{$date.1.time_max|escape:'htmlall':'UTF-8'}];
					{/if}
				{/foreach}
			{/foreach}
		{/foreach}
	{/foreach}
	
	{literal}
		function htmlDecode(input){
			var e = document.createElement('div');
			e.innerHTML = input;
			return e.childNodes[0].nodeValue;
		}
	{/literal}
</script>

<section class="page-product-box" id="advanceddateofdelivery_footer_product" {if $adod_product_display == 'PRODUCT_FANCYBOX'}style="display:none"{/if}>
	<h3 class="page-product-heading">{l s='Delivery date' mod='advanceddateofdelivery'}</h3>
	<div class="advanceddateofdelivery_product">
		<table class="table-data-delivery table table-bordered" id="product_available_carriers">
			<thead>
				<tr>
					<th class="carrier_name first_item">{l s='Carrier' mod='advanceddateofdelivery'}</th>
					<th class="carrier_infos item">{l s='Information' mod='advanceddateofdelivery'}</th>
					{if isset($display_carrier_price) && $display_carrier_price}<th class="carrier_price item">{l s='Price' mod='advanceddateofdelivery'}</th>{/if}
					<th class="carrier_price last_item">{l s='Delivery' mod='advanceddateofdelivery'}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$carrier_delivery_list item=carrier}
					<tr class="{cycle values="odd,even"}" id="carrierItem_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}">
						<td>
							{if $carrier.img}
								<img src="{$carrier.img|escape:'htmlall':'UTF-8'}" alt="{$carrier.name|escape:'htmlall':'UTF-8'}" title="{$carrier.name|escape:'htmlall':'UTF-8'}" style="max-width:100px;"/>
							{else}
								{$carrier.name|escape:'htmlall':'UTF-8'}
							{/if}
						</td>
						<td>
							{if $carrier.delay != null}
								{$carrier.delay|escape:'htmlall':'UTF-8'}
							{/if}
						</td>
						{if isset($display_carrier_price) && $display_carrier_price}
						<td>
							{if $carrier.price}
								<span class="price">
									{if $priceDisplay}
										{displayPrice price=$carrier.price_tax_exc} {if $display_tax_label} {l s='(tax excl.)' mod='advanceddateofdelivery'}{/if}
									{else}
										{displayPrice price=$carrier.price} {if $display_tax_label} {l s='(tax incl.)' mod='advanceddateofdelivery'}{/if}
									{/if}
								</span>
							{else}
								{l s='Free!' mod='advanceddateofdelivery'}
							{/if}
						</td>
						{/if}
						<td class="delivery_dates">
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>

		<span style="font-size:10px;margin:0;padding:0;"><sup>*</sup> {l s='with direct payment methods (e.g. credit card)' mod='advanceddateofdelivery'}</span>
	</div>
</section>