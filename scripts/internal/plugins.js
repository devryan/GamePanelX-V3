/*
*
* Plugins functions
*
*/

// Update a currently installed plugin
function plugin_update_active(pluginID)
{
    if(pluginID == "")
    {
        alert("No plugin ID given!");
        return false;
    }
    
    var plgStatus = $('#plg_'+pluginID+'_status').val();
    
    // Confirm delete
    if(plgStatus == 'delete')
    {
        var answer = confirm("Are you sure?\n\nDelete this plugin?");
        
        if(!answer) return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=plugin_actions&do=update&id='+pluginID+'&status='+plgStatus,
        success:function(html){
            if(plgStatus == 'delete') $('#plugin_'+pluginID).fadeOut();
            
            if(html == 'success') infobox('s','');
            else infobox('f','<b>Failed:</b> '+html);
        }
    });
}

// Install a new plugin that has been uploaded
function plugin_install(name)
{
    if(name == "")
    {
        alert("No plugin name given!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=plugin_actions&do=install&name='+name,
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','<b>Failed:</b> '+html);
        }
    });
}