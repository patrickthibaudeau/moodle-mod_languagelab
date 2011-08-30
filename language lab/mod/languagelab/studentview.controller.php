<?php
//***********************************************************
//**               LANGUAGE LAB MODULE 1                   **
//***********************************************************
//**@package languagelab                                   **
//**@Institution: Campus Saint-Jean, University of Alberta **
//**@authors : Patrick Thibaudeau, Guillaume Bourbonni�re  **
//**@version $Id: version.php,v 1.0 2009/05/25 7:33:00    **
//**@Moodle integration: Patrick Thibaudeau                **
//**@Flash programming: Guillaume Bourbonni�re             **
//**@CSS Developement: Brian Neeland                       **
//***********************************************************
//***********************************************************
require_once("../../config.php");
require_once("lib.php");
global $DB;

$action = optional_param("what",'',PARAM_TEXT);
$languagelab = optional_param("languagelab",0, PARAM_INT);
$userid = optional_param("userid",0,PARAM_INT); //Student id
$path = optional_param("path",'',PARAM_TEXT);
$label = optional_param("label",'', PARAM_TEXT);
$message = optional_param("message",'',PARAM_TEXT);
$count = optional_param("count",0,PARAM_INT);
$id = optional_param("submission_id",0, PARAM_INT);
$groupid = optional_param("groupid",0, PARAM_INT);


echo "action=".$action."<br>";
echo "languagelab=".$languagelab."<br>";
echo "userid=".$userid."<br>";
echo "path=".$path."<br>";
echo "label=".$label."<br>";
echo "message=".$message."<br>";
echo "count=".$count."<br>";
echo "id=".$id."<br>";


if ($action == 'insert_record') {

	$save_recording = new object(); {
	$save_recording->languagelab = $languagelab;
	$save_recording->userid = $userid;
        $save_recording->groupid = $groupid;
	$save_recording->path = $path;
	$save_recording->label = $label;
	$save_recording->message = $message;
        $save_recording->parentnode = '';
	$save_recording->timecreated = time();
	$save_recording->timemodified = time();
	
	$DB->insert_record('languagelab_submissions',$save_recording);
		
		//this will be used to create grade record in languagelab_student_eval
                //Only create one record for the grade. Multiple grade records would break the system.
			if ($count == 0) { //Will only create the record once
				$save_grade = new object();
				$save_grade->languagelab = $languagelab;
				$save_grade->userid = $userid;
				$DB->insert_record('languagelab_student_eval',$save_grade);
			}
 		
	}
	
   

	return false ;
	
}

if ($action == 'update_record') {
	
	$update_recording = new object(); {
	$update_recording->id = $id;
	$update_recording->label = $label;
	$update_recording->message = $message;
	$update_recording->timemodified = time();
	
	$DB->update_record('languagelab_submissions',$update_recording);

	}
	
	return false;
	
}