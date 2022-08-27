<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * Add Role View
 *  
 * @license GPLv3
 * 
 * @since       3.0.0
 * @package     eduTrac SIS
 * @author      Joshua Parker <joshmac3@icloud.com>
 */

$app = \Liten\Liten::getInstance();
$app->view->extend('_layouts/dashboard');
$app->view->block('dashboard');
$screen = 'role';
?>

<ul class="breadcrumb">
	<li><?=_t( 'You are here');?></li>
	<li><a href="<?=get_base_url();?>dashboard/" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
	<li class="divider"></li>
	<li><a href="<?=get_base_url();?>role/" class="glyphicons rotation_lock"><i></i> <?=_t( 'Manage Roles' );?></a></li>
	<li class="divider"></li>
	<li><?=_t( 'Add Role' );?></li>
</ul>

<h3><?=_t( 'Add Role' );?></h3>
<div class="innerLR">
    
    <?=_etsis_flash()->showMessage();?>
    
    <?php jstree_sidebar_menu($screen); ?>

	<!-- Form -->
	<form class="form-horizontal margin-none" action="<?=get_base_url();?>role/add/" id="validateSubmitForm" method="post" autocomplete="off">
		
		<!-- Widget -->
		<div class="widget widget-heading-simple widget-body-gray <?=($app->hook->has_filter('sidebar_menu')) ? 'col-md-12' : 'col-md-10';?>">
		
			<!-- Widget heading -->
			<div class="widget-head">
				<h4 class="heading"><font color="red">*</font> <?=_t( 'Indicates field is required' );?></h4>
			</div>
			<!-- // Widget heading END -->
			
			<div class="widget-body">
					
						<!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label" for="roleName"><font color="red">*</font> <?=_t( 'Role Name' );?></label>
							<div class="col-md-12"><input class="form-control" name="roleName" type="text" required/></div>
						</div>
						<!-- // Group END -->
						
						<!-- Table -->
						<table class="dynamicTable tableTools table table-striped table-bordered table-condensed table-white">
						
							<!-- Table heading -->
							<thead>
								<tr>
									<th><?=_t( 'Permission' );?></th>
									<th><?=_t( 'Allow' );?></th>
								</tr>
							</thead>
							<!-- // Table heading END -->
							
							<tbody>
								<?php rolePerm(null); ?>
							</tbody>
				
					</table>
					<!-- // Table END -->
			
				<hr class="separator" />
				
				<!-- Form actions -->
				<div class="form-actions">
					<button type="submit" name="Submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i><?=_t( 'Submit' );?></button>
					<button type="button" class="btn btn-icon btn-primary glyphicons circle_minus" onclick="window.location='<?=get_base_url();?>role/'"><i></i><?=_t( 'Cancel' );?></button>
				</div>
				<!-- // Form actions END -->
				
			</div>
		</div>
		<!-- // Widget END -->
		
	</form>
	<!-- // Form END -->
	
</div>	
	
		
		</div>
		<!-- // Content END -->
<?php $app->view->stop(); ?>