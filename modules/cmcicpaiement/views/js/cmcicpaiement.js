/*
 * 2007-2015 PrestaShop
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
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2015 PrestaShop SA
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

var p = function () {
    if (debug_mode) {
        var i = 0,
            arg_lenght = arguments.length;
        if (arg_lenght > 0) {
            for (i; i<arg_lenght; i++) {
                if (arguments[i] instanceof Array) {
                    console.log(arguments[i]);
                }
                else if (typeof(arguments[i]) === 'object') {
                    console.table(arguments[i]);
                } else {
                    // console.log(arguments.callee.caller.toString());
                    console.log(arguments[i]);
                }
            }
        }
    }
};

jQuery.fn.listAttributes = function(prefix) {
    var list = [], key;
    $(this).each(function() {
        console.info(this);
        var attributes = [];
        for (key in this.attributes) {
            if(!isNaN(key)) {
                if(!prefix || this.attributes[key].name.substr(0,prefix.length) === prefix) {
                    attributes.push(this.attributes[key].name);
                }
            }
        }
        list.push(attributes);
    });
    return (list.length > 1 ? list : list[0]);
};

// Main Function
var Main = function () {

    /**
     ** Display save Button on collapse
     */
    var collapseStep = function (obj) {
        $id = $(obj).attr('href');
        a = $($id).attr('class');
        $($id).parents().find('.panel-footer').addClass('hide');
        $($id).parent().find('.panel-footer').removeClass('hide');
        $($id).parents().siblings().find('.in').removeClass('in').addClass('collapse');
        $($id).removeClass('in');
        a = $($id).attr('class');
    }

    /**
     ** Click Event
     */
    var runEvent = function () {

        // Click on Panel
        $('#modulecontent .tab-content h3 a').live('click', function (e) {
            e.preventDefault();
            var collapse = $(this).attr('data-toggle');
            if (typeof(collapse) !== "undefined" && collapse === 'collapse') {
                var id = $(this).attr('href');
                var is_collapse = false;

                $(this.attributes).each(function() {
                    if (this.nodeName === 'class') {
                        if(this.nodeValue === '') {
                            is_collapse = true;
                        }
                    }
                });

                if ($(this).attr('class') === undefined) {
                    is_collapse = true;
                }
            }
        });

        // Tab panel active
        $(".list-group-item").on('click', function() {
            var $el = $(this).parent().closest(".list-group").children(".active");
            if ($el.hasClass("active")) {
                target = $(this).find('i').attr('data-target');
                if (target !== undefined) {
                    loadTable('#'+target);
                }
                $el.removeClass("active");
                $(this).addClass("active");
            }
        });

        // Allow to switch only one element
        $("#collapse2 input[type='radio']").on('change',function () {
            $(this).closest('.radio_select').siblings().find('input.switch_off').attr('checked', true);
        });

        $(".contactus").on('click', function() {
            $href = $.trim($(this).attr('href'));
            $(".list-group a.active").each(function() {
                $(this).removeClass("active");
            });

            $(this).addClass("active");
        });

        // Active Tab config
        var is_submit = $("#modulecontent").attr('role');
        if (is_submit >= 1) {
            $(".list-group-item").each(function() {
                if ($(this).hasClass('active')) {
                    $(this).removeClass("active");
                }
                else if ($(this).attr('href') == "#conf") {
                    $(this).addClass("active");
                }
            });
            $('#conf').addClass("active");
            $('#documentation').removeClass("active");

            $('#collapsein'+is_submit).trigger("click");
        }
    };

    /**
     ** Custom Elements
     */
    var runCustomElement = function () {
        // Hide ugly toolbar
        $('table[class="table"]').each(function() {
            $(this).hide();
            $(this).next('div.clear').hide();
        });

        // Hide ugly multishop select
        if (typeof(_PS_VERSION_) !== 'undefined') {
            var version = _PS_VERSION_.substr(0,3);
            if(version === '1.5') {
                $('.multishop_toolbar').addClass("panel panel-default");
                $('.shopList').removeClass("chzn-done").removeAttr("id").css("display", "block").next().remove();
                cloneMulti = $(".multishop_toolbar").clone(true, true);
                $(".multishop_toolbar").first().remove();
                cloneMulti.find('.shopList').addClass('selectpicker show-menu-arrow').attr('data-live-search', 'true');
                cloneMulti.insertBefore("#modulecontent");
                // Copy checkbox for multishop
                cloneActiveShop = $.trim($('table[class="table"] tr:nth-child(2) th').first().html());
                $(cloneActiveShop).insertAfter("#tab_translation");
            }
        }

        // Custom Select
        $('.selectpicker').selectpicker();

        // Fix bug form builder + bootstrap select
        $('.selectpicker').each(function(){
            var select = $(this);
            select.on('click', function() {
                $(this).parents('.bootstrap-select').addClass('open');
                $(this).parents('.bootstrap-select').toggleClass('open');
            });
        });

        // Show tooltip for helping merchant
        $('a').tooltip();
    };

    return {
        init: function () {
            runEvent();
            runCustomElement();
        }
    };
}();

// Load functions
$(window).load(function() {
    Main.init();

    checkActiveMenu();

    function checkActiveMenu(){
        var i = 0;
        $(".list-group-item").each(function() {
            if ($(this).hasClass('active')) {
                i += 1 ;
            }
        });
        if (i == 0)
            $('.doc').addClass('active');
    }
});
