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

$modules = new Rbthemeslider();

?>

<div id="dialog_video" class="dialog-video" title="<?php echo $modules->l('Add Video Layout'); ?>" style="display:none">
    <div id="video_type_chooser" class="video-type-chooser">
        <div class="choose-video-type" style="float:left">
            <?php echo $modules->l('Choose Video Type'); ?>
        </div>

        <div style="float:left; height:35px;line-height:15px;margin-top:20px;margin-left:30px">
            <label for="video_radio_youtube"><?php echo $modules->l('Youtube'); ?></label>
            <input type="radio" checked id="video_radio_youtube" name="video_select">
            <label for="video_radio_vimeo"><?php echo $modules->l('Vimeo'); ?></label>
            <input type="radio" id="video_radio_vimeo" name="video_select">
            <label for="video_radio_html5"><?php echo $modules->l('HTML5'); ?></label>
            <input type="radio" id="video_radio_html5" name="video_select">
        </div>

        <div style="clear:both"></div>
        <hr>
        <div style="width:100%;height:15px;"></div>
    </div>

    <div class="video_left" id="video-dialog-wrap">
        <div id="video_block_vimeo" class="video-select-block" style="display:none;" >
            <div class="video-title" >
                <?php echo $modules->l('Vimeo ID URL'); ?>
            </div>

            <input type="text" id="vimeo_id" value=""></input>

            &nbsp;

            <input type="button" id="button_vimeo_search" class="button-regular" value="search">

            <img id="vimeo_loader" src="<?php echo RbSliderAdmin::$url_plugin ?>views/img/images/loader.gif" style="display:none">

            <div class="video_example">

                <?php echo $modules->l('Example:  30300114'); ?>

            </div>

        </div>

        <div id="video_block_youtube" class="video-select-block">
            <div class="video-title">
                <?php echo $modules->l('Youtube ID URL'); ?>:
            </div>

            <input type="text" id="youtube_id" value=""></input>

            &nbsp;

            <input type="button" id="button_youtube_search" class="button-regular" value="search">

            <img id="youtube_loader" src="<?php echo RbSliderAdmin::$url_plugin ?>views/img/images/loader.gif" style="display:none">

            <div class="video_example">

                <?php echo $modules->l('Example'); ?>:  <?php echo GlobalsRbSlider::YOUTUBE_EXAMPLE_ID ?>

            </div>

        </div>

        <!-- Html 5 block -->

        <div id="video_block_html5" class="video-select-block" style="display:none;">

            <ul>

                <li style="width:45%;float:left;">
                    <div class="video-title">
                        <?php echo $modules->l('Poster Image Url'); ?>:
                    </div>

                    <input type="text" id="html5_url_poster" value=""></input>

                    <span class="video_example"><?php echo $modules->l('Example'); ?>: http://video-js.zencoder.com/oceans-clip.png</span>

                </li>

                <li style="width:45%;float:left;margin-left:4%;">

                    <div class="video-title">

                        <?php echo $modules->l('Video MP4 Url'); ?>:

                    </div>

                    <input type="text" id="html5_url_mp4" value=""></input>

                    <span class="video_example"><?php echo $modules->l('Example'); ?>: http://video-js.zencoder.com/oceans-clip.mp4</span>

                </li>

                <li style="clear:both;width:45%;float:left;margin-top:20px;">

                    <div class="video-title">								

                        <?php echo $modules->l('Video WEBM Url'); ?>:

                    </div>

                    <input type="text" id="html5_url_webm" value=""></input>

                    <span class="video_example"><?php echo $modules->l('Example'); ?>: http://video-js.zencoder.com/oceans-clip.webm</span>					

                </li>

                <li style="width:45%;float:left;margin-left:4%;margin-top:20px;">

                    <div class="video-title">

                        <?php echo $modules->l('Video OGV Url'); ?>:

                    </div>

                    <input type="text" id="html5_url_ogv" value=""></input>

                    <span class="video_example"><?php echo $modules->l('Example'); ?>: http://video-js.zencoder.com/oceans-clip.ogv</span>	

                </li>

            </ul>

        </div>

        <div style="clear:both"></div>

        <!-- Video controls -->

        <div id="video_hidden_controls" style="display:none; margin-top:20px;">

            <hr>

            <div class="video-title">

                <?php echo $modules->l('Video Size'); ?>:

            </div>

            <div id="video_size_wrapper" class="youtube-inputs-wrapper">

                <label for="input_video_fullwidth" class=" float_left mtop_10">

                    <?php echo $modules->l('Full Width'); ?>

                </label>

                <input type="checkbox" class="checkbox_video_dialog float_left mtop_13 " id="input_video_fullwidth" ></input>

                <div class="float_left mleft_20 mtop_10" id="input_video_width_lbl"><?php echo $modules->l('Width'); ?>:</div>

                <input type="text" id="input_video_width" style="margin-top:5px;" class="video-input-small float_left" value="320">

                <div class="float_left mleft_20 mtop_10" id="input_video_height_lbl"><?php echo $modules->l('Height'); ?>:</div>

                <input type="text" id="input_video_height" style="margin-top:5px;" class="video-input-small float_left" value="240">

                <div style="clear:both"></div>

                <div id="fullscreenvideofun" class="mtop_20">

                    <label for="input_video_cover" class="float_left">

                        <?php echo $modules->l('Cover'); ?>

                    </label>

                    <input type="checkbox" class="checkbox_video_dialog float_left " id="input_video_cover" ></input>

                    <label for="input_video_dotted_overlay" class="float_left mleft_20" id="input_video_dotted_overlay_lbl">

                        <?php echo $modules->l('Dotted Overlay'); ?>
                    </label>

                    <select id="input_video_dotted_overlay" style="float: left; margin-top:-5px">

                        <option value="none"><?php echo $modules->l('none'); ?></option>

                        <option value="twoxtwo"><?php echo $modules->l('2 x 2 Black'); ?></option>

                        <option value="twoxtwowhite"><?php echo $modules->l('2 x 2 White'); ?></option>

                        <option value="threexthree"><?php echo $modules->l('3 x 3 Black'); ?></option>

                        <option value="threexthreewhite"><?php echo $modules->l('3 x 3 White'); ?></option>

                    </select>

                    <label for="input_video_ratio" class="float_left mleft_20" id="input_video_ratio_lbl">

                        <?php echo $modules->l('Aspect_Ratio'); ?>
                    </label>				

                    <select id="input_video_ratio" style="margin-top:-10px">
                        <option value="16:9">16:9</option>
                        <option value="4:3">4:3</option>
                    </select>

                </div>

                <div style="clear:both"></div>

            </div>

            <div class="mtop_20">

                <hr>

                <div class="video-title">

                    <?php echo $modules->l('Video Settings'); ?>:

                </div>

                <label for="input_video_loop" class=" float_left mtop_10">

                    <?php echo $modules->l('Loop Video'); ?>

                </label>

                <input type="checkbox" class="checkbox_video_dialog float_left mtop_13" id="input_video_loop" ></input>

                <label for="input_video_autoplay" class="float_left mtop_10 mleft_20">
                    <?php echo $modules->l('Autoplay'); ?>
                </label>

                <input type="checkbox" class="checkbox_video_dialog float_left mtop_13" id="input_video_autoplay" ></input>

                <div id="showautoplayfirsttime" class="float_left">

                    <label for="input_video_autoplay_first_time" class="float_left mtop_10 mleft_20">
                        <?php echo $modules->l('Only 1st Time'); ?>
                    </label>

                    <input type="checkbox" class="checkbox_video_dialog float_left mtop_13" id="input_video_autoplay_first_time" ></input>

                </div>

                <div style="clear:both"></div>	

                <label for="input_video_nextslide" class="float_left mtop_10">
                    <?php echo $modules->l('Next Slide End'); ?>
                </label>

                <input type="checkbox" class="checkbox_video_dialog float_left mtop_13" id="input_video_nextslide" ></input>

                <label for="input_video_force_rewind" class="float_left mtop_10 mleft_20">

                    <?php echo $modules->l('Force Rewind'); ?>

                </label>

                <input type="checkbox" class="checkbox_video_dialog float_left mtop_13" id="input_video_force_rewind" ></input>

                <div style="clear:both"></div>	

                <label for="input_video_control" class="float_left mtop_10">

                    <?php echo $modules->l('Hide Controls'); ?>

                </label>

                <input type="checkbox" class="checkbox_video_dialog float_left mtop_13" id="input_video_control" ></input>

                <label for="input_video_mute" class="float_left mleft_20 mtop_10">
                    <?php echo $modules->l('Mute'); ?>
                </label>

                <input type="checkbox" class="checkbox_video_dialog float_left mtop_13" id="input_video_mute" ></input>

                <div class="clear"></div>

            </div>

            <div class="video-title mtop_20" id="preview-image-video-wrap">
                <hr>
                <div class="video-title">
                    <?php echo $modules->l('Preview Image'); ?>:
                </div>

                <input type="button" id="" class="button-image-select-video button-primary revblue" value="<?php echo $modules->l('Set'); ?>">

                <input type="button" id="" class="button-image-remove-video button-primary revblue" value="<?php echo $modules->l('Remove'); ?>">

                <input type="hidden" class="checkbox_video_dialog float_left" id="input_video_preview">

                <div class="clear"></div>

                <div style="width:100%;height:10px"></div>

            </div>

            <div class="clear"></div>

            <hr>

            <div class="video-title mtop_20">

                <?php echo $modules->l('Arguments'); ?>

            </div>

            <input type="text" id="input_video_arguments" style="width:100%;" value="" data-youtube="<?php echo GlobalsRbSlider::DEFAULT_YOUTUBE_ARGUMENTS ?>" data-vimeo="<?php echo GlobalsRbSlider::DEFAULT_VIMEO_ARGUMENTS ?>" ></input>

            <div class="mtop_20">

            </div>

            <div class="clear"></div>

            <div class="add-button-wrapper">
                <a 
                    href="javascript:void(0)"
                    class="button-primary rbblue"
                    id="button-video-add"
                    data-textadd="<?php echo $modules->l('Add This Video'); ?>"
                    data-textupdate="<?php echo $modules->l('Update_Video'); ?>"
                >
                    <?php echo $modules->l('Add This Video'); ?>    
                </a>
            </div>
        </div>
    </div>
    <div id="video_content" class="video_right" style="display:none"></div>		

</div>

<?php
