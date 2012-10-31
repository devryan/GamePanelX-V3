<?php
require('checkallowed.php'); // No direct access

// Get game info via gpx cloud
$url_id = $GPXIN['id'];

if(empty($url_id) || !is_numeric($url_id)) die('ERROR: Invalid ID given!');

#$postfields = array();
#$postfields['id'] = $url_id;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://gamepanelx.com/games/game.php?id='.$url_id);
#curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 12);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
#curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
$cloud_data = curl_exec($ch);
if(!$cloud_data || empty($cloud_data) || !preg_match('/\[\{/', $cloud_data)) die('Failed to connect to GamePanelX Cloud: '.curl_error($ch));
curl_close($ch);

#echo '<pre>';
#var_dump($cloud_data);
#echo '</pre>';

#echo "DATA: ".htmlspecialchars($cloud_data)."<br>";




########################################################################

$cloud_arr  = json_decode($cloud_data, true);

// Store this info in session if they choose to use it
$_SESSION['cld_gameid']   = $url_id;
$_SESSION['cld_gamedata'] = $cloud_data;

#echo '<pre>';
#var_dump($decoded_data);
#echo '</pre>';

$cld_date_created   = $cloud_arr[0]['date_created'];
$cld_last_updated   = $cloud_arr[0]['last_updated'];
$cld_is_steam       = $cloud_arr[0]['is_steam'];
$cld_name           = $cloud_arr[0]['name'];
$cld_description    = $cloud_arr[0]['description'];
$cld_icon           = $cloud_arr[0]['icon'];
$cld_port           = $cloud_arr[0]['port'];
$cld_banned_chars   = $cloud_arr[0]['banned_chars'];
$cld_gameq          = $cloud_arr[0]['gameq_name'];
$cld_intname        = $cloud_arr[0]['int_name'];
$cld_working_dir    = $cloud_arr[0]['working_dir'];
$cld_pid_file       = $cloud_arr[0]['pid_file'];
$cld_update_cmd     = $cloud_arr[0]['update_cmd'];
$cld_simplecmd      = $cloud_arr[0]['simplecmd'];

if($cld_is_steam) $cld_is_steam = $lang['yes'];
else $cld_is_steam = $lang['no'];

echo '<table border="0" cellpadding="2" cellspacing="0" width="600">
      <tr>
        <td colspan="2" style="font-size:20pt;color:#333;">'.$cld_name.'</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td width="140"><b>'.$lang['date_added'].':</b></td>
        <td>'.$cld_date_created.'</td>
      </tr>
      <tr>
        <td><b>'.$lang['last_updated'].':</b></td>
        <td>'.$cld_last_updated.'</td>
      </tr>
      <tr>
        <td><b>'.$lang['desc'].':</b></td>
        <td>'.$cld_description.'</td>
      </tr>
      <tr>
        <td><b>Steam:</b></td>
        <td>'.$cld_is_steam.'</td>
      </tr>
      <tr>
        <td><b>'.$lang['port'].':</b></td>
        <td>'.$cld_port.'</td>
      </tr>
      </table>
      
      <br /><br />
      
      <div align="center">
        <input type="button" class="button" value="Click to Install" onClick="javascript:cloud_install_game('.$url_id.');" />
      </div>';

?>
