<?php
include('../../config.php');
include('locallib.php');
//Is the Red5 Adapter Plugin set
    if (isset($CFG->languagelab_adapter_file)) {
        //Let's delete all files on the Red5 Server
        $Red5Server = $CFG->languagelab_red5server;
        $prefix = $CFG->languagelab_prefix;
        $salt = $CFG->languagelab_salt;
        //RAP security
        if ($CFG->languagelab_adapter_access == true) {
            $security = 'https://';
        } else {
            $security = 'http://';
        }
        $url = "$security$Red5Server/test.php";
        if(isDomainAvailible($url)){
            echo 'Site is available';
        } else {
            echo 'There is an error in your configuration';
        }
        
        //Encrypt information
        $q = md5($Red5Server.$prefix.$salt);

        $vars = "q=$q";

        //Send request to red5 server using curl
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);

        $result = curl_exec($ch);
    }
    
?>
