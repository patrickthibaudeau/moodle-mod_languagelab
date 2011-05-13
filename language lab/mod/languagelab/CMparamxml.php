<?php
session_cache_limiter('nocache'); //Needed for XML to load with IE
require_once("../../config.php");
require_once("lib.php");

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$l  = optional_param('l', 0, PARAM_INT);  // languagelab ID

global $DB, $CFG, $OUTPUT;

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

//look for all languagelab activities within course
$coursemodules = get_coursemodules_in_course('languagelab', $course->id);

$teacher = $DB->get_record("user", array("id" => $USER->id));
$teacherid = $USER->id;
$teachername= fullname($teacher);


//Moodle 2 makes it easier to print hte user picture, however, a little manipulation is necessary to grab the link
//First get user picture
$userpictureurl = $OUTPUT->user_picture($teacher, array('courseid' => $course->id, 'link' => false)); //$CFG->wwwroot."/user/pix.php/".$teacher->id."/f2.jpg";
//create an array from the image tag
$newuserpictureurl = explode(' ',$userpictureurl);
//Get the link info from array row 1 and remove src="
$teacherpicture = str_replace('src="', '', $newuserpictureurl[1]);
//remove last double quotation marks;
$teacherpicture = str_replace('"', '', $teacherpicture);

//**************************Student information*******************************************

$students = get_enrolled_users($context,'mod/languagelab:studentview');

//****************************End Student information

$writer = new XMLWriter();
// Output directly to the user

$writer->openURI('php://output');
$writer->startDocument('1.0','UTF-8');

$writer->setIndent(4);

$writer->startElement('params');
    $writer->startElement('config');
        $writer->startElement('env');
            $writer->writeAttribute('streamingServer',$CFG->languagelab_red5server);
            $writer->writeAttribute('teacherStream', $CFG->languagelab_prefix.'_'.$USER->id.'_classmon_'.time());
            $writer->writeAttribute('stealthMode', $CFG->languagelab_stealthMode);
            $writer->writeAttribute('socketServerIP',$CFG->languagelab_xssAddress);
            $writer->writeAttribute('socketServerPort',$CFG->languagelab_xssPort);
            $writer->writeAttribute('userID',$USER->id);
            $writer->writeAttribute('userPortrait', $teacherpicture);
        $writer->endElement(); // env
        $writer->startElement('localization');
        $writer->endElement(); //localization
    $writer->endElement(); //config
    $writer->startElement('courses');
        $writer->startElement('course');
            $writer->writeAttribute('id', $course->id);
            $writer->writeAttribute('name', $course->fullname);
            //foreach should only be used in the block to view all course LL activities
            //foreach ($coursemodules as $coursemodule){
                $writer->startElement('activity');
                    $writer->writeAttribute('id', $cm->instance);
                    $writer->writeAttribute('name', $cm->name);
                    $writer->writeAttribute('group', 0);
                $writer->endElement(); //activity
            //}
        $writer->endElement(); //Course
    $writer->endElement(); //courses
    $writer->startElement('students');
    foreach($students as $student){
        $writer->startElement('student');
            //Get student picture Because search_users function only gives fullname and email
            //We need to get the student record in order to more information on the user.
            $studentinfo = $DB->get_record("user",array("id" => $student->id));
            $studentpictureurl = $OUTPUT->user_picture($studentinfo, array('courseid' => $course->id, 'link' => false));
            //create an array from the image tag
            $newstudentpictureurl = explode(' ',$studentpictureurl);
            //Get the link info from array row 1 and remove src="
           $studentpicture = str_replace('src="', '', $newstudentpictureurl[1]);
           //remove last double quotation marks;
           $studentpicture = str_replace('"', '', $studentpicture);
           //Grab biggest image;
           $studentpicture = str_replace('f2', 'f1', $studentpicture);
            //Write attributes
            $writer->writeAttribute('id',$student->id);
            $writer->writeAttribute('name',fullname($student));
             $writer->writeAttribute('portrait',$studentpicture);
        $writer->endElement(); //student
    }
    $writer->endElement(); //students
$writer->endElement(); //params
?>

