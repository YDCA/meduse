{* Loi Hamon Prestashop module
 * Copyright 2014, Prestaddons
 * Author: Prestaddons
 * Website: http://www.prestaddons.fr
 *}

<table>
	<thead>
		<tr>
			<th>Ref.</th>
			<th>Nom</th>
			<th>Quantité</th>
		</tr>
	</thead>
	<tbody>
	{foreach $list as $product}
	<tr>
		<td style="border:1px solid #D6D4D4;">
			<table class="table">
				<tr>
					<td width="10">&nbsp;</td>
					<td>
						<font size="2" face="Open-sans, sans-serif" color="#555454">
							{$product['reference']|escape:'html':'UTF-8'}
						</font>
					</td>
					<td width="10">&nbsp;</td>
				</tr>
			</table>
		</td>
		<td style="border:1px solid #D6D4D4;">
			<table class="table">
				<tr>
					<td width="10">&nbsp;</td>
					<td>
						<font size="2" face="Open-sans, sans-serif" color="#555454">
							<strong>{$product['product_name']|escape:'html':'UTF-8'}</strong>
						</font>
					</td>
					<td width="10">&nbsp;</td>
				</tr>
			</table>
		</td>
		<td style="border:1px solid #D6D4D4;">
			<table class="table">
				<tr>
					<td width="10">&nbsp;</td>
					<td align="right">
						<font size="2" face="Open-sans, sans-serif" color="#555454">
							{$product['product_quantity']|escape:'html':'UTF-8'}
						</font>
					</td>
					<td width="10">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	{/foreach}
	</tbody>
</table>
<br />