<?php
require('checkallowed.php'); // Check logged-in

if(isset($_SESSION['gpx_admin'])) die('Cannot view client area as an admin!');

// Setup Language
require(DOCROOT.'/lang.php');

// Check Install
if(file_exists('install')) die('Currently down for maintenance.  Please try again soon.');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title><?php if(!empty($cfg_company)) echo $cfg_company . ' | '.$lang['game_panel']; else echo $lang['game_panel']; ?></title>
<?php
// Theme - Set user's chosen theme
if(isset($_SESSION['gpx_theme'])) echo '<link rel="stylesheet" type="text/css" href="themes/'.$_SESSION['gpx_theme'].'/index.css" />';
else echo '<link rel="stylesheet" type="text/css" href="themes/default/index.css" />';
?>

<link rel="stylesheet" type="text/css" href="themes/dd.css" />
<script type="text/javascript" src="scripts/jquery.min.js"></script>
<script type="text/javascript" src="scripts/jquery.simplemodal.min.js"></script>
<script type="text/javascript" src="scripts/jquery.dd.js"></script>
<script type="text/javascript">var ajaxURL='ajax/ajax.php';</script>
<script type="text/javascript" src="scripts/gpx.js"></script>
<script type="text/javascript" src="scripts/base64.js"></script>

<script type="text/javascript" src="scripts/internal/files.js"></script>
<script type="text/javascript" src="scripts/internal/servers.js"></script>
<script type="text/javascript" src="scripts/internal/settings.js"></script>
<script type="text/javascript" src="scripts/internal/users.js"></script>

<link href="scripts/upload/fileuploader.css" rel="stylesheet" type="text/css">	
<script src="scripts/upload/fileuploader.js" type="text/javascript"></script>
<script type="text/javascript">
function createUploader(){            
  var uploader = new qq.FileUploader({
      element: document.getElementById("file_up"),
      action: "ajax/file_upload.php",
      debug: true
  });           
}
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('#leftpanel_setup').click(function(){
        $('#leftpanel_setup_items').slideToggle('fast');
    });
    $('#leftpanel_servers').click(function(){
        $('#leftpanel_servers_items').slideToggle('fast');
    });
    $('#leftpanel_users').click(function(){
        $('#leftpanel_users_items').slideToggle('fast');
    });
    $('#leftpanel_accounts').click(function(){
        $('#leftpanel_accounts_items').slideToggle('fast');
    });
    $('#leftpanel_network').click(function(){
        $('#leftpanel_network_items').slideToggle('fast');
    });
    
    // Confirm leaving since everything is ajaxy
    $(window).bind('beforeunload', function(){
        return 'Are you sure you want to leave?';
    });
    
    // Load servers as default page
    mainpage('servers','');
});
</script>
</head>

<body>
<div id="modal" style="display:none;"></div>

<div id="panel_top_client"><?php echo $lang['game_panel']; ?></div>

<div align="center">
    <div id="client_center_enc">
        <div align="center"><div id="client_center_def"><?php require('default.php'); ?></div></div><br />
        <div id="panel_center" class="panel_center_client"></div>
    </div>
</div>

<input type="hidden" id="lastrt" value="" />

</body>
</html>
