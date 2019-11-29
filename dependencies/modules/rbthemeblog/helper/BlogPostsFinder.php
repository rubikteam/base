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

class BlogPostsFinder
{
    private $id_shop;
    private $id_lang;
    private $customer;
    private $id_rbblog_category = 0;
    private $onlyActive = true;
    private $ignoredPosts;
    private $selectedPosts;
    private $featured = false;
    private $author = null;
    private $orderBy = 'sbp.date_add';
    private $orderWay = 'DESC';
    private $onlyPublished = true;
    private $checkForAccess = true;
    private $postType = false;
    private $limit = null;
    
    public function __construct(Context $context = null)
    {
        if (!$context) {
            $context = Context::getContext();
        }

        if ($context) {
            $this->id_shop = $context->shop->id;
            $this->id_lang = $context->language->id;
            $this->customer = $context->customer;
        }
    }

    public function setPostType($type)
    {
        $this->postType = $type;
        return $this;
    }

    public function getPostType()
    {
        return $this->postType;
    }

    public function setCheckForAccess($value)
    {
        $this->checkForAccess = $value;
        return $this;
    }

    public function getCheckForAccess()
    {
        return $this->checkForAccess;
    }

    public function setOnlyPublished($value)
    {
        $this->onlyPublished = $value;
        return $this;
    }

    public function getOnlyPublished()
    {
        return $this->onlyPublished;
    }

    public function setOnlyActive($value)
    {
        $this->onlyActive = $value;
        return $this;
    }

    public function getOnlyActive()
    {
        return (bool) $this->onlyActive;
    }

    public function setIdShop($id_shop)
    {
        $this->id_shop = $id_shop;
        return $this;
    }

    public function getIdShop()
    {
        return (int) $this->id_shop;
    }

    public function setIdLang($id_lang)
    {
        $this->id_lang = $id_lang;
        return $this;
    }

    public function getIdLang()
    {
        return (int) $this->id_lang;
    }

    public function setCustomer($id_customer)
    {
        $this->customer = $customer;
        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setIdCategory($id_rbblog_category)
    {
        $this->id_rbblog_category = $id_rbblog_category;
        return $this;
    }

    public function getIdCategory()
    {
        return (int) $this->id_rbblog_category;
    }

    public function setIgnoredPosts($ids)
    {
        if (!is_array($ids)) {
            return;
        }

        $this->ignoredPosts = $ids;
        return $this;
    }

    public function getIgnoredPosts()
    {
        if (is_array($this->ignoredPosts)) {
            return $this->ignoredPosts;
        }
        return array();
    }

    public function setSelectedPosts($ids)
    {
        if (!is_array($ids)) {
            return;
        }

        $this->selectedPosts = $ids;
        return $this;
    }

    public function getSelectedPosts()
    {
        if (is_array($this->selectedPosts)) {
            return $this->selectedPosts;
        }
        return array();
    }

    public function setFeatured($value)
    {
        $this->featured = (bool) $value;
        return $this;
    }

    public function getFeatured()
    {
        return $this->featured;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setOrderBy($field)
    {
        $this->orderBy = $field;
        return $this;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderWay($way)
    {
        $this->orderWay = $way;
        return $this;
    }

    public function getOrderWay()
    {
        return $this->orderWay;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Return posts from Finder
     * @return array Posts
     */
    public function findPosts()
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('rbblog_post', 'sbp');

        $sql->innerJoin('rbblog_post_lang', 'l', 'sbp.id_rbblog_post = l.id_rbblog_post AND l.id_lang = '.$this->getIdLang());

        $sql->innerJoin('rbblog_post_shop', 'sbps', 'sbp.id_rbblog_post = sbps.id_rbblog_post AND sbps.id_shop = '.$this->getIdShop());

        if ($this->getOnlyActive()) {
            $sql->where('sbp.active = 1');
        }

        if ($this->getIdCategory() > 0) {
            $childrens = RbBlogCategory::getChildrens($this->getIdCategory());
            if ($childrens && !empty($childrens)) {
                $child_categories = array($this->getIdCategory());
                foreach ($childrens as $child) {
                    $child_categories[] = $child['id_rbblog_category'];
                }
                $sql->where('sbp.id_rbblog_category IN ('.implode(',', $child_categories).')');
            } else {
                $sql->where('sbp.id_rbblog_category = '.$this->getIdCategory());
            }
        }

        if (count($this->getIgnoredPosts())) {
            $sql->where('sbp.id_rbblog_post NOT IN ('.implode(',', $this->getIgnoredPosts()).')');
        }

        if (count($this->getSelectedPosts())) {
            $sql->where('sbp.id_rbblog_post IN ('.implode(',', $this->getSelectedPosts()).')');
        }

        if ($this->getFeatured()) {
            $sql->where('sbp.is_featured = 1');
        }

        if ($this->getPostType()) {
            $sql->where('sbp.id_rbblog_post_type = '.(int) $this->getPostType());
        }

        if ($this->getAuthor() && Configuration::get('RB_BLOG_POST_BY_AUTHOR')) {
            $sql->where('sbp.author = \''.pSQL($this->getAuthor()).'\'');
        }

        if ($this->getOnlyPublished()) {
            $sql->where('sbp.date_add <= \''.RbBlogHelper::now(Configuration::get('RBTHEMEBLOG_BLOG_TIMEZONE')).'\'');
        }

        $sql->orderBy($this->getOrderBy().' '.$this->getOrderWay());

        if ($this->getLimit()) {
            $sql->limit($this->getLimit());
        }
        
        $result = Db::getInstance()->executeS($sql);

        if (!$result) {
            return array();
        }

        if ($this->getCheckForAccess()) {
            foreach ($result as $key => $post) {
                if ($userGroups = $this->getCustomer()->getGroups()) {
                    $tmpLinkGroups = unserialize($post['access']);
                    $linkGroups = array();

                    foreach ($tmpLinkGroups as $groupID => $status) {
                        if ($status) {
                            $linkGroups[] = $groupID;
                        }
                    }

                    $intersect = array_intersect($userGroups, $linkGroups);
                    if (!count($intersect)) {
                        unset($result[$key]);
                    }
                }
            }
        }

        return $this->getPostProperties($result, $this->getIdLang());
    }

    private function getPostProperties($posts, $id_lang)
    {
        foreach ($posts as &$row) {
            $blogCategory = new RbBlogCategory($row['id_rbblog_category'], $id_lang);

            $row['url'] = RbBlogPost::getLink($row['link_rewrite'], $blogCategory->link_rewrite, $id_lang);
            $row['category'] = $blogCategory->name;
            $row['category_rewrite'] = $blogCategory->link_rewrite;
            $row['category_url'] = $blogCategory->getObjectLink();

            if (file_exists(_PS_MODULE_DIR_.'rbthemeblog/covers/'.$row['id_rbblog_post'].'.'.$row['cover'])) {
                $row['banner'] = _MODULE_DIR_.'rbthemeblog/covers/'.$row['id_rbblog_post'].'.'.$row['cover'];
                $row['banner_thumb'] = _MODULE_DIR_.'rbthemeblog/covers/'.$row['id_rbblog_post'].'-thumb.'.$row['cover'];
                $row['banner_wide'] = _MODULE_DIR_.'rbthemeblog/covers/'.$row['id_rbblog_post'].'-wide.'.$row['cover'];
            }

            if (file_exists(_PS_MODULE_DIR_.'rbthemeblog/featured/'.$row['id_rbblog_post'].'.'.$row['featured'])) {
                $row['featured'] = _MODULE_DIR_.'rbthemeblog/featured/'.$row['id_rbblog_post'].'.'.$row['featured'];
            }

            $row['allow_comments'] = RbBlogPost::checkIfAllowComments($row['allow_comments']);
            $row['comments'] = RbBlogComment::getCommentsCount((int) $row['id_rbblog_post']);

            $tags = RbBlogTag::getPostTags((int) $row['id_rbblog_post']);
            $row['tags'] = isset($tags[$id_lang]) && !empty($tags[$id_lang] > 0) ? $tags[$id_lang] : false;

            $row['post_type'] = RbBlogPostType::getSlugById((int) $row['id_rbblog_post_type']);
            if ($row['post_type'] == 'gallery') {
                $row['gallery'] = RbBlogPostImage::getAllById((int) $row['id_rbblog_post']);
            }
        }

        return $posts;
    }
}
