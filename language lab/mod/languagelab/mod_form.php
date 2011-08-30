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

    function definition() {
        global $CFG, $USER;
        
	$editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES);
        $mform =& $this->_form;
        $config = get_config('languagelab');
        $mform->addElement('header','general',get_string('general','languagelab'));
	$mform->addElement('text', 'name', get_string('name', 'languagelab'), array('size'=>'45')); //Name to be used 
	$mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('editor', 'content', get_string('description', 'languagelab'),null, $editoroptions);
        $mform->addElement('filepicker', 'master_track', get_string('master_track','languagelab'), null, array('subdirs' => 0, 'maxfiles' => 1, 'accepted_types' => array('*.mp3') ));
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