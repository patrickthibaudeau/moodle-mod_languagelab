<?php
//define('NO_MOODLE_COOKIES', true);

require_once("../../../../../../../config.php");

    global $CFG, $USER;
    $id = optional_param('id', SITEID, PARAM_INT);

    require_course_login($id);
    @header('Content-Type: text/html; charset=utf-8');

    $recordingname = $CFG->languagelab_prefix.'_editor_'.$id.'_'.$USER->id.'_'.time().'_llb';//editor_courseid_userid_timestamp
    $flashvars = $CFG->languagelab_red5server.','.$recordingname.',true';
	//$key = 'LanguageLabFlashVars124956496';
    $enc_flashvars =base64_encode($flashvars);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#recorder_dlg.title}</title>
	<script type="text/javascript" src="../../tiny_mce_popup.js?v={tinymce_version}"></script>
	<script type="text/javascript" src="js/recorder.js?v={tinymce_version}"></script>
        <script type="text/javascript" src="js/recorder.js"></script>
	<link href="css/recorder.css?v={tinymce_version}" rel="stylesheet" type="text/css" />
</head>
<body onresize="RecorderDialog.resize();">
	<form onsubmit="RecorderDialog.insert();return false;">
		<div id="frmbody">
                    <script type="text/javascript"  src="<?php echo $CFG->wwwroot;?>/mod/languagelab/flash.js"></script>
                    <script language="JavaScript" type="text/javascript">
	AC_FL_RunContent(
		'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0',
		'width', '215',
		'height', '138',
		'src', '<?php echo $CFG->wwwroot.'/lib/editor/htmlarea/';?>AudioFilter',
		'quality', 'high',
		'pluginspage', 'http://www.adobe.com/go/getflashplayer',
		'align', 'middle',
		'play', 'true',
		'loop', 'true',
		'scale', 'showall',
		'wmode', 'window',
		'devicefont', 'false',
		'id', 'AudioFilter',
		'bgcolor', 'D4D0C8',
		'name', 'AudioFilter',
		'menu', 'true',
		'allowFullScreen', 'false',
		'allowScriptAccess','always',
		'allowNetworking', 'all',
		'flashvars','sData=<?php echo $enc_flashvars;?>',
		'movie', '<?php echo $CFG->wwwroot.'/lib/editor/tinymce/tiny_mce/3.3.9.2/plugins/recorder/flash/';?>AudioFilter',
		'salign', ''
		); //end AC code
		/*
		Here's what the recorder player needs in order to work.
		To play:
			in flashvars:
				server: address of the FMS server without protocol prefix nor folder suffix
				sname: name of the stream to be played / recorded

		To record:
			same as above plus:
				in flashVars:
					sRec property set to true -- DO NOT DEFINE the sRec PROPERTY UNLESS YOU WANT TO ENABLE IT. It would be way too easy to guess how to hack a teacher's recording otherwise.
				in other parameters:
					height must be set to at least 138 or else flash won't access webcam or mic
		Let me know if you have any question.
		*/
</script>
			<noscript>
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="215" height="138" id="AudioFilter" align="middle">
	<param name="allowScriptAccess" value="always" />
	<param name="allowFullScreen" value="false" />
	<param name="movie" value="<?php echo $CFG->wwwroot.'/lib/editor/tinymce/tiny_mce/3.3.9.2/plugins/recorder/flash/';?>AudioFilter.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />	<embed src="<?php echo $CFG->wwwroot.'/lib/editor/htmlarea/';?>AudioFilter.swf" quality="high" bgcolor="#ffffff" width="215" height="24" name="AudioFilter" align="middle" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
	</object>
</noscript>


      <br/>

        <input name="f_recurl" type="hidden" id="f_recurl" style="width: 100%;" value="<?php echo $recordingname;?>" />
        <input name="userid" type="hidden" id="userid" style="width: 100%;" value="<?php echo $USER->id;?>" />
        <input name="submitted" type="hidden" id="submitted" style="width: 100%;" value="true" />
        
		</div>
		
		<div class="mceActionPanel">
			<input type="submit" id="insert" name="insert" value="{#insert}" />
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
		</div>
	</form>
</body> 
</html> 
