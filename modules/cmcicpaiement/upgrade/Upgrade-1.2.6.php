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
function upgrade_module_1_2_6($module)
{
	$count = count($module->old_conf_names);
	for ($i = 0; $i < $count; $i++)
		if (!empty($module->new_conf_names[$i]))
			if (!Configuration::updateValue($module->new_conf_names[$i], (Configuration::get($module->old_conf_names[$i]))))
				return false;
	return true;
}