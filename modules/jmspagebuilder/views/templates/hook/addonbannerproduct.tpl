{*
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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<script type="text/javascript">
jQuery(function ($) {
    "use strict";
	var bannerproductCarousel = $(".banner-product-carousel");		
	var items = {if $cols}{$cols|escape:'htmlall':'UTF-8'}{else}4{/if},
	    itemsDesktop = {if $cols}{$cols|escape:'htmlall':'UTF-8'}{else}4{/if},
	    itemsDesktopSmall = {if $cols_md}{$cols_md|escape:'htmlall':'UTF-8'}{else}3{/if},
	    itemsTablet = {if $cols_sm}{$cols_sm|escape:'htmlall':'UTF-8'}{else}2{/if},
	    itemsMobile = {if $cols_xs}{$cols_xs|escape:'htmlall':'UTF-8'}{else}1{/if};
	var rtl = false;
	if ($("body").hasClass("rtl")) rtl = true;				
	bannerproductCarousel.owlCarousel({
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
{if $addon_title}
<div class="addon-title">
	<h3>{$addon_title|escape:'htmlall':'UTF-8'}</h3>
</div>
{/if}
{if $addon_desc}
<p class="addon-desc">{$addon_desc|escape:'htmlall':'UTF-8'}</p>
{/if}	
{assign var="box_template" "{$addon_tpl_dir}productbox.tpl"}
{if $banner_img1}
<div class="banner-1 col-lg-6 col-md-6 col-xs-12">
	<img src="{$root_url|escape:'html'}{$banner_img1|escape:'html'}" alt="" class="img-responsive" />
	<div class="banner-content">
	{if $banner_html1}
		{$banner_html1}
	{/if}
	{if $banner_link1}
		<a class="button" href="{$banner_link1|escape:'htmlall':'UTF-8'}">{l s='SHOP NOW' mod='jmspagebuilder'}! <span class="fa fa-long-arrow-right"></span></a>
	{/if}
	</div>
</div>
{/if}
<div class="product-box col-lg-6 col-md-6 col-xs-12">
	<div class="banner-product-carousel">	
		{foreach from = $products_slides item = products_slide}
			<div class="item">
				{foreach from = $products_slide item = product}
					{include file={$box_template} product=$product}	
				{/foreach}
			</div>
		{/foreach}
	</div>	
	{if $banner_img2}
	<div class="banner-2">	
		<img src="{$root_url|escape:'html'}{$banner_img2|escape:'html'}" alt="" class="img-responsive" />
		<div class="banner-content">
			{if $banner_html2}
				{$banner_html2}
			{/if}
			{if $banner_link2}
			<a class="button" href="{$banner_link2|escape:'htmlall':'UTF-8'}">{l s='SHOP NOW' mod='jmspagebuilder'}! <span class="fa fa-long-arrow-right"></span></a>
			{/if}
		</div>	
	</div>	
	{/if}	
</div>