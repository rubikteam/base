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
{include file='./rb-base.tpl' type='image-hotspots'}

<div class="rb-widget-container">
	<# if ( '' !== settings.image.url ) { #>
		<div class="rb-image-hotspots-wrapper">
			<div class="rb-hotspot-image{{ settings.shape ? ' rb-image-shape-' + settings.shape : '' }}">
				<#
				var imgClass = '', image_html = '',
				hasCaption = '' !== settings.caption,
				image_html = '';

				if ( '' !== settings.hover_animation ) {
				imgClass = 'rb-animation-' + settings.hover_animation;
			}

			image_html = '<img src="' + settings.image.url + '" class="' + imgClass + '" alt="' + settings.caption + '" />';

			print( image_html );
			#>
		</div>
		<div class="rb-image-hotspots">
			<#
			if ( settings.icon_list ) {
			_.each( settings.icon_list, function( item ) { #>
			<div class="rb-hotspot" style="top: {{ item.top }}%; left: {{ item.left }}%;" >
				<# if ( item.link && item.link.url ) { #>
				<a href="{{ item.link.url }}">
					<# } #>
					<span class="rb-hotspot-icon">
						<i class="{{ item.icon }}"></i>
					</span>
					<span class="rb-hotspot-text">{{{ item.text }}}</span>
					<# if ( item.link && item.link.url ) { #>
				</a>
				<# } #>
			</div>
			<#
				});
			} #>
	</div>
</div>
    <# } #>
</div>