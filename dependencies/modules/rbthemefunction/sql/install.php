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

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbthemefunction_review` (
    `id_rbthemefunction_review` int(11) NOT NULL AUTO_INCREMENT,
    `id_product` int(11) NOT NULL,
    `id_customer` int(11) NOT NULL,
    `id_guest` int(11) NOT NULL,
    `title` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `customer_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `grade` float unsigned NOT NULL,
    `validate` tinyint(1) NOT NULL,
    `deleted` tinyint(1) NOT NULL,
    `date_add` datetime NOT NULL,
    PRIMARY KEY  (`id_rbthemefunction_review`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbthemefunction_wishlist` (
    `id_rbthemefunction_wishlist` int(11) NOT NULL AUTO_INCREMENT,
    `id_customer` int(11) NOT NULL,
    `token` varchar(64) NOT NULL,
    `name` varchar(64) NOT NULL,
    `counter` int(11) NOT NULL,
    `id_shop` int(11) NOT NULL,
    `id_shop_group` int(11) NOT NULL,
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    `default` int(11) NOT NULL,
    PRIMARY KEY  (`id_rbthemefunction_wishlist`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbthemefunction_wishlist_product` (
    `id_rbthemefunction_wishlist_product` int(11) NOT NULL AUTO_INCREMENT,
    `id_rbthemefunction_wishlist` int(11) NOT NULL,
    `id_product` int(11) NOT NULL,
    `id_product_attribute` int(11) NOT NULL,
    `quantity` int(11) NOT NULL,
    `priority` datetime NOT NULL,
    PRIMARY KEY  (`id_rbthemefunction_wishlist_product`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rbthemefunction_facebook` (
    `id`            INT NOT NULL AUTO_INCREMENT,
    `id_user`       VARCHAR (100) NOT NULL,
    `id_shop_group` INT (11) NOT NULL,
    `id_shop`       INT (11) NOT NULL,
    `first_name`    VARCHAR (100) NOT NULL,
    `last_name`     VARCHAR (100) NOT NULL,
    `email`         VARCHAR (100) NOT NULL,
    `gender`        VARCHAR (100) NOT NULL,
    `birthday`      DATE NOT NULL,
    `date_add`      DATE NOT NULL,
    `date_upd`      DATE NOT NULL,
    PRIMARY KEY     (`id`)
) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
