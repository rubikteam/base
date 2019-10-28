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
<div class="wrap settings_wrap">
    <?php $slider = RbGlobalObject::getVar('slider') ?>

    <div class="title_line">
        <div class="view_title"></div>
    </div>

    <div class="vert_sap"></div>

    <?php echo $modules->l('Multiple Sources'); ?> &nbsp;

    <?php if (RbGlobalObject::getVar('showSortBy') == true): ?> 

        <?php echo $modules->l('Sort By'); ?>: <?php echo RbGlobalObject::getVar('selectSortBy') ?> &nbsp; <span class="hor_sap"></span>

    <?php endif ?>

    <span id="slides_top_loader" class="slides_posts_loader" style="display:none;">
        <?php echo $modules->l('Updating Sorting'); ?>
    </span>

    <div class="vert_sap"></div>

    <div class="sliders_list_container">
        <?php require self::getPathTemplate("slides_list_posts"); ?>
    </div>

    <div class="vert_sap_medium"></div>

    <div class="list_slides_bottom">
        <a class="button-primary rbyellow" href='<?php echo self::getViewUrl(RbSliderAdmin::VIEW_SLIDERS); ?>' ><i class="rbicon-cancel"></i><?php echo $modules->l('Close'); ?></a>
        <a href="<?php echo RbGlobalObject::getVar('linksSliderSettings') ?>" class="button-primary rbgreen"><i class="rbicon-cog"></i><?php echo $modules->l('Slider Settings'); ?></a>
    </div>
</div>

<script type="text/javascript">
    var g_messageDeleteSlide = "<?php echo $modules->l('Warning Removing'); ?>";
    var g_messageChangeImage = "<?php echo $modules->l('Select Slide Image'); ?>";

    jQuery(document).ready(function() {
        RbSliderAdmin.initSlidesListViewPosts("<?php echo RbGlobalObject::getVar('sliderID') ?>");
    });
</script>
<?php
