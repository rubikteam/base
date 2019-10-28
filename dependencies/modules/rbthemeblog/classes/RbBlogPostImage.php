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

class RbBlogPostImage extends ObjectModel
{
	public $id;
    public $id_rbblog_post_image;
    public $id_rbblog_post;
    public $position;
    public $image;

    public static $definition = array(
        'table' => 'rbblog_post_image',
        'primary' => 'id_rbblog_post_image',
        'multilang' => false,
        'fields' => array(
            'id_rbblog_post' => array(
                'type' => self::TYPE_INT,
                'required' => true,
                'validate' => 'isUnsignedInt'
            ),
            'position' => array(
                'type' => self::TYPE_INT,
                'required' => true,
                'validate' => 'isUnsignedInt'
            ),
            'image' => array(
                'type' => self::TYPE_STRING
            ),
        ),
    );

    public function __construct($id_rbblog_post_image = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id_rbblog_post_image, $id_lang, $id_shop);
    }

    public function delete()
    {
        if (!parent::delete()) {
            return false;
        }

        if (!$this->deletePostImage()) {
            return false;
        }

        if (!$this->cleanPositions($this->id_rbblog_post)) {
            return false;
        }

        return true;
    }

    public static function getAll()
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('rbblog_post_image', 'sbpi');
        $sql->orderBy('position ASC');

        return Db::getInstance()->executeS($sql);
    }

    public static function getAllById($id_rbblog_post)
    {
        if (!Validate::isUnsignedInt($id_rbblog_post)) {
            die('getAllById - invalid ID');
        }

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('rbblog_post_image', 'sbpi');
        $sql->where('id_rbblog_post = '.(int) $id_rbblog_post);
        $sql->orderBy('position ASC');

        return Db::getInstance()->executeS($sql);
    }

    public static function getNewLastPosition($id_rbblog_post)
    {
        return Db::getInstance()->getValue(
        	'SELECT IFNULL(MAX(position),0)+1
            FROM `'._DB_PREFIX_.'rbblog_post_image`
            WHERE `id_rbblog_post` = '.(int) $id_rbblog_post
        );
    }

    public function cleanPositions($id_rbblog_post)
    {
        $result = Db::getInstance()->executeS(
        	'SELECT `id_rbblog_post_image`
            FROM `'._DB_PREFIX_.'rbblog_post_image`
            WHERE `id_rbblog_post` = '.(int) $id_rbblog_post.'
            ORDER BY `position`'
        );

        $sizeof = count($result);

        for ($i = 0; $i < $sizeof; ++$i) {
            Db::getInstance()->execute(
            	'UPDATE `'._DB_PREFIX_.'rbblog_post_image`
                SET `position` = '.($i + 1).'
                WHERE `id_rbblog_post_image` = '.(int) $result[$i]['id_rbblog_post_image']
            );
        }

        return true;
    }

    public function deletePostImage()
    {
        $response = true;

        $images = glob(_RBBLOG_GALLERY_DIR_.(int) $this->id.'-'.(int) $this->id_rbblog_post.'-*');

        foreach ($images as $image) {
            $response &= @unlink($image);
        }

        return $response;
    }
}
