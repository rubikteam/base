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
var url = '';

$(document).ready(function() {
	url = $('#rbthemedream-group').data('url') + 'img/cms/';
	$('#fieldset_0').addClass('active');
	selectImage();
	showHide();
});

function hideOption()
{
	$('.rb_hide').each(function() {
		if ($(this).parent().hasClass('input-group')) {
			$(this).closest('.form-group').parent().parent().hide();
		} else {
			$(this).closest('.form-group').hide();
		}
	});
}

function showForClass(clas)
{
	$('.' + clas + '').each(function() {
		$(this).removeClass('rb_hide');
		if ($(this).parent().hasClass('input-group')) {
			$(this).closest('.form-group').parent().parent().show();
		} else {
			$(this).closest('.form-group').show();
		}
	});
}

function hideForClass(clas)
{
	$('.' + clas + '').each(function() {
		$(this).addClass('rb_hide');
		if ($(this).parent().hasClass('input-group')) {
			$(this).closest('.form-group').parent().parent().hide();
		} else {
			$(this).closest('.form-group').hide();
		}
	});
}

function showHide()
{
	hideOption();

	$('.rb_option').change(function() {
		var data = $(this).val();
		var id = $(this).attr('id');

		if (data == 'wide' || data == 'boxed') {
			if (data == 'boxed') {
				showForClass('rb_show_hide');
			} else {
				hideForClass('rb_show_hide');
			}
		}

		if (id == 'RBTHEMEDREAM_CONTAINER_BOX_SHADOW') {
			if (data == 0) {
				hideForClass('rb_box_shadow');
			}

			if (data == 1) {
				showForClass('rb_box_shadow');
			}
		}

		if (id == 'RBTHEMEDREAM_CONTAINER_BORDER_TYPE') {
			if (data != 'none' && data != '') {
				showForClass('rb_bd_type');
			} else {
				hideForClass('rb_bd_type');
			}
		}
	});

	$('input[name="RBTHEMEDREAM_BACKGROUND_IMAGE"]').change(function() {
		$('.rb_gb_img').removeClass('rb_hide');
		$('.rb_gb_img').closest('.form-group').show();
	});
}

function selectImage()
{
	$('.js-img-upload').fancybox({
	    'width': 900,
	    'height': 600,
	    'type': 'iframe',
	    'autoScale': false,
	    'autoDimensions': false,
	    'fitToView': false,
	    'autoSize': false,
	    onUpdate: function onUpdate() {
	    	var url_img = $('.fancybox-iframe').contents().find('a.link');
	    	var input_name = $(this.element).data('input-name');
	    	url_img.data('field_id', input_name);
	    	url_img.attr('data-field_id', input_name);
	    },
	    afterShow: function afterShow() {
	    	var url_img = $('.fancybox-iframe').contents().find('a.link');
	    	var input_name = $(this.element).data('input-name');
	    	url_img.data('field_id', input_name);
	    	url_img.attr('data-field_id', input_name);
	    },
	    beforeClose: function beforeClose() {
	    	var input = $('#' + $(this.element).data("input-name"));
	    	var val = input.val();
	    	input.val(val.replace(url, ""));
	    	input.change();
	    }
	});
}