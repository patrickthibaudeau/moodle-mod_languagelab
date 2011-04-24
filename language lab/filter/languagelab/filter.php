<?php
require_once($CFG->libdir.'/filelib.php');
/*add the following command to your header file in your theme <?php require_js($CFG->wwwroot.'/filter/languagelab/flash.js');?>*/

function languagelab_filter ($courseid,$text){
    global $CFG;

if (!is_string($text)) {
        // non string data can not be filtered anyway
        return $text;
    }
$newtext = $text;

$search = '/<img.*?title="([^<]+\_llb)"[^>]*>.*?/is';
$newtext = preg_replace_callback($search, 'languagelab_filter_callback', $newtext);

return $newtext;
}

function languagelab_filter_callback($recording) {
  global $CFG;

static $count = 0;
    $count++;
    //$id = 'filter_llb_'.time().$count; //we need something unique because it might be stored in text cache

    $RecordingTitle = addslashes_js($recording[1]);
    $recording[0] = '';

    $flashvars = $CFG->languagelab_red5server.','.$RecordingTitle.',false';
    //$key = 'LanguageLabFlashVars124956496';
    $enc_flashvars =base64_encode($flashvars);
  
    return $recording[0].
   '<script type="text/javascript"  src="'.$CFG->wwwroot.'/filter/languagelab/flash.js"></script>
       <script language="JavaScript" type="text/javascript">
	AC_FL_RunContent(
		\'codebase\', \'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0\',
		\'width\', \'150\',
		\'height\', \'20\',
		\'src\', \''.$CFG->wwwroot.'/filter/languagelab/AudioFilter\',
		\'quality\', \'high\',
		\'pluginspage\', \'http://www.adobe.com/go/getflashplayer\',
		\'align\', \'middle\',
		\'play\', \'true\',
		\'loop\', \'true\',
		\'scale\', \'showall\',
		\'wmode\', \'window\',
		\'devicefont\', \'false\',
		\'id\', \'AudioFilter\',
		\'bgcolor\', \'#ffffff\',
		\'name\', \'AudioFilter\',
		\'menu\', \'true\',
		\'allowFullScreen\', \'false\',
		\'allowScriptAccess\',\'always\',
		\'allowNetworking\', \'all\',
		\'flashvars\',\'sData='.$enc_flashvars.'\',
		\'movie\', \''.$CFG->wwwroot.'/filter/languagelab/AudioFilter\',
		\'salign\', \'\'
		); //end AC code
		
		
</script>
<noscript>
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="215" height="138" id="AudioFilter" align="middle">
	<param name="allowScriptAccess" value="always" />
	<param name="allowFullScreen" value="false" />
	<param name="movie" value="'.$CFG->wwwroot.'/filter/languagelab/AudioFilter" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><embed src="'.$CFG->wwwroot.'/filter/languagelab/AudioFilter" quality="high" bgcolor="#ffffff" width="215" height="24" name="AudioFilter" align="middle" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" flashvars="sData='.$enc_flashvars.'" />
	</object>
</noscript>';
}
?>
