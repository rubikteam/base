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

class RbSection extends RbControl
{
	public function __construct()
    {
    	parent::__construct();
    	$this->setControl();
    	$this->presets = array();
        $this->context = Context::getContext();
        $this->module = new Rbthemedream();
    }

    public function setControl()
    {
    	$module = new Rbthemedream();

    	$this->startControlsSection(
            'section_layout',
            array(
                'label' => $module->l('Layout'),
                'tab' => 'layout',
            )
        );

        $this->addControl(
            'rb_class',
            array(
                'label' => $module->l('Class'),
                'type' => 'text',
                'placeholder' => $module->l('Enter Class Name'),
                'section' => 'section_class',
            )
        );

        $this->addControl(
            'rb_class_container',
            array(
                'label' => $module->l('Class Container'),
                'type' => 'text',
                'placeholder' => $module->l('Enter Class Name'),
                'section' => 'section_class',
            )
        );

        $this->addControl(
            'stretch_section',
            array(
                'label' => $module->l('Stretch Section'),
                'type' => 'switcher',
                'default' => '',
                'label_on' => $module->l('Yes'),
                'label_off' => $module->l('No'),
                'return_value' => 'section-stretched',
                'prefix_class' => 'rb-',
                'force_render' => true,
                'hide_in_inner' => true,
                'description' => $module->l('Stretch the section to the full width of the page using JS.'),
            )
        );

        $this->addControl(
            'layout',
            array(
                'label' => $module->l('Content Width'),
                'type' => 'select',
                'default' => 'boxed',
                'options' => array(
                    'boxed' => $module->l('Boxed'),
                    'full_width' => $module->l('Full Width'),
                ),
                'prefix_class' => 'rb-section-',
            )
        );

        $this->addControl(
            'content_width',
            array(
                'label' => $module->l('Content Width'),
                'type' => 'slider',
                'range' => array(
                    'px' => array(
                        'min' => 500,
                        'max' => 1600,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} > .rb-container' => 'max-width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'layout' => array('boxed'),
                ),
                'show_label' => false,
                'separator' => 'none',
            )
        );

        $this->addControl(
            'gap',
            array(
                'label' => $module->l('Columns Gap'),
                'type' => 'select',
                'default' => 'default',
                'options' => array(
                    'default' => $module->l('Default'),
                    'no' => $module->l('No Gap'),
                    'narrow' => $module->l('Narrow'),
                    'extended' => $module->l('Extended'),
                    'wide' => $module->l('Wide'),
                    'wider' => $module->l('Wider'),
                ),
            )
        );

        $this->addControl(
            'height',
            array(
                'label' => $module->l('Height'),
                'type' => 'select',
                'default' => 'default',
                'options' => array(
                    'default' => $module->l('Default'),
                    'full' => $module->l('Fit To Screen'),
                    'min-height' => $module->l('Min Height'),
                ),
                'prefix_class' => 'rb-section-height-',
                'hide_in_inner' => true,
            )
        );

        $this->addControl(
            'custom_height',
            array(
                'label' => $module->l('Minimum Height'),
                'type' => 'slider',
                'default' => array(
                    'size' => 400,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1440,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} > .rb-container' => 'min-height: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'height' => array('min-height'),
                ),
                'hide_in_inner' => true,
            )
        );

        $this->addControl(
            'height_inner',
            array(
                'label' => $module->l('Height'),
                'type' => 'select',
                'default' => 'default',
                'options' => array(
                    'default' => $module->l('Default'),
                    'min-height' => $module->l('Min Height'),
                ),
                'prefix_class' => 'rb-section-height-',
                'hide_in_top' => true,
            )
        );

        $this->addControl(
            'custom_height_inner',
            array(
                'label' => $module->l('Minimum Height'),
                'type' => 'slider',
                'default' => array(
                    'size' => 400,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1440,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} > .rb-container' => 'min-height: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'height_inner' => array('min-height'),
                ),
                'hide_in_top' => true,
            )
        );

        $this->addControl(
            'column_position',
            array(
                'label' => $module->l('Column Position'),
                'type' => 'select',
                'default' => 'middle',
                'options' => array(
                    'stretch' => $module->l('Stretch'),
                    'top' => $module->l('Top'),
                    'middle' => $module->l('Middle'),
                    'bottom' => $module->l('Bottom'),
                ),
                'prefix_class' => 'rb-section-items-',
                'condition' => array(
                    'height' => array('full', 'min-height'),
                ),
            )
        );

        $this->addControl(
            'content_position',
            array(
                'label' => $module->l('Content Position'),
                'type' => 'select',
                'default' => '',
                'options' => array(
                    '' => $module->l('Default'),
                    'top' => $module->l('Top'),
                    'middle' => $module->l('Middle'),
                    'bottom' => $module->l('Bottom'),
                ),
                'prefix_class' => 'rb-section-content-',
            )
        );

        $this->addControl(
            'structure',
            array(
                'label' => $module->l('Structure'),
                'type' => 'structure',
                'default' => '10',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_background',
            array(
                'label' => $module->l('Background'),
                'tab' => 'style',
            )
        );

        $this->addGroupControl(
            'background',
            array(
                'name' => 'background',
                'types' => array('classic', 'gradient', 'video'),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'background_overlay_section',
            array(
                'label' => $module->l('Background Overlay'),
                'tab' => 'style',
                'condition' => array(
                    'background_background' => array('classic', 'gradient', 'video'),
                ),
            )
        );

        $this->addGroupControl(
            'background',
            array(
                'name' => 'background_overlay',
                'types' => array('classic', 'gradient'),
                'selector' => '{{WRAPPER}} > .rb-background-overlay',
                'condition' => array(
                    'background_background' => array('classic', 'gradient', 'video'),
                ),
            )
        );

        $this->addControl(
            'background_overlay_opacity',
            [
                'label' => $module->l('Opacity (%)'),
                'type' => 'slider',
                'default' => array(
                    'size' => .5,
                ),
                'range' => array(
                    'px' => array(
                        'max' => 1,
                        'step' => 0.01,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} > .rb-background-overlay' => 'opacity: {{SIZE}};',
                ),
                'condition' => array(
                    'background_overlay_background' => [ 'classic', 'gradient', ],
                ),
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_border',
            array(
                'label' => $module->l('Border'),
                'tab' => 'style',
            )
        );

        $this->addGroupControl(
            'border',
            array(
                'name' => 'border',
            )
        );

        $this->addControl(
            'border_radius',
            array(
                'label' => $module->l('Border Radius'),
                'type' => 'dimensions',
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}}, {{WRAPPER}} > .rb-background-overlay' =>
                    'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );


        $this->addGroupControl(
            'box-shadow',
            array(
                'name' => 'box_shadow',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_typo',
            array(
                'label' => $module->l('Typography'),
                'tab' => 'style',
            )
        );

        $this->addControl(
            'heading_color',
            array(
                'label' => $module->l('Heading Color'),
                'type' => 'color',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} > .rb-container .rb-heading-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'color_text',
            array(
                'label' => $module->l('Text Color'),
                'type' => 'color',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} > .rb-container' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'color_link',
            array(
                'label' => $module->l('Link Color'),
                'type' => 'color',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} > .rb-container a' => 'color: {{VALUE}};',
                ),
                'tab' => 'style',
                'section' => 'section_typo',
            )
        );

       	$this->addControl(
            'color_link_hover',
            array(
                'label' => $module->l('Link Hover Color'),
                'type' => 'color',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} > .rb-container a:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'text_align',
            array(
                'label' => $module->l('Text Align'),
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
                'selectors' => array(
                    '{{WRAPPER}} > .rb-container' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_advanced',
            array(
                'label' => $module->l('Advanced'),
                'tab' => 'advanced',
            )
        );

        $this->addResponsiveControl(
            'margin',
            array(
                'label' => $module->l('Margin'),
                'type' => 'dimensions',
                'size_units' => array('px', '%'),
                'allowed_dimensions' => 'vertical',
                'placeholder' => array(
                    'top' => '',
                    'right' => 'auto',
                    'bottom' => '',
                    'left' => 'auto',
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
                ),
            )
        );

        $this->addResponsiveControl(
            'padding',
            array(
                'label' => $module->l('Padding'),
                'type' => 'dimensions',
                'size_units' => array('px', 'em', '%'),
                'selectors' => array(
                    '{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

       	$this->addControl(
            'animation',
            array(
                'label' => $module->l('Entrance Animation'),
                'type' => 'animation',
                'default' => '',
                'prefix_class' => 'animated ',
                'label_block' => true,
            )
        );

        $this->addControl(
            'animation_duration',
            array(
                'label' => $module->l('Animation Duration'),
                'type' => 'select',
                'default' => '',
                'options' => array(
                    'slow' => $module->l('Slow'),
                    '' => $module->l('Normal'),
                    'fast' => $module->l('Fast'),
                ),
                'prefix_class' => 'animated-',
                'condition' => array(
                    'animation!' => '',
                ),
            )
        );

        $this->addControl(
            'css_classes',
            array(
                'label' => $module->l('CSS Classes'),
                'type' => 'text',
                'default' => '',
                'prefix_class' => '',
                'label_block' => true,
                'title' => $module->l('Add your custom class WITHOUT the dot. e.g: my-class'),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            '_section_responsive',
            array(
                'label' => $module->l('Responsive'),
                'tab' => 'advanced',
            )
        );

        $this->addControl(
            'reverse_order_mobile',
            array(
                'label' => $module->l('Reverse Columns'),
                'type' => 'switcher',
                'default' => '',
                'prefix_class' => 'rb-',
                'label_on' => $module->l('Yes'),
                'label_off' => $module->l('No'),
                'return_value' => 'reverse-mobile',
                'description' => $module->l(
                	'Reverse column order - When on mobile, the column order is reversed,
                	so the last column appears on top and vice versa.'
                ),
            )
        );

        $this->addControl(
            'heading_visibility',
            array(
                'label' => $module->l('Visibility'),
                'type' => 'heading',
                'separator' => 'before',
            )
        );

        $this->addControl(
            'responsive_description',
            array(
                'raw' => $module->l(
                	'Attention: The display settings (show/hide for mobile, tablet or desktop)
                	will only take effect once you are on the preview or live page'
                ),
                'type' => 'raw_html',
                'classes' => 'rb-control-descriptor',
            )
        );

        $this->addControl(
            'hide_desktop',
            array(
                'label' => $module->l('Hide On Desktop'),
                'type' => 'switcher',
                'default' => '',
                'prefix_class' => 'rb-',
                'label_on' => $module->l('Hide'),
                'label_off' => $module->l('Show'),
                'return_value' => 'hidden-desktop',
            )
        );

        $this->addControl(
            'hide_tablet',
            array(
                'label' => $module->l('Hide On Tablet'),
                'type' => 'switcher',
                'default' => '',
                'prefix_class' => 'rb-',
                'label_on' => $module->l('Hide'),
                'label_off' => $module->l('Show'),
                'return_value' => 'hidden-tablet',
            )
        );

        $this->addControl(
            'hide_mobile',
            array(
                'label' => $module->l('Hide On Mobile'),
                'type' => 'switcher',
                'default' => '',
                'prefix_class' => 'rb-',
                'label_on' => $module->l('Hide'),
                'label_off' => $module->l('Show'),
                'return_value' => 'hidden-phone',
            )
        );

        $this->endControlsSection();
    }

    public function createPresets()
    {
        $additional_presets = array(
            2 => array(
                array(
                    'preset' => array(33, 66),
                ),
                array(
                    'preset' => array(66, 33),
                ),
            ),
            3 => array(
                array(
                    'preset' => array(25, 25, 50),
                ),
                array(
                    'preset' => array(50, 25, 25),
                ),
                array(
                    'preset' => array(25, 50, 25),
                ),
                array(
                    'preset' => array(16, 66, 16),
                ),
            ),
        );

        foreach (range( 1, 10 ) as $columns_count) {
            $this->presets[$columns_count] = array(
                array(
                    'preset' => array(),
                ),
            );

            $preset_unit = floor(1 / $columns_count * 100);

            for ($i = 0; $i < $columns_count; $i++) {
                $this->presets[$columns_count][0]['preset'][] = $preset_unit;
            }

            if (!empty($additional_presets[$columns_count])) {
                $this->presets[ $columns_count ] = array_merge($this->presets[$columns_count], $additional_presets[$columns_count]);
            }

            foreach ($this->presets[ $columns_count ] as $preset_index => & $preset ) {
                $preset['key'] = $columns_count . $preset_index;
            }
        }
    }

    public function getPresets($columns_count = null, $preset_index = null)
    {

        if (!$this->presets) {
            $this->createPresets();
        }

        $presets = $this->presets;

        if ($columns_count !==  null) {
            $presets = $presets[$columns_count];
        }

        if ($preset_index !==  null) {
            $presets = $presets[$preset_index];
        }

        return $presets;
    }

    public function getSectionValues($instance = array())
    {
        $controls = $this->getControls();

        if (!empty($controls)) {
            foreach ($controls as $control) {
                $instance[$control['name']] = $this->getValue($control, $instance);
            }
        }

        return $instance;
    }

    public function beforeRender($instance, $element_id, $element_data = array())
    {
        $section_type = !empty($element_data['isInner']) ? 'inner' : 'top';

        $class_default = array(
            'rb-section',
            'rb-element',
            'rb-element-' . $element_id,
            'rb-' . $section_type . '-section',
        );

        if (isset($instance['rb_class']) && $instance['rb_class'] != '') {
            $rb_class = explode(' ', $instance['rb_class']);

            $class_default = array_merge($rb_class,$class_default);
        }

        $this->addRenderAttribute('wrapper', 'class', $class_default);

        foreach ($this->getClassControls() as $control) {
            if (empty($instance[$control['name']]))
                continue;

            if (!$this->isControlVisible($instance, $control))
                continue;

            $this->addRenderAttribute(
                'wrapper',
                'class',
                $control['prefix_class'] . $instance[$control['name']]
            );
        }

        if (!empty($instance['animation'])) {
            $this->addRenderAttribute(
                'wrapper',
                'data-animation',
                $instance['animation']
            );
        }

        $this->addRenderAttribute(
            'wrapper',
            'data-element_type',
            'section'
        );

        $attribute_string = $this->getRenderAttributeString('wrapper');

        $this->context->smarty->assign(array(
            'attribute_string' => $attribute_string,
            'instance' => $instance,
        ));

        return $this->module->fetch('module:rbthemedream/views/templates/section.tpl');
    }

    public function afterRender()
    {
        return "</div></div></div>";
    }

    public function getDataSection()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Section',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'columns',
    		'presets' => $this->getPresets()
    	);

    	return $data;
    }
}
