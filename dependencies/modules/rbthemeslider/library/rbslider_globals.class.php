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

define("RBSLIDER_TEXTDOMAIN", "rbslider");

class GlobalsRbSlider
{
    const MODULE_NAME = 'rbthemeslider';
    const SHOW_DEBUG = false;
    const SLIDER_RBISION = _RB_VERSION_;
    const TABLE_SLIDERS_NAME = "rbslider_sliders";
    const TABLE_SLIDES_NAME = "rbslider_slides";
    const TABLE_STATIC_SLIDES_NAME = "rbslider_static_slides";
    const TABLE_SETTINGS_NAME = "rbslider_settings";
    const TABLE_CSS_NAME = "rbslider_css";
    const TABLE_LAYER_ANIMS_NAME = "rbslider_layer_animations";
    const TABLE_RBSLIDER_OPTIONS_NAME = "rbslider_options";
    const TABLE_NAVIGATION_NAME = "rbslider_navigations";
    const TABLE_ATTACHMENT_IMAGES = "rbslider_attachment_images";
    const IMAGE_SIZE_THUMBNAIL = 150;
    const IMAGE_SIZE_MEDIUM = 300;
    const IMAGE_SIZE_LARGE = 1024;
    const FIELDS_SLIDE = "slider_id,slide_order,params,layers";
    const FIELDS_SLIDER = "title,alias,params";
    const YOUTUBE_EXAMPLE_ID = "cXwQjHRZieI";
    const DEFAULT_YOUTUBE_ARGUMENTS = "hd=1&amp;wmode=opaque&amp;controls=1&amp;showinfo=0;rel=0;";
    const DEFAULT_VIMEO_ARGUMENTS = "title=0&amp;byline=0&amp;portrait=0;api=1";
    const LINK_HELP_SLIDERS = "#";
    const LINK_HELP_SLIDER = "#";
    const LINK_HELP_SLIDE_LIST = "#";
    const LINK_HELP_SLIDE = "#";

    public static $table_sliders;
    public static $table_slides;
    public static $table_static_slides;
    public static $table_settings;
    public static $table_css;
    public static $table_layer_anims;
    public static $table_navigation;
    public static $table_options;
    public static $filepath_backup;
    public static $filepath_captions;
    public static $filepath_dynamic_captions;
    public static $filepath_static_captions;
    public static $filepath_captions_original;
    public static $urlCaptionsCSS;
    public static $urlStaticCaptionsCSS;
    public static $urlExportZip;
    public static $isNewVersion;
}

class RBSliderGlobals extends GlobalsRbSlider
{
	
}
