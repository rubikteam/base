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

class RbMenuCache
{
	private $expire = 1;

    public function __construct()
    {
        $this->expire = (int)Configuration::get('RBTHEMEMENU_CACHE_LIFE_TIME') >= 1 ? (int)Configuration::get('RBTHEMEMENU_CACHE_LIFE_TIME') : 1;
    }

	public function get($key)
	{
		$files = glob(dirname(__FILE__).'/../cache/' . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			$cache = Tools::file_get_contents($files[0]);

            foreach ($files as $file) {
				$time = (int)Tools::substr(strrchr($file, '.'), 1);

      			if ($time*3600 < time()) {
					if (file_exists($file)) {
						@unlink($file);
					}
      			}
    		}

			return $cache;			
		}

        return false;
	}

  	public function set($key, $value)
  	{
    	$this->delete($key);		
		$file = dirname(__FILE__).'/../cache/'  . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + (int)$this->expire*3600);    	
		$handle = fopen($file, 'w');
    	fwrite($handle, $value ? $value : '');		
    	fclose($handle);
  	}

  	public function delete($key = false)
  	{
		$files = glob(dirname(__FILE__).'/../cache/'  . ($key ? 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) : '') . '.*');

		if ($files) {
    		foreach ($files as $file) {
      			if (file_exists($file) && strpos($file,'index.php')===false) {
					unlink($file);
				}
    		}
		}
  	}
}
