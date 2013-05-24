<?php
// Templates Class
class Templates
{
    // Create a new template
    public function create($netid,$gameid,$file_path,$description,$is_def)
    {
        if(empty($netid)) return 'Templates: No network ID provided';
        elseif(empty($gameid)) return 'Templates: No game ID provided';
        
        // Generate random token for remote server callback
        $Core = new Core;
        $remote_token = $Core->genstring('16');
        
        if(empty($file_path))
        {
            // Check if Steam if no file path given
            $result_stm = @mysql_query("SELECT steam,steam_name,install_mirrors,install_cmd FROM default_games WHERE id = '$gameid' LIMIT 1");
            $row_stm    = mysql_fetch_row($result_stm);
            $is_steam         = $row_stm[0];
            $steam_name       = $row_stm[1];
            $install_mirrors  = $row_stm[2];
            $install_cmd      = $row_stm[3];
        }
        
        // Use correct status
        if($is_steam) $tpl_status = 'steam_running';
        else $tpl_status = 'running';
        
	################################################################
        
        // Mark old defaults as non-default now
        if($is_def) @mysql_query("UPDATE templates SET is_default = '0' WHERE netid = '$netid' AND cfgid = '$gameid'");
        
        // Insert
        @mysql_query("INSERT INTO templates (netid,cfgid,date_created,is_default,status,token,description,file_path) VALUES('$netid','$gameid',NOW(),'$is_def','$tpl_status','$remote_token','$description','$file_path')") or die('Failed to insert template');
        $tpl_id = mysql_insert_id();
        if(empty($tpl_id)) return 'No template ID created!  An unknown error occured.';
        
        ################################################################
        
        // Create on remote server
        require(DOCROOT.'/includes/classes/network.php');
        $Network  = new Network;
        $net_arr  = $Network->netinfo($netid);
        
        // Get callback page
        $this_url   = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
        $this_page  = str_replace('ajax/ajax.php', '', $this_url);
        $this_page  = str_replace('api/api.php', '', $this_page);
        $this_page  .= '/includes/callback.php?token='.$remote_token.'&id='.$tpl_id;
        $this_page  = preg_replace('/\/+/', '/', $this_page); // Remove extra slashes
        $this_page  = 'http://' . $this_page;
        
        ################################################################
        
        // Auto Install (Should start in screen labeled "gpxauto_24" if 24 is tplid)
        if(!empty($install_mirrors) && !empty($install_cmd))
        {
            // Convert newlines to real \n
            $install_cmd  = preg_replace('/(\n|\r|;|&&)+/',' \n',$install_cmd);
            
            $net_cmd  = 'AutoInstall -m "'.$install_mirrors.'" -c "'.$install_cmd.'" -i '.$tpl_id.' -C "'.$this_page.'"';
            if(GPXDEBUG) $net_cmd .= ' -d yes';
        }
        
        // Normal/Archive Method
        elseif(!empty($file_path))
        {
            $file_path = stripslashes($file_path);
            $net_cmd  = 'CreateTemplate -p "' . $file_path . '" -i ' . $tpl_id . ' -u "' . $this_page . '"';
            if(GPXDEBUG) $net_cmd .= ' -d yes';
        }
        
        // Steam Installer (original HldsUpdateTool method)
        elseif($is_steam == '1')
        {
            $net_cmd  = 'SteamInstall -g "' . $steam_name . '" -i ' . $tpl_id . ' -u "' . $this_page . '" >> /dev/null 2>&1 &';
            if(GPXDEBUG) $net_cmd .= ' -d yes';
        }
        
        // SteamCMD Installer (newer SteamCMD method)
        elseif($is_steam == '2')
        {
            // Get steam authentication info
            $settings = $Core->getsettings();
            $cfg_steam_user   = $settings['steam_login_user'];
            $cfg_steam_pass   = $settings['steam_login_pass'];
            $cfg_steam_auth   = $settings['steam_auth'];
            $cfg_steam_user=substr($cfg_steam_user, 6);$cfg_steam_user=substr($cfg_steam_user, 0, -6);$cfg_steam_user=base64_decode($cfg_steam_user);
            $cfg_steam_pass=substr($cfg_steam_pass, 6);$cfg_steam_pass=substr($cfg_steam_pass, 0, -6);$cfg_steam_pass=base64_decode($cfg_steam_pass);
            
            $net_cmd  = "SteamCMDInstall -g '$steam_name' -i $tpl_id -l '$cfg_steam_user' -p '$cfg_steam_pass' -c '$cfg_steam_auth' -u '$this_page' >> /dev/null 2>&1 &";
            if(GPXDEBUG) $net_cmd .= ' -d yes';
        }
        
        // Failure
        else
        {
            return 'Templates: No File Path provided!';
        }
        
        ###################################################
        
        // Run command
        $cmd_out  = $Network->runcmd($netid,$net_arr,$net_cmd,true);
        
        // !! Comment this out if not outputting Steam to /dev/null
        if($is_steam == '1' || $is_steam == '2') $cmd_out = 'success';
        
        // Steam gives no output; give success
        if($is_steam)
        {
              #return 'success';
              
              // Success
              if($cmd_out == 'success')
              {
                  return 'success';
              }
              else
              {
                  // Delete this template since it didn't start
                  @mysql_query("DELETE FROM templates WHERE id = '$tpl_id'") or die('Failed to delete the template from the database');
                  
                  return '<br /><div style="width:100%;height:80px;margin-top:5px;margin-bottom:5px;"><textarea style="width:100%;height:80px;border-radius:6px;">'.$cmd_out.'</textarea></div>';
              }
        }
        // Directory not found
        elseif(preg_match('/That\ directory\ was\ not\ found/', $cmd_out))
        {
            // Delete this template since it didn't start
            @mysql_query("DELETE FROM templates WHERE id = '$tpl_id'") or die('Failed to delete the template from the database');
            return $cmd_out;
        }
        // OK
        else
        {
            return $cmd_out;
        }
    }
    
    
    
    
    
    
    //
    // Delete a template locally and on filesystem
    //
    public function delete($tplid)
    {
        if(empty($tplid)) return 'Delete: No template ID provided!';
        
        // Get netid
        $result_nid = @mysql_query("SELECT netid FROM templates WHERE id = '$tplid' LIMIT 1");
        $row_nid    = mysql_fetch_row($result_nid);
        $netid      = $row_nid[0];
        
        // Delete from DB
        @mysql_query("DELETE FROM templates WHERE id = '$tplid'") or die('Failed to delete the template row!');
        
        
        // Run network deletion
        require(DOCROOT.'/includes/classes/network.php');
        $Network  = new Network;
        $net_arr  = $Network->netinfo($netid);
        
        $net_cmd  = 'DeleteTemplate -i '.$tplid;
        
        // Run command
        $cmd_out  = $Network->runcmd($netid,$net_arr,$net_cmd,true);
        
        return $cmd_out;
    }
}

?>
