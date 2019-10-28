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

if (!RbGlobalObject::getVar('outputTemplates')) {
    $limit = ((int) (Tools::getValue('limit')) > 0) ? (int) (Tools::getValue('limit')) : 500;
    $otype = 'reg';
} else {
    $limit = ((int) (Tools::getValue('limit_t')) > 0) ? (int) (Tools::getValue('limit_t')) : 500;
    $otype = 'temp';
}

$total = 0;

?>
<ul class="tp-list_sliders">
    <?php
    if (!RbGlobalObject::getVar('no_sliders')) {
        $useSliders = RbGlobalObject::getVar('arrSliders');

        foreach ($useSliders as $slider) {
            try {
                $errorMessage = '';
                $id = $slider->getID();
                $showTitle = $slider->getShowTitle();
                $title = $slider->getTitle();
                $alias = $slider->getAlias();
                $isFromStream = $slider->isSlidesFromStream();
                $strSource = $modules->l('Gallery');
                $preicon = "rbicon-picture-1";

                // TODO: Work on it later.
                $is_favorite = $slider->isFavorite();
                $numSlides = $slider->getNumSlides();
                $numReal = '';
                $isFromPosts = $slider->isSlidesFromPosts();
                $rowClass = "";
                $slider_type = 'gallery';

                if ($isFromPosts == true) {
                    $strSource = $modules->l('Products');
                    $preicon = "rbicon-doc";
                    $rowClass = "class='row_alt'";
                    $numReal = $slider->getNumRealSlides();
                    $slider_type = 'posts';
                } elseif ($isFromStream !== false) {
                    $strSource = $modules->l('Social');
                    $preicon = "rbicon-doc";
                    $rowClass = "class='row_alt'";

                    switch ($isFromStream) {
                        case 'facebook':
                            $strSource = $modules->l('Facebook');
                            $preicon = "eg-icon-facebook";
                            $numReal = $slider->getNumRealSlides(false, 'facebook');
                            $slider_type = 'facebook';
                            break;
                        case 'twitter':
                            $strSource = $modules->l('Twitter');
                            $preicon = "eg-icon-twitter";
                            $numReal = $slider->getNumRealSlides(false, 'twitter');
                            $slider_type = 'twitter';
                            break;
                        case 'instagram':
                            $strSource = $modules->l('Instagram');
                            $preicon = "eg-icon-info";
                            $numReal = $slider->getNumRealSlides(false, 'instagram');
                            $slider_type = 'instagram';
                            break;
                        case 'flickr':
                            $strSource = $modules->l('Flickr');
                            $preicon = "eg-icon-flickr";
                            $numReal = $slider->getNumRealSlides(false, 'flickr');
                            $slider_type = 'flickr';
                            break;
                        case 'youtube':
                            $strSource = $modules->l('YouTube');
                            $preicon = "eg-icon-youtube";
                            $numReal = $slider->getNumRealSlides(false, 'youtube');
                            $slider_type = 'youtube';
                            break;
                        case 'vimeo':
                            $strSource = $modules->l('Vimeo');
                            $preicon = "eg-icon-vimeo";
                            $numReal = $slider->getNumRealSlides(false, 'vimeo');
                            $slider_type = 'vimeo';
                            break;
                    }
                }

                $first_slide_image_thumb = array('url' => '', 'class' => 'mini-transparent', 'style' => '');

                if ((int) ($numSlides) == 0) {
                    $first_slide_id = 'new&slider=' . $id;
                } else {
                    $slides = $slider->getSlides(false);

                    if (!empty($slides)) {
                        $first_slide_id = $slides[key($slides)]->getID();

                        $first_slide_image_thumb = $slides[key($slides)]->getImageAttributes($slider_type);
                    } else {
                        $first_slide_id = 'new&slider=' . $id;
                    }
                }

                $editLink = self::getViewUrl(RbSliderAdmin::VIEW_SLIDER, "id=$id");
                $editSlidesLink = self::getViewUrl(RbSliderAdmin::VIEW_SLIDE, "id=$first_slide_id");
                $showTitle = UniteFunctionsRb::getHtmlLink($editLink, $showTitle);
            } catch (Exception $e) {
                $errorMessage = "ERROR: " . $e->getMessage();
                $strSource = "";
                $numSlides = "";
            }

            ?>
            <li class="tls-slide tls-stype-all tls-stype-<?php echo $slider_type;

            ?>" data-favorit="<?php //echo ($is_favorite) ? 'a' : 'b';  ?>" data-id="<?php echo $id;

            ?>" data-name="<?php echo $title;

            ?>" data-type="<?php echo $slider_type;

        ?>">
                <div class="tls-main-metas">
                    <span class="tls-firstslideimage <?php echo $first_slide_image_thumb['class'];

            ?>" style="<?php echo $first_slide_image_thumb['style'];

            ?>;<?php if (!empty($first_slide_image_thumb['url'])) {

                      ?>background-image:url( <?php echo $first_slide_image_thumb['url'];

                      ?>) <?php
                  }

            ?>"></span>
                    <a href="<?php echo $editSlidesLink;

            ?>" class="tls-grad-bg tls-bg-top"></a>
                    <span class="tls-source"><?php echo "<i class=" . $preicon . "></i>" . $strSource;

            ?></span>
                    <span class="tls-star"><a href="javascript:void(0);" class="rb-toogle-fav" id="reg-toggle-id-<?php echo $id;

                ?>"><i class="eg-icon-star<?php echo ($is_favorite) ? '' : '-empty';

            ?>"></i></a></span>
                    <span class="tls-slidenr"><?php
                          echo $numSlides;
                          if ($numReal !== '') {
                              echo ' (' . $numReal . ')';
                          }

                          ?></span>

                    <span class="tls-title-wrapper">
                        <span class="tls-id">#<?php echo $id;

                          ?><span id="slider_title_<?php echo $id;

            ?>" class="hidden"><?php echo $title;

            ?></span><span class="tls-alias hidden" ><?php echo $alias;

                   ?></span></span>
                        <span class="tls-title"><?php echo $showTitle;

                   ?>
        <?php if (!empty($errorMessage)) {

            ?>
                                <span class='error_message'><?php echo $errorMessage;

            ?></span>
                        <?php
                    }

                    ?>
                        </span>
                        <a class="button-primary tls-settings" href='<?php echo $editLink;

                    ?>'><i class="rbicon-cog"></i></a>
                        <a class="button-primary tls-editslides" href='<?php echo $editSlidesLink;

                    ?>'><i class="rbicon-pencil-1"></i></a>
                        <span class="button-primary tls-showmore"><i class="eg-icon-down-open"></i></span>

                    </span>

                </div>

                <div class="tls-hover-metas">
                    <span class="button-primary rs-embed-slider" ><i class="eg-icon-plus"></i><?php echo $modules->l('Embed_Slider');

               ?></span>
                    <a class="button-primary  export_slider_overview" id="export_slider_<?php echo $id;

               ?>" href="javascript:void(0);" ><i class="rbicon-export"></i><?php echo $modules->l('Export');

                    ?></a>	

        <?php
        $operations = new RbOperations();
        $general_settings = $operations->getGeneralSettingsValues();
        $show_dev_export = UniteBaseClassRb::getVar($general_settings, 'show_dev_export', 'off');

        if ($show_dev_export == 'on') {

            ?>
                        <a class="button-primary  export_slider_standalone" id="export_slider_standalone_<?php echo $id;

            ?>" href="javascript:void(0);" ><i class="rbicon-export"></i><?php echo $modules->l('Export to HTML');

            ?></a>
            <?php
        }

        ?>

                    <a class="button-primary  button_delete_slider" id="button_delete_<?php echo $id;

        ?>" href='javascript:void(0)'><i class="rbicon-trash"></i><?php echo $modules->l('Delete');

        ?></a>
                    <a class="button-primary  button_duplicate_slider" id="button_duplicate_<?php echo $id;

        ?>" href='javascript:void(0)'><i class="rbicon-picture"></i><?php echo $modules->l('Duplicate');

        ?></a>
                    <div id="button_preview_<?php echo $id;

        ?>" class="button_slider_preview button-primary rbgray"><i class="rbicon-search-1"></i><?php echo $modules->l('Preview');

        ?></div>
                </div>

                <div class="tls-dimmme"></div>
            </li>
        <?php
    }
}

?>	


    <li class="tls-slide tls-addnewslider">
        <a href='<?php echo RbGlobalObject::getVar('addNewLink'); ?>'>
            <span class="tls-main-metas">
                <span class="tls-new-icon-wrapper">
                    <span class="slider_list_add_buttons add_new_slider_icon">
                        <i class="material-icons">add</i>
                    </span>
                </span>
                <span class="tls-title-wrapper">			
                    <span class="tls-title"><?php echo $modules->l('New_Slider'); ?></span>					
                </span>
            </span>
        </a>
    </li>
    <li class="tls-slide tls-addnewslider" style="display:none">
        <a href="javascript:void(0);" id="button_import_template_slider">
            <span class="tls-main-metas">
                <span class="tls-new-icon-wrapper add_new_template_icon_wrapper">
                    <i class="slider_list_add_buttons add_new_template_icon"></i>
                </span>
                <span class="tls-title-wrapper">			
                    <span class="tls-title"><?php echo $modules->l('Add Slider Template'); ?></span>					
                </span>
            </span>
        </a>
    </li>
    <li class="tls-slide tls-addnewslider">
        <a href="javascript:void(0);" id="button_import_slider">
            <span class="tls-main-metas">
                <span class="tls-new-icon-wrapper">
                    <i class="material-icons slider_list_add_buttons add_new_import_icon">import_export</i>
                </span>
                <span class="tls-title-wrapper">			
                    <span class="tls-title"><?php echo $modules->l('Import_Slider'); ?></span>					
                </span>
            </span>
        </a>		
    </li>
</ul>
<?php require_once self::getPathTemplate("dialog_preview_slider"); ?>


<script>
    jQuery(document).on("ready", function() {
        jQuery('.tls-showmore').click(function() {
            jQuery(this).closest('.tls-slide').find('.tls-hover-metas').show();
            var elements = jQuery('.tls-slide:not(.hovered) .tls-dimmme');
            punchgs.TweenLite.to(elements, 0.5, {autoAlpha: 0.6, overwrite: "all", ease: punchgs.Power3.easeInOut});
            punchgs.TweenLite.to(jQuery(this).find('.tls-dimmme'), 0.3, {autoAlpha: 0, overwrite: "all", ease: punchgs.Power3.easeInOut})
        })

        jQuery('.tls-slide').hover(function() {
            jQuery(this).addClass("hovered");
        }, function() {
            var elements = jQuery('.tls-slide .tls-dimmme');
            punchgs.TweenLite.to(elements, 0.5, {autoAlpha: 0, overwrite: "auto", ease: punchgs.Power3.easeInOut});
            jQuery(this).removeClass("hovered");
            jQuery(this).find('.tls-hover-metas').hide();
        });


    })

    jQuery('#filter-sliders').on("change", function() {
        jQuery('.tls-slide').hide();
        jQuery('.tls-stype-' + jQuery(this).val()).show();
        jQuery('.tls-addnewslider').show();
    })

    function sort_li(a, b) {
        return (jQuery(b).data(jQuery('#sort-sliders').val())) < (jQuery(a).data(jQuery('#sort-sliders').val())) ? 1 : -1;
    }

    jQuery('#sort-sliders').on('change', function() {
        jQuery(".tp-list_sliders li").sort(sort_li).appendTo('.tp-list_sliders');
        jQuery('.tls-addnewslider').appendTo('.tp-list_sliders');
    });

    jQuery('.slider-lg-views').click(function() {
        var tls = jQuery('.tp-list_sliders'),
                t = jQuery(this);
        jQuery('.slider-lg-views').removeClass("active");
        jQuery(this).addClass("active");
        tls.removeClass("rs-listview");
        tls.removeClass("rs-gridview");
        tls.addClass(t.data('type'));
    })
</script>
<?php
