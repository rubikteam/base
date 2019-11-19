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

class RbthemedreamHome extends ObjectModel
{
	public $id_rbthemedream_home;
    public $name;
    public $data;
    public $active;

    public static $definition = array(
        'table' => 'rbthemedream_home',
        'primary' => 'id_rbthemedream_home',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => array(
        	'name' =>     array(
        		'type' => self::TYPE_STRING,
        		'lang' => true,
        		'validate' => 'isCleanHtml',
        		'size' => 255
        	),
            'data' =>     array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isJson'),
            'active' => array('type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool'),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        $this->context = Context::getContext();
        $this->def['table'] = self::$definition['table'];
        ShopCore::addTableAssociation($this->def['table'], array('type' => 'shop'));
        
        parent::__construct($id, $id_lang, $id_shop);
    }

    public function add($autodate = true, $null_values = false)
    {
        return parent::add($autodate, $null_values);
    }

    public function getHomeActive()
    {
        $sql = 'SELECT `id_rbthemedream_home`
        FROM `'._DB_PREFIX_.'rbthemedream_home_shop`
        WHERE `active` = 1
        AND id_shop = ' . (int)$this->context->shop->id;

        return Db::getInstance()->getValue($sql);
    }

    public function getAllHome()
    {
        $sql = 'SELECT `id_rbthemedream_home`, `name`
        FROM `'._DB_PREFIX_.'rbthemedream_home_lang`
        WHERE id_lang = ' . (int)$this->context->language->id;

        return Db::getInstance()->executeS($sql);
    }
}
