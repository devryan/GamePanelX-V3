
// Submit Settings
function settings_save()
{
    var lang      = encodeURIComponent($('#lang').val());
    var email     = encodeURIComponent($('#email').val());
    var company   = encodeURIComponent($('#company').val());
    var theme     = encodeURIComponent($('#theme').val());
    var localDir  = encodeURIComponent($('#local_dir').val());
    var datastr   = "&lang="+lang+"&email="+email+"&company="+company+"&theme="+theme+"&local_dir="+localDir+"&pure=1";
    
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