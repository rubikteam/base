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
			<div class="rb-control-media">
				<div class="rb-control-media-upload-button">
					<i class="fa fa-plus-circle"></i>
				</div>
				<div class="rb-control-media-image-area">
					<div class="rb-control-media-image" style="background-image: url({{ data.controlValue.url }});"></div>
					<div class="rb-control-media-delete">{l s='Delete' mod='rbthemedream'}</div>
				</div>
			</div>
			<input type="text"
				id="rb-control-media-field-{{ data._cid }}"
				class="rb-control-media-field"
				value="{{ data.controlValue.url }}"
			/>
		</div>

		<# if ( data.description ) { #>
			<div class="rb-control-description">{{{ data.description }}}</div>
		<# } #>

		<input type="hidden" data-setting="{{ data.name }}"/>
	</div>
</div>