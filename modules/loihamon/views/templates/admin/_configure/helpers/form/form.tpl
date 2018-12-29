{* Loi Hamon Prestashop module
 * Copyright 2014, Prestaddons
 * Author: Prestaddons
 * Website: http://www.prestaddons.fr
 *}

{extends file="helpers/form/form.tpl"}

{block name="leadin"}
	{if !Configuration::get('PS_ORDER_RETURN')}
	<div class="error draft">
		<p>
			<span style="float: left">
			{l s='Merchandise returns are disabled. If you want to use this feature, you should enable it in the ' mod='loihamon'}
			<a href="{$admin_returns_url|escape:'htmlall':'UTF-8'}" title="{l s='Merchandise returns' mod='loihamon'}">{l s='Merchandise returns' mod='loihamon'}</a>
			{l s='section' mod='loihamon'}.
			</span>
			<br class="clear" />
		</p>
	</div>
	{/if}
{/block}

{block name="legend"}
	<h3>
		{if isset($field.image)}<img src="{$field.image|escape:'html':'UTF-8'}" alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
		{if isset($field.icon)}<i class="{$field.icon|escape:'html':'UTF-8'}"></i>{/if}
		{$field.title|escape:'html':'UTF-8'}
		<span class="panel-heading-action">
		{foreach from=$toolbar_btn item=btn key=k}
			{if $k != 'modules-list' && $k != 'back'}
				<a id="desc-{$table|escape:'html':'UTF-8'}-{if isset($btn.imgclass)}{$btn.imgclass|escape:'html':'UTF-8'}{else}{$k|escape:'html':'UTF-8'}{/if}" class="list-toolbar-btn" {if isset($btn.href)}href="{$btn.href|escape:'html':'UTF-8'}"{/if} {if isset($btn.target) && $btn.target}target="_blank"{/if} {if isset($btn.js) && $btn.js}onclick="{$btn.js|escape:'html':'UTF-8'}" {/if}>
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s=$btn.desc mod='loihamon'}" data-html="true">
						<i class="process-icon-{if isset($btn.imgclass)}{$btn.imgclass|escape:'html':'UTF-8'}{else}{$k|escape:'html':'UTF-8'}{/if} {if isset($btn.class)}{$btn.class|escape:'html':'UTF-8'}{/if}" ></i>
					</span>
				</a>
			{/if}
		{/foreach}
		</span>
	</h3>
	{if !Configuration::get('PS_ORDER_RETURN')}
	<div class="alert alert-danger">
		{l s='Merchandise returns are disabled. If you want to use this feature, you should enable it in the ' mod='loihamon'}
		<a href="{$admin_returns_url|escape:'htmlall':'UTF-8'}" title="{l s='Merchandise returns' mod='loihamon'}">{l s='Merchandise returns' mod='loihamon'}</a>
		{l s='section' mod='loihamon'}.
	</div>
	{/if}
{/block}