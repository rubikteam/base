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

class RbSliderSettingsProduct extends UniteSettingsRbProductRb
{
    public function __construct()
    {
        $this->modules = new Rbthemeslider();
    }

    public static function setSettingsCustomValues(
        UniteSettingsRb $settings,
        $arrValues,
        $postTypesWithCats = false
    ) {
        $arrSettings = $settings->getArrSettings();

        foreach ($arrSettings as $key => $setting) {
            $type = UniteFunctionsRb::getVal($setting, "type");

            if ($type != UniteSettingsRb::TYPE_CUSTOM) {
                continue;
            }

            $customType = UniteFunctionsRb::getVal($setting, "custom_type");

            switch ($customType) {
                case "slider_size":
                    $setting["width"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        "width",
                        UniteFunctionsRb::getVal($setting, "width")
                    );

                    $setting["height"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        "height",
                        UniteFunctionsRb::getVal($setting, "height")
                    );

                    $arrSettings[$key] = $setting;

                    break;

                case "responsitive_settings":

                    $id = $setting["id"];

                    $setting["w1"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        $id . "_w1",
                        UniteFunctionsRb::getVal($setting, "w1")
                    );

                    $setting["w2"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        $id . "_w2",
                        UniteFunctionsRb::getVal($setting, "w2")
                    );

                    $setting["w3"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        $id . "_w3",
                        UniteFunctionsRb::getVal($setting, "w3")
                    );

                    $setting["w4"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        $id . "_w4",
                        UniteFunctionsRb::getVal($setting, "w4")
                    );

                    $setting["w5"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        $id . "_w5",
                        UniteFunctionsRb::getVal($setting, "w5")
                    );

                    $setting["w6"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        $id . "_w6",
                        UniteFunctionsRb::getVal($setting, "w6")
                    );

                    $setting["sw1"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        $id . "_sw1",
                        UniteFunctionsRb::getVal($setting, "sw1")
                    );

                    $setting["sw2"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        $id . "_sw2",
                        UniteFunctionsRb::getVal($setting, "sw2")
                    );

                    $setting["sw3"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        $id . "_sw3",
                        UniteFunctionsRb::getVal($setting, "sw3")
                    );

                    $setting["sw4"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        $id . "_sw4",
                        UniteFunctionsRb::getVal($setting, "sw4")
                    );

                    $setting["sw5"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        $id . "_sw5",
                        UniteFunctionsRb::getVal($setting, "sw5")
                    );

                    $setting["sw6"] = UniteFunctionsRb::getVal(
                        $arrValues,
                        $id . "_sw6",
                        UniteFunctionsRb::getVal($setting, "sw6")
                    );

                    $arrSettings[$key] = $setting;

                    break;
            }
        }

        $settings->setArrSettings($arrSettings);
        $sliderType = $settings->getSettingValue("slider_type");

        switch ($sliderType) {

            case "fixed":

            case "fullwidth":

            case "fullscreen":
                $settingRes = $settings->getSettingByName("responsitive");
                $settingRes["disabled"] = true;
                $settings->updateArrSettingByName("responsitive", $settingRes);

                break;
        }

        switch ($sliderType) {
            case "fixed":

            case "responsitive":

            case "fullscreen":
                $settingRes = $settings->getSettingByName("auto_height");
                $settingRes["disabled"] = true;
                $settings->updateArrSettingByName("auto_height", $settingRes);
                $settingRes = $settings->getSettingByName("force_full_width");
                $settingRes["disabled"] = true;
                $settings->updateArrSettingByName("force_full_width", $settingRes);

                break;
        }

        $settingSize = $settings->getSettingByName("slider_size");
        $settingSize["slider_type"] = $sliderType;
        $settings->updateArrSettingByName("slider_size", $settingSize);

        return($settings);
    }

    protected function drawResponsitiveSettings($setting)
    {
        $id = $setting["id"];
        $w1 = UniteFunctionsRb::getVal($setting, "w1");
        $w2 = UniteFunctionsRb::getVal($setting, "w2");
        $w3 = UniteFunctionsRb::getVal($setting, "w3");
        $w4 = UniteFunctionsRb::getVal($setting, "w4");
        $w5 = UniteFunctionsRb::getVal($setting, "w5");
        $w6 = UniteFunctionsRb::getVal($setting, "w6");
        $sw1 = UniteFunctionsRb::getVal($setting, "sw1");
        $sw2 = UniteFunctionsRb::getVal($setting, "sw2");
        $sw3 = UniteFunctionsRb::getVal($setting, "sw3");
        $sw4 = UniteFunctionsRb::getVal($setting, "sw4");
        $sw5 = UniteFunctionsRb::getVal($setting, "sw5");
        $sw6 = UniteFunctionsRb::getVal($setting, "sw6");
        $disabled = (UniteFunctionsRb::getVal($setting, "disabled") == true);

        $strDisabled = "";

        if ($disabled == true) {
            $strDisabled = "disabled='disabled'";
        }

        echo '<table>';
        echo '<tr>';
        echo '<td>';

        $this->modules->l("Screen Width");
        echo '1:';

        echo '</td>';

        echo '<td>';

        echo '<input id="' . $id . '_w1" name="' . $id . '_w1" type="text" class="textbox-small" ' . $strDisabled . ' value="' . $w1 . '">';

        echo '</td>';

        echo '<td>';

        $this->modules->l("Slider Width");
        echo '1:';

        echo '</td>';

        echo '<td>';

        echo '<input id="' . $id . '_sw1" name="' . $id . '_sw1" type="text" class="textbox-small" ' . $strDisabled . ' value="' . $sw1 . '">';

        echo '</td>';

        echo '</tr>';

        echo '<tr>';

        echo '<td>';

        $this->modules->l("Screen Width");
        echo '2:';

        echo '</td>';

        echo '<td>';

        echo '<input id="' . $id . '_w2" name="' . $id . '_w2" type="text" class="textbox-small" ' . $strDisabled . ' value="' . $w2 . '">';

        echo '</td>';

        echo '<td>';

        $this->modules->l("Slider Width");
        echo '2:';

        echo '</td>';

        echo '<td>';

        echo '<input id="' . $id . '_sw2" name="' . $id . '_sw2" type="text" class="textbox-small" ' . $strDisabled . ' value="' . $sw2 . '">';

        echo '</td>';

        echo '</tr>';

        echo '<tr>';

        echo '<td>';

        $this->modules->l("Screen Width");
        echo '3:';

        echo '</td>';

        echo '<td>';

        echo '<input id="' . $id . '_w3" name="' . $id . '_w3" type="text" class="textbox-small" ' . $strDisabled . ' value="' . $w3 . '">';

        echo '</td>';

        echo '<td>';

        $this->modules->l("Slider Width");
        echo '3:';

        echo '</td>';

        echo '<td>';

        echo '<input id="' . $id . '_sw3" name="' . $id . '_sw3" type="text" class="textbox-small" ' . $strDisabled . ' value="' . $sw3 . '">';

        echo '</td>';

        echo '</tr>';

        echo '<tr>';

        echo '<td>';

        $this->modules->l("Screen Width");
        echo '4:';

        echo '</td>';

        echo '<td>';

        echo '<input type="text" id="' . $id . '_w4" name="' . $id . '_w4" class="textbox-small" ' . $strDisabled . ' value="' . $w4 . '">';

        echo '</td>';

        echo '<td>';

        $this->modules->l("Slider Width");
        echo '4:';

        echo '</td>';

        echo '<td>';

        echo '<input type="text" id="' . $id . '_sw4" name="' . $id . '_sw4" class="textbox-small" ' . $strDisabled . ' value="' . $sw4 . '">';

        echo '</td>';

        echo '</tr>';

        echo '<tr>';

        echo '<td>';

        $this->modules->l("Screen Width");
        echo '5:';

        echo '</td>';

        echo '<td>';

        echo '<input type="text" id="' . $id . '_w5" name="' . $id . '_w5" class="textbox-small" ' . $strDisabled . ' value="' . $w5 . '">';

        echo '</td>';

        echo '<td>';

        $this->modules->l("Slider Width");
        echo '5:';

        echo '</td>';

        echo '<td>';

        echo '<input type="text" id="' . $id . '_sw5" name="' . $id . '_sw5" class="textbox-small" ' . $strDisabled . ' value="' . $sw5 . '">';

        echo '</td>';

        echo '</tr>';

        echo '<tr>';

        echo '<td>';

        $this->modules->l("Screen Width");
        echo '6:';

        echo '</td>';

        echo '<td>';

        echo '<input type="text" id="' . $id . '_w6" name="' . $id . '_w6" class="textbox-small" ' . $strDisabled . ' value="' . $w6 . '">';

        echo '</td>';

        echo '<td>';

        $this->modules->l("Slider Width");
        echo '6:';

        echo '</td>';

        echo '<td>';

        echo '<input type="text" id="' . $id . '_sw6" name="' . $id . '_sw6" class="textbox-small" ' . $strDisabled . ' value="' . $sw6 . '">';

        echo '</td>';

        echo '</tr>				';

        echo '</table>';
    }

    protected function drawSliderSize($setting)
    {
        $width = UniteFunctionsRb::getVal($setting, "width");
        $height = UniteFunctionsRb::getVal($setting, "height");
        $sliderType = UniteFunctionsRb::getVal($setting, "slider_type");
        $textNormalW = $this->modules->l("Grid Width:");
        $textNormalH = $this->modules->l("Grid Height:");
        $textFullWidthW = $this->modules->l("Grid Width:");
        $textFullWidthH = $this->modules->l("Grid Height:");
        $textFullScreenW = $this->modules->l("Grid Width:");
        $textFullScreenH = $this->modules->l("Grid Height:");

        //set default text (fixed, responsive)
        switch ($sliderType) {
            default:
                $textDefaultW = $textNormalW;
                $textDefaultH = $textNormalH;

                break;

            case "fullwidth":
                $textDefaultW = $textFullWidthW;
                $textDefaultH = $textFullWidthH;

                break;

            case "fullscreen":
                $textDefaultW = $textFullScreenW;
                $textDefaultH = $textFullScreenH;

                break;
        }

        echo '<table>';

        echo '<tr>';

        echo '<td id="cellWidth" data-textnormal="' . $textNormalW . '" data-textfull="' . $textFullWidthW . '" data-textscreen="' . $textFullScreenW . '">';

        echo $textDefaultW;

        echo '</td>';

        echo '<td id="cellWidthInput">';

        echo '<input id="width" name="width" type="text" class="textbox-small" value="' . $width . '">';

        echo '</td>';

        echo '<td id="cellHeight" data-textnormal="' . $textNormalH . '" data-textfull="' . $textFullWidthH . '" data-textscreen="' . $textFullScreenH . '">';

        echo $textDefaultH;

        echo '</td>';

        echo '<td>';

        echo '<input id="height" name="height" type="text" class="textbox-small" value="' . $height . '">';

        echo '</td>';

        echo '</tr>';

        echo '</table>';
    }

    protected function drawCustomInputs($setting)
    {
        $customType = UniteFunctionsRb::getVal($setting, "custom_type");

        switch ($customType) {

            case "slider_size":

                $this->drawSliderSize($setting);

                break;

            case "responsitive_settings":

                $this->drawResponsitiveSettings($setting);

                break;

            default:

                UniteFunctionsRb::throwError("No handler function for type: $customType");

                break;
        }
    }

    private static function getFirstCategory($cats)
    {
        foreach ($cats as $key => $value) {
            if (strpos($key, "option_disabled") === false) {
                return($key);
            }
        }

        return("");
    }

    public static function setCategoryByPostTypes(
        UniteSettingsRb $settings,
        $arrValues,
        $postTypesWithCats,
        $nameType,
        $nameCat,
        $defaultType
    ) {
        $postTypes = UniteFunctionsRb::getVal($arrValues, $nameType, $defaultType);

        if (strpos($postTypes, ",") !== false) {
            $postTypes = explode(",", $postTypes);
        } else {
            $postTypes = array($postTypes);
        }

        $arrCats = array();
        $globalCounter = 0;
        $arrCats = array();
        $isFirst = true;

        foreach ($postTypes as $postType) {
            $cats = UniteFunctionsRb::getVal($postTypesWithCats, $postType, array());

            if ($isFirst == true) {
                $firstValue = self::getFirstCategory($cats);

                $isFirst = false;
            }

            $arrCats = array_merge($arrCats, $cats);
        }

        $settingCategory = $settings->getSettingByName($nameCat);
        $settingCategory["items"] = $arrCats;
        $settings->updateArrSettingByName($nameCat, $settingCategory);

        //update value to first category
        $value = $settings->getSettingValue($nameCat);

        if (empty($value)) {
            $settings->updateSettingValue($nameCat, $firstValue);
        }

        return($settings);
    }
}
