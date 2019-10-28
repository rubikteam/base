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
{include file='./rb-base.tpl' type='icon-box'}

<div class="rb-widget-container">
	<# var link = settings.link.url ? 'href="' + settings.link.url + '"' : '',
		iconTag = link ? 'a' : 'span'; #>
	<div class="rb-icon-box-wrapper">
		<div class="rb-icon-box-icon">
			<{{{ iconTag + ' ' + link }}} class="rb-icon rb-animation-{{ settings.hover_animation }}">
			<i class="{{ settings.icon }}"></i>
			</{{{ iconTag }}}>
		</div>
		<div class="rb-icon-box-content">
			<{{{ settings.title_size }}} class="rb-icon-box-title">
			<{{{ iconTag + ' ' + link }}}>{{{ settings.title_text }}}</{{{ iconTag }}}>
			</{{{ settings.title_size }}}>
			<div class="rb-icon-box-description">{{{ settings.description_text }}}</div>
		</div>
	</div>
</div>