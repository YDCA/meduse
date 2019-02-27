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

require_once(dirname(__FILE__).'/classes/tools/config.php');

class GiftProducts extends ModuleGP
{
    public function __construct()
    {
        $this->name = 'giftproducts';
        $this->tab = 'front_office_features';
        $this->version = '1.3.2';
        $this->author = 'DaRiuS';
        $this->need_instance = 0;
        $this->module_key = 'd6ad0a7286b01153b81e319a84b7530f';

        $this->tabs = array(
            array(
                'tab' => 'AdminGiftProduct',
                'name' => array(
                    'en' => 'Gift products',
                    'ru' => 'Подарочные товары'
                ),
                'parent' => 'AdminCatalog'
            )
        );

        parent::__construct();
        $this->displayName = $this->l('Gift products.');
        $this->description = $this->l('Adds gifts in product.');

        $this->classes = array(
            'GiftProduct',
            'GiftCategory',
            'GiftProductOrder'
        );
        $this->hooks = array(
            'displayRightColumnProduct',
            'displayReassurance',
            'displayHeader',
            'displayShoppingCartFooter',
            'actionValidateOrder',
            'displayAdminOrder'
        );
    }

    public function hookDisplayHeader()
    {
        if (Tools::isSubmit('ajaxGift')) {
            if (Tools::getValue('action') == 'deleteGift') {
                $id_product = Tools::getValue('id_product');
                $ids_delete_gift = array();
                $gifts_product_current_product = GiftProduct::getGiftProductsByProduct(
                    $this->context->language->id,
                    $id_product
                );
                foreach ($gifts_product_current_product as $gift) {
                    $ids_delete_gift[$gift['id_product']] = $gift['id_product'];
                }

                $categories = Product::getProductCategories($id_product);
                $gifts_category_current_product = GiftCategory::getGiftProductsByCategoriesSimple(
                    $this->context->language->id,
                    $categories
                );
                foreach ($gifts_category_current_product as $gift) {
                    $ids_delete_gift[$gift['id_product']] = $gift['id_product'];
                }

                $products = $this->context->cart->getProducts();
                $ids_products = array();
                foreach ($products as $product) {
                    if ($product['id_product'] != $id_product) {
                        $ids_products[] = $product['id_product'];
                    }
                }
                $gifts_products = GiftBase::getGiftProductsByProducts($this->context->language->id, $ids_products);

                foreach ($gifts_products as $gift_products) {
                    if (isset($ids_delete_gift[$gift_products['id_product']])) {
                        unset($ids_delete_gift[$gift_products['id_product']]);
                    }
                }

                die(Tools::jsonEncode($ids_delete_gift));
            }
        }

        if ($this->context->controller instanceof ProductController) {
            $this->context->controller->addCSS($this->_path.'views/css/front.css');
        }
        $this->context->controller->addJS($this->_path.'views/js/giftproducts.js');

        if (($this->context->controller instanceof OrderController
                || $this->context->controller instanceof CartController)
            && version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $presentedCart = $this->context->controller->cart_presenter->present($this->context->cart);

            $gift_products = $this->getCartGiftProducts();
            $r = new ReflectionClass(PrestaShop\PrestaShop\Adapter\Cart\CartPresenter::class);
            $present_product = $r->getMethod('presentProduct');
            $present_product->setAccessible(true);

            $products = array();
            foreach ($gift_products as $gift_product) {
                $products[] = $present_product->invokeArgs(
                    $this->context->controller->cart_presenter,
                    array(
                        $gift_product
                    )
                );
            }
            $products = $this->context->controller->cart_presenter->addCustomizedData($products, $this->context->cart);
            $presentedCart['products'] = array_merge($presentedCart['products'], $products);

            $this->context->smarty->assign(array(
                'cart' => $presentedCart
            ));
        }
    }

    public function hookDisplayReassurance()
    {
        return $this->hookDisplayFooterProduct();
    }

    public function hookDisplayRightColumnProduct()
    {
        return $this->hookDisplayFooterProduct();
    }

    public function hookDisplayFooterProduct()
    {
        if (!Tools::getValue('id_product')) {
            return false;
        }
        $id_product = Tools::getValue('id_product');
        $gift_products = GiftProduct::getGiftProductsByProduct(
            $this->context->language->id,
            $id_product
        );
        $categories = Product::getProductCategories($id_product);
        $categories_gift_products = GiftCategory::getGiftProductsByCategoriesSimple(
            $this->context->language->id,
            $categories
        );
        $tmp_ids_products = array();
        foreach ($gift_products as $gift_product) {
            $tmp_ids_products[] = (int)$gift_product['id_product'];
        }

        foreach ($categories_gift_products as $categories_gift_product) {
            if (!in_array($categories_gift_product['id_product'], $tmp_ids_products)) {
                $gift_products[] = $categories_gift_product;
                $tmp_ids_products[] = (int)$categories_gift_product['id_product'];
            }
        }
        $this->context->smarty->assign(array(
            'products_gift' => $gift_products
        ));
        return $this->display(__FILE__, 'footer_product.tpl');
    }

    public function hookDisplayShoppingCartFooter()
    {
        $this->context->smarty->assign(array(
            'gift_products' => $this->getCartGiftProducts()
        ));

        //return $this->display(__FILE__, 'shopping_cart.tpl');
    }

    public function getCartGiftProducts()
    {
        $products = $this->context->cart->getProducts();
        $ids_products = array();
        foreach ($products as $product) {
            $ids_products[] = $product['id_product'];
        }
        $gift_products = GiftProduct::getGiftProductsByProducts($this->context->language->id, $ids_products);
        $ids_gift_products = array();
        foreach ($gift_products as $gift_product) {
            $ids_gift_products[] = (int)$gift_product['id_product'];
        }

        $product_categories = array();
        foreach ($ids_products as $ids_product) {
            $product_categories = array_unique(
                array_merge(
                    $product_categories,
                    Product::getProductCategories($ids_product)
                )
            );
        }
        $categories_gift_products = GiftCategory::getGiftProductsByCategories(
            $this->context->language->id,
            $product_categories
        );
        foreach ($categories_gift_products as $categories_gift_product) {
            if (!in_array($categories_gift_product['id_product'], $ids_gift_products)) {
                $gift_products[] = $categories_gift_product;
            }
        }
        return $gift_products;
    }

    public function hookActionValidateOrder($params)
    {
        $products = $params['cart']->getProducts();
        $ids_products = array();
        foreach ($products as $product) {
            $ids_products[] = $product['id_product'];
        }
        $gift_products = GiftBase::getGiftProductsByProducts(
            $this->context->language->id,
            $ids_products
        );
        foreach ($gift_products as $gift_product) {
            $gift_product_order = new GiftProductOrder();
            $gift_product_order->id_order = $params['order']->id;
            $gift_product_order->id_gift = $gift_product['id_gift'];
            $gift_product_order->save();
        }
    }

    public function hookDisplayAdminOrder($params)
    {
        $id_order = $params['id_order'];
        $this->context->controller->addCSS($this->_path.'views/css/admin.css');
        $gift_products = GiftProductOrder::getGiftProductsByOrder(
            $this->context->language->id,
            $id_order
        );
        if (!count($gift_products)) {
            return false;
        }
        $this->context->smarty->assign(array(
            'products_gift' => $gift_products
        ));
        return $this->display(__FILE__, 'admin_order.tpl');
    }
}