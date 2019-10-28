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

class RbBlogPostType extends ObjectModel
{
	public $id;
    public $id_rbblog_post_type;
    public $name;
    public $slug;
    public $description;

    public static $definition = array(
        'table'                         => 'rbblog_post_type',
        'primary'                       => 'id_rbblog_post_type',
        'multilang'                     => false,
        'fields'                        => array(
            'name'                      => array('type' => self::TYPE_STRING),
            'slug'                      => array('type' => self::TYPE_STRING),
            'description'               => array('type' => self::TYPE_STRING),
        ),
    );

    public function __construct($id_rbblog_post_type = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id_rbblog_post_type, $id_lang, $id_shop);
    }

    public static function getAll()
    {
        $sql = new DbQuery();
        $sql->select('id_rbblog_post_type, name');
        $sql->from('rbblog_post_type', 'sbpt');
        $sql->orderBy('id_rbblog_post_type ASC');

        return Db::getInstance()->executeS($sql);
    }

    public static function getSlugById($id_rbblog_post_type)
    {
        if (!Validate::isUnsignedInt($id_rbblog_post_type)) {
            die('getSlugByID - invalid ID');
        }

        $sql = new DbQuery();
        $sql->select('slug');
        $sql->from('rbblog_post_type', 'sbpt');
        $sql->where('id_rbblog_post_type = '.(int) $id_rbblog_post_type);

        return Db::getInstance()->getValue($sql);
    }

    public static function getIdBySlug($slug)
    {
        if (!Validate::isLinkRewrite($slug)) {
            die('getIdBySlug - invalid slug');
        }

        $sql = new DbQuery();
        $sql->select('id_rbblog_post_type');
        $sql->from('rbblog_post_type', 'sbpt');
        $sql->where('slug = \''.$slug.'\'');

        return Db::getInstance()->getValue($sql);
    }
}
