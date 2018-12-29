{* Loi Hamon Prestashop module
 * Copyright 2014, Prestaddons
 * Author: Prestaddons
 * Website: http://www.prestaddons.fr
 *}
 
{if $lh_psversion17}
	<a class="col-lg-4 col-md-6 col-sm-6 col-xs-12" href="{$lh_retraction_form_link|escape:'htmlall':'UTF-8'}" title="{l s='Retraction form' mod='loihamon'}" >
          <span class="link-item">
            <i class="material-icons">&#xE16D</i>
            {l s='Retraction form' mod='loihamon'}
          </span>
    </a>
{elseif $lh_psversion16}
<li>
	<a href="{$lh_retraction_form_link|escape:'htmlall':'UTF-8'}" title="{l s='Retraction form' mod='loihamon'}">
		<i class="icon-reply-all"></i>
		<span>{l s='Retraction form' mod='loihamon'}</span>
	</a>
</li>
{else}
<li>
	<a href="{$lh_retraction_form_link|escape:'htmlall':'UTF-8'}" title="{l s='Retraction form' mod='loihamon'}">
		<img class="icon" alt="Adresses" src="{$lh_path|escape:'htmlall':'UTF-8'}views/img/return_26x26.png">
		{l s='Retraction form' mod='loihamon'}
	</a>
</li>
{/if}