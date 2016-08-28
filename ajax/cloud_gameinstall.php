<?php
$forceadmin = 1; // Admins only
require('checkallowed.php'); // No direct access

// Install game via session data
$url_id = $GPXIN['id'];

if(empty($url_id) || !is_numeric($url_id)) die('ERROR: Invalid ID given!');

if(!isset($_SESSION['cld_gameid'])) die('No cloud data found!  Close the dialog and click "Install" again to fix');
if($url_id != $_SESSION['cld_gameid']) die('Mismatched cloud data!  Close the dialog and click "Install" again to fix');

$cloud_gameid   = $_SESSION['cld_gameid'];
$cloud_arr      = json_decode($_SESSION['cld_gamedata'], true);

$cld_date_created   = mysqli_real_escape_string($connection, $cloud_arr[0]['date_created']);
$cld_last_updated   = mysqli_real_escape_string($connection, $cloud_arr[0]['last_updated']);
$cld_is_steam       = mysqli_real_escape_string($connection, $cloud_arr[0]['steam']);
$cld_steam_name     = mysqli_real_escape_string($connection, $cloud_arr[0]['steam_name']);
$cld_name           = mysqli_real_escape_string($connection, $cloud_arr[0]['name']);
$cld_description    = mysqli_real_escape_string($connection, $cloud_arr[0]['description']);
$cld_icon           = mysqli_real_escape_string($connection, $cloud_arr[0]['icon']);
$cld_port           = mysqli_real_escape_string($connection, $cloud_arr[0]['port']);
$cld_gameq          = mysqli_real_escape_string($connection, $cloud_arr[0]['gameq_name']);
$cld_intname        = mysqli_real_escape_string($connection, $cloud_arr[0]['intname']);
$cld_working_dir    = mysqli_real_escape_string($connection, $cloud_arr[0]['working_dir']);
$cld_pid_file       = mysqli_real_escape_string($connection, $cloud_arr[0]['pid_file']);
$cld_simplecmd      = mysqli_real_escape_string($connection, $cloud_arr[0]['simplecmd']);
$cld_update_cmd     = mysqli_real_escape_string($connection, $cloud_arr[0]['update_cmd']);
$cld_banned_chars   = mysqli_real_escape_string($connection, $cloud_arr[0]['banned_chars']);
$cld_maxpl          = mysqli_real_escape_string($connection, $cloud_arr[0]['maxplayers']);
$cld_startup        = mysqli_real_escape_string($connection, $cloud_arr[0]['startup']);
$cld_type           = mysqli_real_escape_string($connection, $cloud_arr[0]['type']);
$cld_cfg_sep        = mysqli_real_escape_string($connection, $cloud_arr[0]['cfg_separator']);
$cld_cfg_ip         = mysqli_real_escape_string($connection, $cloud_arr[0]['cfg_ip']);
$cld_cfg_port       = mysqli_real_escape_string($connection, $cloud_arr[0]['cfg_port']);
$cld_cfg_maxpl      = mysqli_real_escape_string($connection, $cloud_arr[0]['cfg_maxplayers']);
$cld_cfg_map        = mysqli_real_escape_string($connection, $cloud_arr[0]['cfg_map']);
$cld_cfg_hostname   = mysqli_real_escape_string($connection, $cloud_arr[0]['cfg_hostname']);
$cld_cfg_rcon       = mysqli_real_escape_string($connection, $cloud_arr[0]['cfg_rcon']);
$cld_cfg_passw      = mysqli_real_escape_string($connection, $cloud_arr[0]['cfg_password']);
$cld_map            = mysqli_real_escape_string($connection, $cloud_arr[0]['map']);
$cld_hostname       = mysqli_real_escape_string($connection, $cloud_arr[0]['hostname']);
$cld_config_file    = mysqli_real_escape_string($connection, $cloud_arr[0]['config_file']);
$cld_inst_mirr      = mysqli_real_escape_string($connection, $cloud_arr[0]['install_mirrors']);
$cld_inst_cmd       = mysqli_real_escape_string($connection, $cloud_arr[0]['install_cmd']);


// Make sure we have data
if(empty($cld_name) || empty($cld_date_created) || empty($cld_port)) die('Insufficient data received from the GamePanelX Cloud!');

#echo '<pre>';
#var_dump($cloud_arr);
#echo '</pre>';

########################################################################

// Get default ID
$result_id  = @mysqli_query($connection, "SELECT id FROM default_games WHERE cloudid = '$url_id' ORDER BY id LIMIT 1");
$row_id     = mysqli_fetch_row($result_id);
$def_id     = $row_id[0];

// Delete any existing default rows or startup items for this game
@mysqli_query($connection, "DELETE FROM default_games WHERE cloudid = '$url_id'");
@mysqli_query($connection, "DELETE FROM default_startup WHERE defid = '$def_id'");

########################################################################

// Insert main row
/*
@mysql_query("INSERT INTO default_games (cloudid,port,maxplayers,startup,steam,type,cfg_separator,gameq_name,name,intname,steam_name,working_dir,pid_file,banned_chars,description,update_cmd,simplecmd) 
              VALUES('$url_id','$cld_port','$cld_is_steam','$cld_gameq',
              '$cld_name','$cld_intname','$cld_steam_name','$cld_working_dir',
              '$cld_pid_file','$cld_banned_chars','$cld_description','$cld_update_cmd','$cld_simplecmd')") or die('Failed to insert game');
              */
              
@mysqli_query($connection, "INSERT INTO `default_games` (`cloudid`, `port`, `maxplayers`, `startup`, `steam`, `type`, `cfg_separator`, `gameq_name`, `name`, `intname`, `working_dir`, `pid_file`, `banned_chars`, `cfg_ip`, `cfg_port`, `cfg_maxplayers`, `cfg_map`, `cfg_hostname`, `cfg_rcon`, `cfg_password`, `map`, `hostname`, `config_file`, `steam_name`, `description`, `install_mirrors`, `install_cmd`, `update_cmd`, `simplecmd`) VALUES ('$url_id', '$cld_port', '$cld_maxpl', '$cld_startup', '$cld_is_steam', '$cld_type', '$cld_cfg_sep', '$cld_gameq', '$cld_name', '$cld_intname', '$cld_working_dir', '$cld_pid_file', '$cld_banned_chars', '$cld_cfg_ip', '$cld_cfg_port', '$cld_cfg_maxpl', '$cld_cfg_map', '$cld_cfg_hostname', '$cld_cfg_rcon', '$cld_cfg_passw', '$cld_map', '$cld_hostname', '$cld_config_file', '$cld_steam_name', '$cld_description', '$cld_inst_mirr', '$cld_inst_cmd', '$cld_update_cmd', '$cld_simplecmd')") or die('Failed to insert game: '.mysqli_error($connection));

$this_defid = mysqli_insert_id($connection);

########################################################################

//
// Startup Items
//
if(!empty($cloud_arr[1]))
{
    // Add all startup items in 1 insert
    $startup_sql = 'INSERT INTO default_startup (defid,sort_order,single,usr_edit,cmd_item,cmd_value) VALUES';

    foreach($cloud_arr[1] as $item)
    {
        $itm_sort       = $item['sort_order'];
        $itm_single     = $item['single'];
        $itm_usr_edit   = $item['usr_edit'];
        $itm_cmd_item   = $item['cmd_item'];
        $itm_cmd_value  = $item['cmd_value'];
        
        $startup_sql .= "('$this_defid','$itm_sort','$itm_single','$itm_usr_edit','$itm_cmd_item','$itm_cmd_value'),";
    }

    // Strip last comma
    $startup_sql  = substr($startup_sql, 0, -1);

    // Run the insert
    @mysqli_query($connection, $startup_sql) or die('Failed to insert startup items');
}


########################################################################

// Update `servers` and `templates` with new default ID
@mysqli_query($connection, "UPDATE servers SET defid = '$this_defid' WHERE defid = '$def_id'") or die('Failed to update servers');
@mysqli_query($connection, "UPDATE templates SET cfgid = '$this_defid' WHERE cfgid = '$def_id'") or die('Failed to update templates');

// Kill cloud session data
unset($_SESSION['cld_gameid']);
unset($_SESSION['cld_gamedata']);

########################################################################

// Save small icon
$fp = fopen(DOCROOT.'/images/gameicons/small/'.$cld_intname.'.png', 'w+');
$ch = curl_init('http://gamepanelx.com/cloud/icons/small/'.$cld_intname.'.png');
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
curl_close($ch);
fclose($fp);

// Save medium icon
$fp = fopen(DOCROOT.'/images/gameicons/medium/'.$cld_intname.'.png', 'w+');
$ch = curl_init('http://gamepanelx.com/cloud/icons/medium/'.$cld_intname.'.png');
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
curl_close($ch);
fclose($fp);

########################################################################

// Final output
echo 'success';

?>
