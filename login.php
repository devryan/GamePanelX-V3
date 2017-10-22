<?php
session_start();

// Check already logged-in
if(isset($_SESSION['gpx_userid']))
{
    header('Location: index.php');
    exit(0);
}

if(!file_exists('configuration.php')) die('Currently down for maintenance.  Please try again soon.');

require('configuration.php');

// Get system settings
require('includes/classes/core.php');
$Core = new Core;
$Core->dbconnect();
$settings = $Core->getsettings();
$cfg_theme      = $settings['theme'];
$cfg_lang       = $settings['language'];
$cfg_company    = $settings['company'];

// Set default language
if(!empty($cfg_lang)) require('languages/'.$cfg_lang.'.php');
else require('languages/english.php');

// Check Install
if(file_exists('install')) die('Currently down for maintenance.  Please try again soon.');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php if(!empty($cfg_company)) echo $cfg_company . ' | '.$lang['game_panel']; else echo $lang['game_panel']; ?></title>
<?php
// Use default system theme
if(!empty($cfg_theme)) echo '<link rel="stylesheet" type="text/css" href="themes/'.$cfg_theme.'/index.css" />';
else echo '<link rel="stylesheet" type="text/css" href="themes/default/index.css" />';
?>
<script type="text/javascript" src="scripts/jquery.min.js"></script>
<script type="text/javascript">var ajaxURL='ajax/ajax.php';</script>
<script type="text/javascript" src="scripts/gpx.js"></script>
<script type="text/javascript" src="scripts/base64.js"></script>
<script type="text/javascript" src="scripts/internal/login.js"></script>
</head>

<body>

<div id="panel_top_client"><?php echo $lang['game_panel']; ?></div>

<script type="text/javascript">
$(document).ready(function(){
    // Submit Login on enter
    $('.inputs').keypress(function(e) {
        if(e.which == 13) {
            login_user();
        }
    });
    
    $('#login_user').focus();
    
    <?php
    // Logged-out msg
    if(isset($_GET['out'])) echo 'infobox(\'s\', \''.$lang['logged_out'].'\');';
    ?>
});
</script>

<div align="center">
    <div id="login_box">
        
        <div class="infobox" style="display:none;"></div>
        
        <table style="margin-top:20px;">
        <tr>
          <td class="links"><?php echo $lang['username']; ?>:</td>
          <td><input type="text" class="inputs" id="login_user" />
        </tr>
        <tr>
          <td class="links"><?php echo $lang['password']; ?>:</td>
          <td><input type="password" class="inputs" id="login_pass" />
        </tr>
        </table>
        
        <input type="button" class="button" id="login_btn" value="<?php echo $lang['login']; ?>" onClick="javascript:login_user();" />
    </div>
</div>

</body>
</html>
