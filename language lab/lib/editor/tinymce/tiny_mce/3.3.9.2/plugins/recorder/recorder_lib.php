<?php
require_once("../../../../../../../config.php");
require_once($CFG->dirroot.'/mod/languagelab/locallib.php');

function recorder_convert($file, $userid){
    global $CFG ;
    
    $context = get_context_instance(CONTEXT_USER, $userid);
    
    //First check if ffmpeg is set. If not then we have to use the filter
    if (empty($CFG->ffmpeg)) {
       echo "<img hspace='0' height='16' border='0' width='16' vspace='0' src='$CFG->wwwroot/mod/languagelab/pix/recording_image.gif'  title='$file' />";
    } else {
        if ($CFG->localred5) {
            convert_stream_editor($file, $context);
        } else {
            convert_ftp_stream_editor($file, $context);
        }
    }

}

?>
