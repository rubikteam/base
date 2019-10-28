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

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbblog_post` (
    `id_rbblog_post` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_rbblog_category` INT( 11 ) UNSIGNED NOT NULL,
    `id_rbblog_post_type` INT( 11 ) UNSIGNED NOT NULL,
    `id_rbblog_author` INT( 11 ) UNSIGNED NOT NULL DEFAULT 0,
    `author` VARCHAR(60) NOT NULL,
    `likes` INT( 11 ) UNSIGNED NOT NULL DEFAULT 0,
    `views` INT( 11 ) UNSIGNED NOT NULL DEFAULT 0,
    `allow_comments` tinyint(1) UNSIGNED NOT NULL DEFAULT 3,
    `is_featured` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
    `access` TEXT NOT NULL,
    `cover` TEXT NOT NULL,
    `featured` TEXT NOT NULL,
    `id_product` TEXT NOT NULL,
    `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY (`id_rbblog_post`)
) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbblog_post_lang` (
    `id_rbblog_post` int(10) UNSIGNED NOT NULL,
    `id_lang` int(10) UNSIGNED NOT NULL,
    `title` varchar(255) NOT NULL,
    `meta_title` varchar(255) NOT NULL,
    `meta_description` varchar(255) NOT NULL,
    `meta_keywords` varchar(255) NOT NULL,
    `canonical` text NOT NULL,
    `short_content` longtext,
    `content` longtext,
    `video_code` text,
    `external_url` text,
    `link_rewrite` varchar(255) NOT NULL,
    PRIMARY KEY (`id_rbblog_post`,`id_lang`)
) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbblog_post_shop` (
    `id_rbblog_post` int(11) UNSIGNED NOT NULL,
    `id_shop` int(11) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_rbblog_post`,`id_shop`)
) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbblog_post_image` (
    `id_rbblog_post_image` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_rbblog_post` INT( 11 ) UNSIGNED NOT NULL,
    `position` int(10) UNSIGNED NOT NULL,
    `image` varchar(255) NOT NULL,
    PRIMARY KEY (`id_rbblog_post_image`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbblog_post_product` (
    `id_rbblog_post` INT( 11 ) UNSIGNED NOT NULL,
    `id_product` INT( 11 ) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_rbblog_post`,`id_product`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbblog_post_type` (
    `id_rbblog_post_type` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `slug` VARCHAR(255),
    `description` TEXT,
    PRIMARY KEY (`id_rbblog_post_type`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbblog_category` (
	`id_rbblog_category` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
	`cover` VARCHAR(5) NOT NULL,
	`position` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`id_parent` int(10) UNSIGNED NOT NULL DEFAULT 0,
	`active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
	`date_add` datetime NOT NULL,
	`date_upd` datetime NOT NULL,
	PRIMARY KEY (`id_rbblog_category`)
) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbblog_category_lang` (
	`id_rbblog_category` int(10) UNSIGNED NOT NULL,
	`id_lang` int(10) UNSIGNED NOT NULL,
	`name` varchar(128) NOT NULL,
	`description` text,
	`link_rewrite` varchar(128) NOT NULL,
	`meta_title` varchar(128) NOT NULL,
	`meta_keywords` varchar(255) NOT NULL,
	`canonical` text NOT NULL,
	`meta_description` varchar(255) NOT NULL,
	PRIMARY KEY (`id_rbblog_category`,`id_lang`)
) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbblog_category_shop` (
    `id_rbblog_category` int(11) UNSIGNED NOT NULL,
    `id_shop` int(11) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_rbblog_category`,`id_shop`)
) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';


$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbblog_tag` (
    `id_rbblog_tag` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_lang` INT( 11 ) UNSIGNED NOT NULL,
    `name` VARCHAR(60) NOT NULL,
    PRIMARY KEY (`id_rbblog_tag`)
) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbblog_post_tag` (
    `id_rbblog_post` INT( 11 ) UNSIGNED NOT NULL,
    `id_rbblog_tag` INT( 11 ) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_rbblog_post`, `id_rbblog_tag`),
    KEY (`id_rbblog_tag`)
) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbblog_comment` (
    `id_rbblog_comment` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_rbblog_post` INT( 11 ) DEFAULT NULL,
    `id_parent` INT( 11 ) DEFAULT NULL,
    `id_customer` INT( 11 ) DEFAULT NULL,
    `id_guest` INT( 11 ) DEFAULT NULL,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `comment` text NOT NULL,
    `active` tinyint(1) unsigned NOT NULL,
    `ip` varchar(255) NOT NULL,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY (`id_rbblog_comment`)
) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
