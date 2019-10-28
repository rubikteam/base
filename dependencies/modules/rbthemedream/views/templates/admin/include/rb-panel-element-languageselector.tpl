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

<div class="rb-noname">
	{l s='Edit:' mod='rbthemedream'}
	{literal}
	<select>
		<# _.each(rb.config.languages, function( language ) { #>
		<option value="{{{ language.id_lang }}}" <# if (rb.config.id_lang == language.id_lang) {#> selected <# } #> >{{{ language.name }}}</option>
		<# } ); #>
	</select>
	<div title="{l s='Import from  other language' mod='rbthemedream'}" id="rb-panel-elements-language-import">
		<span id="rb-panel-elements-language-import-btn">
			<i class="rb-panel-elements-language-clone">
				<svg height="14px" viewBox="-30 0 512 512" width="14px" xmlns="http://www.w3.org/2000/svg"><path d="m381.890625 21.214844 48.894531 48.894531h-48.894531zm-381.890625 490.785156v-432h201.890625v100.109375h100.109375v331.890625zm231.890625-410.785156 48.894531 48.894531h-48.894531zm100.109375 330.785156v-273.101562l-108.898438-108.898438h-74.101562v-50h202.890625v100.109375h100.109375v331.890625zm0 0"/></svg>
			</i>
			<i class="rb-panel-elements-language-close">
				<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="14px" viewBox="0 0 95.939 95.939" style="enable-background:new 0 0 95.939 95.939;" xml:space="preserve"><g><path d="M62.819,47.97l32.533-32.534c0.781-0.781,0.781-2.047,0-2.828L83.333,0.586C82.958,0.211,82.448,0,81.919,0c-0.53,0-1.039,0.211-1.414,0.586L47.97,33.121L15.435,0.586c-0.75-0.75-2.078-0.75-2.828,0L0.587,12.608c-0.781,0.781-0.781,2.047,0,2.828L33.121,47.97L0.587,80.504c-0.781,0.781-0.781,2.047,0,2.828l12.02,12.021c0.375,0.375,0.884,0.586,1.414,0.586c0.53,0,1.039-0.211,1.414-0.586L47.97,62.818l32.535,32.535c0.375,0.375,0.884,0.586,1.414,0.586c0.529,0,1.039-0.211,1.414-0.586l12.02-12.021c0.781-0.781,0.781-2.048,0-2.828L62.819,47.97z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
			</i>
		</span>
		<div id="rb-panel-elements-language-import-list">
		{/literal}
			{l s='Import content from  other language' mod='rbthemedream'}
		{literal}	
			<ul>
				<# _.each(rb.config.languages, function(language) { #>
				<# if (!(rb.config.id_lang == language.id_lang)) {#> <li><a href="#" class="rb-panel-elements-language-import-lng" data-language="{{{ language.id_lang }}}"  >{{{ language.name }}}</a></li><# } #>
				<# } ); #>
			</ul>
		</div>
	</div>
</div>
{/literal}