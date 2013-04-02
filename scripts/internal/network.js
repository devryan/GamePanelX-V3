/*
*
* Network Servers
*
*/

/*
// Show create dialog
function network_show_create()
{
    $.ajax({
        url: ajaxURL,
        data: 'a=network_create_form',
        success:function(html){
            // Show modal with info
            $("#modal").html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});
        }
    });
}
*/

// Show the add IP Address dialog
function network_show_addip(netID)
{
    if(netID == "")
    {
        alert("No Network ID provided");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=network_actions&do=show_addip&id='+netID,
        success:function(html){
            // Show modal with info
            $("#modal").css('height','120').html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); $("#modal").css('height',''); }); 
            }});
            
            setTimeout("$('#new_ip').blur()", 200); // Don't focus on IP
        }
    });
}

// Add IP Address to a parent server
function network_addip(netID)
{
    var newIP = $('#new_ip').val();
    
    if(newIP == "")
    {
        alert("Please specify an IP Address.");
        return false;
    }
    else if(netID == "")
    {
        alert("No Network ID provided!");
        return false;
    }
    
    
    $.ajax({
        url: ajaxURL,
        type: "POST",
        data: 'a=network_actions&do=addip&id='+netID+'&ip='+newIP,
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                $.modal.close();
                
                $('#noips_row').hide();
                $('#netip_table').append('<tr style="background:#FFF;"><td>'+newIP+'</td><td>&nbsp;</td></tr>');
            }
            else
            {
                $.modal.close();
                infobox('f','Failed: '+html);
            }
        },
        error:function(a,b,c){
            $.modal.close();
            infobox('f','Error: '+b+', '+c);
        }
    });
}

// Create network server
function network_create()
{
    var ipAddr      = $('#add_ip').val();
    var isLocal     = $('#add_is_local').val();
    var location    = $('#add_location').val();
    var os          = $('#add_os').val();
    var dataCenter  = $('#add_datacenter').val();
    var loginUser   = $.base64.encode($('#add_login_user').val());
    var loginPass   = $.base64.encode($('#add_login_pass').val());
    var loginPort   = $.base64.encode($('#add_login_port').val());
    // var homedir     = $.base64.encode($('#homedir').val());
    var datastr     = "&do=create&ip="+ipAddr+"&is_local="+isLocal+"&location="+location+"&os="+os+"&datacenter="+dataCenter+"&login_user="+loginUser+"&login_pass="+loginPass+"&login_port="+loginPort;
    
    if(ipAddr == "")
    {
        alert("Please specify an IP Address.");
        $('#add_ip').focus();
        return false;
    }
    
    // Required for remote
    if(isLocal == "0" && $('#add_login_user').val() == "") {
        alert("No remote username provided!");
        return false;
    }
    else if(isLocal == "0" && $('#add_login_pass').val() == ""){
        alert("No remote password provided!");
        return false;
    }
    else if(isLocal == "0" && $('#add_login_port').val() == ""){
        alert("No remote port provided!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        type: "POST",
        data: 'a=network_actions'+datastr,
        beforeSend:function(){
            infobox('i','Creating, please wait ...');
        },
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                
                $('#modal').scrollTop(0);
                setTimeout("$.modal.close();mainpage('network','')", 1000);
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

// Create template
function network_edit(netID)
{
    if(netID == "")
    {
        alert("No Network ID provided");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=network_edit&id='+netID,
        success:function(html){
            // Show modal with info
            $("#modal").html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});
        }
    });
}

// Save network edits
function network_save(netID)
{
    if(netID == "")
    {
        alert("No Network ID given!");
        return false;
    }
    
    var ipAddr      = $('#edit_ip').val();
    var isLocal     = $('#edit_is_local').val();
    var location    = $('#edit_location').val();
    var os          = $('#edit_os').val();
    var dataCenter  = $('#edit_datacenter').val();
    var loginUser   = $.base64.encode($('#edit_login_user').val());
    var loginPass   = $.base64.encode($('#edit_login_pass').val());
    var loginPort   = $.base64.encode($('#edit_login_port').val());
    var homedir     = $.base64.encode($('#edit_homedir').val());
    var datastr     = "&id="+netID+"&do=save&ip="+ipAddr+"&is_local="+isLocal+"&location="+location+"&os="+os+"&datacenter="+dataCenter+"&login_user="+loginUser+"&login_pass="+loginPass+"&login_port="+loginPort+"&homedir="+homedir;
    
    $.ajax({
        url: ajaxURL,
        type: "POST",
        data: 'a=network_actions'+datastr,
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                //$('#modal').scrollTop(0);
                setTimeout("$.modal.close();mainpage('network','')", 1000);
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

// Confirm deleting
function network_confirm_delete(netID)
{
    if(netID == "")
    {
        alert("No Network ID given");
        return false;
    }
    
    var answer = confirm("Are you sure?\n\nDelete this network server, all templates and all IP Addresses with it?");
    
    if(answer) network_delete(netID);
    else return false;
}

// Delete
function network_delete(netID)
{
    if(netID == "")
    {
        alert("No Network ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=network_actions&id='+netID+"&do=delete",
        success:function(html){
            $.modal.close();
            
            if(html == 'success')
            {
                infobox('s','');
                setTimeout("mainpage('network','')", 1000);
            }
            else
            {
                infobox('f','Failed: '+html);
            }
        }
    });
}

// Confirm deleting an IP Address
function network_confirm_delete_ip(netID)
{
    if(netID == "")
    {
        alert("No Network ID given");
        return false;
    }
    
    var answer = confirm("Are you sure?\n\nDelete this IP Address?");
    
    if(answer) network_delete_ip(netID);
    else return false;
}

// Delete an IP Address
function network_delete_ip(netID)
{
    if(netID == "")
    {
        alert("No Network ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=network_actions&id='+netID+"&do=delete_ip",
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
            
            // Remove IP row
            $('#ip_'+netID).fadeOut();
        }
    });
}
