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

class RbAccordion extends RbControl
{
	public function __construct()
    {
    	parent::__construct();
    	$this->setControl();
    }

    public function setControl()
    {
    	$module = new Rbthemedream();

    	$this->startControlsSection(
			'section_title',
			array(
				'label' => $module->l('Accordion'),
			)
		);

		$this->addControl(
			'tabs',
			array(
				'label' => $module->l( 'Accordion Items'),
				'type' => 'repeater',
				'default' => array(
					array(
						'tab_title' => $module->l('Accordion 1'),
						'tab_content' => $module->l('This is item content. Click edit button to change this text'),
					),
					array(
						'tab_title' => $module->l('Accordion 2'),
						'tab_content' => $module->l('This is item content. Click edit button to change this text'),
					),
				),
				'fields' => array(
					array(
						'name' => 'tab_title',
						'label' => $module->l('Title & Content'),
						'type' => 'text',
						'default' => $module->l('Accordion Title'),
						'label_block' => true,
					),
					array(
						'name' => 'tab_content',
						'label' => $module->l('Content'),
						'type' => 'wysiwyg',
						'default' => $module->l('Accordion Content'),
						'show_label' => false,
					),
				),
				'title_field' => 'tab_title',
			)
		);

		$this->addControl(
			'view',
			array(
				'label' => $module->l('View'),
				'type' => 'hidden',
				'default' => 'traditional',
			)
		);

		$this->endControlsSection();

		$this->startControlsSection(
			'section_title_style',
			array(
				'label' => $module->l('Accordion'),
				'tab' => 'style',
			)
		);

		$this->addControl(
			'active_first',
			array(
				'label' => $module->l('Active first tab'),
				'type' => 'select',
				'default' => 1,
				'options' => array(
					1 => $module->l('Yes'),
					0 => $module->l('No'),
				),
			)
		);

		$this->addControl(
			'icon_align',
			array(
				'label' => $module->l('Icon Alignment'),
				'type' => 'select',
				'default' => 'left',
				'options' => array(
					'left' => $module->l('Left'),
					'right' => $module->l('Right'),
				),
			)
		);

		$this->addControl(
			'border_width',
			array(
				'label' => $module->l('Border Width'),
				'type' => 'slider',
				'default' => array(
					'size' => 1,
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'tab' => 'style',
				'section' => 'section_title_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-accordion .rb-accordion-item' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rb-accordion .rb-accordion-content' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rb-accordion .rb-accordion-wrapper .rb-accordion-title.active > span' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->addControl(
			'border_color',
			array(
				'label' => $module->l('Border Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_title_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-accordion .rb-accordion-item' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rb-accordion .rb-accordion-content' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .rb-accordion .rb-accordion-wrapper
					.rb-accordion-title.active > span' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'title_color',
			array(
				'label' => $module->l('Title Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_title_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-accordion .rb-accordion-title' => 'color: {{VALUE}};',
				),
				'scheme' => array(
					'type' => 'color',
					'value' => 1,
				),
				'separator' => 'before',
			)
		);

		$this->addControl(
			'title_background',
			array(
				'label' => $module->l('Title Background'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_title_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-accordion .rb-accordion-title' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'tab_active_color',
			array(
				'label' => $module->l('Active Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_title_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-accordion .rb-accordion-title.active' => 'color: {{VALUE}};',
				),
				'scheme' => array(
					'type' => 'color',
					'value' => 4,
				),
			)
		);

		$this->addGroupControl(
			'typography',
			array(
				'label' => $module->l('Title Typography'),
				'name' => 'title_typography',
				'tab' => 'style',
				'section' => 'section_title_style',
				'selector' => '{{WRAPPER}} .rb-accordion .rb-accordion-title',
				'scheme' => 1,
			)
		);

		$this->addControl(
			'content_background_color',
			array(
				'label' => $module->l('Content Background'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_title_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-accordion .rb-accordion-content' => 'background-color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->addControl(
			'content_color',
			array(
				'label' => $module->l('Content Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_title_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-accordion .rb-accordion-content' => 'color: {{VALUE}};',
				),
				'scheme' => array(
					'type' => 'color',
					'value' => 3,
				),
			)
		);

		$this->endControlsSection();

		$this->addGroupControl(
			'typography',
			array(
				'name' => 'content_typography',
				'label' => $module->l('Content Typography'),
				'tab' => 'style',
				'section' => 'section_title_style',
				'selector' => '{{WRAPPER}} .rb-accordion .rb-accordion-content',
				'scheme' => 3,
			)
		);
   	}

   	public function getDataAccordion()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Accordion',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'accordion'
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

        return $module->fetch('module:rbthemedream/views/templates/widget/rb-accordion.tpl');
    }
}
