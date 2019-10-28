{*
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
*}
<div id="viewWrapper" class="view_wrapper">
	<div class='wrap'>
		<div class="clear_both"></div>

		<div class="title_line nobgnopd" style="margin-bottom: 20px !important;">
			<div class="view_title">
				{l s='Navigation Editor' mod='rbthemeslider'}
			</div>
		</div>
		<div style="clear: both;"></div>
		
		<div class="setting_box navig" style="margin-bottom: 20px;">
			<h3><span class="setting-step-number">1</span>
				<span style="max-width: 400px;">{l s='Select the Navigation Category to Edit' mod='rbthemeslider'}</span>
				<a original-title="" class="button-primary rbblue" id="rs-add-new-navigation-element" href="javascript:void(0);">
					{l s='Add New' mod='rbthemeslider'}
				</a>
			</h3>
			
			<div class="table-titles">				
				<div class="rs-nav-table-cell rs-nav-table-title">{l s='ID' mod='rbthemeslider'}ID</div>
				<div class="rs-nav-table-cell rs-nav-table-title">{l s='Skin Name' mod='rbthemeslider'}</div>
				<div class="rs-nav-table-cell rs-nav-table-title">{l s='Arrows' mod='rbthemeslider'}</div>
				<div class="rs-nav-table-cell rs-nav-table-title">{l s='Bullets' mod='rbthemeslider'}</div>
				<div class="rs-nav-table-cell rs-nav-table-title">{l s='Thumbs' mod='rbthemeslider'}</div>
				<div class="rs-nav-table-cell rs-nav-table-title">{l s='Tabs' mod='rbthemeslider'}</div>	
				<div class="rs-nav-table-cell rs-nav-table-title" style="width:auto;text-align:left;padding:0px 20px;">
					{l s='Actions' mod='rbthemeslider'}
				</div>
			</div>
			
			<div id="list-of-navigations" style="max-height:430px;overflow:hidden;position:relative;top:0px;left:0px;">  
				<div class="rs-nav-table tablecontent">
				</div>
			</div>
		</div>

		<div style="clear: both;"></div>

		<div class="setting_box navig" style="margin-bottom: 20px;">
			<div class="rs-editing-wrapper" style="display: none;">
				<h3 style="border:0;"><span class="setting-step-number">2</span><span style="max-width: 400px;">Editing <span class="rs-nav-editing-title"></span></span> <a href="javascript:void(0);" class="button-primary rbred rs-remove-nav-element">
					{l s='Remove' mod='rbthemeslider'}</a>
				</h3>

				<div class="rs-editing-markups-wrap">
					<div class="rs-markup-selector">
						<div class="rs-selector-title">{l s='Markup' mod='rbthemeslider'}</div>
						<span class="rs-editor-open-field"><i class="rbicon-list-add"></i></span>
					</div>
					<div class="rs-markup-wrapper" style="display: none;">
						<div class="rs-markup-elements">
							<div style="padding: 20px;" class="closemeshowhide">
								<div class="helper-wrappers">
									<h4>Actions</h4>
									<ul class="rs-element-list">
										<li><span class="libtn">Parameters<span class="more-values-available"></span></span>
											<ul style="display: none;" class="rs-element-add">
												<li data-call="params_special" data-paramid="title"><span class="libtn">{l s='Slide Title' mod='rbthemeslider'}</span></li>
												<li data-call="params_special" data-paramid="description"><span class="libtn">{l s='Slide Description' mod='rbthemeslider'}Slide Description</span></li>

												{for $i=1 to 10}
													<li data-call="params_markup" data-paramid="{$i|intval}"><span class="libtn">{l s='Parameter' mod='rbthemeslider'} {$i|intval}</span></li>
												{/for}
											</ul>
										</li>
									</ul>
								</div>
							</div>
							<div class="showhidehelper"></div>
						</div>

						<textarea name="rs-cm-markup" id="rs-cm-markup"></textarea>
					</div><div class="rs-css-selector open">
						<div class="rs-selector-title">CSS</div> <span class="rs-editor-open-field"><i class="rbicon-list-add"></i></span>
					</div>
					<div class="rs-css-wrapper" style="display: none;">
						<div class="rs-css-elements">
							<div style="padding: 20px;" class="closemeshowhide">
								<div class="helper-wrappers rea-open">
									<h4><span class="libtn">{l s='Style Helper' mod='rbthemeslider'}<span class="more-values-available"></span></span></h4>
									<ul class="rs-element-list collapsable" style="display:block">
										<li data-call="color_value"><span class="libtn">{l s='Color Value' mod='rbthemeslider'}<span class="more-values-available"></span></span>
											<div style="display: none;" class="rs-element-add rs-element-add-color">										
												<input type="text" name="rs-color" class="my-color-field inputColorPicker" value="#000000">
												<span class="tp-clearfix"></span>
												<a href="javascript:void(0);" id="rs-add-css-color" class="button-primary rbblue" original-title="">Add</a>
											</div>
										</li>
										<li data-call="border_radius"><span class="libtn">{l s='Border Radius' mod='rbthemeslider'}<span class="more-values-available"></span></span>
											<div style="display: none;" class="rs-element-add rs-element-add-border-radius">
												<label>{l s='Top Left' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-border-radius-top-left" value="1"></td>
												<label>{l s='Top Right' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-border-radius-top-right" value="1"></td>
												<label>{l s='Bottom Right' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-border-radius-bottom-right" value="1"></td>
												<label>{l s='Bottom Left' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-border-radius-bottom-left" value="1"></td>
												<span class="tp-clearfix"></span>
												<a href="javascript:void(0);" id="rs-add-css-border-radius" class="button-primary rbblue" original-title="">{l s='Add' mod='rbthemeslider'}</a>
											</div>
										</li>
										<li data-call="border"><span class="libtn">{l s='Add' mod='rbthemeslider'}Border<span class="more-values-available"></span></span>
											<div style="display: none;" class="rs-element-add rs-element-add-border">
												<label>{l s='Top' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-border-top" value="1">
												<label>{l s='Right' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-border-right" value="1">
												<label>{l s='Bottom' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-border-bottom" value="1">
												<label>{l s='Left' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-border-left" value="1">
												<label>{l s='Opacity' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-border-opacity" value="100">
												<span class="tp-clearfix"></span>														
												<input type="text" name="rs-border-color" class="my-color-field inputColorPicker" value="#000000">
												<span class="tp-clearfix"></span>																				
												<a href="javascript:void(0);" id="rs-add-css-border" class="button-primary rbblue" original-title="">{l s='Add' mod='rbthemeslider'}</a>
											</div>
										</li>
										<li data-call="text_shadow"><span class="libtn">{l s='Text-Shadow' mod='rbthemeslider'}<span class="more-values-available"></span></span>
											<div style="display: none;" class="rs-element-add rs-element-add-text-shadow">
												<label>{l s='Angle' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-text-shadow-angle" value="0">
												<label>{l s='Distance' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-text-shadow-distance" value="0">
												<label>{l s='Blur' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-text-shadow-blur" value="0">
												<label>{l s='Opacity' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-text-shadow-opacity" value="100">
												<span class="tp-clearfix"></span>
												<input type="text" name="rs-text-shadow-color" class="my-color-field inputColorPicker" value="#000000">
												<span class="tp-clearfix"></span>										
												<a href="javascript:void(0);" id="rs-add-css-text-shadow" class="button-primary rbblue" original-title="">{l s='Add' mod='rbthemeslider'}</a>
											</div>
										</li>
										<li data-call="box_shadow"><span class="libtn">{l s='Add' mod='rbthemeslider'}Box-Shadow<span class="more-values-available"></span></span>
											<div style="display: none;" class="rs-element-add rs-element-add-box-shadow">
												<label>{l s='Angle' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-box-shadow-angle" value="0">
												<label>{l s='Distance' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-box-shadow-distance" value="0">
												<label>{l s='Blur' mod='rbthemeslider'}</label>
												<input class="rs-small-input" type="text" name="rs-box-shadow-blur" value="0">										
												<label>{l s='Opacity' mod='rbthemeslider'}</label>																				
												<input class="rs-small-input" type="text" name="rs-box-shadow-opacity" value="100">
												<span class="tp-clearfix"></span>
												<input type="text" name="rs-box-shadow-color" class="my-color-field inputColorPicker" value="#000000">
												<span class="tp-clearfix"></span>										
												<a href="javascript:void(0);" id="rs-add-css-box-shadow" class="button-primary rbblue" original-title="">{l s='Add' mod='rbthemeslider'}</a>
											</div>
										</li>
										<li data-call="font_families"><span class="libtn">{l s='Font Family' mod='rbthemeslider'}<span class="more-values-available"></span></span>
											<div style="display: none;" class="rs-element-add rs-element-add-box-shadow">
												<select name="rs-font-family" style="width: 160px">
													{foreach $font_families as $font_familie}
														<option value="{$font_familie['label']|escape:'htmlall':'UTF-8'}">{$font_familie['label']|escape:'htmlall':'UTF-8'}</option>
													{/foreach}
												</select>
												<a href="javascript:void(0);" id="rs-add-css-font-family" class="button-primary rbblue" original-title="">{l s='Add' mod='rbthemeslider'}</a>
											</div>
										</li>
									</ul>								
								</div>
								<div class="helper-wrappers">		
								</div>					
							</div>
							<div class="showhidehelper"></div>
						</div>
						<textarea name="rs-cm-css" id="rs-cm-css"></textarea>
					</div>
				</div>
				<div class="rs-editing-preview-wrap">
					<div class="rs-editing-preview-overlay"></div>
					<div class="rs-arrows-preview">
						<div class="tp-arrows tp-leftarrow"></div>
						<div class="tp-arrows tp-rightarrow"></div>
					</div>
					<div class="rs-bullets-preview"></div>
					<div class="rs-thumbs-preview"></div>
					<div class="rs-tabs-preview"></div>
					<input id="rs-preview-color-changer" type="text" name="rs-preview-color" class="bg-color-field inputColorPicker" value="#000000">
					<span class="little-info">{l s='Live Preview - Hover &amp; Click for test' mod='rbthemeslider'}</span>
					<span class="little-sizes">
						{l s='Suggested Width:' mod='rbthemeslider'}
						<input class="rs-small-input" type="text" name="rs-test-width" value="160" style="width:45px !important; margin-right:15px;">
						{l s='Suggested Height:' mod='rbthemeslider'}
						<input class="rs-small-input" type="text" name="rs-test-height" value="160" style="width:45px !important;">
					</span>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>

		<div id="preview-nav-wrapper">
			<div class="rs-editing-preview-overlay"></div>
			<div class="rs-arrows-preview">
				<div class="tp-arrows tp-leftarrow"></div>
				<div class="tp-arrows tp-rightarrow"></div>
			</div>
			<div class="rs-bullets-preview"></div>
			<div class="rs-thumbs-preview"></div>
			<div class="rs-tabs-preview"></div>
		</div>

		<a class="button-primary rbgreen" id="rs-save-navigation-style" href="javascript:void(0);">
			<i class="rs-icon-save-light"></i>
			{l s='Save All Changes' mod='rbthemeslider'}
		</a>
	
		<script type="text/javascript">
		
			{* var rs_navigations = jQuery.parseJSON({{RbSliderFunctions::jsonEncodeForClientSide($navigs)}}) || []; *}

			var g_uniteDirPlagin = "{$g_uniteDirPlagin|escape:'htmlall':'UTF-8'}",
				g_urlContent = "{$g_urlContent|escape:'htmlall':'UTF-8'}";

			var ajaxurl = "{$ajaxurl|escape:'htmlall':'UTF-8'}";

			ajaxurl += "&returnurl={$ajaxurl_ext|escape:'htmlall':'UTF-8'}";

			{literal}

			var g_settingsObj = {};

			function showWaitAMinute(obj) {
				var wm = jQuery('#waitaminute');		
				// SHOW AND HIDE WITH DELAY
				if (obj.delay!=undefined) {

					punchgs.TweenLite.to(wm,0.3,{autoAlpha:1,ease:punchgs.Power3.easeInOut});
					punchgs.TweenLite.set(wm,{display:"block"});
					
					setTimeout(function() {
						punchgs.TweenLite.to(wm,0.3,{autoAlpha:0,ease:punchgs.Power3.easeInOut,onComplete:function() {
							punchgs.TweenLite.set(wm,{display:"block"});	
						}});  			
					},obj.delay)
				}

				// SHOW IT
				if (obj.fadeIn != undefined) {
					punchgs.TweenLite.to(wm,obj.fadeIn/1000,{autoAlpha:1,ease:punchgs.Power3.easeInOut});
					punchgs.TweenLite.set(wm,{display:"block"});			
				}

				// HIDE IT
				if (obj.fadeOut != undefined) {

					punchgs.TweenLite.to(wm,obj.fadeOut/1000,{autoAlpha:0,ease:punchgs.Power3.easeInOut,onComplete:function() {
							punchgs.TweenLite.set(wm,{display:"block"});	
						}});  
				}

				// CHANGE TEXT
				if (obj.text != undefined) {
					switch (obj.text) {
						case "progress1":

						break;
						default:					
							wm.html('<div class="waitaminute-message"><i class="eg-icon-emo-coffee"></i><br>'+obj.text+'</div>');
						break;	
					}
				}
			}

			var ps = {};
			ps.template = _.memoize(function ( id ) {
                var compiled,
                    options = {
                        evaluate:    /<#([\s\S]+?)#>/g,
                        interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
                        escape:      /\{\{([^\}]+?)\}\}(?!\})/g,
                        variable:    'data'
                    };

                return function ( data ) {
                    compiled = compiled || _.template( $( '#tmpl-' + id ).html(), null, options );
                    return compiled( data );
                };
	        });

			jQuery(document).ready(function(){
				var rs_current_editing = false;
				var cur_edit_type = false;
				var latest_nav_id = 0;
				var global_navigation_template = ps.template( "rs-navigation-wrap" );
				var global_navigation_template_header = ps.template( "rs-navigation-header-wrap" );
				
				rsAddAllNavigationEntries();
				
				function rsAddNavigationHeader(title, type){
					var data = { title: title, type: type };
					
					var content = global_navigation_template_header(data);
					jQuery('.rs-nav-table.tablecontent').append(content);
				}
				
				function rsAddNavigationElement(nav_values, is_new){
					var data = {
						'name': 		nav_values['name'],
						'id':			nav_values['id'],
						'show-arrows':	'none',
						'hide-arrows':	'block',
						'show-bullets':	'none',
						'hide-bullets':	'block',
						'show-thumbs':	'none',
						'hide-thumbs':	'block',
						'show-tabs':	'none',
						'hide-tabs':	'block'
					};
					
					if(typeof(nav_values['css']) !== 'undefined' && nav_values['css'] !== null){
						if(typeof(nav_values['css']['arrows']) !== 'undefined' && nav_values['css']['arrows'] !== null){
							data['hide-arrows'] = 'none';
							data['show-arrows'] = 'block';
						}
						if(typeof(nav_values['css']['bullets']) !== 'undefined' && nav_values['css']['bullets'] !== null){
							data['hide-bullets'] = 'none';
							data['show-bullets'] = 'block';
						}
						if(typeof(nav_values['css']['thumbs']) !== 'undefined' && nav_values['css']['thumbs'] !== null){
							data['hide-thumbs'] = 'none';
							data['show-thumbs'] = 'block';
						}
						if(typeof(nav_values['css']['tabs']) !== 'undefined' && nav_values['css']['tabs'] !== null){
							data['hide-tabs'] = 'none';
							data['show-tabs'] = 'block';
						}
					}
					
					data['has-original'] = false;
					if(typeof(nav_values['settings']) !== 'undefined' && typeof(nav_values['settings']['original']) !== 'undefined'){
						data['has-original'] = true;
					}
					
					data['edit'] = (typeof(nav_values['default']) !== 'undefined' && nav_values['default'] == true) ? false : true;
					data['show_text'] = (typeof(nav_values['default']) !== 'undefined' && nav_values['default'] == true) ? 'View' : 'Edit';
					
					var content = global_navigation_template(data);
					
					if(is_new){
						jQuery('.rs-default-t-wrap').before(content);
					}else{
						jQuery('.rs-nav-table.tablecontent').append(content);
					}
					
				}
				
				
				var rs_nav_placeholder = {
					arrows: '<div class="tp-arr-allwrapper">'+"\n\t"+'<div class="tp-arr-iwrapper">'+"\n\t\t"+'<div class="tp-arr-imgholder"></div>'+"\n\t\t"+'<div class="tp-arr-titleholder"></div>'+"\n\t\t"+'<div class="tp-arr-subtitleholder"></div>'+"\n\t"+'</div>'+"\n"+'</div>',
					bullets: '<span class="tp-bullet-image"></span>'+"\n"+'<span class="tp-bullet-title"></span>',
					thumbs: '<span class="tp-thumb-image"></span><span class="tp-thumb-title"></span>',
					tabs: '<span class="tp-tab-image"></span><span class="tp-tab-title"></span>'
				};

				var rs_css_placeholder = {
					arrows: '.{{class}}.tparrows {'+"\n\t"+'cursor:pointer;'+"\n\t"+'background:#000;'+"\n\t"+'background:rgba(0,0,0,0.5);'+"\n\t"+'width:40px;'+"\n\t"+'height:40px;'+"\n\t"+'position:absolute;'+"\n\t"+'display:block;'+"\n\t"+'z-index:100;'+"\n"+'}'+"\n"+
							'.{{class}}.tparrows:hover {'+"\n\t"+'background:#000;'+"\n"+'}'+"\n"+
							'.{{class}}.tparrows:before {'+"\n\t"+'font-family: "rbicons";'+"\n\t"+'font-size:15px;'+"\n\t"+'color:#fff;'+"\n\t"+'display:block;'+"\n\t"+'line-height: 40px;'+"\n\t"+'text-align: center;'+"\n"+'}'+"\n"+
							'.{{class}}.tparrows.tp-leftarrow:before {'+"\n\t"+'content: "\\e824";'+"\n"+'}'+"\n"+
							'.{{class}}.tparrows.tp-rightarrow:before {'+"\n\t"+'content: "\\e825";'+"\n"+'}'+"\n"+
							'.{{class}} .tp-arr-allwrapper {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-arr-iwrapper {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-arr-imgholder {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-arr-titleholder {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-arr-subtitleholder {'+"\n"+'}'+"\n",
					arrows_empty:'.{{class}}.tparrows {'+"\n"+'}'+"\n"+
							'.{{class}}.tparrows:hover {'+"\n"+'}'+"\n"+
							'.{{class}}.tparrows:before {'+"\n"+'}'+"\n"+
							'.{{class}}.tparrows.tp-leftarrow:before {'+"\n"+'}'+"\n"+
							'.{{class}}.tparrows.tp-rightarrow:before {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-arr-allwrapper {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-arr-iwrapper {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-arr-imgholder {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-arr-titleholder {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-arr-subtitleholder {'+"\n"+'}'+"\n",


					bullets:'.{{class}}.tp-bullets {'+"\n"+'}'+"\n"+
							'.{{class}}.tp-bullets:before {'+"\n\t"+'content:" ";'+"\n\t"+'position:absolute;'+"\n\t"+'width:100%;'+"\n\t"+'height:100%;'+"\n\t"+'background:#fff;'+"\n\t"+'padding:10px;'+"\n\t"+'margin-left:-10px;margin-top:-10px;'+"\n\t"+'box-sizing:content-box;'+"\n"+'}'+"\n"+
							'.{{class}} .tp-bullet {'+"\n\t"+'width:12px;'+"\n\t"+'height:12px;'+"\n\t"+'position:absolute;'+"\n\t"+'background:#aaa;'+"\n\t"+'border:3px solid #e5e5e5;'+"\n\t"+'border-radius:50%;'+"\n\t"+'cursor: pointer;'+"\n\t"+'box-sizing:content-box;'+"\n"+'}'+"\n"+
							'.{{class}} .tp-bullet:hover,'+"\n"+
							'.{{class}} .tp-bullet.selected {'+"\n\t"+'background:#666;'+"\n"+'}'+"\n"+
							'.{{class}} .tp-bullet-image {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-bullet-title {'+"\n"+'}'+"\n",	
					bullets_empty:'.{{class}}.tp-bullets {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-bullet {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-bullet:hover,'+"\n"+
							'.{{class}} .tp-bullet.selected {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-bullet-image {'+"\n"+'}'+"\n"+
							'.{{class}} .tp-bullet-title {'+"\n"+'}'+"\n",						


					thumbs:'',
					tabs:''
				}
				
				var rs_cm_markup_editor = CodeMirror.fromTextArea(document.getElementById("rs-cm-markup"), {
					lineNumbers: true,
					smartIndent:false,
					//lineWrapping:true,
					mode: 'text/html',
					onChange: function(){ rsCmModified('markup', rs_cm_markup_editor); drawEditor();},
					onCursorActivity: function() {
					    rs_cm_markup_editor.setLineClass(hlLineM, null, null);
					    hlLineM = rs_cm_markup_editor.setLineClass(rs_cm_markup_editor.getCursor().line, null, "activeline");
	  					rs_cm_markup_editor.matchHighlight("CodeMirror-matchhighlight");
					  }


				});
				rs_cm_markup_editor.setSize(null, 600);
				
				var rs_cm_css_editor = CodeMirror.fromTextArea(document.getElementById("rs-cm-css"), {
					lineNumbers: true,
					smartIndent:false,
					//lineWrapping:true,
					mode: 'css',
					onChange: function(){ rsCmModified('css', rs_cm_css_editor);drawEditor(); },
					onCursorActivity: function() {
					    rs_cm_css_editor.setLineClass(hlLineC, null, null);
					    hlLineC = rs_cm_css_editor.setLineClass(rs_cm_css_editor.getCursor().line, null, "activeline");
					    rs_cm_css_editor.matchHighlight("CodeMirror-matchhighlight");
					  }
				});


				var hlLineM = rs_cm_markup_editor.setLineClass(0, "activeline"),
					hlLineC = rs_cm_css_editor.setLineClass(0, "activeline");				

				var previewNav = function(sbut,mclass,the_css,the_markup,settings) {

					var ap = jQuery('#preview-nav-wrapper .rs-arrows-preview'),
						bp = jQuery('#preview-nav-wrapper .rs-bullets-preview'),
						tabp = jQuery('#preview-nav-wrapper .rs-tabs-preview'),
						thumbp = jQuery('#preview-nav-wrapper .rs-thumbs-preview'),
						sizer = jQuery('#preview-nav-wrapper .little-sizes');
						

					ap.html("");
					bp.html("");
					tabp.html("");
					thumbp.html("");
					
					ap.hide();
					bp.hide();
					tabp.hide();
					thumbp.hide();
					sizer.hide();

					if (sbut.hasClass("rs-nav-arrows-edit")) {
						ap.show();		
						var pattern = new RegExp(":hover",'g');			
						
						var t = '<style>'+the_css.replace(pattern,'.fakehover')+'</style>';
						t = t + '<div class="'+mclass+' tparrows tp-leftarrow">'+the_markup+'</div>';
						t = t + '<div class="'+mclass+' tparrows tp-rightarrow">'+the_markup+'</div>';					
						ap.html(t);	
						setTimeout(function() {
							try{ap.find('.tp-rightarrow').addClass("fakehover");} catch(e) {}
						},200);
						
					} else 
					if (sbut.hasClass("rs-nav-bullets-edit")) {
						bp.show();	
						var t = '<style>'+the_css+'</style>';
						t = t + '<div class="'+mclass+' tp-bullets">'
						for (var i=0;i<5;i++) {
							t = t + '<div class="tp-bullet">'+the_markup+'</div>';
						}
						t= t + '</div>';
						bp.html(t);
						var b = bp.find('.tp-bullet').first(),
							bw = b.outerWidth(true),
							bh = b.outerHeight(true),						
							mw = 0;
						bp.find('.tp-bullet').each(function(i) {
							var e = jQuery(this);
							if (i==0) 
								setTimeout(function() {
									try{e.addClass("selected");} catch(e) {}
								},150);
							
							
							var np = i*bw + i*10;
							e.css({left:np+"px"});
							mw = mw + bw + 10;
						})
						mw = mw-10;
						bp.find('.tp-bullets').css({width:mw, height:bh});					
					} else
					if (sbut.hasClass("rs-nav-tabs-edit")) {
						tabp.show();
						var t = '<style>'+the_css+'</style>';
						t = t + '<div class="'+mclass+'"><div class="tp-tab">'+the_markup+'</div></div>';
						tabp.html(t);
						var s = new Object();
						s.w = 160,
						s.h = 160;
						if (settings!="" && settings!=undefined) {							
							if (settings.width!=undefined && settings.width.tabs!=undefined)
								s.w=settings.width.tabs;
							if (settings.height!=undefined && settings.height.tabs!=undefined)
								s.h=settings.height.tabs;
						}
						tabp.find('.tp-tab').each(function(){
							jQuery(this).css({width:s.w+"px",height:s.h+"px"});
						});
						
					} else
					if (sbut.hasClass("rs-nav-thumbs-edit")) {
						thumbp.show();
						var t = '<style>'+the_css+'</style>';
						t = t + '<div class="'+mclass+'"><div class="tp-thumb">'+the_markup+'</div></div>';
						thumbp.html(t);
						var s = new Object();
						s.w = 160,
						s.h = 160;
						if (settings!="" && settings!=undefined) {							
							if (settings.width!=undefined && settings.width.thumbs!=undefined)
								s.w=settings.width.thumbs;
							if (settings.height!=undefined && settings.height.thumbs!=undefined)
								s.h=settings.height.thumbs;
						}
						thumbp.find('.tp-thumb').each(function(){
							jQuery(this).css({width:s.w+"px",height:s.h+"px"});			
						});					
					}

				}

				var drawEditor = function() {

					var sline = jQuery('.rs-nav-table-row.rs-nav-entry-wrap.selected'),
						sbut = sline.find('a.selected'),
						ap = jQuery('.rs-editing-preview-wrap .rs-arrows-preview'),
						bp = jQuery('.rs-editing-preview-wrap .rs-bullets-preview'),
						tabp = jQuery('.rs-editing-preview-wrap .rs-tabs-preview'),
						thumbp = jQuery('.rs-editing-preview-wrap .rs-thumbs-preview'),
						sizer = jQuery('.rs-editing-preview-wrap .little-sizes'),
						mclass = UniteAdminRb.sanitize_input(jQuery('.rs-nav-table-row.rs-nav-entry-wrap.selected').find('input[name="navigation-name"]').val().toLowerCase());
					
					ap.html("");
					bp.html("");
					tabp.html("");
					thumbp.html("");
					
					ap.hide();
					bp.hide();
					tabp.hide();
					thumbp.hide();
					sizer.hide();

					if (sbut.hasClass("rs-nav-arrows-edit")) {
						ap.show();					
						var t = '<style>'+rs_cm_css_editor.getValue()+'</style>';
						t = t + '<div class="'+mclass+' tparrows tp-leftarrow">'+rs_cm_markup_editor.getValue()+'</div>';
						t = t + '<div class="'+mclass+' tparrows tp-rightarrow">'+rs_cm_markup_editor.getValue()+'</div>';
						ap.html(t);	
					} else 
					if (sbut.hasClass("rs-nav-bullets-edit")) {
						bp.show();	
						var t = '<style>'+rs_cm_css_editor.getValue()+'</style>';
						t = t + '<div class="'+mclass+' tp-bullets">'
						for (var i=0;i<5;i++) {
							t = t + '<div class="tp-bullet">'+rs_cm_markup_editor.getValue()+'</div>';
						}
						t= t + '</div>';
						bp.html(t);
						var b = bp.find('.tp-bullet').first(),
							bw = b.outerWidth(true),
							bh = b.outerHeight(true),						
							mw = 0;
						bp.find('.tp-bullet').each(function(i) {
							jQuery(this).click(function() {
								bp.find('.tp-bullet').removeClass("selected");
								jQuery(this).addClass("selected");
							})
							var np = i*bw + i*10;
							jQuery(this).css({left:np+"px"});
							mw = mw + bw + 10;
						})
						mw = mw-10;
						bp.find('.tp-bullets').css({width:mw, height:bh});					
					} else
					if (sbut.hasClass("rs-nav-tabs-edit")) {
						tabp.show();
						var t = '<style>'+rs_cm_css_editor.getValue()+'</style>';
						t = t + '<div class="'+mclass+'"><div class="tp-tab">'+rs_cm_markup_editor.getValue()+'</div></div>';
						tabp.html(t);
						changeTabThumbSize();
						sizer.show();
					} else
					if (sbut.hasClass("rs-nav-thumbs-edit")) {
						thumbp.show();
						var t = '<style>'+rs_cm_css_editor.getValue()+'</style>';
						t = t + '<div class="'+mclass+'"><div class="tp-thumb">'+rs_cm_markup_editor.getValue()+'</div></div>';
						thumbp.html(t);
						sizer.show();
						changeTabThumbSize();					
					}

				}

				jQuery('input[name="rs-test-width"]').on("change",changeTabThumbSize);
				jQuery('input[name="rs-test-height"]').on("change",changeTabThumbSize);

				


				function changeTabThumbSize() {				
					var tabp = tabp = jQuery('.rs-tabs-preview'),
						thumbp = jQuery('.rs-thumbs-preview');
					tabp.find('.tp-tab').css({width:jQuery('input[name="rs-test-width"]').val(), height:jQuery('input[name="rs-test-height"]').val()});
					thumbp.find('.tp-thumb').css({width:jQuery('input[name="rs-test-width"]').val(), height:jQuery('input[name="rs-test-height"]').val()});
				}

				rs_cm_css_editor.setSize(null, 600);
				
				function rsCmModified(add_to, editor){												
					if(rs_current_editing !== false && cur_edit_type !== false){
						for(var key in rs_navigations){
							if(rs_navigations[key]['id'] == rs_current_editing){							
								rs_navigations[key][add_to][cur_edit_type] = editor.getValue();
								break;
							}
						}
					}
				}

				// COLLAPSE UL ON CLICK
				jQuery('body').on('click','.rs-editing-wrapper h4 .libtn',function() {
					var _t = jQuery(this),
						hw = _t.closest('.helper-wrappers'),
						ul = hw.find('ul.collapsable');
					
					ul.addClass("infocus");
					jQuery('.rs-editing-wrapper ul.collapsable').each(function() {
						var ul = jQuery(this),
							hw = ul.closest('.helper-wrappers');
						if (!ul.hasClass("infocus") && hw.hasClass("rea-open")) {
							ul.slideUp(100);
							hw.removeClass("rea-open")
						}
					});
					
					if (hw.hasClass("rea-open")) {
						ul.slideUp(100);
						hw.removeClass("rea-open");
					} else {
						ul.slideDown(100);
						hw.addClass("rea-open");
					}
					ul.removeClass("infocus");
				})
				
				jQuery('body').on('click', '.rs-nav-arrows-edit, .rs-nav-bullets-edit, .rs-nav-thumbs-edit, .rs-nav-tabs-edit', function(){
					var nav_id = jQuery(this).closest('.rs-nav-table-row').attr('id').replace('rs-nav-table-', ''),
						edit_title = jQuery(this).closest('.rs-nav-table-row').find('input[name="navigation-name"]').val(),
						cur_edit = {};
					
					for(var key in rs_navigations){
						if(rs_navigations[key]['id'] == nav_id){
							cur_edit = jQuery.extend(true, {}, rs_navigations[key]);
							break;
						}
					}
					
					jQuery('#reset-markup-arrow').hide();				
					jQuery('#reset-markup-bullets').hide();
					jQuery('#reset-markup-tabs').hide();
					jQuery('#reset-markup-thumbs').hide();

					jQuery('#reset-css-arrow').hide();				
					jQuery('#reset-css-bullets').hide();
					jQuery('#reset-css-tabs').hide();
					jQuery('#reset-css-thumbs').hide();
					jQuery('#reset-css-arrow-empty').hide();				
					jQuery('#reset-css-bullets-empty').hide();
					jQuery('#reset-css-tabs-empty').hide();
					jQuery('#reset-css-thumbs-empty').hide();

					if(jQuery.isEmptyObject(cur_edit)) return false;
					
					
					if(jQuery(this).hasClass('rs-nav-arrows-edit')){
						edit_title += ' - ' + rb_lang.arrows;					
						cur_edit_type = 'arrows';
						jQuery('#reset-markup-arrow').show();
						jQuery('#reset-css-arrow').show();					
						jQuery('#reset-css-arrow-empty').show();					
					}else if(jQuery(this).hasClass('rs-nav-bullets-edit')){
						edit_title += ' - ' + rb_lang.bullets;
						cur_edit_type = 'bullets';
						jQuery('#reset-markup-bullets').show();
						jQuery('#reset-css-bullets').show();
						jQuery('#reset-css-bullets-empty').show();
					}else if(jQuery(this).hasClass('rs-nav-thumbs-edit')){
						edit_title += ' - ' + rb_lang.thumbnails;
						cur_edit_type = 'thumbs';
						jQuery('#reset-markup-thumbs').show();
						jQuery('#reset-css-thumbs').show();
						jQuery('#reset-css-thumbs-empty').show();
					}else if(jQuery(this).hasClass('rs-nav-tabs-edit')){
						edit_title += ' - ' + rb_lang.tabs;
						cur_edit_type = 'tabs';
						jQuery('#reset-markup-tabs').show();
						jQuery('#reset-css-tabs').show();
						jQuery('#reset-css-tabs-empty').show();
					}else{
						return false;
					}


					
					var the_css = (typeof(cur_edit['css']) !== 'undefined' && cur_edit['css'] !== null && typeof(cur_edit['css'][cur_edit_type]) !== 'undefined') ? cur_edit['css'][cur_edit_type] : '';
					var the_markup = (typeof(cur_edit['markup']) !== 'undefined' && cur_edit['markup'] !== null && typeof(cur_edit['markup'][cur_edit_type]) !== 'undefined') ? cur_edit['markup'][cur_edit_type] : rs_nav_placeholder[cur_edit_type];
					
					
					if(cur_edit['css'] == null) cur_edit['css'] = {};
					if(cur_edit['markup'] == null) cur_edit['markup'] = {};
					
					if((typeof(cur_edit['css']) == 'undefined' || typeof(cur_edit['css'][cur_edit_type]) == 'undefined') || (typeof(cur_edit['markup']) == 'undefined' || typeof(cur_edit['markup'][cur_edit_type]) == 'undefined')){
						if(typeof(cur_edit.default) !== 'undefined' && cur_edit.default == true) return false;
						
						if(!confirm(rb_lang.create_this_nav_element)){
							return false;
						}else{
							if(rs_navigations[key]['css'] == null) rs_navigations[key]['css'] = {};
							if(rs_navigations[key]['markup'] == null) rs_navigations[key]['markup'] = {};
							rs_navigations[key]['css'][cur_edit_type] = the_css;
							rs_navigations[key]['markup'][cur_edit_type] = the_markup;
							rs_navigations[key]['settings']['width'] = {"thumbs":"160","arrows":"160","bullets":"160","tabs":"160"};
							rs_navigations[key]['settings']['height'] = {"thumbs":"160","arrows":"160","bullets":"160","tabs":"160"};
							
							jQuery(this).find('.rs-edit-nav').show();
							jQuery(this).find('.rs-edit-cancel-nav').hide();
						}
					}
					
					
					jQuery('.rs-nav-table-row').removeClass('selected');
					jQuery('.rs-nav-table-row').find('*').removeClass('selected');
					
					jQuery(this).closest('.rs-nav-table-row').addClass('selected');
					jQuery(this).closest('.rs-nav-table-cell').addClass('selected');
					
					jQuery(this).addClass('selected');
					
					jQuery('.rs-editing-wrapper').show();
					jQuery('.rs-nav-editing-title').text(edit_title);
					
					rs_current_editing = nav_id;
					
					rs_cm_css_editor.setValue(the_css);
					rs_cm_markup_editor.setValue(the_markup);
					
					rs_cm_css_editor.refresh();
					rs_cm_markup_editor.refresh();
					
					cur_edit.settings = cur_edit.settings === undefined || typeof(cur_edit.settings) === 'string' ? {width: {"thumbs":"160","arrows":"160","bullets":"160","tabs":"160"}, height: {"thumbs":"160","arrows":"160","bullets":"160","tabs":"160"}} : cur_edit.settings;
					cur_edit.settings.width = cur_edit.settings.width === undefined || typeof(cur_edit.settings.width) === 'string' ? {"thumbs":"160","arrows":"160","bullets":"160","tabs":"160"} : cur_edit.settings.width;
					cur_edit.settings.height = cur_edit.settings.height === undefined || typeof(cur_edit.settings.height) === 'string' ? {"thumbs":"160","arrows":"160","bullets":"160","tabs":"160"} : cur_edit.settings.height;
					
					cur_edit.settings.width[cur_edit_type] = cur_edit.settings.width[cur_edit_type] === undefined ? "160" : cur_edit.settings.width[cur_edit_type];
					cur_edit.settings.height[cur_edit_type] = cur_edit.settings.height[cur_edit_type] === undefined ? "160" : cur_edit.settings.height[cur_edit_type];
					
					jQuery('input[name="rs-test-width"]').val(cur_edit.settings.width[cur_edit_type]);
					jQuery('input[name="rs-test-height"]').val(cur_edit.settings.height[cur_edit_type]);
					
					drawEditor();
					jQuery('.rs-markup-selector').click();
					setCMSize();
					
					if(typeof(cur_edit.default) !== 'undefined' && cur_edit.default == true){ //disable both editors
						rs_cm_css_editor.setOption("readOnly", true);
						rs_cm_markup_editor.setOption("readOnly", true);
					}else{ //enable both editors
						rs_cm_css_editor.setOption("readOnly", false);
						rs_cm_markup_editor.setOption("readOnly", false);
					}
					
				});

				jQuery('body').on('mouseenter', '.rs-nav-arrows-edit, .rs-nav-bullets-edit, .rs-nav-thumbs-edit, .rs-nav-tabs-edit', function(){
					var e = jQuery(this),
							nav_id = e.closest('.rs-nav-table-row').attr('id').replace('rs-nav-table-', ''),
							edit_title = e.closest('.rs-nav-table-row').find('input[name="navigation-name"]').val(),
							cur_edit = {};				
					for(var key in rs_navigations){
						if(rs_navigations[key]['id'] == nav_id){
							cur_edit = jQuery.extend(true, {}, rs_navigations[key]);
							break;
						}
					}
					var cur_edit_type="";

					if(e.hasClass('rs-nav-arrows-edit')){					
						cur_edit_type = 'arrows';					
					}else if(e.hasClass('rs-nav-bullets-edit')){
						cur_edit_type = 'bullets';					
					}else if(e.hasClass('rs-nav-thumbs-edit')){					
						cur_edit_type = 'thumbs';					
					}else if(e.hasClass('rs-nav-tabs-edit')){					
						cur_edit_type = 'tabs';					
					}else{
						return false;
					}
					
					var the_css = (typeof(cur_edit['css']) !== 'undefined' && cur_edit['css'] !== null && typeof(cur_edit['css'][cur_edit_type]) !== 'undefined') ? cur_edit['css'][cur_edit_type] : '',
						the_markup = (typeof(cur_edit['markup']) !== 'undefined' && cur_edit['markup'] !== null && typeof(cur_edit['markup'][cur_edit_type]) !== 'undefined') ? cur_edit['markup'][cur_edit_type] : rs_nav_placeholder[cur_edit_type],
						sline = e.closest('.rs-nav-table-row.rs-nav-entry-wrap'),
						settings = (typeof(cur_edit['settings']) !== 'undefined' && cur_edit['settings'] !== null) ? cur_edit['settings'] : "",				
						mclass = UniteAdminRb.sanitize_input(sline.find('input[name="navigation-name"]').val().toLowerCase());
					
					if(cur_edit['css'] == null) return false;
					if(cur_edit['markup'] == null) return false;

					previewNav(e,mclass,the_css,the_markup,settings);
					var pos = e.offset(),
						pp =jQuery('#viewWrapper').offset(),
						ll = pos.left-pp.left,
						tt = pos.top-pp.top+65;
					punchgs.TweenLite.set(jQuery('#preview-nav-wrapper'),{top:tt,left:ll,autoAlpha:1,overwrite:"all"});
					
				});
				jQuery('body').on('mouseleave', '.rs-nav-arrows-edit, .rs-nav-bullets-edit, .rs-nav-thumbs-edit, .rs-nav-tabs-edit', function(e){
					punchgs.TweenLite.set(jQuery('#preview-nav-wrapper'),{autoAlpha:0});
				});
				
				
				jQuery('.rs-markup-selector').click(function(){
					jQuery('.rs-markup-wrapper').show();
					jQuery('.rs-css-wrapper').hide();
					jQuery(this).addClass('open');
					jQuery('.rs-css-selector').removeClass('open');
					rs_cm_markup_editor.refresh();
					setCMSize();
				});
				
				jQuery('.rs-css-selector').click(function(){
					jQuery('.rs-css-wrapper').show();
					jQuery('.rs-markup-wrapper').hide();
					jQuery(this).addClass('open');
					jQuery('.rs-markup-selector').removeClass('open');
					rs_cm_css_editor.refresh();
					setCMSize();
				});
				
				
				jQuery('.rs-element-list li .libtn').click(function(){
					if(rsCheckIfDefaultNav()) return false;
					
					var li = jQuery(this).parent(),
						call = li.data('call'),
						ins = li.data('insert'),
						edit_title = UniteAdminRb.sanitize_input(jQuery('.rs-nav-table-row.rs-nav-entry-wrap.selected').find('input[name="navigation-name"]').val().toLowerCase());

					if (call!=='params_markup' && call!=='params_special')
						jQuery('.rs-element-add').slideUp(100);

					if (call==='arrows_markup')
						rs_cm_markup_editor.setValue(rs_nav_placeholder['arrows']);
					else
					if (call==='bullets_markup') 
						rs_cm_markup_editor.setValue(rs_nav_placeholder['bullets']);
					else
					if (call==='thumbs_markup')
						rs_cm_markup_editor.setValue(rs_nav_placeholder['thumbs']);
					else
					if (call==='tabs_markup')
						rs_cm_markup_editor.setValue(rs_nav_placeholder['tabs']);
					else
					if (call==='arrows_css') 
						rs_cm_css_editor.setValue(rs_css_placeholder['arrows'].replace(/\{\{class\}\}/g,edit_title).toLowerCase());
					else
					if (call==='arrows_css_empty') 
						rs_cm_css_editor.setValue(rs_css_placeholder['arrows_empty'].replace(/\{\{class\}\}/g,edit_title).toLowerCase());
					else
					if (call==='bullets_css') 
						rs_cm_css_editor.setValue(rs_css_placeholder['bullets'].replace(/\{\{class\}\}/g,edit_title).toLowerCase());
					else
					if (call==='bullets_css_empty') 
						rs_cm_css_editor.setValue(rs_css_placeholder['bullets_empty'].replace(/\{\{class\}\}/g,edit_title).toLowerCase());
					
					else
					if (call==='params_markup')						
						rs_cm_markup_editor.replaceSelection('{{param'+li.data('paramid')+'}}',"end");
					else
					if (call==='params_special')
						rs_cm_markup_editor.replaceSelection('{{'+li.data('paramid')+'}}',"end");
					else {										
							var add = false;
							if (!li.hasClass("rea-open")) {
								li.find('.rs-element-add').slideDown(100);
								add = true;							
							} 
							jQuery('.rs-element-list li').removeClass("rea-open");						
							if (add) li.addClass("rea-open");					
					}
				});

				
				jQuery('input[name="rs-test-width"], input[name="rs-test-height"]').change(function(){
					if(rsCheckIfDefaultNav()) return false;
					
					if(rs_current_editing !== false && cur_edit_type !== false){
						for(var key in rs_navigations){
							if(rs_navigations[key]['id'] == rs_current_editing){
								if(typeof(rs_navigations[key]['settings']) == 'undefined' || typeof(rs_navigations[key]['settings']) == 'string') rs_navigations[key]['settings'] = {};
								rs_navigations[key]['settings'] = rs_navigations[key]['settings'] === undefined || typeof(rs_navigations[key]['settings']) === 'string' ? {width: {"thumbs":"160","arrows":"160","bullets":"160","tabs":"160"}, height: {"thumbs":"160","arrows":"160","bullets":"160","tabs":"160"}} : rs_navigations[key]['settings'];
								
								rs_navigations[key]['settings'].width = rs_navigations[key]['settings'].width === undefined || typeof(rs_navigations[key]['settings'].width) === 'string' ? {"thumbs":"160","arrows":"160","bullets":"160","tabs":"160"} : rs_navigations[key]['settings'].width;
								rs_navigations[key]['settings'].height = rs_navigations[key]['settings'].height === undefined || typeof(rs_navigations[key]['settings'].height) === 'string' ? {"thumbs":"160","arrows":"160","bullets":"160","tabs":"160"} : rs_navigations[key]['settings'].height;
								
								rs_navigations[key]['settings']['height'][cur_edit_type] = parseInt(jQuery('input[name="rs-test-height"]').val());
								rs_navigations[key]['settings']['width'][cur_edit_type] = parseInt(jQuery('input[name="rs-test-width"]').val());
								break;
							}
						}
					}
				});
				
				jQuery('#rs-save-navigation-style').click(function(){
					

					UniteAdminRb.ajaxRequest('change_navigations', rs_navigations, function(data){
						var cur_id = rsNavGetSelectedId();
						if (cur_id !== false)
							var cur_type = rsNavGetSelectedNavType(cur_id);
										
						if (data.success == true) {
							rs_navigations = data.navs;
					
							//rebuild all entries
							jQuery('.rs-nav-entry-wrap').remove();
							
							latest_nav_id = 0;
							
							rsAddAllNavigationEntries();
						}
						
						rsNavUnselectAll();
						
						if(cur_id !== false)
							rsNavSelectById(cur_id, cur_type);
					});
				});
				
				
				jQuery('body').on('click', '.rs-nav-reset', function(){
					if(confirm(rb_lang.this_will_reset_navigation)){
						var nav_id = jQuery(this).closest('.rs-nav-table-row').attr('id').replace('rs-nav-table-', '');
						for(var key in rs_navigations){
							if(rs_navigations[key]['id'] != nav_id) continue;
							
							if(typeof(rs_navigations[key]['settings']) !== 'undefined' && typeof(rs_navigations[key]['settings']['original']) !== 'undefined'){
								rs_navigations[key]['css'] = {};
								if(typeof(rs_navigations[key]['settings']['original']['css']) !== 'undefined'){
									for(var ckey in rs_navigations[key]['settings']['original']['css']){
										rs_navigations[key]['css'][ckey] = rs_navigations[key]['settings']['original']['css'][ckey];
									}
								}
								
								rs_navigations[key]['markup'] = {};
								if(typeof(rs_navigations[key]['settings']['original']['markup']) !== 'undefined'){
									for(var ckey in rs_navigations[key]['settings']['original']['markup']){
										rs_navigations[key]['markup'][ckey] = rs_navigations[key]['settings']['original']['markup'][ckey];
									}
								}
							}
						}
						
						//refresh all elements in editor
						var cur_id = rsNavGetSelectedId();
						if(cur_id !== false)
							var cur_type = rsNavGetSelectedNavType(cur_id);
				
						jQuery('.rs-nav-entry-wrap').remove();								
						latest_nav_id = 0;
						
						rsAddAllNavigationEntries();
						
						rsNavUnselectAll();
						
						if(cur_id !== false)
							rsNavSelectById(cur_id, cur_type);
					}
				});
				
				
				jQuery('body').on('click', '.rs-nav-delete', function(){
					var nav_id = jQuery(this).closest('.rs-nav-table-row').attr('id').replace('rs-nav-table-', '');
					if(confirm(rb_lang.delete_navigation)){
						
						for(var key in rs_navigations){
							if(rs_navigations[key]['id'] != nav_id) continue;

							if(typeof(rs_navigations[key]['new']) !== 'undefined' && rs_navigations[key]['new'] == true){
								delete rs_navigations[key];
								
								jQuery('#rs-nav-table-'+nav_id).remove();
								
								rsNavUnselectAll();
								
								return true;
							}
						}

						UniteAdminRb.ajaxRequest('delete_navigation', nav_id, function(data){
							if(data.success == true){
								for(var key in rs_navigations){
									if(rs_navigations[key]['id'] != nav_id) continue;
									
									delete rs_navigations[key];
									break;
								}
								jQuery('#rs-nav-table-'+nav_id).remove();
								
								rsNavUnselectAll();
							}
						});
						
					}
				});
				
				
				jQuery('body').on('click', '.rs-nav-duplicate',function(){
					var nav_id = jQuery(this).closest('.rs-nav-table-row').attr('id').replace('rs-nav-table-', '');
					
					latest_nav_id++;
					
					for(var key in rs_navigations){
						if(rs_navigations[key]['id'] == nav_id){
							var the_copy = jQuery.extend(true, the_copy, rs_navigations[key]);
							the_copy['id'] = latest_nav_id;
							the_copy['name'] += '-'+latest_nav_id;
							the_copy['handle'] += '-'+latest_nav_id;
							
							if(rsCheckIfDefaultNav(nav_id)){
								delete(the_copy['default']);
							}
							if(typeof(the_copy['settings']) === 'undefined' || the_copy['settings'] == '' || the_copy['settings'] == null) the_copy['settings'] = {};
							
							the_copy['settings']['original'] = {css:{},markup:{}};
							the_copy['settings']['original']['css'] = rs_navigations[key]['css'];
													
							var re = new RegExp('.'+rs_navigations[key]['name'].toLowerCase(), 'g');

							for(var t in the_copy['css']){ 
								the_copy['css'][t] = the_copy['css'][t].replace(re, '.'+the_copy['name'].toLowerCase()).toLowerCase();
							}
							
							the_copy['settings']['original']['markup'] = rs_navigations[key]['markup'];
							
							rsAddNavigationElement(the_copy, true);
							
							the_copy['new'] = true;
							
							rs_navigations.push(the_copy);
							break;
						}
					}
					
				});
				
				var updateNavName = function() {
					var inp = jQuery(".focused-navname"),
						cell = inp.closest('.rs-nav-table-row.rs-nav-entry-wrap'),
						curselected = cell.hasClass("selected"),
						updated = false,
						nav_id = inp.closest('.rs-nav-table-row').attr('id').replace('rs-nav-table-', ''),
						name = inp.val(),
						name_handle = UniteAdminRb.sanitize_input(name).toLowerCase();
					 jQuery(".focused-navname").removeClass("focused-navname")
					if(name_handle.length < 3){
						alert(rb_lang.name_too_short_sanitize_3);
						return false;
					}
					
					for(var key in rs_navigations){
						if(rs_navigations[key]['id'] == nav_id){
							updated = key;						
							continue;
						}
						if(rs_navigations[key]['name'] == name){
							alert(rb_lang.nav_name_already_exists);
							return false;
						}
					}
									
					if(updated !== false){
						var oldname = rs_navigations[updated]['handle'],
							oldname_b = rs_navigations[updated]['name'];
						rs_navigations[updated]['name'] = name;
						rs_navigations[updated]['handle'] = name_handle;
						var regex = new RegExp("\\."+oldname+"|."+oldname_b,"gi");
							

						if (curselected) {
							var el = cell.find('.selected .selected');
							el = el.hasClass('rs-nav-bullets-edit') ? "bullets" : el.hasClass('rs-nav-arrows-edit') ? "arrows" : el.hasClass('rs-nav-thumbs-edit') ? "thumbs" : el.hasClass('rs-nav-tabs-edit') ? "tabs" : "none";
							if (el!=="none") {							
								rs_cm_css_editor.setValue(rs_cm_css_editor.getValue().replace(regex,"."+name_handle));
								rs_cm_markup_editor.setValue(rs_cm_markup_editor.getValue().replace(regex,"."+name_handle));
							}
						} 
											
						if (rs_navigations[updated].css.arrows) rs_navigations[updated].css.arrows = rs_navigations[updated].css.arrows.replace(regex,"."+name_handle);
						if (rs_navigations[updated].markup.arrows) rs_navigations[updated].markup.arrows = rs_navigations[updated].markup.arrows.replace(regex,"."+name_handle);
					
						if (rs_navigations[updated].css.bullets) rs_navigations[updated].css.bullets = rs_navigations[updated].css.bullets.replace(regex,"."+name_handle);
						if (rs_navigations[updated].markup.bullets) rs_navigations[updated].markup.bullets = rs_navigations[updated].markup.bullets.replace(regex,"."+name_handle);
					
						if (rs_navigations[updated].css.thumbs) rs_navigations[updated].css.thumbs = rs_navigations[updated].css.thumbs.replace(regex,"."+name_handle);
						if (rs_navigations[updated].markup.thumbs) rs_navigations[updated].markup.thumbs = rs_navigations[updated].markup.thumbs.replace(regex,"."+name_handle);
					
						if (rs_navigations[updated].css.tabs) rs_navigations[updated].css.tabs = rs_navigations[updated].css.tabs.replace(regex,"."+name_handle);
						if (rs_navigations[updated].markup.tabs) rs_navigations[updated].markup.tabs = rs_navigations[updated].markup.tabs.replace(regex,"."+name_handle);
						

						jQuery(this).parent().siblings('.rs-nav-name-wrap').find('.rs-nav-name-text').text(rs_navigations[updated]['name']);				
						jQuery(this).parent().siblings('.rs-nav-name-wrap').show();
						jQuery(this).parent().hide();
					}else{
						alert(rb_lang.could_not_update_nav_name);
					}
				}
				
				jQuery('body').on('click', '.rs-nav-name-wrap', function(){
					//check if this is a default
					var nav_id = jQuery(this).closest('.rs-nav-table-row').attr('id').replace('rs-nav-table-', '');
					if(rsCheckIfDefaultNav(nav_id)) return false;				
					jQuery(this).siblings('.rs-nav-name-edit-wrap').show().find('input').focus();				
					jQuery(this).hide();				
				});			
				
				jQuery('body').on('click', '.rs-edit-navigation-name', updateNavName);
				jQuery('body').on('focus','input[name="navigation-name"]',function() {
					jQuery(this).addClass("focused-navname");
				});
				jQuery('body').on('blur','input[name="navigation-name"]',updateNavName);
				
				
				jQuery('body').on('click', '.rs-nav-save', function(){
					var nav_id = jQuery(this).closest('.rs-nav-table-row').attr('id').replace('rs-nav-table-', '');

					for(var key in rs_navigations){
						if(rs_navigations[key]['id'] == nav_id){
							UniteAdminRb.ajaxRequest('change_specific_navigation', rs_navigations[key], function(data){
								var cur_id = rsNavGetSelectedId();
								if(cur_id !== false)
									var cur_type = rsNavGetSelectedNavType(cur_id);
								
								if(data.success == true){
									rs_navigations = data.navs;
							
									//rebuild all entries
									jQuery('.rs-nav-entry-wrap').remove();
									latest_nav_id = 0;
									rsAddAllNavigationEntries();
								}
								
								rsNavUnselectAll();
								
								if(cur_id !== false)
									rsNavSelectById(cur_id, cur_type);
							});
							break;
						}
					}
					
				});
				
				jQuery('body').on('click', '.rs-remove-nav-element', function(){
					if(rs_current_editing === false) return false;
					
					if(confirm(rb_lang.remove_nav_element)){
						for(var key in rs_navigations){
							if(rs_navigations[key]['id'] == rs_current_editing){
								delete rs_navigations[key]['css'][cur_edit_type];
								delete rs_navigations[key]['markup'][cur_edit_type];
								rsNavUnselectAll();
								
								break;
							}
						}
					}
				});
				
				
				function rsNavGetSelectedId(){
					var curselel = jQuery('.rs-nav-entry-wrap.selected');
					if(curselel.length == 0) return false;
					var cur_id = curselel.attr('id').replace('rs-nav-table-','');
					return cur_id;
				}
				
				function rsNavGetSelectedNavType(nav_id){
					var nav_el = jQuery('#rs-nav-table-'+nav_id);
					return nav_el.find('.rs-nav-table-cell.selected .selected').attr('class').replace('selected', '');
				}
				
				function rsNavSelectById(nav_id, nav_type){
					var found = false;
					jQuery('#rs-nav-table-'+nav_id).find('.'+nav_type).click();
				}
				
				function rsNavUnselectAll(){
					rs_current_editing = false;
					cur_edit_type = false;
					
					jQuery('.rs-nav-table-row').removeClass('selected');
					jQuery('.rs-nav-table-cell').each(function(){
						if(jQuery(this).hasClass('selected')){
							jQuery(this).find('.rs-edit-nav').hide();
							jQuery(this).find('.rs-edit-cancel-nav').show();
						}
						jQuery(this).removeClass('selected');
					});
					jQuery('.rs-nav-table-row').find('*').removeClass('selected');
					
					jQuery('.rs-editing-wrapper').hide();
				}
				
				
				function rsCheckIfDefaultNav(check_id){
					var check_for = (check_id !== undefined) ? check_id : rs_current_editing;
					
					if(check_for !== false){
						for(var key in rs_navigations){
							if(rs_navigations[key]['id'] == check_for){
								if(typeof(rs_navigations[key]['default']) !== 'undefined' && rs_navigations[key]['default'] == true) return true;
							}
						}
					}
					return false;
				}
				
				function rsAddAllNavigationEntries(){
					rsAddNavigationHeader('Custom Navigations', 'rs-custom-t-wrap');
					
					for(var i = 1; i<=2; i++){
						if(i === 2) rsAddNavigationHeader('Default Navigations', 'rs-default-t-wrap');
						
						for(var key in rs_navigations){
							if(i === 1){
								if(typeof(rs_navigations[key]['default']) !== 'undefined') continue;
							}else{
								if(typeof(rs_navigations[key]['default']) === 'undefined') continue;
							}
							
							if(parseInt(rs_navigations[key]['id']) > parseInt(latest_nav_id) && parseInt(rs_navigations[key]['id']) < 5000) latest_nav_id = parseInt(rs_navigations[key]['id']);
							
							if(typeof(rs_navigations[key]['settings']) !== 'object'){
								rs_navigations[key]['settings'] = jQuery.parseJSON(rs_navigations[key]['settings']);
							}
							if(typeof(rs_navigations[key]['settings']) == 'string' || rs_navigations[key]['settings'] == null) rs_navigations[key]['settings'] = {};
							
							rsAddNavigationElement(rs_navigations[key]);
						}
					}
				}
				
				jQuery('#rs-add-css-color').click(function(){
					if(rsCheckIfDefaultNav()) return false;
					
					var br_c = jQuery('input[name="rs-color"]').val();
					
					var css = 'color: '+br_c+';';
					
					rs_cm_css_editor.replaceSelection(css+"\n","end");
				});
				
				jQuery('#rs-add-css-border-radius').click(function(){
					if(rsCheckIfDefaultNav()) return false;
					
					var br_tl = Math.round(jQuery('input[name="rs-border-radius-top-left"]').val());
					var br_tr = Math.round(jQuery('input[name="rs-border-radius-top-right"]').val());
					var br_br = Math.round(jQuery('input[name="rs-border-radius-bottom-right"]').val());
					var br_bl = Math.round(jQuery('input[name="rs-border-radius-bottom-left"]').val());
					var css = 'border-radius: '+br_tl+'px '+br_tr+'px '+br_br+'px '+br_bl+'px;';
					
					rs_cm_css_editor.replaceSelection(css+"\n","end");
				});
				
				jQuery('#rs-add-css-border').click(function(){
					if(rsCheckIfDefaultNav()) return false;
					
					var br_t = Math.round(jQuery('input[name="rs-border-top"]').val());
					var br_r = Math.round(jQuery('input[name="rs-border-right"]').val());
					var br_b = Math.round(jQuery('input[name="rs-border-bottom"]').val());
					var br_l = Math.round(jQuery('input[name="rs-border-left"]').val());
					var br_c = jQuery('input[name="rs-border-color"]').val();
					
					var css = 'border-top: solid '+br_t+'px '+br_c+';';
					css += "\n"+'border-right: solid '+br_r+'px '+br_c+';';
					css += "\n"+'border-bottom: solid '+br_b+'px '+br_c+';';
					css += "\n"+'border-left: solid '+br_l+'px '+br_c+';';
					
					rs_cm_css_editor.replaceSelection(css+"\n","end");
				});
				
				jQuery('#rs-add-css-text-shadow').click(function(){
					if(rsCheckIfDefaultNav()) return false;
					
					var ts_a = Math.round(jQuery('input[name="rs-text-shadow-angle"]').val());
					var ts_d = Math.round(jQuery('input[name="rs-text-shadow-distance"]').val());
					var ts_b = Math.round(jQuery('input[name="rs-text-shadow-blur"]').val());
					var ts_c = jQuery('input[name="rs-text-shadow-color"]').val();
					var ts_o = Math.round(jQuery('input[name="rs-text-shadow-opacity"]').val()) / 100;
					
					ts_c = UniteAdminRb.convertHexToRGB(ts_c);
					
					ts_a = ts_a*((Math.PI)/180);
					var x = Math.round(ts_d * Math.cos(ts_a));
					var y = Math.round(ts_d * Math.sin(ts_a));
					
					var css = 'text-shadow: '+x+'px '+y+'px '+ts_b+'px  rgba('+ts_c+', '+ts_o+');';
					
					rs_cm_css_editor.replaceSelection(css+"\n","end");
				});
				
				jQuery('#rs-add-css-box-shadow').click(function(){
					if(rsCheckIfDefaultNav()) return false;
					
					var bs_a = Math.round(jQuery('input[name="rs-box-shadow-angle"]').val());
					var bs_d = Math.round(jQuery('input[name="rs-box-shadow-distance"]').val());
					var bs_b = Math.round(jQuery('input[name="rs-box-shadow-blur"]').val());
					var bs_c = jQuery('input[name="rs-box-shadow-color"]').val();
					var bs_o = Math.round(jQuery('input[name="rs-box-shadow-opacity"]').val()) / 100;
					
					bs_c = UniteAdminRb.convertHexToRGB(bs_c);
					
					bs_a = bs_a*((Math.PI)/180);
					var x = Math.round(bs_d * Math.cos(bs_a));
					var y = Math.round(bs_d * Math.sin(bs_a));
					
					var css = 'box-shadow: '+x+'px '+y+'px '+bs_b+'px rgba('+bs_c+', '+bs_o+');';
					
					rs_cm_css_editor.replaceSelection(css+"\n","end");
				});
				
				jQuery('#rs-add-css-font-family').click(function(){
					if(rsCheckIfDefaultNav()) return false;
					
					var ff = jQuery('select[name="rs-font-family"] option:selected').val();
					var css = 'font-family: '+ff+';';
					
					rs_cm_css_editor.replaceSelection(css+"\n","end");
				});
				
				jQuery('#rs-add-new-navigation-element').click(function(){
					latest_nav_id++;
					
					var the_copy = {};
					
					the_copy['id'] = latest_nav_id;
					the_copy['name'] = 'New-'+latest_nav_id;
					the_copy['handle'] = 'new-'+latest_nav_id;
					the_copy['settings'] = {};
					
					rsAddNavigationElement(the_copy, true);
					
					the_copy['new'] = true;
					
					rs_navigations.push(the_copy);
				});

				jQuery('#list-of-navigations').perfectScrollbar({wheelPropagation:true});
				jQuery('#list-of-navigations').perfectScrollbar('update');
				

				
				var setCMSize = function() {
					var newsize = (jQuery('.rs-editing-markups-wrap').width() - jQuery('.rs-css-elements').width())-25;
					jQuery('.CodeMirror .CodeMirror-lines').css({width:newsize + "px"});
				}


				jQuery(window).resize(setCMSize);

				jQuery('.showhidehelper').click(function() {
					var elm = jQuery('.rs-markup-elements'),
						elc = jQuery('.rs-css-elements');

					if (elm.hasClass("small")) {
						elm.css({width:"auto",minWidth:"200px"});
						elc.css({width:"auto",minWidth:"200px"});
						jQuery('.closemeshowhide').show();
						jQuery('.showhidehelper').removeClass("small");
						elm.removeClass("small");
					} else {
						elm.css({width:"20px",minWidth:"20px"});
						elc.css({width:"20px",minWidth:"20px"});
						elm.addClass("small");
						jQuery('.showhidehelper').addClass("small");
						jQuery('.closemeshowhide').hide();

					}
					setCMSize();	
				});						
			});
			
			setTimeout(function() {					
			},1000)

			{/literal}
			
		</script>


		
	</div>


	{literal}
	<script type="text/html" id="tmpl-rs-navigation-header-wrap">
		<div class="rs-nav-table-row rs-nav-entry-wrap rs-nav-fullrow {{ data['type'] }}">
			<div class="rs-nav-table-cell rs-nav-fullcell">{{ data['title'] }}</div>
		</div>
	</script>

	<script type="text/html" id="tmpl-rs-navigation-wrap">
		<div class="rs-nav-table-row rs-nav-entry-wrap" id="rs-nav-table-{{ data['id'] }}">
			<div class="rs-nav-table-cell">{{ data['id'] }}</div>
			<div class="rs-nav-table-cell" style="position: relative;">
				<span class="rs-nav-name-edit-wrap" style="display: none;"><input class="regular-text" name="navigation-name" type="text" value="{{ data['name'] }}" /> <i class="input-edit-icon rs-edit-navigation-name"></i></span>
				<span class="rs-nav-name-wrap"><span class="rs-nav-name-text">{{ data['name'] }}</span> <# if ( data.edit ) { #><i class="input-edit-icon"></i><# } #></span>
			</div>
			<div class="rs-nav-table-cell">
				<a href="javascript:void(0);" class="rs-nav-arrows-edit">
					<span class="rs-edit-nav" style="display:{{ data['show-arrows'] }}">{{ data['show_text'] }}</span>
					<i class="eg-icon-plus-circled rs-edit-cancel-nav" style="display:{{ data['hide-arrows'] }}"></i>
				</a>
			</div>
			<div class="rs-nav-table-cell">
				<a href="javascript:void(0);" class="rs-nav-bullets-edit">
					<span class="rs-edit-nav" style="display:{{ data['show-bullets'] }}">{{ data['show_text'] }}</span>
					<i class="eg-icon-plus-circled rs-edit-cancel-nav" style="display:{{ data['hide-bullets'] }}"></i>
				</a>
			</div>
			<div class="rs-nav-table-cell">
				<a href="javascript:void(0);" class="rs-nav-thumbs-edit">
					<span class="rs-edit-nav" style="display:{{ data['show-thumbs'] }}">{{ data['show_text'] }}</span>
					<i class="eg-icon-plus-circled rs-edit-cancel-nav" style="display:{{ data['hide-thumbs'] }}"></i>
				</a>
			</div>
			<div class="rs-nav-table-cell">
				<a href="javascript:void(0);" class="rs-nav-tabs-edit">
					<span class="rs-edit-nav" style="display:{{ data['show-tabs'] }}">{{ data['show_text'] }}</span>
					<i class="eg-icon-plus-circled rs-edit-cancel-nav" style="display:{{ data['hide-tabs'] }}"></i>
				</a>
			</div>
			<div class="rs-nav-table-cell"><# if ( data.edit ) { #><a href="javascript:void(0);" class="rs-nav-delete"><i class="rbicon-trash"></i></a><# } #></div>
			<div class="rs-nav-table-cell"><a href="javascript:void(0);" class="rs-nav-duplicate"><i class="rbicon-picture"></i></a></div>
			<div class="rs-nav-table-cell"><# if ( data.edit ) { #><a href="javascript:void(0);" class="rs-nav-save"><i class="rbicon-arrows-ccw"></i></a><# } #></div>
			<div class="rs-nav-table-cell">&nbsp;</div>
		</div>
	</script>
	{/literal}

</div>

<div id="divColorPicker" style="display:none;"></div>
