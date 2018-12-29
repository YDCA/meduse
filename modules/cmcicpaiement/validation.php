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

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/cmcicpaiement.php');

header('Pragma: no-cache');
header('Content-type: text/plain');
ini_set('display_errors', 'off');

$module = new CMCICPaiement();
$module->includeConf();
$cmcic_error_behavior = (int)Configuration::get('CMCIC_ERROR_BEHAVIOR');
$cmcic_email_notification = Configuration::get('CMCIC_EMAIL_NOTIFICATION');

$data = getMethode();
$cmcic = new CmCicTpe();
$hmac = new CmCicHmac($cmcic);

$data_string = sprintf(CMCIC_CGI2_FIELDS,
	$cmcic->s_numero,
	$data['date'],
	$data['montant'],
    $data['reference'],
	$data['texte-libre'],
	$cmcic->s_version,
	$data['code-retour'],
	$data['cvx'],
	$data['vld'],
	$data['brand'],
	$data['status3ds'],
	$data['numauto'],
    (isset($data['motifrefus'])) ? $data['motifrefus'] : '',
    (isset($data['originecb'])) ? $data['originecb'] : '',
    (isset($data['bincb'])) ? $data['bincb'] : '',
    (isset($data['hpancb'])) ? $data['hpancb'] : '',
    (isset($data['ipclient'])) ? $data['ipclient'] : '',
	(isset($data['originetr'])) ? $data['originetr'] : '',
	(isset($data['veres'])) ? $data['veres'] : '',
	(isset($data['pares'])) ? $data['pares'] : ''
);

$order_status = false;
$order_message = '';
$data_reference = Tools::substr($data['reference'], 0, -2);
$reference = (int)$data_reference;

// Check if the transaction has already been handled
if ($module->isDuplicate($data_reference, $data['code-retour'])) {
    $module->logNotificationRequest($data_reference, $data['code-retour']);
    die();
}
// Log the notification request in DB
$module->logNotificationRequest($data_reference, $data['code-retour']);

if ($hmac->computeHmac($data_string) == Tools::strtolower($data['MAC']))
{
	switch ($data['code-retour'])
	{
		case 'Annulation' :
			$order_status = _PS_OS_ERROR_;
			foreach ($data as $key => $value)
				$order_message .= $key.': '.$value.'<br />';
			break;

		case 'payetest':
			$order_status = _PS_OS_PAYMENT_;
			$order_message = 'NOTICE: This is a test, nothing has really been paid';
			break;

		case 'paiement':
			$order_status = _PS_OS_PAYMENT_;
			foreach ($data as $key => $value)
				$order_message .= $key.': '.$value.'<br />';
			break;
	}
	$receipt = CMCIC_CGI2_MACOK;
}
else
	die();

$id_currency = (int)Currency::getIdByIsoCode(Tools::substr($data['montant'], -3));
$amount = ($order_status == _PS_OS_PAYMENT_ ? Tools::substr($data['montant'], 0, -3) : 0);

if (($order_status != _PS_OS_ERROR_ || $cmcic_error_behavior === 1 || $cmcic_error_behavior === 3) && !empty($data))
{
	if ($id_order = (int)Order::getOrderByCartId((int)$reference))
	{
		$order = new Order((int)$id_order);
		$order->total_paid_real = $amount;
		$order->update();

		$history = new OrderHistory();
		$history->id_order = (int)$id_order;
		$history->changeIdOrderState((int)$order_status, (int)$id_order);
		$history->addWithemail(true, array());

		$message = new Message();
		$message->message = $order_message;
		$message->id_order = (int)$order->id;
		$message->private = 1;
		$message->add();
	}
	elseif ((int)$reference && ($order_status === _PS_OS_PAYMENT_ ||
			($order_status === _PS_OS_ERROR_ && ($cmcic_error_behavior === 1 || $cmcic_error_behavior === 3))))
	{
		$cart = new Cart((int)$reference);
		$customer = new Customer((int)$cart->id_customer);
		$module->validateOrder((int)$reference, $order_status, $amount, $module->displayName,
			$order_message, null, $id_currency, true, $customer->secure_key);
	}
}
if ($order_status === _PS_OS_ERROR_)
{
	if ($cmcic_error_behavior === 2 || $cmcic_error_behavior === 3)
		$module->sendErrorEmail($order_message);
}

printf(CMCIC_CGI2_RECEIPT, $receipt);
