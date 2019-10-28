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
{foreach from=$products item=product_item name=for_products}
	{assign var='product' value=$product_item.product_info}
	{assign var='wishlist' value=$product_item.wishlist_info}

	<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-xs-12 product-miniature js-product-miniature rb-wishlist-item rb-wishlist-item-{$wishlist.id_rbthemefunction_wishlist_product} product-{$product.id_product}" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
		<div class="delete-wishlist-product clearfix">
			{include file='module:rbthemefunction/views/templates/rb-ajax-loading.tpl'}
			<a class="rb-wishlist-delete btn" href="#" title="{l s='Remove from this wishlist' mod='rbthemefunction'}" data-id-wishlist="{$wishlist.id_rbthemefunction_wishlist}" data-id-wishlist-product="{$wishlist.id_rbthemefunction_wishlist_product}" data-id-product="{$product.id_product}">
				<i class="material-icons">&#xE872;</i>
			</a>
		</div>

		<div class="thumbnail-container clearfix">
			{block name='product_thumbnail'}
				<a href="{$product.url}" class="thumbnail product-thumbnail">
					<img class="img-fluid"
					src = "{$product.cover.bySize.home_default.url}"
					alt = "{$product.cover.legend}"
					data-full-size-image-url = "{$product.cover.large.url}"
					>
				</a>
			{/block}

			<div class="product-description">							
				{block name='product_name'}
					<h1 class="h3 product-title" itemprop="name">
						<a href="{$product.url}">{$product.name|truncate:30:'...'}</a>
					</h1>
				{/block}
			</div>			
		</div>
	</div>
{/foreach}