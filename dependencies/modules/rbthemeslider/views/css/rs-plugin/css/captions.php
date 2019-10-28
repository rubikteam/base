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

header("Content-Type: text/css; charset=utf-8");
$absolute_path = str_replace('\\', '/', __FILE__);
$path_to_file = explode('/rbslider/rs-plugin/', $absolute_path);
$path_to_ps = $path_to_file[0].'/../..';

require_once($path_to_ps.'/ps-load.php');

$currentFolder = dirname($absolute_path);

//include framework files
require_once $currentFolder . '/../../library/framework/include_framework.php';

$db = new UniteDBRb();
$styles = $db->fetch(GlobalsRbSlider::$table_css);
echo UniteCssParserRb::parseDbArrayToCss($styles, "\n");
