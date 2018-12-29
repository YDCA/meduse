<?php
/**
 * Loi Hamon Prestashop module
 *
 * @author    Prestaddons <contact@prestaddons.fr>
 * @copyright 2014 Prestaddons
 * @license
 * @link      http://www.prestaddons.fr
 */

class LoihamonRetractationModuleFrontController extends ModuleFrontController
{
    public $auth = true;
    public $php_self = '';
    public $ssl = true;
    public $errors_list = array();

    public function setMedia()
    {
        parent::setMedia();

        if ($this->module->is_version17) {
            $this->addCSS(_MODULE_DIR_.$this->module->name.'/views/css/loihamon_17.css');
        } elseif ($this->module->is_version16) {
            $this->addCSS(_MODULE_DIR_.$this->module->name.'/views/css/loihamon_16.css');
        } else {
            $this->addCSS(_MODULE_DIR_.$this->module->name.'/views/css/loihamon_15.css');
        }

        $this->addJS(_MODULE_DIR_.$this->module->name.'/views/js/loihamon.js');
    }

    /**
     * Assign template vars related to page content
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        if ($this->module->is_version16) {
            $this->display_column_left = false;
        }

        parent::initContent();

        $this->assignOrderList();

        $customer = new Customer($this->context->cookie->id_customer);
        $address_array = $customer->getAddresses($this->context->cookie->id_lang);
        $address = '';
        $phone = '';

        if (count($address_array) > 0) {
            $address = $address_array[0]['address1']."\n".(($address_array[0]['address2'] != '') ?
                            $address_array[0]['address2']."\n" : '').$address_array[0]['postcode'].' '.$address_array[0]['city'];
            $phone = $address_array[0]['phone'];
        }

        $email = Tools::safeOutput(Tools::getValue('from', ((isset($this->context->cookie) && isset($this->context->cookie->email)
                                && Validate::isEmail($this->context->cookie->email)) ? $this->context->cookie->email : '')));

        if (Tools::isSubmit('errorNotReturnable')) {
            $this->errors_list[] = Tools::displayError($this->module->l('This order cannot be returned', 'retractation'));
        } elseif (Tools::isSubmit('errorQuantity')) {
            $this->errors_list[] = Tools::displayError($this->module->l('A withdrawal request has been already sent for the selected products', 'retractation'));
        }

        if (!$this->module->is_version17) {
            $this->errors = $this->errors_list;
            $this->context->smarty->assign(array(
                'errors' => $this->errors,
            ));
        }

        $this->context->smarty->assign(array(
            'errors' => $this->errors_list,
            'name' => $this->context->cookie->customer_firstname.' '.$this->context->cookie->customer_lastname,
            'address' => $address,
            'phone' => $phone,
            'email' => $email,
        ));

        if ($id_customer_thread = (int)Tools::getValue('id_customer_thread') && $token = Tools::getValue('token')) {
            $customer_thread = Db::getInstance()->getRow('
				SELECT cm.* 
				FROM '._DB_PREFIX_.'customer_thread cm
				WHERE cm.id_customer_thread = '.(int)$id_customer_thread.' 
				AND cm.id_shop = '.(int)$this->context->shop->id.' 
				AND token = \''.pSQL($token).'\'
			');
            $this->context->smarty->assign('customerThread', $customer_thread);
        }

        $meta_title = unserialize(rawurldecode(Configuration::get($this->module->short_name.'_meta_title')));
        $meta_description = unserialize(rawurldecode(Configuration::get($this->module->short_name.'_meta_description')));
        $top_message = unserialize(rawurldecode(Configuration::get($this->module->short_name.'_top_message')));

        $this->context->smarty->assign(array(
            'meta_title' => $meta_title[$this->context->cookie->id_lang],
            'meta_description' => $meta_description[$this->context->cookie->id_lang],
            'top_message' => $top_message[$this->context->cookie->id_lang],
            'id_contact' => Configuration::get($this->module->short_name.'_contact_form'),
            'fileupload' => Configuration::get($this->module->short_name.'_upload_field'),
            'message' => html_entity_decode(Tools::getValue('message')),
            'is_version17' => $this->module->is_version17
        ));

        if ($this->module->is_version17) {
            $this->context->smarty->assign(array(
                'is_logged' => $this->context->cookie->logged,
                'PS_CATALOG_MODE' => Configuration::get('PS_CATALOG_MODE'),
                'base_dir' => $this->context->link->getBaseLink(),
                'retraction_form_link' => $this->context->link->getModuleLink($this->module->name, 'retractation')
            ));
        }

        if ($this->module->is_version17) {
            $this->setTemplate('module:loihamon/views/templates/front/retractation_17.tpl');
        } elseif ($this->module->is_version16) {
            $this->setTemplate('retractation.tpl');
        } elseif ($this->module->is_version15) {
            $this->setTemplate('retractation_15.tpl');
        }
    }

    /**
     * Assign template vars related to order list and product list ordered by the customer
     */
    protected function assignOrderList()
    {
        if ($this->context->customer->isLogged()) {
            $this->context->smarty->assign('isLogged', 1);

            if ($this->module->checkPSVersion('1.7.0.0')) {
                $image_type = ImageType::getFormattedName('small');
            } elseif ($this->module->checkPSVersion('1.5.3.1')) {
                $image_type = ImageType::getFormatedName('small');
            } else {
                $image_type = ImageType::getByNameNType('small', 'products');
                $image_type = $image_type['name'];
            }

            $products = array();
            $withdrawal_delay = Configuration::get($this->module->short_name.'_withdrawal_delay');

            // Récupère les commandes qui ont une date de livraison plus récente que le délai de rétractation
            $sql = 'SELECT id_order, delivery_date
			FROM '._DB_PREFIX_.'orders
			WHERE id_customer = '.(int)$this->context->customer->id.'
			AND (delivery_date = "0000-00-00 00:00:00"
			OR delivery_date >= (NOW() - INTERVAL '.(int)$withdrawal_delay.' DAY))
			AND id_shop = '.(int)$this->context->shop->id.'
			ORDER BY date_add';

            $result = Db::getInstance()->executeS($sql);
            $orders = array();
            $delivery = array();
            foreach ($result as $row) {
                $order = new Order($row['id_order']);
                $date = explode(' ', $order->date_add);
                $tmp = $order->getProducts();
                $delivery[] = array(
                    'id_order' => $row['id_order'],
                    'delivery_date' => $row['delivery_date']
                );
                $i = 0;
                foreach ($tmp as $val) {
                    $rewrite_infos = Product::getUrlRewriteInformations($val['product_id']);
                    $img = $val['image'];
                    $products[$row['id_order']][$i] = array(
                        'id_order_detail' => $val['id_order_detail'],
                        'product_id' => $val['product_id'],
                        'attribute_id' => $val['product_attribute_id'],
                        'label' => $val['product_name'],
                        'quantity' => $val['product_quantity'],
                        'image_link' => $this->context->link->getImageLink($rewrite_infos[0]['link_rewrite'], $val['product_id'].'-'.$img->id_image, $image_type)
                    );
                    $i++;
                }

                $orders[] = array(
                    'value' => $order->id,
                    'label' => $order->getUniqReference().' - '.Tools::displayDate($date[0], null),
                    'selected' => (int)Tools::getValue('id_order') == $order->id
                );
            }

            $this->context->smarty->assign('smallSize', Image::getSize($image_type));
            $this->context->smarty->assign('orderList', $orders);
            $this->context->smarty->assign('orderedProductList', $products);
            $this->context->smarty->assign('deliveryList', $delivery);
        }
    }

    /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        if ($this->module->is_version16) {
            $mail_template = 'retraction';
            $mail_template_form = 'retraction_form';
        } else {
            $mail_template = 'retraction15';
            $mail_template_form = 'retraction_form15';
        }

        if (Tools::isSubmit('submitMessage')) {
            $file_attachment = null;
            if (isset($_FILES['fileUpload']['name']) && !empty($_FILES['fileUpload']['name']) && !empty($_FILES['fileUpload']['tmp_name'])) {
                $extension = array('.txt', '.rtf', '.doc', '.docx', '.pdf', '.zip', '.png', '.jpeg', '.gif', '.jpg');
                $filename = uniqid().Tools::substr($_FILES['fileUpload']['name'], -5);
                $file_attachment['content'] = Tools::file_get_contents($_FILES['fileUpload']['tmp_name']);
                $file_attachment['name'] = $_FILES['fileUpload']['name'];
                $file_attachment['mime'] = $_FILES['fileUpload']['type'];
            }
            $message = Tools::getValue('message'); // Html entities is not usefull, iscleanHtml check there is no bad html tags.
            /* if ($message == '') {
              $this->errors_list[] = Tools::displayError($this->module->l('The message is mandatory. Thanks to fill the corresponding field', 'retractation'));
              } else if (!Validate::iscleanHtml($message)) {
              $this->errors_list[] = Tools::displayError($this->module->l('The message is invalid', 'retractation'));
              } else */
            if (!($from = trim(Tools::getValue('from'))) || !Validate::isEmail($from)) {
                $this->errors_list[] = Tools::displayError($this->module->l('Invalid e-mail address', 'retractation'));
            } elseif (!Tools::getValue('id_order')) {
                $this->errors_list[] = Tools::displayError($this->module->l('Invalid Order ID', 'retractation'));
            } elseif (!Tools::getValue('ids_order_detail')) {
                $this->errors_list[] = Tools::displayError($this->module->l('At least one product must be selected', 'retractation'));
            } elseif (!trim(Tools::getValue('name'))) {
                $this->errors_list[] = Tools::displayError($this->module->l('Name cannot be blank', 'retractation'));
            } elseif (!trim(Tools::getValue('address'))) {
                $this->errors_list[] = Tools::displayError($this->module->l('Address cannot be blank', 'retractation'));
            } elseif (!empty($_FILES['fileUpload']['name']) && $_FILES['fileUpload']['error'] != 0) {
                $this->errors_list[] = Tools::displayError($this->module->l('An error occurred during the file upload', 'retractation'));
            } elseif (!empty($_FILES['fileUpload']['name']) && !in_array(Tools::substr($_FILES['fileUpload']['name'], -4), $extension)
                    && !in_array(Tools::substr($_FILES['fileUpload']['name'], -5), $extension)) {
                $this->errors_list[] = Tools::displayError($this->module->l('Bad file extension', 'retractation'));
            } else {
                $customer = $this->context->customer;
                if (!$customer->id) {
                    $customer->getByEmail($from);
                }

                $id_contact = (int)Tools::getValue('id_contact');
                $contact = new Contact($id_contact, $this->context->language->id);

                if (!((($id_customer_thread = (int)Tools::getValue('id_customer_thread'))
                        && (int)Db::getInstance()->getValue(
                            'SELECT cm.id_customer_thread FROM '._DB_PREFIX_.'customer_thread cm
						    WHERE cm.id_customer_thread = '.(int)$id_customer_thread.'
                            AND cm.id_shop = '.(int)$this->context->shop->id.'
                            AND token = \''.pSQL(Tools::getValue('token')).'\''
                        ))
                        || ($id_customer_thread = CustomerThread::getIdCustomerThreadByEmailAndIdOrder($from, (int)Tools::getValue('id_order'))
                        ))) {

                    $fields = Db::getInstance()->executeS(
                        'SELECT cm.id_customer_thread, cm.id_contact, cm.id_customer, cm.id_order, cm.id_product, cm.email
                        FROM '._DB_PREFIX_.'customer_thread cm
                        WHERE email = \''.pSQL($from).'\' AND cm.id_shop = '.(int)$this->context->shop->id.' AND ('.
                        ($customer->id ? 'id_customer = '.(int)$customer->id.' OR ' : '').'
                        id_order = '.(int)Tools::getValue('id_order').')'
                    );

                    $score = 0;
                    foreach ($fields as $row) {
                        $tmp = 0;
                        if ((int)$row['id_customer'] && $row['id_customer'] != $customer->id && $row['email'] != $from) {
                            continue;
                        }
                        if ($row['id_order'] != 0 && Tools::getValue('id_order') != $row['id_order']) {
                            continue;
                        }
                        if ($row['email'] == $from) {
                            $tmp += 4;
                        }
                        if ($row['id_contact'] == $id_contact) {
                            $tmp++;
                        }
                        if (Tools::getValue('id_product') != 0 && $row['id_product'] == Tools::getValue('id_product')) {
                            $tmp += 2;
                        }
                        if ($tmp >= 5 && $tmp >= $score) {
                            $score = $tmp;
                            $id_customer_thread = $row['id_customer_thread'];
                        }
                    }
                }
                $old_message = Db::getInstance()->getValue('
					SELECT cm.message FROM '._DB_PREFIX_.'customer_message cm
					LEFT JOIN '._DB_PREFIX_.'customer_thread cc on (cm.id_customer_thread = cc.id_customer_thread)
					WHERE cc.id_customer_thread = '.(int)$id_customer_thread.' AND cc.id_shop = '.(int)$this->context->shop->id.'
					ORDER BY cm.date_add DESC');
                if ($message != '' && $old_message == $message) {
                    $this->context->smarty->assign('alreadySent', 1);
                    $contact->email = '';
                    $contact->customer_service = 0;
                }
                if (!empty($contact->email)) {
                    $id_order = (int)Tools::getValue('id_order');
                    $ids_order_detail = Tools::getValue('ids_order_detail');
                    $order_qte_input = Tools::getValue('order_qte_input');
                    $order = new Order($id_order);

                    $order_products = $order->getProducts();

                    $products = array();
                    foreach ($order_products as &$product) {
                        if (in_array($product['id_order_detail'], $ids_order_detail)) {
                            $product['product_quantity'] = $order_qte_input[$product['id_order_detail']];
                            $products[] = $product;
                        }
                    }

                    $product_list_txt = $this->getEmailTemplateContent('retractation_product_list.txt', Mail::TYPE_TEXT, $products);
                    $product_list_html = $this->getEmailTemplateContent('retractation_product_list_'.$this->context->language->iso_code.'.tpl', Mail::TYPE_HTML, $products);

                    $message = Tools::nl2br(Tools::stripslashes($message));
                    $mail_var_list = array(
                        '{email}' => $from,
                        '{message}' => $message,
                        '{customer_name}' => Tools::getValue('name'),
                        '{address}' => Tools::getValue('address'),
                        '{phone}' => Tools::getValue('phone'),
                        '{id_order}' => $id_order,
                        '{order_name}' => $order->getUniqReference(),
                        '{products}' => $product_list_html,
                        '{products_txt}' => $product_list_txt,
                        '{attached_file}' => isset($_FILES['fileUpload'], $_FILES['fileUpload']['name']) ? $_FILES['fileUpload']['name'] : ''
                    );

                    if ($message == '') {
                        $return_message = $this->module->l('None', 'retractation');
                    } else {
                        $return_message = $message;
                    }

                    if (Configuration::get('PS_ORDER_RETURN')) {
                        $this->createOrderReturn($id_order, $return_message, $ids_order_detail, $order_qte_input);
                    }

                    if (Mail::Send($this->context->language->id, $mail_template, $this->module->l('Message from retraction form', 'retractation'), $mail_var_list, $contact->email, $contact->name, $from, ($customer->id ? $customer->firstname.' '.$customer->lastname : ''), $file_attachment, null, _PS_MODULE_DIR_.$this->module->name.'/mails/') && Mail::Send($this->context->language->id, $mail_template_form, $this->module->l('Your retraction request has been correctly sent', 'retractation'), $mail_var_list, $from, null, null, null, null, null, _PS_MODULE_DIR_.$this->module->name.'/mails/')) {
                        $this->context->smarty->assign('confirmation', 1);
                    } else {
                        $this->errors_list[] = Tools::displayError($this->module->l('An error occurred while sending message.', 'retractation'));
                    }
                }

                if ($contact->customer_service && $message != '') {
                    if ((int)$id_customer_thread) {
                        $ct = new CustomerThread($id_customer_thread);
                        $ct->status = 'open';
                        $ct->id_lang = (int)$this->context->language->id;
                        $ct->id_contact = (int)$id_contact;
                        if ($id_order = (int)Tools::getValue('id_order')) {
                            $ct->id_order = $id_order;
                        }
                        if ($id_product = (int)Tools::getValue('id_product')) {
                            $ct->id_product = $id_product;
                        }
                        $ct->update();
                    } else {
                        $ct = new CustomerThread();
                        if (isset($customer->id)) {
                            $ct->id_customer = (int)$customer->id;
                        }
                        $ct->id_shop = (int)$this->context->shop->id;
                        if ($id_order = (int)Tools::getValue('id_order')) {
                            $ct->id_order = $id_order;
                        }
                        if ($id_product = (int)Tools::getValue('id_product')) {
                            $ct->id_product = $id_product;
                        }
                        $ct->id_contact = (int)$id_contact;
                        $ct->id_lang = (int)$this->context->language->id;
                        $ct->email = $from;
                        $ct->status = 'open';
                        $ct->token = Tools::passwdGen(12);
                        $ct->add();
                    }

                    if ($ct->id) {
                        $cm = new CustomerMessage();
                        $cm->id_customer_thread = $ct->id;
                        $cm->message = Tools::htmlentitiesUTF8($message);
                        if (isset($filename) && rename($_FILES['fileUpload']['tmp_name'], _PS_MODULE_DIR_.'../upload/'.$filename)) {
                            $cm->file_name = $filename;
                        }
                        $cm->ip_address = ip2long($_SERVER['REMOTE_ADDR']);
                        $cm->user_agent = $_SERVER['HTTP_USER_AGENT'];
                        if ($cm->add()) {
                            if (empty($contact->email)) {
                                $var_list = array(
                                    '{order_name}' => '-',
                                    '{product_name}' => '-',
                                    '{attached_file}' => '-',
                                    '{message}' => Tools::stripslashes($message)
                                );
                                if ($ct->id_order) {
                                    $order = new Order($ct->id_order);
                                    $var_list['{order_name}'] = $order->reference;
                                }
                                if (isset($filename)) {
                                    $var_list['{attached_file}'] = $_FILES['fileUpload']['name'];
                                }

                                Mail::Send($this->context->language->id, $mail_template_form, $this->module->l('Your retraction request has been correctly sent', 'retractation'), $var_list, $from, null, null, null, null, null, _PS_MODULE_DIR_.$this->module->name.'/mails/');
                            }
                            $this->context->smarty->assign('confirmation', 1);
                        } else {
                            $this->errors_list[] = Tools::displayError($this->module->l('An error occurred while sending message.', 'retractation'));
                        }
                    } else {
                        $this->errors_list[] = Tools::displayError($this->module->l('An error occurred while sending message.', 'retractation'));
                    }
                }
                if (count($this->errors_list) > 1) {
                    array_unique($this->errors_list);
                }
            }
        }
    }

    public function createOrderReturn($id_order, $message, $ids_order_detail, $order_qte_input)
    {
        $order = new Order((int)$id_order);
        if (!$order->isReturnable()) {
            Tools::redirect($this->context->link->getModuleLink($this->module->name, 'retractation', array('errorNotReturnable' => 1)));
        }

        if ($order->id_customer != $this->context->customer->id) {
            die(Tools::displayError());
        }

        $order_return = new OrderReturn();
        $order_return->id_customer = (int)$this->context->customer->id;
        $order_return->id_order = $id_order;
        $order_return->question = $message;

        $customization_ids = null;
        $customization_qty_input = null;

        // Check if order detail already exists
        if (!$order_return->checkEnoughProduct($ids_order_detail, $order_qte_input, $customization_ids, $customization_qty_input)) {
            Tools::redirect($this->context->link->getModuleLink($this->module->name, 'retractation', array('errorQuantity' => 1)));
        }

        $order_return->state = 1;
        $order_return->add();
        $order_return->addReturnDetail($ids_order_detail, $order_qte_input, $customization_ids, $customization_qty_input);
        Hook::exec('actionOrderReturn', array('orderReturn' => $order_return));
    }

    /**
     * Fetch the content of $template_name inside the mails folder
     *
     * @param string  $template_name template name with extension
     * @param integer $mail_type     Mail::TYPE_HTML or Mail::TYPE_TXT
     * @param array   $var           list send to smarty
     *
     * @return string
     */
    protected function getEmailTemplateContent($template_name, $mail_type, $var)
    {
        $email_configuration = Configuration::get('PS_MAIL_TYPE');
        if ($email_configuration != $mail_type && $email_configuration != Mail::TYPE_BOTH) {
            return '';
        }

        if ($mail_type == Mail::TYPE_TEXT) {
            $mail_template_path = _PS_MODULE_DIR_.$this->module->name.'/mails/'.$this->context->language->iso_code.'/'.$template_name;
        } else {
            $mail_template_path = _PS_MODULE_DIR_.$this->module->name.'/views/templates/front/'.$template_name;
        }

        if (file_exists($mail_template_path)) {
            $this->context->smarty->assign('list', $var);
            return $this->context->smarty->fetch($mail_template_path);
        }
        return '';
    }
}
