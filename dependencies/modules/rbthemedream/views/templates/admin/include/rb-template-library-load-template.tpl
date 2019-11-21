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
	{l s='Load Template From File' mod='rbthemedream'}
</div>

<div class="rb-template-library-blank-excerpt">
	{l s='Import .json Design File From Your PC' mod='rbthemedream'}
</div>

<form id="rb-template-library-load-template-form">
	<div id="rb-template-library-load-wrapper">
		<button id="rb-template-library-load-btn-file">
			{l s='Select template .json file' mod='rbthemedream'}
		</button>
		<input id="rb-template-library-load-template-file" type="file" name="file" required>
	</div>
	<button id="rb-template-library-load-template-submit" class="rb-button rb-button-success">
		<span class="rb-state-icon">
			<i class="fa fa-spin fa-circle-o-notch "></i>
		</span>
		{l s='Load' mod='rbthemedream'}
	</button>
</form>