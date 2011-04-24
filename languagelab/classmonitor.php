<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

global $DB, $CFG;

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$l  = optional_param('l', 0, PARAM_INT);  // languagelab ID

if ($id) {
    $cm         = get_coursemodule_from_id('languagelab', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $languagelab  = $DB->get_record('languagelab', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($l) {
    $languagelab  = $DB->get_record('languagelab', array('id' => $l), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $languagelab->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('languagelab', $languagelab->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

$context = get_context_instance(CONTEXT_MODULE, $cm->id);

require_login($course, true, $cm); //Needed to gather proper course language used

$filename = "$CFG->wwwroot/mod/languagelab/CMparamxml.php?id=$id";

$PAGE->set_url('/mod/languagelab/classmonitor.php', array('id' => $cm->id));
$PAGE->set_title($languagelab->name);
$PAGE->set_heading($course->shortname);

echo $OUTPUT->header();
?>
<style type="text/css" media="screen">
		html, body { height:100%; background-color: #ffffff;}
		body { margin:0; padding:0; overflow:hidden; }
		#ClassroomMonitor { width:100%; height:100%; }
		</style>
		<div id="flashContent">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="700" height="400" id="ClassroomMonitor" align="middle">
				<param name="movie" value="ClassroomMonitor.swf" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#ffffff" />
				<param name="play" value="true" />
				<param name="loop" value="true" />
				<param name="wmode" value="window" />
				<param name="scale" value="showall" />
				<param name="menu" value="true" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
                                <param name="FlashVars" value="param=<?php echo $filename?>" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="ClassroomMonitor.swf" width="700" height="400">
					<param name="movie" value="ClassroomMonitor.swf" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#ffffff" />
					<param name="play" value="true" />
					<param name="loop" value="true" />
					<param name="wmode" value="window" />
					<param name="scale" value="showall" />
					<param name="menu" value="true" />
					<param name="devicefont" value="false" />
					<param name="salign" value="" />
					<param name="allowScriptAccess" value="sameDomain" />
                                        <param name="FlashVars" value="param=<?php echo $filename?>"  />
				<!--<![endif]-->
					<a href="http://www.adobe.com/go/getflash">
						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
					</a>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
		</div>

<?php
$OUTPUT->footer();
?>