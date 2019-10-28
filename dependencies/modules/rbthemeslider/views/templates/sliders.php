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

$exampleID = '"slider1"';
$dir = pluginDirPath();
$arrSliders = RbGlobalObject::getVar('arrSliders');
$modules = new Rbthemeslider();

if (!empty($arrSliders)) {
    $exampleID = '"' . $arrSliders[0]->getAlias() . '"';
}

$outputTemplates = false;
$latest_version = getOption('rbslider-latest-version', GlobalsRbSlider::SLIDER_RBISION);

?>

<div class='wrap'>
    <div class="clear_both"></div> 
    <div class="title_line" style="margin-bottom:10px">
        <a href="<?php echo GlobalsRbSlider::LINK_HELP_SLIDERS ?>" class="button-secondary float_right mtop_10 mleft_10" target="_blank"><?php echo $modules->l('Help'); ?></a>			
    </div>
    <div class="clear_both"></div> 
    <div class="title_line nobgnopd">
        <div class="view_title">
<?php echo  $modules->l('Rb Theme Sliders'); ?>
        </div>
        <div class="slider-sortandfilter">
            <span class="slider-listviews slider-lg-views" data-type="rs-listview"><i class="eg-icon-align-justify"></i></span>
            <span class="slider-gridviews slider-lg-views active" data-type="rs-gridview"><i class="eg-icon-th"></i></span>
        </div>
    </div>
    <?php
    $no_sliders = false;
    if (empty($arrSliders)) {

        ?>
        <span style="display:block;margin-top:15px;margin-bottom:15px;">
            <?php
            echo  $modules->l('No Sliders Found');
            $no_sliders = true;

            ?>
        </span>
        <?php
    }
    require self::getPathTemplate("sliders_list");

    ?>
    <div style="width:100%;height:50px"></div>
    <div style="width:100%;height:17px"></div>
    <div class="tab-data">
<?php

require self::getPathTemplate("ps_layout");

?>
    </div>

    <div style="width:100%;height:50px"></div>
    <div id="dialog_import_slider" title="<?php echo  $modules->l('Import Slider'); ?>" class="dialog_import_slider" style="display:none">
        <form action="<?php echo UniteBaseClassRb::$url_ajax ?>" enctype="multipart/form-data" method="post">
            <br>
            <input type="hidden" name="action" value="rbslider_ajax_action">
            <input type="hidden" name="client_action" value="import_slider_slidersview">
<?php echo  $modules->l('Choose Import File'); ?>:   
            <br>
            <input type="file" size="60" name="import_file" class="input_import_slider">
            <br><br>
            <span style="font-weight: 700;"><?php echo  $modules->l('CUSTOM STYLES'); ?></span><br><br>
            <table class="impo_slide">
                <tr>
                    <td><?php echo  $modules->l('Custom Animations'); ?></td>
                    <td><input type="radio" name="update_animations" value="true" checked="checked"> <?php echo  $modules->l('Overwrite'); ?></td>
                    <td><input type="radio" name="update_animations" value="false"> <?php echo  $modules->l('Append'); ?></td>
                </tr>
                <tr>
                    <td><?php echo  $modules->l('Custom Navigations:'); ?></td>
                    <td><input type="radio" name="update_navigations" value="true" checked="checked"> <?php echo $modules->l('Overwrite'); ?> </td>
                    <td><input type="radio" name="update_navigations" value="false"> <?php echo $modules->l('Append'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $modules->l('Static Styles'); ?></td>
                    <td><input type="radio" name="update_static_captions" value="true"> <?php echo $modules->l('Overwrite'); ?></td>
                    <td><input type="radio" name="update_static_captions" value="false"> <?php echo $modules->l('Append'); ?></td>
                    <td><input type="radio" name="update_static_captions" value="none" checked="checked"> ignore</td>
                </tr>
            </table>
            <br><br>
            <input type="submit" class='button-primary rb-import-slider-button' value="<?php echo $modules->l('Import Slider'); ?>">
        </form>		
    </div>

    <div id="dialog_duplicate_slider" class="dialog_duplicate_layer" title="<?php $modules->l('Duplicate'); ?>" style="display:none;">
        <div style="margin-top:14px">
            <span style="margin-right:15px"><?php $modules->l('Title:'); ?></span><input id="rs-duplicate-animation" type="text" name="rs-duplicate-animation" value="" />
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            RbSliderAdmin.initSlidersListView();
            jQuery('#benefitsbutton').hover(function() {
                jQuery('#benefitscontent').slideDown(200);
            }, function() {
                jQuery('#benefitscontent').slideUp(200);
            })
            jQuery('#tp-validation-box').click(function() {
                jQuery(this).css({cursor: "default"});
                if (jQuery('#rs-validation-wrapper').css('display') == "none") {
                    jQuery('#tp-before-validation').hide();
                    jQuery('#rs-validation-wrapper').slideDown(200);
                }
            })
        });
    </script>
<?php
