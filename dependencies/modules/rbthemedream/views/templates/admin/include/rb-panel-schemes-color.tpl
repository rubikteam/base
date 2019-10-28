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
<div class="rb-panel-scheme-buttons">
	<div class="rb-panel-scheme-button-wrapper rb-panel-scheme-reset">
		<button class="rb-button">
			<i class="fa fa-undo"></i>
			{l s='Reset' mod='rbthemedream'}
		</button>
	</div>
	<div class="rb-panel-scheme-button-wrapper rb-panel-scheme-discard">
		<button class="rb-button">
			<i class="fa fa-times"></i>
			{l s='Discard' mod='rbthemedream'}
		</button>
	</div>
	<div class="rb-panel-scheme-button-wrapper rb-panel-scheme-save">
		<button class="rb-button rb-button-success" disabled>
			{l s='Apply' mod='rbthemedream'}
		</button>
	</div>
</div>

<div class="rb-panel-scheme-content rb-panel-box">
	<div class="rb-panel-heading">
		<div class="rb-panel-heading-title">{l s='Color Palette' mod='rbthemedream'}</div>
	</div>
	<div class="rb-panel-scheme-items rb-panel-box-content"></div>
</div>

<div class="rb-panel-scheme-colors-more-palettes rb-panel-box">
	<div class="rb-panel-heading">
		<div class="rb-panel-heading-title">{l s='More Palettes' mod='rbthemedream'}</div>
	</div>
	<div class="rb-panel-box-content">
		{foreach from=$schemes key=key_scheme item=scheme}
			<div class="rb-panel-scheme-color-system-scheme" data-scheme-name="{$key_scheme}">
				<div class="rb-panel-scheme-color-system-items">
					{foreach from=$print_colors_index item=color_name}
						{$colors_to_print[$color_name] = $scheme.items[$color_name]}
					{/foreach}

					{foreach from=$colors_to_print item=color_value}
						<div class="rb-panel-scheme-color-system-item" style='background-color:{$color_value}'>
							
						</div>
					{/foreach}
				</div>

				<div class="rb-title">{$scheme.title}</div>
			</div>
		{/foreach}
	</div>
</div>