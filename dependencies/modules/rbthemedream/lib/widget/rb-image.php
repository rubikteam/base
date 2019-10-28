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

class RbImage extends RbControl
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
                'label' => $module->l('Image'),
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

        $this->addResponsiveControl(
            'align',
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
                ),
                'default' => 'center',
                'section' => 'section_image',
                'selectors' => array(
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ),
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
            'link_to',
            array(
                'label' => $module->l('Link to'),
                'type' => 'select',
                'default' => 'none',
                'section' => 'section_image',
                'options' => array(
                    'none' => $module->l('None'),
                    'file' => $module->l('Media File'),
                    'custom' => $module->l('Custom URL'),
                ),
            )
        );

        $this->addControl(
            'link',
            array(
                'label' => $module->l('Link to'),
                'type' => 'url',
                'placeholder' => $module->l('https://www.youtube.com/watch?v=Kuz0A-wvx5c'),
                'section' => 'section_image',
                'condition' => array(
                    'link_to' => 'custom',
                ),
                'show_label' => false,
            )
        );

        $this->addControl(
            'view',
            array(
                'label' => $module->l('View'),
                'type' => 'hidden',
                'default' => 'traditional',
                'section' => 'section_image',
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
            'space',
            array(
                'label' => $module->l('Size (%)'),
                'type' => 'slider',
                'tab' => 'style',
                'section' => 'section_style_image',
                'default' => array(
                    'size' => 100,
                    'unit' => '%',
                ),
                'size_units' => array('%'),
                'range' => array(
                    '%' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .rb-image img' => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'opacity',
            array(
                'label' => $module->l('Opacity'),
                'type' => 'slider',
                'tab' => 'style',
                'section' => 'section_style_image',
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
                'selectors' => array(
                    '{{WRAPPER}} .rb-image img' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->addControl(
            'hover_animation',
            array(
                'label' => $module->l('Hover Animation'),
                'type' => 'hover_animation',
                'tab' => 'style',
                'section' => 'section_style_image',
            )
        );

        $this->addGroupControl(
            'border',
            array(
                'name' => 'image_border',
                'label' => $module->l('Image Border'),
                'tab' => 'style',
                'section' => 'section_style_image',
                'selector' => '{{WRAPPER}} .rb-image img',
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
                    '{{WRAPPER}} .rb-image img' => 'border-radius:
                    {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            'box-shadow',
            array(
                'name' => 'image_box_shadow',
                'section' => 'section_style_image',
                'tab' => 'style',
                'selector' => '{{WRAPPER}} .rb-image img',
            )
        );

        $this->addControl(
            'section_style_caption',
            array(
                'type'  => 'section',
                'label' => $module->l('Caption'),
                'tab'   => 'style',
            )
        );

        $this->addControl(
            'caption_align',
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
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .widget-image-caption' => 'text-align: {{VALUE}};',
                ),
                'tab' => 'style',
                'section' => 'section_style_caption',
            )
        );

        $this->addControl(
            'text_color',
            array(
                'label' => $module->l('Text Color'),
                'type' => 'color',
                'tab' => 'style',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
                ),
                'section' => 'section_style_caption',
                'scheme' => array(
                    'type' => 'color',
                    'value' => 3,
                ),
            )
        );

        $this->addGroupControl(
            'typography',
            array(
                'name' => 'caption_typography',
                'selector' => '{{WRAPPER}} .widget-image-caption',
                'tab' => 'style',
                'section' => 'section_style_caption',
                'scheme' => 3,
            )
        );
    }

    public function getDataImage()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Image',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'img'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
        if (empty($instance['image']['url'])) {
            return;
        }

        $has_caption = ! empty($instance['caption']);
        $image_html = '<div class="rb-image' . (!empty($instance['shape']) ? ' rb-image-shape-' . $instance['shape'] : '') . '">';
        $image_class_html = !empty($instance['hover_animation']) ? ' class="rb-animation-' . $instance['hover_animation'] . '"' : '';

        $image_html .= sprintf(
            '<img src="%s" alt="%s"%s />',
            Tools::safeOutput(RbControl::getImage($instance['image']['url'])),
            Tools::safeOutput($instance['caption']),
            $image_class_html
        );

        $link = $this->getLinkUrl($instance);

        if ($link) {
            $target = '';

            if (!empty( $link['is_external'])) {
                $target = ' target="_blank" rel="noopener noreferrer"';
            }
            $image_html = sprintf('<a href="%s"%s>%s</a>', $link['url'], $target, $image_html);
        }

        $image_html .= '</div>';

        return $image_html;
    }

    public function getLinkUrl($instance)
    {
        if ('none' === $instance['link_to']) {
            return false;
        }

        if ('custom' === $instance['link_to']) {
            if (empty($instance['link']['url'])) {
                return false;
            }
            
            return $instance['link'];
        }

        return array(
            'url' => $instance['image']['url'],
        );
    }
}
