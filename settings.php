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

/**
 * Settings for the quizaccess_proctoring plugin.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


if ($hassiteconfig) {
    // Plugin description and name.
    $plugindescription = get_string('plugin_description', 'quizaccess_proctoring');

    // Pro version description without "Flash Sale".
    $proversiondescription = get_string('pro_version_description', 'quizaccess_proctoring');

    // Pro version link using Moodle's default styling for links.
    $proversionlink = html_writer::link(
        'https://elearning23.com/moodle-proctoring-pro-details/',
        get_string('pro_version_text', 'quizaccess_proctoring'),
    );

    // Combine description and link in a single paragraph.
    $proversioninfo = html_writer::tag('p',
        $proversiondescription . ' ' . $proversionlink,
    );

    // Add the plugin name, description, and Pro version description.
    $settings->add(new admin_setting_heading(
        'pluginnameheading',
        '',
        $plugindescription . $proversioninfo
    ));


    $settings->add(new admin_setting_heading(
        'additional_settings',
        get_string('additional_settings', 'quizaccess_proctoring'),
        ''
    ));

    $settings->add(new admin_setting_description(
        'quizaccess_proctoring/adminimage',
        get_string('setting:adminimagepage', 'quizaccess_proctoring'),
        html_writer::div(
            html_writer::link(
                new moodle_url('/mod/quiz/accessrule/proctoring/userslist.php'),
                get_string('setting:userslist', 'quizaccess_proctoring')
            ) .
            html_writer::tag('p',
                get_string('setting:adminimagedescription', 'quizaccess_proctoring')
            )
        )
    ));

    // Settings for the plugin.
    $settings->add(new admin_setting_configtext('quizaccess_proctoring/autoreconfigurecamshotdelay',
        get_string('setting:camshotdelay', 'quizaccess_proctoring'),
        get_string('setting:camshotdelay_desc', 'quizaccess_proctoring'), 30, PARAM_INT));

    $settings->add(new admin_setting_configtext('quizaccess_proctoring/autoreconfigureimagewidth',
        get_string('setting:camshotwidth', 'quizaccess_proctoring'),
        get_string('setting:camshotwidth_desc', 'quizaccess_proctoring'), 230, PARAM_INT));

    // Face recognition method choice.
    $choices = [
        'BS' => 'BS',
        'None' => 'None',
    ];
    $settings->add(new admin_setting_configselect('quizaccess_proctoring/fcmethod',
        get_string('setting:fc_method', 'quizaccess_proctoring'),
        get_string('setting:fc_methoddesc', 'quizaccess_proctoring'),
        get_string('none', 'quizaccess_proctoring'),
        $choices
    ));

    // BS API settings.
    $settings->add(new admin_setting_configtext('quizaccess_proctoring/bsapi',
        get_string('setting:bs_api', 'quizaccess_proctoring'),
        get_string('setting:bs_apidesc', 'quizaccess_proctoring'), '', PARAM_TEXT));

    // New Option BS API KEY.
    $settings->add(new admin_setting_configpasswordunmask('quizaccess_proctoring/bs_api_key',
        get_string('setting:bs_api_key', 'quizaccess_proctoring'),
        get_string('setting:bs_api_keydesc', 'quizaccess_proctoring'), '', PARAM_TEXT));

    // Face recognition threshold.
    $settings->add(new admin_setting_configtext('quizaccess_proctoring/threshold',
        get_string('setting:bs_apifacematchthreshold', 'quizaccess_proctoring'),
        get_string('setting:bs_bs_apifacematchthresholddesc', 'quizaccess_proctoring'), '68', PARAM_INT));

    // AWS face matching settings.
    $settings->add(new admin_setting_configtext('quizaccess_proctoring/awschecknumber',
        get_string('setting:facematch', 'quizaccess_proctoring'),
        get_string('setting:facematchdesc', 'quizaccess_proctoring'), '', PARAM_INT));

    // Checkbox for quiz start face check.
    $settings->add(new admin_setting_configcheckbox('quizaccess_proctoring/fcheckstartchk',
        get_string('settings:fcheckquizstart', 'quizaccess_proctoring'),
        get_string('settings:fcheckquizstart_desc', 'quizaccess_proctoring'), 0));

    // Add an external page under quiz settings for the proctoring users list.
    $ADMIN->add('modsettingsquizcat', new admin_externalpage(
        'quizaccess_proctoring_page',
        get_string('users_list', 'quizaccess_proctoring'),
        new moodle_url('/mod/quiz/accessrule/proctoring/userslist.php'),
        'moodle/site:config'
    ));

    $pageurl = new moodle_url('/mod/quiz/accessrule/proctoring/trigger_delete.php', ['sesskey' => sesskey()]);
    $deletealllinktext = get_string('settingscontroll:deletealllinktext', 'quizaccess_proctoring');

    $deleteallbutton = html_writer::tag('button', $deletealllinktext, [
        'class' => 'btn btn-danger',
        'data-confirmation' => 'modal',
        'data-confirmation-type' => 'delete',
        'data-confirmation-title-str' => json_encode(["delete", "core"]),
        'data-confirmation-content-str' => json_encode(["areyousure_delete_all_record", "quizaccess_proctoring"]),
        'data-confirmation-yes-button-str' => json_encode(["delete", "core"]),
        'data-confirmation-action-url' => $pageurl,
        'data-confirmation-destination' => $pageurl,
    ]);

    // Box containing the delete all images button styled like the upload image message.
    $pageurl = new moodle_url('/mod/quiz/accessrule/proctoring/trigger_delete.php', ['sesskey' => sesskey()]);
    $deleteicon = html_writer::tag('i', '', ['class' => 'fa fa-trash mr-2']);
    $deletealltext = get_string('settingscontroll:deleteall', 'quizaccess_proctoring');
    $deletealllinktext = get_string('settingscontroll:deletealllinktext', 'quizaccess_proctoring');
    $deletealllink = html_writer::tag('button', $deletealllinktext, [
        'class' => 'btn btn-danger',
        'data-confirmation' => 'modal',
        'data-confirmation-type' => 'delete',
        'data-confirmation-title-str' => json_encode(["delete", "core"]),
        'data-confirmation-content-str' => json_encode(["areyousure_delete_all_record", "quizaccess_proctoring"]),
        'data-confirmation-yes-button-str' => json_encode(["delete", "core"]),
        'data-confirmation-action-url' => $pageurl,
        'data-confirmation-destination' => $pageurl,
    ]);

    $deleteallmessage = html_writer::div(
        $deleteicon . ' ' . $deletealltext . ' ' . $deletealllink,
        'p-1'
    );

    $settingdescription = html_writer::div(
        $deleteallbutton .
        html_writer::tag('p', get_string('settingscontroll:deletealldescription', 'quizaccess_proctoring'))
    );

    global $DB;
    $exists = $DB->record_exists('quizaccess_proctoring_logs', ['deletionprogress' => 0]);
    if ($exists) {
        // Add the box containing the delete message and link.
        $settings->add(new admin_setting_description(
            'quizaccess_proctoring/deleteallimages',
            get_string('settingscontroll:deleteall', 'quizaccess_proctoring'),
            $settingdescription
        ));
    }

}
