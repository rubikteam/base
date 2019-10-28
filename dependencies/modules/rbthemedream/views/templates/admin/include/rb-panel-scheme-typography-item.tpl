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
<div class="rb-panel-heading">
	<div class="rb-panel-heading-toggle">
		<i class="fa"></i>
	</div>
	<div class="rb-panel-heading-title">{{{ title }}}</div>
</div>

<div class="rb-panel-scheme-typography-items rb-panel-box-content">
	{foreach from=$scheme_fields key=option_name item=option}
		<div class="rb-panel-scheme-typography-item">
			<div class="rb-panel-scheme-item-title rb-control-title">{$option.label}</div>
			<div class="rb-panel-scheme-typography-item-value">
				{if $option.type == 'select'}
					<select name="{$option_name}" class="rb-panel-scheme-typography-item-field">
						{foreach from=$option.options key=field_key item=field_value}
							<option value="{$field_key}">{$field_value}</option>
						{/foreach}
					</select>
				{else if $option.type == 'font'}
					<select name="{$option_name}" class="rb-panel-scheme-typography-item-field">
						<option value="">{l s='Default' mod='rbthemedream'}></option>
						<optgroup label="{l s='System' mod='rbthemedream'}">
							{foreach from=$font_systems key=key_system item=system}
								<option value="{$key_system}">{$key_system}</option>
							{/foreach}
						</optgroup>

						<optgroup label="{l s='Google' mod='rbthemedream'}">
							{foreach from=$font_googles key=key_google item=google}
								<option value="{$key_google}">{$key_google}</option>
							{/foreach}
						</optgroup>
					</select>
				{else if $option.type == 'text'}
					<input name="{$option_name}" class="rb-panel-scheme-typography-item-field"/>
				{/if}
			</div>
		</div>
	{/foreach}
</div>