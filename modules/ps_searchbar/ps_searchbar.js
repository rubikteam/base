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
	$('.rb-search').keyup(function(e) {
		e.preventDefault();
		var val = $(this).val();

		if (val.length >= 3) {
			$('.rb-search-name .rb-ajax-loading').show();
			$('.rb-resuilt').html('');
			$('.rb-resuilt-error').hide();

			$.ajax({
				type: "POST",
				headers: { "cache-control": "no-cache" },
				async: false,
				url: url_ajax,
				dataType: "json",
				data: {
					ajax: 1,
					token: token,
					action1: 'findProductByName',
					name: val,
				},
				success: function (data)
				{
					$('.rb-search-name .rb-ajax-loading').hide();

					if (data.success == 1) {
						$('.rb-resuilt').html(data.message);
						$('.rb-resuilt').show();

						$('body').click(function() {
							$('.rb-resuilt').html('');
							$('.rb-resuilt').hide();
							$('.rb-resuilt-error').hide();
						});
					}

					if (data.success == 0){
						$('.rb-resuilt-error').html(data.message);
						$('.rb-resuilt-error').show();
					}			
				}
			});
		}
	});
});