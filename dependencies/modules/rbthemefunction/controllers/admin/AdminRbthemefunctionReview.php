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

require_once _PS_MODULE_DIR_ . 'rbthemefunction/classes/RbthemefunctionReview.php';

class AdminRbthemefunctionReviewController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->table = 'rbthemefunction_review';
        $this->identifier = 'id_rbthemefunction_review';
        $this->className = 'RbthemefunctionReview';
        $this->obj_review = new RbthemefunctionReview();
        parent::__construct();
    }

    public function renderList()
    {
        $this->toolbar_title = $this->l('Review Criteria');
        $return = null;
        $return .= $this->renderModerateLists();
        $return .= parent::renderList();
        $return .= $this->renderReviewsList();
        return $return;
    }

    public function initProcess()
    {
        if (Tools::getIsset('action') && Tools::getValue('action') == 'getNewReview') {
            $this->ajaxProcessgetNewReview();
        }
    }

    public function postProcess()
    {
        if (Tools::isSubmit('deleterbthemefunction_review') && Tools::getValue('id_rbthemefunction_review')) {
            $id_rbthemefunction_review = (int) Tools::getValue('id_rbthemefunction_review');
            $review = new RbthemefunctionReview($id_rbthemefunction_review);
            $review->delete();
            $this->redirect_after = self::$currentIndex.'&token='.$this->token;
        }

        if (Tools::isSubmit('approveReview') && Tools::getValue('id_rbthemefunction_review')) {
            $id_rbthemefunction_review = (int) Tools::getValue('id_rbthemefunction_review');
            $review = new RbthemefunctionReview($id_rbthemefunction_review);
            $review->validate();
            $this->redirect_after = self::$currentIndex.'&token='.$this->token;
        }
    }

    public function renderReviewsList()
    {
        $reviews = $this->obj_review->getByValidate(1, false);
        $fields_list = $this->getStandardFieldList();
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = array('delete');
        $helper->show_toolbar = false;
        $helper->module = $this->module;
        $helper->listTotal = count($reviews);
        $helper->identifier = 'id_rbthemefunction_review';
        $helper->title = $this->l('Approved Reviews');
        $helper->table = 'rbthemefunction_review';
        $helper->token = $this->token;
        $helper->currentIndex = self::$currentIndex;
        $helper->no_link = true;

        return $helper->generateList($reviews, $fields_list);
    }

    public function displayApproveLink($token, $id, $name = null)
    {
        unset($token);
        unset($name);
        $template = $this->createTemplate('list-action-approve.tpl');

        $template->assign(array(
            'href' => $this->context->link->getAdminLink('AdminRbthemefunctionReview').'&approveReview&id_rbthemefunction_review='.$id,
            'action' => $this->l('Approve'),
        ));
        
        return $template->fetch();
    }

    public function renderModerateLists()
    {
        $return = null;
        $reviews = $this->obj_review->getByValidate(0, false);

        if (count($reviews) > 0) {
            $fields_list = $this->getStandardFieldList();
            $actions = array('approve', 'delete');
            $helper = new HelperList();
            $helper->shopLinkType = '';
            $helper->simple_header = true;
            $helper->actions = $actions;
            $helper->show_toolbar = false;
            $helper->module = $this->module;
            $helper->listTotal = count($reviews);
            $helper->identifier = 'id_rbthemefunction_review';
            $helper->title = $this->l('Reviews waiting for approval');
            $helper->table = 'rbthemefunction_review';
            $helper->token = $this->token;
            $helper->currentIndex = self::$currentIndex;
            $helper->no_link = true;
            $return .= $helper->generateList($reviews, $fields_list);
        }

        return $return;
    }

    public function getStandardFieldList()
    {
        return array(
            'id_rbthemefunction_review' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
            ),
            'title' => array(
                'title' => $this->l('Review title'),
                'type' => 'text',
            ),
            'content' => array(
                'title' => $this->l('Review'),
                'type' => 'text',
            ),
            'grade' => array(
                'title' => $this->l('Rating'),
                'type' => 'text',
                'suffix' => '/5',
            ),
            'customer_name' => array(
                'title' => $this->l('Author'),
                'type' => 'text',
            ),
            'name' => array(
                'title' => $this->l('Product'),
                'type' => 'text',
            ),
            'date_add' => array(
                'title' => $this->l('Time Add'),
                'type' => 'date-time',
            ),
        );
    }

    public function ajaxProcessgetNewReview()
    {
        $token = Tools::getValue('token');

        if ($token) {
            $reviews = $this->obj_review->getByValidate(0, false);
            die(Tools::jsonEncode(array(
                'success' => 1,
                'total_review' => count($reviews),
            )));
        } else {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans(
                    'No Tokens Found',
                    array(),
                    'Shop.Theme.Catalog'
                )
            )));
        }
    }
}
