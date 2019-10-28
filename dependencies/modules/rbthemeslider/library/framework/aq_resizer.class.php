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

if (!class_exists('RbAqResize')) {
    class RbAqResize
    {
        private static $instance = null;
        
        private function __construct()
        {

        }

        private function __clone()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == null) {
                self::$instance = new self;
            }

            return self::$instance;
        }
        public function process(
            $url,
            $width = null,
            $height = null,
            $crop = null,
            $single = true,
            $upscale = false
        ) {
            if (! $url || (! $width && ! $height)) {
                return false;
            }
        }

    
        public function aqUpscale($default, $orig_w, $orig_h, $dest_w, $dest_h, $crop)
        {
            if (! $crop) {
                return null;
            }

            $aspect_ratio = $orig_w / $orig_h;
            $new_w = $dest_w;
            $new_h = $dest_h;

            if (! $new_w) {
                $new_w = (int)($new_h * $aspect_ratio);
            }

            if (! $new_h) {
                $new_h = (int)($new_w / $aspect_ratio);
            }

            $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

            $crop_w = round($new_w / $size_ratio);
            $crop_h = round($new_h / $size_ratio);

            $s_x = floor(($orig_w - $crop_w) / 2);
            $s_y = floor(($orig_h - $crop_h) / 2);

            return array(
                0,
                0,
                (int) $s_x,
                (int) $s_y,
                (int) $new_w,
                (int) $new_h,
                (int)
                $crop_w,
                (int) $crop_h
            );
        }
    }
}


if (!function_exists('rbAqResize')) {
    function rbAqResize(
        $url,
        $width = null,
        $height = null,
        $crop = null,
        $single = true,
        $upscale = false
    ) {
        $aq_resize = RbAqResize::getInstance();

        return $aq_resize->process($url, $width, $height, $crop, $single, $upscale);
    }
}
