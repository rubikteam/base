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

$modules = new Rbthemeslider();
?>
<div id="dialog_update_plugin" class="api_wrapper" title="<?php echo $modules->l('Update Slider Plugin');?>" style="display:none;">
	<div class="api-caption"><?php echo $modules->l('Update RbSlider Plugin');?>:</div>
	<div class="api-desc">
		<?php echo $modules->l('Update RbSlider Plugin Desc');  ?>
		<br> <?php echo $modules->l('File Example');  ?>
	</div>
	<br>
	<form action="<?php echo UniteBaseClassRb::$url_ajax?>" enctype="multipart/form-data" method="post">
		<input type="hidden" name="action" value="rbslider_ajax_action">
		<input type="hidden" name="client_action" value="update_plugin">		
		<?php echo $modules->l('Choose Update File');  ?>
		<br>
		<input type="file" name="update_file" class="input_update_slider">
		<input type="submit" class='button-secondary' value="<?php echo $modules->l('Update Slider');?>">
	</form>
</div>

<?php
