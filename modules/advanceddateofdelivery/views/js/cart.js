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

jQuery(document).ready(function ($) {
	// Only for Prestashop 1.7
	if (($('body').attr('id') == 'checkout'))
	{
		$('.delivery_options_address, table#carrierTable, .delivery-options-list .delivery-options').after($('#advanceddateofdelivery'));
		load_right_function();
	}
});

function load_right_function()
{
	if (!asap_display)
	{
		refreshCartDateOfDelivery();
		$(document).on('change', 'input[name^=delivery_option]', function(e){
			e.preventDefault();
			refreshCartDateOfDelivery();
		});
	}
	else
	{
		refreshCartDateOfDeliveryASAP();
		$(document).on('change', 'input[name^=delivery_option]', function(e){
			e.preventDefault();
			refreshCartDateOfDeliveryASAP();
		});
	}
}

function refreshCartDateOfDelivery()
{
	var date_from = null;
	var date_to = null;
	var set = true;
	$.each($('input[name^=delivery_option]:checked'), function()
	{
		var date = cartDatesDelivery[$(this).attr('name').replace(/delivery_option\[(.*)\]/, '$1')][$(this).val()];
		if (typeof(date) != 'undefined')
		{
			if (date_from == null || date_from[1] < date['minimal'][1])
				date_from = date['minimal'];
			if (date_to == null || date_to[1] < date['maximal'][1])
				date_to = date['maximal'];
		}
		else
			set = false;
	});

	if (date_from != null && date_to != null && set)
	{
		$('#advanceddateofdelivery').show();
		if(date_from[0] == date_to[0])
		{
			$('.delivery_dates').html(adod_text_single_date + " <span id='maximal'><b>"+date_to[0]+"</b></span> <sup>*</sup>");
		}
		else
		{
			$('.delivery_dates').html(adod_text_dates + " <span id='minimal'><b>"+date_from[0]+"</b></span> " + adod_text_and + " <span id='maximal'><b>"+date_to[0]+"</b></span> <sup>*</sup>");
		}
	}
	else
		$('#advanceddateofdelivery').hide();
}

function refreshCartDateOfDeliveryASAP()
{
	var products_datas = null;
	var set = true;
	$.each($('input[name^=delivery_option]:checked'), function()
	{
		var datas_dates = cartDatesDelivery[$(this).attr('name').replace(/delivery_option\[(.*)\]/, '$1')][$(this).val()];
		
		if (typeof(datas_dates) != 'undefined')
		{
			products_datas = datas_dates;
		}
		else
			set = false;
	});
	
	if (products_datas != null && set)
	{
		$.ajax({
			url: module_controller_url,
			type: 'POST',
			headers: { "cache-control": "no-cache" },
			data : {
				ajax : true,
				products : products_datas
			},
			dataType: 'json',
			success: function(data){
				if (data.result)
				{
					$('.delivery_dates').html(data.result);
					$('#advanceddateofdelivery').show();
				}
			}
		});
		
		return false;
	}
	else
		$('#advanceddateofdelivery').hide();
}