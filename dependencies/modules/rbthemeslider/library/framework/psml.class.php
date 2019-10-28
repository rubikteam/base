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

class UnitePsmlRb
{
    public function __construct()
    {
        $this->modules = new Rbthemeslider();
    }

    public static function isPsmlExists()
    {
        return true;

        if (class_exists("SitePress")) {
            return(true);
        } else {
            return(false);
        }
    }

    private static function validatePsmlExists()
    {
        if (!self::isPsmlExists()) {
            UniteFunctionsRb::throwError("The plugin don't exists");
        }
    }

    public static function getArrLanguages($getAllCode = true)
    {
        $arrLangs = Language::getLanguages();
        $response = array();

        if ($getAllCode == true) {
            $response["all"] = $this->modules("All Languages");
        }

        foreach ($arrLangs as $code => $arrLang) {
            $ind = $arrLang['iso_code'];
            $response[$ind] = $arrLang['name'];
        }

        return($response);
    }

    public static function getArrLangCodes($getAllCode = true)
    {
        $arrCodes = array();

        if ($getAllCode == true) {
            $arrCodes["all"] = "all";
        }

        $arrLangs = Language::getLanguages();

        foreach ($arrLangs as $code => $arr) {
            $ind = $arr['iso_code'];

            $arrCodes[$ind] = $ind;
        }

        return($arrCodes);
    }

    public static function isAllLangsInArray($arrCodes)
    {
        $arrAllCodes = self::getArrLangCodes();
        $diff = array_diff($arrAllCodes, $arrCodes);

        return(empty($diff));
    }

    public static function getLangsWithFlagsHtmlList($props = "", $htmlBefore = "")
    {
        $arrLangs = self::getArrLanguages();

        if (!empty($props)) {
            $props = " " . $props;
        }

        $html = "<ul" . $props . ">" . "\n";
        $html .= $htmlBefore;

        foreach ($arrLangs as $code => $title) {
            $urlIcon = self::getFlagUrl($code);
            $html .= "<li data-lang='" . $code . "' class='item_lang'><a data-lang='" .
            $code . "' href='javascript:void(0)'>" . "\n";
            $html .= "<img src='" . $urlIcon . "'/> $title" . "\n";
            $html .= "</a></li>" . "\n";
        }

        $html .= "</ul>";

        return($html);
    }

    public static function getFlagUrl($code)
    {
        $arrLangs = Language::getLanguages();

        if ($code == 'all') {
            $url = get_url() . '/views/img/images/icon16.png';
        } else {
            $url = '';
            foreach ($arrLangs as $lang) {
                if ($lang['iso_code'] == $code) {
                    $url = _THEME_LANG_DIR_ . $lang['id_lang'] . '.jpg';
                }
            }
        }

        return($url);
    }

    private function getLangDetails($code)
    {
        $psdb = rbDbClass::rbDbInstance();

        $details = $psdb->getRow("SELECT * FROM " . $psdb->prefix . "icl_languages WHERE code='$code'");

        if (!empty($details)) {
            $details = (array) $details;
        }

        return($details);
    }

    public static function getLangTitle($code)
    {
        $langs = self::getArrLanguages();

        if ($code == "all") {
            return($this->modules->l("All Languages"));
        }

        if (array_key_exists($code, $langs)) {
            return($langs[$code]);
        }

        $details = self::getLangDetails($code);

        if (!empty($details)) {
            return($details["english_name"]);
        }

        return("");
    }

    public static function getCurrentLang()
    {
        $language = Context::getContext()->language;
        $lang = $language->iso_code;

        return($lang);
    }    
}

class RbSliderPsml extends UnitePsmlRb
{

}
