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

require_once _PS_MODULE_DIR_ . 'rbthemeblog/rbthemeblog.php';

class AdminRbthemeblogCommentController extends ModuleAdminController
{
	public function __construct()
    {
        $this->table = 'rbblog_comment';
        $this->className = 'RbBlogComment';
        $this->bootstrap = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        parent::__construct();

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            ),
            'enableSelection' => array('text' => $this->l('Enable selection')),
            'disableSelection' => array('text' => $this->l('Disable selection'))
        );

        $this->_select = 'sbpl.title AS `post_title`';

        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'rbblog_post_lang` sbpl
        ON (sbpl.`id_rbblog_post` = a.`id_rbblog_post`
        AND sbpl.`id_lang` = '.(int)Context::getContext()->language->id.')';

        $this->fields_list = array(
            'id_rbblog_comment' => array(
                'title' => $this->l('ID'),
                'type' => 'int',
                'align' => 'center',
                'width' => 25
            ),
            'id_rbblog_post' => array(
                'title' => $this->l('Post ID'),
                'type' => 'int',
                'align' => 'center',
                'width' => 25
            ),
            'post_title' => array(
                'title' => $this->l('Comment for'),
                'width' => 'auto'
            ),
            'name' => array(
                'title' => $this->l('Name'),
            ),
            'email' => array(
                'title' => $this->l('E-mail'),
            ),
            'comment' => array(
                'title' => $this->l('Comment'),
                'width' => 'auto'
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'width' => 70,
                'active' => 'status',
                'align' => 'center',
                'type' => 'bool'
            )
        );
    }

    public function renderForm()
    {
        $id_lang = $this->context->language->id;
        $obj = $this->loadObject(true);
       
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Comment'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_rbblog_post',
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'id_customer',
                    'label' => $this->l('Customer'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'id_rbblog_post',
                    'label' => $this->l('Post ID'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'name',
                    'label' => $this->l('Name'),
                    'required' => false,
                    'lang' => false
                ),
                array(
                    'type' => 'text',
                    'name' => 'email',
                    'label' => $this->l('E-mail'),
                    'required' => false,
                    'lang' => false
                ),
                array(
                    'type' => 'text',
                    'name' => 'ip',
                    'label' => $this->l('IP Address'),
                    'required' => false,
                    'lang' => false
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Comment'),
                    'name' => 'comment',
                    'cols' => 75,
                    'rows' => 7,
                    'required' => false,
                    'lang' => false
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Displayed'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'savePostComment'
            )
        );

        $this->multiple_fieldsets = true;
        
        $rbBlogPost = new RbBlogPost($obj->id_rbblog_post, $id_lang);
        
        $this->tpl_form_vars = array(
            'customerLink' => $this->context->link->getAdminLink('AdminCustomers'),
            'blogPostLink' => $this->context->link->getAdminLink(
            	'AdminRbthemeblogPost'
            ),
            'blogPostName' => $rbBlogPost->meta_title
        );
        
        return parent::renderForm();
    }
}
