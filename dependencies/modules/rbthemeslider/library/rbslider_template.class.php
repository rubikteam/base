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

class RbSliderTemplate
{
    private $templates_url = 'http://templates.themepunch.tools/';
    private $templates_list = 'rbslider/get-list.php';
    private $templates_download = 'rbslider/download.php';
    private $templates_server_path = '/rbslider/images/';
    private $templates_path = '/views/img/rbtemplates/';
    private $templates_path_plugin = 'admin/assets/imports/';
    const SHOP_VERSION = '1.1.0';

    public function __construct()
    {
        $this->modules = new Rbthemeslider();
    }

    /**
     * Get the Templatelist from servers
     * @since: 5.0.5
     */
    public function getTemplateList($force = false)
    {
        $ps_version = _RB_VERSION_;
        $psdb = rbDbClass::rbDbInstance();
        $last_check = getOption('rbslider-templates-check');

        if ($last_check == false) { //first time called
            $last_check = 172801;
            updateOption('rbslider-templates-check', time());
        }

        if (time() - $last_check > 345600 || $force == true) {
            updateOption('rbslider-templates-check', time());
            $validated = Configuration::get('rbslider-valid');
            $code = Configuration::get('rbslider-code');
            $shop_version = self::SHOP_VERSION;

            if ($validated == 'false') {
                $code = '';
            }

            $rattr = array(
                'code' => $code,
                'shop_version' => urlencode($shop_version),
                'version' => urlencode(RbSliderGlobals::SLIDER_RBISION),
                'product' => urlencode('rbslider_prestashop')
            );

            $request = psRemoteFopen(
                $this->templates_url . $this->templates_list,
                array(
                    'user-agent' => 'PrestaShop/' . $ps_version . '; ',
                    'body' => $rattr
                )
            );

            if ($request !== false) {
                if ($response = maybeUnserialize($request)) {

                    $templates = Tools::jsonDecode($response, true);

                    if (is_array($templates)) {
                        $is_exist = $psdb->getRow(
                            "SELECT * FROM `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
                            WHERE `name`='rbslider_templates_premium_new'"
                        );

                        foreach ($templates['slider'] as &$template) {
                            if (isset($template['plugin_require']) && !empty($template['plugin_require'])) {
                                $template['plugin_require'] = Tools::jsonDecode($template['plugin_require'], true);
                            }

                            if(isset($template['description']) && $template['description']){
                                $template['description'] = str_replace("'", '&#39;', $template['description']);
                            }
                        }

                        $serialized_data = serialize($templates);

                        if ($is_exist) {
                            $psdb->query(
                                "UPDATE `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
                                SET `value`='" . $serialized_data . "'
                                WHERE `name`='rbslider_templates_premium_new';"
                            );
                        } else {
                            $psdb->query(
                                "INSERT INTO `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME .
                                "` (`id`, `name`, `value`) VALUES (NULL, 'rbslider_templates_premium_new',
                                '" . $serialized_data . "');"
                            );
                        }
                    }
                }
            }

            $this->updateTemplateList();
        }
    }

    /**
     * Update the Templatelist, move rs-templates-new into rs-templates
     * @since: 5.0.5
     */
    private function updateTemplateList()
    {
        $psdb = rbDbClass::rbDbInstance();

        $new = $psdb->getRow(
            "SELECT * FROM `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
            WHERE `name`='rbslider_templates_premium_new'"
        );

        $cur = $psdb->getRow(
            "SELECT * FROM `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
            WHERE `name`='rbslider_templates_premium'"
        );

        $new = ($new) ? $new['value'] : serialize(array());
        $cur = ($cur) ? $cur['value'] : serialize(array());
        $new = unserialize($new);
        $cur = unserialize($cur);

        if ($new !== false && !empty($new) && is_array($new)) {
            if (empty($cur)) {
                $cur = $new;
            } else {
                if (@Rbthemeslider::getIsset($new['slider']) && is_array($new['slider'])) {
                    foreach ($new['slider'] as $n) {
                        $found = false;

                        if (@Rbthemeslider::getIsset($cur['slider']) && is_array($cur['slider'])) {
                            foreach ($cur['slider'] as $ck => $c) {
                                if ($c['uid'] == $n['uid']) {
                                    if (version_compare($c['version'], $n['version'], '<')) {
                                        $n['is_new'] = true;
                                        $n['push_image'] = true; //push to get new image and replace
                                    }

                                    if (@Rbthemeslider::getIsset($c['is_new'])) {
                                        $n['is_new'] = true;
                                    }

                                    $n['exists'] = true;
                                    $cur['slider'][$ck] = $n;
                                    $found = true;

                                    break;
                                }
                            }
                        }

                        if (!$found) {
                            $n['exists'] = true;
                            $cur['slider'][] = $n;
                        }
                    }

                    foreach ($cur['slider'] as $ck => $c) {
                        if (!@Rbthemeslider::getIsset($c['exists'])) {
                            unset($cur['slider'][$ck]);
                        } else {
                            unset($cur['slider'][$ck]['exists']);
                        }
                    }

                    $cur['slides'] = $new['slides'];
                }
            }

            $is_exist = $psdb->getRow(
                "SELECT * FROM `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
                WHERE `name`='rbslider_templates_premium'"
            );

            $serialized_data = serialize($cur);

            if ($is_exist) {
                $psdb->query(
                    "UPDATE `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
                    SET `value`='" . $serialized_data . "'
                    WHERE `name`='rbslider_templates_premium';"
                );
            } else {
                $psdb->query(
                    "INSERT INTO `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME .
                    "` (`id`, `name`, `value`) VALUES (NULL, 'rbslider_templates_premium', '" . $serialized_data . "');"
                );
            }

            $is_exist_new = $psdb->getRow(
                "SELECT * FROM `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
                WHERE `name`='rbslider_templates_premium'"
            );

            if ($is_exist_new) {
                $psdb->query(
                    "UPDATE `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
                    SET `value`='false' WHERE `name`='rbslider_templates_premium_new';"
                );
            } else {
                $psdb->query(
                    "INSERT INTO `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
                    (`id`, `name`, `value`) VALUES (NULL, 'rbslider_templates_premium_new', 'false');"
                );
            }

            $this->updateImages();
        }
    }

    /**
     * Remove the is_new attribute which shows the "update available" button
     * @since: 5.0.5
     */
    public function removeIsNew($uid)
    {
        $cur = getOption('rs-templates', array());

        if (@Rbthemeslider::getIsset($cur['slider']) && is_array($cur['slider'])) {
            foreach ($cur['slider'] as $ck => $c) {
                if ($c['uid'] == $uid) {
                    unset($cur['slider'][$ck]['is_new']);

                    break;
                }
            }
        }

        updateOption('rs-templates', $cur);
    }

    /**
     * Update the Images get them from Server and check for existance on each image
     * @since: 5.0.5
     */
    private function updateImages()
    {
        $psdb = rbDbClass::rbDbInstance();

        $templates = $psdb->getRow(
            "SELECT * FROM `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
            WHERE `name`='revslider_templates_premium'"
        );

        $templates = (@Rbthemeslider::getIsset($templates['value']) &&
            !empty($templates['value'])) ? unserialize($templates['value']) : array();

        $reload = array();

        if (!empty($templates) && is_array($templates)) {
            if (!empty($templates['slider']) && is_array($templates['slider'])) {
                foreach ($templates['slider'] as $key => $temp) {
                    $file = PS_CONTENT_DIR . $this->templates_path . $temp['img'];

                    if (!file_exists($file)) {
                        $image_data = @Tools::file_get_contents(
                            $this->templates_url . $this->templates_server_path . $temp['img']
                        );

                        if ($image_data !== false) {
                            $reload[$temp['alias']] = true;
                            @mkdir(dirname($file));
                            @file_put_contents($file, $image_data);
                        }
                    }
                }
            }

            if (!empty($templates['slides']) && is_array($templates['slides'])) {
                foreach ($templates['slides'] as $key => $temp) {
                    foreach ($temp as $k => $tvalues) {
                        $file = PS_CONTENT_DIR . $this->templates_path . '/' . $tvalues['img'];

                        if (!file_exists($file)) {
                            $image_data = @Tools::file_get_contents(
                                $this->templates_url . $this->templates_server_path . $tvalues['img']
                            );

                            if ($image_data !== false) {
                                @mkdir(dirname($file));
                                @file_put_contents($file, $image_data);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Copy a Slide to the Template Slide list
     * @since: 5.0
     */
    public function copySlideToTemplates($slide_id, $slide_title, $slide_settings = array())
    {
        if ((int) ($slide_id) == 0) {
            return false;
        }

        $slide_title = sanitizeTextField($slide_title);

        if (Tools::strlen(trim($slide_title)) < 3) {
            return false;
        }

        $psdb = Rbthemeslider::$psdb;

        $table_name = RbSliderGlobals::$table_slides;
        $duplicate = $psdb->getRow("SELECT * FROM $table_name WHERE id = $slide_id", ARRAY_A);

        if (empty($duplicate)) { // slide not found
            return false;
        }

        unset($duplicate['id']);
        $duplicate['slider_id'] = -1;
        $duplicate['slide_order'] = -1;
        $params = Tools::jsonDecode($duplicate['params'], true);
        $settings = Tools::jsonDecode($duplicate['settings'], true);
        $params['title'] = $slide_title;
        $params['state'] = 'published';

        if (@Rbthemeslider::getIsset($slide_settings['width'])) {
            $settings['width'] = (int) ($slide_settings['width']);
        }

        if (@Rbthemeslider::getIsset($slide_settings['height'])) {
            $settings['height'] = (int) ($slide_settings['height']);
        }

        $duplicate['params'] = Tools::jsonEncode($params);
        $duplicate['settings'] = Tools::jsonEncode($settings);
        $response = $psdb->insert($table_name, $duplicate);

        if ($response) {
            return true;
        }

        return false;
    }

    /**
     * Get all Template Slides
     * @since: 5.0
     */
    public function getTemplateSlides()
    {
        $psdb = Rbthemeslider::$psdb;
        $table_name = RbSliderGlobals::$table_slides;
        $templates = $psdb->getResults("SELECT * FROM $table_name WHERE slider_id = -1", ARRAY_A);

        //add default Template Slides here!
        $default = $this->getDefaultTemplateSlides();
        $templates = array_merge($templates, $default);

        if (!empty($templates)) {
            foreach ($templates as $key => $template) {
                $templates[$key]['params'] = Tools::jsonDecode($template['params'], true);
                $templates[$key]['layers'] = Tools::jsonDecode($template['layers'], true);
                $templates[$key]['settings'] = Tools::jsonDecode($template['settings'], true);
            }
        }

        return $templates;
    }

    /**
     * Add default Template Slides that can't be deleted for example. Authors can add their own Slides here through Filter
     * @since: 5.0
     */
    private function getDefaultTemplateSlides()
    {
        $templates = array();
        $templates = $templates;

        return $templates;
    }

    /**
     * get default ThemePunch default Slides
     * @since: 5.0
     */
    public function getThemePunchTemplateSlides($sliders = false)
    {
        $psdb = Rbthemeslider::$psdb;
        $templates = array();
        $slide_defaults = array(); //

        if ($sliders == false) {
            $sliders = $this->getThemePunchTemplateSliders();
        }

        $table_name = RbSliderGlobals::$table_slides;

        if (!empty($sliders)) {
            foreach ($sliders as $slider) {
                $slides = $this->getThemePunchTemplateDefaultSlides($slider['alias']);

                if (!@Rbthemeslider::getIsset($slider['installed'])) {
                    $templates = array_merge(
                        $templates,
                        $psdb->getResults(
                            sprintf(
                                "SELECT * FROM $table_name WHERE slider_id = %d", (int)$slider['id']
                            ),
                            ARRAY_A
                        )
                    );
                } else {
                    $templates = array_merge($templates, $slides);
                }

                if (!empty($templates)) {
                    foreach ($templates as $key => $tmpl) {
                        if (@Rbthemeslider::getIsset($slides[$key])) {
                            $templates[$key]['img'] = $slides[$key]['img'];
                        }
                    }
                }
            }
        }

        if (!empty($templates)) {
            foreach ($templates as $key => $template) {
                if (!@Rbthemeslider::getIsset($template['installed'])) {
                    $template['params'] = (@Rbthemeslider::getIsset($template['params'])) ? $template['params'] : '';
                    $template['layers'] = (@Rbthemeslider::getIsset($template['layers'])) ? $template['layers'] : '';
                    $template['settings'] = (@Rbthemeslider::getIsset($template['settings'])) ? $template['settings'] : '';
                    $templates[$key]['params'] = Tools::jsonDecode($template['params'], true);
                    $templates[$key]['layers'] = Tools::jsonDecode($template['layers'], true);
                    $templates[$key]['settings'] = Tools::jsonDecode($template['settings'], true);
                }
            }
        }

        return $templates;
    }

    /**
     * get default ThemePunch default Slides
     * @since: 5.0
     */
    public function getThemePunchTemplateDefaultSlides($slider_alias)
    {
        $psdb = Rbthemeslider::$psdb;

        $templates = $psdb->getRow(
            "SELECT * FROM `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
            WHERE `name`='rbslider_templates_premium'"
        );

        $templates = (@Rbthemeslider::getIsset($templates['value']) &&
            !empty($templates['value'])) ? unserialize($templates['value']) : array();
        $slides = (@Rbthemeslider::getIsset($templates['slides']) &&
            !empty($templates['slides'])) ? $templates['slides'] : array();

        return (@Rbthemeslider::getIsset($slides[$slider_alias])) ? $slides[$slider_alias] : array();
    }

    /**
     * Get default Template Sliders
     * @since: 5.0
     */
    public function getDefaultTemplateSliders()
    {
        $psdb = Rbthemeslider::$psdb;
        $sliders = array();
        $check = array();
        $table_name = RbSliderGlobals::$table_sliders;

        //add themepunch default Sliders here
        $check = $psdb->getResults("SELECT * FROM $table_name WHERE type = 'template'", ARRAY_A);

        if (!empty($check) && !empty($sliders)) {
            foreach ($sliders as $key => $the_sliders) {
                foreach ($the_sliders as $skey => $slider) {
                    foreach ($check as $ikey => $installed) {
                        if ($installed['alias'] == $slider['alias']) {
                            $img = $slider['img'];
                            $sliders[$key][$skey] = $installed;
                            $sliders[$key][$skey]['img'] = $img;
                            $sliders[$key]['version'] = (@Rbthemeslider::getIsset($slider['version'])) ? $slider['version'] : '';

                            if (@Rbthemeslider::getIsset($slider['is_new'])) {
                                $sliders[$key]['is_new'] = true;
                            }

                            $preview = (@Rbthemeslider::getIsset($slider['preview'])) ? $slider['preview'] : false;

                            if ($preview !== false) {
                                $sliders[$key]['preview'] = $preview;
                            }

                            break;
                        }
                    }
                }
            }
        }

        return $sliders;
    }

    /**
     * get default ThemePunch default Sliders
     * @since: 5.0
     */
    public function getThemePunchTemplateSliders()
    {
        $psdb = Rbthemeslider::$psdb;
        $sliders = array();
        $table_name = RbSliderGlobals::$table_sliders;

        //add themepunch default Sliders here
        $sliders = $psdb->getResults("SELECT * FROM $table_name WHERE type = 'template'", ARRAY_A);

        $defaults = $psdb->getRow(
            "SELECT * FROM `" . $psdb->prefix . RbSliderGlobals::TABLE_RBSLIDER_OPTIONS_NAME . "`
            WHERE `name`='rbslider_templates_premium'"
        );

        $defaults = ($defaults) ? unserialize($defaults['value']) : array();
        $defaults = (@Rbthemeslider::getIsset($defaults['slider'])) ? $defaults['slider'] : array();

        if (!empty($sliders)) {
            if (!empty($defaults)) {
                foreach ($defaults as $key => $slider) {
                    foreach ($sliders as $ikey => $installed) {
                        if ($installed['alias'] == $slider['alias']) {
                            $img = $slider['img'];
                            $preview = (@Rbthemeslider::getIsset($slider['preview'])) ? $slider['preview'] : false;
                            $defaults[$key] = $installed;
                            $defaults[$key]['img'] = $img;
                            $defaults[$key]['version'] = $slider['version'];
                            $defaults[$key]['cat'] = $slider['cat'];
                            $defaults[$key]['filter'] = $slider['filter'];

                            if (@Rbthemeslider::getIsset($slider['is_new'])) {
                                $defaults[$key]['is_new'] = true;
                                $defaults[$key]['zip'] = $slider['zip'];
                                $defaults[$key]['width'] = $slider['width'];
                                $defaults[$key]['height'] = $slider['height'];
                                $defaults[$key]['uid'] = $slider['uid'];
                            }

                            if ($preview !== false) {
                                $defaults[$key]['preview'] = $preview;
                            }
                            break;
                        }
                    }
                }
            }
        }

        return $defaults;
    }

    /**
     * check if image was uploaded, if yes, return path or url
     * @since: 5.0.5
     */
    public function checkFilePath($image, $url = false)
    {
        $upload_dir = PS_CONTENT_DIR . $this->templates_path;
        $file = $upload_dir . '/' . $image;

        if (file_exists($file)) {
            $image = _MODULE_DIR_ . 'rbthemeslider' . $this->templates_path . $image;
        } else {
            $image = false;
        }

        return $image;
    }

    /**
     * output markup for the import template, the zip was not yet improted
     * @since: 5.0
     */
    public function writeImportTemplateMarkup($template)
    {
        $template['img'] = $this->checkFilePath($template['img'], true);
        $deny = '';

        if (@Rbthemeslider::getIsset($template['required'])) {
            if (Tools::version_compare(RbSliderGlobals::SLIDER_RBISION, $template['required'], '<')) {
                $deny = ' deny_download';
            }
        }

        echo '<div data-src="' . $template['img'] . '" class="template_slider_item_import' . $deny . '"
            data-gridwidth="' . $template['width'] . '"
			data-gridheight="' . $template['height'] . '"
			data-zipname="' . $template['zip'] . '"
			data-uid="' . $template['uid'] . '" ';

        if ($deny !== '') {
            echo 'data-versionneed="' . $template['required'] . '" ';
        }
        echo '>';

        echo '<div class="not-imported-overlay"></div>';
        echo '<div style="position:absolute;top:10px;right:10px;width:35px;text-align:right;z-index:2">				';
        echo '<div class="icon-install_slider"></div>';
        echo '</div>';

        echo '</div>';
        echo '<div style="position:absolute;top:10px;right:50px;width:35px;text-align:right;z-index:2">';

        if (@Rbthemeslider::getIsset($template['preview']) && $template['preview'] !== '') {

            echo '<a class="icon-preview_slider" href="' . $template['preview'] . '" target="_blank"></a>';
        }

        echo '</div>';
    }

    /**
     * output markup for the import template, the zip was not yet imported
     * @since: 5.0
     */
    public function writeImportTemplateMarkupSlide($template)
    {
        $template['img'] = $this->checkFilePath($template['img'], true);
        $deny = '';

        if (@Rbthemeslider::getIsset($template['required'])) {
            if (version_compare(RbSliderGlobals::SLIDER_RBISION, $template['required'], '<')) {
                $deny = ' deny_download';
            }
        }

        echo '<div class="template_slide_item_import">';
        echo '<div class="template_slide_item_img' . $deny . '" ';
        echo 'data-src="' . $template['img'] . '" ';
        echo 'data-gridwidth="' . $template['width'] . '" ';
        echo 'data-gridheight="' . $template['height'] . '" ';
        echo 'data-zipname="' . $template['zip'] . '" ';
        echo 'data-uid="' . $template['uid'] . '"';
        echo 'data-slidenumber="' . $template['number'] . '" ';

        if ($deny !== '') {
            echo 'data-versionneed="' . $template['required'] . '" ';
        }

        echo '>';
        echo '<div class="not-imported-overlay"></div>';
        echo '</div>';
        echo '<div style="position:absolute;top:10px;right:10px;width:100%;text-align:right;z-index:2">';
        echo '<div class="icon-install_slider"></div>';
        echo '</div>';
        echo '<div class="template_title">';
        echo (@Rbthemeslider::getIsset($template['title'])) ? $template['title'] : '';
        echo '</div>';
        echo '</div>';
    }

    /**
     * output markup for template
     * @since: 5.0
     */
    public function writeTemplateMarkup($template, $slider_id = false)
    {
        $params = $template['params'];
        $settings = $template['settings'];
        $slide_id = $template['id'];
        $title = str_replace("'", "", RbSliderBase::getVar($params, 'title', 'Slide'));

        if ($slider_id !== false) {
            $title = '';
        }

        $width = RbSliderBase::getVar($settings, "width", 1240);
        $height = RbSliderBase::getVar($settings, "height", 868);
        $bgType = RbSliderBase::getVar($params, "background_type", "transparent");
        $bgColor = RbSliderBase::getVar($params, "slide_bg_color", "transparent");
        $bgFit = RbSliderBase::getVar($params, "bg_fit", "cover");
        $bgFitX = (int) (RbSliderBase::getVar($params, "bg_fit_x", "100"));
        $bgFitY = (int) (RbSliderBase::getVar($params, "bg_fit_y", "100"));
        $bgPosition = RbSliderBase::getVar($params, "bg_position", "center center");
        $bgPositionX = (int) (RbSliderBase::getVar($params, "bg_position_x", "0"));
        $bgPositionY = (int) (RbSliderBase::getVar($params, "bg_position_y", "0"));
        $bgRepeat = RbSliderBase::getVar($params, "bg_repeat", "no-repeat");
        $bgStyle = ' ';

        if ($bgFit == 'percentage') {
            if ((int) ($bgFitY) == 0 || (int) ($bgFitX) == 0) {
                $bgStyle .= "background-size: cover;";
            } else {
                $bgStyle .= "background-size: " . $bgFitX . '% ' . $bgFitY . '%;';
            }
        } else {
            $bgStyle .= "background-size: " . $bgFit . ";";
        }

        if ($bgPosition == 'percentage') {
            $bgStyle .= "background-position: " . $bgPositionX . '% ' . $bgPositionY . '%;';
        } else {
            $bgStyle .= "background-position: " . $bgPosition . ";";
        }

        $bgStyle .= "background-repeat: " . $bgRepeat . ";";

        if (@Rbthemeslider::getIsset($template['img'])) {
            $thumb = $this->checkFilePath($template['img'], true);
        } else {
            $imageID = RbSliderBase::getVar($params, "image_id");
            if (empty($imageID)) {
                $thumb = RbSliderBase::getVar($params, "image");
                $imgID = RbSliderBase::getImageIdByUrl($thumb);

                if ($imgID !== false) {
                    $thumb = RbSliderFunctionsPS::getUrlAttachmentImage(
                        $imgID,
                        RbSliderFunctionsPS::THUMB_MEDIUM
                    );
                }
            } else {
                $thumb = RbSliderFunctionsPS::getUrlAttachmentImage(
                    $imageID,
                    RbSliderFunctionsPS::THUMB_MEDIUM
                );
            }

            if ($thumb == '') {
                $thumb = RbSliderBase::getVar($params, "image");
            }
        }

        $bg_fullstyle = '';
        $bg_extraClass = '';
        $data_urlImageForView = '';

        if (@Rbthemeslider::getIsset($template['img'])) {
            $data_urlImageForView = 'data-src="' . $thumb . '"';
        } else {
            if ($bgType == 'image' || $bgType == 'vimeo' || $bgType == 'youtube' || $bgType == 'html5') {
                $data_urlImageForView = 'data-src="' . $thumb . '"';
                $bg_fullstyle = ' style="' . $bgStyle . '" ';
            }

            if ($bgType == "solid") {
                $bg_fullstyle = ' style="background-color:' . $bgColor . ';" ';
            }

            if ($bgType == "trans" || $bgType == "transparent") {
                $bg_extraClass = 'mini-transparent';
            }
        }


        echo '<div class="template_slide_single_element" style="display:inline-block">';
        echo '<div ' . $data_urlImageForView . ' class="' . (($slider_id !== false) ? 'template_slider_item' : 'template_item') . ' ' . $bg_extraClass . '" ' . $bg_fullstyle . ' ';
        echo 'data-gridwidth="' . $width . '" ';
        echo 'data-gridheight="' . $height . '" ';
        if ($slider_id !== false) {

            echo 'data-sliderid="' . $slider_id . '"';
        } else {

            echo 'data-slideid="' . $slide_id . '"';
        }


        echo '>';

        echo '<div class="not-imported-overlay"></div>			';
        echo '<div style="position:absolute;top:10px;right:10px;width:35px;text-align:right;z-index:2"><div class="icon-add_slider"></div></div>';

        echo '</div>';
        echo '<div style="position:absolute;top:10px;right:50px;width:35px;text-align:right;z-index:2">				';
        if (@Rbthemeslider::getIsset($template['preview']) && $template['preview'] !== '') {
            echo '<a class="icon-preview_slider" href="' . $template['preview'] . '" target="_blank"></a>';
        }
        echo '</div>';
        if ($slider_id == false) {
            echo '<div class="template_title">' . $title . '</div>';
        }
        echo '</div>';
    }

    /**
     * Download template by UID (also validates if download is legal)
     * @since: 5.0.5
     */
    public function downloadTemplate($uid)
    {
        $ps_version = _PS_VERSION_;

        $uid = esc_attr($uid);

        $code = Configuration::get('rbslider-code');
        $shop_version = self::SHOP_VERSION;
        $validated = Configuration::get('rbslider-valid');

        if ($validated == 'false') {
            $code = '';
        }

        $rattr = array(
            'code' => urlencode($code),
            'shop_version' => urlencode($shop_version),
            'version' => urlencode(RbSliderGlobals::SLIDER_RBISION),
            'uid' => urlencode($uid),
            'product' => urlencode('rbslider_prestashop'),
        );

        $siteurl = Context::getcontext()->shop->getBaseURL();
        $upload_dir = psUploadDir();

        if (psMkdirP($upload_dir['basedir'] . $this->templates_path)) {
            $request = psRemotePost($this->templates_url . $this->templates_download, array(
                'method' => 'POST',
                'user-agent' => 'Prestashop/' . $ps_version . '; ' . $siteurl,
                'body' => $rattr,
                'headers' => array(
                    'Accept-Encoding' => 'deflate;q=1.0, compress;q=0.5, gzip;q=0.5',
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                ),
                'timeout' => 45,
            ));

            if (RbGlobalObject::getIsset($request['body'])) {
                if ($response = $request['body']) {
                    if ($response !== 'invalid') {
                        $file = $upload_dir['basedir'] . $this->templates_path . '/' . $uid . '.zip';
                        @mkdir(dirname($file));
                        $ret = @file_put_contents($file, $response);

                        if ($ret !== false) {
                            return $file;
                        } else {

                            return array(
                                'error' => $this->modules->l(
                                    'Can\'t write the file into the uploads folder of Prestashop,
                                    please change permissions and try again!'
                                )
                            );
                        }
                    }
                }
            }
        } else {
            return array(
                'error' => $this->modules->l(
                    'Can\'t write into the uploads folder of Prestashop, please change permissions and try again!'
                )
            );
        }

        return false;
    }

    /**
     * Delete the Template file
     * @since: 5.0.5
     */
    public function deleteTemplate($uid)
    {
        $uid = esc_attr($uid);
        $upload_dir = psUploadDir();

        if (psMkdirP($upload_dir['basedir'] . $this->templates_path)) {
            $file = $upload_dir['basedir'] . $this->templates_path . '/' . $uid . '.zip';

            if (file_exists($file)) {
                return unlink($file);
            }
        }
        
        return false;
    }
}
