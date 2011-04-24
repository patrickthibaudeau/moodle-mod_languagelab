TinyMCE Recorder plugin

In order for the recorder plugin to work, you must modify the following file:

moodle root/lib/editor/tinymce/lib.php

add the following code

In the $params array under plugin (approx line 127) add ,recorder inside the Quotes. The line should look like this:

'plugins' => "{$xmedia}advimage,safari,table,style,layer,advhr,advlink,emotions,inlinepopups,searchreplace,paste,directionality,fullscreen,moodlenolink,{$xemoticon}{$xdragmath}nonbreaking,contextmenu,insertdatetime,save,iespell,preview,print,noneditable,visualchars,xhtmlxtras,template,pagebreak,spellchecker,recorder",

At the 'theme_advanced_buttons3_add' array value add ,|,recorder inside the quotes. The line should look like this

'theme_advanced_buttons3_add' => "table,|,code,spellchecker,|,recorder",

