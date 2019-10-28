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
<div class="rb-template-library-blank-title">
	{l s='Save Your To Library' mod='rbthemedream'}
</div>

<div class="rb-template-library-blank-excerpt">
	{l s='Your designs will be available for export and reuse on any page or website' mod='rbthemedream'}
</div>

<form id="rb-template-library-save-template-form">
	<input id="rb-template-library-save-template-name"
		name="title"
		placeholder="{l s='Enter Template Name' mod='rbthemedream'}"
		required
	>
	<button id="rb-template-library-save-template-submit" class="rb-button rb-button-success">
		<span class="rb-state-icon">
			<i class="fa fa-spin fa-circle-o-notch "></i>
		</span>
		{l s='Save' mod='rbthemedream'}
	</button>
</form>

<div class="rb-template-library-blank-footer">
	{l s='What is Library' mod='rbthemedream'}
	<a class="rb-template-library-blank-footer-link" href="#" target="_blank">
		{l s='Read our tutorial on using Library templates' mod='rbthemedream'}
	</a>
</div>