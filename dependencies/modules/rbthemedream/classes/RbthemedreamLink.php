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

class RbthemedreamLink extends ObjectModel
{
    public $id_rbthemedream_link;
    public $name;
    public $id_hook;
    public $position;
    public $id_shop;
    public $data;

    public static $definition = array(
        'table' => 'rbthemedream_link',
        'primary' => 'id_rbthemedream_link',
        'multilang' => true,
        'fields' => array(
            'name' =>       array('type' => self::TYPE_STRING, 'lang' => true),
            'id_hook' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'position' =>   array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'data' =>    array('type' => self::TYPE_HTML, 'validate' => 'isJson'),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        $this->context = Context::getContext();
        $this->link = $this->context->link;

        $this->def['table'] = self::$definition['table'];
        ShopCore::addTableAssociation($this->def['table'], array('type' => 'shop'));


        if ($this->id_rbthemedream_link) {
            $this->data = Tools::jsonDecode($this->data, true);
        }

        if (is_null($this->data)) {
            $this->data = array();
        }
        
        parent::__construct($id, $id_lang, $id_shop);
    }

    public function add($autodate = true, $null_values = false)
    {
        return parent::add($autodate, $null_values);
    }

    public function getDisplayHooksForHelper()
    {
        $usableHooks = array(
            'displayFooter',
            'displayFooterBefore',
            'displayFooterAFter',
            'displayLeftColumn',
            'displayRightColumn',
            'displayReassurance',
            'displayRightColumnProduct',
            'displayNav1',
            'displayNav2'
        );

        $sql = "SELECT h.`id_hook` as id, h.`name` as name
        FROM "._DB_PREFIX_."hook h
        WHERE (lower(h.`name`) LIKE 'display%')
        ORDER BY h.name ASC";

        $hooks = Db::getInstance()->executeS($sql);

        foreach ($hooks as $key => $hook) {
            if (preg_match('/admin/i', $hook['name'])
                || preg_match('/backoffice/i', $hook['name'])) {
                    unset($hooks[$key]);
            } else {
                if (!in_array($hook['name'], $usableHooks)) {
                    unset($hooks[$key]);
                }
            }
        }

        return $hooks;
    }

    public function getCountByIdHook($id_hook)
    {
        $sql = "SELECT COUNT(*) FROM "._DB_PREFIX_."rbthemedream_link
        WHERE `id_hook` = " . (int)$id_hook;

        return Db::getInstance()->getValue($sql);
    }

    public function present($links)
    {
        $rb_link = array();

        if (isset($links) && !empty($links['links'])) {
            foreach ($links['links'] as $link) {
                $obj = new RbthemedreamLink($link['id_rbthemedream_link'], $this->context->language->id);

                $data = array();

                if (empty($obj->data)) {
                    $data = $data;
                } else {
                    $data = Tools::jsonDecode($obj->data, true);
                }

                $rb_link[] = array(
                    'id' => $obj->id_rbthemedream_link,
                    'title' => $obj->name,
                    'hook' => $links['hook_name'],
                    'position' => $obj->position,
                    'links' => $this->makeLinks($data),
                );
            }
        }

        return $rb_link;
    }

    public function getLinkByHook($id_shop = null, $id_lang = null)
    {
        $sql = 'SELECT link.`id_rbthemedream_link`, link_l.`name`, link.`id_hook`,
        h.`name` as hook_name, h.`title` as hook_title, h.`description` as hook_description, link.`position`
        FROM `'._DB_PREFIX_.'rbthemedream_link` link
        INNER JOIN `'._DB_PREFIX_.'rbthemedream_link_lang` link_l
        ON (link.`id_rbthemedream_link` = link_l.`id_rbthemedream_link`)
        LEFT JOIN `'._DB_PREFIX_.'hook` h
        ON (link.`id_hook` = h.`id_hook`)
        WHERE link_l.`id_lang` = '.(int)$this->context->language->id.'
        ORDER BY link.`position`';

        $links = Db::getInstance()->executeS($sql);
        $order_link = array();

        foreach ($links as $link) {
            if (!isset($order_link[$link['id_hook']])) {
                $id_hook = ($link['id_hook']) ?: 'not_hooked';

                $order_link[$id_hook] = array(
                    'id_hook' => $link['id_hook'],
                    'hook_name' => $link['hook_name'],
                    'hook_title' => $link['hook_title'],
                    'hook_description' => $link['hook_description'],
                    'links' => array(),
                );
            }
        }

        foreach ($links as $link) {
            $id_hook = ($link['id_hook']) ?: 'not_hooked';
            unset($link['id_hook']);
            unset($link['hook_name']);
            unset($link['hook_title']);
            unset($link['hook_description']);
            $order_link[$id_hook]['links'][] = $link;
        }

        return $order_link;
    }

    public function getCategories($id_lang = null)
    {
        $catSource = $this->getObjCategories($this->context->shop->id, null, (int)$this->context->language->id, false);

        return $this->getCategoryTree($catSource, $parentId = 0);
    }

    public function getObjCategories(
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

        $cache_id = 'Category::getNestedCategories_'.md5((int)$shop_id.(int)$root_category.(int)$id_lang.(int)$active.(int)$active
                .(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));

        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS(
                'SELECT c.*, cl.*
                FROM `'._DB_PREFIX_.'category` c
                INNER JOIN `'._DB_PREFIX_.'category_shop` category_shop
                ON (category_shop.`id_category` = c.`id_category` AND category_shop.`id_shop` = "'.(int)$shop_id.'")
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
                ON (c.`id_category` = cl.`id_category` AND cl.`id_shop` = "'.(int)$shop_id.'")
                WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND cl.`id_lang` = '.(int)$id_lang : '').'
                '.($active ? ' AND (c.`active` = 1 OR c.`is_root_category` = 1)' : '').'
                '.(isset($groups) && Group::isFeatureActive() ? ' AND cg.`id_group` IN ('.implode(',', $groups).')' : '').'
                '.(!$id_lang || (isset($groups) && Group::isFeatureActive()) ? ' GROUP BY c.`id_category`' : '').'
                '.($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC').'
                '.($sql_sort == '' && $use_shop_restriction ? ', category_shop.`position` ASC' : '').'
                '.($sql_limit != '' ? $sql_limit : '')
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

    public function getCategoryTree(array &$elements, $parentId = 0)
    {
        $categories = array();

        foreach ($elements as $element) {
            if ($element['id_parent'] == $parentId) {
                $children = $this->getCategoryTree($elements, $element['id_category']);
                if ($children) {
                    $element['children'] = $children;
                }
                $categories[$element['id_category']] = $element;
                unset($elements[$element['id_category']]);
            }
        }

        return $categories;
    }

    public function getCmsPages($id_lang = null)
    {
        $id_lang = (int) (($id_lang) ?: $this->context->language->id);

        $sql = "SELECT  cc.`id_cms_category`,
                        ccl.`name`,
                        ccl.`description`,
                        ccl.`link_rewrite`,
                        cc.`id_parent`,
                        cc.`level_depth`,
                        NULL as pages
            FROM "._DB_PREFIX_."cms_category cc
            INNER JOIN "._DB_PREFIX_."cms_category_lang ccl
                ON (cc.`id_cms_category` = ccl.`id_cms_category`)
            INNER JOIN "._DB_PREFIX_."cms_category_shop ccs
                ON (cc.`id_cms_category` = ccs.`id_cms_category`)
            WHERE `active` = 1
                AND ccl.`id_lang`= $id_lang
                AND ccs.`id_shop`= " . (int)$this->context->shop->id;

        $pages = Db::getInstance()->executeS($sql);

        foreach ($pages as &$category) {
            $category['pages'] =
                Db::getInstance()->executeS("SELECT c.`id_cms`,
                        c.`position`,
                        cl.`meta_title` as title,
                        cl.`meta_description` as description,
                        cl.`link_rewrite`
                    FROM "._DB_PREFIX_."cms c
                    INNER JOIN "._DB_PREFIX_."cms_lang cl
                        ON (c.`id_cms` = cl.`id_cms`)
                    INNER JOIN "._DB_PREFIX_."cms_shop cs
                        ON (c.`id_cms` = cs.`id_cms`)
                    WHERE c.`active` = 1
                        AND c.`id_cms_category` = ".(int)$category['id_cms_category']."
                        AND cl.`id_lang` = ".(int)$this->context->language->id."
                        AND cs.`id_shop` = " . (int)$this->context->shop->id);
        }

        return $pages;
    }

    public function getStaticPages($id_lang = null)
    {
        $statics = array();
        $pages = array();
        $staticPages = array(
            'prices-drop',
            'new-products',
            'best-sales',
            'manufacturer',
            'supplier',
            'contact',
            'sitemap',
            'stores',
            'authentication',
            'my-account',
            'identity',
            'history',
            'addresses',
            'guest-tracking',
        );

        foreach ($staticPages as $staticPage) {
            $meta = Meta::getMetaByPage($staticPage, $this->context->language->id);

            $statics[] = array(
                'id_cms' => $staticPage,
                'title' => $meta['title'],
            );
        }

        $pages[]['pages'] = $statics;

        return $pages;
    }

    public function makeLinks($content)
    {
        if (!empty($content)) {
            foreach ($content as $key => $page) {
                if ($page['type'] == 'custom') {
                    $content[$key]['data'] = $this->makeCustomLink($page);
                }

                if ($page['type'] == 'static') {
                    $content[$key]['data'] = $this->makeStaticLink($page['id']);
                } elseif ($page['type'] == 'cms_category') {
                    $content[$key]['data'] = $this->makeCmsCategoryLink($page['id']);
                } elseif ($page['type'] == 'cms_page') {
                    $content[$key]['data'] = $this->makeCmsPageLink($page['id']);
                } elseif ($page['type'] == 'category') {
                    $content[$key]['data'] = $this->makeCategoryLink($page['id']);
                }
            }
        }

        return $content;
    }

    public function makeCategoryLink($id)
    {
        $cmsLink = array();

        $cat = new Category((int)$id);

        if (null !== $cat->id) {
            $cmsLink = array(
                'title' => $cat->name[(int)$this->context->language->id],
                'description' => $cat->meta_description[(int)$this->context->language->id],
                'url' => $cat->getLink(),
            );
        }
        return $cmsLink;
    }

    public function makeCmsPageLink($cmsId)
    {
        $cmsLink = array();
        $cms = new CMS((int)$cmsId);

        if (null !== $cms->id) {
            $cmsLink = array(
                'title' => $cms->meta_title[(int)$this->context->language->id],
                'description' => $cms->meta_description[(int)$this->context->language->id],
                'url' => $this->link->getCMSLink($cms),
                );
        }

        return $cmsLink;
    }

    public function makeCmsCategoryLink($cmsId)
    {
        $cmsLink = array();
        $cms = new CMSCategory((int)$cmsId);

        if (null !== $cms->id) {
            $cmsLink = array(
                'title' => $cms->name[(int)$this->context->language->id],
                'description' => $cms->meta_description[(int)$this->context->language->id],
                'url' => $this->link->getCMSCategoryLink($cms),
                );
        }

        return $cmsLink;
    }

    public function makeCustomLink($page)
    {
        $cmsLink = array(
            'title' => $page['title'][(int)$this->context->language->id],
            'url' => $page['url'][(int)$this->context->language->id],
        );
        
        return $cmsLink;
    }

    public function makeStaticLink($staticId)
    {
        $staticLink = array();
        $meta = Meta::getMetaByPage($staticId, (int)$this->context->language->id);

        $staticLink = array(
            'title' => $meta['title'],
            'description' => $meta['description'],
            'url' => $this->link->getPageLink($staticId, true),
        );

        return $staticLink;
    }

    public function getNextPosition($id_hook)
    {
        $sql = 'SELECT MAX(`position`)
        FROM `'._DB_PREFIX_.'rbthemedream_link`
        WHERE `id_hook` = '.(int)$id_hook;

        return Db::getInstance()->getValue($sql) + 1;
    }
}
