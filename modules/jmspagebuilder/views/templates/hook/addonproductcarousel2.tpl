{*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<script type="text/javascript">
jQuery(function ($) {
    "use strict";
	var productCarousel = $(".product-carousel");		
	var items = {if $cols}{$cols|escape:'htmlall':'UTF-8'}{else}4{/if},
	    itemsDesktop = {if $cols}{$cols|escape:'htmlall':'UTF-8'}{else}4{/if},
	    itemsDesktopSmall = {if $cols_md}{$cols_md|escape:'htmlall':'UTF-8'}{else}3{/if},
	    itemsTablet = {if $cols_sm}{$cols_sm|escape:'htmlall':'UTF-8'}{else}2{/if},
	    itemsMobile = {if $cols_xs}{$cols_xs|escape:'htmlall':'UTF-8'}{else}1{/if};
	var rtl = false;
	if ($("body").hasClass("rtl")) rtl = true;				
	productCarousel.owlCarousel({
		responsiveClass:true,
		responsive:{			
			1199:{
				items:itemsDesktop
			},
			991:{
				items:itemsDesktopSmall
			},
			768:{
				items:itemsTablet
			},
			318:{
				items:itemsMobile
			}
		},
		rtl: rtl,
	    nav: {if $navigation == '1'}true{else}false{/if},
	    dots: {if $pagination == '1'}true{else}false{/if},
		autoplay:{if $autoplay == '1'}true{else}false{/if},
	    rewindNav: {if $rewind == '1'}true{else}false{/if},
	    navigationText: ["", ""],
	    slideBy: {if $slidebypage == '1'}'page'{else}1{/if},
	    slideSpeed: 200	
	});
});
</script>


<div class="left-content">
	{if $addon_title}
		<h3>{$addon_title|escape:'htmlall':'UTF-8'}</h3>
	{/if}
	<ul class="categories-list">
		{foreach from=$categories item=category }
            {assign var='categoryLink' value=$link->getcategoryLink($category.id_category, $category.link_rewrite)}
			<li><a href="{$categoryLink}" title="{$category.name}">{$category.name}</a></li>
		{/foreach}
	</ul>
</div>
	
{assign var="box_template" "{$addon_tpl_dir}productbox.tpl"}
<div class="product-carousel">	
	{foreach from = $products_slides item = products_slide}
		<div class="item">
			{foreach from = $products_slide item = product}
				{include file={$box_template} product=$product}	
			{/foreach}
		</div>
	{/foreach}
</div>