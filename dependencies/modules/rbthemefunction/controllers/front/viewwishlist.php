<?php
/**
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
*/

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

require_once _PS_MODULE_DIR_ . 'rbthemefunction/classes/RbthemefunctionWishList.php';
require_once _PS_MODULE_DIR_ . 'rbthemefunction/classes/RbthemefunctionProduct.php';

class RbthemefunctionviewwishlistModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->id_lang = $this->context->language->id;
        $this->id_shop = $this->context->shop->id;
        $this->obj_wishlist = new RbthemefunctionWishList();
        $this->module = new Rbthemefunction();
        $this->obj_product = new RbthemefunctionProduct();

        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();

        if (Configuration::get('RBTHEMEFUNCTION_WISHLIST') != 1) {
            return Tools::redirect('index.php?controller=404');
        }

        $token = Tools::getValue('token');

        if ($token) {
            $wishlist = $this->obj_wishlist->getByToken($token);
            $wishlists = $this->obj_wishlist->getByIdCustomer((int)$wishlist['id_customer']);

            if (count($wishlists) > 1) {
                foreach ($wishlists as $key => $wishlists_item) {
                    if ($wishlists_item['id_rbthemefunction_wishlist'] == $wishlist['id_rbthemefunction_wishlist']) {
                        unset($wishlists[$key]);
                    }
                }
            } else {
                $wishlists = array();
            }

            $products = array();

            $wishlist_product = $this->obj_wishlist->getSimpleProductByIdWishlist(
                (int)$wishlist['id_rbthemefunction_wishlist']
            );

            if (count($wishlist_product) > 0) {
                foreach ($wishlist_product as $wishlist_product_item) {
                    $list_product_tmp = array();
                    $list_product_tmp['wishlist_info'] = $wishlist_product_item;

                    $list_product_tmp['product_info'] =  $this->obj_product->getTemplateVarProduct2(
                        $wishlist_product_item['id_product'],
                        $wishlist_product_item['id_product_attribute']
                    );

                    $list_product_tmp['product_info']['wishlist_quantity'] = $wishlist_product_item['quantity'];
                    $products[] = $list_product_tmp;
                }
            }

            $this->context->smarty->assign(
                array(
                    'current_wishlist' => $wishlist,
                    'wishlists' => $wishlists,
                    'products' => $products,
                    'view_wishlist_url' => $this->context->link->getModuleLink('rbthemefunction', 'viewwishlist'),
                    'show_button_cart' => Configuration::get('RBTHEMEFUNCTION_ADD_TO_CART'),
                )
            );
        }

        $this->setTemplate('module:rbthemefunction/views/templates/front/rb-wishlist-view.tpl');
    }
}
