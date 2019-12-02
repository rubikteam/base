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

class RbBlog extends RbControl
{
	public $editMode = false;
	public $status = 1;

	public function __construct()
    {
    	parent::__construct();
    	$this->context = Context::getContext();

    	if(!Module::isEnabled('rbthemeblog')) {
            $this->status = 0;
        }

    	if (isset($this->context->controller->controller_name) &&
    		$this->context->controller->controller_name == 'AdminRbthemedreamLive'
    	) {
            $this->editMode = true;
        }

    	$this->setControl();
    }

    public function setControl()
    {
    	$module = new Rbthemedream();
    	$slidesToShow = range(1, 12);
        $slidesToShow = array_combine($slidesToShow, $slidesToShow);

        $slidesToShowG = array(
            12 => 1,
            6 => 2,
            4 => 3,
            3 => 4,
            2 => 6,
            1 => 12,
        );

        $postsSourceOptions['lp'] = $module->l('Latest posts');
        $postsSourceOptions['fp'] = $module->l('Featured posts');

        $available_categories = RbBlogCategory::getCategories(
            $this->context->language->id,
            true,
            false
        );

        if (!empty($available_categories)) {
            foreach ($available_categories as &$category) {
                $category['name'] = 'Category: '.$category['name'];

                if($category['is_child'])
                    $category['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$category['name'];

                $postsSourceOptions[$category['id']] = $category['name'];
            }
        }

        $this->addPresControl(array(
        	'section_pswidget_options' => array(
                'label' => $module->l('Widget settings'),
                'type' => 'section',
            ),
            'posts_source' => array(
                'label' => $module->l('Posts source'),
                'type' => 'select',
                'default' => 'lp',
                'label_block' => true,
                'section' => 'section_pswidget_options',
                'options' => $postsSourceOptions,
            ),
            'posts_limit' => array(
                'label' => $module->l('Limit'),
                'type' => 'number',
                'default' => '10',
                'min' => '1',
                'section' => 'section_pswidget_options',
            ),
            'view' => array(
                'label' => $module->l('View'),
                'type' => 'select',
                'default' => 'grid',
                'condition' => array(
                    'view!' => 'default',
                ),
                'section' => 'section_pswidget_options',
                'options' => array(
                    'carousel' => $module->l('Carousel'),
                    'grid' => $module->l('Grid'),
                ),
            ),
            'slides_to_show' => array(
                'responsive' => true,
                'label' => $module->l('Show per line'),
                'type' => 'select',
                'default' => '6',
                'label_block' => true,
                'section' => 'section_pswidget_options',
                'options' => $slidesToShow,
                'condition' => array(
                    'view' => 'carousel',
                ),
            ),
            'slides_to_show_g' => array(
                'responsive' => true,
                'label' => $module->l('Show per line'),
                'type' => 'select',
                'default' => '3',
                'label_block' => true,
                'section' => 'section_pswidget_options',
                'options' => $slidesToShowG,
                'condition' => array(
                    'view' => 'grid',
                ),
            ),
            'blogs_to_display' => array(
                'label' => $module->l('Photo On Row (992-4,768-3)'),
                'type' => 'text',
                'condition' => array(
                    'view' => 'carousel',
                ),
                'section' => 'section_pswidget_options',
            ),
            'navigation' => array(
                'label' => $module->l('Navigation'),
                'type' => 'select',
                'default' => 'both',
                'condition' => array(
                    'view' => 'carousel',
                ),
                'section' => 'section_pswidget_options',
                'options' => array(
                    'both' => $module->l('Arrows and Dots'),
                    'arrows' => $module->l('Arrows'),
                    'dots' => $module->l('Dots'),
                    'none' => $module->l('None'),
                ),
            ),
            'pause_on_hover' => array(
                'label' => $module->l('Pause on Hover'),
                'type' => 'select',
                'default' => 'yes',
                'condition' => array(
                    'view' => 'carousel',
                ),
                'section' => 'section_pswidget_options',
                'options' => array(
                    'yes' => $module->l('Yes'),
                    'no' => $module->l('No'),
                ),
            ),
            'autoplay' => array(
                'label' => $module->l('Autoplay'),
                'type' => 'select',
                'default' => 'yes',
                'condition' => array(
                    'view' => 'carousel',
                ),
                'section' => 'section_pswidget_options',
                'options' => array(
                    'yes' => $module->l('Yes'),
                    'no' => $module->l('No'),
                ),
            ),
            'autoplay_speed' => array(
                'label' => $module->l( 'Autoplay Speed'),
                'type' => 'number',
                'default' => 5000,
                'section' => 'section_pswidget_options',
                'condition' => array(
                    'view' => 'carousel',
                ),
            ),
            'infinite' => array(
                'label' => $module->l('Infinite Loop'),
                'type' => 'select',
                'default' => 'yes',
                'condition' => array(
                    'view' => 'carousel',
                ),
                'section' => 'section_pswidget_options',
                'options' => array(
                    'yes' => $module->l('Yes'),
                    'no' => $module->l('No'),
                ),
            ),
            'section_style_navigation' => array(
                'label' => $module->l('Navigation'),
                'type' => 'section',
                'tab' => 'style',
            ),
            'arrows_color' => array(
                'label' => $module->l('Arrows Color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_navigation',
                'selectors' => array(
                    '{{WRAPPER}} .slick-arrow' => 'color: {{VALUE}};',
                ),
            ),
            'arrows_bg_color' => array(
                'label' => $module->l('Arrows background'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_navigation',
                'selectors' => array(
                    '{{WRAPPER}} .slick-arrow' => 'background: {{VALUE}};',
                ),
            ),
            'dots_color' => array(
                'label' => $module->l('Dots color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_navigation',
                'condition' => array(
                    'navigation' => array('dots', 'both'),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .slick-dots li button:before' => 'color: {{VALUE}};',
                ),
            ),
            'section_style_post' => array(
                'label' => $module->l('Post'),
                'type' => 'section',
                'tab' => 'style',
            ),
            'post_margin' => array(
                'label' => $module->l('Box spacing'),
                'type' => 'slider',
                'tab' => 'style',
                'section' => 'section_style_post',
                'default' => array(
                    'size' => 1,
                    'unit' => 'rem',
                ),
                'range' => array(
                    'rem' => array(
                        'min' => 0,
                        'max' => 4,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .simpleblog-posts-column' => 'padding:  {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .simpleblog-posts' => 'margin: 0 -{{SIZE}}{{UNIT}};',
                ),
            ),
            'post_padding' => array(
                'label' => $module->l('Box padding'),
                'type' => 'slider',
                'tab' => 'style',
                'section' => 'section_style_post',
                'default' => array(
                    'size' => 0,
                    'unit' => 'rem',
                ),
                'range' => array(
                    'rem' => array(
                        'min' => 0,
                        'max' => 4,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .post-item' => 'padding: {{SIZE}}{{UNIT}};',
                ),
            ),
            'post_bg_color' => array(
                'label' => $module->l('Background color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_post',
                'selectors' => array(
                    '{{WRAPPER}} .post-item' => 'background: {{VALUE}};',
                ),
            ),
            'post_title_color' => array(
                'label' => $module->l('Title color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_post',
                'selectors' => array(
                    '{{WRAPPER}} .post-title a' => 'color: {{VALUE}};',
                ),
            ),
            'post_text_color' => array(
                'label' => $module->l('Text color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_post',
                'selectors' => array(
                    '{{WRAPPER}} .post-item' => 'color: {{VALUE}};',
                ),
            ),
            'border' => array(
                'group_type_control' => 'border',
                'name' => 'post_border',
                'label' => $module->l('Border'),
                'tab' => 'style',
                'placeholder' => '1px',
                'default' => '1px',
                'section' => 'section_style_post',
                'selector' => '{{WRAPPER}} .post-item',
            ),
            'box-shadow' => array(
                'group_type_control' => 'box-shadow',
                'name' => 'post_box_shadow',
                'label' => $module->l('Box shadow'),
                'tab' => 'style',
                'placeholder' => '1px',
                'default' => '1px',
                'section' => 'section_style_post',
                'selector' => '{{WRAPPER}} .post-item',
            ),
            'section_style_post_h' => array(
                'label' => $module->l('Post - hover'),
                'type' => 'section',
                'tab' => 'style',
            ),
            'post_bg_color_h' => array(
                'label' => $module->l('Product box bg color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_post_h',
                'selectors' => array(
                    '{{WRAPPER}} .post-item:hover' => 'background: {{VALUE}};',
                ),
            ),
            'post_title_color_h' => array(
                'label' => $module->l('Title color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_post_h',
                'selectors' => array(
                    '{{WRAPPER}} .post-item:hover .post-title a' => 'color: {{VALUE}};',
                ),
            ),
            'post_text_color_h' => array(
                'label' => $module->l('Text color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_post_h',
                'selectors' => array(
                    '{{WRAPPER}} .post-item:hover' => 'color: {{VALUE}};',
                ),
            ),
            'border_h' => array(
                'label' => $module->l('Border color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style_post_h',
                'selectors' => array(
                    '{{WRAPPER}} .post-item:hover' => 'border-color: {{VALUE}};',
                ),
            ),
            'box-shadow_h' => array(
                'group_type_control' => 'box-shadow',
                'name' => 'product_box_shadow_h',
                'label' => $module->l('Box shadow'),
                'tab' => 'style',
                'placeholder' => '1px',
                'default' => '1px',
                'section' => 'section_style_post_h',
                'selector' => '{{WRAPPER}} .post-item:hover',
            ),
        ));
    }

    public function getDataBlog()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Blog',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('prestashop'),
    		'keywords' => '',
    		'icon' => 'blog'
    	);
    	
    	return $data;
    }


    public function rbRender($instance = array())
    {
        if (Tools::getIsset('controller') &&
            Tools::getValue('controller') == 'index'
        ) {
            $optionsSource = $this->getWidgetValues($instance);
            $options = array();
            $classes = array('');
            $source = $optionsSource['posts_source'];

            if ($source == 'lp') {
                $posts = $this->preparePosts((int)$optionsSource['posts_limit']);
            } elseif ($source == 'fp'){
                $posts = $this->preparePosts((int)$optionsSource['posts_limit'], null, true);
            } else{
                $posts = $this->preparePosts((int)$optionsSource['posts_limit'], $source);
            }

            if ($optionsSource['view'] == 'carousel'){
                $show_dots = (in_array( $optionsSource['navigation'], array('dots', 'both')));
                $show_arrows = (in_array( $optionsSource['navigation'], array('arrows', 'both')));

                if (isset($instance['blogs_to_display']) &&
                    $instance['blogs_to_display'] != ''
                ) {
                    $configs = explode(',', $instance['blogs_to_display']);

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
                                'slidesToShow' => abs($optionsSource['slides_to_show_tablet']),
                                'slidesToScroll' => abs(2),
                            ),
                        ),
                        array(
                            'breakpoint' => 576,
                            'settings' => array(
                                'slidesToShow' => abs($optionsSource['slides_to_show_mobile']),
                                'slidesToScroll' => abs(2),
                            ),
                        ),
                    );
                }

                $options  = array(
                    'responsive' => $config_obj,
                    'slidesToShow' => abs($optionsSource['slides_to_show']),
                    'slidesToScroll' => abs(2),
                    'autoplaySpeed' => abs($optionsSource['autoplay_speed']),
                    'autoplay' => ('yes' === $optionsSource['autoplay']),
                    'infinite' => ('yes' === $optionsSource['infinite']),
                    'pauseOnHover' => ('yes' === $optionsSource['pause_on_hover']),
                    'arrows' => $show_arrows,
                    'dots' => $show_dots,
                );
            } else if ($optionsSource['view'] == 'grid') {
                $classes[] = 'posts-grid';

                $options  = array(
                    'gridClasses' => 'col-'.$optionsSource['slides_to_show_g_mobile']. ' col-md-'.
                    $optionsSource['slides_to_show_g_tablet']. ' col-lg-'.$optionsSource['slides_to_show_g'],
                );
            }

            $module = new Rbthemedream();

            $this->context->smarty->assign(array(
                'posts' => $posts,
                'view' => $optionsSource['view'],
                'options' => $options,
                'classes' =>  implode(' ', $classes),
                'blogLayout' => Configuration::get('RBTHEMEBLOG_BLOG_LIST_LAYOUT'),
                'is_category' => true,
            ));

            return $module->fetch('module:rbthemedream/views/templates/widget/rb-blog.tpl');
        } else {
            return;
        }
    }

    public function preparePosts($nb = 10, $cat = null, $featured = false)
    {
        if (!Module::isEnabled('rbthemeblog')) {
            return false;
        }

        $id_lang = (int) $this->context->language->id;
        
        $posts = RbBlogPost::getPosts(
            $id_lang,
            $nb,
            $cat,
            null,
            true,
            'sbp.date_add',
            'DESC',
            null,
            $featured
        );

        return $posts;
    }
}
