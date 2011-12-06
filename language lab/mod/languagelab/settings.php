<?php
//***********************************************************
//**               LANGUAGE LAB MODULE 1                   **
//***********************************************************
//**@package languagelab                                   **
//**@Institution: Campus Saint-Jean, University of Alberta **
//**@authors : Patrick Thibaudeau, Guillaume Bourbonni�re  **
//**@version $Id: version.php,v 1.0 2009/05/25 7:33:00    **
//**@Moodle integration: Patrick Thibaudeau                **
//**@Flash programming: Guillaume Bourbonni�re             **
//**@CSS Developement: Brian Neeland                       **
//***********************************************************
//***********************************************************
$settings->add(new admin_setting_configtext('languagelab_red5server', get_string('red5server', 'languagelab'),
                   get_string('red5server', 'languagelab'), get_string('red5config','languagelab'), PARAM_RAW));
$settings->add(new admin_setting_configtext('languagelab_prefix', get_string('prefix', 'languagelab'),
                   get_string('prefixhelp', 'languagelab'), 'mdl', PARAM_RAW));
$settings->add(new admin_setting_configtext('languagelab_max_users', get_string('maxusers', 'languagelab'),
                   get_string('maxusershelp', 'languagelab'), '25', PARAM_INT));
$settings->add(new admin_setting_configcheckbox('languagelab_stealthMode', get_string('stealthmode', 'languagelab'),
                   get_string('stealthmodehelp', 'languagelab'), '0', 1,0));
//ffmpeg for file conversion
//$settings->add(new admin_setting_configexecutable('ffmpeg', get_string('ffmpeg', 'languagelab'),
 //                  get_string('ffmpeghelp','languagelab'), ''));
//Red5 Server storage information
//Since we are using cURL http, we no longer need FTP or local path. An http alias can be used for this
/*
$settings->add(new admin_setting_configcheckbox('localred5', get_string('localred5', 'languagelab'),
                  get_string('localred5help', 'languagelab'),0));
$settings->add(new admin_setting_configtext('red5path', get_string('red5path', 'languagelab'),
                    get_string('red5pathhelp', 'languagelab'), '', PARAM_RAW));
$settings->add(new admin_setting_configtext('ftphost', get_string('ftphost', 'languagelab'), get_string('ftphosthelp', 'languagelab'),
                    '', PARAM_RAW));
$settings->add(new admin_setting_configtext('ftpusername', get_string('ftpusername', 'languagelab'),
                    get_string('ftpusernamehelp', 'languagelab'), '', PARAM_RAW));
$settings->add(new admin_setting_configpasswordunmask('ftpuserpassword', get_string('ftpuserpassword', 'languagelab'),
                    get_string('ftpuserpasswordhelp', 'languagelab'), '', PARAM_RAW));
$settings->add(new admin_setting_configselect('ftpprotocol', get_string('ftpprotocol', 'languagelab'), get_string('ftpprotocolhelp', 'languagelab'),
                    '', array('ftp' => 'FTP','ftps' => 'FTPS')));
$settings->add(new admin_setting_configtext('ftpport', get_string('ftpport', 'languagelab'), get_string('ftpporthelp', 'languagelab'),
                    '21', PARAM_RAW));
$settings->add(new admin_setting_configtext('ftpdirectory', get_string('ftpdirectory', 'languagelab'), get_string('ftpdirectoryhelp', 'languagelab'),
                    '/', PARAM_RAW));
*/

?>