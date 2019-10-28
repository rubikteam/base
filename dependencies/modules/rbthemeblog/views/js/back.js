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
$(function() {
	$('.rbblog-post-type').hide();

	showPostType($('#id_rbblog_post_type').val());

	function showPostType(id_rbblog_post_type)
	{
		$('.rbblog-post-type').hide();
		$('.rbblog-post-type-' + id_rbblog_post_type).show();
	}

	$(document).on('change', '#id_rbblog_post_type', function() {
		showPostType($(this).val());
	});

	function formatResult(product) {
	    if (!product.image) return product.text;
	    
	    return "<img class='product_image' src='" +
	    product.image +
	    "' style='max-width: 50px; height: auto; vertical-align: middle; margin-right: 10px;' />" +
	    product.text;
	}

	$.fn.select2.defaults.set( "theme", "bootstrap" );
	var productSelector = $('#select_product');

	productSelector.select2({
	    minimumInputLength: 3,
	    width: null,
	    multiple: true,
	    ajax: {
	        url: 'index.php',
	        dataType: 'json',
	        quietMillis: 100,
	        data: function (params) {
			    var queryParameters = {
			      	q: params.term,
			      	controller:'AdminRbthemeblogTag',
					action:'searchProducts',
					token:token,
					ajax:1
			    }
			
			    return queryParameters;
			},
	        processResults: function (data) {
			    return {
			      results: data
			    };
			}
	    },

	    templateResult: formatResult,
		templateSelection: formatResult,
		escapeMarkup: function (markup) { return markup; }
	});
});