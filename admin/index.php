<?php
require('checkallowed.php'); // Check logged-in
#require('../configuration.php');

// Setup Language
require(DOCROOT.'/lang.php');

// Check Install
if(file_exists('../install')) die('Please delete the "install" directory before continuing!');

// Get system settings
require('../includes/classes/core.php');
$Core = new Core;
$Core->dbconnect();
$settings = $Core->getsettings();
$cfg_company    = $settings['company'];

// Setup plugins
require(DOCROOT.'/includes/classes/plugins.php');
$Plugins  = new Plugins;
$Plugins->setup_actions();

$Plugins->do_action('index_init'); // Plugins
?>
<!DOCTYPE html>
<html>
<head>
<!--   
   ___                       ___                 ___  __
  / _ \__ _ _ __ ___   ___  / _ \__ _ _ __   ___| \ \/ /
 / /_\/ _` | '_ ` _ \ / _ \/ /_)/ _` | '_ \ / _ \ |\  / 
/ /_\\ (_| | | | | | |  __/ ___/ (_| | | | |  __/ |/  \ 
\____/\__,_|_| |_| |_|\___\/    \__,_|_| |_|\___|_/_/\_\

-->
<?php $Plugins->do_action('index_head'); // Plugins ?>
<title>Admin | <?php if(!empty($cfg_company)) echo $cfg_company; else echo 'GamePanelX'; ?></title>
<?php
// Theme - Set user's chosen theme
if(isset($_SESSION['gpx_theme'])) echo '<link rel="stylesheet" type="text/css" href="../themes/'.$_SESSION['gpx_theme'].'/index.css" />';
else echo '<link rel="stylesheet" type="text/css" href="../themes/default/index.css" />';
?>
<link rel="stylesheet" type="text/css" href="../themes/dd.css" />
<script type="text/javascript" src="../scripts/jquery.min.js"></script>
<script type="text/javascript" src="../scripts/jquery.simplemodal.min.js"></script>
<script type="text/javascript" src="../scripts/jquery.dd.js"></script>
<script type="text/javascript">var ajaxURL='../ajax/ajax.php';</script>
<script type="text/javascript" src="../scripts/gpxadmin.js"></script>
<script type="text/javascript" src="../scripts/base64.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui.min.js"></script>
<script type="text/javascript" src="../scripts/jquery.form.js"></script>
<!-- <script type="text/javascript" src="../scripts/internal.min.js"></script> -->
<script type="text/javascript" src="../scripts/internal/cloud.js"></script>
<script type="text/javascript" src="../scripts/internal/files.js"></script>
<script type="text/javascript" src="../scripts/internal/servers.js"></script>
<script type="text/javascript" src="../scripts/internal/settings.js"></script>
<script type="text/javascript" src="../scripts/internal/templates.js"></script>
<script type="text/javascript" src="../scripts/internal/network.js"></script>
<script type="text/javascript" src="../scripts/internal/users.js"></script>
<script type="text/javascript" src="../scripts/internal/admins.js"></script>
<script type="text/javascript" src="../scripts/internal/games.js"></script>
<script type="text/javascript" src="../scripts/internal/plugins.js"></script>

<link href="../scripts/upload/fileuploader.css" rel="stylesheet" type="text/css">	
<script src="../scripts/upload/fileuploader.js" type="text/javascript"></script>
<script type="text/javascript">
function createUploader(){            
  var uploader = new qq.FileUploader({
      element: document.getElementById("file_up"),
      action: "../ajax/file_upload.php",
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
    
    // Load default page
    setTimeout("mainpage('default','')", 200);
});
</script>
</head>

<body>
<?php $Plugins->do_action('index_body'); // Plugins ?>

<div id="modal" style="display:none;"></div>

<div id="panel_top">
    <div id="panel_top_imgdiv"><img src="../images/logo.png" border="0" /></div>
    <div id="panel_top_txtdiv"><?php echo $lang['welcome_msg']; ?>, <b><?php echo $_SESSION['gpx_username']; ?></b>! <a href="logout.php" class="links" style="font-size:9pt;">(<?php echo $lang['logout']; ?>)</a></div>
</div>

<div id="panel_enc" align="left">
<div id="panel_left" style="border-top-right-radius:6px;">
    <div id="leftpanel_setup" class="panel_left_menugroup" style="border-top-right-radius:6px;"><?php echo $lang['setup']; ?></div>
    <div id="leftpanel_setup_items">
        <div class="panel_left_menuitem" onClick="javascript:mainpage('default','');"><?php echo $lang['home']; ?></div>
        <div class="panel_left_menuitem" onClick="javascript:mainpage('settings','');"><img src="../images/icons/medium/edit.png" width="18" height="18" /><?php echo $lang['settings']; ?></div>
        <div class="panel_left_menuitem" onClick="javascript:mainpage('games','');"><img src="../images/icons/medium/template.png" width="18" height="18" /><?php echo $lang['game_setups']; ?></div>
        <div class="panel_left_menuitem" onClick="javascript:mainpage('cloudgames','');"><img src="../images/icons/medium/cloud.png" width="18" height="18" /><?php echo $lang['cloud_games']; ?></div>
        <div class="panel_left_menuitem" onClick="javascript:mainpage('plugins','');"><img src="../images/icons/medium/plugins.png" width="18" height="18" /><?php echo $lang['plugins']; ?></div>
    </div>
    
    <div id="leftpanel_servers" class="panel_left_menugroup"><?php echo $lang['servers']; ?></div>
    <div id="leftpanel_servers_items">
        <div class="panel_left_menuitem" onClick="javascript:mainpage('servers','');"><img src="../images/icons/medium/servers.png" width="18" height="18" /><?php echo $lang['all_servers']; ?></div>
        <div class="panel_left_menuitem" onClick="javascript:mainpage('servers','g');"><img src="../images/icons/medium/servers.png" width="18" height="18" /><?php echo $lang['game_servers']; ?></div>
        <div class="panel_left_menuitem" onClick="javascript:mainpage('servers','v');"><img src="../images/icons/medium/servers.png" width="18" height="18" /><?php echo $lang['voice_servers']; ?></div>
        <div class="panel_left_menuitem" style="margin-bottom:3px;" onClick="javascript:mainpage('serveradd','');"><img src="../images/icons/medium/servers.png" width="18" height="18" /><?php echo $lang['create_server']; ?></div>
    </div>
    
    <div id="leftpanel_accounts" class="panel_left_menugroup"><?php echo $lang['accounts']; ?></div>
    <div id="leftpanel_accounts_items">
        <div class="panel_left_menuitem" onClick="javascript:mainpage('users','');"><img src="../images/icons/medium/accounts.png" width="18" height="18" /><?php echo $lang['list_users']; ?></div>
        <div class="panel_left_menuitem" onClick="javascript:user_show_create();"><img src="../images/icons/medium/accounts.png" width="18" height="18" /><?php echo $lang['add_user']; ?></div>
        <div class="panel_left_menuitem" onClick="javascript:mainpage('admins','');"><img src="../images/icons/medium/accounts.png" width="18" height="18" /><?php echo $lang['list_admins']; ?></div>
        <div class="panel_left_menuitem" onClick="javascript:admin_show_create();"><img src="../images/icons/medium/accounts.png" width="18" height="18" /><?php echo $lang['add_admin']; ?></div>
    </div>
    
    <div id="leftpanel_network" class="panel_left_menugroup"><?php echo $lang['network']; ?></div>
    <div id="leftpanel_network_items">
        <div class="panel_left_menuitem" onClick="javascript:mainpage('network','');"><img src="../images/icons/medium/network.png" width="18" height="18" /><?php echo $lang['all_servers']; ?></div>
        <div class="panel_left_menuitem" onClick="javascript:mainpage('networkadd','');"><img src="../images/icons/medium/network.png" width="18" height="18" /><?php echo $lang['create_network']; ?></div>
    </div>
</div>

<div id="panel_center"></div>
</div>

<input type="hidden" id="lastrt" value="" />

<?php $Plugins->do_action('index_body_end'); // Plugins ?>

</body>
</html>
<?php $Plugins->do_action('index_end'); // Plugins ?>
