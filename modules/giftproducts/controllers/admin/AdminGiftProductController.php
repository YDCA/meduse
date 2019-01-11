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

require_once(dirname(__FILE__).'/../../classes/tools/config.php');
class AdminGiftProductController extends ModuleAdminControllerGP
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->className = 'GiftProduct';
        $this->bootstrap = true;
        $this->display = 'edit';
        parent::__construct();
    }

    public function renderForm()
    {
        $this->context->controller->addCSS(array(
            $this->module->getPathUri().'views/css/admin.css',
            $this->module->getPathUri().'views/css/tree_custom.css',
            $this->module->getPathUri().'views/css/select2.css'
        ));

        $this->context->controller->addJS(array(
            $this->module->getPathUri().'views/js/underscore.js',
            $this->module->getPathUri().'views/js/tree_custom.js',
            $this->module->getPathUri().'views/js/vendor/select2.js',
            $this->module->getPathUri().'views/js/admin.js'
        ));

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Gift products')
            )
        );
        $product_with_gifts = GiftProduct::getProductsHasGifts($this->context->language->id);
        foreach ($product_with_gifts as &$product) {
            $product['image'] = ImageManager::thumbnail(
                _PS_PROD_IMG_DIR_.Image::getImgFolderStatic($product['id_image'])
                .$product['id_image'].'.jpg',
                'gp_'.$product['id_product'].'.jpg',
                70
            );
            $gifts = GiftProduct::getGiftProductsByProduct(
                $this->context->language->id,
                $product['id_product']
            );
            foreach ($gifts as $key => $gift) {
                $gifts[$key]['image'] = ImageManager::thumbnail(
                    _PS_PROD_IMG_DIR_.Image::getImgFolderStatic($gift['id_image'])
                    .$gift['id_image'].'.jpg',
                    'gp_gift_'.$gift['id_gift'].'.jpg',
                    30
                );
            }
            $product['gifts'] = $gifts;
        }

        $category_with_gifts = GiftCategory::getCategoriesHasGifts($this->context->language->id);
        foreach ($category_with_gifts as &$category) {
            $gifts = GiftCategory::getGiftProductsByCategory(
                $this->context->language->id,
                $category['id_category']
            );
            foreach ($gifts as $key => $gift) {
                $gifts[$key]['image'] = ImageManager::thumbnail(
                    _PS_PROD_IMG_DIR_.Image::getImgFolderStatic($gift['id_image'])
                    .$gift['id_image'].'.jpg',
                    'gp_gift_'.$gift['id_gift'].'.jpg',
                    30
                );
            }
            $category['gifts'] = $gifts;
        }

        $this->tpl_form_vars['product_with_gifts'] = $product_with_gifts;
        $this->tpl_form_vars['category_with_gifts'] = $category_with_gifts;

        $categories = Category::getSimpleCategories($this->context->language->id);
        foreach ($categories as $key => $c) {
            foreach ($category_with_gifts as $cat) {
                if ($cat['id_category'] == $c['id_category']) {
                    unset($categories[$key]);
                }
            }
        }

        $this->tpl_form_vars['categories_list'] = $categories;

        $this->tpl_form_vars['tree_custom'] = array(
            'id_category' => Configuration::get('PS_HOME_CATEGORY'),
            'categories' => Category::getCategories($this->context->language->id, true),
        );
        return parent::renderForm();
    }

    public function ajaxProcessSearchProducts()
    {
        $query = Tools::getValue('query');
        $categories = $this->getIntArrayRequestVar('categories');
        $exclude_ids = $this->getIntArrayRequestVar('exclude_ids');

        $sql_query = false;
        if (Tools::strlen(trim($query))) {
            $words = explode(' ', $query);
            $this->pSQLArray($words);
            $sql_query = ' AND pl.`name` REGEXP "'.implode('|', $words).'"';
        }
        $sql_category = false;
        if (is_array($categories) && count($categories)) {
            $sql_category = ' AND (SELECT COUNT(p.`id_product`)
            FROM '._DB_PREFIX_.'category_product cp
            WHERE cp.`id_product` = p.`id_product` AND cp.`id_category` IN('.implode(',', $categories).'))';
        }

        $sql_exclude_ids = false;
        if (is_array($exclude_ids) && count($exclude_ids)) {
            $sql_exclude_ids = ' AND p.`id_product` NOT IN('.implode(',', $exclude_ids).')';
        }

        $products = Db::getInstance()->executeS(
            'SELECT
            p.`id_product`,
            pl.`name`,
            (SELECT i.`id_image`
              FROM '._DB_PREFIX_.'image i
              WHERE i.`id_product` = p.`id_product`
              ORDER BY i.`cover` DESC LIMIT 0,1
            ) as id_image
            FROM '._DB_PREFIX_.'product p
            LEFT JOIN '._DB_PREFIX_.'product_lang pl
             ON pl.`id_product` = p.`id_product` AND pl.`id_lang` = '.(int)$this->context->language->id.'
            WHERE 1'.($sql_query ? $sql_query : '')
            .($sql_category ? $sql_category : '').($sql_exclude_ids ? $sql_exclude_ids : '')
        );

        if (is_array($products) && count($products)) {
            foreach ($products as &$product) {
                $product['image'] = ImageManager::thumbnail(
                    _PS_PROD_IMG_DIR_.Image::getImgFolderStatic($product['id_image'])
                    .$product['id_image'].'.jpg',
                    'gp_'.$product['id_product'].'.jpg',
                    70
                );
            }
        }
        die(Tools::jsonEncode($products));
    }

    public function ajaxProcessSaveGiftProduct()
    {
        $giftproduct = Tools::getValue('giftproduct');
        Db::getInstance()->execute('TRUNCATE `'._DB_PREFIX_.'gift_product`');
        if (is_array($giftproduct) && count($giftproduct)) {
            $insert = array();
            foreach ($giftproduct as $gift) {
                $insert[] = array(
                    'id_product' => (int)$gift['id_product'],
                    'id_gift' => (int)$gift['id_gift']
                );
            }
            Db::getInstance()->insert('gift_product', $insert);
        }

        $giftcategory = Tools::getValue('giftcategory');
        Db::getInstance()->execute('TRUNCATE `'._DB_PREFIX_.'gift_category`');
        if (is_array($giftcategory) && count($giftcategory)) {
            $insert = array();
            foreach ($giftcategory as $gift) {
                $insert[] = array(
                    'id_category' => (int)$gift['id_category'],
                    'id_gift' => (int)$gift['id_gift']
                );
            }
            Db::getInstance()->insert('gift_category', $insert);
        }

        die(Tools::jsonEncode(array(
            'hasError' => false,
            'message' => $this->module->l('Save successfully!', 'admingiftproductcontroller')
        )));
    }

    public function getIntArrayRequestVar($var)
    {
        $array = Tools::getValue($var);
        if (!is_array($array) || !count($array)) {
            return array();
        }

        foreach ($array as &$item) {
            $item = (int)$item;
        }
        return $array;
    }

    public function pSQLArray(&$array)
    {
        foreach ($array as $key => &$item) {
            if (empty($item)) {
                unset($array[$key]);
            }
            $item = pSQL($item);
        }
    }
}
