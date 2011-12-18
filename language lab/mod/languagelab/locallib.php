<?php

require_once('../../config.php');
global $CFG;
require_once($CFG->dirroot . '/lib/filelib.php');


//conversion for htmleditor
function convert_stream_editor($stream, $context, $draftitemid){
    global $CFG, $DB;
    //Add file termination to strem
    $stream = $stream.'.flv';
    $red5path = $CFG->languagelab_red5server;

     //get the file using cURL
    $url  = "http://$red5path/languagelab/$stream";
    $path = str_replace('\\' ,'/',$CFG->dataroot).'/temp/'.$stream;
    
    $fp = fopen($path, 'w');
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    $data = curl_exec($ch);
    curl_close($ch);
    fclose($fp);
       
    $draftid = file_get_submitted_draft_itemid($draftitemid);
    
    //Convert files to mp3
            $sourcefile ='"'.$path.'"';
            $mp3file = str_replace('.flv','.mp3',$stream);
            
            $outputfile ='"'.$CFG->dataroot.'/temp/'.$mp3file.'"';
            $command = $CFG->ffmpeg.' -i ';
            shell_exec($command.$sourcefile.' '.$outputfile);

            //check to verify that file has been created
            $checkfile = str_replace('"','',$outputfile);
            $checkfile = str_replace('\\' ,'/',$checkfile);
            $time =time();
            $user = $DB->get_record('user', array('id' => $context->instanceid));

            //if mp3 created, then create file in Moodle file database
               if (is_file($checkfile)) {
                   $fs = get_file_storage();
                    
                    // Prepare file record object
                    $fileinfo = array(
                    'contextid' => $context->id, 
                    'component' => 'user',     // usually = table name
                    'filearea' => 'draft',     // usually = table name
                    'itemid' => $draftid,               // usually = ID of row in table
                    'filepath' => '/',           // any path beginning and ending in /
                    'userid' => $context->instanceid,           // any path beginning and ending in /
                    'filename' => "$mp3file",
                    'author' => $user->firstname.' '.$user->lastname,
                    'license' => 'allrightsreserved'); // any filename

                    // Create file containing text
                    $fs->create_file_from_pathname($fileinfo, $checkfile);
                    echo "<a href='$CFG->wwwroot/draftfile.php/$context->id/user/draft/$draftitemid/$mp3file'><img src='$CFG->wwwroot/mod/languagelab/pix/recording_image.gif'border='0' /></a>";
                    //delete the two files from temp
                   
                    unlink($path);
                    unlink($checkfile);
               }
            
        return true;
}



function convert_curl_stream_editor($stream, $courseid, $userid){
    global $CFG;
    $stream = $stream.'.flv';
    $red5path = $CFG->languagelab_red5server;

    //get the file using cURL
    $url  = "http://$red5path/languagelab/$stream";
    $path = str_replace('\\' ,'/',$CFG->dataroot).'/temp/'.$stream;
    echo $path;
    $fp = fopen($path, 'w');

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    

    $data = curl_exec($ch);

    curl_close($ch);
    fclose($fp);

    return true;
   
}
function isDomainAvailible($domain)
       {
               //check, if a valid url is provided
               if(!filter_var($domain, FILTER_VALIDATE_URL))
               {
                       return false;
               }

               //initialize curl
               $curlInit = curl_init($domain);
               curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
               curl_setopt($curlInit,CURLOPT_HEADER,true);
               curl_setopt($curlInit,CURLOPT_NOBODY,true);
               curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

               //get answer
               $response = curl_exec($curlInit);

               curl_close($curlInit);

               if ($response) return true;

               return false;
       }
 

?>
