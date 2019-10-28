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

$slideID = UniteFunctionsRb::getGetVar("id");
$slide = new RbSlide();
$slide->initByID($slideID);
$slideParams = $slide->getParams();
$operations = RbGlobalObject::getOpInstance();

//init slider object
$sliderID = $slide->getSliderID();
$slider = new RbSlider();
$slider->initByID($sliderID);
$sliderParams = $slider->getParams();
$arrSlideNames = $slider->getArrSlideNames();

//check if slider is template
$sliderTemplate = $slider->getParam("template", "false");

//set slide delay
$sliderDelay = $slider->getParam("delay", "9000");
$slideDelay = $slide->getParam("delay", "");

if (empty($slideDelay)) {
    $slideDelay = $sliderDelay;
}

RbGlobalObject::setVar('slider', $slider);
RbGlobalObject::setVar('sliderDelay', $slideDelay);

require self::getSettingsFilePath("slide_settings");

require self::getSettingsFilePath("layer_settings");

//add plugins.min.js 
self::addScript("jquery.themepunch.tools.min", "rs-plugin/js");
$settingsLayerOutput = new UniteSettingsProductSidebarRb();
$settingsSlideOutput = new UniteSettingsRbProductRb();
$arrLayers = $slide->getLayers();
$loadGoogleFont = $slider->getParam("load_googlefont", "false");

//get settings objects
$settingsLayer = self::getSettings("layer_settings");
$settingsSlide = self::getSettings("slide_settings");
$cssContent = self::getSettings("css_captions_content");
$arrCaptionClasses = $operations->getArrCaptionClasses($cssContent);
$arrFontFamily = $operations->getArrFontFamilys($slider);
$arrCSS = $operations->getCaptionsContentArray();
$arrButtonClasses = $operations->getButtonClasses();
$urlCaptionsCSS = GlobalsRbSlider::$urlCaptionsCSS;
$arrAnim = $operations->getFullCustomAnimations();

//set layer caption as first caption class
$firstCaption = !empty($arrCaptionClasses) ? $arrCaptionClasses[0] : "";
$settingsLayer->updateSettingValue("layer_caption", $firstCaption);

//set stored values from "slide params"
$settingsSlide->setStoredValues($slideParams);

//init the settings output object
$settingsLayerOutput->init($settingsLayer);
$settingsSlideOutput->init($settingsSlide);

//set various parameters needed for the page
$width = $sliderParams["width"];
$height = $sliderParams["height"];
$imageUrl = $slide->getImageUrl();
$imageID = $slide->getImageID();
$imageFilename = $slide->getImageFilename();
$style = "height:" . $height . "px;"; //
$divLayersWidth = "width:" . $width . "px;";
$divbgminwidth = "min-width:" . $width . "px;";

//set iframe parameters
$iframeWidth = $width + 60;
$iframeHeight = $height + 50;
$iframeStyle = "width:" . $iframeWidth . "px;height:" . $iframeHeight . "px;";
$closeUrl = self::getViewUrl(RbSliderAdmin::VIEW_SLIDES, "id=" . $sliderID);
$jsonLayers = UniteFunctionsRb::jsonEncodeForClientSide($arrLayers);
$jsonCaptions = UniteFunctionsRb::jsonEncodeForClientSide($arrCaptionClasses);
$jsonFontFamilys = UniteFunctionsRb::jsonEncodeForClientSide($arrFontFamily);
$arrCssStyles = UniteFunctionsRb::jsonEncodeForClientSide($arrCSS);
$arrCustomAnim = UniteFunctionsRb::jsonEncodeForClientSide($arrAnim);

//bg type params
$bgType = UniteFunctionsRb::getVal($slideParams, "background_type", "image");
$slideBGColor = UniteFunctionsRb::getVal($slideParams, "slide_bg_color", "#E7E7E7");
$divLayersClass = "slide_layers";
$bgSolidPickerProps = 'class="inputColorPicker slide_bg_color disabled" disabled="disabled"';

$bgFit = UniteFunctionsRb::getVal($slideParams, "bg_fit", "cover");
$bgFitX = (int) (UniteFunctionsRb::getVal($slideParams, "bg_fit_x", "100"));
$bgFitY = (int) (UniteFunctionsRb::getVal($slideParams, "bg_fit_y", "100"));
$bgPosition = UniteFunctionsRb::getVal($slideParams, "bg_position", "center top");
$bgPositionX = (int) (UniteFunctionsRb::getVal($slideParams, "bg_position_x", "0"));
$bgPositionY = (int) (UniteFunctionsRb::getVal($slideParams, "bg_position_y", "0"));
$bgEndPosition = UniteFunctionsRb::getVal($slideParams, "bg_end_position", "center top");
$bgEndPositionX = (int) (UniteFunctionsRb::getVal($slideParams, "bg_end_position_x", "0"));
$bgEndPositionY = (int) (UniteFunctionsRb::getVal($slideParams, "bg_end_position_y", "0"));
$kenburn_effect = UniteFunctionsRb::getVal($slideParams, "kenburn_effect", "off");
$kb_duration = UniteFunctionsRb::getVal($slideParams, "kb_duration", $sliderParams["delay"]);
$kb_easing = UniteFunctionsRb::getVal($slideParams, "kb_easing", "Linear.easeNone");
$kb_start_fit = UniteFunctionsRb::getVal($slideParams, "kb_start_fit", "100");
$kb_end_fit = UniteFunctionsRb::getVal($slideParams, "kb_end_fit", "100");
$bgRepeat = UniteFunctionsRb::getVal($slideParams, "bg_repeat", "no-repeat");
$slideBGExternal = UniteFunctionsRb::getVal($slideParams, "slide_bg_external", "");
$style_wrapper = '';
$class_wrapper = '';

switch ($bgType) {
    case "trans":
        $divLayersClass = "slide_layers";
        $class_wrapper = "trans_bg";
        break;
    case "solid":
        $style_wrapper .= "background-color:" . $slideBGColor . ";";
        $bgSolidPickerProps = 'class="inputColorPicker slide_bg_color" style="background-color:' . $slideBGColor . '"';
        break;
    case "image":
        $style_wrapper .= "background-image:url('" . $imageUrl . "');";
        if ($bgFit == 'percentage') {
            $style_wrapper .= "background-size: " . $bgFitX . '% ' . $bgFitY . '%;';
        } else {
            $style_wrapper .= "background-size: " . $bgFit . ";";
        }
        if ($bgPosition == 'percentage') {
            $style_wrapper .= "background-position: " . $bgPositionX . '% ' . $bgPositionY . '%;';
        } else {
            $style_wrapper .= "background-position: " . $bgPosition . ";";
        }
        $style_wrapper .= "background-repeat: " . $bgRepeat . ";";
        break;
    case "external":
        $style_wrapper .= "background-image:url('" . $slideBGExternal . "');";
        if ($bgFit == 'percentage') {
            $style_wrapper .= "background-size: " . $bgFitX . '% ' . $bgFitY . '%;';
        } else {
            $style_wrapper .= "background-size: " . $bgFit . ";";
        }
        if ($bgPosition == 'percentage') {
            $style_wrapper .= "background-position: " . $bgPositionX . '% ' . $bgPositionY . '%;';
        } else {
            $style_wrapper .= "background-position: " . $bgPosition . ";";
        }
        $style_wrapper .= "background-repeat: " . $bgRepeat . ";";
        break;
}

$slideTitle = $slide->getParam("title", "Slide");
$slideOrder = $slide->getOrder();

//treat multilanguage
$isPsmlExists = UnitePsmlRb::isPsmlExists();
$usePsml = $slider->getParam("use_psml", "off");
$psmlActive = false;

if (!$slide->isStaticSlide()) {
    if ($isPsmlExists && $usePsml == "on") {
        $psmlActive = true;
        $parentSlide = $slide->getParentSlide();
        $arrChildLangs = $parentSlide->getArrChildrenLangs();
        RbGlobalObject::setVar('arrChildLangs', $arrChildLangs);
    }
}

RbGlobalObject::setVar('psmlActive', $psmlActive);
RbGlobalObject::setVar('usePsml', $usePsml);
RbGlobalObject::setVar('isPsmlExists', $isPsmlExists);

require self::getPathTemplate("slide");
