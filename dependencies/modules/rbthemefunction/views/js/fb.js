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
$(document).ready(function() {
  	if (typeof rb_facebook.general_appid !== 'undefined') {
	    if (rb_facebook.chat_state == 1) {
	      	$('body').append(
	      		'<div class="fb-customerchat" greeting_dialog_delay="' + rb_facebook.chat_delay + '" theme_color="'
	      		+ rb_facebook.chat_color + '" page_id="' + rb_facebook.general_pageid + '"></div>'
	      	);
	    }

	    if ((rb_facebook.login_state == 1) && (prestashop.customer.is_logged === false)) {
	      	$('.forgot-password').append(
	      		'<fb:login-button data-size="large"data-button-type="login_with" scope="public_profile,email"onlogin="checkLoginState();">'
	      		+rb_facebook.phrases.login + '</fb:login-button>'
	      	);

		    if ($('#checkout')[0]) {
		        rb_facebook.redirect = 'no_redirect';
		    }
	    }

	    var locale = prestashop.language.locale.replace('-', '_');

	    if (typeof window.fbAsyncInit !== 'function') {
	    	window.fbAsyncInit = function() {
	    		FB.init({
	    			appId: rb_facebook.general_appid,
	    			cookie: true,
	    			xfbml: true,
	    			version: 'v3.3',
	    			status: true,
	    			autoLogAppEvents: true
	    		});
	    		FB.AppEvents.logPageView();
	    	};
	    	(function(d, s, id) {
	    		var js, fjs = d.getElementsByTagName(s)[0];
	    		if (d.getElementById(id)) return;
	    		js = d.createElement(s); js.id = id;
	    		js.src = "//connect.facebook.net/"+locale+"/sdk/xfbml.customerchat.js";
	    		fjs.parentNode.insertBefore(js, fjs);
	    	}(document, 'script', 'facebook-jssdk'));
	    }

	}
});

function pfFbLogin(response) {
	$.ajax({
		type: "POST",
		url: url_ajax,
		data: {
			firstname: response.first_name,
			lastname: response.last_name,
			email: response.email,
			id: response.id,
			gender: response.gender,
			action1: loginFacebook
  		},
	  	success:  function(data) {
	  		if (rb_facebook.login_redirect == "no_redirect")
	  			window.location.reload();

	  		if(rb_facebook.login_redirect == "authentication_page")
	  			window.location.href = rb_facebook.login_destination;
	  	}
	});
}

function fb_login(){
  	FB.login(function(response) {
	    if (response.authResponse) {
	      	FB.api('/me',{fields: 'id,first_name,last_name,email,gender'}, function(response) {
	         	pfFbLogin(response);
	      	});
	    }
  	});
}

function checkLoginState() {
  	FB.getLoginStatus(function(response) {
    	statusChangeCallback(response);
  	});
}

function statusChangeCallback(response) {
  	if (response.status === 'connected') {
    	fb_login();
  	}
}

function testAPI() {
  	console.log('Welcome!  Fetching your information.... ');

  	FB.api('/me',{fields: 'id,name,email'}, function(response) {
    	console.log(response);
  	});
}