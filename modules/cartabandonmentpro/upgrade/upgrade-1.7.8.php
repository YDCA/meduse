<?php
/**
* 2007-2015 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
if (!defined('_PS_VERSION_')) {
    exit;
}

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
/**
 * Function used to update your module from previous versions to the version 1.0.2,
 * Don't forget to create one file per version.
 */
function upgrade_module_1_7_8($module)
{
    /**
    * Do everything you want right there,
    * You could add a column in one of your module's tables
    */
    Configuration::updateValue('CARTAB_CRONJOB', 0);

    Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'cronjobs` WHERE description LIKE "%cartabandonment %"');

    unlink(_PS_MODULE_DIR_.'cartabandonmentpro/send_cron.php');
    unlink(_PS_MODULE_DIR_.'cartabandonmentpro/test.php');

    return $module;
}