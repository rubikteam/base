{**
 * 2007-2018 PrestaShop
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
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div class="images-container">
  {block name='product_cover'}
    <div class="product-cover">
      {if $product.cover}
        <img
          class="images-zoom"
          data-zoom-image="{$product.cover.bySize.medium_default.url}"
          src="{$product.cover.bySize.home_default.url}"
          alt="{$product.cover.legend}"
          title="{$product.cover.legend}"
          itemprop="image"
          style="width:100%"
          data-rb-image="{$product.cover.bySize.large_default.url}"
        >
        {foreach from=$product.images item=image}
          {if $product.cover.id_image != $image.id_image}
            <img
              class="images-zoom"
              data-zoom-image="{$image.bySize.medium_default.url}"
              src="{$image.bySize.home_default.url}"
              style="width:100%"
              data-rb-image="{$image.bySize.large_default.url}"
            >
          {/if}
        {/foreach}
      {else}
        <img src="{$urls.no_picture_image.bySize.large_default.url}" style="width:100%;">
      {/if}
    </div>
  {/block}

  {block name='product_images'}
    {if Configuration::get('RBTHEMEFUNCTION_ZOOM_ACTIVE') == 1 && Configuration::get('RBTHEMEFUNCTION_SLICK_ACTIVE') == 1}
      <div id="rb_gallery">
        {$count = 1}

        {foreach from=$product.images item=image}
          <a
            class="thumb-container"
            href="#"
            data-image="{$image.bySize.medium_default.url}"
            data-zoom-image="{$image.bySize.large_default.url}"
          >
            <img class="img img-thumb" id="rb_img_{$count}" src="{$image.bySize.small_default.url}" />
          </a>

          {$count = $count + 1}

        {/foreach}
      </div>
    {else}
      <div class="js-qv-mask mask">
        <ul class="product-images js-qv-product-images" id="rb_gallery">
          {foreach from=$product.images item=image}
            <li class="thumb-container">
              <img
                class="thumb js-thumb {if $image.id_image == $product.cover.id_image} selected {/if}"
                data-image-medium-src="{$image.bySize.medium_default.url}"
                data-image-large-src="{$image.bySize.large_default.url}"
                src="{$image.bySize.home_default.url}"
                alt="{$image.legend}"
                title="{$image.legend}"
                width="100"
                itemprop="image"
              >
            </li>
          {/foreach}
        </ul>
      </div>
    {/if}
  {/block}
</div>

{hook h='displayAfterProductThumbs'}

{if Configuration::get('RBTHEMEFUNCTION_ZOOM_ACTIVE') != 1 && Configuration::get('RBTHEMEFUNCTION_SLICK_ACTIVE') != 1}
  <div class="scroll-box-arrows">
    <i class="material-icons left">&#xE314;</i>
    <i class="material-icons right">&#xE315;</i>
  </div>
{/if}