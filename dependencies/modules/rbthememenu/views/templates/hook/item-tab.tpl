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
    <li data-id-tab="{$tab.id_tab|intval}" class="rb_tabs_li item{$tab.id_tab|intval} {if !$tab.enabled}rb_disabled{/if}" data-obj="tab">
{/if}
    <div class="rb_tab_li_content" style="width: {if $menu.tab_item_width}{$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if}">
        <span class="rb_tab_name rb_tab_toggle">
            <span class="rb_tab_toggle_title">
                {if $tab.url}
                    <a href="{$tab.url|escape:'html':'UTF-8'}">
                {/if}
                {if $tab.tab_img_link}
                    <img src="{$tab.tab_img_link|escape:'html':'UTF-8'}" title="" alt="" width="20" />
                {else if $tab.tab_icon}
                    <i class="fa {$tab.tab_icon|escape:'html':'UTF-8'}"></i>
                {/if}
                {$tab.title|escape:'html':'UTF-8'}
                {if $tab.bubble_text}<span class="rb_bubble_text" style="background: {if $tab.bubble_background_color}{$tab.bubble_background_color|escape:'html':'UTF-8'}{else}#FC4444{/if}; color: {if $tab.bubble_text_color|escape:'html':'UTF-8'}{$tab.bubble_text_color}{else}#ffffff{/if};">{$tab.bubble_text}</span>{/if}
                {if $tab.url}
                    </a>
                {/if}
            </span>
        </span>
        <div class="rb_buttons" style="left:{if $menu.tab_item_width}{$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if};right:{if $menu.tab_item_width}{$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if};">
            <span class="rb_tab_delete" title="{l s='Delete tab' mod='rbthememenu'}">{l s='Delete' mod='rbthememenu'}</span>  
            <span class="rb_duplicate" title="{l s='Duplicate tab' mod='rbthememenu'}">{l s='Duplicate' mod='rbthememenu'}</span>                      
            <span class="rb_tab_edit" title="{l s='Edit tab' mod='rbthememenu'}">{l s='Edit' mod='rbthememenu'}</span>                
            <span class="rb_menu_toggle rb_menu_toggle_arrow" title="{l s='Close' mod='rbthememenu'}">{l s='Close' mod='rbthememenu'}</span> 
            <div class="rb_add_column btn btn-default" title="{l s='Add column' mod='rbthememenu'}" data-id-menu="{$tab.id_menu|intval}" data-id-tab="{$tab.id_tab|intval}" >{l s='Add column' mod='rbthememenu'}</div> 
        </div> 
    </div>
    <ul class="rb_columns_ul" style="width:calc(100% - {if $menu.tab_item_width}{$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if}); left: {if $menu.tab_item_width}{$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if};right: {if $menu.tab_item_width}{$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if};">
        {if $tab.columns}                            
            {foreach from=$tab.columns item='column'}
                <li data-id-column="{$column.id_column|intval}" class="rb_columns_li item{$column.id_column|intval} column_size_{$column.column_size|intval} {if $column.is_breaker}rb_breaker{/if}" data-obj="column">
                    {hook h='displayRbItemColumn' column=$column}
                </li>
            {/foreach}                            
        {/if}  
    </ul>
{if $have_li}
</li>
{/if}
