{*
* 2007-2013 PrestaShop
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
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{include file="$tpl_dir./errors.tpl"}

{if isset($category)}
	{if $category->id AND $category->active}
	{if $scenes || $category->description || $category->id_image}
	<div class="content_scene_cat">
					 {if $scenes}
							<div class="content_scene">
										<!-- Scenes -->
										{include file="$tpl_dir./scenes.tpl" scenes=$scenes}
										{if $category->description}
												<div class="cat_desc rte">
												{if Tools::strlen($category->description) > 350}
														<div id="category_description_short">{$description_short}</div>
														<div id="category_description_full" class="unvisible">{$category->description}</div>
														<a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}" class="lnk_more">{l s='More'}</a>
												{else}
														<div>{$category->description}</div>
												{/if}
												</div>
										{/if}
								</div>
		{else}
								<!-- Category image -->
								<div class="content_scene_cat_bg"{if $category->id_image} style="background:url({$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')|escape:'html':'UTF-8'}) right center no-repeat; background-size:cover; min-height:{$categorySize.height}px;"{/if}>
									<h1 class="page-heading{if (isset($subcategories) && !$products) || (isset($subcategories) && $products) || !isset($subcategories) && $products} product-listing{/if}"><span class="cat-name">{$category->name|escape:'html':'UTF-8'}{if isset($categoryNameComplement)}&nbsp;{$categoryNameComplement|escape:'html':'UTF-8'}{/if}</span>{include file="$tpl_dir./category-count.tpl"}</h1>

										{if $category->description}
												<div class="cat_desc">
												<span class="category-name">
														{strip}
																{$category->name|escape:'html':'UTF-8'}
																{if isset($categoryNameComplement)}
																		{$categoryNameComplement|escape:'html':'UTF-8'}
																{/if}
														{/strip}
												</span>
												{if Tools::strlen($category->description) > 350}
														<div id="category_description_short" class="rte">{$description_short}</div>
														<div id="category_description_full" class="unvisible rte">{$category->description}</div>
														<a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}" class="lnk_more">{l s='More'}</a>
												{else}
														<div class="rte">{$category->description}</div>
												{/if}
												</div>
										{/if}
								 </div>
							{/if}
				</div>
{/if}

		{if $products}
		<div class="filters-panel">
			<div class="row">
				<div class="sort-select col-lg-6 col-md-6 col-sm-7 col-xs-8">
					{include file="./product-sort.tpl"}
				</div>
				<div class="view-modes col-lg-6 col-md-6 col-sm-5 col-xs-4">
					<a class="view-grid" href="#">
						<span class="fa fa-th"></span>
					</a>
					<a class="view-list" href="#">
						<span class="fa fa-list"></span>
					</a>
				</div>
			</div>
		</div>
		{include file="./product-list.tpl" products=$products}
		<div class="filters-panel-bottom">
			{include file="./pagination.tpl"}
		</div>
		{/if}
	{elseif $category->id}
		<div class="alert alert-warning"><button data-dismiss="alert" type="button" class="close">X</button>{l s='This category is currently unavailable.'}</div>
	{/if}
{/if}
