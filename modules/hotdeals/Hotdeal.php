<?php
/**
* 2007-2017 PrestaShop
*
* Jms Hot Deals
*
*  @author    Joommasters <joommasters@gmail.com>
*  @copyright 2007-2017 Joommasters
*  @license   license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*  @Website: http://www.joommasters.com
*/

class Hotdeal extends ObjectModel
{

    public $id_product;
    public $deals_time;
    public $ordering;
    public $active;

    public static $definition = array(
            'table'     => 'hotdeals_items',
            'primary'   => 'id_hotdeals',
            'fields' => array(
                 'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
                 'deals_time' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'copy_post' => false),
                 'active'   =>  array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
                 'ordering' =>  array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true)
            )
        );

    public function __construct($id_hotdeals = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id_hotdeals, $id_lang, $id_shop);
    }


    public function add($autodate = true, $null_values = false)
    {
        $res = true;
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $res = parent::add($autodate, $null_values);
        $sql = '
            INSERT INTO `'._DB_PREFIX_.'hotdeals` (`id_hotdeals`,`id_shop` )
            VALUES('.(int)$this->id.','.(int)$id_shop.')';
        $res &= Db::getInstance()->execute($sql);
        return $res;
    }


    public function delete()
    {
        $res = true;
        $res &= $this->reOrderPositions();
        $sql = '
            DELETE FROM `'._DB_PREFIX_.'hotdeals`
            WHERE `id_hotdeals` = '.(int)$this->id;
        $res &= Db::getInstance()->execute($sql);
        $res &= parent::delete();
        return $res;
    }

    public function reOrderPositions()
    {
        $id_hotdeals = $this->id;
        $context = Context::getContext();
        $id_shop = $context->shop->id;

        $sql = '
            SELECT MAX(hss.`ordering`) as ordering
            FROM `'._DB_PREFIX_.'hotdeals_items` hss, `'._DB_PREFIX_.'hotdeals` hs
            WHERE hss.`id_hotdeals` = hs.`id_hotdeals` AND hs.`id_shop` = '.(int)$id_shop;
        $max = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if ((int)$max == (int)$id_hotdeals) {
            return true;
        }
        $sql = '
            SELECT hss.`ordering` as ordering, hss.`id_hotdeals` as id_hotdeals
            FROM `'._DB_PREFIX_.'hotdeals_items` hss
            LEFT JOIN `'._DB_PREFIX_.'hotdeals` hs ON (hss.`id_hotdeals` = hs.`id_hotdeals`)
            WHERE hs.`id_shop` = '.(int)$id_shop.' AND hss.`ordering` > '.(int)$this->ordering;
        $rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        foreach ($rows as $row) {
            $current_slide = new Hotdeal($row['id_hotdeals']);
            --$current_slide->position;
            $current_slide->update();
            unset($current_slide);
        }
        return true;
    }
}
