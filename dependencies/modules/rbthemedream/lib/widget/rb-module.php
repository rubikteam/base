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
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
require_once(_PS_MODULE_DIR_.'rbthemedream/lib/control/rb-control.php');

class RbModule extends RbControl
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

    	$hooks = array(
            'displayHome' => 'displayHome',
            'displayLeftColumn' => 'displayLeftColumn',
            'displayRightColumn' => 'displayRightColumn',
            'displayTopColumn'=> 'displayTopColumn',
            'displayTop' => 'displayTop',
            'displayFooter' => 'displayFooter'
        );

        $availableModules = array();

        if ($this->editMode) {
            $availableModules = $this->getAvailableModules();
        }

        $this->addPresControl(array(
        	'section_pswidget_options' => array(
                'label' => $module->l('Widget settings'),
                'type' => 'section',
            ),
            'module' => array(
                'label' => $module->l('Module'),
                'type' => 'select',
                'label_block' => true,
                'default' => '0',
                'description' => $module->l('This widget is only for advanced users'),
                'section' => 'section_pswidget_options',
                'options' => $availableModules,
            ),
            'hook' => array(
                'label' => $module->l('hook'),
                'type' => 'select',
                'default' => 'displayHome',
                'description' => $module->l('Make sure module support hook you selected.'),
                'section' => 'section_pswidget_options',
                'options' => $hooks,
            ),
        ));
    }

    public function getAvailableModules()
    {
        $module = new Rbthemedream();

        $excludeModules = array(
            'contactform',
            'ps_categorytree',
            'ps_contactinfo',
            'ps_emailsubscription',
            'ps_customtext',
            'ps_featuredproducts',
            'ps_sharebuttons',
            'ps_socialfollow',
        );

        $modules = Db::getInstance()->executeS(
            'SELECT m.`id_module`, m.`name`
            FROM `'._DB_PREFIX_.'module` m
            '.Shop::addSqlAssociation('module', 'm').'
            WHERE m.`name` IN (\'' . implode("','", $excludeModules) . '\')'
        );

        $modulesHook = array();
        $modulesHook[0] =  $module->l('Select module');

        foreach ($modules as $key => $module) {
            $moduleInstance = Module::getInstanceByName($module['name']);

            if (Validate::isLoadedObject($moduleInstance)) {
                $modulesHook[$module['name']] =  $module['name'];
            }
        }

        return $modulesHook;
    }

    public function getDataModule()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Module',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('prestashop'),
    		'keywords' => '',
    		'icon' => 'module'
    	);
    	
    	return $data;
    }

    public function rbRender($instance = array())
    {
        if (Tools::getIsset('controller') &&
            Tools::getValue('controller') == 'index'
        ) {
            $optionsSource = $this->getWidgetValues($instance);

            if (isset($optionsSource['module'])) {
                $module = Module::getInstanceByName($optionsSource['module']);

                return $module->renderWidget($optionsSource['hook'], array());
            }
        }
        
        return;
    }
}
