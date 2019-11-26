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
<div {$attribute_string nofilter}>
	{if isset($instance.background_background) && $instance.background_background == 'video'}
		{$id_video = RbControl::getYoutubeIdFromUrl($instance.background_video_link)}

		<div class="rb-background-video-container rb-hidden-phone">
			{if $id_video}
				<div class="rb-background-video" data-video-id="{$id_video}"></div>
			{else}
				<video class="rb-background-video rb-html5-video" src="{$instance.background_video_link}" autoplay loop muted>	
				</video>
			{/if}
		</div>
	{/if}

	{if in_array($instance.background_overlay_background, array('classic', 'gradient'))}
		<div class="rb-background-overlay"></div>
	{/if}

	<div class="{if isset($instance.layout) && $instance.layout != 'full_width'}container {/if}{if isset($instance.rb_class_container) && $instance.rb_class_container != ''}{$instance.rb_class_container nofilter} {/if}rb-container rb-column-gap-{Tools::safeOutput($instance.gap)}"
	>
        <div class="row">