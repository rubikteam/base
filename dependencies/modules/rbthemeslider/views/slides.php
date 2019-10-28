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

$operations = new RbOperations();
$sliderID = RbSliderAdmin::getGetVar("id");
$modules = new Rbthemeslider();

if (empty($sliderID)) {
    UniteFunctionsRb::throwError("Slider ID not found");
}

$slider = new RbSlider();
$slider->initByID($sliderID);
$sliderParams = $slider->getParams();
$arrSliders = $slider->getArrSlidersShort($sliderID);

$selectSliders = UniteFunctionsRb::getHTMLSelect(
    $arrSliders,
    "",
    "id='selectSliders'",
    true
);

$numSliders = count($arrSliders);

//set iframe parameters	
$width = $sliderParams["width"];
$height = $sliderParams["height"];
$iframeWidth = $width + 60;
$iframeHeight = $height + 50;
$iframeStyle = "width:" . $iframeWidth . "px;height:" . $iframeHeight . "px;";
$arrSlides = $slider->getSlides(false);
$numSlides = count($arrSlides);
$linksSliderSettings = RbSliderAdmin::getViewUrl(RbSliderAdmin::VIEW_SLIDER, "id=$sliderID");
$patternViewSlide = RbSliderAdmin::getViewUrl("slide", "id=[slideid]");

RbGlobalObject::setVar('arrSlides', $arrSlides);
RbGlobalObject::setVar('numSliders', $numSliders);
RbGlobalObject::setVar('numSlides', $numSlides);
RbGlobalObject::setVar('slider', $slider);
RbGlobalObject::setVar('operations', $operations);
RbGlobalObject::setVar('linksSliderSettings', $linksSliderSettings);
RbGlobalObject::setVar('selectSliders', $selectSliders);
RbGlobalObject::setVar('patternViewSlide', $patternViewSlide);
RbGlobalObject::setVar('sliderID', $sliderID);
RbGlobalObject::setVar('iframeStyle', $iframeStyle);

//treat in case of slides from gallery
if ($slider->isSlidesFromPosts() == false) {
    $templateName = "slides_gallery";
    $isPsmlExists = UnitePsmlRb::isPsmlExists();
    $usePsml = $slider->getParam("use_psml", "off");
    $psmlActive = false;

    if ($isPsmlExists && $usePsml == "on") {
        $psmlActive = true;
        $urlIconDelete = RbSliderAdmin::$url_plugin . "views/img/images/icon-trash.png";
        $urlIconEdit = RbSliderAdmin::$url_plugin . "views/img/images/icon-edit.png";
        $urlIconPreview = RbSliderAdmin::$url_plugin . "views/img/images/preview.png";
        $textDelete = Rbthemeslider::$lang['Delete_Slide'];
        $textEdit = Rbthemeslider::$lang['Edit_Slide'];
        $textPreview = Rbthemeslider::$lang['Preview_Slide'];
        $htmlBefore = "";
        $htmlBefore .= "<li class='item_operation operation_delete'><a data-operation='delete' href='javascript:void(0)'>" . "\n";
        $htmlBefore .= "<img src='" . $urlIconDelete . "'/> " . $textDelete . "\n";
        $htmlBefore .= "</a></li>" . "\n";
        $htmlBefore .= "<li class='item_operation operation_edit'><a data-operation='edit' href='javascript:void(0)'>" . "\n";
        $htmlBefore .= "<img src='" . $urlIconEdit . "'/> " . $textEdit . "\n";
        $htmlBefore .= "</a></li>" . "\n";
        $htmlBefore .= "<li class='item_operation operation_preview'><a data-operation='preview' href='javascript:void(0)'>" . "\n";
        $htmlBefore .= "<img src='" . $urlIconPreview . "'/> " . $textPreview . "\n";
        $htmlBefore .= "</a></li>" . "\n";
        $htmlBefore .= "<li class='item_operation operation_sap'>" . "\n";
        $htmlBefore .= "<div class='float_menu_sap'></div>" . "\n";
        $htmlBefore .= "</a></li>" . "\n";
        $langFloatMenu = UnitePsmlRb::getLangsWithFlagsHtmlList("id='slides_langs_float' class='slides_langs_float'", $htmlBefore);
        RbGlobalObject::setVar('langFloatMenu', $langFloatMenu);
    }

    RbGlobalObject::setVar('psmlActive', $psmlActive);
} else {
    $templateName = "slides_posts";
    $sourceType = $slider->getParam("source_type", "posts");
    $showSortBy = ($sourceType == "posts") ? true : false;
    RbGlobalObject::setVar('showSortBy', $showSortBy);

    //get button links
    $urlNewPost = UniteFunctionsPSRb::getUrlNewPost();

    $linkNewPost = UniteFunctionsRb::getHtmlLink(
        $urlNewPost,
        $modules->l('New_Post'), 
        "button_new_post",
        "button-primary rbblue",
        true
    );

    RbGlobalObject::setVar('linkNewPost', $linkNewPost);

    //get ordering
    $arrSortBy = UniteFunctionsPSRb::getArrSortBy();

    $sortBy = $slider->getParam(
        "post_sortby",
        RbSlider::DEFAULT_POST_SORTBY
    );

    $selectSortBy = UniteFunctionsRb::getHTMLSelect(
        $arrSortBy,
        $sortBy,
        "id='select_sortby'",
        true
    );

    RbGlobalObject::setVar('selectSortBy', $selectSortBy);
}

require RbSliderAdmin::getPathTemplate($templateName);

RbGlobalObject::reset();
