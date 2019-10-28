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
{include file='./rb-base.tpl' type='banner'}

<div class="rb-widget-container">
	<#
        var html = '<div class="rb-live-banner">';

        if ( settings.link.url ) {
                html += '<a href="' + settings.link.url + '">';
        }

        if ( settings.image.url ) {
            var imageHtml = '<img src="' + settings.image.url + '" class="rb-animation-' + settings.hover_animation + '"   alt="' + settings.caption + '"  />';
            html += '<figure class="rb-live-banner-img"><span class="rb-live-banner-overlay"></span>' + imageHtml + '</figure>';
        }

        var hasContent = !! ( settings.title_text || settings.description_text );

        if ( hasContent ) {
            html += '<div class="rb-live-banner-content rb-live-banner-content-' + settings.content_position + ' rb-banner-align-'+ settings.content_vertical_alignment + '">';

            if ( settings.above_title_text ) {
                html += '<span class="rb-live-banner-subtitle rb-live-banner-description">' + settings.above_title_text + '</span>';
            }

            if ( settings.title_text ) {
                var title_html = settings.title_text;

                html += '<' + settings.title_size  + ' class="rb-live-banner-title">' + title_html + '</' + settings.title_size  + '>';
            }

            if ( settings.description_text ) {
                html += '<div class="rb-live-banner-description">' + settings.description_text + '</div>';
            }

            if ( settings.button_text ) {
                html += '<div><span class="rb-button-link rb-button btn rb-size-' + settings.button_size + ' btn-primary">';

                if ( settings.button_icon ) {
                    html += '<span class="rb-button-icon rb-align-icon-' + settings.button_icon_align + '"><i class="' + settings.button_icon + '"></i></span>';
                }

                 html += '<span class="rb-button-text">' + settings.button_text + '</span></span></div>';
            }

            html += '</div>';
        }

        if ( settings.link.url ) {
            html += '</a>';
        }

        html += '</div>';

        print( html );
    #>	
</div>