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

class RbBackground
{
	public function getControls($args)
	{
		$module = new Rbthemedream();

		$available_types = array(
			'classic' => array(
				'title' => $module->l('Classic'),
				'icon' => 'paint-brush',
			),
            'gradient' => array(
                'title' => $module->l('Gradient'),
                'icon' => 'fa fa-barcode',
            ),
			'video' => array(
				'title' => $module->l('Background Video'),
				'icon' => 'video-camera',
			),
		);

		$choose_types = array(
			'none' => array(
				'title' => $module->l('None'),
				'icon' => 'ban',
			),
		);

		foreach ($args['types'] as $type) {
			if (isset($available_types[$type])) {
				$choose_types[$type] = $available_types[$type];
			}
		}

		$controls = array();

		$controls['background'] = array(
			'label' => $module->l('Background Type'),
			'type' => 'choose',
			'default' => $args['default'],
			'options' => $choose_types,
			'label_block' => true,
		);

		if (in_array('classic', $args['types'] )) {
			$controls['color'] = array(
				'label' => $module->l( 'Color'),
				'type' => 'color',
				'default' => '',
				'tab' => $args['tab'],
				'title' => $module->l('Background Color'),
				'selectors' => array(
					$args['selector'] => 'background-color: {{VALUE}};',
				),
				'condition' => array(
                    'background' => array('classic', 'gradient'),
				),
			);

            if (in_array('gradient', $args['types'])) {
                $controls['color_stop'] = array(
                    'label' => $module->l('Location'),
                    'type' => 'slider',
                    'size_units' => array('%'),
                    'default' => array(
                        'unit' => '%',
                        'size' => 0,
                    ),
                    'render_type' => 'ui',
                    'condition' => array(
                        'background' => array('gradient'),
                    ),
                );

                $controls['color_b'] = array(
                    'label' => $module->l('Second Color'),
                    'type' => 'color',
                    'default' => 'transparent',
                    'render_type' => 'ui',
                    'condition' => array(
                        'background' => array('gradient'),
                    ),
                );

                $controls['color_b_stop'] = array(
                    'label' => $module->l('Location'),
                    'type' => 'slider',
                    'size_units' => array('%'),
                    'default' => array(
                        'unit' => '%',
                        'size' => 100,
                    ),
                    'render_type' => 'ui',
                    'condition' => array(
                        'background' => array('gradient'),
                    ),
                );

                $controls['gradient_type'] = array(
                    'label' => $module->l( 'Type'),
                    'type' => 'select',
                    'options' => array(
                        'linear' => $module->l('Linear'),
                        'radial' => $module->l('Radial'),
                    ),
                    'default' => 'linear',
                    'render_type' => 'ui',
                    'condition' => array(
                        'background' => array('gradient'),
                    ),
                );

                $controls['gradient_angle'] = array(
                    'label' => $module->l('Angle'),
                    'type' => 'slider',
                    'size_units' => array('deg'),
                    'default' => array(
                        'unit' => 'deg',
                        'size' => 180,
                    ),
                    'range' => array(
                        'deg' => array(
                            'step' => 10,
                        ),
                    ),
                    'selectors' => array(
                        $args['selector'] => 'background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                    ),
                    'condition' => array(
                        'background' => array('gradient'),
                        'gradient_type' => 'linear',
                    ),
                );

                $controls['gradient_position'] = array(
                    'label' => $module->l('Position'),
                    'type' => 'select',
                    'options' => array(
                        'center center' => $module->l('Center Center'),
                        'center left' => $module->l('Center Left'),
                        'center right' => $module->l('Center Right'),
                        'top center' => $module->l('Top Center'),
                        'top left' => $module->l('Top Left'),
                        'top right' => $module->l('Top Right'),
                        'bottom center' => $module->l('Bottom Center'),
                        'bottom left' => $module->l('Bottom Left'),
                        'bottom right' => $module->l('Bottom Right'),
                    ),
                    'default' => 'center center',
                    'selectors' => array(
                        $args['selector'] => 'background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                    ),
                    'condition' => array(
                        'background' => array('gradient'),
                        'gradient_type' => 'radial',
                    ),
                );
            }
		}

		if ( in_array( 'classic', $args['types'] ) ) {
			$controls['image'] = array(
				'label' => $module->l('Image'),
				'type' => 'media',
				'title' => $module->l('Background Image'),
				'selectors' => array(
					$args['selector'] => 'background-image: url("{{URL}}");',
				),
				'condition' => array(
					'background' => array('classic'),
				),
			);

			$controls['position'] = array(
				'label' => $module->l('Position'),
				'type' => 'select',
				'default' => '',
				'options' => array(
					'' => $module->l('None'),
					'top left' => $module->l('Top Left'),
					'top center' => $module->l('Top Center'),
					'top right' => $module->l('Top Right'),
					'center left' => $module->l('Center Left'),
					'center center' => $module->l('Center Center'),
					'center right' => $module->l('Center Right'),
					'bottom left' => $module->l('Bottom Left'),
					'bottom center' => $module->l('Bottom Center'),
					'bottom right' => $module->l('Bottom Right'),
				),
				'selectors' => array(
					$args['selector'] => 'background-position: {{VALUE}};',
				),
				'condition' => array(
					'background' => array('classic'),
					'image[url]!' => '',
				),
			);

			$controls['attachment'] = array(
				'label' => $module->l('Attachment'),
				'type' => 'select',
				'default' => '',
				'options' => array(
					'' => $module->l('None'),
					'scroll' => $module->l('Scroll'),
					'fixed' => $module->l('Fixed'),
				),
				'selectors' => array(
					$args['selector'] => 'background-attachment: {{VALUE}};',
				),
				'condition' => array(
					'background' => array('classic'),
					'image[url]!' => '',
				),
			);

			$controls['repeat'] = array(
				'label' => $module->l('Repeat'),
				'type' => 'select',
				'default' => '',
				'options' => array(
					'' => $module->l('None'),
					'no-repeat' => $module->l('No-repeat'),
					'repeat' => $module->l('Repeat'),
					'repeat-x' => $module->l('Repeat-x'),
					'repeat-y' => $module->l('Repeat-y'),
				),
				'selectors' => array(
					$args['selector'] => 'background-repeat: {{VALUE}};',
				),
				'condition' => array(
					'background' => array('classic'),
					'image[url]!' => '',
				),
			);

			$controls['size'] = array(
				'label' => $module->l('Size'),
				'type' => 'select',
				'default' => '',
				'options' => array(
					'' => $module->l('None'),
					'auto' => $module->l('Auto'),
					'cover' => $module->l('Cover'),
					'contain' => $module->l('Contain'),
				),
				'selectors' => array(
					$args['selector'] => 'background-size: {{VALUE}};',
				),
				'condition' => array(
					'background' => array('classic'),
					'image[url]!' => '',
				),
			);
		}

		$controls['video_link'] = array(
			'label' => $module->l('Video Link'),
			'type' => 'text',
			'placeholder' => 'https://www.youtube.com/watch?v=9uOETcuFjbE',
			'description' => $module->l('Insert YouTube link or video file (mp4 is recommended)'),
			'label_block' => true,
			'default' => '',
			'condition' => array(
				'background' => array('video'),
			),
		);

		$controls['video_fallback'] = array(
			'label' => $module->l('Background Fallback'),
			'description' => $module->l('This cover image will replace the background video on mobile or tablet.'),
			'type' => 'media',
			'label_block' => true,
			'condition' => array(
				'background' => array('video'),
			),
			'selectors' => array(
				$args['selector'] => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
			),
		);

		return $controls;
	} 
}
