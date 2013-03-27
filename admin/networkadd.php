<?php
require('checkallowed.php'); // Check logged-in
?>
<style type="text/css">
.login_disp
{
    display: none;
}
</style>

<script>
function showRemote()
{
    if($('#add_is_local').val() == '0') $('.login_disp').fadeIn();
    else if($('#add_is_local').val() == '1') $('.login_disp').fadeOut();
}
</script>

<div class="infobox" style="display:none;"></div>

<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['create_network']; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;">
<tr>
  <td width="150"><b><?php echo $lang['ip']; ?>:</b></td>
  <td><input type="text" value="" id="add_ip" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['type']; ?>:</b></td>
  <td>
    <select class="dropdown" id="add_is_local" onChange="javascript:showRemote();">
      <option value="1" selected><?php echo $lang['local_server']; ?></option>
      <option value="0"><?php echo $lang['remote_server']; ?></option>
    </select>
  </td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['location']; ?>:</b></td>
  <td><input type="text" value="" id="add_location" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['os']; ?>:</b></td>
  <td><input type="text" value="" id="add_os" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['datacenter']; ?>:</b></td>
  <td><input type="text" value="" id="add_datacenter" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="login_disp">
  <td colspan="2" style="color:red;"><b>Note:</b> Using this option assumes that you have installed the Remote Server files available from gamepanelx.com.</td>
</tr>
<tr class="login_disp">
  <td><b><?php echo $lang['login_user']; ?>:</b></td>
  <td><input type="text" value="" id="add_login_user" class="inputs" /></td>
</tr>
<tr class="login_disp">
  <td><b><?php echo $lang['login_pass']; ?>:</b></td>
  <td><input type="password" value="" id="add_login_pass" class="inputs" /></td>
</tr>
<tr class="login_disp">
  <td><b><?php echo $lang['login_port']; ?>:</b></td>
  <td><input type="text" value="22" id="add_login_port" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

</table>

<div align="center">
  <div class="button" id="net_save" onClick="javascript:network_create();"><?php echo $lang['add']; ?></div>
</div>

</div></div>
