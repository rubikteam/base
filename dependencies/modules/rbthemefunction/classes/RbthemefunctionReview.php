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

class RbthemefunctionReview extends ObjectModel
{
    public $id;
    public $id_product;
    public $id_customer;
    public $id_guest;
    public $customer_name;
    public $title;
    public $content;
    public $grade;
    public $validate;
    public $deleted;
    public $date_add;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'rbthemefunction_review',
        'primary' => 'id_rbthemefunction_review',
        'multilang_shop' => false,
        'fields' => array(
            'id_product' =>     array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_customer' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_guest' =>       array('type' => self::TYPE_INT),
            'customer_name' =>  array('type' => self::TYPE_STRING),
            'title' =>          array('type' => self::TYPE_STRING),
            'content' =>        array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'required' => true),
            'grade' =>          array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'validate' =>       array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'deleted' =>        array('type' => self::TYPE_BOOL),
            'date_add' =>       array('type' => self::TYPE_DATE),
        )
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        $this->context = Context::getContext();
        parent::__construct($id, $id_lang, false);
    }

    public function getByValidate($validate = '0', $deleted = false)
    {
        $sql  = 'SELECT  pc.`id_rbthemefunction_review`, pc.`id_product`,
        if (c.`id_customer`, CONCAT(c.`firstname`, \' \',  c.`lastname`), pc.`customer_name`) customer_name,
        pc.`title`, pc.`content`, pc.`grade`, pc.`date_add`, pl.`name`
        FROM `'._DB_PREFIX_.'rbthemefunction_review` pc
       	LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = pc.`id_customer`)
        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
        ON (pl.`id_product` = pc.`id_product`
        AND pl.`id_lang` = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('pl').')
        WHERE pc.`validate` = '.(int)$validate;

        $sql .= ' ORDER BY pc.`date_add` DESC';
        unset($deleted);

        return (Db::getInstance()->executeS($sql));
    }

    public function getByProduct($id_product, $p = 1, $n = null, $id_customer = null)
    {
        if (!Validate::isUnsignedId($id_product)) {
            Tools::displayError();
        }

        $validate = 1;
        $p = (int)$p;
        $n = (int)$n;

        if ($p <= 1) {
            $p = 1;
        }

        if ($n != null && $n <= 0) {
            $n = 5;
        }

        $cache_id = 'RbthemefunctionReview::getByProduct_'.
        (int)$id_product.'-'.(int)$p.'-'.(int)$n.
        '-'.(int)$id_customer.'-'.(bool)$validate;

        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT pc.`id_rbthemefunction_review`, pc.`customer_name`, pc.`content`, pc.`grade`, pc.`date_add`, pc.`title`
            FROM `'._DB_PREFIX_.'rbthemefunction_review` pc
            WHERE pc.`id_product` = '.(int)($id_product).($validate == '1' ? ' AND pc.`validate` = 1' : '').'
            ORDER BY pc.`date_add` DESC
            '.($n ? 'LIMIT '.(int)(($p - 1) * $n).', '.(int)($n) : ''));
            Cache::store($cache_id, $result);
        }

        return Cache::retrieve($cache_id);
    }

    public function validate($validate = '1')
    {
        if (!Validate::isUnsignedId($this->id)) {
            return false;
        }

        $success = (Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'rbthemefunction_review` SET
            `validate` = '.(int)$validate.'
            WHERE `id_rbthemefunction_review` = '.(int)$this->id
        ));

        Hook::exec('actionObjectProductReviewValidateAfter', array('object' => $this));
        
        return $success;
    }
}
