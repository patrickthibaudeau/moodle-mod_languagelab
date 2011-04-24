<?php
require("../../../../../../../config.php");
require('recorder_lib.php');

$function = required_param('function', PARAM_ALPHAEXT);
$file = required_param('file', PARAM_TEXT);
$userid = optional_param('userid',0, PARAM_INT);

$function($file, $userid);



?>
