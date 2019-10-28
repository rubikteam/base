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
{extends file='page.tpl'}

{block name="page_content"}
	<section id="main">
		<div id="rb-wishlist">
			<h2>{l s='New Wishlist' mod='rbthemefunction'}</h2>

			<div class="rb-new-wishlist">
				<form class="rb-add-new-wishlist" method="POST" action="" name="new_wishlist">
					<div class="form-group">
						<label for="rb_wishlist_name">{l s='Name' mod='rbthemefunction'}</label>
						<input type="text" name="rb_wishlist_name" required="" class="form-control" id="rb_wishlist_name" placeholder="{l s='Enter name of new wishlist' mod='rbthemefunction'}">
					</div>
					<div class="form-group has-success">
						<div class="form-control-feedback"></div>			 
					</div>
					<div class="form-group has-danger">		 
					  <div class="form-control-feedback"></div>		 
					</div>
					<button type="submit" class="btn btn-primary rb-save-wishlist" name="rb_add_wishlist">
						<span class="rb-save-wishlist-name">
							{l s='Save' mod='rbthemefunction'}
						</span>
					</button>
				</form>
			</div>

			<div class="rb-list-wishlist">
				<table class="table table-striped">
					<thead class="wishlist-table-head">
						<tr>
							<th>{l s='Name' mod='rbthemefunction'}</th>
							<th>{l s='Quantity' mod='rbthemefunction'}</th>
							<th>{l s='Viewed' mod='rbthemefunction'}</th>
							<th class="wishlist-datecreate-head">{l s='Created' mod='rbthemefunction'}</th>
							<th>{l s='View Link' mod='rbthemefunction'}</th>
							<th>{l s='Default' mod='rbthemefunction'}</th>
							<th>{l s='Delete' mod='rbthemefunction'}</th>
						</tr>
					</thead>

					<tbody>
						{if $wishlists}
							{foreach from=$wishlists item=wishlists_item name=for_wishlists}
								<tr>					 
									<td>
										{include file='module:rbthemefunction/views/templates/rb-ajax-loading.tpl'}
										<a href="#" class="view-wishlist-product" data-name-wishlist="{$wishlists_item.name}" data-id-wishlist="{$wishlists_item.id_rbthemefunction_wishlist}"><i class="material-icons">&#xE8EF;</i>{$wishlists_item.name}</a>
									</td>

									<td class="wishlist-numberproduct wishlist-numberproduct-{$wishlists_item.id_rbthemefunction_wishlist}">
										{$wishlists_item.number_product|intval}
									</td>

									<td>{$wishlists_item.counter|intval}</td>

									<td class="wishlist-datecreate">{$wishlists_item.date_add}</td>

									<td>
										<a class="view-wishlist" data-token="{$wishlists_item.token}" target="_blank" href="{$view_wishlist_url}?token={$wishlists_item.token}" title="{l s='View' mod='rbthemefunction'}">{l s='View' mod='rbthemefunction'}</a>
									</td>

									<td>
										<label class="form-check-label">
											<input class="default-wishlist form-check-input" data-id-wishlist="{$wishlists_item.id_rbthemefunction_wishlist}" type="checkbox" {if $wishlists_item.default == 1}checked="checked"{/if}>
										</label>
									
									</td>

									<td>
										<a class="delete-wishlist" data-id-wishlist="{$wishlists_item.id_rbthemefunction_wishlist}" href="#" title="{l s='Delete' mod='rbthemefunction'}"><i class="material-icons">&#xE872;</i></a>
									</td>
								</tr>
							{/foreach}
						{/if}
					  </tbody>
				</table>
			</div>

			<div class="send-wishlist">
				{include file='module:rbthemefunction/views/templates/rb-ajax-loading.tpl'}
				<form class="form-send-wishlist" action="#" method="POST">
					<div class="form-group">
						<div class="input-group">
							<input class="form-control" required="" type="email" name="email" id="email_3" value="" size="40" placeholder="{l s='Email address' mod='rbthemefunction'}">

							<span class="input-group-btn">
								<i class="material-icons">email</i>
							</span>
						</div>
					</div>
					<button type="submit" class="btn btn-primary rb-send-wishlist">
						<i class="material-icons">&#xE163;</i>
						{l s='Send My Wishlist' mod='rbthemefunction'}
					</button>
				</form>
			</div>

			<section id="products">
				<div class="rb-wishlist-product products row">
				
				</div>
			</section>

			<ul class="footer_links">
				<li class="pull-xs-left">
					<a class="btn btn-outline" href="{$link->getPageLink('my-account', true)|escape:'html'}">
						<i class="material-icons">&#xE317;</i>
						{l s='Back to Your Account' mod='rbthemefunction'}
					</a>
				</li>

				<li class="pull-xs-right">
					<a class="btn btn-outline" href="{$urls.base_url}">
						<i class="material-icons">&#xE88A;</i>
						{l s='Home' mod='rbthemefunction'}
					</a>
				</li>
			</ul>
		</div>
	</section>
{/block}