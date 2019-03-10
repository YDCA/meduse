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

function upgrade_module_3_3_0()
{
    $success = true;

    $upgrade_default_values = array(
        'RC_PGANALYTICS_EVENT_ST' => '0',
        'RC_PGANALYTICS_EVENT_PC' => '0',
        'RC_PGANALYTICS_EVENT_AC' => '0',
        'RC_PGANALYTICS_EVENT_RC' => '0',
        'RC_PGANALYTICS_EVENT_CHO' => '0'
    );

    $upgrade_sql = 'ALTER TABLE `'._DB_PREFIX_.'rc_pganalytics_orders_sent`
    MODIFY `id_order` INT(10) UNSIGNED NOT NULL,
    MODIFY `id_shop` INT(10) UNSIGNED NOT NULL,
    ADD `sent_from` VARCHAR(2) NOT NULL,
    ADD `sent_at` DATETIME NOT NULL';

    foreach ($upgrade_default_values as $key => $value) {
        // Set default Products Rate
        if (!Configuration::updateGlobalValue($key, $value)) {
            $success = false;
        }
    }

    if (!Db::getInstance()->execute($upgrade_sql)) {
        $success = false;
    }
    return $success;
}
