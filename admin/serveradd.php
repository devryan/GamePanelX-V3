<?php
require('checkallowed.php'); // Check logged-in
?>
<div class="page_title">
    <div class="page_title_icon"><img src="../images/icons/medium/servers.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['servers']; ?></div>
</div>

<script language="javascript">
$(document).ready(function(e) {
    try {
        $("body select").msDropDown();
    } catch(e) {
        alert(e.message);
    }
});
</script>

<div class="infobox" style="display:none;"></div>

<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['create_server']; ?></div>
<div class="box_content" id="box_servers_content">

<div class="infobox" id="create_info" style="display:none;"></div>

<table border="0" cellpadding="2" cellspacing="0" width="700" class="cfg_table" style="margin-top:20px;">
<tr>
  <td><b><?php echo $lang['network']; ?>:</b></td>
  <td>
    <select class="dropdown" id="create_network" style="width:350px;" onChange="javascript:server_create_gettpls();">
    <option value="" title="../images/icons/small/select_down_arrow.png"><?php echo $lang['ip']; ?></option>
    
    <?php
    // List available Network Servers
    $result_net = $GLOBALS['mysqli']->query("SELECT DISTINCT 
                                  n.id,
                                  n.ip,
                                  n.parentid,
                                  n.location,
				  p.location AS ploc 
                                FROM network AS n 
				LEFT JOIN network AS p ON 
				  n.parentid = p.id 
                                ORDER BY 
                                  n.ip ASC") or die('Failed to query for network servers: '.$GLOBALS['mysqli']->error);
    
    while($row_net  = $result_net->fetch_array())
    {
        $net_id       = $row_net['id'];
        $net_ip       = $row_net['ip'];
        $net_parentid = $row_net['parentid'];
        $net_loc      = $row_net['location'];
	$net_ploc     = $row_net['ploc'];
 	
	if(!empty($net_loc)) $net_loc = ' (' . $net_loc . ')';
	elseif(!empty($net_ploc)) $net_loc = ' (Parent: ' . $net_ploc . ')';
	else $net_loc = '';

        if(!$net_parentid) $net_displ = $net_ip.$net_loc;
        else $net_displ = $net_ip.$net_loc;
        
        echo '<option value="'.$net_id.'">'.$net_displ .'</option>';
    }
    ?>
    </select>
  </td>
</tr>

<tr>
  <td width="180"><b><?php echo $lang['server']; ?>:</b></td>
  <td id="tpl_area"><i>Select a Network Server first</i></td>
</tr>

<tr>
  <td><b><?php echo $lang['owner']; ?>:</b></td>
  <td>
    <select class="dropdown" id="create_owner" style="width:350px;">
    <option value="" title="../images/icons/small/select_down_arrow.png"><?php echo $lang['username']; ?></option>
    
    <?php
    // List user accounts
    $result_usr = $GLOBALS['mysqli']->query("SELECT 
                                  id,
                                  username,
                                  first_name,
                                  last_name 
                                FROM users 
                                WHERE 
                                  deleted = '0'") or die('Failed to query for users: '.$GLOBALS['mysqli']->error);
    
    while($row_usr  = $result_usr->fetch_array())
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
  <td colspan="2">&nbsp;</td>
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


</div></div>
