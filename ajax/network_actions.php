<?php
$forceadmin = 1; // Admins only
require('checkallowed.php'); // No direct access
error_reporting(E_ERROR);

require(DOCROOT.'/includes/classes/network.php');
$Network  = new Network;

$url_id           = $GPXIN['id'];
$url_do           = $GPXIN['do']; // Action
$url_ip           = $GPXIN['ip'];
$url_local        = $GPXIN['is_local'];
$url_os           = htmlspecialchars($GPXIN['os']);
$url_dc           = htmlspecialchars($GPXIN['datacenter']);
$url_location     = htmlspecialchars($GPXIN['location']);
#$url_login_user   = base64_decode($GPXIN['login_user']);
#$url_login_pass   = base64_decode($GPXIN['login_pass']);
#$url_login_port   = base64_decode($GPXIN['login_port']);
#$url_homedir      = base64_decode($GPXIN['homedir']);
$url_login_user   = $GPXIN['login_user'];
$url_login_pass   = $GPXIN['login_pass'];
$url_login_port   = $GPXIN['login_port'];
$url_homedir      = $GPXIN['homedir'];

// Create
if($url_do == 'create')
{
    // NO root users
    if($url_login_user == 'root') die('Do not set <b>Login User</b> to <font color="red">root</font>!  Set this to the normal Linux user created during Remote Server installation. See <a href="http://gamepanelx.com/wikiv3/index.php?title=Remote_Install" class="links" target="_blank">Remote Server Documentation</a>');
    
    echo $Network->create($url_ip,$url_local,$url_os,$url_dc,$url_location,$url_login_user,$url_login_pass,$url_login_port);
}

// Save
elseif($url_do == 'save')
{
    // Include config
    require(DOCROOT.'/configuration.php');
    
    
    $enc_key  = $settings['enc_key'];
    if(empty($enc_key)) die($lang['no_enc_key']);

    $GLOBALS['mysqli']->query("UPDATE network SET ip='$url_ip',is_local='$url_local',os='$url_os',datacenter='$url_dc',location='$url_location',login_user=AES_ENCRYPT('$url_login_user', '$enc_key'),login_pass=AES_ENCRYPT('$url_login_pass', '$enc_key'),login_port=AES_ENCRYPT('$url_login_port', '$enc_key'),homedir='$url_homedir' WHERE id = '$url_id'") or die('Failed to update network settings');
    
    echo 'success';
}

// Delete
elseif($url_do == 'delete')
{
    echo $Network->delete($url_id);
}

// Delete IP Address
elseif($url_do == 'delete_ip')
{
    $GLOBALS['mysqli']->query("DELETE FROM network WHERE id = '$url_id'") or die('Failed to delete the IP Address');
    
    echo 'success';
}



// Show add IP dialog
elseif($url_do == 'show_addip')
{
    // Get original IP
    $result_ip  = $GLOBALS['mysqli']->query("SELECT ip FROM network WHERE id = '$url_id' LIMIT 1") or die('Failed to get IP!');
    $row_ip     = $result_ip->fetch_row();
    $this_ip    = $row_ip[0];
    
    $arr_ip = explode('.', $this_ip);
    $first_3  = $arr_ip[0] . '.' . $arr_ip[1] . '.' . $arr_ip[2] . '.';
    
    
    echo '<b>'.$lang['new_ip'].':</b> <input type="text" class="inputs" id="new_ip" value="'.$first_3.'" /><br />
    <div align="center"><div class="button" onClick="javascript:network_addip('.$url_id.');">'.$lang['save'].'</div></div>';
    
    exit;
}

// Add IP Address to a physical server
elseif($url_do == 'addip')
{
    // Check existing
    $result_ip  = $GLOBALS['mysqli']->query("SELECT id FROM network WHERE ip = '$url_ip' LIMIT 1") or die('Failed to get IP!');
    $row_ip     = $result_ip->fetch_row();
    if($row_ip[0]) die($lang['ip_exists']);
    
    // Check if any servers using this
    #$result_ip  = $GLOBALS['mysqli']->query("SELECT id FROM servers WHERE netid = '$url_id' LIMIT 1") or die('Failed to get IP!');
    #$row_ip     = mysqli_fetch_row($result_ip);
    #if($row_ip[0]) die($lang['srv_using_ip']);
    
    // Check regex
    if(!preg_match('/[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/', $url_ip)) die($lang['invalid_ip']);
    
    $GLOBALS['mysqli']->query("INSERT INTO network (parentid,ip) VALUES('$url_id','$url_ip')") or die('Failed to add the IP Address: '.$GLOBALS['mysqli']->error);
    
    echo 'success';
}


?>
