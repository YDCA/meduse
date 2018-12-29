{* Loi Hamon Prestashop module
 * Copyright 2014, Prestaddons
 * Author: Prestaddons
 * Website: http://www.prestaddons.fr
 *}

<script type="text/javascript">
	var deliveryTab = new Array();
	{foreach from=$deliveryList item=delivery name=deliveryList}
		deliveryTab[{$delivery.id_order|escape:'htmlall':'UTF-8'}] = '{$delivery.delivery_date|escape:'htmlall':'UTF-8'}';
	{/foreach}
</script>

{capture name=path}{l s='Retraction form' mod='loihamon'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1>{l s='Customer Service' mod='loihamon'} - {if isset($customerThread) && $customerThread}{l s='Your reply' mod='loihamon'}{else}{l s='Retraction form' mod='loihamon'}{/if}</h1>

{if isset($confirmation)}
	<p>{l s='Your message has been successfully sent to our team. We will get back to you as soon as possible.' mod='loihamon'}</p>
{elseif isset($alreadySent)}
	<p>{l s='Your message has already been sent.' mod='loihamon'}</p>
{else}
	{$top_message|@print_r|rtrim:'1'|escape:'htmlall':'UTF-8'}
	{if isset($errors) && $errors}
	<div class="error">
		<p>{if $errors|@count > 1}{l s='There are %d errors' mod='loihamon' sprintf=$errors|@count}{else}{l s='There is %d error' mod='loihamon' sprintf=$errors|@count}{/if}</p>
		<ol>
		{foreach from=$errors key=k item=error}
			<li>{$error|@print_r|rtrim:'1'|escape:'htmlall':'UTF-8'}</li>
		{/foreach}
		</ol>
	</div>
	{/if}
	<form action="{$request_uri|escape:'htmlall':'UTF-8'}" method="post" class="std" enctype="multipart/form-data">
		<fieldset>
			<h3>{l s='Send your request' mod='loihamon'}</h3>
			<input type="hidden" name="id_contact" value="{$id_contact|escape:'htmlall':'UTF-8'}" />
		{if !$PS_CATALOG_MODE}
			{if (!isset($customerThread.id_order) || $customerThread.id_order > 0)}
			<p class="text select">
				<label for="id_order">{l s='Order ID' mod='loihamon'}*</label>
				{if !isset($customerThread.id_order) && isset($isLogged) && $isLogged == 1}
					<select name="id_order" >
						<option value="0">{l s='-- Choose --' mod='loihamon'}</option>
						{foreach from=$orderList item=order}
							<option value="{$order.value|intval}">{$order.label|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
				{elseif !isset($customerThread.id_order) && !isset($isLogged)}
					<input type="text" name="id_order" id="id_order" value="{if isset($customerThread.id_order) && $customerThread.id_order > 0}{$customerThread.id_order|intval}{else}{if isset($smarty.post.id_order)}{$smarty.post.id_order|intval}{/if}{/if}" />
				{elseif $customerThread.id_order > 0}
					<input type="text" name="id_order" id="id_order" value="{$customerThread.id_order|intval}" readonly="readonly" />
				{/if}
			</p>
			{/if}
			{if isset($isLogged) && $isLogged}
				<div class="table_block">
				{if !isset($customerThread.id_product)}
					{foreach from=$orderedProductList key=id_order item=products name=products}
						<table class="std table-product" id="{$id_order|escape:'html':'UTF-8'}_order_products">
							<thead>
								<tr>
									<th class="first_item"><input type="checkbox" /></th>
									<th class="item">{l s='Product' mod='loihamon'}</th>
									<th class="last_item">{l s='Quantity' mod='loihamon'}</th>
								</tr>
							</thead>
							<tbody>
							{foreach from=$products item=product name=product}
								<tr class="product_wrapper">
									<td>
										<input type="checkbox" name="ids_order_detail[{$product.id_order_detail|intval}]" value="{$product.id_order_detail|intval}" />
									</td>
									<td class="product_img">
										<img src="{$product.image_link|escape:'htmlall':'UTF-8'}" alt="{$product.label|escape:'htmlall':'UTF-8'}"/>
										<div>
											<strong>{$product.label|strip_tags|escape:'html':'UTF-8'}</strong>
										</div>
									</td>
									<td>
										<input class="order_qte_input" name="order_qte_input[{$product.id_order_detail|intval}]" type="text" size="2" value="{$product.quantity|intval}" />
										<label for="cb_{$product.id_order_detail|intval}"><span class="order_qte_span editable">{$product.quantity|intval}</span></label>
									</td>
								</tr>
							{/foreach}
							</tbody>
						</table>
					{/foreach}
					<br class="clear" /> 
				{elseif $customerThread.id_product > 0}
					<input type="text" name="id_product" id="id_product" value="{$customerThread.id_product|intval}" readonly="readonly" />
				{/if}
				</div>
				<p class="text">
					<label for="delivery">{l s='Delivery date' mod='loihamon'}</label>
					{if isset($customerThread.delivery)}
						<input type="text" id="delivery" name="delivery" value="{$customerThread.delivery|escape:'htmlall':'UTF-8'}" readonly="readonly" />
					{else}
						<input type="text" id="delivery" name="delivery" value="" />
					{/if}
				</p>
			{/if}
		{/if}
		<p class="text">
			<label for="name">{l s='Name' mod='loihamon'}*</label>
			{if isset($customerThread.name)}
				<input type="text" id="name" name="name" value="{$customerThread.name|escape:'htmlall':'UTF-8'}" readonly="readonly" />
			{else}
				<input type="text" id="name" name="name" value="{$name|escape:'htmlall':'UTF-8'}" />
			{/if}
		</p>
		<p class="textarea">
			<label for="address">{l s='Billing address' mod='loihamon'}*</label>
			<textarea id="address" name="address" rows="15" cols="10">{if isset($address)}{$address|escape:'htmlall':'UTF-8'}{/if}</textarea>
		</p>
		<p class="text">
			<label for="email">{l s='E-mail address' mod='loihamon'}*</label>
			{if isset($customerThread.email)}
				<input type="text" id="email" name="from" value="{$customerThread.email|escape:'htmlall':'UTF-8'}" readonly="readonly" />
			{else}
				<input type="text" id="email" name="from" value="{if isset($email)}{$email|escape:'htmlall':'UTF-8'}{/if}" />
			{/if}
		</p>
		<p class="text">
			<label for="phone">{l s='Phone number' mod='loihamon'}</label>
			{if isset($customerThread.phone)}
				<input type="text" id="phone" name="phone" value="{$customerThread.phone|escape:'htmlall':'UTF-8'}" readonly="readonly" />
			{else}
				<input type="text" id="phone" name="phone" value="{if isset($phone)}{$phone|escape:'htmlall':'UTF-8'}{/if}" />
			{/if}
		</p>
		{if $fileupload == 1}
			<p class="text">
			<label for="fileUpload">{l s='Attach File' mod='loihamon'}</label>
				<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
				<input type="file" name="fileUpload" id="fileUpload" />
			</p>
		{/if}
		<p class="textarea">
			<label for="message">{l s='Message' mod='loihamon'}</label>
			<textarea id="message" name="message" rows="15" cols="10">{if isset($message)}{$message|escape:'htmlall':'UTF-8'|stripslashes}{/if}</textarea>
		</p>
		<p class="submit">
			<input type="submit" name="submitMessage" id="submitMessage" value="{l s='Send' mod='loihamon'}" class="button_large" onclick="$(this).hide();" />
			<label for="" style="margin:5px 0 0 110px;"><em>* {l s='Required fields' mod='loihamon'}</em></label>
		</p>
	</fieldset>
</form>
{/if}

<ul class="footer_links">
	<li><a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}"><img src="{$img_dir|escape:'htmlall':'UTF-8'}icon/my-account.gif" alt="" class="icon" /></a><a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">{l s='Back to your account' mod='loihamon'}</a></li>
	<li class="f_right"><a href="{$base_dir|escape:'htmlall':'UTF-8'}"><img src="{$img_dir|escape:'htmlall':'UTF-8'}icon/home.gif" alt="" class="icon" /></a><a href="{$base_dir|escape:'htmlall':'UTF-8'}">{l s='Home' mod='loihamon'}</a></li>
</ul>