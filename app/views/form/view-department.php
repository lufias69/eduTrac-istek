<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * Add Department View
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
$screen = 'dept';
?>

<ul class="breadcrumb">
	<li><?=_t( 'You are here');?></li>
	<li><a href="<?=get_base_url();?>dashbaord/" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
	<li class="divider"></li>
	<li><a href="<?=get_base_url();?>form/department/" class="glyphicons pin_flag"><i></i> <?=_t( 'Department' );?></a></li>
	<li class="divider"></li>
	<li><?=_t( 'View Department' );?></li>
</ul>

<h3><?=_t( 'View Department' );?></h3>
<div class="innerLR">
	
	<?=_etsis_flash()->showMessage();?>
    
    <?php jstree_sidebar_menu($screen); ?>

	<!-- Form -->
	<form class="form-horizontal margin-none" action="<?=get_base_url();?>form/department/<?=_h($dept[0]['id']);?>/" id="validateSubmitForm" method="post" autocomplete="off">
		
		<!-- Widget -->
		<div class="widget widget-heading-simple widget-body-gray <?=($app->hook->has_filter('sidebar_menu')) ? 'col-md-12' : 'col-md-10';?>">
		
			<!-- Widget heading -->
			<div class="widget-head">
				<h4 class="heading"><font color="red">*</font> <?=_t( 'Indicates field is required' );?></h4>
			</div>
			<!-- // Widget heading END -->
			
			<div class="widget-body">
			
				<!-- Row -->
				<div class="row">
					
					<!-- Column -->
					<div class="col-md-6">
					
						<!-- Group -->
						<div class="form-group">
                            <label class="col-md-3 control-label" for="deptCode"><font color="red">*</font> <?=_t( 'Department Code' );?></label>
							<div class="col-md-8"><input class="form-control" id="deptCode"<?=gio();?> name="deptCode" type="text" value="<?=_h($dept[0]['deptCode']);?>" required /></div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="deptTypeCode"><font color="red">*</font> <?=_t( 'Department Type' );?></label>
                            <div class="col-md-8">
                                <?=dept_type_select(_h($dept[0]['deptTypeCode']));?>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
						<div class="form-group">
                            <label class="col-md-3 control-label" for="deptName"><font color="red">*</font> <?=_t( 'Department Name' );?></label>
							<div class="col-md-8"><input class="form-control" id="deptName"<?=gio();?> name="deptName" type="text" value="<?=_h($dept[0]['deptName']);?>" required /></div>
						</div>
						<!-- // Group END -->
						
					</div>
					<!-- // Column END -->
					
					<!-- Column -->
					<div class="col-md-6">
						
						<!-- Group -->
						<div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Department Email' );?></label>
							<div class="col-md-8"><input class="form-control"<?=gio();?> name="deptEmail" type="text" value="<?=_h($dept[0]['deptEmail']);?>" /></div>
						</div>
						<!-- // Group END -->
                        
                        <!-- Group -->
						<div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Department Phone #' );?></label>
							<div class="col-md-8"><input class="form-control"<?=gio();?> name="deptPhone" type="text" value="<?=_h($dept[0]['deptPhone']);?>" /></div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
						<div class="form-group">
                            <label class="col-md-3 control-label" for="deptDesc"><?=_t( 'Short Description' );?></label>
							<div class="col-md-8"><input class="form-control" id="deptDesc"<?=gio();?> name="deptDesc" type="text" value="<?=_h($dept[0]['deptDesc']);?>" /></div>
						</div>
						<!-- // Group END -->
						
					</div>
					<!-- // Column END -->
					
				</div>
				<!-- // Row END -->
			
				<hr class="separator" />
				
				<!-- Form actions -->
				<div class="form-actions">
					<button type="submit"<?=gids();?> class="btn btn-icon btn-primary glyphicons circle_ok"><i></i><?=_t( 'Save' );?></button>
					<button type="button" class="btn btn-icon btn-primary glyphicons circle_minus" onclick="window.location='<?=get_base_url();?>form/department/'"><i></i><?=_t( 'Cancel' );?></button>
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