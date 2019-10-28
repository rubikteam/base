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

class RbTextEditor extends RbControl
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
			'section_editor',
			array(
				'label' => $module->l('Text Editor'),
				'type' => 'section',
			)
		);

		$this->addControl(
			'editor',
			array(
				'label' => '',
				'type' => 'wysiwyg',
				'default' => '<p>' . $module->l( 'I am text block. Click edit button to change this text') . '</p>',
				'section' => 'section_editor',
			)
		);

		$this->addControl(
			'section_style',
			array(
				'label' => $module->l('Text Editor'),
				'type' => 'section',
				'tab' => 'style',
			)
		);

		$this->addResponsiveControl(
			'align',
			array(
				'label' => $module->l('Alignment'),
				'type' => 'choose',
				'tab' => 'style',
				'section' => 'section_style',
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
				'selectors' => array(
					'{{WRAPPER}} .rb-text-editor' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->addControl(
	        'text_color',
	        array(
	            'label' => $module->l('Text Color'),
	            'type' => 'color',
	            'tab' => 'style',
	            'section' => 'section_style',
	            'default' => '',
	            'selectors' => array(
	                '{{WRAPPER}}' => 'color: {{VALUE}};',
	            ),
	            'scheme' => array(
		            'type' => 'color',
		            'value' => 3,
	            ),
	        )
	    );

		$this->addGroupControl(
			'typography',
			array(
				'name' => 'typography',
				'section' => 'section_style',
				'tab' => 'style',
				'scheme' => 3,
			)
		);
    }

    public function getDataTextEditor()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Text Editor',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'rb-text'
    	);
    	
    	return $data;
    }

    public function rbRender($instance = array())
    {
    	$instance['editor'] = RbControl::parseTextEditor($instance['editor'], $instance);

    	return '<div class="rb-text-editor rte-content">'.$instance['editor'].'</div>';
    }
}
