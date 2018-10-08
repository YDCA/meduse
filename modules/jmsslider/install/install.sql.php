<?php
/**
* 2007-2017 PrestaShop
*
* Slider Layer module for prestashop
*
*  @author    Joommasters <joommasters@gmail.com>
*  @copyright 2007-2017 Joommasters
*  @license   license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*  @Website: http://www.joommasters.com
*/

$query = "DROP TABLE IF EXISTS `_DB_PREFIX_jms_slides`;
CREATE TABLE IF NOT EXISTS `_DB_PREFIX_jms_slides` (
  `id_slide` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `class_suffix` varchar(100) NOT NULL,
  `bg_type` int(10) NOT NULL DEFAULT '1',
  `bg_image` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `bg_color` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '#FFF',
  `slide_link` varchar(100) NOT NULL,
  `order` int(10) NOT NULL,
  `status` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_slide`)
) ENGINE=_MYSQL_ENGINE_  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

INSERT INTO `_DB_PREFIX_jms_slides` (`id_slide`, `title`, `class_suffix`, `bg_type`, `bg_image`, `bg_color`, `slide_link`, `order`, `status`) VALUES
(7, 'Slide 1', '', 1, '6d5b249ada6428c0d9429a3997c3bb24.jpg', '#000000', '', 0, 1),
(8, 'Slide 2', '', 1, '1f0d59419caef54744707bc57e1e9b4b.jpg', '', '', 0, 1),
(9, 'Slide 3', '', 1, 'b6c4535ade92576850db92942a139d4d.jpg', '', '', 0, 1),
(10, 'Slide 4', '', 1, 'c95cbd7e95f582c8497c56b478c86b6e.jpg', '', '', 0, 1),
(11, 'Slide 5', '', 1, '06d3a30fee49bfa58364d13cfadd21f9.jpg', '', '', 0, 1),
(12, 'Slide 6', '', 1, '13642d1634cd5c18b44aa862a176e2d2.png', '', '', 0, 1),
(13, 'Slide 7', '', 1, '60ab8ccb44726d63c0b92c1d4fa6d179.jpg', '', '', 0, 1),
(14, 'Slide 8', '', 1, 'a7d1d7960842d17df9564e08e4e9861f.jpg', '', '', 0, 1);

DROP TABLE IF EXISTS `_DB_PREFIX_jms_slides_homes`;
CREATE TABLE IF NOT EXISTS `_DB_PREFIX_jms_slides_homes` (
  `id_slide` int(10) NOT NULL,
  `id_homes` varchar(100) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_slide`,`id_homes`)
) ENGINE=_MYSQL_ENGINE_ DEFAULT CHARSET=utf8;

INSERT INTO `_DB_PREFIX_jms_slides_homes` (`id_slide`, `id_homes`) VALUES
(7, '0'),
(8, '0'),
(9, '0'),
(10, '0'),
(11, '0'),
(12, '0'),
(13, '0'),
(14, '0');

DROP TABLE IF EXISTS `_DB_PREFIX_jms_slides_lang`;
CREATE TABLE IF NOT EXISTS `_DB_PREFIX_jms_slides_lang` (
  `id_slide` int(10) NOT NULL AUTO_INCREMENT,
  `id_lang` int(10) NOT NULL,
  PRIMARY KEY (`id_slide`,`id_lang`)
) ENGINE=_MYSQL_ENGINE_  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

INSERT INTO `_DB_PREFIX_jms_slides_lang` (`id_slide`, `id_lang`) VALUES
(7, 0),
(8, 0),
(9, 0),
(10, 0),
(11, 0),
(12, 0),
(13, 0),
(14, 0);

DROP TABLE IF EXISTS `_DB_PREFIX_jms_slides_layers`;
CREATE TABLE IF NOT EXISTS `_DB_PREFIX_jms_slides_layers` (
  `id_layer` int(10) NOT NULL AUTO_INCREMENT,
  `id_slide` int(10) NOT NULL,
  `data_title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data_class_suffix` varchar(50) NOT NULL,
  `data_fixed` int(10) NOT NULL DEFAULT '0',
  `data_delay` int(10) NOT NULL DEFAULT '1000',
  `data_time` int(10) NOT NULL DEFAULT '1000',
  `data_x` int(10) NOT NULL DEFAULT '0',
  `data_y` int(10) NOT NULL DEFAULT '0',
  `data_in` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'left',
  `data_out` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'right',
  `data_ease_in` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'linear',
  `data_ease_out` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'linear',
  `data_step` int(10) NOT NULL DEFAULT '0',
  `data_special` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'cycle',
  `data_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data_image` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_html` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `data_video` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `data_video_controls` int(10) NOT NULL DEFAULT '1',
  `data_video_muted` int(10) NOT NULL DEFAULT '0',
  `data_video_autoplay` int(10) NOT NULL DEFAULT '1',
  `data_video_loop` int(10) NOT NULL DEFAULT '1',
  `data_video_bg` int(10) NOT NULL DEFAULT '0',
  `data_font_size` int(10) NOT NULL DEFAULT '14',
  `data_line_height` int(10) NOT NULL,
  `data_style` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'normal',
  `data_color` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '#FFFFFF',
  `data_width` int(10) NOT NULL,
  `data_height` int(10) NOT NULL,
  `data_order` int(10) NOT NULL,
  `data_status` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_layer`,`id_slide`)
) ENGINE=_MYSQL_ENGINE_  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

INSERT INTO `_DB_PREFIX_jms_slides_layers` (`id_layer`, `id_slide`, `data_title`, `data_class_suffix`, `data_fixed`, `data_delay`, `data_time`, `data_x`, `data_y`, `data_in`, `data_out`, `data_ease_in`, `data_ease_out`, `data_step`, `data_special`, `data_type`, `data_image`, `data_html`, `data_video`, `data_video_controls`, `data_video_muted`, `data_video_autoplay`, `data_video_loop`, `data_video_bg`, `data_font_size`, `data_line_height`, `data_style`, `data_color`, `data_width`, `data_height`, `data_order`, `data_status`) VALUES
(20, 7, 'Without retails shops and middleman, luxury becomes affordable', 'karla', 0, 1000, 3000, 803, 520, 'fade', 'fade', 'linear', 'linear', 0, '', 'text', '', 'Without retails shops and middleman, luxury <br />\r\nbecomes affordable', '', 0, 0, 0, 0, 0, 16, 30, 'normal', '#666666', 200, 50, 2, 1),
(21, 7, 'Shop now', 'shopnow', 0, 1000, 3000, 885, 620, 'fade', 'fade', 'linear', 'linear', 0, '', 'text', '', '<a href=\"#\" title=\"Shop now\">Shop Now</a>', '', 0, 0, 0, 0, 0, 14, 40, 'normal', '#000000', 200, 50, 3, 1),
(22, 8, 'new way to design', 'robotoslab4', 0, 1000, 4000, 666, 370, 'top', 'bottom', 'linear', 'linear', 0, '', 'text', '', 'new way to design', '', 0, 0, 0, 0, 0, 36, 0, 'normal', '#000000', 200, 50, 0, 1),
(23, 8, 'Dephinus with a tool that makes design simple for everyone.', 'karla', 0, 1000, 4000, 801, 453, 'top', 'bottom', 'linear', 'linear', 0, '', 'text', '', 'Dephinus with a tool that makes design simple <br /> for everyone.', '', 0, 0, 0, 0, 0, 16, 0, 'normal', '#666666', 200, 50, 0, 1),
(24, 9, 'new way to design', 'robotoslab4', 0, 1000, 4000, 649, 384, 'fade', 'fade', 'linear', 'linear', 0, '', 'text', '', 'new way to design', '', 0, 0, 0, 0, 0, 36, 0, 'normal', '#ffffff', 200, 50, 0, 1),
(25, 9, 'Dephinus with a tool that makes design simple for everyone.', 'karla', 0, 1000, 4000, 778, 466, 'fade', 'fade', 'linear', 'linear', 0, '', 'text', '', 'Dephinus with a tool that makes design simple <br /> for everyone.', '', 0, 0, 0, 0, 0, 16, 0, 'normal', '#ffffff', 200, 50, 0, 1),
(26, 10, 'New Layer Image', '', 0, 1000, 3000, 741, 347, 'fade', 'fade', 'linear', 'linear', 0, '', 'image', 'box.png', '', '', 0, 0, 0, 0, 0, 14, 0, 'normal', '#FFFFFF', 468, 182, 0, 1),
(27, 10, 'Shop Now', 'shopnow shopnow-white', 0, 1000, 3000, 1026, 527, 'fade', 'fade', 'linear', 'linear', 0, '', 'text', '', '<a href=\"#\" title=\"Shop now\">Shop Now</a>', '', 0, 0, 0, 0, 0, 14, 0, 'normal', '#ffffff', 88, 21, 0, 1),
(28, 11, 'Sale', '', 0, 1000, 4000, 574, 307, 'left', 'right', 'linear', 'linear', 0, '', 'image', 'sale.png', '', '', 0, 0, 0, 0, 0, 14, 0, 'normal', '#FFFFFF', 754, 218, 0, 1),
(29, 11, 'sale up to 50%', 'sale-up karla', 0, 1000, 4000, 575, 575, 'left', 'right', 'linear', 'linear', 0, '', 'text', '', '<a href=\"#\">sale up to 50%</a>', '', 0, 0, 0, 0, 0, 18, 0, 'normal', '#000000', 200, 50, 0, 1),
(30, 12, 'Chair', '', 0, 1000, 4000, 355, 85, 'fade', 'fade', 'linear', 'linear', 0, '', 'image', 'ch1.jpg', '', '', 0, 0, 0, 0, 0, 14, 0, 'normal', '#FFFFFF', 420, 315, 0, 1),
(31, 12, 'Chair collection', 'robotoslab2', 0, 1000, 4000, 35, 103, 'left', 'right', 'linear', 'linear', 0, '', 'text', '', 'Chair collection', '', 0, 0, 0, 0, 0, 24, 0, 'normal', '#000000', 200, 50, 0, 1),
(33, 12, 'By Delphinus design', 'karla', 0, 1000, 4000, 35, 147, 'left', 'right', 'linear', 'linear', 0, '', 'text', '', 'By Delphinus design', '', 0, 0, 0, 0, 0, 14, 0, 'normal', '#999999', 200, 50, 0, 1),
(34, 12, 'Lorem ipsum dolor sit amet', '', 0, 1000, 4000, 35, 192, 'left', 'right', 'linear', 'linear', 0, '', 'text', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. <br /> Vestibulum eleifend velit arcu, et blandit diam varius <br />nec. Phasellus ac massa in eros pharetra mattis ut non lectus.', '', 0, 0, 0, 0, 0, 14, 0, 'normal', '#666666', 200, 50, 0, 1),
(35, 12, 'Shop Now', 'shopnow2', 0, 1000, 4000, 35, 338, 'left', 'right', 'linear', 'linear', 0, '', 'text', '', '<a href=\"#\" title=\"Shop now\">Shop Now</a>', '', 0, 0, 0, 0, 0, 14, 0, 'normal', '#ffffff', 200, 50, 0, 1),
(36, 14, 'Logo', '', 0, 1000, 4000, 874, 375, 'top', 'top', 'linear', 'linear', 0, '', 'image', 'logo-sl.png', '', '', 0, 0, 0, 0, 0, 14, 0, 'normal', '#FFFFFF', 184, 183, 0, 1),
(37, 14, 'We are delphinus', 'karla-bold', 0, 1000, 5000, 666, 615, 'bottom', 'bottom', 'linear', 'linear', 0, '', 'text', '', 'We are delphinus', '', 0, 0, 0, 0, 0, 48, 0, 'normal', '#ffffff', 200, 50, 0, 1),
(38, 14, 'We made your home more beautiful', 'robotoslab1', 0, 1000, 5000, 814, 701, 'bottom', 'bottom', 'linear', 'linear', 0, '', 'text', '', 'We made your home more beautiful', '', 0, 0, 0, 0, 0, 16, 0, 'normal', '#ffffff', 200, 50, 0, 1),
(39, 7, '2015', '', 0, 1000, 3000, 802, 328, 'fade', 'fade', 'linear', 'linear', 0, '', 'image', '2015.png', '', '', 0, 0, 0, 0, 0, 14, 0, 'normal', '#FFFFFF', 348, 182, 0, 1);


DROP TABLE IF EXISTS `_DB_PREFIX_jms_slides_shop`;
CREATE TABLE IF NOT EXISTS `_DB_PREFIX_jms_slides_shop` (
  `id_slide` int(10) NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) NOT NULL,
  PRIMARY KEY (`id_slide`,`id_shop`)
) ENGINE=_MYSQL_ENGINE_  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

INSERT INTO `_DB_PREFIX_jms_slides_shop` (`id_slide`, `id_shop`) VALUES
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1);
";
