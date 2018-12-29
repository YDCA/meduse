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
	refreshFooterProductDateOfDelivery();
	
	$(document).on('click', '.color_pick', function(e){
		e.preventDefault();
		findCombination();
		refreshFooterProductDateOfDelivery();
	});

	$(document).on('change', '.attribute_select', function(e){
		e.preventDefault();
		findCombination();
		refreshFooterProductDateOfDelivery();
	});

	$(document).on('click', '.attribute_radio', function(e){
		e.preventDefault();
		findCombination();
		refreshFooterProductDateOfDelivery();
	});
	
	if (typeof adod_product_display !== 'undefined' && adod_product_display == 'PRODUCT_FANCYBOX')
	{
		$( ".advanceddateofdelivery_link" ).click(function() {
			$.fancybox({
				'hideOnOverlayClick' : false,
				'showCloseButton': true,
				'content':   $('.advanceddateofdelivery_product').show()
			});
		});
	}
	
	if (typeof adod_product_display !== 'undefined' && adod_product_display == 'PRODUCT_FOOTER')
	{
		$('.advanceddateofdelivery_link').click(function() {
			$('html, body').animate({
				scrollTop:$("#advanceddateofdelivery_footer_product").offset().top
			}, 'slow');
		});
	}
});


function refreshFooterProductDateOfDelivery()
{			
	product_attribute_id = $('#idCombination').val();
	product_id = $('#product_page_product_id').val();
	
	if(product_attribute_id.length == 0)
		product_attribute_id = 0;
		
	var minimal_date = null;
	var maximal_date = null;
	
	$.each($('#product_available_carriers tbody tr'), function()
	{
		var date_from = null;
		var date_to = null;
		var set = true;
		tr_class = $(this).attr('id');
		id_carrier = tr_class.substr(tr_class.indexOf("_") + 1);
		
		var date = productFooterDatesDelivery[0][id_carrier][product_id+"_"+product_attribute_id];

		if (typeof(date) != 'undefined')
		{
			if (date_from == null || date_from[1] < date['minimal'][1])
				date_from = date['minimal'];
			if (date_to == null || date_to[1] < date['maximal'][1])
				date_to = date['maximal'];
				
			if (minimal_date == null || minimal_date[1] > date['minimal'][1])
				minimal_date = date['minimal'];
			if (maximal_date == null || maximal_date[1] < date['maximal'][1])
				maximal_date = date['maximal'];
		}
		else
			set = false;
			
		if (date_from != null && date_to != null && set)
		{
			if(date_from[0] == date_to[0])
			{
				$(this).find($('.delivery_dates')).html(adod_text_single_date + " <span id='maximal'><b>"+date_to[0]+"</b></span> <sup>*</sup>");
			}
			else
			{
				$(this).find($('.delivery_dates')).html(adod_text_dates + " <span id='minimal'><b>"+date_from[0]+"</b></span> "+adod_text_and+" <span id='maximal'><b>"+date_to[0]+"</b></span> <sup>*</sup>");
			}
		}
		else
			$(this).find($('.delivery_dates')).html(adod_text_unavailable_dates);
	});
	
	$.each($('.adod_product_page_txt'), function()
	{
		var adod_product_page_txt_value = htmlDecode(advanceddateofdeliverytext);
		adod_product_page_txt_value = adod_product_page_txt_value.replace('%MINIMAL_DATE%', minimal_date[0]);
		adod_product_page_txt_value = adod_product_page_txt_value.replace('%MAXIMAL_DATE%', maximal_date[0]);
		adod_product_page_txt_value = adod_product_page_txt_value.replace('%NB_TOTAL_CARRIERS%', carrier_delivery_list);
		
		$(this).text(adod_product_page_txt_value);
	});
}

function htmlEncode(value){
  "use strict";
  //create a in-memory div, set it's inner text(which jQuery automatically encodes)
  //then grab the encoded contents back out.  The div never exists on the page.
  return $('<div/>').text(value).html();
}

function htmlDecode(value){
  "use strict";
  return $('<div/>').html(value).text();
}