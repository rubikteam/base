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

class RbSliderAdmin extends UniteBaseAdminClassRb
{

    const DEFAULT_VIEW = "sliders";
    const VIEW_SLIDER = "slider";
    const VIEW_SLIDER_TEMPLATE = "slider_template";
    const VIEW_SLIDERS = "sliders";
    const VIEW_SLIDES = "slides";
    const VIEW_SLIDE = "slide";

    public function __construct($mainFilepath, $view = true)
    {
        self::addMenuPage('Rbthemeslider', "adminPages");

        if ($view) {
            parent::__construct($mainFilepath, $this, self::DEFAULT_VIEW);
        } else {
            parent::__construct($mainFilepath, $this, '');
        }

        $path_view = self::$path_plugin . 'views/css/';

        //set table names
        GlobalsRbSlider::$table_sliders = self::$table_prefix . GlobalsRbSlider::TABLE_SLIDERS_NAME;
        GlobalsRbSlider::$table_slides = self::$table_prefix . GlobalsRbSlider::TABLE_SLIDES_NAME;
        GlobalsRbSlider::$table_static_slides = self::$table_prefix . GlobalsRbSlider::TABLE_STATIC_SLIDES_NAME;
        GlobalsRbSlider::$table_settings = self::$table_prefix . GlobalsRbSlider::TABLE_SETTINGS_NAME;
        GlobalsRbSlider::$table_css = self::$table_prefix . GlobalsRbSlider::TABLE_CSS_NAME;
        GlobalsRbSlider::$table_layer_anims = self::$table_prefix . GlobalsRbSlider::TABLE_LAYER_ANIMS_NAME;
        GlobalsRbSlider::$table_navigation = self::$table_prefix . GlobalsRbSlider::TABLE_NAVIGATION_NAME;
        GlobalsRbSlider::$table_options = self::$table_prefix . GlobalsRbSlider::TABLE_RBSLIDER_OPTIONS_NAME;
        GlobalsRbSlider::$filepath_backup = $path_view . "backup/";
        GlobalsRbSlider::$filepath_captions = $path_view . "rs-plugin/css/captions.css";

        GlobalsRbSlider::$urlCaptionsCSS = Context::getContext()->link->getAdminLink(
            'AdminRbthemesliderAjax'
        ) . '&rbControllerAction=captions';

        GlobalsRbSlider::$urlStaticCaptionsCSS = $path_view . "rs-plugin/css/static-captions.css";
        GlobalsRbSlider::$filepath_dynamic_captions = $path_view . "rs-plugin/css/dynamic-captions.css";
        GlobalsRbSlider::$filepath_static_captions = $path_view . "rs-plugin/css/static-captions.css";
        GlobalsRbSlider::$filepath_captions_original = $path_view . "rs-plugin/css/captions-original.css";
        GlobalsRbSlider::$urlExportZip = self::$path_plugin . "export.zip";

        $this->init();
    }

    private function init()
    {
        self::requireSettings("general_settings");
        $generalSettings = self::getSettings("general");
        $role = $generalSettings->getSettingValue("role", UniteBaseAdminClassRb::ROLE_ADMIN);
        self::setMenuRole($role);
        $action = self::getPostGetVar("client_action");
        $data = self::getPostGetVar("data");
        $ajax_action = self::getPostGetVar("action");

        if (!empty($action) or ! empty($data)) {
            self::onAjaxAction();
        } elseif (!empty($ajax_action)) {
            if (@Rbthemeslider::getIsset(self::$actions['ps_ajax_' . $ajax_action]) &&
                !empty(self::$actions['ps_ajax_' . $ajax_action])
            ) {
                foreach (self::$actions['ps_ajax_' . $ajax_action] as $callback) {
                    call_user_func(array(__CLASS__, $callback));
                }
            }
        } else {
            if (!empty(self::$view)) {
                if (self::$view != 'fileupload') {
                    if (@Rbthemeslider::getIsset(self::$actions['admin_enqueue_scripts']) &&
                        !empty(self::$actions['admin_enqueue_scripts'])
                    ) {
                        foreach (self::$actions['admin_enqueue_scripts'] as $callback) {
                            call_user_func(array(__CLASS__, $callback));
                        }
                    }
                }

                if (@Rbthemeslider::getIsset(self::$actions['admin_menu']) &&
                    !empty(self::$actions['admin_menu'])
                ) {
                    foreach (self::$actions['admin_menu'] as $admin_menu_actions) {
                        call_user_func(array(__CLASS__, $admin_menu_actions));
                    }
                }
            }
        }
    }

    public static function customPostFieldsOutput(UniteSettingsProductSidebarRb $output)
    {
        echo '<ul class="rbslider_settings">';

        $output->drawSettingsByNames("slide_template");

        echo '</ul>';
    }

    public static function onActivate()
    {
        $rt = self::createDBTables();

        RbSliderPluginUpdate::addV5Styles();

        return $rt;
    }

    public static function createDBTables()
    {
        $res = self::createTable(GlobalsRbSlider::TABLE_SLIDERS_NAME);
        $res &= self::createTable(GlobalsRbSlider::TABLE_SLIDES_NAME);
        $res &= self::createTable(GlobalsRbSlider::TABLE_STATIC_SLIDES_NAME);
        $res &= self::createTable(GlobalsRbSlider::TABLE_SETTINGS_NAME);
        $res &= self::createTable(GlobalsRbSlider::TABLE_CSS_NAME);
        $res &= self::createTable(GlobalsRbSlider::TABLE_LAYER_ANIMS_NAME);
        $res &= self::createTable(GlobalsRbSlider::TABLE_RBSLIDER_OPTIONS_NAME);
        $res &= self::createTable(GlobalsRbSlider::TABLE_ATTACHMENT_IMAGES);
        $res &= self::createTable(GlobalsRbSlider::TABLE_NAVIGATION_NAME);

        return $res;
    }

    public static function deleteDBTables()
    {
        $res = self::deleteDBTable(GlobalsRbSlider::TABLE_SLIDERS_NAME);
        $res &= self::deleteDBTable(GlobalsRbSlider::TABLE_SLIDES_NAME);
        $res &= self::deleteDBTable(GlobalsRbSlider::TABLE_SETTINGS_NAME);
        $res &= self::deleteDBTable(GlobalsRbSlider::TABLE_STATIC_SLIDES_NAME);
        $res &= self::deleteDBTable(GlobalsRbSlider::TABLE_CSS_NAME);
        $res &= self::deleteDBTable(GlobalsRbSlider::TABLE_LAYER_ANIMS_NAME);
        $res &= self::deleteDBTable(GlobalsRbSlider::TABLE_RBSLIDER_OPTIONS_NAME);
        $res &= self::deleteDBTable(GlobalsRbSlider::TABLE_ATTACHMENT_IMAGES);
        $res &= self::deleteDBTable(GlobalsRbSlider::TABLE_NAVIGATION_NAME);

        return $res;
    }

    public static function checkCopyCaptionsCSS()
    {
        if (file_exists(GlobalsRbSlider::$filepath_captions) == false) {
            copy(GlobalsRbSlider::$filepath_captions_original, GlobalsRbSlider::$filepath_captions);
        }

        if (!file_exists(GlobalsRbSlider::$filepath_captions) == true) {
            self::setStartupError(
                "Can't copy <b>captions-original.css </b> to <b>captions.css</b> in
                <b> plugins/rbslider/rs-plugin/css </b> folder.
                Please try to copy the file by hand or turn to support."
            );
        }
    }

    public static function enqueueFileUploaderScripts()
    {
        $html = '';
        $js_uri = array();
        $css_uri = array();
        $css_uri[] = self::$url_plugin . "/rs-plugin/fileuploader/uploadify.css";
        $css_uri[] = self::$url_plugin . "/css/bootstrap.min.css";
        $css_uri[] = self::$url_plugin . "/css/jui/new/jquery-ui-1.10.3.custom.css";
        $js_uri[] = self::$url_plugin . 'js/admin.js';
        $js_uri[] = self::$url_plugin . 'js/jquery-ui/jquery-ui-1.10.3.custom.js';
        $js_uri[] = self::$url_plugin . 'rs-plugin/fileuploader/jquery.uploadify.min.js';
        $js_uri[] = self::$url_plugin . 'js/bootstrap.min.js';

        foreach ($css_uri as $css) {
            $html .= '<link href="' . $css . '" rel="stylesheet" type="text/css"/>';
        }

        foreach ($js_uri as $js) {
            $html .= '<script type="text/javascript" src="' . $js . '"></script>';
        }

        return $html;
    }

    public static function onAddScripts()
    {
        self::addStyle("edit_layers", "edit_layers");
        self::addMediaUploadIncludes();
    }

    public static function adminPages()
    {
        parent::adminPages();

        rbHead();

        //require styles by view
        switch (self::$view) {

            case self::VIEW_SLIDERS:

            case self::VIEW_SLIDER:

            case self::VIEW_SLIDER_TEMPLATE:

                self::requireSettings("slider_settings");

                break;

            case self::VIEW_SLIDES:

                break;

            case self::VIEW_SLIDE:

                break;
        }

        self::setMasterView("master_view");
        self::requireView(self::$view);

        rbFooter();
    }

    public static function deleteDBTable($tableName)
    {
        if (!@Rbthemeslider::getIsset(self::$psdb)) {
            $psdb = Rbthemeslider::$psdb;
        } else {
            $psdb = self::$psdb;
        }

        $tableName = $psdb->prefix . $tableName;
        $sql = "DROP TABLE IF EXISTS {$tableName}";
        $q = $psdb->query($sql);

        if ($q) {
            return true;
        }
    }

    public static function createTable($tableName)
    {
        $parseCssToDb = false;

        if (!@Rbthemeslider::getIsset(self::$psdb)) {
            $psdb = Rbthemeslider::$psdb;
        } else {
            $psdb = self::$psdb;
        }

        $tableRealName = $psdb->prefix . $tableName;

        switch ($tableName) {
            case GlobalsRbSlider::TABLE_ATTACHMENT_IMAGES:
                $sql = "CREATE TABLE IF NOT EXISTS {$tableRealName}(
                ID INT(10) NOT NULL AUTO_INCREMENT,
                file_name VARCHAR(100) NOT NULL,
                PRIMARY KEY (ID)
                ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

                break;

            case GlobalsRbSlider::TABLE_SLIDERS_NAME:
                $sql = "CREATE TABLE IF NOT EXISTS " . $tableRealName . " (
                id int(9) NOT NULL AUTO_INCREMENT,
                title tinytext NOT NULL,
                alias tinytext,
                params MEDIUMTEXT NOT NULL,
                settings MEDIUMTEXT NULL,
                type varchar(191) NOT NULL,
                PRIMARY KEY (id)
                ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

                break;

            case GlobalsRbSlider::TABLE_NAVIGATION_NAME:
                $sql = "CREATE TABLE IF NOT EXISTS " . $tableRealName . " (
                id int(9) NOT NULL AUTO_INCREMENT,
                name varchar(191) NOT NULL,
                handle varchar(191) NOT NULL,
                css MEDIUMTEXT NOT NULL,
                markup MEDIUMTEXT NOT NULL,
                settings MEDIUMTEXT,
                PRIMARY KEY (id)
                ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

                break;

            case GlobalsRbSlider::TABLE_SLIDES_NAME:
                $sql = "CREATE TABLE IF NOT EXISTS " . $tableRealName . " (
                id int(9) NOT NULL AUTO_INCREMENT,
                slider_id int(9) NOT NULL,
                slide_order int not NULL,	
                params MEDIUMTEXT NOT NULL,
                layers MEDIUMTEXT NOT NULL,
                settings MEDIUMTEXT NOT NULL,
                PRIMARY KEY (id)
                ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

                break;
            case GlobalsRbSlider::TABLE_STATIC_SLIDES_NAME:
                $sql = "CREATE TABLE IF NOT EXISTS " . $tableRealName . " (
                id int(9) NOT NULL AUTO_INCREMENT,
                slider_id int(9) NOT NULL,
                params MEDIUMTEXT NOT NULL,
                layers MEDIUMTEXT NOT NULL,
                PRIMARY KEY (id)
                ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

                break;
            case GlobalsRbSlider::TABLE_SETTINGS_NAME:
                $sql = "CREATE TABLE IF NOT EXISTS " . $tableRealName . " (
                id int(9) NOT NULL AUTO_INCREMENT,
                general MEDIUMTEXT NOT NULL,
                params MEDIUMTEXT NOT NULL,
                PRIMARY KEY (id)
                ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

                break;

            case GlobalsRbSlider::TABLE_CSS_NAME:
                $sql = "CREATE TABLE IF NOT EXISTS " . $tableRealName . " (
                id int(9) NOT NULL AUTO_INCREMENT,
                handle TEXT NOT NULL,
                settings MEDIUMTEXT,
                hover MEDIUMTEXT,
                params MEDIUMTEXT NOT NULL,
                advanced MEDIUMTEXT,
                PRIMARY KEY (id)
                ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

                $parseCssToDb = true;

                break;

            case GlobalsRbSlider::TABLE_LAYER_ANIMS_NAME:
                $sql = "CREATE TABLE IF NOT EXISTS " . $tableRealName . " (
                id int(9) NOT NULL AUTO_INCREMENT,
                handle TEXT NOT NULL,
                params TEXT NOT NULL,
                PRIMARY KEY (id)
                ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

                break;

            case GlobalsRbSlider::TABLE_RBSLIDER_OPTIONS_NAME:
                $sql = "CREATE TABLE IF NOT EXISTS " . $tableRealName . " (
                id int(9) NOT NULL AUTO_INCREMENT,
                name VARCHAR(32) NOT NULL,
                value MEDIUMTEXT NOT NULL,
                PRIMARY KEY (id)
                ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

                break;
            default:
                UniteFunctionsRb::throwError("table: $tableName not found");

                break;
        }

        $q = $psdb->query($sql);

        return $q;
    }

    public static function sdsCaptionCssInit($parseCssToDb)
    {
        if ((bool) $parseCssToDb === true) {
            $rbOperations = new RbOperations();
            $rbOperations->importCaptionsCssContentArray();
            $rbOperations->moveOldCaptionsCss();
            $rbOperations->updateDynamicCaptions(true);
            return true;
        }
    }

    private static function importSliderHandle(
        $viewBack = null,
        $updateAnim = true,
        $updateStatic = true,
        $updateNavigation = true
    ) {
        $modules = new Rbthemeslider();
        dmp($modules->l("importing slider settings and data..."));
        $slider = new RbSlider();

        $response = $slider->importSliderFromPost(
            $updateAnim,
            $updateStatic,
            false,
            false,
            false,
            $updateNavigation
        );

        $sliderID = $response["sliderID"];

        if (empty($viewBack)) {
            $viewBack = self::getViewUrl(self::VIEW_SLIDER, "id=" . $sliderID);

            if (empty($sliderID)) {
                $viewBack = self::getViewUrl(self::VIEW_SLIDERS);
            }
        }

        //handle error
        if ($response["success"] == false) {
            $message = $response["error"];
            dmp("<b>Error: " . $message . "</b>");
            echo RbSliderFunctions::getHtmlLink($viewBack, $modules->l("Go Back"));
        } else {    //handle success, js redirect.
            dmp($modules->l("Slider Import Success, redirecting..."));
            echo "<script>location.href='$viewBack'</script>";
        }

        exit();
    }

    /**
     * Toggle Favorite State of Slider
     * @since: 5.0
     */
    public static function toggleFavoriteById($id)
    {
        $modules = new Rbthemeslider();
        $id = (int) ($id);

        if ($id === 0) {
            return false;
        }

        if (!@Rbthemeslider::getIsset(self::$psdb)) {
            $psdb = Rbthemeslider::$psdb;
        } else {
            $psdb = self::$psdb;
        }

        $table_name = $psdb->prefix . RbSliderGlobals::TABLE_SLIDERS_NAME;

        //check if ID exists
        $slider = $psdb->getRow("SELECT settings FROM $table_name WHERE id = $id", ARRAY_A);

        if (empty($slider)) {
            return $modules->l('Slider not found');
        }

        $settings = Tools::jsonDecode($slider['settings'], true);

        if (!@Rbthemeslider::getIsset($settings['favorite']) ||
            $settings['favorite'] == 'false' ||
            $settings['favorite'] == false
        ) {
            $settings['favorite'] = 'true';
        } else {
            $settings['favorite'] = 'false';
        }

        $response = $psdb->update(
            $table_name,
            array(
                'settings' => Tools::jsonEncode($settings)
            ),
            array('id' => $id)
        );

        if ($response === false) {
            return $modules->l('Slider setting could not be changed');
        }

        return true;
    }

    /**
     * import slider from TP servers
     * @since: 5.0.5
     */
    private static function importSliderOnlineTemplateHandle(
        $viewBack = null,
        $updateAnim = true,
        $updateStatic = true,
        $single_slide = false
    ) {
        $modules = new Rbthemeslider();
        dmp($modules->l("downloading template slider from server..."));

        $uid = esc_attr(RbliderFunctions::getPostVariable('uid'));

        if ($uid == '') {
            dmp($modules->l("ID missing, something went wrong. Please try again!"));
            echo RbSliderFunctions::getHtmlLink(
                $viewBack,
                $modules->l("Go Back")
            );

            exit;
        } else {
            //send request to TP server and download file
            $tmp = new RbSliderTemplate();
            $filepath = $tmp->downloadTemplate($uid);

            if ($filepath !== false && !is_array($filepath)) {
                $tmp_slider = $tmp->getThemePunchTemplateSliders();

                foreach ($tmp_slider as $tslider) {
                    if (isset($tslider['uid']) && $uid == $tslider['uid']) {
                        if (!isset($tslider['installed'])) {
                            $mSlider = new RbSlider();
                            $mSlider->initByID($tslider['id']);
                            $mSlider->deleteSlider();

                            //remove the update flag from the slider
                            $tmp->removeIsNew($uid);
                        }
                        break;
                    }
                }

                $slider = new RbSlider();

                $response = $slider->importSliderFromPost(
                    $updateAnim,
                    $updateStatic,
                    $filepath,
                    $uid,
                    $single_slide
                );

                $tmp->deleteTemplate($uid);

                if ($single_slide === false) {
                    if (empty($viewBack)) {
                        $sliderID = $response["sliderID"];
                        $viewBack = self::getViewUrl(self::VIEW_SLIDER, "id=" . $sliderID);
                        if (empty($sliderID)) {
                            $viewBack = self::getViewUrl(self::VIEW_SLIDERS);
                        }
                    }
                }

                //handle error
                if ($response["success"] == false) {
                    $message = $response["error"];
                    dmp("<b>Error: " . $message . "</b>");

                    echo RbSliderFunctions::getHtmlLink(
                        $viewBack,
                        $modules->l("Go Back")
                    );
                } else {
                    dmp($modules->l("Slider Import Success, redirecting..."));
                    echo "<script>location.href='$viewBack'</script>";
                }
            } else {
                if (is_array($filepath)) {
                    dmp($filepath['error']);
                } else {
                    dmp($modules->l("Could not download from server. Please try again later!"));
                }

                echo RbSliderFunctions::getHtmlLink($viewBack, $modules->l("Go Back"));

                exit;
            }
        }

        exit;
    }

    public static function onAjaxAction()
    {
        $modules = new Rbthemeslider();
        $slider = new RbSlider();
        $slide = new RbSlide();
        $operations = new RbOperations();
        $action = self::getPostGetVar("client_action");
        $data = self::getPostGetVar("data");

        try {

            switch ($action) {
                //start hook setting
                case 'update_shop':
                    $template = new RbSliderTemplate();
                    $template->getTemplateList(true);
                    self::ajaxResponseSuccess($modules->l("Templates Updated"), array());

                    break;
                case 'add_new_preset':

                    if (!@Rbthemeslider::getIsset($data['settings']) ||
                        !@Rbthemeslider::getIsset($data['values'])) {
                        self::ajaxResponseError('Missing values to add preset', false);
                    }

                    $result = $operations->addPresetSetting($data);

                    if ($result === true) {
                        $presets = $operations->getPresetSettings();
                        self::ajaxResponseSuccess('Preset created', array('data' => $presets));
                    } else {
                        self::ajaxResponseError($result, false);
                    }

                    exit;
                    break;
                case 'update_preset':

                    if (!@Rbthemeslider::getIsset($data['name']) ||
                        !@Rbthemeslider::getIsset($data['values'])) {
                        self::ajaxResponseError($modules->l('Missing values to update preset'), false);
                    }

                    $result = $operations->updatePresetSetting($data);

                    if ($result === true) {
                        $presets = $operations->getPresetSettings();

                        self::ajaxResponseSuccess(
                            $modules->l('Preset updated'),
                            array('data' => $presets)
                        );
                    } else {
                        self::ajaxResponseError($result, false);
                    }

                    exit;

                    break;
                case 'remove_preset':

                    if (!@Rbthemeslider::getIsset($data['name'])) {
                        self::ajaxResponseError($modules->l('Missing values to remove preset'), false);
                    }

                    $result = $operations->removePresetSetting($data);

                    if ($result === true) {
                        $presets = $operations->getPresetSettings();
                        self::ajaxResponseSuccess($modules->l('Preset deleted'), array('data' => $presets));
                    } else {
                        self::ajaxResponseError($result, false);
                    }

                    exit;

                    break;
                case 'add_new_hook':
                    $f = new SdsRbHooksClass();
                    $result = $f->addNewHook($data);

                    if ($result === true) {
                        self::ajaxResponseSuccessRedirect(
                            $modules->l("Hook successfully created!"),
                            self::getViewUrl("sliders")
                        );
                    } else {
                        self::ajaxResponseError($result, false);
                    }
                    break;
                case 'removes_hooks':
                    if (!@Rbthemeslider::getIsset($data['hookname'])) {
                        self::ajaxResponseError($modules->l('Hook not found'), false);
                    }

                    $f = new SdsRbHooksClass();
                    $result = $f->removeHookByHookname($data['hookname']);

                    if ($result === true) {
                        self::ajaxResponseSuccess($modules->l("Hook successfully removed!"), array('data' => $result));
                    } else {
                        self::ajaxResponseError($result, false);
                    }
                    break;
                // end hook setting
                case "export_slider":
                    $sliderID = self::getGetVar("sliderid");
                    $dummy = self::getGetVar("dummy");
                    $slider->initByID($sliderID);
                    $slider->exportSlider($dummy);
                    break;
                case "import_slider":
                    $updateAnim = self::getPostGetVar("update_animations");
                    $updateNav = self::getPostGetVar("update_navigations");
                    $updateStatic = self::getPostGetVar("update_static_captions");
                    self::importSliderHandle(null, $updateAnim, $updateStatic, $updateNav);
                    break;
                case "import_slider_slidersview":
                    $viewBack = self::getViewUrl(self::VIEW_SLIDERS);
                    $updateAnim = self::getPostGetVar("update_animations");
                    $updateNav = self::getPostGetVar("update_navigations");
                    $updateStatic = self::getPostGetVar("update_static_captions");
                    self::importSliderHandle($viewBack, $updateAnim, $updateStatic, $updateNav);
                    break;
                case "import_slider_online_template_slidersview":
                    $viewBack = self::getViewUrl(self::VIEW_SLIDERS);
                    self::importSliderOnlineTemplateHandle($viewBack, 'true', 'none');
                    break;
                case "import_slide_online_template_slidersview":
                    $redirect_id = (self::getPostGetVar("redirect_id"));
                    $viewBack = self::getViewUrl(self::VIEW_SLIDE, "id=$redirect_id");
                    $slidenum = (int) (self::getPostGetVar("slidenum"));
                    $sliderid = (int) (self::getPostGetVar("slider_id"));

                    self::importSliderOnlineTemplateHandle(
                        $viewBack,
                        'true',
                        'none',
                        array(
                            'slider_id' => $sliderid,
                            'slide_id' => $slidenum
                        )
                    );

                    break;
                case "import_slider_template_slidersview":
                    $viewBack = self::getViewUrl(self::VIEW_SLIDERS);
                    $updateAnim = self::getPostGetVar("update_animations");
                    $updateStatic = self::getPostGetVar("update_static_captions");
                    self::importSliderTemplateHandle($viewBack, $updateAnim, $updateStatic);
                    break;
                case "import_slide_template_slidersview":

                    $redirect_id = (self::getPostGetVar("redirect_id"));
                    $viewBack = self::getViewUrl(self::VIEW_SLIDE, "id=$redirect_id");
                    $updateAnim = self::getPostGetVar("update_animations");
                    $updateStatic = self::getPostGetVar("update_static_captions");
                    $slidenum = (int) (self::getPostGetVar("slidenum"));
                    $sliderid = (int) (self::getPostGetVar("slider_id"));

                    self::importSliderTemplateHandle(
                        $viewBack,
                        $updateAnim,
                        $updateStatic,
                        array(
                            'slider_id' => $sliderid,
                            'slide_id' => $slidenum
                        )
                    );

                    break;

                case "create_slider":
                    $data = $operations->modifyCustomSliderParams($data);
                    $newSliderID = $slider->createSliderFromOptions($data);
                    $slideID = $slider->createSlideFromData(array("sliderid" => $newSliderID), true);

                    self::ajaxResponseSuccessRedirect(
                        $modules->l("Slider created"),
                        self::getViewUrl(
                            self::VIEW_SLIDE,
                            'id=' . $slideID . '&slider=' . ($newSliderID)
                        )
                    ); 

                    break;

                case "update_slider":
                    $data = $operations->modifyCustomSliderParams($data);
                    $slider->updateSliderFromOptions($data);
                    self::ajaxResponseSuccess($modules->l("Slider updated"));

                    break;
                case "delete_slider":
                case "delete_slider_stay":
                    $isDeleted = $slider->deleteSliderFromData($data);

                    if (is_array($isDeleted)) {
                        $isDeleted = implode(', ', $isDeleted);
                        self::ajaxResponseError($modules->l(
                            "Template can't be deleted, it is still being used by the following Sliders: ") . $isDeleted
                        );
                    } else {
                        if ($action == 'delete_slider_stay') {
                            self::ajaxResponseSuccess($modules->l("Slider deleted"));
                        } else {
                            self::ajaxResponseSuccessRedirect(
                                $modules->l("Slider deleted"),
                                self::getViewUrl(self::VIEW_SLIDERS)
                            );
                        }
                    }
                    break;
                case "duplicate_slider":
                    $slider->duplicateSliderFromData($data);

                    self::ajaxResponseSuccessRedirect(
                        $modules->l("Success! Refreshing page..."),
                        self::getViewUrl(self::VIEW_SLIDERS)
                    );
                    break;

                case "add_slide":
                case "add_bulk_slide":
                    $slideid = $slider->createSlideFromData($data, true);
                    $sliderID = $data["sliderid"];
                    $responseText = $modules->l("Slide Created");
                    $urlRedirect = self::getViewUrl(self::VIEW_SLIDE, "id={$slideid}&slider={$sliderID}");

                    self::ajaxResponseSuccessRedirect($responseText, $urlRedirect);

                    break;

                case "add_slide_fromslideview":
                    $slideID = $slider->createSlideFromData($data, true);
                    $urlRedirect = self::getViewUrl(self::VIEW_SLIDE, "id=$slideID");
                    $responseText = $modules->l("Slide Created, redirecting...");

                    self::ajaxResponseSuccessRedirect($responseText, $urlRedirect);

                    break;

                case 'copy_slide_to_slider':
                    $slideID = (@Rbthemeslider::getIsset($data['redirect_id'])) ?
                    $data['redirect_id'] : -1;

                    if ($slideID === -1) {
                        RbSliderFunctions::throwError($modules->l('Missing redirect ID!'));
                    }

                    $return = $slider->copySlideToSlider($data);

                    if ($return !== true) {
                        RbSliderFunctions::throwError($return);
                    }

                    $urlRedirect = self::getViewUrl(self::VIEW_SLIDE, "id=$slideID");
                    $responseText = $modules->l("Slide copied to current Slider, redirecting...");
                    self::ajaxResponseSuccessRedirect($responseText, $urlRedirect);
                    break;

                case "update_slide":
                    $slide->updateSlideFromData($data);
                    self::ajaxResponseSuccess($modules->l("Slide updated"));

                    break;
                case "update_static_slide":
                    $slide->updateStaticSlideFromData($data);
                    self::ajaxResponseSuccess($modules->l("Static Global Layers updated"));

                    break;
                case "delete_slide":
                case "delete_slide_stay":
                    $isPost = $slide->deleteSlideFromData($data);

                    if ($isPost) {
                        $message = $modules->l("Post deleted");
                    } else {
                        $message = $modules->l("Slide deleted");
                    }

                    $sliderID = RbSliderFunctions::getVal($data, "sliderID");

                    if ($action == 'delete_slide_stay') {
                        self::ajaxResponseSuccess($message);
                    } else {
                        self::ajaxResponseSuccessRedirect(
                            $message,
                            self::getViewUrl(self::VIEW_SLIDES, "id={$sliderID}")
                        );
                    }
                    break;

                case "duplicate_slide":
                case "duplicate_slide_stay":
                    $return = $slider->duplicateSlideFromData($data);

                    if ($action == 'duplicate_slide_stay') {
                        self::ajaxResponseSuccess(
                            $modules->l("Slide duplicated"),
                            array('id' => $return[1])
                        );
                    } else {
                        self::ajaxResponseSuccessRedirect(
                            $modules->l("Slide duplicated"),
                            self::getViewUrl(self::VIEW_SLIDE, "id={$return[1]}&slider=" . $return[0])
                        );
                    }

                    break;
                case "copy_move_slide":
                case "copy_move_slide_stay":
                    $sliderID = $slider->copyMoveSlideFromData($data);

                    if ($action == 'copy_move_slide_stay') {
                        self::ajaxResponseSuccess($modules->l("Success!"));
                    } else {
                        self::ajaxResponseSuccessRedirect(
                            $modules->l("Success! Refreshing page..."),
                            self::getViewUrl(self::VIEW_SLIDE, "id=new&slider=$sliderID")
                        );
                    }
                    break;

                case "add_slide_to_template":
                    $template = new RbSliderTemplate();

                    if (!@Rbthemeslider::getIsset($data['slideID']) || (int) ($data['slideID']) == 0) {
                        RbSliderFunctions::throwError($modules->l('No valid Slide ID given'));
                        exit;
                    }

                    if (!@Rbthemeslider::getIsset($data['title']) || Tools::strlen(trim($data['title'])) < 3) {
                        RbSliderFunctions::throwError($modules->l('No valid title given'));
                        exit;
                    }

                    if (!@Rbthemeslider::getIsset($data['settings']) ||
                        !@Rbthemeslider::getIsset($data['settings']['width']) ||
                        !@Rbthemeslider::getIsset($data['settings']['height'])
                    ) {
                        RbSliderFunctions::throwError($modules->l('No valid title given'));
                        exit;
                    }

                    $return = $template->copySlideToTemplates(
                        $data['slideID'],
                        $data['title'],
                        $data['settings']
                    );

                    if ($return == false) {
                        RbSliderFunctions::throwError($modules->l('Could not save Slide as Template'));

                        exit;
                    }

                    ob_start();

                    include(PS_CONTENT_DIR . '/views/templates/template-selector.php');

                    $html = ob_get_contents();

                    ob_clean();
                    ob_end_clean();

                    self::ajaxResponseSuccess($modules->l('Slide added to Templates'), array('HTML' => $html));
                    exit;
                    break;
                    break;

                case "get_static_css":

                    $contentCSS = $operations->getStaticCss();

                    self::ajaxResponseData($contentCSS);

                    break;

                case "get_dynamic_css":

                    $contentCSS = $operations->getDynamicCss();

                    self::ajaxResponseData($contentCSS);

                    break;

                case "insert_captions_css":
                    $arrCaptions = $operations->insertCaptionsContentData($data);

                    if ($arrCaptions !== false) {
                        $db = new RbSliderDB();
                        $styles = $db->fetch(RbSliderGlobals::$table_css);
                        $styles = RbSliderCssParser::parseDbArrayToCss($styles, "\n");
                        $styles = RbSliderCssParser::compressCss($styles);
                        $custom_css = RbSliderOperations::getStaticCss();
                        $custom_css = RbSliderCssParser::compressCss($custom_css);
                        $arrCSS = $operations->getCaptionsContentArray();
                        $arrCssStyles = RbSliderFunctions::jsonEncodeForClientSide($arrCSS);
                        $arrCssStyles = $arrCSS;

                        self::ajaxResponseSuccess(
                            $modules->l("CSS saved"),
                            array(
                                "arrCaptions" => $arrCaptions,
                                'compressed_css' => $styles . $custom_css,
                                'initstyles' => $arrCssStyles
                            )
                        );
                    }

                    RbSliderFunctions::throwError($modules->l('CSS could not be saved'));

                    exit();
                    break;
                case "update_captions_css":
                    $arrCaptions = $operations->updateCaptionsContentData($data);

                    if ($arrCaptions !== false) {
                        $db = new RvSliderDB();
                        $styles = $db->fetch(RbSliderGlobals::$table_css);
                        $styles = RbSliderCssParser::parseDbArrayToCss($styles, "\n");
                        $styles = RbSliderCssParser::compressCss($styles);
                        $custom_css = RbSliderOperations::getStaticCss();
                        $custom_css = RbSliderCssParser::compressCss($custom_css);
                        $arrCSS = $operations->getCaptionsContentArray();
                        $arrCssStyles = RbSliderFunctions::jsonEncodeForClientSide($arrCSS);
                        $arrCssStyles = $arrCSS;

                        self::ajaxResponseSuccess(
                            $modules->l("CSS saved"),
                            array(
                                "arrCaptions" => $arrCaptions,
                                'compressed_css' => $styles . $custom_css,
                                'initstyles' => $arrCssStyles
                            )
                        );
                    }

                    RbSliderFunctions::throwError($modules->l('CSS could not be saved'));
                    exit();
                    break;

                case "update_captions_advanced_css":
                    $arrCaptions = $operations->updateAdvancedCssData($data);

                    if ($arrCaptions !== false) {
                        $db = new RbSliderDB();
                        $styles = $db->fetch(RbSliderGlobals::$table_css);
                        $styles = RbSliderCssParser::parseDbArrayToCss($styles, "\n");
                        $styles = RbSliderCssParser::compressCss($styles);
                        $custom_css = RbSliderOperations::getStaticCss();
                        $custom_css = RbSliderCssParser::compressCss($custom_css);
                        $arrCSS = $operations->getCaptionsContentArray();
                        $arrCssStyles = RbSliderFunctions::jsonEncodeForClientSide($arrCSS);
                        $arrCssStyles = $arrCSS;

                        self::ajaxResponseSuccess(
                            $modules->l("CSS saved"),
                            array(
                                "arrCaptions" => $arrCaptions,
                                'compressed_css' => $styles . $custom_css,
                                'initstyles' => $arrCssStyles
                            )
                        );
                    }

                    RbSliderFunctions::throwError($modules->l('CSS could not be saved'));
                    exit();
                    break;

                case "rename_captions_css":
                    //rename all captions in all sliders with new handle if success
                    $arrCaptions = $operations->renameCaption($data);
                    $db = new RbSliderDB();
                    $styles = $db->fetch(RbSliderGlobals::$table_css);
                    $styles = RbSliderCssParser::parseDbArrayToCss($styles, "\n");
                    $styles = RbSliderCssParser::compressCss($styles);
                    $custom_css = RbSliderOperations::getStaticCss();
                    $custom_css = RbSliderCssParser::compressCss($custom_css);
                    $arrCSS = $operations->getCaptionsContentArray();
                    $arrCssStyles = RbSliderFunctions::jsonEncodeForClientSide($arrCSS);
                    $arrCssStyles = $arrCSS;

                    self::ajaxResponseSuccess(
                        $modules->l("Class name renamed"),
                        array(
                            "arrCaptions" => $arrCaptions,
                            'compressed_css' => $styles . $custom_css,
                            'initstyles' => $arrCssStyles
                        )
                    );
                    break;

                case "delete_captions_css":
                    $arrCaptions = $operations->deleteCaptionsContentData($data);
                    $db = new RbSliderDB();
                    $styles = $db->fetch(RbSliderGlobals::$table_css);
                    $styles = RbSliderCssParser::parseDbArrayToCss($styles, "\n");
                    $styles = RbSliderCssParser::compressCss($styles);
                    $custom_css = RbSliderOperations::getStaticCss();
                    $custom_css = RbSliderCssParser::compressCss($custom_css);
                    $arrCSS = $operations->getCaptionsContentArray();
                    $arrCssStyles = RbSliderFunctions::jsonEncodeForClientSide($arrCSS);
                    $arrCssStyles = $arrCSS;

                    self::ajaxResponseSuccess(
                        $modules->l("Style deleted!"),
                        array(
                            "arrCaptions" => $arrCaptions,
                            'compressed_css' => $styles . $custom_css,
                            'initstyles' => $arrCssStyles
                        )
                    );
                    break;

                case "update_static_css":
                    $staticCss = $operations->updateStaticCss($data);
                    $db = new RbSliderDB();
                    $styles = $db->fetch(RbSliderGlobals::$table_css);
                    $styles = RbSliderCssParser::parseDbArrayToCss($styles, "\n");
                    $styles = RbSliderCssParser::compressCss($styles);
                    $custom_css = RbSliderOperations::getStaticCss();
                    $custom_css = RbSliderCssParser::compressCss($custom_css);

                    self::ajaxResponseSuccess(
                        $modules->l("CSS saved"),
                        array(
                            "css" => $staticCss,
                            'compressed_css' => $styles . $custom_css
                        )
                    );
                    break;

                case "insert_custom_anim":
                    $arrAnims = $operations->insertCustomAnim($data); //$arrCaptions =
                    self::ajaxResponseSuccess(
                        $modules->l("Animation saved"),
                        $arrAnims
                    );

                    break;
                case "update_custom_anim":
                    $arrAnims = $operations->updateCustomAnim($data);
                    self::ajaxResponseSuccess(
                        $modules->l("Animation saved"),
                        $arrAnims
                    );

                    break;
                case "update_custom_anim_name":
                    $arrAnims = $operations->updateCustomAnimName($data);
                    self::ajaxResponseSuccess(
                        $modules->l("Animation saved"),
                        $arrAnims
                    );

                    break;
                case "delete_custom_anim":
                    $arrAnims = $operations->deleteCustomAnim($data);
                    self::ajaxResponseSuccess(
                        $modules->l("Animation deleted"),
                        $arrAnims
                    );

                    break;
                case "update_slides_order":
                    $slider->updateSlidesOrderFromData($data);
                    self::ajaxResponseSuccess($modules->l("Order updated"));

                    break;
                case "change_slide_title":
                    $slide->updateTitleByID($data);
                    self::ajaxResponseSuccess($modules->l('Title updated'));

                    break;
                case "change_slide_image":
                    $slide->updateSlideImageFromData($data);
                    $sliderID = RbSliderFunctions::getVal($data, "slider_id");

                    self::ajaxResponseSuccessRedirect(
                        $modules->l("Slide changed"),
                        self::getViewUrl(self::VIEW_SLIDE, "id=new&slider=$sliderID")
                    );

                    break;
                case "preview_slide":
                    $operations->putSlidePreviewByData($data);

                    break;
                case "preview_slider":
                    $sliderID = RbSliderFunctions::getPostGetVariable("sliderid");
                    $do_markup = RbSliderFunctions::getPostGetVariable("only_markup");

                    if ($do_markup == 'true') {
                        $operations->previewOutputMarkup($sliderID);
                    } else {
                        $operations->previewOutput($sliderID);
                    }

                    break;
                case "toggle_slide_state":
                    $currentState = $slide->toggleSlideStatFromData($data);
                    self::ajaxResponseData(array("state" => $currentState));

                    break;
                case "toggle_hero_slide":
                    $currentHero = $slider->setHeroSlide($data);
                    self::ajaxResponseSuccess($modules->l('Slide is now the new active Hero Slide'));

                    break;
                case "slide_lang_operation":
                    $responseData = $slide->doSlideLangOperation($data);
                    self::ajaxResponseData($responseData);

                    break;
                case "update_general_settings":
                    $operations->updateGeneralSettings($data);
                    self::ajaxResponseSuccess($modules->l("General settings updated"));

                    break;
                case "update_posts_sortby":
                    $slider->updatePostsSortbyFromData($data);
                    self::ajaxResponseSuccess($modules->l("Sortby updated"));

                    break;
                case "replace_image_urls":
                    $slider->replaceImageUrlsFromData($data);
                    self::ajaxResponseSuccess($modules->l("Image urls replaced"));

                    break;
                case "reset_slide_settings":
                    $slider->resetSlideSettings($data);
                    self::ajaxResponseSuccess($modules->l("Settings in all Slides changed"));

                    break;
                case "activate_purchase_code":
                    $result = false;

                    if (!empty($data['code'])) {
                        $result = $operations->checkPurchaseVerification($data);
                    } else {
                        RbSliderFunctions::throwError(
                            $modules->l('The API key, the Purchase Code and the Username need to be set!')
                        );

                        exit();
                    }

                    if ($result) {
                        self::ajaxResponseSuccessRedirect(
                            $modules->l("Purchase Code Successfully Activated"),
                            self::getViewUrl(self::VIEW_SLIDERS)
                        );
                    } else {
                        RbSliderFunctions::throwError($modules->l('Purchase Code is invalid'));
                    }

                    break;
                case "deactivate_purchase_code":
                    $result = $operations->doPurchaseDeactivation($data);

                    if ($result) {
                        self::ajaxResponseSuccessRedirect(
                            $modules->l("Successfully removed validation"),
                            self::getViewUrl(self::VIEW_SLIDERS)
                        );
                    } else {
                        RbSliderFunctions::throwError($modules->l('Could not remove Validation!'));
                    }

                    break;
                case 'dismiss_notice':
                    updateOption('rbslider-valid-notice', 'false');
                    self::ajaxResponseSuccess('.');

                    break;
                case 'dismiss_dynamic_notice':
                    $notices_discarded = getOption('rbslider-notices-dc', array());
                    $notices_discarded[] = (trim($data['id']));
                    updateOption('rbslider-notices-dc', $notices_discarded);
                    self::ajaxResponseSuccess(".");

                    break;
                case "update_text":
                    self::updateSettingsText();
                    self::ajaxResponseSuccess($modules->l("All files successfully updated"));

                    break;
                case 'toggle_favorite':
                    if (@Rbthemeslider::getIsset($data['id']) && (int) ($data['id']) > 0) {
                        $return = self::toggleFavoriteById($data['id']);

                        if ($return === true) {
                            self::ajaxResponseSuccess($modules->l('Setting Changed!'));
                        } else {
                            $error = $return;
                        }
                    } else {
                        $error = $modules->l('No ID given');
                    }

                    self::ajaxResponseError($error);

                    break;
                case "subscribe_to_newsletter":
                    if (@Rbthemeslider::getIsset($data['email']) && !empty($data['email'])) {
                        $return = ThemePunchNewsletter::subscribe($data['email']);

                        if ($return !== false) {
                            if (!@Rbthemeslider::getIsset($return['status']) || $return['status'] === 'error') {
                                $error = (@Rbthemeslider::getIsset($return['message']) &&
                                    !empty($return['message'])) ? $return['message'] :
                                $modules->l('Invalid Email');

                                self::ajaxResponseError($error);
                            } else {
                                self::ajaxResponseSuccess(
                                    $modules->l("Success! Please check your Emails to finish the subscription"),
                                    $return
                                );
                            }
                        } else {
                            self::ajaxResponseError($modules->l(
                                'Invalid Email/Could not connect to the Newsletter server'
                            ));
                        }
                    } else {
                        self::ajaxResponseError($modules->l('No Email given'));
                    }

                    break;
                case "unsubscribe_to_newsletter":
                    if (@Rbthemeslider::getIsset($data['email']) && !empty($data['email'])) {
                        $return = ThemePunchNewsletter::unsubscribe($data['email']);

                        if ($return !== false) {
                            if (!@Rbthemeslider::getIsset($return['status']) || $return['status'] === 'error') {
                                $error = (@Rbthemeslider::getIsset($return['message']) &&
                                !empty($return['message'])) ? $return['message'] :
                                $modules->l('Invalid Email');

                                self::ajaxResponseError($error);
                            } else {
                                self::ajaxResponseSuccess(
                                    $modules->l("Success! Please check your Emails to finish the process"),
                                    $return
                                );
                            }
                        } else {
                            self::ajaxResponseError($modules->l(
                                'Invalid Email/Could not connect to the Newsletter server'
                            ));
                        }
                    } else {
                        self::ajaxResponseError($modules->l('No Email given'));
                    }

                    break;
                case 'change_specific_navigation':
                    $nav = new RbSliderNavigation();
                    $found = false;
                    $navigations = $nav->getAllNavigations();

                    foreach ($navigations as $navig) {
                        if ($data['id'] == $navig['id']) {
                            $found = true;
                            break;
                        }
                    }

                    if ($found) {
                        $nav->createUpdateNavigation($data, $data['id']);
                    } else {
                        $nav->createUpdateNavigation($data);
                    }

                    self::ajaxResponseSuccess(
                        $modules->l('Navigation saved/updated'),
                        array('navs' => $nav->getAllNavigations())
                    );

                    break;
                case 'change_navigations':
                    $nav = new RbSliderNavigation();
                    $nav->createUpdateFullNavigation($data);

                    self::ajaxResponseSuccess(
                        $modules->l('Navigations updated'),
                        array('navs' => $nav->getAllNavigations())
                    );

                    break;
                case 'delete_navigation':
                    $nav = new RbSliderNavigation();

                    if (@Rbthemeslider::getIsset($data) && (int) ($data) > 0) {
                        $return = $nav->deleteNavigation($data);

                        if ($return !== true) {
                            self::ajaxResponseError($return);
                        } else {
                            self::ajaxResponseSuccess(
                                $modules->l('Navigation deleted'),
                                array('navs' => $nav->getAllNavigations())
                            );
                        }
                    }

                    self::ajaxResponseError($modules->l('Wrong ID given'));

                    break;
                case "get_facebook_photosets":
                    if (!empty($data['url'])) {
                        $facebook = new RbSliderFacebook();

                        $return = $facebook->getPhotoSetPhotosOptions(
                            $data['url'],
                            $data['album'],
                            $data['app_id'],
                            $data['app_secret']
                        );

                        if (!empty($return)) {
                            self::ajaxResponseSuccess(
                                $modules->l('Successfully fetched Facebook albums'),
                                array('html' => implode(' ', $return))
                            );
                        } else {
                            $error = $modules->l('Could not fetch Facebook albums');
                            self::ajaxResponseError($error);
                        }
                    } else {
                        self::ajaxResponseSuccess(
                            $modules->l('Cleared Albums'),
                            array('html' => implode(' ', $return))
                        );
                    }

                    break;
                case "get_flickr_photosets":
                    if (!empty($data['url']) && !empty($data['key'])) {
                        $flickr = new RbSliderFlickr($data['key']);
                        $user_id = $flickr->get_user_from_url($data['url']);
                        $return = $flickr->get_photo_sets($user_id, $data['count'], $data['set']);

                        if (!empty($return)) {
                            self::ajaxResponseSuccess(
                                $modules->l('Successfully fetched flickr photosets'),
                                array("data" => array('html' => implode(' ', $return)))
                            );
                        } else {
                            $error = $modules->l('Could not fetch flickr photosets');
                            self::ajaxResponseError($error);
                        }
                    } else {
                        if (empty($data['url']) && empty($data['key'])) {
                            self::ajaxResponseSuccess(
                                $modules->l('Cleared Photosets'),
                                array('html' => implode(' ', $return))
                            );
                        } elseif (empty($data['url'])) {
                            $error = $modules->l('No User URL - Could not fetch flickr photosets');
                            self::ajaxResponseError($error);
                        } else {
                            $error = $modules->l('No API KEY - Could not fetch flickr photosets');
                            self::ajaxResponseError($error);
                        }
                    }

                    break;
                case "get_youtube_playlists":
                    if (!empty($data['id'])) {
                        $youtube = new RbSliderYoutube(trim($data['api']), trim($data['id']));
                        $return = $youtube->get_playlist_options($data['playlist']);

                        self::ajaxResponseSuccess(
                            $modules->l('Successfully fetched YouTube playlists'),
                            array("data" => array('html' => implode(' ', $return)))
                        );
                    } else {
                        $error = $modules->l('Could not fetch YouTube playlists');
                        self::ajaxResponseError($error);
                    }

                    break;
                case 'rs_get_store_information':
                    $code = getOption('rbslider-code', '');
                    $shop_version = RbSliderTemplate::SHOP_VERSION;
                    $validated = getOption('rslider-valid');

                    if ($validated == 'false') {
                        $api_key = '';
                        $username = '';
                        $code = '';
                    }

                    $rattr = array(
                        'code' => urlencode($code),
                        'product' => urlencode('rbslider'),
                        'shop_version' => urlencode($shop_version),
                        'version' => urlencode(RbSliderGlobals::SLIDER_RBISION)
                    );

                    $request = psRemoteFopen('#');
                    $response = '';

                    if ($request !== false) {
                        $response = Tools::jsonDecode(@$request, true);
                    }

                    self::ajaxResponseData(array("data" => $response));

                    break;

                case 'delete_uploaded_image':
                    self::deleteUploadedFile($data);

                    break;
                case 'get_uploaded_images':
                    self::getUploadedFilesJson();

                    break;
                default:
                    self::ajaxResponseError("wrong ajax action: $action ");

                    break;
            }
        } catch (Exception $e) {
            $message = $e->getMessage();

            if ($action == "preview_slide" || $action == "preview_slider") {
                echo $message;

                exit();
            }

            self::ajaxResponseError($message);
        }

        exit();
    }
}
