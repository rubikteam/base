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
    <li data-id-column="{$column.id_column|intval}" class="rb_columns_li item{$column.id_column|intval} column_size_{$column.column_size|intval} {if $column.is_breaker}rb_breaker{/if}" data-obj="column">
{/if}

<div class="rb_buttons">
    <span class="rb_column_delete" title="{l s='Delete column' mod='rbthememenu'}">{l s='Delete' mod='rbthememenu'}</span>
    <span class="rb_duplicate" title="{l s='Duplicate column' mod='rbthememenu'}">{l s='Duplicate' mod='rbthememenu'}</span>
    <span class="rb_column_edit" title="{l s='Edit column' mod='rbthememenu'}">{l s='Edit' mod='rbthememenu'}</span>
    <div class="rb_add_block btn btn-default" data-id-column="{$column.id_column|intval}" title="{l s='Add block' mod='rbthememenu'}">{l s='Add block' mod='rbthememenu'}</div>    
</div>
<ul class="rb_blocks_ul">
    {if isset($column.blocks) && $column.blocks}                                                    
        {foreach from=$column.blocks item='block'}
            <li data-id-block="{$block.id_block|intval}" class="rb_blocks_li {if !$block.enabled}rb_disabled{/if} item{$block.id_block|intval}" data-obj="block">
                {hook h='displayRbItemBlock' block=$block}
            </li>
        {/foreach}                                                    
    {/if}
</ul>

{if $have_li}
    </li>
{/if}