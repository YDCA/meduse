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
	var blogCarousel = $(".blog-carousel");		
	var items = 3,
	    itemsDesktop = {if $items_show}{$items_show|escape:'htmlall':'UTF-8'}{else}3{/if},
	    itemsDesktopSmall = {if $items_show_md}{$items_show_md|escape:'htmlall':'UTF-8'}{else}2{/if},
	    itemsTablet = {if $items_show_sm}{$items_show_sm|escape:'htmlall':'UTF-8'}{else}2{/if},
	    itemsMobile = {if $items_show_xs}{$items_show_xs|escape:'htmlall':'UTF-8'}{else}1{/if};
	var rtl = false;
	if ($("body").hasClass("rtl")) rtl = true;				
	blogCarousel.owlCarousel({
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
		margin: 30,
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
{if $posts|@count gt 0}
<div class="blog-carousel">	
	{foreach from=$posts item=post}				
		{assign var=params value=['post_id' => $post.post_id, 'category_slug' => $post.category_alias, 'slug' => $post.alias]}
		{assign var=catparams value=['category_id' => $post.category_id, 'slug' => $post.category_alias]}	
		<div class="blog-item">
			{if $post.link_video && ($show_media == '1')}
			<div class="post-thumb">
				{$post.link_video|escape:'htmlall':'UTF-8'}
			</div>
			{elseif $post.image && ($show_media == '1')}
			<div class="post-thumb">
				<a href="{jmsblog::getPageLink('jmsblog-post', $params)|escape:'htmlall':'UTF-8'|replace:'&amp;':'&'}"><img src="{$image_url|escape:'html':'UTF-8'}thumb_{$post.image|escape:'html':'UTF-8'}" alt="{$post.title|escape:'htmlall':'UTF-8'}" class="img-responsive" /></a>			 		
			</div>
			{/if}	
			<h4 class="post-title"><a href="{jmsblog::getPageLink('jmsblog-post', $params)|escape:'htmlall':'UTF-8'|replace:'&amp;':'&'}">{$post.title|escape:'htmlall':'UTF-8'}</a></h4>
			<ul class="post-meta">
				{if $show_category == '1'}
				<li class="post-category"><span>{l s='Category' mod='jmspagebuilder'} :</span> <a href="{jmsblog::getPageLink('jmsblog-category', $catparams)|escape:'htmlall':'UTF-8'|replace:'&amp;':'&'}">{$post.category_name|escape:'html':'UTF-8'}</a></li>
				{/if}				
				{if $show_time == '1'}						
				<li class="post-created"><span>{l s='Created' mod='jmspagebuilder'} :</span> {$post.created|escape:'html':'UTF-8'|date_format:"%B %e, %Y"}</li>
				{/if}
				{if $show_nviews == '1'}		
				<li class="post-views"><span>{l s='Views' mod='jmspagebuilder'} :</span> {$post.views|escape:'html':'UTF-8'}</li>
				{/if}
				{if $show_ncomments == '1'}		
				<li class="post-comments">{$post.comment_count|escape:'html':'UTF-8'} <span>{l s='Comments' mod='jmspagebuilder'} :</span> </li>
				{/if}	
			</ul>
			{if $show_introtext == '1'}	
			<div class="post-intro">{$post.introtext}</div>	
			{/if}
			{if $show_readmore == '1'}	
				<a href="{jmsblog::getPageLink('jmsblog-post', $params)|escape:'htmlall':'UTF-8'|replace:'&amp;':'&'}" class="post-readmore">{l s='read more' mod='jmspagebuilder'} ...</a>	
			{/if}
		</div>	
	{/foreach}	
</div>	
{/if}	