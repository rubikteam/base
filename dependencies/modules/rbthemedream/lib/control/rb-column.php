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

class RbColumn extends RbControl
{
	public function __construct()
    {
    	parent::__construct();
    	$this->setControl();
    	$this->context = Context::getContext();
        $this->module = new Rbthemedream();
    }

    public function setControl()
    {
    	$module = new Rbthemedream();

    	$this->startControlsSection(
            'section_layout',
            array(
                'label' => $module->l('Layout'),
                'tab' => 'layout',
            )
        );

		$this->addControl(
			'rb_class',
			array(
				'label' => $module->l('Class'),
				'type' => 'text',
				'placeholder' => $module->l('Enter Class Name'),
				'section' => 'section_class',
			)
		);

       	$this->endControlsSection();

    	$this->addControl(
    		'section_style',
    		array(
    			'label' => 'Background & Border',
				'tab' => 'style',
				'type' => 'section',
    		)
    	);

    	$this->addGroupControl(
			'background',
			array(
				'name' => 'background',
				'tab' => 'style',
				'section' => 'section_style',
				'types' => array('classic', 'gradient'),
				'selector' => '{{WRAPPER}} > .rb-element-populated',
			)
		);

		$this->addGroupControl(
			'border',
			array(
				'name' => 'border',
				'tab' => 'style',
				'section' => 'section_style',
				'selector' => '{{WRAPPER}} > .rb-element-populated',
			)
		);

		$this->addControl(
			'border_radius',
			array(
				'label' => $module->l('Border Radius'),
				'type' => 'dimensions',
				'size_units' => array('px', '%'),
				'tab' => 'style',
				'section' => 'section_style',
				'selectors' => array(
					'{{WRAPPER}} > .rb-element-populated' =>
					'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->addGroupControl(
			'box-shadow',
			array(
				'name' => 'box_shadow',
				'section' => 'section_style',
				'tab' => 'style',
				'selector' => '{{WRAPPER}} > .rb-element-populated',
			)
		);

		$this->addControl(
			'section_typo',
			array(
				'label' => $module->l('Typography'),
				'tab' => 'style',
				'type' => 'section',
			)
		);

		$this->addControl(
			'heading_color',
			array(
				'label' => $module->l('Heading Color'),
				'type' => 'color',
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .rb-element-populated .rb-heading-title' => 'color: {{VALUE}};',
				),
				'tab' => 'style',
				'section' => 'section_typo',
			)
		);

		$this->addControl(
			'color_text',
			array(
				'label' => $module->l('Text Color'),
				'type' => 'color',
				'section' => 'section_typo',
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} > .rb-element-populated' => 'color: {{VALUE}};',
				),
				'tab' => 'style',
			)
		);

		$this->addControl(
			'color_link',
			array(
				'label' => $module->l('Link Color'),
				'type' => 'color',
				'section' => 'section_typo',
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .rb-element-populated a' => 'color: {{VALUE}};',
				),
				'tab' => 'style',
			)
		);

		$this->addControl(
			'color_link_hover',
			array(
				'label' => $module->l('Link Hover Color'),
				'type' => 'color',
				'section' => 'section_typo',
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .rb-element-populated a:hover' => 'color: {{VALUE}};',
				),
				'tab' => 'style',
			)
		);

		$this->addControl(
			'text_align',
			array(
				'label' => $module->l('Text Align'),
				'type' => 'choose',
				'tab' => 'style',
				'section' => 'section_typo',
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
				),
				'selectors' => array(
					'{{WRAPPER}} > .rb-element-populated' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'section_advanced',
			array(
				'label' => $module->l('Advanced'),
				'type' => 'section',
				'tab' => 'advanced',
			)
		);

		$this->addResponsiveControl(
			'margin',
			array(
				'label' => $module->l('Margin'),
				'type' => 'dimensions',
				'size_units' => array('px', '%'),
				'section' => 'section_advanced',
				'tab' => 'advanced',
				'selectors' => array(
					'{{WRAPPER}} > .rb-element-populated' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->addResponsiveControl(
			'padding',
			array(
				'label' => $module->l('Padding'),
				'type' => 'dimensions',
				'size_units' => array('px', 'em', '%'),
				'section' => 'section_advanced',
				'tab' => 'advanced',
				'selectors' => array(
					'{{WRAPPER}} > .rb-element-populated' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->addControl(
			'animation',
			array(
				'label' => $module->l('Entrance Animation'),
				'type' => 'animation',
				'default' => '',
				'prefix_class' => 'animated ',
				'tab' => 'advanced',
				'label_block' => true,
				'section' => 'section_advanced',
			)
		);

		$this->addControl(
			'animation_duration',
			array(
				'label' => $module->l('Animation Duration'),
				'type' => 'select',
				'default' => '',
				'options' => array(
					'slow' => $module->l('Slow'),
					'' => $module->l('Normal'),
					'fast' => $module->l('Fast'),
				),
				'prefix_class' => 'animated-',
				'tab' => 'advanced',
				'section' => 'section_advanced',
				'condition' => array(
					'animation!' => '',
				),
			)
		);

		$this->addControl(
			'css_classes',
			array(
				'label' => $module->l('CSS Classes'),
				'type' => 'text',
				'section' => 'section_advanced',
				'tab' => 'advanced',
				'default' => '',
				'prefix_class' => '',
				'label_block' => true,
				'title' => $module->l('Add your custom class'),
			)
		);

		$this->addControl(
			'section_responsive',
			array(
				'label' => $module->l('Responsive'),
				'type' => 'section',
				'tab' => 'advanced',
			)
		);

		$responsive_points = array(
			'screen_sm' => array(
				'title' => $module->l('Mobile Width'),
				'class_prefix' => 'rb-sm-',
				'classes' => '',
				'description' => '',
			),
		);

		foreach ($responsive_points as $point_name => $point_data) {
			$this->addControl(
				$point_name,
				array(
					'label' => $point_data['title'],
					'type' => 'select',
					'section' => 'section_responsive',
					'default' => 'default',
					'options' => array(
						'default' => $module->l('Default'),
						'custom' => $module->l('Custom'),
					),
					'tab' => 'advanced',
					'description' => $point_data['description'],
					'classes' => $point_data['classes'],
				)
			);

			$this->addControl(
				$point_name . '_width',
				array(
					'label' => $module->l('Column Width'),
					'type' => 'select',
					'section' => 'section_responsive',
					'options' => array(
						'10' => '10%',
						'11' => '11%',
						'12' => '12%',
						'14' => '14%',
						'16' => '16%',
						'20' => '20%',
						'25' => '25%',
						'30' => '30%',
						'33' => '33%',
						'40' => '40%',
						'50' => '50%',
						'60' => '60%',
						'66' => '66%',
						'70' => '70%',
						'75' => '75%',
						'80' => '80%',
						'83' => '83%',
						'90' => '90%',
						'100' => '100%',
					),
					'default' => '100',
					'tab' => 'advanced',
					'condition' => array(
						$point_name => array('custom'),
					),
					'prefix_class' => $point_data['class_prefix'],
				)
			);
		}
    }

    public function getColumnValues($instance = array())
    {
        $controls = $this->getControls();

        if (!empty($controls)) {
            foreach ($controls as $control) {
                $instance[$control['name']] = $this->getValue($control, $instance);
            }
        }

        return $instance;
    }

    public function beforeRender($instance, $element_id, $element_data = array())
    {
		$column_type = !empty($element_data['isInner']) ? 'inner' : 'top';

		$column_type = !empty($element_data['isInner']) ? 'inner' : 'top';

		$col = '';

		if ($instance['_column_size'] == 100) {
			$col = 'col-md-12';
		}

		if ($instance['_column_size'] == 50) {
			$col = 'col-md-6';
		}

		if ($instance['_column_size'] == 33) {
			$col = 'col-md-4';
		}

		if ($instance['_column_size'] == 66) {
			$col = 'col-md-8';
		}

		if ($instance['_column_size'] == 16) {
			$col = 'col-md-2';
		}
		
		if ($instance['_column_size'] == 25) {
			$col = 'col-md-3';
		}

		$class_default =  array(
			'rb-column',
			'rb-element',
			'rb-element-' . $element_id,
			$col,
			'rb-' . $column_type . '-column',
		);

		if (isset($instance['rb_class']) && $instance['rb_class'] != '') {
			$rb_class = explode(' ', $instance['rb_class']);

			$class_default = array_merge($rb_class,$class_default);
		}

		$this->addRenderAttribute('wrapper', 'class', $class_default);

		foreach ($this->getClassControls() as $control) {
			if (empty($instance[ $control['name']]))
				continue;

			if (! $this->isControlVisible($instance, $control))
				continue;

			$this->addRenderAttribute(
				'wrapper',
				'class',
				$control['prefix_class'] . $instance[ $control['name']]
			);
		}

		if (!empty($instance['animation'])) {
			$this->addRenderAttribute(
				'wrapper',
				'data-animation',
				$instance['animation']
			);
		}

		$this->addRenderAttribute(
			'wrapper',
			'data-element_type',
			'column'
		);

		$attribute_string = $this->getRenderAttributeString('wrapper');

		$this->context->smarty->assign(array(
            'attribute_string' => $attribute_string,
            'element_data' => $element_data,
        ));

        return $this->module->fetch('module:rbthemedream/views/templates/column.tpl');
	}

	public function afterRender()
	{
		return "</div></div></div>";
	}

    public function getDataColumn()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Column',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'columns'
    	);

    	return $data;
    }
}
