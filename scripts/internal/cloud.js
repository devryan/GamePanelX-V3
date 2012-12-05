
/*
*
* Cloud Games
*
*/

// Fetch game info from the gpx cloud
function cloud_game_info(cloudID)
{
    if(cloudID == "")
    {
        alert("No Cloud Game ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=cloud_gameinfo&id='+cloudID,
        success:function(html){
            /*
            $("#modal").html(html+'').modal({overlayClose:true,onClose: function (dialog) {
                dialog.data.fadeOut(function () { dialog.container.hide(function () { dialog.overlay.slideUp(function () { $.modal.close(); }); }); }); 
            }});
            */
            
            // Show modal with info
            $("#modal").html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

function cloud_install_game(cloudID)
{
    if(cloudID == "")
    {
        alert("No Cloud Game ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=cloud_gameinstall&id='+cloudID,
        success:function(html){
            infobox('s','');
            $.modal.close();
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

function cloud_getall()
{
    $.ajax({
        url: ajaxURL,
        data: 'a=cloud_actions&do=getall',
        beforeSend:function(){
            $('#cloudtbl').append('<tr id="cldld"><td colspan="4" style="font-size:10pt;"><i>Loading cloud data ...</i></td></tr>');
        },
        success:function(html){
            $('#cldld').hide();
            //$(html).hide().appendTo("#cloudtbl").fadeIn();
            $('#cloudtbl').append(html);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

// Check for updates
function cloud_check_updates()
{
    $.ajax({
        url: ajaxURL,
        data: 'a=cloud_actions&do=check_updates',
        success:function(html){
            // Up to date
            if(html != 'success') infobox('i',html);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}
