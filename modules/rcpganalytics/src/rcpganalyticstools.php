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

class RcPgAnalyticsTools
{
    ///////////////////////////////
    /// Handle Product Data

    public static function indexProductsCache(array $products)
    {
        $indexedProducts = array();

        foreach ($products as $product) {
            $index = $product['id_product'].'-'.$product['id_product_attribute'];
            $indexedProducts[$index] = self::clearProductFields($product);
        }

        return $indexedProducts;
    }

    // Clean product fields for better indexation
    public static function clearProductFields(array $product)
    {
        $fields = array(
            'id_product',
            'id_category_default',
            'id_manufacturer',
            'id_product_attribute',
            'cache_default_attribute',
            'name',
            'manufacturer_name',
            'category',
            'reference',
            'supplier_reference',
            'ean13',
            'price', // used on product list
            'price_wt', // used on checkout
        );
        $productsCleared = array();

        foreach ($fields as $key) {
            if (isset($product[$key])) {
                $productsCleared[$key] = $product[$key];
            }
        }

        return $productsCleared;
    }

    // get products requested by ajax process
    public static function trackProducts($ids_detected)
    {
        $context = Context::getContext();

        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;

        $products_by_id = array();
        $indexed_products = array();

        // prepare where query
        $where_products = array();

        foreach ($ids_detected as $identifier) {
            // the (int) stripes the identifier and gets the id_product
            $where_products[] = 'p.id_product = '.(int)$identifier;
        }

        $db_products = DB::getInstance()->executeS('
            SELECT p.id_product, pl.name, m.name AS manufacturer_name, p.id_category_default, p.cache_default_attribute
            FROM '._DB_PREFIX_.'product p
            LEFT JOIN '._DB_PREFIX_.'product_lang pl
                ON (p.id_product = pl.id_product AND pl.id_lang = '.(int)$id_lang.' AND pl.id_shop = '.(int)$id_shop.')
            LEFT JOIN '._DB_PREFIX_.'manufacturer m ON (p.id_manufacturer = m.id_manufacturer)
            WHERE '.implode(' OR ', $where_products).'
            GROUP BY p.id_product
        ');

        // index products by id
        foreach ($db_products as $db_product) {
            $products_by_id[$db_product['id_product']] = $db_product;
        }

        // include detected attributes in final indexation
        foreach ($ids_detected as $identifier) {
            // identifier - 0 id_product / 1 product_id_attribute
            $identifiers = explode('-', $identifier);

            // save product indexed with id_product and id_product_attribute
            $indexed_products[$identifier] = $products_by_id[$identifiers[0]];
            $indexed_products[$identifier]['id_product_attribute'] = $identifiers[1];
        }

        return $indexed_products;
    }

    // get ga categories
    public static function getGaCategories($products)
    {
        foreach ($products as &$product) {
            if (isset($product['id_category_default'])) {
                $product_path = self::getGaCategory($product['id_category_default']);
                $product['ga_category'] = $product_path;
            }
        }
        return $products;
    }

    // get ga category
    public static function getGaCategory($id_category)
    {
        $cache_key = __CLASS__.'::setGaCategory_'.$id_category;

        if (!Cache::isStored($cache_key)) {
            $context = Context::getContext();

            $id_category = (int)$id_category;
            if ($id_category == 1) {
                return '';
            }

            $pipe = '/';

            $full_path = '';

            $interval = Category::getInterval($id_category);
            $id_root_category = $context->shop->getCategory();
            $interval_root = Category::getInterval($id_root_category);

            if ($interval) {
                $sql = 'SELECT cl.name
                        FROM '._DB_PREFIX_.'category c
                        LEFT JOIN '._DB_PREFIX_.'category_lang cl
                            ON (cl.id_category = c.id_category'.Shop::addSqlRestrictionOnLang('cl').')
                        WHERE c.nleft <= '.(int)$interval['nleft'].'
                            AND c.nright >= '.(int)$interval['nright'].'
                            AND c.nleft >= '.(int)$interval_root['nleft'].'
                            AND c.nright <= '.(int)$interval_root['nright'].'
                            AND cl.id_lang = '.(int)$context->language->id.'
                            AND c.active = 1
                            AND c.level_depth > '.(int)$interval_root['level_depth'].'
                        ORDER BY c.level_depth ASC'
                ;
                $categories = Db::getInstance()->executeS($sql);

                $n = 1;
                $n_categories = count($categories);
                foreach ($categories as $category) {
                    $full_path .= $category['name'].(($n++ != $n_categories) ? $pipe : '');
                }
                Cache::store($cache_key, $full_path);

                return $full_path;
            }
        }

        return Cache::retrieve($cache_key);
    }

    /**
     * Get Manufacturer name for all products
     * @param $products
     * @return mixed
     */
    public static function getManufacturerNames($products)
    {
        foreach ($products as &$product) {
            $product['manufacturer_name'] = Manufacturer::getNameById((int)$product['id_manufacturer']);
        }
        return $products;
    }

    /**
     * Get Names Without Variant to perfect view on statistics
     * @param $products
     * @param $id_lang
     * @return mixed
     */
    public static function getNamesWithoutVariant($products, $id_lang, $id_shop)
    {
        foreach ($products as &$product) {
            if ($product['product_attribute_id'] != 0) {
                $sql = 'SELECT `name`
                    FROM `'._DB_PREFIX_.'product_lang`
                    WHERE `id_product` = '.(int)$product['id_product'].'
                    AND `id_shop` = '.(int)$id_shop.'
                    AND `id_lang` = '.(int)$id_lang
                ;
                $result = Db::getInstance()->getRow($sql);

                if (isset($result['name'])) {
                    $product['product_name'] = $result['name'];
                }
            }
        }
        return $products;
    }

    /**
     * Get Variants Names for GA
     * @param $products
     * @return mixed
     */
    public static function getVariants($products)
    {
        foreach ($products as &$product) {
            if (isset($product['id_product_attribute']) && $product['id_product_attribute'] != 0) {
                // Set Variants on checkout products
                $variant_values = self::getVariant($product['id_product'], $product['id_product_attribute']);
                $product['ga_variant'] = $variant_values;
            } elseif (isset($product['product_attribute_id']) && $product['product_attribute_id'] != 0) {
                // Set Variants on Order Complete
                $variant_values = self::getVariant($product['id_product'], $product['product_attribute_id']);
                $product['ga_variant'] = $variant_values;
            }
        }
        return $products;
    }

    /**
     * Get Variant name and price
     * @param $id_product
     * @param $id_product_attribute
     * @return array
     */
    public static function getVariant($id_product, $id_product_attribute)
    {
        $variant_values = array();

        $attributes = Product::getAttributesParams($id_product, $id_product_attribute);
        $product_price = Product::getPriceStatic($id_product, true, $id_product_attribute, 2);

        $combination_name = '';

        $num_items = count($attributes);
        $i = 1;

        // Get the attribute group and name
        foreach ($attributes as $attribute) {
            $combination_name .= $attribute['group'].' '.$attribute['name'].(($i++ == $num_items) ? '' : ', ');
        }

        $variant_values['variant_name'] = $combination_name;
        $variant_values['variant_price'] = (float)$product_price;

        return $variant_values;
    }

    ///////////////////////////////
    /// Handle Tag Product Layer

    /**
     * Prepare Products to Tag
     * @param $products
     * @param $products_position
     * @param $list
     * @param $is_order
     * @return array|bool
     */
    public static function tagProducts($products, $products_position, $list, $is_order)
    {
        if (empty($products)) {
            return false;
        }

        //wrap all tagged products in array
        $tagged_products = array();

        foreach ($products as $product) {
            if ($is_order === false) {
                //retrieve the actual product position
                $position = $products_position[$product['id']];
            } else {
                $position = null;
            }

            $tagged_products[] = self::tagProduct($product, $position, $list, null);
        }

        return $tagged_products;
    }

    /**
     * Normalize product model
     * @param $product
     * @param $position
     * @param $list
     * @param $quantity_wanted
     * @return array
     */
    public static function tagProduct($product, $position, $list, $quantity_wanted)
    {
        // Filter right default attribute
        if (isset($product['id_product_attribute']) && $product['id_product_attribute']) {
            // used on checkout process
            $id_product_attribute = $product['id_product_attribute'];
        } elseif (isset($product['product_attribute_id']) && $product['product_attribute_id']) {
            // used on order confirmation process
            $id_product_attribute = $product['product_attribute_id'];
        } elseif (isset($product['cache_default_attribute']) && $product['cache_default_attribute']) {
            // used on order confirmation process
            $id_product_attribute = $product['cache_default_attribute'];
        }

        // Filter the right price
        if (isset($product['ga_variant'])) {
            // for products with variant
            $product_price_wt = $product['ga_variant']['variant_price'];
        } elseif (isset($product['price_wt'])) {
            // used on product page, checkout
            $product_price_wt = $product['price_wt'];
        } elseif (isset($product['product_price_wt'])) {
            // used on order complete
            $product_price_wt = $product['product_price_wt'];
        } elseif (isset($product['price'])) {
            // should be the last elseif to avoid
            // bad prices in other situations
            // used on product lists, cart
            $product_price_wt = $product['price'];
        }

        // Filter the right quantity value
        if (isset($product['cart_quantity']) && $product['cart_quantity'] > 0 && $quantity_wanted == null) {
            // cart section
            $quantity_wanted = $product['cart_quantity'];
        } elseif (isset($product['product_quantity']) && $product['product_quantity'] > 0 && $quantity_wanted == null) {
            // checkout section
            $quantity_wanted = $product['product_quantity'];
        } elseif (isset($product['product_quantity_refunded']) &&
            $product['product_quantity_refunded'] > 0 &&
            $quantity_wanted == null) {
            // on refund products
            $quantity_wanted = $product['product_quantity_refunded'];
        }

        // Normalize product model
        $tag_product = array(
            'id' => isset($product['id_product']) ? $product['id_product'] : $product['id'],
            'name' => isset($product['name']) ? $product['name'] : $product['product_name'],
            'variant' => isset($product['ga_variant']) ? $product['ga_variant']['variant_name'] : null,
            'brand' => isset($product['manufacturer_name']) ? $product['manufacturer_name'] : null,
            'category' => isset($product['ga_category']) ? $product['ga_category'] : null,
            'list_name' => isset($list) ? $list : null,
            'list_position' => isset($position) ? (int)$position : null,
            'quantity' => (int)$quantity_wanted > 0 ? (int)$quantity_wanted : null,
            'price' => isset($product_price_wt) ? (float)round($product_price_wt, 2) : null,
            // used on scroll tracking for Remarketing Dynamic
            'id_attribute' => isset($id_product_attribute) ? $id_product_attribute : null
        );

        return $tag_product;
    }

    ///////////////////////////////
    /// Handle Order Data

    public static function getCoupon($order)
    {
        $coupon = null;
        $coupons = array();

        if (Validate::isLoadedObject($order)) {
            // Get Discounts applied in the order
            $cart_rules = $order->getCartRules();

            // get coupon name into coupons array
            foreach ($cart_rules as $cart_rule) {
                $coupons[] = $cart_rule['name'];
            }

            // set all coupons in one string
            $coupon = implode(' / ', $coupons);
        }
        return $coupon;
    }

    public static function getProductsRefund($id_order, $id_lang, $id_shop)
    {
        $sql = 'SELECT `product_id` AS `id_product`, `product_name`, `product_attribute_id`, 
            `unit_price_tax_incl` AS `price`, `product_quantity_refunded`
            FROM `'._DB_PREFIX_.'order_detail`
            WHERE `id_order` = '.(int)$id_order
        ;

        $products_refund = Db::getInstance()->executeS($sql);

        $products_refund = self::getVariants($products_refund);
        $products_refund = self::getNamesWithoutVariant($products_refund, $id_lang, $id_shop);
        $products_refund = self::tagProducts($products_refund, null, null, true);

        return $products_refund;
    }

    public static function getSourceConnection($order_id, $order_date)
    {
        $source_connection = '';
        // get exclusion list for manual transactions
        $referral_exclusion_list = Configuration::get('RC_PGANALYTICS_REL');
        $search = array(',','.');
        $replace = array('|','\.');
        $pattern_rel = '/((?!\.|-)[a-z0-9-]*\.)?('.str_replace($search, $replace, $referral_exclusion_list).')$/A';

        // get last connections from order date in a 30 day window
        $query = 'SELECT cos.http_referer
            FROM '._DB_PREFIX_.'orders o
            INNER JOIN '._DB_PREFIX_.'guest g ON g.id_customer = o.id_customer
            INNER JOIN '._DB_PREFIX_.'connections co  ON co.id_guest = g.id_guest
            INNER JOIN '._DB_PREFIX_.'connections_source cos ON cos.id_connections = co.id_connections
            WHERE id_order = '.(int)($order_id).' 
            AND co.date_add <= "'.pSQL($order_date).'" 
            AND co.date_add >= DATE_SUB("'.pSQL($order_date).'", INTERVAL 30 DAY )
            ORDER BY cos.date_add DESC'
        ;

        // execute query
        $order_sources = Db::getInstance()->executeS($query);

        // process order sources
        if ($order_sources) {
            foreach ($order_sources as $source) {
                $host = parse_url($source['http_referer'], PHP_URL_HOST);
                // if referrer is self domain skip it
                if (!preg_match($pattern_rel, $host)) {
                    // set the referrer and break it
                    $source_connection = $source['http_referer'];
                    break;
                }
            }
        }

        return $source_connection;
    }

    public static function getGaUtmValues($order_module, $reference)
    {
        // default utm data (direct) / (none)
        $utm_data = array(
            'source' => '(direct)',
            'medium' => '(none)',
            'campaign' => ''
        );

        // List of modules that place orders directly to DB
        $marketplaces = array(
            'amazon' => array('amazon'),
            'ebay' => array('ebay', 'prestabay')
        );

        $market_source = '';

        // check if order is placed by market place module
        foreach ($marketplaces as $marketplace => $market_modules) {
            // if order is placed by listed marketplaces change default campaign source
            if (in_array($order_module, $market_modules)) {
                $market_source = $marketplace;
                break;
            }
        }

        if ($market_source) {
            $utm_data['source'] = $market_source;
            $utm_data['medium'] = 'referral';
            $utm_data['campaign'] = 'marketplace';
        } elseif ($reference) {
            // get the url host
            $reference_host = parse_url($reference, PHP_URL_HOST);

            // verify that reference don't come from self shop
            $utm_data['source'] = $reference_host;
            $utm_data['medium'] = 'referral';
            $utm_data['campaign'] = '';
        }

        return $utm_data;
    }

    public static function getAffiliation()
    {
        $context = Context::getContext();

        $shop_name = $context->shop->name;
        $iso_lang = $context->language->iso_code;

        $affiliation = $shop_name.' - '.$iso_lang;

        return $affiliation;
    }

    ///////////////////////////////
    /// Handle Tag Order Layer

    public static function tagOrder($order, $affiliation, $coupon)
    {
        $order_tax = (float)$order['total_paid_tax_incl'] - (float)$order['total_paid_tax_excl'];
        $tag_order = array(
            'transaction_id' => (int)$order['id'],
            'affiliation' => isset($affiliation) ? $affiliation : '',
            'value' => round((float)$order['total_paid'], 2),
            'tax' => round((float)$order_tax, 2),
            'shipping' => round((float)$order['total_shipping'], 2),
            'coupon' => isset($coupon) ? $coupon : null,
        );

        return $tag_order;
    }

    ///////////////////////////////
    /// Handle Manual Transaction

    public static function curlTagTransaction($ga_id, $order, $products, $transaction_type, $action_type)
    {
        $client_id = RcPgAnalyticsClientId::getClientIdByCustomerId($order['id_customer']);

        // if customer client_id don't exist send by bo cookie
        if (!$client_id) {
            $ga_cookie = self::setAdminGaCookie();
            $client_id = $ga_cookie->client_id;
        }

        $curl_transaction = array(
            // Protocol version
            'v'     => '1',
            // Tracking ID
            'tid'   => $ga_id,
            // Data Source
            'ds'    => 'backoffice',
            // Anonymous Client ID.
            'cid'   => $client_id,
            // Transaction Type: (pageview/screenview/event/transaction/item/social/exception/timing)
            't'     => $transaction_type,
            // Transaction ID
            'ti'    => $order['id'],
            // Product Action: (detail/click/add/remove/checkout/checkout_option/purchase/refund)
            'pa'    => $action_type,
            // Currency used in the event
            'cu'    => $order['currency_iso_code']
        );

        // if user-id enabled send id customer
        if ((int)Configuration::get('RC_PGANALYTICS_UF')) {
            $curl_transaction['uid'] = $order['id_customer'];
        }

        foreach ($products as $index => $product) {
            $index += 1; // Index should start by 1

            if ($product['quantity'] > 0) {
                $curl_transaction['pr'.$index.'id'] = $product['id'];
                $curl_transaction['pr'.$index.'nm'] = $product['name'];

                if ($product['variant'] !== null) {
                    $curl_transaction['pr'.$index.'va'] = $product['variant'];
                }
                if ($action_type == 'purchase') {
                    $curl_transaction['pr'.$index.'br'] = $product['brand'];
                    $curl_transaction['pr'.$index.'ca'] = $product['category'];
                }

                $curl_transaction['pr'.$index.'pr'] = (float)$product['price'];
                $curl_transaction['pr'.$index.'qt'] = (int)$product['quantity'];
            }
        }

        if ($action_type == 'purchase') {
            $tax = (float)$order['total_paid_tax_incl'] - (float)$order['total_paid_tax_excl'];
            // Total Purchase with tax and shipping
            $curl_transaction['tr'] = round((float)$order['total_paid'], 2);
            // Total Taxes
            $curl_transaction['tt'] = round((float)$tax, 2);
            // Total Shipping int
            $curl_transaction['ts'] = round((float)$order['total_shipping_tax_incl'], 2);
            // Affiliation - Ex ShopName-Lang
            $curl_transaction['ta'] = $order['affiliation'];

            if ($order['coupon']) {
                // Coupon applied in purchase
                $curl_transaction['tcc'] = $order['coupon'];
            }
            if ($order['document_reference']) {
                // send reference - Ex. http://example.com
                $curl_transaction['dr'] = $order['document_reference'];
            }

            // utm tags
            if ($order['ga_utm']) {
                if ($order['ga_utm']['source']) {
                    $curl_transaction['cs'] = $order['ga_utm']['source'];
                }
                if ($order['ga_utm']['medium']) {
                    $curl_transaction['cm'] = $order['ga_utm']['medium'];
                }
                if ($order['ga_utm']['campaign']) {
                    $curl_transaction['cn'] = $order['ga_utm']['campaign'];
                }
            }
        }

        // Prevents send cache hit, should be the latest element
        $curl_transaction['z'] = time();

        return $curl_transaction;
    }

    public static function curlTagAbortedTransaction($ga_id, $action, $order_id, $id_customer)
    {
        $client_id = RcPgAnalyticsClientId::getClientIdByCustomerId($id_customer);

        // if customer client_id don't exist send by bo cookie
        if (!$client_id) {
            $ga_cookie = self::setAdminGaCookie();
            $client_id = $ga_cookie->client_id;
        }

        $transaction_type = 'event';

        $event_params = array(
            'event_action' => $action,
            'event_category' => 'exception',
            'event_label' => 'order_id_' . $order_id,
            'event_value' => 0
        );

        $curl_transaction = array(
            // Protocol version
            'v'     => '1',
            // Tracking ID
            'tid'   => $ga_id,
            // Data Source
            'ds'    => 'backoffice',
            // Anonymous Client ID.
            'cid'   => $client_id,
            // Transaction Type: (pageview/screenview/event/transaction/item/social/exception/timing)
            't'     => $transaction_type,
            // event values
            'ec'    => $event_params['event_category'],
            'ea'    => $event_params['event_action'],
            'el'    => $event_params['event_label'],
            'ev'    => $event_params['event_value']
        );

        return $curl_transaction;
    }

    public static function curlSendGaTransaction($curl_transaction)
    {
        if (empty($curl_transaction)) {
            return false;
        }

        $url = 'https://www.google-analytics.com/collect';

        // encodes the data to be send by curl
        $payload_data = http_build_query($curl_transaction);

        // init curl object
        $curl_connect = curl_init();
        // Configure connection
        curl_setopt($curl_connect, CURLOPT_URL, $url);
        curl_setopt($curl_connect, CURLOPT_POST, true);
        curl_setopt($curl_connect, CURLOPT_POSTFIELDS, $payload_data);
        curl_setopt($curl_connect, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_connect, CURLOPT_HEADER, false);
        curl_setopt($curl_connect, CURLOPT_TIMEOUT, 3);
        curl_setopt($curl_connect, CURLOPT_CONNECTTIMEOUT, 3);
        // execute and close connection
        curl_exec($curl_connect);
        curl_close($curl_connect);

        // GA don't gives feedback if the request are incorrect, or not processed
        // So don't worth to return the curl result
        return true;
    }

    ///////////////////////////////
    /// Handle Control Table

    public static function setOrderSend($id_order, $id_shop, $sent_from = 'fo')
    {
        $date_time = date('Y-m-d H:i:s');

        $order_sent_obj = new RcPgAnalyticsOrderSent();
        $order_sent_obj->id_order = $id_order;
        $order_sent_obj->id_shop = $id_shop;
        $order_sent_obj->sent_from = $sent_from;
        $order_sent_obj->sent_at = $date_time;
        return $order_sent_obj->save();
    }

    ///////////////////////////////
    /// Handle common tracking features

    public static function setAdminGaCookie()
    {
        $ga_cookie = new CookieCore('rcganalytics', __PS_BASE_URI__);

        if (!isset($ga_cookie->client_id)) {
            // create client_id
            $expire = strtotime('+2 year');
            $ga_cookie->setExpire($expire);
            $ga_cookie->client_id = self::setUuidv4();
        }
        return $ga_cookie;
    }

    public static function setUuidv4()
    {
        $uuidv4 = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );

        return $uuidv4;
    }

    public static function isAdminGaCookieExist()
    {
        $name = 'rcganalytics';
        $domain = Context::getContext()->shop->domain;
        $cookie_name = 'PrestaShop-'.md5(_PS_VERSION_.$name.$domain);

        $ga_cookie_exist = isset($_COOKIE[$cookie_name]);

        return $ga_cookie_exist;
    }

    public static function getFeedAcronyms($prefix = '', $suffix = '')
    {
        $lang_iso = Context::getContext()->language->iso_code;
        $country_iso = Context::getContext()->country->iso_code;

        $search = array(
            '{lang}',
            '{LANG}',
            '{country}',
            '{COUNTRY}'
        );
        $replace = array(
            Tools::strtolower($lang_iso),
            Tools::strtoupper($lang_iso),
            Tools::strtolower($country_iso),
            Tools::strtoupper($country_iso)
        );
        
        // replace prefix and suffix
        $prefix = str_replace($search, $replace, $prefix);
        $suffix = str_replace($search, $replace, $suffix);

        return array(
            'prefix' => $prefix,
            'suffix' => $suffix,
        );
    }

    ///////////////////////////////
    /// AJAX actions

    public static function ajaxActionProduct($data)
    {
        $ids_detected        = $data['id_products'];
        $products_position   = $data['products_position'];
        $list                = $data['list'];
        $quantity_wanted     = $data['quantity_wanted'];
        $products_list_cache = $data['products_list_cache'];
        $track_products      = array();

        // detected products in cached products and avoid DB query
        if (!empty($ids_detected) && !empty($products_list_cache)) {
            foreach ($ids_detected as $key => $id_detected) {
                if (isset($products_list_cache[$id_detected])) {
                    // on detected cache products set ga_category
                    $products_list_cache[$id_detected]['ga_category'] = self::getGaCategory(
                        $products_list_cache[$id_detected]['id_category_default']
                    );

                    // get manufacturer name
                    if (!isset($products_list_cache[$id_detected]['manufacturer_name']) &&
                        isset($products_list_cache[$id_detected]['id_manufacturer'])
                    ) {
                        $products_list_cache[$id_detected]['manufacturer_name'] = Manufacturer::getNameById(
                            $products_list_cache[$id_detected]['id_manufacturer']
                        );
                    }

                    // check if product has attributes and attribute != 0
                    if (isset($products_list_cache[$id_detected]['id_product_attribute']) &&
                        $products_list_cache[$id_detected]['id_product_attribute']
                    ) {
                        // get variant name and price
                        $variant_values = self::getVariant(
                            $products_list_cache[$id_detected]['id_product'],
                            $products_list_cache[$id_detected]['id_product_attribute']
                        );

                        $products_list_cache[$id_detected]['ga_variant'] = $variant_values;
                    }

                    // copy cached_product to products
                    $track_products[$id_detected] = $products_list_cache[$id_detected];

                    // remove copied product from ids_detected
                    unset($ids_detected[ $key ]);
                }
            }
        }

        // detected products without cache products
        if (!empty($ids_detected)) {
            $products = self::trackProducts($ids_detected);

            foreach ($products as $key => $product) {
                $check_attribute = 0;
                // get GA category
                $product['ga_category'] = self::getGaCategory($product['id_category_default']);

                // if product has attributes get right attribute
                if ($product['id_product_attribute']) {
                    $check_attribute = $product['id_product_attribute'];
                } elseif ($product['cache_default_attribute']) {
                    $check_attribute = $product['cache_default_attribute'];
                }

                if ($check_attribute) {
                    // get variant data, name and price
                    $variant_values = self::getVariant($product['id_product'], $check_attribute);

                    $product['ga_variant'] = $variant_values;
                } else {
                    // price for product without variant
                    $product['price'] = (float)Product::getPriceStatic($product['id_product'], true, null, 2);
                }

                $track_products[ $key ] = $product;
            }
        }

        // process detected products
        if (!empty($track_products)) {
            $ga_products = array();

            foreach ($track_products as $key => $track_product) {
                $position = null;

                if ($products_position) {
                    // if product selected is not a default variant, product position
                    // will not match, get the position key with cache default attribute
                    $default_key = $track_product['id_product'].'-'.$track_product['cache_default_attribute'];

                    if (isset($products_position[$key])) {
                        $position = $products_position[$key];
                    } elseif (isset($products_position[$default_key])) {
                        $position = $products_position[$default_key];
                    }
                }

                $ga_products[] = self::tagProduct($track_product, $position, $list, $quantity_wanted);
            }

            return $ga_products;
        } else {
            // Error case if no id-products detected
            throw new Exception('no id-product detected');
        }
    }

    public static function ajaxActionSignUp($data)
    {
        $context = Context::getContext();
        $max_lapse = $data['maxLapse'];
        $is_guest = 0;
        $is_new_sign_up = 0;

        // get customer date creation on timestamp
        $customer_date_add = strtotime($context->customer->date_add);

        if ($customer_date_add) {
            // calc sign up time lapse
            $sign_up_lapse = time() - $customer_date_add;

            if ($sign_up_lapse < $max_lapse) {
                $is_new_sign_up = 1;
                // check if customer type is guest
                $is_guest = (int)$context->customer->is_guest;
            }
        }

        return array(
            'isNewSignUp' => $is_new_sign_up,
            'isGuest' => $is_guest
        );
    }

    public static function ajaxActionOrderComplete($data)
    {
        $id_order = $data['id_order'];
        $id_shop  = $data['id_shop'];

        // set order send to GA in data base
        return self::setOrderSend($id_order, $id_shop, 'fo');
    }

    // Aborted transaction by ad-block or by honor do not track, send an event by measure protocol
    public static function ajaxActionAbortedTransaction($data)
    {
        $ga_id = Configuration::get('RC_PGANALYTICS_ID');
        $order_id = $data['id_order'];
        $id_customer = $data['id_customer'];

        // default action blocked by ad block
        $action = 'transaction_aborted_by_ad_block';

        if ($data['doNotTrack']) {
            $action = 'transaction_aborted_by_do_not_track';
        }

        $transaction = self::curlTagAbortedTransaction($ga_id, $action, $order_id, $id_customer);

        return self::curlSendGaTransaction($transaction);
    }

    // Save GA client_id to DB for future sending
    public static function ajaxActionClientId($data)
    {
        $control_client = new RcPgAnalyticsClientId($data['id_customer']);
        $control_client->id_customer = $data['id_customer'];
        $control_client->id_shop = $data['id_shop'];
        $control_client->client_id = $data['client_id'];
        return $control_client->save();
    }

    public static function ajaxActionForceTransaction($params)
    {
        $analytics_id = Configuration::get('RC_PGANALYTICS_ID');

        // Get Order ID
        $order_id = $params['id_order'];

        if (empty($order_id)) {
            return false;
        }

        // Get all Order Data
        $obj_order = new Order($order_id);

        // Get id_lang placed on order
        $order_id_lang = $obj_order->id_lang;

        // Get id_shop placed on order
        $order_id_shop = $obj_order->id_shop;

        // Get date_add placed on order
        $order_date = $obj_order->date_add;

        // convert object to array
        $ga_order = get_object_vars($obj_order);

        $currency = CurrencyCore::getCurrency($obj_order->id_currency);

        $ga_order['currency_iso_code'] = $currency['iso_code'];

        // Check if order has been sent to GA
        $order_sent = (bool)RcPgAnalyticsOrderSent::getOrderReport($order_id, $order_id_shop);

        if (!$order_sent) {
            // get coupon names
            $ga_order['coupon'] = self::getCoupon($obj_order);

            // Get affiliation name
            $ga_order['affiliation'] = self::getAffiliation();

            // Get reference url
            $ga_order['document_reference'] = self::getSourceConnection($order_id, $order_date);

            // get ga utm campaign
            $ga_order['ga_utm'] = self::getGaUtmValues(
                $ga_order['module'],
                $ga_order['document_reference']
            );

            // Get order products
            $products = $obj_order->getProducts();

            if ($products) {
                // normalize product data
                $products = self::getNamesWithoutVariant($products, $order_id_lang, $order_id_shop);
                $products = self::getGaCategories($products);
                $products = self::getManufacturerNames($products);
                $products = self::getVariants($products);

                // Tag the product data for GA
                $products = self::tagProducts($products, null, null, true);

                // Tag order refund to send it to GA
                $transaction = self::curlTagTransaction(
                    $analytics_id,
                    $ga_order,
                    $products,
                    'event',
                    'purchase'
                );

                // send the order to GA by CURL
                self::curlSendGaTransaction($transaction);

                // set order to database
                $is_saved = self::setOrderSend($order_id, $order_id_shop, 'bo');

                if ($is_saved) {
                    return RcPgAnalyticsOrderSent::getOrderReport($order_id, $order_id_shop);
                }
            }
            return true;
        }
        return false;
    }

    public static function ajaxActionDeleteFromControlTable($params)
    {
        $result = RcPgAnalyticsOrderSent::removeOrder($params['id_order'], $params['id_shop']);
        return $result;
    }
}
