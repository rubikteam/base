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

if (!class_exists('SdsRbHooksClass')) {
    class SdsRbHooksClass
    {
    	public function __construct()
    	{
    		$this->modules = new Rbthemeslider();
    	}

        public function addNewHook($new_font)
        {
            if (!@Rbthemeslider::getIsset($new_font['hookname'])) {
                return $this->modules->l('Wrong parameter received');
            }

            $fonts = unserialize(sdsconfig::getval('sds_rb_hooks'));

            if (!empty($fonts)) {
                foreach ($fonts as $font) {
                    if ($font['hookname'] == $new_font['hookname']) {
                        return $this->modules->l(
                        	'Hook Already exist, choose a different Hook'
                        );
                    }
                }
            }

            $new = array('hookname' => $new_font['hookname']);

            $fonts[] = $new;
            $do = sdsconfig::setval('sds_rb_hooks', $fonts);

            //start register hook
            $this->modules->registerHook($new_font['hookname']);

            //End register hook
            if ($do) {
                return true;
            }
        }

        public function editHookByHookname($edit_font)
        {
            if (!@Rbthemeslider::getIsset($edit_font['hookname'])) {
                return $this->modules->l(
                	'Wrong Hook received'
                );
            }

            $fonts = $this->getAllHooks();

            if (!empty($fonts)) {
                foreach ($fonts as $key => $font) {
                    if ($font['hookname'] == $edit_font['hookname']) {
                        $fonts[$key]['hookname'] = $edit_font['hookname'];
                        $do = sdsconfig::setval('sds_rb_hooks', $fonts);
                        return true;
                    }
                }
            }

            return false;
        }

        public function removeHookByHookname($handle)
        {
            $fonts = $this->getAllHooks();

            if (!empty($fonts)) {
                foreach ($fonts as $key => $font) {
                    if ($font['hookname'] == $handle) {
                        unset($fonts[$key]);

                        //start unregister hook
                        $id_hook = Hook::getIdByName($handle);
                        $this->modules->unregisterHook($id_hook);

                        //End unregister hook
                        $do = sdsconfig::setval('sds_rb_hooks', $fonts);

                        return true;
                    }
                }
            }

            return $this->modules->l(
            	'Hook not found! Wrong Hook given.'
            );
        }

        public function getAllHooks()
        {
            $fonts = unserialize(sdsconfig::getval('sds_rb_hooks'));
            
            return $fonts;
        }

        public function getAllHooksHandle()
        {
            $fonts = array();
            $font = unserialize(sdsconfig::getval('sds_rb_hooks'));

            if (!empty($font)) {
                foreach ($font as $f) {
                    $fonts[] = $f['hookname'];
                }
            }

            return $fonts;
        }

        public static function propagateDefaultHooks()
        {
            $default = array();
            $fonts = unserialize(sdsconfig::getval('sds_rb_hooks'));

            if (!empty($fonts)) {
                foreach ($default as $d_key => $d_font) {
                    $found = false;

                    foreach ($fonts as $font) {
                        if ($font['hookname'] == $d_font['hookname']) {
                            $found = true;
                            break;
                        }
                    }

                    if ($found == false) {
                        $fonts[] = $default[$d_key];
                    }
                }

                sdsconfig::setval('sds_rb_hooks', $fonts);
            } else {
                sdsconfig::setval('sds_rb_hooks', $default);
            }
        }
    }
}
