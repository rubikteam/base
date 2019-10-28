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

require_once _PS_MODULE_DIR_.'rbthemeslider/rbthemeslider.php';

$generalSettings = new UniteSettingsRb();
$modules = new Rbthemeslider();

$generalSettings->addSelect(
	"role",
	array(
		UniteBaseAdminClassRb::ROLE_ADMIN => $modules->l('To_Admin'),
    	UniteBaseAdminClassRb::ROLE_EDITOR => $modules->l('Editor Admin'),
    	UniteBaseAdminClassRb::ROLE_AUTHOR => $modules->l('Author Editor Admin')
    ),
	$modules->l('Plugin Permission'),
	UniteBaseAdminClassRb::ROLE_ADMIN,
	array(
		"description" => $modules->l('Edit Plugin')
	)
);

$generalSettings->addRadio(
	"includes_globally",
	array(
		"on" => $modules->l('On'),
		"off" => $modules->l('Off')
	),
	$modules->l('RbSlider Libraries'),
	"on",
	array(
		"description" => $modules->l('Shortcode Exists')
	)
);

$generalSettings->addTextBox(
	"pages_for_includes",
	"",
	$modules->l('Pages RbSlider'),
	array(
		"description" => $modules->l('Specify Homepage')
	)
);

$generalSettings->addRadio(
	"js_to_footer",
	array(
		"on" => $modules->l('On'),
		"off" => $modules->l('Off')
	),
	$modules->l('JS Includes'),
	"off",
	array(
		"description" => $modules->l('Fixing Javascript')
	)
);

$generalSettings->addRadio(
	"show_dev_export",
	array(
		"on" => $modules->l('On'),
		"off" => $modules->l('Off')
	),
	$modules->l('Export Option'),
	"off",
	array(
		"description" => $modules->l('Export Slider')
	)
);

$generalSettings->addRadio(
	"enable_logs",
	array(
		"on" => $modules->l('On'),
		"off" => $modules->l('Off')
	),
	$modules->l('Enable Log'),
	"off",
	array(
		"description" => $modules->l('Enable Console')
	)
);

$operations = new RbOperations();
$arrValues = $operations->getGeneralSettingsValues();
$generalSettings->setStoredValues($arrValues);

self::storeSettings("general", $generalSettings);
