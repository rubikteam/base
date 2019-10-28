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

if (!defined('_PS_VERSION_')) {
    exit;
}

if (!class_exists('Rbthemeslider')) {
    Module::getInstanceByName('rbthemeslider');
}

include_once(_PS_MODULE_DIR_.'Rbthemeslider/rbslider_admin.php');

class AdminRbthemesliderAjaxController extends ModuleAdminController
{
    protected $_ajax_results;
    protected $_ajax_stripslash;
    protected $_filter_whitespace;
    protected $lushslider_model;

    public function __construct()
    {
        $this->display_header = false;
        $this->display_footer = false;
        $this->content_only   = true;

        parent::__construct();

        $this->_ajax_results['error_on'] = 1;
    }

    public function init()
    {
        $this->initProcess();
    }

    public function initProcess()
    {
        $rbAction = Tools::getValue('rbControllerAction');
        $loadTemplate = false;
        new RbSliderAdmin(_PS_MODULE_DIR_.'rbthemeslider', $loadTemplate);
        
        switch ($rbAction) {
            case 'uploadimage':
                $this->rbUploader();

                break;
            case 'captions':
                $db = new UniteDBRb();
                $styles = $db->fetch(GlobalsRbSlider::$table_css);
                header("Content-Type: text/css; charset=utf-8");
            
                echo UniteCssParserRb::parseDbArrayToCss($styles, "\n");

                break;
            default:
                
                break;
        }

        die();
    }

    private function rbUploader()
    {
        $key = Tools::getValue('security_key');

        if (empty($key) ||
            Tools::encrypt(GlobalsRbSlider::MODULE_NAME) != $key
        ) {
            echo Tools::jsonEncode(
                array(
                    'error_on' => 1,
                    'error_details' => 'Security Error'
                )
            );

            die();
        }
        
        $targetFolder = ABSPATH.'/uploads/';
        $info = pathinfo($_FILES['Filedata']['name']);
        $NewFileName = preg_replace_callback('/[^a-zA-Z0-9_\-]+/', function($match){return "-";}, $info['filename']);

        if (!empty($_FILES)) {
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $targetPath = $targetFolder;
            
            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png');

            if (in_array($info['extension'], $fileTypes)) {
                $worked = UniteFunctionsPSRb::importMediaImg(
                    $tempFile,
                    $targetPath,
                    $NewFileName.'.'.$info['extension']
                );

                if (!empty($worked)) {
                    echo '1';
                }
            } else {
                echo '0';
            }
        }
    }

    protected function bindToAjaxRequest($post_method = false)
    {
        if (!$this->isXmlHttpRequest()) {
            die('We Only Accept Ajax Request');
        }

        if ($post_method) {
            if (!@Rbthemeslider::getIsset($_SERVER['REQUEST_METHOD']) ||
                'POST' != $_SERVER['REQUEST_METHOD']
            ) {
                die('Only POST Request Method is allowed');
            }
        }
        
        return true;
    }
}
