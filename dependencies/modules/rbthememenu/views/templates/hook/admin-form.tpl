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
<script type="text/javascript">
    var rb_img_dir ="{$rb_img_dir|escape:'quotes':'UTF-8'}";
    var rbBaseAdminUrl = "{$rbBaseAdminUrl|escape:'quotes':'UTF-8'}";
    var rbCloseTxt = "{l s='Close' mod='rbthememenu'}";
    var rbOpenTxt = "{l s='Open' mod='rbthememenu'}";
    var rbDeleteTxt = "{l s='Delete' mod='rbthememenu'}";
    var rbEditTxt = "{l s='Edit' mod='rbthememenu'}";
    var rbDeleteTitleTxt = "{l s='Delete this item' mod='rbthememenu'}";
    var rbAddMenuTxt = "{l s='Add new menu' mod='rbthememenu'}";
    var rbEditMenuTxt = "{l s='Edit menu' mod='rbthememenu'}";
    var rbAddColumnTxt = "{l s='Add new column' mod='rbthememenu'}";
    var rbEditColumnTxt = "{l s='Edit column' mod='rbthememenu'}";
    var rbDeleteColumnTxt = "{l s='Delete this column' mod='rbthememenu'}";
    var rbDeleteBlockTxt = "{l s='Delete this block' mod='rbthememenu'}";
    var rbEditBlockTxt = "{l s='Edit this block' mod='rbthememenu'}";
    var rbAddBlockTxt = "{l s='Add new block' mod='rbthememenu'}";
    var rbDuplicateTxt = "{l s='Duplicate' mod='rbthememenu'}";
    var rbDuplicateMenuTxt = "{l s='Duplicate this menu' mod='rbthememenu'}";
    var rbDuplicateColumnTxt = "{l s='Duplicate this column' mod='rbthememenu'}";
    var rbDuplicateBlockTxt = "{l s='Duplicate this block' mod='rbthememenu'}";
    var rb_invalid_file = "{l s='Image is invalid' mod='rbthememenu'}";
    var rbLabelDelete = "{l s='Delete' mod='rbthememenu'}";
</script>

<div class="rb_megamenu rb_view_mode_tab {if $rb_backend_layout=='rtl'}rb-dir-rtl backend-layout-rtl{else}rb-dir-ltr backend-layout-ltr{/if} {if $multiLayout}rb_multi_layout{else}rb_single_layout{/if}">
    <div class="rb_menus">
        {if $menus}
            <ul class="rb_menus_ul">
                {foreach from=$menus item='menu'}
                    <li class="rb_menus_li item{$menu.id_menu|intval} {if !$menu.enabled}rb_disabled{/if}" data-id-menu="{$menu.id_menu|intval}" data-obj="menu">
                        {hook h='displayRbItemMenu' menu=$menu}
                    </li>
                {/foreach}
            </ul>
        {/if}        
        <div class="rb_useful_buttons">
            <div class="rb_add_menu btn btn-default">{l s='Add menu' mod='rbthememenu'}</div>
            {if $multiLayout}
                <div class="rb_layout_mode">                
                    <div data-title="&#xE236;" class="rb_layout_ltr rb_change_mode {if $rb_backend_layout!='rtl'}active{/if}">{l s='LTR' mod='rbthememenu'}</div>
                    <div data-title="&#xE237;" class="rb_layout_rlt rb_change_mode {if $rb_backend_layout=='rtl'}active{/if}">{l s='RTL' mod='rbthememenu'}</div>
                </div>
            {/if}
            <div class="rb_config_button btn btn-default" data-title="&#xE8B8;">{l s='Settings' mod='rbthememenu'}</div>
        </div>
    </div>
    <div class="rb_loading_icon"><img src="{$rb_img_dir|escape:'html':'UTF-8'}ajax-loader.gif" /></div>
    <!-- popup forms -->
    <div class="rb_forms hidden rb_popup_overlay">
        <div class="rb_menu_form hidden rb_pop_up">
            <div class="rb_close">{l s='Close' mod='rbthememenu'}</div>
            <div class="rb_form"></div>
        </div>
        <div class="rb_menu_form_new hidden">{$menuForm nofilter}</div>
        <div class="rb_tab_form_new hidden">{$tabForm nofilter}</div>
        <div class="rb_column_form_new hidden">{$columnForm nofilter}</div>
        <div class="rb_block_form_new hidden">{$blockForm nofilter}</div>
        <div class="rb_icon_form_new hidden">{$iconForm nofilter}</div>
    </div>
    <div class="rb_popup_overlay hidden">
        <div class="rb_config_form rb_pop_up">
            <div class="rb_close" >{l s='Close' mod='rbthememenu'}</div>
            <div class="rb_config_form_content"><div class="rb_close"></div>{$configForm nofilter}</div>
        </div>
    </div>
    <div class="rb_export_form hidden rb_popup_overlay">
        <div class="rb_close"></div>
        <div class="rb_export rb_pop_up hidden">
            <div data-title="&#xE14C;" class="rb_close">{l s='Close' mod='rbthememenu'}</div>
            <div class="rb_export_form_content">            
                <div class="rb_export_option">
                    <div class="export_title">{l s='Export menu content' mod='rbthememenu'}</div>
                    <a class="btn btn-default rb_export_menu" href="{$rbBaseAdminUrl nofilter}&exportMenu=1" target="_blank">
                        <i class="fa fa-download" data-title="&#xE864;"></i>{l s='Export menu' mod='rbthememenu'}
                    </a>
                    <p class="rb_export_option_note">{l s='Export all menu data including images, text and configuration' mod='rbthememenu'}</p>
                </div>                       
                <div class="rb_import_option">
                    <div class="export_title">{l s='Import menu data' mod='rbthememenu'}</div>
                    <form action="{$rbBaseAdminUrl nofilter}" method="post" enctype="multipart/form-data" class="rb_import_option_form">
                        <div class="rb_import_option_updata">
                            <label for="sliderdata">{l s='Menu data package' mod='rbthememenu'}</label>
                            <input id="image" type="file" name="sliderdata" id="sliderdata" />
                        </div>
                        <div class="rb_import_option_clean">
                            <input type="checkbox" value="1" id="importoverride" checked="checked" name="importoverride" />
                            <label for="importoverride">{l s='Clear all menus before importing' mod='rbthememenu'}</label>
                        </div>
                        <div class="rb_import_option_button">
                            <input type="hidden" name="importMenu" value="1" />
                            <div class="rb_import_menu_loading"><img src="{$rb_img_dir nofilter}loader.gif" />{l s='Importing data' mod='rbthememenu'}</div>
                            <div class="rb_import_menu_submit">
                                <i class="fa fa-compress" data-title="&#xE0C3;"></i>
                                <input type="submit" value="{l s='Import menu' mod='rbthememenu'}" class="btn btn-default rb_import_menu"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>