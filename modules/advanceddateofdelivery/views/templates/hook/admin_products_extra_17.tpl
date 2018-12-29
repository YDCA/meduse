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
	<div class="alert alert-warning" role="alert">
		<button class="close" data-dismiss="alert" type="button">×</button>
		{l s='There is 1 advertising.' mod='advanceddateofdelivery'}
		<ul id="seeMore" style="display:block;">
			<li>{l s='You must save the product in this shop before adding specific dates of delivery.' mod='advanceddateofdelivery'}</li>
		</ul>
	</div>
{else}
	<div class="row">
		<div class="col-md-12 combinations-list">
		{if !empty($product_combinations)}
			<div class="table-responsive-row clearfix">
				<table id="table-adod_combinations-list" class="table table-striped table-no-bordered uppercase">
					<thead>
						<tr class="nodrag nodrop">
							<th class=" left" style="width:50%"><span class="title_box"> {l s='Attribute - value pair' mod='advanceddateofdelivery'} </span></th>
							<th class=" left"><span class="title_box"> {l s='Additionnal time (in stock)' mod='advanceddateofdelivery'} </span></th>
							<th class=" left"><span class="title_box"> {l s='Additionnal time (out of stock)' mod='advanceddateofdelivery'} </span></th>
							<th class=" left"><span class="title_box"> {l s='Default combination' mod='advanceddateofdelivery'} </span></th>
						</tr>
					</thead>
					<tbody>
						<tr class="product_adod_all">
							<td class="title left">{l s='All combinations' mod='advanceddateofdelivery'}</td>
							<td class=" left">
								<div class="input-group col-lg-8">
									<input id="" class="form-control" type="text" maxlength="3" value="" name="autocomplete_adod_0">
									<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
								</div>
							</td>
							<td class=" left">
								<div class="input-group col-lg-8">
									<input class="form-control" id="" type="text" maxlength="3" value="" name="autocomplete_adod_1">
									<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
								</div>
							</td>
							<td></td>
						</tr>
						{foreach $product_combinations as $combination}
							<tr class="{if $combination.default_on}highlighted{/if} {cycle values="odd,"} product_adod">
								<td class=" left">{$combination.name|escape:'htmlall':'UTF-8'}</td>
								<td class=" left">
									<div class="input-group col-lg-8">
										<input type="text" maxlength="3" value="{$combination.extra_time.in_stock|escape:'htmlall':'UTF-8'}" name="product_adod[{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}][0]" class="form-control product_adod_input">
										<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
									</div>
								</td>
								<td class=" left">
									<div class="input-group col-lg-8">
										<input type="text" maxlength="3" value="{$combination.extra_time.out_stock|escape:'htmlall':'UTF-8'}" name="product_adod[{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}][1]" class="form-control product_adod_input">
										<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
									</div>
								</td>
								<td style="text-align:center"><i class="material-icons">{if $combination.default_on}radio_button_checked{else}radio_button_unchecked{/if}</i></td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		{else}
			<div class="row">
				<fieldset class="col-md-4 form-group">
					<label class="form-control-label">{l s='Additionnal time (in stock)' mod='advanceddateofdelivery'}</label>
					<div class="input-group">
						<input type="text" maxlength="3" value="{$extra_time_product.in_stock|escape:'htmlall':'UTF-8'}" name="product_adod[0][0]" class="form-control product_adod_input">
						<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
					</div>
				</fieldset>
			</div>		  
			<div class="row">
				<fieldset class="col-md-4 form-group">
					<label class="form-control-label">{l s='Additionnal time (out of stock)' mod='advanceddateofdelivery'}</label>
					<div class="input-group">
						<input type="text" maxlength="3" value="{$extra_time_product.out_stock|escape:'htmlall':'UTF-8'}" name="product_adod[0][1]" class="form-control product_adod_input">
						<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
					</div>
				</fieldset>
			</div>
		{/if}
		
		<div class="alert alert-info" role="alert">
			<ul>
				{*{if !empty($product_combinations)}<li>{l s='The blue row indicates the default combination.' mod='advanceddateofdelivery'}</li>{/if}*}
				<li>{l s='Processing time (in stock) is %d day(s).' sprintf=$config.ADOD_SHOP_PROCESSING_INSTOCK mod='advanceddateofdelivery'}</li>
				<li>{l s='Processing time (out of stock) is %d day(s).' sprintf=$config.ADOD_SHOP_PROCESSING_OUTSTOCK mod='advanceddateofdelivery'}</li>
				<li>{l s='Force stock:' mod='advanceddateofdelivery'} {if $config.ADOD_FORCE_STOCK}{l s='Yes' mod='advanceddateofdelivery'}{else}{l s='No' mod='advanceddateofdelivery'}{/if}.</li>
				{if !empty($product->is_virtual)}<li>{l s='Display for virtual products:' mod='advanceddateofdelivery'} {if $config.ADOD_ENABLE_FOR_VIRTUAL}{l s='Yes' mod='advanceddateofdelivery'}{else}{l s='No' mod='advanceddateofdelivery'}{/if}.</li>{/if}
			</ul>
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
					url: '{$module_url|escape:'htmlall':'UTF-8'}',
					controller : 'AdminModules',
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
	</div>
{/if}