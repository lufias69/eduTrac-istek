<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * Add Staff View
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
$tags = "{tag: '".implode("'},{tag: '", get_staff_tags())."'}";
?>

<ul class="breadcrumb">
	<li><?=_t( 'You are here' );?></li>
	<li><a href="<?=get_base_url();?>dashboard/" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
	<li class="divider"></li>
	<li><a href="<?=get_base_url();?>staff/" class="glyphicons search"><i></i> <?=_t( 'Search Staff' );?></a></li>
    <li class="divider"></li>
	<li><?=_t( 'Add Staff' );?></li>
</ul>

<h3><?=_t( 'Add Staff' );?> - <?=get_name(_escape($person['personID']));?></h3>
<div class="innerLR">
    
    <?=_etsis_flash()->showMessage();?>

	<!-- Form -->
	<form class="form-horizontal margin-none" action="<?=get_base_url();?>staff/add/<?=_escape($person['personID']);?>/" id="validateSubmitForm" method="post" autocomplete="off">
		
		<!-- Widget -->
		<div class="widget widget-heading-simple widget-body-gray">
		
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
							<label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Staff' );?></label>
							<div class="col-md-8">
								<input type="text" readonly class="form-control" value="<?=get_name(_escape($person['personID']));?> - <?=_escape($person['personID']);?>" />
							</div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Employment Type' );?></label>
                            <div class="col-md-8">
                                <select name="jobStatusCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
                                    <option value="">&nbsp;</option>
                                    <?php table_dropdown('job_status',null,'typeCode','typeCode','type',$app->req->post['jobStatusCode'] != '' ? $app->req->post['jobStatusCode'] : ''); ?>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Staff Type' );?></label>
                            <div class="col-md-8">
                                <select name="staffType" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
                                    <option value="">&nbsp;</option>
                                    <option value="FAC"<?=selected('FAC',_escape($person['personType']),false);?>><?=_t( 'Faculty' );?></option>
                                    <option value="STA"<?=selected('STA',_escape($person['personType']),false);?>><?=_t( 'Staff' );?></option>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
						
						<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Supervisor' );?></label>
                            <div class="col-md-8">
                                <select name="supervisorID" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
                                    <option value="">&nbsp;</option>
                                    <?php supervisor(_escape($person['personID']), $app->req->post['supervisorID'] != '' ? $app->req->post['supervisorID'] : ''); ?>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Job Title' );?></label>
                            <div class="col-md-8">
                                <select name="jobID" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
                                    <option value="">&nbsp;</option>
                                    <?php table_dropdown('job',null,'id','id','title',$app->req->post['jobID'] != '' ? $app->req->post['jobID'] : ''); ?>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
						
						<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Building' );?></label>
                            <div class="col-md-8">
                                <select name="buildingCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true">
                                    <option value="NULL">&nbsp;</option>
                                    <?php table_dropdown('building','buildingCode <> "NULL"','buildingCode','buildingCode','buildingName',$app->req->post['buildingCode'] != '' ? $app->req->post['buildingCode'] : ''); ?>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Office' );?></label>
                            <div class="col-md-8">
                                <select name="officeCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true">
                                    <option value="NULL">&nbsp;</option>
                                    <?php table_dropdown('room','roomCode <> "NULL"','roomCode','roomCode','roomNumber',$app->req->post['officeCode'] != '' ? $app->req->post['officeCode'] : ''); ?>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Office Phone' );?></label>
                            <div class="col-md-8">
                                <input type="text" name="office_phone" value="<?=$app->req->post['office_phone'] != '' ? $app->req->post['office_phone'] : '';?>" class="form-control" />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'School' );?></label>
                            <div class="col-md-8">
                                <select name="schoolCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true">
                                    <option value="NULL">&nbsp;</option>
                                    <?php table_dropdown('school','schoolCode <> "NULL"','schoolCode','schoolCode','schoolName',$app->req->post['schoolCode'] != '' ? $app->req->post['schoolCode'] : ''); ?>
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
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Department' );?></label>
                            <div class="col-md-8">
                                <select name="deptCode" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
                                    <option value="NULL">&nbsp;</option>
                                    <?php table_dropdown('department','deptCode <> "NULL"','deptCode','deptCode','deptName', $app->req->post['deptCode'] != '' ? $app->req->post['deptCode'] : ''); ?>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Hire Date' );?></label>
                            <div class="col-md-8">
                                <div class="input-group date col-md-8" id="datepicker6">
                                    <input class="form-control" name="hireDate" value="<?=$app->req->post['hireDate'] != '' ? $app->req->post['hireDate'] : '';?>" type="text" />
                                    <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Start Date' );?></label>
                            <div class="col-md-8">
                                <div class="input-group date col-md-8" id="datepicker7">
                                    <input class="form-control" name="startDate" value="<?=$app->req->post['startDate'] != '' ? $app->req->post['startDate'] : '';?>" type="text" />
                                    <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'End Date' );?></label>
                            <div class="col-md-8">
                                <div class="input-group date col-md-8" id="datepicker8">
                                    <input class="form-control" name="endDate" value="<?=$app->req->post['endDate'] != '' ? $app->req->post['endDate'] : '';?>" type="text" />
                                    <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Status' );?></label>
                            <div class="col-md-8">
                                <select name="status" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
                                    <option value="">&nbsp;</option>
                                    <option value="A" selected="selected"><?=_t( 'A Active' );?></option>
                                    <option value="I"><?=_t( 'I Inactive' );?></option>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Tags' );?></label>
                            <div class="col-md-8">
                                <input type="hidden" id="input-tags" name="tags" value="<?=(_escape($app->req->post['tags']) != '' ? _escape($app->req->post['tags']) : '');?>" />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Approved By' );?></label>
                            <div class="col-md-8">
                                <input type="text" readonly value="<?=get_name(_escape(get_persondata('personID')));?>" class="form-control" required />
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
					<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i><?=_t( 'Save' );?></button>
					<button type="button" class="btn btn-icon btn-primary glyphicons circle_minus" onclick="window.location='<?=get_base_url();?>staff/'"><i></i><?=_t( 'Cancel' );?></button>
				</div>
				<!-- // Form actions END -->
				
			</div>
		</div>
		<!-- // Widget END -->
		
	</form>
	<!-- // Form END -->
	
</div>	
		
		</div>
        
<script src="<?=get_base_url();?>static/assets/components/modules/querybuilder/selectize/js/standalone/selectize.min.js" type="text/javascript"></script>
<script type="text/javascript">
$('#input-tags').selectize({
    plugins: ['remove_button'],
    delimiter: ',',
    persist: false,
    maxItems: null,
    valueField: 'tag',
    labelField: 'tag',
    searchField: ['tag'],
    options: [
        <?=$tags;?>
    ],
    render: {
        item: function(item, escape) {
            return '<div>' +
                (item.tag ? '<span class="tag">' + escape(item.tag) + '</span>' : '') +
            '</div>';
        },
        option: function(item, escape) {
            var caption = item.tag ? item.tag : null;
            return '<div>' +
                (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
            '</div>';
        }
    },
    create: function(input) {
        return {
            tag: input
        };
    }
});
</script>

		<!-- // Content END -->
<?php $app->view->stop(); ?>