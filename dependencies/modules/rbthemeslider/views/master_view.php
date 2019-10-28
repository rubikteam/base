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

$rbSliderVersion = GlobalsRbSlider::SLIDER_RBISION;
$modules = new Rbthemeslider();

$wrapperClass = "";

if (GlobalsRbSlider::$isNewVersion == false) {
    $wrapperClass = " oldps";
}


$rsop = new RbSliderOperations();
$glval = $rsop->getGeneralSettingsValues();

?>
<?php
$waitstyle = '';

if (@Rbthemeslider::getIsset($_REQUEST['update_shop'])) {
    $waitstyle = 'display:block';
}

?>

<div id="waitaminute" style="<?php echo $waitstyle; ?>">
    <div class="waitaminute-message">
        <i class="eg-icon-emo-coffee"></i>
        <br><?php $modules->l("Please Wait..."); ?></div>
</div>

<script type="text/javascript">
    var g_uniteDirPlugin = "rbslider";
    var g_urlContent = "<?php echo str_replace(array("\n", "\r", chr(10), chr(13)), array(''), contentUrl()) . "/"; ?>";
    var g_urlAjaxShowImage = "<?php echo RbSliderBase::$url_ajax_showimage; ?>";
    var g_urlAjaxActions = "<?php echo RbSliderBase::$url_ajax_actions; ?>";
    var g_rbslider_url = "<?php echo _MODULE_DIR_ . 'rbthemeslider/'; ?>";
    var g_settingsObj = {};

    var global_grid_sizes = {
        'desktop': '<?php echo RbSliderBase::getVar($glval, 'width', 1230); ?>',
        'notebook': '<?php echo RbSliderBase::getVar($glval, 'width_notebook', 1230); ?>',
        'tablet': '<?php echo RbSliderBase::getVar($glval, 'width_tablet', 992); ?>',
        'mobile': '<?php echo RbSliderBase::getVar($glval, 'width_mobile', 480); ?>'
    };

</script>



<div id="rs-preview-wrapper" style="display: none;">
    <div id="rs-preview-wrapper-inner">
        <div id="rs-preview-info">
            <div class="rs-preview-toolbar">
                <a class="rs-close-preview"><i class="eg-icon-cancel"></i></a>
            </div>

            <div data-type="desktop" class="rs-preview-device_selector_prev rs-preview-ds-desktop selected"></div>									
            <div data-type="notebook" class="rs-preview-device_selector_prev rs-preview-ds-notebook"></div>					
            <div data-type="tablet" class="rs-preview-device_selector_prev rs-preview-ds-tablet"></div>					
            <div data-type="mobile" class="rs-preview-device_selector_prev rs-preview-ds-mobile"></div>

        </div>
        <div class="rs-frame-preview-wrapper">
            <iframe id="rs-frame-preview" name="rs-frame-preview"></iframe>
        </div>
    </div>
</div>
<form id="rs-preview-form" name="rs-preview-form" action="<?php echo RbSliderBase::$url_ajax_actions; ?>" target="rs-frame-preview" method="post">
    <input type="hidden" id="rs-client-action" name="client_action" value="">
    <input type="hidden" name="data" value="" id="preview-slide-data">
    <input type="hidden" id="preview_sliderid" name="sliderid" value="">
    <input type="hidden" id="preview_slider_markup" name="only_markup" value="">
</form>

<div id="dialog_preview_sliders" class="dialog_preview_sliders" title="Preview Slider" style="display:none;">
    <iframe id="frame_preview_slider" name="frame_preview_slider" style="width: 100%;"></iframe>
</div>

<script type="text/javascript">



<?php
$sds_admin_url = adminUrl();
$sds_admin_upload_url = controllerUploadUrl('&view=dialog');
?>
    
    var rb_php_ver = '<?php echo phpversion() ?>';
    var g_uniteDirPlagin = "<?php echo RbSliderAdmin::$dir_plugin ?>";
    var g_urlContent = "<?php echo UniteFunctionsPSRb::getUrlContent() ?>";

    ajaxurl += '&returnurl=<?php echo urlencode(htmlspecialchars_decode($sds_admin_url)) ?>';
    var uploadurl = '<?php echo htmlspecialchars_decode($sds_admin_upload_url) ?>';
    var g_urlAjaxShowImage = "<?php echo htmlspecialchars_decode(UniteBaseClassRb::$url_ajax_showimage) ?>";
    var g_urlAjaxActions = "<?php echo htmlspecialchars_decode(UniteBaseClassRb::$url_ajax_actions) ?>";
    var g_settingsObj = {};

    // Preview Scripts
    jQuery('body').on('click', '.rs-preview-device_selector_prev', function() {
        var btn = jQuery(this);
        jQuery('.rs-preview-device_selector_prev.selected').removeClass("selected");
        btn.addClass("selected");

        var w = parseInt(global_grid_sizes[btn.data("type")], 0);
        if (w > 1450)
            w = 1450;
        jQuery('#rs-preview-wrapper-inner').css({maxWidth: w + "px"});

    });

    jQuery(window).resize(function() {
        var ww = jQuery(window).width();
        if (global_grid_sizes)
            jQuery.each(global_grid_sizes, function(key, val) {
                if (ww <= parseInt(val, 0)) {
                    jQuery('.rs-preview-device_selector_prev.selected').removeClass("selected");
                    jQuery('.rs-preview-device_selector_prev[data-type="' + key + '"]').addClass("selected");
                }
            })
    })


    /* SHOW A WAIT FOR PROGRESS */
    function showWaitAMinute(obj) {
        var wm = jQuery('#waitaminute');
        // SHOW AND HIDE WITH DELAY
        if (obj.delay != undefined) {

            punchgs.TweenLite.to(wm, 0.3, {autoAlpha: 1, ease: punchgs.Power3.easeInOut});
            punchgs.TweenLite.set(wm, {display: "block"});

            setTimeout(function() {
                punchgs.TweenLite.to(wm, 0.3, {autoAlpha: 0, ease: punchgs.Power3.easeInOut, onComplete: function() {
                        punchgs.TweenLite.set(wm, {display: "block"});
                    }});
            }, obj.delay)
        }

        // SHOW IT
        if (obj.fadeIn != undefined) {
            punchgs.TweenLite.to(wm, obj.fadeIn / 1000, {autoAlpha: 1, ease: punchgs.Power3.easeInOut});
            punchgs.TweenLite.set(wm, {display: "block"});
        }

        // HIDE IT
        if (obj.fadeOut != undefined) {

            punchgs.TweenLite.to(wm, obj.fadeOut / 1000, {autoAlpha: 0, ease: punchgs.Power3.easeInOut, onComplete: function() {
                    punchgs.TweenLite.set(wm, {display: "block"});
                }});
        }

        // CHANGE TEXT
        if (obj.text != undefined) {
            switch (obj.text) {
                case "progress1":

                    break;
                default:
                    wm.html('<div class="waitaminute-message"><i class="eg-icon-emo-coffee"></i><br>' + obj.text + '</div>');
                    break;
            }
        }
    }
</script>

<div id="div_debug"></div>
<div class='unite_error_message' id="error_message" style="display:none;"></div>
<div class='unite_success_message' id="success_message" style="display:none;"></div>
<div id="viewWrapper" class="view_wrapper<?php echo $wrapperClass ?>">

<?php
$view = RbGlobalObject::getVar('view');
RbSliderAdmin::requireView($view);

?>

</div>

<div id="divColorPicker" style="display:none;"></div>

<?php RbSliderAdmin::requireView("system/video_dialog") ?>

<?php RbSliderAdmin::requireView("system/update_dialog") ?>

<?php RbSliderAdmin::requireView("system/general_settings_dialog") ?>

<div class="tp-plugin-version">
    <div class="smartsupport" style="float: left;">Open ticket in our <a href="https://smartdatasoft.zendesk.com" target="_blank"><strong>support</strong></a> system if you found issues. Follow our <a href="#" target="_blank"><strong>documentation</strong></a> page to get usability informations.</div>
    <div class="rb_copyright" style="float: right;">&copy; All rights reserved, <a href="http://themepunch.com" target="_blank">Themepunch</a>  ver. <?php echo $rbSliderVersion ?></div>
</div>

<?php if (GlobalsRbSlider::SHOW_DEBUG == true): ?>

    Debug Functions (for developer use only):

    <br><br>

    <a id="button_update_text" class="button-primary rbpurple" href="javascript:void(0)">Update Text</a>

<?php endif;
