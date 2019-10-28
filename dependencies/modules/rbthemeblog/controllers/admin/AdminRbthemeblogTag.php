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

class AdminRbthemeblogTagController extends ModuleAdminController
{
	public function __construct()
    {
        $this->table = 'rbblog_tag';
        $this->className = 'RbBlogTag';
        $this->bootstrap = true;

        parent::__construct();

        $this->fields_list = array(
            'id_rbblog_tag' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 25,
            ),
            'lang' => array(
                'title' => $this->l('Language'),
                'filter_key' => 'l!name',
                'width' => 100,
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'width' => 'auto',
                'filter_key' => 'a!name'
            ),
            'posts' => array(
                'title' => $this->l('Posts:'),
                'align' => 'center',
                'width' => 50,
                'havingFilter' => true
            )
        );

        $this->bulk_actions = array(
        	'delete' => array(
        		'text' => $this->l('Delete selected'),
        		'confirm' => $this->l('Delete selected items?')
        	)
        );
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->_select = 'l.name as lang, COUNT(pt.id_rbblog_post) as posts';

        $this->_join = ' LEFT JOIN `'._DB_PREFIX_.'rbblog_post_tag` pt
        ON (a.`id_rbblog_tag` = pt.`id_rbblog_tag`)
        LEFT JOIN `'._DB_PREFIX_.'lang` l
        ON (l.`id_lang` = a.`id_lang`)';

        $this->_group = 'GROUP BY a.name, a.id_lang';

        return parent::renderList();
    }

    public function postProcess()
    {
        if (Tools::getValue('submitAdd'.$this->table)) {
            if (($id = (int)Tools::getValue($this->identifier)) &&
            	($obj = new $this->className($id)) &&
            	Validate::isLoadedObject($obj)
            ) {
                $previousPosts = $obj->getPosts();
                $removedPosts = array();

                foreach ($previousPosts as $post) {
                    if (!in_array($post['id_rbblog_post'], $_POST['posts'])) {
                        $removedPosts[] = $post['id_rbblog_post'];
                    }
                }

                $obj->setPosts($_POST['posts']);
            }
        }

        return parent::postProcess();
    }


    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Tag')
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Name:'),
                    'name' => 'name',
                    'required' => true
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Language:'),
                    'name' => 'id_lang',
                    'required' => true,
                    'options' => array(
                        'query' => Language::getLanguages(false),
                        'id' => 'id_lang',
                        'name' => 'name'
                    )
                ),
            ),
            'selects' => array(
                'posts' => $obj->getPosts(true),
                'posts_unselected' => $obj->getPosts(false)
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )
        );

        return parent::renderForm();
    }
}
