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

$slider = RbGlobalObject::getVar('slider');
$modules = new Rbthemeslider();

?>
<div class="wrap settings_wrap">
    <div class="clear_both"></div> 

    <div class="title_line">
        <a href="<?php echo GlobalsRbSlider::LINK_HELP_SLIDE_LIST ?>" class="button-secondary float_right mtop_10 mleft_10" target="_blank">
            <?php echo $modules->l('Help'); ?>
        </a>			
    </div>

    <div class="vert_sap"></div>
    <?php if (RbGlobalObject::getVar('numSlides') >= 5) {

        ?>
        <a class='button-primary rbblue' id="button_new_slide_top" href='javascript:void(0)' ><i class="rbicon-list-add"></i><?php echo $modules->l('New Slide');

    ?></a>

        <a class='button-primary rbblue' id="button_new_slide_transparent_top" href='javascript:void(0)' ><i class="rbicon-list-add"></i><?php echo $modules->l('New Transparent');

        ?></a>
        <span class="loader_round new_trans_slide_loader" style="display:none"><?php echo $modules->l('Adding Slide');

        ?></span>		

        <a class="button-primary rbyellow" href='<?php echo self::getViewUrl(RbSliderAdmin::VIEW_SLIDERS);

       ?>' ><i class="rbicon-cancel"></i><?php echo $modules->l('Close');

        ?></a>
            <?php }

        ?>

<?php if (RbGlobalObject::getVar('psmlActive') == true) {

    ?>
        <div id="langs_float_wrapper" class="langs_float_wrapper" style="display:none">
    <?php echo RbGlobalObject::getVar('langFloatMenu') ?>
        </div>
    <?php }

?>

    <div class="vert_sap"></div>
    <div class="sliders_list_container">
       <?php require self::getPathTemplate("slides_list"); ?>
    </div>
    <div class="vert_sap_medium"></div>
    <a class='button-primary rbblue' id="button_new_slide" data-dialogtitle="<?php echo $modules->l('Select Image'); ?>" href='javascript:void(0)' ><i class="rbicon-list-add"></i><?php echo $modules->l('New Slide'); ?></a>
    <a class='button-primary rbblue' id="button_new_slide_transparent" href='javascript:void(0)' ><i class="rbicon-list-add"></i><?php echo $modules->l('New Transparent'); ?></a>
    <span class="loader_round new_trans_slide_loader" style="display:none"><?php echo $modules->l('Adding Slide'); ?></span>
    <?php
    if (RbGlobalObject::getVar('useStaticLayers') == 'on') {

        ?>		
        <a class='button-primary rbgray' href='<?php echo self::getViewUrl(RbSliderAdmin::VIEW_SLIDE, "id=static_" . $slider->getID());

        ?>' style="width:190px; "><i class="eg-icon-dribbble"></i><?php echo $modules->l('Static Global');

        ?></a>
    <?php
}

?>
    <a class="button-primary rbyellow" href='<?php echo self::getViewUrl(RbSliderAdmin::VIEW_SLIDERS); ?>' ><i class="rbicon-cancel"></i><?php echo $modules->l('Close'); ?></a>		
    <a href="<?php echo RbGlobalObject::getVar('linksSliderSettings') ?>" class="button-primary rbgreen"><i class="rbicon-cog"></i><?php echo $modules->l('Slider Settings'); ?></a>		

</div>

<div id="dialog_copy_move" data-textclose="<?php echo $modules->l('Close'); ?>" data-textupdate="<?php echo $modules->l('Do It'); ?>" title="<?php echo $modules->l('Copy Move Slide'); ?>" style="display:none">

    <br>

<?php echo $modules->l('Choose Slider'); ?> :
<?php echo RbGlobalObject::getVar('selectSliders') ?>

    <br><br>

<?php echo $modules->l('Choose Operation'); ?> :

    <input type="radio" id="radio_copy" value="copy" name="copy_move_operation" checked />
    <label for="radio_copy" style="cursor:pointer;"><?php echo $modules->l('Copy'); ?></label>
    &nbsp; &nbsp;
    <input type="radio" id="radio_move" value="move" name="copy_move_operation" />
    <label for="radio_move" style="cursor:pointer;"><?php echo $modules->l('Move'); ?></label>		

</div>

<?php require self::getPathTemplate("dialog_preview_slide"); ?>

<script type="text/javascript">
    var g_patternViewSlide = '<?php echo RbGlobalObject::getVar('patternViewSlide') ?>';
    var g_messageChangeImage = "<?php echo $modules->l('Select Slide Image'); ?>";

    jQuery(document).ready(function() {
        var g_messageDeleteSlide = "<?php echo $modules->l('Delete This Slide'); ?>";
        RbSliderAdmin.initSlidesListView("<?php echo RbGlobalObject::getVar('sliderID') ?>");
    });
</script>

<?php
