<?php
/**
* 2007-2017 PrestaShop
*
* Jms Page Builder
*
*  @author    Joommasters <joommasters@gmail.com>
*  @copyright 2007-2017 Joommasters
*  @license   license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*  @Website: http://www.joommasters.com
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
include_once(_PS_MODULE_DIR_.'jmspagebuilder/addons/addonbase.php');
include_once(_PS_MODULE_DIR_.'jmspagebuilder/classes/productHelper.php');

class JmsAddonMegatab extends JmsAddonBase
{
    public function __construct()
    {
        $this->addonname = 'megatab';
        $this->modulename = 'jmspagebuilder';
        $this->addontitle = 'Mega Tab Product';
        $this->addondesc = 'Show Product Carousel';
        $this->overwrite_tpl = '';
        $this->context = Context::getContext();
    }
    public function getInputs()
    {
        $inputs = array(
            array(
                'type' => 'text',
                'name' => 'title',
                'label' => $this->l('Title'),
                'lang' => '1',
                'desc' => 'Enter text which will be used as addon title. Leave blank if no title is needed.',
                'default' => 'Our Products'
            ),
            array(
                'type' => 'text',
                'name' => 'desc',
                'label' => $this->l('Description'),
                'lang' => '1',
                'desc' => 'Enter text which will be used as addon description. Leave blank if no description is needed.',
                'default' => 'Easy to find product following category'
            ),
            array(
                'type' => 'categories',
                'name' => 'ctcategories',
                'label' => $this->l('Categories'),
                'lang' => '0',
                'desc' => 'Select Categories to Show',
                'default' => '',
                'usecheckbox' => '1'
            ),
            array(
                'type' => 'select',
                'name' => 'producttype',
                'label' => $this->l('Product Type'),
                'lang' => '0',
                'desc' => 'Choose Product Type to Show',
                'options' => array('featured', 'new', 'onsale', 'topseller','special'),
                'default' => 'featured'
            ),
            array(
                'type' => 'select',
                'name' => 'order_by',
                'label' => $this->l('Order By'),
                'lang' => '0',
                'desc' => 'Order By Column',
                'options' => array('position', 'id_product', 'date_add', 'date_upd', 'name', 'manufacturer_name', 'price'),
                'default' => 'position'
            ),
            array(
                'type' => 'select',
                'name' => 'order_way',
                'label' => $this->l('Order Way'),
                'lang' => '0',
                'desc' => 'Order Way Or Order Direction',
                'options' => array('DESC','ASC'),
                'default' => 'DESC'
            ),
            array(
                'type' => 'text',
                'name' => 'items_total',
                'label' => $this->l('Total Items'),
                'lang' => '0',
                'desc' => 'Total Number Items',
                'default' => 40
            ),
            array(
                'type' => 'text',
                'name' => 'rows',
                'label' => $this->l('Number of Rows'),
                'lang' => '0',
                'desc' => 'Number of Rows (Or Number of Product per Column)',
                'default' => 2
            ),
            array(
                'type' => 'text',
                'name' => 'cols',
                'label' => $this->l('Number of Columns'),
                'lang' => '0',
                'desc' => 'Number of Columns (Or Number of Product per Row) ( > 1199px )',
                'default' => 4
            ),
            array(
                'type' => 'text',
                'name' => 'cols_md',
                'label' => $this->l('Number of Columns On Medium Device'),
                'lang' => '0',
                'desc' => 'Number of Columns (Or Number of Product per Row) On Medium Device ( > 991px )',
                'default' => 3
            ),
            array(
                'type' => 'text',
                'name' => 'cols_sm',
                'label' => $this->l('Number of Columns On Tablet'),
                'lang' => '0',
                'desc' => 'Number of Columns (Or Number of Product per Row) On Tablet( >= 768px )',
                'default' => 2
            ),
            array(
                'type' => 'text',
                'name' => 'cols_xs',
                'label' => $this->l('Number of Columns On Mobile'),
                'lang' => '0',
                'desc' => 'Number of Columns (Or Number of Product per Row) On Mobile( >= 320px )',
                'default' => 2
            ),
            array(
                'type' => 'switch',
                'name' => 'navigation',
                'label' => $this->l('Show Navigation'),
                'lang' => '0',
                'desc' => 'Enanble/Disable Navigation',
                'default' => '1'
            ),
            array(
                'type' => 'switch',
                'name' => 'pagination',
                'label' => $this->l('Show Pagination'),
                'lang' => '0',
                'desc' => 'Enanble/Disable Pagination',
                'default' => '0'
            ),
            array(
                'type' => 'switch',
                'name' => 'autoplay',
                'label' => $this->l('Auto Play'),
                'lang' => '0',
                'desc' => 'Enanble/Disable Auto Play',
                'default' => '0'
            ),
            array(
                'type' => 'switch',
                'name' => 'rewind',
                'label' => $this->l('ReWind Navigation'),
                'lang' => '0',
                'desc' => 'Enanble/Disable ReWind Navigation',
                'default' => '1'
            ),
            array(
                'type' => 'switch',
                'name' => 'slidebypage',
                'label' => $this->l('slide By Page'),
                'lang' => '0',
                'desc' => 'Enanble/Disable Slide By Page',
                'default' => '0'
            ),
            array(
                'type' => 'text',
                'name' => 'overwrite_tpl',
                'label' => $this->l('Overwrite Tpl File'),
                'lang' => '0',
                'desc' => 'Use When you want overwrite template file'
            )
        );
        return $inputs;
    }
    public function getFeaturedProduct($category_id, $total_config)
    {
        $category = new Category((int)$category_id, (int)Context::getContext()->language->id);
        $products = $category->getProducts((int)Context::getContext()->language->id, 1, $total_config, 'position');
        $result = array();
        foreach ($products as $product) {
            if ($product['id_category_default'] == $category_id) {
                $result[] =  $product;
            }
        }
        return $result;
    }
    public function getNewProduct($category_id, $total_config)
    {
        $newProducts = false;
        $newProducts = Product::getNewProducts((int) $this->context->language->id, 0, $total_config);
        $result = array();
        foreach ($newProducts as $product) {
            if ($product['id_category_default'] == $category_id) {
                $result[] =  $product;
            }
        }
        return $result;
    }
    public function getonSaleProducts($category_id, $total_config)
    {
        $id_lang = Context::getContext()->language->id;
        $sql = 'SELECT p.*, product_shop.*, pl.*,MAX(image_shop.`id_image`) id_image
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')
                LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.id_product = pl.id_product)
                LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
                WHERE  pl.`id_lang` = '.(int)$id_lang.' AND p.`on_sale` = 1 AND p.`active` = 1 AND cp.id_category ='.$category_id.' GROUP BY p.`id_product` LIMIT 0,'.$total_config;
        $products = Db::getInstance()->executeS($sql);
        $sale_product = Product::getProductsProperties((int)Context::getContext()->language->id, $products);
        $result = array();
        foreach ($sale_product as &$row) {
            if ($row['id_category_default'] == $category_id) {
                $result[] =  $row;
            }
        }
        return $result;
    }
    protected function getTopSellerProduct($category_id, $total_config)
    {
        if (Configuration::get('PS_CATALOG_MODE')) {
            return false;
        }
        if (!($products = ProductSale::getBestSalesLight((int)$this->context->cookie->id_lang, 0, (int)$total_config))) {
            return array();
        }
        $currency = new Currency($this->context->cookie->id_currency);
        $usetax = (Product::getTaxCalculationMethod((int)$this->context->customer->id) != PS_TAX_EXC);
        $result = array();
        foreach ($products as &$row) {
            $row['price'] = Tools::displayPrice(Product::getPriceStatic((int)$row['id_product'], $usetax), $currency);
            if ($row['id_category_default'] == $category_id) {
                $result[] =  $row;
            }
        }
        return $result;
    }
    public function getSpecialProduct($category_id, $total_config)
    {
        $product = Product::getPricesDrop($this->context->language->id, 0, $total_config);
        $result = array();
        foreach ($product as &$rows) {
            if ($rows['id_category_default'] == $category_id) {
                $result[] = $rows;
            }
        }
        return $result;
    }
    public function getProducts1($fields, $id_category)
    {
        $producttype = $fields[3]->value;
        $total_config = $fields[6]->value;
        $rows = $fields[7]->value;
        $cols = $fields[8]->value;
        $_products = array();
        if ($producttype == 'onsale') {
            $_products = $this->getonSaleProducts($id_category, $total_config);
        } elseif ($producttype == 'new') {
            $_products = $this->getNewProduct($id_category, $total_config);
        } elseif ($producttype == 'topseller') {
            $_products = $this->getTopSellerProduct($id_category, $total_config);
        } elseif ($producttype == 'special') {
            $_products = $this->getSpecialProduct($id_category, $total_config);
        } else {
            $_products = $this->getFeaturedProduct($id_category, $total_config);
        }
        return JmsProductHelper::sliceProducts($_products, $rows, $cols, $total_config);
    }
    public function returnValue($addon)
    {
        $id_lang = $this->context->language->id;
        if (Tools::strlen($addon->fields[2]->value) == 0) {
            return "Please select categories for tab!";
        }
        $category_ids = $addon->fields[2]->value;
        //$order_by = $addon->fields[4]->value;
        //$order_way = $addon->fields[5]->value;
        $category_ids = explode(",", $addon->fields[2]->value);
        $categories = array();
        $k = 0;
        foreach ($category_ids as $id_category) {
            $category = new Category($id_category, (int)Context::getContext()->language->id);
            $categories[$k]['id_category'] = $id_category;
            $categories[$k]['name'] = $category->name;
            $categories[$k]['products'] = $this->getProducts1($addon->fields, $id_category);
            $k++;
        }
        $addon_tpl_dir = $this->loadTplDir();
        $this->context->smarty->assign(
            array(
                'link' => $this->context->link,
                'categories' => $categories,
                'addon_title' => JmsPageBuilderHelper::decodeHTML($addon->fields[0]->value->$id_lang),
                'addon_desc' => JmsPageBuilderHelper::decodeHTML($addon->fields[1]->value->$id_lang),
                'cols'  => $addon->fields[8]->value,
                'cols_md'   => $addon->fields[9]->value,
                'cols_sm'   => $addon->fields[10]->value,
                'cols_xs'   => $addon->fields[11]->value,
                'navigation' => $addon->fields[12]->value,
                'pagination' => $addon->fields[13]->value,
                'autoplay' => $addon->fields[14]->value,
                'rewind' => $addon->fields[15]->value,
                'slidebypage' => $addon->fields[16]->value,
                'addon_tpl_dir' => $addon_tpl_dir
            )
        );
        $this->overwrite_tpl = $addon->fields[count($addon->fields)-1]->value;
        $template_path = $this->loadTplPath();
        return $this->context->smarty->fetch($template_path);
    }
}
