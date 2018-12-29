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

{if isset($waiting_first_save) && $waiting_first_save}
	<div class="alert alert-warning">
		<button class="close" data-dismiss="alert" type="button">×</button>
		{l s='There is 1 advertising.' mod='advanceddateofdelivery'}
		<ul id="seeMore" style="display:block;">
			<li>{l s='You must save the product in this shop before adding specific dates of delivery.' mod='advanceddateofdelivery'}</li>
		</ul>
	</div>
{else}
	<div id="product-combinations-advanceddateofdelivery" class="panel product-tab">
		<h3>{l s='Advanced date of delivery' mod='advanceddateofdelivery'}</h3>
		
		{if $smarty.const._PS_VERSION_ >= 1.6}
		{else}
		{/if}
		
		{if !empty($product_combinations)}
			<div class="panel col-lg-12">
				<div class="table-responsive-row clearfix">
					<table id="table-combinations-list" class="table configuration">
						<thead>
							<tr class="nodrag nodrop">
								<th class=" left" style="width:50%"><span class="title_box"> {l s='Attribute - value pair' mod='advanceddateofdelivery'} </span></th>
								<th class=" left"><span class="title_box"> {l s='Additionnal time (in stock)' mod='advanceddateofdelivery'} </span></th>
								<th class=" left"><span class="title_box"> {l s='Additionnal time (out of stock)' mod='advanceddateofdelivery'} </span></th>
							</tr>
						</thead>
						<tbody>
							<tr class="product_adod_all">
								<td class="title left">{l s='All combinations' mod='advanceddateofdelivery'}</td>
								<td class=" left">
									<div class="input-group col-lg-6">
										<input id="" type="text" maxlength="3" value="" name="autocomplete_adod_0">
										<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
									</div>
								</td>
								<td class=" left">
									<div class="input-group col-lg-6">
										<input id="" type="text" maxlength="3" value="" name="autocomplete_adod_1">
										<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
									</div>
								</td>
							</tr>
							{foreach $product_combinations as $combination}
								<tr class="{if $combination.default_on}highlighted{/if} {cycle values="odd,"} product_adod">
									<td class=" left">{$combination.name|escape:'htmlall':'UTF-8'}</td>
									<td class=" left">
										<div class="input-group col-lg-6">
											<input type="text" maxlength="3" value="{$combination.extra_time.in_stock|escape:'htmlall':'UTF-8'}" name="product_adod[{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}][0]" class="product_adod_input">
											<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
										</div>
									</td>
									<td class=" left">
										<div class="input-group col-lg-6">
											<input type="text" maxlength="3" value="{$combination.extra_time.out_stock|escape:'htmlall':'UTF-8'}" name="product_adod[{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}][1]" class="product_adod_input">
											<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
										</div>
									</td>
								</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
			</div>		
		{else}
			<div class="form-group">
				<div class="col-lg-1"><span class="pull-right"> </span></div>
				<label class="control-label col-lg-3">{l s='Additionnal time (in stock)' mod='advanceddateofdelivery'}</label>		
				<div class="col-lg-2">
					<div class="input-group">
						<input type="text" maxlength="3" value="{$extra_time_product.in_stock|escape:'htmlall':'UTF-8'}" name="product_adod[0][0]" class="product_adod_input">
						<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-1"><span class="pull-right"> </span></div>
				<label class="control-label col-lg-3">{l s='Additionnal time (out of stock)' mod='advanceddateofdelivery'}</label>		
				<div class="col-lg-2">
					<div class="input-group">
						<input type="text" maxlength="3" value="{$extra_time_product.out_stock|escape:'htmlall':'UTF-8'}" name="product_adod[0][1]" class="product_adod_input">
						<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
					</div>
				</div>
			</div>
		{/if}
		
		<div class="alert alert-info" style="display:block">
			<ul>
				{if !empty($product_combinations)}<li>{l s='The blue row indicates the default combination.' mod='advanceddateofdelivery'}</li>{/if}
				<li>{l s='Processing time (in stock) is %d day(s).' sprintf=$config.ADOD_SHOP_PROCESSING_INSTOCK mod='advanceddateofdelivery'}</li>
				<li>{l s='Processing time (out of stock) is %d day(s).' sprintf=$config.ADOD_SHOP_PROCESSING_OUTSTOCK mod='advanceddateofdelivery'}</li>
				<li>{l s='Force stock:' mod='advanceddateofdelivery'} {if $config.ADOD_FORCE_STOCK}{l s='Yes' mod='advanceddateofdelivery'}{else}{l s='No' mod='advanceddateofdelivery'}{/if}.</li>
				{if !empty($product->is_virtual)}<li>{l s='Display for virtual products:' mod='advanceddateofdelivery'} {if $config.ADOD_ENABLE_FOR_VIRTUAL}{l s='Yes' mod='advanceddateofdelivery'}{else}{l s='No' mod='advanceddateofdelivery'}{/if}.</li>{/if}
			</ul>
		</div>
		
		<div class="panel-footer">
			<a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}{if isset($smarty.request.page) && $smarty.request.page > 1}&amp;submitFilterproduct={$smarty.request.page|intval}{/if}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='advanceddateofdelivery'}</a>
			<button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='advanceddateofdelivery'}</button>
			<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay' mod='advanceddateofdelivery'}</button>
		</div>
		
		
		<script type="text/javascript">
			$(document).ready(function(){
				bind_inputs_adod();
				
				$(".product_adod_input").bind("change", function()
				{
					updateProductExtraTime(this);
					return false;
				});
			});

			function bind_inputs_adod()
			{
				$(document.body).off('change', 'tr.product_adod_all td input').on('change', 'tr.product_adod_all td input', function() {
				    index = $(this).closest('td').index();
					val = $(this).val();
					$(this).val('');
					$('tr.product_adod').each(function () {
						$(this).find('td:eq('+index+') input:text:enabled').val(val);
						updateProductExtraTime($(this).find('.product_adod_input'));
					});

					return false;
				});
			}

			function updateProductExtraTime(content)
			{
				var input_name = $(content).attr('name');
				var regex = /\[([^\]]*)\]/g;
				match = regex.exec(input_name);
				
				var id_attribute = match[1];
				
				$.ajax({
					url: '../modules/advanceddateofdelivery/ajax.php',
					controller : 'AdminModules',
					type: "POST",
					action : 'ProductOutOfStockDays',
					data: {
						ajax: true,
						id_product_attribute : id_attribute,
						value_min : $("input[name='product_adod["+id_attribute+"][0]']").val(),
						value_max : $("input[name='product_adod["+id_attribute+"][1]']").val(),
						id_product : {$product->id|escape:'htmlall':'UTF-8'},
					},
					context: document.body,
					dataType: 'json',
					context: content,
					async: false,
					success: function(msg)
					{
					if (msg.error)
					{
						showAjaxError(msg.error);
						return;
					}
					showSuccessMessage("{l s='Datas registered with success.' mod='advanceddateofdelivery'}");
					},
					error: function(msg)
					{
						showAjaxError(msg.error);
					}
				});
			}
		</script>	
	</div>
{/if}