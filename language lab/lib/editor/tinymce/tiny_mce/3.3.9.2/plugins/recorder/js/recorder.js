
tinyMCEPopup.requireLangPack();

function ajaxrequest() {

var recurl = document.getElementsByName('f_recurl')[0].value;
var userid = document.getElementsByName('userid')[0].value;



var script = "recorder_controller.php?function=recorder_convert&test=ok&file="+recurl+"&userid="+userid;


        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }


xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    tinyMCEPopup.editor.execCommand('mceInsertContent', false, xmlhttp.responseText);
    tinyMCEPopup.close();
    }
  }
xmlhttp.open("POST",script,true);
xmlhttp.send();


}

var RecorderDialog = {
	init : function() {
		var f = document.forms[0];

		// Get the selected contents as text and place it in the input
		//f.someval.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		//f.somearg.value = tinyMCEPopup.getWindowArg('some_custom_arg');
	},

	insert : function() {
		// Insert the contents from the input into the document
                ajaxrequest();

		//tinyMCEPopup.editor.execCommand('mceInsertContent', false, document.forms[0].someval.value);
		
	}
};

tinyMCEPopup.onInit.add(RecorderDialog.init, RecorderDialog);
//tinyMCEPopup.onInit.add(RecorderDialog.insert, RecorderDialog);
