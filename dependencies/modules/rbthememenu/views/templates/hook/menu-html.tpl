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

{if isset($menus) && $menus}
    <ul class="rb_menus_ul {if isset($rb_config.RBTHEMEMENU_CLICK_TEXT_SHOW_SUB) && $rb_config.RBTHEMEMENU_CLICK_TEXT_SHOW_SUB} clicktext_show_submenu{/if} {if isset($rb_config.RBTHEMEMENU_SHOW_ICON_VERTICAL)&& !$rb_config.RBTHEMEMENU_SHOW_ICON_VERTICAL} hide_icon_vertical{/if}" >
        <li class="close_menu">
            <div class="pull-left">
                <span class="rb_menus_back">
                    <i class="icon-bar"></i>
                    <i class="icon-bar"></i>
                    <i class="icon-bar"></i>
                </span>
                {l s='Menu' mod='rbthememenu'}
            </div>
            <div class="pull-right">
                <span class="rb_menus_back_icon"></span>
                {l s='Back' mod='rbthememenu'}
            </div>
        </li>
        {foreach from=$menus item='menu'}
            <li  class="rb_menus_li{if $menu.enabled_vertical} rb_menus_li_tab{if $menu.menu_ver_hidden_border} rb_no_border{/if}{if $menu.menu_ver_alway_show} menu_ver_alway_show_sub{/if}{/if}{if $menu.custom_class} {$menu.custom_class|escape:'html':'UTF-8'}{/if}{if $menu.sub_menu_type} rb_sub_align_{strtolower($menu.sub_menu_type)|escape:'html':'UTF-8'}{/if}{if $menu.columns} rb_has_sub{/if}{if $menu.display_tabs_in_full_width && $menu.enabled_vertical} display_tabs_in_full_width{/if}" {if $menu.enabled_vertical}style="width: {if $menu.menu_item_width}{$menu.menu_item_width|escape:'html':'UTF-8'}{else}230px{/if}"{/if}>
               <a {if isset($menu.menu_open_new_tab) && $menu.menu_open_new_tab == 1} target="_blank"{/if} href="{$menu.menu_link|escape:'html':'UTF-8'}" style="{if $menu.enabled_vertical}{if isset($menu.menu_ver_text_color) && $menu.menu_ver_text_color}color:{$menu.menu_ver_text_color};{/if}{if isset($menu.menu_ver_background_color) && $menu.menu_ver_background_color}background-color:{$menu.menu_ver_background_color};{/if}{/if}{if Configuration::get('RBTHEMEMENU_HEADING_FONT_SIZE')}font-size:{Configuration::get('RBTHEMEMENU_HEADING_FONT_SIZE')|intval}px;{/if}">
                    <span class="rb_menu_content_title">
                        {if $menu.menu_img_link}
                            <img src="{$menu.menu_img_link|escape:'html':'UTF-8'}" title="" alt="" width="20" />
                        {elseif $menu.menu_icon}
                            <i class="fa {$menu.menu_icon|escape:'html':'UTF-8'}"></i>
                        {/if}
                        {$menu.title|escape:'html':'UTF-8'}
                        {if $menu.columns}<span class="rb_arrow"></span>{/if}
                        {if $menu.bubble_text}<span class="rb_bubble_text" style="background: {if $menu.bubble_background_color}{$menu.bubble_background_color|escape:'html':'UTF-8'}{else}#FC4444{/if}; color: {if $menu.bubble_text_color|escape:'html':'UTF-8'}{$menu.bubble_text_color}{else}#ffffff{/if};">{$menu.bubble_text}</span>{/if}
                    </span>
                </a>
                {if $menu.enabled_vertical}
                    {if $menu.tabs}
                        <span class="arrow closed"></span>
                    {/if}
                {/if}
                {if $menu.enabled_vertical}
                    {if $menu.tabs}
                        <ul class="rb_columns_ul rb_columns_ul_tab {if $menu.menu_ver_alway_show} rb_columns_ul_tab_content{/if}" style="width:{$menu.sub_menu_max_width|escape:'html':'UTF-8'};{if Configuration::get('RBTHEMEMENU_TEXT_FONT_SIZE')} font-size:{Configuration::get('RBTHEMEMENU_TEXT_FONT_SIZE')|intval}px;{/if}">
                            {foreach from=$menu.tabs key='key' item='tab'}
                                <li class="rb_tabs_li{if $tab.columns} {if $key == 0}open {/if}rb_tabs_has_content{/if}{if !$tab.tab_sub_content_pos} rb_tab_content_hoz{/if}">
                                    <div class="rb_tab_li_content closed" style="width: {if $menu.tab_item_width}{$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if}">
                                        <span class="rb_tab_name rb_tab_toggle{if $tab.columns} rb_tab_has_child{/if}">
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
                                    </div>
                                    {if $tab.columns}
                                        <ul class="rb_columns_contents_ul " style="{if $tab.tab_sub_width}width: {$tab.tab_sub_width|escape:'html':'UTF-8'};{else}{if $menu.tab_item_width} width:calc(100% - {$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if} + 2px);{/if} left: {if $menu.tab_item_width}{$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if};right: {if $menu.tab_item_width}{$menu.tab_item_width|escape:'html':'UTF-8'}{else}230px{/if};{if $tab.background_image} background-image:url('{$tab.background_image|escape:'html':'UTF-8'}');background-position:{$tab.position_background|escape:'html':'UTF-8'}{/if}">
                                            {foreach from=$tab.columns item='column'}
                                                <li class="rb_columns_li column_size_{$column.column_size|intval} {if $column.is_breaker}rb_breaker{/if} {if $column.blocks}rb_has_sub{/if}">
                                                    {if isset($column.blocks) && $column.blocks}
                                                        <ul class="rb_blocks_ul">
                                                            {foreach from=$column.blocks item='block'}
                                                                <li data-id-block="{$block.id_block|intval}" class="rb_blocks_li">
                                                                    {hook h='displayBlock' block=$block}
                                                                </li>
                                                            {/foreach}
                                                        </ul>
                                                    {/if}
                                                </li>
                                            {/foreach}
                                        </ul>
                                    {/if}
                                </li>
                            {/foreach} 
                        </ul>
                    {/if}
                {else}
                    {if $menu.columns}<span class="arrow closed"></span>{/if}
                    {if $menu.columns}
                            <ul class="rb_columns_ul" style=" width:{$menu.sub_menu_max_width|escape:'html':'UTF-8'};{if Configuration::get('RBTHEMEMENU_TEXT_FONT_SIZE')} font-size:{Configuration::get('RBTHEMEMENU_TEXT_FONT_SIZE')|intval}px;{/if}{if !$menu.enabled_vertical && $menu.background_image} background-image:url('{$menu.background_image|escape:'html':'UTF-8'}');background-position:{$menu.position_background|escape:'html':'UTF-8'}{/if}">
                                {foreach from=$menu.columns item='column'}
                                    <li class="rb_columns_li column_size_{$column.column_size|intval} {if $column.is_breaker}rb_breaker{/if} {if $column.blocks}rb_has_sub{/if}">
                                        {if isset($column.blocks) && $column.blocks}
                                            <ul class="rb_blocks_ul">
                                                {foreach from=$column.blocks item='block'}
                                                    <li data-id-block="{$block.id_block|intval}" class="rb_blocks_li">
                                                        {hook h='displayBlock' block=$block}
                                                    </li>
                                                {/foreach}
                                            </ul>
                                        {/if}
                                    </li>
                                {/foreach}
                            </ul>
                    {/if}
                {/if}     
            </li>
        {/foreach}
    </ul>
    {hook h='displayCustomMenu'}
{/if}
