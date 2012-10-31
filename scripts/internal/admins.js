/*
*
* Admin Accounts
*
*/

// Show create dialog
function admin_show_create()
{
    $.ajax({
        url: ajaxURL,
        data: 'a=admin_create_form',
        success:function(html){
            // Show modal with info
            $("#modal").html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});
        }
    });
}

// Create
function admin_create()
{
    var username    = $('#username').val();
    var pass1       = $('#pass1').val();
    var pass2       = $('#pass2').val();
    var email       = $('#email').val();
    var fname       = $('#fname').val();
    var lname       = $('#lname').val();
    
    if(username == "" || email == "" || pass1 == "")
    {
        alert("The Username,Email and Password fields must be filled.");
        return false;
    }
    
    if(pass1 != pass2)
    {
        alert("Passwords do not match!");
        return false;
    }
    
    var datastr     = "&do=create&username="+username+"&password="+pass1+"&email="+email+"&fname="+fname+"&lname="+lname;
    
    $.ajax({
        url: ajaxURL,
        type: "POST",
        data: 'a=admin_actions'+datastr,
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                setTimeout("$.modal.close();mainpage('admins','')", 1000);
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

// Edit
function admin_edit(usrID)
{
    if(usrID == "")
    {
        alert("No User ID provided");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=admin_edit&id='+usrID,
        success:function(html){
            // Show modal with info
            $("#modal").html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});
        }
    });
}

// Save admin edits
function admin_save(usrID)
{
    if(usrID == "")
    {
        alert("No User ID given!");
        return false;
    }
    
    var username  = $('#adm_username').val();
    var language  = $('#adm_language').val();
    var theme     = $('#adm_theme').val();
    var pass1     = $('#adm_pass1').val();
    var pass2     = $('#adm_pass2').val();
    var email     = $('#adm_email').val();
    var fname     = $('#adm_fname').val();
    var lname     = $('#adm_lname').val();
    
    if(pass1 != pass2)
    {
        alert("Your passwords do not match!  Please try again");
        $('#pass1').focus();
        return false;
    }
    else
    {
        var addPass = "&password="+pass1;
    }
    
    var datastr   = "&id="+usrID+"&username="+username+"&email="+email+"&fname="+fname+"&lname="+lname+addPass+"&language="+language+"&theme="+theme;
    
    $.ajax({
        url: ajaxURL,
        type: "POST",
        data: 'a=admin_actions&do=save'+datastr,
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

// Confirm deleting
function admin_confirm_delete(usrID)
{
    if(usrID == "")
    {
        alert("No User ID given");
        return false;
    }
    
    var answer = confirm("Are you sure?\n\nDelete this administrator?");
    
    if(answer) admin_delete(usrID);
    else return false;
}

// Delete
function admin_delete(usrID)
{
    if(usrID == "")
    {
        alert("No User ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=admin_actions&do=delete&id='+usrID,
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
            
            // Redirect
            setTimeout("mainpage('admins','')", 1000);
        }
    });
}
