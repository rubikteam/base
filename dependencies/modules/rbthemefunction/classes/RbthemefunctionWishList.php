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

class RbthemefunctionWishList extends ObjectModel
{
    public $id;
    public $id_customer;
    public $token;
    public $name;
    public $date_add;
    public $date_upd;
    public $id_shop;
    public $id_shop_group;
    public $default;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'rbthemefunction_wishlist',
        'primary' => 'id_rbthemefunction_wishlist',
        'multilang_shop' => false,
        'fields' => array(
            'id_customer' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'token' =>            array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'required' => true),
            'name' =>            array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'required' => true),
            'date_add' =>        array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' =>        array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'id_shop' =>        array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_shop_group' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'default' => array('type' => self::TYPE_BOOL, 'validate' => 'isUnsignedId'),
        )
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        $this->context = Context::getContext();
        parent::__construct($id, $id_lang, false);
    }


    public function delete()
    {
        Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'rbthemefunction_wishlist_product`
            WHERE `id_rbthemefunction_wishlist` = '.(int)($this->id)
        );

        return (parent::delete());
    }

    public function getToTalByCustomer($id_customer)
    {
        $sql =  'SELECT COUNT(*) AS `total`
        FROM `'._DB_PREFIX_.'rbthemefunction_wishlist_product`
        WHERE `id_rbthemefunction_wishlist`
        IN (SELECT `id_rbthemefunction_wishlist` FROM `'._DB_PREFIX_.'rbthemefunction_wishlist`
        WHERE `id_customer` = '.$id_customer.')';

        return Db::getInstance()->getValue($sql);
    }

    public function addProduct(
        $id_wishlist,
        $id_customer,
        $id_product,
        $id_product_attribute,
        $quantity
    ) {
        if (!Validate::isUnsignedId($id_wishlist) ||
            !Validate::isUnsignedId($id_customer) ||
            !Validate::isUnsignedId($id_product) ||
            !Validate::isUnsignedId($quantity)
        ) {
            die(Tools::displayError());
        }

        $result = Db::getInstance()->getRow(
            'SELECT wp.`quantity`
            FROM `'._DB_PREFIX_.'rbthemefunction_wishlist_product` wp
            JOIN `'._DB_PREFIX_.'rbthemefunction_wishlist` w ON (w.`id_rbthemefunction_wishlist` = wp.`id_rbthemefunction_wishlist`)
            WHERE wp.`id_rbthemefunction_wishlist` = '.(int)($id_wishlist).'
            AND w.`id_customer` = '.(int)($id_customer).'
            AND wp.`id_product` = '.(int)($id_product).'
            AND wp.`id_product_attribute` = '.(int)($id_product_attribute)
        );

        if (empty($result) === false && sizeof($result)) {
            if (($result['quantity'] + $quantity) <= 0) {
                return ($this->removeProduct(
                    $id_wishlist,
                    $id_customer,
                    $id_product,
                    $id_product_attribute
                ));
            } else {
                return (Db::getInstance()->execute(
                    'UPDATE `'._DB_PREFIX_.'rbthemefunction_wishlist_product` SET
                    `quantity` = '.(int)($quantity + $result['quantity']).'
                    WHERE `id_rbthemefunction_wishlist` = '.(int)($id_wishlist).'
                    AND `id_product` = '.(int)($id_product).'
                    AND `id_product_attribute` = '.(int)($id_product_attribute)
                ));
            }
        } else {
            return (Db::getInstance()->execute(
                'INSERT INTO `'._DB_PREFIX_.'rbthemefunction_wishlist_product`
                (`id_rbthemefunction_wishlist`, `id_product`, `id_product_attribute`, `quantity`, `priority`)
                VALUES('.(int)($id_wishlist).', '.(int)($id_product).','.(int)($id_product_attribute).',
                '.(int)($quantity).', 1)'
            ));
        }
    }

    public function removeProduct($id_wishlist, $id_customer, $id_product, $id_product_attribute)
    {
        if (!Validate::isUnsignedId($id_wishlist) ||
            !Validate::isUnsignedId($id_customer) ||
            !Validate::isUnsignedId($id_product)
        ) {
            die(Tools::displayError());
        }

        $result = Db::getInstance()->getRow(
            'SELECT w.`id_rbthemefunction_wishlist`, wp.`id_rbthemefunction_wishlist_product`
            FROM `'._DB_PREFIX_.'rbthemefunction_wishlist` w
            LEFT JOIN `'._DB_PREFIX_.'rbthemefunction_wishlist_product` wp
            ON (wp.`id_rbthemefunction_wishlist` = w.`id_rbthemefunction_wishlist`)
            WHERE `id_customer` = '.(int)($id_customer).'
            AND w.`id_rbthemefunction_wishlist` = '.(int)($id_wishlist)
        );
        
        if (empty($result) === true ||
            $result === false ||
            !sizeof($result) ||
            $result['id_rbthemefunction_wishlist'] != $id_wishlist
        ) {
            return (false);
        }
        
        return Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'rbthemefunction_wishlist_product`
            WHERE `id_rbthemefunction_wishlist` = '.(int)($id_wishlist).'
            AND `id_product` = '.(int)($id_product).'
            AND `id_product_attribute` = '.(int)($id_product_attribute)
        );
    }

    public function getByIdCustomer($id_customer)
    {
        if (!Validate::isUnsignedId($id_customer)) {
            die(Tools::displayError());
        }

        if (Shop::getContextShopID()) {
            $shop_restriction = 'AND id_shop = '.(int)Shop::getContextShopID();
        } else if (Shop::getContextShopGroupID()) {
            $shop_restriction = 'AND id_shop_group = '.(int)Shop::getContextShopGroupID();
        } else {
            $shop_restriction = '';
        }

        $cache_id = 'WhishList::getByIdCustomer_'.(int)$id_customer.'-'.
        (int)Shop::getContextShopID().'-'.(int)Shop::getContextShopGroupID();

        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS(
                'SELECT w.`id_rbthemefunction_wishlist`, w.`name`, w.`token`,
                w.`date_add`, w.`date_upd`, w.`counter`, w.`default`
                FROM `'._DB_PREFIX_.'rbthemefunction_wishlist` w
                WHERE `id_customer` = '.(int)($id_customer).'
                '.$shop_restriction.'
                ORDER BY w.`name` ASC'
            );

            Cache::store($cache_id, $result);
        }

        return Cache::retrieve($cache_id);
    }

    public function getSimpleProductByIdCustomer($id_customer, $id_shop)
    {
        if (!Validate::isUnsignedId($id_customer) or !Validate::isUnsignedId($id_shop)) {
            die(Tools::displayError());
        }

        $wishlists = Db::getInstance()->executeS(
            'SELECT w.`id_rbthemefunction_wishlist`
            FROM `'._DB_PREFIX_.'rbthemefunction_wishlist` w
            WHERE w.`id_customer` = '.(int)($id_customer).'
            AND w.`id_shop` = '.(int) $id_shop.''
        );
        
        if (empty($wishlists) === true || !sizeof($wishlists)) {
            return array();
        }
        
        $wishlist_product = array();

        foreach ($wishlists as $wishlist) {
            $product = Db::getInstance()->executeS(
                'SELECT wp.`id_product`, wp.`id_product_attribute`
                FROM `'._DB_PREFIX_.'rbthemefunction_wishlist_product` wp
                WHERE wp.`id_rbthemefunction_wishlist` = '.(int)$wishlist['id_rbthemefunction_wishlist'].''
            );

            $wishlist_product[$wishlist['id_rbthemefunction_wishlist']] = $product;
        }
        
        return ($wishlist_product);
    }

    public function getInfosByIdCustomer($id_customer, $id_wishlist)
    {
        if (Shop::getContextShopID()) {
            $shop_restriction = 'AND id_shop = '.(int)Shop::getContextShopID();
        } elseif (Shop::getContextShopGroupID()) {
            $shop_restriction = 'AND id_shop_group = '.(int)Shop::getContextShopGroupID();
        } else {
            $shop_restriction = '';
        }

        if (!Validate::isUnsignedId($id_customer)) {
            die(Tools::displayError());
        }
        
        return (Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
            'SELECT SUM(wp.`quantity`) AS nbProducts, wp.`id_rbthemefunction_wishlist`
            FROM `'._DB_PREFIX_.'rbthemefunction_wishlist_product` wp
            INNER JOIN `'._DB_PREFIX_.'rbthemefunction_wishlist` w
            ON (w.`id_rbthemefunction_wishlist` = wp.`id_rbthemefunction_wishlist`)
            WHERE w.`id_customer` = '.(int)($id_customer).'
            AND wp.`id_rbthemefunction_wishlist` = '.(int)($id_wishlist).'
            '.$shop_restriction.'
            GROUP BY w.`id_rbthemefunction_wishlist`
            ORDER BY w.`name` ASC'
        ));
    }

    public function getSimpleProductByIdWishlist($id_wishlist)
    {
        if (!Validate::isUnsignedId($id_wishlist)) {
            die(Tools::displayError());
        }
        
        return Db::getInstance()->executeS(
            'SELECT wp.*
            FROM `'._DB_PREFIX_.'rbthemefunction_wishlist_product` wp
            WHERE wp.`id_rbthemefunction_wishlist` = '.(int)$id_wishlist.''
        );
    }

    public function getDefault($id_customer)
    {
        return Db::getInstance()->getRow(
            'SELECT *
            FROM `'._DB_PREFIX_.'rbthemefunction_wishlist`
            WHERE `id_customer` = '.(int)$id_customer.'
            AND `default` = 1'
        );
    }

    public function setDefault($id_customer)
    {
        if ($default = $this->getDefault($id_customer)) {
            Db::getInstance()->update(
                'rbthemefunction_wishlist',
                array('default' => '0'),
                'id_rbthemefunction_wishlist = '.(int)$default['id_rbthemefunction_wishlist']
            );
        }

        return Db::getInstance()->update(
            'rbthemefunction_wishlist',
            array('default' => '1'),
            'id_rbthemefunction_wishlist = '.(int)$this->id
        );
    }

    public function removeProductWishlist($id_wishlist, $id_wishlist_product)
    {
        if (!Validate::isUnsignedId($id_wishlist_product) ||
            !Validate::isUnsignedId($id_wishlist)
        ) {
            die(Tools::displayError());
        }
        
        return Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'rbthemefunction_wishlist_product`
            WHERE `id_rbthemefunction_wishlist_product` = '.(int)($id_wishlist_product).'
            AND `id_rbthemefunction_wishlist` = '.(int)($id_wishlist)
        );
    }

    public function getByToken($token)
    {
        if (!Validate::isMessage($token)) {
            die(Tools::displayError());
        }

        return (Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
            'SELECT w.`id_rbthemefunction_wishlist`, w.`name`, w.`id_customer`,
            c.`firstname`, c.`lastname`
            FROM `'._DB_PREFIX_.'rbthemefunction_wishlist` w
            INNER JOIN `'._DB_PREFIX_.'customer` c ON c.`id_customer` = w.`id_customer`
            WHERE `token` = \''.pSQL($token).'\''
        ));
    }

    public function incCounter($id_wishlist)
    {
        if (!Validate::isUnsignedId($id_wishlist)) {
            die(Tools::displayError());
        }

        $result = Db::getInstance()->getRow(
            'SELECT `counter`
            FROM `'._DB_PREFIX_.'rbthemefunction_wishlist`
            WHERE `id_rbthemefunction_wishlist` = '.(int)$id_wishlist
        );
        
        if ($result == false || !count($result) || empty($result) === true) {
            return (false);
        }

        return Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'rbthemefunction_wishlist` SET
            `counter` = '.(int)($result['counter'] + 1).'
            WHERE `id_rbthemefunction_wishlist` = '.(int)$id_wishlist
        );
    }
}
