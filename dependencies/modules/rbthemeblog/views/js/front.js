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
function markAlreadyLikedPost(guest, id_rbblog_post)
{
	if ($.cookie('guest_' + guest + '_' + id_rbblog_post) == "voted") {
		$('.rbblog-like-button').addClass('voted');
	}
};

$(function() {
	if ($('#module-rbthemeblog-single').length > 0) {
		$('.rbblog__post a.fancybox').fancybox();

		markAlreadyLikedPost(
			$('#module-rbthemeblog-single .rbblog-like-button').data('guest'),
			$('.rbblog-single .rbblog-like-button').data('post')
		);

		$(document).on('click', '.rbblog__share .btn', function() {
			type = $(this).attr('data-type');

			if (type.length) {
				switch(type)
				{
					case 'twitter':
						window.open(
							'https://twitter.com/intent/tweet?text=' + rb_sharing_name + ' ' +
							encodeURIComponent(rb_sharing_url),
							'sharertwt',
							'toolbar=0,status=0,width=640,height=445'
						);
						break;
					case 'facebook':
						window.open(
							'http://www.facebook.com/sharer.php?u=' + rb_sharing_url,
							'sharer',
							'toolbar=0,status=0,width=660,height=445'
						);
						break;
					case 'google-plus':
						window.open(
							'https://plus.google.com/share?url=' + rb_sharing_url,
							'sharer', 'toolbar=0,status=0,width=660,height=445'
						);
						break;
					case 'pinterest':
						window.open(
							'http://www.pinterest.com/pin/create/button/?media=' + ph_sharing_img + '&url=' + ph_sharing_url,
							'sharerpinterest',
							'toolbar=0,status=0,width=660,height=445'
						);
						break;
				}
			}
		});

		$(document).on('click', '.rbblog-like-button', function(e) {
			e.preventDefault();
			var id_rbblog_post = $(this).data('post');
			var id_guest = $(this).data('guest');
			var element = $(this);

			if ($.cookie('guest_' + id_guest + '_' + id_rbblog_post) == "voted") {
				$.cookie('guest_' + id_guest + '_' + id_rbblog_post, '');
				var request = $.ajax({
				  	type: "POST",
				  	url: rbthemeblog_ajax,
				  	data: { 
					  	action: 'removeRating',
						id_rbblog_post : id_rbblog_post,
						secure_key: rbthemeblog_token,
						ajax: true
					},
					success: function(result)
					{             
				    	var data = $.parseJSON(result);

						if (data.status == 'success') {		
							element.removeClass('voted').find('span.likes-count').text(data.message);
						} else {
							alert(data.message);
						}
					}
				});
			} else {
				$.cookie('guest_' + id_guest + '_' + id_rbblog_post, 'voted');
				var request = $.ajax({
				  	type: "POST",
				  	url: rbthemeblog_ajax,
				  	data: { 
					  	action: 'addRating',
						id_rbblog_post : id_rbblog_post,
						secure_key: rbthemeblog_token,
						ajax: true
					},
					success: function(result)
					{         
				    	var data = $.parseJSON(result);

						if (data.status == 'success') {		
							element.addClass('voted').find('span.likes-count').text(data.message);
						} else {
							alert(data.message);
						}
					}
				});
			}
		});
	}
	
	if (typeof fancybox !== 'undefined') {
		$('.rbblog-post-item a.post-gallery-link').fancybox();

		$("a.rbblog__post-featured").fancybox({
		    maxWidth    : 1440,
		    fitToView   : false,
		    autoSize    : false,
		    closeClick  : false,
		    width		: 640,
			height		: 385,
		    openEffect  : 'none',
		    closeEffect : 'none',
		    iframe: {
				preload: false
			},
		    helpers : {
		        overlay : {
		            css : {
		                'background' : 'rgba(0, 0, 0, 0.90)'
		            }
		        }
		    }
		});
	}
});