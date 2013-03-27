<?php
require('checkallowed.php'); // No direct access
?>

<div class="infobox" id="create_info" style="display:none;"></div>

<script language="javascript">
$(document).ready(function(e) {
    // Dropdowns
    try {
        $("body select").msDropDown();
    } catch(e) {
        alert("Dropdown Error: "+e.message);
    }
});
</script>


<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['create_server']; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;">
<tr>
  <td><b><?php echo $lang['owner']; ?>:</b></td>
  <td>
    <select class="dropdown" id="create_owner" style="width:350px;">
    <option value="" title="../images/icons/small/select_down_arrow.png"><?php echo $lang['username']; ?></option>
    
    <?php
    // List user accounts
    $result_usr = @mysql_query("SELECT 
                                  id,
                                  username,
                                  first_name,
                                  last_name 
                                FROM users 
                                WHERE 
                                  deleted = '0'") or die('Failed to query for users: '.mysql_error());
    
    while($row_usr  = mysql_fetch_array($result_usr))
    {
        $userid       = $row_usr['id'];
        $usrname      = $row_usr['username'];
        $usr_fullname = ucwords($row_usr['first_name'] . ' ' . $row_usr['last_name']);
        
        if($row_usr['first_name'] || $row_usr['last_name']) $usr_fullname = ' ('.$usr_fullname.')';
        else $usr_fullname = '';
        
        echo '<option value="'.$userid.'">'.$usrname.$usr_fullname.'</option>';
    }
    ?>
    </select>
  </td>
</tr>

<tr>
  <td width="180"><b><?php echo $lang['server']; ?>:</b></td>
  <td>
      <select class="dropdown" id="create_game" style="width:350px;" onChange="javascript:server_getport();">
      <option value="" title="../images/icons/small/select_down_arrow.png"><?php echo $lang['server']; ?></option>
      
      <?php
      // Grab list of available games
      $result_sv  = @mysql_query("SELECT 
                                    d.id,
                                    d.steam,
                                    d.port,
                                    d.name,
                                    d.intname,
                                    d.description,
                                    d.simplecmd 
                                  FROM default_games AS d 
                                  LEFT JOIN templates AS t ON 
                                    d.id = t.cfgid 
                                  WHERE 
                                    t.status = 'complete' 
                                    AND t.is_default = '1' 
                                  ORDER BY name ASC") or die('<option value="">Failed to query for games: '.mysql_error().'</option>');
      
      while($row_sv = mysql_fetch_array($result_sv))
      {
          $sv_id        = $row_sv['id'];
          $sv_steam     = $row_sv['steam'];
          $sv_port      = $row_sv['port'];
          $sv_name      = $row_sv['name'];
          $sv_intname   = $row_sv['intname'];
          $sv_descr     = $row_sv['description'];
          $sv_cmd       = $row_sv['simplecmd'];
          
          echo '<option value="'.$sv_id.'" title="../images/gameicons/small/'.$sv_intname.'.png">'.$sv_name.'</option>';
      }
      ?>
      </select>
  </td>
</tr>

<tr>
  <td><b><?php echo $lang['network']; ?>:</b></td>
  <td>
    <select class="dropdown" id="create_network" style="width:350px;">
    <option value="" title="../images/icons/small/select_down_arrow.png"><?php echo $lang['ip']; ?></option>
    
    <?php
    // List available Network Servers
    $result_net = @mysql_query("SELECT DISTINCT 
                                  n.id,
                                  n.ip,
                                  n.parentid,
                                  n.location 
                                FROM network AS n 
                                ORDER BY 
                                  n.ip ASC") or die('Failed to query for network servers: '.mysql_error());
    
    while($row_net  = mysql_fetch_array($result_net))
    {
        $net_id       = $row_net['id'];
        $net_ip       = $row_net['ip'];
        $net_parentid = $row_net['parentid'];
        $net_loc      = $row_net['location'];
        
        if(!$net_parentid) $net_displ = $net_ip.' (' . $net_loc . ')';
        else $net_displ = $net_ip;
        
        echo '<option value="'.$net_id.'">'.$net_displ .'</option>';
    }
    ?>
    </select>
  </td>
</tr>
<tr>
  <td><b><?php echo $lang['port']; ?>:</b></td>
  <td><input type="text" value="" id="create_port" class="inputs" style="width:350px;" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['desc']; ?>:</b></td>
  <td><input type="text" value="<?php echo $tp_descr; ?>" id="create_desc" class="inputs" style="width:350px;" /></td>
</tr>

</table>

<div align="center">
  <div class="button" onClick="javascript:server_create();"><?php echo $lang['create_sv']; ?></div>
</div>


</div>
</div>
