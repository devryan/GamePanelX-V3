// Generate a random string of 6 characters
function settings_genstring()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 6; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

// Submit Settings
function settings_save()
{
    var lang        = encodeURIComponent($('#lang').val());
    var email       = encodeURIComponent($('#email').val());
    var company     = encodeURIComponent($('#company').val());
    var theme       = encodeURIComponent($('#theme').val());
    var localDir    = encodeURIComponent($('#local_dir').val());
    var steamAuth   = encodeURIComponent($('#steam_auth').val());
    var steamUser   = settings_genstring()+$.base64.encode($('#steam_user').val())+settings_genstring();
    var steamPass   = settings_genstring()+$.base64.encode($('#steam_pass').val())+settings_genstring();
    var datastr     = "&lang="+lang+"&email="+email+"&company="+company+"&theme="+theme+"&local_dir="+localDir+"&steam_login_user="+steamUser+"&steam_login_pass="+steamPass+"&steam_auth="+steamAuth+"&pure=1";
    
    $.ajax({
        url: ajaxURL,
        type: "POST",
        data: 'a=settings_save'+datastr,
        success:function(html){
            if(html == 'success') infobox('s','');
            else infobox('f','Failed: '+html);
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}
