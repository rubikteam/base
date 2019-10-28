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

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(dirname(__FILE__).'/classes/RbMenuObj.php');
require_once(dirname(__FILE__).'/classes/RbMegaMenu.php');
require_once(dirname(__FILE__).'/classes/RbMenuColumn.php');
require_once(dirname(__FILE__).'/classes/RbMenuBlock.php');
require_once(dirname(__FILE__).'/classes/RbMenuConfig.php');
require_once(dirname(__FILE__).'/classes/RbMenuCache.php');
require_once(dirname(__FILE__).'/classes/RbMenuTab.php');
require_once(dirname(__FILE__).'/classes/RbMenuProducts.php');

class Rbthememenu extends Module
{
    public $_html;
    public $alerts;
    public static $menus;
    public static $columns;
    public static $blocks;
    public static $trans;
    public static $configs;
    public static $tab_class;
    public $is17 = true;
    public $multiLayout = false;
    public $googlefonts = array();
    public $image_cat_dir = false;

    public function __construct()
    {
        $this->name = 'rbthememenu';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'R_B';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->secure_key = Tools::encrypt($this->name);

        parent::__construct();

        $this->displayName = $this->l('Rb Theme Menu');
        $this->description = $this->l('This is great modules for create theme Prestashop !!');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->translates();
        $this->multiLayout = $this->multiLayoutExist();
        $this->image_dir = _PS_CAT_IMG_DIR_;

        $this->googlefonts = Tools::jsonDecode(
            Tools::file_get_contents(dirname(__FILE__).'/data/google-fonts.json'),
            true
        );

        if (!$this->googlefonts) {
            $this->googlefonts = array(
                array(
                    'id_option' => 'inherit',
                    'name' => $this->l('THEME DEFAULT FONT'),
                ),
                array(
                    'id_option' => 'Arial',
                    'name' => 'Arial',
                ),
                array(
                    'id_option' => 'Times new roman',
                    'name' => 'Times new roman',
                ),
            );
        }

        self::$menus = array(
            'form' => array(
                'legend' => array(
                    'title' => (int)Tools::getValue('itemId') ? $this->l('Edit menu') : $this->l('Add menu'),
                ),
                'input' => array(),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'name' => 'menu',
                'connect_to' => 'column',
                'connect_to2' => 'tab',
            ),
            'configs' => array(
                'enabled_vertical'=>array(
                    'type'=>'select',
                    'label'=>$this->l('Direction'),
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => '0',
                                    'name' => $this->l('Horizontal')
                                ),
                                array(
                                    'id_option' => '1',
                                    'name' => $this->l('Vertical')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                ),
                'menu_ver_text_color' => array(
                    'label' => $this->l('Vertical menu text color'),
                    'type' => 'color',
                    'default' => '#ffffff',
                    'validate' => 'isColor',
                    'class'=>'color mColorPickerInput'
                ),
                'menu_ver_background_color' => array(
                    'label' => $this->l('Vertical menu background color'),
                    'type' => 'color',
                    'default' => '#666666',
                    'validate' => 'isColor',
                    'class'=>'color mColorPickerInput'
                ),
                'menu_ver_alway_show' => array(
                    'label' => $this->l('Always open vertical menu'),
                    'type' => 'switch',
                    'default' => 0,
                    'desc' => $this->l('Only apply for desktop'),
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'yes',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'no',
                            'value' => 0,
                        )
                    ),
                ),
                'menu_ver_hidden_border' => array(
                    'label' => $this->l('Remove border'),
                    'type' => 'switch',
                    'default' => 0,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'menu_ver_hidden_border1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'menu_ver_hidden_border0',
                            'value' => 0,
                        )
                    ),
                ),
                'menu_item_width'=>array(
                    'label'=> $this->l('Menu item width'),
                    'type'=>'text',
                    'default'=>'230px',
                    'desc' => $this->l('Use "px" or "%" or "vw". Eg: "20%" or "230px" or "80vw"'),
                ),
                'tab_item_width'=>array(
                    'label'=> $this->l('Tab item width'),
                    'type'=>'text',
                    'default'=>'230px',
                    'desc' => $this->l('Use "px" or "%" or "vw". Eg: "20%" or "230px" or "80vw"'),
                ),
                'link_type' => array(
                    'type' => 'select',
                    'label' => $this->l('Menu link type'),
                    'name' => 'menu_type',
                    'class' => 'ybc_menu_type',
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'CUSTOM',
                                    'name' => $this->l('Custom link')
                                ),
                                array(
                                    'id_option' => 'CMS',
                                    'name' => $this->l('CMS page')
                                ),
                                array(
                                    'id_option' => 'CONTACT',
                                    'name' => $this->l('Contact')
                                ),
                                array(
                                    'id_option' => 'CATEGORY',
                                    'name' => $this->l('Category')
                                ),
                                array(
                                    'id_option' => 'MNFT',
                                    'name' => $this->l('Manufacturer')
                                ),
                                array(
                                    'id_option' => 'MNSP',
                                    'name' => $this->l('Supplier')
                                ),
                                array(
                                    'id_option' => 'HOME',
                                    'name' => $this->l('Home')
                                )
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'default' => 'CUSTOM',
                ),
                'title' => array(
                    'label' => $this->l('Title'),
                    'type' => 'text',
                    'required' => true,
                    'lang' => true,
                ),
                'link' => array(
                    'label' => $this->l('Custom link'),
                    'type' => 'text',
                    'lang' => true,
                    'showRequired' => true,
                ),
                'id_manufacturer' => array(
                    'label' => $this->l('Manufacturer'),
                    'type' => 'radio',
                    'values' => $this->getManufacturers(),
                    'showRequired' => true,
                ),
                'id_supplier' => array(
                    'label' => $this->l('Supplier'),
                    'type' => 'radio',
                    'values' => $this->getSuppliers(),
                    'showRequired' => true,
                ),
                'id_category' => array(
                    'type'  => 'categories',
                    'label' => $this->l('Category'),
                    'name'  => 'id_parent',
                    'tree'  => array(
                        'id'      => 'categories-tree',
                        'selected_categories' => array(),
                        'disabled_categories' => array(),
                        'use_checkbox'        => true,
                        'root_category' => (int)Category::getRootCategory()->id,
                    ),
                    'showRequired' => true,
                ),
                'id_cms' => array(
                    'label' => $this->l('CMS page'),
                    'type' => 'radio',
                    'values' => $this->getCMSs(),
                    'showRequired' => true,
                ),
                'menu_icon' => array(
                    'label' => $this->l('Menu icon font'),
                    'type' => 'text',
                    'class' => 'rb_browse_icon',
                    'desc' => $this->l('Use font awesome class. Eg: fa-bars, fa-plus, ...'),
                ),
                'menu_img_link' => array(
                    'label' => $this->l('Menu icon image'),
                    'type' => 'file',
                    'desc' => $this->l('Recommended size:20 x 20'),
                ),
                'sub_menu_type' => array(
                    'type' => 'select',
                    'label' => $this->l('Submenu alignment').($this->multiLayout ? ' '.$this->l('(LTR layout)') : ''),
                    'name' => 'menu_type',
                    'class' => 'ybc_menu_type',
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'FULL',
                                    'name' => $this->l('Auto')
                                ),
                                array(
                                    'id_option' => 'LEFT',
                                    'name' => $this->l('Left')
                                ),
                                array(
                                    'id_option' => 'RIGHT',
                                    'name' => $this->l('Right')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'default' => 'FULL',
                    'desc' => $this->multiLayout ? $this->l('Submenu alignment is reversed on RTL layout automatically') : '',
                ),
                'display_tabs_in_full_width' => array(
                    'label' => $this->l('Display tabs in full width'),
                    'type' => 'switch',
                    'default' => 0,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'display_tabs_in_full_width_1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'display_tabs_in_full_width_0',
                            'value' => 0,
                        )
                    ),
                ),
                'sub_menu_max_width' => array(
                    'label' => $this->l('Sub menu width'),
                    'type' => 'text',
                    'required' => true,
                    'default' => '100%',
                    'desc' => $this->l('Use "px" or "%" or "vw". Eg: "100%" or "100px" or "80vw"'),
                ),
                'custom_class' => array(
                    'label' => $this->l('Custom class'),
                    'type' => 'text',
                ),
                'bubble_text' => array(
                    'label' => $this->l('Bubble alert text'),
                    'type' => 'text',
                    'lang' => true,
                    'desc' => $this->l('New, Sale, Hot... Leave blank if you do not want to have a bubble alert for this menu')
                ),
                'bubble_text_color' => array(
                    'label' => $this->l('Bubble alert text color'),
                    'type' => 'color',
                    'default' => '#ffffff',
                    'validate' => 'isColor',
                    'class'=>'color mColorPickerInput'
                ),
                'bubble_background_color' => array(
                    'label' => $this->l('Bubble alert background color'),
                    'type' => 'color',
                    'default' => '#FC4444',
                    'validate' => 'isColor',
                    'class'=>'color mColorPickerInput'
                ),
                'background_image' => array(
                    'label' => $this->l('Background image'),
                    'type' => 'file',
                ),
                'position_background' => array(
                    'label' => $this->l('Background position'),
                    'type' => 'select',
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'center',
                                    'name' => $this->l('Center')
                                ),
                                array(
                                    'id_option' => 'top',
                                    'name' => $this->l('Top')
                                ),
                                array(
                                    'id_option' => 'top right',
                                    'name' => $this->l('Top right')
                                ),
                                array(
                                    'id_option' => 'top left',
                                    'name' => $this->l('Top left')
                                ),
                                array(
                                    'id_option' => 'left',
                                    'name' => $this->l('Left')
                                ),
                                array(
                                    'id_option' => 'bottom',
                                    'name' => $this->l('Bottom')
                                ),
                                array(
                                    'id_option' => 'bottom left',
                                    'name' => $this->l('Bottom left')
                                ),
                                array(
                                    'id_option' => 'bottom right',
                                    'name' => $this->l('Bottom right')
                                ),
                                array(
                                    'id_option' => 'right',
                                    'name' => $this->l('Right')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                ),
                'menu_open_new_tab' => array(
                    'label' => $this->l('Open link in new tab'),
                    'type' => 'switch',
                    'default' => 0,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'menu_open_new_tab1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'menu_open_new_tab0',
                            'value' => 0,
                        )
                    ),
                ),
                'sort_order' => array(
                    'label' => $this->l('Sort order'),
                    'type' => 'sort_order',
                    'required' => true,
                    'default' => 1,
                    'order_group' => false,
                ),
                'enabled' => array(
                    'label' => $this->l('Enabled'),
                    'type' => 'switch',
                    'default' => 1,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'menu_enabled_1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'menu_enabled_0',
                            'value' => 0,
                        )
                    ),
                ),
            ),
        );

        self::$tab_class = array(
            'form' => array(
                'legend' => array(
                    'title' => (int)Tools::getValue('itemId') ? $this->l('Edit tab') : $this->l('Add tab'),
                ),
                'input' => array(),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'name' => 'tab',
                'connect_to' => 'column',
                'parent' => 'menu',
            ),
            'configs' => array(
                'title' => array(
                    'label' => $this->l('Title'),
                    'type' => 'text',
                    'required' => true,
                    'lang' => true,
                ),
                'url'=> array(
                    'label'=> 'Custom link',
                    'type'=>'text',
                    'lang'=>true,
                ),
                'tab_icon' => array(
                    'label' => $this->l('Tab icon font'),
                    'type' => 'text',
                    'class' => 'rb_browse_icon',
                    'desc' => $this->l('Use font awesome class. Ex: fa-bars, fa-plus, ...'),
                ),
                'tab_img_link' => array(
                    'label' => $this->l('Tab icon image'),
                    'type' => 'file',
                    'desc' => $this->l('Recommended size:20 x 20'),
                ),
                'tab_sub_width' => array(
                    'label' => $this->l('Tab content width'),
                    'type' => 'text',
                    'desc' => $this->l('Use "px" or "%" or "vw". Eg: "20%" or "230px" or "80vw"'),
                ),
                'tab_sub_content_pos' => array(
                    'label' => $this->l('Display tab content from top'),
                    'type' => 'switch',
                    'default' => 1,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'tab_sub_content_pos_1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'tab_sub_content_pos_0',
                            'value' => 0,
                        )
                    ),
                ),
                'bubble_text' => array(
                    'label' => $this->l('Bubble alert text'),
                    'type' => 'text',
                    'lang' => true,
                    'desc' => $this->l('New, Sale, Hot... Leave blank if you do not want to have a bubble alert for this menu')
                ),
                'bubble_text_color' => array(
                    'label' => $this->l('Bubble alert text color'),
                    'type' => 'color',
                    'default' => '#ffffff',
                    'validate' => 'isColor',
                    'class'=>'color mColorPickerInput'
                ),
                'bubble_background_color' => array(
                    'label' => $this->l('Bubble alert background color'),
                    'type' => 'color',
                    'default' => '#FC4444',
                    'validate' => 'isColor',
                    'class'=>'color mColorPickerInput'
                ),
                'background_image' => array(
                    'label' => $this->l('Background image'),
                    'type' => 'file',
                ),
                'position_background' => array(
                    'label' => $this->l('Background position'),
                    'type' => 'select',
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'center',
                                    'name' => $this->l('Center')
                                ),
                                array(
                                    'id_option' => 'top',
                                    'name' => $this->l('Top')
                                ),
                                array(
                                    'id_option' => 'top right',
                                    'name' => $this->l('Top right')
                                ),
                                array(
                                    'id_option' => 'top left',
                                    'name' => $this->l('Top left')
                                ),
                                array(
                                    'id_option' => 'left',
                                    'name' => $this->l('Left')
                                ),
                                array(
                                    'id_option' => 'bottom',
                                    'name' => $this->l('Bottom')
                                ),
                                array(
                                    'id_option' => 'bottom left',
                                    'name' => $this->l('Bottom left')
                                ),
                                array(
                                    'id_option' => 'bottom right',
                                    'name' => $this->l('Bottom right')
                                ),
                                array(
                                    'id_option' => 'right',
                                    'name' => $this->l('Right')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                ),
                'id_menu' => array(
                    'label' => $this->l('Menu'),
                    'type' => 'hidden',
                    'default' => ($id_menu = (int)Tools::isSubmit('id_menu')) ? $id_menu : 0,
                    'required' => true,
                ),
                'sort_order' => array(
                    'label' => $this->l('Sort order'),
                    'type' => 'sort_order',
                    'required' => true,
                    'default' => 1,
                    'order_group' => array(
                        'menu'=>'id_menu',
                    ),
                ),
            ),
        );

        self::$columns = array(
            'form' => array(
                'legend' => array(
                    'title' => (int)Tools::getValue('itemId') ? $this->l('Edit column') : $this->l('Add column'),
                ),
                'input' => array(),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'name' => 'column',
                'connect_to' => 'block',
                'parent' => 'menu',
                'parent2'=>'tab'
            ),
            'configs' => array(
                'column_size' => array(
                    'type' => 'select',
                    'label' => $this->l('Column width size'),
                    'name' => 'menu_type',
                    'options' => array(
                         'query' => $this->getColumnSizes(),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'default' => '3',
                ),
                'is_breaker' => array(
                    'label' => $this->l('Break'),
                    'type' => 'switch',
                    'default' => 0,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'menu_enabled_1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'menu_enabled_0',
                            'value' => 0,
                        )
                    ),
                    'desc' => $this->l('Break from this column to new line'),
                ),
                'id_menu' => array(
                    'label' => $this->l('Menu'),
                    'type' => 'hidden',
                    'default' => ($id_menu = (int)Tools::isSubmit('id_menu')) ? $id_menu : 0,
                    'required' => true,
                ),
                'id_tab' => array(
                    'label' => $this->l('Tab'),
                    'type' => 'hidden',
                    'default' => ($id_tab = (int)Tools::isSubmit('id_tab')) ? $id_tab : 0,
                    'required' => true,
                ),
                'sort_order' => array(
                    'label' => $this->l('Sort order'),
                    'type' => 'sort_order',
                    'required' => true,
                    'default' => 1,
                    'order_group' => array(
                        'menu'=>'id_menu',
                        'tab'=>'id_tab',
                    ),
                ),
            ),
        );

        self::$blocks = array(
            'form' => array(
                'legend' => array(
                    'title' => (int)Tools::getValue('itemId') ? $this->l('Edit block') : $this->l('Add block'),
                ),
                'input' => array(),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'name' => 'block',
                'parent' => 'column',
            ),
            'configs' => array(
                'block_type' => array(
                    'type' => 'select',
                    'label' => $this->l('Block type'),
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'HTML',
                                    'name' => $this->l('Text/Html')
                                ),
                                array(
                                    'id_option' => 'IMAGE',
                                    'name' => $this->l('Image')
                                ),
                                array(
                                    'id_option' => 'CATEGORY',
                                    'name' => $this->l('Category')
                                ),
                                array(
                                    'id_option' => 'CMS',
                                    'name' => $this->l('CMS page')
                                ),
                                array(
                                    'id_option' => 'MNFT',
                                    'name' => $this->l('Manufacturer')
                                ),
                                array(
                                    'id_option' => 'MNSP',
                                    'name' => $this->l('Supplier')
                                ),
                                array(
                                    'id_option' => 'PRODUCT',
                                    'name' => $this->l('Products')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'default' => 'HTML',
                ),
                'title' => array(
                    'label' => $this->l('Title'),
                    'type' => 'text',
                    'required' => true,
                    'lang' => true,
                ),
                'id_column' => array(
                    'label' => $this->l('Column'),
                    'type' => 'hidden',
                    'default' => ($id_column = (int)Tools::isSubmit('id_column')) ? $id_column : 0,
                    'required' => true,
                ),
                'title_link' => array(
                    'label' => $this->l('Title link'),
                    'type' => 'text',
                    'lang' => true,
                    'desc' => $this->l('Leave blank if you do not want to add a link to block title'),
                ),
                'id_manufacturers' => array(
                    'label' => $this->l('Manufacturers'),
                    'type' => 'checkbox',
                    'values' => array(
                         'query' => $this->getManufacturers(),
                         'id' => 'id',
                         'name' => 'label'
                    ),
                    'showRequired' => true,
                ),
                'order_by_manufacturers' => array(
                    'type' => 'select',
                    'label' => $this->l('Order by'),
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'name ASC,m.id_manufacturer ASC',
                                    'name' => $this->l('Name A-Z')
                                ),
                                array(
                                    'id_option' => 'name DESC,m.id_manufacturer ASC',
                                    'name' => $this->l('Name Z-A')
                                ),
                                array(
                                    'id_option' => 'm.id_manufacturer DESC',
                                    'name' => $this->l('Newest manufacturer first')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'default' => 'nam ASC,m.id_manufacturer ASC',
                ),
                'display_mnu_img' => array(
                    'label' => $this->l('Display manufacturers image'),
                    'type' => 'switch',
                    'default' => 0,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'menu_mnu_img_1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'menu_mnu_img_0',
                            'value' => 0,
                        )
                    ),
                ),
                'display_mnu_name' => array(
                    'label' => $this->l('Display manufacturers name'),
                    'type' => 'switch',
                    'default' => 1,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'menu_mnu_name_1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'menu_mnu_name_0',
                            'value' => 0,
                        )
                    ),
                ),
                'display_mnu_inline' => array(
                    'label' => $this->l('Manufacturers per row'),
                    'type' => 'select',
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => '1',
                                    'name' => $this->l('1')
                                ),
                                array(
                                    'id_option' => '2',
                                    'name' => $this->l('2')
                                ),
                                array(
                                    'id_option' => '3',
                                    'name' => $this->l('3')
                                ),
                                array(
                                    'id_option' => '4',
                                    'name' => $this->l('4')
                                ),
                                array(
                                    'id_option' => '5',
                                    'name' => $this->l('5')
                                ),
                                array(
                                    'id_option' => '6',
                                    'name' => $this->l('6')
                                ),
                                array(
                                    'id_option' => '7',
                                    'name' => $this->l('7')
                                ),
                                array(
                                    'id_option' => '8',
                                    'name' => $this->l('8')
                                ),
                                array(
                                    'id_option' => '9',
                                    'name' => $this->l('9')
                                ),     array(
                                    'id_option' => '10',
                                    'name' => $this->l('10')
                                ),
                                array(
                                    'id_option' => '11',
                                    'name' => $this->l('11')
                                ),
                                array(
                                    'id_option' => '12',
                                    'name' => $this->l('12')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                ),
                'id_suppliers' => array(
                    'label' => $this->l('Suppliers'),
                    'type' => 'checkbox',
                    'values' => array(
                         'query' => $this->getSuppliers(),
                         'id' => 'id',
                         'name' => 'label'
                    ),
                    'showRequired' => true,
                ),
                'order_by_suppliers' => array(
                    'type' => 'select',
                    'label' => $this->l('Order by'),
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'name ASC,s.id_supplier ASC',
                                    'name' => $this->l('Name A-Z')
                                ),
                                array(
                                    'id_option' => 'name DESC,s.id_supplier ASC',
                                    'name' => $this->l('Name Z-A')
                                ),
                                array(
                                    'id_option' => 's.id_supplier DESC',
                                    'name' => $this->l('Newest supplier first')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'default' => 'nam ASC,s.id_supplier ASC',
                ),
                'display_suppliers_img' => array(
                    'label' => $this->l('Display suppliers image'),
                    'type' => 'switch',
                    'default' => 0,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'menu_suppliers_img_1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'menu_suppliers_img_0',
                            'value' => 0,
                        )
                    ),
                ),
                'display_suppliers_name' => array(
                    'label' => $this->l('Display suppliers name'),
                    'type' => 'switch',
                    'default' => 1,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'menu_suppliers_name_1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'menu_suppliers_name_0',
                            'value' => 0,
                        )
                    ),
                ),
                'display_suppliers_inline' => array(
                    'label' => $this->l('Suppliers per row'),
                    'type' => 'select',
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => '1',
                                    'name' => $this->l('1')
                                ),
                                array(
                                    'id_option' => '2',
                                    'name' => $this->l('2')
                                ),
                                array(
                                    'id_option' => '3',
                                    'name' => $this->l('3')
                                ),
                                array(
                                    'id_option' => '4',
                                    'name' => $this->l('4')
                                ),
                                array(
                                    'id_option' => '5',
                                    'name' => $this->l('5')
                                ),
                                array(
                                    'id_option' => '6',
                                    'name' => $this->l('6')
                                ),
                                array(
                                    'id_option' => '7',
                                    'name' => $this->l('7')
                                ),
                                array(
                                    'id_option' => '8',
                                    'name' => $this->l('8')
                                ),
                                array(
                                    'id_option' => '9',
                                    'name' => $this->l('9')
                                ),     array(
                                    'id_option' => '10',
                                    'name' => $this->l('10')
                                ),
                                array(
                                    'id_option' => '11',
                                    'name' => $this->l('11')
                                ),
                                array(
                                    'id_option' => '12',
                                    'name' => $this->l('12')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                ),
                'id_categories' => array(
                    'type'  => 'categories',
                    'label' => $this->l('Categories'),
                    'name'  => 'id_parent',
                    'tree'  => array(
                        'id'      => 'categories-tree',
                        'selected_categories' => array(),
                        'disabled_categories' => array(),
                        'use_checkbox'        => true,
                        'root_category' => (int)Category::getRootCategory()->id,
                    ),
                    'showRequired' => true,
                ),
                'order_by_category' => array(
                    'type' => 'select',
                    'label' => $this->l('Order by'),
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'cl.name ASC,c.id_category ASC',
                                    'name' => $this->l('Name A-Z')
                                ),
                                array(
                                    'id_option' => 'cl.name DESC,c.id_category ASC',
                                    'name' => $this->l('Name Z-A')
                                ),
                                array(
                                    'id_option' => 'c.position ASC,c.id_category ASC',
                                    'name' => $this->l('Default order ')
                                ),
                                array(
                                    'id_option' => 'c.id_category DESC',
                                    'name' => $this->l('Newest category first')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'default' => 'cl.name ASC,c.id_category ASC',
                ),
                'id_cmss' => array(
                    'label' => $this->l('CMS pages'),
                    'type' => 'checkbox',
                    'values' => array(
                         'query' => $this->getCMSs(),
                         'id' => 'id',
                         'name' => 'label'
                    ),
                    'showRequired' => true,
                ),
                'content' => array(
                    'label' => $this->l('HTML/Text content'),
                    'type' => 'textarea',
                    'lang' => true,
                    'showRequired' => true,
                ),
                'image' => array(
                    'label' => $this->l('Image'),
                    'type' => 'file',
                    'hide_delete' => true,
                    'showRequired' => true,
                ),
                'image_link' => array(
                    'label' => $this->l('Image link'),
                    'type' => 'text',
                    'lang' => true,
                    'desc' => $this->l('Leave blank if you do not want to add a link to image'),
                ),
                'product_type' => array(
                    'label' => $this->l('Product type'),
                    'type' => 'radios',
                    'default' => 'specific',
                    'values' => array(
                        array(
                            'label' => $this->l('New products'),
                            'value' => 'new',
                        ),
                        array(
                            'label' => $this->l('Popular products'),
                            'value' => 'popular',
                        ),
                        array(
                            'label' => $this->l('Special products'),
                            'value' => 'special',
                        ),
                        array(
                            'label' => $this->l('Best sellers'),
                            'value' => 'best',
                        ),
                        array(
                            'label' => $this->l('Specific products '),
                            'value' => 'specific',
                        ),
                    ),
                ),
                'id_products' => array(
                    'label' => $this->l('Search products'),
                    'type' => 'search',
                    'placeholder' => $this->l('Search product by ID, name or reference'),
                    'showRequired' => true,
                ),
                'product_count' => array(
                    'label' => $this->l('Product count'),
                    'type' => 'text',
                    'default' => '2',
                    'showRequired' => true,
                    'suffix' => $this->l('item(s)')
                ),
                'sort_order' => array(
                    'label' => $this->l('Sort order'),
                    'type' => 'sort_order',
                    'required' => true,
                    'default' => 1,
                    'order_group' => array(
                        'column'=>'id_column',
                    ),
                ),
                'show_description' => array(
                    'label' => $this->l('Enable product description'),
                    'type' => 'switch',
                    'default' => 0,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'show_description_1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'show_description_0',
                            'value' => 0,
                        )
                    ),
                ),
                'show_clock' => array(
                    'label' => $this->l('Enable count down clock'),
                    'type' => 'switch',
                    'default' => 0,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'show_clock_1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'show_clock_0',
                            'value' => 0,
                        )
                    ),
                ),
                'display_title' => array(
                    'label' => $this->l('Display title'),
                    'type' => 'switch',
                    'default' => 1,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'menu_enabled_1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'menu_enabled_0',
                            'value' => 0,
                        )
                    ),
                ),
                'enabled' => array(
                    'label' => $this->l('Enabled'),
                    'type' => 'switch',
                    'default' => 1,
                    'values' => array(
                        array(
                            'label' => $this->l('Yes'),
                            'id' => 'menu_enabled_1',
                            'value' => 1,
                        ),
                        array(
                            'label' => $this->l('No'),
                            'id' => 'menu_enabled_0',
                            'value' => 0,
                        )
                    ),
                ),
            ),
        );
        
        $imageTypes = $this->imageTypes(true);

        self::$configs = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configuration'),
                    'icon' => 'icon-AdminAdmin'
                ),
                'input' => array(),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'name' => 'config'
            ),
            'configs' => array(
                'RBTHEMEMENU_HOOK_TO' => array(
                    'type' => 'select',
                    'label' => $this->l('Hook to'),
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'default',
                                    'name' => $this->l('Default hook')
                                ),
                                array(
                                    'id_option' => 'customhook',
                                    'name' => $this->l('Custom hook')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name',
                    ),
                    'default' => 'default',
                    'desc' => $this->l('Put {hook h=\'displayMegaMenu\'} on tpl file where you want to display the mega menu'),
                ),
                'RBTHEMEMENU_TRANSITION_EFFECT' => array(
                    'type' => 'select',
                    'label' => $this->l('Submenu transition effect'),
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'fade',
                                    'name' => $this->l('Default')
                                ),
                                array(
                                    'id_option' => 'slide',
                                    'name' => $this->l('Slide down')
                                ),
                                array(
                                    'id_option' => 'scale_down',
                                    'name' => $this->l('Scale down')
                                ),
                                array(
                                    'id_option' => 'fadeInUp',
                                    'name' => $this->l('Fade in up')
                                ),
                                array(
                                    'id_option' => 'zoom',
                                    'name' => $this->l('Zoom In')
                                )
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'default' => 'fade',
                ),
                'RBTHEMEMENU_DIR' => array(
                    'type' => 'select',
                    'label' => $this->l('Language direction mode'),
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'auto',
                                    'name' => $this->l('Auto detect LTR or RTL')
                                ),
                                array(
                                    'id_option' => 'ltr',
                                    'name' => $this->l('LTR')
                                ),
                                array(
                                    'id_option' => 'rtl',
                                    'name' => $this->l('RTL')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'default' => 'auto',
                ),
                'ETS_MOBILE_rb_TYPE' => array(
                    'type' => 'select',
                    'label' => $this->l('Mobile menu type'),
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'floating',
                                    'name' => $this->l('Floating')
                                ),
                                array(
                                    'id_option' => 'default',
                                    'name' => $this->l('Bottom')
                                ),
                                array(
                                    'id_option' => 'full',
                                    'name' => $this->l('Full screen')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'default' => 'floating',
                ),
                //hiep
                'RBTHEMEMENU_IMAGE_TYPE' => array(
                    'type' => 'select',
                    'label' => $this->l('Thumbnail image type'),
                    'options' => array(
                         'query' => $imageTypes[0],
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'default' => $imageTypes[1],
                ),
                'RBTHEMEMENU_INCLUDE_SUB_CATEGORIES' => array(
                    'type' => 'switch',
                    'label' => $this->l('Include sub-categories'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'default' => 1,
                ),
                'RBTHEMEMENU_STICKY_ENABLED' => array(
                    'type' => 'switch',
                    'label' => $this->l('Enable Sticky menu'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'default' => 1,
                ),
                'RBTHEMEMENU_STICKY_DISMOBILE' => array(
                    'type' => 'switch',
                    'label' => $this->l('Hide sticky menu on mobile'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'default' => 1,
                ),
                'RBTHEMEMENU_CLICK_TEXT_SHOW_SUB' => array(
                    'type' => 'switch',
                    'label' => $this->l('Click on menu text to open its submenu'),
                    'is_bool' => true,
                    'desc' => $this->l('Apply for mobile only'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'default' => 0,
                ),
                'RBTHEMEMENU_SHOW_ICON_VERTICAL' => array(
                    'type' => 'switch',
                    'label' => $this->l('Show vertical menu icon on mobile'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'default' => 1,
                ),
                'RBTHEMEMENU_ACTIVE_ENABLED' => array(
                    'type' => 'switch',
                    'label' => $this->l('Display active menu item'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'default' => 0,
                ),
                'RBTHEMEMENU_ACTIVE_BG_GRAY' => array(
                    'type' => 'switch',
                    'label' => $this->l('Enable grey overlay effect for submenu'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'default' => 1,
                ),
                'RBTHEMEMENU_CACHE_ENABLED' => array(
                    'type' => 'switch',
                    'label' => $this->l('Enable cache'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'default' => 0,
                ),
                'RBTHEMEMENU_CACHE_LIFE_TIME' => array(
                    'type' => 'text',
                    'label' => $this->l('Cache lifetime'),
                    'default' => 24,
                    'suffix' => $this->l('Hours'),
                    'validate' => 'isUnsignedInt',
                ),
                'RBTHEMEMENU_LAYOUT' => array(
                    'type' => 'select',
                    'label' => $this->l('Layout type'),
                    'options' => array(
                         'query' => array(
                                array(
                                    'id_option' => 'layout1',
                                    'name' => $this->l('Layout 1')
                                ),
                                array(
                                    'id_option' => 'layout2',
                                    'name' => $this->l('Layout 2')
                                ),
                                array(
                                    'id_option' => 'layout3',
                                    'name' => $this->l('Layout 3')
                                ),
                                array(
                                    'id_option' => 'layout4',
                                    'name' => $this->l('Layout 4')
                                ),
                                array(
                                    'id_option' => 'layout5',
                                    'name' => $this->l('Layout 5')
                                ),
                            ),
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'default' => 'layout1',
                ),
                'RBTHEMEMENU_HEADING_FONT' => array(
                    'label' => $this->l('Heading font'),
                    'type' => 'select',
                    'options' => array(
                         'query' => $this->googlefonts,
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'desc' => $this->l('Use default font of your theme or select a Google font from the list'),
                    'default' => 'inherit',
                ),
                'RBTHEMEMENU_HEADING_FONT_SIZE' => array(
                    'label' => $this->l('Heading font size'),
                    'type' => 'text',
                    'default' => '16',
                    'suffix'=>'px',
                    'class'=>'col-lg-3',
                    'validate'=>'isUnsignedInt'
                ),
                'RBTHEMEMENU_TEXT_FONT' => array(
                    'label' => $this->l('General text font'),
                    'type' => 'select',
                    'options' => array(
                         'query' => $this->googlefonts,
                         'id' => 'id_option',
                         'name' => 'name'
                    ),
                    'desc' => $this->l('Use default font of your theme or select a Google font from the list'),
                    'default' => 'inherit',
                ),
                'RBTHEMEMENU_TEXTTITLE_FONT_SIZE' => array(
                    'label' => $this->l('Title submenu font size'),
                    'type' => 'text',
                    'default' => '16',
                    'suffix'=>'px',
                    'class'=>'col-lg-3',
                    'validate'=>'isUnsignedInt'
                ),
                'RBTHEMEMENU_TEXT_FONT_SIZE' => array(
                    'label' => $this->l('General text font size'),
                    'type' => 'text',
                    'default' => '14',
                    'suffix'=>'px',
                    'class'=>'col-lg-3',
                    'validate'=>'isUnsignedInt'
                ),
                'RBTHEMEMENU_COLOR1' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu background'),
                    'validate' => 'isColor',
                    'default' =>  '',
                    'form_group_class'=>'custom_color layout1'
                ),
                'RBTHEMEMENU_COLOR2' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu text color'),
                    'validate' => 'isColor',
                    'default' =>  '#484848',
                    'form_group_class'=>'custom_color layout1'
                ),
                'RBTHEMEMENU_COLOR3' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu text hover'),
                    'validate' => 'isColor',
                    'default' =>  '#ec4249',
                    'form_group_class'=>'custom_color layout1'
                ),
                'RBTHEMEMENU_COLOR4' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu background hover'),
                    'validate' => 'isColor',
                    'default' =>  '#ffffff',
                    'form_group_class'=>'custom_color layout1'
                ),
                'RBTHEMEMENU_COLOR5' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu background'),
                    'validate' => 'isColor',
                    'default' =>  '#ffffff',
                    'form_group_class'=>'custom_color layout1'
                ),
                'RBTHEMEMENU_COLOR_36' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu title'),
                    'validate' => 'isColor',
                    'default' =>  '#414141',
                    'form_group_class'=>'custom_color layout1'
                ),
                'RBTHEMEMENU_COLOR6' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu text color'),
                    'validate' => 'isColor',
                    'default' =>  '#414141',
                    'form_group_class'=>'custom_color layout1'
                ),
                'RBTHEMEMENU_COLOR7' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu link hover'),
                    'validate' => 'isColor',
                    'default' =>  '#ec4249',
                    'form_group_class'=>'custom_color layout1'
                ),

                'RBTHEMEMENU_COLOR8' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu background'),
                    'validate' => 'isColor',
                    'default' =>  '#3cabdb',
                    'form_group_class'=>'custom_color layout2'
                ),
                'RBTHEMEMENU_COLOR9' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu text color'),
                    'validate' => 'isColor',
                    'default' =>  '#ffffff',
                    'form_group_class'=>'custom_color layout2'
                ),
                'RBTHEMEMENU_COLOR_10' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu text hover'),
                    'validate' => 'isColor',
                    'default' =>  '#ffffff',
                    'form_group_class'=>'custom_color layout2'
                ),
                'RBTHEMEMENU_COLOR_11' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu background hover'),
                    'validate' => 'isColor',
                    'default' =>  '#50b4df',
                    'form_group_class'=>'custom_color layout2'
                ),
                'RBTHEMEMENU_COLOR_12' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu background'),
                    'validate' => 'isColor',
                    'default' =>  '#ffffff',
                    'form_group_class'=>'custom_color layout2'
                ),
                'RBTHEMEMENU_COLOR_37' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu title'),
                    'validate' => 'isColor',
                    'default' =>  '#414141',
                    'form_group_class'=>'custom_color layout2'
                ),
                'RBTHEMEMENU_COLOR_13' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu text color'),
                    'validate' => 'isColor',
                    'default' =>  '#666666',
                    'form_group_class'=>'custom_color layout2'
                ),
                'RBTHEMEMENU_COLOR_14' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu link hover'),
                    'validate' => 'isColor',
                    'default' =>  '#fc4444',
                    'form_group_class'=>'custom_color layout2'
                ),

                'RBTHEMEMENU_COLOR_15' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu background'),
                    'validate' => 'isColor',
                    'default' =>  '#333333',
                    'form_group_class'=>'custom_color layout3'
                ),
                'RBTHEMEMENU_COLOR_16' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu text color'),
                    'validate' => 'isColor',
                    'default' =>  '#ffffff',
                    'form_group_class'=>'custom_color layout3'
                ),
                'RBTHEMEMENU_COLOR_17' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu text hover'),
                    'validate' => 'isColor',
                    'default' =>  '#ffffff',
                    'form_group_class'=>'custom_color layout3'
                ),
                'RBTHEMEMENU_COLOR_18' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu background hover'),
                    'validate' => 'isColor',
                    'default' =>  '#000000',
                    'form_group_class'=>'custom_color layout3'
                ),
                'RBTHEMEMENU_COLOR_19' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu background'),
                    'validate' => 'isColor',
                    'default' =>  '#000000',
                    'form_group_class'=>'custom_color layout3'
                ),
                'RBTHEMEMENU_COLOR_38' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu title'),
                    'validate' => 'isColor',
                    'default' =>  '#ec4249',
                    'form_group_class'=>'custom_color layout3'
                ),
                'RBTHEMEMENU_COLOR_20' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu text color'),
                    'validate' => 'isColor',
                    'default' =>  '#dcdcdc',
                    'form_group_class'=>'custom_color layout3'
                ),
                'RBTHEMEMENU_COLOR_21' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu link hover'),
                    'validate' => 'isColor',
                    'default' =>  '#fc4444',
                    'form_group_class'=>'custom_color layout3'
                ),

                'RBTHEMEMENU_COLOR_22' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu background'),
                    'validate' => 'isColor',
                    'default' =>  '#ffffff',
                    'form_group_class'=>'custom_color layout4'
                ),
                'RBTHEMEMENU_COLOR_23' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu text color'),
                    'validate' => 'isColor',
                    'default' =>  '#333333',
                    'form_group_class'=>'custom_color layout4'
                ),
                'RBTHEMEMENU_COLOR_24' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu text hover'),
                    'validate' => 'isColor',
                    'default' =>  '#ffffff',
                    'form_group_class'=>'custom_color layout4'
                ),
                'RBTHEMEMENU_COLOR_25' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu background hover'),
                    'validate' => 'isColor',
                    'default' =>  '#ec4249',
                    'form_group_class'=>'custom_color layout4'
                ),
                'RBTHEMEMENU_COLOR_26' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu background'),
                    'validate' => 'isColor',
                    'default' =>  '#ffffff',
                    'form_group_class'=>'custom_color layout4'
                ),
                'RBTHEMEMENU_COLOR_39' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu title'),
                    'validate' => 'isColor',
                    'default' =>  '#414141',
                    'form_group_class'=>'custom_color layout4'
                ),
                'RBTHEMEMENU_COLOR_27' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu text color'),
                    'validate' => 'isColor',
                    'default' =>  '#666666',
                    'form_group_class'=>'custom_color layout4'
                ),
                'RBTHEMEMENU_COLOR_28' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu link hover'),
                    'validate' => 'isColor',
                    'default' =>  '#ec4249',
                    'form_group_class'=>'custom_color layout4'
                ),

                'RBTHEMEMENU_COLOR_29' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu background'),
                    'validate' => 'isColor',
                    'default' =>  '#f6f6f6',
                    'form_group_class'=>'custom_color layout5'
                ),
                'RBTHEMEMENU_COLOR_30' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu text color'),
                    'validate' => 'isColor',
                    'default' =>  '#333333',
                    'form_group_class'=>'custom_color layout5'
                ),
                'RBTHEMEMENU_COLOR_31' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu text hover'),
                    'validate' => 'isColor',
                    'default' =>  '#ec4249',
                    'form_group_class'=>'custom_color layout5'
                ),
                'RBTHEMEMENU_COLOR_32' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu background hover'),
                    'validate' => 'isColor',
                    'default' =>  '',
                    'form_group_class'=>'custom_color layout5'
                ),
                'RBTHEMEMENU_COLOR_33' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu background'),
                    'validate' => 'isColor',
                    'default' =>  '#ffffff',
                    'form_group_class'=>'custom_color layout5'
                ),
                'RBTHEMEMENU_COLOR_40' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu title'),
                    'validate' => 'isColor',
                    'default' =>  '#414141',
                    'form_group_class'=>'custom_color layout5'
                ),
                'RBTHEMEMENU_COLOR_34' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu text color'),
                    'validate' => 'isColor',
                    'default' =>  '#333333',
                    'form_group_class'=>'custom_color layout5'
                ),
                'RBTHEMEMENU_COLOR_35' => array(
                    'type' => 'color',
                    'label' => $this->l('Sub-menu link hover'),
                    'validate' => 'isColor',
                    'default' =>  '#ec4249',
                    'form_group_class'=>'custom_color layout5'
                ),
                'RBTHEMEMENU_MOBILE_BG_BAR' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu bar background on mobile'),
                    'validate' => 'isColor',
                    'default' =>  '#000000',
                ),
                'RBTHEMEMENU_MOBILE_COLOR_BAR' => array(
                    'type' => 'color',
                    'label' => $this->l('Menu bar color on mobile'),
                    'validate' => 'isColor',
                    'default' =>  '#ffffff',
                ),
                'RBTHEMEMENU_CUSTOM_CLASS' => array(
                    'type' => 'text',
                    'label' => $this->l('Custom class'),
                ),
                'RBTHEMEMENU_DISPLAY_SHOPPING_CART'=>array(
                    'type'=>'switch',
                    'label' => $this->l('Display shopping cart'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'desc'=> $this->l('Not recommended! For advanced users only. Menu can be broken when this option is enabled (especially on custom theme). You are required to have HTML/CSS knowledge to refine the issues. Please understand this is out of free support as it depends on your theme'),
                    'default' => 0,
                ),
                'RBTHEMEMENU_DISPLAY_SEARCH'=>array(
                    'type'=>'switch',
                    'label' => $this->l('Display search box'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'desc'=> $this->l('Not recommended! For default search module and advanced users only. Menu can be broken when this option is enabled (especially on custom theme). You are required to have HTML/CSS knowledge to refine the issues. Please understand this is out of free support as it depends on your theme'),
                    'default' => 0,
                ),
                'RBTHEMEMENU_SEARCH_DISPLAY_DEFAULT'=>array(
                    'type'=>'switch',
                    'label' => $this->l('Open searchbox by default'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'default' => 0,
                    'form_group_class'=>'rb_form_display_search'
                ),
                'RBTHEMEMENU_DISPLAY_CUSTOMER_INFO'=>array(
                    'type'=>'switch',
                    'label' => $this->l('Display user info links'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'desc'=> $this->l('Not recommended! For advanced users only. Menu can be broken when this option is enabled (especially on custom theme). You are required to have HTML/CSS knowledge to refine the issues. Please understand this is out of free support as it depends on your theme'),
                    'default' => 0,
                ),
                'RBTHEMEMENU_CUSTOM_HTML_TEXT'=>array(
                    'type'=>'textarea',
                    'label' => $this->l('Custom HTML text'),
                    'default' => '',
                    'lang'=>true,
                )

            )
        );

        if (!Module::getInstanceByName('ps_shoppingcart') || !Module::isEnabled('ps_shoppingcart'))
            unset(self::$configs['configs']['RBTHEMEMENU_DISPLAY_SHOPPING_CART']);

        if (!Module::getInstanceByName('ps_searchbar')|| !Module::isEnabled('ps_searchbar')) {
            unset(self::$configs['configs']['RBTHEMEMENU_DISPLAY_SEARCH']);
            unset(self::$configs['configs']['RBTHEMEMENU_SEARCH_DISPLAY_DEFAULT']);
        }

        if (!Module::getInstanceByName('ps_customersignin') || !Module::isEnabled('ps_customersignin'))
            unset(self::$configs['configs']['RBTHEMEMENU_DISPLAY_CUSTOMER_INFO']);

        $this->context->smarty->assign(
            array(
                'link' => $this->context->link,
                'MODULE_DIR' => _MODULE_DIR_,
            )
        );
    }

    public function imageTypes($setDefault)
    {
        $types = ImageType::getImagesTypes('products');

        if(!$types)
            return $setDefault? array(false, false) : array();

        $result = array();

        if ($setDefault)
            $default = array();

        foreach ($types as $image_type) {
            $result[] = array(
                'id_option' => ($imgType = $this->imageType($image_type['name'])),
                'name' => Tools::ucfirst($imgType),
            );

            if(isset($default) &&
                (trim($imgType) == 'home' ||
                trim($imgType) == 'large' ||
                trim($imgType) == 'medium')
            ) {
                $default[$imgType] = $imgType;
            }
        }

        if (isset($default) && !$default && isset($result[0]) && ($item = $result[0])) {
            $default[$item['id_option']] = trim($item['id_option']);
            return array($result, $default);
        }

        if(!$result)
            return isset($default)? array(false, false) : array();

        return isset($default)? array($result, isset($default['home'])? $default['home'] :
            (isset($default['large'])? $default['large'] : $default['medium'])) : $result;
    }

    public function imageType($name, $ucFirst = false)
    {
        $name =  str_replace('_default', '', $name);

        if ($ucFirst)
            $name = Tools::ucfirst($name);

        return $name;
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');
        
        $config = new RbMenuConfig();
        $config->installConfigs();
        self::clearAllCache();
        self::clearUploadedImages();
        $this->initMenu();

        $class = 'Admin'.Tools::ucfirst($this->name).Tools::ucfirst('setting');
        $id_parent = Tab::getIdFromClassName('IMPROVE');
        $tab1 = new Tab();
        $tab1->class_name = $class;
        $tab1->module = $this->name;
        $tab1->id_parent = $id_parent;
        $langs = Language::getLanguages(false);

        foreach ($langs as $l) {
            $tab1->name[$l['id_lang']] = $this->l('Rb Theme Menu');
        }

        $tab1->add(true, false);

        Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'tab`
            SET `icon` = "menu"
            WHERE `id_tab` = "'.(int)$tab1->id.'"'
        );

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayRbMenu') &&
            $this->registerHook('displayRbItemMenu') &&
            $this->registerHook('displayRbItemColumn') &&
            $this->registerHook('displayRbItemBlock') &&
            $this->registerHook('displayRbItemTab') &&
            $this->registerHook('displayRbProductList') &&
            $this->registerHook('displayRbProductListItem') &&
            $this->registerHook('displayBlock') &&
            $this->registerHook('ActionAdminControllerSetMedia');
    }

    public function uninstall()
    {
        self::clearAllCache();
        self::clearUploadedImages();
        $config = new RbMenuConfig();
        $config->unInstallConfigs();

        $tab_class = 'Admin'.Tools::ucfirst($this->name).
        Tools::ucfirst('setting');
        $id_tab = Tab::getIdFromClassName($tab_class);

        if ($id_tab != 0) {
            $tab = new Tab($id_tab);
            $tab->delete();
            return true;
        }

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    public function initMenu()
    {
        $languages = Language::getLanguages(false);
        $menu = new RbMegaMenu();
        $menu->enabled_vertical = 0;
        $menu->menu_ver_text_color = '#ffffff';
        $menu->menu_ver_background_color = '#666666';
        $menu->menu_ver_alway_show = 0;
        $menu->menu_ver_hidden_border = 0;
        $menu->menu_item_width = '230px';
        $menu->tab_item_width = '230px';
        $menu->link_type = 'HOME';

        if ($languages) {
            $val = array();

            foreach ($languages as $lang)
                $val[$lang['id_lang']] = $this->l('Home');

            $menu->title = $val;
        }

        $menu->menu_icon = 'fa-home';
        $menu->sub_menu_type = 'FULL';
        $menu->display_tabs_in_full_width = 1;
        $menu->sub_menu_max_width = '100%';
        $menu->bubble_text_color = '#ffffff';
        $menu->bubble_background_color = '#FC4444';
        $menu->position_background = 'center';
        $menu->menu_open_new_tab = 0;
        $menu->enabled = 1;
        $shops = Shop::getShops(false);
        $res = $menu->validateFields(false);

        if (count($shops) > 1) {
            foreach ($shops as $shop) {
                if (!empty($shop['id_shop']))
                    $res &= $menu->add(true, false, (int)$shop['id_shop']);
            }
        } else
            $res &= $menu->add();

        return $res;
    }

    public function getContent()
    {
        if (!$this->active){
            $this->_html .= $this->displayWarning(
                $this->l('You have to enable Mega menu PRO module to configure its features')
            );
        }

       $this->proccessPost();
       $this->requestForm();
       $this->context->controller->addJqueryUI('ui.sortable');
       $this->_html .= $this->renderForm();

       return $this->_html;
    }

    public function renderForm()
    {
        $menu = new RbMegaMenu();
        $tab = new RbMenuTab();
        $column = new RbMenuColumn();
        $block = new RbMenuBlock();
        $config = new RbMenuConfig();

        $this->smarty->assign(array(
            'menuForm' => $menu->renderForm(),
            'columnForm' => $column->renderForm(),
            'tabForm' => $tab->renderForm(),
            'blockForm' => $block->renderForm(),
            'configForm' => $config->renderForm(),
            'menus' => $this->getMenus(false),
            'rbBaseAdminUrl' => $this->baseAdminUrl(),
            'layoutDirection' => $this->layoutDirection(),
            'multiLayout' => $this->multiLayout,
            'rb_img_dir' => $this->_path.'views/img/',
            'rb_backend_layout' => $this->context->language->is_rtl ? 'rtl' : 'ltr',
            'iconForm' => $this->display(__FILE__, 'admin-icon.tpl'),
        ));

        return $this->display(__FILE__,'admin-form.tpl');
    }

    public function baseAdminUrl()
    {
        return $this->context->link->getAdminLink('AdminModules', true).'&configure='.$this->name;
    }

    public function getColumnSizes()
    {
        $sizes = array();

        for ($i = 1; $i<=12; $i++) {
            $sizes[] = array(
                'id_option' => $i,
                'name' => $i != 12 ? $i.'/12' : $this->l('12/12 (Full)'),
            );
        }

        return $sizes;
    }

    public function getMenus($activeOnly = true,$id_lang = false,$id_menu = false)
    {
        $menus = Db::getInstance()->executeS(
            "SELECT m.*,ml.`title`,ml.`link`,ml.`bubble_text`
            FROM "._DB_PREFIX_."rbthememenu_menu m
            INNER JOIN "._DB_PREFIX_."rbthememenu_menu_shop ms ON
            (m.id_menu =ms.id_menu AND ms.id_shop='".(int)$this->context->shop->id."')            
            LEFT JOIN "._DB_PREFIX_."rbthememenu_menu_lang ml
            ON m.id_menu=ml.id_menu AND ml.id_lang=".((int)$id_lang ? (int)$id_lang : (int)$this->context->language->id)."
            WHERE 1 ".($activeOnly ? " AND m.enabled=1" : "").($id_menu ? " AND m.id_menu=".(int)$id_menu : "")." 
            GROUP BY m.id_menu
            ORDER BY m.sort_order asc,ml.title asc"
        );

        if ($menus)
            foreach($menus as &$menu) {
                $menu['columns'] = $this->getColumns($menu['id_menu']);

                if ($menu['enabled_vertical']) {
                    $menu['tabs'] = $this->getTabs($menu['id_menu']);
                }

                $menu['menu_link'] = $this->getMenuLink($menu);

                if ($menu['menu_img_link'])
                    $menu['menu_img_link'] = $this->context->link->getMediaLink(
                        _MODULE_DIR_.$this->name.'/views/img/upload/'.$menu['menu_img_link']
                    );
                if ($menu['background_image'])
                    $menu['background_image'] = $this->context->link->getMediaLink(
                        _MODULE_DIR_.$this->name.'/views/img/upload/'.$menu['background_image']
                    );
                if ($this->context->language->is_rtl) {
                    $menu['position_background'] = str_replace(array('right'),array('_right'),$menu['position_background']);
                    $menu['position_background'] = str_replace(array('left'),array('_left'),$menu['position_background']);
                    $menu['position_background'] = str_replace(array('_left'),array('right'),$menu['position_background']);
                    $menu['position_background'] = str_replace(array('_right'),array('left'),$menu['position_background']);
                }
            }

        return $id_menu && $menus ? $menus[0] : $menus;
    }

    public function getTabs($id_menu = false, $id_tab = false, $id_lang = false)
    {
        $tabs = Db::getInstance()->executeS(
            "SELECT *
            FROM "._DB_PREFIX_."rbthememenu_tab t
            LEFT JOIN "._DB_PREFIX_."rbthememenu_tab_lang tl
            ON (t.id_tab=tl.id_tab AND tl.id_lang=".($id_lang ? (int)$id_lang :(int)$this->context->language->id).")
            WHERE 1 ".($id_menu ? " AND id_menu=".(int)$id_menu : "").($id_tab ? " AND t.id_tab=".(int)$id_tab : "")."
            ORDER BY sort_order asc"
        );

        if ($tabs)
            foreach ($tabs as &$tab) {
                $tab['columns'] = $this->getColumnsByTab($tab['id_tab'],false,$id_lang);

                if ($tab['tab_img_link'])
                    $tab['tab_img_link'] = $this->context->link->getMediaLink(
                        _MODULE_DIR_.$this->name.'/views/img/upload/'.$tab['tab_img_link']
                    );

                if ($tab['background_image'])
                    $tab['background_image'] = $this->context->link->getMediaLink(
                        _MODULE_DIR_.$this->name.'/views/img/upload/'.$tab['background_image']
                    );

                if ($this->context->language->is_rtl) {
                    $tab['position_background'] = str_replace(array('right'),array('_right'),$tab['position_background']);
                    $tab['position_background'] = str_replace(array('left'),array('_left'),$tab['position_background']);
                    $tab['position_background'] = str_replace(array('_left'),array('right'),$tab['position_background']);
                    $tab['position_background'] = str_replace(array('_right'),array('left'),$tab['position_background']);
                }
            }

        return $id_tab && $tabs ? $tabs[0] : $tabs;
    }

    public function getColumnsByTab($id_tab = false, $id_column = false, $id_lang = false)
    {
        $columns = Db::getInstance()->executeS(
            "SELECT *
            FROM "._DB_PREFIX_."rbthememenu_column
            WHERE 1 ".($id_tab ? " AND id_tab=".(int)$id_tab : "").($id_column ? " AND id_column=".(int)$id_column : "")."
            ORDER BY sort_order asc"
        );

        if ($columns)
            foreach($columns as &$column)
                $column['blocks'] = $this->getBlocks($column['id_column'],false,$id_lang);

        return $id_column && $columns ? $columns[0] : $columns;
    }

    public function getColumns($id_menu = false, $id_column = false, $id_lang = false)
    {
        $columns = Db::getInstance()->executeS(
            "SELECT *
            FROM "._DB_PREFIX_."rbthememenu_column
            WHERE 1 ".($id_menu ? " AND id_menu=".(int)$id_menu : "").($id_column ? " AND id_column=".(int)$id_column : "")."
            ORDER BY sort_order asc"
        );

        if ($columns)
            foreach($columns as &$column)
                $column['blocks'] = $this->getBlocks($column['id_column'],false,$id_lang);

        return $id_column && $columns ? $columns[0] : $columns;
    }

    public function getBlocks($id_column = false,$activeOnly = true, $id_block = false,$id_lang = false)
    {
        $blocks = Db::getInstance()->executeS(
            "SELECT b.*,bl.`title`,bl.`title_link`,bl.`content`,bl.`image_link`
            FROM "._DB_PREFIX_."rbthememenu_block b
            LEFT JOIN "._DB_PREFIX_."rbthememenu_block_lang bl
            ON b.id_block=bl.id_block AND bl.id_lang=".($id_lang ? (int)$id_lang : (int)$this->context->language->id)."
            WHERE 1 ".($activeOnly ? "AND b.enabled=1 " : "").($id_column ? " AND b.id_column=".(int)$id_column." " : "").
            ($id_block ? " AND b.id_block=".(int)$id_block : "")."
            ORDER BY b.sort_order ASC,bl.title ASC"
        );

        return $id_block && $blocks ? $blocks[0] : $blocks;
    }

    public function getBlockById($id_block)
    {
        return Db::getInstance()->getRow(
            "SELECT b.*,bl.`title`,bl.`title_link`,bl.`content`,bl.`image_link`
            FROM "._DB_PREFIX_."rbthememenu_block b
            LEFT JOIN "._DB_PREFIX_."rbthememenu_block_lang bl
            ON b.id_block=bl.id_block AND bl.id_lang=".(int)$this->context->language->id."
            WHERE b.id_block=".(int)$id_block
        );
    }

    public function getManufacturers($orderBy = 'name asc', $addWhere = false)
    {
        $sql = "SELECT m.`id_manufacturer` as value,CONCAT('rb_manufacturer_',m.`id_manufacturer`) as id, name as label
            FROM "._DB_PREFIX_."manufacturer m
            INNER JOIN "._DB_PREFIX_."manufacturer_shop ms ON (m.id_manufacturer=ms.id_manufacturer AND ms.id_shop=".
            (int)$this->context->shop->id.")            
            WHERE active = 1 ".($addWhere ? pSQL($addWhere) : "")."
            ORDER BY ".($orderBy ? $orderBy : 'name asc');

        $sql = str_replace('\n', '', $sql);

        return Db::getInstance()->executeS($sql);
    }

    public function getSuppliers($orderBy = 'name asc', $addWhere = false)
    {
        $sql = "SELECT s.`id_supplier` as value,CONCAT('rb_supplier_',s.`id_supplier`) as id, name as label
            FROM "._DB_PREFIX_."supplier s
            INNER JOIN "._DB_PREFIX_."supplier_shop ss ON (s.id_supplier=ss.id_supplier AND ss.id_shop=".
            (int)$this->context->shop->id.")            
            WHERE active=1 ".($addWhere ? pSQL($addWhere) : "")."
            ORDER BY ".($orderBy ? $orderBy : 'name asc');

        $sql = str_replace('\n', '', $sql);

        return Db::getInstance()->executeS($sql);
    }

    public function getCMSs($orderBy = 'cl.meta_title asc', $addWhere = false)
    {
        $sql = "SELECT c.`id_cms` as value,CONCAT('rb_cms_',c.`id_cms`) as id, cl.`meta_title` as label            
            FROM "._DB_PREFIX_."cms c
            INNER JOIN "._DB_PREFIX_."cms_shop cs ON (c.id_cms= cs.id_cms AND cs.id_shop=".(int)$this->context->shop->id.")
            LEFT JOIN "._DB_PREFIX_."cms_lang cl ON c.id_cms=cl.id_cms AND cl.id_lang=".(int)$this->context->language->id."
            WHERE c.active=1 ".($addWhere ? pSQL($addWhere) : "")."
            GROUP BY c.id_cms ORDER BY ".($orderBy ? $orderBy : 'cl.meta_title asc') . "";

        $sql = str_replace('\n', '', $sql);

        return Db::getInstance()->executeS($sql);
    }

    public function getrbType($name = false)
    {
        $rbType = Configuration::get('RBTHEMEMENU_IMAGE_TYPE');

        if (!$rbType && ($imageTypes =  $this->imageTypes(true)) && isset($imageTypes[1]) && $imageTypes[1])
            $rbType = $imageTypes[1];

        if ($name)
            $nameType = ImageType::typeAlreadyExists($name)? $name : $rbType;

        if (!(isset($nameType)) || !$nameType)
            $nameType = $rbType;

        return ImageType::getFormattedName($nameType);
    }

    public function proccessPost()
    {
        $this->alerts = array();

        if (($query = Tools::getValue('q', false)) && $query) {
            $imageType = $this->getRbType('cart');

            if ($pos = strpos($query, ' (ref:')) {
                $query = Tools::substr($query, 0, $pos);
            }

            $excludeIds = Tools::getValue('excludeIds', false);
            $excludedProductIds = array();

            if ($excludeIds && $excludeIds != 'NaN') {
                $excludeIds = implode(',', array_map(array($this, 'isValidIds'), explode(',', $excludeIds)));

                if ($excludeIds && ($ids = explode(',',$excludeIds))) {
                    foreach($ids as $id) {
                        $id = explode('-',$id);
                        if(isset($id[0]) && isset($id[1]) && !$id[1]) {
                            $excludedProductIds[] = (int)$id[0];
                        }
                    }
                }
            } else {
                $excludeIds = false;
            }

            $excludeVirtuals = (bool)Tools::getValue('excludeVirtuals', true);
            $exclude_packs = (bool)Tools::getValue('exclude_packs', true);

            $imgLeftJoin = ' LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop ON (image_shop.`id_product` = p.`id_product` AND image_shop.id_shop=' . (int)$this->context->shop->id . ') ';

            $sql = 'SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`, image_shop.`id_image` id_image, il.`legend`, p.`cache_default_attribute`
                    FROM `' . _DB_PREFIX_ . 'product` p
                    ' . Shop::addSqlAssociation('product', 'p') . '
                    LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (pl.id_product = p.id_product AND pl.id_lang = ' . (int)$this->context->language->id . Shop::addSqlRestrictionOnLang('pl') . ')
                    '. pSQL($imgLeftJoin) .' 
                    LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int)$this->context->language->id . ')
                    LEFT JOIN `'._DB_PREFIX_.'product_shop` ps ON (p.`id_product` = ps.`id_product`) 
                    WHERE '.($excludedProductIds ? 'p.`id_product` NOT IN('.pSQL(implode(',',$excludedProductIds)).') AND ' : '').' (pl.name LIKE \'%' . pSQL($query) . '%\' OR p.reference LIKE \'%' . pSQL($query) . '%\' OR p.id_product = '.(int)$query.') AND ps.`active` = 1 AND ps.`id_shop` = '.(int)$this->context->shop->id .
                   ($excludeVirtuals ? ' AND NOT EXISTS (SELECT 1 FROM `' . _DB_PREFIX_ . 'product_download` pd WHERE (pd.id_product = p.id_product))' : '') .
                   ($exclude_packs ? ' AND (p.cache_is_pack IS NULL OR p.cache_is_pack = 0)' : '') .
                   ($imgLeftJoin? 'AND image_shop.cover = 1' : '').'  GROUP BY p.id_product';

            if (($items = Db::getInstance()->executeS($sql))) {
                $results = array();

                foreach ($items as $item) {
                    if (Combination::isFeatureActive() && (int)$item['cache_default_attribute']) {
                        $sql = 'SELECT pa.`id_product_attribute`, pa.`reference`, ag.`id_attribute_group`, pai.`id_image`, agl.`name` AS group_name, al.`name` AS attribute_name, NULL as `attribute`, a.`id_attribute`
                                FROM `' . _DB_PREFIX_ . 'product_attribute` pa
                                ' . Shop::addSqlAssociation('product_attribute', 'pa') . '
                                LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
                                LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON a.`id_attribute` = pac.`id_attribute`
                                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
                                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = ' . (int)$this->context->language->id . ')
                                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = ' . (int)$this->context->language->id . ')
                                LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_image` pai ON pai.`id_product_attribute` = pa.`id_product_attribute`
                                WHERE pa.`id_product` = ' . (int)$item['id_product'] . ($excludeIds ? ' AND NOT FIND_IN_SET(CONCAT(pa.`id_product`,"-", IF(pa.`id_product_attribute` IS NULL,0,pa.`id_product_attribute`)), "' . pSQL($excludeIds) . '")' : '') . '
                                GROUP BY pa.`id_product_attribute`, ag.`id_attribute_group`
                                ORDER BY pa.`id_product_attribute`';

                        if (($combinations = Db::getInstance()->executeS($sql))) {
                            foreach ($combinations as $combination) {
                                $results[$combination['id_product_attribute']]['id_product'] = $item['id_product'];
                                $results[$combination['id_product_attribute']]['id_product_attribute'] = $combination['id_product_attribute'];
                                $results[$combination['id_product_attribute']]['name'] = $item['name'];

                                !empty($results[$combination['id_product_attribute']]['attribute']) ? $results[$combination['id_product_attribute']]['attribute'] .= ' ' . $combination['group_name'] . '-' . $combination['attribute_name']
                                    : $results[$combination['id_product_attribute']]['attribute'] = $item['attribute'] . ' ' . $combination['group_name'] . '-' . $combination['attribute_name'];
                                if (!empty($combination['reference'])) {
                                    $results[$combination['id_product_attribute']]['ref'] = $combination['reference'];
                                } else {
                                    $results[$combination['id_product_attribute']]['ref'] = !empty($item['reference']) ? $item['reference'] : '';
                                }

                                if (empty($results[$combination['id_product_attribute']]['image'])) {
                                    $results[$combination['id_product_attribute']]['image'] = str_replace('http://', Tools::getShopProtocol(), $this->context->link->getImageLink($item['link_rewrite'], (!empty($combination['id_image'])? (int)$combination['id_image'] : (int)$item['id_image']),$imageType));
                                }
                            }
                        }
                    } else {
                        $results[] = array(
                            'id_product' => (int)($item['id_product']),
                            'id_product_attribute' => 0,
                            'name' => $item['name'],
                            'attribute' => '',
                            'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                            'image' => str_replace('http://', Tools::getShopProtocol(), $this->context->link->getImageLink($item['link_rewrite'], $item['id_image'], $imageType)),
                        );
                    }
                }

                if ($results) {
                    foreach ($results as &$item)
                        echo trim($item['id_product'] . '|' . (int)($item['id_product_attribute']) . '|' . Tools::ucfirst($item['name']). '|' . $item['attribute'] . '|' . $item['ref'] . '|' . $item['image']) . "\n";
                }
            }

            die;
        }

        if (Tools::getValue('product_type', false) && ($IDs = Tools::getValue('ids', false))) {
            die(Tools::jsonEncode(array(
                'html' => $this->hookDisplayRbProductList(array('ids' => $IDs)),
            )));
        }

        $time = time();

        if (Tools::isSubmit('rb_form_submitted') &&
            ($rbObj = Tools::getValue('rb_object')) &&
            in_array($rbObj,array('RbMegaMenu','RbMenuColumn','RbMenuBlock','RbMenuTab'))
        ) {
            $obj = ($itemId = (int)Tools::getValue('itemId')) && $itemId > 0 ? new $rbObj($itemId) : new $rbObj();
            $this->alerts = $obj->saveData();
            $vals = $obj->getFieldVals();
            $params =array();

            switch ($rbObj) {
                case 'RbMegaMenu':
                    $params['menu']= $this->getMenus(false,false,$obj->id);
                    $vals['html_content']=$this->hookDisplayRbItemMenu($params);
                    break;
                case 'RbMenuTab':
                    $params['tab']=$this->getTabs(false,$obj->id);
                    $vals['html_content']=$this->hookDisplayRbItemTab($params);
                    break;
                case 'RbMenuColumn':
                    $params['column']= $this->getColumns(false,$obj->id);
                    $vals['html_content']=$this->hookDisplayRbItemColumn($params);
                    break;
                case 'RbMenuBlock':
                    $params['block']= $this->getBlocks(false,$obj->id);
                    $vals['html_content']=$this->hookDisplayRbItemColumn($params);
                    break;
            }

            if ($obj->id && $rbObj == 'RbMenuBlock')
                $vals['blockHtml'] = $this->hookDisplayBlock(array('block' => $this->getBlockById($obj->id)));

            die(Tools::jsonEncode(array(
                'alert' => $this->displayAlerts($time),
                'itemId' => (int)$obj->id,
                'title' => property_exists($obj,'title') && isset($obj->title[(int)$this->context->language->id]) ? $obj->title[(int)$this->context->language->id] : false,
                'images' => $obj->id && property_exists($obj,'image') && $obj->image ? array(array(
                    'name' => 'image',
                    'url' => $this->_path.'views/img/upload/'.$obj->image,
                )) : false,
                'img_dir' => $this->_path.'views/img/upload/',
                'menu_icon' => $obj->id && property_exists($obj,'menu_icon') && $obj->menu_icon ? $obj->menu_icon:'',
                'itemKey' => 'id_'.$obj->fields['form']['name'],
                'time' => $time,
                'id_menu' => ($id_menu = (int)Tools::getValue('id_menu')) ? $id_menu : false,
                'rb_object' => $rbObj,
                'vals' => $vals,
                'success' => isset($this->alerts['success']) && $this->alerts['success'],
            )));
        }

        if ((Tools::getValue('deleteimage')) &&
            ($rbObj = Tools::getValue('rb_object')) &&
            in_array($rbObj,array('RbMegaMenu','RbMenuColumn','RbMenuBlock','RbMenuTab'))
        ) {
            if (($itemId = (int)Tools::getValue('itemId')) && $itemId > 0) {
                $obj = new $rbObj($itemId);
                $this->alerts = $obj->clearImage(Tools::getValue('deleteimage'));

                die(Tools::jsonEncode(array(
                    'alert' => $this->displayAlerts($time),
                    'itemId' => (int)$obj->id,
                    'itemKey' => 'image',
                    'time' => $time,
                    'success' => isset($this->alerts['success']) && $this->alerts['success'],
                )));
            } else
            die(Tools::jsonEncode(array(
                'alert' => true,
                'itemId' => 0,
                'itemKey' => 'image',
                'time' => true,
                'success' => true,
            )));
        }  elseif ((Tools::getValue('deleteimage')) && $rbObj=='RbMenuConfig') {
            if (file_exists($this->_path.'views/img/upload'.Configuration::get(Tools::getValue('deleteimage'))))
                @unlink($this->_path.'views/img/upload'.Configuration::get(Tools::getValue('deleteimage')));

            Configuration::updateValue(Tools::getValue('deleteimage'),'');

            $this->alerts= array(
            'errors' => false,
            'success' => array
                (
                    $this->l('Delete image successful')
                ),
            );

            die(Tools::jsonEncode(array(
                'alert' => $this->displayAlerts($time),
                'itemId' => 1 ,
                'itemKey' => 'image',
                'time' => $time,
                'success' => isset($this->alerts['success']) && $this->alerts['success'],
            )));
        }

        if((Tools::getValue('deleteobject')) &&
            ($rbObj = Tools::getValue('rb_object')) &&
            in_array($rbObj,array('RbMegaMenu','RbMenuColumn','RbMenuBlock','RbMenuTab')) &&
            ($itemId = (int)Tools::getValue('itemId')) && $itemId > 0
        ) {
            $obj = new $rbObj($itemId);
            $this->alerts = $obj->deleteObj();

            die(Tools::jsonEncode(array(
                'alert' => $this->displayAlerts($time),
                'time' => $time,
                'itemId' => $itemId,
                'success' => isset($this->alerts['success']) && $this->alerts['success'],
                'successMsg' => isset($this->alerts['success']) && $this->alerts['success'] ? $this->l('Item deleted') : false,
            )));
        }

        if((Tools::getValue('duplicateItem')) &&
            ($rbObj = Tools::getValue('rb_object')) &&
            in_array($rbObj , array('RbMegaMenu','RbMenuColumn','RbMenuBlock','RbMenuTab')) &&
            ($itemId = (int)Tools::getValue('itemId')) && $itemId > 0
        ) {
            $obj = new $rbObj($itemId);

            if ($newObj = $obj->duplicateItem()) {
                switch($rbObj)
                {
                    case 'RbMegaMenu':
                        $menu = $this->getMenus(false,false,$newObj->id);
                        $html = $this->hookDisplayRbItemMenu(array('menu' => $menu,'have_li'=>true));
                        break;
                    case 'RbMenuTab':
                        $tab= $this->getTabs(false,$newObj->id);
                        $html= $this->hookDisplayRbItemTab(array('tab' => $tab,'have_li'=>true));
                        break;
                    case 'RbMenuColumn':
                        $column = $this->getColumns(false,$newObj->id);
                        $html = $this->hookDisplayRbItemColumn(array('column' => $column,'have_li'=>true));
                        break;
                    case 'RbMenuBlock':
                        $block = $this->getBlocks(false,false,$newObj->id);
                        $html = $this->hookDisplayRbItemBlock(array('block' => $block,'have_li'=>true));
                        break;
                    default:
                        break;
                }
            }

            die(Tools::jsonEncode(array(
                'alerts' => $newObj ? array('success' => $this->l('Item duplicated')) :
                array('errors' => $this->l('Can not duplicate item. An unknown problem happened')),
                'time' => $time,
                'itemId' => $itemId,
                'newItemId' => $newObj->id,
                'rb_object' => Tools::getValue('rb_object'),
                'html' => isset($html) ? $html : '',
            )));
        }

        if (Tools::isSubmit('rb_config_submitted')) {
            $this->configExtra();
        }

        if (Tools::isSubmit('updateOrder')) {
            $itemId = (int)Tools::getValue('itemId');
            $objName = 'rb_'.Tools::ucfirst(Tools::strtolower(trim(Tools::getValue('obj'))));
            $parentId = (int)Tools::getValue('parentId') > 0 ? (int)Tools::getValue('parentId') : 0;
            $previousId = (int)Tools::getValue('previousId');
            $parentObj=Tools::getValue('parentObj');
            $result = false;

            if(in_array($objName,array('RbMegaMenu','RbMenuColumn','RbMenuBlock','RbMenuTab')) &&
                $itemId > 0
            ) {
                $obj = new $objName($itemId);
                $result = $obj->updateOrder($previousId,$parentId,$parentObj);
            }

            die(Tools::jsonEncode(array(
                'success' => $result
            )));
        }

        if (Tools::isSubmit('clearMenuCache')) {
            $this->clearAllCache();

            die(Tools::jsonEncode(array(
                'success' => $this->l('Cache cleared'),
            )));
        }

        if (Tools::isSubmit('exportMenu')) {
            $this->generateArchive();
        }

        if (Tools::getValue('importMenu')) {
            $errors = $this->processImport();

            die(Tools::jsonEncode(array(
                'success' => !$errors ? $this->l('Menu was successfully imported. This page will be reloaded in 3 seconds') : false,
                'error' => $errors ? implode('; ',$errors) : false,
            )));
        }

        if (Tools::isSubmit('reset_config')) {
            $configuration = new RbMenuConfig();
            $configuration->installConfigs();

            die(Tools::jsonEncode(array(
                'success' => $this->l('Configuration was successfully reset. This page will be reloaded in 3 seconds'),
            )));
        }
    }

    public function enable($force_all = false)
    {
        return parent::enable($force_all) && $this->configExtra(true);
    }

    public function configExtra($reConfig = false)
    {
        if (!$reConfig) {
            $time = time();
            $config = new RbMenuConfig();
        }

        $RBTHEMEMENU_DISPLAY_CUSTOMER_INFO= Configuration::get('RBTHEMEMENU_DISPLAY_CUSTOMER_INFO');
        $RBTHEMEMENU_DISPLAY_SEARCH = Configuration::get('RBTHEMEMENU_DISPLAY_SEARCH');
        $RBTHEMEMENU_DISPLAY_SHOPPING_CART = Configuration::get('RBTHEMEMENU_DISPLAY_SHOPPING_CART');

        if (!$reConfig && isset($config)) {
            $this->alerts = $config->saveData();
        }

        if ($reConfig ||
            $RBTHEMEMENU_DISPLAY_CUSTOMER_INFO != Configuration::get('RBTHEMEMENU_DISPLAY_CUSTOMER_INFO')
        ) {
            $ps_customersignin = Module::getInstanceByName('ps_customersignin');

            if (Configuration::get('RBTHEMEMENU_DISPLAY_CUSTOMER_INFO') && $ps_customersignin) {
                $id_hook = Hook::getIdByName('displayNav2');
                Configuration::updateValue('RBTHEMEMENU_POSITION_USERINFOR',$ps_customersignin->getPosition($id_hook));
                $ps_customersignin->unregisterHook('displayNav2');
                $ps_customersignin->registerHook('displayCustomerInforTop');
            } elseif ($ps_customersignin) {
                $ps_customersignin->registerHook('displayNav2');
                $id_hook = Hook::getIdByName('displayNav2');

                if($position=(int)Configuration::get('RBTHEMEMENU_POSITION_USERINFOR'))
                    $ps_customersignin->updatePosition($id_hook,false,$position);

                $ps_customersignin->unregisterHook('displayCustomerInforTop');
            }
        }

        if($reConfig || $RBTHEMEMENU_DISPLAY_SEARCH != Configuration::get('RBTHEMEMENU_DISPLAY_SEARCH')) {
            $ps_searchbar= Module::getInstanceByName('ps_searchbar');

            if (Configuration::get('RBTHEMEMENU_DISPLAY_SEARCH')&& $ps_searchbar) {
                $id_hook = Hook::getIdByName('displayTop');
                Configuration::updateValue('RBTHEMEMENU_POSITION_BLOCK_SEARCH',$ps_searchbar->getPosition($id_hook));
                $ps_searchbar->unregisterHook('displayTop');
            } elseif($ps_searchbar) {
                $ps_searchbar->registerHook('displayTop');
                $id_hook = Hook::getIdByName('displayTop');

                if ($position = (int)Configuration::get('RBTHEMEMENU_POSITION_BLOCK_SEARCH'))
                    $ps_searchbar->updatePosition($id_hook, false,$position);
            }
        }

        if ($reConfig ||
            $RBTHEMEMENU_DISPLAY_SHOPPING_CART != Configuration::get('RBTHEMEMENU_DISPLAY_SHOPPING_CART')
        ) {
            $ps_shoppingcart= Module::getInstanceByName('ps_shoppingcart');

            if ($ps_shoppingcart && Configuration::get('RBTHEMEMENU_DISPLAY_SHOPPING_CART')) {
                $id_hook = Hook::getIdByName('displayNav2');
                Configuration::updateValue('RBTHEMEMENU_POSITION_BLOCKCART',$ps_shoppingcart->getPosition($id_hook));
                $ps_shoppingcart->unregisterHook('displayNav2');
                $ps_shoppingcart->registerHook('displayCartTop');
            } elseif($ps_shoppingcart) {
                $ps_shoppingcart->registerHook('displayNav2');
                $id_hook=Hook::getIdByName('displayNav2');
                if($position=Configuration::get('RBTHEMEMENU_POSITION_BLOCKCART'))
                    $ps_shoppingcart->updatePosition($id_hook,false,$position);
                $ps_shoppingcart->unregisterHook('displayCartTop');
            }
        }
        
        if (!$reConfig) {
            die(Tools::jsonEncode(array(
                'alert' => $this->displayAlerts($time),
                'time' => $time,
                'layout_direction' => $this->layoutDirection(),
                'success' => isset($this->alerts['success']) && $this->alerts['success'],
            )));
        }

        else
            return true;
    }

    public function requestForm()
    {
        if (Tools::isSubmit('request_form') &&
            ($rbObj = Tools::getValue('rb_object')) &&
            in_array($rbObj,array('RbMegaMenu','RbMenuColumn','RbMenuBlock','RbMenuTab'))
        ) {
            $obj = ($itemId = (int)Tools::getValue('itemId')) && $itemId > 0 ? new $rbObj($itemId) : new $rbObj();

            die(Tools::jsonEncode(array(
                'form' => $obj->renderForm(),
                'itemId' => $itemId,
            )));
        }
    }

    public function displayAlerts($time)
    {
        $this->smarty->assign(array(
            'alerts' => $this->alerts,
            'time' => $time,
        ));

        return $this->display(__FILE__,'admin-alerts.tpl');
    }

    public function hookDisplayBlock($params)
    {
        if (isset($params['block']) && $params['block']) {
            $this->smarty->assign(array(
                'block' => $this->convertBlockProperties($params['block']),
            ));
            return $this->display(__FILE__,'block.tpl');
        }
    }

    public function convertBlockProperties($block)
    {
        if (isset($block['id_manufacturers']) &&
            $block['id_manufacturers'] &&
            ($ids = $this->strToIds($block['id_manufacturers']))
        ) {
            if ($manufacturers = $this->getManufacturers($block['order_by_manufacturers'],'
                AND m.id_manufacturer IN('.implode(',',$ids).')')
            ) {
                foreach ($manufacturers as &$manufacturer) {
                    if ((int)Configuration::get('PS_REWRITING_SETTINGS'))
                        $link_rewrite = Tools::link_rewrite($manufacturer['label']);
                    else
                        $link_rewrite = 0;

                    $manufacturer['link'] = $this->context->link->getManufacturerLink((int)$manufacturer['value'], $link_rewrite);

                    if (file_exists(_PS_MANU_IMG_DIR_.$manufacturer['value'].'.jpg'))
                        $manufacturer['image'] = trim($this->getBaseLink(),'/').'/img/m/'.$manufacturer['value'].'.jpg';
                    else
                        $manufacturer['image'] = $this->_path.'views/img/2.jpg';

                }

                $block['manufacturers'] = $manufacturers;
            }
        }

        if (isset($block['id_suppliers']) &&
            $block['id_suppliers'] &&
            ($ids = $this->strToIds($block['id_suppliers']))
        ) {
            if ($suppliers = $this->getSuppliers($block['order_by_suppliers'],' AND s.id_supplier IN('.implode(',',$ids).')')) {
                foreach ($suppliers as &$supplier) {
                    $supplier['link'] = $this->context->link->getSupplierLink((int)$supplier['value']);

                    if (file_exists(_PS_SUPP_IMG_DIR_.$supplier['value'].'.jpg'))
                        $supplier['image'] =trim($this->getBaseLink(),'/').'/img/su/'.$supplier['value'].'.jpg';
                    else
                        $supplier['image'] = $this->_path.'views/img/2.jpg';
                }

                $block['suppliers'] = $suppliers;
            }
        }

        if(isset($block['id_cmss']) &&
            $block['id_cmss'] &&
            ($ids = $this->strToIds($block['id_cmss']))
        ) {
            if ($cmss = $this->getCMSs(false,' AND c.id_cms IN('.implode(',',$ids).')')) {
                foreach ($cmss as &$c) {
                    $c['link'] = $this->context->link->getCMSLink((int)$c['value']);
                }

                $block['cmss'] = $cmss;
            }
        }

        if(isset($block['id_categories']) &&
            $block['id_categories'] &&
            ($ids = $this->strToIds($block['id_categories']))
        ) {
            $block['categoriesHtml'] = $this->displayCategories(
                $this->getCategoryById(
                    $ids,
                    $block['order_by_category']
                ),
                $block['order_by_category']
            );
        }

        if (isset($block['image']) && $block['image']) {
            $block['image'] = $this->_path.'views/img/upload/'.$block['image'];
        }

        if (isset($block['product_type']) && $block['product_type']) {
            if ($block['product_type'] != 'specific') {
                $block['productsHtml'] = $this->displayProducts(false, $block);
            } elseif (isset($block['id_products'])) {
                $block['productsHtml'] = $this->displayProducts($block['id_products'], $block);
            }
        }

        return $block;
    }

    public function getMenuLink($menu)
    {
        if (isset($menu['link_type'])) {
            switch($menu['link_type'])
            {
                case 'CUSTOM':
                    return $menu['link'];
                case 'CMS':
                    return $this->context->link->getCMSLink((int)$menu['id_cms']);
                case 'CUSTOM':
                    return $menu['link'];
                case 'CATEGORY':
                    return $this->context->link->getCategoryLink((int)$menu['id_category']);
                case 'MNFT':
                    $manufacturer = new Manufacturer((int)$menu['id_manufacturer'], (int)$this->context->language->id);

                    if (!is_null($manufacturer->id)) {
                        if ((int)Configuration::get('PS_REWRITING_SETTINGS'))
                            $manufacturer->link_rewrite = Tools::link_rewrite($manufacturer->name);
                        else
                            $manufacturer->link_rewrite = 0;
                        return $this->context->link->getManufacturerLink((int)$menu['id_manufacturer'], $manufacturer->link_rewrite);
                    }

                    return '#';
                case 'MNSP':
                    $supplier= new Supplier((int)$menu['id_supplier'],(int)$this->context->language->id);

                    if (!is_null($supplier->id)) {
                        return $this->context->link->getSupplierLink($supplier->id);
                    }
                    return '#';
                case 'HOME':
                    return $this->context->link->getPageLink('index', true);
                case 'CONTACT':
                    return $this->context->link->getPageLink('contact', true);
            }
        }

        return '#';
    }

    public function displayProducts($ids, $block)
    {
        $compared_products = array();

        if (Configuration::get('PS_COMPARATOR_MAX_ITEM') && isset($this->context->cookie->id_compare)) {
            $compared_products = CompareProduct::getCompareProducts($this->context->cookie->id_compare);
        }

        $products = $ids? $this->getBlockProducts($ids) : $this->getProductFeatured($block);

        $this->smarty->assign(array(
            'products' => $products,
            'PS_CATALOG_MODE'     => (bool)Configuration::get('PS_CATALOG_MODE') || (Group::isFeatureActive() && !(bool)Group::getCurrent()->show_prices),
            'comparator_max_item' => (int)Configuration::get('PS_COMPARATOR_MAX_ITEM'),
            'compared_products'   => is_array($compared_products) ? $compared_products : array(),
            'protocol_link' => (Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://',
            'link' => new Link(),
            'block'=>$block,
            'imageType' => $this->getRbType('home'),
        ));

        return $this->display(__FILE__,'product-list'.(Tools::getValue('configure')=='rbthememenu' ? '-mini' : ($this->is17 ? '-17':'')).'.tpl');
    }

    public function getProductFeatured($block)
    {
        if (!(isset($block['product_type'])))
            return false;

        $rbProduct = new RbMenuProducts($this->context);
        $perPage = isset($block['product_count']) && ($nb = $block['product_count'])? $nb : 2;
        $rbProduct->setPage(1)
                 ->setPerPage($perPage)
                 ->setOrderBy(null)
                 ->setOrderWay(null);
        $products = array();

        switch ($block['product_type'])
        {
            case 'new':
                $products = $rbProduct->getNewProducts();
                break;
            case 'popular':
                $id_category = ($catID = Configuration::get('HOME_FEATURED_CAT'))? $catID : (int)Category::getRootCategory()->id;
                $products = $rbProduct->setIdCategory($id_category)->getHomeFeatured();
                break;
            case 'special':
                $products = $rbProduct->getSpecialProducts();
                break;
            case 'best':
                $products = $rbProduct->getBestSellers();
                break;
        }

        $products = $this->productsForTemplate($products);

        if ($products)
            foreach ($products as &$product) {
                if(isset($product['specific_prices']) &&
                    $product['specific_prices'] &&
                    $product['specific_prices']['to']!='0000-00-00 00:00:00'
                ) {
                    $product['specific_prices_to'] = $product['specific_prices']['to'];
                }

                if ($this->context->controller->controller_type == 'admin') {
                    $image = ($product['id_product_attribute'] &&
                    ($image = self::getCombinationImageById($product['id_product_attribute'],$this->context->language->id))) ?
                    $image : Product::getCover($product['id_product']);
                    $product['image_id'] = isset($image['id_image']) ? $image['id_image'] : 0;
                }
            }

        return $products;
    }

    public static function getCombinationImageById($id_product_attribute, $id_lang)
    {
        if (!Combination::isFeatureActive() || !$id_product_attribute) {
            return false;
        }

        $result = Db::getInstance()->executeS(
            'SELECT pai.`id_image`, pai.`id_product_attribute`, il.`legend`
            FROM `'._DB_PREFIX_.'product_attribute_image` pai
            LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (il.`id_image` = pai.`id_image`)
            LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_image` = pai.`id_image`)
            WHERE pai.`id_product_attribute` = '.(int)$id_product_attribute.'
            AND il.`id_lang` = '.(int)$id_lang.' ORDER by i.`position` LIMIT 1'
        );

        if (!$result) {
            return false;
        }

        return $result[0];
    }

    public function productsForTemplate($products)
    {
        if (!$products || !is_array($products))
            return array();

        $assembler = new ProductAssembler($this->context);
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new PrestaShop\PrestaShop\Adapter\Image\ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        $products_for_template = array();

        foreach ($products as $item) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($item),
                $this->context->language
            );
        }

        return $products_for_template;
    }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addCSS($this->_path.'views/css/font-awesome.css');
            $this->context->controller->addCSS($this->_path.'views/css/megamenu-admin.css');

            $this->context->controller->addJS($this->_path.'views/js/jquery.countdown.min.js');
            $this->context->controller->addJS($this->_path.'views/js/megamenu-admin.js');
        }
    }

    public function hookDisplayHeader()
    {
        $this->addGoogleFonts();
        $this->context->controller->addCSS($this->_path.'views/css/font-awesome.css');
        $this->addCss17('megamenu','main');
        $this->addCss17('fix17','fix17');
        $this->context->controller->addCSS($this->_path.'views/css/animate.css');
        $this->context->controller->addJS($this->_path.'views/js/megamenu.js');
        $this->context->controller->addJS($this->_path.'views/js/jquery.countdown.min.js');
        $this->context->controller->addJS($this->_path.'views/js/clock.js');
        $config = new RbMenuConfig();

        $this->context->smarty->assign(array(
            'rb_config' => $config->getConfig(),
            'RBTHEMEMENU_ACTIVE_BG_GRAY' => Configuration::get('RBTHEMEMENU_ACTIVE_BG_GRAY')
        ));

        if (Configuration::get('RBTHEMEMENU_CACHE_ENABLED')) {
            if (@file_exists(dirname(__FILE__).'/views/css/cache.css') ||
                !@file_exists(dirname(__FILE__).'/views/css/cache.css') &&
                @file_put_contents(dirname(__FILE__).'/views/css/cache.css',$this->getCSS())
            ) {
                $this->addCss17('cache','cache');
            } else
                return $this->displayDynamicCss();
        } else
            return $this->displayDynamicCss();
    }

    public function addGoogleFonts($frontend = false)
    {
        $font1 = Configuration::get('RBTHEMEMENU_HEADING_FONT');
        $font2 = Configuration::get('RBTHEMEMENU_TEXT_FONT');

        if ($font1!='Times new roman' && $font1!='Arial' && $font1!='inherit') {
            $this->addCss17('https://fonts.googleapis.com/css?family='.urlencode($font1),'rb_gfont_1','remote');
        }

        if ($font2 != $font1 && $font2!='Times new roman' && $font2!='Arial' && $font2!='inherit') {
            $this->addCss17('https://fonts.googleapis.com/css?family='.urlencode($font2),'rb_gfont_2','remote');
        }

        unset($frontend);
    }

    public function addCss17($cssFile,$id = false,$server='local')
    {
        $this->context->controller->registerStylesheet(
            'modules-rbthememenu'.($id ? '_'.$id : ''),
            $server=='remote' ? $cssFile : 'modules/'.$this->name.'/views/css/'.$cssFile.'.css',
            array('media' => 'all','priority' => 150,'server' => $server)
        );
    }

    public function displayDynamicCss()
    {
        $this->smarty->assign(array(
            'rb_css' => $this->getCss(),
        ));

        return $this->display(__FILE__,'header.tpl');
    }

    public function getCSS()
    {
        $colors = array(
            Configuration::get('RBTHEMEMENU_TEXT_FONT_SIZE'),
            Configuration::get('RBTHEMEMENU_COLOR1'),
            Configuration::get('RBTHEMEMENU_COLOR2'),
            Configuration::get('RBTHEMEMENU_COLOR3'),
            Configuration::get('RBTHEMEMENU_COLOR4'),
            Configuration::get('RBTHEMEMENU_COLOR5'),
            Configuration::get('RBTHEMEMENU_COLOR6'),
            Configuration::get('RBTHEMEMENU_COLOR7'),
            Configuration::get('RBTHEMEMENU_COLOR8'),
            Configuration::get('RBTHEMEMENU_COLOR9'),
            Configuration::get('RBTHEMEMENU_COLOR_10'),
            Configuration::get('RBTHEMEMENU_COLOR_11'),
            Configuration::get('RBTHEMEMENU_COLOR_12'),
            Configuration::get('RBTHEMEMENU_COLOR_13'),
            Configuration::get('RBTHEMEMENU_COLOR_14'),
            Configuration::get('RBTHEMEMENU_COLOR_15'),
            Configuration::get('RBTHEMEMENU_COLOR_16'),
            Configuration::get('RBTHEMEMENU_COLOR_17'),
            Configuration::get('RBTHEMEMENU_COLOR_18'),
            Configuration::get('RBTHEMEMENU_COLOR_19'),
            Configuration::get('RBTHEMEMENU_COLOR_20'),
            Configuration::get('RBTHEMEMENU_COLOR_21'),
            Configuration::get('RBTHEMEMENU_COLOR_22'),
            Configuration::get('RBTHEMEMENU_COLOR_23'),
            Configuration::get('RBTHEMEMENU_COLOR_24'),
            Configuration::get('RBTHEMEMENU_COLOR_25'),
            Configuration::get('RBTHEMEMENU_COLOR_26'),
            Configuration::get('RBTHEMEMENU_COLOR_27'),
            Configuration::get('RBTHEMEMENU_COLOR_28'),
            Configuration::get('RBTHEMEMENU_COLOR_29'),
            Configuration::get('RBTHEMEMENU_COLOR_30'),
            Configuration::get('RBTHEMEMENU_COLOR_31'),
            Configuration::get('RBTHEMEMENU_COLOR_32'),
            Configuration::get('RBTHEMEMENU_COLOR_33'),
            Configuration::get('RBTHEMEMENU_COLOR_34'),
            Configuration::get('RBTHEMEMENU_COLOR_35'),
            Configuration::get('RBTHEMEMENU_COLOR_36'),
            Configuration::get('RBTHEMEMENU_COLOR_37'),
            Configuration::get('RBTHEMEMENU_COLOR_38'),
            Configuration::get('RBTHEMEMENU_COLOR_39'),
            Configuration::get('RBTHEMEMENU_COLOR_40'),
            Configuration::get('RBTHEMEMENU_MOBILE_BG_BAR'),
            Configuration::get('RBTHEMEMENU_MOBILE_COLOR_BAR'),
        );

        $colors[] = Configuration::get('RBTHEMEMENU_HEADING_FONT')!='inherit' ? "'".Configuration::get('RBTHEMEMENU_HEADING_FONT')."'" : 'inherit';
        $colors[] = Configuration::get('RBTHEMEMENU_TEXT_FONT') !='inherit' ? "'".Configuration::get('RBTHEMEMENU_TEXT_FONT')."'" : 'inherit';
        $dynamicCSS = @file_exists(dirname(__FILE__).'/views/css/dynamic.css') && @is_readable(dirname(__FILE__).'/views/css/dynamic.css') ? Tools::file_get_contents(dirname(__FILE__).'/views/css/dynamic.css') : '';

        $css =  ($dynamicCSS) ? str_replace(array('text_fontsize','l1_color1','l1_color2','l1_color3','l1_color4','l1_color5','l1_color6','l1_color7','l2_color1','l2_color2','l2_color3','l2_color4','l2_color5','l2_color6','l2_color7', 'l3_color1','l3_color2','l3_color3','l3_color4','l3_color5','l3_color6','l3_color7', 'l4_color1','l4_color2','l4_color3','l4_color4','l4_color5','l4_color6','l4_color7', 'l5_color1','l5_color2','l5_color3','l5_color4','l5_color5','l5_color6','l5_color7','l1_color8','l2_color8','l3_color8','l4_color8','l5_color8','m_bar_bg','m_bar_color','font1','font2'),$colors,$dynamicCSS."\n") : '';

        return $css;
    }

    public function strToIds($str)
    {
        $ids = array();

        if ($str && ($arg = explode(',',$str))) {
            foreach($arg as $id)
                if(!in_array((int)$id, $ids))
                    $ids[] = (int)$id;
        }

        return $ids;
    }

    public function displayCategories($categories, $order_by='cl.name ASC')
    {
        if ($categories) {
            if (Configuration::get('RBTHEMEMENU_INCLUDE_SUB_CATEGORIES')){
                foreach($categories as &$category){
                    $category['sub'] = ($subcategories = $this->getChildCategories((int)$category['id_category'], $order_by)) ? $this->displayCategories($subcategories, $order_by) : false;
                }
            }

            foreach($categories as &$category){
                $category['url_image'] = $this->context->link->getCatImageLink($category['link_rewrite'],(int) $category['id_category'], $this->is17 ? ImageType::getFormattedName('category') : ImageType::getFormatedName('category'));
            }

            $this->smarty->assign(array(
                'categories' => $categories,
                'link' => $this->context->link,
            ));

            return $this->display(__FILE__,'categories-tree.tpl');
        }
    }

    public function getCategoryById($id_category, $order_by='cl.name ASC')
    {
        $sql = "SELECT c.*, cl.`name`,cl.`link_rewrite`
            FROM "._DB_PREFIX_."category c
            INNER JOIN "._DB_PREFIX_."category_shop cs ON (c.id_category=cs.id_category AND cs.id_shop=".(int)$this->context->shop->id.")
            LEFT JOIN "._DB_PREFIX_."category_lang cl ON c.id_category=cl.id_category AND cl.id_lang=".(int)$this->context->language->id."
            WHERE c.active=1 AND  c.id_category ".(is_array($id_category) ? "IN(".implode(',',array_map('intval',$id_category)).")" : "=".(int)$id_category)."
            GROUP BY c.id_category ".($order_by? "ORDER BY ".pSQL($order_by):"");

        $categories = $id_category ? (is_array($id_category) ? Db::getInstance()->executeS($sql) : Db::getInstance()->getRow($sql)) : false;

        return $categories;
    }

    public function getChildCategories($id_parent, $order_by='cl.name ASC')
    {
        return Db::getInstance()->executeS(
            "SELECT c.*, cl.`name`,cl.`link_rewrite`
            FROM "._DB_PREFIX_."category c
            INNER JOIN "._DB_PREFIX_."category_shop cs ON (c.id_category =cs.id_category AND cs.id_shop=".(int)$this->context->shop->id.")
            LEFT JOIN "._DB_PREFIX_."category_lang cl ON c.id_category=cl.id_category AND cl.id_lang=".(int)$this->context->language->id."
            WHERE c.active=1 AND  c.id_parent=".(int)$id_parent." AND c.id_category!=".(int)$id_parent."
            GROUP BY c.id_category ".($order_by? "ORDER BY ".pSQL($order_by):"")
        );
    }

    public static function clearAllCache()
    {
        if(@file_exists(dirname(__FILE__).'/views/css/cache.css'))
            @unlink(dirname(__FILE__).'/views/css/cache.css');

        if ($files = glob(dirname(__FILE__).'/cache/*')) {
            foreach ($files as $file)
                if (@file_exists($file) && strpos($file,'index.php')===false)
                    @unlink($file);
        }
    }

    public static function clearUploadedImages()
    {
        if (@file_exists(dirname(__FILE__).'/views/img/upload/') &&
            ($files = glob(dirname(__FILE__).'/views/img/upload/*'))
        ) {
            foreach ($files as $file)
                if (@file_exists($file) && strpos($file,'index.php')===false)
                    @unlink($file);
        }
    }

    public function multiLayoutExist()
    {
        return Db::getInstance()->getRow(
            "SELECT id_lang FROM "._DB_PREFIX_."lang WHERE is_rtl=0 AND active=1") &&
            Db::getInstance()->getRow("SELECT id_lang FROM "._DB_PREFIX_."lang WHERE is_rtl=1 AND active=1"
        );
    }

    public function translates()
    {
        self::$trans = array(
            'required_text' => $this->l('is required'),
            'data_saved' => $this->l('Saved'),
            'unkown_error' => $this->l('Unknown error happened'),
            'object_empty' => $this->l('Object is empty'),
            'field_not_valid' => $this->l('Field is not valid'),
            'file_too_large' => $this->l('Upload file cannot be larger than 100MB'),
            'file_existed' => $this->l('File name already exists. Try to rename the file and upload again'),
            'can_not_upload' => $this->l('Cannot upload file'),
            'upload_error_occurred' => $this->l('An error occurred during the image upload process.'),
            'image_deleted' => $this->l('Image deleted'),
            'item_deleted' => $this->l('Item deleted'),
            'cannot_delete' => $this->l('Cannot delete the item due to an unknown technical problem'),
            'invalid_text' => $this->l('is invalid'),
            'bubble_text_is_too_long' => $this->l('Bubble text cannot be longer than 50 characters'),
            'bubble_text_color_is_required' => $this->l('Bubble alert text color is required'),
            'bubble_background_color_is_required' => $this->l('Bubble alert background color is required'),

            'custom_link_required_text' => $this->l('Custom link is required'),
            'category_required_text' => $this->l('Please select a category'),
            'manufacturer_required_text' => $this->l('Please select a manufacturer'),
            'supplier_required_text' => $this->l('Please select a supplier'),
            'cms_required_text' => $this->l('CMS page is required'),
            'link_type_not_valid_text' => $this->l('Link type is not valid'),
            'sub_menu_width_invalid' => $this->l('Sub menu width must be between 10 and 100'),

            'content_required_text' => $this->l('HTML/Text is required'),
            'cmss_required_text' => $this->l('CMS pages is required'),
            'categories_required_text' => $this->l('Categories are required'),
            'manufacturers_required_text' => $this->l('Manufacturers are required'),
            'suppliers_required_text' => $this->l('Suppliers are required'),
            'image_required_text' => $this->l('Image is required'),
            'block_type_not_valid_text' => $this->l('Block type is not valid'),
            'products_required_text' => $this->l('Please enter product IDs'),
            'product_count_required_text' => $this->l('Product count required'),
            'products_not_valid_text' => $this->l('Product IDs is not valid. Please enter product IDs separated by a comma (,)'),
            'product_count_not_valid_text' => $this->l('Product count is not valid'),
        );
    }

    public function modulePath()
    {
        return $this->_path;
    }

    public function layoutDirection()
    {
        if (Configuration::get('RBTHEMEMENU_DIR')=='auto')
            return $this->context->language->is_rtl ? 'ets-dir-rtl' : 'ets-dir-ltr';
        else
           return  'ets-dir-'.(Configuration::get('RBTHEMEMENU_DIR') == 'rtl' ? 'rtl' : 'ltr');
    }

    public function displayMenuFrontend()
    {
        $menuHtml = false;

        if (Configuration::get('RBTHEMEMENU_CACHE_ENABLED')) {
            $cache = new RbMenuCache();

            if (!($menuHtml = $cache->get('menu_'.$this->context->language->iso_code.'_'.$this->context->shop->id))) {
                $menuHtml = $this->displayMegaMenu();
                $cache->set('menu_'.$this->context->language->iso_code.'_'.$this->context->shop->id,$menuHtml);
            }
        } else
            $menuHtml = $this->displayMegaMenu();

        $this->smarty->assign(array(
            'menusHTML' => $menuHtml,
            'rb_layout_direction' => $this->layoutDirection(),
            'rb_multiLayout' => $this->multiLayout,
        ));

        return $this->display(__FILE__,'megamenu.tpl');
    }

    public function hookdisplayRbMenu()
    {
        return $this->displayMenuFrontend();
    }

    public function hookDisplayNavFullWidth()
    {
        if (Configuration::get('RBTHEMEMENU_HOOK_TO')!='customhook')
            return $this->displayMenuFrontend();
    }

    public function hookDisplayMegaMenu()
    {
        if (Configuration::get('RBTHEMEMENU_HOOK_TO')=='customhook')
            return $this->displayMenuFrontend();
    }

    public function displayMegaMenu($id_lang = false)
    {
        $menus= $id_lang ? $this->getMenus(true,$id_lang) : $this->getMenus(true);

        $this->smarty->assign(array(
            'menus' => $menus,
            'rb_img_dir' => $this->_path.'views/img/',
        ));

        if ($menus)
            return $this->display(__FILE__,'menu-html.tpl');

        return;
    }

    public function hookDisplayRbItemMenu($params)
    {
        $this->smarty->assign(array(
            'menu' => isset($params['menu']) ? $params['menu'] : false,
            'have_li' => isset($params['have_li']) ? $params['have_li'] : false,
        ));

        return $this->display(__FILE__,'item-menu.tpl');
    }

    public function hookDisplayRbItemColumn($params)
    {
        $this->smarty->assign(array(
            'column' => isset($params['column']) ? $params['column'] : false,
            'have_li' => isset($params['have_li']) ? $params['have_li'] : false,
        ));

        return $this->display(__FILE__,'item-column.tpl');
    }

    public function hookDisplayRbItemTab($params) {
        $this->smarty->assign(array(
            'tab' => isset($params['tab']) ? $params['tab'] : false,
            'have_li' => isset($params['have_li']) ? $params['have_li'] : false,
        ));
        return $this->display(__FILE__,'item-tab.tpl');
    }

    public function hookDisplayRbItemBlock($params)
    {
        $this->smarty->assign(array(
            'block' => isset($params['block']) ? $params['block'] : false,
            'have_li' => isset($params['have_li']) ? $params['have_li'] : false,
        ));

        return $this->display(__FILE__,'item-block.tpl');
    }

    public function getConfigs($id_lang = false)
    {
        $configs = array();

        foreach (self::$configs['configs'] as $key => $val) {
            $configs[$key] = Tools::strtolower(Configuration::get($key,isset($val['lang']) && $val['lang'] ? ($id_lang ? $id_lang : (int)$this->context->language->id) : null));
        }

        return $configs;
    }

    protected function getDisabledCategories()
    {
        return ($result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT GROUP_CONCAT(c.id_category SEPARATOR ",") as `disable_categories`
            FROM `' . _DB_PREFIX_ . 'category` c
            ' . Shop::addSqlAssociation('category', 'c') . '
            WHERE c.active = 0')) ? explode(',', $result) : false;
    }

    public function hookDisplayCustomMenu()
    {
        $this->context->smarty->assign(
            array(
                'RBTHEMEMENU_DISPLAY_SHOPPING_CART' => (int)Configuration::get('RBTHEMEMENU_DISPLAY_SHOPPING_CART'),
                'RBTHEMEMENU_DISPLAY_SEARCH'=> (int)Configuration::get('RBTHEMEMENU_DISPLAY_SEARCH'),
                'RBTHEMEMENU_DISPLAY_CUSTOMER_INFO'=> (int)Configuration::get('RBTHEMEMENU_DISPLAY_CUSTOMER_INFO'),
                'RBTHEMEMENU_CUSTOM_HTML_TEXT'=> Configuration::get('RBTHEMEMENU_CUSTOM_HTML_TEXT',$this->context->language->id),
                'RBTHEMEMENU_SEARCH_DISPLAY_DEFAULT' =>(int)Configuration::get('RBTHEMEMENU_SEARCH_DISPLAY_DEFAULT'),
            )
        );

        return $this->display(__FILE__,'custom_menu.tpl');
    }

    public function getBaseLink()
    {
        return (Configuration::get('PS_SSL_ENABLED_EVERYWHERE') ?'https://':'http://').
            $this->context->shop->domain.$this->context->shop->getBaseURI();
    }

    public function hookDisplayRbProductList($params)
    {
        if (isset($params['ids']) && ($productIds = $params['ids'])) {
            $IDs = explode(',', $productIds);
            $products = array();

            foreach ($IDs as $ID) {
                if ($ID &&($tmpIDs = explode('-', $ID))) {
                    $products[] = array(
                        'id_product' => $tmpIDs[0],
                        'id_product_attribute' => !empty($tmpIDs[1])? $tmpIDs[1] : 0,
                    );
                }
            }

            if ($products) {
                $products = $this->getBlockProducts($products);
            }

            $this->smarty->assign('products', $products);

            return $this->display(__FILE__, 'block-product-item.tpl');
        }
    }

    public function isValidIds($excludeId)
    {
        if ($excludeId != '') {
            $ids = explode('-', $excludeId);
            if(!isset($ids[1]))
                $ids[1] = 0;
            if (Validate::isInt($ids[0]) && Validate::isInt($ids[1]))
                return (int)$ids[0].'-'.(int)$ids[1];
            return false;
        }

        return false;
    }

    public function getBlockProducts($products)
    {
        if (!$products)
            return false;

        if (!is_array($products)) {
            $IDs = explode(',', $products);
            $products = array();
            foreach ($IDs as $ID) {
                if ($ID &&($tmpIDs = explode('-', $ID))) {
                    $products[] = array(
                        'id_product' => $tmpIDs[0],
                        'id_product_attribute' => !empty($tmpIDs[1])? $tmpIDs[1] : 0,
                    );
                }
            }
        }

        if ( !empty($products)) {
            $context = Context::getContext();
            $id_group = isset($context->customer->id) && $context->customer->id?
            Customer::getDefaultGroupId((int)$context->customer->id) : (int)Group::getCurrent()->id;
            $group = new Group($id_group);
            $useTax = $group->price_display_method? false : true;

            foreach ($products as &$product) {
                $p = new Product($product['id_product'], true, $this->context->language->id, $this->context->shop->id);
                $product['link_rewrite'] = $p->link_rewrite;
                $product['price'] = Tools::displayPrice($p->getPrice($useTax,$product['id_product_attribute'] ? $product['id_product_attribute'] : null));

                if (($oldPrice = $p->getPriceWithoutReduct(!$useTax,$product['id_product_attribute'] ? $product['id_product_attribute'] : null)) &&
                    $oldPrice!=$product['price']
                ) {
                    $product['price_without_reduction'] = Tools::convertPrice($oldPrice);
                }

                if (isset($product['price_without_reduction']) && $product['price_without_reduction'] != $product['price'])
                {
                    $product['specific_prices'] = $p->specificPrice;
                }

                if(isset($product['specific_prices']) && $product['specific_prices'] && $product['specific_prices']['to']!='0000-00-00 00:00:00')
                {
                    $product['specific_prices_to'] = $product['specific_prices']['to'];
                }

                $product['name'] = $p->name;
                $product['description_short'] = $p->description_short;
                $image = ($product['id_product_attribute'] && ($image = self::getCombinationImageById($product['id_product_attribute'],$context->language->id))) ? $image : Product::getCover($product['id_product']);
                $product['link'] = $context->link->getProductLink($product,null,null,null,null,null,$product['id_product_attribute'] ? $product['id_product_attribute'] : 0);
                if ($this->context->controller->controller_type == 'admin') {
                    $product['add_to_cart_url'] = isset($context->customer) ? $context->link->getAddToCartURL((int)$product['id_product'], (int)$product['id_product_attribute']) : '';
                    $imageType = $this->getRbType();
                    $product['image'] = $context->link->getImageLink($p->link_rewrite, isset($image['id_image']) ? $image['id_image'] : 0, $imageType);
                    $product['price_tax_exc'] = Product::getPriceStatic( (int)$product['id_product'], false, (int)$product['id_product_attribute'], (!$useTax ? 2 : 6), null, false, true, $p->minimal_quantity);
                    $product['available_for_order'] = $p->available_for_order;
                    if($product['id_product_attribute'])
                    {
                        $p->id_product_attribute = $product['id_product_attribute'];
                        $product['attributes'] = $p->getAttributeCombinationsById((int)$product['id_product_attribute'],$context->language->id);
                    }
                }

                $product['id_image'] = isset($image['id_image']) ? $image['id_image'] : 0;
                $product['image_id'] = $product['id_image'];
                $product['is_available'] = $p->checkQty(1);
                $product['allow_oosp'] = Product::isAvailableWhenOutOfStock($p->out_of_stock);
                $product['show_price'] = $p->show_price;
                $product['out_of_stock'] = $p->out_of_stock;
                $product['id_category_default'] = $p->id_category_default;
                $product['ean13'] = $p->ean13;
            }

            unset($context);
        }

        if ($products && $this->context->controller->controller_type != 'admin') {
            return $this->productsForTemplate($products, $this->context);
        }

        return $products;
    }
}
