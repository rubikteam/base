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

require_once _PS_MODULE_DIR_ . 'ps_emailsubscription/ps_emailsubscription.php';
require_once _PS_MODULE_DIR_ . 'rbthemefunction/classes/RbthemefunctionFacebook.php';

class RbthemefunctionajaxModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->id_lang = $this->context->language->id;
        $this->id_shop = $this->context->shop->id;
        $this->obj_emailsubscription = new RbEmailSubscription();

        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();
        $action = Tools::getValue('action1');

        if (!Tools::isSubmit('ajax')) {
            return;
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

    public function ajaxProcessFindProductByName()
    {
        $token = Tools::getToken(false);
        $name = Tools::getValue('name');

        if ($token != Tools::getValue('token')) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->l('Invalid Token')
            )));
        }

        $products = Product::searchByName($this->id_lang, $name);

        if (!empty($products)) {
            foreach ($products as $key_p => $val_p) {
                $product = new Product((int)$val_p['id_product'], true, $this->id_lang);
                $products[$key_p]['link'] = $this->context->link->getProductLink($product);
                $image = Image::getCover((int)$val_p['id_product']);

                $imagePath = $this->context->link->getImageLink(
                    $product->link_rewrite,
                    $image['id_image'],
                    ImageType::getFormattedName('home')
                );

                $products[$key_p]['image'] = $imagePath;
                $products[$key_p]['image'] = $imagePath;
                $products[$key_p]['price_tax_incl'] = Tools::displayPrice($val_p['price_tax_incl']);
                $products[$key_p]['price_tax_excl'] = Tools::displayPrice($val_p['price_tax_excl']);
            }

            $html = $this->module->displayProductSearch($products);

            die(Tools::jsonEncode(array(
                'success' => 1,
                'message' => $html
            )));
        } else {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->l('No Product Found')
            )));
        }
    }

    public function ajaxProcessLoginFacebook()
    {
        $customer = new Customer();
        $customer->getByEmail(
            Tools::getValue('email'),
            null,
            true
        );

        if ($customer->id) {
            Hook::exec('actionBeforeAuthentication');

            $this->context->cookie->id_customer = (int)($customer->id);
            $this->context->cookie->customer_lastname = $customer->lastname;
            $this->context->cookie->customer_firstname = $customer->firstname;
            $this->context->cookie->logged = 1;
            $this->context->logged = 1;
            $this->context->cookie->is_guest = $customer->isGuest();
            $this->context->cookie->passwd = $customer->passwd;
            $this->context->cookie->email = $customer->email;
            $this->context->customer = $customer;

            if (Configuration::get('PS_CART_FOLLOWING') &&
                (empty($this->context->cookie->id_cart) ||
                Cart::getNbProducts($this->context->cookie->id_cart) == 0) &&
                $id_cart = (int)Cart::lastNoneOrderedCart($this->context->customer->id)
            ) {
                $this->context->cart = new Cart($id_cart);
            } else {
                $id_carrier = (int)$this->context->cart->id_carrier;
                $this->context->cart->id_carrier = 0;
                $this->context->cart->setDeliveryOption(null);
                $this->context->cart->id_address_delivery = (int)Address::getFirstCustomerAddressId((int)($customer->id));
                $this->context->cart->id_address_invoice = (int)Address::getFirstCustomerAddressId((int)($customer->id));
            }

            $this->context->cart->id_customer = (int)$customer->id;
            $this->context->cart->secure_key = $customer->secure_key;
            $this->context->cart->save();
            $this->context->cookie->id_cart = (int)$this->context->cart->id;
            $this->context->cookie->write();
            $this->context->cart->autosetProductAddress();
            Hook::exec('actionAuthentication');
            CartRule::autoRemoveFromCart($this->context);
            CartRule::autoAddToCart($this->context);
        } else {
            $db_user_id = (int)Tools::getValue('id');

            $result = Db::getInstance()->ExecuteS(
                'SELECT *
                FROM '._DB_PREFIX_.'rbthemefunction_facebook
                WHERE id_user ="'.(int)$db_user_id.'"'.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER)
            );

            if (empty($result)) {
                $facebook_users = new RbthemefunctionFacebook();
                $facebook_users->id_user = Tools::getValue('id');
                $facebook_users->id_shop_group = Shop::getContextShopGroupID();
                $facebook_users->id_shop = Shop::getContextShopID();
                $facebook_users->first_name = Tools::getValue('firstname');
                $facebook_users->last_name = Tools::getValue('lastname');
                $facebook_users->email = Tools::getValue('email');
                $facebook_users->gender = Tools::getValue('gender');
                $facebook_users->add();

                Hook::exec('actionBeforeSubmitAccount');

                $customer->firstname = Tools::getValue('firstname');
                $customer->lastname = Tools::getValue('lastname');
                $customer->email = Tools::getValue('email');
                $password = Tools::passwdGen();
                $customer->passwd = md5(pSQL(_COOKIE_KEY_.$password));

                if (Tools::getValue('gender') == 'male') {
                    $id_gender = 1;
                } else if (Tools::getValue('gender') == 'female') {
                    $id_gender = 2;
                } else {
                    $id_gender = null;
                }

                $customer->id_gender = $id_gender;
                $customer->is_guest = 0;
                $customer->active = 1;
                $customer->add();

                $this->context->customer = $customer;
                $this->context->cookie->id_customer = (int)$customer->id;
                $this->context->cookie->customer_lastname = $customer->lastname;
                $this->context->cookie->customer_firstname = $customer->firstname;
                $this->context->cookie->passwd = $customer->passwd;
                $this->context->cookie->logged = 1;
                $this->context->logged = 1;
                $this->context->cookie->email = $customer->email;
                $this->context->cookie->is_guest = $customer->is_guest;
                $this->context->cart->secure_key = $customer->secure_key;
                $this->context->cookie->update();
                $this->context->cart->update();
            }
        }
    }

    public function ajaxProcessSendFormEmail()
    {
        $this->obj_emailsubscription->rbSendEmail();

        if (isset($this->obj_emailsubscription->error)) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->obj_emailsubscription->error
            )));
        }

        if (isset($this->obj_emailsubscription->valid)) {
            die(Tools::jsonEncode(array(
                'success' => 1,
                'message' => $this->obj_emailsubscription->valid
            )));
        }
    }
}

class RbEmailSubscription extends Ps_Emailsubscription
{
    public function __construct()
    {
        $this->context = Context::getContext();
    }

    public function rbSendEmail()
    {
        $this->variables['value'] = Tools::getValue('email', '');
        $this->variables['msg'] = '';
        $this->variables['conditions'] = Configuration::get('NW_CONDITIONS', $this->context->language->id);

        $this->newsletterRegistration();
    }
}
