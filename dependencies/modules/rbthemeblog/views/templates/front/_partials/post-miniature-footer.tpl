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
<div class="rbblog__listing__post__wrapper__footer">
	<div class="row">
        {if Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_DATE')}
	        <div class="rbblog__listing__post__wrapper__footer__block col-md-6 col-xs-12">
	            <i class="material-icons">label</i>

	            <time datetime="{$post.date_add|date_format:'c'}">
	            	{$post.date_add}
	            </time>
	        </div>
        {/if}

        {if isset($post.author) && !empty($post.author) && Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_AUTHOR')}
	        <div class="rbblog__listing__post__wrapper__footer__block col-md-6 col-xs-12">
	            <i class="material-icons">perm_identity</i>
	            <span itemprop="author">{$post.author}</span>
	        </div>
        {else}
        	<meta itemprop="author" content="{Configuration::get('PS_SHOP_NAME')}">
        {/if}

        {if $post.allow_comments eq true && Configuration::get('RBTHEMEBLOG_BLOG_COMMENTS_SYSTEM') == 'native'}
	        <div class="rbblog__listing__post__wrapper__footer__block col-md-6 col-xs-12">
	            <i class="material-icons">comment</i>
	            <span>
	                <a href="{$post.url}#rbthemeblog_comments">
	                	{$post.comments} {l s='comments'  mod='rbthemeblog'}
	                </a>
	            </span>
	        </div>
        {/if}

        {if Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_VIEWS')}
	        <div class="rbblog__listing__post__wrapper__footer__block col-md-6 col-xs-12">
	            <i class="material-icons">remove_red_eye</i>

	            <span>
	                {$post.views} {l s='views'  mod='rbthemeblog'}
	            </span>
	        </div>
        {/if}

        <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
            <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
              <meta itemprop="url" content="{$urls.shop_domain_url|rtrim:'/'}{$shop.logo}">
            </div>
            <meta itemprop="name" content="{Configuration::get('PS_SHOP_NAME')}">
            <meta itemprop="email" content="{Configuration::get('PS_SHOP_EMAIL')}">
        </div>
        
        <meta itemprop="datePublished" content="{$post.date_add}">
        <meta itemprop="dateModified" content="{$post.date_upd}">
        <meta itemprop="mainEntityOfPage" content="{$urls.shop_domain_url}">
    </div>
</div>