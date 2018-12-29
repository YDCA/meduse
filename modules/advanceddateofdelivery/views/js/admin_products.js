/*
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
* @author    Benjamin L.
* @copyright 2017 Créatym <http://modules.creatym.fr>
* @license   Commercial license
* Support by mail  :  info@creatym.fr
* Support on forum :  advanceddateofdelivery
* Phone : +33.87230110
*/
 
$(document).ready(function() {
	bind_inputs_adod();
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