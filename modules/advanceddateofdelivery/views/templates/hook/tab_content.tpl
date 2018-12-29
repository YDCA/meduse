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
	{literal}
		var productTabDatesDelivery = {};
	{/literal}
	
	{foreach from=$carrier_delivery_list item=carrier}
		{foreach $carrier.delivery_date as $by_address}
			productTabDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}] = {};
			{foreach $by_address as $product_attribute}
				productTabDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}][{$product_attribute@key|escape:'htmlall':'UTF-8'}] = {};
				{foreach $product_attribute as $date}
					{if $date && isset($date[0])}
						productTabDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}][{$product_attribute@key|escape:'htmlall':'UTF-8'}]["{$date@key|escape:'htmlall':'UTF-8'}"] = {};
						productTabDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}][{$product_attribute@key|escape:'htmlall':'UTF-8'}]["{$date@key|escape:'htmlall':'UTF-8'}"]['minimal'] = ["{$date.0.date_min|escape:'htmlall':'UTF-8'}",{$date.0.time_min|escape:'htmlall':'UTF-8'}];
						productTabDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}][{$product_attribute@key|escape:'htmlall':'UTF-8'}]["{$date@key|escape:'htmlall':'UTF-8'}"]['maximal'] = ["{$date.1.date_max|escape:'htmlall':'UTF-8'}",{$date.1.time_max|escape:'htmlall':'UTF-8'}];
					{/if}
				{/foreach}
			{/foreach}
		{/foreach}
	{/foreach}
	
	{literal}
	
		function getProductAttribute()
		{
			refreshProductTabDateOfDelivery();
		}
		
		function refreshProductTabDateOfDelivery()
		{			
			product_attribute_id = $('#idCombination').val();
			product_id = $('#product_page_product_id').val();
			
			if(product_attribute_id.length == 0)
				product_attribute_id = 0;
				
			$.each($('#product_available_carriers tbody tr'), function()
			{
				var date_from = null;
				var date_to = null;
				var set = true;
				tr_class = $(this).attr('id');
				id_carrier = tr_class.substr(tr_class.indexOf("_") + 1);
				
				var date = productTabDatesDelivery[0][id_carrier][product_id+"_"+product_attribute_id];

				if (typeof(date) != 'undefined')
				{
					if (date_from == null || date_from[1] < date['minimal'][1])
						date_from = date['minimal'];
					if (date_to == null || date_to[1] < date['maximal'][1])
						date_to = date['maximal'];
				}
				else
					set = false;
					
				if (date_from != null && date_to != null && set)
				{
					if(date_from[0] == date_to[0])
					{
						$(this).find($('.delivery_dates')).html("{/literal}{l s='Approximate date of delivery on' mod='advanceddateofdelivery'}{literal} <span id='maximal'><b>"+date_to[0]+"</b></span> <sup>*</sup>");
					}
					else
					{
						$(this).find($('.delivery_dates')).html("{/literal}{l s='Approximate date of delivery is between' mod='advanceddateofdelivery'}{literal} <span id='minimal'><b>"+date_from[0]+"</b></span> {/literal}{l s='and' mod='advanceddateofdelivery'}{literal} <span id='maximal'><b>"+date_to[0]+"</b></span> <sup>*</sup>");
					}
				}
				else
					$(this).find($('.delivery_dates')).html("{/literal}{l s='Estimated delivery not available' mod='advanceddateofdelivery'}{literal}");
			});
		}
		
		$(function(){		
			refreshProductTabDateOfDelivery();
		});
	{/literal}
</script>

<div id="idTab010">
	<div id="product_delivery_date_tab">
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
</div>
