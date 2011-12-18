<?php
//DO NOT MODIFY THIS FILE
include('config.php');
//Load file that contains the server information for Moodle
$xml = simplexml_load_file($CFG->xml_path); 
$serverInfo = $_REQUEST['q'];
$serverAction = $_REQUEST['o'];
$submissions = $_REQUEST['s'];
$mastertrack = $_REQUEST['m'];


// set count value for array
$x =0;
//Get XML information
foreach($xml->children() as $child)
  {
    //Create MD5 password value to compare with that sent by Moodle server
    $this_serverInfo[$x] = md5($xml->moodle[$x]->attributes()->serverAddress.$xml->moodle[$x]->attributes()->languagelabPrefix.$xml->moodle[$x]->attributes()->salt);
    if ($this_serverInfo[$x] == $serverInfo) {
      $serverAddress = $xml->moodle[$x]->attributes()->serverAddress;
      $languagelabPrefix = $xml->moodle[$x]->attributes()->languagelabPrefix;
      $salt = $xml->moodle[$x]->attributes()->salt;
      $path = $xml->moodle[$x]->attributes()->streamFolderPath;
      break; 
    }
  $x++;
  }
  
  //what action is trying to be applied
  /*
   * For the time being, only two actions exist
   * delete - delete files from the stream server
   * convert - convert files into mp3, zip and send to user. 
   */
  switch ($serverAction) {
      case md5('delete'.$salt):
          $submissions = json_decode($submissions);
          //Delete Master track
          if (file_exists($path.$mastertrack.'.flv')){
              unlink($path.$mastertrack.'.flv');
          }
	   if (file_exists($path.$mastertrack.'.flv.meta')){
              unlink($path.$mastertrack.'.flv.meta');
          }
          //Go through all recordings and delete
          foreach ($submissions as $submission) {
              if (file_exists($path.$submission->path.'.flv')){
                  unlink($path.$submission->path.'.flv');
              }
              
              if (file_exists($path.$submission->path.'.flv,meta')){
                  unlink($path.$submission->path.'.flv.meta');
              }
              
          }
          break;
      case md5('convert'.$salt);
          echo 'convert';
          break;
  }
 
?>