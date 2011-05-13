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
   

 return;

}

?>
