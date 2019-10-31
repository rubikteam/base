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

class RbProduct extends RbControl
{
	public $editMode = false;

	public function __construct()
    {
    	parent::__construct();
    	$this->context = Context::getContext();

    	if (isset($this->context->controller->controller_name) &&
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

        $itemsPerColumn = range(1, 6);
        $itemsPerColumn = array_combine($itemsPerColumn, $itemsPerColumn);

        $categories = array();
        $brandsOptions = array();

        if ($this->editMode) {
            $categories = $this->generateCategoriesOption(
            	$this->customGetNestedCategories(
            		$this->context->shop->id,
                	null,
                	(int)$this->context->language->id,
                	false
                )
            );

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
            'product_source' => array(
                'label' => $module->l('Products source'),
                'type' => 'select',
                'default' => 'np',
                'label_block' => true,
                'section' => 'section_pswidget_options',
                'options' => $productSourceOptions,
            ),
            'products_ids' => array(
                'label' => $module->l('Search for products'),
                'placeholder' => $module->l( 'Product name, id, ref'),
                'type' => 'autocomplete_products',
                'label_block' => true,
                'condition' => array(
                    'product_source' => array('ms'),
                ),
                'section' => 'section_pswidget_options',
            ),
            'brand_list' => array(
                'label' => $module->l('Select brand'),
                'type' => 'select',
                'default' => 0,
                'label_block' => true,
                'section' => 'section_pswidget_options',
                'condition' => array(
                    'product_source' => array('bb'),
                ),
                'options' => $brandsOptions,
            ),
            'products_limit' => array(
                'label' => $module->l('Limit'),
                'type' => 'number',
                'default' => '10',
                'condition' => array(
                    'product_source!' => array('ms'),
                ),
                'section' => 'section_pswidget_options',
            ),
            'products_col' => array(
                'label' => $module->l('Col'),
                'type' => 'text',
                'default' => 'col-md-3',
                'min' => '1',
                'condition' => array(
                    'product_source!' => array('ms'),
                ),
                'section' => 'section_pswidget_options',
            ),
            'order_by' => array(
                'label' => $module->l('Order by'),
                'type' => 'select',
                'default' => 'position',
                'condition' => array(
                    'product_source!' => array('np', 'bs', 'ms'),
                ),
                'section' => 'section_pswidget_options',
                'options' => array(
                    'position' => $module->l('Position'),
                    'name' => $module->l('Name'),
                    'date_add' => $module->l('Date add'),
                    'price' => $module->l('Price'),
                    'random' => $module->l('Random (works only with categories)'),
                ),
            ),
            'order_way' => array(
                'label' => $module->l('Order way'),
                'type' => 'select',
                'default' => 'asc',
                'section' => 'section_pswidget_options',
                'condition' => array(
                    'product_source!' => array('np', 'bs', 'ms'),
                ),
                'options' => array(
                    'asc' => $module->l('Ascending'),
                    'desc' => $module->l('Descending'),
                ),
            ),
            'view' => array(
                'label' => $module->l('View'),
                'type' => 'select',
                'default' => 'carousel',
                'condition' => array(
                    'view!' => 'default',
                ),
                'section' => 'section_pswidget_options',
                'options' => array(
                    'carousel' => $module->l('Carousel'),
                    'list' => $module->l('List')
                ),
            ),
            'slides_to_show' => array(
                'responsive' => true,
                'label' => $module->l('Show per line'),
                'type' => 'select',
                'default' => '6',
                'label_block' => true,
                'section' => 'section_pswidget_options',
                'options' => $slidesToShow,
                'condition' => array(
                    'view' => array('carousel', 'grid', 'carousel_s', 'grid_s'),
                ),
            ),
            'items_per_column' => array(
                'label' => $module->l('Items per column'),
                'type' => 'select',
                'default' => '1',
                'condition' => array(
                    'view' => array('carousel', 'carousel_s'),
                ),
                'section' => 'section_pswidget_options',
                'options' => $itemsPerColumn,
            ),
            'navigation' => array(
                'label' => $module->l('Navigation'),
                'type' => 'select',
                'default' => 'both',
                'condition' => array(
                    'view' => array('carousel'),
                ),
                'section' => 'section_pswidget_options',
                'options' => array(
                    'both' => $module->l('Arrows and Dots'),
                    'arrows' => $module->l('Arrows'),
                    'dots' => $module->l('Dots'),
                    'none' => $module->l('None'),
                ),
            ),
            'autoplay' => array(
                'label' => $module->l('Autoplay'),
                'type' => 'select',
                'default' => 'yes',
                'condition' => array(
                    'view' => array('carousel', 'carousel_s'),
                ),
                'section' => 'section_pswidget_options',
                'options' => array(
                    'yes' => $module->l('Yes'),
                    'no' => $module->l('No'),
                ),
            ),
            'infinite' =>  array(
                'label' => $module->l('Infinite Loop'),
                'type' => 'select',
                'default' => 'yes',
                'condition' => array(
                    'view' => array('carousel', 'carousel_s'),
                ),
                'section' => 'section_pswidget_options',
                'options' => array(
                    'yes' => $module->l('Yes'),
                    'no' => $module->l('No'),
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
                'condition' => array(
                    'view' => array('carousel', 'carousel_s'),
                    'navigation' => array('both', 'arrows'),
                ),
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
                'condition' => array(
                    'navigation' => array('dots', 'both'),
                ),
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
                'tab' => 'style',
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
                    '{{WRAPPER}} .product-miniature:hover, .product-miniature:hover a:link:not(.nav-link):not(.btn), .product-miniature:hover a:visited:not(.nav-link):not(.btn)' => 'color: {{VALUE}};',
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

    public function getDataProduct()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'List Product',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('prestashop'),
    		'keywords' => '',
    		'icon' => 'product'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
        if (Tools::getIsset('controller') &&
            Tools::getValue('controller') == 'index'
        ) {
            $optionsSource = $this->getWidgetValues($instance);
            $source = $optionsSource['product_source'];
            $products = array();

            if ($source == 'ms') {
                $products = $this->getProductsByIds($optionsSource['products_ids']);
            } else{
                $products = $this->getProducts(
                    $source,
                    $optionsSource['products_limit'],
                    $optionsSource['order_by'],
                    $optionsSource['order_way'],
                    $optionsSource['brand_list']
                );
            }

            $return = array(
                'products' => $products,
                'view' => $optionsSource['view'],
                'products_col' => $optionsSource['products_col'],
            );

            if ($optionsSource['view'] == 'grid' || $optionsSource['view'] == 'grid_s'){
                $optionsSource['slides_to_show'] = $this->calculateGrid($optionsSource['slides_to_show']);
                $optionsSource['slides_to_show_tablet'] = $this->calculateGrid($optionsSource['slides_to_show_tablet']);
                $optionsSource['slides_to_show_mobile'] = $this->calculateGrid($optionsSource['slides_to_show_mobile']);

                $return['slidesToShow'] = array(
                    'desktop' => $optionsSource['slides_to_show'],
                    'tablet' => $optionsSource['slides_to_show_tablet'],
                    'mobile' => $optionsSource['slides_to_show_mobile'],
                );

            } else if ($optionsSource['view'] == 'carousel' || $optionsSource['view'] == 'carousel_s'){
                $return['arrows_position'] = $optionsSource['arrows_position'];
                $show_dots = (in_array($optionsSource['navigation'], array('dots', 'both')));
                $show_arrows = (in_array($optionsSource['navigation'], array('arrows', 'both')));

                $return['options'] = array(
                    'slidesToShow' => abs( $optionsSource['slides_to_show'] ),
                    'slidesToScroll' => abs($optionsSource['slides_to_show']),
                    'slidesToShowMobile' => abs($optionsSource['slides_to_show_mobile']),
                    'itemsPerColumn' => abs( $optionsSource['items_per_column']),
                    'autoplay' => ('yes' === $optionsSource['autoplay']),
                    'infinite' => ('yes' === $optionsSource['infinite']),
                    'arrows' => $show_arrows,
                    'dots' => $show_dots,
                );
            }

            $module = new Rbthemedream();

            $this->context->smarty->assign(array(
                'products' => $return,
            ));

            return $module->fetch('module:rbthemedream/views/templates/widget/rb-product.tpl');
        } else {
            return;
        }
    }
}
