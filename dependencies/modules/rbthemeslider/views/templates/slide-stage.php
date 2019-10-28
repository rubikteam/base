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

$operations = RbGlobalObject::getVar('operations');
$startanims = $operations->getArrAnimations();
$slider = RbGlobalObject::getVar('slider');
$modules = new Rbthemeslider();

?>

<div style="width:100%;height:20px"></div>
<div class="editor_buttons_wrapper  postbox unite-postbox" style="max-width:100% !important; min-width:1140px !important">
    <div class="box-closed tp-accordion" style="border-bottom:5px solid #ddd;">
        <ul class="rs-layer-settings-tabs">
            <li id="rs-style-tab-button" data-content="#rs-style-content-wrapper" class="selected"><i style="height:45px" class="rs-mini-layer-icon rs-icon-droplet rs-toolbar-icon"></i>
                <span class="rs-anim-tab-txt"><?php echo $modules->l("Style"); ?></span>
                <span id="style-morestyle" class="tipsy_enabled_top" title="<?php echo $modules->l("Advanced Style on/off"); ?>">
                    <i class="rs-icon-morestyles-dark"></i>
                    <i class="rs-icon-morestyles-light"></i>
                </span>
            </li>
            <li id="rs-animation-tab-button" data-content="#rs-animation-content-wrapper"><i style="height:45px" class="rs-mini-layer-icon rs-icon-chooser-2 rs-toolbar-icon"></i>
                <span class="rs-anim-tab-txt"><?php echo $modules->l("Animation"); ?></span>
                <span id="layeranimation-playpause" class=" tipsy_enabled_top" title="<?php echo $modules->l("Play/Pause Single Layer Animation"); ?>">
                    <i class="eg-icon-play"></i>
                    <i class="eg-icon-pause"></i>
                </span>
            </li>
            <li id="rs-loopanimation-tab-button" data-content="#rs-loop-content-wrapper"><i style="height:45px" class="rs-mini-layer-icon rs-icon-chooser-3 rs-toolbar-icon"></i>
                <span class="rs-anim-tab-txt"><?php echo $modules->l("Loop"); ?></span>
                <span id="loopanimation-playpause" class="tipsy_enabled_top" title="<?php echo $modules->l("Play/Pause Layer Loop Animation"); ?>">
                    <i class="eg-icon-play"></i>
                    <i class="eg-icon-pause"></i>
                </span>
            </li>
            <li data-content="#rs-visibility-content-wrapper"><i style="height:45px" class="rs-mini-layer-icon rs-icon-visibility rs-toolbar-icon"></i><?php echo $modules->l("Visibility"); ?></li>
            <li data-content="#rs-behavior-content-wrapper"><i style="height:45px" class="rs-mini-layer-icon eg-icon-resize-full-2 rs-toolbar-icon "></i><?php echo $modules->l("Behavior"); ?></li>
            <li data-content="#rs-action-content-wrapper"><i style="height:45px; font-size:16px" class="rs-mini-layer-icon eg-icon-link rs-toolbar-icon"></i><?php echo $modules->l("Actions"); ?></li>

            <li data-content="#rs-attribute-content-wrapper"><i style="height:45px" class="rs-mini-layer-icon rs-icon-edit-basic rs-toolbar-icon"></i><?php echo $modules->l("Attributes"); ?></li>
            <?php $slide = RbGlobalObject::getVar('slide');
            if ($slide->isStaticSlide()) {

                ?>
                <li data-content="#rs-static-content-wrapper"><i style="height:45px" class="rs-mini-layer-icon eg-icon-dribbble-1 rs-toolbar-icon"></i><?php echo $modules->l("Static Layers");

                ?></li>
    <?php }

?>
            <li data-content="#rs-parallax-content-wrapper"><i style="height:45px; font-size:16px;" class="rs-mini-layer-icon eg-icon-picture-1 rs-toolbar-icon"></i><?php echo $modules->l("Parallax / 3D"); ?></li>
        </ul>

        <div style="clear:both"></div>



    </div>
    <div style="clear:both"></div>

    <!-- THE AMAZING TOOLBAR ABOVE THE SLIDE EDITOR -->
    <form name="form_layers" class="form_layers notselected">

        <!-- LAYER STYLING -->
        <div class="layer-settings-toolbar" id="rs-style-content-wrapper" style="">
<?php //add global styles editor  ?>
            <div id="css_static$modules->lditor_wrap" title="<?php echo $modules->l("Global Style Editor") ?>" style="display:none;">
                <div id="css-static-accordion">
                    <h3><?php echo $modules->l("Dynamic Styles (Not Editable):") ?></h3>
                    <div class="css$modules->lditor_novice_wrap">
                        <textarea id="textarea_show_dynamic_styles" rows="20" cols="81"></textarea>
                    </div>
                    <h3 class="notopradius" style="margin-top:20px"><?php echo $modules->l("Static Styles:") ?></h3>
                    <div>
                        <textarea id="textarea$modules->ldit_static" rows="20" cols="81"></textarea>
                    </div>
                </div>
            </div>
            <div id="dialog-change-css-static" title="<?php echo $modules->l("Save Static Styles") ?>" style="display:none;">
                <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 50px 0;"></span><?php echo $modules->l('Overwrite current static styles?') ?></p>
            </div>

            <div>

                <!-- FONT TEMPLATE -->
                <span class="rs-layer-toolbar-box " style="padding-right:15px;">
                    <i class="rtlmr0 rs-mini-layer-icon rs-icon-fonttemplate rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Caption Style"); ?>" style="margin-right:10px"></i>
                    <input type="text"  style="width:150px; padding-right:30px;" class="textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Style Template"); ?>"  id="layer_caption" name="layer_caption" value="-" />
                    <span id="layer_captions_down" ><i class="eg-icon-arrow-combo"></i></span>
                    <span id="css-template-handling-dd" class="clicktoshowmoresub">
                        <span id="css-template-handling-dd-inner" class="clicktoshowmoresub_inner">
                            <span style="background:#ddd !important; padding-left:20px;margin-bottom:5px" class="css-template-handling-menupoint"><span><?php echo $modules->l("Style Options"); ?></span></span>
                            <span id="save-current-css"   class="save-current-css css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-save-dark"></i><span><?php echo $modules->l("Save"); ?></span></span>
                            <span id="save-as-current-css"   class="save-as-current-css css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-save-dark"></i><span><?php echo $modules->l("Save As"); ?></span></span>
                            <span id="rename-current-css" class="rename-current-css css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-chooser-1"></i><span><?php echo $modules->l("Rename"); ?></span></span>
                            <span id="reset-current-css"  class="reset-current-css css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-2drotation"></i><span><?php echo $modules->l("Reset"); ?></span></span>
                            <span id="delete-current-css" class="delete-current-css css-template-handling-menupoint"><i style="background-size:10px 14px;" class="rs-mini-layer-icon rs-toolbar-icon rs-icon-delete"></i><span><?php echo $modules->l("Delete"); ?></span></span>
                        </span>
                    </span>
                </span>

                <span class="rs-layer-toolbar-box hide_on_shapelayer hide_on_videolayer hide_on_imagelayer">
                    <!-- FONT SIZE -->
                    <i class="rs-mini-layer-icon rs-icon-fontsize rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Font Size (px)"); ?>" style="margin-right:6px" ></i>
                    <input type="text"  data-suffix="px" class="rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Font Size"); ?>" style="width:61px" id="layer_font_size_s" name="font_size_static" value="20px" />
                    <span class="rs-layer-toolbar-space"></span>

                    <!-- LINE HEIGHT -->
                    <i class="rs-mini-layer-icon rs-icon-lineheight rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Line Height (px)"); ?>" style="margin-right:11px" ></i>
                    <input type="text" data-suffix="px" class="rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Line Height"); ?>" style="width:61px" id="layer_line_height_s" name="line_height_static" value="22px" />
                    <span class="rs-layer-toolbar-space" style="margin-right:6px" ></span>
                </span>

                <!-- WRAP -->
                <span class="rs-layer-toolbar-box tipsy_enabled_top" style="display: none" title="<?php echo $modules->l("White Space"); ?>">
                    <i class="rs-mini-layer-icon rs-icon-wrap rs-toolbar-icon"></i>
                    <select class="rs-layer-input-field" style="width:95px" id="layer_whitespace" name="layer_whitespace">
                        <option value="normal"><?php echo $modules->l('Normal'); ?></option>
                        <option value="pre"><?php echo $modules->l('Pre'); ?></option>
                        <option value="nowrap" selected="selected"><?php echo $modules->l('No-wrap'); ?></option>
                        <option value="pre-wrap"><?php echo $modules->l('Pre-Wrap'); ?></option>
                        <option value="pre-line"><?php echo $modules->l('Pre-Line'); ?></option>
                    </select>
                </span>

                <!-- ALIGN -->
                <span class="rs-layer-toolbar-box tipsy_enabled_top" style="padding-right:18px;" id="rs-align-wrapper">
                    <i class="rs-mini-layer-icon eg-icon-arrow-combo rs-toolbar-icon" style="margin-right:3px"></i>
                    <a href="javascript:void(0)" id='align_left' data-hor="left"  class='rbnewgray layer-toolbar-button rs-new-align-button tipsy_enabled_top' title="<?php echo $modules->l("Layer Align Left"); ?>"><i class="rs-mini-layer-icon rs-icon-alignleft rs-toolbar-icon"></i></a>
                    <a href="javascript:void(0)" id='align_center_h' data-hor="center" class='rbnewgray layer-toolbar-button rs-new-align-button tipsy_enabled_top' title="<?php echo $modules->l("Layer Align Center"); ?>"><i class="rs-mini-layer-icon rs-icon-aligncenterh rs-toolbar-icon"></i></a>
                    <a href="javascript:void(0)" id='align_right' data-hor="right" class='rbnewgray layer-toolbar-button rs-new-align-button tipsy_enabled_top' title="<?php echo $modules->l("Layer Align Right"); ?>"><i class="rs-mini-layer-icon rs-icon-alignright rs-toolbar-icon"></i></a>									
                    <input type="text"  class='text-sidebar' style="display:none" id="layer_align_hor" name="layer_align_hor" value="left" />				

                </span>

                <span class="rs-layer-toolbar-box">
                    <!-- POSITION X -->
                    <i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Horizontal Offset from Aligned Position (px)"); ?>" style="margin-right:8px"></i>
                    <input type="text" data-suffix="px" class="text-sidebar setting-disabled rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Horizontal Offset from Aligned Position (px)"); ?>" style="width:50px" id="layer_left" name="layer_left" value="" disabled="disabled">
                    <span class="rs-layer-toolbar-space" style="margin-right:10px"></span>

                    <!-- POSITION Y -->
                    <i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Vertical Offset from Aligned Position (px)"); ?>" style="margin-right:4px"></i>
                    <input type="text" data-suffix="px" class="text-sidebar setting-disabled rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Vertical Offset from Aligned Position (px)"); ?>" style="width:50px" id="layer_top" name="layer_top" value="" disabled="disabled">
                    <span class="rs-layer-toolbar-space" style="margin-right:10px"></span>
                </span>

                <span class="rs-layer-toolbar-box hide_on_shapelayer hide_on_videolayer hide_on_imagelayer hide_on_buttonlayer">
                    <!-- HTML TAG -->
                    <i class="rs-mini-layer-icon eg-icon-code rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("HTML Tag for Layer"); ?>"></i>
                    <select class="rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("HTML Tag"); ?>" style="width:61px" id="layer_html_tag" name="layer_html_tag">
                        <option value="div">&lt;div&gt; - default</option>
                        <option value="p">&lt;p&gt;</option>
                        <option value="h1">&lt;h1&gt;</option>
                        <option value="h2">&lt;h2&gt;</option>
                        <option value="h3">&lt;h3&gt;</option>
                        <option value="h4">&lt;h4&gt;</option>
                        <option value="h5">&lt;h5&gt;</option>
                        <option value="h6">&lt;h6&gt;</option>
                        <option value="span">&lt;span&gt;</option>
                    </select>					
                </span>
            </div>


            <div style="border-top:1px solid #ddd;">

                <!-- FONT FAMILY-->
                <span class="rs-layer-toolbar-box hide_on_shapelayer hide_on_videolayer hide_on_imagelayer" style="padding-right:0px;">
                    <i class="rs-mini-layer-icon rs-icon-fontfamily rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Font Family"); ?>" style="margin-right:10px"></i>
                    <input type="text" class="rs-staticcustomstylechange text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Font Family"); ?>" style="width:185px" type="text" id="css_font-family" name="css_font-family" value="" autocomplete="off"> <?php /*  id="font_family" */ ?>
                    <span id="css_fonts_down" class="ui-state-active" style="position: relative;"><i class="eg-icon-arrow-combo"></i></span>
                    <span class="rs-layer-toolbar-space" style="margin-right:9px"></span>
                </span>



                <!-- FONT DIRECT MANAGEMENT -->
                <span class="rs-layer-toolbar-box hide_on_shapelayer hide_on_videolayer hide_on_imagelayer">

                    <!-- FONT WEIGHT -->
                    <i class="rs-mini-layer-icon rs-icon-fontweight rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Font Weight"); ?>"></i>
                    <select class="rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Font Weight"); ?>" style="width:61px" id="layer_font_weight_s" name="font_weight_static">
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="300">300</option>
                        <option value="400">400</option>
                        <option value="500">500</option>
                        <option value="600">600</option>
                        <option value="700">700</option>
                        <option value="800">800</option>
                        <option value="900">900</option>
                    </select>
                    <span class="rs-layer-toolbar-space"></span>

                    <!-- COLOR -->
                    <i class="rs-mini-layer-icon rs-icon-color rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Font Color"); ?>"></i>
                    <input type="text" class="my-color-field rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Font Color"); ?>"  id="layer_color_s" name="color_static" value="#ffffff" />
                </span>

                <!-- ALIGN -->
                <span class="rs-layer-toolbar-box tipsy_enabled_top" style="padding-right:18px;" id="rs-align-wrapper-ver">
                    <i class="rs-mini-layer-icon eg-icon-arrow-combo rs-toolbar-icon" style="margin-right:3px"></i>															
                    <a href="javascript:void(0)" id='align_top' data-ver="top" class='rbnewgray layer-toolbar-button rs-new-align-button tipsy_enabled_top' title="<?php echo $modules->l("Layer Align Top"); ?>"><i class="rs-mini-layer-icon rs-icon-aligntop rs-toolbar-icon"></i></a>
                    <a href="javascript:void(0)" id='align_center_v' data-ver="middle" class='rbnewgray layer-toolbar-button rs-new-align-button tipsy_enabled_top' title="<?php echo $modules->l("Layer Align Middle"); ?>"><i class="rs-mini-layer-icon rs-icon-aligncenterv rs-toolbar-icon"></i></a>
                    <a href="javascript:void(0)" id='align_bottom' data-ver="bottom" class='rbnewgray layer-toolbar-button rs-new-align-button tipsy_enabled_top' title="<?php echo $modules->l("Layer Align Bottom"); ?>"><i class="rs-mini-layer-icon rs-icon-alignbottom rs-toolbar-icon"></i></a>
                    <input type="text"  class='text-sidebar' style="display:none" id="layer_align_vert" name="layer_align_vert" value="top" />
                </span>

                <span class="rs-layer-toolbar-box">
                    <!-- WIDTH -->
                    <i class="rs-mini-layer-icon rs-icon-maxwidth rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Layer Width (px). Use 'auto' to respect White Space"); ?>" style="margin-right:3px"></i>
                    <input type="text" data-suffix="px" class="text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Layer Width (px). Use 'auto' to respect White Space"); ?>" style="display:none;width:50px" id="layer_max_width" name="layer_max_width" value="auto">
                    <input type="text" data-suffix="px" class="text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Layer Width (px). Use 'auto' to respect White Space"); ?>" style="width:50px" id="layer_scaleX" name="layer_scaleX" value="">
                    <input type="text" data-suffix="px" class="text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Video Width (px). Use 'auto' to respect White Space"); ?>" style="display:none;width:50px" id="layer_video_width" name="layer_video_width" value="">
                    <span class="rs-layer-toolbar-space" style="margin-right:11px"></span>

                    <i class="rs-mini-layer-icon rs-icon-maxheight rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Layer Height (px). Use 'auto' to respect White Space"); ?>"></i>
                    <input type="text" data-suffix="px" class="text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Layer Height (px). Use 'auto' to respect White Space"); ?>" style="display:none;width:50px" id="layer_max_height" name="layer_max_height" value="auto">
                    <input type="text" data-suffix="px" class="text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Layer Height (px) Use 'auto' to respect White Space"); ?>" style="width:50px" id="layer_scaleY" name="layer_scaleY" value="">
                    <input type="text" data-suffix="px" class="text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Video Height (px). Use 'auto' to respect White Space"); ?>" style="display:none;width:50px" id="layer_video_height" name="layer_video_height" value="">
                    <span class="rs-layer-toolbar-space" style="margin-right:11px"></span>					

                    <span id="layer-covermode-wrapper" class="tipsy_enabled_top" title="<?php echo $modules->l("Cover Mode"); ?>">
                        <i class="rs-mini-layer-icon eg-icon-resize-normal rs-toolbar-icon"></i>
                        <select class="rs-layer-input-field"  style="width:75px" id="layer_cover_mode" name="layer_cover_mode">
                            <option value="custom"><?php echo $modules->l('Custom'); ?></option>
                            <option value="fullwidth"><?php echo $modules->l('Full Width'); ?></option>
                            <option value="fullheight"><?php echo $modules->l('Full Height'); ?></option>
                            <option value="cover"><?php echo $modules->l('Stretch'); ?></option>
                            <option value="cover-proportional"><?php echo $modules->l('Cover'); ?></option>
                        </select>
                    </span>
                    <span class="rs-layer-toolbar-space" style="margin-right:11px"></span>

                    <span id="layer-linebreak-wrapper" class="rs-linebreak-check layer-toolbar-button tipsy_enabled_top" title="<?php echo $modules->l("Auto Linebreak (on/off - White Space:normal / nowrap).  "); ?>" style="">
                        <i class="rs-mini-layer-icon eg-icon-paragraph rs-toolbar-icon"></i>
                        <input type="checkbox" id="layer_auto_line_break" class="inputCheckbox" name="layer_auto_line_break" style="width: 26px;height: 26px;margin: 0px;position: absolute;top: 0px;left: 0px; filter: alpha(opacity=0);-moz-opacity: 0.0;-khtml-opacity: 0.0;opacity: 0.0;">
                    </span>

                    <span id="layer-prop-wrapper" class="rs-proportion-check layer-toolbar-button tipsy_enabled_top" title="<?php echo $modules->l("Keep Aspect Ratio (on/off)"); ?>" style="position:relative; display:inline-block;  width:26px; padding:0px; text-align:center; vertical-align: middle">
                        <i class="rs-mini-layer-icon eg-icon-link rs-toolbar-icon"></i>
                        <input type="checkbox" id="layer_proportional_scale" class="inputCheckbox" name="layer_proportional_scale" style="width: 26px;height: 26px;margin: 0px;position: absolute;top: 0px;left: 0px; filter: alpha(opacity=0);-moz-opacity: 0.0;-khtml-opacity: 0.0;opacity: 0.0;">
                    </span>

                    <a href="javascript:void(0)" id="reset-scale" class="rbnewgray layer-toolbar-button  tipsy_enabled_top" title="<?php echo $modules->l("Reset original size"); ?>"><i class="eg-icon-resize-normal"></i></a>


                </span>
            </div>


            <!-- SUB SETTINGS FOR CSS -->
            <div id="style_form_wrapper">
                <div id="extra_style_settings" class="extra_sub_settings_wrapper" >
                    <div class="close$modules->lxtra_settings"></div>
                    <div class="inner-settings-wrapper">
                        <div id="tp-idle-state-advanced-style" style="float:left; padding-left:20px;">

                            <ul class="rs-layer-animation-settings-tabs" style="display:inline-block; ">
                                <li data-content="#style-sub-font" class="selected"><?php echo $modules->l("Font"); ?></li>
                                <li data-content="#style-sub-background"><?php echo $modules->l("Background"); ?></li>
                                <li data-content="#style-sub-border"><?php echo $modules->l("Border"); ?></li>
                                <li data-content="#style-sub-transfrom" ><?php echo $modules->l("Transform"); ?></li>
                                <li data-content="#style-sub-rotation" ><?php echo $modules->l("Rotation"); ?></li>
                                <li data-content="#style-sub-perspective"><?php echo $modules->l("Perspective"); ?></li>								
                                <li data-content="#style-sub-sharpc"><?php echo $modules->l("Corners"); ?></li>
                                <li data-content="#style-sub-advcss"><?php echo $modules->l("Advanced CSS"); ?></li>		
                                <li data-content="#style-sub-hover"><?php echo $modules->l("Hover"); ?></li>		
                            </ul>
                            <div style="width:100%;height:1px;display:block"></div>
                            <span id="style-sub-font" class="rs-layer-toolbar-box hide_on_shapelayer hide_on_videolayer hide_on_imagelayer" style="display:block">

                                <!-- FONT OPACITY -->
                                <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Font Opacity"); ?>" style="margin-right:10px"></i>
                                <input data-suffix="" data-steps="0.05" data-min="0" data-max="1" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Font Opacity"); ?>" style="width:50px" type="text" id="css_font-transparency" name="css_font-transparency" value="1">
                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- ITALIC -->
                                <i class="rs-mini-layer-icon rs-icon-italic rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Italic Font"); ?>" style="margin-right:10px"></i>
                                <input type="checkbox" id="css_font-style" name="css_font-style" class="rs-staticcustomstylechange tipsy_enabled_top tp-moderncheckbox" title="<?php echo $modules->l("Italic Font On/Off"); ?>">
                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- DECORATION -->
                                <i class="rs-mini-layer-icon rs-icon-decoration rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Font Decoration"); ?>" style="margin-right:10px"></i>
                                <select class="rs-staticcustomstylechange rs-layer-input-field  tipsy_enabled_top" title="<?php echo $modules->l("Font Decoration"); ?>" style="width:100px;cursor:pointer" id="css_text-decoration" name="css_text-decoration">
                                    <option value="none"><?php echo $modules->l('none'); ?></option>
                                    <option value="underline"><?php echo $modules->l('underline'); ?></option>
                                    <option value="overline"><?php echo $modules->l('overline'); ?></option>
                                    <option value="line-through"><?php echo $modules->l('line-through'); ?></option>
                                </select>

                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- TEXT ALIGN -->
                                <i class="rs-mini-layer-icon eg-icon-menu rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Text Align"); ?>" style="margin-right:10px"></i>
                                <select class="rs-staticcustomstylechange rs-layer-input-field  tipsy_enabled_top" title="<?php echo $modules->l("Text Align"); ?>" style="width:100px;cursor:pointer" id="css_text-align" name="css_text-align">
                                    <option value="left"><?php echo $modules->l('Left'); ?></option>
                                    <option value="center"><?php echo $modules->l('Center'); ?></option>
                                    <option value="right"><?php echo $modules->l('Right'); ?></option>
                                </select>

                            </span>


                            <span id="style-sub-background" class="rs-layer-toolbar-box" style="display:none;border:none;">
                                <!-- BACKGROUND COLOR -->
                                <i class="rs-mini-layer-icon rs-icon-bg rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Background Color"); ?>" style="margin-right:10px"></i>
                                <input type="text" class="rs-staticcustomstylechange rs-layer-input-field tipsy_enabled_top my-color-field" title="<?php echo $modules->l("Background Color"); ?>" style="width:150px" id="css_background-color" name="css_background-color" value="transparent" />
                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- BACKGROUND OPACITY -->
                                <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Background Opacity"); ?>" style="margin-right:10px"></i>
                                <input data-suffix="" data-steps="0.05" data-min="0" data-max="1" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Background Opacity"); ?>" style="width:50px" type="text" id="css_background-transparency" name="css_background-transparency" value="1">
                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- PADDING -->
                                <i class="rs-mini-layer-icon rs-icon-padding rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Padding"); ?>" style="margin-right:10px"></i>
                                <input data-suffix="px" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Padding Top"); ?>" style="width:50px" type="text" name="css_padding[]" value="">
                                <input data-suffix="px" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Padding Right"); ?>" style="width:50px" type="text" name="css_padding[]" value="">
                                <input data-suffix="px" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Padding Bottom"); ?>" style="width:50px" type="text" name="css_padding[]" value="">
                                <input data-suffix="px" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Padding Left"); ?>" style="width:50px" type="text" name="css_padding[]" value="">
                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- BACKGROUND POSITION ALIGN -->
                                <!--<i class="rs-mini-layer-icon eg-icon-move rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Background Position"); ?>" style="margin-right:10px"></i>
                                <select class="rs-staticcustomstylechange rs-layer-input-field  tipsy_enabled_top" title="<?php echo $modules->l("Background Position"); ?>" style="width:100px;cursor:pointer" name="layer_bg_position">
                                    <option value="left top"><?php echo $modules->l('Left Top'); ?></option>
                                    <option value="center top"><?php echo $modules->l('Center Top'); ?></option>
                                    <option value="right top"><?php echo $modules->l('Right Top'); ?></option>
                                    <option value="left center"><?php echo $modules->l('Left Center'); ?></option>
                                    <option value="center center"><?php echo $modules->l('Center Center'); ?></option>
                                    <option value="right center"><?php echo $modules->l('Right Center'); ?></option>
                                    <option value="left bottom"><?php echo $modules->l('Left Bottom'); ?></option>
                                    <option value="center bottom"><?php echo $modules->l('Center Bottom'); ?></option>
                                    <option value="right bottom"><?php echo $modules->l('Right Bottom'); ?></option>
                                </select>	-->

                                <!-- BACKGROUND SIZE  -->
                                <!--<i class="rs-mini-layer-icon eg-icon-crop rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Background Size"); ?>" style="margin-right:10px"></i>
                                <select class="rs-staticcustomstylechange rs-layer-input-field  tipsy_enabled_top" title="<?php echo $modules->l("Background Size"); ?>" style="width:100px;cursor:pointer" name="layer_bg_size">
                                    <option value="cover"><?php echo $modules->l('Cover'); ?></option>
                                    <option value="contain"><?php echo $modules->l('Contain'); ?></option>
                                </select>-->
                            </span>

                            <span id="style-sub-border" class="rs-layer-toolbar-box" style="display:none;border:none;">
                                <!-- BORDER COLOR -->
                                <i class="rs-mini-layer-icon rs-icon-bordercolor rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Border Color"); ?>" style="margin-right:10px"></i>
                                <input type="text" class="rs-staticcustomstylechange my-color-field rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Border Color"); ?>"  style="width:150px" id="css_border-color-show" name="css_border-color-show" value="transparent" />
                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- FONT OPACITY -->
                                <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Border Opacity"); ?>" style="margin-right:10px"></i>
                                <input data-suffix="" data-steps="0.05" data-min="0" data-max="1" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Border Opacity"); ?>" style="width:50px" type="text" id="css_border-transparency" name="css_border-transparency" value="1">
                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- BORDER STYLE -->
                                <i class="rs-mini-layer-icon rs-icon-borderstyle rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Border Style"); ?>" style="margin-right:10px"></i>
                                <select class="rs-staticcustomstylechange rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Border Style"); ?>" style="width:100px cursor:pointer" id="css_border-style" name="css_border-style">
                                    <option value="none"><?php echo $modules->l('none'); ?></option>
                                    <option value="dotted"><?php echo $modules->l('dotted'); ?></option>
                                    <option value="dashed"><?php echo $modules->l('dashed'); ?></option>
                                    <option value="solid"><?php echo $modules->l('solid'); ?></option>
                                    <option value="double"><?php echo $modules->l('double'); ?></option>
                                </select>
                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- BORDER WIDTH-->
                                <i class="rs-mini-layer-icon rs-icon-borderwidth rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Border Width"); ?>" style="margin-right:10px"></i>
                                <input data-suffix="px" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Border Width"); ?>" style="width:50px" type="text" id="css_border-width" name="css_border-width" value="0">
                                <span class="rs-layer-toolbar-space" style="margin-right:16px"></span>

                                <!-- BORDER RADIUS -->
                                <i class="rs-mini-layer-icon rs-icon-borderradius rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Border Radius (px)"); ?>" style="margin-right:10px"></i>
                                <input data-suffix="px" data-suffixalt="%" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" data-steps="1" data-min="0"  title="<?php echo $modules->l("Border Radius Top Left"); ?>" style="width:50px" type="text" name="css_border-radius[]" value="">
                                <input data-suffix="px" data-suffixalt="%" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" data-steps="1" data-min="0" title="<?php echo $modules->l("Border Radius Top Right"); ?>" style="width:50px" type="text" name="css_border-radius[]" value="">
                                <input data-suffix="px" data-suffixalt="%" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" data-steps="1" data-min="0" title="<?php echo $modules->l("Border Radius Bottom Right"); ?>" style="width:50px" type="text" name="css_border-radius[]" value="">
                                <input data-suffix="px" data-suffixalt="%" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" data-steps="1" data-min="0" title="<?php echo $modules->l("Border Radius Bottom Left"); ?>" style="width:50px" type="text" name="css_border-radius[]" value="">
                            </span>


                            <span id="style-sub-rotation" class="rs-layer-toolbar-box" style="display:none;border:none;">
                                <!--  X  ROTATE -->
                                <i class="rs-mini-layer-icon rs-icon-rotationx rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Rotation on X axis (+/-)"); ?>"></i>
                                <input data-suffix="deg" type="text" style="width:55px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Rotation on X axis (+/-)"); ?>" id="layer__xrotate" name="layer__xrotate" value="0">
                                <span class="rs-layer-toolbar-space"></span>
                                <!--  Y ROTATE -->
                                <i class="rs-mini-layer-icon rs-icon-rotationy rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Rotation on Y axis (+/-)"); ?>"></i>
                                <input data-suffix="deg" type="text" style="width:55px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Rotation on Y axis (+/-)"); ?>" id="layer__yrotate" name="layer__yrotate" value="0">
                                <span class="rs-layer-toolbar-space"></span>

                                <!--  Z ROTATE -->
                                <i class="rs-mini-layer-icon rs-icon-rotationz rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Rotation on Z axis (+/-)"); ?>"></i>
                                <input data-suffix="deg" type="text" style="width:55px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Rotation on Z axis (+/-)"); ?>" id="layer_2d_rotation" name="layer_2d_rotation" value="0">
                                <span class="rs-layer-toolbar-space" style="margin-right:15px;"></span>

                                <!-- ORIGIN X -->
                                <i class="rs-mini-layer-icon rs-icon-originx rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Horizontal Origin"); ?>"></i>
                                <input data-suffix="%" type="text" style="width:55px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Horizontal Origin"); ?>" id="layer_2d_origin_x" name="layer_2d_origin_x" value="50">
                                <span class="rs-layer-toolbar-space"></span>
                                <!-- ORIGIN Y -->
                                <i class="rs-mini-layer-icon rs-icon-originy rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Vertical Origin"); ?>"></i>
                                <input data-suffix="%" type="text" style="width:55px;" class="textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Vertical Origin"); ?>" id="layer_2d_origin_y" name="layer_2d_origin_y" value="50">

                            </span>

                            <span id="style-sub-transfrom" class="rs-layer-toolbar-box" style="display:none;border:none;">
                                <!-- OPACITY -->
                                <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Opacity. (1 = 100% Visible / 0.5 = 50% opacaity / 0 = transparent)"); ?>" style="margin-right:8px"></i>
                                <input data-suffix="" data-steps="0.05" data-min="0" data-max="1"  type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Opacity (1 = 100% Visible / 0.5 = 50% opacaity / 0 = transparent)"); ?>" id="layer__opacity" name="layer__opacity" value="1">
                                <span class="rs-layer-toolbar-space" style="margin-right:15px;"></span>

                                <!-- SCALE X -->
                                <i class="rs-mini-layer-icon rs-icon-scalex rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("X Scale  1 = 100%, 0.5=50%... (+/-)"); ?>" style="margin-right:4px"></i>
                                <input data-suffix="" data-steps="0.01" data-min="0" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("X Scale  1 = 100%, 0.5=50%... (+/-)"); ?>" id="layer__scalex" name="layer__scalex" value="1">
                                <span class="rs-layer-toolbar-space" style="margin-right:15px;"></span>

                                <!-- SCALE Y -->
                                <i  class="rs-mini-layer-icon rs-icon-scaley rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Y Scale  1 = 100%, 0.5=50%... (+/-)"); ?>" style="margin-right:8px"></i>
                                <input data-suffix="" data-steps="0.01"  data-min="0" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Y Scale  1 = 100%, 0.5=50%... (+/-)"); ?>" id="layer__scaley" name="layer__scaley" value="1">
                                <span class="rs-layer-toolbar-space" style="margin-right:15px;"></span>

                                <!-- SKEW X -->
                                <i class="rs-mini-layer-icon rs-icon-skewx rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("X Skew (+/-  px)"); ?>" style="margin-right:4px"></i>
                                <input data-suffix="px" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field  tipsy_enabled_top" title="<?php echo $modules->l("X Skew (+/-  px)"); ?>" id="layer__skewx" name="layer__skewx" value="0">
                                <span class="rs-layer-toolbar-space" style="margin-right:15px;"></span>

                                <!-- SKEW Y -->
                                <i class="rs-mini-layer-icon rs-icon-skewy rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Y Skew (+/-  px)"); ?>" style="margin-right:8px"></i>
                                <input data-suffix="px" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Y Skew (+/-  px)"); ?>" id="layer__skewy" name="layer__skewy" value="0">

                            </span>

                            <!-- ADVANCED CSS -->
                            <span id="style-sub-advcss" class="rs-layer-toolbar-box" style="display:none;border:none;">
                                <div id="advanced-css-main" class="rb-advanced-css-idle advanced-copy-button"><?php echo $modules->l("Template"); ?></div>
                                <div id="advanced-css-layer" class="rb-advanced-css-idle-layer advanced-copy-button"><?php echo $modules->l("Layer"); ?></div>
                            </span>

<?php $easings = $operations->getArrEasing(); ?>

                            <!-- CAPTION HOVER CSS -->
                            <span id="style-sub-hover" class="rs-layer-toolbar-box" style="display:none;border:none;">
                                <!-- Caption Hover on/off -->
                                <span><?php echo $modules->l("Layer Hover"); ?></span>
                                <span class="rs-layer-toolbar-space"></span>
                                <input id="hover_allow" name="hover_allow" type="checkbox" class="tp-moderncheckbox" />
                                <span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

                                <!-- ANIMATION START SPEED -->
                                <i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Hover Animation Speed (in ms)"); ?>"></i>
                                <input type="text" style="width:90px; padding-right:10px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Hover Animation Speed (in ms)"); ?>" id="hover_speed" name="hover_speed" value="">
                                <span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>


                                <!-- HOVER EASE -->
                                <i class="rs-mini-layer-icon rs-icon-easing rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Hover Animation Easing"); ?>"></i>
                                <select class="rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Hover Animation Easing"); ?>" style="width:140px"  id="hover$modules->lasing" name="hover$modules->lasing">
<?php
foreach ($easings as $ehandle => $ename) {
    echo '<option value="' . $ehandle . '">' . $ename . '</option>';
}

?>
                                </select>
                                <span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

                                <!-- CURSOR -->
                                <i class="rs-mini-layer-icon eg-icon-up-hand rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Mouse Cursor"); ?>" style="margin-right:10px"></i>
                                <select class="rs-staticcustomstylechange rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Mouse Cursor"); ?>" style="width:100px cursor:pointer" id="css_cursor" name="css_cursor">
                                    <option value="auto"><?php echo $modules->l('auto'); ?></option>
                                    <option value="default"><?php echo $modules->l('default'); ?></option>
                                    <option value="crosshair"><?php echo $modules->l('crosshair'); ?></option>
                                    <option value="pointer"><?php echo $modules->l('pointer'); ?></option>
                                    <option value="move"><?php echo $modules->l('move'); ?></option>
                                    <option value="text"><?php echo $modules->l('text'); ?></option>
                                    <option value="wait"><?php echo $modules->l('wait'); ?></option>
                                    <option value="help"><?php echo $modules->l('help'); ?></option>
                                    <option value="zoom-in"><?php echo $modules->l('zoom-in'); ?></option>
                                    <option value="zoom-out"><?php echo $modules->l('zoom-out'); ?></option>
                                </select>

                            </span>


                            <span id="style-sub-perspective" class="rs-layer-toolbar-box" style="display:none;border:none;">
                                <!-- PERSPECTIVE -->
                                <i class="rs-mini-layer-icon rs-icon-perspective rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation Perspective (default 600)"); ?>" style="margin-right:8px"></i>
                                <input type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Animation Perspective (default 600)"); ?>" id="layer__pers" name="layer__pers" value="600">
                                <span class="rs-layer-toolbar-space"></span>

                                <!-- Z - OFFSET -->
                                <i class="rs-mini-layer-icon rs-icon-zoffset rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Z Offset (+/-  px)"); ?>" style="margin-right:4px"></i>
                                <input data-suffix="px" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Z Offset (+/-  px)"); ?>" id="layer__z" name="layer__z" value="0">
                            </span>


                            <span id="style-sub-sharpc" class="rs-layer-toolbar-box" style="display:none;border:none;">

                                <span><?php echo $modules->l("Sharp Left"); ?></span>
                                <span class="rs-layer-toolbar-space"></span>
                                <select id="layer_cornerleft" name="layer_cornerleft" class="rs-layer-input-field" style="width:175px">
                                    <option value="nothing" selected="selected"><?php echo $modules->l("No Corner"); ?></option>
                                    <option value="curved"><?php echo $modules->l("Sharp"); ?></option>
                                    <option value="reverced"><?php echo $modules->l("Sharp Reversed"); ?></option>
                                </select>
                                <span class="rs-layer-toolbar-space"></span>

                                <span><?php echo $modules->l("Sharp Right"); ?></span>
                                <span class="rs-layer-toolbar-space"></span>
                                <select id="layer_cornerright" name="layer_cornerright" class="rs-layer-input-field" style="width:175px">
                                    <option value="nothing" selected="selected"><?php echo $modules->l("No Corner"); ?></option>
                                    <option value="curved"><?php echo $modules->l("Sharp"); ?></option>
                                    <option value="reverced"><?php echo $modules->l("Sharp Reversed"); ?></option>
                                </select>

                            </span>
                        </div>

                        <!-- THE HOVER STLYE PART -->
                        <div id="tp-hover-state-advanced-style" style="float:left;display:none; padding-left:20px;">
                            <ul class="rs-layer-animation-settings-tabs" style="display:inline-block;min-width:615px ">
                                <li data-content="#hover-sub-font" class="selected"><?php echo $modules->l("Font"); ?></li>
                                <li data-content="#hover-sub-background"><?php echo $modules->l("Background"); ?></li>
                                <li data-content="#hover-sub-border"><?php echo $modules->l("Border"); ?></li>
                                <li data-content="#hover-sub-transform"><?php echo $modules->l("Transform"); ?></li>
                                <li data-content="#hover-sub-rotation" ><?php echo $modules->l("Rotation"); ?></li>
                                <li data-content="#hover-sub-advcss" ><?php echo $modules->l("Advanced CSS"); ?></li>
                            </ul>

                            <div style="width:100%;height:1px;display:block"></div>

                            <span id="hover-sub-font" class="rs-layer-toolbar-box" style="display:block">

                                <i class="rs-mini-layer-icon rs-icon-color rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Font Color"); ?>"></i>
                                <input type="text" class="my-color-field rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Font Color"); ?>"  id="hover_layer_color_s" name="hover_color_static" value="#ff0000" />
                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- FONT HOVER OPACITY -->
                                <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Font Hover Opacity"); ?>" style="margin-right:10px"></i>
                                <input data-suffix="" data-steps="0.05" data-min="0" data-max="1" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Font Hover Opacity"); ?>" style="width:50px" type="text" id="hover_css_font-transparency" name="hover_css_font-transparency" value="1">

                                <!-- DECORATION -->
                                <i class="rs-mini-layer-icon rs-icon-decoration rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Font Decoration"); ?>" style="margin-right:10px"></i>
                                <select class="rs-staticcustomstylechange rs-layer-input-field  tipsy_enabled_top" title="<?php echo $modules->l("Font Decoration"); ?>" style="width:100px;cursor:pointer" id="hover_css_text-decoration" name="hover_css_text-decoration">
                                    <option value="none"><?php echo $modules->l('none'); ?></option>
                                    <option value="underline"><?php echo $modules->l('underline'); ?></option>
                                    <option value="overline"><?php echo $modules->l('overline'); ?></option>
                                    <option value="line-through"><?php echo $modules->l('line-through'); ?></option>
                                </select>
                            </span>

                            <span id="hover-sub-background" class="rs-layer-toolbar-box" style="display:none;border:none;">
                                <!-- BACKGROUND COLOR -->
                                <i class="rs-mini-layer-icon rs-icon-bg rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Background Color"); ?>" style="margin-right:10px"></i>
                                <input type="text" class="rs-staticcustomstylechange my-color-field rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Background Color"); ?>"  id="hover_css_background-color" name="hover_css_background-color" value="transparent" />
                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- BACKGROUND OPACITY -->
                                <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Background Opacity"); ?>" style="margin-right:10px"></i>
                                <input data-suffix="" data-steps="0.05" data-min="0" data-max="1" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Background Opacity"); ?>" style="width:50px" type="text" id="hover_css_background-transparency" name="hover_css_background-transparency" value="1">
                            </span>

                            <span id="hover-sub-border" class="rs-layer-toolbar-box" style="display:none;border:none;">
                                <!-- BORDER COLOR -->
                                <i class="rs-mini-layer-icon rs-icon-bordercolor rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Border Color"); ?>" style="margin-right:10px"></i>
                                <input type="text" class="rs-staticcustomstylechange my-color-field rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Border Color"); ?>"  id="hover_css_border-color-show" name="hover_css_border-color-show" value="transparent" />

                                <!-- BORDER OPACITY -->
                                <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Border Opacity"); ?>" style="margin-right:10px"></i>
                                <input data-suffix="" data-steps="0.05" data-min="0" data-max="1" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Border Opacity"); ?>" style="width:50px" type="text" id="hover_css_border-transparency" name="hover_css_border-transparency" value="1">


                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- BORDER STYLE -->
                                <i class="rs-mini-layer-icon rs-icon-borderstyle rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Border Style"); ?>" style="margin-right:10px"></i>
                                <select class="rs-staticcustomstylechange rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Border Style"); ?>" style="width:100px;cursor:pointer" id="hover_css_border-style" name="hover_css_border-style">
                                    <option value="none"><?php echo $modules->l('none'); ?></option>
                                    <option value="dotted"><?php echo $modules->l('dotted'); ?></option>
                                    <option value="dashed"><?php echo $modules->l('dashed'); ?></option>
                                    <option value="solid"><?php echo $modules->l('solid'); ?></option>
                                    <option value="double"><?php echo $modules->l('double'); ?></option>
                                </select>
                                <span class="rs-layer-toolbar-space" style="margin-right:15px"></span>

                                <!-- BORDER WIDTH-->
                                <i class="rs-mini-layer-icon rs-icon-borderwidth rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Border Width"); ?>" style="margin-right:10px"></i>
                                <input data-suffix="px" class="rs-staticcustomstylechange pad-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Border Width"); ?>" style="width:50px" type="text" id="hover_css_border-width" name="hover_css_border-width" value="0">
                                <span class="rs-layer-toolbar-space" style="margin-right:16px"></span>

                                <!-- BORDER RADIUS -->
                                <i class="rs-mini-layer-icon rs-icon-borderradius rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Border Radius (px)"); ?>" style="margin-right:10px"></i>
                                <input data-suffix="px" data-steps="1" data-min="0" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Border Radius Top Left"); ?>" style="width:50px" type="text" name="hover_css_border-radius[]" value="0px">
                                <input data-suffix="px" data-steps="1" data-min="0" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Border Radius Top Right"); ?>" style="width:50px" type="text" name="hover_css_border-radius[]" value="0px">
                                <input data-suffix="px" data-steps="1" data-min="0" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Border Radius Bottom Right"); ?>" style="width:50px" type="text" name="hover_css_border-radius[]" value="0px">
                                <input data-suffix="px" data-steps="1" data-min="0" class="rs-staticcustomstylechange corn-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Border Radius Bottom Left"); ?>" style="width:50px" type="text" name="hover_css_border-radius[]" value="0px">
                            </span>

                            <span id="hover-sub-transform" class="rs-layer-toolbar-box" style="display:none;border:none;">
                                <!-- OPACITY -->
                                <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Opacity. (1 = 100% Visible / 0.5 = 50% opacaity / 0 = transparent)"); ?>" style="margin-right:8px"></i>
                                <input data-suffix="" data-steps="0.05" data-min="0" data-max="1" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Opacity (1 = 100% Visible / 0.5 = 50% opacaity / 0 = transparent)"); ?>" id="hover_layer__opacity" name="hover_layer__opacity" value="1">
                                <span class="rs-layer-toolbar-space"></span>

                                <!-- SCALE X -->
                                <i class="rs-mini-layer-icon rs-icon-scalex rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("X Scale  1 = 100%, 0.5=50%... (+/-)"); ?>" style="margin-right:8px"></i>
                                <input data-suffix="" data-steps="0.01" data-min="0" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("X Scale  1 = 100%, 0.5=50%... (+/-)"); ?>" id="hover_layer__scalex" name="hover_layer__scalex" value="1">
                                <span class="rs-layer-toolbar-space"></span>
                                <!-- SCALE Y -->
                                <i class="rs-mini-layer-icon rs-icon-scaley rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Y Scale  1 = 100%, 0.5=50%... (+/-)"); ?>" style="margin-right:4px"></i>
                                <input data-suffix="" data-steps="0.01"  data-min="0" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Y Scale  1 = 100%, 0.5=50%... (+/-)"); ?>" id="hover_layer__scaley" name="hover_layer__scaley" value="1">
                                <span class="rs-layer-toolbar-space"></span>

                                <!-- SKEW X -->
                                <i class="rs-mini-layer-icon rs-icon-skewx rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("X Skew (+/-  px)"); ?>" style="margin-right:8px"></i>
                                <input data-suffix="px" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field  tipsy_enabled_top" title="<?php echo $modules->l("X Skew (+/-  px)"); ?>" id="hover_layer__skewx" name="hover_layer__skewx" value="0">
                                <span class="rs-layer-toolbar-space"></span>
                                <!-- SKEW Y -->
                                <i class="rs-mini-layer-icon rs-icon-skewy rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Y Skew (+/-  px)"); ?>" style="margin-right:4px"></i>
                                <input data-suffix="px" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Y Skew (+/-  px)"); ?>" id="hover_layer__skewy" name="hover_layer__skewy" value="0">
                            </span>


                            <span id="hover-sub-rotation" class="rs-layer-toolbar-box" style="display:none;border:none;">
                                <!--  X  ROTATE -->
                                <i class="rs-mini-layer-icon rs-icon-rotationx rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Rotation on X axis (+/-)"); ?>"></i>
                                <input data-suffix="deg" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Rotation on X axis (+/-)"); ?>" id="hover_layer__xrotate" name="hover_layer__xrotate" value="0">
                                <span class="rs-layer-toolbar-space"></span>
                                <!--  Y ROTATE -->
                                <i class="rs-mini-layer-icon rs-icon-rotationy rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Rotation on Y axis (+/-)"); ?>"></i>
                                <input data-suffix="deg" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Rotation on Y axis (+/-)"); ?>" id="hover_layer__yrotate" name="hover_layer__yrotate" value="0">
                                <span class="rs-layer-toolbar-space"></span>

                                <!--  Z ROTATE -->
                                <i class="rs-mini-layer-icon rs-icon-rotationz rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Rotation on Z axis (+/-)"); ?>"></i>
                                <input data-suffix="deg" type="text" style="width:50px;" class="rs-staticcustomstylechange textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Rotation on Z axis (+/-)"); ?>" id="hover_layer_2d_rotation" name="hover_layer_2d_rotation" value="0">

                            </span>

                            <!-- ADVANCED CSS -->
                            <span id="hover-sub-advcss" class="rs-layer-toolbar-box" style="display:none;border:none;">
                                <div id="advanced-css-main" class="rb-advanced-css-hover advanced-copy-button"><?php echo $modules->l("Template"); ?></div>
                                <div id="advanced-css-layer" class="rb-advanced-css-hover-layer advanced-copy-button"><?php echo $modules->l("Layer"); ?></div>
                            </span>

                        </div>


                        <!-- IDLE OR HOVER -->
                        <div id="idle-hover-swapper" style="width:83px; z-index:900;position: relative;">
                            <span id="toggle-idle-hover" class="idleisselected">
                                <span class="advanced-label icon-styleidle"><?php echo $modules->l("Idle"); ?></span>
                                <span class="advanced-label icon-stylehover"><?php echo $modules->l("Hover"); ?></span>
                            </span>
                            <div style="width:100%;height:1px; clear:both"></div>
                            <div style="margin:10px 0px 0px; text-align: center">
                                <div id="copy-idle-css" class="advanced-copy-button copy-settings-trigger clicktoshowmoresub"><?php echo $modules->l("COPY"); ?><i class="eg-icon-down-open"></i>
                                    <span class="copy-settings-from clicktoshowmoresub_inner" style="display: none; height:58px;">
                                        <span class="copy-from-idle css-template-handling-menupoint"><?php echo $modules->l("Copy From Idle"); ?></span>
                                        <span class="copy-from-hover css-template-handling-menupoint"><?php echo $modules->l("Copy From Hover"); ?></span>
                                        <span class="copy-from-in-anim css-template-handling-menupoint"><?php echo $modules->l("Copy From In Animation"); ?></span>
                                        <span class="copy-from-out-anim css-template-handling-menupoint"><?php echo $modules->l("Copy From Out Animation"); ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div style="clear:both; display:block;"></div>
                    </div>											
                </div>
            </div>

        </div>

        <div class="layer-settings-toolbar" id="rs-animation-content-wrapper" style="display:none">
            <p style="margin:0; border-bottom:1px solid #ddd">
                <!-- START TRANSITIONS -->
                <span class="rs-layer-toolbar-box startanim_mainwrapper">
                    <i class="rs-icon-inanim rs-toolbar-icon" style="z-index:100; position:relative;"></i>
                    <span id="startanim_wrapper" style="z-index:50; ">
                        <span id="startanim_timerunnerbox"></span>
                        <span id="startanim_timerunner"></span>
                    </span>
                </span>

                <span id="add_customanimation_in" title="<?php echo $modules->l("Advanced Settings"); ?>"><i style="width:40px;height:40px" class="rs-icon-plus-gray"></i></span>

                <span class="rs-layer-toolbar-box" style="">
                    <!-- ANIMATION DROP DOWN -->
                    <i class="rs-mini-layer-icon rs-icon-transition rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation Template"); ?>"></i>
                    <select class="rs-inoutanimationfield rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Animation Template"); ?>" style="width:135px" id="layer_animation" name="layer_animation">
<?php
foreach ($startanims as $ahandle => $aname) {
    $dis = (in_array($ahandle, array('custom', "v5s", "v5", "v5e", "v4s", "v4", "v4e", "vss", "vs", "vse"))) ? ' disabled="disabled"' : '';
    echo '<option value="' . $ahandle . '"' . $dis . '>' . $aname['handle'] . '</option>';
}

?>
                    </select>
                    <span id="animin-template-handling-dd" class="clicktoshowmoresub">
                        <span id="animin-template-handling-dd-inner" class="clicktoshowmoresub_inner">
                            <span style="background:#ddd !important; padding-left:20px;margin-bottom:5px" class="css-template-handling-menupoint"><span><?php echo $modules->l("Template Options"); ?></span></span>
                            <span id="save-current-animin"   	class="save-current-animin css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-save-dark"></i><span><?php echo $modules->l("Save"); ?></span></span>
                            <span id="save-as-current-animin"   class="save-as-current-animin css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-save-dark"></i><span><?php echo $modules->l("Save As"); ?></span></span>
                            <span id="rename-current-animin" class="rename-current-animin css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-chooser-1"></i><span><?php echo $modules->l("Rename"); ?></span></span>
                            <span id="reset-current-animin"  class="reset-current-animin css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-2drotation"></i><span><?php echo $modules->l("Reset"); ?></span></span>
                            <span id="delete-current-animin" class="delete-current-animin css-template-handling-menupoint"><i style="background-size:10px 14px;" class="rs-mini-layer-icon rs-toolbar-icon rs-icon-delete"></i><span><?php echo $modules->l("Delete"); ?></span></span>
                        </span>
                    </span>

                    <span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

                    <!-- EASING-->
                    <i class="rs-mini-layer-icon rs-icon-easing rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation Easing"); ?>"></i>
                    <select class="rs-inoutanimationfield rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Animation Easing"); ?>" style="width:135px"  id="layer$modules->lasing" name="layer$modules->lasing">
<?php
foreach ($easings as $ehandle => $ename) {
    echo '<option value="' . $ehandle . '">' . $ename . '</option>';
}

?>
                    </select>
                    <span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

                    <!-- ANIMATION START SPEED -->
                    <i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation Speed (in ms)"); ?>"></i>
                    <input type="text" style="width:60px; padding-right:10px;" class="rs-inoutanimationfield textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Animation Speed (in ms)"); ?>" id="layer_speed" name="layer_speed" value="">
                    <span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

                    <!-- SPLIT TEXT -->
                    <i class="rs-mini-layer-icon rs-icon-splittext rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Split Animaton Text (will not respect Html Markups !)"); ?>"></i>
                    <select id="layer_split" name="layer_split" class="rs-inoutanimationfield rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Split Animaton Text (will not respect Html Markups !)"); ?>" style="width:110px">
                        <option value="none" selected="selected"><?php echo $modules->l("No Split"); ?></option>
                        <option value="chars"><?php echo $modules->l("Char Based"); ?></option>
                        <option value="words"><?php echo $modules->l("Word Based"); ?></option>
                        <option value="lines"><?php echo $modules->l("Line Based"); ?></option>
                    </select>
                    <span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

                    <i class="rs-mini-layer-icon rs-icon-splittext-delay rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation Delay between Splitted Elements"); ?>"></i>
                    <input type="text" style="width:65px; padding-right:10px;" class="rs-inoutanimationfield textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Animation Delay between Splitted Elements"); ?>" id="layer_splitdelay" name="layer_splitdelay" value="10" disabled="disabled">
                </span>
            </p>

            <div id="extra_start_animation_settings" class="extra_sub_settings_wrapper" style="margin:0; background:#fff; display:none; " >

                <div class="anim-direction-wrapper" style="text-align: center">
                    <i class="rs-icon-schin rs-toolbar-icon" style="height:90px"></i>																
                </div>

                <div class="float_left" style="display:inline-block;padding:10px 0px;">
                    <div class="inner-settings-wrapper">
                        <ul class="rs-layer-animation-settings-tabs">
                            <li data-content="#anim-sub-s-offset" class="selected"><?php echo $modules->l("Offset"); ?></li>
                            <li data-content="#anim-sub-s-opacity"><?php echo $modules->l("Opacity"); ?></li>
                            <li data-content="#anim-sub-s-rotation"><?php echo $modules->l("Rotation"); ?></li>
                            <li data-content="#anim-sub-s-scale"><?php echo $modules->l("Scale"); ?></li>
                            <li data-content="#anim-sub-s-skew"><?php echo $modules->l("Skew"); ?></li>
                            <li data-content="#anim-sub-s-mask"><?php echo $modules->l("Masking"); ?></li>
                        </ul>

                        <!-- MASKING IN -->							
                        <span id="anim-sub-s-mask" class="rs-layer-toolbar-box" style="display:none;border:none;">
                            <span class="mask-is-available">
                                <i class="rs-mini-layer-icon eg-icon-scissors rs-toolbar-icon"></i>
                                <input type="checkbox" id="masking-start" name="masking-start" class="rs-inoutanimationfield tp-moderncheckbox"/>
                                <span class="rs-layer-toolbar-space"></span>
                            </span>
                            <span class="mask-not-available">
                                <strong><?php echo $modules->l('Mask is not available due Style Transitions. Please remove any Rotation, Scale or skew effect form Idle and Hover settings'); ?></strong>
                            </span>
                            <span class="mask-start-settings">
                                <!-- MASK X OFFSET -->
                                <i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon "  style="margin-right:8px"></i>								
                                <input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="mask_anim_xstart" name="mask_anim_xstart" value="0" data-reverse="on" data-selects="0||Random||Custom||Stage Left||Stage Right||-100%||100%||-175%||175%" data-svalues ="0||{-50,50}||50||stage_left||stage_right||[-100%]||[100%]||[-175%]||[175%]" data-icons="minus||shuffle||wrench||right||left||filter||filter||filter||filter">
                                <span class="rs-layer-toolbar-space"></span>
                                <!-- MASK Y OFFSET -->
                                <i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon "  style="margin-right:4px"></i>
                                <input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="mask_anim_ystart" name="mask_anim_ystart" value="0" data-reverse="on" data-selects="0||Random||Custom||Stage Top||Stage Bottom||-100%||100%||-175%||175%" data-svalues ="0||{-5,50}||50||stage_top||stage_bottom||[-100%]||[100%]||[-175%]||[175%]" data-icons="minus||shuffle||wrench||down||up||filter||filter||filter||filter">
                                <span class="rs-layer-toolbar-space"></span>									
                            </span>					
                        </span>


                        <span id="anim-sub-s-offset" class="rs-layer-toolbar-box" style="border:none;">
                            <!-- X OFFSET -->
                            <i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon "  style="margin-right:8px"></i>								
                            <input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_anim_xstart" name="layer_anim_xstart" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom||Stage Left||Stage Right||Stage Center||-100%||100%||-175%||175%" data-svalues ="inherit||{-50,50}||50||left||right||center||[-100%]||[100%]||[-175%]||[175%]" data-icons="export||shuffle||wrench||right||left||cancel||filter||filter||filter||filter">
                            <span class="rs-layer-toolbar-space"></span>
                            <!-- Y OFFSET -->
                            <i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon "  style="margin-right:4px"></i>
                            <input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_anim_ystart" name="layer_anim_ystart" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom||Stage Top||Stage Bottom||Stage Middle||-100%||100%||-175%||175%" data-svalues ="inherit||{-5,50}||50||top||bottom||middle||[-100%]||[100%]||[-175%]||[175%]" data-icons="export||shuffle||wrench||down||up||cancel||filter||filter||filter||filter">
                            <span class="rs-layer-toolbar-space"></span>
                            <!-- Z OFFSET -->
                            <i class="rs-mini-layer-icon rs-icon-zoffset rs-toolbar-icon "  style="margin-right:4px"></i>
                            <input type="text" data-suffix="px" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_anim_zstart" name="layer_anim_zstart" value="inherit" id="layer_anim_ystart" name="layer_anim_ystart" value="inherit" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-20,20}||20" data-icons="export||shuffle||wrench">
                        </span>

                        <span id="anim-sub-s-skew" class="rs-layer-toolbar-box" style="display:none;border:none;">
                            <!-- SKEW X -->
                            <i class="rs-mini-layer-icon rs-icon-skewx rs-toolbar-icon "  style="margin-right:8px"></i>
                            <input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field  "  id="layer_skew_xstart" name="layer_skew_xstart" value="inherit"  value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-25,25}||20" data-icons="export||shuffle||wrench">
                            <span class="rs-layer-toolbar-space"></span>
                            <!-- SKEW Y -->
                            <i class="rs-mini-layer-icon rs-icon-skewy rs-toolbar-icon "  style="margin-right:8px"></i>
                            <input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_skew_ystart" name="layer_skew_ystart" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-25,25}||20" data-icons="export||shuffle||wrench">
                        </span>


                        <span id="anim-sub-s-rotation" class="rs-layer-toolbar-box" style="display:none;border:none;">
                            <!--  X  ROTATE -->
                            <i class="rs-mini-layer-icon rs-icon-rotationx rs-toolbar-icon " ></i>
                            <input data-suffix="deg" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_anim_xrotate" name="layer_anim_xrotate" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-90,90}||45" data-icons="export||shuffle||wrench">
                            <span class="rs-layer-toolbar-space"></span>
                            <!--  Y ROTATE -->
                            <i class="rs-mini-layer-icon rs-icon-rotationy rs-toolbar-icon " ></i>
                            <input data-suffix="deg" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_anim_yrotate" name="layer_anim_yrotate" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-90,90}||45" data-icons="export||shuffle||wrench">
                            <span class="rs-layer-toolbar-space"></span>

                            <!--  Z ROTATE -->
                            <i class="rs-mini-layer-icon rs-icon-rotationz rs-toolbar-icon " ></i>
                            <input data-suffix="deg" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_anim_zrotate" name="layer_anim_zrotate" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-360,360}||45" data-icons="export||shuffle||wrench">

                        </span>

                        <span id="anim-sub-s-scale" class="rs-layer-toolbar-box" style="display:none;border:none;">
                            <!-- SCALE X -->
                            <i class="rs-mini-layer-icon rs-icon-scalex rs-toolbar-icon "  style="margin-right:8px"></i>
                            <input data-suffix="" data-steps="0.01" data-min="0" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_scale_xstart" name="layer_scale_xstart" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{0,1}||0.5" data-icons="export||shuffle||wrench">
                            <span class="rs-layer-toolbar-space"></span>
                            <!-- SCALE Y -->
                            <i class="rs-mini-layer-icon rs-icon-scaley rs-toolbar-icon " style="margin-right:8px"></i>
                            <input data-suffix="" data-steps="0.01" data-min="0" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field " id="layer_scale_ystart" name="layer_scale_ystart" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{0,1}||0.5" data-icons="export||shuffle||wrench">
                        </span>

                        <span id="anim-sub-s-opacity" class="rs-layer-toolbar-box" style="display:none;border:none;">
                            <!-- OPACITY -->
                            <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon "  style="margin-right:8px"></i>
                            <input data-suffix="" data-steps="0.05" data-min="0" data-max="1" type="text" style="width:100px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field " id="layer_opacity_start" name="layer_opacity_start" value="inherit" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{0,1}||0.5" data-icons="export||shuffle||wrench">
                        </span>
                    </div>																
                </div>				
                <div style="clear:both; display:block;"></div>


            </div>

            <!-- END TRANSITIONS -->
            <p style="margin:0;">
                <span class="rs-layer-toolbar-box endanim_mainwrapper">
                    <i class="rs-icon-outanim rs-toolbar-icon " style="z-index:100; position:relative;"></i>
                    <span id="endanim_wrapper" style="z-index:50">
                        <span id="endanim_timerunnerbox"></span>
                        <span id="endanim_timerunner"></span>
                    </span>
                </span>

                <span id="add_customanimation_out" title="<?php echo $modules->l("Advanced Settings"); ?>"><i style="width:40px;height:40px" class="rs-icon-plus-gray"></i></span>

                        <?php
                        $endanims = $operations->getArrEndAnimations();

                        ?>
                <span class="rs-layer-toolbar-box" style="">
                    <!-- ANIMATION DROP DOWN -->
                    <i class="rs-mini-layer-icon rs-icon-transition rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation Template"); ?>"></i>
                    <select class="rs-inoutanimationfield rs-layer-input-field" style="width:135px" id="layer$modules->lndanimation" name="layer$modules->lndanimation" class=" tipsy_enabled_top" title="<?php echo $modules->l("Animation Template"); ?>">
<?php
foreach ($endanims as $ahandle => $aname) {
    $dis = (in_array($ahandle, array('custom', "v5s", "v5", "v5e", "v4s", "v4", "v4e", "vss", "vs", "vse"))) ? ' disabled="disabled"' : '';
    echo '<option value="' . $ahandle . '"' . $dis . '>' . $aname['handle'] . '</option>';
}

?>
                    </select>
                    <span id="animout-template-handling-dd" class="clicktoshowmoresub" style="z-index:901">
                        <span id="animout-template-handling-dd-inner" class="clicktoshowmoresub_inner">
                            <span style="background:#ddd !important; padding-left:20px;margin-bottom:5px" class="css-template-handling-menupoint"><span><?php echo $modules->l("Template Options"); ?></span></span>
                            <span id="save-current-animout"   	class="save-current-animout css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-save-dark"></i><span><?php echo $modules->l("Save"); ?></span></span>
                            <span id="save-as-current-animout"   class="save-as-current-animout css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-save-dark"></i><span><?php echo $modules->l("Save As"); ?></span></span>
                            <span id="rename-current-animout" class="rename-current-animout css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-chooser-1"></i><span><?php echo $modules->l("Rename"); ?></span></span>
                            <span id="reset-current-animout"  class="reset-current-animout css-template-handling-menupoint"><i class="rs-mini-layer-icon rs-toolbar-icon rs-icon-2drotation"></i><span><?php echo $modules->l("Reset"); ?></span></span>
                            <span id="delete-current-animout" class="delete-current-animout css-template-handling-menupoint"><i style="background-size:10px 14px;" class="rs-mini-layer-icon rs-toolbar-icon rs-icon-delete"></i><span><?php echo $modules->l("Delete"); ?></span></span>
                        </span>
                    </span>

                    <span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>
                        <?php
                        $easings = $operations->getArrEndEasing();

                        ?>
                    <!-- EASING-->
                    <i class="rs-mini-layer-icon rs-icon-easing rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation Easing"); ?>"></i>
                    <select class="rs-inoutanimationfield rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Animation Easing"); ?>" style="width:135px"  id="layer$modules->lndeasing" name="layer$modules->lndeasing">
<?php
foreach ($easings as $ehandle => $ename) {
    echo '<option value="' . $ehandle . '">' . $ename . '</option>';
}

?>
                    </select>
                    <span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

                    <!-- ANIMATION END SPEED -->
                    <i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation Speed (in ms)"); ?>"></i>
                    <input type="text" style="width:60px; " class="rs-inoutanimationfield textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Animation Speed (in ms)"); ?>" id="layer$modules->lndspeed" name="layer$modules->lndspeed" value="">
                    <span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

                    <!-- SPLIT TEXT -->
                    <i class="rs-mini-layer-icon rs-icon-splittext rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Split Animaton Text (will not respect Html Markups !)"); ?>"></i>
                    <select id="layer$modules->lndsplit" name="layer$modules->lndsplit" class="rs-inoutanimationfield rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Split Animaton Text (will not respect Html Markups !)"); ?>" style="width:110px">
                        <option value="none" selected="selected"><?php echo $modules->l("No Split"); ?></option>
                        <option value="chars"><?php echo $modules->l("Char Based"); ?></option>
                        <option value="words"><?php echo $modules->l("Word Based"); ?></option>
                        <option value="lines"><?php echo $modules->l("Line Based"); ?></option>
                    </select>
                    <span class="rs-layer-toolbar-space" style="margin-right: 10px"></span>

                    <i class="rs-mini-layer-icon rs-icon-splittext-delay rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation Delay between Splitted Elements"); ?>"></i>
                    <input type="text" style="width:65px; " class="rs-inoutanimationfield textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Animation Delay between Splitted Elements"); ?>" id="layer$modules->lndsplitdelay" name="layer$modules->lndsplitdelay" value="10" disabled="disabled">
                </span>
            </p>
            <div id="extra$modules->lnd_animation_settings" class="extra_sub_settings_wrapper" style="margin:0; background:#fff; display:none;">
                <div class="anim-direction-wrapper" style="text-align: center">
                    <i class="rs-icon-schout rs-toolbar-icon" style="height:90px"></i>																
                </div>


                <div class="float_left" style="display:inline-block;padding:10px 0px;">
                    <div class="inner-settings-wrapper" >
                        <ul class="rs-layer-animation-settings-tabs">
                            <li data-content="#anim-sub-e-offset" class="selected"><?php echo $modules->l("Offset"); ?></li>
                            <li data-content="#anim-sub-e-opacity"><?php echo $modules->l("Opacity"); ?></li>
                            <li data-content="#anim-sub-e-rotation"><?php echo $modules->l("Rotation"); ?></li>
                            <li data-content="#anim-sub-e-scale"><?php echo $modules->l("Scale"); ?></li>
                            <li data-content="#anim-sub-e-skew"><?php echo $modules->l("Skew"); ?></li>
                            <li data-content="#anim-sub-e-mask"><?php echo $modules->l("Masking"); ?></li>
                        </ul>


                        <!-- MASKING IN -->							
                        <span id="anim-sub-e-mask" class="rs-layer-toolbar-box" style="display:none;border:none;">
                            <span class="mask-is-available">
                                <i class="rs-mini-layer-icon eg-icon-scissors rs-toolbar-icon"></i>
                                <input type="checkbox" id="masking-end" name="masking-end" class="rs-inoutanimationfield tp-moderncheckbox"/>
                                <span class="rs-layer-toolbar-space"></span>
                            </span>
                            <span class="mask-not-available">
                                <strong><?php echo $modules->l('Mask is not available due Style Transitions. Please remove any Rotation, Scale or skew effect form Idle and Hover settings'); ?></strong>
                            </span>
                            <span class="mask-end-settings">
                                <!-- MASK X OFFSET -->
                                <i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon "  style="margin-right:8px"></i>								
                                <input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="mask_anim_xend" name="mask_anim_xend" value="0" data-reverse="on" data-selects="Inherit||Random||Custom||Stage Left||Stage Right||Stage Center||-100%||100%||-175%||175%" data-svalues ="inherit||{-50,50}||50||left||right||center||[-100%]||[100%]||[-175%]||[175%]" data-icons="export||shuffle||wrench||right||left||cancel||filter||filter||filter||filter">
                                <span class="rs-layer-toolbar-space"></span>
                                <!-- MASK Y OFFSET -->
                                <i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon "  style="margin-right:4px"></i>
                                <input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="mask_anim_yend" name="mask_anim_yend" value="0" data-reverse="on" data-selects="Inherit||Random||Custom||Stage Top||Stage Bottom||Stage Middle||-100%||100%||-175%||175%" data-svalues ="inherit||{-5,50}||50||top||bottom||middle||[-100%]||[100%]||[-175%]||[175%]" data-icons="export||shuffle||wrench||down||up||cancel||filter||filter||filter||filter">
                                <span class="rs-layer-toolbar-space"></span>
                            </span>					
                        </span>


                        <span id="anim-sub-e-offset" class="rs-layer-toolbar-box" style="border:none;">
                            <!-- X OFFSET END-->
                            <i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon"  style="margin-right:8px"></i>								
                            <input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_anim_xend" name="layer_anim_xend" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom||Stage Left||Stage Right||Stage Center||-100%||100%||-175%||175%" data-svalues ="inherit||{-50,50}||50||left||right||center||[-100%]||[100%]||[-175%]||[175%]" data-icons="export||shuffle||wrench||right||left||cancel||filter||filter||filter||filter">
                            <span class="rs-layer-toolbar-space"></span>
                            <!-- Y OFFSET END-->
                            <i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon"  style="margin-right:4px"></i>
                            <input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_anim_yend" name="layer_anim_yend" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom||Stage Top||Stage Bottom||Stage Middle||-100%||100%||-175%||175%" data-svalues ="inherit||{-5,50}||50||top||bottom||middle||[-100%]||[100%]||[-175%]||[175%]" data-icons="export||shuffle||wrench||down||up||cancel||filter||filter||filter||filter">
                            <span class="rs-layer-toolbar-space"></span>
                            <!-- Z OFFSET END-->
                            <i class="rs-mini-layer-icon rs-icon-zoffset rs-toolbar-icon"  style="margin-right:4px"></i>
                            <input type="text" data-suffix="px" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_anim_zend" name="layer_anim_zend" value="inherit" value="0" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-20,20}||20" data-icons="export||shuffle||wrench">
                        </span>


                        <span id="anim-sub-e-skew" class="rs-layer-toolbar-box" style="display:none;border:none;">
                            <!-- SKEW X -->
                            <i class="rs-mini-layer-icon rs-icon-skewx rs-toolbar-icon"  style="margin-right:8px"></i>
                            <input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_skew_xend" name="layer_skew_xend" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-25,25}||20" data-icons="export||shuffle||wrench">
                            <span class="rs-layer-toolbar-space"></span>
                            <!-- SKEW Y -->
                            <i class="rs-mini-layer-icon rs-icon-skewy rs-toolbar-icon"  style="margin-right:8px"></i>
                            <input data-suffix="px" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_skew_yend" name="layer_skew_yend" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-25,25}||20" data-icons="export||shuffle||wrench">
                        </span>


                        <span id="anim-sub-e-rotation" class="rs-layer-toolbar-box" style="display:none;border:none;">
                            <!--  X  ROTATE -->
                            <i class="rs-mini-layer-icon rs-icon-rotationx rs-toolbar-icon" ></i>
                            <input data-suffix="deg" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_anim_xrotate_end" name="layer_anim_xrotate_end" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-90,90}||45" data-icons="export||shuffle||wrench">
                            <span class="rs-layer-toolbar-space"></span>
                            <!--  Y ROTATE END -->
                            <i class="rs-mini-layer-icon rs-icon-rotationy rs-toolbar-icon" ></i>
                            <input data-suffix="deg" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_anim_yrotate_end" name="layer_anim_yrotate_end" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-90,90}||45" data-icons="export||shuffle||wrench">
                            <span class="rs-layer-toolbar-space"></span>

                            <!--  Z ROTATE END -->
                            <i class="rs-mini-layer-icon rs-icon-rotationz rs-toolbar-icon" ></i>
                            <input data-suffix="deg" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field"  id="layer_anim_zrotate_end" name="layer_anim_zrotate_end" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{-360,360}||45" data-icons="export||shuffle||wrench">
                        </span>

                        <span id="anim-sub-e-scale" class="rs-layer-toolbar-box" style="display:none;border:none;">
                            <!-- SCALE X -->
                            <i class="rs-mini-layer-icon rs-icon-scalex rs-toolbar-icon "  style="margin-right:8px"></i>
                            <input data-suffix="" data-steps="0.01" data-min="0" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field "  id="layer_scale_xend" name="layer_scale_xend" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{0,1}||0.5" data-icons="export||shuffle||wrench">
                            <span class="rs-layer-toolbar-space"></span>
                            <!-- SCALE Y -->
                            <i class="rs-mini-layer-icon rs-icon-scaley rs-toolbar-icon " style="margin-right:8px"></i>
                            <input data-suffix="" data-steps="0.01" data-min="0" type="text" style="width:175px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field " id="layer_scale_yend" name="layer_scale_yend" value="inherit" data-reverse="on" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{0,1}||0.5" data-icons="export||shuffle||wrench">
                        </span>

                        <span id="anim-sub-e-opacity" class="rs-layer-toolbar-box" style="display:none;border:none;">
                            <!-- OPACITY -->
                            <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon "  style="margin-right:8px"></i>
                            <input data-suffix="" data-steps="0.05" data-min="0" data-max="1" type="text" style="width:100px;" class="input-deepselects rs-inoutanimationfield textbox-caption rs-layer-input-field " id="layer_opacity$modules->lnd" name="layer_opacity$modules->lnd" value="inherit" data-selects="Inherit||Random||Custom" data-svalues ="inherit||{0,1}||0.5" data-icons="export||shuffle||wrench">
                        </span>
                    </div>					
                </div>
                <div style="clear:both; display:block;"></div>

            </div>

        </div>

        <!-- LOOP ANIMATIONS -->
        <div class="layer-settings-toolbar" id="rs-loop-content-wrapper" style="display: none">
            <div class="topdddborder">
                <span class="rs-layer-toolbar-box" style="padding-right:26px">
                    <span><?php echo $modules->l("Loop"); ?></span>
                </span>

                <span class="rs-layer-toolbar-box" style="">
                    <i class="rs-mini-layer-icon rs-icon-transition rs-toolbar-icon"></i>
                    <select class="rs-loopanimationfield rs-layer-input-field" style="width:110px" id="layer_loop_animation" name="layer_loop_animation" class="">
                        <option value="none" selected="selected"><?php echo $modules->l('Disabled'); ?></option>
                        <option value="rs-pendulum"><?php echo $modules->l('Pendulum'); ?></option>
                        <option value="rs-rotate"><?php echo $modules->l('Rotate'); ?></option>
                        <option value="rs-slideloop"><?php echo $modules->l('Slideloop'); ?></option>
                        <option value="rs-pulse"><?php echo $modules->l('Pulse'); ?></option>
                        <option value="rs-wave"><?php echo $modules->l('Wave'); ?></option>
                    </select>
                </span>

                <!-- ANIMATION END SPEED -->
                <span id="layer_speed_wrapper" class="rs-layer-toolbar-box tipsy_enabled_top" title="<?php echo $modules->l("Loop Speed (sec) - 0.3 = 300ms"); ?>" style="display:none">
                    <i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Loop Speed (ms)"); ?>"></i>
                    <input type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field" id="layer_loop_speed" name="layer_loop_speed" value="2">
                    <span class="rs-layer-toolbar-space"></span>
                </span>
                        <?php
                        $easings = $operations->getArrEasing();

                        ?>

                <!-- EASING-->
                <span id="layer$modules->lasing_wrapper" class="rs-layer-toolbar-box tipsy_enabled_top" title="<?php echo $modules->l("Loop Easing"); ?>" style="display:none">
                    <i class="rs-mini-layer-icon rs-icon-easing rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Loop Easing"); ?>"></i>
                    <select class="rs-loopanimationfield  rs-layer-input-field" style="width:175px"  id="layer_loop$modules->lasing" name="layer_loop$modules->lasing">
<?php
foreach ($easings as $ehandle => $ename) {
    echo '<option value="' . $ehandle . '">' . $ename . '</option>';
}

?>
                    </select>
                </span>
            </div>
            <div>
                <!-- LOOP PARAMETERS -->
                <span class="rs-layer-toolbar-box" style="padding-right:18px; display:none;" id="layer_parameters_wrapper">
                    <span><?php echo $modules->l("Loop Parameters"); ?></span>
                </span>

                <!-- ROTATION PART -->
                <span id="layer_degree_wrapper" class="rs-layer-toolbar-box" style="display:none">
                    <!-- ROTATION START -->
                    <i class="rs-mini-layer-icon rs-icon-rotation-start rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("2D Rotation start deg."); ?>"></i>
                    <input data-suffix="deg" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("2D Rotation start deg."); ?>" id="layer_loop_startdeg" name="layer_loop_startdeg" value="-20">
                    <span class="rs-layer-toolbar-space"></span>
                    <!-- ROTATION END -->
                    <i class="rs-mini-layer-icon rs-icon-rotation-end rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("2D Rotation end deg."); ?>"></i>
                    <input data-suffix="deg" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("2D Rotation end deg."); ?>" id="layer_loop$modules->lnddeg" name="layer_loop$modules->lnddeg" value="20">
                </span>
                <!-- ORIGIN -->
                <span id="layer_origin_wrapper" class="rs-layer-toolbar-box" style="display:none">
                    <!-- ORIGIN X -->
                    <i class="rs-mini-layer-icon rs-icon-originx rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("2D Rotation X Origin"); ?>"></i>
                    <input data-suffix="%" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("2D Rotation X Origin"); ?>" id="layer_loop_xorigin" name="layer_loop_xorigin" value="50">
                    <span class="rs-layer-toolbar-space"></span>
                    <!-- ORIGIN Y -->
                    <i class="rs-mini-layer-icon rs-icon-originy rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("2D Rotation Y Origin"); ?>"></i>
                    <input data-suffix="%" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("2D Rotation Y Origin"); ?>" id="layer_loop_yorigin" name="layer_loop_yorigin" value="50">
                </span>
                <!-- X/Y START -->
                <span id="layer_x_wrapper" class="rs-layer-toolbar-box" style="display:none">
                    <span><?php echo $modules->l("Start"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Start X Offset"); ?>" style="margin-right:8px"></i>
                    <input data-suffix="px" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Start X Offset"); ?>" id="layer_loop_xstart" name="layer_loop_xstart" value="0">
                    <span class="rs-layer-toolbar-space"></span>
                    <i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Start Y Offset"); ?>" style="margin-right:4px"></i>
                    <input data-suffix="px" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Start Y Offset"); ?>" id="layer_loop_ystart" name="layer_loop_ystart" value="0">
                </span>
                <!-- X/Y END -->
                <span id="layer_y_wrapper" class="rs-layer-toolbar-box" style="display:none">
                    <span><?php echo $modules->l("End"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <i class="rs-mini-layer-icon rs-icon-xoffset rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("End X Offset"); ?>" style="margin-right:8px"></i>
                    <input data-suffix="px" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("End X Offset"); ?>" id="layer_loop_xend" name="layer_loop_xend" value="0">
                    <span class="rs-layer-toolbar-space"></span>
                    <i class="rs-mini-layer-icon rs-icon-yoffset rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("End Y Offset"); ?>" style="margin-right:4px"></i>
                    <input data-suffix="px" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("End Y Offset"); ?>" id="layer_loop_yend" name="layer_loop_yend" value="0">
                </span>

                <!-- ZOOM -->
                <span id="layer_zoom_wrapper" class="rs-layer-toolbar-box" style="display:none">
                    <span><?php echo $modules->l("Zoom Start"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <input type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Zoom Start"); ?>" id="layer_loop_zoomstart" name="layer_loop_zoomstart" value="1">
                    <span class="rs-layer-toolbar-space"></span>
                    <span><?php echo $modules->l("Zoom End"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <input type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Zoom End"); ?>" id="layer_loop_zoomend" name="layer_loop_zoomend" value="1">
                </span>
                <!-- ANGLE -->
                <span id="layer_angle_wrapper" class="rs-layer-toolbar-box" style="display:none">
                    <span><?php echo $modules->l("Angle"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <input type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Start Angle"); ?>" id="layer_loop_angle" name="layer_loop_angle" value="0">
                </span>
                <!-- RADIUS -->
                <span id="layer_radius_wrapper" class="rs-layer-toolbar-box" style="display:none">
                    <span><?php echo $modules->l("Radius"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <input data-suffix="px" type="text" style="width:90px;" class="rs-loopanimationfield  textbox-caption rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Radius of Rotation / Pendulum"); ?>" id="layer_loop_radius" name="layer_loop_radius" value="10">
                </span>
            </div>
        </div>

        <!-- LINK SETTINGS -->
        <div class="layer-settings-toolbar" id="rs-parallax-content-wrapper" style="display:none;">
                <?php
                if (RbGlobalObject::getVar('use_parallax') == "off") {
                    echo '<span class="rs-layer-toolbar-box">';
                    echo '<span class="rs-layer-toolbar-space"></span>';
                    echo '<i style="color:#c0392b">';
                    echo $modules->l("Parallax Feature in Slider Settings is deactivated, parallax will be ignored.");
                    echo '</i>';
                    echo '</span>';
                } else {

                    ?>

                <!-- PARALLAX LEVEL -->
                <span class="rs-layer-toolbar-box">
    <?php
    $ddd_label_text = "Parallax Depth";
    $ddd_no_parallax = "No Parallax";
    $parallaxisddd = RbGlobalObject::getVar('parallaxisddd');
    if ($parallaxisddd != "off") {
        $ddd_label_text = "3D Depth";
        $ddd_no_parallax = "Default 3D Depth";
    }

    $parallax_level = RbGlobalObject::getVar('parallax_level');

    ?>
                    <span><?php echo $modules->l($ddd_label_text);

                        ?></span>

                    <span class="rs-layer-toolbar-space"></span>
                    <select class="rs-layer-input-field" style="width:149px" id="parallax_level" name="parallax_level">																								
                        <option value="-"><?php echo $modules->l($ddd_no_parallax);

                        ?></option>
                        <option value="1">1 - (<?php echo $parallax_level[0];

    ?>%)</option>
                        <option value="2">2  - (<?php echo $parallax_level[1];

                        ?>%)</option>
                        <option value="3">3  - (<?php echo $parallax_level[2];

    ?>%)</option>
                        <option value="4">4  - (<?php echo $parallax_level[3];

                        ?>%)</option>
                        <option value="5">5  - (<?php echo $parallax_level[4];

                        ?>%)</option>
                        <option value="6">6  - (<?php echo $parallax_level[5];

    ?>%)</option>
                        <option value="7">7  - (<?php echo $parallax_level[6];

            ?>%)</option>
                        <option value="8">8  - (<?php echo $parallax_level[7];

            ?>%)</option>
                        <option value="9">9  - (<?php echo $parallax_level[8];

            ?>%)</option>
                        <option value="10">10  - (<?php echo $parallax_level[9];

            ?>%)</option>
                        <option value="11">11  - (<?php echo $parallax_level[10];

    ?>%)</option>
                        <option value="12">12  - (<?php echo $parallax_level[11];

            ?>%)</option>
                        <option value="13">13  - (<?php echo $parallax_level[12];

            ?>%)</option>
                        <option value="14">14  - (<?php echo $parallax_level[13];

            ?>%)</option>
                        <option value="15">15  - (<?php echo $parallax_level[14];

            ?>%)</option>
                    </select>
                </span>
    <?php
    if ($parallaxisddd != "off") {

        ?>
                    <!-- CLASSES -->
                    <span class="rs-layer-toolbar-box" id="put_layer_ddd_to_z">
                        <span><?php echo $modules->l("Attach to");

        ?></span>
                        <span class="rs-layer-toolbar-space"></span>
                        <select class="rs-layer-input-field" style="width:149px" id="parallax_layer_ddd_zlevel" name="parallax_layer_ddd_zlevel">									
                            <option value="layers"><?php echo $modules->l('Layers 3D Group');

        ?></option>							
                            <option value="bg"><?php echo $modules->l('Background 3D Group');

        ?></option>	
                        </select>
                    </span>
        <?php
    }
}

?>						
        </div>
        <script>
            jQuery('#parallax_level').on("change", function() {
                var sbi = jQuery(this),
                        v = sbi.find('option:selected').val();
                if (v == "-")
                    jQuery('#put_layer_ddd_to_z').show();
                else
                    jQuery('#put_layer_ddd_to_z').hide();
            });
            jQuery('#parallax_level').change();
        </script>

        <!-- LINK SETTINGS -->
        <div class="layer-settings-toolbar" id="rs-action-content-wrapper" style="display:none">		

            <div style="padding:5px 20px 5px">

                <span id="triggered-element-behavior">
                    <span class="rs-layer-toolbar-box">
                        <span><?php echo $modules->l("Animation Timing"); ?></span>
                        <span class="rs-layer-toolbar-space"></span>
                        <select id="layer-animation-overwrite" name="layer-animation-overwrite" class="rs-layer-input-field" style="width:150px">
                            <option value="default" selected="selected"><?php echo $modules->l("In and Out Animation Default"); ?></option>							
                            <option value="waitout"><?php echo $modules->l("In Animation Default and Out Animation Wait for Trigger"); ?></option>
                            <option value="wait"><?php echo $modules->l("Wait for Trigger"); ?></option>
                        </select>
                    </span>
                    <span class="rs-layer-toolbar-box">
                        <span><?php echo $modules->l("Trigger Memory"); ?></span>
                        <span class="rs-layer-toolbar-space"></span>
                        <select id="layer-tigger-memory" name="layer-tigger-memory" class="rs-layer-input-field" style="width:150px">
                            <option value="reset" selected="selected"><?php echo $modules->l("Reset Animation and Trigger States every loop"); ?></option>
                            <option value="keep"><?php echo $modules->l("Keep last selected State"); ?></option>

                        </select>
                    </span>
                </span>	

                <ul class="layer_action_table">

                    <!-- actions will be added here -->


                    <li class="layer_action_row layer_action_add_template">
                        <div class="add-action-row">
                            <i class="eg-icon-plus"></i>
                        </div>
                    </li>
                </ul>

                <script>
                    jQuery(document).ready(function() {
                        jQuery('body').on('click', '.remove-action-row', function() {
                            UniteLayersRb.removeAction(jQuery(this));
                        });

                        jQuery('.add-action-row').click(function() {
                            UniteLayersRb.addLayerActions();
                        });
                    });

                </script>
            </div>

        </div>

        <!-- ATTRIBUTE SETTINGS -->
        <div class="layer-settings-toolbar" id="rs-attribute-content-wrapper" style="display:none;">
            <div class="topdddborder">

                <!-- ID -->
                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("ID"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <input type="text" style="width:105px;" class="textbox-caption rs-layer-input-field" id="layer_id" name="layer_id" value="">
                </span>

                <!-- CLASSES -->
                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("Classes"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <input type="text" style="width:105px;" class="textbox-caption rs-layer-input-field" id="layer_classes" name="layer_classes" value="">
                </span>

                <!-- TITLE -->
                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("Title"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <input type="text" style="width:105px;" class="textbox-caption rs-layer-input-field" id="layer_title" name="layer_title" value="">
                </span>

                <!-- REL -->
                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("Rel"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <input type="text" style="width:105px;" class="textbox-caption rs-layer-input-field" id="layer_rel" name="layer_rel" value="">
                    <span class="rs-layer-toolbar-space"></span>
                </span>

                <!-- ALT -->
                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("Alt"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <select id="layer_alt_option" name="layer_alt_option" class="rs-layer-input-field" style="width:100px">
                        <option value="media_library"><?php echo $modules->l('From Media Library'); ?></option>
                        <option value="file_name"><?php echo $modules->l('From Filename'); ?></option>
                        <option value="custom"><?php echo $modules->l('Custom'); ?></option>
                    </select>
                    <input type="text" style="display: none; width:105px;" class="textbox-caption rs-layer-input-field" id="layer_alt" name="layer_alt" value="">
                </span>


                <!-- INTERNAL CLASSES -->
                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("Internal Classes:"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
<?php
//ONLY FOR DEBUG!!

?>

                    <input type="hidden" style="width:105px;" class="textbox-caption rs-layer-input-field" id="internal_classes" name="internal_classes" value="">
                    <span class="rs-internal-class-wrapper"></span>
                    <span class="rs-layer-toolbar-space"></span>
                </span>

<?php
//ONLY FOR DEBUG!!

?>

            </div>
        </div>


        <!-- VISIBILITY SETTINGS -->
        <div class="layer-settings-toolbar" id="rs-visibility-content-wrapper" style="display:none">
            <div class="topdddborder">
                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("Visibility on Devices"); ?></span>
                </span>
                <span class="rs-layer-toolbar-box">
                    <span class="rb-visibility-on-sizes">
                        <i class="rs-mini-layer-icon rs-icon-desktop rs-toolbar-icon"></i>
                        <input type="checkbox" id="visible-desktop" name="visible-desktop" class="tp-moderncheckbox"/>
                        <span class="rs-layer-toolbar-space"></span>

                        <i class="rs-mini-layer-icon rs-icon-laptop rs-toolbar-icon"></i>
                        <input type="checkbox" id="visible-notebook" name="visible-notebook" class="tp-moderncheckbox"/>
                        <span class="rs-layer-toolbar-space"></span>

                        <i class="rs-mini-layer-icon rs-icon-tablet rs-toolbar-icon"></i>
                        <input type="checkbox" id="visible-tablet" name="visible-tablet" class="tp-moderncheckbox"/>
                        <span class="rs-layer-toolbar-space"></span>

                        <i class="rs-mini-layer-icon rs-icon-phone rs-toolbar-icon"></i>
                        <input type="checkbox" id="visible-mobile" name="visible-mobile" class="tp-moderncheckbox"/>
                    </span>
                </span>

                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("Hide 'Under' Width"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <input type="checkbox" id="layer_hidden" class="tp-moderncheckbox" name="layer_hidden">
                </span>

                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("Only on Slider Hover"); ?></span>
                    <span class="rs-layer-toolbar-space"></span>
                    <input type="checkbox" id="layer_on_slider_hover" class="tp-moderncheckbox" name="layer_on_slider_hover">
                </span>
            </div>
        </div>

        <!-- BEHAVIOR SETTINGS -->
        <div class="layer-settings-toolbar" id="rs-behavior-content-wrapper" style="display:none">
            <div class="topdddborder">

                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("Auto Responsive"); ?></span>
                    <span class="rs-layer-toolbar-space" style="margin-right:18px"></span>
                    <input type="checkbox" id="layer_resize-full" class="tp-moderncheckbox" name="layer_resize-full" checked="checked">
                </span>

                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("Child Elements Responsive"); ?></span>
                    <span class="rs-layer-toolbar-space" style="margin-right:18px"></span>
                    <input type="checkbox" id="layer_resizeme" class="tp-moderncheckbox" name="layer_resizeme" checked="checked">
                </span>

                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("Align"); ?></span>
                    <span class="rs-layer-toolbar-space" style="margin-right:18px"></span>
                    <select id="layer_align_base" name="layer_align_base" class="rs-layer-input-field" style="width:100px">
                        <option value="grid" selected="selected"><?php echo $modules->l("Grid Based"); ?></option>
                        <option value="slide"><?php echo $modules->l("Slide Based"); ?></option>							
                    </select>
                </span>

                <span class="rs-layer-toolbar-box">
                    <span><?php echo $modules->l("Responsive Offset"); ?></span>
                    <span class="rs-layer-toolbar-space" style="margin-right:18px"></span>
                    <input type="checkbox" id="layer_resp_offset" class="tp-moderncheckbox" name="layer_resp_offset" checked="checked">
                </span>

            </div>

            <span class="rs-layer-toolbar-box rs-lazy-load-images-wrap">
                <span><?php echo $modules->l("Lazy Loading"); ?></span>
                <span class="rs-layer-toolbar-space"></span>
                <select id="layer-lazy-loading" name="layer-lazy-loading" class="rs-layer-input-field" style="width:150px">
                    <option value="auto" selected="selected"><?php echo $modules->l("Default Setting"); ?></option>
                    <option value="force"><?php echo $modules->l("Force Lazy Loading"); ?></option>
                    <option value="ignore"><?php echo $modules->l("Ignore Lazy Loading"); ?></option>
                </select>
            </span>
            <span class="rs-layer-toolbar-box rs-lazy-load-images-wrap">
                <span><?php echo $modules->l("Source Type"); ?></span>
                <span class="rs-layer-toolbar-space"></span>
                <select id="layer-image-size" name="layer-image-size" class="rs-layer-input-field" style="width:150px">
                    <option value="auto" selected="selected"><?php echo $modules->l("Default Setting"); ?></option>
                    <?php
                    $img_sizes = RbGlobalObject::getVar('img_sizes');
                    foreach ($img_sizes as $imghandle => $imgSize) {
                        echo '<option value="' . $imghandle . '">' . $imgSize . '</option>';
                    }

                    ?>
                </select>
            </span>			

        </div>

        <!-- STATIC LAYERS SETTINGS -->
        <div class="layer-settings-toolbar" id="rs-static-content-wrapper" style="display:none">
                    <?php
                    $show_static = 'display: none;';
                    if ($slide->isStaticSlide()) {
                        $show_static = '';
                    }
                    $isTemplate = $slider->getParam("template", "false");

                    if ($isTemplate == "true") {

                        ?>
                <span class="rs-layer-toolbar-box">
    <?php echo $modules->l('Static Layers will be shown on every slide in template sliders');

    ?>
                </span>
                        <?php
                    }

                    ?>

            <span class="rs-layer-toolbar-box" id="layer_static_wrapper" <?php echo ($isTemplate == "true") ? ' style="display: none;"' : ''; ?>>

                <span><?php echo $modules->l("Start on Slide"); ?></span>
                <span class="rs-layer-toolbar-space"></span>
                <select id="layer_static_start" name="static_start">
                    <?php
                    $arrSlideNames = RbGlobalObject::getVar('arrSlideNames');
                    if (!empty($arrSlideNames)) {
                        $si = 1;
                        end($arrSlideNames);
                        //fetch key of the last element of the array.
                        $lastElementKey = key($arrSlideNames);
                        foreach ($arrSlideNames as $sid => $sparams) {
                            if ($lastElementKey == $sid) {
                                break;
                            } //break on the last element

                            ?>
                            <option value="<?php echo $si;

                            ?>"><?php echo $si;

                            ?></option>
            <?php
            $si++;
        }
    } else {

        ?>
                        <option value="-1">-1</option>
        <?php
    }

    ?>
                </select>
                <span class="rs-layer-toolbar-space"></span>
                <span><?php echo $modules->l("End on Slide"); ?></span>
                <span class="rs-layer-toolbar-space"></span>
                <select id="layer_static$modules->lnd" name="static$modules->lnd">
<?php
if (!empty($arrSlideNames)) {
    $si = 1;
    foreach ($arrSlideNames as $sid => $sparams) {

        ?>
                            <option value="<?php echo $si;

        ?>"><?php echo $si;

        ?></option>
                             <?php
                             $si++;
                         }
                     } else {

                         ?>
                        <option value="-1">-1</option>
    <?php
}

?>
                </select>
            </span>
        </div>
    </form>
    <!-- END OF AMAZING TOOLBAR -->
                     <?php
                     $slidertype = $slider->getParam("slider_type", "fullwidth");
                     $style = RbGlobalObject::getVar('style');
                     $style .= ' margin: 0 auto;';
                     $maxbgwidth = RbGlobalObject::getVar('maxbgwidth');
                     $tempwidth_jq = $maxbgwidth;
                     RbGlobalObject::setVar('tempwidth_jq', $tempwidth_jq);

                     $style_wrapper = RbGlobalObject::getVar('style_wrapper');
                     if ($slidertype == 'fullwidth' || $slidertype == 'fullscreen') {
                         $style_wrapper .= ' width: 100%;';
                         $maxbgwidth = "";
                     } else {
                         $style_wrapper .= $style;
                     }
                     $settings = RbGlobalObject::getVar('settings');
                     $hor_lines = RbSliderFunctions::getVal($settings, "hor_lines", "");
                     $ver_lines = RbSliderFunctions::getVal($settings, "ver_lines", "");

                     ?>
    <script>
        var __slidertype = "<?php echo $slidertype; ?>";
    </script>
    <div id="thelayer-editor-wrapper">
        <!-- THE EDITOR PART -->
        <div id="horlinie"><div id="horlinetext">0</div></div>
        <div id="verlinie"><div id="verlinetext">0</div></div>
        <div id="hor-css-linear">
            <ul class="linear-texts"></ul>
            <div class="helplines-offsetcontainer">
<?php
if (!empty($hor_lines)) {
    foreach ($hor_lines as $lines) {

        ?>
                        <div class="helplines" style="position:absolute;width:1px;background:#4AFFFF;left:<?php echo $lines;

        ?>;top:0px"><i class="helpline-drag eg-icon-move"></i><i class="helpline-remove eg-icon-cancel"></i></div>
                    <?php
                }
            }

            ?>
            </div>
        </div>
        <div id="ver-css-linear">
            <ul class="linear-texts"></ul>
            <div class="helplines-offsetcontainer">
            <?php
            if (!empty($ver_lines)) {
                foreach ($ver_lines as $lines) {

                    ?>
                        <div class="helplines" style="position:absolute;height:1px;background:#4AFFFF;top:<?php echo $lines;

                    ?>;left:0px"><i class="helpline-drag eg-icon-move"></i><i class="helpline-remove eg-icon-cancel"></i></div>
                        <?php
                    }
                }

                ?>
            </div>
        </div>

        <div id="hor-css-linear-cover-left"></div>
        <div id="hor-css-linear-cover-right"></div>
        <div id="ver-css-linear-cover"></div>
            <?php
            //show/hide layers of specific slides
            $add_static = 'false';
            if ($slide->isStaticSlide()) {
                $add_static = 'true';
            }

            ?>
        <div id="top-toolbar-wrapper">
            <div id="add-layer-selector-container">
                <a href="javascript:void(0)" id="button_add_any_layer" class="add-layer-button-any tipsy_enabled_top"><i class="rs-icon-addlayer2"></i><span class="add-layer-txt"><?php echo $modules->l("Add Layer") ?></span></a>
                <div id="add-new-layer-container-wrapper">
                    <div id="add-new-layer-container">
                        <a href="javascript:void(0)" id="button_add_layer" data-isstatic="<?php echo $add_static; ?>" class="add-layer-button" >
                            <i class="material-icons">text_format</i>
                            <span class="add-layer-txt"><?php echo $modules->l("Text/Html") ?></span>
                        </a>
                        <a href="javascript:void(0)" id="button_add_layer_image" data-isstatic="<?php echo $add_static; ?>" class="add-layer-button" >
                            <i class="material-icons">image</i>
                            <span class="add-layer-txt"><?php echo $modules->l("Image") ?></span>
                        </a>
                        <a href="javascript:void(0)" id="button_add_layer_video" data-isstatic="<?php echo $add_static; ?>" class="add-layer-button" >
                            <i class="material-icons">videocam</i>
                            <span class="add-layer-txt"><?php echo $modules->l("Video") ?></span>
                        </a>
                        <a href="javascript:void(0)" id="button_add_layer_button" data-isstatic="<?php echo $add_static; ?>" class="add-layer-button" >
                            <i class="material-icons">panorama_fish_eye</i>
                            <span class="add-layer-txt"><?php echo $modules->l("Button") ?></span>
                        </a>
                        <a href="javascript:void(0)" id="button_add_layer_shape" data-isstatic="<?php echo $add_static; ?>" class="add-layer-button" >
                            <i class="material-icons">share</i>
                            <span class="add-layer-txt"><?php echo $modules->l("Shape") ?></span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- DESKTOP / TABLET / PHONE SIZING -->
                        <?php
                        $_width = $slider->getParam('width', 1240);
                        $_width_notebook = $slider->getParam('width_notebook', 1024);
                        $_width_tablet = $slider->getParam('width_tablet', 778);
                        $_width_mobile = $slider->getParam('width_mobile', 480);

                        $_height = $slider->getParam('height', 868);
                        $_height_notebook = $slider->getParam('height_notebook', 768);
                        $_height_tablet = $slider->getParam('height_tablet', 960);
                        $_height_mobile = $slider->getParam('height_mobile', 720);
                        $adv_resp_sizes = RbGlobalObject::getVar('adv_resp_sizes');
                        $enable_custom_size_notebook = RbGlobalObject::getVar('enable_custom_size_notebook');
                        $enable_custom_size_tablet = RbGlobalObject::getVar('enable_custom_size_tablet');
                        $enable_custom_size_iphone = RbGlobalObject::getVar('enable_custom_size_iphone');

                        if ($adv_resp_sizes === true) {

                            ?>				
                <span id="rs-edit-layers-on-btn">
                    <div data-val="desktop" class="rs-slide-device_selector rs-slide-ds-desktop selected"></div>
    <?php if ($enable_custom_size_notebook == 'on') {

        ?><div data-val="notebook" class="rs-slide-device_selector rs-slide-ds-notebook"></div><?php
    }

    ?>
    <?php if ($enable_custom_size_tablet == 'on') {

        ?><div data-val="tablet" class="rs-slide-device_selector rs-slide-ds-tablet"></div><?php
    }

    ?>
    <?php if ($enable_custom_size_iphone == 'on') {

        ?><div data-val="mobile" class="rs-slide-device_selector rs-slide-ds-mobile"></div><?php
    }

    ?>
                </span>
    <?php
}

?>
            <div id="quick-layer-selector-container">
                <div class="current-active-main-toolbar">
                    <div id="layer-short-toolbar" class="layer-toolbar-li">							
                        <span id="button_show_all_layer" class="layer-short-tool rbdarkgray"><i class="eg-icon-menu"></i>
                            <input class="nolayerselectednow" type="text" id="the_current-editing-layer-title"  disabled name="the_current-editing-layer-title" value="No Layer Selected">
                        </span>

                        <span style="display:none;" id="button_change_video_settings" class="layer-short-tool rbblue"><i class="eg-icon-pencil"></i></span>		
                        <span id="layer-short-tool-placeholder-a" class="layer-short-tool rbdarkgray"></span>
                        <span id="layer-short-tool-placeholder-b" class="layer-short-tool rbdarkgray"></span>					
                        <span style="display:none" id="button$modules->ldit_layer" class="layer-short-tool rbblue"><i class="eg-icon-pencil"></i></span>

                        <span style="display:none;" id="button_change_image_source" class="layer-short-tool rbblue"><i class="eg-icon-pencil"></i></span>		

                        <span style="display:none" id="button_reset_size" class="layer-short-tool rbblue"><i class="eg-icon-resize-normal"></i></span>				
                        <span id="button_delete_layer" class="layer-short-tool rbred"><i class="rs-lighttrash"></i></span>
                        <span id="button_duplicate_layer" class="layer-short-tool rbyellow" data-isstatic="<?php echo $add_static; ?>"><i class="rs-lightcopy"></i></span>				
                        <span style="display:none;"  id="tp-addiconbutton" class="layer-short-tool rbblue"><i class="eg-icon-th"></i></span>
<?php
$slider_type = RbGlobalObject::getVar('slider_type');
if ($slider_type != 'gallery') {

    ?>
                            <span id="linkInsertTemplate"  style="display:none" class="layer-short-tool rbyellow"><i class="eg-icon-filter"></i></span>					
            <?php }

        ?>
                        <span style="display:none" id="hide_layer_content$modules->lditor"  class="layer-short-tool rbgreen" ><i class="eg-icon-check"></i></span>					
                        <span class="quick-layer-view layer-short-tool rbdarkgray"><i class="eg-icon-eye"></i></span>
                        <span class="quick-layer-lock layer-short-tool rbdarkgray"><i class="eg-icon-lock-open"></i></span>										
                        <div style="clear:both;display:block"></div>
                    </div>
                </div>
                <div id="quick-layers-wrapper" style="display:none">				
                    <div id="quick-layers">	

                        <div class="tp-clearfix"></div>
                        <ul class="quick-layers-list">
                            <li class="nolayersavailable"><div class="add-layer-button"><?php echo $modules->l("Slide contains no layers") ?></div></li>
                        </ul>
                    </div>
                </div>
                <!-- TEXT / IMAGE INPUT FIELD CONTENT -->
                <form name="form_layers" class="form_layers">
                    <div id="layer_text_holder">
                        <div id="layer_text_wrapper" style="display:none">
                            <div class="layer_text_wrapper_header">					
                                <span style="display:none; font-weight:600;" class="layer-content-title-b"><?php echo $modules->l("Image Layer Title "); ?><span style="margin-left:5px;font-size:11px; font-style: italic;"><?php echo $modules->l("(only for internal usage):"); ?></span> </span>					
                            </div>
                            <textarea id="layer_text" class="area-layer-params" name="layer_text" ></textarea>
                        </div>
                    </div>
                </form>
                <script>
                    jQuery('#button_show_all_layer i, #button_show_all_layer').click(function() {

                        var camt = jQuery('.current-active-main-toolbar'),
                                t = jQuery('#button_show_all_layer'),
                                b = jQuery(this);

                        if (b.hasClass("eg-icon-up") || b.hasClass("eg-icon-menu") || jQuery('#the_current-editing-layer-title').hasClass("nolayerselectednow")) {
                            if (camt.hasClass("opened")) {
                                jQuery('#quick-layers-wrapper').slideUp(300);
                                camt.removeClass("opened");
                                t.find('i').removeClass("eg-icon-up").addClass("eg-icon-menu");
                            } else {
                                jQuery('#quick-layers-wrapper').slideDown(300);
                                camt.addClass("opened");
                                t.find('i').addClass("eg-icon-up").removeClass("eg-icon-menu");
                                jQuery('.quick-layers-list').perfectScrollbar("update");

                                // KRIKI
                                jQuery('#layer_text_wrapper').hide();
                                jQuery('#layer_text_wrapper').removeClass('currently$modules->lditing_txt');
                                UniteLayersRb.showHideContentEditor(false);

                            }
                        }
                        return false;
                    })
                </script>
            </div>
        </div>
<?php
$divbgminwidth = RbGlobalObject::getVar('divbgminwidth');
$class_wrapper = RbGlobalObject::getVar('class_wrapper');
$divLayersClass = RbGlobalObject::getVar('divLayersClass');
$divLayersWidth = RbGlobalObject::getVar('$divLayersWidth');

?>

        <div id="divLayers-wrapper" style="overflow: hidden;<?php echo $style . $maxbgwidth; ?>" class="slide_wrap_layers" >
            <div id="divbgholder" style="<?php echo $style_wrapper . $divbgminwidth . $maxbgwidth ?>" class="<?php echo $class_wrapper; ?>">
                <div class="oldslotholder" style="overflow:hidden;width:100%;height:100%;position:absolute;top:0px;left:0px;z-index:0;">
                    <div class="tp-bgimg defaultimg"></div>
                </div>
                <div class="slotholder" style="overflow:hidden;width:100%;height:100%;position:absolute;top:0px;left:0px;z-index:1">
                    <div class="tp-bgimg defaultimg" style="width: 100%;height: 100%;position: absolute;top:0px;left:0px; <?php echo $style_wrapper . $divbgminwidth . $maxbgwidth ?>"></div>
                </div>
                <div id="divLayers" class="<?php echo $divLayersClass ?>" style="<?php echo $style . $divLayersWidth; ?>">
                    <div class="slide_layers_border"></div>
                </div>
            </div>

        </div>






        <!-- ADD LAYERS, REMOVE LAYERS, DUPLICATE LAYERS -->
        <div id="layer-settings-toolbar-bottom" class="layer-settings-toolbar-bottom">
            <select style="display:none" name="rs-edit-layers-on" id="rs-edit-layers-on">
                <option value="desktop"><?php echo $modules->l('Desktop'); ?></option>
                <option value="notebook"><?php echo $modules->l('Notebook'); ?></option>
                <option value="tablet"><?php echo $modules->l('Tablet'); ?></option>
                <option value="mobile"><?php echo $modules->l('Mobile'); ?></option>
            </select>
            <script type="text/javascript">
                jQuery('#add-layer-selector-container').hover(function() {
                    jQuery('#add-new-layer-container-wrapper').show();
                }, function() {
                    jQuery('#add-new-layer-container-wrapper').hide();
                });

                jQuery('#add-layer-minimiser').click(function() {
                    var t = jQuery(this);
                    if (t.hasClass("closed")) {
                        t.removeClass("closed");
                        punchgs.TweenLite.to(jQuery('#add-layer-selector-container'), 0.4, {autoAlpha: 1, rotationY: 0, transformOrigin: "0% 50%", ease: punchgs.Power3.easeInOut});
                        punchgs.TweenLite.to(jQuery('#quick-layer-selector-container'), 0.4, {autoAlpha: 1, rotationY: 0, transformOrigin: "0% 50%", ease: punchgs.Power3.easeInOut});
                    } else {
                        t.addClass("closed");
                        punchgs.TweenLite.to(jQuery('#add-layer-selector-container'), 0.4, {autoAlpha: 0, rotationY: -90, transformOrigin: "0% 50%", ease: punchgs.Power3.easeInOut});
                        punchgs.TweenLite.to(jQuery('#quick-layer-selector-container'), 0.4, {autoAlpha: 0, rotationY: -90, transformOrigin: "0% 50%", ease: punchgs.Power3.easeInOut});
                    }
                    return false;
                });

                jQuery('#add-new-layer-container a').click(function() {
                    jQuery('#add-new-layer-container-wrapper').hide();
                    return true;
                });

<?php
if ($adv_resp_sizes === true) {

    ?>
                    var rb_adv_resp_sizes = true;
                    var rb_sizes = {
                        'desktop': [<?php echo $_width . ', ' . $_height;

    ?>],
                        'notebook': [<?php echo $_width_notebook . ', ' . $_height_notebook;

    ?>],
                        'tablet': [<?php echo $_width_tablet . ', ' . $_height_tablet;

    ?>],
                        'mobile': [<?php echo $_width_mobile . ', ' . $_height_mobile;

    ?>]
                    };

    <?php
} else {

    ?>
                    var rb_adv_resp_sizes = false;
    <?php
}

?>
            </script>

            <!-- HELPER GRID ON/OFF -->
            <span style="float:right;display:inline-block;line-height:40px;vertical-align: middle; margin-right:30px;">
                <span class="setting_text_3"><?php echo $modules->l("Helper Grid:"); ?></span>
                <select name="rs-grid-sizes" id="rs-grid-sizes">
                    <option value="1"><?php echo $modules->l("Disabled"); ?></option>
                    <option value="10">10 x 10</option>
                    <option value="25">25 x 25</option>
                    <option value="50">50 x 50</option>
                    <option value="custom"><?php echo $modules->l('Custom'); ?></option>
                </select>
                <span class="rs-layer-toolbar-space" style="margin-right:20px"></span>
                <span class="setting_text_3"><?php echo $modules->l("Snap to:"); ?></span>
                <select name="rs-grid-snapto" id="rs-grid-snapto" >
                    <option value=""><?php echo $modules->l("None"); ?></option>
                    <option value=".helplines"><?php echo $modules->l("Help Lines"); ?></option>
                    <option value=".slide_layer"><?php echo $modules->l("Layers"); ?></option>
                </select>
            </span>
        </div>
    </div>

    <!-- THE CURRENT TIMER FOR LAYER -->
    <div style="direction:ltr !important">
        <div id="mastertimer-wrapper" class="layer_sortbox">
            <div id="timline-manual-dialog" style="display:none">
                <!-- ANIMATION START TIME -->

                <label style="width:70px"><?php echo $modules->l("Start Time"); ?></label>
                <i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation Start Time (ms)"); ?>"></i>
                <input type="text" style="width:90px;" class="textbox-caption rs-layer-input-field" id="clayer_start_time" name="clayer_start_time" value="0">
                <span class="over-ms">ms</span>
                </span>
                <span class="rs-layer-toolbar-space" style="margin-right:20px"></span>

                <!-- ANIMATION END TIME -->
                <span>
                    <label style="width:70px"><?php echo $modules->l("End Time"); ?></label>
                    <i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation End Time (ms)"); ?>"></i>
                    <input type="text" style="width:90px;" class="textbox-caption rs-layer-input-field" id="clayer$modules->lnd_time" name="clayer$modules->lnd_time" value="0">
                    <span class="over-ms">ms</span>
                </span>


                <span class="tp-clearfix"></span>



                <!-- ANIMATION START DURATION -->
                <span>
                    <label style="width:70px"><?php echo $modules->l("Start speed"); ?></label>
                    <i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation Start Duration (ms)"); ?>"></i>
                    <input type="text" style="width:90px;" class="textbox-caption rs-layer-input-field" id="clayer_start_speed" name="clayer_start_speed" value="0">
                    <span class="over-ms">ms</span>
                </span>

                <span class="rs-layer-toolbar-space" style="margin-right:20px"></span>

                <!-- ANIMATION END DURATION -->
                <span>
                    <label style="width:70px"><?php echo $modules->l("End Speed"); ?></label>
                    <i class="rs-mini-layer-icon rs-icon-clock rs-toolbar-icon tipsy_enabled_top" title="<?php echo $modules->l("Animation End Duration (ms)"); ?>"></i>
                    <input type="text" style="width:90px;" class="textbox-caption rs-layer-input-field" id="clayer$modules->lnd_speed" name="clayer$modules->lnd_speed" value="0">
                    <span class="over-ms">ms</span>
                </span>				
                <div id="timline-manual-closer"><i class="eg-icon-cancel"></i></div>
            </div>


            <div id="master-selectedlayer-t"></div>
            <div id="master-selectedlayer-b"></div>
            <div class="master-leftcell">
                <div id="master-leftheader">
                    <div id="mastertimer-playpause-wrapper">
                        <i class="eg-icon-play"></i>
                        <span><?php echo $modules->l('PLAY'); ?></span>
                    </div>
                    <div id="mastertimer-backtoidle">
                    </div>

                    <div id="master-timer-time">00:00.00</div>
                </div>
                <div class="layers-wrapper">
                    <div class="layers-wrapper-scroll">
                        <div id="layers-left" class="sortlist">
                            <ul>
                                <li id="slide_in_sort" class="mastertimer-layer mastertimer-slide ui-state-default" style="overflow: visible !important; z-index: 1000; position: relative">
                                    <div id="fake-select-label-wrapper">
                                        <span id="fake-select-i" style="margin-right:0px;width:27px;line-height:18px;vertical-align:middle">
                                            <i style="margin-left:10px;margin-right:0px;" class="eg-icon-cog"></i>
                                        </span>
                                        <span id="fake-select-label"><?php echo $modules->l('Animation'); ?></span>

                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="master-rightcell">
                <div id="master-rightheader">
                    <div id="mastertimer-position"><span id="mastertimer-poscurtime"><?php echo $modules->l('DragMe'); ?></span></div>
                    <div id="mastertimer-maxtime"><span id="mastertimer-maxcurtime"></span></div>
                    <div id="mastertimer-idlezone"></div>


                    <div class="mastertimer">

                        <div id="mastertimer-linear">
                            <ul class="linear-texts">
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="layers-wrapper">
                    <div class="layers-wrapper-scroll">
                        <div id="layers-right">
                            <ul>
                                <li id="slide_in_sort_time" class="mastertimer-layer mastertimer-slide ui-state-default">
                                    <div class="timeline">
                                        <div class="tl-fullanim">
                                            <div class="tl-startanim">
                                                <span class="sortbox_speedin">100</span>
                                                <span class="start-anim-puller"></span>
                                            </div>
                                        </div>
                                        <div class="slide-idle-section"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="mastertimer-wrapper-resizer"></div>
        </div>
    </div>
    <div id="tp-thelistofclasses"></div>
    <div id="tp-thelistoffonts"></div>

    <!-- THE BUTTON DIALOG WINDOW -->
    <div id="dialog_addbutton" class="dialog-addbutton" title="<?php echo $modules->l("Add Button Layer"); ?>" style="display:none">
        <div class="addbuton-dialog-inner">
            <div id="addbutton-examples">
                <div class="addbe-title-row">					
                    <span class="addbutton-bg-light"></span>
                    <span class="addbutton-bg-dark"></span>
                    <span class="addbutton-title" style="font-size:14px;"><?php echo $modules->l("Click on Element to add it"); ?></span>
                </div>

                <div class="addbutton-examples-wrapper">
                    <span class="addbutton-title"><?php echo $modules->l("Buttons"); ?></span>
                    <div class="addbutton-buttonrow" style="padding-top: 10px;">
                        <a data-needclass="rb-btn" class="rb-btn rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?></a>
                        <a data-needclass="rb-btn" class="rb-btn rb-medium rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?></a>
                        <a data-needclass="rb-btn" class="rb-btn rb-small rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?></a>
                    </div>
                    <div class="addbutton-buttonrow">
                        <a data-needclass="rb-btn" class="rb-btn rb-minround rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?></a>
                        <a data-needclass="rb-btn" class="rb-btn rb-medium rb-minround rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?></a>
                        <a data-needclass="rb-btn" class="rb-btn rb-small rb-minround rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?></a>
                    </div>
                    <div class="addbutton-buttonrow">
                        <a data-needclass="rb-btn" class="rb-btn rb-maxround rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?></a>
                        <a data-needclass="rb-btn" class="rb-btn rb-medium rb-maxround rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?></a>
                        <a data-needclass="rb-btn" class="rb-btn rb-small rb-maxround rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?></a>
                    </div>
                    <div class="addbutton-buttonrow">
                        <a data-needclass="rb-btn rb-withicon" class="rb-btn rb-maxround rb-withicon rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?><i class="icon-right-open"></i></a>
                        <a data-needclass="rb-btn rb-withicon" class="rb-btn rb-medium rb-maxround rb-withicon rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?><i class="icon-right-open"></i></a>
                        <a data-needclass="rb-btn rb-withicon" class="rb-btn rb-small rb-maxround rb-withicon rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?><i class="icon-right-open"></i></a>
                    </div>
                    <div class="addbutton-buttonrow">
                        <a data-needclass="rb-btn rb-hiddenicon" class="rb-btn rb-maxround rb-hiddenicon rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?><i class="icon-right-open"></i></a>
                        <a data-needclass="rb-btn rb-hiddenicon" class="rb-btn rb-medium rb-maxround rb-hiddenicon rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?><i class="icon-right-open"></i></a>
                        <a data-needclass="rb-btn rb-hiddenicon" class="rb-btn rb-small rb-maxround rb-hiddenicon rb-bordered" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?><i class="icon-right-open"></i></a>
                    </div>
                    <div class="addbutton-buttonrow">
                        <a data-needclass="rb-btn rb-hiddenicon" class="rb-btn rb-maxround rb-hiddenicon rb-bordered rb-uppercase" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?><i class="icon-right-open"></i></a>
                        <a data-needclass="rb-btn rb-hiddenicon" class="rb-btn rb-medium rb-maxround rb-hiddenicon rb-bordered rb-uppercase" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?><i class="icon-right-open"></i></a>
                        <a data-needclass="rb-btn rb-hiddenicon" class="rb-btn rb-small rb-maxround rb-hiddenicon rb-bordered rb-uppercase" href="javascript:void(0);"><?php echo $modules->l("Click Here"); ?><i class="icon-right-open"></i></a>
                    </div>
                    <span class="addbutton-title" style="margin-top:10px;margin-bottom:10px;"><?php echo $modules->l("Predefined Elements"); ?></span>
                    <div class="addbutton-buttonrow trans_bg">
                        <div class="dark_trans_overlay"></div> 
                        <div data-needclass="rb-burger rbb-white" class="rbb-white rb-burger" style="display:inline-block;margin-right:10px">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div data-needclass="rb-burger rbb-whitenoborder" class="rbb-whitenoborder rb-burger" style="display:inline-block;margin-right:10px">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div data-needclass="rb-burger rbb-darkfull" class="rbb-darkfull rb-burger" style="display:inline-block;margin-right:10px">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div data-needclass="rb-burger rbb-dark" class="rbb-dark rb-burger" style="display:inline-block;margin-right:10px">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div data-needclass="rb-burger rbb-darknoborder" class="rbb-darknoborder rb-burger" style="display:inline-block;margin-right:10px">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div data-needclass="rb-burger rbb-whitefull" class="rbb-whitefull rb-burger" style="display:inline-block;margin-right:10px">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>

                        <div style="width:100%;height:25px;display:block"></div>
                        <span data-needclass="rb-scroll-btn" class="rb-scroll-btn" style="margin-right:10px">							
                            <span>
                            </span>							
                        </span>
                        <span data-needclass="rb-scroll-btn rbs-dark" class="rb-scroll-btn rbs-dark" style="margin-right:10px">
                            <span>
                            </span>												
                        </span>

                        <span data-needclass="rb-scroll-btn rbs-fullwhite" class="rb-scroll-btn rbs-fullwhite" style="margin-right:10px">
                            <span>
                            </span>							
                        </span>

                        <span data-needclass="rb-scroll-btn rbs-fulldark" class="rb-scroll-btn rbs-fulldark" style="margin-right:10px">
                            <span>
                            </span>
                        </span>

                        <span data-needclass="" class="rb-control-btn rb-sbutton rb-sbutton-blue" style="margin-right:10px;vertical-align:top">
                            <i class="fa-icon-facebook"></i>
                        </span>

                        <span data-needclass="" class="rb-control-btn rb-sbutton rb-sbutton-lightblue" style="margin-right:10px;vertical-align:top">
                            <i class="fa-icon-twitter"></i>
                        </span>

                        <span data-needclass="" class="rb-control-btn rb-sbutton rb-sbutton-red" style="margin-right:10px;vertical-align:top">
                            <i class="fa-icon-google-plus"></i>
                        </span>

                        <span data-needclass="" class="rb-control-btn rb-sbutton" style="margin-right:10px;vertical-align:top">
                            <i class="fa-icon-envelope"></i>
                        </span>

                        <div style="width:100%;height:25px;display:block"></div>
                        <span data-needclass="" class="rb-control-btn rb-cbutton-dark" style="margin-right:10px">
                            <i class="fa-icon-play"></i>
                        </span>

                        <span data-needclass="" class="rb-control-btn rb-cbutton-light" style="margin-right:10px">
                            <i class="fa-icon-play"></i>
                        </span>

                        <span data-needclass="" class="rb-control-btn rb-cbutton-dark-sr" style="margin-right:10px">
                            <i class="fa-icon-play"></i>
                        </span>

                        <span data-needclass="" class="rb-control-btn rb-cbutton-light-sr" style="margin-right:10px">
                            <i class="fa-icon-play"></i>
                        </span>
                        <div style="width:100%;height:25px;display:block"></div>
                        <span data-needclass="" class="rb-control-btn rb-cbutton-dark" style="margin-right:10px">
                            <i class="fa-icon-pause"></i>
                        </span>

                        <span data-needclass="" class="rb-control-btn rb-cbutton-light" style="margin-right:10px">
                            <i class="fa-icon-pause"></i>
                        </span>

                        <span data-needclass="" class="rb-control-btn rb-cbutton-dark-sr" style="margin-right:10px">
                            <i class="fa-icon-pause"></i>
                        </span>

                        <span data-needclass="" class="rb-control-btn rb-cbutton-light-sr" style="margin-right:10px">
                            <i class="fa-icon-pause"></i>
                        </span>
                        <div style="width:100%;height:25px;display:block"></div>


                    </div>
                </div>
            </div>
            <div id="addbutton-settings">
                <div class="adb-configs" style="padding-top:0px">
                    <!-- TITLE -->
                    <div class="add-togglebtn"><span class="addbutton-title"><?php echo $modules->l("Idle State"); ?></span><span class="adb-toggler eg-icon-minus"></span></div>
                    <div class="add-intern-accordion" style="display:block">
                        <!-- COLOR 1 -->
                        <div class="add-lbl-wrapper">
                            <label><?php echo $modules->l("Background"); ?></label>
                        </div>					
                        <!-- COLOR -->					
                        <input type="text" class="rs-layer-input-field my-color-field" style="width:150px" name="adbutton-color-1" value="#000000" />
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

                        <!-- OPACITY -->
                        <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon " style="margin-right:5px"></i>
                        <input data-suffix="" class="adb-input rs-layer-input-field "  style="width:45px" type="text" name="adbutton-opacity-1" value="0.75">



                        <!-- TEXT / ICON -->
                        <div style="width:100%;height:5px"></div>
                        <div class="add-lbl-wrapper">
                            <label><?php echo $modules->l("Color"); ?></label>
                        </div>					
                        <!-- TEXT COLOR -->					
                        <input type="text" class="rs-layer-input-field  my-color-field" title="<?php echo $modules->l("Color 2"); ?>" style="width:150px" name="adbutton-color-2" value="#ffffff" />
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>					

                        <!-- TEXT OPACITY -->
                        <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon "  style="margin-right:5px"></i>
                        <input class="adb-input rs-layer-input-field "  style="width:45px" type="text" name="adbutton-opacity-2" value="1">
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>


                        <!-- BORDER -->
                        <div style="width:100%;height:5px"></div>
                        <div class="add-lbl-wrapper">
                            <label><?php echo $modules->l("Border"); ?></label>
                        </div>					
                        <!-- BORDER COLOR -->					
                        <input type="text" class="rs-layer-input-field  my-color-field" title="<?php echo $modules->l("Border Color"); ?>" style="width:150px" name="adbutton-border-color" value="#000000" />
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>					

                        <!-- BORDER OPACITY -->
                        <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon " title="<?php echo $modules->l("Border Opacity"); ?>" style="margin-right:5px"></i>
                        <input class="adb-input rs-layer-input-field " title="<?php echo $modules->l("Border Opacity"); ?>" style="width:45px" type="text" name="adbutton-border-opacity" value="1">
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

                        <!-- BORDER WIDTH-->
                        <i class="rs-mini-layer-icon rs-icon-borderwidth rs-toolbar-icon " title="<?php echo $modules->l("Border Width"); ?>" style="margin-right:5px"></i>
                        <input class="adb-input text-sidebar rs-layer-input-field " title="<?php echo $modules->l("Border Width"); ?>" style="width:45px" type="text" name="adbutton-border-width" value="0">
                        <div style="width:100%;height:5px"></div>

                        <!-- ICON  & FONT-->
                        <div style="width:100%;height:5px"></div>
                        <div class="add-lbl-wrapper">
                            <label><?php echo $modules->l("Text / Icon"); ?></label>
                        </div>					

                        <span class="addbutton-icon"><i class="fa-icon-chevron-right"></i></span>
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

                        <i class="rs-mini-layer-icon rs-icon-fontfamily rs-toolbar-icon " title="<?php echo $modules->l("Font Family"); ?>" style="margin-right:5px"></i>
                        <input class="adb-input text-sidebar rs-layer-input-field " title="<?php echo $modules->l("Font Family"); ?>" style="width:75px" type="text" name="adbutton-fontfamily" value="Roboto">

                    </div>
                </div>
                <div class="adb-configs">
                    <!-- TITLE -->
                    <div class="add-togglebtn"><span class="addbutton-title"><?php echo $modules->l("Hover State"); ?></span><span class="adb-toggler eg-icon-plus"></span></div>
                    <div class="add-intern-accordion" style="display:none">
                        <!-- COLOR 1 -->
                        <div class="add-lbl-wrapper">
                            <label><?php echo $modules->l("Background"); ?></label>
                        </div>					
                        <!-- COLOR -->					
                        <input type="text" class="rs-layer-input-field my-color-field" style="width:150px" name="adbutton-color-1-h" value="#FFFFFF" />
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

                        <!-- OPACITY -->
                        <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon " style="margin-right:5px"></i>
                        <input data-suffix="" class="adb-input rs-layer-input-field "  style="width:45px" type="text" name="adbutton-opacity-1-h" value="1">

                        <!-- TEXT / ICON -->
                        <div style="width:100%;height:5px"></div>
                        <div class="add-lbl-wrapper">
                            <label><?php echo $modules->l("Color"); ?></label>
                        </div>	

                        <!-- TEXT COLOR -->					
                        <input type="text" class="rs-layer-input-field  my-color-field" title="<?php echo $modules->l("Color 2"); ?>" style="width:150px" name="adbutton-color-2-h" value="#000000" />
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>					

                        <!-- TEXT OPACITY -->
                        <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon "  style="margin-right:5px"></i>
                        <input class="adb-input rs-layer-input-field "  style="width:45px" type="text" name="adbutton-opacity-2-h" value="1">
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>


                        <!-- BORDER -->
                        <div style="width:100%;height:5px"></div>
                        <div class="add-lbl-wrapper">
                            <label><?php echo $modules->l("Border"); ?></label>
                        </div>					
                        <!-- BORDER COLOR -->					
                        <input type="text" class="rs-layer-input-field  my-color-field" title="<?php echo $modules->l("Border Color"); ?>" style="width:150px" name="adbutton-border-color-h" value="#000000" />
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>					

                        <!-- BORDER OPACITY -->
                        <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon " title="<?php echo $modules->l("Border Opacity"); ?>" style="margin-right:5px"></i>
                        <input class="adb-input rs-layer-input-field " title="<?php echo $modules->l("Border Opacity"); ?>" style="width:45px" type="text" name="adbutton-border-opacity-h" value="1">
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

                        <!-- BORDER WIDTH-->
                        <i class="rs-mini-layer-icon rs-icon-borderwidth rs-toolbar-icon " title="<?php echo $modules->l("Border Width"); ?>" style="margin-right:5px"></i>
                        <input class="adb-input text-sidebar rs-layer-input-field " title="<?php echo $modules->l("Border Width"); ?>" style="width:45px" type="text" name="adbutton-border-width-h" value="0">
                        <div style="width:100%;height:5px"></div>
                    </div>


                </div>
                <div class="adb-configs">	
                    <div class="add-togglebtn"><span class="addbutton-title"><?php echo $modules->l("Text"); ?></span><span class="adb-toggler eg-icon-plus"></span></div>
                    <div class="add-intern-accordion" style="display:none">						
                        <input class="adb-input text-sidebar rs-layer-input-field " style="width:100%" type="text" name="adbutton-text" value="Click Here">						
                    </div>
                </div>

            </div>
        </div>
    </div>



    <!-- THE shape DIALOG WINDOW -->
    <div id="dialog_addshape" class="dialog-addshape" title="<?php echo $modules->l("Add Shape Layer"); ?>" style="display:none">
        <div class="addbuton-dialog-inner">
            <div id="addshape-examples">
                <div class="addbe-title-row">					
                    <span class="addshape-bg-light"></span>
                    <span class="addshape-bg-dark"></span>
                    <span class="addshape-title"><?php echo $modules->l("Click your Shape below to add it"); ?></span>
                </div>
                <div class="addshape-examples-wrapper">

                </div>

            </div>
            <div id="addshape-settings">
                <div class="adb-configs" style="padding-top:0px">
                    <!-- TITLE -->
                    <span class="addshape-title"><?php echo $modules->l("Shape Settings"); ?></span>
                    <div class="add-intern-accordion" style="display:block">	
                        <!-- COLOR 1 -->
                        <div class="add-lbl-wrapper">
                            <label><?php echo $modules->l("Background"); ?></label>
                        </div>					
                        <!-- COLOR -->					
                        <input type="text" class="rs-layer-input-field my-color-field" style="width:150px" name="adshape-color-1" value="#000000" />
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

                        <!-- OPACITY -->
                        <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon " style="margin-right:5px"></i>
                        <input data-suffix="" class="ads-input rs-layer-input-field "  style="width:45px" type="text" name="adshape-opacity-1" value="0.5">

                        <!-- BORDER -->
                        <div style="width:100%;height:5px"></div>
                        <div class="add-lbl-wrapper">
                            <label><?php echo $modules->l("Border"); ?></label>
                        </div>					

                        <!-- BORDER COLOR -->					
                        <input type="text" class="rs-layer-input-field  my-color-field" title="<?php echo $modules->l("Border Color"); ?>" style="width:150px" name="adshape-border-color" value="#000000" />
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>					

                        <!-- BORDER OPACITY -->
                        <i class="rs-mini-layer-icon rs-icon-opacity rs-toolbar-icon " title="<?php echo $modules->l("Border Opacity"); ?>" style="margin-right:5px"></i>
                        <input class="ads-input rs-layer-input-field " title="<?php echo $modules->l("Border Opacity"); ?>" style="width:45px" type="text" name="adshape-border-opacity" value="0.5">
                        <span class="rs-layer-toolbar-space" style="margin-right:0px"></span>

                        <!-- BORDER WIDTH-->
                        <i class="rs-mini-layer-icon rs-icon-borderwidth rs-toolbar-icon " title="<?php echo $modules->l("Border Width"); ?>" style="margin-right:5px"></i>
                        <input class="ads-input text-sidebar rs-layer-input-field " title="<?php echo $modules->l("Border Width"); ?>" style="width:45px" type="text" name="adshape-border-width" value="0">
                        <div style="width:100%;height:5px"></div>	


                        <!-- BORDER RADIUS-->
                        <div style="width:100%;height:5px"></div>
                        <div class="add-lbl-wrapper">
                            <label><?php echo $modules->l("Border Radius"); ?></label>
                        </div>					
                        <i class="rs-mini-layer-icon rs-icon-borderradius rs-toolbar-icon"  style="margin-right:10px"></i>
                        <input data-suffix="px" class="ads-input text-sidebar rs-layer-input-field "  style="width:50px" type="text" name="shape_border-radius[]" value="0">
                        <input data-suffix="px" class="ads-input text-sidebar rs-layer-input-field "  style="width:50px" type="text" name="shape_border-radius[]" value="0">
                        <input data-suffix="px" class="ads-input text-sidebar rs-layer-input-field "  style="width:50px" type="text" name="shape_border-radius[]" value="0">
                        <input data-suffix="px" class="ads-input text-sidebar rs-layer-input-field "  style="width:50px" type="text" name="shape_border-radius[]" value="0">

                        <!-- SIZE OF SHAPE-->
                        <div style="width:100%;height:5px"></div>
                        <div class="add-lbl-wrapper">
                            <label><?php echo $modules->l("Width"); ?></label>
                            <span class="rs-layer-toolbar-space" style="margin-right:30px"></span>
                            <label><?php echo $modules->l("Full-Width"); ?></label> 
                        </div>				
                        <input class="ads-input text-sidebar rs-layer-input-field "  style="width:50px" type="text" name="shape_width" value="200">
                        <span class="rs-layer-toolbar-space" style="margin-right:13px"></span>						
                        <input type="checkbox" name="shape_fullwidth" class="tp-moderncheckbox"/>

                        <div style="width:100%;height:5px"></div>
                        <div class="add-lbl-wrapper">
                            <label><?php echo $modules->l("Height"); ?></label>
                            <span class="rs-layer-toolbar-space" style="margin-right:30px"></span>
                            <label><?php echo $modules->l("Full-Height"); ?></label> 
                        </div>				
                        <input class="ads-input text-sidebar rs-layer-input-field "  style="width:50px" type="text" name="shape_height" value="200">
                        <span class="rs-layer-toolbar-space" style="margin-right:13px"></span>						
                        <input type="checkbox" name="shape_fullheight" class="tp-moderncheckbox"/>

                        <div class="shape_padding">
                            <!-- SIZE OF SHAPE-->
                            <div style="width:100%;height:5px"></div>
                            <div class="add-lbl-wrapper">
                                <label><?php echo $modules->l("Padding"); ?></label>
                            </div>
                            <i class="rs-mini-layer-icon rs-icon-padding rs-toolbar-icon" title="<?php echo $modules->l("Padding"); ?>" style="margin-right:10px"></i>
                            <input data-suffix="px" disabled class="ads-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Padding Top"); ?>" style="width:50px" type="text" name="shape_padding[]" value="0">
                            <input data-suffix="px" disabled class="ads-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Padding Right"); ?>" style="width:50px" type="text" name="shape_padding[]" value="0">
                            <input data-suffix="px" disabled class="ads-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Padding Bottom"); ?>" style="width:50px" type="text" name="shape_padding[]" value="0">
                            <input data-suffix="px" disabled class="ads-input text-sidebar rs-layer-input-field tipsy_enabled_top" title="<?php echo $modules->l("Padding Left"); ?>" style="width:50px" type="text" name="shape_padding[]" value="0">

                        </div>
                    </div>
                </div>				
            </div>
        </div>
    </div>

    <div id="dialog-change-style-from-css" title="<?php echo $modules->l('Apply Styles to Selection') ?>" style="display:none;width:275px">

        <div style="margin-top:3px;margin-bottom:13px;">
            <div class="rs-style-device-wrap"><div data-type="desktop" class="rs-style-device_selector_prev rs-preview-ds-desktop selected"></div><input type="checkbox" class="rs-style-device_input" name="rs-css-set-on[]" value="desktop" checked="checked" /></div>
<?php
//check if advanced responsive size is enabled and which ones are
if ($adv_resp_sizes === true) {
    if ($enable_custom_size_notebook == 'on') {

        ?><div class="rs-style-device-wrap"><div data-type="notebook" class="rs-style-device_selector_prev rs-preview-ds-notebook"></div><input type="checkbox" class="rs-style-device_input" name="rs-css-set-on[]" value="notebook" checked="checked" /></div><?php
    }
    if ($enable_custom_size_tablet == 'on') {

        ?><div class="rs-style-device-wrap"><div data-type="tablet" class="rs-style-device_selector_prev rs-preview-ds-tablet"></div><input type="checkbox" class="rs-style-device_input" name="rs-css-set-on[]" value="tablet" checked="checked" /></div><?php
    }
    if ($enable_custom_size_iphone == 'on') {

        ?><div class="rs-style-device-wrap"><div data-type="mobile" class="rs-style-device_selector_prev rs-preview-ds-mobile"></div><input type="checkbox" class="rs-style-device_input" name="rs-css-set-on[]" value="mobile" checked="checked" /></div><?php
    }
}

?>
        </div>

        <p style="margin:0px 0px 6px 0px;font-size:14px"><input type="checkbox" name="rs-css-include[]" value="color" checked="checked" /><?php echo $modules->l('Color'); ?></p>
        <p style="margin:0px 0px 6px 0px;font-size:14px"><input type="checkbox" name="rs-css-include[]" value="font-size" checked="checked" /><?php echo $modules->l('Font Size'); ?></p>
        <p style="margin:0px 0px 6px 0px;font-size:14px"><input type="checkbox" name="rs-css-include[]" value="line-height" checked="checked" /><?php echo $modules->l('Line Height'); ?></p>
        <p style="margin:0px 0px 6px 0px;font-size:14px"><input type="checkbox" name="rs-css-include[]" value="font-weight" checked="checked" /><?php echo $modules->l('Font Weight'); ?></p>
        <p style="margin:20px 0px 0px 0px;font-size:13px;color:#999;font-style:italic"><?php echo $modules->l('Advanced Styles will alwys be applied to all Device Sizes.'); ?></p>
    </div>


    <script type="text/html" id="tmpl-rs-action-layer-wrap">
        <li class="layer_action_row layer_action_wrap">
        <# if(data['edit'] == true){ #>
        <div class="remove-action-row">
        <i class="eg-icon-minus"></i>
        </div>
        <# }else{ #>

        <# } #>

        <select name="<# if(data['edit'] == false){ #>no_<# } #>layer_tooltip$modules->lvent[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:100px; margin-right:30px;">
        <option <# if( data['tooltip$modules->lvent'] == 'click' ){ #>selected="selected" <# } #>value="click"><?php echo $modules->l("Click"); ?></option>
        <option <# if( data['tooltip$modules->lvent'] == 'mouseenter' ){ #>selected="selected" <# } #>value="mouseenter"><?php echo $modules->l("Mouse Enter"); ?></option>
        <option <# if( data['tooltip$modules->lvent'] == 'mouseleave' ){ #>selected="selected" <# } #>value="mouseleave"><?php echo $modules->l("Mouse Leave"); ?></option>
        </select>

        <select name="<# if(data['edit'] == false){ #>no_<# } #>layer_action[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>layer_actions rs-layer-input-field" style="width:150px; margin-right:30px;">						
        <option <# if( data['action'] == 'none' ){ #>selected="selected" <# } #>value="none"><?php echo $modules->l("Disabled"); ?></option>
        <option <# if( data['action'] == 'link' ){ #>selected="selected" <# } #>value="link"><?php echo $modules->l("Simple Link"); ?></option>
        <option <# if( data['action'] == 'jumpto' ){ #>selected="selected" <# } #>value="jumpto"><?php echo $modules->l("Jump to Slide"); ?></option>
        <option <# if( data['action'] == 'next' ){ #>selected="selected" <# } #>value="next"><?php echo $modules->l("Next Slide"); ?></option>
        <option <# if( data['action'] == 'prev' ){ #>selected="selected" <# } #>value="prev"><?php echo $modules->l("Previous Slide"); ?></option>
        <option <# if( data['action'] == 'pause' ){ #>selected="selected" <# } #>value="pause"><?php echo $modules->l("Pause Slider"); ?></option>								
        <option <# if( data['action'] == 'resume' ){ #>selected="selected" <# } #>value="resume"><?php echo $modules->l("Play Slider"); ?></option>																
        <option <# if( data['action'] == 'toggle_slider' ){ #>selected="selected" <# } #>value="toggle_slider"><?php echo $modules->l("Toggle Slider"); ?></option>																
        <option <# if( data['action'] == 'callback' ){ #>selected="selected" <# } #>value="callback"><?php echo $modules->l("CallBack"); ?></option>												
        <option <# if( data['action'] == 'scroll_under' ){ #>selected="selected" <# } #>value="scroll_under"><?php echo $modules->l("Scroll Below Slider"); ?></option>
        <option <# if( data['action'] == 'start_in' ){ #>selected="selected" <# } #>value="start_in"><?php echo $modules->l('Start Layer "in" Animation'); ?></option>
        <option <# if( data['action'] == 'start_out' ){ #>selected="selected" <# } #>value="start_out"><?php echo $modules->l('Start Layer "out" Animation'); ?></option>
        <option <# if( data['action'] == 'toggle_layer' ){ #>selected="selected" <# } #>value="toggle_layer"><?php echo $modules->l('Toggle Layer Animation'); ?></option>
        <option <# if( data['action'] == 'start_video' ){ #>selected="selected" <# } #>value="start_video"><?php echo $modules->l('Start Video'); ?></option>
        <option <# if( data['action'] == 'stop_video' ){ #>selected="selected" <# } #>value="stop_video"><?php echo $modules->l('Stop Video'); ?></option>
        <option <# if( data['action'] == 'toggle_video' ){ #>selected="selected" <# } #>value="toggle_video"><?php echo $modules->l('Toggle Video'); ?></option>
        <option <# if( data['action'] == 'simulate_click' ){ #>selected="selected" <# } #>value="simulate_click"><?php echo $modules->l('Simulate Click'); ?></option>
        <option <# if( data['action'] == 'toggle_class' ){ #>selected="selected" <# } #>value="toggle_class"><?php echo $modules->l('Toggle Layer Class'); ?></option>
        <option <# if( data['action'] == 'togglefullscreen' ){ #>selected="selected" <# } #>value="togglefullscreen"><?php echo $modules->l("Toggle FullScreen"); ?></option>
        <option <# if( data['action'] == 'gofullscreen' ){ #>selected="selected" <# } #>value="gofullscreen"><?php echo $modules->l("Go FullScreen"); ?></option>
        <option <# if( data['action'] == 'exitfullscreen' ){ #>selected="selected" <# } #>value="exitfullscreen"><?php echo $modules->l("Exit FullScreen"); ?></option>
        </select>

        <!-- SIMPLE LINK PARAMETERS -->
        <span class="action-link-wrapper" style="display:none;">
        <span><?php echo $modules->l("Link Url"); ?></span>
        <span class="rs-layer-toolbar-space"></span>
        <input type="text" style="width:150px;margin-right:30px;" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>textbox-caption rs-layer-input-field"  name="<# if(data['edit'] == false){ #>no_<# } #>layer_image_link[]" value="{{ data['image_link'] }}">

        <span><?php echo $modules->l("Link Target"); ?></span>
        <span class="rs-layer-toolbar-space"></span>
        <select name="<# if(data['edit'] == false){ #>no_<# } #>layer_link_open_in[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:150px;margin-right:30px;">
        <option <# if( data['link_open_in'] == '_same' ){ #>selected="selected" <# } #>value="_self"><?php echo $modules->l("Same Window"); ?></option>
        <option <# if( data['link_open_in'] == '_blank' ){ #>selected="selected" <# } #>value="_blank"><?php echo $modules->l("New Window"); ?></option>
        </select>

        <span><?php echo $modules->l("Link Type"); ?></span>
        <span class="rs-layer-toolbar-space"></span>
        <select name="<# if(data['edit'] == false){ #>no_<# } #>layer_link_type[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:150px">
        <option <# if( data['link_type'] == 'jquery' ){ #>selected="selected" <# } #>value="jquery"><?php echo $modules->l("jQuery Link"); ?></option>
        <option <# if( data['link_type'] == 'a' ){ #>selected="selected" <# } #>value="a"><?php echo $modules->l("a Tag Link"); ?></option>
        </select>
        </span>

        <!-- JUMP TO SLIDE -->
        <span class="action-jump-to-slide" style="display:none;">
        <span><?php echo $modules->l("Jump To"); ?></span>
        <span class="rs-layer-toolbar-space"></span>
        <select name="<# if(data['edit'] == false){ #>no_<# } #>jump_to_slide[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:150px" data-selectoption="{{ data['jump_to_slide'] }}">
        </select>

        </span>

        <!-- SCROLL OFFSET -->
        <span class="action-scrollofset" style="display:none;">						
        <span><?php echo $modules->l("Scroll Offset"); ?></span>
        <span class="rs-layer-toolbar-space" ></span>
        <input type="text" style="width:125px;" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>textbox-caption rs-layer-input-field"  name="<# if(data['edit'] == false){ #>no_<# } #>layer_scrolloffset[]" value="{{ data['scrolloffset'] }}">						
        </span>

        <!-- CALLBACK FUNCTION-->
        <span class="action-callback" style="display:none;">						
        <span><?php echo $modules->l("Function"); ?></span>
        <span class="rs-layer-toolbar-space" ></span>
        <input type="text" style="width:250px;" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>textbox-caption rs-layer-input-field"  name="<# if(data['edit'] == false){ #>no_<# } #>layer_actioncallback[]" value="{{ data['actioncallback'] }}">						
        </span>

        <span class="action-target-layer" style="display:none;">
        <span><?php echo $modules->l("Target"); ?></span>
        <span class="rs-layer-toolbar-space"></span>
        <select name="<# if(data['edit'] == false){ #>no_<# } #>layer_target[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:100px;margin-right:30px;" data-selectoption="{{ data['layer_target'] }}">
        </select>
        <span><?php echo $modules->l("Delay"); ?></span>
        <span class="rs-layer-toolbar-space"></span>
        <input type="text" style="width:60px;" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>textbox-caption rs-layer-input-field" name="<# if(data['edit'] == false){ #>no_<# } #>layer_action_delay[]" value="{{ data['action_delay'] }}"> <?php echo $modules->l('ms'); ?>
        </span>		

        <span class="action-toggle_layer" style="display:none;">
        <span class="rs-layer-toolbar-space"></span>
        <span><?php echo $modules->l("at Start"); ?></span>
        <select name="<# if(data['edit'] == false){ #>no_<# } #>toggle_layer_type[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:150px">
        <option <# if( data['toggle_layer_type'] == 'visible' ){ #>selected="selected" <# } #>value="visible"><?php echo $modules->l("Play In Animation"); ?></option>
        <option <# if( data['toggle_layer_type'] == 'hidden' ){ #>selected="selected" <# } #>value="hidden"><?php echo $modules->l("Keep Hidden"); ?></option>
        </select>
        </span>	

        <!-- CALLBACK FUNCTION-->
        <span class="action-toggleclass" style="display:none;">	
        <span class="rs-layer-toolbar-space"></span>
        <span><?php echo $modules->l("Class"); ?></span>
        <span class="rs-layer-toolbar-space" ></span>
        <input type="text" style="width:100px;" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>textbox-caption rs-layer-input-field"  name="<# if(data['edit'] == false){ #>no_<# } #>layer_toggleclass[]" value="{{ data['toggle_class'] }}">
        </span>

        <span class="action-triggerstates" style="display: none; white-space:nowrap">
        <span class="rs-layer-toolbar-space"></span>
        <span><?php echo $modules->l("Animation Timing"); ?></span>
        <span class="rs-layer-toolbar-space" ></span>
        <select name="<# if(data['edit'] == false){ #>no_<# } #>do-layer-animation-overwrite[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:150px">
        <option value="default"><?php echo $modules->l("In and Out Animation Default"); ?></option>
        <option value="waitout"><?php echo $modules->l("In Animation Default and Out Animation Wait for Trigger"); ?></option>
        <option value="wait"><?php echo $modules->l("Wait for Trigger"); ?></option>
        </select>
        <span class="rs-layer-toolbar-space" ></span>
        <span><?php echo $modules->l("Trigger Memory"); ?></span>
        <span class="rs-layer-toolbar-space" ></span>
        <select name="<# if(data['edit'] == false){ #>no_<# } #>do-layer-trigger-memory[]" class="<# if(data['edit'] == false){ #>rs_disabled_field <# } #>rs-layer-input-field" style="width:150px">
        <option value="reset"><?php echo $modules->l("Reset Animation and Trigger States every loop"); ?></option>
        <option value="keep"><?php echo $modules->l("Keep last selected State"); ?></option>
        </select>
        </span>
        </li>
    </script>

    <script>
        // CHANGE STYLE OF EXAMPLE BUTTONS ON DEMAND
        // RGBA HEX CALCULATOR
        var local_cHex = function(hex, o) {
            o = parseFloat(o);
            hex = hex.replace('#', '');
            var r = parseInt(hex.substring(0, 2), 16),
                    g = parseInt(hex.substring(2, 4), 16),
                    b = parseInt(hex.substring(4, 6), 16),
                    result = 'rgba(' + r + ',' + g + ',' + b + ',' + o + ')';
            return result;
        }

        var getButtonExampleValues = function() {
            var o = new Object();
            o.bgc = local_cHex(jQuery('input[name="adbutton-color-1"]').val(), jQuery('input[name="adbutton-opacity-1"]').val());
            o.col = local_cHex(jQuery('input[name="adbutton-color-2"]').val(), jQuery('input[name="adbutton-opacity-2"]').val());
            o.borc = local_cHex(jQuery('input[name="adbutton-border-color"]').val(), jQuery('input[name="adbutton-border-opacity"]').val());
            o.borw = parseInt(jQuery('input[name="adbutton-border-width"]').val(), 0) + "px";
            o.borwh = parseInt(jQuery('input[name="adbutton-border-width-h"]').val(), 0) + "px";
            o.bgch = local_cHex(jQuery('input[name="adbutton-color-1-h"]').val(), jQuery('input[name="adbutton-opacity-1-h"]').val());
            o.colh = local_cHex(jQuery('input[name="adbutton-color-2-h"]').val(), jQuery('input[name="adbutton-opacity-2-h"]').val());
            o.borch = local_cHex(jQuery('input[name="adbutton-border-color-h"]').val(), jQuery('input[name="adbutton-border-opacity-h"]').val());
            o.ff = jQuery('input[name="adbutton-fontfamily"]').val();
            return o;
        }

        var setExampleButtons = function() {
            var c = jQuery('#addbutton-examples');
            c.find('.rb-btn').each(function() {
                var b = jQuery(this),
                        o = getButtonExampleValues();

                b.css({backgroundColor: o.bgc,
                    color: o.col,
                    fontFamily: o.ff});

                b.find('i').css({color: o.col});


                if (b.hasClass("rb-bordered"))
                    b.css({borderColor: o.borc, borderWidth: o.borw, borderStyle: "solid"})

                if (b.find('i').length > 0) {
                    b.find('i').remove();
                    b.html(jQuery('input[name="adbutton-text"]').val());
                    b.append(jQuery('.addbutton-icon').html());
                } else {
                    b.html(jQuery('input[name="adbutton-text"]').val());
                }

                b.unbind('hover');
                b.hover(function() {
                    var b = jQuery(this),
                            o = getButtonExampleValues();
                    b.css({backgroundColor: o.bgch, color: o.colh});
                    b.find('i').css({color: o.colh});
                    if (b.hasClass("rb-bordered"))
                        b.css({borderColor: o.borch, borderWidth: o.borwh, borderStyle: "solid"});
                },
                        function() {
                            var b = jQuery(this),
                                    o = getButtonExampleValues();
                            b.css({backgroundColor: o.bgc, color: o.col});
                            b.find('i').css({color: o.col});
                            if (b.hasClass("rb-bordered"))
                                b.css({borderColor: o.borc, borderWidth: o.borw, borderStyle: "solid"});
                        })

            })
        }

        var setExampleShape = function() {
            var p = jQuery('.addshape-examples-wrapper'),
                    o = new Object();

            o.bgc = local_cHex(jQuery('input[name="adshape-color-1"]').val(), jQuery('input[name="adshape-opacity-1"]').val());
            o.w = parseInt(jQuery('input[name="shape_width"]').val(), 0);
            o.h = parseInt(jQuery('input[name="shape_height"]').val(), 0);
            o.borc = local_cHex(jQuery('input[name="adshape-border-color"]').val(), jQuery('input[name="adshape-border-opacity"]').val());
            o.borw = parseInt(jQuery('input[name="adshape-border-width"]').val(), 0) + "px";
            o.fw = jQuery('input[name="shape_fullwidth"]').is(':checked');
            o.fh = jQuery('input[name="shape_fullheight"]').is(':checked');
            o.br = "";

            if (o.fw) {
                o.w = "100%";
                o.l = "0px";
                o.ml = "0px";
                jQuery('input[name="shape_width"]').attr("disabled", "disabled");
            } else {
                o.w = parseInt(o.w, 0) + "px";
                o.l = "50%";
                o.ml = 0 - parseInt(o.w, 0) / 2;
                jQuery('input[name="shape_width"]').removeAttr("disabled");
            }

            if (o.fh) {
                o.h = "100%";
                o.t = "0px";
                o.mt = "0px";
                jQuery('input[name="shape_height"]').attr("disabled", "disabled");
            } else {
                o.h = parseInt(o.h, 0) + "px";
                o.t = "50%";
                o.mt = 0 - parseInt(o.h, 0) / 2;
                jQuery('input[name="shape_height"]').removeAttr("disabled");
            }

            jQuery('input[name="shape_border-radius[]"]').each(function(i) {
                var t = jQuery.isNumeric(jQuery(this).val()) ? parseInt(jQuery(this).val(), 0) + "px" : jQuery(this).val();
                o.br = o.br + t;
                o.br = i < 3 ? o.br + " " : o.br;
            });
            o.pad = "";
            if (o.fh && o.fw) {
                jQuery('input[name="shape_padding[]"]').removeAttr("disabled");
                jQuery('input[name="shape_padding[]"]').each(function(i) {
                    var t = jQuery.isNumeric(jQuery(this).val()) ? parseInt(jQuery(this).val(), 0) + "px" : jQuery(this).val();
                    o.pad = o.pad + t;
                    o.pad = i < 3 ? o.pad + " " : o.pad;

                });
            } else {
                jQuery('input[name="shape_padding[]"]').attr("disabled", "disabled");
                o.pad = "0";

            }




            if (p.find('.example-shape').length == 0)
                p.append('<div class="example-shape-wrapper"><div class="example-shape"></div></div>');
            var e = p.find('.example-shape');

            e.css({backgroundColor: o.bgc,
                padding: o.pad,
                borderStyle: "solid", borderWidth: o.borw, borderColor: o.borc, borderRadius: o.br});
            e.parent().css({
                top: o.t, left: o.l, marginLeft: o.ml, marginTop: o.mt,
                width: o.w, height: o.h,
                padding: o.pad
            })
            RbSliderSettings.onoffStatus(jQuery('input[name="shape_fullwidth"]'));
            RbSliderSettings.onoffStatus(jQuery('input[name="shape_fullheight"]'));
        }



        jQuery(document).ready(function() {

            jQuery('.quick-layers-list').perfectScrollbar({wheelPropagation: false});

            // MANAGE BG COLOR OF DIALOG BOXES
            jQuery('.addbutton-bg-dark').click(function() {
                jQuery('#addbutton-examples').css({backgroundColor: "#333333"});
            })
            jQuery('.addbutton-bg-light').click(function() {
                jQuery('#addbutton-examples').css({backgroundColor: "#eeeeee"});
            })
            jQuery('.addshape-bg-dark').click(function() {
                jQuery('#addshape-examples').css({backgroundColor: "#333333"});
            })
            jQuery('.addshape-bg-light').click(function() {
                jQuery('#addshape-examples').css({backgroundColor: "#eeeeee"});
            })

            // ADD BUTTON DIALOG RELEVANT FUNCTIONS
            jQuery('.addbutton-examples-wrapper').perfectScrollbar({wheelPropagation: true});
            jQuery('.add-togglebtn').click(function() {
                var aia = jQuery(this).parent().find('.add-intern-accordion');
                aia.addClass("nowactive");
                jQuery('.add-intern-accordion').each(function() {
                    if (!jQuery(this).hasClass("nowactive"))
                        jQuery(this).slideUp(200);
                });
                jQuery('.adb-toggler').removeClass("eg-icon-minus").addClass("eg-icon-plus");
                aia.slideDown(200);
                jQuery(this).find('.adb-toggler').addClass("eg-icon-minus").removeClass("eg-icon-plus");
                aia.removeClass("nowactive");
            })


            jQuery('body').on("click", "fake-select-i, #fake-select-label", function() {
                var tab = jQuery('#slide-animation-settings-content-tab');
                tab.click();
                jQuery("html, body").animate({scrollTop: (tab.offset().top - 200) + "px"}, {duration: 400});
            })

            jQuery('.master-rightcell .layers-wrapper, #divLayers-wrapper').perfectScrollbar({wheelPropagation: true, suppressScrollY: true});
            jQuery('.master-leftcell .layers-wrapper').perfectScrollbar({suppressScrollX: true});



            var bawi = jQuery('#thelayer-editor-wrapper').outerWidth(true) - 2;
            //jQuery('.master-rightcell').css({maxWidth:bawi-222});
            jQuery('#mastertimer-wrapper').css({maxWidth: bawi});
            jQuery('.layers-wrapper').css({maxWidth: bawi - 222});
            var scrint;

            jQuery('.master-rightcell, .master-leftcell').hover(function() {
                jQuery(this).addClass("overtoscroll");
            }, function() {
                jQuery(this).removeClass("overtoscroll");
            })


            jQuery('.master-rightcell .layers-wrapper').on("scroll", function() {
                if (jQuery('.master-rightcell').hasClass("overtoscroll")) {

                    var ts = jQuery(this).scrollTop();
                    jQuery('.master-leftcell .layers-wrapper').scrollTop(ts);

                    clearTimeout(scrint);
                    var ts = jQuery(this).scrollTop(),
                            ls = jQuery('.master-rightcell .layers-wrapper').scrollLeft();

                    jQuery('#master-rightheader').css({left: (0 - ls)});
                    jQuery(this).scrollLeft(ls);
                    jQuery('.layers-wrapper').scrollTop(ts);
                    scrint = setTimeout(function() {
                        var ls = jQuery('.master-rightcell .layers-wrapper').scrollLeft();
                        jQuery('#master-rightheader').css({left: (0 - ls)});
                        jQuery('.layers-wrapper').scrollTop(ts);
                    }, 50);
                }
            });

            jQuery('.master-leftcell .layers-wrapper').on("scroll", function() {
                if (!jQuery('.master-rightcell').hasClass("overtoscroll")) {
                    clearTimeout(scrint);
                    var ts = jQuery(this).scrollTop();
                    jQuery('.master-rightcell .layers-wrapper').perfectScrollbar('update').scrollTop(ts);
                    jQuery('.master-rightcell .ps-scrollbar-x-rail').css({visibility: "hidden"});
                    scrint = setTimeout(function() {
                        jQuery('.master-rightcell .ps-scrollbar-x-rail').css({visibility: "visible"});
                    }, 50);
                }
            });



            jQuery(window).resize(function() {
                var bawi = jQuery('#thelayer-editor-wrapper').outerWidth(true) - 2;
                //jQuery('.master-rightcell').css({maxWidth:bawi-222});
                jQuery('#mastertimer-wrapper').css({maxWidth: bawi});
                jQuery('.layers-wrapper').css({maxWidth: bawi - 222});
                jQuery('.master-rightcell .layers-wrapper, #divLayers-wrapper').perfectScrollbar("update");
            });

            jQuery('#mastertimer-wrapper').resizable({
                handles: "s",
                minHeight: 102,
                //alsoResize:".layers-wrapper",
                start: function() {
                    jQuery('.master-rightcell .layers-wrapper').perfectScrollbar("destroy");
                },
                resize: function() {
                    jQuery('.layers-wrapper').height(jQuery('#mastertimer-wrapper').height() - 50);
                },
                stop: function() {
                    jQuery('.layers-wrapper').height(jQuery('#mastertimer-wrapper').height() - 40);
                    jQuery('.master-rightcell .layers-wrapper').perfectScrollbar({wheelPropagation: true});
                    jQuery('.master-leftcell .layers-wrapper').perfectScrollbar({wheelPropagation: true, suppressScrollX: true});

                }
            });

            UniteAdminRb.initVideoDef();
        });
    </script>
</div>
<?php
