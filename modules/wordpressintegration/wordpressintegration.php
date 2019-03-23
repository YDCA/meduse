<?php
/**
 * WordPress Integration module
 *
 * @author Jonathan Gaudé
 * @copyright 2018
 * @license Commercial
 */

if (!defined('_PS_VERSION_'))
	exit;

require_once(dirname(__FILE__).'/classes/Helper.php');

class WordpressIntegration extends Module
{
	public function __construct()
	{
		$this->name = 'wordpressintegration';
		$this->tab = 'front_office_features';
		$this->version = '1.0.2';
		$this->author = 'Jonathan Gaudé';
		$this->module_key = '6dbc5c832653677a2317f34a0919d298';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array('min' => '1.5.6.2', 'max' => _PS_VERSION_); 
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('WordPress Integration');
		$this->description = $this->l('Include your WordPress blog\'s latest posts to your shop.');
	}
	
	public function install()
	{
		if (!parent::install()
			|| !$this->registerHook('header')
			|| !$this->registerHook('displayWordpressIntegration')
			|| !$this->registerHook('displayHome')
			|| !Configuration::updateValue('wordpressintegration_blog_title', Configuration::get('PS_SHOP_NAME'))
			|| !Configuration::updateValue('wordpressintegration_blog_url', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/blog')
			|| !Configuration::updateValue('wordpressintegration_nb_posts_allowed', 5)
			|| !Configuration::updateValue('wordpressintegration_description_max_chars', 120)
			|| !Configuration::updateValue('wordpressintegration_display_images', 0))
			return false;
		return true;
	}
	
	public function uninstall()
	{
		if (!parent::uninstall()
			|| !Configuration::deleteByName('wordpressintegration_blog_title')
			|| !Configuration::deleteByName('wordpressintegration_blog_url')
			|| !Configuration::deleteByName('wordpressintegration_nb_posts_allowed')
			|| !Configuration::deleteByName('wordpressintegration_description_max_chars')
			|| !Configuration::deleteByName('wordpressintegration_display_images'))
			return false;
		return true;
	}
	
	public function hookHeader()
	{
		$this->context->controller->addCSS($this->_path.'/views/css/front/wordpressintegration.css', 'all');
	}
	
	public function hookDisplayHome($params)
	{
		return $this->hookDisplayWordpressIntegration($params);
	}
	
	public function hookDisplayWordpressIntegration($params)
	{
		$blog_title = Configuration::get('wordpressintegration_blog_title');
		$blog_url = Configuration::get('wordpressintegration_blog_url');
		$nb_posts_allowed = Configuration::get('wordpressintegration_nb_posts_allowed');
		$description_max_chars = Configuration::get('wordpressintegration_description_max_chars');
		$display_images = Configuration::get('wordpressintegration_display_images');
		
		$blog_feed_url = $blog_url . ((Tools::substr($blog_url, -1) == '/') ? '' : '/') . 'feed/'; // WordPress posts feed URL
		$xml_string = Tools::file_get_contents($blog_feed_url);
		
		// If the URL is unreachable, it might be because allow_url_fopen is set to false.
		// Try with cURL
		if (!$xml_string)
		{
			$xml_string = WordpressIntegration\Helper::curlGetContents($blog_feed_url);
			
			// If the URL is still not reachable, return an error
			if (!$xml_string)
			{
				$this->context->smarty->assign(array(
					'wpi_errors' => $this->l("Unable to reach the WordPress XML feed.")
				));
				return ($this->display(__FILE__, '/views/templates/front/error.tpl'));
			}
		}
		
		// Disable libxml errors
		libxml_use_internal_errors(true);

		// Fix illegal characters
		$xml_string = str_replace(
			array('&amp;', '&laquo;', '&raquo;', '&rsquo;'),
			array('&', '«', '»', '\''),
			$xml_string);
		
		// Try to load the XML data into a string
		$xml = simplexml_load_string($xml_string);
		
		// If the data is not valid XML, return an error
		if (!$xml)
		{
			
			if ($_SERVER['REMOTE_ADDR'] == '92.95.72.62')
			{
			$xml_errors = array();
			foreach (libxml_get_errors() as $xml_error)
				$xml_errors[] = WordpressIntegration\Helper::displayXmlError($xml_error);
				$xml_errors[] = getcwd();
			}
			// file_put_contents ('/$xml_string);
			$this->context->smarty->assign(array(
				'wpi_errors' => $xml_errors
			));
			return ($this->display(__FILE__, '/views/templates/front/error.tpl'));
		}
		
		// Create a new XML object
		$xml = new SimpleXMLElement($xml_string);
		
		// Retrieve all posts (SimpleXML only makes it an array if there is > 1 post)
		$xml_posts = count($xml->channel->item) > 1 ? $xml->channel->item : array($xml->channel->item);
		// echo "<pre>";var_dump($xml->channel); die();
		
		$blog_posts = array();
		foreach($xml_posts as $post)
		{
			$blog_posts[] = array(
				'title' => (string)$post->title,
				'link' => (string)$post->link,
				'image' => isset($post->image) ? (string)$post->image : '',
				'date_created' => strtotime((string)$post->pubDate),
				'description' => (string)html_entity_decode($post->description)
			);
			
			if (count($blog_posts) >= $nb_posts_allowed) break;
		}
		// echo "<pre>";var_dump($blog_posts); die();
		
		$this->context->smarty->assign(array(
			'blog_title' => $blog_title,
			'blog_url' => $blog_url,
			'blog_posts' => $blog_posts,
			'description_max_chars' => !empty($description_max_chars) && is_numeric($description_max_chars) ? $description_max_chars : 9999,
			'display_images' => $display_images,
		));
		
		return ($this->display(__FILE__, '/views/templates/front/display_blog_posts.tpl'));
	}
	
	public function getContent()
	{
		$output = null;
		
		if (Tools::isSubmit('submit' . $this->name))
		{
			$blog_title = (string)Tools::getValue('wordpressintegration_blog_title');
			$blog_url = (string)Tools::getValue('wordpressintegration_blog_url');
			$nb_posts_allowed = (string)Tools::getValue('wordpressintegration_nb_posts_allowed');
			$description_max_chars = (string)Tools::getValue('wordpressintegration_description_max_chars');
			$display_images = (string)Tools::getValue('wordpressintegration_display_images');
			
			if (!$blog_url || empty($blog_url)
				|| !$nb_posts_allowed || empty($nb_posts_allowed) || !is_numeric($nb_posts_allowed)
				|| !is_numeric($description_max_chars)
				|| !is_numeric($display_images))
				$output .= $this->displayError($this->l('Invalid configuration value'));
			else
			{
				Configuration::updateValue('wordpressintegration_blog_title', $blog_title);
				Configuration::updateValue('wordpressintegration_blog_url', $blog_url);
				Configuration::updateValue('wordpressintegration_nb_posts_allowed', $nb_posts_allowed);
				Configuration::updateValue('wordpressintegration_description_max_chars', $description_max_chars);
				Configuration::updateValue('wordpressintegration_display_images', $display_images);
				$output .= $this->displayConfirmation($this->l('Settings updated'));
			}
		}
		
		// Check if SimpleXML is installed
		if (!extension_loaded('simplexml'))
			$output .= $this->displayError($this->l("Error detected ! The SimpleXML extension isn't loaded ; please check your setup."));
		
		// Check if allow_url_fopen is enabled
		if (!ini_get('allow_url_fopen'))
			$output .= $this->displayWarning($this->l("Warning : the allow_url_fopen PHP directive is not enabled on this server."));
		
		// Check if cURL is enabled
		if (!function_exists('curl_version'))
			$output .= $this->displayWarning($this->l("Warning : cURL is not enabled on this server."));

		$output .= $this->displayForm();
		
		return $output;
	}
	
	public function displayForm()
	{
		// Get default language
		// $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		$default_lang = (int)$this->context->language->id;
		
		// Documentation path
		$documentation_url = _MODULE_DIR_ . $this->name . '/readme_' . ($this->context->language->iso_code == 'fr' ? 'fr' : 'en') . '.pdf';

		// Init Fields form array
		$fields_form = array();
		$fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Settings') ,
				'icon' => 'icon-cogs'
			) ,
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Blog title :') ,
					'name' => 'wordpressintegration_blog_title',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'text',
					'label' => $this->l('WordPress blog URL :') ,
					'name' => 'wordpressintegration_blog_url',
					'size' => 20,
					'required' => true
				),
				array(
					'type' => 'text',
					'label' => $this->l('Number of posts to display :') ,
					'name' => 'wordpressintegration_nb_posts_allowed',
					'size' => 20,
					'required' => true
				),
				array(
					'type' => 'text',
					'label' => $this->l('Max. number of characters before the description is truncated :') ,
					'name' => 'wordpressintegration_description_max_chars',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Display blog post images :'),
					'desc' => $this->l('Please follow the instructions in the')
								. ' <a href="' . $documentation_url . '">' . $this->l('documentation') . '</a> ' . $this->l('in order to set up automatic retrieval of post thumbnails.'),
					'name' => 'wordpressintegration_display_images',
					'is_bool' => true,
					'required' => true,
					'values' => array(
						array(
							'id' => 'wordpressintegration_display_images_yes',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'wordpressintegration_display_images_no',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					)
				)
			) ,
			'submit' => array(
				'title' => $this->l('Save')
			)
		);
		$helper = new HelperForm();

		// Module, token and currentIndex
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

		// Language
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;

		// Title and toolbar
		$helper->title = $this->displayName;
		$helper->show_toolbar = true; // false -> remove toolbar
		$helper->toolbar_scroll = true; // yes - > Toolbar is always visible on the top of the screen.
		$helper->submit_action = 'submit' . $this->name;
		$helper->toolbar_btn = array(
			'save' => array(
				'desc' => $this->l('Save') ,
				'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules')
			) ,
			'back' => array(
				'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules') ,
				'desc' => $this->l('Back to list')
			)
		);

		// Load current value
		$helper->fields_value['wordpressintegration_blog_title'] = Configuration::get('wordpressintegration_blog_title');
		$helper->fields_value['wordpressintegration_blog_url'] = Configuration::get('wordpressintegration_blog_url');
		$helper->fields_value['wordpressintegration_nb_posts_allowed'] = Configuration::get('wordpressintegration_nb_posts_allowed');
		$helper->fields_value['wordpressintegration_description_max_chars'] = Configuration::get('wordpressintegration_description_max_chars');
		$helper->fields_value['wordpressintegration_display_images'] = Configuration::get('wordpressintegration_display_images');
		
		return $helper->generateForm($fields_form);
	}
}
