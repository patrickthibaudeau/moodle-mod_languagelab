<?php  //$Id: upgrade.php,v 1.1.8.1 2008/05/01 20:51:20 skodak Exp $

// This file keeps track of upgrades to 
// the label module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

defined('MOODLE_INTERNAL') || die;

function xmldb_languagelab_upgrade($oldversion=0) {

  global $CFG, $THEME, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2011021200) {

        // Define field contentformat to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('contentformat', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'description');

        // Conditionally launch add field contentformat
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011021200, 'languagelab');
    }

        if ($oldversion < 2011031800) {

        // Define field contentformat to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('contentformat', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'description');

        // Conditionally launch add field contentformat
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011031800, 'languagelab');
    }

        if ($oldversion < 2011041901) {

        // Define field id to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('use_grade_book', XMLDB_TYPE_INTEGER, '1', null, null, null, null, null);

        // Conditionally launch add field id
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011041901, 'languagelab');
    }
    if ($oldversion < 2011050900) {


        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011050900, 'languagelab');
    }

    if ($oldversion < 2011051100) {


        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011051100, 'languagelab');
    }

    if ($oldversion < 2011052600) {

        // Define field video to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('video', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'attempts');

        // Conditionally launch add field video
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011052600, 'languagelab');
    }

    if ($oldversion < 2011052601) {

        // Define field group_type to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('group_type', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'use_grade_book');

        // Conditionally launch add field group_type
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011052601, 'languagelab');
    }

    if ($oldversion < 2011080800) {

        //Fixed undefined variables
        //Fixed $available in view.php
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011080800, 'languagelab');
    }
        if ($oldversion < 2011082600) {

        // Define field master_track to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('master_track', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'group_type');

        // Conditionally launch add field master_track
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011082600, 'languagelab');
    }
    if ($oldversion < 2011082700) {

        //Activated Master track feature
        
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011082700, 'languagelab');
    }
	if ($oldversion < 2011082900) {

        //Fixed backup_steps (missing fields)
		//Updated LanguageLabCT.swf
        
        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011082900, 'languagelab');
    }
    if ($oldversion < 2011113000) {

        //Rebuilt both SWF. No longer need XMLSocket server
		//Updated LanguageLabCT.swf

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011113000, 'languagelab');
    }
    if ($oldversion < 2011120500) {

        //Removed XML Socket settings
		//Updated LanguageLabCT.swf

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011120500, 'languagelab');
    }
        if ($oldversion < 2011121200) {

        // Define field master_track_recording to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('master_track_recording', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'master_track');

        // Conditionally launch add field master_track_recording
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011121200, 'languagelab');
    }
        if ($oldversion < 2011121500) {

        // Define field use_mp3 to be added to languagelab
        $table = new xmldb_table('languagelab');
        $field = new xmldb_field('use_mp3', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'master_track_recording');

        // Conditionally launch add field use_mp3
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011121500, 'languagelab');
    }
    if ($oldversion < 2011121701) {

        //Added Red5 Adapter Plugin File name admin setting
        //Added Security level for the RAP Server 

        // languagelab savepoint reached
        upgrade_mod_savepoint(true, 2011121701, 'languagelab');
    }


 return;

}

?>
