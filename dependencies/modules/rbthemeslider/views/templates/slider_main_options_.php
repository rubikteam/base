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

$operations = new RbSliderOperations();
$rs_nav = new RbSliderNavigation();
$arrValues = $operations->getGeneralSettingsValues();
$arr_navigations = $rs_nav->getAllNavigations();
$modules = new Rbthemeslider();
$transitions = $operations->getArrTransition();

$_width = (@Rbthemeslider::getIsset($arrValues['width'])) ? $arrValues['width'] : 1240;
$_width_notebook = (@Rbthemeslider::getIsset($arrValues['width_notebook'])) ? $arrValues['width_notebook'] : 1024;
$_width_tablet = (@Rbthemeslider::getIsset($arrValues['width_tablet'])) ? $arrValues['width_tablet'] : 778;
$_width_mobile = (@Rbthemeslider::getIsset($arrValues['width_mobile'])) ? $arrValues['width_mobile'] : 480;

$is_edit = RbGlobalObject::getVar('is_edit');
$linksEditSlides = RbGlobalObject::getVar('linksEditSlides');
$sliderID = RbGlobalObject::getVar('sliderID');

if (!@Rbthemeslider::getIsset($is_edit)) {
    $is_edit = false;
}
if (!@Rbthemeslider::getIsset($linksEditSlides)) {
    $linksEditSlides = '';
}

?>

<input type="hidden" id="sliderid" value="<?php echo $sliderID ?>"></input>

<div class="wrap settings_wrap">
    <div class="clear_both"></div> 

    <div class="title_line">
        <?php
        if (RbGlobalObject::getVar('sliderTemplate')) {

            ?>
            <div class="view_title"><?php echo $modules->l('Edit Slider Template');

            ?></div>
            <?php
            $template_value = 'true';
        } else {

            ?>
            <div class="view_title"><?php echo $modules->l('Edit_Slider');

            ?></div>
            <?php
            $template_value = 'false';
        }

        ?>
        <input type="hidden" id="rbslider_template" value="<?php echo $template_value; ?>"></input>

        <a href="<?php echo GlobalsRbSlider::LINK_HELP_SLIDER ?>" class="button-secondary float_right mtop_10 mleft_10" target="_blank"><?php echo $modules->l('help'); ?></a>			

    </div>

    <div class="rs_breadcrumbs">
        <div class="rs-breadcrumbs-wrapper">
            <a class='breadcrumb-button' href='<?php echo self::getViewUrl("sliders"); ?>'><i class="eg-icon-th-large"></i><?php echo $modules->l('All Sliders'); ?></a>
            <a class='breadcrumb-button selected' href="#"><i class="eg-icon-cog"></i><?php echo $modules->l('Slider_Settings'); ?></a>
            <a class='breadcrumb-button' href="<?php echo $linksEditSlides; ?>"><i class="eg-icon-pencil-2"></i><?php echo $modules->l('Slide Editor'); ?></a>
        </div>
        <div class="tp-clearfix"></div>
        <div class="rs-mini-toolbar">
            <div class="rs-mini-toolbar-button rs-toolbar-savebtn">
                <a original-title="" class="button-primary rbgreen" href="javascript:void(0)" id="button_save_slider_t"><i class="rs-icon-save-light" style="display: inline-block;vertical-align: middle;width: 18px;height: 20px;background-repeat: no-repeat;margin-right:10px;margin-left:2px;"></i><span class="mini-toolbar-text"></i><?php echo $modules->l('Save Settings'); ?></span></a>
                <span id="loader_update_t" class="loader_round" style="display:none;background-color:#27AE60 !important; color:#fff;padding: 5px 5px 6px 25px;margin-right: 5px;">updating... </span>
                <span id="update_slider_success_t" class="success_message"></span>
            </div>
                   <?php
                   if (@Rbthemeslider::getIsset($linksEditSlides)) {

                       ?>
                <div class="rs-mini-toolbar-button rs-toolbar-slides">
                    <a original-title="" class="button-primary rbblue" href="<?php echo $linksEditSlides;

                       ?>" id="link$modules->ldit_slides_t"><i class="rbicon-pencil-1"></i><span class="mini-toolbar-text">Edit Slides</span></a>
                </div>
    <?php
}

?>
            <div class="rs-mini-toolbar-button  rs-toolbar-preview">
                <a original-title="" class="button-primary rbgray" href="javascript:void(0)" id="button_preview_slider_t"><i class="rbicon-search-1"></i><span class="mini-toolbar-text"><?php echo $modules->l('Preview'); ?></span></a>
            </div>
            <div class="rs-mini-toolbar-button  rs-toolbar-delete">
                <a original-title="" class="button-primary rbred" id="button_delete_slider_t" href="javascript:void(0)"><i class="rbicon-trash"></i><span class="mini-toolbar-text">Delete Slider</span></a>
            </div>
            <div class="rs-mini-toolbar-button  rs-toolbar-close">                                                
                <a original-title="" class="button-primary rbyellow" id="button_close_slider_edit_t" href="<?php echo adminUrl('admin.php?view=sliders'); ?>"><i class="eg-icon-th-large"></i><span class="mini-toolbar-text"><?php echo $modules->l('All Sliders'); ?></span></a>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function() {
            jQuery('.rs-mini-toolbar-button').hover(function() {
                var btn = jQuery(this),
                        txt = btn.find('.mini-toolbar-text');
                punchgs.TweenLite.to(txt, 0.2, {width: "100px", ease: punchgs.Linear.easeNone, overwrite: "all"});
                punchgs.TweenLite.to(txt, 0.1, {autoAlpha: 1, ease: punchgs.Linear.easeNone, delay: 0.1, overwrite: "opacity"});
            }, function() {
                var btn = jQuery(this),
                        txt = btn.find('.mini-toolbar-text');
                punchgs.TweenLite.to(txt, 0.2, {autoAlpha: 0, width: "0px", ease: punchgs.Linear.easeNone, overwrite: "all"});
            });
            var mtb = jQuery('.rs-mini-toolbar'),
                    mtbo = mtb.offset().top;
            jQuery(document).on("scroll", function() {

                if (mtbo - jQuery(window).scrollTop() < 35)
                    mtb.addClass("sticky");
                else
                    mtb.removeClass("sticky");

            });
            jQuery(document).on('keydown', function(event) {
                if (event.ctrlKey || event.metaKey) {
                    switch (String.fromCharCode(event.which).toLowerCase()) {
                        case 's':
                            event.preventDefault();
                            jQuery('#button_save_slider_t').click();
                            break;
                    }
                }
            });
        });
    </script>

    <div class="settings_panel">

        <div class="settings_panel_left settings_wrapper">

            <form name="form_slider_main" id="form_slider_main">
                <input type="hidden" name="hero_active" value="<?php $arrFieldsParams = RbGlobalObject::getVar('arrFieldsParams');
echo RbSliderFunctions::getVal($arrFieldsParams, 'hero_active', -1); ?>" />
                <div class="setting_box">
                    <h3><span class="setting-step-number">1</span><span>Content Source</span></h3>
                    <div class="inside tp-clearfix" style="padding:0px;">
                        <div class="source-selector-wrapper">
<?php $source_type = RbSliderFunctions::getVal($arrFieldsParams, 'source_type', 'gallery'); ?>
                            <span class="rs-source-selector selected">
                                <span class="rs-source-image rssi-default"></span>
                                <input type="radio" id="source_type_3" value="gallery" name="source_type" <?php checked($source_type, 'gallery'); ?> />
                                <span class="rs-source-label">Default Slider</span>
                            </span>

                            <span class="rs-source-selector">
                                <span class="rs-source-image rssi-post"></span>
                                <input type="radio" id="source_type_1" value="posts" name="source_type" <?php checked($source_type, 'posts'); ?> />
                                <span class="rs-source-label">Category Based Products</span>
                            </span>
                            <span class="rs-source-selector">
                                <span class="rs-source-image rssi-post"></span>
                                <input type="radio" id="source_type_2" value="specific_posts" name="source_type" <?php checked($source_type, 'specific_posts'); ?> />
                                <span class="rs-source-label">Specific Products</span>
                            </span>
                            <span class="rs-source-selector">
                                <span class="rs-source-image rssi-flickr"></span>
                                <input type="radio" id="source_type_3" value="flickr" name="source_type" <?php checked($source_type, 'flickr'); ?> />
                                <span class="rs-source-label">Flickr Stream</span>
                            </span>
                            <span class="rs-source-selector">
                                <span class="rs-source-image rssi-instagram"></span>
                                <input type="radio" id="source_type_4" value="instagram" name="source_type" <?php checked($source_type, 'instagram'); ?> />
                                <span class="rs-source-label">Instagram Stream</span>
                            </span>
                            <span class="rs-source-selector">
                                <span class="rs-source-image rssi-twitter"></span>
                                <input type="radio" id="source_type_6" value="twitter" name="source_type" <?php checked($source_type, 'twitter'); ?> />
                                <span class="rs-source-label">Twitter Stream</span>
                            </span>
                            <span class="rs-source-selector">
                                <span class="rs-source-image rssi-facebook"></span>
                                <input type="radio" id="source_type_7" value="facebook" name="source_type" <?php checked($source_type, 'facebook'); ?> />
                                <span class="rs-source-label">Facebook Stream</span>
                            </span>
                            <span class="rs-source-selector">
                                <span class="rs-source-image rssi-youtube"></span>
                                <input type="radio" id="source_type_8" value="youtube" name="source_type" <?php checked($source_type, 'youtube'); ?> />
                                <span class="rs-source-label">YouTube Stream</span>
                            </span>
                            <span class="rs-source-selector">
                                <span class="rs-source-image rssi-vimeo"></span>
                                <input type="radio" id="source_type_9" value="vimeo" name="source_type" <?php checked($source_type, 'vimeo'); ?> />
                                <span class="rs-source-label">Vimeo Stream</span>
                            </span>
                            <div class="tp-clearfix"></div>
                        </div>
                        <script>
                            jQuery(document).on("ready", function() {
                                function rsSelectorFun() {
                                    jQuery('.rs-source-selector').removeClass("selected");
                                    jQuery('.source-selector-wrapper input:checked').closest(".rs-source-selector").addClass("selected");
                                }
                                jQuery('.source-selector-wrapper input').change(rsSelectorFun);
                                rsSelectorFun();
                                jQuery('.source-selector-wrapper input:checked').trigger('click');
                            })
                        </script>
                    </div>
                    <div id="rs-instagram-settings-wrapper" class="rs-settings-wrapper">
                        <div style="width:50%;display:block;float:left;">
                            <span class="rb-new-label">Slides (max 33)</span>
                            <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'instagram-count', ''); ?>" name="instagram-count" title="Display this number of photos">
                            <p>
                                <span class="rb-new-label">Access Token</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'instagram-access-token', ''); ?>" name="instagram-access-token" title="Put in your Instagram Access Token">
                            </p>
                            <p>
                                <span class="description">Get your Instagram Access Token <a target="_blank" href="http://www.pinceladasdaweb.com.br/instagram/access-token/">here</a></span>
                            </p>
                        </div>
                        <div style="width:50%;display:block;float:left;">
                            <span class="rb-new-label">Instagram User ID</span>
                            <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'instagram-user-id', ''); ?>" name="instagram-user-id" title="Put in the Instagram User ID">
                            <p>
                                <span class="description">Find the Instagram User ID <a target="_blank" href="http://www.otzberg.net/iguserid/">here</a></span>
                            </p>
                        </div>
                    </div>
                    <div id="rs-flickr-settings-wrapper" class="rs-settings-wrapper">
                        <div style="width:50%;display:block;float:left;">
                            <span class="rb-new-label">Slides (max 500)</span>
                            <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'flickr-count', ''); ?>" name="flickr-count" title="Display this number of photos">
                            <p>
                                <span class="rb-new-label">Flickr API Key</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'flickr-api-key', ''); ?>" name="flickr-api-key" title="Put in your Flickr API Key">
                            </p>
                            <span class="description">Read <a target="_blank" href="http://weblizar.com/get-flickr-api-key/">here</a> how to receive your Flickr API key</span>
                        </div>
                        <div style="width:50%;display:block;float:left;">
                            <span class="rb-new-label">Source</span>
                            <select name="flickr-type">
                                <option value="publicphotos" <?php Rbthemeslider::selected(UniteFunctionsRb::getVal($arrFieldsParams, 'flickr-type', 'publicphotos'), 'publicphotos') ?> title="Display a user's public photos">User Public Photos</option>
                                <option value="photosets" <?php Rbthemeslider::selected(UniteFunctionsRb::getVal($arrFieldsParams, 'flickr-type', 'publicphotos'), 'photosets') ?> title="Display a certain photoset from a user">User Photoset</option>
                                <option value="gallery" <?php Rbthemeslider::selected(UniteFunctionsRb::getVal($arrFieldsParams, 'flickr-type', 'publicphotos'), 'gallery') ?> title="Display a gallery">Gallery</option>
                                <option value="group" <?php Rbthemeslider::selected(UniteFunctionsRb::getVal($arrFieldsParams, 'flickr-type', 'publicphotos'), 'group') ?> title="Display a group's photos"> Groups' Photos</option>
                            </select>
                            <div id="flickr-publicphotos-url-wrap">
                                <p>
                                    <span class="rb-new-label">Flickr User Url</span>
                                    <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'flickr-user-url'); ?>" name="flickr-user-url" title="Put in the URL of the flickr User">
                                </p>
                            </div>
                            <div id="flickr-photosets-wrap">
                                <p>
                                    <span class="rb-new-label">Select Photoset</span>
                                    <input type="hidden" name="flickr-photoset" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'flickr-photoset', ''); ?>">
                                    <select name="flickr-photoset-select" title="Select the photoset to pull the data from">
                                    </select>
                                </p>
                            </div>
                            <div id="flickr-gallery-url-wrap">
                                <p>
                                    <span class="rb-new-label">Flickr Gallery Url</span>
                                    <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'flickr-gallery-url'); ?>" name="flickr-gallery-url" title="Put in the URL of the flickr Gallery">
                                </p>
                            </div>
                            <div id="flickr-group-url-wrap">
                                <p>
                                    <span class="rb-new-label">Flickr Group Url</span>
                                    <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'flickr-group-url'); ?>" name="flickr-group-url" title="Put in the URL of the flickr Group">
                                </p>
                            </div>
                        </div>
                    </div>

                    <div id="rs-facebook-settings-wrapper" class="rs-settings-wrapper">
                        <div style="width:50%;display:block;float:left;">
                            <span class="rb-new-label">Slides (max 25)</span>
                            <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'facebook-count', ''); ?>" name="facebook-count" title="Display this number of posts">
                            <p>
                                <span class="rb-new-label">Cache (sec)</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'facebook-transient', '1200'); ?>" name="facebook-transient"  title="Cache the stream for x seconds">
                            </p>
                            <p>
<?php $facebook_page_url = UniteFunctionsRb::getVal($arrFieldsParams, 'facebook-page-url', ''); ?>
                                <span class="rb-new-label">Facebook Page</span>
                                <input type="text" value="<?php echo $facebook_page_url; ?>" name="facebook-page-url" id="facebook-page-url" title="Put in the URL/ID of the Facebook page">
                            </p>
                        </div>
                        <div style="width:50%;display:block;float:left;">

                            <input type="hidden" name="facebook-type-source" value="timeline">

                            <span class="rb-new-label">App ID</span>
                            <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'facebook-app-id', '') ?>" name="facebook-app-id" class="eg-tooltip-wrap" title="Put in the Facebook App ID">

                            <p>
                                <span class="rb-new-label">App Secret</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'facebook-app-secret', '') ?>" name="facebook-app-secret" class="eg-tooltip-wrap" title="Put in the Facebook App secret">
                            </p>
                        </div>
                    </div>

                    <div id="rs-twitter-settings-wrapper" class="rs-settings-wrapper">
                        <div style="width:50%;display:block;float:left;">
                            <span class="rb-new-label">Slides (max 500)</span>
                            <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'twitter-count', ''); ?>" name="twitter-count" title="Display this number of tweets">
                            <p>
                                <span class="rb-new-label">Cache (sec)</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'twitter-transient', '1200'); ?>" name="twitter-transient"  title="Cache the stream for x seconds">
                            </p>
                            <p>
                                <span class="rb-new-label">Twitter Name @</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'twitter-user-id', ''); ?>" name="twitter-user-id"  title="Put in the Twitter Account to stream from">
                            </p>
                            <p>
                                <span class="rb-new-label">Text Tweets</span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="twitter-image-only" name="twitter-image-only" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'twitter-image-only', 'off'), 'on'); ?>>
                            </p>
                            <p>
                                <span class="rb-new-label">Retweets</span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="twitter-include-retweets" name="twitter-include-retweets" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'twitter-include-retweets', 'off'), 'on'); ?> >
                            </p>
                            <p>
                                <span class="rb-new-label">Replies</span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="twitter-exclude-replies" name="twitter-exclude-replies" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'twitter-exclude-replies', 'off'), 'on'); ?> >
                            </p>
                        </div>
                        <div style="width:50%;display:block;float:left;">
                            <span class="rb-new-label">Consumer Key</span>
                            <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'twitter-consumer-key', ''); ?>" name="twitter-consumer-key" title="Put in your Twitter Consumer Key">
                            <p>
                                <span class="rb-new-label">Consumer Secret</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'twitter-consumer-secret', ''); ?>" name="twitter-consumer-secret" title="Put in your Twitter Consumer Secret">
                            </p>
                            <p>
                                <span class="rb-new-label">Access Token</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'twitter-access-token', ''); ?>" name="twitter-access-token" title="Put in your Twitter Access Token">
                            </p>
                            <p>
                                <span class="rb-new-label">Access Secret</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'twitter-access-secret', ''); ?>" name="twitter-access-secret" title="Put in your Twitter Access Secret">
                            </p>
                            <span class="description">Please <a target="_blank" href="https://dev.twitter.com/apps">register</a> your application with Twitter to get the values</span>
                        </div>
                    </div>

                    <div id="rs-youtube-settings-wrapper" class="rs-settings-wrapper">
                        <div class="rs-notice-wrap stream-notice">The “YouTube Stream‿ content source is used to display a full stream of videos from a channel/playlist.<br> If you want to display a single youtube video, please select the content source “Default Slider‿ and add a video layer in the slide editor.</div>
                        <div style="width:50%;display:block;float:left;">
                            <span class="rb-new-label">Slides (max 50)</span>
                            <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'youtube-count', ''); ?>" name="youtube-count" title="Display this number of videos">
                            <p>
                                <span class="rb-new-label">Youtube API Key</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'youtube-api', ''); ?>" name="youtube-api" title="Put in your YouTube API Key">
                            </p>

                            <span class="description">Find information about the YouTube API key <a target="_blank" href="https://developers.google.com/youtube/v3/getting-started#before-you-start">here</a></span>
                        </div>
                        <div style="width:50%;display:block;float:left;">
                            <span class="rb-new-label">Channel ID</span>
                            <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'youtube-channel-id', ''); ?>" name="youtube-channel-id" title="Put in the ID of the YouTube channel">
                            <p>
                                <span class="rb-new-label">Source</span>
                                <select name="youtube-type-source">
                                    <option value="channel" <?php Rbthemeslider::selected(UniteFunctionsRb::getVal($arrFieldsParams, 'youtube-type-source', 'channel'), 'channel') ?> title="Display the channel´s videos"> Channel </option>
                                    <option value="playlist" <?php Rbthemeslider::selected(UniteFunctionsRb::getVal($arrFieldsParams, 'youtube-type-source', 'channel'), 'playlist') ?> title="Display a playlist"> Playlist </option>
                                </select>
                            </p>
                            <div id="youtube-playlist-wrap">
                                <p>
                                    <span class="rb-new-label">Select Playlist</span>
                                    <input type="hidden" name="youtube-playlist" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'youtube-playlist', '') ?>">
                                    <select name="youtube-playlist-select">
                                    </select>
                                </p>
                            </div>
                            <span class="description">See how to find the Youtube channel ID <a target="_blank" href="https://support.google.com/youtube/answer/3250431?hl=en">here</a></span>
                        </div>
                    </div>

                    <div id="rs-vimeo-settings-wrapper" class="rs-settings-wrapper">
                        <div class="rs-notice-wrap stream-notice">The “Vimeo Stream‿ content source is used to display a full stream of videos from a user/album/group/channel.<br> If you want to display a single vimeo video, please select the content source “Default Slider‿ and add a video layer in the slide editor.</div>
                        <div style="width:50%;display:block;float:left;">
                            <span class="rb-new-label">Slides (max 60)</span>
                            <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'vimeo-count', ''); ?>" name="vimeo-count" title="Display this number of videos">
                            <p>
                                <span class="rb-new-label">Source</span>
                                <select name="vimeo-type-source">
                                    <option name="vimeo-type-source" <?php Rbthemeslider::selected(UniteFunctionsRb::getVal($arrFieldsParams, 'vimeo-type-source', 'user'), 'user') ?> value="user" title="Display the user's videos" > User </option>
                                    <option name="vimeo-type-source" <?php Rbthemeslider::selected(UniteFunctionsRb::getVal($arrFieldsParams, 'vimeo-type-source', 'user'), 'album') ?> value="album" title="Display an album"> Album </option>
                                    <option name="vimeo-type-source" value="group" <?php Rbthemeslider::selected(UniteFunctionsRb::getVal($arrFieldsParams, 'vimeo-type-source', 'user'), 'group') ?> title="Display a group's videos"> Group </option>
                                    <option name="vimeo-type-source" value="channel" <?php Rbthemeslider::selected(UniteFunctionsRb::getVal($arrFieldsParams, 'vimeo-type-source', 'user'), 'channel') ?> title="Display a channel's videos"> Channel </option>
                                </select>
                            </p>
                        </div>
                        <div style="width:50%;display:block;float:left;">
                            <div id="vimeo-user-wrap" class="source-vimeo">
                                <span class="rb-new-label">User</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'vimeo-username', ''); ?>" name="vimeo-username" title="Either the shortcut URL or ID of the user">
                            </div>
                            <div id="vimeo-group-wrap" class="source-vimeo">
                                <span class="rb-new-label">Group</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'vimeo-groupname', ''); ?>" name="vimeo-groupname" title="Either the shortcut URL or ID of the group">
                            </div>
                            <div id="vimeo-album-wrap" class="source-vimeo">
                                <span class="rb-new-label">Album ID</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'vimeo-albumid', ''); ?>" name="vimeo-albumid" title="The ID of the album">
                            </div>
                            <div id="vimeo-channel-wrap" class="source-vimeo">
                                <span class="rb-new-label">Channel</span>
                                <input type="text" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'vimeo-channelname', ''); ?>" name="vimeo-channelname" title="Either the shortcut URL of the channel">
                            </div>
                        </div>
                    </div>

                    <div id="rs-post-settings-wrapper" class="rs-settings-wrapper">
                        <div style="width:50%;display:block;float:left;">

                            <div class="rs-specific-posts-wrap">
                                <span class="rb-new-label">Specific Product(s) id:</span>
                                <input type="text" class='regular-text' placeholder="coma separated | ex: 23,24,25" id="posts_list" name="posts_list" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'posts_list', ''); ?>" style="width:255px" />
                            </div>
                            <div class="rs-post-types-wrapper">
<?php
$sel_post_cagetory = UniteFunctionsRb::getVal($arrFieldsParams, 'post_category', '');
?>
                                <span class="tp-clearfix"></span>
                                <span class="rb-new-label">Product Categories:</span>
                                <input type="text" class='regular-text' placeholder="coma separated | ex: 23,24,25" id="post_category" name="post_category" value="<?php echo $sel_post_cagetory; ?>" style="width:255px" />

                            </div>
                        </div>
                        <?php
                        //events integration
                        if (UniteEmRb::isEventsExists()) {
                            $arrEventsFilter = UniteEmRb::getArrFilterTypes();

                            if (!empty($arrEventsFilter)) {
                                echo '<p>';
                                $events_filter = UniteFunctionsRb::getVal($arrFieldsParams, 'events_filter', '');
                                echo '<span class="rb-new-label">' . __('Filter Events By:', RSLIDER_TEXTDOMAIN) . '</span>';
                                echo '<select id="events_filter" name="events_filter">';
                                foreach ($arrEventsFilter as $event_handle => $event_name) {
                                    $sel = ($event_handle == $events_filter) ? ' selected="selected"' : '';
                                    echo '<option value="' . $event_handle . '"' . $sel . '>' . $event_name . '</option>';
                                }
                                echo '</select>';
                                echo '</p>';
                            }
                        }

                        ?>

                        <div style="width:50%;display:block;float:left;" class="rs-hide-for-wc">
                            <div id="post_sortby_row" valign="top">
                                <span class="rb-new-label"><?php echo $modules->l('Product Image Type'); ?></span>
                                <?php $GetArrImageSize = UniteFunctionsPSRb::getArrImageSize(); ?>
<?php $gaImg_val = UniteFunctionsRb::getVal($arrFieldsParams, 'prd_img_size', ''); ?>
                                <select name="prd_img_size" id="prd_img_size">
<?php foreach ($GetArrImageSize as $rb_p_img_id => $rb_p_img_label): ?>
                                        <option value="<?php echo $rb_p_img_id; ?>" <?php Rbthemeslider::selected($gaImg_val, $rb_p_img_id); ?> ><?php echo ucwords(str_replace('_', ' ', $rb_p_img_label)); ?></option>
<?php endforeach; ?>

                                </select>
<?php $post_sortby = UniteFunctionsRb::getVal($arrFieldsParams, 'post_sortby', 'ID'); ?>
                                <span class="rb-new-label">Sort Posts By:</span>
                                <select id="post_sortby" name="post_sortby">
                                    <option value="ID"  <?php Rbthemeslider::selected($post_sortby, 'id_product'); ?>>Product ID</option>
                                    <option value="date" <?php Rbthemeslider::selected($post_sortby, 'date_add'); ?>>Date Add</option>
                                    <option value="date" <?php Rbthemeslider::selected($post_sortby, 'date_upd'); ?>>Date Update</option>
                                    <option value="title" <?php Rbthemeslider::selected($post_sortby, 'name'); ?>>Name</option>
                                    <option value="name" <?php Rbthemeslider::selected($post_sortby, 'manufacturer'); ?>>Manufacturer</option>
                                    <option value="author" <?php Rbthemeslider::selected($post_sortby, 'price'); ?>>Price</option>
                                    <option value="modified" <?php Rbthemeslider::selected($post_sortby, 'position'); ?>>Position</option>                                    
                                    <option value="rand" <?php Rbthemeslider::selected($post_sortby, 'rand'); ?>>Random</option>                                    
                                    <?php
                                    if (UniteEmRb::isEventsExists()) {
                                        $arrEMSortBy = UniteEmRb::getArrSortBy();
                                        if (!empty($arrEMSortBy)) {
                                            foreach ($arrEMSortBy as $event_handle => $event_name) {
                                                $sel = ($event_handle == $post_sortby) ? ' selected="selected"' : '';
                                                echo '<option value="' . $event_handle . '"' . $sel . '>' . $event_name . '</option>';
                                            }
                                        }
                                    }

                                    ?>
                                </select>
                                <span class="tp-clearfix"></span>

<?php $posts_sort_direction = UniteFunctionsRb::getVal($arrFieldsParams, 'posts_sort_direction', 'DESC'); ?>
                                <span class="rb-new-label">Sort Direction:</span>
                                <span>
                                    <input type="radio" id="posts_sort_direction_1" value="DESC" name="posts_sort_direction" <?php checked($posts_sort_direction, 'DESC'); ?> />
                                    <label for="posts_sort_direction_1" style="cursor:pointer;">Descending</label>
                                    <input type="radio" style="margin-left:20px;" id="posts_sort_direction_2" value="ASC" name="posts_sort_direction" <?php checked($posts_sort_direction, 'ASC'); ?> />
                                    <label for="posts_sort_direction_2" style="cursor:pointer;">Ascending</label>
                                </span>
                                <span class="tp-clearfix"></span>

                                <span class="rb-new-label">Max Posts Per Slider:</span>
                                <input type="text" class='small-text' id="max_slider_posts" name="max_slider_posts" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'max_slider_posts', '30'); ?>" />
                                <span class="tp-clearfix"></span>

                                <span class="rb-new-label">Limit The Excerpt To:</span>
                                <input type="text" class='small-text' id="excerpt_limit" name="excerpt_limit" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'excerpt_limit', '55'); ?>" />
                            </div>
                        </div>
                        <span class="tp-clearfix"></span>
                    </div>

                </div>


                <div class="setting_box" style="background:#fff">
                    <h3><span class="setting-step-number">2</span><span>Slider Title &amp; Hook</span></h3>
                    <div class="inside">
                        <div class="slidertitlebox">

                            <span class="one-third-container">
                                <input placeholder='Enter your Slider Name here' type="text" class='regular-text' id="title" name="title" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'title', ''); ?>"/>
                                <i class="input-edit-icon"></i>
                                <span class="description">The title of the slider, example: Slider 1</span>
                            </span>

                            <span class="one-third-container">
                                <input placeholder='Enter your Slider Alias here' type="text" class='regular-text' id="alias" name="alias" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'alias', ''); ?>"/>
                                <i class="input-edit-icon"></i>
                                <span class="description">The alias for embedding your slider, example: slider1</span>
                            </span>

                            <?php

                            $gethooks = array();
                            $customhooks = $modules->getHook();

                            if (@Rbthemeslider::getIsset($customhooks) && !empty($customhooks)) {
                                foreach ($customhooks as $values) {
                                    foreach ($values as $valu) {
                                        $gethooks[$valu] = $valu;
                                    }
                                }
                            }

                            ?>
                            <span class="one-third-container">
                                <select class='' id="displayhook" name="displayhook">
                                    <?php
                                    if (count($gethooks)) {
                                        foreach ($gethooks as $hook_val => $hook_name) {
                                            $selected = ($hook_val == UniteFunctionsRb::getVal($arrFieldsParams, 'displayhook', '')) ? 'selected="selected"' : '';

                                            ?>
                                            <option value="<?php echo $hook_val;

                                            ?>" <?php echo $selected;

                                            ?>><?php echo $hook_name;

                                            ?></option>
                            <?php
                        }
                    }

                    ?>
                                </select>
                                <!-- <i class="input-shortcode-icon"></i> -->
                                <span class="description">Place select a hook where you want to show the slider</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- THE SLIDE TYPE CHOOSER -->
                <div class="setting_box">
<?php
$slider_type = UniteFunctionsRb::getVal($arrFieldsParams, 'slider-type', 'standard');

?>
                    <h3><span class="setting-step-number">3</span><span>Select a Slider Type</span></h3>
                    <div class="rs-slidetypeselector">

                        <div data-mode="standardpreset" class="rs-slidertype<?php echo ($slider_type == 'standard') ? ' selected' : ''; ?>">
                            <span class="rs-preset-image standardslider"></span>
                            <span class="rs-preset-label">Standard Slider</span>
                            <input style="display: none;" type="radio" name="slider-type" value="standard" <?php checked($slider_type, 'standard'); ?> />
                        </div>
                        <div data-mode="heropreset" class="rs-slidertype<?php echo ($slider_type == 'hero') ? ' selected' : ''; ?>">
                            <span class="rs-preset-image heroscene"></span>
                            <span class="rs-preset-label">Hero Scene</span>
                            <input style="display: none;" type="radio" name="slider-type" value="hero" <?php checked($slider_type, 'hero'); ?> />
                        </div>
                        <div data-mode="carouselpreset" class="rs-slidertype<?php echo ($slider_type == 'carousel') ? ' selected' : ''; ?>">
                            <span class="rs-preset-image carouselslider"></span>
                            <span class="rs-preset-label">Carousel Slider</span>
                            <input style="display: none;" type="radio" name="slider-type" value="carousel" <?php checked($slider_type, 'carousel'); ?> />
                        </div>
                    </div>
                    <span class="preset-splitter readytoopen">Load a Preset from this Slider Type<i class="eg-icon-down-open"></i></span>
                    <div id="preset-selector-wrapper" class="preset-selector-wrapper" style="display:none">
                        <div id="preselect-horiz-wrapper" class="preselect-horiz-wrapper">
<?php
$presets = RbOperations::getPresetSettings();

echo '<span class="rs-preset-selector rs-do-nothing standardpreset heropreset carouselpreset" id="rs-add-new-settings-preset">';
echo '<span class="rs-preset-image" style="background-image: url(' . _MODULE_DIR_ . 'rbthemeslider/views/img/images/mainoptions/add_preset.png);"></span>';
echo '	<span class="rs-preset-label">Save Current Settings as Preset</span>';
echo '</span>';

echo '<script type="text/javascript">';

$pjs = UniteFunctionsRb::jsonEncodeForClientSide($presets);
echo 'var rbslider_presets = jQuery.parseJSON(' . $pjs . ');';
echo '</script>';

?>
                            <span class="tp-clearfix"></span>
                        </div>
                    </div>
                    <script type="text/javascript">
                        jQuery("document").ready(function() {

                            jQuery('.preset-splitter').click(function() {
                                jQuery('#preset-selector-wrapper').slideDown(200);
                                jQuery(this).removeClass('readytoopen');
                            })
                            var preset_template_container = ps.template("rs-preset-container");

                            function rs_reset_preset_html() {

                                jQuery('.rs-preset-entry').remove();

                                for (var key in rbslider_presets) {

                                    if (typeof(rbslider_presets[key]['settings']['preset']) === 'undefined') {
                                        rbslider_presets[key]['settings']['preset'] = 'standardpreset';
                                    }

                                    var data = {};
                                    data['key'] = key;
                                    data['name'] = rbslider_presets[key]['settings']['name'];
                                    data['type'] = rbslider_presets[key]['settings']['preset'];
                                    data['img'] = (typeof(rbslider_presets[key]['settings']['image']) !== 'undefined') ? rbslider_presets[key]['settings']['image'] : '';
                                    data['class'] = (typeof(rbslider_presets[key]['settings']['class']) !== 'undefined') ? rbslider_presets[key]['settings']['class'] : '';
                                    data['custom'] = (typeof(rbslider_presets[key]['settings']['custom']) !== 'undefined' && rbslider_presets[key]['settings']['custom'] == true) ? true : false;


                                    var content = preset_template_container(data);


                                    jQuery('#rs-add-new-settings-preset').before(content);
                                }

                                jQuery('.rs-slidertype.selected').click(); //show only for current active type
                            }
                            rs_reset_preset_html();

                            function updateSliderPresets() {
                                var bt = jQuery('.rs-slidertype.selected'),
                                        sw = jQuery('#preselect-horiz-wrapper'),
                                        sps = jQuery('#preset-selector-wrapper'),
                                        mode = bt.data('mode'),
                                        prewi = (sps.width() - 2) / 4;
                                if (prewi < 200)
                                    prewi = ((sps.width() - 1) / 3);

                                preitems = jQuery('.rs-preset-selector.' + mode);

                                jQuery('.rs-preset-selector').removeClass("selected").hide().css({width: prewi + "px"});
                                preitems.show();

                                //if (preitems.length<7) {
                                sw.css({position: "relative", height: "auto", width: "100%"});
                                sps.css({position: "relative", height: "auto"});

                                switch (mode) {
                                    case "standardpreset":
                                        jQuery('.dontshowonhero').show();
                                        jQuery('.dontshowonstandard').hide();
                                        break;
                                    case "carouselpreset":
                                        jQuery('.dontshowonhero').show();
                                        jQuery('.dontshowonstandard').show();
                                        break;
                                    case "heropreset":
                                        jQuery('.dontshowonhero').hide();
                                        break;
                                }

                            }

                            jQuery('body').on("click", '.rs-slidertype', function() {
                                var bt = jQuery(this);
                                jQuery('.rs-slidertype').removeClass("selected");
                                bt.addClass("selected").find('input[name="slider-type"]').attr('checked', 'checked');
                                updateSliderPresets();
                            });

                            // jQuery('.preset-selector-wrapper').perfectScrollbar({});
                            updateSliderPresets();
                            jQuery(window).resize(updateSliderPresets);


                            jQuery('body').on('mouseover', '.rs-preset-selector', function() {
                                jQuery(this).find('.rb-remove-preset').show();
                                jQuery(this).find('.rb-update-preset').show();
                            });
                            jQuery('body').on('mouseleave', '.rs-preset-selector', function() {
                                jQuery(this).find('.rb-remove-preset').hide();
                                jQuery(this).find('.rb-update-preset').hide();
                            });

                            var googlef_template_container = ps.template("rs-preset-googlefont");

                            jQuery('body').on('click', '.rs-preset-selector', function() {
                                if (typeof(jQuery(this).attr('id')) == 'undefined' || jQuery(this).hasClass('rs-do-nothing'))
                                    return false;
                                var preset_id = jQuery(this).attr('id').replace('rs-preset-', '');

                                showWaitAMinute({fadeIn: 300, text: rb_lang.preset_loaded});

                                if (typeof(rbslider_presets[preset_id]) !== 'undefined') {

                                    for (var key in rbslider_presets[preset_id]['values']) {
                                        var entry = jQuery('[name="' + key + '"]');

                                        if (key == 'google_font') {
                                            jQuery('#rs-google-fonts').html('');

                                            for (var gfk in rbslider_presets[preset_id]['values'][key]) {
                                                jQuery('#rs-google-fonts').append(googlef_template_container({'value': rbslider_presets[preset_id]['values'][key][gfk]}));
                                            }

                                        }

                                        if (entry.length == 0)
                                            continue;

                                        switch (entry.prop('tagName').toLowerCase()) {
                                            case 'input':
                                                switch (entry.attr('type')) {
                                                    case 'radio':
                                                        jQuery('[name="' + key + '"][value="' + rbslider_presets[preset_id]['values'][key] + '"]').click();
                                                        break;
                                                    case 'checkbox':
                                                        if (rbslider_presets[preset_id]['values'][key] == 'on')
                                                            entry.attr('checked', true);
                                                        else
                                                            entry.attr('checked', false);

                                                        UniteSettingsRb.onoffStatus(entry);
                                                        break;
                                                    default:
                                                        entry.val(rbslider_presets[preset_id]['values'][key]);
                                                        break;
                                                }
                                                break;
                                            case 'select':
                                                jQuery('[name="' + key + '"] option[value="' + rbslider_presets[preset_id]['values'][key] + '"]').attr('selected', true);
                                                break;
                                            default:
                                                switch (key) {
                                                    case 'custom_css':
                                                        if (typeof rb_cm_custom_css !== 'undefined')
                                                            rb_cm_custom_css.setValue(UniteAdminRb.stripslashes(rbslider_presets[preset_id]['values'][key]));
                                                        break;
                                                    case 'custom_javascript':
                                                        if (typeof rb_cm_custom_js !== 'undefined')
                                                            rb_cm_custom_js.setValue(UniteAdminRb.stripslashes(rbslider_presets[preset_id]['values'][key]));
                                                        break;
                                                    default:
                                                        jQuery('[name="' + key + '"]').val(rbslider_presets[preset_id]['values'][key]);
                                                        break;
                                                }
                                                break;
                                        }

                                        entry.change(); //trigger change call for elements to hide/show dependencies
                                    }

                                    if (typeof rb_cm_custom_css !== 'undefined')
                                        rb_cm_custom_css.refresh();
                                    if (typeof rb_cm_custom_js !== 'undefined')
                                        rb_cm_custom_js.refresh();
                                }

                                setTimeout('showWaitAMinute({fadeOut:300})', 400);
                            });


                            function get_preset_params() {
                                var params = UniteSettingsRb.getSettingsObject('form_slider_params');
                                delete params.action;
                                delete params['0'];

                                var ecsn = (jQuery('input[name="enable_custom_size_notebook"]').is(':checked')) ? 'on' : 'off';
                                var ecst = (jQuery('input[name="enable_custom_size_tablet"]').is(':checked')) ? 'on' : 'off';
                                var ecsi = (jQuery('input[name="enable_custom_size_iphone"]').is(':checked')) ? 'on' : 'off';
                                var mof = (jQuery('input[name="main_overflow_hidden"]').is(':checked')) ? 'on' : 'off';
                                var ah = (jQuery('input[name="auto_height"]').is(':checked')) ? 'on' : 'off';

                                var params2 = {
                                    slider_type: jQuery('input[name="slider_type"]:checked').val(),
                                    width: jQuery('input[name="width"]').val(),
                                    width_notebook: jQuery('input[name="width_notebook"]').val(),
                                    width_tablet: jQuery('input[name="width_tablet"]').val(),
                                    width_mobile: jQuery('input[name="width_mobile"]').val(),
                                    height: jQuery('input[name="height"]').val(),
                                    height_notebook: jQuery('input[name="height_notebook"]').val(),
                                    height_tablet: jQuery('input[name="height_tablet"]').val(),
                                    height_mobile: jQuery('input[name="height_mobile"]').val(),
                                    enable_custom_size_notebook: ecsn,
                                    enable_custom_size_tablet: ecst,
                                    enable_custom_size_iphone: ecsi,
                                    main_overflow_hidden: mof,
                                    auto_height: ah,
                                    min_height: jQuery('input[name="min_height"]').val(),
                                };

                                if (typeof rb_cm_custom_js !== 'undefined')
                                    params2.custom_javascript = rb_cm_custom_js.getValue();

                                if (typeof rb_cm_custom_css !== 'undefined')
                                    params2.custom_css = rb_cm_custom_css.getValue();

                                jQuery.extend(params, params2);


                                return params;

                            }


                            jQuery('body').on('click', '.rb-update-preset', function() {
                                if (confirm(rb_lang.update_preset)) {

                                    var pr_id = jQuery(this).closest('.rs-preset-entry').attr('id').replace('rs-preset-', '');

                                    if (typeof(rbslider_presets[pr_id]) == 'undefined')
                                        alert(rb_lang.preset_not_found);

                                    var params = get_preset_params();

                                    var update_preset = {name: rbslider_presets[pr_id]['settings']['name'], values: params};

                                    UniteAdminRb.ajaxRequest('update_preset', update_preset, function(response) {
                                        if (response.success == true) {
                                            //refresh presets
                                            rbslider_presets = response.data;

                                            rs_reset_preset_html();
                                        }
                                    });
                                }

                                return false;
                            });


                            jQuery('#rs-add-new-settings-preset').click(function() {
                                jQuery('input[name="rs-preset-name"]').val('');
                                jQuery('input[name="rs-preset-image-id"]').val('');
                                jQuery('#rs-preset-img-wrapper').css('background-image', '');

                                jQuery('#dialog-rs-add-new-setting-presets').dialog({
                                    modal: true,
                                    resizable: false,
                                    minWidth: 400,
                                    minHeight: 300,
                                    closeOnEscape: true,
                                    buttons: {
                                        'Save Settings': function() {
                                            var preset_name = UniteAdminRb.sanitize_input(jQuery('input[name="rs-preset-name"]').val());
                                            var preset_img = jQuery('input[name="rs-preset-image-id"]').val();
                                            jQuery('input[name="rs-preset-name"]').val(preset_name);

                                            if (preset_name == '')
                                                return false;

                                            for (var key in rbslider_presets) {
                                                if (rbslider_presets[key]['settings']['name'] == preset_name) {
                                                    alert(rb_lang.preset_name_already$modules->lxists);
                                                    return false;
                                                }
                                            }
                                            var c_type = jQuery('.rs-slidertype.selected').data('mode');

                                            var params = get_preset_params();

                                            var new_preset = {
                                                settings: {'class': '', image: preset_img, name: preset_name, preset: c_type},
                                                values: params
                                            };


                                            //add new preset to the list
                                            UniteAdminRb.ajaxRequest('add_new_preset', new_preset, function(response) {
                                                if (response.success == true) {
                                                    //refresh presets
                                                    rbslider_presets = response.data;


                                                    rs_reset_preset_html();
                                                }

                                                jQuery('#dialog-rs-add-new-setting-presets').dialog('close');
                                            });

                                        }
                                    }
                                });


                            });

                            jQuery('input[name="rs-button-select-img"]').click(function() {
                                jQuery('#rs-preset-img-wrapper').css('background-image', '');
                                jQuery('input[name="rs-preset-image-id"]').val('');

                                UniteAdminRb.openAddImageDialog(rb_lang.select_image, function(urlImage, imageID, width, height) {
                                    var data = {url_image: urlImage, image_id: imageID, img_width: width, img_height: height};

                                    console.log(data);

                                    jQuery('input[name="rs-preset-image-id"]').val(data.image_id);

                                    jQuery('#rs-preset-img-wrapper').css('background-image', ' url(' + data.url_image + ')');

                                    var mw = 200;
                                    var mh = 200;

                                    jQuery('#rs-preset-img-wrapper').css('width', mw + 'px');
                                    jQuery('#rs-preset-img-wrapper').css('height', mh + 'px');
                                    jQuery('#rs-preset-img-wrapper').css('background-size', 'cover');
                                });

                            });

                            jQuery('body').on('click', '.rb-remove-preset', function() {
                                if (confirm(rb_lang.delete_preset)) {

                                    var pr_id = jQuery(this).closest('.rs-preset-entry').attr('id').replace('rs-preset-', '');

                                    if (typeof(rbslider_presets[pr_id]) == 'undefined')
                                        alert(rb_lang.preset_not_found);

                                    UniteAdminRb.ajaxRequest('remove_preset', {name: rbslider_presets[pr_id]['settings']['name']}, function(response) {
                                        rbslider_presets = response.data;

                                        rs_reset_preset_html();
                                    });
                                }

                                return false;
                            });

                        });
                    </script>
                </div>


                <?php
                $width_notebook = UniteFunctionsRb::getVal($arrFieldsParams, "width_notebook", $_width_notebook);
                $height_notebook = UniteFunctionsRb::getVal($arrFieldsParams, "height_notebook");
                if ((int) ($height_notebook) == 0) {
                    $height_notebook = 768;
                }

                $width = UniteFunctionsRb::getVal($arrFieldsParams, "width", $_width);
                $height = UniteFunctionsRb::getVal($arrFieldsParams, "height", 868);

                $width_tablet = UniteFunctionsRb::getVal($arrFieldsParams, "width_tablet", $_width_tablet);
                if ((int) ($width_tablet) == 0) {
                    $width_tablet = $_width_tablet;
                }

                $height_tablet = UniteFunctionsRb::getVal($arrFieldsParams, "height_tablet");
                if ((int) ($height_tablet) == 0) {
                    $height_tablet = 960;
                }
                $width_mobile = UniteFunctionsRb::getVal($arrFieldsParams, "width_mobile", $_width_mobile);

                if ((int) ($width_mobile) == 0) {
                    $width_mobile = $_width_mobile;
                }

                $height_mobile = UniteFunctionsRb::getVal($arrFieldsParams, "height_mobile");
                if ((int) ($height_mobile) == 0) {
                    $height_mobile = 720;
                }

                $advanced_sizes = UniteFunctionsRb::getVal($arrFieldsParams, "advanced-responsive-sizes", 'false');
                $advanced_sizes = UniteFunctionsRb::strToBool($advanced_sizes);

                $sliderType = UniteFunctionsRb::getVal($arrFieldsParams, "slider_type");

                ?>

                <div class="setting_box" id="rs-slider-layout-cont">
                    <h3><span class="setting-step-number">4</span><span>Slide Layout</span></h3>
                    <div class="inside" style="padding:0px">

<?php $slider_type = UniteFunctionsRb::getVal($arrFieldsParams, 'slider_type', 'fullwidth'); ?>
                        <div style="background:#eee">
                            <div class="rs-slidesize-selector" >
                                <div class="rs-slidersize">
                                    <span class="rs-size-image autosized"></span>
                                    <span class="rs-preset-label">Auto</span>
                                    <input type="radio" id="slider_type_1" value="auto" name="slider_type" <?php checked($slider_type, 'auto'); ?> />
                                </div>
                                <div class="rs-slidersize">
                                    <span class="rs-size-image fullwidthsized"></span>
                                    <span class="rs-preset-label">Full-Width</span>
                                    <input type="radio" id="slider_type_2" value="fullwidth" name="slider_type" <?php checked($slider_type, 'fullwidth'); ?> />
                                </div>
                                <div class="rs-slidersize selected">
                                    <span class="rs-size-image fullscreensized"></span>
                                    <span class="rs-preset-label">Full-Screen</span>
                                    <input type="radio" id="slider_type_3" style="margin-left:20px" value="fullscreen" name="slider_type" <?php checked($slider_type, 'fullscreen'); ?> />
                                </div>
                                <div style="clear:both;float:none"></div>
                            </div>
                        </div>

                 <?php require self::getPathTemplate("multi-shop"); ?>


                        <div id="layout-preshow">
                            <div class="rsp-desktop-view rsp-view-cell">
                                <div class="rsp-view-header">
                                    Desktop Large <span class="rsp-cell-dimension">Max</span>
                                </div>
                                <div class="rsp-present-area">
                                    <div class="rsp-device-imac">
                                        <div class="rsp-imac-topbar"></div>
                                        <div class="rsp-bg"></div>
                                        <div class="rsp-browser">
                                            <div class="rsp-slide-bg" data-width="140" data-height="70" data-fullwidth="188" data-faheight="70" data-fixwidth="140" data-fixheight="70" data-gmaxw = "140" data-lscale = "1">
                                                <div class="rsp-grid">
                                                    <div class="rsp-dotted-line-hr-left"></div>
                                                    <div class="rsp-dotted-line-hr-right"></div>
                                                    <div class="rsp-dotted-line-vr-top"></div>
                                                    <div class="rsp-dotted-line-vr-bottom"></div>
                                                    <div class="rsp-layer">Layer</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rsp-device-imac-bg"></div>
                                </div>
                                <div class="slide-size-wrapper">
                                    <span class="rs-preset-label">Layer Grid Size</span>
                                    <span class="relpos"><input id="width" name="width" type="text" style="width:120px" class="textbox-small" value="<?php echo $width; ?>"><span class="pxfill">px</span></span>
                                    <span class="rs-preset-label label-multiple">x</span>
                                    <span class="relpos"><input id="height" name="height" type="text" style="width:120px" class="textbox-small" value="<?php echo $height; ?>"><span class="pxfill">px</span></span>
                                    <span class="tp-clearfix" style="margin-bottom:15px"></span>
                                    <span class="description">Specify a layer grid size above</span>
                                    <span class="description" style="padding:20px 20px 0px; box-sizing:border-box;-moz-box-sizing:border-box;">Slider is always Linear Responsive till next Defined Grid size has been hit.</span>
                                </div>
                            </div>
                            <div class="rsp-macbook-view rsp-view-cell">
                                <div class="rsp-view-header">
                                    Notebook <span class="rsp-cell-dimension"><?php echo $_width_notebook; ?>px</span>
                                </div>
                                <div class="rsp-present-area">
                                    <div class="rsp-device-macbook">
                                        <div class="rsp-macbook-topbar"></div>
                                        <div class="rsp-bg"></div>
                                        <div class="rsp-browser">
                                            <div class="rsp-slide-bg" data-width="140" data-height="60" data-fullwidth="160" data-faheight="60" data-fixwidth="140" data-fixheight="60" data-gmaxw = "140" data-lscale = "0.8">
                                                <div class="rsp-grid">
                                                    <div class="rsp-dotted-line-hr-left"></div>
                                                    <div class="rsp-dotted-line-hr-right"></div>
                                                    <div class="rsp-dotted-line-vr-top"></div>
                                                    <div class="rsp-dotted-line-vr-bottom"></div>
                                                    <div class="rsp-layer">Layer</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rsp-device-macbook-bg"></div>
                                </div>
                                <div class="slide-size-wrapper">
                                    <span class="rs-preset-label">Layer Grid Size</span>
                                    <span class="rs-width-height-wrapper">
                                        <span class="relpos"><input name="width_notebook" type="text" style="width:120px" class="textbox-small" value="<?php echo $width_notebook; ?>"><span class="pxfill">px</span></span>
                                        <span class="rs-preset-label label-multiple">x</span>
                                        <span class="relpos"><input name="height_notebook" type="text" style="width:120px" class="textbox-small" value="<?php echo $height_notebook; ?>"><span class="pxfill">px</span></span>
                                    </span>
                                    <span class="rs-width-height-alternative" style="display:none">
                                        <span class="rs-preset-label">Auto Sizes</span>
                                    </span>
                                    <span class="tp-clearfix" style="margin-bottom:15px"></span>
                                    <span class="rs-preset-label" style="display:inline-block; margin-right:15px;">Custom Grid Size</span>
                                    <span style="text-align:left">
                                        <input type="checkbox"  class="tp-moderncheckbox" id="enable_custom_size_notebook" name="enable_custom_size_notebook" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'enable_custom_size_notebook', 'off'), "on"); ?>>
                                    </span>
                                    <span class="description" style="padding:0px 20px 0px; box-sizing:border-box;-moz-box-sizing:border-box;"><br>If not defined, the next bigger Layer Grid Size is the basic of Linear Responsive calculations.</span>
                                </div>
                            </div>
                            <div class="rsp-tablet-view rsp-view-cell">
                                <div class="rsp-view-header">
                                    Tablet <span class="rsp-cell-dimension"><?php echo $_width_tablet; ?>px</span>
                                </div>
                                <div class="rsp-present-area">
                                    <div class="rsp-device-ipad">
                                        <div class="rsp-bg"></div>
                                        <div class="rsp-browser">
                                            <div class="rsp-slide-bg" data-width="126" data-height="60" data-fullwidth="138" data-faheight="130" data-fixwidth="140" data-fixheight="70" data-gmaxw = "126" data-lscale = "0.7">
                                                <div class="rsp-grid">
                                                    <div class="rsp-dotted-line-hr-left"></div>
                                                    <div class="rsp-dotted-line-hr-right"></div>
                                                    <div class="rsp-dotted-line-vr-top"></div>
                                                    <div class="rsp-dotted-line-vr-bottom"></div>
                                                    <div class="rsp-layer">Layer</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rsp-device-ipad-bg"></div>
                                </div>
                                <div class="slide-size-wrapper">
                                    <span class="rs-preset-label">Layer Grid Size</span>
                                    <span class="rs-width-height-wrapper">
                                        <span class="relpos"><input name="width_tablet" type="text" style="width:120px" class="textbox-small" value="<?php echo $width_tablet; ?>"><span class="pxfill">px</span></span>
                                        <span class="rs-preset-label label-multiple">x</span>
                                        <span class="relpos"><input name="height_tablet" type="text" style="width:120px" class="textbox-small" value="<?php echo $height_tablet; ?>"><span class="pxfill">px</span></span>
                                    </span>
                                    <span class="rs-width-height-alternative" style="display:none">
                                        <span class="rs-preset-label">Auto Sizes</span>
                                    </span>
                                    <span class="tp-clearfix" style="margin-bottom:15px"></span>
                                    <span class="rs-preset-label" style="display:inline-block; margin-right:15px;">Custom Grid Size</span>
                                    <span style="text-align:left">
                                        <input type="checkbox"  class="tp-moderncheckbox" id="enable_custom_size_tablet" name="enable_custom_size_tablet" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'enable_custom_size_tablet', 'off'), "on"); ?>>
                                    </span>
                                    <span class="description" style="padding:0px 20px 0px; box-sizing:border-box;-moz-box-sizing:border-box;"><br>If not defined, the next bigger Layer Grid Size is the basic of Linear Responsive calculations.</span>
                                </div>

                            </div>
                            <div class="rsp-mobile-view rsp-view-cell">
                                <div class="rsp-view-header">
                                    Mobile <span class="rsp-cell-dimension"><?php echo $_width_mobile; ?>px</span>
                                </div>
                                <div class="rsp-present-area">
                                    <div class="rsp-device-iphone">
                                        <div class="rsp-bg"></div>
                                        <div class="rsp-browser">
                                            <div class="rsp-slide-bg" data-width="70" data-height="40" data-fullwidth="80" data-faheight="100" data-fixwidth="140" data-fixheight="70" data-gmaxw = "70" data-lscale = "0.4">
                                                <div class="rsp-grid">
                                                    <div class="rsp-dotted-line-hr-left"></div>
                                                    <div class="rsp-dotted-line-hr-right"></div>
                                                    <div class="rsp-dotted-line-vr-top"></div>
                                                    <div class="rsp-dotted-line-vr-bottom"></div>
                                                    <div class="rsp-layer">Layer</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rsp-device-iphone-bg"></div>
                                </div>
                                <div class="slide-size-wrapper">
                                    <span class="rs-preset-label">Layer Grid Size</span>
                                    <span class="rs-width-height-wrapper">
                                        <span class="relpos"><input name="width_mobile" type="text" style="width:120px" class="textbox-small" value="<?php echo $width_mobile; ?>"><span class="pxfill">px</span></span>
                                        <span class="rs-preset-label label-multiple">x</span>
                                        <span class="relpos"><input name="height_mobile" type="text" style="width:120px" class="textbox-small" value="<?php echo $height_mobile; ?>"><span class="pxfill">px</span></span>
                                    </span>
                                    <span class="rs-width-height-alternative" style="display:none">
                                        <span class="rs-preset-label">Auto Sizes</span>
                                    </span>
                                    <span class="tp-clearfix" style="margin-bottom:15px"></span>
                                    <span class="rs-preset-label" style="display:inline-block; margin-right:15px;">Custom Grid Size</span>
                                    <span style="text-align:left">
                                        <input type="checkbox"  class="tp-moderncheckbox" id="enable_custom_size_iphone" name="enable_custom_size_iphone" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'enable_custom_size_iphone', 'off'), "on"); ?>>
                                    </span>
                                    <span class="description" style="padding:0px 20px 0px; box-sizing:border-box;-moz-box-sizing:border-box;"><br>If not defined, the next bigger Layer Grid Size is the basic of Linear Responsive calculations.</span>
                                </div>
                            </div>
                            <div style="clear:both;float:none"></div>
                        </div>
                        <div class="buttonarea" id="removethisbuttonarea">
                            <a class="button-primary rbblue" id="show_advanced_navigation" original-title=""><i class="rbicon-cog"></i>Show Advanced Size Options</a>
                        </div>

                        <!-- VISUAL ADVANCED SIZING -->
                        <div class="inside" id="visual-sizing" style="display:none; padding:25px 20px;">
                            <div id="fullscreen-advanced-sizing">
                                <span class="one-half-container" style="vertical-align:top">

                                    <span class="rs-preset-label noopacity ">Minimal Height of Slider (Optional)</span>
                                    <span style="clear:both;float:none; height:25px;display:block"></span>

                                    <span style="text-align:left; display:none;">
                                        <span class="rs-preset-label noopacity " style="display:inline-block;margin-right:20px">FullScreen Align Force </span>
                                        <input type="checkbox"  class="tp-moderncheckbox withlabel" id="full_screen_align_force" name="full_screen_align_force" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'full_screen_align_force', 'off'), "on"); ?> >
                                        <span class="description">Layers align within the full slider instead of the layer grid.</span>
                                    </span>

                                    <span class="slidertitlebox limitedtablebox">
                                        <span class="one-half-container">
                                            <input placeholder="Min. Height" type="text" class="text-sidebar" id="fullscreen_min_height" name="fullscreen_min_height" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, "fullscreen_min_height", ""); ?>">
                                            <i class="input-edit-icon"></i>
                                            <span class="description">The minimum height of the Slider in FullScreen mode.</span>
                                        </span>
                                    </span>
                                    <span style="clear:both;float:none; height:25px;display:block"></span>

                                    <span style="text-align:left; padding:0px 20px;">
                                        <span class="rs-preset-label noopacity " style="display:inline-block;margin-right:20px">Disable Force FullWidth </span>
                                        <input type="checkbox"  class="tp-moderncheckbox withlabel" id="autowidth_force" name="autowidth_force" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'autowidth_force', 'off'), "on"); ?> >
                                        <span class="description" style="padding:0px 20px;">Disable the FullWidth Force function, and allow to float the Fullheight slider horizontal.</span>
                                    </span>

                                </span>

                                <span class="one-half-container" style="vertical-align:top">
                                    <span class="rs-preset-label noopacity ">Increase/Decrease Fullscreen Height (Optional)</span>
                                    <span style="clear:both;float:none; height:25px;display:block"></span>

                                    <span class="slidertitlebox limitedtablebox">
                                        <span class="one-full-container">
                                            <input placeholder="Containers" type="text" class="text-sidebar" id="fullscreen_offset_container" name="fullscreen_offset_container" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, "fullscreen_offset_container", ""); ?>">
                                            <i class="input-edit-icon"></i>
                                            <span class="description">Example: #header or .header, .footer, #somecontainer | Height of Slider will be decreased with the height of these Containers to fit perfect in the screen.</span>
                                        </span>
                                    </span>
                                    <span style="clear:both;float:none; height:25px;display:block"></span>
                                    <span class="slidertitlebox limitedtablebox">
                                        <span class="one-full-container">
                                            <input placeholder="PX or %" type="text" class="text-sidebar" id="fullscreen_offset_size" name="fullscreen_offset_size" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, "fullscreen_offset_size", ""); ?>">
                                            <i class="input-edit-icon"></i>
                                            <span class="description">Decrease/Increase height of Slider. Can be used with px and %. Positive/Negative values allowed. Example: 40px or 10%</span>
                                        </span>
                                    </span>
                                    <span style="clear:both;float:none; height:25px;display:block"></span>

                                </span>
                            </div>
                            <div id="normal-advanced-sizing">

                                <div class="slidertitlebox limitedtablebox" style="width:100%; max-width:100%">
                                    <span class="one-third-container" style="vertical-align:top">
                                        <span class="rs-preset-label noopacity" style="margin-top:12px;display:inline-block;margin-right:20px" >Overflow Hidden </span>
                                        <input type="checkbox"  class="tp-moderncheckbox" id="main_overflow_hidden" name="main_overflow_hidden" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'main_overflow_hidden', 'off'), "on"); ?> >
                                        <div style="clear:both;float:none; height:25px"></div>
                                        <span class="description">Adds overflow:hidden to the slider wrapping container which will hide / cut any overlapping elements. Mostly used in Carousel Sliders.</span>
                                    </span>

                                    <span class="one-third-container" style="vertical-align:top">
                                        <span class="rs-preset-label noopacity" style="margin-top:12px;display:inline-block;margin-right:20px" >Respect Aspect Ratio </span>
                                        <input type="checkbox"  class="tp-moderncheckbox" id="auto_height" name="auto_height" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'auto_height', 'off'), "on"); ?> >
                                        <div style="clear:both;float:none; height:25px"></div>
                                        <span class="description">It will keep aspect ratio and ignore max height of Layer Grid by upscaling. Layer Area will be vertical centered.</span>
                                    </span>

                                    <span class="one-third-container" style="vertical-align:top">
                                        <input placeholder="Min. Heigh (Optional)" type="text" class="text-sidebar" style="padding:11px 45px 11px 15px; line-height:26px" id="min_height" name="min_height" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, "min_height", ""); ?>">
                                        <i class="input-edit-icon"></i>
                                        <span class="description">The minimum height of the Slider in FullWidth or Auto mode.</span>
                                        <span class="rs-show-on-auto">
                                            <input placeholder="Max. Width (Optional)" type="text" class="text-sidebar" style="padding:11px 45px 11px 15px; margin-top: 20px; line-height:26px" id="max_width" name="max_width" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, "max_width", ""); ?>">
                                            <i class="input-edit-icon" style="top: 99px;"></i>
                                            <span class="description">The maximum width of the Slider in Auto mode.</span>
                                        </span>
                                    </span>
                                </div>
                            </div>

                        </div>		<!-- / VISUAL ADVANCED SIZING -->
                        <script>
                            jQuery("document").ready(function() {

                                jQuery('#show_advanced_navigation').click(function() {
                                    var a = jQuery('#visual-sizing');
                                    a.slideDown(200);
                                    jQuery('#removethisbuttonarea').remove();
                                })

                                jQuery('#show_advanced_navigation').click();

                                jQuery('input[name="slider_type"]').on("change", function() {
                                    var s_fs = jQuery('#slider_type_3').attr("checked") === "checked";
                                    if (s_fs) {
                                        jQuery('#normal-advanced-sizing').hide();
                                        jQuery('#fullscreen-advanced-sizing').show();
                                    } else {
                                        jQuery('#normal-advanced-sizing').show();
                                        jQuery('#fullscreen-advanced-sizing').hide();
                                    }

                                    var s_fs = jQuery('#slider_type_1').attr("checked") === "checked";
                                    if (s_fs) {
                                        jQuery('.rs-show-on-auto').show();
                                    } else {
                                        jQuery('.rs-show-on-auto').hide();
                                    }
                                });

                                jQuery('#slider_type_3').change();

                                function getPreviewRespSizes() {
                                    var s = new Object();

                                    s.n = jQuery('#enable_custom_size_notebook').attr('checked') === "checked";
                                    s.t = jQuery('#enable_custom_size_tablet').attr('checked') === "checked";
                                    s.m = jQuery('#enable_custom_size_iphone').attr('checked') === "checked";



                                    s.w_d = jQuery('input[name="width"]');
                                    s.w_n = s.n ? jQuery('input[name="width_notebook"]') : s.w_d;
                                    s.w_t = s.t ? jQuery('input[name="width_tablet"]') : s.n ? s.w_n : s.w_d;
                                    s.w_m = s.m ? jQuery('input[name="width_mobile"]') : s.t ? s.w_t : s.n ? s.w_n : s.w_d;

                                    s.h_d = jQuery('input[name="height"]');
                                    s.h_n = s.n ? jQuery('input[name="height_notebook"]') : s.h_d;
                                    s.h_t = s.t ? jQuery('input[name="height_tablet"]') : s.n ? s.h_n : s.h_d;
                                    s.h_m = s.m ? jQuery('input[name="height_mobile"]') : s.t ? s.h_t : s.n ? s.h_n : s.h_d;
                                    return s;
                                }

                                function drawResponsivePreviews(s) {
                                    var d_gw = (s.w_d.val() / 1400) * 150;
                                }

                                function setLayoutDesign(element, bw, bh, sw, sh, bm, ww, mm, fh, tt) {
                                    var o = new Object();
                                    o.mtp = 1;

                                    if (bw > sw) {
                                        bh = bh * sw / bw;
                                        o.mtp = sw / bw;
                                        bw = sw;
                                    }
                                    if (bh > sh) {
                                        bw = bw * sh / bh;
                                        o.mtp = sh / bh;
                                        bh = sh;
                                    }

                                    o.tt = tt;

                                    o.left = o.right = (2 + ((1 - (bw / sw)) * (sw / 10)) / 2) * bm,
                                            o.height = (bh / 10) * bm;
                                    o.mt = (((sh / 10) - o.height) / 20) * bm;

                                    if (fh === 1)
                                        o.gridtop = (((sh * bm / 10) - o.height) / 2);
                                    else {
                                        o.gridtop = 0;

                                        if (jQuery('#auto_height').attr("checked") === "checked")
                                            o.gridtop = Math.abs((o.height - (o.height * (sw / bw)))) / 2;
                                    }

                                    punchgs.TweenLite.to(element.find('.rsp-grid'), 0.3, {top: o.gridtop, height: o.height, left: mm, ease: punchgs.Power3.easeInOut});
                                    punchgs.TweenLite.to(element.find('.rsp-dotted-line-hr-left'), 0.3, {left: o.left, ease: punchgs.Power3.easeInOut});
                                    punchgs.TweenLite.to(element.find('.rsp-dotted-line-hr-right'), 0.3, {right: o.right, ease: punchgs.Power3.easeInOut});
                                    if (fh === 1) {
                                        o.height = "100%";
                                        o.mt = 0;
                                        o.tt = 0;
                                    }
                                    else

                                    if (jQuery('#auto_height').attr("checked") === "checked")
                                        o.height = o.height * (sw / bw);


                                    punchgs.TweenLite.to(element.find('.rsp-slide-bg'), 0.3, {top: o.tt, width: ww, left: 0 - mm, marginTop: o.mt, height: o.height, ease: punchgs.Power3.easeInOut});
                                    punchgs.TweenLite.to(element.find('.rsp-layer'), 0.3, {fontSize: o.mtp * 14, paddingTop: o.mtp * 3, paddingBottom: o.mtp * 3, paddingLeft: o.mtp * 5, paddingRight: o.mtp * 5, lineHeight: o.mtp * 14 + "px", ease: punchgs.Power3.easeInOut});

                                }
                                function readLayoutValues(goon) {
                                    var s = getPreviewRespSizes(),
                                            o = new Object();
                                    if (goon === 1)
                                        jQuery('.slide-size-wrapper .tp-moderncheckbox').change();

                                    if (jQuery('#slider_type_2').attr("checked") === "checked" || jQuery('#slider_type_3').attr("checked") === "checked") {
                                        o.dw = 187;
                                        o.dm = 23;
                                        o.nw = 160;
                                        o.nm = 10;
                                        o.tw = 140;
                                        o.tm = 7;
                                        o.mw = 80;
                                        o.mm = 5;
                                    } else {
                                        o.dw = 140;
                                        o.dm = 0;
                                        o.nw = 140;
                                        o.nm = 0;
                                        o.tw = 126;
                                        o.tm = 0;
                                        o.mw = 71;
                                        o.mm = 0;
                                    }

                                    if (jQuery('#slider_type_3').attr("checked") === "checked") {
                                        o.dh = 1;
                                        o.nh = 1;
                                        o.th = 1;
                                        o.mh = 1;
                                    } else {
                                        o.dh = 0;
                                        o.nh = 0;
                                        o.th = 0;
                                        o.mh = 0;
                                    }


                                    setLayoutDesign(jQuery('.rsp-device-imac'), s.w_d.val(), s.h_d.val(), 1400, 900, 1, o.dw, o.dm, o.dh, 0);
                                    setLayoutDesign(jQuery('.rsp-device-macbook'), s.w_n.val(), s.h_n.val(), 1200, 770, 1.166, o.nw, o.nm, o.nh, 0);
                                    setLayoutDesign(jQuery('.rsp-device-ipad'), s.w_t.val(), s.h_t.val(), 768, 1024, 1.78, o.tw, o.tm, o.th, 6);
                                    setLayoutDesign(jQuery('.rsp-device-iphone'), s.w_m.val(), s.h_m.val(), 640, 1136, 1.25, o.mw, o.mm, o.mh, 0);
                                }

                                jQuery('.slide-size-wrapper .tp-moderncheckbox').on("change", function() {
                                    var bt = jQuery(this),
                                            bp = bt.closest('.slide-size-wrapper'),
                                            cw = bp.find('.rs-width-height-alternative'),
                                            aw = bp.find('.rs-width-height-wrapper'),
                                            s = getPreviewRespSizes();


                                    if (bt.attr('checked') === "checked") {
                                        if (bt.data('oldstatus') === "unchecked" || bt.data('oldstatus') === undefined) {
                                            bp.removeClass("disabled").find('input[type="text"]').removeAttr("disabled");
                                            aw.show();
                                            cw.hide();
                                            bp.find('input[type="text"]').each(function() {
                                                var inp = jQuery(this);
                                                if (inp.data('oldval') !== undefined)
                                                    inp.val(inp.data('oldval'));
                                            });

                                        }
                                        bt.data('oldstatus', "checked");
                                    } else {
                                        if (bt.data('oldstatus') === "checked" || bt.data('oldstatus') === undefined) {
                                            bp.addClass("disabled").find('input[type="text"]').attr("disabled", "disabled");
                                            aw.hide();
                                            cw.show();
                                            bp.find('input[type="text"]').each(function() {
                                                var inp = jQuery(this);
                                                inp.data('oldval', inp.val());
                                            });
                                        }
                                        bt.data('oldstatus', "unchecked");
                                    }
                                    readLayoutValues(0);
                                });

                                jQuery('.slide-size-wrapper .tp-moderncheckbox').change();
                                readLayoutValues();

                                jQuery('input[name="slider_type"], #auto_height, #width, #height, input[name="width_notebook"], input[name="height_notebook"], input[name="width_tablet"], input[name="height_tablet"], input[name="width_mobile"], input[name="height_mobile"]').on("change", function() {
                                    readLayoutValues(1);
                                });
                            });
                        </script>
                        <!-- FALLBACK SETTINGS -->
                        <p style="display:none">
<?php $force_full_width = UniteFunctionsRb::getVal($arrFieldsParams, 'force_full_width', 'off'); ?>
                            <span class="rb-new-label"><?php $modules->l('Force Full Width:'); ?></span>
                            <input type="checkbox" class="tp-moderncheckbox " id="force_full_width" name="force_full_width" data-unchecked="off" <?php checked($force_full_width, 'on'); ?>>

                        </p>

                        <script>
                            jQuery(document).on("ready", function() {
                                function rsSelectorFun(firsttime) {
                                    jQuery('.rs-slidersize').removeClass("selected");
                                    jQuery('.rs-slidesize-selector input:checked').closest(".rs-slidersize").addClass("selected");

                                    // IF AUTO IS SELECTED AND FULLSCREEN IS FORCED (FALL BACK) THAN SELECT FULLWIDTH !
                                    if (firsttime === 1) {

                                        if (jQuery('#force_full_width').attr('checked') === "checked" && jQuery('#slider_type_1').attr('checked') === "checked") {
                                            jQuery('#slider_type_1').removeAttr('checked').change();
                                            jQuery('#slider_type_2').attr('checked', "checked").change();
                                        }

                                        if (jQuery('#force_full_width').attr('checked') !== "checked" && jQuery('#slider_type_2').attr('checked') === "checked") {
                                            jQuery('#slider_type_2').removeAttr('checked').change();
                                            jQuery('#slider_type_1').attr('checked', "checked").change();
                                        }
                                    }

                                    if (jQuery('#slider_type_2').attr('checked') === "checked" || jQuery('#slider_type_3').attr('checked') === "checked")
                                        jQuery('#force_full_width').attr('checked', "checked").change();
                                    else
                                        jQuery('#force_full_width').removeAttr('checked').change();
                                }

                                jQuery('.rs-slidesize-selector input').change(rsSelectorFun);
                                jQuery('#force_full_width').change();
                                rsSelectorFun(1);
                            })
                        </script>
                        <div style="float:none; clear:both"></div>

                    </div>


                </div>

                <div class="setting_box">
                    <h3><span class="setting-step-number">5</span><span>Customize, Build &amp; Implement</span></h3>
                    <div class="inside" style="padding:35px 20px">
                        <div class="slidertitlebox breakdownonmobile">
                            <span class="one-third-container" style="text-align:center">
                                <img style="width:100%; max-width:325px;" src="<?php echo _MODULE_DIR_; ?>/rbthemeslider/views/img/images/mainoptions/mini-customizeslide.jpg">
                                <span class="cbi-title">Advanced Settings</span>
                                <span class="description" style="text-align:center;min-height:60px;">Go for further customization using the advanced settings on the right of this configuration page.</span>
                                <div style="float:none; clear:both; height:40px;display:block;"></div>
                                <a class="button-primary rbblue" href="#form_slider_params"><i class="rbicon-cog"></i>Scroll to Options</a>
                            </span>

                            <span class="one-third-container" style="text-align:center">
                                <img style="width:100%;max-width:325px;" src="<?php echo _MODULE_DIR_; ?>/rbthemeslider/views/img/images/mainoptions/mini-editslide.jpg">
                                <span class="cbi-title">Start Building Slides</span>
                                <span class="description" style="text-align:center;min-height:60px;">Our drag and drop editor will make creating slide content an absolut breeze. This is where the magic happens!</span>
                                <div style="float:none; clear:both; height:20px;"></div>
<?php
if (@Rbthemeslider::getIsset($linksEditSlides)) {

    ?>
                                    <a class="button-primary rbblue" href="<?php echo $linksEditSlides;

    ?>"  id="link$modules->ldit_slides"><i class="rbicon-pencil-1"></i>Edit Slides </a>
    <?php
}

?>
                            </span>

                            <span class="one-third-container" style="text-align:center">
                                <img style="width:100%;max-width:325px;" src="<?php echo _MODULE_DIR_; ?>/rbthemeslider/views/img/images/mainoptions/mini-implement.jpg"><span class="description"></span>
                                <span class="cbi-title">Implement your Slider</span>
                                <span class="description" style="text-align:center;min-height:60px;">There are several ways to add your slider to your wordpress post / page / etc.</span>
                                <div style="float:none; clear:both; height:40px;"></div>

                                <span class="button-primary rbblue rs-embed-slider"><i class="eg-icon-plus-circled"></i>Embed Slider </span>

                            </span>
                        </div>
                    </div>
                    <div class="buttonarea" style="background-color:#eee; text-align:center">
                        <a style="width:125px" class='button-primary rbgreen' href='javascript:void(0)' id="button_save_slider" ><i class="rs-rp-accordion-icon rs-icon-save-light" style="display: inline-block;vertical-align: middle;width: 18px;height: 20px;margin-right:5px;background-repeat: no-repeat;"></i>Save Settings</a>
                        <span id="loader_update" class="loader_round" style="display:none;background-color:#27AE60 !important; color:#fff;padding: 4px 5px 5px 25px;margin-right: 5px;">updating... </span>
                        <span id="update_slider_success" class="success_message"></span>
                        <a style="width:125px" class='button-primary rbred' id="button_delete_slider" href='javascript:void(0)' ><i class="rbicon-trash"></i>Delete Slider</a>
                        <a style="width:125px" class='button-primary revyellow' id="button_close_slider$modules->ldit"  href='<?php echo self::getViewUrl("sliders") ?>' ><i class="eg-icon-th-large"></i>All Sliders</a>
                        <a style="width:125px" class="button-primary revgray" href="javascript:void(0)"  id="button_preview_slider" title="Preview Slider"><i class="rbicon-search-1"></i>Preview</a>
                    </div>
                </div>

            </form>

<?php
if ($is_edit) {
    $custom_css = '';
    $custom_js = '';
    if (!empty($sliderID)) {
        $custom_css = @Tools::stripslashes($arrFieldsParams['custom_css']);
        $custom_js = @Tools::stripslashes($arrFieldsParams['custom_javascript']);
    }

    ?>
                <div class="setting_box" id="css-javascript-customs" style="max-width:100%;position:relative;overflow:hidden">
                    <h3><span class="setting-step-number">6</span><span>Custom CSS / Javascript</span></h3>
                    <div class="inside" id="codemirror-wrapper">

                        <span class="cbi-title">Custom CSS</span>
                        <textarea name="custom_css" id="rs_custom_css"><?php echo $custom_css;

    ?></textarea>
                        <div class="divide20"></div>

                        <span class="cbi-title">Custom JavaScript</span>
                        <textarea name="custom_javascript" id="rs_custom_javascript"><?php echo $custom_js;

    ?></textarea>
                        <div class="divide20"></div>

                    </div>
                </div>

                <script type="text/javascript">
                    rb_cm_custom_css = null;
                    rb_cm_custom_js = null;

                    jQuery(document).ready(function() {
                        rb_cm_custom_css = CodeMirror.fromTextArea(document.getElementById("rs_custom_css"), {
                            onChange: function() {
                            },
                            lineNumbers: true,
                            mode: 'css',
                            lineWrapping: true
                        });

                        rb_cm_custom_js = CodeMirror.fromTextArea(document.getElementById("rs_custom_javascript"), {
                            onChange: function() {
                            },
                            lineNumbers: true,
                            mode: 'text/html',
                            lineWrapping: true
                        });


                        jQuery('.rs-cm-refresh').click(function() {
                            rb_cm_custom_css.refresh();
                            rb_cm_custom_js.refresh();
                        });

                        var hlLineC = rb_cm_custom_css.setLineClass(0, "activeline"),
                                hlLineJ = rb_cm_custom_js.setLineClass(0, "activeline");


                    });
                </script>

    <?php
}

?>
        </div>
        <div class="settings_panel_right">
            <script type="text/javascript">
                function drawToolBarPreview() {

                    var tslideprev = jQuery('.toolbar-sliderpreview'),
                            tslider = jQuery('.toolbar-slider'),
                            tslider_image = jQuery('.toolbar-slider-image'),
                            tprogress = jQuery('.toolbar-progressbar'),
                            tdot = jQuery('.toolbar-dottedoverlay'),
                            tthumbs = jQuery('.toolbar-navigation-thumbs'),
                            ttabs = jQuery('.toolbar-navigation-tabs'),
                            tbuls = jQuery('.toolbar-navigation-bullets'),
                            tla = jQuery('.toolbar-navigation-left'),
                            tra = jQuery('.toolbar-navigation-right');


                    // DRAW SHADOWS
                    jQuery('.shadowTypes').css({display: "none"});
                    tslideprev.removeClass("tp-shadow1").removeClass("tp-shadow2").removeClass("tp-shadow3").removeClass("tp-shadow4").removeClass("tp-shadow5").removeClass("tp-shadow6");

                    // MAKE ddd_IF NEEDED
                    if (jQuery('#ddd_parallax').attr('checked') && jQuery('#use_parallax').attr('checked')) {
                        punchgs.TweenLite.to(tslideprev, 0.5, {transformPerspective: 800, rotationY: 30, rotationX: 10, scale: 0.8});
                        if (jQuery('#ddd_parallax_shadow').attr('checked')) {
                            tslideprev.css({boxShadow: "0 45px 100px rgba(0, 0, 0, 0.4)"})
                        } else {
                            tslideprev.css({boxShadow: "none"})
                        }
                    } else {
                        punchgs.TweenLite.to(tslideprev, 0.5, {transformPerspective: 800, rotationY: 0, rotationX: 0, scale: 1});
                        tslideprev.addClass('tp-shadow' + jQuery('#shadow_type').val());
                        tslideprev.css({boxShadow: "none"})
                    }

                    tslideprev.css({padding: jQuery('#padding').val() + "px"});

                    // DRAWING BACKGROUND IMAGE OR COLOR
                    if (jQuery('#show_background_image').attr("checked") === "checked")	// DRAWING BACKGROUND IMAGE
                        tslider.css({background: "url(" + jQuery('#background_image').val() + ")",
                            backgroundSize: jQuery('#bg_fit').val(),
                            backgroundPosition: jQuery('#bg_repeat').val(),
                            backgroundRepeat: jQuery('#bg_position').val(),
                        });
                    else
                        tslider.css({background: jQuery('#background_color').val()});	// DRAW BACKGROUND COLOR

                    // DRAWING PROGRESS BAR
                    var progope = parseInt(jQuery('#progress_opa').val(), 0),
                            progheight = parseInt(jQuery('#progress_height').val(), 0);

                    progope = jQuery.isNumeric(progope) ? progope / 100 : 0.15;
                    progheight = jQuery.isNumeric(progheight) ? progheight : 5;

                    switch (jQuery('#show_timerbar').val()) {
                        case "top":
                            punchgs.TweenLite.set(tprogress, {backgroundColor: jQuery('#progressbar_color').val(), top: "0px", bottom: "auto", height: progheight + "px", opacity: progope});
                            break;
                        case "bottom":
                            punchgs.TweenLite.set(tprogress, {backgroundColor: jQuery('#progressbar_color').val(), bottom: "0px", top: "auto", height: progheight + "px", opacity: progope});
                            break;
                    }
                    if (jQuery('#enable_progressbar').attr('checked') === "checked")
                        punchgs.TweenLite.set(tprogress, {display: "block"});
                    else
                        punchgs.TweenLite.set(tprogress, {display: "none"});


                    function removeClasses(obj, cs) {
                        var classes = cs.split(",");
                        if (classes)
                            jQuery.each(classes, function(index, c) {
                                obj.removeClass("tbn-" + c);
                            });
                    }

                    jQuery('.toolbar-sliderpreview').removeClass("outer-left").removeClass("outer-right").removeClass("outer-top").removeClass("outer-bottom").removeClass("inner");

                    // SHOW / HIDE ARROWS
                    if (jQuery('#enable_arrows').attr("checked") !== "checked") {
                        tla.hide();
                        tra.hide();
                    } else {
                        tla.show();
                        tra.show();
                        removeClasses(tla, "left,right,center,top,bottom,middle");
                        removeClasses(tra, "left,right,center,top,bottom,middle");

                        // LEFT ARROW
                        var hor = jQuery('#leftarrow_align_hor option:selected').val(),
                                ver = jQuery('#leftarrow_align_vert option:selected').val();
                        ver = ver === "center" ? "middle" : ver;
                        tla.addClass("tbn-" + hor);
                        tla.addClass("tbn-" + ver);
                        var ml = Math.ceil(parseInt(jQuery('#leftarrow_offset_hor').val(), 0) / 4),
                                mt = Math.ceil(parseInt(jQuery('#leftarrow_offset_vert').val(), 0) / 4);

                        if (hor === "right")
                            tla.css({marginRight: ml + "px", marginLeft: "0px"});
                        else
                            tla.css({marginRight: "0px", marginLeft: ml + "px"});

                        if (ver === "bottom")
                            tla.css({marginBottom: mt + "px", marginTop: "0px"});
                        else
                            tla.css({marginBottom: "0px", marginTop: mt + "px"});


                        // RIGHT ARROW
                        hor = jQuery('#rightarrow_align_hor option:selected').val();
                        ver = jQuery('#rightarrow_align_vert option:selected').val();
                        ver = ver === "center" ? "middle" : ver;
                        tra.addClass("tbn-" + hor);
                        tra.addClass("tbn-" + ver);
                        ml = Math.ceil(parseInt(jQuery('#rightarrow_offset_hor').val(), 0) / 4),
                                mt = Math.ceil(parseInt(jQuery('#rightarrow_offset_vert').val(), 0) / 4);
                        if (hor === "right")
                            tra.css({marginRight: ml + "px", marginLeft: "0px"});
                        else
                            tra.css({marginRight: "0px", marginLeft: ml + "px"});

                        if (ver === "bottom")
                            tra.css({marginBottom: mt + "px", marginTop: "0px"});
                        else
                            tra.css({marginBottom: "0px", marginTop: mt + "px"});

                    }


                    // SHOW HIDE BULLETS
                    if (jQuery('#enable_bullets').attr("checked") !== "checked") {
                        tbuls.hide();
                    } else {
                        tbuls.show();
                        removeClasses(tbuls, "left,right,center,top,bottom,middle,vertical,horizontal,inner,outer-left,outer-right,outer-top,outer-bottom");

                        hor = jQuery('#bullets_align_hor option:selected').val();
                        ver = jQuery('#bullets_align_vert option:selected').val();
                        ver = ver === "center" ? "middle" : ver;
                        tbuls.addClass("tbn-" + hor);
                        tbuls.addClass("tbn-" + ver);

                        ml = Math.ceil(parseInt(jQuery('#bullets_offset_hor').val(), 0) / 4),
                                mt = Math.ceil(parseInt(jQuery('#bullets_offset_vert').val(), 0) / 4);
                        if (hor === "right")
                            tbuls.css({marginRight: ml + "px", marginLeft: "0px"});
                        else
                            tbuls.css({marginRight: "0px", marginLeft: ml + "px"});

                        if (ver === "bottom")
                            tbuls.css({marginBottom: mt + "px", marginTop: "0px"});
                        else
                            tbuls.css({marginBottom: "0px", marginTop: mt + "px"});

                        tbuls.addClass("tbn-" + jQuery('#bullets_direction option:selected').val());
                    }

                    // SHOW HIDE THUMBNAILS
                    if (jQuery('#enable_thumbnails').attr("checked") !== "checked") {
                        tthumbs.hide();
                    } else {
                        tthumbs.show();
                        removeClasses(tthumbs, "left,right,center,top,bottom,middle,vertical,horizontal,inner,outer,spanned,outer-left,outer-right,outer-top,outer-bottom");
                        tthumbs.addClass("tbn-" + jQuery('#thumbnails_align_hor option:selected').val());
                        var v = jQuery('#thumbnails_align_vert option:selected').val() === "center" ? "middle" : jQuery('#thumbnails_align_vert option:selected').val();
                        tthumbs.addClass("tbn-" + v);
                        tthumbs.addClass("tbn-" + jQuery('#thumbnail_direction option:selected').val());
                        if (jQuery('#span_thumbnails_wrapper').attr("checked") === "checked")
                            tthumbs.addClass("tbn-spanned");
                        jQuery('.toolbar-navigation-thumbs-bg').css({background: jQuery('#thumbnails_wrapper_color').val(), opacity: jQuery('#thumbnails_wrapper_opacity').val() / 100});

                        jQuery('.toolbar-sliderpreview').addClass(jQuery('#thumbnails_inner_outer option:selected').val())
                        tthumbs.addClass("tbn-" + jQuery('#thumbnails_inner_outer option:selected').val())

                    }

                    // SHOW HIDE TABS
                    if (jQuery('#enable_tabs').attr("checked") !== "checked") {
                        ttabs.hide();
                    } else {
                        ttabs.show();
                        removeClasses(ttabs, "left,right,center,top,bottom,middle,vertical,horizontal,inner,outer,spanned,outer-left,outer-right,outer-top,outer-bottom");
                        ttabs.addClass("tbn-" + jQuery('#tabs_align_hor option:selected').val());
                        var v = jQuery('#tabs_align_vert option:selected').val() === "center" ? "middle" : jQuery('#tabs_align_vert option:selected').val();
                        ttabs.addClass("tbn-" + v);
                        ttabs.addClass("tbn-" + jQuery('#tabs_direction option:selected').val());
                        if (jQuery('#span_tabs_wrapper').attr("checked") === "checked")
                            ttabs.addClass("tbn-spanned");
                        jQuery('.toolbar-navigation-tabs-bg').css({background: jQuery('#tabs_wrapper_color').val(), opacity: jQuery('#tabs_wrapper_opacity').val() / 100});
                        jQuery('.toolbar-sliderpreview').addClass(jQuery('#tabs_inner_outer option:selected').val());
                        ttabs.addClass("tbn-" + jQuery('#tabs_inner_outer option:selected').val());
                    }

                    // DRAWING DOTTED OVERLAY
                    tdot.removeClass("twoxtwo").removeClass("twoxtwowhite").removeClass("threexthree").removeClass("threexthreewhite");
                    tdot.addClass(jQuery('#background_dotted_overlay').val());
                }
                jQuery(document).ready(function() {
                    RbSliderAdmin.initEditSlideView();
                });
            </script>

            <div class="settings_wrapper closeallothers" id="form_slider_params_wrap">
                <form name="form_slider_params" id="form_slider_params" >
                    <!-- GENERAL SETTINGS -->
                    <div class="setting_box">
                        <h3 class="box_closed"><i class="rs-rp-accordion-icon eg-icon-cog-alt"></i>
                            <div class="setting_box-arrow"></div>
                            <span><?php $modules->l("General Settings"); ?></span>
                        </h3>

                        <div class="inside" style="display:none;">

                            <ul class="main-options-small-tabs" style="display:inline-block; ">
                                <li data-content="#general-slideshow" class="selected"><?php $modules->l('Slideshow'); ?></li>
                                <li data-content="#general-defaults" class=""><?php $modules->l('Defaults'); ?></li>
                                <li data-content="#general-progressbar" class="dontshowonhero"><?php $modules->l('Progress Bar'); ?></li>
                                <li data-content="#general-firstslide" class="dontshowonhero"><?php $modules->l('1st Slide'); ?></li>
                                <li data-content="#general-misc"><?php $modules->l('Misc.'); ?></li>

                            </ul>

                            <!-- GENERAL MISC. -->
                            <div id="general-misc" style="display:none">
<?php
if (UnitePsmlRb::ispsmlExists()) {

    ?>
                                    <!-- MULTI LANGUANGE -->
                                    <span id="label_use_psml" class="label" origtitle="<?php $modules->l("Show multi language controls across the slider. Only available when psml plugin exists.");

    ?>"><?php $modules->l("Use Multi Language");

    ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel" id="use_psml" name="use_psml" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'use_psml', 'off'), "on");

    ?> >
                                    <div class="clearfix"></div>
    <?php
}

?>

                                <div class="dontshowonhero">
                                    <!-- NEXT SLIDE ON FOCUS -->
                                    <span id="label_next_slide_on_window_focus" class="label" origtitle="<?php $modules->l("Call next slide when inactive browser tab is focused again. Use this for avoid dissorted layers and broken timeouts after bluring the browser tab."); ?>"><?php $modules->l("Next Slide on Focus"); ?> </span>
                                    <input type="checkbox"  class="tp-moderncheckbox withlabel" id="next_slide_on_window_focus" name="next_slide_on_window_focus" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'next_slide_on_window_focus', 'off'), "on"); ?> >
                                    <div class="clearfix"></div>
                                </div>

                                <div>
                                    <!-- BLUR ON FOCUS -->
                                    <span id="label_disable_focus_listener" class="label" origtitle="<?php $modules->l("This will disable the blur/focus behavior of the browser."); ?>"><?php $modules->l("Disable Blur/Focus behavior"); ?> </span>
                                    <input type="checkbox"  class="tp-moderncheckbox withlabel" id="disable_focus_listener" name="disable_focus_listener" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'disable_focus_listener', 'off'), "on"); ?> >
                                    <div class="clearfix"></div>
                                </div>
                            </div><!-- end of GENERAL MISC -->

                            <!-- GENERAL DEFAULTS -->
                            <div id="general-defaults" style="display:none">
                                <!-- DELAY -->
                                <span class="label" id="label_delay" origtitle="<?php $modules->l("The time one slide stays on the screen in Milliseconds. This is a Default Global value. Can be adjusted slide to slide also in the slide editor."); ?>"><?php $modules->l("Default Slide Duration"); ?> </span>
                                <input type="text"  class="text-sidebar withlabel" id="delay" name="delay" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'delay', '9000'); ?>">
                                <span ><?php $modules->l("ms"); ?></span>
                                <div class="clearfix"></div>

                                <!-- Initialisation Delay -->
                                <span id="label_start_js_after_delay" class="label" origtitle="<?php $modules->l("Sets a delay before the Slider gets initialized"); ?>"><?php $modules->l("Initialization Delay"); ?> </span>
                                <input type="text"  class="text-sidebar withlabel" id="start_js_after_delay" name="start_js_after_delay" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'start_js_after_delay', '0'); ?>">
                                <span ><?php $modules->l("ms"); ?></span>
                                <div class="clear"></div>
                                <div id="reset-to-default-inputs">
                                    <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-slide_transition" /> <span id="label_def-slide_transition" origtitle="<?php $modules->l("Default transition by creating a new slide."); ?>" class="label"><?php $modules->l('Transitions'); ?></span>
                                    <select id="def-slide_transition" name="def-slide_transition" style="max-width:105px" class="withlabel">
                                        <?php
                                        $def_trans = UniteFunctionsRb::getVal($arrFieldsParams, 'def-slide_transition', 'fade');
                                        foreach ($transitions as $handle => $name) {
                                            $not = (strpos($handle, 'notselectable') !== false) ? ' disabled="disabled"' : '';
                                            $sel = ($def_trans == $handle) ? ' selected="selected"' : '';
                                            echo '<option value="' . $handle . '"' . $not . $sel . '>' . $name . '</option>';
                                        }

                                        ?>
                                    </select>
                                    <div class="clear"></div>

<?php $def_trans_dur = UniteFunctionsRb::getVal($arrFieldsParams, 'def-transition_duration', '300'); ?>
                                    <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-transition_duration" /> <span id="label_def-transition_duration" origtitle="<?php $modules->l("Default transition duration by creating a new slide."); ?>" class="label" origtitle=""><?php $modules->l('Animation Duration'); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="def-transition_duration" name="def-transition_duration" value="<?php echo $def_trans_dur; ?>">
                                    <span><?php $modules->l('ms'); ?></span>
                                    <div class="clear"></div>

<?php
$img_sizes = UniteBaseClassRb::getAllImageSizes();
$bg_image_size = UniteFunctionsRb::getVal($arrFieldsParams, 'def-image_source_type', 'full');

?>
                                    <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-image_source_type" /> <span id="label_def-image_source_type" class="label" origtitle="<?php $modules->l("Default main image source size by creating a new slide."); ?>" ><?php $modules->l('Image Source Size'); ?></span>
                                    <select name="def-image_source_type">
                                    <?php
                                           foreach ($img_sizes as $imghandle => $imgSize) {
                                               $sel = ($bg_image_size == $imghandle) ? ' selected="selected"' : '';
                                               echo '<option value="' . $imghandle . '"' . $sel . '>' . $imgSize . '</option>';
                                           }

                                           ?>
                                    </select>
                                    <div class="clear"></div>

<?php
$bgFit = UniteFunctionsRb::getVal($arrFieldsParams, 'def-background_fit', 'cover');
$bgFitX = UniteFunctionsRb::getVal($arrFieldsParams, 'def-bg_fit_x', '100');
$bgFitY = UniteFunctionsRb::getVal($arrFieldsParams, 'def-bg_fit_y', '100');

?>
                                    <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-background_fit" /> <span id="label_background_fit" origtitle="<?php $modules->l("Default background size by creating a new slide."); ?>"  class="label"><?php $modules->l('Background Fit'); ?></span>
                                    <select id="def-background_fit" name="def-background_fit" style="max-width: 105px;" class="withlabel">
                                        <option value="cover" <?php Rbthemeslider::selected($bgFit, 'cover') ?>>cover</option>
                                        <option value="contain" <?php Rbthemeslider::selected($bgFit, 'contain') ?>>contain</option>
                                        <option value="percentage" <?php Rbthemeslider::selected($bgFit, 'percentage') ?>>(%, %)</option>
                                        <option value="normal" <?php Rbthemeslider::selected($bgFit, 'normal') ?>>normal</option>
                                    </select>
                                    <input type="text" name="def-bg_fit_x" style="<?php
                                    if ($bgFit != 'percentage') {
                                        echo 'display: none; ';
                                    }

                                    ?> width:60px;margin-right:10px" value="<?php echo $bgFitX; ?>" />
                                    <input type="text" name="def-bg_fit_y" style="<?php
                                    if ($bgFit != 'percentage') {
                                        echo 'display: none; ';
                                    }

                                    ?> width:60px;margin-right:10px"  value="<?php echo $bgFitY; ?>" />
                                    <div class="clear"></div>

<?php
$bgPosition = UniteFunctionsRb::getVal($arrFieldsParams, 'def-bg_position', 'center center');
$bgPositionX = UniteFunctionsRb::getVal($arrFieldsParams, 'def-bg_position_x', '0');
$bgPositionY = UniteFunctionsRb::getVal($arrFieldsParams, 'def-bg_position_y', '0');

?>
                                    <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-bg_position" /> <span id="label_slide_bg_position" origtitle="<?php $modules->l("Default background position by creating a new slide."); ?>" class="label"><?php $modules->l('Background Position'); ?></span>
                                    <select name="def-bg_position" id="slide_bg_position">
                                        <option value="center top" <?php Rbthemeslider::selected($bgPosition, 'center top') ?>>center top</option>
                                        <option value="center right" <?php Rbthemeslider::selected($bgPosition, 'center right') ?>>center right</option>
                                        <option value="center bottom" <?php Rbthemeslider::selected($bgPosition, 'center bottom') ?>>center bottom</option>
                                        <option value="center center" <?php Rbthemeslider::selected($bgPosition, 'center center') ?>>center center</option>
                                        <option value="left top" <?php Rbthemeslider::selected($bgPosition, 'left top') ?>>left top</option>
                                        <option value="left center" <?php Rbthemeslider::selected($bgPosition, 'left center') ?>>left center</option>
                                        <option value="left bottom" <?php Rbthemeslider::selected($bgPosition, 'left bottom') ?>>left bottom</option>
                                        <option value="right top" <?php Rbthemeslider::selected($bgPosition, 'right top') ?>>right top</option>
                                        <option value="right center" <?php Rbthemeslider::selected($bgPosition, 'right center') ?>>right center</option>
                                        <option value="right bottom" <?php Rbthemeslider::selected($bgPosition, 'right bottom') ?>>right bottom</option>
                                    </select>
                                    <input type="text" name="def-bg_position_x" style="<?php
if ($bgPosition != 'percentage') {
    echo 'display: none;';
}

?>width:60px;margin-right:10px" value="<?php echo $bgPositionX; ?>" />
                                    <input type="text" name="def-bg_position_y" style="<?php
                                        if ($bgPosition != 'percentage') {
                                            echo 'display: none;';
                                        }

?>width:60px;margin-right:10px" value="<?php echo $bgPositionY; ?>" />
                                    <div class="clear"></div>
<?php
$bgRepeat = UniteFunctionsRb::getVal($arrFieldsParams, 'def-bg_repeat', 'no-repeat');

?>
                                    <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-bg_repeat" />
                                    <span id="slide_bg_repeat" origtitle="<?php $modules->l("Default background repeat by creating a new slide."); ?>"  class="label"><?php $modules->l('Background Repeat'); ?></span>
                                    <select name="def-bg_repeat" id="slide_bg_repeat" style="margin-right:20px">
                                        <option value="no-repeat" <?php Rbthemeslider::selected($bgRepeat, 'no-repeat') ?>>no-repeat</option>
                                        <option value="repeat" <?php Rbthemeslider::selected($bgRepeat, 'repeat') ?>>repeat</option>
                                        <option value="repeat-x" <?php Rbthemeslider::selected($bgRepeat, 'repeat-x') ?>>repeat-x</option>
                                        <option value="repeat-y" <?php Rbthemeslider::selected($bgRepeat, 'repeat-y') ?>>repeat-y</option>
                                    </select>
                                    <div class="clear"></div>

<?php $kenburn_effect = UniteFunctionsRb::getVal($arrFieldsParams, 'def-kenburn_effect', 'off'); ?>
                                    <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-kenburn$modules->lffect" />
                                    <span id="def-kenburn_effect" origtitle="<?php $modules->l("Default Ken/Burn setting by creating a new slide."); ?>" class="label"><?php $modules->l('Ken Burns / Pan Zoom:'); ?></span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel" id="def-kenburn_effect" name="def-kenburn_effect" data-unchecked="off" <?php checked($kenburn_effect, 'on'); ?>>

                                    <div class="clear"></div>
                                    <div id="def-kenburns-wrapper" <?php
if ($kenburn_effect == 'off') {
    echo 'style="display: none;"';
}

?>>

<?php $kb_start_fit = UniteFunctionsRb::getVal($arrFieldsParams, 'def-kb_start_fit', '100'); ?>
                                        <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-kb_start_fit" />
                                        <span id="label_kb_start_fit" class="label"><?php $modules->l('Start Fit: (in %):'); ?></span>
                                        <input type="text" name="def-kb_start_fit" value="<?php echo (int) ($kb_start_fit); ?>" />
                                        <div class="clear"></div>

                                        <?php $kb_easing = UniteFunctionsRb::getVal($arrFieldsParams, 'def-kb$modules->lasing', 'Linear.easeNone'); ?>
                                        <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-kb$modules->lasing" /> <span id="label_kb$modules->lasing" class="label"><?php $modules->l('Easing:'); ?></span>
                                        <select name="def-kb$modules->lasing">
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Linear.easeNone') ?> value="Linear.easeNone">Linear.easeNone</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power0.easeIn') ?> value="Power0.easeIn">Power0.easeIn  (linear)</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power0.easeInOut') ?> value="Power0.easeInOut">Power0.easeInOut  (linear)</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power0.easeOut') ?> value="Power0.easeOut">Power0.easeOut  (linear)</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power1.easeIn') ?> value="Power1.easeIn">Power1.easeIn</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power1.easeInOut') ?> value="Power1.easeInOut">Power1.easeInOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power1.easeOut') ?> value="Power1.easeOut">Power1.easeOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power2.easeIn') ?> value="Power2.easeIn">Power2.easeIn</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power2.easeInOut') ?> value="Power2.easeInOut">Power2.easeInOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power2.easeOut') ?> value="Power2.easeOut">Power2.easeOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power3.easeIn') ?> value="Power3.easeIn">Power3.easeIn</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power3.easeInOut') ?> value="Power3.easeInOut">Power3.easeInOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power3.easeOut') ?> value="Power3.easeOut">Power3.easeOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power4.easeIn') ?> value="Power4.easeIn">Power4.easeIn</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power4.easeInOut') ?> value="Power4.easeInOut">Power4.easeInOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Power4.easeOut') ?> value="Power4.easeOut">Power4.easeOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Back.easeIn') ?> value="Back.easeIn">Back.easeIn</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Back.easeInOut') ?> value="Back.easeInOut">Back.easeInOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Back.easeOut') ?> value="Back.easeOut">Back.easeOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Bounce.easeIn') ?> value="Bounce.easeIn">Bounce.easeIn</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Bounce.easeInOut') ?> value="Bounce.easeInOut">Bounce.easeInOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Bounce.easeOut') ?> value="Bounce.easeOut">Bounce.easeOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Circ.easeIn') ?> value="Circ.easeIn">Circ.easeIn</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Circ.easeInOut') ?> value="Circ.easeInOut">Circ.easeInOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Circ.easeOut') ?> value="Circ.easeOut">Circ.easeOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Elastic.easeIn') ?> value="Elastic.easeIn">Elastic.easeIn</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Elastic.easeInOut') ?> value="Elastic.easeInOut">Elastic.easeInOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Elastic.easeOut') ?> value="Elastic.easeOut">Elastic.easeOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Expo.easeIn') ?> value="Expo.easeIn">Expo.easeIn</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Expo.easeInOut') ?> value="Expo.easeInOut">Expo.easeInOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Expo.easeOut') ?> value="Expo.easeOut">Expo.easeOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Sine.easeIn') ?> value="Sine.easeIn">Sine.easeIn</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Sine.easeInOut') ?> value="Sine.easeInOut">Sine.easeInOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'Sine.easeOut') ?> value="Sine.easeOut">Sine.easeOut</option>
                                            <option <?php Rbthemeslider::selected($kb_easing, 'SlowMo.ease') ?> value="SlowMo.ease">SlowMo.ease</option>
                                        </select>
                                        <div class="clear"></div>
                                        ?>

<?php $kb_start_fit = UniteFunctionsRb::getVal($arrFieldsParams, 'def-kb$modules->lnd_fit', '100'); ?>
                                        <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-kb$modules->lnd_fit" /> <span id="label_kb$modules->lnd_fit" class="label"><?php $modules->l('End Fit: (in %):'); ?></span>
                                        <input type="text" name="def-kb$modules->lnd_fit" value="<?php echo (int) ($kb_start_fit); ?>" />
                                        <div class="clear"></div>

<?php $kb_start_offset_x = UniteFunctionsRb::getVal($arrFieldsParams, 'def-kb_start_offset_x', '0'); ?>
                                        <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-kb_start_offset_x" /> <span id="label_kb$modules->lnd_fit" class="label"><?php $modules->l('Start Offset X:'); ?></span>
                                        <input type="text" name="def-kb_start_offset_x" value="<?php echo (int) ($kb_start_offset_x); ?>" />
                                        <div class="clear"></div>

<?php $kb_start_offset_y = UniteFunctionsRb::getVal($arrFieldsParams, 'def-kb_start_offset_y', '0'); ?>
                                        <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-kb_start_offset_y" /> <span id="label_kb$modules->lnd_fit" class="label"><?php $modules->l('Start Offset Y:'); ?></span>
                                        <input type="text" name="def-kb_start_offset_y" value="<?php echo (int) ($kb_start_offset_y); ?>" />
                                        <div class="clear"></div>

<?php $kb_end_offset_x = UniteFunctionsRb::getVal($arrFieldsParams, 'def-kb$modules->lnd_offset_x', '0'); ?>
                                        <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-kb$modules->lnd_offset_x" /> <span id="label_kb$modules->lnd_fit" class="label"><?php $modules->l('End Offset X:'); ?></span>
                                        <input type="text" name="def-kb$modules->lnd_offset_x" value="<?php echo (int) ($kb_end_offset_x); ?>" />
                                        <div class="clear"></div>

<?php $kb_end_offset_y = UniteFunctionsRb::getVal($arrFieldsParams, 'def-kb$modules->lnd_offset_y', '0'); ?>
                                        <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-kb$modules->lnd_offset_y" /> <span id="label_kb$modules->lnd_fit" class="label"><?php $modules->l('End Offset Y:'); ?></span>
                                        <input type="text" name="def-kb$modules->lnd_offset_y" value="<?php echo (int) ($kb_end_offset_y); ?>" />
                                        <div class="clear"></div>

                                        <?php $kb_start_rotate = UniteFunctionsRb::getVal($arrFieldsParams, 'def-kb_start_rotate', '0'); ?>
                                        <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-kb_start_rotate" /> <span id="label_kb$modules->lnd_fit" class="label"><?php $modules->l('Start Rotate:'); ?></span>
                                        <input type="text" name="def-kb_start_rotate" value="<?php echo (int) ($kb_start_rotate); ?>" />
                                        <div class="clear"></div>

                                        <?php $kb_end_rotate = UniteFunctionsRb::getVal($arrFieldsParams, 'def-kb$modules->lnd_rotate', '0'); ?>
                                        <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-kb$modules->lnd_rotate" /> <span id="label_kb$modules->lnd_fit" class="label"><?php $modules->l('End Rotate:'); ?></span>
                                        <input type="text" name="def-kb$modules->lnd_rotate" value="<?php echo (int) ($kb_end_rotate); ?>" />
                                        <div class="clear"></div>

<?php $kb_duration = UniteFunctionsRb::getVal($arrFieldsParams, 'def-kb_duration', '10000'); ?>
                                        <input type="checkbox" class="rs-ingore-save rs-reset-slide-setting" name="reset-kb_duration" /> <span id="label_kb_duration" class="label"><?php $modules->l('Duration (in ms):'); ?></span>
                                        <input type="text" name="def-kb_duration" value="<?php echo (int) ($kb_duration); ?>" />
                                        <div class="clear"></div>
                                    </div>
                                    <span class="overwrite-arrow"></span>
                                    <input type="button" id="reset_slide_button" value="<?php $modules->l('Overwrite Selected Settings on all Slides'); ?>" class="button-primary rbblue" origtitle="">
                                    <div class="clear"></div>
                                </div>


                            </div> <!-- END OF GENERAL DEFAULTS -->

                            <!-- GENERAL FIRST SLIDE -->
                            <div id="general-firstslide" style="display:none">
                                <span id="label_start_with_slide$modules->lnable" class="label" origtitle="<?php $modules->l("Activate Alternative 1st Slide."); ?>"><?php $modules->l("Activate Alt. 1st Slide"); ?></span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="start_with_slide$modules->lnable" name="start_with_slide$modules->lnable" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "start_with_slide$modules->lnable", "off"), "on"); ?> >
                                <div class="clear"></div>

<?php $start_with_slide = (int) (UniteFunctionsRb::getVal($arrFieldsParams, 'start_with_slide', '1')); ?>
                                <div id="start_with_slide_row">
                                    <span id="label_start_with_slide" class="label" origtitle="<?php $modules->l("Start from a different slide instead of the first slide. I.e. good for preview / edit mode."); ?>"><?php $modules->l("Alternative 1st Slide"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel" id="start_with_slide" name="start_with_slide" value="<?php echo $start_with_slide; ?>">
                                    <div class="clear"></div>
                                </div>

                                <span id="label_first_transition_active" class="label" origtitle="<?php $modules->l("If active, it will overwrite the first slide transition. Use it to get special transition for the first slide."); ?>"><?php $modules->l("First Transition Active"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="first_transition_active" name="first_transition_active" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "first_transition_active", "off"), "on"); ?>>
                                <div class="clear"></div>

                                <div id="first_transition_row" class="withsublabels">
                                    <span id="label_first_transition_type" class="label" origtitle="<?php $modules->l("First slide transition type"); ?>"><?php $modules->l("Transition Type"); ?> </span>
                                    <select id="first_transition_type" name="first_transition_type" style="max-width:100px"  class="withlabel">
<?php
$transitions = $operations->getArrTransition();
$ftt = UniteFunctionsRb::getVal($arrFieldsParams, 'first_transition_type', 'fade');
foreach ($transitions as $handle => $name) {
    $not = (strpos($handle, 'notselectable') !== false) ? ' disabled="disabled"' : '';
    $sel = ($handle == $ftt) ? ' selected="selected"' : '';
    echo '<option value="' . $handle . '"' . $not . $sel . '>' . $name . '</option>';
}

?>
                                    </select>
                                    <div class="clear"></div>

                                    <span id="label_first_transition_duration" class="label" origtitle="<?php $modules->l("First slide transition duration."); ?>"><?php $modules->l("Transition Duration"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel" id="first_transition_duration" name="first_transition_duration" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, "first_transition_duration", "300"); ?>">
                                    <span><?php $modules->l("ms"); ?></span>
                                    <div class="clear"></div>


                                    <span id="label_first_transition_slot_amount" class="label" origtitle="<?php $modules->l("The number of slots or boxes the slide is divided into."); ?>"><?php $modules->l("Transition Slot Amount"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel"  id="first_transition_slot_amount" name="first_transition_slot_amount" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, "first_transition_slot_amount", "7"); ?>">
                                    <span><?php $modules->l("ms"); ?></span>
                                    <div class="clear"></div>
                                </div>
                            </div><!-- END OF GENERAL FIRST SLIDE -->

                            <!-- GENERAL SLIDE SHOW -->
                            <div id="general-slideshow" style="display:block;">
                                <div class="dontshowonhero">
                                    <!-- Stop Slider on Hover -->
                                    <span id="label_stop_on_hover" class="label" origtitle="<?php $modules->l("Stops the Timer when mouse is hovering the slider."); ?>"><?php $modules->l("Stop Slide On Hover"); ?></span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel" id="stop_on_hover" name="stop_on_hover" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'stop_on_hover', 'off'), "on"); ?> >
                                    <div class="clear"></div>

                                    <!-- Stop Slider -->
                                    <span class="label label-with-subsection" id="label_stop_slider" origtitle="<?php $modules->l("Stops the slideshow after the predefined loop amount at the predefined slide."); ?>"><?php $modules->l("Stop Slider After ..."); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel"  id="stop_slider" name="stop_slider" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'stop_slider', 'off'), 'on'); ?> >
                                    <div class="clear"></div>

                                    <div id="stopoptionsofslider" class="withsublabels">
                                        <!-- Stop After loops -->
                                        <span class="label " id="label_stop_after_loops" origtitle="<?php $modules->l("Stops the slider after certain amount of loops. 0 related to the first loop."); ?>"><?php $modules->l("Amount of Loops"); ?> </span>
                                        <input type="text" class="text-sidebar withlabel"   id="stop_after_loops" name="stop_after_loops" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'stop_after_loops', '0'); ?>">
                                        <div class="clear"></div>

                                        <!-- Stop At Slide -->
                                        <span class="label" id="label_stop_at_slide" origtitle="<?php $modules->l("Stops the slider at the given slide"); ?>"><?php $modules->l("At Slide"); ?> </span>
                                        <input type="text"  class="text-sidebar withlabel"  id="stop_at_slide" name="stop_at_slide" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'stop_at_slide', '2'); ?>">
                                        <div class="clear"></div>
                                    </div>

                                    <!-- SHUFFLE -->
                                    <span id="label_shuffle" class="label" origtitle="<?php $modules->l("Randomize the order of the slides at every Page reload."); ?>"><?php $modules->l("Shuffle / Random Mode"); ?> </span>
                                    <input type="checkbox"  class="tp-moderncheckbox withlabel" id="shuffle" name="shuffle" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'shuffle', 'off'), "on"); ?> >
                                    <div class="clearfix"></div>

                                    <!-- Loop Single Slide -->
                                    <span class="label" id="label_loop_slide" origtitle="<?php $modules->l("If only one Slide is in the Slider, you can choose wether the Slide should loop or if it should stop. If only one Slide exist, slide will be duplicated !"); ?>"><?php $modules->l("Loop Single Slide"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel"  id="loop_slide" name="loop_slide" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'loop_slide', 'off'), "on"); ?> >
                                    <div class="clear"></div>
                                </div>

                                <!-- ViewPort Slider -->
                                <span class="label label-with-subsection" origtitle="<?php $modules->l("Allow to stop the Slider out of viewport."); ?>"><?php $modules->l("Stop Slider Out of ViewPort"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel"  id="label_viewport" name="label_viewport" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'label_viewport', 'off'), 'on'); ?> >
                                <div class="clear"></div>

                                <div id="viewportoptionsofslider" class="withsublabels" <?php
if (UniteFunctionsRb::getVal($arrFieldsParams, 'label_viewport', 'off') == 'off') {
    echo 'style="display: none;"';
}
$viewport_start = UniteFunctionsRb::getVal($arrFieldsParams, 'viewport_start', 'wait');
?>>
                                    <span class="label"><?php $modules->l('Out Of ViewPort:'); ?></span>
                                    <select name="viewport_start">
                                        <option <?php Rbthemeslider::selected($viewport_start, 'wait') ?> value="wait">Wait</option>
                                        <option <?php Rbthemeslider::selected($viewport_start, 'pause') ?> value="pause">Pause</option>
                                    </select>
                                    <div class="clear"></div>

                                    <span class="label" origmedia="show" origtitle="<?php $modules->l("Min. Size of Slider must be in Viewport before slide starts again."); ?>"><?php $modules->l("Area out of ViewPort:"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel"  id="viewport_area" name="viewport_area" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'viewport_area', '80'); ?>">
                                    <span><?php $modules->l("%"); ?></span>

                                </div>
                            </div><!-- END OF GENERAL SLIDE SHOW -->

                            <!-- GENERAL PROGRESSBAR -->
                            <div id="general-progressbar" style="display:none">

                                <span class="label" id="label$modules->lnable_progressbar" origmedia='show' origtitle="<?php $modules->l("Enable / disable progress var"); ?>"><?php $modules->l("Progress Bar Active"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="enable_progressbar" name="enable_progressbar" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "enable_progressbar", "off"), "on"); ?>>
                                <div id="progressbar_settings">
                                    <!-- Show Progressbar -->
                                    <span class="label" id="label_show_timerbar" origmedia="show" origtitle="<?php $modules->l("Position of the progress bar."); ?>"><?php $modules->l("Progress Bar Position"); ?> </span>
                                    <?php $show_timerbar = UniteFunctionsRb::getVal($arrFieldsParams, 'show_timerbar', 'top');?>
                                    <select id="show_timerbar" name="show_timerbar" class="withlabel" >
                                        <option <?php Rbthemeslider::selected($show_timerbar, 'top') ?> value="top">Top</option>
                                        <option <?php Rbthemeslider::selected($show_timerbar, 'bottom') ?> value="bottom">Bottom</option>
                                    </select>
                                    <div class="clear"></div>

                                    <!-- Progress Bar Height -->
                                    <span class="label" id="label_progress_height" origmedia="show" origtitle="<?php $modules->l("The height of the progress bar"); ?>"><?php $modules->l("Progress Bar Heigth"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel"  id="progress_height" name="progress_height" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'progress_height', '5'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>

                                    <!-- Progress Bar Opacity -->
                                    <span class="label" id="label_progress_opa" origmedia="show" origtitle="<?php $modules->l("The opacity of the progress bar <br>(0 == Transparent, 100 = Solid color, 50 = 50% opacity etc...)"); ?>"><?php $modules->l("Progress Bar Opacity"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel"  id="progress_opa" name="progress_opa" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'progress_opa', '15'); ?>">
                                    <span><?php $modules->l("%"); ?></span>
                                    <div class="clear"></div>

                                    <!-- Progress Bar Color -->
                                    <span class="label" id="label_progressbar_color" origmedia="show" origtitle="<?php $modules->l("Color of the progress bar."); ?>"><?php $modules->l("Progress Bar Color"); ?> </span>
                                    <input type="text" class="my-color-field rs-layer-input-field tipsy$modules->lnabled_top withlabel inputColorPicker" title="<?php $modules->l("Font Color"); ?>"  id="progressbar_color" name="progressbar_color" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'progressbar_color', '#000000'); ?>" />
                                    <div class="clear"></div>
                                </div>
                            </div><!-- END OF GENERAL PROGRESSBAR -->
                        </div>

                        <script>
                            jQuery('#stop_slider').on("change", function() {
                                var sbi = jQuery(this);
                                if (sbi.attr("checked") === "checked") {
                                    jQuery('#stopoptionsofslider').show();
                                } else {
                                    jQuery('#stopoptionsofslider').hide();
                                }
                                drawToolBarPreview();
                            });

                            jQuery('#label_viewport').on("change", function() {
                                var sbi = jQuery(this);
                                if (sbi.attr("checked") === "checked") {
                                    jQuery('#viewportoptionsofslider').show();
                                } else {
                                    jQuery('#viewportoptionsofslider').hide();
                                }
                                drawToolBarPreview();
                            });


                            jQuery('#enable_progressbar').on("change", function() {
                                var sbi = jQuery(this);
                                if (sbi.attr('checked') !== "checked")
                                    jQuery('#progressbar_settings').hide();
                                else
                                    jQuery('#progressbar_settings').show();
                                drawToolBarPreview();
                            });

                            jQuery('#progress_height').on("keyup", drawToolBarPreview);
                            jQuery('#progress_opa').on("keyup", drawToolBarPreview);

                            jQuery('#enable_progressbar').change();
                            jQuery('#stop_slider').change();
                            jQuery('#show_timerbar').change();

                            // ALTERNATIVE FIRST SLIDE
                            jQuery('#first_transition_active').on("change", function() {
                                var sbi = jQuery(this);

                                if (sbi.attr("checked") === "checked") {
                                    jQuery('#first_transition_row').show();

                                } else {
                                    jQuery('#first_transition_row').hide();
                                }
                            });
                            jQuery('#first_transition_active').change();
                        </script>
                    </div><!-- END OF GENERAL SETTINGS -->

                    <!-- LAYOUT VISAL SETTINGS -->
                    <div class="setting_box">
                        <h3 class="box_closed"><i class="rs-rp-accordion-icon eg-icon-droplet"></i>
                            <div class="setting_box-arrow"></div>
                            <span><?php $modules->l("Layout & Visual"); ?></span>
                        </h3>

                        <div class="inside" style="display:none">
                            <ul class="main-options-small-tabs" style="display:inline-block; ">
                                <li data-content="#visual-appearance" class="selected"><?php $modules->l('Appearance'); ?></li>
                                <li data-content="#visual-spinner"><?php $modules->l('Spinner'); ?></li>
                                <li data-content="#visual-mobile"><?php $modules->l('Mobile'); ?></li>
                                <li data-content="#visual-position"><?php $modules->l('Position'); ?></li>
                            </ul>

                            <!-- VISUAL Mobile -->
                            <div id="visual-mobile" style="display:none">
                                <span class="label" id="label_disable_on_mobile" origtitle="<?php $modules->l("If this is enabled, the slider will not be loaded on mobile devices."); ?>"><?php $modules->l("Disable Slider on Mobile"); ?></span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="disable_on_mobile" name="disable_on_mobile" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "disable_on_mobile", "off"), "on"); ?> >
                                <div class="clear"></div>


                                <span class="label" id="label_disable_kenburns_on_mobile"  origtitle="<?php $modules->l("This will disable KenBurns on mobile devices to save performance"); ?>"><?php $modules->l("Disable KenBurn On Mobile"); ?></span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="disable_kenburns_on_mobile" name="disable_kenburns_on_mobile" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "disable_kenburns_on_mobile", "off"), "on"); ?> >
                                <div class="clear"></div>

                                <h4><?php $modules->l("Hide Element Under Width:"); ?></h4>

                                <span class="label" id="label_hide_slider_under"  origtitle="<?php $modules->l("Hide the slider under the defined slider width. Value 0 will disable the function."); ?>"><?php $modules->l("Slider"); ?></span>
                                <input type="text" class="text-sidebar withlabel" id="hide_slider_under" name="hide_slider_under" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, "hide_slider_under", "0"); ?>">
                                <span><?php $modules->l("px"); ?></span>
                                <div class="clear"></div>


                                <span class="label" id="label_hide_defined_layers_under" style="font-size:12px" origtitle="<?php $modules->l("Hide the selected layers (set layers hide under in slide editor) under the defined slider width. Value 0 will disable the function."); ?>"><?php $modules->l("Predefined Layers"); ?></span>
                                <input type="text" class="text-sidebar withlabel" id="hide_defined_layers_under" name="hide_defined_layers_under" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, "hide_defined_layers_under", "0"); ?>">
                                <span><?php $modules->l("px"); ?></span>
                                <div class="clear"></div>

                                <span class="label" id="label_hide_all_layers_under"  origtitle="<?php $modules->l("Hide all layers under the defined slider width. Value 0 will disable the function."); ?>"><?php $modules->l("All Layers"); ?></span>
                                <input type="text" class="text-sidebar withlabel" id="hide_all_layers_under" name="hide_all_layers_under" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, "hide_all_layers_under", "0"); ?>">
                                <span><?php $modules->l("px"); ?></span>
                                <div class="clear"></div>

                            </div><!-- VISUAL MOBILE -->


                            <!-- VISUAL APPEARANCE -->
                            <div id="visual-appearance" style="display:block">
                                <div class="hide_on_ddd_parallax">
                                    <span class="label " id="label_shadow_type" origmedia='show' origtitle="<?php $modules->l("The Shadow display underneath the banner."); ?>"><?php $modules->l("Shadow Type"); ?> </span>
                                    <?php $shadow_type = UniteFunctionsRb::getVal($arrFieldsParams, 'shadow_type', '0');?>
                                    <select id="shadow_type"  class="withlabel" name="shadow_type">
                                        <option <?php Rbthemeslider::selected($shadow_type, '0') ?> value="0"><?php $modules->l("No Shadow"); ?></option>
                                        <option <?php Rbthemeslider::selected($shadow_type, '1') ?> value="1">1</option>
                                        <option <?php Rbthemeslider::selected($shadow_type, '2') ?> value="2">2</option>
                                        <option <?php Rbthemeslider::selected($shadow_type, '3') ?> value="3">3</option>
                                        <option <?php Rbthemeslider::selected($shadow_type, '4') ?> value="4">4</option>
                                        <option <?php Rbthemeslider::selected($shadow_type, '5') ?> value="5">5</option>
                                        <option <?php Rbthemeslider::selected($shadow_type, '6') ?> value="6">6</option>
                                    </select>
                                    <div class="clear"></div>
                                </div>
                                <span class="label" id="label_background_dotted_overlay" origmedia="show" origtitle="<?php $modules->l("Show a dotted overlay over the slides."); ?>"><?php $modules->l("Dotted Overlay Size"); ?> </span>
                                <?php $background_dotted_overlay = UniteFunctionsRb::getVal($arrFieldsParams, 'background_dotted_overlay', 'none');?>
                                <select id="background_dotted_overlay" name="background_dotted_overlay" class="withlabel">
                                    <option <?php Rbthemeslider::selected($background_dotted_overlay, 'none') ?> value="none"><?php $modules->l("none"); ?></option>
                                    <option <?php Rbthemeslider::selected($background_dotted_overlay, 'twoxtwo') ?> value="twoxtwo"><?php $modules->l("2 x 2 Black"); ?></option>
                                    <option <?php Rbthemeslider::selected($background_dotted_overlay, 'twoxtwowhite') ?> value="twoxtwowhite"><?php $modules->l("2 x 2 White"); ?></option>
                                    <option <?php Rbthemeslider::selected($background_dotted_overlay, 'threexthree') ?> value="threexthree"><?php $modules->l("3 x 3 Black"); ?></option>
                                    <option <?php Rbthemeslider::selected($background_dotted_overlay, 'threexthreewhite') ?> value="threexthreewhite"><?php $modules->l("3 x 3 White"); ?></option>
                                </select>
                                <div class="clear"></div>

                                <h4><?php $modules->l("Slider Background"); ?></h4>
                                <span class="label" id="label_background_color" origmedia="showbg" origtitle="<?php $modules->l("General background color for slider. Clear value to get transparent slider container."); ?>"><?php $modules->l("Background color"); ?> </span>
                                <input type="text"  class="my-color-field rs-layer-input-field tipsy$modules->lnabled_top withlabel inputColorPicker" title="<?php $modules->l("Font Color"); ?>"  id="background_color" name="background_color" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'background_color', 'transparent'); ?>" />
                                <div class="clear"></div>

                                <span class="label" id="label_padding"  origmedia="showbg" origtitle="<?php $modules->l("Padding around the slider. Together with background color shows as slider border."); ?>"><?php $modules->l("Padding as Border"); ?> </span>
                                <input type="text" class="text-sidebar withlabel"  id="padding" name="padding" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'padding', '0'); ?>">
                                <div class="clear"></div>

                                <span class="label" id="label_show_background_image"  origmedia="showbg" origtitle="<?php $modules->l("Use a general background image instead of general background color."); ?>"><?php $modules->l("Show Background Image"); ?> </span>
                                <input type="checkbox"  class="tp-moderncheckbox withlabel" id="show_background_image" name="show_background_image" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'show_background_image', 'off'), "on"); ?> >
                                <div class="clear"></div>

                                <div id="background_settings" class="withsublabels">
                                    <span class="label" id="label_background_image" origmedia="showbg" origtitle="<?php $modules->l("The source of the general background image."); ?>"><?php $modules->l("Background Image Url"); ?> </span>
                                    <input type="text" class="text-sidebar-long withlabel" style="width: 104px;" id="background_image" name="background_image" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'background_image', ''); ?>"> <a href="javascript:void(0)" class="button-image-select-bg-img button-primary rbblue"><?php $modules->l('Set'); ?></a>
                                    <div class="clear"></div>

                                    <span class="label" id="label_bg_fit" origmedia="showbg" origtitle="<?php $modules->l("General background image size. Cover - always fill the container, cuts overlays. Contain- always fits image into slider."); ?>"><?php $modules->l("Background Fit"); ?> </span>
                                    <?php $bg_fit = UniteFunctionsRb::getVal($arrFieldsParams, 'bg_fit', 'cover');?>
                                    <select id="bg_fit"  name="bg_fit" class="withlabel">
                                        <option <?php Rbthemeslider::selected($bg_fit, 'cover') ?> value="cover"><?php $modules->l("cover"); ?></option>
                                        <option <?php Rbthemeslider::selected($bg_fit, 'contain') ?> value="contain"><?php $modules->l("contain"); ?></option>
                                        <option <?php Rbthemeslider::selected($bg_fit, 'normal') ?> value="normal"><?php $modules->l("normal"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <span class="label" id="label_bg_repeat" origmedia="showbg" origtitle="<?php $modules->l("General background image repeat attitude. Used for tiled images."); ?>"><?php $modules->l("Background Repeat"); ?> </span>
                                    <?php $bg_repeat = UniteFunctionsRb::getVal($arrFieldsParams, 'bg_repeat', 'cover');?>
                                    <select id="bg_repeat" name="bg_repeat" class="withlabel">
                                        <option <?php Rbthemeslider::selected($bg_repeat, 'no-repeat') ?> value="no-repeat"><?php $modules->l("no-repeat"); ?></option>
                                        <option <?php Rbthemeslider::selected($bg_repeat, 'repeat') ?> value="repeat"><?php $modules->l("repeat"); ?></option>
                                        <option <?php Rbthemeslider::selected($bg_repeat, 'repeat-x') ?> value="repeat-x"><?php $modules->l("repeat-x"); ?></option>
                                        <option <?php Rbthemeslider::selected($bg_repeat, 'repeat-y') ?> value="repeat-y"><?php $modules->l("repeat-y"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <span class="label" id="label_bg_position" origmedia="showbg" origtitle="<?php $modules->l("General background image position.  i.e. center center to always center vertical and horizontal the image in the slider background."); ?>"><?php $modules->l("Background Position"); ?> </span>
                                    <?php $bgPosition = UniteFunctionsRb::getVal($arrFieldsParams, 'bg_position', 'center center');?>
                                    <select id="bg_position" name="bg_position" class="withlabel">
                                        
                                        <option value="center top" <?php Rbthemeslider::selected($bgPosition, 'center top') ?>>center top</option>
                                        <option value="center right" <?php Rbthemeslider::selected($bgPosition, 'center right') ?>>center right</option>
                                        <option value="center bottom" <?php Rbthemeslider::selected($bgPosition, 'center bottom') ?>>center bottom</option>
                                        <option value="center center" <?php Rbthemeslider::selected($bgPosition, 'center center') ?>>center center</option>
                                        <option value="left top" <?php Rbthemeslider::selected($bgPosition, 'left top') ?>>left top</option>
                                        <option value="left center" <?php Rbthemeslider::selected($bgPosition, 'left center') ?>>left center</option>
                                        <option value="left bottom" <?php Rbthemeslider::selected($bgPosition, 'left bottom') ?>>left bottom</option>
                                        <option value="right top" <?php Rbthemeslider::selected($bgPosition, 'right top') ?>>right top</option>
                                        <option value="right center" <?php Rbthemeslider::selected($bgPosition, 'right center') ?>>right center</option>
                                        <option value="right bottom" <?php Rbthemeslider::selected($bgPosition, 'right bottom') ?>>right bottom</option>
                                        
                                    </select>
                                    <div class="clear"></div>
                                </div>
                            </div>	<!-- / VISUAL APPEARANCE -->



                            <!-- VISUAL POSITION -->
                            <div id="visual-position" style="display:none;">
                                <span class="label" id="label_position" origtitle="<?php $modules->l("The position of the slider within the parrent container. (float:left or float:right or with margin:0px auto;). We recomment do use always CENTER, since the slider will auto fill and grow with the wrapping container. Set any border,padding, floating etc. to the wrapping container where the slider embeded instead of using left/right here !"); ?>"><?php $modules->l("Position on the page"); ?> </span>
                                <?php $position = UniteFunctionsRb::getVal($arrFieldsParams, 'position', 'left');?>
                                <select id="position" class="withlabel"  name="position">
                                    <option <?php Rbthemeslider::selected($position, 'left') ?> value="left"><?php $modules->l("Left"); ?></option>
                                    <option <?php Rbthemeslider::selected($position, 'center') ?> value="center"><?php $modules->l("Center"); ?></option>
                                    <option <?php Rbthemeslider::selected($position, 'right') ?> value="right"><?php $modules->l("Right"); ?></option>
                                </select>
                                <div class="clear"></div>


                                <span class="label" id="label_margin_top" origtitle="<?php $modules->l("The top margin of the slider wrapper div"); ?>"><?php $modules->l("Margin Top"); ?> </span>
                                <input type="text" class="text-sidebar withlabel"   id="margin_top" name="margin_top" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'margin_top', '0'); ?>">
                                <span><?php $modules->l("px"); ?></span>
                                <div class="clear"></div>


                                <span class="label" id="label_margin_bottom" origtitle="<?php $modules->l("The bottom margin of the slider wrapper div"); ?>"><?php $modules->l("Margin Bottom"); ?> </span>
                                <input type="text" class="text-sidebar withlabel" id="margin_bottom" name="margin_bottom" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'margin_bottom', '0'); ?>">
                                <span><?php $modules->l("px"); ?></span>
                                <div class="clear"></div>

                                <div id="leftrightmargins">
                                    <span class="label" id="label_margin_left" origtitle="<?php $modules->l("The left margin of the slider wrapper div"); ?>"><?php $modules->l("Margin Left"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel" id="margin_left" name="margin_left" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'margin_left', '0'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>

                                    <span class="label" id="label_margin_right" origtitle="<?php $modules->l("The right margin of the slider wrapper div"); ?>"><?php $modules->l("Margin Right"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel"  id="margin_right" name="margin_right" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, 'margin_right', '0'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>
                                </div>
                            </div> <!-- / VISUAL POSITION -->

                            <!-- VISUAL SPINNER -->
                            <div id="visual-spinner"  style="display:none;">
                                <div id="spinner_preview"><div class="tp-loader tp-demo spinner2" style="background-color: rgb(255, 255, 255);"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>
                                <div style="height:15px;width:100%;"></div>
                                <span class="label" id="label_use_spinner" origtitle="<?php $modules->l("Select a Spinner for your Slider"); ?>"><?php $modules->l("Choose Spinner"); ?> </span>
                                <?php $use_spinner = UniteFunctionsRb::getVal($arrFieldsParams, 'use_spinner', '-1');?>
                                <select id="use_spinner" name="use_spinner" class="withlabel">
                                    <option <?php Rbthemeslider::selected($use_spinner, '-1') ?> value="-1"><?php $modules->l("Off"); ?></option>
                                    <option <?php Rbthemeslider::selected($use_spinner, '0') ?> value="0"><?php $modules->l("0"); ?></option>
                                    <option <?php Rbthemeslider::selected($use_spinner, '1') ?> value="1"><?php $modules->l("1"); ?></option>
                                    <option <?php Rbthemeslider::selected($use_spinner, '2') ?> value="2"><?php $modules->l("2"); ?></option>
                                    <option <?php Rbthemeslider::selected($use_spinner, '3') ?> value="3"><?php $modules->l("3"); ?></option>
                                    <option <?php Rbthemeslider::selected($use_spinner, '4') ?> value="4"><?php $modules->l("4"); ?></option>
                                    <option <?php Rbthemeslider::selected($use_spinner, '5') ?> value="5"><?php $modules->l("5"); ?></option>
                                </select>
                                <div class="clear"></div>
                                <div id="spinner_color_row">
                                    <span id="label_spinner_color" class="label" origtitle="<?php $modules->l("The Color the Spinner will be shown in"); ?>"><?php $modules->l("Spinner Color"); ?> </span>
                                    <input type="text" class="my-color-field rs-layer-input-field tipsy$modules->lnabled_top withlabel inputColorPicker" title="<?php $modules->l("Font Color"); ?>"  id="spinner_color" name="spinner_color" value="<?php echo UniteFunctionsRb::getVal($arrFieldsParams, "spinner_color", "#FFFFFF"); ?>" />
                                </div>
                                <div class="clear"></div>
                            </div>	<!-- / VISUAL SPINNER -->

                        </div>

                        <script type="text/javascript">
                            jQuery(document).on("ready", function() {
                                /**
                                 * set shadow type
                                 */
                                // SHADOW TYPES
                                jQuery("#shadow_type").change(function() {
                                    var sel = jQuery(this).val();

                                    drawToolBarPreview();
                                });

                                // BACKGROUND IMAGE SCRIPT
                                jQuery('#show_background_image').on("change", function() {
                                    var sbi = jQuery(this);
                                    if (sbi.attr("checked") === "checked") {
                                        jQuery('#background_settings').show();
                                    } else {
                                        jQuery('#background_settings').hide();
                                    }
                                });
                                jQuery('#show_background_image').change();
                                jQuery('#padding').change(drawToolBarPreview).on("keyup", drawToolBarPreview);
                                jQuery('#background_dotted_overlay').change(drawToolBarPreview);

                                // POSITION SCRIPT
                                jQuery('#position').on("change", function() {
                                    var sbi = jQuery(this);
                                    switch (jQuery(this).val()) {
                                        case "left":
                                        case "right":
                                            jQuery('#leftrightmargins').show();
                                            break;
                                        case "center":
                                            jQuery('#leftrightmargins').hide();
                                            break;
                                    }
                                    drawToolBarPreview();
                                });
                                jQuery('#position').change();

                                // SPINNER SCRIPT
                                jQuery('#use_spinner').on("change", function() {
                                    switch (jQuery(this).val()) {
                                        case "-1":
                                        case "0":
                                        case "5":
                                            jQuery('#spinner_color_row').hide();
                                            break;
                                        default:
                                            jQuery('#spinner_color_row').show();
                                            break;
                                    }
                                });
                                jQuery('#use_spinner').change();

                                // TAB CHANGES
                                jQuery('.main-options-small-tabs').find('li').click(function() {
                                    var li = jQuery(this),
                                            ul = li.closest('.main-options-small-tabs'),
                                            ref = li.data('content');

                                    jQuery(ul.find('.selected').data('content')).hide();
                                    ul.find('.selected').removeClass("selected");

                                    jQuery(ref).show();
                                    li.addClass("selected");

                                    if (ref == '#navigation-arrows' || ref == '#navigation-bullets' || ref == '#navigation-tabs' || ref == '#navigation-thumbnails')
                                        jQuery('#navigation-miniimagedimensions').show();
                                    else
                                    if (!jQuery('#navigation-settings-wrapper>h3').hasClass("box_closed"))
                                        jQuery('#navigation-miniimagedimensions').hide();
                                })
                            });
                        </script>
                    </div> <!-- END OF LAYOUT VISUAL SETTINGS -->

                    <!-- NAVIGATION SETTINGS -->
                    <div class="setting_box dontshowonhero" id="navigation-settings-wrapper">
                        <h3 class="box_closed"><i class="rs-rp-accordion-icon eg-icon-flickr"></i>
                            <div class="setting_box-arrow"></div>
                            <span><?php $modules->l('Navigation'); ?></span>
                        </h3>

                        <div class="inside" style="display:none;">
                            <ul class="main-options-small-tabs" style="display:inline-block; ">
                                <li data-content="#navigation-arrows" class="selected"><?php $modules->l('Arrows'); ?></li>
                                <li data-content="#navigation-bullets"><?php $modules->l('Bullets'); ?></li>
                                <li data-content="#navigation-tabs"><?php $modules->l('Tabs'); ?></li>
                                <li data-content="#navigation-thumbnails"><?php $modules->l('Thumbs'); ?></li>
                                <li data-content="#navigation-touch"><?php $modules->l('Touch'); ?></li>
                                <li data-content="#navigation-keyboard"><?php $modules->l('Misc.'); ?></li>
                            </ul>

                            <!-- NAVIGATION ARROWS -->
                            <div id="navigation-arrows">
                                <span class="label" id="label$modules->lnable_arrows"  origtitle="<?php $modules->l("Enable / Disable Arrows"); ?>"><?php $modules->l("Enable Arrows"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="enable_arrows" name="enable_arrows" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "enable_arrows", "off"), "on"); ?>>

                                <div id="nav_arrows_subs">

                                    <span class="label triggernavstyle" id="label_navigation_arrow_style" origtitle="<?php $modules->l("Look of the navigation Arrows"); ?>"><?php $modules->l("Arrows Style"); ?></span>
                                    <select id="navigation_arrow_style" name="navigation_arrow_style" class=" withlabel triggernavstyle">
                                        <option value="" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'navigation_arrow_style', 'round'), ''); ?>><?php $modules->l('No Style'); ?></option>
<?php
if (!empty($arr_navigations)) {
    foreach ($arr_navigations as $cur_nav) {
        if (@Rbthemeslider::getIsset($cur_nav['markup']['arrows'])) {

            ?>
                                                    <option value="<?php echo $cur_nav['handle'];

            ?>" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'navigation_arrow_style', 'round'), $cur_nav['handle']);

            ?>><?php echo $cur_nav['name'];

            ?></option>
            <?php
        }
    }
}

?>
                                    </select>
                                    <div class="clear"></div>

                                    <h4><?php $modules->l("Visibility"); ?></h4>
                                    <span class="label" id="label_arrows_always_on" origtitle="<?php $modules->l("Enable to make arrows always visible. Disable to hide arrows after the defined time."); ?>"><?php $modules->l("Always Show "); ?></span>
                                    <select id="arrows_always_on" name="arrows_always_on" class=" withlabel showhidewhat_truefalse" data-showhidetarget="hide_after_arrow">
                                        <option value="false" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'arrows_always_on', 'false'), "false"); ?>><?php $modules->l("Yes"); ?></option>
                                        <option value="true" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'arrows_always_on', 'false'), "true"); ?>><?php $modules->l("No"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <div id="hide_after_arrow">
                                        <span class="label" id="label_hide_arrows" origtitle="<?php $modules->l("Time after the Arrows will be hidden(Default: 200 ms)"); ?>"><?php $modules->l("Hide After"); ?></span>
                                        <input type="text" class="text-sidebar withlabel" id="hide_arrows" name="hide_arrows" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'hide_arrows', '200'); ?>">
                                        <span><?php $modules->l("ms"); ?></span>
                                        <div class="clear"></div>

                                        <span class="label" id="label_hide_arrows_mobile" origtitle="<?php $modules->l("Time after the Arrows will be hidden on Mobile(Default: 1200 ms)"); ?>"><?php $modules->l("Hide After on Mobile"); ?></span>
                                        <input type="text" class="text-sidebar withlabel" id="hide_arrows_mobile" name="hide_arrows_mobile" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'hide_arrows_mobile', '1200'); ?>">
                                        <span><?php $modules->l("ms"); ?></span>
                                        <div class="clear"></div>
                                    </div>

                                    <span class="label" id="label_hide_arrows_on_mobile"  origtitle="<?php $modules->l("Force Hide Navigation Arrows under width"); ?>"><?php $modules->l("Hide Under"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel showhidewhat_truefalse" data-showhidetarget="hide_under_arrow" id="hide_arrows_on_mobile" name="hide_arrows_on_mobile" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "hide_arrows_on_mobile", "off"), "on"); ?>>
                                    <div class="clear"></div>

                                    <div id="hide_under_arrow" class="withsublabels">
                                        <span id="label_arrows_under_hidden" class="label" origtitle="<?php $modules->l("If browser size goes below this value, then Navigation Arrows are hidden."); ?>"><?php $modules->l("Width"); ?> </span>
                                        <input type="text" class="text-sidebar withlabel" id="arrows_under_hidden" name="arrows_under_hidden" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'arrows_under_hidden', '0'); ?>">
                                        <span><?php $modules->l("px"); ?></span>
                                        <div class="clear"></div>
                                    </div>

                                    <span class="label" id="label_hide_arrows_over"  origtitle="<?php $modules->l("Force Hide Navigation over width"); ?>"><?php $modules->l("Hide Over"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel showhidewhat_truefalse" data-showhidetarget="hide_over_arrow" id="hide_arrows_over" name="hide_arrows_over" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "hide_arrows_over", "off"), "on"); ?>>
                                    <div class="clear"></div>

                                    <div id="hide_over_arrow" class="withsublabels">
                                        <span id="label_arrows_over_hidden" class="label" origtitle="<?php $modules->l("If browser size goes over this value, then Navigation Arrows are hidden."); ?>"><?php $modules->l("Width"); ?> </span>
                                        <input type="text" class="text-sidebar withlabel" id="arrows_over_hidden" name="arrows_over_hidden" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'arrows_over_hidden', '0'); ?>">
                                        <span><?php $modules->l("px"); ?></span>
                                        <div class="clear"></div>
                                    </div>

                                    <h4><?php $modules->l("Left Arrow Position"); ?></h4>

                                    <span class="label" id="label_leftarrow_align_hor" origtitle="<?php $modules->l("Horizontal position of the left arrow."); ?>"><?php $modules->l("Horizontal Align"); ?></span>
                                    <select id="leftarrow_align_hor" name="leftarrow_align_hor" class="withlabel">
                                        <option value="left" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "leftarrow_align_hor", "left"), "left"); ?>><?php $modules->l("Left"); ?></option>
                                        <option value="center" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "leftarrow_align_hor", "left"), "center"); ?>><?php $modules->l("Center"); ?></option>
                                        <option value="right" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "leftarrow_align_hor", "left"), "right"); ?>><?php $modules->l("Right"); ?></option>
                                    </select>
                                    <div class="clear"></div>


                                    <span class="label" id="label_leftarrow_align_vert" origtitle="<?php $modules->l("Vertical position of the left arrow."); ?>"><?php $modules->l("Vertical Align"); ?> </span>
                                    <select id="leftarrow_align_vert" name="leftarrow_align_vert" class="withlabel">
                                        <option value="top" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "leftarrow_align_vert", "center"), "top"); ?>><?php $modules->l("Top"); ?></option>
                                        <option value="center" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "leftarrow_align_vert", "center"), "center"); ?>><?php $modules->l("Center"); ?></option>
                                        <option value="bottom" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "leftarrow_align_vert", "center"), "bottom"); ?>><?php $modules->l("Bottom"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <span id="label_leftarrow_offset_hor" class="label" origtitle="<?php $modules->l("Offset from current horizontal position of of left arrow."); ?>"><?php $modules->l("Horizontal Offset"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel" id="leftarrow_offset_hor" name="leftarrow_offset_hor" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'leftarrow_offset_hor', '20'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>


                                    <span id="label_leftarrow_offset_vert" class="label" origtitle="<?php $modules->l("Offset from current vertical position of of left arrow."); ?>"><?php $modules->l("Vertical Offset"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel" id="leftarrow_offset_vert" name="leftarrow_offset_vert" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "leftarrow_offset_vert", "0"); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>

                                    <h4><?php $modules->l("Right Arrow Position"); ?></h4>
                                    <span class="label" id="label_rightarrow_align_hor" origtitle="<?php $modules->l("Horizontal position of the right arrow."); ?>"><?php $modules->l("Horizontal Align"); ?> </span>
                                    <select id="rightarrow_align_hor" name="rightarrow_align_hor" class="withlabel">
                                        <option value="left" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "rightarrow_align_hor", "right"), "left"); ?>><?php $modules->l("Left"); ?></option>
                                        <option value="center" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "rightarrow_align_hor", "right"), "center"); ?>><?php $modules->l("Center"); ?></option>
                                        <option value="right" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "rightarrow_align_hor", "right"), "right"); ?>><?php $modules->l("Right"); ?></option>
                                    </select>

                                    <div class="clear"></div>


                                    <span id="label_rightarrow_align_vert" class="label" origtitle="<?php $modules->l("Vertical position of the right arrow."); ?>"><?php $modules->l("Vertical Align"); ?> </span>
                                    <select id="rightarrow_align_vert" name="rightarrow_align_vert" class="withlabel">
                                        <option value="top" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "rightarrow_align_vert", "center"), "top"); ?>><?php $modules->l("Top"); ?></option>
                                        <option value="center" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "rightarrow_align_vert", "center"), "center"); ?>><?php $modules->l("Center"); ?></option>
                                        <option value="bottom" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "rightarrow_align_vert", "center"), "bottom"); ?>><?php $modules->l("Bottom"); ?></option>
                                    </select>

                                    <div class="clear"></div>


                                    <span id="label_rightarrow_offset_hor" class="label" origtitle="<?php $modules->l("Offset from current horizontal position of of right arrow."); ?>"><?php $modules->l("Horizontal Offset"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel" id="rightarrow_offset_hor" name="rightarrow_offset_hor" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "rightarrow_offset_hor", "20"); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>


                                    <span id="label_rightarrow_offset_vert" class="label" origtitle="<?php $modules->l("Offset from current vertical position of of right arrow."); ?>"><?php $modules->l("Vertical Offset"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="rightarrow_offset_vert" name="rightarrow_offset_vert" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "rightarrow_offset_vert", "0"); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>
                                </div>
                            </div><!-- END OF NAVIGATION ARROWS -->

                            <!-- NAVIGATION BULLETS -->
                            <div id="navigation-bullets" style="display:none;">

                                <span class="label" id="label$modules->lnable_bullets"  origtitle="<?php $modules->l("Enable / Disable Bullets"); ?>"><?php $modules->l("Enable Bullets"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="enable_bullets" name="enable_bullets" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "enable_bullets", "off"), "on"); ?>>

                                <div id="nav_bullets_subs">

                                    <span class="label triggernavstyle" id="label_navigation_bullets_style" origtitle="<?php $modules->l("Look of the Bullets"); ?>"><?php $modules->l("Bullet Style"); ?></span>
                                    <select id="navigation_bullets_style" name="navigation_bullets_style" class="triggernavstyle withlabel">
                                        <option value="" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'navigation_bullets_style', 'round'), ''); ?>><?php $modules->l('No Style'); ?></option>
<?php
if (!empty($arr_navigations)) {
    foreach ($arr_navigations as $cur_nav) {
        if (@Rbthemeslider::getIsset($cur_nav['markup']['bullets'])) {

            ?>
                                                    <option value="<?php echo $cur_nav['handle'];

            ?>" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'navigation_bullets_style', 'round'), $cur_nav['handle']);

            ?>><?php echo $cur_nav['name'];

            ?></option>
            <?php
        }
    }
}

?>
                                    </select>
                                    <div class="clear"></div>

                                    <span class="label" id="label_bullets_space" origtitle="<?php $modules->l("Space between the bullets."); ?>"><?php $modules->l("Space"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="bullets_space" name="bullets_space" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'bullets_space', '5'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>


                                    <span class="label" id="label_bullets_direction" origtitle="<?php $modules->l("Direction of the Bullets. Vertical or Horizontal."); ?>"><?php $modules->l("Direction"); ?></span>
                                    <select id="bullets_direction" name="bullets_direction" class=" withlabel">
                                        <option value="horizontal" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'bullets_direction', 'horizontal'), "horizontal"); ?>><?php $modules->l("Horizontal"); ?></option>
                                        <option value="vertical" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'bullets_direction', 'horizontal'), "vertical"); ?>><?php $modules->l("Vertical"); ?></option>
                                    </select>
                                    <div class="clear"></div>


                                    <h4><?php $modules->l("Visibility"); ?></h4>

                                    <span class="label" id="label_bullets_always_on" origtitle="<?php $modules->l("Enable to make bullets always visible. Disable to hide bullets after the defined time."); ?>"><?php $modules->l("Always Show"); ?></span>
                                    <select id="bullets_always_on" name="bullets_always_on" class=" withlabel showhidewhat_truefalse" data-showhidetarget="hide_after_bullets">
                                        <option value="false" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'bullets_always_on', 'false'), "false"); ?>><?php $modules->l("Yes"); ?></option>
                                        <option value="true" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'bullets_always_on', 'false'), "true"); ?>><?php $modules->l("No"); ?></option>
                                    </select>
                                    <div class="clear"></div>
                                    <div id="hide_after_bullets">
                                        <span class="label" id="label_hide_bullets" origtitle="<?php $modules->l("Time after that the bullets will be hidden(Default: 200 ms)"); ?>"><?php $modules->l("Hide After"); ?></span>
                                        <input type="text" class="text-sidebar withlabel" id="hide_bullets" name="hide_bullets" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'hide_bullets', '200'); ?>">
                                        <span><?php $modules->l("ms"); ?></span>
                                        <div class="clear"></div>

                                        <span class="label" id="label_hide_bullets_mobile" origtitle="<?php $modules->l("Time after the bullets will be hidden on Mobile (Default: 1200 ms)"); ?>"><?php $modules->l("Hide After on Mobile"); ?></span>
                                        <input type="text" class="text-sidebar withlabel" id="hide_bullets_mobile" name="hide_bullets_mobile" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'hide_bullets_mobile', '1200'); ?>">
                                        <span><?php $modules->l("ms"); ?></span>
                                        <div class="clear"></div>
                                    </div>

                                    <span class="label" id="label_hide_bullets_on_mobile"  origtitle="<?php $modules->l("Force Hide Navigation Bullets under width"); ?>"><?php $modules->l("Hide under Width"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel showhidewhat_truefalse" data-showhidetarget="hide_under_bullet" id="hide_bullets_on_mobile" name="hide_bullets_on_mobile" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "hide_bullets_on_mobile", "off"), "on"); ?>>
                                    <div class="clear"></div>

                                    <div id="hide_under_bullet" class="withsublabels">
                                        <span id="label_bullets_under_hidden" class="label" origtitle="<?php $modules->l("If browser size goes below this value, then Navigation bullets are hidden."); ?>"><?php $modules->l("Width"); ?> </span>
                                        <input type="text" class="text-sidebar withlabel" id="bullets_under_hidden" name="bullets_under_hidden" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'bullets_under_hidden', '0'); ?>">
                                        <span><?php $modules->l("px"); ?></span>
                                        <div class="clear"></div>
                                    </div>

                                    <span class="label" id="label_hide_bullets_over"  origtitle="<?php $modules->l("Force Hide Navigation Bullets over width"); ?>"><?php $modules->l("Hide over Width"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel showhidewhat_truefalse" data-showhidetarget="hide_over_bullet" id="hide_bullets_over" name="hide_bullets_over" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "hide_bullets_over", "off"), "on"); ?>>
                                    <div class="clear"></div>

                                    <div id="hide_over_bullet" class="withsublabels">
                                        <span id="label_bullets_over_hidden" class="label" origtitle="<?php $modules->l("If browser size goes below this value, then Navigation bullets are hidden."); ?>"><?php $modules->l("Width"); ?> </span>
                                        <input type="text" class="text-sidebar withlabel" id="bullets_over_hidden" name="bullets_over_hidden" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'bullets_over_hidden', '0'); ?>">
                                        <span><?php $modules->l("px"); ?></span>
                                        <div class="clear"></div>
                                    </div>

                                    <h4><?php $modules->l("Position"); ?></h4>
                                    <span class="label" id="label_bullets_align_hor" origtitle="<?php $modules->l("Horizontal position of bullets "); ?>"><?php $modules->l("Horizontal Align"); ?></span>
                                    <select id="bullets_align_hor" name="bullets_align_hor" class=" withlabel">
                                        <option value="left" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'bullets_align_hor', 'center'), "left"); ?>><?php $modules->l("Left"); ?></option>
                                        <option value="center" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'bullets_align_hor', 'center'), "center"); ?>><?php $modules->l("Center"); ?></option>
                                        <option value="right" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'bullets_align_hor', 'center'), "right"); ?>><?php $modules->l("Right"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <span class="label" id="label_bullets_align_vert" origtitle="<?php $modules->l("Vertical positions of bullets "); ?>"><?php $modules->l("Vertical Align"); ?></span>
                                    <select id="bullets_align_vert" name="bullets_align_vert" class="withlabel">
                                        <option value="top" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'bullets_align_vert', 'bottom'), "top"); ?>><?php $modules->l("Top"); ?></option>
                                        <option value="center" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'bullets_align_vert', 'bottom'), "center"); ?>><?php $modules->l("Center"); ?></option>
                                        <option value="bottom" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'bullets_align_vert', 'bottom'), "bottom"); ?>><?php $modules->l("Bottom"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <span class="label" id="label_bullets_offset_hor" origtitle="<?php $modules->l("Offset from current horizontal position."); ?>"><?php $modules->l("Horizontal Offset"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="bullets_offset_hor" name="bullets_offset_hor" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'bullets_offset_hor', '0'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>


                                    <span class="label" id="label_bullets_offset_vert" origtitle="<?php $modules->l("Offset from current Vertical  position."); ?>"><?php $modules->l("Vertical Offset"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="bullets_offset_vert" name="bullets_offset_vert" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'bullets_offset_vert', '20'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>
                                </div>
                            </div><!-- END OF NAVIGATION BULLETS -->


                            <!-- NAVIGATION THUMBNAILS -->
                            <div id="navigation-thumbnails" style="display:none;">
                                <span class="label" id="label$modules->lnable_thumbnails"  origtitle="<?php $modules->l("Enable / Disable Thumbnails"); ?>"><?php $modules->l("Enable Thumbnails"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="enable_thumbnails" name="enable_thumbnails" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "enable_thumbnails", "off"), "on"); ?>>

                                <div id="nav_thumbnails_subs">



                                    <h4><?php $modules->l("Wrapper Container"); ?></h4>

                                    <span class="label" id="label_thumbnails_padding"  origtitle="<?php $modules->l("The wrapper div padding of thumbnails"); ?>"><?php $modules->l("Wrapper Padding"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel"  id="thumbnails_padding" name="thumbnails_padding" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_padding', '5'); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_span_thumbnails_wrapper"  origtitle="<?php $modules->l("Span wrapper to full width or full height based on the direction selected"); ?>"><?php $modules->l("Span Wrapper"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel" id="span_thumbnails_wrapper" name="span_thumbnails_wrapper" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "span_thumbnails_wrapper", "off"), "on"); ?>>


                                    <span class="label" id="label_thumbnails_wrapper_color"  origtitle="<?php $modules->l("Thumbnails wrapper background color. For transparent leave empty."); ?>"><?php $modules->l("Wrapper color"); ?> </span>
                                    <input type="text"  class="my-color-field rs-layer-input-field tipsy$modules->lnabled_top withlabel inputColorPicker" title="<?php $modules->l("Wrapper Color"); ?>"  id="thumbnails_wrapper_color" name="thumbnails_wrapper_color" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_wrapper_color', 'transparent'); ?>" />
                                    <div class="clear"></div>

                                    <span class="label" id="label_thumbnails_wrapper_opacity" origtitle="<?php $modules->l("Opacity of the Wrapper container. 0 - transparent, 50 - 50% opacity..."); ?>"><?php $modules->l("Wrapper Opacity"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="thumbnails_wrapper_opacity" name="thumbnails_wrapper_opacity" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_wrapper_opacity', '100'); ?>">
                                    <div class="clear"></div>


                                    <h4><?php $modules->l("Thumbnails"); ?></h4>

                                    <span class="label triggernavstyle" id="label_thumbnails_style" origtitle="<?php $modules->l("Style of the thumbnails."); ?>"><?php $modules->l("Thumbnails Style"); ?></span>
                                    <select id="thumbnails_style" name="thumbnails_style" class="triggernavstyle withlabel">
                                        <option value="" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_style', 'round'), ''); ?>><?php $modules->l('No Style'); ?></option>
<?php
if (!empty($arr_navigations)) {
    foreach ($arr_navigations as $cur_nav) {
        if (@Rbthemeslider::getIsset($cur_nav['markup']['thumbs'])) {

            ?>
                                                    <option value="<?php echo $cur_nav['handle'];

            ?>" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_style', 'round'), $cur_nav['handle']);

            ?>><?php echo $cur_nav['name'];

            ?></option>
            <?php
        }
    }
}

?>
                                    </select>
                                    <div class="clear"></div>


                                    <span id="label_thumb_amount" class="label"  origtitle="<?php $modules->l("The amount of max visible Thumbnails in the same time. "); ?>"><?php $modules->l("Visible Thumbs Amount"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="thumb_amount" name="thumb_amount" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "thumb_amount", "5"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_thumbnails_space" origtitle="<?php $modules->l("Space between the thumbnails."); ?>"><?php $modules->l("Space"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="thumbnails_space" name="thumbnails_space" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_space', '5'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>

                                    <span class="label" id="label_thumbnail_direction" origtitle="<?php $modules->l("Direction of the Thumbnails. Vertical or Horizontal."); ?>"><?php $modules->l("Direction"); ?></span>
                                    <select id="thumbnail_direction" name="thumbnail_direction" class=" withlabel">
                                        <option value="horizontal" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnail_direction', 'horizontal'), "horizontal"); ?>><?php $modules->l("Horizontal"); ?></option>
                                        <option value="vertical" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnail_direction', 'horizontal'), "vertical"); ?>><?php $modules->l("Vertical"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <h4><?php $modules->l("Thumbnail Container Size"); ?></h4>

                                    <span id="label_thumb_width" class="label"  origtitle="<?php $modules->l("The basic Width of one Thumbnail Container."); ?>"><?php $modules->l("Container Width"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="thumb_width" name="thumb_width" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "thumb_width", "100"); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>

                                    <span id="label_thumb_height" class="label"  origtitle="<?php $modules->l("The basic Height of one Thumbnail."); ?>"><?php $modules->l("Container Height"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="thumb_height" name="thumb_height" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "thumb_height", "50"); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>

                                    <span id="label_thumb_width_min" class="label"  origtitle="<?php $modules->l("The minimum width of the auto resized thumbs. Between Max and Min width the sizes are auto calculated)."); ?>"><?php $modules->l("Min Container Width"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="thumb_width_min" name="thumb_width_min" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "thumb_width_min", "100"); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>



                                    <h4><?php $modules->l("Visibility"); ?></h4>

                                    <span class="label" id="label_thumbs_always_on" origtitle="<?php $modules->l("Enable to make thumbnails always visible. Disable to hide thumbnails after the defined time."); ?>"><?php $modules->l("Always Show "); ?></span>
                                    <select id="thumbs_always_on" name="thumbs_always_on" class=" withlabel showhidewhat_truefalse" data-showhidetarget="hide_after_thumbs">
                                        <option value="false" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbs_always_on', 'false'), "false"); ?>><?php $modules->l("Yes"); ?></option>
                                        <option value="true" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbs_always_on', 'false'), "true"); ?>><?php $modules->l("No"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <div id="hide_after_thumbs">
                                        <span class="label" id="label_hide_thumbs" origtitle="<?php $modules->l("Time after that the thumbnails will be hidden(Default: 200 ms)"); ?>"><?php $modules->l("Hide After"); ?></span>
                                        <input type="text" class="text-sidebar withlabel" id="hide_thumbs" name="hide_thumbs" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'hide_thumbs', '200'); ?>">
                                        <span><?php $modules->l("ms"); ?></span>
                                        <div class="clear"></div>

                                        <span class="label" id="label_hide_thumbs_mobile" origtitle="<?php $modules->l("Time after that the thumbnails will be hidden on Mobile (Default: 1200 ms)"); ?>"><?php $modules->l("Hide After on Mobile"); ?></span>
                                        <input type="text" class="text-sidebar withlabel" id="hide_thumbs_mobile" name="hide_thumbs_mobile" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'hide_thumbs_mobile', '1200'); ?>">
                                        <span><?php $modules->l("ms"); ?></span>
                                        <div class="clear"></div>
                                    </div>

                                    <span class="label" id="label_hide_thumbs_on_mobile" origtitle="<?php $modules->l("Force Hide Navigation Thumbnails under width"); ?>"><?php $modules->l("Hide under Width"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel showhidewhat_truefalse" data-showhidetarget="hide_under_thumb" id="hide_thumbs_on_mobile" name="hide_thumbs_on_mobile" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "hide_thumbs_on_mobile", "off"), "on"); ?>>
                                    <div class="clear"></div>

                                    <div id="hide_under_thumb" class="withsublabels">
                                        <span id="label_thumbs_under_hidden" class="label" origtitle="<?php $modules->l("If browser size goes below this value, then Navigation thumbs are hidden."); ?>"><?php $modules->l("Width"); ?> </span>
                                        <input type="text" class="text-sidebar withlabel" id="thumbs_under_hidden" name="thumbs_under_hidden" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'thumbs_under_hidden', '0'); ?>">
                                        <span><?php $modules->l("px"); ?></span>
                                        <div class="clear"></div>
                                    </div>

                                    <span class="label" id="label_hide_thumbs_over" origtitle="<?php $modules->l("Force Hide Navigation Thumbnails under width"); ?>"><?php $modules->l("Hide over Width"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel showhidewhat_truefalse" data-showhidetarget="hide_over_thumb" id="hide_thumbs_over" name="hide_thumbs_over" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "hide_thumbs_over", "off"), "on"); ?>>
                                    <div class="clear"></div>

                                    <div id="hide_over_thumb" class="withsublabels">
                                        <span id="label_thumbs_over_hidden" class="label" origtitle="<?php $modules->l("If browser size goes below this value, then Navigation thumbs are hidden."); ?>"><?php $modules->l("Width"); ?> </span>
                                        <input type="text" class="text-sidebar withlabel" id="thumbs_over_hidden" name="thumbs_over_hidden" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'thumbs_over_hidden', '0'); ?>">
                                        <span><?php $modules->l("px"); ?></span>
                                        <div class="clear"></div>
                                    </div>

                                    <h4><?php $modules->l("Position"); ?></h4>
                                    <span class="label" id="label_thumbnails_inner_outer" origtitle="<?php $modules->l("Put the thumbnails inside or outside of the slider container. Outside added thumbnails will decrease the size of the slider."); ?>"><?php $modules->l("Inner / outer"); ?></span>
                                    <select id="thumbnails_inner_outer" name="thumbnails_inner_outer" class=" withlabel">
                                        <option value="inner" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_inner_outer', 'inner'), "inner"); ?>><?php $modules->l("Inner Slider"); ?></option>
                                        <option value="outer-left" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_inner_outer', 'inner'), "outer-left"); ?>><?php $modules->l("Outer Left"); ?></option>
                                        <option value="outer-right" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_inner_outer', 'inner'), "outer-right"); ?>><?php $modules->l("Outer Right"); ?></option>
                                        <option value="outer-top" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_inner_outer', 'inner'), "outer-top"); ?>><?php $modules->l("Outer Top"); ?></option>
                                        <option value="outer-bottom" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_inner_outer', 'inner'), "outer-bottom"); ?>><?php $modules->l("Outer Bottom"); ?></option>

                                    </select>
                                    <div class="clear"></div>



                                    <span class="label" id="label_thumbnails_align_hor" origtitle="<?php $modules->l("Horizontal position of thumbnails"); ?>"><?php $modules->l("Horizontal Align"); ?></span>
                                    <select id="thumbnails_align_hor" name="thumbnails_align_hor" class=" withlabel">
                                        <option value="left" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_align_hor', 'center'), "left"); ?>><?php $modules->l("Left"); ?></option>
                                        <option value="center" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_align_hor', 'center'), "center"); ?>><?php $modules->l("Center"); ?></option>
                                        <option value="right" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_align_hor', 'center'), "right"); ?>><?php $modules->l("Right"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <span class="label" id="label_thumbnails_align_vert" origtitle="<?php $modules->l("Vertical position of thumbnails"); ?>"><?php $modules->l("Vertical Align"); ?></span>
                                    <select id="thumbnails_align_vert" name="thumbnails_align_vert" class="withlabel">
                                        <option value="top" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_align_vert', 'bottom'), "top"); ?>><?php $modules->l("Top"); ?></option>
                                        <option value="center" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_align_vert', 'bottom'), "center"); ?>><?php $modules->l("Center"); ?></option>
                                        <option value="bottom" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_align_vert', 'bottom'), "bottom"); ?>><?php $modules->l("Bottom"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <span class="label" id="label_thumbnails_offset_hor" origtitle="<?php $modules->l("Offset from current Horizontal position."); ?>"><?php $modules->l("Horizontal Offset"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="thumbnails_offset_hor" name="thumbnails_offset_hor" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_offset_hor', '0'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>


                                    <span class="label" id="label_thumbnails_offset_vert" origtitle="<?php $modules->l("Offset from current Vertical position."); ?>"><?php $modules->l("Vertical Offset"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="thumbnails_offset_vert" name="thumbnails_offset_vert" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'thumbnails_offset_vert', '20'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>


                                </div>
                            </div>
                            <!-- END OF NAVIGATION THUMBNAILS -->

                            <!-- NAVIGATION TABS-->
                            <div id="navigation-tabs" style="display:none;">

                                <span class="label" id="label$modules->lnable_tabs"  origtitle="<?php $modules->l("Enable / Disable navigation tabs."); ?>"><?php $modules->l("Enable Tabs"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="enable_tabs" name="enable_tabs" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "enable_tabs", "off"), "on"); ?>>

                                <div id="nav_tabs_subs">



                                    <h4><?php $modules->l("Wrapper Container"); ?></h4>

                                    <span class="label" id="label_tabs_padding"  origtitle="<?php $modules->l("The wrapper div padding of tabs."); ?>"><?php $modules->l("Wrapper Padding"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel"  id="tabs_padding" name="tabs_padding" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'tabs_padding', '5'); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_span_tabs_wrapper"  origtitle="<?php $modules->l("Span wrapper to full width or full height based on the direction selected."); ?>"><?php $modules->l("Span Wrapper"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel" id="span_tabs_wrapper" name="span_tabs_wrapper" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "span_tabs_wrapper", "off"), "on"); ?>>


                                    <span class="label" id="label_tabs_wrapper_color" origtitle="<?php $modules->l("Tabs wrapper background color. For transparent leave empty."); ?>"><?php $modules->l("Wrapper Color"); ?> </span>
                                    <input type="text"  class="my-color-field rs-layer-input-field tipsy$modules->lnabled_top withlabel inputColorPicker" title="<?php $modules->l("Wrapper Color"); ?>"  id="tabs_wrapper_color" name="tabs_wrapper_color" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'tabs_wrapper_color', 'transparent'); ?>" />
                                    <div class="clear"></div>

                                    <span class="label" id="label_tabs_wrapper_opacity" origtitle="<?php $modules->l("Opacity of the Wrapper container. 0 - transparent, 50 - 50% opacity..."); ?>"><?php $modules->l("Wrapper Opacity"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="tabs_wrapper_opacity" name="tabs_wrapper_opacity" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'tabs_wrapper_opacity', '5'); ?>">
                                    <div class="clear"></div>

                                    <h4><?php $modules->l("Tabs"); ?></h4>

                                    <span class="triggernavstyle label" id="label_tabs_style" origtitle="<?php $modules->l("Style of the tabs."); ?>"><?php $modules->l("Tabs Style"); ?></span>
                                    <select id="tabs_style" name="tabs_style" class="triggernavstyle withlabel">
                                        <option value="" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_style', 'round'), ''); ?>><?php $modules->l('No Style'); ?></option>
<?php
if (!empty($arr_navigations)) {
    foreach ($arr_navigations as $cur_nav) {
        if (@Rbthemeslider::getIsset($cur_nav['markup']['tabs'])) {

            ?>
                                                    <option value="<?php echo $cur_nav['handle'];

            ?>" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_style', 'round'), $cur_nav['handle']);

            ?>><?php echo $cur_nav['name'];

            ?></option>
            <?php
        }
    }
}

?>
                                    </select>
                                    <div class="clear"></div>

                                    <span id="label_tabs_amount" class="label"  origtitle="<?php $modules->l("The amount of max visible tabs in same time."); ?>"><?php $modules->l("Visible Tabs Amount"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="tabs_amount" name="tabs_amount" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "tabs_amount", "5"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_tabs_space" origtitle="<?php $modules->l("Space between the tabs."); ?>"><?php $modules->l("Space"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="tabs_space" name="tabs_space" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'tabs_space', '5'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>

                                    <span class="label" id="label_tabs_direction" origtitle="<?php $modules->l("Direction of the Tabs. Vertical or Horizontal."); ?>"><?php $modules->l("Direction"); ?></span>
                                    <select id="tabs_direction" name="tabs_direction" class=" withlabel">
                                        <option value="horizontal" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_direction', 'horizontal'), "horizontal"); ?>><?php $modules->l("Horizontal"); ?></option>
                                        <option value="vertical" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_direction', 'horizontal'), "vertical"); ?>><?php $modules->l("Vertical"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <h4><?php $modules->l("Tab Sizes"); ?></h4>

                                    <span id="label_tabs_width" class="label"  origtitle="<?php $modules->l("The basic width of one tab."); ?>"><?php $modules->l("Tabs Width"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="tabs_width" name="tabs_width" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "tabs_width", "100"); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>

                                    <span id="label_tabs_height" class="label"  origtitle="<?php $modules->l("the basic height of one tab."); ?>"><?php $modules->l("Tabs Height"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="tabs_height" name="tabs_height" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "tabs_height", "50"); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>

                                    <span id="label_tabs_width_min" class="label"  origtitle="<?php $modules->l("The minimum width of the auto resized Tabs. Between Max and Min width the sizes are auto calculated)."); ?>"><?php $modules->l("Min. Tab Width"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="tabs_width_min" name="tabs_width_min" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "tabs_width_min", "100"); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>

                                    <h4><?php $modules->l("Visibility"); ?></h4>

                                    <span class="label" id="label_tabs_always_on" origtitle="<?php $modules->l("Enable to make tabs always visible. Disable to hide tabs after the defined time."); ?>"><?php $modules->l("Always Show "); ?></span>
                                    <select id="tabs_always_on" name="tabs_always_on" class=" withlabel showhidewhat_truefalse" data-showhidetarget="hide_after_tabs">
                                        <option value="false" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_always_on', 'false'), "false"); ?>><?php $modules->l("Yes"); ?></option>
                                        <option value="true" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_always_on', 'false'), "true"); ?>><?php $modules->l("No"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <div id="hide_after_tabs">
                                        <span class="label" id="label_hide_tabs" origtitle="<?php $modules->l("Time after that the tabs will be hidden(Default: 200 ms)"); ?>"><?php $modules->l("Hide  After"); ?></span>
                                        <input type="text" class="text-sidebar withlabel" id="hide_tabs" name="hide_tabs" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'hide_tabs', '200'); ?>">
                                        <span><?php $modules->l("ms"); ?></span>
                                        <div class="clear"></div>
                                        <span class="label" id="label_hide_tabs_mobile" origtitle="<?php $modules->l("Time after that the tabs will be hidden on Mobile (Default: 1200 ms)"); ?>"><?php $modules->l("Hide  After on Mobile"); ?></span>
                                        <input type="text" class="text-sidebar withlabel" id="hide_tabs_mobile" name="hide_tabs_mobile" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'hide_tabs_mobile', '1200'); ?>">
                                        <span><?php $modules->l("ms"); ?></span>
                                        <div class="clear"></div>
                                    </div>

                                    <span class="label" id="label_hide_tabs_on_mobile" origtitle="<?php $modules->l("Force Hide Navigation tabs under width"); ?>"><?php $modules->l("Hide under Width"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel showhidewhat_truefalse" data-showhidetarget="hide_under_tab" id="hide_tabs_on_mobile" name="hide_tabs_on_mobile" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "hide_tabs_on_mobile", "off"), "on"); ?>>
                                    <div class="clear"></div>

                                    <div id="hide_under_tab" class="withsublabels">
                                        <span id="label_tabs_under_hidden" class="label" origtitle="<?php $modules->l("If browser size goes below this value, then Navigation tabs are hidden."); ?>"><?php $modules->l("Width"); ?> </span>
                                        <input type="text" class="text-sidebar withlabel" id="tabs_under_hidden" name="tabs_under_hidden" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'tabs_under_hidden', '0'); ?>">
                                        <span><?php $modules->l("px"); ?></span>
                                        <div class="clear"></div>
                                    </div>

                                    <span class="label" id="label_hide_tabs_over" origtitle="<?php $modules->l("Force Hide Navigation tabs under width"); ?>"><?php $modules->l("Hide over Width"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel showhidewhat_truefalse" data-showhidetarget="hide_over_tab" id="hide_tabs_over" name="hide_tabs_over" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "hide_tabs_over", "off"), "on"); ?>>
                                    <div class="clear"></div>

                                    <div id="hide_over_tab" class="withsublabels">
                                        <span id="label_tabs_over_hidden" class="label" origtitle="<?php $modules->l("If browser size goes below this value, then Navigation tabs are hidden."); ?>"><?php $modules->l("Width"); ?> </span>
                                        <input type="text" class="text-sidebar withlabel" id="tabs_over_hidden" name="tabs_over_hidden" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'tabs_over_hidden', '0'); ?>">
                                        <span><?php $modules->l("px"); ?></span>
                                        <div class="clear"></div>
                                    </div>

                                    <h4><?php $modules->l("Position"); ?></h4>

                                    <span class="label" id="label_tabs_inner_outer" origtitle="<?php $modules->l("Put the tabs inside or outside of the slider container. Outside added tabs will decrease the size of the slider."); ?>"><?php $modules->l("Inner / outer"); ?></span>
                                    <select id="tabs_inner_outer" name="tabs_inner_outer" class=" withlabel">
                                        <option value="inner" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_inner_outer', 'inner'), "inner"); ?>><?php $modules->l("Inner Slider"); ?></option>
                                        <option value="outer-left" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_inner_outer', 'inner'), "outer-left"); ?>><?php $modules->l("Outer Left"); ?></option>
                                        <option value="outer-right" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_inner_outer', 'inner'), "outer-right"); ?>><?php $modules->l("Outer Right"); ?></option>
                                        <option value="outer-top" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_inner_outer', 'inner'), "outer-top"); ?>><?php $modules->l("Outer Top"); ?></option>
                                        <option value="outer-bottom" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_inner_outer', 'inner'), "outer-bottom"); ?>><?php $modules->l("Outer Bottom"); ?></option>

                                    </select>
                                    <div class="clear"></div>


                                    <span class="label" id="label_tabs_align_hor" origtitle="<?php $modules->l("Horizontal position of tabs."); ?>"><?php $modules->l("Horizontal Align"); ?></span>
                                    <select id="tabs_align_hor" name="tabs_align_hor" class=" withlabel">
                                        <option value="left" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_align_hor', 'center'), "left"); ?>><?php $modules->l("Left"); ?></option>
                                        <option value="center" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_align_hor', 'center'), "center"); ?>><?php $modules->l("Center"); ?></option>
                                        <option value="right" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_align_hor', 'center'), "right"); ?>><?php $modules->l("Right"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <span class="label" id="label_tabs_align_vert" origtitle="<?php $modules->l("Vertical position of tabs."); ?>"><?php $modules->l("Vertical Align"); ?></span>
                                    <select id="tabs_align_vert" name="tabs_align_vert" class="withlabel">
                                        <option value="top" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_align_vert', 'bottom'), "top"); ?>><?php $modules->l("Top"); ?></option>
                                        <option value="center" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_align_vert', 'bottom'), "center"); ?>><?php $modules->l("Center"); ?></option>
                                        <option value="bottom" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'tabs_align_vert', 'bottom'), "bottom"); ?>><?php $modules->l("Bottom"); ?></option>
                                    </select>
                                    <div class="clear"></div>

                                    <span class="label" id="label_tabs_offset_hor" origtitle="<?php $modules->l("Offset from current horizontal position of tabs."); ?>"><?php $modules->l("Horizontal Offset"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="tabs_offset_hor" name="tabs_offset_hor" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'tabs_offset_hor', '0'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>


                                    <span class="label" id="label_tabs_offset_vert" origtitle="<?php $modules->l("Offset from current vertical position of tabs."); ?>"><?php $modules->l("Vertical Offset"); ?></span>
                                    <input type="text" class="text-sidebar withlabel" id="tabs_offset_vert" name="tabs_offset_vert" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'tabs_offset_vert', '20'); ?>">
                                    <span><?php $modules->l("px"); ?></span>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <!-- END OF NAVIGATION TABS-->

                            <!-- TOUCH NAVIGATION -->
                            <div id="navigation-touch" style="display:none;">

                                <span class="label" id="label_touchenabled" origtitle="<?php $modules->l("Enable Swipe Function on touch devices"); ?>"><?php $modules->l("Touch Enabled"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="touchenabled" name="touchenabled" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "touchenabled", "off"), "on"); ?>>
                                <div class="clear"></div>

                                <span class="label" id="label_drag_block_vertical" origtitle="<?php $modules->l("Scroll below slider on vertical swipe"); ?>"><?php $modules->l("Drag Block Vertical"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="drag_block_vertical" name="drag_block_vertical" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "drag_block_vertical", "off"), "on"); ?>>
                                <div class="clear"></div>

                                <span class="label" id="label_swipe_velocity" origtitle="<?php $modules->l("Defines the sensibility of gestures. Smaller values mean a higher sensibility"); ?>"><?php $modules->l("Swipe Treshhold (0 - 200)"); ?> </span>
                                <input type="text" class="text-sidebar withlabel" id="swipe_velocity" name="swipe_velocity" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "swipe_velocity", "75"); ?>">
                                <div class="clear"></div>

                                <span class="label" id="label_swipe_min_touches" origtitle="<?php $modules->l("Defines how many fingers are needed minimum for swiping"); ?>"><?php $modules->l("Swipe Min Finger"); ?> </span>
                                <input type="text" class="text-sidebar withlabel" id="swipe_min_touches" name="swipe_min_touches" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "swipe_min_touches", "50"); ?>">
                                <div class="clear"></div>

                                <span class="label" id="label_swipe_direction" origtitle="<?php $modules->l("Swipe Direction to swap slides?"); ?>"><?php $modules->l("Swipe Direction"); ?></span>
                                <select id="swipe_direction" name="swipe_direction" class="withlabel">
                                    <option value="horizontal" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'swipe_direction', 'horizontal'), "horizontal"); ?>><?php $modules->l("Horizontal"); ?></option>
                                    <option value="vertical" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'swipe_direction', 'horizontal'), "vertical"); ?>><?php $modules->l("Vertical"); ?></option>											
                                </select>
                                <div class="clear"></div>

                            </div> <!-- END TOUCH NAVIGATION -->

                            <!-- KEYBOARD NAVIGATION -->
                            <div id="navigation-keyboard" style="display:none;">
                                <span class="label" id="label_keyboard_navigation" origtitle="<?php $modules->l("Allow/disallow to navigate the slider with keyboard."); ?>"><?php $modules->l("Keyboard Navigation"); ?></span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="keyboard_navigation" name="keyboard_navigation" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'keyboard_navigation', 'off'), "on"); ?>>
                                <div class="clear"></div>

                                <span class="label" id="label_keyboard_direction" origtitle="<?php $modules->l("Keyboard Direction to swap slides (horizontal - left/right arrow, vertical - up/down arrow)?"); ?>"><?php $modules->l("Key Direction"); ?></span>
                                <select id="keyboard_direction" name="keyboard_direction" class="withlabel">
                                    <option value="horizontal" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'keyboard_direction', 'horizontal'), "horizontal"); ?>><?php $modules->l("Horizontal"); ?></option>
                                    <option value="vertical" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'keyboard_direction', 'horizontal'), "vertical"); ?>><?php $modules->l("Vertical"); ?></option>											
                                </select>
                                <div class="clear"></div>

                                <span class="label" id="label_mousescroll_navigation" origtitle="<?php $modules->l("Allow/disallow to navigate the slider with Mouse Scroll."); ?>"><?php $modules->l("Mouse Scroll Navigation"); ?></span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="mousescroll_navigation" name="mousescroll_navigation" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'mousescroll_navigation', 'off'), "on"); ?>>
                                <div class="clear"></div>

                            </div><!-- END KEYBOARD NAVIGATION -->

                            <!-- PREVIEW IMAGE SIZES -->									
                            <div id="navigation-miniimagedimensions" style="border-top:1px solid #f1f1f1; margin:20px -20px 0px; padding:0px 20px">
                                <h4><?php $modules->l("Preview Image Size"); ?></h4>

                                <span id="label_previewimage_width" class="label"  origtitle="<?php $modules->l("The basic Width of one Preview Image."); ?>"><?php $modules->l("Preview Image Width"); ?></span>
                                <input type="text" class="text-sidebar withlabel" id="previewimage_width" name="previewimage_width" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "previewimage_width", RbSliderFunctions::getVal($arrFieldsParams, "thumb_width", 100)); ?>">
                                <span><?php $modules->l("px"); ?></span>
                                <div class="clear"></div>

                                <span id="label_previewimage_height" class="label"  origtitle="<?php $modules->l("The basic Height of one Preview Image."); ?>"><?php $modules->l("Preview Image Height"); ?></span>
                                <input type="text" class="text-sidebar withlabel" id="previewimage_height" name="previewimage_height" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "previewimage_height", RbSliderFunctions::getVal($arrFieldsParams, "thumb_height", 50)); ?>">
                                <span><?php $modules->l("px"); ?></span>
                                <div class="clear"></div>

                                <div class="clear"></div>
                            </div>
                        </div>
                        <script>
                            jQuery('#navigation-settings-wrapper input, #navigation-settings-wrapper select').on("change", drawToolBarPreview);

                            // NOT NICE, BUT SURELY UNBREAKABLE LATER :) 
                            jQuery('#enable_arrows').on("change", function() {
                                var sbi = jQuery(this);
                                if (sbi.attr("checked") === "checked") {
                                    jQuery('#nav_arrows_subs').show();
                                } else {
                                    jQuery('#nav_arrows_subs').hide();
                                }
                                drawToolBarPreview();
                            });


                            jQuery('#enable_bullets').on("change", function() {
                                var sbi = jQuery(this);
                                if (sbi.attr("checked") === "checked") {
                                    jQuery('#nav_bullets_subs').show();
                                } else {
                                    jQuery('#nav_bullets_subs').hide();
                                }
                                drawToolBarPreview();
                            });


                            jQuery('#enable_thumbnails').on("change", function() {
                                var sbi = jQuery(this);
                                if (sbi.attr("checked") === "checked") {
                                    jQuery('#nav_thumbnails_subs').show();
                                } else {
                                    jQuery('#nav_thumbnails_subs').hide();
                                }
                                drawToolBarPreview();
                            });


                            jQuery('#enable_tabs').on("change", function() {
                                var sbi = jQuery(this);
                                if (sbi.attr("checked") === "checked") {
                                    jQuery('#nav_tabs_subs').show();
                                } else {
                                    jQuery('#nav_tabs_subs').hide();
                                }
                                drawToolBarPreview();
                            });

                            jQuery('.showhidewhat_truefalse').on("change", function() {
                                var sbi = jQuery(this);
                                if (sbi.val() === true || sbi.val() === "true" || sbi.attr("checked")) {
                                    jQuery("#" + sbi.data("showhidetarget")).show();
                                } else {
                                    jQuery("#" + sbi.data("showhidetarget")).hide();
                                }
                            });



                            jQuery('#thumbnails_inner_outer').on('change', function() {
                                var sbi = jQuery(this),
                                        v = sbi.find('option:selected').val();
                                if (v === "outer-top" || v === "outer-bottom") {
                                    if (v === "outer-top")
                                        jQuery('#thumbnails_align_vert').val("top");
                                    if (v === "outer-bottom")
                                        jQuery('#thumbnails_align_vert').val("bottom");
                                    jQuery('#thumbnails_align_vert').attr("disabled", "disabled");
                                    jQuery('#thumbnail_direction').val("horizontal");
                                    jQuery('#thumbnail_direction').change();
                                }
                                else
                                    jQuery('#thumbnails_align_vert').removeAttr("disabled");


                                if (v === "outer-left" || v === "outer-right") {
                                    if (v === "outer-left")
                                        jQuery('#thumbnails_align_hor').val("left");
                                    if (v === "outer-right")
                                        jQuery('#thumbnails_align_hor').val("right");
                                    jQuery('#thumbnails_align_hor').attr("disabled", "disabled");
                                    jQuery('#thumbnail_direction').val("vertical");
                                    jQuery('#thumbnail_direction').change();
                                }
                                else
                                    jQuery('#thumbnails_align_hor').removeAttr("disabled");


                                if (v === "outer-left" || v === "outer-right" || v === "outer-top" || v === "outer-bottom")
                                    jQuery('#thumbnail_direction').attr("disabled", "disabled");
                                else
                                    jQuery('#thumbnail_direction').removeAttr("disabled");

                            });

                            jQuery('#tabs_inner_outer').on('change', function() {
                                var sbi = jQuery(this),
                                        v = sbi.find('option:selected').val();
                                if (v === "outer-top" || v === "outer-bottom") {
                                    if (v === "outer-top")
                                        jQuery('#tabs_align_vert').val("top");
                                    if (v === "outer-bottom")
                                        jQuery('#tabs_align_vert').val("bottom");
                                    jQuery('#tabs_align_vert').attr("disabled", "disabled");
                                    jQuery('#tabs_direction').val("horizontal");
                                    jQuery('#tabs_direction').change();
                                    jQuery('#tabs_direction').attr("disabled", "disabled");
                                }
                                else
                                    jQuery('#tabs_align_vert').removeAttr("disabled");

                                if (v === "outer-left" || v === "outer-right") {
                                    if (v === "outer-left")
                                        jQuery('#tabs_align_hor').val("left");
                                    if (v === "outer-right")
                                        jQuery('#tabs_align_hor').val("right");
                                    jQuery('#tabs_align_hor').attr("disabled", "disabled");
                                    jQuery('#tabs_direction').val("vertical");
                                    jQuery('#tabs_direction').change();
                                    jQuery('#tabs_direction').attr("disabled", "disabled");
                                }
                                else
                                    jQuery('#tabs_align_hor').removeAttr("disabled");

                                if (v === "outer-left" || v === "outer-right" || v === "outer-top" || v === "outer-bottom")
                                    jQuery('#tabs_direction').removeAttr("disabled", "disabled");
                            });



                            jQuery('.showhidewhat_truefalse').change();
                            jQuery('#thumbnails_inner_outer').change();
                            jQuery('#tabs_inner_outer').change();
                            jQuery('#enable_arrows').change();
                            jQuery('#enable_thumbnails').change();
                            jQuery('#enable_bullets').change();
                            jQuery('#enable_tabs').change();

                        </script>
                    </div><!-- END OF NAVIGATION SETTINGS -->

                    <!-- CAROUSEL SETTINGS -->
                    <div class="setting_box dontshowonhero dontshowonstandard">
                        <h3 class="box_closed"><i class="rs-rp-accordion-icon eg-icon-ccw"></i>
                            <div class="setting_box-arrow"></div>
                            <span><?php $modules->l("Carousel Settings"); ?></span>
                        </h3>

                        <div class="inside" style="display: none;">

                            <ul class="main-options-small-tabs" style="display:inline-block; ">
                                <li data-content="#carousel-basics" class="selected"><?php $modules->l('Basics'); ?></li>
                                <li data-content="#carousel-trans"><?php $modules->l('Transformations'); ?></li>
                                <li data-content="#carousel-aligns"><?php $modules->l('Aligns'); ?></li>
                            </ul>
                            <div id="carousel-basics">
                                <!-- Infinity -->
                                <span class="label" id="label_carousel_infinity" origtitle="<?php $modules->l("Infinity Carousel Scroll. No Endpoints exists at first and last slide if valuse is set to ON."); ?>"><?php $modules->l("Infinity Scroll"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="carousel_infinity" name="carousel_infinity" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_infinity', 'off'), 'on'); ?>>
                                <div class="clearfix"></div>

                                <!-- Carousel Spaces -->
                                <span class="label" id="label_carousel_space" origtitle="<?php $modules->l("The horizontal gap/space between the slides"); ?>"><?php $modules->l("Space between slides"); ?> </span>
                                <input type="text" class="text-sidebar withlabel"   id="carousel_space" name="carousel_space" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'carousel_space', '0'); ?>">
                                <span><?php $modules->l("px"); ?></span>
                                <div class="clear"></div>

                                <!-- Border Radius -->
                                <span class="label" id="label_carousel_borderr" origtitle="<?php $modules->l("The border radius of slides"); ?>"><?php $modules->l("Border Radius"); ?> </span>
                                <input style="width:60px;min-width:60px;max-width:60px;" type="text" class="text-sidebar withlabel"   id="carousel_borderr" name="carousel_borderr" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'carousel_borderr', '0'); ?>">

                                <!-- Border Radius Unit -->
                                <select style="width:45px;min-width:45px;max-width:45px;" id="carousel_borderr_unit" name="carousel_borderr_unit">
                                    <option value="px" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_borderr_unit', 'px'), "px"); ?>><?php $modules->l("px"); ?></option>
                                    <option value="%" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_borderr_unit', 'px'), "%"); ?>><?php $modules->l("%"); ?></option>
                                </select>
                                <div class="clear"></div>

                                <!-- Padding -->
                                <span class="label" id="label_carousel_padding_top" origtitle="<?php $modules->l("The padding top of slides"); ?>"><?php $modules->l("Padding Top"); ?> </span>
                                <input type="text" class="text-sidebar withlabel"   id="carousel_padding_top" name="carousel_padding_top" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'carousel_padding_top', '0'); ?>"> <?php $modules->l('px'); ?>

                                <span class="label" id="label_carousel_padding_bottom" origtitle="<?php $modules->l("The padding bottom of slides"); ?>"><?php $modules->l("Padding Bottom"); ?> </span>
                                <input type="text" class="text-sidebar withlabel"   id="carousel_padding_bottom" name="carousel_padding_bottom" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'carousel_padding_bottom', '0'); ?>"> <?php $modules->l('px'); ?>

                                <!-- Carousel Max Visible Items -->
                                <span class="label" id="label_carousel_maxitems" origtitle="<?php $modules->l("The maximum visible items in same time."); ?>"><?php $modules->l("Max. Visible Items"); ?> </span>
                                <select id="carousel_maxitems" class="withlabel"  name="carousel_maxitems">
                                    <option value="1" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_maxitems', '3'), "1"); ?>><?php $modules->l("1"); ?></option>
                                    <option value="3" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_maxitems', '3'), "3"); ?>><?php $modules->l("3"); ?></option>
                                    <option value="5" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_maxitems', '3'), "5"); ?>><?php $modules->l("5"); ?></option>
                                    <option value="7" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_maxitems', '3'), "7"); ?>><?php $modules->l("7"); ?></option>
                                    <option value="9" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_maxitems', '3'), "9"); ?>><?php $modules->l("9"); ?></option>
                                    <option value="11" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_maxitems', '3'), "11"); ?>><?php $modules->l("11"); ?></option>
                                    <option value="13" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_maxitems', '3'), "13"); ?>><?php $modules->l("13"); ?></option>
                                    <option value="15" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_maxitems', '3'), "15"); ?>><?php $modules->l("15"); ?></option>
                                    <option value="17" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_maxitems', '3'), "17"); ?>><?php $modules->l("17"); ?></option>
                                    <option value="19" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_maxitems', '3'), "19"); ?>><?php $modules->l("19"); ?></option>
                                </select>

                                <!-- Carousel Stretch Out -->
                                <span class="label" id="label_carousel_stretch" origtitle="<?php $modules->l("Stretch carousel element width to the wrapping container width.  Using this you can see only 1 item in same time."); ?>"><?php $modules->l("Stretch Element"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="carousel_stretch" name="carousel_stretch" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_stretch', 'off'), 'on'); ?>>
                                <div class="clearfix"></div>

                                <div class="clear"></div>
                            </div>


                            <div id="carousel-trans" style="display:none">
                                <!-- Carousel Fade Out -->
                                <span class="label" id="label_carousel_fadeout" origtitle="<?php $modules->l("All elements out of focus will get some Opacity value based on the Distance to the current focused element, or only the coming/leaving elements."); ?>"><?php $modules->l("Fade All Elements"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="carousel_fadeout" name="carousel_fadeout" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_fadeout', 'on'), "on"); ?>>
                                <div class="clearfix"></div>

                                <div id="carousel-fade-row" class="withsublabels">
                                    <!-- Carousel Rotation Varying Out -->
                                    <span class="label" id="label_carousel_varyfade" origtitle="<?php $modules->l("Fade is varying based on the distance to the focused element."); ?>"><?php $modules->l("Varying Fade"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel" id="carousel_varyfade" name="carousel_varyfade" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_varyfade', 'off'), "on"); ?>>
                                    <div class="clearfix"></div>
                                </div>


                                <!-- Carousel Rotation  -->
                                <span class="label label-with-subsection" id="label_carousel_rotation" origtitle="<?php $modules->l("Rotation enabled/disabled for not focused elements."); ?>"><?php $modules->l("Rotation"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="carousel_rotation" name="carousel_rotation" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_rotation', 'off'), "on"); ?>>
                                <div class="clearfix"></div>

                                <div id="carousel-rotation-row" class="withsublabels">

                                    <!-- Carousel Rotation Varying Out -->
                                    <span class="label" id="label_carousel_varyrotate" origtitle="<?php $modules->l("Rotation is varying based on the distance to the focused element."); ?>"><?php $modules->l("Varying Rotation"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel" id="carousel_varyrotate" name="carousel_varyrotate" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_varyrotate', 'off'), "on"); ?>>
                                    <div class="clearfix"></div>

                                    <!-- Carousel Max Rotation -->
                                    <span class="label" id="label_carousel_maxrotation" origtitle="<?php $modules->l("The maximum rotation of the Side elements. Rotation will depend on the element distance to the current focused element. 0 will turn off the Rotation"); ?>"><?php $modules->l("Max. Rotation"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel"   id="carousel_maxrotation" name="carousel_maxrotation" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'carousel_maxrotation', '0'); ?>">
                                    <span><?php $modules->l("deg"); ?></span>
                                    <div class="clear" ></div>
                                </div>

                                <!-- Carousel Scale -->
                                <span class="label label-with-subsection" id="label_carousel_scale" origtitle="<?php $modules->l("Scale enabled/disabled for not focused elements."); ?>"><?php $modules->l("Scale"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="carousel_scale" name="carousel_scale" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_scale', 'off'), "on"); ?>>
                                <div class="clearfix"></div>


                                <div id="carousel-scale-row" class="withsublabels">

                                    <!-- Carousel Scale Varying Out -->
                                    <span class="label" id="label_carousel_varyscale" origtitle="<?php $modules->l("Scale is varying based on the distance to the focused element."); ?>"><?php $modules->l("Varying Scale"); ?> </span>
                                    <input type="checkbox" class="tp-moderncheckbox withlabel" id="carousel_varyscale" name="carousel_varyscale" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_varyscale', 'off'), "on"); ?>>
                                    <div class="clearfix"></div>

                                    <!-- Carousel Min Scale Down -->
                                    <span class="label" id="label_carousel_scaledown" origtitle="<?php $modules->l("The maximum scale down of the Side elements. Scale will depend on the element distance to the current focused element. Min value is 0 and max value is 100."); ?>"><?php $modules->l("Max. Scaledown"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel"   id="carousel_scaledown" name="carousel_scaledown" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, 'carousel_scaledown', '50'); ?>">
                                    <span><?php $modules->l("%"); ?></span>
                                    <div class="clear"></div>
                                </div>
                            </div>

                            <div id="carousel-aligns" style="display:none">

                                <!-- Align of Carousel -->
                                <span class="label" id="label_carousel_hposition" origtitle="<?php $modules->l("Horizontal Align of the Carousel."); ?>"><?php $modules->l("Horizontal Aligns"); ?> </span>
                                <select id="carousel_hposition" class="withlabel"  name="carousel_hposition">
                                    <option value="left" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_hposition', 'center'), "left"); ?>><?php $modules->l("Left"); ?></option>
                                    <option value="center" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_hposition', 'center'), "center"); ?>><?php $modules->l("Center"); ?></option>
                                    <option value="right" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_hposition', 'center'), "right"); ?>><?php $modules->l("Right"); ?></option>
                                </select>
                                <div class="clear"></div>

                                <span class="label" id="label_carousel_vposition" origtitle="<?php $modules->l("Vertical Align of the Carousel."); ?>"><?php $modules->l("Vertical Aligns"); ?> </span>
                                <select id="carousel_vposition" class="withlabel"  name="carousel_vposition">
                                    <option value="top" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_vposition', 'center'), "top"); ?>><?php $modules->l("Top"); ?></option>
                                    <option value="center" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_vposition', 'center'), "center"); ?>><?php $modules->l("Center"); ?></option>
                                    <option value="bottom" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'carousel_vposition', 'center'), "bottom"); ?>><?php $modules->l("Bottom"); ?></option>
                                </select>
                                <div class="clear"></div>
                            </div>


                            <script>
                                jQuery('#carousel_stretch').on("change", function() {
                                    var sbi = jQuery(this);
                                    if (sbi.attr("checked") === "checked" && jQuery('#carousel_maxitems option[value="1"]').attr("selected") === undefined) {
                                        jQuery('#carousel_maxitems option:selected').removeAttr('selected');
                                        jQuery('#carousel_maxitems option[value="1"]').attr("selected", "selected");
                                    }
                                });

                                jQuery('#carousel_maxitems').on("change", function() {
                                    if (jQuery('#carousel_stretch').attr("checked") === "checked" && jQuery('#carousel_maxitems option[value="1"]').attr("selected") === undefined) {
                                        jQuery('#carousel_stretch').removeAttr("checked");
                                        jQuery('#carousel_stretch').change();
                                    }
                                });

                                jQuery('#carousel_fadeout').on("change", function() {
                                    var sbi = jQuery(this);

                                    if (sbi.attr("checked") === "checked") {
                                        jQuery('#carousel-fade-row').show();

                                    } else {
                                        jQuery('#carousel-fade-row').hide();
                                    }
                                });

                                jQuery('#carousel_rotation').on("change", function() {
                                    var sbi = jQuery(this);

                                    if (sbi.attr("checked") === "checked") {
                                        jQuery('#carousel-rotation-row').show();

                                    } else {
                                        jQuery('#carousel-rotation-row').hide();
                                    }
                                });

                                jQuery('#carousel_scale').on("change", function() {
                                    var sbi = jQuery(this);

                                    if (sbi.attr("checked") === "checked") {
                                        jQuery('#carousel-scale-row').show();

                                    } else {
                                        jQuery('#carousel-scale-row').hide();
                                    }
                                });
                                jQuery('#carousel_scale').change();
                                jQuery('#carousel_fadeout').change();
                                jQuery('#carousel_rotation').change();
                                jQuery('#first_transition_active').change();

                            </script>

                        </div>
                    </div> <!-- END OF CAROUSEL SETTINGS -->

                    <!-- Parallax Level -->
                    <div class="setting_box">
                        <h3 class="box_closed"><i class="rs-rp-accordion-icon eg-icon-camera-alt"></i>

                            <div class="setting_box-arrow"></div>

                            <span><?php $modules->l('Parallax & 3D'); ?></span>
                        </h3>

                        <div class="inside" style="display:none">
                            <span class="label" id="label_use_parallax" origtitle="<?php $modules->l("Enabling this, will give you new options in the slides to create a unique parallax effect"); ?>"><?php $modules->l("Enable Parallax / 3D"); ?> </span>
                            <input type="checkbox" class="tp-moderncheckbox withlabel" id="use_parallax" name="use_parallax" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "use_parallax", "off"), "on"); ?>>
                            <div class="clear"></div>

                            <div id="parallax_settings_row">

                                <span id="label_disable_parallax_mobile" class="label" origtitle="<?php $modules->l("If set to on, parallax will be disabled on mobile devices to save performance"); ?>"><?php $modules->l("Disable on Mobile"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="disable_parallax_mobile" name="disable_parallax_mobile" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "disable_parallax_mobile", "off"), "on"); ?>>
                                <div class="clear"></div>

                                <span class="label" id="label_ddd_parallax" origtitle="<?php $modules->l("Enabling this, will build a ddd_Rotating World of your Slides."); ?>"><?php $modules->l("3D"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="ddd_parallax" name="ddd_parallax" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "ddd_parallax", "off"), "on"); ?>>
                                <div class="clear"></div>



                                <div class="show_on_ddd_parallax">
                                    <h4><?php $modules->l("3D Settings"); ?></h4>
                                    <div class="withsublabels">
                                        <span class="label" id="label_ddd_parallax_shadow" origtitle="<?php $modules->l("Enabling 3D Shadow"); ?>"><?php $modules->l("3D Shadow"); ?> </span>
                                        <input type="checkbox" class="tp-moderncheckbox withlabel" id="ddd_parallax_shadow" name="ddd_parallax_shadow" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "ddd_parallax_shadow", "off"), "on"); ?>>
                                        <div class="clear"></div>

                                        <span class="label" id="label_ddd_parallax_bgfreeze" origtitle="<?php $modules->l("BG 3D Disabled"); ?>"><?php $modules->l("3D Background Disabled"); ?> </span>
                                        <input type="checkbox" class="tp-moderncheckbox withlabel" id="ddd_parallax_bgfreeze" name="ddd_parallax_bgfreeze" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "ddd_parallax_bgfreeze", "off"), "on"); ?>>
                                        <div class="clear"></div>

                                        <span class="label" id="label_ddd_parallax_overflow" origtitle="<?php $modules->l("If option is enabled, all slides and Layers are cropped by the Slider sides."); ?>"><?php $modules->l("Slider Overflow Hidden"); ?> </span>
                                        <input type="checkbox" class="tp-moderncheckbox withlabel" id="ddd_parallax_overflow" name="ddd_parallax_overflow" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "ddd_parallax_overflow", "off"), "on"); ?>>
                                        <div class="clear"></div>

                                        <span class="label" id="label_ddd_parallax_layer_overflow" origtitle="<?php $modules->l("If option enabled, Layers are cropped by the Grid Layer Dimensions to avoid Floated 3d Texts and hide Elements outside of the Slider."); ?>"><?php $modules->l("Layers Overflow Hidden"); ?> </span>
                                        <input type="checkbox" class="tp-moderncheckbox withlabel" id="ddd_parallax_layer_overflow" name="ddd_parallax_layer_overflow" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "ddd_parallax_layer_overflow", "off"), "on"); ?>>
                                        <div class="clear"></div>


                                                <!--<span class="label" id="label_ddd_parallax_path" origtitle="<?php $modules->l("Select the Events which should trigger the 3D Animation.  Mouse - Mouse Movements will rotate the Slider, Static Paths will set Single or Animated 3d Rotations per Slides (Edit these paths via the Slide Editor), and both will allow you to use both in the same Time."); ?>"><?php $modules->l("3D Path"); ?> </span>
                                                <select id="ddd_parallax_path" class="withlabel"  name="ddd_parallax_path" style="max-width:110px">
                                                    <option value="mouse" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'ddd_parallax_path', 'mouse'), "mouse"); ?>><?php $modules->l("Mouse Based"); ?></option>
                                                    <option value="static" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'ddd_parallax_path', 'mouse'), "static"); ?>><?php $modules->l("Static Path (Set Slide by Slide)"); ?></option>
                                                    <option value="both" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, 'ddd_parallax_path', 'mouse'), "both"); ?>><?php $modules->l("Both"); ?></option>												
                                                </select>
                                                <div class="clear"></div>-->

                                        <span class="label" id="label_ddd_parallax_zcorrection" origtitle="<?php $modules->l("Solves issues in Safari Browser. It will move layers along z-axis if BG Freeze enabled to avoid 3d Rendering issues"); ?>"><?php $modules->l("3D Crop Fix (z)"); ?> </span>
                                        <input type="text" class="text-sidebar withlabel" id="ddd_parallax_zcorrection" name="ddd_parallax_zcorrection" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "ddd_parallax_zcorrection", "65"); ?>">									
                                        <span ><?php $modules->l("px"); ?></span>
                                        <div class="clear"></div>

                                    </div>
                                </div>




                                <h4><?php $modules->l("Mouse Sensibility"); ?></h4>
                                <div class="withsublabels">
                                    <div class="hide_on_ddd_parallax">
                                        <span id="label_parallax_type" class="label" origtitle="<?php $modules->l("Defines on what event type the parallax should react to"); ?>"><?php $modules->l("Event"); ?></span>
                                        <select id="parallax_type" name="parallax_type"  class="withlabel">
                                            <option value="mouse" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "parallax_type", "mouse"), "mouse"); ?>><?php $modules->l("Mouse Move"); ?></option>
                                            <option value="scroll" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "parallax_type", "mouse"), "scroll"); ?>><?php $modules->l("Scroll Position"); ?></option>
                                            <option value="mouse+scroll" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "parallax_type", "mouse"), "mouse+scroll"); ?>><?php $modules->l("Move and Scroll"); ?></option>
                                        </select>
                                        <div class="clear"></div>

                                        <span id="label_parallax_origo" class="label" origtitle="<?php $modules->l("Mouse Based parallax calculation Origo"); ?>"><?php $modules->l("Parallax Origo"); ?></span>
                                        <select id="parallax_origo" name="parallax_origo"  class="withlabel">
                                            <option value="enterpoint" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "parallax_origo", "enterpoint"), "enterpoint"); ?>><?php $modules->l("Mouse Enter Point"); ?></option>
                                            <option value="slidercenter" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "parallax_origo", "enterpoint"), "slidercenter"); ?>><?php $modules->l("Slider Center"); ?></option>										
                                        </select>
                                        <div class="clear"></div>
                                    </div>

                                    <span class="label" id="label_parallax_speed" origtitle="<?php $modules->l("Parallax Speed for Mouse movents."); ?>"><?php $modules->l("Animation Speed"); ?> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_speed" name="parallax_speed" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_speed", "400"); ?>">									
                                    <span ><?php $modules->l("ms"); ?></span>
                                    <div class="clear"></div>		
                                </div>							

                                <h4 class="hide_on_ddd_parallax"><?php $modules->l("Parallax Levels"); ?></h4>
                                <h4 class="show_on_ddd_parallax"><?php $modules->l("3D Depth Levels"); ?></h4>

                                <div class="withsublabels">
                                    <span class="show_on_ddd_parallax">
                                        <span class="label" id="label_parallax_level_16" origtitle="<?php $modules->l("Defines the Strength of the 3D Rotation on the Background and Layer Groups.  The Higher the Value the stronger the effect.  All other Depth will offset this default value !"); ?>"><span><?php $modules->l("Default 3D Depth"); ?></span> </span>
                                        <input type="text" class="text-sidebar withlabel" id="parallax_level_16" name="parallax_level_16" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_16", "55"); ?>">
                                        <span class="clear"></span>
                                    </span>

                                    <span class="label" id="label_parallax_level_1" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 1"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 1"); ?></span></span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_1" name="parallax_level_1" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_1", "5"); ?>">									
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_2" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 2"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 2"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_2" name="parallax_level_2" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_2", "10"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_3" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 3"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 3"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel " id="parallax_level_3" name="parallax_level_3" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_3", "15"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_4" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 4"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 4"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_4" name="parallax_level_4" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_4", "20"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_5" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 5"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 5"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_5" name="parallax_level_5" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_5", "25"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_6" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 6"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 6"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_6" name="parallax_level_6" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_6", "30"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_7" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 7"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 7"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_7" name="parallax_level_7" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_7", "35"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_8" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 8"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 8"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_8" name="parallax_level_8" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_8", "40"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_9" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 9"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 9"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_9" name="parallax_level_9" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_9", "45"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_10" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 10"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 10"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_10" name="parallax_level_10" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_10", "46"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_11" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 11"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 11"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_11" name="parallax_level_11" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_11", "47"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_12" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 12"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 12"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_12" name="parallax_level_12" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_12", "48"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_13" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 13"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 13"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_13" name="parallax_level_13" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_13", "49"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_14" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 14"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 14"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_14" name="parallax_level_14" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_14", "50"); ?>">
                                    <div class="clear"></div>

                                    <span class="label" id="label_parallax_level_15" origtitle="<?php $modules->l("Defines the strength of the effect. The higher the value, the stronger the effect. In 3D World the smaller Value comes to the front, and the Higher Value goes to the Background. Set for BG in 3D World the highest value always. Elements with higher z-index should get smaller values to make the effect perfect."); ?>"><span class="hide_on_ddd_parallax"><?php $modules->l("Level Depth 15"); ?></span><span class="show_on_ddd_parallax"><?php $modules->l("Depth 15"); ?></span> </span>
                                    <input type="text" class="text-sidebar withlabel" id="parallax_level_15" name="parallax_level_15" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "parallax_level_15", "51"); ?>">
                                    <div class="clear"></div>


                                </div>
                            </div>
                        </div>

                        <script>
                            jQuery('#use_parallax').on("change", function() {
                                var sbi = jQuery(this);
                                drawToolBarPreview();
                                if (sbi.attr("checked") === "checked") {
                                    jQuery('#parallax_settings_row').show();
                                    jQuery('#ddd_parallax').change();
                                } else {
                                    jQuery('#parallax_settings_row').hide();
                                    jQuery('.hide_on_ddd_parallax').show();
                                    jQuery('.show_on_ddd_parallax').hide();
                                }
                            });
                            jQuery('#ddd_parallax').on("change", function() {
                                drawToolBarPreview();
                                var sbi = jQuery(this);
                                if (sbi.attr("checked") === "checked" && jQuery('#use_parallax').attr("checked") === "checked") {
                                    jQuery('.hide_on_ddd_parallax').hide();
                                    jQuery('.show_on_ddd_parallax').show();
                                } else {
                                    jQuery('.hide_on_ddd_parallax').show();
                                    jQuery('.show_on_ddd_parallax').hide();
                                }
                            });

                            jQuery('#ddd_parallax_shadow').on("change", drawToolBarPreview);

                            jQuery('#use_parallax').change();
                            jQuery('#ddd_parallax').change();
                        </script>

                    </div><!-- End of Parallax Level -->

                            <?php if (!empty($sliderID)) {

                                ?>
                        <!-- SPEED MONITOR -->
                        <div class="setting_box">
                            <h3 class="box_closed"><i class="rs-rp-accordion-icon eg-icon-cog-alt"></i>
                                <div class="setting_box-arrow"></div>
                                <span><?php $modules->l("Performance and SEO Optimization");

                            ?></span>
                            </h3>

                            <div class="inside" style="display:none;">

                                <!-- LAZY LOAD -->
    <?php
    $llt = RbSliderFunctions::getVal($arrFieldsParams, 'lazy_load_type', false);
    if ($llt === false) {
        //do fallback checks to removed lazy_load value since version 5.0 and replaced with an enhanced version
        $old_ll = RbSliderFunctions::getVal($arrFieldsParams, 'lazy_load', 'off');
        $llt = ($old_ll == 'on') ? 'all' : 'none';
    }

    ?>
                                <span id="label_lazy_load_type" class="label" origtitle="<?php $modules->l("How to load/preload the images. <br><br><strong>All</strong> - Load all image element in a sequence at the initialisation. This will boost up the loading of your page, and will preload all images to have a smooth and breakless run already in the first loop.  <br><br><strong>Smart</strong> - It will load the page as quick as possible, and load only the current and neighbour slide elements. If slide is called which not loaded yet, will be loaded on demand with minimal delays.   <br><br><strong>Single</strong> - It will load only the the start slide. Any other slides will be loaded on demand.");

    ?>"><?php $modules->l("Lazy Load");

    ?> </span>
                                <select id="lazy_load_type" name="lazy_load_type" class="withlabel" >
                                    <option value="all" <?php Rbthemeslider::selected($llt, 'all');

    ?>><?php $modules->l("All");

    ?></option>
                                    <option value="smart" <?php Rbthemeslider::selected($llt, 'smart');

                    ?>><?php $modules->l("Smart");

                    ?></option>
                                    <option value="single" <?php Rbthemeslider::selected($llt, 'single');

                    ?>><?php $modules->l("Single");

                    ?></option>
                                    <option value="none" <?php Rbthemeslider::selected($llt, 'none');

                    ?>><?php $modules->l("No Lazy Loading");

                    ?></option>
                                </select>
                                <div class="clearfix"></div>

    <?php
    $seo_opti = RbSliderFunctions::getVal($arrFieldsParams, 'seo_optimization', 'none');

    ?>
                                <span id="label_seo_optimization" class="label" origtitle="<?php $modules->l('Define SEO Optimization for the Images in the Slider, useful if Lazy Load is on.');

    ?>"><?php $modules->l('SEO Optimization');

    ?> </span>
                                <select id="seo_optimization" name="seo_optimization" class="withlabel">
                                    <option value="none" <?php Rbthemeslider::selected($seo_opti, 'none');

    ?>><?php $modules->l("None");

    ?></option>
                                    <option value="noscript" <?php Rbthemeslider::selected($seo_opti, 'single');

    ?>><?php $modules->l("NoScript");

    ?></option>
                                    <option value="noframe" <?php Rbthemeslider::selected($seo_opti, 'smart');

    ?>><?php $modules->l("NoFrame");

    ?></option>
                                </select>
                                <div class="clearfix"></div>

                                <!-- MONITORING PART -->
    <?php //list all images and speed here  ?>
    <?php
    /////////////////////////////////////////////
    ///////////// Need to work on it ////////////
    /////////////////////////////////////////////
    RbSliderOperations::getSliderSpeed($sliderID);

    ?>
                            </div><!-- END OF INSIDE-->
                        </div>
                        <script>
                            jQuery(document).on("ready", function() {
                                jQuery('#lazy_load_type').on("change", function() {
                                    switch (jQuery('#lazy_load_type option:selected').val()) {
                                        case "all":
                                        case "none":
                                            jQuery('.tp-monitor-single-speed').hide();
                                            jQuery('.tp-monitor-smart-speed').hide();
                                            jQuery('.tp-monitor-all-speed').show();
                                            break;
                                        case "smart":
                                            jQuery('.tp-monitor-single-speed').hide();
                                            jQuery('.tp-monitor-smart-speed').show();
                                            jQuery('.tp-monitor-all-speed').hide();
                                            break;
                                        case "single":
                                            jQuery('.tp-monitor-single-speed').show();
                                            jQuery('.tp-monitor-smart-speed').hide();
                                            jQuery('.tp-monitor-all-speed').hide();
                                            break;
                                    }
                                });
                                jQuery('#lazy_load_type').change();
                            })
                        </script>

    <?php }

?>


                    <!-- FALLBACKS -->
                    <div class="setting_box" id="phandlings">
                        <h3 class="box_closed"><i class="rs-rp-accordion-icon eg-icon-medkit"></i>

                            <div class="setting_box-arrow"></div>

                            <span class="phandlingstitle"><?php $modules->l('Problem Handlings'); ?></span>
                        </h3>

                        <div class="inside" style="display:none;">
                            <ul class="main-options-small-tabs" style="display:inline-block; ">
                                <li data-content="#problem-fallback" class="selected"><?php $modules->l('Fallbacks'); ?></li>
                                <li id="phandling_menu" data-content="#problem-troubleshooting" class=""><?php $modules->l('Troubleshooting'); ?></li>
                            </ul>
                            <div id="problem-fallback">
                                <span id="label_simplify_ie8_ios4" class="label" origtitle="<?php $modules->l("Simplyfies the Slider on IOS4 and IE8"); ?>"><?php $modules->l("Simplify on IOS4/IE8"); ?> </span>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="simplify_ie8_ios4" name="simplify_ie8_ios4" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "simplify_ie8_ios4", "off"), "on"); ?>>
                                <div class="clear"></div>

                                <div id="label_show_alternative_type" class="label" origtitle="<?php $modules->l("Disables the Slider and load an alternative image instead"); ?>"><?php $modules->l("Use Alternative Image"); ?> </div>
                                <select id="show_alternative_type" name="show_alternative_type" class="withlabel">
                                    <option value="off" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "show_alternative_type", "off"), "off"); ?>><?php $modules->l("Off"); ?></option>
                                    <option value="mobile" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "show_alternative_type", "off"), "mobile"); ?>><?php $modules->l("On Mobile"); ?></option>
                                    <option value="ie8" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "show_alternative_type", "off"), "ie8"); ?>><?php $modules->l("On IE8"); ?></option>
                                    <option value="mobile-ie8" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "show_alternative_type", "off"), "mobile-ie8"); ?>><?php $modules->l("On Mobile and IE8"); ?></option>
                                </select>
                                <div class="clear"></div>

                                <div class="enable_alternative_image">
                                    <div id="label_show_alternate_image" class="label" origtitle="<?php $modules->l("The image that will be loaded instead of the slider."); ?>"><?php $modules->l("Alternate Image"); ?> </div>
                                    <input type="text" style="width: 104px;" class="text-sidebar-long withlabel" id="show_alternate_image" name="show_alternate_image" value="<?php echo RbSliderFunctions::getVal($arrFieldsParams, "show_alternate_image", ""); ?>">
                                    <a original-title="" href="javascript:void(0)" class="button-image-select-background-img button-primary rbblue"><?php $modules->l('Set'); ?></a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div id="problem-troubleshooting" style="display:none;">
                                <?php /*
                                  <div id="label_jquery_noconflict" class="label" origtitle="<?php $modules->l("Turns on / off jquery noconflict mode. Try to enable this option if javascript conflicts exist on the page.");?>"><?php $modules->l("JQuery No Conflict Mode");?> </div>
                                  <input type="checkbox" class="tp-moderncheckbox withlabel" id="jquery_noconflict" name="jquery_noconflict" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "jquery_noconflict", "off"), "on");?>>
                                 */ ?>
                                <div class="clear"></div>

                                <span id="label_js_to_body" class="label" origtitle="<?php $modules->l("Try this to fix some javascript conflicts of type: TypeError: tpj('#rb_slider_1_1').show().rbthemeslider is not a function"); ?>"><?php $modules->l("Put JS Includes To Body"); ?> </span>
                                <span id="js_to_body" class="withlabel">
                                    <input type="radio" id="js_to_body_1" value="true"  name="js_to_body" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "js_to_body", "false"), "true"); ?>>
                                    <label for="js_to_body_1" style="cursor:pointer;margin-right:15px"><?php $modules->l('On'); ?></label>
                                    <input type="radio" id="js_to_body_2" value="false" name="js_to_body" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "js_to_body", "false"), "false"); ?>>
                                    <label for="js_to_body_2" style="cursor:pointer;"><?php $modules->l('Off'); ?></label>
                                </span>
                                <div class="clear"></div>

                                <div id="label_output_type" class="label" origtitle="<?php $modules->l("Activate a protection against wordpress output filters that adds html blocks to the shortcode output like P and BR."); ?>"><?php $modules->l("Output Filters Protection"); ?> </div>
                                <select id="output_type" name="output_type" style="max-width:105px" class="withlabel">
                                    <option value="none" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "output_type", "none"), "none"); ?>><?php $modules->l("None"); ?></option>
                                    <option value="compress" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "output_type", "none"), "compress"); ?>><?php $modules->l("By Compressing Output"); ?></option>
                                    <option value="echo" <?php Rbthemeslider::selected(RbSliderFunctions::getVal($arrFieldsParams, "output_type", "none"), "echo"); ?>><?php $modules->l("By Echo Output"); ?></option>
                                </select>
                                <div class="clear"></div>

                                <div id="label_jquery_debugmode" class="label phandlingstitle" origtitle="<?php $modules->l("Turns on / off visible Debug Mode on Front End."); ?>"><?php $modules->l("Debug Mode"); ?> </div>
                                <input type="checkbox" class="tp-moderncheckbox withlabel" id="jquery_debugmode" name="jquery_debugmode" data-unchecked="off" <?php checked(RbSliderFunctions::getVal($arrFieldsParams, "jquery_debugmode", "off"), "on"); ?>>
                                <div class="clear"></div>

                                <div style="margin-top:15px"><a href="http://www.themepunch.com/faq/troubleshooting-tips-for-5-0/" target="_blank"><?php $modules->l("Follow FAQ for Troubleshooting"); ?></a></div>
                            </div>
                        </div>
                        <script>
                            jQuery('#show_alternative_type').on("change", function() {
                                var sbi = jQuery(this);
                                switch (sbi.val()) {
                                    case "off":
                                        jQuery('.enable_alternative_image').hide();
                                        break;
                                    default:
                                        jQuery('.enable_alternative_image').show();
                                        break;
                                }
                            });

                            jQuery('#jquery_debugmode').on("change", function() {
                                if (jQuery(this).attr("checked") === "checked")
                                    jQuery('#phandlings').addClass("debugmodeon");
                                else
                                    jQuery('#phandlings').removeClass("debugmodeon");
                            });

                            if (jQuery('#jquery_debugmode').attr("checked") === "checked")
                                jQuery('#phandlings').addClass("debugmodeon");
                            else
                                jQuery('#phandlings').removeClass("debugmodeon");

                            jQuery('#show_alternative_type').change();
                        </script>
                    </div> <!-- End of FALLBACKS -->

                    <!-- Google Font -->
                    <div class="setting_box rs-cm-refresh">
                        <h3 class="box_closed"><i class="rs-rp-accordion-icon eg-icon-font"></i>
                            <div class="setting_box-arrow"></div>
                            <span><?php $modules->l('Google Fonts'); ?></span>
                        </h3>

                        <div class="inside" style="display:none">

                            <div class="rs-gf-listing">
<?php
$subsets = RbSliderFunctions::getVal($arrFieldsParams, 'subsets', array());

$gf = array();
if ($is_edit) {
    if (!empty($sliderID)) {
        $slider = RbGlobalObject::getVar('slider');
        $gf = $slider->getUsedFonts();
    }
}
if (!empty($gf)) {
    echo '<h4 style="margin-top:0px;margin-bottom:8px">' . __('Dynamically Registered Google Fonts') . '</h4>';

    foreach ($gf as $mgf => $mgv) {
        echo '<div class="single-google-font-item">';
        echo '<span class="label font-name-label">' . $mgf . ':';
        if (!empty($mgv['variants'])) {
            $mgfirst = true;
            foreach ($mgv['variants'] as $mgvk => $mgvv) {
                if (!$mgfirst) {
                    echo ',';
                }
                echo $mgvk;
                $mgfirst = false;
            }
        }
        echo '</span>';
        echo '<div class="single-font-setting-wrapper">';
        if (!empty($mgv['slide'])) {
            echo '<span class="label">Used in Slide:</span>';
            echo '<select class="google-font-slide-link-list">';
            echo '<option value="blank">Edit Slide(s)</option>';
            foreach ($mgv['slide'] as $mgskey => $mgsval) {
                echo '<option value="' . self::getViewUrl(RbSliderAdmin::VIEW_SLIDE, 'id=' . $mgsval['id'] . '&slider=' . (int) ($sliderID)) . '">' . __('Edit:') . ' ' . ($mgsval['title']) . '</option>';
            }
            echo '</select>';
        }

        if (!empty($mgv['subsets'])) {
            echo '<div class="clear"></div>';

            foreach ($mgv['subsets'] as $ssk => $ssv) {
                echo '<span class="label subsetlabel">' . $ssv . '</span>';
                echo '<input class="tp-moderncheckbox" type="checkbox" data-useval="true" value="' . ($mgf . '+' . $ssv) . '" name="subsets[]" ';
                if (array_search(($mgf . '+' . $ssv), $subsets) !== false) {
                    echo 'checked="checked"';
                }
                echo '> ';
            }
        }
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<h4 style="margin-top:0px;">' . __('No dynamic fonts registered') . '</h4>';
}

?>
                            </div>
                            <script>
                                jQuery('.google-font-slide-link-list').on('change', function() {
                                    var t = jQuery(this),
                                            v = t.find('option:selected').val();

                                    if (v != "blank") {
                                        var win = window.open(v, '_blank');
                                        if (win) {
                                            win.focus();
                                        } else {
                                            alert('<?php $modules->l('Link to Slide Editor is Blocked ! Please Allow Pop Ups for this Site !'); ?>');
                                        }
                                    }
                                    t.val("blank");

                                });
                            </script>
                            <h4><?php $modules->l("Deprecated Google Font Import"); ?></h4>
                            <div id="rs-google-fonts">

                            </div>
                            <!--p><a class="button-primary rbblue" id="add_new_google_font" original-title=""><i class="rbicon-cog"></i><?php $modules->l('Add New Font'); ?></a></p-->
                            <!--i style="font-size:10px;color:#777"><?php $modules->l('Copy the Google Font Family from <a href="http://www.google.com/fonts" target="_blank">http://www.google.com/fonts</a> like: <strong>Open+Sans:400,700,600</strong>'); ?></i-->
                        </div>
                    </div> <!-- End of Google Font -->

                </form>

                <!-- IMPORT / EXPORT SETTINGS -->
<?php
if ($is_edit) {

    ?>
                    <div class="setting_box">
                        <h3 class="box_closed"><i class="rs-rp-accordion-icon eg-icon-upload"></i>
                            <div class="setting_box-arrow"></div>
                            <span><?php $modules->l('Import / Export / Replace');

                ?></span>
                        </h3>
                        <div class="inside" style="display:none">
                            <ul class="main-options-small-tabs" style="display:inline-block; ">
                                <li data-content="#import-import" class="selected"><?php $modules->l('Import');

                ?></li>
                                <li data-content="#import-export" class=""><?php $modules->l('Export');

                ?></li>
                                <li data-content="#import-replace" class=""><?php $modules->l('Replace URL');

                ?></li>
                            </ul>

                            <div id="import-import">
                                <form name="import_slider_form" id="rs_import_slider_form" action="<?php echo RbSliderBase::$url_ajax;

                ?>" enctype="multipart/form-data" method="post">
                                    <input type="hidden" name="action" value="rbslider_ajax_action">
                                    <input type="hidden" name="client_action" value="import_slider">
                                    <input type="hidden" name="sliderid" value="<?php echo $sliderID;

                ?>">
                                    <input type="file" name="import_file" class="input_import_slider" style="width:100%; font-size:12px;">
                                    <div style="width:100%;height:25px"></div>

                                    <span class="label label-with-subsection" id="label_update_animations" origtitle="<?php $modules->l("Overwrite or append the custom animations due the new imported values ?");

    ?>"><?php $modules->l("Custom Animations");

                                  ?> </span>
                                    <input class="withlabel" type="radio" name="update_animations" value="true" checked="checked"> <?php $modules->l("overwrite") ?>
                                    <input class="withlabel"  type="radio" name="update_animations" value="false"> <?php $modules->l("append") ?>
                                    <div class="tp-clearfix"></div>

                                    <span class="label label-with-subsection" id="label_update_static_captions" origtitle="<?php $modules->l("Overwrite or append the static styles due the new imported values ?");

                                  ?>"><?php $modules->l("Static Styles");

                                  ?> </span>
                                    <input class="withlabel" type="radio" name="update_static_captions" value="true"> <?php $modules->l("overwrite") ?>
                                    <input class="withlabel" type="radio" name="update_static_captions" value="false"> <?php $modules->l("append") ?>
                                    <input class="withlabel" type="radio" name="update_static_captions" value="none" checked="checked"> <?php $modules->l("ignore");

                                  ?>
                                    <div class="tp-clearfix"></div>

                                    <div class="divide5"></div>
                                    <input type="submit" style="width:100%" class="button-primary rbgreen" id="rs-submit-import-form" value="<?php $modules->l('Import Slider');

                                  ?>">
                                </form>
                                <div class="divide20"></div>
                                <div class="rbred api-desc" style="padding:8px;color:#fff;font-weight:600;font-size:12px"><?php $modules->l("Note! Style templates will be updated if they exist. Importing slider, will delete all the current slider settings and slides and replacing it with the imported content.") ?></div>

                            </div>

                            <div id="import-export" style="display:none">
                                <a id="button$modules->lxport_slider" class='button-primary rbgreen' href='javascript:void(0)' style="width:100%;text-align:center;" ><?php $modules->l("Export Slider") ?></a> <div style="display: none;"><input type="checkbox" name="export_dummy_images"> <?php $modules->l("Export with Dummy Images") ?></div>
                            </div>

                            <div id="import-replace" style="display:none">

                                <span class="label label-with-subsection" id="label_replace_url_from" origtitle="<?php $modules->l("Replace all layer and backgorund image url's. example - replace from: http://localhost");

                                  ?>"><?php $modules->l("Replace From");

                                  ?> </span>
                                <input type="text" class="text-sidebar-link withlabel" id="replace_url_from">
                                <div class="tp-clearfix"></div>

                                <span class="label label-with-subsection" id="label_replace_url_to" origtitle="<?php $modules->l("Replace all layer and backgorund image url's. example - replace to: http://yoursite.com");

                                  ?>"><?php $modules->l("Replace To");

    ?> </span>
                                <input type="text" class="text-sidebar-link withlabel" id="replace_url_to">
                                <div class="tp-clearfix"></div>


                                <div style="width:100%;height:15px;display:block"></div>

                                <a id="button_replace_url" class='button-primary rbgreen' href='javascript:void(0)' style="width:100%; text-align:center;"  ><?php $modules->l("Replace URL's") ?></a>
                                <div id="loader_replace_url" class="loader_round" style="display:none;"><?php $modules->l("Replacing...") ?> </div>
                                <div id="replace_url_success" class="success_message" class="display:none;"></div>
                                <div class="divide20"></div>
                                <div class="rbred api-desc" style="padding:8px;color:#fff;font-weight:600;font-size:12px"><?php $modules->l("Note! The replace process is not reversible !") ?></div>
                            </div>
                        </div>
                    </div>
                                    <?php
                                }

                                ?> <!-- END OF IMPORT EXPORT SETTINGS -->

                <!-- API SETTINGS -->
                                <?php
                                if ($is_edit) {
                                    $api = "rbapi" . $sliderID;

                                    ?>

                    <div class="setting_box rs-cm-refresh">
                        <h3 class="box_closed"><i class="rs-rp-accordion-icon eg-icon-magic"></i>
                            <div class="setting_box-arrow"></div>
                            <span><?php $modules->l('API Functions');

                                    ?></span>
                        </h3>
                        <div class="inside" style="display:none">
                            <ul class="main-options-small-tabs" style="display:inline-block; ">
                                <li data-content="#api-method" class="selected"><?php $modules->l('Methods');

                                ?></li>
                                <li data-content="#api-events" class=""><?php $modules->l('Events');

                                    ?></li>
                            </ul>
                            <div id="api-method">
                                <span class="label" id="label_apiapi1" style="min-width:130px" origtitle="<?php $modules->l("Call this function to pause the slider.");

                                    ?>"><?php $modules->l("Pause Slider") ?>:</span>
                                <input type="text" style="width:180px" readonly  class="api-input withlabel" id="apiapi1" value="<?php echo $api ?>.rbpause();"></span>
                                <div class="tp-clearfix"></div>

                                <span class="label" id="label_apiapi2" style="min-width:130px" origtitle="<?php $modules->l("Call this function to play the slider if it is paused.");

                                    ?>"><?php $modules->l("Resume Slider") ?>:</span>
                                <input type="text" style="width:180px" readonly class="api-input withlabel" id="apiapi2" value="<?php echo $api ?>.rbresume();"></span>
                                <div class="tp-clearfix"></div>

                                <span class="label" id="label_apiapi3" style="min-width:130px" origtitle="<?php $modules->l("Switch slider to previous slide.");

                                    ?>"><?php $modules->l("Previous Slide") ?>:</span>
                                <input type="text" style="width:180px" readonly class="api-input withlabel" id="apiapi3" value="<?php echo $api ?>.revprev();"></span>
                                <div class="tp-clearfix"></div>

                                <span class="label" id="label_apiapi4" style="min-width:130px" origtitle="<?php $modules->l("Switch slider to next slide.");

                                    ?>"><?php $modules->l("Next Slide") ?>:</span>
                                <input type="text" style="width:180px" readonly class="api-input withlabel" id="apiapi4" value="<?php echo $api ?>.rbnext();"></span>
                                <div class="tp-clearfix"></div>

                                <span class="label" id="label_apiapi5" style="min-width:130px" origtitle="<?php $modules->l("Switch to the slide which is defined as parameter.");

                                    ?>"><?php $modules->l("Go To Slide") ?>:</span>
                                <input type="text" style="width:180px" readonly class="api-input withlabel" id="apiapi5" value="<?php echo $api ?>.rbshowslide(2);"></span>
                                <div class="tp-clearfix"></div>

                                <span class="label" id="label_apiapi5" style="min-width:130px" origtitle="<?php $modules->l("Switch to the slide which is defined as parameter.");

                                ?>"><?php $modules->l("Go To Slide with ID") ?>:</span>
                                <input type="text" style="width:180px" readonly class="api-input withlabel" id="apiapi5" value="<?php echo $api ?>.rbcallslidewithid('rs-1007');"></span>
                                <div class="tp-clearfix"></div>

                                <span class="label" id="label_apiapi6" style="min-width:130px" origtitle="<?php $modules->l("Get the amount of existing slides in the slider.");

                                    ?>"><?php $modules->l("Max Slides") ?>:</span>
                                <input type="text" style="width:180px" readonly class="api-input withlabel" id="apiapi6" value="<?php echo $api ?>.rbmaxslide();"></span>
                                <div class="tp-clearfix"></div>

                                <span class="label" id="label_apiapi7" style="min-width:130px" origtitle="<?php $modules->l("Get the current focused slide index.");

                                    ?>"><?php $modules->l("Current Slide") ?>:</span>
                                <input type="text" style="width:180px" readonly class="api-input withlabel" id="apiapi7" value="<?php echo $api ?>.rbcurrentslide();"></span>
                                <div class="tp-clearfix"></div>

                                <span class="label" id="label_apiapi8" style="min-width:130px" origtitle="<?php $modules->l("Get the previously played slide.");

                                    ?>"><?php $modules->l("Last Slide") ?>:</span>
                                <input type="text" style="width:180px" readonly class="api-input withlabel" id="apiapi8" value="<?php echo $api ?>.rblastslide();"></span>
                                <div class="tp-clearfix"></div>

                                <span class="label" id="label_apiapi9" style="min-width:130px" origtitle="<?php $modules->l("Scroll page under the slider.");

                                    ?>"><?php $modules->l("External Scroll") ?>:</span>
                                <input type="text" style="width:180px" readonly class="api-input withlabel" id="apiapi9" value="<?php echo $api ?>.rbscroll(offset);"></span>
                                <div class="tp-clearfix"></div>

                                <span class="label" id="label_apiapi10" style="min-width:130px" origtitle="<?php $modules->l("Recalculate all positions, sizing etc in the slider.  This should be called i.e. if Slider was invisible and becomes visible without any window resize event.");

                ?>"><?php $modules->l("Redraw Slider") ?>:</span>
                                <input type="text" style="width:180px" readonly  class="api-input withlabel" id="apiapi10" value="<?php echo $api ?>.rbredraw();"></span>
                                <div class="tp-clearfix"></div>

                                <span class="label" id="label_apiapi11" style="min-width:130px" origtitle="<?php $modules->l("Unbind all listeners, remove current animations and delete containers. Ready for Garbage collection.");

                ?>"><?php $modules->l("Kill Slider") ?>:</span>
                                <input type="text" style="width:180px" readonly  class="api-input withlabel" id="apiapi11" value="<?php echo $api ?>.rbkill();"></span>
                                <div class="tp-clearfix"></div>
                            </div>
                            <div id="api-events" style="display:none">
                                <h4 style="margin-top:0px"><?php $modules->l("Slider Loaded") ?></h4>
                                <textarea class="api_area" style="height:80px;" readonly>
    <?php echo $api ?>.bind("rbthemeslider.slide.onloaded",function (e) {
    	console.log("slider loaded");
    });</textarea>
                                <h4 style="margin-top:15px"><?php $modules->l("Slider swapped to an other slide") ?></h4>
                                <textarea class="api_area" style=" height:100px;" readonly>
    <?php echo $api ?>.bind("rbthemeslider.slide.onchange",function (e,data) {
    	console.log("slide changed to: "+data.slideIndex);
    	console.log("current slide <li> Index: "+data.slideLIIndex);
    	//data.currentslide - <?php $modules->l('Current  Slide as jQuery Object');

    ?>

    	//data.prevslide - <?php $modules->l('Previous Slide as jQuery Object');

    ?>  
    });</textarea>
                                <h4 style="margin-top:15px"><?php $modules->l("Slider paused") ?></h4>
                                <textarea class="api_area" style=" height:80px;" readonly>
    <?php echo $api ?>.bind("rbthemeslider.slide.onpause",function (e,data) {
    	console.log("timer paused");
    });</textarea>
                                <h4 style="margin-top:15px"><?php $modules->l("Slider is Playing after pause") ?></h4>
                                <textarea class="api_area" style=" height:80px;" readonly>
    <?php echo $api ?>.bind("rbthemeslider.slide.onresume",function (e,data) {
    	console.log("timer resume");
    });</textarea>
                                <h4 style="margin-top:15px"><?php $modules->l("Video is playing in slider") ?></h4>
                                <textarea class="api_area" style=" height:130px;" readonly>
    <?php echo $api ?>.bind("rbthemeslider.slide.onvideoplay",function (e,data) {
    	console.log("video play");
    	//data.video - <?php $modules->l('The Video API to Manage Video functions');

    ?>

    	//data.videotype - <?php $modules->l('youtube, vimeo, html5');

    ?>

    	//data.settings - <?php $modules->l('Video Settings');

    ?>
    });</textarea>
                                <h4 style="margin-top:15px"><?php $modules->l("Video stopped in slider") ?></h4>
                                <textarea class="api_area" style=" height:130px;" readonly>
    <?php echo $api ?>.bind("rbthemeslider.slide.onvideostop",function (e,data) {
    	console.log("video stop");
    	//data.video - <?php $modules->l('The Video API to Manage Video functions');

    ?>

    	//data.videotype - <?php $modules->l('youtube, vimeo, html5');

    ?>

    	//data.settings - <?php $modules->l('Video Settings');

    ?>
    });</textarea>
                                <h4 style="margin-top:15px"><?php $modules->l("Slider reached the 'stop at' slide") ?></h4>
                                <textarea class="api_area" style=" height:80px;" readonly>
    <?php echo $api ?>.bind("rbthemeslider.slide.onstop",function (e,data) {
    	console.log("slider stopped");
    });</textarea>
                                <h4 style="margin-top:15px"><?php $modules->l("Prepared for slide change") ?></h4>
                                <textarea class="api_area" style=" height:100px;" readonly>
    <?php echo $api ?>.bind("rbthemeslider.slide.onbeforeswap",function (e) {
    	console.log("Slider Before Swap");
    	//data.currentslide - <?php $modules->l('Current Slide as jQuery Object');

    ?>

    	//data.nextslide - <?php $modules->l('Coming Slide as jQuery Object');

    ?>
    });</textarea>
                                <h4 style="margin-top:15px"><?php $modules->l("Finnished with slide change") ?></h4>
                                <textarea class="api_area" style=" height:100px;" readonly>
    <?php echo $api ?>.bind("rbthemeslider.slide.onafterswap",function (e) {
    	console.log("Slider After Swap");
    	//data.currentslide - <?php $modules->l('Current Slide as jQuery Object');

    ?>

    	//data.previousslide - <?php $modules->l('Previous Slide as jQuery Object');

    ?>
    });</textarea>
                                <h4 style="margin-top:15px"><?php $modules->l("Last slide starts") ?></h4>
                                <textarea class="api_area" style=" height:80px;" readonly>
    <?php echo $api ?>.bind("rbthemeslider.slide.slideatend",function (e) {
    	console.log("slide at end");
    });</textarea>
                                <h4 style="margin-top:15px"><?php $modules->l("Layer Events") ?></h4>
                                <textarea class="api_area" style=" height:130px;" readonly>
    <?php echo $api ?>.bind("rbthemeslider.slide.layeraction",function (e) {
    	//data.eventtype - <?php $modules->l('Layer Action (enterstage, enteredstage, leavestage,leftstage)');

    ?>

    	//data.layertype - <?php $modules->l('Layer Type (image,video,html)');

    ?>

    	//data.layersettings - <?php $modules->l('Default Settings for Layer');

    ?>

    	//data.layer - <?php $modules->l('Layer as jQuery Object');

    ?>

    });</textarea>
                            </div>
                        </div>
                    </div>
    <?php }

?>	<!-- END OF API SETTINGS -->

            </div>



            <script type="text/javascript">
                jQuery(document).on("ready", function() {
                    /**
                     * set shadow type
                     */
                    // SHADOW TYPES
                    jQuery("#shadow_type").change(function() {
                        var sel = jQuery(this).val();

                        drawToolBarPreview();
                    });

                    // BACKGROUND IMAGE SCRIPT
                    jQuery('#show_background_image').on("change", function() {
                        var sbi = jQuery(this);
                        if (sbi.attr("checked") === "checked") {
                            jQuery('#background_settings').show();
                        } else {
                            jQuery('#background_settings').hide();
                        }
                    });
                    jQuery('#show_background_image').change();
                    jQuery('#padding').change(drawToolBarPreview).on("keyup", drawToolBarPreview);
                    jQuery('#background_dotted_overlay').change(drawToolBarPreview);

                    // POSITION SCRIPT
                    jQuery('#position').on("change", function() {
                        var sbi = jQuery(this);
                        switch (jQuery(this).val()) {
                            case "left":
                            case "right":
                                jQuery('#leftrightmargins').show();
                                break;
                            case "center":
                                jQuery('#leftrightmargins').hide();
                                break;
                        }
                        drawToolBarPreview();
                    });
                    jQuery('#position').change();

                    // SPINNER SCRIPT
                    jQuery('#use_spinner').on("change", function() {
                        switch (jQuery(this).val()) {
                            case "-1":
                            case "0":
                            case "5":
                                jQuery('#spinner_color_row').hide();
                                break;
                            default:
                                jQuery('#spinner_color_row').show();
                                break;
                        }
                    });
                    jQuery('#use_spinner').change();

                    // TAB CHANGES
                    jQuery('.main-options-small-tabs').find('li').click(function() {
                        var li = jQuery(this),
                                ul = li.closest('.main-options-small-tabs'),
                                ref = li.data('content');

                        jQuery(ul.find('.selected').data('content')).hide();
                        ul.find('.selected').removeClass("selected");

                        jQuery(ref).show();
                        li.addClass("selected");

                        if (ref == '#navigation-arrows' || ref == '#navigation-bullets' || ref == '#navigation-tabs' || ref == '#navigation-thumbnails')
                            jQuery('#navigation-miniimagedimensions').show();
                        else
                        if (!jQuery('#navigation-settings-wrapper>h3').hasClass("box_closed"))
                            jQuery('#navigation-miniimagedimensions').hide();
                    })
                });
            </script>

            <!-- THE TOOLBAR FUN -->
            <div id="form_toolbar">
                <div class="toolbar-title"></div>
                <div class="toolbar-content"></div>
                <!--<div class="toolbar-title-a">Schematic</div>				-->
                <!--<div class="toolbar-media"></div>-->
                <div class="toolbar-sliderpreview">
                    <div class="toolbar-slider">
                        <div class="toolbar-slider-image"></div>
                        <div class="toolbar-progressbar"></div>
                        <div class="toolbar-dottedoverlay"></div>
                        <div class="toolbar-navigation-right"></div>
                        <div class="toolbar-navigation-left"></div>
                        <div class="toolbar-navigation-bullets">
                            <div class="toolbar-navigation-bullet"></div>
                            <div class="toolbar-navigation-bullet"></div>
                            <div class="toolbar-navigation-bullet" style="margin:0px !important"></div>
                            <span class="tp-clearfix"></span>
                        </div>

                        <div class="toolbar-navigation-thumbs">
                            <div class="toolbar-navigation-thumb"></div>
                            <div style="border-color:#fff" class="toolbar-navigation-thumb"></div>
                            <div class="toolbar-navigation-thumb" style="margin:0px !important"></div>
                            <span class="toolbar-navigation-thumbs-bg tntb"></span>
                            <span class="tp-clearfix"></span>
                        </div>

                        <div class="toolbar-navigation-tabs">
                            <div class="toolbar-navigation-tab"><span class="long-lorem-ipsum"></span><span class="short-lorem-ipsum"></span></div>
                            <div style="border-color:#fff"  class="toolbar-navigation-tab"><span class="long-lorem-ipsum"></span><span class="short-lorem-ipsum"></span></div>
                            <div class="toolbar-navigation-tab" style="margin:0px !important"><span class="long-lorem-ipsum"></span><span class="short-lorem-ipsum"></span></div>
                            <span class="toolbar-navigation-tabs-bg tntb"></span>
                            <span class="tp-clearfix"></span>
                        </div>
                    </div>
                </div>
                <div id="preview-nav-wrapper">
                    <div class="rs-editing-preview-overlay"></div>
                    <div class="rs-arrows-preview">
                        <div class="tp-arrows tp-leftarrow"></div>
                        <div class="tp-arrows tp-rightarrow"></div>
                    </div>
                    <div class="rs-bullets-preview"></div>
                    <div class="rs-thumbs-preview"></div>
                    <div class="rs-tabs-preview"></div>
                </div>
                <div class="toolbar-extended-info"><i><?php $modules->l('*Only Illustration, most changes are not visible.'); ?></i></div>
            </div>
        </div>

        <div class="clear"></div>

    </div>

    <script type="text/javascript">
        /**
         *
         */
        // Some Inline Script for Right Side Panel Actions.
        jQuery(document).ready(function($) {

            UniteSettingsRb.createModernOnOff();

            jQuery(".tp-moderncheckbox").each(function() {
                UniteSettingsRb.onoffStatus(jQuery(this));
            });


            jQuery('#def-background_fit').change(function() {
                if (jQuery(this).val() == 'percentage') {
                    jQuery('input[name="def-bg_fit_x"]').show();
                    jQuery('input[name="def-bg_fit_y"]').show();
                } else {
                    jQuery('input[name="def-bg_fit_x"]').hide();
                    jQuery('input[name="def-bg_fit_y"]').hide();
                }
            });

            jQuery('#slide_bg_position').change(function() {
                if (jQuery(this).val() == 'percentage') {
                    jQuery('input[name="def-bg_position_x"]').show();
                    jQuery('input[name="def-bg_position_y"]').show();
                } else {
                    jQuery('input[name="def-bg_position_x"]').hide();
                    jQuery('input[name="def-bg_position_y"]').hide();
                }
            });

            jQuery('#slide_bg$modules->lnd_position').change(function() {
                if (jQuery(this).val() == 'percentage') {
                    jQuery('input[name="def-bg$modules->lnd_position_x"]').show();
                    jQuery('input[name="def-bg$modules->lnd_position_y"]').show();
                } else {
                    jQuery('input[name="def-bg$modules->lnd_position_x"]').hide();
                    jQuery('input[name="def-bg$modules->lnd_position_y"]').hide();
                }
            });

            jQuery('input[name="def-kenburn$modules->lffect"]').change(function() {
                if (jQuery(this).attr('checked') == 'checked') {
                    jQuery('#def-kenburns-wrapper').show();
                } else {
                    jQuery('#def-kenburns-wrapper').hide();
                }
            });



            // Accordion
            jQuery('.settings_wrapper').find('.setting_box h3').each(function() {
                jQuery(this).click(function() {
                    var btn = jQuery(this),
                            sb = jQuery(this).closest('.setting_box'),
                            toclose = btn.hasClass("box_closed") ? true : false;

                    if (btn.closest('.settings_wrapper').hasClass("closeallothers"))
                        btn.closest('.settings_wrapper').find('.setting_box').each(function() {
                            var sb = jQuery(this);
                            sb.find('h3').addClass("box_closed");
                            sb.find('.inside').slideUp(200);
                        });
                    else
                    {
                        sb.find('h3').addClass("box_closed");
                        sb.find('.inside').slideUp(200);
                    }

                    if (toclose) {
                        btn.removeClass("box_closed");
                        sb.find('.inside').slideDown(200);
                    }
                });
            });

            jQuery('.ps-color-result').on("click", function() {
                if (jQuery(this).hasClass("ps-picker-open"))
                    jQuery(this).closest('.ps-picker-container').addClass("pickerisopen");
                else
                    jQuery(this).closest('.ps-picker-container').removeClass("pickerisopen");
            });

            jQuery("body").click(function(event) {
                jQuery('.ps-picker-container.pickerisopen').removeClass("pickerisopen");
            })

            // PREPARE ON/OFF BUTTON
            jQuery('.tp-onoffbutton .withlabel').each(function() {
                var wl = jQuery(this),
                        tpo = wl.closest('.tp-onoffbutton');
                tpo.attr('label', wl.attr('id'));
                tpo.addClass("withlabel");
            })

            jQuery('.ps-picker-container .withlabel').each(function() {
                var wl = jQuery(this),
                        tpo = wl.closest('.ps-picker-container');
                tpo.attr('label', wl.attr('id'));
                tpo.addClass("withlabel");
            });

            var previewNav = function(sbut, mclass, the_css, the_markup, settings) {

                var ap = jQuery('#preview-nav-wrapper .rs-arrows-preview'),
                        bp = jQuery('#preview-nav-wrapper .rs-bullets-preview'),
                        tabp = jQuery('#preview-nav-wrapper .rs-tabs-preview'),
                        thumbp = jQuery('#preview-nav-wrapper .rs-thumbs-preview'),
                        sizer = jQuery('#preview-nav-wrapper .little-sizes');


                ap.html("");
                bp.html("");
                tabp.html("");
                thumbp.html("");

                ap.hide();
                bp.hide();
                tabp.hide();
                thumbp.hide();
                sizer.hide();

                var pattern = new RegExp(":hover", 'g');
                if (sbut == "arrows") {
                    ap.show();


                    var t = '<style>' + the_css.replace(pattern, '.fakehover') + '</style>';
                    t = t + '<div class="' + mclass + ' tparrows tp-leftarrow">' + the_markup + '</div>';
                    t = t + '<div class="' + mclass + ' tparrows tp-rightarrow">' + the_markup + '</div>';
                    ap.html(t);
                    setTimeout(function() {
                        try {
                            ap.find('.tp-rightarrow').addClass("fakehover");
                        } catch (e) {
                        }
                    }, 200);

                } else
                if (sbut == "bullets") {
                    bp.show();
                    var t = '<style>' + the_css + '</style>';
                    t = t + '<div class="' + mclass + ' tp-bullets">'
                    for (var i = 0; i < 5; i++) {
                        t = t + '<div class="tp-bullet">' + the_markup + '</div>';
                    }
                    t = t + '</div>';
                    bp.html(t);
                    var b = bp.find('.tp-bullet').first(),
                            bw = jQuery('#bullets_direction option:selected').attr("value") == "horizontal" ? b.outerWidth(true) : b.outerHeight(true),
                            bh = jQuery('#bullets_direction option:selected').attr("value") == "vertical" ? b.outerWidth(true) : b.outerHeight(true),
                            mw = 0;
                    bp.find('.tp-bullet').each(function(i) {
                        var e = jQuery(this);
                        if (i == 0)
                            setTimeout(function() {
                                try {
                                    e.addClass("selected");
                                } catch (e) {
                                }
                            }, 150);


                        var np = i * bw + i * 10;
                        if (jQuery('#bullets_direction option:selected').attr("value") == "horizontal") {
                            e.css({left: np + "px"});
                        } else {
                            e.css({top: np + "px"});
                        }

                        mw = mw + bw + 10;
                    })
                    mw = mw - 10;
                    if (jQuery('#bullets_direction option:selected').attr("value") == "horizontal") {
                        bp.find('.tp-bullets').css({width: mw, height: bh});
                    } else {
                        bp.find('.tp-bullets').css({height: mw, width: bh});
                    }

                } else
                if (sbut == "tabs") {
                    tabp.show();
                    var t = '<style>' + the_css + '</style>';
                    t = t + '<div class="' + mclass + '"><div class="tp-tab">' + the_markup + '</div></div>';
                    tabp.html(t);
                    var s = new Object();
                    s.w = 160,
                            s.h = 160;
                    if (settings != "" && settings != undefined) {
                        if (settings.width != undefined && settings.width.tabs != undefined)
                            s.w = settings.width.tabs;
                        if (settings.height != undefined && settings.height.tabs != undefined)
                            s.h = settings.height.tabs;
                    }
                    tabp.find('.tp-tab').each(function() {
                        jQuery(this).css({width: s.w + "px", height: s.h + "px"});
                    });
                    return s;

                } else
                if (sbut == "thumbs") {
                    thumbp.show();
                    var t = '<style>' + the_css + '</style>';
                    t = t + '<div class="' + mclass + '"><div class="tp-thumb">' + the_markup + '</div></div>';
                    thumbp.html(t);
                    var s = new Object();
                    s.w = 160,
                            s.h = 160;
                    if (settings != "" && settings != undefined) {
                        if (settings.width != undefined && settings.width.thumbs != undefined)
                            s.w = settings.width.thumbs;
                        if (settings.height != undefined && settings.height.thumbs != undefined)
                            s.h = settings.height.thumbs;
                    }
                    thumbp.find('.tp-thumb').each(function() {
                        jQuery(this).css({width: s.w + "px", height: s.h + "px"});
                    });
                    return s;
                }

            }

            function changeNavStyle(navtype) {
                var cur$modules->ldit = {},
                        cur$modules->ldit_type,
                        navtype,
                        nav_id,
                        mclass = "";

                if (navtype == "arrows")
                    cur$modules->ldit_type = jQuery('#navigation_arrow_style option:selected').attr('value');
                else
                if (navtype == 'bullets')
                    cur$modules->ldit_type = jQuery('#navigation_bullets_style option:selected').attr('value');
                else
                if (navtype == 'tabs')
                    cur$modules->ldit_type = jQuery('#tabs_style option:selected').attr('value');
                else
                if (navtype == 'thumbs')
                    cur$modules->ldit_type = jQuery('#thumbnails_style option:selected').attr('value');

                for (var key in rs_navigations) {
                    if (rs_navigations[key]['handle'] == cur$modules->ldit_type) {
                        cur$modules->ldit = jQuery.extend(true, {}, rs_navigations[key]);
                        break;
                    }
                }

                var the_css = (typeof(cur$modules->ldit['css']) !== 'undefined' && cur$modules->ldit['css'] !== null && typeof(cur$modules->ldit['css'][navtype]) !== 'undefined') ? cur$modules->ldit['css'][navtype] : '',
                        the_markup = (typeof(cur$modules->ldit['markup']) !== 'undefined' && cur$modules->ldit['markup'] !== null && typeof(cur$modules->ldit['markup'][navtype]) !== 'undefined') ? cur$modules->ldit['markup'][navtype] : "",
                        settings = (typeof(cur$modules->ldit['settings']) !== 'undefined' && cur$modules->ldit['settings'] !== null) ? cur$modules->ldit['settings'] : "";

                if (cur$modules->ldit["name"] == undefined)
                    return false;
                var mclass = UniteAdminRb.sanitize_input(cur$modules->ldit["name"].toLowerCase());


                if (cur$modules->ldit['css'] == null)
                    return false;
                if (cur$modules->ldit['markup'] == null)
                    return false;


                return previewNav(navtype, mclass, the_css, the_markup, settings);

            }
            // THE AMAZING TOOL TIP FUN
            jQuery('#form_slider_params_wrap form').find('.label, .withlabel').each(function() {
                var lbl = jQuery(this);

                lbl.hover(function() {
                    drawToolBarPreview();

                    var lbl = jQuery(this).hasClass("withlabel") ? (jQuery(this).attr('id') === undefined ? jQuery("#label_" + jQuery(this).attr("label")) : jQuery("#label_" + jQuery(this).attr("id"))) : jQuery(this),
                            ft = jQuery('#form_slider_params_wrap form').offset().top;
                    tb = jQuery('#form_toolbar'),
                            title = tb.find('.toolbar-title'),
                            cont = tb.find('.toolbar-content'),
                            med = tb.find('.toolbar-media'),
                            prev = tb.find('.toolbar-sliderpreview'),
                            shads = tb.find('.toolbar-shadows'),
                            img = tb.find('.toolbar-slider-image'),
                            exti = tb.find('.toolbar-extended-info'),
                            navpre = jQuery('#preview-nav-wrapper');

                    title.html(lbl.html());
                    cont.html(lbl.attr('origtitle'));

                    if (lbl.attr('origmedia') == "show" || lbl.attr('origmedia') == "showbg") {
                        prev.slideDown(150);
                        shads.slideDown(150);
                    }

                    if (lbl.attr('origmedia') == "showbg")
                        img.addClass('shownowbg');
                    else
                        img.removeClass('shownowbg');

                    var topp = (lbl.offset().top - ft - 14),
                            hh = tb.outerHeight(),
                            so = jQuery(document).scrollTop(),
                            foff = jQuery('#form_slider_params_wrap form').offset().top,
                            wh = jQuery(window).height(),
                            diff = (so + wh - foff) - (topp + hh);

                    if (diff < 0)
                        topp = topp + diff;


                    if (lbl.hasClass("triggernavstyle")) {

                        prev.hide();
                        navpre.show();
                        punchgs.TweenLite.set(navpre, {autoAlpha: 1});
                        var e = jQuery(this);
                        if (e.closest('#nav_arrows_subs').length > 0)
                            navtype = 'arrows';
                        else
                        if (e.closest('#nav_bullets_subs').length > 0)
                            navtype = 'bullets';
                        else
                        if (e.closest('#nav_tabs_subs').length > 0)
                            navtype = 'tabs';
                        else
                        if (e.closest('#nav_thumbnails_subs').length > 0)
                            navtype = 'thumbs';

                        var s = changeNavStyle(navtype, jQuery(this));
                        if (s != undefined)
                            cont.html("Suggested Size:" + s.w + " x " + s.h + "px");
                        else
                            cont.hide();
                    } else {
                        cont.show();
                        prev.show();
                        navpre.hide();
                    }
                    punchgs.TweenLite.to(tb, 0.5, {autoAlpha: 1, right: "100%", top: topp, ease: punchgs.Power3.easeOut, overwrite: "all"});


                }, function() {
                    var lbl = jQuery(this);
                    tb = jQuery('#form_toolbar');
                    punchgs.TweenLite.to(tb, 0.2, {autoAlpha: 0, ease: punchgs.Power3.easeOut, delay: 0.5});

                });
            });

            jQuery('#navigation_arrow_style, #navigation_bullets_style, #tabs_style, #thumbnails_style').on("change", function() {
                var e = jQuery(this),
                        tb = jQuery('#form_toolbar'),
                        title = tb.find('.toolbar-title'),
                        cont = tb.find('.toolbar-content');

                if (e.closest('#nav_arrows_subs').length > 0)
                    navtype = 'arrows';
                else
                if (e.closest('#nav_bullets_subs').length > 0)
                    navtype = 'bullets';
                else
                if (e.closest('#nav_tabs_subs').length > 0)
                    navtype = 'tabs';
                else
                if (e.closest('#nav_thumbnails_subs').length > 0)
                    navtype = 'thumbs';

                var s = changeNavStyle(navtype, jQuery(this));
                if (s != undefined)
                    cont.html("Suggested Size:" + s.w + " x " + s.h + "px");
                else
                    cont.hide();
            })
            var rs_navigations = jQuery.parseJSON(<?php echo UniteFunctionsRb::jsonEncodeForClientSide($arr_navigations); ?>);


            var googlef_template_container = ps.template("rs-preset-googlefont");

            jQuery('#add_new_google_font').click(function() {
                var content = googlef_template_container({'value': ''});
                jQuery('#rs-google-fonts').append(content);
            });

            jQuery('body').on('click', '.rs-google-remove-field', function() {
                jQuery(this).parent().remove();
            });
        });
    </script>

</div>

<?php require self::getPathTemplate("dialog_preview_slider"); ?>



<!-- PRESET SAVING DIALOG -->
<div id="dialog-rs-add-new-setting-presets" title="<?php $modules->l('Save Settings as Preset'); ?>" style="display:none;">
    <div class="settings_wrapper unite_settings_wide">
        <p><label><?php $modules->l('Preset Name'); ?></label> <input type="text" name="rs-preset-name" /></p>
        <p><label><?php $modules->l('Select Image'); ?></label> <input type="button" value="<?php $modules->l('Select'); ?>" name="rs-button-select-img" /></p>
        <input type="hidden" name="rs-preset-image-id" value="" />
        <div id="rs-preset-img-wrapper">

        </div>
    </div>
</div>

<script type="text/html" id="tmpl-rs-preset-container">
    <span class="rs-preset-selector rs-preset-entry {{ data['type'] }} {{ data['class'] }} " id="rs-preset-{{ data['key'] }}">
    <span class="rs-preset-image"<# if( data['img'] !== '' ){ #> style="background-image: url({{ data['img'] }});"<# } #>>
    <# if( data['custom'] == true ){ #><span class="rb-update-preset"><i class="rbicon-pencil-1"></i></span><span class="rb-remove-preset"><i class="rbicon-cancel"></i></span><# } #>
    </span>
    <span class="rs-preset-label">{{ data['name'] }}</span>
    </span>
</script>

<script type="text/html" id="tmpl-rs-preset-googlefont">
    <div>
    <span class="label" style="min-width:100px" origtitle="<?php $modules->l("Google Font String"); ?>"><?php $modules->l("Font") ?>:</span>
    <input type="text" style="width:180px" name="google_font[]" value="{{ data['value'] }}">
    <a class="button-primary rbred rs-google-remove-field" original-title=""><i class="rbicon-trash"></i></a>
    <div class="tp-clearfix"></div>
    </div>
</script>

<?php
