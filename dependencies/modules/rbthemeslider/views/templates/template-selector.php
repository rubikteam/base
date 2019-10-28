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

$tmpl = new RbSliderTemplate();
$templates = $tmpl->getTemplateSlides();
$tp_templates = $tmpl->getThemePunchTemplateSlides();
$tp_template_slider = $tmpl->getThemePunchTemplateSliders();
$tmp_slider = new RbSlider();
$all_slider = $tmp_slider->getArrSliders();
$modules = new Rbthemeslider();

?>

<div id="template_area">
    <div id="template_header_part">
        <h2><span class="rblogo-mini" style="margin-right:15px;"></span><?php $modules->l('Slider Template Library'); ?></h2>
        <div id="close-template"></div>

        <div class="rbthemeslider-template-switcher">
            <span data-showgroup="rbthemeslider-all-slides-templates" class="rbthemeslider-templatebutton selected"><?php $modules->l('All Slides'); ?></span>
            <span data-showgroup="rbthemeslider-customer-templates" class="rbthemeslider-templatebutton" style="border-right:none"><?php $modules->l('Templates'); ?></span>						
            <span data-showgroup="rbthemeslider-premium-templates" class="rbthemeslider-templatebutton premium-templatebutton"><i class="eg-icon-basket"></i><?php $modules->l('Premium Sliders'); ?></span>
            <span class="rs-reload-shop"><i class="eg-icon-arrows-ccw"></i><?php $modules->l('Check for new Templates'); ?></span>
        </div>

        <div class="rbthemeslider-template-subtitle"><?php $modules->l('Add Single Slide'); ?></div>
    </div>

    <div class="rbthemeslider-premium-templates rbthemeslider-template-groups" style="display: none">
        <?php
        if (!empty($tp_template_slider)) {
            foreach ($tp_template_slider as $m_slider) {
                if ($m_slider['cat'] != 'Premium') {
                    continue;
                }

                if (!empty($m_slider['filter']) && is_array($m_slider['filter'])) {
                    foreach ($m_slider['filter'] as $f => $v) {
                        $m_slider['filter'][$f] = 'temp_' . $v;
                    }
                }

                if (!@Rbthemeslider::getIsset($m_slider['installed']) &&
                    !@Rbthemeslider::getIsset($m_slider['is_new'])
                ) {
                    $c_slider = new RbSlider();
                    $c_slider->initByDBData($m_slider);
                    $c_slides = $tmpl->getThemePunchTemplateSlides(array($m_slider));
                    $c_title = $c_slider->getTitle();
                    $width = $c_slider->getParam("width", 1240);
                    $height = $c_slider->getParam("height", 868);

                    if (!empty($c_slides)) {

                        ?>
                        <div class="template_group_wrappers <?php
                        if (@Rbthemeslider::getIsset($m_slider['filter'])) {
                            echo implode(' ', $m_slider['filter']);
                        }

                        ?>">							
                                 <?php
                                 echo '<div class="template_slider_title">';
                                 if (@Rbthemeslider::getIsset($m_slider['preview']) && $m_slider['preview'] !== '') {
                                     echo '<a href="' . ($m_slider['preview']) . '" target="_blank" class="icon-preview_slider" style="margin-right:15px"></a>';
                                 }

                                 echo $c_title . '</div>';

                                 foreach ($c_slides as $c_slide) {
                                     if (@Rbthemeslider::getIsset($m_slider['filter'])) {
                                         $c_slide['filter'] = $m_slider['filter']; //add filters 
                                     }
                                     $c_slide['settings']['width'] = $width;
                                     $c_slide['settings']['height'] = $height;

                                     $tmpl->writeTemplateMarkup($c_slide);
                                 }

                                 ?>							
                        </div><?php
                    }
                } else {
                    $c_slides = $tmpl->getThemePunchTemplateDefaultSlides($m_slider['alias']);

                    if (!empty($c_slides)) {

                        ?>
                        <div class="template_group_wrappers not-imported-wrapper <?php
                             if (@Rbthemeslider::getIsset($m_slider['filter'])) {
                                 echo implode(' ', $m_slider['filter']);
                             }

                             ?>">

                            <?php
                            echo '<div class="template_slider_title">';
                            if (@Rbthemeslider::getIsset($m_slider['preview']) && $m_slider['preview'] !== '') {
                                echo '<a href="' . ($m_slider['preview']) . '" target="_blank" class="icon-preview_slider" style="margin-right:15px"></a>';
                            }
                            echo $m_slider['title'] . '</div>';

                            foreach ($c_slides as $key => $c_slide) {
                                if (@Rbthemeslider::getIsset($m_slider['filter'])) {
                                    $c_slide['filter'] = $m_slider['filter'];
                                }
                                $c_slide['width'] = $m_slider['width'];
                                $c_slide['height'] = $m_slider['height'];
                                $c_slide['uid'] = $m_slider['uid'];
                                $c_slide['number'] = $key;
                                $c_slide['zip'] = $m_slider['zip'];
                                $c_slide['required'] = $m_slider['required'];
                                $tmpl->writeImportTemplateMarkupSlide($c_slide);
                            }

                            ?>							
                        </div><?php
                    }
                }
                echo '<div style="margin-bottom:30px" class="tp-clearfix"></div>';
            }
        }

        ?>
        <div style="clear:both;width:100%"></div>
    </div>

    <!-- THE rbthemeslider CUSTOMER TEMPLATES -->
    <div class="rbthemeslider-customer-templates rbthemeslider-template-groups">
        <div class="template_group_wrappers">
            <?php
            if (!empty($templates)) {
                foreach ($templates as $template) {
                    $tmpl->writeTemplateMarkup($template);
                }
            }

            ?>
            <div style="clear:both;width:100%"></div>
        </div>
        <div style="clear:both;width:100%"></div>
    </div>

    <!-- THE ALL SLIDES GROUP -->
    <div class="rbthemeslider-all-slides-templates rbthemeslider-template-groups" style="display:block;">
        <?php
        if (!empty($all_slider)) {
            foreach ($all_slider as $c_slider) {
                $c_slides = $c_slider->getSlides(false);
                $c_title = $c_slider->getTitle();
                $width = $c_slider->getParam("width", 1240);
                $height = $c_slider->getParam("height", 868);

                if (!empty($c_slides)) {

                    ?>
                    <div class="template_group_wrappers">
                        <?php
                        echo '<div class="template_slider_title">' . $c_title . '</div>';
                        foreach ($c_slides as $c_slide) {
                            $mod_slide = array();
                            $mod_slide['id'] = $c_slide->getID();
                            $mod_slide['params'] = $c_slide->getParams();
                            $mod_slide['settings'] = $c_slide->getSettings();
                            $mod_slide['settings']['width'] = $width;
                            $mod_slide['settings']['height'] = $height;
                            $tmpl->writeTemplateMarkup($mod_slide);
                        }

                        ?>						
                    </div><?php
        }
        echo '<div style="margin-bottom:30px" class="tp-clearfix"></div>';
    }
}

?>
    </div>
</div>
<?php
$rs_disable_template_script = true;
if (!@Rbthemeslider::getIsset($rs_disable_template_script)) {

    ?>
    <script>
        jQuery("document").ready(function() {
            templateSelectorHandling();
        });

        function isElementInViewport(element, sctop, wh) {
            var etp = parseInt(element.position().top, 0),
                    ebp = etp + parseInt(element.height(), 0),
                    inviewport = false;
            if ((etp > 0 && etp < parseInt(wh, 0)) || (ebp < parseInt(wh, 0) && ebp > 0))
                inviewport = true;

            return inviewport;
        }

        function scrollTA() {
            var ta = jQuery('#template_area'),
                    st = ta.scrollTop(),
                    wh = jQuery(window).height();

            ta.find('.template_item:visible, .template_slide_item_img:visible').each(function() {
                var el = jQuery(this);

                if (el.data('src') != undefined && el.data('bgadded') != 1) {
                    if (jQuery('#template_area').hasClass("show"))
                        if (isElementInViewport(el, st, wh)) {
                            el.css({backgroundImage: 'url("' + el.data('src') + '")'});
                            el.data('bgadded', 1);
                        }
                }
            });
        }

        function templateSelectorHandling() {
            jQuery('.template_filter_button').on("click", function() {
                var btn = jQuery(this),
                        sch = btn.data('type');
                jQuery('.template_filter_button').removeClass("selected");
                btn.addClass("selected");
                jQuery('.temp_slider, .temp_carousel, .temp_hero').hide();
                if (sch == "temp_all")
                    jQuery('.temp_slider, .temp_carousel, .temp_hero').show();
                else
                    jQuery('.' + sch).show();
            });


            jQuery('.template_item, .template_slide_item_img').each(function() {
                var item = jQuery(this),
                        gw = item.data('gridwidth'),
                        gh = item.data('gridheight'),
                        id = item.data('slideid'),
                        w = 180;

                if (gw == undefined || gw <= 0)
                    gw = w;
                if (gh == undefined || gh <= 0)
                    gh = w;

                var h = Math.round((w / gw) * gh);
                var factor = w / gw;
                var htitle = item.closest('.template_group_wrappers').find('h3');

                if (!htitle.hasClass("modificated")) {
                    htitle.html(htitle.html() + " (" + gw + "x" + gh + ")").addClass("modificated");
                }
            });

            jQuery('#close-template').click(function() {
                jQuery('#template_area').removeClass("show");
            });

            function templateButtonClicked(btn) {
                jQuery('.rbthemeslider-template-groups').each(function() {
                    jQuery(this).hide();
                });
                jQuery("." + btn.data("showgroup")).show();
                jQuery('.rbthemeslider-templatebutton').removeClass("selected");
                btn.addClass("selected");
                scrollTA();
                jQuery('#template_area').perfectScrollbar();

                if (btn.data("showgroup") == 'rbthemeslider-basic-templates' || btn.data("showgroup") == 'rbthemeslider-premium-templates') {
                    jQuery('.rbthemeslider-filters').show();
                } else {
                    jQuery('.rbthemeslider-filters').hide();
                }
            }
            ;

            jQuery('body').on("click", '.rbthemeslider-templatebutton', function() {
                templateButtonClicked(jQuery(this));
            });

            scrollTA();
            jQuery('#template_area').perfectScrollbar();

            jQuery('#template_area').on("scroll", function() {
                scrollTA()
            });

            jQuery(".input_import_slider").change(function() {
                if (jQuery(this).val() !== '') {
                    jQuery('.rs-import-slider-button').show();
                } else {
                    jQuery('.rs-import-slider-button').hide();
                }
            });

        }
        ;

    <?php
    if (@Rbthemeslider::getIsset($_REQUEST['update_shop'])) {

        ?>
            var recalls_amount = 0;
            function callTemplateSlider() {
                recalls_amount++;
                if (recalls_amount > 5000) {
                    jQuery('#waitaminute').hide();
                } else {
                    if (jQuery('#template_area').length > 0) {
                        scrollTA();
                        jQuery('#template_area').addClass("show");
                        jQuery('#waitaminute').hide();
                    } else {
                        callTemplateSlider();
                    }
                }
            }
            callTemplateSlider();
                  <?php
              }

              ?>
    </script>

    <div id="dialog_import_template_slide" title="<?php $modules->l("Import Template Slide");

              ?>" class="dialog_import_template_slide" style="display:none">
        <form action="<?php echo RbSliderBase::$url_ajax;

              ?>" enctype="multipart/form-data" method="post">
            <input type="hidden" name="action" value="rbslider_ajax_action">
            <input type="hidden" name="client_action" value="import_slide_template_slidersview">
            <input type="hidden" name="uid" class="rs-uid" value="">
            <input type="hidden" name="slidenum" class="rs-slide-number" value="">
            <input type="hidden" name="slider_id" class="rs-slider-id" value="">
            <input type="hidden" name="redirect_id" class="rs-slide-id" value="">

            <p><?php $modules->l('Please select the corresponding zip file from the download packages import folder called');

              ?>:</p>
            <p class="filetoimport"><b><span class="rs-zip-name"></span></b></p>
            <p class="import-file-wrapper"><input type="file" size="60" name="import_file" class="input_import_slider"></p>
            <span style="margin-top:45px;display:block"><input type="submit" class="rs-import-slider-button button-primary rbblue tp-be-button" value="<?php $modules->l("Import Template Slide");

              ?>"></span>
            <span class="tp-clearfix"></span>
            <span style="font-weight: 700;"><?php $modules->l("Note: style templates will be updated if they exist!");

              ?></span><br><br>
            <table style="display: none;">
                <tr>
                    <td><?php $modules->l("Custom Animations:");

                    ?></td>
                    <td><input type="radio" name="update_animations" value="true" checked="checked"> <?php $modules->l("overwrite");

                    ?></td>
                    <td><input type="radio" name="update_animations" value="false"> <?php $modules->l("append");

                    ?></td>
                </tr>
                <tr>
                    <td><?php $modules->l("Static Styles:");

                    ?></td>
                    <td><input type="radio" name="update_static_captions" value="true"> <?php $modules->l("overwrite");

                    ?></td>
                    <td><input type="radio" name="update_static_captions" value="false"> <?php $modules->l("append");

                    ?></td>
                    <td><input type="radio" name="update_static_captions" value="none" checked="checked"> <?php $modules->l("ignore");

                    ?></td>
                </tr>
            </table>		

        </form>
    </div>


    <div id="dialog_import_template_slide_from" title="<?php $modules->l("Import Template Slide");

                    ?>" class="dialog_import_template_slide_from" style="display:none">
    <?php $modules->l('Import Slide from local or from ThemePunch online server?');

    ?>
        <form action="<?php echo RbSliderBase::$url_ajax;

    ?>" enctype="multipart/form-data" method="post" name="rs-import-slide-template-from-server" id="rs-import-slide-template-from-server">
            <input type="hidden" name="action" value="rbslider_ajax_action">
            <input type="hidden" name="client_action" value="import_slide_online_template_slidersview">
            <input type="hidden" name="uid" class="rs-uid" value="">
            <input type="hidden" name="slidenum" class="rs-slide-number" value="">
            <input type="hidden" name="slider_id" class="rs-slider-id" value="">
            <input type="hidden" name="redirect_id" class="rs-slide-id" value="">
        </form>
    </div>
    <?php
}
