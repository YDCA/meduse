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
<h1 class="page-heading bottom-indent">
	{l s='Customer Service' mod='loihamon'} - {if isset($customerThread) && $customerThread}{l s='Your reply' mod='loihamon'}{else}{l s='Retraction form' mod='loihamon'}{/if}
</h1>
{if isset($confirmation)}
	<p class="alert alert-success">{l s='Your message has been successfully sent to our team. We will get back to you as soon as possible.' mod='loihamon'}</p>
{elseif isset($alreadySent)}
	<p class="alert alert-warning">{l s='Your message has already been sent.' mod='loihamon'}</p>
{else}
	{$top_message|@print_r|rtrim:'1'|escape:'htmlall':'UTF-8'}
	{if isset($errors) && $errors}
	<div class="alert alert-danger">
		<p>{if $errors|@count > 1}{l s='There are %d errors' mod='loihamon' sprintf=$errors|@count}{else}{l s='There is %d error' mod='loihamon' sprintf=$errors|@count}{/if}</p>
		<ol>
		{foreach from=$errors key=k item=error}
			<li>{$error|@print_r|rtrim:'1'|escape:'htmlall':'UTF-8'}</li>
		{/foreach}
		</ol>
	</div>
	{/if}
	<br />
	<form action="{$request_uri|escape:'htmlall':'UTF-8'}" method="post" class="contact-form-box" enctype="multipart/form-data">
		<fieldset>
        <h3 class="page-subheading">{l s='Send your request' mod='loihamon'}</h3>
        <div class="clearfix">
            <div class="col-xs-12 col-md-3">
                <input type="hidden" name="id_contact" value="{$id_contact|escape:'htmlall':'UTF-8'}" />
                {if !$PS_CATALOG_MODE}
                    {if (!isset($customerThread.id_order) || $customerThread.id_order > 0)}
                        <div class="form-group selector1">
                            <label for="id_order">{l s='Order ID' mod='loihamon'}</label>
                            {if !isset($customerThread.id_order) && isset($is_logged) && $is_logged}
                                <select id="id_order" name="id_order" class="form-control">
                                    <option value="0">{l s='-- Choose --' mod='loihamon'}</option>
                                    {foreach from=$orderList item=order}
                                        <option value="{$order.value|intval}"{if $order.selected|intval} selected="selected"{/if}>{$order.label|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                </select>
                            {elseif !isset($customerThread.id_order) && empty($is_logged)}
                                <input class="form-control grey" type="text" name="id_order" id="id_order" value="{if isset($customerThread.id_order) && $customerThread.id_order|intval > 0}{$customerThread.id_order|intval}{else}{if isset($smarty.post.id_order) && !empty($smarty.post.id_order)}{$smarty.post.id_order|intval}{/if}{/if}" />
                            {elseif $customerThread.id_order|intval > 0}
                                <input class="form-control grey" type="text" name="id_order" id="id_order" value="{$customerThread.id_order|intval}" readonly="readonly" />
                            {/if}
                        </div>
                    {/if}
                    {if isset($is_logged) && $is_logged}
						<p class="form-group">
							<label for="delivery">{l s='Delivery date' mod='loihamon'}</label>
							{if isset($customerThread.delivery)}
								<input class="form-control grey" type="text" id="delivery" name="delivery" value="{$customerThread.delivery|escape:'htmlall':'UTF-8'}" readonly="readonly" />
							{else}
								<input class="form-control grey validate" type="text" id="delivery" name="delivery" value="" />
							{/if}
						</p>
						<p class="form-group">
							<label for="name">{l s='Name' mod='loihamon'}*</label>
							{if isset($customerThread.name)}
								<input class="form-control grey" type="text" id="name" name="name" value="{$customerThread.name|escape:'htmlall':'UTF-8'}" readonly="readonly" />
							{else}
								<input class="form-control grey validate" type="text" id="name" name="name" value="{$name|escape:'htmlall':'UTF-8'}" />
							{/if}
						</p>
                    {/if}
                {/if}
				<p class="form-group">
					<label for="address">{l s='Billing address' mod='loihamon'}*</label>
					<textarea class="form-control" id="address" name="address" style="height:100px;">{if isset($address)}{$address|escape:'htmlall':'UTF-8'}{/if}</textarea>
				</p>
				<p class="form-group">
                    <label for="email">{l s='E-mail address' mod='loihamon'}</label>
                    {if isset($customerThread.email)}
                        <input class="form-control grey" type="text" id="email" name="from" value="{$customerThread.email|escape:'html':'UTF-8'}" readonly="readonly" />
                    {else}
                        <input class="form-control grey validate" type="text" id="email" name="from" data-validate="isEmail" value="{if isset($email)}{$email|escape:'html':'UTF-8'}{/if}" />
                    {/if}
                </p>
				<p class="form-group">
					<label for="phone">{l s='Phone number' mod='loihamon'}</label>
					{if isset($customerThread.phone)}
						<input class="form-control grey" type="text" id="phone" name="phone" value="{$customerThread.phone|escape:'htmlall':'UTF-8'}" readonly="readonly" />
					{else}
						<input class="form-control grey validate" type="text" id="phone" name="phone" value="{if isset($phone)}{$phone|escape:'htmlall':'UTF-8'}{/if}" />
					{/if}
				</p>
                {if $fileupload == 1}
                    <p class="form-group">
                        <label for="fileUpload">{l s='Attach File' mod='loihamon'}</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                        <input type="file" name="fileUpload" id="fileUpload" class="form-control" />
                    </p>
                {/if}
            </div>
            <div class="col-xs-12 col-md-9">
				{if isset($is_logged) && $is_logged}
					<div class="table_block table-responsive">
						{if !isset($customerThread.id_product)}
							{foreach from=$orderedProductList key=id_order item=products name=products}
								<table class="table table-bordered table-product" id="{$id_order|escape:'html':'UTF-8'}_order_products">
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
												<input class="order_qte_input form-control grey" name="order_qte_input[{$product.id_order_detail|escape:'html':'UTF-8'}]" type="text" size="2" value="{$product.quantity|intval}" />
												<div class="clearfix return_quantity_buttons">
													<a href="#" class="return_quantity_down btn btn-default button-minus"><span><i class="icon-minus"></i></span></a>
													<a href="#" class="return_quantity_up btn btn-default button-plus"><span><i class="icon-plus"></i></span></a>
												</div>
												<label for="cb_{$product.id_order_detail|intval}"><span class="order_qte_span editable">{$product.quantity|intval}</span></label></td>
											</td>
										</tr>
									{/foreach}
									</tbody>
								</table>
							{/foreach}
						{elseif $customerThread.id_product > 0}
							<input class="form-control grey" type="text" name="id_product" id="id_product" value="{$customerThread.id_product|intval}" readonly="readonly" />
						{/if}
					</div>
				{/if}
                <div class="form-group">
                    <label for="message">{l s='Message' mod='loihamon'}</label>
                    <textarea class="form-control" id="message" name="message">{if isset($message)}{$message|escape:'html':'UTF-8'|stripslashes}{/if}</textarea>
                </div>
            </div>
        </div>
        <div class="submit">
            <button type="submit" name="submitMessage" id="submitMessage" class="button btn btn-default button-medium"><span>{l s='Send' mod='loihamon'}<i class="icon-chevron-right right"></i></span></button>
		</div>
	</fieldset>
</form>
{/if}
<ul class="footer_links clearfix">
	<li>
		<a class="btn btn-default button button-small" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
			<span>
				<i class="icon-chevron-left"></i> {l s='Back to your account' mod='loihamon'}
			</span>
		</a>
	</li>
	<li>
		<a class="btn btn-default button button-small" href="{$base_dir|escape:'htmlall':'UTF-8'}">
			<span>
				<i class="icon-chevron-left"></i> {l s='Home' mod='loihamon'}
			</span>
		</a>
	</li>
</ul>
{addJsDefL name='contact_fileDefaultHtml'|escape:'html':'UTF-8'}{l s='No file selected' mod='loihamon' js=1}{/addJsDefL|escape:'html':'UTF-8'}
{addJsDefL name='contact_fileButtonHtml'|escape:'html':'UTF-8'}{l s='Choose File' mod='loihamon' js=1}{/addJsDefL|escape:'html':'UTF-8'}
