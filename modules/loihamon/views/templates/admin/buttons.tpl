{* Marketing Popup Prestashop module
 * Copyright 2014, Prestaddons
 * Author: Prestaddons
 * Website: http://www.prestaddons.fr
 *}
{if $ps_version16}
	<div id="hamon-law-buttons-admin" class="panel">
		<h3><i class="icon-support"></i> {$module_name|escape:'html':'UTF-8'}</h3>
		<div class="row">
			<a class="btn btn-default" href="{$support_url|escape:'html':'UTF-8'}"><i class="icon-file-pdf-o"></i>{l s='Documentation' mod='loihamon'}</a>
			<a class="btn btn-default" href="{$addons_url|escape:'html':'UTF-8'}" target="_blank"><i class="process-icon-help"></i>{l s='Support' mod='loihamon'}</a>
		</div>
	</div>
{/if}