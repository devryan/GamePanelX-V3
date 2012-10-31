<?php
require('checkallowed.php'); // Check logged-in

// URL ID
$url_id   = $GPXIN['id'];

// Include config
# require(DOCROOT.'/configuration.php');

$enc_key  = $settings['enc_key'];
if(empty($url_id)) die('No ID provided');
if(empty($enc_key)) die($lang['no_enc_key']);

// List all IP's for this physical server
$result_net = @mysql_query("SELECT 
                              n.id,
                              n.ip 
                            FROM network AS n 
                            WHERE 
                              n.parentid = '$url_id' 
                            ORDER BY 
                              n.ip ASC") or die('Failed to query for IPs: '.mysql_error());

$count_ips  = mysql_num_rows($result_net);

// Tabs
$tab = 'ips';
include(DOCROOT.'/ajax/network_tabs.php');

echo $lang['net_showing_ips'].' #'.$url_id;

?>
<div class="infobox" style="display:none;"></div>

<div style="width:100%;margin-top:20px;margin-bottom:10px;">
    <span onClick="javascript:network_show_addip(<?php echo $url_id; ?>);" class="links"><img src="../images/icons/medium/add.png" border="0" width="28" height="28" /> <?php echo $lang['add_ip']; ?></span>
</div>

<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['network']; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="0" cellspacing="0" align="center" class="box_table" width="600" style="text-align:left;" id="netip_table">
  <tr>
    <td><b><?php echo $lang['ip']; ?></b></td>
    <td width="120">&nbsp;</td>
  </tr>

<?php
// No rows
if($count_ips == 0)
{
    echo '<tr id="noips_row">
            <td colspan="2">'.$lang['none'].'</td>
          </tr>';
}


while($row_net  = mysql_fetch_array($result_net))
{
    $net_id         = $row_net['id'];
    $net_ip         = $row_net['ip'];
    $net_parent_ip  = $row_net['parentip'];
    
    echo '<tr id="ip_'.$net_id.'">
            <td>'.$net_ip.'</td>
            <td><img src="../images/icons/medium/error.png" width="25" height="25" border="0" title="'.$lang['delete'].'" style="cursor:pointer;" onClick="javascript:network_confirm_delete_ip('.$net_id.');" /></td>
          </tr>';
}
?>
</table>

</div></div>
