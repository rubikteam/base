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
{if $have_li}
    <li class="rb_menus_li item{$menu.id_menu|intval} {if !$menu.enabled}rb_disabled{/if}" data-id-menu="{$menu.id_menu|intval}" data-obj="menu">
{/if}                        
    {if $menu.enabled_vertical}
        <div class="rb_menus_li_content" style="width: {if $menu.menu_item_width}{$menu.menu_item_width|escape:'html':'UTF-8'}{else}230px{/if}">
            <span class="rb_menu_name rb_menu_toggle">
                <span class="rb_menu_content_title">
                    {if $menu.menu_img_link}
                        <img src="{$menu.menu_img_link|escape:'html':'UTF-8'}" title="" alt="" width="20" />
                    {else if $menu.menu_icon}
                        <i class="fa {$menu.menu_icon|escape:'html':'UTF-8'}"></i>
                    {/if}
                    {$menu.title|escape:'html':'UTF-8'}
                    {if $menu.bubble_text}<span class="rb_bubble_text" style="background: {if $menu.bubble_background_color}{$menu.bubble_background_color|escape:'html':'UTF-8'}{else}#FC4444{/if}; color: {if $menu.bubble_text_color|escape:'html':'UTF-8'}{$menu.bubble_text_color}{else}#ffffff{/if};">{$menu.bubble_text}</span>{/if}
                </span>
            </span>
            <div class="rb_buttons button_add_tab">
                <span class="rb_menu_delete" title="{l s='Delete menu' mod='rbthememenu'}">{l s='Delete' mod='rbthememenu'}</span>  
                <span class="rb_duplicate" title="{l s='Duplicate menu' mod='rbthememenu'}">{l s='Duplicate' mod='rbthememenu'}</span>                      
                <span class="rb_menu_edit" title="{l s='Edit menu' mod='rbthememenu'}">{l s='Edit menu' mod='rbthememenu'}</span>                
                <span class="rb_menu_toggle rb_menu_toggle_arrow">{l s='Close' mod='rbthememenu'}</span> 
                <div class="rb_add_tab btn btn-default" data-id-menu="{$menu.id_menu|intval}" title="{l s='Add tab' mod='rbthememenu'}">{l s='Add tab' mod='rbthememenu'}</div> 
            </div> 
        </div>
        
        <div class="rb_tabs_ul">
            <ul class="rb_tabs_ul_content">
                {if $menu.tabs}                            
                    {foreach from=$menu.tabs item='tab'}
                        <li data-id-tab="{$tab.id_tab|intval}" class="rb_tabs_li item{$tab.id_tab|intval} {if !$tab.enabled}rb_disabled{/if}" data-obj="tab">
                            {hook h='displayRbItemTab' tab=$tab}
                        </li>
                    {/foreach}                            
                {/if}
            </ul>
        </div>
    {else}
        <div class="rb_menus_li_content">
            <span class="rb_menu_name rb_menu_toggle">
                <span class="rb_menu_content_title">
                    {if $menu.menu_img_link}
                        <img src="{$menu.menu_img_link|escape:'html':'UTF-8'}" title="" alt="" width="20" />
                    {else if $menu.menu_icon}
                        <i class="fa {$menu.menu_icon|escape:'html':'UTF-8'}"></i>
                    {/if}
                    {$menu.title|escape:'html':'UTF-8'}
                    {if $menu.bubble_text}<span class="rb_bubble_text" style="background: {if $menu.bubble_background_color}{$menu.bubble_background_color|escape:'html':'UTF-8'}{else}#FC4444{/if}; color: {if $menu.bubble_text_color|escape:'html':'UTF-8'}{$menu.bubble_text_color}{else}#ffffff{/if};">{$menu.bubble_text}</span>{/if}
                </span>
            </span>
            <div class="rb_buttons">
                <span class="rb_menu_delete" title="{l s='Delete menu' mod='rbthememenu'}">{l s='Delete' mod='rbthememenu'}</span>  
                <span class="rb_duplicate" title="{l s='Duplicate menu' mod='rbthememenu'}">{l s='Duplicate' mod='rbthememenu'}</span>                      
                <span class="rb_menu_edit" title="{l s='Edit menu' mod='rbthememenu'}">{l s='Edit' mod='rbthememenu'}</span>                
                <span class="rb_menu_toggle rb_menu_toggle_arrow">{l s='Close' mod='rbthememenu'}</span> 
                <div class="rb_add_column btn btn-default" data-id-menu="{$menu.id_menu|intval}" title="{l s='Add column' mod='rbthememenu'}">{l s='Add column' mod='rbthememenu'}</div> 
            </div> 
        </div>
        <ul class="rb_columns_ul">
            {if $menu.columns}                            
                {foreach from=$menu.columns item='column'}
                    <li data-id-column="{$column.id_column|intval}" class="rb_columns_li item{$column.id_column|intval} column_size_{$column.column_size|intval} {if $column.is_breaker}rb_breaker{/if}" data-obj="column">
                        {hook h='displayRbItemColumn' column=$column}
                    </li>
                {/foreach}                            
            {/if}  
        </ul> 
    {/if} 
{if $have_li}
</li>
{/if}