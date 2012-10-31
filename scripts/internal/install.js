function install_start()
{
    var dbHost      = $('#db_host').val();
    var dbName      = $('#db_name').val();
    var dbUser      = $('#db_user').val();
    var dbPass      = $('#db_pass').val();
    var adminUser   = $('#admin_user').val();
    var adminEmail  = $('#admin_email').val();
    var adminPass   = $('#admin_pass').val();
    var adminPass2  = $('#admin_pass2').val();
    
    if(dbHost == "") { infobox('i','You left a field blank!'); $('#db_host').focus(); return false; }
    else if(dbName == "") { infobox('i','You left a field blank!'); $('#db_name').focus(); return false; }
    else if(dbUser == "") { infobox('i','You left a field blank!'); $('#db_user').focus(); return false; }
    else if(dbPass == "") { infobox('i','You left a field blank!'); $('#db_pass').focus(); return false; }
    else if(adminUser == "") { infobox('i','You left a field blank!'); $('#admin_user').focus(); return false; }
    else if(adminEmail == "") { infobox('i','You left a field blank!'); $('#admin_email').focus(); return false; }
    else if(adminPass == "") { infobox('i','You left a field blank!'); $('#admin_pass').focus(); return false; }
    else if(adminPass2 == "") { infobox('i','You left a field blank!'); $('#admin_pass2').focus(); return false; }
    
    if(adminPass != adminPass2)
    {
        infobox('f','Your passwords do not match!');
        $('#admin_pass').focus();
        return false;
    }
    
    $.ajax({
        url: 'install_actions.php',
        type: 'POST',
        data: 'a=start'+'&db_host='+dbHost+'&db_name='+dbName+'&db_user='+dbUser+'&db_pass='+dbPass+'&admin_user='+adminUser+'&admin_email='+adminEmail+'&admin_pass='+adminPass,
        beforeSend:function(){
            $('#install_btn').hide();
        },
        success:function(html){
            if(html == 'success')
            {
                infobox('s','');
                setTimeout("window.location='../admin/'", 2000);
            }
            else
            {
                $('#install_btn').fadeIn();
                infobox('f','<b>Error: </b>'+html);
            }
        },
        error:function(a,b,c){
            $('#install_btn').fadeIn();
            infobox('f','Error: '+b+', '+c);
        }
    });
}