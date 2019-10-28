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

class RbDivider extends RbControl
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
			'section_divider',
			array(
				'label' => $module->l('Divider'),
				'type' => 'section',
			)
		);

		$this->addControl(
			'style',
			array(
				'label' => $module->l('Style'),
				'type' => 'select',
				'section' => 'section_divider',
				'options' => array(
					'solid' => $module->l('Solid'),
					'double' => $module->l('Double'),
					'dotted' => $module->l('Dotted'),
					'dashed' => $module->l('Dashed'),
				),
				'default' => 'solid',
				'selectors' => array(
					'{{WRAPPER}} .rb-divider-separator' => 'border-top-style: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'weight',
			array(
				'label' => $module->l('Weight'),
				'type' => 'slider',
				'section' => 'section_divider',
				'default' => array(
					'size' => 1,
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rb-divider-separator' => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->addControl(
			'color',
			array(
				'label' => $module->l('Color'),
				'type' => 'color',
				'section' => 'section_divider',
				'default' => '',
				'scheme' => array(
					'type' => 'color',
					'value' => 3,
				),
				'selectors' => array(
					'{{WRAPPER}} .rb-divider-separator' => 'border-top-color: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'width',
			array(
				'label' => $module->l('Width'),
				'type' => 'slider',
				'default' => array(
					'size' => 100,
					'unit' => '%',
				),
				'section' => 'section_divider',
				'selectors' => array(
					'{{WRAPPER}} .rb-divider-separator' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->addResponsiveControl(
			'align',
			array(
				'label' => $module->l('Alignment'),
				'type' => 'choose',
				'section' => 'section_divider',
				'options' => array(
					'left'    => array(
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
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}} .rb-divider' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'gap',
			array(
				'label' => $module->l('Gap'),
				'type' => 'slider',
				'default' => array(
					'size' => 15,
				),
				'range' => array(
					'px' => array(
						'min' => 2,
						'max' => 50,
					),
				),
				'section' => 'section_divider',
				'selectors' => array(
					'{{WRAPPER}} .rb-divider' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->addControl(
			'view',
			array(
				'label' => $module->l('View'),
				'type' => 'hidden',
				'default' => 'traditional',
				'section' => 'section_divider',
			)
		);
    }

    public function getDataDivider()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Divider',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'divider'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
		return '<div class="rb-divider"><span class="rb-divider-separator"></span></div>';
	}
}    
