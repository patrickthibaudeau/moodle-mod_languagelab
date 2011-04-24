<?php //$Id: backuplib.php,v 1.4 2006/01/13 03:45:30 mjollnir_ Exp $
    //This php script contains all the stuff to backup/restore
    //languagelab mods

    //This is the "graphical" structure of the languagelab mod:
    //
    //                       languagelab
    //                     (CL,pk->id)
    //
    // Meaning: pk->primary key field of the languagelable
    //          fk->foreign key to link with parent
    //          nt->nested field (recursive data)
    //          CL->course level info
    //          UL->user level info
    //          files->languagelable may have files)
    //
    //-----------------------------------------------------------

    //This function executes all the backup procedure about this mod
        function languagelab_backup_mods($bf,$preferences) {

        global $CFG;

        $status = true;

        //Iterate over languagelab table
        $languagelabs = get_records ("languagelab","course",$preferences->backup_course,"id");
        if ($languagelabs) {
            foreach ($languagelabs as $languagelab) {
                if (backup_mod_selected($preferences,'languagelab',$languagelab->id)) {
                    $status = languagelab_backup_one_mod($bf,$preferences,$languagelab);
                }
            }
        }
        return $status;
    }
    
    function languagelab_backup_one_mod($bf,$preferences,$languagelab) {
        
        $status = true;
        
        if (is_numeric($languagelab)) {
            $languagelab = get_record('languagelab','id',$languagelab);
        }
        
        //Start mod
        fwrite ($bf,start_tag("MOD",3,true));
        //Print choice data
        fwrite ($bf,full_tag("ID",4,false,$languagelab->id));
        fwrite ($bf,full_tag("MODTYPE",4,false,"languagelab"));
        fwrite ($bf,full_tag("COURSE",4,false,$languagelab->course));
        fwrite ($bf,full_tag("DESCRIPTION",4,false,$languagelab->description));
        fwrite ($bf,full_tag("TIMEDUE",4,false,$languagelab->timedue));
        fwrite ($bf,full_tag("TIMEAVAILABLE",4,false,$languagelab->timeavailable));
        fwrite ($bf,full_tag("GRADE",4,false,$languagelab->grade));
        fwrite ($bf,full_tag("ATTEMPTS",4,false,$languagelab->attempts));
        fwrite ($bf,full_tag("TIMEMODIFIED",4,false,$languagelab->timemodified));
        fwrite ($bf,full_tag("NAME",4,false,$languagelab->name));
        fwrite ($bf,full_tag("RECORDING_TIMELIMIT",4,false,$languagelab->recording_timelimit));
        
        //if we've selected to backup users info, then execute backup_languagelab_answers and
        //backup_languagelab_analysis
        if (backup_userdata_selected($preferences,'languagelab',$languagelab->id)) {
            $status = backup_LanguageLabSubmissions($bf,$preferences,$languagelab->id);
            $status = backup_LanguageLabStudentEvaluation($bf,$preferences,$languagelab->id);
        }
        //End mod
        $status =fwrite ($bf,end_tag("MOD",3,true));
        return $status;
    }


	function backup_LanguageLabStudentEvaluation ($bf,$preferences,$languagelab) {

        global $CFG;

        $status = true;

        $languagelab_student_eval = get_records("languagelab_student_eval","languagelab",$languagelab,"id,languagelab,userid,correctionnotes,grade,teacher,timemarked,timecreated,timemodified");
        //If there are evaluations
        if ($languagelab_student_eval) {
            //Write start tag
            $status =fwrite ($bf,start_tag("LanguageLabStudentEvaluations",4,true));
            //Iterate over each answer
            foreach ($languagelab_student_eval as $languagelab_opt) {
                //Start option
                $status =fwrite ($bf,start_tag("languagelab_student_eval",5,true));
                //Print option contents
                fwrite ($bf,full_tag("ID",6,false,$languagelab_opt->id));
                fwrite ($bf,full_tag("LANGUAGELAB",6,false,$languagelab_opt->languagelab));
                fwrite ($bf,full_tag("USERID",6,false,$languagelab_opt->userid));
				fwrite ($bf,full_tag("CORRECTIONNOTES",6,false,$languagelab_opt->correctionnotes));
				fwrite ($bf,full_tag("GRADE",6,false,$languagelab_opt->grade));
				fwrite ($bf,full_tag("TEACHER",6,false,$languagelab_opt->teacher));
				fwrite ($bf,full_tag("TIMEMARKED",6,false,$languagelab_opt->timemarked));
				fwrite ($bf,full_tag("TIMECREATED",6,false,$languagelab_opt->timecreated));
                fwrite ($bf,full_tag("TIMEMODIFIED",6,false,$languagelab_opt->timemodified));
                //End answer
                $status =fwrite ($bf,end_tag("languagelab_student_eval",5,true));
            }

            //Write end tag
            $status =fwrite ($bf,end_tag("LanguageLabStudentEvaluations",4,true));
        }
        return $status;
    }
    

   
   function backup_LanguageLabSubmissions ($bf,$preferences,$languagelab) {

        global $CFG;

        $status = true;

        $languagelab_submissions = get_records("languagelab_submissions","languagelab",$languagelab,"id,languagelab,userid,groupid,path,label,message,parentnode,timecreated,timemodified");
        //If there are evaluations
        if ($languagelab_submissions) {
            //Write start tag
            $status =fwrite ($bf,start_tag("languagelab_submissions",4,true));
            //Iterate over each answer
            foreach ($languagelab_submissions as $languagelab_submission) {
                //Start option
                $status =fwrite ($bf,start_tag("languagelab_SUBMISSION",5,true));
                //Print option contents
                fwrite ($bf,full_tag("ID",6,false,$languagelab_submission->id));
                fwrite ($bf,full_tag("LANGUAGELAB",6,false,$languagelab_submission->languagelab));
                fwrite ($bf,full_tag("USERID",6,false,$languagelab_submission->userid));
				fwrite ($bf,full_tag("GROUPID",6,false,$languagelab_submission->groupid));
				fwrite ($bf,full_tag("PATH",6,false,$languagelab_submission->path));
				fwrite ($bf,full_tag("LABEL",6,false,$languagelab_submission->label));
				fwrite ($bf,full_tag("MESSAGE",6,false,$languagelab_submission->message));
				fwrite ($bf,full_tag("PARENTNODE",6,false,$languagelab_submission->parentnode));
				fwrite ($bf,full_tag("TIMECREATED",6,false,$languagelab_submission->timecreated));
                fwrite ($bf,full_tag("TIMEMODIFIED",6,false,$languagelab_submission->timemodified));
                //End answer
                $status =fwrite ($bf,end_tag("languagelab_SUBMISSION",5,true));
            }
            //start languagelab_submissions backup
			
            //Write end tag
            $status =fwrite ($bf,end_tag("languagelab_submissions",4,true));
        }
        return $status;
    }

    ////Return an array of info (name,value)
    function languagelab_check_backup_mods($course,$user_data=false,$backup_unique_code,$instances=null) {
        if (!empty($instances) && is_array($instances) && count($instances)) {
            $info = array();
            foreach ($instances as $id => $instance) {
                $info += languagelab_check_backup_mods_instances($instance,$backup_unique_code);
            }
            return $info;
        }

         //First the course data
         $info[0][0] = get_string("modulenameplural","languagelab");
         $info[0][1] = count_records("languagelab", "course", "$course");
         return $info;
    }

    ////Return an array of info (name,value)
    function languagelab_check_backup_mods_instances($instance,$backup_unique_code) {
         //First the course data
        $info[$instance->id.'0'][0] = '<b>'.$instance->name.'</b>';
        $info[$instance->id.'0'][1] = '';
        return $info;
    }

?>

