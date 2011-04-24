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

/// (replace languagelab with the name of your module and delete this line)


defined('MOODLE_INTERNAL') || die();

/**
 * List of features supported in Tab display
 * @uses FEATURE_IDNUMBER
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_GROUPMEMBERSONLY
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool|null True if module supports feature, false if not, null if doesn't know
 */
function languagelab_supports($feature) {
    switch($feature) {
        case FEATURE_IDNUMBER:                return false;
        case FEATURE_GROUPS:                  return true;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_GROUPMEMBERSONLY:        return false;
        case FEATURE_MOD_INTRO:               return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_GRADE_HAS_GRADE:         return true;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_ASSIGNMENT;
        case FEATURE_BACKUP_MOODLE2:          return true;

        default: return null;
    }
}

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod.html) this function 
 * will create a new instance and return the id number 
 * of the new instance.
 *
 * @param object $instance An object from the form in mod.html
 * @return int The id of the newly inserted languagelab record
 **/
function languagelab_add_instance($languagelab) {
	global $DB, $CFG;

    require_once("$CFG->libdir/resourcelib.php");
    
    $draftitemid = $languagelab->content['itemid'];
    $cmid = $languagelab->coursemodule;
    $languagelab->contentformat = $languagelab->content['format'];
    $languagelab->description = $languagelab->content['text'];
    $languagelab->timemodified = time();
    
      
    $languagelab->id = $DB->insert_record("languagelab", $languagelab);
    
    //only use grade book when checked
    if ($languagelab->use_grade_book ==  true) {
        languagelab_grade_item_update($languagelab);
    }
        // we need to use context now, so we need to make sure all needed info is already in db
        $DB->set_field('course_modules', 'instance', $languagelab->id, array('id'=>$cmid));

        $context = get_context_instance(CONTEXT_MODULE, $cmid);
        $editoroptions = array('subdirs'=>1, 'maxbytes'=>$CFG->maxbytes, 'maxfiles'=>-1, 'changeformat'=>1, 'context'=>$context, 'noclean'=>1, 'trusttext'=>true);
        if ($draftitemid) {
            $languagelab->description = file_save_draft_area_files($draftitemid, $context->id, 'mod_languagelab', 'content', $languagelab->id, $editoroptions, $languagelab->description);
            $DB->update_record('languagelab', $languagelab);
        }
    
    return $languagelab->id ;

     
}

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod.html) this function 
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function languagelab_update_instance($languagelab) {
	global $CFG, $DB;

    $cmid = $languagelab->coursemodule;
    $draftitemid = $languagelab->content['itemid'];
    $languagelab->contentformat = $languagelab->content['format'];
    $languagelab->description = $languagelab->content['text'];
    $languagelab->timemodified = time();
    $languagelab->id = $languagelab->instance;
    
    
    # May have to add extra stuff in here #
    $temp = $DB->update_record("languagelab", $languagelab);

    //only use grade book when checked
    if ($languagelab->use_grade_book ==  true) {
    // update grade item definition
    languagelab_grade_item_update($languagelab);

    // update grades - TODO: do it only when grading style changes
    languagelab_update_grades($languagelab, 0, false);
    }
    
    $context = get_context_instance(CONTEXT_MODULE, $cmid);
    $editoroptions = array('subdirs'=>1, 'maxbytes'=>$CFG->maxbytes, 'maxfiles'=>-1, 'changeformat'=>1, 'context'=>$context, 'noclean'=>1, 'trusttext'=>true);
        if ($draftitemid) {
            $languagelab->description = file_save_draft_area_files($draftitemid, $context->id, 'mod_languagelab', 'content', $languagelab->id, $editoroptions, $languagelab->description);
            $DB->update_record('languagelab', $languagelab);
        }

    return true;
     
}

/**
 * Given an ID of an instance of this module, 
 * this function will permanently delete the instance 
 * and any data that depends on it. 
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 **/
function languagelab_delete_instance($id) {
	global $DB;

    if (!$languagelab = $DB->get_record("languagelab", array("id" => $id))) {
        return false;
    }

    $result = true;

    # Delete any dependent records here #
    $DB->delete_records("languagelab_student_eval", array("languagelab" => $languagelab->id));
    $DB->delete_records("languagelab_submissions", array("languagelab" => $languagelab->id));

    if (! $DB->delete_records("languagelab", array("id" => $languagelab->id))) {
        $result = false;
    }
    languagelab_grade_item_delete($languagelab);

    return $result;
}

/**
 * Return a small object with summary information about what a 
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 **/
function languagelab_user_outline($course, $user, $mod, $languagelab) {
 
    
   
    $return = new stdClass;
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Print a detailed representation of what a user has done with 
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function languagelab_user_complete($course, $user, $mod, $languagelab) {
    return true;

     $submissions = $DB->get_records('languagelab_submissions',array('languagelab' => $languagelab->id, 'userid' => $user->id));

}

/**
 * Given a course and a time, this module should find recent activity 
 * that has occurred in languagelab activities and print it out. 
 * Return true if there was output, or false is there was none. 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function languagelab_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;  //  True if anything was printed, otherwise false
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such 
 * as sending out mail, toggling flags etc ... 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function languagelab_cron () {
    return true;
}

/**
 * Must return an array of grades for a given instance of this module, 
 * indexed by user.  It also returns a maximum allowed grade.
 * 
 * Example:
 *    $return->grades = array of grades;
 *    $return->maxgrade = maximum allowed grade;
 *
 *    return $return;
 *
 * @param int $languagelabid ID of an instance of this module
 * @return mixed Null or object with an array of grades and with the maximum grade
 **/
function languagelab_get_user_grades($languagelab, $userid=0) {
    global $CFG, $DB;

    $user = $userid ? "AND u.id = $userid" : "";
    $fuser = $userid ? "AND uu.id = $userid" : "";

               $sql = "SELECT u.id, u.id AS userid, AVG(g.grade) AS rawgrade
                      FROM {user} u, {languagelab_student_eval} g
                     WHERE u.id = g.userid AND g.languagelab = $languagelab->id
                           $user
                  GROUP BY u.id";

    return $DB->get_records_sql($sql);
}

/**
 * Update grades in central gradebook
 *
 */
function languagelab_update_grades($languagelab=null, $userid=0, $nullifnone=true) {
    global $CFG, $DB;
    if (!function_exists('grade_update')) { //workaround for buggy PHP versions
        require_once($CFG->libdir.'/gradelib.php');
    }

    if ($languagelab != null) {
        if ($grades = languagelab_get_user_grades($languagelab, $userid)) {
            languagelab_grade_item_update($languagelab, $grades);

        } else if ($userid and $nullifnone) {
            $grade = new object();
            $grade->userid   = $userid;
            $grade->rawgrade = NULL;
            languagelab_grade_item_update($languagelab, $grade);

        } else {
            languagelab_grade_item_update($languagelab);
        }

    } else {
        $sql = "SELECT l.*, cm.idnumber as cmidnumber, l.course as courseid
                  FROM {languagelab} l, {course_modules} cm, {modules} m
                 WHERE m.name='languagelab' AND m.id=cm.module AND cm.instance=l.id";
        if ($rs = $DB->get_recordset_sql($sql)) {
            while ($languagelab = rs_fetch_next_record($rs)) {
                if ($languagelab->grade != 0) {
                    languagelab_update_grades($languagelab, 0, false);
                } else {
                    languagelab_grade_item_update($languagelab);
                }
            }
            rs_close($rs);
        }
    }
}

/**
 * Create grade item for given lesson
 *
 * @param object $lesson object with extra cmidnumber
 * @param mixed optional array/object of grade(s); 'reset' means reset grades in gradebook
 * @return int 0 if ok, error code otherwise
 */
function languagelab_grade_item_update($languagelab, $grades=NULL) {
    global $CFG;
    
    if (!function_exists('grade_update')) { //workaround for buggy PHP versions
        require_once($CFG->libdir.'/gradelib.php');
    }

    if (array_key_exists('cmidnumber', $languagelab)) { //it may not be always present
        echo 'array key exits';
        $params = array('itemname'=>$languagelab->name, 'idnumber'=>$languagelab->cmidnumber);
    } else {
        $params = array('itemname'=>$languagelab->name);
      // echo 'array key does not exits '.$languagelab->name;
    }

    if ($languagelab->grade > 0) {
        $params['gradetype']  = GRADE_TYPE_VALUE;
        $params['grademax']   = $languagelab->grade;
        $params['grademin']   = 0;

    } else {
        $params['gradetype']  = GRADE_TYPE_NONE;
    }

    if ($grades  === 'reset') {
        $params['reset'] = true;
        $grades = NULL;
    } else if (!empty($grades)) {
        // Need to calculate raw grade (Note: $grades has many forms)
        if (is_object($grades)) {
            $grades = array($grades->userid => $grades);
        } else if (array_key_exists('userid', $grades)) {
            $grades = array($grades['userid'] => $grades);
        }
        foreach ($grades as $key => $grade) {
            if (!is_array($grade)) {
                $grades[$key] = $grade = (array) $grade;
            }
            $grades[$key]['rawgrade'] = ($grade['rawgrade'] * $languagelab->grade / 100);
        }
    }
    
    return grade_update('mod/languagelab', $languagelab->course, 'mod', 'languagelab', $languagelab->id, 0, $grades, $params);
}

/**
 * Delete grade item for given lesson
 *
 * @param object $lesson object
 * @return object lesson
 */
function languagelab_grade_item_delete($languagelab) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    return grade_update('mod/languagelab', $languagelab->course, 'mod', 'languagelab', $languagelab->id, 0, NULL, array('deleted'=>1));
}

/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of languagelab. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $languagelabid ID of an instance of this module
 * @return mixed boolean/array of students
 **/
function languagelab_get_participants($languagelabid) {
    return false;
}

/**
 * This function returns if a scale is being used by one languagelab
 * it it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $languagelabid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 **/
function languagelab_scale_used ($languagelabid,$scaleid) {
    global $DB;

    $return = false;

    //$rec = $DB->get_record("newmodule", array("id" => "$newmoduleid", "scale" => "-$scaleid"));
    //
    //if (!empty($rec) && !empty($scaleid)) {
    //    $return = true;
    //}

    return $return;
}

/**
 * Checks if scale is being used by any instance of newmodule.
 * This function was added in 1.9
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any newmodule
 */
function languagelab_scale_used_anywhere($scaleid) {
    global $DB;

    if ($scaleid and $DB->record_exists('languagelab', 'grade', -$scaleid)) {
        return true;
    } else {
        return false;
    }
}

//Needed for ajax to get languagelabid 
function get_languagelab_id($languagelab) {
    $languagelabid = $languagelab;
    return $languagelabid;
}

function languagelab_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload) {
    global $CFG, $DB;
    require_once("$CFG->libdir/resourcelib.php");

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    require_course_login($course, true, $cm);
    

    if ($filearea !== 'content') {
        // intro is handled automatically in pluginfile.php
        return false;
    }

    //Get languagelab-> id from file_rewrite_pluginfile_urls ***IMPORTANT** Otherwisefiles won't display!!!!
    $languagelabid = (int)array_shift($args);

    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = "/$context->id/mod_languagelab/$filearea/$languagelabid/$relativepath";
    if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
        $languagelab = $DB->get_record('languagelab', array('id'=>$cm->instance), 'id, legacyfiles', MUST_EXIST);
        if ($languagelab->legacyfiles != RESOURCELIB_LEGACYFILES_ACTIVE) {
            return false;
        }
        if (!$file = resourcelib_try_file_migration('/'.$relativepath, $cm->id, $cm->course, 'mod_page', 'content', 0)) {
            return false;
        }
        //file migrate - update flag
        $languagelab->legacyfileslast = time();
        $DB->update_record('languagelab', $languagelab);
    }

    // finally send the file
    send_stored_file($file, 86400, 0, $forcedownload);
}
/**
 * Execute post-uninstall custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function languagelab_uninstall() {
    return true;
}


//////////////////////////////////////////////////////////////////////////////////////
/// Any other languagelab functions go here.  Each of them must have a name that 
/// starts with languagelab_


?>
