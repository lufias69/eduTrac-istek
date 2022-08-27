<?php
if (!defined('BASE_PATH'))
    exit('No direct script access allowed');
use app\src\Core\NodeQ\etsis_NodeQ as Node;
use app\src\Core\NodeQ\NodeQException;
use app\src\Core\Exception\NotFoundException;
use app\src\Core\Exception\Exception;
use PDOException as ORMException;
use \app\src\elFinder\elFinderConnector;
use \app\src\elFinder\elFinder;
use \app\src\elFinder\elFinderVolumeDriver;
use \app\src\elFinder\elFinderVolumeLocalFileSystem;
use \app\src\elFinder\elFinderVolumeS3;
use Cascade\Cascade;

/**
 * Dashboard Router
 *
 * @license GPLv3
 *         
 * @since 5.0.0
 * @package eduTrac SIS
 * @author Joshua Parker <joshmac3@icloud.com>
 */
/**
 * Before route check.
 */
$app->before('GET|POST', '/dashboard(.*)', function () {
    if (!is_user_logged_in()) {
        _etsis_flash()->error(_t('401 - Error: Unauthorized.'), get_base_url() . 'login' . '/');
    }

    if (!hasPermission('access_dashboard')) {
        _etsis_flash()->error(_t('403 - Error: Forbidden.'), get_base_url());
    }
});

$app->group('/dashboard', function () use($app) {

    $app->get('/', function () use($app) {

        try {
            $stuProg = $app->db->sacp()
                ->select('COUNT(sacp.id) as ProgCount,sacp.acadProgCode')
                ->_join('acad_program', 'sacp.acadProgCode = b.acadProgCode', 'b')
                ->_join('student', 'sacp.stuID = student.stuID')
                ->where('sacp.currStatus <> "G"')->_and_()
                ->where('student.status = "A"')
                ->groupBy('sacp.acadProgCode')
                ->orderBY('sacp.acadProgCode', 'DESC')
                ->limit(10);

            $prog = $stuProg->find(function ($data) {
                $array = [];
                foreach ($data as $d) {
                    $array[] = $d;
                }
                return $array;
            });

            $stuDept = $app->db->person()
                ->select('SUM(person.gender="M") AS Male,SUM(person.gender="F") AS Female,d.deptCode')
                ->_join('student', 'person.personID = student.stuID')
                ->_join('sacp', 'student.stuID = b.stuID', 'b')
                ->_join('acad_program', 'b.acadProgCode = c.acadProgCode', 'c')
                ->_join('department', 'c.deptCode = d.deptCode', 'd')
                ->where('b.startDate = (SELECT MAX(startDate) FROM sacp WHERE stuID = b.stuID)')->_and_()
                ->where('student.status = "A"')->_and_()
                ->where('b.currStatus = "A"')->_and_()
                ->where('d.deptTypeCode = "ACAD"')
                ->groupBy('d.deptCode')
                ->orderBy('d.deptCode', 'DESC')
                ->limit(10);

            $dept = $stuDept->find(function ($data) {
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

        etsis_register_style('dash');
        etsis_register_script('highcharts');
        etsis_register_script('dashboard');

        $app->view->display('dashboard/index', [
            'title' => 'Dashboard',
            'prog' => $prog,
            'dept' => $dept
        ]);
    });

    $app->post('/search/', function () use($app) {
        $acro = $app->req->post['screen'];
        $screen = explode(" ", $acro);

        if (get_screen($screen[0]) == '') {
            etsis_redirect(get_base_url() . 'err/screen-error?code=' . _h($screen[0]));
        } else {
            etsis_redirect(get_base_url() . get_screen($screen[0]) . '/');
        }
    });

    $app->get('/support/', function () use($app) {
        $app->view->display('dashboard/support', [
            'title' => 'Online Support'
        ]);
    });

    /**
     * Before route check.
     */
    $app->before('GET|POST', '/system-snapshot/', function () {
        if (!hasPermission('edit_settings')) {
            _etsis_flash()->error(_t('403 - Error: Forbidden.'), get_base_url() . 'dashboard' . '/');
        }
    });

    $app->get('/system-snapshot/', function () use($app) {
        try {
            $db = $app->db->query("SELECT version() AS version")->findOne();
            $nae = $app->db->person()->where("status = 'A'")->count('personID');
            $stu = $app->db->student()->where("status = 'A'")->count('stuID');
            $staf = $app->db->staff()->where("status = 'A'")->count('staffID');
            $error = $app->db->error()->count('id');
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
        $app->view->display('dashboard/system-snapshot', [
            'title' => 'System Snapshot Report',
            'db' => $db,
            'nae' => $nae,
            'stu' => $stu,
            'staf' => $staf,
            'error' => $error
        ]);
    });

    /**
     * Before route check.
     */
    $app->before('GET|POST', '/modules/', function () {
        if (!hasPermission('access_plugin_screen')) {
            _etsis_flash()->error(_t('403 - Error: Forbidden.'), get_base_url() . 'dashboard' . '/');
        }
    });

    $app->get('/modules/', function () use($app) {

        etsis_register_style('form');
        etsis_register_style('table');
        etsis_register_script('select');
        etsis_register_script('select2');
        etsis_register_script('datatables');

        $app->view->display('dashboard/modules', [
            'title' => 'System Modules'
        ]);
    });

    /**
     * Before route check.
     */
    $app->before('GET|POST', '/install-module/', function () {
        if (!hasPermission('access_plugin_admin_page')) {
            _etsis_flash()->error(_t('403 - Error: Forbidden.'), get_base_url() . 'dashboard' . '/');
        }
    });

    $app->match('GET|POST', '/install-module/', function () use($app) {

        if ($app->req->isPost()) {
            $name = explode(".", $_FILES["module_zip"]["name"]);
            $accepted_types = [
                'application/zip',
                'application/x-zip-compressed',
                'multipart/x-zip',
                'application/x-compressed'
            ];

            foreach ($accepted_types as $mime_type) {
                if ($mime_type == $type) {
                    $okay = true;
                    break;
                }
            }

            $continue = strtolower($name[1]) == 'zip' ? true : false;

            if (!$continue) {
                _etsis_flash()->error(_t('The file you are trying to upload is not the accepted file type. Please try again.'));
            }
            $target_path = BASE_PATH . $_FILES["module_zip"]["name"];
            if (move_uploaded_file($_FILES["module_zip"]["tmp_name"], $target_path)) {
                $zip = new \ZipArchive();
                $x = $zip->open($target_path);
                if ($x === true) {
                    $zip->extractTo(BASE_PATH);
                    $zip->close();
                    unlink($target_path);
                }
                _etsis_flash()->success(_t('The module was uploaded and installed properly.'), $app->req->server['HTTP_REFERER']);
            } else {
                _etsis_flash()->error(_t('There was a problem uploading the module. Please try again or check the module package.'));
            }
        }

        etsis_register_style('form');
        etsis_register_script('select');
        etsis_register_script('select2');
        etsis_register_script('upload');

        $app->view->display('dashboard/install-module', [
            'title' => 'Install Modules'
        ]);
    });

    $app->get('/flushCache/', function () use($app) {
        etsis_cache_flush();
        _etsis_flash()->success(_t('Cache was flushed successfully.'), $app->req->server['HTTP_REFERER']);
    });

    $app->match('GET|POST|PATCH|PUT|OPTIONS|DELETE', '/connector/', function () use($app) {
        error_reporting(0);
        $opts = [
            // 'debug' => true,
            'locale' => 'es_ES.UTF-8',
            'roots' => [
                [
                    'driver' => 'LocalFileSystem',
                    'path' => $app->config('cookies.savepath') . 'nodes',
                    'alias' => 'Nodes',
                    'mimeDetect' => 'auto',
                    'accessControl' => 'access',
                    'attributes' => [
                        [
                            'read' => true,
                            'write' => true,
                            'locked' => false
                        ],
                        [
                            'pattern' => '/\.tmb/',
                            'read' => false,
                            'write' => false,
                            'hidden' => true,
                            'locked' => false
                        ],
                        [
                            'pattern' => '/\.quarantine/',
                            'read' => false,
                            'write' => false,
                            'hidden' => true,
                            'locked' => false
                        ],
                        [
                            'pattern' => '/\.DS_Store/',
                            'read' => false,
                            'write' => false,
                            'hidden' => true,
                            'locked' => false
                        ],
                        [
                            'pattern' => '/\.json$/',
                            'read' => true,
                            'write' => true,
                            'hidden' => false,
                            'locked' => false
                        ]
                    ],
                    'uploadMaxSize' => '500M',
                    'uploadAllow' => ['text/plain'],
                    'uploadOrder' => ['allow', 'deny']
                ],
                [
                    'driver' => 'LocalFileSystem',
                    'path' => $app->config('file.savepath'),
                    'alias' => 'Files',
                    'mimeDetect' => 'auto',
                    'accessControl' => 'access',
                    'attributes' => [
                        [
                            'read' => true,
                            'write' => true,
                            'locked' => false
                        ],
                        [
                            'pattern' => '/\.tmb/',
                            'read' => false,
                            'write' => false,
                            'hidden' => true,
                            'locked' => false
                        ],
                        [
                            'pattern' => '/\.quarantine/',
                            'read' => false,
                            'write' => false,
                            'hidden' => true,
                            'locked' => false
                        ],
                        [
                            'pattern' => '/\.DS_Store/',
                            'read' => false,
                            'write' => false,
                            'hidden' => true,
                            'locked' => false
                        ],
                        [
                            'pattern' => '/\.json$/',
                            'read' => true,
                            'write' => true,
                            'hidden' => false,
                            'locked' => false
                        ]
                    ],
                    'uploadMaxSize' => '500M',
                    'uploadAllow' => ['text/plain'],
                    'uploadOrder' => ['allow', 'deny']
                ]
            ]
        ];
        // run elFinder
        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
    });

    /**
     * Before route middleware check.
     */
    $app->before('GET|POST', '/ftp/', function() {
        if (!hasPermission('access_ftp')) {
            _etsis_flash()->error(_t("You don't have permission to access FTP server."), get_base_url() . 'dashboard' . '/');
        }
    });

    $app->get('/ftp/', function () use($app) {
        etsis_register_style('elFinder');

        $app->view->display('dashboard/ftp', [
            'title' => 'FTP'
            ]
        );
    });

    $app->get('/getSACP/', function () use($app) {

        try {
            $stuProg = $app->db->sacp()
                ->select('COUNT(sacp.id) AS Count,sacp.acadProgCode AS Prog')
                ->_join('acad_program', 'sacp.acadProgCode = b.acadProgCode', 'b')
                ->_join('student', 'sacp.stuID = student.stuID')
                ->where('sacp.currStatus <> "G"')->_and_()
                ->where('sacp.currStatus <> "C"')->_and_()
                ->where('student.status = "A"')
                ->groupBy('sacp.acadProgCode')
                ->orderBY('sacp.acadProgCode', 'DESC')
                ->limit(10);
            $q = $stuProg->find();
            $rows = [];
            foreach ($q as $r) {
                $row[0] = _h($r->Prog);
                $row[1] = _h($r->Count);
                array_push($rows, $row);
            }
            print json_encode($rows, JSON_NUMERIC_CHECK);
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
    });

    $app->get('/getDEPT/', function () use($app) {

        try {

            $stuDept = $app->db->person()
                ->select('SUM(CASE person.gender WHEN "M" THEN 1 ELSE 0 END) AS Male')
                ->select('SUM(CASE person.gender WHEN "F" THEN 1 ELSE 0 END) AS Female,d.deptCode')
                //->select('SUM(person.gender="M") AS Male,SUM(person.gender="F") AS Female,d.deptCode')
                ->_join('student', 'person.personID = student.stuID')
                ->_join('sacp', 'student.stuID = b.stuID', 'b')
                ->_join('acad_program', 'b.acadProgCode = c.acadProgCode', 'c')
                ->_join('department', 'c.deptCode = d.deptCode', 'd')
                ->where('b.startDate = (SELECT MAX(startDate) FROM sacp WHERE stuID = b.stuID)')->_and_()
                ->where('student.status = "A"')->_and_()
                ->where('b.currStatus = "A"')->_and_()
                ->where('d.deptTypeCode = "ACAD"')
                ->groupBy('d.deptCode')
                ->orderBy('d.deptCode', 'DESC')
                ->limit(10);

            $q = $stuDept->find();
            $category = [];
            $category['name'] = _t('Academic Departments');
            $series1 = [];
            $series1['name'] = _t('Male');
            $series2 = [];
            $series2['name'] = _t('Female');
            foreach ($q as $r) {
                $category['data'][] = _h($r->deptCode);
                $series1['data'][] = _h($r->Male);
                $series2['data'][] = _h($r->Female);
            }
            $result = [];
            array_push($result, $category);
            array_push($result, $series1);
            array_push($result, $series2);
            print json_encode($result, JSON_NUMERIC_CHECK);
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
    });

    /**
     * Before route middleware check.
     */
    $app->before('GET|POST', '/sms/', function() {
        if (!hasPermission('send_sms')) {
            _etsis_flash()->error(_t('403 - Error: Forbidden.'), get_base_url() . 'dashboard' . '/');
        }
    });

    $app->match('GET|POST', '/sms/', function () use($app) {
        try {
            Node::dispense('sms');
        } catch (NodeQException $e) {
            _etsis_flash()->error($e->getMessage());
        } catch (Exception $e) {
            _etsis_flash()->error($e->getMessage());
        }

        if ($app->req->isPost()) {
            try {
                if ($app->req->post['sms_group'] == 'all') {
                    $sms = $app->db->person()
                        ->select('CASE WHEN address.phoneType1 = "CEL" THEN address.phone1 ELSE address.phone2 END AS Phone')
                        ->_join('address', 'person.personID = address.personID')
                        ->where('person.status = "A"')->_and_()
                        ->where('address.addressStatus = "C"')->_and_()
                        ->where('(address.phoneType1 <> "" or address.phoneType2 <> "")')
                        ->find();
                } elseif ($app->req->post['sms_group'] == 'fac') {
                    $sms = $app->db->staff()
                        ->select('CASE WHEN address.phoneType1 = "CEL" THEN address.phone1 ELSE address.phone2 END AS Phone')
                        ->_join('address', 'staff.staffID = address.personID')
                        ->_join('person', 'staff.staffID = person.personID')
                        ->where('staff.status = "A"')->_and_()
                        ->where('address.addressStatus = "C"')->_and_()
                        ->where('person.personType = "FAC"')
                        ->where('(address.phoneType1 <> "" or address.phoneType2 <> "")')
                        ->find();
                } elseif ($app->req->post['sms_group'] == 'student') {
                    $sms = $app->db->student()
                        ->select('CASE WHEN address.phoneType1 = "CEL" THEN address.phone1 ELSE address.phone2 END AS Phone')
                        ->_join('address', 'student.stuID = address.personID')
                        ->where('student.status = "A"')->_and_()
                        ->where('address.addressStatus = "C"')->_and_()
                        ->where('(address.phoneType1 <> "" or address.phoneType2 <> "")')
                        ->find();
                } elseif ($app->req->post['sms_group'] == 'sta') {
                    $sms = $app->db->staff()
                        ->select('CASE WHEN address.phoneType1 = "CEL" THEN address.phone1 ELSE address.phone2 END AS Phone')
                        ->_join('person', 'staff.staffID = person.personID')
                        ->_join('address', 'staff.staffID = address.personID')
                        ->where('staff.status = "A"')->_and_()
                        ->where('person.personType = "STA"')->_and_()
                        ->where('address.addressStatus = "C"')->_and_()
                        ->where('(address.phoneType1 <> "" or address.phoneType2 <> "")')
                        ->find();
                } elseif ($app->req->post['sms_group'] == 'facsta') {
                    $sms = $app->db->staff()
                        ->select('CASE WHEN address.phoneType1 = "CEL" THEN address.phone1 ELSE address.phone2 END AS Phone')
                        ->_join('person', 'staff.staffID = person.personID')
                        ->_join('address', 'staff.staffID = address.personID')
                        ->where('staff.status = "A"')->_and_()
                        ->where('person.personType IN("FAC","STA")')->_and_()
                        ->where('address.addressStatus = "C"')->_and_()
                        ->where('(address.phoneType1 <> "" or address.phoneType2 <> "")')
                        ->find();
                }
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

            $numItems = count($sms);
            $i = 0;
            foreach ($sms as $val) {
                $phone = str_replace(['-', '.'], '', _h($val->Phone));
                try {
                    $node = Node::table('sms');
                    $node->number = _trim($phone);
                    $node->text = $app->req->post['sms_text'];
                    $node->sent = 0;
                    $node->save();
                    if (++$i === $numItems) {
                        _etsis_flash()->success(_t('SMS messages have been queued for sending.'), $app->req->server['HTTP_REFERER']);
                    }
                } catch (NodeQException $e) {
                    Cascade::getLogger('error')->error(sprintf('NODEQSTATE[%s]: %s', $e->getCode(), $e->getMessage()));
                    _etsis_flash()->error(_etsis_flash()->notice(409));
                } catch (Exception $e) {
                    Cascade::getLogger('error')->error(sprintf('NODEQSTATE[%s]: %s', $e->getCode(), $e->getMessage()));
                    _etsis_flash()->error(_etsis_flash()->notice(409));
                }
            }
        }

        etsis_register_style('form');
        etsis_register_script('select');
        etsis_register_script('select2');

        $app->view->display('dashboard/sms', [
            'title' => 'Short Message Service (SMS)'
            ]
        );
    });
});



$app->setError(function () use($app) {

    $app->view->display('error/404', [
        'title' => '404 Error'
    ]);
});
