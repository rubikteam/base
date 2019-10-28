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
	<# var defaultValue = '', dataAlpha = '';
	if ( data.default ) {
	if ( '#' !== data.default.substring( 0, 1 ) ) {
	defaultValue = '#' + data.default;
	} else {
	defaultValue = data.default;
	}
	defaultValue = ' data-default-color=' + defaultValue; // Quotes added automatically.
	}
	if ( data.alpha ) {
	dataAlpha = ' data-alpha=true';
	} #>

	<div class="rb-control-field">
			<label class="rb-control-title">
				<# if ( data.label ) { #>
					{{{ data.label }}}
					<# } #>
						<# if ( data.description ) { #>
							<span class="rb-control-description">{{{ data.description }}}</span>
						<# } #>
			</label>

			<div class="rb-control-input-wrapper">
				<input data-setting="{{ name }}" class="color-picker-hex" type="text" maxlength="7" placeholder="{l s='Hex Value' mod='rbthemedream'}" {{ defaultValue }}{{ dataAlpha }} />
			</div>
		</div>
</div>