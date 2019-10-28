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

require_once(_PS_MODULE_DIR_.'rbthemedream/include.php');

class AdminRbthemedreamLiveController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->display_header = true;
        parent::__construct();
        $this->context = Context::getContext();
        $this->id_lang = $this->context->language->id;
        $this->name = 'AdminRbthemedreamLive';
    }

    public function renderView()
    {

    }

    public function initContent()
    {
        $this->setMedia();
        $this->initHeader();
    }

    public function setMedia($isNewTheme = false)
    {
        $this->addJquery();
        $this->addJS(_PS_JS_DIR_.'tiny_mce/tinymce.min.js');
        $this->addJqueryPlugin('fancybox');
        $this->addJqueryPlugin('autocomplete');

        $this->addCSS(array(
            __PS_BASE_URI__.$this->admin_webpath.'/themes/'.$this->bo_theme.'/css/admin-theme.css',
            __PS_BASE_URI__.$this->admin_webpath.'/themes/'.$this->bo_theme.'/public/theme.css',
            _MODULE_DIR_.'rbthemedream/views/css/lib/rb-font-awesome.css',
            _MODULE_DIR_.'rbthemedream/views/css/lib/rb-select2.css',
            _MODULE_DIR_.'rbthemedream/views/css/home.css',
            _MODULE_DIR_.'rbthemedream/views/css/view.css',
            _MODULE_DIR_.'rbthemedream/views/css/front.css',
        ));

        $this->addJS(array(
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-core.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-widget.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-mouse.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-sortable.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-resizable.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-position.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-draggable.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-slider.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-touch-punch.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-iris.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-color-picker.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-color-picker-alpha.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-waypoints.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-imagesloaded.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-numerator.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-slick.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-nprogress.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-underscore.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-dialog.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/backbone/backbone-min.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/backbone/backbone.marionette.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/backbone/backbone.radio.min.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-scrollbar.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-tipsy.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-helper.js',
            _MODULE_DIR_.'rbthemedream/views/js/plugin/rb-select2.js',
            _MODULE_DIR_.'rbthemedream/views/js/rb-front.js',
            _MODULE_DIR_.'rbthemedream/views/js/home.js',
        ));

        Media::addJsDef(array(
            'show_new_orders' => Configuration::get('PS_SHOW_NEW_ORDERS'),
            'customer_name_msg' => $this->l('registered'),
            'rbFrontendConfig' => array(
                'isEditMode' => 1,
                'stretchedSectionContainer' =>'',
                'is_rtl' => '',
                'rbBaseUrl' => Tools::safeOutput($this->context->shop->getBaseURL(true, true)),
            ),
            'wpColorPickerL10n' => array(
                'clear' => $this->l('Clear'),
                'defaultString' => $this->l('Default'),
                'pick' => $this->l('Pick a color'),
                'current' => $this->l('Current color')
            ),
        ));

        Hook::exec('actionAdminControllerSetMedia');
    }

    public function display()
    {
        ob_start();
        parent::display();
        ob_end_clean();

        $type = Tools::getValue('page');
        $data = '';
        $id = '';

        switch ($type) {
            case 'home':
                $id = 'id_rbthemedream_home';
                $id_rbthemedream_home = Tools::getValue('id_rbthemedream_home');
                $home = new RbthemedreamHome($id_rbthemedream_home, $this->id_lang);
                $data = Tools::jsonDecode($home->data, true);
            break;
        }

        $colors = new SchemeColor();
        $fonts = new RbTypography();
        $columns = new RbColumn();
        $columns = $columns->getDataColumn();

        $sections = new RbSection();
        $sections = $sections->getDataSection();

        $controls = new RbWidget();
        $controls = $controls->getControlsData();

        $headings = new RbHeading();
        $images = new RbImage();
        $text_editor = new RbTextEditor();
        $videos = new RbVideo();
        $buttons = new RbButton();
        $banners = new RbBanner();
        $dividers = new RbDivider();
        $spacer = new RbSpacer();
        $image_box = new RbImageBox();
        $map = new RbMap();
        $icon_box = new RbIconBox();
        $image_carousel = new RbImageCarousel();
        $image_hotspot = new RbImageHotspots();
        $counter = new RbCounter();
        $progress = new RbProgress();
        $testimonial = new RbTestimonial();
        $rb_tab = new RbTabs();
        $accordion = new RbAccordion();
        $toggle = new RbToggle();
        $instagram = new RbInstagram();
        $social_icon = new RbSocialIcons();
        $alert = new RbAlert();
        $html = new RbHtml();
        $brands = new RbBrands();
        $rb_product = new RbProduct();
        $rb_product_tab = new RbProductTab();
        $rb_slider = new RbSliderDD();
        $rb_menu = new RbMenu();
        $newsletter = new RbNewsletter();
        $blog = new RbBlog();
        $rb_module = new RbModule();
        $rb_tpl = new RbTpl();

        $this->context->smarty->assign(array(
            'type' => 'rb_change',
        ));

        Media::addJsDef(array(
            'html' =>   $this->module->fetch(
                'module:rbthemedream/views/templates/admin/widget/rb-base.tpl'
            ),
            'text_editor' => $this->module->l('Text Editor'),
            'brand' => $this->module->l('List Brands'),
            'product' => $this->module->l('List Product'),
            'product_tab' => $this->module->l('List Product Tab'),
            'image_carousel' => $this->module->l('Slider'),
            'menu' => $this->module->l('Menu'),
            'newsletter' => $this->module->l('Newsletter'),
            'blog' => $this->module->l('Blog'),
            'rb_module' => $this->module->l('Module'),
            'rb_tpl' => $this->module->l('Tpl'),
            'map' => $this->module->l('Map'),
            'accordion' => $this->module->l('Accordion'),
            'testimonial' => $this->module->l('Testimonial'),
            'video' => $this->module->l('Video'),
            'rb_instagram' => $this->module->l('Instagram'),
            'rbConfig' => array(
                'ajaxurl' => $this->context->link->getAdminLink('AdminRbthemedreamLive').'&ajax=1',
                'view_link' => $this->context->shop->getBaseURL(true, true) . 'view?page='.$type.'&'.$id.'='. Tools::getValue($id),
                'elements_categories' => array(
                    'basic' => array(
                        'title' => $this->l('Widget'),
                        'icon' => 'font',
                    ),
                    'prestashop' => array(
                        'title' => $this->l('Prestashop'),
                        'icon' => 'prestashop',
                    ),
                ),
                'controls' => $controls,
                'elements' => array(
                    'column' => $columns,
                    'section' => $sections
                ),
                'widgets' => array(
                    'heading' => $headings->getDataHeading(),
                    'image' => $images->getDataImage(),
                    'text-editor' => $text_editor->getDataTextEditor(),
                    'video' => $videos->getDataVideo(),
                    'button' => $buttons->getDataButton(),
                    'banner' => $banners->getDataBanner(),
                    'divider' => $dividers->getDataDivider(),
                    'spacer' => $spacer->getDataSpacer(),
                    'image-box' => $image_box->getDataImageBox(),
                    'google_maps' => $map->getDataMap(),
                    'icon-box' => $icon_box->getDataIconBox(),
                    'image-carousel' => $image_carousel->getDataImageCarousel(),
                    'image-hotspots' => $image_hotspot->getDataImageHotspot(),
                    'counter' => $counter->getDataCounter(),
                    'progress' => $progress->getDataProgress(),
                    'testimonial' => $testimonial->getDataTestimonial(),
                    'tabs' => $rb_tab->getDataTab(),
                    'accordion' => $accordion->getDataAccordion(),
                    'toggle' => $toggle->getDataToggle(),
                    'instagram' => $instagram->getDataInstagram(),
                    'social-icons' => $social_icon->getDataSocialIcons(),
                    'alert' => $alert->getDataAlert(),
                    'html' => $html->getDataHtml(),
                    'prestashop-widget-Brands' => $brands->getDataBrands(),
                    'prestashop-widget-ProductsList' => $rb_product->getDataProduct(),
                    'prestashop-widget-ProductsListTabs' => $rb_product_tab->getDataProductTab(),
                    'prestashop-widget-Rbthemeslider' => $rb_slider->getDataSlider(),
                    'prestashop-widget-Menu' => $rb_menu->getDataMenu(),
                    'prestashop-widget-Newsletter' => $newsletter->getDataNewsletter(),
                    'prestashop-widget-Blog' => $blog->getDataBlog(),
                    'prestashop-widget-modules' => $rb_module->getDataModule(),
                    'prestashop-widget-CustomTpl' => $rb_tpl->getDataTpl(),
                ),
                'schemes' => array(
                    'items' => array(
                        'color' => array(
                            'title' => 'Colors',
                            'disabled_title'=> $this->l('Color Palettes'),
                            'items' => $colors->getColorDefault(),
                        ),
                        'typography' => array(
                            'title' => 'Typography',
                            'disabled_title' => 'Default Fonts',
                            'items' => $fonts->getFontDefault(),
                        ),
                    ),
                    'enabled_schemes' => array(),
                ),
                'default_schemes' => array(
                    'color' => array(
                        'title' => 'Colors',
                        'items' => $colors->color_value,
                    ),
                    'typography' => array(
                        'title' => 'Typography',
                        'items' => $fonts->font_value,
                    ),
                ),
                'system_schemes' => '',
                'wp_live' => '<div class="wp-core-ui wp-live-wrap html-active" id="wp-rbwplive-wrap"><div class="wp-live-container" id="wp-rbwplive-live-container"><textarea class="rb-wp-live wp-live-area" cols="40" id="rbwplive" name="rbwplive" rows="15">%%EDITORCONTENT%%</textarea></div></div> ',
                'post_id' => Tools::getValue($id),
                'page_type' => 'home',
                'languages' => $this->context->controller->getLanguages(),
                'id_lang' => $this->context->language->id,
                'post_permalink' => '',
                'edit_post_link' => $this->context->link->getAdminLink('AdminRbthemedreamHome', true).
                '&updaterbthemedream_home&id_rbthemedream_home='.Tools::getValue('id_rbthemedream_home'),
                'rb_site' => '',
                'help_the_content_url' => '',
                'maintance_url_settings' =>  $this->context->link->getAdminLink('AdminMaintenance'),
                'assets_url' => $this->module->getPathUri().'views/',
                'data' => $data,
                'is_rtl' => '',
                'introduction' => array(
                    'active' => true,
                    'title' => '<div id="rb-introduction-title">' .
                    $this->l( 'Two Minute Tour') .
                    '</div><div id="rb-introduction-subtitle">' .
                    $this->l( 'Watch this quick tour that gives you a basic understanding of how to use module') .
                    '</div>',
                    'content' => '<div class="rb-video-wrapper"><iframe src="" frameborder="0" allowfullscreen></iframe></div>',
                    'delay' => 2500,
                    'version' => 1,
                ),
                'viewportBreakpoints' => array(
                    'xs' => 0,
                    'sm' => 576,
                    'md' => 768,
                    'lg' => 992,
                    'xl' => 1200,
                ),
                'dd' => array(
                    'rb' => $this->l('Rb'),
                    'dialog_confirm_delete' => $this->l('Are you want to remove this?').' {0}',
                    'dialog_user_taken_over' => '{0} '.$this->l('Are you want to take over this page editing?'),
                    'delete' =>  $this->l('Delete'),
                    'cancel' =>  $this->l('Cancel'),
                    'delete_element' => $this->l('Delete').' {0}',
                    'take_over' => $this->l('Take Over'),
                    'go_back' => $this->l('Go Back'),
                    'saved' => $this->l('Saved'),
                    'before_unload_alert' => $this->l('All unsaved changes will be lost.'),
                    'edit_element' => $this->l('Edit').' {0}',
                    'global_colors' => $this->l('Global Colors'),
                    'global_fonts' => $this->l('Global Fonts'),
                    'about_rb' => $this->l('About Rb'),
                    'clear_page' => $this->l('Delete all content'),
                    'dialog_confirm_clear_page' => $this->l('Are you shure you want delete all content?'),
                    'changes_lost' => $this->l('You have unsaved changes!'),
                    'dialog_confirm_changes_lost' => $this->l('Please return and save, otherwise your changes will be lost.'),
                    'import_language_dialog_title' => $this->l('Erase content and import'),
                    'import_language_dialog_msg' => $this->l('Please confirm that you want to erase content of this page and import content of other language'),
                    'inner_section' => $this->l('Columns'),
                    'dialog_confirm_gallery_delete' => $this->l('Are you sure you want to reset this gallery?'),
                    'delete_gallery' => $this->l('Reset Gallery'),
                    'gallery_images_selected' => '{0}' . $this->l('Images Selected'),
                    'insert_media' => $this->l('Insert Media'),
                    'preview_el_not_found_header' => $this->l('Preview not found'),
                    'preview_el_not_found_message' => $this->l('Make sure you added own ip in Maintenance settings (Backoffice > shop parameters > general > maintenance)'),
                    'learn_more' => $this->l('Learn more'),
                    'ie_edge_browser' => $this->l('Builder do not support IE/Edge browsers'),
                    'ie_edge_browser_info' => $this->l('Please edit your layout in different browser, like Chrome, Firefox, Opera or Safari'),
                    'an_error_occurred' => $this->l('An error occurred'),
                    'templates_request_error' => $this->l('The following error occurred when processing the request:'),
                    'save_your_template' => $this->l('Save Your {0} to Library'),
                    'load_your_template' => $this->l('Load your template from file'),
                    'page' => $this->l('Page'),
                    'section' => $this->l('Section'),
                    'delete_template' => $this->l('Delete Template'),
                    'delete_template_confirm' => $this->l('Are you want to delete this template?'),
                ),
            ),
        ));

        $scheme_fields_keys = array('font_family', 'font_weight');
        $typography_fields =  RbTypography::rbGetFields();
        $scheme_fields = array_intersect_key($typography_fields, array_flip($scheme_fields_keys));
        $font_systems = RbTypography::rbGetFontsGroups(array(RbTypography::SYSTEM));
        $font_googles = RbTypography::rbGetFontsGroups(array(RbTypography::GOOGLE, RbTypography::EARLYACCESS));

        $animations = array(
            'Fading' => array(
                'fadeIn' => 'Fade In',
                'fadeInDown' => 'Fade In Down',
                'fadeInLeft' => 'Fade In Left',
                'fadeInRight' => 'Fade In Right',
                'fadeInUp' => 'Fade In Up',
            ),
            'Zooming' => array(
                'zoomIn' => 'Zoom In',
                'zoomInDown' => 'Zoom In Down',
                'zoomInLeft' => 'Zoom In Left',
                'zoomInRight' => 'Zoom In Right',
                'zoomInUp' => 'Zoom In Up',
            ),
            'Bouncing' => array(
                'bounceIn' => 'Bounce In',
                'bounceInDown' => 'Bounce In Down',
                'bounceInLeft' => 'Bounce In Left',
                'bounceInRight' => 'Bounce In Right',
                'bounceInUp' => 'Bounce In Up',
            ),
            'Sliding' => array(
                'slideInDown' => 'Slide In Down',
                'slideInLeft' => 'Slide In Left',
                'slideInRight' => 'Slide In Right',
                'slideInUp' => 'Slide In Up',
            ),
            'Rotating' => array(
                'rotateIn' => 'Rotate In',
                'rotateInDownLeft' => 'Rotate In Down Left',
                'rotateInDownRight' => 'Rotate In Down Right',
                'rotateInUpLeft' => 'Rotate In Up Left',
                'rotateInUpRight' => 'Rotate In Up Right',
            ),
            'Attention Seekers' => array(
                'bounce' => 'Bounce',
                'flash' => 'Flash',
                'pulse' => 'Pulse',
                'rubberBand' => 'Rubber Band',
                'shake' => 'Shake',
                'headShake' => 'Head Shake',
                'swing' => 'Swing',
                'tada' => 'Tada',
                'wobble' => 'Wobble',
                'jello' => 'Jello',
            ),
            'Light Speed' => array(
                'lightSpeedIn' => 'Light Speed In',
            ),
            'Specials' => array(
                'rollIn' => 'Roll In',
            ),
        );

        $hover_animations = array(
            'grow' => 'Grow',
            'shrink' => 'Shrink',
            'pulse' => 'Pulse',
            'pulse-grow' => 'Pulse Grow',
            'pulse-shrink' => 'Pulse Shrink',
            'push' => 'Push',
            'pop' => 'Pop',
            'bounce-in' => 'Bounce In',
            'bounce-out' => 'Bounce Out',
            'rotate' => 'Rotate',
            'grow-rotate' => 'Grow Rotate',
            'fade-out-20' => 'Fade-out 20%',
            'float' => 'Float',
            'sink' => 'Sink',
            'bob' => 'Bob',
            'hang' => 'Hang',
            'skew' => 'Skew',
            'skew-forward' => 'Skew Forward',
            'skew-backward' => 'Skew Backward',
            'wobble-vertical' => 'Wobble Vertical',
            'wobble-horizontal' => 'Wobble Horizontal',
            'wobble-to-bottom-right' => 'Wobble To Bottom Right',
            'wobble-to-top-right' => 'Wobble To Top Right',
            'wobble-top' => 'Wobble Top',
            'wobble-bottom' => 'Wobble Bottom',
            'wobble-skew' => 'Wobble Skew',
            'buzz' => 'Buzz',
        );

        $this->context->smarty->assign(array(
            'js_def_vars' => Media::getJsDef(),
            'baseDir' => __PS_BASE_URI__.basename(_PS_ADMIN_DIR_).'/',
            'schemes' => SchemeColor::getSystemScheme(),
            'print_colors_index' => array(
                SchemeColor::COLOR_1,
                SchemeColor::COLOR_2,
                SchemeColor::COLOR_3,
                SchemeColor::COLOR_4,
            ),
            'colors_to_print' => array(),
            'scheme_fields' => $scheme_fields,
            'font_systems' => $font_systems,
            'font_googles' => $font_googles,
            'dimensions' => array(
                'top' => $this->l('Top'),
                'right' => $this->l('Right'),
                'bottom' => $this->l('Bottom'),
                'left' => $this->l('Left'),
            ),
            'sliders' => array(
                array(
                    'label' => $this->l('Blur'),
                    'type' => 'blur',
                    'min' => 0,
                    'max' => 100,
                ),
                array(
                    'label' => $this->l('Spread'),
                    'type' => 'spread',
                    'min' => 0,
                    'max' => 100,
                ),
                array(
                    'label' => $this->l('Horizontal'),
                    'type' => 'horizontal',
                    'min' => -100,
                    'max' => 100,
                ),
                array(
                    'label' => $this->l('Vertical'),
                    'type' => 'vertical',
                    'min' => -100,
                    'max' => 100,
                )
            ),
            'animations' => $animations,
            'hover_animations' => $hover_animations,
        ));

        $this->smartyOutputContent(_PS_MODULE_DIR_ .'/rbthemedream/views/templates/admin/live_edit_home.tpl');
    }

    public function ajaxProcessRenderWidget()
    {
        die();
    }

    public function ajaxProcessSaveLive()
    {
        header('Content-Type: application/json');

        $pageId = (int) Tools::getValue('page_id');
        $pageType = Tools::getValue('page_type');
        $data =  $this->getJsonValue('data');
        $idLang = (int) Tools::getValue('id_lang');

        switch ($pageType) {
            case 'home':
                $home = new RbthemedreamHome($pageId, $idLang);
                $home->data = $data;
                $home->update();
                // $this->module->clearHomeCache();
                break;
            case 'cms':
                if ($data == '[]'){
                    $data = '';
                }
                $cms = new CMS($pageId);
                $cms->content[$idLang] = $data;
                $cms->update();
                break;
            case 'blog':
                $blogPost = new SimpleBlogPost($pageId);
                $blogPost->content[$idLang] = $data;
                $blogPost->update();
                break;
            case 'category':
                $id = IqitElementorCategory::getIdByCategory($pageId);
                if ($id){
                    $category = new IqitElementorCategory($id, $idLang);
                    $category->data = $data;
                    $category->update();
                } else {
                    $category = new IqitElementorCategory(null, $idLang);
                    $category->data = $data;
                    $category->id_category = (int) $pageId;
                    $category->add();
                }
                $this->module->clearCategoryCache($pageId);
                break;
            case 'product':
                $id = IqitElementorProduct::getIdByProduct($pageId);
                if ($id){
                    $product = new IqitElementorProduct($id, $idLang);
                    $product->data = $data;
                    $product->update();
                } else {
                    $product = new IqitElementorProduct(null, $idLang);
                    $product->data = $data;
                    $product->id_product = (int) $pageId;
                    $product->add();
                }
                $this->module->clearProductCache($pageId);
                break;
        }

        $return = array(
            'success' => true
        );

        die(json_encode($return));
    }

    public function ajaxProcessSearchProducts()
    {
        $query = Tools::getValue('q', false);

        if (!$query or $query == '' or Tools::strlen($query) < 1) {
            die();
        }

        if ($pos = strpos($query, ' (ref:')) {
            $query = Tools::substr($query, 0, $pos);
        }

        $excludeIds = Tools::getValue('excludeIds', false);

        if ($excludeIds && $excludeIds != 'NaN') {
            $excludeIds = implode(',', array_map('intval', explode(',', $excludeIds)));
        } else {
            $excludeIds = '';
        }
        
        $excludeVirtuals = false;
        $exclude_packs = false;
        $context = Context::getContext();
        $sql = 'SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`, image.`id_image` id_image, il.`legend`, p.`cache_default_attribute`
        FROM `' . _DB_PREFIX_ . 'product` p
        ' . Shop::addSqlAssociation('product', 'p') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (pl.id_product = p.id_product AND pl.id_lang = ' . (int)$context->language->id . Shop::addSqlRestrictionOnLang('pl') . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'image` image
        ON (image.`id_product` = p.`id_product` AND image.cover=1)
        LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int)$context->language->id . ')
        WHERE (pl.name LIKE \'%' . pSQL($query) . '%\' OR p.reference LIKE \'%' . pSQL($query) . '%\') AND p.`active` = 1' .
            (!empty($excludeIds) ? ' AND p.id_product NOT IN (' . $excludeIds . ') ' : ' ') .
            ($excludeVirtuals ? 'AND NOT EXISTS (SELECT 1 FROM `' . _DB_PREFIX_ . 'product_download` pd WHERE (pd.id_product = p.id_product))' : '') .
            ($exclude_packs ? 'AND (p.cache_is_pack IS NULL OR p.cache_is_pack = 0)' : '') .
            ' GROUP BY p.id_product';

        $items = Db::getInstance()->executeS($sql);

        if ($items) {
            $results = array();
            foreach ($items as $item) {
                $product = array(
                    'id' => (int)($item['id_product']),
                    'name' => $item['name'],
                    'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                    'image' => str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], $item['id_image'], ImageType::getFormattedName('small'))),
                );
                array_push($results, $product);
            }
            $results = array_values($results);
            die(json_encode($results));
        } else {
            die(json_encode(new stdClass));
        }
    }

    public function getJsonValue($key, $default_value = false)
    {
        if (!isset($key) || empty($key) || !is_string($key)) {
            return false;
        }

        if (getenv('kernel.environment') === 'test' && self::$request instanceof Request) {
            $value = self::$request->request->get($key, self::$request->query->get($key, $default_value));
        } else {
            $value = '';

            if (Tools::getIsset($key)) {
               $value = Tools::getValue($key);
            }  else {
                $value = $default_value;
            }
        }

        if (is_string($value)) {
            return urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($value)));
        }

        return $value;
    }
}
