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
    <li data-id-block="{$block.id_block|intval}" class="rb_blocks_li {if !$block.enabled}rb_disabled{/if} item{$block.id_block|intval}" data-obj="block">
{/if}

<div class="rb_buttons">
    <span class="rb_block_delete" title="{l s='Delete block' mod='rbthememenu'}">{l s='Delete' mod='rbthememenu'}</span>
    <span class="rb_duplicate" title="{l s='Duplicate block' mod='rbthememenu'}">{l s='Duplicate' mod='rbthememenu'}</span>
    <span class="rb_block_edit" title="{l s='Edit block' mod='rbthememenu'}">{l s='Edit' mod='rbthememenu'}</span>
</div>

<div class="rb_block_wrapper">
    {hook h='displayBlock' block=$block}
</div>

{if $have_li}
    </li>
{/if}