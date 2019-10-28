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

class RbMenuBlock extends RbMenuObj
{
    public $id_block;
    public $title;    
    public $title_link;
    public $content;
    public $enabled;
    public $sort_order;
    public $id_categories;
    public $order_by_category;
    public $id_manufacturers;
    public $order_by_manufacturers;
    public $display_mnu_img;
    public $display_mnu_name;
    public $display_mnu_inline;
    public $id_suppliers;
    public $order_by_suppliers;
    public $display_suppliers_img;
    public $display_suppliers_name;
    public $display_suppliers_inline;
    public $id_cmss;
    public $block_type;
    public $image;
    public $custom_class;
    public $display_title;
    public $id_column;
    public $image_link;
	public $product_type;
    public $id_products;
    public $product_count;
    public $combination_enabled;
    public $show_description;
    public $show_clock;

    public static $definition = array(
		'table' => 'rbthememenu_block',
		'primary' => 'id_block',
		'multilang' => true,
		'fields' => array(
			'sort_order' => array('type' => self::TYPE_INT),
            'id_column' => array('type' => self::TYPE_INT), 
            'id_categories' => array('type' => self::TYPE_STRING),  
            'order_by_category' => array('type' => self::TYPE_STRING), 
            'id_manufacturers' => array('type' => self::TYPE_STRING),
            'order_by_manufacturers' => array('type' => self::TYPE_STRING), 
            'display_mnu_img' => array('type' => self::TYPE_INT),
            'display_mnu_name' => array('type' => self::TYPE_INT),
            'display_mnu_inline' => array('type' => self::TYPE_STRING),
            'id_suppliers' => array('type' => self::TYPE_STRING),
            'order_by_suppliers' => array('type' => self::TYPE_STRING), 
            'display_suppliers_img' => array('type' => self::TYPE_INT),
            'display_suppliers_name' => array('type' => self::TYPE_INT),
            'display_suppliers_inline' => array('type' => self::TYPE_STRING),
            'id_cmss' => array('type' => self::TYPE_STRING),
			'product_type' => array('type' => self::TYPE_STRING),
            'id_products' => array('type' => self::TYPE_STRING),
			'product_count' => array('type' => self::TYPE_INT),
            'enabled' => array('type' => self::TYPE_INT),
            'image' => array('type' => self::TYPE_STRING,'lang' => false),
            'block_type' => array('type' => self::TYPE_STRING),
            'display_title' => array('type' => self::TYPE_INT),
            'show_description' => array('type' => self::TYPE_INT),
            'show_clock' => array('type' => self::TYPE_INT),
            // Lang fields
            'title' => array('type' => self::TYPE_STRING, 'lang' => true),			
            'title_link' => array('type' => self::TYPE_STRING, 'lang' => true), 
            'image_link' => array('type' => self::TYPE_STRING, 'lang' => true),   
            'content' => array('type' => self::TYPE_HTML, 'lang' => true),                
        )
	);

    public	function __construct(
    	$id_item = null,
    	$id_lang = null,
    	$id_shop = null,
    	Context $context = null
    ) {
		parent::__construct($id_item, $id_lang, $id_shop);
        $languages = Language::getLanguages(false);

        foreach($languages as $lang) {
            foreach(self::$definition['fields'] as $field => $params) {   
                $temp = $this->$field;

                if (isset($params['lang']) && $params['lang'] && !isset($temp[$lang['id_lang']])) {                      
                    $temp[$lang['id_lang']] = '';                        
                }

                $this->$field = $temp;
            }
        }

        unset($context);        
        $this->setFields(Rbthememenu::$blocks);
	}
}
