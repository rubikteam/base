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
<div class="rb-control-content">
	<#
		var defaultColorValue = '';
		if ( data.default.color ) {
			if ( '#' !== data.default.color.substring( 0, 1 ) ) {
				defaultColorValue = '#' + data.default.color;
			} else {
				defaultColorValue = data.default.color;
			}
			defaultColorValue = ' data-default-color=' + defaultColorValue; // Quotes added automatically.
		}
	#>

	<div class="rb-control-field">
		<label class="rb-control-title">{l s='Color' mod='rbthemedream'}</label>
			<div class="rb-control-input-wrapper">
			<input data-setting="color"
				class="rb-box-shadow-color-picker"
				type="text" maxlength="7"
				placeholder="{l s='Hex Value' mod='rbthemedream'}"
				data-alpha="true"{{{ defaultColorValue }}}
			/>
		</div>
	</div>

	{foreach from=$sliders item=slider}
		<div class="rb-box-shadow-slider">
			<label class="rb-control-title">{$slider.label}</label>
			<div class="rb-control-input-wrapper">
				<div class="rb-slider" data-input="{$slider.type}"></div>
				<div class="rb-slider-input">
					<input type="number"
						min="{$slider.min}"
						max="{$slider.max}"
						step="{{ data.step }}"
						data-setting="{$slider.type}"
					/>
				</div>
			</div>
		</div>
	{/foreach}
</div>