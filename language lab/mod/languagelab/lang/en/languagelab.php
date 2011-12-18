<?php 
//************************************************************************
//**               LANGUAGE LAB Version 2 for Moodle 2                  **
//************************************************************************
//**@package languagelab                                                **
//**@Institution: oohoo.biz, Campus Saint-Jean, University of Alberta   **
//**@authors : Patrick Thibaudeau, Guillaume Bourbonniere               **
//**@version $Id: version.php,v 1.0 2011/12/17                          **
//**@Moodle integration: Patrick Thibaudeau                             **
//**@Flash programming: Guillaume Bourbonniere                          **
//**@Moodle integration: Patrick Thibaudeau                             **
//************************************************************************
//************************************************************************

$string['name'] = 'Name';
$string['modulename'] = 'Language lab';
$string['modulenameplural'] = 'Language labs';
$string['languagelab'] = 'Language lab';
$string['pluginadministration'] = 'Language lab';
$string['pluginname'] = 'Language lab';
$string['red5server'] = 'Path to your Red5 server';
$string['red5config'] = 'Enter the IP address or the fully qualified name for your red5 server. Localhost will not work!';
$string['name'] = 'Activity name';
$string['description'] = 'Description';
$string['availabledate'] = 'Available from: ';
$string['duedate'] = 'Due date: ';
$string['general'] = 'General';
$string['attempts'] = 'One recording per student';
$string['recording_timelimit'] = 'Recoding time limit in minutes. (0 = unlimited)';
$string['attempts_warning'] = 'Note: Only one submission is allowed.<br>You can return and record over the previous submission. This will delete the previous recording.<br>Only the last submitted recording will be evaluated';
$string['master_track'] = 'Master track (mp3 only)';
$string['master_track_file'] = 'Master track currently used';
$string['master_track_help'] = 'Similarily, if you have previous recordings in MP3 format, you can choose to upload the MP3 file and use it as the Master Track.
                                If you do use the MP3 file, it is important that you check the following checkbox \'<i>Use the Uploaded MP3 as Master Track.</i>\' Otherwise, the manually 
                                recorded track above will be used.<br>If you have previously uploaded an MP3, but would like to use the manual recording, uncheck the checkbox
                                from \'<i>Use the Uploaded MP3 as Master Track.</i>\' ';
$string['not_available'] = 'This activity expired. You can read/listen to your teachers notes. However, you will be unable to do any new recordings.';
$string['no_due_date'] = 'No due date entered.';
$string['no_available_date'] = 'No start date entered.';
$string['submit'] = 'Submit';
$string['recorderdescription'] = 'Recorder';
$string['use_grade_book'] = 'Use grade book';
$string['emailsubject'] = 'Activity - ';
$string['emailgreeting'] = 'Hello';
$string['emailbodynewreply'] = 'I have added a comment to your recording. Please return to the activity and listen/read my comments. ';
$string['emailbodydelete'] = 'I would like you to restart your recording. Please return to the activity and record yourself again. ';
$string['emailthankyou'] = 'Thank you';
$string['words'] = 'List of words for the students to record';
$string['prefix'] = 'Enter a prefix for recorded streams.';
$string['prefixhelp'] = 'The prefix here is usefull if the red5 server is used to stream other material. You will be able to easliy identify recorded streams';



$string['submit_recording'] = 'Submit your recording';
$string['recording_failed_save'] = 'Failed to save your recording to the database';
$string['recording_saved'] = 'Your recording has been submitted';
$string['recording_exists'] = 'Notice: you already have a recording. Please review the recording before pressing the record button.<br>Pressing the record button will erase the previous recording.';
$string['red5_adapter_access'] = 'Secure access to Red5 Adapter Plugin?';
$string['red5_adapter_access_help'] = 'Is the RAP server protected with a certificate? ie: https';
$string['red5_adapter_file'] = 'File name for the Red5 Adapter Plugin';
$string['red5_adapter_file_help'] = 'Enter the file name (with no extension) given to you by the Red5 administrator. The Red5 Adapter Plugin (RAP) is used to manipulate streams recorded on the Red5 Server. Example: When deleting a Language Lab activity, the RAP
                                    deletes the associated audio and video files, keeping the Red5 Server clean. In the next release, It will also be used to backup the audio and video files
                                    when doing a Moodle backup. This feature is not yet available. The file name is used for security reasons. This way if someone accesses the Red5 server, no default home page will show up. 
                                    This is the only file in the folder, making it harder to figure out. Of course, directory browsing must be disabled.';
$string['use_mp3'] = 'Use the Uploaded MP3 as master track?';
$string['use_mp3_help'] = 'If you use an uploaded file, you want to make sure that the check box is checked. Otherwise, when saving, the Manual recording will be
                            used as the Master track. On the other hand, if you do want to use the manual recording, make sure that this check box is unchecked. Remember to save the changes';
$string['master_track_recorder'] = 'Master track';
$string['master_track_recorder_help'] = 'A master track is an audio sample with blank spaces allowing students to
                                record themselves during the blank spaces.<br>Simply press the round record button and record your
                                exercise. For example, you could record the following exercise:
                                <ul><li>
                                Please say the word bonjour <i>(leave a 5 second pause)</i> Now, say the words bon soir <i>(again, leave a 5 second pause)</i>
                                </li></ul> This recording will play back when the student presses the record button. During the 5 second pauses, the student can say the
                                words and they will be recorded. As the teacher, when you listen to the student recordings, the Master Track will be played with the students response
                                allowing you to easliy compare.<br>Note: We had to integrate in this fashion because Flash does not
                                allow pausing while recording.';
$string['previous_recording'] = 'Your previous recordings: ';

//XML localization
$string['XMLLoadFail'] = 'XML couldn\'t be loaded. Contact your webmaster.';
$string['prerequisitesNotMet'] = 'Target server unspecified. Contact your webmaster.';
$string['warningLossOfWork'] = 'You are attempting to navigate away from a recording on which you made submitable changes. Are you sure you want to discard your changes?';
$string['newRecording'] = 'New recording';
$string['newReply'] = 'Teacher reply';
$string['timesOut'] = 'Time is up';

$string['subject'] = 'Recording';
$string['message'] = 'You may type a message here.';
$string['btnDiscard'] = 'Discard changes';
$string['btnCancel'] = 'Cancel';
$string['submitBlank'] = 'Submit';
$string['submitNew'] = 'Submit recording';
$string['submitChanges'] = 'Submit changes';
$string['submitGrade'] = 'Submit grade.';
$string['agoBefore'] = '';
$string['agoAfter'] = 'ago';
$string['days'] = 'days';
$string['hours'] = 'hours';
$string['minutes'] = 'minutes';
$string['seconds'] = 'seconds';
$string['grading'] = 'Grading';
$string['grade'] = 'Grade';
$string['startOver'] = 'Require student to start over';
$string['corrNotes'] = 'Enter correction notes here';

$string['monday'] = 'Mon';
$string['tuesday'] = 'Tues';
$string['wednesday'] = 'Wed';
$string['thursday'] = 'Thur';
$string['friday'] = 'Fri';
$string['saturday'] = 'Sat';
$string['sunday'] = 'Sun';
$string['january'] = 'Jan';
$string['february'] = 'Feb';
$string['march'] = 'Mar';
$string['april'] = 'Apr';
$string['may'] = 'May';
$string['june'] = 'Jun';
$string['july'] = 'Jul';
$string['august'] = 'Aug';
$string['september'] = 'Sept';
$string['october'] = 'Oct';
$string['november'] = 'Nov';
$string['december'] = 'Dec';

$string["advanced"] = 'Advanced settings';
$string["attempts_help"] = 'Check this box if you want your students to record only to one file.';
$string["async"] = 'Discussion (forum like)';
$string["dialogue"] = 'Dialogue';
$string["group_type"] = 'Group type';
$string["group_type_help"] = '<b>Note: Only use this setting if you are using seperate groups or visible groups</b><br>
                               <ul><li><i>Discussion:</i> Use this type if you would like your students to record asynchronously. A forum like thread will display the conversation</li>
                               <li><i>Dialogue:</i> Use this type if you want your group of students to have a recorded conversation.</li></ul>';
$string['maxusers'] = 'Maximum number of users.';
$string['maxusershelp'] = 'Maximum number of users that can use the language lab simultaniously.';

$string['languagelab:studentview'] = 'Language lab: Student view.';
$string['languagelab:teacherview'] = 'Language lab: Teacher view.';
$string['select_group_type'] = 'Select group type';


$string['classmonitor'] = 'Monitor your class';
$string['salt'] = 'Password salt value:';
$string['salt_help'] = 'Enter the password salt value for your red5 instance as provided by your red5 administrator.';
$string['stealthmode'] = 'Activate stealth mode?';
$string['stealthmodehelp'] = 'When activated, students will not know that they are being monitored when the teacher uses the classroom monitor.';
$string["use_video"] = 'Allow video.';
$string["use_grade_book_help"] = 'By default, no grading will be given for language lab activities. That way you can create as many language activities
                                   for exercise purposes, without filling up your gradebook. If you do want to grade this particular activity, check this box.';
$string["video"] = 'Allowing video.';
$string["video_help"] = 'Check this box if you would like your students to use video and audio while recording. This can be helpful, for example, for sign language.';


?>