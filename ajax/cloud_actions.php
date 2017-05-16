<?php
$forceadmin = 1; // Admins only
require('checkallowed.php'); // No direct access

/*
 *
 * Clicking "Install" should curl get complete info on that game (def row, startup items, game icon, and insert a row here.)
 * Reinstall is identical, but first deletes current rows and startup items for new thing
*/
error_reporting(E_ERROR);

// actions
$url_id       = $GPXIN['id'];
$url_do       = $GPXIN['do']; // Action

// Create
if($url_do == 'getall')
{
    // Setup Database
    #require('includes/classes/core.php');
    #$Core = new Core;
    #$Core->dbconnect();



    // Get currently installed game data
    $result_cur = $GLOBALS['mysqli']->query("SELECT cloudid FROM default_games ORDER BY id ASC") or die('ERROR: Failed to query for current games');
    $arr_curr   = array();

    while($row_cur  = $result_cur->fetch_array())
    {
        $arr_curr[] = $row_cur;
    }

    #echo '<pre>';
    #var_dump($arr_curr);
    #echo '</pre>';

    ########################################################################

    #$postfields = array();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://gamepanelx.com/cloud/gamelist.php');
    #curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 12);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    #curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $cloud_data = curl_exec($ch);
    if(!$cloud_data || empty($cloud_data) || !preg_match('/\[\{/', $cloud_data)) die('Failed to connect to GamePanelX Cloud: '.curl_error($ch));
    curl_close($ch);

    $cloud_data = json_decode($cloud_data, true);
    
    #echo '<pre>';
    #var_dump($cloud_data);
    #echo '</pre>';
    
    // Loop through available games
    foreach($cloud_data as $game)
    {
        $has_game=false;
        $game_id            = $game['id'];
        $game_date_created  = $game['date_created'];
        $game_last_updated  = $game['last_updated'];
        $game_is_steam      = $game['steam'];
        $game_name          = stripslashes($game['name']);
        $game_intname       = stripslashes($game['intname']);
        $game_description   = stripslashes($game['description']);
        
        // Use proper icon
        if(file_exists(DOCROOT.'/images/gameicons/small/'.$game_intname.'.png')) $game_icon_small = '../images/gameicons/small/'.$game_intname.'.png';
        else $game_icon_small    = 'http://gamepanelx.com/'.stripslashes($game['icon_small']);
        
        echo '<tr id="availgame_' . $game_id . '" style="cursor:pointer;" onClick="javascript:cloud_game_info('.$game_id.');">
                <td><img src="' . $game_icon_small . '" width="28" height="28" border="0" /></td>
                <td>' . $game_name . '</td>
                <td style="font-size:10pt;">' . $game_description . '</td>
                <td style="font-size:10pt;">'.$game_last_updated.'</td>';
                
                foreach($arr_curr as $game => $key)
                {
                    if($key['cloudid'] == $game_id)
                    {
                        echo '<td><span style="cursor:pointer;font-weight:normal;" class="links" onClick="javascript:cloud_game_info('.$game_id.');">Reinstall</span></td>';
                        $has_game=true;
                        break;
                    }
                }
                
                if(!$has_game) echo '<td><span style="cursor:pointer;font-weight:bold;" class="links" onClick="javascript:cloud_game_info('.$game_id.');">Install</span></td>';
                
        echo '</tr>';
    }
}


// Check system updates
elseif($url_do == 'check_updates')
{
    // Only check updates on initial login
    if(!isset($_SESSION['gpx_upd_ck']))
    {
        // Get current version
        $result_vr    = $GLOBALS['mysqli']->query("SELECT config_value FROM configuration WHERE config_setting = 'version' LIMIT 1");
        $row_vr       = $result_vr->fetch_row();
        $gpx_version  = $row_vr[0];
        
        // Check GPX Cloud for any updates
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://gamepanelx.com/cloud/updates.php');
        curl_setopt($ch, CURLOPT_TIMEOUT, 12);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $cloud_data = curl_exec($ch);
        
        // Error
        if(!$cloud_data || empty($cloud_data) || !preg_match('/\{\"/', $cloud_data))
        {
            if(GPXDEBUG) die('Failed to check the GamePanelX Cloud for updates: '.curl_error($ch));
            else die('success');
        }
        
        curl_close($ch);
        $arr_data = json_decode($cloud_data, true);
        $latest_ver   = $arr_data['latest'];
        $release_date = $arr_data['date'];
        
        // Updates available
        # if($latest_ver > $gpx_version) 
        #if (version_compare($gpx_version, $latest_ver, "<=")) echo '<b>'.$lang['system_update'].'</b>  (Currently: v'.$gpx_version.', Latest: v'.$latest_ver.').  This was released on '.$release_date.'.  See the <a href="http://gamepanelx.com/downloads/" class="links" target="_blank">Downloads</a> page to get it.';
        if(version_compare($gpx_version, $latest_ver) == -1) echo '<b>'.$lang['system_update'].'</b>  (Currently: v'.$gpx_version.', Latest: v'.$latest_ver.').  This was released on '.$release_date.'.  See the <a href="http://gamepanelx.com/downloads/" class="links" target="_blank">Downloads</a> page to get it.';
        
        // Up to date
        else echo 'success';
        
        // Set sess var to only check once
        $_SESSION['gpx_upd_ck'] = '1';
    }
    // Already checked, just show as fine
    else
    {
        echo 'success';
    }
}


?>
