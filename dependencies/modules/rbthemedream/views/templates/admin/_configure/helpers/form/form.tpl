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

{extends file="helpers/form/form.tpl"}

{block name="field"}
	{if $input.type == 'rb_file'}
		<div class="form-group">
			<div class="col-lg-6">
				<div class="row">
					<div class="input-group">
						<input  id="{$input.name}"
							type="text"
							value="{Configuration::get({$input.name})}"
							class="form-control"
							name="{$input.name}"
						/>
						<div class="input-group-addon">
							<a href="filemanager/dialog.php?type=1&field_id={$input.name}"
								class="js-img-upload"
	                            data-input-name="{$input.name}"
	                            type="button">
	                            	{l s='Select image' mod='rbthemedream'}
	                            	<i class="icon-external-link"></i>
	                       	</a>
	                    </div>
                    </div>
				</div>
			</div>
		</div>
	{else if $input.type == 'rb_option1'}	
	{else}
		{$smarty.block.parent}	
	{/if}
{/block}