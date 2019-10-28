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

class RbGroupTypography
{
	public function __construct()
	{
		$this->module = new Rbthemedream();
	}

	public function getControls($args)
	{
		$controls = $this->getFields();

		array_walk($controls, function(&$control, $control_name) use ($args) {
			$selector_value = !empty($control['selector_value']) ? $control['selector_value'] :
			str_replace( '_', '-', $control_name ) . ': {{VALUE}};';

			$control['selectors'] = array(
				$args['selector'] => $selector_value,
			);

			$control['condition'] = array(
				'typography' => array('custom'),
			);
		});


        $controls['font_family_custom']['condition'] = array(
            'typography' => array('custom'),
            'font_family' => array('custom'),
        );

		$typography_control = array(
			'typography' => array(
				'label' => $this->module->l('Typography'),
				'type' => 'select',
				'default' => '',
				'options' => array(
					'' => $this->module->l('Default'),
					'custom' => $this->module->l('Custom'),
				),
			),
		);

		$controls = $typography_control + $controls;
		
		return $controls;
	}

	public function getFields()
	{
		$fields = array();

		$fields['font_size'] = array(
			'label' => $this->module->l('Size'),
			'type' => 'slider',
			'size_units' => array('px', 'em', 'rem'),
			'range' => array(
				'px' => array(
					'min' => 1,
					'max' => 200,
				),
			),
			'responsive' => true,
			'selector_value' => 'font-size: {{SIZE}}{{UNIT}}',
		);

		$default_fonts = 'Sans-serif';

		if ($default_fonts) {
			$default_fonts = ', ' . $default_fonts;
		}

		$fields['font_family'] = array(
			'label' => $this->module->l('Family'),
			'type' => 'font',
			'default' => '',
			'selector_value' => 'font-family: {{VALUE}}' . $default_fonts . ';',
		);

        $fields['font_family_custom'] = array(
            'label' => $this->module->l('Custom font family'),
            'type' => 'text',
            'default' => '',
            'selector_value' => 'font-family: {{VALUE}}' . $default_fonts . ';',
        );

		$typo_weight_options = array('' => $this->module->l('Default'));

		foreach (array_merge(array('normal', 'bold'), range( 100, 900, 100 )) as $weight) {
			$typo_weight_options[$weight] = ucfirst($weight);
		}

		$fields['font_weight'] = array(
			'label' => $this->module->l('Weight'),
			'type' =>'select',
			'default' => '',
			'options' => $typo_weight_options,
		);

		$fields['text_transform'] = array(
			'label' => $this->module->l('Transform'),
			'type' => 'select',
			'default' => '',
			'options' => array(
				'' => $this->module->l('Default'),
				'uppercase' => $this->module->l('Uppercase'),
				'lowercase' => $this->module->l('Lowercase'),
				'capitalize' => $this->module->l('Capitalize'),
			),
		);

		$fields['font_style'] = array(
			'label' => $this->module->l('Style'),
			'type' => 'select',
			'default' => '',
			'options' => array(
				'' => $this->module->l('Default'),
				'normal' => $this->module->l('Normal'),
				'italic' => $this->module->l('Italic'),
				'oblique' => $this->module->l('Oblique'),
			),
		);

		$fields['line_height'] = array(
			'label' => $this->module->l('Line-Height'),
			'type' => 'slider',
			'default' => array(
				'unit' => 'em',
			),
			'range' => array(
				'px' => array(
					'min' => 1,
				),
			),
			'responsive' => true,
			'size_units' => array('px', 'em'),
			'selector_value' => 'line-height: {{SIZE}}{{UNIT}}',
		);

		$fields['letter_spacing'] = array(
			'label' => $this->module->l('Letter Spacing'),
			'type' => 'slider',
			'range' => array(
				'px' => array(
					'min' => -5,
					'max' => 10,
					'step' => 0.1,
				),
			),
			'responsive' => true,
			'selector_value' => 'letter-spacing: {{SIZE}}{{UNIT}}',
		);

		return $fields;
	}
}
