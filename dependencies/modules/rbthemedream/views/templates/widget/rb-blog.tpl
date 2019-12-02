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
{if isset($posts) && count($posts)}
    {if $view == 'carousel'}
        <section class="rb-blog-posts rb-blog-posts-carousel rb-slick-slider rbthemeblog">
            <div class="rb-blog-carousel simpleblog-posts {$classes nofilter}" data-slider_options='{$options|@json_encode nofilter}'>
                {foreach $posts as $post}
                    <div class="simpleblog-posts-column">
                        <div class="rb-left-block">
                            <div class="rb-blog-image-container">
                                <a class="rb-blog-img-link" href="{$post.url}" title="{$post.title}" itemprop="url">
                                    <img class="img-fluid slick-loading" data-lazy="{$post.banner_thumb}" alt="{$post.title}" itemprop="image">
                                    <div class="rb-image-loading"></div>
                                </a>
                            </div>
                        </div>

                        <div class="right-block">
                            <div class="rb-blog-meta">
                                <h5 class="rb-blog-title" itemprop="name">
                                    <a href="{$post.url}" title="{$post.title}">{$post.title}</a>
                                </h5>

                                <p class="rb-blog-desc" itemprop="description">
                                    {$post.short_content nofilter}
                                </p>

                                <a href="{$post.url}" title="{$post.title}" class="post-btn-more">{l s='Read More' mod='rbthemedream'}</a>
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </section>
    {else}
        <section class="rb-blog-posts rb-blog-posts-grid rbthemeblog">
            <div class="row simpleblog-posts">
                {foreach $posts as $post}
                    <div class="simpleblog-posts-column {$options.gridClasses nofilter}">
                        <div class="simpleblog-posts-column">
                            <div class="rb-left-block">
                                <div class="rb-blog-image-container">
                                    <a class="rb-blog-img-link" href="{$post.url}" title="{$post.title}" itemprop="url">
                                        <img class="img-fluid" src="{$post.banner_thumb}" alt="{$post.title}" itemprop="image">
                                    </a>
                                </div>
                            </div>

                            <div class="right-block">
                                <div class="rb-blog-meta">
                                    <h5 class="rb-blog-title" itemprop="name">
                                        <a href="{$post.url}" title="{$post.title}">{$post.title}</a>
                                    </h5>

                                    <p class="rb-blog-desc" itemprop="description">
                                        {$post.short_content nofilter}
                                    </p>

                                    <a href="{$post.url}" title="{$post.title}" class="post-btn-more">{l s='Read More' mod='rbthemedream'}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </section>
    {/if}
    <div>
        <div class="blog-viewall">
            <a class="btn" href="{$rb_list}" title="View All">{l s='View All' mod='rbthemedream'}</a>
        </div>
    </div>
{/if}