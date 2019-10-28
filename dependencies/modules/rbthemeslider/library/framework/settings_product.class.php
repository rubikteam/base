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

class UniteSettingsRbProductRb extends UniteSettingsOutputRb
{
    protected function drawTextInput($setting)
    {
        $disabled = "";
        $style = "";
        $readonly = "";

        if (@Rbthemeslider::getIsset($setting["style"])) {
            $style = "style='" . $setting["style"] . "'";
        }

        if (@Rbthemeslider::getIsset($setting["disabled"])) {
            $disabled = 'disabled="disabled"';
        }

        if (@Rbthemeslider::getIsset($setting["readonly"])) {
            $readonly = "readonly='readonly'";
        }

        $class = "regular-text";

        if (@Rbthemeslider::getIsset($setting["class"]) && !empty($setting["class"])) {
            $class = $setting["class"];

            switch ($class) {

                case "small":

                    $class = "small-text";

                    break;

                case "code":

                    $class = "regular-text code";

                    break;
            }
        }

        if (!empty($class)) {
            $class = "class='$class'";
        }


        echo '<input type="text" ' . $class . ' ' . $style . ' ' . $disabled . '' . $readonly . '
        id="' . $setting["id"] . '" name="' . $setting["name"] . '" value="' . $setting["value"] . '" />';
    }

    protected function drawHiddenInput($setting)
    {
        $disabled = "";
        $style = "";
        $readonly = "";

        if (@Rbthemeslider::getIsset($setting["style"])) {
            $style = "style='" . $setting["style"] . "'";
        }

        if (@Rbthemeslider::getIsset($setting["disabled"])) {
            $disabled = 'disabled="disabled"';
        }

        if (@Rbthemeslider::getIsset($setting["readonly"])) {
            $readonly = "readonly='readonly'";
        }

        $class = "regular-text";

        if (@Rbthemeslider::getIsset($setting["class"]) && !empty($setting["class"])) {
            $class = $setting["class"];

            switch ($class) {

                case "small":

                    $class = "small-text";

                    break;

                case "code":

                    $class = "regular-text code";

                    break;
            }
        }

        if (!empty($class)) {
            $class = "class='$class'";
        }

        echo '<input type="hidden" ' . $class . ' ' . $style . ' ' . $disabled . '' . $readonly . '
        id="' . $setting["id"] . '" name="' . $setting["name"] . '" value="' . $setting["value"] . '" />';
    }

    protected function drawImageInput($setting)
    {
        $class = UniteFunctionsRb::getVal($setting, "class");

        if (!empty($class)) {
            $class = "class='$class'";
        }

        $settingsID = $setting["id"];
        $buttonID = $settingsID . "_button";
        $spanPreviewID = $buttonID . "_preview";
        $img = "";
        $value = UniteFunctionsRb::getVal($setting, "value");

        if (!empty($value)) {
            $urlImage = $value;
            $imagePath = UniteFunctionsPSRb::getImageRealPathFromUrl($urlImage);

            if (file_exists($imagePath)) {
                $filepath = UniteFunctionsPSRb::getImagePathFromURL($urlImage);
                $urlImage = UniteBaseClassRb::getImageUrl($filepath, 100, 70, true);
            }

            $img = "<img width='100' height='70' src='$urlImage'></img>";
        }

        echo '<span id="' . $spanPreviewID . '" class="setting-image-preview">' . $img . '</span>';
        echo '<input type="hidden" id="' . $setting["id"] . '" name="' .
        $setting["name"] . '" value="' . $setting["value"] . '" />';
        echo '<input type="button" id="' . $buttonID . '" class="button-image-select button-primary rbblue' .
        $class . '" value="Choose Image" />';
    }

    protected function drawColorPickerInput($setting)
    {
        $bgcolor = $setting["value"];
        $bgcolor = str_replace("0x", "#", $bgcolor);

        // set the forent color (by black and white value)
        $rgb = UniteFunctionsRb::html2rgb($bgcolor);
        $bw = UniteFunctionsRb::yiq($rgb[0], $rgb[1], $rgb[2]);
        $color = "#000000";

        if ($bw < 128) {
            $color = "#ffffff";
        }

        $disabled = "";

        if (@Rbthemeslider::getIsset($setting["disabled"])) {
            $color = "";
            $disabled = 'disabled="disabled"';
        }

        $style = "style='background-color:$bgcolor;color:$color'";
        echo '<input type="text" class="inputColorPicker" id="' . $setting["id"] . '" ' .
        $style . ' name="' . $setting["name"] . '" value="' . $bgcolor . '" ' . $disabled . ' >';
    }

    protected function drawDatePickerInput($setting)
    {
        $date = $setting["value"];

        echo '<input type="text" class="inputDatePicker" id="' .
        $setting["id"] . '" name="' . $setting["name"] . '" value="' . $date . '" />';
    }

    protected function drawInputs($setting)
    {
        switch ($setting["type"]) {

            case UniteSettingsRb::TYPE_HIDDEN:

                $this->drawHiddenInput($setting);

                break;

            case UniteSettingsRb::TYPE_TEXT:

                $this->drawTextInput($setting);

                break;

            case UniteSettingsRb::TYPE_COLOR:

                $this->drawColorPickerInput($setting);

                break;

            case UniteSettingsRb::TYPE_DATE:

                $this->drawDatePickerInput($setting);

                break;

            case UniteSettingsRb::TYPE_SELECT:

                $this->drawSelectInput($setting);

                break;

            case UniteSettingsRb::TYPE_CHECKLIST:

                $this->drawChecklistInput($setting);

                break;

            case UniteSettingsRb::TYPE_CHECKBOX:

                $this->drawCheckboxInput($setting);

                break;

            case UniteSettingsRb::TYPE_RADIO:

                $this->drawRadioInput($setting);

                break;

            case UniteSettingsRb::TYPE_TEXTAREA:

                $this->drawTextAreaInput($setting);

                break;

            case UniteSettingsRb::TYPE_IMAGE:

                $this->drawImageInput($setting);

                break;

            case UniteSettingsRb::TYPE_CUSTOM:

                if (method_exists($this, "drawCustomInputs") == false) {
                    UniteFunctionsRb::throwError("Method don't exists: drawCustomInputs, please override the class");
                }

                $this->drawCustomInputs($setting);

                break;

            default:

                throw new Exception("wrong setting type - " . $setting["type"]);

                break;
        }
    }

    protected function drawTextAreaInput($setting)
    {
        $disabled = "";

        if (@Rbthemeslider::getIsset($setting["disabled"])) {
            $disabled = 'disabled="disabled"';
        }

        $style = "";

        if (@Rbthemeslider::getIsset($setting["style"])) {
            $style = "style='" . $setting["style"] . "'";
        }

        $rows = UniteFunctionsRb::getVal($setting, "rows");

        if (!empty($rows)) {
            $rows = "rows='$rows'";
        }

        $cols = UniteFunctionsRb::getVal($setting, "cols");

        if (!empty($cols)) {
            $cols = "cols='$cols'";
        }

        echo '<textarea id="' . $setting["id"] . '" name="' . $setting["name"] . '" ' .
        $style . ' ' . $disabled . ' ' . $rows . ' ' . $cols . '  >' . $setting["value"] . '</textarea>';

        if (!empty($cols)) {
            echo "<br>";
        }
    }

    protected function drawRadioInput($setting)
    {
        $items = $setting["items"];
        $settingID = UniteFunctionsRb::getVal($setting, "id");
        $wrapperID = $settingID . "_wrapper";
        $addParams = UniteFunctionsRb::getVal($setting, "addparams");
        $counter = 0;
        $outputHtml = '<span id="' . $wrapperID . '" class="radio_settings_wrapper" ' . $addParams . '>';

        foreach ($items as $value => $text):
            $counter++;
            $radioID = $setting["id"] . "_" . $counter;
            $checked = "";

            if ($value == $setting["value"]) {
                $checked = " checked='checked'";
            }

            $outputHtml .= '<div class="radio_inner_wrapper">';
            $outputHtml .= '<input type="radio" id="' . $radioID . '" value="' . $value .
            '" name="' . $setting["name"] . '" ' . $checked . '/>';
            $outputHtml .= '<label for="' . $radioID . '" style="cursor:pointer;">' . $text . '</label>';
            $outputHtml .= '</div>';
        endforeach;

        $outputHtml .= '</span>';

        echo $outputHtml;
    }

    protected function drawCheckboxInput($setting)
    {
        $checked = "";

        if ($setting["value"] == true) {
            $checked = 'checked="checked"';
        }

        echo '<input type="checkbox" id="' . $setting["id"] . '" class="inputCheckbox" name="' .
        $setting["name"] . '" ' . $checked . '/>';
    }

    protected function drawSelectInput($setting)
    {
        $className = "";

        if (@Rbthemeslider::getIsset($this->arrControls[$setting["name"]])) {
            $className = "control";
        }

        $class = "";

        if ($className != "") {
            $class = "class='" . $className . "'";
        }

        $disabled = "";

        if (@Rbthemeslider::getIsset($setting["disabled"])) {
            $disabled = 'disabled="disabled"';
        }

        $args = UniteFunctionsRb::getVal($setting, "args");
        $settingValue = $setting["value"];

        if (strpos($settingValue, ",") !== false) {
            $settingValue = explode(",", $settingValue);
        }

        $outputHtml = '<select id="' . $setting["id"] . '" name="' . $setting["name"] . '" ' . $disabled . ' ' . $class . ' ' . $args . '>';

        foreach ($setting["items"] as $value => $text):
            $selected = "";
            $addition = "";

            if (strpos($value, "option_disabled") === 0) {
                $addition = "disabled";
            } else {
                if (is_array($settingValue)) {
                    if (array_search($value, $settingValue) !== false) {
                        $selected = 'selected="selected"';
                    }
                } else {
                    if ($value == $settingValue) {
                        $selected = 'selected="selected"';
                    }
                }
            }

            $outputHtml .= '<option ' . $addition . ' value="' . $value . '" ' . $selected . '>' . $text . '</option>';
        endforeach;

        $outputHtml .= '</select>';

        echo $outputHtml;
    }

    protected function drawChecklistInput($setting)
    {
        $className = "input_checklist";

        if (@Rbthemeslider::getIsset($this->arrControls[$setting["name"]])) {
            $className .= " control";
        }

        $class = "";

        if ($className != "") {
            $class = "class='" . $className . "'";
        }

        $disabled = "";

        if (@Rbthemeslider::getIsset($setting["disabled"])) {
            $disabled = 'disabled="disabled"';
        }

        $args = UniteFunctionsRb::getVal($setting, "args");
        $settingValue = $setting["value"];

        if (strpos($settingValue, ",") !== false) {
            $settingValue = explode(",", $settingValue);
        }

        $style = "z-index:1000;";
        $minWidth = UniteFunctionsRb::getVal($setting, "minwidth");

        if (!empty($minWidth)) {
            $style .= "min-width:" . $minWidth . "px;";

            $args .= " data-minwidth='" . $minWidth . "'";
        }

        $outputHtml = '<select id="' . $setting["id"] . '" name="' .
        $setting["name"] . '" ' . $disabled . ' multiple ' . $class . ' ' . $args . ' size="1" style="' . $style . '">';

        foreach ($setting["items"] as $value => $text):
            $selected = "";
            $addition = "";

            if (strpos($value, "option_disabled") === 0) {
                $addition = "disabled";
            } else {
                if (is_array($settingValue)) {
                    if (array_search($value, $settingValue) !== false) {
                        $selected = 'selected="selected"';
                    }
                } else {
                    if ($value == $settingValue) {
                        $selected = 'selected="selected"';
                    }
                }
            }

            $outputHtml .= '<option ' . $addition . ' value="' . $value . '" ' . $selected . '>' . $text . '</option>';
        endforeach;

        $outputHtml .= '</select>';
        echo $outputHtml;
    }

    protected function drawTextRow($setting)
    {
        $cellStyle = "";

        if (@Rbthemeslider::getIsset($setting["padding"])) {
            $cellStyle .= "padding-left:" . $setting["padding"] . ";";
        }

        if (!empty($cellStyle)) {
            $cellStyle = "style='$cellStyle'";
        }

        //set style
        $rowStyle = "";

        if (@Rbthemeslider::getIsset($setting["hidden"])) {
            $rowStyle .= "display:none;";
        }

        if (!empty($rowStyle)) {
            $rowStyle = "style='$rowStyle'";
        }

        $outputHtml = '<tr id="' . $setting["id_row"] . '" ' . $rowStyle . ' valign="top">';
        $outputHtml .= '<td colspan="4" align="right" ' . $cellStyle . '>';
        $outputHtml .= '<span class="spanSettingsStaticText">' . $setting["text"] . '</span>';
        $outputHtml .= '</td>';
        $outputHtml .= '</tr>';

        echo $outputHtml;
    }

    protected function drawHrRow($setting)
    {
        //set hidden
        $rowStyle = "";

        if (@Rbthemeslider::getIsset($setting["hidden"])) {
            $rowStyle = "style='display:none;'";
        }

        $class = UniteFunctionsRb::getVal($setting, "class");

        if (!empty($class)) {
            $class = "class='$class'";
        }

        $outputHtml = '<tr id="' . $setting["id_row"] . '" ' . $rowStyle . '>';
        $outputHtml .= '<td colspan="4" align="left" style="text-align:left;">';
        $outputHtml .= '<hr ' . $class . ' />';
        $outputHtml .= '</td>';
        $outputHtml .= '</tr>';

        echo $outputHtml;
    }

    protected function drawSettingRow($setting)
    {
        //set cellstyle:
        $cellStyle = "";

        if (@Rbthemeslider::getIsset($setting[UniteSettingsRb::PARAM_CELLSTYLE])) {
            $cellStyle .= $setting[UniteSettingsRb::PARAM_CELLSTYLE];
        }

        //set text style:
        $textStyle = $cellStyle;

        if (@Rbthemeslider::getIsset($setting[UniteSettingsRb::PARAM_TEXTSTYLE])) {
            $textStyle .= $setting[UniteSettingsRb::PARAM_TEXTSTYLE];
        }

        if ($textStyle != "") {
            $textStyle = "style='" . $textStyle . "'";
        }

        if ($cellStyle != "") {
            $cellStyle = "style='" . $cellStyle . "'";
        }

        //set hidden
        $rowStyle = "";

        if (@Rbthemeslider::getIsset($setting["hidden"])) {
            $rowStyle = "display:none;";
        }

        if (!empty($rowStyle)) {
            $rowStyle = "style='$rowStyle'";
        }

        //set text class:
        $class = "";

        if (@Rbthemeslider::getIsset($setting["disabled"])) {
            $class = "class='disabled'";
        }

        //modify text:
        $text = UniteFunctionsRb::getVal($setting, "text", "");

        // prevent line break (convert spaces to nbsp)
        $text = str_replace(" ", "&nbsp;", $text);

        switch ($setting["type"]) {
            case UniteSettingsRb::TYPE_CHECKBOX:
                $text = "<label for='" . $setting["id"] . "' style='cursor:pointer;'>$text</label>";

                break;
        }

        //set settings text width:
        $textWidth = "";

        if (@Rbthemeslider::getIsset($setting["textWidth"])) {
            $textWidth = 'width="' . $setting["textWidth"] . '"';
        }

        $description = UniteFunctionsRb::getVal($setting, "description");
        $required = UniteFunctionsRb::getVal($setting, "required");
        echo '<tr id="' . $setting["id_row"] . '" ' . $rowStyle . ' ' . $class . ' valign="top">';

        if ($setting['type'] == UniteSettingsRb::TYPE_HIDDEN) {
            echo '<td colspan="3" ' . $cellStyle . '>';
            $this->drawInputs($setting);
            echo '</td>';
        } else {
            echo '<th ' . $textStyle . ' scope="row" ' . $textWidth . '>';
            echo $text . ':';
            echo '</th>';
            echo '<td ' . $cellStyle . '>';
            $this->drawInputs($setting);

            if (!empty($required)):
                echo "<span class='setting_required'>*</span>";
            endif;

            echo '<div class="description_container">';

            if (!empty($description)):

                echo '<span class="description">' . $description . '</span>';

            endif;

            echo '</div>';

            echo '</td>';

            echo '<td class="description_container_in_td">';

            if (!empty($description)):

                echo '<span class="description">' . $description . '</span>';

            endif;

            echo '</td>';
        }

        echo '</tr>';
    }

    public function drawSettings()
    {
        $this->drawHeaderIncludes();
        $this->prepareToDraw();

        //draw main div
        $lastSectionKey = -1;
        $visibleSectionKey = 0;
        $lastSapKey = -1;
        $arrSections = $this->settings->getArrSections();
        $arrSettings = $this->settings->getArrSettings();

        //draw settings - simple
        if (empty($arrSections)):
            echo "<table class='form-table'>";
            foreach ($arrSettings as $key => $setting) {
                switch ($setting["type"]) {

                    case UniteSettingsRb::TYPE_HR:

                        $this->drawHrRow($setting);

                        break;

                    case UniteSettingsRb::TYPE_STATIC_TEXT:

                        $this->drawTextRow($setting);

                        break;

                    default:

                        $this->drawSettingRow($setting);

                        break;
                }
            }

            echo '</table>';
        else:

            //draw settings - advanced - with sections
            foreach ($arrSettings as $key => $setting):
                //operate sections:
                if (!empty($arrSections) && @Rbthemeslider::getIsset($setting["section"])) {
                    $sectionKey = $setting["section"];

                    if ($sectionKey != $lastSectionKey):
                        $arrSaps = $arrSections[$sectionKey]["arrSaps"];

                        if (!empty($arrSaps)) {
                            //close sap
                            if ($lastSapKey != -1):
                                echo '</table></div>';
                            endif;

                            $lastSapKey = -1;
                        }

                        $style = ($visibleSectionKey == $sectionKey) ? "" : "style='display:none'";

                        //close section
                        if ($sectionKey != 0):
                            if (empty($arrSaps)) {
                                echo "</table>";
                            }

                            echo "</div>\n";

                        endif;

                        if (empty($arrSaps)):

                            echo '<table class="form-table">';
                        endif;

                    endif;

                    $lastSectionKey = $sectionKey;
                }

                if (!empty($arrSaps) && @Rbthemeslider::getIsset($setting["sap"])) {
                    $sapKey = $setting["sap"];

                    if ($sapKey != $lastSapKey) {
                        $sap = $this->settings->getSap($sapKey, $sectionKey);

                        if ($sapKey != 0):

                            echo '</table>';

                        endif;

                        $style = "";
                        $class = "divSapControl";

                        if ($sapKey == 0 || @Rbthemeslider::getIsset($sap["opened"]) && $sap["opened"] == true) {
                            $style = "";
                            $class = "divSapControl opened";
                        }

                        echo '<div id="divSapControl_' . $sectionKey . "_" . $sapKey . '" class="' . $class . '">';
                        echo '<h3>' . $sap["text"] . '</h3>';
                        echo '</div>';
                        echo '<div id="divSap_' . $sectionKey . "_" . $sapKey . '" class="divSap" ' . $style . '>';
                        echo '<table class="form-table">';

                        $lastSapKey = $sapKey;
                    }
                }

                switch ($setting["type"]) {

                    case UniteSettingsRb::TYPE_HR:

                        $this->drawHrRow($setting);

                        break;

                    case UniteSettingsRb::TYPE_STATIC_TEXT:

                        $this->drawTextRow($setting);

                        break;

                    default:

                        $this->drawSettingRow($setting);

                        break;
                }

            endforeach;

        endif;

        ?>

        </table>



        <?php
        if (!empty($arrSections)):

            if (empty($arrSaps)) {     //close table settings if no saps
                echo "</table>";
            }

            echo "</div>\n";     //close last section div

        endif;
    }

    public function drawSections($activeSection = 0)
    {
        if (!empty($this->arrSections)):

            echo "<ul class='listSections' >";

            for ($i = 0; $i < count($this->arrSections); $i++):

                $class = "";

                if ($activeSection == $i) {
                    $class = "class='selected'";
                }

                $text = $this->arrSections[$i]["text"];

                echo '<li ' . $class . '><a onfocus="this.blur()" href="#' . ($i + 1) . '"><div>' . $text . '</div></a></li>';

            endfor;

            echo "</ul>";

        endif;

        //call custom draw function:

        if ($this->customFunction_afterSections) {
            call_user_func($this->customFunction_afterSections);
        }
    }

    public function draw($formID = null, $drawForm = false)
    {
        if (empty($formID)) {
            UniteFunctionsRb::throwError("The form ID can't be empty. you must provide it");
        }

        $this->formID = $formID;
        echo '<div class="settings_wrapper unite_settings_wide">';

        if ($drawForm == true) {
            echo '<form name="' . $formID . '" id="' . $formID . '">';
            $this->drawSettings();
            echo '</form>';
        } else {
            $this->drawSettings();
        }
        
        echo '</div>';
    }
}
