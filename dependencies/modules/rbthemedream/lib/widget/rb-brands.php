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

class RbBrands extends RbControl
{
	public $editMode = false;

	public function __construct()
    {
    	parent::__construct();

    	if (isset(Context::getContext()->controller->controller_name) &&
    		Context::getContext()->controller->controller_name == 'AdminRbthemedreamLive'
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
        $slidesToShow[12] = 12;

        $slidesToShowSlider = range(1, 12);
        $slidesToShowSlider = array_combine($slidesToShowSlider, $slidesToShowSlider);

        $itemsPerColumn = range(1, 12);
        $itemsPerColumn = array_combine($itemsPerColumn, $itemsPerColumn);

        $brands = array();

        if ($this->editMode) {
            $brands = Manufacturer::getManufacturers();
        }

        $brandsOptions = array();
        $brandsSelect = array();
        $brandsOrder = array();

        $brandsSelect[0] = $module->l('Show all');
        $brandsSelect[1] = $module->l('Manual select');
        $brandsOrder[0] = $module->l('Default');
        $brandsOrder[1] = $module->l('Alphabetical');

        foreach ($brands as $brand) {
            $brandsOptions[$brand['id_manufacturer']] = $brand['name'];
        }

        $this->addPresControl(array(
        	'section_pswidget_options' => array(
                'label' => $module->l('Widget settings'),
                'type' => 'section',
            ),
            'brand_select' => array(
                'label' => $module->l('Selection'),
                'type' => 'select',
                'default' => '0',
                'label_block' => true,
                'section' => 'section_pswidget_options',
                'options' => $brandsSelect,
            ),
            'brand_list' => array(
                'label' => $module->l('Select brands'),
                'type' => 'select_sort',
                'default' => '0',
                'label_block' => true,
                'multiple' => true,
                'section' => 'section_pswidget_options',
                'options' => $brandsOptions,
                'condition' => array(
                    'brand_select' => '1',
                ),
            ),
            'view' => array(
                'label' => $module->l('View'),
                'type' => 'select',
                'default' => 'grid',
                'condition' => array(
                    'view!' => 'default',
                ),
                'section' => 'section_pswidget_options',
                'options' => array(
                    'carousel' => $module->l('Carousel'),
                    'grid' => $module->l('Grid'),
                ),
            ),
            'slides_to_show' => array(
                'responsive' => true,
                'label' => $module->l('Show per line'),
                'type' => 'select',
                'default' => '6',
                'label_block' => true,
                'condition' => array(
                    'view' => 'grid',
                ),
                'section' => 'section_pswidget_options',
                'options' => $slidesToShow,
            ),
            'slides_to_show_s' => array(
                'responsive' => true,
                'label' => $module->l('Show per line'),
                'type' => 'select',
                'default' => '6',
                'label_block' => true,
                'condition' => array(
                    'view' => 'carousel',
                ),
                'section' => 'section_pswidget_options',
                'options' => $slidesToShowSlider,
            ),
            'items_per_column' => array(
                'label' => $module->l('Items per column'),
                'type' => 'select',
                'default' => '1',
                'condition' => array(
                    'view' => 'carousel',
                ),
                'section' => 'section_pswidget_options',
                'options' => $itemsPerColumn,
            ),
            'navigation' => array(
                'label' => $module->l('Navigation'),
                'type' => 'select',
                'default' => 'both',
                'condition' => array(
                    'view' => 'carousel',
                ),
                'section' => 'section_pswidget_options',
                'options' => [
                    'both' => $module->l('Arrows and Dots'),
                    'arrows' => $module->l('Arrows'),
                    'dots' => $module->l('Dots'),
                    'none' => $module->l('None'),
                ],
            ),
            'autoplay' => array(
                'label' => $module->l('Autoplay'),
                'type' => 'select',
                'default' => 'yes',
                'condition' => array(
                    'view' => 'carousel',
                ),
                'section' => 'section_pswidget_options',
                'options' => array(
                    'yes' => $module->l('Yes'),
                    'no' => $module->l('No'),
                ),
            ),
            'infinite' => array(
                'label' => $module->l('Infinite Loop'),
                'type' => 'select',
                'default' => 'yes',
                'condition' => array(
                    'view' => 'carousel',
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
                    'view' => array('carousel'),
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
                    '{{WRAPPER}} .rb-brands-carousel .slick-dots li button:before' => 'color: {{VALUE}};',
                ),
            ),
  		));
    }

    public function getDataBrands()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'List Brands',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('prestashop'),
    		'keywords' => '',
    		'icon' => 'brand'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
        if (Tools::getIsset('controller') &&
            Tools::getValue('controller') == 'index'
        ) {
            $optionsSource = $this->getWidgetValues($instance);
            $selectedBrands = $optionsSource['brand_list'];
            $brandsType = $optionsSource['brand_select'];
            $brands = array();
            $widgetOptions = array();

            if ($brandsType == 0) {
                $allBrands = Manufacturer::getManufacturers();
                foreach ($allBrands as $brand) {
                    $fileExist = file_exists(
                        _PS_MANU_IMG_DIR_ . $brand['id_manufacturer'] . '-' .
                        ImageType::getFormattedName('small') . '.jpg'
                    );

                    if ($fileExist) {
                        $brands[$brand['id_manufacturer']]['name'] = $brand['name'];

                        $brands[$brand['id_manufacturer']]['link'] = Context::getContext()->link->getManufacturerLink(
                            $brand['id_manufacturer'],
                            $brand['link_rewrite']
                        );

                        $brands[$brand['id_manufacturer']]['image'] = Context::getContext()->link->getManufacturerImageLink(
                            $brand['id_manufacturer'],
                            'small_default'
                        );
                    }
                }
            } else {
                if ($selectedBrands) {
                    foreach ($selectedBrands as $brand) {
                        $fileExist = file_exists(
                            _PS_MANU_IMG_DIR_ . $brand . '.jpg'
                        );

                        if ($fileExist == true) {
                            $manufacturer = new Manufacturer($brand, Context::getContext()->language->id);
                            $brands[$brand]['name'] = $manufacturer->name;
                            $brands[$brand]['link'] = Context::getContext()->link->getManufacturerLink($manufacturer);

                            $brands[$brand]['image'] = Context::getContext()->link->getManufacturerImageLink(
                                $brand,
                                'small_default'
                            );
                        }
                    }
                }
            }

            $widgetOptions['brands'] = $brands;
            $widgetOptions['view'] = $optionsSource['view'];

            if ($optionsSource['view'] == 'grid') {
                $optionsSource['slides_to_show'] = $this->calculateGrid($optionsSource['slides_to_show']);
                $optionsSource['slides_to_show_tablet'] = $this->calculateGrid($optionsSource['slides_to_show_tablet']);
                $optionsSource['slides_to_show_mobile'] = $this->calculateGrid($optionsSource['slides_to_show_mobile']);

                $widgetOptions['slidesToShow'] = array(
                    'desktop' => $optionsSource['slides_to_show'],
                    'tablet' => $optionsSource['slides_to_show_tablet'],
                    'mobile' => $optionsSource['slides_to_show_mobile'],
                );

            } else {
                $widgetOptions['arrows_position'] = $optionsSource['arrows_position'];

                $show_dots = (in_array($optionsSource['navigation'], array('dots', 'both')));
                $show_arrows = (in_array($optionsSource['navigation'], array('arrows', 'both')));

                $widgetOptions['options'] = array(
                    'slidesToShow' => abs($optionsSource['slides_to_show_s']),
                    'slidesToScroll' => abs($optionsSource['slides_to_show_s']),
                    'slidesToShowTablet' => abs($optionsSource['slides_to_show_s_tablet']),
                    'slidesToShowMobile' => abs($optionsSource['slides_to_show_s_mobile']),
                    'navigation' => $optionsSource['navigation'],
                    'itemsPerColumn' => $optionsSource['items_per_column'],
                    'autoplay' => ('yes' === $optionsSource['autoplay']),
                    'infinite' =>('yes' ===  $optionsSource['infinite']),
                    'arrows' => $show_arrows,
                    'dots' => $show_dots,
                );
            }

            $context = Context::getContext();
            $module = new Rbthemedream();

            $context->smarty->assign(array(
                'widgetOptions' => $widgetOptions,
            ));

            return $module->fetch('module:rbthemedream/views/templates/widget/rb-brand.tpl');
        } else {
            return;
        }
    }
}
