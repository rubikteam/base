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

$ext_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg');
$ext = array_merge($ext_img);
$upload_dir = '';
$current_path = '';
$thumbs_base_path = '';
$default_view = 0;
$transliteration = false;
$default_language = "en";
$aviary_active = false;
$loading_bar = true;
$MaxSizeUpload = 100;
$aviary_key = "dvh8qudbp6yx2bnp";
$aviary_version = 3;
$aviary_language = 'en';
$duplicate_files = false;
$base_url = '';
$file_number_limit_js = 500;
$upload_files = true;
$java_upload = false;
$create_folders = false;
$show_sorting_bar = true;

include_once('config/config.php');

$iso = '';

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__));
}

$up_media_url = _MODULE_DIR_ . "rbthemeslider/views/";
$plugins_url = _MODULE_DIR_ . "rbthemeslider/";
$views_urls = controllerUploadUrl('&view=dialog');
$upload_urls = controllerUploadUrl('&view=upload');
$ajax_calls_url = $up_media_url . "ajax_calls.php";
$hash = Tools::encrypt(GlobalsRbSlider::MODULE_NAME);
$tablename = _DB_PREFIX_ . 'rbslider_attachment_images';
$url = uploadsUrl();

$tablename = _DB_PREFIX_ . GlobalsRbSlider::TABLE_ATTACHMENT_IMAGES;
$totalRows = Db::getInstance()->getValue("SELECT COUNT(*) FROM {$tablename}");
$perPage = 30;

$totalPages = ceil($totalRows / $perPage);
$startFrom = Tools::getValue('page') && (int) Tools::getValue('page') > 1 ?
((int) Tools::getValue('page') - 1) * $perPage : 0;

$results = UniteBaseAdminClassRb::getUploadedFilesResult($perPage, $startFrom);

$context = Context::getContext();

$context->cookie->__set('verifysmartupload', "RESPONSIVEfilemanager");

if (Tools::isSubmit('submit')) {
    include($upload_urls);
} else {
    include('include/utils.php');

    if (Tools::isSubmit('fldr') && Tools::getValue('fldr') && preg_match('/\.{1,2}[\/|\\\]/', urldecode(Tools::getValue('fldr'))) === 0
    ) {
        $subdir = urldecode(trim(Tools::getValue('fldr'), '/') . '/');
    } else {
        $subdir = '';
    }


    setcookie('last_position', $subdir, time() + (86400 * 7));

    if ($subdir == '') {
        if (@Rbthemeslider::getIsset($context->cookie->last_position) &&
            !empty($context->cookie->last_position) &&
            strpos($context->cookie->last_position, '.') === false
        ) {
            $subdir = trim($context->cookie->last_position);
        }
    }

    if ($subdir == '/') {
        $subdir = '';
    }

    if (!@Rbthemeslider::getIsset($context->cookie->subfolder)) {
        $context->cookie->__set('subfolder', '');
    }

    $subfolder = '';

    if (!empty($context->cookie->subfolder) &&
        strpos($context->cookie->subfolder, '../') === false &&
        strpos($context->cookie->subfolder, './') === false &&
        strpos($context->cookie->subfolder, '/') !== 0 &&
        strpos($context->cookie->subfolder, '.') === false
    ) {
        $subfolder = $context->cookie->subfolder;
    }

    if ($subfolder != '' && $subfolder[Tools::strlen($subfolder) - 1] != '/') {
        $subfolder .= '/';
    }

    if (!file_exists($current_path . $subfolder . $subdir)) {
        $subdir = '';
        if (!file_exists($current_path . $subfolder . $subdir)) {
            $subfolder = '';
        }
    }

    if (trim($subfolder) == '') {
        $cur_dir = $upload_dir . $subdir;
        $cur_path = $current_path . $subdir;
        $thumbs_path = $thumbs_base_path;
        $parent = $subdir;
    } else {
        $cur_dir = $upload_dir . $subfolder . $subdir;
        $cur_path = $current_path . $subfolder . $subdir;
        $thumbs_path = $thumbs_base_path . $subfolder;
        $parent = $subfolder . $subdir;
    }

    $cycle = true;
    $max_cycles = 50;
    $i = 0;

    while ($cycle && $i < $max_cycles) {
        $i++;
        if ($parent == './') {
            $parent = '';
        }
        if (file_exists($current_path . $parent . 'config.php')) {
            require_once($current_path . $parent . 'config.php');
            $cycle = false;
        }

        if ($parent == '') {
            $cycle = false;
        } else {
            $parent = fix_dirname($parent) . '/';
        }
    }

    if (!is_dir($thumbs_path . $subdir)) {
        create_folder(false, $thumbs_path . $subdir);
    }

    if (Tools::getValue('popup')) {
        $popup = Tools::getValue('popup');
    } else {
        $popup = 0;
    }

    $popup = !!$popup;

    if (!@Rbthemeslider::getIsset($context->cookie->view_type)) {
        $view = $default_view;
        $context->cookie->__set('view_type', $view);
    }
    if (Tools::getValue('view')) {
        $view = Tools::getValue('view');
        $context->cookie->__set('view_type', $view);
    }
    $view = $context->cookie->view_type;

    if (Tools::getValue('filter')) {
        $filter = fix_filename(Tools::getValue('filter'), $transliteration);
    } else {
        $filter = '';
    }

    if (!@Rbthemeslider::getIsset($context->cookie->sort_by)) {
        $context->cookie->__set('sort_by', '');
    }
    if (Tools::getValue('sort_by')) {
        $sort_by = fix_filename(Tools::getValue('sort_by'), $transliteration);
        $context->cookie->__set('sort_by', $sort_by);
    } else {
        $sort_by = $context->cookie->sort_by;
    }

    if (!@Rbthemeslider::getIsset($context->cookie->descending)) {
        $context->cookie->__set('descending', false);
    }
    if (Tools::getValue('descending')) {
        $descending = fix_filename(Tools::getValue('descending'), $transliteration) === 'true';
        $context->cookie->__set('descending', $descending);
    } else {
        $descending = $context->cookie->descending;
    }

    $lang = $default_language;

    if (Tools::getValue('lang') && Tools::getValue('lang') != 'undefined' && Tools::getValue('lang') != '') {
        $lang = Tools::getValue('lang');
    }

    $language_file = 'lang/' . $default_language . '.php';

    if ($lang != $default_language) {
        $path_parts = pathinfo($lang);
        if (is_readable('lang/' . $path_parts['basename'] . '.php')) {
            $language_file = 'lang/' . $path_parts['basename'] . '.php';
        } else {
            $lang = $default_language;
        }
    }

    require_once $language_file;

    $sdstype = Tools::getValue('type');
    $sdsfield_id = Tools::getValue('field_id') ? (int) Tools::getValue('field_id') : '';

    if (!Tools::getValue('type')) {
        $sdstype = 0;
    }

    $get_params = http_build_query(
        array(
            'type' => Tools::safeOutput($sdstype),
            'lang' => Tools::safeOutput($lang),
            'popup' => $popup,
            'field_id' => $sdsfield_id,
            'fldr' => ''
        )
    );
    $sds_admin_url = adminUrl();

    ?><!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8" />


            <script type="text/javascript">
                var g_urlContent = "<?php echo UniteFunctionsPSRb::getUrlContent() ?>";
                var g_uniteDirPlagin = "rbthemeslider";
                var ajaxurl = "<?php echo Context::getContext()->link->getAdminLink('AdminRbthemesliderAjax'); ?>";
                ajaxurl += '&returnurl=<?php echo urlencode(htmlspecialchars_decode($sds_admin_url)) ?>';
            </script>
            <link rel="shortcut icon" href="img/ico/favicon.ico">
            <link href="<?php echo $up_media_url;

    ?>css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo $up_media_url;

    ?>css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo $up_media_url;

    ?>css/bootstrap-lightbox.min.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo $up_media_url;

    ?>css/style.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo $up_media_url;

              ?>css/dropzone.min.css" type="text/css" rel="stylesheet"/>
            <link href="<?php echo $up_media_url;

    ?>css/jquery.contextMenu.min.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo $up_media_url;

              ?>css/bootstrap-modal.min.css" rel="stylesheet" type="text/css"/>

            <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>				


            <script type="text/javascript" src="<?php echo $up_media_url;

    ?>js/bootstrap.min.js"></script>
            <script type="text/javascript" src="<?php echo $up_media_url;

        ?>js/bootstrap-lightbox.min.js"></script>
            <script type="text/javascript" src="<?php echo $up_media_url;

    ?>js/dropzone.min.js"></script>
            <script type="text/javascript" src="<?php echo $up_media_url;

        ?>js/jquery.touchSwipe.min.js"></script>
            <script type="text/javascript" src="<?php echo $up_media_url;

        ?>js/modernizr.custom.js"></script>

            <script type="text/javascript" src="<?php echo $up_media_url;

    ?>js/bootstrap-modal.min.js"></script>
            <script type="text/javascript" src="<?php echo $up_media_url;

        ?>js/bootstrap-modalmanager.min.js"></script>

            <script type="text/javascript" src="<?php echo $up_media_url;

        ?>js/imagesloaded.pkgd.min.js"></script>
            <script type="text/javascript" src="<?php echo $up_media_url;

        ?>js/jquery.queryloader2.min.js"></script>
            <script type="text/javascript" src="<?php echo $up_media_url;

        ?>js/js/jquery-ui/jquery-ui-1.10.3.custom.js"></script> 
            <script type="text/javascript" src="<?php echo $up_media_url; ?>js/js/admin.js"></script>

            <style>
                #main,.page-sidebar #content
                {
                    padding:0;
                    margin:0;
                }
                .modal-scrollable,.modal-backdrop,#footer,.bootstrap.panel,.bootstrap .page-head,#footer,#header,#nav-sidebar,#top_container #header,#top_container #footer,#top_container #content > .table
                {
                    display: none;
                }
                #top_container #main
                {
                    min-height: 0;
                    height: auto;
                }
                #image_size{
                    font: 400 12px/1.42857 "Open Sans",Helvetica,Arial,sans-serif;
                }
            </style>
            <?php
            if ($aviary_active) {
                if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {

                    ?>
                    <script type="text/javascript" src="https://dme0ih8comzn4.cloudfront.net/js/feather.js"></script>
            <?php
        } else {

            ?>
                    <script type="text/javascript" src="http://feather.aviary.com/js/feather.js "></script>
                    <?php
                }
            }

            ?>

            <script src="<?php echo $up_media_url;

            ?>js/jquery.contextMenu.min.js" type="text/javascript"></script>

            <script type="text/javascript">

                var ext_img = new Array('<?php echo implode("','", $ext_img) ?>');
                var allowed_ext = new Array('<?php echo implode("','", $ext) ?>');
                var loading_bar =<?php echo $loading_bar ? "true" : "false"; ?>;
                var image_editor =<?php echo $aviary_active ? "true" : "false"; ?>;
                //dropzone config
                Dropzone.options.myAwesomeDropzone = {
                    dictInvalidFileType: "<?php echo lang_Error_extension; ?>",
                    dictFileTooBig: "<?php echo lang_Error_Upload; ?>",
                    dictResponseError: "SERVER ERROR",
                    paramName: "file", // The name that will be used to transfer the file
                    maxFilesize: <?php echo $MaxSizeUpload; ?>, // MB
                    url: "<?php echo $upload_urls; ?>",
                    accept: function(file, done) {
                        var extension = file.name.split('.').pop();
                        extension = extension.toLowerCase();
                        if ($.inArray(extension, allowed_ext) > -1) {
                            done();
                        }
                        else {
                            done("<?php echo lang_Error_extension; ?>");
                        }
                    }
                };
                if (image_editor) {
                    var featherEditor = new Aviary.Feather({
                        apiKey: "<?php echo $aviary_key ?>",
                        apiVersion: <?php echo $aviary_version ?>,
                        language: "<?php echo $aviary_language ?>",
                        theme: 'light',
                        tools: 'all',
                        onSave: function(imageID, newURL) {
                            show_animation();
                            var img = document.getElementById(imageID);
                            img.src = newURL;
                            $.ajax({
                                type: "POST",
                                url: "<?php echo $ajax_calls_url; ?>?action=save_img",
                                data: {url: newURL, path: $('#sub_folder').val() + $('#fldr_value').val(), name: $('#aviary_img').data('name')}
                            }).done(function(msg) {
                                featherEditor.close();
                                d = new Date();
                                $("figure[data-name='" + $('#aviary_img').data('name') + "']").find('img').each(function() {
                                    $(this).attr('src', $(this).attr('src') + "?" + d.getTime());
                                });
                                $("figure[data-name='" + $('#aviary_img').data('name') + "']").find('figcaption a.preview').each(function() {
                                    $(this).data('url', $(this).data('url') + "?" + d.getTime());
                                });
                                hide_animation();
                            });
                            return false;
                        },
                        onError: function(errorObj) {
                            bootbox.alert(errorObj.message);
                        }

                    });
                }
            </script>
            <script>
                var ajax_calls_url = "<?php $ajax_calls_url; ?>";
            </script>

            <script type="text/javascript" src="<?php echo $up_media_url;

               ?>js/include.js"></script>
        </head>
        <body>
            <input type="hidden" id="popup" value="<?php echo Tools::safeOutput($popup);

            ?>"/>
            <input type="hidden" id="view" value="<?php echo Tools::safeOutput($view);

               ?>"/>
            <input type="hidden" id="cur_dir" value="<?php echo Tools::safeOutput($cur_dir);

            ?>"/>
            <input type="hidden" id="cur_dir_thumb" value="<?php echo Tools::safeOutput($subdir);

        ?>"/>
            <input type="hidden" id="insert_folder_name" value="<?php echo Tools::safeOutput(lang_Insert_Folder_Name);

        ?>"/>
            <input type="hidden" id="new_folder" value="<?php echo Tools::safeOutput(lang_New_Folder);

            ?>"/>
            <input type="hidden" id="ok" value="<?php echo Tools::safeOutput(lang_OK);

               ?>"/>
            <input type="hidden" id="cancel" value="<?php echo Tools::safeOutput(lang_Cancel);

            ?>"/>
            <input type="hidden" id="rename" value="<?php echo Tools::safeOutput(lang_Rename);

               ?>"/>
            <input type="hidden" id="lang_duplicate" value="<?php echo Tools::safeOutput(lang_Duplicate);

            ?>"/>
            <input type="hidden" id="duplicate" value="<?php
               if ($duplicate_files) {
                   echo 1;
               } else {
                   echo 0;
               }

            ?>"/>
            <input type="hidden" id="base_url" value="<?php echo Tools::safeOutput($base_url) ?>"/>
            <input type="hidden" id="base_url_true" value="<?php echo baseUrl();

               ?>"/>
            <input type="hidden" id="fldr_value" value="<?php echo Tools::safeOutput($subdir);

               ?>"/>
            <input type="hidden" id="sub_folder" value="<?php echo Tools::safeOutput($subfolder);

            ?>"/>
            <input type="hidden" id="file_number_limit_js" value="<?php echo Tools::safeOutput($file_number_limit_js);

            ?>"/>
            <input type="hidden" id="descending" value="<?php echo $descending ? "true" : "false";

            ?>"/>
    <?php $protocol = 'http';

    ?>
            <input type="hidden" id="current_url" value="<?php echo str_replace(array('&filter=' . $filter), array(''), $protocol . "://" . $_SERVER['HTTP_HOST'] . Tools::safeOutput($_SERVER['REQUEST_URI']));

    ?>"/>
            <input type="hidden" id="lang_show_url" value="<?php echo Tools::safeOutput(lang_Show_url);

    ?>"/>
            <input type="hidden" id="lang_extract" value="<?php echo Tools::safeOutput(lang_Extract);

                              ?>"/>
            <input type="hidden" id="lang_file_info" value="<?php echo fixStrtoupper(lang_File_info);

                              ?>"/>
            <input type="hidden" id="lang_edit_image" value="<?php echo Tools::safeOutput(lang_Edit_image);

                              ?>"/>
            <input type="hidden" id="transliteration" value="<?php echo $transliteration ? "true" : "false";

    ?>"/>
                                           <?php if ($upload_files) {

                                               ?>


                <div class="uploader">
                    <center>
                        <button class="btn btn-inverse close-uploader">
                            <i class="icon-backward icon-white"></i> <?php echo Tools::safeOutput(lang_Return_Files_List) ?></button>
                    </center>
                    <div class="space10"></div>
                    <div class="space10"></div>
                                    <?php if ($java_upload) {

                                        ?>
                        <div class="tabbable upload-tabbable"> <!-- Only required for left/right tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab"><?php echo Tools::safeOutput(lang_Upload_base);

                            ?></a></li>
                                <li><a href="#tab2" id="uploader-btn" data-toggle="tab"><?php echo Tools::safeOutput(lang_Upload_java);

                            ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                <?php
                            }

                            ?>
                                <form action="<?php echo $views_urls;

                            ?>" method="post" enctype="multipart/form-data" id="myAwesomeDropzone" class="dropzone">
                                    <input type="hidden" name="path" value="<?php echo Tools::safeOutput($subfolder . $subdir);

                            ?>"/>
                                    <input type="hidden" name="path_thumb" value="<?php echo Tools::safeOutput($subfolder . $subdir);

                            ?>"/>

                                    <div class="fallback">
                    <?php echo lang_Upload_file ?>:<br/>
                                        <input name="file" type="file"/>
                                        <input type="hidden" name="fldr" value="<?php echo Tools::safeOutput($subdir);

                    ?>"/>
                                        <input type="hidden" name="view" value="<?php echo Tools::safeOutput($view);

                    ?>"/>
                                        <input type="hidden" name="type" value="<?php echo Tools::safeOutput(Tools::getValue('type'));

                    ?>"/>
                                        <input type="hidden" name="field_id" value="<?php echo (int) Tools::getValue('field_id');

                    ?>"/>
                                        <input type="hidden" name="popup" value="<?php echo Tools::safeOutput($popup);

                    ?>"/>
                                        <input type="hidden" name="lang" value="<?php echo Tools::safeOutput($lang);

                    ?>"/>
                                        <input type="hidden" name="filter" value="<?php echo Tools::safeOutput($filter);

                    ?>"/>
                                        <input type="submit" name="submit" value="<?php echo lang_OK ?>"/>
                                    </div>
                                </form>
                                <div class="upload-help"><?php echo Tools::safeOutput(lang_Upload_base_help);

                    ?></div>
                    <?php if ($java_upload) {

                        ?>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <div id="iframe-container">

                                    </div>
                                    <div class="upload-help"><?php echo Tools::safeOutput(lang_Upload_java_help);

                        ?></div>
                                </div>
                        <?php
                    }

                    ?>
                        </div>
                    </div>

                </div>

                    <?php
                }

                ?>
            <div class="container-fluid">

                <?php
                $class_ext = '';
                $src = '';

                if (Tools::getValue('type') == 1) {
                    $apply = 'apply_img';
                } elseif (Tools::getValue('type') == 2) {
                    $apply = 'apply_link';
                } elseif (Tools::getValue('type') == 0 && Tools::getValue('field_id') == '') {
                    $apply = 'apply_none';
                } elseif (Tools::getValue('type') == 3) {
                    $apply = 'apply_video';
                } else {
                    $apply = 'apply';
                }

                $files = scandir($current_path . $subfolder . $subdir);
                $n_files = count($files);

                //php sorting
                $sorted = array();
                $current_folder = array();
                $prev_folder = array();
                foreach ($files as $k => $file) {
                    if ($file == ".") {
                        $current_folder = array('file' => $file);
                    } elseif ($file == "..") {
                        $prev_folder = array('file' => $file);
                    } elseif (is_dir($current_path . $subfolder . $subdir . $file)) {
                        $date = filemtime($current_path . $subfolder . $subdir . $file);
                        $size = foldersize($current_path . $subfolder . $subdir . $file);
                        $file_ext = lang_Type_dir;
                        $sorted[$k] = array('file' => $file, 'date' => $date, 'size' => $size, 'extension' => $file_ext);
                    } else {
                        $file_path = $current_path . $subfolder . $subdir . $file;
                        $date = filemtime($file_path);
                        $size = filesize($file_path);
                        $file_ext = Tools::substr(strrchr($file, '.'), 1);
                        $sorted[$k] = array('file' => $file, 'date' => $date, 'size' => $size, 'extension' => $file_ext);
                    }
                }

                function filenameSort($x, $y)
                {
                    return $x['file'] < $y['file'];
                }

                function dateSort($x, $y)
                {
                    return $x['date'] < $y['date'];
                }

                function sizeSort($x, $y)
                {
                    return $x['size'] - $y['size'];
                }

                function extensionSort($x, $y)
                {
                    return $x['extension'] < $y['extension'];
                }
                switch ($sort_by) {
                    case 'name':
                        usort($sorted, 'filenameSort');
                        break;
                    case 'date':
                        usort($sorted, 'dateSort');
                        break;
                    case 'size':
                        usort($sorted, 'sizeSort');
                        break;
                    case 'extension':
                        usort($sorted, 'extensionSort');
                        break;
                    default:
                        break;
                }

                if ($descending) {
                    $sorted = array_reverse($sorted);
                }

                $files = array_merge(array($prev_folder), array($current_folder), $sorted);

                ?>

                <div class="navbar navbar-fixed-top">
                    <div class="navbar-inner">
                        <div class="container-fluid">
                            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <div class="brand"><?php echo Tools::safeOutput(lang_Toolbar);

                                        ?> -></div>
                            <div class="nav-collapse collapse">
                                <div class="filters">
                                    <div class="row-fluid">
                                        <div class="span3 half">
                                            <span><?php echo Tools::safeOutput(lang_Actions);?>:</span>
                                            <?php
                                                if ($upload_files) {
                                            ?>
                                                <button class="tip btn upload-btn" title="<?php echo Tools::safeOutput(lang_Upload_file);?>">
                                                    <svg width="15px" height="15px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 455 455" style="enable-background:new 0 0 455 455;" xml:space="preserve"><polygon points="455,212.5 242.5,212.5 242.5,0 212.5,0 212.5,212.5 0,212.5 0,242.5 212.5,242.5 212.5,455 242.5,455 242.5,242.5 455,242.5 "/><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                                    
                                                    <svg width="15px" height="15px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 59.911 59.911" style="enable-background:new 0 0 59.911 59.911;" xml:space="preserve"><path d="M59.605,23.399c-0.241-0.281-0.593-0.443-0.965-0.443h-2.685V8.723c0-0.975-0.793-1.768-1.768-1.768H23.522l-2.485-4.141c-0.317-0.53-0.898-0.859-1.516-0.859H5.723c-0.975,0-1.768,0.793-1.768,1.768v19.232H1.271c-0.37,0-0.722,0.161-0.963,0.441c-0.242,0.28-0.35,0.651-0.294,1.02l4.918,32.461c0.097,0.625,0.625,1.078,1.256,1.078h47.534c0.632,0,1.16-0.453,1.257-1.081l4.917-32.454C59.953,24.053,59.847,23.681,59.605,23.399z M5.956,3.956h13.434l2.485,4.141c0.317,0.53,0.898,0.859,1.516,0.859h30.565v14h-2v-7h-44v7h-2V3.956z M49.956,22.956h-40v-5h40V22.956z M53.096,55.956H6.815l-4.696-31h1.837h4h44h4h1.837L53.096,55.956z"/><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                                </button>
                                            <?php
                                                }
                                            ?>

                                            <?php
                                                if ($create_folders) {
                                            ?>
                                                <button class="tip btn new-folder" title="<?php echo Tools::safeOutput(lang_New_Folder)?>">
                                                    <i class="icon-plus"></i>
                                                    <i class="icon-folder-open"></i>
                                                </button>
                                            <?php
                                                }
                                            ?>
                                        </div>

                                        <div class="span3 half view-controller">
                                            <span><?php echo lang_View;?>:</span>
                                            <button
                                                class="btn tip
                                                <?php
                                                    if ($view == 0) {
                                                        echo " btn-inverse"; 
                                                    }
                                                ?>"
                                                id="view0"
                                                data-value="0"
                                                title="<?php echo Tools::safeOutput(lang_View_boxes);?>"
                                            >
                                               <svg width="15px" height="15px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 341.333 341.333" style="enable-background:new 0 0 341.333 341.333;" xml:space="preserve"><g><g><g><rect x="128" y="128" width="85.333" height="85.333"/><rect x="0" y="0" width="85.333" height="85.333"/><rect x="128" y="256" width="85.333" height="85.333"/><rect x="0" y="128" width="85.333" height="85.333"/><rect x="0" y="256" width="85.333" height="85.333"/><rect x="256" y="0" width="85.333" height="85.333"/><rect x="128" y="0" width="85.333" height="85.333"/><rect x="256" y="128" width="85.333" height="85.333"/><rect x="256" y="256" width="85.333" height="85.333"/></g></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                            </button>

                                            <button
                                                class="btn tip
                                                <?php
                                                    if ($view == 1) {
                                                        echo " btn-inverse";
                                                    }
                                                ?>"
                                                id="view1"
                                                data-value="1"
                                                title="<?php echo Tools::safeOutput(lang_View_list);?>"
                                            >
                                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 44.776 44.776" style="enable-background:new 0 0 44.776 44.776;" xml:space="preserve"><g><g><g><path d="M39.776,12.766H5c-2.761,0-5-2.239-5-5s2.239-5,5-5h34.776c2.761,0,5,2.239,5,5S42.537,12.766,39.776,12.766z"/></g><g><path d="M39.776,27.388H5c-2.761,0-5-2.239-5-5c0-2.762,2.239-5,5-5h34.776c2.761,0,5,2.238,5,5C44.776,25.149,42.537,27.388,39.776,27.388z"/></g><g><path d="M39.776,42.01H5c-2.761,0-5-2.239-5-5c0-2.762,2.239-5,5-5h34.776c2.761,0,5,2.238,5,5C44.776,39.771,42.537,42.01,39.776,42.01z"/></g></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                            </button>

                                            <button
                                                class="btn tip
                                                <?php
                                                    if ($view == 2) {
                                                        echo " btn-inverse";
                                                    }
                                                ?>"
                                                id="view2"
                                                data-value="2"
                                                title="<?php echo Tools::safeOutput(lang_View_columns_list);?>"
                                            >
                                                <svg id="Layer" enable-background="new 0 0 64 64" height="15px" viewBox="0 0 64 64" width="15px" xmlns="http://www.w3.org/2000/svg"><path d="m50 8h-36c-3.309 0-6 2.691-6 6v36c0 3.309 2.691 6 6 6h36c3.309 0 6-2.691 6-6v-36c0-3.309-2.691-6-6-6zm-38 18h18v12h-18zm22 0h18v12h-18zm18-12v8h-18v-10h16c1.103 0 2 .897 2 2zm-38-2h16v10h-18v-8c0-1.103.897-2 2-2zm-2 38v-8h18v10h-16c-1.103 0-2-.897-2-2zm38 2h-16v-10h18v8c0 1.103-.897 2-2 2z"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row-fluid">
                            <?php
                            $link = $views_urls;
                            ?>
                    <ul class="breadcrumb">
                        <li class="pull-left">
                            <a href="<?php echo Tools::safeOutput($link);?>">
                                <svg width="15px" height="15px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 306.773 306.773" style="enable-background:new 0 0 306.773 306.773;" xml:space="preserve"><g><path style="fill:#010002;" d="M302.93,149.794c5.561-6.116,5.024-15.49-1.199-20.932L164.63,8.898c-6.223-5.442-16.2-5.328-22.292,0.257L4.771,135.258c-6.092,5.585-6.391,14.947-0.662,20.902l3.449,3.592c5.722,5.955,14.971,6.665,20.645,1.581l10.281-9.207v134.792c0,8.27,6.701,14.965,14.965,14.965h53.624c8.264,0,14.965-6.695,14.965-14.965v-94.3h68.398v94.3c-0.119,8.264,5.794,14.959,14.058,14.959h56.828c8.264,0,14.965-6.695,14.965-14.965V154.024c0,0,2.84,2.488,6.343,5.567c3.497,3.073,10.842,0.609,16.403-5.513L302.93,149.794z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                            </a>
                        </li>

                        <li>
                            <span class="divider">/</span>
                        </li>

                        <?php
                            $bc = explode("/", $subdir);
                            $tmp_path = '';

                            if (!empty($bc)) {
                                foreach ($bc as $k => $b) {
                                    $tmp_path .= $b . "/";

                                    if ($k == count($bc) - 2) {

                        ?>
                            <li class="active"><?php echo Tools::safeOutput($b) ?></li>
                            <?php
                                } elseif ($b != "") {
                            ?>
                                <li>
                                    <a href="<?php echo Tools::safeOutput($link . $tmp_path) ?>">
                                        <?php echo Tools::safeOutput($b) ?></a>
                                    </li>
                                    <li><span class="divider"><?php echo "/";?></span>
                                </li>
                        <?php
                                    }
                                }
                            }

                        ?>

                        <li class="pull-right">
                            <a id="refresh" class="btn-small" href="<?php echo Tools::safeOutput($link);?>">
                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 438.529 438.528" style="enable-background:new 0 0 438.529 438.528;" xml:space="preserve"><g><g><path d="M433.109,23.694c-3.614-3.612-7.898-5.424-12.848-5.424c-4.948,0-9.226,1.812-12.847,5.424l-37.113,36.835c-20.365-19.226-43.684-34.123-69.948-44.684C274.091,5.283,247.056,0.003,219.266,0.003c-52.344,0-98.022,15.843-137.042,47.536C43.203,79.228,17.509,120.574,5.137,171.587v1.997c0,2.474,0.903,4.617,2.712,6.423c1.809,1.809,3.949,2.712,6.423,2.712h56.814c4.189,0,7.042-2.19,8.566-6.565c7.993-19.032,13.035-30.166,15.131-33.403c13.322-21.698,31.023-38.734,53.103-51.106c22.082-12.371,45.873-18.559,71.376-18.559c38.261,0,71.473,13.039,99.645,39.115l-39.406,39.397c-3.607,3.617-5.421,7.902-5.421,12.851c0,4.948,1.813,9.231,5.421,12.847c3.621,3.617,7.905,5.424,12.854,5.424h127.906c4.949,0,9.233-1.807,12.848-5.424c3.613-3.616,5.42-7.898,5.42-12.847V36.542C438.529,31.593,436.733,27.312,433.109,23.694z"/><path d="M422.253,255.813h-54.816c-4.188,0-7.043,2.187-8.562,6.566c-7.99,19.034-13.038,30.163-15.129,33.4c-13.326,21.693-31.028,38.735-53.102,51.106c-22.083,12.375-45.874,18.556-71.378,18.556c-18.461,0-36.259-3.423-53.387-10.273c-17.13-6.858-32.454-16.567-45.966-29.13l39.115-39.112c3.615-3.613,5.424-7.901,5.424-12.847c0-4.948-1.809-9.236-5.424-12.847c-3.617-3.62-7.898-5.431-12.847-5.431H18.274c-4.952,0-9.235,1.811-12.851,5.431C1.807,264.844,0,269.132,0,274.08v127.907c0,4.945,1.807,9.232,5.424,12.847c3.619,3.61,7.902,5.428,12.851,5.428c4.948,0,9.229-1.817,12.847-5.428l36.829-36.833c20.367,19.41,43.542,34.355,69.523,44.823c25.981,10.472,52.866,15.701,80.653,15.701c52.155,0,97.643-15.845,136.471-47.534c38.828-31.688,64.333-73.042,76.52-124.05c0.191-0.38,0.281-1.047,0.281-1.995c0-2.478-0.907-4.612-2.715-6.427C426.874,256.72,424.731,255.813,422.253,255.813z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                            </a>
                        </li>

                        <li class="pull-right">
                            <div class="btn-group">
                                <a class="btn dropdown-toggle sorting-btn" data-toggle="dropdown" href="#">
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="0 0 511.627 511.627" style="enable-background:new 0 0 511.627 511.627;" xml:space="preserve"><g><g><path d="M333.584,438.536h-73.087c-2.666,0-4.853,0.855-6.567,2.573c-1.709,1.711-2.568,3.901-2.568,6.564v54.815c0,2.673,0.855,4.853,2.568,6.571c1.715,1.711,3.901,2.566,6.567,2.566h73.087c2.666,0,4.856-0.855,6.563-2.566c1.718-1.719,2.563-3.898,2.563-6.571v-54.815c0-2.663-0.846-4.854-2.563-6.564C338.44,439.392,336.25,438.536,333.584,438.536z"/><path d="M196.54,401.991h-54.817V9.136c0-2.663-0.854-4.856-2.568-6.567C137.441,0.859,135.254,0,132.587,0H77.769c-2.663,0-4.856,0.855-6.567,2.568c-1.709,1.715-2.568,3.905-2.568,6.567v392.855H13.816c-4.184,0-7.04,1.902-8.564,5.708c-1.525,3.621-0.855,6.95,1.997,9.996l91.361,91.365c2.094,1.707,4.281,2.562,6.567,2.562c2.474,0,4.665-0.855,6.567-2.562l91.076-91.078c1.906-2.279,2.856-4.571,2.856-6.844c0-2.676-0.859-4.859-2.568-6.584C201.395,402.847,199.208,401.991,196.54,401.991z"/><path d="M388.4,292.362H260.494c-2.666,0-4.853,0.855-6.567,2.566c-1.71,1.711-2.568,3.901-2.568,6.563v54.823c0,2.662,0.855,4.853,2.568,6.563c1.714,1.711,3.901,2.566,6.567,2.566H388.4c2.666,0,4.855-0.855,6.563-2.566c1.715-1.711,2.573-3.901,2.573-6.563v-54.823c0-2.662-0.858-4.853-2.573-6.563C393.256,293.218,391.066,292.362,388.4,292.362z"/><path d="M504.604,2.568C502.889,0.859,500.702,0,498.036,0H260.497c-2.666,0-4.853,0.855-6.567,2.568c-1.709,1.715-2.568,3.905-2.568,6.567v54.818c0,2.666,0.855,4.853,2.568,6.567c1.715,1.709,3.901,2.568,6.567,2.568h237.539c2.666,0,4.853-0.855,6.567-2.568c1.711-1.714,2.566-3.901,2.566-6.567V9.136C507.173,6.473,506.314,4.279,504.604,2.568z"/><path d="M443.22,146.181H260.494c-2.666,0-4.853,0.855-6.567,2.57c-1.71,1.713-2.568,3.9-2.568,6.567v54.816c0,2.667,0.855,4.854,2.568,6.567c1.714,1.711,3.901,2.57,6.567,2.57H443.22c2.663,0,4.853-0.855,6.57-2.57c1.708-1.713,2.563-3.9,2.563-6.567v-54.816c0-2.667-0.855-4.858-2.563-6.567C448.069,147.04,445.879,146.181,443.22,146.181z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                    <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu pull-left sorting">
                                    <li>
                                        <center><strong><?php echo Tools::safeOutput(lang_Sorting) ?></strong></center>
                                    </li>

                                    <li>
                                        <a
                                            class="sorter sort-name
                                            <?php
                                                if ($sort_by == "name") {
                                                    echo ($descending) ? "descending" : "ascending";
                                                }
                                            ?>"
                                            href="javascript:void('')"
                                            data-sort="name"
                                        >
                                            <?php echo Tools::safeOutput(lang_Filename);?>
                                        </a>
                                    </li>

                                    <li>
                                        <a class="sorter sort-date
                                            <?php
                                                if ($sort_by == "date") {
                                                    echo ($descending) ? "descending" : "ascending";
                                                }
                                            ?>"
                                            href="javascript:void('')"
                                            data-sort="date"
                                        >
                                            <?php echo Tools::safeOutput(lang_Date);?>
                                        </a>
                                    </li>

                                    <li>
                                        <a 
                                            class="sorter sort-size
                                            <?php
                                                if ($sort_by == "size") {
                                                    echo ($descending) ? "descending" : "ascending";
                                                }
                                            ?>"
                                            href="javascript:void('')"
                                            data-sort="size"
                                        >
                                            <?php echo Tools::safeOutput(lang_Size);?>   
                                        </a>
                                    </li>

                                    <li>
                                        <a
                                            class="sorter sort-extension
                                            <?php
                                                if ($sort_by == "extension") {
                                                    echo ($descending) ? "descending" : "ascending";
                                                }
                                            ?>"
                                            href="javascript:void('')"
                                            data-sort="extension"
                                        >
                                            <?php echo Tools::safeOutput(lang_Type);?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="row-fluid ff-container">
                    <div class="span9" >
                                        <?php if (@opendir($current_path . $subfolder . $subdir) === false) {

                                            ?>
                            <br/>
                            <div class="alert alert-error">There is an error! The upload folder there isn't. Check your config.php file.
                            </div>
                                        <?php
                                    } else {

                                        ?>
                            <h4 id="help"><?php echo Tools::safeOutput(lang_Swipe_help);

                                ?></h4>

        <?php if ($show_sorting_bar) {

            ?>
                                <!-- sorter -->
                                <div class="sorter-container <?php echo "list-view" . Tools::safeOutput($view);

            ?>">
                                    <div class="file-name"><a class="sorter sort-name <?php
                                if ($sort_by == "name") {
                                    echo ($descending) ? "descending" : "ascending";
                                }

                                ?>" href="javascript:void('')" data-sort="name"><?php echo Tools::safeOutput(lang_Filename);

            ?></a></div>
                                    <div class="file-date"><a class="sorter sort-date <?php
                                if ($sort_by == "date") {
                                    echo ($descending) ? "descending" : "ascending";
                                }

            ?>" href="javascript:void('')" data-sort="date"><?php echo Tools::safeOutput(lang_Date);

            ?></a></div>
                                    <div class="file-size"><a class="sorter sort-size <?php
                                    if ($sort_by == "size") {
                                        echo ($descending) ? "descending" : "ascending";
                                    }

                                    ?>" href="javascript:void('')" data-sort="size"><?php echo Tools::safeOutput(lang_Size);

                                    ?></a></div>
                                    <div class='img-dimension'><?php echo Tools::safeOutput(lang_Dimension);

                                    ?></div>
                                    <div class='file-extension'><a class="sorter sort-extension <?php
                    if ($sort_by == "extension") {
                        echo ($descending) ? "descending" : "ascending";
                    }

                    ?>" href="javascript:void('')" data-sort="extension"><?php echo Tools::safeOutput(lang_Type);

                    ?></a></div>
                                    <div class='file-operations'><?php echo Tools::safeOutput(lang_Operations);

                    ?></div>
                                </div>
            <?php
        }

        ?>

                            <input type="hidden" id="file_number" value="<?php echo Tools::safeOutput($n_files);

        ?>"/>
                            <div class="grid cs-style-2 <?php echo "list-view" . Tools::safeOutput($view);

        ?>">

        <?php
        if (!empty($results)) {
            $pagingHTML = '';
            if ($totalPages > 1) {
                $currentPage = Tools::getValue('page') ? (int) Tools::getValue('page') : 1;
                $backwardPage = $currentPage - 7 > 0 ? $currentPage - 7 : false;
                $forwardPage = $currentPage + 7 <= $totalPages ? $currentPage + 7 : false;
                $loopStart = $currentPage - 2 > 0 ? $currentPage - 2 : 1;
                $loopCont = $currentPage + 2 <= $totalPages ? $currentPage + 2 : $totalPages;
                if ($currentPage < 3) {
                    $loopCont = $currentPage + 4 <= $totalPages ? $currentPage + 4 : $totalPages;
                }
                ob_start();

                ?>

                                        <nav class="vc_media_pagination pagination">

                                            <ul style="margin-left:30px;">
                <?php if ($currentPage > 3) {

                    ?>
                                                    <li>
                                                        <a href="<?php echo $views_urls . '&page=1' ?>" aria-label="First">
                                                            <span aria-hidden="true">&laquo; First</span>
                                                        </a>
                                                    </li>
                    <?php
                }
                if ($backwardPage) {

                    ?>  

                                                    <li>
                                                        <a href="<?php echo "{$views_urls}&page={$backwardPage}" ?>" aria-label="">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>

                    <?php
                }
                for ($n = $loopStart; $n <= $loopCont; $n++) {

                    ?>
                                                    <li>
                    <?php if ($n == $currentPage) {

                        ?>
                                                            <span style="background-color:#0084ff; color: #fff;"><?php echo $n ?></span>
                        <?php
                    } else {

                        ?>
                                                            <a href="<?php echo "{$views_urls}&page={$n}" ?>"><?php echo $n ?></a>
                        <?php
                    }

                    ?>
                                                    </li>
                    <?php
                }
                if ($forwardPage) {

                    ?>  

                                                    <li>
                                                        <a href="<?php echo "{$views_urls}&page={$forwardPage}" ?>" aria-label="">
                                                            <span aria-hidden="true">&raquo;</span>
                                                        </a>
                                                    </li>

                    <?php
                }
                if ($currentPage < $totalPages) {

                    ?>
                                                    <li>                                          
                                                        <a href="<?php echo "{$views_urls}&page={$totalPages}" ?>" aria-label="Last">
                                                            <span aria-hidden="true">Last &raquo;</span>
                                                        </a>                                      
                                                    </li>
                    <?php
                }

                ?>
                                            </ul>
                                        </nav>                                                
                <?php
                $pagingHTML = ob_get_clean();
            }

            echo $pagingHTML;
            echo UniteBaseAdminClassRb::getUploadedFilesMarkup($results);
            echo $pagingHTML;
        }

        ?>


                            </div>
        <?php
    }

    ?>
                    </div>

                    <div class="span3">

                        <div class="well">

                            <div id="imgContainer" class="clearfix">

                            </div>

                            <div class="btn-group">

                                <select id="image_size">

                                    <option value="">Full</option>

                                    <option value="thumbnail">Thumbnail</option>

                                    <option value="medium">Medium</option>

                                    <option value="large">Large</option>

                                </select>

                            </div>

                            <div class="btn-group">

                                <button id="txtImageInsert" class="btn" value="">Insert</button>

                                <button id="txtImageDelete" class="btn" value="">Delete</button>

                            </div>

                        </div>

                    </div>
                </div>


                <div id="previewLightbox" class="lightbox hide fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class='lightbox-content'>
                        <img id="full-img" src="">
                    </div>
                </div>



                <div id="loading_container" style="display:none;">
                    <div id="loading" style="background-color:#000; position:fixed; width:100%; height:100%; top:0px; left:0px;z-index:100000"></div>
                    <img id="loading_animation" src="<?php echo $up_media_url;

    ?>img/storing_animation.gif" alt="loading" style="z-index:10001; margin-left:-32px; margin-top:-32px; position:fixed; left:50%; top:50%"/>
                </div>

                <div class="modal hide fade" id="previewAV">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3><?php echo lang_Preview;

    ?></h3>
                    </div>
                    <div class="modal-body">
                        <div class="row-fluid body-preview">
                        </div>
                    </div>

                </div>

                <img id='aviary_img' src='' class="hide"/>

    <?php }

?>
            <script type="text/javascript">

<?php $timestamp = time(); ?>

                var imgsizes = ['<?php echo GlobalsRbSlider::IMAGE_SIZE_THUMBNAIL ?>',
                    '<?php echo GlobalsRbSlider::IMAGE_SIZE_MEDIUM ?>',
                    '<?php echo GlobalsRbSlider::IMAGE_SIZE_LARGE ?>'];

                $(function() {


                    $('#imageTab a').click(function(e) {

                        e.preventDefault();

                        $(this).tab('show');

                    });

                    var splitFileparts = function(filename, size) {

                        var filerealname = filename.substr(0, filename.lastIndexOf('.'));

                        var fileext = filename.substr(filename.lastIndexOf('.'), filename.length - filename.lastIndexOf('.'));

                        var newfilename = filerealname + '-' + imgsizes[size] + 'x' + imgsizes[size] + fileext;

                        return [newfilename, filerealname, fileext];

                    };

                    $('ul#selectable > li a.link-img').off('click'); // remove active actions
                    $(document.body).on('click', '#selectable > li a.link-img', function(event) {
                        var elem = $(this).closest('li');
                        elem.siblings('li').removeClass('ui-selected');
                        elem.addClass('ui-selected');
                        $("#imgContainer").html(elem.children('figure').find('img.original').clone());
                        $("#txtImageInsert, #txtImageDelete").val(elem.data('image'));
                        $("#txtImageInsert").attr('data-image-id', elem.data('image-id'));
                        event.preventDefault();
                    });
                    $('#txtImageInsert').on('click', function() {

                        var isize = $('#image_size > option:selected').val();

                        var filename = $(this).val();

                        var imageId = $(this).attr('data-image-id');

                        if (filename == '')
                            return false;



                        var nfilename = '';

                        switch (isize) {

                            case 'thumbnail':

                                nfilename = splitFileparts(filename, 0);

                                filename = nfilename[0];

                                break;

                            case 'medium':

                                nfilename = splitFileparts(filename, 1);

                                filename = nfilename[0];

                                break;

                            case 'large':

                                nfilename = splitFileparts(filename, 2);

                                filename = nfilename[0];

                                break;

                            default:

                                break;

                        }



                        parent.iframe_img = '<?php echo $url ?>' + filename;
                        parent.iframe_img_id = imageId;

                        parent.tb_remove();

                        parent.getImg();

                    });

                    $('#txtImageDelete').on('click', function() {

                        var img = $(this).val();

                        var data = {};

                        data.img = img;

                        if (img !== undefined || img !== '')
                            UniteAdminRb.ajaxRequest("delete_uploaded_image", data, function(response) {
                                if (response.success !== undefined && response.success == '1') {
                                    $("#imgContainer").html('');
                                    $(this).val('');
                                    $('#txtImageInsert').val('');
                                    $('#divImageList').html(response.output);
                                }

                            });
                    });
                });

            </script>
            <style type="text/css">
                #selectable li figure{
                    display: block;
                    border: 5px solid transparent;               
                }

                #selectable  li.ui-selected  figure {
                    border-color: #0084FF;                
                }            

            </style>
    </body>
</html>

<?php
    die();
