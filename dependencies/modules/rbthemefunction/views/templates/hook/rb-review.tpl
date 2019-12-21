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
{if $type == 'number'}
	<span class="rb-number-review">({$number})</span>
{/if}

{if $type == 'edit'}
	{if $rb_login == 1}
		<div class="product_reviews">
			<a class="rb-open-review" href="#rb_li_review">
				<i class="fa fa-pencil-square-o"></i>
				{l s='Add Review' mod='rbthemefunction'}
			</a>
		</div>
	{/if}
{/if}

{if $type == 'content'}
	<div class="product_reviews_block_tab">
		<div class="rb-review-list">
			{if isset($reviews) && !empty($reviews)}
				<div id="product_reviews_block">
					{foreach from=$reviews item=review}
						{if $review.content}
							<div class="review" itemprop="review" itemscope itemtype="https://schema.org/Review">
								<div class="review-info">
									<div class="author_image">
										<img alt="" src="../modules/rbthemefunction/views/img/author.png" class="avatar avatar-60 photo" height="60" width="60">
									</div>
									<div class="comment-text">
										<div class="review_author">
											<div class="review_author_infos">
												<strong itemprop="author">{$review.customer_name|escape:'html':'UTF-8'}</strong>
												<meta itemprop="datePublished" content="{$review.date_add|escape:'html':'UTF-8'|substr:0:10}" />
												<em>- {$review.date_add|escape:'html':'UTF-8'}</em>
											</div>
											<div class="star_content clearfix"  itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
												{section name="i" start=0 loop=5 step=1}
													{if $review.grade le $smarty.section.i.index}
														<div class="star"></div>
													{else}
														<div class="star star_on"></div>
													{/if}
												{/section}
												<meta itemprop="worstRating" content = "0" />
												<meta itemprop="ratingValue" content = "{$review.grade|escape:'html':'UTF-8'}" />
												<meta itemprop="bestRating" content = "5" />
											</div>
										</div>
										<div class="review-detail">
											<p itemprop="name" class="title_block">
												<strong>{$review.title}</strong>
											</p>
											<p itemprop="reviewBody">{$review.content nofilter}</p>
										</div>
									</div>
								</div>
							</div>
						{/if}
					{/foreach}
				</div>
			{else}
				<p class="alert alert-info">{l s='No comment at this time.' mod='rbthemefunction'}</p>
			{/if}

			{if $rb_login == 0}
				<a href="{$rb_login_url}" class="btn-primary">
					{l s='You Must Login To Review' mod='rbthemefunction'}
				</a>
			{/if}
		</div>

		{if $rb_login == 1}
			<div class="rb-new-review-form">
				<div class="modal-header">
					<h4 class="modal-title h2">
						{l s='Write a review' mod='rbthemefunction'}		
					</h4>
				</div>
				<div class="modal-body">
					<ul id="criterions_list">
						<li>
							<label>{l s='Quality' mod='rbthemefunction'}:</label>
							<div class="star_content">
								<input class="star not_uniform" type="radio" name="criterion" value="1">
								<input class="star not_uniform" type="radio" name="criterion" value="2">
								<input class="star not_uniform" type="radio" name="criterion" value="3">
								<input class="star not_uniform" type="radio" name="criterion" value="4" checked="checked">
								<input class="star not_uniform" type="radio" name="criterion" value="5">
							</div>
							<div class="clearfix"></div>
						</li>
					</ul>

					<form class="form-new-review" action="" method="POST">
						<div class="form-group">
							<label class="form-control-label" for="new_review_title">
								{l s='Title' mod='rbthemefunction'} <sup class="required">*</sup>
							</label>
							<input type="text" class="form-control" id="new_review_title" required="" name="new_review_title">					  
						</div>

						<div class="form-group">
							<label class="form-control-label" for="new_review_content">
								{l s='Comment' mod='rbthemefunction'} <sup class="required">*</sup>
							</label>

							<textarea type="text" class="form-control" id="rb_review_content" required="" name="rb_review_content"></textarea>				  
						</div>

						<div class="form-group">
							<label class="form-control-label"><sup>*</sup> {l s='Required fields' mod='rbthemefunction'}</label>
							<input id="id_product_review" name="id_product_review" type="hidden" value="{$id_product}"/>
						</div>

						{include file='module:rbthemefunction/views/templates/rb-ajax-loading.tpl'}

						<button class="btn btn-primary rb-control-submit pull-xs-right" type="submit">
							{l s='Submit' mod='rbthemefunction'}
						</button>
					</form>
				</div>
			</div>
		{/if}
	</div>
{/if}