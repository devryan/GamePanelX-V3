/*
*
* Login
*
*/

// Admin Login
function login_admin()
{
    
    var loginUser = $.base64.encode('xxz'+$('#login_user').val()+'yy');
    var loginPass = $.base64.encode('xyy'+$('#login_pass').val()+'yyx');
    
    if(loginUser == "" || loginPass == "") return false;
    
    $.ajax({
        url: ajaxURL,
        type: 'POST',
        data: 'a=login_actions&do=adminlogin&user='+loginUser+'&pass='+loginPass,
        success:function(html){
            if(html == 'success')
            {
                infobox('s','Successfully logged in!  Redirecting ...');
                
                setTimeout("window.location='index.php'", 1000);
            }
            else
            {
                infobox('f','<b>Login Failed:</b> '+html);
                $('#login_pass').val('');
            }
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}

// User Login
function login_user()
{
    
    var loginUser = $.base64.encode('xxff'+$('#login_user').val()+'yyuuu');
    var loginPass = $.base64.encode('xyd'+$('#login_pass').val()+'yyd');
    
    if(loginUser == "" || loginPass == "") return false;
    
    $.ajax({
        url: ajaxURL,
        type: 'POST',
        data: 'a=login_actions&do=userlogin&user='+loginUser+'&pass='+loginPass,
        success:function(html){
            if(html == 'success')
            {
                infobox('s','Successfully logged in!  Redirecting ...');
                
                setTimeout("window.location='index.php'", 1000);
            }
            else
            {
                infobox('f','<b>Login Failed:</b> '+html);
                $('#login_pass').val('');
            }
        },
        error:function(a,b,c){
            infobox('f','Error: '+b+', '+c);
        }
    });
}
