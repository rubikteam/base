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
<article class="rb-mini-product" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
	<div class="thumbnail-container relative row">
		<div class="thumbnail product-thumbnail relative">
	      	{block name='product_thumbnail'}
		      	<a href="{$product.url}" class="relative">
		        	{include file='catalog/_partials/product-rb-image.tpl' image=$product.cover type=cart_default}
		      	</a>
	      	{/block}
    	</div>

    	<div class="product-description">
    		{if isset($product.manufacturer_name)}
		      	{block name='product_manufacturer'}
		        	<h6 class="product-brand">{$product.manufacturer_name}</h6>
		      	{/block}
      		{/if}

      		{block name='product_name'}
        		<h3 class="product-title">
        			<a class="rb-product" href="{$product.url}">{$product.name}</a>
        		</h3>
      		{/block}

      		{block name='product_price_and_shipping'}
      			<div class="product-price-and-shipping">
      				{*
      				{if $product.has_discount}
			            {hook h='displayProductPriceBlock' product=$product type="old_price"}
			            <span class="regular-price">{$product.regular_price}</span>

			            {if $product.discount_type === 'percentage'}
			            	<span class="discount-percentage">{$product.discount_percentage}</span>
			            {/if}
          			{/if}
          			*}

          			<span class="price">
			          	{if isset($product.light_list)}
			            	{$product.price}
			          	{else}
			            	{if isset($product.cart_quantity) && isset($product.total)}
					            {if $product.cart_quantity > 1}
					              	{$product.total} <span>({$product.cart_quantity} &#215; {$product.price})</span>
					            {else}
					              	{$product.total}
			            		{/if}
			            	{/if}
			          	{/if}
          			</span>
      			</div>
      		{/block}

      		{if !isset($product.light_list) && isset($product.remove_from_cart_url)}
		      	<a href="{$product.remove_from_cart_url}" rel="nofollow" class="remove-product" data-link-action="remove-from-cart" title="{l s='Remove from cart' d='Shop.Theme.Actions'}">
		        	<svg width='15px' height='15px' version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 51.976 51.976" style="enable-background:new 0 0 51.976 51.976;" xml:space="preserve"><g><path d="M44.373,7.603c-10.137-10.137-26.632-10.138-36.77,0c-10.138,10.138-10.137,26.632,0,36.77s26.632,10.138,36.77,0C54.51,34.235,54.51,17.74,44.373,7.603z M36.241,36.241c-0.781,0.781-2.047,0.781-2.828,0l-7.425-7.425l-7.778,7.778c-0.781,0.781-2.047,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l7.778-7.778l-7.425-7.425c-0.781-0.781-0.781-2.048,0-2.828c0.781-0.781,2.047-0.781,2.828,0l7.425,7.425l7.071-7.071c0.781-0.781,2.047-0.781,2.828,0c0.781,0.781,0.781,2.047,0,2.828l-7.071,7.071l7.425,7.425C37.022,34.194,37.022,35.46,36.241,36.241z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
		      	</a>
      		{/if}
    	</div>
	</div>
</article>