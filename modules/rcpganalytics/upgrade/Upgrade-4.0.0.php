<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a trade license awarded by
 * Garamo Online L.T.D.
 *
 * Any use, reproduction, modification or distribution
 * of this source file without the written consent of
 * Garamo Online L.T.D It Is prohibited.
 *
 *  @author    Reaction Code <info@reactioncode.com>
 *  @copyright 2015-2018 Garamo Online L.T.D
 *  @license   Commercial license
 */

function upgrade_module_4_0_0($module)
{
    $success = true;

    $upgrade_default_values = array(
        'RC_PGANALYTICS_SSSR' => 1,
        'RC_PGANALYTICS_D4' => 4,
        'RC_PGANALYTICS_OPT_ID' => '',
        'RC_PGANALYTICS_OPT_HCN' => 'optimize-loading',
        'RC_PGANALYTICS_OPT_HTO' => 4000
    );

    $remove_old_values = array(
        'RC_PGANALYTICS_EVENT_ST',
        'RC_PGANALYTICS_EVENT_PC',
        'RC_PGANALYTICS_EVENT_AC',
        'RC_PGANALYTICS_EVENT_RC',
        'RC_PGANALYTICS_EVENT_CHO',
    );

    $remove_hook = 'productFooter';

    // create new tables
    $upgrade_sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rc_pganalytics_client_id` (
        `id_customer` INT(10) UNSIGNED NOT NULL,
        `id_shop` INT(10) UNSIGNED NOT NULL,
        `client_id` VARCHAR(50) NOT NULL,
        PRIMARY KEY  (`id_customer`, `id_shop`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;'
    ;

    // set default values from new vars
    foreach ($upgrade_default_values as $key => $value) {
        // Set default Products Rate
        if (!Configuration::updateGlobalValue($key, $value)) {
            $success = false;
        }
    }

    // remove old values from db
    foreach ($remove_old_values as $old_value) {
        Configuration::deleteByName($old_value);
    }

    $module->unregisterHook($remove_hook);

    Media::clearCache();

    if (!Db::getInstance()->execute($upgrade_sql)) {
        $success = false;
    }
    return $success;
}
