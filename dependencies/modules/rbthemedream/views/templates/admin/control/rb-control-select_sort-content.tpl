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
{literal}
<div class="rb-control-content">
	<div class="rb-control-field">
		<label class="rb-control-title">{{{ data.label }}}</label>

		<select class="rb-select-sort-selector" <# if ( data.multiple ) { #> multiple <# } #>>
			<# _.each( data.options, function( option_title, option_value ) {
				if (!_.contains(data.controlValue, option_value)){
				#>
					<option value="{{ option_value }}">{{{ option_title }}}</option>
				<#
			}}); #>
		</select>
	</div>
{/literal}
	<button class="rb-button rb-value-add">
		<i class="fa fa-angle-down"></i>
		{l s='Select' mod='rbthemedream'}
	</button>

	<div class="rb-control-field">
		<label class="rb-control-title">{l s='Selected' mod='rbthemedream'}</label>
	</div>
{literal}
	<div class="rb-control-field">
		<div class="rb-control-selected-preview">
			<# _.each( data.controlValue, function(option_value) {
			if (!_.isEmpty(data.options[option_value])){#>
			<div class="rb-selected-value-preview" data-value-text="{{{ data.options[option_value]  }}}" data-value-id="{{ option_value }}"><div class="rb-repeater-row-handle-sortable"><i class="fa fa-ellipsis-v"></i></div>
			<div class="selected-value-preview-info">{{{ data.options[option_value]  }}}<button data-value-id="{{ option_value }}" data-value-text="{{{ data.options[option_value]  }}}" class="rb-selected-value-remove selected-value-remove{{ option_value }}"><i class="fa fa-remove"></i></button></div></div>
			<# }} ); #>
		</div>

		<div class="rb-control-input-wrapper rb-control-type-select_sort">
			<select class="rb-select-sort" data-setting="{{ data.name }}" <# if ( data.multiple ) { #> multiple <# } #>>
				<# _.each( data.controlValue, function(option_value) {
				if (!_.isEmpty(data.options[option_value])){
				#>
					<option value="{{ option_value }}">{{{ data.options[option_value]}}}</option>
				<# }} ); #>
			</select>
		</div>

	</div>

	<# if ( data.description ) { #>
		<div class="rb-control-description">{{{ data.description }}}</div>
	<# } #>
</div>
{/literal}