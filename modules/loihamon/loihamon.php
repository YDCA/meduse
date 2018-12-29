<?php
/**
 * Loi Hamon Prestashop module
 *
 * @author    Prestaddons <contact@prestaddons.fr>
 * @copyright 2014 Prestaddons
 * @license
 * @link      http://www.prestaddons.fr
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(dirname(__FILE__).'/classes/HTMLTemplateCMS.php');

class LoiHamon extends Module
{
    /** @var string html Output */
    protected $html = '';

    /** @var array post_errors Errors on forms */
    protected $post_errors = array();

    /** @var bool $is_version15 Prestashop is under 1.5 version */
    public $is_version15;

    /** @var bool $is_version16 Prestashop is under 1.6 version */
    public $is_version16;

    /** @var bool $is_version16 Prestashop is under 1.6 version */
    public $is_version17;

    /**
     * Constructeur de la classe Loi Hamon
     */
    public function __construct()
    {
        $this->name = 'loihamon';
        $this->short_name = 'lh';
        $this->tab = 'checkout';
        $this->version = '1.7.1';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7');
        $this->controllers = array('retractation');
        $this->bootstrap = true;
        $this->module_key = '3cb722a97e795c4235f474c9bebcedd9';

        parent::__construct();

        $this->displayName = $this->l('Loi Hamon');
        $this->description = $this->l('Add a retraction form to your shop to be in accordance with the Hamon law');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the Hamon law module?');
        $this->author = $this->l('Prestaddons');
        $this->contact = 'contact@prestaddons.fr';
        $this->addons_url = 'https://addons.prestashop.com/contact-form.php?id_product=17161';
        $this->is_version15 = $this->checkPSVersion();
        $this->is_version16 = $this->checkPSVersion('1.6.0.0');
        $this->is_version17 = $this->checkPSVersion('1.7.0.0');
    }

    /**
     * Méthode install()
     *
     * Gère l'installation du module
     *
     * @return bool True si l'installation a fonctionné, false dans le cas contraire
     */
    public function install()
    {
        if ($this->is_version15 && Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        $params = $this->installFixtures();

        if (!parent::install()
            || !$this->registerHook('customerAccount')
            || !$this->registerHook('displayBackOfficeHeader')
            || !Configuration::updateValue($this->short_name.'_withdrawal_delay', 14)
            || !Configuration::updateValue($this->short_name.'_contact_form', 1)
            || !Configuration::updateValue($this->short_name.'_upload_field', 1)
            || !Configuration::updateValue($this->short_name.'_cms_attachment', 1)
            || !Configuration::updateValue($this->short_name.'_id_cms', 1)
            || !Configuration::updateValue($this->short_name.'_meta_title', rawurlencode(serialize($params['meta_title'])))
            || !Configuration::updateValue($this->short_name.'_meta_description', rawurlencode(serialize($params['meta_description'])))
            || !Configuration::updateValue($this->short_name.'_top_message', rawurlencode(serialize($params['top_message'])))) {
            return false;
        }

        return true;
    }

    /**
     * Install overrides files for the module
     *
     * @return bool
     */
    public function installOverrides()
    {
        $this->createOverride();

        return parent::installOverrides();
    }

    /**
     * Create PaymentModule Class override from the native class
     *
     * @return void
     */
    private function createOverride()
    {
        // Get the validateOrder() method content
        $method = new ReflectionMethod('PaymentModuleCore', 'validateOrder');
        $start_line = $method->getStartLine() - 1;
        $method_length = $method->getEndLine() - $start_line;
        $source_file = file(_PS_CLASS_DIR_.'PaymentModule.php');
        $method_content = implode('', array_slice($source_file, $start_line, $method_length));

        // Add Hamon law changes to the validateOrder() method
        if ($this->checkPSVersion('1.6.1.1', '>=')) {
            $cursor = 26;
            $filename = 'validateorder_changes.txt';
        } else {
            $cursor = 0;
            $filename = 'validateorder_changes_15.txt';
        }

        $method_content = explode('$file_attachement = null;', $method_content);
        $method_content[1] = Tools::substr($method_content[1], $cursor, Tools::strlen($method_content[1]));
        $patch_module = Tools::file_get_contents(_PS_MODULE_DIR_.$this->name.'/classes/'.$filename);
        $method_content = $method_content[0].$patch_module.$method_content[1];

        // Generates override file
        if ($this->checkPSVersion('1.7.2.0', '>=')) {
            $template_class = fopen(_PS_MODULE_DIR_.$this->name.'/classes/PaymentModuleWithUse.php', 'r');
            $start_line = 14;
        } else {
            $template_class = fopen(_PS_MODULE_DIR_.$this->name.'/classes/PaymentModule.php', 'r');
            $start_line = 13;
        }

        $override_class = fopen(_PS_MODULE_DIR_.$this->name.'/override/classes/PaymentModule.php', 'w');

        $i = 1;
        while (!feof($template_class)) {
            $line_read = fgets($template_class, 4096);
            if ($i == $start_line) {
                fwrite($override_class, $method_content);
            } else {
                fwrite($override_class, $line_read);
            }

            $i++;
        }
        fclose($template_class);
        fclose($override_class);
    }

    /**
     * Méthode uninstall()
     *
     * Gère la désinstallation du module
     *
     * @return bool True si la désinstallation a fonctionné, false dans le cas contraire
     */
    public function uninstall()
    {
        if (!parent::uninstall()
                || !Configuration::deleteByName($this->short_name.'_withdrawal_delay')
                || !Configuration::deleteByName($this->short_name.'_contact_form')
                || !Configuration::deleteByName($this->short_name.'_upload_field')
                || !Configuration::deleteByName($this->short_name.'_cms_attachment')
                || !Configuration::deleteByName($this->short_name.'_id_cms')
                || !Configuration::deleteByName($this->short_name.'_meta_title')
                || !Configuration::deleteByName($this->short_name.'_meta_description')
                || !Configuration::deleteByName($this->short_name.'_top_message')) {
            return false;
        }

        if (is_file(_PS_MODULE_DIR_.$this->name.'/override/classes/PaymentModule.php')) {
            unlink(_PS_MODULE_DIR_.$this->name.'/override/classes/PaymentModule.php');
        }
        
        return true;
    }

    /**
     * Méthode installFixtures()
     *
     * Initialise tous les paramètres nécessaires à l'installation du module
     *
     * @return array $params Tableau contenant les paramètres nécessaires à l'installation
     */
    private function installFixtures()
    {
        $meta_title = array(
            'en' => 'Withdrawal form',
            'fr' => 'Formulaire de rétractation',
            'pt' => 'Solicitar uma retirada'
        );
        $meta_description = array(
            'en' => 'Use this form to make a withdrawal request',
            'fr' => 'Utilisez ce formulaire pour faire une demande de rétractation',
            'pt' => 'Sob a Lei de Hamon, você pode solicitar uma retirada para o nosso Atendimento ao Cliente no prazo de 14 dias de sua encomenda.'
        );
        $top_message = array(
            'en' => '<p>According to the Hamon Law, you can send a withdrawal request to our Customer Service, within 14 days after your order.</p>',
            'fr' => '<p>Conformément à la Loi Hamon, vous pouvez envoyer une demande de rétractation à notre Service Client, 
					dans les 14 jours qui suivent votre commande.</p>',
            'pt' => '<p>Sob a Lei de Hamon, você pode solicitar uma retirada para o nosso Atendimento ao Cliente no prazo de 14 dias de sua encomenda.</p>'
        );

        $languages = Language::getLanguages(false);

        $meta_tile_tmp = array();
        $meta_description_tmp = array();
        $top_message_tmp = array();

        foreach ($languages as $language) {
            $meta_tile_tmp[$language['id_lang']] = (isset($meta_title[$language['iso_code']]) ? $meta_title[$language['iso_code']] : '');
            $meta_description_tmp[$language['id_lang']] = (isset($meta_description[$language['iso_code']]) ? $meta_description[$language['iso_code']] : '');
            $top_message_tmp[$language['id_lang']] = (isset($top_message[$language['iso_code']]) ? $top_message[$language['iso_code']] : '');
        }

        $params = array(
            'meta_title' => $meta_tile_tmp,
            'meta_description' => $meta_description_tmp,
            'top_message' => $top_message_tmp
        );

        return $params;
    }

    /**
     * Méthode postValidation()
     *
     * Contrôle les variables saisies dans le backoffice et définit les éventuelles erreurs à afficher
     *
     * @return string HTML du résultat de la vérification (message d'erreur éventuel)
     */
    private function postValidation()
    {
        if (Tools::isSubmit('submit'.$this->name)) {
            if (!Validate::isUnsignedInt(Tools::getValue('withdrawal_delay'))) {
                $this->post_errors[] = $this->l('The withdrawal delay must be a positive integer');
            }
        }
    }

    /**
     * Méthode postProcess()
     *
     * Traitement des informations saisies dans le backoffice
     * Traitements divers, mise à jour la base de données, définition des messages d'erreur ou de confirmation...
     *
     * @return string HTML du résultat du traitement (message d'erreur ou de confirmation)
     */
    private function postProcess()
    {
        if (Tools::isSubmit('submit'.$this->name)) {
            $languages = Language::getLanguages(false);
            $meta_title = array();
            $meta_description = array();
            $top_message = array();
            foreach ($languages as $language) {
                $meta_title[$language['id_lang']] = Tools::getValue('meta_title_'.$language['id_lang']);
                $meta_description[$language['id_lang']] = Tools::getValue('meta_description_'.$language['id_lang']);
                if (Validate::isCleanHtml(Tools::getValue('top_message_'.$language['id_lang']))) {
                    $top_message[$language['id_lang']] = Tools::getValue('top_message_'.$language['id_lang']);
                } else {
                    $top_message[$language['id_lang']] = '';
                }
            }

            Configuration::updateValue($this->short_name.'_withdrawal_delay', Tools::getValue('withdrawal_delay'));
            Configuration::updateValue($this->short_name.'_contact_form', Tools::getValue('contact_form'));
            Configuration::updateValue($this->short_name.'_upload_field', Tools::getValue('upload_field'));
            Configuration::updateValue($this->short_name.'_cms_attachment', Tools::getValue('cms_attachment'));
            Configuration::updateValue($this->short_name.'_id_cms', Tools::getValue('id_cms'));
            Configuration::updateValue($this->short_name.'_meta_title', rawurlencode(serialize($meta_title)));
            Configuration::updateValue($this->short_name.'_meta_description', rawurlencode(serialize($meta_description)));
            Configuration::updateValue($this->short_name.'_top_message', rawurlencode(serialize($top_message)));

            $this->html .= $this->displayConfirmation($this->l('Settings have been updated'));
        }
    }

    /**
     * Méthode getContent()
     *
     * Gère l'administration du module dans le backoffice
     * Dispatch vers les différentes méthodes en fonctions des cas (affichage des formulaires, des erreurs, des confirmations, ...)
     *
     * @return string HTML de la partie backoffice du module
     */
    public function getContent()
    {
        $this->postValidation();

        if (!isset($this->post_errors) || !count($this->post_errors)) {
            $this->postProcess();
        } else {
            foreach ($this->post_errors as $err) {
                $this->html .= $this->displayError($err);
            }
        }

        if (Tools::isSubmit('support'.$this->name)) {
            $this->html .= $this->renderSupportForm();
        } else {
            $this->html .= $this->getButtonsTpl().$this->renderForm();
        }

        return $this->html;
    }

    /**
     * Méthode renderForm()
     *
     * Affiche le formulaire principale du module dans le backoffice
     *
     * @return string HTML du backoffice du module
     */
    public function renderForm()
    {
        // Get default Language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $contacts = Contact::getContacts($this->context->language->id);
        $contact_options = array();
        foreach ($contacts as $contact) {
            $contact_options[] = array(
                'id_option' => $contact['id_contact'],
                'name' => $contact['name'].' ('.$contact['email'].')'
            );
        }

        $cms_pages = CMS::getCMSPages($this->context->language->id);
        $cms_options = array();
        foreach ($cms_pages as $cms) {
            $cms_options[] = array(
                'id_option' => $cms['id_cms'],
                'name' => $cms['meta_title']
            );
        }

        // Init Fields form array
        $fields_form = array();
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
                (!$this->is_version16) ? 'image' : 'icon' => (!$this->is_version16) ? _MODULE_DIR_.$this->name.'/views/img/settings_16x16.png' : 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Withdrawal delay'),
                    'desc' => $this->l('Set the delay during the customer can make a withdrawal request'),
                    'name' => 'withdrawal_delay',
                    'suffix' => $this->l('days'),
                    'required' => true,
                    'size' => 50, //only 1.5
                    'class' => 'fixed-width-xl' //only 1.6
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Contact form'),
                    'name' => 'contact_form',
                    'desc' => $this->l('Contact for retraction request'),
                    'required' => true,
                    'options' => array(
                        'query' => $contact_options,
                        'id' => 'id_option',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => ($this->is_version16) ? 'switch' : 'radio',
                    'label' => $this->l('Upload field'),
                    'desc' => $this->l('Add an upload field in the retraction form'),
                    'name' => 'upload_field',
                    'class' => 't', //only 1.5
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'upload_field_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'upload_field_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => ($this->is_version16) ? 'switch' : 'radio',
                    'label' => $this->l('CMS as attachment'),
                    'desc' => $this->l('Send a CMS page content as an attachment when an order is placed'),
                    'name' => 'cms_attachment',
                    'class' => 't', //only 1.5
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'cms_attachment_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'cms_attachment_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('CMS page'),
                    'name' => 'id_cms',
                    'desc' => $this->l('CMS page you want customer receive when an order is placed'),
                    'required' => true,
                    'options' => array(
                        'query' => $cms_options,
                        'id' => 'id_option',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Meta title'),
                    'desc' => $this->l('Set the form meta title'),
                    'name' => 'meta_title',
                    'required' => true,
                    'size' => 50, //only 1.5
                    'lang' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Meta description'),
                    'desc' => $this->l('Set the form meta description'),
                    'name' => 'meta_description',
                    'required' => true,
                    'size' => 50, //only 1.5
                    'lang' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Top message'),
                    'desc' => $this->l('Message displayed above the retraction form'),
                    'name' => 'top_message',
                    'autoload_rte' => true,
                    'lang' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => ($this->is_version16) ? 'btn btn-default pull-right' : 'button',
                'name' => 'submit'.$this->name
            )
        );

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
        $helper->show_toolbar = true;  // false -> remove toolbar. Only 1.5
        $helper->toolbar_scroll = true;   // true -> Toolbar is always visible on the top of the screen. Only 1.5
        if (!$this->checkPSVersion('1.5.5.0')) {
            $helper->submit_action = 'submit'.$this->name;
        }

        if (!$this->is_version16) {
            // Only usefull on 1.5
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
        }

        $helper->toolbar_btn['help-new'] = array(
            'desc' => $this->l('Support'),
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&support'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules')
        );

        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        // Needed for WYSIWYG
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $this->getAddFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'image_baseurl' => $this->_path.'images/',
            'admin_returns_url' => $this->context->link->getAdminLink('AdminReturn')
        );

        return $helper->generateForm($fields_form);
    }

    public function getAddFieldsValues()
    {
        $fields = array();
        $languages = Language::getLanguages(false);

        $fields['withdrawal_delay'] = Tools::getValue('withdrawal_delay', Configuration::get($this->short_name.'_withdrawal_delay'));
        $fields['contact_form'] = Tools::getValue('contact_form', Configuration::get($this->short_name.'_contact_form'));
        $fields['upload_field'] = Tools::getValue('upload_field', Configuration::get($this->short_name.'_upload_field'));
        $fields['cms_attachment'] = Tools::getValue('cms_attachment', Configuration::get($this->short_name.'_cms_attachment'));
        $fields['id_cms'] = Tools::getValue('id_cms', Configuration::get($this->short_name.'_id_cms'));

        $meta_title = unserialize(rawurldecode(Configuration::get($this->short_name.'_meta_title')));
        $meta_description = unserialize(rawurldecode(Configuration::get($this->short_name.'_meta_description')));
        $top_message = unserialize(rawurldecode(Configuration::get($this->short_name.'_top_message')));
        foreach ($languages as $lang) {
            $fields['meta_title'][$lang['id_lang']] = isset($meta_title[$lang['id_lang']]) ? $meta_title[$lang['id_lang']] : '';
            $fields['meta_description'][$lang['id_lang']] = isset($meta_description[$lang['id_lang']]) ? $meta_description[$lang['id_lang']] : '';
            $fields['top_message'][$lang['id_lang']] = isset($top_message[$lang['id_lang']]) ? $top_message[$lang['id_lang']] : '';
        }

        return $fields;
    }

    public function renderSupportForm()
    {
        // Envoi des paramètres au template
        $this->context->smarty->assign(array(
            'path' => _MODULE_DIR_.$this->name.'/',
            'iso' => Language::getIsoById($this->context->cookie->id_lang),
            'display_name' => $this->displayName,
            'version' => $this->version,
            'author' => $this->author,
            'contact' => $this->contact,
            'back_link' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'psversion16' => $this->is_version16
        ));
        return $this->display(__FILE__, 'views/templates/admin/support.tpl');
    }

    /**
     * Méthode checkPSVersion()
     *
     * Compare la version de Prestashop passée en paramètre avec la version courante
     *
     * @param string $version Version à comparer
     * @param string $compare Sens de la comparaison
     *
     * @return boolean True si la comparaison est vérifiée
     */
    public function checkPSVersion($version = '1.5.0.0', $compare = '>')
    {
        return version_compare(_PS_VERSION_, $version, $compare);
    }

    public function hookCustomerAccount()
    {
        $this->context->smarty->assign(array(
            $this->short_name.'_path' => $this->_path,
            $this->short_name.'_psversion16' => $this->is_version16,
            $this->short_name.'_psversion17' => $this->is_version17,
            $this->short_name.'_retraction_form_link' => $this->context->link->getModuleLink($this->name, 'retractation')
        ));
        return $this->display(__FILE__, 'loihamon.tpl');
    }

    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCSS(($this->_path).'views/css/hl-admin.css', 'all');
    }
    
    private function getButtonsTpl()
    {
        $this->context->smarty->assign(array(
            'module_name' => $this->displayName,
            'support_url' => $this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&support'.$this->name,
            'addons_url' => $this->addons_url,
            'ps_version16' => $this->checkPSVersion('1.6.0.0'),
            'base_url' => _MODULE_DIR_.$this->name
        ));
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/admin/buttons.tpl');
    }
}
