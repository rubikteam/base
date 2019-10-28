{*
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
*}
<div class="row">
    <div class="col-md-12">
	    <label class="rb-check-module" for="rb_rbthemedream">
	    	<input id="rb_rbthemedream" type="checkbox" name="rb_rbthemedream" value="1">
	    	<span>{l s='rbthemedream' mod='rbthemedream'}</span>
		</label>

		<h3 class="block-title">
    		<p class="help-block" style="display: inline-block;">
    			{l s='Back Up: ' mod='rbthemedream'}
    			"{$rb_dir}rbthemedream/sql/same.php"
    		</p>
    	</h3>
	</div>

	<div class="col-md-12">
		<label class="rb-check-module" for="rb_rbthememenu">
	    	<input id="rb_rbthememenu" type="checkbox" name="rb_rbthememenu" value="1">
	    	<span>{l s='rbthememenu' mod='rbthemedream'}</span>
		</label>

		<h3 class="block-title">
    		<p class="help-block" style="display: inline-block;">
    			{l s='Back Up: ' mod='rbthemedream'}
    			"{$rb_dir}rbthememenu/sql/same.php"
    		</p>
    	</h3>
	</div>

	<div class="col-md-12">
		<label class="rb-check-module" for="rb_rbthemeblog">
	    	<input id="rb_rbthemeblog" type="checkbox" name="rb_rbthemeblog" value="1">
	    	<span>{l s='rbthemeblog' mod='rbthemedream'}</span>
		</label>

		<h3 class="block-title">
    		<p class="help-block" style="display: inline-block;">
    			{l s='Back Up: ' mod='rbthemedream'}
    			"{$rb_dir}rbthemeblog/sql/same.php"
    		</p>
    	</h3>
	</div>

	<div class="col-md-12">
		<label class="rb-check-module" for="rb_slider">
	    	<input id="rb_slider" type="checkbox" name="rb_slider" value="1">
	    	<span>{l s='rbthemeslider' mod='rbthemedream'}</span>
		</label>

		<h3 class="block-title">
    		<p class="help-block" style="display: inline-block;">
    			{l s='Back Up: ' mod='rbthemedream'}
    			"{$rb_dir}rbthemeslidersql/same.php"
    		</p>
    	</h3>
	</div>
</div>