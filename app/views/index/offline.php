<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * myetSIS Offline View
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
if(_h(get_option('enable_myetsis_portal')) == 1) {
    etsis_redirect(get_base_url());
}
?>

		<div class="col-md-12">
			
			<div class="separator bottom"></div>
			<div class="separator bottom"></div>
		
			<div class="widget widget-heading-simple widget-body-white">
				<div class="widget-body">
					<div class="row">	
						<div class="col-md-12">
							<h5 class="strong"><?=_t( 'Offline' );?></h5>
							<div class="separator bottom"></div>
                            <section class="panel error-panel"><div class="alerts alerts-info"><?=nl2br(_h(get_option('myetsis_offline_message')));?></div></section>
						</div>
					</div>
				</div>
			</div>
		
		</div>
	</div>
</div>
	
		</div>
		<!-- // Content END -->
<?php $app->view->stop(); ?>