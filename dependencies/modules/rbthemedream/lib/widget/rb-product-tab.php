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

require_once(_PS_MODULE_DIR_.'rbthemedream/lib/control/rb-control.php');

class RbProductTab extends RbControl
{
	public $editMode = false;

	public function __construct()
    {
    	parent::__construct();

    	$this->context = Context::getContext();

    	if (isset(Context::getContext()->controller->controller_name) &&
    		$this->context->controller->controller_name == 'AdminRbthemedreamLive'
    	) {
            $this->editMode = true;
        }

    	$this->setControl();
    }

    public function setControl()
    {
    	$module = new Rbthemedream();

    	$slidesToShow = range(1, 6);
        $slidesToShow = array_combine($slidesToShow, $slidesToShow);

        $itemsPerColumn = range(1, 10);
        $itemsPerColumn = array_combine($itemsPerColumn, $itemsPerColumn);

        $categories = array();
        $brandsOptions = array();

        if ($this->editMode) {
            $categories = $this->generateCategoriesOption($this->customGetNestedCategories(
            	$this->context->shop->id,
            	null,
            	(int)$this->context->language->id,
            	false
            ));

            $brands = Manufacturer::getManufacturers();

            foreach ($brands as $brand) {
                $brandsOptions[$brand['id_manufacturer']] = $brand['name'];
            }
        }

        $productSourceOptions['ms'] = $module->l('Manual selection');
        $productSourceOptions['bb'] = $module->l('By brand');
        $productSourceOptions['np'] = $module->l('New products');
        $productSourceOptions['pd'] = $module->l('Price drops');
        $productSourceOptions['bs'] = $module->l('Best sellers');
        $productSourceOptions = array_merge($productSourceOptions, $categories);

        $this->addPresControl(array(
        	'section_pswidget_options' => array(
                'label' => $module->l('Widget settings'),
                'type' => 'section',
            ),
            'tabs' => array(
                'label' => $module->l('Tabs items'),
                'type' => 'repeater',
                'section' => 'section_pswidget_options',
                'default' => array(),
                'fields' => array(
                    array(
                        'name' => 'tab_title',
                        'label' => $module->l('Title'),
                        'type' => 'text',
                        'default' => $module->l('Tab Title'),
                        'placeholder' => $module->l('Tab Title'),
                        'label_block' => true,
                    ),
                    array(
                        'name' =>  'product_source',
                        'label' => $module->l('Products source'),
                        'type' => 'select',
                        'default' => 'np',
                        'label_block' => true,
                        'options' => $productSourceOptions,
                    ),
                    array(   
                    	'name' => 'products_ids',
                        'label' => $module->l('Search for products'),
                        'placeholder' => $module->l('Product name, id, ref'),
                        'type' => 'autocomplete_products',
                        'label_block' => true,
                        'condition' => array(
                            'product_source' => array('ms'),
                        ),
                    ),
                    array(
                        'name' => 'products_limit',
                        'label' => $module->l('Limit'),
                        'type' => 'number',
                        'default' => '10',
                        'min' => '1',
                        'condition' => array(
                            'product_source!' => array('ms'),
                        ),
                    ),
                    array(
                        'name' => 'products_col',
                        'label' => $module->l('Col'),
                        'type' => 'text',
                        'default' => 'col-md-3',
                        'condition' => array(
                            'product_source!' => array('ms'),
                        ),
                    ),
                    array(
                        'name' =>  'brand_list',
                        'label' => $module->l('Select brand'),
                        'type' => 'select',
                        'default' => 0,
                        'label_block' => true,
                        'options' => $brandsOptions,
                        'condition' => array(
                            'product_source' => array('bb'),
                        ),
                    ),
                    array(
                        'name' => 'order_by',
                        'label' => $module->l('Order by'),
                        'type' => 'select',
                        'default' => 'position',
                        'condition' => [
                            'product_source!' => array('np', 'bs', 'ms'),
                        ],
                        'options' => array(
                            'position' => $module->l('Position'),
                            'name' => $module->l('Name'),
                            'date_add' => $module->l('Date add'),
                            'price' => $module->l('Price'),
                            'random' => $module->l('Random (works only with categories)'),
                        ),
                    ),
                    array(
                        'name' => 'order_way',
                        'label' => $module->l('Order way'),
                        'type' => 'select',
                        'default' => 'asc',
                        'condition' => array(
                            'product_source!' => array('np', 'bs', 'ms'),
                        ),
                        'options' => array(
                            'asc' => $module->l('Ascending'),
                            'desc' => $module->l('Descending'),
                        ),
                    ),
                    array(
                        'name' => 'view',
                        'label' => $module->l('View'),
                        'type' => 'select',
                        'default' => 'carousel',
                        'condition' => array(
                            'view!' => 'default',
                        ),
                        'options' => array(
                            'carousel' => $module->l('Carousel'),
                            'list' => $module->l('List')
                        ),
                    ),
                    array(
                        'name' => 'slides_to_show',
                        'label' => $module->l('Show per line (desktop)'),
                        'type' => 'select',
                        'default' => '6',
                        'label_block' => true,
                        'options' => $slidesToShow,
                        'condition' => array(
                            'view' => array('carousel', 'grid'),
                        ),
                    ),
                    array(
                        'name' => 'slides_to_show_tablet',
                        'label' => $module->l('Show per line (tablet)'),
                        'type' => 'select',
                        'default' => '6',
                        'label_block' => true,
                        'options' => $slidesToShow,
                        'condition' => array(
                            'view' => array('carousel', 'grid'),
                        ),
                    ),
                    array(
                        'name' => 'slides_to_show_mobile',
                        'label' => $module->l('Show per line (phone)'),
                        'type' => 'select',
                        'default' => '6',
                        'label_block' => true,
                        'options' => $slidesToShow,
                        'condition' => array(
                            'view' => array('carousel', 'grid'),
                        ),
                    ),
                    array(
                        'name' => 'products_display',
                        'label' => $module->l('Products On Row (992-4,768-3)'),
                        'type' => 'text',
                        'condition' => array(
                            'view' => 'carousel',
                        ),
                        'options' => $itemsPerColumn,
                    ),
                    array(
                    	'name' => 'items_per_column',
                        'label' => $module->l('Items per column'),
                        'type' => 'select',
                        'default' => '1',
                        'condition' => array(
                            'view' => 'carousel',
                        ),
                        'options' => $itemsPerColumn,
                    ),
                    array(
                    	'name' => 'navigation',
                        'label' => $module->l('Navigation'),
                        'type' => 'select',
                        'default' => 'both',
                        'condition' => array(
                            'view' => 'carousel',
                        ),
                        'options' => array(
                            'both' => $module->l('Arrows and Dots'),
                            'arrows' => $module->l('Arrows'),
                            'dots' => $module->l('Dots'),
                            'none' => $module->l('None'),
                        ),
                    ),
                    array(
                    	'name' => 'autoplay',
                        'label' => $module->l('Autoplay'),
                        'type' => 'select',
                        'default' => 'yes',
                        'condition' => array(
                            'view' => 'carousel',
                        ),
                        'options' => array(
                            'yes' => $module->l('Yes'),
                            'no' => $module->l('No'),
                        ),
                    ),
                    array(
                    	'name' => 'infinite',
                        'label' => $module->l('Infinite Loop'),
                        'type' => 'select',
                        'condition' => array(
                            'view' => 'carousel',
                        ),
                        'default' => 'yes',
                        'options' => array(
                            'yes' => $module->l('Yes'),
                            'no' => $module->l('No'),
                        ),
                    ),
                ),
                'title_field' => 'tab_title',
            ),
			'section_style_tabs' => array(
                'label' => $module->l('Tabs'),
                'type' => 'section',
                'tab' => 'style',
            ),
            'tabs_position' => array(
                'label' => $module->l('Tabs position'),
                'type' => 'select',
                'tab' => 'style',
                'default' => 'left',
                'section' => 'section_style_tabs',
                'options' => array(
                    'left' => $module->l('Left'),
                    'center' => $module->l('Center'),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .nav-tabs' => 'justify-content:  {{VALUE}};',
                ),
            ),
            'tab_color' => array(
                'label' => $module->l('Tab color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_tabs',
                'selectors' => array(
                    '{{WRAPPER}} .nav-tabs .nav-link' => 'color: {{VALUE}};',
                ),
            ),
            'tab_border_color' => array(
                'label' => $module->l('Tab border color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_tabs',
                'selectors' => array(
                    '{{WRAPPER}} .nav-tabs .nav-link.active, .nav-tabs .nav-link:hover,
                    .nav-tabs .nav-link:focus' => 'border-color: {{VALUE}};',
                ),
            ),
            'section_style_navigation' => array(
                'label' => $module->l('Navigation'),
                'type' => 'section',
                'tab' => 'style',
            ),
            'arrows_position' => array(
                'label' => $module->l('Arrows position'),
                'type' => 'select',
                'default' => 'middle',
                'tab' => 'style',
                'section' => 'section_style_navigation',
                'options' => array(
                    'middle' => $module->l('Middle'),
                    'above' => $module->l('Above'),
                ),
            ),
            'arrows_position_top' => array(
                'label' => $module->l('Position top'),
                'type' => 'number',
                'default' => '-20',
                'min' => '-100',
                'tab' => 'style',
                'condition' => array(
                    'arrows_position' => array('above'),
                ),
                'section' => 'section_style_navigation',
                'selectors' => array(
                    '{{WRAPPER}} .slick-arrow' => 'top: {{VALUE}}px;',
                ),
            ),
            'arrows_color' => array(
                'label' => $module->l('Arrows Color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_navigation',
                'selectors' => array(
                    '{{WRAPPER}} .slick-arrow' => 'color: {{VALUE}};',
                ),
            ),
            'arrows_bg_color' => array(
                'label' => $module->l('Arrows background'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_navigation',
                'selectors' => array(
                    '{{WRAPPER}} .slick-arrow' => 'background: {{VALUE}};',
                ),
            ),
            'dots_color' => array(
                'label' => $module->l('Dots color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_navigation',
                'selectors' => array(
                    '{{WRAPPER}} .slick-dots li button:before' => 'color: {{VALUE}};',
                ),
            ),
            'section_style_product' => array(
                'label' => $module->l('Product'),
                'type' => 'section',
                'tab' => 'style',
            ),
            'product_bg_color' => array(
                'label' => $module->l('Product box bg color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_product',
                'selectors' => array(
                    '{{WRAPPER}} .product-miniature' => 'background: {{VALUE}};',
                ),
            ),
            'product_text_color' => array(
                'label' => $module->l('Product text color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_product',
                'selectors' => array(
                    '{{WRAPPER}} .product-miniature, .product-miniature a:link:not(.nav-link):not(.btn), .product-miniature a:visited:not(.nav-link):not(.btn)' => 'color: {{VALUE}};',
                ),
            ),
            'product_price_color' => array(
                'label' => $module->l('Product price color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_product',
                'selectors' => array(
                    '{{WRAPPER}} .product-miniature .product-price' => 'color: {{VALUE}};',
                ),
            ),
            'product_stars_color' => array(
                'label' => $module->l('Product stars color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_product',
                'selectors' => array(
                    '{{WRAPPER}} .product-miniature .dd-review-star' => 'color: {{VALUE}};',
                ),
            ),
            'border' => array(
                'group_type_control' => 'border',
                'name' => 'product_border',
                'label' => $module->l('Border'),
                'tab' => 'style',
                'placeholder' => '1px',
                'default' => '1px',
                'section' => 'section_style_product',
                'selector' => '{{WRAPPER}} .product-miniature',
            ),
            'box-shadow' => array(
                'group_type_control' => 'box-shadow',
                'name' => 'product_box_shadow',
                'label' => $module->l('Box shadow'),
                'placeholder' => '1px',
                'default' => '1px',
                'section' => 'section_style_product',
                'selector' => '{{WRAPPER}} .product-miniature',
            ),
            'section_style_product_h' => array(
                'label' => $module->l('Product hover'),
                'type' => 'section',
                'tab' => 'style',
            ),
            'product_bg_color_h' => array(
                'label' => $module->l('Product box bg color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_product_h',
                'selectors' => array(
                    '{{WRAPPER}} .product-miniature:hover' => 'background: {{VALUE}};',
                ),
            ),
            'product_overlay_h' => array(
                'label' => $module->l('Product overlay'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_product_h',
                'selectors' => array(
                    '{{WRAPPER}} .product-miniature-layout-3 .product-description' => 'background: {{VALUE}};',
                ),
            ),
            'product_text_color_h' => array(
                'label' => $module->l('Product text color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_product_h',
                'selectors' => array(
                    '{{WRAPPER}} .product-miniature:hover, .product-miniature:hover
                    a:link:not(.nav-link):not(.btn), .product-miniature:hover
                    a:visited:not(.nav-link):not(.btn)' => 'color: {{VALUE}};',
                ),
            ),
            'product_price_color_h' => array(
                'label' => $module->l('Product price color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_product_h',
                'selectors' => array(
                    '{{WRAPPER}} .product-miniature:hover .product-price' => 'color: {{VALUE}};',
                ),
            ),
            'product_stars_color_h' => array(
                'label' => $module->l('Product stars color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_product_h',
                'selectors' => array(
                    '{{WRAPPER}} .product-miniature:hover .dd-review-star' => 'color: {{VALUE}};',
                ),
            ),
            'border_h' => array(
                'label' => $module->l('Border color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_product_h',
                'selectors' => array(
                    '{{WRAPPER}} .product-miniature:hover' => 'border-color: {{VALUE}};',
                ),
            ),
            'box-shadow_h' => array(
                'group_type_control' => 'box-shadow',
                'name' => 'product_box_shadow_h',
                'label' => $module->l('Box shadow'),
                'tab' => 'style',
                'placeholder' => '1px',
                'default' => '1px',
                'section' => 'section_style_product_h',
                'selector' => '{{WRAPPER}} .product-miniature:hover',
            ),
        ));
    }

    public function getDataProductTab()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'List Product Tab',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('prestashop'),
    		'keywords' => '',
    		'icon' => 'product-tab'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
        if (Tools::getIsset('controller') &&
            Tools::getValue('controller') == 'index'
        ) {
            $optionsSource = $this->getWidgetValues($instance);
            $tabs = array();
            $parsedOptions = array();
            $uid = substr(md5(uniqid(mt_rand(), true)), 0, 8);

            if (isset($optionsSource['tabs'])){
                foreach ($optionsSource['tabs'] as $tab) {
                    $source = $tab['product_source'];

                    if ($source == 'ms') {
                        $products = $this->getProductsByIds($tab['products_ids']);
                    } else{
                        $products = $this->getProducts(
                            $source,
                            $tab['products_limit'],
                            $tab['order_by'],
                            $tab['order_way'],
                            $tab['brand_list']
                        );
                    }

                    $parsedTab = array(
                        'products' => $products,
                        'uid' => $uid,
                        'title' => $tab['tab_title'],
                        'view' => $tab['view'],
                        'products_col' => isset($tab['products_col']) ? $tab['products_col'] : 'col-md-3',
                    );

                    if ($tab['view'] == 'grid') {
                        $tab['slides_to_show'] = $this->calculateGrid($tab['slides_to_show']);
                        $tab['slides_to_show_tablet'] = $this->calculateGrid($tab['slides_to_show_tablet']);
                        $tab['slides_to_show_mobile'] = $this->calculateGrid($tab['slides_to_show_mobile']);

                        $parsedTab['slidesToShow'] = array(
                            'desktop' => $tab['slides_to_show'],
                            'tablet' => $tab['slides_to_show_tablet'],
                            'mobile' => $tab['slides_to_show_mobile'],
                        );

                    } else if ($tab['view'] == 'carousel') {
                        $parsedTab['arrows_position'] = $optionsSource['arrows_position'];
                        $show_dots = (in_array($tab['navigation'], ['dots', 'both' ]));
                        $show_arrows = (in_array($tab['navigation'], ['arrows', 'both']));
                        $config_obj = array();

                        if (isset($tab['products_display']) && $tab['products_display'] != '') {
                            $configs = explode(',', $tab['products_display']);

                            foreach ($configs as $key_cf => $config) {
                                $config = explode('-', $config);

                                $config_obj[] = array(
                                    'breakpoint' => abs($config[0]),
                                    'settings' => array(
                                        'slidesToShow' => abs($config[1]),
                                        'slidesToScroll' => abs($config[1]),
                                    )
                                );
                            }
                        } else {
                            $config_obj = array(
                                array(
                                    'breakpoint' => 992,
                                    'settings' => array(
                                        'slidesToShow' => abs($tab['slides_to_show_tablet']),
                                        'slidesToScroll' => abs($tab['slides_to_show_tablet']),
                                    ),
                                ),
                                array(
                                    'breakpoint' => 576,
                                    'settings' => array(
                                        'slidesToShow' => abs($tab['slides_to_show_mobile']),
                                        'slidesToScroll' => abs($tab['slides_to_show_mobile']),
                                    ),
                                ),
                            );
                        }

                        $parsedTab['options'] = array(
                            'responsive' => $config_obj,
                            'dots' => true,
                            'infinite' => false,
                            'slidesToShow' => abs($tab['slides_to_show']),
                            'slidesToScroll' => abs($tab['slides_to_show']),
                            'rows' => abs($tab['items_per_column']),
                            'autoplay' => ('yes' === $tab['autoplay']),
                            'infinite' => ('yes' === $tab['infinite']),
                            'arrows' => $show_arrows,
                            'dots' => $show_dots,
                        );
                    }

                    $tabs[] = $parsedTab;
                }
            }

            $parsedOptions['tabs'] = $tabs;
            $module = new Rbthemedream();

            $this->context->smarty->assign(array(
                'tabs' => $parsedOptions,
            ));

            return $module->fetch('module:rbthemedream/views/templates/widget/rb-product-tab.tpl');
        } else {
            return;
        }
    }
}
