<?php
require('checkallowed.php'); // No direct access
?>

<div class="infobox" style="display:none;"></div>

<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;">
<tr>
  <td width="150"><b><?php echo $lang['ip']; ?>:</b></td>
  <td><input type="text" value="" id="ip" class="inputs" /></td>
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
  <td><input type="text" value="" id="location" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['os']; ?>:</b></td>
  <td><input type="text" value="" id="os" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['datacenter']; ?>:</b></td>
  <td><input type="text" value="" id="datacenter" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['login_user']; ?>:</b></td>
  <td><input type="text" value="" id="login_user" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['login_pass']; ?>:</b></td>
  <td><input type="password" value="" id="login_pass" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['login_port']; ?>:</b></td>
  <td><input type="text" value="" id="login_port" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['login_homedir']; ?>:</b></td>
  <td><input type="text" value="" id="homedir" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

</table>

<div align="center">
  <div class="button" onClick="javascript:network_create();"><?php echo $lang['save']; ?></div>
</div>
