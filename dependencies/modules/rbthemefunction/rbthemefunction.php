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

require_once _PS_MODULE_DIR_ . 'rbthemefunction/classes/RbthemefunctionWishList.php';
require_once _PS_MODULE_DIR_ . 'rbthemefunction/classes/RbthemefunctionReview.php';

class Rbthemefunction extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'rbthemefunction';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'R_B';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Rb Theme Function');
        $this->description = $this->l('This is great module create functions for theme');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->context = Context::getContext();
        $this->id_lang = $this->context->language->id;
        $this->id_shop = $this->context->shop->id;
        $this->module_path = $this->local_path;
    }

    public function install()
    {
        Configuration::updateValue('RBTHEMEFUNCTION_ADD_TO_CART', '1');
        Configuration::updateValue('RBTHEMEFUNCTION_COMPARE', '1');
        Configuration::updateValue('RBTHEMEFUNCTION_WISHLIST', '1');
        Configuration::updateValue('RBTHEMEFUNCTION_REVIEW', '1');
        Configuration::updateValue('RBTHEMEFUNCTION_SALE_POPUP', '1');
        Configuration::updateValue('RBTHEMEFUNCTION_BUTTON_PRINT', '1');
        Configuration::updateValue('RBTHEMEFUNCTION_GRID_VIEW', '1');

        Configuration::updateValue('RBTHEMEFUNCTION_POPUP_WIDTH', 770);
        Configuration::updateValue('RBTHEMEFUNCTION_POPUP_HEIGHT', 460);
        Configuration::updateValue('RBTHEMEFUNCTION_POPUP_NEWSLETTER', 1);
        Configuration::updateValue('RBTHEMEFUNCTION_POPUP_BG', 1);

        $res = true;
        $class = 'Admin'.Tools::ucfirst($this->name).'Management';
        $id_parent = Tab::getIdFromClassName('IMPROVE');
        $tab1 = new Tab();
        $tab1->class_name = $class;
        $tab1->module = $this->name;
        $tab1->id_parent = $id_parent;
        $langs = Language::getLanguages(false);

        $rb_text = array();

        foreach ($langs as $l) {
            $tab1->name[$l['id_lang']] = $this->l('Rb Theme Function');
            $rb_text[$l['id_lang']] = '<h2>Be the first to know</h2>
            <p>Subscribe for the latest news &amp; get 15% off your first order.</p>';
        }

        Configuration::updateValue('RBTHEMEFUNCTION_POPUP_TEXT', $rb_text, true);

        $tab1->add(true, false);

        Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'tab`
            SET `icon` = "functions"
            WHERE `id_tab` = "'.(int)$tab1->id.'"'
        );

        $this->installModuleTab('Setting', 'setting', 'AdminRbThemeFunctionManagement');
        $this->installModuleTab('Review', 'review', 'AdminRbThemeFunctionManagement');
        $this->rbRegisterHook();

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install();
    }

    public function rbRegisterHook()
    {
        $this->registerHook('header');
        $this->registerHook('displayAfterBodyOpeningTag');
        $this->registerHook('displayRbAddToCart');
        $this->registerHook('displayRbCompareProduct');
        $this->registerHook('displayRbWishListProduct');
        $this->registerHook('displayRbBrandProduct');
        $this->registerHook('displayRbTopLogin');
        $this->registerHook('displayRbTopCompare');
        $this->registerHook('displayRbTopWishlist');
        $this->registerHook('displayRbReviewProduct');
        $this->registerHook('displayFooterAfter');
        $this->registerHook('moduleRoutes');
        $this->registerHook('displayFooterProduct');
        $this->registerHook('displayProductExtraContent');
        $this->registerHook('displayFacebookLogin');
        $this->registerHook('displayNextPrevProduct');
        $this->registerHook('ActionAdminControllerSetMedia');
        $this->registerHook('displayTagCateProduct');
    }

    public function uninstall()
    {
        Configuration::deleteByName('RBTHEMEFUNCTION_ADD_TO_CART');
        Configuration::deleteByName('RBTHEMEFUNCTION_COMPARE');
        Configuration::deleteByName('RBTHEMEFUNCTION_WISHLIST');
        Configuration::deleteByName('RBTHEMEFUNCTION_REVIEW');
        Configuration::deleteByName('RBTHEMEFUNCTION_SALE_POPUP');
        Configuration::deleteByName('RBTHEMEFUNCTION_BUTTON_PRINT');

        $this->uninstallModuleTab('management');
        $this->uninstallModuleTab('setting');
        $this->uninstallModuleTab('review');
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
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

        if (((bool)Tools::isSubmit('submitRbthemefunctionModule')) == true) {
            $color_back = Tools::getValue('RBTHEMEFUNCTION_BACKGROUND_LOADING');

            if (!Validate::isColor($color_back)) {
                $errors[] = $this->trans(
                    'Invalid value for the Color Background Loading',
                    array(),
                    'Modules.Rbthemedream.Admin'
                );
            }

            if (isset($errors) && count($errors)) {
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

        $this->context->smarty->assign('module_dir', $this->_path);

        return $output.$this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitRbthemefunctionModule';
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

        $url_img = '';

        $fields_form = array();

        $fields_form[0]['form'] = array(
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Add To Cart'),
                    'name' => 'RBTHEMEFUNCTION_ADD_TO_CART',
                    'is_bool' => true,
                    'desc' => $this->l('Show Hide Button Add To Cart'),
                    'values' => $switch
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show Hide Loading Page'),
                    'name' => 'RBTHEMEFUNCTION_LOADING_PAGE',
                    'is_bool' => true,
                    'desc' => $this->l('Show Hide Loading Page'),
                    'values' => $switch
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Color Background Loading'),
                    'name' => 'RBTHEMEFUNCTION_BACKGROUND_LOADING',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show Hide Compare'),
                    'name' => 'RBTHEMEFUNCTION_COMPARE',
                    'is_bool' => true,
                    'desc' => $this->l('Show Hide Compare'),
                    'values' => $switch
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show Hide WishList'),
                    'name' => 'RBTHEMEFUNCTION_WISHLIST',
                    'is_bool' => true,
                    'desc' => $this->l('Show Hide WishList'),
                    'values' => $switch
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show Hide Review'),
                    'name' => 'RBTHEMEFUNCTION_REVIEW',
                    'is_bool' => true,
                    'desc' => $this->l('Show Hide WishList'),
                    'values' => $switch
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show Hide Sale Popup'),
                    'name' => 'RBTHEMEFUNCTION_SALE_POPUP',
                    'is_bool' => true,
                    'desc' => $this->l('Show Hide Sale Popup'),
                    'values' => $switch
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show Hide Button Print Product Details Page'),
                    'name' => 'RBTHEMEFUNCTION_BUTTON_PRINT',
                    'is_bool' => true,
                    'desc' => $this->l('Show Hide Sale Popup'),
                    'values' => $switch
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show Hide Next/Prev Product Details Page'),
                    'name' => 'RBTHEMEFUNCTION_BUTTON_NEXT_PREV',
                    'is_bool' => true,
                    'desc' => $this->l('Show Hide Next/Prev Product Details Page'),
                    'values' => $switch
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Grid View Product List'),
                    'name' => 'RBTHEMEFUNCTION_GRID_VIEW',
                    'is_bool' => true,
                    'desc' => $this->l('Grid View Product List'),
                    'values' => $switch
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show Category, Tag'),
                    'name' => 'RBTHEMEFUNCTION_TAG_CATEGORY',
                    'is_bool' => true,
                    'desc' => $this->l('Show Category, Tag Product Detail'),
                    'values' => $switch
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
                    'type' => 'text',
                    'label' => $this->l('App ID'),
                    'name' => 'RBTHEMEFUNCTION_APP_ID',
                    'is_bool' => true,
                    'desc' => $this->l('Create Facebook App: ') .
                    '<a href="https://developers.facebook.com/apps/">'.'https://developers.facebook.com/apps/'.'</a>',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Page ID'),
                    'name' => 'RBTHEMEFUNCTION_PAGE_ID',
                    'desc' => $this->l('Necessary for Facebook Chat. Find in page: ') .
                    '<a href="https://www.facebook.com/help/1503421039731588">'.'https://www.facebook.com/help/1503421039731588'.'</a>',
                ),
                array(
                    'type' => 'html',
                    'name' => 'html_data',
                    'html_content' => $this->genHtmlForm('Chat Facebook'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active Chat'),
                    'name' => 'RBTHEMEFUNCTION_ACTIVE_CHAT',
                    'is_bool' => true,
                    'values' => $switch
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Color'),
                    'name' => 'RBTHEMEFUNCTION_COLOR_CHAT',
                    'is_bool' => true,
                    'values' => $switch
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Time Before Display Chat'),
                    'name' => 'RBTHEMEFUNCTION_TIME_CHAT',
                    'col' => 3,
                ),
                array(
                    'type' => 'html',
                    'name' => 'html1_data',
                    'html_content' => $this->genHtmlForm('Login Facebook'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active Facebook Login'),
                    'name' => 'RBTHEMEFUNCTION_ACTIVE_LOGIN',
                    'is_bool' => true,
                    'values' => $switch
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Redirect After Login'),
                    'name' => 'RBTHEMEFUNCTION_AFTER_REDIRECT',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'no_redirect',
                                'name' => $this->l('No Redirect')
                            ),
                            array(
                                'id' => 'authentication_page',
                                'name' => $this->l('My Account Page')),
                            ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'html',
                    'name' => 'html2_data',
                    'html_content' => $this->genHtmlForm('Comments Facebook'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active Facebook Comments'),
                    'name' => 'RBTHEMEFUNCTION_ACTIVE_COMMENTS',
                    'is_bool' => true,
                    'values' => $switch
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Comments Location'),
                    'desc' => $this->l('Where Show Facebook Comments'),
                    'name' => 'RBTHEMEFUNCTION_LOCATION_COMMENTS',
                    'tab' => 'comments',
                    'options' => array(
                        'query' => array(
                            array('id' => 1, 'name' => $this->l('Product Tabs')),
                            array('id' => 2, 'name' => $this->l('Product Footer')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type'   => 'text',
                    'name'   => 'RBTHEMEFUNCTION_COMMENTS_WIDTH',
                    'label'  => $this->l('Comments Width'),
                    'value'  => '100%',
                    'desc'   => 'e.g. 100%',
                    'col' => 3,
                ),
                array(
                    'type'   => 'text',
                    'name'   => 'RBTHEMEFUNCTION_COMMENTS_NUMBER',
                    'label'  => $this->l('Comments Number'),
                    'value'  => '10',
                    'col' => 3,
                ),
                array(
                    'type'   => 'text',
                    'name'   => 'RBTHEMEFUNCTION_COMMENTS_ADMIN',
                    'desc' => $this->l('Grant moderation privileges for selected facebook accounts. Separate all admin IDs by commas IDs of Facebook private profiles'),
                    'label'  => $this->l('Admins'),
                    'col' => 3,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        $url_img = '';

        if (file_exists(dirname(__FILE__).'/views/img/imgbg_'.$this->context->shop->id.'.jpg')) {
            $url_img = $this->_path."/views/img/imgbg_".$this->context->shop->id.".jpg";
        }

        $fields_form[2]['form'] = array(
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active Popup'),
                    'name' => 'RBTHEMEFUNCTION_POPUP_ACTIVE',
                    'is_bool' => true,
                    'values' => $switch
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Width'),
                    'name' => 'RBTHEMEFUNCTION_POPUP_WIDTH',
                    'desc' => 'px',
                    'col' => 3
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Height'),
                    'name' => 'RBTHEMEFUNCTION_POPUP_HEIGHT',
                    'desc' => 'px',
                    'col' => 3
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show Newsletter Form'),
                    'name' => 'RBTHEMEFUNCTION_POPUP_NEWSLETTER',
                    'is_bool' => true,
                    'values' => $switch
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show Background Image'),
                    'name' => 'RBTHEMEFUNCTION_POPUP_BG',
                    'is_bool' => true,
                    'values' => $switch
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Background Image'),
                    'name' => 'image',
                    'value' => true,
                    'thumb' => $url_img ? $url_img : '',
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Text Popup'),
                    'lang' => true,
                    'name' => 'RBTHEMEFUNCTION_POPUP_TEXT',
                    'autoload_rte' => true,
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
                    'label' => $this->l('Slick Product Detail'),
                    'name' => 'RBTHEMEFUNCTION_SLICK_ACTIVE',
                    'is_bool' => true,
                    'values' => $switch
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Slideshow'),
                    'name' => 'RBTHEMEFUNCTION_SLICK_SLIDE_SHOW',
                    'col' => 3
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Slide To Scroll'),
                    'name' => 'RBTHEMEFUNCTION_SLICK_SLIDE_SCROLL',
                    'col' => 3
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Slick Autoplay'),
                    'name' => 'RBTHEMEFUNCTION_SLICK_AUTOPLAY',
                    'is_bool' => true,
                    'values' => $switch
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Slide Speed'),
                    'name' => 'RBTHEMEFUNCTION_SLICK_AUTOSPEED',
                    'col' => 3
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
                    'type' => 'switch',
                    'label' => $this->l('Zoom Product Detail'),
                    'name' => 'RBTHEMEFUNCTION_ZOOM_ACTIVE',
                    'is_bool' => true,
                    'values' => $switch
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Zoom Type'),
                    'name' => 'RBTHEMEFUNCTION_ZOOM_TYPE',
                    'options' => array(
                        'query' => array(
                            array(
                                'id_option' => 1,
                                'name' => $this->l('Zoom Basic'),
                            ),
                            array(
                                'id_option' => 2,
                                'name' => $this->l('Zoom Constrain'),
                            ),
                            array(
                                'id_option' => 3,
                                'name' => $this->l('Zoom Constrain Round'),
                            ),
                        ),
                        'id' => 'id_option',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Zoom Scroll'),
                    'name' => 'RBTHEMEFUNCTION_ZOOM_SCROll',
                    'is_bool' => true,
                    'values' => $switch
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        return $fields_form;
    }

    public function genHtmlForm($text)
    {
        $html = '<div class="alert alert-info rb-html-class">';
        $html .= '<p>'.$this->l($text).'</p>';
        $html .= '</div>';

        return $html;
    }

    protected function getConfigFormValues()
    {
        $rb_text = array();

        foreach (Language::getLanguages(false) as $lang) {
            $rb_text[$lang['id_lang']] = Configuration::get('RBTHEMEFUNCTION_POPUP_TEXT', $lang['id_lang']);
        }

        return array(
            'RBTHEMEFUNCTION_GRID_VIEW' => Configuration::get('RBTHEMEFUNCTION_GRID_VIEW'),
            'RBTHEMEFUNCTION_BUTTON_NEXT_PREV' => Configuration::get('RBTHEMEFUNCTION_BUTTON_NEXT_PREV'),
            'RBTHEMEFUNCTION_ADD_TO_CART' => Configuration::get('RBTHEMEFUNCTION_ADD_TO_CART'),
            'RBTHEMEFUNCTION_LOADING_PAGE' => Configuration::get('RBTHEMEFUNCTION_LOADING_PAGE'),
            'RBTHEMEFUNCTION_BACKGROUND_LOADING' => Configuration::get('RBTHEMEFUNCTION_BACKGROUND_LOADING'),
            'RBTHEMEFUNCTION_COMPARE' => Configuration::get('RBTHEMEFUNCTION_COMPARE'),
            'RBTHEMEFUNCTION_WISHLIST' => Configuration::get('RBTHEMEFUNCTION_WISHLIST'),
            'RBTHEMEFUNCTION_REVIEW' => Configuration::get('RBTHEMEFUNCTION_REVIEW'),
            'RBTHEMEFUNCTION_SALE_POPUP' => Configuration::get('RBTHEMEFUNCTION_SALE_POPUP'),
            'RBTHEMEFUNCTION_BUTTON_PRINT' => Configuration::get('RBTHEMEFUNCTION_BUTTON_PRINT'),
            'RBTHEMEFUNCTION_TAG_CATEGORY' => Configuration::get('RBTHEMEFUNCTION_TAG_CATEGORY'),
            'RBTHEMEFUNCTION_APP_ID' => Configuration::get('RBTHEMEFUNCTION_APP_ID'),
            'RBTHEMEFUNCTION_PAGE_ID' => Configuration::get('RBTHEMEFUNCTION_PAGE_ID'),
            'RBTHEMEFUNCTION_ACTIVE_CHAT' => Configuration::get('RBTHEMEFUNCTION_ACTIVE_CHAT'),
            'RBTHEMEFUNCTION_COLOR_CHAT' => Configuration::get('RBTHEMEFUNCTION_COLOR_CHAT'),
            'RBTHEMEFUNCTION_TIME_CHAT' => Configuration::get('RBTHEMEFUNCTION_TIME_CHAT'),
            'RBTHEMEFUNCTION_ACTIVE_LOGIN' => Configuration::get('RBTHEMEFUNCTION_ACTIVE_LOGIN'),
            'RBTHEMEFUNCTION_AFTER_REDIRECT' => Configuration::get('RBTHEMEFUNCTION_AFTER_REDIRECT'),
            'RBTHEMEFUNCTION_ACTIVE_COMMENTS' => Configuration::get('RBTHEMEFUNCTION_ACTIVE_COMMENTS'),
            'RBTHEMEFUNCTION_LOCATION_COMMENTS' => Configuration::get('RBTHEMEFUNCTION_LOCATION_COMMENTS'),
            'RBTHEMEFUNCTION_COMMENTS_WIDTH' => Configuration::get('RBTHEMEFUNCTION_COMMENTS_WIDTH'),
            'RBTHEMEFUNCTION_COMMENTS_NUMBER' => Configuration::get('RBTHEMEFUNCTION_COMMENTS_NUMBER'),
            'RBTHEMEFUNCTION_COMMENTS_ADMIN' => Configuration::get('RBTHEMEFUNCTION_COMMENTS_ADMIN'),
            'RBTHEMEFUNCTION_POPUP_WIDTH' => Configuration::get('RBTHEMEFUNCTION_POPUP_WIDTH', 770),
            'RBTHEMEFUNCTION_POPUP_HEIGHT' => Configuration::get('RBTHEMEFUNCTION_POPUP_HEIGHT', 460),
            'RBTHEMEFUNCTION_POPUP_NEWSLETTER' => Configuration::get('RBTHEMEFUNCTION_POPUP_NEWSLETTER'),
            'RBTHEMEFUNCTION_POPUP_BG' => Configuration::get('RBTHEMEFUNCTION_POPUP_BG'),
            'RBTHEMEFUNCTION_POPUP_TEXT' => $rb_text,
            'RBTHEMEFUNCTION_POPUP_ACTIVE' => Configuration::get('RBTHEMEFUNCTION_POPUP_ACTIVE', 1),
            'RBTHEMEFUNCTION_SLICK_ACTIVE' => Configuration::get('RBTHEMEFUNCTION_SLICK_ACTIVE'),
            'RBTHEMEFUNCTION_SLICK_SLIDE_SHOW' => Configuration::get('RBTHEMEFUNCTION_SLICK_SLIDE_SHOW'),
            'RBTHEMEFUNCTION_SLICK_SLIDE_SCROLL' => Configuration::get('RBTHEMEFUNCTION_SLICK_SLIDE_SCROLL'),
            'RBTHEMEFUNCTION_SLICK_AUTOPLAY' => Configuration::get('RBTHEMEFUNCTION_SLICK_AUTOPLAY'),
            'RBTHEMEFUNCTION_SLICK_AUTOSPEED' => Configuration::get('RBTHEMEFUNCTION_SLICK_AUTOSPEED'),
            'RBTHEMEFUNCTION_ZOOM_ACTIVE' => Configuration::get('RBTHEMEFUNCTION_ZOOM_ACTIVE'),
            'RBTHEMEFUNCTION_ZOOM_TYPE' => Configuration::get('RBTHEMEFUNCTION_ZOOM_TYPE'),
            'RBTHEMEFUNCTION_ZOOM_SCROll' => Configuration::get('RBTHEMEFUNCTION_ZOOM_SCROll'),
        );
    }

    public function getConfigFacebook()
    {
        $config = array();

        $config['general_appid'] = Configuration::get('RBTHEMEFUNCTION_APP_ID');
        $config['general_pageid'] = Configuration::get('RBTHEMEFUNCTION_PAGE_ID');
        $config['chat_state'] = Configuration::get('RBTHEMEFUNCTION_ACTIVE_CHAT');
        $config['chat_color'] = Configuration::get('RBTHEMEFUNCTION_COLOR_CHAT');
        $config['chat_delay'] = Configuration::get('RBTHEMEFUNCTION_TIME_CHAT');
        $config['login_state'] = Configuration::get('RBTHEMEFUNCTION_ACTIVE_LOGIN');
        $config['login_redirect'] = Configuration::get('RBTHEMEFUNCTION_AFTER_REDIRECT');
        $config['comments_state'] = Configuration::get('RBTHEMEFUNCTION_ACTIVE_COMMENTS');
        $config['comments_tab'] = Configuration::get('RBTHEMEFUNCTION_ACTIVE_COMMENTS');
        $config['comments_width'] = Configuration::get('RBTHEMEFUNCTION_COMMENTS_WIDTH');
        $config['comments_number'] = Configuration::get('RBTHEMEFUNCTION_COMMENTS_NUMBER');
        $config['comments_admins'] = Configuration::get('RBTHEMEFUNCTION_COMMENTS_ADMIN');
        $config['product_page_url'] = 'https://'.trim(strtok($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], '?'));
        $config['login_destination'] = $this->context->link->getPageLink('my-account', true);
        $config['phrases']['login'] = $this->l('Log in');

        return $config;
    }

    public function getConfigSlick()
    {
        $config = array();

        $config['active'] = Configuration::get('RBTHEMEFUNCTION_SLICK_ACTIVE');
        $config['slideshow'] = Configuration::get('RBTHEMEFUNCTION_SLICK_SLIDE_SHOW');
        $config['slidesToScroll'] = Configuration::get('RBTHEMEFUNCTION_SLICK_SLIDE_SCROLL');
        $config['autoplay'] = Configuration::get('RBTHEMEFUNCTION_SLICK_AUTOPLAY');
        $config['autospeed'] = Configuration::get('RBTHEMEFUNCTION_SLICK_AUTOSPEED');

        return $config;
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            if ($key == 'RBTHEMEFUNCTION_POPUP_TEXT') {
                $rb_text = array();
                
                foreach (Language::getLanguages(false) as $lang) {
                    $rb_text[$lang['id_lang']] = Tools::getValue('RBTHEMEFUNCTION_POPUP_TEXT_' . $lang['id_lang']);
                }

                Configuration::updateValue($key, $rb_text, true);
            } else {
                Configuration::updateValue($key, Tools::getValue($key));
            }
        }

        $width = Configuration::get('RBTHEMEFUNCTION_POPUP_WIDTH', 770);
        $height = Configuration::get('RBTHEMEFUNCTION_POPUP_HEIGHT', 460);

        if (isset($_FILES['image']) &&
            isset($_FILES['image']['tmp_name']) &&
            !empty($_FILES['image']['tmp_name'])
        ) {
            $img = dirname(__FILE__).'/views/img/imgbg_'.$this->context->shop->id.'.jpg';

            if (file_exists($img)) {
                unlink($img);
            }

            if ($error = ImageManager::validateUpload($_FILES['image'])) {
                Tools::displayError($error);
            } else if (!($tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS')) ||
                !move_uploaded_file($_FILES['image']['tmp_name'], $tmp_name)
            ) {
                return false;
            } else if (!ImageManager::resize($tmp_name, $img, $width, $height)) {
                Tools::displayError(($this->l('An error occurred while attempting to upload the image')));
            }

            if (isset($tmp_name)) {
                unlink($tmp_name);
            }
        }
    }

    public function hookActionAdminControllerSetMedia()
    {
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    public function hookHeader()
    {
        $this->context->controller->addJqueryPlugin('fancybox');
        $this->context->controller->addJS($this->_path.'/views/js/cookie.js');
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addJS($this->_path.'/views/js/fb.js');
        $this->context->controller->addJS($this->_path.'/views/js/jquery.magnific-popup.min.js');
        $this->context->controller->addJS($this->_path.'/views/js/rate.js');
        $this->context->controller->addJS($this->_path.'/views/js/zoom-min.js');
        $this->context->controller->addJS($this->_path.'/views/js/zoom.js');

        if (Tools::getIsset('controller') &&
            Tools::getValue('controller') == 'index' &&
            Configuration::get('RBTHEMEFUNCTION_POPUP_ACTIVE') == 1
        ) {
            $this->context->controller->addJS($this->_path.'/views/js/popup.js');
        }
        
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
        $this->context->controller->addCSS($this->_path.'/views/css/magnific-popup.css');
        $this->context->controller->addCSS($this->_path.'/views/css/rate.css');

        if ($this->context->customer->isLogged()) {
            $isLogged = true;
        } else {
            $isLogged = false;
        }

        $token = Tools::getToken(false);
        $link = $this->context->link->getPageLink('authentication');

        $products = Db::getInstance()->executeS(
            'SELECT DISTINCT p.`id_product`, pl.`name`, pl.`link_rewrite`
            FROM `'._DB_PREFIX_.'product` p
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
            ON p.`id_product` = pl.`id_product`
            WHERE pl.`id_lang` = '.(int)$this->context->language->id.'
            ORDER BY RAND()
            LIMIT 10'
        );

        $collections = array();

        foreach ($products as $product) {
            $url = $this->context->link->getProductLink($product['id_product']);
            $image = Image::getCover($product['id_product']);

            $url_img = $this->context->link->getImageLink(
                $product['link_rewrite'],
                $image['id_image'],
                ImageType::getFormattedName('home')
            );

            $collections[] = "<a href='" . $url . "' class='sale-popup-img'>"
            . "<img src='" . $url_img . "' alt='" . $product['name'] . "'/>"
            . "</a>"
            . "<div class='sale-popup-content'>"
            . "<h3>"
            . "<a href='" . $url . "' title='" . $product['name'] . "'>" . $product['name'] . "</a>"
            . "</h3>"
            . "<span class='sale-popup-timeago'></span>"
            . "</div>"
            . "<span class='button-close'><i class='material-icons'>close</i></span>";
        }

        $config = $this->getConfigFacebook();

        $this->smarty->assign(array(
            'rb_width' => Configuration::get('RBTHEMEFUNCTION_POPUP_WIDTH'),
            'rb_height' => Configuration::get('RBTHEMEFUNCTION_POPUP_HEIGHT'),
            'rb_img' => Configuration::get('RBTHEMEFUNCTION_POPUP_BG'),
            'rb_form' => Configuration::get('RBTHEMEFUNCTION_POPUP_NEWSLETTER'),
            'rb_url_img' => $this->_path."/views/img/imgbg_".$this->context->shop->id.".jpg",
            'rb_text' => Configuration::get('RBTHEMEFUNCTION_POPUP_TEXT', $this->context->language->id),
        ));

        $popup = $this->fetch('module:rbthemefunction/views/templates/rb-popup.tpl');

        Media::addJsDef(array(
            'url_ajax' => $this->context->link->getModuleLink('rbthemefunction', 'ajax'),
            'url_compare' => $this->context->link->getModuleLink('rbthemefunction', 'compare'),
            'url_wishlist' => $this->context->link->getModuleLink('rbthemefunction', 'wishlist'),
            'isLogged' => $isLogged,
            'token' => $token,
            'rb_text' => $this->l('You must be logged') . '. <a href="'.$link.'">'.$this->l('Sign in').'</a>',
            'text1' => $this->l('No Product'),
            'text2' => $this->l('You Can Not Delete Default Wishlist'),
            'rb_modal' => $this->fetch('module:rbthemefunction/views/templates/rb-modal.tpl'),
            'cancel_rating_txt' => $this->l('Cancel Rating'),
            'collections' => $collections,
            'rb_facebook' => $config,
            'rb_slick' => $this->getConfigSlick(),
            'rb_zoom' => array(
                'active' => Configuration::get('RBTHEMEFUNCTION_ZOOM_ACTIVE'),
                'type' => Configuration::get('RBTHEMEFUNCTION_ZOOM_TYPE'),
                'scroll' => Configuration::get('RBTHEMEFUNCTION_ZOOM_SCROll'),
            ),
            'popup' => $popup,
            'active' => Configuration::get('RBTHEMEFUNCTION_POPUP_ACTIVE'),
            'rb_width' => Configuration::get('RBTHEMEFUNCTION_POPUP_WIDTH'),
            'rb_height' => Configuration::get('RBTHEMEFUNCTION_POPUP_HEIGHT'),
            'rb_view' => Configuration::get('RBTHEMEFUNCTION_GRID_VIEW'),
        ));
    }

    public function displayProductSearch($products)
    {
        $this->smarty->assign(array(
            'products' => $products,
        ));

        return $this->fetch('module:rbthemefunction/views/templates/rb-product.tpl');
    }

    public function hookdisplayRbAddToCart($params)
    {
        if (Configuration::get('RBTHEMEFUNCTION_ADD_TO_CART') != 1 || $params['product']['quantity'] < 0) {
            return;
        }

        $this->smarty->assign(array(
            'static_token' => Tools::getToken(false),
            'product' => $params['product'],
        ));

        return $this->display(__FILE__, 'views/templates/hook/rb-add-to-cart.tpl');
    }

    public function hookdisplayAfterBodyOpeningTag()
    {
        if (Configuration::get('RBTHEMEFUNCTION_LOADING_PAGE') != 1) {
            return;
        }

        $this->smarty->assign(array(
            'color_back' => Configuration::get('RBTHEMEFUNCTION_BACKGROUND_LOADING'),
            'rb_img' => $this->context->shop->getBaseURL(true, true) . 'img/logo.png',
        ));

        return $this->display(__FILE__, 'views/templates/hook/rb-loading.tpl');
    }

    public function hookdisplayRbCompareProduct($params)
    {
        if (Configuration::get('RBTHEMEFUNCTION_COMPARE') != 1) {
            return;
        }

        $arr = array();
        $id_product = $params['product']['id_product'];

        if (isset($this->context->cookie->rb_compare) && $this->context->cookie->rb_compare) {
            $arr = explode(',', $this->context->cookie->rb_compare);
        }

        $this->smarty->assign(array(
            'rb_class' => in_array($id_product, $arr) ? ' rb_added ' : '',
            'product' => $params['product'],
            'rb_compare'    => array(
                'url' => $this->context->link->getModuleLink('rbthemefunction', 'compare'),
            ),
        ));

        return $this->display(__FILE__, 'views/templates/hook/rb-compare.tpl');
    }

    public function hookdisplayRbBrandProduct($params)
    {
        $id_manufacturer = $params['product']['id_manufacturer'];

        $obj_brand = new Manufacturer(
            $id_manufacturer,
            $this->context->language->id,
            $this->context->shop->id
        );

        $this->smarty->assign(array(
            'name' => $obj_brand->name,
            'link' => $this->context->link->getManufacturerLink($obj_brand),
        ));

        return $this->display(__FILE__, 'views/templates/hook/rb-brand.tpl');
    }

    public function hookdisplayRbWishListProduct($params)
    {
        if (Configuration::get('RBTHEMEFUNCTION_WISHLIST') != 1) {
            return;
        }

        $wishlists = array();
        $wishlists_added = array();
        $id_wishlist = false;
        $added_wishlist = false;
        $id_product = $params['product']['id_product'];
        $id_product_attribute = $params['product']['id_product_attribute'];

        if ($this->context->customer->isLogged()) {
            $obj_wishlist = new RbthemefunctionWishList();
            $wishlists = $obj_wishlist->getByIdCustomer($this->context->customer->id);

            if (!empty($wishlists)) {
                $id_wishlist = $wishlists[0]['id_rbthemefunction_wishlist'];
                $wishlist_products = $obj_wishlist->getSimpleProductByIdCustomer(
                    $this->context->customer->id,
                    $this->context->shop->id
                );

                $check_product_added = array(
                    'id_product' => $id_product,
                    'id_product_attribute' => $id_product_attribute
                );

                foreach ($wishlist_products as $key => $wishlist_product) {
                    if (in_array($check_product_added, $wishlist_product)) {
                        $added_wishlist = true;
                        $wishlists_added[] = $key;
                    }
                }
            }
        }

        $this->smarty->assign(array(
            'wishlists_added' => $wishlists_added,
            'wishlists' => $wishlists,
            'added_wishlist' => $added_wishlist,
            'id_wishlist' => $id_wishlist,
            'rb_wishlist_id_product' => $id_product,
            'rb_wishlist_id_product_attribute' => $id_product_attribute,
        ));

        return $this->display(__FILE__, 'views/templates/hook/rb-wishlist.tpl');
    }

    public function hookdisplayRbTopLogin()
    {
        $this->smarty->assign(array(
            'rb_login' => $this->context->customer->id ? 1 : 0,
            'logout_url' => $this->context->customer->id ?
            $this->context->link->getPageLink('index', true, null, 'mylogout') : '#',
            'customerName' => $this->context->customer->id ?
            $this->context->customer->firstname . ' ' . $this->context->customer->lastname : '',
            'my_account_url' => $this->context->customer->id ?
            $this->context->link->getPageLink('my-account', true) : '#',
            'top' => 'login',
        ));

        return $this->display(__FILE__, 'views/templates/hook/rb-top-compare.tpl');
    }

    public function hookdisplayRbTopCompare()
    {
        if (Configuration::get('RBTHEMEFUNCTION_COMPARE') != 1) {
            return;
        }

        $arr = array();

        if (isset($this->context->cookie->rb_compare) && $this->context->cookie->rb_compare) {
            $arr = explode(',', $this->context->cookie->rb_compare);
        }

        $this->smarty->assign(array(
            'rb_compare' => $this->context->link->getModuleLink('rbthemefunction', 'compare'),
            'rb_number_compare' => count($arr),
            'top' => 'compare',
        ));

        return $this->display(__FILE__, 'views/templates/hook/rb-top-compare.tpl');
    }

    public function hookdisplayRbTopWishlist()
    {
        if (Configuration::get('RBTHEMEFUNCTION_WISHLIST') != 1) {
            return;
        }

        $obj_wishlist = new RbthemefunctionWishList();

        $this->smarty->assign(array(
            'rb_wishlist' => $this->context->link->getModuleLink('rbthemefunction', 'wishlist'),
            'rb_number_wishlist' => $this->context->customer->id ?
            $obj_wishlist->getToTalByCustomer($this->context->customer->id) : 0,
            'top' => 'wishlist',
        ));

        return $this->display(__FILE__, 'views/templates/hook/rb-top-compare.tpl');
    }

    public function hookdisplayRbReviewProduct($params)
    {
        if (Configuration::get('RBTHEMEFUNCTION_REVIEW') != 1) {
            return;
        }

        $id_product = $params['product']['id_product'];
        $id_guest = (!$id_customer = $this->context->cookie->id_customer) ?
        $this->context->cookie->id_guest : false;
        $obj_review = new RbthemefunctionReview();

        $reviews = $obj_review->getByProduct(
            $id_product,
            1,
            null,
            $this->context->cookie->id_customer
        );

        $this->smarty->assign(array(
            'type' => $params['type'],
            'rb_login' => $this->context->customer->id ? 1 : 0,
            'rb_login_url' => $this->context->link->getPageLink('authentication'),
            'id_product' => $id_product,
            'reviews' => $reviews,
            'number' => count($reviews),
        ));

        return $this->display(__FILE__, 'views/templates/hook/rb-review.tpl');
    }

    public function hookDisplayProductExtraContent($params)
    {
        $config = $this->getConfigFacebook();

        if (isset($config['comments_tab']) &&
            @$config['comments_tab'] == 1 &&
            @$config['comments_state'] == 1
        ) {
            $tabs = array();

            $title = $this->l('Facebook Comments');
            $content = '<div id="fcbc"><fb:comments href="'.$config['product_page_url'].
            '" colorscheme="light" width="'.$config["comments_width"].'"></fb:comments></div>';

            $tabs[] = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent())
                    ->setTitle($title)
                    ->setContent($content);

            return $tabs;
        }
    }

    public function hookdisplayFooterAfter($params)
    {
        if (Configuration::get('RBTHEMEFUNCTION_SALE_POPUP') != 1) {
            return;
        }

        return $this->display(__FILE__, 'views/templates/hook/rb-popup.tpl');
    }

    public function hookdisplayNextPrevProduct($params)
    {
        if (Configuration::get('RBTHEMEFUNCTION_BUTTON_NEXT_PREV') != 1) {
            return;
        }

        $id_product = $params['product']['id_product'];

        $productNext = Db::getInstance()->executeS(
            'SELECT `id_product`
            FROM `'._DB_PREFIX_.'product`
            WHERE `id_product` > '.(int)$id_product.'
            AND `active` = 1 
            LIMIT 0,1'
        );

        $productPrev = Db::getInstance()->executeS(
            'SELECT `id_product`
            FROM `'._DB_PREFIX_.'product`
            WHERE `id_product` < '.(int)$id_product.'
            AND `active` = 1
            LIMIT 0,1'
        );

        $productNext = $this->dataProduct($productNext);
        $productPrev = $this->dataProduct($productPrev);

        $this->smarty->assign(array(
            'productNext' => $productNext,
            'productPrev' => $productPrev,
        ));

        return $this->display(__FILE__, 'views/templates/hook/rb-next-prev.tpl');
    }

    public function hookdisplayTagCateProduct($params)
    {
        if (Configuration::get('RBTHEMEFUNCTION_TAG_CATEGORY') != 1) {
            return;
        }

        $id_product = $params['product']['id_product'];
        $obj_product = new Product($id_product, $this->id_lang, $this->id_shop);
        $categories = Product::getProductCategoriesFull($id_product, $this->id_lang);
        $tags = $obj_product->getTags($this->id_lang);

        $this->smarty->assign(array(
            'obj_link' => $this->context->link,
            'categories' => $categories,
            'tags' => $tags,
        ));

        return $this->display(__FILE__, 'views/templates/hook/rb-tag-cate.tpl');
    }

    public function dataProduct($products)
    {
        if (empty($products)) {
            return array();
        }

        $id_product = $products[0]['id_product'];
        $product = new Product($id_product, true, $this->context->language->id);
        $image = Image::getCover($id_product);

        $imagePath = $this->context->link->getImageLink(
            $product->link_rewrite,
            $image['id_image'],
            ImageType::getFormattedName('home')
        );

        return array(
            'name' => $product->name,
            'url' => $this->context->link->getProductLink($id_product),
            'image' => $imagePath,
        );
    }

    public function hookmoduleRoutes()
    {
        $routes = array();

        $routes['module-rbthemefunction-compare'] = array(
            'controller' => 'compare',
            'rule' => 'compare',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'rbthemefunction'
            )
        );

        $routes['module-rbthemefunction-wishlist'] = array(
            'controller' => 'wishlist',
            'rule' => 'wishlist',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'rbthemefunction'
            )
        );

        $routes['module-rbthemefunction-viewwishlist'] = array(
            'controller' => 'viewwishlist',
            'rule' => 'viewwishlist',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'rbthemefunction'
            )
        );

        return $routes;
    }
}
