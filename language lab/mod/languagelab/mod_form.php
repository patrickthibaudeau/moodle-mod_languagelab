<?php
//***********************************************************
//**               LANGUAGE LAB MODULE 1                   **
//***********************************************************
//**@package languagelab                                   **
//**@Institution: Campus Saint-Jean, University of Alberta **
//**@authors : Patrick Thibaudeau, Guillaume Bourbonni?re  **
//**@version $Id: version.php,v 1.0 2009/05/25 7:33:00    **
//**@Moodle integration: Patrick Thibaudeau                **
//**@Flash programming: Guillaume Bourbonni?re             **
//**@CSS Developement: Brian Neeland                       **
//***********************************************************
//***********************************************************
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
        global $CFG, $USER, $DB;
        
        //flash recorder component
        if (isset($_REQUEST['update'])){
            if ($_REQUEST['update'] <> 0){
                $languagelab = $DB->get_record('languagelab',array('id'=>$this->current->instance));
            }
        }
        //Code needed to add recorder
        if(isset($languagelab->master_track_recording)) {
            $recordingname = $languagelab->master_track_recording;
        } else {
            $recordingname = $CFG->languagelab_prefix.'_mastertrack_'.time();
        }


        //echo $recordingname;
        //find out if students have submitted work
        $submitted_recordings = $DB->count_records('languagelab_submissions', array('languagelab' => $languagelab->id));
	if ($submitted_recordings > 0) {
            $record_button = 'false';
        } else {
            $record_button = 'true';
        }
        $flashvars = $CFG->languagelab_red5server.','.$recordingname.','.$record_button;
        //$key = 'LanguageLabFlashVars124956496';
        $enc_flashvars =base64_encode($flashvars);

        //This is the Flash player required for recording the master track
        $recorder = <<<HERE
        <script type="text/javascript"  src="$CFG->wwwroot/mod/languagelab/flash.js"></script>
                            <script language="JavaScript" type="text/javascript">
            AC_FL_RunContent(
                'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0',
                'width', '215',
                'height', '138',
                'src', '$CFG->wwwroot/mod/languagelab/flash/AudioFilter',
                'quality', 'high',
                'pluginspage', 'http://www.adobe.com/go/getflashplayer',
                'align', 'middle',
                'play', 'true',
                'loop', 'true',
                'scale', 'showall',
                'wmode', 'window',
                'devicefont', 'false',
                'id', 'AudioFilter',
                'bgcolor', 'D4D0C8',
                'name', 'AudioFilter',
                'menu', 'true',
                'allowFullScreen', 'false',
                'allowScriptAccess','always',
                'allowNetworking', 'all',
                'bgcolor', '#ffffff',
                'flashvars','sData=$enc_flashvars',
                'movie', '$CFG->wwwroot/mod/languagelab/flash/AudioFilter',
                'salign', ''
                ); //end AC code
                /*
                Here's what the recorder player needs in order to work.
                To play:
                    in flashvars:
                        server: address of the FMS server without protocol prefix nor folder suffix
                        sname: name of the stream to be played / recorded

                To record:
                    same as above plus:
                        in flashVars:
                            sRec property set to true -- DO NOT DEFINE the sRec PROPERTY UNLESS YOU WANT TO ENABLE IT. It would be way too easy to guess how to hack a teacher's recording otherwise.
                        in other parameters:
                            height must be set to at least 138 or else flash won't access webcam or mic
                Let me know if you have any question.
                */
        </script>
                    <noscript>
            <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="215" height="138" id="AudioFilter" align="middle">
            <param name="allowScriptAccess" value="always" />
            <param name="allowFullScreen" value="false" />
            <param name="movie" value="$CFG->wwwroot/mod/languagelab/flash/';?>AudioFilter.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />	<embed src="$CFG->wwwroot/mod/languagelab/flash/AudioFilter.swf" quality="high" bgcolor="#ffffff" width="215" height="24" name="AudioFilter" align="middle" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
            </object>
        </noscript>
HERE;
        //Change this value to false once MP3 works again
        $notfunctional = true;
	$editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES);
        $mform =& $this->_form;
        $config = get_config('languagelab');
        $mform->addElement('header','general',get_string('general','languagelab'));
	$mform->addElement('text', 'name', get_string('name', 'languagelab'), array('size'=>'45')); //Name to be used 
	$mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('editor', 'content', get_string('description', 'languagelab'),null, $editoroptions);
        $mform->addElement('static','master_track_recorder',get_string('master_track_recorder','languagelab'),$recorder);
        $mform->addHelpButton('master_track_recorder', 'master_track_recorder', 'languagelab');
        //This actual file name
        $mform->addElement('hidden','master_track_recording', $recordingname);
        if ($notfunctional == true) {
            $mform->addElement('hidden', 'master_track');  
        } else {
            $mform->addElement('filepicker', 'master_track', get_string('master_track','languagelab'), null, array('subdirs' => 0, 'maxfiles' => 1, 'accepted_types' => array('*.mp3') ));
        }
        
        
        $mform->addHelpButton('master_track', 'master_track', 'languagelab');
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
        $mform->addElement('checkbox','video',get_string('use_video','languagelab'));
        $mform->addHelpButton('video', 'video', 'languagelab');
        $mform->setDefault('video', false);
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
            
        }
    }

}
?>