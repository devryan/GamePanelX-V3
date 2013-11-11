<?php
// GamePanelX V3 API
// Servers
if(!defined('DOCROOT')) die('No direct access');
require(DOCROOT.'/includes/classes/servers.php');
$Servers = new Servers;

$api_action       = $GPXIN['action'];
$api_relid        = $GPXIN['id'];
$usr_username     = $GPXIN['username'];
$usr_game_intname = $GPXIN['game'];
$usr_password     = $GPXIN['password'];
$usr_email        = $GPXIN['email'];
$usr_first_name   = $GPXIN['first_name'];
$usr_last_name    = $GPXIN['last_name'];
$srv_total_slots  = $GPXIN['slots'];
$srv_url_port     = $GPXIN['port'];
$srv_rcon_pass    = $GPXIN['rcon_password'];
$srv_private_pass = $GPXIN['private_password'];
$srv_is_private   = $GPXIN['is_private'];

// Create server
if($api_action == 'create' || $api_action == 'createserver')
{
    // Get available IP with default port (for now ...later we will add incremental ports)
    $combo = $Servers->get_avail_ip_port($usr_game_intname,$srv_url_port);
    
    #var_dump($combo); echo '<br>';
    
    // Get ID for this game
    $result_gid = @mysql_query("SELECT id FROM default_games WHERE intname = '$usr_game_intname'");
    $row_gid    = mysql_fetch_row($result_gid);
    $this_gid   = $row_gid[0];
    if(empty($this_gid)) die('Invalid game specified!');
    
    if($combo['available'] == 'yes')
    {
        $srv_netid          = $combo['netid'];
        $srv_port           = $combo['port'];
        $srv_description    = '';
        
        // Check if username exists
        $result_ck  = @mysql_query("SELECT id FROM users WHERE username = '$usr_username' AND deleted = '0' LIMIT 1");
        $row_ck     = mysql_fetch_row($result_ck);
        $new_userid = $row_ck[0];
        
        // User doesnt exist, create them
        if(empty($new_userid))
        {
            require(DOCROOT.'/includes/classes/users.php');
            $Users   = new Users;
            
            $new_userid = $Users->create($usr_username,$usr_password,$usr_email,$usr_first_name,$usr_last_name);
            if(!is_numeric($new_userid)) die('Failed to create user: '.$new_userid);
            
            // Sleep for 4 seconds to allow the remote server enough time to create the system user account (it queues up every 3 seconds)
            sleep(4);
        }
        
	// Using default template so no need to specify that here
	$tplid = '';

        // Create the server
        echo $Servers->create($srv_netid,$this_gid,$new_userid,$tplid,$srv_port,$srv_description,$srv_total_slots,$srv_rcon_pass,$srv_is_private,$srv_private_pass);
    }
    else
    {
        die('Sorry, no available ip/port combinations available to handle this request!');
    }
}

############################################

// Delete/terminate server
elseif($api_action == 'delete' || $api_action == 'terminate' || $api_action == 'terminateserver')
{
        if(empty($api_relid)) die('No server ID provided');
        echo $Servers->delete($api_relid);
}

############################################

// Start/Restart a server instance
elseif($api_action == 'restart')
{
  if(empty($api_relid)) die('No server ID provided');
  echo $Servers->restart($api_relid);
}

############################################

// Stop/Halt a server instance
elseif($api_action == 'stop')
{
  if(empty($api_relid)) die('No server ID provided');
  echo $Servers->stop($api_relid);
}

############################################

// Suspend/Un-Suspend
elseif($api_action == 'suspend' || $api_action == 'unsuspend')
{
	die('Suspend/UnSuspend have not been implemented yet, sorry');
}

?>
