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

class RbMenuColumn extends RbMenuObj
{
	public $id_column;
    public $id_menu;    
    public $id_tab;
    public $column_size;    
    public $sort_order;
    public $is_breaker;

    public static $definition = array(
		'table' => 'rbthememenu_column',
		'primary' => 'id_column',
		'multilang' => false,
		'fields' => array(
			'id_menu' => array('type' => self::TYPE_INT),
            'id_tab' => array('type' => self::TYPE_INT),  
            'column_size' => array('type' => self::TYPE_STRING),   
            'sort_order' => array('type' => self::TYPE_INT), 
            'is_breaker' => array('type' => self::TYPE_INT),             
        )
	);

    public	function __construct($id_item = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		parent::__construct($id_item, $id_lang, $id_shop);
        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {
            foreach (self::$definition['fields'] as $field => $params) {   
                $temp = $this->$field;

                if(isset($params['lang']) && $params['lang'] && !isset($temp[$lang['id_lang']])) {                      
                    $temp[$lang['id_lang']] = '';                        
                }

                $this->$field = $temp;
            }
        }

        unset($context);        
        $this->setFields(Rbthememenu::$columns);
	}
}
