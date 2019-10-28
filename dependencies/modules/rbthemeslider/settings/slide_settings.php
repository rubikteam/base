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

$modules = new Rbthemeslider();
// @codingStandardsIgnoreStart
$operations = RBGlobalObject::getOpInstance();

//set Slide settings
$arrTransitions = $operations->getArrTransition();
$arrPremiumTransitions = $operations->getArrTransition(true);
$defaultTransition = $operations->getDefaultTransition();

$arrSlideNames = array();

$slider = RbGlobalObject::getVar('slider');

if (@Rbthemeslider::getIsset($slider) && $slider->isInited()) {
    $arrSlideNames = $slider->getArrSlideNames();
}

$slideSettings = new UniteSettingsAdvancedRb();

//title
$params = array(
	"description" => $modules->l('The title of the slide, will be shown in the slides list'),
	"class" => "medium"
);

$slideSettings->addTextBox(
	"title",
	$modules->l('Slide'),
	$modules->l('Slide Title'),
	$params
);

//state
$params = array(
	"description" => $modules->l(
		'The state of the slide. The unpublished slide will be excluded from the slider'
	)
);

$slideSettings->addSelect(
	"state",
	array(
		"published" => $modules->l('Published'),
		"unpublished" => $modules->l('Unpublished')
	),
	$modules->l('State'),
	"published",
	$params
);

if (@Rbthemeslider::getIsset($slider) && $slider->isInited()) {
    $isPsmlExists = true;
    $usePsml = $slider->getParam("use_psml", "off");

    if ($isPsmlExists && $usePsml == "on") {
        $arrLangs = UnitePsmlRb::getArrLanguages();

        $params = array(
        	"description" => $modules->l('The language of the slide')
        );

        $slideSettings->addSelect(
        	"lang",
        	$arrLangs,
        	$modules->l('Language'),
        	"all",
        	$params
        );
    }
}

$params = array(
	"description" => $modules->l('If set, slide will be visible after the date is reached')
);

$slideSettings->addDatePicker(
	"date_from",
	"",
	$modules->l('Visible from'),
	$params
);

$params = array(
	"description" => $modules->l('If set, slide will be visible till the date is reached')
);

$slideSettings->addDatePicker(
	"date_to",
	"",
	$modules->l('Visible until'),
	$params
);

$slideSettings->addHr("");

//transition
$params = array(
	"description" => $modules->l('The appearance transitions of this slide'),
	"minwidth" => "250px"
);

$slideSettings->addChecklist(
	"slide_transition",
	$arrTransitions,
	$modules->l('Transitions'),
	$defaultTransition,
	$params
);

//slot amount
$params = array(
	"description" => $modules->l(
		'The number of slots or boxes the slide is divided into. If you use boxfade, over 7 slots can be juggy'
	),
	"class" => "small",
	"datatype" => "number"
);

$slideSettings->addTextBox(
	"slot_amount",
	"7",
	$modules->l('Slot Amount'),
	$params
);

//rotation:
$params = array(
	"description" => $modules->l('Simple Transitions'),
	"class" => "small",
	"datatype" => "number"
);

$slideSettings->addTextBox(
	"transition_rotation",
	"0",
	$modules->l('Rotation'),
	$params
);

//transition speed
$params = array(
	"description" => $modules->l('The duration of the transition (Default:300, min: 100 max 2000).'),
	"class" => "small",
	"datatype" => "number"
);

$slideSettings->addTextBox(
	"transition_duration",
	"300",
	$modules->l('Transition Duration'),
	$params
);

$sliderDelay = RbGlobalObject::getVar('sliderDelay');
RbGlobalObject::reset();

if (empty($sliderDelay)) {
    $sliderDelay = 0;
}

//delay	
$params = array(
	"description" => $modules->l('A new delay value for the Slide. If no delay defined per slide, the delay defined via Options (') .
	$sliderDelay .
	$modules->l('ms) will be used'),
	"class" => "small",
	"datatype" => UniteSettingsRb::DATATYPE_NUMBEROREMTY
);

$slideSettings->addTextBox(
	"delay",
	"",
	$modules->l('Delay'),
	$params
);

$params = array(
	"description" => "",
	"class" => "small"
);

$slideSettings->addRadio(
	"save_performance",
	array(
		"on" => $modules->l('On'),
		"off" => $modules->l('Off')
	),
	$modules->l('Save Performance'),
	"off",
	$params
);

//enable link
$slideSettings->addSelectBoolean(
	"enable_link",
	$modules->l('Enable_Link'),
	false,
	$modules->l('Enable'),
	$modules->l('Disable')
);

$slideSettings->startBulkControl(
	"enable_link",
	UniteSettingsRb::CONTROL_TYPE_SHOW,
	"true"
);

//link type
$slideSettings->addRadio(
	"link_type",
	array(
		"regular" => $modules->l('Regular'),
		"slide" => $modules->l('To Slide')
	),
	$modules->l('Link Type'),
	"regular"
);

//link	
$params = array(
	"description" => $modules->l(
		'A link on the whole slide pic (use %link% or %meta:somemegatag% in template sliders to link
		to a post or some other meta)'
	)
);

$slideSettings->addTextBox(
	"link",
	"",
	$modules->l('Slide Link'),
	$params
);

//link target
$params = array(
	"description" => $modules->l('Target Slide Link')
);

$slideSettings->addSelect(
	"link_open_in",
	array(
		"same" => $modules->l('Same_Window'),
		"new" => $modules->l('New_Window')
	),
	$modules->l('Link_Open'),
	"same",
	$params
);

//num_slide_link
$arrSlideLink = array();
$arrSlideLink["nothing"] = $modules->l('Not Chosen');
$arrSlideLink["next"] = $modules->l('Next Slide');
$arrSlideLink["prev"] = $modules->l('Previous Slide');
$arrSlideLinkLayers = $arrSlideLink;
$arrSlideLinkLayers["scroll_under"] = $modules->l('Scroll Below Slider');

foreach ($arrSlideNames as $slideNameID => $arr) {
    $slideName = $arr["title"];
    $arrSlideLink[$slideNameID] = $slideName;
    $arrSlideLinkLayers[$slideNameID] = $slideName;
}

$slideSettings->addSelect(
	"slide_link",
	$arrSlideLink,
	"Link To Slide",
	"nothing"
);

$params = array(
	"description" => "The position of the link related to layers"
);

$slideSettings->addRadio(
	"link_pos",
	array(
		"front" => "Front",
		"back" => "Back"
	),
	"Link Position",
	"front",
	$params
);

$slideSettings->addHr("link_sap");
$slideSettings->endBulkControl();

$slideSettings->addControl(
	"link_type",
	"slide_link",
	UniteSettingsRb::CONTROL_TYPE_ENABLE,
	"slide"
);

$slideSettings->addControl(
	"link_type",
	"link",
	UniteSettingsRb::CONTROL_TYPE_DISABLE,
	"slide"
);

$slideSettings->addControl(
	"link_type",
	"link_open_in",
	UniteSettingsRb::CONTROL_TYPE_DISABLE,
	"slide"
);

$params = array(
	"description" => $modules->l('Slide Thumbnail Image')
);

$slideSettings->addImage(
	"slide_thumb",
	"",
	$modules->l('Thumbnail'),
	$params
);

$slideSettings->addTextBox(
	"background_type",
	"image",
	$modules->l('Background Type'),
	array("hidden" => true)
);

$slideSettings->addHr("");

//store settings
$params = array(
	"description" => $modules->l(
		'Adds a unique class to the li of the Slide like class="rb_special_class"
		(add only the classnames, seperated by space)'
	)
);

$slideSettings->addTextBox(
	"class_attr",
	"",
	$modules->l('Class'),
	$params
);

$params = array(
	"description" => $modules->l(
		'Adds a unique ID to the li of the Slide like
		id="rb_special_id" (add only the id)'
	)
);

$slideSettings->addTextBox(
	"id_attr",
	"",
	$modules->l('ID'),
	$params
);

$params = array(
	"description" => $modules->l(
		'Adds a unique Attribute to the li of the Slide like
		attr="rb_special_attr" (add only the attribute)'
	)
);

$slideSettings->addTextBox(
	"attr_attr",
	"",
	$modules->l('Attribute'),
	$params
);

$params = array(
	"description" => $modules->l('Attributes data custom')
);

$slideSettings->addTextArea(
	"data_attr",
	"",
	$modules->l('Custom Fields'),
	$params
);

self::storeSettings("slide_settings", $slideSettings);
