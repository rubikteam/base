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

{if $RBTHEMEMENU_DISPLAY_SHOPPING_CART || $RBTHEMEMENU_DISPLAY_SEARCH || $RBTHEMEMENU_DISPLAY_CUSTOMER_INFO || $RBTHEMEMENU_CUSTOM_HTML_TEXT}
    <div class="rb_extra_item{if $RBTHEMEMENU_SEARCH_DISPLAY_DEFAULT} rb_display_search_default{/if}">
        {if $RBTHEMEMENU_CUSTOM_HTML_TEXT}
            <div class="rb_custom_text">
                {$RBTHEMEMENU_CUSTOM_HTML_TEXT nofilter}
            </div>
        {/if}
        {if $RBTHEMEMENU_DISPLAY_SEARCH}
            {hook h='displaySearch'}
        {/if}
        {if $RBTHEMEMENU_DISPLAY_CUSTOMER_INFO}
            {hook h='displayCustomerInforTop'}
        {/if}
        {if $RBTHEMEMENU_DISPLAY_SHOPPING_CART }
            {hook h='displayCartTop'}
        {/if}
    </div>
{/if}