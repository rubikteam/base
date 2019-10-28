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
<div class="rb-tabs tabs">
	{$counter1 = 1}
	{$counter2 = 1}

	<ul class="nav nav-tabs">
		{foreach $instance.tabs item=item}
			<li class="nav-item">
				<a class="nav-link rb-tab-title" data-tab="{$counter1}" >{$item.tab_title nofilter}</a>
			</li>
			{$counter1 = $counter1 + 1}
		{/foreach}
	</ul>

	<div class="rb-tabs-content-wrapper tab-content">
		{foreach $instance.tabs item=item}
			<div data-tab="{$counter2}" class="rb-tab-content tab-pane">
				{RbControl::parseTextEditor($item.tab_content, $item) nofilter}
			</div>

			{$counter2 = $counter2 + 1}
		{/foreach}
	</div>
</div>