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

		{include file='./rb-units-template.tpl'}

		<div class="rb-control-input-wrapper">
			<ul class="rb-control-dimensions">
				{foreach from=$dimensions key=dimension_key item=dimension_title}
					<li class="rb-control-dimension">
						<input type="number" 
							data-setting="{$dimension_key}"
							placeholder="<#
						       if ( _.isObject( data.placeholder ) ) {
						        if ( ! _.isUndefined( data.placeholder.{$dimension_key} ) ) {
						            print( data.placeholder.{$dimension_key} );
						        }
						       } else {
						        print( data.placeholder );
						       } #>"
							<# if ( -1 === _.indexOf( allowed_dimensions, "{$dimension_key}" ) ) { #>
								disabled
								<# } #>
						/>
						<span>{$dimension_title}</span>
					</li>		
				{/foreach}

				<li>
					<button class="rb-link-dimensions tooltip-target"
						data-tooltip="{l s='Link Values Together' mod='rbthemedream'}"
					>
						<span class="rb-linked"><i class="fa fa-link"></i></span>
						<span class="rb-unlinked"><i class="fa fa-chain-broken"></i></span>
					</button>
				</li>
			</ul>
		</div>
	</div>

	<# if ( data.description ) { #>
		<div class="rb-control-description">{{{ data.description }}}</div>
	<# } #>
</div>