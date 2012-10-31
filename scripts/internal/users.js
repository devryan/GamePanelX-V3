/*
*
* User Accounts
*
*/

// Show create dialog
function user_show_create()
{
    $.ajax({
        url: ajaxURL,
        data: 'a=user_create_form',
        success:function(html){
            // Show modal with info
            $("#modal").html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});
        }
    });
}

// Create user
function user_create()
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
        data: 'a=user_actions'+datastr,
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                setTimeout("$.modal.close();mainpage('users','')", 1000);
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
function user_edit(usrID)
{
    if(usrID == "")
    {
        alert("No User ID provided");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=user_edit&id='+usrID,
        success:function(html){
            // Show modal with info
            $("#modal").html(html).modal({overlayClose:true,opacity:70,overlayCss:{backgroundColor:"#000"},onClose: function (dialog) {
                dialog.data.fadeOut('fast',function () { $.modal.close(); }); 
            }});
        }
    });
}

// Save user edits
function user_save(usrID)
{
    if(usrID == "")
    {
        alert("No User ID given!");
        return false;
    }
    
    var username  = $('#usr_username').val();
    var pass1     = $('#usr_pass1').val();
    var pass2     = $('#usr_pass2').val();
    var email     = $('#usr_email').val();
    var fname     = $('#usr_fname').val();
    var lname     = $('#usr_lname').val();
    var theme     = $('#usr_theme').val();
    var language  = $('#usr_language').val();
    
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
    
    var datastr   = "&id="+usrID+"&username="+username+"&email="+email+"&fname="+fname+"&lname="+lname+"&language="+language+"&theme="+theme+addPass;
    
    $.ajax({
        url: ajaxURL,
        type: "POST",
        data: 'a=user_actions&do=save'+datastr,
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
function user_confirm_delete(usrID)
{
    if(usrID == "")
    {
        alert("No User ID given");
        return false;
    }
    
    var answer = confirm("Are you sure?\n\nDelete this user?");
    
    if(answer) user_delete(usrID);
    else return false;
}

// Delete
function user_delete(usrID)
{
    if(usrID == "")
    {
        alert("No User ID given");
        return false;
    }
    
    $.ajax({
        url: ajaxURL,
        data: 'a=user_actions&do=delete&id='+usrID,
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                
                // Redirect
                setTimeout("mainpage('users','')", 1000);
            }
            else
            {
                infobox('f','<b>Failed: </b>'+html);
            }
        }
    });
}

// Save permissions
function user_perm_save(usrID)
{
    if(usrID == "")
    {
        alert("No User ID given!");
        return false;
    }
    
    var perm_ftp    = $('input[name=perm_ftp]:checked').val();
    var perm_fm     = $('input[name=perm_fm]:checked').val();
    var perm_strt   = $('input[name=perm_str]:checked').val();
    var perm_upd    = $('input[name=perm_upd]:checked').val();
    var perm_chpass = $('input[name=perm_chpass]:checked').val();
    var datastr   = "&id="+usrID+"&ftp="+perm_ftp+"&fm="+perm_fm+"&str="+perm_strt+"&upd="+perm_upd+"&chpass="+perm_chpass;
    
    $.ajax({
        url: ajaxURL,
        type: "POST",
        data: 'a=user_actions&do=save_perms'+datastr,
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}
