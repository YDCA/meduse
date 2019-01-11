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

class GiftCategory extends ObjectModel
{
    public $id_category;
    public $id_gift;

    public static $definition = array(
        'table' => 'gift_category',
        'primary' => 'id_gift_category',
        'fields' => array(
            'id_category' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'id_gift' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            )
        ),
    );

    public static function getGiftProductsByCategory($id_lang, $category)
    {
        $context = Context::getContext();
        $id_shop = Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP
            ? (int)$context->shop->id : 'p.id_shop_default';

        $id_category = ($category instanceof Category ? (int)$category->id : (int)$category);
        $products = Db::getInstance()->executeS('SELECT gc.`id_gift`, gc.`id_category`,
        gc.`id_gift_category` , pl.*, p.*,
        (SELECT i.`id_image`
           FROM '._DB_PREFIX_.'image i
           WHERE i.`id_product` = gc.`id_gift`
           ORDER BY i.`cover` DESC LIMIT 0,1
        ) as id_image
        FROM '._DB_PREFIX_.'gift_category gc
        LEFT JOIN '._DB_PREFIX_.'product p ON p.`id_product` = gc.`id_gift`
        LEFT JOIN '._DB_PREFIX_.'product_lang pl ON p.`id_product` = pl.`id_product`
         AND pl.`id_lang` = '.(int)$id_lang.'
        AND pl.`id_shop` = '.pSQL($id_shop).'
        WHERE gc.`id_category` = '.(int)$id_category);
        return $products;
    }

    public static function getGiftProductsByCategoriesSimple($id_lang, $ids_categories)
    {
        if (!is_array($ids_categories) || !count($ids_categories)) {
            return array();
        }
        $context = Context::getContext();
        $id_shop = Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP
            ? (int)$context->shop->id : 'p.id_shop_default';

        $products = Db::getInstance()->executeS(
            'SELECT gc.`id_gift`, gc.`id_category`,
            gc.`id_gift_category` , pl.*, p.*,
            (SELECT i.`id_image` FROM '._DB_PREFIX_.'image i WHERE i.`id_product` = gc.`id_gift`
             ORDER BY i.`cover` DESC LIMIT 0,1) as id_image
            FROM '._DB_PREFIX_.'gift_category gc
            LEFT JOIN '._DB_PREFIX_.'product p ON p.`id_product` = gc.`id_gift`
            LEFT JOIN '._DB_PREFIX_.'product_lang pl
             ON p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$id_lang.'
            AND pl.`id_shop` = '.pSQL($id_shop).'
            WHERE gc.`id_category` IN('.implode(',', array_map('intval', $ids_categories)).')'
        );
        return $products;
    }

    public static function getCategoriesHasGifts($id_lang)
    {
        return Db::getInstance()->executeS(
            'SELECT
            cl.*,
            COUNT(gc.id_category) as count_gift
            FROM '._DB_PREFIX_.'gift_category gc
            LEFT JOIN '._DB_PREFIX_.'category_lang cl ON cl.`id_category` = gc.`id_category`
             AND cl.`id_lang` = '.(int)$id_lang.'
            GROUP BY gc.`id_category`'
        );
    }

    public static function getGiftProductsByCategories($id_lang, $ids_categories)
    {
        if (!is_array($ids_categories) || !count($ids_categories)) {
            return array();
        }
        $context = Context::getContext();
        $id_shop = Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP
            ? (int)$context->shop->id : 'p.id_shop_default';

        $products = Db::getInstance()->executeS(
            'SELECT '.GiftBase::getFieldsSql().'
            FROM '._DB_PREFIX_.'gift_category gc
            LEFT JOIN '._DB_PREFIX_.'product p ON p.`id_product` = gc.`id_gift`
            LEFT JOIN '._DB_PREFIX_.'product_lang pl ON gc.`id_gift` = pl.`id_product`
             AND pl.`id_lang` = '.(int)$id_lang.'
            AND pl.`id_shop` = '.pSQL($id_shop).'
            WHERE gc.`id_category` IN ('.implode(',', $ids_categories).') GROUP BY p.`id_product`'
        );
        if (is_array($products) && count($products)) {
            foreach ($products as &$product) {
                $image = Product::getCover($product['id_product']);
                $product['id_image'] = $image['id_image'];
                $product['id_address_delivery'] = 0;
            }
        } else {
            return array();
        }
        return $products;
    }
}
