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
			<# if ( data.description ) { #>
				<div class="rb-control-description">{{{ data.description }}}</div>
			<# } #>
			<div class="rb-control-media">
				<div class="rb-control-gallery-status">
					<span class="rb-control-gallery-status-title">
						<# if ( data.controlValue.length ) {
						print( rb.translate( 'gallery_images_selected', [ data.controlValue.length ] ) );
						} else { #>
							{l s='No Images Selected' mod='rbthemedream'}
						<# } #>
					</span>
					<span class="rb-control-gallery-clear">({l s='Clear' mod='rbthemedream'}</span>
				</div>
				<div class="rb-control-gallery-thumbnails">
					<# _.each( data.controlValue, function( image ) { #>
						<div class="rb-control-gallery-thumbnail" style="background-image: url({{ image.url }})"></div>
					<# } ); #>
				</div>
				<button class="rb-button rb-control-gallery-add">{l s='Add Images' mod='rbthemedream'}></button>
			</div>
		</div>
<	/div>
</div>