<?php
/**
* 2007-2014 PrestaShop
*
* Jms Advance Footer
*
*  @author    Joommasters <joommasters@gmail.com>
*  @copyright 2007-2014 Joommasters
*  @license   license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*  @Website: http://www.joommasters.com
*/

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('jmspagebuilder.php');

$context = Context::getContext();
$rows = array();
if (Tools::getValue('action') == 'updateHomesOrdering' && Tools::getValue('homepage')) {
    $homepage = Tools::getValue('homepage');

    foreach ($homepage as $position => $id_homepage) {
        $res = Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'jmspagebuilder_homepages` SET `ordering` = '.(int)$position.' WHERE `id_homepage` = '.(int)$id_homepage);
    }
    $jms_homepage = new JmsHomepage();
    $jms_homepage->clearCache();
}
if (Tools::getValue('action') == 'SaveLayout' && Tools::getValue('id_homepage')) {
    $id_homepage = Tools::getValue('id_homepage');
    $params = Tools::getValue('params');
    $params = Db::getInstance()->escape($params, true);
    $res = Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'jmspagebuilder_homepages` SET `params` = \''.$params.'\' WHERE `id_homepage` = '.(int)$id_homepage);
}
if ((Tools::getValue('action') == 'editModule' || Tools::getValue('action') == 'addModule') && Tools::getValue('modulename')) {
    $modulename = Tools::getValue('modulename');
    $hookselected = Tools::getValue('hook');
    $html = '<div class="form-group"><label>Module</label><input type="text" class="form-control addon-input addon-modulename" readonly data-attrname="modulename" value="'.$modulename.'"></div>';
    $html .= '<div class="form-group"><label>Hook</label><select class="form-control addon-input addon-hook" data-attrname="hook"><option value=""></option>';
    $hook_assign = array('home', 'top', 'footer', 'nav', 'rightcolumn', 'leftcolumn','topcolumn');
    $module_instance = ModuleCore::getInstanceByName($modulename);
    $hooks = array();
    $result = array();
    foreach ($hook_assign as $hook) {
        $retro_hook_name = Hook::getRetroHookName($hook);
        if ($hook == 'topcolumn') {
            $retro_hook_name = 'displayTopColumn';
        }
        if ($hook == 'nav') {
            $retro_hook_name = 'displayNav';
        }
        if (is_callable(array($module_instance, 'hook'.$hook)) || is_callable(array($module_instance, 'hook'.$retro_hook_name))) {
            $hooks[] = $retro_hook_name;
        }
    }
    if ($hooks) {
        $result = Db::getInstance()->ExecuteS('
            SELECT `id_hook`, `name`
            FROM `'._DB_PREFIX_.'hook`
            WHERE `name` IN (\''.implode("','", $hooks).'\')');
    }
    $len = count($result);
    if ($result && $len > 0) {
        for ($i = 0; $i < $len; $i++) {
            $html .= '<option value="'.$result[$i]['name'].'" ';
            if ($hookselected == $result[$i]['name']) {
                $html .= 'selected="selected"';
            }
            $html .= '>'.$result[$i]['name'].'</option>';
        }
    }
    $html .= '</select></div>';
    echo $html;
}
if ((Tools::getValue('action') == 'addAddon' || Tools::getValue('action') == 'editAddon') && Tools::getValue('type')) {
    $addontype = Tools::getValue('type');
    $addonfile = 'addon'.$addontype.'.php';
    $addonclass = 'JmsAddon'.Tools::ucfirst($addontype);
    if (file_exists(_PS_MODULE_DIR_.'jmspagebuilder/addons/'.$addonfile)) {
        require_once(_PS_MODULE_DIR_.'jmspagebuilder/addons/'.$addonfile);
    }
    $addon_instance = new $addonclass();
    $addon_instance->renderForm();
}
