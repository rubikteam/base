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
<div class="rb-element-overlay"></div>

<# if ( 'video' === settings.background_background ) {
    var videoLink = settings.background_video_link;

	if ( videoLink ) {
        var videoID = rb.helpers.getYoutubeIDFromURL( settings.background_video_link ); #>

        <div class="rb-background-video-container rb-hidden-phone">
           <# if ( videoID ) { #>
                <div class="rb-background-video" data-video-id="{{ videoID }}"></div>
            <# } else { #>
                <video class="rb-background-video" src="{{ videoLink }}" autoplay loop muted></video>
            <# } #>
        </div>
<# }

    if ( settings.background_video_fallback ) { #>
        <div class="rb-background-video-fallback" style="background-image: url({{ settings.background_video_fallback.url }})"></div>
    <# }
 }

if ( -1 !== [ 'classic', 'gradient' ].indexOf( settings.background_overlay_background ) ) { #>
    <div class="rb-background-overlay"></div>
<# } #>

<div class="rb-container rb-column-gap-{{ settings.gap }}" <# if ( settings.get_render_attribute_string ) { #>{{{ settings.get_render_attribute_string( 'wrapper' ) }}} <# } #> >
    <div class="rb-row"></div>
</div>