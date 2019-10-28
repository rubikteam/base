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
{extends file='page.tpl'}

{block name="page_content"}
	{if isset($rb_compare_products) && count($rb_compare_products)}
		<h3 class="page_heading">{l s='Compare List' d='Shop.Theme.Catalog'}</h3>

		<div class="rb-compare-table">
			<table class="rb-table rb-table-bordered">
				<tbody>
					<tr>
		                <th scope="row">
		                	<a href="#" class="rb-compare-remove-all">
			                	<svg width='25px' height='25px' version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 44 44"> <path d="m22,0c-12.2,0-22,9.8-22,22s9.8,22 22,22 22-9.8 22-22-9.8-22-22-22zm3.2,22.4l7.5,7.5c0.2,0.2 0.3,0.5 0.3,0.7s-0.1,0.5-0.3,0.7l-1.4,1.4c-0.2,0.2-0.5,0.3-0.7,0.3-0.3,0-0.5-0.1-0.7-0.3l-7.5-7.5c-0.2-0.2-0.5-0.2-0.7,0l-7.5,7.5c-0.2,0.2-0.5,0.3-0.7,0.3-0.3,0-0.5-0.1-0.7-0.3l-1.4-1.4c-0.2-0.2-0.3-0.5-0.3-0.7s0.1-0.5 0.3-0.7l7.5-7.5c0.2-0.2 0.2-0.5 0-0.7l-7.5-7.5c-0.2-0.2-0.3-0.5-0.3-0.7s0.1-0.5 0.3-0.7l1.4-1.4c0.2-0.2 0.5-0.3 0.7-0.3s0.5,0.1 0.7,0.3l7.5,7.5c0.2,0.2 0.5,0.2 0.7,0l7.5-7.5c0.2-0.2 0.5-0.3 0.7-0.3 0.3,0 0.5,0.1 0.7,0.3l1.4,1.4c0.2,0.2 0.3,0.5 0.3,0.7s-0.1,0.5-0.3,0.7l-7.5,7.5c-0.2,0.1-0.2,0.5 3.55271e-15,0.7z"/></svg>
			                	{l s='Remove All' d='Shop.Theme.Catalog'}
		                	</a>
		                </th>
		                {foreach $rb_compare_products as $product}
		                    <td class="rb-compare-td-{$product.id_product}">
		                        <a href="#" title="{l s='Remove' d='Shop.Theme.Catalog'}" class="rb-remove-compare-product" data-id-product="{$product.id_product}">
		                        	<svg width='25px' height='25px' version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 44 44"> <path d="m22,0c-12.2,0-22,9.8-22,22s9.8,22 22,22 22-9.8 22-22-9.8-22-22-22zm3.2,22.4l7.5,7.5c0.2,0.2 0.3,0.5 0.3,0.7s-0.1,0.5-0.3,0.7l-1.4,1.4c-0.2,0.2-0.5,0.3-0.7,0.3-0.3,0-0.5-0.1-0.7-0.3l-7.5-7.5c-0.2-0.2-0.5-0.2-0.7,0l-7.5,7.5c-0.2,0.2-0.5,0.3-0.7,0.3-0.3,0-0.5-0.1-0.7-0.3l-1.4-1.4c-0.2-0.2-0.3-0.5-0.3-0.7s0.1-0.5 0.3-0.7l7.5-7.5c0.2-0.2 0.2-0.5 0-0.7l-7.5-7.5c-0.2-0.2-0.3-0.5-0.3-0.7s0.1-0.5 0.3-0.7l1.4-1.4c0.2-0.2 0.5-0.3 0.7-0.3s0.5,0.1 0.7,0.3l7.5,7.5c0.2,0.2 0.5,0.2 0.7,0l7.5-7.5c0.2-0.2 0.5-0.3 0.7-0.3 0.3,0 0.5,0.1 0.7,0.3l1.4,1.4c0.2,0.2 0.3,0.5 0.3,0.7s-0.1,0.5-0.3,0.7l-7.5,7.5c-0.2,0.1-0.2,0.5 3.55271e-15,0.7z"/></svg>
		                        	{l s='Remove' d='Shop.Theme.Catalog'}
		                        </a>
		                    </td>
		                {/foreach}
            		</tr>

            		{if $rb_compare_items&1}
			            <tr>
			                <th scope="row"></th>

			                {foreach $rb_compare_products as $product}
			                    <td class="rb-compare-td-{$product.id_product}">
			                        <a class="product_image" href="{$product.url}" title="{$product.name}">
			                        	<img src="{$product.cover.bySize.home_default.url}" width="{$product.cover.bySize.home_default.width}" height="{$product.cover.bySize.home_default.height}" alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name}{/if}"/>
			                        </a>
			                    </td>
			                {/foreach}
			            </tr>
            		{/if}

            		{if $rb_compare_items&2}
			            <tr>
			                <th scope="row">{l s='Name' d='Shop.Theme.Catalog'}</th>

			                {foreach $rb_compare_products as $product}
			                    <td class="rb-compare-td-{$product.id_product}">
			                        <h3 class="rb-title-block">
			                        	<a href="{$product.url}" title="{$product.name}">{$product.name}</a>
			                        </h3>
			                    </td>
			                {/foreach}
			            </tr>
            		{/if}

            		{if $rb_compare_items&4}
			            <tr>
			                <th scope="row">{l s='Price' d='Shop.Theme.Catalog'}</th>

			                {foreach $rb_compare_products as $product}
			                    <td class="rb-compare-td-{$product.id_product}">
			                        <span class="price">{$product.price}</span>
			                        {if $product.has_discount}
			                            <span class="regular-price">{$product.regular_price}</span>

			                            {if $product.discount_type === 'percentage'}
			                              	<span class="discount-percentage">{$product.discount_percentage}</span>
			                            {/if}
			                        {/if}
			                    </td>
			                {/foreach}
			            </tr>
            		{/if}

            		{if $rb_compare_items&8}
			            <tr>
			                <th scope="row">{l s='Short description' d='Shop.Theme.Catalog'}</th>

			                {foreach $rb_compare_products as $product}
			                    <td class="rb-compare-td-{$product.id_product}">
			                        {$product.description_short nofilter}
			                    </td>
			                {/foreach}
			            </tr>
		            {/if}

		            {if $rb_compare_items&16}
			            <tr>
			                <th scope="row">{l s='Stock' d='Shop.Theme.Catalog'}</th>

			                {foreach $rb_compare_products as $product}
			                    <td class="rb-compare-td-{$product.id_product}">
			                        {if $product.show_availability && $product.availability_message}
			                        	{$product.availability_message}
			                        {/if}
			                    </td>
			                {/foreach}
			            </tr>
		            {/if}

		            {if $rb_compare_items&64}
			            <tr>
			                <th scope="row">{l s='Color' d='Shop.Theme.Catalog'}</th>

			                {foreach $rb_compare_products as $product}
			                    <td class="rb-compare-td-{$product.id_product}">
			                        {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
			                    </td>
			                {/foreach}
			            </tr>
            		{/if}

            		{if isset($rb_compare_order_features) && !empty($rb_compare_order_features)}
		                {foreach $rb_compare_order_features as $feature}
		                    <tr>
		                        <th scope="row">
		                            {$feature.name}
		                        </th>

		                        {foreach $rb_compare_products as $product}
		                            {assign var='product_id' value=$product.id_product}
		                            {assign var='feature_id' value=$feature.id_feature}
		                            {if isset($rb_compare_order_features[$product_id])}
		                                {assign var='tab' value=$rb_compare_order_features[$product_id]}
		                                <td class="rb-compare-td-{$product.id_product}">{if (isset($tab[$feature_id]))}{$tab[$feature_id]}{/if}</td>
		                            {else}
		                                <td class="rb-compare-td-{$product.id_product}"></td>
		                            {/if}
		                        {/foreach}
		                    </tr>
		                {/foreach}
		            {else}
		                <tr>
		                    <th scope="row"></th>
		                    <td colspan="{$rb_compare_products|@count}" class="text-center">
		                    	{l s='No features to compare' d='Shop.Theme.Catalog'}
		                    </td>
		                </tr>
            		{/if}

            		{if $rb_compare_items&64}
			            <tr>
			                <th scope="row"></th>

			                {foreach $rb_compare_products as $product}
			                    <td class="rb-compare-td-{$product.id_product}">
			                        {if $product.add_to_cart_url && ($product.quantity>0 || $product.allow_oosp)}
			                          	{include file='module:rbthemefunction/views/templates/hook/rb-add-to-cart.tpl'}
			                          	{include file='catalog/_partials/product-view.tpl' classname="btn btn-default"}
			                        {else}
			                          	{include file='catalog/_partials/product-view.tpl' classname="btn btn-default"}
			                        {/if}
			                    </td>
			                {/foreach}
			            </tr>
		            {/if}

            		{*
            		{if $rb_compare_items&128}
			            <tr>
			                <th scope="row">{l s='Rating' d='Shop.Theme.Catalog'}</th>

			                {foreach $rb_compare_products as $product}
			                    <td class="rb-compare-td-{$product.id_product}">
			                        {if isset($product.stproductcomments) && $product.stproductcomments}{include file='catalog/_partials/miniatures/rating-box.tpl'}{/if}
			                    </td>
			                {/foreach}
			            </tr>
            		{/if}
            		*}
				</tbody>
			</table>
		</div>
	{else}
		<article class="alert alert-warning rb-no-products" role="alert" data-alert="warning">
          	{l s='There are no products in list compare' d='Shop.Theme.Catalog'}
        </article>
	{/if}
{/block}