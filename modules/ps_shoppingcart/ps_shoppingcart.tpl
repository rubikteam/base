{*
* 2007-2017 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div id="blockcart" class="blockcart cart-preview" data-refresh-url="{$refresh_url}">
    <a id="cart-toogle" class="cart-toogle header-btn header-cart-btn" data-toggle="dropdown" data-display="static">
        <i class="fa fa-shopping-bag fa-fw icon" aria-hidden="true">
            <span class="cart-products-count-btn">{$cart.products_count}</span>
        </i>
        <span class="info-wrapper">
            <span class="title">{l s='Cart' d='Shop.Theme.Checkout'}</span>
            <span class="cart-toggle-details">
                <span class="text-faded cart-separator"> / </span>

                {if $cart.products_count > 0}
                    <span class="cart-products-count">({$cart.products_count})</span>
                    {foreach from=$cart.subtotals item="subtotal"}
                        {if $subtotal.type == 'products'}
                            <span class="value">{$subtotal.value}</span>
                        {/if}
                    {/foreach}
                {else}
                    {l s='Empty' d='Shop.Theme.Checkout'}
                {/if}
            </span>
        </span>
    </a>
    {include 'module:ps_shoppingcart/ps_shoppingcart-content.tpl' class='dropdown'}
</div>