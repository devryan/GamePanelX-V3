/*
*
* Server Templates
*
*/

// Create template
function template_show_create(tplID)
{
    if(tplID == "")
    {
        alert("No template ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=template_create_form&id='+tplID,
        success:function(html){
            /*
            // Show modal with info
            $("#modal").html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});
            */
            $('#panel_center').hide().html(html).fadeIn('fast');
        }
    });
}

// Edit Template
function template_edit(tplID)
{
    if(tplID == "")
    {
        alert("No template ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=template_edit&id='+tplID,
        success:function(html){
            // Show modal with info
            $("#modal").html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});
        }
    });
}

// Save template edits
function template_save(tplID,netID)
{
    if(tplID == "" || netID == "")
    {
        alert("No Template ID or Network ID given!");
        return false;
    }
    
    var desc      = encodeURIComponent($('#desc').val());
    var isDefault = $('#is_default').val();
    var datastr   = "&id="+tplID+"&netid="+netID+"&do=save&desc="+desc+"&default="+isDefault;
    
    $.ajax({
        url: ajaxURL,
        type: "POST",
        data: 'a=template_actions'+datastr,
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
            
            // Update page
            //mainpage('templates',"+tplID+")
            setTimeout("$.modal.close();", 1000);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

// Confirm deleting Template
function template_confirm_delete(tplID)
{
    if(tplID == "")
    {
        alert("No template ID given");
        return false;
    }
    
    var answer = confirm("Are you sure?\n\nDelete this template?");
    
    if(answer) template_delete(tplID);
    else return false;
}

// Delete Template
function template_delete(tplID)
{
    if(tplID == "")
    {
        alert("No template ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=template_actions&id='+tplID+"&do=delete",
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
            
            // Close modal 
            $.modal.close();
            // page_templates(); // and redirect to templates
            
            // Remove row
            $('#tpl_'+tplID).fadeOut('slow');
        }
    });
}

// Create Template
function template_create()
{
    var gameID    = $('#create_tpl_game').val();
    var netID     = $('#create_tpl_network').val();
    var filePath  = $('#create_tpl_file_path').val();
    var tplisDef  = $('#create_tpl_is_default').val();
    var descript  = $('#create_tpl_desc').val();
 
    // Check empty
    if(gameID == "")
    {
        alert("Please choose a Game!");
        return false;
    }
    else if(netID == "")
    {
        alert("Please choose a Network Server");
        return false;
    }
    
    
    $.ajax({
        url: ajaxURL,
        data: "a=template_actions&do=create&gameid="+gameID+"&netid="+netID+"&default="+tplisDef+"&file_path="+filePath+"&description="+descript,
        beforeSend:function(){
            // Show progress
            infobox('i','<i>Starting ...</i>');
        },
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                
                // Refresh status
                setTimeout("mainpage('templates',"+gameID+")", 1000);
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

// Check one-time if running templates are completed
function template_checkdone()
{
    $.ajax({
        url: ajaxURL,
        data: 'a=template_actions&do=checkdone',
        success:function(html){
            // Only print if erroneous
            if(html == 'success')
            {
                $('#results').html('Templates OK');
            }
            // Updated a status
            else if(html == 'updated')
            {
                $('#results').html('Updated!');
                
                var tplID = $('#tplid').val();
                setTimeout("mainpage('templates',"+tplID+")", 1000);
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

function tpl_check_statuses()
{
    $('#results').append('Checking...<br>');
    
    $.ajax({
        url: ajaxURL,
        data:"a=template_status",
        dataType:"JSON",
        beforeSend:function(){
            $('#results').html('<b>RT:</b> Updating ...');
        },
        success:function(html){
            $('#results').html('<b>RT:</b> Idle.');
            
            // Loop through options
            $.each(html, function(index, value) {
                thisID            = value.id;
                thisSteamPercent  = value.steam_percent;
                thisStatus        = value.status;
                
                if(thisStatus == 'steam_running') $('#status_'+thisID).html('<font color="blue">Steam Install: '+thisSteamPercent+'%</font>');
                else if(thisStatus == 'running')  $('#status_'+thisID).html('<font color="orange">Running</font>');
                else if(thisStatus == 'complete') $('#status_'+thisID).html('<font color="green">Complete</font>');
                else if(thisStatus == 'failed')   $('#status_'+thisID).html('<font color="red">Failed</font>');
                else $('#status_'+thisID).html('<font color="orange">Unknown</font>');
            });
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

function template_browse_dir()
{
    var netID = $('#network').val();
    
    if(netID == "")
    {
        alert("Please select a Network Server first!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=file_load_dir&browsetpl=1&reset=1&id='+netID,
        beforeSend:function(){
            $('#browse_done').hide();
            
            $("#modal").html('<i>Loading ...</i>').modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});
        },
        success:function(html){
            // Show modal with info
            $("#modal").html(html); /*.modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});*/
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

function template_browse_select(thisDir)
{
    if(thisDir == "")
    {
        alert("No directory provided!");
        return false;
    }
    
    $.modal.close();
    
    $('#file_path').val(thisDir);
    $('#browse_done').fadeIn();
    $('#file_path').focus();
}
