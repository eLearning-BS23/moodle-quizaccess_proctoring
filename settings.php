<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/*
 * Settings page for quiz access proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2020 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $ADMIN;

if ($hassiteconfig) {
    $pageurl = new moodle_url('/mod/quiz/accessrule/proctoring/deleteallimages.php');
    $btnlabel = get_string('settingscontroll:deleteall', 'quizaccess_proctoring');
    $params = new stdClass();
    $params->pageurl = $pageurl->__toString();
    $params->btnlabel = $btnlabel;
    $params->formlabel = get_string('settings:deleteallformlabel', 'quizaccess_proctoring');
    $params->deleteconfirm = get_string('settings:deleteallconfirm', 'quizaccess_proctoring');

    $PAGE->requires->js_call_amd('quizaccess_proctoring/deletebtnjs', 'setup', [$params]);

    $settings->add(new admin_setting_description('quizaccess_proctoring/adminimage', 
        get_string('setting:adminimagepage', 'quizaccess_proctoring'), 
        '<a class="mb-5" style="font-size: 20px;" href=" ' . new moodle_url('/mod/quiz/accessrule/proctoring/userslist.php') .'">'. get_string('setting:userslist', 'quizaccess_proctoring') .'</a>'), 'admin image');

    $settings->add(new admin_setting_configtext('quizaccess_proctoring/autoreconfigurecamshotdelay',
        get_string('setting:camshotdelay', 'quizaccess_proctoring'),
        get_string('setting:camshotdelay_desc', 'quizaccess_proctoring'), 30, PARAM_INT));

    $settings->add(new admin_setting_configtext('quizaccess_proctoring/autoreconfigureimagewidth',
        get_string('setting:camshotwidth', 'quizaccess_proctoring'),
        get_string('setting:camshotwidth_desc', 'quizaccess_proctoring'), 230, PARAM_INT));

    $choices = array(
        'BS' => 'BS',
        'AWS' => 'AWS'
    );
    $settings->add(new admin_setting_configselect('quizaccess_proctoring/fcmethod',  
        get_string('setting:fc_method', 'quizaccess_proctoring'), 
        get_string('setting:fc_methoddesc', 'quizaccess_proctoring'), 
        'BS', 
        $choices
    ));

    $settings->add(new admin_setting_configtext('quizaccess_proctoring/bsapi',
        get_string('setting:bs_api', 'quizaccess_proctoring'),
        get_string('setting:bs_apidesc', 'quizaccess_proctoring'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('quizaccess_proctoring/username',
        get_string('setting:bs_apiusername', 'quizaccess_proctoring'),
        get_string('setting:bs_apiusernamedoc', 'quizaccess_proctoring'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('quizaccess_proctoring/password',
        get_string('setting:bs_apipassword', 'quizaccess_proctoring'),
        get_string('setting:bs_apipassworddesc', 'quizaccess_proctoring'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('quizaccess_proctoring/threshold',
        get_string('setting:bs_apifacematchthreshold', 'quizaccess_proctoring'),
        get_string('setting:bs_bs_apifacematchthresholddesc', 'quizaccess_proctoring'), '68', PARAM_INT));

    $settings->add(new admin_setting_configtext('quizaccess_proctoring/awskey',
        get_string('setting:aws_key', 'quizaccess_proctoring'),
        get_string('setting:aws_keydesc', 'quizaccess_proctoring'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('quizaccess_proctoring/awssecret',
        get_string('setting:aws_secret', 'quizaccess_proctoring'),
        get_string('setting:aws_secretdesc', 'quizaccess_proctoring'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('quizaccess_proctoring/awschecknumber',
        get_string('setting:facematch', 'quizaccess_proctoring'),
        get_string('setting:facematchdesc', 'quizaccess_proctoring'), '', PARAM_INT));

    $settings->add(new admin_setting_configtext('quizaccess_proctoring/awsfcthreshold',
        get_string('setting:fcthreshold', 'quizaccess_proctoring'),
        get_string('setting:fcthresholddesc', 'quizaccess_proctoring'), '80', PARAM_INT));

    $settings->add(new admin_setting_configcheckbox('quizaccess_proctoring/fcheckstartchk',
        get_string('settings:fcheckquizstart', 'quizaccess_proctoring'),
        get_string('settings:fcheckquizstart_desc', 'quizaccess_proctoring'), 0));
    
    
}
