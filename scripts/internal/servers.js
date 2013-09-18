/*
*
* Gameserver Tabs
*
*/

// Load server info into box
function server_tab_info(srvID)
{
    if(srvID == "")
    {
        alert("No server ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_info&id='+srvID,
        success:function(html){
            $('#panel_center').hide().html(html).fadeIn('fast');
        }
    });
}

// Load server settings tab
function server_tab_settings(srvID)
{
    if(srvID == "")
    {
        alert("No server ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_settings&id='+srvID,
        success:function(html){
            $('#panel_center').hide().html(html).fadeIn('fast');
        }
    });
}

// Load server files tab
function server_tab_files(srvID)
{
    if(srvID == "")
    {
        alert("No server ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_files&id='+srvID,
        beforeSend:function(){
            infobox('i','Loading ...');
        },
        success:function(html){
            // Hide infobox
            $('.infobox').hide();
            
            $('#panel_center').hide().html(html).fadeIn('fast');
        }
    });
}

// Load server startup tab
function server_tab_startup(srvID)
{
    if(srvID == "")
    {
        alert("No server ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_startup&id='+srvID,
        success:function(html){
            $('#panel_center').hide().html(html).fadeIn('fast');
        }
    });
}


// Submit server settings tab
function srv_settings_save(serverID)
{
    if(serverID == "")
    {
        alert("No server ID given!");
        return false;
    }
    
    var srvdescr    = encodeURIComponent($('#desc').val());
    var userID      = encodeURIComponent($('#userid').val());
    var netID       = encodeURIComponent($('#ip').val());
    var updatecmd   = encodeURIComponent($('#update_cmd').val());
    var cmd         = encodeURIComponent($('#cmd').val());
    var startup     = encodeURIComponent($('#startup_type').val());
    var port        = encodeURIComponent($('#port').val());
    var workingDir  = encodeURIComponent($('#working_dir').val());
    var pidFile     = encodeURIComponent($('#pid_file').val());
    var maxpl       = encodeURIComponent($('#maxplayers').val());
    var hostn       = encodeURIComponent($('#hostname').val());
    var map         = encodeURIComponent($('#map').val());
    var rcon        = encodeURIComponent($('#rcon').val());
    var sv_passw    = encodeURIComponent($('#sv_password').val());
    
    var datastr     = "&id="+serverID+"&srvdescr="+srvdescr+"&userid="+userID+"&ip="+netID+"&port="+port+"&working_dir="+workingDir+"&pid_file="+pidFile+"&startup="+startup+"&update_cmd="+updatecmd+"&cmd="+cmd+"&maxplayers="+maxpl+"&hostname="+hostn+"&map="+map+"&rcon="+rcon+"&sv_password="+sv_passw;
    
    // a=server_settings_save
    $.ajax({
        url: ajaxURL,
        type: "POST",
        data: 'a=server_actions&do=settings_save'+datastr,
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}





















/*
*
* Startup Items / CMD Line Editor
*
*/

function server_save_startup(gameID)
{
    if(gameID == "")
    {
        alert("No game ID provided!");
        return false;
    }
    
    var data = "";
    var sortList  = $('#sort_list').val();
    //var startType = $('#startup').val();
    //var startType = $('input[name=startup]').val();
    
    if($('#smp').is(':checked')) var startType = 'smp';
    else var startType = 'str';
    
    // Loop through all items and their values
    $('input').each(function(index,value) {
        var thisID    = value.id;
        var thisValue = encodeURIComponent(value.value);
        
        // Update checkboxes
        if($('#'+thisID).is(':checked'))
        {
            $('#'+thisID).val('1');
            thisValue = '1';
        }
        
        data += "&"+thisID+"="+thisValue;
    });
    
    // a=server_startup_save
    $.ajax({
        url: ajaxURL,
        data: "a=server_actions&do=startup_save&id="+gameID+data+"&start_type="+startType+"&sort_list="+sortList,
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
        },
        error:function(a,b,c){
            alert("Error: "+a+", b: "+b+", c: "+c);
        }
    });
}

// Add a startup item
function server_add_startup()
{
    // Add + 1 to current num, store new
    var newAmount   = parseFloat($('#newitemnum').val()) + 1;
    $('#newitemnum').val(newAmount);
    
    $('#strtbl').append('<tbody id="sortitm_'+newAmount+'" class="sortable"><tr><td width="200"><div class="str_itm_ed"><input type="text" class="inputs" id="additm_'+newAmount+'" value="" /></div></td><td width="200"><div class="str_val_ed"><input type="text" class="inputs" id="addval_'+newAmount+'" value="" /></div></td><td width="120" style="cursor:default;"><input type="checkbox" id="addusred_'+newAmount+'" value="0" /></td></tr></tbody>');
}

// Delete a startup item
function server_del_startup(startID,serverID)
{
    if(startID == "" || serverID == "")
    {
        alert("No startup ID or server ID provided!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: "a=server_actions&do=startup_del_item&id="+startID+"&serverid="+serverID,
        success:function(html){
            if(html == 'success')
            {
                //infobox('s','');
                $('#sortitm_'+startID).fadeOut('slow');
            }
            else
            {
                infobox('f','Failed: '+html);
            }
        },
        error:function(a,b,c){
            alert("Error: "+a+", b: "+b+", c: "+c);
        }
    });
}

// Confirm deleting a startup item
function server_confirm_del_startup(startID,serverID)
{
    var answer = confirm("Are you sure?\n\nDelete this startup item?");
    
    if(answer) server_del_startup(startID,serverID)
    else return false;
}













/*
*
* Server Status Changes
*
*/

// Restart a gameserver
function server_restart(srvID)
{
    if(srvID == "")
    {
        alert("No server ID given!");
        return false;
    }
    
    // a=server_do_restart
    $.ajax({
        url: ajaxURL,
        data: 'a=server_actions&do=restart&id='+srvID,
        beforeSend:function(){
            infobox('i','Restarting ...');
        },
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
            
            // Refresh status
            setTimeout("server_tab_info("+srvID+")",3000);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

// Stop a gameserver
function server_stop(srvID)
{
    if(srvID == "")
    {
        alert("No server ID given!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_actions&do=stop&id='+srvID,
        beforeSend:function(){
            infobox('i','Stopping ...');
        },
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
            
            // Refresh status
            setTimeout("server_tab_info("+srvID+")",3000);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

// Update a server
function server_update(srvID)
{
    if(srvID == "")
    {
        alert("No server ID given!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_actions&do=update&id='+srvID,
        beforeSend:function(){
            infobox('i','Starting update ...');
            
            // Refresh status
            setTimeout("server_tab_info("+srvID+")",2000);
        },
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}





/*
*
* Server Creation
*
*/

// Create Form
function server_show_create()
{
    $.ajax({
        url: ajaxURL,
        data: 'a=server_create_form',
        success:function(html){
            // Show modal with info
            $("#modal").html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});
        }
    });
}

// Create
function server_create()
{
    var gameID    = $('#create_game').val();
    var netID     = $('#create_network').val();
    var ownerID   = $('#create_owner').val();
    var port      = $('#create_port').val();
    var descr     = $('#create_desc').val();
    
    $.ajax({
        url: ajaxURL,
        data: "a=server_actions&do=create&gameid="+gameID+"&netid="+netID+"&ownerid="+ownerID+"&port="+port+"&desc="+descr,
        success:function(html){
            if(html == 'success')
            {
                $.modal.close();
                mainpage('servers','');
            }
            else
            {
                $('#create_info').hide().html('<b>Failed:</b> '+html).fadeIn();
            }
        },
        error:function(a,b,c){
            alert('Error: '+b+', '+c);
        }
    });
}



// Get default port for server creation
function server_getport()
{
    var gameID    = $('#create_game').val();
    
    if(gameID == "")
    {
        //alert("No game given!");
        $('#create_port').val('');
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_actions&do=create_getport&gameid='+gameID,
        success:function(html){
            $('#create_port').val(html);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}



// Get all templates available for this chosen Network Server
function server_create_gettpls()
{
    var netID = $('#create_network').val();
    
    if(netID == "")
    {
        //alert("No network server selected!");
        $('#create_port').val('');
        $('#tpl_area').html('Select a Network Server first');
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_actions&do=create_gettpls&netid='+netID,
        beforeSend:function(){
            $('#tpl_area').html('<i>Loading ...</i>');
        },
        success:function(html){
            $('#tpl_area').html(html);
            
            try {
                $("body select").msDropDown();
            } catch(e) {
                alert(e.message);
            }
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}



// Get PID(s), CPU and Memory info for a server
function server_getinfo(srvID)
{
    if(srvID == "")
    {
        alert("No server ID given!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_actions&do=getinfo&id='+srvID,
        dataType: 'json',
        beforeSend:function(){
            $('#gamecpu').html('<i>Connecting ...</i>');
        },
        success:function(html){
            // Example: {"respid":"10562","ppid":"10569","cpid":"10574","cpu":"0.0","mem":"3.1"}
            // Normal response
            if(html.respid)
            {
                var srvResPID     = html.respid;
                var srvParentPID  = html.ppid;
                var srvCPU        = html.cpu;
                var srvMemory     = html.mem;
                
                if(html.cpid) var srvChildPID = ', Child PID: '+html.cpid;
                else var srvChildPID = '';
                
                $('#gamecpu').hide().html('<b>CPU:</b> '+srvCPU+'%, <b>Memory:</b> '+srvMemory+'%').fadeIn('fast');
                $('#gamepids').hide().html('Restart PID: '+srvResPID+', Server PID: '+srvParentPID+srvChildPID).fadeIn('fast');
            }
            // Bash script error output
            else if(html.error)
            {
                $('#gamecpu').html('Error: ('+html.error+')');
                $('#gamepids').hide();
            }
            else
            {
                $('#gamecpu').html('Unable to get system info ('+html+')');
                $('#gamepids').hide();
            }
        },
        error:function(a,b,c){
            $('#gamecpu').html('Unable to get system info: '+a+', b: '+b+', c: '+c);
            $('#gamepids').hide();
        }
    });
}

// Confirm deleting a server
function confirm_server_delete(srvID)
{
    if(srvID == "")
    {
        alert("No server ID given!");
        return false;
    }
    
    var answer = confirm("Are you sure?\n\nDelete this server and ALL its files permanently?");
    
    if(answer) server_delete(srvID);
    else return false;
}

// Delete server
function server_delete(srvID)
{
    if(srvID == "")
    {
        alert("No server ID given!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_actions&do=delete&id='+srvID,
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                setTimeout("mainpage('servers','')",1000);
            }
            else
            {
                infobox('f','Error: '+html);
            }
            
            
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}




// Get server output
function server_getoutput(srvID)
{
    if(srvID == "")
    {
        alert("No server ID given!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_actions&do=getoutput&id='+srvID,
        beforeSend:function(){
            $('#srv_outputrow,#srv_sendrow').show();
            $('#srv_outputbox').html('Loading ...');
            $('#send_cmd').focus();
        },
        success:function(html){
            // $('#box_servers_content').append(html);
            $('#srv_outputbox').html(html);
            $('#srv_outputbox').animate({scrollTop: $('#srv_outputbox').prop("scrollHeight")}, 300);
            $('html,body').animate({scrollTop: $('html,body').prop("scrollHeight")}, 300);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}



// Send a command via GNU Screen
function server_send_screen_cmd(srvID)
{
    if(srvID == "")
    {
        alert("No server ID given!");
        return false;
    }
    var cmd = $('#send_cmd').val();
    if(cmd == "") return false;
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_actions&do=sendscreencmd&id='+srvID+'&cmd='+cmd,
        beforeSend:function(){
            infobox('i','Sending ...');
        },
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                setTimeout("server_getoutput("+srvID+")", 1000);
            }
            else
            {
                infobox('f','Error: '+html);
            }
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}



// Multi server query (with JSON input)
function multi_query()
{
    var jsonArr = JSON.parse($('#json_hid').val());
    var rawJSON = $('#json_hid').val();
    
    $.each(jsonArr, function() {
        var srvID = this.id;
        
        // console.log("Host: "+this.host);
        $('#statustd_'+srvID).html('<i>Loading ...</i>');
    });
    
    $.ajax({
        url: ajaxURL,
        data: 'a=server_actions&do=multi_query_json&json='+rawJSON,
        success:function(html){
            //alert("HTML: "+html);
            
            // $('body').append("HTML: "+html);
            var resJSON = JSON.parse(html);
            
            $.each(resJSON, function() {
                var srvID     = this.id;
                var srvStatus = this.status;
                
                $('#statustd_'+srvID).html(srvStatus);
            });
        },
        error:function(a,b,c){
            alert('Error: '+b+', '+c);
        }
    });
}
