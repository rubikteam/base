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

class UniteFunctionsPSRb
{

    public static $urlSite;
    public static $urlAdmin;
    public static $prestaprds;

    const SORTBY_NONE = "none";
    const SORTBY_ID = "ID";
    const SORTBY_PRICE = "price";
    const SORTBY_SALES = "sales";
    const SORTBY_AUTHOR = "author";
    const SORTBY_TITLE = "title";
    const SORTBY_SLUG = "name";
    const SORTBY_DATE = "date";
    const SORTBY_LAST_MODIFIED = "modified";
    const SORTBY_RAND = "rand";
    const SORTBY_COMMENT_COUNT = "comment_count";
    const SORTBY_MENU_ORDER = "menu_order";
    const ORDER_DIRECTION_ASC = "ASC";
    const ORDER_DIRECTION_DESC = "DESC";
    const THUMB_SMALL = "thumbnail";
    const THUMB_MEDIUM = "medium";
    const THUMB_LARGE = "large";
    const THUMB_FULL = "full";
    const STATE_PUBLISHED = "publish";
    const STATE_DRAFT = "draft";

    public static function initStaticVars()
    {
        self::$urlSite = __PS_BASE_URI__;

        if (Tools::substr(self::$urlSite, -1) != "/") {
            self::$urlSite .= "/";
        }
        
        self::$urlAdmin = adminUrl();
    }

    public static function getArrSortBy()
    {
        $arr = array();
        $arr[self::SORTBY_ID] = "ID";
        $arr[self::SORTBY_DATE] = "Date";
        $arr[self::SORTBY_TITLE] = "Title";
        $arr[self::SORTBY_PRICE] = "Price";
        $arr[self::SORTBY_RAND] = "Random";

        return($arr);
    }

    public static function getArrImageSize()
    {
        $arr = array();

        $img_type = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT * FROM `' . _DB_PREFIX_ . 'image_type` where `products` = 1'
        );

        foreach ($img_type as $type) {
            $arr[$type['name']] = $type['name'];
        }

        return($arr);
    }

    public static function getArrSortDirection()
    {
        $arr = array();
        $arr[self::ORDER_DIRECTION_DESC] = "Descending";
        $arr[self::ORDER_DIRECTION_ASC] = "Ascending";

        return($arr);
    }

    public static function getBlogID()
    {
        return null;
    }

    public static function isMultisite()
    {
        return false;
    }

    public static function isDBTableExists($tableName)
    {
        if (class_exists('Rbthemeslider')) {
            $psdb = Rbthemeslider::$psdb;
        } else {
            $psdb = rbDbClass::rbDbInstance();
        }

        if (empty($tableName)) {
            UniteFunctionsRb::throwError("Empty table name!!!");
        }

        $tableName = $psdb->prefix . $tableName;
        $sql = "show tables like '$tableName'";

        $table = $psdb->getVar($sql);

        if ($table == $tableName) {
            return(true);
        }

        return(false);
    }

    public static function getPathBase()
    {
        return ABSPATH;
    }

    public static function getPathUploads()
    {
        if (self::isMultisite()) {
            if (!defined("BLOGUPLOADDIR")) {
                $pathBase = self::getPathBase();

                $pathContent = $pathBase . "wp-content/uploads/";
            } else {
                $pathContent = BLOGUPLOADDIR;
            }
        } else {
            $pathContent = PS_CONTENT_DIR;

            if (!empty($pathContent)) {
                $pathContent .= "/";
            } else {
                $pathBase = self::getPathBase();

                $pathContent = $pathBase . "wp-content/uploads/";
            }
        }

        return($pathContent);
    }

    public static function getUrlUploads()
    {
        $baseUrl = contentUrl() . "/";

        return($baseUrl);
    }

    public static function importMediaImg($file_url, $folder, $filename)
    {
        $psdb = rbDbClass::rbDbInstance();
        $tempname = Tools::substr($filename, 0, strrpos($filename, '.'));
        $extension = Tools::substr($filename, strrpos($filename, '.'));

        $found = $psdb->getVar(
            "SELECT COUNT(*) AS found FROM " . $psdb->prefix . GlobalsRbSlider::TABLE_ATTACHMENT_IMAGES . "
            WHERE file_name LIKE '{$tempname}%'"
        );

        if ($found && $found > 0) {
            $filename = $tempname . '-' . (++$found) . $extension;
        }

        copy($file_url, $folder . $filename);
        $imagearray = array('file_name' => $filename);
        $mysqli = $psdb->insert($psdb->prefix . GlobalsRbSlider::TABLE_ATTACHMENT_IMAGES, $imagearray);
        $imgid = $psdb->insertID();

        if (!empty($mysqli) && is_numeric($imgid)) {
            $sizes = array(
                GlobalsRbSlider::IMAGE_SIZE_THUMBNAIL,
                GlobalsRbSlider::IMAGE_SIZE_MEDIUM,
                GlobalsRbSlider::IMAGE_SIZE_LARGE
            );

            $filerealname = Tools::substr($filename, 0, strrpos($filename, '.'));

            $fileext = Tools::substr(
                $filename,
                strrpos($filename, '.'),
                Tools::strlen($filename) - Tools::strlen($filerealname)
            );

            list($width, $height) = getimagesize($folder . $filename);
            $count = 0;

            foreach ($sizes as $size) {
                $nsize = self::getImgAspectRatio(array($width, $height, $size));
                $newfilename = "{$filerealname}-{$size}x{$size}{$fileext}";

                if (++$count > 1) {
                    ImageManager::resize($folder . $filename, $folder . $newfilename, $nsize[0], $nsize[1]);
                } else {
                    $posx = ($width / 2) - ($size / 2);

                    $posy = ($height / 2) - ($size / 2);

                    ImageManager::cut(
                        $folder . $filename,
                        $folder . $newfilename,
                        $size,
                        $size,
                        Tools::substr($fileext, 1),
                        $posx,
                        $posy
                    );
                }
            }

            return array("id" => $imgid, "path" => 'uploads/' . $filename);
        }
    }

    public static function importMedia($file_url)
    {
        $folder = ABSPATH . '/uploads/';
        $file_urls = explode('#', $file_url);
        $randnum = rand(0000000, 9999999);
        $filename = basename($file_urls[1]);

        if ($fp = fopen($file_url, "r")) {
            fclose($fp);

            return self::importMediaImg($file_url, $folder, $filename);
        }

        return false;
    }

    protected static function getImgAspectRatio($params)
    {
        $newdim = array();

        if ($params[2] == $params[0] or $params[2] == $params[1]) {
            return $params;
        } elseif ($params[0] > $params[1]) {
            $r = $params[2] / $params[1];
            $nw = round($params[0] * $r);
            $nh = round($params[1] * $r);

            if ($nw >= $params[0] or $nh >= $params[1]) {
                $newdim[] = $params[0];

                $newdim[] = $params[1];
            } else {
                $newdim[] = $nw;

                $newdim[] = $nh;
            }
        } elseif ($params[0] < $params[1]) {
            $r = $params[2] / $params[0];
            $nw = round($params[0] * $r);
            $nh = round($params[1] * $r);

            if ($nw >= $params[0] or $nh >= $params[1]) {
                $newdim[] = $params[0];

                $newdim[] = $params[1];
            } else {
                $newdim[] = $nw;

                $newdim[] = $nh;
            }
        }

        return $newdim;
    }

    public static function getImagePathFromURL($urlImage)
    {
        $baseUrl = self::getUrlUploads();
        $pathLower = Tools::strtolower($urlImage);

        if (strpos($pathLower, "http://") !== false || strpos($pathLower, "www.") === 0) {
            return($urlImage);
        }

        $pathImage = str_replace($baseUrl, "", $urlImage);

        return($pathImage);
    }

    public static function getImageRealPathFromUrl($urlImage)
    {
        $filepath = self::getImagePathFromURL($urlImage);
        $realPath = UniteFunctionsPSRb::getPathUploads() . $filepath;

        return($realPath);
    }

    public static function getImageUrlFromPath($pathImage)
    {
        $pathLower = Tools::strtolower($pathImage);

        if (strpos($pathLower, "http://") !== false || strpos($pathLower, "www.") === 0) {
            return($pathImage);
        }

        $urlImage = self::getUrlUploads() . $pathImage;

        return($urlImage);
    }

    public static function getCategoriesAssoc($taxonomy = "category")
    {
        $arrCats = array();

        if (strpos($taxonomy, ",") !== false) {
            $arrTax = explode(",", $taxonomy);

            foreach ($arrTax as $tax) {
                $cats = self::getCategoriesAssoc($tax);

                $arrCats = array_merge($arrCats, $cats);
            }
        }

        return($arrCats);
    }

    public static function getPostTypeTitle($postType)
    {
        $objType = $postType;

        if (empty($objType)) {
            return($postType);
        }

        $title = $objType->labels->singular_name;

        if (!empty($title)) {
            return($title);
        } else {
            return($postType);
        }
    }

    public static function getPostTypeTaxomonies($postType)
    {
        $arrNames = array();

        return($arrNames);
    }

    public static function getPostTypeTaxonomiesString($postType)
    {
        $arrTax = self::getPostTypeTaxomonies($postType);
        $strTax = "";

        foreach (array_keys($arrTax) as $name) {
            if (!empty($strTax)) {
                $strTax .= ",";
            }

            $strTax .= $name;
        }

        return($strTax);
    }

    public static function getCMSPages()
    {
        $pages = CMS::listCms(null, false, true);
        $npages = array();

        if (!empty($pages)) {
            foreach ($pages as $page) {
                $npages['CMS_' . $page['id_cms']] = $page['meta_title'];
            }
        }

        return $npages;
    }

    public static function getNestedCategories(
        $root_category = null,
        $id_lang = false,
        $active = true,
        $groups = null,
        $use_shop_restriction = true,
        $sql_filter = '',
        $sql_sort = '',
        $sql_limit = ''
    ) {
        if (@Rbthemeslider::getIsset($root_category) && !Validate::isInt($root_category)) {
            die(Tools::displayError());
        }

        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }

        if (@Rbthemeslider::getIsset($groups) && Group::isFeatureActive() && !is_array($groups)) {
            $groups = (array) $groups;
        }

        $cache_id = 'Category::getNestedCategories_' . md5(
            (int) $root_category . (int) $id_lang . (int) $active . (int) $active.
            (@Rbthemeslider::getIsset($groups) && Group::isFeatureActive() ? implode('', $groups) : '')
        );

        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS(
                'SELECT c.*, cl.*
                FROM `' . _DB_PREFIX_ . 'category` c
                ' . ($use_shop_restriction ? Shop::addSqlAssociation('category', 'c') : '') . '
                LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON c.`id_category` = cl.`id_category`' . Shop::addSqlRestrictionOnLang('cl') . '
                ' . (@Rbthemeslider::getIsset($groups) && Group::isFeatureActive() ? 'LEFT JOIN `' . _DB_PREFIX_ . 'category_group` cg ON c.`id_category` = cg.`id_category`' : '') . '
                ' . (@Rbthemeslider::getIsset($root_category) ? 'RIGHT JOIN `' . _DB_PREFIX_ . 'category` c2 ON c2.`id_category` = ' . (int) $root_category . ' AND c.`nleft` >= c2.`nleft` AND c.`nright` <= c2.`nright`' : '') . '
                WHERE 1 ' . $sql_filter . ' ' . ($id_lang ? 'AND `id_lang` = ' . (int) $id_lang : '') . '
                ' . ($active ? ' AND c.`active` = 1' : '') . '
                ' . (@Rbthemeslider::getIsset($groups) && Group::isFeatureActive() ? ' AND cg.`id_group` IN (' . implode(',', $groups) . ')' : '') . '
                ' . (!$id_lang || (@Rbthemeslider::getIsset($groups) && Group::isFeatureActive()) ? ' GROUP BY c.`id_category`' : '') . '
                ' . ($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC') . '
                ' . ($sql_sort == '' && $use_shop_restriction ? ', category_shop.`position` ASC' : '') . '
                ' . ($sql_limit != '' ? $sql_limit : '')
            );

            $categories = array();
            $buff = array();

            if (!@Rbthemeslider::getIsset($root_category)) {
                $root_category = 1;
            }

            foreach ($result as $row) {
                $current = &$buff[$row['id_category']];
                $current = $row;

                if ($row['id_category'] == $root_category) {
                    $categories[$row['id_category']] = &$current;
                } else {
                    $buff[$row['id_parent']]['children'][$row['id_category']] = &$current;
                }
            }

            Cache::store($cache_id, $categories);
        }

        return Cache::retrieve($cache_id);
    }

    public static function getPrestaProdChildCat($cats)
    {
        foreach ($cats as $cat) {
            if ($cat['id_shop'] == Context::getContext()->shop->id) {
                self::$prestaprds['category_' . $cat['id_category']] = $cat['name'];
            }

            if (@Rbthemeslider::getIsset($cat['children']) && !empty($cat['children'])) {
                self::getPrestaProdChildCat($cat['children']);
            }
        }
    }

    public static function getPrestaProdCat()
    {
        if (method_exists('Category', 'getNestedCategories')) {
            $cats = Category::getNestedCategories(null, (int) Context::getContext()->language->id, true);
        } else {
            $cats = self::getNestedCategories(null, (int) Context::getContext()->language->id, true);
        }

        self::$prestaprds = array();

        if (!empty($cats)) {
            foreach ($cats as $cat) {
                if ($cat['id_shop'] == Context::getContext()->shop->id) {
                    self::$prestaprds['category_' . $cat['id_category']] = $cat['name'];
                }

                if (@Rbthemeslider::getIsset($cat['children']) && !empty($cat['children'])) {
                    self::getPrestaProdChildCat($cat['children']);
                }
            }
        }

        return self::$prestaprds;
    }

    public static function getPrestaProdduct()
    {
        $prds = array();
        $prducts = array();

        if (method_exists('Product', 'getSimpleProducts')) {
            $id_lang = (int) Context::getContext()->language->id;
            $prds = Product::getSimpleProducts($id_lang);
        } else {
            $prds = self::getSimpleProducts();
        }

        if (@Rbthemeslider::getIsset($prds) && !empty($prds)) {
            $i = 0;

            foreach ($prds as $prd) {
                $prducts[$i]['id_product'] = 'product_' . $prd['id_product'];
                $prducts[$i]['name'] = $prd['name'];
                $i++;
            }

            return $prducts;
        } else {
            return false;
        }
    }

    public static function getPostTypesAssoc($arrPutToTop = array())
    {
        $arrPostTypes = array('product' => 'product');

        return($arrPostTypes);
    }

    public static function getPostsByIDs($strIDs)
    {
        $arrPosts = array();
        $arrPrd = array();
        $id_lang = Context::getContext()->language->id;
        $id_shop = Context::getContext()->shop->id;

        if (is_string($strIDs)) {
            $arr = explode(",", $strIDs);
        }

        $i = 0;

        foreach ($arr as $ar) {
            $product = new Product($ar, true, $id_lang, $id_shop);
            $product = (array) $product;
            $product['id_product'] = (int) $ar;
            $lnk = new Link();
            $prd_link = $lnk->getProductLink($product);
            $arrPrd['id_product'] = $ar;
            $arrPrd['link'] = $prd_link;
            $productArr = Product::getProductsProperties($id_lang, array($product));
            $productArr = $productArr[0];

            foreach ($productArr as $key => $value) {
                if ($key == 'id_category_default') {
                    $arrPrd['default_category'] = self::getCategoryNameById($value);
                } else {
                    $arrPrd[$key] = $value;
                }
            }

            $arrPosts[$i] = $arrPrd;
            $i++;
        }

        return($arrPosts);
    }

    public static function getPost($postID)
    {
        $products = new Product(
            $postID,
            true,
            Context::getcontext()->language->id,
            Context::getcontext()->shop->id
        );

        $post = $products;

        if (empty($post)) {
            UniteFunctionsRb::throwError("Post with id: $postID not found");
        }

        $arrPost = (array) $post;

        return($arrPost);
    }

    public static function getAttachmentImage($thumbID, $size = self::THUMB_FULL)
    {
        $arrImage = psGetAttachmentImageSrc($thumbID, $size);

        if (empty($arrImage)) {
            return(false);
        }

        $output = array();
        $output["url"] = UniteFunctionsRb::getVal($arrImage, 0);
        $output["width"] = UniteFunctionsRb::getVal($arrImage, 1);
        $output["height"] = UniteFunctionsRb::getVal($arrImage, 2);

        return($output);
    }

    public static function getUrlAttachmentImage($thumbID, $size = self::THUMB_FULL)
    {
        $arrImage = psGetAttachmentImageSrc($thumbID, $size);

        if (empty($arrImage)) {
            return(false);
        }

        $url = UniteFunctionsRb::getVal($arrImage, 0);

        return($url);
    }

    public static function getUrlSlidesEditByCatID($catID)
    {
        $url = self::$urlAdmin;

        $url .= "edit.php?s&post_status=all&post_type=post&action=-1&m=0&cat="
        . $catID . "&paged=1&mode=list&action2=-1";

        return($url);
    }

    public static function getUrlEditPost($postID)
    {
        $url = self::$urlAdmin;
        $url .= "post.php?post=" . $postID . "&action=edit";

        return($url);
    }

    public static function getUrlNewPost()
    {
        $url = self::$urlAdmin;
        $url .= "post-new.php";

        return($url);
    }

    public static function getExcerptById($postID, $limit = 55)
    {
        $excerpt = '';

        return $excerpt;
    }

    public static function getUserDisplayName($userID)
    {
        $displayName = 'pro_or_blog_author_name';

        return($displayName);
    }

    public static function getCategoriesByIDs($arrIDs, $strTax = null)
    {
        if (empty($arrIDs)) {
            return(array());
        }

        if (is_string($arrIDs)) {
            $strIDs = $arrIDs;
        } else {
            $strIDs = implode(",", $arrIDs);
        }

        $args = array();
        $args["include"] = $strIDs;

        if (!empty($strTax)) {
            if (is_string($strTax)) {
                $strTax = explode(",", $strTax);
            }

            $args["taxonomy"] = $strTax;
        }

        $arrCats = 'All_Pro_or_blog_categories';

        if (!empty($arrCats)) {
            $arrCats = UniteFunctionsRb::convertStdClassToArray($arrCats);
        }

        return($arrCats);
    }

    public static function getCategoriesByIDsShort($arrIDs, $strTax = null)
    {
        $arrCats = self::getCategoriesByIDs($arrIDs, $strTax);
        $arrNew = array();

        foreach ($arrCats as $cat) {
            $catID = $cat["term_id"];
            $catName = $cat["name"];
            $arrNew[$catID] = $catName;
        }

        return($arrNew);
    }

    public static function getTagsHtmlList($postID)
    {
        $tagList = 'Set All Tag List';

        return($tagList);
    }

    public static function convertPostDate($date)
    {
        return($date);
    }

    public static function getPostTypesWithTaxomonies()
    {
        $arrPostTypes = self::getPostTypesAssoc();

        foreach ($arrPostTypes as $postType => $title) {
            $arrTaxomonies = self::getPostTypeTaxomonies($postType);

            $arrPostTypes[$postType] = $arrTaxomonies;
        }

        return($arrPostTypes);
    }

    public static function getPostTypesWithCats()
    {
        $arrPostTypes = self::getPostTypesWithTaxomonies();
        $arrPostTypesOutput = array();

        foreach ($arrPostTypes as $name => $arrTax) {
            $arrTaxOutput = array();

            foreach ($arrTax as $taxName => $taxTitle) {
                $cats = self::getCategoriesAssoc($taxName);

                if (!empty($cats)) {
                    $arrTaxOutput[] = array(
                        "name" => $taxName,
                        "title" => $taxTitle,
                        "cats" => $cats);
                }
            }

            $arrPostTypesOutput[$name] = $arrTaxOutput;
        }

        return($arrPostTypesOutput);
    }

    public static function getUrlContent()
    {
        $baseUrl = contentUrl() . "/";

        if (is_ssl()) {
            $baseUrl = str_replace("http://", "https://", $baseUrl);
        }

        return($baseUrl);
    }

    public static function getPathContent()
    {
        if (self::isMultisite()) {
            if (!defined("BLOGUPLOADDIR")) {
                $pathBase = self::getPathBase();
                $pathContent = $pathBase . "wp-content/";
            } else {
                $pathContent = BLOGUPLOADDIR;
            }
        } else {
            $pathContent = PS_CONTENT_DIR;

            if (!empty($pathContent)) {
                $pathContent .= "/";
            } else {
                $pathBase = self::getPathBase();
                $pathContent = $pathBase . "wp-content/";
            }
        }

        return($pathContent);
    }

    public static function getCatAndTaxData($catIDs)
    {
        if (is_string($catIDs)) {
            $catIDs = trim($catIDs);

            if (empty($catIDs)) {
                return(array("tax" => "", "cats" => ""));
            }

            $catIDs = explode(",", $catIDs);
        }

        $strCats = "";
        $arrTax = array();

        foreach ($catIDs as $cat) {
            if (strpos($cat, "option_disabled") === 0) {
                continue;
            }

            $pos = strrpos($cat, "_");

            if ($pos === false) {
                UniteFunctionsRb::throwError("The category is in wrong format");
            }

            $taxName = Tools::substr($cat, 0, $pos);
            $catID = Tools::substr($cat, $pos + 1, Tools::strlen($cat) - $pos - 1);
            $arrTax[$taxName] = $taxName;

            if (!empty($strCats)) {
                $strCats .= ",";
            }

            $strCats .= $catID;
        }

        $strTax = "";

        foreach ($arrTax as $taxName) {
            if (!empty($strTax)) {
                $strTax .= ",";
            }
            $strTax .= $taxName;
        }

        $output = array("tax" => $strTax, "cats" => $strCats);

        return($output);
    }

    public static function getCurrentLangCode()
    {
        $language = Context::getContext()->language;
        $langTag = $language->iso_code;

        return($langTag);
    }

    public static function writeSettingLanguageFile($filepath)
    {
        $modules = new Rbthemeslider();
        $info = pathinfo($filepath);
        $path = UniteFunctionsRb::getVal($info, "dirname") . "/";
        $filename = UniteFunctionsRb::getVal($info, "filename");
        $ext = UniteFunctionsRb::getVal($info, "extension");
        $filenameOutput = "{$filename}_{$ext}_lang.php";
        $filepathOutput = $path . $filenameOutput;

        $settings = new UniteSettingsAdvancedRb();
        $settings->loadXMLFile($filepath);
        $arrText = $settings->getArrTextFromAllSettings();
        $str = "";
        $str .= "<?php \n";

        foreach ($arrText as $text) {
            $text = str_replace('"', '\\"', $text);
            $str .= "$modules->l(\"$text\",\"" . RBSLIDER_TEXTDOMAIN . "\"); \n";
        }

        $str .= "?>";

        UniteFunctionsRb::writeFile($str, $filepathOutput);
    }

    public static function getSimpleProducts()
    {
        $context = Context::getContext();
        $id_lang = (int) Context::getContext()->language->id;
        $front = true;

        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }

        $sql = 'SELECT p.`id_product`, pl.`name`
        FROM `' . _DB_PREFIX_ . 'product` p
        ' . Shop::addSqlAssociation('product', 'p') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' .
        Shop::addSqlRestrictionOnLang('pl') . ')
        WHERE pl.`id_lang` = ' . (int) $id_lang . '
        ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . '
        ORDER BY pl.`name`';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public static function getRbPostDataArray(
        $catIDs,
        $sortBy = self::SORTBY_ID,
        $direction = self::ORDER_DIRECTION_DESC,
        $numPosts = -1,
        $postTypes = "any",
        $taxonomies = "category",
        $arrAddition = array()
    ) {
        if ($numPosts == -1) {
            $numPosts = null;
        }

        $results = array();
        $categoriesid = array();
        $ids = explode(',', $catIDs);

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $categoriesid[] = (int)$id;
            }
        }

        $i = 0;

        if (@Rbthemeslider::getIsset($categoriesid) && !empty($categoriesid)) {
            foreach ($categoriesid as $catid) {
                $results_temp = self::getAllProducts($catid, $sortBy, $direction, $numPosts);

                foreach ($results_temp as $temp) {
                    $results[$i] = $temp;
                    $i++;
                }
            }
        }

        return $results;
    }

    public static function getAllBlogPost()
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT * FROM `' . _DB_PREFIX_ . 'smart_blog_category` sbc
            INNER JOIN `' . _DB_PREFIX_ . 'smart_blog_category_lang` sbcl
            ON(sbc.`id_smart_blog_category` = sbcl.`id_smart_blog_category`
            AND sbcl.`id_lang` = ' . (int) ($id_lang) . ')
            INNER JOIN `' . _DB_PREFIX_ . 'smart_blog_category_shop` sbs
            ON sbs.id_smart_blog_category = sbc.id_smart_blog_category
            and sbs.id_shop = ' . (int) Context::getContext()->shop->id . ' WHERE sbc.`active`= 1'
        );

        $id_lang = (int) Context::getContext()->language->id;

        return $result;
    }

    public static function getCategoryNameById($id_category = '')
    {
        if (@Rbthemeslider::getIsset($id_category) && !empty($id_category) && $id_category != 0) {
            $id_lang = (int) Context::getContext()->language->id;

            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                SELECT sbcl.`name` FROM `' . _DB_PREFIX_ . 'category` sbc
                INNER JOIN `' . _DB_PREFIX_ . 'category_lang` sbcl
                ON(sbc.`id_category` = sbcl.`id_category`
                AND sbcl.`id_lang` = ' . (int) ($id_lang) . ')
                INNER JOIN `' . _DB_PREFIX_ . 'category_shop` sbs
                ON sbs.id_category = sbc.id_category
                AND sbs.id_shop = ' . (int) Context::getContext()->shop->id . '
                WHERE sbc.`active`= 1 and sbc.`id_category` = ' . $id_category
            );

            return $result[0]['name'];
        } else {
            return false;
        }
    }

    public static function getAllProducts(
        $id_category,
        $order_by = null,
        $order_way = null,
        $limit = null
    ) {
        $random = false;

        if ($order_by == 'ID') {
            $order_by = 'id_product';
        } elseif ($order_by == 'date') {
            $order_by = 'date_add';
        } elseif ($order_by == 'title') {
            $order_by = 'name';
        } elseif ($order_by == 'price') {
            $order_by = 'price';
        } else {
            $order_by = 'position';
        }

        if ($order_by == 'rand') {
            $random = true;
        }

        $random_number_products = 1;
        $check_access = true;
        $id_lang = Context::getcontext()->language->id;
        $context = Context::getContext();
        $active = true;
        $front = true;

        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }

        if (empty($order_by)) {
            $order_by = 'position';
        } else {
            $order_by = Tools::strtolower($order_by);
        }

        if (empty($order_way)) {
            $order_way = 'ASC';
        }

        $order_by_prefix = false;

        if ($order_by == 'id_product' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'p';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        } elseif ($order_by == 'manufacturer') {
            $order_by_prefix = 'm';
            $order_by = 'name';
        } elseif ($order_by == 'position') {
            $order_by_prefix = 'cp';
        }

        if ($order_by == 'price') {
            $order_by = 'orderprice';
        }

        if (!Validate::isBool($active) ||
            !Validate::isOrderBy($order_by) ||
            !Validate::isOrderWay($order_way)
        ) {
            die(Tools::displayError());
        }

        $sql = 'SELECT p.*, product_shop.*, stock.`out_of_stock`,
        IFNULL(stock.`quantity`, 0) as quantity, MAX(product_attribute_shop.`id_product_attribute`) id_product_attribute,
        product_attribute_shop.`minimal_quantity` AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
        pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
        il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS default_category,
        DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
        INTERVAL ' . (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20) . '
        DAY)) > 0 AS new, product_shop.price AS orderprice
        FROM `' . _DB_PREFIX_ . 'category_product` cp
        LEFT JOIN `' . _DB_PREFIX_ . 'product` p
        ON p.`id_product` = cp.`id_product`
        ' . Shop::addSqlAssociation('product', 'p') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa
        ON (p.`id_product` = pa.`id_product`)
        ' . Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1') . '
        ' . Product::sqlStock('p', 'product_attribute_shop', false, $context->shop) . '
        LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl
        ON (product_shop.`id_category_default` = cl.`id_category`
        AND cl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('cl') . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl
        ON (p.`id_product` = pl.`id_product`
        AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl') . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'image` i
        ON (i.`id_product` = p.`id_product`)' .
        Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il
        ON (image_shop.`id_image` = il.`id_image`
        AND il.`id_lang` = ' . (int) $id_lang . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m
        ON m.`id_manufacturer` = p.`id_manufacturer`
        WHERE product_shop.`id_shop` = ' . (int) $context->shop->id . '
        AND cp.`id_category` = ' . (int) $id_category
        . ($active ? ' AND product_shop.`active` = 1' : '')
        . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
        . ' GROUP BY product_shop.id_product';

        if ($random === true) {
            $sql .= ' ORDER BY RAND() LIMIT ' . (int) $random_number_products;
        } else {
            $sql .= ' ORDER BY ' . (!empty($order_by_prefix) ? $order_by_prefix . '.' : '') . '`' . bqSQL($order_by) . '` ' . pSQL($order_way);
        }

        if (@Rbthemeslider::getIsset($limit)) {
            $sql .= ' LIMIT ' . $limit;
        }

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if ($order_by == 'orderprice') {
            Tools::orderbyPrice($result, $order_way);
        }

        if (!$result) {
            return array();
        }

        return Product::getProductsProperties($id_lang, $result);
    }
}

class RbSliderFunctionsPS extends UniteFunctionsPSRb
{
    
}
