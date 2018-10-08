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
<div class="jms-slider-wrapper">
	<div class="responisve-container">
		<div class="slider" >
		<div class="fs_loader"></div>
		{foreach from=$slides item=slide}
			<div class="slide {$slide.class_suffix|escape:'htmlall':'UTF-8'}" style="background:{$slide.bg_color|escape:'htmlall':'UTF-8'} url({$image_url|escape:'htmlall':'UTF-8'}slides/{$slide.bg_image|escape:'htmlall':'UTF-8'}) no-repeat left top;background-size:cover;" {if $slide.slide_link}onclick="document.location='{$slide.slide_link|escape:'htmlall':'UTF-8'}';"{/if}>
				{foreach from=$slide.layers item=layer}
					{if $layer.data_type=='text'}
					<div class="{$layer.data_class_suffix|escape:'htmlall':'UTF-8'} jms-slide-content" 
					{if $layer.data_fixed}data-fixed{/if} 
					data-position="{$layer.data_y|escape:'htmlall':'UTF-8'},{$layer.data_x|escape:'htmlall':'UTF-8'}" 
					data-fontsize = "{$layer.data_font_size|escape:'html':'UTF-8'}"
					{if $layer.data_line_height}
					data-lineheight = "{$layer.data_line_height|escape:'html':'UTF-8'}px"
					{/if}
					data-in="{$layer.data_in|escape:'html':'UTF-8'}" 
					data-out="{$layer.data_out|escape:'html':'UTF-8'}" 
					data-delay="{$layer.data_delay|escape:'html':'UTF-8'}" 
					data-ease-in="{$layer.data_ease_in|escape:'html':'UTF-8'}" 
					data-ease-out="{$layer.data_ease_out|escape:'html':'UTF-8'}" 
					data-step="{$layer.data_step|escape:'html':'UTF-8'}" 
					data-special="{$layer.data_special|escape:'html':'UTF-8'}"
					data-time = "{$layer.data_time|escape:'html':'UTF-8'}"
					style="font-size: {$layer.data_font_size|escape:'html':'UTF-8'}px; font-style:{$layer.data_style|escape:'html':'UTF-8'}; color: {$layer.data_color|escape:'html':'UTF-8'}; line-height:{if $layer.data_line_height}{$layer.data_line_height|escape:'html':'UTF-8'}px{/if};"					
					>{$layer.data_html}
					</div>
					{elseif $layer.data_type=='image'}					
					<img class="{$layer.data_class_suffix|escape:'htmlall':'UTF-8'} jms-slide-content" 
					src="{$image_url|escape:'htmlall':'UTF-8'}layers/{$layer.data_image|escape:'htmlall':'UTF-8'}" 
					{if $layer.data_fixed}data-fixed{/if} 
					data-position="{$layer.data_y|escape:'htmlall':'UTF-8'},{$layer.data_x|escape:'htmlall':'UTF-8'}" 
					data-in="{$layer.data_in|escape:'htmlall':'UTF-8'}" 
					data-out="{$layer.data_out|escape:'htmlall':'UTF-8'}" 
					data-delay="{$layer.data_delay|escape:'htmlall':'UTF-8'}" 
					data-ease-in="{$layer.data_ease_in|escape:'htmlall':'UTF-8'}" 
					data-ease-out="{$layer.data_ease_out|escape:'htmlall':'UTF-8'}" 
					data-time = "{$layer.data_time|escape:'htmlall':'UTF-8'}"
					data-step="{$layer.data_step|escape:'htmlall':'UTF-8'}" 
					data-special="{$layer.data_special|escape:'htmlall':'UTF-8'}" 
					width="{$layer.data_width|escape:'htmlall':'UTF-8'}" 
					height="{$layer.data_height|escape:'htmlall':'UTF-8'}" alt="Joommasters.com" />
					{else}
						
					<iframe class="{$layer.data_class_suffix|escape:'htmlall':'UTF-8'} jms-slide-content"
					{if $layer.data_fixed || $layer.data_video_bg}data-fixed{/if} 
					data-position="{$layer.data_y|escape:'htmlall':'UTF-8'},{$layer.data_x|escape:'htmlall':'UTF-8'}" 
					data-in="{$layer.data_in|escape:'htmlall':'UTF-8'}" 
					data-out="{$layer.data_out|escape:'htmlall':'UTF-8'}" 
					{if $layer.data_video_bg}data-delay="0"{else}data-delay="{$layer.data_delay|escape:'htmlall':'UTF-8'}" {/if}
					data-ease-in="{$layer.data_ease_in|escape:'htmlall':'UTF-8'}" 
					data-ease-out="{$layer.data_ease_out|escape:'html':'UTF-8'}" 
					data-step="{$layer.data_step|escape:'htmlall':'UTF-8'}" 
					data-special="{$layer.data_special|escape:'htmlall':'UTF-8'}"
					data-time = "{$layer.data_time|escape:'htmlall':'UTF-8'}"
					{if $layer.data_video_bg}
						width="{$configs.JMS_SLIDER_WIDTH|escape:'htmlall':'UTF-8'}"
						height="{$configs.JMS_SLIDER_HEIGHT|escape:'htmlall':'UTF-8'}"
					{else}
						width="{$layer.data_width|escape:'htmlall':'UTF-8'}"
						height="{$layer.data_height|escape:'htmlall':'UTF-8'}"
					{/if}
					{if $layer.videotype == 'youtube'}
						src="http://www.youtube.com/embed/{$layer.data_video|substr:($layer.data_video|strpos:'?v='+3)}?autoplay={$layer.data_video_autoplay|escape:'htmlall':'UTF-8'}&controls={$layer.data_video_controls|escape:'htmlall':'UTF-8'}&loop={$layer.data_video_loop|escape:'htmlall':'UTF-8'}"
					{else if $layer.videotype == 'vimeo'}
						 {assign var=vimeo_link value = ("/"|explode:$layer.data_video)}
						src="https://player.vimeo.com/video/{$vimeo_link[$vimeo_link|count-1]}?autoplay={$layer.data_video_autoplay|escape:'htmlall':'UTF-8'}&loop={$layer.data_video_loop|escape:'htmlall':'UTF-8'}"
					{/if}	
					allowfullscreen 
					frameborder="0">
					</iframe> 
					{/if}
				{/foreach}
			</div>
		{/foreach}
		</div>
	</div>
</div>

  
<script type="text/javascript">
	$(window).load(function(){
		$('.slider').fractionSlider({	
			'slideTransition' : "{$configs.JMS_SLIDER_TRANS|escape:'html':'UTF-8'}",
			'slideEndAnimation' : {if $configs.JMS_SLIDER_END_ANIMATE}true{else}false{/if},
			'transitionIn' : "{$configs.JMS_SLIDER_TRANS_IN|escape:'html':'UTF-8'}",
			'transitionOut' : "{$configs.JMS_SLIDER_TRANS_OUT|escape:'html':'UTF-8'}",
			'fullWidth' : {if $configs.JMS_SLIDER_FULL_WIDTH}true{else}false{/if},
			'delay' : {$configs.JMS_SLIDER_DELAY|escape:'html':'UTF-8'},
			'timeout' : {$configs.JMS_SLIDER_DURATION|escape:'html':'UTF-8'},
			'speedIn' : {$configs.JMS_SLIDER_SPEED_IN|escape:'html':'UTF-8'},
			'speedOut' : {$configs.JMS_SLIDER_SPEED_OUT|escape:'html':'UTF-8'},
			'easeIn' : "{$configs.JMS_SLIDER_EASE_IN|escape:'html':'UTF-8'}",
			'easeOut' : "{$configs.JMS_SLIDER_EASE_OUT|escape:'html':'UTF-8'}",
			'controls' : {if $navigation=='1'}true{else}false{/if},
			'pager' : {if $pagination=='1'}true{else}false{/if},
			'autoChange' : {if $autoplay=='1'}true{else}false{/if},
			'pauseOnHover' : {if $pausehover=='1'}true{else}false{/if},
			'backgroundAnimation' : {if $configs.JMS_SLIDER_BG_ANIMATE}true{else}false{/if},
			'backgroundEase' : "{$configs.JMS_SLIDER_BG_EASE|escape:'html':'UTF-8'}",
			'responsive' : {if $configs.JMS_SLIDER_RESPONSIVE}true{else}false{/if},
			'dimensions' : "{$max_width|escape:'html':'UTF-8'},{$max_height|escape:'html':'UTF-8'}",
		});
	});
</script>