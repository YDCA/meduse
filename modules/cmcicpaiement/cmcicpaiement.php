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

class CMCICPaiement extends PaymentModule
{
	protected $js_path = null;
	protected $css_path = null;

	protected $server = array(
        'https://paiement.creditmutuel.fr/test/',
        'https://ssl.paiement.cic-banques.fr/test/',
        'https://ssl.paiement.banque-obc.fr/test/',
		'https://p.monetico-services.com/test/'
    );
    protected $server_index;
	protected $cmcic_board_link = 'https://www.cmcicpaiement.fr/fr/identification/identification.html';
	protected $url_validation = null;
	protected $website = null;
	public $new_conf_names = array(
		'CMCIC_SERVER',
		'CMCIC_KEY',
		'CMCIC_COMPANY_CODE',
		'',
		'CMCIC_ERROR_BEHAVIOR',
		'CMCIC_EMAIL_NOTIFICATION',
		'CMCIC_TPE',
		'CMCIC_URLOK',
		'CMCIC_URLKO'
	);
	public $old_conf_names = array(
		'CMCIC_SERVEUR',
		'CMCIC_CLE',
		'CMCIC_CODESOCIETE',
		'CMCIC_ERROR',
		'CMCIC_WHENERR',
		'CMCIC_EMAILERR'
	);
	protected $logo_conf_list = array(
		'CMCIC_LOGO_HOME',
		'CMCIC_LOGO_RIGHT_COLUMN',
		'CMCIC_LOGO_LEFT_COLUMN'
	);

	protected static $lang_cache;

	private $html;

	public function __construct()
	{
		$this->name = 'cmcicpaiement';
		$this->version = '1.4.7';

		$this->page = basename(__FILE__, '.php');
		$this->bootstrap = true;
		$this->author = 'PrestaShop';
		$this->module_key = 'f36c83d7812c3ebcd0de45ea33d32699';
		$this->author_address = '0x64aa3c1e4034d07015f639b0e171b0d7b27d01aa';

		$this->tab = 'payments_gateways';

		parent::__construct();

		$this->js_path = $this->_path.'views/js/';
		$this->css_path = $this->_path.'views/css/';

		if (version_compare(_PS_VERSION_, '1.6', '<'))
			$this->getLang();

		$this->displayName = $this->l('CM-CIC P@iement');
		$this->description = '';

		$shop = new Shop((int)$this->context->shop->id);

		if (defined('_PS_HOST_MODE') || getenv('_PS_HOST_MODE_') == true)
			$this->website = $shop->getBaseURL();
		else if (Tools::usingSecureMode())
			$this->website = str_replace('http://', 'https://', $shop->getBaseURL());
		else
			$this->website = $shop->getBaseURL();


		$server = Configuration::get('CMCIC_SERVER');
		for ($i = 0; isset($this->server[$i]); $i++) {
			if (strstr($this->server[$i], $server))
				$this->server_index = $i;
		}

		$this->url_validation = $this->website.'modules/'.$this->name.'/validation.php';
	}

	public function install()
	{
		if (!Configuration::get('CMCIC_ENVIRONMENT'))
			Configuration::updateValue('CMCIC_SERVER', $this->server[$this->server_index]);

		if (!Configuration::get('CMCIC_EXPRESS'))
			Configuration::updateValue('CMCIC_EXPRESS', 0);

		if (!Configuration::get('CMCIC_URLOK'))
		{
			if (version_compare(_PS_VERSION_, '1.5', '>'))
				Configuration::updateValue('CMCIC_URLOK', $this->website.'index.php?controller=order-confirmation');
			else
				Configuration::updateValue('CMCIC_URLOK', $this->website.'order-confirmation.php');
		}

		if (!Configuration::get('CMCIC_URLKO'))
		{
			if (version_compare(_PS_VERSION_, '1.5', '>'))
				Configuration::updateValue('CMCIC_URLKO', $this->website.'index.php?controller=order');
			else
				Configuration::updateValue('CMCIC_URLKO', $this->website.'order.php');
		}
        $sql = 'CREATE TABLE IF NOT EXISTS `'. _DB_PREFIX_.'cmcic_notification_event` ('.
            '`event_id` INT NOT NULL AUTO_INCREMENT,'.
            '`cart_reference` INT DEFAULT NULL,'.
            '`code-retour` VARCHAR(15) DEFAULT NULL,'.
            '`created_at` DATETIME DEFAULT NULL,'.
            'PRIMARY KEY (`event_id`)'.
            ') DEFAULT CHARSET=utf8;';

        return (
            parent::install() &&
            $this->registerHook('payment') &&
            $this->registerHook('orderConfirmation') &&
            Db::getInstance()->Execute($sql)
        );
	}

	public function checkUrl()
	{
		if (version_compare(_PS_VERSION_, '1.5', '>'))
		{
			if (Configuration::get('CMCIC_URLOK') != $this->website.'index.php?controller=order-confirmation')
				Configuration::updateValue('CMCIC_URLOK', $this->website.'index.php?controller=order-confirmation');

			if (Configuration::get('CMCIC_URLKO') != $this->website.'index.php?controller=order')
				Configuration::updateValue('CMCIC_URLKO', $this->website.'index.php?controller=order');
		}
	}

	public function postProcess()
	{
		$return = array(
			'code' => 0,
			'error' => 0,
			'step_name' => ''
		);

		// Step 1
		if (Tools::isSubmit('submitBankInformations'))
		{
			$return['step_name'] = 'Bank informations';

			if (!preg_match('#^[a-zA-Z0-9]{40}$#', trim(Tools::getValue('CMCIC_KEY')))
			|| !preg_match('#^[0-9]{7}$#', trim(Tools::getValue('CMCIC_TPE')))
			|| !preg_match('#^[a-zA-Z0-9_-]+$#', trim(Tools::getValue('CMCIC_COMPANY_CODE'))))
				$return['error'] = -1;

            $this->server_index = (int)Tools::getValue('CMCIC_SERVER');
			Configuration::updateValue('CMCIC_KEY', trim(Tools::getValue('CMCIC_KEY')));
			Configuration::updateValue('CMCIC_TPE', trim(Tools::getValue('CMCIC_TPE')));
			Configuration::updateValue('CMCIC_COMPANY_CODE', trim(Tools::getValue('CMCIC_COMPANY_CODE')));
			Configuration::updateValue('CMCIC_ENVIRONMENT', (int)Tools::getValue('CMCIC_ENVIRONMENT'));
			Configuration::updateValue('CMCIC_EXPRESS', (int)Tools::getValue('CMCIC_EXPRESS'));
			Configuration::updateValue('CMCIC_SERVER', ((int)Tools::getValue('CMCIC_ENVIRONMENT') === 1) ?
				str_replace('test/', '', $this->server[$this->server_index]) : $this->server[$this->server_index]);
			$return['code'] = 1;
			return $return;
		}

		// Step 3
		if (Tools::isSubmit('submitCMCICOptions'))
		{
			$email_list = trim(Tools::getValue('CMCIC_EMAIL_NOTIFICATION'));
			$return['step_name'] = 'CM-CIC options';

			if (!empty($email_list))
			{
				if (Tools::substr($email_list, -1) == ',')
					$email_list = Tools::substr($email_list, 0, -1);

				$email_list = str_replace(' ', '', $email_list);
				$email_array = explode(',', $email_list);
				foreach ($email_array as $email)
					if (!Validate::isEmail($email))
						$return['error'] = -3;
				if ($return['error'] != -3)
					Configuration::updateValue('CMCIC_EMAIL_NOTIFICATION', $email_list);
			}
			Configuration::updateValue('CMCIC_ERROR_BEHAVIOR', (int)Tools::getValue('CMCIC_ERROR_BEHAVIOR'));

			foreach ($this->logo_conf_list as $logo_label)
				Configuration::updateValue($logo_label, (int)Tools::getValue($logo_label));

			$return['code'] = 3;
			return $return;
		}
		return $return;
	}

	public function getContent()
	{
		$return_post_process = $this->postProcess();
		$is_submit = $return_post_process['code'];
		$this->checkUrl();
		$this->loadAsset();

		$this->autoHookLogo();

		$shop_enable = (int)Configuration::get('PS_SHOP_ENABLE');

		/* Language for documentation in back-office */
		$lang = 'FR';

		include_once('classes/APIFAQClass.php');
		$api = new APIFAQ();
		$api_json = Tools::jsonDecode($api->getData($this));

		$this->context->smarty->assign(array(
			'is_submit'=> $is_submit,
			'form_uri' => $_SERVER['REQUEST_URI'],
			'module_active' => (int)$this->active,

			'apifaq' => $api_json->categories,

			'key' => pSQL(Configuration::get('CMCIC_KEY')),
			'tpe' => Configuration::get('CMCIC_TPE'),
			'company_code' => pSQL(Configuration::get('CMCIC_COMPANY_CODE')),
			'server' => (int)$this->server_index,
			'environment' => (int)Configuration::get('CMCIC_ENVIRONMENT'),
			'express_option' => (int)Configuration::get('CMCIC_EXPRESS'),
			'url_ok' => pSQL(Configuration::get('CMCIC_URLOK')),
			'url_ko' => pSQL(Configuration::get('CMCIC_URLKO')),
			'url_validation' => pSQL($this->url_validation),
			'behavior' => (int)Configuration::get('CMCIC_ERROR_BEHAVIOR'),
			'notification' => pSQL(Configuration::get('CMCIC_EMAIL_NOTIFICATION')),

			'html'=> $this->html,
			'shop_enable'=> $shop_enable,
			'module_name' => $this->name,
			'module_version' => $this->version,
			'cmcic_board_link' => $this->cmcic_board_link,
			'lang_select' => self::$lang_cache,
			'module_display' => $this->displayName,
			'debug_mode' => (int)_PS_MODE_DEV_,
			'multishop' => (int)Shop::isFeatureActive(),
			'ps_version' => (bool)version_compare(_PS_VERSION_, '1.6', '>'),
			'guide_link' => 'docs/CM-CIC_documentation_utilisateur_'.$lang.'.pdf',

			'tracking_url' => '?utm_source=back-office&utm_medium=module&utm_campaign=back-office-'.$lang.'&utm_content='.$this->name,
			'tracking_url_install' => '?utm_source=modulePS&utm_medium=installation&utm_campaign=cmcic',

			'error' => (int)$return_post_process['error'],
			'step_name' => pSQL($return_post_process['step_name']),

			'logo_home' => (int)Configuration::get('CMCIC_LOGO_HOME'),
			'logo_left_column' => (int)Configuration::get('CMCIC_LOGO_LEFT_COLUMN'),
			'logo_right_column' => (int)Configuration::get('CMCIC_LOGO_RIGHT_COLUMN')
		));
		return $this->display(__FILE__, 'views/templates/admin/configuration.tpl');
	}

	public function includeConf()
	{
		if (empty($this->context->cart))
		{
			$cart = new Cart((int)$this->context->cookie->id_cart);
			Context::getContext()->cart = $cart;
		}
		define('CMCIC_VERSION', '3.0');
		foreach ($this->new_conf_names as $var)
		{
			if (!empty($var))
			{
				if (!$$var = Configuration::get($var))
					continue;
				if ($var == 'CMCIC_URLOK')
					$$var .= '&id_cart='.(int)$this->context->cart->id.'&id_module='.(int)$this->id.'&key='.$this->context->customer->secure_key;
				define($var, $$var);
			}
		}
		unset($var);
		require_once(dirname(__FILE__).'/CmCicTpe.inc.php');
		return true;
	}

	public function hookOrderConfirmation($params)
	{
		if ($params['objOrder']->module != $this->name)
			return;

		if ($params['objOrder']->valid || $params['objOrder']->current_state == (int)_PS_OS_PAYMENT_)
			$this->context->smarty->assign(array('status' => 'ok', 'id_order' => $params['objOrder']->id, 'shop_name'=> Configuration::get('PS_SHOP_NAME')));
		else
			$this->context->smarty->assign('status', 'failed');
		return $this->display(__FILE__, 'views/templates/hook/hookorderconfirmation.tpl');
	}

	public function hookDisplayHome()
	{
		$this->context->smarty->assign('img_url', _MODULE_DIR_.$this->name.'/views/img/logo_home_small.png');
		return $this->display(__FILE__, 'views/templates/hook/hookhome.tpl');
	}

	public function hookDisplayRightColumn()
	{
		$this->context->smarty->assign('img_url', _MODULE_DIR_.$this->name.'/views/img/logo_column.png');
		return $this->display(__FILE__, 'views/templates/hook/hookcolumn.tpl');
	}

	public function hookDisplayLeftColumn()
	{
		$this->context->smarty->assign('img_url', _MODULE_DIR_.$this->name.'/views/img/logo_column.png');
		return $this->display(__FILE__, 'views/templates/hook/hookcolumn.tpl');
	}

	private function getLang()
	{
		if (self::$lang_cache == null && !is_array(self::$lang_cache))
		{
			self::$lang_cache = array();
			if ($languages = Language::getLanguages())
			{
				foreach ($languages as $row)
				{
					$exprow = explode(' (', $row['name']);
					$subtitle = (isset($exprow[1]) ? trim(Tools::substr($exprow[1], 0, -1)) : '');
					self::$lang_cache[$row['iso_code']] = array (
						'title' => trim($exprow[0]),
						'subtitle' => $subtitle
					);
				}
				/* Clean memory */
				unset($row, $exprow, $subtitle, $languages);
			}
		}
	}

	/**
	 * @return bool
	 */
	public function hookPayment()
	{
		$language = $this->context->language;
		$currency = $this->context->currency;
		$customer = $this->context->customer;
		$cart = $this->context->cart;

		if (!$this->includeConf())
			return false;

		// CMCIC server only understands "EN" for english language
		if (Tools::strtoupper($language->iso_code) == "GB") {
			$cmcic = new CmCicTpe("EN");
		} else {
			$cmcic = new CmCicTpe(Tools::strtoupper($language->iso_code));
		}

		$hmac = new CmCicHmac($cmcic);
		$cmcic_date = date('d/m/Y:H:i:s');
		$cmcic_amount = $cart->getOrderTotal();
		$cmcic_alias = '';

		$cmcic_currency = Tools::strtoupper($currency->iso_code);
		$cmcic_reference = $cart->id.rand(10, 99);
		$cmcic_email = $customer->email;
		$cmcic_textelibre = '['.$customer->id.'] '.$customer->email;

		if ((int)Configuration::get('CMCIC_EXPRESS') == 1)
		{
			$cmcic_alias = $customer->id;
		}

		$this->context->smarty->assign('cmcic', $cmcic);
		$this->context->smarty->assign('cmcic_date', $cmcic_date);
		$this->context->smarty->assign('cmcic_montant', $cmcic_amount.$cmcic_currency);
		$this->context->smarty->assign('cmcic_reference', $cmcic_reference);
		$this->context->smarty->assign('cmcic_textelibre', $cmcic_textelibre);
		$this->context->smarty->assign('cmcic_email', $cmcic_email);

		$this->context->smarty->assign('express_option', (int)Configuration::get('CMCIC_EXPRESS'));
		$this->context->smarty->assign('cmcic_alias', $cmcic_alias);

		$hmac_plain = sprintf(CMCIC_CGI1_FIELDS,
			$cmcic->s_numero,
			$cmcic_date,
			$cmcic_amount,
			$cmcic_currency,
			$cmcic_reference,
			$cmcic_textelibre,
			$cmcic->s_version,
			$cmcic->s_langue,
			$cmcic->s_code_societe,
			$cmcic_email,
			$cmcic_alias,
			'', '', '', '', '', '', '', '', ''
		);

		$hmac_cipher = $hmac->computeHmac($hmac_plain);
		$this->context->smarty->assign('hmac', $hmac_cipher);
		$this->context->smarty->assign('cmcicpaiement_form', 'cmcicpaiement_form1');
		$this->context->smarty->assign('cmcic_picture', 'views/img/cmcicpaiement_paiement.png');
		$this->context->smarty->assign('cmcic_text', $this->l('Pay by credit card with CM-CIC paiement'));
		return $this->display(__FILE__, 'views/templates/hook/hookpayment.tpl');
	}

	/**
	 * Loads asset resources
	 */
	public function loadAsset()
	{
		$css_compatibility = $js_compatibility = array();

		// Load CSS
		$css = array(
			$this->css_path.'font-awesome.min.css',
			$this->css_path.'bootstrap-select.min.css',
			$this->css_path.'bootstrap-responsive.min.css',
			$this->css_path.'faq.css',
			$this->css_path.$this->name.'.css',
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			$css_compatibility = array(
				$this->css_path.'bootstrap.min.css',
				$this->css_path.'bootstrap.extend.css',
				$this->css_path.'font-awesome.min.css',
			);
			$css = array_merge($css_compatibility, $css);
		}

		$this->context->controller->addCSS($css, 'all');

		// Load JS
		$jss = array(
			$this->js_path.'bootstrap-select.min.js',
			$this->js_path.'faq.js',
			$this->js_path.$this->name.'.js'
		);

		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			$js_compatibility = array(
				$this->js_path.'bootstrap.min.js'
			);
			$jss = array_merge($jss, $js_compatibility);
		}
		$this->context->controller->addJS($jss);

		// Clean memory
		unset($jss, $css, $js_compatibility, $css_compatibility);
	}

	/**
	 * @param $order_message
	 */
	public function sendErrorEmail($order_message)
	{
		$cmcic_email_notification = Configuration::get('CMCIC_EMAIL_NOTIFICATION');

		$email_array = explode(',', $cmcic_email_notification);
		foreach ($email_array as $email)
		{
			if (Validate::isEmail($email))
			{
				Mail::Send(Configuration::get('PS_LANG_DEFAULT'), 'notification',
					$this->l('CM-CIC notification'),
					array('message' => 'CM-CIC payment error'. str_ireplace('', "n", $order_message)),
					$email,
					null, null, null, null, null,
					dirname(__FILE__).'/mails/');
			}
		}
	}


	public function autoHookLogo()
	{
		foreach ($this->logo_conf_list as $logo_label)
		{
			$label = str_replace('cmcic_logo_', '', Tools::strtolower($logo_label));

			if (strpos($label, '_'))
			{
				$expl = explode('_', $label);
				if (isset($expl[0]) && isset($expl[1]))
					$label = $expl[0].Tools::ucfirst($expl[1]);
			}

			$hook = 'display'.Tools::ucfirst($label);
			if ((int)Configuration::get($logo_label) === 1)
				$this->registerHook($hook);
			else
				$this->unregisterHook($hook);
		}
	}

    public function logNotificationRequest($cart_reference, $codeRetour)
    {
        Db::getInstance()->insert('cmcic_notification_event', array(
            'cart_reference' => pSQL($cart_reference),
            'code-retour'    => pSQL($codeRetour),
            'created_at'     => date('Y-m-d H:i:s')
        ));
    }

    public function isDuplicate($cart_reference, $codeRetour)
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'cmcic_notification_event`'.
            'WHERE `cart_reference`='.pSQL($cart_reference).'AND code-retour ='.pSQL($codeRetour);

        if (Db::getInstance()->getRow($sql)) {
            return true;
        } else {
            return false;
        }
    }
}
