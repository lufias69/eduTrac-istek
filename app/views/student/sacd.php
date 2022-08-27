<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * View Academic Credits View
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
$stu = get_student(_escape($sacd->stuID));
?>

<ul class="breadcrumb">
    <li><?=_t( 'You are here' );?></li>
    <li><a href="<?=get_base_url();?>dashboard/" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
    <li class="divider"></li>
    <li><a href="<?=get_base_url();?>stu/" class="glyphicons search"><i></i> <?=_t( 'Search Student' );?></a></li>
    <li class="divider"></li>
    <li><a href="<?=get_base_url();?>stu/stac/<?=_escape($stu->stuID);?>/" class="glyphicons coins"><i></i> <?=_t( 'Academic Credits' );?></a></li>
    <li class="divider"></li>
    <li><?=_t( 'View Academic Credits (SACD)' );?></li>
</ul>

<div class="innerLR">

	<?php get_stu_header($stu->stuID); ?>
    
    <div class="separator line bottom"></div>
    
    <?=_etsis_flash()->showMessage();?>

	<!-- Form -->
	<form class="form-horizontal margin-none" action="<?=get_base_url();?>stu/sacd/<?=_escape($sacd->id);?>/" id="validateSubmitForm" method="post" autocomplete="off">
		
		<!-- Widget -->
		<div class="widget widget-heading-simple widget-body-gray">
			
			<div class="widget-body">
			
				<!-- Row -->
				<div class="row">
					<!-- Column -->
					<div class="col-md-6">
						
						<!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label"><?=_t( 'CRSE ID/Name/Sec' );?> <a href="<?=get_base_url();?>crse/<?=_escape($sacd->courseID);?>/"><img src="<?=get_base_url();?>static/common/theme/images/cascade.png" /></a></label>
							<div class="col-md-3">
								<input type="text" name="courseID" value="<?=_escape($sacd->courseID);?>" class="form-control" required/>
							</div>
							
							<div class="col-md-3">
								<input type="text" name="courseCode" value="<?=_escape($sacd->courseCode);?>" class="form-control" required/>
							</div>
							
							<div class="col-md-2">
                                <input type="text" name="sectionNumber" value="<?=_escape($sacd->sectionNumber);?>" class="form-control" <?=_escape($sacd->creditType) == 'TR' ? 'readonly required' : '';?>/>
                            </div>
						</div>
						<!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Short Title' );?></label>
                            <div class="col-md-8">
                                <input type="text" name="shortTitle" value="<?=_escape($sacd->shortTitle);?>" class="form-control" required/>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Long Title' );?></label>
                            <div class="col-md-8">
                                <input type="text" name="longTitle" value="<?=_escape($sacd->longTitle);?>" class="form-control" required/>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Subject' );?></label>
                            <div class="col-md-8">
                            	<select name="subjectCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
									<option value="">&nbsp;</option>
                                    <?php table_dropdown('subject', 'subjectCode <> "NULL"', 'subjectCode', 'subjectCode', 'subjectName', _escape($sacd->subjectCode)); ?>
	                        	</select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Acad Lvl/Crse Lvl' );?></label>
                            <div class="col-md-4">
                                <select name="acadLevelCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
                                    <option value="">&nbsp;</option>
                                    <?php table_dropdown('aclv',null,'code','code','name',_escape($sacd->acadLevelCode)); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="courseLevelCode" id="courseLevelCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
									<option value="">&nbsp;</option>
	                        		<?php table_dropdown('crlv', null, 'code', 'code', 'name', _escape($sacd->courseLevelCode)); ?>
	                        	</select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Department' );?></label>
                            <div class="col-md-8">
                            	<select name="deptCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
									<option value="">&nbsp;</option>
                            		<?php table_dropdown('department', 'deptTypeCode = "acad" AND deptCode <> "NULL"', 'deptCode', 'deptCode', 'deptName',_escape($sacd->deptCode)); ?>
                            	</select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Credit Type' );?></label>
                            <div class="col-md-8">
                            	<select name="creditType" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
									<option value="">&nbsp;</option>
	                        		<option value="I"<?=selected("I",_escape($sacd->creditType),false);?>><?=_t( 'I Institutional' );?></option>
	                        		<option value="TR"<?=selected("TR",_escape($sacd->creditType),false);?>><?=_t( 'TR Transfer' );?></option>
	                        	</select>
                            </div>
                        </div>
                        <!-- // Group END -->
						
					</div>
					<!-- // Column END -->
					
					<!-- Column -->
					<div class="col-md-6">
						
						<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Start/End Date' );?></label>
                            <div class="col-md-4">
                            	<div class="input-group date" id="datepicker6">
                                    <input class="form-control" name="startDate"<?=sio();?> value="<?=_escape($sacd->startDate);?>" type="text" required/>
                                    <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="input-group date" id="datepicker7">
                                    <input class="form-control" name="endDate"<?=sio();?> value="<?=_escape($sacd->endDate);?>" type="text" required/>
                                    <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <!-- // Group END -->
						
						<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Term/Rpt Term' );?></label>
                            <div class="col-md-4">
                            	<select name="termCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
									<option value="">&nbsp;</option>
                            		<?php table_dropdown('term', 'termCode <> "NULL"', 'termCode', 'termCode', 'termName',_escape($sacd->termCode)); ?>
                            	</select>
                            </div>
                            
                            <div class="col-md-4">
                                <input type="text" readonly name="reportingTerm" value="<?=_escape($sacd->reportingTerm);?>" class="form-control" required />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Att/Comp Cred' );?></label>
                            <div class="col-md-4">
                                <input type="text" name="attCred"<?=sio();?> value="<?=_escape(number_format($sacd->attCred,6));?>" class="form-control" required/>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="compCred"<?=sio();?> value="<?=_escape(number_format($sacd->compCred,6));?>" class="form-control" required/>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Grade/Grd Pts' );?></label>
                            <div class="col-md-4">
                                <input type="text" name="grade"<?=sio();?> value="<?=_escape($sacd->grade);?>" class="form-control" />
                            </div>
                            
                            <div class="col-md-4">
                                <input type="text" name="gradePoints"<?=sio();?> value="<?=_escape(number_format($sacd->gradePoints,6));?>" class="form-control" required/>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Status' );?></label>
                            <div class="col-md-8">
                                <?=stcs_status_select(_escape($sacd->status));?>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Status Date/Time' );?></label>
                            <div class="col-md-4">
                                <div class="input-group date" id="datepicker8">
                                    <input class="form-control" name="statusDate"<?=sio();?> value="<?=_escape($sacd->statusDate);?>" type="text" required/>
                                    <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="input-group bootstrap-timepicker">
                                    <input id="timepicker10" type="text" <?=sio();?> class="form-control" value="<?=_escape($sacd->statusTime);?>" required />
                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                </div>
                            </div>
                        </div>
                        <!-- // Group END -->
						
					</div>
					<!-- // Column END -->
				</div>
				<!-- // Row END -->
			
				<hr class="separator" />
				
				<!-- Form actions -->
				<div class="form-actions">
					<button type="submit"<?=sids();?> class="btn btn-icon btn-primary glyphicons circle_ok"><i></i><?=_t( 'Save' );?></button>
					<button type="button" class="btn btn-icon btn-primary glyphicons circle_minus" onclick="window.location='<?=get_base_url();?>stu/stac/<?=_escape($stu->stuID);?>/'"><i></i><?=_t( 'Cancel' );?></button>
				</div>
				<!-- // Form actions END -->
				
			</div>
		</div>
		<!-- // Widget END -->
		
	</form>
	<!-- // Form END -->
	
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