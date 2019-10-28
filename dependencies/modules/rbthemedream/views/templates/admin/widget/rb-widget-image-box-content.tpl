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
{include file='./rb-base.tpl' type='image-box'}

<div class="rb-widget-container">
	<#
        var html = '<div class="rb-image-box-wrapper">';

        if ( settings.image.url ) {
            var imageHtml = '<img src="' + settings.image.url + '"  alt="' + settings.caption + '" class="rb-animation-' + settings.hover_animation + '" />';

            if ( settings.link.url ) {
                imageHtml = '<a href="' + settings.link.url + '">' + imageHtml + '</a>';
            }

            html += '<figure class="rb-image-box-img">' + imageHtml + '</figure>';
        }

        var hasContent = !! ( settings.title_text || settings.description_text );

        if ( hasContent ) {
            html += '<div class="rb-image-box-content">';

            if ( settings.title_text ) {
                var title_html = settings.title_text;

                if ( settings.link.url ) {
                    title_html = '<a href="' + settings.link.url + '">' + title_html + '</a>';
                }

                html += '<' + settings.title_size  + ' class="rb-image-box-title">' + title_html + '</' + settings.title_size  + '>';
            }

            if ( settings.description_text ) {
                html += '<div class="rb-image-box-description">' + settings.description_text + '</div>';
            }

            html += '</div>';
        }

        html += '</div>';

        print( html );
    #>
</div>
