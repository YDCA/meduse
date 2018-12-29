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
	
	bindToggleCalendar();
	
	$('input[name="ADOD_ALLOW_ASAP_OPTION"]').on('click', function() {
		if ($(this).val() == 0) {
			$('.display_allow_asap_options').hide();
		} else {
			$('.display_allow_asap_options').show();
		}
	});
	
	if($('#ADOD_ALLOW_ASAP_OPTION_on').is(':checked')) {
		$('.display_allow_asap_options').show();
	}
	else {		
		$('.display_allow_asap_options').hide();
	}
	
	$('input[name="ADOD_DISPLAY_ON_PRODUCT_PAGE"]').on('click', function() {
		if ($(this).val() == 0) {
			$('.display_product_page_options').hide();
		} else {
			$('.display_product_page_options').show();
		}
	});
	
	if($('#ADOD_DISPLAY_ON_PRODUCT_PAGE_on').is(':checked')) {
		$('.display_product_page_options').show();
	}
	else {		
		$('.display_product_page_options').hide();
	}
	
	$('input[name="ADOD_FORCE_STOCK"]').on('click', function() {
		if ($(this).val() == 0) {
			getE('ADOD_SHOP_PROCESSING_OUTSTOCK').disabled = false;
		} else {
			getE('ADOD_SHOP_PROCESSING_OUTSTOCK').disabled = 'disabled';
		}
	});
	
	if($('#ADOD_FORCE_STOCK_on').is(':checked')) {
		getE('ADOD_SHOP_PROCESSING_OUTSTOCK').disabled = 'disabled';
	}
	else {
		getE('ADOD_SHOP_PROCESSING_OUTSTOCK').disabled = false;
	}
	
	$('select[name="ADOD_PRODUCT_POSITION"]').on('change', function() {
		if ($(this).val() == 'PRODUCT_FOOTER') {
			$('#container_ADOD_DISPLAY_FOOTER_ANCHOR').show();
		} else {
			$('#container_ADOD_DISPLAY_FOOTER_ANCHOR').hide();
		}
	});

	if($('#ADOD_PRODUCT_POSITION').val() == 'PRODUCT_FOOTER' && $('#ADOD_DISPLAY_ON_PRODUCT_PAGE_on').is(':checked')) {
		$('#container_ADOD_DISPLAY_FOOTER_ANCHOR').show();
	}
	else {
		$('#container_ADOD_DISPLAY_FOOTER_ANCHOR').hide();
	}
});

function bindToggleCalendar (){
	$('#button_calendar_action').click(function(e) {
		e.preventDefault();

		if ($(this).children('i').first().hasClass('icon-eye-open'))
			removeButtonCalendar();
		else
		{
			addButtonCalendar();
		}
	});
};

function removeButtonCalendar()
{		
	$('.holiday_table_container').show();
	$('#button_calendar_action').children('i').first().removeClass('icon-eye-open');
	$('#button_calendar_action').children('i').first().addClass('icon-eye-close');
	$('#button_calendar_action').children('span').first().html(hide_holidays_button_text);
};

function addButtonCalendar()
{
	$('.holiday_table_container').hide();
	$('#button_calendar_action').children('i').first().removeClass('icon-eye-close');
	$('#button_calendar_action').children('i').first().addClass('icon-eye-open');
	$('#button_calendar_action').children('span').first().html(show_holidays_button_text);
};