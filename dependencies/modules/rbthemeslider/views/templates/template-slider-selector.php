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
$tp_template_slider = $tmpl->getThemePunchTemplateSliders();
$author_template_slider = $tmpl->getDefaultTemplateSliders();
$modules = new Rbthemeslider();
$tmp_slider = new RbSlider();
$all_slider = $tmp_slider->getArrSliders();

?>

<div id="template_area">
    <div id="template_header_part">
        <h2><span class="rblogo-mini" style="margin-right:15px;"></span><?php $modules->l('Slider Template Library'); ?></h2>

        <div id="close-template"></div>

        <div class="rbthemeslider-template-switcher">
            <span data-showgroup="rbthemeslider-basic-templates" class="rbthemeslider-templatebutton selected"><?php $modules->l('rbthemeslider Base'); ?></span>
            <span data-showgroup="rbthemeslider-premium-templates" class="rbthemeslider-templatebutton premium-templatebutton"><i class="eg-icon-basket"></i><?php $modules->l('Premium Sliders'); ?></span>
            <?php
            if (!empty($author_template_slider) && is_array($author_template_slider)) {
                foreach ($author_template_slider as $name => $v) {

                    ?>
                    <span data-showgroup="rbthemeslider-<?php echo sanitize_title($name);

                    ?>" class="rbthemeslider-templatebutton"><?php echo($name);

                    ?></span>
                    <?php
                }
            }

            ?>
            <span class="rs-reload-shop"><i class="eg-icon-arrows-ccw"></i><?php $modules->l('Check for new Templates'); ?></span>
        </div>

        <div class="rbthemeslider-template-subtitle"><?php $modules->l('Add Slider'); ?>
            <span style="display:inline-block;width:40px;"></span>
            <span class="template_filter_button selected" data-type="temp_all"><?php $modules->l('SHOW ALL'); ?></span>
            <span class="template_filter_button" data-type="temp_slider"><?php $modules->l('SLIDER'); ?></span>
            <span class="template_filter_button" data-type="temp_carousel"><?php $modules->l('CAROUSEL'); ?></span>
            <span class="template_filter_button" data-type="temp_hero"><?php $modules->l('HERO'); ?></span>
        </div>
    </div>

    <div class="rbthemeslider-basic-templates rbthemeslider-template-groups">
        <?php
        if (!empty($tp_template_slider)) {
            foreach ($tp_template_slider as $m_slider) {
                $is_supported = Tools::version_compare(
                    $m_slider['required'],
                    RbSliderGlobals::SLIDER_RBISION,
                    'le'
                );

                if ($m_slider['cat'] != 'rbthemeslider Base' || !$is_supported) {
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
                                 foreach ($c_slides as $c_slide) {
                                    $c_slide['img'] = $m_slider['img'];

                                    if (@Rbthemeslider::getIsset($m_slider['preview'])) {
                                        $c_slide['preview'] = $m_slider['preview'];
                                    }

                                    if (@Rbthemeslider::getIsset($m_slider['filter'])) {
                                        $c_slide['filter'] = $m_slider['filter'];
                                    }

                                    $c_slide['settings']['width'] = $width;
                                    $c_slide['settings']['height'] = $height;
                                    $tmpl->RbSliderTemplate($c_slide, $c_slider->getID());
                                    break;
                                 }
                                 
                                 echo '<div class="template_thumb_title">' . $c_title . '</div>';

                                 ?>
                        </div><?php
                    }
                } else {

                    ?>
                    <div class="template_group_wrappers not-imported-wrapper <?php
                             if (@Rbthemeslider::getIsset($m_slider['filter'])) {
                                 echo implode(' ', $m_slider['filter']);
                             }

                             ?>">
                    <?php
                    $tmpl->writeImportTemplateMarkup($m_slider);
                    echo '<div class="template_thumb_title">' . $m_slider['title'] . '</div>';

                    ?>
                    </div>
                    <?php
                }
            }

        } else {
            echo '<span style="color: #F00; font-size: 20px">';
            $modules->l('No data could be retrieved from the servers. Please make sure that your website can connect to the themepunch servers.');
            echo '</span>';
        }

        ?>
        <div style="clear:both;width:100%"></div>
    </div>

    <!-- THE rbthemeslider PREMIUM TEMPLATES -->
    <div class="rbthemeslider-premium-templates rbthemeslider-template-groups" style="display: none">
        <?php
        if (!empty($tp_template_slider)) {
            foreach ($tp_template_slider as $m_slider) {
                $is_supported = Tools::version_compare($m_slider['required'], RbSliderGlobals::SLIDER_RBISION, 'le');
                if ($m_slider['cat'] != 'Premium' || !$is_supported) {
                    continue;
                }

                if (!empty($m_slider['filter']) && is_array($m_slider['filter'])) {
                    foreach ($m_slider['filter'] as $f => $v) {
                        $m_slider['filter'][$f] = 'temp_' . $v;
                    }
                }

                if (!@Rbthemeslider::getIsset($m_slider['installed']) && !@Rbthemeslider::getIsset($m_slider['is_new'])) {
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
                                 foreach ($c_slides as $c_slide) {
                                     $c_slide['img'] = $m_slider['img'];
                                     if (@Rbthemeslider::getIsset($m_slider['preview'])) {
                                         $c_slide['preview'] = $m_slider['preview'];
                                     }
                                     if (@Rbthemeslider::getIsset($m_slider['filter'])) {
                                         $c_slide['filter'] = $m_slider['filter'];
                                     }

                                     if ($c_slide['img'] == '') {
                                         //set default image
                                     }

                                     $c_slide['settings']['width'] = $width;
                                     $c_slide['settings']['height'] = $height;
                                     $tmpl->RbSliderTemplate($c_slide, $c_slider->getID());
                                     break;
                                 }
                                 echo '<div class="template_thumb_title">' . $c_title . '</div>';

                                 ?>
                        </div><?php
                    }
                } else {

                    ?>
                    <div class="template_group_wrappers not-imported-wrapper <?php
                    if (@Rbthemeslider::getIsset($m_slider['filter'])) {
                        echo implode(' ', $m_slider['filter']);
                    }

                    ?>">
            <?php
            $tmpl->writeImportTemplateMarkup($m_slider);
            echo '<div class="template_thumb_title">' . $m_slider['title'] . '</div>';

            ?>
                    </div>
            <?php
        }
    }
}

?>
        <div style="clear:both;width:100%"></div>

        <a href="javascript:void(0);" class="rs-visit-store"></a>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('.rs-visit-store').click(function() {
                    UniteAdminRb.ajaxRequest("rs_get_store_information", {}, function(response) {
                        if (response.success == true) {
                            if (typeof(response.data.message) !== 'undefined') {
                                alert(response.data.message);
                            }
                        }

                        return true;
                    });
                });

            });
        </script>
        <div style="clear:both;width:100%"></div>
    </div>

         <?php
         if (!empty($author_template_slider) && is_array($author_template_slider)) {
             foreach ($author_template_slider as $name => $v) {

                 ?>
            <!-- THE rbthemeslider AUTHOR TEMPLATES -->
            <div class="rbthemeslider-<?php echo sanitize_title($name);

                 ?> rbthemeslider-template-groups" style="display: none">
                 <?php
                 if (!empty($v)) {
                     foreach ($v as $m_slider) {
                         if (!empty($m_slider['filter']) && is_array($m_slider['filter'])) {
                             foreach ($m_slider['filter'] as $f => $v) {
                                 $m_slider['filter'][$f] = 'temp_' . $v;
                             }
                         }

                         if (!@Rbthemeslider::getIsset($m_slider['installed']) && !@Rbthemeslider::getIsset($m_slider['is_new'])) {
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
                        foreach ($c_slides as $c_slide) {
                            $c_slide['img'] = $m_slider['img']; //set slide image
                            if (@Rbthemeslider::getIsset($m_slider['preview'])) {
                                $c_slide['preview'] = $m_slider['preview']; //set preview URL
                            }
                            if (@Rbthemeslider::getIsset($m_slider['filter'])) {
                                $c_slide['filter'] = $m_slider['filter']; //add filters 
                            }
                            $c_slide['settings']['width'] = $width;
                            $c_slide['settings']['height'] = $height;

                            if ($c_slide['img'] == '') {
                                //set default image
                            }

                            $tmpl->RbSliderTemplate($c_slide, $c_slider->getID());
                            break;
                        }
                        echo '<div class="template_thumb_title">' . $c_title . '</div>';

                        ?>

                                </div><?php
                        }
                    } else {

                        ?>
                            <div class="template_group_wrappers not-imported-wrapper <?php
                    if (@Rbthemeslider::getIsset($m_slider['filter'])) {
                        echo implode(' ', $m_slider['filter']);
                    }

                    ?>">
                    <?php
                    $tmpl->writeImportTemplateMarkup($m_slider);
                    echo '<div class="template_thumb_title">' . $m_slider['title'] . '</div>';

                    ?>

                            </div>
                    <?php
                }
            }
        }

        ?>
                <div style="clear:both;width:100%"></div>
            </div>
        <?php
    }
}

?>
</div>

<script>
    jQuery("document").ready(function() {
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


        jQuery('.template_slider_item, .template_slider_item_import').each(function() {
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
            //item.css({height:h+"px"});

            var factor = w / gw;

            var htitle = item.closest('.template_group_wrappers').find('h3');
            if (!htitle.hasClass("modificated")) {
                htitle.html(htitle.html() + " (" + gw + "x" + gh + ")").addClass("modificated");
            }
        });

        // CLOSE SLIDE TEMPLATE
        jQuery('#close-template').click(function() {
            jQuery('#template_area').removeClass("show");
        });

        // TEMPLATE TAB CHANGE 
        jQuery('body').on("click", '.rbthemeslider-templatebutton', function() {
            var btn = jQuery(this);
            jQuery('.rbthemeslider-template-groups').each(function() {
                jQuery(this).hide();
            });
            jQuery("." + btn.data("showgroup")).show();
            jQuery('.rbthemeslider-templatebutton').removeClass("selected");
            btn.addClass("selected");
            scrollTA();
            jQuery('#template_area').perfectScrollbar();
        });

        scrollTA();
        jQuery('#template_area').perfectScrollbar();

        function isElementInViewport(element, sctop, wh) {
            var etp = parseInt(element.position().top, 0),
                    ebp = etp + parseInt(element.height(), 0),
                    inviewport = false;

            if ((etp > 0 && etp < parseInt(wh, 0)) || (ebp < parseInt(wh, 0) && ebp > 0))
                inviewport = true;

            return inviewport;
        }

        jQuery('#template_area').on("scroll", function() {
            scrollTA()
        });

        function scrollTA() {
            var ta = jQuery('#template_area'),
                    st = ta.scrollTop(),
                    wh = jQuery(window).height();

            ta.find('.template_slider_item:visible, .template_slider_item_import:visible').each(function() {
                var el = jQuery(this);

                if (el.data('src') != undefined && el.data('bgadded') != 1) {
                    if (isElementInViewport(el, st, wh)) {
                        el.css({backgroundImage: 'url("' + el.data('src') + '")'});
                        el.data('bgadded', 1);
                    }


                }
            });
        }


        jQuery(".input_import_slider").change(function() {
            if (jQuery(this).val() !== '') {
                jQuery('.rs-import-slider-button').show();
            } else {
                jQuery('.rs-import-slider-button').hide();
            }
        });
    });

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


<!-- Import template slider dialog -->
<div id="dialog_import_template_slider" title="<?php $modules->l("Import Template Slider"); ?>" class="dialog_import_template_slider" style="display:none">
    <form action="<?php echo RbSliderBase::$url_ajax; ?>" enctype="multipart/form-data" method="post">
        <input type="hidden" name="action" value="RbSlider_ajax_action">
        <input type="hidden" name="client_action" value="import_slider_template_slidersview">
        <input type="hidden" name="uid" class="rs-uid" value="">

        <p><?php $modules->l('Please select the corresponding zip file from the download packages import folder called'); ?>:</p> 
        <p class="filetoimport"><b><span class="rs-zip-name"></span></b></p>
        <p class="import-file-wrapper"><input type="file" size="60" name="import_file" class="input_import_slider "></p>
        <span style="margin-top:45px;display:block"><input type="submit" class="rs-import-slider-button button-primary rbblue tp-be-button" value="<?php $modules->l("Import Template Slider"); ?>"></span>
        <span class="tp-clearfix"></span>
        <span style="font-weight: 700;"><?php $modules->l("Note: style templates will be updated if they exist!"); ?></span>
        <table style="display: none;">
            <tr>
                <td><?php $modules->l("Custom Animations:"); ?></td>
                <td><input type="radio" name="update_animations" value="true" checked="checked"> <?php $modules->l("overwrite"); ?></td>
                <td><input type="radio" name="update_animations" value="false"> <?php $modules->l("append"); ?></td>
            </tr>
            <tr>
                <td><?php $modules->l("Static Styles:"); ?></td>
                <td><input type="radio" name="update_static_captions" value="true"> <?php $modules->l("overwrite"); ?></td>
                <td><input type="radio" name="update_static_captions" value="false"> <?php $modules->l("append"); ?></td>
                <td><input type="radio" name="update_static_captions" value="none" checked="checked"> <?php $modules->l("ignore"); ?></td>
            </tr>
        </table>
    </form>
</div>


<div id="dialog_import_template_slider_from" title="<?php $modules->l("Import Template Slider"); ?>" class="dialog_import_template_slider_from" style="display:none">
<?php $modules->l('Import Slider from local or from ThemePunch server?'); ?>
    <form action="<?php echo RbSliderBase::$url_ajax; ?>" enctype="multipart/form-data" method="post" name="rs-import-template-from-server" id="rs-import-template-from-server">
        <input type="hidden" name="action" value="rbslider_ajax_action_ajax_action">
        <input type="hidden" name="client_action" value="import_slider_online_template_slidersview">
        <input type="hidden" name="uid" class="rs-uid" value="">
    </form>
</div>

<?php
