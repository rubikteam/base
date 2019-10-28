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
{if isset($block) && $block && $block.enabled}    
    <div class="rb_block rb_block_type_{strtolower($block.block_type)|escape:'html':'UTF-8'} {if !$block.display_title}rb_hide_title{/if}">
        <h4 {if Configuration::get('RBTHEMEDREAM_TEXTTITLE_FONT_SIZE')} style="font-size:{Configuration::get('RBTHEMEDREAM_TEXTTITLE_FONT_SIZE')|intval}px"{/if}>{if $block.title_link}<a href="{$block.title_link|escape:'html':'UTF-8'}" {if Configuration::get('RBTHEMEDREAM_TEXTTITLE_FONT_SIZE')} style="font-size:{Configuration::get('RBTHEMEDREAM_TEXTTITLE_FONT_SIZE')|intval}px"{/if}>{/if}{$block.title|escape:'html':'UTF-8'}{if $block.title_link}</a>{/if}</h4>
        <div class="rb_block_content">        
            {if $block.block_type=='CATEGORY'}
                {if isset($block.categoriesHtml)}{$block.categoriesHtml nofilter}{/if}
            {elseif $block.block_type=='MNFT'}
                {if isset($block.manufacturers) && $block.manufacturers}
                    <ul {if isset($block.display_mnu_img) && $block.display_mnu_img}class="rb_mnu_display_img"{/if}>
                        {foreach from=$block.manufacturers item='manufacturer'}
                            <li class="{if isset($block.display_mnu_img) && $block.display_mnu_img}item_has_img {if isset($block.display_mnu_inline) && $block.display_mnu_inline}item_inline_{$block.display_mnu_inline|escape:'html':'UTF-8'}{/if}{/if}">
                                <a href="{$manufacturer.link|escape:'html':'UTF-8'}">
                                    {if isset($block.display_mnu_img) && $block.display_mnu_img}
                                        <span class="rb_item_img">
                                            <img src="{$manufacturer.image|escape:'html':'UTF-8'}" alt="" title="{$manufacturer.label|escape:'html':'UTF-8'}"/>
                                        </span>
                                        {if isset($block.display_mnu_name) && $block.display_mnu_name}
                                            <span class="rb_item_name">{$manufacturer.label|escape:'html':'UTF-8'}</span>
                                        {/if}
                                    {else}
                                        {$manufacturer.label|escape:'html':'UTF-8'}
                                    {/if}
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                {/if}
            {elseif $block.block_type=='MNSP'}
                {if isset($block.suppliers) && $block.suppliers}
                    <ul {if isset($block.display_suppliers_img) && $block.display_suppliers_img}class="rb_mnu_display_img"{/if}>
                        {foreach from=$block.suppliers item='supplier'}
                            <li class="{if isset($block.display_suppliers_img) && $block.display_suppliers_img}{if isset($block.display_suppliers_inline) && $block.display_suppliers_inline}item_inline_{$block.display_suppliers_inline|escape:'html':'UTF-8'}{/if} item_has_img{/if}">
                                <a href="{$supplier.link|escape:'html':'UTF-8'}">
                                    {if isset($block.display_suppliers_img) && $block.display_suppliers_img}
                                        <span class="rb_item_img">
                                            <img src="{$supplier.image|escape:'html':'UTF-8'}" alt="" title="{$supplier.label|escape:'html':'UTF-8'}" />
                                        </span>
                                        {if isset($block.display_suppliers_name) && $block.display_suppliers_name}
                                            <span class="rb_item_name">{$supplier.label|escape:'html':'UTF-8'}</span>
                                        {/if}
                                    {else}
                                        {$supplier.label|escape:'html':'UTF-8'}
                                    {/if}
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                {/if}
            {elseif $block.block_type=='CMS'}
                {if isset($block.cmss) && $block.cmss}
                    <ul>
                        {foreach from=$block.cmss item='cms'}
                            <li><a href="{$cms.link|escape:'html':'UTF-8'}">{$cms.label|escape:'html':'UTF-8'}</a></li>
                        {/foreach}
                    </ul>
                {/if}
            {elseif $block.block_type=='IMAGE'}
                {if isset($block.image) && $block.image}{if $block.image_link}<a href="{$block.image_link|escape:'html':'UTF-8'}">{/if}
                    <span class="rb_img_content">
                        <img src="{$block.image|escape:'html':'UTF-8'}" alt="{$block.title|escape:'html':'UTF-8'}" />
                    </span>
                {if $block.image_link}</a>{/if}{/if}
            {elseif $block.block_type=='PRODUCT'}
                {if isset($block.productsHtml)}{$block.productsHtml nofilter}{/if}
            {else}
                {$block.content nofilter}
            {/if}
        </div>
    </div>
    <div class="clearfix"></div>
{/if}