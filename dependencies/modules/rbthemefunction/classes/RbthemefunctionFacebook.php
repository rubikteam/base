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

class RbthemefunctionFacebook extends ObjectModel
{
    public $id;
    public $id_user;
    public $id_shop_group;
    public $id_shop;
    public $first_name;
    public $last_name;
    public $email;
    public $gender;
    public $date_add;
    public $date_upd;

    public static $definition = array(
        'table' => 'rbthemefunction_facebook',
        'primary' => 'id',
        'fields' => array(
            'id_user' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 100, 'required' => true),
            'id_shop_group' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'size' => 11, 'required' => true),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'size' => 11, 'required' => true),
            'first_name' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 100),
            'last_name' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 100),
            'email' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 100),
            'gender' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 100),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );

    public function add($autodate = true, $null_values = false)
    {
        if (!parent::add($autodate, $null_values)) {
            return false;
        }

        return true;
    }
}
