<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * SPRO Record View
 * 
 * This view is used when viewing a student record via
 * the SPRO screen.
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
$stu = get_student(_escape($prog[0]['stuID']));
$tags = "{tag: '".implode("'},{tag: '", tagList())."'}";
?>

<ul class="breadcrumb">
    <li><?=_t( 'You are here');?></li>
    <li><a href="<?=get_base_url();?>dashboard/" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
    <li class="divider"></li>
    <li><a href="<?=get_base_url();?>stu/" class="glyphicons search"><i></i> <?=_t( 'Search Student' );?></a></li>
    <li class="divider"></li>
    <li><?=get_name(_escape($stu->stuID));?> <?=_t( '(SPRO)' );?></li>
</ul>

<div class="innerLR">
    
    <?php get_stu_header(_escape($stu->stuID)); ?>
    
    <div class="separator line bottom"></div>
    
    <?=_etsis_flash()->showMessage();?>

    <!-- Form -->
    <form class="form-horizontal margin-none" action="<?=get_base_url();?>stu/<?=$stu->stuID;?>/" id="validateSubmitForm" method="post" autocomplete="off">
        
        <!-- Widget -->
        <div class="widget widget-heading-simple widget-body-gray">
            
            <!-- Tabs Heading -->
            <div class="tabsbar">
                <ul>
                    <li class="glyphicons user active"><a href="<?=get_base_url();?>stu/<?=_escape($stu->stuID);?>/" data-toggle="tab"><i></i> <?=_t( 'Student Profile (SPRO)' );?></a></li>
                    <li class="glyphicons package"><a href="<?=get_base_url();?>stu/stac/<?=_escape($stu->stuID);?>/"><i></i> <?=_t( 'Student Academic Credits (STAC)' );?></a></li>
                    <li class="glyphicons tags tab-stacked"><a href="<?=get_base_url();?>stu/sttr/<?=_escape($stu->stuID);?>/"><i></i> <?=_t( 'Student Terms (STTR)' );?></a></li>
                    <li class="glyphicons history tab-stacked"><a href="<?=get_base_url();?>stu/shis/<?=_escape($stu->stuID);?>/"><i></i> <span><?=_t( 'Student Hiatus (SHIS)' );?></span></a></li>
                </ul>
            </div>
            <!-- // Tabs Heading END -->
            
            <div class="widget-body">
            
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-12">
                        
                        <!-- Table -->
                        <table class="table table-striped table-bordered table-condensed table-white">
                        
                            <!-- Table heading -->
                            <thead>
                                <tr>
                                    <th class="text-center">&nbsp;</th>
                                    <th class="text-center"><?=_t( 'Academic Program' );?></th>
                                    <th class="text-center"><?=_t( 'Academic Level' );?></th>
                                    <th class="text-center"><?=_t( 'Status' );?></th>
                                    <th class="text-center"><?=_t( 'Status Date' );?></th>
                                    <th class="text-center"><?=_t( 'Admit Status' );?></th>
                                </tr>
                            </thead>
                            <!-- // Table heading END -->
                            
                            <!-- Table body -->
                            <tbody>
                            <?php if($prog != '') : foreach($prog as $k => $v) { ?>
                            <tr class="gradeX">
                                <td class="text-center"><a href="<?=get_base_url();?>stu/sacp/<?=_escape($v['id']);?>/"><img src="<?=get_base_url();?>static/common/theme/images/cascade.png" /></a></td>
                                <td><input class="form-control center" type="text" readonly value="<?=_escape($v['acadProgCode']);?>" /></td>
                                <td><input class="form-control center" type="text" readonly value="<?=_escape($v['progAcadLevel']);?>" /></td>
                                <td><input class="form-control center" type="text" readonly value="<?=_escape($v['currStatus']);?>" /></td>
                                <td><input class="form-control center" type="text" readonly value="<?=_escape($v['statusDate']);?>" /></td>
                                <td><input class="form-control center" type="text" readonly value="<?=_escape($admit->admitStatus);?>" /></td>
                            </tr>
                            <?php } endif; ?>
                            </tbody>
                            <!-- // Table body END -->
                            
                        </table>
                        <!-- // Table END -->
                        
                    </div>
                    <!-- // Column END -->
                </div>
                <!-- // Row END -->
            
                <hr class="separator" />
                
                <div class="separator line bottom"></div>
                
                <!-- Column -->
                <div class="col-md-6">
                    
                    <!-- Group -->
					<div class="form-group">
                        <label class="col-md-3 control-label"><?=_t( 'Tags' );?> <a href="#tags" data-toggle="modal"><img src="<?=get_base_url();?>static/common/theme/images/help.png" /></a></label>
						<div class="col-md-8">
                            <input type="hidden" id="input-tags"<?=sio();?> name="tags" value="<?=_escape($prog[0]['tags']);?>" />
						</div>
					</div>
					<!-- // Group END -->
                    
                </div>
                <!-- // Column END -->
                
                <!-- Column -->
                <div class="col-md-6">
                    
                    <!-- Group -->
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=_t( 'Status' );?></label>
                        <div class="col-md-8">
                            <select name="status"<?=sio();?> class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
                                <option value="">&nbsp;</option>
                                <option value="A"<?=selected('A',_escape($stu->stuStatus),false);?>><?=_t( 'Active' );?></option>
                                <option value="I"<?=selected('I',_escape($stu->stuStatus),false);?>><?=_t( 'Inactive' );?></option>
                            </select>
                        </div>
                    </div>
                    <!-- // Group END -->
                    
                </div>
                <!-- // Column END -->
                
                <hr class="separator" />
                
                <div class="separator line bottom"></div>
                
                <hr class="separator" />
                
                <!-- Form actions -->
                <div class="form-actions">
                    <button type="submit"<?=sids();?> class="btn btn-icon btn-primary glyphicons circle_ok"><i></i><?=_t( 'Save' );?></button>
                    <button type="button" class="btn btn-icon btn-primary glyphicons circle_minus" onclick="window.location='<?=get_base_url();?>stu/'"><i></i><?=_t( 'Cancel' );?></button>
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
    
    <div class="modal fade" id="tags">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal heading -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title"><?=_t( 'Tags' );?></h3>
				</div>
				<!-- // Modal heading END -->
                <div class="modal-body">
                    <p><?=_t( "Tags are only for internal use, but they let staff members identify students based on unique or particular characteristics. Tags are like 'metadata' or 'keywords' attached to students in the system to make them easier to find or communicate with. Nevertheless, feel free to use them in a way that is most beneficial for your institution." );?></p>
                </div>
                <div class="modal-footer">
                    <a href="#" data-dismiss="modal" class="btn btn-primary"><?=_t( 'Cancel' );?></a>
                </div>
           	</div>
      	</div>
    </div>
    
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