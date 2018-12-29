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
	var module_controller_url = '{$module_controller_url|escape:'htmlall':'UTF-8'}';
	var secure_key = '{$secure_key|escape:'htmlall':'UTF-8'}';
	var cartDatesDelivery = {$cartDatesDelivery nofilter};
	</script>

	<div id="advanceddateofdelivery" class="advanced_box row">
		<p class="delivery_dates"></p>
		<span style="font-size:10px;margin:0;padding:0;"><sup>*</sup> {l s='with direct payment methods (e.g. credit card)' mod='advanceddateofdelivery'}</span>
	</div>
{/if}
