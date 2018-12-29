{* Loi Hamon Prestashop module
 * Copyright 2014, Prestaddons
 * Author: Prestaddons
 * Website: http://www.prestaddons.fr
 *}
 
<div id="fieldset_0" class="panel">
	
	{if $psversion16}
	<div class="panel-heading">
		<i class="icon-question-sign">&nbsp;</i>{l s='Support' mod='loihamon'}
	</div>
	<div class="form-wrapper">
	{else}
	<div class="toolbar-placeholder">
		<div class="toolbarBox">
			<ul class="cc_button">
				<li>
					<a class="toolbar_btn" href="{$back_link|escape:'htmlall':'UTF-8'}" title="{l s='Back' mod='loihamon'}">
						<span class="process-icon-back"></span>
						<div class="locked">{l s='Back' mod='loihamon'}</div>
					</a>
				</li>
			</ul>
			<div class="pageTitle">
				<h3>
					<span style="font-weight: normal;" id="current_obj">
						<span class="breadcrumb item-0">{$display_name|escape:'htmlall':'UTF-8'}</span>
					</span>
				</h3>
			</div>
		</div>
	</div>
	<div class="leadin"></div>
	<fieldset>
		<legend><img src="{$path|escape:'htmlall':'UTF-8'}views/img/help_16x16.png" alt="{l s='Support' mod='loihamon'}" />{l s='Support' mod='loihamon'}</legend>
	{/if}
		<div class="form-group" style="font-size:14px;">
			<div style="width:70%;margin:0 auto;">
				<img src="{$path|escape:'htmlall':'UTF-8'}logo.png" alt="{$display_name|escape:'htmlall':'UTF-8'} {$version|escape:'htmlall':'UTF-8'}" />
				<strong>{$display_name|escape:'htmlall':'UTF-8'} {$version|escape:'htmlall':'UTF-8'}</strong>
				<img style="float:right;" width="200" height="48" src="{$path|escape:'htmlall':'UTF-8'}views/img/prestaddons.png" alt="{l s='Check out all our addons for Prestashop' mod='loihamon'}" />
			</div>
			<br /><br />
			<div style="width:70%;margin:0 auto;border:1px solid #E6E6E6;padding:10px;">
				<span style="font-size: 1.1em;"><img src="{$path|escape:'htmlall':'UTF-8'}views/img/pdf_16x16.png" alt="{l s='Documentation' mod='loihamon'}" />&nbsp;{l s='Documentation' mod='loihamon'}</span>
				<br />
				<div style="text-align:center;">
					<a href="{$path|escape:'htmlall':'UTF-8'}docs/readme_{$iso|escape:'htmlall':'UTF-8'}.pdf" title="{l s='Download the' mod='loihamon'} {$display_name|escape:'htmlall':'UTF-8'} {l s='documentation' mod='loihamon'}">
						<img src="{$path|escape:'htmlall':'UTF-8'}views/img/pdf_16x16.png" alt="{l s='Documentation' mod='loihamon'}" />
						{l s='Download the' mod='loihamon'} {$display_name|escape:'htmlall':'UTF-8'} {l s='documentation' mod='loihamon'}
					</a>
				</div>
				<br />
			</div>
			<br />
			<div style="width:70%;margin:0 auto;border:1px solid #E6E6E6;padding:10px;">
				<span style="font-size: 1.1em;"><img src="{$path|escape:'htmlall':'UTF-8'}views/img/contact_20x15.png" alt="{l s='Contact' mod='loihamon'}" />&nbsp;{l s='Contact' mod='loihamon'}</span>
				<br />
				<div style="text-align:center;">
					{l s='Contact_Text' mod='loihamon'}<br /><br />
					<a href="mailto:{$contact|escape:'htmlall':'UTF-8'}" title="{l s='Send an e-mail to' mod='loihamon'} {$contact|escape:'htmlall':'UTF-8'}">
						<img src="{$path|escape:'htmlall':'UTF-8'}views/img/contact_20x15.png" alt="{l s='Send an e-mail to' mod='loihamon'} {$contact|escape:'htmlall':'UTF-8'}" />
						{l s='Send an e-mail to' mod='loihamon'} {$contact|escape:'htmlall':'UTF-8'}
					</a>
					<br /><br />
				{l s='Contact_Text2' mod='loihamon'}
				</div>
				<br />
			</div>
			<br />
			<div style="width:70%;margin:0 auto;border:1px solid #E6E6E6;padding:10px;">
				<span style="font-size: 1.1em;"><img src="{$path|escape:'htmlall':'UTF-8'}views/img/copyright_16x16.png" alt="{l s='Copyright' mod='loihamon'}" />&nbsp;{l s='Copyright' mod='loihamon'}</span>
				<br /><br />
				<div style="text-align:justify;">
					{l s='Copyright_Text' mod='loihamon'}
				</div>
				<br />
			</div>
			<br />
		</div>
	{if !$psversion16}
	</fieldset>
	{else}
	</div>
	<div class="panel-footer">
		<a class="btn btn-default" href="{$back_link|escape:'htmlall':'UTF-8'}"><i class="process-icon-back"></i>{l s='Back' mod='loihamon'}</a>
	</div>
	{/if}
</div>