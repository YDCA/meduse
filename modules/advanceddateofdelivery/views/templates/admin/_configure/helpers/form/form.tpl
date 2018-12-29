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
* @package    Google Trusted Stores
* @copyright  Copyright (c) 2015 CREATYM (http://modules.creatym.fr)
* @author     Benjamin L.
* @license    Commercial license
* Support by mail  :  info@creatym.fr
* Support on forum :  advanceddateofdelivery
* Phone : +33.87230110
*}
{extends file="helpers/form/form.tpl"}

{block name="script"}
	var show_holidays_button_text = '{l s='Show calendar' mod='advanceddateofdelivery'}';
	var hide_holidays_button_text = '{l s='Hide calendar' mod='advanceddateofdelivery'}';
{/block}

{block name="label"}
	{if $smarty.const._PS_VERSION_ <= 1.5}
		{if $input.name == 'ADOD_DISPLAY_CARRIER_PRICE' || $input.name == 'ADOD_PRODUCT_POSITION' || $input.name == 'ADOD_PRODUCT_DISPLAY' || $input.name == 'ADOD_PRODUCT_PAGE_TXT'}
			<div class="display_product_page_options">
		{/if}
	{/if}
	
	{if $smarty.const._PS_VERSION_ <= 1.5}
		{if $input.name == 'ADOD_ALLOW_ASAP_OPTION_DETAIL'}
			<div class="display_allow_asap_options">
		{/if}
	{/if}
	
	{if $input.type == 'hr_tag'}
		<hr/>
	{else}
		{$smarty.block.parent}
	{/if}
	
	{if $smarty.const._PS_VERSION_ <= 1.5}
		{if $input.name == 'ADOD_DISPLAY_CARRIER_PRICE' || $input.name == 'ADOD_PRODUCT_POSITION' || $input.name == 'ADOD_PRODUCT_DISPLAY' || $input.name == 'ADOD_PRODUCT_PAGE_TXT'}
			</div>
		{/if}
	{/if}
	
	{if $smarty.const._PS_VERSION_ <= 1.5}
		{if $input.name == 'ADOD_ALLOW_ASAP_OPTION_DETAIL'}
			</div>
		{/if}
	{/if}
{/block}

{block name="input_row"}
	{if $smarty.const._PS_VERSION_ >= 1.6}
		{if $input.name == 'ADOD_DISPLAY_CARRIER_PRICE' || $input.name == 'ADOD_PRODUCT_POSITION' || $input.name == 'ADOD_PRODUCT_DISPLAY' || $input.name == 'ADOD_PRODUCT_PAGE_TXT'}
			<div class="display_product_page_options" id="container_{$input.name|escape:'htmlall':'UTF-8'}">
		{/if}
	{/if}
	
	{if $smarty.const._PS_VERSION_ >= 1.6}
		{if $input.name == 'ADOD_ALLOW_ASAP_OPTION_DETAIL'}
			<div class="display_allow_asap_options">
		{/if}
	{/if}
	
	{if $input.type == 'hr_tag'}
		<hr/>
	{else}
		{$smarty.block.parent}
	{/if}
	
	{if $smarty.const._PS_VERSION_ >= 1.6}
		{if $input.name == 'ADOD_DISPLAY_CARRIER_PRICE' || $input.name == 'ADOD_PRODUCT_POSITION' || $input.name == 'ADOD_PRODUCT_DISPLAY' || $input.name == 'ADOD_PRODUCT_PAGE_TXT'}
			</div>
		{/if}
	{/if}
	
	{if $smarty.const._PS_VERSION_ >= 1.6}
		{if $input.name == 'ADOD_ALLOW_ASAP_OPTION_DETAIL'}
			</div>
		{/if}
	{/if}
{/block}

{block name="input"}
    {if $input.type == 'ADOD_HOLIDAYS'}
		<a class="btn btn-default" href="#" id="button_calendar_action">
			<i class="icon-eye-open"></i>
			<span>{l s='Show calendar' mod='advanceddateofdelivery'}</span>
		</a>
	    <div class="table-responsive clearfix holiday_table_container" style="display:none">
			<table class="table holidays_table">
				<thead>
					<tr class="nodrag nodrop">
						<th class="center"><span class="title_box"> {l s='Jan' mod='advanceddateofdelivery'} </span></th>
						<th class="center"><span class="title_box"> {l s='Feb' mod='advanceddateofdelivery'} </span></th>
						<th class="center"><span class="title_box"> {l s='Mar' mod='advanceddateofdelivery'} </span></th>
						<th class="center"><span class="title_box"> {l s='Apr' mod='advanceddateofdelivery'} </span></th>
						<th class="center"><span class="title_box"> {l s='May' mod='advanceddateofdelivery'} </span></th>
						<th class="center"><span class="title_box"> {l s='Jun' mod='advanceddateofdelivery'} </span></th>
						<th class="center"><span class="title_box"> {l s='Jul' mod='advanceddateofdelivery'} </span></th>
						<th class="center"><span class="title_box"> {l s='Aug' mod='advanceddateofdelivery'} </span></th>
						<th class="center"><span class="title_box"> {l s='Sep' mod='advanceddateofdelivery'} </span></th>
						<th class="center"><span class="title_box"> {l s='Oct' mod='advanceddateofdelivery'} </span></th>
						<th class="center"><span class="title_box"> {l s='Nov' mod='advanceddateofdelivery'} </span></th>
						<th class="center"><span class="title_box"> {l s='Dec' mod='advanceddateofdelivery'} </span></th>
				</thead>
				<tbody>
					{for $foo=1 to 31}
						<tr>
							<td class="left odd"><input type="checkbox" name="ADOD_HOLIDAYS[]" value="1_{$foo|escape:'htmlall':'UTF-8'}" {if in_array("1_{$foo}",$adod_holidays)}checked="checked"{/if}/> {$foo|escape:'htmlall':'UTF-8'}</td>
							<td class="left">{if $foo <= '29'}<input type="checkbox" name="ADOD_HOLIDAYS[]" value="2_{$foo|escape:'htmlall':'UTF-8'}" {if in_array("2_{$foo}",$adod_holidays)}checked="checked"{/if} /> {$foo|escape:'htmlall':'UTF-8'}{/if}</td>
							<td class="left odd"><input type="checkbox" name="ADOD_HOLIDAYS[]" value="3_{$foo|escape:'htmlall':'UTF-8'}" {if in_array("3_{$foo}",$adod_holidays)}checked="checked"{/if} /> {$foo|escape:'htmlall':'UTF-8'}</td>
							<td class="left">{if $foo <= '30'}<input type="checkbox" name="ADOD_HOLIDAYS[]" value="4_{$foo|escape:'htmlall':'UTF-8'}" {if in_array("4_{$foo}",$adod_holidays)}checked="checked"{/if} /> {$foo|escape:'htmlall':'UTF-8'}{/if}</td>
							<td class="left odd"><input type="checkbox" name="ADOD_HOLIDAYS[]" value="5_{$foo|escape:'htmlall':'UTF-8'}" {if in_array("5_{$foo}",$adod_holidays)}checked="checked"{/if} /> {$foo|escape:'htmlall':'UTF-8'}</td>
							<td class="left">{if $foo <= '30'}<input type="checkbox" name="ADOD_HOLIDAYS[]" value="6_{$foo|escape:'htmlall':'UTF-8'}" {if in_array("6_{$foo}",$adod_holidays)}checked="checked"{/if} /> {$foo|escape:'htmlall':'UTF-8'}{/if}</td>
							<td class="left odd"><input type="checkbox" name="ADOD_HOLIDAYS[]" value="7_{$foo|escape:'htmlall':'UTF-8'}" {if in_array("7_{$foo}",$adod_holidays)}checked="checked"{/if} /> {$foo|escape:'htmlall':'UTF-8'}</td>
							<td class="left"><input type="checkbox" name="ADOD_HOLIDAYS[]" value="8_{$foo|escape:'htmlall':'UTF-8'}" {if in_array("8_{$foo}",$adod_holidays)}checked="checked"{/if} /> {$foo|escape:'htmlall':'UTF-8'}</td>
							<td class="left odd">{if $foo <= '30'}<input type="checkbox" name="ADOD_HOLIDAYS[]" value="9_{$foo|escape:'htmlall':'UTF-8'}" {if in_array("9_{$foo}",$adod_holidays)}checked="checked"{/if} /> {$foo|escape:'htmlall':'UTF-8'}{/if}</td>
							<td class="left"><input type="checkbox" name="ADOD_HOLIDAYS[]" value="10_{$foo|escape:'htmlall':'UTF-8'}" {if in_array("10_{$foo}",$adod_holidays)}checked="checked"{/if} /> {$foo|escape:'htmlall':'UTF-8'}</td>
							<td class="left odd">{if $foo <= '30'}<input type="checkbox" name="ADOD_HOLIDAYS[]" value="11_{$foo|escape:'htmlall':'UTF-8'}" {if in_array("11_{$foo}",$adod_holidays)}checked="checked"{/if} /> {$foo|escape:'htmlall':'UTF-8'}{/if}</td>
							<td class="left"><input type="checkbox" name="ADOD_HOLIDAYS[]" value="12_{$foo|escape:'htmlall':'UTF-8'}" {if in_array("12_{$foo}",$adod_holidays)}checked="checked"{/if} /> {$foo|escape:'htmlall':'UTF-8'}</td>
						</tr>
					{/for}
				</tbody>
			</table>
		</div>
	{elseif $input.type == 'ADOD_CARRIERS_PROCESSING_TIME'}
		<div class="table-responsive clearfix">
			<table class="table">
				<thead>
					<tr class="nodrag nodrop">
						<th class=""><span class="title_box"> {l s='Carrier name' mod='advanceddateofdelivery'} </span></th>
						<th class=""><span class="title_box"> {l s='Active' mod='advanceddateofdelivery'} </span></th>
						<th class=""><span class="title_box"> {l s='Delivery processing time min (in days)' mod='advanceddateofdelivery'} </span></th>
						<th class=""><span class="title_box"> {l s='Delivery processing time max (in days)' mod='advanceddateofdelivery'} </span></th>
						<th class=""><span class="title_box"> {l s='Order hour limit (for processing same day)' mod='advanceddateofdelivery'} </span></th>
						<th class=" center"><span class="title_box"> {l s='Carrier delivery days' mod='advanceddateofdelivery'} </span></th>
					</tr>
				</thead>
				<tbody>
					{foreach $adod_carriers as $carrier}
						<tr class="{cycle values="odd,"}">
							<td>{$carrier.name|escape:'htmlall':'UTF-8'}</td>
							<td>
								<span class="switch prestashop-switch fixed-width-lg">
									<input type="radio" name="is_active_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}" id="is_active_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}_on" value="1" {if $carrier.is_active}checked="checked" {/if} />
									<label for="is_active_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}_on" class="radioCheck">
										{l s='Yes' mod='advanceddateofdelivery'}
									</label>
									<input type="radio" name="is_active_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}" id="is_active_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}_off" value="0" {if !$carrier.is_active}checked="checked"{/if} />
									<label for="is_active_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}_off" class="radioCheck">
										{l s='No' mod='advanceddateofdelivery'}
									</label>
									<a class="slide-button btn"></a>
								</span>
							</td>
							<td>
								<div class="input-group">
									<input type="text" name="processing_days_min_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}" value="{$carrier.processing_days_min|escape:'htmlall':'UTF-8'}" />
									<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
								</div>
							</td>
							<td>
								<div class="input-group">
									<input type="text" name="processing_days_max_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}" value="{$carrier.processing_days_max|escape:'htmlall':'UTF-8'}" />
									<span class="input-group-addon"> {l s='day(s)' mod='advanceddateofdelivery'} </span>
								</div>
							</td>
							<td>
								<div class="input-group">
									<select name="processing_hour_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}">
										{for $foo=0 to 23}
											<option value="{if $foo < 10}0{/if}{$foo|escape:'htmlall':'UTF-8'}" {if $carrier.hour_limit|date_format:"%H" == $foo}selected="selected"{/if}>{if $foo < 10}0{/if}{$foo|escape:'htmlall':'UTF-8'}</option>
										{/for}
									</select>
									<span class="input-group-addon"> {l s='hour(s)' mod='advanceddateofdelivery'}</span>
									<select name="processing_min_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}">
										<option value="00" {if $carrier.hour_limit|date_format:"%M" == "00"}selected="selected"{/if}>00</option>
										<option value="15" {if $carrier.hour_limit|date_format:"%M" == "15"}selected="selected"{/if}>15</option>
										<option value="30" {if $carrier.hour_limit|date_format:"%M" == "30"}selected="selected"{/if}>30</option>
										<option value="45" {if $carrier.hour_limit|date_format:"%M" == "45"}selected="selected"{/if}>45</option>
									</select>
									<span class="input-group-addon"> {l s='min(s)' mod='advanceddateofdelivery'}</span>
								</div>
							</td>
							<td>
								<input type="checkbox" name="delivery_days_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}[]" value="1" {if in_array("1",$carrier.delivery_days)}checked="checked"{/if}/> {l s='Monday' mod='advanceddateofdelivery'} <br/>
								<input type="checkbox" name="delivery_days_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}[]" value="2" {if in_array("2",$carrier.delivery_days)}checked="checked"{/if}/> {l s='Tuesday' mod='advanceddateofdelivery'} <br/>
								<input type="checkbox" name="delivery_days_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}[]" value="3" {if in_array("3",$carrier.delivery_days)}checked="checked"{/if}/> {l s='Wednesday' mod='advanceddateofdelivery'} <br/>
								<input type="checkbox" name="delivery_days_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}[]" value="4" {if in_array("4",$carrier.delivery_days)}checked="checked"{/if}/> {l s='Thursday' mod='advanceddateofdelivery'} <br/>
								<input type="checkbox" name="delivery_days_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}[]" value="5" {if in_array("5",$carrier.delivery_days)}checked="checked"{/if}/> {l s='Friday' mod='advanceddateofdelivery'} <br/>
								<input type="checkbox" name="delivery_days_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}[]" value="6" {if in_array("6",$carrier.delivery_days)}checked="checked"{/if}/> {l s='Saturday' mod='advanceddateofdelivery'} <br/>
								<input type="checkbox" name="delivery_days_{$carrier.id_carrier|escape:'htmlall':'UTF-8'}[]" value="0" {if in_array("0",$carrier.delivery_days)}checked="checked"{/if}/> {l s='Sunday' mod='advanceddateofdelivery'}
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	{elseif $input.type == 'ADOD_CLOSING_DAYS'}
		<div class="checkbox"><label><input type="checkbox" name="ADOD_CLOSING_DAYS[]" value="1" {if in_array("1",$adod_closing_days)}checked="checked"{/if}/> {l s='Monday' mod='advanceddateofdelivery'}</label></div>
		<div class="checkbox"><label><input type="checkbox" name="ADOD_CLOSING_DAYS[]" value="2" {if in_array("2",$adod_closing_days)}checked="checked"{/if}/> {l s='Tuesday' mod='advanceddateofdelivery'}</label></div>
		<div class="checkbox"><label><input type="checkbox" name="ADOD_CLOSING_DAYS[]" value="3" {if in_array("3",$adod_closing_days)}checked="checked"{/if}/> {l s='Wednesday' mod='advanceddateofdelivery'}</label></div>
		<div class="checkbox"><label><input type="checkbox" name="ADOD_CLOSING_DAYS[]" value="4" {if in_array("4",$adod_closing_days)}checked="checked"{/if}/> {l s='Thursday' mod='advanceddateofdelivery'}</label></div>
		<div class="checkbox"><label><input type="checkbox" name="ADOD_CLOSING_DAYS[]" value="5" {if in_array("5",$adod_closing_days)}checked="checked"{/if}/> {l s='Friday' mod='advanceddateofdelivery'}</label></div>
		<div class="checkbox"><label><input type="checkbox" name="ADOD_CLOSING_DAYS[]" value="6" {if in_array("6",$adod_closing_days)}checked="checked"{/if}/> {l s='Saturday' mod='advanceddateofdelivery'}</label></div>
		<div class="checkbox"><label><input type="checkbox" name="ADOD_CLOSING_DAYS[]" value="0" {if in_array("0",$adod_closing_days)}checked="checked"{/if}/> {l s='Sunday' mod='advanceddateofdelivery'}</label></div>
	{else}
		{$smarty.block.parent}
    {/if}
{/block}
