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
session_cache_limiter('nocache'); //Needed for XML to load with IE
//header("Cache-Control: no-cache"); //Prevent caching issues with MSIE
require_once("../../config.php");
require_once("lib.php");
require_once("locallib.php");

//We need to determine if activity is available for the times chosen by teacher


$id = optional_param('activity_id', 0, PARAM_INT); // Course Module ID, or
$l  = optional_param('l', 0, PARAM_INT);  // languagelab ID

$selecteduser  = optional_param('selecteduser', 0, PARAM_INT);
$selectedelem  = optional_param('selectedelem', 0, PARAM_INT);

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

$context = get_context_instance(CONTEXT_MODULE, $cm->id);

//************************Get teacher information*****************************************
require_login($course, true, $cm); //Needed to gather proper course language used

if (!has_capability('mod/languagelab:teacherview', $context, null, true)){
    error('You don\'t have the privilege to access to this page');
}
else
{
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
    //************************Get language activity params*******************************
    $languagelab_params = $DB->get_record('languagelab',array('id' => $languagelab->id));

    $now =time();
    $available = $languagelab_params->timeavailable < $now && ($now < $languagelab_params->timedue || !$languagelab_params->timedue);
    //************************End language activity params******************************

    //************************Get master track*******************************
    if (isset($languagelab->master_track)){
        //check to find out if MP3.
        if (strpos($languagelab->master_track, '.mp3') == false) {
            $mastertrack = $languagelab->master_track;
        } else {
            $mastertrack = moodle_url::make_pluginfile_url($context->id,'mod_languagelab','mastertrack',0,'/',$languagelab->master_track);
        }

    } else {
        $mastertrack = '';
    }
    //************************End Get master track*******************************

    //****************Get students *************************************
    $students = get_enrolled_users($context,'mod/languagelab:studentview');
    //**************************************************************************


    // if attempts is true, it must equal 0
    if ($languagelab_params->attempts == 1) {
            $attempts = 0;
    } else {
            $attempts = 1;
    }
    //**********JSON OUTPUT******************

    $json = array();
    $checksumData = array();
    foreach ($students as $student) {
        
        $checksumData[] = array('id'=>$student->id, 'timemodified'=>$student->timemodified);
        
        $student_grade = $DB->get_record('languagelab_student_eval',array('userid' => $student->id,'languagelab' => $languagelab->id));
        
        if ($student_grade == null || $student_grade->grade == null)
        {
            $gradeFormat = '';
            $grade = 0;
            $gradePrivateNote = '';
        }
        else
        {
            $gradeFormat = ' ('.$student_grade->grade.'%)';
            $grade = $student_grade->grade;
            $gradePrivateNote = $student_grade->correctionnotes;
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
                                    
        $elem = new stdClass();

        $elem->data = new stdClass();
        $elem->data->title = fullname($student).$gradeFormat;
        $elem->data->attr = new stdClass();
        $elem->data->attr->href = '#';
        $elem->data->icon = 'user';

        $elem->metadata = new stdClass();
        $elem->metadata->type = "user";
        $elem->metadata->title = $elem->data->title;
        $elem->metadata->grade = $grade;
        $elem->metadata->author = fullname($student);
        $elem->metadata->portrait = $studentpicture;
        $elem->metadata->grade = $grade;
        $elem->metadata->gradePrivateNote = $gradePrivateNote;
        $elem->metadata->studentid = $studentinfo->id;
        $elem->metadata->downloadName = format_name_download_mp3(fullname($student).'_'.$languagelab->name);
        
        if($selectedelem == 0 && $selecteduser == $studentinfo->id)
        {
            $elem->metadata->selected = true;
        }
        else
        {
            $elem->metadata->selected = false;
        }

        $elem->children = array();
        
        $studentrecordings = $DB->get_records('languagelab_submissions',array('userid' => $student->id, 'languagelab' => $languagelab->id));
        
        foreach ($studentrecordings as $recording) {

            $checksumData[] = array('id'=>$recording->id, 'timemodified'=>$recording->timemodified);
            
            $rec = new stdClass();

            $rec->data = new stdClass();
            $rec->data->title = str_replace('@@',"'",$recording->label);//.'<span class="delRecord>">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
            $rec->data->attr = new stdClass();
            $rec->data->attr->href = '#';
            $rec->data->icon = 'record';

            $rec->metadata = new stdClass();
            $rec->metadata->type = "record";
            $rec->metadata->title = $rec->data->title;
            $rec->metadata->recURI = $recording->path;
            $rec->metadata->mastertrack = $mastertrack;
            $rec->metadata->lastUpdate = formatTimeSince($recording->timemodified);
            $rec->metadata->author = fullname($student);
            $rec->metadata->portrait = $studentpicture;
            $rec->metadata->tMessage = $recording->message;
            $rec->metadata->recordingid = $recording->id;
            $rec->metadata->grade = $grade;
            $rec->metadata->gradePrivateNote = $gradePrivateNote;
            $rec->metadata->studentid = $studentinfo->id;
            $rec->metadata->downloadName = format_name_download_mp3(fullname($student).'_'.$rec->data->title.'_'.$recording->id);
            
            if($selectedelem != 0 && $selectedelem == $recording->id)
            {
                $rec->metadata->selected = true;
            }
            else
            {
                $rec->metadata->selected = false;
            }

            $parentnode = array("parentnode" =>$recording->path, "languagelab" => $languagelab->id);
            $rec->children = array();
            $childnodes = $DB->get_records('languagelab_submissions',$parentnode);
            foreach ($childnodes as $childnode) {
                
                $checksumData[] = array('id'=>$childnode->id, 'timemodified'=>$childnode->timemodified);
                
                $teacher = $DB->get_record("user", array("id" => $childnode->userid));
                $teacherpictureurl = $OUTPUT->user_picture($teacher, array('courseid' => $course->id, 'link' => false)); //$CFG->wwwroot."/user/pix.php/".$teacher->id."/f2.jpg";
                //create an array from the image tag
                $newteacherpictureurl = explode(' ',$teacherpictureurl);
                //Get the link info from array row 1 and remove src="
                $teacherpicture = str_replace('src="', '', $newteacherpictureurl[1]);
                //remove last double quotation marks;
                $teacherpicture = str_replace('"', '', $teacherpicture);

                $child = new stdClass();
                $child->data = new stdClass();
                $child->data->title = str_replace('@@',"'",$childnode->label);//.'<span class="delRecord>">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                $child->data->attr = new stdClass();
                $child->data->attr->href = '#';
                $child->data->icon = 'feedback';

                $child->metadata = new stdClass();
                $child->metadata->type = "feedback";
                $child->metadata->title = $child->data->title;
                $child->metadata->recURI = $childnode->path;
                $child->metadata->lastUpdate = formatTimeSince($childnode->timemodified);
                $child->metadata->author = fullname($teacher);
                $child->metadata->portrait = $teacherpicture;
                $child->metadata->tMessage = $childnode->message;
                $child->metadata->recordingid = $childnode->id;
                $child->metadata->grade = $grade;
                $child->metadata->gradePrivateNote = $gradePrivateNote;
                $child->metadata->studentid = $studentinfo->id;
                $child->metadata->downloadName = format_name_download_mp3(fullname($teacher).'_'.$child->data->title.'_'.$recording->id);
                
                if($selectedelem != 0 && $selectedelem == $childnode->id)
                {
                    $child->metadata->selected = true;
                }
                else
                {
                    $child->metadata->selected = false;
                }
            
                $rec->children[] = $child;
            }
            $elem->children[] = $rec;
        }
        
        $json[] = $elem;
    }
    
    $obj = new stdClass();
    $obj->checksum = md5(json_encode($checksumData));
    $obj->json = $json;
    echo json_encode($obj);
}
?>