
<!DOCTYPE html>
<html>
<head>
<title>Mega-Lan | Game Control Panel</title>
<link rel="stylesheet" type="text/css" href="themes/default/index.css" /><script type="text/javascript" src="scripts/jquery.min.js"></script>
<script type="text/javascript">var ajaxURL='ajax/ajax.php';</script>
<script type="text/javascript" src="scripts/gpx.js"></script>
<script type="text/javascript" src="scripts/base64.js"></script>
<script type="text/javascript" src="scripts/internal/login.js"></script>
</head>

<body>

<div id="panel_top_client">Game Control Panel</div>

<script type="text/javascript">
$(document).ready(function(){
    // Submit Login on enter
    $('.inputs').keypress(function(e) {
        if(e.which == 13) {
            login_user();
        }
    });
    
    $('#login_user').focus();
    
    infobox('s', 'Successfully logged out');});
</script>

<div align="center">
    <div id="login_box">
        
        <div class="infobox" style="display:none;"></div>
        
        <table style="margin-top:20px;">
        <tr>
          <td class="links">Username:</td>
          <td><input type="text" class="inputs" id="login_user" />
        </tr>
        <tr>
          <td class="links">Password:</td>
          <td><input type="password" class="inputs" id="login_pass" />
        </tr>
        </table>
        
        <input type="button" class="button" id="login_btn" value="Login" onClick="javascript:login_user();" />
    </div>
</div>

</body>
</html>
