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

require_once(_PS_MODULE_DIR_.'rbthemedream/classes/RbthemedreamLink.php');
require_once(_PS_MODULE_DIR_.'rbthemedream/classes/RbthemedreamHome.php');

class AdminRbthemedreamLinkController extends ModuleAdminController
{
	public function __construct()
    {
    	$this->context = Context::getContext();
    	$this->bootstrap = true;
    	$this->display = 'view';
    	$this->lang = true;
    	$this->name = 'AdminRbthemedreamLink';
    	$this->className = 'RbthemedreamLink';
        $this->table = 'rbthemedream_link';

    	parent::__construct();
    }

    public function init()
    {
        if (Tools::isSubmit('edit' . $this->className)) {
            $this->display = 'edit';
        } elseif (Tools::isSubmit('addRbthemedreamLink')) {
            $this->display = 'add';
        }

        parent::init();
    }

    public function setMedia($isNewTheme = false)
    {
    	parent::setMedia($isNewTheme);

    	$this->addJqueryPlugin('tablednd');

    	$this->addCSS(array(
    		_MODULE_DIR_.'rbthemedream/views/css/link.css',
    	));

    	$this->addJS(_MODULE_DIR_.'rbthemedream/views/js/link.js');
    	
    	$this->addJS(_PS_JS_DIR_ . 'admin/dnd.js');
   	}

    public function postProcess()
    {
        if (Tools::isSubmit('submitRbthemedreamLink')) {
        	$this->addNameArrayToPost();

            if (!$this->processSave()) {
                return false;
            }

            $hook_name = Hook::getNameById(Tools::getValue('id_hook'));

            if (!Hook::isModuleRegisteredOnHook($this->module, $hook_name, $this->context->shop->id)) {
                Hook::registerHook($this->module, $hook_name);
            }

            Tools::redirectAdmin($this->context->link->getAdminLink('AdminRbthemedreamLink'));

        } elseif (Tools::isSubmit('delete' . $this->className)) {
            $obj_link = new RbthemedreamLink(Tools::getValue('id_rbthemedream_link'));
            $obj_link->delete();

            if ($obj_link->getCountByIdHook($obj_link->id_hook)) {
                Hook::unregisterHook($this->module, Hook::getNameById((int) $obj_link->id_hook));
            }

            Tools::redirectAdmin($this->context->link->getAdminLink('AdminRbthemedreamLink'));
        }

        return parent::postProcess();
    }

    public function renderView()
    {
        $title = $this->l('Link List');
        $obj = new RbthemedreamLink();

        $this->fields_form[]['form'] = array(
            'legend' => array(
                'title' => $title,
                'icon' => 'icon-list-alt',
            ),
            'input' => array(
                array(
                    'type' => 'link_block',
                    'label' => $this->l(''),
                    'name' => 'link_block',
                    'values' => $obj->getLinkByHook(),
                ),
            ),
            'buttons' => array(
                'newBlock' => array(
                    'title' => $this->l('Add New Link'),
                    'href' => $this->context->link->getAdminLink($this->name) . '&amp;addRbthemedreamLink',
                    'class' => 'pull-right',
                    'icon' => 'process-icon-new',
                ),
            ),
        );

        $this->getLanguages();

        $helper = $this->renderFormHelper();
        $helper->submit_action = '';
        $helper->title = $title;

        $helper->fields_value = $this->fields_value;

        return $helper->generateForm($this->fields_form);
    }

    public function renderForm()
    {
    	$obj = new RbthemedreamLink((int)Tools::getValue('id_rbthemedream_link'));

    	$this->fields_form[0]['form'] = array(
            'tinymce' => true,
            'legend' => array(
                'title' => isset($obj->id_rbthemedream_link) ? $this->l('Edit Link') : $this->l('New Link'),
                'icon' => isset($obj->id_rbthemedream_link) ? 'icon-edit' : 'icon-plus-square',
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_rbthemedream_link',
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'id_shop',
                    'value' => (int) $this->context->shop->id,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Name Link'),
                    'name' => 'name',
                    'lang' => true,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Hook'),
                    'name' => 'id_hook',
                    'class' => 'input-lg',
                    'options' => array(
                        'query' => $obj->getDisplayHooksForHelper(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'list_page',
                    'label' => '',
                    'name' => 'list_page',
                ),
                array(
                    'type' => 'selected_link',
                    'label' => '',
                    'name' => 'selected_link[]',
                ),
            ),
            'buttons' => array(
                'cancelBlock' => array(
                    'title' => $this->l('Cancel'),
                    'href' => (Tools::safeOutput(Tools::getValue('back', false)))
                    ?: $this->context->link->getAdminLink('AdminRbthemedreamLink'),
                    'icon' => 'process-icon-cancel',
                ),
            ),
            'submit' => array(
                'name' => 'submitRbthemedreamLink',
                'title' => $this->l('Save'),
            ),
        );


        if ($id_hook = Tools::getValue('id_hook')) {
            $obj->id_hook = (int) $id_hook;
        }

        if (Tools::getValue('name')) {
            $obj->name = Tools::getValue('name');
        }

        $obj->id_shop = (int) $this->context->shop->id;

    	$helper = $this->renderFormHelper();

        if (isset($obj->id_rbthemedream_link)) {
            $helper->currentIndex = AdminController::$currentIndex . '&id_rbthemedream_link=' . $obj->id_rbthemedream_link;
            $helper->submit_action = 'submitEdit' . $this->className;
        } else {
            $helper->submit_action = 'submitAdd' . $this->className;
        }

        $data = array();

    	if (empty($obj->data)) {
    		$data = $obj->data;
    	} else {
    		$data = Tools::jsonDecode($obj->data, true);
    	}



        $helper->fields_value = (array) $obj;

        $helper->tpl_vars = array(
            'category_tree' => $obj->getCategories(),
            'cms_tree' => $obj->getCmsPages(),
            'static_pages' => $obj->getStaticPages(),
            'selected_links' => $obj->makeLinks($data),
        );

        return $helper->generateForm($this->fields_form);
    }

    private function addNameArrayToPost()
    {
        $languages = Language::getLanguages();
        $names = array();

        foreach ($languages as $lang) {
            if ($name = Tools::getValue('name_' . (int) $lang['id_lang'])) {
                $names[(int) $lang['id_lang']] = $name;
            }
        }

        $obj = new RbthemedreamLink();
        $_POST['name'] = $names;
        $_POST['position'] = $obj->getNextPosition(Tools::getValue('id_hook'));
    }

    public function renderFormHelper()
    {
        $helper = new HelperForm();
        $helper->module = $this->module;
        $helper->override_folder = 'rbthemedream_live/';
        $helper->identifier = $this->className;
        $helper->token = Tools::getAdminTokenLite('AdminRbthemedreamLink');
        $helper->languages = $this->_languages;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminRbthemedreamLink');
        $helper->default_form_language = $this->default_form_language;
        $helper->allow_employee_form_lang = $this->allow_employee_form_lang;
        $helper->toolbar_scroll = true;
        $helper->toolbar_btn = $this->initToolbar();

        return $helper;
    }

    public function ajaxProcessUpdatePositions()
    {
    	$way = (int) (Tools::getValue('way'));

    	die();
    }
}