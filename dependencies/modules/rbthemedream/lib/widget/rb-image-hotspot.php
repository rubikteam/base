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

class RbImageHotspots extends RbControl
{
	public function __construct()
    {
    	parent::__construct();
    	$this->setControl();
    }

    public function setControl()
    {
    	$module = new Rbthemedream();

    	$this->addControl(
			'section_image',
			array(
				'label' => $module->l('Image'),
				'type' => 'section',
			)
		);

		$this->addControl(
			'image',
			array(
				'label' => $module->l('Choose Image'),
				'type' => 'media',
				'default' => array(
					'url' => _MODULE_DIR_ . 'rbthemedream/views/img/img.jpg',
				),
				'section' => 'section_image',
			)
		);

		$this->addControl(
			'caption',
			array(
				'label' => $module->l('Alt text'),
				'type' => 'text',
				'default' => '',
				'placeholder' => $module->l('Enter your Alt about the image'),
				'title' => $module->l('Input image Alt here'),
				'section' => 'section_image',
			)
		);

		$this->addControl(
			'section_icon',
			array(
				'label' => $module->l('Hotspots'),
				'type' => 'section',
			)
		);

		$this->addControl(
			'icon_list',
			array(
				'label' => '',
				'type' => 'repeater',
				'default' => array(
					array(
						'text' => $module->l('List Item #1'),
						'icon' => 'fa fa-check',
						'type' => 'product',
						'left' => 10,
						'top' => 10,
					),
				),
				'section' => 'section_icon',
				'fields' => array(
					array(
						'name' => 'top',
						'label' => $module->l('Position top'),
						'type' => 'number',
						'min' => 0,
						'step' => 0.25,
						'default' => 10,
						'max' => 100,
						'selectors' => array(
							'{{WRAPPER}} {{CURRENT_ITEM}} .rb-hotspot' => 'top: {{VALUE}}%;',
						),
					),
					array(
						'name' => 'left',
						'label' => $module->l('Position Left'),
						'type' => 'number',
						'min' => 0,
						'step' => 0.25,
						'default' => 10,
						'max' => 100,
						'selectors' => array(
							'{{WRAPPER}} {{CURRENT_ITEM}} .rb-hotspot' => 'left: {{VALUE}}%;',
						),
					),
					array(
						'name' => 'type',
						'label' => $module->l('Type'),
						'type' => 'select',
						'default' => 'custom',
						'options' => array(
							'custom' => $module->l('Custom text'),
							'product' => $module->l('Product'),
						),
					),
					array(
						'name' => 'text',
						'label' => $module->l('Title'),
						'type' => 'text',
						'label_block' => true,
						'placeholder' => $module->l('Hotspot'),
						'default' => $module->l('Hotspot'),
					),
					array(
						'name' => 'icon',
						'label' => $module->l('Icon'),
						'type' => 'icon',
						'label_block' => true,
						'default' => 'fa fa-check',
					),
					array(
						'name' => 'text_content',
						'label' => $module->l('Text'),
						'type' => 'text',
						'label_block' => true,
						'placeholder' => $module->l('Lorem ipsum dolor sit amet'),
						'default' => $module->l('Lorem ipsum dolor sit amet'),
						'condition' => array(
							'type' => 'custom',
						),
					),
					array(
						'name' => 'products_ids',
						'label' => $module->l('Search for product'),
						'placeholder' => $module->l('Product name, id, ref'),
						'single' => true,
						'type' => 'autocomplete_products',
						'label_block' => true,
						'condition' => array(
							'type' => 'product',
						),
					),
					array(
						'name' => 'link',
						'label' => $module->l('Link'),
						'type' => 'url',
						'label_block' => true,
						'placeholder' => 'https://www.youtube.com/watch?v=yikCzp_OB50',
						'condition' => array(
							'type' => 'custom',
						),
					),

				),
				'title_field' => 'text',
			)
		);

		$this->addControl(
			'view',
			array(
				'label' => $module->l('View'),
				'type' => 'hidden',
				'default' => 'traditional',
				'section' => 'section_icon',
			)
		);

		$this->addControl(
			'section_icon_style',
			array(
				'label' => $module->l('Hotspot'),
				'type' => 'section',
				'tab' => 'style',
			)
		);

		$this->addControl(
			'icon_size',
			array(
				'label' => $module->l('Font Size'),
				'type' => 'slider',
				'tab' => 'style',
				'section' => 'section_icon_style',
				'default' => array(
					'size' => 14,
				),
				'range' => array(
					'px' => array(
						'min' => 6,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rb-hotspot' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->addControl(
			'icon_color',
			array(
				'label' => $module->l('Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_icon_style',
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .rb-hotspot, {{WRAPPER}} .rb-hotspot a' => 'color: {{VALUE}};',
				),
				'scheme' => array(
					'type' => 'color',
					'value' => 1,
				),
			)
		);

		$this->addControl(
			'icon_background',
			array(
				'label' => $module->l('Background'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_icon_style',
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .rb-hotspot' => 'background: {{VALUE}};',
				),
				'scheme' => array(
					'type' => 'color',
					'value' => 1,
				),
			)
		);

		$this->addControl(
			'tooltip_content_color',
			array(
				'label' => $module->l('Tooltip color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_icon_style',
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .tooltip-inner' => 'color: {{VALUE}};',
				),
				'scheme' => array(
					'type' => 'color',
					'value' => 1,
				),
			)
		);

		$this->addControl(
			'tooltip_content_background',
			array(
				'label' => $module->l('Tooltip background'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_icon_style',
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .tooltip-inner' => 'background: {{VALUE}};',
				),
				'scheme' => array(
					'type' => 'color',
					'value' => 1,
				),
			)
		);

		$this->addGroupControl(
			'box-shadow',
			array(
				'name' => 'tooltip_box_shadow',
				'section' => 'section_icon_style',
				'tab' => 'style',
				'selector' => '{{WRAPPER}} .tooltip-inner',
			)
		);
    }

    public function getDataImageHotspot()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Hotspot',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'hotspot'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
    	if (empty($instance['image']['url'])) {
            return;
        }

    	$context = Context::getContext();
        $module = new Rbthemedream();

        $image_html = '<div class="rb-hotspot-image' . (!empty($instance['shape']) ? ' rb-image-shape-' . $instance['shape'] : '' ) . '">';
        $image_class_html = !empty($instance['hover_animation']) ? ' class="rb-animation-' . $instance['hover_animation'] . '"' : '';
        
        $image_html .= sprintf(
        	'<img src="%s" alt="%s"%s />',
        	Tools::safeOutput(RbControl::getImage($instance['image']['url'])),
        	Tools::safeOutput($instance['caption']) ,
        	$image_class_html
        );

        $image_html .= '</div>';
        $html = '';

        foreach ($instance['icon_list'] as $item) {
        	$tooltip = '';
			$tooltipText = '';
			$tooltipLink = '#';

			if (isset($item['link']['url'])) {
				$tooltipLink = $item['link']['url'];
			}

			$tooltipLinkTarget = 'target="_blank" rel="noopener noreferrer"';

			if ($item['type'] == 'custom') {
				$tooltipText = $item['text_content'];

				if (isset($item['link']['url'])) {
					$tooltipLink = $item['link']['url'];
				}
				
				$tooltipLinkTarget = isset($item['link']['is_external']) ? ' target="_blank" rel="noopener noreferrer"' : '';
			} else {
				if (isset($item['products_ids'])) {
					$product = $this->getProduct($item['products_ids']);

					if (!empty($product['name'])) {
						$tooltipText = "<div class='row rb-hotspot-product'>
						<div class='thumbnail-container'><img src='".$product['cover']['url']."'
						class='img-fluid' width='".$product['cover']['width']."' height='".$product['cover']['height']."'></div>
						<div class='product-description'><div class='product-title'>".$product['name']."</div>
	    				<span class='product-price-hotspot'>".$product['price']."</span></div></div>";
						$tooltipLink = $product['url'];
					}
				}
			}

			if (isset($instance['id_widget_instance'])) {
				if (!empty($tooltipText)) {
					$tpl = "<div class='rb-element rb-element-".$instance['id_widget_instance']." tooltip' role='tooltip'><div class='tooltip-inner tooltip-inner-hotspot'></div></div>";
					$tooltip = 'data-toggle="tooltip" data-html="true"';
				}
			}

			$html .= '<div class="rb-hotspot" style="top: '.$item['top'].'%; left: '.$item['left'].'%;" '.$tooltip.'>';
			$html .= $tooltipText;

			if (!empty( $tooltipLink)) {
				$html .= '<a href="' . $tooltipLink . '"  ' . $tooltipLinkTarget . '>';
			}

			if ($item['icon']) {
				$html .= '<span class="rb-hotspot-icon"><i class="'.Tools::safeOutput($item['icon']).'"></i></span>';
			}

			$html .= '<span class="rb-hotspot-text">'.$item['text'].'</span>';

			if (!empty($tooltipLink)) {
				$html .= '</a>';
			}

			$html .= '</div>';
        }

        $context->smarty->assign(array(
            'instance' => $instance,
            'image_html' => $image_html,
            'html' => $html,
        ));

        return $module->fetch('module:rbthemedream/views/templates/widget/rb-image-hotspot.tpl');
    }
}
