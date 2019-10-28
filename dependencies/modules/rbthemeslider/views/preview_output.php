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
$isPsmlExists = UnitePsmlRb::isPsmlExists();
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

    $selectLangChoose = UniteFunctionsRb::getHTMLSelect(
      $arrLanguages,
      $currentLang,
      "id='select_langs'",
      true
    );
}


$output->setPreviewMode();

//put the output html
$urlPlugin = RbSliderAdmin::$url_plugin . 'views/';
$urlCSS = "{$urlPlugin}css/rs-plugin/";
$urlJS = "{$urlPlugin}js/rs-plugin/";

$urlPreviewPattern = UniteBaseClassRb::$url_ajax_actions .
"&client_action=preview_slider&sliderid=" . $sliderID . "&lang=[lang]&nonce=[nonce]";
$setBase = (is_ssl()) ? "https://" : "http://";

$f = new ThemePunchFonts();
$my_fonts = $f->getAllFonts();

?>
<html>
    <head>

        <link rel='stylesheet' href='<?php echo $urlCSS ?>css/settings.css?rb=<?php echo GlobalsRbSlider::SLIDER_RBISION;

?>' type='text/css' media='all' />
              <?php
              $db = new UniteDBRb();

              $styles = $db->fetch(GlobalsRbSlider::$table_css);
              $styles = UniteCssParserRb::parseDbArrayToCss($styles, "\n");
              $styles = UniteCssParserRb::compressCss($styles);

              echo '<style type="text/css">' . $styles . '</style>';

              if (!empty($my_fonts)) {
                  foreach ($my_fonts as $c_font) {

                      ?>
                <link rel='stylesheet' href="<?php echo '//fonts.googleapis.com/css?family=' . strip_tags($c_font['url']);

                      ?>" type='text/css' />
                      <?php
                  }
              }

              $custom_css = RbOperations::getStaticCss();
              echo '<style type="text/css">' . UniteCssParserRb::compressCss($custom_css) . '</style>';

              ?>

        <script type='text/javascript' src='//code.jquery.com/jquery-latest.min.js'></script>

        <script type='text/javascript' src='<?php echo $urlJS ?>js/jquery.themepunch.tools.min.js?rb=<?php echo GlobalsRbSlider::SLIDER_RBISION;

              ?>'></script>
        <script type='text/javascript' src='<?php echo $urlJS ?>js/jquery.themepunch.rbthemeslider.min.js?rb=<?php echo GlobalsRbSlider::SLIDER_RBISION;

              ?>'></script>
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
            </script>
        <?php 
        endif; 
        $output->putSliderBase($sliderID);
        ?>

    </body>
</html>
<?php
exit();
