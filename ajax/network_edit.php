<?php
$forceadmin = 1; // Admins only
require('checkallowed.php'); // No direct access

// URL ID
$url_id   = $GPXIN['id'];

// Include config
require(DOCROOT.'/configuration.php');

$enc_key  = $settings['enc_key'];
if(empty($url_id)) die('No ID provided');
if(empty($enc_key)) die('No encryption key found!  Check "/configuration.php"');

// List available Network Servers
$result_net = $GLOBALS['mysqli']->query("SELECT 
                              id,
                              is_local,
                              ip,
                              os,
                              datacenter,
                              location,
                              AES_DECRYPT(login_user, '$enc_key') AS login_user,
                              AES_DECRYPT(login_pass, '$enc_key') AS login_pass,
                              AES_DECRYPT(login_port, '$enc_key') AS login_port,
                              homedir 
                            FROM network 
                            WHERE 
                              id = '$url_id'") or die('Failed to query for network servers: '.$GLOBALS['mysqli']->error);

while($row_net  = $result_net->fetch_array())
{
    $net_local          = $row_net['is_local'];
    $net_ip             = $row_net['ip'];
    $net_loc            = $row_net['location'];
    $net_os             = $row_net['os'];
    $net_datacenter     = $row_net['datacenter'];
    $net_login_user     = $row_net['login_user'];
    $net_login_pass     = $row_net['login_pass'];
    $net_login_port     = $row_net['login_port'];
    $net_login_homedir  = $row_net['homedir'];
}
?>

<div class="infobox" style="display:none;"></div>

<?php echo $lang['network']; ?> ID #<b><?php echo $url_id; ?></b>

<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;">
<tr>
  <td width="150"><b><?php echo $lang['ip']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_ip; ?>" id="ip" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['local_server']; ?>:</b></td>
  <td>
    <select class="dropdown" id="is_local">
    <?php
    if($net_local) echo '<option value="1" selected>' . $lang['yes'] . '</option><option value="0">' . $lang['no'] . '</option>';
    else echo '<option value="1">' . $lang['yes'] . '</option><option value="0" selected>' . $lang['no'] . '</option>';
    ?>
    </select>
  </td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['location']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_loc; ?>" id="location" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['os']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_os; ?>" id="os" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['datacenter']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_datacenter; ?>" id="datacenter" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['login_user']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_login_user; ?>" id="login_user" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['login_pass']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_login_pass; ?>" id="login_pass" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['login_port']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_login_port; ?>" id="login_port" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['login_homedir']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_login_homedir; ?>" id="homedir" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['delete']; ?>:</b></td>
  <td><span style="font-size:11pt;color:red;cursor:pointer;" onClick="javascript:network_confirm_delete(<?php echo $url_id; ?>);"><?php echo $lang['delete']; ?></span></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

</table>

<div align="center">
  <div class="button" onClick="javascript:network_save(<?php echo $url_id; ?>);"><?php echo $lang['save']; ?></div>
</div>
