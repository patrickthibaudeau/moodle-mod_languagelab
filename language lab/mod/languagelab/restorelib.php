<?php //$Id: restorelib.php,v 1.17 2006-09-18 09:13:06 moodler Exp $
    //This php script contains all the stuff to backup/restore
    //languagelab mods



    function languagelab_restore_mods($mod,$restore) {

        global $CFG,$db;

        $status = true;

        //Get record from backup_ids
        $data = backup_getid($restore->backup_unique_code,$mod->modtype,$mod->id);

        if ($data) {
            //Now get completed xmlized object   
            $info = $data->info;
          

            //Now, build the languagelab record structure
            $languagelab->course = $restore->course_id;
            $languagelab->description = backup_todb($info['MOD']['#']['DESCRIPTION']['0']['#']);
            $languagelab->timedue = backup_todb($info['MOD']['#']['TIMEDUE']['0']['#']);
            $languagelab->timeavailable = backup_todb($info['MOD']['#']['TIMEAVAILABLE']['0']['#']);
            $languagelab->grade = backup_todb($info['MOD']['#']['GRADE']['0']['#']);
            $languagelab->attempts = backup_todb($info['MOD']['#']['ATTEMPTS']['0']['#']);
            $languagelab->timemodified = backup_todb($info['MOD']['#']['TIMEMODIFIED']['0']['#']);
            $languagelab->name = backup_todb($info['MOD']['#']['NAME']['0']['#']);
            $languagelab->recording_timelimit = backup_todb($info['MOD']['#']['RECORDING_TIMELIMIT']['0']['#']);

            //The structure is equal to the db, so insert the languagelab
            $newlanguagelabid = insert_record ("languagelab",$languagelab);

            //Do some output
            if (!defined('RESTORE_SILENTLY')) {
                echo "<li>".get_string("modulename","languagelab")." \"".format_string(stripslashes($languagelab->name),true)."\"</li>";
            }
            backup_flush(300);

            if ($newlanguagelabid) {
                //We have the newid, update backup_ids
                backup_putid($restore->backup_unique_code,$mod->modtype,
                             $mod->id, $newlanguagelabid);
                //Now check if want to restore user data and do it.
                if (restore_userdata_selected($restore,'languagelab',$mod->id)) {
                    //Restore languagelab_student_eval
                    $status = LanguageLabStudentEvaluation_restore_mods ($newlanguagelabid,$info,$restore);
                    //Restore languagelab_submissioins
                    if ($status) {
                        $status = LanguageLabSubmissions_restore_mods ($newlanguagelabid,$info,$restore);
                    }
                }
            } else {
                $status = false;
            }
        } else {
            $status = false;
        }

        return $status;
    }

   //This function restores the languagelab_answers
    function LanguageLabStudentEvaluation_restore_mods($languagelab_id,$info,$restore) {

        global $CFG;

        $status = true;

        //Get the answers array
        $student_evaluations =$info['MOD']['#']['LANGUAGELABSTUDENTEVALUATIONS']['0']['#']['LANGUAGELAB_STUDENT_EVAL'];
        

        //Iterate over student_evaluations
        for($i = 0; $i < sizeof($student_evaluations); $i++) {
            $eval_info = $student_evaluations[$i];
            //traverse_xmlize($sub_info);                                                                 //Debug
            //print_object ($GLOBALS['traverse_array']);                                                  //Debug
            //$GLOBALS['traverse_array']="";                                                              //Debug

            //We'll need this later!!
            $oldid = backup_todb($eval_info['#']['ID']['0']['#']);
            $olduserid = backup_todb($eval_info['#']['USERID']['0']['#']);

            //Now, build the languagelab_ANSWERS record structure
            $student_evaluation->languagelab = $languagelab_id;
            $student_evaluation->userid = backup_todb($eval_info['#']['USERID']['0']['#']);
            $student_evaluation->correctionnotes = backup_todb($eval_info['#']['CORRECTIONNOTES']['0']['#']);
            $student_evaluation->grade = backup_todb($eval_info['#']['GRADE']['0']['#']);
            $student_evaluation->teacher = backup_todb($eval_info['#']['TEACHER']['0']['#']);
            $student_evaluation->timemarked = backup_todb($eval_info['#']['TIMEMARKED']['0']['#']);
            $student_evaluation->timecreated = backup_todb($eval_info['#']['TIMECREATED']['0']['#']);
            $student_evaluation->timemodified = backup_todb($eval_info['#']['TIMEMODIFIED']['0']['#']);

            //We have to recode the userid field
           $user = backup_getid($restore->backup_unique_code,"user",$student_evaluation->userid);
            if ($user) {
                $student_evaluation->userid = $user->new_id;
            }

            //The structure is equal to the db, so insert the languagelab_student_eval
            
            $newid = insert_record ("languagelab_student_eval",$student_evaluation);
            

            //Do some output
            if (($i+1) % 50 == 0) {
                if (!defined('RESTORE_SILENTLY')) { //RESTORE_SILENTLY
                    echo ".";
                    if (($i+1) % 1000 == 0) {
                        echo "<br />";
                    }
                }
                backup_flush(300);
            } 

            if ($newid) {
                //We have the newid, update backup_ids
                backup_putid($restore->backup_unique_code,"languagelab_student_eval",$oldid,
                             $newid);
            } else {
                $status = false;
            }
        }

        return $status;
    }

   //This function restores the languagelab_submissions
    function LanguageLabSubmissions_restore_mods($languagelab_id,$info,$restore) {

        global $CFG;

        $status = true;

        //Get the analysis array
        $submissions = $info['MOD']['#']['LANGUAGELAB_SUBMISSIONS']['0']['#']['LANGUAGELAB_SUBMISSION'];
        

        //Iterate over submissions
        for($i = 0; $i < sizeof($submissions); $i++) {
            $submit_info = $submissions[$i];
            //traverse_xmlize($sub_info);                                                                 //Debug
            //print_object ($GLOBALS['traverse_array']);                                                  //Debug
            //$GLOBALS['traverse_array']="";                                                              //Debug

            //We'll need this later!!
            $oldid = backup_todb($submit_info['#']['ID']['0']['#']);
            $olduserid = backup_todb($submit_info['#']['USERID']['0']['#']);

            //Now, build the languagelab_ANALYSIS record structure
            $submission->languagelab = $languagelab_id;
            $submission->userid = backup_todb($submit_info['#']['USERID']['0']['#']);
            $submission->groupid = backup_todb($submit_info['#']['GROUPID']['0']['#']);
            $submission->path = backup_todb($submit_info['#']['PATH']['0']['#']);
            $submission->label = backup_todb($submit_info['#']['LABEL']['0']['#']);
            $submission->message = backup_todb($submit_info['#']['MESSAGE']['0']['#']);
            $submission->parentnode = backup_todb($submit_info['#']['PARENTNODE']['0']['#']);
            $submission->timecreated = backup_todb($submit_info['#']['TIMECREATED']['0']['#']);
            $submission->timemodified = backup_todb($submit_info['#']['TIMEMODIFIED']['0']['#']);

            
            //We have to recode the userid field
            $user = backup_getid($restore->backup_unique_code,"user",$submission->userid);
            if ($user) {
                $submission->userid = $user->new_id;
            }

            //The structure is equal to the db, so insert the languagelab_analysis
            $newid = insert_record ("languagelab_submissions",$submission);
            

            //Do some output
            if (($i+1) % 50 == 0) {
                if (!defined('RESTORE_SILENTLY')) {
                    echo ".";       
                    if (($i+1) % 1000 == 0) {
                        echo "<br />";
                    }
                }
                backup_flush(300);
            }

            if ($newid) {
                //We have the newid, update backup_ids
                backup_putid($restore->backup_unique_code,"languagelab_submissions",$oldid,
                             $newid);
            } else {
                $status = false;
            }
        }

        return $status;
    }

    //Return a content decoded to support interactivities linking. Every module
    //should have its own. They are called automatically from
    //servey_decode_content_links_caller() function in each module
    //in the restore process
   function languagelab_decode_content_links ($content,$restore) {
            
        global $CFG;
            
        $result = $content;
                
        //Link to the list of serveys
                
        $searchstring='/\$@(languagelabINDEX)\*([0-9]+)@\$/';
        //We look for it
        preg_match_all($searchstring,$content,$foundset);
        //If found, then we are going to look for its new id (in backup tables)
        if ($foundset[0]) {
            //print_object($foundset);                                     //Debug
            //Iterate over foundset[2]. They are the old_ids
            foreach($foundset[2] as $old_id) {
                //We get the needed variables here (course id)
                $rec = backup_getid($restore->backup_unique_code,"course",$old_id);
                //Personalize the searchstring
                $searchstring='/\$@(languagelabINDEX)\*('.$old_id.')@\$/';
                //If it is a link to this course, update the link to its new location
                if($rec->new_id) {
                    //Now replace it
                    $result= preg_replace($searchstring,$CFG->wwwroot.'/mod/languagelab/index.php?id='.$rec->new_id,$result);
                } else { 
                    //It's a foreign link so leave it as original
                    $result= preg_replace($searchstring,$restore->original_wwwroot.'/mod/languagelab/index.php?id='.$old_id,$result);
                }
            }
        }

        //Link to servey view by moduleid

        $searchstring='/\$@(languagelabVIEWBYID)\*([0-9]+)@\$/';
        //We look for it
        preg_match_all($searchstring,$result,$foundset);
        //If found, then we are going to look for its new id (in backup tables)
        if ($foundset[0]) {
            //print_object($foundset);                                     //Debug
            //Iterate over foundset[2]. They are the old_ids
            foreach($foundset[2] as $old_id) {
                //We get the needed variables here (course_modules id)
                $rec = backup_getid($restore->backup_unique_code,"course_modules",$old_id);
                //Personalize the searchstring
                $searchstring='/\$@(languagelabVIEWBYID)\*('.$old_id.')@\$/';
                //If it is a link to this course, update the link to its new location
                if($rec->new_id) {
                    //Now replace it
                    $result= preg_replace($searchstring,$CFG->wwwroot.'/mod/languagelab/view.php?id='.$rec->new_id,$result);
                } else {
                    //It's a foreign link so leave it as original
                    $result= preg_replace($searchstring,$restore->original_wwwroot.'/mod/languagelab/view.php?id='.$old_id,$result);
                }
            }
        }

        return $result;
    }

    //This function makes all the necessary calls to xxxx_decode_content_links()
    //function in each module, passing them the desired contents to be decoded
    //from backup format to destination site/course in order to mantain inter-activities
    //working in the backup/restore process. It's called from restore_decode_content_links()
    //function in restore process
    function languagelab_decode_content_links_caller($restore) {
        global $CFG;
        $status = true;
        
        if ($languagelabs = get_records_sql ("SELECT s.id, s.intro
                                   FROM {$CFG->prefix}languagelab s
                                   WHERE s.course = $restore->course_id")) {
                                               //Iterate over each languagelab->intro
            $i = 0;   //Counter to send some output to the browser to avoid timeouts
            foreach ($languagelabs as $languagelab) {
                //Increment counter
                $i++;
                $content = $languagelab->intro;
                $result = restore_decode_content_links_worker($content,$restore);
                if ($result != $content) {
                    //Update record
                    $languagelab->intro = addslashes($result);
                    $status = update_record("languagelab",$languagelab);
                    if (debugging()) {
                        if (!defined('RESTORE_SILENTLY')) {
                            echo '<br /><hr />'.s($content).'<br />changed to<br />'.s($result).'<hr /><br />';
                        }
                    }
                }
                //Do some output
                if (($i+1) % 5 == 0) {
                    if (!defined('RESTORE_SILENTLY')) {
                        echo ".";
                        if (($i+1) % 100 == 0) {
                            echo "<br />";
                        }
                    }
                    backup_flush(300);
                }
            }
        }

        return $status;
    }

    //This function returns a log record with all the necessay transformations
    //done. It's used by restore_log_module() to restore modules log.
    function languagelab_restore_logs($restore,$log) {

        $status = false;

        //Depending of the action, we recode different things
        switch ($log->action) {
        case "add":
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                    $log->url = "view.php?id=".$log->cmid;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        case "submit":
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                    $log->url = "view.php?id=".$log->cmid;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        case "update":
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                    $log->url = "view.php?id=".$log->cmid;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        case "view form":
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                    $log->url = "view.php?id=".$log->cmid;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        case "view graph":
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                    $log->url = "view.php?id=".$log->cmid;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        case "view report":
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                    $log->url = "report.php?id=".$log->cmid;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        case "view all":
            $log->url = "index.php?id=".$log->course;
            $status = true;
            break;
        case "download":
            if ($log->cmid) {
                //Get the new_id of the module (to recode the info field)
                $mod = backup_getid($restore->backup_unique_code,$log->module,$log->info);
                if ($mod) {
                    //Rebuild the url, extracting the type (txt, xls)
                    $filetype = substr($log->url,-3);
                    $log->url = "download.php?id=".$log->cmid."&type=".$filetype;
                    $log->info = $mod->new_id;
                    $status = true;
                }
            }
            break;
        default:
            if (!defined('RESTORE_SILENTLY')) {
                echo "action (".$log->module."-".$log->action.") unknown. Not restored<br />";                 //Debug
            }
            break;
        }

        if ($status) {
            $status = $log;
        }
        return $status;
    }
    
?>
