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

class RbTestimonial extends RbControl
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
            'section_testimonial',
            array(
                'label' => $module->l('Testimonial'),
                'type' => 'section',
            )
        );

        $this->addControl(
            'testimonials_list',
            array(
                'label' => '',
                'type' => 'repeater',
                'default' => array(),
                'section' => 'section_testimonial',
                'fields' => array(
                    array(
                        'name' => 'name',
                        'label' => $module->l('Name'),
                        'type' => 'text',
                        'default' => 'Ms Diep',
                    ),
                    array(
                        'name' => 'job',
                        'label' => $module->l('Job'),
                        'type' => 'text',
                        'default' => 'Designer',
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
                        'label' => $module->l('Content'),
                        'type' => 'textarea',
                        'rows' => '10',
                        'name' => 'content',
                        'default' => 'Lorem ipsum dolor sit amet,
                        consectetur adipiscing elit.',
                    )
                ),
                'title_field' => 'name',
            )
        );

        $this->addControl(
            'section_additional_options',
            array(
                'label' => $module->l('Settings'),
                'type' => 'section',
            )
        );

        $this->addControl(
            'testimonial_image_position',
            array(
                'label' => $module->l('Image Position'),
                'type' => 'select',
                'default' => 'aside',
                'section' => 'section_additional_options',
                'options' => array(
                    'aside' => $module->l('Aside to name'),
                    'top' => $module->l('Top (above content)'),
                ),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'testimonial_alignment',
            array(
                'label' => $module->l('Alignment'),
                'type' => 'choose',
                'default' => 'center',
                'section' => 'section_additional_options',
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
            'section_style_testimonial_box',
            array(
                'label' => $module->l('Testimonial box'),
                'type' => 'section',
                'tab' => 'style',
            )
        );

        $this->addControl(
            'background_color',
            array(
                'label' => $module->l('Background Color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_testimonial_box',
                'scheme' => array(
                    'type' => 'color',
                    'value' => 4,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .rb-testimonial-wrapper' =>
                    'background-color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            'border',
            array(
                'name' => 'testimonial_border',
                'label' => $module->l('Image Border'),
                'tab' => 'style',
                'section' => 'section_style_testimonial_box',
                'selector' => '{{WRAPPER}} .rb-testimonial-wrapper',
            )
        );

        $this->addControl(
            'testimonial_border_radius',
            array(
                'label' => $module->l('Border Radius'),
                'type' => 'dimensions',
                'size_units' => array('px', '%'),
                'tab' => 'style',
                'section' => 'section_style_testimonial_box',
                'selectors' => array(
                    '{{WRAPPER}} .rb-testimonial-wrapper' =>
                    'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'testimonial_padding',
            array(
                'label' => $module->l('Box padding'),
                'type' => 'dimensions',
                'size_units' => array('px', 'em', '%'),
                'tab' => 'style',
                'section' => 'section_style_testimonial_box',
                'selectors' => array(
                    '{{WRAPPER}} .rb-testimonial-wrapper' =>
                    'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'testimonial_margin',
            array(
                'label' => $module->l('Box Margin'),
                'type' => 'dimensions',
                'size_units' => array('px', 'em', '%'),
                'tab' => 'style',
                'section' => 'section_style_testimonial_box',
                'selectors' => array(
                    '{{WRAPPER}} .rb-testimonial-wrapper' =>
                    'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            'box-shadow',
            array(
                'name' => 'testimonial_box_shadow',
                'section' => 'section_style_testimonial_box',
                'tab' => 'style',
                'selector' => '{{WRAPPER}} .rb-testimonial-wrapper',
            )
        );

        $this->addControl(
            'section_style_testimonial_content',
            array(
                'label' => $module->l('Content'),
                'type' => 'section',
                'tab' => 'style',
            )
        );

        $this->addControl(
            'content_content_color',
            array(
                'label' => $module->l('Content Color'),
                'type' => 'color',
                'scheme' => array(
                    'type' => 'color',
                    'value' => 3,
                ),
                'tab' => 'style',
                'section' => 'section_style_testimonial_content',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .rb-testimonial-content' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            'typography',
            array(
                'name' => 'content_typography',
                'label' => $module->l('Typography'),
                'scheme' => 3,
                'tab' => 'style',
                'section' => 'section_style_testimonial_content',
                'selector' => '{{WRAPPER}} .rb-testimonial-content',
            )
        );

        $this->addControl(
            'section_style_testimonial_image',
            array(
                'label' => $module->l('Image'),
                'type' => 'section',
                'tab' => 'style',
            )
        );

        $this->addControl(
            'image_size',
            array(
                'label' => $module->l('Image Size'),
                'type' => 'slider',
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 20,
                        'max' => 200,
                    ),
                ),
                'section' => 'section_style_testimonial_image',
                'tab' => 'style',
                'selectors' => array(
                    '{{WRAPPER}} .rb-testimonial-wrapper .rb-testimonial-image img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            'border',
            array(
                'name' => 'image_border',
                'tab' => 'style',
                'section' => 'section_style_testimonial_image',
                'selector' => '{{WRAPPER}} .rb-testimonial-wrapper
                .rb-testimonial-image img',
            )
        );

        $this->addControl(
            'image_border_radius',
            array(
                'label' => $module->l('Border Radius'),
                'type' => 'dimensions',
                'size_units' => array('px', '%'),
                'tab' => 'style',
                'section' => 'section_style_testimonial_image',
                'selectors' => array(
                    '{{WRAPPER}} .rb-testimonial-wrapper .rb-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'section_style_testimonial_name',
            array(
                'label' => $module->l('Name'),
                'type' => 'section',
                'tab' => 'style',
            )
        );

        $this->addControl(
            'name_text_color',
            array(
                'label' => $module->l('Text Color'),
                'type' => 'color',
                'scheme' => array(
                    'type' => 'color',
                    'value' => 1,
                ),
                'tab' => 'style',
                'section' => 'section_style_testimonial_name',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .rb-testimonial-name' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            'typography',
            array(
                'name' => 'name_typography',
                'label' => $module->l('Typography'),
                'scheme' => 1,
                'tab' => 'style',
                'section' => 'section_style_testimonial_name',
                'selector' => '{{WRAPPER}} .rb-testimonial-name',
            )
        );

        $this->addControl(
            'section_style_testimonial_job',
            array(
                'label' => $module->l('Job'),
                'type' => 'section',
                'tab' => 'style',
            )
        );

        $this->addControl(
            'job_text_color',
            array(
                'label' => $module->l('Text Color'),
                'type' => 'color',
                'scheme' => array(
                    'type' => 'color',
                    'value' => 2,
                ),
                'tab' => 'style',
                'section' => 'section_style_testimonial_job',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .rb-testimonial-job' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addGroupControl(
            'typography',
            array(
                'name' => 'job_typography',
                'label' => $module->l('Typography'),
                'scheme' => 2,
                'tab' => 'style',
                'section' => 'section_style_testimonial_job',
                'selector' => '{{WRAPPER}} .rb-testimonial-job',
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
                    'middle' => $module->l('Middle'),
                    'above' => $module->l('Above'),
                ),
                'condition' => array(
                    'navigation' => array('arrows', 'both'),
                ),
            )
        );

        $this->addControl(
            'arrows_position_top',
            array(
                'label' => $module->l('Arrows Top Position'),
                'type' => 'number',
                'section' => 'section_style_navigation',
                'tab' => 'style',
                'default' => '-20',
                'min' => '-100',
                'condition' => array(
                    'arrows_position' => array('above'),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .slick-arrows-above .slick-arrow' => 'top: {{VALUE}}px;',
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
                    '{{WRAPPER}} .rb-testimonial-carousel-wrapper .slick-slider
                    .slick-prev:before, {{WRAPPER}}
                    .rb-testimonial-carousel-wrapper .slick-slider
                    .slick-next:before' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .rb-testimonial-carousel-wrapper .slick-slider .slick-prev, {{WRAPPER}} .rb-testimonial-carousel-wrapper .slick-slider .slick-next' => 'background: {{VALUE}};',
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
            'dots_color',
            array(
                'label' => $module->l('Dots Color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_navigation',
                'selectors' => array(
                    '{{WRAPPER}} .rb-testimonial-carousel-wrapper
                    .rb-testimonial-carousel .slick-dots li button:before' =>
                    'color: {{VALUE}};',
                ),
                'condition' => array(
                    'navigation' => array('dots', 'both'),
                ),
            )
        );
    }

    public function getDataTestimonial()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Testimonial',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'testimonial'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
        if (empty($instance['testimonials_list']))
            return;

        $is_slideshow = '1' === $instance['slides_to_show'];
        $is_rtl = ( 'rtl' === $instance['direction'] );
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

        $carousel_classes = array('rb-testimonial-carousel');

        if ($show_arrows) {
            $carousel_classes[] = 'slick-arrows-' . $instance['arrows_position'];
        }

        if (!$is_slideshow) {
            $slick_options['slidesToScroll'] = abs(intval($instance['slides_to_scroll']));
        } else {
            $slick_options['fade'] = ('fade' === $instance['effect']);
        }

        $testimonial_alignment = $instance['testimonial_alignment'] ? ' rb-testimonial-text-align-' . $instance['testimonial_alignment'] : '';
        $testimonial_image_position = $instance['testimonial_image_position'] ? ' rb-testimonial-image-position-' . $instance['testimonial_image_position'] : '';

        $html = '<div class="rb-testimonial-carousel-wrapper rb-slick-slider" dir="'.$direction.'">';
        $html .= '<div class="'.implode(' ', $carousel_classes).'" data-slider_options="'.Tools::jsonEncode($slick_options).'">';

        foreach ($instance['testimonials_list'] as $item) {
            $html .= '<div><div class="slick-slide-inner">';
            $has_image = false;

            if ('' !== $item['image']['url']) {
                $image_url = $item['image']['url'];
                $has_image = ' rb-has-image';
            }

            $html .= ' <div class="rb-testimonial-wrapper'.$testimonial_alignment.'">';
            $rb_has_image = '';

            if (isset($image_url) && $instance['testimonial_image_position'] == 'top') {
                if ($has_image) {
                    $rb_has_image = $has_image;
                }    

                $html .= '<div class="rb-testimonial-meta'.$rb_has_image.$testimonial_image_position.'">
                <div class="rb-testimonial-image"><img src="'.Tools::safeOutput($image_url).'"
                alt="'.Tools::safeOutput("imagealt").'" /></div></div>';
            }

            if (!empty($item['content'])) {
                $html .= '<div class="rb-testimonial-content">'.$item['content'].'</div>';
            }

            $html .= '<div class="rb-testimonial-meta'.$rb_has_image.$testimonial_image_position.'">
            <div class="rb-testimonial-meta-inner">';

            if (isset($image_url) && $instance['testimonial_image_position'] == 'aside') {
                $html .= '<div class="rb-testimonial-image"><img src="'.Tools::safeOutput($image_url).'" alt="'.
                Tools::safeOutput('imagealt').'" /></div>';
                $html .= '<div class="rb-testimonial-details">';

                if (!empty($item['name'])) {
                    $html .= '<div class="rb-testimonial-name">'.$item['name'].'</div>';
                }

                if (!empty($item['job'])) {
                    $html .= '<div class="rb-testimonial-job">'.$item['job'].'</div>';
                }

                $html .= '</div>';
            }

            $html .= '</div></div></div></div></div>';
        }

        $html .= '</div></div>';

        return $html;
    }
}
