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
<!DOCTYPE html>
<html class="no-js" {$full_cldr_language_code}>
 	<head>
  		<meta charset="utf-8" />
  		<link rel="icon" type="image/x-icon" href="{$img_dir}favicon.ico"/>
  		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
  		<title>{l s='Home Live Edit' mod='rbthemedream'}</title>
	  	<script type="text/javascript">
	    var iso_user = '{$iso_user|@addcslashes:'\''}';
	    var full_language_code = '{$full_language_code|@addcslashes:'\''}';
	    var full_cldr_language_code = '{$full_cldr_language_code|@addcslashes:'\''}';
	    var country_iso_code = '{$country_iso_code|@addcslashes:'\''}';
	    var _PS_VERSION_ = '{$smarty.const._PS_VERSION_|@addcslashes:'\''}';
	    var roundMode = {$round_mode|intval};
	    var token = '{$token|addslashes}';
	    var youEditFieldFor = 'a';
	    var baseAdminDir = '{$baseDir|addslashes}';
	    var from_msg ='a';
	    var token_admin_orders = '{getAdminToken tab='AdminOrders'}';
	    var token_admin_customers = '{getAdminToken tab='AdminCustomers'}';
	    var token_admin_customer_threads = '{getAdminToken tab='AdminCustomerThreads'}';
	    var currentIndex = '{$currentIndex|@addcslashes:'\''}';
	    var employee_token = '{getAdminToken tab='AdminEmployees'}';
	    var default_language = '{$default_language|intval}';
	    var admin_modules_link = '{$link->getAdminLink("AdminModulesSf", true, ['route' => "admin_module_catalog_post"])|addslashes}';
	    var tab_modules_list = '{if isset($tab_modules_list) && $tab_modules_list}{$tab_modules_list|addslashes}{/if}';
	  	</script>
	  	{if isset($css_files)}
		    {foreach from=$css_files key=css_uri item=media}
		      <link href="{$css_uri|escape:'html':'UTF-8'}" rel="stylesheet" type="text/css"/>
		    {/foreach}
	  	{/if}
	</head>

	<body class="rb-live-active">
		<div id="rb-live-wrapper">
			<div id="rb-preview">
				<div id="rb-loading">
					<div class="rb-loader-wrapper">
						<div class="rb-loader">
							<div id="loadFacebookG">
								<div id="blockG_1" class="facebook_blockG"></div>
								<div id="blockG_2" class="facebook_blockG"></div>
								<div id="blockG_3" class="facebook_blockG"></div>
							</div>
						</div>
						<div class="rb-loading-title">{l s='Loading' mod='rbthemedream'}</div>
					</div>
				</div>
				<div id="rb-preview-responsive-wrapper" class="rb-device-desktop rb-device-rotate-portrait">
					
				</div>
			</div>
			<div id="rb-panel"></div>
		</div>

		<script type="text/template" id="dd-rb-panel">
			{include file='./include/rb-panel.tpl'}
		</script>

		<script type="text/template" id="dd-rb-panel-menu-item">
			{include file='./include/rb-panel-menu-item.tpl'}
		</script>

		<script type="text/template" id="dd-rb-panel-header">
			{include file='./include/rb-panel-header.tpl'}
		</script>

		<script type="text/template" id="dd-rb-panel-footer-content">
			{include file='./include/rb-panel-footer-content.tpl'}
		</script>

		<script type="text/template" id="dd-rb-mode-switcher-content">
			{include file='./include/rb-mode-switcher-content.tpl'}
		</script>

		<script type="text/template" id="dd-live-content">
			{include file='./include/rb-live-content.tpl'}
		</script>

		<script type="text/template" id="dd-rb-panel-schemes-typography">
			{include file='./include/rb-panel-schemes-typography.tpl'}
		</script>

		<script type="text/template" id="dd-rb-panel-schemes-color">
			{include file='./include/rb-panel-schemes-color.tpl'}
		</script>

		<script type="text/template" id="dd-rb-panel-schemes-disabled">
			{include file='./include/rb-panel-schemes-disabled.tpl'}
		</script>

		<script type="text/template" id="dd-rb-panel-scheme-color-item">
			{include file='./include/rb-panel-scheme-color-item.tpl'}
		</script>

		<script type="text/template" id="dd-rb-panel-scheme-typography-item">
			{include file='./include/rb-panel-scheme-typography-item.tpl'}
		</script>

		<script type="text/template" id="dd-rb-control-responsive-switchers">
			{include file='./include/rb-control-responsive-switchers.tpl'}
		</script>

		<script type="text/template" id="dd-rb-panel-elements-category">
			{include file='./include/rb-panel-elements-category.tpl'}
		</script>

		<script type="text/template" id="dd-rb-panel-element-languageselector">
			{include file='./include/rb-panel-element-languageselector.tpl'}
		</script>

		<script type="text/template" id="dd-rb-panel-element-search">
			{include file='./include/rb-panel-element-search.tpl'}
		</script>

		<script type="text/template" id="dd-rb-element-library-element">
			{include file='./include/rb-element-library-element.tpl'}
		</script>

		<script type="text/template" id="dd-rb-repeater-row">
			{include file='./include/rb-repeater-row.tpl'}
		</script>

		<script type="text/template" id="dd-rb-template-library-header">
			{include file='./include/rb-template-library-header.tpl'}
		</script>

		<script type="text/template" id="dd-rb-template-library-header-logo">
			<i class="eicon-rb-square"></i>
			<span>{l s='Library' mod='rbthemedream'}</span>
		</script>

		<script type="text/template" id="dd-rb-template-library-header-save">
			<i class="eicon-save" title="{l s='Save Template' mod='rbthemedream'}"></i>
		</script>

		<script type="text/template" id="dd-rb-template-library-header-load">
			<i class="icon-upload" title="{l s='Load Template' mod='rbthemedream'}"></i>
		</script>

		<script type="text/template" id="dd-rb-template-library-header-menu">
			<div id="rb-template-library-menu-my-templates" class="rb-template-library-menu-item" data-template-source="local">
				{l s='List Templates' mod='rbthemedream'}
			</div>
		</script>

		<script type="text/template" id="dd-rb-template-library-header-preview">
			{include file='./include/rb-template-library-header-preview.tpl'}
		</script>

		<script type="text/template" id="dd-rb-template-library-header-back">
			<i class="eicon-"></i>
			<span>{l s='Back To library' mod='rbthemedream'}</span>
		</script>

		<script type="text/template" id="dd-rb-template-library-loading">
			{include file='./include/rb-template-library-loading.tpl'}
		</script>

		<script type="text/template" id="dd-rb-template-library-templates">
			{include file='./include/rb-template-library-templates.tpl'}
		</script>

		<script type="text/template" id="dd-rb-template-library-template-local">
			{include file='./include/rb-template-library-template-local.tpl'}
		</script>

		<script type="text/template" id="dd-rb-template-library-save-template">
			{include file='./include/rb-template-library-save-template.tpl'}
		</script>

		<script type="text/template" id="dd-rb-template-library-load-template">
			{include file='./include/rb-template-library-load-template.tpl'}
		</script>

		<script type="text/template" id="dd-rb-template-library-templates-empty">
			{include file='./include/rb-template-library-templates-empty.tpl'}
		</script>

		<script type="text/template" id="dd-rb-template-library-preview">
			<iframe></iframe>
		</script>

		{include file='./rb-control.tpl'}
		{include file='./rb-widget.tpl'}

		<script type="text/html" id="dd-rb-element-column-content">
			{include file='./rb-element-column-content.tpl'}
		</script>

		<script type="text/html" id="dd-rb-element-section-content">
			{include file='./rb-element-section-content.tpl'}
		</script>

		<script type="text/template" id="dd-rb-panel-elements">
			{include file='./include/rb-panel-elements.tpl'}
		</script>

		<script type="text/html" id="dd-rb-element-column-content">
			{include file='./include/rb-column.tpl'}
		</script>

		<script type="text/html" id="dd-rb-element-section-content">
			{include file='./include/rb-section.tpl'}
		</script>

		<script type="text/template" id="dd-rb-empty-preview">
			<div class="rb-first-add">
				<div class="rb-icon eicon-plus"></div>
			</div>
		</script>

		<script type="text/template" id="dd-rb-preview">
			{include file='./include/rb-preview.tpl'}
		</script>

		{if isset($js_def_vars) && is_array($js_def_vars) && $js_def_vars|@count}
			<script type="text/javascript">
				{foreach from=$js_def_vars key=k item=def}
			   		var {$k} = {$def|json_encode nofilter};
			    {/foreach}
			</script>
		{/if}

		{if isset($js_files) && count($js_files)}
		  {include file=$smarty.const._PS_ALL_THEMES_DIR_|cat:"javascript.tpl"}
		{/if}
	</body>
</html>