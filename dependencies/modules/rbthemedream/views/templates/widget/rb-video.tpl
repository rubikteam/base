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
{if isset($video_html) && $video_html != 1}
	{if $instance.in_modal === 'yes'}
		<button class="rb-video-open-modal" data-toggle="modal"
			data-target="#rb-video-modal-{if isset($instance.id_widget_instance)}{$instance.id_widget_instance}{/if}
		">
			<i class="fa fa-play-circle"></i>
		</button>

		<div class="modal fade rb-video-modal" id="rb-video-modal-{if isset($instance.id_widget_instance)}{$instance.id_widget_instance}{/if}">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<span class="modal-title"></span>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						{$video_html nofilter}
					</div>
				</div>
			</div>
		</div>
	{else}
		<div class="rb-video-wrapper">
            {$video_html nofilter}

            {if RbVideo::hasImageOverlay()}
                <div class="rb-custom-embed-image-overlay" style="background-image: url({$current_instance.image_overlay.url})">
                    {if 'yes' === $current_instance.show_play_icon}
                        <div class="rb-custom-embed-play">
                            <i class="fa fa-play-circle"></i>
                        </div>
                    {/if}
                </div>
            {/if}
        </div>
	{/if}
{/if}