<?php
require('checkallowed.php'); // Check logged-in

// URL ID
$url_id   = $GPXIN['id'];

// Include config
#require(DOCROOT.'/configuration.php');

$enc_key  = $settings['enc_key'];
if(empty($url_id)) die('No ID provided');
if(empty($enc_key)) die($lang['no_enc_key']);

// List available Network Servers
$result_net = $GLOBALS['mysqli']->query("SELECT 
                              id,
                              parentid,
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
    $net_parentid       = $row_net['parentid'];
    $net_loc            = $row_net['location'];
    $net_os             = $row_net['os'];
    $net_datacenter     = $row_net['datacenter'];
    $net_login_user     = $row_net['login_user'];
    $net_login_pass     = $row_net['login_pass'];
    $net_login_port     = $row_net['login_port'];
    $net_login_homedir  = $row_net['homedir'];
}

// Tabs
$tab = 'edit';
include(DOCROOT.'/ajax/network_tabs.php');
?>
<div class="infobox" style="display:none;"></div>

<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['edit']; ?> #<?php echo $url_id; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;">
<tr>
  <td width="150"><b><?php echo $lang['ip']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_ip; ?>" id="edit_ip" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['type']; ?>:</b></td>
  <td>
    <select class="dropdown" id="edit_is_local">
    <?php
    if($net_local) echo '<option value="1" selected>' . $lang['local_server'] . '</option><option value="0">' . $lang['remote_server'] . '</option>';
    else echo '<option value="1">' . $lang['local_server'] . '</option><option value="0" selected>' . $lang['remote_server'] . '</option>';
    ?>
    </select>
  </td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['location']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_loc; ?>" id="edit_location" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['os']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_os; ?>" id="edit_os" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['datacenter']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_datacenter; ?>" id="edit_datacenter" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['login_user']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_login_user; ?>" id="edit_login_user" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['login_pass']; ?>:</b></td>
  <td><input type="password" value="<?php echo $net_login_pass; ?>" id="edit_login_pass" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['login_port']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_login_port; ?>" id="edit_login_port" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['login_homedir']; ?>:</b></td>
  <td><input type="text" value="<?php echo $net_login_homedir; ?>" id="edit_homedir" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['delete']; ?>:</b></td>
  <td><span style="font-size:11pt;color:red;cursor:pointer;" onClick="javascript:network_confirm_delete(<?php echo $url_id; ?>);"><?php echo $lang['click_here']; ?></span></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

</table>

<div align="center">
  <div class="button" id="net_save" onClick="javascript:network_save(<?php echo $url_id; ?>);"><?php echo $lang['save']; ?></div>
</div>

</div></div>
