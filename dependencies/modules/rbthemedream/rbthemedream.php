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

require_once(_PS_MODULE_DIR_.'rbthemedream/classes/RbthemedreamHome.php');
require_once(_PS_MODULE_DIR_.'rbthemedream/classes/RbthemedreamLink.php');
require_once(_PS_MODULE_DIR_.'rbthemedream/lib/rb-front.php');

class Rbthemedream extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'rbthemedream';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'R_B';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Rb Theme Dream');
        $this->description = $this->l('This is great module for create theme Prestashop');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->context = Context::getContext();
        $this->id_lang = $this->context->language->id;
        $this->id_shop = $this->context->shop->id;
    }

    public function install()
    {
        if (parent::install() && $this->rbRegisterHook()) {
            $res = true;
            $class = 'Admin'.Tools::ucfirst($this->name).'Management';
            $id_parent = Tab::getIdFromClassName('IMPROVE');
            $tab1 = new Tab();
            $tab1->class_name = $class;
            $tab1->module = $this->name;
            $tab1->id_parent = $id_parent;
            $langs = Language::getLanguages(false);

            foreach ($langs as $l) {
                $tab1->name[$l['id_lang']] = $this->l('Rb Theme Dream');
            }

            $tab1->add(true, false);

            Db::getInstance()->execute(
                'UPDATE `'._DB_PREFIX_.'tab`
                SET `icon` = "dashboard"
                WHERE `id_tab` = "'.(int)$tab1->id.'"'
            );

            $this->installModuleTab('Home', 'home', 'AdminRbThemeDreamManagement');
            $this->installModuleTab('Link', 'link', 'AdminRbThemeDreamManagement');
            $this->installModuleTab('Setting', 'setting', 'AdminRbThemeDreamManagement');
            $this->installModuleTab('Live', 'live', 'AdminRbThemeDreamManagement');

            include(dirname(__FILE__).'/sql/install.php');
            include(dirname(__FILE__).'/sql/same.php');

            return (bool)$res;
        }

        return false;
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

    public function rbRegisterHook()
    {
        $ps_languageselector = Module::getInstanceByName('ps_languageselector');
        $ps_languageselector->registerHook('displayRbLanguage');
        $ps_languageselector->unregisterHook('displayNav2');

        $ps_currencyselector = Module::getInstanceByName('ps_currencyselector');
        $ps_currencyselector->registerHook('displayRbCurrency');
        $ps_currencyselector->unregisterHook('displayNav2');

        $ps_shoppingcart = Module::getInstanceByName('ps_shoppingcart');
        $ps_shoppingcart->registerHook('displayRbTopCart');
        $ps_shoppingcart->unregisterHook('displayNav2');
            
        $ps_contactinfo = Module::getInstanceByName('ps_contactinfo');
        $ps_contactinfo->registerHook('displayRbTopContact');
        $ps_contactinfo->registerHook('displayRbFooterContact');
        $ps_contactinfo->unregisterHook('displayNav1');
        $ps_contactinfo->unregisterHook('displayFooter');

        $ps_searchbar = Module::getInstanceByName('ps_searchbar');
        $ps_searchbar->registerHook('displayRbSearch');
        $ps_searchbar->unregisterHook('displayTop');

        $ps_customtext = Module::getInstanceByName('ps_customtext');
        $ps_customtext->registerHook('displayRbText');
        $ps_customtext->unregisterHook('displayHome');

        $ps_emailsubscription = Module::getInstanceByName('ps_emailsubscription');
        $ps_emailsubscription->registerHook('displayRbEmail');
        $ps_emailsubscription->unregisterHook('displayFooterBefore');

        $res = true;

        $res &= $this->registerHook('header');
        $res &= $this->registerHook('displayHome');
        $res &= $this->registerHook('displayRbFooter');
        $res &= $this->registerHook('displayRbSocial');
        $res &= $this->registerHook('moduleRoutes');
        $res &= $this->registerHook('actionAdminControllerSetMedia');

        return $res;
    }

    public function uninstall()
    {
        $this->uninstallModuleTab('management');
        $this->uninstallModuleTab('setting');
        $this->uninstallModuleTab('home');
        $this->uninstallModuleTab('live');
        $this->uninstallModuleTab('link');

        include(dirname(__FILE__).'/sql/uninstall.php');

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
            $background_color = Tools::getValue('RBTHEMEDREAM_BACKGROUND_COLOR');

            if (!Validate::isColor($background_color)) {
                $errors[] = $this->trans(
                    'Invalid value for the Background Color',
                    array(),
                    'Modules.Rbthemedream.Admin'
                );
            }

            $latitude = Tools::getValue('RBTHEMEDREAM_MAP_LATITUDE');

            if (!Validate::isFloat($latitude)) {
                $errors[] = $this->trans(
                    'Invalid value for the Latitude',
                    array(),
                    'Modules.Rbthemedream.Admin'
                );
            }

            $longitude = Tools::getValue('RBTHEMEDREAM_MAP_LONGITUDE');

            if (!Validate::isFloat($longitude)) {
                $errors[] = $this->trans(
                    'Invalid value for the Longitude',
                    array(),
                    'Modules.Rbthemedream.Admin'
                );
            }

            $phone = Tools::getValue('RBTHEMEDREAM_PHONE_NUMBER');

            if (!Validate::isInt($phone)) {
                $errors[] = $this->trans(
                    'Invalid value for the Phone Number',
                    array(),
                    'Modules.Rbthemedream.Admin'
                );
            }

            $email = Tools::getValue('RBTHEMEDREAM_EMAIL');

            if (!Validate::isEmail($email)) {
                $errors[] = $this->trans(
                    'Invalid value for the Email',
                    array(),
                    'Modules.Rbthemedream.Admin'
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

                if (Tools::getIsset('rb_rbthemedream') ||
                    Tools::getIsset('rb_rbthememenu') ||
                    Tools::getIsset('rb_rbthemeblog') ||
                    Tools::getIsset('rb_slider')
                ) {
                    $output .= $this->displayConfirmation(
                        $this->trans(
                            'Export Data Success.',
                            array(),
                            'Admin.Notifications.Success'
                        )
                    );
                }
            }
        } else if (((bool)Tools::isSubmit('submitUpdateModule')) == 1) {
            $this->rbRegisterHook();

            $rbthemeblog = Module::getInstanceByName('rbthemeblog');
            $rbthemeblog->rbRegisterHook();

            $rbthemefunction = Module::getInstanceByName('rbthemefunction');
            $rbthemefunction->rbRegisterHook();

            $rbthememenu = Module::getInstanceByName('rbthememenu');
            $rbthememenu->rbRegisterHook();

            $rbthemeslider = Module::getInstanceByName('rbthemeslider');
            $rbthemeslider->rbRegisterHook();

            $output .= $this->displayConfirmation(
                $this->trans(
                    'Update Module Success',
                    array(),
                    'Admin.Notifications.Success'
                )
            );
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        return $output . $this->renderForm();
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

    public function createText($text)
    {
        $html = '<div class="bootstrap">';
        $html .= '<div class="alert alert-success">'.$this->l($text).'</div>';
        $html .= '</div>';

        return $html;
    }

    protected function getConfigForm()
    {
        $fields_form = array();

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

        $header = array();
        $footer = array();
        $product_list = array();
        $dirname = _PS_ALL_THEMES_DIR_._THEME_NAME_ . '/templates/';
        
        if (is_dir($dirname . '_partials/header/')) {
            $total_files = 1;
            $dp = opendir($dirname . '_partials/header/');

            if ($dp) {
                while (($filename=readdir($dp)) == true) {
                    if (($filename !=".") && ($filename !="..")) {
                        $header[] = array(
                            'id' => $total_files,
                            'name' => $this->l('Header - ') . $total_files,
                        );

                        $total_files++;
                    }
                }
            }
        }

        if (is_dir($dirname . '_partials/footer/')) {
            $total_footer = 1;

            $dp = opendir($dirname . '_partials/footer/');

            if ($dp) {
                while (($filename=readdir($dp)) == true) {
                    if (($filename !=".") && ($filename !="..")) {
                        $footer[] = array(
                            'id' => $total_footer,
                            'name' => $this->l('Footer - ') . $total_footer,
                        );

                        $total_footer++;
                    }
                }
            }
        }

        if (is_dir($dirname . 'catalog/_partials/miniatures/product-list/')) {
            $total_product = 1;

            $dp = opendir($dirname . 'catalog/_partials/miniatures/product-list/');

            if ($dp) {
                while (($filename=readdir($dp)) == true) {
                    if (($filename !=".") && ($filename !="..")) {
                        $product_list[] = array(
                            'id' => $total_product,
                            'name' => $this->l('Product - ') . $total_product,
                        );

                        $total_product++;
                    }
                }
            }
        }

        $fields_form[0]['form'] = array(
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Select Header'),
                    'name' => 'RBTHEMEDREAM_HEADER',
                    'options' => array(
                        'query' => $header,
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select Footer'),
                    'name' => 'RBTHEMEDREAM_FOOTER',
                    'options' => array(
                        'query' => $footer,
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select Product List'),
                    'name' => 'RBTHEMEDREAM_PRODUCT_LIST',
                    'options' => array(
                        'query' => $product_list,
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Col Product List'),
                    'name' => 'RBTHEMEDREAM_COL_PRODUCT_LIST',
                    'desc' => 'col-md-3',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Float Header'),
                    'name' => 'RBTHEMEDREAM_FLOAT_HEADER',
                    'values' => $switch,
                ),
                array(
                    'type' => 'html',
                    'name' => 'html_data',
                    'html_content' => $this->htmlFormLayout(),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default')
        );

        $fields_form[1]['form'] = array(
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show Map'),
                    'name' => 'RBTHEMEDREAM_SHOW_MAP',
                    'values' => $switch,
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'label' => $this->l('Latitude'),
                    'name' => 'RBTHEMEDREAM_MAP_LATITUDE',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'label' => $this->l('Longitude'),
                    'name' => 'RBTHEMEDREAM_MAP_LONGITUDE',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'label' => $this->l('Company Name'),
                    'name' => 'RBTHEMEDREAM_COMPANY_NAME',
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Address'),
                    'name' => 'RBTHEMEDREAM_ADDRESS',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'label' => $this->l('Phone Number'),
                    'name' => 'RBTHEMEDREAM_PHONE_NUMBER',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'prefix' => '<i class="icon icon-envelope"></i>',
                    'desc' => $this->l('Enter a valid email address'),
                    'name' => 'RBTHEMEDREAM_EMAIL',
                    'label' => $this->l('Email'),
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Custom text'),
                    'name' => 'RBTHEMEDREAM_CONTENT_PAGE',
                    'autoload_rte' => true,
                    'lang' => true,
                    'cols' => 60,
                    'rows' => 30,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default')
        );

        $fields_form[2]['form'] = array(
            'input' => array(
                array(
                    'col' => 5,
                    'type' => 'text',
                    'label' => $this->l('Facebook'),
                    'name' => 'RBTHEMEDREAM_FACEBOOK',
                    'desc' => 'https://www.facebook.com/',
                ),
                array(
                    'col' => 5,
                    'type' => 'text',
                    'label' => $this->l('Twitter'),
                    'name' => 'RBTHEMEDREAM_TWITTER',
                    'desc' => 'https://twitter.com/',
                ),
                array(
                    'col' => 5,
                    'type' => 'text',
                    'label' => $this->l('instagram'),
                    'name' => 'RBTHEMEDREAM_INSTAGRAM',
                    'desc' => 'https://www.instagram.com/',
                ),
                array(
                    'col' => 5,
                    'type' => 'text',
                    'label' => $this->l('Pinterest'),
                    'name' => 'RBTHEMEDREAM_PINTEREST',
                    'desc' => 'https://www.pinterest.com/',
                ),
                array(
                    'col' => 5,
                    'type' => 'text',
                    'label' => $this->l('Youtube'),
                    'name' => 'RBTHEMEDREAM_YOUTUBE',
                    'desc' => 'https://www.youtube.com/',
                ),
                array(
                    'col' => 5,
                    'type' => 'text',
                    'label' => $this->l('Vimeo'),
                    'name' => 'RBTHEMEDREAM_VIMEO',
                    'desc' => 'https://vimeo.com/',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default')
        );

        $fields_form[3]['form'] = array(
            'input' => array(
                array(
                    'type' => 'html',
                    'name' => 'html_data',
                    'html_content' => $this->htmlFormExport(),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default')
        );

        return $fields_form;
    }

    public function htmlFormLayout()
    {
        $obj_home = new RbthemedreamHome();
        $rbthemedreams = $obj_home->getAllHome();

        if (!empty($rbthemedreams)) {
            foreach ($rbthemedreams as $key_rb => $rbthemedream) {
                $page = 'RBTHEMEDREAM_PAGE_'.$rbthemedream['id_rbthemedream_home'];
                $home = 'RBTHEMEDREAM_HOME_'.$rbthemedream['id_rbthemedream_home'];
                $rbthemedreams[$key_rb]['page'] = Configuration::get($page);
                $rbthemedreams[$key_rb]['home'] = Configuration::get($home);
            }
        }

        $this->context->smarty->assign(array(
            'rbthemedreams' => $rbthemedreams,
        ));

        return $this->display(__FILE__, 'views/templates/rb-home.tpl');
    }

    public function htmlFormExport()
    {
        $this->context->smarty->assign(array(
            'rb_dir' => _PS_MODULE_DIR_,
            'module_link' => $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='
            .$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
        ));

        return $this->display(__FILE__, 'views/templates/rb-export.tpl');
    }

    protected function getConfigFormValues()
    {
        $content_page =  array();

        foreach (Language::getLanguages(false) as $lang) {
            $content_page[$lang['id_lang']] = Configuration::get('RBTHEMEDREAM_CONTENT_PAGE', $lang['id_lang']);
        }

        return array(
            'RBTHEMEDREAM_HEADER' => Configuration::get('RBTHEMEDREAM_HEADER'),
            'RBTHEMEDREAM_FOOTER' => Configuration::get('RBTHEMEDREAM_FOOTER'),
            'RBTHEMEDREAM_PRODUCT_LIST' => Configuration::get('RBTHEMEDREAM_PRODUCT_LIST'),
            'RBTHEMEDREAM_COL_PRODUCT_LIST' => Configuration::get('RBTHEMEDREAM_COL_PRODUCT_LIST'),
            'RBTHEMEDREAM_SHOW_MAP' => Configuration::get('RBTHEMEDREAM_SHOW_MAP'),
            'RBTHEMEDREAM_FLOAT_HEADER' => Configuration::get('RBTHEMEDREAM_FLOAT_HEADER'),
            'RBTHEMEDREAM_MAP_LATITUDE' => Configuration::get('RBTHEMEDREAM_MAP_LATITUDE'),
            'RBTHEMEDREAM_MAP_LONGITUDE' => Configuration::get('RBTHEMEDREAM_MAP_LONGITUDE'),
            'RBTHEMEDREAM_COMPANY_NAME' => Configuration::get('RBTHEMEDREAM_COMPANY_NAME'),
            'RBTHEMEDREAM_ADDRESS' => Configuration::get('RBTHEMEDREAM_ADDRESS'),
            'RBTHEMEDREAM_PHONE_NUMBER' => Configuration::get('RBTHEMEDREAM_PHONE_NUMBER'),
            'RBTHEMEDREAM_EMAIL' => Configuration::get('RBTHEMEDREAM_EMAIL'),
            'RBTHEMEDREAM_CONTENT_PAGE' => $content_page,
            'RBTHEMEDREAM_FACEBOOK' => Configuration::get('RBTHEMEDREAM_FACEBOOK'),
            'RBTHEMEDREAM_TWITTER' => Configuration::get('RBTHEMEDREAM_TWITTER'),
            'RBTHEMEDREAM_INSTAGRAM' => Configuration::get('RBTHEMEDREAM_INSTAGRAM'),
            'RBTHEMEDREAM_PINTEREST' => Configuration::get('RBTHEMEDREAM_PINTEREST'),
            'RBTHEMEDREAM_YOUTUBE' => Configuration::get('RBTHEMEDREAM_YOUTUBE'),
            'RBTHEMEDREAM_VIMEO' => Configuration::get('RBTHEMEDREAM_VIMEO'),
        );
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        $this->exportDataSame();

        $obj_home = new RbthemedreamHome();
        $rbthemedreams = $obj_home->getAllHome();

        if (!empty($rbthemedreams)) {
            foreach ($rbthemedreams as $rbthemedream) {
                $page = 'RBTHEMEDREAM_PAGE_'.$rbthemedream['id_rbthemedream_home'];
                $home = 'RBTHEMEDREAM_HOME_'.$rbthemedream['id_rbthemedream_home'];

                Configuration::updateValue($page, Tools::getValue($page));
                Configuration::updateValue($home, Tools::getValue($home));
            }
        }

        foreach (array_keys($form_values) as $key) {
            if ($key == 'RBTHEMEDREAM_CONTENT_PAGE') {
                $content_page =  array();

                foreach (Language::getLanguages(false) as $lang) {
                    $content_page[$lang['id_lang']] = Tools::getValue('RBTHEMEDREAM_CONTENT_PAGE_' . $lang['id_lang']);
                }

                Configuration::updateValue($key, $content_page, true);
            } else {
                Configuration::updateValue($key, Tools::getValue($key));
            }
        }
    }

    public function exportDataSame()
    {
        if (Tools::getIsset('rb_rbthemedream')) {
            $this->exportDataRbthemedream();
        }

        if (Tools::getIsset('rb_rbthememenu')) {
            $this->exportDataRbthememenu();
        }

        if (Tools::getIsset('rb_rbthemeblog')) {
            $this->exportDataRbthemeblog();
        }

        if (Tools::getIsset('rb_slider')) {
            $this->exportDataRbthemeslider();
        }

        return true;
    }

    public function exportDataRbthemeslider()
    {
        $content = "<?php \n";
        $content .= '$sql = array();' . "\n";

        $rbslider_attachment_images = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbslider_attachment_images`');

        if (!empty($rbslider_attachment_images)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbslider_attachment_images(`ID`,`file_name`) VALUES ';
            $count = 1;

            foreach ($rbslider_attachment_images as $rbslider_attachment_image) {
                if ($count == 1) {
                    $content .= "('".$rbslider_attachment_image['ID']."','".$rbslider_attachment_image['file_name']."')";
                } else {
                    $content .= ",('".$rbslider_attachment_image['ID']."','".$rbslider_attachment_image['file_name']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbslider_csss = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbslider_css`');

        if (!empty($rbslider_csss)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbslider_css(`id`,`handle`,`settings`,`hover`,`params`,`advanced`) VALUES ';
            $count = 1;

            foreach ($rbslider_csss as $rbslider_css) {
                $rbslider_css['params'] = str_replace('\"', '', $rbslider_css['handle']);
                $rbslider_css['settings'] = str_replace('\"', '', $rbslider_css['settings']);
                $rbslider_css['hover'] = str_replace('\"', '', $rbslider_css['hover']);
                $rbslider_css['advanced'] = str_replace('\"', '', $rbslider_css['advanced']);
                $rbslider_css['params'] = str_replace('\"', '', $rbslider_css['params']);

                if ($count == 1) {
                    $content .= "('".$rbslider_css['id']."','".str_replace('"', '\"', $rbslider_css['handle'])."','".str_replace('"', '\"', $rbslider_css['settings'])."','".str_replace('"', '\"', $rbslider_css['hover'])."','".str_replace('"', '\"', $rbslider_css['params'])."','".str_replace('"', '\"', $rbslider_css['advanced'])."')";
                } else {
                    $content .= ",('".$rbslider_css['id']."','".str_replace('"', '\"', $rbslider_css['handle'])."','".str_replace('"', '\"', $rbslider_css['settings'])."','".str_replace('"', '\"', $rbslider_css['hover'])."','".str_replace('"', '\"', $rbslider_css['params'])."','".str_replace('"', '\"', $rbslider_css['advanced'])."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbslider_layer_animations = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbslider_layer_animations`');

        if (!empty($rbslider_layer_animations)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbslider_layer_animations(`id`,`handle`,`params`) VALUES ';
            $count = 1;

            foreach ($rbslider_layer_animations as $rbslider_layer_animation) {
                $rbslider_layer_animation['params'] = str_replace('\"', '', $rbslider_layer_animation['params']);
                $rbslider_layer_animation['handle'] = str_replace('\"', '', $rbslider_layer_animation['handle']);

                if ($count == 1) {
                    $content .= "('".$rbslider_layer_animation['id']."','".str_replace('"', '\"', $rbslider_layer_animation['handle'])."','".str_replace('"', '\"', $rbslider_layer_animation['params'])."')";
                } else {
                    $content .= ",('".$rbslider_layer_animation['id']."','".str_replace('"', '\"', $rbslider_layer_animation['handle'])."','".str_replace('"', '\"', $rbslider_layer_animation['params'])."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbslider_navigations = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbslider_navigations`');

        if (!empty($rbslider_navigations)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbslider_navigations(`id`,`name`,`handle`,`css`,`markup`,`settings`) VALUES ';
            $count = 1;

            foreach ($rbslider_navigations as $rbslider_navigation) {
                $rbslider_navigation['css'] = str_replace('\"', '', $rbslider_navigation['css']);
                $rbslider_navigation['handle'] = str_replace('\"', '', $rbslider_navigation['handle']);
                $rbslider_navigation['markup'] = str_replace('\"', '', $rbslider_navigation['markup']);
                $rbslider_navigation['settings'] = str_replace('\"', '', $rbslider_navigation['settings']);

                if ($count == 1) {
                    $content .= "('".$rbslider_navigation['id']."','".$rbslider_navigation['name']."','".str_replace('"', '\"', $rbslider_navigation['handle'])."','".str_replace('"', '\"', $rbslider_navigation['markup'])."','".str_replace('"', '\"', $rbslider_navigation['settings'])."')";
                } else {
                    $content .= ",('".$rbslider_navigation['id']."','".$rbslider_navigation['name']."','".str_replace('"', '\"', $rbslider_navigation['handle'])."','".str_replace('"', '\"', $rbslider_navigation['markup'])."','".str_replace('"', '\"', $rbslider_navigation['settings'])."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbslider_options = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbslider_options`');

        if (!empty($rbslider_options)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbslider_options(`id`,`name`,`value`) VALUES ';
            $count = 1;

            foreach ($rbslider_options as $rbslider_option) {
                $rbslider_option['value'] = str_replace('\"', '', $rbslider_option['value']);

                if ($count == 1) {
                    $content .= "('".$rbslider_option['id']."','".$rbslider_option['name']."','".str_replace('"', '\"', $rbslider_option['value'])."')";
                } else {
                    $content .= ",('".$rbslider_option['id']."','".$rbslider_option['name']."','".str_replace('"', '\"', $rbslider_option['value'])."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbslider_settings = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbslider_settings`');

        if (!empty($rbslider_settings)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbslider_settings(`id`,`general`,`params`) VALUES ';
            $count = 1;

            foreach ($rbslider_settings as $rbslider_setting) {
                $rbslider_setting['general'] = str_replace('\"', '', $rbslider_setting['general']);
                $rbslider_setting['params'] = str_replace('\"', '', $rbslider_setting['params']);

                if ($count == 1) {
                    $content .= "('".$rbslider_setting['id']."','".str_replace('"', '\"', $rbslider_setting['general'])."','".str_replace('"', '\"', $rbslider_setting['params'])."')";
                } else {
                    $content .= ",('".$rbslider_setting['id']."','".str_replace('"', '\"', $rbslider_setting['general'])."','".str_replace('"', '\"', $rbslider_setting['params'])."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbslider_sliders = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbslider_sliders`');

        if (!empty($rbslider_sliders)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbslider_sliders(`id`,`title`,`alias`,`params`,`settings`,`type`) VALUES ';
            $count = 1;

            foreach ($rbslider_sliders as $rbslider_slider) {
                $rbslider_slider['settings'] = str_replace('\"', '', $rbslider_slider['settings']);
                $rbslider_slider['params'] = str_replace('\"', '', $rbslider_slider['params']);

                if ($count == 1) {
                    $content .= "('".$rbslider_slider['id']."','".$rbslider_slider['title']."','".$rbslider_slider['alias']."','".str_replace('"', '\"', $rbslider_slider['params'])."','".str_replace('"', '\"', $rbslider_slider['settings'])."','".$rbslider_slider['type']."')";
                } else {
                    $content .= ",('".$rbslider_slider['id']."','".$rbslider_slider['title']."','".$rbslider_slider['alias']."','".str_replace('"', '\"', $rbslider_slider['params'])."','".str_replace('"', '\"', $rbslider_slider['settings'])."','".$rbslider_slider['type']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbslider_slides = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbslider_slides`');

        if (!empty($rbslider_slides)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbslider_slides(`id`,`slider_id`,`slide_order`,`params`,`layers`,`settings`) VALUES ';
            $count = 1;

            foreach ($rbslider_slides as $rbslider_slide) {
                $rbslider_slide['layers'] = str_replace('\"', '', $rbslider_slide['layers']);
                $rbslider_slide['params'] = str_replace('\"', '', $rbslider_slide['params']);
                $rbslider_slide['settings'] = str_replace('\"', '', $rbslider_slide['settings']);

                if ($count == 1) {
                    $content .= "('".$rbslider_slide['id']."','".$rbslider_slide['slider_id']."','".$rbslider_slide['slide_order']."','".str_replace('"', '\"', $rbslider_slide['params'])."','".str_replace('"', '\"', $rbslider_slide['layers'])."','".str_replace('"', '\"', $rbslider_slide['settings'])."')";
                } else {
                    $content .= ",('".$rbslider_slide['id']."','".$rbslider_slide['slider_id']."','".$rbslider_slide['slide_order']."','".str_replace('"', '\"', $rbslider_slide['params'])."','".str_replace('"', '\"', $rbslider_slide['layers'])."','".str_replace('"', '\"', $rbslider_slide['settings'])."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbslider_static_slides = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbslider_static_slides`');

        if (!empty($rbslider_static_slides)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbslider_static_slides(`id`,`slider_id`,`params`,`layers`) VALUES ';
            $count = 1;

            foreach ($rbslider_static_slides as $rbslider_static_slide) {
                $rbslider_static_slide['layers'] = str_replace('\"', '', $rbslider_static_slide['layers']);
                $rbslider_static_slide['params'] = str_replace('\"', '', $rbslider_static_slide['params']);

                if ($count == 1) {
                    $content .= "('".$rbslider_static_slide['id']."','".$rbslider_static_slide['slider_id']."','".str_replace('"', '\"', $rbslider_static_slide['params'])."','".str_replace('"', '\"', $rbslider_static_slide['layers'])."')";
                } else {
                    $content .= ",('".$rbslider_static_slide['id']."','".$rbslider_static_slide['slider_id']."','".str_replace('"', '\"', $rbslider_static_slide['params'])."','".str_replace('"', '\"', $rbslider_static_slide['layers'])."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $content .= 'foreach ($sql as $query) {' . "\n";
        $content .= '   if (Db::getInstance()->execute($query) == false) {' . "\n";
        $content .= '       return false;' . "\n";
        $content .= '   }' . "\n";
        $content .= '}' . "\n";

        $fp = fopen(_PS_MODULE_DIR_ . 'rbthemeslider/sql/same.php', 'w');
        fwrite($fp, $content);
        fclose($fp);
    }

    public function exportDataRbthemeblog()
    {
        $content = "<?php \n";
        $content .= '$sql = array();' . "\n";

        $rbblog_posts = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbblog_post`');

        if (!empty($rbblog_posts)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbblog_post(`id_rbblog_post`,`id_rbblog_category`,`id_rbblog_post_type`,`id_rbblog_author`,`author`,`likes`,`views`,`allow_comments`,`is_featured`,`access`,`cover`,`featured`,`id_product`,`active`,`date_add`,`date_upd`) VALUES ';
            $count = 1;

            foreach ($rbblog_posts as $rbblog_post) {
                if ($count == 1) {
                    $content .= "('".$rbblog_post['id_rbblog_post']."','".$rbblog_post['id_rbblog_category']."','".$rbblog_post['id_rbblog_post_type']."','".$rbblog_post['id_rbblog_author']."','".$rbblog_post['author']."','".$rbblog_post['likes']."','".$rbblog_post['views']."','".$rbblog_post['allow_comments']."','".$rbblog_post['is_featured']."','".$rbblog_post['access']."','".$rbblog_post['cover']."','".$rbblog_post['featured']."','".$rbblog_post['id_product']."','".$rbblog_post['active']."','".$rbblog_post['date_add']."','".$rbblog_post['date_upd']."')";
                } else {
                    $content .= ",('".$rbblog_post['id_rbblog_post']."','".$rbblog_post['id_rbblog_category']."','".$rbblog_post['id_rbblog_post_type']."','".$rbblog_post['id_rbblog_author']."','".$rbblog_post['author']."','".$rbblog_post['likes']."','".$rbblog_post['views']."','".$rbblog_post['allow_comments']."','".$rbblog_post['is_featured']."','".$rbblog_post['access']."','".$rbblog_post['cover']."','".$rbblog_post['featured']."','".$rbblog_post['id_product']."','".$rbblog_post['active']."','".$rbblog_post['date_add']."','".$rbblog_post['date_upd']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbblog_post_langs = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbblog_post_lang`');

        if (!empty($rbblog_post_langs)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbblog_post_lang(`id_rbblog_post`,`id_lang`,`title`,`meta_title`,`meta_description`,`meta_keywords`,`canonical`,`short_content`,`content`,`video_code`,`external_url`,`link_rewrite`) VALUES ';
            $count = 1;

            foreach ($rbblog_post_langs as $rbblog_post_lang) {
                if ($count == 1) {
                    $content .= "('".$rbblog_post_lang['id_rbblog_post']."','".$rbblog_post_lang['id_lang']."','".$rbblog_post_lang['title']."','".$rbblog_post_lang['meta_title']."','".$rbblog_post_lang['meta_description']."','".$rbblog_post_lang['meta_keywords']."','".$rbblog_post_lang['canonical']."','".$rbblog_post_lang['short_content']."','".$rbblog_post_lang['content']."','".$rbblog_post_lang['video_code']."','".$rbblog_post_lang['external_url']."','".$rbblog_post_lang['link_rewrite']."')";
                } else {
                    $content .= ",('".$rbblog_post_lang['id_rbblog_post']."','".$rbblog_post_lang['id_lang']."','".$rbblog_post_lang['title']."','".$rbblog_post_lang['meta_title']."','".$rbblog_post_lang['meta_description']."','".$rbblog_post_lang['meta_keywords']."','".$rbblog_post_lang['canonical']."','".$rbblog_post_lang['short_content']."','".$rbblog_post_lang['content']."','".$rbblog_post_lang['video_code']."','".$rbblog_post_lang['external_url']."','".$rbblog_post_lang['link_rewrite']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbblog_post_shops = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbblog_post_shop`');

        if (!empty($rbblog_post_shops)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbblog_post_shop(`id_rbblog_post`,`id_shop`) VALUES ';
            $count = 1;

            foreach ($rbblog_post_shops as $rbblog_post_shop) {
                if ($count == 1) {
                    $content .= "('".$rbblog_post_shop['id_rbblog_post']."','".$rbblog_post_shop['id_shop']."')";
                } else {
                    $content .= ",('".$rbblog_post_shop['id_rbblog_post']."','".$rbblog_post_shop['id_shop']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbblog_post_images = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbblog_post_image`');

        if (!empty($rbblog_post_images)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbblog_post_image(`id_rbblog_post_image`,`id_rbblog_post`,`position`,`image`) VALUES ';
            $count = 1;

            foreach ($rbblog_post_images as $rbblog_post_image) {
                if ($count == 1) {
                    $content .= "('".$rbblog_post_image['id_rbblog_post_image']."','".$rbblog_post_image['id_rbblog_post']."','".$rbblog_post_image['position']."','".$rbblog_post_image['image']."')";
                } else {
                    $content .= ",('".$rbblog_post_image['id_rbblog_post_image']."','".$rbblog_post_image['id_rbblog_post']."','".$rbblog_post_image['position']."','".$rbblog_post_image['image']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbblog_post_products = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbblog_post_product`');

        if (!empty($rbblog_post_products)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbblog_post_product(`id_rbblog_post`,`id_product`) VALUES ';
            $count = 1;

            foreach ($rbblog_post_products as $rbblog_post_product) {
                if ($count == 1) {
                    $content .= "('".$rbblog_post_product['id_rbblog_post']."','".$rbblog_post_product['id_product']."')";
                } else {
                    $content .= ",('".$rbblog_post_product['id_rbblog_post']."','".$rbblog_post_product['id_product']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbblog_categories = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbblog_category`');

        if (!empty($rbblog_categories)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbblog_category(`id_rbblog_category`,`cover`,`position`,`id_parent`,`active`,`date_add`,`date_upd`) VALUES ';
            $count = 1;

            foreach ($rbblog_categories as $rbblog_category) {
                if ($count == 1) {
                    $content .= "('".$rbblog_category['id_rbblog_category']."','".$rbblog_category['cover']."','".$rbblog_category['position']."','".$rbblog_category['id_parent']."','".$rbblog_category['active']."','".$rbblog_category['date_add']."','".$rbblog_category['date_upd']."')";
                } else {
                    $content .= ",('".$rbblog_category['id_rbblog_category']."','".$rbblog_category['cover']."','".$rbblog_category['position']."','".$rbblog_category['id_parent']."','".$rbblog_category['active']."','".$rbblog_category['date_add']."','".$rbblog_category['date_upd']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbblog_category_langs = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbblog_category_lang`');

        if (!empty($rbblog_category_langs)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbblog_category_lang(`id_rbblog_category`,`id_lang`,`name`,`description`,`link_rewrite`,`meta_title`,`meta_keywords`,`canonical`,`meta_description`) VALUES ';
            $count = 1;

            foreach ($rbblog_category_langs as $rbblog_category_lang) {
                if ($count == 1) {
                    $content .= "('".$rbblog_category_lang['id_rbblog_category']."','".$rbblog_category_lang['id_lang']."','".$rbblog_category_lang['name']."','".$rbblog_category_lang['description']."','".$rbblog_category_lang['link_rewrite']."','".$rbblog_category_lang['meta_title']."','".$rbblog_category_lang['meta_keywords']."','".$rbblog_category_lang['canonical']."','".$rbblog_category_lang['meta_description']."')";
                } else {
                    $content .= ",('".$rbblog_category_lang['id_rbblog_category']."','".$rbblog_category_lang['id_lang']."','".$rbblog_category_lang['name']."','".$rbblog_category_lang['description']."','".$rbblog_category_lang['link_rewrite']."','".$rbblog_category_lang['meta_title']."','".$rbblog_category_lang['meta_keywords']."','".$rbblog_category_lang['canonical']."','".$rbblog_category_lang['meta_description']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbblog_category_shops = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbblog_category_shop`');

        if (!empty($rbblog_category_shops)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbblog_category_shop(`id_rbblog_category`,`id_shop`) VALUES ';
            $count = 1;

            foreach ($rbblog_category_shops as $rbblog_category_shop) {
                if ($count == 1) {
                    $content .= "('".$rbblog_category_shop['id_rbblog_category']."','".$rbblog_category_shop['id_shop']."')";
                } else {
                    $content .= ",('".$rbblog_category_shop['id_rbblog_category']."','".$rbblog_category_shop['id_shop']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbblog_tags = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbblog_tag`');

        if (!empty($rbblog_tags)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbblog_tag(`id_rbblog_tag`,`id_lang`,`name`) VALUES ';
            $count = 1;

            foreach ($rbblog_tags as $rbblog_tag) {
                if ($count == 1) {
                    $content .= "('".$rbblog_tag['id_rbblog_tag']."','".$rbblog_tag['id_lang']."','".$rbblog_tag['name']."')";
                } else {
                    $content .= ",('".$rbblog_tag['id_rbblog_tag']."','".$rbblog_tag['id_lang']."','".$rbblog_tag['name']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbblog_post_tags = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbblog_post_tag`');

        if (!empty($rbblog_post_tags)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbblog_post_tag(`id_rbblog_post`,`id_rbblog_tag`) VALUES ';
            $count = 1;

            foreach ($rbblog_post_tags as $rbblog_post_tag) {
                if ($count == 1) {
                    $content .= "('".$rbblog_post_tag['id_rbblog_post']."','".$rbblog_post_tag['id_rbblog_tag']."')";
                } else {
                    $content .= ",('".$rbblog_post_tag['id_rbblog_post']."','".$rbblog_post_tag['id_rbblog_tag']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbblog_comments = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbblog_comment`');

        if (!empty($rbblog_comments)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbblog_comment(`id_rbblog_comment`,`id_rbblog_post`,`id_parent`,`id_customer`,`id_guest`,`name`,`email`,`comment`,`active`,`ip`,`date_add`,`date_upd`) VALUES ';
            $count = 1;

            foreach ($rbblog_comments as $rbblog_comment) {
                if ($count == 1) {
                    $content .= "('".$rbblog_comment['id_rbblog_comment']."','".$rbblog_comment['id_rbblog_post']."','".$rbblog_comment['id_parent']."','".$rbblog_comment['id_customer']."','".$rbblog_comment['id_guest']."','".$rbblog_comment['name']."','".$rbblog_comment['email']."','".$rbblog_comment['comment']."','".$rbblog_comment['active']."','".$rbblog_comment['ip']."','".$rbblog_comment['date_add']."','".$rbblog_comment['date_upd']."')";
                } else {
                    $content .= ",('".$rbblog_comment['id_rbblog_comment']."','".$rbblog_comment['id_rbblog_post']."','".$rbblog_comment['id_parent']."','".$rbblog_comment['id_customer']."','".$rbblog_comment['id_guest']."','".$rbblog_comment['name']."','".$rbblog_comment['email']."','".$rbblog_comment['comment']."','".$rbblog_comment['active']."','".$rbblog_comment['ip']."','".$rbblog_comment['date_add']."','".$rbblog_comment['date_upd']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $content .= 'foreach ($sql as $query) {' . "\n";
        $content .= '   if (Db::getInstance()->execute($query) == false) {' . "\n";
        $content .= '       return false;' . "\n";
        $content .= '   }' . "\n";
        $content .= '}' . "\n";

        $fp = fopen(_PS_MODULE_DIR_ .'rbthemeblog/sql/same.php', 'w');
        fwrite($fp, $content);
        fclose($fp);
    }

    public function exportDataRbthememenu()
    {
        $content = "<?php \n";
        $content .= '$sql = array();' . "\n";

        $rbthememenu_menus = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthememenu_menu`');

        if (!empty($rbthememenu_menus)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthememenu_menu(`id_menu`,`sort_order`,`enabled`,`enabled_vertical`,`menu_open_new_tab`,`id_cms`,`id_manufacturer`,`id_supplier`,`id_category`,`link_type`,`sub_menu_type`,`sub_menu_max_width`,`custom_class`,`menu_icon`,`menu_img_link`,`bubble_text_color`,`menu_item_width`,`tab_item_width`,`bubble_background_color`,`menu_ver_text_color`,`menu_ver_background_color`,`background_image`,`position_background`,`menu_ver_alway_show`,`menu_ver_hidden_border`,`display_tabs_in_full_width`) VALUES ';
            $count = 1;

            foreach ($rbthememenu_menus as $rbthememenu_menu) {
                if ($count == 1) {
                    $content .= "('".$rbthememenu_menu['id_menu']."','".$rbthememenu_menu['sort_order']."','".$rbthememenu_menu['enabled']."','".$rbthememenu_menu['enabled_vertical']."','".$rbthememenu_menu['menu_open_new_tab']."','".$rbthememenu_menu['id_cms']."','".$rbthememenu_menu['id_manufacturer']."','".$rbthememenu_menu['id_supplier']."','".$rbthememenu_menu['id_category']."','".$rbthememenu_menu['link_type']."','".$rbthememenu_menu['sub_menu_type']."','".$rbthememenu_menu['sub_menu_max_width']."','".$rbthememenu_menu['custom_class']."','".$rbthememenu_menu['menu_icon']."','".$rbthememenu_menu['menu_img_link']."','".$rbthememenu_menu['bubble_text_color']."','".$rbthememenu_menu['menu_item_width']."','".$rbthememenu_menu['tab_item_width']."','".$rbthememenu_menu['bubble_background_color']."','".$rbthememenu_menu['menu_ver_text_color']."','".$rbthememenu_menu['menu_ver_background_color']."','".$rbthememenu_menu['background_image']."','".$rbthememenu_menu['position_background']."','".$rbthememenu_menu['menu_ver_alway_show']."','".$rbthememenu_menu['menu_ver_hidden_border']."','".$rbthememenu_menu['display_tabs_in_full_width']."')";
                } else {
                    $content .= ",('".$rbthememenu_menu['id_menu']."','".$rbthememenu_menu['sort_order']."','".$rbthememenu_menu['enabled']."','".$rbthememenu_menu['enabled_vertical']."','".$rbthememenu_menu['menu_open_new_tab']."','".$rbthememenu_menu['id_cms']."','".$rbthememenu_menu['id_manufacturer']."','".$rbthememenu_menu['id_supplier']."','".$rbthememenu_menu['id_category']."','".$rbthememenu_menu['link_type']."','".$rbthememenu_menu['sub_menu_type']."','".$rbthememenu_menu['sub_menu_max_width']."','".$rbthememenu_menu['custom_class']."','".$rbthememenu_menu['menu_icon']."','".$rbthememenu_menu['menu_img_link']."','".$rbthememenu_menu['bubble_text_color']."','".$rbthememenu_menu['menu_item_width']."','".$rbthememenu_menu['tab_item_width']."','".$rbthememenu_menu['bubble_background_color']."','".$rbthememenu_menu['menu_ver_text_color']."','".$rbthememenu_menu['menu_ver_background_color']."','".$rbthememenu_menu['background_image']."','".$rbthememenu_menu['position_background']."','".$rbthememenu_menu['menu_ver_alway_show']."','".$rbthememenu_menu['menu_ver_hidden_border']."','".$rbthememenu_menu['display_tabs_in_full_width']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbthememenu_menu_langs = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthememenu_menu_lang`');

        if (!empty($rbthememenu_menu_langs)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthememenu_menu_lang(`id_menu`,`id_lang`,`title`,`link`,`bubble_text`) VALUES ';
            $count = 1;

            foreach ($rbthememenu_menu_langs as $rbthememenu_menu_lang) {
                if ($count == 1) {
                    $content .= "('".$rbthememenu_menu_lang['id_menu']."','".$rbthememenu_menu_lang['id_lang']."','".$rbthememenu_menu_lang['title']."','".$rbthememenu_menu_lang['link']."','".$rbthememenu_menu_lang['bubble_text']."')";
                } else {
                    $content .= ",('".$rbthememenu_menu_lang['id_menu']."','".$rbthememenu_menu_lang['id_lang']."','".$rbthememenu_menu_lang['title']."','".$rbthememenu_menu_lang['link']."','".$rbthememenu_menu_lang['bubble_text']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbthememenu_menu_shops = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthememenu_menu_shop`');

        if (!empty($rbthememenu_menu_shops)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthememenu_menu_shop(`id_menu`,`id_shop`) VALUES ';
            $count = 1;

            foreach ($rbthememenu_menu_shops as $rbthememenu_menu_shop) {
                if ($count == 1) {
                    $content .= "('".$rbthememenu_menu_shop['id_menu']."','".$rbthememenu_menu_shop['id_shop']."')";
                } else {
                    $content .= ",('".$rbthememenu_menu_shop['id_menu']."','".$rbthememenu_menu_shop['id_shop']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbthememenu_tabs = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthememenu_tab`');

        if (!empty($rbthememenu_tabs)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthememenu_tab(`id_tab`,`id_menu`,`enabled`,`tab_img_link`,`tab_sub_width`,`tab_sub_content_pos`,`tab_icon`,`bubble_text_color`,`bubble_background_color`,`sort_order`,`background_image`,`position_background`) VALUES ';
            $count = 1;

            foreach ($rbthememenu_tabs as $rbthememenu_tab) {
                if ($count == 1) {
                    $content .= "('".$rbthememenu_tab['id_tab']."','".$rbthememenu_tab['id_menu']."','".$rbthememenu_tab['enabled']."','".$rbthememenu_tab['tab_img_link']."','".$rbthememenu_tab['tab_sub_width']."','".$rbthememenu_tab['tab_sub_content_pos']."','".$rbthememenu_tab['tab_icon']."','".$rbthememenu_tab['bubble_text_color']."','".$rbthememenu_tab['bubble_background_color']."','".$rbthememenu_tab['sort_order']."','".$rbthememenu_tab['background_image']."','".$rbthememenu_tab['position_background']."')";
                } else {
                    $content .= ",('".$rbthememenu_tab['id_tab']."','".$rbthememenu_tab['id_menu']."','".$rbthememenu_tab['enabled']."','".$rbthememenu_tab['tab_img_link']."','".$rbthememenu_tab['tab_sub_width']."','".$rbthememenu_tab['tab_sub_content_pos']."','".$rbthememenu_tab['tab_icon']."','".$rbthememenu_tab['bubble_text_color']."','".$rbthememenu_tab['bubble_background_color']."','".$rbthememenu_tab['sort_order']."','".$rbthememenu_tab['background_image']."','".$rbthememenu_tab['position_background']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbthememenu_tab_langs = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthememenu_tab_lang`');

        if (!empty($rbthememenu_tab_langs)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthememenu_tab_lang(`id_tab`,`id_lang`,`title`,`url`,`bubble_text`) VALUES ';
            $count = 1;

            foreach ($rbthememenu_tab_langs as $rbthememenu_tab_lang) {
                if ($count == 1) {
                    $content .= "('".$rbthememenu_tab_lang['id_tab']."','".$rbthememenu_tab_lang['id_lang']."','".$rbthememenu_tab_lang['title']."','".$rbthememenu_tab_lang['url']."','".$rbthememenu_tab_lang['bubble_text']."')";
                } else {
                    $content .= ",('".$rbthememenu_tab_lang['id_tab']."','".$rbthememenu_tab_lang['id_lang']."','".$rbthememenu_tab_lang['title']."','".$rbthememenu_tab_lang['url']."','".$rbthememenu_tab_lang['bubble_text']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbthememenu_columns = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthememenu_column`');

        if (!empty($rbthememenu_columns)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthememenu_column(`id_column`,`id_menu`,`id_tab`,`is_breaker`,`column_size`,`sort_order`) VALUES ';
            $count = 1;

            foreach ($rbthememenu_columns as $rbthememenu_column) {
                if ($count == 1) {
                    $content .= "('".$rbthememenu_column['id_column']."','".$rbthememenu_column['id_menu']."','".$rbthememenu_column['id_tab']."','".$rbthememenu_column['is_breaker']."','".$rbthememenu_column['column_size']."','".$rbthememenu_column['sort_order']."')";
                } else {
                    $content .= ",('".$rbthememenu_column['id_column']."','".$rbthememenu_column['id_menu']."','".$rbthememenu_column['id_tab']."','".$rbthememenu_column['is_breaker']."','".$rbthememenu_column['column_size']."','".$rbthememenu_column['sort_order']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbthememenu_blocks = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthememenu_block`');

        if (!empty($rbthememenu_blocks)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthememenu_block(`id_block`,`id_column`,`block_type`,`image`,`sort_order`,`enabled`,`id_categories`,`order_by_category`,`id_manufacturers`,`order_by_manufacturers`,`display_mnu_img`,`display_mnu_name`,`display_mnu_inline`,`id_suppliers`,`order_by_suppliers`,`display_suppliers_img`,`display_suppliers_name`,`display_suppliers_inline`,`product_type`,`id_products`,`product_count`,`id_cmss`,`display_title`,`show_description`,`show_clock`) VALUES ';
            $count = 1;

            foreach ($rbthememenu_blocks as $rbthememenu_block) {
                if ($count == 1) {
                    $content .= "('".$rbthememenu_block['id_block']."','".$rbthememenu_block['id_column']."','".$rbthememenu_block['block_type']."','".$rbthememenu_block['image']."','".$rbthememenu_block['sort_order']."','".$rbthememenu_block['enabled']."','".$rbthememenu_block['id_categories']."','".$rbthememenu_block['order_by_category']."','".$rbthememenu_block['id_manufacturers']."','".$rbthememenu_block['order_by_manufacturers']."','".$rbthememenu_block['display_mnu_img']."','".$rbthememenu_block['display_mnu_name']."','".$rbthememenu_block['display_mnu_inline']."','".$rbthememenu_block['id_suppliers']."','".$rbthememenu_block['order_by_suppliers']."','".$rbthememenu_block['display_suppliers_img']."','".$rbthememenu_block['display_suppliers_name']."','".$rbthememenu_block['display_suppliers_inline']."','".$rbthememenu_block['product_type']."','".$rbthememenu_block['id_products']."','".$rbthememenu_block['product_count']."','".$rbthememenu_block['id_cmss']."','".$rbthememenu_block['display_title']."','".$rbthememenu_block['show_description']."','".$rbthememenu_block['show_clock']."')";
                } else {
                    $content .= ",('".$rbthememenu_block['id_block']."','".$rbthememenu_block['id_column']."','".$rbthememenu_block['block_type']."','".$rbthememenu_block['image']."','".$rbthememenu_block['sort_order']."','".$rbthememenu_block['enabled']."','".$rbthememenu_block['id_categories']."','".$rbthememenu_block['order_by_category']."','".$rbthememenu_block['id_manufacturers']."','".$rbthememenu_block['order_by_manufacturers']."','".$rbthememenu_block['display_mnu_img']."','".$rbthememenu_block['display_mnu_name']."','".$rbthememenu_block['display_mnu_inline']."','".$rbthememenu_block['id_suppliers']."','".$rbthememenu_block['order_by_suppliers']."','".$rbthememenu_block['display_suppliers_img']."','".$rbthememenu_block['display_suppliers_name']."','".$rbthememenu_block['display_suppliers_inline']."','".$rbthememenu_block['product_type']."','".$rbthememenu_block['id_products']."','".$rbthememenu_block['product_count']."','".$rbthememenu_block['id_cmss']."','".$rbthememenu_block['display_title']."','".$rbthememenu_block['show_description']."','".$rbthememenu_block['show_clock']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbthememenu_block_langs = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthememenu_block_lang`');

        if (!empty($rbthememenu_block_langs)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthememenu_block_lang(`id_block`,`id_lang`,`title`,`content`,`title_link`,`image_link`) VALUES ';
            $count = 1;

            foreach ($rbthememenu_block_langs as $rbthememenu_block_lang) {
                if ($count == 1) {
                    $content .= "('".$rbthememenu_block_lang['id_block']."','".$rbthememenu_block_lang['id_lang']."','".$rbthememenu_block_lang['title']."','".$rbthememenu_block_lang['content']."','".$rbthememenu_block_lang['title_link']."','".$rbthememenu_block_lang['image_link']."')";
                } else {
                    $content .= ",('".$rbthememenu_block_lang['id_block']."','".$rbthememenu_block_lang['id_lang']."','".$rbthememenu_block_lang['title']."','".$rbthememenu_block_lang['content']."','".$rbthememenu_block_lang['title_link']."','".$rbthememenu_block_lang['image_link']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $content .= 'foreach ($sql as $query) {' . "\n";
        $content .= '   if (Db::getInstance()->execute($query) == false) {' . "\n";
        $content .= '       return false;' . "\n";
        $content .= '   }' . "\n";
        $content .= '}' . "\n";

        $fp = fopen(_PS_MODULE_DIR_ . 'rbthememenu/sql/same.php', 'w');
        fwrite($fp, $content);
        fclose($fp);
    }

    public function exportDataRbthemedream()
    {
        $content = "<?php \n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_HEADER", "'.Configuration::get('RBTHEMEDREAM_HEADER').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_FOOTER", "'.Configuration::get('RBTHEMEDREAM_FOOTER').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_SHOW_MAP", "'.Configuration::get('RBTHEMEDREAM_SHOW_MAP').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_MAP_LATITUDE", "'.Configuration::get('RBTHEMEDREAM_MAP_LATITUDE').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_MAP_LONGITUDE", "'.Configuration::get('RBTHEMEDREAM_MAP_LONGITUDE').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_COMPANY_NAME", "'.Configuration::get('RBTHEMEDREAM_COMPANY_NAME').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_ADDRESS", "'.Configuration::get('RBTHEMEDREAM_ADDRESS').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_PHONE_NUMBER", "'.Configuration::get('RBTHEMEDREAM_PHONE_NUMBER').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_EMAIL", "'.Configuration::get('RBTHEMEDREAM_EMAIL').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_CONTENT_PAGE", "'.Configuration::get('RBTHEMEDREAM_CONTENT_PAGE').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_FACEBOOK", "'.Configuration::get('RBTHEMEDREAM_FACEBOOK').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_TWITTER", "'.Configuration::get('RBTHEMEDREAM_TWITTER').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_INSTAGRAM", "'.Configuration::get('RBTHEMEDREAM_INSTAGRAM').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_PINTEREST", "'.Configuration::get('RBTHEMEDREAM_PINTEREST').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_YOUTUBE", "'.Configuration::get('RBTHEMEDREAM_YOUTUBE').'")';
        $content .= ";\n";
        $content .= 'Configuration::updateValue("RBTHEMEDREAM_VIMEO", "'.Configuration::get('RBTHEMEDREAM_VIMEO').'")';
        $content .= ";\n";
        
        $content .= '$sql = array();' . "\n";

        $rbthemedream_homes = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthemedream_home`');

        if (!empty($rbthemedream_homes)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthemedream_home(`id_rbthemedream_home`, `active`) VALUES ';
            $count = 1;

            foreach ($rbthemedream_homes as $rbthemedream_home) {
                if ($count == 1) {
                    $content .= "('".$rbthemedream_home['id_rbthemedream_home']."','".$rbthemedream_home['active']."')";
                } else {
                    $content .= ",('".$rbthemedream_home['id_rbthemedream_home']."','".$rbthemedream_home['active']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbthemedream_home_langs = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthemedream_home_lang`');

        if (!empty($rbthemedream_home_langs)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthemedream_home_lang(`id_rbthemedream_home`, `id_shop`, `id_lang`, `name`, `data`) VALUES ';
            $count = 1;

            foreach ($rbthemedream_home_langs as $rbthemedream_home_lang) {
                if ($count == 1) {
                    $content .= "('".$rbthemedream_home_lang['id_rbthemedream_home']."','".$rbthemedream_home_lang['id_shop']."','".$rbthemedream_home_lang['id_lang']."','".$rbthemedream_home_lang['name']."','".str_replace('"', '\"', $rbthemedream_home_lang['data'])."')";
                } else {
                    $content .= ",('".$rbthemedream_home_lang['id_rbthemedream_home']."','".$rbthemedream_home_lang['id_shop']."','".$rbthemedream_home_lang['id_lang']."','".$rbthemedream_home_lang['name']."','".str_replace('"', '\"', $rbthemedream_home_lang['data'])."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbthemedream_home_shops = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthemedream_home_shop`');

        if (!empty($rbthemedream_home_shops)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthemedream_home_shop(`id_rbthemedream_home`,`id_shop`,`active`) VALUES ';
            $count = 1;

            foreach ($rbthemedream_home_shops as $rbthemedream_home_shop) {
                if ($count == 1) {
                    $content .= "('".$rbthemedream_home_shop['id_rbthemedream_home']."','".$rbthemedream_home_shop['id_shop']."','".$rbthemedream_home_shop['active']."')";
                } else {
                    $content .= ",('".$rbthemedream_home_shop['id_rbthemedream_home']."','".$rbthemedream_home_shop['id_shop']."','".$rbthemedream_home_shop['active']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbthemedream_links = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthemedream_link`');

        if (!empty($rbthemedream_links)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthemedream_link(`id_rbthemedream_link`,`id_hook`,`position`,`data`) VALUES ';
            $count = 1;

            foreach ($rbthemedream_links as $rbthemedream_link) {
                if ($count == 1) {
                    $content .= "('".$rbthemedream_link['id_rbthemedream_link']."','".$rbthemedream_link['id_hook']."','".$rbthemedream_link['position']."','".$rbthemedream_link['data']."')";
                } else {
                    $content .= ",('".$rbthemedream_link['id_rbthemedream_link']."','".$rbthemedream_link['id_hook']."','".$rbthemedream_link['position']."','".$rbthemedream_link['data']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbthemedream_link_langs = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthemedream_link_lang`');

        if (!empty($rbthemedream_link_langs)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthemedream_link_lang(`id_rbthemedream_link`,`id_lang`,`name`) VALUES ';
            $count = 1;

            foreach ($rbthemedream_link_langs as $rbthemedream_link_lang) {
                if ($count == 1) {
                    $content .= "('".$rbthemedream_link_lang['id_rbthemedream_link']."','".$rbthemedream_link_lang['id_lang']."','".$rbthemedream_link_lang['name']."')";
                } else {
                    $content .= ",('".$rbthemedream_link_lang['id_rbthemedream_link']."','".$rbthemedream_link_lang['id_lang']."','".$rbthemedream_link_lang['name']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $rbthemedream_link_shops = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'rbthemedream_link_shop`');

        if (!empty($rbthemedream_link_shops)) {
            $content .= '$sql[] = "INSERT INTO "._DB_PREFIX_."rbthemedream_link_shop(`id_rbthemedream_link`,`id_shop`) VALUES ';
            $count = 1;

            foreach ($rbthemedream_link_shops as $rbthemedream_link_shop) {
                if ($count == 1) {
                    $content .= "('".$rbthemedream_link_shop['id_rbthemedream_link']."','".$rbthemedream_link_shop['id_shop']."')";
                } else {
                    $content .= ",('".$rbthemedream_link_shop['id_rbthemedream_link']."','".$rbthemedream_link_shop['id_shop']."')";
                }

                $count ++;
            }

            $content .= '"';
            $content .= ";\n";
        }

        $content .= 'foreach ($sql as $query) {' . "\n";
        $content .= '   if (Db::getInstance()->execute($query) == false) {' . "\n";
        $content .= '       return false;' . "\n";
        $content .= '   }' . "\n";
        $content .= '}' . "\n";

        $fp = fopen(_PS_MODULE_DIR_ . $this->name . '/sql/same.php', 'w');
        fwrite($fp, $content);
        fclose($fp);
    }

    public function hookHeader()
    {
        /***************** ADD JS *******************/
        $this->context->controller->addJS(_MODULE_DIR_.'rbthemedream/views/js/plugin/rb-instagram.js');
        $this->context->controller->addJS(_MODULE_DIR_.'rbthemedream/views/js/plugin/rb-clock.js');
        $this->context->controller->addJS($this->_path.'views/js/plugin/rb-slick.js');
        $this->context->controller->addJS($this->_path.'views/js/rb-front.js');

        /***************** ADD CSS *******************/
        $this->context->controller->addCSS($this->_path.'views/css/front.css');
        $this->context->controller->addCSS($this->_path.'views/css/slick.css');
        $this->context->controller->addCSS($this->_path.'views/css/header.css');
        $this->context->controller->addCSS($this->_path.'views/css/footer.css');

        Media::addJsDef(array(
            'rbFrontendConfig' => array(
                'isEditMode' => '',
                'stretchedSectionContainer' =>'',
                'is_rtl' => '',
                'rb_day' => $this->l('Days'),
            ),
            'rb_days' => $this->l('Days'),
            'rb_hours' => $this->l('Hours'),
            'rb_minutes' => $this->l('Minutes'),
            'rb_seconds' => $this->l('Seconds'),
        ));

        $obj_home = new RbthemedreamHome();
        $layout = 0;
        $id = $obj_home->getHomeActive();

        if (Tools::getValue('controller') == 'index') {
            if (Configuration::get('RBTHEMEDREAM_HOME_' . (int)$id) == 1) {
                $layout = 1;
            }
        } else if (Tools::getIsset('module') && Tools::getValue('module') == 'rbthemedream') {
            $id = Tools::getValue('id');

            if (Configuration::get('RBTHEMEDREAM_HOME_' . (int)$id) == 1) {
                $layout = 1;
            }
        } else {
            if (Configuration::get('RBTHEMEDREAM_PAGE_' . (int)$id) == 1) {
                $layout = 1;
            }
        }

        $this->context->smarty->assign(array(
            'rb_layout' => $layout,
        ));
    }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookdisplayHome()
    {
        $hookName = 'displayHome';

        $templateFile = 'rb_content.tpl';
        $cacheId = $hookName;

        if (preg_match('/^displayHome\d*$/', $hookName)) {
            $cacheId = 'rbthemedream|'.$hookName;
        }

        if (!$this->isCached('module:' . $this->name . '/views/templates/hook/' .$templateFile, $this->getCacheId($cacheId))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, array()));
        }

        return $this->fetch('module:' . $this->name . '/views/templates/hook/' . $templateFile, $this->getCacheId($cacheId));
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        if ($hookName == null && isset($configuration['hook'])) {
            $hookName = $configuration['hook'];
        }

        $content = '';
        $options = array();

        if (preg_match('/^displayHome\d*$/', $hookName)) {
            $obj = new RbthemedreamHome();
            $id_rbthemedream_home = (int)$obj->getHomeActive();
            $home = new RbthemedreamHome($id_rbthemedream_home, $this->id_lang);

            $this->getViewHome($home);

            if ($home->data != '') {
                $front = new RbFront(Tools::jsonDecode($home->data, true));
                $content = $front->applyBuilderInContent();
            }
        }

        return array(
            'content' => $content,
            'options' => $options,
        );
    }

    public function hookdisplayRbSocial()
    {
        $var = array();

        $var['facebook'] = Configuration::get('RBTHEMEDREAM_FACEBOOK');
        $var['twitter'] = Configuration::get('RBTHEMEDREAM_TWITTER');
        $var['instagram'] = Configuration::get('RBTHEMEDREAM_INSTAGRAM');
        $var['pinterest'] = Configuration::get('RBTHEMEDREAM_PINTEREST');
        $var['youtube'] = Configuration::get('RBTHEMEDREAM_YOUTUBE');
        $var['vimeo'] = Configuration::get('RBTHEMEDREAM_VIMEO');

        $this->smarty->assign($var);

        return $this->display(__FILE__, 'views/templates/hook/rb-social.tpl');
    }

    public function hookdisplayRbFooter()
    {
        return $this->renderByHookName('displayRbFooter');
    }

    public function renderByHookName($hook_name)
    {
        $id_hook = Hook::getIdByName($hook_name);
        $obj = new RbthemedreamLink();
        $links = $obj->getLinkByHook($id_hook);
        $rb_links = array();

        foreach ($links as $link) {
            $rb_links = $obj->present($link);
        }

        $var = array(
            'rb_links' => $rb_links,
        );

        if ($hook_name == 'displayRbFooter') {
            $var['type'] = 'contact';
            $var['company'] = Configuration::get('RBTHEMEDREAM_COMPANY_NAME');
            $var['address'] = Configuration::get('RBTHEMEDREAM_ADDRESS');
            $var['phone'] = Configuration::get('RBTHEMEDREAM_PHONE_NUMBER');
            $var['email'] = Configuration::get('RBTHEMEDREAM_EMAIL');
            $var['content'] = Configuration::get('RBTHEMEDREAM_CONTENT_PAGE');
            $var['map'] = Configuration::get('RBTHEMEDREAM_SHOW_MAP');
            $var['latitude'] = Configuration::get('RBTHEMEDREAM_MAP_LATITUDE');
            $var['longitude'] = Configuration::get('RBTHEMEDREAM_MAP_LONGITUDE');

            /* Social Link */

            $var['facebook'] = Configuration::get('RBTHEMEDREAM_FACEBOOK');
            $var['twitter'] = Configuration::get('RBTHEMEDREAM_TWITTER');
            $var['instagram'] = Configuration::get('RBTHEMEDREAM_INSTAGRAM');
            $var['pinterest'] = Configuration::get('RBTHEMEDREAM_PINTEREST');
            $var['youtube'] = Configuration::get('RBTHEMEDREAM_YOUTUBE');
            $var['vimeo'] = Configuration::get('RBTHEMEDREAM_VIMEO');
        }

        $this->smarty->assign($var);

        return $this->display(__FILE__, 'views/templates/hook/rb_link.tpl');
    }

    public function hookmoduleRoutes()
    {
        $routes = array();

        Configuration::deleteByName('PS_ROUTE_module-rbthemedream-live');

        $routes['module-rbthemedream-live'] = array(
            'controller' => 'live',
            'rule' => '{rewrite}-{id}.html',
            'keywords' => array(
                'id' => array('regexp' => '[0-9]+', 'param' => 'id'),
                'rewrite' => array('regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'rewrite'),
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'rbthemedream'
            )
        );

        $routes['module-rbthemedream-view'] = array(
            'controller' => 'view',
            'rule' => 'view',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'rbthemedream'
            )
        );

        return $routes;
    }

    public function getViewHome($home, $params1 = array())
    {
        $params = array(
            'id' => $home->id_rbthemedream_home,
            'rewrite' => 'home',
        );

        $params = array_merge($params, $params1);
        
        return $this->getModuleLink('module-rbthemedream-home', 'home', $params);
    }

    public function getModuleLink(
        $route_id,
        $controller,
        array $params = array(),
        $ssl = null,
        $id_lang = null,
        $id_shop = null
    ) {
        return $this->getLink($route_id, $controller, $params, $ssl, $id_lang, $id_shop);
    }

    public function getLink(
        $route_id,
        $controller = 'default',
        array $params = array(),
        $ssl = null,
        $id_lang = null,
        $id_shop = null
    ) {
        unset($controller);

        if (!$id_lang) {
            $id_lang = Context::getContext()->language->id;
        }

        $link = new Link();
        $url = $link->getBaseLink($id_shop, $ssl). $this->getLangLink($id_lang, null, Context::getContext()->shop->id);

        return $url.Dispatcher::getInstance()->createUrl(
            $route_id,
            $id_lang,
            $params,
            Configuration::get('PS_REWRITING_SETTINGS'),
            '',
            $id_shop
        );
    }

    public function getLangLink($idLang = null, Context $context = null, $idShop = null)
    {
        static $psRewritingSettings = null;
        if ($psRewritingSettings === null) {
            $psRewritingSettings = (int) Configuration::get('PS_REWRITING_SETTINGS', null, null, $idShop);
        }

        if (!$context) {
            $context = Context::getContext();
        }

        if ((!Configuration::get('PS_REWRITING_SETTINGS') &&
            in_array($idShop, array($context->shop->id,  null))) ||
            !Language::isMultiLanguageActivated($idShop) ||
            !$psRewritingSettings
        ) {
            return '';
        }

        if (!$idLang) {
            $idLang = $context->language->id;
        }

        return Language::getIsoById($idLang) . '/';
    }
}
