<?php
// GamePanelX V3 API
// Servers
if(!defined('DOCROOT')) die('No direct access');
require(DOCROOT.'/includes/classes/servers.php');
$Servers = new Servers;

$api_action       = $_GET['action'];
$api_relid        = $_GET['id'];
$usr_username     = $_GET['username'];
$usr_game_intname = $_GET['game'];
$usr_password     = $_GET['password'];
$usr_email        = $_GET['email'];
$usr_first_name   = $_GET['first_name'];
$usr_last_name    = $_GET['last_name'];

// Create server
if($api_action == 'create')
{
    // Get available IP with default port (for now ...later we will add incremental ports)
    $combo = $Servers->get_avail_ip_port($usr_game_intname);
    
    // Get ID for this game
    $result_gid = @mysql_query("SELECT id FROM default_games WHERE intname = '$usr_game_intname'");
    $row_gid    = mysql_fetch_row($result_gid);
    $this_gid   = $row_gid[0];
    if(empty($this_gid)) die('Invalid game specified!');
    
    if($combo['available'] == 'yes')
    {
        $srv_netid  = $combo['netid'];
        $srv_port   = $combo['port'];
        
        // Create user account first
        $new_userid = $Users->create($usr_username,$usr_password,$usr_email,$usr_first_name,$usr_last_name);
        if(!is_numeric($new_userid)) die('Failed to create user: '.$new_userid);
        
        // Sleep for 4 seconds to allow the remote server enough time to create the system account (it queues up every 3 seconds)
        sleep(4);
        
        echo $Servers->create($srv_netid,$this_gid,$new_userid,$srv_port,'');
    }
    else
    {
        die('Sorry, no available servers to handle this request!');
    }
}

#  delete($srvid)
?>
