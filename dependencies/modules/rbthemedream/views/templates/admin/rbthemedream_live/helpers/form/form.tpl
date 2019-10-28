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

{block name="input_row"}
	{if $input.type == 'link_block'}
		<div class="row">
			<script type="text/javascript">
                var come_from = '{$name_controller}';
                var token = '{$token}';
                var alternate = 1;
            </script>

            {foreach $input.values key=key item=link_position name='linkLoop'}
            	<div class="col-lg-6">
            		<div class="panel">
            		 	<div class="panel-heading">
                            {$link_position.hook_name}
                            <small>{$link_position.hook_title}</small>
                        </div>

                        <table class="table tableDnD cms" id="id_rbthemedream_link_{$link_position.id_hook}">
                        	<thead>
                                <tr class="nodrag nodrop">
                                    <th>{l s='ID' mod='rbthemedream'}</th>
                                    <th>{l s='Position' mod='rbthemedream'}</th>
                                    <th>{l s='Link Name' mod='rbthemedream'}</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                            	{foreach $link_position.links item=link}
                            		<tr class="{if $key%2}alt_row{else}not_alt_row{/if} row_hover" id="tr_{$link_position.id_hook}_{$link.id_rbthemedream_link}_{$link.position}">
                            			<td>{$link.id_rbthemedream_link}</td>
                            			<td class="center pointer dragHandle" id="td_{$link_position.id_hook}_{$link.id_rbthemedream_link}">
                                            <div class="dragGroup">
                                                <div class="positions">
                                                    {$link.position + 1}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{$link.name}</td>
                                        <td>
                                        	<div class="btn-group-action">
                                        		<div class="btn-group pull-right">
                                        			<a class="btn btn-default" href="{$current}&amp;edit{$identifier}&amp;id_rbthemedream_link={(int)$link.id_rbthemedream_link}" title="{l s='Edit' mod='rbthemedream'}">
                                                        <i class="icon-edit"></i> {l s='Edit' mod='rbthemedream'}
                                                    </a>

                                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                        <i class="icon-caret-down"></i>&nbsp;
                                                    </button>

                                                    <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{$current}&amp;delete{$identifier}&amp;id_rbthemedream_link={$link.id_rbthemedream_link}" title="{l s='Delete' mod='rbthemedream'}">
                                                            <i class="icon-trash"></i> {l s='Delete' mod='rbthemedream'}
                                                        </a>
                                                    </li>
                                                    </ul>
                                        		</div>
                                        	</div>
                                        </td>
                            		</tr>
                            	{/foreach}
                            </tbody>
                        </table>
            		</div>
            	</div>

            	{if $smarty.foreach.linkLoop.index%2}<div class="clearfix"></div>{/if}
            {/foreach}
		</div>
	{else if $input.type == 'list_page'}
		{function name="cms_tree" nodes=[] depth=0}
		    {strip}
			    {if $nodes|count}
			        {foreach from=$nodes item=node}
			            <li data-id="{$node.id_cms_category}" data-type="cms_category" style="margin-left:{math equation="17 * depth" depth=$depth}px" class="cms-category">
			            	<span class="drag-handle">&#9776;</span>
			            	{$node.name}
			            	<small>({l s='cms category' mod='rbthemedream'})</small>
			            	<i class="icon-trash js-remove "></i>
			            </li>

			            {foreach from=$node.pages item=page}
			                <li data-id="{$page.id_cms}" data-type="cms_page" style="margin-left:{math equation="17 * (depth+1)" depth=$depth}px">
			                	<span class="drag-handle">&#9776;</span>
			                	{$page.title}
			                	<small>({l s='cms page' mod='rbthemedream'})</small>
			                	<i class="icon-trash js-remove "></i>
			                </li>
			            {/foreach}

			            {if isset($node.children)} {cms_tree nodes=$node.children depth=$depth+1} {/if}
			         {/foreach}
			    {/if}
		    {/strip}
    	{/function}

	    {function name="category_tree" nodes=[] depth=0}
	        {strip}
	            {if $nodes|count}
	                {foreach from=$nodes item=node}
	                    {if $node.level_depth > 1}
	                      	<li data-id="{$node.id_category}"
	                      	data-type="category" style="margin-left:{math equation="17 * (depth - 2)" depth=$depth}px" class=""
	                      	>
	                      		<span class="drag-handle">&#9776;</span>
	                      		{$node.name}
	                      		<small>({l s='category' mod='rbthemedream'})</small>
	                      		<i class="icon-trash js-remove "></i>
	                      	</li>
	                    {/if}

	                    {if isset($node.children)}
	                       	{category_tree nodes=$node.children depth=$depth+1}
	                    {/if}
	                {/foreach}
	            {/if}
	        {/strip}
	    {/function}

    	<div class="col-xs-7">
		    <div class="panel link-selector">
		        <div class="panel-heading">{$input.label}</div>
		        <ul id="repository-list">
		          	<li class="list-subtitle">{l s='Cms pages' mod='rbthemedream'}</li>
		          	{cms_tree nodes=$cms_tree}
		          	<li class="list-subtitle">{l s='Static pages' mod='rbthemedream'}</li>

			        {foreach $static_pages as $static}
			            {foreach $static.pages as $key => $page}
			              	<li data-id="{$page.id_cms}" data-type="static">
			              	<span class="drag-handle">&#9776;</span>
			              	{$page.title}
			              	<small>({l s='static page' mod='rbthemedream'})</small>
			              	<i class="icon-trash js-remove "></i></li>
			            {/foreach}
			         {/foreach}

		            <li class="list-subtitle">{l s='Categories' mod='rbthemedream'}</li>
		            {category_tree nodes=$category_tree}
		        </ul>
		    </div>
    	</div>
    {elseif $input.type == 'selected_link'}
    	<input type="hidden" name="data" id="selected-link" value="">

    	{function name="custom_link_lang" page=[]}
    		{strip}
    		<div class="form-group">
    			<label class="control-label col-lg-3">{l s='Title' mod='rbthemedream'}</label>
    			{foreach from=$languages item=language}
	    			{if $languages|count > 1}
		    			<div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
		    		{/if}

		    		<div class="col-lg-7">
		    			<input value="{$page.title[$language.id_lang]}"
		    			type="text" class="link-title-{$language.id_lang|escape:'htmlall':'UTF-8'}">
		    		</div>

		    		{if $languages|count > 1}
		    			<div class="col-lg-2">
		    				<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
		    					{$language.iso_code|escape:'htmlall':'UTF-8'}
		    					<span class="caret"></span>
		    				</button>
		    				<ul class="dropdown-menu">
		    					{foreach from=$languages item=lang}
		    						<li><a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'htmlall':'UTF-8'} );" tabindex="-1">{$lang.name|escape:'html'}</a></li>
		    					{/foreach}
		    				</ul>
		    			</div>
		    		{/if}

		    		{if $languages|count > 1}
		    			</div>
	    			{/if}
    			{/foreach}
    		</div>
    		{/strip}
    	{/function}

    	<div class="col-xs-5">
    		<div class="panel link-selector">
		        <div class="panel-heading">{$input.label}</div>

		        <div class="drag-info">
		        	<span class="drag-handle">&#9776;</span>
		        	{l s='Drag&drop links below from repository' mod='rbthemedream'}
		        </div>

		        <ul id="selected-list">
			        {foreach $selected_links as $page}
			            {if $page.type == 'custom'}
			                <li data-type="{$page.type}"><span class="drag-handle">&#9776;</span>
			                    {custom_link_lang page=$page}
			                <i class="icon-trash js-remove "></i></li>
			            {else}
			                {if isset($page.data.title)}
			                	<li data-type="{$page.type}" data-id="{$page.id}">
			                		<span class="drag-handle">&#9776;</span>
			                		{$page.data.title}
			                		<small>
			                			{if $page.type == 'static'}({l s='static pages' mod='rbthemedream'}){/if}

			                			{if $page.type == 'cms_category'}({l s='cms category' mod='rbthemedream'}){/if}

			                			{if $page.type == 'cms_page'}({l s='cms page' mod='rbthemedream'}){/if}
			                		</small>
			                		<i class="icon-trash js-remove "></i>
			                	</li>
			                {/if}
			            {/if}
			        {/foreach}
		        </ul>
		    </div>

		    <div class="drag-info">{l s='Or add custom link' mod='rbthemedream'}</div>

		    <div id="custom-links-panel">
		    	<div class="form-group">
        			<label class="control-label col-lg-3">
            			{l s='Title' mod='rbthemedream'}
        			</label>

        			{foreach from=$languages item=language}
				        {if $languages|count > 1}
				        	<div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
				        {/if}

				        <div class="col-lg-7">
				            <input value="" type="text" class="link-title-{$language.id_lang|escape:'htmlall':'UTF-8'}">
				        </div>

				        {if $languages|count > 1}
				            <div class="col-lg-2">
				                <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
				                    {$language.iso_code|escape:'htmlall':'UTF-8'}
				                    <span class="caret"></span>
				                </button>
				                <ul class="dropdown-menu">
				                    {foreach from=$languages item=lang}
				                    	<li><a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'htmlall':'UTF-8'} );" tabindex="-1">{$lang.name|escape:'html'}</a></li>
				                    {/foreach}
				                </ul>
				            </div>
				        {/if}

				        {if $languages|count > 1}
				        	</div>
				        {/if}
        			{/foreach}
    			</div>

    			<div class="form-group">
			        <label class="control-label col-lg-3">
			           {l s='Url' mod='rbthemedream'}
			        </label>

			        {foreach from=$languages item=language}
				        {if $languages|count > 1}
				        	<div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
				        {/if}
			            <div class="col-lg-7">
			                <input value="" type="text" class="link-url-{$language.id_lang|escape:'htmlall':'UTF-8'}">
			                <p class="help-block">{l s='Put absolute url with http:// or https:// prefix' mod='rbthemedream'}</p>
			            </div>

			            {if $languages|count > 1}
			            <div class="col-lg-2">
			                <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
			                    {$language.iso_code|escape:'htmlall':'UTF-8'}
			                    <span class="caret"></span>
			                </button>
			                <ul class="dropdown-menu">
			                    {foreach from=$languages item=lang}
			                    <li><a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'htmlall':'UTF-8'} );" tabindex="-1">{$lang.name|escape:'html'}</a></li>
			                    {/foreach}
			                </ul>
			            </div>
			            {/if}

			            {if $languages|count > 1}
			        		</div>
			        	{/if}
			        {/foreach}
    			</div>
		    </div>

		    <div class="form-group">
		        <button type="button" id="add-custom-link" class="btn btn-default btn-lg">
		             <i class="icon-plus"></i> {l s='Add' mod='rbthemedream'}
		        </button>
    		</div>
    	</div>

    	<div class="clearfix"></div>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}