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

class RbTpl extends RbControl
{
	public $editMode = false;

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
    	$availableTpls = array();

        if ($this->editMode) {
            $dir = $this->context->controller->module->getLocalPath().'views/templates/widget/custom/';
            
            foreach (glob($dir . '*.tpl') as $file) {
                $fileName = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($file));
                $availableTpls[$fileName] = $fileName;
            }
        }

    	$this->addPresControl(array(
    		'section_pswidget_options' => array(
                'label' => $module->l('Widget settings'),
                'type' => 'section',
            ),
            'file' => array(
                'label' => $module->l('File'),
                'type' => 'select',
                'label_block' => true,
                'default' => 'customtpl',
                'description' => $module->l(
                	'Sometimes you may need to put some custom smarty content as widget'
                ),
                'section' => 'section_pswidget_options',
                'options' => $availableTpls,
            ),
    	));
    }

    public function getDataTpl()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Custom TPL',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('prestashop'),
    		'keywords' => '',
    		'icon' => 'tpl'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
        $optionsSource = $this->getWidgetValues($instance);

        if (isset($optionsSource['file'])){
            $module = new Rbthemedream();

            $this->context->smarty->assign(array(
                'file' => $optionsSource['file'],
            ));

            return $module->fetch('module:rbthemedream/views/templates/widget/rb-custom.tpl');
        }

        return;
    }
}
