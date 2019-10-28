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
<div class="postbox box-slideslist">
    <h3>
        <span class='slideslist-title'><?php echo $modules->l('Slides List'); ?></span>
        <span id="saving_indicator" class='slideslist-loading'><?php echo $modules->l('Saving Order'); ?>...</span>
    </h3>
    <div class="inside">
        <?php
        $arrSlides = RbGlobalObject::getVar('arrSlides');
        $slider = RbGlobalObject::getVar('slider');
        if (empty($arrSlides)):

            ?>
            <?php echo Rbthemeslider::$lang['No_Slides_Found']; ?>
        <?php endif ?>

        <?php
        $useStaticLayers = $slider->getParam("enable_static_layers", "off");
        RbGlobalObject::setVar('useStaticLayers', $useStaticLayers);

        ?>
        <ul id="list_slides" class="list_slides ui-sortable">

            <?php
            $counter = 0;
            foreach ($arrSlides as $slide):

                $counter++;

                $bgType = $slide->getParam("background_type", "image");

                $bgFit = $slide->getParam("bg_fit", "cover");
                $bgFitX = (int) ($slide->getParam("bg_fit_x", "100"));
                $bgFitY = (int) ($slide->getParam("bg_fit_y", "100"));

                $bgPosition = $slide->getParam("bg_position", "center top");
                $bgPositionX = (int) ($slide->getParam("bg_position_x", "0"));
                $bgPositionY = (int) ($slide->getParam("bg_position_y", "0"));

                $bgRepeat = $slide->getParam("bg_repeat", "no-repeat");

                $bgStyle = ' ';
                if ($bgFit == 'percentage') {
                    $bgStyle .= "background-size: " . $bgFitX . '% ' . $bgFitY . '%;';
                } else {
                    $bgStyle .= "background-size: " . $bgFit . ";";
                }
                if ($bgPosition == 'percentage') {
                    $bgStyle .= "background-position: " . $bgPositionX . '% ' . $bgPositionY . '%;';
                } else {
                    $bgStyle .= "background-position: " . $bgPosition . ";";
                }
                $bgStyle .= "background-repeat: " . $bgRepeat . ";";


                //set language flag url
                $isPsmlExists = UnitePsmlRb::isPsmlExists();
                $usePsml = $slider->getParam("use_psml", "off");
                $showLangs = false;
                if ($isPsmlExists && $usePsml == "on") {
                    $showLangs = true;
                    $arrChildLangs = $slide->getArrChildrenLangs();
                    $arrSlideLangCodes = $slide->getArrChildLangCodes();
                    RbGlobalObject::setVar('arrChildLangs', $arrChildLangs);
                    $addItemStyle = "";
                    if (UnitePsmlRb::isAllLangsInArray($arrSlideLangCodes)) {
                        $addItemStyle = "style='display:none'";
                    }
                }

                $imageFilepath = $slide->getImageFilepath();
                $urlImageForView = $slide->getThumbUrl();

                $slideTitle = $slide->getParam("title", "Slide");
                $title = $slideTitle;
                $filename = $slide->getImageFilename();

                $imageAlt = Tools::stripslashes($slideTitle);
                if (empty($imageAlt)) {
                    $imageAlt = "slide";
                }

                if ($bgType == "image") {
                    $title .= " (" . $filename . ")";
                }

                $slideid = $slide->getID();

                $urlEditSlide = self::getViewUrl(RbSliderAdmin::VIEW_SLIDE, "id=$slideid");
                $linkEdit = UniteFunctionsRb::getHtmlLink($urlEditSlide, $title);

                $state = $slide->getParam("state", "published");

                ?>
                <li id="slidelist_item_<?php echo $slideid ?>" class="ui-state-default">

                    <span class="slide-col col-order">
                        <span class="order-text"><?php echo $counter ?></span>
                        <div class="state_loader" style="display:none;"></div>
                        <?php if ($state == "published"): ?>
                            <div class="icon_state state_published" data-slideid="<?php echo $slideid ?>" title="<?php echo $modules->l('Unpublish Slide'); ?>"></div>
    <?php else: ?>
                            <div class="icon_state state_unpublished" data-slideid="<?php echo $slideid ?>" title="<?php echo $modules->l('Publish Slide'); ?>"></div>
    <?php endif ?>

                        <div class="icon_slide_preview" title="<?php echo $modules->l('Preview Slide'); ?>" data-slideid="<?php echo $slideid ?>"></div>

                    </span>

                    <span class="slide-col col-name">
                        <div class="slide-title-in-list"><?php echo $linkEdit ?></div>
                        <a class='button-primary rbgreen' href='<?php echo $urlEditSlide ?>' style="width:120px; "><i class="rbicon-pencil-1"></i><?php echo $modules->l('Edit Slide'); ?></a>
                    </span>
                    <span class="slide-col col-image">
                        <?php
                        switch ($bgType):
                            default:
                            case "image":

                                ?>
                                <div id="slide_image_<?php echo $slideid ?>" style="background-image:url('<?php echo $urlImageForView ?>');<?php echo $bgStyle; ?>" class="slide_image" title="Slide Image - Click to change"></div>
                                <?php
                                break;
                            case "solid":
                                $bgColor = $slide->getParam("slide_bg_color", "#d0d0d0");

                                ?>
                                <div class="slide_color_preview" style="background-color:<?php echo $bgColor ?>"></div>
                                <?php
                                break;
                            case "trans":

                                ?>
                                <div class="slide_color_preview_trans"></div>
            <?php
            break;
    endswitch;

    ?>
                    </span>

                    <span class="slide-col col-operations">
                        <a id="" class='button-primary revred button_delete_slide ' style="width:120px; margin-top:8px !important" data-slideid="<?php echo $slideid ?>" href='javascript:void(0)'><i class="rbicon-trash"></i><?php echo $modules->l('Delete'); ?></a>
                        <span class="loader_round loader_delete" style="display:none;"><?php echo $modules->l('Deleting Slide'); ?></span>
                        <a id="button_duplicate_slide_<?php echo $slideid ?>" style="width:120px; " class='button-primary rbyellow button_duplicate_slide' href='javascript:void(0)'><i class="rbicon-picture"></i><?php echo $modules->l('Duplicate'); ?></a>
                        <?php
                            $copyButtonClass = "button-primary rbblue  button_copy_slide";
                            $copyButtonTitle = $modules->l('Copy Move Dialog');
                            $numSliders = RbGlobalObject::getVar('numSliders');

                            if ($numSliders == 0) {
                                $copyButtonClass .= " button-disabled";
                                $copyButtonTitle = $modules->l('Copy Move Found');
                            }
                        ?>
                        <a id="button_copy_slide_<?php echo $slideid ?>" class='<?php echo $copyButtonClass ?>' title="<?php echo $copyButtonTitle ?>" style="width:120px; " href='javascript:void(0)'><i class="rbicon-picture"></i><?php echo $modules->l('Copy Move'); ?></a>							
                        <span class="loader_round loader_copy mtop_10 mleft_20 display_block" style="display:none;"><?php echo $modules->l('Working'); ?></span>
                    </span>

                    <span class="slide-col col-handle">
                        <div class="col-handle-inside">
                            <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                        </div>
                    </span>	
                    <div class="clear"></div>
    <?php if ($showLangs == true): ?>
                        <ul class="list_slide_icons">
                            <?php
                            foreach ($arrChildLangs as $arrLang):
                                $isParent = UniteFunctionsRb::boolToStr($arrLang["isparent"]);
                                $childSlideID = $arrLang["slideid"];
                                $lang = $arrLang["lang"];
                                $urlFlag = UnitePsmlRb::getFlagUrl($lang);
                                $langTitle = UnitePsmlRb::getLangTitle($lang);

                                ?>
                                <li>
                                    <img id="icon_lang_<?php echo $childSlideID ?>" class="icon_slide_lang" src="<?php echo $urlFlag ?>" title="<?php echo $langTitle ?>" data-slideid="<?php echo $childSlideID ?>" data-lang="<?php echo $lang ?>" data-isparent="<?php echo $isParent ?>">
                                    <div class="icon_lang_loader loader_round" style="display:none"></div>								
                                </li>
        <?php endforeach ?>
                            <li>
                                <div id="icon_add_lang_<?php echo $slideid ?>" class="icon_slide_lang_add" data-operation="add" data-slideid="<?php echo $slideid ?>" <?php echo $addItemStyle ?>></div>
                                <div class="icon_lang_loader loader_round" style="display:none"></div>
                            </li>
                        </ul>						
    <?php endif ?>
                </li>
<?php endforeach; ?>
        </ul>

    </div>
</div>

<?php
