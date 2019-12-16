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
<div class="rb-tag-cate">
	{if !empty(categories)}
		<div class="rb-category">
			<label class="title-cate">
				<i class="material-icons">edit</i>
				{l s='Categories:' mod='rbthemefunction'}
			</label>

			{assign var="count_cate" value="1"}

			{foreach from=$categories item=category}
				{if $count_cate == 1}
					<span class="rb-items">
						<a href="{$obj_link->getCategoryLink($category.id_category)}">
							{$category.name}
						</a>
					</span>
				{else}
					{l s=', ' mod='rbthemefunction'}
					<span class="rb-items">
						<a href="{$obj_link->getCategoryLink($category.id_category)}">
							{$category.name}
						</a>
					</span>	
				{/if}

				{$count_cate = $count_cate + 1}
			{/foreach}
		</div>
	{/if}

	{if $tags != ''}
		<div class="rb-tag">
			<label class="title-tag">
				<i class="material-icons">bookmark_border</i>
				{l s='Tags:' mod='rbthemefunction'}
			</label>

			<span class="rb-items">{$tags}</span>
		</div>
	{/if}
</div>