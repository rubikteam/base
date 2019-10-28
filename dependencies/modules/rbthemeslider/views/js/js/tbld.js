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
(function() {
	tinymce.create('tinymce.plugins.rbslider', {
		init : function(ed, url) {
		},
		createControl : function(n, cm) {
 
            if (n=='rbslider') {
                var mlb = cm.createListBox('rbslider', {
                     title: 'Rb Theme Slider',
                     onselect : function(v) {
                     	if (tinyMCE.activeEditor.selection.getContent() == '') {
                            tinyMCE.activeEditor.selection.setContent( v )
                        }
                     }
                });
 
                for(var i in rbslider_shortcodes)
                	mlb.add(rbslider_shortcodes[i],rbslider_shortcodes[i]);
 
                return mlb;
            }
            return null;
        }
 
 
	});
	tinymce.PluginManager.add('rbslider', tinymce.plugins.rbslider);
	
	setTimeout(function() {
		jQuery('.mce-widget.mce-btn').each(function() {
			var btn = jQuery(this);
			if (btn.attr('aria-label')=="Rb Theme Slider")
				btn.find('span').css({padding:"10px 20px 10px 10px"});
		});
	},1000);
})();