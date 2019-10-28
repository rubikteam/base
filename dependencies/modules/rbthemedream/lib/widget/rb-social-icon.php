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

class RbSocialIcons extends RbControl
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
			'section_social_icon',
			array(
				'label' => $module->l('Social Icons'),
				'type' => 'section',
			)
		);

		$this->addControl(
			'social_icon_list',
			array(
				'label' => $module->l('Social Icons'),
				'type' => 'repeater',
				'default' => array(
					array(
						'social' => 'fa fa-facebook',
					),
					array(
						'social' => 'fa fa-twitter',
					),
					array(
						'social' => 'fa fa-google-plus',
					),
				),
				'section' => 'section_social_icon',
				'fields' => array(
					array(
						'name' => 'social',
						'label' => $module->l('Icon'),
						'type' => 'icon',
						'label_block' => true,
						'default' => 'fa fa-wordpress',
						'include' => [
							'fa fa-behance',
							'fa fa-bitbucket',
							'fa fa-codepen',
							'fa fa-delicious',
							'fa fa-digg',
							'fa fa-dribbble',
							'fa fa-facebook',
							'fa fa-flickr',
							'fa fa-foursquare',
							'fa fa-github',
							'fa fa-google-plus',
							'fa fa-instagram',
							'fa fa-jsfiddle',
							'fa fa-linkedin',
							'fa fa-medium',
							'fa fa-pinterest',
							'fa fa-product-hunt',
							'fa fa-reddit',
							'fa fa-snapchat',
							'fa fa-soundcloud',
							'fa fa-stack-overflow',
							'fa fa-tumblr',
							'fa fa-twitter',
							'fa fa-vimeo',
							'fa fa-wordpress',
							'fa fa-youtube',
						],
					),
					array(
						'name' => 'link',
						'label' => $module->l('Link'),
						'type' => 'url',
						'label_block' => true,
						'default' => array(
							'url' => '',
							'is_external' => 'true',
						),
						'placeholder' => $module->l('https://www.youtube.com/watch?v=yikCzp_OB50'),
					),
				),
				'title_field' => 'social',
			)
		);

		$this->addControl(
			'shape',
			array(
				'label' => $module->l('Shape'),
				'type' => 'select',
				'section' => 'section_social_icon',
				'default' => 'rounded',
				'options' => array(
					'rounded' => $module->l('Rounded'),
					'square' => $module->l('Square'),
					'circle' => $module->l('Circle'),
				),
				'prefix_class' => 'rb-shape-',
			)
		);

		$this->addResponsiveControl(
			'align',
			array(
				'label' => $module->l('Alignment'),
				'type' => 'choose',
				'section' => 'section_social_icon',
				'options' => array(
					'left'    => array(
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
				),
				'default' => 'center',
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'view',
			array(
				'label' => $module->l('View'),
				'type' => 'hidden',
				'default' => 'traditional',
				'section' => 'section_icon',
			)
		);

		$this->addControl(
			'section_social_style',
			array(
				'label' => $module->l('Icon'),
				'type' => 'section',
				'tab' => 'style',
			)
		);

		$this->addControl(
			'icon_color',
			array(
				'label' => $module->l('Background Color'),
				'type' => 'select',
				'tab' => 'style',
				'section' => 'section_social_style',
				'default' => 'default',
				'options' => array(
					'default' => $module->l('Official Color'),
					'custom' => $module->l('Custom'),
				),
			)
		);

		$this->addControl(
			'icon_primary_color',
			array(
				'label' => $module->l('Background'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_social_style',
				'condition' => array(
					'icon_color' => 'custom',
				),
				'selectors' => array(
					'{{WRAPPER}} .rb-social-icon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'icon_secondary_color',
			array(
				'label' => $module->l('Icon'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_social_style',
				'default' => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rb-social-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->addControl(
			'icon_size',
			array(
				'label' => $module->l('Icon Size'),
				'type' => 'slider',
				'tab' => 'style',
				'section' => 'section_social_style',
				'range' => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rb-social-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->addControl(
			'icon_padding',
			array(
				'label' => $module->l('Icon Padding'),
				'type' => 'slider',
				'tab' => 'style',
				'section' => 'section_social_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-social-icon' => 'padding: {{SIZE}}{{UNIT}};',
				),
				'default' => array(
					'unit' => 'em',
				),
				'range' => array(
					'em' => array(
						'min' => 0,
					),
				),
			)
		);

		$icon_spacing = 'margin-right: {{SIZE}}{{UNIT}};';

		$this->addControl(
			'icon_spacing',
			array(
				'label' => $module->l('Icon Spacing'),
				'type' => 'slider',
				'tab' => 'style',
				'section' => 'section_social_style',
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rb-social-icon:not(:last-child)' => $icon_spacing,
				),
			)
		);

		$this->addGroupControl(
			'border',
			array(
				'name' => 'image_border',
				'tab' => 'style',
				'section' => 'section_social_style',
				'selector' => '{{WRAPPER}} .rb-social-icon',
			)
		);

		$this->addControl(
			'border_radius',
			array(
				'label' => $module->l('Border Radius'),
				'type' => 'dimensions',
				'size_units' => array('px', '%'),
				'tab' => 'style',
				'section' => 'section_social_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-icon' => 'border-radius:
					{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
    }

    public function getDataSocialIcons()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Social Icons',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'social-icons'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
 		$html = '<div class="rb-social-icons-wrapper">';

 		foreach ($instance['social_icon_list'] as $item) {
 			$social = str_replace( 'fa fa-', '', $item['social'] );
			$target = isset($item['link']['is_external']) ? ' target="_blank" rel="noopener noreferrer"' : '';

			if (!isset($item['link']['url'])) {
				$item['link']['url'] = '#';
			}

			$html .= '<a class="rb-icon rb-social-icon rb-social-icon-'.Tools::safeOutput($social).'" href="'.Tools::safeOutput($item['link']['url']).'"'.$target.'>';
			$html .= '<i class="'.$item['social'].'"></i></a>';
 		}

 		$html .= '</div>';

 		return $html;
    }
}
