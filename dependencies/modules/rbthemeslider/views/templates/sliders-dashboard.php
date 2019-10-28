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

$validated = Configuration::get('rbslider-valid');
$activewidgetclass = $validated === 'true' ? "rs-status-green-wrap" : "rs-status-red-wrap";
$code = Configuration::get('rbslider-code');
$modules = new Rbthemeslider();

?>

<div class="rs-dash-widget">
    <div class="rs-dash-title-wrap <?php echo $activewidgetclass; ?>">
        <div class="rs-dash-title"><?php $modules->l("Plugin Activation"); ?></div>
        <div class="rs-dash-title-button rs-status-red"><i class="icon-not-registered"></i><?php $modules->l("Not Activated"); ?></div>
        <div class="rs-dash-title-button rs-status-green"><i class="icon-no-problem-found"></i><?php $modules->l("Plugin Activated"); ?></div>
    </div>

    <div class="rs-dash-widget-inner rs-dash-widget-deregistered" <?php echo ($validated !== 'true') ? '' : 'style="display: none;"'; ?>>
        <div class="rs-dash-icon rs-dash-refresh"></div>
        <div class="rs-dash-content-with-icon">
            <div class="rs-dash-strong-content"><?php $modules->l("Live Updates"); ?></div>
            <div><?php $modules->l("Fresh versions directly to your admin"); ?></div>
        </div>
        <div class="rs-dash-content-space"></div>
        <div class="rs-dash-icon rs-dash-ticket"></div>
        <div class="rs-dash-content-with-icon">
            <div class="rs-dash-strong-content"><?php $modules->l("Ticket Support"); ?></div>
            <div><?php $modules->l("Direct help from our qualified support team"); ?></div>
        </div>
        <div class="rs-dash-content-space"></div>				
        <div class="rs-dash-icon rs-dash-gift"></div>
        <div class="rs-dash-content-with-icon">
            <div class="rs-dash-strong-content"><?php $modules->l("Free Premium Sliders"); ?></div>
            <div><?php $modules->l("Exclusive new slider exports for our direct customers"); ?></div>
        </div>

        <div class="rs-dash-bottom-wrapper">
            <span id="rs-validation-activate-step-a" class="rs-dash-button"><?php $modules->l('Register Slider Rbthemeslider'); ?></a>
        </div>
    </div>

    <div class="rs-dash-widget-inner rs-dash-widget-registered" <?php echo ($validated === 'true') ? '' : 'style="display: none;position:absolute;top:60px;left:0px;"'; ?>>

        <div class="rs-dash-icon rs-dash-credit"></div>
        <div class="rs-dash-content-with-icon">
            <div class="rs-dash-strong-content"><?php $modules->l("Purchase Code"); ?></div>
            <div><?php echo htmlspecialchars_decode($modules->l("You can learn how to find your purchase key <a target='_blank' href='http://www.themepunch.com/faq/where-to-find-the-purchase-code/'>here</a>")); ?></div>
        </div>
        <div class="rs-dash-content-space"></div>
        <?php if (!RS_DEMO) { ?>				
            <input type="text" name="rs-validation-token" class="rs-dashboard-input" style="width:100%" value="<?php echo $code; ?>" <?php echo ($validated === 'true') ? ' readonly="readonly"' : ''; ?> style="width: 350px;" />
            <div class="rs-dash-content-space"></div>

            <?php if ($validated == 'true') {

                ?>
                <div><?php $modules->l("In order to register your purchase code on another domain, deregister <br>it first by clicking the button below."); ?></div>				
                <?php } else {

                ?>
                <div><?php $modules->l("
                    Reminder ! One registration per Website. If registered elsewhere please deactivate that registration first."
                    ); ?>
                </div>				
                <?php
            }

            ?>

            <div class="rs-dash-bottom-wrapper">
                <span style="display:none" id="rs_purchase_validation" class="loader_round"><?php $modules->l('Please Wait...'); ?></span>					
                <a href="javascript:void(0);" <?php echo ($validated !== 'true') ? '' : 'style="display: none;"'; ?> id="rs-validation-activate" class="rs-dash-button"><?php $modules->l('Register the code'); ?></a>				
                <a href="javascript:void(0);" <?php echo ($validated === 'true') ? '' : 'style="display: none;"'; ?> id="rs-validation-deactivate" class="rs-dash-button"><?php $modules->l('Deregister the code'); ?></a>
            </div>					
<?php } ?>
    </div>		

    <script>
        $(document).ready(function () {
            $('#rs-validation-activate-step-a').click(function () {
                punchgs.TweenLite.to($('.rs-dash-widget-inner.rs-dash-widget-deregistered'), 0.5, {autoAlpha: 1, x: "-100%", ease: punchgs.Power3.easeInOut});
                punchgs.TweenLite.fromTo($('.rs-dash-widget-inner.rs-dash-widget-registered'), 0.5, {display: "block", autoAlpha: 0, left: 400}, {autoAlpha: 1, left: 0, ease: punchgs.Power3.easeInOut});
            })
        });
    </script>
</div>

<?php
