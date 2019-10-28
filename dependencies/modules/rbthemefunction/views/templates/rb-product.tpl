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
{foreach from=$products item=product}
	<div class="rb-item-product clearfix">
		<div class="rb-product-img clearfix col-md-3">
			<a class="image" href="{$product.link}" title="{$product.name}">
				<img class="img img-thumbnail" src="{$product.image}" title="{$product.name}" alt="{$product.name}">
			</a>
		</div>

		<div class="rb-product-info col-md-9">
			<a class="rb-product-name" href="{$product.link}" title="{$product.name}">{$product.name}</a>
			<p class="rb_product_price">{$product.price_tax_excl}</p>
		</div>
	</div>
{/foreach}