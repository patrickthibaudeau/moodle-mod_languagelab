<?php // $Id: tab.php,v 1.2 2008/07/01 09:44:05 moodler Exp $ 
      // tab.php - created with Moodle 1.9

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

$string['maxusers'] = 'Maximum number of users.';
$string['maxusershelp'] = 'Maximum number of users that can use the language lab simultaniously.';

$string['languagelab:studentview'] = 'Language lab: Student view.';
$string['languagelab:teacherview'] = 'Language lab: Teacher view.';

$string['xssAddress'] = 'XML Socket Server Address';
$string['xssAddresshelp'] = 'The IP address or fully qualified name of the XML Socket Server.';
$string['xssAddress_name'] = 'Enter the IP address or FQDN of your XML socket server';
$string['xssPort'] = 'XML Socket Server port.';
$string['xssPorthelp'] = 'The port number used by XML Socket Server.';

$string['classmonitor'] = 'Monitor your class';
$string['stealthmode'] = 'Activate stealth mode?';
$string['stealthmodehelp'] = 'When activated, students will not know that they are being monitored when the teacher uses the classroom monitor.';

//previously in languagelab block
$string["ffmpeg"] = 'Path to ffmpeg executable.';
$string["ffmpeghelp"] = 'Entering the path to the ffmpeg executable will enable you to convert recorded streams to mp3.';
$string["ftpdirectory"] = 'FTP folder to the Red5 streams';
$string["ftpdirectoryhelp"] = 'FTP folder to the Red5 streams. Usaully /';
$string["ftphost"] = 'IP or DNS address of your FTP server.';
$string["ftphosthelp"] = 'You only need to enter an FTP address if the Red5 Server is not installed on the same physical serve. You will need an FTP connection to the Red5 server stream folder inorder to perform backups and conversions';
$string["ftpusername"] = 'FTP Accountusername.';
$string["ftpuserpassword"] = 'FTP Account password.';
$string["ftpuserpasswordhelp"] = 'Password.';
$string["ftpport"] = 'FTP Port number.';
$string["ftpporthelp"] = 'Port number used for your FTP server.';
$string["ftpprotocolhelp"] = 'Protocol used to connect to the FTP server.';
$string["ftpprotocol"] = 'FTP protocol.';
$string["ftpprotocolhelp"] = 'Protocol used to connect to the FTP server.';
$string["ftpusernamehelp"] = 'Username used to access FTP site.';
$string["getffmpeg"] = 'You should ask your administrator to install FFMPEG on your server. This would cut file size by 25%.';
$string["localred5"] = 'Is your Red5 server hosted on the same server as moodle?';
$string["localred5help"] = 'If your Red5 Server installation is hosted on the same server as your Moodle server, check this box. If not leave blank and enter the FTP information';
$string["red5path"] = 'Path to the Red5 server stream folder.';
$string["red5pathhelp"] = 'Enter the path to the Red5 server stream folder.';
?>