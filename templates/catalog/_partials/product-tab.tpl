{**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div class="tabs">
	<ul class="nav nav-tabs" role="tablist">
		{if $product.description}
			<li class="nav-item">
				<a
				class="nav-link{if $product.description} active{/if}"
				data-toggle="tab"
				href="#description"
				role="tab"
				aria-controls="description"
				{if $product.description} aria-selected="true"{/if}>
					<i class="material-icons">description</i>
					{l s='Description' d='Shop.Theme.Catalog'}
				</a>
			</li>
		{/if}

		<li class="nav-item">
			<a
			class="nav-link{if !$product.description} active{/if}"
			data-toggle="tab"
			href="#product-details"
			role="tab"
			aria-controls="product-details"
			{if !$product.description} aria-selected="true"{/if}>
				<i class="material-icons">details</i>
				{l s='Product Details' d='Shop.Theme.Catalog'}
			</a>
		</li>

		{if $product.attachments}
			<li class="nav-item">
				<a
				class="nav-link"
				data-toggle="tab"
				href="#attachments"
				role="tab"
				aria-controls="attachments">{l s='Attachments' d='Shop.Theme.Catalog'}</a>
			</li>
		{/if}

		{foreach from=$product.extraContent item=extra key=extraKey}
			<li class="nav-item">
				<a
				class="nav-link"
				data-toggle="tab"
				href="#extra-{$extraKey}"
				role="tab"
				aria-controls="extra-{$extraKey}">{$extra.title}</a>
			</li>
		{/foreach}

		<li class="nav-item" id="rb_li_review">
			<a
			class="nav-link"
			data-toggle="tab"
			href="#rb_review"
			role="tab"
			aria-controls="rb_review">
				<i class="material-icons">comment</i>
				{l s='Review' d='Shop.Theme.Catalog'}
				{hook h='displayRbReviewProduct' product=$product type='number'}
			</a>
		</li>
	</ul>

	<div class="tab-content" id="tab-content">
		<div class="tab-pane fade in{if $product.description} active{/if}" id="description" role="tabpanel">
			{block name='product_description'}
				<div class="product-description">{$product.description nofilter}</div>
			{/block}
		</div>

		{block name='product_details'}
			{include file='catalog/_partials/product-details.tpl'}
		{/block}

		{block name='product_attachments'}
			{if $product.attachments}
				<div class="tab-pane fade in" id="attachments" role="tabpanel">
					<section class="product-attachments">
						<p class="h5 text-uppercase">{l s='Download' d='Shop.Theme.Actions'}</p>
						{foreach from=$product.attachments item=attachment}
						<div class="attachment">
							<h4>
								<a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
									{$attachment.name}
								</a>
							</h4>
							<p>{$attachment.description}</p
								<a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
									{l s='Download' d='Shop.Theme.Actions'} ({$attachment.file_size_formatted})
								</a>
							</div>
						{/foreach}
					</section>
				</div>
			{/if}
		{/block}

		{foreach from=$product.extraContent item=extra key=extraKey}
			<div class="tab-pane fade in {$extra.attr.class}" id="extra-{$extraKey}" role="tabpanel" {foreach $extra.attr as $key => $val} {$key}="{$val}"{/foreach}>
				{$extra.content nofilter}
			</div>
		{/foreach}

		<div class="tab-pane fade in" id="rb_review" role="tabpanel">
			{hook h='displayRbReviewProduct' product=$product type='content'}
		</div>
	</div>  
</div>