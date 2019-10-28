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
<div class="rb-template-library-template-icon">
	<i class="fa fa-{{ 'section' === type ? 'columns' : 'file-text-o' }}"></i>
</div>

<div class="rb-template-library-template-name">{{{ title }}}</div>

<div class="rb-template-library-template-controls">
	<button class="rb-template-library-template-insert rb-button rb-button-success">
		<i class="eicon-file-download"></i>
		<span class="rb-button-title">{l s='Insert' mod='rbthemedream'}</span>
	</button>

	<div class="rb-template-library-template-export">
		<a href="{{ export_link }}">
			<i class="fa fa-sign-out"></i>
			<span class="rb-template-library-template-control-title">
				{l s='Export' mod='rbthemedream'}
			</span>
		</a>
	</div>

	<div class="rb-template-library-template-delete">
		<i class="fa fa-trash-o"></i>
		<span class="rb-template-library-template-control-title">
			{l s='Delete' mod='rbthemedream'}
		</span>
	</div>

	<div class="rb-template-library-template-preview">
		<i class="eicon-zoom-in"></i>
		<span class="rb-template-library-template-control-title">
			{l s='Preview' mod='rbthemedream'}
		</span>
	</div>
</div>