/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    Goryachev Dmitry    <dariusakafest@gmail.com>
 * @copyright 2007-2018 Goryachev Dmitry
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

window.oldDeleteProductFromSummary = window.deleteProductFromSummary;

window.deleteProductFromSummary = function(id)
{
    deleteGift(id.split('_')[0], function (json) {
        for (var i in json) {
            $('#cart_summary tr[id$=_gift][id^=product_'+ json[i] +']').remove();
        }
    });
    window.oldDeleteProductFromSummary(id);
};

if (typeof prestashop != 'undefined') {
    $('[data-link-action="delete-from-cart"]').on('click', function () {
        deleteGift(
            $(this).data('id-product'),
            function (json) {
                for (var i in json) {
                    $('[data-link-action="delete-from-cart"][data-id-product="'+ json[i] +'"]')
                    .closest('.cart-item')
                    .remove();
                }
            }
        );
    });
}

function deleteGift(id_product, success) {
    $.ajax({
        url: document.location.href,
        dataType: 'JSON',
        type: 'POST',
        data: {
            ajaxGift: true,
            action: 'deleteGift',
            id_product: id_product
        },
        success: function (json)
        {
            success(json);
        }
    });
}