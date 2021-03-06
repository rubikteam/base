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

if (!defined('ABSPATH')) {
    exit();
}

class RbSliderPluginUpdate
{
    /**
     * @since 5.0
     */
    public function __construct()
    {

    }

    public static function doUpdateChecks()
    {
        self::updateCssStyles(); //update styles to the new 5.0 way
        self::addV5Styles(); //add the version 5 styles that are new!
        self::moveTemplateSlider(); //move template sliders slides to the post based sliders and delete them/move them if not used
        self::addAnimationSettingsToLayer(); //set missing animation fields to the slides layers
        self::addStyleSettingsToLayer(); //set missing styling fields to the slides layers
        self::changeSettingsOnLayers(); //change settings on layers, for example, add the new structure of actions
        self::removeStaticSlides(); //remove static slides if the slider was v4 and had static slides which were not enabled
        $version = 5.0;
        
        if (version_compare($version, '5.0.7', '<')) {
            $version = '5.0.7';
            self::changeGeneralSettings507();
        }
        
        if (version_compare($version, '5.1.1', '<')) {
            $version = '5.1.1';
            self::changeGeneralSettings511();
        }
    }
    
    
    /**
     * add new styles for version 5.0
     * @since 5.0
     */
    public static function addV5Styles()
    {
        $v5 = array(
            array('handle' => '.tp-caption.MarkerDisplay','settings' => '{"translated":5,"type":"text","version":"5.0"}','hover' => '{"color":"#ff0000","text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-style":"none","border-width":"0","border-radius":["0px","0px","0px","0px"],"skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0"}','params' => '{"font-style":"normal","font-family":"Permanent Marker","padding":"0px 0px 0px 0px","text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"#000000","border-style":"none","border-width":"0px","border-radius":"0px 0px 0px 0px","z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"text-shadow":"none"},"hover":""}'),
            array('handle' => '.tp-caption.Restaurant-Display','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0"}','params' => '{"color":"#ffffff","font-size":"120px","line-height":"120px","font-weight":"700","font-style":"normal","font-family":"Roboto","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.Restaurant-Cursive','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0"}','params' => '{"color":"#ffffff","font-size":"30px","line-height":"30px","font-weight":"400","font-style":"normal","font-family":"Nothing you could do","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.Restaurant-ScrollDownText','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0"}','params' => '{"color":"#ffffff","font-size":"17px","line-height":"17px","font-weight":"400","font-style":"normal","font-family":"Roboto","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.Restaurant-Description','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0"}','params' => '{"color":"#ffffff","font-size":"20px","line-height":"30px","font-weight":"300","font-style":"normal","font-family":"Roboto","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"3px"},"hover":""}'),
            array('handle' => '.tp-caption.Restaurant-Price','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0"}','params' => '{"color":"#ffffff","font-size":"30px","line-height":"30px","font-weight":"300","font-style":"normal","font-family":"Roboto","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"3px"},"hover":""}'),
            array('handle' => '.tp-caption.Restaurant-Menuitem','settings' => '{"hover":"false","type":"text","version":"5.0","translated":"5"}','hover' => '{"color":"#000000","color-transparency":"1","text-decoration":"none","background-color":"#ffffff","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"500","easing":"Power2.easeInOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"17px","line-height":"17px","font-weight":"400","font-style":"normal","font-family":"Roboto","padding":["10px","30px","10px","30px"],"text-decoration":"none","text-align":"left","background-color":"#000000","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.Furniture-LogoText','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#e6cfa3","color-transparency":"1","font-size":"160px","line-height":"150px","font-weight":"300","font-style":"normal","font-family":"\\"Raleway\\"","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"text-shadow":"none"},"hover":""}'),
            array('handle' => '.tp-caption.Furniture-Plus','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"#000000","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["30px","30px","30px","30px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0.5","easing":"Linear.easeNone"}','params' => '{"color":"#e6cfa3","color-transparency":"1","font-size":"20","line-height":"20px","font-weight":"400","font-style":"normal","font-family":"\\"Raleway\\"","padding":["6px","7px","4px","7px"],"text-decoration":"none","background-color":"#ffffff","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["30px","30px","30px","30px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"text-shadow":"none","box-shadow":"rgba(0,0,0,0.1) 0 1px 3px"},"hover":""}'),
            array('handle' => '.tp-caption.Furniture-Title','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#000000","color-transparency":"1","font-size":"20px","line-height":"20px","font-weight":"700","font-style":"normal","font-family":"\\"Raleway\\"","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"text-shadow":"none","letter-spacing":"3px"},"hover":""}'),
            array('handle' => '.tp-caption.Furniture-Subtitle','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#000000","color-transparency":"1","font-size":"17px","line-height":"20px","font-weight":"300","font-style":"normal","font-family":"\\"Raleway\\"","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"text-shadow":"none"},"hover":""}'),
            array('handle' => '.tp-caption.Gym-Display','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"80px","line-height":"70px","font-weight":"900","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.Gym-Subline','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"30px","line-height":"30px","font-weight":"100","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"5px"},"hover":""}'),
            array('handle' => '.tp-caption.Gym-SmallText','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"17px","line-height":"22","font-weight":"300","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"text-shadow":"none"},"hover":""}'),
            array('handle' => '.tp-caption.Fashion-SmallText','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"12px","line-height":"20px","font-weight":"600","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.Fashion-BigDisplay','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#000000","color-transparency":"1","font-size":"60px","line-height":"60px","font-weight":"900","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.Fashion-TextBlock','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#000000","color-transparency":"1","font-size":"20px","line-height":"40px","font-weight":"400","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.Sports-Display','settings' => '{"translated":5,"type":"text","version":"5.0"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"130px","line-height":"130px","font-weight":"100","font-style":"normal","font-family":"\\"Raleway\\"","padding":"0 0 0 0","text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":"0 0 0 0","z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"13px"},"hover":""}'),
            array('handle' => '.tp-caption.Sports-DisplayFat','settings' => '{"translated":5,"type":"text","version":"5.0"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"130px","line-height":"130px","font-weight":"900","font-style":"normal","font-family":"\\"Raleway\\"","padding":"0 0 0 0","text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":"0 0 0 0","z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":[""],"hover":""}'),
            array('handle' => '.tp-caption.Sports-Subline','settings' => '{"translated":5,"type":"text","version":"5.0"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#000000","color-transparency":"1","font-size":"32px","line-height":"32px","font-weight":"400","font-style":"normal","font-family":"\\"Raleway\\"","padding":"0 0 0 0","text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":"0 0 0 0","z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"4px"},"hover":""}'),
            array('handle' => '.tp-caption.Instagram-Caption','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"20px","line-height":"20px","font-weight":"900","font-style":"normal","font-family":"Roboto","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.News-Title','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"70px","line-height":"60px","font-weight":"400","font-style":"normal","font-family":"Roboto Slab","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.News-Subtitle','settings' => '{"hover":"true","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"0.65","text-decoration":"none","background-color":"#ffffff","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"solid","border-width":"0px","border-radius":["0","0","0px","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"300","easing":"Power3.easeInOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"15px","line-height":"24px","font-weight":"300","font-style":"normal","font-family":"Roboto Slab","padding":["0","0","0","0"],"text-decoration":"none","background-color":"#ffffff","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.Photography-Display','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"80px","line-height":"70px","font-weight":"100","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"5px"},"hover":""}'),
            array('handle' => '.tp-caption.Photography-Subline','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#777777","color-transparency":"1","font-size":"20px","line-height":"30px","font-weight":"300","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"3px"},"hover":""}'),
            array('handle' => '.tp-caption.Photography-ImageHover','settings' => '{"hover":"true","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"0.5","scalex":"0.8","scaley":"0.8","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"1000","easing":"Power3.easeInOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"20","line-height":"22","font-weight":"400","font-style":"normal","font-family":"","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"#ffffff","border-transparency":"0","border-style":"none","border-width":"0px","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.Photography-Menuitem','settings' => '{"hover":"true","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"#00ffde","background-transparency":"0.65","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"200","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"20px","line-height":"20px","font-weight":"300","font-style":"normal","font-family":"Raleway","padding":["3px","5px","3px","8px"],"text-decoration":"none","background-color":"#000000","background-transparency":"0.65","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.Photography-Textblock','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#fff","color-transparency":"1","font-size":"17px","line-height":"30px","font-weight":"300","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.Photography-Subline-2','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"0.35","font-size":"20px","line-height":"30px","font-weight":"300","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":{"letter-spacing":"3px"},"hover":""}'),
            array('handle' => '.tp-caption.Photography-ImageHover2','settings' => '{"hover":"true","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"0.5","scalex":"0.8","scaley":"0.8","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"500","easing":"Back.easeOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"20","line-height":"22","font-weight":"400","font-style":"normal","font-family":"Arial","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"#ffffff","border-transparency":"0","border-style":"none","border-width":"0px","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.WebProduct-Title','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#333333","color-transparency":"1","font-size":"90px","line-height":"90px","font-weight":"100","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.WebProduct-SubTitle','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#999999","color-transparency":"1","font-size":"15px","line-height":"20px","font-weight":"400","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.WebProduct-Content','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#999999","color-transparency":"1","font-size":"16px","line-height":"24px","font-weight":"600","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.WebProduct-Menuitem','settings' => '{"hover":"true","version":"5.0","translated":"5"}','hover' => '{"color":"#999999","color-transparency":"1","text-decoration":"none","background-color":"#ffffff","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"200","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"15px","line-height":"20px","font-weight":"500","font-style":"normal","font-family":"Raleway","padding":["3px","5px","3px","8px"],"text-decoration":"none","text-align":"left","background-color":"#333333","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.WebProduct-Title-Light','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#fff","color-transparency":"1","font-size":"90px","line-height":"90px","font-weight":"100","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","text-align":"left","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.WebProduct-SubTitle-Light','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"0.35","font-size":"15px","line-height":"20px","font-weight":"400","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","text-align":"left","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","parallax":"-"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.WebProduct-Content-Light','settings' => '{"hover":"false","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"0.65","font-size":"16px","line-height":"24px","font-weight":"600","font-style":"normal","font-family":"Raleway","padding":["0","0","0","0"],"text-decoration":"none","text-align":"left","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","parallax":"-"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.FatRounded','settings' => '{"hover":"true","type":"text","version":"5.0","translated":"5"}','hover' => '{"color":"#fff","color-transparency":"1","text-decoration":"none","background-color":"#000000","background-transparency":"1","border-color":"#d3d3d3","border-transparency":"1","border-style":"none","border-width":"0px","border-radius":["50px","50px","50px","50px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"300","easing":"Linear.easeNone"}','params' => '{"color":"#fff","color-transparency":"1","font-size":"30px","line-height":"30px","font-weight":"900","font-style":"normal","font-family":"Raleway","padding":["20px","22px","20px","25px"],"text-decoration":"none","text-align":"left","background-color":"#000000","background-transparency":"0.5","border-color":"#d3d3d3","border-transparency":"1","border-style":"none","border-width":"0px","border-radius":["50px","50px","50px","50px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"text-shadow":"none"},"hover":""}'),
            array('handle' => '.tp-caption.NotGeneric-Title','settings' => '{"translated":5,"type":"text","version":"5.0"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"70px","line-height":"70px","font-weight":"800","font-style":"normal","font-family":"Raleway","padding":"10px 0px 10px 0","text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":"0 0 0 0","z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":"[object Object]","hover":""}'),
            array('handle' => '.tp-caption.NotGeneric-SubTitle','settings' => '{"translated":5,"type":"text","version":"5.0"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"13px","line-height":"20px","font-weight":"500","font-style":"normal","font-family":"Raleway","padding":"0 0 0 0","text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":"0 0 0 0","z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"4px","text-align":"left"},"hover":""}'),
            array('handle' => '.tp-caption.NotGeneric-CallToAction','settings' => '{"hover":"true","translated":5,"type":"text","version":"5.0"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"#ffffff","border-transparency":"1","border-style":"solid","border-width":"1","border-radius":"0px 0px 0px 0px","opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"300","easing":"Power3.easeOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"14px","line-height":"14px","font-weight":"500","font-style":"normal","font-family":"Raleway","padding":"10px 30px 10px 30px","text-decoration":"none","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"0.5","border-style":"solid","border-width":"1","border-radius":"0px 0px 0px 0px","z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"3px","text-align":"left"},"hover":""}'),
            array('handle' => '.tp-caption.NotGeneric-Icon','settings' => '{"translated":5,"type":"text","version":"5.0"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"#ffffff","border-transparency":"1","border-style":"solid","border-width":"1","border-radius":["0px","0px","0px","0px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"default","speed":"300","easing":"Power3.easeOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"30px","line-height":"30px","font-weight":"400","font-style":"normal","font-family":"Raleway","padding":"0px 0px 0px 0px","text-decoration":"none","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"0","border-style":"solid","border-width":"0px","border-radius":"0px 0px 0px 0px","z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"3px","text-align":"left"},"hover":""}'),
            array('handle' => '.tp-caption.NotGeneric-Menuitem','settings' => '{"hover":"true","translated":5,"type":"text","version":"5.0"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"1","border-style":"solid","border-width":"1px","border-radius":"0px 0px 0px 0px","opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"300","easing":"Power1.easeInOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"14px","line-height":"14px","font-weight":"500","font-style":"normal","font-family":"Raleway","padding":"27px 30px 27px 30px","text-decoration":"none","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"0.15","border-style":"solid","border-width":"1px","border-radius":"0px 0px 0px 0px","z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"3px","text-align":"left"},"hover":""}'),
            array('handle' => '.tp-caption.MarkerStyle','settings' => '{"translated":5,"type":"text","version":"5.0"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"17px","line-height":"30px","font-weight":"100","font-style":"normal","font-family":"\\"Permanent Marker\\"","padding":"0 0 0 0","text-decoration":"none","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":"0 0 0 0","z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"text-align":"left","0":""},"hover":""}'),
            array('handle' => '.tp-caption.Gym-Menuitem','settings' => '{"hover":"true","type":"text","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"#000000","background-transparency":"1","border-color":"#ffffff","border-transparency":"0.25","border-style":"solid","border-width":"2px","border-radius":["3px","3px","3px","3px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"200","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"20px","line-height":"20px","font-weight":"300","font-style":"normal","font-family":"Raleway","padding":["3px","5px","3px","8px"],"text-decoration":"none","text-align":"left","background-color":"#000000","background-transparency":"1","border-color":"#ffffff","border-transparency":"0","border-style":"solid","border-width":"2px","border-radius":["3px","3px","3px","3px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.Newspaper-Button','settings' => '{"hover":"true","type":"button","version":"5.0","translated":"5"}','hover' => '{"color":"#000000","color-transparency":"1","text-decoration":"none","background-color":"#FFFFFF","background-transparency":"1","border-color":"#ffffff","border-transparency":"1","border-style":"solid","border-width":"1px","border-radius":["0px","0px","0px","0px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"300","easing":"Power1.easeInOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"13px","line-height":"17px","font-weight":"700","font-style":"normal","font-family":"Roboto","padding":["12px","35px","12px","35px"],"text-decoration":"none","text-align":"left","background-color":"#ffffff","background-transparency":"0","border-color":"#ffffff","border-transparency":"0.25","border-style":"solid","border-width":"1px","border-radius":["0px","0px","0px","0px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.Newspaper-Subtitle','settings' => '{"hover":"false","type":"text","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#a8d8ee","color-transparency":"1","font-size":"15px","line-height":"20px","font-weight":"900","font-style":"normal","font-family":"Roboto","padding":["0","0","0","0"],"text-decoration":"none","text-align":"left","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.Newspaper-Title','settings' => '{"hover":"false","type":"text","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#fff","color-transparency":"1","font-size":"50px","line-height":"55px","font-weight":"400","font-style":"normal","font-family":"\\"Roboto Slab\\"","padding":["0","0","10px","0"],"text-decoration":"none","text-align":"left","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.Newspaper-Title-Centered','settings' => '{"hover":"false","type":"text","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#fff","color-transparency":"1","font-size":"50px","line-height":"55px","font-weight":"400","font-style":"normal","font-family":"\\"Roboto Slab\\"","padding":["0","0","10px","0"],"text-decoration":"none","text-align":"center","background-color":"transparent","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.Hero-Button','settings' => '{"hover":"true","type":"button","version":"5.0","translated":"5"}','hover' => '{"color":"#000000","color-transparency":"1","text-decoration":"none","background-color":"#ffffff","background-transparency":"1","border-color":"#ffffff","border-transparency":"1","border-style":"solid","border-width":"1","border-radius":["0px","0px","0px","0px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"300","easing":"Power1.easeInOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"14px","line-height":"14px","font-weight":"500","font-style":"normal","font-family":"Raleway","padding":["10px","30px","10px","30px"],"text-decoration":"none","text-align":"left","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"0.5","border-style":"solid","border-width":"1","border-radius":["0px","0px","0px","0px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"3px"},"hover":""}'),
            array('handle' => '.tp-caption.Video-Title','settings' => '{"hover":"false","type":"text","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#fff","color-transparency":"1","font-size":"30px","line-height":"30px","font-weight":"900","font-style":"normal","font-family":"Raleway","padding":["5px","5px","5px","5px"],"text-decoration":"none","text-align":"left","background-color":"#000000","background-transparency":"1","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"-20%","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.Video-SubTitle','settings' => '{"hover":"false","type":"text","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"0","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"12px","line-height":"12px","font-weight":"600","font-style":"normal","font-family":"Raleway","padding":["5px","5px","5px","5px"],"text-decoration":"none","text-align":"left","background-color":"#000000","background-transparency":"0.35","border-color":"transparent","border-transparency":"1","border-style":"none","border-width":"0","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"-20%","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.NotGeneric-Button','settings' => '{"hover":"true","type":"button","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"transparent","background-transparency":"0","border-color":"#ffffff","border-transparency":"1","border-style":"solid","border-width":"1","border-radius":["0px","0px","0px","0px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"300","easing":"Power1.easeInOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"14px","line-height":"14px","font-weight":"500","font-style":"normal","font-family":"Raleway","padding":["10px","30px","10px","30px"],"text-decoration":"none","text-align":"left","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"0.5","border-style":"solid","border-width":"1","border-radius":["0px","0px","0px","0px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"3px","text-align":"left"},"hover":""}'),
            array('handle' => '.tp-caption.NotGeneric-BigButton','settings' => '{"hover":"true","type":"button","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"1","border-style":"solid","border-width":"1px","border-radius":["0px","0px","0px","0px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"300","easing":"Power1.easeInOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"14px","line-height":"14px","font-weight":"500","font-style":"normal","font-family":"Raleway","padding":["27px","30px","27px","30px"],"text-decoration":"none","text-align":"left","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"0.15","border-style":"solid","border-width":"1px","border-radius":["0px","0px","0px","0px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"3px"},"hover":""}'),
            array('handle' => '.tp-caption.WebProduct-Button','settings' => '{"hover":"true","type":"button","version":"5.0","translated":"5"}','hover' => '{"color":"#333333","color-transparency":"1","text-decoration":"none","background-color":"#ffffff","background-transparency":"1","border-color":"#000000","border-transparency":"1","border-style":"none","border-width":"2","border-radius":["0","0","0","0"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"300","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"16px","line-height":"48px","font-weight":"600","font-style":"normal","font-family":"Raleway","padding":["0px","40px","0px","40px"],"text-decoration":"none","text-align":"left","background-color":"#333333","background-transparency":"1","border-color":"#000000","border-transparency":"1","border-style":"none","border-width":"2","border-radius":["0","0","0","0"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"1px"},"hover":""}'),
            array('handle' => '.tp-caption.Restaurant-Button','settings' => '{"hover":"true","type":"button","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"#000000","background-transparency":"0","border-color":"#ffe081","border-transparency":"1","border-style":"solid","border-width":"2","border-radius":["0px","0px","0px","0px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"300","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"17px","line-height":"17px","font-weight":"500","font-style":"normal","font-family":"Roboto","padding":["12px","35px","12px","35px"],"text-decoration":"none","text-align":"left","background-color":"#0a0a0a","background-transparency":"0","border-color":"#ffffff","border-transparency":"0.5","border-style":"solid","border-width":"2","border-radius":["0px","0px","0px","0px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"3px"},"hover":""}'),
            array('handle' => '.tp-caption.Gym-Button','settings' => '{"hover":"true","type":"button","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"#72a800","background-transparency":"1","border-color":"#000000","border-transparency":"0","border-style":"solid","border-width":"0","border-radius":["30px","30px","30px","30px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"300","easing":"Power1.easeInOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"15px","line-height":"15px","font-weight":"600","font-style":"normal","font-family":"Raleway","padding":["13px","35px","13px","35px"],"text-decoration":"none","text-align":"left","background-color":"#8bc027","background-transparency":"1","border-color":"#000000","border-transparency":"0","border-style":"solid","border-width":"0","border-radius":["30px","30px","30px","30px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"1px"},"hover":""}'),
            array('handle' => '.tp-caption.Gym-Button-Light','settings' => '{"hover":"true","type":"button","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"#72a800","background-transparency":"0","border-color":"#8bc027","border-transparency":"1","border-style":"solid","border-width":"2px","border-radius":["30px","30px","30px","30px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"300","easing":"Power2.easeInOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"15px","line-height":"15px","font-weight":"600","font-style":"normal","font-family":"Raleway","padding":["12px","35px","12px","35px"],"text-decoration":"none","text-align":"left","background-color":"transparent","background-transparency":"0","border-color":"#ffffff","border-transparency":"0.25","border-style":"solid","border-width":"2px","border-radius":["30px","30px","30px","30px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":"","hover":""}'),
            array('handle' => '.tp-caption.Sports-Button-Light','settings' => '{"hover":"true","type":"button","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"1","border-style":"solid","border-width":"2","border-radius":["0px","0px","0px","0px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"500","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"17px","line-height":"17px","font-weight":"600","font-style":"normal","font-family":"Raleway","padding":["12px","35px","12px","35px"],"text-decoration":"none","text-align":"left","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"0.5","border-style":"solid","border-width":"2","border-radius":["0px","0px","0px","0px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.Sports-Button-Red','settings' => '{"hover":"true","type":"button","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"#000000","background-transparency":"1","border-color":"#000000","border-transparency":"1","border-style":"solid","border-width":"2","border-radius":["0px","0px","0px","0px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"500","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"17px","line-height":"17px","font-weight":"600","font-style":"normal","font-family":"Raleway","padding":["12px","35px","12px","35px"],"text-decoration":"none","text-align":"left","background-color":"#db1c22","background-transparency":"1","border-color":"#db1c22","border-transparency":"0","border-style":"solid","border-width":"2px","border-radius":["0px","0px","0px","0px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"2px"},"hover":""}'),
            array('handle' => '.tp-caption.Photography-Button','settings' => '{"hover":"true","type":"button","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"1","border-style":"solid","border-width":"1px","border-radius":["30px","30px","30px","30px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"auto","speed":"300","easing":"Power3.easeOut"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"15px","line-height":"15px","font-weight":"600","font-style":"normal","font-family":"Raleway","padding":["13px","35px","13px","35px"],"text-decoration":"none","text-align":"left","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"0.25","border-style":"solid","border-width":"1px","border-radius":["30px","30px","30px","30px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":{"letter-spacing":"1px"},"hover":""}'),
            array('handle' => '.tp-caption.Newspaper-Button-2','settings' => '{"hover":"true","type":"button","version":"5.0","translated":"5"}','hover' => '{"color":"#ffffff","color-transparency":"1","text-decoration":"none","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"1","border-style":"solid","border-width":"2","border-radius":["3px","3px","3px","3px"],"opacity":"1","scalex":"1","scaley":"1","skewx":"0","skewy":"0","xrotate":"0","yrotate":"0","2d_rotation":"0","css_cursor":"pointer","speed":"300","easing":"Linear.easeNone"}','params' => '{"color":"#ffffff","color-transparency":"1","font-size":"15px","line-height":"15px","font-weight":"900","font-style":"normal","font-family":"Roboto","padding":["10px","30px","10px","30px"],"text-decoration":"none","text-align":"left","background-color":"#000000","background-transparency":"0","border-color":"#ffffff","border-transparency":"0.5","border-style":"solid","border-width":"2","border-radius":["3px","3px","3px","3px"],"z":"0","skewx":"0","skewy":"0","scalex":"1","scaley":"1","opacity":"1","xrotate":"0","yrotate":"0","2d_rotation":"0","2d_origin_x":"50","2d_origin_y":"50","pers":"600","corner_left":"nothing","corner_right":"nothing","parallax":"-"}','advanced' => '{"idle":"","hover":""}')
        );
        
        $db = new RbSliderDB();

        foreach ($v5 as $v5class) {
            $result = $db->fetch(_DB_PREFIX_.GlobalsRbSlider::TABLE_CSS_NAME, "handle = '".$v5class['handle']."'");

            if (empty($result)) {
                $db->insert(_DB_PREFIX_.GlobalsRbSlider::TABLE_CSS_NAME, $v5class);
            }
        }
    }
    
    /**
     * update the styles to meet requirements for version 5.0
     * @since 5.0
     */
    public static function updateCssStyles()
    {
        $css = new RbSliderCssParser();
        $db = new RbSliderDB();
        $styles = $db->fetch(RbSliderGlobals::$table_css);
        $default_classes = RbSliderCssParser::defaultCssClasses();
        
        $cs = array(
            'background-color' => 'backgroundColor', //rgb rgba and opacity
            'border-color' => 'borderColor',
            'border-radius' => 'borderRadius',
            'border-style' => 'borderStyle',
            'border-width' => 'borderWidth',
            'color' => 'color',
            'font-family' => 'fontFamily',
            'font-size' => 'fontSize',
            'font-style' => 'fontStyle',
            'font-weight' => 'fontWeight',
            'line-height' => 'lineHeight',
            'opacity' => 'opacity',
            'padding' => 'padding',
            'text-decoration' => 'textDecoration',
            'x' => 'x',
            'y' => 'y',
            'z' => 'z',
            'skewx' => 'skewx',
            'skewy' => 'skewy',
            'scalex' => 'scalex',
            'scaley' => 'scaley',
            'opacity' => 'opacity',
            'xrotate' => 'xrotate',
            'yrotate' => 'yrotate',
            '2d_rotation' => '2d_rotation',
            'layer_2d_origin_x' => 'layer_2d_origin_x',
            'layer_2d_origin_y' => 'layer_2d_origin_y',
            '2d_origin_x' => '2d_origin_x',
            '2d_origin_y' => '2d_origin_y',
            'pers' => 'pers',
            
            'color-transparency' => 'color-transparency',
            'background-transparency' => 'background-transparency',
            'border-transparency' => 'border-transparency',
            'css_cursor' => 'css_cursor',
            'speed' => 'speed',
            'easing' => 'easing',
            'corner_left' => 'corner_left',
            'corner_right' => 'corner_right',
            'parallax' => 'parallax'
        );
        
        foreach ($styles as $key => $attr) {
            if (@Rbthemeslider::getIsset($attr['advanced'])) {
                $adv = Tools::jsonDecode($attr['advanced'], true);
            } else {
                $adv = array('idle' => array(), 'hover' => '');
            }
            
            if (!@Rbthemeslider::getIsset($adv['idle'])) {
                $adv['idle'] = array();
            }

            if (!@Rbthemeslider::getIsset($adv['hover'])) {
                $adv['hover'] = array();
            }
            
            //only do this to styles prior 5.0
            $settings = Tools::jsonDecode($attr['settings'], true);
            if (!empty($settings) && @Rbthemeslider::getIsset($settings['translated'])) {
                if (version_compare($settings['translated'], 5.0, '>=')) {
                    continue;
                }
            }
            
            $idle = Tools::jsonDecode($attr['params'], true);
            $hover = Tools::jsonDecode($attr['hover'], true);
            $the_type = 'text';
            
            if (!empty($idle)) {
                foreach ($idle as $style => $value) {
                    if ($style == 'type') {
                        $the_type = $value;
                    }

                    if (!@Rbthemeslider::getIsset($cs[$style])) {
                        $adv['idle'][$style] = $value;
                        unset($idle[$style]);
                    }
                }
            }
            
            if (!empty($hover)) {
                foreach ($hover as $style => $value) {
                    if (!@Rbthemeslider::getIsset($cs[$style])) {
                        $adv['hover'][$style] = $value;
                        unset($hover[$style]);
                    }
                }
            }
            
            $settings['translated'] = 5.0;
            $settings['type'] = $the_type;

            if (!@Rbthemeslider::getIsset($settings['version'])) {
                if (@Rbthemeslider::getIsset($default_classes[$styles[$key]['handle']])) {
                    $settings['version'] = $default_classes[$styles[$key]['handle']];
                } else {
                    $settings['version'] = 'custom';
                }
            }
            
            $styles[$key]['params'] = Tools::jsonEncode($idle);
            $styles[$key]['hover'] = Tools::jsonEncode($hover);
            $styles[$key]['advanced'] = Tools::jsonEncode($adv);
            $styles[$key]['settings'] = Tools::jsonEncode($settings);
        }
        
        //save now all styles back to database
        foreach ($styles as $key => $attr) {
            $ret = $db->update(
                RbSliderGlobals::$table_css,
                array(
                    'settings' => $styles[$key]['settings'],
                    'params' => $styles[$key]['params'],
                    'hover' => $styles[$key]['hover'],
                    'advanced' => $styles[$key]['advanced']
                ),
                array('id' => $attr['id'])
            );
        }
    }
    
    public static function moveTemplateSlider()
    {
        $db = new RbSliderDB();
        $used_templates = array();
        $sr = new RbSlider();
        $sl = new RbSliderSlide();
        $arrSliders = $sr->getArrSliders(false, false);
        $tempSliders = $sr->getArrSliders(false, true);
        
        if (empty($tempSliders) || !is_array($tempSliders)) {
            return true;
        }

        if (!empty($arrSliders) && is_array($arrSliders)) {
            foreach ($arrSliders as $slider) {
                if ($slider->getParam('source_type', 'gallery') !== 'posts') {
                    continue;
                }

                $slider_id = $slider->getID();
                $template_id = $slider->getParam('slider_template_id', 0);
                
                if ($template_id > 0) {
                    foreach ($tempSliders as $t_slider) {
                        if ($t_slider->getID() === $template_id) {
                            $slides = $t_slider->getSlides();
                            
                            if (!empty($slides) && is_array($slides)) {
                                foreach ($slides as $slide) {
                                    $slide_id = $slide->getID();
                                    $slider->copySlideToSlider(array('slider_id' => $slider_id, 'slide_id' => $slide_id));
                                }
                            }
                            
                            $static_id = $sl->getStaticSlideID($template_id);

                            if ($static_id !== false) {
                                $record = $db->fetchSingle(RbSliderGlobals::$table_static_slides, "id=$static_id");
                                unset($record['id']);
                                $record['slider_id'] = $slider_id;
                                $db->insert(RbSliderGlobals::$table_static_slides, $record);
                            }
                            
                            $used_templates[$template_id] = $t_slider;

                            break;
                        }
                    }
                }
            }
        }
        
        if (!empty($used_templates)) {
            foreach ($used_templates as $tid => $t_slider) {
                $t_slider->deleteSlider();
            }
        }
        
        $temp_sliders = $sr->getArrSliders(false, true);
        
        if (!empty($temp_sliders) && is_array($temp_sliders)) {
            foreach ($temp_sliders as $slider) {
                $slider->updateParam(array('template' => 'false'));
                $slider->updateParam(array('source_type' => 'posts'));
            }
        }
    }
    
    
    /**
     * add missing new animation fields to the layers as all animations would be broken without this
     * @since 5.0
     */
    public static function addAnimationSettingsToLayer($sliders = false)
    {
        $sr = new RbSlider();
        $sl = new RbSliderSlide();
        
        if ($sliders === false) {
            $sliders = $sr->getArrSliders(false);
        } else {
            $sliders = array($sliders);
        }
        
        $inAnimations = RbSliderOperations::getArrAnimations(true);
        $outAnimations = RbSliderOperations::getArrEndAnimations(true);

        if (!empty($sliders) && is_array($sliders)) {
            foreach ($sliders as $slider) {
                $slides = $slider->getSlides();
                $staticID = $sl->getStaticSlideID($slider->getID());

                if ($staticID !== false) {
                    $msl = new RbSliderSlide();

                    if (strpos($staticID, 'static_') === false) {
                        $staticID = 'static_'.$slider->getID();
                    }

                    $msl->initByID($staticID);

                    if ($msl->getID() !== '') {
                        $slides = array_merge($slides, array($msl));
                    }
                }
                
                if (!empty($slides) && is_array($slides)) {
                    foreach ($slides as $slide) {
                        $layers = $slide->getLayers();

                        if (!empty($layers) && is_array($layers)) {
                            foreach ($layers as $lk => $layer) {
                                if (RbSliderFunctions::getVal($layer, 'x_start', false) == false) {
                                    $animation = RbSliderFunctions::getVal($layer, 'animation', 'tp-fade');
                                    $endanimation = RbSliderFunctions::getVal($layer, 'endanimation', 'tp-fade');

                                    if ($animation == 'fade') {
                                        $animation = 'tp-fade';
                                    }
                                    if ($endanimation == 'fade') {
                                        $endanimation = 'tp-fade';
                                    }
                                    
                                    $anim_values = array();

                                    foreach ($inAnimations as $handle => $anim) {
                                        if ($handle == $animation) {
                                            $anim_values = (@Rbthemeslider::getIsset($anim['params'])) ? $anim['params'] : '';

                                            if (!is_array($anim_values)) {
                                                $anim_values = Tools::jsonEncode($anim_values);
                                            }

                                            break;
                                        }
                                    }
                                    
                                    $anim_endvalues = array();

                                    foreach ($outAnimations as $handle => $anim) {
                                        if ($handle == $endanimation) {
                                            $anim_endvalues = (@Rbthemeslider::getIsset($anim['params'])) ? $anim['params'] : '';
                                            if (!is_array($anim_endvalues)) {
                                                $anim_endvalues = Tools::jsonEncode($anim_endvalues);
                                            }
                                            break;
                                        }
                                    }
                                    
                                    $layers[$lk]['x_start'] = RbSliderFunctions::getVal($anim_values, 'movex', 'inherit');
                                    $layers[$lk]['x_end'] = RbSliderFunctions::getVal($anim_endvalues, 'movex', 'inherit');
                                    $layers[$lk]['y_start'] = RbSliderFunctions::getVal($anim_values, 'movey', 'inherit');
                                    $layers[$lk]['y_end'] = RbSliderFunctions::getVal($anim_endvalues, 'movey', 'inherit');
                                    $layers[$lk]['z_start'] = RbSliderFunctions::getVal($anim_values, 'movez', 'inherit');
                                    $layers[$lk]['z_end'] = RbSliderFunctions::getVal($anim_endvalues, 'movez', 'inherit');

                                    $layers[$lk]['x_rotate_start'] = RbSliderFunctions::getVal(
                                        $anim_values,
                                        'rotationx',
                                        'inherit'
                                    );

                                    $layers[$lk]['x_rotate_end'] = RbSliderFunctions::getVal(
                                        $anim_endvalues,
                                        'rotationx',
                                        'inherit'
                                    );

                                    $layers[$lk]['y_rotate_start'] = RbSliderFunctions::getVal(
                                        $anim_values,
                                        'rotationy',
                                        'inherit'
                                    );

                                    $layers[$lk]['y_rotate_end'] = RbSliderFunctions::getVal(
                                        $anim_endvalues,
                                        'rotationy',
                                        'inherit'
                                    );

                                    $layers[$lk]['z_rotate_start'] = RbSliderFunctions::getVal(
                                        $anim_values,
                                        'rotationz',
                                        'inherit'
                                    );

                                    $layers[$lk]['z_rotate_end'] = RbSliderFunctions::getVal(
                                        $anim_endvalues,
                                        'rotationz',
                                        'inherit'
                                    );

                                    $layers[$lk]['scale_x_start'] = RbSliderFunctions::getVal($anim_values, 'scalex', 'inherit');

                                    if ((int)($layers[$lk]['scale_x_start']) > 10) {
                                        $layers[$lk]['scale_x_start'] /= 100;
                                    }

                                    $layers[$lk]['scale_x_end'] = RbSliderFunctions::getVal($anim_endvalues, 'scalex', 'inherit');

                                    if ((int)($layers[$lk]['scale_x_end']) > 10) {
                                        $layers[$lk]['scale_x_end'] /= 100;
                                    }

                                    $layers[$lk]['scale_y_start'] = RbSliderFunctions::getVal($anim_values, 'scaley', 'inherit');

                                    if ((int)($layers[$lk]['scale_y_start']) > 10) {
                                        $layers[$lk]['scale_y_start'] /= 100;
                                    }

                                    $layers[$lk]['scale_y_end'] = RbSliderFunctions::getVal($anim_endvalues, 'scaley', 'inherit');

                                    if ((int)($layers[$lk]['scale_y_end']) > 10) {
                                        $layers[$lk]['scale_y_end'] /= 100;
                                    }
                                    
                                    $layers[$lk]['skew_x_start'] = RbSliderFunctions::getVal($anim_values, 'skewx', 'inherit');
                                    $layers[$lk]['skew_x_end'] = RbSliderFunctions::getVal($anim_endvalues, 'skewx', 'inherit');
                                    $layers[$lk]['skew_y_start'] = RbSliderFunctions::getVal($anim_values, 'skewy', 'inherit');
                                    $layers[$lk]['skew_y_end'] = RbSliderFunctions::getVal($anim_endvalues, 'skewy', 'inherit');

                                    $layers[$lk]['opacity_start'] = RbSliderFunctions::getVal(
                                        $anim_values,
                                        'captionopacity',
                                        'inherit'
                                    );

                                    $layers[$lk]['opacity_end'] = RbSliderFunctions::getVal(
                                        $anim_endvalues,
                                        'captionopacity',
                                        'inherit'
                                    );
                                }
                            }

                            $slide->setLayersRaw($layers);
                            $slide->saveLayers();
                        }
                    }
                }
            }
        }
    }
    
    /**
     * add/change layers options
     * @since 5.0
     */
    public static function changeSettingsOnLayers($sliders = false)
    {
        $sr = new RbSlider();
        $sl = new RbSliderSlide();

        if ($sliders === false) {
            $sliders = $sr->getArrSliders(false);
        } else {
            $sliders = array($sliders);
        }
        
        if (!empty($sliders) && is_array($sliders)) {
            foreach ($sliders as $slider) {
                $slides = $slider->getSlides();
                $staticID = $sl->getStaticSlideID($slider->getID());

                if ($staticID !== false) {
                    $msl = new RbSliderSlide();
                    if (strpos($staticID, 'static_') === false) {
                        $staticID = 'static_'.$slider->getID();
                    }

                    $msl->initByID($staticID);

                    if ($msl->getID() !== '') {
                        $slides = array_merge($slides, array($msl));
                    }
                }
                if (!empty($slides) && is_array($slides)) {
                    foreach ($slides as $slide) {
                        $layers = $slide->getLayers();
                        if (!empty($layers) && is_array($layers)) {
                            $do_save = false;
                            foreach ($layers as $lk => $layer) {
                                $link_slide = RbSliderFunctions::getVal($layer, 'link_slide', false);
                                if ($link_slide != false && $link_slide !== 'nothing') {
                                    $layers[$lk]['layer_action'] = new stdClass();
                                    switch ($link_slide) {
                                        case 'link':
                                            $link = RbSliderFunctions::getVal($layer, 'link');
                                            $link_open_in = RbSliderFunctions::getVal($layer, 'link_open_in');
                                            $layers[$lk]['layer_action']->action = array('a' => 'link');
                                            $layers[$lk]['layer_action']->link_type = array('a' => 'a');
                                            $layers[$lk]['layer_action']->image_link = array('a' => $link);
                                            $layers[$lk]['layer_action']->link_open_in = array('a' => $link_open_in);
                                            unset($layers[$lk]['link']);
                                            unset($layers[$lk]['link_open_in']);
//                                            Layer Link
                                        case 'next':
                                            $layers[$lk]['layer_action']->action = array('a' => 'next');
                                            break;
                                        case 'prev':
                                            $layers[$lk]['layer_action']->action = array('a' => 'prev');
                                            break;
                                        case 'scroll_under':
                                            $scrollunder_offset = RbSliderFunctions::getVal($layer, 'scrollunder_offset');
                                            $layers[$lk]['layer_action']->action = array('a' => 'scroll_under');
                                            $layers[$lk]['layer_action']->scrollunder_offset = array('a' => $scrollunder_offset);
                                            unset($layers[$lk]['scrollunder_offset']);
                                            break;
                                        default: //its an ID, so its a slide ID
                                            $layers[$lk]['layer_action']->action = array('a' => 'jumpto');
                                            $layers[$lk]['layer_action']->jump_to_slide = array('a' => $link_slide);
                                            break;
                                        
                                    }

                                    $layers[$lk]['layer_action']->tooltip_event = array('a' => 'click');
                                    unset($layers[$lk]['link_slide']);
                                    $do_save = true;
                                }
                            }
                            
                            if ($do_save) {
                                $slide->setLayersRaw($layers);
                                $slide->saveLayers();
                            }
                        }
                    }
                }
            }
        }
    }
    
    
    /**
     * add missing new style fields to the layers as all layers would be broken without this
     * @since 5.0
     */
    public static function addStyleSettingsToLayer($sliders = false)
    {
        $sr = new RbSlider();
        $sl = new RbSliderSlide();
        $operations = new RbSliderOperations();

        if ($sliders === false) {
            $sliders = $sr->getArrSliders(false);
        } else {
            $sliders = array($sliders);
        }
        
        $styles = $operations->getCaptionsContentArray();
        
        if (!empty($sliders) && is_array($sliders)) {
            foreach ($sliders as $slider) {
                $slides = $slider->getSlides();
                $staticID = $sl->getStaticSlideID($slider->getID());

                if ($staticID !== false) {
                    $msl = new RbSliderSlide();
                    if (strpos($staticID, 'static_') === false) {
                        $staticID = 'static_'.$slider->getID();
                    }

                    $msl->initByID($staticID);

                    if ($msl->getID() !== '') {
                        $slides = array_merge($slides, array($msl));
                    }
                }

                if (!empty($slides) && is_array($slides)) {
                    foreach ($slides as $slide) {
                        $layers = $slide->getLayers();
                        if (!empty($layers) && is_array($layers)) {
                            foreach ($layers as $lk => $layer) {
                                $static_styles = (array) RbSliderFunctions::getVal($layer, 'static_styles', array());
                                $def_val = (array) RbSliderFunctions::getVal($layer, 'deformation', array());
                                $defh_val = (array) RbSliderFunctions::getVal($layer, 'deformation-hover', array());
                                
                                if (empty($def_val)) {
                                    //add parallax always!
                                    $def_val['parallax'] = RbSliderFunctions::getVal($layer, 'parallax_level', '-');
                                    $layers[$lk]['deformation'] = $def_val;
                                    
                                    //check for selected style in styles, then add all deformations to the layer
                                    $cur_style = RbSliderFunctions::getVal($layer, 'style', '');
                                    
                                    if (trim($cur_style) == '') {
                                        continue;
                                    }

                                    $wws = false;
                                    
                                    foreach ($styles as $style) {
                                        if ($style['handle'] == '.tp-caption.'.$cur_style) {
                                            $wws = $style;
                                            break;
                                        }
                                    }
                                    
                                    if ($wws == false) {
                                        continue;
                                    }
                                    
                                    $css_idle = '';
                                    $css_hover = '';
                                    $wws['params'] = (array)$wws['params'];
                                    $wws['hover'] = (array)$wws['hover'];
                                    $wws['advanced'] = (array)$wws['advanced'];
                                    
                                    if (@Rbthemeslider::getIsset($wws['params']['font-family'])) {
                                        $def_val['font-family'] = $wws['params']['font-family'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['padding'])) {
                                        $raw_pad = $wws['params']['padding'];

                                        if (!is_array($raw_pad)) {
                                            $raw_pad = explode(' ', $raw_pad);
                                        }
                                        
                                        switch (count($raw_pad)) {
                                            case 1:
                                                $raw_pad = array($raw_pad[0], $raw_pad[0], $raw_pad[0], $raw_pad[0]);
                                                break;
                                            case 2:
                                                $raw_pad = array($raw_pad[0], $raw_pad[1], $raw_pad[0], $raw_pad[1]);
                                                break;
                                            case 3:
                                                $raw_pad = array($raw_pad[0], $raw_pad[1], $raw_pad[2], $raw_pad[1]);
                                                break;
                                        }
                                        
                                        $def_val['padding'] = $raw_pad;
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['font-style'])) {
                                        $def_val['font-style'] = $wws['params']['font-style'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['text-decoration'])) {
                                        $def_val['text-decoration'] = $wws['params']['text-decoration'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['background-color'])) {
                                        if (RbSliderFunctions::isrgb($wws['params']['background-color'])) {
                                            $def_val['background-color'] = RbSliderFunctions::rgba2hex($wws['params']['background-color']);
                                        } else {
                                            $def_val['background-color'] = $wws['params']['background-color'];
                                        }
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['background-transparency'])) {
                                        $def_val['background-transparency'] = $wws['params']['background-transparency'];
                                        if ($def_val['background-transparency'] > 1) {
                                            $def_val['background-transparency'] /= 100;
                                        }
                                    } else {
                                        if (@Rbthemeslider::getIsset($wws['params']['background-color'])) {
                                            $def_val['background-transparency'] = RbSliderFunctions::getTransFromRgba($wws['params']['background-color'], true);
                                        }
                                    }
                                    
                                    if (@Rbthemeslider::getIsset($wws['params']['border-color'])) {
                                        if (RbSliderFunctions::isrgb($wws['params']['border-color'])) {
                                            $def_val['border-color'] = RbSliderFunctions::rgba2hex($wws['params']['border-color']);
                                        } else {
                                            $def_val['border-color'] = $wws['params']['border-color'];
                                        }
                                    }
                                    
                                    if (@Rbthemeslider::getIsset($wws['params']['border-style'])) {
                                        $def_val['border-style'] = $wws['params']['border-style'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['border-width'])) {
                                        $def_val['border-width'] = $wws['params']['border-width'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['border-radius'])) {
                                        $raw_bor = $wws['params']['border-radius'];

                                        if (!is_array($raw_bor)) {
                                            $raw_bor = explode(' ', $raw_bor);
                                        }
                                        
                                        switch (count($raw_bor)) {
                                            case 1:
                                                $raw_bor = array($raw_bor[0], $raw_bor[0], $raw_bor[0], $raw_bor[0]);
                                                break;
                                            case 2:
                                                $raw_bor = array($raw_bor[0], $raw_bor[1], $raw_bor[0], $raw_bor[1]);
                                                break;
                                            case 3:
                                                $raw_bor = array($raw_bor[0], $raw_bor[1], $raw_bor[2], $raw_bor[1]);
                                                break;
                                        }
                                        
                                        $def_val['border-radius'] = $raw_bor;
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['x'])) {
                                        $def_val['x'] = $wws['params']['x'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['y'])) {
                                        $def_val['y'] = $wws['params']['y'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['z'])) {
                                        $def_val['z'] = $wws['params']['z'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['skewx'])) {
                                        $def_val['skewx'] = $wws['params']['skewx'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['skewy'])) {
                                        $def_val['skewy'] = $wws['params']['skewy'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['scalex'])) {
                                        $def_val['scalex'] = $wws['params']['scalex'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['scaley'])) {
                                        $def_val['scaley'] = $wws['params']['scaley'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['opacity'])) {
                                        $def_val['opacity'] = $wws['params']['opacity'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['xrotate'])) {
                                        $def_val['xrotate'] = $wws['params']['xrotate'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['yrotate'])) {
                                        $def_val['yrotate'] = $wws['params']['yrotate'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['2d_rotation'])) {
                                        $def_val['2d_rotation'] = $wws['params']['2d_rotation'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['2d_origin_x'])) {
                                        $def_val['2d_origin_x'] = $wws['params']['2d_origin_x'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['2d_origin_y'])) {
                                        $def_val['2d_origin_y'] = $wws['params']['2d_origin_y'];
                                    }

                                    if (@Rbthemeslider::getIsset($wws['params']['pers'])) {
                                        $def_val['pers'] = $wws['params']['pers'];
                                    }
                                    
                                    if (@Rbthemeslider::getIsset($wws['params']['color'])) {
                                        if (RbSliderFunctions::isrgb($wws['params']['color'])) {
                                            $static_styles['color'] = RbSliderFunctions::rgba2hex($wws['params']['color']);
                                        } else {
                                            $static_styles['color'] = $wws['params']['color'];
                                        }
                                    }
                                    
                                    if (@Rbthemeslider::getIsset($wws['params']['font-weight'])) {
                                        $static_styles['font-weight'] = $wws['params']['font-weight'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['params']['font-size'])) {
                                        $static_styles['font-size'] = $wws['params']['font-size'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['params']['line-height'])) {
                                        $static_styles['line-height'] = $wws['params']['line-height'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['params']['font-family'])) {
                                        $static_styles['font-family'] = $wws['params']['font-family'];
                                    }
                                    
                                    if (@Rbthemeslider::getIsset($wws['advanced']) &&
                                        @Rbthemeslider::getIsset($wws['advanced']['idle']) &&
                                        is_array($wws['advanced']['idle']) &&
                                        !empty($wws['advanced']['idle'])
                                    ) {
                                        $css_idle = '{'."\n";
                                        foreach ($wws['advanced']['idle'] as $handle => $value) {
                                            $value = implode(' ', $value);
                                            if ($value !== '') {
                                                $css_idle .= '	'.$handle.': '.$value.';'."\n";
                                            }
                                        }
                                        $css_idle .= '}'."\n";
                                    }
                                    
                                    if (@Rbthemeslider::getIsset($wws['hover']['color'])) {
                                        if (RbSliderFunctions::isrgb($wws['hover']['color'])) {
                                            $defh_val['color'] = RbSliderFunctions::rgba2hex($wws['hover']['color']);
                                        } else {
                                            $defh_val['color'] = $wws['hover']['color'];
                                        }
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['text-decoration'])) {
                                        $defh_val['text-decoration'] = $wws['hover']['text-decoration'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['background-color'])) {
                                        if (RbSliderFunctions::isrgb($wws['hover']['background-color'])) {
                                            $defh_val['background-color'] = RbSliderFunctions::rgba2hex($wws['hover']['background-color']);
                                        } else {
                                            $defh_val['background-color'] = $wws['hover']['background-color'];
                                        }
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['background-transparency'])) {
                                        $defh_val['background-transparency'] = $wws['hover']['background-transparency'];
                                        if ($defh_val['background-transparency'] > 1) {
                                            $defh_val['background-transparency'] /= 100;
                                        }
                                    } else {
                                        if (@Rbthemeslider::getIsset($wws['hover']['background-color'])) {
                                            $defh_val['background-transparency'] = RbSliderFunctions::getTransFromRgba($wws['hover']['background-color'], true);
                                        }
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['border-color'])) {
                                        if (RbSliderFunctions::isrgb($wws['hover']['border-color'])) {
                                            $defh_val['border-color'] = RbSliderFunctions::rgba2hex($wws['hover']['border-color']);
                                        } else {
                                            $defh_val['border-color'] = $wws['hover']['border-color'];
                                        }
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['border-style'])) {
                                        $defh_val['border-style'] = $wws['hover']['border-style'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['border-width'])) {
                                        $defh_val['border-width'] = $wws['hover']['border-width'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['border-radius'])) {
                                        $raw_bor = $wws['hover']['border-radius'];
                                        if (!is_array($raw_bor)) {
                                            $raw_bor = explode(' ', $raw_bor);
                                        }
                                        
                                        switch (count($raw_bor)) {
                                            case 1:
                                                $raw_bor = array($raw_bor[0], $raw_bor[0], $raw_bor[0], $raw_bor[0]);
                                                break;
                                            case 2:
                                                $raw_bor = array($raw_bor[0], $raw_bor[1], $raw_bor[0], $raw_bor[1]);
                                                break;
                                            case 3:
                                                $raw_bor = array($raw_bor[0], $raw_bor[1], $raw_bor[2], $raw_bor[1]);
                                                break;
                                        }
                                        
                                        $defh_val['border-radius'] = $raw_bor;
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['x'])) {
                                        $defh_val['x'] = $wws['hover']['x'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['y'])) {
                                        $defh_val['y'] = $wws['hover']['y'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['z'])) {
                                        $defh_val['z'] = $wws['hover']['z'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['skewx'])) {
                                        $defh_val['skewx'] = $wws['hover']['skewx'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['skewy'])) {
                                        $defh_val['skewy'] = $wws['hover']['skewy'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['scalex'])) {
                                        $defh_val['scalex'] = $wws['hover']['scalex'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['scaley'])) {
                                        $defh_val['scaley'] = $wws['hover']['scaley'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['opacity'])) {
                                        $defh_val['opacity'] = $wws['hover']['opacity'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['xrotate'])) {
                                        $defh_val['xrotate'] = $wws['hover']['xrotate'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['yrotate'])) {
                                        $defh_val['yrotate'] = $wws['hover']['yrotate'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['2d_rotation'])) {
                                        $defh_val['2d_rotation'] = $wws['hover']['2d_rotation'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['2d_origin_x'])) {
                                        $defh_val['2d_origin_x'] = $wws['hover']['2d_origin_x'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['2d_origin_y'])) {
                                        $defh_val['2d_origin_y'] = $wws['hover']['2d_origin_y'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['speed'])) {
                                        $defh_val['speed'] = $wws['hover']['speed'];
                                    }
                                    if (@Rbthemeslider::getIsset($wws['hover']['easing'])) {
                                        $defh_val['easing'] = $wws['hover']['easing'];
                                    }
                                    
                                    if (@Rbthemeslider::getIsset($wws['advanced']) &&
                                        @Rbthemeslider::getIsset($wws['advanced']['hover']) &&
                                        is_array($wws['advanced']['hover']) &&
                                        !empty($wws['advanced']['hover'])
                                    ) {
                                        $css_hover = '{'."\n";

                                        foreach ($wws['advanced']['hover'] as $handle => $value) {
                                            $value = implode(' ', $value);
                                            if ($value !== '') {
                                                $css_hover .= '	'.$handle.': '.$value.';'."\n";
                                            }
                                        }

                                        $css_hover .= '}'."\n";
                                    }
                                    
                                    if (!@Rbthemeslider::getIsset($layers[$lk]['inline'])) {
                                        $layers[$lk]['inline'] = array();
                                    }
                                    if ($css_idle !== '') {
                                        $layers[$lk]['inline']['idle'] = $css_idle;
                                    }
                                    if ($css_hover !== '') {
                                        $layers[$lk]['inline']['idle'] = $css_hover;
                                    }
                                    
                                    $layers[$lk]['deformation'] = $def_val;
                                    $layers[$lk]['deformation-hover'] = $defh_val;
                                    $layers[$lk]['static_styles'] = $static_styles;
                                }
                            }
                            
                            $slide->setLayersRaw($layers);
                            $slide->saveLayers();
                        }
                    }
                }
            }
        }
    }
    
    
    /**
     * add settings to layer depending on how 
     * @since 5.0
     */
    public static function addGeneralSettings($sliders = false)
    {
        $sr = new RbSlider();
        $sl = new RbSliderSlide();

        if ($sliders === false) {
            $sliders = $sr->getArrSliders(false);
        } else {
            $sliders = array($sliders);
        }
        
        if (!empty($sliders) && is_array($sliders)) {
            $fonts = getOption('tp-google-fonts', array());
            
            foreach ($sliders as $slider) {
                $settings = $slider->getSettings();
                $bg_freeze = $slider->getParam('parallax_bg_freeze', 'off');
                $google_fonts = $slider->getParam('google_font', array());
                
                if (!@Rbthemeslider::getIsset($settings['version']) ||
                    version_compare($settings['version'], 5.0, '<')
                ) {
                    if (empty($google_fonts) && !empty($fonts)) {
                        foreach ($fonts as $font) {
                            $google_fonts[] = $font['url'];
                        }
                        $slider->updateParam(array('google_font' => $google_fonts));
                    }

                    $settings['version'] = 5.0;
                    $slider->updateSetting(array('version' => 5.0));
                }
                
                if ($bg_freeze == 'on') {
                    $slider->updateParam(array('parallax_bg_freeze' => 'off'));
                }
                
                $slides = $slider->getSlides();
                $staticID = $sl->getStaticSlideID($slider->getID());

                if ($staticID !== false) {
                    $msl = new RbSliderSlide();
                    if (strpos($staticID, 'static_') === false) {
                        $staticID = 'static_'.$slider->getID();
                    }
                    $msl->initByID($staticID);
                    if ($msl->getID() !== '') {
                        $slides = array_merge($slides, array($msl));
                    }
                }
                if (!empty($slides) && is_array($slides)) {
                    foreach ($slides as $slide) {
                        if ($bg_freeze == 'on') {
                            $slide->setParam('slide_parallax_level', '1');
                        }

                        $slide->saveParams();
                    }
                }
            }
        }
    }
    
    /**
     * remove static slide from Sliders if the setting was set to off
     * @since 5.0
     */
    public static function removeStaticSlides($sliders = false)
    {
        $sr = new RbSlider();
        $sl = new RbSliderSlide();

        if ($sliders === false) {
            $sliders = $sr->getArrSliders(false);
        } else {
            $sliders = array($sliders);
        }
        
        if (!empty($sliders) && is_array($sliders)) {
            foreach ($sliders as $slider) {
                $settings = $slider->getSettings();
                $enable_static_layers = $slider->getParam('enable_static_layers', 'off');
                
                if ($enable_static_layers == 'off') {
                    $staticID = $sl->getStaticSlideID($slider->getID());
                    if ($staticID !== false) {
                        $slider->deleteStaticSlide();
                    }
                }
            }
        }
    }
    
    /**
     * change general settings of all sliders to 5.0.7
     * @since 5.0.7
     */
    public static function changeGeneralSettings507($sliders = false)
    {
        $sr = new RbSlider();
        $sl = new RbSliderSlide();

        if ($sliders === false) {
            $sliders = $sr->getArrSliders(false);
        } else {
            $sliders = array($sliders);
        }
        
        if (!empty($sliders) && is_array($sliders)) {
            foreach ($sliders as $slider) {
                $settings = $slider->getSettings();
                
                if (!@Rbthemeslider::getIsset($settings['version']) || version_compare($settings['version'], '5.0.7', '<')) {
                    $start_with_slide = $slider->getParam('start_with_slide', '1');
                    
                    if ($start_with_slide !== '1') {
                        $slider->updateParam(array('start_with_slide_enable' => 'on'));
                    }
                    
                    $settings['version'] = '5.0.7';
                    $slider->updateSetting(array('version' => '5.0.7'));
                }
            }
        }
    }
    
    /**
     * change image id of all slides to 5.1.1
     * @since 5.1.1
     */
    public static function changeGeneralSettings511($sliders = false)
    {
        $sr = new RbSlider();
        $sl = new RbSliderSlide();

        if ($sliders === false) {
            $sliders = $sr->getArrSliders(false);
        } else {
            $sliders = array($sliders);
        }
        
        if (!empty($sliders) && is_array($sliders)) {
            foreach ($sliders as $slider) {
                $slides = $slider->getSlides();
                $staticID = $sl->getStaticSlideID($slider->getID());
                if ($staticID !== false) {
                    $msl = new RbSliderSlide();
                    if (strpos($staticID, 'static_') === false) {
                        $staticID = 'static_'.$slider->getID();
                    }
                    $msl->initByID($staticID);
                    if ($msl->getID() !== '') {
                        $slides = array_merge($slides, array($msl));
                    }
                }
                
                if (!empty($slides) && is_array($slides)) {
                    foreach ($slides as $slide) {
                        $image_id = $slide->getParam('image_id', '');
                        $image = $slide->getParam('image', '');
                        $ml_id = '';

                        if ($image !== '') {
                            $ml_id = getImageIdByUrl($image);
                        }
                        if ($image == '' && $image_id == '') {
                            continue;
                        }

                        if ($ml_id !== false && $ml_id !== $image_id) {
                            $urlImage = psGetAttachmentImageSrc($ml_id, 'full');

                            $slide->setParam('image_id', $ml_id);
                            $slide->saveParams();
                        }
                    }
                }
            }
        }
    }
}

class UnitePluginUpdateRb extends RbSliderPluginUpdate
{
    
}
