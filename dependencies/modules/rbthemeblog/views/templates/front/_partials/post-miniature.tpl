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
<div class="rbblog__listing__post
    {if $blogLayout eq 'grid' AND $columns eq '3'}
        col-md-4 col-sm-6 col-xs-12 col-ms-12 {cycle values="first-in-line,second-in-line,last-in-line"}
    {elseif $blogLayout eq 'grid' AND $columns eq '4'}
        col-md-3 col-sm-6 col-xs-12 col-ms-12 {cycle values="first-in-line,second-in-line,third-in-line,last-in-line"}
    {elseif $blogLayout eq 'grid' AND $columns eq '2'}
        col-md-6 col-sm-6 col-xs-12 col-ms-12 {cycle values="first-in-line,last-in-line"}
    {else}
    col-md-12
    {/if}"
>
	<div
		class="rbblog__listing__post__wrapper"
		itemscope="itemscope"
		itemtype="http://schema.org/BlogPosting"
		itemprop="blogPost"
	>
		{if $post.post_type == 'url'}
        	{include file="module:rbthemeblog/views/templates/front/_partials/type-url/post-thumbnail.tpl"}
        {else if $post.post_type == 'video'}
        	{include file="module:rbthemeblog/views/templates/front/_partials/type-video/post-thumbnail.tpl"}
        {else}
        	{include file="module:rbthemeblog/views/templates/front/_partials/type-default/post-thumbnail.tpl"}
        {/if}

        <div class="rbblog__listing__post__wrapper__content">
            {if $post.post_type == 'url'}
                {include file="module:rbthemeblog/views/templates/front/_partials/type-url/post-headline.tpl"}
            {else}
                {include file="module:rbthemeblog/views/templates/front/_partials/type-default/post-headline.tpl"}
            {/if}

            {if Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_DESCRIPTION')}
                <p>{$post.short_content|strip_tags:'UTF-8'}</p>
            {/if}

            {if $post.post_type == 'url'}
                {include file="module:rbthemeblog/views/templates/front/_partials/type-url/post-bottomline.tpl"}
            {else}
                {include file="module:rbthemeblog/views/templates/front/_partials/type-default/post-bottomline.tpl"}
            {/if}
        </div>

        {include file="module:rbthemeblog/views/templates/front/_partials/post-miniature-footer.tpl"}
	</div>	
</div>