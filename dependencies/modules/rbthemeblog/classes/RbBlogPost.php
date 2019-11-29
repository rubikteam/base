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

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_.'rbthemeblog/rbthemeblog.php';

class RbBlogPost extends ObjectModel
{
	public $id;
    public $id_rbblog_post;
    public $id_rbblog_category;
    public $id_rbblog_post_type;
    public $id_rbblog_author;
    public $author;
    public $likes;
    public $views;
    public $allow_comments = 3;
    public $is_featured = 0;
    public $access;
    public $cover;
    public $featured;
    public $id_product;
    public $active = 1;
    public $title;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;
    public $short_content;
    public $content;
    public $link_rewrite;
    public $video_code;
    public $external_url;
    public $date_add;
    public $date_upd;
    public $featured_image;
    public $banner;
    public $tags;
    public $post_type;
    public $category_url;
    public $parent_category = false;
    public $category;

    public static $definition = array(
        'table' => 'rbblog_post',
        'primary' => 'id_rbblog_post',
        'multilang' => true,
        'fields' => array(
            'id_rbblog_category' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ),
            'id_rbblog_post_type' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ),
            'active' => array(
                'type' => self::TYPE_BOOL,
            ),
            'is_featured' => array(
                'type' => self::TYPE_BOOL,
            ),
            'access' => array(
                'type' => self::TYPE_STRING,
            ),
            'author' => array(
                'type' => self::TYPE_STRING,
            ),
            'likes' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ),
            'views' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ),
            'allow_comments' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ),
            'cover' => array(
                'type' => self::TYPE_STRING,
            ),
            'featured' => array(
                'type' => self::TYPE_STRING,
            ),
            'id_product' => array(
                'type' => self::TYPE_STRING,
                'size' => '3999999999999',
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
            ),
            'date_upd' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
            ),

            // Lang fields
            'title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'required' => true,
                'size' => 255,
            ),
            'meta_title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'size' => 255,
            ),
            'meta_description' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'size' => 255,
            ),
            'meta_keywords' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isGenericName',
                'size' => 255,
            ),
            'link_rewrite' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isLinkRewrite',
                'required' => true,
                'size' => 128,
            ),
            'short_content' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml',
                'size' => 3999999999999,
            ),
            'content' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml',
                'size' => 3999999999999,
            ),
            'video_code' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml',
                'size' => 3999999999999,
            ),
            'external_url' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml',
                'size' => 3999999999999,
            ),
        ),
    );

    public function __construct($id_rbblog_post = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id_rbblog_post, $id_lang, $id_shop);

        $context = Context::getContext();

        if ($id_lang && $this->id) {
            $category = new RbBlogCategory($this->id_rbblog_category, $id_lang);
            $this->category = $category->name;
            $this->category_rewrite = $category->link_rewrite;

            $this->url = $context->link->getModuleLink(
                'rbthemeblog',
                'single',
                array(
                    'rewrite' => $this->link_rewrite,
                    'rb_category' => $this->category_rewrite,
                )
            );

            $this->category_url = $context->link->getModuleLink(
                'rbthemeblog',
                'category',
                array(
                    'rb_category' => $this->category_rewrite,
                )
            );

            if ($category->id_parent > 0) {
                $this->parent_category = new RbBlogCategory($category->id_parent, $id_lang);
            }
        }

        if (file_exists(_PS_MODULE_DIR_.'rbthemeblog/covers/'.$this->id_rbblog_post.'.'.$this->cover)) {
            $this->banner = _MODULE_DIR_.'rbthemeblog/covers/'.$this->id_rbblog_post.'.'.$this->cover;
            $this->banner_thumb = _MODULE_DIR_.'rbthemeblog/covers/'.$this->id_rbblog_post.'-thumb.'.$this->cover;
            $this->banner_wide = _MODULE_DIR_.'rbthemeblog/covers/'.$this->id_rbblog_post.'-wide.'.$this->cover;
        }

        if (file_exists(_PS_MODULE_DIR_.'rbthemeblog/featured/'.$this->id_rbblog_post.'.'.$this->featured)) {
            $this->featured_image = _MODULE_DIR_.'rbthemeblog/featured/'.$this->id_rbblog_post.'.'.$this->featured;
        }

        if ($this->id) {
            $tags = RbBlogTag::getPostTags((int) $this->id);
            $this->tags = $tags;

            if (isset($tags) && isset($tags[$id_lang])) {
                $this->tags_list = $tags[$id_lang];
            }

            $this->comments = RbBlogComment::getCommentsCount((int) $this->id);
            $this->post_type = RbBlogPostType::getSlugById((int) $this->id_rbblog_post_type);

            if ($this->post_type == 'gallery') {
                $this->gallery = RbBlogPostImage::getAllById((int) $this->id);
            }
        }
    }

    public function add($autodate = false, $null_values = false)
    {
        return parent::add($autodate, $null_values);
    }

    public function delete()
    {
        if ((int) $this->id === 0) {
            return false;
        }

        return self::deleteCover($this->id)
                && self::deleteFeatured($this->id)
                && parent::delete();
    }

    public static function getUrlRewriteInformations($id_rbblog)
    {
        $sql = 'SELECT l.`id_lang`, c.`link_rewrite`
        FROM `'._DB_PREFIX_.'rbblog_lang` AS c
        LEFT JOIN  `'._DB_PREFIX_.'lang` AS l ON c.`id_lang` = l.`id_lang`
        WHERE c.`id_rbblog` = '.(int) $id_rbblog.'
        AND l.`active` = 1';

        return Db::getInstance()->executeS($sql);
    }


    public static function getRbPosts(
    	$id_lang,
    	$id_shop = null,
    	Context $context = null,
    	$filter = null,
    	$selected = array(),
    	$archive = false
    ) {
        if (!isset($context)) {
            $context = Context::getContext();
        }

        if (!$id_shop) {
            $id_shop = $context->shop->id;
        }

        $sql = new DbQuery();
        $sql->select('sbp.id_rbblog_post, l.title');
        $sql->from('rbblog_post', 'sbp');

        $sql->innerJoin(
        	'rbblog_post_lang',
        	'l',
        	'sbp.id_rbblog_post = l.id_rbblog_post
        	AND l.id_lang = '.(int) $id_lang
        );

        $sql->innerJoin(
        	'rbblog_post_shop',
        	'sbps',
        	'sbp.id_rbblog_post = sbps.id_rbblog_post
        	AND sbps.id_shop = '.(int) $id_shop
        );

        if ($filter) {
            $sql->where('sbp.id_rbblog_post '.$filter.' ('.implode(',', $selected).')');
        }

        $sql->where('sbp.date_add <= \''.RbBlogHelper::now(Configuration::get('RBTHEMEBLOG_BLOG_TIMEZONE')).'\'');
        $sql->where('sbp.active = 1');

        $posts = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!empty($posts)) {
            $posts = self::checkAccess($posts);
        }

        return $posts;
    }

    public static function checkAccess($posts)
    {
        if (Context::getContext()->customer) {
            foreach ($posts as $key => $post) {
                if ($userGroups = Context::getContext()->customer->getGroups()) {
                    $tmpLinkGroups = unserialize($post['access']);
                    $linkGroups = array();

                    foreach ($tmpLinkGroups as $groupID => $status) {
                        if ($status) {
                            $linkGroups[] = $groupID;
                        }
                    }

                    $intersect = array_intersect($userGroups, $linkGroups);

                    if (!count($intersect)) {
                        unset($posts[$key]);
                    }
                }
            }

            return $posts;
        } else {
            return $posts;
        }
    }

    public static function findPosts(
    	$type = 'author',
    	$keyword = false,
    	$id_lang = null,
    	$limit = 10,
    	$page = null
    ) {
        if ($type == 'author') {
            return self::getPosts(
            	$id_lang,
            	$limit,
            	null,
            	$page,
            	$page,
            	null,
            	null,
            	null,
            	null,
            	false,
            	$keyword
            );
        } elseif ($type == 'tag') {
            return self::getPosts(
            	$id_lang,
            	$limit,
            	null,
            	$page,
            	$page,
            	null,
            	null,
            	null,
            	null,
            	false,
            	false,
            	$keyword
            );
        } else {
            return;
        }
    }


    public static function getAllAvailablePosts($id_lang)
    {
        return self::getPosts(
        	$id_lang,
        	99999,
        	null,
        	null,
        	false,
        	false,
        	false,
        	null,
        	false,
        	false,
        	null,
        	false,
        	false,
        	false
        );
    }

    public static function getPosts(
        $id_lang,
        $limit = 10,
        $id_rbblog_category = null,
        $page = null,
        $active = true,
        $orderby = false,
        $orderway = false,
        $exclude = null,
        $featured = false,
        $author = false,
        $id_shop = null,
        $filter = false,
        $selected = array(),
        $check_access = true
    ) {
        $context = Context::getContext();
        $start = $limit * ($page == 0 ? 0 : $page - 1);
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('rbblog_post', 'sbp');

        if ($id_lang) {
            $sql->innerJoin(
            	'rbblog_post_lang',
            	'l',
            	'sbp.id_rbblog_post = l.id_rbblog_post
            	AND l.id_lang = '.(int) $id_lang
            );
        }

        if (!$id_shop) {
            $id_shop = $context->shop->id;
        }

        $sql->innerJoin(
        	'rbblog_post_shop',
        	'sbps',
        	'sbp.id_rbblog_post = sbps.id_rbblog_post
        	AND sbps.id_shop = '.(int) $id_shop
        );

        if ($active) {
            $sql->where('sbp.active = 1');
        }

        if (isset($id_rbblog_category) && (int) $id_rbblog_category > 0) {
            $childrens = RbBlogCategory::getChildrens((int) $id_rbblog_category);

            if ($childrens && !empty($childrens)) {
                $child_categories = array((int) $id_rbblog_category);

                foreach ($childrens as $child) {
                    $child_categories[] = $child['id_rbblog_category'];
                }

                $sql->where('sbp.id_rbblog_category IN ('.implode(',', $child_categories).')');
            } else {
                $sql->where('sbp.id_rbblog_category = '.(int) $id_rbblog_category);
            }
        }

        if ($exclude) {
            $sql->where('sbp.id_rbblog_post != '.(int) $exclude);
        }

        if ($featured) {
            $sql->where('sbp.is_featured = 1');
        }

        if ($author && Configuration::get('RBTHEMEBLOG_BLOG_POST_BY_AUTHOR')) {
            $sql->where('sbp.author = \''.pSQL($author).'\'');
        }

        if ($filter) {
            $sql->where('sbp.id_rbblog_post '.$filter.' ('.implode(',', $selected).')');
        }

        $sql->where('sbp.date_add <= \''.RbBlogHelper::now(Configuration::get('RBTHEMEBLOG_BLOG_TIMEZONE')).'\'');

        if (!$orderby) {
            $orderby = 'sbp.date_add';
        }

        if (!$orderway) {
            $orderway = 'DESC';
        }

        $sql->orderBy($orderby.' '.$orderway);

        $sql->limit($limit, $start);

        $result = Db::getInstance()->executeS($sql);

        if (!empty($result) && $check_access == true) {
            $result = self::checkAccess($result);
        }

        if (!empty($result)) {
            foreach ($result as &$row) {
                $category_rewrite = RbBlogCategory::getRewriteByCategory($row['id_rbblog_category'], $id_lang);

                $category_obj = new RbBlogCategory($row['id_rbblog_category'], $id_lang);

                $category_url = RbBlogCategory::getLink($category_obj->link_rewrite, $id_lang);

                if (file_exists(_PS_MODULE_DIR_.'rbthemeblog/covers/'.$row['id_rbblog_post'].'.'.$row['cover'])) {
                    $row['banner'] = _MODULE_DIR_.'rbthemeblog/covers/'.$row['id_rbblog_post'].'.'.$row['cover'];
                    $row['banner_thumb'] = _MODULE_DIR_.'rbthemeblog/covers/'.$row['id_rbblog_post'].'-thumb.'.$row['cover'];
                    $row['banner_wide'] = _MODULE_DIR_.'rbthemeblog/covers/'.$row['id_rbblog_post'].'-wide.'.$row['cover'];
                }

                if (file_exists(_PS_MODULE_DIR_.'rbthemeblog/featured/'.$row['id_rbblog_post'].'.'.$row['featured'])) {
                    $row['featured'] = _MODULE_DIR_.'rbthemeblog/featured/'.$row['id_rbblog_post'].'.'.$row['featured'];
                }

                $row['url'] = self::getLink($row['link_rewrite'], $category_obj->link_rewrite, $id_lang);
                $row['category'] = $category_obj->name;
                $row['category_rewrite'] = $category_obj->link_rewrite;
                $row['category_url'] = $category_url;

                $row['allow_comments'] = self::checkIfAllowComments($row['allow_comments']);
                $row['comments'] = RbBlogComment::getCommentsCount((int) $row['id_rbblog_post']);

                $tags = RbBlogTag::getPostTags((int) $row['id_rbblog_post']);

                $row['tags'] = isset($tags[$id_lang]) && !empty($tags[$id_lang] > 0) ? $tags[$id_lang] : false;
                $row['post_type'] = RbBlogPostType::getSlugById((int) $row['id_rbblog_post_type']);

                if ($row['post_type'] == 'gallery') {
                    $row['gallery'] = RbBlogPostImage::getAllById((int) $row['id_rbblog_post']);
                }
            }
        } else {
            return;
        }

        return $result;
    }

    public static function getLink($rewrite, $category)
    {
        return Context::getContext()->link->getModuleLink(
        	'rbthemeblog',
        	'single',
        	array('rewrite' => $rewrite, 'rb_category' => $category)
        );
    }

    public static function getSearchLink($type = 'author', $keyword = false)
    {
        return Context::getContext()->link->getModuleLink(
        	'rbthemeblog',
        	'search',
        	array('type' => $type, 'keyword' => $keyword)
        );
    }

    public static function getByRewrite($rewrite = null, $id_lang = null)
    {
        if (!$rewrite) {
            return;
        }

        $sql = new DbQuery();
        $sql->select('l.id_rbblog_post');
        $sql->from('rbblog_post_lang', 'l');

        if ($id_lang) {
            $sql->where('l.link_rewrite = \''.$rewrite.'\' AND l.id_lang = '.(int) $id_lang);
        } else {
            $sql->where('l.link_rewrite = \''.$rewrite.'\'');
        }

        $result = Db::getInstance()->getValue($sql);

        if (!$result) {
            $sql = new DbQuery();
            $sql->select('l.id_rbblog_post');
            $sql->from('rbblog_post_lang', 'l');
            $sql->where('l.link_rewrite = \''.$rewrite.'\'');
            $searched_post = Db::getInstance()->getValue($sql);

            if ($searched_post) {
                $sql = new DbQuery();
                $sql->select('l.link_rewrite');
                $sql->from('rbblog_post_lang', 'l');
                $sql->where('l.id_lang = '.(int) $id_lang.' AND l.id_rbblog_post = '.(int) $searched_post);
                $rewrite = Db::getInstance()->getValue($sql);
            }

            if ($rewrite) {
                $sql = new DbQuery();
                $sql->select('l.id_rbblog_post');
                $sql->from('rbblog_post_lang', 'l');

                if ($id_lang) {
                    $sql->where('l.link_rewrite = \''.$rewrite.'\' AND l.id_lang = '.(int) $id_lang);
                } else {
                    $sql->where('l.link_rewrite = \''.$rewrite.'\'');
                }

                $post = new self(Db::getInstance()->getValue($sql), $id_lang);

                return $post;
            } else {
                return '404';
            }
        } else {
            $post = new self(Db::getInstance()->getValue($sql), $id_lang);

            return $post;
        }
    }

    public function getTags($id_lang)
    {
        if (is_null($this->tags)) {
            $this->tags = RbBlogTag::getPostTags($this->id);
        }

        if (!($this->tags && key_exists($id_lang, $this->tags))) {
            return '';
        }

        $result = '';

        foreach ($this->tags[$id_lang] as $tag_name) {
            $result .= $tag_name.', ';
        }

        return rtrim($result, ', ');
    }

    public static function getPageLink($page_nb, $type = false, $rewrite = false)
    {
        $url = Rbthemeblog::myRealUrl();
        $id_lang = Context::getContext()->language->id;
        $context = Context::getContext();

        $dispatcher = Dispatcher::getInstance();
        $params = array();
        $additionalParams = array();
        $params['p'] = $page_nb;

        if ($type == 'category') {
            if ($page_nb == 1 && isset($rewrite)) {
                return RbBlogCategory::getLink($rewrite, $id_lang);
            }

            if (isset($rewrite)) {
                $params['sb_category'] = $rewrite;

                return $url.$dispatcher->createUrl(
                	'module-rbthemeblog-categorypage',
                	$id_lang,
                	$params
                );
            }
        }

        if (Tools::getValue('y', 0)) {
            $additionalParams['y'] = (int) Tools::getValue('y');
        }

        if ($page_nb > 1) {
            return $context->link->getModuleLink(
            	'rbthemeblog',
            	'page',
            	array_merge($params, $additionalParams)
            );
        } else {
            return $context->link->getModuleLink('rbthemeblog', 'list', $additionalParams);
        }
    }

    public static function getPaginationLink(
    	$nb = false,
    	$sort = false,
    	$pagination = true,
    	$array = false
    ) {
        $vars = array();
        $vars_nb = array('n', 'search_query');
        $vars_sort = array('orderby', 'orderway');
        $vars_pagination = array('p');
        $url = Rbthemeblog::myRealUrl();

        foreach ($_GET as $k => $value) {
            if (Configuration::get('PS_REWRITING_SETTINGS') && ($k == 'isolang' || $k == 'id_lang')) {
                continue;
            }

            $if_nb = (!$nb || ($nb && !in_array($k, $vars_nb)));
            $if_sort = (!$sort || ($sort && !in_array($k, $vars_sort)));
            $if_pagination = (!$pagination || ($pagination && !in_array($k, $vars_pagination)));

            if ($if_nb && $if_sort && $if_pagination) {
                if (!is_array($value)) {
                    $vars[urlencode($k)] = $value;
                } else {
                    foreach (explode('&', http_build_query(array($k => $value), '', '&')) as $key => $val) {
                        $data = explode('=', $val);
                        $vars[urldecode($data[0])] = $data[1];
                    }
                }
            }
        }

        if (!$array) {
            if (count($vars)) {
                return $url.((Configuration::get('PS_REWRITING_SETTINGS') == 1) ? '?' : '&').http_build_query($vars, '', '&');
            } else {
                return $url;
            }
        }

        $vars['requestUrl'] = $url;

        return $vars;
    }

    public static function deleteCover($id_rbblog_post)
    {
        $post = new RbBlogPost((int) $id_rbblog_post);

        if (!Validate::isLoadedObject($post)) {
            return;
        }

        $tmp_location = _PS_TMP_IMG_DIR_.'rbthemeblog_'.$post->id.'.'.$post->cover;

        if (file_exists($tmp_location)) {
            @unlink($tmp_location);
        }

        $orig_location = _PS_MODULE_DIR_.'rbthemeblog/covers/'.$post->id.'.'.$post->cover;
        $thumb = _PS_MODULE_DIR_.'rbthemeblog/covers/'.$post->id.'-thumb.'.$post->cover;
        $thumbWide = _PS_MODULE_DIR_.'rbthemeblog/covers/'.$post->id.'-wide.'.$post->cover;

        if (file_exists($orig_location)) {
            @unlink($orig_location);
        }

        if (file_exists($thumb)) {
            @unlink($thumb);
        }

        if (file_exists($thumbWide)) {
            @unlink($thumbWide);
        }

        Db::getInstance()->update(
        	'rbblog_post',
        	array('cover' => ''),
        	'id_rbblog_post = '.$post->id
        );

        return true;
    }


    public static function deleteFeatured($id_rbblog_post)
    {
        $post = new RbBlogPost((int) $id_rbblog_post);

        if (!Validate::isLoadedObject($post)) {
            return;
        }

        $tmp_location = _PS_TMP_IMG_DIR_.'rbthemeblog_'.$post->id.'.'.$post->featured;

        if (file_exists($tmp_location)) {
            @unlink($tmp_location);
        }

        $orig_location = _PS_MODULE_DIR_.'rbthemeblog/featured/'.$post->id.'.'.$post->featured;

        if (file_exists($orig_location)) {
            @unlink($orig_location);
        }

        Db::getInstance()->update(
        	'rbblog_post',
        	array('featured' => ''),
        	'id_rbblog_post = '.$post->id
        );

        return true;
    }

    public static function regenerateThumbnails()
    {
        $posts = self::getAllAvailablePosts(Context::getContext()->language->id);

        if (!$posts) {
            return;
        }

        foreach ($posts as $post) {
            if (isset($post['cover']) && !empty($post['cover'])) {
                $tmp_location = _PS_TMP_IMG_DIR_.'rbthemeblog_'.$post['id_rbblog_post'].'.'.$post['cover'];

                if (file_exists($tmp_location)) {
                    @unlink($tmp_location);
                }

                $orig_location = _PS_MODULE_DIR_.'rbthemeblog/covers/'.$post['id_rbblog_post'].'.'.$post['cover'];
                $thumbLoc = _PS_MODULE_DIR_.'rbthemeblog/covers/'.$post['id_rbblog_post'].'-thumb.'.$post['cover'];
                $thumbWideLoc = _PS_MODULE_DIR_.'rbthemeblog/covers/'.$post['id_rbblog_post'].'-wide.'.$post['cover'];

                if (file_exists($thumbLoc)) {
                    @unlink($thumbLoc);
                }

                if (file_exists($thumbWideLoc)) {
                    @unlink($thumbWideLoc);
                }

                $thumbX = Configuration::get('RBTHEMEBLOG_BLOG_THUMB_X');
                $thumbY = Configuration::get('RBTHEMEBLOG_BLOG_THUMB_Y');
                $thumb_wide_X = Configuration::get('RBTHEMEBLOG_BLOG_THUMB_X_WIDE');
                $thumb_wide_Y = Configuration::get('RBTHEMEBLOG_BLOG_THUMB_Y_WIDE');
                $thumbMethod = Configuration::get('RBTHEMEBLOG_BLOG_THUMB_METHOD');

                try {
                    $thumb = PhpThumbFactory::create($orig_location);
                    $thumbWide = PhpThumbFactory::create($orig_location);
                } catch (Exception $e) {
                    continue;
                }

                if ($thumbMethod == '1') {
                    $thumb->adaptiveResize($thumbX, $thumbY);
                    $thumbWide->adaptiveResize($thumb_wide_X, $thumb_wide_Y);
                } elseif ($thumbMethod == '2') {
                    $thumb->cropFromCenter($thumbX, $thumbY);
                    $thumbWide->cropFromCenter($thumb_wide_X, $thumb_wide_Y);
                }

                $thumb->save($thumbLoc);
                $thumbWide->save($thumbWideLoc);

                unset($thumb);
                unset($thumbWide);
            }
        }
    }

    public static function changeRating($way = 'up', $id_rbblog_post = false)
    {
        if ($way == 'up') {
            $sql = 'UPDATE `'._DB_PREFIX_.'rbblog_post`
            SET `likes` = `likes` + 1
            WHERE id_rbblog_post = '.$id_rbblog_post;
        } elseif ($way == 'down') {
            $sql = 'UPDATE `'._DB_PREFIX_.'rbblog_post`
            SET `likes` = `likes` - 1
            WHERE id_rbblog_post = '.$id_rbblog_post;
        } else {
            return;
        }

        $result = Db::getInstance()->Execute($sql);

        $sql = 'SELECT `likes` FROM `'._DB_PREFIX_.'rbblog_post`
        WHERE id_rbblog_post = '.$id_rbblog_post;

        $res = Db::getInstance()->ExecuteS($sql);

        return $res;
    }

    public function increaseViewsNb()
    {
        $sql = 'UPDATE `'._DB_PREFIX_.'rbblog_post`
        SET `views` = `views` + 1
        WHERE id_rbblog_post = '.$this->id_rbblog_post;

        $result = Db::getInstance()->Execute($sql);

        return $result;
    }

    public function isAccessGranted()
    {
        if ($userGroups = Context::getContext()->customer->getGroups()) {
            if (!isset($this->id_rbblog_post)) {
                return false;
            }

            $tmpLinkGroups = unserialize($this->access);
            $linkGroups = array();

            foreach ($tmpLinkGroups as $groupID => $status) {
                if ($status) {
                    $linkGroups[] = $groupID;
                }
            }

            $intersect = array_intersect($userGroups, $linkGroups);

            if (count($intersect)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getRelatedProducts($ids)
    {
        if (!$ids) {
            return false;
        }

        $context = Context::getContext();
        $id_lang = $context->language->id;
        $front = true;

        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }

        $groups = FrontController::getCurrentCustomerGroups();
        $sql_groups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');

        $sql = 'SELECT p.*, product_shop.*, stock.`out_of_stock`,
        IFNULL(stock.`quantity`, 0) as quantity, pl.`description`, pl.`description_short`,
        MAX(product_attribute_shop.`id_product_attribute`) id_product_attribute,
                    pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
                    pl.`name`, MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` AS manufacturer_name,
                    DATEDIFF(
                        p.`date_add`,
                        DATE_SUB(
                            NOW(),
                            INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
                        )
                    ) > 0 AS new
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON (pa.id_product = p.id_product)
                '.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'
                '.Product::sqlStock('p', 0, false, $context->shop).'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
                    p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = '.(int) $id_lang.Shop::addSqlRestrictionOnLang('pl').'
                )
                LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
                Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int) $id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
                WHERE product_shop.`active` = 1
                '.($front ? ' AND p.`visibility` IN ("both", "catalog")' : '').'
                AND p.`id_product` IN ('.$ids.')
                AND p.`id_product` IN (
                    SELECT cp.`id_product`
                    FROM `'._DB_PREFIX_.'category_group` cg
                    LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
                    WHERE cg.`id_group` '.$sql_groups.'
                )
                GROUP BY product_shop.id_product
                ORDER BY `pl`.name ASC';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!$result) {
            return false;
        }

        return Product::getProductsProperties($id_lang, $result);
    }

    public static function checkIfAllowComments($flag)
    {
        $allow_comments = false;

        switch ($flag) {
            case 1:
                $allow_comments = true;
                break;

            case 2:
                $allow_comments = false;
                break;

            case 3:
                $allow_comments = Configuration::get('RBTHEMEBLOG_BLOG_COMMENT_ALLOW');
                break;

            default:
                $allow_comments = false;
        }

        return $allow_comments;
    }

    public function allowComments()
    {
        return self::checkIfAllowComments($this->allow_comments);
    }

    public function getPostCategory()
    {
        return new RbBlogCategory(
        	(int)$this->id_rbblog_category,
        	(int)Context::getContext()->language->id
        );
    }

    public function getPostType()
    {
        return new RbBlogPostType(
        	(int)$this->id_rbblog_post_type,
        	(int)Context::getContext()->language->id
        );
    }
}
