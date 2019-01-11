<?php
/**
 * 2007-2018 PrestaShop
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
 * @copyright 2007-2018 Goryachev Dmitry
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

class GiftBase
{
    public static function getFieldsSql()
    {
        $fields = array(
            'id_gift',
            'pl.*',
            'p.*',
            '0 as allow_oosp',
            '1 as quantity_available',
            '0 as price_wt',
            '0 as price_without_reduction',
            '1 as quantity_all_versions',
            '1 as cart_quantity',
            '1 as quantity',
            '0 as total_wt',
            '0 as id_product_attribute',
            '1 as gift',
            '1 as is_gift',
            'null as id_customization'
        );
        return implode(',', $fields);
    }

    /**
     * @param $id_lang
     * @param $ids_products
     * @return array|false|mysqli_result|null|PDOStatement|resource
     */
    public static function getGiftProductsByProducts($id_lang, $ids_products)
    {
        if (!is_array($ids_products) || !count($ids_products)) {
            return array();
        }

        $gift_products = GiftProduct::getGiftProductsByProducts($id_lang, $ids_products);

        $product_categories = array();
        foreach ($ids_products as $ids_product) {
            $product_categories = array_unique(
                array_merge($product_categories, Product::getProductCategories($ids_product))
            );
        }
        $categories_gift_products = GiftCategory::getGiftProductsByCategories($id_lang, $product_categories);
        $tmp_ids_products = array();
        foreach ($gift_products as $gift_product) {
            $tmp_ids_products[] = (int)$gift_product['id_product'];
        }

        foreach ($categories_gift_products as $categories_gift_product) {
            if (!in_array($categories_gift_product['id_product'], $tmp_ids_products)) {
                $gift_products[] = $categories_gift_product;
            }
        }

        return $gift_products;
    }
}
