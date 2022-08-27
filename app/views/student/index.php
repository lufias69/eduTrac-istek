<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * Student Search View
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
?>

<ul class="breadcrumb">
	<li><?=_t( 'You are here' );?></li>
	<li><a href="<?=get_base_url();?>dashboard/" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
	<li class="divider"></li>
	<li><?=_t( 'Student' );?></li>
</ul>

<h3><?=_t( 'Search Student' );?></h3>
<div class="innerLR">
    
    <?=_etsis_flash()->showMessage();?>

	<!-- Widget -->
	<div class="widget widget-heading-simple widget-body-gray">
		<div class="widget-body">
		
			<div class="tab-pane" id="search-users">
				<div class="widget widget-heading-simple widget-body-white margin-none">
					<div class="widget-body">
						
						<div class="widget widget-heading-simple widget-body-simple text-right form-group">
							<form class="form-search text-center" action="<?=get_base_url();?>stu/" method="post" autocomplete="off">
							  	<input type="text" name="spro" class="form-control" placeholder="Search by student ID or name . . . " /> 
							  	<a href="#myModal" data-toggle="modal"><img src="<?=get_base_url();?>static/common/theme/images/help.png" /></a>
							</form>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="separator bottom"></div>
			
			<?php if(isset($_POST['spro'])) { ?>
			<!-- Table -->
			<table class="dynamicTable tableTools table table-striped table-bordered table-condensed table-white">
			
				<!-- Table heading -->
				<thead>
					<tr>
						<th class="text-center"><?=_t( 'Image' );?></th>
						<th class="text-center"><?=_t( 'ID' );?></th>
						<th class="text-center"><?=_t( 'Last Name' );?></th>
						<th class="text-center"><?=_t( 'First Name' );?></th>
						<th class="text-center"><?=_t( 'Actions' );?></th>
					</tr>
				</thead>
				<!-- // Table heading END -->
				
				<!-- Table body -->
				<tbody>
				<?php if($search != '') : foreach($search as $k => $v) { ?>
                <tr class="gradeX">
                	<td class="text-center"><?=get_school_photo(_escape($v['stuID']), _escape($v['email']), 48, 'avatar-frame');?></td>
                    <td class="text-center"><?=(_escape($v['altID']) != '' ? _escape($v['altID']) : _escape($v['stuID']));?></td>
                    <td class="text-center"><?=_escape($v['lname']);?></td>
                    <td class="text-center"><?=_escape($v['fname']);?></td>
                    <td class="text-center">
                    	<div class="btn-group dropup">
                            <button class="btn btn-default btn-xs" type="button"><?=_t( 'Actions' ); ?></button>
                            <button data-toggle="dropdown" class="btn btn-xs btn-primary dropdown-toggle" type="button">
                                <span class="caret"></span>
                                <span class="sr-only"><?=_t( 'Toggle Dropdown' ); ?></span>
                            </button>
                            <ul role="menu" class="dropdown-menu dropup-text pull-right">
                                <li><a href="<?=get_base_url();?>stu/<?=_escape($v['stuID']);?>/"><?=_t( 'View' ); ?></a></li>
                                <li><a href="<?=get_base_url();?>stu/stac/<?=_escape($v['stuID']);?>/"><?=_t( 'Academic Credits (STAC)' ); ?></a></li>
                                <li><a href="<?=get_base_url();?>stu/shis/<?=_escape($v['stuID']);?>/"><?=_t( 'Hiatus (SHIS)' ); ?></a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
				<?php } endif; ?>
				</tbody>
				<!-- // Table body END -->
				
			</table>
			<!-- // Table END -->
			
			<?php } ?>
			
		</div>
	</div>
	<div class="separator bottom"></div>
	
	<!-- Modal -->
	<div class="modal fade" id="myModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal heading -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title"><?=_t( 'Student Search' );?></h3>
				</div>
				<!-- // Modal heading END -->
				<!-- Modal body -->
				<div class="modal-body">
					<?=file_get_contents( APP_PATH . 'Info/person-search.txt' );?>
				</div>
				<!-- // Modal body END -->
				<!-- Modal footer -->
				<div class="modal-footer">
					<a href="#" class="btn btn-default" data-dismiss="modal">Close</a> 
				</div>
				<!-- // Modal footer END -->
			</div>
		</div>
	</div>
	<!-- // Modal END -->
	
	<!-- // Widget END -->
	
</div>	
	
		
		</div>
		<!-- // Content END -->
<?php $app->view->stop(); ?>