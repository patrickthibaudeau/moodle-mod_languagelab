<?php
//***********************************************************
//**               LANGUAGE LAB MODULE 1                   **
//***********************************************************
//**@package languagelab                                   **
//**@Institution: Campus Saint-Jean, University of Alberta **
//**@authors : Patrick Thibaudeau, Guillaume Bourbonnière  **
//**@version $Id: version.php,v 1.0 2009/05/25 7:33:00    **
//**@Moodle integration: Patrick Thibaudeau                **
//**@Flash programming: Guillaume Bourbonnière             **
//**@CSS Developement: Brian Neeland                       **
//***********************************************************
//***********************************************************

require_once ("../../config.php");
require_once("lib.php");
global $DB;
$action = $_POST["what"];
$languagelab = $_POST["languagelab"];
$path = $_POST["path"];
$label = $_POST["label"];
$message = $_POST["message"];
$parentnode = $_POST["parentnode"];
$studentid = $_POST["studentid"];
$teacherid = $_POST["teacherid"];
$grade = $_POST["grade"];
$correctionnotes = $_POST["correctionnotes"];
$submissionid = $_POST["submissionid"];
$LanguageLabStudentEvaluation_id = $_POST["gradeid"];

echo "action=".$action."<br>";
echo "languagelab=".$languagelab."<br>";
echo "path=".$path."<br>";
echo "label=".$label."<br>";
echo "message=".$message."<br>";
echo "parentnode=".$parentnode."<br>";
echo "studentid=".$studentid."<br>";
echo "teacherid=".$teacherid."<br>";
echo "grade=".$grade."<br>";
echo "submissionid=".$submissionid."<br>";

//**************Object need to pass grades*******************
$languagelab_obj = $DB->get_record("languagelab", array("id" => $languagelab));
//***********************************************************

//**************Create objects with user info for sending email*******************
//Teacher first
$teacher_info = $DB->get_record("user", array("id" => $teacherid));
//Now student
$student_info = $DB->get_record("user", array("id" => $studentid));
//***********************************************************
if ($action == 'insert_record') {

    //******************Enter reply information into languagelab_submission table********************************
	//The information needed is languagelab,teacherid = userid, parentnode,label,message. Grade information will be submitted
	//in the evaluation table after this first step.
	
	$save_recording = new object(); {
	$save_recording->languagelab = $languagelab;
	$save_recording->userid = $teacherid;
	$save_recording->path = $path;
	$save_recording->label = $label;
	$save_recording->message = $message;
	$save_recording->parentnode = $parentnode;
	$save_recording->timecreated = time();
	$save_recording->timemodified = time();
	}
	
	if (!$DB->insert_record('languagelab_submissions',$save_recording)) {
		print_simple_box (get_string('evaluation_failed_save','languagelab'),'center','70%');
		} else {
		print_simple_box (get_string('evaluation_saved','languagelab'),'center','70%');
 		}
    //********Send email notification to student**************
	$subject =$languagelab_obj->name;
	$body = get_string('emailgreeting','languagelab').' '.fullname($student_info)."\n";
	$body .= get_string('emailbodynewreply','languagelab')."\n";
	$body .= get_string('emailthankyou','languagelab')."\n";
	$body .= fullname($teacher_info)."\n";
	echo $studentid."<br>";
	echo $teacherid."<br>";
	echo $subject."<br>";
	echo $body;
	if (email_to_user($student_info,$teacher_info,$subject,$body)){
	echo "email sent";
	};
	//**********************************************************
	
	return false ;
	
}
if ($action == 'update_grade') {

	$update_grade = new object();{
	$update_grade->id = $LanguageLabStudentEvaluation_id;
	$update_grade->grade = $grade;
	$update_grade->correctionnotes = $correctionnotes;
	$update_grade->teacher = $teacherid;
	$update_grade->timemarked = time();
	$update_grade->timemodified = time();

	}

	if (!$DB->update_record('languagelab_student_eval',$update_grade)) {
		print_simple_box (get_string('evaluation_failed_save','languagelab'),'center','70%');
		} else {

		print_simple_box (get_string('evaluation_saved','languagelab'),'center','70%');
		}
		
	// update grade item definition
    languagelab_update_grades($languagelab_obj);
	
	return false;

}
if ($action == 'update_record') {
    
    
    $update_recording = new object(); {
	$update_recording->id = $submissionid;
	$update_recording->label = $label;
	$update_recording->message = $message;
	$update_recording->timemodified = time();

	if (!$DB->update_record('languagelab_submissions',$update_recording)) {
		print_simple_box (get_string('evaluation_failed_save','languagelab'),'center','70%');
		} else {

		print_simple_box (get_string('evaluation_saved','languagelab'),'center','70%');;
		}

	}

	return false;
	
	
}

if ($action == 'delete_record') {
    
	//get parentnode from record being deleted
	$parentnode_id =$DB->get_record('languagelab_submissions',array('id' => $submissionid));
		
	$DB->delete_records('languagelab_submissions',array('id' => $submissionid));
	$DB->delete_records('languagelab_submissions',array('parentnode' => $parentnode_id->path));
	
	//********Send email notification to student**************
	$subject =$languagelab_obj->name;
	$body = get_string('emailgreeting','languagelab').' '.fullname($student_info)."\n";
	$body .= get_string('emailbodydelete','languagelab')."\n";
	$body .= get_string('emailthankyou','languagelab')."\n";
	$body .= fullname($teacher_info)."\n";
	echo $studentid."<br>";
	echo $teacherid."<br>";
	echo $subject."<br>";
	echo $body;
	if (email_to_user($student_info,$teacher_info,$subject,$body)){
	//echo "email sent";
	};
	//**********************************************************
	return false;
	
}
?>