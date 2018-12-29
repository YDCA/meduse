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

<div id="advanceddateofdelivery" class="info-delivery box">
	{foreach from=$order_dates item=dates name=order_dates}
		<strong>
			<i class="icon-calendar"></i>
			<b>{l s='Delivery %1$s:' sprintf=[$smarty.foreach.order_dates.iteration] mod='advanceddateofdelivery'}</b>
			{if $dates.date_min == $dates.date_max}
				{l s='Approximate date of delivery on %1$s' sprintf=[$dates.date_min] mod='advanceddateofdelivery'}
			{else}
				{l s='Approximate date of delivery is between %1$s and %2$s' sprintf=[$dates.date_min, $dates.date_max] mod='advanceddateofdelivery'}
			{/if}</strong><sup>*</sup>
		</strong><br/>		
	{/foreach}
	
	<span style="font-size:10px;margin:0;padding:0;"><sup>*</sup> {l s='with direct payment methods (e.g. credit card)' mod='advanceddateofdelivery'}</span>
</div>
