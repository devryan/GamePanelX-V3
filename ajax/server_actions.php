<?php
require('checkallowed.php'); // No direct access

error_reporting(E_ERROR);


// Server actions
$url_id     = $GPXIN['id'];
$url_do     = $GPXIN['do'];
$url_netid  = $GPXIN['netid'];
$url_gameid = $GPXIN['gameid'];
$gpx_srvid=$url_id; require(DOCROOT.'/checkallowed.php'); // Check login/ownership

require(DOCROOT.'/includes/classes/servers.php');
$Servers  = new Servers;



// Gameserver restart
if($url_do == 'restart')
{
    echo $Servers->restart($url_id);
}

// Gameserver restart
elseif($url_do == 'stop')
{
    echo $Servers->stop($url_id);
}

// Gameserver update
elseif($url_do == 'update')
{
    echo $Servers->update($url_id);
}


// Save Server Settings
elseif($url_do == 'settings_save')
{
    // Save Server Settings via server settings tab
    $url_netid        = $GPXIN['ip'];
    $url_descr        = strip_tags($GPXIN['description']);
    $url_userid       = $GPXIN['userid'];
    $url_updatecmd    = $GPXIN['update_cmd'];
    $url_cmd          = $GPXIN['cmd'];
    $url_startup      = $GPXIN['startup'];
    $url_port         = $GPXIN['port'];
    $url_working_dir  = $GPXIN['working_dir'];
    $url_pid_file     = $GPXIN['pid_file'];
    
    if(preg_match('/^\./', $url_working_dir)) $url_working_dir = '';
    
    // Get current info
    $srvinfo        = $Servers->getinfo($url_id);
    $orig_userid    = $srvinfo[0]['userid'];
    $orig_netid     = $srvinfo[0]['netid'];
    $orig_port      = $srvinfo[0]['port'];
    $orig_username  = $srvinfo[0]['username'];
    $orig_ip        = $srvinfo[0]['ip'];
    
    // Check if IP:Port combo already exists
    if($url_netid != $orig_netid && isset($_SESSION['gpx_admin']))
    {
        if(!$Servers->checkcombo($url_netid,$url_port)) die($lang['ip_port_used']);
    }
    
    ########################################################################
    
    // Need to move gameserver directories (user,ip,or port changes)
    if(isset($_SESSION['gpx_admin']))
    {
        if($url_userid != $orig_userid || $url_netid != $orig_netid || $url_port != $orig_port)
        {
            // Move directory on gameserver
            $srv_move = $Servers->moveserver($url_id,$orig_userid,$orig_username,$orig_netid,$orig_ip,$orig_port,$url_userid,$url_netid,$url_port);
            
            if($srv_move != 'success')
            {
                die('Failed to move server: '.$srv_move);
            }
        }
    }
    
    ########################################################################
    
    // Admins
    if(isset($_SESSION['gpx_admin'])) @mysql_query("UPDATE servers SET netid = '$url_netid',userid = '$url_userid',port = '$url_port',startup = '$url_startup',last_updated = NOW(),working_dir = '$url_working_dir',pid_file = '$url_pid_file',description = '$url_descr',update_cmd = '$url_updatecmd',simplecmd = '$url_cmd' WHERE id = '$url_id'") or die('Failed to update server settings: '.mysql_error());
    
    // Users
    else @mysql_query("UPDATE servers SET last_updated = NOW(),working_dir = '$url_working_dir',description = '$url_descr' WHERE id = '$url_id' AND userid = '$gpx_userid'") or die('Failed to update server settings!');
    
    echo 'success';
}




// Save Startup Values
elseif($url_do == 'startup_save')
{
    $sort_order   = $GPXIN['sort_list'];
    $startup_type = $GPXIN['start_type'];

    // Get server info
    $srvinfo    = $Servers->getinfo($url_id);

    // If simple, update and exit
    if(isset($_SESSION['gpx_admin']))
    {
        if($startup_type == 'smp')
        {
            @mysql_query("UPDATE servers SET startup = '0' WHERE id = '$url_id'") or die('Failed to update startup type');
            echo 'success';
            exit;
        }
        // If Startup, update and continue
        elseif($startup_type == 'str')
        {
            @mysql_query("UPDATE servers SET startup = '1' WHERE id = '$url_id'") or die('Failed to update startup type');
            
            if($srvinfo[0]['startup'] == 0) exit;
        }
    }

    // Begin multi-update queries for all startup items and values
    $update_item_query  = 'UPDATE `servers_startup` SET `cmd_item` = CASE `id` ';
    $update_val_query   = 'UPDATE `servers_startup` SET `cmd_value` = CASE `id` ';
    $update_usred_query = 'UPDATE `servers_startup` SET `usr_edit` = CASE `id` ';
    if($sort_order) $update_sort_query  = 'UPDATE `servers_startup` SET `sort_order` = CASE `id` ';

    // Adding items
    $add_query  = 'INSERT INTO servers_startup (srvid,cmd_item,cmd_value,usr_edit) VALUES';
    
    # Loop through input
    foreach($GPXIN as $item => $val)
    {
        $arr_itms = explode('_', $item);
        $item_id  = $arr_itms[1];
        $banned_char_arr  = str_split($srvinfo[0]['banned_chars']);
        
        // Item / Value
        if(preg_match('/^stritm_/', $item)) {   $update_item_query  .= 'WHEN \'' . $item_id . '\' THEN \'' . $val . '\' '; }
        elseif(preg_match('/^strval_/', $item))
        {
            // Strip any bad chars out
            if(!isset($_SESSION['gpx_admin']))
            {
                foreach($banned_char_arr as $badchar)
                {
                    // Strip out each bad character
                    $val  = str_replace($badchar, '', $val);
                }
            }
            
            $update_val_query   .= 'WHEN \'' . $item_id . '\' THEN \'' . $val . '\' ';
        }
        elseif(preg_match('/^usred_/', $item)) {  $update_usred_query .= 'WHEN \'' . $item_id . '\' THEN \'' . $val . '\' '; }
        
        // Adding items
        elseif(preg_match('/^additm_/', $item)) {  $add_query  .=  "('$url_id','$val',"; }
        elseif(preg_match('/^addval_/', $item))
        {
            // Strip any bad chars out
            if(!isset($_SESSION['gpx_admin']))
            {
                foreach($banned_char_arr as $badchar)
                {
                    // Strip out each bad character
                    $val  = str_replace($badchar, '', $val);
                }
            }
            
            $add_query  .= "'$val',";
        }
        elseif(preg_match('/^addusred_/', $item)) { $add_query  .= "'$val'),"; }
    }
    
    // Update Sort Order
    if($sort_order)
    {
        $order_arr  = explode(',', $sort_order);
        $sort_cnt   = 0;
        
        foreach($order_arr as $sort_item)
        {
            $sort_id  = str_replace('sortitm_', '', $sort_item);
            $update_sort_query .= 'WHEN \'' . $sort_id . '\' THEN \'' . $sort_cnt . '\' ';
            
            $sort_cnt++;
        }
    }
    
    // Finish queries
    $update_item_query  .= ' ELSE `cmd_item` END';
    $update_val_query   .= ' ELSE `cmd_value` END';
    $update_usred_query .= ' ELSE `usr_edit` END';
    if($sort_order) $update_sort_query .= ' ELSE `sort_order` END';
    
    ############################################################################
    
    // Run insert(s)
    if(isset($_SESSION['gpx_admin']) && !preg_match('/VALUES$/', $add_query))
    {
        $add_query  = substr($add_query, 0, -1); // Lose last comma
        @mysql_query($add_query) or die('Failed to add items: '.mysql_error());
    }
    
    // Admins only
    if(isset($_SESSION['gpx_admin']))
    {
        @mysql_query($update_item_query) or die('Failed to update items: '.mysql_error());
        @mysql_query($update_usred_query) or die('Failed to update user editable: '.mysql_error());
        if($sort_order) @mysql_query($update_sort_query) or die('Failed to update sorting order: '.mysql_error());
    }
    
    // Run updates
    @mysql_query($update_val_query) or die('Failed to update values: '.mysql_error());
    
    ############################################################################
    
    // Update simplecmd with most recent order
    $upd_cmd  = $Servers->update_startup_cmd($url_id,$srvinfo[0]['ip'],$srvinfo[0]['port']);
    if($upd_cmd != 'success') die('Failed to update cmd: '.$upd_cmd);
    
    echo 'success';
}




// Delete startup item
elseif($url_do == 'startup_del_item')
{
    $server_id  = $GPXIN['serverid'];
    if(empty($url_id) || empty($server_id)) die('No startup ID or server ID specified!');
    
    @mysql_query("DELETE FROM servers_startup WHERE id = '$url_id' AND srvid = '$server_id'") or die('Failed to delete the startup item');
    
    // Get info for cmd rebuild / Rebuild cmd line
    $srvinfo    = $Servers->getinfo($server_id);
    $upd_cmd    = $Servers->update_startup_cmd($server_id,$srvinfo[0]['ip'],$srvinfo[0]['port']);
    if($upd_cmd != 'success') die('Failed to update cmd: '.$upd_cmd);
    
    echo 'success';
}







// Create Server
elseif($url_do == 'create')
{
    $url_descr    = $GPXIN['desc'];
    $url_port     = $GPXIN['port'];
    $url_ownerid  = $GPXIN['ownerid'];
    
    echo $Servers->create($url_netid,$url_gameid,$url_ownerid,$url_port,$url_descr);
}


// Delete Server
elseif($url_do == 'delete')
{
    echo $Servers->delete($url_id);
}




// Create Server - get port for server
elseif($url_do == 'create_getport')
{
    $result_port  = @mysql_query("SELECT port FROM default_games WHERE id = '$url_gameid' LIMIT 1");
    $row_port     = mysql_fetch_row($result_port);
    $this_port    = $row_port[0];
    
    if(empty($this_port)) echo '(none found)';
    else echo $this_port;
}





// Get PID(s), CPU and Memory info for a server
elseif($url_do == 'getinfo')
{
    echo $Servers->getcpuinfo($url_id);
}

?>
