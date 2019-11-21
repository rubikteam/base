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

class RbthemefunctionwishlistModuleFrontController extends ModuleFrontController
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
        $action = Tools::getValue('action');

        if (Tools::isSubmit('rb_add_wishlist')) {
            $this->createWishList();
        }

        if (!Tools::isSubmit('ajax')) {
            $this->rbDisplayList();
        } else if (!empty($action) && method_exists($this, 'ajaxProcess'.Tools::toCamelCase($action))) {
            $this->{'ajaxProcess'.Tools::toCamelCase($action)}();
        } else {
            die(Tools::jsonEncode(array(
                'error' => $this->trans(
                    'Method doesn\'t exist',
                    array(),
                    'Shop.Theme.Catalog'
                )
            )));
        }
    }

    public function createWishList()
    {
        $obj_wishlist = new RbthemefunctionWishList();
        $obj_wishlist->id_shop = $this->context->shop->id;
        $obj_wishlist->id_shop_group = $this->context->shop->id_shop_group;
        $obj_wishlist->default = 0;
        $obj_wishlist->name = Tools::getValue('rb_wishlist_name');
        $obj_wishlist->id_customer = (int)$this->context->customer->id;
        list($us, $s) = explode(' ', microtime());
        srand($s * $us);

        $obj_wishlist->token = Tools::strtoupper(Tools::substr(
            sha1(uniqid(rand(), true)._COOKIE_KEY_.$this->context->customer->id),
            0,
            16
        ));

        $obj_wishlist->add();

        Tools::redirect($this->context->link->getModuleLink('rbthemefunction', 'wishlist'));
    }

    public function rbDisplayList()
    {
        if (Configuration::get('RBTHEMEFUNCTION_WISHLIST') != 1) {
            return Tools::redirect('index.php?controller=404');
        }

        if ($this->context->customer->isLogged()) {
            $wishlists = $this->obj_wishlist->getByIdCustomer($this->context->customer->id);

            if (count($wishlists)>0) {
                foreach ($wishlists as $key => $wishlist) {
                    $wishlist_product = $this->obj_wishlist->getInfosByIdCustomer(
                        $this->context->customer->id,
                        $wishlist['id_rbthemefunction_wishlist']
                    );

                    $wishlists[$key]['number_product'] = $wishlist_product['nbProducts'];
                }
            }

            $this->context->smarty->assign(array(
                'wishlists' => $wishlists,
                'view_wishlist_url' => $this->context->link->getModuleLink('rbthemefunction', 'viewwishlist'),
            ));
        } else {
            Tools::redirect($this->context->link->getPageLink('authentication'));
        }

        $this->setTemplate('module:rbthemefunction/views/templates/front/rb-list-wishlist.tpl');
    }

    public function ajaxProcessAddWishListProduct()
    {
        if (!$this->isTokenValid() || !Tools::getValue('action')) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans(
                    'An error while processing. Please try again',
                    array(),
                    'Shop.Theme.Catalog'
                )
            )));
        };

        $action = Tools::getValue('action');
        $id_wishlist = (int)Tools::getValue('id_wishlist');
        $id_product = (int)Tools::getValue('id_product');
        $quantity = (int)Tools::getValue('quantity');
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');

        if ($id_wishlist == '') {
            $obj_wishlist = new RbthemefunctionWishList();
            $obj_wishlist->id_shop = $this->context->shop->id;
            $obj_wishlist->id_shop_group = $this->context->shop->id_shop_group;
            $obj_wishlist->default = 1;
            $obj_wishlist->name = $this->l('My wishlist', 'mywishlist');
            $obj_wishlist->id_customer = (int)$this->context->customer->id;
            list($us, $s) = explode(' ', microtime());
            srand($s * $us);

            $obj_wishlist->token = Tools::strtoupper(Tools::substr(
                sha1(uniqid(rand(), true)._COOKIE_KEY_.$this->context->customer->id),
                0,
                16
            ));

            $obj_wishlist->add();

            $obj_wishlist->addProduct(
                $obj_wishlist->id,
                $this->context->customer->id,
                $id_product,
                $id_product_attribute,
                $quantity
            );
        } else {
            $this->obj_wishlist->addProduct(
                $id_wishlist,
                $this->context->customer->id,
                $id_product,
                $id_product_attribute,
                $quantity
            );
        }

        die(Tools::jsonEncode(array(
            'success' => 1,
            'action' => 1,
            'message' => $this->trans(
                'Added to %1%wishlist list%2%',
                array(
                    '%1%'=>'<a href="'.$this->context->link->getModuleLink('rbthemefunction', 'wishlist').
                    '" class="rbwishlist-link-in-popup">',
                    '%2%'=>'</a>'
                ),
                'Shop.Theme.Catalog'
            ),
            'total' => $this->obj_wishlist->getToTalByCustomer($this->context->customer->id),
        )));
    }

    public function ajaxProcessviewWishListProduct()
    {
        $id_wishlist = (int)Tools::getValue('id_wishlist');
        $obj_wishlist = new RbthemefunctionWishList((int)$id_wishlist);

        if ($this->context->customer->id != $obj_wishlist->id_customer ||
            !Validate::isLoadedObject($obj_wishlist)
        ) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans(
                    'Cannot show the product(s) of this wishlist',
                    array(),
                    'Shop.Theme.Catalog'
                )
            )));
        }

        $products = array();
        $show_send_wishlist = 0;
        $wishlist_products = $this->obj_wishlist->getSimpleProductByIdWishlist($id_wishlist);

        if (!empty($wishlist_products)) {
            foreach ($wishlist_products as $wishlist_product) {
                $list_product_tmp = array();
                $list_product_tmp['wishlist_info'] = $wishlist_product;

                $list_product_tmp['product_info'] = $this->obj_product->getTemplateVarProduct2(
                    $wishlist_product['id_product'],
                    $wishlist_product['id_product_attribute']
                );

                $products[] = $list_product_tmp;
            }

            $wishlists = $this->obj_wishlist->getByIdCustomer($this->context->customer->id);

            foreach ($wishlists as $key => $wishlists_item) {
                if ($wishlists_item['id_rbthemefunction_wishlist'] == $id_wishlist) {
                    unset($wishlists[$key]);
                }
            }

            $this->context->smarty->assign(array(
                'products' => $products,
                'wishlists' => $wishlists,
            ));

            die(Tools::jsonEncode(array(
                'success' => 1,
                'message' => $this->module->fetch('module:rbthemefunction/views/templates/front/rb-wishlist-product.tpl')
            )));
        } else {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans(
                    'No product(s) of this wishlist',
                    array(),
                    'Shop.Theme.Catalog'
                )
            )));
        }
    }

    public function ajaxProcesssendWishListEmail()
    {
        $id_wishlist = (int)Tools::getValue('id_wishlist');
        $obj_wishlist = new RbthemefunctionWishList((int)$id_wishlist);

        if ($this->context->customer->id != $obj_wishlist->id_customer ||
            !Validate::isLoadedObject($obj_wishlist)
        ) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans(
                    'Invalid wishlist',
                    array(),
                    'Shop.Theme.Catalog'
                )
            )));
        }

        $to = Tools::getValue('email');
        $toName = Tools::safeOutput(Configuration::get('PS_SHOP_NAME'));
        $customer = $this->context->customer;

        if (!Validate::isLoadedObject($customer)) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans(
                    'Invalid Customer',
                    array(),
                    'Shop.Theme.Catalog'
                )
            )));
        } else {
            if (Mail::Send(
                $this->context->language->id,
                'wishlist',
                $this->module->l('Message From') . ' ' . $customer->lastname . $customer->firstname,
                array(
                '{lastname}' => $customer->lastname,
                    '{firstname}' => $customer->firstname,
                    '{wishlist}' => $obj_wishlist->name,
                    '{message}' => $this->context->link->getModuleLink(
                        'rbthemefunction',
                        'viewwishlist',
                        array('token' => $obj_wishlist->token)
                    )
                ),
                $to,
                $toName,
                $customer->email,
                $customer->firstname.' '.$customer->lastname,
                null,
                null,
                $this->module->module_path.'/mails/'
            )) {
                die(Tools::jsonEncode(array(
                    'success' => 1,
                    'message' => $this->trans(
                        'The Wishlist Was Successfully Sent',
                        array(),
                        'Shop.Theme.Catalog'
                    )
                )));
            } else {
                die(Tools::jsonEncode(array(
                    'success' => 0,
                    'message' => $this->trans(
                        'The Wishlist Sent Error',
                        array(),
                        'Shop.Theme.Catalog'
                    )
                )));
            }
        }
    }

    public function ajaxProcesssetDefaultWishList()
    {
        $id_wishlist = (int)Tools::getValue('id_wishlist');
        $obj_wishlist = new RbthemefunctionWishList((int)$id_wishlist);

        if ($this->context->customer->id != $obj_wishlist->id_customer ||
            !Validate::isLoadedObject($obj_wishlist)
        ) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans(
                    'Can Not Update This Wishlist',
                    array(),
                    'Shop.Theme.Catalog'
                )
            )));
        }

        if ($obj_wishlist->setDefault((int)$this->context->customer->id)) {
            die(Tools::jsonEncode(array(
                'success' => 1,
            )));
        } else {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans(
                    'Can Not Update This Wishlist',
                    array(),
                    'Shop.Theme.Catalog'
                )
            )));
        }
    }

    public function ajaxProcessdeleteWishList()
    {
        $id_wishlist = (int)Tools::getValue('id_wishlist');
        $obj_wishlist = new RbthemefunctionWishList((int)$id_wishlist);

        if ($this->context->customer->id != $obj_wishlist->id_customer ||
            !Validate::isLoadedObject($obj_wishlist)
        ) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans(
                    'Can Not Delete This Wishlist',
                    array(),
                    'Shop.Theme.Catalog'
                )
            )));
        }
        
        $obj_wishlist->delete();

        die(Tools::jsonEncode(array(
            'success' => 1,
            'total' => $this->obj_wishlist->getToTalByCustomer($this->context->customer->id),
        )));
    }

    public function ajaxProcessdeleteWishListProduct()
    {
        $id_wishlist_product = Tools::getValue('id_wishlist_product');
        $id_wishlist = (int)Tools::getValue('id_wishlist');
        $obj_wishlist = new RbthemefunctionWishList((int)$id_wishlist);

        if ($this->context->customer->id != $obj_wishlist->id_customer ||
            !Validate::isLoadedObject($obj_wishlist) ||
            !Validate::isUnsignedId($id_wishlist_product)
        ) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans(
                    'Invalid Wishlist',
                    array(),
                    'Shop.Theme.Catalog'
                )
            )));
        }

        if ($obj_wishlist->removeProductWishlist($id_wishlist, $id_wishlist_product)) {
            die(Tools::jsonEncode(array(
                'success' => 1,
                'total' => $this->obj_wishlist->getToTalByCustomer($this->context->customer->id),
            )));
        } else {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans(
                    'Can Not Delete',
                    array(),
                    'Shop.Theme.Catalog'
                )
            )));
        }
    }
}
