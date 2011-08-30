<?php
//***********************************************************
//**               LANGUAGE LAB MODULE 1                   **
//***********************************************************
//**@package languagelab                                   **
//**@Institution: Campus Saint-Jean, University of Alberta **
//**@authors : Patrick Thibaudeau, Guillaume Bourbonni?re  **
//**@version $Id: version.php,v 1.0 2009/05/25 7:33:00    **
//**@Moodle integration: Patrick Thibaudeau                **
//**@Flash programming: Guillaume Bourbonni?re             **
//**@CSS Developement: Brian Neeland                       **
//***********************************************************
//***********************************************************



/// (Replace languagelab with the name of your module)
	//require_once ($CFG->dirroot.'/course/moodleform_mod.php');
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once("$CFG->dirroot/lib/resourcelib.php");
require_once("$CFG->dirroot/lib/filestorage/file_storage.php");
require_once("locallib.php");

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$l  = optional_param('l', 0, PARAM_INT);  // languagelab ID
$action = optional_param('what', 'view', PARAM_CLEAN); 
//$usehtmleditor = can_use_html_editor();

global $CFG,$DB;

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


require_login($course, true, $cm);

add_to_log($course->id, 'languagelab', 'view', "view.php?id=$cm->id", $languagelab->name, $cm->id);

/// Print the page header

$PAGE->set_url('/mod/languagelab/view.php', array('id' => $cm->id));
$PAGE->set_title($languagelab->name);
$PAGE->set_heading($course->shortname);
$PAGE->set_button(update_module_button($cm->id, $course->id, get_string('languagelab', 'languagelab')));

$context = get_context_instance(CONTEXT_MODULE, $cm->id);
$contextcourse = get_context_instance(CONTEXT_COURSE, $course->id);

if (groupmode($course, $cm) == SEPARATEGROUPS) {
    $groupid = get_current_group($course->id);
} else {
    $groupid = 0;
}

//print_object($context);
// Output starts here
echo $OUTPUT->header();


/// Print the main part of the page
echo $OUTPUT->box_start();
        
        $content = file_rewrite_pluginfile_urls($languagelab->description, 'pluginfile.php', $context->id, 'mod_languagelab', 'content', $languagelab->id);
        $formatoptions = array('noclean'=>true, 'overflowdiv'=>true);
        $content = format_text($content, $languagelab->contentformat, $formatoptions, $course->id);
        
        echo $OUTPUT->box($content, 'generalbox center clearfix');

    echo '<div align=\'center\'>';
    
    if (has_capability('mod/languagelab:teacherview', $context, null, true)){

    	//print out flash applet
        echo '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="800" height="650" id="TeacherConsole" align="middle">'."\n";
	echo '<param name="allowScriptAccess" value="sameDomain" />'."\n";
	echo '<param name="allowFullScreen" value="true" />'."\n";
	echo '<param name="wmode" value="transparent"> '."\n";
	echo '<param name="movie" value="LanguageLabCT.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><param name="flashvars" value="xmlAddress=teacher.param.xml.php?id='.$id.'"/><embed src="LanguageLabCT.swf" flashvars="xmlAddress=teacher.param.xml.php?id='.$id.'" quality="high" bgcolor="#ffffff" width="800" height="650" name="TeacherConsole" align="middle" wmode="transparent" allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />'."\n";
	echo '</object>'."\n";
        echo $OUTPUT->box("<a href='classmonitor.php?id=$id' target='_blank'>".get_string('classmonitor','languagelab')."</a>");
	echo "</div>";


    } else {
        
    	//print out flash applet
    	echo '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="400" height="650" id="SimpleRecorderGB2" align="middle">'."\n";
	echo '<param name="allowScriptAccess" value="sameDomain" />'."\n";
	echo '<param name="allowFullScreen" value="false" />'."\n";
	echo '<param name="wmode" value="transparent"> '."\n";
	echo '<param name="movie" value="LanguageLabCT.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><param name="flashvars" value="xmlAddress=student.param.xml.php?id='.$id.'"/>	<embed src="LanguageLabCT.swf" wmode="transparent" flashvars="xmlAddress=student.param.xml.php?id='.$id.'" quality="high" bgcolor="#ffffff" width="400" height="650" name="StudentConsole" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />'."\n";
	echo '</object>'."\n";
           echo '</div>';
        //print out restrictions
        
        //We need to determine if activity is available for the times chosen by teacher
	$now =time();
	$available = $languagelab->timeavailable < $now && ($now < $languagelab->timedue || !$languagelab->timedue);
        if (!$available)  {

		//Enter the proper date/time or a text message if no date/time
		if (empty($languagelab->timedue)) {
				$timedue = get_string('no_due_date','languagelab');
		} else {
				$timedue = userdate($languagelab->timedue);
		}

		if (empty($languagelab->timeavailable)) {
				$timeavailable = get_string('no_available_date','languagelab');
		} else {
				$timeavailable = userdate($languagelab->timeavailable);
		}

		//Activity not availabe
		$OUTPUT->box(get_string('not_available','languagelab').'<p>'.get_string('availabledate','languagelab').$timeavailable.'<br>'.get_string('duedate','languagelab').$timedue,'languagelab_submit', 'languagelab_submit');

	}
    }
echo $OUTPUT->box_end();

// Finish the page
echo $OUTPUT->footer();

?>
