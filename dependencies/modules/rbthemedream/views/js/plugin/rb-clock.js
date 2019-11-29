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
(function(){$.fn.lofCountDown=function(options){return this.each(function(){new $.lofCountDown(this,options)})}
$.lofCountDown=function(obj,options){this.options=$.extend({autoStart:!0,LeadingZero:!0,DisplayFormat:"<div>%%D%%</div><div>%%H%%</div><div>%%M%%</div><div>%%S%%</div>",FinishMessage:"Time Finished",CountActive:!0,TargetDate:null},options||{});if(this.options.TargetDate==null||this.options.TargetDate==''){return}
this.timer=null;this.element=obj;this.CountStepper=-1;this.CountStepper=Math.ceil(this.CountStepper);this.SetTimeOutPeriod=(Math.abs(this.CountStepper)-1)*1000+990;var dthen=new Date(this.options.TargetDate);var dnow=new Date();if(this.CountStepper>0){ddiff=new Date(dnow-dthen)}
else{ddiff=new Date(dthen-dnow)}
gsecs=Math.floor(ddiff.valueOf()/1000);this.CountBack(gsecs,this)};$.lofCountDown.fn=$.lofCountDown.prototype;$.lofCountDown.fn.extend=$.lofCountDown.extend=$.extend;$.lofCountDown.fn.extend({calculateDate:function(secs,num1,num2){var s=((Math.floor(secs/num1))%num2).toString();if(this.options.LeadingZero&&s.length<2){s="0"+s}
return"<b>"+s+"</b>"},CountBack:function(secs,self){if(secs<0){self.element.innerHTML='<div class="lof-labelexpired"><span> '+self.options.FinishMessage+"</span></div>";return}
clearInterval(self.timer);DisplayStr=self.options.DisplayFormat.replace(/%%D%%/g,self.calculateDate(secs,86400,100000));DisplayStr=DisplayStr.replace(/%%H%%/g,self.calculateDate(secs,3600,24));DisplayStr=DisplayStr.replace(/%%M%%/g,self.calculateDate(secs,60,60));DisplayStr=DisplayStr.replace(/%%S%%/g,self.calculateDate(secs,1,60));self.element.innerHTML=DisplayStr;if(self.options.CountActive){self.timer=null;self.timer=setTimeout(function(){self.CountBack((secs+self.CountStepper),self)},(self.SetTimeOutPeriod))}}})})(jQuery)