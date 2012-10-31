/*
*
* Game Configuration
*
*/

// Load server settings tab
function game_tab_startup(gameID)
{
    if(gameID == "")
    {
        alert("No game ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=games_startup&id='+gameID,
        success:function(html){
            $('#panel_center').hide().html(html).fadeIn('fast');
        }
    });
}

// Save
function game_save(gameID)
{
    if(gameID == "")
    {
        alert("No game ID given!");
        return false;
    }
    
    var port              = encodeURIComponent($('#port').val());
    var game_name         = encodeURIComponent($('#name').val());
    var game_intname      = encodeURIComponent($('#intname').val());
    var game_working_dir  = encodeURIComponent($('#working_dir').val());
    var game_pid_file     = encodeURIComponent($('#pid_file').val());
    var game_descr        = encodeURIComponent($('#desc').val());
    var game_inst_mirrors = encodeURIComponent($('#install_mirrors').val());
    var game_installcmd   = encodeURIComponent($('#install_cmd').val());
    var game_updatecmd    = encodeURIComponent($('#update_cmd').val());
    var game_simplecmd    = encodeURIComponent($('#simplecmd').val());
    var game_bannedchars  = encodeURIComponent($('#banned_chars').val());
    var game_steam        = encodeURIComponent($('#steam_based').val());
    var game_steam_name   = encodeURIComponent($('#steam_name').val());
    var game_query_eng    = encodeURIComponent($('#query_engine').val());
    
    if(port == "") { alert("You must fill out the Port field"); return false; }
    else if(game_name == "") { alert("You must fill out the Name field"); return false; }
    else if(game_intname == "") { alert("You must fill out the Internal Name field"); return false; }
    else if(game_simplecmd == "") { alert("You must fill out the CMD field"); return false; }
    
    // Check internal regex
    var intRegex = new RegExp(/^[a-zA-Z0-9-_]+$/i);
    if(!intRegex.test(game_intname))
    {
        alert("Invalid Internal Name specified!  Only letters, numbers, - and _ are accepted.");
        return false;
    }
    
    var datastr   = "&id="+gameID+"&do=save&desc="+game_descr+"&port="+port+"&name="+game_name+"&intname="+game_intname+"&working_dir="+game_working_dir+"&pid_file="+game_pid_file+"&install_mirrors="+game_inst_mirrors+"&install_cmd="+game_installcmd+"&update_cmd="+game_updatecmd+"&simplecmd="+game_simplecmd+"&banned_chars="+game_bannedchars+"&is_steam="+game_steam+"&steam_name="+game_steam_name+"&query_engine="+game_query_eng;
    
    $.ajax({
        url: ajaxURL,
        type: "POST",
        data: 'a=games_actions'+datastr,
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

// Create new game
function game_add()
{
    var port              = encodeURIComponent($('#add_port').val());
    var game_name         = encodeURIComponent($('#add_name').val());
    var game_intname      = encodeURIComponent($('#add_intname').val());
    var game_working_dir  = encodeURIComponent($('#add_working_dir').val());
    var game_pid_file     = encodeURIComponent($('#add_pid_file').val());
    var game_descr        = encodeURIComponent($('#add_desc').val());
    var game_updatecmd    = encodeURIComponent($('#add_update_cmd').val());
    var game_simplecmd    = encodeURIComponent($('#add_simplecmd').val());
    var game_steam        = encodeURIComponent($('#add_steam_based').val());
    var game_steam_name   = encodeURIComponent($('#steam_name').val());
    var game_query_eng    = encodeURIComponent($('#add_query_engine').val());
    
    // Check internal regex
    var intRegex = new RegExp(/^[a-zA-Z0-9-_]+$/i);
    if(!intRegex.test(game_intname))
    {
        alert("Invalid Internal Name specified!  Only letters, numbers, - and _ are accepted.");
        return false;
    }
    
    if(port == "") { alert("You must fill out the Port field"); return false; }
    else if(game_name == "") { alert("You must fill out the Name field"); return false; }
    else if(game_intname == "") { alert("You must fill out the Internal Name field"); return false; }
    else if(game_simplecmd == "") { alert("You must fill out the CMD field"); return false; }
    
    var datastr   = "&do=add&desc="+game_descr+"&port="+port+"&name="+game_name+"&intname="+game_intname+"&working_dir="+game_working_dir+"&pid_file="+game_pid_file+"&update_cmd="+game_updatecmd+"&simplecmd="+game_simplecmd+"&is_steam="+game_steam+"&steam_name="+game_steam_name+"&query_engine="+game_query_eng;
    
    $.ajax({
        url: ajaxURL,
        type: "POST",
        data: 'a=games_actions'+datastr,
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                
                setTimeout("mainpage('games','')", 1000);
            }
            else
            {
                infobox('f','Failed: '+html);
            }
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

// Confirm deleting a game
function game_confirm_del(gameID)
{
    if(gameID == "")
    {
        alert("No game ID provided!");
        return false;
    }
    
    var answer = confirm("Are you sure?\n\nDelete this game?");
    
    if(answer) game_del(gameID);
    else return false;
}

// Delete a game
function game_del(gameID)
{
    if(gameID == "")
    {
        alert("No game ID provided!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=games_actions&do=delete&id='+gameID,
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                
                setTimeout("mainpage('games','')", 1000);
            }
            else
            {
                infobox('f','Failed: '+html);
            }
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

function games_save_startup(gameID)
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
        data: "a=games_actions&do=startup_save&id="+gameID+data+"&start_type="+startType+"&sort_list="+sortList,
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
        },
        error:function(a,b,c){
            alert("Error: "+a+", b: "+b+", c: "+c);
        }
    });
}

// Delete a startup item
function games_del_startup(startID,serverID)
{
    if(startID == "" || serverID == "")
    {
        alert("No startup ID or server ID provided!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: "a=games_actions&do=startup_del_item&id="+startID+"&serverid="+serverID,
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
function games_confirm_del_startup(startID,serverID)
{
    var answer = confirm("Are you sure?\n\nDelete this default startup item?");
    
    if(answer) games_del_startup(startID,serverID)
    else return false;
}



function game_showcreate()
{
    $.ajax({
        url: ajaxURL,
        data: 'a=game_actions&do=show_creategame',
        success:function(html){
            // Show modal with info
            $("#modal").html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});
        }
    });
}
