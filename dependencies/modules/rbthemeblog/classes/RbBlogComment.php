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

class RbBlogComment extends ObjectModel
{
	private static $commentHierarchy = array();

    public $id_rbblog_comment;
    public $id_rbblog_post;
    public $id_parent = 0;
    public $id_customer;
    public $id_guest;
    public $name;
    public $email;
    public $comment;
    public $active = 0;
    public $ip;
    public $date_add;
    public $date_upd;

    /**
    * @see ObjectModel::$definition
    */
    public static $definition = array(
        'table' => 'rbblog_comment',
        'primary' => 'id_rbblog_comment',
        'multilang' => false,
        'fields' => array(
            'id_rbblog_comment' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'id_rbblog_post' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'id_parent' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'id_customer' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'id_guest' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'name' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCleanHtml',
                'size' => 255
            ),
            'email' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCleanHtml',
                'size' => 140
            ),
            'comment' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCleanHtml'
            ),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'ip' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isCleanHtml',
                'size' => 255
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate'
            ),
            'date_upd' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate'
            ),
        ),
    );

    public function __construct($id_rbblog_comment = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id_rbblog_comment, $id_lang, $id_shop);
    }

    public static function getComments($id_rbblog_post, $withHierarchy = true)
    {
        $response = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT id_rbblog_comment, id_parent
            FROM '._DB_PREFIX_.'rbblog_comment
            WHERE id_rbblog_post = '.(int) $id_rbblog_post.'
            AND active = 1'
        );

        if ($withHierarchy) {
            return self::renderComments($response);
        } else {
            return $response;
        }
    }


    public static function renderComments(&$comments, $parent = 0, $depth = 0)
    {
        foreach ($comments as $key => $comment) {
            if ($comment['id_parent'] == $parent) {
                $rbBlogComment = new self($comment['id_rbblog_comment']);
                self::$commentHierarchy[$comment['id_rbblog_comment']]['depth'] = $depth;
                self::$commentHierarchy[$comment['id_rbblog_comment']]['id'] = (int) $rbBlogComment->id_rbblog_comment;
                self::$commentHierarchy[$comment['id_rbblog_comment']]['name'] = $rbBlogComment->name;
                self::$commentHierarchy[$comment['id_rbblog_comment']]['email'] = $rbBlogComment->email;
                self::$commentHierarchy[$comment['id_rbblog_comment']]['comment'] = $rbBlogComment->comment;
                self::$commentHierarchy[$comment['id_rbblog_comment']]['date_add'] = $rbBlogComment->date_add;

                unset($comments[$key]);
                self::renderComments($comments, $comment['id_rbblog_comment'], $depth + 1);
            }
        }

        reset($comments);

        return self::$commentHierarchy;
    }

    public static function getCommentsCount($id_rbblog_post)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT COUNT(id_rbblog_comment)
            FROM '._DB_PREFIX_.'rbblog_comment
            WHERE id_rbblog_post = '.(int) $id_rbblog_post.'
            AND active = 1'
        );
    }
}
