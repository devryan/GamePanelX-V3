<?php
require('checkallowed.php'); // Check logged-in
?>

<div class="page_title">
    <div class="page_title_icon"><img src="../images/icons/medium/network.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['network']; ?></div>
</div>


<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['network']; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="0" cellspacing="0" align="center" width="900" class="box_table" style="text-align:left;">
  <tr>
    <td width="150"><b><?php echo $lang['type']; ?></b></td>
    <td width="140"><b><?php echo $lang['ip']; ?></b></td>
    <td width="250"><b><?php echo $lang['os']; ?></b></td>
    <td width="200"><b><?php echo $lang['location']; ?></td>
    <td width="200"><b><?php echo $lang['datacenter']; ?></b></td>
    <td width="120">&nbsp;</td>
  </tr>
<?php
// List network servers
$result_net = @mysql_query("SELECT DISTINCT 
                                  n.id,
                                  n.is_local,
                                  n.ip,
                                  n.location,
                                  n.os,
                                  n.datacenter 
                                FROM network AS n 
                                WHERE 
                                  n.parentid = '0' 
                                ORDER BY 
                                  n.id DESC") or die('Failed to query for network servers: '.mysql_error());

while($row_net  = mysql_fetch_array($result_net))
{
    $net_id     = $row_net['id'];
    #$net_local  = $row_net['is_local'];
    $net_ip     = $row_net['ip'];
    $net_loc    = $row_net['location'];
    $net_os     = $row_net['os'];
    $net_dc     = $row_net['datacenter'];
    
    if($row_net['is_local'])  $net_type = $lang['local_server'];
    else $net_type = $lang['remote_server'];
    
    if($net_local) $net_local = $lang['yes'];
    else $net_local = $lang['no'];
    
    echo '<tr id="net_' . $net_id . '" style="cursor:pointer;" onClick="javascript:mainpage(\'networkedit\',' . $net_id . ');">
            <td>' . $net_type . '</td>
            <td>' . $net_ip . '</td>
            <td>' . $net_os . '</td>
            <td>' . $net_loc . '</td>
            <td>' . $net_dc . '</td>
            <td class="links"><span onClick="javascript:mainpage(\'networkedit\',' . $net_id . ');">'.$lang['manage'].'</span></td>
          </tr>';
}

?>
</table>

</div>
</div>
