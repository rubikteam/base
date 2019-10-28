/**
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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
$(document).ready(function () {
	if ($.cookie("rb_popup") != "true") {
		$.fancybox.open(popup, {
			'padding' : 0,
			'width' : rb_width,
			'height' : rb_height,
			'type': 'inline',
			'fitToView' : true,
			'autoSize' : false,
			'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic',
			'openEffect'	: 'elastic',
			'openSpeed'		: '600',
			'easingIn'      : 'swing',
			'easingOut'     : 'swing',
			beforeShow: function() {
				$( "body" ).addClass( "fancyBoxActive" );
				disableScrolling();
	    	},
			beforeClose: function() {
				$.cookie("rb_popup", "true");
				$('.fancybox-overlay-custom').remove();
				$( "body" ).removeClass( "fancyBoxActive" );

				window.onscroll = function()
				{

				};
			}
		});

		sendEmail();
	}	
});

function sendEmail()
{
	$(".rb-send-email").click(function(e){
		e.preventDefault();
		$('.rb-email').hide();
		$('.rb-email-alert .rb-ajax-loading').show();
		var email = $('#rb-newsletter-popup').val();

		$.ajax({
			type: "POST",
			headers: { "cache-control": "no-cache" },
			async: false,
			url: url_ajax,
			dataType: "json",
			data: {
				ajax: 1,
				token: token,
				email: email,
				action: 0,
				action1: 'sendFormEmail'
			},
			success: function(data) {
				$('.rb-email-alert .rb-ajax-loading').hide();

				if (data.success == 0) {
					$('.rb-email-error').html(data.message);
					$('.rb-email-error').show();
				}

				if (data.success == 1) {
					$('.rb-email-success').html(data.message);
					$('.rb-email-success').show();
				}
			}
		});
	});
}

function disableScrolling()
{
    var x = window.scrollX;
    var y = window.scrollY;

    window.onscroll = function() {
    	window.scrollTo(x, y);
    };
}