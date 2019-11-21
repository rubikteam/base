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
function changeToMaterial() {
  var materialIconAssoc = {
    'mce-i-code': '<i class="material-icons">code</i>',
    'mce-i-none': '<i class="material-icons">format_color_text</i>',
    'mce-i-bold': '<i class="material-icons">format_bold</i>',
    'mce-i-italic': '<i class="material-icons">format_italic</i>',
    'mce-i-underline': '<i class="material-icons">format_underlined</i>',
    'mce-i-strikethrough': '<i class="material-icons">format_strikethrough</i>',
    'mce-i-blockquote': '<i class="material-icons">format_quote</i>',
    'mce-i-link': '<i class="material-icons">link</i>',
    'mce-i-alignleft': '<i class="material-icons">format_align_left</i>',
    'mce-i-aligncenter': '<i class="material-icons">format_align_center</i>',
    'mce-i-alignright': '<i class="material-icons">format_align_right</i>',
    'mce-i-alignjustify': '<i class="material-icons">format_align_justify</i>',
    'mce-i-bullist': '<i class="material-icons">format_list_bulleted</i>',
    'mce-i-numlist': '<i class="material-icons">format_list_numbered</i>',
    'mce-i-image': '<i class="material-icons">image</i>',
    'mce-i-table': '<i class="material-icons">grid_on</i>',
    'mce-i-media': '<i class="material-icons">video_library</i>',
    'mce-i-browse': '<i class="material-icons">attachment</i>',
    'mce-i-checkbox': '<i class="mce-ico mce-i-checkbox"></i>',
  };

  $.each(materialIconAssoc, function (index, value) {
    $('.' + index).replaceWith(value);
  });
}

function tinySetup(config) {
  if (!config) {
    config = {};
  }

  if (typeof config.editor_selector != 'undefined') {
    config.selector = '.' + config.editor_selector;
  }

  var default_config = {
    selector: ".rte",
    plugins: "align colorpicker link image filemanager table placeholder advlist code table",
    browser_spellcheck: true,
    toolbar1: "code,colorpicker,bold,italic,underline",
    toolbar2: "strikethrough,blockquote,link,align,bullist",
    toolbar3: "numlist,table,image",
    external_filemanager_path: baseAdminDir + "filemanager/",
    filemanager_title: "File manager",
    external_plugins: {"filemanager": baseAdminDir + "filemanager/plugin.min.js"},
    language: iso_user,
    skin: "prestashop",
    menubar: false,
    statusbar: false,
    relative_urls: false,
    convert_urls: false,
    entity_encoding: "raw",
    extended_valid_elements: "em[class|name|id],@[role|data-*|aria-*]",
    valid_children: "+*[*]",
    valid_elements: "*[*]",
    init_instance_callback: "changeToMaterial",
    setup: function(editor) {
      editor.on( 'keyup change undo redo', function() {
        editor.save();
        $('#' + editor.id).keyup();
        });
      editor.on('PostProcess', function (e) {
        e.content = e.content.replace(/\n/g, '');
      });
    }
  };

  $.each(default_config, function (index, el) {
    if (config[index] === undefined)
      config[index] = el;
  });

  // Change icons in popups
  $('body').on('click', '.mce-btn, .mce-open, .mce-menu-item', function () {
    changeToMaterial();
  });

 tinyMCE.init(config);
}

function openPsFileManager($fieldId = 'rb-control-media-field') {
  $.fancybox({
    'width'		: 900,
    'href': baseAdminDir + 'filemanager/dialog.php?type=1&field_id=rb-control-media-field',
    'height'	: 600,
    'type'		: 'iframe',
    'autoScale' : false,
    'autoDimensions': false,
    'fitToView' : false,
    'autoSize' : false,
    onUpdate : function(){
      var $link = $('.fancybox-iframe').contents().find('a.link');
      $link.data('field_id', $fieldId);
      $link.attr('data-field_id', $fieldId);
      },
    afterShow: function(){
      var $link = $('.fancybox-iframe').contents().find('a.link');
      $link.data('field_id', $fieldId);
      $link.attr('data-field_id', $fieldId);
    },
    afterClose: function () {

      let $input = $('#' + $fieldId);
      let val = $input.val();

      $input.val(val.replace(rbFrontendConfig.ddBaseUrl, ""));

      $input.trigger( 'input' );
    }
  });
}

$(document ).ready(function() {
  tinySetup({
    editor_selector :"rb-wp-editor"
  });
});