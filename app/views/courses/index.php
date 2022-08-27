<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * myetSIS Course Search View
 *  
 * @license GPLv3
 * 
 * @since       4.3
 * @package     eduTrac SIS
 * @author      Joshua Parker <joshmac3@icloud.com>
 */

$app = \Liten\Liten::getInstance();
$app->view->extend('_layouts/myetsis/' . _h(get_option('myetsis_layout')) . '.layout');
$app->view->block('myetsis');
?>

<script type='text/javascript'>//<![CDATA[ 
$(window).load(function(){
    $("tr input[type=checkbox]").click(function(){
        var countchecked = $("tr input[type=checkbox]:checked").length;
    
        if(countchecked >= <?=_h(get_option('number_of_courses'));?>) 
        {
            $('tr input[type=checkbox]').not(':checked').attr("disabled",true);
        }
        else
        {
            $('tr input[type=checkbox]').not(':checked').attr("disabled",false);
        }
    });
});//]]>
</script>

<div class="col-md-12">

	<h3 class="glyphicons search"><i></i><?=_t( 'Search Courses' );?></h3>
	
	<?=_etsis_flash()->showMessage();?>
	
    <?php $app->hook->do_action('course_reg_message'); ?>
	
	<?php if(_h(get_option('reg_instructions')) != '') { ?>
		<div class="widget widget-heading-simple widget-body-white">
			<div class="widget-body">
				<div class="alerts alerts-info">
					<p><?=_h(get_option('reg_instructions'));?></p>
				</div>
			</div>
		</div>
	<?php } ?>
	
	<?php if(person_has_restriction() != false) { ?>
		<div class="widget widget-heading-simple widget-body-white">
			<div class="widget-body">
				<div class="alerts alerts-error">
					<p><?=_t( 'You have a hold on your account which is currently restricting you from registering for a course(s). Please contact the following office(s)/department(s) to inquire about the hold(s) on your account: ' );?><?=person_has_restriction();?></p>
				</div>
			</div>
		</div>
	<?php } ?>
	
	<!-- Form -->
    <form class="margin-none" action="<?=get_base_url();?>courses/" id="validateSubmitForm" method="post" autocomplete="off">

	<!-- Widget -->
	<div class="widget widget-heading-simple widget-body-gray">
		<div class="widget-body">
		<!-- Table -->
		<table class="dynamicTable tableTools table table-striped table-bordered table-condensed table-primary">
		
			<!-- Table heading -->
			<thead>
				<tr>
					<th class="text-center"><?=_t( 'Course Section' );?></th>
					<th class="text-center"><?=_t( 'Title' );?></th>
					<th class="text-center"><?=_t( 'Meeting Day(s)' );?></th>
                    <th class="text-center"><?=_t( 'Time' );?></th>
                    <th class="text-center"><?=_t( 'Credits' );?></th>
                    <th class="text-center"><?=_t( 'Location' );?></th>
                    <th class="text-center"><?=_t( 'Info' );?></th>
                    <?php if(is_user_logged_in()) : ?>
                    <th<?=isRegistrationOpen();?> class="text-center"><?=_t( 'Select' );?></th>
                    <?php endif; ?>
				</tr>
			</thead>
			<!-- // Table heading END -->
			
			<!-- Table body -->
			<tbody>
			<?php if($sect != '') : foreach($sect as $k => $v) { ?>
            <tr class="gradeX">
                <td class="text-center"><?=_h($v['courseSection']);?></td>
                <td class="text-center"><?=_h($v['secShortTitle']);?></td>
                <td class="text-center"><?=_h($v['dotw']);?></td>
                <td class="text-center"><?=_h($v['startTime'].' To '.$v['endTime']);?></td>
                <td class="text-center"><?=_h($v['minCredit']);?></td>
                <td class="text-center"><?=_h($v['locationName']);?></td>
                <td>
                	<center><button data-toggle="modal" data-target="#info-<?=_h($v['courseSecID']);?>" class="btn btn-xs btn-purple" type="button"><i class="fa fa-info"></i></button></center>
                	<!-- Modal -->
					<div class="modal fade" id="info-<?=_h($v['courseSecID']);?>">
						
						<div class="modal-dialog">
							<div class="modal-content">
					
								<!-- Modal heading -->
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h3 class="modal-title"><?=_h($v['courseSection']);?> <?=_t( 'Section Info' );?></h3>
								</div>
								<!-- // Modal heading END -->
								
								<!-- Modal body -->
								<div class="modal-body">
									<table>
                                		<tr>
                                			<td><strong><?=_t( 'Instructor:' );?></strong></td>
                                			<td><?=get_name(_h($v['facID']));?></td>
                                		</tr>
                                		<tr>
                                			<td><strong><?=_t( 'Description:' );?></strong></td>
                                            <td><?=_escape($v['courseDesc']);?></td>
                                		</tr>
                                		<tr>
                                			<td><strong><?=_t( 'Comment:' );?></strong></td>
                                			<td><?=_h(_escape($v['comment']));?></td>
                                		</tr>
                                		<tr>
                                			<td><strong><?=_t( 'Course Fee:' );?></strong></td>
                                			<td><?=money_format('%i',(double)_h($v['courseFee']));?></td>
                                		</tr>
                                		<tr>
                                			<td><strong><?=_t( 'Lab Fee:' );?></strong></td>
                                			<td><?=money_format('%i',(double)_h($v['labFee']));?></td>
                                		</tr>
                                		<tr>
                                			<td style="width:100px;"><strong><?=_t( 'Material Fee:' );?></strong></td>
                                			<td><?=money_format('%i',(double)_h($v['materialFee']));?></td>
                                		</tr>
                                	</table>
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
                </td>
                <?php if(is_user_logged_in()) : ?>
                <td<?=isRegistrationOpen();?> class="text-center">
                    <?php if(_h($v['termCode']) == _h(get_option('registration_term'))) : ?>
                    <?php if(student_can_register()) : ?>
                    <?php if(crse_prereq(get_persondata('personID'),_h($v['courseSecID'])) && etsis_prereq_rule(get_persondata('personID'), _h($v['courseID']))) : ?>
                    <input<?=getStuSec(_h($v['courseSecCode']),_h($v['termCode']));?> type="checkbox" name="courseSecID[]" value="<?=_h($v['courseSecID']);?>" />
                    <?php endif; endif; endif; ?>
                </td>
                <?php endif; ?>
            </tr>
			<?php } endif; ?>
			</tbody>
			<!-- // Table body END -->
			
		</table>
		<!-- // Table END -->
		<hr class="separator" />
        <?php if(is_user_logged_in()) : ?>
		<!-- Form actions -->
		<div<?=isRegistrationOpen();?> class="form-actions">
			<input type="hidden" name="regTerm" value="<?=_h(get_option('registration_term'));?>" />
            <?php if(student_can_register()) : ?>
			<button type="submit" class="btn btn-icon btn-primary glyphicons circle_plus"><i></i><?=_t( 'Add to Cart' );?></button>
            <?php endif; ?>
		</div>
		<!-- // Form actions END -->
        <?php endif; ?>
		</div>
	</div>
	</form>
    
    <div class="separator bottom" style="margin-bottom:15em;"></div>

</div>
	</div>
</div>

	
		</div>
		<!-- // Content END -->
<?php $app->view->stop(); ?>