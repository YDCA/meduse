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

<table class="adod_table_detail">
	{foreach from=$array_products item=dates name=array_products}
		<tr>
			<td colspan="2">
				<i class="icon-calendar"></i>
				<strong>
				{if ($array_products|@count) > 1}<b>{l s='Delivery %1$s:' sprintf=[$smarty.foreach.array_products.iteration] mod='advanceddateofdelivery'}</b>{/if}
				{if $dates.date_min == $dates.date_max}
					{l s='Approximate date of delivery with this carrier on %1$s' sprintf=[$dates.date_min] mod='advanceddateofdelivery'}
				{else}
					{l s='Approximate date of delivery with this carrier is between %1$s and %2$s' sprintf=[$dates.date_min, $dates.date_max] mod='advanceddateofdelivery'}
				{/if}</strong><sup>*</sup>
				{if $display_asap_option_detail}<span class="pull-right toggle_adod_products" rel="{$smarty.foreach.array_products.iteration|escape:'htmlall':'UTF-8'}"><i class="icon-angle-down"></i></span>{/if}
			</td>
		</tr>
		{if $display_asap_option_detail}
			{foreach $dates.products as $products}
				<tr class="adod_table_products_container adod_table_products_{$smarty.foreach.array_products.iteration|escape:'htmlall':'UTF-8'}">
					<td class="cart_product">
						<img src="{$link->getImageLink($products.product_datas.link_rewrite, $products.product_datas.id_image, 'cart_default')|escape:'html':'UTF-8'}" alt="{$products.product_datas.name|escape:'html':'UTF-8'}" />
					</td>
					<td class="cart_description">
						<p class="product-name">
							{$products.product_datas.name|escape:'htmlall':'UTF-8'}
						</p>
						{if $products.product_datas.reference}<small class="cart_ref">Référence: {$products.product_datas.reference|escape:'html':'UTF-8'}</small>{/if}
						{if isset($products.product_datas.attributes) && $products.product_datas.attributes}<small>
							{$products.product_datas.attributes|escape:'htmlall':'UTF-8'}
						</small>{/if}
					</td>
				</tr>
			{/foreach}
		{/if}
	{/foreach}
</table>

<script>
	$(document).ready(function(){
		$('.toggle_adod_products').click(function() {
			var delivery = $(this).attr('rel');
			$('.adod_table_products_'+delivery).toggle('1000');
			$("i", this).toggleClass("icon-angle-up icon-angle-down");
		});
	});
</script>