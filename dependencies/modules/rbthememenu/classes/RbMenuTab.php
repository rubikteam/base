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

class RbMenuTab extends RbMenuObj
{
    public $id_tab;
    public $id_menu;   
    public $tab_img_link;
    public $tab_sub_width;
    public $menu_ver_hidden_border;
    public $tab_sub_content_pos;
    public $tab_icon; 
    public $title;    
    public $enabled;
    public $sort_order;    
    public $bubble_background_color;
    public $bubble_text_color;
    public $bubble_text;
    public $background_image;
    public $position_background;
    public $url;

    public static $definition = array(
		'table' => 'rbthememenu_tab',
		'primary' => 'id_tab',
		'multilang' => true,
		'fields' => array(
			'id_menu' => array('type' => self::TYPE_INT), 
            'tab_img_link'=> array('type'=>self::TYPE_STRING),
            'tab_sub_width'=> array('type'=>self::TYPE_STRING),
            'tab_icon'=> array('type'=>self::TYPE_STRING),
            'bubble_text_color'=> array('type'=>self::TYPE_STRING),
            'bubble_background_color'=> array('type'=>self::TYPE_STRING),
            'tab_sub_content_pos'=>array('type'=>self::TYPE_INT),
            'enabled'=>array('type'=>self::TYPE_INT),
            'background_image' => array('type' => self::TYPE_STRING),
            'position_background' => array('type' => self::TYPE_STRING),
            'title' => array('type' => self::TYPE_STRING,'lang' => true),
            'url' => array('type'=>self::TYPE_STRING,'lang'=>true),
            'bubble_text' => array('type' => self::TYPE_STRING,'lang' => true),   
            'sort_order' => array('type' => self::TYPE_INT),             
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

        foreach ($languages as $lang) {
            foreach (self::$definition['fields'] as $field => $params) {   
                $temp = $this->$field;

                if (isset($params['lang']) &&
                	$params['lang'] &&
                	!isset($temp[$lang['id_lang']])
                ) {                      
                    $temp[$lang['id_lang']] = '';                        
                }

                $this->$field = $temp;
            }
        }

        unset($context);        
        $this->setFields(Rbthememenu::$tab_class);
	}
}
