var flashMajor = 11;
var flashMinor = 2;
var flashRevisionMin = 202;
var flashRevisionMax = 380;

$(function(){
    var elem = $('#descrLabLang');
    
    if(FlashDetect.major == flashMajor && FlashDetect.minor == flashMinor && (FlashDetect.revision >= flashRevisionMin && FlashDetect.revision <= flashRevisionMax))
    {
        var text = elem.html() +'<b style="color:red;">Your Flash version is not entirely functional with the Language Lab. You will not be able to listen to your recording prior to you submitting it. Please download an other version of flash.<br /><br />';
        text = text + 'Click <a href="http://oohoo.biz/flash/fp_11.1.102.62_archive.zip">here</a> to download a compatible version of flash</b></br></br>';
        $(elem).html(text);
    }
});
//CHECK FLASH VERSION



//Used to get the flash component
function getFlashMovieObject(movieName)
{
    if (window.document[movieName]) 
    {
        return window.document[movieName];
    }
    if (navigator.appName.indexOf("Microsoft Internet")==-1)
    {
        if (document.embeds && document.embeds[movieName])
            return document.embeds[movieName]; 
        else
            return null;
    }
    else // if (navigator.appName.indexOf("Microsoft Internet")!=-1)
    {
        return document.getElementById(movieName);
    }
}

//Hide or display the player options
function toggleDisplayOptions()
{
    if (document.getElementById('divPlayerOptions').style.position == 'relative')
    {
        
    }
    else
    {
        playeroptions_open();
    }
}

//Put the UserLiveURI in the variable
function getUserLiveURI(liveURI)
{
    userLiveURI = liveURI;
    $("#mylive").text(liveURI);
}

//Put the UserRecordURI in the variable
function getUserRecordURI(recordURI)
{
    //If mode Masterstrack
    if(playerRecorders['playerRecorderMastertrack'] != undefined)
    {
        $('input[name="master_track_recording"]').val(recordURI);
    }
    userRecordURI = recordURI;
}

//Run this function when player is ready
function playeroptions_ready()
{
    if (playerOptions == undefined)
    {
        playerOptions = getFlashMovieObject('playerOptions');
        if(playerOptions.microphoneEnabled())
        {
        //If microphone enabled, do nothing
        }
        else
        {
            playeroptions_open();
        }
    }
}

function playeroptions_open()
{
    $("#divPlayerOptions").dialog(
    {
        closeOnEscape: false,
        modal: true,
        width: 450,
        buttons: [ {
            text:playeroptionsBtnOk, 
            click: function() {
                window.location.reload();
            }
        }],
        open: function(event, ui){
            $(".ui-dialog-titlebar-close", $(this).parent()).hide();
            $("#divPlayerOptions").css('position', 'relative');
            $("#divPlayerOptions").css('top',0);
        }
    });
}
                
//Run this function when player is ready
function playerrecorder_ready(idPlayer)
{
    if (playerRecorders[idPlayer] == undefined)
    {
        playerRecorders[idPlayer] = getFlashMovieObject(idPlayer);
    }
    if(this['playerrecorder_ready_'+idPlayer] != undefined)
    {
        this['playerrecorder_ready_'+idPlayer].apply(this,[]);
    }
}

//Init the connection with the player
function playerrecorder_ready_playerRecorderStudent()
{
    //If Tmode, not publish a live feed
    var tmode = (typeof(teacherMode) != 'undefined' && teacherMode == true);
    playerRecorders['playerRecorderStudent'].set_sPrefixFiles(files_prefix);
    playerRecorders['playerRecorderStudent'].init_rtmpConnection(rtmpserver, !tmode);
}

//Init the connection with the player
function playerrecorder_ready_playerRecorderTeacher()
{
    playerRecorders['playerRecorderTeacher'].set_sPrefixFiles(files_prefix);
    playerRecorders['playerRecorderTeacher'].init_rtmpConnection(rtmpserver, false);
}

//Init the connection with the player
function playerrecorder_ready_playerRecorderMastertrack()
{
    playerRecorders['playerRecorderMastertrack'].set_sPrefixFiles(files_prefix);
    playerRecorders['playerRecorderMastertrack'].init_rtmpConnection(rtmpserver, false);
}

//Sent after connection established
function RTMPServerReady(idPlayer)
{
    $("#connectionScreen").fadeOut();
    
    if(typeof(teacherMode) != 'undefined')
    {
        //TEACHER MODE
    }
    else
    {
        if(idPlayer == 'playerRecorderMastertrack') //ACTIVITY EDIT MODE
        {
            if(newRecording)
            {
                playerRecorders['playerRecorderMastertrack'].setPlayerMode(2);
            }
            else
            {
                playerRecorders['playerRecorderMastertrack'].setPlayerMode(1);
                playerRecorders['playerRecorderMastertrack'].addURIToNetStreams(fileURI);
            }
        }
        else //STUDENT MODE
        {
            $("#newRecording").click();
        }
    }
}

//Set the player for a new feedback
function newFeedback()
{
    $("#titleFeedback").val(reFeedBack+$("#titleRecording").val());
    playerRecorders['playerRecorderTeacher'].setPlayerMode(2);
    $("#descriptionFeedback").val('');
    $("#submitFeedback").removeAttr('disabled');
    $("#submitFeedback").removeClass('btnDisabled');
}

//This function refresh the history only if the data changed (based on a checksum)
function autoRefreshHistory()
{
    $.getJSON(
        urlHistory+"?activity_id="+activityid+'&selectedelem='+selectedElem+'&selecteduser='+selectedUser+"&rand="+Math.random(),
        function(data)
        {
            if(checksum == '' || checksum != data.checksum)
            {
                $("#recordings").jstree("refresh");
            }
            //add the call to the autorefresh
            setTimeout('autoRefreshHistory()', secondsRefreshHistory);
        }
        );
}

//This function refresh the student live
function autoRefreshStudentLive()
{
    $.getJSON(
        urlLive+"?id="+activityid+"&uri="+userLiveURI+"&rand="+Math.random(),
        function(data)
        {
            
            //Get data back from the refresh
            var events = data.data;
            
            for (i in events)
            {
                if(events[i].type == 'live_add' || events[i].type == 'liveclass_add')
                {
                    try{
                        playerRecorders['playerRecorderStudent'].addURIToNetStreamsLive(events[i].data.uri);
                        
                        
                        $("#dialogInfo").html(events[i].data.message);
                        $("#dialogInfo").dialog({
                            title: events[i].data.title,
                            resizable: false,
                            closeOnEscape: false,
                            modal: true,
                            buttons: [],
                            open: function(event, ui){
                                $(".ui-dialog-titlebar-close", $(this).parent()).hide();
                            }
                        });
                    }
                    catch(e)
                    {
                        
                    }
                
                }
                else if(events[i].type == 'live_remove' || events[i].type == 'liveclass_remove')
                {
                    try{
                        playerRecorders['playerRecorderStudent'].dropURIToNetStreamsLive(events[i].data.uri);
                        $("#dialogInfo").dialog( "close");
                    }
                    catch(e)
                    {
                        
                    }
                }
                else if(events[i].type == 'listened_add')
                {
                    $("#listened").fadeIn();
                }
                else if(events[i].type == 'listened_remove')
                {
                    $("#listened").fadeOut();
                }
                else if(events[i].type == 'thumbs_up')
                {
                    $("#thumbsup").fadeIn();
                    setTimeout('hide_thumbsup()', 30000);
                }
            }
            
            //add the call to the autorefresh
            setTimeout('autoRefreshStudentLive()', secondsRefreshStudentView);
        }
        );
}

function hide_thumbsup()
{
    $("#thumbsup").fadeOut();
}

//Load student record in the player
function loadStudentRecord(elem)
{
    $("#imgStudent").attr('src',elem.data('portrait'));
    $("#recordAgoStudent").text(elem.data('lastUpdate'));
    $("#nameStudent").text(elem.data('author'));

    //LOAD the sound in the player
    playerRecorders['playerRecorderStudent'].setPlayerMode(1);
    $("#titleRecording").val(elem.data('title'));
    $("#descriptionRecording").val(elem.data('tMessage'));

    playerRecorders['playerRecorderStudent'].addURIToNetStreams(elem.data('recURI'));
    if (elem.data('mastertrack') != '')
    {
        playerRecorders['playerRecorderStudent'].addURIToNetStreams(elem.data('mastertrack'));
    }
}
//Load the teacher record in the player
function loadTeacherRecord(elem)
{
    $("#imgTeacher").attr('src',elem.data('portrait'));
    $("#recordAgoTeacher").text(elem.data('lastUpdate'));
    $("#nameTeacher").text(elem.data('author'));

    $("#titleFeedback").attr('readonly', 'readonly');
    $("#descriptionFeedback").attr('readonly', 'readonly');
    $("#submitFeedback").attr('disabled', 'disabled');
    $("#submitFeedback").addClass('btnDisabled', 'btnDisabled');

    //LOAD the sound in the player
    playerRecorders['playerRecorderTeacher'].setPlayerMode(1);
    $("#titleFeedback").val(elem.data('title'));
    $("#descriptionFeedback").val(elem.data('tMessage'));

    playerRecorders['playerRecorderTeacher'].addURIToNetStreams(elem.data('recURI'));
}
//Load the grade of he student
function loadGradeStudent(elem)
{
    $("#gradeInactif").hide();
    $("#submitGrade").removeAttr('disabled');
    $("#submitGrade").removeClass('btnDisabled');

    $("#privateNote").val(elem.data('gradePrivateNote'));
    $("#gradeInput").val(elem.data('grade'));
    $("#gradeSlider").slider('value', elem.data('grade'));
}
//Select the previous node in the list
function selectPreviousNode()
{
    $("#recordings li").each(function()
    {
        if($(this).data('selected') == true)
        {
            $("#recordings").jstree("select_node",$(this));
        }
    });
}

$(function(){
    $.jstree._themes = './js/jquery.jstree/themes/';
    
    $("#recordings").jstree(
    {
        "plugins" : ["themes", "json_data","ui", "search"],
        "themes" : {
            "theme" : "default",
            "dots" : false,
            "icons" : true
        },
        "json_data" : { 
            "ajax" :{
                "url" : function(){
                    return urlHistory+"?activity_id="+activityid+'&selectedelem='+selectedElem+'&selecteduser='+selectedUser+"&rand="+Math.random();
                },
                "data" : function (n) {
                    return ;
                },
                "success": function (data){
                    checksum = data.checksum;
                    return data.json;
                }
            }
        },
        "search" : {
            "case_insensitive" : true,
            //"search_method" :  "jstree_title_contains",
            "ajax" : false
        },
        "ui" : {
            "select_multiple_modifier" : false
        }
    }
    )
    .bind("select_node.jstree", function (event, data) {
    
        selectedElem = data.rslt.obj.data('recordingid');
        selectedUser = data.rslt.obj.data('studentid');
        //TeacherMode
        var tMode = (typeof(teacherMode) != 'undefined' && teacherMode == true);
        
        var elemType = $(".jstree-clicked","#recordings").parent().data('type');
        
        if(elemType == 'record' || (tMode && elemType == 'feedback'))
        {
            $("#deleteRecordings").removeAttr('disabled');
            $("#deleteRecordings").removeClass('btnDisabled');
        }
        else
        {
            $("#deleteRecordings").attr('disabled','disabled');
            $("#deleteRecordings").addClass('btnDisabled');
            
        }
        
        if(elemType == 'user' && $('li', $(".jstree-clicked","#recordings").parent()).length == 0)
        {
            $("#downloadRecording").attr('disabled','disabled');
            $("#downloadRecording").addClass('btnDisabled');
        }
        else
        {
            $("#downloadRecording").removeAttr('disabled');
            $("#downloadRecording").removeClass('btnDisabled');
        }
            
        //Set the buttons status
        $("#titleRecording").attr('readonly','readonly');
        $("#descriptionRecording").attr('readonly','readonly');
        $("#submitRecording").attr('disabled','disabled');
        $("#submitRecording").addClass('btnDisabled');
        
        
        if(tMode)
        {
            playerRecorders['playerRecorderStudent'].resetNetStreams();
            playerRecorders['playerRecorderTeacher'].resetNetStreams();
            
            if(elemType == 'record')
            {
                loadStudentRecord(data.rslt.obj);
                //Check if a feedback exists and load it if, else load the player for a record
                var feedback = $('li:first', data.rslt.obj);
                
                if(feedback.length > 0)
                {
                    loadTeacherRecord(feedback);
                }
                else
                {
                    //If no feedback, set the fields for
                    $("#imgTeacher").attr('src',defaultUserPicture);
                    $("#recordAgoTeacher").text('');
                    $("#nameTeacher").text('');
                    
                    playerRecorders['playerRecorderTeacher'].setPlayerMode(2);
                    $("#titleFeedback").val(reFeedBack+data.rslt.obj.data('title'));
                    $("#descriptionFeedback").val('');
                    $("#titleFeedback").removeAttr('readonly');
                    $("#descriptionFeedback").removeAttr('readonly');
                    $("#submitFeedback").removeAttr('disabled');
                    $("#submitFeedback").removeClass('btnDisabled');
                }
                
                if(useGradebook)
                {
                    loadGradeStudent(data.rslt.obj);
                }
            }
            else if(elemType == 'feedback')
            {
                loadTeacherRecord(data.rslt.obj);
                //Get the student record for this feedback
                var record = $(data.rslt.obj).parent().parent();
                loadStudentRecord(record);
                
                if(useGradebook)
                {
                    loadGradeStudent(data.rslt.obj);
                }
            }
            else // if(elemType == 'user')
            {
                $("#imgStudent").attr('src',data.rslt.obj.data('portrait'));
                $("#recordAgoStudent").text('');
                $("#nameStudent").text(data.rslt.obj.data('author'));
                
                $("#imgTeacher").attr('src',defaultUserPicture);
                $("#recordAgoTeacher").text('');
                $("#nameTeacher").text('');
            
                playerRecorders['playerRecorderStudent'].setPlayerMode(0);
                $("#titleRecording").val('');
                $("#descriptionRecording").val('');
                
                
                playerRecorders['playerRecorderTeacher'].setPlayerMode(0);
                $("#titleFeedback").val('');
                $("#descriptionFeedback").val('');
                $("#titleFeedback").attr('readonly', 'readonly');
                $("#descriptionFeedback").attr('readonly', 'readonly');
                $("#submitFeedback").attr('disabled', 'disabled');
                $("#submitFeedback").addClass('btnDisabled', 'btnDisabled');
                
                //LOAD Grade
                if(useGradebook)
                {
                    if($('li',data.rslt.obj).length > 0)
                    {
                        loadGradeStudent(data.rslt.obj);
                    }
                    else
                    {
                        $("#gradeInactif").show();
                        $("#gradeInactif").html('<br/><br/><br/>'+gradeStudentWithRecordings);
                        $("#submitGrade").attr('disabled', 'disabled');
                        $("#submitGrade").addClass('btnDisabled');
                        $("#privateNote").val('');
                        $("#gradeInput").val(0);
                        $("#gradeSlider").slider('value', 0);
                    }
                }
            }
        }
        else
        {
            //LOAD the sound in the player
            playerRecorders['playerRecorderStudent'].setPlayerMode(1);
            $("#titleRecording").val(data.rslt.obj.data('title'));
            $("#descriptionRecording").val(data.rslt.obj.data('tMessage'));

            $("#imgStudent").attr('src',data.rslt.obj.data('portrait'));
            $("#recordAgoStudent").text(data.rslt.obj.data('lastUpdate'));
            $("#nameStudent").text(data.rslt.obj.data('author'));

            playerRecorders['playerRecorderStudent'].resetNetStreams();
            playerRecorders['playerRecorderStudent'].addURIToNetStreams(data.rslt.obj.data('recURI'));
            if (data.rslt.obj.data('mastertrack') != '')
            {
                playerRecorders['playerRecorderStudent'].addURIToNetStreams(data.rslt.obj.data('mastertrack'));
            }
        }
    })
    .bind("deselect_all.jstree", function (event, data) {
        //DESELECT ALL NODES EVENT
        $("#deleteRecordings").attr("disabled","disabled");
        $("#deleteRecordings").addClass("btnDisabled");
        $("#downloadRecording").attr("disabled","disabled");
        $("#downloadRecording").addClass("btnDisabled");
    }).
    bind("refresh.jstree", function (event, data) {
        //After refresh select the previous elem
        setTimeout('selectPreviousNode()', 100);
        
    });
    
    //SEARCH functionnality
    $("#searchRecordings").keyup(function(){
        $("#recordings").jstree("search",$(this).val());
    });
    $("#searchRecordings").change(function(){
        $("#recordings").jstree("search",$(this).val());
    });
    //Refresh
    $("#refreshHistory").click(function(){
        
        $("#recordings").jstree("refresh");
        
    });
    
    //DELETE a Recording
    $("#deleteRecordings").click(function(){
        
        var canBeDeleted = false;
        var elemType = $(".jstree-clicked","#recordings").parent().data('type');
        
        if(elemType == 'record' || (typeof(teacherMode) != undefined && teacherMode == true && elemType == 'feedback'))
        {
            canBeDeleted = true;
        }
        
        if(canBeDeleted && $(".jstree-clicked","#recordings").length > 0 )
        {
            
            $("#dialogInfo").text(lblConfirmDelete+'"'+$(".jstree-clicked","#recordings").parent().data('title')+'"?');
            $("#dialogInfo").dialog({
                title: titleConfirm,
                resizable: false,
                height:180,
                modal: true,
                buttons: [
                {
                    text: deleteRecord,
                    click: function() {
                        
                        $.post(
                            "ajax.recording.php?id="+activityid+"&del=true",
                            {
                                recordingid: $(".jstree-clicked","#recordings").parent().data('recordingid')
                            },
                            function(data)
                            {
                                res = jQuery.parseJSON(data);
                                if(res.success)
                                {
                                    $("#newRecording").removeAttr('disabled');
                                    $("#newRecording").removeClass('btnDisabled');
                                    $("#refreshHistory").click();
                                    $("#newRecording").click();
                                    if(typeof(teacherMode) != 'undefined' && teacherMode == true)
                                    {
                                        $("#gradeInactif").show();
                                        
                                        playerRecorders['playerRecorderStudent'].resetNetStreams();
                                        playerRecorders['playerRecorderTeacher'].resetNetStreams();
                                        playerRecorders['playerRecorderStudent'].setPlayerMode(0);
                                        playerRecorders['playerRecorderTeacher'].setPlayerMode(0);
                                        
                                        $("#privateNote").val('');
                                        $("#gradeInput").val(0);
                                        $("#gradeSlider").slider('value', 0);
    
                                        $("#imgStudent").attr('src',defaultUserPicture);
                                        $("#recordAgoStudent").text('');
                                        $("#nameStudent").text('');
                                        $("#imgTeacher").attr('src',defaultUserPicture);
                                        $("#recordAgoTeacher").text('');
                                        $("#nameTeacher").text('');
                                        
                                        $("#titleRecording").attr("readonly","readonly");
                                        $("#descriptionRecording").attr("readonly","readonly");
                                        $("#titleRecording").val('');
                                        $("#descriptionRecording").val('');
                                        
                                        $("#titleFeedback").attr("readonly","readonly");
                                        $("#descriptionFeedback").attr("readonly","readonly");
                                        $("#submitFeedback").attr("disabled");
                                        $("#submitFeedback").addClass("btnDisabled");
                                        $("#titleFeedback").val('');
                                        $("#descriptionFeedback").val('');
                                    }
                                }
                                else
                                {
                                    $("#dialogInfo").text(res.message);
                                    $("#dialogInfo").dialog({
                                        height: 140,
                                        modal: true,
                                        title: errorTitle,
                                        buttons:[]
                                    });
                                }
                            }
                            );
                        $( this ).dialog( "close" );
                    }
                },
                {
                    text: cancel,
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }
                ]
            });
        }
    });
    
    //DOWNLOAD a Recording
    $("#downloadRecording").click(function(){
        var eData = $(".jstree-clicked","#recordings").parent();
        if(eData.data('type') == 'user')
        {
            var p = '';
            var n = '';
            $('li', $(".jstree-clicked","#recordings").parent()).each(function(){
                p = p+';'+$(this).data('recURI');
                n = n+';'+$(this).data('downloadName');
            });
            
            p = p.substr(1);
            n = n.substr(1);
            
            window.location.href = urlZipDownload+'&p='+p+'&n='+n+'&z='+eData.data('downloadName');
        }
        else
        {
            window.location.href = urlDownload+'&p='+eData.data('recURI')+'&n='+eData.data('downloadName');
        }
    });
    
    
    //Save the current recording - STUDENT
    $("#submitRecording").click(function(){
        
        //Submit only if the recording exists
        if (userRecordURI != '')
        {
            $("#ajaxSubmitRecording").show();
            $.post(
                "ajax.recording.php?id="+activityid+"&add=true",
                {
                    title: $("#titleRecording").val(),
                    message: $("#descriptionRecording").val(),
                    path: userRecordURI
                },
                function(data)
                {
                    res = jQuery.parseJSON(data);
                    if(res.success != false)
                    {
                        if(onlyOneRecording)
                        {
                            $("#newRecording").attr('disabled', 'disabled');
                            $("#newRecording").addClass('btnDisabled');
                        }
                        selectedElem = res.success;
                        $("#refreshHistory").click();
                    }
                    else
                    {
                        $("#dialogInfo").text(res.message);
                        $("#dialogInfo").dialog({
                            height: 140,
                            modal: true,
                            title: errorTitle,
                            buttons:[]
                        });
                    }
                    $("#ajaxSubmitRecording").hide();
                }
                );
        }
        else
        {
            $("#dialogInfo").text(recordingRequired);
            $("#dialogInfo").dialog({
                height: 140,
                modal: true,
                title: errorTitle,
                buttons:[]
            });
        }
    });
    
    //Save the current recording - TEACHER FEEDBACK
    $("#submitFeedback").click(function(){
        
        //Submit only if the recording exists
        if (userRecordURI != '')
        {
            $("#ajaxSubmitFeedback").show();
            $.post(
                "ajax.recording.php?id="+activityid+"&add=true&submission="+$(".jstree-clicked","#recordings").parent().data('recordingid'),
                {
                    title: $("#titleFeedback").val(),
                    message: $("#descriptionFeedback").val(),
                    path: userRecordURI
                },
                function(data)
                {
                    res = jQuery.parseJSON(data);
                    if(res.success)
                    {
                        selectedElem = res.success;
                        $("#refreshHistory").click();
                    }
                    else
                    {
                        $("#dialogInfo").text(res.message);
                        $("#dialogInfo").dialog({
                            height: 140,
                            modal: true,
                            title: errorTitle,
                            buttons:[]
                        });
                    }
                    $("#ajaxSubmitFeedback").hide();
                }
                );
        }
        else
        {
            $("#dialogInfo").text(recordingRequired);
            $("#dialogInfo").dialog({
                height: 140,
                modal: true,
                title: errorTitle,
                buttons:[]
            });
        }
    });
    
    //Save the current grade - TEACHER 
    $("#submitGrade").click(function(){
        
        $("#ajaxSubmitGrade").show();
        $.post(
            "ajax.recording.php?id="+activityid+"&grade=true",
            {
                grade: $("#gradeInput").val(),
                privateNotes: $("#privateNote").val(),
                studentid: $(".jstree-clicked","#recordings").parent().data('studentid')
            },
            function(data)
            {
                res = jQuery.parseJSON(data);
                if(res.success)
                {
                    $("#refreshHistory").click();
                }
                else
                {
                    $("#dialogInfo").text(res.message);
                    $("#dialogInfo").dialog({
                        height: 140,
                        modal: true,
                        title: errorTitle,
                        buttons:[]
                    });
                }
                $("#ajaxSubmitGrade").hide();
            }
            );
        
    });
    
    /**
     * Actions for the actions buttons on the top of the page
     */
    
    //Create a new recording
    $("#newRecording").click(function(){
        if($("#newRecording").attr('disabled') != 'disabled')
        {
            selectedElem = null;
            selectedUser = null;

            $("#titleRecording").attr("readonly",false);
            $("#descriptionRecording").attr("readonly",false);
            $("#submitRecording").removeAttr("disabled");
            $("#submitRecording").removeClass("btnDisabled");
            $("#recordings").jstree('deselect_all');

            $("#titleRecording").val(defaultTitleNewRecording);
            $("#descriptionRecording").val('');

            playerRecorders['playerRecorderStudent'].setPlayerMode(2);
            userRecordURI = '';
            playerRecorders['playerRecorderStudent'].refreshRecordFileName();
            playerRecorders['playerRecorderStudent'].resetNetStreams();
            if(urlmasterTrack != '')
            {
                playerRecorders['playerRecorderStudent'].addURIToNetStreams(urlmasterTrack, true);
            }
        }
    });
    
    $('#micConfig').click(function(){
        toggleDisplayOptions();
    });
    
    
    //TEACHER FUNCTIONALITIES
    $('#gradeSlider').slider({
        range: "min",
        value: 0,
        min: 0,
        max: 100,
        slide: function( event, ui ) {
            $( "#gradeInput" ).val(ui.value );
        }
    });
    
    //If the gradebook is used, delete the message
    if(typeof(useGradebook) != 'undefined' && useGradebook)
    {
        $("#gradeInactif").text('');
    }
    
    //ACTIVITY EDIT MODE
    
    $("#loadPreviousMastertrack").click(function()
    {
        playerRecorders['playerRecorderMastertrack'].setPlayerMode(1);
        getUserRecordURI(existingMastertrackURI);
        playerRecorders['playerRecorderMastertrack'].resetNetStreams();
        playerRecorders['playerRecorderMastertrack'].addURIToNetStreams(userRecordURI);
        return false;
    });
    
    $("#newMastertrack").click(function()
    {
        playerRecorders['playerRecorderMastertrack'].setPlayerMode(2);
        userRecordURI = '';
        playerRecorders['playerRecorderMastertrack'].refreshRecordFileName();
        playerRecorders['playerRecorderMastertrack'].resetNetStreams();
        return false;
    });
    
    $("#raiseHand").click(function(){
       
       $(this).show('highlight');
       $.getJSON(
            urlLive+"?id="+activityid
                +"&event=raise_hand"
                +"&rand="+Math.random(),
            function(data)
            {

            });
    });
    
    if(typeof(secondsRefreshHistory) != 'undefined')
    {
        //SET TIMERS FOR UPDATE CONTENT OF THE HISTORY
        setTimeout('autoRefreshHistory()', secondsRefreshHistory);
    }
    
    if(typeof(teacherMode) == 'undefined' && typeof(secondsRefreshStudentView) != 'undefined')
    {
        setTimeout('autoRefreshStudentLive()', secondsRefreshStudentView);
    }
});

