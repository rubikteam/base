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

require_once(_PS_MODULE_DIR_.'rbthemedream/lib/control/rb-column.php');
require_once(_PS_MODULE_DIR_.'rbthemedream/lib/control/rb-section.php');
require_once(_PS_MODULE_DIR_.'rbthemedream/lib/control/rb-control.php');
require_once(_PS_MODULE_DIR_.'rbthemedream/include.php');

class RbFront
{
	public function __construct($data)
	{
		$this->context = Context::getContext();
		$this->module = new Rbthemedream();

		if (!is_null($data)){
			$this->data = $data;
		}
	}

	public function rbPrintSection($section_data)
	{
		$sections = new RbSection();
		$instance = $sections->getSectionValues($section_data['settings']);
		$html = $sections->beforeRender($instance, $section_data['id'], $section_data);

		foreach ($section_data['elements'] as $column_data) {
			$html .= $this->rbPrintColumn($column_data);
		}
		$html .= $sections->afterRender();

		return $html;
	}

	public function rbPrintColumn($column_data)
	{
		$colums = new RbColumn();
		$instance = $colums->getColumnValues($column_data['settings']);
		$html = $colums->beforeRender($instance, $column_data['id'], $column_data);

		foreach ($column_data['elements'] as $widget_data) {
			if ($widget_data['elType'] === 'section') {
				$html .= $this->rbPrintSection($widget_data);
			} else {
				$html .= $this->rbPrintWidget($widget_data);
			}
		}

		$html .= $colums->afterRender();

		return $html;
	}

	public function rbPrintWidget($widget_data)
	{
		$name_class = 'Rb' . Tools::ucwords($widget_data['widgetType']);
		$name_class = str_replace('-', '', $name_class);

		$widget_obj = '';

		if ($name_class == 'RbGoogle_Maps') {
			$widget_obj = new RbMap();
		} else if ($name_class == 'RbPrestashopWidgetMenu') {
			$widget_obj = new RbMenu();
		} else if ($name_class == 'RbPrestashopWidgetProductslist') {
			$widget_obj = new RbProduct();
		} else if ($name_class == 'RbPrestashopWidgetBrands') {
			$widget_obj = new RbBrands();
		} else if ($name_class == 'RbPrestashopWidgetRbthemeslider') {
			$widget_obj = new RbSliderDD();
		} else if ($name_class == 'RbPrestashopWidgetProductslisttabs') {
			$widget_obj = new RbProductTab();
		} else if ($name_class == 'RbPrestashopWidgetNewsletter') {
			$widget_obj = new RbNewsletter();
		} else if ($name_class == 'RbPrestashopWidgetBlog') {
			$widget_obj = new RbBlog();
		} else if ($name_class == 'RbPrestashopWidgetModules') {
			$widget_obj = new RbModule();
		} else if ($name_class == 'RbPrestashopWidgetCustomTpl') {
			$widget_obj = new RbTpl();
		} else {
			$widget_obj = new $name_class();
		}

		if (empty($widget_data['settings']))
			$widget_data['settings'] = array();

		$instance = $widget_obj->getWidgetValues($widget_data['settings']);
		$html = $widget_obj->wdBeforeRender($instance, $widget_data['id'], $widget_data , $widget_data['widgetType']);
		$instance['id_widget_instance'] = $widget_data['id'];
		$html .= $this->wdRenderContent($instance, $widget_obj);
		$html .= "</div>";

		return $html;
	}

	public function wdRenderContent($instance, $obj)
    {
        $this->context->smarty->assign(array(
            'content' => $obj->rbRender($instance),
        ));

        return $this->module->fetch('module:rbthemedream/views/templates/widget.tpl');
    }

	public function applyBuilderInContent()
	{
		$data = '';

		if (!empty($this->data)) {
			foreach ($this->data as $section) {
				$data .= $this->rbPrintSection($section);
			}
		}

		$this->context->smarty->assign(array(
			'data' => $data,
		));

		return $this->module->fetch('module:rbthemedream/views/templates/front/front.tpl');
	}
}
