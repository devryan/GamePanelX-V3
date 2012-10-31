<?php
require('checkallowed.php'); // Check logged-in

$url_id = $GPXIN['id'];

// Get user info
$result_usr = @mysql_query("SELECT 
                              perm_ftp,
                              perm_files,
                              perm_startup,
                              perm_chpass,
                              perm_updetails,
                              username 
                            FROM users 
                            WHERE 
                              id = '$url_id' 
                            LIMIT 1") or die('Failed to query for users: '.mysql_error());

while($row_usr  = mysql_fetch_array($result_usr))
{
    $usr_usrname      = $row_usr['username'];
    $perm_ftp         = $row_usr['perm_ftp'];
    $perm_files       = $row_usr['perm_files'];
    $perm_startup     = $row_usr['perm_startup'];
    $perm_chpass      = $row_usr['perm_chpass'];
    $perm_updetails   = $row_usr['perm_updetails'];
}

$tab  = 'perms';
include(DOCROOT.'/ajax/user_tabs.php');
?>

<div class="infobox" style="display:none;"></div>

<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;">

<tr>
  <td width="150"><b><?php echo $lang['access_ftp']; ?>:</b></td>
  <td><input type="radio" value="1" name="perm_ftp" id="perm_ftp_1" <?php if($perm_ftp) echo 'checked '; ?>/><label for="perm_ftp_1"> <?php echo $lang['yes']; ?> </label><input type="radio" value="0" name="perm_ftp" id="perm_ftp_2" <?php if(!$perm_ftp) echo 'checked '; ?>/><label for="perm_ftp_2"> <?php echo $lang['no']; ?></label> </td>
</tr>
<tr>
  <td width="150"><b><?php echo $lang['files']; ?>:</b></td>
  <td><input type="radio" value="1" name="perm_fm" id="perm_fm_1" <?php if($perm_files) echo 'checked '; ?>/><label for="perm_fm_1"> <?php echo $lang['yes']; ?> </label><input type="radio" value="0" name="perm_fm" id="perm_fm_2" <?php if(!$perm_files) echo 'checked '; ?>/><label for="perm_fm_2"> <?php echo $lang['no']; ?></label> </td>
</tr>
<tr>
  <td width="150"><b><?php echo $lang['startup']; ?>:</b></td>
  <td><input type="radio" value="1" name="perm_str" id="perm_str_1" <?php if($perm_startup) echo 'checked '; ?>/><label for="perm_str_1"> <?php echo $lang['yes']; ?> </label><input type="radio" value="0" name="perm_str" id="perm_str_2" <?php if(!$perm_startup) echo 'checked '; ?>/><label for="perm_str_2"> <?php echo $lang['no']; ?></label> </td>
</tr>
<tr>
  <td width="150"><b><?php echo $lang['update_usr_det']; ?>:</b></td>
  <td><input type="radio" value="1" name="perm_upd" id="perm_upd_1" <?php if($perm_updetails) echo 'checked '; ?>/><label for="perm_upd_1"> <?php echo $lang['yes']; ?> </label><input type="radio" value="0" name="perm_upd" id="perm_upd_2" <?php if(!$perm_updetails) echo 'checked '; ?>/><label for="perm_upd_2"> <?php echo $lang['no']; ?></label> </td>
</tr>
<tr>
  <td width="150"><b><?php echo $lang['chpassword']; ?>:</b></td>
  <td><input type="radio" value="1" name="perm_chpass" id="perm_chpass_1" <?php if($perm_chpass) echo 'checked '; ?>/><label for="perm_chpass_1"> <?php echo $lang['yes']; ?> </label><input type="radio" value="0" name="perm_chpass" id="perm_chpass_2" <?php if(!$perm_chpass) echo 'checked '; ?>/><label for="perm_chpass_2"> <?php echo $lang['no']; ?></label> </td>
</tr>
</table>

<div align="center">
  <div class="button" onClick="javascript:user_perm_save(<?php echo $url_id; ?>);"><?php echo $lang['save']; ?></div>
</div>
