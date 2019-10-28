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
require_once(_PS_MODULE_DIR_.'rbthemedream/lib/rb-front.php');

class RbthemedreamliveModuleFrontController extends ModuleFrontController
{
	public function __construct()
    {
    	$this->context = Context::getContext();
        $this->id_lang = $this->context->language->id;
        $this->id_shop = $this->context->shop->id;

        parent::__construct();
    }

    public function setMedia()
    {
        parent::setMedia();
    }

    public function initContent()
    {
    	$content = ''; 

    	if (Tools::getIsset('controller') &&
    		Tools::getValue('controller') == 'live' &&
    		Tools::getValue('id') != ''
    	) {
    		$id_rbthemedream_home = Tools::getValue('id');
    		$home = new RbthemedreamHome($id_rbthemedream_home, $this->id_lang);
            if ($home->data != '') {
                $front = new RbFront(Tools::jsonDecode($home->data, true));
                $content = $front->applyBuilderInContent();
            }
    	}

    	$this->context->smarty->assign(array(
    		'content' => $content,
        ));

    	$this->setTemplate('module:rbthemedream/views/templates/front/view.tpl');
    	parent::initContent();
    }
}