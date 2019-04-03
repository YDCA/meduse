{*
* 2007-2019 PrestaShop
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
* @author    Goryachev Dmitry    <dariusakafest@gmail.com>
* @copyright 2007-2019 Goryachev Dmitry
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

<script>
    var no_image = '<img class="no_image" src="{"`$smarty.const._MODULE_DIR_`giftproducts/views/img/"|escape:'quotes':'UTF-8'}no-image.jpg">';
    var l_delete_item_with_gift = "{l s='Are you sure you want to delete the item with gifts' mod='giftproducts'}";
    var l_without_gift = "{l s='Products which not have been added gifts will not be saved' mod='giftproducts'}";
</script>

<div class="cols">
    <div class="col_right panel">
        <h3 class="panel_heading">{l s='Search and adding products' mod='giftproducts'}</h3>
        <div class="search_panel">
            <div class="row add_gift_product_with_gifts">
                <div class="selected_product_with_gift"></div>
                <input type="hidden" value="0" name="product_with_gift"/>
                <input type="hidden" value="0" name="category_with_gift"/>
                <a class="cancel_adding_gift btn btn-danger" href="#">
                    <i class="icon-remove"></i>
                </a>
            </div>
            <div class="row">
                <label>{l s='List categories' mod='giftproducts'}</label>
                <div class="tree_custom">
                    {include file="./tree.tpl"
                    categories=$tree_custom.categories
                    id_category=$tree_custom.id_category
                    root=true
                    view_header=true
                    multiple=true
                    selected_categories=array()
                    name='search_category'
                    }
                </div>
                <script>
                    var tree_cat = new TreeCustom('.tree_custom', '.tree_categories_header');
                    tree_cat.init();
                </script>
            </div>
            <div class="row">
                <label>{l s='Write for search' mod='giftproducts'}</label>
                <div>
                    <input type="text" name="search_query"/>
                </div>
            </div>
            <div class="row">
                <input class="btn btn-success" value="{l s='Search' mod='giftproducts'}" type="button" id="btnSearch" name="btn_search"/>
            </div>
        </div>
        <div class="list_product_query">

        </div>
    </div>

    <div class="col_left panel">
        <h3 class="panel_heading">{l s='Product with gifts' mod='giftproducts'}</h3>
        <div class="list_product_with_gift">
            {if is_array($product_with_gifts) && count($product_with_gifts)}
                {foreach from=$product_with_gifts item=product}
                    <div data-product="{$product.id_product|intval}" class="product_with_gift">
                        <div class="product_title">
                            {$product.image|escape:'quotes':'UTF-8'}
                            <span>
                                {$product.name|escape:'quotes':'UTF-8'}
                            </span>
                            <a class="delete_product_with_gift btn btn-danger" href="#">
                                <i class="icon-remove"></i>
                            </a>
                            <a class="add_gift btn btn-default" href="#">
                                <i class="icon-gift"></i>
                                {l s='Add gift' mod='giftproducts'}
                            </a>
                            <div class="count_gifts">{if isset($product.gifts)}{count($product.gifts)|intval}{else}0{/if}</div>
                        </div>
                        <div class="product_gifts">
                            {if isset($product.gifts) && count($product.gifts)}
                                {foreach from=$product.gifts item=gift}
                                    <div data-gift="{$gift.id_gift|intval}" class="gift">
                                        <input type="hidden" name="giftproduct[{$product.id_product|intval}]" value="{$gift.id_gift|intval}">
                                        {$gift.image|escape:'quotes':'UTF-8'}
                                        <span>
                                            {$gift.name|escape:'quotes':'UTF-8'}
                                        </span>
                                        <a class="delete_gift btn btn-danger" href="#">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </div>
                                {/foreach}
                            {/if}
                        </div>
                    </div>
                {/foreach}
            {/if}
        </div>
        <div class="panel-footer" style="display: none;">
            <button type="button" id="saveGiftProducts" class="btn btn-success">
                <i class="icon-save"></i>
                {l s='Save' mod='giftproducts'}
            </button>
        </div>
    </div>

    <div class="col_left panel">
        <h3 class="panel_heading">{l s='Category with gifts' mod='giftproducts'}</h3>
        <div class="list_category_with_gift">
            {if is_array($category_with_gifts) && count($category_with_gifts)}
                {foreach from=$category_with_gifts item=category}
                    <div data-category="{$category.id_category|intval}" class="category_with_gift">
                        <div class="category_title">
                            <span>
                                {$category.name|escape:'quotes':'UTF-8'}
                            </span>
                            <a class="delete_category_with_gift btn btn-danger" href="#">
                                <i class="icon-remove"></i>
                            </a>
                            <a class="add_gift_category btn btn-default" href="#">
                                <i class="icon-gift"></i>
                                {l s='Add gift' mod='giftproducts'}
                            </a>
                            <div class="count_gifts">{if isset($category.gifts)}{count($category.gifts)|intval}{else}0{/if}</div>
                        </div>
                        <div class="category_gifts">
                            {if isset($category.gifts) && count($category.gifts)}
                                {foreach from=$category.gifts item=gift}
                                    <div data-gift="{$gift.id_gift|intval}" class="gift">
                                        <input type="hidden" name="giftcategory[{$category.id_category|intval}]" value="{$gift.id_gift|intval}">
                                        {$gift.image|escape:'quotes':'UTF-8'}
                                        <span>
                                            {$gift.name|escape:'quotes':'UTF-8'}
                                        </span>
                                        <a class="delete_gift btn btn-danger" href="#">
                                            <i class="icon-remove"></i>
                                        </a>
                                    </div>
                                {/foreach}
                            {/if}
                        </div>
                    </div>
                {/foreach}
            {/if}
        </div>
        <div class="form-group clearfix">
            <div class="col-lg-9">
                <select name="category_list">
                    {if is_array($categories_list) && count($categories_list)}
                        {foreach from=$categories_list item=category}
                            <option value="{$category.id_category|intval}">{$category.name|escape:'quotes':'UTF-8'}</option>
                        {/foreach}
                    {/if}
                </select>
            </div>
            <div class="col-lg-3">
                <button class="btn btn-default add_category">
                    {l s='Add' mod='giftproducts'}
                </button>
            </div>
        </div>
        <div class="panel-footer" style="display: none;">
            <button type="button" id="saveGiftProducts" class="btn btn-success">
                <i class="icon-save"></i>
                {l s='Save' mod='giftproducts'}
            </button>
        </div>
    </div>
</div>

<script id="tpl_search_product" type="text/html">
    <div data-product="<%= id_product %>" class="search_product">
        <div class="col_left"> <%= image %> <span><%= name %></span></div>
        <div class="col_right"><button class="<%= action %> btn btn-success">{l s='Add' mod='giftproducts'}</button></div>
    </div>
</script>
<script id="tpl_product_with_gift" type="text/html">
    <div data-product="<%= id_product %>" class="product_with_gift">
        <div class="product_title">
            <%= product_title %>
            <a class="delete_product_with_gift btn btn-danger" href="#">
                <i class="icon-remove"></i>
            </a>
            <a class="add_gift btn btn-default" href="#">
                <i class="icon-gift"></i>
                {l s='Add gift' mod='giftproducts'}
            </a>
            <div class="count_gifts">0</div>
        </div>
        <div class="product_gifts">
        </div>
    </div>
</script>
<script id="tpl_gift" type="text/html">
    <div data-gift="<%= id_gift_product %>" class="gift">
        <input type="hidden" name="giftproduct[<%= id_product %>]" value="<%= id_gift_product %>">
        <%= product_title %>
        <a class="delete_gift btn btn-danger" href="#">
            <i class="icon-remove"></i>
        </a>
    </div>
</script>

<script id="tpl_gift_category" type="text/html">
    <div data-gift="<%= id_gift_product %>" class="gift">
        <input type="hidden" name="giftcategory[<%= id_category %>]" value="<%= id_gift_product %>">
        <%= product_title %>
        <a class="delete_gift btn btn-danger" href="#">
            <i class="icon-remove"></i>
        </a>
    </div>
</script>


<script id="tpl_category_with_gift" type="text/html">
    <div data-category="<%= id_category %>" class="category_with_gift">
        <div class="category_title">
            <%= category_title %>
            <a class="delete_category_with_gift btn btn-danger" href="#">
                <i class="icon-remove"></i>
            </a>
            <a class="add_gift_category btn btn-default" href="#">
                <i class="icon-gift"></i>
                {l s='Add gift' mod='giftproducts'}
            </a>
            <div class="count_gifts">0</div>
        </div>
        <div class="category_gifts">
        </div>
    </div>
</script>