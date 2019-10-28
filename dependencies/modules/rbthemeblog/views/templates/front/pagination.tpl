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
{if (($start != $stop) && ($start > 1))}
	<nav class="rbblog__listing__pagination pagination">
	    <div class="col-md-12 pr-0">
	        <ul class="rbblog__listing__pagination__list page-list clearfix text-sm-center flex-container">
	        	{if $p != 1}
					{assign var='p_previous' value=$p-1}
					<li>
						<a rel="prev" href="{RbBlogPost::getPageLink($p_previous, $type, $rewrite)}" class="previous disabled ">
							<svg class="svgic"><use xlink:href="#si-arrowleft"></use></svg>
						</a>
					</li>
				{else}
					<li>
						<a rel="prev" href="#" class="previous disabled "><svg class="svgic"><use xlink:href="#si-arrowleft"></use></svg></a>
					</li>
				{/if}

				{if $start > 1}
					<li><a href="{RbBlogPost::getPageLink(1, $type, $rewrite)}">1</a></li>

					<li>
						<span class="spacer">…</span>
					</li>
				{/if}

				{section name=pagination start=$start loop=$stop+1 step=1}
					{if $p == $smarty.section.pagination.index}
						<li class="current">
							<a href="#" class="disabled">{$p}</a>
						</li>
					{else}
						<li>
							<a href="{RbBlogPost::getPageLink($smarty.section.pagination.index, $type, $rewrite)}">{$smarty.section.pagination.index}</a>
						</li>
					{/if}
				{/section}

	            {if $pages_nb>$stop}
					<li>
						<span class="spacer">…</span>
					</li>

					<li>
						<a href="{RbBlogPost::getPageLink($pages_nb, $type, $rewrite)}">{$pages_nb|intval}</a>
					</li>
				{/if}

				{if $pages_nb > 1 AND $p != $pages_nb}
					{assign var='p_next' value=$p+1}
					
					<li>
		                <a rel="next" href="{RbBlogPost::getPageLink($p_next, $type, $rewrite)}" class="next ">
		                	<svg class="svgic"><use xlink:href="#si-arrowright"></use></svg>
		                </a>
		            </li>
				{else}
					<li>
		                <a rel="next" href="#" class="next  disabled"><svg class="svgic"><use xlink:href="#si-arrowright"></use></svg></a>
		            </li>
				{/if}
	        </ul>
	    </div>
	</nav>
{/if}