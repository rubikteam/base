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

require_once _PS_MODULE_DIR_.'rbthemeslider/rbthemeslider.php';

if (!class_exists('ThemePunchFonts')) {
    class ThemePunchFonts
    {
    	public function __construct()
    	{
    		$this->modules = new Rbthemeslider();
    	}

        public function addNewFont($new_font)
        {
            if (!@Rbthemeslider::getIsset($new_font['url']) || Tools::strlen($new_font['url']) < 3) {
                return $this->modules->l('Wrong parameter received');
            }

            if (!@Rbthemeslider::getIsset($new_font['handle']) ||
            	Tools::strlen($new_font['handle']) < 3
            ) {
                return $this->modules->l('Wrong handle received');
            }

            $fonts = unserialize(sdsconfig::getval('tp-google-fonts'));

            if (!empty($fonts)) {
                foreach ($fonts as $font) {
                    if ($font['handle'] == $new_font['handle']) {
                        return $this->modules->l(
                        	'Font with handle already exist, choose a different handle'
                        );
                    }
                }
            }

            $new = array('url' => $new_font['url'], 'handle' => $new_font['handle']);

            $fonts[] = $new;

            $do = sdsconfig::setval('tp-google-fonts', $fonts);

            if ($do) {
                return true;
            }
        }

        public function editFontByHandle($edit_font)
        {
            if (!@Rbthemeslider::getIsset($edit_font['handle']) ||
            	Tools::strlen($edit_font['handle']) < 3
            ) {
                return $this->modules->l('Wrong Handle received');
            }

            if (!@Rbthemeslider::getIsset($edit_font['url']) ||
            	Tools::strlen($edit_font['url']) < 3
            ) {
                return $this->modules->l('Wrong Params received');
            }

            $fonts = $this->getAllFonts();

            if (!empty($fonts)) {
                foreach ($fonts as $key => $font) {
                    if ($font['handle'] == $edit_font['handle']) {
                        $fonts[$key]['handle'] = $edit_font['handle'];
                        $fonts[$key]['url'] = $edit_font['url'];
                        $do = sdsconfig::setval('tp-google-fonts', $fonts);

                        return true;
                    }
                }
            }

            return false;
        }

        public function removeFontByHandle($handle)
        {
            $fonts = $this->getAllFonts();

            if (!empty($fonts)) {
                foreach ($fonts as $key => $font) {
                    if ($font['handle'] == $handle) {
                        unset($fonts[$key]);
                        $do = sdsconfig::setval('tp-google-fonts', $fonts);

                        return true;
                    }
                }
            }

            return $this->modules->l('Font not found! Wrong handle given.');
        }

        public function getAllFonts()
        {
            $fonts = unserialize(sdsconfig::getval('tp-google-fonts'));

            return $fonts;
        }

        public function getAllFontsHandle()
        {
            $fonts = array();
            $font = unserialize(sdsconfig::getval('tp-google-fonts'));

            if (!empty($font)) {
                foreach ($font as $f) {
                    $fonts[] = $f['handle'];
                }
            }

            return $fonts;
        }

        public function registerFonts()
        {
            $fonts = $this->getAllFonts();

            if (!empty($fonts)) {
                $http = (is_ssl()) ? 'https' : 'http';

                foreach ($fonts as $font) {
                    if ($font !== '') {
                        $font_url = $http . '://fonts.googleapis.com/css?family=' . $font['url'];
                        Context::getcontext()->controller->addCSS($font_url);
                    }
                }
            }
        }

        public static function propagateDefaultFonts()
        {
            $default = array(
                array('url' => 'Open+Sans:300,400,600,700,800', 'handle' => 'open-sans'),
                array('url' => 'Raleway:100,200,300,400,500,600,700,800,900', 'handle' => 'raleway'),
                array('url' => 'Droid+Serif:400,700', 'handle' => 'droid-serif')
            );

            $fonts = unserialize(sdsconfig::getval('tp-google-fonts'));
            
            if (!empty($fonts)) {
                foreach ($default as $d_key => $d_font) {
                    $found = false;
                    foreach ($fonts as $font) {
                        if ($font['handle'] == $d_font['handle']) {
                            $found = true;
                            break;
                        }
                    }
                    if ($found == false) {
                        $fonts[] = $default[$d_key];
                    }
                }
                sdsconfig::setval('tp-google-fonts', $fonts);
            } else {
                sdsconfig::setval('tp-google-fonts', $default);
            }
        }
    }
}
