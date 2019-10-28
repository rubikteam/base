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
{block name='product_add_to_cart'}
	<div class="product-add-to-cart-rb">
		{if $product.availability != 'unavailable'}
			{if ( ((Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY') == 1) && (!empty($product.main_variants))) || (Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY') == 1 || empty($product.main_variants)))}
				{block name='product_quantity'}
					<div class="product-quantity">
						<div class="add">
							<button class="btn btn-primary add-to-cart" title="{l s='Add to cart' d='rbthemefunction'}" data-button-action="add-to-cart" type="submit">
								<i class="material-icons">shopping_cart</i>
								{l s='Add To Cart' mod='rbthemefunction'}
							</button>

							{block name='product_availability'}
								<span class="product-availability hidden">
									{if $product.show_availability && $product.availability_message}
										{if $product.availability == 'available'}
											<i class="material-icons product-available">available</i>
										{elseif $product.availability == 'last_remaining_items'}
											<i class="material-icons product-last-items">last-items</i>
										{else}
											<i class="material-icons product-unavailable">unavailable</i>
										{/if}
									{/if}
								</span>
							{/block}
						</div>
					</div>
				{/block}

				{block name='product_minimal_quantity'}
					<p class="product-minimal-quantity hidden">
						{if $product.minimal_quantity > 1}
							{l s='The minimum purchase order quantity for the product is %quantity%.' d='rbthemefunction' sprintf=['%quantity%' => $product.minimal_quantity]}
						{/if}
					</p>
				{/block}	
			{/if}
		{/if}
	</div>
{/block}