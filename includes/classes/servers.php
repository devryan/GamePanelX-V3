<?php
class Servers
{
    // Query for info on a server (assumes $srvid is already escaped)
    public function getinfo($srvid)
    {
        if(empty($srvid)) return 'No server ID provided';
        
        $srv_info   = array();
        
        $result_srv = @mysql_query("SELECT 
                                      s.id,
                                      s.userid,
                                      s.netid,
                                      s.port,
                                      s.maxplayers,
                                      s.startup,
                                      DATE_FORMAT(s.date_created, '%c/%e/%Y %h:%i%p') AS date_added,
                                      DATE_FORMAT(s.last_updated, '%c/%e/%Y %h:%i%p') AS last_updated,
                                      s.description,
                                      s.status,
                                      s.working_dir,
                                      s.pid_file,
                                      s.update_cmd,
                                      s.simplecmd,
                                      s.map,
                                      s.hostname,
                                      s.sv_password,
                                      s.rcon,
                                      n.ip,
                                      u.username,
                                      p.id AS parentid,
                                      d.steam,
                                      d.type,
                                      d.config_file,
                                      d.gameq_name,
                                      d.banned_chars,
                                      d.cfg_separator,
                                      d.cfg_ip,
                                      d.cfg_port,
                                      d.cfg_maxplayers,
                                      d.cfg_map,
                                      d.cfg_hostname,
                                      d.cfg_rcon,
                                      d.cfg_password,
                                      d.steam_name 
                                    FROM servers AS s 
                                    LEFT JOIN network AS n ON 
                                      s.netid = n.id 
                                    JOIN network AS p ON 
                                      n.parentid = p.id 
                                      OR n.parentid = '0' 
                                    LEFT JOIN users AS u ON 
                                      s.userid = u.id 
                                    LEFT JOIN default_games AS d ON 
                                      s.defid = d.id 
                                    WHERE 
                                      s.id = '$srvid' 
                                    LIMIT 1") or die('Failed to query for servers: '.mysql_error());
        
        while($row_srv = mysql_fetch_assoc($result_srv))
        {
            $srv_info[] = $row_srv;
        }
        
        // Return array of data
        return $srv_info;
    }
    
    
    
    
    // Query a single server with GameQ V2
    public function query($srv_arr)
    {
        // No GameQ type - try a basic TCP check
        if(empty($srv_arr[0]['gameq_name']) || $srv_arr[0]['gameq_name'] == 'none')
        {
            // Setup language
            require(DOCROOT.'/lang.php');
            
            $results  = array();
            
            // Offline / Not responding
            if(!fsockopen($srv_arr[0]['ip'], $srv_arr[0]['port'], $errno, $errstr, 4)) $srv_status = strtolower($lang['offline']);
            
            // Online / Responding to TCP check
            else $srv_status = strtolower($lang['online']);
            
            // Add status
            $srv_id = $srv_arr[0]['id'];
            $results[$srv_id]['gq_online']  = $srv_status;
        }
        // GameQ query
        else
        {
            require(DOCROOT.'/includes/GameQv2/GameQ.php');
            
            $server = array(
                'id' => $srv_arr[0]['id'],
                'type' => $srv_arr[0]['gameq_name'],
                'host' => $srv_arr[0]['ip'].':'.$srv_arr[0]['port']
            );
            
            // Call the class, and add your servers.
            $gq = new GameQ();
            $gq->addServer($server);
            
            // You can optionally specify some settings
            $gq->setOption('timeout', 5); // Seconds
            #$gq->setOption('debug', TRUE);
            $gq->setFilter('normalise');
            $results = $gq->requestData();
        }
        
        return $results;
    }
    
    
    
    
    
    
    // Restart a gameserver (change status)
    public function restart($srvid)
    {
        error_reporting(E_ERROR);
        
        if(empty($srvid)) return 'No server ID given';
        
        $srv_info = $this->getinfo($srvid);
        
        $srv_username   = $srv_info[0]['username'];
        $srv_ip         = $srv_info[0]['ip'];
        $srv_port       = $srv_info[0]['port'];
        $srv_cmd        = $srv_info[0]['simplecmd'];
        $srv_work_dir   = $srv_info[0]['working_dir'];
        $srv_pid_file   = $srv_info[0]['pid_file'];
        $srv_netid      = $srv_info[0]['parentid'];
        
        #var_dump($srv_info);
        
        // Double-check required
        if(empty($srv_username) || empty($srv_ip) || empty($srv_port) || empty($srv_cmd)) return 'restart class: Required values were left out';
        
        // Working dir, PID file
        if($srv_work_dir) $srv_work_dir = '-w ' . $srv_work_dir;
        if($srv_pid_file) $srv_pid_file = '-P ' . $srv_pid_file;
        
        // Run the command
        $ssh_cmd      = "Restart -u $srv_username -i $srv_ip -p $srv_port $srv_pid_file $srv_work_dir -o '$srv_cmd'";
        
        require('network.php');
        $Network  = new Network;
        $net_info = $Network->netinfo($srv_netid);
        
        $ssh_response = $Network->runcmd($srv_netid,$net_info,$ssh_cmd,true,$srvid);
        
        // Should return 'success'
        return $ssh_response;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // Stop a gameserver (change status)
    public function stop($srvid)
    {
        error_reporting(E_ERROR);
        
        if(empty($srvid)) return 'No server ID given';
        
        // Get network info to SSH in
        $srv_info = $this->getinfo($srvid);
        
        $srv_username   = $srv_info[0]['username'];
        $srv_ip         = $srv_info[0]['ip'];
        $srv_port       = $srv_info[0]['port'];
        $srv_netid      = $srv_info[0]['parentid'];
        $srv_work_dir   = $srv_info[0]['working_dir'];
        $srv_pid_file   = $srv_info[0]['pid_file'];
        
        if($srv_work_dir) $srv_work_dir = ' -w ' . $srv_work_dir;
        if($srv_pid_file) $srv_pid_file = ' -P ' . $srv_pid_file;
        
        #var_dump($srv_info);
        
        // Double-check required
        if(empty($srv_username) || empty($srv_ip) || empty($srv_port)) return 'stop class: Required values were left out';
        
        // Force back to completed if updating
        if($srv_info[0]['status'] == 'updating') @mysql_query("UPDATE servers SET status = 'complete' WHERE id = '$srvid'");
        
        // Run the command
        $ssh_cmd  = "Stop -u $srv_username -i $srv_ip -p $srv_port $srv_work_dir $srv_pid_file";
        
        require('network.php');
        $Network  = new Network;
        $net_info = $Network->netinfo($srv_netid);
        
        $ssh_response = $Network->runcmd($srv_netid,$net_info,$ssh_cmd,true,$srvid);
        
        // Should return 'success'
        return $ssh_response;
    }
    
    
    // Update a gameserver
    public function update($srvid)
    {
        error_reporting(E_ERROR);
        $Core = new Core;
        
        if(empty($srvid)) return 'No server ID given';
        
        // Get network info to SSH in
        $srv_info = $this->getinfo($srvid);
        
        $srv_username     = $srv_info[0]['username'];
        $srv_ip           = $srv_info[0]['ip'];
        $srv_port         = $srv_info[0]['port'];
        $srv_update_cmd   = $srv_info[0]['update_cmd'];
        $srv_netid        = $srv_info[0]['parentid'];
        $srv_is_steam     = $srv_info[0]['steam'];
        $srv_steam_name   = $srv_info[0]['steam_name'];
        
        if($srv_is_steam)
        {
            $settings = $Core->getsettings();
            $cfg_steam_user   = $settings['steam_login_user'];
            $cfg_steam_pass   = $settings['steam_login_pass'];
            $cfg_steam_auth   = $settings['steam_auth'];
            $cfg_steam_user=substr($cfg_steam_user, 6);$cfg_steam_user=substr($cfg_steam_user, 0, -6);$cfg_steam_user=base64_decode($cfg_steam_user);
            $cfg_steam_pass=substr($cfg_steam_pass, 6);$cfg_steam_pass=substr($cfg_steam_pass, 0, -6);$cfg_steam_pass=base64_decode($cfg_steam_pass);
            
            if($cfg_steam_auth) $cfg_steam_auth = "-f '$cfg_steam_auth'";
            $add_steam  = "-g '$srv_steam_name' -d '$cfg_steam_user' -e '$cfg_steam_pass' $cfg_steam_auth";
        }
        else
        {
            $add_steam  = '';
        }
        
        #var_dump($srv_info);
        
        // Double-check required
        if(empty($srv_username) || empty($srv_ip) || empty($srv_port) || empty($srv_update_cmd)) return 'update class: Required values were left out';
        
        // Generate and store random token for remote server callback
        $remote_token = $Core->genstring('16');
        @mysql_query("UPDATE servers SET token = '$remote_token' WHERE id = '$srvid'") or die('Failed to update token!');
        
        // Get callback page
        $this_url   = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
        $this_page  = str_replace('ajax/ajax.php', '', $this_url);
        $this_page  .= '/includes/callback.php?token='.$remote_token.'&id='.$srvid;
        $this_page  = preg_replace('/\/+/', '/', $this_page); // Remove extra slashes
        $this_page  = 'http://' . $this_page;
        
        // Set as updating
        #@mysql_query("UPDATE servers SET status = 'updating' WHERE id = '$srvid'") or die('Failed to update status!');
        
        // Run the command
        $ssh_cmd      = "UpdateServer -u $srv_username -i $srv_ip -p $srv_port $add_steam -c \"$this_page\" -o \"$srv_update_cmd\"";
        
        require('network.php');
        $Network  = new Network;
        $net_info = $Network->netinfo($srv_netid);
        $ssh_response = $Network->runcmd($srv_netid,$net_info,$ssh_cmd,true,$srvid);
        
        // Should return 'success'
        return $ssh_response;
    }
    
    
    
    
    
    // Check for used IP/Port combination
    public function checkcombo($netid,$port)
    {
        if(!$netid || !$port) return 'CheckCombo: No IP or Port specified!';
        
        $result_ck  = @mysql_query("SELECT id FROM servers WHERE netid = '$netid' AND port = '$port' LIMIT 1");
        $row_ck     = mysql_fetch_row($result_ck);
        
        // Return false if exists already
        if($row_ck[0]) return false;
        else return true;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    // Create a new server
    public function create($netid,$gameid,$ownerid,$port,$description,$total_slots)
    {
        if(empty($netid) || empty($gameid) || empty($ownerid)) return 'Servers: Insufficient info provided';
        
        // Generate random token for remote server callback
        $Core = new Core;
        $remote_token = $Core->genstring('16');
        
        // Check for uses IP/Port combo (false if used)
        if(!$this->checkcombo($netid,$port)) return 'Servers: That IP/Port combination is already in use!  Please choose a different IP or Port and try again.';
        
        // Get owner username
        $result_name  = @mysql_query("SELECT username FROM users WHERE id = '$ownerid' LIMIT 1");
        $row_name     = mysql_fetch_row($result_name);
        $this_usrname = $row_name[0];
        
        // Get default template
        $result_tpl  = @mysql_query("SELECT id FROM templates WHERE cfgid = '$gameid' AND status = 'complete' AND is_default = '1' ORDER BY id LIMIT 1");
        $row_tpl     = mysql_fetch_row($result_tpl);
        $this_tplid  = $row_tpl[0];
        
        
        // Setup to create on remote server
        require(DOCROOT.'/includes/classes/network.php');
        $Network  = new Network;
        $net_arr  = $Network->netinfo($netid);
        
        if(!empty($net_arr['real_ip'])) $this_ip = $net_arr['real_ip'];
        else $this_ip  = $net_arr['game_ip'];
        
        
        # if(empty($net_arr['game_ip'])) $this_ip = $net_arr['ssh_ip'];
        # else $this_ip  = $net_arr['game_ip'];
        
        // Double check everything
        if(empty($this_usrname)) return 'Servers: No username specified!';
        elseif(empty($this_ip)) return 'Servers: No IP Address specified!';
        elseif(empty($port)) return 'Servers: No port specified!';
        elseif(empty($this_tplid)) return 'Servers: No default template found for this game!';
        
        ############################################################################################
        
        // Get some defaults
        $result_dfts  = @mysql_query("SELECT maxplayers,working_dir,pid_file,update_cmd,simplecmd,map,hostname FROM default_games WHERE id = '$gameid' LIMIT 1");
        
        $row_dfts     = mysql_fetch_row($result_dfts);
        $def_working_dir  = mysql_real_escape_string($row_dfts[1]);
        $def_pid_file     = mysql_real_escape_string($row_dfts[2]);
        $def_update_cmd   = mysql_real_escape_string($row_dfts[3]);
        $def_simple_cmd   = mysql_real_escape_string($row_dfts[4]);
        $def_map          = mysql_real_escape_string($row_dfts[5]);
        $def_hostname     = mysql_real_escape_string($row_dfts[6]);
        
        // Max player slots - use what was given, otherwise use the default
        if(!empty($total_slots) && is_numeric($total_slots)) $def_maxplayers = mysql_real_escape_string($total_slots);
        else $def_maxplayers   = mysql_real_escape_string($row_dfts[0]);
        
        // Insert into db
        @mysql_query("INSERT INTO servers (userid,netid,defid,port,maxplayers,status,date_created,token,working_dir,pid_file,update_cmd,description,map,hostname) VALUES('$ownerid','$netid','$gameid','$port','$def_maxplayers','installing',NOW(),'$remote_token','$def_working_dir','$def_pid_file','$def_update_cmd','$description','$def_map','$def_hostname')") or die('Failed to insert server: '.mysql_error());
        $srv_id = mysql_insert_id();
        
        // Insert default srv settings
        $result_smp = @mysql_query("SELECT * FROM default_startup WHERE defid = '$gameid' ORDER BY sort_order ASC");
        $total_strt = mysql_num_rows($result_smp);
        
        $insert_new = 'INSERT INTO servers_startup (srvid,sort_order,single,usr_edit,cmd_item,cmd_value) VALUES ';
        $simplecmd  = '';
        
        while($row_smp  = mysql_fetch_array($result_smp))
        {
            $cmd_sort   = $row_smp['sort_order'];
            $cmd_single = $row_smp['single'];
            $cmd_usred  = $row_smp['usr_edit'];
            $cmd_item   = $row_smp['cmd_item'];
            $cmd_val    = $row_smp['cmd_value'];
            
            $insert_new .= "('$srv_id','$cmd_sort','$cmd_single','$cmd_usred','$cmd_item','$cmd_val'),";
            
            // Replace %vars% for simplecmd
            $cmd_val  = str_replace('%IP%', $this_ip, $cmd_val);
            $cmd_val  = str_replace('%PORT%', $port, $cmd_val);
            $cmd_val  = str_replace('%MAP%', $def_map, $cmd_val);
            $cmd_val  = str_replace('%MAXPLAYERS%', $def_maxplayers, $cmd_val);
            $cmd_val  = str_replace('%HOSTNAME%', $def_hostname, $cmd_val);
            
            // Update simplecmd
            $simplecmd .= $cmd_item . ' ';
            if($cmd_val || $cmd_val == '0') $simplecmd .= $cmd_val . ' ';
        }
        
        
        // Run multi-insert (only if there were default startup items)
        if($total_strt)
        {
            // Remove last comma
            $insert_new = substr($insert_new, 0, -1);
            
            @mysql_query($insert_new) or die('Failed to insert startup items: '.mysql_error());
        }
        
        // Add simplecmd
        if(empty($simplecmd)) $simplecmd = $def_simple_cmd;
        @mysql_query("UPDATE servers SET simplecmd = '$simplecmd' WHERE id = '$srv_id'");
        
        ############################################################################################
        
        // Get callback page
        $this_url   = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
        $this_page  = str_replace('ajax/ajax.php', '', $this_url);
        $this_page  .= '/includes/callback.php?token='.$remote_token.'&id='.$srv_id;
        $this_page  = preg_replace('/\/+/', '/', $this_page); // Remove extra slashes
        $this_page  = 'http://' . $this_page;
        
        ############################################################################################

        // Create on Remote server
        $net_cmd  = "CreateServer -u $this_usrname -i $this_ip -p $port -x $this_tplid -c \"$this_page\"";
        $result_net_create = $Network->runcmd($netid,$net_arr,$net_cmd,true,$srv_id);

	if($result_net_create != 'success')
	{
		// Failed on Remote Creation; delete this server
		@mysql_query("DELETE FROM servers WHERE id = '$srv_id'") or die('Failed to delete the server from the database');
		@mysql_query("DELETE FROM servers_startup WHERE srvid = '$srv_id'") or die('Failed to delete the server startups from the database');

		return $result_net_create;
	}
	else
	{
		return 'success';
	}
    }
    
    
    
    
    
    
    
    
    // Delete a gameserver
    public function delete($srvid)
    {
        if(empty($srvid)) return 'No server ID given';
        
        // Get network info to SSH in
        $srv_info = $this->getinfo($srvid);
        
        $srv_username   = $srv_info[0]['username'];
        $srv_ip         = $srv_info[0]['ip'];
        $srv_port       = $srv_info[0]['port'];
        $srv_netid      = $srv_info[0]['parentid'];
        
        
        // Run deletion on server-side
        $ssh_cmd  = "DeleteServer -u $srv_username -i $srv_ip -p $srv_port";
        
        require('network.php');
        $Network  = new Network;
        $net_info = $Network->netinfo($srv_netid);
        
        $ssh_response = $Network->runcmd($srv_netid,$net_info,$ssh_cmd,true,$srvid);
        
        // Delete from db
        @mysql_query("DELETE FROM servers WHERE id = '$srvid'") or die('Failed to delete server from database!');
        @mysql_query("DELETE FROM servers_startup WHERE srvid = '$srvid'") or die('Failed to delete server startup items from database!');
        
        
        // If actually deleted files...
        if($ssh_response == 'success')
        {
            return 'success';
        }
        else
        {
            // Can't delete the files.  Delete the server, but warn that files weren't deleted, otherwise we're stuck.
            return 'Deleted the server, but failed to delete the server files: '.$ssh_response;
        }
        
    }
    
    
    
    
    
    
    
    
    
    
    
    // Get PID(s), CPU and Memory info for a server
    public function getcpuinfo($srvid)
    {
        // All output needs to be JSON for javascript to pick it up properly
        if(empty($srvid)) return '{"error":"restart class: No server ID given"}';
        
        $srv_info = $this->getinfo($srvid);
        
        $srv_username   = $srv_info[0]['username'];
        $srv_ip         = $srv_info[0]['ip'];
        $srv_port       = $srv_info[0]['port'];
        $srv_netid      = $srv_info[0]['parentid'];
        
        // Double-check required
        if(empty($srv_username) || empty($srv_ip) || empty($srv_port)) return '{"error":"restart class: Required values were left out"}';
        
        require('network.php');
        $Network  = new Network;
        $net_info = $Network->netinfo($srv_netid);
        
        // Run the command
        $ssh_cmd      = "CheckGame -u $srv_username -i $srv_ip -p $srv_port";
        $ssh_response = $Network->runcmd($srv_netid,$net_info,$ssh_cmd,true,$srvid);
        
        // If invalid json, make it a JSON error msg
        if(!json_decode($ssh_response))
        {
            $ssh_response = addslashes($ssh_response);
            $ssh_response = '{"error":"'.$ssh_response.'"}';
        }
        
        // Should return 'success'
        return $ssh_response;
    }
    
    
    
    
    
    // Update simplecmd with most recent order
    public function update_startup_cmd($srvid,$srv_ip,$srv_port)
    {
        if(empty($srvid) || empty($srv_ip) || empty($srv_port)) return 'Insufficient info given!';
        
        $simplecmd  = '';
        $result_smp = @mysql_query("SELECT cmd_item,cmd_value FROM servers_startup WHERE srvid = '$srvid' ORDER BY sort_order ASC") or die('Failed to get startup item list!');
        
        while($row_smp  = mysql_fetch_array($result_smp))
        {
            $cmd_item = $row_smp['cmd_item'];
            $cmd_val  = $row_smp['cmd_value'];
            
            // Get other values
            $srvinfo      = $this->getinfo($srvid);
            $srv_map      = $srvinfo[0]['map'];
            $srv_maxpl    = $srvinfo[0]['maxplayers'];
            $srv_hostname = $srvinfo[0]['hostname'];
            $srv_rcon     = $srvinfo[0]['rcon'];
            $srv_passw    = $srvinfo[0]['sv_password'];
            
            // Replace %vars%
            $cmd_val  = str_replace('%IP%', $srv_ip, $cmd_val);
            $cmd_val  = str_replace('%PORT%', $srv_port, $cmd_val);
            $cmd_val  = str_replace('%MAP%', $srv_map, $cmd_val);
            $cmd_val  = str_replace('%MAXPLAYERS%', $srv_maxpl, $cmd_val);
            $cmd_val  = str_replace('%RCON%', $srv_rcon, $cmd_val);
            $cmd_val  = str_replace('%HOSTNAME%', $srv_hostname, $cmd_val);
            $cmd_val  = str_replace('%PASSWORD%', $srv_passw, $cmd_val);
            
            
            $simplecmd .= $cmd_item . ' ';
            if($cmd_val || $cmd_val == '0') $simplecmd .= $cmd_val . ' ';
        }
        
        // Update new simplecmd
        @mysql_query("UPDATE servers SET simplecmd = '$simplecmd' WHERE id = '$srvid'") or die('Failed to update cmd!');
        
        return 'success';
    }
    
    
    
    // Updating a userid or IP/Port for a server - Move server to new area
    public function moveserver($srvid,$orig_userid,$orig_username,$orig_netid,$orig_ip,$orig_port,$new_userid,$new_netid,$new_port)
    {
        // Get new username
        if($new_userid != $orig_userid)
        {
            $result_nu    = @mysql_query("SELECT username FROM users WHERE id = '$new_userid' LIMIT 1");
            $row_nu       = mysql_fetch_row($result_nu);
            $new_username = $row_nu[0];
        }
        // Not moving users, just use original username
        else
        {
            $new_username = $orig_username;
        }
        
        // Get new IP
        $result_nip = @mysql_query("SELECT ip FROM network WHERE id = '$new_netid' LIMIT 1");
        $row_nip    = mysql_fetch_row($result_nip);
        $new_ip     = $row_nip[0];
        
        // Check required
        if(empty($orig_username) || empty($orig_ip) || empty($orig_port) || empty($new_username) || empty($new_ip) || empty($new_port)) return 'Sorry, did not receive all required options!';
        
        $ssh_cmd      = "MoveServerLocal -u $orig_username -i $orig_ip -p $orig_port -U $new_username -I $new_ip -P $new_port";
        
        require('network.php');
        $Network  = new Network;
        $net_info = $Network->netinfo($orig_netid);
        
        $ssh_response = $Network->runcmd($orig_netid,$net_info,$ssh_cmd,true,$srvid);
        
        // Should return 'success'
        return $ssh_response;
    }
    
    
    
    
    
    
    
    
    
    
    // Get recent server log output
    public function getoutput($srvid)
    {
        if(empty($srvid)) return 'Error: Restart class: No server ID given!';
        
        $srv_info  = $this->getinfo($srvid);
        $srv_username     = $srv_info[0]['username'];
        $srv_ip           = $srv_info[0]['ip'];
        $srv_port         = $srv_info[0]['port'];
        $srv_netid        = $srv_info[0]['parentid'];
        $srv_netid        = $srv_info[0]['parentid'];
        $srv_working_dir  = $srv_info[0]['working_dir'];
        if($srv_working_dir) $srv_working_dir = ' -w ' . $srv_working_dir;
        
        require('network.php');
        $Network   = new Network;
        $net_info  = $Network->netinfo($srv_netid);
        $ssh_cmd   = "ServerOutput -u $srv_username -i $srv_ip -p $srv_port $srv_working_dir";
	$net_local = $net_info['is_local'];

	################################################

	// Local Servers can read the screen output directly
	if($net_local)
	{
		$log_loc  = $Network->runcmd($srv_netid,$net_info,$ssh_cmd,true,$srvid);$log_loc  = $Network->runcmd($srv_netid,$net_info,$ssh_cmd,true,$srvid);

		// Function to tail the log
		function tail_logfile($file, $lines) {
		    //global $fsize;
		    $handle = fopen($file, "r");
		    $linecounter = $lines;
		    $pos = -2;
		    $beginning = false;
		    $text = array();
		    while ($linecounter > 0) {
			$t = " ";
			while ($t != "\n") {
			    if(fseek($handle, $pos, SEEK_END) == -1) {
				$beginning = true; 
				break; 
			    }
			    $t = fgetc($handle);
			    $pos --;
			}
			$linecounter --;
			if ($beginning) {
			    rewind($handle);
			}
			$text[$lines-$linecounter-1] = fgets($handle);
			if ($beginning) break;
		    }
		    fclose ($handle);
		    return array_reverse($text);
		}

		// Show last 40 lines
		$lines = tail_logfile($log_loc, 40);
		foreach ($lines as $line) {
		    # Ignore empty whitespace
		    if(!preg_match("/^\n+$/", $line)) echo $line;
		}
	}
	// Remote Servers can simply show the output
	else
	{
		return $Network->runcmd($srv_netid,$net_info,$ssh_cmd,true,$srvid);
	}
    }
    
    
    
    
    // Send a command via GNU Screen to a server
    public function send_screen_cmd($srvid,$cmd)
    {
        if(empty($srvid)) return 'Error: Restart class: No server ID given!';
        elseif(empty($cmd)) return 'Error: Restart class: No command given!';
        
        if(preg_match('/\./', $cmd)) return 'Invalid command.';
        elseif(preg_match('/[;&/|]+/', $cmd)) return 'Invalid command.';
        $cmd = escapeshellarg($cmd);
        
        $srv_info  = $this->getinfo($srvid);
        $srv_username     = $srv_info[0]['username'];
        $srv_ip           = $srv_info[0]['ip'];
        $srv_port         = $srv_info[0]['port'];
        $srv_netid        = $srv_info[0]['parentid'];
        $srv_working_dir  = $srv_info[0]['working_dir'];
        if($srv_working_dir) $srv_working_dir = ' -w ' . $srv_working_dir;
        
        require('network.php');
        $Network  = new Network;
        $net_info = $Network->netinfo($srv_netid);
        $ssh_cmd  = "ServerSendCMD -u $srv_username -i $srv_ip -p $srv_port $srv_working_dir -c $cmd";
        
        // Return server log
        return $Network->runcmd($srv_netid,$net_info,$ssh_cmd,true,$srvid);
    }
    
    
    
    
    // Determine an available IP/Port combo for new servers
    public function get_avail_ip_port($intname)
    {
        if(empty($intname)) return 'No game name provided!';
        
        // Get default port for this server type
        $result_def   = @mysql_query("SELECT port FROM default_games WHERE intname = '$intname' ORDER BY intname DESC LIMIT 1");
        $row_def      = mysql_fetch_row($result_def);
        $default_port = $row_def[0];
        
        // Get network server with lowest load
        $result_low = @mysql_query("SELECT netid FROM loadavg GROUP BY netid ORDER BY load_avg ASC LIMIT 1");
        $row_low    = mysql_fetch_row($result_low);
        $this_netid = $row_low[0];
        
        if(empty($this_netid))
        {
            // Check if we're local (if local, no remote would call home anyway)
            $result_loc = @mysql_query("SELECT id,is_local FROM network WHERE parentid = '0'");
            $row_loc    = mysql_fetch_row($result_loc);
            $this_netid = $row_loc[0];
            $net_local  = $row_loc[1];
            
            // Exit if we're not local - it's remote and the manager hasn't called home yet
            if(!$net_local) return 'Not enough network server info to process this request.  Try again in 5 minutes.';
        }
        
        // Try and use up all IP's with default ports first
        $result_low = @mysql_query("SELECT 
                                      n.id,
                                      n.is_local
                                      s.port 
                                    FROM network AS n 
                                    LEFT JOIN servers AS s ON 
                                      n.id = s.netid 
                                    WHERE 
                                      (n.id = '$this_netid' OR n.parentid = '$this_netid')");
        
        $ret_arr  = array();
        while($row_ips  = mysql_fetch_array($result_low))
        {
            $this_netid = $row_ips['id'];
            $this_port  = $row_ips['port'];
            
            // No default port with this IP, use this
            if(empty($this_port))
            {
                $ret_arr['available'] = 'yes';
                $ret_arr['netid']     = $this_netid;
                $ret_arr['port']      = $this_port;
                break;
            }
        }
        
        if(empty($ret_arr)) $ret_arr['available'] = '0';
        
        return $ret_arr;
    }
    
}
