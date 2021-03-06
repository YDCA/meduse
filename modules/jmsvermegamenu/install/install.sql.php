<?php
/**
* 2007-2017 PrestaShop
*
* Jms Mega Menu module for prestashop
*
*  @author    Joommasters <joommasters@gmail.com>
*  @copyright 2007-2017 Joommasters
*  @license   license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*  @Website: http://www.joommasters.com
*/

$query = "DROP TABLE IF EXISTS `_DB_PREFIX_jmsvermegamenu`;
CREATE TABLE IF NOT EXISTS `_DB_PREFIX_jmsvermegamenu` (
  `mitem_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(11) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL,
  `type` varchar(45) NOT NULL,
  `value` varchar(255) NOT NULL,
  `html_content` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `target` varchar(25) NOT NULL,
  `params` text NOT NULL,
  `ordering` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mitem_id`)
) ENGINE=_MYSQL_ENGINE_  DEFAULT CHARSET=utf8 AUTO_INCREMENT=236 ;

INSERT INTO `_DB_PREFIX_jmsvermegamenu` (`mitem_id`, `id_shop`, `parent_id`, `type`, `value`, `html_content`, `active`, `target`, `params`, `ordering`) VALUES
(217, 1, 0, 'category', '12', '', 1, '_self', '', 1),
(218, 1, 0, 'category', '14', '', 1, '_self', '', 2),
(219, 1, 0, 'link', '#', '', 1, '_self', '', 3),
(220, 1, 0, 'link', '#', '', 1, '_self', '', 4),
(221, 1, 0, 'link', '#', '', 1, '_self', '', 5),
(222, 1, 0, 'link', '#', '', 1, '_self', '', 6),
(223, 1, 0, 'link', '#', '', 1, '_self', '', 7),
(224, 1, 0, 'link', '#', '', 1, '_self', '', 8),
(225, 1, 0, 'link', 'index.php?controller=new-products', '', 1, '_self', '', 9),
(226, 1, 0, 'link', '#', '', 1, '_self', '', 10),
(227, 1, 217, 'link', '#', '', 1, '_self', '', 1),
(228, 1, 217, 'link', '#', '', 1, '_self', '', 2),
(229, 1, 217, 'link', '#', '', 1, '_self', '', 3),
(230, 1, 218, 'link', '#', '', 1, '_self', '', 1),
(231, 1, 218, 'link', '#', '', 1, '_self', '', 2),
(232, 1, 218, 'link', '#', '', 1, '_self', '', 3),
(233, 1, 219, 'link', '#', '', 1, '_self', '', 1),
(234, 1, 219, 'link', '#', '', 1, '_self', '', 2),
(235, 1, 219, 'link', '#', '', 1, '_self', '', 3);

DROP TABLE IF EXISTS `_DB_PREFIX_jmsvermegamenu_lang`;
CREATE TABLE IF NOT EXISTS `_DB_PREFIX_jmsvermegamenu_lang` (
  `mitem_id` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=_MYSQL_ENGINE_ DEFAULT CHARSET=utf8;

INSERT INTO `_DB_PREFIX_jmsvermegamenu_lang` (`mitem_id`, `id_lang`, `name`, `description`) VALUES
(217, 1, 'Living Room', ''),
(217, 2, 'Living Room', ''),
(217, 3, 'Living Room', ''),
(217, 4, 'Living Room', ''),
(217, 5, 'Living Room', ''),
(217, 6, 'Living Room', ''),
(217, 7, 'Living Room', ''),
(217, 8, 'Living Room', ''),
(217, 9, 'Living Room', ''),
(217, 10, 'Living Room', ''),
(218, 1, 'Dining Room', ''),
(218, 2, 'Dining Room', ''),
(218, 3, 'Dining Room', ''),
(218, 4, 'Dining Room', ''),
(218, 5, 'Dining Room', ''),
(218, 6, 'Dining Room', ''),
(218, 7, 'Dining Room', ''),
(218, 8, 'Dining Room', ''),
(218, 9, 'Dining Room', ''),
(218, 10, 'Dining Room', ''),
(219, 1, 'Bed Room', ''),
(219, 2, 'Bed Room', ''),
(219, 3, 'Bed Room', ''),
(219, 4, 'Bed Room', ''),
(219, 5, 'Bed Room', ''),
(219, 6, 'Bed Room', ''),
(219, 7, 'Bed Room', ''),
(219, 8, 'Bed Room', ''),
(219, 9, 'Bed Room', ''),
(219, 10, 'Bed Room', ''),
(220, 1, 'Bath Room', ''),
(220, 2, 'Bath Room', ''),
(220, 3, 'Bath Room', ''),
(220, 4, 'Bath Room', ''),
(220, 5, 'Bath Room', ''),
(220, 6, 'Bath Room', ''),
(220, 7, 'Bath Room', ''),
(220, 8, 'Bath Room', ''),
(220, 9, 'Bath Room', ''),
(220, 10, 'Bath Room', ''),
(221, 1, 'Office Room', ''),
(221, 2, 'Office Room', ''),
(221, 3, 'Office Room', ''),
(221, 4, 'Office Room', ''),
(221, 5, 'Office Room', ''),
(221, 6, 'Office Room', ''),
(221, 7, 'Office Room', ''),
(221, 8, 'Office Room', ''),
(221, 9, 'Office Room', ''),
(221, 10, 'Office Room', ''),
(222, 1, 'Entryway & Mudroom', ''),
(222, 2, 'Entryway & Mudroom', ''),
(222, 3, 'Entryway & Mudroom', ''),
(222, 4, 'Entryway & Mudroom', ''),
(222, 5, 'Entryway & Mudroom', ''),
(222, 6, 'Entryway & Mudroom', ''),
(222, 7, 'Entryway & Mudroom', ''),
(222, 8, 'Entryway & Mudroom', ''),
(222, 9, 'Entryway & Mudroom', ''),
(222, 10, 'Entryway & Mudroom', ''),
(223, 1, 'Outdoor Furniture', ''),
(223, 2, 'Outdoor Furniture', ''),
(223, 3, 'Outdoor Furniture', ''),
(223, 4, 'Outdoor Furniture', ''),
(223, 5, 'Outdoor Furniture', ''),
(223, 6, 'Outdoor Furniture', ''),
(223, 7, 'Outdoor Furniture', ''),
(223, 8, 'Outdoor Furniture', ''),
(223, 9, 'Outdoor Furniture', ''),
(223, 10, 'Outdoor Furniture', ''),
(224, 1, 'Furniture collections', ''),
(224, 2, 'Furniture collections', ''),
(224, 3, 'Furniture collections', ''),
(224, 4, 'Furniture collections', ''),
(224, 5, 'Furniture collections', ''),
(224, 6, 'Furniture collections', ''),
(224, 7, 'Furniture collections', ''),
(224, 8, 'Furniture collections', ''),
(224, 9, 'Furniture collections', ''),
(224, 10, 'Furniture collections', ''),
(225, 1, 'New Arrivals', ''),
(225, 2, 'New Arrivals', ''),
(225, 3, 'New Arrivals', ''),
(225, 4, 'New Arrivals', ''),
(225, 5, 'New Arrivals', ''),
(225, 6, 'New Arrivals', ''),
(225, 7, 'New Arrivals', ''),
(225, 8, 'New Arrivals', ''),
(225, 9, 'New Arrivals', ''),
(225, 10, 'New Arrivals', ''),
(226, 1, 'Hot Deals', ''),
(226, 2, 'Hot Deals', ''),
(226, 3, 'Hot Deals', ''),
(226, 4, 'Hot Deals', ''),
(226, 5, 'Hot Deals', ''),
(226, 6, 'Hot Deals', ''),
(226, 7, 'Hot Deals', ''),
(226, 8, 'Hot Deals', ''),
(226, 9, 'Hot Deals', ''),
(226, 10, 'Hot Deals', ''),
(227, 1, 'Category demo 1', ''),
(227, 2, 'Category demo 1', ''),
(227, 3, 'Category demo 1', ''),
(227, 4, 'Category demo 1', ''),
(227, 5, 'Category demo 1', ''),
(227, 6, 'Category demo 1', ''),
(227, 7, 'Category demo 1', ''),
(227, 8, 'Category demo 1', ''),
(227, 9, 'Category demo 1', ''),
(227, 10, 'Category demo 1', ''),
(228, 1, 'Category demo 2', ''),
(228, 2, 'Category demo 2', ''),
(228, 3, 'Category demo 2', ''),
(228, 4, 'Category demo 2', ''),
(228, 5, 'Category demo 2', ''),
(228, 6, 'Category demo 2', ''),
(228, 7, 'Category demo 2', ''),
(228, 8, 'Category demo 2', ''),
(228, 9, 'Category demo 2', ''),
(228, 10, 'Category demo 2', ''),
(229, 1, 'Category demo 3', ''),
(229, 2, 'Category demo 3', ''),
(229, 3, 'Category demo 3', ''),
(229, 4, 'Category demo 3', ''),
(229, 5, 'Category demo 3', ''),
(229, 6, 'Category demo 3', ''),
(229, 7, 'Category demo 3', ''),
(229, 8, 'Category demo 3', ''),
(229, 9, 'Category demo 3', ''),
(229, 10, 'Category demo 3', ''),
(230, 1, 'Category demo 1', ''),
(230, 2, 'Category demo 1', ''),
(230, 3, 'Category demo 1', ''),
(230, 4, 'Category demo 1', ''),
(230, 5, 'Category demo 1', ''),
(230, 6, 'Category demo 1', ''),
(230, 7, 'Category demo 1', ''),
(230, 8, 'Category demo 1', ''),
(230, 9, 'Category demo 1', ''),
(230, 10, 'Category demo 1', ''),
(231, 1, 'Category demo 2', ''),
(231, 2, 'Category demo 2', ''),
(231, 3, 'Category demo 2', ''),
(231, 4, 'Category demo 2', ''),
(231, 5, 'Category demo 2', ''),
(231, 6, 'Category demo 2', ''),
(231, 7, 'Category demo 2', ''),
(231, 8, 'Category demo 2', ''),
(231, 9, 'Category demo 2', ''),
(231, 10, 'Category demo 2', ''),
(232, 1, 'Category demo 3', ''),
(232, 2, 'Category demo 3', ''),
(232, 3, 'Category demo 3', ''),
(232, 4, 'Category demo 3', ''),
(232, 5, 'Category demo 3', ''),
(232, 6, 'Category demo 3', ''),
(232, 7, 'Category demo 3', ''),
(232, 8, 'Category demo 3', ''),
(232, 9, 'Category demo 3', ''),
(232, 10, 'Category demo 3', ''),
(233, 1, 'Category demo 1', ''),
(233, 2, 'Category demo 1', ''),
(233, 3, 'Category demo 1', ''),
(233, 4, 'Category demo 1', ''),
(233, 5, 'Category demo 1', ''),
(233, 6, 'Category demo 1', ''),
(233, 7, 'Category demo 1', ''),
(233, 8, 'Category demo 1', ''),
(233, 9, 'Category demo 1', ''),
(233, 10, 'Category demo 1', ''),
(234, 1, 'Category demo 2', ''),
(234, 2, 'Category demo 2', ''),
(234, 3, 'Category demo 2', ''),
(234, 4, 'Category demo 2', ''),
(234, 5, 'Category demo 2', ''),
(234, 6, 'Category demo 2', ''),
(234, 7, 'Category demo 2', ''),
(234, 8, 'Category demo 2', ''),
(234, 9, 'Category demo 2', ''),
(234, 10, 'Category demo 2', ''),
(235, 1, 'Category demo 3', ''),
(235, 2, 'Category demo 3', ''),
(235, 3, 'Category demo 3', ''),
(235, 4, 'Category demo 3', ''),
(235, 5, 'Category demo 3', ''),
(235, 6, 'Category demo 3', ''),
(235, 7, 'Category demo 3', ''),
(235, 8, 'Category demo 3', ''),
(235, 9, 'Category demo 3', ''),
(235, 10, 'Category demo 3', '');
";
