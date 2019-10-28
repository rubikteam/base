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

$sliderTemplate = false;
RbGlobalObject::setVar('sliderTemplate', $sliderTemplate);
$settingsMain = self::getSettings("slider_main");
$settingsParams = self::getSettings("slider_params");
$settingsSliderMain = new RbSliderSettingsProduct();
$settingsSliderParams = new UniteSettingsProductSidebarRb();

//get taxonomies with cats
$productprds = UniteFunctionsPSRb::getPrestaProdCat();
$postTypesWithCats = array();
$postTypesWithCats['product'] = $productprds;
$jsonTaxWithCats = UniteFunctionsRb::jsonEncodeForClientSide($postTypesWithCats);

//check existing slider data:
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

    $settingsMain = RbSliderSettingsProduct::setSettingsCustomValues($settingsMain, $arrFieldsParams);

    //set setting values from the slider

    $settingsMain->setStoredValues($arrFieldsParams);
    $settingsParams->setStoredValues($arrFieldsParams);
    $isFromStream = $slider->isSlidesFromStream();

    $slider_type = 'gallery';

    if ($isFromStream !== false) {
        $strSource = Rbthemeslider::$lang['Social'];
        $preicon = "rbicon-doc";
        $rowClass = "class='row_alt'";

        switch ($isFromStream) {
            case 'facebook':
                $strSource = Rbthemeslider::$lang['Facebook'];
                $preicon = "eg-icon-facebook";
                $numReal = $slider->getNumRealSlides(false, 'facebook');
                $slider_type = 'facebook';
                break;
            case 'twitter':
                $strSource = Rbthemeslider::$lang['Twitter'];
                $preicon = "eg-icon-twitter";
                $numReal = $slider->getNumRealSlides(false, 'twitter');
                $slider_type = 'twitter';
                break;
            case 'instagram':
                $strSource = Rbthemeslider::$lang['Instagram'];
                $preicon = "eg-icon-info";
                $numReal = $slider->getNumRealSlides(false, 'instagram');
                $slider_type = 'instagram';
                break;
            case 'flickr':
                $strSource = Rbthemeslider::$lang['Flickr'];
                $preicon = "eg-icon-flickr";
                $numReal = $slider->getNumRealSlides(false, 'flickr');
                $slider_type = 'flickr';
                break;
            case 'youtube':
                $strSource = Rbthemeslider::$lang['YouTube'];
                $preicon = "eg-icon-youtube";
                $numReal = $slider->getNumRealSlides(false, 'youtube');
                $slider_type = 'youtube';
                break;
            case 'vimeo':
                $strSource = Rbthemeslider::$lang['Vimeo'];
                $preicon = "eg-icon-vimeo";
                $numReal = $slider->getNumRealSlides(false, 'vimeo');
                $slider_type = 'vimeo';
                break;
        }
    }

    $numSlides = $slider->getNumSlides();

    if ((int) ($numSlides) == 0) {
        $first_slide_id = 'new&slider=' . $sliderID;
    } else {
        $slides = $slider->getSlides(false);

        if (!empty($slides)) {
            $first_slide_id = $slides[key($slides)]->getID();

            $first_slide_image_thumb = $slides[key($slides)]->getImageAttributes($slider_type);
        } else {
            $first_slide_id = 'new&slider=' . $sliderID;
        }
    }

    $linksEditSlides = self::getViewUrl(RbSliderAdmin::VIEW_SLIDE, "id=$first_slide_id");
    RbGlobalObject::setVar('linksEditSlides', $linksEditSlides);
    RbGlobalObject::setVar('arrFieldsParams', $arrFieldsParams);
    RbGlobalObject::setVar('slider', $slider);
    $settingsSliderParams->init($settingsParams);
    $settingsSliderMain->init($settingsMain);
    $settingsSliderParams->isAccordion(true);

    require self::getPathTemplate("slider_edit");
} else {
    $settingsMain = RbSliderSettingsProduct::setSettingsCustomValues($settingsMain, array());
    $settingsSliderParams->init($settingsParams);
    $settingsSliderMain->init($settingsMain);
    $settingsSliderParams->isAccordion(true);

    require self::getPathTemplate("slider_new");
}
