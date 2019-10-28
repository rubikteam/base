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
{include file='./rb-base.tpl' type='tabs'}

<div class="rb-widget-container">
	<div class="rb-tabs tabs" data-active-tab="{{ editSettings.activeItemIndex ? editSettings.activeItemIndex : 0 }}">
		<#
			if ( settings.tabs ) {
				var counter = 1; #>
				<ul class="nav nav-tabs">
					<#
					_.each( settings.tabs, function( item ) { #>

						<li class="nav-item"><a class="nav-link rb-tab-title" data-tab="{{ counter }}">{{{ item.tab_title }}}</a></li>
					<#
						counter++;
					} ); #>
				</ul>

				<# counter = 1; #>
				<div class="rb-tabs-content-wrapper tab-content">
					<#
					_.each( settings.tabs, function( item ) { #>
						<div class="rb-tab-content tab-pane" data-tab="{{ counter }}">{{{ item.tab_content }}}</div>
					<#
					counter++;
					} ); #>
				</div>
			<# }
		#>
	</div>
</div>