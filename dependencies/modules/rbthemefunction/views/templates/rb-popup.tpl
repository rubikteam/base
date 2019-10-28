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
<div
	class="rb-popup-container"
	style="width:{$rb_width}px;
	height:{$rb_height}px;{if $rb_img == 1}
	background-image: url('{$rb_url_img}');{/if}"
>
	<div class="rb-popup-flex">
		<div id="rb_newsletter_popup" class="rb-block">
			<div class="rb-block-content">
				<form action="" method="POST">
					<div class="rb-popup-text">
    					{$rb_text nofilter}
                    </div>

                    {if $rb_form == 1}
	                    <div class="rb-relative-input relative">
	                    	<input class="inputNew" id="rb-newsletter-popup" type="email" name="email" required="" value="" placeholder="{l s='your@email.com' mod='rbthemefunction'}" />
	                    	<button class="rb-send-email">
	                    		<i class="material-icons">trending_flat</i>
	                    	</button>
	                    </div>

	                    <div class="rb-email-alert">
	                    	{include file='module:rbthemefunction/views/templates/rb-ajax-loading.tpl'}
	                    	<p class="rb-email rb-email-success alert alert-success"></p>
	                    	<p class="rb-email rb-email-error alert alert-danger"></p>
	                    </div>
                    {/if}
				</form>
			</div>
		</div>
	</div>
</div>