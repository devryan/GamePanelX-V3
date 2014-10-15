<?php
$forceadmin = 1; // Admins only
require('checkallowed.php'); // No direct access
?>

<div class="page_title">
    <div class="page_title_icon"><img src="../images/icons/medium/template.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['server_templates']; ?></div>
</div>

<span class="links" onClick="javascript:mainpage('gamesedit',<?php echo $url_id; ?>);"><?php echo $lang['go_back']; ?></span><br />

<div class="infobox" style="display:none;"></div>

<script language="javascript">
$(document).ready(function(e) {
    try {
    $("body select").msDropDown();
    } catch(e) {
    alert(e.message);
    }
});
</script>

<div class="box">
<div class="box_title" style="margin-top:0px;" id="box_servers_title"><?php echo $lang['create_tp']; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;">
<tr>
  <td width="180"><b><?php echo $lang['server']; ?>:</b></td>
  <td>
      <select class="dropdown" id="create_tpl_game" style="width:435px;">
      <option value="" title="../images/icons/small/select_down_arrow.png"><?php echo $lang['server']; ?></option>
      
      <?php
      $url_id = $GPXIN['id'];
      
      // Grab list of available games
      $result_sv  = @mysql_query("SELECT 
                                    id,
                                    steam,
                                    port,
                                    name,
                                    intname,
                                    description,
                                    simplecmd 
                                  FROM default_games 
                                  ORDER BY name ASC");
      
      while($row_sv = mysql_fetch_array($result_sv))
      {
          $sv_id        = $row_sv['id'];
          $sv_steam     = $row_sv['steam'];
          $sv_port      = $row_sv['port'];
          $sv_name      = $row_sv['name'];
          $sv_intname   = $row_sv['intname'];
          $sv_descr     = $row_sv['description'];
          $sv_cmd       = $row_sv['simplecmd'];
          
          if($url_id == $sv_id) $selected = ' selected';
          else $selected = '';
          
          echo '<option value="'.$sv_id.'" title="../images/gameicons/small/'.$sv_intname.'.png"'.$selected.'>'.$sv_name.'</option>';
      }
      ?>
      </select>
  </td>
</tr>

<tr>
  <td><b><?php echo $lang['network']; ?>:</b></td>
  <td>
    <select class="dropdown" id="create_tpl_network" style="width:435px;">
    <option value="" title="../images/icons/small/select_down_arrow.png"><?php echo $lang['network_server']; ?></option>
    
    <?php
    // List available parent Network Servers
    $result_net = @mysql_query("SELECT DISTINCT 
                                  p.id,
                                  p.is_local,
                                  p.ip,
                                  p.location 
                                FROM network AS n 
                                JOIN network AS p ON 
                                  n.parentid = p.id 
                                  OR n.parentid = '0' 
                                WHERE 
                                  p.parentid = '0' 
                                ORDER BY 
                                  p.parentid ASC,
                                  p.ip ASC") or die('Failed to query for network servers: '.mysql_error());
    
    $total_nets = mysql_num_rows($result_net);
    
    while($row_net  = mysql_fetch_array($result_net))
    {
        $net_id     = $row_net['id'];
        $net_local  = $row_net['is_local'];
        $net_ip     = $row_net['ip'];
        $net_loc    = $row_net['location'];
        
        if($net_loc) $net_displ = $net_ip.' (' . $net_loc . ')';
        else $net_displ = $net_ip;
        
        // If only 1 network server, select it automatically for them
        if($total_nets == 1) echo '<option value="'.$net_id.'" selected>'.$net_displ .'</option>';
        else echo '<option value="'.$net_id.'">'.$net_displ .'</option>';
    }
    ?>
    </select>
  </td>
</tr>
<tr>
  <td><b><?php echo $lang['desc']; ?>:</b></td>
  <td><input type="text" value="<?php echo $tp_descr; ?>" id="create_tpl_desc" class="inputs" style="width:435px;" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['default']; ?>:</b></td>
  <td>
    <select class="dropdown" id="create_tpl_is_default" style="width:435px;">
    
    <?php
    #if($tp_default) echo '<option value="1" selected>' . $lang['yes'] . '</option><option value="0">' . $lang['no'] . '</option>';
    #else echo '<option value="1">' . $lang['yes'] . '</option><option value="0" selected>' . $lang['no'] . '</option>';
    echo '<option value="1" selected>' . $lang['yes'] . '</option><option value="0">' . $lang['no'] . '</option>';
    ?>
    </select>
  </td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr id="set_path_opt">
  <td colspan="2"><span class="links" onClick="javascript:$('#set_path_opt').hide();$('#set_path_show_1').show();$('#set_path_show_2').show();">Optional: Set file path manually</span></td>
</tr>
<tr id="set_path_show_1" style="display:none;">
  <td colspan="2"><?php echo $lang['note_steam_auto']; ?></td>
</tr>
<tr id="set_path_show_2" style="display:none;">
  <td><b><?php echo $lang['file_path']; ?>:</b> (<?php echo $lang['optional']; ?>)</td>
  <td><input type="text" value="" id="create_tpl_file_path" class="inputs" style="width:435px;" /> <span id="browse_done" style="display:none;color:green;"><?php echo $lang['saved']; ?>! </span> <span class="links" onClick="javascript:template_browse_dir();">(<?php echo $lang['browse']; ?>)</span></td>
</tr>

</table>

<div align="center">
  <div class="button" onClick="javascript:template_create();"><?php echo $lang['create_tp']; ?></div>
</div>


</div>
</div>
