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

require_once(_PS_MODULE_DIR_.'rbthemedream/lib/group/rb-background.php');
require_once(_PS_MODULE_DIR_.'rbthemedream/lib/group/rb-border.php');
require_once(_PS_MODULE_DIR_.'rbthemedream/lib/group/rb-box-shadow.php');
require_once(_PS_MODULE_DIR_.'rbthemedream/lib/group/rb-typography.php');
require_once(_PS_MODULE_DIR_.'rbthemedream/include.php');

use PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\NewProducts\NewProductsProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\PricesDrop\PricesDropProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Manufacturer\ManufacturerProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class RbControl
{
	public static $available_tabs_controls;
    public $render_attributes = array();

	public function __construct()
	{
		$this->controls = array();
		$this->tabs_controls = array();
        $this->current_section = null;
        $this->context = Context::getContext();
        $this->module = new Rbthemedream();
	}

    public static function getImage($image = '')
    {
        if (Validate::isAbsoluteUrl($image)) {
            return $image;
        } else{
            $http = Tools::getCurrentUrlProtocolPrefix();
            return $http.Tools::getMediaServer($image).$image;
        }
    }

	public function addControl($id, $args)
	{
		$default_args = array(
            'default' => '',
            'type' => 'text',
            'tab' => 'content',
        );

        $args['name'] = $id;
        $args = array_merge($default_args, $args);

        if (isset($this->controls[$id])) {
            return false;
        }

        if (!in_array($args['type'], array('section', 'wp_widget'))) {
            if ($this->current_section !== null) {
                $args = array_merge($args, $this->current_section);
            }
        }

        $available_tabs = $this->getAvailableTabsControls();

        if (!isset($available_tabs[$args['tab']])) {
            $args['tab'] = $default_args['tab'];
        }

        $this->tabs_controls[$args['tab']] = $available_tabs[$args['tab']];
        $this->controls[$id] = array_merge($default_args, $args);

        return true;
	}


    public function addGroupControl($group_name, $args = array())
    {
        switch ($group_name) {
            case 'background':
                $control = new RbBackground();
                $this->addControls($control, $args);
                return;
            case 'border':
                $control = new RbBorder();
                $this->addControls($control, $args);;
                return;
            case 'typography':
                $control = new RbGroupTypography();
                $this->addControls($control, $args);
                return;
            // case 'image-size':
            //     $control = new Group_Control_Image_Size();
            //     $control->add_controls($this, $args);
            //     return;
            case 'box-shadow':
                $control = new RbBoxShadow();
                $this->addControls($control, $args);
                return;
        }
    }

	public function getDefaultArgs()
	{
		return array(
			'section' => '',
			'default' => '',
			'selector' => '{{WRAPPER}}',
			'tab' => 'content',
			'fields' => 'all',
		);
	}


    public function addResponsiveControl($id, $args = array())
    {
        $control_args = $args;

        if (!empty( $args['prefix_class'])) {
            $control_args['prefix_class'] = sprintf($args['prefix_class'], '');
        }

        $control_args['responsive'] = 'desktop';

        $this->addControl(
            $id,
            $control_args
        );

        $control_args = $args;

        if (!empty($args['prefix_class'])) {
            $control_args['prefix_class'] = sprintf($args['prefix_class'], '-' . 'tablet');
        }

        $control_args['responsive'] = 'tablet';

        $this->addControl(
            $id . '_tablet',
            $control_args
        );

        $control_args = $args;

        if (!empty($args['prefix_class'])) {
            $control_args['prefix_class'] = sprintf($args['prefix_class'], '-' . 'mobile');
        }

        $control_args['responsive'] = 'mobile';

        $this->addControl(
            $id . '_mobile',
            $control_args
        );
    }

    public function addControls($control, $args)
    {
    	$this->args = array_merge($this->getDefaultArgs(), array(), $args);
    	$controls = $control->getControls($this->args);
    	$controls = $this->addPrefixes($controls);

    	foreach ($controls as $control_id => $control_args) {
			$control_args = $this->addGroupArgsToControl($control_id, $control_args);
			$id = $this->args['name'] . '_' . $control_id;

			if (!empty($control_args['responsive'])) {
				unset( $control_args['responsive']);
				$this->addResponsiveControl($id, $control_args);
			} else {
				$this->addControl($id, $control_args);
			}
		}
    }

    public function getBaseGroupClasses($name)
    {
		return 'rb-group-control-' . $name . ' live-group-control';
	}

    public function addGroupArgsToControl($control_id, $control_args)
    {
		$args = $this->args;

		$control_args['tab'] = $args['tab'];
		$control_args['section'] = $args['section'];
		$control_args['classes'] = $this->getBaseGroupClasses($this->args['name']) . ' rb-group-control-' . $control_id;

		if (!empty($args['condition'])) {
			if (empty( $control_args['condition']))
				$control_args['condition'] = array();

			$control_args['condition'] += $args['condition'];
		}

		return $control_args;
	}

    public function addPrefixes($controls)
    {
        foreach ($controls as &$control ) {
            if (!empty($control['condition'])) {
                $control = $this->addConditionsPrefix( $control );
            }

            if (!empty($control['selectors'] ) ) {
                $control = $this->addSelectorsPrefix( $control );
            }
        }

        return $controls;
    }

    public function addConditionsPrefix($control)
    {
        $prefixed_condition_keys = array_map(
            function ($key) {
                return $this->args['name'] . '_' . $key;
            },
            array_keys($control['condition'])
        );

        $control['condition'] = array_combine(
            $prefixed_condition_keys,
            $control['condition']
        );

        return $control;
    }

    public function addSelectorsPrefix($control)
    {
        foreach ($control['selectors'] as &$selector) {
            $selector = preg_replace_callback( '/(?:\{\{)\K[^.}]+(?=\.[^}]*}})/', function ($matches) {
                return $this->args['name'] . '_' . $matches[0];
            }, $selector );
        }

        return $control;
    }

    public function startControlsSection($id, $args)
    {
        $args['type'] = 'section';

        $this->addControl($id, $args);

        if ($this->current_section !== null) {
            die(
                sprintf(
                    'You can not start a section before the end of the previous section: ',
                    $this->current_section['section']
                )
            );
        }

        $this->current_section = array(
            'section' => $id,
            'tab' => $this->controls[$id]['tab'],
        );
    }

    public function endControlsSection()
    {
        $this->current_section = null;
    }

	public function getControls()
	{
        return array_values($this->controls);
    }

	public static function getAvailableTabsControls()
	{
		$module = new Rbthemedream();

        if (!self::$available_tabs_controls) {
            self::$available_tabs_controls = array(
                'content' => $module->l('Content'),
                'style' => $module->l('Style'),
                'advanced' => $module->l('Advanced' ),
                'responsive' => $module->l('Responsive'),
                'layout' => $module->l('Layout'),
            );
        }

        return self::$available_tabs_controls;
    }

    public function addPresControl($controls)
    {
        if (!empty($controls)) {
            foreach ($controls as $key => $control) {
                if (isset($control['responsive'])) {
                    $this->addResponsiveControl(
                        $key,
                        $control
                    );
                } elseif (isset($control['group_type_control'])) {
                    $this->addGroupControl(
                        $control['group_type_control'],
                        $control
                    );
                } else {
                    $this->addControl(
                        $key,
                        $control
                    );
                }
            }
        }
    }

    public function generateCategoriesOption($categories)
    {
        $return_categories = array();

        foreach ($categories as $key => $category) {
            if ($category['id_parent'] != 0) {
                $return_categories['xc_' . $key] = str_repeat('&nbsp;', 2 *
                (int)$category['level_depth']) . $category['name'];
            }

            if (isset($category['children']) && !empty($category['children']))
                $return_categories = array_merge($return_categories, $this->generateCategoriesOption($category['children']));
        }

        return $return_categories;
    }

    public function customGetNestedCategories(
        $shop_id,
        $root_category = null,
        $id_lang = false,
        $active = false,
        $groups = null,
        $use_shop_restriction = true,
        $sql_filter = '',
        $sql_sort = '',
        $sql_limit = ''
    ) {
        if (isset($root_category) && !Validate::isInt($root_category)) {
            die(Tools::displayError());
        }

        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }

        if (isset($groups) && Group::isFeatureActive() && !is_array($groups)) {
            $groups = (array)$groups;
        }

        $cache_id = 'Category::getNestedCategories_' . md5((int)$shop_id . (int)$root_category . (int)$id_lang . (int)$active . (int)$active
                . (isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));

        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS(
                'SELECT c.*, cl.*
                FROM `' . _DB_PREFIX_ . 'category` c
                INNER JOIN `' . _DB_PREFIX_ . 'category_shop` category_shop
                ON (category_shop.`id_category` = c.`id_category`
                AND category_shop.`id_shop` = "' . (int)$shop_id . '")
                LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl
                ON (c.`id_category` = cl.`id_category`
                AND cl.`id_shop` = "' . (int)$shop_id . '")
                WHERE 1 ' . $sql_filter . ' ' . ($id_lang ? '
                AND cl.`id_lang` = ' . (int)$id_lang : '') . '
                ' . ($active ? ' AND (c.`active` = 1 OR c.`is_root_category` = 1)' : '') . '
                ' . (isset($groups) && Group::isFeatureActive() ? ' AND cg.`id_group`
                IN (' . implode(',', $groups) . ')' : '') . '
                ' . (!$id_lang || (isset($groups) && Group::isFeatureActive()) ? '
                GROUP BY c.`id_category`' : '') . '
                ' . ($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC') . '
                ' . ($sql_sort == '' && $use_shop_restriction ? ', category_shop.`position` ASC' : '') . '
                ' . ($sql_limit != '' ? $sql_limit : '')
            );

            $categories = array();
            $buff = array();

            foreach ($result as $row) {
                $current = &$buff[$row['id_category']];
                $current = $row;

                if ($row['id_parent'] == 0) {
                    $categories[$row['id_category']] = &$current;
                } else {
                    $buff[$row['id_parent']]['children'][$row['id_category']] = &$current;
                }
            }

            Cache::store($cache_id, $categories);
        }

        return Cache::retrieve($cache_id);
    }

    public function rbGetValue($control, $instance)
    {
        $value = $this->getValue($control, $instance);

        if ( empty( $control['default'] ) )
            $control['default'] = array();

        if (!is_array($value))
            $value = array();



        $control['default'] = array_merge(
            array(),
            $control['default']
        );

        return array_merge(
            $control['default'],
            $value
        );
    }

    public function getValue($control, $instance)
    {   
        if (isset($control['type']) &&
            (isset($control['default']['size']) && $control['default']['size'] == 400) &&
            ($control['type'] == 'slider' || $control['type'] == 'dimensions') &&
            !isset($control['default']['unit'])
        ) {
            $control['default']['unit'] = 'px';
        }

        if (!isset($control['default']))
            $control['default'] = array();

        if (!isset( $instance[$control['name']]))
            return $control['default'];

        return $instance[$control['name']];
    }

    public function getClassControls()
    {
        return array_filter($this->getControls(), function($control) {
            return (isset($control['prefix_class']));
        });
    }

    public function isControlVisible($element_instance, $control)
    {
        if (empty($control['condition'])) {
            return true;
        }

        foreach ($control['condition'] as $condition_key => $condition_value) {
            preg_match( '/([a-z_0-9]+)(?:\[([a-z_]+)])?(!?)$/i', $condition_key, $condition_key_parts );

            $pure_condition_key = $condition_key_parts[1];
            $condition_sub_key = $condition_key_parts[2];
            $is_negative_condition = ! ! $condition_key_parts[3];

            $instance_value = $element_instance[ $pure_condition_key ];

            if ($condition_sub_key) {
                if (!isset($instance_value[$condition_sub_key])) {
                    return false;
                }

                $instance_value = $instance_value[$condition_sub_key];
            }

            $is_contains = is_array($condition_value) ? in_array($instance_value, $condition_value) : $instance_value === $condition_value;

            if ($is_negative_condition &&
                $is_contains ||
                ! $is_negative_condition &&
                ! $is_contains
            ) {
                return false;
            }
        }

        return true;
    }

    public function addRenderAttribute($element, $key, $value)
    {
        if (empty($this->render_attributes[$element][$key])) {
            $this->render_attributes[$element][$key] = array();
        }

        $this->render_attributes[$element][$key] = array_merge($this->render_attributes[$element][$key], (array)$value);
    }

    public function getRenderAttributeString($element)
    {
        if (empty($this->render_attributes[$element])) {
            return '';
        }

        $render_attributes = $this->render_attributes[$element];
        $attributes = array();

        foreach ($render_attributes as $attribute_key => $attribute_values) {
            $attributes[] = sprintf('%s="%s"', $attribute_key, Tools::safeOutput(implode(' ', $attribute_values)));
        }

        unset($this->render_attributes[$element]);

        return implode(' ', $attributes);
    }

    public static function getYoutubeIdFromUrl($url)
    {
        preg_match('/^.*(?:youtu.be\/|v\/|e\/|u\/\w+\/|embed\/|v=)([^#\&\?]*).*/', $url, $video_id_parts);

        if (empty($video_id_parts[1])) {
            return false;
        }

        return $video_id_parts[1];
    }

    public function getWidgetValues($instance = array())
    {
        $controls = $this->getControls();

        if (!empty($controls)) {
            foreach ($controls as $control) {
                $instance[$control['name']] = $this->getValue($control, $instance);
            }
        }

        return $instance;
    }

    public function wdBeforeRender($instance, $element_id, $element_data = array(), $name)
    {
        $this->addRenderAttribute(
            'wrapper',
            'class',
            array(
                'rb-widget',
                'rb-element',
                'rb-element-' . $element_id,
                'rb-widget-' . $name,
            )
        );

        foreach ($this->getClassControls() as $control) {
            if (empty($instance[$control['name']]))
                continue;

            if (!$this->isControlVisible($instance, $control))
                continue;

            $this->addRenderAttribute(
                'wrapper',
                'class',
                $control['prefix_class'] . $instance[ $control['name']]
            );
        }

        if (!empty($instance['_animation'])) {
            $this->addRenderAttribute(
                'wrapper',
                'data-animation',
                $instance['_animation']
            );
        }

        $this->addRenderAttribute('wrapper', 'data-element_type', $name);
        $attribute_string = $this->getRenderAttributeString('wrapper');

        return "<div ".$attribute_string.">";
    }

    public function addBaseControl()
    {
        $module = new Rbthemedream();

        $this->addControl(
            '_section_style',
            array(
                'label' => $module->l('Element Style'),
                'type' => 'section',
                'tab' => 'advanced',
            )
        );

        $this->addResponsiveControl(
            '_margin',
            array(
                'label' => $module->l('Margin'),
                'type' => 'dimensions',
                'size_units' => array('px', '%'),
                'tab' => 'advanced',
                'section' => '_section_style',
                'selectors' => array(
                    '{{WRAPPER}} .rb-widget-container' => 'margin:
                    {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addResponsiveControl(
            '_padding',
            array(
                'label' => $module->l('Padding'),
                'type' => 'dimensions',
                'size_units' => array('px', 'em', '%'),
                'tab' => 'advanced',
                'section' => '_section_style',
                'selectors' => array(
                    '{{WRAPPER}} .rb-widget-container' => 'padding:
                    {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            '_animation',
            array(
                'label' => $module->l('Entrance Animation'),
                'type' => 'animation',
                'default' => '',
                'prefix_class' => 'animated ',
                'tab' => 'advanced',
                'label_block' => true,
                'section' => '_section_style',
            )
        );

        $this->addControl(
            'animation_duration',
            array(
                'label' => $module->l( 'Animation Duration', 'rb' ),
                'type' => 'select',
                'default' => '',
                'options' => array(
                    'slow' => $module->l('Slow'),
                    '' => $module->l('Normal'),
                    'fast' => $module->l('Fast'),
                ),
                'prefix_class' => 'animated-',
                'tab' => 'advanced',
                'section' => '_section_style',
                'condition' => array(
                    '_animation!' => '',
                ),
            )
        );

        $this->addControl(
            '_css_classes',
            array(
                'label' => $module->l('CSS Classes'),
                'type' => 'text',
                'tab' => 'advanced',
                'section' => '_section_style',
                'default' => '',
                'prefix_class' => '',
                'label_block' => true,
                'title' => $module->l('Add your custom class WITHOUT the dot. e.g: my-class'),
            )
        );

        $this->addControl(
            '_section_background',
            array(
                'label' => $module->l('Background & Border'),
                'type' => 'section',
                'tab' => 'advanced',
            )
        );

        $this->addGroupControl(
            'background',
            array(
                'name' => '_background',
                'tab' => 'advanced',
                'section' => '_section_background',
                'selector' => '{{WRAPPER}} .rb-widget-container',
                'types' => array('classic'),
            )
        );

        $this->addGroupControl(
            'border',
            array(
                'name' => '_border',
                'tab' => 'advanced',
                'section' => '_section_background',
                'selector' => '{{WRAPPER}} .rb-widget-container',
            )
        );

        $this->addControl(
            '_border_radius',
            array(
                'label' => $module->l('Border Radius'),
                'type' => 'dimensions',
                'size_units' => array('px', '%'),
                'tab' => 'advanced',
                'section' => '_section_background',
                'selectors' => array(
                    '{{WRAPPER}} .rb-widget-container' => 'border-radius:
                    {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->addGroupControl(
            'box-shadow',
            array(
                'name' => '_box_shadow',
                'section' => '_section_background',
                'tab' => 'advanced',
                'selector' => '{{WRAPPER}} .rb-widget-container',
            )
        );


        $this->addControl(
            '_section_responsive',
            array(
                'label' => $module->l('Responsive'),
                'type' => 'section',
                'tab' => 'advanced',
            )
        );

        $this->addControl(
            'responsive_description',
            array(
                'raw' => $module->l('Attention: The display settings (show/hide for mobile, tablet or desktop)'),
                'type' => 'raw_html',
                'tab' => 'advanced',
                'section' => '_section_responsive',
                'classes' => 'rb-control-descriptor',
            )
        );

        $this->addControl(
            'hide_desktop',
            array(
                'label' => $module->l('Hide On Desktop'),
                'type' => 'switcher',
                'tab' => 'advanced',
                'section' => '_section_responsive',
                'default' => '',
                'prefix_class' => 'rb-',
                'label_on' => 'Hide',
                'label_off' => 'Show',
                'return_value' => 'hidden-desktop',
            )
        );

        $this->addControl(
            'hide_tablet',
            array(
                'label' => $module->l('Hide On Tablet'),
                'type' => 'switcher',
                'tab' => 'advanced',
                'section' => '_section_responsive',
                'default' => '',
                'prefix_class' => 'rb-',
                'label_on' => 'Hide',
                'label_off' => 'Show',
                'return_value' => 'hidden-tablet',
            )
        );

        $this->addControl(
            'hide_mobile',
            array(
                'label' => $module->l('Hide On Mobile'),
                'type' => 'switcher',
                'tab' => 'advanced',
                'section' => '_section_responsive',
                'default' => '',
                'prefix_class' => 'rb-',
                'label_on' => 'Hide',
                'label_off' => 'Show',
                'return_value' => 'hidden-phone',
            )
        );
    }

    public static function getProductsInfoByIds($ids, $id_lang, $context, $active = true)
    {
        $product_ids = join(',', $ids);
        $id_shop = (int) $context->shop->id;

        $sql = 'SELECT p.*, product_shop.*, stock.`out_of_stock`,
        IFNULL(stock.`quantity`, 0) as quantity, pl.`description`, pl.`description_short`, pl.`link_rewrite`,
        pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,
        image_shop.`id_image` id_image, il.`legend`, m.`name` as
        manufacturer_name, cl.`name` AS category_default, IFNULL(product_attribute_shop.`id_product_attribute`, 0) id_product_attribute,
        DATEDIFF(p.`date_add`,
                        DATE_SUB(
                            "'.date('Y-m-d').' 00:00:00",
                            INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
                        )
                    ) > 0 AS new
                FROM  `'._DB_PREFIX_.'product` p 
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
                    ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$id_shop.')
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
                    p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
                )
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (
                    product_shop.`id_category_default` = cl.`id_category`
                    AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').'
                )
                LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
                    ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int)$id_shop.')
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (p.`id_manufacturer`= m.`id_manufacturer`)
                '.Product::sqlStock('p', 0).'
                WHERE p.id_product IN ('.$product_ids.')'.
            ($active ? ' AND product_shop.`active` = 1 AND product_shop.`visibility` != \'none\'' : '').'
                ORDER BY FIELD(product_shop.id_product, '.$product_ids.')
                ';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return false;
        }

        foreach ($result as &$row) {
            $row['id_product_attribute'] = Product::getDefaultAttribute((int)$row['id_product']);
        }

        return Product::getProductsProperties($id_lang, $result);
    }

    public function getProductsByIds($ids)
    {
        if (!is_array($ids)){
            return;
        }

        if (empty($ids)){
            return;
        }

        $products = self::getProductsInfoByIds($ids, $this->context->language->id, $this->context);
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();

        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        if (is_array($products)) {
            foreach ($products as &$product) {
                $product = $presenter->present(
                    $presentationSettings,
                    Product::getProductProperties($this->context->language->id, $product, $this->context),
                    $this->context->language
                );
            }

            unset($product);
        }

        return $products;
    }

    public function getProducts($source, $limit, $orderBy, $orderWay, $brand = null)
    {
        $context = new ProductSearchContext($this->context);
        $query = new ProductSearchQuery();
        $nProducts = $limit;

        if ($nProducts < 0) {
            $nProducts = 12;
        }

        $query
            ->setResultsPerPage($nProducts)
            ->setPage(1);

        switch ($source) {
            case 'np':
                $searchProvider = new NewProductsProductSearchProvider(
                    $this->context->getTranslator()
                );
                $query
                    ->setQueryType('new-products')
                    ->setSortOrder(new SortOrder('product', 'date_add', 'desc'));
                break;
            case 'pd':
                $searchProvider = new PricesDropProductSearchProvider(
                    $this->context->getTranslator()
                );
                $query->setQueryType('prices-drop');
                if ($orderBy == 'random') {
                    $orderBy = 'position';
                } else {
                    $query->setSortOrder(new SortOrder('product', $orderBy, $orderWay));
                }
                break;
            case 'bb':
                $brand = new Manufacturer((int)$brand);
                $searchProvider = new ManufacturerProductSearchProvider(
                    $this->context->getTranslator(),
                    $brand
                );
                if ($orderBy == 'random') {
                    $orderBy = 'position';
                } else {
                    $query->setSortOrder(new SortOrder('product', $orderBy, $orderWay));
                }
                break;
            case 'bs':
                if (Configuration::get('PS_CATALOG_MODE')) {
                    return false;
                }
                $searchProvider = new BestSalesProductSearchProvider(
                    $this->context->getTranslator()
                );
                $query->setQueryType('best-sales');
                $query->setSortOrder(new SortOrder('product', 'name', 'asc'));
                break;
            default:
                $idCategory = (int)str_replace('xc_', '', $source);
                $category = new Category((int)$idCategory);
                $searchProvider = new CategoryProductSearchProvider(
                    $this->context->getTranslator(),
                    $category
                );
                if ($orderBy == 'random') {
                    $query->setSortOrder(SortOrder::random());
                } else {
                    $query->setSortOrder(new SortOrder('product', $orderBy, $orderWay));
                }

                $query->setQueryType('prices-drop');
        }

        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        $assembler = new ProductAssembler($this->context);
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();

        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        $products_for_template = array();

        foreach ($result->getProducts() as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        return $products_for_template;
    }

    public function getProduct($id)
    {
        $productSource = $this->getProductsByIds($id);

        if (isset($productSource[0])){
            $product['name'] = $productSource[0]['name'];
            $product['price'] = $productSource[0]['price'];
            $product['url'] = $productSource[0]['url'];
            $product['cover'] = $productSource[0]['cover']['bySize']['small_default'];

            return $product;
        }
    }

    public function calculateGrid($nb)
    {
        if ($nb == 0) {
            $nb = 1;
        }

        if ($nb == 5) {
            $nb = 15;
        } else {
            $nb = (12 / $nb);
        }

        return $nb;
    }

    public static function parseTextEditor($content, $instance = array())
    {
        return $content;
    }
}