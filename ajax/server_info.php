<?php
require('checkallowed.php'); // No direct access

// URL ID
$url_id = $GPXIN['id'];
$gpx_srvid=$url_id; require(DOCROOT.'/checkallowed.php'); // Check login/ownership

// Show Server Tabs
$tab = 'info';
require('server_tabs.php');

// Setup game query on server
$info = array();
$info[0]['server']  = $srvinfo[0]['gameq_name'];
$info[0]['ip']      = $srvinfo[0]['ip'];
$info[0]['port']    = $srvinfo[0]['port'];

// Query Response
$query_info = $Servers->gamequery($info);

#echo '<pre>';
#var_dump($query_info);
#echo '</pre>';

$qry_status       = trim($query_info[0]['current_status']);
$qry_players_cur  = trim($query_info[0]['current_numplayers']);
$qry_players_max  = trim($query_info[0]['current_maxplayers']);
$qry_map          = trim($query_info[0]['current_mapname']);
$qry_hostname     = trim($query_info[0]['current_hostname']);




/*
 * GameQ V2 (3 second delays for some reason...)
 * 
$srv_ip   = $srvinfo[0]['ip'];
$srv_port = $srvinfo[0]['port'];

require(DOCROOT.'/includes/GameQ2/GameQ.php');

$servers = array(
	array(
		'id' => 'srv'.$url_id,
		'type' => 'source',
		'host' => $srv_ip.':'.$srv_port,
	)
);

// Call the class, and add your servers.
$gq = new GameQ();
$gq->addServers($servers);

// You can optionally specify some settings
#$gq->setOption('timeout', 3); // Seconds

// You can optionally specify some output filters,
// these will be applied to the results obtained.
#$gq->setFilter('normalise');

// Send requests, and parse the data
$results = $gq->requestData();

echo '<pre>';
var_dump($results);
echo '</pre>';
*/
?>

<div class="infobox" style="display:none;"></div>


<div class="box" style="width:750px;">
<div class="box_title" id="box_servers_title"><?php echo $lang['info']; ?></div>
<div class="box_content" id="box_servers_content">


<?php
//
// Restart, Stop, Update buttons
//
echo '<div style="width:100%;height:70px;margin-bottom:10px;">';

$restart_btn  = '<div style="float:left;cursor:pointer;" onClick="javascript:server_restart(' . $url_id . ');" title="'.$lang['restart'].'"><img src="'.$relpath.'images/icons/medium/server_restart.png" border="0" /></div>';
$stop_btn     = '<div style="float:left;margin-left:15px;cursor:pointer;" onClick="javascript:server_stop(' . $url_id . ');" title="'.$lang['stop'].'"><img src="'.$relpath.'images/icons/medium/server_stop.png" border="0" /></div>';
$update_btn   = '<div style="float:left;margin-left:15px;cursor:pointer;" onClick="javascript:server_update('.$url_id.');" title="'.$lang['update'].'"><img src="'.$relpath.'images/icons/medium/update.png" border="0" /></div>';

// Updating, only allow stopping
if($srvinfo[0]['status'] == 'updating')
{
    echo $stop_btn;
}
// Show all buttons
else
{
    echo $restart_btn . $stop_btn;
    if($srvinfo[0]['update_cmd']) echo $update_btn;
}

echo '</div>';
?>
    
<table border="0" cellpadding="2" cellspacing="0" width="600" class="cfg_table">
<tr>
  <td width="100"><b><?php echo $lang['status']; ?>:</b></td>
  <td>
      <?php
      // Complete
      if($srvinfo[0]['status'] == 'complete')
      {
          if($qry_status == 'online') echo '<font color="green"><b>' . $lang['online'] . '</b></font>';
          elseif($qry_status == 'offline') echo '<font color="red"><b>' . $lang['offline'] . '</b></font>';
          else echo '<font color="orange">' . $lang['unknown'] . '</font>';
      }
      // Updating
      elseif($srvinfo[0]['status'] == 'updating')
      {
          echo '<font color="blue"><b>Updating</b> (Click Stop to kill updating process)</font>';
      }
      // Others
      else
      {
          echo '<font color="orange"><b>'.ucwords($srvinfo[0]['status']).'</b></font>';
      }
      ?>
  </td>
</tr>
<?php
// Show extra info if online
if($qry_status == 'online')
{
    // Process info
    if(isset($_SESSION['gpx_admin']))
    {
        echo '<tr>
                <td><b>System Info:</b></td>
                <td id="gamecpu">&nbsp;</td>
              </tr>
              <tr>
                <td><b>Process IDs:</b></td>
                <td id="gamepids">&nbsp;</td>
              </tr>';
    }
    
    // Map
    if($qry_map) echo '<tr>
                          <td><b>'.$lang['map'].':</b></td>
                          <td>' . $qry_map . '</td>
                        </tr>';
    
    // Hostname
    if($qry_hostname) echo '<tr>
                              <td><b>'.$lang['hostname'].':</b></td>
                              <td>' . $qry_hostname . '</td>
                            </tr>';
    
    // Current Players
    if($qry_players_max) echo '<tr>
                              <td><b>'.$lang['players'].':</b></td>
                              <td>' . $qry_players_cur . ' / ' . $qry_players_max . '</td>
                            </tr>';
}

?>

<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['connect']; ?>:</b></td>
  <td><?php echo $srvinfo[0]['ip'] . ':' . $srvinfo[0]['port']; ?></td>
</tr>

<?php
// Check user permission
if($_SESSION['gpx_perms']['perm_startup'] == 1 && $_SESSION['gpx_perms']['perm_startup_see'] == 1 || isset($_SESSION['gpx_admin']))
{
?>
<tr>
  <td><b><?php echo $lang['startup']; ?>:</b></td>
  <td><i><?php echo strip_tags($srvinfo[0]['simplecmd']); ?></i></td>
</tr>
<?php
}
?>

</table>

</div></div>

<script type="text/javascript">
$(document).ready(function(){
    server_getinfo(<?php echo $url_id; ?>);
});
</script>
