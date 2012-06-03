<?php

        foreach(glob($CFG->rootdir.'*.zip') as $file){
            $lastchanged = filectime($file);
            $difference = time() - $lastchanged;
            $expires = 450000; //5 days
            //Delete files if older than 5 days
            if ($difference >= $expires ) {
                unlink($file);
                echo 'Deleted '.$file.'<br>';
            } else {
                echo 'No files to delete';
            }
        }
  
?>
