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

class RbCounter extends RbControl
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
			'section_counter',
			array(
				'label' => $module->l('Counter'),
				'type' => 'section',
			)
		);

		$this->addControl(
			'starting_number',
			array(
				'label' => $module->l('Starting Number'),
				'type' => 'number',
				'min' => 0,
				'default' => 0,
				'section' => 'section_counter',
			)
		);

		$this->addControl(
			'ending_number',
			array(
				'label' => $module->l('Ending Number'),
				'type' => 'number',
				'min' => 1,
				'default' => 100,
				'section' => 'section_counter',
			)
		);

		$this->addControl(
			'prefix',
			array(
				'label' => $module->l('Number Prefix'),
				'type' => 'text',
				'default' => '',
				'placeholder' => 1,
				'section' => 'section_counter',
			)
		);

		$this->addControl(
			'suffix',
			array(
				'label' => $module->l('Number Suffix'),
				'type' => 'text',
				'default' => '',
				'placeholder' => $module->l('Plus'),
				'section' => 'section_counter',
			)
		);

		$this->addControl(
			'duration',
			array(
				'label' => $module->l('Animation Duration'),
				'type' => 'number',
				'default' => 2000,
				'min' => 100,
				'step' => 100,
				'section' => 'section_counter',
			)
		);

		$this->addControl(
			'title',
			array(
				'label' => $module->l('Title'),
				'type' => 'text',
				'label_block' => true,
				'default' => $module->l('Cool Number'),
				'placeholder' => $module->l('Cool Number'),
				'section' => 'section_counter',
			)
		);

		$this->addControl(
			'view',
			array(
				'label' => $module->l('View'),
				'type' => 'hidden',
				'default' => 'traditional',
				'section' => 'section_counter',
			)
		);

		$this->addControl(
			'section_number',
			array(
				'label' => $module->l('Number'),
				'type' => 'section',
				'tab' => 'style',
			)
		);

		$this->addControl(
			'number_color',
			array(
				'label' => $module->l('Text Color'),
				'type' => 'color',
				'scheme' => array(
					'type' => 'color',
					'value' => 1,
				),
				'tab' => 'style',
				'section' => 'section_number',
				'selectors' => [
					'{{WRAPPER}} .rb-counter-number-wrapper' => 'color: {{VALUE}};',
				],
			)
		);

		$this->addGroupControl(
			'typography',
			array(
				'name' => 'typography_number',
				'scheme' => 1,
				'tab' => 'style',
				'section' => 'section_number',
				'selector' => '{{WRAPPER}} .rb-counter-number-wrapper',
			)
		);

		$this->addControl(
			'section_title',
			array(
				'label' => $module->l('Title'),
				'type' => 'section',
				'tab' => 'style',
			)
		);

		$this->addControl(
			'title_color',
			array(
				'label' => $module->l('Text Color'),
				'type' => 'color',
				'scheme' => array(
					'type' => 'color',
					'value' => 2,
				),
				'tab' => 'style',
				'section' => 'section_title',
				'selectors' => array(
					'{{WRAPPER}} .rb-counter-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->addGroupControl(
			'typography',
			array(
				'name' => 'typography_title',
				'scheme' => 2,
				'tab' => 'style',
				'section' => 'section_title',
				'selector' => '{{WRAPPER}} .rb-counter-title',
			)
		);
   	}

   	public function getDataCounter()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Counter',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'counter'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
    	$context = Context::getContext();
        $module = new Rbthemedream();

        $context->smarty->assign(array(
            'instance' => $instance,
        ));

        return $module->fetch('module:rbthemedream/views/templates/widget/rb-counter.tpl');
    }
}
