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
{if !empty($rbthemedreams)}
	<div class="row">
		<p class="help-block" style="display: inline-block;">

		{foreach from=$rbthemedreams item=rbthemedream}
			<h3 class="block-title">
    			<p class="help-block" style="display: inline-block;">
    			{$rbthemedream.name}
    		</h3>

    		<div class="form-group">						
				<label class="control-label col-lg-3">{l s='Fullwidth Homepage' mod='rbthemedream'}</label>

				<div class="col-lg-9">
					<span class="switch prestashop-switch fixed-width-lg">
						<input
							type="radio"
							name="RBTHEMEDREAM_HOME_{$rbthemedream.id_rbthemedream_home}"
							id="RBTHEMEDREAM_HOME_{$rbthemedream.id_rbthemedream_home}_on"
							{if $rbthemedream.home == 1}checked="checked"{/if}
							value="1"
						>

						<label for="RBTHEMEDREAM_HOME_{$rbthemedream.id_rbthemedream_home}_on">{l s='Yes' mod='rbthemedream'}</label>

						<input
							type="radio"
							name="RBTHEMEDREAM_HOME_{$rbthemedream.id_rbthemedream_home}"
							id="RBTHEMEDREAM_HOME_{$rbthemedream.id_rbthemedream_home}_off"
							value="0"
							{if $rbthemedream.home != 1}checked="checked"{/if}
						>

						<label for="RBTHEMEDREAM_HOME_{$rbthemedream.id_rbthemedream_home}_off">{l s='No' mod='rbthemedream'}</label>

						<a class="slide-button btn"></a>
					</span>																												
				</div>			
			</div>

			<div class="form-group">						
				<label class="control-label col-lg-3">{l s='Fullwidth Other Page' mod='rbthemedream'}</label>

				<div class="col-lg-9">
					<span class="switch prestashop-switch fixed-width-lg">
						<input
							type="radio"
							name="RBTHEMEDREAM_PAGE_{$rbthemedream.id_rbthemedream_home}"
							id="RBTHEMEDREAM_PAGE_{$rbthemedream.id_rbthemedream_home}_on"
							{if $rbthemedream.page == 1}checked="checked"{/if}
							value="1"
						>

						<label for="RBTHEMEDREAM_PAGE_{$rbthemedream.id_rbthemedream_home}_on">{l s='Yes' mod='rbthemedream'}</label>

						<input
							type="radio"
							name="RBTHEMEDREAM_PAGE_{$rbthemedream.id_rbthemedream_home}"
							id="RBTHEMEDREAM_PAGE_{$rbthemedream.id_rbthemedream_home}_off"
							value="0"
							{if $rbthemedream.page != 1}checked="checked"{/if}
						>

						<label for="RBTHEMEDREAM_PAGE_{$rbthemedream.id_rbthemedream_home}_off">{l s='No' mod='rbthemedream'}</label>

						<a class="slide-button btn"></a>
					</span>																												
				</div>			
			</div>
		{/foreach}
	</div>
{/if}