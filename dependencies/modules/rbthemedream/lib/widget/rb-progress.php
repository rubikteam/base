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

class RbProgress extends RbControl
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
			'section_progress',
			array(
				'label' => $module->l('Progress Bar'),
				'type' => 'section',
			)
		);

		$this->addControl(
			'title',
			array(
				'label' => $module->l('Title'),
				'type' => 'text',
				'placeholder' => $module->l('Enter your title'),
				'default' => $module->l('My Skill'),
				'label_block' => true,
				'section' => 'section_progress',
			)
		);

		$this->addControl(
			'progress_type',
			array(
				'label' => $module->l('Type'),
				'type' => 'select',
				'default' => '',
				'section' => 'section_progress',
				'options' => array(
					'' => $module->l('Default'),
					'info' => $module->l('Info'),
					'success' => $module->l('Success'),
					'warning' => $module->l('Warning'),
					'danger' => $module->l('Danger'),
				),
			)
		);

		$this->addControl(
			'percent',
			array(
				'label' => $module->l('Percentage'),
				'type' => 'slider',
				'default' => array(
					'size' => 50,
					'unit' => '%',
				),
				'label_block' => true,
				'section' => 'section_progress',
			)
		);

	    $this->addControl(
	        'display_percentage',
	        array(
	            'label' => $module->l('Display Percentage'),
	            'type' => 'select',
	            'default' => 'show',
	            'section' => 'section_progress',
	            'options' => array(
	                'show' => $module->l('Show'),
	                'hide' => $module->l('Hide'),
	            ),
	        )
	    );

		$this->addControl(
			'inner_text',
			array(
				'label' => $module->l('Inner Text'),
				'type' => 'text',
				'placeholder' => $module->l('Web Designer'),
				'default' => $module->l('Web Designer'),
				'label_block' => true,
				'section' => 'section_progress',
			)
		);

		$this->addControl(
			'view',
			array(
				'label' => $module->l('View'),
				'type' => 'hidden',
				'default' => 'traditional',
				'section' => 'section_progress',
			)
		);

		$this->addControl(
			'section_progress_style',
			array(
				'label' => $module->l('Progress Bar'),
				'type' => 'section',
				'tab' => 'style',
			)
		);

		$this->addControl(
			'bar_color',
			array(
				'label' => $module->l('Bar Color'),
				'type' => 'color',
				'tab' => 'style',
				'scheme' => array(
					'type' => 'color',
					'value' => 1,
				),
				'section' => 'section_progress_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-progress-wrapper .rb-progress-bar' =>
					'background-color: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'bar_bg_color',
			array(
				'label' => $module->l('Bar Background Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_progress_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-progress-wrapper' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'bar_inline_color',
			array(
				'label' => $module->l('Inner Text Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_progress_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-progress-wrapper .rb-progress-inner-text' =>
					'color: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'section_title',
			array(
				'label' => $module->l('Title Style'),
				'type' => 'section',
				'tab' => 'style',
			)
		);

		$this->addControl(
			'title_color',
			array(
				'label' => $module->l('Text Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_title',
				'selectors' => array(
					'{{WRAPPER}} .rb-title' => 'color: {{VALUE}};',
				),
				'scheme' => array(
					'type' => 'color',
					'value' => 1,
				),
			)
		);

		$this->addGroupControl(
			'typography',
			array(
				'name' => 'typography',
				'tab' => 'style',
				'section' => 'section_title',
				'selector' => '{{WRAPPER}} .rb-title',
				'scheme' => 1,
			)
		);
    }

    public function getDataProgress()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Progress Bar',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'progress'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
    	$html = '';

		$this->addRenderAttribute('wrapper', 'class', 'rb-progress-wrapper');

		if (!empty($instance['progress_type'])) {
			$this->addRenderAttribute('wrapper', 'class', 'progress-' . $instance['progress_type']);
		}

		if (!empty( $instance['title'])) {
			$html .= '<span class="rb-title">' . $instance['title'] . '</span>';
		}

		$html .= '<div ' . $this->getRenderAttributeString('wrapper') . ' role="timer">';

		$html .= '<div class="rb-progress-bar" data-max="' . $instance['percent']['size'] . '">';

		if (!empty($instance['inner_text'])) {
			$data_inner = ' data-inner="' . $instance['inner_text'] . '"';
		} else {
			$data_inner = '';
		}

		$html .= '<span class="rb-progress-text">'. $instance['inner_text'] . '</span>';

		if ('hide' !== $instance['display_percentage']) {
			$html .= '<span class="rb-progress-percentage">' . $instance['percent']['size'] . '%</span>';
		}

		$html .= '</div></div>';

		return $html;
    }
}
