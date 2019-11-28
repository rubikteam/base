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

<div id="rb-section-wrap"></div>
<div id="rb-add-section" class="rb-visible-desktop">
	<div id="rb-add-section-inner">
		<div id="rb-add-new-section">
			<button id="rb-add-section-button" class="rb-button">{l s='Add New Section' mod='rbthemedream'}</button>

			<div id="rb-add-section-drag-title">{l s='Or drag widget here' mod='rbthemedream'}</div>
		</div>
		<div id="rb-select-preset">
			<div id="rb-select-preset-close">
				<i class="fa fa-times"></i>
			</div>
			<div id="rb-select-preset-title">{l s='Select your Structure' mod='rbthemedream'}</div>
			<ul id="rb-select-preset-list">
	          	{literal}
		          	<#
		                var structures = [ 10, 20, 30, 40, 21, 22, 31, 32, 33, 50, 60, 34 ];
		                _.each( structures, function(structure) {
		                var preset = rb.presetsFactory.getPresetByStructure(structure);
		            #>
		            <li class="rb-preset rb-column rb-col-16"
		                data-structure="{{ structure }}">
		              	{{{ rb.presetsFactory.getPresetSVG( preset.preset ).outerHTML }}}
		            </li>
		            
		            <# 
		        		});
		            #>
              	{/literal}
			</ul>
		</div>
	</div>
</div>