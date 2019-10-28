<?php
/**
 * Blog for PrestaShop module by Krystian Podemski from PrestaHome.
 *
 * @author    Krystian Podemski <krystian@prestahome.com>
 * @copyright Copyright (c) 2014-2018 Krystian Podemski - www.PrestaHome.com / www.Podemski.info
 * @license   You only can use module, nothing more!
 */
require_once _PS_MODULE_DIR_.'rbthemeblog/rbthemeblog.php';

class RbBlogHelper
{
    public static function uploadImage()
    {
        // Nothing to do here atm
    }

    public static function now($str_user_timezone)
    {
        $date = new DateTime('now');
        // $date->setTimezone(new DateTimeZone($str_user_timezone));
        $str_server_now = $date->format('Y.m.d H:i:s');

        return $str_server_now;
    }

    public static function checkForArchives($type)
    {
        $id_shop = Context::getContext()->shop->id;

        switch ($type) {
            case 'year':
                $sql = new DbQuery();
                $sql->select('YEAR(sbp.date_add) as year, MONTH(sbp.date_add) as month, COUNT(sbp.id_rbblog_post) as nbPosts');
                $sql->from('rbblog_post', 'sbp');
                $sql->innerJoin('rbblog_post_shop', 'sbps', 'sbp.id_rbblog_post = sbps.id_rbblog_post AND sbps.id_shop = '.(int) $id_shop);
                $sql->where('sbp.date_add <= \''.RbBlogHelper::now(Configuration::get('RBTHEME_BLOG_TIMEZONE')).'\'');
                $sql->where('sbp.active = 1');
                $sql->groupBy('YEAR(sbp.date_add)');
                $sql->orderBy('year DESC');

                $result = Db::getInstance()->executeS($sql);

                return $result;

                break;

            case 'month':
                # code...
                break;    
        }
    }
}
