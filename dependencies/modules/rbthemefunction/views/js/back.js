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
	$('.rb-html-class').parent().removeClass('col-lg-9');
	$('.rb-html-class').parent().removeClass('col-lg-offset-3');
	
	getNewReview();
});

function getNewReview()
{
	$.ajax({
		type: 'GET',
		url: 'index.php',
		headers: { "cache-control": "no-cache" },
		async: true,
		cache: false,
		dataType: "json",
		data: {
			ajax: 1,
			controller: 'AdminRbthemefunctionReview',
			token: token,
			action: 'getNewReview'
		},
		success: function (data)
		{
			if (data.success == 1 && data.total_review > 0) {
				$('#subtab-AdminRbthemefunctionManagement').addClass('has-review');
				$('#subtab-AdminRbthemefunctionReview').append(
					'<div class="notification-container"><span class="notification-counter">'+data.total_review+'</span></div>'
				);
			}
		}
	});
}