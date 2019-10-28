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
<a class="rb-view-button btn btn-primary" href="{$product.url}" title="{l s='View more' d='Shop.Theme.Catalog'}" rel="nofollow">
	<div class="hover_fly_btn_inner">
		<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 561 561" style="enable-background:new 0 0 561 561;" xml:space="preserve"><g><g id="visibility"><path d="M280.5,89.25C153,89.25,43.35,168.3,0,280.5c43.35,112.2,153,191.25,280.5,191.25S517.65,392.7,561,280.5C517.65,168.3,408,89.25,280.5,89.25z M280.5,408C209.1,408,153,351.9,153,280.5c0-71.4,56.1-127.5,127.5-127.5c71.4,0,127.5,56.1,127.5,127.5C408,351.9,351.9,408,280.5,408z M280.5,204c-43.35,0-76.5,33.15-76.5,76.5c0,43.35,33.15,76.5,76.5,76.5c43.35,0,76.5-33.15,76.5-76.5C357,237.15,323.85,204,280.5,204z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
		<span>{if isset($is_select_options) && $is_select_options}{l s='Select options' d='Shop.Theme.Catalog'}{else}{l s='View more' d='Shop.Theme.Catalog'}{/if}</span>
	</div>
</a>