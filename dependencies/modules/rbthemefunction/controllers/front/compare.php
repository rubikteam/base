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

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

require_once _PS_MODULE_DIR_ . 'rbthemefunction/classes/RbthemefunctionReview.php';

class RbthemefunctioncompareModuleFrontController extends ModuleFrontController
{
	public function __construct()
    {
    	$this->context = Context::getContext();
        $this->id_lang = $this->context->language->id;
        $this->id_shop = $this->context->shop->id;

        parent::__construct();
    }

    public function initContent()
	{
		parent::initContent();
		$action = Tools::getValue('action');

		if (!Tools::isSubmit('ajax'))
			$this->rbDisplayList();
		elseif (!empty($action) && method_exists($this, 'ajaxProcess'.Tools::toCamelCase($action)))
			$this->{'ajaxProcess'.Tools::toCamelCase($action)}();
		else
			die(Tools::jsonEncode(array(
				'error' => $this->trans(
					'Method doesn\'t exist',
					array(),
					'Shop.Theme.Catalog'
				)
			)));
	}

	public function rbDisplayList()
	{
		$errors = array();
		$products = array();
        $arr = array();

        if (isset($this->context->cookie->rb_compare) && $this->context->cookie->rb_compare) {
            $arr = explode(',', $this->context->cookie->rb_compare);
        }

        $arr = array_unique($arr);

        if (count($arr)) {
        	$assembler = new ProductAssembler($this->context);
            $presenterFactory = new ProductPresenterFactory($this->context);
            $presentationSettings = $presenterFactory->getPresentationSettings();

            $presenter = new ProductListingPresenter(
                new ImageRetriever(
                    $this->context->link
                ),
                $this->context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->context->getTranslator()
            );

            $listProducts = array();
            $listFeatures = array();

            foreach($arr as $k => $id) {
                $obj_p = new Product((int)$id);

                if (!Validate::isLoadedObject($obj_p)) {
                    unset($arr[$k]);
                    continue;
                }

                $product = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct(array('id_product' => $id)),
                    $this->context->language
                );

                foreach ($product['features'] as $feature) {
                    $listFeatures[$id][$feature['id_feature']] = $feature['value'];
                }

                $products[] = $product;
            }

            $ordered_features = $this->getFeaturesForCompare($arr, $this->context->language->id);

            $this->context->smarty->assign(array(
                'rb_compare_order_features' => $ordered_features,
                'rb_compare_product_features' => $listFeatures,
                'rb_compare_products' => $products,
                'rb_compare_items' => 255,
            ));
        }

		$this->setTemplate('module:rbthemefunction/views/templates/front/rb-list.tpl');
	}

	public static function getFeaturesForCompare($list_ids_product, $id_lang)
    {
        if (!Feature::isFeatureActive()) {
            return false;
        }

        $ids = '';

        foreach ($list_ids_product as $id) {
            $ids .= (int)$id.',';
        }

        $ids = rtrim($ids, ',');

        if (empty($ids)) {
            return false;
        }

        return Db::getInstance()->executeS('
            SELECT f.*, fl.*
            FROM `'._DB_PREFIX_.'feature` f
            LEFT JOIN `'._DB_PREFIX_.'feature_product` fp
                ON f.`id_feature` = fp.`id_feature`
            LEFT JOIN `'._DB_PREFIX_.'feature_lang` fl
                ON f.`id_feature` = fl.`id_feature`
            WHERE fp.`id_product` IN ('.$ids.')
            AND `id_lang` = '.(int)$id_lang.'
            GROUP BY f.`id_feature`
            ORDER BY f.`position` ASC
        ');
    }

    public function ajaxProcessAddCompareProduct()
    {
        $id_product = (int)Tools::getValue('id_product');

        if (!$id_product) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans('Failed, product ID is empty', array(), 'Shop.Theme.Catalog'))
            ));
        }

        $arr = array();

        if (isset($this->context->cookie->rb_compare) && $this->context->cookie->rb_compare) {
            $arr = explode(',', $this->context->cookie->rb_compare);
        }

        array_unshift($arr, $id_product);
        $arr = array_unique($arr);
        $this->context->cookie->__set('rb_compare', trim(implode(',', $arr), ','));

        die(Tools::jsonEncode(array('success' => 1,
            'action' => 1, 
            'message' => $this->trans('Added to %1%compare list%2%', array('%1%'=>'<a href="'.$this->context->link->getModuleLink('rbthemefunction', 'compare').'" class="rbcompare-link-in-popup">','%2%'=>'</a>'), 'Shop.Theme.Catalog'),
            'total' => count($arr),
            ))
        );
    }

    public function ajaxProcessDeleteCompareProduct()
    {
        $id_product = (int)Tools::getValue('id_product');

        if(!$id_product) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans('Failed, product ID is empty', array(), 'Shop.Theme.Catalog')
            )));
        }

        $arr = array();

        if (isset($this->context->cookie->rb_compare) && $this->context->cookie->rb_compare) {
            $arr = explode(',', $this->context->cookie->rb_compare);
            $arr = array_diff($arr, array($id_product));
            $this->context->cookie->__set('rb_compare', trim(implode(',', $arr), ','));
        }

        die(Tools::jsonEncode(array(
            'success' => 1,
            'action' => 0,
            'message' => $this->trans('Removed from %1%compare list%2%', array('%1%'=>'<a href="'.$this->context->link->getModuleLink('rbthemefunction', 'compare').'" class="rb-link-in-popup">','%2%'=>'</a>'), 'Shop.Theme.Catalog'),
            'total' => count($arr),
        )));
    }

    public function ajaxProcessaddReviewProduct()
    {
        $id_product = Tools::getValue('id_product');

        if (!Validate::isInt($id_product)) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans('Failed, product ID is empty', array(), 'Shop.Theme.Catalog'))
            ));
        }

        $title = Tools::getValue('title');

        if (!$title || !Validate::isGenericName($title)) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans('Title Is Incorrect', array(), 'Shop.Theme.Catalog'))
            ));
        }

        $cmt = Tools::getValue('cmt');

        if (!$cmt || !Validate::isMessage($cmt)) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans('Comment Is Incorrect', array(), 'Shop.Theme.Catalog'))
            ));
        }

        $rate = Tools::getValue('rate');

        if ($rate == '') {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans('You Must Give A Rating', array(), 'Shop.Theme.Catalog'))
            ));
        }

        $obj_product = new Product($id_product);

        if (!$obj_product->id) {
            die(Tools::jsonEncode(array(
                'success' => 0,
                'message' => $this->trans('Product Not Found', array(), 'Shop.Theme.Catalog'))
            ));
        }

        $obj_review = new RbthemefunctionReview();
        $obj_review->content = strip_tags($cmt);
        $obj_review->id_product = (int) $id_product;
        $obj_review->id_customer = (int) $this->context->customer->id;
        $obj_review->id_guest = (int) $this->context->customer->id;
        $obj_review->customer_name = pSQL($this->context->customer->firstname . ' ' . $this->context->customer->lastname);
        $obj_review->title = $title;
        $obj_review->grade = $rate;
        $obj_review->validate = 0;
        $obj_review->date_add = date("Y-m-d");

        if ($obj_review->add()) {
            die(Tools::jsonEncode(array(
                'success' => 1,
                'message' => $this->trans('You Added Review Success', array(), 'Shop.Theme.Catalog'))
            ));
        } else {
            die(Tools::jsonEncode(array(
                'success' => 1,
                'message' => $this->trans('You Can Not Add Review', array(), 'Shop.Theme.Catalog'))
            ));
        }
    }
}
