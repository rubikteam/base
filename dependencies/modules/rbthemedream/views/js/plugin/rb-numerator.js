/* 
 *   jQuery Numerator Plugin 0.2.0
 *   https://github.com/garethdn/jquery-numerator
 *
 *   Copyright 2013, Gareth Nolan
 *   http://ie.linkedin.com/in/garethnolan/

 *   Based on jQuery Boilerplate by Zeno Rocha with the help of Addy Osmani
 *   http://jqueryboilerplate.com
 *
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/MIT
 */
!function($,window,document,undefined){"use strict";function Plugin(element,options){this.element=element,this.settings=$.extend({},defaults,options),this._defaults=defaults,this._name=pluginName,this.init()}var pluginName="numerator",defaults={easing:"swing",duration:500,delimiter:undefined,rounding:0,toValue:undefined,fromValue:0,queue:!1,onStart:function(){},onStep:function(){},onProgress:function(){},onComplete:function(){}};Plugin.prototype={init:function(){this.parseElement(),this.setValue()},parseElement:function(){var elText=$.trim($(this.element).text());this.settings.fromValue=this.format(elText),this.settings.toValue=this.format($(this.element).data("to_value"))},setValue:function(){var self=this;$({value:self.settings.fromValue}).animate({value:self.settings.toValue},{duration:parseInt(self.settings.duration),easing:self.settings.easing,start:self.settings.onStart,step:function(now,fx){$(self.element).text(self.format(now)),self.settings.onStep(now,fx)},progress:self.settings.onProgress,complete:self.settings.onComplete})},format:function(value){var self=this;return value=parseInt(this.settings.rounding)<1?parseInt(value):parseFloat(value).toFixed(parseInt(this.settings.rounding)),self.settings.delimiter?this.delimit(value):value},delimit:function(value){var self=this;if(value=value.toString(),self.settings.rounding&&parseInt(self.settings.rounding)>0){var decimals=value.substring(value.length-(self.settings.rounding+1),value.length),wholeValue=value.substring(0,value.length-(self.settings.rounding+1));return self.addCommas(wholeValue)+decimals}return self.addCommas(value)},addCommas:function(value){return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g,this.settings.delimiter)}},$.fn[pluginName]=function(options){return this.each(function(){$.data(this,"plugin_"+pluginName)&&$.data(this,"plugin_"+pluginName,null),$.data(this,"plugin_"+pluginName,new Plugin(this,options))})}}(jQuery,window,document);