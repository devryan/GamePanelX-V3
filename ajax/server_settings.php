<?php
require('checkallowed.php'); // No direct access

// URL ID
$url_id = $GPXIN['id'];
$gpx_srvid=$url_id; require(DOCROOT.'/checkallowed.php'); // Check login/ownership

// Show Server Tabs
$tab = 'settings';
require('server_tabs.php');
?>
<div class="infobox" style="display:none;"></div>

<div class="box" style="width:750px;">
<div class="box_title" id="box_servers_title"><?php echo $lang['settings']; ?></div>
<div class="box_content" id="box_servers_content">


<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;">

<tr>
  <td width="130"><b>ID:</b></td>
  <td><?php echo $srvinfo[0]['id']; ?></td>
</tr>
<tr>
  <td width="130"><b><?php echo $lang['install']; ?>:</b></td>
  <td><?php echo ucwords($srvinfo[0]['status']); ?></td>
</tr>

<tr>
  <td><b><?php echo $lang['last_updated']; ?>:</b></td>
  <td><?php echo strtolower($srvinfo[0]['last_updated']); ?></td>
</tr>

<?php
// Only admins
if(isset($_SESSION['gpx_admin']))
{
?>
<tr>
  <td><b><?php echo $lang['delete']; ?>:</b></td>
  <td><span class="links" onClick="javascript:confirm_server_delete(<?php echo $url_id; ?>);"><?php echo $lang['delete']; ?></span></td>
</tr>
<?php
}
?>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['desc']; ?>:</b></td>
  <td><input type="text" id="srv_desc" value="<?php echo $srvinfo[0]['description']; ?>" class="inputs" /></td>
</tr>

<?php
// Only admins
if(isset($_SESSION['gpx_admin']))
{
?>
<tr>
  <td><b><?php echo $lang['ip']; ?>:</b></td>
  <td>
    <select class="dropdown" id="ip">
    
    <?php
    // List available IP's
    $result_net = @mysql_query("SELECT 
                                  id,
                                  ip 
                                FROM network 
                                ORDER BY 
                                  ip ASC") or die('Failed to query for IP Addresses');
    
    while($row_net  = mysql_fetch_array($result_net))
    {
        $net_id     = $row_net['id'];
        $net_ip     = $row_net['ip'];
        
        if($net_ip == $srvinfo[0]['ip']) echo '<option value="'.$net_id.'" selected>'.$net_ip.'</option>';
        else echo '<option value="'.$net_id.'">'.$net_ip.'</option>';
    }
    
    if(empty($srvinfo[0]['ip'])) echo '<option value="0" selected>'.$lang['none'].'</option>';
    ?>
    </select>
  </td>
</tr>
<tr>
  <td><b><?php echo $lang['port']; ?>:</b></td>
  <td><input type="text" id="port" value="<?php echo $srvinfo[0]['port']; ?>" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['maxplayers']; ?>:</b></td>
  <td><input type="text" id="maxplayers" value="<?php echo $srvinfo[0]['maxplayers']; ?>" class="inputs" /></td>
</tr>
<?php
} // admin check
?>
<tr>
  <td><b><?php echo $lang['map']; ?>:</b></td>
  <td><input type="text" id="map" value="<?php echo $srvinfo[0]['map']; ?>" class="inputs" /></td>
</tr>
<tr>
  <td><b>Hostname:</b></td>
  <td><input type="text" id="hostname" value="<?php echo $srvinfo[0]['hostname']; ?>" class="inputs" /></td>
</tr>
<tr>
  <td><b>Rcon <?php echo $lang['password']; ?>:</b></td>
  <td><input type="text" id="rcon" value="<?php echo $srvinfo[0]['rcon']; ?>" class="inputs" /></td>
</tr>
<tr>
  <td><b>Server <?php echo $lang['password']; ?>:</b></td>
  <td><input type="text" id="sv_password" value="<?php echo $srvinfo[0]['sv_password']; ?>" class="inputs" /></td>
</tr>
</table>

<?php
// Normal users need to provide the "ip" id as well
if(!isset($_SESSION['gpx_admin'])) {
	// Get netid
	$gamesrv_id = $_SESSION['gamesrv_id'];
	$result_nid = @mysql_query("SELECT netid FROM servers WHERE id = '$gamesrv_id' LIMIT 1") or die('Failed to query for network ID');
	$row_nid    = mysql_fetch_row($result_nid);
	$net_id     = $row_nid[0];
	if(empty($net_id)) echo 'WARNING: No network ID found!<br />';

        echo '<input type="hidden" id="ip" value="' . $net_id . '" readonly />';
}
?>

<div class="links" style="margin-top:10px;margin-bottom:20px;" id="show_adv" onClick="javascript:$('#show_adv').hide();$('#tbl_adv').fadeIn();$('#tbl_adv_cmd').fadeIn();"><?php echo $lang['show_options']; ?></div>

<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;display:none;" id="tbl_adv">
<tr>
  <td width="130"><b><?php echo $lang['date_added']; ?>:</b></td>
  <td><?php echo strtolower($srvinfo[0]['date_added']); ?></td>
</tr>

<?php
// Only admins
if(isset($_SESSION['gpx_admin']))
{
?>
<tr>
  <td><b><?php echo $lang['owner']; ?>:</b></td>
  <td>
      <select id="userid" class="dropdown">
          <?php
          // Get list of users
          $result_users = @mysql_query("SELECT id,username,first_name,last_name FROM users WHERE deleted = '0' ORDER BY username ASC") or die('Failed to list users!');
          
          while($row_users  = mysql_fetch_array($result_users))
          {
              $usr_id       = $row_users['id'];
              $usr_name     = $row_users['username'];
              $usr_fullname = $row_users['first_name'] . ' ' . $row_users['last_name'];
              
              if($srvinfo[0]['userid'] == $usr_id) echo '<option value="' . $usr_id . '" selected>' . $usr_name . '(' . $usr_fullname . ')</option>';
              else echo '<option value="' . $usr_id . '">' . $usr_name . '(' . $usr_fullname . ')</option>';
          }
          ?>
      </select>
    </td>
</tr>


<tr>
  <td><b><?php echo $lang['working_dir']; ?>:</b></td>
  <td><input type="text" id="working_dir" value="<?php echo $srvinfo[0]['working_dir']; ?>" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['pid_file']; ?>:</b></td>
  <td><input type="text" id="pid_file" value="<?php echo $srvinfo[0]['pid_file']; ?>" class="inputs" /></td>
</tr>
<?php } ?>


<?php
// Only admins
if(isset($_SESSION['gpx_admin']))
{
?>
<tr>
  <td><b><?php echo $lang['startup']; ?>:</b></td>
  <td>
      <select id="startup_type" class="dropdown">
          <?php
          if($srvinfo[0]['startup']) echo '<option value="0">'.$lang['simple'].'</option><option value="1" selected>'.$lang['advanced'].'</option>';
          else echo '<option value="0" selected>'.$lang['simple'].'</option><option value="1">'.$lang['advanced'].'</option>';
          ?>
      </select>
    </td>
</tr>
<?php } ?>

</table>

<?php
// Only admins
if(isset($_SESSION['gpx_admin']))
{
?>
<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;display:none;" id="tbl_adv_cmd">
<tr>
  <td><b><?php echo $lang['command']; ?>:</b></td>
</tr>
<tr>
  <td><textarea id="cmd" class="inputs" style="width:700px;height:80px;padding:0px;"><?php echo $srvinfo[0]['simplecmd']; ?></textarea></td>
</tr>
<tr>
  <td width="130"><b><?php echo $lang['update_cmd']; ?>:</b></td>
</tr>
<tr>
  <td><textarea id="update_cmd" class="inputs" style="width:700px;height:80px;padding:0px;"><?php echo $srvinfo[0]['update_cmd']; ?></textarea></td>
</tr>
</table>
<?php } ?>

<div class="button" onClick="javascript:srv_settings_save(<?php echo $url_id; ?>);"><?php echo $lang['save']; ?></div>


</div></div>
