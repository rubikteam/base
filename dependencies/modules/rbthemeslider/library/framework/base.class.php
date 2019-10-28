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

class UniteBaseClassRb
{
    protected static $psdb;
    protected static $table_prefix;
    protected static $mainFile;
    protected static $t;
    protected static $dir_plugin;
    protected static $dir_languages;
    public static $url_plugin;
    public static $static_shortcode_tags;
    protected static $url_ajax;
    public static $url_ajax_actions;
    protected static $url_ajax_showimage;
    protected static $path_settings;
    public static $path_plugin;
    protected static $path_languages;
    protected static $path_temp;
    protected static $path_views;
    protected static $path_templates;
    protected static $path_cache;
    protected static $path_base;
    protected static $is_multisite;
    protected static $debugMode = false;
    protected static $actions = array();
    protected static $admin_scripts = array();
    protected static $front_scripts = array();
    protected static $admin_styles = array();
    protected static $front_styles = array();

    public function __construct($mainFile, $t)
    {
        self::$is_multisite = UniteFunctionsPSRb::isMultisite();
        $this->modules = new Rbthemeslider();

        if (class_exists('Rbthemeslider')) {
            self::$psdb = Rbthemeslider::$psdb;
        } else {
            self::$psdb = rbDbClass::rbDbInstance();
        }

        self::$table_prefix = self::$psdb->prefix;

        if (UniteFunctionsPSRb::isMultisite()) {
            $blogID = UniteFunctionsPSRb::getBlogID();

            if ($blogID != 1) {
                self::$table_prefix .= $blogID . "_";
            }
        }

        self::$mainFile = $mainFile;
        self::$t = $t;
        $info = pathinfo($mainFile);
        $baseName = $info["basename"];
        $filename = str_replace(".php", "", $baseName);
        self::$dir_plugin = $filename;
        self::$url_plugin = pluginsUrl(self::$dir_plugin) . "/";
        $context = Context::getContext();
        self::$url_ajax = @Rbthemeslider::getIsset($context->controller->admin_webpath) ? adminUrl() : null;
        self::$url_ajax_actions = self::$url_ajax . "&action=" . self::$dir_plugin . "_ajax_action";
        self::$url_ajax_showimage = self::$url_plugin . "ajax.php?action=" . self::$dir_plugin . "_show_image";
        self::$path_plugin = self::$mainFile . "/";
        self::$path_settings = self::$path_plugin . "settings/";
        self::$path_temp = self::$path_plugin . "temp/";

        //set cache path:
        self::setPathCache();
        self::$path_views = self::$path_plugin . "views/";
        self::$path_templates = self::$path_views . "/templates/";
        self::$path_base = ABSPATH;
        self::$path_languages = self::$path_plugin . "languages/";
        self::$dir_languages = self::$dir_plugin . "/languages/";
        GlobalsRbSlider::$isNewVersion = true;
    }

    private static function setPathCache()
    {
        self::$path_cache = self::$path_plugin . "cache/";

        if (self::$is_multisite) {
            if (!defined("BLOGUPLOADDIR") || !is_dir(BLOGUPLOADDIR)) {
                return false;
            }

            $path = BLOGUPLOADDIR . self::$dir_plugin . "-cache/";

            if (!is_dir($path)) {
                mkdir($path);
            }

            if (is_dir($path)) {
                self::$path_cache = $path;
            }
        }
    }

    public static function setDebugMode()
    {
        self::$debugMode = true;
    }

    public static function psEnqueueStyle(
        $scriptName,
        $src = '',
        $deps = array(),
        $ver = '1.0',
        $media = 'all',
        $noscript = false
    ) {
        $cadm = count(self::$admin_styles) ? count(self::$admin_styles) : 0;
        $cfrt = count(self::$front_styles) ? count(self::$front_styles) : 0;

        if (is_array($scriptName)) {
            $deps = $scriptName;
        }

        if (is_admin()) {
            self::$admin_styles[$cadm] = new stdClass();


            if (is_string($scriptName)) {
                self::$admin_styles[$cadm]->css = "<link rel='stylesheet' id='{$scriptName}' media='{$media}'
                href='{$src}' type='text/css' />";
            }

            if ($noscript) {
                self::$admin_styles[$cadm]->css = "<noscript>" . self::$admin_styles[$cadm]->css . "</noscript>";
            }
        } else {
            self::$front_styles[$cfrt] = new stdClass();

            if (is_string($scriptName)) {
                self::$front_styles[$cfrt]->css = "<link rel='stylesheet' id='{$scriptName}' media='{$media}'
                href='{$src}' type='text/css' />";
            }
        }
    }

    public static function psEnqueueScript(
        $scriptName,
        $src = '',
        $deps = array(),
        $ver = '1.0',
        $in_footer = false
    ) {
        $cadm = count(self::$admin_scripts) ? count(self::$admin_scripts) : 0;
        $cfrt = count(self::$front_scripts) ? count(self::$front_scripts) : 0;

        if (is_array($scriptName)) {
            $deps = $scriptName;
        }

        if (is_admin()) {
            self::$admin_scripts[$cadm] = new stdClass();
            self::$admin_scripts[$cadm]->deps = loadAdditionalScripts($deps, self::$admin_scripts);
            self::$admin_scripts[$cadm]->footer = $in_footer;

            if (is_string($scriptName) && !empty($src)) {
                self::$admin_scripts[$cadm]->script = "<script id='{$scriptName}' src='{$src}' type='text/javascript'></script>";
            } else {
                $scriptArr = is_array($scriptName) ? $scriptName : array($scriptName);

                $getScripts = loadAdditionalScripts($scriptArr, self::$admin_scripts);

                if (!empty($getScripts)) {
                    foreach ($getScripts as $id => $src):
                        self::$admin_scripts[$cadm]->script = "<script id='{$id}' src='" . scriptUrl() . $src . "' type='text/javascript'></script>";
                        self::$admin_scripts[$cadm]->footer = $in_footer;
                        $cadm++;
                    endforeach;
                }
            }
        } else {
            self::$front_scripts[$cfrt] = new stdClass();
            self::$front_scripts[$cadm]->deps = loadAdditionalScripts($deps, self::$front_scripts);
            self::$front_scripts[$cfrt]->footer = $in_footer;

            if (is_string($scriptName) && !empty($src)) {
                self::$front_scripts[$cfrt]->script = "<script id='{$scriptName}' src='{$src}' type='text/javascript'></script>";
            } else {
                $scriptArr = is_array($scriptName) ? $scriptName : array($scriptName);
                $getScripts = loadAdditionalScripts($scriptArr, self::$front_scripts);

                if (!empty($getScripts)) {
                    foreach ($getScripts as $id => $src):
                        self::$front_scripts[$cadm]->script = "<script id='{$id}'
                        src='" . scriptUrl() . $src . "' type='text/javascript'></script>";
                        self::$front_scripts[$cadm]->footer = $in_footer;
                        $cadm++;
                    endforeach;
                }
            }
        }
    }

    public static function enqueueCss($script)
    {
        echo "\t\n";

        if (@Rbthemeslider::getIsset($script->css)) {
            echo $script->css;
        }
    }

    public static function enqueueScript($script)
    {
        if (!empty($script->deps)) {
            foreach ($script->deps as $key => $src):

                echo "<script id='{$key}' type='text/javascript' src='" . scriptUrl() . $src . "'></script>";

            endforeach;
        }

        echo "\t\n";

        if (@Rbthemeslider::getIsset($script->script)) {
            echo $script->script;
        }
    }

    public static function rbHead()
    {
        if (is_admin() && !empty(self::$admin_styles)) {
            foreach (self::$admin_styles as $script):
                self::enqueueCss($script);
            endforeach;
        } elseif (!is_admin() && !empty(self::$front_styles)) {
            foreach (self::$front_styles as $script):
                self::enqueueCss($script);
            endforeach;
        }

        echo "\t\n";

        if (is_admin() && !empty(self::$admin_scripts)) {
            foreach (self::$admin_scripts as $script):
                if ($script->footer) {
                    continue;
                }

                self::enqueueScript($script);
            endforeach;
        } elseif (!is_admin() && !empty(self::$front_scripts)) {
            foreach (self::$front_scripts as $script):
                if ($script->footer) {
                    continue;
                }

                self::enqueueScript($script);
            endforeach;
        }

        echo "\t\n";
    }

    public static function rbFooter()
    {
        if (is_admin() && !empty(self::$admin_scripts)) {
            foreach (self::$admin_scripts as $script):
                if (!$script->footer) {
                    continue;
                }

                self::enqueueScript($script);
            endforeach;
        } elseif (!is_admin() && !empty(self::$front_scripts)) {
            foreach (self::$front_scripts as $script):
                if (!$script->footer) {
                    continue;
                }

                self::enqueueScript($script);
            endforeach;
        }
    }

    protected static function addAction($action, $eventFunction)
    {
        if (!@Rbthemeslider::getIsset(self::$actions[$action])) {
            self::$actions[$action] = array();
            self::$actions[$action][0] = $eventFunction;
        } else {
            self::$actions[$action][count(self::$actions[$action])] = $eventFunction;
        }
    }

    protected static function addScriptAbsoluteUrl($scriptPath, $handle)
    {
        psEnqueueScript($handle, $scriptPath, array('jquery'), '', false);
    }

    protected static function addScriptAbsoluteUrlWaitForOther($scriptPath, $handle, $waitfor = array())
    {
        psEnqueueScript($handle, $scriptPath, $waitfor);
    }

    protected static function addScript($scriptName, $folder = "js", $handle = null)
    {
        if ($handle == null) {
            $handle = self::$dir_plugin . "-" . $scriptName;
        }

        $scriptPath = self::$url_plugin . $folder . "/" . $scriptName . ".js";
        psEnqueueScript($handle, $scriptPath, array(), '', false);
    }

    protected static function addScriptWaitFor($scriptName, $folder = "js", $handle = null, $waitfor = array())
    {
        if ($handle == null) {
            $handle = self::$dir_plugin . "-" . $scriptName;
        }

        psEnqueueScript(
            $handle,
            self::$url_plugin . $folder . "/" . $scriptName . ".js?rb=" . GlobalsRbSlider::SLIDER_RBISION,
            $waitfor
        );
    }

    protected static function addScriptCommon($scriptName, $handle = null, $folder = "js")
    {
        if ($handle == null) {
            $handle = $scriptName;
        }

        self::addScript($scriptName, $folder, $handle);
    }

    protected static function addPSScript($scriptName)
    {
        psEnqueueScript($scriptName);
    }

    protected static function addStyle($styleName, $handle = null, $folder = "css")
    {
        if ($handle == null) {
            $handle = self::$dir_plugin . "-" . $styleName;
        }
    }

    protected static function addDynamicStyle($styleName, $handle = null, $folder = "css")
    {
        if ($handle == null) {
            $handle = self::$dir_plugin . "-" . $styleName;
        }
    }

    protected static function addStyleCommon($styleName, $handle = null, $folder = "css")
    {
        if ($handle == null) {
            $handle = $styleName;
        }
    }

    protected static function addStyleAbsoluteUrl($styleUrl, $handle)
    {
        
    }

    protected static function addPSStyle($styleName)
    {
        
    }

    public static function getImageUrl(
        $filepath,
        $width = null,
        $height = null,
        $exact = false,
        $effect = null,
        $effect_param = null
    ) {
        $urlImage = UniteImageViewRb::getUrlThumb(
            self::$url_ajax_showimage,
            $filepath,
            $width,
            $height,
            $exact,
            $effect,
            $effect_param
        );

        return($urlImage);
    }

    public static function onShowImage()
    {
        $img = Tools::getValue('img');

        if (empty($img)) {
            die('Image doesn\'t exists!');
        }

        $pathImages = UniteFunctionsPSRb::getPathContent();
        $urlImages = UniteFunctionsPSRb::getUrlContent();

        try {
            $imageView = new UniteImageViewRb(self::$path_cache, $pathImages, $urlImages);
            $imageView->showImageFromGet();
        } catch (Exception $e) {
            header("status: 500");
            echo $e->getMessage();

            exit();
        }
    }

    protected static function getPostVar($key, $defaultValue = "")
    {
        $val = Tools::getValue($key, $defaultValue);

        return($val);
    }

    public static function getGetVar($key, $defaultValue = "")
    {
        $val = Tools::getValue($key, $defaultValue);

        return($val);
    }

    protected static function getPostGetVar($key, $defaultValue = "")
    {
        $val = Tools::getValue($key, $defaultValue);

        return($val);
    }

    public static function getVar($arr, $key, $defaultValue = "")
    {
        $val = $defaultValue;

        if (@Rbthemeslider::getIsset($arr[$key])) {
            $val = $arr[$key];
        }

        return($val);
    }

    /**
     * Get all images sizes + custom added sizes
     */
    public static function getAllImageSizes($type = 'gallery')
    {
        $custom_sizes = array();

        switch ($type) {
            case 'flickr':
                $custom_sizes = array(
                    'original' => $this->modules->l('Original'),
                    'large' => $this->modules->l('Large'),
                    'large-square' => $this->modules->l('Large Square'),
                    'medium' => $this->modules->l('Medium'),
                    'medium-800' => $this->modules->l('Medium 800'),
                    'medium-640' => $this->modules->l('Medium 640'),
                    'small' => $this->modules->l('Small'),
                    'small-320' => $this->modules->l('Small 320'),
                    'thumbnail' => $this->modules->l('Thumbnail'),
                    'square' => $this->modules->l('Square')
                );
                break;
            case 'instagram':
                $custom_sizes = array(
                    'standard_resolution' => $this->modules->l('Standard Resolution'),
                    'thumbnail' => $this->modules->l('Thumbnail'),
                    'low_resolution' => $this->modules->l('Low Resolution')
                );
                break;
            case 'twitter':
                $custom_sizes = array(
                    'large' => $this->modules->l('Standard Resolution')
                );
                break;
            case 'facebook':
                $custom_sizes = array(
                    'full' => $this->modules->l('Original Size'),
                    'thumbnail' => $this->modules->l('Thumbnail')
                );
                break;
            case 'youtube':
                $custom_sizes = array(
                    'default' => $this->modules->l('Default'),
                    'medium' => $this->modules->l('Medium'),
                    'high' => $this->modules->l('High'),
                    'standard' => $this->modules->l('Standard'),
                    'maxres' => $this->modules->l('Max. Res.')
                );
                break;
            case 'vimeo':
                $custom_sizes = array(
                    'thumbnail_small' => $this->modules->l('Small'),
                    'thumbnail_medium' => $this->modules->l('Medium'),
                    'thumbnail_large' => $this->modules->l('Large'),
                );
                break;
            case 'gallery':
                break;
            default:
                $img_orig_sources = array(
                    'full' => $this->modules->l('Original Size'),
                    'thumbnail' => $this->modules->l('Thumbnail'),
                    'medium' => $this->modules->l('Medium'),
                    'large' => $this->modules->l('Large')
                );

                $custom_sizes = $img_orig_sources;
                break;
        }

        return $custom_sizes;
    }

    /**
     * retrieve the image id from the given image url
     */
    public static function getImageIdByUrl($image_url)
    {
        return getImageIdByUrl($image_url);
    }

    protected static function updateSettingsText()
    {
        $filelist = UniteFunctionsRb::getFileList(self::$path_settings, "xml");

        foreach ($filelist as $file) {
            $filepath = self::$path_settings . $file;

            UniteFunctionsPSRb::writeSettingLanguageFile($filepath);
        }
    }

    /**
     * translates removed settings from Slider Settings from version <= 4.x to 5.0
     * @since: 5.0
     * */
    public static function translateSettingsToV5($settings)
    {
        if (@Rbthemeslider::getIsset($settings['navigaion_type'])) {
            switch ($settings['navigaion_type']) {
                case 'none': // all is off, so leave the defaults
                    break;
                case 'bullet':
                    $settings['enable_bullets'] = 'on';
                    $settings['enable_thumbnails'] = 'off';
                    $settings['enable_tabs'] = 'off';

                    break;
                case 'thumb':
                    $settings['enable_bullets'] = 'off';
                    $settings['enable_thumbnails'] = 'on';
                    $settings['enable_tabs'] = 'off';
                    break;
            }
            unset($settings['navigaion_type']);
        }

        if (@Rbthemeslider::getIsset($settings['navigation_arrows'])) {
            $settings['enable_arrows'] = ($settings['navigation_arrows'] == 'solo' ||
                $settings['navigation_arrows'] == 'nexttobullets') ? 'on' : 'off';

            unset($settings['navigation_arrows']);
        }

        if (@Rbthemeslider::getIsset($settings['navigation_style'])) {
            $settings['navigation_arrow_style'] = $settings['navigation_style'];
            $settings['navigation_bullets_style'] = $settings['navigation_style'];
            unset($settings['navigation_style']);
        }

        if (@Rbthemeslider::getIsset($settings['navigaion_always_on'])) {
            $settings['arrows_always_on'] = $settings['navigaion_always_on'];
            $settings['bullets_always_on'] = $settings['navigaion_always_on'];
            $settings['thumbs_always_on'] = $settings['navigaion_always_on'];
            unset($settings['navigaion_always_on']);
        }

        if (@Rbthemeslider::getIsset($settings['hide_thumbs']) &&
            !@Rbthemeslider::getIsset($settings['hide_arrows']) &&
            !@Rbthemeslider::getIsset($settings['hide_bullets'])
        ) {
            $settings['hide_arrows'] = $settings['hide_thumbs'];
            $settings['hide_bullets'] = $settings['hide_thumbs'];
        }

        if (@Rbthemeslider::getIsset($settings['navigaion_align_vert'])) {
            $settings['bullets_align_vert'] = $settings['navigaion_align_vert'];
            $settings['thumbnails_align_vert'] = $settings['navigaion_align_vert'];
            unset($settings['navigaion_align_vert']);
        }

        if (@Rbthemeslider::getIsset($settings['navigaion_align_hor'])) {
            $settings['bullets_align_hor'] = $settings['navigaion_align_hor'];
            $settings['thumbnails_align_hor'] = $settings['navigaion_align_hor'];
            unset($settings['navigaion_align_hor']);
        }

        if (@Rbthemeslider::getIsset($settings['navigaion_offset_hor'])) {
            $settings['bullets_offset_hor'] = $settings['navigaion_offset_hor'];
            $settings['thumbnails_offset_hor'] = $settings['navigaion_offset_hor'];
            unset($settings['navigaion_offset_hor']);
        }

        if (@Rbthemeslider::getIsset($settings['navigaion_offset_hor'])) {
            $settings['bullets_offset_hor'] = $settings['navigaion_offset_hor'];
            $settings['thumbnails_offset_hor'] = $settings['navigaion_offset_hor'];
            unset($settings['navigaion_offset_hor']);
        }

        if (@Rbthemeslider::getIsset($settings['navigaion_offset_vert'])) {
            $settings['bullets_offset_vert'] = $settings['navigaion_offset_vert'];
            $settings['thumbnails_offset_vert'] = $settings['navigaion_offset_vert'];
            unset($settings['navigaion_offset_vert']);
        }

        if (@Rbthemeslider::getIsset($settings['show_timerbar']) &&
            !@Rbthemeslider::getIsset($settings['enable_progressbar'])
        ) {
            if ($settings['show_timerbar'] == 'hide') {
                $settings['enable_progressbar'] = 'off';
                $settings['show_timerbar'] = 'top';
            } else {
                $settings['enable_progressbar'] = 'on';
            }
        }

        return $settings;
    }

    /**
     * explodes google fonts and returns the number of font weights of all fonts
     * @since: 5.0
     * */
    public static function getFontWeightCount($string)
    {
        $string = explode(':', $string);
        $nums = 0;

        if (count($string) >= 2) {
            $string = $string[1];
            if (strpos($string, '&') !== false) {
                $string = explode('&', $string);
                $string = $string[0];
            }

            $nums = count(explode(',', $string));
        }

        return $nums;
    }

    /**
     * strip slashes recursive
     * @since: 5.0
     */
    public static function stripslashesDeep($value)
    {
        if (is_array($value)) {
            $value = array_map(array(__CLASS__, 'stripslashesDeep'), $value);
        } elseif (is_object($value)) {
            $vars = get_object_vars($value);

            foreach ($vars as $key => $data) {
                $value->{$key} = self::stripslashesDeep($data);
            }
        } elseif (is_string($value)) {
            $value = Tools::stripslashes($value);
        }

        return $value;
    }

    public static function getIconSets()
    {
        $icon_sets = array();
        $icon_sets = self::setIconSets($icon_sets);

        return $icon_sets;
    }

    public static function setIconSets($icon_sets)
    {
        $icon_sets[] = 'fa-icon-';
        $icon_sets[] = 'pe-7s-';

        return $icon_sets;
    }

    public static function addWrapAroundUrl($text)
    {
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

        if (preg_match($reg_exUrl, $text, $url)) {
            return preg_replace($reg_exUrl, '<a href="' . $url[0] . '" rel="nofollow" target="_blank">'
            . $url[0] . '</a>', $text);
        } else {
            return $text;
        }
    }
}

class RbSliderBase extends UniteBaseClassRb
{
    
}
