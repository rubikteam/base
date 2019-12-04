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
<div class="rb-brands">
	{if $widgetOptions.view == 'grid'}
	    <div class="row">
	        {foreach from=$widgetOptions.brands item=brand name=brand_list}
	            <div class="col-{$widgetOptions.slidesToShow.mobile} col-md-{$widgetOptions.slidesToShow.tablet} col-lg-{$widgetOptions.slidesToShow.desktop} col-xl-{$widgetOptions.slidesToShow.desktop}">
	                <a href="{$brand.link}">
	                    <img src="{$brand.image}" alt="{$brand.name}"/>
	                </a>
	            </div>
	        {/foreach}
	    </div>
	{else}
	    <div class="rb-brands-carousel slick-arrows-{$widgetOptions.arrows_position}"  data-slider_options='{$widgetOptions.options|@json_encode nofilter}'>
	        {foreach from=$widgetOptions.brands item=brand name=brand_list}
	            <div class="rb-brands-item">
	                <a href="{$brand.link}">
	                    <img class="img-fluid slick-loading" data-lazy="{$brand.image}" alt="{$brand.name}" />
	                    <div class="rb-image-loading"></div>
	                </a>
	            </div>
	        {/foreach}
	    </div>
	{/if}
</div>