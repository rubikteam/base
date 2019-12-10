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
<div class="product-button-next-prev">
	<div class="product-button-prev">
		{if isset($productPrev) && !empty($productPrev)}
			<a class="btn btn-default button button-prev" href="{$productPrev.url}" title="{$productPrev.name}">
				<i class="fa fa-angle-left"></i>
            </a>

            <div class="button-hover">
            	<img class="img-fluid" src="{$productPrev.image}" title="{$productPrev.name}">
            	<h5>{$productPrev.name}</h5>
            </div>
		{/if}
	</div>

	<div class="product-button-next">
		{if isset($productNext) && !empty($productNext)}
			<a class="btn btn-default button button-prev" href="{$productNext.url}" title="{$productNext.name}">
				<i class="fa fa-angle-right"></i>
            </a>

            <div class="button-hover">
            	<img class="img-fluid" src="{$productNext.image}" title="{$productNext.name}">
            	<h5>{$productNext.name}</h5>
            </div>
		{/if}
	</div>
</div>