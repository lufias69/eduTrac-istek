<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * Room View
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
$screen = 'room';
?>

<ul class="breadcrumb">
	<li><?=_t( 'You are here');?></li>
	<li><a href="<?=get_base_url();?>dashbaord/" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
	<li class="divider"></li>
	<li><a href="<?=get_base_url();?>form/room/" class="glyphicons pin_flag"><i></i> <?=_t( 'Room' );?></a></li>
	<li class="divider"></li>
	<li><?=_t( 'View Room' );?></li>
</ul>

<h3><?=_t( 'Viewing ' );?><?=_h($room[0]['roomNumber']);?></h3>
<div class="innerLR">
	
	<?=_etsis_flash()->showMessage();?>
    
    <?php jstree_sidebar_menu($screen); ?>

	<!-- Form -->
	<form class="form-horizontal margin-none" action="<?=get_base_url();?>form/room/<?=_h($room[0]['id']);?>/" id="validateSubmitForm" method="post" autocomplete="off">
		
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
                            <label class="col-md-3 control-label" for="buildingCode"><font color="red">*</font> <?=_t( 'Building' );?></label>
                            <div class="col-md-8">
                                <select name="buildingCode"<?=gio();?> class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
                                    <option value="">&nbsp;</option>
                                    <?=table_dropdown('building', 'buildingCode <> "NULL"', 'buildingCode', 'buildingCode', 'buildingName', _h($room[0]['buildingCode']) );?>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
					
						<!-- Group -->
						<div class="form-group">
                            <label class="col-md-3 control-label" for="roomCode"><font color="red">*</font> <?=_t( 'Room Code' );?></label>
							<div class="col-md-8"><input class="form-control"<?=gio();?> name="roomCode" type="text" value="<?=_h($room[0]['roomCode']);?>" required /></div>
						</div>
						<!-- // Group END -->
						
					</div>
					<!-- // Column END -->
					
					<!-- Column -->
                    <div class="col-md-6">
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="roomNumber"><font color="red">*</font> <?=_t( 'Room Number' );?></label>
                            <div class="col-md-8"><input class="form-control"<?=gio();?> name="roomNumber" type="text" value="<?=_h($room[0]['roomNumber']);?>" required /></div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="roomCap"><font color="red">*</font> <?=_t( 'Seating Capacity' );?></label>
                            <div class="col-md-8"><input class="form-control"<?=gio();?> name="roomCap" type="text" value="<?=_h($room[0]['roomCap']);?>" required /></div>
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
					<button type="button" class="btn btn-icon btn-primary glyphicons circle_minus" onclick="window.location='<?=get_base_url();?>form/room/'"><i></i><?=_t( 'Cancel' );?></button>
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