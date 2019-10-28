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

mb_internal_encoding('UTF-8');
$base_url="http://".$_SERVER['HTTP_HOST'];
$upload_dir = __PS_BASE_URI__.'modules/rbthemeslider/uploads/';
$current_path = _PS_ROOT_DIR_.'/modules/rbthemeslider/uploads/';
$thumbs_base_path = _PS_ROOT_DIR_.'/modules/rbthemeslider/uploads/';
$MaxSizeUpload=100;
$default_language="en";
$icon_theme="ico";
$show_folder_size=false;
$show_sorting_bar=true;
$loading_bar=true;
$transliteration=false;
$image_max_width=0;
$image_max_height=0;
$image_resizing=false;
$image_resizing_width=0;
$image_resizing_height=0;
$default_view=0;
$ellipsis_title_after_first_row=true;
$delete_files=true;
$create_folders=false;
$delete_folders=false;
$upload_files=true;
$rename_files=true;
$rename_folders=false;
$duplicate_files=false;
$ext_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg'); //
$ext=array_merge($ext_img);
$aviary_active=false;
$aviary_key="dvh8qudbp6yx2bnp";
$aviary_secret="m6xaym5q42rpw433";
$aviary_version=3;
$aviary_language='en';
$file_number_limit_js=500;
$hidden_folders = array();
$hidden_files = array('config.php');
$java_upload=false;
$JAVAMaxSizeUpload=200; //Gb
$fixed_image_creation                   = false;
$fixed_path_from_filemanager            = array('../test/','../test1/');
$fixed_image_creation_name_to_prepend   = array('','test_');
$fixed_image_creation_to_append         = array('_test','');
$fixed_image_creation_width             = array(300,400);
$fixed_image_creation_height            = array(200,'');
$relative_image_creation                = false;
$relative_path_from_current_pos         = array('thumb/','thumb/');
$relative_image_creation_name_to_prepend= array('','test_');
$relative_image_creation_name_to_append = array('_test','');
$relative_image_creation_width          = array(300,400);
$relative_image_creation_height         = array(200,'');
