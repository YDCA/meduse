/* Loi Hamon Prestashop module
 * Copyright 2014, Prestaddons
 * Author: Prestaddons
 * Website: http://www.prestaddons.fr
 */

//global variables
if (typeof $.uniform !== 'undefined' && typeof $.uniform.defaults !== 'undefined')
{
	if (typeof contact_fileDefaultHtml !== 'undefined')
		$.uniform.defaults.fileDefaultHtml = contact_fileDefaultHtml;
	if (typeof contact_fileButtonHtml !== 'undefined')
		$.uniform.defaults.fileButtonHtml = contact_fileButtonHtml;
}
$(window).load(function () {
	// Deactivate Uniform on checkboxes
	if (typeof $.uniform !== 'undefined')
		$.uniform.restore("input[type='checkbox']");
});
$(document).ready(function(){

	$('select[name=id_order]').change(function () {
		showDeliveryDate($(this).val());
		showProducts($(this).val());
	});
	if ($('select[name=id_order]').val() !== 0) {
		showDeliveryDate($('select[name=id_order]').val());
		showProducts($('select[name=id_order]').val());
	}
	if ($('input[name=product_attribute_id]').is(':checked')) {
		$(this).click();
	}
	$('th input[type=checkbox]').click(function(){
		var el = $(this);
		var table = el.parents('table').eq(0);
		table.find('td input[type=checkbox]').each(function()
		{
			$(this).attr('checked', el.is(':checked'));
			showQuantity($(this));
		});
	});
	$('td input[type=checkbox]').each(function(){
		showQuantity($(this));
	});
	$('td input[type=checkbox]').click(function(){
		showQuantity($(this));
	});
	
	$('td .order_qte_input').keyup(function()
	{
		var maxQuantity = parseInt($(this).parent().find('.order_qte_span').text());
		var quantity = parseInt($(this).val());
		if (isNaN($(this).val()) && $(this).val() !== '')
		{
			$(this).val(maxQuantity);
		}
		else
		{
			if (quantity > maxQuantity)
				$(this).val(maxQuantity);
			else if (quantity < 1)
				$(this).val(1);
		}
	});
});

function showDeliveryDate(id_order)
{
	if (deliveryTab[id_order] === '0000-00-00 00:00:00')
	{
		deliveryTab[id_order] = 'N/A';
	}
	$('#delivery').val(deliveryTab[id_order]);
}

function showProducts(id_order)
{
	$('.table-product').hide();
	if (id_order == 0)
		$('#product_wrapper_label').hide();
	else
		$('#product_wrapper_label').show();
	
	$('#'+id_order+'_order_products').show();
}

function showQuantity(el)
{
	if (el.is(':checked')) {
		el.closest('tr').find('.order_qte_input').val(el.closest('tr').find('.order_qte_span').eq(0).text());
		el.closest('tr').find('.order_qte_span').eq(0).hide();
		el.closest('tr').find('.order_qte_input').eq(0).show();
		el.closest('tr').find('.return_quantity_buttons').eq(0).show();
	}
	else
	{
		el.closest('tr').find('.order_qte_input').eq(0).hide();
		el.closest('tr').find('.return_quantity_buttons').eq(0).hide();
		el.closest('tr').find('.order_qte_span').eq(0).show();
	}
}

// The button to decrement the product return value
$(document).on('click', '.return_quantity_down', function(e){
	e.preventDefault();
	var $input = $(this).parent().parent().find('input');
	var count = parseInt($input.val()) - 1;
	count = count < 1 ? 1 : count;
	$input.val(count);
	$input.change();
});

// The button to increment the product return value
$(document).on('click', '.return_quantity_up', function(e){
	e.preventDefault();
	var maxQuantity = parseInt($(this).parent().parent().find('.order_qte_span').text());
	var $input = $(this).parent().parent().find('input');
	var count = parseInt($input.val()) + 1;
	count = count > maxQuantity ? maxQuantity : count;
	$input.val(count);
	$input.change();
});