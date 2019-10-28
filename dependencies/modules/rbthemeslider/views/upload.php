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

$current_path = '';
$ext_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg'); //
$ext=array_merge($ext_img);

include('config/config.php');
include('include/utils.php');
include_once(_PS_MODULE_DIR_ . 'rbthemeslider/rbprestashoploader.php');
include_once(_PS_MODULE_DIR_ . 'rbthemeslider/rbslider_admin.php');

$storeFolder = $current_path.Tools::getValue('path');

if (empty($storeFolder)) {
    die('wrong path');
}

$path = $storeFolder;
$cycle = true;
$max_cycles = 50;
$i = 0;

while ($cycle && $i < $max_cycles) {
    $i++;
    if ($path == $current_path) {
        $cycle = false;
    }
    if (file_exists($path . 'config.php')) {
        require_once($path . 'config.php');
        $cycle = false;
    }
    $path = fixDirname($path) . '/';
}

if (!empty($_FILES)) {
    $info = pathinfo($_FILES['file']['name']);
    if (@Rbthemeslider::getIsset($info['extension']) &&
        in_array(fixStrtolower($info['extension']),$ext)
    ) {
        $tempFile = $_FILES['file']['tmp_name'];

        if (in_array(fixStrtolower($info['extension']), $ext_img) && @getimagesize($tempFile) != false) {
            $is_img = true;
        } else {
            $is_img = false;
        }

        if ($is_img) {
            $targetFolder = ABSPATH . '/uploads/';

            $NewFileName = preg_replace_callback(
                '/[^a-zA-Z0-9_\-]+/',
                function($match){return "-";},
                $info['filename']
            );

            $targetPath = $targetFolder;
            $fileTypes = array('jpg', 'jpeg', 'gif', 'png');

            if (in_array($info['extension'], $fileTypes)) {
                $worked = UniteFunctionsPSRb::importMediaImg(
                    $tempFile,
                    $targetPath,
                    $NewFileName . '.' . $info['extension']
                );
                if (!empty($worked)) {
                    echo '1';
                }
            } else {
                echo '0';
            }
        }
    } else {
        header('HTTP/1.1 406 file not permitted', true, 406);
        exit();
    }
} else {
    header('HTTP/1.1 405 Bad Request', true, 405);
    exit();
}

if (Tools::isSubmit('submit')) {
    $query = http_build_query(
            array(
                'type' => Tools::getValue('type'),
                'lang' => Tools::getValue('lang'),
                'popup' => Tools::getValue('popup'),
                'field_id' => Tools::getValue('field_id'),
                'fldr' => Tools::getValue('fldr'),
            )
    );

    Tools::redirect(get_url().'/views/dialog.php?' . $query);
}
