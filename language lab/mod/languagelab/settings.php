<?php
//************************************************************************
//************************************************************************
//**               LANGUAGE LAB Version 2 for Moodle 2                  **
//************************************************************************
//**@package languagelab                                                **
//**@Institution: oohoo.biz, Campus Saint-Jean, University of Alberta   **
//**@authors : Patrick Thibaudeau, Guillaume Bourbonniere               **
//**@version $Id: version.php,v 1.0 2011/12/17                          **
//**@Moodle integration: Patrick Thibaudeau                             **
//**@Flash programming: Guillaume Bourbonniere                          **
//**@Moodle integration: Patrick Thibaudeau                             **
//************************************************************************
//************************************************************************
$settings->add(new admin_setting_configtext('languagelab_red5server', get_string('red5server', 'languagelab'),
                   get_string('red5server', 'languagelab'), get_string('red5config','languagelab'), PARAM_RAW));
$settings->add(new admin_setting_configtext('languagelab_prefix', get_string('prefix', 'languagelab'),
                   get_string('prefixhelp', 'languagelab'), 'mdl', PARAM_RAW));
$settings->add(new admin_setting_configtext('languagelab_max_users', get_string('maxusers', 'languagelab'),
                   get_string('maxusershelp', 'languagelab'), '25', PARAM_INT));
$settings->add(new admin_setting_configcheckbox('languagelab_stealthMode', get_string('stealthmode', 'languagelab'),
                   get_string('stealthmodehelp', 'languagelab'), '0', 1,0));
$settings->add(new admin_setting_configtext('languagelab_adapter_file', get_string('red5_adapter_file', 'languagelab'),
                   get_string('red5_adapter_file_help', 'languagelab'), '', PARAM_TEXT));
$settings->add(new admin_setting_configcheckbox('languagelab_adapter_access', get_string('red5_adapter_access', 'languagelab'),
                   get_string('red5_adapter_access_help', 'languagelab'), '0',1,0 ));
$settings->add(new admin_setting_configtext('languagelab_salt', get_string('salt', 'languagelab'),
                   get_string('salt_help', 'languagelab'), '', PARAM_TEXT));
//ffmpeg for file conversion
//$settings->add(new admin_setting_configexecutable('ffmpeg', get_string('ffmpeg', 'languagelab'),
 //                  get_string('ffmpeghelp','languagelab'), ''));


?>