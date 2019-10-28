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

if (!defined('ABSPATH')) {
    exit();
}

$modules = new Rbthemeslider();
$slideID = RbSliderFunctions::getGetVar("id");

if ($slideID == 'new') {
    $sID = (int)(RbSliderFunctions::getGetVar("slider"));

    if ($sID > 0) {
        $rbs = new RbSlider();
        $rbs->initByID($sID);
        $arrS = $rbs->getSlides(false);

        if (empty($arrS)) {
            $slideID = $rbs->createSlideFromData(array('sliderid'=>$sID), true);
        } else {
            $slideID = key($arrS);
        }
    }
}

$patternViewSlide = self::getViewUrl("slide", "id=[slideid]");

//init slide object
$slide = new RbSlide();
$slide->initByID($slideID);
$slideParams = $slide->getParams();
$operations = new RbSliderOperations();

//init slider object
$sliderID = $slide->getSliderID();
$slider = new RbSlider();
$slider->initByID($sliderID);
$sliderParams = $slider->getParams();
$arrSlideNames = $slider->getArrSlideNames();

$arrSlides = $slider->getSlides(false);
$arrSlidesPSML = $slider->getSlidesPSML(false, $slide);

$arrSliders = $slider->getArrSlidersShort($sliderID);
$selectSliders = RbSliderFunctions::getHTMLSelect($arrSliders, "", "id='selectSliders'", true);

RbGlobalObject::setVar('slideParams', $slideParams);
RbGlobalObject::setVar('slider', $slider);
RbGlobalObject::setVar('slide', $slide);
RbGlobalObject::setVar('selectSliders', $selectSliders);
RbGlobalObject::setVar('slideID', $slideID);
RbGlobalObject::setVar('operations', $operations);
RbGlobalObject::setVar('arrSlideNames', $arrSlideNames);
RbGlobalObject::setVar('arrSlides', $arrSlides);

//check if slider is template
$sliderTemplate = $slider->getParam("template", "false");

//set slide delay
$sliderDelay = $slider->getParam("delay", "9000");
$slideDelay = $slide->getParam("delay", "");
if (empty($slideDelay)) {
    $slideDelay = $sliderDelay;
}

//add tools.min.js
psEnqueueScript('tp-tools', _MODULE_DIR_ .'public/assets/js/jquery.themepunch.tools.min.js', array(), RbSliderGlobals::SLIDER_RBISION);

$arrLayers = $slide->getLayers();

//set Layer settings
$cssContent = $operations->getCaptionsContent();

$arrCaptionClasses = $operations->getArrCaptionClasses($cssContent);
//$arrCaptionClassesSorted = $operations->getArrCaptionClasses($cssContent);
$arrCaptionClassesSorted = RbSliderCssParser::getCaptionsSorted();

$arrFontFamily = $operations->getArrFontFamilys($slider);
$arrCSS = $operations->getCaptionsContentArray();
$arrButtonClasses = $operations->getButtonClasses();


$arrAnim = $operations->getFullCustomAnimations();
$arrAnimDefaultIn = $operations->getArrAnimations(false);
$arrAnimDefaultOut = $operations->getArrEndAnimations(false);

$arrAnimDefault = array_merge($arrAnimDefaultIn, $arrAnimDefaultOut);

//set various parameters needed for the page
$width = $sliderParams["width"];
$height = $sliderParams["height"];
$imageUrl = $slide->getImageUrl();
$imageID = $slide->getImageID();
RbGlobalObject::setVar('imageID', $imageID);

$slider_type = $slider->getParam('source_type', 'gallery');

RbGlobalObject::setVar('slider_type', $slider_type);

/**
 * Get Slider params which will be used as default on Slides
 * @since: 5.0
 **/
$def_background_fit = $slider->getParam('def-background_fit', 'cover');
$def_image_source_type = $slider->getParam('def-image_source_type', 'full');
$def_bg_fit_x = $slider->getParam('def-bg_fit_x', '100');
$def_bg_fit_y = $slider->getParam('def-bg_fit_y', '100');
$def_bg_position = $slider->getParam('def-bg_position', 'center center');
$def_bg_position_x = $slider->getParam('def-bg_position_x', '0');
$def_bg_position_y = $slider->getParam('def-bg_position_y', '0');
$def_bg_repeat = $slider->getParam('def-bg_repeat', 'no-repeat');
$def_kenburn_effect = $slider->getParam('def-kenburn_effect', 'off');
$def_kb_start_fit = $slider->getParam('def-kb_start_fit', '100');
$def_kb_easing = $slider->getParam('def-kb_easing', 'Linear.easeNone');
$def_kb_end_fit = $slider->getParam('def-kb_end_fit', '100');
$def_kb_duration = $slider->getParam('def-kb_duration', '10000');
$def_transition = $slider->getParam('def-slide_transition', 'fade');
RbGlobalObject::setVar('def_transition', $def_transition);
$def_transition_duration = $slider->getParam('def-transition_duration', 'default');
RbGlobalObject::setVar('def_transition_duration', $def_transition_duration);
$def_use_parallax = $slider->getParam('use_parallax', 'on');

/* NEW KEN BURN INPUTS */
$def_kb_start_offset_x = $slider->getParam('def-kb_start_offset_x', '0');
$def_kb_start_offset_y = $slider->getParam('def-kb_start_offset_y', '0');
$def_kb_end_offset_x = $slider->getParam('def-kb_end_offset_x', '0');
$def_kb_end_offset_y = $slider->getParam('def-kb_end_offset_y', '0');
$def_kb_start_rotate = $slider->getParam('def-kb_start_rotate', '0');
$def_kb_end_rotate = $slider->getParam('def-kb_end_rotate', '0');
/* END OF NEW KEN BURN INPUTS */

$imageFilename = $slide->getImageFilename();

$style = "height:".$height."px;"; //

$divLayersWidth = "width:".$width."px;";
$divbgminwidth = "min-width:".$width."px;";
$maxbgwidth = "max-width:".$width."px;";

RbGlobalObject::setVar('divbgminwidth', $divbgminwidth);
RbGlobalObject::setVar('maxbgwidth', $maxbgwidth);
RbGlobalObject::setVar('divLayersWidth', $divLayersWidth);

//set iframe parameters
$iframeWidth = $width+60;
$iframeHeight = $height+50;

$iframeStyle = "width:".$iframeWidth."px;height:".$iframeHeight."px;";

$closeUrl = self::getViewUrl(RbSliderAdmin::VIEW_SLIDES, "id=".$sliderID);

$jsonLayers = RbSliderFunctions::jsonEncodeForClientSide($arrLayers);
$jsonFontFamilys = RbSliderFunctions::jsonEncodeForClientSide($arrFontFamily);
$jsonCaptions = RbSliderFunctions::jsonEncodeForClientSide($arrCaptionClassesSorted);

$arrCssStyles = RbSliderFunctions::jsonEncodeForClientSide($arrCSS);

$arrCustomAnim = RbSliderFunctions::jsonEncodeForClientSide($arrAnim);
$arrCustomAnimDefault = RbSliderFunctions::jsonEncodeForClientSide($arrAnimDefault);

//bg type params
$bgType = RbSliderFunctions::getVal($slideParams, 'background_type', 'image');
RbGlobalObject::setVar('bgType', $bgType);

$slideBGColor = RbSliderFunctions::getVal($slideParams, 'slide_bg_color', '#E7E7E7');
RbGlobalObject::setVar('slideBGColor', $slideBGColor);
$divLayersClass = "slide_layers";

$meta_handle = RbSliderFunctions::getVal($slideParams, 'meta_handle', '');

$bgFit = RbSliderFunctions::getVal($slideParams, 'bg_fit', $def_background_fit);
RbGlobalObject::setVar('bgFit', $bgFit);
$bgFitX = (int)(RbSliderFunctions::getVal($slideParams, 'bg_fit_x', $def_bg_fit_x));
RbGlobalObject::setVar('bgFitX', $bgFitX);
$bgFitY = (int)(RbSliderFunctions::getVal($slideParams, 'bg_fit_y', $def_bg_fit_y));
RbGlobalObject::setVar('bgFitY', $bgFitY);

$bgPosition = RbSliderFunctions::getVal($slideParams, 'bg_position', $def_bg_position);
RbGlobalObject::setVar('bgPosition', $bgPosition);
$bgPositionX = (int)(RbSliderFunctions::getVal($slideParams, 'bg_position_x', $def_bg_position_x));
RbGlobalObject::setVar('bgPositionX', $bgPositionX);
$bgPositionY = (int)(RbSliderFunctions::getVal($slideParams, 'bg_position_y', $def_bg_position_y));
RbGlobalObject::setVar('bgPositionY', $bgPositionY);

$slide_parallax_level = RbSliderFunctions::getVal($slideParams, 'slide_parallax_level', '-');
RbGlobalObject::setVar('slide_parallax_level', $slide_parallax_level);
$kenburn_effect = RbSliderFunctions::getVal($slideParams, 'kenburn_effect', $def_kenburn_effect);
RbGlobalObject::setVar('kenburn_effect', $kenburn_effect);
$kb_duration = RbSliderFunctions::getVal($slideParams, 'kb_duration', $def_kb_duration);
RbGlobalObject::setVar('kb_duration', $kb_duration);
$kb_easing = RbSliderFunctions::getVal($slideParams, 'kb_easing', $def_kb_easing);
RbGlobalObject::setVar('kb_easing', $kb_easing);
$kb_start_fit = RbSliderFunctions::getVal($slideParams, 'kb_start_fit', $def_kb_start_fit);
RbGlobalObject::setVar('kb_start_fit', $kb_start_fit);
$kb_end_fit = RbSliderFunctions::getVal($slideParams, 'kb_end_fit', $def_kb_end_fit);
RbGlobalObject::setVar('kb_end_fit', $kb_end_fit);

$ext_width = RbSliderFunctions::getVal($slideParams, 'ext_width', '1920');
RbGlobalObject::setVar('ext_width', $ext_width);
$ext_height = RbSliderFunctions::getVal($slideParams, 'ext_height', '1080');
RbGlobalObject::setVar('ext_height', $ext_height);

$use_parallax = RbSliderFunctions::getVal($slideParams, 'use_parallax', $def_use_parallax);

RbGlobalObject::setVar('use_parallax', $use_parallax);
$parallax_level = array();
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_1", "5");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_2", "10");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_3", "15");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_4", "20");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_5", "25");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_6", "30");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_7", "35");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_8", "40");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_9", "45");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_10", "45");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_11", "46");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_12", "47");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_13", "48");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_14", "49");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_15", "50");
$parallax_level[] =  RbSliderFunctions::getVal($sliderParams, "parallax_level_16", "55");
RbGlobalObject::setVar('parallax_level', $parallax_level);
$parallaxisddd = RbSliderFunctions::getVal($sliderParams, "ddd_parallax", "off");
RbGlobalObject::setVar('parallaxisddd', $parallaxisddd);
$parallaxbgfreeze = RbSliderFunctions::getVal($sliderParams, "ddd_parallax_bgfreeze", "off");
RbGlobalObject::setVar('parallaxbgfreeze', $parallaxbgfreeze);

$slideBGYoutube = RbSliderFunctions::getVal($slideParams, 'slide_bg_youtube', '');
$slideBGVimeo = RbSliderFunctions::getVal($slideParams, 'slide_bg_vimeo', '');
$slideBGhtmlmpeg = RbSliderFunctions::getVal($slideParams, 'slide_bg_html_mpeg', '');
$slideBGhtmlwebm = RbSliderFunctions::getVal($slideParams, 'slide_bg_html_webm', '');
$slideBGhtmlogv = RbSliderFunctions::getVal($slideParams, 'slide_bg_html_ogv', '');

RbGlobalObject::setVar('slideBGYoutube', $slideBGYoutube);
RbGlobalObject::setVar('slideBGVimeo', $slideBGVimeo);
RbGlobalObject::setVar('slideBGhtmlmpeg', $slideBGhtmlmpeg);
RbGlobalObject::setVar('slideBGhtmlwebm', $slideBGhtmlwebm);
RbGlobalObject::setVar('slideBGhtmlogv', $slideBGhtmlogv);


$stream_do_cover = RbSliderFunctions::getVal($slideParams, 'stream_do_cover', 'on');
RbGlobalObject::setVar('stream_do_cover', $stream_do_cover);

$stream_do_cover_both = RbSliderFunctions::getVal($slideParams, 'stream_do_cover_both', 'on');
RbGlobalObject::setVar('stream_do_cover_both', $stream_do_cover_both);


$video_force_cover = RbSliderFunctions::getVal($slideParams, 'video_force_cover', 'on');
RbGlobalObject::setVar('video_force_cover', $video_force_cover);
$video_dotted_overlay = RbSliderFunctions::getVal($slideParams, 'video_dotted_overlay', 'none');
RbGlobalObject::setVar('video_dotted_overlay', $video_dotted_overlay);
$video_ratio = RbSliderFunctions::getVal($slideParams, 'video_ratio', 'none');
RbGlobalObject::setVar('video_ratio', $video_ratio);
$video_loop = RbSliderFunctions::getVal($slideParams, 'video_loop', 'none');
RbGlobalObject::setVar('video_loop', $video_loop);
$video_nextslide = RbSliderFunctions::getVal($slideParams, 'video_nextslide', 'off');
RbGlobalObject::setVar('video_nextslide', $video_nextslide);
$video_allowfullscreen = RbSliderFunctions::getVal($slideParams, 'video_allowfullscreen', 'on');
RbGlobalObject::setVar('video_allowfullscreen', $video_allowfullscreen);
$video_force_rewind = RbSliderFunctions::getVal($slideParams, 'video_force_rewind', 'on');
RbGlobalObject::setVar('video_force_rewind', $video_force_rewind);
$video_speed = RbSliderFunctions::getVal($slideParams, 'video_speed', '1');
RbGlobalObject::setVar('video_speed', $video_speed);
$video_mute = RbSliderFunctions::getVal($slideParams, 'video_mute', 'on');
RbGlobalObject::setVar('video_mute', $video_mute);
$video_volume = RbSliderFunctions::getVal($slideParams, 'video_volume', '100');
RbGlobalObject::setVar('video_volume', $video_volume);
$video_start_at = RbSliderFunctions::getVal($slideParams, 'video_start_at', '');
RbGlobalObject::setVar('video_start_at', $video_start_at);
$video_end_at = RbSliderFunctions::getVal($slideParams, 'video_end_at', '');
RbGlobalObject::setVar('video_end_at', $video_end_at);
$video_arguments = RbSliderFunctions::getVal($slideParams, 'video_arguments', RbSliderGlobals::DEFAULT_YOUTUBE_ARGUMENTS);
RbGlobalObject::setVar('video_arguments', $video_arguments);
$video_arguments_vim = RbSliderFunctions::getVal($slideParams, 'video_arguments_vimeo', RbSliderGlobals::DEFAULT_VIMEO_ARGUMENTS);
RbGlobalObject::setVar('video_arguments_vim', $video_arguments_vim);


/* NEW KEN BURN INPUTS */
$kbStartOffsetX = (int)(RbSliderFunctions::getVal($slideParams, 'kb_start_offset_x', $def_kb_start_offset_x));
RbGlobalObject::setVar('kbStartOffsetX', $kbStartOffsetX);
$kbStartOffsetY = (int)(RbSliderFunctions::getVal($slideParams, 'kb_start_offset_y', $def_kb_start_offset_y));
RbGlobalObject::setVar('kbStartOffsetY', $kbStartOffsetY);
$kbEndOffsetX = (int)(RbSliderFunctions::getVal($slideParams, 'kb_end_offset_x', $def_kb_end_offset_x));
RbGlobalObject::setVar('kbEndOffsetX', $kbEndOffsetX);
$kbEndOffsetY = (int)(RbSliderFunctions::getVal($slideParams, 'kb_end_offset_y', $def_kb_end_offset_y));
RbGlobalObject::setVar('kbEndOffsetY', $kbEndOffsetY);
$kbStartRotate = (int)(RbSliderFunctions::getVal($slideParams, 'kb_start_rotate', $def_kb_start_rotate));
RbGlobalObject::setVar('kbStartRotate', $kbStartRotate);
$kbEndRotate = (int)(RbSliderFunctions::getVal($slideParams, 'kb$modules->lnd_rotate', $def_kb_start_rotate));
RbGlobalObject::setVar('kbEndRotate', $kbEndRotate);
/* END OF NEW KEN BURN INPUTS*/

$bgRepeat = RbSliderFunctions::getVal($slideParams, 'bg_repeat', $def_bg_repeat);
RbGlobalObject::setVar('bgRepeat', $bgRepeat);

$slideBGExternal = RbSliderFunctions::getVal($slideParams, "slide_bg_external", "");
RbGlobalObject::setVar('slideBGExternal', $slideBGExternal);

$img_sizes = RbSliderBase::getAllImageSizes($slider_type);
RbGlobalObject::setVar('img_sizes', $img_sizes);

$bg_image_size = RbSliderFunctions::getVal($slideParams, 'image_source_type', $def_image_source_type);
RbGlobalObject::setVar('bg_image_size', $bg_image_size);

$style_wrapper = '';
$class_wrapper = '';


switch ($bgType) {
    case "trans":
        $divLayersClass = "slide_layers";
        $class_wrapper = "trans_bg";
    break;
    case "solid":
        $style_wrapper .= "background-color:".$slideBGColor.";";
    break;
    case "image":
        switch ($slider_type) {
            case 'posts':
                $imageUrl = _MODULE_DIR_.'/rbthemeslider/views/img/images/sources/post.png';
            break;
            case 'woocommerce':
                $imageUrl = _MODULE_DIR_.'/rbthemeslider/views/img/images/sources/wc.png';
            break;
            case 'facebook':
                $imageUrl = _MODULE_DIR_.'/rbthemeslider/views/img/images/sources/fb.png';
            break;
            case 'twitter':
                $imageUrl = _MODULE_DIR_.'/rbthemeslider/views/img/images/sources/tw.png';
            break;
            case 'instagram':
                $imageUrl = _MODULE_DIR_.'/rbthemeslider/views/img/images/sources/ig.png';
            break;
            case 'flickr':
                $imageUrl = _MODULE_DIR_.'/rbthemeslider/views/img/images/sources/fr.png';
            break;
            case 'youtube':
                $imageUrl = _MODULE_DIR_.'/rbthemeslider/views/img/images/sources/yt.png';
            break;
            case 'vimeo':
                $imageUrl = _MODULE_DIR_.'/rbthemeslider/views/img/images/sources/vm.png';
            break;
        }
        $style_wrapper .= "background-image:url('".$imageUrl."');";
        if ($bgFit == 'percentage') {
            $style_wrapper .= "background-size: ".$bgFitX.'% '.$bgFitY.'%;';
        } else {
            $style_wrapper .= "background-size: ".$bgFit.";";
        }
        if ($bgPosition == 'percentage') {
            $style_wrapper .= "background-position: ".$bgPositionX.'% '.$bgPositionY.'%;';
        } else {
            $style_wrapper .= "background-position: ".$bgPosition.";";
        }
        $style_wrapper .= "background-repeat: ".$bgRepeat.";";
    break;
    case "external":
        $style_wrapper .= "background-image:url('".$slideBGExternal."');";
        if ($bgFit == 'percentage') {
            $style_wrapper .= "background-size: ".$bgFitX.'% '.$bgFitY.'%;';
        } else {
            $style_wrapper .= "background-size: ".$bgFit.";";
        }
        if ($bgPosition == 'percentage') {
            $style_wrapper .= "background-position: ".$bgPositionX.'% '.$bgPositionY.'%;';
        } else {
            $style_wrapper .= "background-position: ".$bgPosition.";";
        }
        $style_wrapper .= "background-repeat: ".$bgRepeat.";";
    break;
}

RbGlobalObject::setVar('imageUrl', $imageUrl);
RbGlobalObject::setVar('class_wrapper', $class_wrapper);
RbGlobalObject::setVar('style_wrapper', $style_wrapper);
RbGlobalObject::setVar('divLayersClass', $divLayersClass);

$slideTitle = $slide->getParam("title", "Slide");
$slideOrder = $slide->getOrder();

?>

<script type="text/javascript" src="<?php echo _MODULE_DIR_.'rbthemeslider/views/js/js/webfontsload.min.js'?>"></script>
<script type="text/javascript">

	var sgfamilies = [];
	<?php
    $operations = new RbSliderOperations();
    RbGlobalObject::setVar('operations', $operations);
    $googleFont = $slider->getParam("google_font", array());

    if (!empty($googleFont)) {
        if (is_array($googleFont)) {
            $fontsstr = '';
            $count = 0;

            foreach ($googleFont as $key => $font) {
                preg_match('/family=([^\\\']+)/', $font, $match);
                if (@Rbthemeslider::getIsset($match[1]) && !empty($match[1])) {
                    $fontsstr .= (Tools::substr($match[1], Tools::strlen($match[1]) - 1) == '\\') ? Tools::substr($match[1], 0, -1) : $match[1];
                    $font = str_replace('&subset=', ':', $fontsstr);
                }
                ?> sgfamilies.push('<?php  echo($font);
                ?>');<?php
                            $count++;
            }
        } else {
            ?>sgfamilies.push('<?php echo str_replace('&subset=', ':', $googleFont);
            ?>');<?php

        }
    }

    //add here all new google fonts of the layers, with full variants and subsets
    $gfsubsets = $slider->getParam("subsets", array());
    $gf = $slider->getUsedFonts(true);
        
        $tempgfonts = '';
    $counts = 0;
        
        foreach ($gf as $gfk => $gfv) {
            $tcf = $gfk.':';
                
            if (!empty($gfv['variants'])) {
                $mgfirst = true;
                foreach ($gfv['variants'] as $mgvk => $mgvv) {
                    if (!$mgfirst) {
                        $tcf .= ',';
                    }
                    $tcf .= $mgvk;
                    $mgfirst = false;
                }
            }
        
            if (!empty($gfv['subsets'])) {
                $mgfirst = true;
                foreach ($gfv['subsets'] as $ssk => $ssv) {
                    //				if($mgfirst) $tcf .= '&subset=';
                if ($mgfirst) {
                    $tcf .= ':';
                }
                    if (!$mgfirst) {
                        $tcf .= ',';
                    }
                    $tcf .= $ssv;

                    $mgfirst = false;
                }
            }

            ?>
                    sgfamilies.push('<?php  echo $tcf;
            ?>');<?php

        }
    ?>
	var callAllIdle_LocalTimeOut;
	function fontLoaderWaitForTextLayers() {		
		if (jQuery('.slide_layer_type_text').length>0) {			
			tpLayerTimelinesRb.allLayerToIdle({type:"text"});
			clearTimeout(callAllIdle_LocalTimeOut);
			callAllIdle_LocalTimeOut = setTimeout(function() {				
				tpLayerTimelinesRb.allLayerToIdle({type:"text"});
			},1250);
		}
		else
			setTimeout(fontLoaderWaitForTextLayers,250);
	}
        
	if (sgfamilies.length)
		tpWebFont.load({
			timeout:10000,
			google:{
				families:sgfamilies
			},
			loading:function() {				
			},
			active:function() {						
				fontLoaderWaitForTextLayers();								
			},
			inactive:function() {														
				fontLoaderWaitForTextLayers();								
			},
		});
   
</script>

<?php

RbGlobalObject::setVar('slide', $slide);

if ($slide->isStaticSlide() || $slider->isSlidesFromPosts()) {
    ?><input type="hidden" id="sliderid" value="<?php echo $slider->getID();
    ?>" /><?php
}

require self::getPathTemplate('template-selector');

?>

<div class="wrap settings_wrap">
	<div class="clear_both"></div>

	<div class="title_line" style="margin-bottom:0px !important;">		
		<a href="<?php echo RbSliderGlobals::LINK_HELP_SLIDE; ?>" class="button-primary float_right rbblue mtop_10 mleft_10" target="_blank">
            <?php echo $modules->l("Help"); ?>
        </a>
	</div>

	<div class="rs_breadcrumbs">
		<a class='breadcrumb-button' href='<?php echo self::getViewUrl("sliders");?>'><i class="eg-icon-th-large"></i><?php echo $modules->l("All Sliders");?></a>
		<a class='breadcrumb-button' href="<?php echo self::getViewUrl(RbSliderAdmin::VIEW_SLIDER, "id=$sliderID"); ?>"><i class="eg-icon-cog"></i><?php echo $modules->l('Slider Settings');?></a>
		<a class='breadcrumb-button selected' href="#"><i class="eg-icon-pencil-2"></i><?php echo $modules->l('Slide Editor ');?>"<?php echo ' '.$slider->getParam("title", ""); ?>"</a>
		<div class="tp-clearfix"></div>


		<!-- FIXED TOOLBAR ON THE RIGHT SIDE -->
		<div class="rs-mini-toolbar">
			<?php
            if (!$slide->isStaticSlide()) {
                $savebtnid="button_save_slide-tb";
                $prevbtn = "button_preview_slide-tb";
                if ($slider->isSlidesFromPosts()) {
                    $prevbtn = "button_preview_slider-tb";
                }
            } else {
                $savebtnid="button_save_static_slide-tb";
                $prevbtn = "button_preview_slider-tb";
            }
            ?>
			<div class="rs-toolbar-savebtn rs-mini-toolbar-button">
				<a class='button-primary rbgreen' href='javascript:void(0)' id="<?php echo $savebtnid; ?>" >
                    <i class="material-icons">save</i>
                    <span class="mini-toolbar-text">
                        <?php echo $modules->l("Save Slide"); ?>
                    </span>
                </a>
			</div>
			
			<div class="rs-toolbar-cssbtn rs-mini-toolbar-button">
				<a class='button-primary rbpurple' href='javascript:void(0)' id='button_edit_css_global'>
                    <i class="material-icons">&lt;/&gt;</i>
                    <span class="mini-toolbar-text">
                    <?php echo $modules->l("CSS Global"); ?></span>
                </a>
			</div>


			<div class="rs-toolbar-slides rs-mini-toolbar-button">
				<?php
                    $slider_url = ($sliderTemplate == 'true') ? RbSliderAdmin::VIEW_SLIDER_TEMPLATE :
                    RbSliderAdmin::VIEW_SLIDER;
                ?>
				<a class="button-primary rbblue" href="<?php echo self::getViewUrl($slider_url, "id=$sliderID"); ?>" id="link_edit_slides_t">
                    <i class="rbicon-cog material-icons"></i>
                    <span class="mini-toolbar-text"><?php echo $modules->l("Slider Settings"); ?></span>
                </a>
			</div>
			<div class="rs-toolbar-preview rs-mini-toolbar-button">
				<a class="button-primary rbgray" href="javascript:void(0)"  id="<?php echo $prevbtn; ?>" >
                    <i class="rbicon-search-1 material-icons"></i>
                    <span class="mini-toolbar-text"><?php echo $modules->l("Preview"); ?></span>
                </a>
			</div>
			
		</div>
	</div>

	<script>
		jQuery(document).ready(function() {			
			jQuery('.rs-mini-toolbar-button').hover(function() {				
				var btn=jQuery(this),
					txt = btn.find('.mini-toolbar-text');
				punchgs.TweenLite.to(txt,0.2,{width:"100px",ease:punchgs.Linear.easeNone,overwrite:"all"});
				punchgs.TweenLite.to(txt,0.1,{autoAlpha:1,ease:punchgs.Linear.easeNone,delay:0.1,overwrite:"opacity"});
			}, function() {
				var btn=jQuery(this),
					txt = btn.find('.mini-toolbar-text');
				punchgs.TweenLite.to(txt,0.2,{autoAlpha:0,width:"0px",ease:punchgs.Linear.easeNone,overwrite:"all"});				
			});
			var mtb = jQuery('.rs-mini-toolbar'),
				mtbo = mtb.offset().top;
			jQuery(document).on("scroll",function() {
				
				if (mtbo-jQuery(window).scrollTop()<100) 
					mtb.addClass("sticky");
				else
					mtb.removeClass("sticky");
				
			})
		});
	</script>

	<?php

        
    require self::getPathTemplate("slide-selector");
        
        $usePsml = RbGlobalObject::getVar('usePsml');
        $psmlActive = RbGlobalObject::getVar('psmlActive');
        
        if ($psmlActive == true && $usePsml == 'on') {
            require self::getPathTemplate('psml-selector');
        }
        
    if (!$slide->isStaticSlide()) {
        require self::getPathTemplate('slide-general-settings');
    }

    $operations = new RbSliderOperations();
        RbGlobalObject::setVar('operations', $operations);
    $settings = $slide->getSettings();
    RbGlobalObject::setVar('settings', $settings);
    $enable_custom_size_notebook = $slider->getParam('enable_custom_size_notebook', 'off');
    $enable_custom_size_tablet = $slider->getParam('enable_custom_size_tablet', 'off');
    $enable_custom_size_iphone = $slider->getParam('enable_custom_size_iphone', 'off');
        
        RbGlobalObject::setVar('enable_custom_size_notebook', $enable_custom_size_notebook);
        RbGlobalObject::setVar('enable_custom_size_tablet', $enable_custom_size_tablet);
        RbGlobalObject::setVar('enable_custom_size_iphone', $enable_custom_size_iphone);
        
    $adv_resp_sizes = ($enable_custom_size_notebook == 'on' || $enable_custom_size_tablet == 'on' || $enable_custom_size_iphone == 'on') ? true : false;
        RbGlobalObject::setVar('adv_resp_sizes', $adv_resp_sizes);
    ?>

	<div id="jqueryui$modules->lrror_message" class="unite$modules->lrror_message" style="display:none;">
		<?php echo $modules->l("<b>Warning!!! </b>The jquery ui javascript include that is loaded by some of the plugins are custom made and not contain needed components like 'autocomplete' or 'draggable' function.
		Without those functions the editor may not work correctly. Please remove those custom jquery ui includes in order the editor will work correctly."); ?>
	</div>

	<div class="edit_slide_wrapper<?php echo ($slide->isStaticSlide()) ? ' rb_static_layers' : ''; ?>">
		<?php
                RbGlobalObject::setVar('style', $style);
        require self::getPathTemplate('slide-stage');
        ?>
		<div style="width:100%;clear:both;height:20px"></div>

		<div id="dialog_insert_icon" class="dialog_insert_icon" title="Insert Icon" style="display:none;"></div>

		<div id="dialog_template_insert" class="dialog_template_help" title="<?php echo $modules->l('Insert Meta') ?>" style="display:none;">

			<div style="clear: both;"></div>
			<?php
            switch ($slider_type) {
                case 'posts':
                case 'specific_posts':
                case 'woocommerce':
                    ?>
					<table class="table_template_help">
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('product:somemegatag')">%product:somemegatag%</a></td><td>Any custom Tag</td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('title')">%title%</a></td><td>Product Name</td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('product_price')">%product_price%</a></td><td>Product Price</td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('description_short')">%description_short%</a></td><td>Product Description Short</td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('description')">%description%</a></td><td>Product Description</td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('link')">%link%</a></td><td>The link to the Product</td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('addtocart')">%addtocart%</a></td><td>The link to the Product Add to Cart</td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('default_category')">%default_category%</a></td><td>Product Category Default</td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('date')">%date%</a></td><td>Date created</td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('date_modified')">%date_modified%</a></td><td>Date modified</td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('countdown')">%countdown%</a></td><td>Specials offer CountDown</td></tr>
					</table>
					<?php
                break;
                case 'flickr':
                    ?>
					<table class="table_template_help" id="slide-flickr-template-entry">
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('title')">{{title}}</a></td><td><?php echo $modules->l("Post Title"); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('content')">{{content}}</a></td><td><?php echo $modules->l("Post content"); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('link')">{{link}}</a></td><td><?php echo $modules->l("The link to the post"); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('date')">{{date}}</a></td><td><?php echo $modules->l("Date created"); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('author_name')">{{author_name}}</a></td><td><?php echo $modules->l('Username'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('views')">{{views}}</a></td><td><?php echo $modules->l('Views'); ?></td></tr>
					</table>
					<table class="table_template_help" id="slide-images-template-entry">
						<?php
                        foreach ($img_sizes as $img_handle => $img_name) {
                            ?>
							<tr><td><a href="javascript:UniteLayersRb.insertTemplate('image_url_<?php echo($img_handle);
                            ?>')">{{image_url_<?php echo($img_handle);
                            ?>}}</a></td><td><?php echo $modules->l("Image URL");
                            echo ' '.$img_name;
                            ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRb.insertTemplate('image_<?php echo($img_handle);
                            ?>')">{{image_<?php echo($img_handle);
                            ?>}}</a></td><td><?php echo $modules->l("Image &lt;img /&gt;");
                            echo ' '.$img_name;
                            ?></td></tr>
							<?php

                        }
                        ?>
					</table>
					<?php
                break;
                case 'instagram':
                    ?>
					<table class="table_template_help" id="slide-instagram-template-entry">
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('title')">{{title}}</a></td><td><?php echo $modules->l("Title"); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('content')">{{content}}</a></td><td><?php echo $modules->l("Content"); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('link')">{{link}}</a></td><td><?php echo $modules->l("Link"); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('date')">{{date}}</a></td><td><?php echo $modules->l("Date created"); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('author_name')">{{author_name}}</a></td><td><?php echo $modules->l('Username'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('likes')">{{likes}}</a></td><td><?php echo $modules->l('Number of Likes'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('num_comments')">{{num_comments}}</a></td><td><?php echo $modules->l('Number of Comments'); ?></td></tr>
					</table>
					<table class="table_template_help" id="slide-images-template-entry">
						<?php
                        foreach ($img_sizes as $img_handle => $img_name) {
                            ?>
							<tr><td><a href="javascript:UniteLayersRb.insertTemplate('image_url_<?php echo($img_handle);
                            ?>')">{{image_url_<?php echo($img_handle);
                            ?>}}</a></td><td><?php echo $modules->l("Image URL");
                            echo ' '.$img_name;
                            ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRb.insertTemplate('image_<?php echo($img_handle);
                            ?>')">{{image_<?php echo($img_handle);
                            ?>}}</a></td><td><?php echo $modules->l("Image &lt;img /&gt;");
                            echo ' '.$img_name;
                            ?></td></tr>
							<?php

                        }
                        ?>
					</table>
					<?php
                break;
                case 'twitter':
                    ?>
					<table class="table_template_help" id="slide-twitter-template-entry">
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('title')">{{title}}</a></td><td><?php echo $modules->l('Title'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('content')">{{content}}</a></td><td><?php echo $modules->l('Content'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('link')">{{link}}</a></td><td><?php echo $modules->l("Link"); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('date_published')">{{date_published}}</a></td><td><?php echo $modules->l('Pulbishing Date'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('author_name')">{{author_name}}</a></td><td><?php echo $modules->l('Username'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('retweet_count')">{{retweet_count}}</a></td><td><?php echo $modules->l('Retweet Count'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('favorite_count')">{{favorite_count}}</a></td><td><?php echo $modules->l('Favorite Count'); ?></td></tr>
					</table>
					<table class="table_template_help" id="slide-images-template-entry">
						<?php
                        foreach ($img_sizes as $img_handle => $img_name) {
                            ?>
							<tr><td><a href="javascript:UniteLayersRb.insertTemplate('image_url_<?php echo($img_handle);
                            ?>')">{{image_url_<?php echo($img_handle);
                            ?>}}</a></td><td><?php echo $modules->l("Image URL");
                            echo ' '.$img_name;
                            ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRb.insertTemplate('image_<?php echo($img_handle);
                            ?>')">{{image_<?php echo($img_handle);
                            ?>}}</a></td><td><?php echo $modules->l("Image &lt;img /&gt;");
                            echo ' '.$img_name;
                            ?></td></tr>
							<?php

                        }
                        ?>
					</table>
					<?php
                break;
                case 'facebook':
                    ?>
					<table class="table_template_help" id="slide-facebook-template-entry">
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('title')">{{title}}</a></td><td><?php echo $modules->l('Title'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('content')">{{content}}</a></td><td><?php echo $modules->l('Content'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('link')">{{link}}</a></td><td><?php echo $modules->l('Link'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('date_published')">{{date_published}}</a></td><td><?php echo $modules->l('Pulbishing Date'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('date_published')">{{date_modified}}</a></td><td><?php echo $modules->l('Last Modify Date'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('author_name')">{{author_name}}</a></td><td><?php echo $modules->l('Username'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('likes')">{{likes}}</a></td><td><?php echo $modules->l('Number of Likes'); ?></td></tr>
					</table>
					<table class="table_template_help" id="slide-images-template-entry">
						<?php
                        foreach ($img_sizes as $img_handle => $img_name) {
                            ?>
							<tr><td><a href="javascript:UniteLayersRb.insertTemplate('image_url_<?php echo($img_handle);
                            ?>')">{{image_url_<?php echo($img_handle);
                            ?>}}</a></td><td><?php echo $modules->l("Image URL");
                            echo ' '.$img_name;
                            ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRb.insertTemplate('image_<?php echo($img_handle);
                            ?>')">{{image_<?php echo($img_handle);
                            ?>}}</a></td><td><?php echo $modules->l("Image &lt;img /&gt;");
                            echo ' '.$img_name;
                            ?></td></tr>
							<?php

                        }
                        ?>
					</table>
					<?php
                break;
                case 'youtube':
                    ?>
					<table class="table_template_help" id="slide-youtube-template-entry">
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('title')">{{title}}</a></td><td><?php echo $modules->l('Title'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('excerpt')">{{excerpt}}</a></td><td><?php echo $modules->l('Excerpt'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('content')">{{content}}</a></td><td><?php echo $modules->l('Content'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('date_published')">{{date_published}}</a></td><td><?php echo $modules->l('Pulbishing Date'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('link')">{{link}}</a></td><td><?php echo $modules->l('Link'); ?></td></tr>
					</table>
					<table class="table_template_help" id="slide-images-template-entry">
						<?php
                        foreach ($img_sizes as $img_handle => $img_name) {
                            ?>
							<tr><td><a href="javascript:UniteLayersRb.insertTemplate('image_url_<?php echo($img_handle);
                            ?>')">{{image_url_<?php echo($img_handle);
                            ?>}}</a></td><td><?php echo $modules->l("Image URL");
                            echo ' '.$img_name;
                            ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRb.insertTemplate('image_<?php echo($img_handle);
                            ?>')">{{image_<?php echo($img_handle);
                            ?>}}</a></td><td><?php echo $modules->l("Image &lt;img /&gt;");
                            echo ' '.$img_name;
                            ?></td></tr>
							<?php

                        }
                        ?>
					</table>
					<?php
                break;
                case 'vimeo':
                    ?>
					<table class="table_template_help" id="slide-vimeo-template-entry">
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('title')">{{title}}</a></td><td><?php echo $modules->l('Title'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('excerpt')">{{excerpt}}</a></td><td><?php echo $modules->l('Excerpt'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('content')">{{content}}</a></td><td><?php echo $modules->l('Content'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('link')">{{link}}</a></td><td><?php echo $modules->l('The link to the post'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('date_published')">{{date_published}}</a></td><td><?php echo $modules->l('Pulbishing Date'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('author_name')">{{author_name}}</a></td><td><?php echo $modules->l('Username'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('likes')">{{likes}}</a></td><td><?php echo $modules->l('Number of Likes'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('views')">{{views}}</a></td><td><?php echo $modules->l('Number of Views'); ?></td></tr>
						<tr><td><a href="javascript:UniteLayersRb.insertTemplate('num_comments')">{{num_comments}}</a></td><td><?php echo $modules->l('Number of Comments'); ?></td></tr>
					</table>
					<table class="table_template_help" id="slide-images-template-entry">
						<?php
                        foreach ($img_sizes as $img_handle => $img_name) {
                            ?>
							<tr><td><a href="javascript:UniteLayersRb.insertTemplate('image_url_<?php echo($img_handle);
                            ?>')">{{image_url_<?php echo($img_handle);
                            ?>}}</a></td><td><?php echo $modules->l("Image URL");
                            echo ' '.$img_name;
                            ?></td></tr>
							<tr><td><a href="javascript:UniteLayersRb.insertTemplate('image_<?php echo($img_handle);
                            ?>')">{{image_<?php echo($img_handle);
                            ?>}}</a></td><td><?php echo $modules->l("Image &lt;img /&gt;");
                            echo ' '.$img_name;
                            ?></td></tr>
							<?php

                        }
                        ?>
					</table>
					<?php
                break;
            }
            ?>
			<script type="text/javascript">
			jQuery('document').ready(function() {
				jQuery('.rs-template-settings-tabs li').click(function() {
					var tw = jQuery('.rs-template-settings-tabs .selected'),
						tn = jQuery(this);
					jQuery(tw.data('content')).hide(0);
					tw.removeClass("selected");
					tn.addClass("selected");
					jQuery(tn.data('content')).show(0);
				});
				jQuery('.rs-template-settings-tabs li:first-child').click();
			});
		</script>
		</div>

		<div id="dialog_advanced_css" class="dialog_advanced_css" title="<?php echo $modules->l('Advanced CSS'); ?>" style="display:none;">
			<div style="display: none;"><span id="rb-example-style-layer">example</span></div>
			<div class="first-css-area">
				<span class="advanced-css-title" style="background:#e67e22"><?php echo $modules->l('Style from Options'); ?><span style="margin-left:15px;font-size:11px;font-style:italic">(<?php echo $modules->l('Editable via Option Fields, Saved in the Class:'); ?><span class="current-advance-edited-class"></span>)</span></span>
				<textarea id="textarea_template_css_editor_uneditable" rows="20" cols="81" disabled="disabled"></textarea>
			</div>
			<div class="second-css-area">
				<span class="advanced-css-title"><?php echo $modules->l('Additional Custom Styling'); ?><span style="margin-left:15px;font-size:11px;font-style:italic">(<?php echo $modules->l('Appended in the Class:'); ?><span class="current-advance-edited-class"></span>)</span></span>
				<textarea id="textarea_advanced_css_editor" rows="20" cols="81"></textarea>
			</div>
		</div>
		
		<div id="dialog_save_as_css" class="dialog_save_as_css" title="<?php echo $modules->l('Save As'); ?>" style="display:none;">
			<div style="margin-top:14px">
				<span style="margin-right:15px"><?php echo $modules->l('Save As:'); ?></span><input id="rs-save-as-css" type="text" name="rs-save-as-css" value="" />
			</div>
		</div>
		 
		<div id="dialog_rename_css" class="dialog_rename_css" title="<?php echo $modules->l('Rename CSS'); ?>" style="display:none;">
			<div style="margin-top:14px">
				<span style="margin-right:15px"><?php echo $modules->l('Rename to:'); ?></span><input id="rs-rename-css" type="text" name="rs-rename-css" value="" />
			</div>
		</div>
		 
		<div id="dialog_advanced_layer_css" class="dialog_advanced_layer_css" title="<?php echo $modules->l('Layer Inline CSS'); ?>" style="display:none;">
			<div class="first-css-area">
				<span class="advanced-css-title" style="background:#e67e22"><?php echo $modules->l('Advanced Custom Styling'); ?><span style="margin-left:15px;font-size:11px;font-style:italic">(<?php echo $modules->l('Appended Inline to the Layer Markup'); ?>)</span></span>
				<textarea id="textarea_template_css_editor_layer" name="textarea_template_css_editor_layer"></textarea>
			</div>
		</div>
		
		<div id="dialog_save_as_animation" class="dialog_save_as_animation" title="<?php echo $modules->l('Save As'); ?>" style="display:none;">
			<div style="margin-top:14px">
				<span style="margin-right:15px"><?php echo $modules->l('Save As:'); ?></span><input id="rs-save-as-animation" type="text" name="rs-save-as-animation" value="" />
			</div>
		</div>
		
		<div id="dialog_save_animation" class="dialog_save_animation" title="<?php echo $modules->l('Save Under'); ?>" style="display:none;">
			<div style="margin-top:14px">
				<span style="margin-right:15px"><?php echo $modules->l('Save Under:'); ?></span><input id="rs-save-under-animation" type="text" name="rs-save-under-animation" value="" />
			</div>
		</div>
		
		<script type="text/javascript">
			
			<?php
            $icon_sets = RbSliderBase::getIconSets();
            $sets = array();
            if (!empty($icon_sets)) {
                $sets = implode("','", $icon_sets);
            }
            ?>

			 var rs_icon_sets = new Array('<?php echo $sets; ?>');
			
			 
			jQuery(document).ready(function() {

				
				
				<?php if (!empty($jsonLayers)) {
    ?>
					//set init layers object
					UniteLayersRb.setInitLayersJson(<?php echo $jsonLayers?>);
				<?php 
} ?>
				
				<?php
                if ($slide->isStaticSlide()) {
                    $arrayDemoLayers = array();
                    $arrayDemoSettings = array();
                    $all_slides = RbGlobalObject::getVar('all_slides');
                    if (!empty($all_slides) && is_array($all_slides)) {
                        foreach ($all_slides as $cSlide) {
                            $arrayDemoLayers[$cSlide->getID()] = $cSlide->getLayers();
                            $arrayDemoSettings[$cSlide->getID()] = $cSlide->getParams();
                        }
                    }
                    $jsonDemoLayers = RbSliderFunctions::jsonEncodeForClientSide($arrayDemoLayers);
                    $jsonDemoSettings = RbSliderFunctions::jsonEncodeForClientSide($arrayDemoSettings);
                    ?>
					//set init demo layers object
					UniteLayersRb.setInitDemoLayersJson(<?php echo $jsonDemoLayers;
                    ?>);
					UniteLayersRb.setInitDemoSettingsJson(<?php echo $jsonDemoSettings;
                    ?>);
					<?php

                } ?>

				<?php if (!empty($jsonCaptions)) {
    ?>
				UniteLayersRb.setInitCaptionClasses(<?php echo $jsonCaptions;
    ?>);
				<?php 
} ?>

				<?php if (!empty($arrCustomAnim)) {
    ?>
				UniteLayersRb.setInitLayerAnim(<?php echo $arrCustomAnim;
    ?>);
				<?php 
} ?>

				<?php if (!empty($arrCustomAnimDefault)) {
    ?>
				UniteLayersRb.setInitLayerAnimsDefault(<?php echo $arrCustomAnimDefault;
    ?>);
				<?php 
} ?>

				<?php if (!empty($jsonFontFamilys)) {
    ?>
				UniteLayersRb.setInitFontTypes(<?php echo $jsonFontFamilys;
    ?>);
				<?php 
} ?>

				<?php if (!empty($arrCssStyles)) {
    ?>
				UniteCssEditorRb.setInitCssStyles(<?php echo $arrCssStyles;
    ?>);
				<?php 
} ?>

				<?php
                $trans_sizes = RbSliderFunctions::jsonEncodeForClientSide($slide->translateIntoSizes());
                ?>
				UniteLayersRb.setInitTransSetting(<?php echo $trans_sizes; ?>);
				UniteLayersRb.init("<?php echo $slideDelay; ?>");		
				UniteCssEditorRb.init();
				RbSliderAdmin.initGlobalStyles();
				RbSliderAdmin.initLayerPreview();
				RbSliderAdmin.setStaticCssCaptionsUrl('<?php echo addslashes(PS_CONTENT_DIR).'public/assets/css/static-captions.css'; ?>');

				<?php if ($kenburn_effect == 'on') {
    ?>
				jQuery('input[name="kenburn_effect"]:checked').change();
				<?php 
} ?>


				// DRAW  HORIZONTAL AND VERTICAL LINEAR
				var horl = jQuery('#hor-css-linear .linear-texts'),
					verl = jQuery('#ver-css-linear .linear-texts'),
					maintimer = jQuery('#mastertimer-linear .linear-texts'),
					mw = "<?php echo RbGlobalObject::getVar('tempwidth_jq'); ?>";
					mw = parseInt(mw.split(":")[1],0);

				for (var i=-600;i<mw;i=i+100) {
					if (mw-i<100)
						horl.append('<li style="width:'+(mw-i)+'px"><span>'+i+'</span></li>');
					else
						horl.append('<li><span>'+i+'</span></li>');
				}

				for (var i=0;i<2000;i=i+100) {
					verl.append('<li><span>'+i+'</span></li>');
				}

				for (var i=0;i<160;i=i+1) {
					var txt = i+"s";

					maintimer.append('<li><span>'+txt+'</span></li>');
				}

				// SHIFT RULERS and TEXTS and HELP LINES//
				function horRuler() {
					var dl = jQuery('#divLayers'),
						l = parseInt(dl.offset().left,0) - parseInt(jQuery('#thelayer-editor-wrapper').offset().left,0);
					jQuery('#hor-css-linear').css({backgroundPosition:(l)+"px 50%"});
					jQuery('#hor-css-linear .linear-texts').css({left:(l-595)+"px"});
					jQuery('#hor-css-linear .helplines-offsetcontainer').css({left:(l)+"px"});

					jQuery('#ver-css-linear .helplines').css({left:"-15px"}).width(jQuery('#thelayer-editor-wrapper').outerWidth(true)-35);
					jQuery('#hor-css-linear .helplines').css({top:"-15px"}).height(jQuery('#thelayer-editor-wrapper').outerHeight(true)-41);
				}

				horRuler();
				jQuery('.adb-input').on("change blur focus",setExampleButtons);
				jQuery('.ads-input, input[name="shape_fullwidth"], input[name="shape_fullheight"]').on("change blur focus",setExampleShape);
				jQuery('.ui-autocomplete').on('click',setExampleButtons);

				jQuery('.ps-color-result').on("click",function() {

					if (jQuery(this).hasClass("ps-picker-open"))
						jQuery(this).closest('.ps-picker-container').addClass("pickerisopen");
					else
						jQuery(this).closest('.ps-picker-container').removeClass("pickerisopen");
				});

				jQuery("body").click(function(event) {
					jQuery('.ps-picker-container.pickerisopen').removeClass("pickerisopen");
				})

				jQuery(window).resize(horRuler);
				jQuery('#divLayers-wrapper').on('scroll',horRuler);


				jQuery('#toggle-idle-hover .icon-stylehover').click(function() {
					var bt = jQuery('#toggle-idle-hover');
					bt.removeClass("idleisselected").addClass("hoverisselected");
					jQuery('#tp-idle-state-advanced-style').hide();
					jQuery('#tp-hover-state-advanced-style').show();
				});

				jQuery('#toggle-idle-hover .icon-styleidle').click(function() {
					var bt = jQuery('#toggle-idle-hover');
					bt.addClass("idleisselected").removeClass("hoverisselected");
					jQuery('#tp-idle-state-advanced-style').show();
					jQuery('#tp-hover-state-advanced-style').hide();
				});


				jQuery('input[name="hover_allow"]').on("change",function() {
					if (jQuery(this).attr("checked")=="checked") {
						jQuery('#idle-hover-swapper').show();
					} else {
						jQuery('#idle-hover-swapper').hide();
					}
				});


				// HIDE /SHOW  INNER SAVE,SAVE AS ETC..
				jQuery('.clicktoshowmoresub').click(function() {
					jQuery(this).find('.clicktoshowmoresub_inner').show();
				});

				jQuery('.clicktoshowmoresub').on('mouseleave',function() {
					jQuery(this).find('.clicktoshowmoresub_inner').hide();
				});
				
				//arrowRepeater();
				function arrowRepeater() {
					var tw = new punchgs.TimelineLite();
					tw.add(punchgs.TweenLite.from(jQuery('.animatemyarrow'),0.5,{x:-10,opacity:0}),0);
					tw.add(punchgs.TweenLite.to(jQuery('.animatemyarrow'),0.5,{x:10,opacity:0}),0.5);
					
					tw.play(0);
					tw.eventCallback("onComplete",function() {
						tw.restart();
					})
				}
				
				RbSliderSettings.createModernOnOff();

			});

		</script>

	

		<?php
        if (!$slide->isStaticSlide()) {
            ?>
		<?php 
        } else {
            ?>

		<?php 
        } ?>

		<?php
        if (!$slide->isStaticSlide()) {
            ?>

		<?php 
        } ?>
	</div>

	<div class="vert_sap"></div>

	<div id="dialog_rename_animation" class="dialog_rename_animation" title="<?php echo $modules->l('Rename Animation'); ?>" style="display:none;">
		<div style="margin-top:14px">
			<span style="margin-right:15px"><?php echo $modules->l('Rename to:'); ?></span><input id="rs-rename-animation" type="text" name="rs-rename-animation" value="" />
		</div>
	</div>


</div>

<?php
if ($slide->isStaticSlide()) {
    $slideID = $slide->getID();
}

$mslide_list = array();
if(!empty($arrSlidesPSML)){
	foreach($arrSlidesPSML as $arwmpl) {
		if($arwmpl['id'] == $slideID) continue;
		
		$mslide_list[] = array($arwmpl['id'] => $arwmpl['title']);
	}
}
$mslide_list = RbSliderFunctions::jsonEncodeForClientSide($mslide_list);

?>
<script type="text/javascript">
	var g_patternViewSlide = '<?php echo $patternViewSlide; ?>';

	
	var g_messageDeleteSlide = "<?php echo $modules->l("Delete this slide?"); ?>";
	jQuery(document).ready(function(){
		RbSliderAdmin.initEditSlideView(<?php echo $slideID; ?>, <?php echo $sliderID; ?>, <?php echo ($slide->isStaticSlide()) ? 'true' : 'false'; ?>);
		
		UniteLayersRb.setInitSlideIds(<?php echo $mslide_list; ?>);
        
	});
	var curSlideID = <?php echo $slideID; ?>;
</script>

<?php

require self::getPathTemplate("../system/dialog-copy-move");
