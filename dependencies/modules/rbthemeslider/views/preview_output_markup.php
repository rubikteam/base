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

$sliderID = RbGlobalObject::getVar('sliderID');
$output = RbGlobalObject::getVar('output');

$slider = new RbSlider();
$modules = new Rbthemeslider();
$slider->initByID($sliderID);
$isPsmlExists = true;
$usePsml = $slider->getParam("use_psml", "off");
$psmlActive = false;

if ($isPsmlExists && $usePsml == "on") {
    $psmlActive = true;
    $arrLanguages = UnitePsmlRb::getArrLanguages(false);

    //set current lang to output
    $currentLang = UniteFunctionsRb::getPostGetVariable("lang");

    if (empty($currentLang)) {
        $currentLang = UnitePsmlRb::getCurrentLang();
    }

    if (empty($currentLang)) {
        $currentLang = $arrLanguages[0];
    }

    $output->setLang($currentLang);

    $selectLangChoose = UniteFunctionsRb::getHTMLSelect($arrLanguages, $currentLang, "id='select_langs'", true);
}


$output->setPreviewMode();
$urlPlugin = RbSliderAdmin::$url_plugin . 'views/';
$urlCSS = "{$urlPlugin}css/rs-plugin/";
$urlJS = "{$urlPlugin}js/rs-plugin/";
$urlPreviewPattern = UniteBaseClassRb::$url_ajax_actions . "&client_action=preview_slider&only_markup=true&sliderid=" . $sliderID . "&lang=[lang]&nonce=[nonce]";

$setBase = (is_ssl()) ? "https://" : "http://";

$f = new ThemePunchFonts();
$my_fonts = $f->getAllFonts();

?>
<html>
    <head>
        <script type='text/javascript' src='<?php echo $setBase;

?>ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
    </head>
    <body style="padding:0px;margin:0px;">
        <?php if ($psmlActive == true): ?>
            <div style="margin-bottom:10px;text-align:center;">
                <?php $modules->l("Choose language") ?>: <?php echo $selectLangChoose ?>
            </div>

            <script type="text/javascript">
                var g_previewPattern = '<?php echo $urlPreviewPattern ?>';
                jQuery("#select_langs").change(function() {
                    var lang = this.value;
                    var nonce = "";
                    var pattern = g_previewPattern;
                    var urlPreview = pattern.replace("[lang]", lang).replace("[nonce]", nonce);
                    location.href = urlPreview;
                });

                jQuery('body').on('click', '#rb_replace_images', function() {
                    var from = jQuery('input[name="orig_image_path"]').val();
                    var to = jQuery('input[name="replace_image_path"]').val();

                    jQuery('#rb_script_content').val(jQuery('#rb_script_content').val().replace(from, to));
                    jQuery('#rb_the_content').val(jQuery('#rb_the_content').val().replace(from, to));
                    jQuery('#rb_style_content').val(jQuery('#rb_style_content').val().replace(from, to));
                    jQuery('#rb_head_content').val(jQuery('#rb_head_content').val().replace(from, to));
                });

            </script>
        <?php endif ?>
        <?php

        ob_start();

        ?><link rel='stylesheet' href='<?php echo $urlCSS ?>css/settings.css?rb=<?php echo GlobalsRbSlider::SLIDER_RBISION;

        ?>' type='text/css' media='all' />
              <?php
              $http = (is_ssl()) ? 'https' : 'http';

              if (!empty($my_fonts)) {
                  foreach ($my_fonts as $c_font) {

                      ?><link rel='stylesheet' href="<?php echo $http . '://fonts.googleapis.com/css?family=' . strip_tags($c_font['url']);

                      ?>" type='text/css' /><?php
                      echo "\n";
                  }
              }

              ?>
        <script type='text/javascript' src='<?php echo $urlJS ?>js/jquery.themepunch.tools.min.js?rb=<?php echo GlobalsRbSlider::SLIDER_RBISION;

              ?>'></script>
        <script type='text/javascript' src='<?php echo $urlJS ?>js/jquery.themepunch.rbthemeslider.min.js?rb=<?php echo GlobalsRbSlider::SLIDER_RBISION;

              ?>'></script>
                <?php
                $head_content = ob_get_clean();

                ob_start();

                $custom_css = RbOperations::getStaticCss();
                echo $custom_css . "\n\n";

                echo '/*****************' . "\n";
                echo ' ** ' . $modules->l('CAPTIONS CSS') . "\n";
                echo ' ****************/' . "\n\n";
                $db = new UniteDBRb();
                $styles = $db->fetch(GlobalsRbSlider::$table_css);
                echo UniteCssParserRb::parseDbArrayToCss($styles, "\n");
                $style_content = ob_get_clean();

                ob_start();

                $output->putSliderBase($sliderID);
                $content = ob_get_clean();
                $script_content = Tools::substr(
                    $content,
                    strpos($content, '<script type="text/javascript">'),
                    strpos($content, '</script>') + 9 - strpos($content,
                        '<script type="text/javascript">')
                );

                $content = htmlentities(str_replace($script_content, '', $content));
                $script_content = str_replace('				', '', $script_content);
                $script_content = str_replace(array('<script type="text/javascript">', '</script>'), '', $script_content);

                ?>
        <style>
            body 	 { font-family:sans-serif; font-size:12px;}
            textarea { background:#f1f1f1; border:#ddd; font-size:10px; line-height:16px; margin-bottom:40px; padding:10px;}
            .rb_cont_title { color:#000; text-decoration:none;font-size:14px; line-height:24px; font-weight:800;background: #D5D5D5;padding: 10px;}
            .rb_cont_title a,
            .rb_cont_title a:visited { margin-left:25px;font-size:12px;line-height:12px;float:right;background-color:#8e44ad; color:#fff; padding:8px 10px;text-decoration:none;}
            .rb_cont_title a:hover	  { background-color:#9b59b6}
        </style>
        <p>
            <?php
                $dir = uploadsUrl();
            ?>
            
            <?php $modules->l('Replace image path:');

            ?> <?php $modules->l('From:');

            ?> <input type="text" name="orig_image_path" value="<?php echo $dir;

            ?>" /> <?php $modules->l('To:');

            ?> <input type="text" name="replace_image_path" value="" /> <input id="rb_replace_images" type="button" name="replace_images" value="<?php $modules->l('Replace');

            ?>" />
        </p>

        <div class="rb_cont_title"><?php $modules->l('Header');

            ?> <a class="button-primary rbpurple export_slider_standalone copytoclip" data-idt="rb_head_content"  href="javascript:void(0);" original-title=""><?php $modules->l('Mark to Copy');

            ?></a><div style="clear:both"></div></div>
        <textarea id="rb_head_content" readonly="true" style="width: 100%; height: 100px; color:#3498db"><?php echo $head_content;

            ?></textarea>
        <div class="rb_cont_title"><?php $modules->l('CSS');

            ?><a class="button-primary rbpurple export_slider_standalone copytoclip" data-idt="rb_style_content"  href="javascript:void(0);" original-title=""><?php $modules->l('Mark to Copy');

            ?></a></div>
        <textarea id="rb_style_content" readonly="true" style="width: 100%; height: 100px;"><?php echo $style_content;

            ?></textarea>
        <div class="rb_cont_title"><?php $modules->l('Body');

            ?><a class="button-primary rbpurple export_slider_standalone copytoclip" data-idt="rb_the_content"  href="javascript:void(0);" original-title=""><?php $modules->l('Mark to Copy');

            ?></a></div>
        <textarea id="rb_the_content" readonly="true" style="width: 100%; height: 100px;"><?php echo $content;

            ?></textarea>
        <div class="rb_cont_title"><?php $modules->l('Script');

            ?><a class="button-primary rbpurple export_slider_standalone copytoclip" data-idt="rb_script_content"  href="javascript:void(0);" original-title=""><?php $modules->l('Mark to Copy');

            ?></a></div>
        <textarea id="rb_script_content" readonly="true" style="width: 100%; height: 100px;"><?php echo $script_content;

            ?></textarea>

        <script>
            jQuery('body').on('click', '.copytoclip', function() {
                jQuery("#" + jQuery(this).data('idt')).select();
            });
        </script>
    </body>
</html>
<?php
exit();
