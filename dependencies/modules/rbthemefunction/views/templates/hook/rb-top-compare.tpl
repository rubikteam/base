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
<!-- TPL LOGIN -->
{if isset($top) && $top == 'login'}
	<div class="rb-login">
		<a
			href="#"
			title="{l s='Login' mod='rbthemefunction'}"
			class="align-items-center"
		>
			<i class="material-icons">&#xE7FF;</i>
		</a>

		{if $rb_login == 1}
			<div class="rb-dropdown rb-login-form">
				<a class="rb-logout" href="{$logout_url|escape:'html':'UTF-8'}" rel="nofollow">
		        	<i class="material-icons">&#xE879;</i>      
		        	<span class="hidden-sm-down">{l s='Sign out' mod='rbthemefunction'}</span>
		    	</a>
		    	<a
		    		class="rb-account"
		    		href="{$my_account_url|escape:'html':'UTF-8'}"
		    		title="{l s='View My Account' mod='rbthemefunction'}"
		    		rel="nofollow"
		    	>
		        	<i class="material-icons">&#xE7FD;</i>
		        	<span class="hidden-sm-down">{$customerName|escape:'html':'UTF-8'}</span>
		    	</a>
	    	</div>
		{else}
			<div class="rb-dropdown rb-login-form rb-form-container dd-container dd-products dd-view">
				<div class="indent rb-indent">
					<div class="title-wrap flex-container">
						<h4 class="customer-form-tab login-tab active">
							<span>{l s='Sign In' mod='rbthemefunction'}</span>
						</h4>

						<h4>{l s='OR' mod='rbthemefunction'}</h4>

						<h4 class="customer-form-tab register-tab">
							<span>{l s='Register' mod='rbthemefunction'}</span>
						</h4>
					</div>

					<div class="form-wrap">
						<form class="rb-customer-form active rb-form-login" action="{$urls.pages.authentication}" method="post">
							<div class="relative form-group">
								<div class="icon-true">
									<input class="form-control" name="email" type="email" value="" placeholder="{l s='Email' mod='rbthemefunction'}" required="">
									<i class="material-icons">email</i>
								</div>
							</div>
							<div class="relative form-group">
								<div class="input-group-dis js-parent-focus">
									<div class="icon-true relative">
										<input class="form-control js-child-focus js-visible-password" name="password" type="password" value="" placeholder="{l s='Password' d='Shop.Forms.Labels'}" required="">
										<i class="material-icons">vpn_key</i>
									</div>
								</div>
							</div>

							<div class="forgot-password">
								<input type="hidden" name="submitLogin" value="1">
								<button class="btn btn-primary" data-link-action="sign-in" type="submit">
									{l s='Sign In' mod='rbthemefunction'}
								</button>
								&nbsp;{hook h='displayFacebookLogin'}
							</div>

							<a href="{$urls.pages.password}" rel="nofollow">
								{l s='Forgot your password?' mod='rbthemefunction'}
							</a>
						</form>

						<form action="{$urls.pages.register}?back=identity" class="rb-customer-form rb-form-register" method="POST">
							<input type="hidden" value="1" name="submitCreate">
								<input type="hidden" value="0" name="newsletter">
								<input type="hidden" value="0" name="optin">
								<input type="hidden" value=""  name="id_customer">
								<input type="hidden" value="1" name="id_gender">
				
								<div class="form-group relative">
									<div class="icon-true">
										<input class="form-control" name="email" type="email" value="" placeholder="{l s='Email' mod='rbthemefunction'}" required="">
										<i class="material-icons">email</i>
									</div>
								</div>

								<div class="form-group relative">
									<div class="input-group-dis js-parent-focus">
										<div class="icon-true relative">
											<input class="form-control" name="password" placeholder="{l s='Password' d='Shop.Forms.Labels'}" type="password" value="" required="" pattern=".{literal}{{/literal}5,{literal}}{/literal}">
											<i class="material-icons">vpn_key</i>
										</div>
									</div>
								</div>
								<div class="form-group relative">
									<div class="icon-true">
										<input class="form-control" name="firstname" type="text" value="" placeholder="{l s='First Name' mod='rbthemefunction'}" required="">
										<i class="material-icons">&#xE7FF;</i>
									</div>
								</div>
								<div class="form-group relative">
									<div class="icon-true">
										<input class="form-control" name="lastname" type="text" value="" placeholder="{l s='Last Name' mod='rbthemefunction'}" required="">
										<span class="focus-border"><i></i></span>
										<svg class="svgic input-icon"><use xlink:href="#si-account"></use></svg>
									</div>
								</div>
								<div class="relative form-group rb-check-box">
									<input class="form-control" name="optin" id="optin" type="checkbox" value="1">
									<label for="optin">
										{l s='I agree to the' mod='rbthemefunction'}
									</label>
								</div>
								<div class="relative form-group rb-check-box">
									<input class="form-control" name="newsletter" id="newsletter" type="checkbox" value="1">
									<label for="optin">
										{l s='Sign up for our newsletter' mod='rbthemefunction'}
									</label>
								</div>
								<button class="btn btn-primary form-control-submit register-button" type="submit" data-back="{$urls.pages.identity}">
									{l s='Register' mod='rbthemefunction'}
								</button>
							</form>
					</div>
				</div>
			</div>
		{/if}
	</div>
{/if}
<!-- End -->

<!-- Compare -->
{if isset($top) && $top == 'compare'}
	<div class="rb-id-compare">
		<a href="{$rb_compare}">
			<span class="rb-header-item">
				<i class="material-icons">compare_arrows</i>
				<span class="rb-compare-quantity rb-amount-inline">{$rb_number_compare}</span>
			</span>
		</a>
	</div>
{/if}
<!-- End -->

<!-- TPL wishlist -->
{if isset($top) && $top == 'wishlist'}
	<div class="rb-id-wishlist">
		<a href="{$rb_wishlist}">
			<span class="rb-header-item">
				<i class="icon-btn-product icon-wishlist material-icons">&#xE87D;</i>
				<span class="rb-wishlist-quantity rb-amount-inline">{$rb_number_wishlist}</span>
			</span>
		</a>
	</div>
{/if}
<!-- End -->