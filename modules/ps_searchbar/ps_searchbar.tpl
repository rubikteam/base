{**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div class="row">
	<div class="col-md-12">
		<div class="rb-search-name search-widget">
			<label>{l s='Search By Name' mod='apsearch'}</label>
			
			<div class="rb-search-widget">
				<form method="get" action="{$search_controller_url}">
					<input type="text" name="s" aria-label="{l s='Search' mod='apsearch'}" class="rb-search" autocomplete="off">
					<button type="submit">
						<i class="material-icons search"></i>
						<span class="hidden-xl-down">{l s='Search' mod='apsearch'}</span>
					</button>
				</form>
			</div>

			<div class="cssload-container rb-ajax-loading">
				<div class="cssload-double-torus"></div>
			</div>
			<div class="resuilt-search">
				<div class="rb-resuilt row"></div>
			</div>

	        <p class="rb-resuilt-error"></p>
		</div>
	</div>
</div>
