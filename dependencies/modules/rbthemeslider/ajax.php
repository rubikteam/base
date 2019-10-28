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


include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('library/rbslider_globals.class.php');
include_once('library/rbslider_db.class.php');

$action = Tools::getValue('action');
$mod_url = context::getcontext()->shop->getBaseURL() . "modules/rbthemeslider/";

switch ($action) {
    case 'rbthemeslider_show_image':
        $imgsrc = Tools::getValue('img');

        if ($imgsrc) {
            if (is_numeric($imgsrc)) {
                $table = _DB_PREFIX_ . GlobalsRbSlider::TABLE_ATTACHMENT_IMAGES;

                $result = rbDbClass::rbDbInstance()->getVar(
                    "SELECT file_name FROM {$table} WHERE ID={$imgsrc}"
                );

                if (empty($result)) {
                    die();
                }

                $imgsrc = "uploads/$result";
            } else {
                $imgsrc = str_replace('../', '', urldecode($imgsrc));
            }

            if (strpos($imgsrc, 'uploads') !== false) {
                $file = @getimagesize($imgsrc);

                if (!empty($file) && @Rbthemeslider::getIsset($file['mime'])) {
                    $size = GlobalsRbSlider::IMAGE_SIZE_MEDIUM;
                    $filename = basename($imgsrc);
                    $filetitle = Tools::substr($filename, 0, strrpos($filename, '.'));
                    $fileext = Tools::substr($filename, strrpos($filename, '.'));
                    $newfile = "uploads/{$filetitle}-{$size}x{$size}{$fileext}";

                    if ($newfilesize = @getimagesize($newfile)) {
                        $file = $newfilesize;
                        $imgsrc = $newfile;
                    }

                    header('Content-Type:' . $file['mime']);
                    echo Tools::file_get_contents($mod_url . $imgsrc);
                }
            }
        }

    break;
}

die();
