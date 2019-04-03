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

<!--Gift products-->
{if is_array($products_gift) && count($products_gift)}
<div>
    <h3>{l s='Gift products' mod='giftproducts'}</h3>
    <ul>
        {foreach from=$products_gift item=product_gift}
            <li>
                <img width="70px" src="{$link->getImageLink($product_gift.link_rewrite,$product_gift.id_image,'home_default')|escape:'quotes':'UTF-8'}">
                {$product_gift.name|escape:'quotes':'UTF-8'}
            </li>
        {/foreach}
    </ul>
</div>
{/if}
<!--/Gift products-->