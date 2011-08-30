<?php
session_cache_limiter('nocache'); //Needed for XML to load with IE
require_once("../../config.php");
require_once("lib.php");



$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$l  = optional_param('l', 0, PARAM_INT);  // languagelab ID

global $DB, $CFG;

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


//***************************End Teacher Information**************************************
//
//**************************Student information*******************************************

$students = get_enrolled_users($context,'mod/languagelab:studentview');

//****************************End Student information

//************************Get master track*******************************
if (isset($languagelab->master_track)){
$mastertrack = moodle_url::make_pluginfile_url($context->id,'mod_languagelab','mastertrack',0,'/',$languagelab->master_track);
} else {
    $mastertrack = '';
}
//************************End Get master track*******************************

//**********XML OUTPUT******************

$writer = new XMLWriter();
// Output directly to the user

$writer->openURI('php://output');
$writer->startDocument('1.0','UTF-8');

$writer->setIndent(4);

$writer->startElement('playerParam');

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

	$writer->startElement('basicParam');
	    $writer->startElement('envVar');
            $writer->writeAttribute('targetServer','rtmp://'.$CFG->languagelab_red5server.'/oflaDemo');
            $writer->writeAttribute('targetPost','./teacherview.controller.php');
            $writer->writeAttribute('languageLabId',$languagelab->id);
            $writer->writeAttribute('courseModuleId',$cm->id);
            $writer->writeAttribute('xssAddress',$CFG->languagelab_xssAddress);
            $writer->writeAttribute('xssPort',$CFG->languagelab_xssPort);
            $writer->writeAttribute('courseName',$course->fullname);
            $writer->writeAttribute('activityName',$languagelab->name);
            $writer->writeAttribute('useGradeBook',$languagelab->use_grade_book);
            $writer->writeAttribute('masterTrack',$mastertrack);
        $writer->endElement(); //end envVar

        $writer->startElement('permissions');
			$writer->writeAttribute('bCanRecordNewStreams','0');
			$writer->writeAttribute('bCanReply','1');
			$writer->writeAttribute('bMultipleRecordings','0');
			$writer->writeAttribute('bAutoSubmit','0');
			$writer->writeAttribute('recording_timelimit','0');
		$writer->endElement(); //end permissions

		$writer->startElement('recordParam');
			$writer->writeAttribute('targetRecording', $CFG->languagelab_prefix.'_'.$languagelab->id.'_'.$USER->id.'_'.time());
			$writer->writeAttribute('author',$teachername);//Teacher
			$writer->writeAttribute('authorId',$teacherid);//Teacher
			$writer->writeAttribute('portrait',$teacherpicture);
                        $writer->writeAttribute('videoMode', $languagelab->video);
		$writer->endElement(); // End recordParam

                $writer->startElement('teacherParam');
                    $writer->writeAttribute('bTeach', '1');
                    $writer->writeAttribute('bEvaluated', $languagelab->use_grade_book);
                $writer->endElement(); // End teacherParam

	$writer->endElement(); //End basicParam

	$writer->startElement('previousRecordings');
	//Note: This has to be in a loop
        foreach ($students as $student) {

				//echo fullname($student)."<br>";
				//Student element gathers student name and ID for use in the tree function
				//***************Get the students grade******************************
				$student_grade = $DB->get_record('languagelab_student_eval',array('userid' => $student->id,'languagelab' => $languagelab->id));
				//*******************************************************************
	                        $writer->startElement('student');
				$writer->writeAttribute('label',fullname($student));
				$writer->writeAttribute('studentId',$student->id);
                                if (isset($student_grade->grade)){
                                    $writer->writeAttribute('grade',$student_grade->grade);
                                } else {
                                    $writer->writeAttribute('grade','');
                                }
                                if (isset($student_grade->id)){
                                    $writer->writeAttribute('gradeid',$student_grade->id);//languagelab_student_evalution->id
                                } else {
                                    $writer->writeAttribute('gradeid','');
                                }
                                  //Get student picture Because search_users function only gives fullname and email
				  //We need to get the student record in order to gather more information on the user.
				  $studentinfo = $DB->get_record("user",array("id" => $student->id));
				  $studentpictureurl = $OUTPUT->user_picture($studentinfo, array('courseid' => $course->id, 'link' => false));
                                  //create an array from the image tag
                                    $newstudentpictureurl = explode(' ',$studentpictureurl);
                                                                        //Get the link info from array row 1 and remove src="
                                    $studentpicture = str_replace('src="', '', $newstudentpictureurl[1]);
                                    //remove last double quotation marks;
                                    $studentpicture = str_replace('"', '', $studentpicture);
                                    					
				  //get users recordings
				  $studentrecordings = $DB->get_records('languagelab_submissions',array('userid' => $student->id, 'languagelab' => $languagelab->id));
					foreach ($studentrecordings as $studentrecording) {
					
						$writer->startElement('recording');
							$writer->writeAttribute('recURI',$studentrecording->path);
							$writer->writeAttribute('lastUpdate',$studentrecording->timemodified);
							$writer->writeAttribute('label',str_replace('@@',"'",$studentrecording->label));
							$writer->writeAttribute('author',fullname($student));
							$writer->writeAttribute('userid',$student->id);
							$writer->writeAttribute('portrait',$studentpicture);
							$writer->writeAttribute('tMessage',$studentrecording->message);
							$writer->writeAttribute('recordingid',$studentrecording->id);
                                                        if (isset($student_grade->grade)){
                                                            $writer->writeAttribute('grade',$student_grade->grade);
                                                        } else {
                                                            $writer->writeAttribute('grade','');
                                                        }
                                                        if (isset($student_grade->correctionnotes)){
                                                            $writer->writeAttribute('corrNotes',$student_grade->correctionnotes);
                                                        } else {
                                                            $writer->writeAttribute('corrNotes','');
                                                        }
							
							$parentnode = array("parentnode" => $studentrecording->path, "languagelab" => $languagelab->id);
														
							//now to verify if there are child nodes to this recording
							$replyrecordings = $DB->get_records('languagelab_submissions',$parentnode);
							
								foreach ($replyrecordings as $replyrecording) {
								$writer->startElement('reply');
									$writer->writeAttribute('recURI',$replyrecording->path);
									$writer->writeAttribute('lastUpdate',$replyrecording->timemodified);
									$writer->writeAttribute('label',$replyrecording->label);
									$writer->writeAttribute('author',$teachername);
									$writer->writeAttribute('userid',$teacherid);
									$writer->writeAttribute('portrait',$userpictureurl);
									$writer->writeAttribute('tMessage',$replyrecording->message);
									$writer->writeAttribute('recordingid',$replyrecording->id);
								$writer->endElement(); // End reply
								}
						$writer->endElement(); //end recording
					}
				$writer->endElement(); //End Student
			
		}

	$writer->endElement(); //End previousRecordings

	

$writer->endElement(); // end playerParam
 ?>