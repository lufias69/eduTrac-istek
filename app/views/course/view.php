<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * Edit course view.
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
include('ajax.php');
$screen = 'vcrse';
?>

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script src="<?=get_base_url();?>static/assets/plugins/tinymce/plugin.js"></script>
<script type="text/javascript">
	tinymce.init({selector: "textarea",plugins: [ "placeholder" ]});
</script>

<ul class="breadcrumb">
	<li><?=_t( 'You are here' );?></li>
	<li><a href="<?=get_base_url()?>dashboard/" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
	<li class="divider"></li>
	<li><a href="<?=get_base_url();?>crse/" class="glyphicons search"><i></i> <?=_t( 'Search Course' );?></a></li>
	<li class="divider"></li>
	<li><?=_h($crse->courseCode);?></li>
</ul>

<h3>
    <?=_h($crse->courseCode);?> 
    <span data-toggle="tooltip" data-original-title="Clone Course" data-placement="top">
        <a<?=ae('add_course');?> href="#crse<?=_h($crse->courseID);?>" data-toggle="modal" class="btn btn-primary"><i class="fa fa-copy"></i></a>
    </span>
</h3>
<div class="innerLR">
	
	<?=_etsis_flash()->showMessage();?>
    
    <?php jstree_sidebar_menu($screen, $crse); ?>

	<!-- Form -->
	<form class="form-horizontal margin-none" action="<?=get_base_url();?>crse/<?=_h($crse->courseID);?>/" id="validateSubmitForm" method="post" autocomplete="off">
        
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
							<label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Department' );?></label>
							<div class="col-md-8" id="divDept">
								<select name="deptCode"<?=cio();?> id="deptCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
									<option value="">&nbsp;</option>
                            		<?php table_dropdown('department', 'deptTypeCode = "acad" AND deptCode <> "NULL"', 'deptCode', 'deptCode', 'deptName', _h($crse->deptCode)); ?>
                            	</select>
							</div>
							<a<?=ae('access_forms');?> href="#dept" data-toggle="modal" title="Department" class="btn btn-primary"><i class="fa fa-plus"></i></a>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Subject' );?></label>
							<div class="col-md-8" id="divSubj">
								<select name="subjectCode"<?=cio();?> id="subjectCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
									<option value="">&nbsp;</option>
                                    <?php table_dropdown('subject', 'subjectCode <> "NULL"', 'subjectCode', 'subjectCode', 'subjectName', _h($crse->subjectCode)); ?>
	                        	</select>
	                       </div>
	                       <a<?=ae('access_forms');?> href="#subj" data-toggle="modal" title="Subject" class="btn btn-primary"><i class="fa fa-plus"></i></a>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Course Number' );?></label>
							<div class="col-md-8">
								<input class="form-control" type="text"<?=cio();?> name="courseNumber" value="<?=_h($crse->courseNumber);?>" required />
							</div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Course Level' );?></label>
                            <div class="col-md-8">
                                <select name="courseLevelCode" id="courseLevelCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
									<option value="">&nbsp;</option>
	                        		<?php table_dropdown('crlv', null, 'code', 'code', 'name', _h($crse->courseLevelCode)); ?>
	                        	</select>
                            </div>
                        </div>
                        <!-- // Group END -->
						
						<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Short Title' );?></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text"<?=cio();?> name="courseShortTitle" value="<?=_h($crse->courseShortTitle);?>" maxlength="25" required/>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Long Title' );?></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text"<?=cio();?> name="courseLongTitle" value="<?=_h($crse->courseLongTitle);?>" maxlength="60" required/>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Credit Type' );?></label>
							<div class="col-md-8">
								<select name="creditType"<?=cio();?> class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
									<option value="">&nbsp;</option>
									<option value="I"<?=selected('I',_h($crse->creditType),false);?>><?=_t( 'I Institutional' );?></option>
	                        	</select>
	                       </div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Effective / End Date' );?></label>
							<div class="col-md-4">
								<div class="input-group date col-md-12" id="datepicker6">
						    		<input class="form-control"<?=cio();?> name="startDate" type="text" value="<?=_h($crse->startDate);?>" required />
				    				<span class="input-group-addon"><i class="fa fa-th"></i></span>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="input-group date col-md-12" id="datepicker7">
					    			<input class="form-control"<?=cio();?> name="endDate" type="text" value="<?=(_h($crse->endDate) > '0000-00-00' ? _h($crse->endDate) : '');?>" />
				    				<span class="input-group-addon"><i class="fa fa-th"></i></span>
								</div>
							</div>
						</div>
						<!-- // Group END -->
                        
                        <?php 
                        /**
                         * CRSE Form Field (Left)
                         * 
                         * Action will print a form field or any type of data
                         * on the left side of the CRSE screen.
                         * 
                         * @since 6.1.10
                         * @param array $crse Course data object.
                         */
                        $app->hook->do_action('left_crse_view_form', $crse); 
                        ?>
						
					</div>
					<!-- // Column END -->
					
					<!-- Column -->
					<div class="col-md-6">
					    
					    <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Credits' );?></label>
                            <div class="col-md-4">
                                <input class="form-control" type="text"<?=cio();?> name="minCredit" value="<?=_h($crse->minCredit);?>" required/>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Academic Level' );?></label>
                            <div class="col-md-8">
                                <select name="acadLevelCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
                                    <option value="">&nbsp;</option>
                                    <?php table_dropdown('aclv',null,'code','code','name',_h($crse->acadLevelCode)); ?>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
					
						<!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Status' );?></label>
							<div class="col-md-4">
								<?=status_select(_h($crse->currStatus)); ?>
							</div>
							
							<div class="col-md-4">
						    	<input class="form-control"<?=cio();?> name="statusDate" type="text" readonly value="<?=\Jenssegers\Date\Date::parse(_h($crse->statusDate))->format('D, M d, o');?>" />
							</div>
						</div>
						<!-- // Group END -->
                        
                        <?php 
                        /**
                         * CRSE Form Field (Right)
                         * 
                         * Action will print a form field or any type of data
                         * on the right side of the CRSE screen.
                         * 
                         * @since 6.1.10
                         * @param array $crse Course data object.
                         */
                        $app->hook->do_action('right_crse_view_form', $crse); 
                        ?>
						
						<!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label"><?=_t( 'Approval Person' );?></label>
							<div class="col-md-6">
                                <input class="form-control" type="text" readonly value="<?=get_name(_h($crse->approvedBy));?>" />
							</div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label"><?=_t( 'Approval Date' );?></label>
							<div class="col-md-6">
								<input class="form-control" type="text" readonly value="<?=\Jenssegers\Date\Date::parse(_h($crse->approvedDate))->format('D, M d, o');?>" />
							</div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label"><?=_t( 'Last Update' );?></label>
							<div class="col-md-6">
								<input class="form-control" type="text" readonly value="<?=\Jenssegers\Date\Date::parse(_h($crse->LastUpdate))->format('D, M d, o h:i A');?>" />
							</div>
						</div>
						<!-- // Group END -->
                        <?php if(hasPermission('manage_business_rules')) : ?>
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Prerequisite' );?> <a href="<?=get_base_url();?>crse/<?=_h($crse->courseID);?>/prrl/"><img src="<?=get_base_url();?>static/common/theme/images/cascade.png" /></a></label>
                            <div class="col-md-1">
                                <input class="form-control" type="text" disabled value="<?=is_count_zero('course', 'rule', _escape($crse->rule));?>" class="center" />
                            </div>
                        </div>
                        <!-- // Group END -->
						<?php endif; ?>
						<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Additional Info' );?> <a href="<?=get_base_url();?>crse/<?=_h($crse->courseID);?>/addnl/"><img src="<?=get_base_url();?>static/common/theme/images/cascade.png" /></a></label>
                            <div class="col-md-1">
                                <?php
                                     if($crse->preReq != '' || $crse->allowAudit != 0  || $crse->allowWaitlist != 0 || 
                                     $crse->minEnroll != 0 || $crse->seatCap != 0) {
                                ?>
                                    <input class="form-control" type="text" disabled value="X" class="center" />
                                <?php } else { ?>
                                    <input class="form-control" type="text" disabled class="col-md-1" />
                                <?php } ?>
                            </div>
                        </div>
                        <!-- // Group END -->
						
					</div>
					<!-- // Column END -->
				</div>
				<!-- // Row END -->
			
				<hr class="separator" />
				
				<div class="separator line bottom"></div>
				
				<!-- Group -->
				<div class="form-group col-md-12">
					<div class="widget-body">
						<div class="col-md-12"><textarea id="mustHaveId"<?=cio();?> class="col-md-12 form-control" name="courseDesc" rows="8" placeholder="Enter the course description . . ."><?=_h($crse->courseDesc);?></textarea></div>
					</div>
				</div>
				<!-- // Group END -->
				
				<!-- Form actions -->
				<div class="form-actions">
					<button type="submit"<?=cids();?> class="btn btn-icon btn-primary glyphicons circle_ok"><i></i><?=_t( 'Save' );?></button>
                    <button type="button" class="btn btn-icon btn-primary glyphicons circle_minus" onclick="window.location='<?=get_base_url();?>crse/'"><i></i><?=_t( 'Cancel' );?></button>
				</div>
				<!-- // Form actions END -->
				
			</div>
		</div>
		<!-- // Widget END -->
		
	</form>
	<!-- // Form END -->
	
	<!-- Modal -->
    <div class="modal fade" id="crse<?=_h($crse->courseID);?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal heading -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title"><?=_h($crse->courseShortTitle);?> <?=_h($crse->courseCode);?></h3>
                </div>
                <!-- // Modal heading END -->
                <!-- Modal body -->
                <div class="modal-body">
                    <p><?=_t( "Are you sure you want to create a copy of this course?" );?></p>
                </div>
                <!-- // Modal body END -->
                <!-- Modal footer -->
                <div class="modal-footer">
                    <a href="<?=get_base_url();?>crse/<?=_h($crse->courseID);?>/clone/" class="btn btn-default"><?=_t( 'Yes' );?></a>
                    <a href="#" class="btn btn-primary" data-dismiss="modal"><?=_t( 'No' );?></a> 
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