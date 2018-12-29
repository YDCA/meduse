<?php
/**
 * 2007-2015 PrestaShop
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2014 PrestaShop SA
 * @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
 * International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_'))
	exit;

/**
 * @param $module
 * @return bool
 * Get the old value & delete configuration name
 */
function upgrade_module_1_4_2($module)
{
    $sql = 'CREATE TABLE IF NOT EXISTS `'. _DB_PREFIX_.'cmcic_notification_event` ('.
        '`event_id` INT NOT NULL AUTO_INCREMENT,'.
        '`cart_reference` INT DEFAULT NULL,'.
        '`code-retour` VARCHAR(15) DEFAULT NULL,'.
        '`created_at` DATETIME DEFAULT NULL,'.
        'PRIMARY KEY (`event_id`)'.
        ') DEFAULT CHARSET=utf8;';

    return (Db::getInstance()->Execute($sql));
}
