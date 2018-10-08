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
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
jQuery(function ($) {
    "use strict";
	var testimonialCarousel = $(".testimonial-carousel");
	var items = 3,
	    itemsDesktop = {if $items_show}{$items_show}{else}1{/if},
	    itemsDesktopSmall = {if $items_show_md}{$items_show_md}{else}1{/if},
	    itemsTablet = {if $items_show_sm}{$items_show_sm}{else}1{/if},
	    itemsMobile = {if $items_show_xs}{$items_show_xs}{else}1{/if};
	var rtl = false;
	if ($("body").hasClass("rtl")) rtl = true;				
	testimonialCarousel.owlCarousel({
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
<div class="testimonial-carousel">
{foreach from=$testimonials item=testimonial}	
<div class="testimonial-box">
	{if $show_image}
	<div class="testimonial-img">						
		<img class="img-responsive" src="{$image_url|escape:'html':'UTF-8'}resized_{$testimonial.image|escape:'html':'UTF-8'}" />					
	</div>
	{/if}
	{if $show_time}
	<div class="testimonial-date">			
		{$testimonial.posttime|escape:'html':'UTF-8'}			
	</div>
	{/if}
	<div class="testimonial-comment" >
		&ldquo; {$testimonial.comment|escape:'html':'UTF-8'} &rdquo;
	</div>
	<div class="testimonial-author">
		{$testimonial.author|escape:'html':'UTF-8'}
		{if $show_office}
			<span class="show_office">{$testimonial.office|escape:'html':'UTF-8'}</span>
		{/if}
	</div>
</div>	
{/foreach}
</div>