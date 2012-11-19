/*
*
* Files
*
*/

// Confirm file deletion
function confirm_delete_file(srvID,file,rowID)
{
    if(srvID == "" || file == "")
    {
        alert("No server ID or path given!");
        return false;
    }
    
    var answer = confirm("Are you sure?\n\nThis file will be deleted permanently!");
    
    if(answer) delete_file(srvID,file,rowID);
    else return false;
}


// Delete a file
function delete_file(srvID,file,rowID)
{
    if(srvID == "" || file == "")
    {
        alert("No server ID or filename given!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=file_actions&do=delete&id='+srvID+'&file='+file,
        success:function(html){
            if(html == 'success')
            {
                $('#file_'+rowID).fadeOut('slow');
                infobox('s','');
            }
            else
            {
                infobox('f','Failed to delete the file: ('+html+')');
            }
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

// Confirm directory deletion
function confirm_delete_dir(srvID,dir)
{
    if(srvID == "" || dir == "")
    {
        alert("No server ID or path given ("+srvID+", "+dir+") !");
        return false;
    }
    
    var answer = confirm("Are you sure?\n\nRemove this directory \""+dir+"\" ?");
    
    if(answer) delete_dir(srvID,dir);
    else return false;
}

// Delete a directory
function delete_dir(srvID,dir)
{
    if(srvID == "" || dir == "")
    {
        alert("No server ID or directory given!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=file_actions&do=delete_dir&id='+srvID+'&file='+dir,
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                
                setTimeout("load_dir("+srvID+",'',1,'');", 1000);
            }
            else
            {
                infobox('f','Failed to delete the directory: ('+html+')');
            }
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}



function load_dir(srvID,dir,back,tplBrowse)
{
    if(srvID == "")
    {
        alert("No server ID given!");
        return false;
    }
    
    if(!back && dir == "")
    {
        alert("No directory given!");
        return false;
    }
    
    if(tplBrowse == "reset") var addBrowse  = '&browsetpl=1&reset=1';
    else if(tplBrowse) var addBrowse  = '&browsetpl=1';
    else var addBrowse  = '';
    
    $.ajax({
        url: ajaxURL,
        data: 'a=file_load_dir&id='+srvID+'&dir='+dir+'&back='+back+addBrowse,
        beforeSend:function(){
            infobox('i','Loading ...');
        },
        success:function(html){
            // Hide infobox
            $('.infobox').hide();
            
            // Browsing for templates, use modal for display
            if(tplBrowse)
            {
                $("#modal").html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                    dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
                }});
            }
            else
            {
                $('#panel_center').hide().html(html).fadeIn('fast');
            }
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

function file_savecontent(srvid,file)
{
    if(srvid == "" || file == "")
    {
        alert("Insufficient info given!");
        return false;
    }
    
    var contents  = $('#filecontent_cur').val();
    
    $.ajax({
        url: ajaxURL,
        type: 'POST',
        data: 'a=file_actions&do=savecontent&id='+srvid+'&file='+file+'&content='+contents,
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed to save the file: ('+html+')');
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

function file_show_addfile(srvID)
{
    if(srvID == "")
    {
        alert("Insufficient info given!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=file_actions&do=show_addfile&id='+srvID,
        success:function(html){
            // Show modal with info
            $("#modal").css('height','').html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); $("#modal").css('height',''); }); 
            }});
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

function file_show_add_dir(srvID)
{
    if(srvID == "")
    {
        alert("Insufficient info given!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=file_actions&do=show_add_dir&id='+srvID,
        success:function(html){
            // Show modal with info
            $("#modal").css('height','100').html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); $("#modal").css('height',''); }); 
            }});
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

function file_add_newdir(srvID)
{
    if(srvID == "")
    {
        alert("Insufficient info given!");
        return false;
    }
    var newDirName  = $('#new_dirname').val();
    
    if(newDirName == "")
    {
        alert("You must specify a directory name!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        type: 'POST',
        data: 'a=file_actions&do=create_newdir&id='+srvID+'&dir='+newDirName,
        success:function(html){
            $.modal.close();
            
            if(html == 'success')
            {
                infobox('s','');
                
                // Show directory in listing
                var curTrs  = $('#files_table tr').length;
                var thisCnt = parseInt(curTrs) + parseInt(1);
                
                $('#files_table').append('<tr id="file_'+thisCnt+'" style="cursor:default;background:#FFF;" class="filerows"><td><img src="../images/icons/medium/folder.png" border="0" width="28" height="28" style="cursor:pointer;" onClick="javascript:load_dir('+srvID+',\''+newDirName+'\',0);" /></td><td align="left"><span class="links" style="cursor:pointer;" onClick="javascript:load_dir('+srvID+',\''+newDirName+'\',0);">'+newDirName+'</span></td><td>Today</td><td>Today</td><td>4096</td><td style="cursor:default;">&nbsp;</td></tr>');
            }
            else
            {
                infobox('f','Failed to create the directory: ('+html+')');
            }
                        
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

function file_savenewfile(srvID)
{
    if(srvID == "")
    {
        alert("Insufficient info given!");
        return false;
    }
    
    var newFilename = $('#newfilename').val();
    var contents    = $('#filecontent').val();
    
    if(newFilename == "")
    {
        alert("You must specify a filename!");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        type: 'POST',
        data: 'a=file_actions&do=save_newfile&id='+srvID+'&file='+newFilename+'&content='+contents,
        success:function(html){
            $.modal.close();
            
            if(html == 'success')
            {
                infobox('s','');
                
                // Show file in listing
                var curTrs  = $('#files_table tr').length;
                var thisCnt = parseInt(curTrs) + parseInt(1);
                
                if(/\.(txt|cfg|rc|log|ini|inf|vdf)$/i.test(newFilename)) var editable  = ' class="links" style="cursor:pointer;" onClick="javascript:load_dir('+srvID+',\''+newFilename+'\',0);" ';
                else var editable  = ' class="links" style="font-weight:normal;cursor:default;text-decoration:none;" ';
                
                $('#files_table').append('<tr id="file_'+thisCnt+'" style="cursor:default;background:#FFF;" class="filerows"><td><img src="../images/icons/medium/file.png" border="0" width="28" height="28" '+editable+'/></td><td align="left"><span '+editable+'>'+newFilename+'</span></td><td>Today</td><td>Today</td><td>&nbsp;</td><td style="cursor:default;"><img src="../images/icons/medium/error.png" width="25" height="25" border="0" title="Delete" style="cursor:pointer;" onClick="javascript:confirm_delete_file('+srvID+',\''+newFilename+'\','+thisCnt+');" /></td></tr>');
            }
            else
            {
                infobox('f','Failed to save the file: ('+html+')');
            }
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}
