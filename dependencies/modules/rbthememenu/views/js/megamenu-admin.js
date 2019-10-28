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
var rb_func = {
    search : function() {
        if ($('.rb_form .rb_product_ids').length > 0 && $('.rb_form .rb_search_product').length > 0 && typeof rbBaseAdminUrl !== "undefined")
        {
            var rb_autocomplete = $('.rb_form .rb_search_product');
            //var rb_product_ids = $('.rb_form .rb_product_ids').val();
            rb_autocomplete.autocomplete(rbBaseAdminUrl, {
                resultsClass: "rb_results",
                minChars: 1,
                delay: 300,
                appendTo: '.rb_form .rb_search_product_form',
                autoFill: false,
                max: 20,
                matchContains: false,
                mustMatch: false,
                scroll: true,
                cacheLength: 100,
                scrollHeight: 180,
                extraParams: {
                    excludeIds: $('.rb_form .rb_product_ids').val(),
                },
                formatItem: function (item) {
                    return '<span data-item-id="'+item[0]+'-'+item[1]+'" class="rb_item_title">' + (item[5] ? '<img src="'+item[5]+'" alt=""/> ' : '') + item[2] + (item[3]? item[3] : '') + (item[4] ? ' (Ref:' + item[4] + ')' : '') + '</span>';
                },
            }).result(function (event, data, formatted) {
                if (data)
                {
                    rb_func.addProduct(data, $('.rb_form .rb_product_ids'));
                }
                rb_autocomplete.val('');
                rb_func.closeSearch();
            });
        }
        $(document).on('click', '.rb_block_item_close', function () {
            if ($(this).parent('li').data('id') != '')
                rb_func.removeProduct($(this).parents('li').data('id'));
        });
        if ($('.rb_form .rb_products').length > 0) {
            rb_func.sortProductList();
        }
    },
    addProduct: function (data, rb_product_ids) {
        if ($('.rb_form .rb_products').length > 0)
        {
            if ($('.rb_form .rb_products .rb_product_loading.active').length <=0)
            {
                $('.rb_form .rb_products .rb_product_loading').addClass('active');
                $.ajax({
                    url: rbBaseAdminUrl,
                    data: {
                        ids : data[0] + '-' + data[1],
                        product_type : 'specific'
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(json)
                    {
                        if (json) 
                        {
                            $('.rb_form .rb_products .rb_product_loading.active').before(json.html);
                            if (!rb_product_ids.val()) 
                            {
                                rb_product_ids.val(data[0] + '-' + data[1]);
                            } 
                            else 
                            {
                                if (rb_product_ids.val().split(',').indexOf(data[0] + '-' + data[1]) == -1) 
                                {
                                    rb_product_ids.val(rb_product_ids.val() + ',' + data[0] + '-' + data[1]);

                                } 
                                else 
                                {
                                    showErrorMessage(data[2].toString() + ' has been tagged.');
                                }
                            }
                            //reset search
                            $('.rb_form .rb_search_product').unautocomplete();
                            rb_func.search();
                        }
                        $('.rb_form .rb_products .rb_product_loading.active').removeClass('active');

                    },
                    error: function(xhr, status, error)
                    {
                        $('.rb_form .rb_products .rb_product_loading.active').removeClass('active');
                    }
                });
            }
        }
    },
    removeIds: function (parent, element) {
        var ax = -1;
        if ((ax = parent.indexOf(element)) !== -1)
            parent.splice(ax, 1);
        return parent;
    },
    removeProduct : function(ID) {
        if ($('.rb_form .rb_products').length > 0 && $('.rb_form .rb_products .rb_product_item').length > 0 && $('.rb_form li.rb_product_item[data-id="'+ID+'"]').length >0 && $('.rb_form .rb_product_ids').length > 0)
        {
            $('.rb_form li.rb_product_item[data-id="'+ID+'"]').remove();
            if (!$('.rb_form li.rb_product_item[data-id="'+ID+'"]').length)
            {
                var IDs = $('.rb_form .rb_product_ids').val().split(',');
                $('.rb_form .rb_product_ids').val(rb_func.removeIds(IDs, ID));
            }
        }
    },
    closeSearch: function () {
        $('.rb_form .rb_search_product').val('');
        if ($('.ybc_ins_results').length > 0)
            $('.ybc_ins_results').hide();
    },
    sortProductList: function () {
        $('.rb_form .rb_products').sortable({
            update: function (e, ui) {
                if (this === ui.item.parent()[0])
                {
                    var $sort = '';
                    $('.rb_form .rb_products .rb_product_item').each(function () {
                        $sort += $(this).data('id') + ',';
                    });
                    if ($sort && $('.rb_form .rb_product_ids').length > 0)
                        $('.rb_form .rb_product_ids').val($sort);
                }
            }
        }).disableSelection();
    },
}
$(document).ready(function(){
    if($('.rb_menus_li.open .rb_tabs_ul .rb_tabs_li.open .rb_columns_ul').length)
    {
       $('.rb_menus_li.open .rb_tabs_ul').css('height',($('.rb_menus_li.open .rb_tabs_ul .rb_tabs_li.open .rb_columns_ul').height()+300)+'px') 
    }
    $(window).on('load',function(){
        displayHeightTab();
        displayCountDownClock();
    });
    if($('input[name="RBTHEMEMENU_DISPLAY_SEARCH"]:checked').val()==1)
    {
        $('.form-group.rb_form_display_search').show();
    }
    else
        $('.form-group.rb_form_display_search').hide();
    $(document).on('click','input[name="RBTHEMEMENU_DISPLAY_SEARCH"]',function(){
        if($('input[name="RBTHEMEMENU_DISPLAY_SEARCH"]:checked').val()==1)
        {
            $('.form-group.rb_form_display_search').show();
        }
        else
            $('.form-group.rb_form_display_search').hide();
    });
    $(document).on('click', '#awesome-icon .rb_icon', function () {
        if ($('.dummyfile > input.rb_browse_icon').length > 0)
        {
            $('.dummyfile > input.rb_browse_icon').val($(this).data('icon'));
            $('.rb_pop_up .rb_close').click();
            $('.dummyfile > input.rb_browse_icon').focus();
        }
    });
    $(document).on('click', '.rb_browse_icon button[type="button"]', function () {
        if ($('.rb_menu_form.rb_pop_up').length > 0 && !$('.rb_menu_form.rb_pop_up').hasClass('hidden') && $('.rb_icon_form_new').length > 0)
        {
            $('.rb_menu_form.rb_pop_up').addClass('hidden').removeClass('rb_pop_up');
            $('.rb_icon_form_new').removeClass('hidden').addClass('rb_pop_up');
        }
    });
   $(document).on('click','.rb_add_menu',function(){
        $('.rb_pop_up').addClass('hidden');
        $('.rb_menu_form').removeClass('hidden');   
        $('.rb_forms').removeClass('hidden').parents('.rb_popup_overlay').removeClass('hidden'); 
        if($('.rb_menu_form .rb_form form input[name="itemId"]').length <= 0 || $('.rb_menu_form .rb_form form input[name="rb_object"]') != 'RbMegaMenu'  || $('.rb_menu_form .rb_form form input[name="itemId"]').length > 0 && parseInt($('.rb_menu_form .rb_form form input[name="itemId"]').val())!=0)
            $('.rb_menu_form .rb_form').html($('.rb_menu_form_new').html());
        checkFormFields();
        $('.rb-alert').remove();
        return false;     
   }); 
   $(document).on('click','.checkbox_all input',function(){
        if($(this).is(':checked'))
        {
            $(this).closest('.form-group').find('input').attr('checked','checked');
        }
        else
        {
            $(this).closest('.form-group').find('input').removeAttr('checked');
        }
   });
   $(document).on('click','.checkbox input',function(){
        if($(this).is(':checked'))
        {
            if($(this).closest('.form-group').find('input:checked').length==$(this).closest('.form-group').find('input').length-1)
                 $(this).closest('.form-group').find('.checkbox_all input').attr('checked','checked');
        }
        else
        {
            $(this).closest('.form-group').find('.checkbox_all input').removeAttr('checked');
        } 
   });
   $(document).on('click','input[name="RBTHEMEMENU_VERTICAL_ENABLED"],input[name="RBTHEMEMENU_STICKY_ENABLED"],input[name="display_mnu_img"],input[name="display_suppliers_img"]',function(){
        checkFormFields();
   });
   $(document).on('change','select[name="enabled_vertical"],select[name="sub_menu_type"]',function(){
        checkFormFields();
   });
   $(document).on('click','.rb_import_button',function(){
        $(this).parents('.rb_pop_up').addClass('hidden');
        $(this).parents('.rb_forms').addClass('hidden');
        $('.rb_export_form').removeClass('hidden');
        $('.rb_export.rb_pop_up').removeClass('hidden');
   });
   $(document).on('click','.rb_menu_toggle',function(){
        if(!$(this).parents('.rb_menus_li').eq(0).hasClass('open'))
        {
            $('.rb_menus_li').removeClass('open');
            $(this).parents('.rb_menus_li').eq(0).addClass('open');
            if($('.rb_menus_li.open .rb_tabs_li').length>0)
            {
                $('.rb_menus_li.open .rb_tabs_li').removeClass('open');
                $('.rb_menus_li.open .rb_tabs_li:first-child').addClass('open');
            } 
            displayHeightTab();  
        }
   });
   $(document).on('click','.rb_tab_toggle',function(){
        if(!$(this).parents('.rb_tabs_li').eq(0).hasClass('open'))
        {
            $('.rb_tabs_li').removeClass('open');
            $(this).parents('.rb_tabs_li').eq(0).addClass('open');  
            displayHeightTab(); 
        }
   });

   $(document).on('click','.rb_save',function(){
        tinyMCE.triggerSave();
        if(!$(this).parents('form').eq(0).hasClass('active') && $('.defaultForm.active').length <= 0)
        {
            $(this).parents('form').eq(0).addClass('active');
            $(this).parents('.rb_save_wrapper').eq(0).addClass('loading');
            $('.rb-alert').remove();
            var formData = new FormData($(this).parents('form').get(0));
            if($('.defaultForm.active input[type="file"]').length > 0)
            {
                $('.defaultForm.active input[type="file"]').each(function(){
                    if (document.getElementById($(this).attr('id')).files.length == 0 ) {
                          formData.delete($(this).attr('id'));
                    }
                });
            }       
            $.ajax({
                url: $(this).parents('form').eq(0).attr('action'),
                data: formData,
                type: 'post',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(json){
                    showSaveMessage(json.alert);
                    $('.rb_save_wrapper').removeClass('loading');                    
                    if(json.images && json.success)
                    {
                       $.each(json.images,function(i,item){
                            if($('.defaultForm.active input[name="'+item.name+'"]').length > 0)
                            {
                                updatePreviewImage(item.name,item.url,item.delete_url);
                            }
                       });
                    }
                    if(json.itemId && json.itemKey && json.success)
                    {
                        $('.defaultForm.active input[name="'+json.itemKey+'"]').val(json.itemId);
                        $('.defaultForm.active input[name="itemId"]').val(json.itemId);
                    }
                    if(json.rb_object=='RbMegaMenu' && json.success && json.title)
                    {                        
                        if($('.rb_menus ul').length <= 0)
                        {
                            $('.rb_menus').append('<ul class="rb_menus_ul"></ul>');
                            //Sortable  
                            rbSort('.rb_menus_ul'); 
                        }                            
                        if($('.rb_menus > ul.rb_menus_ul > li.item'+json.itemId).length <=0 )
                        {
                            $('.rb_menus_li').removeClass('open');
                            $('.rb_menus > ul.rb_menus_ul').append('<li class="rb_menus_li '+(!json.vals.enabled ? ' rb_disabled ' : '')+' item'+json.itemId+' open" data-id-menu="'+json.itemId+'" data-obj="menu">'+json.vals.html_content+'</li>');   
                            $('.rb_form form .panel-heading').html(rbEditMenuTxt);
                            rbSort('.rb_tabs_ul_content');
                            rbSort('.rb_columns_ul'); 
                            rbSort('.rb_blocks_ul');                           
                        }                            
                        else
                        {
                            $('.rb_menus > ul.rb_menus_ul > li.item'+json.itemId).html(json.vals.html_content);
                            rbSort('.rb_tabs_ul_content');
                            rbSort('.rb_columns_ul');
                            rbSort('.rb_blocks_ul');  
                        }
                        if($('.rb_menus_li.open .rb_tabs_li').length>0)
                        {
                            $('.rb_menus_li.open .rb_tabs_li:first-child').addClass('open');
                        }                                                    
                    } 
                    if(json.rb_object=='RbMenuTab' && json.success)
                    {
                        if($('.rb_menus_li.item'+json.vals.id_menu+' > div.rb_tabs_ul > ul.rb_tabs_ul_content').length <= 0)
                        {
                            $('.rb_menus_li.item'+json.vals.id_menu).append('<div class="rb_tabs_ul"><ul class="rb_tabs_ul_content"></ul></div>');
                            //Sortable                            
                            rbSort('.rb_tabs_ul_content'); 
                        }                            
                        if($('.rb_menus_li.item'+json.vals.id_menu+' > div.rb_tabs_ul > ul.rb_tabs_ul_content > li.item'+json.itemId).length <=0 )
                        {
                            $('.rb_tabs_li').removeClass('open');
                            $('.rb_menus_li.item'+json.vals.id_menu+' > div.rb_tabs_ul > ul.rb_tabs_ul_content').append('<li class="rb_tabs_li item'+json.itemId+(!json.vals.enabled ? ' rb_disabled ' : '')+' open" data-id-tab="'+json.itemId+'" data-obj="tab">'+json.vals.html_content+'</li>');
                            $('.rb_form form .panel-heading').html(rbEditColumnTxt);
                            rbSort('.rb_tabs_ul_content');
                            rbSort('.rb_columns_ul'); 
                        }                            
                        else
                        {
                            $('ul.rb_tabs_ul_content > li.item'+json.itemId).html(json.vals.html_content);
                            rbSort('.rb_tabs_ul_content');
                            rbSort('.rb_columns_ul');
                            rbSort('.rb_blocks_ul'); 
                            
                        }    
                    }                    
                    if(json.rb_object=='RbMenuColumn' && json.success)
                    {
                        if($('.rb_menus_li.item'+json.vals.id_menu+' > div.rb_tabs_ul > ul.rb_tabs_ul_content').length<=0)
                        {
                            if($('.rb_menus_li.item'+json.vals.id_menu+' > ul.rb_columns_ul').length <= 0)
                            {
                                $('.rb_menus_li.item'+json.vals.id_menu).append('<ul class="rb_columns_ul"></ul>');
                                //Sortable    
                                rbSort('.rb_tabs_ul_content');                        
                                rbSort('.rb_columns_ul'); 
                            }                            
                            if($('.rb_menus_li.item'+json.vals.id_menu+' > ul.rb_columns_ul > li.item'+json.itemId).length <=0 )
                            {
                                $('.rb_menus_li.item'+json.vals.id_menu+' > ul.rb_columns_ul').append('<li class="rb_columns_li item'+json.itemId+' column_size_'+json.vals.column_size+' '+(json.vals.is_breaker ? 'rb_breaker' : '')+'" data-id-column="'+json.itemId+'" data-obj="column">'+json.vals.html_content+'</li>');
                                $('.rb_form form .panel-heading').html(rbEditColumnTxt);
                                rbSort('.rb_blocks_ul'); 
                            }                            
                            else
                                $('.rb_menus_li.item'+json.vals.id_menu+' > ul.rb_columns_ul > li.item'+json.itemId).attr('class','rb_columns_li item'+json.itemId+' column_size_'+json.vals.column_size+' '+(json.vals.is_breaker ? 'rb_breaker' : ''));                           
                        }
                        else
                        {
                            if($('.rb_tabs_li.item'+json.vals.id_tab+' > ul.rb_columns_ul').length <= 0)
                            {
                                $('.rb_tabs_li.item'+json.vals.id_tab).append('<ul class="rb_columns_ul"></ul>');
                                //Sortable    
                                rbSort('.rb_tabs_ul_content');                        
                                rbSort('.rb_columns_ul'); 
                            }                            
                            if($('.rb_tabs_li.item'+json.vals.id_tab+' > ul.rb_columns_ul > li.item'+json.itemId).length <=0 )
                            {
                                $('.rb_tabs_li.item'+json.vals.id_tab+' > ul.rb_columns_ul').append('<li class="rb_columns_li item'+json.itemId+' column_size_'+json.vals.column_size+' '+(json.vals.is_breaker ? 'rb_breaker' : '')+'" data-id-column="'+json.itemId+'" data-obj="column">'+json.vals.html_content+'</li>');
                                $('.rb_form form .panel-heading').html(rbEditColumnTxt);
                                rbSort('.rb_blocks_ul'); 
                            }                            
                            else
                                $('.rb_tabs_li.item'+json.vals.id_tab+' > ul.rb_columns_ul > li.item'+json.itemId).attr('class','rb_columns_li item'+json.itemId+' column_size_'+json.vals.column_size+' '+(json.vals.is_breaker ? 'rb_breaker' : ''));
                        }
                    } 
                    if(json.rb_object=='RbMenuBlock' && json.success && json.vals.blockHtml)
                    {
                        if($('.rb_columns_li.item'+json.vals.id_column+' > ul.rb_blocks_ul').length <= 0)
                        {
                            $('.rb_columns_li.item'+json.vals.id_column).append('<ul class="rb_blocks_ul"></ul>');
                            //Sortable                            
                            rbSort('.rb_blocks_ul'); 
                        }                            
                        if($('.rb_columns_li.item'+json.vals.id_column+' > ul.rb_blocks_ul > li.item'+json.itemId).length <=0 )
                        {
                            $('.rb_columns_li.item'+json.vals.id_column+' > ul.rb_blocks_ul').append('<li class="rb_blocks_li '+(!json.vals.enabled ? ' rb_disabled ' : '')+' item'+json.itemId+'" data-id-block="'+json.itemId+'" data-obj="block">'+'<div class="rb_buttons"><span class="rb_block_delete" title="'+rbDeleteBlockTxt+'">'+rbDeleteTxt+'</span><span class="rb_duplicate" title="'+rbDuplicateBlockTxt+'">'+rbDuplicateTxt+'</span><span class="rb_block_edit" title="'+rbEditBlockTxt+'">'+rbEditTxt+'</span></div><div class="rb_block_wrapper">'+json.vals.blockHtml+'</div></li>');
                            $('.rb_form form .panel-heading').html(rbEditBlockTxt);
                        }                            
                        else
                        {
                            $('.rb_columns_li.item'+json.vals.id_column+' > ul.rb_blocks_ul > li.item'+json.itemId + ' .rb_block_wrapper').html(json.vals.blockHtml);
                            if(json.vals.enabled)
                                $('.rb_columns_li.item'+json.vals.id_column+' > ul.rb_blocks_ul > li.item'+json.itemId).removeClass('rb_disabled');
                            else
                                $('.rb_columns_li.item'+json.vals.id_column+' > ul.rb_blocks_ul > li.item'+json.itemId).addClass('rb_disabled');
                        }                            
                    }                    
                    $('.defaultForm.active').removeClass('active');
                    if(json.success)
                    {
                        rbAlertSucccess($('.rb_menu_form .alert-success').html());
                        $('.rb_pop_up').addClass('hidden').parents('.rb_forms').addClass('hidden').parents('.rb_popup_overlay').addClass('hidden');
                    } 
                    displayHeightTab();   
                    var $images = $('.rbthememenu img');
                    $images.on('load',function(){
                        displayHeightTab();
                    });
                    displayCountDownClock();                
                },
                error: function(xhr, status, error)
                {
                    $('.defaultForm.active').removeClass('active');
                    $('.rb_save_wrapper').removeClass('loading'); 
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);                    
                }
            });   
        } 
        return false;       
   });
   $(document).on('click','.rb_close',function(){
       if ($('.rb_icon_form_new').hasClass('rb_pop_up'))
       {
           $('.rb_icon_form_new').removeClass('rb_pop_up').addClass('hidden');
           $('.rb_menu_form').removeClass('hidden').addClass('rb_pop_up');
       }
       else
       {
           $(this).parents('.rb_pop_up').addClass('hidden').parents('.rb_popup_overlay').addClass('hidden');
           $(this).parents('.rb_forms').addClass('hidden');
       }
       $('.rb_export_form').addClass('hidden');
   });
   $(document).on('change','input[type="file"]',function(){
        var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp','zip'];
        
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            $(this).val('');
            if($(this).next('.dummyfile').length > 0)
            {
                $(this).next('.dummyfile').eq(0).find('input[type="text"]').val('');
            }
            if($(this).parents('.col-lg-9').eq(0).find('.preview_img').length > 0)
                $(this).parents('.col-lg-9').eq(0).find('.preview_img').eq(0).remove(); 
            if($(this).parents('.col-lg-9').eq(0).next('.uploaded_image_label').length > 0)
            {
                $(this).parents('.col-lg-9').eq(0).next('.uploaded_image_label').removeClass('hidden');
                $(this).parents('.col-lg-9').eq(0).next('.uploaded_image_label').next('.uploaded_img_wrapper').removeClass('hidden');
            }            
            alert(rb_invalid_file);
        }
        else
        {
            readURL(this);            
        }       
    });
    $(document).on('click','.del_preview',function(){
        var $this=$(this);
        var field_name=''
        var object_name='';
        var idItem=0;
        $(this).parents('form').eq(0).addClass('active');
        if($(this).parents('.col-lg-9').eq(0).find('input[type="file"]').length > 0)
        {
            var field_name=$(this).parents('.col-lg-9').eq(0).find('input[type="file"]').eq(0).attr('name');
        }
        if($(this).parents('form').eq(0).find('input[name="rb_object"]').length > 0)
        {
            var object_name=$(this).parents('form').eq(0).find('input[name="rb_object"]').eq(0).val();
        }
        if($(this).parents('form').eq(0).find('input[name="itemId"]').length > 0)
        {
            var idItem=$(this).parents('form').eq(0).find('input[name="itemId"]').eq(0).val();
        }
        if(field_name && object_name)
        {
            $.ajax({
                url: '',
                data: 'deleteimage='+field_name+'&itemId='+idItem+'&rb_object='+object_name,
                type: 'post',
                dataType: 'json',                
                success: function(json){
                    showSaveMessage(json.alert);   
                    if($this.parents('.col-lg-9').eq(0).next('.uploaded_image_label').length > 0)
                    {
                        $this.parents('.col-lg-9').eq(0).next('.uploaded_image_label').removeClass('hidden');
                        $this.parents('.col-lg-9').eq(0).next('.uploaded_image_label').next('.uploaded_img_wrapper').removeClass('hidden');
                    }
                    $this.parents('.col-lg-9').eq(0).find('.dummyfile input[type="text"]').val('');
                    if($this.parents('.col-lg-9').eq(0).find('input[type="file"]').length > 0)
                    {
                        $this.parents('.col-lg-9').eq(0).find('input[type="file"]').eq(0).val('');
                    }  
                    $this.parents('.preview_img').remove();                 
                    $('.defaultForm.active').removeClass('active');
                },
                error: function(xhr, status, error)
                {
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                    $('.defaultForm.active').removeClass('active');
                }
            });
        }
    });
    $(document).on('click','.delete_url',function(){
        var delLink = $(this);
        if(!$(this).parents('form').eq(0).hasClass('active') && $('.defaultForm.active').length <= 0)
        {
            $(this).parents('form').eq(0).addClass('active');
            $.ajax({
                url: $(this).attr('href'),
                data: {},
                type: 'post',
                dataType: 'json',                
                success: function(json){
                    showSaveMessage(json.alert);   
                    if(json.success)
                    {
                        delLink.parents('.uploaded_img_wrapper').eq(0).prev('.uploaded_image_label').eq(0).remove();
                        delLink.parents('.uploaded_img_wrapper').eq(0).remove();
                    }                 
                    $('.defaultForm.active').removeClass('active');
                },
                error: function(xhr, status, error)
                {
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                    $('.defaultForm.active').removeClass('active');
                }
            });
        }
        return false;
    });

    $(document).on('click','.rb_menu_edit',function(){
        if(!$(this).hasClass('active'))
        {
            $(this).addClass('active');
            $('.rbthememenu').addClass('loading-form');            
            $('.rb-alert').remove();
            $.ajax({
                url: rbBaseAdminUrl,
                dataType: 'json',
                type: 'post',
                data: {
                    itemId: $(this).parents('li').eq(0).data('id-menu'),
                    request_form: 1,
                    rb_object: 'RbMegaMenu',                
                },
                success: function(json){
                    showSaveMessage(json.alert);  
                    $('.rb_pop_up').addClass('hidden'); 
                    $('.rb_forms').removeClass('hidden');
                    $('.rb_menu_form').removeClass('hidden');
                    $('.rb_menu_form .rb_form').html(json.form);
                    checkFormFields();
                    $('.rb_menu_form .rb_form .mColorPickerInput').mColorPicker();
                    $('.rb_menus_li.item'+json.itemId+' .rb_menu_edit').removeClass('active');
                    $('.rb_menus_li').removeClass('open');
                    $('.rb_menus_li.item'+json.itemId).addClass('open');
                    $('.rbthememenu').removeClass('loading-form');
                },
                error: function(xhr, status, error)
                {
                    $('.rb_menu_edit').removeClass('active');
                    $('.rbthememenu').removeClass('loading-form');
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                }
            }); 
        }               
    });
    $(document).on('click','.rb_menu_delete',function(){
            if(!$(this).hasClass('active'))
            {
                $(this).addClass('active');
                $.ajax({
                    url: rbBaseAdminUrl,
                    dataType: 'json',
                    type: 'post',
                    data: {
                        itemId: $(this).parents('li').eq(0).data('id-menu'),
                        deleteobject: 1,
                        rb_object: 'RbMegaMenu',                
                    },
                    success: function(json){
                        if(json.success)
                        {
                            if($('.rb_menus_li.item'+json.itemId).hasClass('open'))
                            {
                                if($('.rb_menus_li.item'+json.itemId).prev('li').length > 0)
                                    $('.rb_menus_li.item'+json.itemId).prev('li').addClass('open');
                                else 
                                if($('.rb_menus_li.item'+json.itemId).next('li').length > 0)
                                    $('.rb_menus_li.item'+json.itemId).next('li').addClass('open');
                            }                            
                            $('.rb_menus_li.item'+json.itemId).remove();
                            rbAlertSucccess(json.successMsg); 
                        }                            
                        else
                            $('.rb_menus_li.item'+json.itemId+' .rb_menu_delete').removeClass('active');
                        displayHeightTab();
                    },
                    error: function(xhr, status, error)
                    {
                        $('.rb_menu_delete').removeClass('active');
                        var err = eval("(" + xhr.responseText + ")");
                        alert(err.Message);
                    }
                });
            }            
        return false;
    });
    
    //Column
    
    $(document).on('click','.rb_add_column',function(){  
        $('.rb_pop_up').addClass('hidden');
        $('.rb_forms').removeClass('hidden');
        $('.rb_menu_form').removeClass('hidden');   
        if($('.rb_menu_form .rb_form form input[name="itemId"]').length <= 0 || $('.rb_menu_form .rb_form form input[name="rb_object"]')!='RbMenuColumn'  || $('.rb_menu_form .rb_form form input[name="itemId"]').length > 0 && (parseInt($('.rb_menu_form .rb_form form input[name="itemId"]').val())!=0 || parseInt($('.rb_menu_form .rb_form form input[name="itemId"]').val())==0 && parseInt($('.rb_menu_form .rb_form form input[name="id_menu"]').val()))!=parseInt($(this).attr('data-id-menu')))
        {
            $('.rb_menu_form .rb_form').html($('.rb_column_form_new').html()); 
            $('.rb_menu_form .rb_form form input[name="id_menu"]').val($(this).attr('data-id-menu')); 
            $('.rb_menu_form .rb_form form input[name="id_tab"]').val(parseInt($(this).attr('data-id-tab')));             
        }
        $('.rb-alert').remove();
        return false;     
    }); 
    $(document).on('click','.rb_add_tab',function(){  
        $('.rb_pop_up').addClass('hidden');
        $('.rb_forms').removeClass('hidden');
        $('.rb_menu_form').removeClass('hidden');   
        if($('.rb_menu_form .rb_form form input[name="itemId"]').length <= 0 || $('.rb_menu_form .rb_form form input[name="rb_object"]')!='RbMenuTab'  || $('.rb_menu_form .rb_form form input[name="itemId"]').length > 0 && (parseInt($('.rb_menu_form .rb_form form input[name="itemId"]').val())!=0 || parseInt($('.rb_menu_form .rb_form form input[name="itemId"]').val())==0 && parseInt($('.rb_menu_form .rb_form form input[name="id_menu"]').val()))!=parseInt($(this).attr('data-id-menu')))
        {
            $('.rb_menu_form .rb_form').html($('.rb_tab_form_new').html()); 
            $('.rb_menu_form .rb_form form input[name="id_menu"]').val($(this).attr('data-id-menu'));              
        }
        $('.rb-alert').remove();
        return false;     
    });
    $(document).on('click','.rb_tab_delete',function(){
            if(!$(this).hasClass('active'))
            {
                $(this).addClass('active');
                $.ajax({
                    url: rbBaseAdminUrl,
                    dataType: 'json',
                    type: 'post',
                    data: {
                        itemId: $(this).parents('li').eq(0).data('id-tab'),
                        deleteobject: 1,
                        rb_object: 'RbMenuTab',                
                    },
                    success: function(json){
                        if(json.success)
                        {
                            if($('.rb_tabs_li.item'+json.itemId).hasClass('open'))
                            {
                                if($('.rb_tabs_li.item'+json.itemId).prev('li').length > 0)
                                    $('.rb_tabs_li.item'+json.itemId).prev('li').addClass('open');
                                else 
                                if($('.rb_tabs_li.item'+json.itemId).next('li').length > 0)
                                    $('.rb_tabs_li.item'+json.itemId).next('li').addClass('open');
                            }                            
                            $('.rb_tabs_li.item'+json.itemId).remove();
                            rbAlertSucccess(json.successMsg); 
                        }                            
                        else
                            $('.rb_tabs_li.item'+json.itemId+' .rb_tab_delete').removeClass('active');
                        displayHeightTab();
                    },
                    error: function(xhr, status, error)
                    {
                        $('.rb_menu_delete').removeClass('active');
                        var err = eval("(" + xhr.responseText + ")");
                        alert(err.Message);
                    }
                });
            }            
        return false;
    });    
    $(document).on('click','.rb_column_delete',function(){
            if(!$(this).hasClass('active'))
            {
                $(this).addClass('active');
                $.ajax({
                    url: rbBaseAdminUrl,
                    dataType: 'json',
                    type: 'post',
                    data: {
                        itemId: $(this).parents('li').eq(0).data('id-column'),
                        deleteobject: 1,
                        rb_object: 'RbMenuColumn',                
                    },
                    success: function(json){
                        if(json.success)
                        {
                            $('.rb_columns_li.item'+json.itemId).remove();
                            rbAlertSucccess(json.successMsg);
                        }                            
                        else
                            $('.rb_columns_li.item'+json.itemId+' .rb_column_delete').removeClass('active');
                        displayHeightTab();
                    },
                    error: function(xhr, status, error)
                    {
                        $('.rb_column_delete').removeClass('active');
                        var err = eval("(" + xhr.responseText + ")");
                        alert(err.Message);
                    }
                });
            }            
        return false;
    });
    $(document).on('click','.rb_column_edit',function(){
        if(!$(this).hasClass('active'))
        {
            $('.rbthememenu').addClass('loading-form');
            $(this).addClass('active');
            $('.rb-alert').remove();
            $.ajax({
                url: rbBaseAdminUrl,
                dataType: 'json',
                type: 'post',
                data: {
                    itemId: $(this).parents('li').eq(0).data('id-column'),
                    request_form: 1,
                    rb_object: 'RbMenuColumn',                
                },
                success: function(json){       
                    $('.rb_pop_up').addClass('hidden');
                    $('.rb_forms').removeClass('hidden');
                    $('.rb_menu_form').removeClass('hidden');
                    $('.rb_menu_form .rb_form').html(json.form);
                    checkFormFields();
                    $('.rb_menu_form .rb_form .mColorPickerInput').mColorPicker();
                    $('.rb_columns_li.item'+json.itemId+' .rb_column_edit').removeClass('active');
                    $('.rbthememenu').removeClass('loading-form');
                },
                error: function(xhr, status, error)
                {
                    $('.rb_column_edit').removeClass('active');
                    $('.rbthememenu').removeClass('loading-form');
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                }
            }); 
        }               
    });
    $(document).on('click','.rb_tab_edit',function(){
        if(!$(this).hasClass('active'))
        {
            $('.rbthememenu').addClass('loading-form');
            $(this).addClass('active');
            $('.rb-alert').remove();
            $.ajax({
                url: rbBaseAdminUrl,
                dataType: 'json',
                type: 'post',
                data: {
                    itemId: $(this).parents('li').eq(0).data('id-tab'),
                    request_form: 1,
                    rb_object: 'RbMenuTab',                
                },
                success: function(json){       
                    $('.rb_pop_up').addClass('hidden');
                    $('.rb_forms').removeClass('hidden');
                    $('.rb_menu_form').removeClass('hidden');
                    $('.rb_menu_form .rb_form').html(json.form);
                    checkFormFields();
                    $('.rb_menu_form .rb_form .mColorPickerInput').mColorPicker();
                    $('.rb_tabs_li.item'+json.itemId+' .rb_tab_edit').removeClass('active');
                    $('.rbthememenu').removeClass('loading-form');
                },
                error: function(xhr, status, error)
                {
                    $('.rb_tab_edit').removeClass('active');
                    $('.rbthememenu').removeClass('loading-form');
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                }
            }); 
        }               
    });
    //Block
    $(document).on('click','.rb_add_block',function(){ 
        $('.rb_pop_up').addClass('hidden');
        $('.rb_menu_form').removeClass('hidden');
        $('.rb_forms').removeClass('hidden');   
        if($('.rb_menu_form .rb_form form input[name="itemId"]').length <= 0 || $('.rb_menu_form .rb_form form input[name="rb_object"]')!='RbMenuBlock'  || $('.rb_menu_form .rb_form form input[name="itemId"]').length > 0 && (parseInt($('.rb_menu_form .rb_form form input[name="itemId"]').val())!=0 || parseInt($('.rb_menu_form .rb_form form input[name="itemId"]').val())==0 && parseInt($('.rb_menu_form .rb_form form input[name="id_column"]').val()))!=parseInt($(this).attr('data-id-column')))
        {
            $('.rb_menu_form .rb_form').html($('.rb_block_form_new').html()); 
            $('.rb_menu_form .rb_form form input[name="id_column"]').val($(this).attr('data-id-column')); 
            checkFormFields();
        }
        $('.rb-alert').remove();
        return false;     
    }); 
    $(document).on('click','.rb_block_edit',function(){
        if(!$(this).hasClass('active'))
        {
            $(this).addClass('active');
            $('.rbthememenu').addClass('loading-form');
            $('.rb-alert').remove();
            $.ajax({
                url: rbBaseAdminUrl,
                dataType: 'json',
                type: 'post',
                data: {
                    itemId: $(this).parents('li').eq(0).data('id-block'),
                    request_form: 1,
                    rb_object: 'RbMenuBlock',                
                },
                success: function(json){        
                    $('.rb_pop_up').addClass('hidden');
                    $('.rb_forms').removeClass('hidden');  
                    $('.rb_menu_form').removeClass('hidden');
                    $('.rb_menu_form .rb_form').html(json.form);
                    checkFormFields();
                    if ($('#block_type').length > 0 && $('#block_type').val() == 'PRODUCT')
                    {
                        rb_func.search();
                    }
                    $('.rb_menu_form .rb_form .mColorPickerInput').mColorPicker();
                    $('.rb_blocks_li.item'+json.itemId+' .rb_block_edit').removeClass('active');
                    $('.rbthememenu').removeClass('loading-form');
                },
                error: function(xhr, status, error)
                {
                    $('.rb_block_edit').removeClass('active');
                    $('.rbthememenu').removeClass('loading-form');
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                }
            }); 
        }               
    });
    $(document).on('click','.rb_block_delete',function(){
            if(!$(this).hasClass('active'))
            {
                $(this).addClass('active');
                $.ajax({
                    url: rbBaseAdminUrl,
                    dataType: 'json',
                    type: 'post',
                    data: {
                        itemId: $(this).parents('li').eq(0).data('id-block'),
                        deleteobject: 1,
                        rb_object: 'RbMenuBlock',                
                    },
                    success: function(json){
                        if(json.success)
                        {
                            $('.rb_blocks_li.item'+json.itemId).remove();
                            rbAlertSucccess(json.successMsg);
                        }                            
                        else
                            $('.rb_blocks_li.item'+json.itemId+' .rb_block_delete').removeClass('active');
                        displayHeightTab();
                    },
                    error: function(xhr, status, error)
                    {
                        $('.rb_block_delete').removeClass('active');
                        var err = eval("(" + xhr.responseText + ")");
                        alert(err.Message);
                    }
                });
            }            
        return false;
    });
    
    //Duplicate
    $(document).on('click','.rb_duplicate',function(){
            if(!$(this).hasClass('active'))
            {
                $(this).addClass('active');
                var rb_object = $(this).parents('li').eq(0).data('obj');
                var itemId = 0;

                if (rb_object=='menu') {
                    rb_object = 'RbMegaMenu';
                    itemId = $(this).parents('li').eq(0).data('id-menu');
                } else if (rb_object=='column') {
                    rb_object = 'RbMenuColumn';
                    itemId = $(this).parents('li').eq(0).data('id-column');
                } else if (rb_object=='tab') {
                    rb_object = 'RbMenuTab';
                    itemId = $(this).parents('li').eq(0).data('id-tab');
                } else {
                    rb_object = 'RbMenuBlock';
                    itemId = $(this).parents('li').eq(0).data('id-block');
                }

                $.ajax({
                    url: rbBaseAdminUrl,
                    dataType: 'json',
                    type: 'post',
                    data: {
                        itemId: itemId,
                        duplicateItem: 1,
                        rb_object: rb_object,                
                    }, 
                    success: function(json) {
                        var rb_obj = '';

                        if (json.rb_object == 'RbMegaMenu') {
                            rb_obj = 'menu';
                        }

                        if (json.rb_object == 'RbMenuBlock') {
                            rb_obj = 'block';
                        }

                        if (json.rb_object == 'RbMenuTab') {
                            rb_obj = 'tab';
                        }

                        if (json.rb_object == 'RbMenuColumn') {
                            rb_obj = 'column';
                        }

                        if (json.rb_object != 'RbMegaMenu') {
                            if ($('li[data-id-'+rb_obj+'="'+json.itemId+'"] > .rb_buttons .rb_duplicate').length > 0)
                                $('li[data-id-'+rb_obj+'="'+json.itemId+'"] > .rb_buttons .rb_duplicate').removeClass('active');
                        } else {
                            if ($('li[data-id-'+rb_obj+'="'+json.itemId+'"] > .rb_menus_li_content .rb_buttons > .rb_duplicate').length > 0)
                                $('li[data-id-'+rb_obj+'="'+json.itemId+'"] > .rb_menus_li_content .rb_buttons > .rb_duplicate').removeClass('active');
                        }

                        if (json.html) {
                            if ($('li[data-id-'+rb_obj+'="'+json.itemId+'"]').length > 0)
                                $('li[data-id-'+rb_obj+'="'+json.itemId+'"]').after(json.html);
                            else
                                if($('ul.rb_'+rb_obj+'s_ul').length > 0)
                                    $('ul.rb_'+rb_obj+'s_ul').append(json.html);
                        }

                        if (json.rb_object=='RbMegaMenu') {
                            $('.rb_menus_li').removeClass('open');
                            $('li[data-id-'+rb_obj+'="'+json.newItemId+'"]').addClass('open');

                            if ($('.rb_menus_li.open .rb_tabs_li').length>0) {
                                $('.rb_menus_li.open .rb_tabs_li:first-child').addClass('open');
                            } 
                        }

                        if (json.rb_object=='RbMenuTab') {
                            $('.rb_tabs_li').removeClass('open');
                            $('li[data-id-'+rb_obj+'="'+json.newItemId+'"]').addClass('open');
                        }

                        rbSort('.rb_blocks_ul'); 
                        rbSort('.rb_tabs_ul_content');
                        rbSort('.rb_columns_ul');
                        rbSort('.rb_menus_ul');

                        if(json.alerts.success)
                            rbAlertSucccess(json.alerts.success);
                        else
                            alert(json.alerts.errors);

                        displayHeightTab();

                        var $images = $('.rbthememenu img');
                        $images.on('load',function(){
                            displayHeightTab();
                        });
                    },
                    error: function(xhr, status, error)
                    {
                        $('.rb_duplicate').removeClass('active');
                        var err = eval("(" + xhr.responseText + ")");
                        alert(err.Message);
                    }
                });
            }            
        return false;
    });
    
    $(document).on('change','.rb_form select[name="link_type"],.rb_form select[name="block_type"]',function(){
        checkFormFields();
        if ($('#block_type').length > 0)
        {
            if ($('#block_type').val() != 'PRODUCT')
            {
                $('.row_id_products, .row_product_count, .row_product_type').hide();
            }
            else
            {
                changeProductType();
                rb_func.search();
            }
        }
    });
    
    //Config
    $(document).on('click','.rb_config_save',function(){
        if (!$('.rb_config_form_content').hasClass('active')) {
            $('.rb_config_form_content').addClass('active');
            $(this).parents('.rb_save_wrapper').eq(0).addClass('loading');
            $('.rb-alert').remove();
            var formData = new FormData($(this).parents('form').get(0));
            $.ajax({
                url: $(this).parents('form').eq(0).attr('action'),
                data: formData,
                type: 'post',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(json)
                {
                    $('.rb-alert').remove();
                    $('.rb_config_form_content').removeClass('active');
                    $('.rb_config_form_content').append(json.alert);
                    if (json.success) {
                        rbAlertSucccess($('.rb_config_form_content .alert-success').html());

                        $('.rb_pop_up').addClass('hidden').parents('.rb_popup_overlay').addClass('hidden');
                    }

                    $('.rb_save_wrapper').removeClass('loading'); 
                },
                error: function(xhr, status, error)
                {
                    $('.rb-alert').remove();
                    $('.rb_save_wrapper').removeClass('loading');
                    $('.rb_config_form_content').removeClass('active');
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                }
            });
        }
        return false;
    });
    $(document).on('click','.rb_config_button',function(){
        $('.rb_pop_up').addClass('hidden');
        $('.rb_config_form').removeClass('hidden').parents('.rb_popup_overlay').removeClass('hidden');
        $('.rb-alert.alert-success').remove();
        checkFormFields();
    });
    $(document).on('click','.rb_import_menu',function(){
        if(!$('.rb_import_option_form').hasClass('active'))
        {
            $('.rb_import_option_form').addClass('active');
            var formData = new FormData($(this).parents('form').get(0));
            $('.rb_import_option_form .alert').remove();
            $.ajax({
                url: $('.rb_import_option_form').attr('action'),
                data: formData,
                type: 'post',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(json){
                    $('.rb_import_option_form').removeClass('active');
                    if(json.success)
                    {
                        $('.rb_pop_up').addClass('hidden');
                        $('.rb_forms').addClass('hidden');
                        $('.rb_export_form').addClass('hidden');
                        $('.rb_export.rb_pop_up').addClass('hidden');
                        rbAlertSucccess(json.success);
                        setTimeout(function(){
                            location.reload();
                        },3000);                        
                    }
                    else
                    {
                        $('.rb_import_option_form').append('<div class="alert alert-danger">'+json.error+'</div>');
                    }                                        
                },
                error: function(xhr, status, error)
                {
                    $('.rb_import_option_form').removeClass('active');
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);                    
                }                
            }); 
        }
        return false;
    }); 
    //Reset
    $(document).on('click','.rb_reset_default',function(){
            if(!$(this).hasClass('active'))
            {
                $(this).addClass('active');
                $.ajax({
                    url: rbBaseAdminUrl,
                    dataType: 'json',
                    type: 'post',
                    data: {
                        reset_config: 1,                
                    },
                    success: function(json){
                        $('.rb_reset_default').removeClass('active'); 
                        if(json.success)
                        {
                            rbAlertSucccess(json.success);
                            setTimeout(function(){
                                location.reload();
                            },3000); 
                        }                            
                    },
                    error: function(xhr, status, error)
                    {
                        $('.rb_reset_default').removeClass('active');
                        var err = eval("(" + xhr.responseText + ")");
                        alert(err.Message);
                    }
                });
            }            
        return false;
    });
    //Sortable
    rbSort('.rb_blocks_ul');
    rbSort('.rb_tabs_ul_content'); 
    rbSort('.rb_columns_ul');
    rbSort('.rb_menus_ul');   
    
    //Color   
    $('.custom_color').hide();
    $('.custom_color.'+$('#RBTHEMMENU_LAYOUT').val()).show();
    $(document).on('change','#RBTHEMEMENU_LAYOUT',function(){
        $('.custom_color').hide();
        $('.custom_color.'+$('#RBTHEMEMENU_LAYOUT').val()).show();
    });   
    
    //Cache    
    if(parseInt($('input[name="RBTHEMEMENU_CACHE_ENABLED"]:checked').val())==1)
        $('.row_rb_cache_life_time').show();
    else
        $('.row_rb_cache_life_time').hide();
    $(document).on('change','input[name="RBTHEMEMENU_CACHE_ENABLED"]',function(){
        if(parseInt($('input[name="RBTHEMEMENU_CACHE_ENABLED"]:checked').val())==1)
            $('.row_rb_cache_life_time').show();
        else
            $('.row_rb_cache_life_time').hide();
    });
    $(document).on('click','.rb_clear_cache',function(){
        if(!$(this).hasClass('active'))
        {
            $(this).addClass('active');
            $.ajax({
                url: $(this).attr('href'),
                data: {
                    clearMenuCache: 1,
                },
                type: 'post',
                dataType: 'json',
                success: function(json){
                    $('.rb_clear_cache').removeClass('active');                    
                    if(json.success)
                        rbAlertSucccess(json.success);
                },
                error: function(xhr, status, error)
                {
                    $('.rb_clear_cache').removeClass('active');   
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                }
            });
        }
        return false;
    });
    //Initial events
    $('.rb_fancy').fancybox();
    if($('.rb_menus_ul > li').length > 0)
        $('.rb_menus_ul > li:first-child').addClass('open');
    if($('.rb_menus_li.open .rb_tabs_li').length>0)
    {
        $('.rb_menus_li.open .rb_tabs_li:first-child').addClass('open');
    }
    displayHeightTab();    
    $(document).mouseup(function (e)
    {
        if ($('.rb_results').length > 0 && $('.rb_results').is(':visible'))
            return false;
        var container = $(".rb_pop_up");
        var colorpanel = $('#mColorPicker');
        var mce_container =$('.mce-container');
        if (!mce_container.is(e.target) && mce_container.has(e.target).length === 0 && !container.is(e.target) 
            && container.has(e.target).length === 0 && !colorpanel.is(e.target) && colorpanel.has(e.target).length === 0
            && ($('#mColorPicker').length <=0 || ($('#mColorPicker').length > 0 && $('#mColorPicker').css('display')=='none'))
            && $('.rb_export.rb_pop_up').hasClass('hidden'))
        {
            if ($('.rb_icon_form_new').hasClass('rb_pop_up'))
            {
                $('.rb_icon_form_new').removeClass('rb_pop_up').addClass('hidden');
                $('.rb_menu_form').removeClass('hidden').addClass('rb_pop_up');
            }
            else
            {
                $('.rb_pop_up').addClass('hidden').parents('.rb_popup_overlay').addClass('hidden');
                $('.rb_forms').addClass('hidden');
            }
            $('.rb_export_form').addClass('hidden');
        }
    });
    $(document).keyup(function(e) {      
      if (e.keyCode === 27)
      {
          if ($('.rb_icon_form_new').hasClass('rb_pop_up'))
          {
              $('.rb_icon_form_new').removeClass('rb_pop_up').addClass('hidden');
              $('.rb_menu_form').removeClass('hidden').addClass('rb_pop_up');
          }
          else
          {
              $('.rb_pop_up').addClass('hidden').parents('.rb_popup_overlay').addClass('hidden');
              $('.rb_forms').addClass('hidden');
          }
        $('.rb_export_form').addClass('hidden');
      }
    });
    $(document).on('click','.rb_change_mode',function(){
        $('.rb_change_mode').removeClass('active');
        $(this).addClass('active');
        if($(this).hasClass('rb_layout_rlt'))
            $('.rbthememenu').removeClass('rb-dir-ltr').addClass('rb-dir-rtl');
        else
            $('.rbthememenu').removeClass('rb-dir-rtl').addClass('rb-dir-ltr');
    });
    
    $(document).on('click','.rb_view_mode',function(){
        if(!$(this).hasClass('active'))
        {
            $('.rb_view_mode').removeClass('active');
            $(this).addClass('active');
            if($(this).hasClass('rb_view_mode_tab_select'))
            {
                $('.rbthememenu').removeClass('rb_view_mode_list').addClass('rb_view_mode_tab');
                displayHeightTab();
            }
            else
            {
                $('.rbthememenu').removeClass('rb_view_mode_tab').addClass('rb_view_mode_list');
                if($('.rb_tabs_ul_content').length)
                    $('.rb_tabs_ul_content').removeAttr('style');
            }
                
        }        
    });
    if($('select[name="RBTHEMEMENU_HOOK_TO"]').val()=='customhook' && $('select[name="RBTHEMEMENU_HOOK_TO"]').next('.help-block').length > 0)
        $('select[name="RBTHEMEMENU_HOOK_TO"]').next('.help-block').addClass('active');
    $(document).on('change','select[name="RBTHEMEMENU_HOOK_TO"]',function(){
        if($(this).val()=='customhook' && $(this).next('.help-block').length > 0)
            $(this).next('.help-block').addClass('active');
        else
            $(this).next('.help-block').removeClass('active');
    });
    $(document).on('click','.rb_config_form_tab > li',function(){
        $('.rb_config_form_tab > li,.rb_config_forms > div').removeClass('active');
        $(this).addClass('active');
        $('.rb_config_forms div.rb_config_'+$(this).attr('data-tab')).addClass('active');
    });
    if($('.rb_block_wrapper a').length > 0)
      $('.rb_block_wrapper a').attr('target','_blank');  
    if ($('#product_type_specific').length > 0)
        changeProductType();
    $(document).on('change','input[type=radio][name=product_type]', function () {
        changeProductType();
    });
});

function changeProductType()
{
    $('.row_product_type').show();
    if ($('#product_type_specific').is(':checked'))
    {
        $('.row_id_products').show();
        $('.row_product_count').hide();
    }
    else
    {
        $('.row_id_products').hide();
        $('.row_product_count').show();
    }
}


function rbSort(selector)
{
    $(selector).sortable({
      connectWith: selector,
      update: function(e,ui)
      {
         if (this === ui.item.parent()[0]) {
            var obj = ui.item.attr('data-obj');
            var itemId = ui.item.attr('data-id-'+obj);
            var parentObj = ui.item.parents('li').length > 0 ? ui.item.parents('li').eq(0).attr('data-obj') : false;
            var parentId = parentObj && ui.item.parents('li').length > 0 ? ui.item.parents('li').eq(0).attr('data-id-'+parentObj) : 0;
            var previousId = ui.item.prev('li').length > 0 ? ui.item.prev('li').attr('data-id-'+obj) : 0;
            $.ajax({
                url: rbBaseAdminUrl,
                type: 'post',
                dataType: 'json',
                data: {
                    itemId: itemId,
                    obj: obj,
                    parentId: parentId,
                    parentObj: parentObj ? parentObj : '',
                    previousId: previousId,
                    updateOrder: 1,
                },
                success: function(json)
                {
                    if(!json.success)
                        $(selector).sortable('cancel');
                    displayHeightTab();
                },
                error: function()
                {
                    $(selector).sortable('cancel');
                }
            });
         }        
      }
    }).disableSelection();
}
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if($(input).parents('.col-lg-9').eq(0).find('.preview_img').length <= 0)
            {
                $(input).parents('.col-lg-9').eq(0).append('<div class="preview_img"><img src="'+e.target.result+'"/> <i style="font-size: 20px;" class="process-icon-delete del_preview"></i></div>');
            }
            else
            {
                $(input).parents('.col-lg-9').eq(0).find('.preview_img img').eq(0).attr('src',e.target.result);
            }
            if($(input).parents('.col-lg-9').eq(0).next('.uploaded_image_label').length > 0)
            {
                $(input).parents('.col-lg-9').eq(0).next('.uploaded_image_label').addClass('hidden'); 
                $(input).parents('.col-lg-9').eq(0).next('.uploaded_image_label').next('.uploaded_img_wrapper').addClass('hidden');
            }                                      
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function updatePreviewImage(name,url,delete_url)
{
    if($('.defaultForm.active input[name="'+name+'"]').length > 0 && $('.defaultForm.active input[name="'+name+'"]').parents('.col-lg-9').length > 0)
    {
        if($('.defaultForm.active input[name="'+name+'"]').parents('.col-lg-9').eq(0).find('.preview_img').length > 0)
           $('.defaultForm.active input[name="'+name+'"]').parents('.col-lg-9').eq(0).find('.preview_img').eq(0).remove(); 
        if($('.defaultForm.active input[name="'+name+'"]').parents('.col-lg-9').eq(0).next('.uploaded_image_label').length<=0)
        {
            $('.defaultForm.active input[name="'+name+'"]').parents('.col-lg-9').eq(0).after('<label class="control-label col-lg-3 uploaded_image_label" style="font-style: italic;">Uploaded image: </label><div class="col-lg-9 uploaded_img_wrapper"><a class="ybc_fancy" href="'+url+'"><img title="Click to see full size image" style="display: inline-block; max-width: 200px;" src="'+url+'"></a>'+(delete_url ? '<a class="delete_url" style="display: inline-block; text-decoration: none!important;" href="'+delete_url+'"><span style="color: #666"><i style="font-size: 20px;" class="process-icon-delete"></i></span></a>' : '')+'</div>');
        }
        else
        {
            var imageWrapper = $('.defaultForm.active input[name="'+name+'"]').parents('.col-lg-9').eq(0).next('.uploaded_image_label').next('.col-lg-9');
            imageWrapper.find('a.rb_fancy').eq(0).attr('href',url);
            imageWrapper.find('a.rb_fancy img').eq(0).attr('src',url);
            if(imageWrapper.find('a.delete_url').length > 0)
                imageWrapper.find('a.delete_url').eq(0).attr('href',delete_url);
            $('.defaultForm.active input[name="'+name+'"]').parents('.col-lg-9').eq(0).next('.uploaded_image_label').removeClass('hidden');
            $('.defaultForm.active input[name="'+name+'"]').parents('.col-lg-9').eq(0).next('.uploaded_image_label').next('.uploaded_img_wrapper').removeClass('hidden');            
        }
        $('.defaultForm.active input[name="'+name+'"]').val('');        
    }
}
function showSaveMessage(alertmsg)
{    
    if(alertmsg)
    {
        if($('.defaultForm.active').parents('.rb_pop_up').eq(0).find('.alert').length > 0)
            $('.defaultForm.active').parents('.rb_pop_up').eq(0).find('.alert').remove();
        $('.defaultForm.active').parents('.rb_pop_up').eq(0).append(alertmsg);
    }    
}
function checkFormFields()
{
    if($('.rb_form select[name="link_type"]').length > 0)
    {
        $('.rb_form .row_link, .rb_form .row_id_manufacturer, .rb_form .row_menu_ver_alway_show, .rb_form .row_id_category, .rb_form .row_id_cms, .rb_form .row_menu_ver_hidden_border, .rb_form .row_id_supplier,.rb_form .row_menu_ver_text_color, .rb_form .row_menu_ver_background_color, .rb_form .row_menu_item_width,.rb_form .row_tab_item_width,.rb_form .row_background_image,.rb_form .row_position_background,.rb_form .row_display_tabs_in_full_width').hide();
        if($('.rb_form select[name="link_type"]').val()=='CUSTOM')
            $('.rb_form .row_link').show();
        else if($('.rb_form select[name="link_type"]').val()=='CMS')
            $('.rb_form .row_id_cms').show();
        else if($('.rb_form select[name="link_type"]').val()=='CATEGORY')
            $('.rb_form .row_id_category').show();
        else if($('.rb_form select[name="link_type"]').val()=='MNFT')
            $('.rb_form .row_id_manufacturer').show();
        else if($('.rb_form select[name="link_type"]').val()=='MNSP')
            $('.rb_form .row_id_supplier').show();
        if($('select[name="enabled_vertical"]').val()==1)
        {
            $('.rb_form .row_menu_ver_text_color, .rb_form .row_menu_ver_alway_show, .rb_form .row_menu_ver_hidden_border, .rb_form .row_menu_ver_background_color,.rb_form .row_menu_item_width,.rb_form .row_tab_item_width').show();
            if($('#sub_menu_type').val()=='FULL')
                $('.rb_form .row_display_tabs_in_full_width').show();
        }  
        else
            $('.rb_form .row_background_image,.rb_form .row_position_background').show();
         
    }
    if($('.rb_form select[name="block_type"]').length > 0)
    {
        $('.rb_form .row_product_type,.rb_form .row_product_count,.rb_form .row_image, .rb_form .row_id_manufacturers, .rb_form .row_id_categories, .rb_form .row_id_cmss,.rb_form .row_image_link,.rb_form .row_content,.rb_form .row_id_products, .rb_form .row_id_suppliers,.rb_form .row_order_by_category,.rb_form .row_order_by_suppliers,.rb_form .row_order_by_manufacturers,.rb_form .row_display_mnu_name, .rb_form .row_display_mnu_inline,.rb_form .row_display_suppliers_name,.rb_form .row_display_suppliers_inline,.rb_form .row_display_suppliers_img,.rb_form .row_display_mnu_img,.rb_form .row_show_description, .rb_form .row_show_clock').hide();
        if($('.rb_form select[name="block_type"]').val()=='HTML')
            $('.rb_form .row_content').show();
        else if($('.rb_form select[name="block_type"]').val()=='CMS')
            $('.rb_form .row_id_cmss').show();
        else if($('.rb_form select[name="block_type"]').val()=='CATEGORY'){
            $('.rb_form .row_id_categories,.rb_form .row_order_by_category').show();
        }
            
        else if($('.rb_form select[name="block_type"]').val()=='MNFT')
        {
            $('.rb_form .row_id_manufacturers,.rb_form .row_order_by_manufacturers, .rb_form .row_display_mnu_img').show();
            if($('input[name="display_mnu_img"]:checked').val()==1)
            {
                $('.rb_form .row_display_mnu_name, .rb_form .row_display_mnu_inline').show();
            }
        }
        else if($('.rb_form select[name="block_type"]').val()=='MNSP')
        {
            $('.rb_form .row_id_suppliers,.rb_form .row_order_by_suppliers,.rb_form .row_display_suppliers_img').show();
            if($('input[name="display_suppliers_img"]:checked').val()==1)
            {
                $('.rb_form .row_display_suppliers_name, .rb_form .row_display_suppliers_inline').show();
            }
        }
            
        else if($('.rb_form select[name="block_type"]').val()=='PRODUCT')
        {
            $('.rb_form .row_show_description, .rb_form .row_show_clock').show();
            $('.rb_form .row_id_products').show();
            changeProductType();
        }
            
        else if($('.rb_form select[name="block_type"]').val()=='IMAGE')
        {
            $('.rb_form .row_image').show();
            $('.rb_form .row_image_link').show();    
        }
    }
    if($('input[name="RBTHEMEMENU_VERTICAL_ENABLED"]').length>0)
    {
        if($('input[name="RBTHEMEMENU_VERTICAL_ENABLED"]:checked').val()==1)
        {
            $('.vertical_group').show();
        }
        else
            $('.vertical_group').hide();
    }
    if($('input[name="RBTHEMEMENU_STICKY_ENABLED"]').length>0)
    {
        if($('input[name="RBTHEMEMENU_STICKY_ENABLED"]:checked').val()==1)
        {
            $('.row_rb_sticky_dismobile').show();
        }
        else
            $('.row_rb_sticky_dismobile').hide();
    }
    $('.rb_config_extra_features .form-group-wrapper .help-block').addClass('alert-warning').addClass('alert');
}
function rbAlertSucccess(successMsg)
{    
    if($('#content .rb_success_alert').length <= 0)
    {
        $('#content').append('<div class="alert alert-success rb_success_alert" style="display: none;"></div>');        
    }
    $('#content .rb_success_alert').html(successMsg);
    $('#content .rb_success_alert').fadeIn().delay(5000).fadeOut();
}
function displayHeightTab()
{
    if($('.rb_menus_li.open .rb_tabs_ul .rb_tabs_li.open .rb_columns_ul').length)
    {
       var height_menu = $('.rb_menus_li.open .rb_tabs_ul .rb_tabs_li.open .rb_columns_ul').height()+300;
       $('.rb_menus_li.open .rb_tabs_ul').css('height',($('.rb_menus_li.open .rb_tabs_ul .rb_tabs_li.open .rb_columns_ul').height()+300)+'px');
      
       $('.rbthememenu').css('height',(height_menu+100)+'px');
    }
    else if($('.rb_menus_li.open .rb_columns_ul').length)
    {
        $('.rbthememenu').css('height',($('.rb_menus_li.open .rb_columns_ul').height()+200)+'px');
    }
}
function displayCountDownClock()
{
    var t = $("[data-countdown]"),
    n = '<div class="countdown-item"><div class="countdown-inner"><div class="countdown-cover"><div class="countdown-table"><div class="countdown-cell"><div class="countdown-time">%-D</div><div class="countdown-text">Day%!D</div></div></div></div></div></div><div class="countdown-item"><div class="countdown-inner"><div class="countdown-cover"><div class="countdown-table"><div class="countdown-cell"><span class="countdown-time">%H</span><div class="countdown-text">Hr%!H</div></div></div></div></div></div><div class="countdown-item"><div class="countdown-inner"><div class="countdown-cover"><div class="countdown-table"><div class="countdown-cell"><span class="countdown-time">%M</span><div class="countdown-text">Min%!M</div></div></div></div></div></div><div class="countdown-item"><div class="countdown-inner"><div class="countdown-cover"><div class="countdown-table"><div class="countdown-cell"><span class="countdown-time">%S</span><div class="countdown-text">Sec%!S</div></div></div></div></div></div>';
    if(t.length>0)
    {
        t.each(function() {
            var t = $(this).data("countdown");
            $(this).countdown(t).on("update.countdown", function(t) {
                $(this).html(t.strftime(n));
            })
        });
    }
      
}