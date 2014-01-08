<?php
// Class for File Management of game servers
class Files
{
    // Get a file list for a game server
    public function file_list($srvid,$dir,$tpl_browse=false)
    {
        if(empty($srvid)) return 'No server ID given';
        
        // Get network ID (if browsing for template, use given ID as it's already a network ID
        if($tpl_browse)
        {
            $this_netid = $srvid;
        }
        // Otherwise we were given a gameserver ID, get it's network id
        else
        {
            $result_nid = @mysql_query("SELECT netid FROM servers WHERE id = '$srvid' LIMIT 1");
            $row_nid    = mysql_fetch_row($result_nid);
            $this_netid = $row_nid[0];
        }
        
        if(empty($this_netid)) return 'Failed to get network ID!';
        
        require(DOCROOT.'/includes/classes/network.php');
        $Network  = new Network;
        $netinfo  = $Network->netinfo($this_netid);
        
        $net_local      = $netinfo['is_local'];
        #$net_game_ip    = $netinfo['game_ip'];
        $ssh_ip         = $netinfo['ssh_ip'];
        $ssh_port       = $netinfo['ssh_port'];
        $ssh_user       = $netinfo['ssh_user'];
        $ssh_pass       = $netinfo['ssh_pass'];
        $ssh_homedir    = $netinfo['ssh_homedir'];
        $net_local      = $netinfo['is_local'];
        
        // Get real server info
        if(!class_exists('Servers')) require(DOCROOT.'/includes/classes/servers.php');
        $Servers  = new Servers;
        $srvinfo  = $Servers->getinfo($srvid);
        
        $net_game_ip    = $srvinfo[0]['ip'];
        $net_gameuser   = $srvinfo[0]['username'];
        $net_game_port  = $srvinfo[0]['port'];
        
        // Cant read any files with . in them (includes .filename and ../)
        if(preg_match('/^\./', $dir))
        {
            $dir  = stripslashes($dir);
            $dir  = strip_tags($dir);
            
            return 'Invalid directory provided ('.$dir.')!';
        }
        
        ################################################################
        
        // Local Listing
        if($net_local)
        {
            $local_dir  = DOCROOT . '/_SERVERS/';
            
            // Browsing for templates.  Start at homedir
            if($tpl_browse) $game_dir = $local_dir;
            
            // Use Gameserver Directory
            else $game_dir = $local_dir.'/accounts/'.$net_gameuser.'/'.$net_game_ip.':'.$net_game_port;
            
            // Append new directory to basedir
            if($dir) $game_dir .= '/' . $dir;
            
            ##########################################################################################
            
            // File Contents
            if(preg_match('/\.(txt|cfg|rc|log|ini|inf|vdf|yml|properties|json|conf)$/i', $dir))
            {
                if(!file_exists($game_dir)) return 'That file doesnt exist or the webserver cannot view it.';
                
                // Read file
                $fh = fopen($game_dir, "rb");
                $file_content = fread($fh, 4096);
                fclose($fh);
                
                // Return remote file content
                return $file_content;
            }
            
            ##########################################################################################
            
            // Continue with directory listing
            if(GPXDEBUG) $add_path  = '('.$game_dir.') ';
            else $add_path = '';
            
            if(!is_dir($game_dir)) die('Sorry, that game directory '.$add_path.'does not exist!');
            $dir        = dir($game_dir);
            $dir_arr    = array();
            $count_dir  = 0;
            
            // Permission errors etc
            if(!opendir($game_dir)) return 'err_opendir';
            
            // Loop over files, no . or .. dirs
            while(($file = $dir->read()) !== false)
            {
                if(!preg_match('/^\.+/', $file))
                {
                    $full_path  = $game_dir.'/'.$file;
                    
                    if(is_dir($full_path)) $thetype = '2';
                    else $thetype = '1';
                    
                    #$dir_arr[$count_dir]['name']      = $file;
                    #$dir_arr[$count_dir]['size']      = filesize($full_path);
                    #$dir_arr[$count_dir]['mtime']     = filemtime($full_path);
                    #$dir_arr[$count_dir]['atime']     = fileatime($full_path);
                    #$dir_arr[$count_dir]['perms']     = fileperms($full_path);
                    #$dir_arr[$count_dir]['type']      = $thetype;
                    
                    $dir_arr[$file]['size']         = filesize($full_path);
                    $dir_arr[$file]['mtime']        = filemtime($full_path);
                    $dir_arr[$file]['atime']        = fileatime($full_path);
                    $dir_arr[$file]['uid']          = fileowner($full_path);
                    $dir_arr[$file]['gid']          = filegroup($full_path);
                    $dir_arr[$file]['permissions']  = fileperms($full_path);
                    $dir_arr[$file]['type']         = $thetype;
                }
                
                $count_dir++;
            }
            
            $dir->close();
            
            return $dir_arr;
        }
        // Remote Listing
        else
        {
            // Not browsing for templates
            if(!$tpl_browse)
            {
                // Use real SSO user's login, not gpx login
                $sso_info = $Network->sso_info($srvid);
                $ssh_user = $sso_info['sso_user'];
                $ssh_pass = $sso_info['sso_pass'];
                $sso_username = $sso_info['username'];
                $sso_gamedir  = $sso_info['game_path'];
                
                // Set game dir
                $game_dir = $sso_gamedir;
                
		if(GPXDEBUG) echo "DEBUG: Gamedir: $sso_gamedir, SSO User: $ssh_user, Username: $sso_username<br>";

                // File Contents
                if(preg_match('/\.(txt|cfg|rc|log|ini|inf|vdf|yml|properties|json|conf)$/i', $dir))
                {
                    if($tpl_browse) return 'Sorry, you cannot edit files while browsing for templates!';
                    
                    $net_cmd      = 'FileContent -f ' . $game_dir . '/' . $dir;
                    $file_content = $Network->runcmd($this_netid,$netinfo,$net_cmd,true,$srvid);
                    
                    // Return remote file content
                    return $file_content;
                }
            }
            // Browsing for templates
            else
            {
                // Use normal SSH homedir
                $game_dir = $ssh_homedir;
            }
            
            ##########################################################################################
            
            // PHPSecLib (Pure-PHP SSH Implementation)
            require(DOCROOT.'/includes/SSH/Net/SFTP.php');
            
            // Setup Connection
            $sftp = new Net_SFTP($ssh_ip,$ssh_port,12);
            
            // Test login
            if(!$sftp->login($ssh_user, $ssh_pass)) return 'ERROR: Failed to login to the remote server';
            
            // Append new directory to basedir
            if($dir) $game_dir .= '/' . $dir;
            
            // Get raw file list
            $file_list  = $sftp->rawlist($game_dir);
            
            return $file_list;
        }
    }
    
    
    
    
    
    
    
    
    // Delete a file from a gameserver
    public function delete_file($srvid,$name)
    {
        if(empty($srvid) || empty($name)) return 'ERROR: No server ID or filename given!';
        
        // Check invalid path or name
        if(preg_match('/(^\.+)/', $name)) return 'ERROR: Invalid filename given';
        
        // Get network ID
        $result_nid = @mysql_query("SELECT netid FROM servers WHERE id = '$srvid'");
        $row_nid    = mysql_fetch_row($result_nid);
        $this_netid = $row_nid[0];
        
        if(empty($this_netid)) return 'Failed to get network ID!';
        
        require(DOCROOT.'/includes/classes/network.php');
        $Network  = new Network;
        $netinfo = $Network->netinfo($this_netid);
        
        #$net_game_ip    = $netinfo['game_ip'];
        #$net_game_port  = $netinfo['game_port'];
        #$net_gameuser   = $netinfo['username'];
        $net_local      = $netinfo['is_local'];
        
        // Get real server info
        require(DOCROOT.'/includes/classes/servers.php');
        $Servers  = new Servers;
        $srvinfo  = $Servers->getinfo($srvid);
        
        $net_game_ip    = $srvinfo[0]['ip'];
        $net_gameuser   = $srvinfo[0]['username'];
        $net_game_port  = $srvinfo[0]['port'];
        
        // Get userdir
        if($net_local)
        {
            $home_dir   = DOCROOT . '/_SERVERS/';
            $file_path  = "$home_dir/accounts/$net_gameuser/$net_game_ip:$net_game_port";
            
            if(!empty($_SESSION['curdir'])) $file_path .= '/' . $_SESSION['curdir'] . '/' . $name;
            else $file_path .= '/' . $name;
            
            // Try unlink
            if(!unlink($file_path)) return 'Unable to delete file ('.$file_path.')!';
            else return 'success';
        }
        else
        {
            # $home_dir = $netinfo['ssh_homedir'];
            
            // Get SSO info
            $sso_info = $Network->sso_info($srvid);
            $sso_gamedir  = $sso_info['game_path'];
                
            // Use full path
            $file_path  = $sso_info['game_path']; #"$home_dir/accounts/$net_gameuser/$net_game_ip\:$net_game_port";
            if(!empty($_SESSION['curdir'])) $file_path .= '/' . $_SESSION['curdir'] . '/' . $name;
            else $file_path .= '/' . $name;
            
            ####################################################################

            // Delete File
            $run_cmd = 'FileDelete -f '.$file_path;
            
            // Run the command, return output
            $cmd_out  =  $Network->runcmd($this_netid,$netinfo,$run_cmd,true,$srvid);
            
            return $cmd_out;
        }
    }
    
    
    
    
    
    // Delete a directory from a gameserver
    public function delete_dir($srvid,$name)
    {
        if(empty($srvid) || empty($name)) return 'ERROR: No server ID or directory given!';
        
        // Check invalid path or name
        if(preg_match('/(^\.+)/', $name)) return 'ERROR: Invalid directory given';
        
        // Get network ID
        $result_nid = @mysql_query("SELECT netid FROM servers WHERE id = '$srvid'");
        $row_nid    = mysql_fetch_row($result_nid);
        $this_netid = $row_nid[0];
        
        if(empty($this_netid)) return 'Failed to get network ID!';
        
        require(DOCROOT.'/includes/classes/network.php');
        $Network  = new Network;
        $netinfo = $Network->netinfo($this_netid);
        
        #$net_game_ip    = $netinfo['game_ip'];
        #$net_game_port  = $netinfo['game_port'];
        #$net_gameuser   = $netinfo['username'];
        $net_local      = $netinfo['is_local'];
        
        // Get real server info
        require(DOCROOT.'/includes/classes/servers.php');
        $Servers  = new Servers;
        $srvinfo  = $Servers->getinfo($srvid);
        
        $net_game_ip    = $srvinfo[0]['ip'];
        $net_gameuser   = $srvinfo[0]['username'];
        $net_game_port  = $srvinfo[0]['port'];
        
        // Get userdir
        if($net_local)
        {
            $home_dir   = DOCROOT . '/_SERVERS/';
            $file_path  = "$home_dir/accounts/$net_gameuser/$net_game_ip:$net_game_port";
            
            #if(!empty($_SESSION['curdir'])) $file_path .= '/' . $_SESSION['curdir'] . '/' . $name;
            #else $file_path .= '/' . $name;
            $file_path  .= '/' . $name;
            
            // rmdir
            if(!rmdir($file_path)) return 'Unable to delete directory ('.$file_path.')!';
            else return 'success';
        }
        else
        {
            #$home_dir = $netinfo['ssh_homedir'];
            
            // Use full path
            #$file_path  = "$home_dir/accounts/$net_gameuser/$net_game_ip\:$net_game_port";
            
            // Get SSO info
            $sso_info = $Network->sso_info($srvid);
            $file_path  = $sso_info['game_path'];
            
            #if(!empty($_SESSION['curdir'])) $file_path .= '/' . $_SESSION['curdir'] . '/' . $name;
            #else $file_path .= '/' . $name;
            $file_path  .= '/' . $name;
            
            ####################################################################

            // Delete Directory
            $run_cmd = 'DeleteDirectory -f '.$file_path;
            
            // Run the command, return output
            $cmd_out  = $Network->runcmd($this_netid,$netinfo,$run_cmd,true,$srvid);
            
            return $cmd_out;
        }
    }
    
    
    
    
    // Save file content
    public function save_file($srvid,$file,$content)
    {
        if(empty($file)) return 'No filename given!';
        
        // Get network ID
        $result_nid = @mysql_query("SELECT netid FROM servers WHERE id = '$srvid'");
        $row_nid    = mysql_fetch_row($result_nid);
        $this_netid = $row_nid[0];
        
        if(empty($this_netid)) return 'Failed to get network ID!';
        
        require(DOCROOT.'/includes/classes/network.php');
        $Network  = new Network;
        $netinfo = $Network->netinfo($this_netid);
        
        $net_game_ip    = $netinfo['game_ip'];
        #$net_game_port  = $netinfo['game_port'];
        #$net_gameuser   = $netinfo['username'];
        $net_local      = $netinfo['is_local'];
        
        // Get real server info
        require(DOCROOT.'/includes/classes/servers.php');
        $Servers  = new Servers;
        $srvinfo  = $Servers->getinfo($srvid);
        
        #$net_game_ip    = $srvinfo[0]['ip'];
        $net_gameuser   = $srvinfo[0]['username'];
        $net_game_port  = $srvinfo[0]['port'];
        
        // Get userdir
        if($net_local)
        {
            $localdir   = DOCROOT . '/_SERVERS/';
            $game_dir = $localdir . '/accounts/'.$net_gameuser.'/'.$net_game_ip.':'.$net_game_port . '/' . $file;
            
            // Stupid newlines, this took forever to figure out '\\\n'
            $content  = preg_replace('/\\\n/', "\n", $content);
            $content  = stripslashes($content);
            
            // Replace carets for now as BASH hates them when saving a file
            $content  = str_replace('`', '?', $content);
            
            // Write to file
            $fh = fopen($game_dir, "w") or die('Failed to open file for writing!');
            fwrite($fh, $content);
            fclose($fh);
            
            return 'success';
        }
        else
        {
            // Get SSO info
            #$file_path  = $netinfo['ssh_homedir'] . "/accounts/$net_gameuser/$net_game_ip\:$net_game_port/$file";
            $sso_info   = $Network->sso_info($srvid);
            $sso_user   = $sso_info['username'];
            $file_path  = $sso_info['game_path'].'/'.$file;
            
            // Save File
            $run_cmd = 'FileSave -f '.$file_path.' -c "'.$content.'"';
            
            // Run the command, return output
            return $Network->runcmd($this_netid,$netinfo,$run_cmd,true,$srvid);
        }
    }
    
    
    // Save NEW file
    public function save_newfile($srvid,$file,$content)
    {
        if(empty($file)) return 'No filename given!';
        
        // Get network ID
        $result_nid = @mysql_query("SELECT netid FROM servers WHERE id = '$srvid'");
        $row_nid    = mysql_fetch_row($result_nid);
        $this_netid = $row_nid[0];
        
        if(empty($this_netid)) return 'Failed to get network ID!';
        
        require(DOCROOT.'/includes/classes/network.php');
        $Network  = new Network;
        $netinfo = $Network->netinfo($this_netid);
        
        #$net_game_ip    = $netinfo['game_ip'];
        #$net_game_port  = $netinfo['game_port'];
        #$net_gameuser   = $netinfo['username'];
        $net_local      = $netinfo['is_local'];
        
        // Get real server info
        require(DOCROOT.'/includes/classes/servers.php');
        $Servers  = new Servers;
        $srvinfo  = $Servers->getinfo($srvid);
        
        $net_game_ip    = $srvinfo[0]['ip'];
        $net_gameuser   = $srvinfo[0]['username'];
        $net_game_port  = $srvinfo[0]['port'];
        
        // Add full path to file
        if(isset($_SESSION['curdir'])) $file = $_SESSION['curdir'] . '/' . $file;
        
        
        // Get userdir
        if($net_local)
        {
            $localdir   = DOCROOT . '/_SERVERS/';
            $game_dir   = $localdir . '/accounts/'.$net_gameuser.'/'.$net_game_ip.':'.$net_game_port . '/' . $file;
            
            // Stupid newlines, this took forever to figure out '\\\n/' - either jquery caused this or the textarea did, no idea
            $content  = preg_replace('/\\\n/', "\n", $content);
            $content  = stripslashes($content);
            
            // Write to file
            $fh = fopen($game_dir, "w") or die('Failed to open file for writing!');
            fwrite($fh, $content);
            fclose($fh);
            
            return 'success';
        }
        else
        {
            #$file_path  = $netinfo['ssh_homedir'] . "/accounts/$net_gameuser/$net_game_ip\:$net_game_port/$file";

            // Get SSO info
            $sso_info = $Network->sso_info($srvid);
            $file_path  = $sso_info['game_path'] . '/' . $file;
            
            // Save File
            $run_cmd = 'FileSave -f '.$file_path.' -c "'.$content.'"';
            
            // Run the command, return output
            return $Network->runcmd($this_netid,$netinfo,$run_cmd,true,$srvid);
        }
    }
    
    
    
    
    // Create directory
    public function create_newdir($srvid,$dir_name)
    {
        if(empty($dir_name)) return 'No directory name given!';
        
        // Get network ID
        $result_nid = @mysql_query("SELECT netid FROM servers WHERE id = '$srvid'");
        $row_nid    = mysql_fetch_row($result_nid);
        $this_netid = $row_nid[0];
        
        if(empty($this_netid)) return 'Failed to get network ID!';
        
        require(DOCROOT.'/includes/classes/network.php');
        $Network  = new Network;
        $netinfo = $Network->netinfo($this_netid);
        
        // Get real server info
        require(DOCROOT.'/includes/classes/servers.php');
        $Servers  = new Servers;
        $srvinfo  = $Servers->getinfo($srvid);
        
        $net_game_ip    = $netinfo['game_ip'];
        $net_local      = $netinfo['is_local'];
        $net_gameuser   = $srvinfo[0]['username'];
        $net_game_port  = $srvinfo[0]['port'];
        
        // Add full path to dir
        if(isset($_SESSION['curdir'])) $dir_name  = $_SESSION['curdir'] . '/' . $dir_name;
        
        
        // Get userdir
        if($net_local)
        {
            $localdir   = DOCROOT . '/_SERVERS/';
            $game_dir   = $localdir . '/accounts/'.$net_gameuser.'/'.$net_game_ip.':'.$net_game_port . '/' . $dir_name;
            
            // Check existing
            if(file_exists($game_dir)) die('Sorry, that directory already exists!');
            
            // Create directory
            if(!mkdir($game_dir)) return 'Failed to create the directory ('.$game_dir.')!';
            else return 'success';
        }
        else
        {
            // Save File
            $run_cmd = "CreateDirectory -u $net_gameuser -i $net_game_ip -p $net_game_port -d \"$dir_name\"";
            
            // Run the command, return output
            return $Network->runcmd($this_netid,$netinfo,$run_cmd,true,$srvid);
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // Display a file list
    public function displaydir($file_list,$srvid,$filename=false,$tpl_browse=false)
    {
	if(GPXDEBUG) echo "DEBUG: Server ID: $srvid, Filename: $filename, Browsing Templates: $tpl_browse<br>";

        // Use correct img path
        if(isset($_SESSION['gpx_admin'])) $bk_path  = '../';
        else $bk_path = '';
        
        // Setup language
        require(DOCROOT.'/lang.php');
        
        #$back_link  = '<span class="links" onClick="javascript:load_dir('.$srvid.',\'\',1);" title="Go Back">&lt; Go Back</span><br /><br />';
        $back_link  = '<div class="links" style="margin-top:10px;margin-bottom:5px;" onClick="javascript:load_dir('.$srvid.',\'\',1,'.$tpl_browse.');"><img src="'.$bk_path.'images/icons/small/back.png" width="28" height="28" border="0" style="margin-top:5px;" /> '.$lang['go_back'].'</div>';
        
        // Unable to read due to permissions etc
        if($file_list == 'err_opendir') die($back_link . '<br />Sorry, unable to read this directory.');
        
        ####################################################
        
        // Not a directory; show file contents
        if(!is_array($file_list))
        {
            if($tpl_browse) return 'Sorry, cannot edit files in template browse mode.';
            
            # require(DOCROOT.'/lang.php');
            
            echo '<div class="infobox" style="display:none;"></div> ' . $back_link . '<textarea id="filecontent_cur" class="txteditor" style="white-space:pre;">'.$file_list.'</textarea><br />
            <div class="button" onClick="javascript:file_savecontent('.$srvid.',\''.$filename.'\');">'.$lang['save'].'</div>';
            
            exit;
        }
        
        
        ####################################################
        
	// Setup file/dir sorting
	$arr_files = array();
	$arr_dirs  = array();

	#echo '<pre>';
	#var_dump($file_list);
	#echo '</pre>';
	#exit;

	foreach($file_list as $filename => $file_arr)
	{
		$file_type = $file_arr['type'];
		#$filename  = strval($filename);
		if(is_numeric($filename)) $filename .= ' ';
		#echo "Name: $filename<br>";

		if($file_type == '1') $arr_files[$filename][] = $file_arr;
		else $arr_dirs[$filename][] = $file_arr;
	}

	// Sort arrays by filename
	ksort($arr_files);
	ksort($arr_dirs);

	# Combine, dirs first
        $file_list = array_merge($arr_dirs, $arr_files);

	// OLD/unorganized - Make new array to sort directories together
        #$array = array($file_list,array_keys($file_list));
        #array_multisort($array[0], SORT_DESC,  $array[1], SORT_DESC);
        #$file_list = array_combine($array[1], $array[0]);
        #unset($array);

        ####################################################
        
        // Check if server is local
        $Network  = new Network;
        $is_local = $Network->islocal($srvid);
        
        // Setup language
        #require(DOCROOT.'/lang.php');
        
        ####################################################
        
        // File Uploads - Allow if local
        if($is_local)
        {
            echo '<div align="center">
                      <div id="file_up"></div>
                  </div>
                  
                  <script type="text/javascript">
                  $(document).ready(function(){
                      createUploader();
                  });
                  </script>';
        }
        
        ####################################################
        
        // Allow back button
        if(!empty($_SESSION['curdir']))
        {
            // If tpl browsing, add "use this directory" option
            if($tpl_browse)
            {
                $cur_dir  = $_SESSION['curdir'];
                echo '<div align="center"><span class="links" onClick="javascript:template_browse_select(\''.$cur_dir.'\');">Click to use this folder for this template</span></div>';
            }
            
            
            #$backdir  = $_SESSION['curdir']; //dirname($_SESSION['curdir']);
            echo $back_link;
        }
        
        // Show current directory
        if($_SESSION['curdir']) echo '<div style="width:100%;height:20px;line-height:20px;font-family:Arial;font-size:11pt;color:#777;"><b>'.$lang['working_dir'].':</b> '.htmlspecialchars(stripslashes($_SESSION['curdir'])).'</div>';
        
        
        echo '<div class="infobox" style="display:none;"></div>';
        
        echo '<div class="box">
        <div class="box_title" id="box_servers_title">' . $lang['files'] . '</div>
        <div class="box_content" id="box_servers_content">

        <table border="0" cellpadding="0" cellspacing="0" align="center" width="900" class="box_table" style="text-align:center;" id="files_table">
          <tr>
            <td width="50">&nbsp;</td>
            <td width="350" align="left"><b>'.$lang['name'].'</b></td>
            <td width="120"><b>'.$lang['modified'].'</b></td>
            <td width="120"><b>'.$lang['accessed'].'</b></td>
            <td width="120"><b>'.$lang['size'].'</b></td>
            <td width="60"><b>'.$lang['delete'].'</b></td>
          </tr>';
          
          // Loop through files
          $file_cnt = 0;
          foreach($file_list as $file => $key)
          {
              if(!preg_match('/^\.+/', $file))
              {
                  $file_mtime   = date('M jS',$key[0]['mtime']);
                  $file_atime   = date('M jS',$key[0]['atime']);
                  $file_size    = $key[0]['size'];
                  $file_perms   = $key[0]['permissions'];
                  $file_type    = $key[0]['type'];
                  $file_owner   = $key[0]['uid'];

		  // Trim off space on numeric array names
		  $file = trim($file);

                  if($tpl_browse) $add_tplb = ',\'1\'';
                  else $add_tplb = '';
                  
                  $editable_link  = '<span class="links" onClick="javascript:load_dir('.$srvid.',\''.$file.'\',0'.$add_tplb.');">' . $file . '</span>';
                  $editable_img   = ' style="cursor:pointer;" onClick="javascript:load_dir('.$srvid.',\''.$file.'\',0'.$add_tplb.');"';
                  
                  // Files
                  if($file_type == 1 || $file_type == '1')
                  {
                      $icon = 'file.png';
                      
                      // Editable File Types
                      if(preg_match('/\.(txt|cfg|rc|log|ini|inf|vdf|yml|properties|json|conf)$/i', $file) && !$tpl_browse)
                      {
                          $edit_link  = $editable_link;
                          $img_link   = $editable_img;
                      }
                      else
                      {
                          $edit_link  = '<span class="links" style="font-weight:normal;cursor:default;text-decoration:none;">'.$file.'</span>';
                          $img_link   = '';
                      }
                      
                      // Allow deleting files
                      if(!$tpl_browse) $delete_add = '<img src="'.$bk_path.'images/icons/medium/error.png" width="25" height="25" border="0" title="Delete" style="cursor:pointer;" onClick="javascript:confirm_delete_file('.$srvid.',\''.$file.'\','.$file_cnt.');" />';
                      
                      // Can't delete in tpl browser
                      else $delete_add = '&nbsp;';
                  }
                  // Directories
                  else
                  {
                      $icon = 'folder.png';
                      $edit_link  = $editable_link;
                      $img_link   = $editable_img;
                      
                      // Can't delete entire directories for safety
                      $delete_add = '&nbsp;';
                  }
                  
                  # <td width="50" align="left" style="cursor:default;"><img src="images/icons/medium/error.png" width="25" height="25" border="0" title="Delete" style="cursor:pointer;" onClick="javascript:server_confirm_del_startup('.$s_id.','.$url_id.');" /></td>
                  
                  echo '<tr id="file_' . $file_cnt . '" style="cursor:default;" class="filerows">
                          <td><img src="'.$bk_path.'images/icons/medium/'.$icon.'" border="0" width="28" height="28" '.$img_link.'/></td>
                          <td align="left">'.$edit_link.'</td>
                          <td>' . $file_mtime . '</td>
                          <td>' . $file_atime . '</td>
                          <td>' . $file_size . '</td>
                          <td style="cursor:default;">'.$delete_add.'</td>
                        </tr>';
                  
                  $file_cnt++;
              }
          }
          
          echo '</table>
          </div>
          </div>';
          
          // Only admins can add files or directories
          if(isset($_SESSION['gpx_admin']) && !$tpl_browse)
          {
              echo '<div style="width:100%;margin-bottom:30px;">
                        <span onClick="javascript:file_show_addfile('.$srvid.');" class="links"><img src="'.$bk_path.'/images/icons/medium/add.png" border="0" width="28" height="28" /> Add File</span><br />
                        <span onClick="javascript:file_show_add_dir('.$srvid.');" class="links"><img src="'.$bk_path.'/images/icons/medium/add.png" border="0" width="28" height="28" /> Add Directory</span><br />';
                        
               // Allow directory deletion if empty
              if(count($file_list) == 0) echo '<span class="links" onClick="javascript:confirm_delete_dir('.$srvid.',\''.$filename.'\');"><img src="'.$bk_path.'images/icons/medium/error.png" width="25" height="25" border="0" title="Delete" style="cursor:pointer;" /> Remove this directory</span><br />';
          
              echo '</div>';
          }
    }
}
