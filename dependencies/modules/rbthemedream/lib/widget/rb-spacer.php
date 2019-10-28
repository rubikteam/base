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

class RbSpacer extends RbControl
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
			'section_spacer',
			array(
				'label' => $module->l('Spacer'),
				'type' => 'section',
			)
		);

		$this->addResponsiveControl(
			'space',
			array(
				'label' => $module->l('Space'),
				'type' => 'slider',
				'section' => 'section_spacer',
				'default' => array(
					'size' => 50,
				),
				'range' => array(
					'px' => array(
						'min' => 10,
						'max' => 600,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rb-spacer-inner' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->addControl(
			'view',
			array(
				'label' => $module->l('View'),
				'type' => 'hidden',
				'default' => 'traditional',
				'section' => 'section_spacer',
			)
		);
    }

    public function getDataSpacer()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Height',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'height'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
    	return '<div class="rb-spacer"><div class="rb-spacer-inner"></div></div>';
    }
}
