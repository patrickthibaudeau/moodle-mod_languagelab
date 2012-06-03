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
//**@Flash programming: Nicolas Bretin, Guillaume Bourbonniere          **
//**@Moodle integration: Patrick Thibaudeau, Nicolas Bretin             **
//************************************************************************
//************************************************************************

defined('MOODLE_INTERNAL') || die;
require_once ($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->libdir.'/filelib.php');



class mod_languagelab_mod_form extends moodleform_mod {
/**
     * @global moodle_database $DB
     * @global core_renderer $OUTPUT
     * @global moodle_page $PAGE.
     */

    function definition() {
        global $CFG, $USER, $DB, $PAGE;
        
	
        $PAGE->requires->js('/mod/languagelab/js/jquery-1.7.2.min.js', true);
        $PAGE->requires->js('/mod/languagelab/js/jquery.ui/jquery-ui-1.8.20.custom.min.js', true);
        $PAGE->requires->js('/mod/languagelab/js/jquery.jstree/jquery.jstree.js', true);
        $PAGE->requires->js('/mod/languagelab/js/flash_detect_min.js', true);
        $PAGE->requires->js('/mod/languagelab/js/languagelab.js', true);

        $PAGE->requires->css('/mod/languagelab/js/jquery.ui/custom-theme/jquery-ui-1.8.20.custom.css');
        $PAGE->requires->css('/mod/languagelab/style.css');
        
        $newRecording = 'true';
        //flash recorder component
        if (isset($_REQUEST['update'])){
            if ($_REQUEST['update'] <> 0){
                $newRecording = 'false';
                $languagelab = $DB->get_record('languagelab',array('id'=>$this->current->instance));
            }
        }
        //Code needed to add recorder
        if(isset($languagelab->master_track_recording)) {
            $recordingname = $languagelab->master_track_recording;
        } else {
            $recordingname = '';
        }


        //echo $recordingname;
        //find out if students have submitted work
        if (isset($languagelab->id)) {
            $submitted_recordings = $DB->count_records('languagelab_submissions', array('languagelab' => $languagelab->id));
                if ($submitted_recordings > 0) {
                    $record_button = 'false';
                } else {
                    $record_button = 'true';
                }
        } else {
            $record_button = 'true';
            $submitted_recordings = 0;
        }

        $idPlayer = 'playerRecorderMastertrack';
        $filePrefix = $CFG->languagelab_folder.'/'.$CFG->languagelab_prefix.'mastertrack_';
        
        
        if( $newRecording=='false' && $submitted_recordings == 0)
        {
            $buttonsPlayer = '<button id="loadPreviousMastertrack">'.get_string('load_prev_master', 'languagelab').'</button>';
            $buttonsPlayer .= '<button id="newMastertrack">'.get_string('newMastertrack', 'languagelab').'</button>';
        }
        else
        {
            $buttonsPlayer = '';
        }
        
        $titlePlayerOptions = get_string('titlePlayerOptions', 'languagelab');
        $playeroptionstxt1 = get_string('playeroptionstxt1', 'languagelab');
        $playeroptionstxt2 = get_string('playeroptionstxt2', 'languagelab', '<img src="'.$CFG->wwwroot.'/mod/languagelab/pix/privacy-ico.png"/>');
        $playeroptionstxt3 = get_string('playeroptionstxt3', 'languagelab', '<img src="'.$CFG->wwwroot.'/mod/languagelab/pix/allow-ico.png"/>');
        $playeroptionstxt4 = get_string('playeroptionstxt4', 'languagelab', '<img src="'.$CFG->wwwroot.'/mod/languagelab/pix/check-ico.png"/>');
        $playeroptionstxt5 = get_string('playeroptionstxt5', 'languagelab');
        $playeroptionstxt6 = get_string('playeroptionstxt6', 'languagelab');
        $playeroptionsBtnOk = get_string('playeroptionsBtnOk', 'languagelab');
        //This is the Flash player required for recording the master track
        $recorder = <<<HERE
        
        <div id="dialogInfo" title="">
        </div>
        
        <script type="text/javascript">
            var playerRecorders = [];
            var playerOptions;
            var userLiveURI;
            var userRecordURI;
            var rtmpserver = "rtmp://$CFG->languagelab_red5server/oflaDemo";
            var files_prefix = "$filePrefix";
            var newRecording = $newRecording;
            var fileURI = "$recordingname";
            var existingMastertrackURI = "$recordingname";
            var playeroptionsBtnOk = "$playeroptionsBtnOk";
            var submitted_recordings = $submitted_recordings;
        </script>
        
        <div id="divPlayerOptions" title="$titlePlayerOptions" style="position:absolute;top:-1000px;">
            <div id="divPlayerOptionsText" style="width: 400px;">
                $playeroptionstxt1
                <ol>
                    <li>$playeroptionstxt2</li>
                    <li>$playeroptionstxt3</li>
                    <li>$playeroptionstxt4</li>
                    <li>$playeroptionstxt5</li>
                    <li>$playeroptionstxt6</li>
                </ol>
            </div>
            <div id="divPlayerOptionsObj" style="text-align:center;">
                <object type="application/x-shockwave-flash" data="$CFG->wwwroot/mod/languagelab/flash/PlayerOptions.swf" width="250" height="160" name="playerOptions" id="playerOptions">
                    <param name="allowScriptAccess" value="always" />
                    <param name="allowFullScreen" value="true" />
                    <param name="wmode" value="window">
                    <param name="movie" value="$CFG->wwwroot/mod/languagelab/flash/PlayerOptions.swf" />
                    <param name="quality" value="high" />
                </object>
            </div>
            <div style="clear:both;"></div>
        </div>               
        
        <object type="application/x-shockwave-flash" data="$CFG->wwwroot/mod/languagelab/flash/PlayerRecorder.swf?idHTML=$idPlayer" width="350" height="45" name="$idPlayer" id="$idPlayer" style="outline: none;" >
            <param name="movie" value="$CFG->wwwroot/mod/languagelab/flash/PlayerRecorder.swf" />
            <param name="allowScriptAccess" value="always" />
            <param name="allowFullScreen" value="true" />
            <param name="wmode" value="transparent"/> 
            <param name="quality" value="high" />
        </object>
        $buttonsPlayer
HERE;
        
	$editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES);
        $mform =& $this->_form;
        $config = get_config('languagelab');
        $mform->addElement('header','general',get_string('general','languagelab'));
	$mform->addElement('text', 'name', get_string('name', 'languagelab'), array('size'=>'45')); //Name to be used 
	$mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('editor', 'content', get_string('description', 'languagelab'),null, $editoroptions);
        $mform->addElement('static','txt','','<div id="descrLabLang"></div>');
        $mform->addElement('static','master_track_recorder',get_string('master_track_recorder','languagelab'),$recorder);
        $mform->addHelpButton('master_track_recorder', 'master_track_recorder', 'languagelab');
        //This actual file name
        $mform->addElement('hidden','master_track_recording', $recordingname);
        if ($submitted_recordings == 0) {
            $mform->addElement('filepicker', 'master_track', get_string('master_track','languagelab'), null, array('subdirs' => 0, 'maxfiles' => 1, 'accepted_types' => array('*.mp3') ));
            $mform->addHelpButton('master_track', 'master_track', 'languagelab');
            $mform->addElement('checkbox','use_mp3',get_string('use_mp3','languagelab'));
            $mform->addHelpButton('use_mp3', 'use_mp3', 'languagelab');
            //$mform->setDefault('use_mp3', true);
        }
        $mform->addElement('hidden','submitted_recordings', $submitted_recordings);
        $mform->addElement('hidden','master_track_used');
        $mform->addElement('static','master_track_file',get_string('master_track_file','languagelab'),'');
        $mform->addElement('advcheckbox','attempts',get_string('attempts','languagelab'),null);
        $mform->addHelpButton('attempts', 'attempts', 'languagelab');
	//$mform->addElement('text','recording_timelimit',get_string('recording_timelimit','languagelab'));
        //$mform->setDefault('recording_timelimit', 0);
        
        
        $mform->addElement('date_time_selector', 'timeavailable', get_string('availabledate', 'languagelab'), array('optional'=>true, 'value' => 0));
        $mform->setDefault('timeavailable', time());
        $mform->addElement('date_time_selector', 'timedue', get_string('duedate', 'languagelab'), array('optional'=>true, 'value' => 0));
        $mform->setDefault('timedue', time()+7*24*3600);
        $ynoptions = array( 0 => get_string('no'), 1 => get_string('yes'));

        $mform->addElement('header','general',get_string('advanced','languagelab'));
        //$mform->addElement('checkbox','video',get_string('use_video','languagelab'));
        //$mform->addHelpButton('video', 'video', 'languagelab');
        //$mform->setDefault('video', false);
        
        $mform->addElement('static','video_disabled','Video not available for now. Please check for new version soon...');
        
        //$mform->addElement('select', 'group_type', get_string('group_type','languagelab'), array(0 => get_string('select_group_type','languagelab') , 1 => get_string('async','languagelab') , 2 => get_string('dialogue','languagelab')));
        //$mform->addHelpButton('group_type', 'group_type', 'languagelab');
        $mform->addElement('checkbox','use_grade_book',get_string('use_grade_book','languagelab'));
        $mform->setDefault('use_grade_book', false);
        $mform->addHelpButton('use_grade_book', 'use_grade_book', 'languagelab');
        $mform->addElement('modgrade', 'grade', get_string('grade'));
        $mform->disabledIf('grade', 'use_grade_book');
        $mform->setDefault('grade', 0);

        /*
        $features = new stdClass;
        $features->groups = true;
        $features->groupings = true;
        $features->groupmembersonly = true;
        $features->idnumber = false;
         *
         */
        $this->standard_coursemodule_elements();
        
        $this->add_action_buttons();
                
    }
    function data_preprocessing(&$default_values) {

        global $CFG,$DB;

         $editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'context'=>$this->context);
                  
        if ($this->current->instance) {
            $languagelab = $DB->get_record('languagelab',array('id'=>$this->current->instance));
            $draftitemid = file_get_submitted_draft_itemid('content');
            $default_values['content']['format'] = $languagelab->contentformat;
            $default_values['content']['text']   = file_prepare_draft_area($draftitemid, $this->context->id, 'mod_languagelab', 'content',$languagelab->id , $editoroptions, $languagelab->description);
            $default_values['content']['itemid'] = $draftitemid;
            $default_values['master_track_file'] = $languagelab->master_track;
            $default_values['master_track_used'] = $languagelab->master_track;
            
            
        }
    }

}
?>