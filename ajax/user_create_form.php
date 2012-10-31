<?php
require('checkallowed.php'); // No direct access
?>

<div class="infobox" style="display:none;"></div>


<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['add_user']; ?></div>
<div class="box_content" id="box_servers_content">


<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;">
<tr>
  <td width="150"><b><?php echo $lang['username']; ?>:</b></td>
  <td><input type="text" value="" id="username" class="inputs" /></td>
</tr>
<tr>
  <td width="150"><b><?php echo $lang['newpassword']; ?>:</b></td>
  <td><input type="password" value="" id="pass1" class="inputs" /></td>
</tr>
<tr>
  <td width="150"><?php echo $lang['newpassword_conf']; ?>:</td>
  <td><input type="password" value="" id="pass2" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td width="150"><b><?php echo $lang['email_address']; ?>:</b></td>
  <td><input type="text" value="" id="email" class="inputs" /></td>
</tr>
<tr>
  <td width="150"><b><?php echo $lang['first_name']; ?>:</b></td>
  <td><input type="text" value="" id="fname" class="inputs" /></td>
</tr>
<tr>
  <td width="150"><b><?php echo $lang['last_name']; ?>:</b></td>
  <td><input type="text" value="" id="lname" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

</table>

<div align="center">
  <div class="button" onClick="javascript:user_create();"><?php echo $lang['save']; ?></div>
</div>


</div>
</div>
