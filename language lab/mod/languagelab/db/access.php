<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$capabilities = array(

    'mod/languagelab:studentview' => array(

        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'guest' => CAP_ALLOW,
            'student' => CAP_ALLOW,
            'teacher' => CAP_PREVENT,
            'editingteacher' => CAP_PREVENT, 
            'manager' => CAP_PREVENT
        )
    ),

    'mod/languagelab:teacherview' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

);
?>
