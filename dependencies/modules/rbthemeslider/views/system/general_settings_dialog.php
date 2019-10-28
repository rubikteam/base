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

$generalSettings = self::getSettings("general");
$role = $generalSettings->getSettingValue("role", UniteBaseAdminClassRb::ROLE_ADMIN);
$includes_globally = $generalSettings->getSettingValue("includes_globally", 'on');
$pages_for_includes = $generalSettings->getSettingValue("pages_for_includes", '');
$js_to_footer = $generalSettings->getSettingValue("js_to_footer", 'off');
$show_dev_export = $generalSettings->getSettingValue("show_dev_export", 'off');
$enable_logs = $generalSettings->getSettingValue("enable_logs", 'off');
$modules = new Rbthemeslider();
?>

<div id="dialog_general_settings" title="<?php echo $modules->l('General Settings');  ?>" style="display:none;">
	<div class="settings_wrapper unite_settings_wide">
		<form name="form_general_settings" id="form_general_settings">
				<script type="text/javascript">
					g_settingsObj['form_general_settings'] = {}					
				</script>
		</form>
	</div>
	<br>

	<a id="button_save_general_settings" class="button-primary" original-title="">
		<?php echo $modules->l('Update'); ?>
	</a>
	<span id="loader_general_settings" class="loader_round mleft_10" style="display: none;"></span>
</div>

<?php
