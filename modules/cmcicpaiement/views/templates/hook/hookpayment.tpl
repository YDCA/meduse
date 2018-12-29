{*
* 2007-2015 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*}

<p class="payment_module" xmlns="http://www.w3.org/1999/html">
	<a class="bankwire" href="javascript:document.{$cmcicpaiement_form|escape:'htmlall':'UTF-8'}.submit();" title="{$cmcic_text|escape:'htmlall':'UTF-8'}">
		<img src="{$module_dir|escape:'htmlall':'UTF-8'}{$cmcic_picture|escape:'htmlall':'UTF-8'}" alt="{$cmcic_text|escape:'htmlall':'UTF-8'}" class="img-responsive" />
		{$cmcic_text|escape:'htmlall':'UTF-8'}
	</a>
</p>
<form action="{$cmcic->s_url_paiement|escape:'htmlall':'UTF-8'}" method="post" name="{$cmcicpaiement_form|escape:'htmlall':'UTF-8'}">
	<input type="hidden" name="version" value="{$cmcic->s_version|escape:'htmlall':'UTF-8'}" />
	<input type="hidden" name="TPE" value="{$cmcic->s_numero|escape:'htmlall':'UTF-8'}" />
	<input type="hidden" name="date" value="{$cmcic_date|escape:'htmlall':'UTF-8'}" />
	<input type="hidden" name="montant" value="{$cmcic_montant|escape:'htmlall':'UTF-8'}" />
	<input type="hidden" name="reference" value="{$cmcic_reference|intval}" />
	<input type="hidden" name="MAC" value="{$hmac|escape:'htmlall':'UTF-8'}" />
	<input type="hidden" name="url_retour" value="{$cmcic->s_url_ko|escape:'htmlall':'UTF-8'}" />
	<input type="hidden" name="url_retour_ok" value="{$cmcic->s_url_ok|escape:'htmlall':'UTF-8'}" />
	<input type="hidden" name="url_retour_err" value="{$cmcic->s_url_ko|escape:'htmlall':'UTF-8'}" />
	<input type="hidden" name="lgue" value="{$cmcic->s_langue|escape:'htmlall':'UTF-8'}" />
	<input type="hidden" name="societe" value="{$cmcic->s_code_societe|escape:'htmlall':'UTF-8'}" />
	<input type="hidden" name="texte-libre" value="{$cmcic_textelibre|escape:'htmlall':'UTF-8'}" />
	<input type="hidden" name="mail" value="{$cmcic_email|escape:'htmlall':'UTF-8'}" />
	{if $express_option == 1}
		<input type="hidden" name="aliascb" value="{$cmcic_alias|intval}" />
	{/if}
</form>