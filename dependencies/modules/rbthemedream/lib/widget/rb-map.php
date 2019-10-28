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

class RbMap extends RbControl
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
			'section_map',
			array(
				'label' => $module->l('Map'),
				'type' => 'section',
			)
		);

		$default_address = $module->l('Manchester, United Kingdom');

		$this->addControl(
			'address',
			array(
				'label' => $module->l('Address'),
				'type' => 'text',
				'placeholder' => $default_address,
				'default' => $default_address,
				'label_block' => true,
				'section' => 'section_map',
			)
		);

		$this->addControl(
			'zoom',
			array(
				'label' => $module->l('Zoom Level'),
				'type' => 'slider',
				'default' => array(
					'size' => 10,
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'section' => 'section_map',
			)
		);

		$this->addControl(
			'height',
			array(
				'label' => $module->l('Height'),
				'type' => 'slider',
				'default' => array(
					'size' => 300,
				),
				'range' => array(
					'px' => array(
						'min' => 40,
						'max' => 1440,
					),
				),
				'section' => 'section_map',
				'selectors' => array(
					'{{WRAPPER}} iframe' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->addControl(
			'prevent_scroll',
			array(
				'label' => $module->l('Prevent Scroll'),
				'type' => 'select',
				'default' => '',
				'options' => array(
					'' => $module->l('No'),
					'yes' => $module->l('Yes'),
				),
				'section' => 'section_map',
				'selectors' => array(
					'{{WRAPPER}} iframe' => 'pointer-events: none;',
				),
			)
		);

		$this->addControl(
			'view',
			array(
				'label' => $module->l('View'),
				'type' => 'hidden',
				'default' => 'traditional',
				'section' => 'section_map',
			)
		);
    }

    public function getDataMap()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Map',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'map'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
    	if (empty($instance['address']))
			return;

		if (0 === abs(intval($instance['zoom']['size'])))
			$instance['zoom']['size'] = 10;

		$map = '400px';

		if (isset($instance['height']['size']) && $instance['height']['size'] != '') {
			$map = $instance['height']['size'] . $instance['height']['unit'];
		}

		return '<div class="rb-custom-embed"><iframe height="'.$map.'" frameborder="0" scrolling="'.$instance['prevent_scroll'].'" marginheight="0" marginwidth="0"
		src="https://maps.google.com/maps?q='.urlencode($instance['address']).'&amp;t=m&amp;z='.abs($instance['zoom']['size']).'&amp;output=embed&amp;iwloc=near"></iframe></div>';
    }
}    	
