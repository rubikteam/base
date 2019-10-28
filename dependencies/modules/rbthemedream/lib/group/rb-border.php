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

class RbBorder
{
	public function getControls($args)
	{
		$module = new Rbthemedream();
		$controls = array();
        $property = 'border';

        if (isset($args['property']) && ($args['property']  == 'outline')) {
            $property = 'outline';
        }

        $controls['border'] = array(
			'label' => $module->l('Border Type'),
			'type' => 'select',
			'options' => array(
				'' => $module->l('None'),
				'solid' => $module->l('Solid'),
				'double' => $module->l('Double'),
				'dotted' => $module->l('Dotted'),
				'dashed' => $module->l('Dashed'),
			),
			'selectors' => array(
				$args['selector'] => $property . '-style: {{VALUE}};',
			),
			'separator' => 'before',
		);

		$controls['width'] = array(
			'label' => $module->l('Width'),
			'type' => 'dimensions',
			'selectors' => array(
				$args['selector'] => $property . '-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition' => array(
				'border!' => '',
			),
		);

		$controls['color'] = array(
			'label' => $module->l('Color'),
			'type' => 'color',
			'default' => '',
			'tab' => $args['tab'],
			'selectors' => array(
				$args['selector'] => $property . '-color: {{VALUE}};',
			),
			'condition' => array(
				'border!' => '',
			),
		);

		return $controls;
	}
}
