<?php
//Load file that contains the server information for Moodle
$CFG = new stdClass();
//Change this value to the root folder of your host
$CFG->rootdir = '/var/www/';
//Change this value to point to your config.xml file.
$CFG->xml_path = "/var/config.xml";
//Change this value to point to a temp folder. Make sure write read permissions are set properly
$CFG->temp_folder = "/var/tmp/";
//Path to ffmpeg for mp3 conversion. If empty, you will not be able to convert flv to mp3
//Usually found in /usr/bin/
$CFG->ffmpeg = "";
?>