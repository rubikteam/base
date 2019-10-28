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

if( !defined( 'ABSPATH') ) exit();

$orders = false;
$modules = new Rbthemeslider();

if(isset($_GET['ot']) && isset($_GET['order']) && isset($_GET['type'])){
	$order = array();
	switch($_GET['ot']){
		case 'alias':
			$order['alias'] = ($_GET['order'] == 'asc') ? 'ASC' : 'DESC';
		break;
		case 'favorite':
			$order['favorite'] = ($_GET['order'] == 'asc') ? 'ASC' : 'DESC';
		break;
		case 'name':
		default:
			$order['title'] = ($_GET['order'] == 'asc') ? 'ASC' : 'DESC';
		break;
	}
	
	$orders = $order;
}

$slider = new RbSlider();
$arrSliders = $slider->getArrSliders($orders);
$addNewLink = self::getViewUrl(RbSliderAdmin::VIEW_SLIDER);

$fav = getOption('rb_fav_slider', array());

if($orders == false){
	if(!empty($fav) && !empty($arrSliders)){
		$fav_sort = array();

		foreach($arrSliders as $skey => $sort_slider){
			if (in_array($sort_slider->getID(), $fav)) {
				$fav_sort[] = $arrSliders[$skey];
				unset($arrSliders[$skey]);
			}
		}

		if(!empty($fav_sort)){
			krsort($fav_sort);

			foreach($fav_sort as $fav_arr){
				array_unshift($arrSliders, $fav_arr);
			}
		}
	}
}

global $rbSliderAsTheme;
$exampleID = '"slider1"';

if (!empty($arrSliders))
	$exampleID = '"'.$arrSliders[0]->getAlias().'"';

$latest_version = getOption('rbslider-latest-version', RbSliderGlobals::SLIDER_RBISION);
$stable_version = getOption('rbslider-stable-version', '4.1');

?>

<div class='wrap'>
	<div class="clear_both"></div>
	<div class="title_line" style="margin-bottom:10px">
		<a
			href="<?php echo RbSliderGlobals::LINK_HELP_SLIDERS; ?>"
			class="button-secondary float_right mtop_10 mleft_10"
			target="_blank"
		>
			<?php $modules->l("Help"); ?>
		</a>

		<a id="button_general_settings" class="button-secondary float_right mtop_10"><?php $modules->l("Global Settings"); ?></a>
	</div>

	<div class="clear_both"></div>

	<div class="title_line nobgnopd" style="height:auto; min-height:50px">
		<div class="view_title">
			<?php $modules->l("Rb Theme Slider"); ?>			
		</div>

		<div class="slider-sortandfilter">
				<span class="slider-listviews slider-lg-views" data-type="rs-listview"><i class="eg-icon-align-justify"></i></span>
				<span class="slider-gridviews slider-lg-views active" data-type="rs-gridview"><i class="eg-icon-th"></i></span>
				<span class="slider-sort-drop"><?php $modules->l("Sort By:"); ?></span>
				<select id="sort-sliders" name="sort-sliders" style="max-width: 105px;" class="withlabel">
					<option value="id" selected="selected"><?php $modules->l("By ID"); ?></option>
					<option value="name"><?php $modules->l("By Name"); ?></option>
					<option value="type"><?php $modules->l("By Type"); ?></option>
					<option value="favorit"><?php $modules->l("By Favorit"); ?></option>
				</select>
				
				<span class="slider-filter-drop"><?php $modules->l("Filter By:"); ?></span>
				
				<select id="filter-sliders" name="filter-sliders" style="max-width: 105px;" class="withlabel">
					<option value="all" selected="selected"><?php $modules->l("All"); ?></option>
					<option value="posts"><?php $modules->l("Posts"); ?></option>
					<option value="gallery"><?php $modules->l("Gallery"); ?></option>
					<option value="vimeo"><?php $modules->l("Vimeo"); ?></option>
					<option value="youtube"><?php $modules->l("YouTube"); ?></option>
					<option value="twitter"><?php $modules->l("Twitter"); ?></option>
					<option value="facebook"><?php $modules->l("Facebook"); ?></option>
					<option value="instagram"><?php $modules->l("Instagram"); ?></option>
					<option value="flickr"><?php $modules->l("Flickr"); ?></option>
				</select>
		</div>

		<div style="width:100%;height:1px;float:none;clear:both"></div>
	</div>

	<?php
	$no_sliders = false;

	if(empty($arrSliders)){
		?>
		<span style="display:block;margin-top:15px;margin-bottom:15px;">
		<?php  $modules->l("No Sliders Found"); ?>
		</span>
		<?php
		$no_sliders = true;
	}

	require self::getPathTemplate('sliders-list');

	?>

	<div class="rs-dialog-embed-slider" style="display: none;">
		<div class="rbyellow" style="background: none repeat scroll 0% 0% #F1C40F; left:0px;top:36px;position:absolute;height:224px;padding:20px 10px;"><i style="color:#fff;font-size:25px" class="rbicon-arrows-ccw"></i></div>
		<div style="margin:5px 0px; padding-left: 55px;">
			<div style="font-size:14px;margin-bottom:10px;"><strong><?php $modules->l("Standard Embeding"); ?></strong></div>
			<?php $modules->l("For the"); ?> <b><?php $modules->l("pages or posts editor"); ?></b> <?php $modules->l("insert the shortcode:"); ?> <code class="rs-example-alias-1"></code>
			<div style="width:100%;height:10px"></div>
			<?php $modules->l("From the"); ?> <b><?php $modules->l("widgets panel"); ?></b> <?php $modules->l("drag the 'Rbthemeslider' widget to the desired sidebar"); ?>
			<div style="width:100%;height:25px"></div>
			<div id="advanced-emeding" style="font-size:14px;margin-bottom:10px;"><strong><?php $modules->l("Advanced Embeding"); ?></strong><i class="eg-icon-plus"></i></div>
			<div id="advanced-accord" style="display:none">
				<?php $modules->l("From the"); ?> <b><?php $modules->l("theme html"); ?></b> <?php $modules->l("use"); ?>: <code>&lt?php putRbSlider( '<span class="rs-example-alias">alias</span>' ); ?&gt</code><br>
				<span><?php $modules->l("To add the slider only to homepage use"); ?>: <code>&lt?php putRbSlider('<span class="rs-example-alias"><?php echo $exampleID; ?></span>', 'homepage'); ?&gt</code></span></br>
				<span><?php $modules->l("To add the slider on specific pages or posts use"); ?>: <code>&lt?php putRbSlider('<span class="rs-example-alias"><?php echo $exampleID; ?></span>', '2,10'); ?&gt</code></span></br>
			</div>
			
		</div>
	</div>

	<script>
		jQuery('#advanced-emeding').click(function() {
			jQuery('#advanced-accord').toggle(200);
		});
	</script>


	<div style="width:100%;height:40px"></div>
	
	<!-- DASHBOARD -->
	<div class="rs-dashboard">
		<?php
		$validated = getOption('rbslider-valid', 'false');
		$code = getOption('rbslider-code', '');
		$latest_version = getOption('rbslider-latest-version', RbSliderGlobals::SLIDER_RBISION);

		$activewidgetclass = $validated === 'true'? "rs-status-green-wrap" : "rs-status-red-wrap";
		
		$dashboard_array = array();
		$dashboard_required_array = array();
		
		ob_start();
		?>
		<!-- VALIDATION WIDGET -->
		<div class="rs-dash-widget">
			<div class="rs-dash-title-wrap <?php echo $activewidgetclass; ?>">
				<div class="rs-dash-title"><?php $modules->l("Plugin Activation"); ?></div>
				<div class="rs-dash-title-button rs-status-red"><i class="icon-not-registered"></i><?php $modules->l("Not Activated"); ?></div>
				<div class="rs-dash-title-button rs-status-green"><i class="icon-no-problem-found"></i><?php $modules->l("Plugin Activated",); ?></div>
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
					<div><?php $modules->l("You can learn how to find your purchase key <a target='_blank' href='#'>here</a>"); ?></div>
				</div>
				<div class="rs-dash-content-space"></div>
				<?php if(!RS_DEMO){ ?>				
					<input type="text" name="rs-validation-token" class="rs-dashboard-input" style="width:100%" value="<?php echo $code; ?>" <?php echo ($validated === 'true') ? ' readonly="readonly"' : ''; ?> style="width: 350px;" />
					<div class="rs-dash-content-space"></div>
					
					<?php if ($validated == 'true') {
					?>
						<div>
							<?php $modules->l(
								"In order to register your purchase code on another domain,
								deregister <br>it first by clicking the button below."
							); ?>
						</div>				
					<?php 
					} else { ?>
						<div><?php $modules->l("Reminder ! One registration per Website. If registered elsewhere please deactivate that registration first."); ?></div>				
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
				jQuery(document).ready(function() {
					jQuery('#rs-validation-activate-step-a').click(function() {
						punchgs.TweenLite.to(jQuery('.rs-dash-widget-inner.rs-dash-widget-deregistered'),0.5,{autoAlpha:1,x:"-100%",ease:punchgs.Power3.easeInOut});
						punchgs.TweenLite.fromTo(jQuery('.rs-dash-widget-inner.rs-dash-widget-registered'),0.5,{display:"block",autoAlpha:0,left:400},{autoAlpha:1,left:0,ease:punchgs.Power3.easeInOut});
					})
				});
			</script>
		</div><!-- END OF VALIDATION WIDGET -->
		<?php
		$dbc = ob_get_contents();
		ob_clean();
		ob_end_clean();
		
		$dashboard_array['rs-validation'] = array('order' => 10, 'content' => $dbc);
		
		ob_start();
		?>
		<!--
		THE CURRENT AND NEXT VERSION
		-->
		<?php 
		if (version_compare(RbSliderGlobals::SLIDER_RBISION, $latest_version, '<')) { 
			$updateclass = 'rs-status-orange-wrap';
		} else {	
			$updateclass = 'rs-status-green-wrap';
		}
		if($validated !== 'true' && version_compare(RbSliderGlobals::SLIDER_RBISION, $stable_version, '<'))
			$updateclass = 'rs-status-red-wrap';
		?>
		<div class="rs-dash-widget">
			<div class="rs-dash-title-wrap <?php echo $updateclass; ?>">
				<div class="rs-dash-title"><?php $modules->l("Plugin Updates"); ?></div>
				<div class="rs-dash-title-button rs-status-orange"><i class="icon-update-refresh"></i><?php $modules->l("Update Available"); ?></div>
				<div class="rs-dash-title-button rs-status-green"><i class="icon-no-problem-found"></i><?php $modules->l("Plugin up to date"); ?></div>
				<div class="rs-dash-title-button rs-status-red"><i class="icon-no-problem-found"></i><?php $modules->l("Critical Update"); ?></div>
			</div>
			
			<div class="rs-dash-widget-inner">
				<div class="rs-dash-strong-content"><?php $modules->l("Installed Version"); ?></div>
				<div><?php echo RbSliderGlobals::SLIDER_RBISION; ?></div>								
				<div class="rs-dash-content-space"></div>
				<div class="rs-dash-strong-content"><?php $modules->l("Latest Available Version"); ?></div>
				<div><?php echo $latest_version; ?></div>
				<div class="rs-dash-content-space"></div>
				<a class='rs-dash-invers-button' href='?page=rbslider&checkforupdates=true' id="rb_check_version"><?php $modules->l("Check for Updates"); ?> </a>			
				<?php if(!RS_DEMO){ ?>	
					<div class="rs-dash-bottom-wrapper">
					<?php if ($validated === 'true') 
						{ 					
							if (version_compare(RbSliderGlobals::SLIDER_RBVISION, $latest_version, '<')) { 
							?>
								<a href="update-core.php?checkforupdates=true" id="rs-check-updates" class="rs-dash-button"><?php $modules->l('Update Now'); ?></a>
							<?php	
							} else {
							?>	
								<span  class="rs-dash-button-gray"><?php $modules->l('Update to date'); ?></span>
							<?php 					
							}					
						} else {
						?>
							<span class="rs-dash-button-gray"><?php $modules->l('Register to Access Update'); ?></a>
						<?php
						}	
						
						if($validated !== 'true' && version_compare(RbSliderGlobals::SLIDER_RBVISION, $stable_version, '<')){
							?>
							<a href="update-core.php?checkforupdates=true" id="rs-check-updates" class="rs-dash-button"><?php $modules->l('Update to Stable (Free)'); ?></a><br>
							<?php
						}
						?>	
					</div>
				<?php } ?>
			</div>
			
		</div><!-- END OF VERSION INFORMATION WIDGET -->
		<?php
		$dbc = ob_get_contents();
		ob_clean();
		ob_end_clean();
		
		$dashboard_required_array['rs-version-information'] = array('order' => 20, 'content' => $dbc);
		
		ob_start();
		?>
		<!-- Requirements & Recommendations -->
		<div class="rs-dash-widget">
			<?php
				$dir = psUploadDir();
				$mem_limit = ini_get('memory_limit');
				$mem_limit_byte = psConvertHrToBytes($mem_limit);
				$upload_max_filesize = ini_get('upload_max_filesize');
				$upload_max_filesize_byte = psConvertHrToBytes($upload_max_filesize);
				$post_max_size = ini_get('post_max_size');
				$post_max_size_byte = psConvertHrToBytes($post_max_size);

				$writeable_boolean = psIsWritable($dir['basedir'].'/');
				$can_connect = getOption('rbslider-connection', false);
				$mem_limit_byte_boolean = $mem_limit_byte<268435456;
				$upload_max_filesize_byte_boolean = ($upload_max_filesize_byte < 33554432);
				$post_max_size_byte_boolean = ($post_max_size_byte < 33554432);
				$dash_rr_status = ($writeable_boolean==true && $can_connect==true && $mem_limit_byte_boolean==false && $upload_max_filesize_byte_boolean==false && $post_max_size_byte_boolean==false) ? "rs-status-green-wrap" : "rs-status-red-wrap";
				
			?>
			<div class="rs-dash-title-wrap <?php echo $dash_rr_status; ?>">
				<div class="rs-dash-title"><?php $modules->l("System Requirements"); ?></div>
				<div class="rs-dash-title-button rs-status-red"><i class="icon-problem-found"></i><?php $modules->l("Problem Found"); ?></div>
				<a class="rs-status-red rs-dash-title-button requirement-link" target="_blank" href="#" ><i class="eg-icon-info"></i></a> <div class="rs-dash-title-button rs-status-green"><i class="icon-no-problem-found"></i><?php $modules->l("No Problems"); ?></div>
			</div>
			<div class="rs-dash-widget-inner">
				<span class="rs-dash-label"><?php $modules->l('Uploads folder writable'); ?></span>
				<?php
				//check if uploads folder can be written into
				
				if($writeable_boolean){
					echo '<i class="rbgreenicon eg-icon-ok"></i>';
				}else{
					echo '<i class="rbredicon eg-icon-cancel"></i><span style="margin-left:16px" class="rs-dash-more-info" data-title="'.$modules->l('Error with File Permissions').'" data-content="'.$modules->l('Please set write permission (755) to your uploads folders to make sure the Slider can save all updates and imports in the future.').'"><i class="eg-icon-info"></i></span>';
				}
				?>
				
				<div class="rs-dash-content-space-small"></div>
				<span class="rs-dash-label"><?php $modules->l('Memory Limit'); ?></span>
				<?php
				

				if($mem_limit_byte_boolean){ 
					//not good
					echo '<i style="margin-right:20px" class="rbredicon eg-icon-cancel"></i>';
					echo '<span class="rs-dash-red-content">';
				} else {
					echo '<i style="margin-right:20px" class="rbgreenicon eg-icon-ok"></i>';
					echo '<span class="rs-dash-strong-content">';
				}

				echo $modules->l('Currently:').' '.$mem_limit;
				echo '</span>';
				if($mem_limit_byte_boolean){
					//not good
					echo '<span class="rs-dash-strong-content" style="margin-left:20px">'. $modules->l('(min:256M)').'</span>';
				} 
				?>
				<div class="rs-dash-content-space-small"></div>
				<span class="rs-dash-label"><?php $modules->l('Upload Max. Filesize'); ?></span>
				<?php
				
				
				if($upload_max_filesize_byte_boolean){
					//not good
					echo '<i style="margin-right:20px" class="rbredicon eg-icon-cancel"></i>';
					echo '<span class="rs-dash-red-content">';
				} else {
					echo '<i style="margin-right:20px"class="rbgreenicon eg-icon-ok"></i>';
					echo '<span class="rs-dash-strong-content">';
				}

				echo $modules->l('Currently:').' '.$upload_max_filesize;
				echo '</span>';
				if($upload_max_filesize_byte_boolean){
					echo '<span class="rs-dash-strong-content" style="margin-left:20px">'. $modules->l('(min:32M)').'</span>';
				}
				?>
				<div class="rs-dash-content-space-small"></div>
				<span class="rs-dash-label"><?php $modules->l('Max. Post Size'); ?></span>
				<?php
				
				
				
				if($post_max_size_byte_boolean){
				//not good
					echo '<i style="margin-right:20px" class="rbredicon eg-icon-cancel"></i>';
					echo '<span class="rs-dash-red-content">';
				} else {
					echo '<i style="margin-right:20px"class="rbgreenicon eg-icon-ok"></i>';
					echo '<span class="rs-dash-strong-content">';
				}

				echo $modules->l('Currently:').' '.$post_max_size;
				echo '</span>';
				if($post_max_size_byte_boolean){
					echo '<span class="rs-dash-strong-content" style="margin-left:20px">'. $modules->l('(min:32M)').'</span>';
				}
				?>

				<div class="rs-dash-content-space-small"></div>
				<span class="rs-dash-label"><?php $modules->l('Contact ThemePunch Server'); ?></span>
				<?php
				
				if($can_connect){
					echo '<i class="rbgreenicon eg-icon-ok"></i>';
				}else{
					echo '<i class="rbredicon eg-icon-cancel"></i>';					
				}
				?>				
				<a class='rs-dash-invers-button' href='?page=rbslider&checkforupdates=true' id="rb_check_version_1" style="margin-left:16px"><?php $modules->l("Check Now"); ?></a>
				<?php 
				if(!$can_connect){
					echo '<span class="rs-dash-more-info" data-title="'.$modules->l('Error with contacting the ThemePunch Server').'" data-content="'.$modules->l('Please make sure that your server can connect to updates.themepunch.tools and templates.themepunch.tools programmatically.').'"><i class="eg-icon-info"></i></span>';
				} 
				?>
			</div>
		</div><!-- END OF Requirements & Recommendations -->
		<?php
		$dbc = ob_get_contents();
		ob_clean();
		ob_end_clean();
		
		$dashboard_required_array['rs-requirements'] = array('order' => 30, 'content' => $dbc);
		
		ob_start();
		?>
		<!--
		TEMPLATE WIDGET
		-->
		<div class="rs-dash-widget">
			<div class="templatestore-bg"></div>
			<div class="rs-dash-title-wrap" style="position:relative; z-index:1">
				<div class="rs-dash-title"><?php $modules->l("Start Downloading Templates"); ?></div>				
			</div>
			
			<div class="rs-dash-widget-inner">				
				<?php if ($validated === 'true') { 
					?>
					<div class="rs-dash-icon rs-dash-download"></div>
					<div class="rs-dash-content-with-icon">
						<div class="rs-dash-strong-content"><?php $modules->l("Online Slider Library"); ?></div>
						<div><?php $modules->l("Full examples for instant usage"); ?></div>
					</div>
					<div class="rs-dash-content-space"></div>				
					<div class="rs-dash-icon rs-dash-diamond"></div>
					<div class="rs-dash-content-with-icon">
						<div class="rs-dash-strong-content"><?php $modules->l("Get Free Premium Sliders"); ?></div>
						<div class=""><?php $modules->l("Activate your plugin and profit"); ?></div>
					</div>
				<?php }else{ ?>
					<div class="rs-dash-icon rs-dash-notregistered"></div>
					<div class="rs-dash-content-with-icon" style="width:190px;margin-right:20px">
						<div class="rs-dash-strong-content rs-dash-deactivated"><?php $modules->l("Online Slider Library"); ?></div>
						<div class="rs-dash-deactivated"><?php $modules->l("Full examples for instant usage"); ?></div>						
					</div>
					<span class="rs-dash-more-info" data-takemeback="false" data-title="<?php $modules->l('How to Unlock Premium Features?');?>" data-content="<?php echo $modules->l('If you have purchased Slider Rbthemeslider from ThemePunch directly you can find your activation code here:').'<div class=\'rs-dash-content-space\'></div><a target=\'_blank\' href= class=\'rs-dash-invers-button\'>'.$modules->l('Where is my Purchase Code?').'</a><div class=\'rs-dash-content-space\'></div>'.$modules->l('Dont have a license yet? Purchase a license on CodeCanyon').'<div class=\'rs-dash-content-space\'></div><a target=\'_blank\' href=\'#\' class=\'rs-dash-button-small\'>'.$modules->l('Buy Now from $19').'</a>'; ?>"><span class="rs-dash-invers-button-gray rs-dash-close-panel">Unlock Now</span></span>
					<div class="rs-dash-content-space"></div>				
					<div class="rs-dash-icon rs-dash-notregistered"></div>
					<div class="rs-dash-content-with-icon" style="width:190px;margin-right:20px">
						<div class="rs-dash-strong-content rs-dash-deactivated"><?php $modules->l("Get Free Premium Sliders"); ?></div>
						<div class="rs-dash-deactivated"><?php $modules->l("Activate your plugin and profit"); ?></div>						
					</div>
					<span class="rs-dash-more-info" data-takemeback="false" data-title="<?php $modules->l('How to Unlock Premium Features?');?>" data-content="<?php echo $modules->l('If you have purchased Slider from ThemePunch directly you can find your activation code here:').'<div class=\'rs-dash-content-space\'></div><a target=\'_blank\' href=\'#\' class=\'rs-dash-invers-button\'>'.$modules->l('Where is my Purchase Code?').'</a><div class=\'rs-dash-content-space\'></div>'.$modules->l('Dont have a license yet? Purchase a license on CodeCanyon').'<div class=\'rs-dash-content-space\'></div><a target=\'_blank\' href=\'#\' class=\'rs-dash-button-small\'>'.$modules->l('Buy Now from $19').'</a>'; ?>"><span class="rs-dash-invers-button-gray rs-dash-close-panel">Unlock Now</span></span>
				<?php } ?>				
				<div class="rs-dash-bottom-wrapper">
					<?php if ($validated === 'true') { ?>
						<a href="javascript:void(0)" class="rs-dash-button" id="button_import_template_slider_b"><?php $modules->l('Open Template Store'); ?></a>				
					<?php }else{ ?>
				 		<span class="rs-dash-button-gray" ><?php $modules->l('Register to Access Store'); ?></span>				 
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
		$dbc = ob_get_contents();
		ob_clean();
		ob_end_clean();
		
		$dashboard_required_array['rs-templates'] = array('order' => 40, 'content' => $dbc);
		
		ob_start();
		?>
		<!--
		NEWSLETTER
		-->
		<div class="rs-dash-widget">
			<div class="rs-dash-title-wrap">
				<div class="rs-dash-title"><?php $modules->l("ThemePunch Newsletter"); ?></div>				
			</div>
			<div class="newsletter-bg"></div>
			<div class="rs-dash-widget-inner">				
				<div class="rs-dash-icon rs-dash-speaker"></div>
				<div class="rs-dash-content-with-icon">
					<div class="rs-dash-strong-content"><?php $modules->l("Stay Updated"); ?></div>
					<div><?php $modules->l("Receive info on the latest product updates & products"); ?></div>
				</div>
				<div class="rs-dash-content-space"></div>				
				<div class="rs-dash-icon rs-dash-gift"></div>
				<div class="rs-dash-content-with-icon">
					<div class="rs-dash-strong-content"><?php $modules->l("Free Goodies"); ?></div>
					<div><?php $modules->l("Learn about free stuff we offer on a regular basis"); ?></div>
				</div>
				<div class="rs-dash-content-space"></div>				
				<div class="rs-dash-icon rs-dash-smile"></div>
				<div class="rs-dash-content-with-icon">
					<div class="rs-dash-strong-content"><?php $modules->l("Provide Feedback"); ?></div>
					<div><?php $modules->l("Participate in survey and help us improve constantly"); ?></div>
				</div>

				<div class="rs-dash-bottom-wrapper">
					<span class="subscribe-newsletter-wrap"><a href="javascript:void(0);" class="rs-dash-button" id="subscribe-to-newsletter"><?php $modules->l('Subscribe'); ?></a></span>				
					<input class="rs-dashboard-input" style="width:220px;margin-left:10px" type="text" value="" placeholder="<?php $modules->l('Enter your E-Mail here'); ?>" name="rs-email" />
				</div>
			</div>
			
		</div>
		<?php
		$dbc = ob_get_contents();
		ob_clean();
		ob_end_clean();
		
		$dashboard_required_array['rs-newsletter'] = array('order' => 50, 'content' => $dbc);
		
		ob_start();
		?>

		<div class="rs-dash-widget">
			<div class="rs-dash-title-wrap">
				<div class="rs-dash-title"><?php $modules->l("Product Support"); ?></div>				
			</div>			
			<div class="rs-dash-widget-inner">			

				<div class="rs-dash-icon rs-dash-copy"></div>
				<div class="rs-dash-content-with-icon">
					<div class="rs-dash-strong-content"><?php $modules->l("Online Documentation"); ?></div>
					<div><?php $modules->l("The best start for Slider Rbthemeslider beginners"); ?></div>
				</div>
				<div class="rs-dash-content-space"></div>				
				<div class="rs-dash-icon rs-dash-light"></div>
				<div class="rs-dash-content-with-icon">
					<div class="rs-dash-strong-content"><?php $modules->l("Browse FAQ's"); ?></div>
					<div><?php $modules->l("Instant solutions for most problems"); ?></div>
				</div>
				<div class="rs-dash-content-space"></div>
				<?php if ($validated === 'true') { ?>
					<div class="rs-dash-icon rs-dash-ticket"></div>
					<div class="rs-dash-content-with-icon">
						<div class="rs-dash-strong-content"><?php $modules->l("Ticket Support"); ?></div>
						<div><?php $modules->l("Direct help from our qualified support team"); ?></div>
					</div>
				<?php }else{ ?>												
					<div class="rs-dash-icon rs-dash-notregistered"></div>
					<div class="rs-dash-content-with-icon" style="width:278px;margin-right:20px">
						<div class="rs-dash-strong-content"><?php $modules->l("Ticket Support"); ?></div>
						<div><?php $modules->l("Direct help from our qualified support team"); ?></div>
					</div>
					<span class="rs-dash-more-info" data-takemeback="false" data-title="<?php $modules->l('How to Unlock Premium Features?');?>" data-content="<?php echo $modules->l('If you have purchased Slider from ThemePunch directly you can find your activation code here:').'<div class=\'rs-dash-content-space\'></div><a target=\'_blank\' href=\'#\' class=\'rs-dash-invers-button\'>'.$modules->l('Where is my Purchase Code?').'</a><div class=\'rs-dash-content-space\'></div>'.$modules->l('Dont have a license yet? Purchase a license on CodeCanyon').'<div class=\'rs-dash-content-space\'></div><a target=\'_blank\' href=\'#\' class=\'rs-dash-button-small\'>'.$modules->l('Buy Now from $19').'</a>'; ?>"><span class="rs-dash-invers-button-gray rs-dash-close-panel">Unlock Now</span></span>
				<?php } ?>

				<div class="rs-dash-bottom-wrapper">					
					<a href="http://www.themepunch.com/support-center/" target="_blank" class="rs-dash-button"><?php $modules->l('Visit Support Center'); ?></a>									
				</div>
			</div>
			
		</div><!-- END OF PRODUCT SUPPORT  -->
		<?php
		$dbc = ob_get_contents();
		ob_clean();
		ob_end_clean();
		
		$dashboard_array['rs-support'] = array('order' => 60, 'content' => $dbc);
		
		$dbvariables = array(
							'validated' 		=> $validated,
							'code'				=> $code,
							'current_version'	=> RbSliderGlobals::SLIDER_RBVISION
							);
							
		$dashboard_array = apply_filters('rbslider_dashboard_elements', $dashboard_array, $dbvariables);
		
		$dashboard_array = array_merge($dashboard_array, $dashboard_required_array);
		
		
		$dashboard_server = (array) getOption('rbslider-dashboard', array());
		
		if(!empty($dashboard_server)){
			foreach($dashboard_server as $dbk => $dbv){
				$dashboard_server[$dbk] = (array) $dbv;
				if(version_compare(RbSliderGlobals::SLIDER_RBVISION, $dbv->version_from, '<') || version_compare(RbSliderGlobals::SLIDER_RBVISION, $dbv->version_to, '>')){
					unset($dashboard_server[$dbk]);
				}
			}
			
			if(!empty($dashboard_server)){
				$dashboard_array = array_merge($dashboard_array, $dashboard_server);
			}
		}
		
		if(!empty($dashboard_array) && is_array($dashboard_array)){
			usort($dashboard_array, array('RbSliderFunctions', 'sortByOrder'));
			
			foreach($dashboard_array as $dbarray){
				foreach($dbvariables as $dbhandle => $dbvalue){
					$dbarray['content'] = str_replace('{{'.$dbhandle.'}}', $dbvalue, $dbarray['content']);
				}
				echo $dbarray['content'];
			}
		}
		?>
		<div class="tp-clearfix"></div>
	</div>
	
	<div style="width:100%;height:40px"></div>
	<div class="rs-update-history-wrapper">
		<div class="rs-dash-title-wrap">
			<div class="rs-dash-title"><?php $modules->l("Update History"); ?></div>				
		</div>	
		<div class="rs-update-history"><?php echo file_get_contents(RS_PLUGIN_PATH.'release_log.html'); ?></div>
	</div>
	
</div>

<!-- Import slider dialog -->
<div id="dialog_import_slider" title="<?php $modules->l("Import Slider"); ?>" class="dialog_import_slider" style="display:none">
	<form action="<?php echo RbSliderBase::$url_ajax; ?>" enctype="multipart/form-data" method="post">
		<br>
		<input type="hidden" name="action" value="rbslider_ajax_action">
		<input type="hidden" name="client_action" value="import_slider_slidersview">
		<input type="hidden" name="nonce" value="<?php echo psCreateNonce("rbslider_actions"); ?>">
		<?php $modules->l("Choose the import file"); ?>:
		<br>
		<input type="file" size="60" name="import_file" class="input_import_slider">
		<br><br>
		<span style="font-weight: 700;"><?php $modules->l("Note: styles templates will be updated if they exist!"); ?></span><br><br>
		<table>
			<tr>
				<td><?php $modules->l("Custom Animations:"); ?></td>
				<td><input type="radio" name="update_animations" value="true" checked="checked"> <?php $modules->l("overwrite"); ?></td>
				<td><input type="radio" name="update_animations" value="false"> <?php $modules->l("append"); ?></td>
			</tr>
			<tr>
				<td><?php $modules->l("Custom Navigations:"); ?></td>
				<td><input type="radio" name="update_navigations" value="true" checked="checked"> <?php $modules->l("overwrite"); ?></td>
				<td><input type="radio" name="update_navigations" value="false"> <?php $modules->l("append"); ?></td>
			</tr>
			<tr>
				<td><?php $modules->l("Static Styles:"); ?></td>
				<td><input type="radio" name="update_static_captions" value="true"> <?php $modules->l("overwrite"); ?></td>
				<td><input type="radio" name="update_static_captions" value="false"> <?php $modules->l("append"); ?></td>
				<td><input type="radio" name="update_static_captions" value="none" checked="checked"> <?php $modules->l("ignore"); ?></td>
			</tr>
		</table>
		<br><br>
		<input type="submit" class="button-primary rbblue tp-be-button rb-import-slider-button" style="display: none;" value="<?php $modules->l("Import Slider"); ?>">
	</form>
</div>

<div id="dialog_duplicate_slider" class="dialog_duplicate_layer" title="<?php $modules->l('Duplicate'); ?>" style="display:none;">
	<div style="margin-top:14px">
		<span style="margin-right:15px"><?php $modules->l('Title:'); ?></span><input id="rs-duplicate-animation" type="text" name="rs-duplicate-animation" value="" />
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function(){
		RbSliderAdmin.initSlidersListView();
		RbSliderAdmin.initNewsletterRoutine();
		
		jQuery('#benefitsbutton').hover(function() {
			jQuery('#benefitscontent').slideDown(200);
		}, function() {
			jQuery('#benefitscontent').slideUp(200);
		});
		
		jQuery('#why-subscribe').hover(function() {
			jQuery('#why-subscribe-wrapper').slideDown(200);
		}, function() {
			jQuery('#why-subscribe-wrapper').slideUp(200);				
		});
		
		jQuery('#tp-validation-box').click(function() {
			jQuery(this).css({cursor:"default"});
			if (jQuery('#rs-validation-wrapper').css('display')=="none") {
				jQuery('#tp-before-validation').hide();
				jQuery('#rs-validation-wrapper').slideDown(200);
			}
		});

		jQuery('body').on('click','.rs-dash-more-info',function() {
			var btn = jQuery(this),
				p = btn.closest('.rs-dash-widget-inner'),
				tmb = btn.data('takemeback'),
				btxt = '';

			btxt = btxt + '<div class="rs-dash-widget-warning-panel">';
			btxt = btxt + '	<i class="eg-icon-cancel rs-dash-widget-ps-cancel"></i>';
			btxt = btxt + '	<div class="rs-dash-strong-content">'+ btn.data("title")+'</div>';				
			btxt = btxt + '	<div class="rs-dash-content-space"></div>';
			btxt = btxt + '	<div>'+btn.data("content")+'</div>';
		
			if (tmb!=="false" && tmb!==false) {
				btxt = btxt + '	<div class="rs-dash-content-space"></div>';
				btxt = btxt + '	<span class="rs-dash-invers-button-gray rs-dash-close-panel">Thanks! Take me back</span>';
			}
			btxt = btxt + '</div>';

			p.append(btxt);
			var panel = p.find('.rs-dash-widget-warning-panel');

			punchgs.TweenLite.fromTo(panel,0.3,{y:-10,autoAlpha:0},{autoAlpha:1,y:0,ease:punchgs.Power3.easeInOut});
			panel.find('.rs-dash-widget-ps-cancel, .rs-dash-close-panel').click(function() {
				punchgs.TweenLite.to(panel,0.3,{y:-10,autoAlpha:0,ease:punchgs.Power3.easeInOut});
				setTimeout(function() {
					panel.remove();
				},300)
			})
		});
	});
</script>
<?php
require self::getPathTemplate('template-slider-selector');
?>
