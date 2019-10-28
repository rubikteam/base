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
	<div class="rb-control-field rb-control-url-external-{{{ data.show_external ? 'show' : 'hide' }}}">
		<label class="rb-control-title">{{{ data.label }}}</label>
		<div class="rb-control-input-wrapper">
			<input type="url"
				data-setting="url"
				placeholder="{{ data.placeholder }}"
				id="rb-control-url-field-{{ data._cid }}"
			/>
			<button class="rb-control-url-target tooltip-target"
				data-tooltip="{l s='Open Link In New Tab' mod='rbthemedream'}"
				title="{l s='Open Link In New Tab' mod='rbthemedream'}"
			>
				<span class="rb-control-url-external"
					title="{l s='New Window' mod='rbthemedream'}"
				>
					<i class="fa fa-external-link"></i>
				</span>
			</button>

			<button class="rb-control-url-media tooltip-target"
				data-tooltip="{l s='Media Link' mod='rbthemedream'}"
				title="{l s='Choose Media Link' mod='rbthemedream'}"
			>
				<span class="rb-control-url-external" title="{l s='Media Link' mod='rbthemedream'}">
					<i class="fa fa-paperclip"></i>
				</span>
			</button>
		</div>
	</div>
	<# if ( data.description ) { #>
		<div class="rb-control-description">{{{ data.description }}}</div>
	<# } #>
</div>