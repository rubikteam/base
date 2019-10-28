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

<div class="title_line nobgnopd"><div class="view_title"><?php echo $modules->l('Punch Fonts'); ?></div></div>
<div id="eg-grid-google-font-wrapper">
    <?php
    $fonts = new ThemePunchFonts();
    $custom_fonts = $fonts->getAllFonts();

    if (!empty($custom_fonts)) {
        foreach ($custom_fonts as $font) {
            $cur_font = $font['url'];
            $cur_font = explode('+', $cur_font);
            $cur_font = implode(' ', $cur_font);
            $cur_font = explode(':', $cur_font);

            $title = $cur_font['0'];

            ?>
            <div class="postbox eg-postbox" style="width:100%;min-width:500px">
                <h3 class="box-closed"><span style="font-weight:400"><?php echo $modules->l('Font Family');

            ?></span><span style="text-transform:uppercase;"><?php echo $title;

                ?> </span><div class="postbox-arrow"></div></h3>
                <div class="inside" style="display:none;padding:0px !important;margin:0px !important;height:100%;position:relative;background:#ebebeb">
                    <div class="tp-googlefont-row">
                        <div class="eg-cus-row-l"><label><?php echo $modules->l('Handle');

                ?></label> tp-<input type="text" name="esg-font-handle[]" value="<?php echo @$font['handle'];

            ?>" readonly="readonly"></div>
                        <div class="eg-cus-row-l"><label><?php echo $modules->l('Parameter');

                                   ?></label><input type="text" name="esg-font-url[]" value="<?php echo @$font['url'];

                                   ?>"></div>
                    </div>
                    <div class="tp-googlefont-save-wrap-settings">
                        <a class="button-primary rbblue eg-font-edit" href="javascript:void(0);"><?php echo $modules->l('Edit');

            ?></a>
                        <a class="button-primary rbred eg-font-delete" href="javascript:void(0);"><?php echo $modules->l('Remove');

            ?></a>
                    </div>
                </div>
            </div>
        <?php
    }
}

?>
    <div>
        <i style="font-size:10px;color:#777"><?php echo html_entity_decode($modules->l('Google Font Desc')); ?></i>
    </div>
</div>

<a class="button-primary rbblue" id="eg-font-add" href="javascript:void(0);"><?php echo $modules->l('Add New Font'); ?></a>

<div id="font-dialog-wrap" class="essential-dialog-wrap" title="<?php echo $modules->l('Add Font'); ?>"  style="display: none; padding:20px !important;">

    <div class="tp-googlefont-cus-row-l"><label><?php echo $modules->l('Handle'); ?></label><span style="margin-left:-20px;margin-right:2px;"><strong>tp-</strong></span><input type="text" name="eg-font-handle" value="" /></div>
    <div style="margin-top:0px; padding-left:100px; margin-bottom:20px;">
        <i style="font-size:12px;color:#777; line-height:20px;"><?php echo $modules->l('Unique Handle'); ?></i>
    </div>
    <div class="tp-googlefont-cus-row-l"><label><?php echo $modules->l('Parameter'); ?></label><input type="text" name="eg-font-url" value="" /></div>
    <div style="padding-left:100px;">
        <i style="font-size:12px;color:#777; line-height:20px;"><?php echo html_entity_decode($modules->l('Google Font Desc')); ?></i>
    </div>

</div>

<script type="text/javascript">
    jQuery(function() {
        UniteAdminRb.initGoogleFonts();
        UniteAdminRb.initAccordion();
    });
</script>

<?php
