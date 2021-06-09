<?php
require_once (__DIR__ . '/../../../../config.php');
require_once ($CFG->dirroot . '/lib/tablelib.php');
require_once (__DIR__ . '/classes/addtional_settings_helper.php');

$cmid = required_param('cmid', PARAM_INT);
$type = required_param('type', PARAM_TEXT);
$id = required_param('id', PARAM_INT);
$context = context_module::instance($cmid, MUST_EXIST);
require_capability('quizaccess/proctoring:deletecamshots', $context);

$params = array('cmid' => $cmid, 'type'=>$type, 'id'=>$id);
$url = new moodle_url(
'/mod/quiz/accessrule/proctoring/bulkdelete.php',
$params
);

list ($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');

require_login($course, true, $cm);

$PAGE->set_url($url);
$PAGE->set_title('Proctoring:Bulk Delete');
$PAGE->set_heading('Proctoring Bulk Delete');

$PAGE->navbar->add('Proctoring: Bulk Delete', $url);
//$PAGE->requires->js_call_amd('quizaccess_proctoring/additionalSettings', 'setup',array());
$helper = new addtional_settings_helper();
echo $OUTPUT->header();

if($type == 'course'){
    $data = $helper->searchByCourseID($id);
}
else if($type == 'quiz'){
    $data = $helper->searchByQuizID($id);
}
else{
    echo "invalid type";
}
$rowids = array();
foreach ($data as $row){
    array_push($rowids,$row->id);
}

$rowidstring = implode(',',$rowids);
$helper->deleteLogs($rowidstring);
