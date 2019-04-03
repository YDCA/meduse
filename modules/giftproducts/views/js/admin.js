/**
 * 2007-2019 PrestaShop
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
 * @copyright 2007-2019 Goryachev Dmitry
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

$(function () {
    $('#btnSearch').live('click', function () {
        $('.search_panel').closest('.col_right').addClass('loading');
        if (typeof window.ajaxSearch != 'undefined')
            window.ajaxSearch.abort();
        var data = {};
        data['categories'] = [];
        $.each(tree_cat.getListSelectedCategories(), function (index, item) {
            data['categories'].push(item.id);
        });
        data['query'] = $('[name="search_query"]').val();
        data['ajax'] = true;
        data['action'] = 'search_products';
        data['exclude_ids'] = [];

        var product_with_gift = parseInt($('[name="product_with_gift"]').val());
        var category_with_gift = parseInt($('[name="category_with_gift"]').val());

        if (!product_with_gift && !category_with_gift)
            $('.list_product_with_gift [data-product]').each(function () {
                data['exclude_ids'].push($(this).data('product'));
            });
        else
        {
            if (product_with_gift)
            {
                $('.list_product_with_gift [data-product="'+product_with_gift+'"] .gift').each(function () {
                    data['exclude_ids'].push($(this).data('gift'));
                });
            }

            if (category_with_gift)
            {
                $('.list_category_with_gift [data-category="'+category_with_gift+'"] .gift').each(function () {
                    data['exclude_ids'].push($(this).data('gift'));
                });
            }
        }

        window.ajaxSearch = $.ajax({
            url: document.location.href,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (r)
            {
                $('.search_panel').closest('.col_right').removeClass('loading');
                $('.list_product_query').html('');
                $.each(r, function (index, value)
                {
                    var tpl_search_product = _.template($('#tpl_search_product').html());
                    var search_product = tpl_search_product({
                        image: (value.image ? value.image : no_image),
                        action: 'add_product',
                        name: value.name,
                        id_product: value.id_product
                    });
                    $('.list_product_query').append(search_product);
                });
            },
            error: function () {
                $('.search_panel').closest('.col_right').removeClass('loading');
            }
        });
    });

    $('.add_product').live('click', function () {
        var search_product = $(this).closest('.search_product');

        var product_with_gift_id = parseInt($('[name="product_with_gift"]').val());
        var category_with_gift_id = parseInt($('[name="category_with_gift"]').val());
        if (!product_with_gift_id && !category_with_gift_id)
        {
            var tpl_product_with_gift = _.template($('#tpl_product_with_gift').html());
            var product_with_gift = tpl_product_with_gift({
                id_product: search_product.data('product'),
                product_title: search_product.find('.col_left').html()
            });
            $('.list_product_with_gift').append(product_with_gift);
        }
        else
        {
            if (product_with_gift_id)
            {
                var tpl_gift = _.template($('#tpl_gift').html());
                var gift = tpl_gift({
                    id_gift_product: search_product.data('product'),
                    id_product: product_with_gift_id,
                    product_title: search_product.find('.col_left').html()
                });
                var prod_with_gift = $('.list_product_with_gift .product_with_gift[data-product="'+product_with_gift_id+'"]');
                prod_with_gift.find('.product_gifts').append(gift);
                prod_with_gift.find('.count_gifts').text(prod_with_gift.find('.gift').length);
            }
            if (category_with_gift_id)
            {
                var tpl_gift = _.template($('#tpl_gift_category').html());
                var gift = tpl_gift({
                    id_gift_product: search_product.data('product'),
                    id_category: category_with_gift_id,
                    product_title: search_product.find('.col_left').html()
                });
                var category_with_gift = $('.list_category_with_gift .category_with_gift[data-category="'+category_with_gift_id+'"]');
                category_with_gift.find('.category_gifts').append(gift);
                category_with_gift.find('.count_gifts').text(category_with_gift.find('.gift').length);
            }

            $('.panel-footer').show();
        }
        search_product.remove();
    });

    $('.delete_product_with_gift').live('click', function (e) {
        e.preventDefault();
        var confirmMess = confirm(l_delete_item_with_gift);
        if (confirmMess)
        {
            var product = $(this).closest('.product_with_gift');
            if (parseInt($('[name="product_with_gift"]').val()) == parseInt(product.data('product')))
                cancelAddingGift();
            $(this).closest('.product_with_gift').remove();
            $('.panel-footer').show();
        }
    });

    $('.delete_category_with_gift').live('click', function (e) {
        e.preventDefault();
        var confirmMess = confirm(l_delete_item_with_gift);
        if (confirmMess)
        {
            var category = $(this).closest('.category_with_gift');
            if (parseInt($('[name="category_with_gift"]').val()) == parseInt(category.data('category')))
                cancelAddingGift();
            $(this).closest('.category_with_gift').remove();
            $('.panel-footer').show();
        }
    });

    $('.add_gift').live('click', function (e) {
        e.preventDefault();
        resetActiveProductWithGift();
        var product = $(this).closest('.product_with_gift');
        var product_data = product.find('.product_title').find('img, span').clone();
        product.addClass('active');
        $('.search_panel').addClass('active');
        $('.search_panel').closest('.col_right').addClass('active');
        $('[name="product_with_gift"]').val(product.data('product'));
        $('.selected_product_with_gift').html(product_data);
        $('#btnSearch').trigger('click');
        product.find('.product_gifts').stop(true, true).slideDown(300);
    });

    $('.cancel_adding_gift').live('click', function (e) {
        e.preventDefault();
        cancelAddingGift();
    });

    $('.delete_gift').live('click', function (e) {
        e.preventDefault();
        var prod_with_gift = $(this).closest('.product_with_gift');
        if (prod_with_gift.length)
        {
            $(this).closest('.gift').remove();
            prod_with_gift.find('.count_gifts').text(prod_with_gift.find('.gift').length);
        }

        var category_with_gift = $(this).closest('.category_with_gift');
        if (category_with_gift.length)
        {
            $(this).closest('.gift').remove();
            category_with_gift.find('.count_gifts').text(category_with_gift.find('.gift').length);
        }
        $('.panel-footer').show();
    });

    $('[id=saveGiftProducts]').live('click', function () {
        alert(l_without_gift);
        $('.cols > .col_left').addClass('loading');
        var data = {};
        data['giftproduct'] = [];
        $('.list_product_with_gift :input').each(function () {
            var id_product = $(this).attr('name').match(/giftproduct\[([0-9]+)\]/)[1];
            var id_gift = $(this).val();
            data['giftproduct'].push({
                id_product: id_product,
                id_gift: id_gift
            });
        });
        data['giftcategory'] = [];
        $('.list_category_with_gift :input').each(function () {
            var id_category = $(this).attr('name').match(/giftcategory\[([0-9]+)\]/)[1];
            var id_gift = $(this).val();
            data['giftcategory'].push({
                id_category: id_category,
                id_gift: id_gift
            });
        });

        data['ajax'] = true;
        data['action'] = 'save_gift_product';
        $.ajax({
            url: document.location.href,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (r) {
                $('.cols > .col_left').removeClass('loading');
                if (!r.hasError)
                {
                    alert(r.message);
                    $('.panel-footer').hide();
                }
            },
            error: function () {
                $('.cols > .col_left').removeClass('loading');
                alert('Has error');
            }
        });
    });

    function resetActiveProductWithGift()
    {
        $('.product_with_gift').removeClass('active');
        $('.product_with_gift .product_gifts').hide();

        $('.category_with_gift').removeClass('active');
        $('.category_with_gift .category_gifts').hide();
    }

    function cancelAddingGift()
    {
        resetActiveProductWithGift();
        $('[name="product_with_gift"]').val(0);
        $('[name="category_with_gift"]').val(0);
        $('.selected_product_with_gift').html('');
        $('.search_panel').removeClass('active type_category');
        $('.search_panel').closest('.col_right').removeClass('active');
        $('#btnSearch').trigger('click');
    }

    $('.add_category').live('click', function () {
        var tpl_category_with_gift = _.template($('#tpl_category_with_gift').html());
        var option = $('[name="category_list"] option:selected');
        if (option.length)
        {
            var category_with_gift = tpl_category_with_gift({
                id_category: option.attr('value'),
                category_title: '<span>'+option.text()+'</span>'
            });
            $('.list_category_with_gift').append(category_with_gift);
            option.remove();
        }
    });

    $('.add_gift_category').live('click', function (e) {
        e.preventDefault();
        cancelAddingGift();
        var category = $(this).closest('.category_with_gift');
        var category_title = category.find('.category_title').find('span').clone();
        category.addClass('active');
        $('.search_panel').addClass('active type_category');
        $('.search_panel').closest('.col_right').addClass('active');
        $('[name="category_with_gift"]').val(category.data('category'));
        $('.selected_product_with_gift').html(category_title);
        $('#btnSearch').trigger('click');
        category.find('.category_gifts').stop(true, true).slideDown(300);
    });

    $('[name=category_list]').select2();
});
