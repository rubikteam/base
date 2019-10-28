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

$sliderMainSettings = new UniteSettingsAdvancedRb();
$modules = new Rbthemeslider();

$sliderMainSettings->addTextBox(
	"title",
	"",
	$modules->l('Slider Title'),
	array(
		"description" => $modules->l('The title of the slider. Example: Slider1'),
		"required" => "true"
	)
);

$sliderMainSettings->addTextBox(
	"alias",
	"",
	$modules->l('Slider Alias'),
	array(
		"description" => $modules->l(
			'The alias that will be used for embedding the slider. Example: slider1'
		),
		"required" => "true"
	)
);

$sliderMainSettings->addHr();

//start set IMages Size
$gethooks = $modules->getHook();
$hookobj = new SdsRbHooksClass();
$customhooks = $hookobj->getAllHooks();

if (@Rbthemeslider::getIsset($customhooks) && !empty($customhooks)) {
    foreach ($customhooks as $values) {
        foreach ($values as $valu) {
            $gethooks[$valu] = $valu;
        }
    }
}

$sliderMainSettings->addSelect(
	"displayhook",
	$gethooks,
	$modules->l('Display Hook'),
	'id'
);

$arrSourceTypes = array(
	"posts" => $modules->l('Products'),
    "specific_posts" => $modules->l('Specific Products'),
    "gallery" => $modules->l('Gallery')
);

$sliderMainSettings->addRadio(
	"source_type",
	$arrSourceTypes,
	$modules->l('Source Type'),
	"gallery"
);

$sliderMainSettings->startBulkControl(
	"source_type",
	UniteSettingsRb::CONTROL_TYPE_SHOW,
	"posts"
);

//post types
$arrPostTypes = UniteFunctionsPSRb::getPostTypesAssoc(array("product"));
$arrParams = array("args" => "multiple size='5'");

$sliderMainSettings->addSelect(
	"post_types",
	$arrPostTypes,
	$modules->l('Types'),
	"product",
	$arrParams
);

//post categories
$arrParams = array("args" => "multiple size='7'");
$sliderMainSettings->addSelect(
	"post_category",
	array(),
	$modules->l('Product Categories'),
	"",
	$arrParams
);

//sort by
$arrSortBy = UniteFunctionsPSRb::getArrSortBy();

$sliderMainSettings->addSelect(
	"post_sortby",
	$arrSortBy,
	$modules->l('Sort Posts'),
	RbSlider::DEFAULT_POST_SORTBY
);

//start set IMages Size
$GetArrImageSize = UniteFunctionsPSRb::getArrImageSize();

$sliderMainSettings->addSelect(
	"prd_img_size",
	$GetArrImageSize,
	$modules->l('Product Image Type'),
	''
);

//End set IMages Size
//sort direction
$arrSortDir = UniteFunctionsPSRb::getArrSortDirection();

$sliderMainSettings->addRadio(
	"posts_sort_direction",
	$arrSortDir,
	$modules->l('Sort Direction'),
	RbSlider::DEFAULT_POST_SORTDIR
);

//max posts for slider
$arrParams = array("class" => "small", "unit" => "posts");

$sliderMainSettings->addTextBox(
	"max_slider_posts",
	"30",
	$modules->l('Max Posts'),
	$arrParams
);

//exerpt limit
$arrParams = array("class" => "small", "unit" => "words");

$sliderMainSettings->addTextBox(
	"excerpt_limit",
	"55",
	$modules->l('Limit Excerpt'),
	$arrParams
);

//slider template
$sliderMainSettings->addhr();
$slider1 = new RbSlider();

$arrSlidersTemplates = $slider1->getArrSlidersShort(
	null,
	RbSlider::SLIDER_TYPE_TEMPLATE
);

$sliderMainSettings->addSelect(
	"slider_template_id",
	$arrSlidersTemplates,
	$modules->l('Template Slider'),
	"",
	array()
);

$sliderMainSettings->endBulkControl();

$arrParams = array(
	"description" => $modules->l('Type post')
);

$sliderMainSettings->addTextBox(
	"posts_list",
	"",
	$modules->l('Specific Posts'),
	$arrParams
);

$sliderMainSettings->addControl(
	"source_type",
	"posts_list",
	UniteSettingsRb::CONTROL_TYPE_SHOW,
	"specific_posts"
);

$sliderMainSettings->addHr();

//set slider type / texts
$sliderMainSettings->addRadio(
	"slider_type",
	array(
		"fixed" => $modules->l('Fixed'),
    	"responsitive" => $modules->l('Custom'),
    	"fullwidth" => $modules->l('Auto Responsive'),
    	"fullscreen" => $modules->l('Full Screen')
    ),
    $modules->l('Slider Layout'),
    "fullwidth"
);

$arrParams = array(
	"class" => "medium",
	"description" => $modules->l(
		'Example: #header or .header, .footer, #somecontainer |
		The height of fullscreen slider will be decreased with the height
		of these Containers to fit perfect in the screen'
	)
);

$sliderMainSettings->addTextBox(
	"fullscreen_offset_container",
	"",
	$modules->l('Offset Containers'),
	$arrParams
);

$sliderMainSettings->addControl(
	"slider_type",
	"fullscreen_offset_container",
	UniteSettingsRb::CONTROL_TYPE_SHOW,
	"fullscreen"
);

$arrParams = array(
	"class" => "medium",
	"description" => $modules->l('Defines Offset')
);

$sliderMainSettings->addTextBox(
	"fullscreen_offset_size",
	"",
	$modules->l('Offset Size'),
	$arrParams
);

$sliderMainSettings->addControl(
	"slider_type",
	"fullscreen_offset_size",
	UniteSettingsRb::CONTROL_TYPE_SHOW,
	"fullscreen"
);

$arrParams = array("description" => "");

$sliderMainSettings->addTextBox(
	"fullscreen_min_height",
	"",
	$modules->l('Fullscreen Height'),
	$arrParams
);

$sliderMainSettings->addControl(
	"slider_type",
	"fullscreen_min_height",
	UniteSettingsRb::CONTROL_TYPE_SHOW,
	"fullscreen"
);

$sliderMainSettings->addRadio(
	"full_screen_align_force",
	array(
		"on" => $modules->l('On'),
		"off" => $modules->l('Off')
	),
	$modules->l('FullScreen Align'),
	"off"
);

$sliderMainSettings->addRadio(
	"auto_height",
	array(
		"on" => $modules->l('On'),
		"off" => $modules->l('Off')
	),
	$modules->l('Unlimited_Height'),
	"off"
);

$sliderMainSettings->addRadio(
	"force_full_width",
	array(
		"on" => $modules->l('On'),
		"off" => $modules->l('Off')
	),
	$modules->l('Force Full Width'),
	"off"
);

$arrParams = array("description" => "");

$sliderMainSettings->addTextBox(
	"min_height",
	"0",
	$modules->l('Min Height'),
	$arrParams
);

$paramsSize = array(
	"width" => 960,
	"height" => 350,
	"datatype" => UniteSettingsRb::DATATYPE_NUMBER,
	"description" => $modules->l(
		'- The <span class="prevxmpl">LAYERS GRID</span> is the container of layers within the
		<span class="prevxmpl">SLIDER</span> <br>
		- The "Grid Size" setting does not relate to the actual "Slider Size". <br>
		- "Max Height" of the slider equals the "Grid Height"<br>
		- "Slider Width" can be greater than the set "Grid Width"'
	)
);

$sliderMainSettings->addCustom(
	"slider_size",
	"slider_size",
	"",
	$modules->l('Layers Grid'),
	$paramsSize
);

$paramsResponsitive = array(
	"w1" => 940,
	"sw1" => 770,
	"w2" =>
	780,
	"sw2" => 500,
	"w3" => 510,
	"sw3" => 310,
	"datatype" => UniteSettingsRb::DATATYPE_NUMBER
);

$sliderMainSettings->addCustom(
	"responsitive_settings",
	"responsitive",
	"",
	$modules->l('Responsive Sizes'),
	$paramsResponsitive
);

$sliderMainSettings->addHr();

RbSliderAdmin::storeSettings("slider_main", $sliderMainSettings);

//set "slider_params" settings. 
$sliderParamsSettings = new UniteSettingsAdvancedRb();

$sliderParamsSettings->loadXMLFile(
	RbSliderAdmin::$path_settings . "/slider_settings.xml"
);

//update transition type setting.
$settingFirstType = $sliderParamsSettings->getSettingByName(
	"first_transition_type"
);

$operations = new RbOperations();
$arrTransitions = $operations->getArrTransition();

if (count($arrTransitions) == 0) {
    $arrTransitions = $operations->getArrTransition(true);
}

$settingFirstType["items"] = $arrTransitions;

$sliderParamsSettings->updateArrSettingByName(
	"first_transition_type",
	$settingFirstType
);

//store params
RbSliderAdmin::storeSettings("slider_params", $sliderParamsSettings);
