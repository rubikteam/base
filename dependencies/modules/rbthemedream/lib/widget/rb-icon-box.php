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

class RbIconBox extends RbControl
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
			'section_icon',
			array(
				'label' => $module->l('Icon Box'),
				'type' => 'section',
			)
		);

		$this->addControl(
			'view',
			array(
				'label' => $module->l('View'),
				'type' => 'select',
				'section' => 'section_icon',
				'options' => array(
					'default' => $module->l('Default'),
					'stacked' => $module->l('Stacked'),
					'framed' => $module->l('Framed'),
				),
				'default' => 'default',
				'prefix_class' => 'rb-view-',
			)
		);

		$this->addControl(
			'icon',
			array(
				'label' => $module->l('Choose Icon'),
				'type' => 'icon',
				'default' => 'fa fa-star',
				'section' => 'section_icon',
			)
		);

		$this->addControl(
			'shape',
			array(
				'label' => $module->l('Shape'),
				'type' => 'select',
				'section' => 'section_icon',
				'options' => array(
					'circle' => $module->l('Circle'),
					'square' => $module->l('Square'),
				),
				'default' => 'circle',
				'condition' => array(
					'view!' => 'default',
				),
				'prefix_class' => 'rb-shape-',
			)
		);

		$this->addControl(
			'title_text',
			array(
				'label' => $module->l('Title & Description'),
				'type' => 'text',
				'default' => $module->l('This is the heading'),
				'placeholder' => $module->l('Your Title'),
				'section' => 'section_icon',
				'label_block' => true,
			)
		);

		$this->addControl(
			'description_text',
			array(
				'label' => '',
				'type' => 'wysiwyg',
				'default' => '<p>' . $module->l('I am text block. Click edit button to change this text' ) . '</p>',
				'title' => $module->l('Input icon text here'),
				'section' => 'section_icon',
				'separator' => 'none',
				'show_label' => false,
			)
		);

		$this->addControl(
			'link',
			array(
				'label' => $module->l('Link to'),
				'type' => 'url',
				'placeholder' => $module->l('https://www.youtube.com/watch?v=yikCzp_OB50'),
				'section' => 'section_icon',
				'separator' => 'before',
			)
		);

		$this->addControl(
			'position',
			array(
				'label' => $module->l('Icon Position'),
				'type' => 'choose',
				'default' => 'top',
				'options' => array(
					'left' => array(
						'title' => $module->l('Left'),
						'icon' => 'align-left',
					),
					'top' => array(
						'title' => $module->l('Top'),
						'icon' => 'align-center',
					),
					'right' => array(
						'title' => $module->l('Right'),
						'icon' => 'align-right',
					),
				),
				'prefix_class' => 'rb-position-',
				'section' => 'section_icon',
				'toggle' => false,
			)
		);

		$this->addControl(
			'title_size',
			array(
				'label' => $module->l('Title HTML Tag'),
				'type' => 'select',
				'options' => array(
					'h1' => $module->l('H1'),
					'h2' => $module->l('H2'),
					'h3' => $module->l('H3'),
					'h4' => $module->l('H4'),
					'h5' => $module->l('H5'),
					'h6' => $module->l('H6'),
					'div' => $module->l('div'),
					'span' => $module->l('span'),
					'p' => $module->l('p'),
				),
				'default' => 'h3',
				'section' => 'section_icon',
			)
		);

		$this->addControl(
			'section_style_icon',
			array(
				'type'  => 'section',
				'label' => $module->l('Icon'),
				'tab'   => 'style',
			)
		);

		$this->addControl(
			'primary_color',
			array(
				'label' => $module->l('Primary Color'),
				'type' => 'color',
				'scheme' => array(
					'type' => 'color',
					'value' => 1,
				),
				'tab' => 'style',
				'section' => 'section_style_icon',
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}}.rb-view-stacked .rb-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.rb-view-framed .rb-icon, {{WRAPPER}}.rb-view-default .rb-icon' =>
					'color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'secondary_color',
			array(
				'label' => $module->l('Secondary Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_style_icon',
				'default' => '',
				'condition' => array(
					'view!' => 'default',
				),
				'selectors' => array(
					'{{WRAPPER}}.rb-view-framed .rb-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.rb-view-stacked .rb-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'icon_space',
			array(
				'label' => $module->l('Icon Spacing'),
				'type' => 'slider',
				'default' => array(
					'size' => 15,
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'section' => 'section_style_icon',
				'tab' => 'style',
				'selectors' => array(
					'{{WRAPPER}}.rb-position-right .rb-icon-box-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rb-position-left .rb-icon-box-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rb-position-top .rb-icon-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->addControl(
			'icon_size',
			array(
				'label' => $module->l('Icon Size'),
				'type' => 'slider',
				'range' => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'section' => 'section_style_icon',
				'tab' => 'style',
				'selectors' => array(
					'{{WRAPPER}} .rb-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->addControl(
			'icon_padding',
			array(
				'label' => $module->l('Icon Padding'),
				'type' => 'slider',
				'tab' => 'style',
				'section' => 'section_style_icon',
				'selectors' => array(
					'{{WRAPPER}} .rb-icon' => 'padding: {{SIZE}}{{UNIT}};',
				),
				'default' => array(
					'size' => 1.5,
					'unit' => 'em',
				),
				'range' => array(
					'em' => array(
						'min' => 0,
					),
				),
				'condition' => array(
					'view!' => 'default',
				),
			)
		);

		$this->addControl(
			'rotate',
			array(
				'label' => $module->l('Icon Rotate'),
				'type' => 'slider',
				'default' => array(
					'size' => 0,
					'unit' => 'deg',
				),
				'tab' => 'style',
				'section' => 'section_style_icon',
				'selectors' => array(
					'{{WRAPPER}} .rb-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
				),
			)
		);

		$this->addControl(
			'border_width',
			array(
				'label' => $module->l('Border Width'),
				'type' => 'dimensions',
				'tab' => 'style',
				'section' => 'section_style_icon',
				'selectors' => array(
					'{{WRAPPER}} .rb-icon' =>
					'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'view' => 'framed',
				),
			)
		);

		$this->addControl(
			'border_radius',
			array(
				'label' => $module->l('Border Radius'),
				'type' => 'dimensions',
				'size_units' => array('px', '%'),
				'tab' => 'style',
				'section' => 'section_style_icon',
				'selectors' => array(
					'{{WRAPPER}} .rb-icon' => 'border-radius:
					{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'view!' => 'default',
				),
			)
		);

		$this->addControl(
			'section_hover',
			array(
				'label' => $module->l('Icon Hover'),
				'type' => 'section',
				'tab' => 'style',
			)
		);

		$this->addControl(
			'hover_primary_color',
			array(
				'label' => $module->l('Primary Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_hover',
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}}.rb-view-stacked .rb-icon:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.rb-view-framed .rb-icon:hover,
					{{WRAPPER}}.rb-view-default .rb-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'hover_secondary_color',
			array(
				'label' => $module->l('Secondary Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_hover',
				'default' => '',
				'condition' => array(
					'view!' => 'default',
				),
				'selectors' => array(
					'{{WRAPPER}}.rb-view-framed .rb-icon:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.rb-view-stacked .rb-icon:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'hover_animation',
			array(
				'label' => $module->l('Animation'),
				'type' => 'hover_animation',
				'tab' => 'style',
				'section' => 'section_hover',
			)
		);

		$this->addControl(
			'section_style_content',
			array(
				'type'  => 'section',
				'label' => $module->l('Content'),
				'tab'   => 'style',
			)
		);

		$this->addResponsiveControl(
			'text_align',
			array(
				'label' => $module->l('Alignment'),
				'type' => 'choose',
				'options' => array(
					'left' => array(
						'title' => $module->l('Left'),
						'icon' => 'align-left',
					),
					'center' => array(
						'title' => $module->l('Center'),
						'icon' => 'align-center',
					),
					'right' => array(
						'title' => $module->l('Right'),
						'icon' => 'align-right',
					),
					'justify' => array(
						'title' => $module->l('Justified'),
						'icon' => 'align-justify',
					),
				),
				'section' => 'section_style_content',
				'tab' => 'style',
				'selectors' => array(
					'{{WRAPPER}} .rb-icon-box-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'content_vertical_alignment',
			array(
				'label' => $module->l('Vertical Alignment'),
				'type' => 'select',
				'options' => array(
					'top' => $module->l('Top'),
					'middle' => $module->l('Middle'),
					'bottom' => $module->l('Bottom'),
				),
				'default' => 'top',
				'section' => 'section_style_content',
				'tab' => 'style',
				'prefix_class' => 'rb-vertical-align-',
			)
		);

		$this->addControl(
			'heading_title',
			array(
				'label' => $module->l('Title'),
				'type' => 'heading',
				'section' => 'section_style_content',
				'tab' => 'style',
				'separator' => 'before',
			)
		);

		$this->addResponsiveControl(
			'title_bottom_space',
			array(
				'label' => $module->l('Title Spacing'),
				'type' => 'slider',
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'section' => 'section_style_content',
				'tab' => 'style',
				'selectors' => array(
					'{{WRAPPER}} .rb-icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->addControl(
			'title_color',
			array(
				'label' => $module->l('Title Color'),
				'type' => 'color',
				'tab' => 'style',
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .rb-icon-box-content .rb-icon-box-title' => 'color: {{VALUE}};',
				),
				'section' => 'section_style_content',
				'scheme' => array(
					'type' => 'color',
					'value' => 1,
				),
			)
		);

		$this->addGroupControl(
			'typography',
			array(
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .rb-icon-box-content .rb-icon-box-title',
				'tab' => 'style',
				'section' => 'section_style_content',
				'scheme' => 1,
			)
		);

		$this->addControl(
			'heading_description',
			array(
				'label' => $module->l('Description' ),
				'type' => 'heading',
				'section' => 'section_style_content',
				'tab' => 'style',
				'separator' => 'before',
			)
		);

		$this->addControl(
			'description_color',
			array(
				'label' => $module->l('Description Color'),
				'type' => 'color',
				'tab' => 'style',
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .rb-icon-box-content .rb-icon-box-description' =>
					'color: {{VALUE}};',
				),
				'section' => 'section_style_content',
				'scheme' => array(
					'type' => 'color',
					'value' => 3,
				),
			)
		);

		$this->addGroupControl(
			'typography',
			array(
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .rb-icon-box-content .rb-icon-box-description',
				'tab' => 'style',
				'section' => 'section_style_content',
				'scheme' => 3,
			)
		);
    }

   	public function getDataIconBox()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Icon Box',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'icon-box'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
    	$context = Context::getContext();
        $module = new Rbthemedream();

        $this->addRenderAttribute(
        	'icon',
        	'class',
        	array('rb-icon', 'rb-animation-' . $instance['hover_animation'])
        );

		$icon_tag = 'span';

		if (!empty($instance['link']['url'])) {
			$this->addRenderAttribute( 'link', 'href', $instance['link']['url'] );
			$icon_tag = 'a';

			if (!empty($instance['link']['is_external'])) {
				$this->addRenderAttribute('link', 'target', '_blank');
				$this->addRenderAttribute('link', 'rel', 'noopener noreferrer');
			}
		}

		$this->addRenderAttribute('i', 'class', $instance['icon']);

		$icon_attributes = $this->getRenderAttributeString('icon');
		$link_attributes = $this->getRenderAttributeString('link');
		$rb_i = $this->getRenderAttributeString('i');

        $context->smarty->assign(array(
            'instance' => $instance,
            'icon_attributes' => $icon_attributes,
            'link_attributes' => $link_attributes,
            'rb_i' => $rb_i,
        ));

        return $module->fetch('module:rbthemedream/views/templates/widget/rb-icon-box.tpl');
    }
}
