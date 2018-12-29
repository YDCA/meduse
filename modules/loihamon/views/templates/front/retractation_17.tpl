{* Loi Hamon Prestashop module
 * Copyright 2014, Prestaddons
 * Author: Prestaddons
 * Website: http://www.prestaddons.fr
 *}
{extends file='page.tpl'} 

{block name='breadcrumb'}
<nav data-depth="2" class="breadcrumb hidden-sm-down">
	<ol itemscope itemtype="http://schema.org/BreadcrumbList">
		<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
			<a itemprop="item" href="{$base_dir|escape:'htmlall':'UTF-8'}">
				<span itemprop="name">{l s='Home' mod='loihamon'}</span>
			</a>
			<meta itemprop="position" content="1">
		</li>
		<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
			<a itemprop="item" href="{$retraction_form_link|escape:'htmlall':'UTF-8'}">
				<span itemprop="name">{l s='Retraction form' mod='loihamon'}</span>
			</a>
			<meta itemprop="position" content="1">
		</li>
	</ol>
</nav>
{/block}

{block name='content'}
<script type="text/javascript">
	var deliveryTab = new Array();
	{foreach from=$deliveryList item=delivery name=deliveryList}
		deliveryTab[{$delivery.id_order|escape:'htmlall':'UTF-8'}] = '{$delivery.delivery_date|escape:'htmlall':'UTF-8'}';
	{/foreach}
</script>

{capture name=path}{l s='Retraction form' mod='loihamon'}{/capture}
<section id="main">
	<header class="page-header">       
		<h1 class="page-heading bottom-indent">
			{l s='Customer Service' mod='loihamon'} - {if isset($customerThread) && $customerThread}{l s='Your reply' mod='loihamon'}{else}{l s='Retraction form' mod='loihamon'}{/if}
		</h1>
	</header>
		
	<section id="content" class="page-content card card-block">

		{if isset($confirmation)}
			<aside id="notifications">
				<div class="container">
					<article class="alert alert-success" role="alert" data-alert="success"><ul><li>{l s='Your message has been successfully sent to our team. We will get back to you as soon as possible.' mod='loihamon'}</li></ul></article>
				</div>
			</aside>
		{elseif isset($alreadySent)}
			<aside id="notifications">
				<div class="container">
					<article class="alert alert-warning" role="alert" data-alert="warning"><ul><li>{l s='Your message has already been sent.' mod='loihamon'}</li></ul></article>
				</div>
			</aside>
		{else}
			{if isset($errors) && $errors}
			<aside id="notifications">
				<div class="container">
					<article class="alert alert-danger" role="alert" data-alert="danger">
						<ul>
						{foreach from=$errors key=k item=error}
							<li>{$error|@print_r|rtrim:'1'|escape:'html':'UTF-8'}</li>
						{/foreach}
						</ul>
					</article>
				</div>
			</aside>
			{/if}
			<h6>{$top_message|@print_r|rtrim:'1'|escape:'htmlall':'UTF-8'}</h6>
			<form action="{$request_uri|escape:'htmlall':'UTF-8'}" method="post" class="contact-form-box" enctype="multipart/form-data">
				<h3 class="page-subheading">{l s='Send your request' mod='loihamon'}</h3>
				<div class="clearfix">
					<div class="col-xs-12 col-md-3">
						<input type="hidden" name="id_contact" value="{$id_contact|escape:'htmlall':'UTF-8'}" />
						{if !$PS_CATALOG_MODE}
							{if (!isset($customerThread.id_order) || $customerThread.id_order > 0)}
								<div class="form-group selector1">
									<label>{l s='Order ID' mod='loihamon'}</label>
									{if !isset($customerThread.id_order) && isset($is_logged) && $is_logged}
										<select name="id_order" class="form-control">
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
					</div>
					<div class="col-xs-12 col-md-9">
						{if isset($is_logged) && $is_logged}
							<div class="table_block table-responsive col-md-12">
								{if !isset($customerThread.id_product)}
									{foreach from=$orderedProductList key=id_order item=products name=products}
										<table class="table table-bordered table-product" id="{$id_order|escape:'html':'UTF-8'}_order_products">
											<thead>
												<tr>
													<th class="first_item" width="5%"><input type="checkbox" /></th>
													<th class="item" width="80%">{l s='Product' mod='loihamon'}</th>
													<th class="last_item" width="15%">{l s='Quantity' mod='loihamon'}</th>
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
													<td align="center">
														<input class="order_qte_input form-control grey" name="order_qte_input[{$product.id_order_detail|escape:'html':'UTF-8'}]" type="text" size="2" value="{$product.quantity|intval}" />
														<div class="clearfix return_quantity_buttons">
															<a href="#" class="return_quantity_down"><span><i class="material-icons">&#xE15D;</i></span></a>
															<a href="#" class="return_quantity_up"><span><i class="material-icons">&#xE148;</i></span></a>
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
						<div class="form-group col-md-12">
							<label for="message">{l s='Message' mod='loihamon'}</label>
							<textarea class="form-control" id="message" name="message">{if isset($message)}{$message|escape:'html':'UTF-8'|stripslashes}{/if}</textarea>
						</div>
						
						<div class="form-group">&nbsp;</div>
						{if $fileupload == 1}
							<div id="file-upload-wrapper" class="form-group" style="padding:0;">
								<label class="col-md-12 text-xs-left">{l s='Attachment' mod='loihamon'}</label>
								<div class="col-md-6">
								  <input type="file" name="fileUpload" class="filestyle">
								</div>
								<span class="col-md-3 form-control-comment">
								  {l s='optional' mod='loihamon'}
								</span>
							</div>
						{/if}
						<div class="form-group {if $fileupload == 1}col-md-3{else}col-md-12{/if} text-xs-right">
							<button type="submit" name="submitMessage" id="submitMessage" class="btn btn-primary"><span>{l s='Send' mod='loihamon'}<i class="icon-chevron-right right"></i></span></button>
						</div>
					</div>
				</div>
				
		</form>
		{/if}
	</section>
	<footer class="page-footer">
		<a class="account-link" href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
			<i class="material-icons">&#xE5CB;</i>
			<span>{l s='Back to your account' mod='loihamon'}</span>
		</a>
		<a class="account-link" href="{$base_dir|escape:'htmlall':'UTF-8'}">
			<i class="material-icons">&#xE88A;</i>
			<span> {l s='Home' mod='loihamon'}</span>
		</a>
	</footer>
</section>
{*addJsDefL name='contact_fileDefaultHtml'|escape:'html':'UTF-8'}{l s='No file selected' mod='loihamon' js=1}{/addJsDefL|escape:'html':'UTF-8'*}
{*addJsDefL name='contact_fileButtonHtml'|escape:'html':'UTF-8'}{l s='Choose File' mod='loihamon' js=1}{/addJsDefL|escape:'html':'UTF-8'*}
{/block}