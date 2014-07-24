<?php
error_reporting(E_ERROR);
session_start();
if(!isset($_SESSION['gpx_userid']) || !isset($_SESSION['gpx_admin'])) die('Please login');

$Plugins->do_action('home_top'); // Plugins

// Debug info
if(GPXDEBUG)
{
    // Get version
    $result_vr    = @mysql_query("SELECT config_value FROM configuration WHERE config_setting = 'version' LIMIT 1");
    $row_vr       = mysql_fetch_row($result_vr);
    $gpx_version  = $row_vr[0];

    echo '<b>NOTICE:</b> Debug mode has been enabled in configuration.php.<br />';   
    echo 'DEBUG: Master Version '.$gpx_version.'<br />';
    echo 'DEBUG: Document Root: '.DOCROOT.'<br />';
    if(mysql_error()) echo 'DEBUG: Last MySQL error: '.mysql_error().'<br />';
}
?>
<div class="infobox" style="display:none;"></div>

<script>
// Check for system updates
$(document).ready(function(){
    setTimeout("cloud_check_updates()", 500);
});
</script>

<div id="homeic_boxes">
    <div class="homeic_box" onClick="javascript:mainpage('servers','');">
        <img src="../images/icons/medium/servers.png" /><?php echo $lang['servers']; ?>
    </div>
    <div class="homeic_box" onClick="javascript:mainpage('users','');">
        <img src="../images/icons/medium/accounts.png" /><?php echo $lang['accounts']; ?>
    </div>
    <div class="homeic_box" onClick="javascript:mainpage('games','');">
        <img src="../images/icons/medium/template.png" /><?php echo $lang['game_setups']; ?>
    </div>
    <div class="homeic_box" onClick="javascript:mainpage('settings','');">
        <img src="../images/icons/medium/edit.png" /><?php echo $lang['settings']; ?>
    </div>
    
    
    <div class="homeic_box" onClick="javascript:mainpage('cloudgames','');">
        <img src="../images/icons/medium/cloud.png" /><?php echo $lang['cloud_games']; ?>
    </div>
    <div class="homeic_box" onClick="javascript:mainpage('network','');">
        <img src="../images/icons/medium/network.png" /><?php echo $lang['network']; ?>
    </div>
    <div class="homeic_box" onClick="javascript:mainpage('plugins','');">
        <img src="../images/icons/medium/plugins.png" /><?php echo $lang['plugins']; ?>
    </div>
    <div class="homeic_box" onClick="javascript:mainpage('admins','');">
        <img src="../images/icons/medium/accounts.png" /><?php echo $lang['admins']; ?>
    </div>
</div>

<?php
//
// Check how setup they are
//
$result_tpl = @mysql_query("SELECT 
                              u.id AS uid,
                              s.id AS sid,
                              t.id AS tid,
                              n.id AS nid 
                            FROM configuration AS c
                            LEFT JOIN users AS u ON (SELECT id FROM users LIMIT 1)  
                            LEFT JOIN servers AS s ON (SELECT id FROM servers LIMIT 1) 
                            LEFT JOIN templates AS t ON (SELECT id FROM templates WHERE t.status = 'complete' LIMIT 1) 
                            LEFT JOIN network AS n ON (SELECT id FROM network LIMIT 1)
                            LIMIT 1") or die('Failed to check setup: '.mysql_error());

$row_tpl  = mysql_fetch_row($result_tpl);
$ck_u   = $row_tpl[0];
$ck_s   = $row_tpl[1];
$ck_t   = $row_tpl[2];
$ck_n   = $row_tpl[3];

// Network
if(empty($ck_n)) echo '<b>'.$lang['def_adm_tip_docs'].':</b> <a class="links" href="http://gamepanelx.com/wikiv3/index.php?title=Master_Install" target="_blank">'.$lang['documentation'].'</a><br /><br />
<div class="def_warnings">'.$lang['def_adm_step'].' 1.) <b>'.$lang['network'].': </b> '.$lang['def_adm_tip_net'].': <span class="links" style="font-size:9pt;" onClick="javascript:mainpage(\'networkadd\',\'\');">'.$lang['click_here'].'</span></div>';

// User Accounts
if(empty($ck_u)) echo '<div class="def_warnings">'.$lang['def_adm_step'].' 2.) <b>'.$lang['accounts'].': </b> '.$lang['def_adm_tip_accts'].' (<span class="links" style="font-size:9pt;" onClick="javascript:user_show_create();">'.$lang['click_here'].'</span>)</div>';

// Templates
if(empty($ck_t)) echo '<div class="def_warnings">'.$lang['def_adm_step'].' 3.) <b>'.$lang['templates'].': </b> '.$lang['def_adm_tip_tpl'].' (<span class="links" style="font-size:9pt;" onClick="javascript:mainpage(\'games\',\'\');">'.$lang['click_here'].'</span>)</div>';

// Templates and Users, no servers yet
if($ck_t && $ck_u && !$ck_s) echo '<div class="def_warnings" style="color:#444;"><b style="color:green;">'.$lang['servers'].': </b>'.$lang['def_adm_tip_srv1'].' <span class="links" style="font-size:9pt;" onClick="javascript:server_show_create();">'.$lang['click_here'].'</span></div>';

// Servers
elseif(empty($ck_s)) echo '<div class="def_warnings">'.$lang['def_adm_step'].' 4.) <b>'.$lang['servers'].': </b> '.$lang['def_adm_tip_srv2'].'</div>';

##########################

$Plugins->do_action('home_bottom'); // Plugins

?>
