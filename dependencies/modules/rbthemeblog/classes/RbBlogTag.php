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

class RbBlogTag extends ObjectModel
{
	public $id_lang;
    public $name;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'rbblog_tag',
        'primary' => 'id_rbblog_tag',
        'fields' => array(
            'id_lang' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true
            ),
            'name' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
                'size' => 32
            ),
        ),
    );

    public function __construct($id = null, $name = null, $id_lang = null)
    {
        $this->def = Tag::getDefinition($this);
        $this->setDefinitionRetrocompatibility();

        if ($id) {
            parent::__construct($id);
        } elseif ($name && Validate::isGenericName($name) && $id_lang && Validate::isUnsignedId($id_lang)) {
            $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
            	'SELECT *
            	FROM `'._DB_PREFIX_.'rbblog_tag` t
            	WHERE `name` LIKE \''.pSQL($name).'\' AND `id_lang` = '.(int) $id_lang
            );

            if ($row) {
                $this->id = (int) $row['id_rbblog_tag'];
                $this->id_lang = (int) $row['id_lang'];
                $this->name = $row['name'];
            }
        }
    }

    public function add($autodate = true, $null_values = false)
    {
        if (!parent::add($autodate, $null_values)) {
            return false;
        } else if (Tools::getIsset('posts')) {
            return $this->setPosts(Tools::getValue('posts'));
        }

        return true;
    }

    public static function addTags($id_lang, $id_rbblog_post, $tag_list, $separator = ',')
    {
        if (!Validate::isUnsignedId($id_lang)) {
            return false;
        }

        if (!is_array($tag_list)) {
            $tag_list = array_filter(array_unique(array_map(
            	'trim',
            	preg_split(
            		'#\\'.$separator.'#',
            		$tag_list,
            		null,
            		PREG_SPLIT_NO_EMPTY
            	)
            )));
        }

        $list = array();

        if (is_array($tag_list)) {
            foreach ($tag_list as $tag) {
                if (!Validate::isGenericName($tag)) {
                    return false;
                }

                $tag_obj = new self(null, $tag, (int) $id_lang);

                if (!Validate::isLoadedObject($tag_obj)) {
                    $tag_obj->name = $tag;
                    $tag_obj->id_lang = (int) $id_lang;
                    $tag_obj->add();
                }

                if (!in_array($tag_obj->id, $list)) {
                    $list[] = $tag_obj->id;
                }
            }
        }

        $data = '';

        foreach ($list as $tag) {
            $data .= '('.(int) $tag.','.(int) $id_rbblog_post.'),';
        }

        $data = rtrim($data, ',');

        $sql = 'INSERT INTO `'._DB_PREFIX_.'rbblog_post_tag`
        (`id_rbblog_tag`, `id_rbblog_post`) VALUES '.$data;

        return Db::getInstance()->execute($sql);
    }

    public static function getMainTags($id_lang, $nb = 10)
    {
        $groups = FrontController::getCurrentCustomerGroups();
        $sql_groups = (count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
        	'SELECT t.`name`, COUNT(pt.`id_tag`) AS times
	        FROM `'._DB_PREFIX_.'rbblog_post_tag` pt
	        LEFT JOIN `'._DB_PREFIX_.'tag` t ON (t.id_tag = pt.id_tag)
	        LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.id_rbblog_post = pt.id_rbblog_post)
	        '.Shop::addSqlAssociation('product', 'p').'
	        WHERE t.`id_lang` = '.(int) $id_lang.'
	        AND product_shop.`active` = 1
	        AND product_shop.`id_rbblog_post` IN (
	            SELECT cp.`id_rbblog_post`
	            FROM `'._DB_PREFIX_.'category_group` cg
	            LEFT JOIN `'._DB_PREFIX_.'category_product` cp
	            ON (cp.`id_category` = cg.`id_category`)
	            WHERE cg.`id_group` '.pSQL($sql_groups).'
	        )
	        GROUP BY t.id_tag
	        ORDER BY times DESC
	        LIMIT 0, '.(int) $nb
	    );
    }

    public static function getPostTags($id_rbblog_post)
    {
        if (!$tmp = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
        	'SELECT t.`id_lang`, t.`name`
	        FROM '._DB_PREFIX_.'rbblog_tag t
	        LEFT JOIN '._DB_PREFIX_.'rbblog_post_tag pt ON (pt.id_rbblog_tag = t.id_rbblog_tag)
	        WHERE pt.`id_rbblog_post`='.(int) $id_rbblog_post
	    	)
    	) {
            return false;
        }

        $result = array();

        foreach ($tmp as $tag) {
            $result[$tag['id_lang']][] = $tag['name'];
        }

        return $result;
    }

    public function getPosts($associated = true, Context $context = null)
    {
        if (!$context) {
            $context = Context::getContext();
        }

        $id_lang = $this->id_lang ? $this->id_lang : $context->language->id;

        if (!$this->id && $associated) {
            return array();
        }

        $in = $associated ? 'IN' : 'NOT IN';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
        	'SELECT pl.`title`, pl.`id_rbblog_post`
	        FROM `'._DB_PREFIX_.'rbblog_post` p
	        LEFT JOIN `'._DB_PREFIX_.'rbblog_post_lang` pl
	        ON p.id_rbblog_post = pl.id_rbblog_post
	        WHERE pl.id_lang = '.(int) $id_lang.'
	        '.($this->id ? ('AND p.id_rbblog_post '.$in.' (SELECT pt.id_rbblog_post
	        FROM `'._DB_PREFIX_.'rbblog_post_tag` pt WHERE pt.id_rbblog_tag = '.(int) $this->id.')') : '').'
	        ORDER BY pl.`title`'
    	);
    }

    public function setPosts($array)
    {
        $result = Db::getInstance()->execute(
        	'DELETE FROM '._DB_PREFIX_.'rbblog_post_tag
        	WHERE id_rbblog_tag = '.(int) $this->id
        );

        if (is_array($array)) {
            $array = array_map('intval', $array);
            $ids = array();

            foreach ($array as $id_rbblog_post) {
                $ids[] = '('.(int) $id_rbblog_post.','.(int) $this->id.')';
            }

            if ($result) {
                $result &= Db::getInstance()->execute(
                	'INSERT INTO '._DB_PREFIX_.'rbblog_post_tag
                	(id_rbblog_post, id_rbblog_tag)
                	VALUES '.implode(',', $ids));
            }
        }

        return $result;
    }

    public static function deleteTagsForPost($id_rbblog_post)
    {
        return Db::getInstance()->execute(
        	'DELETE FROM `'._DB_PREFIX_.'rbblog_post_tag`
        	WHERE `id_rbblog_post` = '.(int) $id_rbblog_post
        );
    }

    public static function getLink($tag, $id_lang = null, $id_shop = null)
    {
        $url = Rbthemeblog::myRealUrl();

        $dispatcher = Dispatcher::getInstance();
        $params = array();
        $params['tag'] = $tag;

        return $url.$dispatcher->createUrl('rbthemeblog_tag', $id_lang, $params);
    }
}
