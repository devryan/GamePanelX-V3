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
    $url_maxpl        = $GPXIN['maxplayers'];
    $url_hostn        = $GPXIN['hostname'];
    $url_map          = $GPXIN['map'];
    $url_rcon         = $GPXIN['rcon'];
    $url_passw        = $GPXIN['sv_password'];
    
    if(preg_match('/^\./', $url_working_dir)) $url_working_dir = '';
    
    // Get current info
    $srvinfo        = $Servers->getinfo($url_id);
    $orig_userid    = $srvinfo[0]['userid'];
    $orig_netid     = $srvinfo[0]['netid'];
    $orig_port      = $srvinfo[0]['port'];
    $orig_username  = $srvinfo[0]['username'];
    $orig_ip        = $srvinfo[0]['ip'];
    $config_file    = $srvinfo[0]['config_file'];
    
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
    if(isset($_SESSION['gpx_admin'])) @mysql_query("UPDATE servers SET netid = '$url_netid',userid = '$url_userid',port = '$url_port',startup = '$url_startup',last_updated = NOW(),working_dir = '$url_working_dir',pid_file = '$url_pid_file',description = '$url_descr',update_cmd = '$url_updatecmd',simplecmd = '$url_cmd',maxplayers = '$url_maxpl',hostname = '$url_hostn',map = '$url_map',rcon = '$url_rcon',sv_password = '$url_passw' WHERE id = '$url_id'") or die('Failed to update server settings: '.mysql_error());
    
    // Users
    else @mysql_query("UPDATE servers SET last_updated = NOW(),working_dir = '$url_working_dir',description = '$url_descr' WHERE id = '$url_id' AND userid = '$gpx_userid'") or die('Failed to update server settings!');
    
    ########################################################################
    
    // Update gameserver config file if needed
    if(!$url_startup && !empty($config_file))
    {
        // Server values
        $config_sepa    = $srvinfo[0]['cfg_separator'];
        $cfg_ip         = $srvinfo[0]['cfg_ip'];
        $cfg_port       = $srvinfo[0]['cfg_port'];
        $cfg_map        = $srvinfo[0]['cfg_map'];
        $cfg_maxpl      = $srvinfo[0]['cfg_maxplayers'];
        $cfg_hostn      = $srvinfo[0]['cfg_hostname'];
        $cfg_rcon       = $srvinfo[0]['cfg_rcon'];
        $cfg_passw      = $srvinfo[0]['cfg_password'];
        
        require(DOCROOT.'/includes/classes/network.php');
        $Network  = new Network;
        $net_info = $Network->netinfo($orig_netid);
        $net_local  = $net_info['is_local'];
        
        // Local Server
        if($net_local)
        {
            $cfg_file = DOCROOT.'/_SERVERS/' . $_SESSION['gamesrv_root'] . '/' . stripslashes($config_file);
            
            $fh = fopen($cfg_file, 'r');
            $file_lines = fread($fh, 4096);
            fclose($fh);
            
            // Lose excess newlines
            $file_lines = preg_replace("/\n+/", "\n", $file_lines);
            
            $arr_lines  = explode("\n", $file_lines);
            $new_file   = '';
            
            foreach($arr_lines as $file_lines)
            {
                // Setup all changes
                $file_lines = preg_replace("/^$cfg_ip.*/", $cfg_ip . $config_sepa . $orig_ip, $file_lines);
                $file_lines = preg_replace("/^$cfg_port.*/", $cfg_port . $config_sepa . $url_port, $file_lines);
                $file_lines = preg_replace("/^$cfg_map.*/", $cfg_map . $config_sepa . $url_map, $file_lines);
                $file_lines = preg_replace("/^$cfg_maxpl.*/", $cfg_maxpl . $config_sepa . $url_maxpl, $file_lines);
                $file_lines = preg_replace("/^$cfg_hostn.*/", $cfg_hostn . $config_sepa . $url_hostn, $file_lines);
                $file_lines = preg_replace("/^$cfg_rcon.*/", $cfg_rcon . $config_sepa . $url_rcon, $file_lines);
                $file_lines = preg_replace("/^$cfg_passw.*/", $cfg_passw . $config_sepa . $url_passw, $file_lines);
                
                $new_file .= $file_lines . "\n";
            }
            
            // Write changes to file
            $fh = fopen($cfg_file, 'w') or die('Failed to open local config ('.$cfg_file.') for writing.');
            fwrite($fh, $new_file);
            fclose($fh);
            
            echo 'success';
            exit;
        }
        // Remote Server
        else
        {
            // Add exhaustive list of config options to this script (no, the option letters dont make sense, they are random because there are so many)
            $ssh_cmd = "ConfigUpdate -u $orig_username -i $orig_ip -p $url_port -c '$config_file' -s '$config_sepa' ";
            $ssh_cmd .= "-d $cfg_ip -e $cfg_port -f $cfg_map -g $cfg_maxpl -h $cfg_rcon -j $cfg_hostn -r $cfg_passw ";
            $ssh_cmd .= "-k '$orig_ip' -l '$url_port' -m '$url_map' -n '$url_maxpl' -O '$url_rcon' -q '$url_hostn' -t '$url_passw'";
            
            echo $Network->runcmd($orig_netid,$net_info,$ssh_cmd,true);
            exit;
        }
    }
    
    ########################################################################
    
    // Output
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





// Get server log output
elseif($url_do == 'getoutput')
{
    echo $Servers->getoutput($url_id);
}

// Send command via GNU Screen to server
elseif($url_do == 'sendscreencmd')
{
    $url_cmd  = $GPXIN['cmd'];
    echo $Servers->send_screen_cmd($url_id,$url_cmd);
}


// Multi-server query
elseif($url_do == 'multi_query')
{
    // Game or voice or all
    $url_type = $GPXIN['t'];
    if($url_type == 'g') $sql_where = "WHERE s.type = 'game'";
    elseif($url_type == 'v') $sql_where = "WHERE s.type = 'voice'";
    else $sql_where = '';

    // List servers
    $total_srv  = 0;
    $result_srv = @mysql_query("SELECT 
                                  s.id,
                                  s.userid,
                                  s.port,
                                  s.status,
                                  s.description,
                                  d.intname,
                                  d.gameq_name,
                                  d.name,
                                  n.ip,
                                  u.username 
                                FROM servers AS s 
                                LEFT JOIN default_games AS d ON 
                                  s.defid = d.id 
                                LEFT JOIN network AS n ON 
                                  s.netid = n.id 
                                LEFT JOIN users AS u ON 
                                  s.userid = u.id 
                                $sql_where 
                                ORDER BY 
                                  s.id DESC,
                                  n.ip ASC 
                                LIMIT 30") or die($lang['err_query'].' ('.mysql_error().')');

    $srv_arr    = array();
    #$gameq_arr  = array();

    while($row_srv  = mysql_fetch_assoc($result_srv))
    {
        $srv_arr[]  = $row_srv;
        
        // Add in GameQ required info - id, type, host (ip:port)
        if($row_srv['id'])          $gameq_arr[$total_srv]['id']   = $row_srv['id'];
        if($row_srv['gameq_name'])  $gameq_arr[$total_srv]['type'] = $row_srv['gameq_name'];
        if($row_srv['port'])        $gameq_arr[$total_srv]['host'] = ':' . $row_srv['port'];
        if($row_srv['ip'])          $gameq_arr[$total_srv]['host'] = $row_srv['ip'] . $gameq_arr[$total_srv]['host'];
        
        $total_srv++;
    }

    // Get GameQ status
    require(DOCROOT.'/includes/GameQv2/GameQ.php');
    $gq = new GameQ();
    $gq->addServers($gameq_arr);
    $gq->setOption('timeout', 8);
    $gq->setFilter('normalise');
    $gq_results = $gq->requestData();

    #echo '<pre>';
    #var_dump($gq_results);
    #echo '</pre>';
    
    // Loop through servers
    foreach($srv_arr as $row_srv)
    {
        $srv_id           = $row_srv['id'];
        $srv_userid       = $row_srv['userid'];
        $srv_ip           = $row_srv['ip'];
        $srv_port         = $row_srv['port'];
        $srv_status       = $row_srv['status'];
        $srv_description  = $row_srv['description'];
        $srv_def_name     = $row_srv['name'];
        $srv_def_intname  = $row_srv['intname'];
        $srv_gameq_name   = $row_srv['gameq_name'];
        $srv_username     = $row_srv['username'];
        $gameq_status     = $gq_results[$srv_id]['gq_online'];
        $gameq_numplayers = $gq_results[$srv_id]['gq_numplayers'];
        $gameq_maxplayers = $gq_results[$srv_id]['gq_maxplayers'];
        
        // Use correct status; if complete, show online/offline
        if($srv_status == 'complete')
        {
            // GameQ Server Statuses
            if($gameq_status == 'online') $srv_status = '<font color="green">'.$lang['online'].'</font>';
            elseif(!$gameq_status) $srv_status = '<font color="red">'.$lang['offline'].'</font>';
            else $srv_status = $lang['unknown'];
        }
        elseif($srv_status == 'installing')
        {
            $srv_status = '<font color="blue">'.$lang['installing'].' ...</font>';
        }
        elseif($srv_status == 'failed')
        {
            $srv_status = '<font color="red">'.$lang['failed'].'!</font>';
        }
        elseif($srv_status == 'none')
        {
            $srv_status = '<font color="orange">'.$lang['unknown'].'</font>';
        }
        
        echo '<tr id="srv_' . $srv_id . '" style="cursor:pointer;" onClick="javascript:server_tab_info(' . $srv_id . ');">
                <td><img src="../images/gameicons/small/' . $srv_def_intname . '.png" width="20" height="20" border="0" /></td>
                <td>' . $srv_def_name . '</td>
                <td>' . $srv_username . '</td>
                <td>' . $srv_ip . ':' . $srv_port . '</td>
                <td style="font-size:10pt;">' . $srv_description . '</td>
                
                <td id="statustd_' . $srv_id . '">'.$srv_status;
                
                // Connected Players
                if($gameq_status == 'online') echo '&nbsp;<span style="font-size:8pt;color:#777;">' . $gameq_numplayers . '/' . $gameq_maxplayers . '</span>';
                else echo '&nbsp;';
                
                echo '</td>
                <td class="links">'.$lang['manage'].'</td>
              </tr>';
        
        unset($this_gqarr);
    }
}






// Multi-server query (with JSON input)
elseif($url_do == 'multi_query_json')
{
    $json_data  = json_decode(stripslashes($GPXIN['json']), true);
    
    #var_dump($json_data);
    
    
    // Get GameQ status
    require(DOCROOT.'/includes/GameQv2/GameQ.php');
    $gq = new GameQ();
    $gq->addServers($json_data);
    $gq->setOption('timeout', 8);
    $gq->setFilter('normalise');
    $gq_results = $gq->requestData();
    
    $json_out = array();
    $json_cnt = 0;
    
    // Make simple response (id, status)
    foreach($gq_results as $key=>$value)
    {
        $gq_online      = $value['gq_online'];
        $gq_numplayers  = $value['gq_numplayers'];
        $gq_maxplayers  = $value['gq_maxplayers'];
        
        if($gq_online)  $srv_status = '<font color="green">'.$lang['online'].'</font>&nbsp;<span style="font-size:8pt;color:#777;">' . $gq_numplayers . '/' . $gq_maxplayers . '</span>';
        else $srv_status = '<font color="red">'.$lang['offline'].'</font>';
        
        $json_out[$json_cnt]['id']      = $key;
        $json_out[$json_cnt]['status']  = $srv_status;
        
        $json_cnt++;
    }
    
    echo json_encode($json_out);
}

?>
