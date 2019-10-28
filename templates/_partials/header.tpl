{**
 * 2007-2019 PrestaShop and Contributors
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
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{if Configuration::get('RBTHEMEDREAM_HEADER') == 2}
  	{include file='_partials/header/header-2.tpl'}
{else if Configuration::get('RBTHEMEDREAM_HEADER') == 3}
	{include file='_partials/header/header-3.tpl'}
{else if Configuration::get('RBTHEMEDREAM_HEADER') == 4}
	{include file='_partials/header/header-4.tpl'}
{else if Configuration::get('RBTHEMEDREAM_HEADER') == 5}
	{include file='_partials/header/header-5.tpl'}
{else if Configuration::get('RBTHEMEDREAM_HEADER') == 6}
	{include file='_partials/header/header-6.tpl'}
{else if Configuration::get('RBTHEMEDREAM_HEADER') == 7}
	{include file='_partials/header/header-7.tpl'}
{else}
  	{include file='_partials/header/header-1.tpl'}
{/if}