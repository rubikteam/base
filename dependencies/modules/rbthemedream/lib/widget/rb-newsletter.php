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

class RbNewsletter extends RbControl
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

    	$this->addPresControl(array(
    		'section_pswidget_options' => array(
                'label' => $module->l('Widget settings'),
                'type' => 'section',
            ),
            'width' => array(
                'label' => $module->l('Max width'),
                'type' => 'slider',
                'default' => array(
                    'size' => 300,
                    'unit' => 'px',
                ),
                'range' => array(
                    'px' => array(
                        'min' => 250,
                        'max' => 1400,
                    ),
                ),
                'section' => 'section_pswidget_options',
                'selectors' => array(
                    '{{WRAPPER}} .rb-newsletter-form' => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            ),
            'height' => array(
                'label' => $module->l('Input height'),
                'type' => 'slider',
                'default' => array(
                    'size' => 45,
                    'unit' => 'px',
                ),
                'range' => array(
                    'px' => array(
                        'min' => 25,
                        'max' => 80,
                    ),
                ),
                'section' => 'section_pswidget_options',
                'selectors' => array(
                    '{{WRAPPER}} .rb-newsletter-input' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .rb-newsletter-btn' => 'min-height: {{SIZE}}{{UNIT}};',

                ),
            ),
            'align' => array(
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
                ),
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .rb-newsletter-input' => 'text-align: {{VALUE}};',
                ),
                'section' => 'section_pswidget_options',
            ),
            'section_style_input' => array(
                'label' => $module->l('Input'),
                'type' => 'section',
                'tab' => 'style',
            ),
            'input_bg' => array(
                'label' => $module->l('Background'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_input',
                'selectors' => array(
                    '{{WRAPPER}} .rb-newsletter-input' => 'background: {{VALUE}};',
                ),
            ),
            'input_color' => array(
                'label' => $module->l('Text color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_input',
                'selectors' => array(
                    '{{WRAPPER}} .rb-newsletter-input' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .rb-newsletter-input::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .rb-newsletter-input:-ms-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .rb-newsletter-input::placeholder' => 'color: {{VALUE}};',
                ),
            ),
            'input_border' => array(
                'group_type_control' => 'border',
                'name' => 'border',
                'label' => $module->l('Border'),
                'tab' => 'style',
                'placeholder' => '1px',
                'default' => '1px',
                'section' => 'section_style_input',
                'selector' => '{{WRAPPER}} .rb-newsletter-input',
            ),
            'input_bg_hover' => array(
                'label' => $module->l('Focus - background'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_input',
                'selectors' => array(
                    '{{WRAPPER}} .rb-newsletter-input:focus' => 'background: {{VALUE}};',
                ),
            ),
            'input_color_hover' => array(
                'label' => $module->l('Focus - color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_input',
                'selectors' => array(
                    '{{WRAPPER}} .rb-newsletter-input:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .rb-newsletter-input:focus::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .rb-newsletter-input:focus:-ms-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .rb-newsletter-input:focus::placeholder' => 'color: {{VALUE}};',
                ),
            ),
            'input_border_h' => array(
                'label' => $module->l('Focus - border color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_input',
                'selectors' => array(
                    '{{WRAPPER}} .rb-newsletter-input:focus' => 'border-color: {{VALUE}};',
                ),
            ),
            'section_style_btn' => array(
                'label' => $module->l('Button'),
                'type' => 'section',
                'tab' => 'style',
            ),
            'btn_bg' => array(
                'label' => $module->l('Background'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_btn',
                'selectors' => array(
                    '{{WRAPPER}} .rb-newsletter-btn' => 'background: {{VALUE}};',
                ),
            ),
            'btn_color' => array(
                'label' => $module->l('Text'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_btn',
                'selectors' => array(
                    '{{WRAPPER}} .rb-newsletter-btn' => 'color: {{VALUE}};',
                ),
            ),
            'btn_bg_hover' => array(
                'label' => $module->l('Hover - background'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_btn',
                'selectors' => array(
                    '{{WRAPPER}} .rb-newsletter-btn:hover' => 'background: {{VALUE}};',
                ),
            ),
            'btn_color_hover' => array(
                'label' => $module->l('Hover - text'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_btn',
                'selectors' => array(
                    '{{WRAPPER}} .rb-newsletter-btn:hover' => 'color: {{VALUE}};',
                ),
            ),
    	));
    }

    public function getDataNewsletter()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Newsletter',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('prestashop'),
    		'keywords' => '',
    		'icon' => 'newsletter'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
        if (Tools::getIsset('controller') &&
            Tools::getValue('controller') == 'index'
        ) {
            $module = Module::getInstanceByName('ps_emailsubscription');

            return $module->renderWidget('displayHome', array());
        } else {
            return;
        }
    }
}
