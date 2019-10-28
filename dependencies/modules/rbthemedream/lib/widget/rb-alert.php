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

class RbAlert extends RbControl
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
			'section_alert',
			array(
				'label' => $module->l('Alert'),
				'type' => 'section',
			)
		);

		$this->addControl(
			'alert_type',
			array(
				'label' => $module->l('Type'),
				'type' => 'select',
				'default' => 'info',
				'section' => 'section_alert',
				'options' => array(
					'info' => $module->l('Info'),
					'success' => $module->l('Success'),
					'warning' => $module->l('Warning'),
					'danger' => $module->l('Danger'),
				),
			)
		);

		$this->addControl(
			'alert_title',
			array(
				'label' => $module->l('Title & Description'),
				'type' => 'text',
				'placeholder' => $module->l('Your Title'),
				'default' => $module->l('This is Alert'),
				'label_block' => true,
				'section' => 'section_alert',
			)
		);

		$this->addControl(
			'alert_description',
			array(
				'label' => $module->l('Content'),
				'type' => 'textarea',
				'placeholder' => $module->l('Your Description'),
				'default' => $module->l( 'This is description. Click edit button to change this text'),
				'separator' => 'none',
				'section' => 'section_alert',
				'show_label' => false,
			)
		);

		$this->addControl(
			'show_dismiss',
			array(
				'label' => $module->l('Dismiss Button'),
				'type' => 'select',
				'default' => 'show',
				'section' => 'section_alert',
				'options' => array(
					'show' => $module->l('Show'),
					'hide' => $module->l('Hide'),
				),
			)
		);

		$this->addControl(
			'view',
			array(
				'label' => $module->l('View'),
				'type' => 'hidden',
				'default' => 'traditional',
				'section' => 'section_alert',
			)
		);
    }

    public function getDataAlert()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Alert',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'alert'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
    	if (empty($instance['alert_title'])) {
			return;
		}

		if (!empty($instance['alert_type'])) {
			$this->addRenderAttribute(
				'wrapper',
				'class',
				'rb-alert alert alert-' . $instance['alert_type']
			);
		}

		$html ='<div ' . $this->getRenderAttributeString('wrapper') . ' role="alert">';
		$html .= sprintf('<span class="rb-alert-title">%1$s</span>', $instance['alert_title']);

		if (!empty( $instance['alert_description'])) {
			$html .= sprintf('<span class="rb-alert-description">%s</span>', $instance['alert_description']);
		}

		if (!empty($instance['show_dismiss'] ) && 'show' === $instance['show_dismiss']) {
			$html .= '<button type="button" class="rb-alert-dismiss">X</button></div>';
		}

		return $html;
    }
}
