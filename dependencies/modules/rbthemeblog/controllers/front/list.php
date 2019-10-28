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

require_once _PS_MODULE_DIR_.'rbthemeblog/rbthemeblog.php';

class RbthemeblogListModuleFrontController extends ModuleFrontController
{
	public $context;
    public $sb_category = false;
    public $rbblog_search;
    public $rbblog_keyword;
    public $is_search = false;
    public $is_category = false;
    public $posts_per_page;
    public $n;
    public $p;
    private $blogCategory;

    public function init()
    {
        parent::init();

        $rb_category = Tools::getValue('rb_category');
        $rbblog_search = Tools::getValue('rbblog_search');
        $rbblog_keyword = Tools::getValue('rbblog_keyword');

        if (isset($sb_category)) {
            $this->sb_category = $sb_category;
            $this->is_category = true;
        }

        if ($rbblog_search && $rbblog_keyword) {
            $this->rbblog_search = $rbblog_search;
            $this->rbblog_keyword = $rbblog_keyword;
            $this->is_search = true;
        }

        $this->posts_per_page = Configuration::get('RBTHEMEBLOG_BLOG_POSTS_PER_PAGE');
        $this->p = (int) Tools::getValue('p', 0);

        $this->context = Context::getContext();
    }

    public function assignGeneralPurposesVariables()
    {
        $gridType = Configuration::get('RBTHEMEBLOG_BLOG_COLUMNS');
        $gridColumns = Configuration::get('RBTHEMEBLOG_BLOG_GRID_COLUMNS');
        $blogLayout = Configuration::get('RBTHEMEBLOG_BLOG_LIST_LAYOUT');

        $this->context->smarty->assign(array(
            'categories' => RbBlogCategory::getCategories((int) $this->context->language->id),
            'blogMainTitle' => Configuration::get('RBTHEMEBLOG_BLOG_MAIN_TITLE', (int) $this->context->language->id),
            'grid' => Configuration::get('RBTHEMEBLOG_BLOG_COLUMNS'),
            'columns' => $gridColumns,
            'blogLayout' => $blogLayout,
            'module_dir' => _MODULE_DIR_.'rbthemeblog/',
            'tpl_path' => _PS_MODULE_DIR_.'rbthemeblog/views/templates/front/',
            'gallery_dir' => _MODULE_DIR_.'rbthemeblog/galleries/',
            'is_category' => $this->is_category,
            'is_search' => $this->is_search,
        ));
    }

    public function initContent()
    {
        $id_lang = $this->context->language->id;

        parent::initContent();

        $this->context->smarty->assign(
        	'is_16',
        	(bool) (version_compare(_PS_VERSION_, '1.6.0', '>=') === true)
        );

        $this->assignGeneralPurposesVariables();

        if ($this->sb_category != '') {
            $rbBlogCategory = RbBlogCategory::getByRewrite($this->sb_category, $id_lang);

            if (!Validate::isLoadedObject($rbBlogCategory)) {
                $rbBlogCategory = RbBlogCategory::getByRewrite($this->sb_category, false);

                if (Validate::isLoadedObject($rbBlogCategory)) {
                    $rbBlogCategory = new RbBlogCategory($rbBlogCategory->id, $id_lang);
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: '.RbBlogCategory::getLink($rbBlogCategory->link_rewrite));
                } else {
                    header('HTTP/1.1 404 Not Found');
                    header('Status: 404 Not Found');
                    Tools::redirect($this->context->link->getPageLink('404'));
                }
            }

            $this->blogCategory = $rbBlogCategory;

            if ($rbBlogCategory->id_parent > 0) {
                $parent = new RbBlogCategory($rbBlogCategory->id_parent, $id_lang);
                $this->context->smarty->assign('parent_category', $parent);
            }

            $finder = new BlogPostsFinder();
            $finder->setIdCategory($rbBlogCategory->id);
            $posts = $finder->findPosts();

            $this->context->smarty->assign('blogCategory', $rbBlogCategory);
            $this->context->smarty->assign('category_rewrite', $rbBlogCategory->link_rewrite);
        } elseif ($this->is_search) {
            $this->context->smarty->assign('is_search', true);

            switch ($this->rbblog_search) {
                case 'author':
                    break;
                case 'tag':
                    break;
            }

            $this->context->smarty->assign(
            	'meta_title',
            	$this->l('Posts by', 'list').' '.$this->rbblog_author.' - '.$this->l('Blog', 'list')
            );

            $posts = RbBlogPost::findPosts(
            	$this->rbblog_search,
            	$this->rbblog_keyword,
            	$id_lang,
            	$this->posts_per_page,
            	$this->p
            );

            $this->assignPagination(
            	$this->posts_per_page,
            	sizeof(
            		RbBlogPost::findPosts(
	            		$this->rbblog_search,
	            		$this->rbblog_keyword,
	            		$id_lang
            		)
            	)
            );

            $this->context->smarty->assign('posts', $posts);
        } else {
            $finder = new BlogPostsFinder();
            $posts = $finder->findPosts();
        }

        $this->assignPagination($this->posts_per_page, sizeof($posts));
        $posts = array_splice($posts, $this->p ? ($this->p - 1) * $this->posts_per_page : 0, $this->posts_per_page);

        $this->assignMetas();

        $this->context->smarty->assign('posts', $posts);

        $this->setTemplate('module:rbthemeblog/views/templates/front/list.tpl');
    }

    public function assignMetas()
    {
        $pageVariables = $this->getTemplateVarPage();
        $defaultMetaTitleForBlog = Configuration::get('RBTHEMEBLOG_BLOG_MAIN_TITLE', $this->context->language->id);
        $defaultMetaDescriptionForBlog = Configuration::get('RBTHEMEBLOG_BLOG_MAIN_META_DESCRIPTION', $this->context->language->id);

        if ($this->sb_category) {
            $meta_title = $this->blogCategory->name.' - '.$pageVariables['meta']['title'];
        } else {
            if (empty($defaultMetaTitleForBlog)) {
                $meta_title = $pageVariables['meta']['title'].' '.$this->l('Blog', 'list-v17');
            } else {
                $meta_title = $defaultMetaTitleForBlog;
            }
        }

        if ($this->sb_category) {
            if (!empty($this->blogCategory->meta_description)) {
                $meta_description = $this->blogCategory->meta_description;
            } else {
                $meta_description = $pageVariables['meta']['description'];
            }
        } else {
            $meta_description = empty($defaultMetaDescriptionForBlog) ? $pageVariables['meta']['description'] : $defaultMetaDescriptionForBlog;
        }

        if ($this->p > 1) {
            $meta_title .= ' ('.$this->p.')';
        }

        $this->context->smarty->assign('meta_title', $meta_title);
        $this->context->smarty->assign('meta_description', strip_tags($meta_description));
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();
        $id_lang = $this->context->language->id;

        $breadcrumb['links'][] = array(
            'title' => $this->l('Blog'),
            'url' => $this->context->link->getModuleLink('rbthemeblog', 'list'),
        );

        if ($this->sb_category != '') {
            $rbBlogCategory = RbBlogCategory::getByRewrite($this->sb_category, $id_lang);

            if (!Validate::isLoadedObject($rbBlogCategory)) {
                $rbBlogCategory = RbBlogCategory::getByRewrite($this->sb_category, false);
            }

            $breadcrumb['links'][] = array(
                'title' => $rbBlogCategory->name,
                'url' => $rbBlogCategory->link_rewrite,
            );
        }

        return $breadcrumb;
    }

    public function assignPagination($limit, $nbPosts)
    {
        $this->n = $limit;
        $this->p = abs((int) Tools::getValue('p', 1));
        $current_url = tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']);
        $current_url = preg_replace('/(\?)?(&amp;)?p=\d+/', '$1', $current_url);

        $range = 2;

        if ($this->p < 1) {
            $this->p = 1;
        }

        $pages_nb = ceil($nbPosts / (int) $this->n);
        $start = (int) ($this->p - $range);

        if ($start < 1) {
            $start = 1;
        }

        $stop = (int) ($this->p + $range);

        if ($stop > $pages_nb) {
            $stop = (int) $pages_nb;
        }

        $this->context->smarty->assign('nb_posts', $nbPosts);

        $pagination_infos = array(
            'products_per_page' => $limit,
            'pages_nb' => $pages_nb,
            'p' => $this->p,
            'n' => $this->n,
            'range' => $range,
            'start' => $start,
            'stop' => $stop,
            'current_url' => $current_url,
        );
        
        $this->context->smarty->assign($pagination_infos);
    }

    public function getBlogCategory()
    {
        return $this->blogCategory;
    }
}
