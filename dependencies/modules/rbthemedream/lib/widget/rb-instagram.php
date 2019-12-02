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

class RbInstagram extends RbControl
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
            'section_instagram',
            array(
                'label' => $module->l('Instagram feed'),
                'type' => 'section',
            )
        );

        $this->addControl(
            'instagram_description',
            array(
                'raw' => $module->l('You can get token there: ').'<a target="_blank" href="https://elfsight.com/service/get-instagram-access-token/">'.$module->l('token generator ').'</a>',
                'type' => 'raw_html',
                'section' => 'section_instagram',
                'classes' => 'rb-control-descriptor',
            )
        );

        $this->addControl(
            'instagram_token',
            array(
                'label' => $module->l('Access Token'),
                'type' => 'text',
                'section' => 'section_instagram',
                'placeholder' => $module->l('Enter your token'),
                'default' => '',
                'label_block' => true,
            )
        );

        $this->addControl(
            'instagram_limit',
            array(
                'label' => $module->l('Limit'),
                'type' => 'number',
                'description' => $module->l('An integer that indicates the amount of photos to be feed'),
                'min' => 1,
                'default' => 10,
                'section' => 'section_instagram',
            )
        );

        $this->addControl(
            'instagram_linked',
            array(
                'label' => $module->l('Linked'),
                'type' => 'select',
                'section' => 'section_instagram',
                'description' => $module->l('Value that indicates whether or not the images should be linked to their page on Instagram'),
                'options' => array(
                    'no' => $module->l('No'),
                    'yes' => $module->l('Yes'),
                ),
                'default' => '1',
            )
        );


        $this->addControl(
            'instagram_info',
            array(
                'label' => $module->l('Hover info'),
                'type' => 'select',
                'section' => 'section_instagram',
                'description' => $module->l('Show info like comments, likes and date on hover'),
                'options' => array(
                    'none' => $module->l( 'No'),
                    '' => $module->l('Yes'),
                ),
                'default' => 'block',
                'selectors' => array(
                    '{{WRAPPER}} .il-item-inner:hover .il-photo__metainner' => 'display: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'instagram_view',
            array(
                'label' => $module->l('View'),
                'type' => 'select',
                'section' => 'section_instagram',
                'options' => array(
                    'slider' => $module->l('Slider'),
                    'grid' => $module->l('Grid'),
                ),
                'default' => 'grid',
            )
        );

        $slidesToShow = array(
            12 => 1,
            6 => 2,
            4 => 3,
            3 => 4,
            2 => 6,
            1 => 12,
        );

        $this->addResponsiveControl(
            'photos_to_show',
            array(
                'label' => $module->l('Show per line'),
                'type' => 'select',
                'label_block' => true,
                'section' => 'section_instagram',
                'default' => '6',
                'options' => $slidesToShow,
                'condition' => array(
                    'instagram_view' => 'grid',
                ),
            )
        );

        $slides_to_show = range(1, 10);
        $slides_to_show = array_combine($slides_to_show, $slides_to_show);

        $this->addResponsiveControl(
            'photos_to_show_s',
            array(
                'label' => $module->l('Show per line'),
                'type' => 'select',
                'label_block' => true,
                'section' => 'section_instagram',
                'default' => '6',
                'options' =>  $slides_to_show,
                'condition' => array(
                    'instagram_view' => 'slider',
                ),
            )
        );

        $this->addControl(
            'photos_to_display',
            array(
                'label' => $module->l('Photo On Row (992-4,768-3)'),
                'type' => 'text',
                'section' => 'section_instagram',
                'condition' => array(
                    'instagram_view' => 'slider',
                ),
            )
        );

        $this->addControl(
            'slides_to_scroll',
            array(
                'label' => $module->l('Slides to Scroll'),
                'type' => 'select',
                'default' => '2',
                'section' => 'section_instagram',
                'options' => $slides_to_show,
                'condition' => array(
                    'slides_to_show!' => '1',
                    'instagram_view' => 'slider',
                ),
            )
        );

        $this->addControl(
            'navigation',
            array(
                'label' => $module->l('Navigation'),
                'type' => 'select',
                'default' => 'both',
                'section' => 'section_instagram',
                'options' => array(
                    'both' => $module->l('Arrows and Dots'),
                    'arrows' => $module->l('Arrows'),
                    'dots' => $module->l('Dots'),
                    'none' => $module->l('None'),
                ),
                'condition' => array(
                    'instagram_view' => 'slider',
                ),
            )
        );

        $this->addControl(
            'pause_on_hover',
            array(
                'label' => $module->l('Pause on Hover'),
                'type' => 'select',
                'default' => 'yes',
                'section' => 'section_instagram',
                'options' => array(
                    'yes' => $module->l('Yes'),
                    'no' => $module->l('No'),
                ),
                'condition' => array(
                    'instagram_view' => 'slider',
                ),
            )
        );

        $this->addControl(
            'autoplay',
            array(
                'label' => $module->l('Autoplay'),
                'type' => 'select',
                'default' => 'yes',
                'section' => 'section_instagram',
                'options' => array(
                    'yes' => $module->l('Yes'),
                    'no' => $module->l('No'),
                ),
                'condition' => array(
                    'instagram_view' => 'slider',
                ),
            )
        );

        $this->addControl(
            'autoplay_speed',
            array(
                'label' => $module->l('Autoplay Speed'),
                'type' => 'number',
                'default' => 5000,
                'section' => 'section_instagram',
                'condition' => array(
                    'instagram_view' => 'slider',
                ),
            )
        );

        $this->addControl(
            'infinite',
            array(
                'label' => $module->l('Infinite Loop'),
                'type' => 'select',
                'default' => 'yes',
                'section' => 'section_instagram',
                'options' => array(
                    'yes' => $module->l('Yes'),
                    'no' => $module->l('No'),
                ),
                'condition' => array(
                    'instagram_view' => 'slider',
                ),
            )
        );

        $this->addControl(
            'effect',
            array(
                'label' => $module->l('Effect'),
                'type' => 'select',
                'default' => 'slide',
                'section' => 'section_instagram',
                'options' => array(
                    'slide' => $module->l('Slide'),
                    'fade' => $module->l('Fade'),
                ),
                'condition' => array(
                    'slides_to_show' => '1',
                    'instagram_view' => 'slider',
                ),
            )
        );

        $this->addControl(
            'speed',
            array(
                'label' => $module->l('Animation Speed'),
                'type' => 'number',
                'default' => 500,
                'section' => 'section_instagram',
                'condition' => array(
                    'instagram_view' => 'slider',
                ),
            )
        );

        $this->addControl(
            'section_style',
            array(
                'label' => $module->l('Instagram photo'),
                'type' => 'section',
                'tab' => 'style',
            )
        );

        $this->addControl(
            'instagram_photo_height',
            array(
                'label' => $module->l('Max height'),
                'type' => 'text',
                'tab' => 'style',
                'section' => 'section_style',
                'description' => $module->l('Helpful when you use various aspect ratio for images'),
                'default' => '100%',
                'selectors' => array(
                    '{{WRAPPER}} .il-item a' => 'max-height: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'instagram_text_color',
            array(
                'label' => $module->l('Text Color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .il-photo__meta' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'instagram_overlay_color',
            array(
                'label' => $module->l('Overlay background'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .il-photo__meta' => 'background: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'instagram_padding',
            array(
                'label' => $module->l('Photo padding'),
                'type' => 'dimensions',
                'size_units' => array('px', 'em', '%'),
                'tab' => 'style',
                'section' => 'section_style',
                'selectors' => array(
                    '{{WRAPPER}} .il-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .rb-instagram' => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right:-{{RIGHT}}{{UNIT}} ;',
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
                    '{{WRAPPER}} .rb-instagram-carousel-wrapper .slick-slider .slick-prev:before,
                    {{WRAPPER}} .rb-instagram-carousel-wrapper .slick-slider .slick-next:before' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .rb-instagram-carousel-wrapper .slick-slider .slick-prev,
                    {{WRAPPER}} .rb-instagram-carousel-wrapper .slick-slider .slick-next' => 'background: {{VALUE}};',
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
            'dots_color',
            array(
                'label' => $module->l('Dots Color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_navigation',
                'selectors' => array(
                    '{{WRAPPER}} .rb-instagram-carousel-wrapper .rb-instagram-carousel .slick-dots
                    li button:before' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'navigation' => array('dots', 'both'),
                ),
            )
        );
    }

    public function getDataInstagram()
    {
        $controls = $this->getControls();

        $data = array(
            'title' => 'Instagram',
            'controls' => $controls,
            'tabs_controls' => $this->tabs_controls,
            'categories' => array('basic'),
            'keywords' => '',
            'icon' => 'instagram'
        );

        return $data;
    }

    public function rbRender($instance = array())
    {
        if ($instance['instagram_view'] == 'grid') {
            $class = 'col-'.$instance['photos_to_show_mobile']. ' col-md-'.$instance['photos_to_show_tablet'].
            ' col-lg-'.$instance['photos_to_show'];

            $instagram_options = array(
                'token' => $instance['instagram_token'],
                'limit' => $instance['instagram_limit'],
                'linked' => ( 'yes' === $instance['instagram_linked'] ),
                'class' => $class,
            );
        } else {
            $is_slideshow = '1' === $instance['photos_to_show_s'];
            $show_dots = (in_array($instance['navigation'], array('dots', 'both')));
            $show_arrows = (in_array($instance['navigation'], array('arrows', 'both')));

            if (isset($instance['photos_to_display']) &&
                $instance['photos_to_display'] != ''
            ) {
                $configs = explode(',', $instance['photos_to_display']);

                foreach ($configs as $key_cf => $config) {
                    $config = explode('-', $config);

                    $config_obj[] = array(
                        'breakpoint' => abs($config[0]),
                        'settings' => array(
                            'slidesToShow' => abs($config[1]),
                            'slidesToScroll' => abs($config[1]),
                        )
                    );
                }
            } else {
                $config_obj = array(
                    array(
                        'breakpoint' => 992,
                        'settings' => array(
                            'slidesToShow' => abs($instance['photos_to_show_s_tablet']),
                            'slidesToScroll' => abs($instance['slides_to_scroll']),
                        ),
                    ),
                    array(
                        'breakpoint' => 576,
                        'settings' => array(
                            'slidesToShow' => abs($instance['photos_to_show_s_mobile']),
                            'slidesToScroll' => abs($instance['slides_to_scroll']),
                        ),
                    ),
                );
            }

            $slick_options = array(
                'responsive' => $config_obj,
                'slidesToShow' => abs(intval($instance['photos_to_show_s'])),
                'slidesToScroll' => abs($instance['slides_to_scroll']),
                'autoplaySpeed' => abs(intval($instance['autoplay_speed'])),
                'autoplay' => ('yes' === $instance['autoplay']),
                'infinite' => ('yes' === $instance['infinite']),
                'pauseOnHover' => ('yes' === $instance['pause_on_hover']),
                'speed' => abs(intval($instance['speed'])),
                'arrows' => $show_arrows,
                'dots' => $show_dots,
            );

            $carousel_classes = array('rb-instagram-carousel');

            if ($show_arrows) {
                $carousel_classes[] = 'slick-arrows-' . $instance['arrows_position'];
            }

            if ($show_dots) {
                $carousel_classes[] = 'slick-dots-' . $instance['dots_position'];
            }

            if ( ! $is_slideshow ) {
                $slick_options['slidesToScroll'] = abs(intval($instance['photos_to_show_s']));
            } else {
                $slick_options['fade'] = ('fade' === $instance['effect']);
            }

            $instagram_options = array(
                'token' => $instance['instagram_token'],
                'limit' => $instance['instagram_limit'],
                'linked' => ('yes' === $instance['instagram_linked'] ),
                'class' => '',
            );
        }

        $html = '';

        if ($instance['instagram_view'] == 'grid') {
            $html .= '<div class="rb-instagram row" data-options=\''.Tools::jsonEncode($instagram_options).'\'></div>';
        }

        if ($instance['instagram_view'] == 'slider') {
            $html .= '<div class="rb-instagram-carousel-wrapper rb-slick-slider">';
            $html .= '<div class="rb-instagram '.implode(' ', $carousel_classes).
            '" data-options=\''.Tools::jsonEncode($instagram_options).'\'
            data-slider_options=\''.Tools::jsonEncode($slick_options).'\'></div>';
            $html .= '</div>';
        }

        return $html;
    }
}
