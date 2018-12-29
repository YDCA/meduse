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

{if $smarty.const._PS_VERSION_ >= 1.6}
	<div class="row">
		<div class="col-lg-7">
			<div class="panel">
				<div class="panel-heading">
					<i class="icon-calendar"></i>
					{l s='Delivery date' mod='advanceddateofdelivery'}
				</div>
			
				<div class="form-horizontal">
					{foreach from=$order_dates item=dates name=order_dates}
						<div class="well">
							<p>
								<b>{l s='Delivery %1$s:' sprintf=[$smarty.foreach.order_dates.iteration] mod='advanceddateofdelivery'}</b>
								{if $dates.date_min == $dates.date_max}
									{l s='Approximate date of delivery on %1$s' sprintf=[$dates.date_min] mod='advanceddateofdelivery'}
								{else}
									{l s='Approximate date of delivery is between %1$s and %2$s' sprintf=[$dates.date_min, $dates.date_max] mod='advanceddateofdelivery'}
								{/if}</strong><sup>*</sup>
							</p>
							
							{if $display_asap_option_detail}
								<table class="table adod_table_order_products">
								{foreach $dates.products as $products}
									<tr>
										<td class="cart_product">
											{if isset($products.product_datas.image) && $products.product_datas.image->id}{$products.product_datas.image_tag}{/if}
										</td>
										<td class="cart_description">
											<p class="product-name">
												{$products.product_datas.product_name|escape:'htmlall':'UTF-8'}
											</p>
											{if $products.product_datas.reference}<small class="cart_ref">Référence: {$products.product_datas.reference|escape:'html':'UTF-8'}</small>{/if}
										</td>
									</tr>
								{/foreach}
								</table>
							{/if}
							
							<p>
								{l s='Inidicative shipping date on %1$s.' sprintf=[$dates.shipping_date] mod='advanceddateofdelivery'}
							</p>
						</div>
					{/foreach}
				</div>
			</div>
		</div>
	</div>
{else}
	<br>
	<fieldset>
		<legend>
			<img src="../img/admin/date.png">
			{l s='Delivery date' mod='advanceddateofdelivery'}
		</legend>
		
		<div>
			DATES
		</div>
	</fieldset>
{/if}