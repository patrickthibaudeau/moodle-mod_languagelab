<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

        $get_groupings = groups_get_all_groupings($course->id);
        $get_groups = groups_get_all_groups($course->id, $USER->id, 5);

        $get_group_members = groups_get_members($get_groups->id);
foreach ($get_groups as $group){
        $group_members = groups_get_members($group->id);

            print_object($group_members);
        }
?>
