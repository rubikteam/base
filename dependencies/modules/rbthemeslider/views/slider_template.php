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

$sliderTemplate = true;
$settingsMain = self::getSettings("slider_main");
$settingsParams = self::getSettings("slider_params");
$settingsSliderMain = new RbSliderSettingsProduct();
$settingsSliderParams = new UniteSettingsProductSidebarRb();
$productprds = UniteFunctionsPSRb::getPrestaProdCat();
$postTypesWithCats = array();
$postTypesWithCats['product'] = $productprds;
$jsonTaxWithCats = UniteFunctionsRb::jsonEncodeForClientSide($postTypesWithCats);
$sliderID = self::getGetVar("id");

if (!empty($sliderID)) {
    $slider = new RbSlider();
    $slider->initByID($sliderID);

    //get setting fields
    $settingsFields = $slider->getSettingsFields();
    $arrFieldsMain = $settingsFields["main"];
    $arrFieldsParams = $settingsFields["params"];

    //modify arrows type for backword compatability
    $arrowsType = UniteFunctionsRb::getVal($arrFieldsParams, "navigation_arrows");

    switch ($arrowsType) {
        case "verticalcentered":
            $arrFieldsParams["navigation_arrows"] = "solo";

            break;
    }

    //set custom type params values:
    $settingsMain = RbSliderSettingsProduct::setSettingsCustomValues(
        $settingsMain,
        $arrFieldsParams,
        $postTypesWithCats
    );

    //set setting values from the slider
    $settingsMain->setStoredValues($arrFieldsParams);
    $settingsParams->setStoredValues($arrFieldsParams);
    $linksEditSlides = self::getViewUrl(RbSliderAdmin::VIEW_SLIDES, "id=$sliderID");
    $settingsSliderParams->init($settingsParams);
    $settingsSliderMain->init($settingsMain);
    $settingsSliderParams->isAccordion(true);

    require self::getPathTemplate("slider_edit");
} else {

    //set custom type params values:
    $settingsMain = RbSliderSettingsProduct::setSettingsCustomValues(
        $settingsMain,
        array(),
        $postTypesWithCats
    );

    $settingsSliderParams->init($settingsParams);
    $settingsSliderMain->init($settingsMain);
    $settingsSliderParams->isAccordion(true);

    require self::getPathTemplate("slider_new");
}
