<?php
/**
 * Loi Hamon Prestashop module
 *
 * @author    Prestaddons <contact@prestaddons.fr>
 * @copyright 2014 Prestaddons
 * @license
 * @link      http://www.prestaddons.fr
 */
abstract class PaymentModule extends PaymentModuleCore
{
    /*
    * module: loihamon
    * date: 2018-10-24 12:23:22
    * version: 1.7.1
    */
    public function validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method = 'Unknown',
        $message = null, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false,
        $secure_key = false, Shop $shop = null)
    {
        if (self::DEBUG_MODE) {
            PrestaShopLogger::addLog('PaymentModule::validateOrder - Function called', 1, null, 'Cart', (int)$id_cart, true);
        }
        if (!isset($this->context)) {
            $this->context = Context::getContext();
        }
        $this->context->cart = new Cart((int)$id_cart);
        $this->context->customer = new Customer((int)$this->context->cart->id_customer);
        $this->context->cart->setTaxCalculationMethod();
        $this->context->language = new Language((int)$this->context->cart->id_lang);
        $this->context->shop = ($shop ? $shop : new Shop((int)$this->context->cart->id_shop));
        ShopUrl::resetMainDomainCache();
        $id_currency = $currency_special ? (int)$currency_special : (int)$this->context->cart->id_currency;
        $this->context->currency = new Currency((int)$id_currency, null, (int)$this->context->shop->id);
        if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
            $context_country = $this->context->country;
        }
        $order_status = new OrderState((int)$id_order_state, (int)$this->context->language->id);
        if (!Validate::isLoadedObject($order_status)) {
            PrestaShopLogger::addLog('PaymentModule::validateOrder - Order Status cannot be loaded', 3, null, 'Cart', (int)$id_cart, true);
            throw new PrestaShopException('Can\'t load Order status');
        }
        if (!$this->active) {
            PrestaShopLogger::addLog('PaymentModule::validateOrder - Module is not active', 3, null, 'Cart', (int)$id_cart, true);
            die(Tools::displayError());
        }
        if (Validate::isLoadedObject($this->context->cart) && $this->context->cart->OrderExists() == false) {
            if ($secure_key !== false && $secure_key != $this->context->cart->secure_key) {
                PrestaShopLogger::addLog('PaymentModule::validateOrder - Secure key does not match', 3, null, 'Cart', (int)$id_cart, true);
                die(Tools::displayError());
            }
            $delivery_option_list = $this->context->cart->getDeliveryOptionList();
            $package_list = $this->context->cart->getPackageList();
            $cart_delivery_option = $this->context->cart->getDeliveryOption();
            foreach ($delivery_option_list as $id_address => $package) {
                if (!isset($cart_delivery_option[$id_address]) || !array_key_exists($cart_delivery_option[$id_address], $package)) {
                    foreach ($package as $key => $val) {
                        $cart_delivery_option[$id_address] = $key;
                        break;
                    }
                }
            }
            $order_list = array();
            $order_detail_list = array();
            do {
                $reference = Order::generateReference();
            } while (Order::getByReference($reference)->count());
            $this->currentOrderReference = $reference;
            $order_creation_failed = false;
            $cart_total_paid = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH), 2);
            foreach ($cart_delivery_option as $id_address => $key_carriers) {
                foreach ($delivery_option_list[$id_address][$key_carriers]['carrier_list'] as $id_carrier => $data) {
                    foreach ($data['package_list'] as $id_package) {
                        $package_list[$id_address][$id_package]['id_warehouse'] = (int)$this->context->cart->getPackageIdWarehouse($package_list[$id_address][$id_package], (int)$id_carrier);
                        $package_list[$id_address][$id_package]['id_carrier'] = $id_carrier;
                    }
                }
            }
            CartRule::cleanCache();
            $cart_rules = $this->context->cart->getCartRules();
            foreach ($cart_rules as $cart_rule) {
                if (($rule = new CartRule((int)$cart_rule['obj']->id)) && Validate::isLoadedObject($rule)) {
                    if ($error = $rule->checkValidity($this->context, true, true)) {
                        $this->context->cart->removeCartRule((int)$rule->id);
                        if (isset($this->context->cookie) && isset($this->context->cookie->id_customer) && $this->context->cookie->id_customer && !empty($rule->code)) {
                            if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 1) {
                                Tools::redirect('index.php?controller=order-opc&submitAddDiscount=1&discount_name='.urlencode($rule->code));
                            }
                            Tools::redirect('index.php?controller=order&submitAddDiscount=1&discount_name='.urlencode($rule->code));
                        } else {
                            $rule_name = isset($rule->name[(int)$this->context->cart->id_lang]) ? $rule->name[(int)$this->context->cart->id_lang] : $rule->code;
                            $error = sprintf(Tools::displayError('CartRule ID %1s (%2s) used in this cart is not valid and has been withdrawn from cart'), (int)$rule->id, $rule_name);
                            PrestaShopLogger::addLog($error, 3, '0000002', 'Cart', (int)$this->context->cart->id);
                        }
                    }
                }
            }
            foreach ($package_list as $id_address => $packageByAddress) {
                foreach ($packageByAddress as $id_package => $package) {
                    
                    $order = new Order();
                    $order->product_list = $package['product_list'];
                    if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                        $address = new Address((int)$id_address);
                        $this->context->country = new Country((int)$address->id_country, (int)$this->context->cart->id_lang);
                        if (!$this->context->country->active) {
                            throw new PrestaShopException('The delivery address country is not active.');
                        }
                    }
                    $carrier = null;
                    if (!$this->context->cart->isVirtualCart() && isset($package['id_carrier'])) {
                        $carrier = new Carrier((int)$package['id_carrier'], (int)$this->context->cart->id_lang);
                        $order->id_carrier = (int)$carrier->id;
                        $id_carrier = (int)$carrier->id;
                    } else {
                        $order->id_carrier = 0;
                        $id_carrier = 0;
                    }
                    $order->id_customer = (int)$this->context->cart->id_customer;
                    $order->id_address_invoice = (int)$this->context->cart->id_address_invoice;
                    $order->id_address_delivery = (int)$id_address;
                    $order->id_currency = $this->context->currency->id;
                    $order->id_lang = (int)$this->context->cart->id_lang;
                    $order->id_cart = (int)$this->context->cart->id;
                    $order->reference = $reference;
                    $order->id_shop = (int)$this->context->shop->id;
                    $order->id_shop_group = (int)$this->context->shop->id_shop_group;
                    $order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($this->context->customer->secure_key));
                    $order->payment = $payment_method;
                    if (isset($this->name)) {
                        $order->module = $this->name;
                    }
                    $order->recyclable = $this->context->cart->recyclable;
                    $order->gift = (int)$this->context->cart->gift;
                    $order->gift_message = $this->context->cart->gift_message;
                    $order->mobile_theme = $this->context->cart->mobile_theme;
                    $order->conversion_rate = $this->context->currency->conversion_rate;
                    $amount_paid = !$dont_touch_amount ? Tools::ps_round((float)$amount_paid, 2) : $amount_paid;
                    $order->total_paid_real = 0;
                    $order->total_products = (float)$this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
                    $order->total_products_wt = (float)$this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
                    $order->total_discounts_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts = $order->total_discounts_tax_incl;
                    $order->total_shipping_tax_excl = (float)$this->context->cart->getPackageShippingCost((int)$id_carrier, false, null, $order->product_list);
                    $order->total_shipping_tax_incl = (float)$this->context->cart->getPackageShippingCost((int)$id_carrier, true, null, $order->product_list);
                    $order->total_shipping = $order->total_shipping_tax_incl;
                    if (!is_null($carrier) && Validate::isLoadedObject($carrier)) {
                        $order->carrier_tax_rate = $carrier->getTaxesRate(new Address((int)$this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
                    }
                    $order->total_wrapping_tax_excl = (float)abs($this->context->cart->getOrderTotal(false, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping_tax_incl = (float)abs($this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping = $order->total_wrapping_tax_incl;
                    $order->total_paid_tax_excl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(false, Cart::BOTH, $order->product_list, $id_carrier), _PS_PRICE_COMPUTE_PRECISION_);
                    $order->total_paid_tax_incl = (float)Tools::ps_round((float)$this->context->cart->getOrderTotal(true, Cart::BOTH, $order->product_list, $id_carrier), _PS_PRICE_COMPUTE_PRECISION_);
                    $order->total_paid = $order->total_paid_tax_incl;
                    $order->round_mode = Configuration::get('PS_PRICE_ROUND_MODE');
                    $order->round_type = Configuration::get('PS_ROUND_TYPE');
                    $order->invoice_date = '0000-00-00 00:00:00';
                    $order->delivery_date = '0000-00-00 00:00:00';
                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Order is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                    }
                    $result = $order->add();
                    if (!$result) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Order cannot be created', 3, null, 'Cart', (int)$id_cart, true);
                        throw new PrestaShopException('Can\'t save Order');
                    }
                    if ($order_status->logable && number_format($cart_total_paid, _PS_PRICE_COMPUTE_PRECISION_) != number_format($amount_paid, _PS_PRICE_COMPUTE_PRECISION_)) {
                        $id_order_state = Configuration::get('PS_OS_ERROR');
                    }
                    $order_list[] = $order;
                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - OrderDetail is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                    }
                    $order_detail = new OrderDetail(null, null, $this->context);
                    $order_detail->createList($order, $this->context->cart, $id_order_state, $order->product_list, 0, true, $package_list[$id_address][$id_package]['id_warehouse']);
                    $order_detail_list[] = $order_detail;
                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - OrderCarrier is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                    }
                    if (!is_null($carrier)) {
                        $order_carrier = new OrderCarrier();
                        $order_carrier->id_order = (int)$order->id;
                        $order_carrier->id_carrier = (int)$id_carrier;
                        $order_carrier->weight = (float)$order->getTotalWeight();
                        $order_carrier->shipping_cost_tax_excl = (float)$order->total_shipping_tax_excl;
                        $order_carrier->shipping_cost_tax_incl = (float)$order->total_shipping_tax_incl;
                        $order_carrier->add();
                    }
                }
            }
            if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                $this->context->country = $context_country;
            }
            if (!$this->context->country->active) {
                PrestaShopLogger::addLog('PaymentModule::validateOrder - Country is not active', 3, null, 'Cart', (int)$id_cart, true);
                throw new PrestaShopException('The order address country is not active.');
            }
            if (self::DEBUG_MODE) {
                PrestaShopLogger::addLog('PaymentModule::validateOrder - Payment is about to be added', 1, null, 'Cart', (int)$id_cart, true);
            }
            if ($order_status->logable) {
                if (isset($extra_vars['transaction_id'])) {
                    $transaction_id = $extra_vars['transaction_id'];
                } else {
                    $transaction_id = null;
                }
                if (!isset($order) || !Validate::isLoadedObject($order) || !$order->addOrderPayment($amount_paid, null, $transaction_id)) {
                    PrestaShopLogger::addLog('PaymentModule::validateOrder - Cannot save Order Payment', 3, null, 'Cart', (int)$id_cart, true);
                    throw new PrestaShopException('Can\'t save Order Payment');
                }
            }
            $only_one_gift = false;
            $cart_rule_used = array();
            $products = $this->context->cart->getProducts();
            CartRule::cleanCache();
            foreach ($order_detail_list as $key => $order_detail) {
                
                $order = $order_list[$key];
                if (!$order_creation_failed && isset($order->id)) {
                    if (!$secure_key) {
                        $message .= '<br />'.Tools::displayError('Warning: the secure key is empty, check your payment account before validation');
                    }
                    if (isset($message) & !empty($message)) {
                        $msg = new Message();
                        $message = strip_tags($message, '<br>');
                        if (Validate::isCleanHtml($message)) {
                            if (self::DEBUG_MODE) {
                                PrestaShopLogger::addLog('PaymentModule::validateOrder - Message is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                            }
                            $msg->message = $message;
                            $msg->id_cart = (int)$id_cart;
                            $msg->id_customer = (int)($order->id_customer);
                            $msg->id_order = (int)$order->id;
                            $msg->private = 1;
                            $msg->add();
                        }
                    }
                    $products_list = '';
                    $virtual_product = true;
                    $product_var_tpl_list = array();
                    foreach ($order->product_list as $product) {
                        $price = Product::getPriceStatic((int)$product['id_product'], false, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                        $price_wt = Product::getPriceStatic((int)$product['id_product'], true, ($product['id_product_attribute'] ? (int)$product['id_product_attribute'] : null), 2, null, false, true, $product['cart_quantity'], false, (int)$order->id_customer, (int)$order->id_cart, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                        $product_price = Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt;
                        $product_var_tpl = array(
                            'reference' => $product['reference'],
                            'name' => $product['name'].(isset($product['attributes']) ? ' - '.$product['attributes'] : ''),
                            'unit_price' => Tools::displayPrice($product_price, $this->context->currency, false),
                            'price' => Tools::displayPrice($product_price * $product['quantity'], $this->context->currency, false),
                            'quantity' => $product['quantity'],
                            'customization' => array()
                        );
                        $customized_datas = Product::getAllCustomizedDatas((int)$order->id_cart);
                        if (isset($customized_datas[$product['id_product']][$product['id_product_attribute']])) {
                            $product_var_tpl['customization'] = array();
                            foreach ($customized_datas[$product['id_product']][$product['id_product_attribute']][$order->id_address_delivery] as $customization) {
                                $customization_text = '';
                                if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD])) {
                                    foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text) {
                                        $customization_text .= $text['name'].': '.$text['value'].'<br />';
                                    }
                                }
                                if (isset($customization['datas'][Product::CUSTOMIZE_FILE])) {
                                    $customization_text .= sprintf(Tools::displayError('%d image(s)'), count($customization['datas'][Product::CUSTOMIZE_FILE])).'<br />';
                                }
                                $customization_quantity = (int)$product['customization_quantity'];
                                $product_var_tpl['customization'][] = array(
                                    'customization_text' => $customization_text,
                                    'customization_quantity' => $customization_quantity,
                                    'quantity' => Tools::displayPrice($customization_quantity * $product_price, $this->context->currency, false)
                                );
                            }
                        }
                        $product_var_tpl_list[] = $product_var_tpl;
                        if (!$product['is_virtual']) {
                            $virtual_product &= false;
                        }
                    } // end foreach ($products)
                    $product_list_txt = '';
                    $product_list_html = '';
                    if (count($product_var_tpl_list) > 0) {
                        $product_list_txt = $this->getEmailTemplateContent('order_conf_product_list.txt', Mail::TYPE_TEXT, $product_var_tpl_list);
                        $product_list_html = $this->getEmailTemplateContent('order_conf_product_list.tpl', Mail::TYPE_HTML, $product_var_tpl_list);
                    }
                    $cart_rules_list = array();
                    $total_reduction_value_ti = 0;
                    $total_reduction_value_tex = 0;
                    foreach ($cart_rules as $cart_rule) {
                        $package = array('id_carrier' => $order->id_carrier, 'id_address' => $order->id_address_delivery, 'products' => $order->product_list);
                        $values = array(
                            'tax_incl' => $cart_rule['obj']->getContextualValue(true, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package),
                            'tax_excl' => $cart_rule['obj']->getContextualValue(false, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package)
                        );
                        if (!$values['tax_excl']) {
                            continue;
                        }
                        if (count($order_list) == 1 && $values['tax_incl'] > ($order->total_products_wt - $total_reduction_value_ti) && $cart_rule['obj']->partial_use == 1 && $cart_rule['obj']->reduction_amount > 0) {
                            $voucher = new CartRule((int)$cart_rule['obj']->id); // We need to instantiate the CartRule without lang parameter to allow saving it
                            unset($voucher->id);
                            $voucher->code = empty($voucher->code) ? substr(md5($order->id.'-'.$order->id_customer.'-'.$cart_rule['obj']->id), 0, 16) : $voucher->code.'-2';
                            if (preg_match('/\-([0-9]{1,2})\-([0-9]{1,2})$/', $voucher->code, $matches) && $matches[1] == $matches[2]) {
                                $voucher->code = preg_replace('/'.$matches[0].'$/', '-'.(intval($matches[1]) + 1), $voucher->code);
                            }
                            if ($voucher->reduction_tax) {
                                $voucher->reduction_amount = ($total_reduction_value_ti + $values['tax_incl']) - $order->total_products_wt;
                                if ($voucher->free_shipping == 1 && $voucher->reduction_amount >= $order->total_shipping_tax_incl) {
                                    $voucher->reduction_amount -= $order->total_shipping_tax_incl;
                                }
                            } else {
                                $voucher->reduction_amount = ($total_reduction_value_tex + $values['tax_excl']) - $order->total_products;
                                if ($voucher->free_shipping == 1 && $voucher->reduction_amount >= $order->total_shipping_tax_excl) {
                                    $voucher->reduction_amount -= $order->total_shipping_tax_excl;
                                }
                            }
                            if ($voucher->reduction_amount <= 0) {
                                continue;
                            }
                            if ($this->context->customer->isGuest()) {
                                $voucher->id_customer = 0;
                            } else {
                                $voucher->id_customer = $order->id_customer;
                            }
                            $voucher->quantity = 1;
                            $voucher->reduction_currency = $order->id_currency;
                            $voucher->quantity_per_user = 1;
                            $voucher->free_shipping = 0;
                            if ($voucher->add()) {
                                CartRule::copyConditions($cart_rule['obj']->id, $voucher->id);
                                $params = array(
                                    '{voucher_amount}' => Tools::displayPrice($voucher->reduction_amount, $this->context->currency, false),
                                    '{voucher_num}' => $voucher->code,
                                    '{firstname}' => $this->context->customer->firstname,
                                    '{lastname}' => $this->context->customer->lastname,
                                    '{id_order}' => $order->reference,
                                    '{order_name}' => $order->getUniqReference()
                                );
                                Mail::Send(
                                    (int)$order->id_lang,
                                    'voucher',
                                    sprintf(Mail::l('New voucher for your order %s', (int)$order->id_lang), $order->reference),
                                    $params,
                                    $this->context->customer->email,
                                    $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                    null, null, null, null, _PS_MAIL_DIR_, false, (int)$order->id_shop
                                );
                            }
                            $values['tax_incl'] = $order->total_products_wt - $total_reduction_value_ti;
                            $values['tax_excl'] = $order->total_products - $total_reduction_value_tex;
                        }
                        $total_reduction_value_ti += $values['tax_incl'];
                        $total_reduction_value_tex += $values['tax_excl'];
                        $order->addCartRule($cart_rule['obj']->id, $cart_rule['obj']->name, $values, 0, $cart_rule['obj']->free_shipping);
                        if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && !in_array($cart_rule['obj']->id, $cart_rule_used)) {
                            $cart_rule_used[] = $cart_rule['obj']->id;
                            $cart_rule_to_update = new CartRule((int)$cart_rule['obj']->id);
                            $cart_rule_to_update->quantity = max(0, $cart_rule_to_update->quantity - 1);
                            $cart_rule_to_update->update();
                        }
                        $cart_rules_list[] = array(
                            'voucher_name' => $cart_rule['obj']->name,
                            'voucher_reduction' => ($values['tax_incl'] != 0.00 ? '-' : '').Tools::displayPrice($values['tax_incl'], $this->context->currency, false)
                        );
                    }
                    $cart_rules_list_txt = '';
                    $cart_rules_list_html = '';
                    if (count($cart_rules_list) > 0) {
                        $cart_rules_list_txt = $this->getEmailTemplateContent('order_conf_cart_rules.txt', Mail::TYPE_TEXT, $cart_rules_list);
                        $cart_rules_list_html = $this->getEmailTemplateContent('order_conf_cart_rules.tpl', Mail::TYPE_HTML, $cart_rules_list);
                    }
                    $old_message = Message::getMessageByCartId((int)$this->context->cart->id);
                    if ($old_message && !$old_message['private']) {
                        $update_message = new Message((int)$old_message['id_message']);
                        $update_message->id_order = (int)$order->id;
                        $update_message->update();
                        $customer_thread = new CustomerThread();
                        $customer_thread->id_contact = 0;
                        $customer_thread->id_customer = (int)$order->id_customer;
                        $customer_thread->id_shop = (int)$this->context->shop->id;
                        $customer_thread->id_order = (int)$order->id;
                        $customer_thread->id_lang = (int)$this->context->language->id;
                        $customer_thread->email = $this->context->customer->email;
                        $customer_thread->status = 'open';
                        $customer_thread->token = Tools::passwdGen(12);
                        $customer_thread->add();
                        $customer_message = new CustomerMessage();
                        $customer_message->id_customer_thread = $customer_thread->id;
                        $customer_message->id_employee = 0;
                        $customer_message->message = $update_message->message;
                        $customer_message->private = 0;
                        if (!$customer_message->add()) {
                            $this->errors[] = Tools::displayError('An error occurred while saving message');
                        }
                    }
                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Hook validateOrder is about to be called', 1, null, 'Cart', (int)$id_cart, true);
                    }
                    Hook::exec('actionValidateOrder', array(
                        'cart' => $this->context->cart,
                        'order' => $order,
                        'customer' => $this->context->customer,
                        'currency' => $this->context->currency,
                        'orderStatus' => $order_status
                    ));
                    foreach ($this->context->cart->getProducts() as $product) {
                        if ($order_status->logable) {
                            ProductSale::addProductSale((int)$product['id_product'], (int)$product['cart_quantity']);
                        }
                    }
                    if (self::DEBUG_MODE) {
                        PrestaShopLogger::addLog('PaymentModule::validateOrder - Order Status is about to be added', 1, null, 'Cart', (int)$id_cart, true);
                    }
                    $new_history = new OrderHistory();
                    $new_history->id_order = (int)$order->id;
                    $new_history->changeIdOrderState((int)$id_order_state, $order, true);
                    $new_history->addWithemail(true, $extra_vars);
                    if (Configuration::get('PS_STOCK_MANAGEMENT') && ($order_detail->getStockState() || $order_detail->product_quantity_in_stock <= 0)) {
                        $history = new OrderHistory();
                        $history->id_order = (int)$order->id;
                        $history->changeIdOrderState(Configuration::get($order->valid ? 'PS_OS_OUTOFSTOCK_PAID' : 'PS_OS_OUTOFSTOCK_UNPAID'), $order, true);
                        $history->addWithemail();
                    }
                    unset($order_detail);
                    $order = new Order((int)$order->id);
                    if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $this->context->customer->id) {
                        $invoice = new Address((int)$order->id_address_invoice);
                        $delivery = new Address((int)$order->id_address_delivery);
                        $delivery_state = $delivery->id_state ? new State((int)$delivery->id_state) : false;
                        $invoice_state = $invoice->id_state ? new State((int)$invoice->id_state) : false;
                        $data = array(
                        '{firstname}' => $this->context->customer->firstname,
                        '{lastname}' => $this->context->customer->lastname,
                        '{email}' => $this->context->customer->email,
                        '{delivery_block_txt}' => $this->_getFormatedAddress($delivery, "\n"),
                        '{invoice_block_txt}' => $this->_getFormatedAddress($invoice, "\n"),
                        '{delivery_block_html}' => $this->_getFormatedAddress($delivery, '<br />', array(
                            'firstname'    => '<span style="font-weight:bold;">%s</span>',
                            'lastname'    => '<span style="font-weight:bold;">%s</span>'
                        )),
                        '{invoice_block_html}' => $this->_getFormatedAddress($invoice, '<br />', array(
                                'firstname'    => '<span style="font-weight:bold;">%s</span>',
                                'lastname'    => '<span style="font-weight:bold;">%s</span>'
                        )),
                        '{delivery_company}' => $delivery->company,
                        '{delivery_firstname}' => $delivery->firstname,
                        '{delivery_lastname}' => $delivery->lastname,
                        '{delivery_address1}' => $delivery->address1,
                        '{delivery_address2}' => $delivery->address2,
                        '{delivery_city}' => $delivery->city,
                        '{delivery_postal_code}' => $delivery->postcode,
                        '{delivery_country}' => $delivery->country,
                        '{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
                        '{delivery_phone}' => ($delivery->phone) ? $delivery->phone : $delivery->phone_mobile,
                        '{delivery_other}' => $delivery->other,
                        '{invoice_company}' => $invoice->company,
                        '{invoice_vat_number}' => $invoice->vat_number,
                        '{invoice_firstname}' => $invoice->firstname,
                        '{invoice_lastname}' => $invoice->lastname,
                        '{invoice_address2}' => $invoice->address2,
                        '{invoice_address1}' => $invoice->address1,
                        '{invoice_city}' => $invoice->city,
                        '{invoice_postal_code}' => $invoice->postcode,
                        '{invoice_country}' => $invoice->country,
                        '{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
                        '{invoice_phone}' => ($invoice->phone) ? $invoice->phone : $invoice->phone_mobile,
                        '{invoice_other}' => $invoice->other,
                        '{order_name}' => $order->getUniqReference(),
                        '{date}' => Tools::displayDate(date('Y-m-d H:i:s'), null, 1),
                        '{carrier}' => ($virtual_product || !isset($carrier->name)) ? Tools::displayError('No carrier') : $carrier->name,
                        '{payment}' => Tools::substr($order->payment, 0, 32),
                        '{products}' => $product_list_html,
                        '{products_txt}' => $product_list_txt,
                        '{discounts}' => $cart_rules_list_html,
                        '{discounts_txt}' => $cart_rules_list_txt,
                        '{total_paid}' => Tools::displayPrice($order->total_paid, $this->context->currency, false),
                        '{total_products}' => Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? $order->total_products : $order->total_products_wt, $this->context->currency, false),
                        '{total_discounts}' => Tools::displayPrice($order->total_discounts, $this->context->currency, false),
                        '{total_shipping}' => Tools::displayPrice($order->total_shipping, $this->context->currency, false),
                        '{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $this->context->currency, false),
                        '{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $this->context->currency, false));
                        if (is_array($extra_vars)) {
                            $data = array_merge($data, $extra_vars);
                        }
                        if ((int)Configuration::get('PS_INVOICE') && $order_status->invoice && $order->invoice_number) {
                            $order_invoice_list = $order->getInvoicesCollection();
                            Hook::exec('actionPDFInvoiceRender', array('order_invoice_list' => $order_invoice_list));
                            $pdf = new PDF($order_invoice_list, PDF::TEMPLATE_INVOICE, $this->context->smarty);
                            $file_attachement['content'] = $pdf->render(false);
                            $file_attachement['name'] = Configuration::get('PS_INVOICE_PREFIX', (int)$order->id_lang, null, $order->id_shop).sprintf('%06d', $order->invoice_number).'.pdf';
                            $file_attachement['mime'] = 'application/pdf';
                        } else {
                            $file_attachement = null;
						}
						if (Configuration::get('lh_cms_attachment')) {
							if (!class_exists('HTMLTemplateCMS')) {
                                include_once(_PS_MODULE_DIR_.'loihamon/classes/HTMLTemplateCMS.php');
                            }
							if (!empty($file_attachement) && !$file_attachement) {
								$file_attachement = array($file_attachement);
							} else {
								$file_attachement = array();
							}
							$cms_attachement = array();
							$id_cms = Configuration::get('lh_id_cms');
							$cms = new CMS($id_cms, (int)$order->id_lang, (int)$order->id_shop);
							$pdf = new PDF($cms, PDF::TEMPLATE_CMS, $this->context->smarty);
							$cms_attachement['content'] = $pdf->render(false);
							$cms_attachement['name'] = $cms->link_rewrite.'.pdf';
							$cms_attachement['mime'] = 'application/pdf';
							array_push($file_attachement, $cms_attachement);
						}
                        if (self::DEBUG_MODE) {
                            PrestaShopLogger::addLog('PaymentModule::validateOrder - Mail is about to be sent', 1, null, 'Cart', (int)$id_cart, true);
                        }
                        if (Validate::isEmail($this->context->customer->email)) {
                            Mail::Send(
                                (int)$order->id_lang,
                                'order_conf',
                                Mail::l('Order confirmation', (int)$order->id_lang),
                                $data,
                                $this->context->customer->email,
                                $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                null,
                                null,
                                $file_attachement,
                                null, _PS_MAIL_DIR_, false, (int)$order->id_shop
                            );
                        }
                    }
                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                        $product_list = $order->getProducts();
                        foreach ($product_list as $product) {
                            if (StockAvailable::dependsOnStock($product['product_id'])) {
                                StockAvailable::synchronize($product['product_id'], $order->id_shop);
                            }
                        }
                    }
                    $order->updateOrderDetailTax();
                } else {
                    $error = Tools::displayError('Order creation failed');
                    PrestaShopLogger::addLog($error, 4, '0000002', 'Cart', intval($order->id_cart));
                    die($error);
                }
            } // End foreach $order_detail_list
            if (isset($order) && $order->id) {
                $this->currentOrder = (int)$order->id;
            }
            if (self::DEBUG_MODE) {
                PrestaShopLogger::addLog('PaymentModule::validateOrder - End of validateOrder', 1, null, 'Cart', (int)$id_cart, true);
            }
            return true;
        } else {
            $error = Tools::displayError('Cart cannot be loaded or an order has already been placed using this cart');
            PrestaShopLogger::addLog($error, 4, '0000001', 'Cart', intval($this->context->cart->id));
            die($error);
        }
    }
}