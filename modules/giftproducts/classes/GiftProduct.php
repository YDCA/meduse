<?php
/**
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
 */

class GiftProduct extends ObjectModel
{
    public $id_product;
    public $id_gift;

    public static $definition = array(
        'table' => 'gift_product',
        'primary' => 'id_gift_product',
        'fields' => array(
            'id_product' => array(
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

    public static function getGiftProductsByProduct($id_lang, $product)
    {
        $context = Context::getContext();
        $id_shop = Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP
            ? (int)$context->shop->id : 'p.id_shop_default';
        $id_product = ($product instanceof Product ? (int)$product->id : (int)$product);
        $products = Db::getInstance()->executeS(
            'SELECT id_gift, gp.`id_product` as id_real_product,
            gp.`id_gift` , pl.*, p.*,
            (SELECT i.`id_image`
              FROM '._DB_PREFIX_.'image i
              WHERE i.`id_product` = gp.`id_gift` ORDER BY i.`cover` DESC LIMIT 0,1
            ) as id_image
            FROM '._DB_PREFIX_.'gift_product gp
            LEFT JOIN '._DB_PREFIX_.'product p ON p.`id_product` = gp.`id_gift`
            LEFT JOIN '._DB_PREFIX_.'product_lang pl ON gp.`id_gift` = pl.`id_product`
             AND pl.`id_lang` = '.(int)$id_lang.'
            AND pl.`id_shop` = '.pSQL($id_shop).'
            WHERE gp.`id_product` = '.(int)$id_product
        );
        return $products;
    }

    public static function getProductsHasGifts($id_lang)
    {
        $context = Context::getContext();
        $id_shop = Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP
            ? (int)$context->shop->id : 'p.id_shop_default';
        return Db::getInstance()->executeS(
            'SELECT
            pl.*,
            COUNT(gp.id_product) as count_gift,
            (SELECT i.`id_image` FROM '._DB_PREFIX_.'image i
             WHERE i.`id_product` = gp.`id_product` ORDER BY i.`cover` DESC LIMIT 0,1) as id_image
            FROM '._DB_PREFIX_.'gift_product gp
            LEFT JOIN '._DB_PREFIX_.'product p ON p.`id_product` = gp.`id_product`
            LEFT JOIN '._DB_PREFIX_.'product_lang pl ON pl.`id_product` = gp.`id_product`
             AND pl.`id_lang` = '.(int)$id_lang.'
            AND pl.`id_shop` = '.pSQL($id_shop).'
            GROUP BY gp.`id_product`'
        );
    }

    public static function getGiftProductsByProducts($id_lang, $ids_products, $id_cart)
    {
        if (!is_array($ids_products) || !count($ids_products)) {
            return array();
        }
        $context = Context::getContext();
        $id_shop = Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP
            ? (int)$context->shop->id : 'p.id_shop_default';
        $products = Db::getInstance()->executeS(
            'SELECT '.GiftBase::getFieldsSql().',
            SUM((
                SELECT SUM(cp.`quantity`)
                FROM '._DB_PREFIX_.'cart_product cp
                WHERE cp.`id_product` = gp.`id_product` AND cp.`id_cart` = '.(int)$id_cart.'
            )) as quantity
            FROM '._DB_PREFIX_.'gift_product gp
            LEFT JOIN '._DB_PREFIX_.'product p ON p.`id_product` = gp.`id_gift`
            LEFT JOIN '._DB_PREFIX_.'product_lang pl ON gp.`id_gift` = pl.`id_product`
             AND pl.`id_lang` = '.(int)$id_lang.'
            AND pl.`id_shop` = '.pSQL($id_shop).'
            WHERE gp.`id_product` IN ('.implode(',', $ids_products).') GROUP BY p.`id_product`'
        );

        if (is_array($products) && count($products)) {
            foreach ($products as &$product) {
                $image = Product::getCover($product['id_product']);
                $product['cart_quantity'] = $product['quantity'];
                $product['id_image'] = $image['id_image'];
                $product['id_address_delivery'] = 0;
            }
        } else {
            return array();
        }

        return $products;
    }
}
