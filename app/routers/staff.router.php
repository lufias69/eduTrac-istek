<?php
if (!defined('BASE_PATH'))
    exit('No direct script access allowed');
use \app\src\elFinder\elFinderConnector;
use \app\src\elFinder\elFinder;
use \app\src\elFinder\elFinderVolumeDriver;
use \app\src\elFinder\elFinderVolumeLocalFileSystem;
use \app\src\elFinder\elFinderVolumeS3;
use app\src\Core\Exception\NotFoundException;
use app\src\Core\Exception\Exception;
use PDOException as ORMException;
use Cascade\Cascade;

/**
 * Staff Router
 *  
 * @license GPLv3
 * 
 * @since       5.0.0
 * @package     eduTrac SIS
 * @author      Joshua Parker <joshmac3@icloud.com>
 */

/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 * */
function access($attr, $path, $data, $volume)
{
    return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
        ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
        : null;                                    // else elFinder decide it itself
}
/**
 * Before router middleware checks to see
 * if the user is logged in.
 */
$app->before('GET|POST', '/staff(.*)', function() {
    if (!is_user_logged_in()) {
        _etsis_flash()->error(_t('401 - Error: Unauthorized.'), get_base_url() . 'login' . '/');
        exit();
    }
    if (!hasPermission('access_staff_screen')) {
        _etsis_flash()->error(_t('403 - Error: Forbidden.'), get_base_url() . 'dashboard' . '/');
        exit();
    }
});

$app->group('/staff', function () use($app) {

    $app->match('GET|POST', '/', function () use($app) {

        if ($app->req->isPost()) {
            try {
                $post = $app->req->post['staff'];
                $search = $app->db->staff()
                    ->setTableAlias('a')
                    ->select('a.staffID,b.lname,b.fname,b.email,b.altID')
                    ->_join('person', 'a.staffID = b.personID', 'b')
                    ->whereLike('CONCAT(b.fname," ",b.lname)', "%$post%")->_or_()
                    ->whereLike('CONCAT(b.lname," ",b.fname)', "%$post%")->_or_()
                    ->whereLike('CONCAT(b.lname,", ",b.fname)', "%$post%")->_or_()
                    ->whereLike('b.altID', "%$post%")->_or_()
                    ->whereLike('a.staffID', "%$post%");
                $q = $search->find(function($data) {
                    $array = [];
                    foreach ($data as $d) {
                        $array[] = $d;
                    }
                    return $array;
                });
            } catch (NotFoundException $e) {
                Cascade::getLogger('error')->error($e->getMessage());
                _etsis_flash()->error(_etsis_flash()->notice(409));
            } catch (ORMException $e) {
                Cascade::getLogger('error')->error($e->getMessage());
                _etsis_flash()->error(_etsis_flash()->notice(409));
            } catch (Exception $e) {
                Cascade::getLogger('error')->error($e->getMessage());
                _etsis_flash()->error(_etsis_flash()->notice(409));
            }
        }

        etsis_register_style('form');
        etsis_register_style('table');
        etsis_register_script('select');
        etsis_register_script('select2');
        etsis_register_script('datatables');

        $app->view->display('staff/index', [
            'title' => 'Staff Lookup',
            'search' => $q
            ]
        );
    });

    $app->match('GET|POST|PATCH|PUT|OPTIONS|DELETE', '/connector/', function () use($app) {
        error_reporting(0);
        if (_escape(get_option('elfinder_driver')) === 'elf_local_driver') :
            _mkdir($app->config('file.savepath') . get_persondata('uname') . '/');
            $opts = array(
                // 'debug' => true,
                'roots' => array(
                    array(
                        'driver' => 'LocalFileSystem',
                        'path' => $app->config('file.savepath') . get_persondata('uname') . '/',
                        'alias' => 'Files',
                        'mimeDetect' => 'mime_content_type',
                        'mimefile' => BASE_PATH . 'app/src/elFinder/mime.types',
                        'accessControl' => 'access',
                        'attributes' => array(
                            array(
                                'read' => true,
                                'write' => true,
                                'locked' => false
                            )
                        ),
                        'uploadAllow' => [
                            'image/png', 'image/gif', 'image/jpeg',
                            'application/pdf', 'application/msword', 'application/rtf',
                            'application/vnd.ms-excel', 'application/x-compress',
                            'application/x-compressed-tar', 'application/x-gzip',
                            'application/x-tar', 'application/zip', 'audio/mpeg',
                            'audio/x-m4a', 'audio/x-wav', 'text/css', 'text/plain', 'text/x-comma-separated-values',
                            'text/rdf', 'video/mpeg', 'video/mp4', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-powerpoint', 'application/vnd.ms-excel'],
                        'uploadDeny' => ['application/x-php'],
                        'uploadOrder' => array('allow', 'deny')
                    )
                )
            );
        else :
            $opts = array(
                //'debug' => true,
                'roots' => array(
                    array(
                        'driver' => 'S3',
                        'path' => ucfirst(get_persondata('uname')),
                        'URL' => 'http://' . _escape(get_option('amz_s3_bucket')) . '.s3.amazonaws.com/' . ucfirst(get_persondata('uname')) . '/',
                        'alias' => 'Files',
                        'mimeDetect' => 'mime_content_type',
                        'mimefile' => BASE_PATH . 'app/src/elFinder/mime.types',
                        'accessControl' => 'access',
                        'uploadAllow' => [
                            'image/png', 'image/gif', 'image/jpeg',
                            'application/pdf', 'application/msword', 'application/rtf',
                            'application/vnd.ms-excel', 'application/x-compress',
                            'application/x-compressed-tar', 'application/x-gzip',
                            'application/x-tar', 'application/zip', 'audio/mpeg',
                            'audio/x-m4a', 'audio/x-wav', 'text/css', 'text/plain', 'text/x-comma-separated-values',
                            'text/rdf', 'video/mpeg', 'video/mp4', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-powerpoint', 'application/vnd.ms-excel'],
                        'uploadDeny' => ['application/x-php'],
                        'uploadOrder' => array('allow', 'deny'),
                        "s3" => array(
                            "key" => _escape(get_option('amz_s3_access_key')),
                            "secret" => _escape(get_option('amz_s3_secret_key')),
                            "region" => 'us-east-1'
                        ),
                        "bucket" => _escape(get_option('amz_s3_bucket')),
                        "acl" => "public-read"
                    )
                )
            );
        endif;
        // run elFinder
        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
    });

    /**
     * Before route middleware check.
     */
    $app->before('GET|POST', '/file-manager/', function() {
        if (!hasPermission('access_dashboard')) {
            etsis_redirect(get_base_url());
        }
    });
    $app->get('/file-manager/', function () use($app) {

        etsis_register_style('elFinder');

        $app->view->display('staff/file-manager', [
            'title' => 'File Manager'
            ]
        );
    });

    /**
     * Before route middleware check.
     */
    $app->before('GET|POST', '/elfinder/', function() {
        if (!hasPermission('access_dashboard')) {
            etsis_redirect(get_base_url());
        }
    });
    $app->match('GET|POST', '/elfinder/', function () use($app) {
        $app->view->display('staff/elfinder', [
            'title' => 'elfinder 2.0',
            'cssArray' => ['plugins/elfinder/css/elfinder.min.css', 'plugins/elfinder/css/theme.css'],
            'jsArray' => ['plugins/elfinder/js/elfinder.min.js', 'plugins/elfinder/js/tinymce.plugin.js']
            ]
        );
    });

    /**
     * Before route middleware check.
     */
    $app->before('GET|POST', '/(\d+)/', function() {
        if (!hasPermission('access_staff_screen')) {
            _etsis_flash()->error(_t('403 - Error: Forbidden.'), get_base_url() . 'dashboard' . '/');
            exit();
        }
    });

    $app->match('GET|POST', '/(\d+)/', function ($id) use($app) {
        if ($app->req->isPost()) {
            try {
                $staff = $app->db->staff();
                foreach (_filter_input_array(INPUT_POST) as $k => $v) {
                    $staff->$k = $v;
                }
                $staff->where('staffID = ?', $id);
                $staff->update();
                /**
                 * Is triggered after staff record is updated.
                 * 
                 * @since 6.1.12
                 * @param mixed $staff Staff data object.
                 */
                $app->hook->do_action('post_update_staff', $staff);
                etsis_logger_activity_log_write('Update Record', 'Staff', get_name($id), get_persondata('uname'));
                _etsis_flash()->success(_etsis_flash()->notice(200), $app->req->server['HTTP_REFERER']);
            } catch (NotFoundException $e) {
                Cascade::getLogger('error')->error($e->getMessage());
                _etsis_flash()->error(_etsis_flash()->notice(409));
            } catch (ORMException $e) {
                Cascade::getLogger('error')->error($e->getMessage());
                _etsis_flash()->error(_etsis_flash()->notice(409));
            } catch (Exception $e) {
                Cascade::getLogger('error')->error($e->getMessage());
                _etsis_flash()->error(_etsis_flash()->notice(409));
            }
        }

        try {
            $staf = $app->db->staff()
                ->where('staffID = ?', $id)
                ->findOne();

            $addr = $app->db->address()
                ->setTableAlias('a')
                ->select("a.*,person.email")
                ->_join('staff', 'a.personID = b.staffID', 'b')
                ->_join('person', 'a.personID = person.personID')
                ->where('a.addressType = "P"')->_and_()
                ->where('a.addressStatus = "C"')->_and_()
                ->where('a.personID = ?', $id);
            $q = $addr->find(function($data) {
                $array = [];
                foreach ($data as $d) {
                    $array[] = $d;
                }
                return $array;
            });
        } catch (NotFoundException $e) {
            Cascade::getLogger('error')->error($e->getMessage());
            _etsis_flash()->error(_etsis_flash()->notice(409));
        } catch (ORMException $e) {
            Cascade::getLogger('error')->error($e->getMessage());
            _etsis_flash()->error(_etsis_flash()->notice(409));
        } catch (Exception $e) {
            Cascade::getLogger('error')->error($e->getMessage());
            _etsis_flash()->error(_etsis_flash()->notice(409));
        }

        /**
         * If the database table doesn't exist, then it
         * is false and a 404 should be sent.
         */
        if ($staf == false) {

            $app->view->display('error/404', ['title' => '404 Error']);
        }
        /**
         * If the query is legit, but there
         * is no data in the table, then 404
         * will be shown.
         */ elseif (empty($staf) == true) {

            $app->view->display('error/404', ['title' => '404 Error']);
        }
        /**
         * If data is zero, 404 not found.
         */ elseif (_escape($staf->staffID) <= 0) {

            $app->view->display('error/404', ['title' => '404 Error']);
        }
        /**
         * If we get to this point, the all is well
         * and it is ok to process the query and print
         * the results in a html format.
         */ else {

            etsis_register_style('form');
            etsis_register_style('selectize');
            etsis_register_script('select');
            etsis_register_script('select2');

            $app->view->display('staff/view', [
                'title' => get_name(_escape($staf->staffID)),
                'staff' => $staf,
                'addr' => $q
                ]
            );
        }
    });

    /**
     * Before route middleware check.
     */
    $app->before('GET|POST', '/add/(\d+)/', function() {
        if (!hasPermission('create_staff_record')) {
            _etsis_flash()->error(_t('403 - Error: Forbidden.'), get_base_url() . 'dashboard' . '/');
            exit();
        }
    });

    $app->match('GET|POST', '/add/(\d+)/', function ($id) use($app) {

        $get_person = get_person($id);
        $get_staff = get_staff($id);

        if ($app->req->isPost()) {
            try {
                $staff = $app->db->staff();
                $staff->staffID = $id;
                $staff->schoolCode = $app->req->post['schoolCode'];
                $staff->buildingCode = $app->req->post['buildingCode'];
                $staff->officeCode = $app->req->post['officeCode'];
                $staff->office_phone = $app->req->post['office_phone'];
                $staff->deptCode = $app->req->post['deptCode'];
                $staff->status = $app->req->post['status'];
                $staff->tags = $app->req->post['tags'] != '' ? $app->req->post['tags'] : NULL;
                $staff->addDate = Jenssegers\Date\Date::now();
                $staff->approvedBy = get_persondata('personID');
                /**
                 * Fires during the saving/creating of a staff record.
                 *
                 * @since 6.1.12
                 * @param array $staff Staff object.
                 */
                $app->hook->do_action('save_staff_db_table', $staff);
                $staff->save();

                $meta = $app->db->staff_meta();
                $meta->jobStatusCode = $app->req->post['jobStatusCode'];
                $meta->jobID = $app->req->post['jobID'];
                $meta->staffID = $id;
                $meta->supervisorID = $app->req->post['supervisorID'];
                $meta->staffType = $app->req->post['staffType'];
                $meta->hireDate = $app->req->post['hireDate'];
                $meta->startDate = $app->req->post['startDate'];
                $meta->endDate = ($app->req->post['endDate'] != '' ? $app->req->post['endDate'] : NULL);
                $meta->addDate = Jenssegers\Date\Date::now();
                $meta->approvedBy = get_persondata('personID');
                /**
                 * Fires during the saving/creating of staff
                 * meta data.
                 *
                 * @since 6.1.12
                 * @param array $meta Staff meta object.
                 */
                $app->hook->do_action('save_staff_meta_db_table', $meta);
                $meta->save();
                /**
                 * Is triggered after staff record has been created.
                 * 
                 * @since 6.1.12
                 * @param mixed $staff Staff data object.
                 */
                $app->hook->do_action('post_save_staff', $staff);

                /**
                 * Is triggered after staff meta data is saved.
                 * 
                 * @since 6.1.12
                 * @param mixed $staff Staff meta data object.
                 */
                $app->hook->do_action('post_save_staff_meta', $meta);
                etsis_logger_activity_log_write('New Record', 'Staff Member', get_name($id), get_persondata('uname'));
                _etsis_flash()->success(_etsis_flash()->notice(200), get_base_url() . 'staff' . '/' . $id . '/');
            } catch (NotFoundException $e) {
                Cascade::getLogger('error')->error($e->getMessage());
                _etsis_flash()->error(_etsis_flash()->notice(409));
            } catch (ORMException $e) {
                Cascade::getLogger('error')->error($e->getMessage());
                _etsis_flash()->error(_etsis_flash()->notice(409));
            } catch (Exception $e) {
                Cascade::getLogger('error')->error($e->getMessage());
                _etsis_flash()->error(_etsis_flash()->notice(409));
            }
        }

        /**
         * If the database table doesn't exist, then it
         * is false and a 404 should be sent.
         */
        if ($get_person == false) {

            $app->view->display('error/404', ['title' => '404 Error']);
        }
        /**
         * If the query is legit, but there
         * is no data in the table, then 404
         * will be shown.
         */ elseif (empty($get_person) == true) {

            $app->view->display('error/404', ['title' => '404 Error']);
        }
        /**
         * If data is zero, 404 not found.
         */ elseif (_escape($get_person->personID) <= 0) {

            $app->view->display('error/404', ['title' => '404 Error']);
        }
        /**
         * If staffID already exists, then redirect
         * the user to the staff record.
         */ elseif (_escape($get_staff->staffID) > 0) {

            etsis_redirect(get_base_url() . 'staff' . '/' . _escape($get_staff->staffID) . '/');
        }
        /**
         * If we get to this point, the all is well
         * and it is ok to process the query and print
         * the results in a html format.
         */ else {

            etsis_register_style('form');
            etsis_register_style('selectize');
            etsis_register_script('select');
            etsis_register_script('select2');
            etsis_register_script('datepicker');

            $app->view->display('staff/add', [
                'title' => get_name(_escape($get_person->personID)),
                'person' => (array) $get_person
                ]
            );
        }
    });
});
