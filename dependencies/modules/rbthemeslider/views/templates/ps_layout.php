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

<div class="title_line nobgnopd"><div class="view_title"><?php echo $modules->l('Create Custom Hook'); ?></div></div>
<div id="eg-grid-custom-hook-wrapper">
    <?php
    $fonts = new SdsRbHooksClass();
    $custom_fonts = $fonts->getAllHooks();

    if (!empty($custom_fonts)) {
        foreach ($custom_fonts as $font) {
            $cur_font = $font['hookname'];
            $title = $font['hookname'];

            ?>
            <div class="postbox eg-postbox" style="width:100%;min-width:500px">
                <h3 class="box-closed">
                    <span style="font-weight:400"><?php echo $modules->l('Hook_Name');

        ?>
                    </span>
                    <span style="text-transform:uppercase;"><?php echo $title;

        ?></span>
                    <div class="fontpostbox-arrow"></div>
                </h3>

                <div class="inside" style="display:none;padding:0px !important;margin:0px !important;height:100%;position:relative;background:#ebebeb">
                    <div class="tp-googlefont-row">
                        <div class="eg-cus-row-l"><label><?php echo $modules->l('Hook Name');

            ?></label><input type="text" name="esg-hook-name[]" value="<?php echo @$font['hookname'];

            ?>"></div>

                            <?php echo $modules->l('Custom Hook Desc');

                            ?><span><strong> {hook h="<?php echo $title;

                    ?>"}</strong></span>
                    </div>
                    <div class="tp-googlefont-save-wrap-settings">
                        <a class="button-primary rbred eg-hook-delete" href="javascript:void(0);"><?php echo $modules->l('Remove');

                            ?></a>
                    </div>
                </div>
            </div>
        <?php
    }
}

?>
</div>

<a class="button-primary rbblue" id="eg-hook-add" href="javascript:void(0);"><?php echo $modules->l('Add New Hook'); ?></a>
<div id="hook-dialog-wrap" class="essential-dialog-wrap" title="<?php echo $modules->l('Add Hook'); ?>"  style="display: none; padding:20px !important;">
    <div class="tp-googlefont-cus-row-l"><label><?php echo $modules->l('Hook Name'); ?>:</label><input type="text" name="eg-hook-name" value="" />
    </div>
</div>


<script type="text/javascript">
    jQuery(function() {
        UniteAdminRb.inithooksetting();
        UniteAdminRb.initfontAccordion();
    });
</script>

<?php
