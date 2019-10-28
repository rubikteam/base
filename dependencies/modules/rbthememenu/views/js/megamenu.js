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
    $(document).mouseup(function (e) {
        var container_block_search = $('.rb_extra_item.active');

        if (!container_block_search.is(e.target)&& container_block_search.has(e.target).length === 0) {
            $('.rb_extra_item').removeClass('active');
        }
    });
    
    if ($('.rb_extra_item button[type="submit"]').length) {
        $(document).on('click','.rb_extra_item button[type="submit"]',function(){
            if (!$(this).closest('.rb_extra_item').hasClass('rb_display_search_default')) {
                if ( !$(this).closest('.rb_extra_item').hasClass('active') ){
                    $(this).closest('.rb_extra_item').addClass('active');
                    return false;
                } else {
                    if ($(this).prev('input').val() == 0){
                        $('.rb_extra_item').removeClass('active');
                        return false;
                    }
                }
            } 
        });
    }

    displayHeightTab();

    if ($('.rb_megamenu.sticky_enabled').length > 0) {
        var sticky_navigation_offset_top = $('.rb_megamenu.sticky_enabled').offset().top;
        var headerFloatingHeight = $('.rb_megamenu.sticky_enabled').height()+($('#header').length > 0 ?
            parseInt($('.rb_megamenu.sticky_enabled').css('marginTop').replace('px',''))+
            parseInt($('.rb_megamenu.sticky_enabled').css('marginBottom').replace('px','')) : 0);

        var oldHeaderMarginBottom = $('#header').length > 0 ? parseInt($('#header').css('marginBottom').replace('px','')) : 0;
        var sticky_navigation = function() {
            if(!$('.rb_megamenu').hasClass('sticky_enabled'))
                return false;
            var scroll_top = $(window).scrollTop(); 
            if (scroll_top > sticky_navigation_offset_top) {
                $('.rb_megamenu.sticky_enabled').addClass('scroll_heading');
                if($('#header').length > 0)
                    $('#header').css({'marginBottom':headerFloatingHeight+'px'});
            } else {
                $('.rb_megamenu.sticky_enabled').removeClass('scroll_heading');
                if($('#header').length > 0)
                    $('#header').css({'marginBottom':oldHeaderMarginBottom+'px'});
            } 
        };

        sticky_navigation();

        $(window).scroll(function() {
            sticky_navigation();
        });

        if ($(window).width() < 768 && !$('body').hasClass('disable-sticky'))
            $('body').addClass('disable-sticky');

        $(window).on('resize',function(e){
            if ($(window).width() < 768 && !$('body').hasClass('disable-sticky'))
                $('body').addClass('disable-sticky');
            else
                if($(window).width() >= 768 && $('body').hasClass('disable-sticky'))
                    $('body').removeClass('disable-sticky');
            });
    }
     
    $(window).load(function(){
        if ($('.rb_mn_submenu_full_height').length > 0 ){
            var ver_sub_height = $('.rb_mn_submenu_full_height').height();
            $('.rb_mn_submenu_full_height').find('.rb_columns_ul').css("min-height",ver_sub_height);
        }
    });

    if ( $('.rb_columns_ul_tab_content').length > 0 && $('body#index').length >0 ){
        $('.rb_columns_ul_tab_content').addClass('active').prev('.arrow').removeClass('closed').addClass('opened');

    }
     
    $(window).resize(function(){
        $('.rb_menus_ul:not(.rb_all_show_resize)').removeClass('rb_mn_active');
    });

    $(document).on('click','.rb_has_sub > .arrow',function(){
        var wrapper = $(this).next('.rb_columns_ul');

        if ($(this).hasClass('closed')) {
            $('.rb_columns_ul').removeClass('active');
            $('.rb_has_sub > .arrow').removeClass('opened');
            $('.rb_has_sub > .arrow').addClass('closed');
            var btnObj = $(this); 
            btnObj.removeClass('closed');
            btnObj.addClass('opened');
            wrapper.stop(true,true).addClass('active');
        } else {
            var btnObj = $(this); 
            btnObj.removeClass('opened');
            btnObj.addClass('closed');       
                wrapper.stop(true,true).removeClass('active');
        }   
    });

     $('.transition_slide:not(.changestatus) li.rb_menus_li').hover(function(){
        if($(window).width() >= 768){
            $(this).find('.rb_columns_ul').stop(true,true).slideDown(300);
        }
    }, function(){
        if ($(window).width() >= 768) {
            $(this).find('.rb_columns_ul').stop(true,true).slideUp(0);
        }
    });
    
    $('.ybc-menu-toggle, .ybc-menu-vertical-button').on('click',function(){
        var wrapper = $(this).next('.rb_menus_ul');
        if ($(this).hasClass('closed')) {
            var btnObj = $(this); 
            btnObj.removeClass('closed');
            btnObj.addClass('opened');
            wrapper.stop(true,true).addClass('active');

            if ( $('.transition_slide.transition_default').length != '' ){
                wrapper.stop(true,true).slideDown(0);
            }
        } else {
            var btnObj = $(this); 
            btnObj.removeClass('opened');
            btnObj.addClass('closed');         
            wrapper.stop(true,true).removeClass('active');

            if ($('.transition_slide.transition_default').length != '' ) {
                wrapper.stop(true,true).slideUp(0);
            }
        }   
    });

    $('.close_menu').on('click',function(){
        $(this).parent().prev().removeClass('opened');
        $(this).parent().prev().addClass('closed');        
        $(this).parent().stop(true,true).removeClass('active');
      
    });

    //Active menu
    if ($('.rb_megamenu').hasClass('enable_active_menu') && $('.rb_menus_ul > li').length > 0) {
        var currentUrl = window.location.href;

        $('.rb_menus_ul > li').each(function(){
            if($(this).find('a[href="'+currentUrl+'"]').length > 0)
            {
                $(this).addClass('active');
                return false;
            }
        });
    }

    if ($('.rb_breaker').length > 0 && $('.rb_breaker').prev('li').length > 0) {
        $('.rb_breaker').prev('li').addClass('rb_before_breaker');
    }
    
    $('.rb_tab_li_content').hover(function(){
        if (!$(this).closest('.rb_tabs_li').hasClass('open'))
        {
            $(this).closest('.rb_columns_ul_tab').find('.rb_tabs_li').removeClass('open');
            $(this).closest('.rb_tabs_li').addClass('open');
            $(this).closest('.rb_columns_ul').removeClass('rb_tab_no_content');
            if ( !$(this).next('.rb_columns_contents_ul').length ){
                $(this).closest('.rb_columns_ul').addClass('rb_tab_no_content');
            }
            displayHeightTab();
        }
    });

    if ($('.clicktext_show_submenu').length <= 0)
    {
        $(document).on('click touchstar', '.rb_tab_li_content', function (evt) {
            var btnObj = $(this), wrapper = $(this).next();
            if (!btnObj.find('.rb_tab_toggle_title a').is(evt.target))
            {
                if(btnObj.hasClass('closed'))
                {
                    $('.rb_tab_li_content').removeClass('opened');
                    $('.rb_tab_li_content').addClass('closed');
                    $('.rb_columns_contents_ul').removeClass('active');
                    btnObj.removeClass('closed');
                    btnObj.addClass('opened');
                    wrapper.stop(true,true).addClass('active');
                }
                else
                {
                    btnObj.removeClass('opened');
                    btnObj.addClass('closed');
                    wrapper.stop(true,true).removeClass('active');
                }
        }
        });
    }

});
function autoChangeStatus()
{
    var width_ul_menu = $('ul.rb_menus_ul').width();
    var width_li_menu=0;
    $('ul.rb_menus_ul li.rb_menus_li').each(function(){
        width_li_menu += parseFloat($(this).width());
    });
    
    if(width_li_menu > width_ul_menu+5)
    {
        $('.rb_megamenu').addClass('changestatus'); 
        $('.menu_ver_alway_show_sub .rb_columns_ul_tab_content').removeClass('active');
        $('#index .menu_ver_alway_show_sub .arrow').removeClass('opened').addClass('closed');
    }
    else
    {
        $('.rb_megamenu').removeClass('changestatus');
        if ( $(window).width() > 767 ){
            $('#index .menu_ver_alway_show_sub .arrow').addClass('opened').removeClass('closed');
            $('#index .menu_ver_alway_show_sub .rb_columns_ul_tab_content').addClass('active');
        }
    }
    if ( $(window).width() < 768 ){
        $('.menu_ver_alway_show_sub .rb_columns_ul_tab_content').removeClass('active');
        $('.menu_ver_alway_show_sub .arrow').removeClass('opened').addClass('closed');
    }
}

function itemClickMenu($this){
    var btnObj =  $($this).next('.arrow');
     var wrapper =  btnObj.next();
    if ( ! btnObj.length ){
        var btn_temp = $($this).closest('.rb_tab_li_content').first();
        var wrapper =  btn_temp.next();
        if( btn_temp.hasClass('closed')){
            $('.rb_tab_li_content').removeClass('opened');
                    $('.rb_tab_li_content').addClass('closed');
                    $('.rb_tab_li_content + .rb_columns_contents_ul').removeClass('active');
            btn_temp.removeClass('closed');
            btn_temp.addClass('opened');
            wrapper.stop(true,true).addClass('active');
            
                        
        }else{
            btn_temp.removeClass('opened');
            btn_temp.addClass('closed');      
            wrapper.stop(true,true).removeClass('active');
            
        }
        
    }else{
        if(btnObj.hasClass('closed'))
        {
            $('.rb_has_sub > .arrow').removeClass('opened');
            $('.rb_has_sub > .arrow').addClass('closed');      
            $('.rb_columns_ul').removeClass('active');
            
            btnObj.removeClass('closed');
            btnObj.addClass('opened');
            wrapper.stop(true,true).addClass('active');
        }
        else
        {
            btnObj.removeClass('opened');
            btnObj.addClass('closed');      
            wrapper.stop(true,true).removeClass('active');
        } 
    }
  
   
}
function clickTextShowMenu(){

    if ( $('.clicktext_show_submenu').length > 0 ){
         $('.clicktext_show_submenu li.has-sub').each(function() {
            $(this).find('a').first().on('click', function(e){
                if ($(window).width() <= 767 ){
                    e.preventDefault();
                   var btnObj =  $(this).next('.arrow');
                    var wrapper =  btnObj.next();
                   if(btnObj.hasClass('closed'))
                    {                        
                        btnObj.removeClass('closed');
                        btnObj.addClass('opened');
                        wrapper.stop(true,true).addClass('active');
                    }
                    else
                    {
                        btnObj.removeClass('opened');
                        btnObj.addClass('closed');      
                        wrapper.stop(true,true).removeClass('active');
                    } 
               } 
            });
            
        });
        
    }
    if ( $('.clicktext_show_submenu').length > 0 ){
        $('.clicktext_show_submenu li.has-sub').each(function() {
            $(this).find('a').first().on('click', function(e){
                
                if ( $('.rb_megamenu').hasClass('changestatus') && $(window).width() > 767 ){
                   e.preventDefault();
                   //itemClickMenu(this);
                   var btnObj =  $(this).next('.arrow');
                    var wrapper =  btnObj.next();
                   if(btnObj.hasClass('closed'))
                    {                        
                        btnObj.removeClass('closed');
                        btnObj.addClass('opened');
                        wrapper.stop(true,true).addClass('active');
                    }
                    else
                    {
                        btnObj.removeClass('opened');
                        btnObj.addClass('closed');      
                        wrapper.stop(true,true).removeClass('active');
                    } 
                }
            });
        });
    }
    
    if ( $('.clicktext_show_submenu').length > 0 ){
         $('.clicktext_show_submenu li.rb_tabs_has_content > div').each(function() {
            $(this).find('a').first().on('click', function(e){
                if ($(window).width() <= 767 ){
                    e.preventDefault();
                    itemClickMenu(this);
               } 
            });
            
        });
    }
    if ( $('.clicktext_show_submenu').length > 0 ){
        $('.clicktext_show_submenu li.rb_tabs_has_content > div').each(function() {
            $(this).find('a').first().on('click', function(e){
                if ( $('.rb_megamenu').hasClass('changestatus') && $(window).width() > 767 ){
                   e.preventDefault();
                   itemClickMenu(this);
                }
            });
        });
    }
        if ( $('.clicktext_show_submenu').length > 0 ){
         $('.clicktext_show_submenu li.rb_has_sub > a').each(function() {
            $(this).on('click', function(e){
                if ($(window).width() <= 767 ){
                    e.preventDefault();
                   itemClickMenu(this);
               } 
            });
            
        });
        
    }
    if ( $('.clicktext_show_submenu').length > 0 ){
        $('.clicktext_show_submenu li.rb_has_sub > a').each(function() {
            $(this).on('click', function(e){
                if ( $('.rb_megamenu').hasClass('changestatus') && $(window).width() > 767 ){
                   e.preventDefault();
                   itemClickMenu(this);
                }
            });
        });
    }
    if ( $('.clicktext_show_submenu').length > 0 ){
        $('.rb_tab_has_child > .rb_tab_toggle_title').on('click', function(e){
            if ( $(this).find('a').length <= 0 ){
                if ( $('.rb_megamenu').hasClass('changestatus') || $(window).width() > 767 ){
                   var btnObj = $(this).parents('.rb_tab_li_content'), wrapper = $(this).parents('.rb_tab_li_content').next();

                    if(btnObj.hasClass('closed'))
                    {
                        $('.rb_tab_li_content').removeClass('opened');
                        $('.rb_tab_li_content').addClass('closed');
                        $('.rb_tab_li_content + .rb_columns_contents_ul').removeClass('active');

                        btnObj.removeClass('closed');
                        btnObj.addClass('opened');
                        wrapper.stop(true,true).addClass('active');
                    }
                    else
                    {
                        btnObj.removeClass('opened');
                        btnObj.addClass('closed');
                        wrapper.stop(true,true).removeClass('active');
                    }
                }
            }
        });
    }
}

$(document).on('click','.rb_categories .has-sub .arrow',function(e){
        e.stopPropagation();
        var wrapper = $(this).next('.rb_categories');
        if($(this).hasClass('closed'))
        {
            var btnObj = $(this); 
            btnObj.removeClass('closed');
            btnObj.addClass('opened');
            wrapper.stop(true,true).addClass('active');
        }
        else
        {
            var btnObj = $(this); 
            btnObj.removeClass('opened');
            btnObj.addClass('closed');
            //btnObj.text('+');           
            wrapper.stop(true,true).removeClass('active');
        }
        
});

function displayHeightTab()
{
    if($('.rb_tabs_li.open .rb_columns_contents_ul').length)
    {
        $('.rb_tabs_li.open .rb_columns_contents_ul').each(function(){
           $(this).closest('.rb_columns_ul_tab').css('height', $(this).height('px')); 
        });
    }
}

$(document).ready(function(){
    var rb_ACTIVE_BG_GRAY = $('.rb_megamenu').attr('data-bggray');
    $('.rb_megamenu').removeClass('bg_submenu');
    if (typeof rb_ACTIVE_BG_GRAY !== "undefined" && rb_ACTIVE_BG_GRAY ) {
        $('.rb_megamenu .rb_menus_ul > li.rb_has_sub').mouseenter(function() {
            $('.rb_megamenu').addClass('bg_submenu');
        })
        .mouseleave(function() {
            $('.rb_megamenu').removeClass('bg_submenu');
        });
    }
});

$(document).ready(function(){
    autoChangeStatus();
    clickTextShowMenu();
    
    $(window).resize(function(){
        autoChangeStatus();
    });
});