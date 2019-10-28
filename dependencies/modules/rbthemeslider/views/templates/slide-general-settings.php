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

$slider_type = RbGlobalObject::getVar('slider_type');
$bgType = RbGlobalObject::getVar('bgType');
$modules = new Rbthemeslider();

?>
<div class="editor_buttons_wrapper  postbox unite-postbox" style="max-width:100% !important; min-width:1040px !important;">
    <div class="box-closed tp-accordion" style="border-bottom:5px solid #ddd;">
        <ul class="rs-slide-settings-tabs">
            <li data-content="#slide-main-image-settings-content" class="selected"><i style="height:45px" class="rs-mini-layer-icon eg-icon-picture-1 rs-toolbar-icon"></i><span><?php echo $modules->l("Main Background"); ?></span></li>
            <li data-content="#slide-general-settings-content"><i style="height:45px" class="rs-mini-layer-icon rs-icon-chooser-2 rs-toolbar-icon"></i><?php echo $modules->l("General Settings"); ?></li>
            <li data-content="#slide-thumbnail-settings-content"><i style="height:45px" class="rs-mini-layer-icon eg-icon-flickr-1 rs-toolbar-icon"></i><?php echo $modules->l("Thumbnail"); ?></li>
            <li data-content="#slide-animation-settings-content" id="slide-animation-settings-content-tab"><i style="height:45px" class="rs-mini-layer-icon rs-icon-chooser-3 rs-toolbar-icon"></i><?php echo $modules->l("Slide Animation"); ?></li>
            <li data-content="#slide-seo-settings-content"><i style="height:45px" class="rs-mini-layer-icon rs-icon-advanced rs-toolbar-icon"></i><?php echo $modules->l("Link & Seo"); ?></li>
            <li data-content="#slide-info-settings-content"><i style="height:45px; font-size:16px;" class="rs-mini-layer-icon eg-icon-info-circled rs-toolbar-icon"></i><?php echo $modules->l("Slide Info"); ?></li>						
        </ul>

        <div style="clear:both"></div>
        <script type="text/javascript">
            jQuery('document').ready(function() {
                jQuery('.rs-slide-settings-tabs li').click(function() {
                    var tw = jQuery('.rs-slide-settings-tabs .selected'),
                            tn = jQuery(this);
                    jQuery(tw.data('content')).hide(0);
                    tw.removeClass("selected");
                    tn.addClass("selected");
                    jQuery(tn.data('content')).show(0);
                });
            });
        </script>
    </div>
    <div style="padding:15px">
        <form name="form_slide_params" id="form_slide_params" class="slide-main-settings-form">

            <div id="slide-main-image-settings-content" class="slide-main-settings-form">

                <ul class="rs-layer-main-image-tabs" style="display:inline-block; ">
                    <li data-content="#mainbg-sub-source" class="selected"><?php echo $modules->l('Source'); ?></li>
                    <li class="mainbg-sub-settings-selector" data-content="#mainbg-sub-setting"><?php echo $modules->l('Source Settings'); ?></li>
                    <li class="mainbg-sub-parallax-selector" data-content="#mainbg-sub-parallax"><?php echo $modules->l('Parallax / 3D'); ?></li>
                    <li class="mainbg-sub-kenburns-selector" data-content="#mainbg-sub-kenburns"><?php echo $modules->l('Ken Burns'); ?></li>
                </ul>

                <div class="tp-clearfix"></div>

                <script type="text/javascript">
                    jQuery('document').ready(function() {
                        jQuery('.rs-layer-main-image-tabs li').click(function() {
                            var tw = jQuery('.rs-layer-main-image-tabs .selected'),
                                    tn = jQuery(this);
                            jQuery(tw.data('content')).hide(0);
                            tw.removeClass("selected");
                            tn.addClass("selected");
                            jQuery(tn.data('content')).show(0);
                        });
                    });
                </script>

                <span id="mainbg-sub-source" style="display:block">
                    <div style="float:none; clear:both; margin-bottom: 15px;"></div>
                    <input type="hidden" name="rs-gallery-type" value="<?php echo $slider_type; ?>" />
                    <span class="diblock bg-settings-block">												
                        <?php
                        if ($slider_type == 'posts' || $slider_type == 'specific_posts' || $slider_type == 'woocommerce') {

                            ?>
                            <label><?php echo $modules->l("Featured Image");

                            ?></label>
                            <input
                                type="radio"
                                name="background_type"
                                value="image"
                                class="bgsrcchanger"
                                data-callid="tp-bgimagepssrc"
                                data-imgsettings="on"
                                data-bgtype="image"
                                id="radio_back_image" <?php checked($bgType, 'image');?>
                            >

                            <?php
                        } elseif ($slider_type !== 'gallery') {

                            ?>
                            <label><?php echo $modules->l("Stream Image");

                            ?></label>
                            <input type="radio" name="background_type" value="image" class="bgsrcchanger" data-callid="tp-bgimagepssrc" data-imgsettings="on" data-bgtype="image" id="radio_back_image" <?php checked($bgType, 'image');

                               ?>>
                                <?php
                                if ($slider_type == 'vimeo' || $slider_type == 'youtube' || $slider_type == 'instagram' || $slider_type == 'twitter') {

                                    ?>
                                <div class="tp-clearfix"></div>
                                <label><?php echo $modules->l("Stream Video");

                               ?></label>
                                <input type="radio" name="background_type" value="stream<?php echo $slider_type;

                                    ?>" class="bgsrcchanger" data-callid="tp-bgimagepssrc" data-imgsettings="on" data-bgtype="stream<?php echo $slider_type;

                            ?>" <?php checked($bgType, 'stream' . $slider_type);

                            ?>>
                                <span id="streamvideo_cover" class="streamvideo_cover" style="display:none;margin-left:20px;">
                                    <span style="margin-right: 10px"><?php echo $modules->l("Use Cover");

                                    ?></span>
                                    <input type="checkbox" class="tp-moderncheckbox" id="stream_do_cover" name="stream_do_cover" data-unchecked="off" <?php
                               $stream_do_cover = RbGlobalObject::getVar('stream_do_cover');
                               checked($stream_do_cover, 'on');

                                    ?>>
                                </span>

                                <div class="tp-clearfix"></div>
                                <label><?php echo $modules->l("Stream Video + Image");

                                    ?></label>
                                <input type="radio" name="background_type" value="stream<?php echo $slider_type;

                                    ?>both" class="bgsrcchanger" data-callid="tp-bgimagepssrc" data-imgsettings="on" data-bgtype="stream<?php echo $slider_type;

                                    ?>both" <?php checked($bgType, 'stream' . $slider_type . 'both');

                                    ?>>
                                <span id="streamvideo_cover_both" class="streamvideo_cover_both" style="display:none;margin-left:20px;">
                                    <span style="margin-right: 10px"><?php echo $modules->l("Use Cover");

                                    ?></span>
                                    <input type="checkbox" class="tp-moderncheckbox" id="stream_do_cover_both" name="stream_do_cover_both" data-unchecked="off" <?php
                        $stream_do_cover_both = RbGlobalObject::getVar('stream_do_cover_both');
                        checked($stream_do_cover_both, 'on');

                        ?>>
                                </span>
        <?php
    }
} else {
    ?>
    <label ><?php echo $modules->l("Main / Background Image");
    ?></label>
        <input type="radio" name="background_type" value="image" class="bgsrcchanger" data-callid="tp-bgimagepssrc" data-imgsettings="on" data-bgtype="image" id="radio_back_image" <?php checked($bgType, 'image');
    ?>>
    <?php
}

?>
                        <span id="tp-bgimagepssrc" class="bgsrcchanger-div" style="display:none;margin-left:20px;">
                            <a href="javascript:void(0)" id="button_change_image" class="button-primary rbblue" >
                                <?php echo $modules->l("Change Image"); ?>
                            </a>
                        </span>

                        <div class="tp-clearfix"></div>

                        <label><?php echo $modules->l("External URL"); ?></label>
                        <input type="radio" name="background_type" value="external" data-callid="tp-bgimageextsrc" data-imgsettings="on" class="bgsrcchanger" data-bgtype="external" id="radio_back_external" <?php checked($bgType, 'external'); ?>>

                        <span id="tp-bgimageextsrc" class="bgsrcchanger-div" style="display:none;margin-left:20px;">
                            <input type="text" name="bg_external" id="slide_bg_external" value="<?php echo RbGlobalObject::getVar('slideBGExternal'); ?>" <?php echo ($bgType != 'external') ? ' class="disabled"' : ''; ?>>
                            <a href="javascript:void(0)" id="button_change_external" class="button-primary rbblue" ><?php echo $modules->l("Get External"); ?></a>
                        </span>

                        <div class="tp-clearfix"></div>

                        <label><?php echo $modules->l("Transparent"); ?></label>
                        <input type="radio" name="background_type" value="trans" data-callid="" class="bgsrcchanger" data-bgtype="trans" id="radio_back_trans" <?php checked($bgType, 'trans'); ?>>
                        <div class="tp-clearfix"></div>

                        <label><?php echo $modules->l("Solid Colored"); ?></label>
                        <input type="radio" name="background_type" value="solid"  data-callid="tp-bgcolorsrc" class="bgsrcchanger" data-bgtype="solid" id="radio_back_solid" <?php checked($bgType, 'solid'); ?>>

                        <span id="tp-bgcolorsrc"  class="bgsrcchanger-div"  style="display:none;margin-left:20px;">
                            <input type="text" name="bg_color" id="slide_bg_color" class="my-color-field slide_bg_color inputColorPicker" value="<?php echo RbGlobalObject::getVar('slideBGColor');
                            ; ?>">
                        </span>
                        <div class="tp-clearfix"></div>

                        <label><?php echo $modules->l("YouTube Video"); ?></label>
                        <input type="radio" name="background_type" value="youtube"  data-callid="tp-bgyoutubesrc" class="bgsrcchanger" data-bgtype="youtube" id="radio_back_youtube" <?php checked($bgType, 'youtube'); ?>>
                        <div class="tp-clearfix"></div>

                        <span id="tp-bgyoutubesrc" class="bgsrcchanger-div" style="display:none; margin-left:20px;">
                            <label style="min-width:180px"><?php echo $modules->l("ID:"); ?></label>
                            <input type="text" name="slide_bg_youtube" id="slide_bg_youtube" value="<?php echo RbGlobalObject::getVar('slideBGYoutube'); ?>" <?php echo ($bgType != 'youtube') ? ' class="disabled"' : ''; ?>>							
<?php echo $modules->l('example: T8--OggjJKQ'); ?>
                            <div class="tp-clearfix"></div>
                            <label style="min-width:180px"><?php echo $modules->l("Cover Image:"); ?></label>
                            <span id="youtube-image-picker"></span>
                        </span>
                        <div class="tp-clearfix"></div>

                        <label><?php echo $modules->l("Vimeo Video"); ?></label>
                        <input type="radio" name="background_type" value="vimeo"  data-callid="tp-bgvimeosrc" class="bgsrcchanger" data-bgtype="vimeo" id="radio_back_vimeo" <?php checked($bgType, 'vimeo'); ?>>
                        <div class="tp-clearfix"></div>

                        <span id="tp-bgvimeosrc" class="bgsrcchanger-div" style="display:none; margin-left:20px;">
                            <label style="min-width:180px"><?php echo $modules->l("ID:"); ?></label>
                            <input type="text" name="slide_bg_vimeo" id="slide_bg_vimeo" value="<?php echo RbGlobalObject::getVar('slideBGVimeo'); ?>" <?php echo ($bgType != 'vimeo') ? ' class="disabled"' : ''; ?>>							
<?php echo $modules->l('example: 30300114'); ?>
                            <div class="tp-clearfix"></div>
                            <label style="min-width:180px"><?php echo $modules->l("Cover Image:"); ?></label>
                            <span id="vimeo-image-picker"></span>
                        </span>
                        <div class="tp-clearfix"></div>

                        <label><?php echo $modules->l("HTML5 Video"); ?></label>
                        <input type="radio" name="background_type" value="html5"  data-callid="tp-bghtmlvideo" class="bgsrcchanger" data-bgtype="html5" id="radio_back_htmlvideo" <?php checked($bgType, 'html5'); ?>>
                        <div class="tp-clearfix"></div>

                        <span id="tp-bghtmlvideo" class="bgsrcchanger-div" style="display:none; margin-left:20px;">

                            <label style="min-width:180px"><?php echo $modules->l('MPEG:'); ?></label>
                            <input type="text" name="slide_bg_html_mpeg" id="slide_bg_html_mpeg" value="<?php echo RbGlobalObject::getVar('slideBGhtmlmpeg'); ?>" <?php echo ($bgType != 'html5') ? ' class="disabled"' : ''; ?>>
                            <span class="vidsrcchanger-div" style="margin-left:20px;">
                                <a href="javascript:void(0)" data-inptarget="slide_bg_html_mpeg" class="button_change_video button-primary rbblue" ><?php echo $modules->l('Change Video'); ?></a>
                            </span>
                            <div class="tp-clearfix"></div>
                            <label style="min-width:180px"><?php echo $modules->l('WEBM:'); ?></label>
                            <input type="text" name="slide_bg_html_webm" id="slide_bg_html_webm" value="<?php echo RbGlobalObject::getVar('slideBGhtmlwebm'); ?>" <?php echo ($bgType != 'html5') ? ' class="disabled"' : ''; ?>>
                            <span class="vidsrcchanger-div" style="margin-left:20px;">
                                <a href="javascript:void(0)" data-inptarget="slide_bg_html_webm" class="button_change_video button-primary rbblue" ><?php echo $modules->l('Change Video'); ?></a>
                            </span>
                            <div class="tp-clearfix"></div>
                            <label style="min-width:180px"><?php echo $modules->l('OGV:'); ?></label>
                            <input type="text" name="slide_bg_html_ogv" id="slide_bg_html_ogv" value="<?php echo RbGlobalObject::getVar('slideBGhtmlogv'); ?>" <?php echo ($bgType != 'html5') ? ' class="disabled"' : ''; ?>>							
                            <span class="vidsrcchanger-div" style="margin-left:20px;">
                                <a href="javascript:void(0)" data-inptarget="slide_bg_html_ogv" class="button_change_video button-primary rbblue" ><?php echo $modules->l('Change Video'); ?></a>
                            </span>
                            <div class="tp-clearfix"></div>
                            <label style="min-width:180px"><?php echo $modules->l('Cover Image:'); ?></label>
                            <span id="html5video-image-picker"></span>
                        </span>
                    </span>
                </span>
                <span id="mainbg-sub-setting" style="display:none">
                    <div class="rs-img-source-url">
                        <div style="float:none; clear:both; margin-bottom: 15px;"></div>
                        <label><?php echo $modules->l('Image Source:'); ?></label>
                        <span class="text-selectable" id="the_image_source_url" style="margin-right:20px"></span>
                    </div>

                    <div class="rs-img-source-size">
                        <div style="float:none; clear:both; margin-bottom: 15px;"></div>
                        <label><?php echo $modules->l('Image Source Size:'); ?></label>
                        <span style="margin-right:20px">
                            <select name="image_source_type">
                            <?php
                            $img_sizes = RbGlobalObject::getVar('img_sizes');
                            $bg_image_size = RbGlobalObject::getVar('bg_image_size');
                            foreach ($img_sizes as $imghandle => $imgSize) {
                                $sel = ($bg_image_size == $imghandle) ? ' selected="selected"' : '';
                                echo '<option value="' . ($imghandle) . '"' . $sel . '>' . $imgSize . '</option>';
                            }

                            ?>
                            </select>
                        </span>
                    </div>

                    <span id="tp-bgimagesettings" class="bgsrcchanger-div" style="display:none;">
                        <p>
<?php
$slideParams = RbGlobalObject::getVar('slideParams');
$alt_option = RbSliderFunctions::getVal($slideParams, 'alt_option', 'media_library');

?>
                            <label><?php echo $modules->l("Alt:"); ?></label>
                            <select id="alt_option" name="alt_option">
                                <option value="media_library" <?php selected($alt_option, 'media_library'); ?>><?php echo $modules->l('From Media Library'); ?></option>
                                <option value="file_name" <?php selected($alt_option, 'file_name'); ?>><?php echo $modules->l('From Filename'); ?></option>
                                <option value="custom" <?php selected($alt_option, 'custom'); ?>><?php echo $modules->l('Custom'); ?></option>
                            </select>
<?php $alt_attr = RbSliderFunctions::getVal($slideParams, 'alt_attr', ''); ?>
                            <input style="<?php echo ($alt_option !== 'custom') ? 'display:none;' : ''; ?>" type="text" id="alt_attr" name="alt_attr" value="<?php echo $alt_attr; ?>">
                        </p>
                        <p class="ext_setting" style="display: none;">
                            <label><?php echo $modules->l('Width:') ?></label>
                            <input type="text" name="ext_width" value="<?php echo RbGlobalObject::getVar('ext_width'); ?>" />
                        </p>
                        <p class="ext_setting" style="display: none;">
                            <label><?php echo $modules->l('Height:') ?></label>
                            <input type="text" name="ext_height" value="<?php echo RbGlobalObject::getVar('ext_height'); ?>" />
                        </p>
                    </span>					

                    <span id="video-settings" style="display: block;">
                        <p>
                            <label for="video_force_cover" class="video-label"><?php echo $modules->l('Force Cover:'); ?></label>
                            <input type="checkbox" class="tp-moderncheckbox" id="video_force_cover" name="video_force_cover" data-unchecked="off" <?php $video_force_cover = RbGlobalObject::getVar('video_force_cover');
                                checked($video_force_cover, 'on'); ?>>
                        </p>
                        <span id="video_dotted_overlay_wrap">
                            <label for="video_dotted_overlay">
                                <?php echo $modules->l('Dotted Overlay:'); ?>
                            </label>				
                            <select id="video_dotted_overlay" name="video_dotted_overlay" style="width:100px">
                                <option <?php $video_dotted_overlay = RbGlobalObject::getVar('video_dotted_overlay');
                                selected($video_dotted_overlay, 'none'); ?> value="none"><?php echo $modules->l('none'); ?></option>
                                <option <?php selected($video_dotted_overlay, 'twoxtwo'); ?> value="twoxtwo"><?php echo $modules->l('2 x 2 Black'); ?></option>
                                <option <?php selected($video_dotted_overlay, 'twoxtwowhite'); ?> value="twoxtwowhite"><?php echo $modules->l('2 x 2 White'); ?></option>
                                <option <?php selected($video_dotted_overlay, 'threexthree'); ?> value="threexthree"><?php echo $modules->l('3 x 3 Black'); ?></option>
                                <option <?php selected($video_dotted_overlay, 'threexthreewhite'); ?> value="threexthreewhite"><?php echo $modules->l('3 x 3 White'); ?></option>
                            </select>
                            <p style="clear: both;"></p>
                        </span>
                        <label for="video_ratio">
<?php echo $modules->l("Aspect Ratio:"); ?>
                        </label>				
                        <select id="video_ratio" name="video_ratio" style="width:100px">
                            <option <?php $video_ratio = RbGlobalObject::getVar('video_ratio');
selected($video_ratio, '16:9'); ?> value="16:9"><?php echo $modules->l('16:9'); ?></option>
                            <option <?php selected($video_ratio, '4:3'); ?> value="4:3"><?php echo $modules->l('4:3'); ?></option>
                        </select>
                        <p style="clear: both;"></p>
                        <p>
                            <label for="video_ratio">
<?php echo $modules->l("Start At:"); ?>
                            </label>				
                            <input type="text" value="<?php echo RbGlobalObject::getVar('video_start_at'); ?>" name="video_start_at"> <?php echo $modules->l('For Example: 00:17'); ?>
                        <p style="clear: both;"></p>
                        </p>
                        <p>
                            <label for="video_ratio">
<?php echo $modules->l("End At:"); ?>
                            </label>				
                            <input type="text" value="<?php echo RbGlobalObject::getVar('video_end_at'); ?>" name="video_end_at"> <?php echo $modules->l('For Example: 02:17'); ?>
                        <p style="clear: both;"></p>
                        </p>
                        <p>
                            <label for="video_loop"><?php echo $modules->l('Loop Video:'); ?></label>
                            <select id="video_loop" name="video_loop" style="width: 200px;">
                                <option <?php $video_loop = RbGlobalObject::getVar('video_loop');
selected($video_loop, 'none'); ?> value="none"><?php echo $modules->l('Disable'); ?></option>
                                <option <?php selected($video_loop, 'loop'); ?> value="loop"><?php echo $modules->l('Loop, Slide is paused'); ?></option>
                                <option <?php selected($video_loop, 'loopandnoslidestop'); ?> value="loopandnoslidestop"><?php echo $modules->l('Loop, Slide does not stop'); ?></option>
                            </select>
                        </p>

                        <p>	
                            <label for="video_nextslide"><?php echo $modules->l('Next Slide On End:'); ?></label>
                            <input type="checkbox" class="tp-moderncheckbox" id="video_nextslide" name="video_nextslide" data-unchecked="off" <?php $video_nextslide = RbGlobalObject::getVar('video_nextslide');
checked($video_nextslide, 'on'); ?>>
                        </p>
                        <p>
                            <label for="video_force_rewind"><?php echo $modules->l('Rewind at Slide Start:'); ?></label>
                            <input type="checkbox" class="tp-moderncheckbox" id="video_force_rewind" name="video_force_rewind" data-unchecked="off" <?php $video_force_rewind = RbGlobalObject::getVar('video_force_rewind');
checked($video_force_rewind, 'on'); ?>>
                        </p>

                        <p>	
                            <label for="video_mute"><?php echo $modules->l('Mute Video:'); ?></label>
                            <input type="checkbox" class="tp-moderncheckbox" id="video_mute" name="video_mute" data-unchecked="off" <?php $video_mute = RbGlobalObject::getVar('video_mute');
checked($video_mute, 'on'); ?>>
                        </p>

                        <p class="vid-rb-vimeo-youtube video_volume_wrapper">
                            <label for="video_volume"><?php echo $modules->l('Video Volume:'); ?></label>
                            <input type="text" id="video_volume" name="video_volume" <?php echo RbGlobalObject::getVar('video_volume'); ?>>
                        </p>

                        <span id="vid-rb-youtube-options">
                            <p>
                                <label for="video_speed"><?php echo $modules->l('Video Speed:'); ?></label>
                                <select id="video_speed" name="video_speed" style="width:75px">
                                    <option <?php $video_speed = RbGlobalObject::getVar('video_speed');
                            selected($video_speed, '0.25'); ?> value="0.25"><?php echo $modules->l('0.25'); ?></option>
                                    <option <?php selected($video_speed, '0.50'); ?> value="0.50">0.50</option>
                                    <option <?php selected($video_speed, '1'); ?> value="1">1</option>
                                    <option <?php selected($video_speed, '1.5'); ?> value="1.5">1.5</option>
                                    <option <?php selected($video_speed, '2'); ?> value="2">2</option>
                                </select>
                            </p>
                            <p>
                                <label><?php echo $modules->l('Arguments YouTube:'); ?></label>
                                <input type="text" id="video_arguments" name="video_arguments" style="width:350px;" value="<?php echo RbGlobalObject::getVar('video_arguments'); ?>">
                            </p>
                        </span>
                        <p id="vid-rb-vimeo-options">
                            <label><?php echo $modules->l('Arguments Vimeo:'); ?></label>
                            <input type="text" id="video_arguments_vim" name="video_arguments_vim" style="width:350px;" value="<?php echo RbGlobalObject::getVar('video_arguments_vim'); ?>">
                        </p>
                    </span>

                    <span id="bg-setting-wrap">
                        <p>
                            <label for="slide_bg_fit"><?php echo $modules->l('Background Fit:'); ?></label>
                            <select name="bg_fit" id="slide_bg_fit" style="margin-right:20px">
                                <option value="cover"<?php $bgFit = RbGlobalObject::getVar('bgFit');
                                selected($bgFit, 'cover'); ?>>cover</option>
                                <option value="contain"<?php selected($bgFit, 'contain'); ?>>contain</option>
                                <option value="percentage"<?php selected($bgFit, 'percentage'); ?>>(%, %)</option>
                                <option value="normal"<?php selected($bgFit, 'normal'); ?>>normal</option>
                            </select>
                            <input type="text" name="bg_fit_x" style="min-width:54px;width:54px; <?php
                                if ($bgFit != 'percentage') {
                                    echo 'display: none; ';
                                }

?> width:60px;margin-right:10px" value="<?php echo RbGlobalObject::getVar('bgFitX'); ?>" />
                            <input type="text" name="bg_fit_y" style="min-width:54px;width:54px;  <?php
                                if ($bgFit != 'percentage') {
                                    echo 'display: none; ';
                                }

?> width:60px;margin-right:10px"  value="<?php echo RbGlobalObject::getVar('bgFitY'); ?>" />
                        </p>
                        <p>
                            <label for="slide_bg_position" id="bg-position-lbl"><?php echo $modules->l('Background Position:'); ?></label>
                            <span id="bg-start-position-wrapper">
                                <select name="bg_position" id="slide_bg_position">
                                    <option value="center top"<?php $bgPosition = RbGlobalObject::getVar('bgPosition');
                        selected($bgPosition, 'center top'); ?>>center top</option>
                                    <option value="center right"<?php selected($bgPosition, 'center right'); ?>>center right</option>
                                    <option value="center bottom"<?php selected($bgPosition, 'center bottom'); ?>>center bottom</option>
                                    <option value="center center"<?php selected($bgPosition, 'center center'); ?>>center center</option>
                                    <option value="left top"<?php selected($bgPosition, 'left top'); ?>>left top</option>
                                    <option value="left center"<?php selected($bgPosition, 'left center'); ?>>left center</option>
                                    <option value="left bottom"<?php selected($bgPosition, 'left bottom'); ?>>left bottom</option>
                                    <option value="right top"<?php selected($bgPosition, 'right top'); ?>>right top</option>
                                    <option value="right center"<?php selected($bgPosition, 'right center'); ?>>right center</option>
                                    <option value="right bottom"<?php selected($bgPosition, 'right bottom'); ?>>right bottom</option>
                                    <option value="percentage"<?php selected($bgPosition, 'percentage'); ?>>(x%, y%)</option>
                                </select>
                                <input type="text" name="bg_position_x" style="min-width:54px;width:54px; <?php
                                        if ($bgPosition != 'percentage') {
                                            echo 'display: none;';
                                        }

                                        ?>width:60px;margin-right:10px" value="<?php echo RbGlobalObject::getVar('bgPositionX'); ?>" />
                                <input type="text" name="bg_position_y" style="min-width:54px;width:54px; <?php
                                    if ($bgPosition != 'percentage') {
                                        echo 'display: none;';
                                    }

                                    ?>width:60px;margin-right:10px" value="<?php echo RbGlobalObject::getVar('bgPositionY'); ?>" />
                            </span>
                        </p>

                        <p>
                            <label><?php echo $modules->l("Background Repeat:") ?></label>
                            <span>
                                <select name="bg_repeat" id="slide_bg_repeat" style="margin-right:20px">
                                    <option value="no-repeat"<?php $bgRepeat = RbGlobalObject::getVar('bgRepeat');
                                    selected($bgRepeat, 'no-repeat'); ?>>no-repeat</option>
                                    <option value="repeat"<?php selected($bgRepeat, 'repeat'); ?>>repeat</option>
                                    <option value="repeat-x"<?php selected($bgRepeat, 'repeat-x'); ?>>repeat-x</option>
                                    <option value="repeat-y"<?php selected($bgRepeat, 'repeat-y'); ?>>repeat-y</option>
                                </select>
                            </span>
                        </p>
                    </span>

                </span>

                <span id="mainbg-sub-parallax" style="display:none">
                    <p>
                                    <?php
                                    if (RbGlobalObject::getVar('use_parallax') == "off") {
                                        echo '<i style="color:#c0392b">';
                                        $modules->l("Parallax Feature in Slider Settings is deactivated, parallax will be ignored.");
                                        echo '</i>';
                                    } else {
                                        if (RbGlobalObject::getVar('parallaxisddd') == "off") {

                                            ?>
                                <label><?php echo $modules->l("Parallax Level:");

                                            ?></label>
                                <select name="slide_parallax_level" id="slide_parallax_level">
                                    <option value="-" <?php
                                $slide_parallax_level = RbGlobalObject::getVar('slide_parallax_level');
                                selected($slide_parallax_level, '-');

                                ?>><?php echo $modules->l('No Parallax');

                                ?></option>
                                    <option value="1" <?php selected($slide_parallax_level, '1');

                            ?>>1 - (<?php
                                $parallax_level = RbGlobalObject::getVar('parallax_level');
                                echo $parallax_level[0];

                                ?>%)</option>
                                    <option value="2" <?php selected($slide_parallax_level, '2');

                                ?>>2 - (<?php echo $parallax_level[1];

                                ?>%)</option>
                                    <option value="3" <?php selected($slide_parallax_level, '3');

                                ?>>3 - (<?php echo $parallax_level[2];

                                ?>%)</option>
                                    <option value="4" <?php selected($slide_parallax_level, '4');

                        ?>>4 - (<?php echo $parallax_level[3];

                        ?>%)</option>
                                    <option value="5" <?php selected($slide_parallax_level, '5');

                        ?>>5 - (<?php echo $parallax_level[4];

                        ?>%)</option>
                                    <option value="6" <?php selected($slide_parallax_level, '6');

                        ?>>6 - (<?php echo $parallax_level[5];

                        ?>%)</option>
                                    <option value="7" <?php selected($slide_parallax_level, '7');

                        ?>>7 - (<?php echo $parallax_level[6];

                        ?>%)</option>
                                    <option value="8" <?php selected($slide_parallax_level, '8');

                        ?>>8 - (<?php echo $parallax_level[7];

                        ?>%)</option>
                                    <option value="9" <?php selected($slide_parallax_level, '9');

                        ?>>9 - (<?php echo $parallax_level[8];

                        ?>%)</option>
                                    <option value="10" <?php selected($slide_parallax_level, '10');

                        ?>>10 - (<?php echo $parallax_level[9];

                        ?>%)</option>
                                    <option value="11" <?php selected($slide_parallax_level, '11');

                        ?>>11 - (<?php echo $parallax_level[10];

                        ?>%)</option>
                                    <option value="12" <?php selected($slide_parallax_level, '12');

                        ?>>12 - (<?php echo $parallax_level[11];

                        ?>%)</option>
                                    <option value="13" <?php selected($slide_parallax_level, '13');

                        ?>>13 - (<?php echo $parallax_level[12];

                        ?>%)</option>
                                    <option value="14" <?php selected($slide_parallax_level, '14');

                        ?>>14 - (<?php echo $parallax_level[13];

                        ?>%)</option>
                                    <option value="15" <?php selected($slide_parallax_level, '15');

                        ?>>15 - (<?php echo $parallax_level[14];

                        ?>%)</option>							
                                </select>
        <?php
    } else {
        if (RbGlobalObject::getVar('parallaxbgfreeze') == "off") {

            ?>							
                                    <label><?php echo $modules->l("Selected 3D Depth:");

            ?></label>
                                    <input style="min-width:54px;width:54px" type="text" disabled value="<?php echo $parallax_level[15];

            ?>%" />							
                                    <span><i><?php echo $modules->l('3D Parallax is Enabled via Slider Settings !');

            ?></i></span>
            <?php
        } else {

            ?>
                                    <label><?php echo $modules->l("Background 3D is Disabled");

            ?></label>									
                                    <span style="display: inline-block;vertical-align: middle;line-height:32px"><i><?php echo $modules->l('To Enable 3D Parallax for Background please change the Option "BG 3D Disabled" to "OFF" via the Slider Settings !');

            ?></i></span>
            <?php
        }
    }
}

?>
                    </p>

                </span>

                <span id="mainbg-sub-kenburns" style="display:none">
                    <p>
                        <label><?php echo $modules->l('Ken Burns / Pan Zoom:'); ?></label>
                        <input type="checkbox" class="tp-moderncheckbox withlabel" id="kenburn_effect" name="kenburn_effect" data-unchecked="off" <?php $kenburn_effect = RbGlobalObject::getVar('kenburn_effect');
checked($kenburn_effect, 'on'); ?>>
                    </p>
                    <span id="kenburn_wrapper" <?php echo ($kenburn_effect == 'off') ? 'style="display: none;"' : ''; ?>>						
                        <p>
                            <label><?php echo $modules->l('Scale: (in %):'); ?></label>
                            <label style="min-width:40px"><?php echo $modules->l('From'); ?></label>
                            <input style="min-width:54px;width:54px" type="text" name="kb_start_fit" value="<?php echo (int) (RbGlobalObject::getVar('kb_start_fit')); ?>" />
                            <label style="min-width:20px"><?php echo $modules->l('To') ?></label>
                            <input style="min-width:54px;width:54px" type="text" name="kb_end_fit" value="<?php echo (int) (RbGlobalObject::getVar('kb_end_fit')); ?>" />
                        </p>

                        <p>
                            <label><?php echo $modules->l('Horizontal Offsets:') ?></label>
                            <label style="min-width:40px"><?php echo $modules->l('From'); ?></label>							
                            <input style="min-width:54px;width:54px" type="text" name="kb_start_offset_x" value="<?php echo RbGlobalObject::getVar('kbStartOffsetX'); ?>" />
                            <label style="min-width:20px"><?php echo $modules->l('To') ?></label>
                            <input style="min-width:54px;width:54px" type="text" name="kb_end_offset_x" value="<?php echo RbGlobalObject::getVar('kbEndOffsetX'); ?>" />
                            <span><i><?php echo $modules->l('Use Negative and Positive Values to offset from the Center !'); ?></i></span>
                        </p>

                        <p>
                            <label><?php echo $modules->l('Vertical Offsets:') ?></label>		
                            <label style="min-width:40px"><?php echo $modules->l('From'); ?></label>												
                            <input style="min-width:54px;width:54px" type="text" name="kb_start_offset_y" value="<?php echo RbGlobalObject::getVar('kbStartOffsetY'); ?>" />
                            <label style="min-width:20px"><?php echo $modules->l('To') ?></label>
                            <input style="min-width:54px;width:54px" type="text" name="kb_end_offset_y" value="<?php echo RbGlobalObject::getVar('kbEndOffsetY'); ?>" />
                            <span><i><?php echo $modules->l('Use Negative and Positive Values to offset from the Center !'); ?></i></span>
                        </p>

                        <p>
                            <label><?php echo $modules->l('Rotation:') ?></label>		
                            <label style="min-width:40px"><?php echo $modules->l('From'); ?></label>												
                            <input style="min-width:54px;width:54px" type="text" name="kb_start_rotate" value="<?php echo RbGlobalObject::getVar('kbStartRotate'); ?>" />
                            <label style="min-width:20px"><?php echo $modules->l('To') ?></label>
                            <input style="min-width:54px;width:54px" type="text" name="kb_end_rotate" value="<?php echo RbGlobalObject::getVar('kbEndRotate'); ?>" />
                        </p>

                        <p>
                            <label><?php echo $modules->l('Easing:'); ?></label>
                            <select name="kb_easing">
                                <option <?php $kb_easing = RbGlobalObject::getVar('kb_easing');
                    selected($kb_easing, 'Linear.easeNone'); ?> value="Linear.easeNone">Linear.easeNone</option>
                                <option <?php selected($kb_easing, 'Power0.easeIn'); ?> value="Power0.easeIn">Power0.easeIn  (linear)</option>
                                <option <?php selected($kb_easing, 'Power0.easeInOut'); ?> value="Power0.easeInOut">Power0.easeInOut  (linear)</option>
                                <option <?php selected($kb_easing, 'Power0.easeOut'); ?> value="Power0.easeOut">Power0.easeOut  (linear)</option>
                                <option <?php selected($kb_easing, 'Power1.easeIn'); ?> value="Power1.easeIn">Power1.easeIn</option>
                                <option <?php selected($kb_easing, 'Power1.easeInOut'); ?> value="Power1.easeInOut">Power1.easeInOut</option>
                                <option <?php selected($kb_easing, 'Power1.easeOut'); ?> value="Power1.easeOut">Power1.easeOut</option>
                                <option <?php selected($kb_easing, 'Power2.easeIn'); ?> value="Power2.easeIn">Power2.easeIn</option>
                                <option <?php selected($kb_easing, 'Power2.easeInOut'); ?> value="Power2.easeInOut">Power2.easeInOut</option>
                                <option <?php selected($kb_easing, 'Power2.easeOut'); ?> value="Power2.easeOut">Power2.easeOut</option>
                                <option <?php selected($kb_easing, 'Power3.easeIn'); ?> value="Power3.easeIn">Power3.easeIn</option>
                                <option <?php selected($kb_easing, 'Power3.easeInOut'); ?> value="Power3.easeInOut">Power3.easeInOut</option>
                                <option <?php selected($kb_easing, 'Power3.easeOut'); ?> value="Power3.easeOut">Power3.easeOut</option>
                                <option <?php selected($kb_easing, 'Power4.easeIn'); ?> value="Power4.easeIn">Power4.easeIn</option>
                                <option <?php selected($kb_easing, 'Power4.easeInOut'); ?> value="Power4.easeInOut">Power4.easeInOut</option>
                                <option <?php selected($kb_easing, 'Power4.easeOut'); ?> value="Power4.easeOut">Power4.easeOut</option>
                                <option <?php selected($kb_easing, 'Back.easeIn'); ?> value="Back.easeIn">Back.easeIn</option>
                                <option <?php selected($kb_easing, 'Back.easeInOut'); ?> value="Back.easeInOut">Back.easeInOut</option>
                                <option <?php selected($kb_easing, 'Back.easeOut'); ?> value="Back.easeOut">Back.easeOut</option>
                                <option <?php selected($kb_easing, 'Bounce.easeIn'); ?> value="Bounce.easeIn">Bounce.easeIn</option>
                                <option <?php selected($kb_easing, 'Bounce.easeInOut'); ?> value="Bounce.easeInOut">Bounce.easeInOut</option>
                                <option <?php selected($kb_easing, 'Bounce.easeOut'); ?> value="Bounce.easeOut">Bounce.easeOut</option>
                                <option <?php selected($kb_easing, 'Circ.easeIn'); ?> value="Circ.easeIn">Circ.easeIn</option>
                                <option <?php selected($kb_easing, 'Circ.easeInOut'); ?> value="Circ.easeInOut">Circ.easeInOut</option>
                                <option <?php selected($kb_easing, 'Circ.easeOut'); ?> value="Circ.easeOut">Circ.easeOut</option>
                                <option <?php selected($kb_easing, 'Elastic.easeIn'); ?> value="Elastic.easeIn">Elastic.easeIn</option>
                                <option <?php selected($kb_easing, 'Elastic.easeInOut'); ?> value="Elastic.easeInOut">Elastic.easeInOut</option>
                                <option <?php selected($kb_easing, 'Elastic.easeOut'); ?> value="Elastic.easeOut">Elastic.easeOut</option>
                                <option <?php selected($kb_easing, 'Expo.easeIn'); ?> value="Expo.easeIn">Expo.easeIn</option>
                                <option <?php selected($kb_easing, 'Expo.easeInOut'); ?> value="Expo.easeInOut">Expo.easeInOut</option>
                                <option <?php selected($kb_easing, 'Expo.easeOut'); ?> value="Expo.easeOut">Expo.easeOut</option>
                                <option <?php selected($kb_easing, 'Sine.easeIn'); ?> value="Sine.easeIn">Sine.easeIn</option>
                                <option <?php selected($kb_easing, 'Sine.easeInOut'); ?> value="Sine.easeInOut">Sine.easeInOut</option>
                                <option <?php selected($kb_easing, 'Sine.easeOut'); ?> value="Sine.easeOut">Sine.easeOut</option>
                                <option <?php selected($kb_easing, 'SlowMo.ease'); ?> value="SlowMo.ease">SlowMo.ease</option>
                            </select>
                        </p>
                        <p>
                            <label><?php echo $modules->l('Duration (in ms):') ?></label>
                            <input type="text" name="kb_duration" value="<?php echo (int) (RbGlobalObject::getVar('kb_duration')); ?>" />
                        </p>
                    </span>
                </span>

                <input type="hidden" id="image_url" name="image_url" value="<?php echo RbGlobalObject::getVar('imageUrl'); ?>" />
                <input type="hidden" id="image_id" name="image_id" value="<?php echo RbGlobalObject::getVar('imageID'); ?>" />
            </div>

            <div id="slide-general-settings-content" style="display:none">
                <p style="display:none">
<?php $title = RbSliderFunctions::getVal($slideParams, 'title', 'Slide'); ?>
                    <label><?php echo $modules->l("Slide Title"); ?></label>
                    <input type="text" class="medium" id="title" disabled="disabled" name="title" value="<?php echo $title; ?>">
                    <span class="description"><?php echo $modules->l("The title of the slide, will be shown in the slides list."); ?></span>
                </p>

                <p>
<?php $delay = RbSliderFunctions::getVal($slideParams, 'delay', ''); ?>
                    <label><?php echo $modules->l('Slide "Delay":'); ?></label>
                    <input type="text" class="small-text" id="delay" name="delay" value="<?php echo $delay; ?>">
                    <span class="description"><?php echo $modules->l("A new delay value for the Slide. If no delay defined per slide, the delay defined via Options (9000ms) will be used."); ?></span>
                </p>

                <p>
<?php $state = RbSliderFunctions::getVal($slideParams, 'state', 'published'); ?>
                    <label><?php echo $modules->l("Slide State"); ?></label>
                    <select id="state" name="state">
                        <option value="published"<?php selected($state, 'published'); ?>><?php echo $modules->l("Published"); ?></option>
                        <option value="unpublished"<?php selected($state, 'unpublished'); ?>><?php echo $modules->l("Unpublished"); ?></option>
                    </select>
                    <span class="description"><?php echo $modules->l("The state of the slide. The unpublished slide will be excluded from the slider."); ?></span>
                </p>

                <p>
                    <?php $date_from = RbSliderFunctions::getVal($slideParams, 'date_from', ''); ?>
                    <label><?php echo $modules->l("Visible from:"); ?></label>
                    <input type="text" class="inputDatePicker" id="date_from" name="date_from" value="<?php echo $date_from; ?>">
                    <span class="description"><?php echo $modules->l("If set, slide will be visible after the date is reached."); ?></span>
                </p>

                <p>
                    <?php $date_to = RbSliderFunctions::getVal($slideParams, 'date_to', ''); ?>
                    <label><?php echo $modules->l("Visible until:"); ?></label>
                    <input type="text" class="inputDatePicker" id="date_to" name="date_to" value="<?php echo $date_to; ?>">
                    <span class="description"><?php echo $modules->l("If set, slide will be visible till the date is reached."); ?></span>
                </p>

                <p style="display:none">
<?php $save_performance = RbSliderFunctions::getVal($slideParams, 'save_performance', 'off'); ?>
                    <label><?php echo $modules->l("Save Performance:"); ?></label>
                    <span style="display:inline-block; width:200px; margin-right:20px;">
                        <input type="checkbox" class="tp-moderncheckbox withlabel" id="save_performance" name="save_performance" data-unchecked="off" <?php checked($save_performance, "on"); ?>>
                    </span>
                    <span class="description"><?php echo $modules->l("Slide End Transition will first start when last Layer has been removed."); ?></span>
                </p>

            </div>

            <div id="slide-thumbnail-settings-content" style="display:none">

                <div style="margin-top:10px">
<?php $slide_thumb = RbSliderFunctions::getVal($slideParams, 'slide_thumb', ''); ?>
                    <span style="display:inline-block; vertical-align: top;">
                        <label><?php echo $modules->l("Thumbnail:"); ?></label>
                    </span>
                    <div style="display:inline-block; vertical-align: top;">
                        <div id="slide_thumb_button_preview" class="setting-image-preview"><?php
if ((int) ($slide_thumb) > 0) {

    ?>
                                <div style="width:100px;height:70px;background:url('<?php echo adminUrl('admin-ajax.php');

    ?>?action=rbslider_show_image&amp;img=<?php echo $slide_thumb;

    ?>&amp;w=100&amp;h=70&amp;t=exact'); background-position:center center; background-size:cover;"></div>
    <?php
} elseif ($slide_thumb !== '') {

    ?>
                                <div style="width:100px;height:70px;background:url('<?php echo $slide_thumb;

    ?>'); background-position:center center; background-size:cover;"></div>
    <?php
}

?></div>
                        <input type="hidden" id="slide_thumb" name="slide_thumb" value="<?php echo $slide_thumb; ?>">
                        <span style="clear:both;display:block"></span>
                        <input type="button" id="slide_thumb_button" style="width:110px !important; display:inline-block;" class="button-image-select button-primary rbblue" value="Choose Image" original-title="">
                        <input type="button" id="slide_thumb_button_remove" style="margin-right:20px !important; width:85px !important; display:inline-block;" class="button-image-remove button-primary rbred"  value="Remove" original-title="">
                        <span class="description"><?php echo $modules->l("Slide Thumbnail. If not set - it will be taken from the slide image."); ?></span>
                    </div>
                </div>
<?php $thumb_dimension = RbSliderFunctions::getVal($slideParams, 'thumb_dimension', 'slider'); ?>
<?php $thumb_for_admin = RbSliderFunctions::getVal($slideParams, 'thumb_for_admin', 'off'); ?>

                <p>
                    <span style="display:inline-block; vertical-align: top;">
                        <label><?php echo $modules->l("Thumbnail Dimensions:"); ?></label>
                    </span>
                    <select name="thumb_dimension">
                        <option value="slider" <?php selected($thumb_dimension, 'slider'); ?>><?php echo $modules->l('From Slider Settings'); ?></option>
                        <option value="orig" <?php selected($thumb_dimension, 'orig'); ?>><?php echo $modules->l('Original Size'); ?></option>
                    </select>
                    <span class="description"><?php echo $modules->l("Width and height of thumbnails can be changed in the Slider Settings -> Navigation -> Thumbs tab."); ?></span>
                </p>

                <p style="display:none;" class="show_on_thumbnail_exist">
                    <span style="display:inline-block; vertical-align: top;">
                        <label><?php echo $modules->l("Thumbnail Admin Purpose:"); ?></label>
                    </span>
                    <span style="display:inline-block; width:200px; margin-right:20px;line-height:27px">
                        <input type="checkbox" class="tp-moderncheckbox" id="thumb_for_admin" name="thumb_for_admin" data-unchecked="off" <?php checked($thumb_for_admin, 'on'); ?>>
                    </span>
                    <span class="description"><?php echo $modules->l("Use the Thumbnail also for Admin purposes. This will use the selected Thumbnail to represent the Slide in all Slider Admin area."); ?></span>
                </p>
            </div>

            <div id="slide-animation-settings-content" style="display:none">
                <div id="slide_transition_row">
                        <?php
                        $slide_transition = RbSliderFunctions::getVal($slideParams, 'slide_transition', RbGlobalObject::getVar('def_transition'));
                        if (!is_array($slide_transition)) {
                            $slide_transition = explode(',', $slide_transition);
                        }

                        if (!is_array($slide_transition)) {
                            $slide_transition = array($slide_transition);
                        }

                        $transitions = RbGlobalObject::getVar('operations')->getArrTransition();

                        ?>
                        <?php $slot_amount = (array) RbSliderFunctions::getVal($slideParams, 'slot_amount', 'default'); ?>
                        <?php $transition_rotation = (array) RbSliderFunctions::getVal($slideParams, 'transition_rotation', '0'); ?>
                        <?php $transition_duration = (array) RbSliderFunctions::getVal($slideParams, 'transition_duration', RbGlobalObject::getVar('def_transition_duration')); ?>
                        <?php $transition_ease_in = (array) RbSliderFunctions::getVal($slideParams, 'transition_ease_in', 'default'); ?>
                        <?php $transition_ease_out = (array) RbSliderFunctions::getVal($slideParams, 'transition_ease_out', 'default'); ?>
                    <script type="text/javascript">
                        var choosen_slide_transition = [];
                        <?php
                        $tr_count = count($slide_transition);
                        foreach ($slide_transition as $tr) {
                            echo 'choosen_slide_transition.push("' . $tr . '");' . "\n";
                        }

                        ?>
                        var transition_settings = {
                            'slot': [],
                            'rotation': [],
                            'duration': [],
                            'ease_in': [],
                            'ease_out': []
                        };
<?php
foreach ($slot_amount as $sa) {
    echo 'transition_settings["slot"].push("' . $sa . '");' . "\n";
}
$sac = count($slot_amount);
if ($sac < $tr_count) {
    while ($sac < $tr_count) {
        $sac++;
        echo 'transition_settings["slot"].push("' . $slot_amount[0] . '");' . "\n";
    }
}

foreach ($transition_rotation as $sa) {
    echo 'transition_settings["rotation"].push("' . $sa . '");' . "\n";
}
$sac = count($transition_rotation);
if ($sac < $tr_count) {
    while ($sac < $tr_count) {
        $sac++;
        echo 'transition_settings["rotation"].push("' . $transition_rotation[0] . '");' . "\n";
    }
}

foreach ($transition_duration as $sa) {
    echo 'transition_settings["duration"].push("' . $sa . '");' . "\n";
}
$sac = count($transition_duration);
if ($sac < $tr_count) {
    while ($sac < $tr_count) {
        $sac++;
        echo 'transition_settings["duration"].push("' . $transition_duration[0] . '");' . "\n";
    }
}

foreach ($transition_ease_in as $sa) {
    echo 'transition_settings["ease_in"].push("' . $sa . '");' . "\n";
}
$sac = count($transition_ease_in);
if ($sac < $tr_count) {
    while ($sac < $tr_count) {
        $sac++;
        echo 'transition_settings["ease_in"].push("' . $transition_ease_in[0] . '");' . "\n";
    }
}

foreach ($transition_ease_out as $sa) {
    echo 'transition_settings["ease_out"].push("' . $sa . '");' . "\n";
}
$sac = count($transition_ease_out);
if ($sac < $tr_count) {
    while ($sac < $tr_count) {
        $sac++;
        echo 'transition_settings["ease_out"].push("' . $transition_ease_out[0] . '");' . "\n";
    }
}

?>
                    </script>
                    <div id="slide_transition"  multiple="" size="1" style="z-index: 100;">
<?php
if (!empty($transitions) && is_array($transitions)) {
    $counter = 0;
    $optgroupexist = false;
    $transmenu = '<ul class="slide-trans-menu">';
    $lastclass = '';
    $transchecks = '';
    $listoftrans = '<div class="slide-trans-lists">';

    foreach ($transitions as $tran_handle => $tran_name) {
        $sel = (in_array($tran_handle, $slide_transition)) ? ' checked="checked"' : '';

        if (strpos($tran_handle, 'notselectable') !== false) {
            $listoftrans = $listoftrans . $transchecks;
            $lastclass = "slide-trans-" . $tran_handle;
            $transmenu = $transmenu . '<li class="slide-trans-menu-element" data-reference="' . $lastclass . '">' . $tran_name . '</li>';
            $transchecks = '';
        } else {
            $transchecks = $transchecks . '<div class="slide-trans-checkelement ' . $lastclass . '"><input name="slide_transition[]" type="checkbox" data-useval="true" value="' . $tran_handle . '"' . $sel . '>' . $tran_name . '</div>';
        }
    }

    $listoftrans = $listoftrans . $transchecks;
    $transmenu = $transmenu . "</ul>";
    $listoftrans = $listoftrans . "</div>";
    echo $transmenu;
    echo $listoftrans;
}

?>

                        <div class="slide-trans-example">
                            <div class="slide-trans-example-inner">
                                <div class="oldslotholder" style="overflow:hidden;width:100%;height:100%;position:absolute;top:0px;left:0px;z-index:1">
                                    <div class="tp-bgimg defaultimg"></div>
                                </div>
                                <div class="slotholder" style="overflow:hidden;width:100%;height:100%;position:absolute;top:0px;left:0px;z-index:1">
                                    <div class="tp-bgimg defaultimg"></div>
                                </div>
                            </div>
                        </div>
                        <div class="slide-trans-cur-selected">
                            <p><?php echo $modules->l("Used Transitions (Order in Loops)"); ?></p>
                            <ul class="slide-trans-cur-ul">
                            </ul>
                        </div>
                        <div class="slide-trans-cur-selected-settings">
                            <!-- SLOT AMOUNT -->

                            <label><?php echo $modules->l("Slot / Box Amount:"); ?></label>
                            <input type="text" class="small-text input-deepselects" id="slot_amount" name="slot_amount" value="<?php echo $slot_amount[0]; ?>" data-selects="1||Random||Custom||Default" data-svalues ="1||random||3||default" data-icons="thumbs-up||shuffle||wrench||key">
                            <span class="tp-clearfix"></span>
                            <span class="description"><?php echo $modules->l("Of slots/boxes the slide is divided into."); ?></span>					
                            <span class="tp-clearfix"></span>

                            <!-- ROTATION -->

                            <label><?php echo $modules->l("Slot Rotation:"); ?></label>
                            <input type="text" class="small-text input-deepselects" id="transition_rotation" name="transition_rotation" value="<?php echo $transition_rotation[0]; ?>" data-selects="0||Random||Custom||Default||45||90||180||270||360" data-svalues ="0||random||-75||default||45||90||180||270||360" data-icons="thumbs-up||shuffle||wrench||key||star-empty||star-empty||star-empty||star-empty||star-empty">
                            <span class="tp-clearfix"></span>
                            <span class="description"><?php echo $modules->l("Start Rotation of Transition (deg)."); ?></span>
                            <span class="tp-clearfix"></span>

                            <!-- DURATION -->

                            <label><?php echo $modules->l("Animation Duration:"); ?></label>
                            <input type="text" class="small-text input-deepselects" id="transition_duration" name="transition_duration" value="<?php echo $transition_duration[0]; ?>" data-selects="300||Random||Custom||Default" data-svalues ="500||random||650||default" data-icons="thumbs-up||shuffle||wrench||key">
                            <span class="tp-clearfix"></span>
                            <span class="description"><?php echo $modules->l("The duration of the transition."); ?></span>
                            <span class="tp-clearfix"></span>

                            <!-- IN EASE -->

                            <label><?php echo $modules->l("Easing In:"); ?></label>
                            <select name="transition_ease_in">
                                <option value="default">Default</option>
                                <option value="Linear.easeNone">Linear.easeNone</option>
                                <option value="Power0.easeIn">Power0.easeIn  (linear)</option>
                                <option value="Power0.easeInOut">Power0.easeInOut  (linear)</option>
                                <option value="Power0.easeOut">Power0.easeOut  (linear)</option>
                                <option value="Power1.easeIn">Power1.easeIn</option>
                                <option value="Power1.easeInOut">Power1.easeInOut</option>
                                <option value="Power1.easeOut">Power1.easeOut</option>
                                <option value="Power2.easeIn">Power2.easeIn</option>
                                <option value="Power2.easeInOut">Power2.easeInOut</option>
                                <option value="Power2.easeOut">Power2.easeOut</option>
                                <option value="Power3.easeIn">Power3.easeIn</option>
                                <option value="Power3.easeInOut">Power3.easeInOut</option>
                                <option value="Power3.easeOut">Power3.easeOut</option>
                                <option value="Power4.easeIn">Power4.easeIn</option>
                                <option value="Power4.easeInOut">Power4.easeInOut</option>
                                <option value="Power4.easeOut">Power4.easeOut</option>
                                <option value="Back.easeIn">Back.easeIn</option>
                                <option value="Back.easeInOut">Back.easeInOut</option>
                                <option value="Back.easeOut">Back.easeOut</option>
                                <option value="Bounce.easeIn">Bounce.easeIn</option>
                                <option value="Bounce.easeInOut">Bounce.easeInOut</option>
                                <option value="Bounce.easeOut">Bounce.easeOut</option>
                                <option value="Circ.easeIn">Circ.easeIn</option>
                                <option value="Circ.easeInOut">Circ.easeInOut</option>
                                <option value="Circ.easeOut">Circ.easeOut</option>
                                <option value="Elastic.easeIn">Elastic.easeIn</option>
                                <option value="Elastic.easeInOut">Elastic.easeInOut</option>
                                <option value="Elastic.easeOut">Elastic.easeOut</option>
                                <option value="Expo.easeIn">Expo.easeIn</option>
                                <option value="Expo.easeInOut">Expo.easeInOut</option>
                                <option value="Expo.easeOut">Expo.easeOut</option>
                                <option value="Sine.easeIn">Sine.easeIn</option>
                                <option value="Sine.easeInOut">Sine.easeInOut</option>
                                <option value="Sine.easeOut">Sine.easeOut</option>
                                <option value="SlowMo.ease">SlowMo.ease</option>
                            </select>
                            <span class="tp-clearfix"></span>
                            <span class="description"><?php echo $modules->l("The easing of Appearing transition."); ?></span>
                            <span class="tp-clearfix"></span>

                            <!-- OUT EASE -->

                            <label><?php echo $modules->l("Easing Out:"); ?></label>
                            <select name="transition_ease_out">
                                <option value="default">Default</option>
                                <option value="Linear.easeNone">Linear.easeNone</option>
                                <option value="Power0.easeIn">Power0.easeIn  (linear)</option>
                                <option value="Power0.easeInOut">Power0.easeInOut  (linear)</option>
                                <option value="Power0.easeOut">Power0.easeOut  (linear)</option>
                                <option value="Power1.easeIn">Power1.easeIn</option>
                                <option value="Power1.easeInOut">Power1.easeInOut</option>
                                <option value="Power1.easeOut">Power1.easeOut</option>
                                <option value="Power2.easeIn">Power2.easeIn</option>
                                <option value="Power2.easeInOut">Power2.easeInOut</option>
                                <option value="Power2.easeOut">Power2.easeOut</option>
                                <option value="Power3.easeIn">Power3.easeIn</option>
                                <option value="Power3.easeInOut">Power3.easeInOut</option>
                                <option value="Power3.easeOut">Power3.easeOut</option>
                                <option value="Power4.easeIn">Power4.easeIn</option>
                                <option value="Power4.easeInOut">Power4.easeInOut</option>
                                <option value="Power4.easeOut">Power4.easeOut</option>
                                <option value="Back.easeIn">Back.easeIn</option>
                                <option value="Back.easeInOut">Back.easeInOut</option>
                                <option value="Back.easeOut">Back.easeOut</option>
                                <option value="Bounce.easeIn">Bounce.easeIn</option>
                                <option value="Bounce.easeInOut">Bounce.easeInOut</option>
                                <option value="Bounce.easeOut">Bounce.easeOut</option>
                                <option value="Circ.easeIn">Circ.easeIn</option>
                                <option value="Circ.easeInOut">Circ.easeInOut</option>
                                <option value="Circ.easeOut">Circ.easeOut</option>
                                <option value="Elastic.easeIn">Elastic.easeIn</option>
                                <option value="Elastic.easeInOut">Elastic.easeInOut</option>
                                <option value="Elastic.easeOut">Elastic.easeOut</option>
                                <option value="Expo.easeIn">Expo.easeIn</option>
                                <option value="Expo.easeInOut">Expo.easeInOut</option>
                                <option value="Expo.easeOut">Expo.easeOut</option>
                                <option value="Sine.easeIn">Sine.easeIn</option>
                                <option value="Sine.easeInOut">Sine.easeInOut</option>
                                <option value="Sine.easeOut">Sine.easeOut</option>
                                <option value="SlowMo.ease">SlowMo.ease</option>
                            </select>
                            <span class="tp-clearfix"></span>
                            <span class="description"><?php echo $modules->l("The easing of Disappearing transition."); ?></span>

                        </div>

                    </div>

                </div>


            </div>

            <!-- SLIDE BASIC INFORMATION -->
            <div id="slide-info-settings-content" style="display:none">
                <p>
<?php
for ($i = 1; $i <= 10; $i++) {

    ?>
                    <p>
                            <?php
                            $modules->l('Parameter');
                            echo ' ' . $i;

                            ?> <input type="text" name="params_<?php echo $i;

                            ?>" value="<?php echo Tools::stripslashes(RbSliderFunctions::getVal($slideParams, 'params_' . $i, ''));

                            ?>">
                            <?php echo $modules->l('Max. Chars');

                            ?> <input type="text" style="width: 50px; min-width: 50px;" name="params_<?php echo $i;

                            ?>_chars" value="<?php echo RbSliderFunctions::getVal($slideParams, 'params_' . $i . '_chars', 10, RbSlider::FORCE_NUMERIC);

                            ?>">
                            <?php if ($slider_type !== 'gallery') {

                                ?><i class="eg-icon-pencil rs-param-meta-open" data-curid="<?php echo $i;

                                ?>"></i><?php
                            }

                            ?>
                    </p>
                                <?php
                            }

                            ?>
                </p>
                <!-- BASIC DESCRIPTION -->
                <p>
<?php $slide_description = Tools::stripslashes(RbSliderFunctions::getVal($slideParams, 'slide_description', '')); ?>
                    <label><?php echo $modules->l("Description of Slider:"); ?></label>

                    <textarea name="slide_description" style="height: 425px; width: 100%"><?php echo $slide_description; ?></textarea>
                    <span class="description"><?php echo $modules->l('Define a description here to show at the navigation if enabled in Slider Settings'); ?></span>
                </p>
            </div>

            <!-- SLIDE SEO INFORMATION -->
            <div id="slide-seo-settings-content" style="display:none">
                <!-- CLASS -->
                <p>
<?php $class_attr = RbSliderFunctions::getVal($slideParams, 'class_attr', ''); ?>
                    <label><?php echo $modules->l("Class:"); ?></label>
                    <input type="text" class="" id="class_attr" name="class_attr" value="<?php echo $class_attr; ?>">
                    <span class="description"><?php echo $modules->l('Adds a unique class to the li of the Slide like class="rb_special_class" (add only the classnames, seperated by space)'); ?></span>
                </p>

                <!-- ID -->
                <p>
<?php $id_attr = RbSliderFunctions::getVal($slideParams, 'id_attr', ''); ?>
                    <label><?php echo $modules->l("ID:"); ?></label>
                    <input type="text" class="" id="id_attr" name="id_attr" value="<?php echo $id_attr; ?>">
                    <span class="description"><?php echo $modules->l('Adds a unique ID to the li of the Slide like id="rb_special_id" (add only the id)'); ?></span>
                </p>

                <!-- CUSTOM FIELDS -->
                <p>
<?php $data_attr = Tools::stripslashes(RbSliderFunctions::getVal($slideParams, 'data_attr', '')); ?>
                    <label><?php echo $modules->l("Custom Fields:"); ?></label>
                    <textarea id="data_attr" name="data_attr"><?php echo $data_attr; ?></textarea>
                    <span class="description"><?php echo $modules->l('Add as many attributes as you wish here. (i.e.: data-layer="firstlayer" data-custom="somevalue").'); ?></span>
                </p>

                <!-- Enable Link -->
                <p>
<?php $enable_link = RbSliderFunctions::getVal($slideParams, 'enable_link', 'false'); ?>
                    <label><?php echo $modules->l("Enable Link:"); ?></label>
                    <select id="enable_link" name="enable_link">
                        <option value="true"<?php selected($enable_link, 'true'); ?>><?php echo $modules->l("Enable"); ?></option>
                        <option value="false"<?php selected($enable_link, 'false'); ?>><?php echo $modules->l("Disable"); ?></option>
                    </select>
                    <span class="description"><?php echo $modules->l('Link the Full Slide to an URL or Action.'); ?></span>
                </p>

                <div class="rs-slide-link-setting-wrapper">
                    <!-- Link Type -->
                    <p>
<?php $enable_link = RbSliderFunctions::getVal($slideParams, 'link_type', 'regular'); ?>
                        <label><?php echo $modules->l("Link Type:"); ?></label>
                        <span style="display:inline-block; width:200px; margin-right:20px;">
                            <input type="radio" id="link_type_1" value="regular" name="link_type"<?php checked($enable_link, 'regular'); ?>><span style="line-height:30px; vertical-align: middle; margin:0px 20px 0px 10px;"><?php echo $modules->l('Regular'); ?></span>
                            <input type="radio" id="link_type_2" value="slide" name="link_type"<?php checked($enable_link, 'slide'); ?>><span style="line-height:30px; vertical-align: middle; margin:0px 20px 0px 10px;"><?php echo $modules->l('To Slide'); ?></span>
                        </span>
                        <span class="description"><?php echo $modules->l('Regular - Link to URL,  To Slide - Call a Slide Action'); ?></span>
                    </p>

                    <div class="rs-regular-link-setting-wrap">
                        <!-- SLIDE LINK -->
                        <p>
<?php $val_link = RbSliderFunctions::getVal($slideParams, 'link', ''); ?>
                            <label><?php echo $modules->l("Slide Link:"); ?></label>
                            <input type="text" id="rb_link" name="link" value="<?php echo $val_link; ?>">
                            <span class="description"><?php echo $modules->l('A link on the whole slide pic (use {{link}} or {{meta:somemegatag}} in template sliders to link to a post or some other meta)'); ?></span>
                        </p>

                        <!-- LINK TARGET -->
                        <p>
<?php $link_open_in = RbSliderFunctions::getVal($slideParams, 'link_open_in', 'same'); ?>
                            <label><?php echo $modules->l("Link Target:"); ?></label>
                            <select id="link_open_in" name="link_open_in">
                                <option value="same"<?php selected($link_open_in, 'same'); ?>><?php echo $modules->l('Same Window'); ?></option>
                                <option value="new"<?php selected($link_open_in, 'new'); ?>><?php echo $modules->l('New Window'); ?></option>
                            </select>
                            <span class="description"><?php echo $modules->l('The target of the slide link.'); ?></span>
                        </p>
                    </div>
                    <!-- LINK TO SLIDE -->
                    <p class="rs-slide-to-slide">
<?php
$slide_link = RbSliderFunctions::getVal($slideParams, 'slide_link', 'nothing');
//num_slide_link
$arrSlideLink = array();
$arrSlideLink["nothing"] = $modules->l("-- Not Chosen --");
$arrSlideLink["next"] = $modules->l("-- Next Slide --");
$arrSlideLink["prev"] = $modules->l("-- Previous Slide --");

$arrSlideLinkLayers = $arrSlideLink;
$arrSlideLinkLayers["scroll_under"] = $modules->l("-- Scroll Below Slider --");
$arrSlideNames = array();
$slider = RbGlobalObject::getVar('slider');
if (@Rbthemeslider::getIsset($slider) && $slider->isInited()) {
    $arrSlideNames = $slider->getArrSlideNames();
}
if (!empty($arrSlideNames) && is_array($arrSlideNames)) {
    foreach ($arrSlideNames as $slideNameID => $arr) {
        $slideName = $arr["title"];
        $arrSlideLink[$slideNameID] = $slideName;
        $arrSlideLinkLayers[$slideNameID] = $slideName;
    }
}

?>
                        <label><?php echo $modules->l("Link To Slide:"); ?></label>
                        <select id="slide_link" name="slide_link">
<?php
if (!empty($arrSlideLinkLayers) && is_array($arrSlideLinkLayers)) {
    foreach ($arrSlideLinkLayers as $link_handle => $link_name) {
        $sel = ($link_handle == $slide_link) ? ' selected="selected"' : '';
        echo '<option value="' . $link_handle . '"' . $sel . '>' . $link_name . '</option>';
    }
}

?>
                        </select>
                        <span class="description"><?php echo $modules->l('Call Slide Action'); ?></span>
                    </p>
                    <!-- Link POSITION -->
                    <p>
<?php $link_pos = RbSliderFunctions::getVal($slideParams, 'link_pos', 'front'); ?>
                        <label><?php echo $modules->l("Link Sensibility:"); ?></label>
                        <span style="display:inline-block; width:200px; margin-right:20px;">
                            <input type="radio" id="link_pos_1" value="front" name="link_pos"<?php checked($link_pos, 'front'); ?>><span style="line-height:30px; vertical-align: middle; margin:0px 20px 0px 10px;"><?php echo $modules->l('Front'); ?></span>
                            <input type="radio" id="link_pos_2" value="back" name="link_pos"<?php checked($link_pos, 'back'); ?>><span style="line-height:30px; vertical-align: middle; margin:0px 20px 0px 10px;"><?php echo $modules->l('Back'); ?></span>
                        </span>
                        <span class="description"><?php echo $modules->l('The z-index position of the link related to layers'); ?></span>
                    </p>
                </div>
            </div>

        </form>

    </div>
</div>
<script type="text/javascript">
    var rs_plugin_url = '<?php echo addslashes(_MODULE_DIR_ . "rbthemeslider/"); ?>';

    jQuery('document').ready(function() {

        jQuery('#enable_link').change(function() {
            if (jQuery(this).val() == 'true') {
                jQuery('.rs-slide-link-setting-wrapper').show();
            } else {
                jQuery('.rs-slide-link-setting-wrapper').hide();
            }
        });
        jQuery('#enable_link option:selected').change();

        jQuery('input[name="link_type"]').change(function() {
            if (jQuery(this).val() == 'regular') {
                jQuery('.rs-regular-link-setting-wrap').show();
                jQuery('.rs-slide-to-slide').hide();
            } else {
                jQuery('.rs-regular-link-setting-wrap').hide();
                jQuery('.rs-slide-to-slide').show();
            }
        });
        jQuery('input[name="link_type"]:checked').change();

    });
</script>

<?php
