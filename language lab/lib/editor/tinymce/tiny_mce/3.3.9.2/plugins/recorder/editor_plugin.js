(function(){tinymce.PluginManager.requireLangPack('recorder');tinymce.create('tinymce.plugins.RecorderPlugin',{init:function(ed,url){courseid=ed.getParam('courseid');ed.addCommand('mceRecorder',function(){ed.windowManager.open({file:url+'/recorder.php?id='+courseid,width:320+parseInt(ed.getLang('recorder.delta_width',0)),height:120+parseInt(ed.getLang('recorder.delta_height',0)),inline:1},{plugin_url:url,some_custom_arg:'custom arg'})});ed.addButton('recorder',{title:'recorder.desc',cmd:'mceRecorder',image:url+'/img/recorder.gif'});ed.onNodeChange.add(function(ed,cm,n){cm.setActive('recorder',n.nodeName=='IMG')})},createControl:function(n,cm){return null},getInfo:function(){return{longname:'Recorder plugin',author:'Patrick Thibaudeau',authorurl:'http://www.csj.ualberta.ca',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/recorder',version:"1.0"}}});tinymce.PluginManager.add('recorder',tinymce.plugins.RecorderPlugin)})();