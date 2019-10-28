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

class RbImageCarousel extends RbControl
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
			'section_image_carousel',
			array(
				'label' => $module->l('Images list'),
				'type' => 'section',
			)
		);

        $this->addControl(
            'images_list',
            array(
                'label' => '',
                'type' => 'repeater',
                'default' => array(),
                'section' => 'section_image_carousel',
                'fields' => array(
                    array(
                        'name' => 'text',
                        'label' => $module->l('Image alt'),
                        'type' => 'text',
                        'label_block' => true,
                        'placeholder' => $module->l('Image alt'),
                        'default' => $module->l('Image alt'),
                    ),
                    array(
                        'name' => 'image',
                        'label' => $module->l('Choose Image'),
                        'type' => 'media',
                        'placeholder' => $module->l('Image'),
                        'label_block' => true,
                        'default' => array(
                            'url' => _MODULE_DIR_ . 'rbthemedream/views/img/img.jpg',
                        ),
                    ),
                    array(
                        'name' => 'link',
                        'label' => $module->l('Link'),
                        'type' => 'url',
                        'label_block' => true,
                        'placeholder' => 'https://www.youtube.com/watch?v=yikCzp_OB50',
                    ),
                ),
                'title_field' => 'text',
            )
        );

		$this->addControl(
			'view',
			array(
				'label' => $module->l('View'),
				'type' => 'hidden',
				'default' => 'traditional',
				'section' => 'section_image_carousel',
			)
		);

		$this->addControl(
			'section_additional_options',
			array(
				'label' => $module->l('Carousel settings'),
				'type' => 'section',
			)
		);

		$slides_to_show = range(1, 10);
		$slides_to_show = array_combine($slides_to_show, $slides_to_show);

		$this->addControl(
			'slides_to_show',
			array(
				'label' => $module->l('Slides to Show'),
				'type' => 'select',
				'default' => '3',
				'section' => 'section_additional_options',
				'options' => $slides_to_show,
			)
		);

		$this->addControl(
			'slides_to_scroll',
			array(
				'label' => $module->l('Slides to Scroll'),
				'type' => 'select',
				'default' => '2',
				'section' => 'section_additional_options',
				'options' => $slides_to_show,
				'condition' => array(
					'slides_to_show!' => '1',
				),
			)
		);

		$this->addControl(
			'image_stretch',
			array(
				'label' => $module->l('Image Stretch'),
				'type' => 'select',
				'default' => 'no',
				'section' => 'section_additional_options',
				'options' => array(
					'no' => $module->l('No'),
					'yes' => $module->l('Yes'),
				),
			)
		);

		$this->addControl(
			'image_lazy',
			array(
				'label' => $module->l('Lazy load'),
				'type' => 'select',
				'default' => 'yes',
				'section' => 'section_additional_options',
				'options' => array(
					'no' => $module->l('No'),
					'yes' => $module->l('Yes'),
				),
			)
		);

		$this->addControl(
			'navigation',
			array(
				'label' => $module->l('Navigation'),
				'type' => 'select',
				'default' => 'both',
				'section' => 'section_additional_options',
				'options' => array(
					'both' => $module->l('Arrows and Dots'),
					'arrows' => $module->l('Arrows'),
					'dots' => $module->l('Dots'),
					'none' => $module->l('None'),
				),
			)
		);

		$this->addControl(
			'pause_on_hover',
			array(
				'label' => $module->l('Pause on Hover'),
				'type' => 'select',
				'default' => 'yes',
				'section' => 'section_additional_options',
				'options' => array(
					'yes' => $module->l('Yes'),
					'no' => $module->l('No'),
				),
			)
		);

		$this->addControl(
			'autoplay',
			array(
				'label' => $module->l('Autoplay'),
				'type' => 'select',
				'default' => 'yes',
				'section' => 'section_additional_options',
				'options' => array(
					'yes' => $module->l('Yes'),
					'no' => $module->l('No'),
				),
			)
		);

		$this->addControl(
			'autoplay_speed',
			array(
				'label' => $module->l('Autoplay Speed'),
				'type' => 'number',
				'default' => 5000,
				'section' => 'section_additional_options',
			)
		);

		$this->addControl(
			'infinite',
			array(
				'label' => $module->l('Infinite Loop'),
				'type' => 'select',
				'default' => 'yes',
				'section' => 'section_additional_options',
				'options' => array(
					'yes' => $module->l('Yes'),
					'no' => $module->l('No'),
				),
			)
		);

		$this->addControl(
			'effect',
			array(
				'label' => $module->l('Effect'),
				'type' => 'select',
				'default' => 'slide',
				'section' => 'section_additional_options',
				'options' => array(
					'slide' => $module->l('Slide'),
					'fade' => $module->l('Fade'),
				),
				'condition' => array(
					'slides_to_show' => '1',
				),
			)
		);

		$this->addControl(
			'speed',
			array(
				'label' => $module->l('Animation Speed'),
				'type' => 'number',
				'default' => 500,
				'section' => 'section_additional_options',
			)
		);

		$this->addControl(
			'direction',
			array(
				'label' => $module->l('Direction'),
				'type' => 'select',
				'default' => 'ltr',
				'section' => 'section_additional_options',
				'options' => array(
					'ltr' => $module->l('Left'),
					'rtl' => $module->l('Right'),
				),
			)
		);

		$this->addControl(
			'section_style_navigation',
			array(
				'label' => $module->l('Navigation'),
				'type' => 'section',
				'tab' => 'style',
				'condition' => array(
					'navigation' => array('arrows', 'dots', 'both'),
				),
			)
		);

		$this->addControl(
			'heading_style_arrows',
			array(
				'label' => $module->l('Arrows'),
				'type' => 'heading',
				'tab' => 'style',
				'section' => 'section_style_navigation',
				'separator' => 'before',
				'condition' => array(
					'navigation' => array('arrows', 'both'),
				),
			)
		);

		$this->addControl(
			'arrows_position',
			array(
				'label' => $module->l('Arrows Position'),
				'type' => 'select',
				'default' => 'inside',
				'section' => 'section_style_navigation',
				'tab' => 'style',
				'options' => array(
					'inside' => $module->l('Inside'),
					'outside' => $module->l('Outside'),
				),
				'condition' => array(
					'navigation' => array('arrows', 'both'),
				),
			)
		);

		$this->addControl(
			'arrows_size',
			array(
				'label' => $module->l('Arrows Size'),
				'type' => 'slider',
				'section' => 'section_style_navigation',
				'tab' => 'style',
				'range' => array(
					'px' => array(
						'min' => 20,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rb-image-carousel-wrapper .slick-slider .slick-prev:before,
					{{WRAPPER}} .rb-image-carousel-wrapper .slick-slider .slick-next:before' =>
					'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'navigation' => array('arrows', 'both'),
				),
			)
		);

		$this->addControl(
			'arrows_color',
			array(
				'label' => $module->l('Arrows Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_style_navigation',
				'selectors' => array(
					'{{WRAPPER}} .rb-image-carousel-wrapper .slick-slider .slick-prev:before,
					{{WRAPPER}} .rb-image-carousel-wrapper .slick-slider .slick-next:before' =>
					'color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array('arrows', 'both'),
				),
			)
		);

		$this->addControl(
			'arrows_bg_color',
			array(
				'label' => $module->l('Arrows background'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_style_navigation',
				'selectors' => array(
					'{{WRAPPER}} .rb-image-carousel-wrapper  .slick-slider .slick-prev,
					{{WRAPPER}} .rb-image-carousel-wrapper  .slick-slider .slick-next' =>
					'background: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array('arrows', 'both'),
				),
			)
		);

		$this->addControl(
			'heading_style_dots',
			array(
				'label' => $module->l('Dots'),
				'type' => 'heading',
				'tab' => 'style',
				'section' => 'section_style_navigation',
				'separator' => 'before',
				'condition' => array(
					'navigation' => array('dots', 'both'),
				),
			)
		);

		$this->addControl(
			'dots_position',
			array(
				'label' => $module->l('Dots Position'),
				'type' => 'select',
				'default' => 'outside',
				'tab' => 'style',
				'section' => 'section_style_navigation',
				'options' => array(
					'outside' => $module->l('Outside'),
					'inside' => $module->l('Inside'),
				),
				'condition' => array(
					'navigation' => array('dots', 'both'),
				),
			)
		);

		$this->addControl(
			'dots_size',
			array(
				'label' => $module->l('Dots Size'),
				'type' => 'slider',
				'tab' => 'style',
				'section' => 'section_style_navigation',
				'range' => array(
					'px' => array(
						'min' => 5,
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rb-image-carousel-wrapper .rb-image-carousel
					.slick-dots li button:before' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'navigation' => array('dots', 'both'),
				),
			)
		);

		$this->addControl(
			'dots_color',
			array(
				'label' => $module->l('Dots Color'),
				'type' => 'color',
				'tab' => 'style',
				'section' => 'section_style_navigation',
				'selectors' => array(
					'{{WRAPPER}} .rb-image-carousel-wrapper .rb-image-carousel
					.slick-dots li button:before' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array('dots', 'both'),
				),
			)
		);

		$this->addControl(
			'section_style_image',
			array(
				'label' => $module->l('Image'),
				'type' => 'section',
				'tab' => 'style',
			)
		);

		$this->addControl(
			'image_spacing',
			array(
				'label' => $module->l('Spacing'),
				'type' => 'select',
				'tab' => 'style',
				'section' => 'section_style_image',
				'options' => array(
					'' => $module->l('Default'),
					'custom' => $module->l('Custom'),
				),
				'default' => '',
				'condition' => array(
					'slides_to_show!' => '1',
				),
			)
		);

		$this->addControl(
			'image_spacing_custom',
			array(
				'label' => $module->l('Image Spacing'),
				'type' => 'slider',
				'tab' => 'style',
				'section' => 'section_style_image',
				'range' => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 20,
				),
				'show_label' => false,
				'selectors' => array(
					'{{WRAPPER}} .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-slide .slick-slide-inner' => 'padding-left: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'image_spacing' => 'custom',
					'slides_to_show!' => '1',
				),
			)
		);


		$this->addGroupControl(
			'border',
			array(
				'name' => 'image_border',
				'tab' => 'style',
				'section' => 'section_style_image',
				'selector' => '{{WRAPPER}} .rb-image-carousel-wrapper .rb-image-carousel .slick-slide-image',
			)
		);

		$this->addControl(
			'image_border_radius',
			array(
				'label' => $module->l('Border Radius'),
				'type' => 'dimensions',
				'size_units' => array('px', '%'),
				'tab' => 'style',
				'section' => 'section_style_image',
				'selectors' => array(
					'{{WRAPPER}} .rb-image-carousel-wrapper .rb-image-carousel
					.slick-slide-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}}
					{{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
    }

    public function getDataImageCarousel()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Image Carousel',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'image-carousel'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
    	if (empty($instance['images_list']))
			return;

		$slides = array();
		$lazy = 'src';

		if ('yes' === $instance['image_lazy']) {
			$lazy = 'data-lazy';
		}

		foreach ($instance['images_list'] as $item) {
			$image_url = $item['image']['url'];
			$image_html = '<img class="slick-slide-image" '.$lazy.'="' . Tools::safeOutput(RbControl::getImage($image_url)) . '"/>';
			$image_html .= '<div class="rb-image-loading"></div>';

            if (!empty($item['link']['url'])) {
                $target = $item['link']['is_external'] ? ' target="_blank" rel="noopener noreferrer"' : '';
                $image_html = '<a href="'.$item['link']['url'].'" '.$target.'>'.$image_html.'</a>';
            }

			$slides[] = '<div><div class="slick-slide-inner">' . $image_html . '</div></div>';
		}

		if (empty( $slides )) {
			return;
		}

		$is_slideshow = '1' === $instance['slides_to_show'];
		$is_rtl = ('rtl' === $instance['direction']);
		$direction = $is_rtl ? 'rtl' : 'ltr';
		$show_dots = (in_array($instance['navigation'], array('dots', 'both')));
		$show_arrows = (in_array($instance['navigation'], array('arrows', 'both')));

		$slick_options = array(
			'slidesToShow' => abs(intval($instance['slides_to_show'])),
			'autoplaySpeed' => abs(intval($instance['autoplay_speed'])),
			'autoplay' => ('yes' === $instance['autoplay']),
			'infinite' => ('yes' === $instance['infinite']),
			'pauseOnHover' => ('yes' === $instance['pause_on_hover']),
			'speed' => abs(intval($instance['speed'])),
			'arrows' => $show_arrows,
			'dots' => $show_dots,
			'rtl' => $is_rtl,
		);

		$carousel_classes = array('rb-image-carousel');

		if ($show_arrows) {
			$carousel_classes[] = 'slick-arrows-' . $instance['arrows_position'];
		}

		if ($show_dots) {
			$carousel_classes[] = 'slick-dots-' . $instance['dots_position'];
		}

		if ('yes' === $instance['image_stretch']) {
			$carousel_classes[] = 'slick-image-stretch';
		}

		if ( ! $is_slideshow ) {
			$slick_options['slidesToScroll'] = abs(intval($instance['slides_to_scroll']));
		} else {
			$slick_options['fade'] = ('fade' === $instance['effect']);
		}

		$html = '<div class="rb-image-carousel-wrapper rb-slick-slider" dir="'.$direction.'">';
		$html .= "<div class='".implode(' ', $carousel_classes)."' data-slider_options='".Tools::jsonEncode($slick_options)."'>";
		$html .= implode( '', $slides );
		$html .= '</div>';
		$html .= '</div>';

		return $html;
    }
} 
