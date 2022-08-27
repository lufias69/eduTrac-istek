<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * Student Hiatus View
 *  
 * @license GPLv3
 * 
 * @since       4.3
 * @package     eduTrac SIS
 * @author      Joshua Parker <joshmac3@icloud.com>
 */

$app = \Liten\Liten::getInstance();
$app->view->extend('_layouts/dashboard');
$app->view->block('dashboard');
?>

<script type="text/javascript">

function addMsg(text,element_id) {

document.getElementById(element_id).value += text;

}
</script>

<ul class="breadcrumb">
    <li><?=_t( 'You are here' );?></li>
    <li><a href="<?=get_base_url();?>dashboard/" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
    <li class="divider"></li>
    <li><a href="<?=get_base_url();?>stu/" class="glyphicons search"><i></i> <?=_t( 'Search Student' );?></a></li>
    <li class="divider"></li>
    <li><a href="<?=get_base_url();?>stu/<?=_escape($stu->stuID);?>/" class="glyphicons user"><i></i> <?=_t( 'Student Profile' );?></a></li>
    <li class="divider"></li>
    <li><?=_t( 'Student Hiatus (SHIS)' );?></li>
</ul>

<div class="innerLR">
    
    <?php get_stu_header($stu->stuID); ?>
    
    <div class="separator line bottom"></div>
    
    <?=_etsis_flash()->showMessage();?>
    
    <!-- Tabs Heading -->
    <div class="tabsbar">
        <ul>
            <li class="glyphicons user"><a href="<?=get_base_url();?>stu/<?=_escape($stu->stuID);?>/"><i></i> <?=_t( 'Student Profile (SPRO)' );?></a></li>
            <li class="glyphicons package"><a href="<?=get_base_url();?>stu/stac/<?=_escape($stu->stuID);?>/"><i></i> <?=_t( 'Student Academic Credits (STAC)' );?></a></li>
            <li class="glyphicons tags tab-stacked"><a href="<?=get_base_url();?>stu/sttr/<?=_escape($stu->stuID);?>/"><i></i> <?=_t( 'Student Terms (STTR)' );?></a></li>
            <li class="glyphicons history tab-stacked active"><a href="<?=get_base_url();?>stu/shis/<?=_escape($stu->stuID);?>/" data-toggle="tab"><i></i> <span><?=_t( 'Student Hiatus (SHIS)' );?></span></a></li>
        </ul>
    </div>
    <!-- // Tabs Heading END -->

	<!-- Form -->
	<form class="form-horizontal margin-none" action="<?=get_base_url();?>stu/shis/<?=_escape($stu->stuID);?>/" id="validateSubmitForm" method="post" autocomplete="off">
		
		<!-- Table -->
		<table class="table table-striped table-responsive swipe-horizontal table-primary">
		
			<!-- Table heading -->
			<thead>
				<tr>
					<th class="text-center"><?=_t( 'Hiatus' );?></th>
                    <th class="text-center"><?=_t( 'Start Date' );?></th>
                    <th class="text-center"><?=_t( 'End Date' );?></th>
                    <th class="text-center"><?=_t( 'Comments' );?></th>
                    <th class="text-center"><?=_t( 'Actions' );?></th>
				</tr>
			</thead>
			<!-- // Table heading END -->
			
			<!-- Table body -->
			<tbody>
				<?php if($shis != '') : foreach($shis as $k => $v) { ?>
				<!-- Table row -->
				<tr class="gradeA">
					<td style="width:300px;">
						<select name="code[]" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
                            <option value="">&nbsp;</option>
                            <option value="W"<?=selected('W',_escape($v['code']),false);?>><?=_t( 'Withdrawal' );?></option>
                            <option value="LOA"<?=selected('LOA',_escape($v['code']),false);?>><?=_t( 'Leave of Absence' );?></option>
                            <option value="SA"<?=selected('SA',_escape($v['code']),false);?>><?=_t( 'Study Abroad' );?></option>
                            <option value="ILLN"<?=selected('ILLN',_escape($v['code']),false);?>><?=_t( 'Illness' );?></option>
                            <option value="DISM"<?=selected('DISM',_escape($v['code']),false);?>><?=_t( 'Dismissal' );?></option>
                        </select>
					</td>
					<td style="width:160px;">
						<div class="input-group date" id="datepicker6<?=_escape($v['id']);?>">
                            <input type="text" name="startDate[]" class="form-control" value="<?=_escape($v['startDate']);?>" required/>
                            <span class="input-group-addon"><i class="fa fa-th"></i></span>
                        </div>
					</td>
					<td style="width:160px;">
						<div class="input-group date" id="datepicker7<?=_escape($v['id']);?>">
                            <?php if(_escape($v['endDate']) != '0000-00-00') : ?>
                            <input type="text" name="endDate[]" class="form-control" value="<?=_escape($v['endDate']);?>" />
                            <?php else : ?>
                            <input type="text" name="endDate[]" class="form-control" />
                            <?php endif; ?>
                            <span class="input-group-addon"><i class="fa fa-th"></i></span>
                        </div>
					</td>
					<td class="text-center">
						<button type="button" title="Comment" class="btn <?=(_escape($v['Comment']) == 'empty' ? 'btn-primary' : 'btn-danger');?>" data-toggle="modal" data-target="#comments-<?=_escape($v['id']);?>"><i class="fa fa-comment"></i></button>
						<!-- Modal -->
						<div class="modal fade" id="comments-<?=_escape($v['id']);?>">
							
							<div class="modal-dialog">
								<div class="modal-content">
						
									<!-- Modal heading -->
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h3 class="modal-title"><?=_t( 'Comments' );?></h3>
									</div>
									<!-- // Modal heading END -->
									
									<!-- Modal body -->
									<div class="modal-body">
										<textarea id="<?=_escape($v['id']);?>" class="form-control" name="comment[]" rows="5" data-height="auto"><?=_escape($v['comment']);?></textarea>
                                        <input type="button" class="btn btn-default" value="Insert Timestamp" onclick="addMsg('<?=\Jenssegers\Date\Date::now()->format('D, M d, o @ h:i A');?> <?=get_name(get_persondata('personID'));?>','<?=_escape($v['id']);?>'); return false;" />
									</div>
									<!-- // Modal body END -->
									
									<!-- Modal footer -->
									<div class="modal-footer">
										<a href="#" class="btn btn-default" data-dismiss="modal"><?=_t( 'Close' );?></a> 
									</div>
									<!-- // Modal footer END -->
						
								</div>
							</div>
							<input type="hidden" name="id[]" value="<?=_escape($v['id']);?>" />
						</div>
						<!-- // Modal END -->
					</td>
					<td class="text-center">
						<button type="button" title="Delete" class="btn bt-sm" data-toggle="modal" data-target="#delete-<?=_escape($v['id']);?>"><i class="fa fa-trash-o"></i></button>
						<!-- Modal -->
						<div class="modal fade" id="delete-<?=_escape($v['id']);?>">
							
							<div class="modal-dialog">
								<div class="modal-content">
						
									<!-- Modal heading -->
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h3 class="modal-title"><?=_escape($v['Code']);?></h3>
									</div>
									<!-- // Modal heading END -->
									
									<!-- Modal body -->
									<div class="modal-body">
										<p><?=_t( 'Are you sure you want to delete this student\'s hiatus status?' );?></p>
									</div>
									<!-- // Modal body END -->
									
									<!-- Modal footer -->
									<div class="modal-footer">
										<a href="<?=get_base_url();?>stu/deleteSHIS/<?=_escape($v['id']);?>" class="btn btn-default"><?=_t( 'Delete' );?></a>
										<a href="#" class="btn btn-primary" data-dismiss="modal"><?=_t( 'Close' );?></a> 
									</div>
									<!-- // Modal footer END -->
						
								</div>
							</div>
						</div>
						<!-- // Modal END -->
					</td>
				</tr>
				<!-- // Table row END -->
				<?php } endif; ?>
				
			</tbody>
			<!-- // Table body END -->
			
		</table>
		<!-- // Table END -->
		
		<!-- Form actions -->
		<div class="form-actions">
		    <?php if(_escape($shis[0]['stuID']) != '') : ?>
			<button type="submit"<?=sids();?> class="btn btn-icon btn-primary glyphicons circle_ok"><i></i><?=_t( 'Save' );?></button>
			<?php endif; ?>
			<button type="button"<?=sids();?> class="btn btn-icon btn-primary glyphicons circle_plus" data-toggle="modal" data-target="#md-ajax"><i></i><?=_t( 'Add' );?></button>
			<button type="button" class="btn btn-icon btn-primary glyphicons circle_minus" onclick="window.location='<?=get_base_url();?>stu/'"><i></i><?=_t( 'Cancel' );?></button>
		</div>
		<!-- // Form actions END -->
		
	</form>
	<!-- // Form END -->
	
	<!-- Modal -->
	<div class="modal fade" id="md-ajax">
		<form class="form-horizontal" data-collabel="3" data-alignlabel="left" action="<?=get_base_url();?>stu/shis/<?=_escape($stu->stuID);?>/" id="validateSubmitForm" method="post" autocomplete="off">
		<div class="modal-dialog">
			<div class="modal-content">
	
				<!-- Modal heading -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title"><?=_t( 'Comments' );?></h3>
				</div>
				<!-- // Modal heading END -->
				
				<!-- Modal body -->
				<div class="modal-body">
					<div class="form-group">
                        <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Hiatus' );?></label>
                        <div class="col-md-8">
	                        <select name="code" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
	                            <option value="">&nbsp;</option>
	                            <option value="W"><?=_t( 'Withdrawal' );?></option>
	                            <option value="LOA"><?=_t( 'Leave of Absence' );?></option>
	                            <option value="SA"><?=_t( 'Study Abroad' );?></option>
	                            <option value="ILLN"><?=_t( 'Illness' );?></option>
	                            <option value="DISM"><?=_t( 'Dismissal' );?></option>
	                        </select>
                       </div>
                    </div>
                    
                    <div class="form-group">
                    	<label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Start Date' );?></label>
                    	<div class="col-md-8">
	                        <div class="input-group date" id="datepicker9">
	                            <input type="text" name="startDate" class="form-control" required/>
	                            <span class="input-group-addon"><i class="fa fa-th"></i></span>
	                        </div>
                       </div>
                    </div>
                    
                    <div class="form-group">
                    	<label class="col-md-3 control-label"><?=_t( 'End Date' );?></label>
                    	<div class="col-md-8">
	                        <div class="input-group date" id="datepicker9">
	                            <input type="text" name="endDate" class="form-control" />
	                            <span class="input-group-addon"><i class="fa fa-th"></i></span>
	                        </div>
                       </div>
                    </div>
                    
                    <div class="form-group">
                    	<label class="col-md-3 control-label"><?=_t( 'Comment' );?></label>
                    	<div class="col-md-8">
	                        <textarea id="comment" class="form-control" name="comment" rows="5" data-height="auto"></textarea>
	                        <input type="button" class="btn btn-default" value="Insert Timestamp" onclick="addMsg('<?=\Jenssegers\Date\Date::now()->format('D, M d, o @ h:i A');?> <?=get_name(get_persondata('personID'));?>','comment'); return false;" />
                       </div>
                    </div>
				</div>
				<!-- // Modal body END -->
				
				<!-- Modal footer -->
				<div class="modal-footer">
                    <input type="hidden" name="stuID" value="<?=_escape($stu->stuID);?>" />
                    <input type="hidden" name="addDate" value="<?=\Jenssegers\Date\Date::now()->format('Y-m-d');?>" />
                    <input type="hidden" name="addedBy" value="<?=get_persondata('personID');?>" />
					<button type="submit" class="btn btn-default"><?=_t( 'Submit' );?></button>
					<a href="#" class="btn btn-primary" data-dismiss="modal"><?=_t( 'Cancel' );?></a>
				</div>
				<!-- // Modal footer END -->
	
			</div>
		</div>
		</form>
	</div>
	<!-- // Modal END -->
	
	<!-- Modal -->
    <div class="modal fade" id="FERPA">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal heading -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title"><?=_t( 'Family Educational Rights and Privacy Act (FERPA)' );?></h3>
                </div>
                <!-- // Modal heading END -->
                <!-- Modal body -->
                <div class="modal-body">
                    <p><?=_t('"FERPA gives parents certain rights with respect to their children\'s education records. 
                    These rights transfer to the student when he or she reaches the age of 18 or attends a school beyond 
                    the high school level. Students to whom the rights have transferred are \'eligible students.\'"');?></p>
                    <p><?=_t('If the FERPA restriction states "Yes", then the student has requested that none of their 
                    information be given out without their permission. To get a better understanding of FERPA, visit 
                    the U.S. DOE\'s website @ ') . 
                    '<a href="http://www2.ed.gov/policy/gen/guid/fpco/ferpa/index.html">http://www2.ed.gov/policy/gen/guid/fpco/ferpa/index.html</a>.';?></p>
                </div>
                <!-- // Modal body END -->
                <!-- Modal footer -->
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?=_t( 'Close' );?></a> 
                </div>
                <!-- // Modal footer END -->
            </div>
        </div>
    </div>
    <!-- // Modal END -->
	
</div>	
		
		</div>
		<!-- // Content END -->
<?php $app->view->stop(); ?>