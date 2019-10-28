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

if (!defined('__DIR__')) {
    define('__DIR__', dirname(__FILE__));
}

$dir = _PS_MODULE_DIR_ . 'rbthemeslider';

if (!defined('ABSPATH')) {
    define('ABSPATH', $dir);
}

define('PS_CONTENT_DIR', $dir);

if (!defined('ARRAY_A')) {
    define('ARRAY_A', true);
}

define('OBJECT', false);
$currentFolder = $dir;
$folderIncludes = "{$currentFolder}/library/framework/";

// include db class
require_once $currentFolder . '/library/rbslider_db.class.php';
require_once $folderIncludes . 'base.class.php';
require_once $folderIncludes . 'elements_base.class.php';
require_once $folderIncludes . 'base_admin.class.php';

//include frameword files
require_once $folderIncludes . 'include_framework.php';

// include front base
require_once $folderIncludes . 'base_front.class.php';

require_once $currentFolder . '/library/rbslider_settings_product.class.php';
require_once $currentFolder . '/library/rbslider_globals.class.php';
require_once $currentFolder . '/library/rbslider_operations.class.php';
require_once $currentFolder . '/library/rbslider_navigation.class.php';
require_once $currentFolder . '/library/rbslider_slider.class.php';
require_once $currentFolder . '/library/rbslider_output.class.php';

require_once $currentFolder . '/library/rbslider_slide.class.php';
require_once $currentFolder . '/library/rbslider_params.class.php';
require_once $currentFolder . '/library/fonts.class.php'; //punchfonts
require_once $currentFolder . '/library/hooks.class.php'; //prestashop hooks

require_once $currentFolder . '/library/rbslider_template.class.php';
require_once $currentFolder . '/library/external-sources.class.php';

function bloginfo($prop)
{
    switch ($prop) {
        case 'charset':
            echo "UTF-8";

            break;
        default:
            break;
    }
}

function psUploadDir()
{
    return array('basedir' => ABSPATH);
}

function rbGetToken()
{
    $token = Context::getcontext()->controller->token;

    if (@Rbthemeslider::getIsset($token)) {
        return $token;
    }

    return false;
}

function isMultisite()
{
    if (Shop::isFeatureActive()) {
        return true;
    } else {
        return false;
    }
}

function is_ssl()
{
    $is_secure = Tools::usingSecureMode();

    if (is_bool($is_secure)) {
        return $is_secure;
    } elseif ($is_secure == 'https') {
        return true;
    }

    return false;
}

function is_admin()
{
    $admin = @Rbthemeslider::getIsset(
        Context::getContext()->controller->admin_webpath
    );

    return $admin;
}

function rbTitle()
{
    if (is_admin()) {
        echo "Rbthemeslider";
        return;
    }

    echo "Homepage";
}

function loadAdditionalScripts($deps = array(), $parent = false)
{
    if (empty($deps) || !is_array($deps)) {
        return false;
    }

    $load = array();

    foreach ($deps as $dep) {
        switch ($dep) {
            case 'jquery':
                $load[$dep] = 'js/jquery-1.9.1.min.js';

                break;

            default:

                break;
        }
    }

    return $load;
}

function get_url($link = '')
{
    $url = '//' . Tools::getHttpHost() . _MODULE_DIR_ . "rbthemeslider";

    return $url;
}

function pluginDirPath($link = '')
{
    $url = Context::getcontext()->shop->getBaseURL() . "modules/rbthemeslider/";

    return $url;
}

function uploadsUrl($src = '')
{
    return get_url() . '/uploads/' . $src;
}

function scriptUrl()
{
    return get_url() . '/';
}

function controllerUploadUrl($link = '')
{
    $hash = Tools::encrypt(GlobalsRbSlider::MODULE_NAME);
    $cntrl = Context::getContext()->link->getAdminLink('AdminRbthemesliderUpload') . '&security_key=' . $hash;
    $url = $cntrl . $link;

    return $url;
}

function adminUrl($link = '')
{
    preg_match('/\?(.*)$/', $link, $found);

    $arr = array(
        'configure' => 'rbthemeslider',
        'module_name' => 'rbthemeslider',
        'tab_module' => 'front_office_features',
    );

    $url = Context::getContext()->link->getAdminLink('AdminModules');

    if (@Rbthemeslider::getIsset($found[1]) && !empty($found[1])) {

        $level1 = explode('&', $found[1]);

        foreach ($level1 as $level2) {
            $lv2 = explode('=', $level2);
            $arr[$lv2[0]] = $lv2[1];
        }
    }

    $url .= '&' . http_build_query($arr);

    return $url;
}

function pluginsUrl($file = '')
{
    if (!empty($file)) {
        return get_url(dirname($file));
    }

    return ABSPATH;
}

function contentUrl($link = '')
{
    return get_url($link);
}

function rbMediaFolder()
{
    $folder = _PS_ROOT_DIR_ . '/img/cms/rbthemeslider/';

    if (!file_exists($folder)) {
        if (!mkdir($folder, 0755, true)) {
            $folder = _PS_ROOT_DIR_ . 'img/cms/';
        } else {
            $folder = _PS_ROOT_DIR_ . 'img/cms/rbthemeslider/';
        }
    }

    return $folder;
}

function rbMediaFolderuri()
{
    $folder = _PS_ROOT_DIR_ . '/img/cms/rbthemeslider/';

    if (!file_exists($folder)) {
        $folder = __PS_BASE_URI__ . 'img/cms/';
    } else {
        $folder = __PS_BASE_URI__ . 'img/cms/rbthemeslider/';
    }

    return $folder;
}

function rbMediaUrl($link = '')
{
    $folder = rbMediaFolderuri() . $link;

    return $folder;
}

function getTemplateDirectoryUri()
{
    return get_url();
}

function getImageRealSize($image)
{
    $filepath = ABSPATH . '/uploads/' . $image;

    if (file_exists($filepath)) {
        return list($width, $height) = getimagesize($filepath);
    }

    return false;
}

function getImageIdByUrl($image)
{
    $psdb = rbDbClass::rbDbInstance();
    $tablename = $psdb->prefix . GlobalsRbSlider::TABLE_ATTACHMENT_IMAGES;
    $image = basename($image);
    $id = $psdb->getVar("SELECT ID FROM {$tablename} WHERE file_name='{$image}'");

    return $id;
}

function getAttachedFile($file)
{
    $filepath = ABSPATH . "/uploads/{$file}";

    return file_exists($filepath) ? $filepath : false;
}

function psGetAttachmentImageSrc($attach_id, $size = 'thumbnail', $args = array())
{
    $psdb = rbDbClass::rbDbInstance();
    $tablename = $psdb->prefix . GlobalsRbSlider::TABLE_ATTACHMENT_IMAGES;
    $filename = $psdb->getVar("SELECT file_name FROM {$tablename} WHERE ID={$attach_id}");

    if (!empty($filename)) {
        $filerealname = Tools::substr($filename, 0, strrpos($filename, '.'));

        $fileext = Tools::substr(
            $filename,
            strrpos($filename, '.'),
            Tools::strlen($filename) - Tools::strlen($filerealname)
        );

        $newfilename = $filerealname;

        if (gettype($size) == 'string') {
            switch ($size) {

                case "thumbnail":

                    $px = GlobalsRbSlider::IMAGE_SIZE_THUMBNAIL;

                    $newfilename .= "-{$px}x{$px}";

                    break;

                case "medium":

                    $px = GlobalsRbSlider::IMAGE_SIZE_MEDIUM;

                    $newfilename .= "-{$px}x{$px}";

                    break;

                case "large":

                    $px = GlobalsRbSlider::IMAGE_SIZE_LARGE;

                    $newfilename .= "-{$px}x{$px}";

                    break;

                default:
                    break;
            }

            $newfilename .= $fileext;
            $imagesize = getImageRealSize($newfilename);

            return array(uploadsUrl($newfilename), $imagesize[0], $imagesize[1]);
        }
    }

    return false;
}

function GetLinkobj()
{
    $ret = array();

    if (Tools::usingSecureMode()) {
        $useSSL = true;
    } else {
        $useSSL = false;
    }

    $protocol_link = (Configuration::get('PS_SSL_ENABLED') &&
        Configuration::get('PS_SSL_ENABLED_EVERYWHERE')
    ) ? 'https://' : 'http://';

    $protocol_content = (@Rbthemeslider::getIsset($useSSL) &&
        $useSSL &&
        Configuration::get('PS_SSL_ENABLED') &&
        Configuration::get('PS_SSL_ENABLED_EVERYWHERE')) ? 'https://' : 'http://';

    $link = new Link($protocol_link, $protocol_content);
    $ret['protocol_link'] = $protocol_link;
    $ret['protocol_content'] = $protocol_content;
    $ret['obj'] = $link;

    return $ret;
}

function modifyImageUrl($img_src = '')
{
    $lnk = GetLinkobj();
    $img_pathinfo = pathinfo($img_src);
    $mainstr = $img_pathinfo['basename'];
    $static_url = __PS_BASE_URI__ . 'modules/rbthemeslider/uploads/' . $mainstr;

    return $lnk['protocol_content'] . Tools::getMediaServer($static_url) . $static_url;
}

function modify_layer_image($img_src = '')
{
    $lnk = GetLinkobj();
    $img_pathinfo = pathinfo($img_src);
    $mainstr = $img_pathinfo['basename'];
    $static_url = __PS_BASE_URI__ . 'modules/rbthemeslider/uploads/' . $mainstr;

    return $lnk['protocol_content'] . Tools::getMediaServer($static_url) . $static_url;
}

function psEnqueueScript($scriptName, $src = '', $deps = array(), $ver = '1.0', $in_footer = false)
{
    UniteBaseClassRb::psEnqueueScript($scriptName, $src, $deps, $ver, $in_footer);
}

function psEnqueueStyle(
    $handle,
    $src = '',
    $deps = array(),
    $ver = '',
    $media = 'all',
    $noscript = false
) {
    UniteBaseClassRb::psEnqueueStyle($handle, $src, $deps, $ver, $media, $noscript);
}

function rbHead()
{
    UniteBaseClassRb::rbHead();
}

function rbFooter()
{
    UniteBaseClassRb::rbFooter();
}

function escSql($data)
{
    $psdb = rbDbClass::rbDbInstance();

    return $psdb->_escape($data);
}

function putRbSlider($data, $putIn = "")
{
    $operations = new RbOperations();
    $arrValues = $operations->getGeneralSettingsValues();
    $includesGlobally = UniteFunctionsRb::getVal($arrValues, "includes_globally", "on");
    $strPutIn = UniteFunctionsRb::getVal($arrValues, "pages_for_includes");
    $isPutIn = RbSliderOutput::isPutIn($strPutIn, true);

    if ($isPutIn == false && $includesGlobally == "off") {
        $output = new RbSliderOutput();
        $option1Name = "Include Rbthemeslider libraries globally (all pages/posts)";
        $option2Name = "Pages to include Rbthemeslider libraries";

        $output->putErrorMessage("");

        return(false);
    }

    RbSliderOutput::putSlider($data, $putIn);
}

class sdsconfig
{
    public $ocdb;

    public static function getval($key, $store_id = 0, $group = 'config')
    {
        $value = Configuration::get($key);
        
        if (@Rbthemeslider::getIsset($value)) {
            return $value;
        } else {
            return false;
        }
    }

    public static function setval(
        $key,
        $value = '',
        $group = 'config',
        $store_id = 0,
        $serialized = 0
    ) {
        $value = serialize($value);

        if (Configuration::updateValue($key, $value)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getcaptioncss($tabl)
    {
        $psdb = rbDbClass::rbDbInstance();
        $sql = "SELECT * FROM " . _DB_PREFIX_ . $tabl;
        $value = $psdb->getResults($sql);

        if (@Rbthemeslider::getIsset($value)) {
            return $value;
        } else {
            return false;
        }
    }

    public static function getgeneratecss()
    {
        $getcss = self::getcaptioncss(GlobalsRbSlider::TABLE_CSS_NAME);
        $value = UniteCssParserRb::parseDbArrayToCss($getcss, "\n");

        if (@Rbthemeslider::getIsset($value)) {
            return $value;
        } else {
            return false;
        }
    }

    public static function getgeneratecssfile()
    {
        $csscontent = sdsconfig::getgeneratecss();
        $cache_filename = RbSliderAdmin::$path_plugin . 'rs-plugin/css/captions.css';
        file_put_contents($cache_filename, $csscontent);
        chmod($cache_filename, 0777);
    }

    public static function getLayouts()
    {
        $sql = 'SELECT *
        FROM `' . _DB_PREFIX_ . 'meta` m
        INNER JOIN `' . _DB_PREFIX_ . 'meta_lang` ml
        ON(m.`id_meta` = ml.`id_meta`
        AND ml.`id_lang` = ' . (int) Context::getContext()->language->id . '
        AND ml.`id_shop` = ' . (int) Context::getContext()->shop->id . ')';

        $meta = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return $meta;
    }

    public static function getrbslide()
    {
        $result = array();
        $psdb = rbDbClass::rbDbInstance();
        $sql = "SELECT * FROM " . $psdb->prefix . GlobalsRbSlider::TABLE_SLIDERS_NAME;
        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        //$data = $psdb->getResults($sql);
        if (!empty($data)) {
            $i = 0;

            foreach ($data as $val) {
                $result[$i]['id'] = $val['id'];
                $result[$i]['title'] = $val['title'];
                $i = $i + 1;
            }
        }

        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    public static function getCurrentStore()
    {
        $store_id = (int) Context::getContext()->shop->id;

        if (!@Rbthemeslider::getIsset($store_id)) {
            $store_id = 1;
        }

        return $store_id;
    }

    public static function getNameById($id)
    {
        $sql = 'SELECT name
        FROM ' . _DB_PREFIX_ . 'shop_group
        WHERE id_shop_group = ' . $id;

        return Db::getInstance()->getValue($sql);
    }
}

function psMkdirP($target)
{
    $wrapper = null;

    // Strip the protocol.
    if (psIsStream($target)) {
        list($wrapper, $target) = explode('://', $target, 2);
    }

    // From php.net/mkdir user contributed notes.
    $target = str_replace('//', '/', $target);

    // Put the wrapper back on the target.
    if ($wrapper !== null) {
        $target = $wrapper . '://' . $target;
    }

    $target = rtrim($target, '/');

    if (empty($target)) {
        $target = '/';
    }

    if (file_exists($target)) {
        return @is_dir($target);
    }

    // We need to find the permissions of the parent folder that exists and inherit that.
    $target_parent = dirname($target);

    while ('.' != $target_parent && !is_dir($target_parent)) {
        $target_parent = dirname($target_parent);
    }

    // Get the permission bits.
    if ($stat = @stat($target_parent)) {
        $dir_perms = $stat['mode'] & 0007777;
    } else {
        $dir_perms = 0777;
    }

    if (@mkdir($target, $dir_perms, true)) {
        if ($dir_perms != ($dir_perms & ~umask())) {
            $folder_parts = explode('/', Tools::substr($target, Tools::strlen($target_parent) + 1));
            for ($i = 1, $c = count($folder_parts); $i <= $c; $i++) {
                @chmod($target_parent . '/' . implode('/', array_slice($folder_parts, 0, $i)), $dir_perms);
            }
        }

        return true;
    }

    return false;
}

function psIsStream($path)
{
    $wrappers = stream_get_wrappers();
    $wrappers_re = '(' . join('|', $wrappers) . ')';

    return preg_match("!^$wrappers_re://!", $path) === 1;
}

function checkedSelectedHelper($helper, $current, $echo, $type)
{
    if ((string) $helper === (string) $current) {
        $result = " $type='$type'";
    } else {
        $result = '';
    }

    if ($echo) {
        echo $result;
    } else {
        return $result;
    }
}

function selected($selected, $current = true, $echo = true)
{
    return checkedSelectedHelper($selected, $current, $echo, 'selected');
}

function checked($checked, $current = true, $echo = true)
{
    return checkedSelectedHelper($checked, $current, $echo, 'checked');
}

function size_format($bytes, $decimals = 0)
{
    $quant = array(
        'TB' => 1099511627776, // pow( 1024, 4)
        'GB' => 1073741824, // pow( 1024, 3)
        'MB' => 1048576, // pow( 1024, 2)
        'kB' => 1024, // pow( 1024, 1)
        'B' => 1, // pow( 1024, 0)
    );

    foreach ($quant as $unit => $mag) {
        if (doubleval($bytes) >= $mag) {
            return numberFormatI18n($bytes / $mag, $decimals) . ' ' . $unit;
        }
    }

    return false;
}

function numberFormatI18n($number, $decimals = 0)
{
    $formatted = number_format($number, absint($decimals), '.', ',');

    return $formatted;
}

function absint($maybeint)
{
    return abs((int) ($maybeint));
}

function currentTime($type, $gmt = 0)
{
    switch ($type) {
        case 'mysql':
            return ($gmt) ? gmdate('Y-m-d H:i:s') : gmdate('Y-m-d H:i:s', (time() + (0 * (60 * 60))));
        case 'timestamp':
            return ($gmt) ? time() : time() + (0 * (60 * 60));
        default:
            return ($gmt) ? date($type) : date($type, time() + (0 * (60 * 60)));
    }
}

function getTransient($option_name)
{
    $main_opt_name = "_trns_{$option_name}";
    $return = false;
    $psdb = rbDbClass::rbDbInstance();

    $result = $psdb->getRow(
        "SELECT * FROM `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
        WHERE `name`='{$main_opt_name}'"
    );

    $return_temp = unserialize(Tools::stripslashes($result['value']));

    if ($result && is_array($result)) {
        if ($return_temp['reset_time'] >= time()) {
            $return = $return_temp['data'];
        }
    }

    return $return;
}

function setTransient($option_name, $option_value, $reset_time = 1200)
{
    $main_opt_name = "_trns_{$option_name}";
    $psdb = rbDbClass::rbDbInstance();
    $serialized_data = array();
    $serialized_data['reset_time'] = time() + $reset_time;
    $serialized_data['data'] = $option_value;
    $serialized_data = addslashes(serialize($serialized_data));

    $is_exist = $psdb->getRow(
        "SELECT * FROM `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
        WHERE `name`='{$main_opt_name}'"
    );

    $result_temp = unserialize(Tools::stripslashes($is_exist['value']));

    if (!$is_exist || $result_temp['reset_time'] < time()) {
        if ($is_exist) {
            $psdb->query(
                "UPDATE `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
                SET `value`='" . $serialized_data . "'
                WHERE `name`='{$main_opt_name}';"
            );
        } else {
            $psdb->query(
                "INSERT INTO `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
                (`id`, `name`, `value`) VALUES (NULL, '" . $main_opt_name . "', '" .
                $serialized_data . "');"
            );
        }
    }
}

function psRemoteFopen($Url)
{
    $UserAgentList = array();
    $UserAgentList[] = "Mozilla/4.0 (compatible; MSIE 6.0; X11; Linux i686; en) Opera 8.01";
    $UserAgentList[] = "Mozilla/5.0 (compatible; Konqueror/3.3; Linux) (KHTML, like Gecko)";
    $UserAgentList[] = "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2";
    $UserAgentList[] = "Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9.2.25) Gecko/20111212 Firefox/3.6.25";
    $UserAgentList[] = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.52.7 (KHTML, like Gecko) Version/5.1.2 Safari/534.52.7";
    $UserAgentList[] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; Win64; x64; SV1; .NET CLR 2.0.50727)";
    $UserAgentList[] = "Mozilla/5.0 (Windows NT 6.1; rv:8.0.1) Gecko/20100101 Firefox/8.0.1";
    $UserAgentList[] = "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.63 Safari/535.7";

    $hcurl = curl_init();

    curl_setopt($hcurl, CURLOPT_URL, $Url);
    curl_setopt($hcurl, CURLOPT_USERAGENT, $UserAgentList[array_rand($UserAgentList)]);
    curl_setopt($hcurl, CURLOPT_TIMEOUT, 60);
    curl_setopt($hcurl, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($hcurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($hcurl, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($hcurl);
    curl_close($hcurl);

    return $result;
}

function smartMergeAttrs($pairs, $atts)
{
    $atts = (array) $atts;
    $out = array();

    foreach ($pairs as $name => $default) {
        if (array_key_exists($name, $atts)) {
            $out[$name] = $atts[$name];
        } else {
            $out[$name] = $default;
        }
    }

    return $out;
}

function maybeUnserialize($original)
{
    if (isSerialized($original)) {
        return @unserialize($original);
    }

    return $original;
}

function isSerialized($data, $strict = true)
{

    if (!is_string($data)) {
        return false;
    }

    $data = trim($data);

    if ('N;' == $data) {
        return true;
    }
    if (Tools::strlen($data) < 4) {
        return false;
    }
    if (':' !== $data[1]) {
        return false;
    }
    if ($strict) {
        $lastc = Tools::substr($data, -1);
        if (';' !== $lastc && '}' !== $lastc) {
            return false;
        }
    } else {
        $semicolon = strpos($data, ';');
        $brace = strpos($data, '}');
        // Either ; or } must exist.
        if (false === $semicolon && false === $brace) {
            return false;
        }
        // But neither must be in the first X characters.
        if (false !== $semicolon && $semicolon < 3) {
            return false;
        }
        if (false !== $brace && $brace < 4) {
            return false;
        }
    }

    $token = $data[0];

    switch ($token) {
        case 's':
            if ($strict) {
                if ('"' !== Tools::substr($data, -2, 1)) {
                    return false;
                }
            } elseif (false === strpos($data, '"')) {
                return false;
            }
        case 'a':
        case 'O':
            return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
        case 'b':
        case 'i':
        case 'd':
            $end = $strict ? '$' : '';
            return (bool) preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
    }

    return false;
}

function sanitizeTitle($title)
{
    $raw_title = $title;
    $title = Tools::strtolower($title);
    $title = str_replace(' ', '-', $title);
    $title = preg_replace('/[^A-Za-z0-9\-]/', '', $title);

    return $title;
}

function psStripAllTags($string, $remove_breaks = false)
{
    $string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);
    $string = strip_tags($string);

    if ($remove_breaks) {
        $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
    }

    return trim($string);
}

function psPreKsesLessThan($text)
{
    return preg_replace_callback('%<[^>]*?((?=<)|>|$)%', 'psPreKsesLessThan_callback', $text);
}

function sanitizeTextField($str)
{
    $filtered = $str;

    if (strpos($filtered, '<') !== false) {
        $filtered = psPreKsesLessThan($filtered);

        // This will strip extra whitespace for us.
        $filtered = psStripAllTags($filtered, true);
    } else {
        $filtered = trim(preg_replace('/[\r\n\t ]+/', ' ', $filtered));
    }

    $found = false;

    while (preg_match('/%[a-f0-9]{2}/i', $filtered, $match)) {
        $filtered = str_replace($match[0], '', $filtered);
        $found = true;
    }

    if ($found) {
        $filtered = trim(preg_replace('/ +/', ' ', $filtered));
    }

    return $filtered;
}

function throwError($message, $code = null)
{
    UniteFunctionsRb::throwError($message, $code);
}

function updateOption($key, $value)
{
    $psdb = rbDbClass::rbDbInstance();

    $is_exist = $psdb->getVar(
        "SELECT id FROM `{$psdb->prefix}" . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
        WHERE `name`='{$key}'"
    );

    if (is_array($value)) {
        $value = serialize($value);
    }

    if (!empty($is_exist)) {
        $psdb->query(
            "UPDATE `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
            SET `value`='{$value}' WHERE `id`={$is_exist}
            AND `name`='{$key}';"
        );
    } else {
        $psdb->query(
            "INSERT INTO `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "` (`name`, `value`)
            VALUES ('{$key}', '{$value}');"
        );
    }

    return true;
}

function getOption($key, $default = false)
{
    $psdb = rbDbClass::rbDbInstance();

    $value = $psdb->getVar(
        "SELECT value FROM `{$psdb->prefix}" . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
        WHERE `name`='{$key}'"
    );

    return $value !== false ? $value : $default;
}

function psRemoteGet($url, $args = array())
{
    $obj = new RbGlobalObject();

    if (is_callable(array($obj, 'getHttpCurl'))) {
        return $obj->getHttpCurl($url, $args);
    }
    return false;
}

function psRemotePost($url, $args = array())
{
    return psRemoteGet($url, $args);
}

if (!defined('RS_PLUGIN_URL')) {
    define('RS_PLUGIN_URL', get_url() . '/');
}

function psRemoteRetrieveResponseCode($response)
{
    if (!isset($response['info']['http_code']) || !is_array($response['info'])) {
        return '';
    }

    return $response['info']['http_code'];
}

function psRemoteRetrieveBody($response)
{
    if (!isset($response['body'])) {
        return '';
    }

    return $response['body'];
}

function esc_attr($string)
{
    return Tools::safeOutput($string);
}


class RbGlobalObject
{
    public static $objOperation;
    public static $dynamicObj = array();
    public $headers;
    public $body;

    public function __construct()
    {
        $this->headers = '';
        $this->body = '';
    }

    public function getHttpCurl($url, $args)
    {
        if (function_exists('curl_init')) {
            $defaults = array(
                'method' => 'GET',
                'timeout' => 5,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                    'Authorization' => 'Basic ',
                    'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                    'Accept-Encoding' => 'gzip'
                ),
                'body' => array(),
                'cookies' => array(),
                'user-agent' => 'Prestashop/' . _PS_VERSION_,
                'header' => false,
                'sslverify' => true,
            );

            $args = smartMergeAttrs($defaults, $args);
            $curl_timeout = ceil($args['timeout']);
            $curl = curl_init();

            if ($args['httpversion'] == '1.0') {
                curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            } else {
                curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            }

            curl_setopt($curl, CURLOPT_USERAGENT, $args['user-agent']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $curl_timeout);
            curl_setopt($curl, CURLOPT_TIMEOUT, $curl_timeout);
            $ssl_verify = $args['sslverify'];
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ssl_verify);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, ( $ssl_verify === true ) ? 2 : false );

            if ($ssl_verify) {
                curl_setopt($curl, CURLOPT_CAINFO, ABSPATH . '/views/ssl/ca-bundle.crt');
            }

            curl_setopt($curl, CURLOPT_HEADER, $args['header']);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);

            if (defined('CURLOPT_PROTOCOLS')) {
                curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
            }

            $http_headers = array();

            foreach ($args['headers'] as $key => $value) {
                $http_headers[] = "{$key}: {$value}";
            }

            if (is_array($args['body']) || is_object($args['body'])) {
                $args['body'] = http_build_query($args['body']);
            }

            $http_headers[] = 'Content-Length: ' . Tools::strlen($args['body']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $http_headers);

            switch ($args['method']) {
                case 'HEAD':
                    curl_setopt($curl, CURLOPT_NOBODY, true);
                    break;
                case 'POST':
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $args['body']);
                    break;
                case 'PUT':
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $args['body']);
                    break;
                default:
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $args['method']);
                    if (!is_null($args['body'])) {
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $args['body']);
                    }
                    break;
            }

            curl_setopt($curl, CURLOPT_HEADERFUNCTION, array($this, 'streamHeaders'));
            curl_setopt($curl, CURLOPT_WRITEFUNCTION, array($this, 'streamBody'));
            curl_exec($curl);

            $responseBody = $this->body;
            $responseHeader = $this->headers;

            if (self::shouldDecode($responseHeader) === true) {
                $responseBody = self::decompress($responseBody);
            }

            $this->body = '';
            $this->headers = '';

            $error = curl_error($curl);
            $errorcode = curl_errno($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);

            $response = array(
                'body' => $responseBody,
                'headers' => $responseHeader,
                'info' => $info,
                'error' => $error,
                'errno' => $errorcode
            );

            return $response;
        }

        return false;
    }

    private function streamHeaders($handle, $headers)
    {
        $this->headers .= $headers;
        return Tools::strlen($headers);
    }

    private function streamBody($handle, $data)
    {
        $data_length = strlen($data);
        $this->body .= $data;

        return $data_length;
    }

    /**
     * Decompression of deflated string.
     *
     * Will attempt to decompress using the RFC 1950 standard, and if that fails
     * then the RFC 1951 standard deflate will be attempted. Finally, the RFC
     * 1952 standard gzip decode will be attempted. If all fail, then the
     * original compressed string will be returned.
     *
     * @since 2.8.0
     *
     * @static
     *
     * @param string $compressed String to decompress.
     * @param int $length The optional length of the compressed data.
     * @return string|bool False on failure.
     */
    public static function decompress($compressed, $length = null)
    {

        if (empty($compressed))
            return $compressed;

        if (false !== ( $decompressed = @gzinflate($compressed) ))
            return $decompressed;

        if (false !== ( $decompressed = self::compatibleGzinflate($compressed) ))
            return $decompressed;

        if (false !== ( $decompressed = @gzuncompress($compressed) ))
            return $decompressed;

        if (function_exists('gzdecode')) {
            $decompressed = @gzdecode($compressed);

            if (false !== $decompressed)
                return $decompressed;
        }

        return $compressed;
    }

    /**
     * Whether the content be decoded based on the headers.
     *
     * @since 2.8.0
     *
     * @static
     *
     * @param array|string $headers All of the available headers.
     * @return bool
     */
    public static function shouldDecode($headers)
    {
        if (is_array($headers)) {
            if (array_key_exists('content-encoding', $headers) && !empty($headers['content-encoding']))
                return true;
        } elseif (is_string($headers)) {
            return ( stripos($headers, 'content-encoding:') !== false );
        }

        return false;
    }

    /**
     * Decompression of deflated string while staying compatible with the majority of servers.
     *
     * Certain Servers will return deflated data with headers which PHP's gzinflate()
     * function cannot handle out of the box. The following function has been created from
     * various snippets on the gzinflate() PHP documentation.
     *
     * Warning: Magic numbers within. Due to the potential different formats that the compressed
     * data may be returned in, some "magic offsets" are needed to ensure proper decompression
     * takes place. For a simple progmatic way to determine the magic offset in use, see:
     * https://core.trac.wordpress.org/ticket/18273
     *
     * @since 2.8.1
     * @link https://core.trac.wordpress.org/ticket/18273
     * @link http://au2.php.net/manual/en/function.gzinflate.php#70875
     * @link http://au2.php.net/manual/en/function.gzinflate.php#77336
     *
     * @static
     *
     * @param string $gzData String to decompress.
     * @return string|bool False on failure.
     */
    public static function compatibleGzinflate($gzData)
    {
        if (substr($gzData, 0, 3) == "\x1f\x8b\x08") {
            $i = 10;
            $flg = ord(substr($gzData, 3, 1));

            if ($flg > 0) {
                if ($flg & 4) {
                    list($xlen) = unpack('v', substr($gzData, $i, 2));
                    $i = $i + 2 + $xlen;
                }
                if ($flg & 8)
                    $i = strpos($gzData, "\0", $i) + 1;
                if ($flg & 16)
                    $i = strpos($gzData, "\0", $i) + 1;
                if ($flg & 2)
                    $i = $i + 2;
            }

            $decompressed = @gzinflate(substr($gzData, $i, -8));

            if (false !== $decompressed)
                return $decompressed;
        }

        // Compressed data from java.util.zip.Deflater amongst others.
        $decompressed = @gzinflate(substr($gzData, 2));
        if (false !== $decompressed)
            return $decompressed;

        return false;
    }

    public static function getOpInstance()
    {
        if (!self::$objOperation instanceof RbOperations) {
            self::$objOperation = new RbOperations();
        }
        return self::$objOperation;
    }

    public static function getIsset($variable)
    {
        return isset($variable);
    }

    public static function setVar($key = null, $value = null)
    {
        if (!empty($key) && !is_null($value)) {
            self::$dynamicObj[$key] = $value;
        }
    }

    public static function getVar($key = null)
    {
        if (@Rbthemeslider::getIsset(self::$dynamicObj[$key])) {
            return self::$dynamicObj[$key];
        }
        
        return null;
    }

    public static function reset()
    {
        self::$dynamicObj = array();
    }
}
