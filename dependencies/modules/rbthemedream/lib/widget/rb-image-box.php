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

class RbImageBox extends RbControl
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
            'section_image',
            array(
                'label' => $module->l('Image Box'),
                'type' => 'section',
            )
        );

        $this->addControl(
            'image',
            array(
                'label' => $module->l('Choose Image'),
                'type' => 'media',
                'default' => array(
                    'url' => _MODULE_DIR_ . 'rbthemedream/views/img/img.jpg',
                ),
                'section' => 'section_image',
            )
        );

        $this->addControl(
            'caption',
            array(
                'label' => $module->l('Alt text'),
                'type' => 'text',
                'default' => '',
                'placeholder' => $module->l('Enter your Alt about the image'),
                'title' => $module->l('Input image Alt here'),
                'section' => 'section_image',
            )
        );

        $this->addControl(
            'title_text',
            array(
                'label' => $module->l('Title & Description'),
                'type' => 'text',
                'default' => $module->l('This is the heading'),
                'placeholder' => $module->l( 'Your Title'),
                'section' => 'section_image',
                'label_block' => true,
            )
        );

        $this->addControl(
            'description_text',
            array(
                'show_label' => false,
                'label' => $module->l('Content'),
                'type' => 'wysiwyg',
                'default' => '<p>' . $module->l('I am text block. Click edit button to change this text') . '</p>',
                'section' => 'section_image',
            )
        );

        $this->addControl(
            'link',
            array(
                'label' => $module->l('Link to'),
                'type' => 'url',
                'placeholder' => $module->l('https://www.youtube.com/watch?v=Kuz0A-wvx5c'),
                'section' => 'section_image',
                'separator' => 'before',
            )
        );

        $this->addControl(
            'position',
            array(
                'label' => $module->l('Image Position'),
                'type' => 'choose',
                'default' => 'top',
                'options' => array(
                    'left' => array(
                        'title' => $module->l('Left'),
                        'icon' => 'align-left',
                    ),
                    'top' => array(
                        'title' => $module->l('Top'),
                        'icon' => 'align-center',
                    ),
                    'right' => array(
                        'title' => $module->l('Right'),
                        'icon' => 'align-right',
                    ),
                ),
                'prefix_class' => 'rb-position-',
                'toggle' => false,
                'section' => 'section_image',
            )
        );

        $this->addControl(
            'title_size',
            array(
                'label' => $module->l('Title HTML Tag'),
                'type' => 'select',
                'options' => array(
                    'h1' => $module->l('H1'),
                    'h2' => $module->l('H2'),
                    'h3' => $module->l('H3'),
                    'h4' => $module->l('H4'),
                    'h5' => $module->l('H5'),
                    'h6' => $module->l('H6'),
                    'div' => $module->l('div'),
                    'span' => $module->l('span'),
                    'p' => $module->l('p'),
                ),
                'default' => 'h3',
                'section' => 'section_image',
            )
        );

        $this->addControl(
            'view',
            array(
                'label' => $module->l('View'),
                'type' => 'hidden',
                'default' => 'traditional',
                'section' => 'section_content',
            )
        );

        $this->addControl(
            'section_style_image',
            array(
                'type'  => 'section',
                'label' => $module->l('Image'),
                'tab'   => 'style',
            )
        );

        $this->addControl(
            'image_space',
            array(
                'label' => $module->l('Image Spacing'),
                'type' => 'slider',
                'default' => array(
                    'size' => 15,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'section' => 'section_style_image',
                'tab' => 'style',
                'selectors' => [
                    '{{WRAPPER}}.rb-position-right .rb-image-box-img' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.rb-position-left .rb-image-box-img' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.rb-position-top .rb-image-box-img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            )
        );

        $this->addControl(
            'image_size',
            array(
                'label' => $module->l('Image Size'),
                'type' => 'slider',
                'default' => array(
                    'size' => 30,
                    'unit' => '%',
                ),
                'size_units' => array('%'),
                'range' => array(
                    '%' => array(
                        'min' => 5,
                        'max' => 100,
                    ),
                ),
                'section' => 'section_style_image',
                'tab' => 'style',
                'selectors' => array(
                    '{{WRAPPER}} .rb-image-box-wrapper .rb-image-box-img' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'image_opacity',
            array(
                'label' => $module->l('Opacity'),
                'type' => 'slider',
                'default' => array(
                    'size' => 1,
                ),
                'range' => array(
                    'px' => array(
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ),
                ),
                'section' => 'section_style_image',
                'tab' => 'style',
                'selectors' => array(
                    '{{WRAPPER}} .rb-image-box-wrapper .rb-image-box-img img' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->addControl(
            'hover_animation',
            array(
                'label' => $module->l('Animation'),
                'type' => 'hover_animation',
                'tab' => 'style',
                'section' => 'section_style_image',
            )
        );

        $this->addControl(
            'section_style_content',
            array(
                'type'  => 'section',
                'label' => $module->l('Content'),
                'tab'   => 'style',
            )
        );

        $this->addResponsiveControl(
            'text_align',
            array(
                'label' => $module->l('Alignment'),
                'type' => 'choose',
                'options' => array(
                    'left' => array(
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
                    'justify' => array(
                        'title' => $module->l('Justified'),
                        'icon' => 'align-justify',
                    ),
                ),
                'section' => 'section_style_content',
                'tab' => 'style',
                'selectors' => [
                    '{{WRAPPER}} .rb-image-box-wrapper' => 'text-align: {{VALUE}};',
                ],
            )
        );

        $this->addControl(
            'content_vertical_alignment',
            array(
                'label' => $module->l('Vertical Alignment'),
                'type' => 'select',
                'options' => [
                    'top' => $module->l('Top'),
                    'middle' => $module->l('Middle'),
                    'bottom' => $module->l( 'Bottom'),
                ],
                'default' => 'top',
                'section' => 'section_style_content',
                'tab' => 'style',
                'prefix_class' => 'rb-vertical-align-',
            )
        );

        $this->addControl(
            'heading_title',
            array(
                'label' => $module->l('Title'),
                'type' => 'heading',
                'section' => 'section_style_content',
                'tab' => 'style',
                'separator' => 'before',
            )
        );

        $this->addResponsiveControl(
            'title_bottom_space',
            array(
                'label' => $module->l('Title Spacing'),
                'type' => 'slider',
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'section' => 'section_style_content',
                'tab' => 'style',
                'selectors' => array(
                    '{{WRAPPER}} .rb-image-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'title_color',
            array(
                'label' => $module->l('Title Color'),
                'type' => 'color',
                'tab' => 'style',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .rb-image-box-content .rb-image-box-title' => 'color: {{VALUE}};',
                ),
                'section' => 'section_style_content',
                'scheme' => array(
                    'type' => 'color',
                    'value' => 1,
                ),
            )
        );

        $this->addGroupControl(
            'typography',
            array(
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .rb-image-box-content .rb-image-box-title',
                'tab' => 'style',
                'section' => 'section_style_content',
                'scheme' => 1,
            )
        );

        $this->addControl(
            'heading_description',
            array(
                'label' => $module->l('Description'),
                'type' => 'heading',
                'section' => 'section_style_content',
                'tab' => 'style',
                'separator' => 'before',
            )
        );

        $this->addControl(
            'description_color',
            array(
                'label' => $module->l('Description Color'),
                'type' => 'color',
                'tab' => 'style',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .rb-image-box-content .rb-image-box-description' => 'color: {{VALUE}};',
                ),
                'section' => 'section_style_content',
                'scheme' => array(
                    'type' => 'color',
                    'value' => 3,
                ),
            )
        );

        $this->addGroupControl(
            'typography',
            array(
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .rb-image-box-content .rb-image-box-description',
                'tab' => 'style',
                'section' => 'section_style_content',
                'scheme' => 3,
            )
        );
    }

    public function getDataImageBox()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Image Box',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'image-box'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
        $has_content = !empty($instance['title_text'] ) || ! empty($instance['description_text']);

        $html = '<div class="rb-image-box-wrapper">';

        if (!empty($instance['image']['url'])) {
            $this->addRenderAttribute('image', 'src', RbControl::getImage($instance['image']['url']));
            $this->addRenderAttribute('image', 'alt', Tools::safeOutput( $instance['caption']));

            if ($instance['hover_animation']) {
                $this->addRenderAttribute('image', 'class', 'rb-animation-' . $instance['hover_animation']);
            }

            $image_html = '<img ' . $this->getRenderAttributeString('image') . '>';

            if (!empty($instance['link']['url'])) {
                $target = '';

                if (!empty($instance['link']['is_external'])) {
                    $target = ' target="_blank" rel="noopener noreferrer"';
                }

                $image_html = sprintf('<a href="%s"%s>%s</a>', $instance['link']['url'], $target, $image_html);
            }

            $html .= '<figure class="rb-image-box-img">' . $image_html . '</figure>';
        }

        if ($has_content) {
            $html .= '<div class="rb-image-box-content">';

            if (!empty($instance['title_text'])) {
                $title_html = $instance['title_text'];

                if (!empty($instance['link']['url'])) {
                    $target = '';

                    if (!empty($instance['link']['is_external'])) {
                        $target = ' target="_blank" rel="noopener noreferrer"';
                    }

                    $title_html = sprintf('<a href="%s"%s>%s</a>', $instance['link']['url'], $target, $title_html);
                }

                $html .= sprintf('<%1$s class="rb-image-box-title">%2$s</%1$s>', $instance['title_size'], $title_html);
            }

            if (!empty($instance['description_text'])) {
                $html .= sprintf( '<div class="rb-image-box-description">%s</div>', $instance['description_text'] );
            }

            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }
}
