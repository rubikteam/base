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
	<div class="rb-control-field">
		<label class="rb-control-title">{{{ data.label }}}</label>
		<div class="rb-control-input-wrapper">
			<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
			<select class="rb-select2" type="select2" {{ multiple }} data-setting="{{ data.name }}">
				<# _.each( data.options, function( option_title, option_value ) {
				var value = data.controlValue;
				if ( typeof value == 'string' ) {
				var selected = ( option_value === value ) ? 'selected' : '';
				} else {
				var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
				}
				#>
					<option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
				<# } ); #>
			</select>
		</div>
	</div>
	<# if ( data.description ) { #>
		<div class="rb-control-description">{{{ data.description }}}</div>
	<# } #>
</div>