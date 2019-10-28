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

$context = Context::getContext();

if (!Employee::checkPassword((int) $context->cookie->id_employee, $context->cookie->passwd)
) {
    die('forbiden');
}

function deleteDir($dir)
{
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDir($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    return rmdir($dir);
}

function duplicateFile($old_path, $name)
{
    if (file_exists($old_path)) {
        $info = pathinfo($old_path);
        $new_path = $info['dirname'] . "/" . $name . "." . $info['extension'];

        if (file_exists($new_path)) {
            return false;
        }

        return copy($old_path, $new_path);
    }
}

function renameFile($old_path, $name, $transliteration)
{
    $name = fixFilename($name, $transliteration);

    if (file_exists($old_path)) {
        $info = pathinfo($old_path);
        $new_path = $info['dirname'] . "/" . $name . "." . $info['extension'];

        if (file_exists($new_path)) {
            return false;
        }

        return rename($old_path, $new_path);
    }
}

function renameFolder($old_path, $name, $transliteration)
{
    $name = fixFilename($name, $transliteration);

    if (file_exists($old_path)) {
        $new_path = fixDirname($old_path) . "/" . $name;

        if (file_exists($new_path)) {
            return false;
        }

        return rename($old_path, $new_path);
    }
}

function makeSize($size)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $u = 0;

    while ((round($size / 1024) > 0) && ($u < 4)) {
        $size = $size / 1024;
        $u++;
    }

    return (number_format($size, 0) . " " . $units[$u]);
}

function foldersize($path)
{
    $total_size = 0;
    $files = scandir($path);
    $cleanPath = rtrim($path, '/') . '/';

    foreach ($files as $t) {
        if ($t <> "." && $t <> "..") {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
                $size = foldersize($currentFile);
                $total_size += $size;
            } else {
                $size = filesize($currentFile);
                $total_size += $size;
            }
        }
    }

    return $total_size;
}

function createFolder($path = false, $path_thumbs = false)
{
    $oldumask = umask(0);

    if ($path && !file_exists($path)) {
        mkdir($path, 0777, true);
    }

    if ($path_thumbs && !file_exists($path_thumbs)) {
        mkdir($path_thumbs, 0777, true) || die("$path_thumbs cannot be found");
    }

    umask($oldumask);
}

function checkFilesExtensionsOnPath($path, $ext)
{
    if (!is_dir($path)) {
        $fileinfo = pathinfo($path);

        if (!in_array(mb_strtolower($fileinfo['extension']), $ext)) {
            unlink($path);
        }
    } else {
        $files = scandir($path);
        foreach ($files as $file) {
            checkFilesExtensionsOnPath(trim($path, '/') . "/" . $file, $ext);
        }
    }
}

function checkFilesExtensionsOnPhar($phar, &$files, $basepath, $ext)
{
    foreach ($phar as $file) {
        if ($file->isFile()) {
            if (in_array(mb_strtolower($file->getExtension()), $ext)) {
                $files[] = $basepath . $file->getFileName();
            }
        } elseif ($file->isDir()) {
            $iterator = new DirectoryIterator($file);
            checkFilesExtensionsOnPhar($iterator, $files, $basepath . $file->getFileName() . '/', $ext);
        }
    }
}

function fixFilename($str, $transliteration)
{
    if ($transliteration) {
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        $str = preg_replace("/[^a-zA-Z0-9\.\[\]_| -]/", '', $str);
    }

    $str = str_replace(array('"', "'", "/", "\\"), "", $str);
    $str = strip_tags($str);

    if (strpos($str, '.') === 0) {
        $str = 'file' . $str;
    }

    return trim($str);
}

function fixDirname($str)
{
    return str_replace('~', ' ', dirname(str_replace(' ', '~', $str)));
}

function fixStrtoupper($str)
{
    if (function_exists('mb_strtoupper')) {
        return mb_strtoupper($str);
    } else {
        return Tools::strtoupper($str);
    }
}

function fixStrtolower($str)
{
    if (function_exists('mb_strtoupper')) {
        return mb_strtolower($str);
    } else {
        return Tools::strtolower($str);
    }
}

function fixPath($path, $transliteration)
{
    $info = pathinfo($path);

    if (($s = strrpos($path, '/')) !== false) {
        $s++;
    }

    if (($e = strrpos($path, '.') - $s) !== Tools::strlen($info['filename'])) {
        $info['filename'] = Tools::substr($path, $s, $e);
        $info['basename'] = Tools::substr($path, $s);
    }

    $tmp_path = $info['dirname'] . DIRECTORY_SEPARATOR . $info['basename'];
    $str = fixFilename($info['filename'], $transliteration);

    if ($tmp_path != "") {
        return $tmp_path . DIRECTORY_SEPARATOR . $str;
    } else {
        return $str;
    }
}

function baseUrl()
{
    return sprintf(
        "%s://%s", @Rbthemeslider::getIsset($_SERVER['HTTPS']) &&
        $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['HTTP_HOST']
    );
}

function configLoading($current_path, $fld)
{
    if (file_exists($current_path . $fld . ".config")) {
        require_once($current_path . $fld . ".config");
        return true;
    }

    echo "!!!!" . $parent = fixDirname($fld);

    if ($parent != "." && !empty($parent)) {
        configLoading($current_path, $parent);
    }

    return false;
}
