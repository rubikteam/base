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

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbthemedream_home` (
    `id_rbthemedream_home` int(11) NOT NULL AUTO_INCREMENT,
    `active` int(1) NOT NULL,
    PRIMARY KEY  (`id_rbthemedream_home`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbthemedream_home_lang` (
    `id_rbthemedream_home` int(11) NOT NULL,
    `id_shop` int(11) NOT NULL,
    `id_lang` int(11) NOT NULL,
    `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `data` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY  (`id_rbthemedream_home`, `id_shop`, `id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbthemedream_home_shop` (
    `id_rbthemedream_home` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(11) NOT NULL,
    `active` int(1) NOT NULL,
    PRIMARY KEY  (`id_rbthemedream_home`, `id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbthemedream_link` (
    `id_rbthemedream_link` int(11) NOT NULL AUTO_INCREMENT,
    `id_hook` int(11) NOT NULL,
    `position` int(11) NOT NULL,
    `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
    PRIMARY KEY  (`id_rbthemedream_link`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbthemedream_link_lang` (
    `id_rbthemedream_link` int(11),
    `id_lang` int(11) NOT NULL,
    `name` varchar(255) NULL DEFAULT NULL,
    PRIMARY KEY  (`id_rbthemedream_link`, `id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'rbthemedream_link_shop` (
    `id_rbthemedream_link` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(11) NOT NULL,
    PRIMARY KEY  (`id_rbthemedream_link`, `id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
