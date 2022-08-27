<?php
if (!defined('BASE_PATH'))
    exit('No direct script access allowed');
use app\src\Core\Exception\NotFoundException;
use app\src\Core\Exception\Exception;
use PDOException as ORMException;
use Cascade\Cascade;

/**
 * eduTrac SIS Student Functions
 *
 * @license GPLv3
 *         
 * @since 6.2.3
 * @package eduTrac SIS
 * @author Joshua Parker <joshmac3@icloud.com>
 */
$app = \Liten\Liten::getInstance();

/**
 * A function which returns true if the logged in user
 * is a student in the system.
 *
 * @since 6.3.0
 * @param int $id
 *            Student's ID.
 * @return bool
 */
function is_student($id)
{
    if ('' == _trim($id)) {
        $message = _t('Invalid student ID: Empty ID given.');
        _incorrectly_called(__FUNCTION__, $message, '6.2.0');
        return;
    }

    if (!is_numeric($id)) {
        $message = _t('Invalid student ID: student id must be numeric.');
        _incorrectly_called(__FUNCTION__, $message, '6.2.0');
        return;
    }

    $stu = get_person_by('personID', $id);

    if (_h($stu->stuID) != '') {
        return true;
    }
    return false;
}

/**
 * If the logged in user is not a student,
 * hide the menu item.
 * For myetSIS usage.
 *
 * @since 4.3
 * @param int $id
 *            Person ID
 * @return string
 */
function checkStuMenuAccess($id)
{
    if ('' == _trim($id)) {
        $message = _t('Invalid person ID: empty ID given.');
        _incorrectly_called(__FUNCTION__, $message, '6.2.0');
        return;
    }

    if (!is_numeric($id)) {
        $message = _t('Invalid person ID: person id must be numeric.');
        _incorrectly_called(__FUNCTION__, $message, '6.2.0');
        return;
    }

    if (!is_student($id)) {
        return ' style="display:none !important;"';
    }
}

/**
 * If the logged in user is not a student,
 * redirect the user to his/her profile.
 *
 * @since 4.3
 * @param int $id
 *            Person ID.
 * @return mixed
 */
function checkStuAccess($id)
{
    if ('' == _trim($id)) {
        $message = _t('Invalid student ID: empty ID given.');
        _incorrectly_called(__FUNCTION__, $message, '6.2.0');
        return;
    }

    if (!is_numeric($id)) {
        $message = _t('Invalid student ID: student id must be numeric.');
        _incorrectly_called(__FUNCTION__, $message, '6.2.0');
        return;
    }

    return is_student($id);
}

function studentsExist($id)
{
    $app = \Liten\Liten::getInstance();
    try {
        $sect = $app->db->stcs()
            ->where('courseSecID = ?', $id)
            ->count('courseSecID');

        if ($sect > 0) {
            return true;
        } else {
            return false;
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
}

/**
 * Generates student load based on credits and academic level.
 * 
 * @since 6.3.0
 * @param float $creds Number of credits to check for.
 * @param string $level Academic level to check for.
 * @return string
 */
function get_stld($creds, $level)
{
    $app = \Liten\Liten::getInstance();
    try {
        $stld = $app->db->aclv()
            ->where('code = ?', $level)
            ->findOne();
        $Q = 0.75 * _h($stld->ft_creds);
        if ($creds < _h($stld->ht_creds)) {
            return 'L';
        } elseif ($creds >= _h($stld->ht_creds) && $creds < _h($stld->ft_creds)) {
            return 'H';
        } elseif ($Q > _h($stld->ht_creds) && $Q < _h($stld->ft_creds)) {
            return 'Q';
        } elseif ($creds >= _h($stld->ft_creds) && $creds < _h($stld->ovr_creds)) {
            return 'F';
        } elseif ($creds >= _h($stld->ovr_creds)) {
            return 'O';
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
}

function getStuSec($code, $term)
{
    $app = \Liten\Liten::getInstance();
    try {
        $stcs = $app->db->stcs()
            ->where('stuID = ?', get_persondata('personID'))->_and_()
            ->where('courseSecCode = ?', $code)->_and_()
            ->where('termCode = ?', $term)
            ->findOne();

        if ($stcs !== false) {
            return ' style="display:none;"';
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
}

function isRegistrationOpen()
{
    if (get_option('open_registration') == 0 || !is_student(get_persondata('personID'))) {
        return ' style="display:none !important;"';
    }
}

/**
 * Graduated Status: if the status on a student's program
 * is "G", then the status and status dates are disabled.
 *
 * @since 1.0.0
 * @param
 *            string
 * @return mixed
 */
function gs($s)
{
    if ($s == 'G') {
        return ' readonly="readonly"';
    }
}

/**
 * Calculates grade points for stac.
 *
 * @since 6.3.0
 * @param string $grade
 *            Letter grade.
 * @param float $credits
 *            Number of course credits.
 * @return mixed
 */
function calculate_grade_points($grade, $credits)
{
    $app = \Liten\Liten::getInstance();
    try {
        $gp = $app->db->grade_scale()
            ->select('points')
            ->where('grade = ?', $grade);
        $q = $gp->find();
        foreach ($q as $r) {
            $gradePoints = _h($r->points) * $credits;
        }
        return $gradePoints;
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
 * Checks to see if the logged in student can
 * register for courses.
 *
 * @return bool
 */
function student_can_register()
{
    $app = \Liten\Liten::getInstance();
    try {
        $stcs = $app->db->query("SELECT
                        COUNT(courseSecCode) AS Courses
                    FROM stcs
                    WHERE stuID = ?
                    AND termCode = ?
                    AND status IN('A','N')
                    GROUP BY stuID,termCode", [
            get_persondata('personID'),
            get_option('registration_term')
        ]);
        $q = $stcs->find(function ($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });
        foreach ($q as $r) {
            $courses = $r['Courses'];
        }

        $rest = $app->db->query("SELECT *
                    FROM perc
                    WHERE severity = '99'
                    AND personID = ?
                    AND endDate IS NULL
                    OR endDate <= '0000-00-00'
                    OR endDate > ?", [
            get_persondata('personID'),
            Jenssegers\Date\Date::now()->format('Y-m-d')
        ]);
        $sql1 = $rest->find(function ($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });

        $stu = $app->db->query("SELECT
        				a.id
    				FROM
    					student a
					LEFT JOIN
						sacp 
					ON
						a.stuID = sacp.stuID
					WHERE
						a.stuID = ?
					AND
						a.status = 'A'
					AND
						sacp.currStatus = 'A'", [
            get_persondata('personID')
        ]);

        $sql2 = $stu->find(function ($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });

        if ($courses != NULL && $courses >= get_option('number_of_courses')) {
            return false;
        } elseif (count($sql1[0]['id']) > 0) {
            return false;
        } elseif (count($sql2[0]['id']) <= 0) {
            return false;
        } else {
            return true;
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
}

/**
 * Checks to see if there is a preReq on
 * the course the student is registering for.
 * If there is one, then we do a check to see
 * if the student has meet the preReq.
 *
 * @param int $stuID
 *            Student ID.
 * @param int $courseSecID
 *            ID of course section.
 * @return bool
 */
function crse_prereq($stuID, $courseSecID)
{
    $app = \Liten\Liten::getInstance();
    try {
        $crse = $app->db->course()
            ->select('course.preReq')
            ->_join('course_sec', 'course.courseID = course_sec.courseID')
            ->where('course_sec.courseSecID = ?', $courseSecID);
        $q1 = $crse->find(function ($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });
        $array = [];
        foreach ($q1 as $r1) {
            $array[] = $r1;
        }
        $req = explode(",", _escape($r1['preReq']));
        if (count(_escape($q1[0]['preReq'])) > 0) {
            $stac = $app->db->query("SELECT
	    					id
						FROM stac
						WHERE courseCode IN('" . str_replace(",", "', '", _escape($r1['preReq'])) . "')
						AND stuID = ?
						AND status IN('A','N')
						AND grade <> ''
						AND grade <> 'W'
						AND grade <> 'I'
						AND grade <> 'F'
						GROUP BY stuID,courseCode", [
                $stuID
            ]);
            $q2 = $stac->find(function ($data) {
                $array = [];
                foreach ($data as $d) {
                    $array[] = $d;
                }
                return $array;
            });
        }
        if (empty(_escape($r1['preReq'])) || count($req) == count($q2)) {
            return true;
        }
        return false;
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
 * The prereq check runs when a student tries to register for a course.
 * 
 * @since 6.3.0
 * @param int $stuID Unique student ID.
 * @param int $sectID Unique course section ID.
 * @return boolean
 */
function check_prereq($stuID, $sectID)
{
    $app = \Liten\Liten::getInstance();
    /**
     * If staff member has this permission, then he/she
     * will be able to register a student into a course section
     * in spite of the student's restriction.
     */
    if (hasPermission('override_rule')) {
        return true;
    }

    $sect = get_course_sec($sectID);
    $crse = get_course(_h($sect->courseID));
    $dept = get_department(_h($crse->deptCode));

    if (crse_prereq($stuID, $sectID) == false || etsis_prereq_rule($stuID, _h($crse->courseID)) == false) {
        $message = _escape($crse->printText);
        $message = str_replace('{name}', get_name($stuID), $message);
        $message = str_replace('{stuID}', get_alt_id($stuID), $message);
        $message = str_replace('{course}', _h($crse->courseCode), $message);
        $message = str_replace('{deptName}', _h($dept->deptName), $message);
        $message = str_replace('{deptEmail}', _h($dept->deptEmail), $message);
        $message = str_replace('{deptPhone}', _h($dept->deptPhone), $message);
        _etsis_flash()->error($message, $app->req->server['HTTP_REFERER']);
        return false;
    }
    return true;
}

/**
 *
 * @since 4.4
 */
function shoppingCart()
{
    $app = \Liten\Liten::getInstance();
    try {
        $cart = $app->db->stu_rgn_cart()->where('stuID = ?', get_persondata('personID'));
        $q = $cart->find(function ($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });
        if (count($q[0]['stuID']) > 0) {
            return true;
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
}

/**
 *
 * @since 4.4
 */
function removeFromCart($section)
{
    $app = \Liten\Liten::getInstance();
    try {
        $cart = $app->db->stu_rgn_cart()
            ->where('stuID = ?', get_persondata('personID'))->_and_()
            ->whereGte('deleteDate', Jenssegers\Date\Date::now())->_and_()
            ->where('courseSecID = ?', $section);
        $q = $cart->find(function ($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });
        if (count($q[0]['stuID']) > 0) {
            return true;
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
}

/**
 * Retrieves all the tags from every student
 * and removes duplicates.
 *
 * @since 6.0.04
 * @return mixed
 */
function tagList()
{
    $app = \Liten\Liten::getInstance();
    try {
        $tagging = $app->db->query('SELECT tags FROM student');
        $q = $tagging->find(function ($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });
        $tags = [];
        foreach ($q as $r) {
            $tags = array_merge($tags, explode(",", $r['tags']));
        }
        $tags = array_unique_compact($tags);
        foreach ($tags as $key => $value) {
            if ($value == "" || strlen($value) <= 0) {
                unset($tags[$key]);
            }
        }
        return $tags;
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
 * Retrieve person's FERPA restriction status.
 *
 * @return Person's FERPA restriction status.
 */
function get_perc($person_id)
{
    $app = \Liten\Liten::getInstance();
    try {
        $rest = $app->db->query("SELECT
                        rest.code,perc.severity,rest.description,c.deptEmail,c.deptPhone,c.deptName,
        				GROUP_CONCAT(DISTINCT rest.code SEPARATOR ',') AS 'Restriction'
    				FROM perc 
					LEFT JOIN rest ON perc.code = rest.code
					LEFT JOIN department c ON rest.deptCode = c.deptCode
					WHERE perc.personID = ?
                    AND perc.code <> 'FERPA'
                    AND perc.endDate IS NULL
                    OR perc.endDate <= '0000-00-00'
					GROUP BY perc.code,perc.personID
					HAVING perc.personID = ?", [ $person_id, $person_id]
        );
        $q = $rest->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });

        return $q;
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
 * Get SACP
 * 
 * Retrieve the student's active and gradutated programs.
 *
 * @since 6.2.11
 * @return array
 */
function get_sacp($stu_id)
{
    $app = \Liten\Liten::getInstance();
    try {
        $sacp = $app->db->query("SELECT
                        sacp.acadProgCode,sacp.currStatus,b.acadProgTitle,b.programDesc,
        				GROUP_CONCAT(DISTINCT sacp.acadProgCode SEPARATOR ',') AS 'SACP',
                        b.acadLevelCode 
    				FROM sacp 
					LEFT JOIN acad_program b ON sacp.acadProgCode = b.acadProgCode
					WHERE sacp.stuID = ?
                    AND sacp.currStatus IN('A','G')
					GROUP BY sacp.acadProgCode,sacp.stuID
					HAVING sacp.stuID = ?", [ $stu_id, $stu_id]
        );
        $q = $sacp->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });

        return $q;
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
 * Student hiatuses.
 *
 * @since 6.2.10
 * @return Hiatus proper name.
 */
function get_shis_name($code)
{
    switch ($code) {
        case "W":
            $hiatus = "Withdrawal";
            break;
        case "LOA":
            $hiatus = "Leave of Absence";
            break;
        case "SA":
            $hiatus = "Study Abroad";
            break;
        case "ILLN":
            $hiatus = "Illness";
            break;
        case "DISM":
            $hiatus = "Dismissal";
            break;
    }
    return $hiatus;
}

/**
 * Retrieve student's hiatus status.
 *
 * @since 6.2.10
 * @return Student's hiatus status.
 */
function get_stu_shis($stu_id, $field)
{
    $app = \Liten\Liten::getInstance();
    try {
        $shis = $app->db->hiatus()
            ->where('stuID = ?', $stu_id)->_and_()
            ->where('endDate IS NULL')->_or_()
            ->whereLte('endDate', '0000-00-00')
            ->findOne();
        return _h($shis->{$field});
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
 * Get active SACP
 * 
 * Retrieve the student's active program.
 *
 * @since 6.3.0
 * @return object
 */
function get_active_sacp($stu_id)
{
    $app = \Liten\Liten::getInstance();
    try {
        $sacp = $app->db->sacp()
            ->where('stuID = ?', $stu_id)->_and_()
            ->where('currStatus = "A"')
            ->find();

        return $sacp;
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
 * Creates a new student sttr record is none exists or
 * updates the attCreds if the student registers for a
 * new course in the same term.
 * 
 * @since 6.3.0
 * @param object $sacd Object holding the last insert into stac.
 */
function create_update_sttr_record($sacd)
{
    $app = \Liten\Liten::getInstance();
    try {
        $sttr = $app->db->sttr()
            ->where('stuID = ?', _h($sacd->stuID))->_and_()
            ->where('termCode = ?', _h($sacd->termCode))
            ->count('id');

        if ($sttr <= 0) {
            $insert = $app->db->sttr();
            $insert->insert([
                'stuID' => _h($sacd->stuID),
                'termCode' => _h($sacd->termCode),
                'acadLevelCode' => _h($sacd->acadLevelCode),
                'attCred' => _h($sacd->attCred),
                'created' => Jenssegers\Date\Date::now()
            ]);
        } else {
            $upd = $app->db->sttr()
                ->where('stuID = ?', _h($sacd->stuID))->_and_()
                ->where('termCode = ?', _h($sacd->termCode))
                ->findOne();
            $upd->set([
                    'attCred' => _h($upd->attCred) + _h($sacd->attCred)
                ])
                ->where('stuID = ?', _h($sacd->stuID))->_and_()
                ->where('termCode = ?', _h($sacd->termCode))
                ->update();
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
}

/**
 * Retrieve student's academic level standing.
 * 
 * @since 6.3.0
 * @param int $stuID Unique student ID.
 * @return string
 */
function get_student_alst($stuID)
{
    $app = \Liten\Liten::getInstance();
    $alst = $app->db->stal()
        ->select('acadStanding')
        ->where('stuID = ?', $stuID)->_and_()
        ->where('endDate IS NULL')
        ->findOne();
    return _escape($alst->acadStanding);
}

/**
 * Retrieve student's class level.
 * 
 * @since 6.3.0
 * @param int $stuID Unique student ID.
 * @return string
 */
function get_student_clas($stuID)
{
    $app = \Liten\Liten::getInstance();
    $alst = $app->db->stal()
        ->select('clas.name')
        ->_join('clas','stal.currentClassLevel = clas.code')
        ->where('stuID = ?', $stuID)->_and_()
        ->where('endDate IS NULL')
        ->findOne();
    return _escape($alst->name);
}

function get_stu_header($stu_id)
{
    $app = \Liten\Liten::getInstance();
    $student = get_student($stu_id);

    ?>

    <!-- List Widget -->
    <div class="relativeWrap">
        <div class="widget">
            <div class="widget-head">
                <h4 class="heading glyphicons user"><i></i><?= get_name(_h($student->stuID)); ?></h4>&nbsp;
                <?php if (!isset($app->req->cookie['SWITCH_USERBACK']) && _h($student->stuID) != get_persondata('personID')) : ?>
                    &nbsp;<span<?= ae('login_as_user'); ?> class="label label-inverse"><a href="<?= get_base_url(); ?>switchUserTo/<?= _h($student->stuID); ?>/"><font color="#FFFFFF"><?= _t('Switch To'); ?></font></a></span>&nbsp;&nbsp;
                <?php endif; ?>
                <strong><?= _t('Address:'); ?></strong> <?= _h($student->address1); ?> <?= _h($student->address2); ?> <?= _h($student->city); ?> <?= _h($student->state); ?> <?= _h($student->zip); ?> <strong><?= _t('Phone:'); ?></strong> <?= _h($student->phone1); ?>
                <?php if (get_persondata('personID') == $student->stuID && !hasPermission('access_dashboard')) : ?>
                    <a href="<?= get_base_url(); ?>profile/" class="heading pull-right"><?= _h($student->stuID); ?></a>
                <?php else : ?>
                    <a href="<?= get_base_url(); ?>stu/<?= _h($student->stuID); ?>/" class="heading pull-right"><?= (_h($student->altID) != '' ? _h($student->altID) : _h($student->stuID)); ?></a>
                <?php endif; ?>
            </div>
            <div class="widget-body">
                <!-- 4 Column Grid / One Third -->
                <div class="row">

                    <!-- One Fifth's Column -->
                    <div class="col-md-2">
                        <?= get_school_photo(_h($student->stuID), _h($student->email1), '90'); ?>
                    </div>
                    <!-- // One Fifth's Column END -->

                    <!-- Two Fifth's Column -->
                    <div class="col-md-2">
                        <p><strong><?= _t('Email:'); ?></strong> <a href="mailto:<?= _h($student->email1); ?>"><?= _h($student->email1); ?></a></p>
                        <p><strong><?= _t('Birth Date:'); ?></strong> <?= (_h($student->dob) > '0000-00-00' ? Jenssegers\Date\Date::parse(_h($student->dob))->format('D, M d, o') : ''); ?></p>
                        <p><strong><?= _t('Status:'); ?></strong> <?= (_h($student->stuStatus) == 'A') ? _t('Active') : _t('Inactive'); ?></p>
                    </div>
                    <!-- // Two Fifth's Column END -->

                    <!-- Three Fifth's Column -->
                    <div class="col-md-2">
                        <p><strong><?= _t('FERPA:'); ?></strong> <?= is_ferpa(_h($student->stuID)); ?> 
                            <?php if (is_ferpa(_h($student->stuID)) == 'Yes') : ?>
                                <a href="#FERPA" data-toggle="modal"><img style="vertical-align:top !important;" src="<?= get_base_url(); ?>static/common/theme/images/exclamation.png" /></a>
                            <?php else : ?>
                                <a href="#FERPA" data-toggle="modal"><img style="vertical-align:top !important;" src="<?= get_base_url(); ?>static/common/theme/images/information.png" /></a>
                            <?php endif; ?>
                        </p>
                        <p><strong><?= _t('PERC:'); ?></strong> 
                            <?php
                            $rest = '';
                            foreach (get_perc($student->stuID) as $v) :

                                ?>
                                <?= $rest; ?><span data-toggle="popover" data-title="<?= _h($v['description']); ?>" data-content="Contact: <?= _h($v['deptName']); ?> <?= (_h($v['deptEmail']) != '') ? ' | ' . $v['deptEmail'] : ''; ?><?= (_h($v['deptPhone']) != '') ? ' | ' . $v['deptPhone'] : ''; ?><?= (_h($v['severity']) == 99) ? _t(' | Restricted from registering for courses.') : ''; ?>" data-placement="bottom"><a href="#"><?= _h($v['Restriction']); ?></a></span>
                                <?php
                                $rest = ', ';
                            endforeach;

                            ?>
                        </p>
                        <p><strong><?= _t('Entry Date:'); ?></strong> <?= Jenssegers\Date\Date::parse(_h($student->stuAddDate))->format('D, M d, o'); ?></p>
                    </div>
                    <!-- // Three Fifth's Column END -->

                    <!-- Four Fifth's Column -->
                    <div class="col-md-2">
                        <p><strong><?= _t('SACP:'); ?></strong> 
                            <?php
                            $sacp = '';
                            foreach (get_sacp($student->stuID) as $v) :

                                ?>
                                <?= $sacp; ?><span data-toggle="popover" data-title="<?= _h($v['acadProgTitle']); ?> (<?= (_h($v['currStatus']) == 'A' ? _t('Active') : _t('Graduated')); ?>)" data-content="<?= _h($v['programDesc']); ?>" data-placement="bottom"><a href="#"><?= _h($v['SACP']); ?></a></span>
                                <?php
                                $sacp = ', ';
                            endforeach;

                            ?>
                        </p>
                        <p><strong><?= _t('Admit Status:'); ?></strong> 
                            <?= get_admit_status($student->stuID); ?>
                        </p>
                        <p><strong><?= _t('Hiatus:'); ?></strong> 
                            <span data-toggle="popover" data-title="<?= get_shis_name(get_stu_shis(_h($student->stuID), 'code')); ?>" data-content="Start Date: <?= get_stu_shis(_h($student->stuID), 'startDate'); ?> | End Date: <?= (get_stu_shis(_h($student->stuID), 'endDate') <= '0000-00-00' ? '' : get_stu_shis(_h($student->stuID), 'endDate')); ?>" data-placement="bottom"><a href="#"><?= get_stu_shis(_h($student->stuID), 'code'); ?></a></span>
                        </p>
                    </div>
                    <!-- // Four Fifth's Column END -->

                    <!-- Five Fifth's Column -->
                    <div class="col-md-2">
                        <p><strong><?= _t('Academic Standing:'); ?></strong> <?=(get_student_alst(_escape($student->stuID)) == 'PROB' ? '<font color="#d9534f">'.get_student_alst($student->stuID).'</font>' : '<font color="#3fad46">'.get_student_alst($student->stuID).'</font>');?></p>
                        <p><strong><?= _t('Class Level:'); ?></strong> <?=get_student_clas(_escape($student->stuID));?></p>
                    </div>
                    <!-- // Five Fifth's Column END -->

                </div>
                <!-- // 4 Column Grid / One Third END -->
            </div>
        </div>
    </div>
    <!-- // List Widget END -->

    <!-- Modal -->
    <div class="modal fade" id="FERPA">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal heading -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title"><?= _t('Family Educational Rights and Privacy Act (FERPA)'); ?></h3>
                </div>
                <!-- // Modal heading END -->
                <!-- Modal body -->
                <div class="modal-body">
                    <p><?= _t('"FERPA gives parents certain rights with respect to their children\'s education records. These rights transfer to the student when he or she reaches the age of 18 or attends a school beyond the high school level. Students to whom the rights have transferred are \'eligible students.\'"'); ?></p>
                    <p><?= sprintf(_t('If the FERPA restriction states "Yes", then the student has requested that none of their information be given out without their permission. To get a better understanding of FERPA, visit the U.S. DOE\'s website @ %s.'), '<a href="http://www2.ed.gov/policy/gen/guid/fpco/ferpa/index.html">http://www2.ed.gov/policy/gen/guid/fpco/ferpa/index.html</a>'); ?></p>
                </div>
                <!-- // Modal body END -->
                <!-- Modal footer -->
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?= _t('Close'); ?></a> 
                </div>
                <!-- // Modal footer END -->
            </div>
        </div>
    </div>
    <!-- // Modal END -->

    <?php
}

/**
 * Retrieves student data given a student ID or student array.
 *
 * @since 6.2.3
 * @param int|etsis_Student|null $student
 *            Student ID or student array.
 * @param bool $object
 *            If set to true, data will return as an object, else as an array.
 */
function get_student($student, $object = true)
{
    if ($student instanceof \app\src\Core\etsis_Student) {
        $_student = $student;
    } elseif (is_array($student)) {
        if (empty($student['stuID'])) {
            $_student = new \app\src\Core\etsis_Student($student);
        } else {
            $_student = \app\src\Core\etsis_Student::get_instance($student['stuID']);
        }
    } else {
        $_student = \app\src\Core\etsis_Student::get_instance($student);
    }

    if (!$_student) {
        return null;
    }

    if ($object == true) {
        $_student = array_to_object($_student);
    }

    return $_student;
}
