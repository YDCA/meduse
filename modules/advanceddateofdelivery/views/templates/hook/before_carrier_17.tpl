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

{if $cartDatesDelivery|count}
	<script type="text/javascript">
		{literal}
			var cartDatesDelivery = {};
		{/literal}
		{foreach $cartDatesDelivery as $by_address}
			cartDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}] = {};
			{foreach $by_address as $date}
				{if $date && isset($date[0])}
					cartDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}]["{$date@key|escape:'htmlall':'UTF-8'}"] = {};
					cartDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}]["{$date@key|escape:'htmlall':'UTF-8'}"]['minimal'] = ["{$date.0.date_min|escape:'html':'UTF-8'}",{$date.0.time_min|escape:'htmlall':'UTF-8'}];
					cartDatesDelivery[{$by_address@key|escape:'htmlall':'UTF-8'}]["{$date@key|escape:'htmlall':'UTF-8'}"]['maximal'] = ["{$date.1.date_max|escape:'html':'UTF-8'}",{$date.1.time_max|escape:'htmlall':'UTF-8'}];
				{/if}
			{/foreach}
		{/foreach}
	</script>

	<div id="advanceddateofdelivery" class="advanced_box row">
		<i class="icon-calendar"></i>
		{*{if $nbPackages <= 1}
			{l s='Approximate date of delivery with this carrier is between' mod='advanceddateofdelivery'}
		{else}
			{l s='There are %s packages, that will be approximately delivered with the delivery option you choose between' sprintf=$nbPackages mod='advanceddateofdelivery'}
		{/if}
			<span id="minimal"></span> {l s='and' mod='advanceddateofdelivery'} <span id="maximal"></span> <sup>*</sup>
		<br />*}
		<p class="delivery_dates"></p>
		<span style="font-size:10px;margin:0;padding:0;"><sup>*</sup> {l s='with direct payment methods (e.g. credit card)' mod='advanceddateofdelivery'}</span>
	</div>
{/if}
