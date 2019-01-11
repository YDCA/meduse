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

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/rcpganalytics.php');

// Make sure the request is POST, token exist and module is installed
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Tools::getValue('token') || !Module::isEnabled('rcpganalytics')) {
    // Pretend we're not here if the message is invalid
    http_response_code(404);
    die;
}

try {
    $rcpganalytics = new RcPgAnalytics();

    if (Tools::getValue('token') === $rcpganalytics->secret_key) {
        // get post values - no use of Tools::getValue because strip slashes and breaks JSON
        $data = (Tools::getIsset('data') ? Tools::jsonDecode($_POST['data'], true) : null);

        // check is array and is not empty
        if (is_array($data) && $data) {
            // execute ajax call with data
            $rcpganalytics->ajaxCall($data);
        } else {
            throw new Exception('Invalid data');
        }
    } else {
        throw new Exception('Invalid token');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo 'Error: ', $e->getMessage();
}
