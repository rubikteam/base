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

if (!defined('THUMBLIB_BASE_PATH') &&
    file_exists(_PS_MODULE_DIR_.'rbthemeblog/library/phpthumb/ThumbLib.inc.php')
) {
    require_once _PS_MODULE_DIR_.'rbthemeblog/library/phpthumb/ThumbLib.inc.php';
}

require_once _PS_MODULE_DIR_.'rbthemeblog/classes/RbBlogCategory.php';
require_once _PS_MODULE_DIR_.'rbthemeblog/classes/RbBlogPost.php';
require_once _PS_MODULE_DIR_.'rbthemeblog/classes/RbBlogPostType.php';
require_once _PS_MODULE_DIR_.'rbthemeblog/classes/RbBlogPostImage.php';
require_once _PS_MODULE_DIR_.'rbthemeblog/classes/RbBlogTag.php';
require_once _PS_MODULE_DIR_.'rbthemeblog/classes/RbBlogComment.php';
require_once _PS_MODULE_DIR_.'rbthemeblog/helper/RbBlogHelper.php';
require_once _PS_MODULE_DIR_.'rbthemeblog/helper/BlogPostsFinder.php';

define('_RBTHEMEBLOG_GALLERY_DIR_', _PS_MODULE_DIR_.'rbthemeblog/galleries/');
define('_RBTHEMEBLOG_GALLERY_URL_', _MODULE_DIR_.'rbthemeblog/galleries/');

if (!defined('_PS_VERSION_')) {
    exit;
}

class Rbthemeblog extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'rbthemeblog';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'R_B';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();
        
        $this->displayName = $this->l('Rb Theme Blog');
        $this->description = $this->l('This is great module for create blog');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->controllers = array('single', 'list', 'category', 'categorypage', 'page');
        $this->secure_key = Tools::encrypt($this->name);
    }

    public function install()
    {
        Configuration::updateValue('RBTHEMEBLOG_BLOG_POSTS_PER_PAGE', '10');
        Configuration::updateValue('RBTHEMEBLOG_BLOG_SLUG', 'blog');
        Configuration::updateValue('RBTHEMEBLOG_CATEGORY_IMAGE_X', 1000);
        Configuration::updateValue('RBTHEMEBLOG_CATEGORY_IMAGE_Y', 150);
        Configuration::updateValue('RBTHEMEBLOG_BLOG_THUMB_X', 600);
        Configuration::updateValue('RBTHEMEBLOG_BLOG_THUMB_Y', 600);
        Configuration::updateValue('RBTHEMEBLOG_BLOG_THUMB_X_WIDE', 1000);
        Configuration::updateValue('RBTHEMEBLOG_BLOG_THUMB_Y_WIDE', 1000);

        $res = true;
        $class = 'Admin'.Tools::ucfirst($this->name).'Management';
        $id_parent = Tab::getIdFromClassName('IMPROVE');
        $tab1 = new Tab();
        $tab1->class_name = $class;
        $tab1->module = $this->name;
        $tab1->id_parent = $id_parent;
        $langs = Language::getLanguages(false);

        foreach ($langs as $l) {
            $tab1->name[$l['id_lang']] = $this->l('Rb Theme Blog');
        }

        $tab1->add(true, false);

        Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'tab`
            SET `icon` = "ac_unit"
            WHERE `id_tab` = "'.(int)$tab1->id.'"'
        );

        $this->installModuleTab('Categories', 'categories', 'AdminRbThemeBlogManagement');
        $this->installModuleTab('Posts', 'post', 'AdminRbThemeBlogManagement');
        $this->installModuleTab('Comments', 'comment', 'AdminRbThemeBlogManagement');
        $this->installModuleTab('Tags', 'tag', 'AdminRbThemeBlogManagement');
        $this->installModuleTab('Settings', 'setting', 'AdminRbThemeBlogManagement');

        include_once dirname(__FILE__).'/sql/install.php';
        include_once dirname(__FILE__).'/sql/same.php';

        $default_post_type = new RbBlogPostType();
        $default_post_type->name = 'Post';
        $default_post_type->slug = 'post';
        $default_post_type->description = 'Default type of post';
        $default_post_type->add();

        $gallery_post_type = new RbBlogPostType();
        $gallery_post_type->name = 'Gallery';
        $gallery_post_type->slug = 'gallery';
        $gallery_post_type->add();

        $external_url_post_type = new RbBlogPostType();
        $external_url_post_type->name = 'External URL';
        $external_url_post_type->slug = 'url';
        $external_url_post_type->add();

        $video_post_type = new RbBlogPostType();
        $video_post_type->name = 'Video';
        $video_post_type->slug = 'video';
        $video_post_type->add();

        return parent::install();
    }

    public function rbRegisterHook()
    {
        $this->registerHook('moduleRoutes');
        $this->registerHook('displayRbBlogPosts');
        $this->registerHook('displayRbBlogCategories');
        $this->registerHook('displayHeader');
        $this->registerHook('displayTop');
        $this->registerHook('displayBackOfficeHeader');
        $this->registerHook('displayPrestaHomeBlogAfterPostContent');
        $this->registerHook('displayLeftColumn');
    }

    private function installModuleTab($title, $class_sfx = '', $parent = '')
    {
        $class = 'Admin'.Tools::ucfirst($this->name).Tools::ucfirst($class_sfx);
        @copy(_PS_MODULE_DIR_.$this->name.'/logo.gif', _PS_IMG_DIR_.'t/'.$class.'.gif');

        if ($parent == '') {
            $position = Tab::getCurrentTabId();
        } else {
            $position = Tab::getIdFromClassName($parent);
        }

        $tab1 = new Tab();
        $tab1->class_name = $class;
        $tab1->module = $this->name;
        $tab1->id_parent = (int)$position;

        if ($class_sfx == 'live') {
            $tab1->id_parent = -1;
        } else {
            $tab1->id_parent = (int)$position;
        }

        $langs = Language::getLanguages(false);

        foreach ($langs as $l) {
            $tab1->name[$l['id_lang']] = $title;
        }

        $tab1->add(true, false);
    }

    public function uninstall()
    {
        Configuration::deleteByName('RBTHEMEBLOG_LIVE_MODE');

        return parent::uninstall();
    }

    private function uninstallModuleTab($class_sfx = '')
    {
        $tab_class = 'Admin'.Tools::ucfirst($this->name).Tools::ucfirst($class_sfx);
        $id_tab = Tab::getIdFromClassName($tab_class);

        if ($id_tab != 0) {
            $tab = new Tab($id_tab);
            $tab->delete();
            return true;
        }

        return false;
    }

    public function getContent()
    {
        $output = '';
        $errors = array();

        if (((bool)Tools::isSubmit('submitRbthemedreamModule')) == true) {
            $post_per_page = Tools::getValue('RBTHEMEBLOG_BLOG_POSTS_PER_PAGE');

            if (!Validate::isInt($post_per_page) || $post_per_page == '') {
                $errors[] = $this->trans(
                    'Invalid value for the Posts Per Page',
                    array(),
                    'Modules.Rbthemeblog.Admin'
                );
            }

            $blog_slug = Tools::getValue('RBTHEMEBLOG_BLOG_SLUG');

            if ($blog_slug == '') {
                $errors[] = $this->trans(
                    'Invalid value for the Blog main URL',
                    array(),
                    'Modules.Rbthemeblog.Admin'
                );
            }

            $cate_img_x = Tools::getValue('RBTHEMEBLOG_CATEGORY_IMAGE_X');

            if (!Validate::isInt($cate_img_x)) {
                $errors[] = $this->trans(
                    'Invalid value for the Default category image width',
                    array(),
                    'Modules.Rbthemeblog.Admin'
                );
            }

            $cate_img_y = Tools::getValue('RBTHEMEBLOG_CATEGORY_IMAGE_Y');

            if (!Validate::isInt($cate_img_y)) {
                $errors[] = $this->trans(
                    'Invalid value for the Default category image height',
                    array(),
                    'Modules.Rbthemeblog.Admin'
                );
            }

            $thumb_img_x = Tools::getValue('RBTHEMEBLOG_BLOG_THUMB_X');
            $thumb_img_y = Tools::getValue('RBTHEMEBLOG_BLOG_THUMB_Y');
            $thumb_img_x_wide = Tools::getValue('RBTHEMEBLOG_BLOG_THUMB_X_WIDE');
            $thumb_img_y_wide = Tools::getValue('RBTHEMEBLOG_BLOG_THUMB_Y_WIDE');

            if (!Validate::isInt($thumb_img_x)) {
                $errors[] = $this->trans(
                    'Invalid value for the Default thumbnail width',
                    array(),
                    'Modules.Rbthemeblog.Admin'
                );
            }

            if (!Validate::isInt($thumb_img_y)) {
                $errors[] = $this->trans(
                    'Invalid value for the Default thumbnail height',
                    array(),
                    'Modules.Rbthemeblog.Admin'
                );
            }

            if (!Validate::isInt($thumb_img_x_wide)) {
                $errors[] = $this->trans(
                    'Invalid value for the Default thumbnail width (wide version)',
                    array(),
                    'Modules.Rbthemeblog.Admin'
                );
            }

            if (!Validate::isInt($thumb_img_y_wide)) {
                $errors[] = $this->trans(
                    'Invalid value for the Default thumbnail height (wide version)',
                    array(),
                    'Modules.Rbthemeblog.Admin'
                );
            }

            if (isset($errors) && count($errors) > 0) {
                $output = $this->displayError(implode('<br />', $errors));
            } else {
                $this->postProcess();
                $output = $this->displayConfirmation(
                    $this->trans(
                        'The settings have been updated.',
                        array(),
                        'Admin.Notifications.Success'
                    )
                );
            }
        }

        if (Tools::isSubmit('regenerateThumbnails')) {
            RbBlogPost::regenerateThumbnails();

            $url = 'index.php?controller=adminmodules&configure=rbthemeblog&token='.
            Tools::getAdminTokenLite('AdminModules')
            .'&tab_module=Home&module_name=rbthemeblog';

            Tools::redirectAdmin($url);
        }


        if (Tools::isSubmit('submitExportSettings')) {
            header('Content-type: text/plain');
            header('Content-Disposition: attachment; filename=rbthemeblog_configuration_'.date('d-m-Y').'.txt');
            $configs = array();

            foreach ($this->fields_options as $category_data) {
                if (!isset($category_data['fields'])) {
                    continue;
                }

                $fields = $category_data['fields'];

                foreach ($fields as $field => $values) {
                    if ($values['type'] == 'textLang') {
                        $configs[$field] = self::getValueForLangs($field);
                    } else {
                        $configs[$field] = Configuration::get($field);
                    }
                }
            }

            echo serialize($configs);

            exit();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        return $output.$this->renderForm();
    }

    protected function renderForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitRbthemedreamModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $content = $helper->generateForm($this->getConfigForm());

        $content = str_replace(
            '<div class="panel" id="fieldset_0">',
            '<div class="panel active" id="fieldset_0">',
            $content
        );

        $this->context->smarty->assign(array(
            'content' => $content,
            'url_admin' => $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name,
            'baseurl' => $this->context->shop->getBaseURL(true, true),
        ));

        return $this->context->smarty->fetch($this->local_path . 'views/templates/admin/setting.tpl');
    }

    protected function getConfigForm()
    {
        $fields_form = array();

        $pre_settings_content = '<button type="submit" name="regenerateThumbnails"
        class="button btn btn-default"><i class="process-icon-cogs"></i>'.
        $this->l('Regenerate thumbnails').'</button>&nbsp;';
        $pre_settings_content .= '<button type="submit" name="submitExportSettings" class="button btn btn-default"><i class="process-icon-export"></i>'.$this->l('Export settings').'</button>&nbsp;';
        $pre_settings_content .= '<br /><br>';

        $switch = array(
            array(
                'id' => 'active_on',
                'value' => '1',
                'label' => $this->l('Yes')
            ),
            array(
                'id' => 'active_off',
                'value' => '0',
                'label' => $this->l('No')
            )
        );

        $fields_form[0]['form'] = array(
            'input' => array(
                array(
                    'type' => 'html',
                    'name' => 'html_data',
                    'html_content' => $pre_settings_content,
                ),
                array(
                    'col' => 3,
                    'required' => true,
                    'type' => 'text',
                    'label' => $this->l('Posts per page'),
                    'name' => 'RBTHEMEBLOG_BLOG_POSTS_PER_PAGE',
                    'desc' => $this->l('Number of blog posts displayed per page. Default is 10.'),
                ),
                array(
                    'col' => 3,
                    'required' => true,
                    'label' => $this->l('Blog main URL'),
                    'type' => 'text',
                    'size' => 40,
                    'name' => 'RBTHEMEBLOG_BLOG_SLUG'
                ),
                array(
                    'col' => 6,
                    'required' => true,
                    'lang' => true,
                    'label' => $this->l('Blog title'),
                    'type' => 'text',
                    'size' => 40,
                    'name' => 'RBTHEMEBLOG_BLOG_MAIN_TITLE'
                ),
                array(
                    'label' => $this->l('Blog description'),
                    'type' => 'text',
                    'name' => 'RBTHEMEBLOG_BLOG_MAIN_META_DESCRIPTION',
                    'lang' => true,
                    'desc' => $this->l('Meta Description for blog homepage'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Layout'),
                    'name' => 'RBTHEMEBLOG_BLOG_CATEGORY_SORTBY',
                    'options' => array(
                        'query' => array(
                            array(
                                'id_option' => 'position', 
                                'name' => $this->l('Position') . ' (1-9)',
                            ),
                            array(
                                'id_option' => 'name',
                                'name' => $this->l('Name (A-Z)'),
                            ),
                            array(
                                'id_option' => 'id',
                                'name' => $this->l('ID (1-9)'),
                            ),
                        ),
                        'id' => 'id_option', 
                        'name' => 'name'
                    ),
                    'desc' => $this->l('Select which method use to sort categories in Rbthemeblog Categories Block'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Init Facebook?'),
                    'name' => 'RBTHEMEBLOG_BLOG_FB_INIT',
                    'values' => $switch,
                    'desc' => $this->l('If you already use some Facebook widgets in your theme please select option to "No". If you select "Yes" then Rbthemeblog will add facebook connect script on single post page'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        $fields_form[1]['form'] = array(
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Posts list layout'),
                    'name' => 'RBTHEMEBLOG_BLOG_LIST_LAYOUT',
                    'options' => array(
                        'query' => array(
                            array(
                                'id_option' => 'full', 
                                'name' => $this->l('Full width large images'),
                            ),
                            array(
                                'id_option' => 'grid',
                                'name' => $this->l('Grid'),
                            ),
                        ),
                        'id' => 'id_option', 
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Grid columns'),
                    'name' => 'RBTHEMEBLOG_BLOG_GRID_COLUMNS',
                    'options' => array(
                        'query' => array(
                            array(
                                'id_option' => '2', 
                                'name' => $this->l('2 columns'),
                            ),
                            array(
                                'id_option' => '3',
                                'name' => $this->l('3 columns'),
                            ),
                            array(
                                'id_option' => '4',
                                'name' => $this->l('4 columns'),
                            ),
                        ),
                        'id' => 'id_option', 
                        'name' => 'name'
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        $fields_form[2]['form'] = array(
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display Like'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_LIKES',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Share icons on single post page'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_SHARER',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display author'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_AUTHOR',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display post creation date'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_DATE',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display post featured image'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_FEATURED',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display post category'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_CATEGORY',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display post tags'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_TAGS',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display related products'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_RELATED',
                    'values' => $switch,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        $fields_form[3]['form'] = array(
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display "read more"'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_MORE',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display thumbnails'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_THUMBNAIL',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display description'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_DESCRIPTION',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display category description'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_CAT_DESC',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display category image'),
                    'name' => 'RBTHEMEBLOG_BLOG_DISPLAY_CATEGORY_IMAGE',
                    'values' => $switch,
                ),
                array(
                    'col' => 3,
                    'label' => $this->l('Default category image width (px)'),
                    'type' => 'text',
                    'name' => 'RBTHEMEBLOG_CATEGORY_IMAGE_X',
                    'desc' => $this->l('Default: 1000'),
                ),
                array(
                    'col' => 3,
                    'label' => $this->l('Default category image height (px)'),
                    'type' => 'text',
                    'name' => 'RBTHEMEBLOG_CATEGORY_IMAGE_Y',
                    'desc' => $this->l('Default: 150'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        $fields_form[4]['form'] = array(
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Comments system'),
                    'name' => 'RBTHEMEBLOG_BLOG_COMMENTS_SYSTEM',
                    'desc' => 'What type of comments system do you want to use',
                    'options' => array(
                        'query' => array(
                            array(
                                'id_option' => 'native', 
                                'name' => $this->l('Default native comments'),
                            ),
                            array(
                                'id_option' => 'facebook',
                                'name' => $this->l('Facebook comments'),
                            ),
                            array(
                                'id_option' => 'disqus',
                                'name' => $this->l('Disqus comments'),
                            ),
                        ),
                        'id' => 'id_option', 
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Automatically approve new comments'),
                    'name' => 'RBTHEMEBLOG_BLOG_COMMENT_AUTO_APPROVAL',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow comments'),
                    'name' => 'RBTHEMEBLO_BLOG_COMMENT_ALLOW',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow comments for non logged in users'),
                    'name' => 'RBTHEMEBLO_BLOG_COMMENT_ALLOW_GUEST',
                    'values' => $switch,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        $fields_form[5]['form'] = array(
            'input' => array(
                array(
                    'col' => 9,
                    'label' => $this->l('Facebook comments moderator User ID'),
                    'type' => 'text',
                    'name' => 'RBTHEMEBLOG_BLOG_FACEBOOK_MODERATOR',
                ),
                array(
                    'col' => 9,
                    'label' => $this->l('Facebook application ID'),
                    'type' => 'text',
                    'name' => 'RBTHEMEBLOG_BLOG_FACEBOOK_APP_ID',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Faceboook comments color scheme'),
                    'name' => 'RBTHEMEBLOG_BLOG_FACEBOOK_COLOR_SCHEME',
                    'options' => array(
                        'query' => array(
                            array(
                                'id_option' => 'light', 
                                'name' => $this->l('Light'),
                            ),
                            array(
                                'id_option' => 'dark',
                                'name' => $this->l('Dark'),
                            ),
                        ),
                        'id' => 'id_option', 
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Image use as a image shared on Facebook'),
                    'name' => 'RBTHEMEBLOG_BLOG_IMAGE_FBSHARE',
                    'options' => array(
                        'query' => array(
                            array(
                                'id_option' => 'featured', 
                                'name' => $this->l('Featured'),
                            ),
                            array(
                                'id_option' => 'thumbnail',
                                'name' => $this->l('Thumbnail'),
                            ),
                        ),
                        'id' => 'id_option', 
                        'name' => 'name'
                    ),
                ),
                array(
                    'col' => 9,
                    'label' => $this->l('Shortname'),
                    'type' => 'text',
                    'name' => 'RBTHEMEBLO_BLOG_DISQUS_SHORTNAME',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        $html_capcha = '<div class="alert alert-info">';
        $html_capcha .= '<p>'.$this->l('Spam protection is provided by Google reCAPTCHA service, to gain keys').'</p>';
        $html_capcha .= '<ol>';
        $html_capcha .= '<li>';
        $html_capcha .= $this->l('Login to your Google Account and go to this page:') . '
        <a href="https://www.google.com/recaptcha/admin">https://www.google.com/recaptcha/admin</a>';
        $html_capcha .= '</li>';
        $html_capcha .= '<li>'.$this->l('Register a new site').'</li>';
        $html_capcha .= '<li>'.$this->l('Get Site Key and Secret Key and provide these keys here in Settings').'</li>';
        $html_capcha .= '<li>'.$this->l('Remember: if you do not specify the correct keys, the captcha will not work').'</li>';
        $html_capcha .= '<ol>';
        $html_capcha .= '</ol>';
        $html_capcha .= '</div>';

        $fields_form[6]['form'] = array(
            'input' => array(
                array(
                    'type' => 'html',
                    'name' => 'html_data',
                    'html_content' =>  $html_capcha,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable spam protection'),
                    'name' => 'RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA',
                    'values' => $switch,
                ),
                array(
                    'col' => 9,
                    'label' => $this->l('Site key'),
                    'type' => 'text',
                    'name' => 'RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA_SITE_KEY',
                ),
                array(
                    'col' => 9,
                    'label' => $this->l('Secret key'),
                    'type' => 'text',
                    'name' => 'RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA_SECRET_KEY',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('reCAPTCHA color scheme'),
                    'name' => 'RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA_THEME',
                    'options' => array(
                        'query' => array(
                            array(
                                'id_option' => 'light', 
                                'name' => $this->l('Light'),
                            ),
                            array(
                                'id_option' => 'dark',
                                'name' => $this->l('Dark'),
                            ),
                        ),
                        'id' => 'id_option', 
                        'name' => 'name'
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        $html_thumnails = '<div class="alert alert-info">'.
        $this->l('Remember to regenerate thumbnails after doing changes here').'</div>';

        $fields_form[7]['form'] = array(
            'input' => array(
                array(
                    'type' => 'html',
                    'name' => 'html_data',
                    'html_content' =>  $html_thumnails,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Resize method'),
                    'name' => 'RBTHEMEBLOG_BLOG_THUMB_METHOD',
                    'options' => array(
                        'query' => array(
                            array(
                                'id_option' => '1', 
                                'name' => $this->l('Adaptive resize'),
                            ),
                            array(
                                'id_option' => '2',
                                'name' => $this->l('Crop from center'),
                            ),
                        ),
                        'id' => 'id_option', 
                        'name' => 'name'
                    ),
                ),
                array(
                    'col' => 3,
                    'label' => $this->l('Default thumbnail width (px)'),
                    'type' => 'text',
                    'name' => 'RBTHEMEBLOG_BLOG_THUMB_X',
                    'desc' => $this->l('Default: 600'),
                ),
                array(
                    'col' => 3,
                    'label' => $this->l('Default thumbnail height (px)'),
                    'type' => 'text',
                    'name' => 'RBTHEMEBLOG_BLOG_THUMB_Y',
                ),
                array(
                    'col' => 3,
                    'label' => $this->l('Default thumbnail width (wide version) (px)'),
                    'type' => 'text',
                    'name' => 'RBTHEMEBLOG_BLOG_THUMB_X_WIDE',
                    'desc' => $this->l('Default: 1000'),
                ),
                array(
                    'col' => 3,
                    'label' => $this->l('Default thumbnail height (wide version) (px)'),
                    'type' => 'text',
                    'name' => 'RBTHEMEBLOG_BLOG_THUMB_Y_WIDE',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        $fields_form[8]['form'] = array(
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Use product list from your theme for related products'),
                    'name' => 'RBTHEMEBLOG_BLOG_RELATED_PRODUCTS_USE_DEFAULT_LIST',
                    'values' => $switch,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Load FitVids from module'),
                    'name' => 'RBTHEMEBLOG_BLOG_LOAD_FITVIDS',
                    'values' => $switch,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        $fields_form[9]['form'] = array(
            'input' => array(
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Paste here content of your settings file to import'),
                    'name' => 'RBTHEMEBLOG_BLOG_IMPORT_SETTINGS',
                    'cols' => '70',
                    'rows' => '100',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        return $fields_form;
    }

    protected function getConfigFormValues()
    {
        $rb_blog_main =  array();
        $rb_blog_main_des = array();

        foreach (Language::getLanguages(false) as $lang) {
            $rb_blog_main[$lang['id_lang']] = Configuration::get('RBTHEMEBLOG_BLOG_MAIN_TITLE', $lang['id_lang']);
            $rb_blog_main_des[$lang['id_lang']] = Configuration::get('RBTHEMEBLOG_BLOG_MAIN_META_DESCRIPTION', $lang['id_lang']);
        }

        return array(
            'RBTHEMEBLOG_BLOG_POSTS_PER_PAGE' => Configuration::get('RBTHEMEBLOG_BLOG_POSTS_PER_PAGE'),
            'RBTHEMEBLOG_BLOG_SLUG' => Configuration::get('RBTHEMEBLOG_BLOG_SLUG'),
            'RBTHEMEBLOG_BLOG_MAIN_TITLE' => $rb_blog_main,
            'RBTHEMEBLOG_BLOG_MAIN_META_DESCRIPTION' => $rb_blog_main_des,
            'RBTHEMEBLOG_BLOG_CATEGORY_SORTBY' => Configuration::get('RBTHEMEBLOG_CATEGORY_SORTBY'),
            'RBTHEMEBLOG_BLOG_FB_INIT' => Configuration::get('RBTHEMEBLOG_FB_INIT'),
            'RBTHEMEBLOG_BLOG_LIST_LAYOUT' => Configuration::get('RBTHEMEBLOG_LIST_LAYOUT'),
            'RBTHEMEBLOG_BLOG_GRID_COLUMNS' => Configuration::get('RBTHEMEBLOG_LIST_LAYOUT'),
            'RBTHEMEBLOG_BLOG_DISPLAY_LIKES' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_LIKES'),
            'RBTHEMEBLOG_BLOG_DISPLAY_SHARER' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_SHARER'),
            'RBTHEMEBLOG_BLOG_DISPLAY_AUTHOR' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_AUTHOR'),
            'RBTHEMEBLOG_BLOG_DISPLAY_DATE' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_DATE'),
            'RBTHEMEBLOG_BLOG_DISPLAY_FEATURED' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_FEATURED'),
            'RBTHEMEBLOG_BLOG_DISPLAY_CATEGORY' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_CATEGORY'),
            'RBTHEMEBLOG_BLOG_DISPLAY_TAGS' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_TAGS'),
            'RBTHEMEBLOG_BLOG_DISPLAY_RELATED' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_RELATED'),
            'RBTHEMEBLOG_BLOG_DISPLAY_MORE' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_MORE'),
            'RBTHEMEBLOG_BLOG_DISPLAY_THUMBNAIL' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_THUMBNAIL'),
            'RBTHEMEBLOG_BLOG_DISPLAY_DESCRIPTION' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_DESCRIPTION'),
            'RBTHEMEBLOG_BLOG_DISPLAY_CAT_DESC' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_CAT_DESC'),
            'RBTHEMEBLOG_BLOG_DISPLAY_CATEGORY_IMAGE' => Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_CATEGORY_IMAGE'),
            'RBTHEMEBLOG_CATEGORY_IMAGE_X' => Configuration::get('RBTHEMEBLOG_CATEGORY_IMAGE_X'),
            'RBTHEMEBLOG_CATEGORY_IMAGE_Y' => Configuration::get('RBTHEMEBLOG_CATEGORY_IMAGE_Y'),
            'RBTHEMEBLOG_BLOG_COMMENTS_SYSTEM' => Configuration::get('RBTHEMEBLOG_BLOG_COMMENTS_SYSTEM'),
            'RBTHEMEBLOG_BLOG_COMMENT_AUTO_APPROVAL' => Configuration::get('RBTHEMEBLOG_BLOG_COMMENT_AUTO_APPROVAL'),
            'RBTHEMEBLO_BLOG_COMMENT_ALLOW' => Configuration::get('RBTHEMEBLO_BLOG_COMMENT_ALLOW'),
            'RBTHEMEBLO_BLOG_COMMENT_ALLOW_GUEST' => Configuration::get('RBTHEMEBLO_BLOG_COMMENT_ALLOW_GUEST'),
            'RBTHEMEBLOG_BLOG_FACEBOOK_MODERATOR' => Configuration::get('RBTHEMEBLOG_BLOG_FACEBOOK_MODERATOR'),
            'RBTHEMEBLOG_BLOG_FACEBOOK_APP_ID' => Configuration::get('RBTHEMEBLOG_BLOG_FACEBOOK_APP_ID'),
            'RBTHEMEBLOG_BLOG_FACEBOOK_COLOR_SCHEME' => Configuration::get('RBTHEMEBLOG_BLOG_FACEBOOK_COLOR_SCHEME'),
            'RBTHEMEBLOG_BLOG_IMAGE_FBSHARE' => Configuration::get('RBTHEMEBLOG_BLOG_IMAGE_FBSHARE'),
            'RBTHEMEBLO_BLOG_DISQUS_SHORTNAME' => Configuration::get('RBTHEMEBLO_BLOG_DISQUS_SHORTNAME'),
            'RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA' => Configuration::get('RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA'),
            'RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA_SITE_KEY' => Configuration::get('RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA_SITE_KEY'),
            'RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA_SECRET_KEY' => Configuration::get('RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA_SECRET_KEY'),
            'RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA_THEME' => Configuration::get('RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA_THEME'),
            'RBTHEMEBLOG_BLOG_THUMB_METHOD' => Configuration::get('RBTHEMEBLOG_BLOG_THUMB_METHOD'),
            'RBTHEMEBLOG_BLOG_THUMB_X' => Configuration::get('RBTHEMEBLOG_BLOG_THUMB_X'),
            'RBTHEMEBLOG_BLOG_THUMB_Y' => Configuration::get('RBTHEMEBLOG_BLOG_THUMB_Y'),
            'RBTHEMEBLOG_BLOG_THUMB_X_WIDE' => Configuration::get('RBTHEMEBLOG_BLOG_THUMB_X_WIDE'),
            'RBTHEMEBLOG_BLOG_THUMB_Y_WIDE' => Configuration::get('RBTHEMEBLOG_BLOG_THUMB_Y_WIDE'),
            'RBTHEMEBLOG_BLOG_RELATED_PRODUCTS_USE_DEFAULT_LIST' => Configuration::get('
                RBTHEMEBLOG_BLOG_RELATED_PRODUCTS_USE_DEFAULT_LIST
            '),
            'RBTHEMEBLOG_BLOG_LOAD_FITVIDS' => Configuration::get('RBTHEMEBLOG_BLOG_LOAD_FITVIDS'),
            'RBTHEMEBLOG_BLOG_IMPORT_SETTINGS' => Configuration::get('RBTHEMEBLOG_BLOG_IMPORT_SETTINGS'),
        );
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            if ($key == 'RBTHEMEBLOG_BLOG_MAIN_TITLE') {
                $rb_blog_main =  array();

                foreach (Language::getLanguages(false) as $lang) {
                    $rb_blog_main[$lang['id_lang']] = Tools::getValue('RBTHEMEBLOG_BLOG_MAIN_TITLE_' . $lang['id_lang']);
                }

                Configuration::updateValue($key, $rb_blog_main, true);
            } else if ($key == 'RBTHEMEBLOG_BLOG_MAIN_META_DESCRIPTION') {
                $rb_blog_main_des =  array();

                foreach (Language::getLanguages(false) as $lang) {
                    $rb_blog_main_des[$lang['id_lang']] = Tools::getValue('RBTHEMEBLOG_BLOG_MAIN_META_DESCRIPTION_' . $lang['id_lang']);
                }

                Configuration::updateValue($key, $rb_blog_main_des, true);
            } else {
                Configuration::updateValue($key, Tools::getValue($key));
            }
        }
    }

    protected function myAssignModuleAssets()
    {
        $this->context->controller->registerStylesheet(
            'modules-rbthemeblog',
            'modules/'.$this->name.'/views/css/rbthemeblog.css',
            array('media' => 'all',
                'priority' => 150
            )
        );

        $this->context->controller->registerStylesheet(
            'modules-rbthemeblog-custom',
            'modules/'.$this->name.'/css/custom.css',
            array(
                'position' => 'bottom',
                'priority' => 150
            )
        );

        $this->context->controller->registerJavascript(
            'modules-rbthemeblog',
            'modules/'.$this->name.'/views/js/front.js',
            array(
                'position' => 'bottom',
                'priority' => 190,
                'attributes' => 'async'
            )
        );

        if (Configuration::get('RBTHEMEBLOG_BLOG_LOAD_FITVIDS')) {
            $this->context->controller->addJS($this->_path.'js/jquery.fitvids.js');
        }
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookHeader()
    {
        $this->myAssignModuleAssets();

        if (Tools::getValue('module') &&
            Tools::getValue('module') == 'rbthemeblog' &&
            Tools::getValue('controller') == 'single'
        ) {
            $post = $this->context->controller->getPost();
            $imageForFacebook = Configuration::get('RBTHEMEBLOG_BLOG_IMAGE_FBSHARE', 'featured') == 'featured' ? 'featured' : 'banner';

            $this->context->smarty->assign(array(
                'post_url' => $post->url,
                'post_title' => $post->title,
                'post_description' => strip_tags($post->short_content),
                'post_image' => rtrim($this->context->shop->getBaseUrl(true, false), '/').$post->{$imageForFacebook},
            ));

            Media::addJsDef(
                array(
                    'rb_sharing_name' => addcslashes($post->title, "'"),
                    'rb_sharing_url' => addcslashes($post->url, "'"),
                    'rb_sharing_img' => addcslashes(
                        rtrim($this->context->shop->getBaseUrl(true, false), '/').$post->{$imageForFacebook}, "'"
                    ),
                )
            );

            return $this->display(__FILE__, 'header.tpl');
        }
    }

    public function hookModuleRoutes($params)
    {
        if (Tools::getIsset('SubmitGsitemap')) {
            return array();
        }

        $blog_slug = Configuration::get('RBTHEMEBLOG_BLOG_SLUG');
        Configuration::deleteByName('PS_ROUTE_modules-rbthemeblog-list');
        Configuration::deleteByName('PS_ROUTE_module-rbthemeblog-category');
        Configuration::deleteByName('PS_ROUTE_module-rbthemeblog-single');

        return array(
            'module-rbthemeblog-list' => array(
                'controller' => 'list',
                'rule' => $blog_slug . '.html',
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'rbthemeblog',
                ),
            ),
            // Home pagination
            'module-rbthemeblog-page' => array(
                'controller' => 'page',
                'rule' => $blog_slug.'/page/{p}',
                'keywords' => array(
                    'p' => array(
                        'regexp' => '[_a-zA-Z0-9-\pL]*',
                        'param' => 'p',
                    ),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'rbthemeblog',
                ),
            ),

            // Category list
            'module-rbthemeblog-category' => array(
                'controller' => 'category',
                'rule' => $blog_slug.'/{rb_category}.html',
                'keywords' => array(
                    'rb_category' => array(
                        'regexp' => '[_a-zA-Z0-9-\pL]*',
                        'param' => 'rb_category',
                    ),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'rbthemeblog',
                ),
            ),

            // Category pagination
            'module-rbthemeblog-categorypage' => array(
                'controller' => 'categorypage',
                'rule' => $blog_slug.'/{rb_category}/page/{p}',
                'keywords' => array(
                    'p' => array(
                        'regexp' => '[_a-zA-Z0-9-\pL]*',
                        'param' => 'p',
                    ),
                    'rb_category' => array(
                        'regexp' => '[_a-zA-Z0-9-\pL]*',
                        'param' => 'rb_category',
                    ),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'rbthemeblog',
                ),
            ),

            // Single
            'module-rbthemeblog-single' => array(
                'controller' => 'single',
                'rule' => $blog_slug.'/{rb_category}/{rewrite}.html',
                'keywords' => array(
                    'rb_category' => array(
                        'regexp' => '[_a-zA-Z0-9-\pL]*',
                        'param' => 'rb_category',
                    ),
                    'rewrite' => array(
                        'regexp' => '[_a-zA-Z0-9-\pL]*',
                        'param' => 'rewrite',
                    ),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'rbthemeblog',
                ),
            ),
        );
    }

    public static function getLink()
    {
        return Context::getContext()->link->getModuleLink('rbthemeblog', 'list');
    }

    public static function myRealURL()
    {
        return Context::getContext()->shop->getBaseUrl(true, true);
    }

    public function prepareRbBlogCategories()
    {
        $this->context->smarty->assign(array(
            'categories' => RbBlogCategory::getCategories($this->context->language->id, true),
        ));
    }

    public function hookDisplayRbBlogCategories($params)
    {
        $this->prepareRbBlogCategories();

        if (isset($params['template'])) {
            return $this->display(__FILE__, $params['template'].'.tpl');
        } else {
            return $this->hookDisplayLeftColumn($params);
        }
    }

    public function hookDisplayLeftColumn($params)
    {
        $this->prepareRbBlogCategories();

        return $this->fetch('module:'.$this->name.'/views/templates/hook/left-column.tpl');
    }

    public function hookDisplayRightColumn($params)
    {
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayHome($params)
    {
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayFooter($params)
    {
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (method_exists($this->context->controller, 'addCSS')) {
            $this->context->controller->addCSS(($this->_path).'css/back.css', 'all');
        }
    }

    public function hookDisplayPrestaHomeBlogAfterPostContent($params)
    {
        return $this->fetch('module:'.$this->name.'/views/templates/hook/after-post-content.tpl');
    }
}
