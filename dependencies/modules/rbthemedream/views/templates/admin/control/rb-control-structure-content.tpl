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
	<div class="rb-control-input-wrapper">
		<div class="rb-control-structure-title">{l s='Structure' mod='rbthemedream'}</div>
			<# var currentPreset = rb.presetsFactory.getPresetByStructure( data.controlValue ); #>
			<div class="rb-control-structure-preset rb-control-structure-current-preset">
				{{{ rb.presetsFactory.getPresetSVG( currentPreset.preset, 233, 72, 5 ).outerHTML }}}
			</div>
			<div class="rb-control-structure-reset">
				<i class="fa fa-undo"></i>
				{l s='Reset Structure' mod='rbthemedream'}
			</div>
			<#
			var morePresets = getMorePresets();

			if ( morePresets.length > 1 ) { #>
				<div class="rb-control-structure-more-presets-title">
					{l s='More Structures' mod='rbthemedream'}
				</div>
				<div class="rb-control-structure-more-presets">
					<# _.each( morePresets, function( preset ) { #>
						<div class="rb-control-structure-preset-wrapper">
							<input id="rb-control-structure-preset-{{ data._cid }}-{{ preset.key }}" type="radio" name="rb-control-structure-preset-{{ data._cid }}" data-setting="structure" value="{{ preset.key }}">
							<label class="rb-control-structure-preset" for="rb-control-structure-preset-{{ data._cid }}-{{ preset.key }}">
								{{{ rb.presetsFactory.getPresetSVG( preset.preset, 102, 42 ).outerHTML }}}
							</label>
							<div class="rb-control-structure-preset-title">{{{ preset.preset.join( ', ' ) }}}</div>
						</div>
					<# } ); #>
				</div>
			<# } #>
		</div>
	</div>
		
	<# if ( data.description ) { #>
		<div class="rb-control-description">{{{ data.description }}}</div>
	<# } #>
</div>