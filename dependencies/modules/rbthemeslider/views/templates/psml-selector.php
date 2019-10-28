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

if (!defined('ABSPATH')) {
    exit();
}

$modules = new Rbthemeslider();

$urlIconDelete = RS_PLUGIN_URL."views/img/images/icon-trash.png";
$urlIconEdit = RS_PLUGIN_URL."views/img/images/icon-edit.png";
$urlIconPreview = RS_PLUGIN_URL."views/img/images/preview.png";

$textDelete = $modules->l("Delete Slide");
$textEdit = $modules->l("Edit Slide");
$textPreview = $modules->l("Preview Slide");

$htmlBefore = "";
$htmlBefore .= "<li class='item_operation operation_delete'><a data-operation='delete' href='javascript:void(0)'>"."\n";
$htmlBefore .= "<img src='".$urlIconDelete."'/> ".$textDelete."\n";
$htmlBefore .= "</a></li>"."\n";

$htmlBefore .= "<li class='item_operation operation_edit'><a data-operation='edit' href='javascript:void(0)'>"."\n";
$htmlBefore .= "<img src='".$urlIconEdit."'/> ".$textEdit."\n";
$htmlBefore .= "</a></li>"."\n";

$htmlBefore .= "<li class='item_operation operation_preview'><a data-operation='preview' href='javascript:void(0)'>"."\n";
$htmlBefore .= "<img src='".$urlIconPreview."'/> ".$textPreview."\n";
$htmlBefore .= "</a></li>"."\n";

$htmlBefore .= "<li class='item_operation operation_sap'>"."\n";
$htmlBefore .= "<div class='float_menu_sap'></div>"."\n";
$htmlBefore .= "</a></li>"."\n";

$langFloatMenu = RbSliderPsml::getLangsWithFlagsHtmlList("id='slides_langs_float' class='slides_langs_float'", $htmlBefore);
$slide = RbGlobalObject::getVar('slide');
$arrChildLangs = RbGlobalObject::getVar('arrChildLangs');
$slideID = RbGlobalObject::getVar('slideID');
?>
<div id="langs_float_wrapper" class="langs_float_wrapper" style="display:none">
<?php echo $langFloatMenu; ?>
</div>

<div id="rb_lang_list">
	<div class="slide_langs_selector editor_buttons_wrapper  postbox unite-postbox" style="margin-bottom:20px; max-width:100% !important; min-width:1040px !important;">
		<div class="slide-main-settings-form" style="padding:15px;">
			
			<label style="display:inline-block; margin-right:15px;"><?php echo $modules->l("Choose slide language"); ?>:</label>
			
			<ul class="list_slide_icons" style="display:inline-block; vertical-align: middle; margin-bottom:0px;">
				<?php
                $langSlide = $slide->getParentSlide();
                $arrSlideLangCodes = $langSlide->getArrChildLangCodes();
                $parent_id = $langSlide->getID();
                
                $addItemStyle = "";
                if (RbSliderPsml::isAllLangsInArray($arrSlideLangCodes)) {
                    $addItemStyle = "style='display:none'";
                }
                    
                foreach ($arrChildLangs as $arrLang) {
                    $isParent = RbSliderFunctions::boolToStr($arrLang["isparent"]);
                    $childSlideID = $arrLang["slideid"];
                    $lang = $arrLang["lang"];
                    $urlFlag = RbSliderPsml::getFlagUrl($lang);
                    $langTitle = RbSliderPsml::getLangTitle($lang);
                    $class = "";
                    $urlEditSlide = self::getViewUrl(RbSliderAdmin::VIEW_SLIDE, "id=$childSlideID");
                    if ($childSlideID == $slideID) {
                        $class = "lang-selected";
                        $urlEditSlide = "javascript:void(0)";
                    }
                    if ($lang == 'all') {
                        $urlFlag = RS_PLUGIN_URL.'views/img/images/icon-all.png';
                    }
                    ?>
					<li class="<?php echo $class;
                    ?>">
						<img id="icon_lang_<?php echo $childSlideID;
                    ?>" class="icon_slide_lang" src="<?php echo $urlFlag;
                    ?>" title="<?php echo $langTitle;
                    ?>" data-slideid="<?php echo $childSlideID;
                    ?>" data-lang="<?php echo $lang;
                    ?>" data-isparent="<?php echo $isParent;
                    ?>">
						<div class="icon_lang_loader loader_round" style="display:none"></div>
					</li>
					<?php

                }
                
                ?>
				<li>
					<div id="icon_add_lang_<?php echo $slideID; ?>" class="icon_slide_lang_add" data-operation="add" data-slideid="<?php echo $slideID; ?>" data-origid="<?php echo $parent_id; ?>" <?php echo $addItemStyle; ?>></div>
					<div class="icon_lang_loader loader_round" style="display:none"></div>
				</li>
			</ul>
		</div>
	</div>
</div>

<?php
