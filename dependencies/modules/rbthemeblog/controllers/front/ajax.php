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

require_once _PS_MODULE_DIR_ . 'rbthemeblog/Rbthemeblog.php';

class RbthemeblogAjaxModuleFrontController extends ModuleFrontController
{
    public $product;

    public function init()
    {
        parent::init();
    }

    public function postProcess()
    {
        if (Module::isEnabled('rbthemeblog') &&
        	(Tools::getValue('action') == 'addRating' ||
        	Tools::getValue('action') == 'removeRating') &&
        	Tools::getValue('secure_key') == $this->module->secure_key
        ) {
            parent::postProcess();
        } else {
            die('Access denied');
        }
    }

    public function displayAjaxAddRating()
    {
        $id_rbblog_post = Tools::getValue('id_rbblog_post');
        $reply = RbBlogPost::changeRating('up', (int) $id_rbblog_post);
        $message = $reply[0]['likes'];


        $this->ajaxDie(
            Tool::jsonEncode(
                array(
                    'hasError' => false,
                    'status' => 'success',
                    'message' => $message
                )
            )
        );
    }

    public function displayAjaxRemoveRating()
    {
        $id_rbblog_post = Tools::getValue('id_rbblog_post');
        $reply = RbBlogPost::changeRating('down', (int) $id_rbblog_post);
        $message = $reply[0]['likes'];

        $this->ajaxDie(
            Tools::jsonEncode(
                array(
                    'hasError' => false,
                    'status' => 'success',
                    'message' => $message
                )
            )
        );
    }
}
