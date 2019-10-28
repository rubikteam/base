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

require_once(_PS_MODULE_DIR_.'rbthemedream/classes/RbthemedreamHome.php');

class AdminRbthemedreamHomeController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->table = 'rbthemedream_home';
        $this->identifier = 'id_rbthemedream_home';
        $this->className = 'RbthemedreamHome';
        $this->lang = true;
        $this->bootstrap = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        parent::__construct();

        $this->fields_list = array(
            'id_rbthemedream_home' => array('title' => $this->l('ID'), 'width' => 100),
            'name' => array('title' => $this->l('Name'), 'width' => 100),
            'active' => array(
                'title' => $this->l('Active'),
                'width' => 100,
                'type' => 'bool',
                'active' => 'status'
            ),
        );
    }

    public function renderForm()
    {
        if (Tools::getIsset('id_rbthemedream_home')) {
            $this->fields_form = array(
                'legend' => array(
                    'title' => $this->l('Edit Home'),
                    'icon' => 'icon-folder-close'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Name'),
                        'name' => 'name',
                        'required' => true,
                        'lang' => true
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'html_data',
                        'html_content' => '<a class="rb-live-edit" href="'.$this->context->link->getAdminLink('AdminRbthemedreamLive', true).'&page=home&id_rbthemedream_home='.Tools::getValue('id_rbthemedream_home').'">'.$this->l('Edit Home').'</a>'
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    'save-and-stay' => array(
                        'title' => $this->l('Save and Stay'),
                        'name' => 'submitAdd'.$this->table.'AndStay',
                        'type' => 'submit',
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save'
                    )
                )
            );
        } else {
            $this->fields_form = array(
                'legend' => array(
                    'title' => $this->l('Edit Home'),
                    'icon' => 'icon-folder-close'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Name'),
                        'name' => 'name',
                        'required' => true,
                        'lang' => true
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    'save-and-stay' => array(
                        'title' => $this->l('Save and Stay'),
                        'name' => 'submitAdd'.$this->table.'AndStay',
                        'type' => 'submit',
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save'
                    )
                )
            );
        }

        return parent::renderForm();
    }


    public function processStatus()
    {
        if (Validate::isLoadedObject($object = $this->loadObject())) {
            if ($object->active == 0) {
                Db::getInstance()->execute(
                    'UPDATE `'._DB_PREFIX_.'rbthemedream_home_shop`
                    SET `active`= "0"
                    WHERE `id_rbthemedream_home`
                    NOT IN ('.(int)$object->id_rbthemedream_home.')'
                );

                Db::getInstance()->execute(
                    'UPDATE `'._DB_PREFIX_.'rbthemedream_home`
                    SET `active`= "0"
                    WHERE `id_rbthemedream_home`
                    NOT IN ('.(int)$object->id_rbthemedream_home.')'
                );

                $object->toggleStatus();
            }
        }

        Tools::redirectAdmin($this->context->link->getAdminLink('AdminRbthemedreamHome', true));
    }

    public function processAdd()
    {
        Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'rbthemedream_home_shop`
            SET `active`= "0"'
        );

        Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'rbthemedream_home`
            SET `active`= "0"'
        );

        $obj = new RbthemedreamHome();
        parent::validateRules();

        if (count($this->errors) <= 0) {
            $this->copyFromPost($obj, 'home');
            $obj->active = 1;

            if (!$obj->add()) {
                $this->errors[] = Tools::displayError('An error occurred while creating an object.')
                .' <b>'.$this->table.' ('.Db::getInstance()->getMsgError().')</b>';
            }

            if (Tools::getValue('save_and_add') === '' || ToolsCore::getValue('save_and_add')) {
                $this->redirect_after = self::$currentIndex.'&conf=3&add'.$this->table.'&token='.$this->token;
            }
        }

        $this->errors = array_unique($this->errors);

        if (!empty($this->errors)) {
             $this->display = 'edit';
            return false;
        }

        $this->display = 'list';

        if (empty($this->errors)) {
                $this->confirmations[] = $this->_conf[3];
        }
    }
}
