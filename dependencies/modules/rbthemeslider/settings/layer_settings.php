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
$operations = new RbOperations();
$contentCSS = $operations->getCaptionsContent();
$arrAnimations = $operations->getArrAnimations();
$arrEndAnimations = $operations->getArrEndAnimations();

$htmlButtonDown = '<div id="layer_captions_down" style="width:30px; text-align:center;padding:0px;" class="rbgray button-primary"><i class="eg-icon-down-dir"></i></div>';

$buttonEditStyles = UniteFunctionsRb::getHtmlLink(
	"javascript:void(0)",
	"<i class=\"rbicon-magic\"></i>Edit Style",
	"button_edit_css", "button-primary rbblue"
);

$buttonEditStylesGlobal = UniteFunctionsRb::getHtmlLink(
	"javascript:void(0)",
	"<i class=\"rbicon-palette\"></i>Edit Global Style",
	"button_edit_css_global",
	"button-primary rbblue"
);

$arrSplit = $operations->getArrSplit();
$arrEasing = $operations->getArrEasing();
$arrEndEasing = $operations->getArrEndEasing();
$captionsAddonHtml = $htmlButtonDown.$buttonEditStyles.$buttonEditStylesGlobal;

    //set Layer settings
$layerSettings = new UniteSettingsAdvancedRb();

$layerSettings->addSection(
	$modules->l('Layer Params'),
	$modules->l('layer_params')
);

$layerSettings->addSap(
	$modules->l('Layer Params'),
	$modules->l('layer_params')
);

$layerSettings->addTextBox(
	"layer_caption",
	$modules->l('Caption Green'),
	$modules->l('Style'),
	array(
		UniteSettingsRb::PARAM_ADDTEXT => $captionsAddonHtml,
		"class"=>"textbox-caption"
	)
);

$addHtmlTextarea = '';

$addHtmlTextarea .= UniteFunctionsRb::getHtmlLink(
	"javascript:void(0)",
	"Insert Button",
	"linkInsertButton",
	"disabled rbblue button-primary"
);

$layerSettings->addTextArea(
	"layer_text",
	"",
	$modules->l('Text Html'),
	array(
		"class" => "area-layer-params",
		UniteSettingsRb::PARAM_ADDTEXT_BEFORE_ELEMENT => $addHtmlTextarea
	)
);

$layerSettings->addTextBox(
	"layer_image_link", "",
	$modules->l('Image Link'),
	array(
		"class" => "text-sidebar-link",
		"hidden"=>true
	)
);

$layerSettings->addSelect(
	"layer_link_open_in",
	array(
		"same" => $modules->l('Same Window'),
		"new" => $modules->l('New_Window')
	),
	$modules->l('Link Open In'),
	"same",
	array("hidden" => true)
);

$layerSettings->addSelect(
	"layer_animation",
	$arrAnimations,
	$modules->l('Start Animation'),
	"fade"
);

$layerSettings->addSelect(
	"layer_easing",
	$arrEasing,
	$modules->l('Start Easing'),
	"Power3.easeInOut"
);

$params = array("unit" => $modules->l('ms'));

$paramssplit = array(
	"unit" => $modules->l('ms keep low')
);

$layerSettings->addTextBox("layer_speed", "", "Start Duration", $params);
$layerSettings->addTextBox("layer_splitdelay", "10", "Split Delay", $paramssplit);

$layerSettings->addSelect(
	"layer_split",
	$arrSplit,
	$modules->l('Split Text Per'),
	"none"
);

$layerSettings->addCheckbox(
	"layer_hidden",
	false,
	$modules->l('Hide Under Width')
);

$params = array("hidden"=>true);

$layerSettings->addTextBox(
	"layer_link_id",
	"",
	$modules->l('Link ID'),
	$params
);

$layerSettings->addTextBox(
	"layer_link_class",
	"",
	$modules->l('Link Classes'),
	$params
);

$layerSettings->addTextBox(
	"layer_link_title",
	"",
	$modules->l('Link Title'),
	$params
);

$layerSettings->addTextBox(
	"layer_link_rel",
	"",
	$modules->l('Link Rel'),
	$params
);

//scale for img
$textScaleX = $modules->l('Width');
$textScaleProportionalX = $modules->l('Width Height');

$params = array(
	"attrib_text" => "data-textproportional='".$textScaleProportionalX."' data-textnormal='".$textScaleX."'",
	"hidden" => false
);

$layerSettings->addTextBox(
	"layer_scaleX",
	"",
	$modules->l('Width'),
	$params
);

$layerSettings->addTextBox(
	"layer_scaleY",
	"",
	$modules->l('Height'),
	array("hidden" => false)
);

$layerSettings->addCheckbox(
	"layer_proportional_scale",
	false,
	$modules->l('Scale Proportional'),
	array("hidden" => false)
);

$arrParallaxLevel = array(
	'-' => $modules->l('No Movement'),
	'1' => 1,
	'2' => 2,
	'3' => 3,
	'4' => 4,
	'5' => 5,
	'6' => 6,
	'7' => 7,
	'8' => 8,
	'9' => 9,
	'10' => 10,
);

$layerSettings->addSelect(
	"parallax_level",
	$arrParallaxLevel,
	$modules->l('Level'),
	"nowrap",
	array("hidden" => false)
);

//put left top
$textOffsetX = $modules->l('OffsetX');
$textX = $modules->l('X');

$params = array(
	"attrib_text" => "data-textoffset='".$textOffsetX."'
	data-textnormal='".$textX."'"
);

$layerSettings->addTextBox(
	"layer_left",
	"",
	$modules->l('X'),
	$params
);

$textOffsetY = $modules->l('OffsetY');
$textY = $modules->l('Y');

$params = array(
	"attrib_text"=>"data-textoffset='".$textOffsetY."'
	data-textnormal='".$textY."'"
);

$layerSettings->addTextBox(
	"layer_top",
	"",
	$modules->l('Y'),
	$params
);

$layerSettings->addTextBox(
	"layer_align_hor",
	"left",
	$modules->l('Hor Align'),
	array("hidden" => true)
);

$layerSettings->addTextBox(
	"layer_align_vert",
	"top",
	$modules->l('Vert Align'),
	array("hidden" => true)
);

$para = array(
	"unit" => $modules->l('nbsp_auto'),
	'hidden'=>true
);

$layerSettings->addTextBox(
	"layer_max_width",
	"auto",
	$modules->l('Max Width'),
	$para
);

$layerSettings->addTextBox(
	"layer_max_height",
	"auto",
	$modules->l('Max Height'),
	$para
);

$layerSettings->addTextBox(
	"layer_2d_rotation",
	"0",
	$modules->l('2D Rotation'),
	array(
		"hidden"=>false,
		'unit'=>'&nbsp;(-360 - 360)'
	)
);

$layerSettings->addTextBox(
	"layer_2d_origin_x",
	"50",
	$modules->l('Rotation Origin X'),
	array(
		"hidden" => false,
		'unit' => '%&nbsp;(-100 - 200)'
	)
);

$layerSettings->addTextBox(
	"layer_2d_origin_y",
	"50",
	$modules->l('Rotation Origin Y'),
	array(
		"hidden"=>false,
		'unit'=>'%&nbsp;(-100 - 200)'
	)
);

$arrWhiteSpace = array(
	"normal" => $modules->l('Normal'),
    "pre" => $modules->l('Pre'),
    "nowrap" => $modules->l('NO Wrap'),
    "pre-wrap" => $modules->l('Pre Wrap'),
    "pre-line" => $modules->l('Pre Line')
);

$layerSettings->addSelect(
	"layer_whitespace",
	$arrWhiteSpace,
	$modules->l('White Space'),
	"nowrap",
	array("hidden" => true)
);

$arrSlideLink = array();
$arrSlideLink["nothing"] = $modules->l("-- Not Chosen --");
$arrSlideLink["next"] = $modules->l("-- Next Slide --");
$arrSlideLink["prev"] = $modules->l("-- Previous Slide --");
$arrSlideLinkLayers = $arrSlideLink;
$arrSlideLinkLayers["scroll_under"] = $modules->l(
	"-- Scroll Below Slider --"
);

$layerSettings->addSelect(
	"layer_slide_link",
	$arrSlideLinkLayers,
	$modules->l('Link To Slide'),
	"nothing"
);

$params = array(
	"unit" => $modules->l('px'),
	"hidden" => true
);

$layerSettings->addTextBox(
	"layer_scrolloffset",
	"0",
	$modules->l('Scroll Under Slider'),
	$params
);

$layerSettings->addButton(
	"button_change_image_source",
	$modules->l('Change Image Source'),
	array(
		"hidden" => true,
		"class" => "button-primary rbblue"
	)
);

$layerSettings->addTextBox(
	"layer_alt",
	"",
	"Alt Text",
	array(
		"hidden"=>true,
		"class"=>"area-alt-params"
	)
);

$layerSettings->addButton(
	"button_edit_video",
	$modules->l('Edit_Video'),
	array(
		"hidden" => true,
		"class"=>"button-primary rbblue"
	)
);

$params = array("unit" => $modules->l('ms'));
$paramssplit = array("unit" => $modules->l('ms_keep_low'));
$params_1 = array("unit" => $modules->l('ms'), 'hidden'=>true);

$layerSettings->addTextBox(
	"layer_endtime",
	"",
	$modules->l('End Time'),
	$params_1
);

$layerSettings->addTextBox(
	"layer_endspeed",
	"",
	$modules->l('End_Duration'),
	$params
);

$layerSettings->addTextBox(
	"layer_endsplitdelay",
	"10",
	"End Split Delay",
	$paramssplit
);

$layerSettings->addSelect(
	"layer_endsplit",
	$arrSplit,
	$modules->l('Split Text Per'),
	"none"
);

$layerSettings->addSelect(
	"layer_endanimation",
	$arrEndAnimations,
	$modules->l('End Animation'),
	"auto"
);

$layerSettings->addSelect(
	"layer_endeasing",
	$arrEndEasing,
	$modules->l('End Easing'),
	"nothing"
);

$params = array("unit" => $modules->l('ms'));

$arrCorners = array(
	"nothing" => $modules->l('No_Corner'),
    "curved" => $modules->l('Sharp'),
    "reverced" => $modules->l('Sharp_Reversed')
);

$params = array();

$layerSettings->addSelect(
	"layer_cornerleft",
	$arrCorners,
	$modules->l('Left Corner'),
	"nothing",
	$params
);

$layerSettings->addSelect(
	"layer_cornerright",
	$arrCorners,
	$modules->l('Right Corner'),
	"nothing",
	$params
);

$layerSettings->addCheckbox(
	"layer_resizeme",
	true,
	$modules->l('Responsive Levels'),
	$params
);

$params = array();

$layerSettings->addTextBox(
	"layer_id",
	"",
	$modules->l('ID'),
	$params
);

$layerSettings->addTextBox(
	"layer_classes",
	"",
	$modules->l('Classes'),
	$params
);

$layerSettings->addTextBox(
	"layer_title",
	"",
	$modules->l('Title'),
	$params
);

$layerSettings->addTextBox(
	"layer_rel",
	"",
	$modules->l('Rel'),
	$params
);

//Loop Animation
$arrAnims = array(
	"none" => $modules->l('Disabled'),
	"rs-pendulum" => $modules->l('Pendulum'),
	"rs-slideloop" => $modules->l('Slideloop'),
	"rs-pulse" => $modules->l('Pulse'),
	"rs-wave"=>$modules->l('Wave')
);

$params = array();

$layerSettings->addSelect(
	"layer_loop_animation",
	$arrAnims,
	$modules->l('Animation'),
	"none",
	$params
);

$layerSettings->addTextBox(
	"layer_loop_speed",
	"2",
	$modules->l('Speed'),
	array(
		"unit" => $modules->l('nbsp')
	)
);

$layerSettings->addTextBox(
	"layer_loop_startdeg",
	"-20",
	$modules->l('Start Degree')
);

$layerSettings->addTextBox(
	"layer_loop_enddeg",
	"20",
	$modules->l('End Degree'),
	array(
		"unit" => $modules->l('nbsp')
	)
);

$layerSettings->addTextBox(
	"layer_loop_xorigin",
	"50",
	$modules->l('x Origin'),
	array(
		"unit" => '%'
	)
);

$layerSettings->addTextBox(
	"layer_loop_yorigin",
	"50",
	$modules->l('y Origin'),
	array(
		"unit" => $modules->l('%_250')
	)
);

$layerSettings->addTextBox(
	"layer_loop_xstart",
	"0",
	$modules->l('x Start Pos'),
	array(
		"unit" => 'px'
	)
);

$layerSettings->addTextBox(
	"layer_loop_xend",
	"0",
	$modules->l('x End Pos'),
	array(
		"unit"=> $modules->l('px (-2000px - 2000px)')
	)
);

$layerSettings->addTextBox(
	"layer_loop_ystart",
	"0",
	$modules->l('y Start Pos'),
	array(
		"unit" => 'px'
	)
);

$layerSettings->addTextBox(
	"layer_loop_yend",
	"0",
	$modules->l('y End Pos'),
	array(
		"unit" => $modules->l('px 2000px')
	)
);

$layerSettings->addTextBox(
	"layer_loop_zoomstart",
	"1",
	$modules->l('Start Zoom')
);

$layerSettings->addTextBox(
	"layer_loop_zoomend",
	"1",
	$modules->l('End Zoom'),
	array(
		"unit" => $modules->l('&nbsp;(0.00 - 20.00)')
	)
);

$layerSettings->addTextBox(
	"layer_loop_angle",
	"0",
	$modules->l('Angle'),
	array(
		"unit" => $modules->l('° (0° - 360°)')
	)
);

$layerSettings->addTextBox(
	"layer_loop_radius",
	"10",
	$modules->l('Radius'),
	array(
		"unit" => $modules->l('px (0px - 2000px)')
	)
);

$layerSettings->addSelect(
	"layer_loop_easing",
	$arrEasing,
	$modules->l('Easing'),
	"Power3.easeInOut"
);

self::storeSettings("layer_settings", $layerSettings);

//store settings of content css for editing on the client.
self::storeSettings("css_captions_content", $contentCSS);
