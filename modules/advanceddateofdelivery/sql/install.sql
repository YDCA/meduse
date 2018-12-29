CREATE TABLE IF NOT EXISTS `PREFIX_adod_carriers` (
  `id_carrier_reference` int(11) NOT NULL,
  `active` tinyint(1) unsigned NOT NULL,
  `processing_days_min` int(11) NOT NULL,
  `processing_days_max` int(11) NOT NULL,
  `hour_limit` time NOT NULL,
  `delivery_days` varchar(255) NOT NULL,
  UNIQUE KEY `id_carrier_reference` (`id_carrier_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `PREFIX_adod_product` (
  `id_product` int(11) NOT NULL,
  `id_product_attribute` int(11) NOT NULL,
  `in_stock` int(11) NOT NULL,
  `out_stock` int(11) NOT NULL,
  UNIQUE KEY `product_dates` (`id_product`,`id_product_attribute`),
  KEY `id_product` (`id_product`),
  KEY `id_product_attribute` (`id_product_attribute`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;