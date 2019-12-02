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
var obj;

$(document).ready(function() {
	$('.rb-loading').fadeOut(2500);

	if ($(window).width() >= 768 ) {
		SalePopup();
	}

	ajaxloading();
	addCompare();
	addWishlist();
	viewWishList();
	setDefaultWishList();
	deleteWishList();
	addReview();
	activeRate();
	sendReview();
	rbFormRegister();
	addQuickview();
	selectViewProductList();

	prestashop.on('updateProductList', function() {
		addCompare();
		addWishlist();
		ajaxloading();
		addQuickview();
	});

	prestashop.on('updatedProduct', function() {  
		addCompare();
		addWishlist();
		ajaxloading();
		addReview();
	});

	$('.rb-compare-remove-all').click(function(e) {
		e.preventDefault();
		removeCompare($(this), 1);
	});

	$('.rb-remove-compare-product').click(function(e) {
		e.preventDefault();
		removeCompare($(this), 0);
	});
});

$(document).on('click', '.type-view', function(e){
  	e.preventDefault();
  	var $this= $(this);

  	if ($this.hasClass('rb-grid')) {
  		$('#js-product-list .rb-product-list').removeClass('list');
  		$('#js-product-list .rb-product-list').addClass('grid');
  		$.cookie('rb_view_product', 'grid');
  	} else {
  		$('#js-product-list .rb-product-list').removeClass('grid');
  		$('#js-product-list .rb-product-list').addClass('list');
  		$.cookie('rb_view_product', 'list');
  	}

  	$('.type-view').removeClass('selected');
  	$this.addClass('selected');
});

function selectViewProductList()
{
	if ($.cookie('rb_view_product') == 'grid') {
		$('#rb_grid').trigger('click');
	} else if ($.cookie('rb_view_product') == 'grid') {
		$('#rb_list').trigger('click');
	} else {
		if (rb_view == 1) {
			$('#rb_grid').trigger('click');
		} else {
			$('#rb_list').trigger('click');
		}
	}
}

function addQuickview()
{
	$('.rb-quick-view').click(function(e) {
		e.preventDefault();
		var $this = $(this);
		showHideLoading($(this), 1);

		$(this).closest('.js-product-miniature').find('a.quick-view').trigger('click');

		$(document).ajaxComplete(function(event, jqxhr, data) {
			showHideLoading($this, 0);
		});
	});
}

function rbFormRegister()
{
	$('.customer-form-tab').click(function() {
		$('.customer-form-tab').removeClass('active');
		$('.rb-customer-form').removeClass('active');
		$(this).addClass('active');

		if ($(this).hasClass('login-tab')) {
			$('.rb-form-login').addClass('active');
		} else {
			$('.rb-form-register').addClass('active');
		}
	});
}

function choseProduct(myArray) {
	var i = myArray.length, j, temp;
	if (i === 0) return false;

	while (--i) {
		j = Math.floor(Math.random() * ( i + 1 ));
		temp = myArray[i];
		myArray[i] = myArray[j]; 
		myArray[j] = temp;
	}
}

choseProduct(collections);

function SalePopup() {
	var text1 = $('.sale-popup').data('text1');
	var text2 = $('.sale-popup').data('text2');

	if ($.cookie("rb_popup_sale") == "true") {
		return;
	}

	if ($('.sale-popup').length < 0)
		return;

	setInterval(function() {
		$('.sale-popup').fadeIn(function() {
			$(this).removeClass('slideUp');
		}).delay(10000).fadeIn(function() {
			randomProduct = Math.floor(Math.random() * collections.length),
			randomShow = collections[randomProduct],
			$(".sale-popup").html(randomShow);
			$('.sale-popup-timeago').text(text1 + ' ' + Math.floor((Math.random() * 59) + 1) + ' ' + text2);
			$(this).addClass('slideUp');
			$('.button-close').on('click', function() {
				$('.sale-popup').remove();
				$.cookie("rb_popup_sale", "true");
			});
		}).delay(6000);
	}, 6000);
}

$(document).ajaxComplete(function(event, jqxhr, data) {
	if (typeof data.data != 'undefined' && data.data.indexOf('action=add-to-cart') != -1) {
		showHideLoading(obj, 0);
	}
});

function sendReview()
{
	$('.rb-control-submit').click(function(e) {
		var rb_this = $(this);
		if (!rb_this.hasClass('active')) {
			rb_this.addClass('active');
			rb_this.closest('.form-new-review').find('.rb-ajax-loading').show();
			var title = $('#new_review_title').val();
			var cmt = $('#rb_review_content').val();
			var id_product = $('#id_product_review').val();

			if (title != '' && cmt != '') {
				e.preventDefault();
				var rating = 0;

				$('.star_on').each(function() {
					rating ++;
				});

				$.ajax({
					type: 'POST',
					url: url_compare,
					headers: { "cache-control": "no-cache" },
					async: true,
					cache: false,
					dataType: "json",
					data: {
						ajax: 1,
						token: token,
						id_product:id_product,
						title: title,
						cmt : cmt,
						rate : rating,
						action: 'addReviewProduct'
					},
					success: function(data)
					{
						rb_this.removeClass('active');
						rb_this.closest('.form-new-review').find('.rb-ajax-loading').hide();
						rbPopup(data.message, 1);
					}
				});
			}
		}
	});
}

function activeRate()
{
	$('input.star').rating({cancel: cancel_rating_txt});
	$('.auto-submit-star').rating({cancel: cancel_rating_txt});
}

function deleteWishList()
{
	$('.delete-wishlist').click(function(e) {
		e.preventDefault();
		var rb_this = $(this);
		if (!rb_this.hasClass('active')) {
			rb_this.addClass('active');
			rb_this.parents('tr').addClass('active');
			var id_wishlist = rb_this.data('id-wishlist');

			if ($('.rb-list-wishlist tr.active .default-wishlist').is(":checked")) {
				rbPopup(text2,  1);
				rb_this.removeClass('active');
				rb_this.parents('tr').removeClass('active');
			} else {
				rbPopup(rb_modal,  0);
				rb_this.removeClass('active');
				rb_this.parents('tr').removeClass('active');

				$('.rb-modal-accept').click(function() {
					if ($(this).hasClass('rb-modal-no')) {
						$(this).closest('.rb-popup-content').find('.mfp-close').trigger('click');
					} else {
						if (!$(this).hasClass('active')) {
							$(this).addClass('active');

							$.ajax({
								type: 'GET',
						        url: url_wishlist,
						        headers: { "cache-control": "no-cache" },
						        async: true,
						        cache: false,
						        dataType: "json",
						        data: {
						            ajax: 1,
						            token: token,
									id_wishlist: id_wishlist,
						            action: 'deleteWishList'
						        },
						        success: function(resp)
						        {
						        	$('.rb-wishlist-quantity').toggleClass('lingge', resp.total==0).html(resp.total);

						    		if (resp.success == 0) {
						    			rbPopup(resp.message, 1);
						    		}

						    		if (resp.success == 1) {
						    			window.location.reload();
						    		}
						        }
						    });
						}
					}
				});
			}
		}
	});
}

function sendWishList()
{
	$('.rb-send-wishlist').click(function(e) {
		var rb_this = $(this);
		var email = $('#email_3').val();
		var id_wishlist = $('.rb-list-wishlist tr.show td a.view-wishlist-product').data('id-wishlist');

		if (email == '') {
			$('.form-send-wishlist').trigger('submit');
		} else if (!validateEmail(email)) {
			e.preventDefault();
		} else {
			e.preventDefault();
			rb_this.closest('.send-wishlist').find('.rb-ajax-loading').show();

			$.ajax({
				type: 'GET',
		        url: url_wishlist,
		        headers: { "cache-control": "no-cache" },
		        async: true,
		        cache: false,
		        dataType: "json",
		        data: {
		            ajax: 1,
		            token: token,
					id_wishlist: id_wishlist,
					email: email,
		            action: 'sendWishListEmail'
		        },
		        success: function(resp)
		        {
		        	rb_this.closest('.send-wishlist').find('.rb-ajax-loading').hide();
		        	rbPopup(resp.message, 1);
		        }
		    });
		}
	});
}

function setDefaultWishList()
{
	$('.default-wishlist').click(function(e) {
		e.preventDefault();
		var rb_this = $(this);

		if (rb_this.is(":checked")) {
			if (!$('.default-wishlist.active').length) {
				rb_this.addClass('active');
				var id_wishlist = $('.default-wishlist.active').data('id-wishlist');

				$.ajax({
					type: 'POST',
					headers: {"cache-control": "no-cache"},
					url: url_wishlist,
					async: true,
					cache: false,
					dataType: "json",
					data: {
		            	ajax: 1,
		            	token: token,
		            	id_wishlist: id_wishlist,
		            	action: 'setDefaultWishList'
					},
					success: function(resp)
					{
						rb_this.removeClass('active');

						if (resp.success == 0) {
							rbPopup(resp.message, 1);
						}

						if (resp.success == 1) {
							$('.default-wishlist:checked').prop('checked', false);
							rb_this.prop('checked', true);
						}	
					}
				});
			}
		}
	});
}	

function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function viewWishList()
{
	$('.view-wishlist-product').click(function(e) {
		e.preventDefault();
		var rb_this= $(this);
		if (!$('.view-wishlist-product.active').length) {
			rb_this.parent().find('.rb-ajax-loading').show();
			$(this).addClass('active');
			$('.list-wishlist tr.show').removeClass('show');
			$(this).parents('tr').addClass('show');

			var rb_this = $(this);
			var id_wishlist = $(this).data('id-wishlist');

			$.ajax({
				type: 'GET',
		        url: url_wishlist,
		        headers: { "cache-control": "no-cache" },
		        async: true,
		        cache: false,
		        dataType: "json",
		        data: {
		            ajax: 1,
		            token: token,
					id_wishlist: id_wishlist,
		            action: 'viewWishListProduct'
		        },
		       	success: function (resp)
	        	{
	        		rb_this.parent().find('.rb-ajax-loading').hide();
	        		$('.list-wishlist tr.show').removeClass('show');
	        		$('.view-wishlist-product.active').removeClass('active');

		            if (resp.success == 1) {
		            	sendWishList();
		               	$('.rb-wishlist-product').html(resp.message).fadeIn();
		               	$('.send-wishlist').fadeIn();
		               	deleteWishListProduct();
		            }

		            if (resp.success == 0) {
		            	$('.send-wishlist').hide();
		            	var html = '<div class="col-xl-12"><p class="alert alert-warning">'+text1+'</p></div>';
		            	$('.rb-wishlist-product').html(html);
		               	rbPopup(resp.message, 1);
		            }
	        	},
			});
		}
	});
}

function deleteWishListProduct()
{
	$('.rb-wishlist-delete').click(function(e) {
		e.preventDefault();
		var rb_this = $(this);

		if (!rb_this.hasClass('active')) {
			rb_this.addClass('active');
			rb_this.closest('.delete-wishlist-product').find('.rb-ajax-loading').show();
			var id_wishlist = rb_this.data('id-wishlist');
			var id_wishlist_product = rb_this.data('id-wishlist-product');

			$.ajax({
				type: 'GET',
		        url: url_wishlist,
		        headers: { "cache-control": "no-cache" },
		        async: true,
		        cache: false,
		        dataType: "json",
		        data: {
		            ajax: 1,
		            token: token,
					id_wishlist: id_wishlist,
					id_wishlist_product: id_wishlist_product,
		            action: 'deleteWishListProduct'
		        },
		       	success: function (resp)
	        	{
	        		$('.rb-wishlist-quantity').toggleClass('lingge', resp.total==0).html(resp.total);
	        		rb_this.removeClass('active');
	        		rb_this.closest('.delete-wishlist-product').find('.rb-ajax-loading').hide();

	        		if (resp.success == 0) {
	        			rbPopup(resp.message, 0);
	        		}

	        		if (resp.success == 1) {
	        			window.location.reload();
	        		}
	        	},
			});
		}
	});
}

function ajaxloading()
{
	$('.product-quantity .add-to-cart').click(function() {
		obj = $(this);
		showHideLoading(obj, 1);
	});
}

function showHideLoading(obj, type)
{
	if ($('body#product').length > 0) {
		if (type == 0) {
			obj.closest('.product-actions').find('.rb-ajax-loading').hide();
		} else {
			obj.closest('.product-actions').find('.rb-ajax-loading').show();
		}
	} else {
		if (type == 0) {
			obj.closest('.js-product-miniature').find('.rb-ajax-loading').hide();
		} else {
			obj.closest('.js-product-miniature').find('.rb-ajax-loading').show();
		}
	}
}

function removeCompare(rb_this, action)
{
	var id_product = rb_this.data('id-product');

    if (!id_product && !action) {
    	return false;
    }

  	rb_this.addClass('active');

  	$.ajax({
		type: 'GET',
		url: url_compare,
		headers: { "cache-control": "no-cache" },
		async: true,
		cache: false,
		dataType: "json",
		data: {
			id_product:id_product,
			ajax: 1,
			action: action ? 'deleteAllCompareProducts' : 'deleteCompareProduct'
		},
		success: function (resp)
		{
			$('.remove-compare-product.active').removeClass('active');

			if (resp.success == 1) {
	            if (!resp.total) {
	            	stCompareInformation();
	            }

	            $('.rb-compare-td-'+id_product).remove();
      			$('.rb-compare-quantity').toggleClass('lingge', resp.total==0).html(resp.total);
			} else if (!resp.success) {
				rbPopup(resp.message, 0);
			}
		},
	    error: function(XMLHttpRequest, textStatus, errorThrown)
	    {
			$('.remove-compare-product.active').removeClass('active');
	    }
	});
}

function addWishlist()
{
	$('.rb-wishlist-link').on('click', function(e) {
		var rb_this = $(this);
		e.preventDefault();
		showHideLoading($(this), 1);
		var remove = rb_this.hasClass('rb_added');
		var id_product = $(this).data('id-product');
		var id_wishlist = $(this).data('id-wishlist');
		var id_product_attribute = $(this).data('id-product-attribute');
		var remove = rb_this.hasClass('rb_added');
		rb_this.toggleClass('rb_added').addClass('active');

		if (!isLogged) {
			setTimeout(function() {
	        	rbPopup(rb_text, 0, rb_this);
		    }, 800);

			return false;
		}

		$.ajax({
	        type: 'GET',
	        url: url_wishlist,
	        headers: { "cache-control": "no-cache" },
	        async: true,
	        cache: false,
	        dataType: "json",
	        data: {
	            id_product:id_product,
	            ajax: 1,
	            token: token,
	            id_product: id_product,
				id_wishlist: id_wishlist,
				id_product_attribute: id_product_attribute,
				quantity: 1,
	            action: remove ? 'deleteWishListProduct' : 'addWishListProduct'
	        },
	       	success: function (resp)
	        {
				$('.rb-wishlist-link.active').removeClass('active');
	      		$('.rb-wishlist-quantity').toggleClass('lingge', resp.total==0).html(resp.total);
	            
	            if (resp.success) {
	                rbPopup(resp.message, 2);
	            } else {
	                rbPopup(resp.message, 0);
	                rb_this.toggleClass('rb_added');
	            }
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown)
	        {
				$('.rb-wishlist-link.active').removeClass('active');
	            rb_this.toggleClass('rb_added');
	        },
	        complete: function()
	        {
	        	showHideLoading(rb_this, 0);
	        }
	    });
	});
}

function addCompare()
{
	$('.rb-compare-link').on('click', function(e) {
		e.preventDefault();
		var rb_this = $(this);
		showHideLoading(rb_this, 1);
		var id_product = $(this).data('id_product');

		if (!id_product)
		   return false;

		var remove = rb_this.hasClass('rb_added');
		rb_this.toggleClass('rb_added').addClass('active');

		$.ajax({
	        type: 'GET',
	        url: url_compare,
	        headers: { "cache-control": "no-cache" },
	        async: true,
	        cache: false,
	        dataType: "json",
	        data: {
	            id_product:id_product,
	            ajax: 1,
	            token: token,
	            action: remove ? 'deleteCompareProduct' : 'addCompareProduct'
	        },
	        success: function (resp)
	        {
				$('.rb-compare-link.active').removeClass('active');
	      		$('.rb-compare-quantity').toggleClass('lingge', resp.total==0).html(resp.total);
	            
	            if (resp.success) {
	                rbPopup(resp.message, 2);
	            } else {
	                rbPopup(resp.message, 0);
	                rb_this.toggleClass('rb_added');
	            }
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown)
	        {
				$('.rb-compare-link.active').removeClass('active');
	            rb_this.toggleClass('rb_added');
	        },
	        complete: function()
	        {
	        	showHideLoading(rb_this, 0);
	        }
    	});
	});
}

function rbCompareInformation()
{
    $('.rb-compare-table').addClass('rb-none');
    $('.rb-compare-no-products').removeClass('rb-none');
}

function addReview()
{
	$('.rb-open-review').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('href');
		$(document).off("scroll");
		var target = this.hash,
		menu = target;
		$target = $(target);

		$('body,html').stop().animate({
			scrollTop: $target.offset().top-150
		}, 1500);

		$('' + id + ' a').trigger('click');
	});
}

function rbPopup(msg, autoclose, obj = '')
{
    $.magnificPopup.open({
      	removalDelay: 500,
      	showCloseBtn: autoclose ? false : true,
      	callbacks: {
	        beforeOpen: function() {
	           this.st.mainClass = 'mfp-zoom-in'+(autoclose ? ' mfp-modal-noti ' : '');
	        },
	        open: function(){
	        	var persist=this;
	        	
	        	if (autoclose) {
	        		setTimeout(function(){
	        			persist.close();
		            }, autoclose*2000);
		        }

		      	if (obj != '') {
	        		showHideLoading(obj, 0);
		        }
	        }
      	},
	    items: {
	        src: '<div class="rb-popup-content rb-small-popup text-center">'+msg+'</div>',
	        type: 'inline'
	    }
    });
}