<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from CREATYM
 * Use, copy, modification or distribution of this source file without written
 * license agreement from CREATYM is strictly forbidden.
 * In order to obtain a license, please contact us: info@creatym.fr
 * ...........................................................................
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * concedee par la societe CREATYM
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de la part de CREATYM est
 * expressement interdite.
 * Pour obtenir une licence, veuillez contacter CREATYM a l'adresse: info@creatym.fr
 * ...........................................................................
 *
 * @author    Benjamin L.
 * @copyright 2017 Créatym <http://modules.creatym.fr>
 * @license   Commercial license
 * Support by mail  :  info@creatym.fr
 * Support on forum :  advanceddateofdelivery
 * Phone : +33.87230110
 */

if (!defined('_PS_VERSION_'))
	exit;

class AdvancedDateOfDelivery extends Module
{
	const INSTALL_SQL_FILE = 'install.sql';

	private $html = '';
	private $post_errors = array();
	private $filters = array();

	private $base_url;

	private $config = array(
		'ADOD_DISPLAY_ON_PRODUCT_PAGE' => false,
		'ADOD_DISPLAY_CARRIER_PRICE' => false,
		'ADOD_PRODUCT_POSITION' => 'PRODUCT_ACTIONS',
		'ADOD_PRODUCT_DISPLAY' => 'PRODUCT_FOOTER',
		'ADOD_DISPLAY_ON_CARRIERS_LIST' => false,
		'ADOD_DISPLAY_ON_PDF_INVOICE' => false,
		'ADOD_DISPLAY_ON_PDF_DELIVERYSLIP' => false,
		'ADOD_DISPLAY_ON_ORDER_HISTORY' => false,
		'ADOD_SHOP_PROCESSING_INSTOCK' => 0,
		'ADOD_SHOP_PROCESSING_OUTSTOCK' => 2,
		'ADOD_EXTRA_SHOP_PROCESSING' => 0,
		'ADOD_FORCE_STOCK' => false,
		'ADOD_HOLIDAYS' => '1_1,12_25',
		'ADOD_CLOSING_DAYS' => '0,6',
		'ADOD_DATE_FORMAT' => 'l j F Y',
		'ADOD_ENABLE_FOR_VIRTUAL' => false,
		'ADOD_PRODUCT_PAGE_TXT' => null,
		'ADOD_ALLOW_ASAP_OPTION' => false,
		'ADOD_ALLOW_ASAP_OPTION_DETAIL' => false
	);

	public function __construct()
	{
		$this->name = 'advanceddateofdelivery';
		$this->tab = 'shipping_logistics';
		$this->version = '3.0.29';
		$this->author = 'Créatym';
		$this->need_instance = 0;
		$this->ps_version_compliancy 	= array('min' => '1.6');
		$this->bootstrap = true;
		$this->module_key = '35ffa5168ec3f50f27c3258b763d2dde';
		$this->author_address = '0xC48016fa3CF9E25265B09DEdb791516DDF26fF4a';

		parent::__construct();

		$this->secure_key = Tools::encrypt($this->name);

		$this->displayName = $this->l('Advanced date of delivery');
		$this->description = $this->l('Displays an approximate date of delivery more precise');

		$this->css_path = $this->_path.'views/css/';
		$this->js_path = $this->_path.'views/js/';
		
		$this->module_url = __PS_BASE_URI__ . basename(_PS_MODULE_DIR_) . '/' . $this->name . '/' ;
		$this->getTemplatePath = $this->module_url . '/views/templates/front/';
	}

	public function install()
	{
		foreach ($this->config as $key => $value)
			if (!Configuration::updateValue($key, $value))
				return false;

		if (!parent::install()
			|| !$this->registerHook('header')
			|| !$this->registerHook('backOfficeHeader')
			|| !$this->registerHook('beforeCarrier')
			|| !$this->registerHook('orderDetailDisplayed')
			|| !$this->registerHook('actionCarrierUpdate')
			|| !$this->registerHook('displayPDFInvoice')
			|| !$this->registerHook('displayPDFDeliverySlip')
			|| !$this->registerHook('productActions')
			|| !$this->registerHook('displayProductListFunctionalButtons')
			|| !$this->registerHook('displayLeftColumnProduct')
			|| !$this->registerHook('displayRightColumnProduct')
			|| !$this->registerHook('productTab')
			|| !$this->registerHook('productTabContent')
			|| !$this->registerHook('displayFooterProduct')
			|| !$this->registerHook('displayAdminOrder')
			|| !$this->registerHook('displayAdminProductsExtra')
			|| !$this->registerHook('extraLeft')
			|| !$this->registerHook('displayReassurance')
			|| !$this->initConfigurationValues()
			|| !$this->executeSQLFile('install.sql'))
			return false;

		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall()
			|| !$this->unregisterHook('header')
			|| !$this->unregisterHook('backOfficeHeader')
			|| !$this->unregisterHook('beforeCarrier')
			|| !$this->unregisterHook('orderDetailDisplayed')
			|| !$this->unregisterHook('actionCarrierUpdate')
			|| !$this->unregisterHook('displayPDFInvoice')
			|| !$this->unregisterHook('displayPDFDeliverySlip')
			|| !$this->unregisterHook('productActions')
			|| !$this->unregisterHook('displayProductListFunctionalButtons')
			|| !$this->unregisterHook('displayLeftColumnProduct')
			|| !$this->unregisterHook('displayRightColumnProduct')
			|| !$this->unregisterHook('productTab')
			|| !$this->unregisterHook('productTabContent')
			|| !$this->unregisterHook('displayFooterProduct')
			|| !$this->unregisterHook('displayAdminOrder')
			|| !$this->unregisterHook('displayAdminProductsExtra')
			|| !$this->unregisterHook('extraLeft')
			|| !$this->unregisterHook('displayReassurance')
			|| !$this->executeSQLFile('uninstall.sql'))
			return false;

		foreach (array_keys($this->config) as $key)
			if (!Configuration::deleteByName($key))
				return false;

		return true;
	}

	protected function initConfigurationValues()
    {
		$values = array();

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang)
        {
            $values['ADOD_DATE_FORMAT'][(int)$lang['id_lang']] = 'l j F Y';
            Configuration::updateValue('ADOD_DATE_FORMAT', $values['ADOD_DATE_FORMAT']);
        }

        return true;
    }
	
	public function executeSQLFile($file)
	{
		$path = realpath(_PS_MODULE_DIR_.$this->name).DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR; // @since 1.3.3.0

		if (!file_exists($path.$file))
			$path = realpath(_PS_MODULE_DIR_.$this->name).DIRECTORY_SEPARATOR;

		if (!file_exists($path.$file))
		{
			$this->post_errors[] = 'File not found : '.$path.$file;
			$this->_errors[] = 'File not found : '.$path.$file;
			return false;
		}

		if (!($sql = Tools::file_get_contents($path.$file)))
		{
			$this->post_errors[] = 'File empty : '.$path.$file;
			$this->_errors[] = 'File empty : '.$path.$file;
			return false;
		}

		$sql = preg_split("/;\s*[\r\n]+/", str_replace('PREFIX_', _DB_PREFIX_, $sql));
		$db = Db::getInstance();

		foreach ($sql as $query)
		{
			$query = trim($query);

			if ($query)
			{
				if (!$db->Execute($query))
				{
					$this->post_errors[] = $db->getMsgError().' '.$query;
					$this->_errors[] = $db->getMsgError().' '.$query;
					return false;
				}
			}
		}
		return true;
	}

	/**
	* Loads asset resources
	*/
	public function loadAsset()
	{
		$css_compatibility = $js_compatibility = array();

		// Load CSS
		$css = array(
			$this->css_path.'admin.css',
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			$css_compatibility = array(
				$this->css_path.'bootstrap.css',
				
				$this->css_path.'bootstrap-responsive.min.css',
				$this->css_path.'font-awesome.min.css',
			);
			$css = array_merge($css_compatibility, $css);
		}
		$this->context->controller->addCSS($css, 'all');

		// Load JS
		$js = array(
			$this->js_path.'module.js',
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			$js_compatibility = array(

			);
			$js = array_merge($js_compatibility, $js);
		}
		$this->context->controller->addJS($js);

		// Clean memory
		unset($js, $css, $js_compatibility, $css_compatibility);
	}

	public function displayForm()
	{
		// Get default language
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		$helper = new HelperForm();

		// Module, token and currentIndex
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

		// Language
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;

		// Title and toolbar
		$helper->title = $this->displayName;
		$helper->show_toolbar = true;        // false -> remove toolbar
		$helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
		$helper->submit_action = 'submit'.$this->name;
		$helper->toolbar_btn = array(
			'save' =>
				array(
					'desc' => $this->l('Save'),
					'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
					'&token='.Tools::getAdminTokenLite('AdminModules'),
				),
			'back' => array(
				'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
				'desc' => $this->l('Back to list')
			)
		);
		
		$helper->tpl_vars = array(
            'adod_carriers' => $this->getADODCarriers(),
			'adod_holidays' => explode(',', Configuration::get('ADOD_HOLIDAYS')),
			'adod_closing_days' => explode(',', Configuration::get('ADOD_CLOSING_DAYS')),
			'languages' =>  $this->getLanguages()
        );

		if (version_compare(_PS_VERSION_, '1.7.0', '>='))
		{
			$position_list_tab = array(
				array(
					'value' => 'PRODUCT_ACTIONS',
					'name' => $this->l('Product actions (product_actions)')
				),
				array(
					'value' => 'PRODUCT_LEFT_COLUMN',
					'name' => $this->l('Product left (product_left_column)')
				),
				array(
					'value' => 'PRODUCT_RIGHT_COLUMN',
					'name' => $this->l('Product right (product_right_column)')
				),
			);
		}
		else
		{
			$position_list_tab = array(
				array(
					'value' => 'PRODUCT_ACTIONS',
					'name' => $this->l('Product actions (product_actions)')
				),
				array(
					'value' => 'PRODUCT_EXTRA_LEFT',
					'name' => $this->l('Product usefull links (extra_left)')
				),
				array(
					'value' => 'PRODUCT_RIGHT_COLUMN',
					'name' => $this->l('Product right (product_right_column)')
				),
			);
		}
		
		$display_list_tab = array(
			array(
				'value' => 'PRODUCT_FOOTER',
				'name' => $this->l('Product footer (product_footer)')
			),
			array(
				'value' => 'PRODUCT_FANCYBOX',
				'name' => $this->l('Window (Fancybox)')
			),
		);

		$fields_form = array();

		// Init Fields form array
		$fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Settings'),
				'icon' => 'icon-cogs'
			),
			'input' => array(
				array(
					'type' => 'switch',
					'label' => $this->l('Allow sending products as soon as possible'),
					'name' => 'ADOD_ALLOW_ASAP_OPTION',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'ADOD_ALLOW_ASAP_OPTION_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'ADOD_ALLOW_ASAP_OPTION_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'desc' => $this->l('If enable, there are several dates of delivery will be displaying for customer for his order.').
					'<br/>'.$this->l('If disable, only the maximal date of delivery will be displaying.')
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Display detail on carriers page'),
					'name' => 'ADOD_ALLOW_ASAP_OPTION_DETAIL',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'ADOD_ALLOW_ASAP_OPTION_DETAIL_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'ADOD_ALLOW_ASAP_OPTION_DETAIL_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'desc' => $this->l('Display detail with products by delivery date.')
				),
				array(
					'type' => 'hr_tag',
					'name' => ''
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Display on product page'),
					'name' => 'ADOD_DISPLAY_ON_PRODUCT_PAGE',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'ADOD_DISPLAY_ON_PRODUCT_PAGE_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'ADOD_DISPLAY_ON_PRODUCT_PAGE_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'hint' => $this->l('You can enable or disable the display delivery dates on product page.')
				),
				array(
					'type' => 'select',
					'label' => $this->l('Position'),
					'name' => 'ADOD_PRODUCT_POSITION',
					'options' => array(
						'query' => $position_list_tab,
						'id' => 'value',
						'name' => 'name'
					),
					'identifier' => 'id',
					'hint' => $this->l('Choose the position of delivery date displaying on product page.')
				),
				array(
					'type' => 'select',
					'label' => $this->l('Display'),
					'name' => 'ADOD_PRODUCT_DISPLAY',
					'options' => array(
						'query' => $display_list_tab,
						'id' => 'value',
						'name' => 'name'
					),
					'identifier' => 'id',
					'hint' => $this->l('Choose the display of delivery date on product page.')
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Display carrier price'),
					'name' => 'ADOD_DISPLAY_CARRIER_PRICE',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'ADOD_DISPLAY_CARRIER_PRICE_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'ADOD_DISPLAY_CARRIER_PRICE_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'hint' => $this->l('You can enable or disable the display carrier price on product page.')
				),
				array(
					'name' => 'ADOD_PRODUCT_PAGE_TXT',
					'label' => $this->l('Text'),
					'size' => 6,
					'type' => 'text',
					'lang' => true,
					'hint' => $this->l('Define text to introduce delivery dates on product page.'),
					'desc' => $this->l('You can use variables in string:').'  %MINIMAL_DATE%, %MAXIMAL_DATE%, %NB_TOTAL_CARRIERS%.</br>
					'.$this->l('Exemple: Delivery from'). ' %MINIMAL_DATE%.', 
                ),
				array(
					'type' => 'hr_tag',
					'name' => ''
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Display on carriers list'),
					'name' => 'ADOD_DISPLAY_ON_CARRIERS_LIST',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'ADOD_DISPLAY_ON_CARRIERS_LIST_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'ADOD_DISPLAY_ON_CARRIERS_LIST_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'hint' => $this->l('You can enable or disable the display delivery dates on carriers list.')
				),
				array(
					'type' => 'hr_tag',
					'name' => ''
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Display on invoice'),
					'name' => 'ADOD_DISPLAY_ON_PDF_INVOICE',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'ADOD_DISPLAY_ON_PDF_INVOICE_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'ADOD_DISPLAY_ON_PDF_INVOICE_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'hint' => $this->l('You can enable or disable the display delivery dates on invoice.')
				),
				array(
					'type' => 'hr_tag',
					'name' => ''
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Display on delivery slip'),
					'name' => 'ADOD_DISPLAY_ON_PDF_DELIVERYSLIP',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'ADOD_DISPLAY_ON_PDF_DELIVERYSLIP_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'ADOD_DISPLAY_ON_PDF_DELIVERYSLIP_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'hint' => $this->l('You can enable or disable the display delivery dates on delivery slip.')
				),
				array(
					'type' => 'hr_tag',
					'name' => ''
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Display on order history'),
					'name' => 'ADOD_DISPLAY_ON_ORDER_HISTORY',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'ADOD_DISPLAY_ON_ORDER_HISTORY_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'ADOD_DISPLAY_ON_ORDER_HISTORY_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'hint' => $this->l('You can enable or disable the display delivery dates on order history.')
				),
				array(
					'type' => 'hr_tag',
					'name' => ''
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Display for virtual products'),
					'name' => 'ADOD_ENABLE_FOR_VIRTUAL',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'ADOD_ENABLE_FOR_VIRTUAL_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'ADOD_ENABLE_FOR_VIRTUAL_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'hint' => $this->l('You can enable or disable the display delivery dates for virtual products.')
				),
			),
			'submit' => array(
				'title' => $this->l('Save'),
			)
		);

		// Init Fields form array
		$fields_form[1]['form'] = array(
			'legend' => array(
				'title' => $this->l('Delivery'),
				'icon' => 'icon-calendar'
			),
			'input' => array(
				array(
					'type' => 'ADOD_CARRIERS_PROCESSING_TIME',
					'label' => $this->l('Carriers processing time'),
					'name' => 'ADOD_CARRIERS_PROCESSING_TIME',
					'hint' => $this->l('Define processing time by carrier.')
				),
				array(
					'type' => 'ADOD_HOLIDAYS',
					'label' => $this->l('Holidays'),
					'name' => 'ADOD_HOLIDAYS',
					'hint' => $this->l('Define all days when your shop is closed.')
				),
				array(
					'type' => 'text',
					'label' => $this->l('Processing time (in stock)'),
					'name' => 'ADOD_SHOP_PROCESSING_INSTOCK',
					'size' => 2,
					'required' => true,
					'hint' => $this->l('Define your processing time for orders where all products are available (put 0 if you ship orders the same day).'),
					'suffix' => $this->l('day(s)'),
				),
				array(
					'type' => 'text',
					'label' => $this->l('Processing time (out stock)'),
					'name' => 'ADOD_SHOP_PROCESSING_OUTSTOCK',
					'size' => 2,
					'required' => true,
					'hint' => $this->l('Define your processing time for orders where a product is not available.'),
					'suffix' => $this->l('day(s)'),
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Force stock'),
					'name' => 'ADOD_FORCE_STOCK',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'ADOD_FORCE_STOCK_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'ADOD_FORCE_STOCK_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
					'hint' => $this->l('Force stock even if a product is not available (delivery date will be shorter).')
				),
				array(
					'type' => 'text',
					'label' => $this->l('Extra processing time'),
					'name' => 'ADOD_EXTRA_SHOP_PROCESSING',
					'size' => 2,
					'required' => true,
					'hint' => $this->l('Define your extra processing time (for peak periods like Christamas).'),
					'suffix' => $this->l('day(s)'),
				),
				array(
					'type' => 'ADOD_CLOSING_DAYS',
					'label' => $this->l('Closing days'),
					'name' => 'ADOD_CLOSING_DAYS',
					'hint' => $this->l('Define all days when your shop is closed and you do not expedited orders.')
				),
				array(
					'type' => 'text',
					'label' => $this->l('Date format'),
					'name' => 'ADOD_DATE_FORMAT',
					'lang' => true,
					'size' => 10,
					'desc' => $this->l('You can see all parameters available at:').' <a href="http://www.php.net/manual/en/function.date.php">http://www.php.net/manual/en/function.date.php</a>'
				),
			),
			'submit' => array(
				'title' => $this->l('Save'),
			)
		);
		
		// Load current value
		$helper->fields_value['ADOD_ALLOW_ASAP_OPTION'] = Configuration::get('ADOD_ALLOW_ASAP_OPTION');
		$helper->fields_value['ADOD_ALLOW_ASAP_OPTION_DETAIL'] = Configuration::get('ADOD_ALLOW_ASAP_OPTION_DETAIL');
		$helper->fields_value['ADOD_DISPLAY_ON_PRODUCT_PAGE'] = Configuration::get('ADOD_DISPLAY_ON_PRODUCT_PAGE');
		$helper->fields_value['ADOD_PRODUCT_POSITION'] = Configuration::get('ADOD_PRODUCT_POSITION');
		$helper->fields_value['ADOD_PRODUCT_DISPLAY'] = Configuration::get('ADOD_PRODUCT_DISPLAY');
		$helper->fields_value['ADOD_DISPLAY_FOOTER_ANCHOR'] = Configuration::get('ADOD_DISPLAY_FOOTER_ANCHOR');
		$helper->fields_value['ADOD_DISPLAY_CARRIER_PRICE'] = Configuration::get('ADOD_DISPLAY_CARRIER_PRICE');
		$helper->fields_value['ADOD_PRODUCT_PAGE_TXT'] = Configuration::getInt('ADOD_PRODUCT_PAGE_TXT');

		$helper->fields_value['ADOD_DISPLAY_ON_CARRIERS_LIST'] = Configuration::get('ADOD_DISPLAY_ON_CARRIERS_LIST');
		$helper->fields_value['ADOD_DISPLAY_ON_PDF_INVOICE'] = Configuration::get('ADOD_DISPLAY_ON_PDF_INVOICE');
		$helper->fields_value['ADOD_DISPLAY_ON_PDF_DELIVERYSLIP'] = Configuration::get('ADOD_DISPLAY_ON_PDF_DELIVERYSLIP');
		$helper->fields_value['ADOD_DISPLAY_ON_ORDER_HISTORY'] = Configuration::get('ADOD_DISPLAY_ON_ORDER_HISTORY');

		$helper->fields_value['ADOD_ENABLE_FOR_VIRTUAL'] = Configuration::get('ADOD_ENABLE_FOR_VIRTUAL');

		$helper->fields_value['ADOD_EXTRA_SHOP_PROCESSING'] = Configuration::get('ADOD_EXTRA_SHOP_PROCESSING');
		$helper->fields_value['ADOD_SHOP_PROCESSING_INSTOCK'] = Configuration::get('ADOD_SHOP_PROCESSING_INSTOCK');
		$helper->fields_value['ADOD_SHOP_PROCESSING_OUTSTOCK'] = Configuration::get('ADOD_SHOP_PROCESSING_OUTSTOCK');
		$helper->fields_value['ADOD_FORCE_STOCK'] = Configuration::get('ADOD_FORCE_STOCK');

		$helper->fields_value['ADOD_DATE_FORMAT'] = Configuration::getInt('ADOD_DATE_FORMAT');

		return $helper->generateForm($fields_form);
	}

	public function getLanguages()
    {
        $cookie = $this->context->cookie;
        $this->allow_employee_form_lang = (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        if ($this->allow_employee_form_lang && !$cookie->employee_form_lang) {
            $cookie->employee_form_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        }

        $lang_exists = false;
        $this->_languages = Language::getLanguages(false);
        foreach ($this->_languages as $lang) {
            if (isset($cookie->employee_form_lang) && $cookie->employee_form_lang == $lang['id_lang']) {
                $lang_exists = true;
            }
        }

        $this->default_form_language = $lang_exists ? (int)$cookie->employee_form_lang : (int)Configuration::get('PS_LANG_DEFAULT');

        foreach ($this->_languages as $k => $language) {
            $this->_languages[$k]['is_default'] = (int)($language['id_lang'] == $this->default_form_language);
        }

        return $this->_languages;
    }
	
	public function getADODCarriers()
    {
		$id_lang = (int)Context::getContext()->language->id;

        $carriers = Carrier::getCarriers($id_lang, false, false, false, null, 'ALL_CARRIERS');

		foreach ($carriers as &$carrier)
		{
			$carrier_reference = $carrier['id_reference'];

			$adod_carrier = Db::getInstance()->getRow('
				SELECT * 
				FROM `'._DB_PREFIX_.'adod_carriers` 
				WHERE `id_carrier_reference` = '.(int)($carrier_reference)
			);

			if (!$adod_carrier)
			{
				$carrier['processing_days_min'] = 2;
				$carrier['processing_days_max'] = 3;
				$carrier['hour_limit'] = '12:00:00';
				$carrier['delivery_days'] = array(1,2,3,4,5);
				$carrier['is_active'] = true;
			}
			else
			{
				$carrier['processing_days_min'] = $adod_carrier['processing_days_min'];
				$carrier['processing_days_max'] = $adod_carrier['processing_days_max'];
				$carrier['hour_limit'] = $adod_carrier['hour_limit'];
				$carrier['delivery_days'] =  explode(',', $adod_carrier['delivery_days']);
				$carrier['is_active'] = $adod_carrier['active'];
			}
		}

		return $carriers;
    }

	/**
	* back office module configuration page content
	*/
	public function getContent()
	{
		$this->loadAsset();

		$output = '';
		if (Tools::isSubmit('submit'.$this->name))
		{	
			$adod_allow_sending_asap_option = Tools::getValue('ADOD_ALLOW_ASAP_OPTION');
			$adod_allow_sending_asap_option_detail = Tools::getValue('ADOD_ALLOW_ASAP_OPTION_DETAIL');
			$adod_display_on_product_page = Tools::getValue('ADOD_DISPLAY_ON_PRODUCT_PAGE');
			$adod_product_position = Tools::getValue('ADOD_PRODUCT_POSITION');
			$adod_product_display = Tools::getValue('ADOD_PRODUCT_DISPLAY');
			$adod_display_carrier_price = Tools::getValue('ADOD_DISPLAY_CARRIER_PRICE');
			$adod_display_on_carriers_list = Tools::getValue('ADOD_DISPLAY_ON_CARRIERS_LIST');
			$adod_display_on_pdf_invoice = Tools::getValue('ADOD_DISPLAY_ON_PDF_INVOICE');
			$adod_display_on_pdf_deliveryslip = Tools::getValue('ADOD_DISPLAY_ON_PDF_DELIVERYSLIP');
			$adod_display_on_order_history = Tools::getValue('ADOD_DISPLAY_ON_ORDER_HISTORY');
			$adod_enable_for_virtual_products = Tools::getValue('ADOD_ENABLE_FOR_VIRTUAL');
			
			$adod_product_page_txt = array();
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				if (Tools::getValue('ADOD_PRODUCT_PAGE_TXT_'.$language['id_lang'])) {
					$adod_product_page_txt[$language['id_lang']] = Tools::getValue('ADOD_PRODUCT_PAGE_TXT_'.$language['id_lang']);
				}
			}

			$adod_date_format = array();
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				if (Tools::getValue('ADOD_DATE_FORMAT_'.$language['id_lang'])) {
					$adod_date_format[$language['id_lang']] = Tools::getValue('ADOD_DATE_FORMAT_'.$language['id_lang']);
				}
			}
			
			$adod_shop_processing_instock =  Tools::getValue('ADOD_SHOP_PROCESSING_INSTOCK');
			$adod_shop_processing_outstock =  Tools::getValue('ADOD_SHOP_PROCESSING_OUTSTOCK');
			$adod_extra_shop_processing =  Tools::getValue('ADOD_EXTRA_SHOP_PROCESSING');

			$adod_holidays =  Tools::getValue('ADOD_HOLIDAYS');
			$adod_force_stock = Tools::getValue('ADOD_FORCE_STOCK');
			$adod_closing_days = Tools::getValue('ADOD_CLOSING_DAYS');

			Configuration::updateValue('ADOD_SHOP_PROCESSING_INSTOCK', (int)$adod_shop_processing_instock);
			Configuration::updateValue('ADOD_SHOP_PROCESSING_OUTSTOCK', (int)$adod_shop_processing_outstock);
			Configuration::updateValue('ADOD_EXTRA_SHOP_PROCESSING', (int)$adod_extra_shop_processing);
			Configuration::updateValue('ADOD_FORCE_STOCK', (bool)$adod_force_stock);
			Configuration::updateValue('ADOD_HOLIDAYS', implode(',', $adod_holidays));
			Configuration::updateValue('ADOD_CLOSING_DAYS', implode(',', $adod_closing_days));

			Configuration::updateValue('ADOD_ALLOW_ASAP_OPTION', $adod_allow_sending_asap_option);
			Configuration::updateValue('ADOD_ALLOW_ASAP_OPTION_DETAIL', $adod_allow_sending_asap_option_detail);
			Configuration::updateValue('ADOD_DISPLAY_ON_PRODUCT_PAGE', $adod_display_on_product_page);
			Configuration::updateValue('ADOD_PRODUCT_POSITION', $adod_product_position);
			Configuration::updateValue('ADOD_PRODUCT_DISPLAY', $adod_product_display);
			Configuration::updateValue('ADOD_DISPLAY_CARRIER_PRICE', $adod_display_carrier_price);
			Configuration::updateValue('ADOD_DISPLAY_ON_CARRIERS_LIST', $adod_display_on_carriers_list);
			Configuration::updateValue('ADOD_DISPLAY_ON_PDF_INVOICE', $adod_display_on_pdf_invoice);
			Configuration::updateValue('ADOD_DISPLAY_ON_PDF_DELIVERYSLIP', $adod_display_on_pdf_deliveryslip);
			Configuration::updateValue('ADOD_DISPLAY_ON_ORDER_HISTORY', $adod_display_on_order_history);
			Configuration::updateValue('ADOD_ENABLE_FOR_VIRTUAL', $adod_enable_for_virtual_products);
			
			Configuration::updateValue('ADOD_PRODUCT_PAGE_TXT', $adod_product_page_txt);

			Configuration::updateValue('ADOD_DATE_FORMAT', $adod_date_format);

			Configuration::updateValue('ADOD_CONFIGURATION_OK', true);

			$output .= $this->displayConfirmation($this->l('Settings updated successfully'));

			$this->registerCarriersDatas();
		}

		$output .= $this->displayForm();

		return $output;
	}

	public function registerCarriersDatas()
    {
		$id_lang = (int)Context::getContext()->language->id;

        $carriers = Carrier::getCarriers($id_lang, false, false, false, null, 'ALL_CARRIERS');

		foreach ($carriers as &$carrier)
		{
			$id_carrier = $carrier['id_carrier'];
			$carrier = new Carrier((int)$id_carrier);
			$carrier_reference = $carrier->id_reference;

			$is_active = Tools::getValue('is_active_'.$id_carrier);
			$processing_days_min = Tools::getValue('processing_days_min_'.$id_carrier);
			$processing_days_max = Tools::getValue('processing_days_max_'.$id_carrier);
			$processing_hour = Tools::getValue('processing_hour_'.$id_carrier);
			$processing_min = Tools::getValue('processing_min_'.$id_carrier);
			$delivery_days = Tools::getValue('delivery_days_'.$id_carrier);

			$hour_limit_bdd = $processing_hour.':'.$processing_min;
			$delivery_days_bdd = implode(',', $delivery_days);

			$adod_carrier = Db::getInstance()->getRow('
				SELECT * 
				FROM `'._DB_PREFIX_.'adod_carriers` 
				WHERE `id_carrier_reference` = '.(int)($carrier_reference)
			);

			if (!$adod_carrier)
			{
				Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'adod_carriers` (`id_carrier_reference`, `active`, `processing_days_min`, `processing_days_max`, `hour_limit`, `delivery_days`) VALUES ('.(int)($carrier_reference).', '.(int)($is_active).', '.(int)($processing_days_min).', '.(int)($processing_days_max).', "'.(string)($hour_limit_bdd).'", "'.(string)($delivery_days_bdd).'")');
			}
			else
			{
				Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'adod_carriers` SET `active` = '.(int)$is_active.', `processing_days_min` = '.(int)$processing_days_min.', `processing_days_max` = '.(int)$processing_days_max.', `hour_limit` = "'.(string)$hour_limit_bdd.'", `delivery_days` = "'.(string)$delivery_days_bdd.'" WHERE `id_carrier_reference` = '.(int)$carrier_reference);
			}
		}

		return true;
	}

	public function hookHeader($params)
	{
		if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
		{
			$this->context->controller->registerStylesheet('modules-advanceddateofdelivery', 'modules/'.$this->name.'/views/css/front_17.css', array('media' => 'all', 'priority' => 150));
			$this->context->controller->registerStylesheet('modules-advanceddateofdelivery-fontawesome', 'modules/'.$this->name.'/views/css/font-awesome.min.css', array('media' => 'all', 'priority' => 150));
		}
		else
			$this->context->controller->addCSS($this->css_path.'front.css', 'all');
		
		if (($this->context->controller->php_self == 'order') || ($this->context->controller->php_self == 'order-opc')){
			if (Configuration::get('ADOD_DISPLAY_ON_CARRIERS_LIST'))
			{
				if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
					$this->context->controller->registerJavascript('modules-advanceddateofdelivery', 'modules/'.$this->name.'/views/js/cart.js', array('position' => 'bottom', 'priority' => 150));
				else
					$this->context->controller->addJS($this->js_path.'cart.js');
			}
		}

		// check if we are in product page
		if($this->context->controller->php_self == 'product'){
			if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
			{
				$this->context->controller->registerStylesheet('modules-advanceddateofdelivery-fancybox', 'modules/'.$this->name.'/views/css/plugin/fancybox/jquery.fancybox.css', array('media' => 'all', 'priority' => 150));
				
				$this->context->controller->registerJavascript('modules-advanceddateofdelivery', 'modules/'.$this->name.'/views/js/plugin/fancybox/jquery.fancybox.js', array('position' => 'bottom', 'priority' => 150));
				$this->context->controller->registerJavascript('modules-advanceddateofdelivery-2', 'modules/'.$this->name.'/views/js/product_17.js', array('position' => 'bottom', 'priority' => 150));
			}
			else
				$this->context->controller->addJS($this->js_path.'product.js');
		}

		$asap_display = 0;
		
		if (Configuration::get('ADOD_ALLOW_ASAP_OPTION'))
			$asap_display = true;
		
		$this->smarty->assign(array(
			'asap_display' => $asap_display,
		));
		
		return $this->display(__FILE__, 'header.tpl');
	}

	/**
	* Any back-office header requirements
	*/
	public function hookBackOfficeHeader()
	{
		$controller = pSQL(Tools::getValue('controller'));
		$controller_uri = pSQL(Tools::getValue('controllerUri'));

		$js = array(
			
		);
		
		$css = array(
			$this->css_path.'admin_order.css'
		);
			
		if (($controller == 'AdminProducts' && $controller_uri == 'AdminProducts'))
		{
			$css[] = $this->css_path.'admin_products.css';
		}
		
		$this->context->controller->addCSS($css);
		$this->context->controller->addJS($js);
	}
	
	public function hookDisplayAdminOrder($params)
	{
		$order = new Order($params['id_order']);
		$product_extra_time = null;
		$product_extra_time_in_stock = null;
		$product_extra_time_oos = null;

		if (Validate::isLoadedObject($order))
		{
			$date_reference = ($order->invoice_date != '0000-00-00 00:00:00' ? $order->invoice_date : $order->date_add);

			if (Configuration::get('ADOD_ALLOW_ASAP_OPTION'))
			{
				$dates_delivery = array();
				
				foreach ($order->getProducts() as $product)
				{
					 if ($product['image'] != null) {
						$name = 'product_mini_'.(int)$product['product_id'].(isset($product['product_attribute_id']) ? '_'.(int)$product['product_attribute_id'] : '').'.jpg';
						// generate image cache, only for back office
						$product['image_tag'] = ImageManager::thumbnail(_PS_IMG_DIR_.'p/'.$product['image']->getExistingImgPath().'.jpg', $name, 45, 'jpg');
						if (file_exists(_PS_TMP_IMG_DIR_.$name)) {
							$product['image_size'] = getimagesize(_PS_TMP_IMG_DIR_.$name);
						} else {
							$product['image_size'] = false;
						}
					}

					$product_extra_time = false;
					$oos = false; // For out of stock management

					if (Configuration::get('PS_STOCK_MANAGEMENT'))
						if ($product['product_quantity'] > $product['product_quantity_in_stock'])
							$oos = true;

					if (Configuration::get('ADOD_FORCE_STOCK'))
						$oos = false;

					$product_extra_datas = $this->_getProductExtraTime($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null));
					$available_date = $this->getAvailableDate($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null), (int)$this->context->shop->id);

					if ($available_date > $date_reference)
						$date_reference = $available_date;

					if ($oos)
						$product_extra_time = ($product_extra_datas['out_stock'] > $product_extra_time ? $product_extra_datas['out_stock'] : $product_extra_time);
					else
						$product_extra_time = ($product_extra_datas['in_stock'] > $product_extra_time ? $product_extra_datas['in_stock'] : $product_extra_time);

					// Order general information
					$dates = $this->_getDatesOfDelivery($order->id_carrier, $oos, $date_reference, $product_extra_time, true);
					
					if (!isset($dates_delivery[$dates['delivery_date']['time_min']])) {
						$dates_delivery[$dates['delivery_date']['time_min']] = array(
							'date_min' => $dates['delivery_date']['date_min'],
							'time_min' => $dates['delivery_date']['time_min'],
							'date_max' => $dates['delivery_date']['date_max'],
							'time_max' => $dates['delivery_date']['time_max'],
							'shipping_date' => $dates['shipping_date'],
							'products' => array(),
						);
					}
					
					$dates_delivery[$dates['delivery_date']['time_min']]['products'][] = array(
						'product_datas' => $product,
						'date_min' => $dates['delivery_date']['date_min'],
						'time_min' => $dates['delivery_date']['time_min'],
						'date_max' => $dates['delivery_date']['date_max'],
						'time_max' => $dates['delivery_date']['time_max'],
						'shipping_date' => $dates['shipping_date'],
					);
				}

				ksort($dates_delivery);

				$this->smarty->assign(array(
					'order_dates' => $dates_delivery,
					'display_asap_option_detail' => true //Configuration::get('ADOD_ALLOW_ASAP_OPTION_DETAIL')
				));

				return $this->display(__FILE__, 'admin_asap.tpl');
			}
			else
			{
				$oos = false; // For out of stock management
				
				foreach ($order->getProducts() as $product)
				{
					if (Configuration::get('PS_STOCK_MANAGEMENT'))
						if ($product['product_quantity'] > $product['product_quantity_in_stock'])
							$oos = true;

					$product_extra_datas = $this->_getProductExtraTime($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null));
					$available_date = $this->getAvailableDate($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null), (int)$this->context->shop->id);

					if ($available_date > $date_reference)
						$date_reference = $available_date;

					if ($oos)
						$product_extra_time_oos = ($product_extra_datas['out_stock'] > $product_extra_time_oos ? $product_extra_datas['out_stock'] : $product_extra_time_oos);
					else
						$product_extra_time_in_stock = ($product_extra_datas['in_stock'] > $product_extra_time_in_stock ? $product_extra_datas['in_stock'] : $product_extra_time_in_stock);
				}

				if (Configuration::get('ADOD_FORCE_STOCK'))
					$oos = false;

				if ($oos)
					$product_extra_time = $product_extra_time_oos;
				else
					$product_extra_time = $product_extra_time_in_stock;

				// Order general information
				$dates = $this->_getDatesOfDelivery($order->id_carrier, $oos, $date_reference, $product_extra_time, true);

				$this->smarty->assign('order_dates', $dates);

				return $this->display(__FILE__, 'admin.tpl');
			}
		}
	}
	
	public function hookOrderDetailDisplayed($params)
	{
		if (!Configuration::get('ADOD_DISPLAY_ON_ORDER_HISTORY'))
			return false;

		$id_carrier = $params['order']->id_carrier;

		$carrier = new Carrier((int)$id_carrier);
		$id_carrier_reference = $carrier->id_reference;

		$carrier_rule = $this->getCarrierRuleWithIdCarrier((int)($id_carrier_reference));

		if (!empty($carrier_rule))
		{
			if (!$carrier_rule['active'])
				return false;
		}
		
		$product_extra_time = null;
		$product_extra_time_in_stock = null;
		$product_extra_time_oos = null;

		$date_reference = ($params['order']->invoice_date != '0000-00-00 00:00:00' ? $params['order']->invoice_date : $params['order']->date_add);

		if (Configuration::get('ADOD_ALLOW_ASAP_OPTION'))
		{
			$dates_delivery = array();
			
			foreach ($params['order']->getProducts() as $product)
			{
				$product_extra_time = false;
				$oos = false; // For out of stock management

				if (Configuration::get('PS_STOCK_MANAGEMENT'))
					if ($product['product_quantity'] > $product['product_quantity_in_stock'])
						$oos = true;

				if (Configuration::get('ADOD_FORCE_STOCK'))
					$oos = false;

				$product_extra_datas = $this->_getProductExtraTime($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null));
				$available_date = $this->getAvailableDate($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null), (int)$this->context->shop->id);

				if ($available_date > $date_reference)
					$date_reference = $available_date;

				if ($oos)
					$product_extra_time = ($product_extra_datas['out_stock'] > $product_extra_time ? $product_extra_datas['out_stock'] : $product_extra_time);
				else
					$product_extra_time = ($product_extra_datas['in_stock'] > $product_extra_time ? $product_extra_datas['in_stock'] : $product_extra_time);

				// Order general information
				$dates = $this->_getDatesOfDelivery((int)($params['order']->id_carrier), $oos, $date_reference, $product_extra_time, true);
				
				if (!isset($dates_delivery[$dates['delivery_date']['time_min']])) {
					$dates_delivery[$dates['delivery_date']['time_min']] = array(
						'date_min' => $dates['delivery_date']['date_min'],
						'time_min' => $dates['delivery_date']['time_min'],
						'date_max' => $dates['delivery_date']['date_max'],
						'time_max' => $dates['delivery_date']['time_max'],
						'shipping_date' => $dates['shipping_date'],
						'products' => array(),
					);
				}
				
				$dates_delivery[$dates['delivery_date']['time_min']]['products'][] = array(
					'product_datas' => $product,
					'date_min' => $dates['delivery_date']['date_min'],
					'time_min' => $dates['delivery_date']['time_min'],
					'date_max' => $dates['delivery_date']['date_max'],
					'time_max' => $dates['delivery_date']['time_max'],
					'shipping_date' => $dates['shipping_date'],
				);
			}

			ksort($dates_delivery);

			$this->smarty->assign(array(
				'order_dates' => $dates_delivery,
				'display_asap_option_detail' => Configuration::get('ADOD_ALLOW_ASAP_OPTION_DETAIL')
			));

			return $this->display(__FILE__, 'order_detail_asap.tpl');
		}
		else
		{
			$oos = false; // For out of stock management
			foreach ($params['order']->getProducts() as $product)
			{
				if (Configuration::get('PS_STOCK_MANAGEMENT'))
					if ($product['product_quantity'] > $product['product_quantity_in_stock'])
						$oos = true;
				
				$product_extra_datas = $this->_getProductExtraTime($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null));
				$available_date = $this->getAvailableDate($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null), (int)$this->context->shop->id);

				if ($available_date > $date_reference)
					$date_reference = $available_date;

				if ($oos)
					$product_extra_time_oos = ($product_extra_datas['out_stock'] > $product_extra_time_oos ? $product_extra_datas['out_stock'] : $product_extra_time_oos);
				else
					$product_extra_time_in_stock = ($product_extra_datas['in_stock'] > $product_extra_time_in_stock ? $product_extra_datas['in_stock'] : $product_extra_time_in_stock);
			}
			
			if (Configuration::get('ADOD_FORCE_STOCK'))
				$oos = false;

			if ($oos)
				$product_extra_time = $product_extra_time_oos;
			else
				$product_extra_time = $product_extra_time_in_stock;

			// Order general information
			$dates = $this->_getDatesOfDelivery((int)($params['order']->id_carrier), $oos, $date_reference, $product_extra_time, true);

			$this->smarty->assign('order_dates', $dates);

			return $this->display(__FILE__, 'order_detail.tpl');
		}
	}
	
	/**
	 * Displays the delivery dates on the invoice
	 *
	 * @param $params contains an instance of OrderInvoice
	 * @return string
	 *
	 */
	public function hookDisplayPDFInvoice($params)
	{
		if (!Configuration::get('ADOD_DISPLAY_ON_PDF_INVOICE'))
			return false;
		
		$product_extra_time = null;
		$product_extra_time_in_stock = null;
		$product_extra_time_oos = null;

		$order_invoice = $params['object'];
		if (!($order_invoice instanceof OrderInvoice))
			return;

		$order = new Order((int)$order_invoice->id_order);
		$id_carrier = (int)OrderInvoice::getCarrierId($order_invoice->id);

		$carrier = new Carrier((int)$id_carrier);
		$id_carrier_reference = $carrier->id_reference;

		$carrier_rule = $this->getCarrierRuleWithIdCarrier((int)($id_carrier_reference));

		if (!empty($carrier_rule))
		{
			if (!$carrier_rule['active'])
				return false;
		}

		$date_reference = $order_invoice->date_add;

		if (Configuration::get('ADOD_ALLOW_ASAP_OPTION'))
		{
			$dates_delivery = array();
			$return = '';
			
			foreach ($order->getProducts() as $product)
			{
				$product_extra_time = false;
				$oos = false; // For out of stock management

				if (Configuration::get('PS_STOCK_MANAGEMENT'))
					if ($product['product_quantity'] > $product['product_quantity_in_stock'])
						$oos = true;

				if (Configuration::get('ADOD_FORCE_STOCK'))
					$oos = false;

				$product_extra_datas = $this->_getProductExtraTime($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null));
				$available_date = $this->getAvailableDate($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null), (int)$this->context->shop->id);

				if ($available_date > $date_reference)
					$date_reference = $available_date;

				if ($oos)
					$product_extra_time = ($product_extra_datas['out_stock'] > $product_extra_time ? $product_extra_datas['out_stock'] : $product_extra_time);
				else
					$product_extra_time = ($product_extra_datas['in_stock'] > $product_extra_time ? $product_extra_datas['in_stock'] : $product_extra_time);

				// Order general information
				$dates = $this->_getDatesOfDelivery($id_carrier, $oos, $date_reference, $product_extra_time, true);

				if (!isset($dates_delivery[$dates['delivery_date']['time_min']])) {
					$dates_delivery[$dates['delivery_date']['time_min']] = array(
						'date_min' => $dates['delivery_date']['date_min'],
						'time_min' => $dates['delivery_date']['time_min'],
						'date_max' => $dates['delivery_date']['date_max'],
						'time_max' => $dates['delivery_date']['time_max'],
						'shipping_date' => $dates['shipping_date'],
						'products' => array(),
					);
				}
				
				$dates_delivery[$dates['delivery_date']['time_min']]['products'][] = array(
					'product_datas' => $product,
					'date_min' => $dates['delivery_date']['date_min'],
					'time_min' => $dates['delivery_date']['time_min'],
					'date_max' => $dates['delivery_date']['date_max'],
					'time_max' => $dates['delivery_date']['time_max'],
					'shipping_date' => $dates['shipping_date'],
				);
			}

			ksort($dates_delivery);

			$index = 0;
			foreach ($dates_delivery as $dates)
			{
				$index = $index+1;
				
				if ($dates['date_min'] == $dates['date_max'])
					$return .= sprintf($this->l('Delivery %1$s:'), $index).' '.sprintf($this->l('Approximate date of delivery on %1$s.'), $dates['date_min']).'<br/>';
				else
					$return .= sprintf($this->l('Delivery %1$s:'), $index).' '.sprintf($this->l('Approximate date of delivery is between %1$s and %2$s.'), $dates['date_min'], $dates['date_max']).'<br/>';
			}
		}
		else
		{
			$oos = false; // For out of stock management
			foreach ($order->getProducts() as $product)
			{
				if (Configuration::get('PS_STOCK_MANAGEMENT'))
					if ($product['product_quantity'] > $product['product_quantity_in_stock'])
						$oos = true;
				
				$product_extra_datas = $this->_getProductExtraTime($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null));
				$available_date = $this->getAvailableDate($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null), (int)$this->context->shop->id);

				if ($available_date > $date_reference)
					$date_reference = $available_date;

				if ($oos)
					$product_extra_time_oos = ($product_extra_datas['out_stock'] > $product_extra_time_oos ? $product_extra_datas['out_stock'] : $product_extra_time_oos);
				else
					$product_extra_time_in_stock = ($product_extra_datas['in_stock'] > $product_extra_time_in_stock ? $product_extra_datas['in_stock'] : $product_extra_time_in_stock);
			}

			if (Configuration::get('ADOD_FORCE_STOCK'))
				$oos = false;

			if ($oos)
				$product_extra_time = $product_extra_time_oos;
			else
				$product_extra_time = $product_extra_time_in_stock;

			// Order general information
			$dates = $this->_getDatesOfDelivery($id_carrier, $oos, $date_reference, $product_extra_time, true);

			if ($dates['delivery_date']['date_min'] == $dates['delivery_date']['date_max'])
				$return = sprintf($this->l('Approximate date of delivery on %1$s.'), $dates['delivery_date']['date_min']);
			else
				$return = sprintf($this->l('Approximate date of delivery is between %1$s and %2$s.'), $dates['delivery_date']['date_min'], $dates['delivery_date']['date_max']);
		}

		return $return;
	}
	
	public function hookDisplayPDFDeliverySlip($params)
	{
		if (!Configuration::get('ADOD_DISPLAY_ON_PDF_DELIVERYSLIP'))
			return false;
		
		$product_extra_time = null;
		$product_extra_time_in_stock = null;
		$product_extra_time_oos = null;

		$order_invoice = $params['object'];
		if (!($order_invoice instanceof OrderInvoice))
			return;

		$order = new Order((int)$order_invoice->id_order);
		$id_carrier = (int)OrderInvoice::getCarrierId($order_invoice->id);

		$carrier = new Carrier((int)$id_carrier);
		$id_carrier_reference = $carrier->id_reference;

		$carrier_rule = $this->getCarrierRuleWithIdCarrier((int)($id_carrier_reference));

		if (!empty($carrier_rule))
		{
			if (!$carrier_rule['active'])
				return false;
		}

		$date_reference = $order_invoice->date_add;

		if (Configuration::get('ADOD_ALLOW_ASAP_OPTION'))
		{
			$dates_delivery = array();
			$return = '';
			
			foreach ($order->getProducts() as $product)
			{
				$product_extra_time = false;
				$oos = false; // For out of stock management

				if (Configuration::get('PS_STOCK_MANAGEMENT'))
					if ($product['product_quantity'] > $product['product_quantity_in_stock'])
						$oos = true;

				if (Configuration::get('ADOD_FORCE_STOCK'))
					$oos = false;

				$product_extra_datas = $this->_getProductExtraTime($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null));
				$available_date = $this->getAvailableDate($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null), (int)$this->context->shop->id);

				if ($available_date > $date_reference)
					$date_reference = $available_date;

				if ($oos)
					$product_extra_time = ($product_extra_datas['out_stock'] > $product_extra_time ? $product_extra_datas['out_stock'] : $product_extra_time);
				else
					$product_extra_time = ($product_extra_datas['in_stock'] > $product_extra_time ? $product_extra_datas['in_stock'] : $product_extra_time);

				// Order general information
				$dates = $this->_getDatesOfDelivery($id_carrier, $oos, $date_reference, $product_extra_time, true);

				if (!isset($dates_delivery[$dates['delivery_date']['time_min']])) {
					$dates_delivery[$dates['delivery_date']['time_min']] = array(
						'date_min' => $dates['delivery_date']['date_min'],
						'time_min' => $dates['delivery_date']['time_min'],
						'date_max' => $dates['delivery_date']['date_max'],
						'time_max' => $dates['delivery_date']['time_max'],
						'shipping_date' => $dates['shipping_date'],
						'products' => array(),
					);
				}
				
				$dates_delivery[$dates['delivery_date']['time_min']]['products'][] = array(
					'product_datas' => $product,
					'date_min' => $dates['delivery_date']['date_min'],
					'time_min' => $dates['delivery_date']['time_min'],
					'date_max' => $dates['delivery_date']['date_max'],
					'time_max' => $dates['delivery_date']['time_max'],
					'shipping_date' => $dates['shipping_date'],
				);
			}

			ksort($dates_delivery);

			$index = 0;
			foreach ($dates_delivery as $dates)
			{
				$index = $index+1;
				
				if ($dates['date_min'] == $dates['date_max'])
					$return .= sprintf($this->l('Delivery %1$s:'), $index).' '.sprintf($this->l('Approximate date of delivery on %1$s.'), $dates['date_min']).'<br/>';
				else
					$return .= sprintf($this->l('Delivery %1$s:'), $index).' '.sprintf($this->l('Approximate date of delivery is between %1$s and %2$s.'), $dates['date_min'], $dates['date_max']).'<br/>';
			}
		}
		else
		{
			$oos = false; // For out of stock management
			foreach ($order->getProducts() as $product)
			{
				if (Configuration::get('PS_STOCK_MANAGEMENT'))
					if ($product['product_quantity'] > $product['product_quantity_in_stock'])
						$oos = true;
				
				$product_extra_datas = $this->_getProductExtraTime($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null));
				$available_date = $this->getAvailableDate($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null), (int)$this->context->shop->id);

				if ($available_date > $date_reference)
					$date_reference = $available_date;

				if ($oos)
					$product_extra_time_oos = ($product_extra_datas['out_stock'] > $product_extra_time_oos ? $product_extra_datas['out_stock'] : $product_extra_time_oos);
				else
					$product_extra_time_in_stock = ($product_extra_datas['in_stock'] > $product_extra_time_in_stock ? $product_extra_datas['in_stock'] : $product_extra_time_in_stock);
			}

			if (Configuration::get('ADOD_FORCE_STOCK'))
				$oos = false;

			if ($oos)
				$product_extra_time = $product_extra_time_oos;
			else
				$product_extra_time = $product_extra_time_in_stock;

			// Order general information
			$dates = $this->_getDatesOfDelivery($id_carrier, $oos, $date_reference, $product_extra_time, true);

			if ($dates['delivery_date']['date_min'] == $dates['delivery_date']['date_max'])
				$return = sprintf($this->l('Approximate date of delivery on %1$s.'), $dates['delivery_date']['date_min']);
			else
				$return = sprintf($this->l('Approximate date of delivery is between %1$s and %2$s.'), $dates['delivery_date']['date_min'], $dates['delivery_date']['date_max']);
		}

		return $return;
	}

	public function hookBeforeCarrier($params)
	{
		if (!Configuration::get('ADOD_DISPLAY_ON_CARRIERS_LIST'))
			return false;

		if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
		{
			if (!count($params['cart']->getDeliveryOptionList()))
				return false;
			
			$delivery_option_list = $params['cart']->getDeliveryOptionList();
		}
		else
		{
			if (!isset($params['delivery_option_list']) || !count($params['delivery_option_list']))
				return false;
			
			$delivery_option_list = $params['delivery_option_list'];
		}
		
		$package_list = $params['cart']->getPackageList();

		$dates_delivery = array();
		foreach ($delivery_option_list as $id_address => $by_address)
		{
			$dates_delivery[$id_address] = array();
			foreach ($by_address as $key => $delivery_option)
			{
				$date_from = null;
				$date_to = null;
				$dates_delivery[$id_address][$key] = array();

				foreach ($delivery_option['carrier_list'] as $id_carrier => $carrier)
				{
					foreach ($carrier['package_list'] as $id_package)
					{
						if (isset($package_list[$id_address][$id_package]))
							$package = $package_list[$id_address][$id_package];

						$oos = false;
						if (isset($package['product_list']) && is_array($package['product_list']))
							foreach ($package['product_list'] as $product)
							{
								if (Configuration::get('ADOD_ALLOW_ASAP_OPTION'))
									$oos = false;

								$product_extra_time = false;
								
								if (Configuration::get('PS_STOCK_MANAGEMENT'))
								{
									$quantity_in_stock = StockAvailable::getQuantityAvailableByProduct($product['id_product'], ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), (int)$this->context->shop->id);
								
									if ($product['quantity'] > $quantity_in_stock)
										$oos = true;
								}
								
								if (Configuration::get('ADOD_FORCE_STOCK'))
									$oos = false;
								
								$product_extra_datas = $this->_getProductExtraTime($product['id_product'], ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null));
								
								$available_date = $this->getAvailableDate($product['id_product'], ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), (int)$this->context->shop->id);
							
								if ($oos)
									$product_extra_time = ($product_extra_datas['out_stock'] > $product_extra_time ? $product_extra_datas['out_stock'] : $product_extra_time);
								else
									$product_extra_time = ($product_extra_datas['in_stock'] > $product_extra_time ? $product_extra_datas['in_stock'] : $product_extra_time);

								$date_range = $this->_getDatesOfDelivery((int)($id_carrier), $oos, $available_date, $product_extra_time);
								
								if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
								{
									$protocol_link = (Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode()) ? 'https://' : 'http://';
									$useSSL = ((isset($this->ssl) && $this->ssl && Configuration::get('PS_SSL_ENABLED')) || Tools::usingSecureMode()) ? true : false;
									$protocol_content = ($useSSL) ? 'https://' : 'http://';
									$link = new Link($protocol_link, $protocol_content);
									$product['image_tag'] = $link->getImageLink($product['link_rewrite'], $product['id_image'], 'cart_default');
								}
								
								if (Configuration::get('ADOD_ALLOW_ASAP_OPTION'))
								{
									if (isset($date_range))
									{
										if (!isset($dates_delivery[$id_address][$key][$date_range['delivery_date']['time_min']])) {
											$dates_delivery[$id_address][$key][$date_range['delivery_date']['time_min']] = array(
												'date_min' => $date_range['delivery_date']['date_min'],
												'time_min' => $date_range['delivery_date']['time_min'],
												'date_max' => $date_range['delivery_date']['date_max'],
												'time_max' => $date_range['delivery_date']['time_max'],
												'products' => array(),
											);
										}
										
										$dates_delivery[$id_address][$key][$date_range['delivery_date']['time_min']]['products'][] = array(
											'product_datas' => $product,
											'date_min' => $date_range['delivery_date']['date_min'],
											'time_min' => $date_range['delivery_date']['time_min'],
											'date_max' => $date_range['delivery_date']['date_max'],
											'time_max' => $date_range['delivery_date']['time_max'],
										);
									}
								}
								else
								{
									if (isset($date_range) && (is_null($date_from) || $date_from < $date_range['delivery_date']['time_min']))
									{
										$date_from = $date_range['delivery_date']['time_min'];
										$dates_delivery[$id_address][$key][0] = array(
											'date_min' => $date_range['delivery_date']['date_min'],
											'time_min' => $date_range['delivery_date']['time_min']
										);
									}
									if (isset($date_range) && (is_null($date_to) || $date_to < $date_range['delivery_date']['time_max']))
									{
										$date_to = $date_range['delivery_date']['time_max'];
										$dates_delivery[$id_address][$key][1] = array(
											'date_max' => $date_range['delivery_date']['date_max'],
											'time_max' => $date_range['delivery_date']['time_max']
										);
									}
								}									
							}
					}
				}
				
				foreach ($delivery_option['carrier_list'] as $id_carrier => $carrier)
				{
					$carrier = new Carrier((int)$id_carrier);
					$id_carrier_reference = $carrier->id_reference;

					$carrier_rule = $this->getCarrierRuleWithIdCarrier((int)($id_carrier_reference));

					if (!empty($carrier_rule))
					{
						if (!$carrier_rule['active'])
							unset($dates_delivery[$id_address][$key]);
					}
				}
			}
		}

		$this->smarty->assign(array(
			'nbPackages' => $params['cart']->getNbOfPackages(),
			'delivery_option' => $delivery_option,
			'module_controller_url' => $this->module_url.'ajax_carriers.php',
			'secure_key' => $this->secure_key,
		));

		if (Configuration::get('ADOD_ALLOW_ASAP_OPTION'))
		{
			$this->smarty->assign(array(
				'cartDatesDelivery' => Tools::jsonEncode($dates_delivery),
			));
		
			if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
				return $this->display(__FILE__, 'before_carrier_asap_17.tpl');
			else
				return $this->display(__FILE__, 'before_carrier_asap.tpl');
		}
		else
		{
			$this->smarty->assign(array(
				'cartDatesDelivery' => $dates_delivery,
			));
		
			if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
				return $this->display(__FILE__, 'before_carrier_17.tpl');
			else
				return $this->display(__FILE__, 'before_carrier.tpl');
		}
	}
	
	public function createTemplate($tpl_name) {
        if (file_exists($this->getTemplatePath() . $tpl_name) && $this->viewAccess())
            return $this->context->smarty->createTemplate($this->getTemplatePath() . $tpl_name, $this->context->smarty);
        return parent::createTemplate($tpl_name);
    }
	
	public function ajaxProcessShowAdodTemplate()
	{
		$array_products = Tools::getValue('products');
		
		$this->smarty->assign(array(
			'array_products' => $array_products,
			'display_asap_option_detail' => Configuration::get('ADOD_ALLOW_ASAP_OPTION_DETAIL')
		));
		
		if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
			$content = $this->display(__FILE__, 'before_carriers_asap_17.tpl');
		else
			$content = $this->display(__FILE__, 'before_carriers_asap.tpl');
		 
		die(Tools::jsonEncode(array(
			'result' => $content,
		)));
	}
	
	public function hookDisplayAdminProductsExtra($params)
	{
		if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
			$id_product = $params['id_product'];
		else
			$id_product = Tools::getValue('id_product');

		if (!(bool)$id_product) {
			$this->context->smarty->assign(array(
				'waiting_first_save' => true,
			));
		}
		else
		{
			$product = new Product($id_product,false,(int)$this->context->language->id);

			$currency = $this->context->currency;
			$default_class = 'highlighted';

			/* Build attributes combinations */
			$combinations = $product->getAttributeCombinations($this->context->language->id);
			$groups = array();
			$comb_array = array();
			if (is_array($combinations))
			{
				$combination_images = $product->getCombinationImages($this->context->language->id);
				foreach ($combinations as $combination)
				{
					$price_to_convert = Tools::convertPrice($combination['price'], $currency);
					$price = Tools::displayPrice($price_to_convert, $currency);

					$comb_array[$combination['id_product_attribute']]['id_product_attribute'] = $combination['id_product_attribute'];
					$comb_array[$combination['id_product_attribute']]['attributes'][] = array($combination['group_name'], $combination['attribute_name'], $combination['id_attribute']);
					$comb_array[$combination['id_product_attribute']]['wholesale_price'] = $combination['wholesale_price'];
					$comb_array[$combination['id_product_attribute']]['price'] = $price;
					$comb_array[$combination['id_product_attribute']]['weight'] = $combination['weight'].Configuration::get('PS_WEIGHT_UNIT');
					$comb_array[$combination['id_product_attribute']]['unit_impact'] = $combination['unit_price_impact'];
					$comb_array[$combination['id_product_attribute']]['reference'] = $combination['reference'];
					$comb_array[$combination['id_product_attribute']]['ean13'] = $combination['ean13'];
					$comb_array[$combination['id_product_attribute']]['upc'] = $combination['upc'];
					$comb_array[$combination['id_product_attribute']]['id_image'] = isset($combination_images[$combination['id_product_attribute']][0]['id_image']) ? $combination_images[$combination['id_product_attribute']][0]['id_image'] : 0;
					$comb_array[$combination['id_product_attribute']]['available_date'] = strftime($combination['available_date']);
					$comb_array[$combination['id_product_attribute']]['default_on'] = $combination['default_on'];
					if ($combination['is_color_group'])
						$groups[$combination['id_attribute_group']] = $combination['group_name'];
				}
			}

			if (isset($comb_array))
			{
				foreach ($comb_array as $id_product_attribute => $product_attribute)
				{
					$list = '';

					/* In order to keep the same attributes order */
					asort($product_attribute['attributes']);

					foreach ($product_attribute['attributes'] as $attribute)
						$list .= $attribute[0].' - '.$attribute[1].', ';

					$list = rtrim($list, ', ');
					$comb_array[$id_product_attribute]['image'] = $product_attribute['id_image'] ? new Image($product_attribute['id_image']) : false;
					$comb_array[$id_product_attribute]['available_date'] = $product_attribute['available_date'] != 0 ? date('Y-m-d', strtotime($product_attribute['available_date'])) : '0000-00-00';
					$comb_array[$id_product_attribute]['attributes'] = $list;
					$comb_array[$id_product_attribute]['name'] = $list;

					$array_extra_time = $this->_getProductExtraTime($product->id, $product_attribute['id_product_attribute']);

					$comb_array[$id_product_attribute]['extra_time'] = array(
						'in_stock' => (isset($array_extra_time['in_stock']) ? $array_extra_time['in_stock'] : 0),
						'out_stock' => (isset($array_extra_time['out_stock']) ? $array_extra_time['out_stock'] : 0)
					);

					if ($product_attribute['default_on'])
						$comb_array[$id_product_attribute]['class'] = $default_class;
				}
			}

			/* Get all required values from configuration table for the module's back-office */
			$keys = array(
				'ADOD_SHOP_PROCESSING_INSTOCK',
				'ADOD_SHOP_PROCESSING_OUTSTOCK',
				'ADOD_FORCE_STOCK',
				'ADOD_ENABLE_FOR_VIRTUAL',
			);

			$config = Configuration::getMultiple($keys);

			$array_extra_time_product = $this->_getProductExtraTime($product->id, false);


			$this->context->smarty->assign(array(
				'product_combinations' => $comb_array,
				'extra_time_product' => $array_extra_time_product,
				'module_url' => $this->module_url.'ajax.php',
				'product' => $product,
				'config' => $config,
			));
		}

		if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
			return $this->display(__FILE__, 'admin_products_extra_17.tpl');
		else
			return $this->display(__FILE__, 'admin_products_extra.tpl');
	}

	public function getOrderDateOfDelivery($id_order)
	{
		$order = new Order((int)$id_order);
		$id_carrier = $order->id_carrier;
		$product_extra_time = null;
		$product_extra_time_in_stock = null;
		$product_extra_time_oos = null;

		$date_reference = ($order->invoice_date != '0000-00-00 00:00:00' ? $order->invoice_date : $order->date_add);

		if (Configuration::get('ADOD_ALLOW_ASAP_OPTION'))
		{
			$dates_delivery = array();
			$return = '';
			
			foreach ($order->getProducts() as $product)
			{
				$oos = false; // For out of stock management
				
				if (Configuration::get('PS_STOCK_MANAGEMENT'))
					if ($product['product_quantity'] > $product['product_quantity_in_stock'])
						$oos = true;
				
				$product_extra_datas = $this->_getProductExtraTime($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null));
				$available_date = $this->getAvailableDate($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null), (int)$this->context->shop->id);

				if ($available_date > $date_reference)
					$date_reference = $available_date;

				if ($oos)
					$product_extra_time_oos = ($product_extra_datas['out_stock'] > $product_extra_time_oos ? $product_extra_datas['out_stock'] : $product_extra_time_oos);
				else
					$product_extra_time_in_stock = ($product_extra_datas['in_stock'] > $product_extra_time_in_stock ? $product_extra_datas['in_stock'] : $product_extra_time_in_stock);
				
				if (Configuration::get('ADOD_FORCE_STOCK'))
					$oos = false;

				if ($oos)
					$product_extra_time = $product_extra_time_oos;
				else
					$product_extra_time = $product_extra_time_in_stock;

				// Order general information
				$dates = $this->_getDatesOfDelivery($id_carrier, $oos, $date_reference, $product_extra_time, true);
				
				if (!isset($dates_delivery[$dates['delivery_date']['time_min']])) {
					$dates_delivery[$dates['delivery_date']['time_min']] = array(
						'date_min' => $dates['delivery_date']['date_min'],
						'time_min' => $dates['delivery_date']['time_min'],
						'date_max' => $dates['delivery_date']['date_max'],
						'time_max' => $dates['delivery_date']['time_max'],
						'shipping_date' => $dates['shipping_date'],
						'products' => array(),
					);
				}
				
				$dates_delivery[$dates['delivery_date']['time_min']]['products'][] = array(
					'product_datas' => $product,
					'date_min' => $dates['delivery_date']['date_min'],
					'time_min' => $dates['delivery_date']['time_min'],
					'date_max' => $dates['delivery_date']['date_max'],
					'time_max' => $dates['delivery_date']['time_max'],
					'shipping_date' => $dates['shipping_date'],
				);
			}

			$index = 0;
			foreach ($dates_delivery as $dates)
			{
				$index = $index+1;
				
				if ($dates['date_min'] == $dates['date_max'])
					$return .= sprintf($this->l('Delivery %1$s:'), $index).' '.sprintf($this->l('Approximate date of delivery on %1$s.'), $dates['date_min']).'<br/>';
				else
					$return .= sprintf($this->l('Delivery %1$s:'), $index).' '.sprintf($this->l('Approximate date of delivery is between %1$s and %2$s.'), $dates['date_min'], $dates['date_max']).'<br/>';
			}
		}
		else
		{
			$oos = false; // For out of stock management
			
			foreach ($order->getProducts() as $product)
			{
				if (Configuration::get('PS_STOCK_MANAGEMENT'))
					if ($product['product_quantity'] > $product['product_quantity_in_stock'])
						$oos = true;

				$product_extra_datas = $this->_getProductExtraTime($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null));
				$available_date = $this->getAvailableDate($product['product_id'], ($product['product_attribute_id'] ? (int)$product['product_attribute_id'] : null), (int)$this->context->shop->id);

				if ($available_date > $date_reference)
					$date_reference = $available_date;

				if ($oos)
					$product_extra_time_oos = ($product_extra_datas['out_stock'] > $product_extra_time_oos ? $product_extra_datas['out_stock'] : $product_extra_time_oos);
				else
					$product_extra_time_in_stock = ($product_extra_datas['in_stock'] > $product_extra_time_in_stock ? $product_extra_datas['in_stock'] : $product_extra_time_in_stock);
			}

			if (Configuration::get('ADOD_FORCE_STOCK'))
				$oos = false;

			if ($oos)
				$product_extra_time = $product_extra_time_oos;
			else
				$product_extra_time = $product_extra_time_in_stock;

			// Order general information

			$dates = $this->_getDatesOfDelivery((int)($order->id_carrier), $oos, $date_reference, $product_extra_time, true);

			if ($dates['delivery_date']['date_min'] == $dates['delivery_date']['date_max'])
				$return = sprintf($this->l('Approximate date of delivery on %1$s.'), $dates['delivery_date']['date_min']);
			else
				$return = sprintf($this->l('Approximate date of delivery is between %1$s and %2$s.'), $dates['delivery_date']['date_min'], $dates['delivery_date']['date_max']);
		}

		return $return;
	}

	public static function _getProductExtraTime($id_product, $id_product_attribute = null)
	{
		if (!Validate::isUnsignedId($id_product))
			return false;

		$query = new DbQuery();
		$query->select('*');
		$query->from('adod_product');
		$query->where('id_product = '.(int)$id_product);

		if ($id_product_attribute !== null)
			$query->where('id_product_attribute = '.(int)$id_product_attribute);

		return Db::getInstance()->getRow($query);
	}

	public function hookDisplayFooterProduct($params)
	{
		if (!Configuration::get('ADOD_DISPLAY_ON_PRODUCT_PAGE'))
			return false;

		$carrierList = $this->getProductCarriers();		

		if (!$carrierList)
			return false;
		
		$this->context->smarty->assign(array(
			'carrier_delivery_list' => $carrierList,
			'adod_product_position' => Configuration::get('ADOD_PRODUCT_POSITION'),
			'adod_product_display' => Configuration::get('ADOD_PRODUCT_DISPLAY'),
			'adod_product_page_txt' => Configuration::get('ADOD_PRODUCT_PAGE_TXT', (int)Context::getContext()->language->id),
			'display_carrier_price' => Configuration::get('ADOD_DISPLAY_CARRIER_PRICE')
		));

		if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
			return ($this->display(__FILE__, '/footer_product_17.tpl'));
		else
			return ($this->display(__FILE__, '/footer_product.tpl'));
	}

	public function hookProductActions($params)
	{
		if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
			return false;
	
		if (!Configuration::get('ADOD_DISPLAY_ON_PRODUCT_PAGE'))
			return false;

		if (Configuration::get('ADOD_PRODUCT_POSITION') != 'PRODUCT_ACTIONS')
			return false;

		$carrierList = $this->getProductCarriers();		

		if (!$carrierList)
			return false;

		$this->smarty->assign(array(
			'id_product' => (int)Tools::getValue('id_product'),
			'adod_product_page_txt' => Configuration::get('ADOD_PRODUCT_PAGE_TXT', (int)Context::getContext()->language->id),
		));
		
		return ($this->display(__FILE__, '/product_actions.tpl'));
	}

	public function hookDisplayReassurance($params)
	{
		if (Context::getContext()->controller->php_self != 'product')
			return false;

		if (!Configuration::get('ADOD_DISPLAY_ON_PRODUCT_PAGE'))
			return false;

		if (Configuration::get('ADOD_PRODUCT_POSITION') != 'PRODUCT_ACTIONS')
			return false;

		$carrierList = $this->getProductCarriers();		

		if (!$carrierList)
			return false;

		$this->smarty->assign(array(
			'id_product' => (int)Tools::getValue('id_product'),
			'adod_product_page_txt' => Configuration::get('ADOD_PRODUCT_PAGE_TXT', (int)Context::getContext()->language->id),
		));
		
		if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
			return ($this->display(__FILE__, '/product_actions_17.tpl'));
	}

	public function hookExtraLeft($params)
	{
		if (!Configuration::get('ADOD_DISPLAY_ON_PRODUCT_PAGE'))
			return false;

		if ( !in_array(Configuration::get('ADOD_PRODUCT_POSITION'), array('PRODUCT_LEFT_COLUMN','PRODUCT_EXTRA_LEFT'), true ) )
			return false;

		/*if (Configuration::get('ADOD_PRODUCT_POSITION') != 'PRODUCT_EXTRA_LEFT')
			return false;*/

		$carrierList = $this->getProductCarriers();		

		if (!$carrierList)
			return false;

		$this->smarty->assign(array(
			'id_product' => (int)Tools::getValue('id_product'),
			'adod_product_page_txt' => Configuration::get('ADOD_PRODUCT_PAGE_TXT', (int)Context::getContext()->language->id),
		));
		
		return ($this->display(__FILE__, '/extra_left.tpl'));
	}
	
	public function hookDisplayLeftColumnProduct($params)
	{
		if (!Configuration::get('ADOD_DISPLAY_ON_PRODUCT_PAGE'))
			return false;

		if ( !in_array(Configuration::get('ADOD_PRODUCT_POSITION'), array('PRODUCT_LEFT_COLUMN','PRODUCT_EXTRA_LEFT'), true ) )
			return false;

		/*if (Configuration::get('ADOD_PRODUCT_POSITION') != 'PRODUCT_LEFT_COLUMN')
			return false;*/

		$carrierList = $this->getProductCarriers();		

		if (!$carrierList)
			return false;

		$this->smarty->assign(array(
			'id_product' => (int)Tools::getValue('id_product'),
			'adod_product_page_txt' => Configuration::get('ADOD_PRODUCT_PAGE_TXT', (int)Context::getContext()->language->id),
		));
		
		if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
			return ($this->display(__FILE__, '/product_right_column_17.tpl'));
		else
			return ($this->display(__FILE__, '/extra_left.tpl'));
	}
	
	public function hookDisplayRightColumnProduct($params)
	{
		if (!Configuration::get('ADOD_DISPLAY_ON_PRODUCT_PAGE'))
			return false;

		if (Configuration::get('ADOD_PRODUCT_POSITION') != 'PRODUCT_RIGHT_COLUMN')
			return false;

		$carrierList = $this->getProductCarriers();		

		if (!$carrierList)
			return false;

		$this->smarty->assign(array(
			'id_product' => (int)Tools::getValue('id_product'),
			'adod_product_page_txt' => Configuration::get('ADOD_PRODUCT_PAGE_TXT', (int)Context::getContext()->language->id),
		));
		
		if (version_compare(_PS_VERSION_, '1.7.0', '>=')) // 1.7 compatibility
			return ($this->display(__FILE__, '/product_right_column_17.tpl'));
		else
			return ($this->display(__FILE__, '/product_right_column.tpl'));
	}
	
	public function getProductCarriers()
	{
		// Variables
		$id_product = (int)Tools::getValue('id_product');
		$id_country = Context::getContext()->cookie->id_country;
		$id_state = Context::getContext()->cookie->id_state;
		$postcode = Context::getContext()->cookie->postcode;

		$carrierList = $this->getCarriersListByIdZone($id_product, $id_country, $id_state, $postcode);

		$product = new Product((int)$id_product);

		if ($product->is_virtual && !Configuration::get('ADOD_ENABLE_FOR_VIRTUAL'))
			return false;

		$attributes = $product->getAttributesGroups((int)Context::getContext()->language->id);
		$combinations = array();

		$id_address = 0;

		foreach ($attributes as $attribute)
		{
			$combinations[$attribute['id_product_attribute']]['id_product_attribute'] = $attribute['id_product_attribute'];
			if (!isset($combinations[$attribute['id_product_attribute']]['attributes']))
				$combinations[$attribute['id_product_attribute']]['attributes'] = '';
			$combinations[$attribute['id_product_attribute']]['attributes'] .= $attribute['attribute_name'].' - ';

			$combinations[$attribute['id_product_attribute']]['default_on'] = $attribute['default_on'];
		}

		foreach ($carrierList as $key => $carrier)
		{
			$dates_delivery = array();
			$id_carrier = $carrier['id_carrier'];

			if(!empty($combinations))
			{
				foreach ($combinations as &$combination)
				{
					$id_product_attribute = $combination['id_product_attribute'];
					$product_extra_time = false;
					
					$oos = false; // For out of stock management
					$date_from = null;
					$date_to = null;
					$dates_delivery[$id_address][$id_carrier][$product->id.'_'.$id_product_attribute] = array();

					if (Configuration::get('PS_STOCK_MANAGEMENT'))
						if (StockAvailable::getQuantityAvailableByProduct($product->id, ($id_product_attribute ? (int)$id_product_attribute : null), (int)Context::getContext()->shop->id) <= 0)
							$oos = true;

					if (Configuration::get('ADOD_FORCE_STOCK'))
						$oos = false;

					$product_extra_datas = $this->_getProductExtraTime($product->id, ($id_product_attribute ? (int)$id_product_attribute : null));
					
					if ($oos)
						$product_extra_time = $product_extra_datas['out_stock'];
					else
						$product_extra_time = $product_extra_datas['in_stock'];
					
					$available_date = $this->getAvailableDate($product->id, ($id_product_attribute ? (int)$id_product_attribute : null), (int)Context::getContext()->shop->id);

					$date_range = $this->_getDatesOfDelivery($id_carrier, $oos, $available_date, $product_extra_time);

					if (isset($date_range) && (is_null($date_from) || $date_from < $date_range[0]))
					{
						$date_from = $date_range['delivery_date']['date_min'];
						$dates_delivery[$id_address][$id_carrier][$product->id.'_'.$id_product_attribute][0] = array(
							'date_min' => $date_range['delivery_date']['date_min'],
							'time_min' => $date_range['delivery_date']['time_min']
						);
					}
					if (isset($date_range) && (is_null($date_to) || $date_to < $date_range[1]))
					{
						$date_to = $date_range['delivery_date']['date_max'];
						$dates_delivery[$id_address][$id_carrier][$product->id.'_'.$id_product_attribute][1] = array(
							'date_max' => $date_range['delivery_date']['date_max'],
							'time_max' => $date_range['delivery_date']['time_max']
						);
					}
				}
			}
			else
			{
				$id_product_attribute = 0;
				$product_extra_time = false;
				
				$oos = false; // For out of stock management
				$date_from = null;
				$date_to = null;
				$dates_delivery[$id_address][$id_carrier][$product->id.'_'.$id_product_attribute] = array();

				if (Configuration::get('PS_STOCK_MANAGEMENT'))
					if (StockAvailable::getQuantityAvailableByProduct($product->id, ($id_product_attribute ? (int)$id_product_attribute : null), (int)Context::getContext()->shop->id) <= 0)
						$oos = true;

				if (Configuration::get('ADOD_FORCE_STOCK'))
					$oos = false;

				$product_extra_datas = $this->_getProductExtraTime($product->id, ($id_product_attribute ? (int)$id_product_attribute : null));
				
				if ($oos)
					$product_extra_time = $product_extra_datas['out_stock'];
				else
					$product_extra_time = $product_extra_datas['in_stock'];
					
				$available_date = $this->getAvailableDate($product->id, ($id_product_attribute ? (int)$id_product_attribute : null), (int)Context::getContext()->shop->id);

				$date_range = $this->_getDatesOfDelivery($id_carrier, $oos, $available_date, $product_extra_time);

				if (isset($date_range) && (is_null($date_from) || $date_from < $date_range[0]))
				{
					$date_from = $date_range['delivery_date']['date_min'];
					$dates_delivery[$id_address][$id_carrier][$product->id.'_'.$id_product_attribute][0] = array(
						'date_min' => $date_range['delivery_date']['date_min'],
						'time_min' => $date_range['delivery_date']['time_min']
					);
				}
				if (isset($date_range) && (is_null($date_to) || $date_to < $date_range[1]))
				{
					$date_to = $date_range['delivery_date']['date_max'];
					$dates_delivery[$id_address][$id_carrier][$product->id.'_'.$id_product_attribute][1] = array(
						'date_max' => $date_range['delivery_date']['date_max'],
						'time_max' => $date_range['delivery_date']['time_max']
					);
				}
			}

			$carrierList[$key]['delivery_date'] = $dates_delivery;
		}

		foreach ($carrierList as $key => $carrier)
		{
			$dates_delivery = array();
			$id_carrier = $carrier['id_carrier'];
	
			$carrier = new Carrier((int)$id_carrier);
			$id_carrier_reference = $carrier->id_reference;

			$carrier_rule = $this->getCarrierRuleWithIdCarrier((int)($id_carrier_reference));

			if (!empty($carrier_rule))
			{
				if (!$carrier_rule['active'])
					unset($carrierList[$key]);
			}
		}
		
		return $carrierList;
	}
	
	public function getAvailableDate($id_product, $id_product_attribute = null)
    {
        $sql = 'SELECT';

        if ($id_product_attribute === null) {
            $sql .= ' p.`available_date`';
        } else {
            $sql .= ' IF(pa.`available_date` = "0000-00-00", p.`available_date`, pa.`available_date`) AS available_date';
        }

        $sql .= ' FROM `'._DB_PREFIX_.'product` p';

        if ($id_product_attribute !== null) {
            $sql .= ' LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.`id_product` = p.`id_product`)';
        }

        $sql .= Shop::addSqlAssociation('product', 'p');

        if ($id_product_attribute !== null) {
            $sql .= Shop::addSqlAssociation('product_attribute', 'pa');
        }

        $sql .= ' WHERE p.`id_product` = '.(int)$id_product;

        if ($id_product_attribute !== null) {
            $sql .= ' AND pa.`id_product` = '.(int)$id_product.' AND pa.`id_product_attribute` = '.(int)$id_product_attribute;
        }

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);

        if ($result == '0000-00-00') {
            $result = null;
        }

        return $result;
    }
	
	public function getCarriersListByIdZone($id_product, $id_country, $id_state = 0, $zipcode = 0)
	{
		if (!$id_country)
			$id_country = Context::getContext()->country->id;

		$id_zone = 0;
		if ($id_state != 0)
			$id_zone = State::getIdZone($id_state);
		if (!$id_zone)
			$id_zone = Country::getIdZone($id_country);

		$carriers = $this->getCarriersByCountryAndProduct($id_product, $id_country, $id_state, $zipcode, Context::getContext()->cart, Context::getContext()->customer->id);

		return (sizeof($carriers) ? $carriers : array());
	}
	
	public function getCarriersByCountryAndProduct($id_product, $id_country, $id_state, $zipcode, $exiting_cart, $id_customer)
	{
		// Create temporary Address
		$addr_temp = new Address();
		$addr_temp->id_customer = $id_customer;
		$addr_temp->id_country = $id_country;
		$addr_temp->id_state = $id_state;
		$addr_temp->postcode = $zipcode;

		// Populate required attributes
		// Note: Some carrier needs the whole address
		// the '.' will do the job
		$addr_temp->firstname = ".";
		$addr_temp->lastname = ".";
		$addr_temp->address1 = ".";
		$addr_temp->city = ".";
		$addr_temp->dni = ".";
		$addr_temp->phone = '0000000000';
		$addr_temp->phone_mobile = '0000000000';
		$addr_temp->alias = "TEMPORARY_ADDRESS_TO_DELETE";
		$addr_temp->active = false;
		$addr_temp->save();

		$cart = new Cart();
		$cart->id_currency = $exiting_cart->id_currency;
		$cart->id_lang = $exiting_cart->id_lang;
		$cart->id_address_delivery = $addr_temp->id;
		$cart->add();

		$product = new Product((int)$id_product);
		$minimal_quantity = (int)$product->minimal_quantity;

		$cart->updateQty($minimal_quantity, $id_product);

		$delivery_option_list = $cart->getDeliveryOptionList(null, true);

		$carriers = array();

		if ($delivery_option_list)
			foreach (reset($delivery_option_list) as $option)
			{
				$price = $option['total_price_with_tax'];
				$price_tax_exc = $option['total_price_without_tax'];

				if ($option['unique_carrier'])
				{
					$carrier = reset($option['carrier_list']);
					$id_carrier = $carrier['instance']->id;
					$name = $carrier['instance']->name;
					$img = $carrier['logo'];
					$delay = $carrier['instance']->delay;
					$delay = isset($delay[Context::getContext()->language->id]) ? $delay[Context::getContext()->language->id] : $delay[(int)Configuration::get('PS_LANG_DEFAULT')];
				}
				else
				{
					$nameList = array();
					foreach ($option['carrier_list'] as $carrier)
						$nameList[] = $carrier['instance']->name;
					$name = join(' -', $nameList);
					$id_carrier = $carrier['instance']->id;
					$img = ''; // No images if multiple carriers
					$delay = '';
				}
				$carriers[] = array(
					'name' => $name,
					'img' => $img,
					'delay' => $delay,
					'price' => $price,
					'price_tax_exc' => $price_tax_exc,
					'id_carrier' => $id_carrier, // Need to translate to an integer for retrocompatibility reason, in 1.4 template we used intval
					'is_module' => false,
				);
			}

		//delete temporary objects
		$addr_temp->delete();
		$cart->delete();

		return $carriers;
	}
	
	public function _getDatesOfDelivery($id_carrier, $product_oos = false, $date = null, $product_extra_time = null, $ordered = false)
    {
		$add_processing_stock = 0;
		$add_extra_processing_shop = 0;
		$add_hour_limit_day = 0;
		$add_closed_day = 0;

		$adod_closing_days = Configuration::get('ADOD_CLOSING_DAYS');
		$values_restrictions_closed = explode(',', $adod_closing_days);
		$values_restrictive_dates = explode(',', Configuration::get('ADOD_HOLIDAYS'));

		if ($product_oos)
			$add_processing_stock = Configuration::get('ADOD_SHOP_PROCESSING_OUTSTOCK');
		else
			$add_processing_stock = Configuration::get('ADOD_SHOP_PROCESSING_INSTOCK');

		if (Configuration::get('ADOD_EXTRA_SHOP_PROCESSING'))
			$add_extra_processing_shop = Configuration::get('ADOD_EXTRA_SHOP_PROCESSING');

		// DELAI TRANSPORTEUR
		$carrier = new Carrier((int)$id_carrier);
		$id_carrier_reference = $carrier->id_reference;

		$carrier_rule = $this->getCarrierRuleWithIdCarrier((int)($id_carrier_reference));

		if (empty($carrier_rule))
		{
			$carrier_rule['processing_days_min'] = 2;
			$carrier_rule['processing_days_max'] = 3;
			$carrier_rule['hour_limit'] = '12:00:00';
			$carrier_rule['delivery_days'] = '1,2,3,4,5';
			$carrier_rule['is_active'] = true;
		}

		if ($date != null && Validate::isDate($date) && strtotime($date) > time())
			$date_now = strtotime($date);
		elseif ($date != null && Validate::isDate($date) && $ordered)
			$date_now = strtotime($date);
		else
			$date_now = time(); // Date on timestamp format

		if (date('H:i:s', $date_now) > $carrier_rule['hour_limit'])
			$add_hour_limit_day = true;
		else
			if (in_array(date('w', $date_now), $values_restrictions_closed) || in_array(date('n_j', $date_now), $values_restrictive_dates))
				$add_closed_day = true;

		// DATE EXTRA
		$date_extra_shipping = $add_processing_stock + $add_extra_processing_shop + $add_hour_limit_day + $product_extra_time + $add_closed_day;
		$date_extra_delivery_min = $carrier_rule['processing_days_min'];
		$date_extra_delivery_max = $carrier_rule['processing_days_max'];

		// JOURS FERIES
		$shipping_time = $this->getRestrictionDates($date_now, $date_extra_shipping, false);		
		$delivery_time_min = $this->getRestrictionDates($shipping_time, $date_extra_delivery_min, $carrier_rule);
		$delivery_time_max = $this->getRestrictionDates($shipping_time, $date_extra_delivery_max, $carrier_rule);

		// CONVERT DATES TO WANTED FORMAT
		$shipping_date = $this->change_date_format($shipping_time);
		$delivery_date_min = $this->change_date_format($delivery_time_min);
		$delivery_date_max = $this->change_date_format($delivery_time_max);

		$array_dates = array(
			'shipping_date' => $shipping_date,
			'delivery_date' => array('date_min' => $delivery_date_min, 'time_min' => $delivery_time_min, 'date_max' => $delivery_date_max, 'time_max' => $delivery_time_max)
		);

		return $array_dates;
	}

	private function change_date_format($date)
	{
		/*
		// Do not remove this commentary, it's usefull to allow translations of months and days in the translator tool

		$this->l('Sunday');
		$this->l('Monday');
		$this->l('Tuesday');
		$this->l('Wednesday');
		$this->l('Thursday');
		$this->l('Friday');
		$this->l('Saturday');

		$this->l('January');
		$this->l('February');
		$this->l('March');
		$this->l('April');
		$this->l('May');
		$this->l('June');
		$this->l('July');
		$this->l('August');
		$this->l('September');
		$this->l('October');
		$this->l('November');
		$this->l('December');
		*/

		$new_date = '';

		$date_format = preg_split('/([a-z])/Ui', Configuration::get('ADOD_DATE_FORMAT', (int)Context::getContext()->language->id), null, PREG_SPLIT_DELIM_CAPTURE);
		foreach ($date_format as $elmt)
		{
			if ($elmt == 'l' || $elmt == 'F')
			{
				$new_date .= $this->l(date($elmt, $date));
			}
			elseif (preg_match('/[a-z]/Ui', $elmt))
			{
				$new_date .= date($elmt, $date);
			}
			else
			{
				$new_date .= $elmt;
			}
		}

		return $new_date;
	}

	public function getCarrierRuleWithIdCarrier($id_carrier)
	{
		if (!(int)($id_carrier))
			return false;

		return Db::getInstance()->getRow('
		SELECT * 
		FROM `'._DB_PREFIX_.'adod_carriers` 
		WHERE `id_carrier_reference` = '.(int)($id_carrier)
		);
	}

	public static function getRestrictionDates($iDate, $iDays, $carrier_rule = false)
	{
		$values_restrictions_exp = explode(',', Configuration::get('ADOD_CLOSING_DAYS'));
		$values_restrictions_del = array();
		$restrictive_dates = explode(',', Configuration::get('ADOD_HOLIDAYS'));
		$array_week = array('0','1','2','3','4','5','6');

		if ($carrier_rule)
		{
			$delivery_days = explode(',', $carrier_rule['delivery_days']);
			$array_week = array('0','1','2','3','4','5','6');

			for ($i = 0; $i < count($array_week); $i++)
			{
				if (!in_array($array_week[$i], $delivery_days))
					$values_restrictions_del[] = $array_week[$i];
			}

			$iEnd = $iDays * 86400;

			// DELIVERY
			$i = 0;
			while ($i < $iEnd) {
				$i = strtotime('+1 day', $i);
				if (in_array(date('w', $iDate+$i), $values_restrictions_del) || in_array(date('n_j', $iDate+$i), $restrictive_dates)) {
					$iEnd = strtotime('+1 day', $iEnd);
					$iDays ++;
				}
			}

			$stampFin = $iDate + $iEnd;
		}
		else
		{
			// stamp théorique de fin
			$iEnd = $iDays * 86400;

			// EXPEDITION
			$i = 0;
			while ($i < $iEnd) {
				$i = strtotime('+1 day', $i);
				if (in_array(date('w', $iDate+$i), $values_restrictions_exp) || in_array(date('n_j', $iDate+$i), $restrictive_dates)) {
					$iEnd = strtotime('+1 day', $iEnd);
					$iDays ++;
				}
			}

			$stampFin = $iDate + $iEnd;
		}

		return ($stampFin);
	}
	
	public function ajaxProcessUpdateProductExtraTime()
	{
		$product = new Product((int)Tools::getValue('id_product'), true);

		$this->_setProductExtraTime($product->id, (int)Tools::getValue('id_product_attribute'), (int)Tools::getValue('value_min'), (int)Tools::getValue('value_max'));

		die(Tools::jsonEncode(array('error' => false)));
	}
	
	private function _setProductExtraTime($id_product, $id_product_attribute, $in_stock, $out_stock)
	{
		if (!Validate::isUnsignedId($id_product))
			return false;

		$product_extra_time = $this->_getProductExtraTime($id_product, $id_product_attribute);

		if ($product_extra_time)
			Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'adod_product` SET `in_stock` = '.(int)$in_stock.', `out_stock` = '.(int)$out_stock.' WHERE `id_product` = '.(int)$id_product.($id_product_attribute ? ' AND `id_product_attribute` = '.(int)$id_product_attribute.'': ''));
		else
			Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'adod_product` (`id_product`, `id_product_attribute`, `in_stock`, `out_stock`) VALUES ('.(int)($id_product).', '.(int)($id_product_attribute).', '.(int)($in_stock).', '.(int)($out_stock).')');
	}
}