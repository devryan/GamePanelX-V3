<?php
$forceadmin = 1; // Admins only
require('checkallowed.php'); // No direct access

// URL ID
$url_id = $GPXIN['id'];

// List templates
$result_srv = $GLOBALS['mysqli']->query("SELECT 
                              t.id,
                              t.netid,
                              t.date_created,
                              t.is_default,
                              t.status,
                              t.size,
                              t.file_path,
                              t.description,
                              d.name,
                              n.ip,
                              n.location 
                            FROM templates AS t 
                            LEFT JOIN network AS n ON 
                              t.netid = n.id 
                            LEFT JOIN default_games AS d ON 
                              t.cfgid = d.id 
                            WHERE 
                              t.id = '$url_id'") or die($lang['err_query'].' ('.$GLOBALS['mysqli']->error.')');

while($row_tpl  = $result_srv->fetch_array())
{
    $tp_id        = $row_tpl['id'];
    $tp_netid     = $row_tpl['netid'];
    $tp_date      = $row_tpl['date_created'];
    $tp_default   = $row_tpl['is_default'];
    $tp_status    = $row_tpl['status'];
    $tp_size      = $row_tpl['size'];
    $tpl_filepath = $row_tpl['file_path'];
    $tp_descr     = stripslashes($row_tpl['description']);
    $tp_ip        = $row_tpl['ip'];
    $tp_game      = $row_tpl['name'];
    $tp_loc       = stripslashes($row_tpl['location']);
}

if($tp_status == 'complete') $tp_status = '<font color="green">'.ucwords($tp_status).'</font>';
elseif($tp_status == 'running') $tp_status = '<font color="orange">'.ucwords($tp_status).'</font>';
elseif($tp_status == 'failed') $tp_status = '<font color="red">'.ucwords($tp_status).'</font>';
?>

<div class="infobox" style="display:none;"></div>

<span style="font-size:18pt;"><?php echo $lang['template']; ?> #<b><?php echo $tp_id; ?></b></span>

<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;">
<tr>
  <td width="150"><b><?php echo $lang['server']; ?>:</b></td>
  <td><?php echo $tp_game; ?></td>
</tr>
<tr>
  <td width="150"><b><?php echo $lang['network']; ?>:</b></td>
  <td>
      <?php
      echo $tp_ip;
      
      if($tp_loc) echo ' (' . $tp_loc . ')';
      ?>
  </td>
</tr>
<tr>
  <td><b><?php echo $lang['status']; ?>:</b></td>
  <td><b><?php echo $tp_status; ?></b></td>
</tr>
<tr>
  <td><b><?php echo $lang['size']; ?>:</b></td>
  <td><?php if(empty($tp_size)) echo ucwords($lang['unknown']); else echo '<b>'.$tp_size.'</b>'; ?></td>
</tr>
<tr>
  <td><b><?php echo $lang['default']; ?>:</b></td>
  <td>
    <select class="dropdown" id="is_default">
    <?php
    if($tp_default) echo '<option value="1" selected>' . $lang['yes'] . '</option><option value="0">' . $lang['no'] . '</option>';
    else echo '<option value="1">' . $lang['yes'] . '</option><option value="0" selected>' . $lang['no'] . '</option>';
    ?>
    </select>
  </td>
</tr>
<tr>
  <td><b><?php echo $lang['desc']; ?>:</b></td>
  <td><input type="text" value="<?php echo $tp_descr; ?>" id="desc" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['file_path']; ?>:</b></td>
  <td><input type="text" value="<?php echo $tpl_filepath; ?>" id="desc" class="inputs" readonly style="background:#E9E9E9;" /></td>
</tr>


<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['delete']; ?>:</b></td>
  <td><span style="font-size:11pt;color:red;cursor:pointer;" onClick="javascript:template_confirm_delete(<?php echo $url_id; ?>);"><?php echo $lang['delete_tp']; ?></span></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

</table>

<div align="center">
  <div class="button" onClick="javascript:template_save(<?php echo $url_id . ',' . $tp_netid; ?>);"><?php echo $lang['save']; ?></div>
</div>
