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
{if $menusHTML}
    <div class="rb_megamenu 
        {if isset($rb_config.RBTHEMEMENU_LAYOUT)&&$rb_config.RBTHEMEMENU_LAYOUT}layout_{$rb_config.RBTHEMEMENU_LAYOUT|escape:'html':'UTF-8'}{/if} 
        {if isset($rb_config.RBTHEMEMENU_SHOW_ICON_VERTICAL) && $rb_config.RBTHEMEMENU_SHOW_ICON_VERTICAL} show_icon_in_mobile{/if} 
        {if isset($rb_config.RBTHEMEMENU_SKIN)&&$rb_config.RBTHEMEMENU_SKIN}skin_{$rb_config.RBTHEMEMENU_SKIN|escape:'html':'UTF-8'}{/if}  
        {if isset($rb_config.RBTHEMEMENU_TRANSITION_EFFECT)&&$rb_config.RBTHEMEMENU_TRANSITION_EFFECT}transition_{$rb_config.RBTHEMEMENU_TRANSITION_EFFECT|escape:'html':'UTF-8'}{/if}   
        {if isset($rb_config.RB_MOBILE_RB_TYPE)&&$rb_config.RB_MOBILE_RB_TYPE}transition_{$rb_config.RB_MOBILE_RB_TYPE|escape:'html':'UTF-8'}{/if} 
        {if isset($rb_config.RBTHEMEMENU_CUSTOM_CLASS)&&$rb_config.RBTHEMEMENU_CUSTOM_CLASS}{$rb_config.RBTHEMEMENU_CUSTOM_CLASS|escape:'html':'UTF-8'}{/if}
        {if isset($rb_config.RBTHEMEMENU_ACTIVE_ENABLED)&&$rb_config.RBTHEMEMENU_ACTIVE_ENABLED}enable_active_menu{/if} 
        {if isset($rb_layout_direction)&&$rb_layout_direction}{$rb_layout_direction|escape:'html':'UTF-8'}{else}rb-dir-ltr{/if}
        {if isset($rb_config.RBTHEMEMENU_HOOK_TO)&&$rb_config.RBTHEMEMENU_HOOK_TO=='customhook'}hook-custom{else}hook-default{/if}
        {if isset($rb_multiLayout)&&$rb_multiLayout}multi_layout{else}single_layout{/if}
        {if isset($rb_config.RBTHEMEMENU_STICKY_DISMOBILE) && $rb_config.RBTHEMEMENU_STICKY_DISMOBILE } disable_sticky_mobile {/if}
        "
        data-bggray="{if isset($rb_config.RBTHEMEMENU_ACTIVE_BG_GRAY)&&$rb_config.RBTHEMEMENU_ACTIVE_BG_GRAY}bg_gray{/if}"
        >
        <div class="rb_megamenu_content">
            <div class="container">
                <div class="rb_megamenu_content_content">
                    <div class="ybc-menu-toggle ybc-menu-btn closed">
                        <span class="ybc-menu-button-toggle_icon">
                            <i class="icon-bar"></i>
                            <i class="icon-bar"></i>
                            <i class="icon-bar"></i>
                        </span>
                        {l s='Menu' mod='rbthememenu'}
                    </div>
                    {$menusHTML nofilter}
                </div>
            </div>
        </div>
    </div>
{/if}