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

require_once(_PS_MODULE_DIR_.'rbthemedream/lib/control/rb-control.php');

class RbSliderDD extends RbControl
{
	public $editMode = false;
	public $revModule;

	public function __construct()
    {
    	parent::__construct();
    	$this->context = Context::getContext();

    	if (isset($this->context->controller->controller_name) &&
    		$this->context->controller->controller_name == 'AdminRbthemedreamLive'
    	) {
            $this->editMode = true;
        }

    	$this->setControl();
    }

    public function setControl()
    {
    	$module = new Rbthemedream();

    	$sliders = array();
        $sliders[0] = $module->l('-- None --');

        if ($this->editMode) {
            $this->revModule = Module::getInstanceByName('rbthemeslider');

            if (Validate::isLoadedObject($this->revModule)) {
                $slidersData = $this->getSliders();

                if ($slidersData) {
                    foreach ($slidersData as $slide) {
                        $sliders[$slide['id']] = $slide['title'] . '(' . $slide['alias'] . ')';
                    }
                }
            }
        }

        $this->addPresControl(array(
            'section_pswidget_options' => array(
                'label' => $module->l('Widget settings'),
                'type' => 'section',
            ),
            'slider' => array(
                'label' => $module->l('Slider'),
                'type' => 'select',
                'default' => 0,
                'section' => 'section_pswidget_options',
                'options' => $sliders,
            ),
        ));
    }

    public function getSliders()
    {
        $sql = 'SELECT *
		FROM  `' . _DB_PREFIX_ . 'rbslider_sliders`';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return false;
        }

        return $result;
    }

    public function getDataSlider()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Slider',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('prestashop'),
    		'keywords' => '',
    		'icon' => 'image-carousel'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
        if (Tools::getIsset('controller') &&
            Tools::getValue('controller') == 'index'
        ) {
            $instance = $this->getWidgetValues($instance);
            $module = new Rbthemedream();
            $slider = '';
            $sliderId = (int)$instance['slider'];

            if ($sliderId != 0) {
                $rbslider = Module::getInstanceByName('rbthemeslider');

                if (Validate::isLoadedObject($rbslider)) {
                    $rbslider->_prehook();
                    $slider = $rbslider->generateSliderById($sliderId);
                }
            }

            $this->context->smarty->assign(array(
                'slider' => $slider,
            ));

            return $module->fetch('module:rbthemedream/views/templates/widget/rb-slider.tpl');
        } else {
            return;
        }
    }
}
