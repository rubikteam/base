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
<div
	id="dialog_copy_move"
	data-textclose="<?php $modules->l("Close")?>"
	data-textupdate="<?php $modules->l("Do It!")?>"
	title="<?php $modules->l("Copy / move slide")?>"
	style="display:none"
>
	<br>
	<?php $modules->l("Choose Slider")?>:
	<?php echo RbGlobalObject::getVar('selectSliders'); RbGlobalObject::reset();?>
	<br><br>
	<?php $modules->l("Choose Operation")?>:
	<input type="radio" id="radio_copy" value="copy" name="copy_move_operation" checked />
	<label for="radio_copy" style="cursor:pointer;"><?php $modules->l("Copy")?></label>
	&nbsp; &nbsp;
	<input type="radio" id="radio_move" value="move" name="copy_move_operation" />
	<label for="radio_move" style="cursor:pointer;"><?php $modules->l("Move")?></label>		
</div>

<?php
