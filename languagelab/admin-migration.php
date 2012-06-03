<?php
//************************************************************************
//************************************************************************
//**               LANGUAGE LAB Version 3 for Moodle 2                  **
//************************************************************************
//**@package languagelab                                                **
//**@Institution: oohoo.biz, Campus Saint-Jean, University of Alberta   **
//**@authors : Patrick Thibaudeau, Nicolas Bretin                       **
//**@version $Id: version.php,v 1.0 2012/05/10                          **
//**@Moodle integration: Patrick Thibaudeau, Nicolas Bretin             **
//**@Flash programming: Nicolas Bretin                                  **
//**@Moodle integration: Patrick Thibaudeau, Nicolas Bretin             **
//************************************************************************
//************************************************************************



/// (Replace languagelab with the name of your module)
	//require_once ($CFG->dirroot.'/course/moodleform_mod.php');
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once("$CFG->dirroot/lib/resourcelib.php");
require_once("$CFG->dirroot/lib/filestorage/file_storage.php");
require_once("locallib.php");

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$l  = optional_param('l', 0, PARAM_INT);  // languagelab ID


global $CFG,$DB, $PAGE;


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

$PAGE->set_url('/mod/languagelab/admin-migration.php', array('id' => $cm->id));
$PAGE->set_title($languagelab->name);
$PAGE->set_heading($course->shortname);
$PAGE->set_button(update_module_button($cm->id, $course->id, get_string('languagelab', 'languagelab')));

$context = get_context_instance(CONTEXT_MODULE, $cm->id);
$contextcourse = get_context_instance(CONTEXT_COURSE, $course->id);

//print_object($context);
// Output starts here
echo $OUTPUT->header();


/// Print the main part of the page
echo $OUTPUT->box_start();

if (!is_siteadmin($USER)){
    error('Only administrators can access this page');
}
else
{
    echo "NOTHING AVAILABLE HERE";
    migrate_all_flv_to_mp3_recording($id);
}

echo $OUTPUT->box_end();


    
// Finish the page
echo $OUTPUT->footer();



?>
