<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a trade license awared by
 * Garamo Online L.T.D.
 *
 * Any use, reproduction, modification or distribution
 * of this source file without the written consent of
 * Garamo Online L.T.D It Is prohibited.
 *
 *  @author    ReactionCode <info@reactioncode.com>
 *  @copyright 2015-2018 Garamo Online L.T.D
 *  @license   Commercial license
 */

$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'rc_pganalytics_orders_sent`';
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'rc_pganalytics_client_id`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
