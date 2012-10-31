<?php
require('checkallowed.php'); // No direct access

$url_id = $GPXIN['id'];

// Get user info
$result_usr = @mysql_query("SELECT 
                              id,
                              first_name,
                              last_name,
                              username,
                              email_address 
                            FROM users 
                            WHERE 
                              id = '$url_id' 
                            LIMIT 1") or die('Failed to query for users: '.mysql_error());

while($row_usr  = mysql_fetch_array($result_usr))
{
    $usr_id         = $row_usr['id'];
    $usr_fname      = $row_usr['first_name'];
    $usr_lname      = $row_usr['last_name'];
    $usr_usrname    = $row_usr['username'];
    $usr_email      = $row_usr['email_address'];
}
?>

<div class="infobox" style="display:none;"></div>

<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;">
<tr>
  <td width="150"><b><?php echo $lang['username']; ?>:</b></td>
  <td><input type="text" value="<?php echo $usr_usrname; ?>" id="username" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td width="150"><b><?php echo $lang['cur_password']; ?>:</b></td>
  <td><input type="text" value="" id="pass_orig" class="inputs" /></td>
</tr>
<tr>
  <td width="150"><b><?php echo $lang['newpassword']; ?>:</b></td>
  <td><input type="text" value="" id="pass1" class="inputs" /></td>
</tr>
<tr>
  <td width="150"><?php echo $lang['newpassword_conf']; ?>:</td>
  <td><input type="text" value="" id="pass2" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td width="150"><b><?php echo $lang['email_address']; ?>:</b></td>
  <td><input type="text" value="<?php echo $usr_email; ?>" id="email" class="inputs" /></td>
</tr>
<tr>
  <td width="150"><b><?php echo $lang['first_name']; ?>:</b></td>
  <td><input type="text" value="<?php echo $usr_fname; ?>" id="fname" class="inputs" /></td>
</tr>
<tr>
  <td width="150"><b><?php echo $lang['last_name']; ?>:</b></td>
  <td><input type="text" value="<?php echo $usr_lname; ?>" id="lname" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

</table>

<div align="center">
  <div class="button" onClick="javascript:user_edit(<?php echo $url_id; ?>);"><?php echo $lang['save']; ?></div>
</div>
