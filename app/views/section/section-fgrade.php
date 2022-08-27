<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * Course Section Final Grading View
 *
 * @license GPLv3
 * 
 * @since       4.2.2
 * @package     eduTrac SIS
 * @author      Joshua Parker <joshmac3@icloud.com>
 */
$app = \Liten\Liten::getInstance();
$app->view->extend('_layouts/dashboard');
$app->view->block('dashboard');
$acl = new \app\src\ACL(get_persondata('personID'));
$screen = 'vsect';
?>

<ul class="breadcrumb">
	<li><?=_t( 'You are here' );?></li>
	<li><a href="<?=get_base_url();?>dashboard/" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
	<li class="divider"></li>
	<li><a href="<?=get_base_url();?>sect/" class="glyphicons search"><i></i> <?=_t( 'Search Section' );?></a></li>
	<li class="divider"></li>
	<li><a href="<?=get_base_url();?>sect/<?=_h($grade[0]['courseSecID']);?>/" class="glyphicons adjust_alt"><i></i> <?=_h($grade[0]['courseSection']);?></a></li>
    <li class="divider"></li>
	<li><?=_t( 'Course Section Final Grades' );?></li>
</ul>

<h3><?=_t( 'Final Grades for ' );?><?=_h($grade[0]['secShortTitle']);?></h3>
<div class="innerLR">
    
    <?=_etsis_flash()->showMessage();?>
    
    <?php jstree_sidebar_menu($screen,'',$sect); ?>

    <!-- Form -->
    <form class="form-horizontal margin-none" action="<?=get_base_url();?>sect/fgrade/<?=_h($grade[0]['courseSecID']);?>/" id="validateSubmitForm" method="post" autocomplete="off">

	<!-- Widget -->
	<div class="widget widget-heading-simple widget-body-gray <?=($app->hook->has_filter('sidebar_menu')) ? 'col-md-12' : 'col-md-10';?>">
            
		<div class="widget-body">
			
			<!-- Table -->
			<table class="table table-striped table-bordered table-condensed table-white">
			
				<!-- Table heading -->
				<thead>
					<tr>
                        <th class="text-center"><?=_t( 'Course Section' );?></th>
						<th class="text-center"><?=_t( 'Student' );?></th>
						<th class="text-center"><?=_t( 'Grade' );?></th>
                        <th style="display:none;">&nbsp;</th>
					</tr>
				</thead>
				<!-- // Table heading END -->
				
				<!-- Table body -->
				<tbody>
                <?php if($grade[0]['stuID'] != '') : foreach($grade as $k => $v) { ?>
                <tr class="gradeX">
                    <td class="text-center"><?=_h($v['courseSection']);?></td>
                    <td class="text-center">
                        <?=get_name(_h($v['stuID']));?>
                        <input type="hidden" name="stuID[]" value="<?=_h($v['stuID']);?>" />
                    </td>
                    <td class="text-center">
                        <?=grading_scale(_h($v['grade']));?>
                    </td>
                    <td style="display:none;">
                        <input type="hidden" name="courseSecCode" value="<?=_h($v['courseSecCode']);?>" />
                        <input type="hidden" name="termCode" value="<?=_h($v['termCode']);?>" />
                    </td>
                </tr>
                <?php } endif; ?>
				</tbody>
				<!-- // Table body END -->
				
			</table>
			<!-- // Table END -->
            
            <hr class="separator" />
    			
			<!-- Form actions -->
			<div class="form-actions">
				<?php if($grade[0]['facID'] == get_persondata('personID') || $acl->userHasRole(8) || hasPermission('submit_final_grades')) : ?>
			    <?php if($grade[0]['stuID'] != '') : ?>
			    <input type="hidden" name="attCredit" value="<?=_h($grade[0]['minCredit']);?>" />
			    <input type="hidden" name="courseSecID" value="<?=_h($grade[0]['courseSecID']);?>" />
				<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i><?=_t( 'Submit' );?></button>
                <?php endif; endif; ?>
				<button type="button" class="btn btn-icon btn-primary glyphicons circle_minus" onclick="window.location='<?=get_base_url();?>sect/<?=_h($grade[0]['courseSecID']);?>/'"><i></i><?=_t( 'Cancel' );?></button>
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