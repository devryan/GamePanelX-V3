<?php
$forceadmin = 1; // Admins only
require('checkallowed.php'); // No direct access
error_reporting(E_ERROR);

// actions
$url_id       = $GPXIN['id'];
$url_do       = $GPXIN['do']; // Action

// Create
if($url_do == 'create')
{
    $url_gameid     = $GPXIN['gameid'];
    $url_file_path  = $GPXIN['file_path'];
    
    require(DOCROOT.'/includes/classes/templates.php');
    $Templates  = new Templates;
    echo $Templates->create($url_netid,$url_gameid,$url_file_path,$url_descr,$url_default);
}

// Save
elseif($url_do == 'save')
{
    $game_port            = $GPXIN['port'];
    $game_name            = $GPXIN['name'];
    $game_intname         = $GPXIN['intname'];
    $game_startup         = $GPXIN['startup'];
    $game_working_dir     = $GPXIN['working_dir'];
    $game_pid_file        = $GPXIN['pid_file'];
    $game_config_file     = $GPXIN['config_file'];
    $game_descr           = $GPXIN['desc'];
    $game_inst_mirrors    = $GPXIN['install_mirrors'];
    $game_installcmd      = $GPXIN['install_cmd'];
    $game_updatecmd       = $GPXIN['update_cmd'];
    $game_simplecmd       = $GPXIN['simplecmd'];
    $game_banned_chars    = $GPXIN['banned_chars']; // Banned characters for startup values
    $game_steam           = $GPXIN['is_steam'];
    $game_steam_name      = $GPXIN['steam_name'];
    $game_query_engine    = $GPXIN['query_engine'];
    $game_map             = $GPXIN['map'];
    $game_maxpl           = $GPXIN['maxplayers'];
    $game_hostname        = $GPXIN['hostname'];
    
    $game_cfg_sep         = $GPXIN['cfg_sep'];
    $game_cfg_ip          = $GPXIN['cfg_ip'];
    $game_cfg_port        = $GPXIN['cfg_port'];
    $game_cfg_maxplayers  = $GPXIN['cfg_maxplayers'];
    $game_cfg_map         = $GPXIN['cfg_map'];
    $game_cfg_hostname    = $GPXIN['cfg_hostname'];
    $game_cfg_rcon        = $GPXIN['cfg_rcon'];
    $game_cfg_password    = $GPXIN['cfg_password'];
    
    // Check internal regex etc
    if(!preg_match('/^[a-zA-Z0-9-_]+$/i', $game_intname)) die($lang['invalid_intname']);
    elseif(!is_numeric($game_port)) die($lang['invalid_port']);
    
    $GLOBALS['mysqli']->query("UPDATE default_games 
                    SET 
                      startup = '$game_startup',port = '$game_port',maxplayers = '$game_maxpl',steam = '$game_steam',steam_name = '$game_steam_name',gameq_name = '$game_query_engine',name = '$game_name',intname = '$game_intname',
                      working_dir = '$game_working_dir',pid_file = '$game_pid_file',config_file = '$game_config_file',description = '$game_descr',
                      install_mirrors = '$game_inst_mirrors',install_cmd = '$game_installcmd',update_cmd = '$game_updatecmd',simplecmd = '$game_simplecmd',banned_chars = '$game_banned_chars',
                      cfg_separator = '$game_cfg_sep',cfg_ip = '$game_cfg_ip',cfg_port = '$game_cfg_port',
                      cfg_maxplayers = '$game_cfg_maxplayers',cfg_map = '$game_cfg_map',cfg_hostname = '$game_cfg_hostname',cfg_rcon = '$game_cfg_rcon',cfg_password = '$game_cfg_password',
                      map = '$game_map',hostname = '$game_hostname' 
                   WHERE 
                      id = '$url_id'") or die('Failed to update game: '.$GLOBALS['mysqli']->error);
    
    echo 'success';
}

// Add new game
elseif($url_do == 'add')
{
    $game_type          = $GPXIN['add_type'];
    $game_port          = strip_tags($GPXIN['add_port']);
    $game_name          = strip_tags($GPXIN['add_name']);
    $game_intname       = strip_tags($GPXIN['add_intname']);
    $game_working_dir   = strip_tags($GPXIN['add_working_dir']);
    $game_pid_file      = strip_tags($GPXIN['add_pid_file']);
    $game_descr         = strip_tags($GPXIN['add_desc']);
    $game_updatecmd     = $GPXIN['add_update_cmd'];
    $game_simplecmd     = $GPXIN['add_simplecmd'];
    $game_steam         = $GPXIN['add_steam_based'];
    $game_steam_name    = $GPXIN['add_steam_name'];
    $game_query_engine  = $GPXIN['add_query_engine'];
    $game_map           = strip_tags($GPXIN['add_def_map']);
    $game_maxpl         = strip_tags($GPXIN['add_def_maxplayers']);
    $game_hostn         = strip_tags($GPXIN['add_def_hostname']);
    $game_config_file   = strip_tags($GPXIN['add_config_file']);
    
    // Check internal regex etc
    if(!preg_match('/^[a-zA-Z0-9-_]+$/i', $game_intname)) die($lang['invalid_intname']);
    elseif(!is_numeric($game_port)) die($lang['invalid_port']);
    elseif(empty($game_maxpl)) die('You must fill out the Max Players field!');
    
    $GLOBALS['mysqli']->query("INSERT INTO default_games (port,maxplayers,steam,type,gameq_name,name,intname,working_dir,pid_file,map,hostname,config_file,steam_name,description,update_cmd,simplecmd) 
                  VALUES('$game_port','$game_maxpl','$game_steam','$game_type','$game_query_engine',
                  '$game_name','$game_intname','$game_working_dir','$game_pid_file','$game_map','$game_hostn',
                  '$game_config_file','$game_steam_name','$game_descr','$game_updatecmd','$game_simplecmd')") or die('Failed to add the game: '.$GLOBALS['mysqli']->error);
    
    echo 'success';
}


// Save Startup Values
elseif($url_do == 'startup_save')
{
    $sort_order   = $GPXIN['sort_list'];
    $startup_type = $GPXIN['start_type'];

    // If simple, update and exit
    if($startup_type == 'smp')
    {
        $GLOBALS['mysqli']->query("UPDATE default_games SET startup = '0' WHERE id = '$url_id'") or die('Failed to update startup type');
        echo 'success';
        exit;
    }
    // If Startup, update and continue
    elseif($startup_type == 'str')
    {
        $GLOBALS['mysqli']->query("UPDATE default_games SET startup = '1' WHERE id = '$url_id'") or die('Failed to update startup type');
    }


    // Begin multi-update queries for all startup items and values
    $update_item_query  = 'UPDATE `default_startup` SET `cmd_item` = CASE `id` ';
    $update_val_query   = 'UPDATE `default_startup` SET `cmd_value` = CASE `id` ';
    $update_usred_query = 'UPDATE `default_startup` SET `usr_edit` = CASE `id` ';
    if($sort_order) $update_sort_query  = 'UPDATE `default_startup` SET `sort_order` = CASE `id` ';

    // Adding items
    $add_query  = 'INSERT INTO default_startup (defid,cmd_item,cmd_value,usr_edit) VALUES';
    
    # Loop through input
    foreach($GPXIN as $item => $val)
    {
        $arr_itms = explode('_', $item);
        $item_id  = $arr_itms[1];
        
        // Item / Value
        if(preg_match('/^stritm_/', $item))     $update_item_query  .= 'WHEN \'' . $item_id . '\' THEN \'' . $val . '\' ';
        elseif(preg_match('/^strval_/', $item)) $update_val_query   .= 'WHEN \'' . $item_id . '\' THEN \'' . $val . '\' ';
        elseif(preg_match('/^usred_/', $item))  $update_usred_query .= 'WHEN \'' . $item_id . '\' THEN \'' . $val . '\' ';
        
        // Adding items
        elseif(preg_match('/^additm_/', $item))   $add_query  .=  "('$url_id','$val',";
        elseif(preg_match('/^addval_/', $item))   $add_query  .= "'$val',";
        elseif(preg_match('/^addusred_/', $item)) $add_query  .= "'$val'),";
    }
    
    // Check if current items
    if($update_item_query != 'UPDATE `default_startup` SET `cmd_item` = CASE `id` ') $hascur = 1;
    else $hascur = 0;
    
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
    
    // Run insert(s)
    if(!preg_match('/VALUES$/', $add_query))
    {
        $add_query  = substr($add_query, 0, -1); // Lose last comma
        $GLOBALS['mysqli']->query($add_query) or die('Failed to add items: '.$GLOBALS['mysqli']->error);
    }
    
    // Run updates
    if($hascur)
    {
        $GLOBALS['mysqli']->query($update_item_query) or die('Failed to update items: '.$GLOBALS['mysqli']->error);
        $GLOBALS['mysqli']->query($update_val_query) or die('Failed to update values: '.$GLOBALS['mysqli']->error);
        $GLOBALS['mysqli']->query($update_usred_query) or die('Failed to update user editable: '.$GLOBALS['mysqli']->error);
        if($sort_order) $GLOBALS['mysqli']->query($update_sort_query) or die('Failed to update order: '.$GLOBALS['mysqli']->error);
    }
    
    // Update simplecmd with most recent order
    $simplecmd  = '';
    $result_smp = $GLOBALS['mysqli']->query("SELECT cmd_item,cmd_value FROM default_startup WHERE defid = '$url_id' ORDER BY sort_order ASC") or die('Failed to get item/vals!');
    
    while($row_smp  = $result_smp->fetch_array())
    {
        $cmd_item = $row_smp['cmd_item'];
        $cmd_val  = $row_smp['cmd_value'];
        
        $simplecmd .= $cmd_item . ' ';
        if($cmd_val || $cmd_val == '0') $simplecmd .= $cmd_val . ' ';
    }
    
    // Update new simplecmd
    $GLOBALS['mysqli']->query("UPDATE default_games SET simplecmd = '$simplecmd' WHERE id = '$url_id'") or die('Failed to update simplecmd!');
    
    echo 'success';
}




// Delete startup item
elseif($url_do == 'startup_del_item')
{
    $server_id  = $GPXIN['serverid'];
    if(empty($url_id) || empty($server_id)) die('No startup ID or server ID specified!');
    
    $GLOBALS['mysqli']->query("DELETE FROM default_startup WHERE id = '$url_id' AND defid = '$server_id'") or die('Failed to delete the startup item');
    
    echo 'success';
}


// Delete
elseif($url_do == 'delete')
{
    // Check for gameservers using this
    $result_chk = $GLOBALS['mysqli']->query("SELECT id FROM servers WHERE defid = '$url_id' LIMIT 1");
    $row_chk    = $result_chk->fetch_row();
    if($row_chk[0]) die('There are servers using this game!  Delete them first and try again.');
    
    $GLOBALS['mysqli']->query("DELETE FROM default_games WHERE id = '$url_id'") or die('Failed to delete the game setup');
    
    echo 'success';
}


// Show create new game setup
elseif($url_do == 'show_creategame')
{
    echo '<table border="0" cellpadding="2" cellspacing="0" width="600" class="cfg_table">
          <tr>
            <td width="200"><b>'.$lang['port'].':</b></td>
            <td><input type="text" id="port" value="" class="inputs" /></td>
          </tr>
          <tr>
            <td><b>'.$lang['name'].':</b></td>
            <td><input type="text" id="name" value="" class="inputs" /></td>
          </tr>
          <tr>
            <td><b>'.$lang['int_name'].':</b></td>
            <td><input type="text" id="intname" value="" class="inputs" /></td>
          </tr>
          <tr>
            <td><b>'.$lang['working_dir'].':</b></td>
            <td><input type="text" id="working_dir" value="" class="inputs" /></td>
          </tr>
          <tr>
            <td><b>'.$lang['pid_file'].':</b></td>
            <td><input type="text" id="pid_file" value="" class="inputs" /></td>
          </tr>
          <tr>
            <td><b>'.$lang['desc'].':</b></td>
            <td><input type="text" id="desc" value="" class="inputs" /></td>
          </tr>
          <tr>
            <td><b>'.$lang['update_cmd'].':</b></td>
            <td><input type="text" id="update_cmd" value="" class="inputs" /></td>
          </tr>
          <tr>
            <td><b>'.$lang['command'].':</b></td>
            <td><input type="text" id="simplecmd" value="" class="inputs" /></td>
          </tr>
          </table>
          
          <div class="button" onClick="javascript:game_create();">'.$lang['save'].'</div>';
}


// Allow submission to GPX Cloud Games for review
elseif($url_do == 'submit_cloudgames')
{
    // Get game info
    $result_info  = $GLOBALS['mysqli']->query("SELECT * FROM default_games WHERE id = '$url_id' ORDER BY id DESC LIMIT 1") or die('Failed to query for game info!');
    $game_arr     = array();
    $total_info   = $result_info->num_rows;
    if(!$total_info) die('No information found for this game!');
    
    while($row_info = $result_info->fetch_assoc())
    {
        $game_arr[] = $row_info;
    }
    
    // Check json support
    if(!function_exists('json_encode')) die('No JSON support found (json_encode)!  Exiting.');
    
    if(empty($game_arr)) die('No information found for this game (empty array)!');
    else $json_game_info = json_encode($game_arr);
    
    ###################################################
    
    // Get game startup items
    $result_strt  = $GLOBALS['mysqli']->query("SELECT * FROM default_startup WHERE defid = '$url_id' ORDER BY sort_order ASC") or die('Failed query for game setup items!');
    $strt_arr     = array();
    $total_info   = $result_strt->num_rows;
    
    // Only run this if this game has startup items
    if($total_info)
    {
        while($row_strt = $result_strt->fetch_assoc())
        {
            $strt_arr[] = $row_strt;
        }
        $json_startup_items = json_encode($strt_arr);
    }
    else
    {
        $json_startup_items = '';
    }
    
    ###################################################
    
    // Compress the data
    if(!function_exists('curl_init')) die('No curl support found (curl_init)!  Exiting.');
    #if(!function_exists('gzcompress')) die('No gzip compression support (gzcompress)!  Exiting.');
    
    #$gzip_info    = gzcompress($json_game_info, 9);
    #$gzip_startup = gzcompress($json_startup_items, 9);
    
    ###################################################
    
    // Get basic info so we know who submitted
    #require(DOCROOT.'/includes/classes/core.php');
    #$Core = new Core;
    $gpxcfg = $Core->getsettings();
    $gpx_email    = strip_tags($gpxcfg['default_email_address']);
    $gpx_company  = strip_tags($gpxcfg['company']);
    $gpx_version  = strip_tags($gpxcfg['version']);
    
    $postfields = array();
    $postfields['email']        = base64_encode($gpx_email);
    $postfields['company']      = base64_encode($gpx_company);
    $postfields['version']      = base64_encode($gpx_version);
    $postfields['gameinfo']     = $json_game_info;
    $postfields['gamestartups'] = $json_startup_items;
    
    // Connect to gamepanelx cloud site
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://gamepanelx.com/cloud/cloudsubmit.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $data = curl_exec($ch);
    curl_close($ch);
    
    if($data == 'success') echo 'success';
    else echo 'Failed: '.$data;
}

?>
