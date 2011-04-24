<?php
session_cache_limiter('nocahce'); //Needed for XML to load with IE
//header("Cache-Control: no-cache"); //Prevent caching issues with MSIE
require_once("../../config.php");
require_once("lib.php");
include('../lib/gdlib.php');

//We need to determine if activity is available for the times chosen by teacher


$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$l  = optional_param('l', 0, PARAM_INT);  // languagelab ID

global $DB;

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

//************************Get teacher information*****************************************
require_login($course, true, $cm); //Needed to gather proper course language used

$student = $DB->get_record("user", array("id" => $USER->id));
$studentid = $USER->id;
$studentname = fullname($student);
$userpictureurl = $OUTPUT->user_picture($student, array('courseid' => $course->id, 'link' => false)); //$CFG->wwwroot."/user/pix.php/".$teacher->id."/f2.jpg";
//create an array from the image tag
$newuserpictureurl = explode(' ',$userpictureurl);
//Get the link info from array row 1 and remove src="
$studentpicture = str_replace('src="', '', $newuserpictureurl[1]);
//remove last double quotation marks;
$studentpicture = str_replace('"', '', $studentpicture);
//***************************End Teacher Information**************************************
//
//************************Get language activity params*******************************
$languagelab_params = $DB->get_record('languagelab',array('id' => $languagelab->id));

$now =time();
$available = $languagelab_params->timeavailable < $now && ($now < $languagelab_params->timedue || !$languagelab_params->timedue);
//************************End language activity params******************************

//****************Get students recording*************************************
$recordings = $DB->get_records('languagelab_submissions',array('userid' => $studentid, 'languagelab' => $languagelab->id));
//**************************************************************************



//*******************************number of evalutions submitted **************************************
//Number of submissions is used to determine wether or not the user has submitted a recording
//the reason it is used is to prevent more then one entry in the languagelab_student_evalution table.
//ie. only one entry per languagelab activity can be entered in the submission table. Otherwise, it would cause problems with grading.
$number_of_submissions = $DB->count_records('languagelab_submissions',array('userid' => $studentid, 'languagelab' => $languagelab->id));

// if attempts is true, it must equal 0
if ($languagelab_params->attempts == 1) {
	$attempts = 0;
} else {
	$attempts = 1;
}
//**********XML OUTPUT******************
//echo get_string('subject','languagelab');
$writer = new XMLWriter();
// Output directly to the user

$writer->openURI('php://output');
$writer->startDocument('1.0','UTF-8');

$writer->setIndent(4);

$writer->startElement('playerParam'); //playerParam start
	
	$writer->startElement('localization');
		$writer->startElement('XMLLoadFail');
		$writer->writeCData(get_string('XMLLoadFail','languagelab'));
		$writer->endElement();
		$writer->startElement('warningLossOfWork');
		$writer->writeCData(get_string('warningLossOfWork','languagelab'));
		$writer->endElement();
		$writer->startElement('prerequisitesNotMet');
		$writer->writeCData(get_string('prerequisitesNotMet','languagelab'));
		$writer->endElement();
		$writer->startElement('newRecording');
		$writer->writeCData(get_string('newRecording','languagelab'));
		$writer->endElement();
		$writer->startElement('newReply');
		$writer->writeCData(get_string('newReply','languagelab'));
		$writer->endElement();
		$writer->startElement('timesOut');
		$writer->writeCData(get_string('timesOut','languagelab'));
		$writer->endElement();
		$writer->startElement('subject');
		$writer->writeCData(get_string('subject','languagelab'));
		$writer->endElement();
                $writer->startElement('submitBlank');
		$writer->writeCData(get_string('submitBlank','languagelab'));
		$writer->endElement();
                $writer->startElement('submitNew');
		$writer->writeCData(get_string('submitNew','languagelab'));
		$writer->endElement();
                $writer->startElement('submitChanges');
		$writer->writeCData(get_string('submitChanges','languagelab'));
		$writer->endElement();	
		$writer->startElement('message');
		$writer->writeCData(get_string('message','languagelab'));
		$writer->endElement();	
		
		$writer->startElement('btnDiscard');
		$writer->writeCData(get_string('btnDiscard','languagelab'));
		$writer->endElement();

		$writer->startElement('btnCancel');
		$writer->writeCData(get_string('btnCancel','languagelab'));
		$writer->endElement();
		
		$writer->startElement('submitGrade');
		$writer->writeCData(get_string('submitGrade','languagelab'));
		$writer->endElement();
		
		$writer->startElement('agoBefore');
		$writer->writeCData(get_string('agoBefore','languagelab'));
		$writer->endElement();
		
		$writer->startElement('agoAfter');
		$writer->writeCData(get_string('agoAfter','languagelab'));
		$writer->endElement();
		
		$writer->startElement('days');
		$writer->writeCData(get_string('days','languagelab'));
		$writer->endElement();
		
		$writer->startElement('hours');
		$writer->writeCData(get_string('hours','languagelab'));
		$writer->endElement();
		
		$writer->startElement('minutes');
		$writer->writeCData(get_string('minutes','languagelab'));
		$writer->endElement();
		
		$writer->startElement('seconds');
		$writer->writeCData(get_string('seconds','languagelab'));
		$writer->endElement();
		
		$writer->startElement('grading');
		$writer->writeCData(get_string('grading','languagelab'));
		$writer->endElement();
		
		$writer->startElement('grade');
		$writer->writeCData(get_string('grade','languagelab'));
		$writer->endElement();
		
		$writer->startElement('startOver');
		$writer->writeCData(get_string('startOver','languagelab'));
		$writer->endElement();
		
		$writer->startElement('corrNotes');
		$writer->writeCData(get_string('corrNotes','languagelab'));
		$writer->endElement();
 //days of the week
		
        $writer->startElement('monday');
		$writer->writeCData(get_string('monday','languagelab'));
		$writer->endElement();
        $writer->startElement('tuesday');
		$writer->writeCData(get_string('tuesday','languagelab'));
		$writer->endElement();
        $writer->startElement('wednesday');
		$writer->writeCData(get_string('wednesday','languagelab'));
		$writer->endElement();
        $writer->startElement('thursday');
		$writer->writeCData(get_string('thursday','languagelab'));
		$writer->endElement();
        $writer->startElement('friday');
		$writer->writeCData(get_string('friday','languagelab'));
		$writer->endElement();
        $writer->startElement('saturday');
		$writer->writeCData(get_string('saturday','languagelab'));
		$writer->endElement();
        $writer->startElement('sunday');
		$writer->writeCData(get_string('sunday','languagelab'));
		$writer->endElement();
        //months
        $writer->startElement('january');
		$writer->writeCData(get_string('january','languagelab'));
		$writer->endElement();
        $writer->startElement('february');
		$writer->writeCData(get_string('february','languagelab'));
		$writer->endElement();
        $writer->startElement('march');
		$writer->writeCData(get_string('march','languagelab'));
		$writer->endElement();
        $writer->startElement('april');
		$writer->writeCData(get_string('april','languagelab'));
		$writer->endElement();
        $writer->startElement('may');
		$writer->writeCData(get_string('may','languagelab'));
		$writer->endElement();
        $writer->startElement('june');
		$writer->writeCData(get_string('june','languagelab'));
		$writer->endElement();
        $writer->startElement('july');
		$writer->writeCData(get_string('july','languagelab'));
		$writer->endElement();
        $writer->startElement('august');
		$writer->writeCData(get_string('august','languagelab'));
		$writer->endElement();
        $writer->startElement('september');
		$writer->writeCData(get_string('september','languagelab'));
		$writer->endElement();
        $writer->startElement('october');
		$writer->writeCData(get_string('october','languagelab'));
		$writer->endElement();
        $writer->startElement('november');
		$writer->writeCData(get_string('november','languagelab'));
		$writer->endElement();
        $writer->startElement('december');
		$writer->writeCData(get_string('december','languagelab'));
		$writer->endElement();

	$writer->endElement(); //End localization


	$writer->startElement('basicParam'); //start basicParam
		$writer->startElement('envVar'); //start envVar
            $writer->writeAttribute('targetServer','rtmp://'.$CFG->languagelab_red5server.'/oflaDemo');
            $writer->writeAttribute('targetPost',$CFG->wwwroot.'/mod/languagelab/studentview.controller.php');
            $writer->writeAttribute('languageLabId',$languagelab->id);
            $writer->writeAttribute('courseModuleId',$cm->id);
            $writer->writeAttribute('courseId',$course->id);
            $writer->writeAttribute('xssAddress',$CFG->languagelab_xssAddress);
            $writer->writeAttribute('xssPort',$CFG->languagelab_xssPort);
            $writer->writeAttribute('courseName',trim($course->fullname));
            $writer->writeAttribute('activityName',$languagelab->name);
        $writer->endElement(); //end envVar

        $writer->startElement('permissions'); //start permissions
                        if ($available) {
			 $writer->writeAttribute('bCanRecordNewStreams','1');
                        } else {
                         $writer->writeAttribute('bCanRecordNewStreams','0');
                        }
			$writer->writeAttribute('bCanReply','0');
			$writer->writeAttribute('bMultipleRecordings',$attempts);
			$writer->writeAttribute('bAutoSubmit','0');
			$writer->writeAttribute('recording_timelimit',$languagelab_params->recording_timelimit);
		$writer->endElement(); //end permissions

		$writer->startElement('recordParam'); //start recordParma
			$writer->writeAttribute('targetRecording',$CFG->languagelab_prefix.'_'.$languagelab->id.'_'.$USER->id.'_'.time());
			$writer->writeAttribute('author',$studentname);//Student
			$writer->writeAttribute('authorId',$studentid);//Students ID number
			$writer->writeAttribute('portrait',$studentpicture);
			$writer->writeAttribute('count',$number_of_submissions);
		$writer->endElement(); // End recordParam

	$writer->endElement(); //End basicParam

	$writer->startElement('previousRecordings'); //Start previousRecordings
	//Note: This has to be in a loop

        
        foreach ($recordings as $recording) {
		
			$writer->startElement('recording'); //start recording
							$writer->writeAttribute('recURI',$recording->path);
							$writer->writeAttribute('lastUpdate',$recording->timemodified);
							$writer->writeAttribute('label',str_replace('@@',"'",$recording->label));
							$writer->writeAttribute('author',$studentname);
							$writer->writeAttribute('portrait',$userpictureurl);
							$writer->writeAttribute('tMessage',$recording->message);
							$writer->writeAttribute('recordingid',$recording->id);
                                                        if ($available) {
                                                        $writer->writeAttribute('bRec','true');
                                                        } else {
                                                            $writer->writeAttribute('bRec','false');
                                                        }
							$parentnode = array("parentnode" =>$recording->path, "languagelab" => $languagelab->id);
							
							$childnodes = $DB->get_records('languagelab_submissions',$parentnode);
							//now to verify if there are child nodes to this recording
							
							//print_object($childnodes);
								foreach ($childnodes as $childnode) {
									//Get teacher info
									$teacher = $DB->get_record("user", array("id" => $childnode->userid));
									$userpictureurl = $OUTPUT->user_picture($teacher, array('courseid' => $course->id, 'link' => false)); //$CFG->wwwroot."/user/pix.php/".$teacher->id."/f2.jpg";
                                                                        //create an array from the image tag
                                                                        $newuserpictureurl = explode(' ',$userpictureurl);
                                                                        //Get the link info from array row 1 and remove src="
                                                                        $teacherpicture = str_replace('src="', '', $newuserpictureurl[1]);
                                                                        //remove last double quotation marks;
                                                                        $teacherpicture = str_replace('"', '', $teacherpicture);
									$writer->startElement('reply'); //start reply
										$writer->writeAttribute('recURI',$childnode->path);
										$writer->writeAttribute('lastUpdate',$childnode->timemodified);
										$writer->writeAttribute('label',$childnode->label);
										$writer->writeAttribute('author',fullname($teacher));
										$writer->writeAttribute('portrait',$teacherpicture);
										$writer->writeAttribute('tMessage',$childnode->message);
									$writer->endElement(); // End reply
								} 
						$writer->endElement(); //end recording
		
		}
        

	$writer->endElement(); //End previousRecordings
	


$writer->endElement(); // end playerParam
 ?>