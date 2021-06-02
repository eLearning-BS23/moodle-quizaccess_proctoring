<?php
require_once (__DIR__ . '/../../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot. '/mod/quiz/accessrule/proctoring/classes/settings_form.php');
// No guest autologin.
require_login(0, false);

// Get URL parameters.
$systemcontext = context_system::instance();
$contextid = optional_param('context', $systemcontext->id, PARAM_INT);

// Check permissions.
list($context, $course, $cm) = get_context_info_array($contextid);
require_login($course, false, $cm);
require_capability('quizaccess/proctoring:deletecamshots', $context);

$pageurl = new moodle_url('/mod/quiz/accessrule/proctoring/externalsettings.php');
$PAGE->set_url($pageurl);

admin_externalpage_setup('proctoringsettings');
$imagewidth = "230";
$imagewidthsql = "SELECT * FROM {config_plugins} WHERE plugin = 'quizaccess_proctoring' AND name='autoreconfigureimagewidth'";
$imagewidthdata = $DB->get_record_sql($imagewidthsql);
if($imagewidthdata){
    $imagewidth = $imagewidthdata->value;
}

$delay = "30";
$delaysql = "SELECT * FROM {config_plugins} WHERE plugin = 'quizaccess_proctoring' AND name='autoreconfigurecamshotdelay'";
$delaydata = $DB->get_record_sql($delaysql);
if($delaydata){
    $delay = $delaydata->value;
}

$settings_form = new quizaccess_proctoring_settings_form(null, array(
    'imagewidth' => $imagewidth,
    'delay' => $delay
));
$errors = array();
if ($settings_form->is_cancelled()) {
    // Go back to the manage.php page
    $url = new moodle_url('/');
    redirect($url, get_string('settingserror:formcancelled', 'quizaccess_proctoring'),null,'error');
} else if ($fromform = $settings_form->get_data()) {
    // Handle form post
    $submittype = $fromform->submitvalue;
    if($submittype == get_string('settingscontroll:save', 'quizaccess_proctoring')){
        $imagewidth = $fromform->imagewidth;
        // Update image width
        if(is_numeric($imagewidth)){
            $imagewidthsql = "SELECT * FROM {config_plugins} WHERE plugin = 'quizaccess_proctoring' AND name='autoreconfigureimagewidth'";
            $imagewidthdata = $DB->get_record_sql($imagewidthsql);
            if($imagewidthdata){
                // Update
                $imagewidthdata->value = $imagewidth;
                $DB->update_record('config_plugins', $imagewidthdata, $bulk=false);
            }
            else{
                // Insert
                $newimagewidthsettings = new stdClass();
                $newimagewidthsettings->plugin = 'quizaccess_proctoring';
                $newimagewidthsettings->name = 'autoreconfigureimagewidth';
                $newimagewidthsettings->value = $imagewidth;
                $DB->insert_record('config_plugins', $newimagewidthsettings);
            }
        }
        else{
            $url = $pageurl;
            redirect($url, get_string('settingserror:imagewidth', 'quizaccess_proctoring'),null,'error');
        }


        $delay = $fromform->delay;
        if(is_numeric($delay)){
            $delaysql = "SELECT * FROM {config_plugins} WHERE plugin = 'quizaccess_proctoring' AND name='autoreconfigurecamshotdelay'";
            $delaydata = $DB->get_record_sql($delaysql);
            if($delaydata){
                // Update
                $delaydata->value = $delay;
                $DB->update_record('config_plugins', $delaydata, $bulk=false);
            }
            else{
                // Insert
                $newdelaysettings = new stdClass();
                $newdelaysettings->plugin = 'quizaccess_proctoring';
                $newdelaysettings->name = 'autoreconfigurecamshotdelay';
                $newdelaysettings->value = $delay;
                $DB->insert_record('config_plugins', $newimagewidthsettings);
            }

            // Successfully updated. Redirect to home page
            $url = new moodle_url('/');
            redirect($url, get_string('settings:updatesuccess', 'quizaccess_proctoring'),null,'success');
        }
        else{
            $url = $pageurl;
            redirect($url, get_string('settingserror:imagedelay', 'quizaccess_proctoring'),null,'error');
        }
    }

    if($submittype == get_string('settingscontroll:deleteall', 'quizaccess_proctoring')){
        $DB->set_field('quizaccess_proctoring_logs', 'userid', 0);

        // Delete users file (webcam images).
        $filesql = 'SELECT * FROM {files} WHERE component = \'quizaccess_proctoring\' AND filearea = \'picture\'';

        $usersfile = $DB->get_records_sql($filesql);

        $fs = get_file_storage();
        foreach ($usersfile as $file):
            // Prepare file record object
            $fileinfo = array(
                'component' => 'quizaccess_proctoring',
                'filearea' => 'picture',     // Usually = table name.
                'itemid' => $file->itemid,               // Usually = ID of row in table.
                'contextid' => $context->id, // ID of context.
                'filepath' => '/',           // any path beginning and ending in /.
                'filename' => $file->filename); // any filename.

            // Get file
            $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
                $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);

            // Delete it if it exists
            if ($file) {
                $file->delete();
            }
        endforeach;
        $url = new moodle_url('/');
        redirect($url, get_string('settings:deleteallsuccess', 'quizaccess_proctoring'), -11,'success');
    }
}

echo $OUTPUT->header();
$settings_form->display();
echo $OUTPUT->footer();