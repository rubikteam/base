<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbthememenu_block` (
	`id_block` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id_column` int(11) DEFAULT NULL,
	`block_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT \'HTML\',
	`image` varchar(500) NOT NULL,
	`sort_order` int(11) NOT NULL DEFAULT \'1\',
	`enabled` tinyint(1) NOT NULL DEFAULT \'1\',
	`id_categories` varchar(500) DEFAULT NULL,
	`order_by_category` varchar(500) DEFAULT NULL,
	`id_manufacturers` varchar(500) DEFAULT NULL,
	`order_by_manufacturers` varchar(500) DEFAULT NULL,
	`display_mnu_img` tinyint(1) NOT NULL DEFAULT \'1\',
	`display_mnu_name` tinyint(1) NOT NULL DEFAULT \'1\',
	`display_mnu_inline` varchar(500) DEFAULT NULL,
	`id_suppliers` varchar(500) DEFAULT NULL,
	`order_by_suppliers` varchar(500) DEFAULT NULL,
	`display_suppliers_img` tinyint(1) NOT NULL DEFAULT \'1\',
	`display_suppliers_name` tinyint(1) NOT NULL DEFAULT \'1\',
	`display_suppliers_inline` varchar(500) DEFAULT NULL,
	`product_type` varchar(50) NOT NULL,
	`id_products` varchar(500) NOT NULL,
	`product_count` int(11) NOT NULL,
	`id_cmss` varchar(500) DEFAULT NULL,
	`display_title` tinyint(1) NOT NULL DEFAULT \'1\',
	`show_description` tinyint(1) NOT NULL DEFAULT \'0\',
	`show_clock` tinyint(1) NOT NULL DEFAULT \'0\',
	PRIMARY KEY (`id_block`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbthememenu_block_lang` (
	`id_block` int(11) NOT NULL,
	`id_lang` int(11) NOT NULL,
	`title` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
	`content` text CHARACTER SET utf8 COLLATE utf8_bin,
	`title_link` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
	`image_link` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (`id_block`,`id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbthememenu_column` (
	`id_column` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id_menu` int(11) DEFAULT NULL,
	`id_tab` int(11) DEFAULT NULL,
	`is_breaker` tinyint(1) NOT NULL DEFAULT \'0\',
	`column_size` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
	`sort_order` int(11) NOT NULL DEFAULT \'1\',
	PRIMARY KEY (`id_column`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbthememenu_menu_shop` (
	`id_menu` int(10) unsigned NOT NULL,
	`id_shop` int(11) NOT NULL,
	PRIMARY KEY (`id_menu`,`id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbthememenu_menu` (
	`id_menu` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`sort_order` int(11) NOT NULL DEFAULT \'1\',
	`enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT \'1\',
	`enabled_vertical` int(1) NOT NULL DEFAULT \'1\',
	`menu_open_new_tab` tinyint(1) UNSIGNED NOT NULL DEFAULT \'1\',
	`id_cms` int(11) DEFAULT NULL,
	`id_manufacturer` int(11) DEFAULT NULL,
	`id_supplier` int(11) DEFAULT NULL,
	`id_category` int(11) DEFAULT NULL,
	`link_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT \'FULL\',
	`sub_menu_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT \'FULL\',
	`sub_menu_max_width` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	`custom_class` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
	`menu_icon` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
	`menu_img_link` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
	`bubble_text_color` varchar(50) DEFAULT NULL,
	`menu_item_width` varchar(50)  DEFAULT NULL,
	`tab_item_width` varchar(50)  DEFAULT NULL,
	`bubble_background_color` varchar(50) DEFAULT NULL,
	`menu_ver_text_color` varchar(50) DEFAULT NULL,
	`menu_ver_background_color` varchar(50) DEFAULT NULL,
	`background_image` varchar(200) DEFAULT NULL,
	`position_background` varchar(50) DEFAULT NULL,
	`menu_ver_alway_show` tinyint(1) UNSIGNED NOT NULL DEFAULT \'0\',
	`menu_ver_hidden_border` tinyint(1) UNSIGNED NOT NULL DEFAULT \'0\',
	`display_tabs_in_full_width` INT(1) NOT NULL,
	PRIMARY KEY (`id_menu`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbthememenu_menu_lang` (
	`id_menu` int(10) UNSIGNED NOT NULL,
	`id_lang` int(10) UNSIGNED NOT NULL,
	`title` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	`link` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
	`bubble_text` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (`id_menu`,`id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbthememenu_tab` (
	`id_tab` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id_menu` INT(11) NOT NULL,
	`enabled` INT(11) NOT NULL,
	`tab_img_link` text,
	`tab_sub_width` text,
	`tab_sub_content_pos` INT(11) NOT NULL,
	`tab_icon` varchar(22),
	`bubble_text_color` varchar(50) DEFAULT NULL,
	`bubble_background_color` varchar(50) DEFAULT NULL,
	`sort_order` int(11) DEFAULT NULL,
	`background_image` varchar(200) DEFAULT NULL,
	`position_background` varchar(50) DEFAULT NULL,
	PRIMARY KEY (`id_tab`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbthememenu_tab_lang` (
	`id_tab` int(10) UNSIGNED NOT NULL,
	`id_lang` int(10) UNSIGNED NOT NULL,
	`title` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	`url` text,
	`bubble_text` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
	PRIMARY KEY (`id_tab`,`id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
