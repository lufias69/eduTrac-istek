<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * Manager person role View
 * 
 * This view is used when editing a person's role.
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
$screen = 'prole';
?>

<ul class="breadcrumb">
	<li><?=_t( 'You are here');?></li>
	<li><a href="<?=get_base_url();?>dashboard/" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
	<li class="divider"></li>
	<li><a href="<?=get_base_url();?>nae/" class="glyphicons search"><i></i> <?=_t( 'Search Person' );?></a></li>
	<li class="divider"></li>
    <li><a href="<?=get_base_url();?>nae/<?=_escape($nae[0]['personID']);?>/" class="glyphicons vcard"><i></i> <?=get_name(_escape($nae[0]['personID']));?></a></li>
    <li class="divider"></li>
	<li><?=_t( 'Manage Person Role' );?></li>
</ul>

<h3><?=get_name(_escape($nae[0]['personID']));?>: <?=_t( 'ID#' );?> <?=(_escape($nae[0]['altID']) != '' ? _escape($nae[0]['altID']) : _escape($nae[0]['personID']));?></h3>
<div class="innerLR">
    
    <?=_etsis_flash()->showMessage();?>
    
    <?php jstree_sidebar_menu($screen,'','',$nae,$staff); ?>

	<!-- Form -->
	<form class="form-horizontal margin-none" action="<?=get_base_url();?>nae/role/<?=_escape($nae[0]['personID']);?>/" id="validateSubmitForm" method="post" autocomplete="off">
		
		<!-- Widget -->
		<div class="widget widget-heading-simple widget-body-gray <?=($app->hook->has_filter('sidebar_menu')) ? 'col-md-12' : 'col-md-10';?>">
			
			<div class="widget-body">
						
				<!-- Table -->
				<table class="dynamicTable tableTools table table-striped table-bordered table-condensed table-white">
				
					<!-- Table heading -->
					<thead>
						<tr>
							<th><?=_t( 'Role' );?></th>
							<th><?=_t( 'Member' );?></th>
							<th><?=_t( 'Not Member' );?></th>
						</tr>
					</thead>
					<!-- // Table heading END -->
				
					<!-- Table body -->
					<tbody>
						<?php 
						$roleACL = new \app\src\ACL(_escape((int)$nae[0]['personID']));
							$role = $roleACL->getAllRoles('full');
							foreach ($role as $k => $v) {
								echo '<tr><td>'._escape($v['Name']).'</td>';
								
								echo "<td class=\"center\"><input type=\"radio\" name=\"role_" . _escape($v['ID']) . "\" id=\"role_" . _escape($v['ID']) . "_1\" value=\"1\"";
    							if ($roleACL->userHasRole(_escape($v['ID']))) { echo " checked=\"checked\""; }
    							echo " /></td>";
								 
								echo "<td class=\"center\"><input type=\"radio\" name=\"role_" . _escape($v['ID']) . "\" id=\"role_" . _escape($v['ID']) . "_0\" value=\"0\"";
    							if (!$roleACL->userHasRole(_escape($v['ID']))) { echo " checked=\"checked\""; }
    							echo " /></td></tr>";
							}
						?>
					</tbody>
					<!-- // Table body END -->
		
			</table>
			<!-- // Table END -->
			
				<hr class="separator" />
				
				<!-- Form actions -->
				<div class="form-actions">
					<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i><?=_t( 'Save' );?></button>
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