<?php
require('checkallowed.php'); // Check logged-in
?>

<div class="page_title">
    <div class="page_title_icon"><img src="images/icons/medium/servers.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['servers']; ?></div>
</div>


<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['servers']; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="0" cellspacing="0" align="center" width="900" class="box_table" style="text-align:left;">
  <tr>
    <td width="300"><b><?php echo $lang['game']; ?></b></td>
    <td width="200"><b><?php echo $lang['network']; ?></b></td>
    <td width="300"><b><?php echo $lang['desc']; ?></b></td>
    <td width="200"><b><?php echo $lang['status']; ?></b></td>
    <td width="80"><b><?php echo $lang['manage']; ?></b></td>
  </tr>
<?php
// Game or voice or all
$url_type = $GPXIN['t'];
if($url_type == 'g') $sql_where = "AND s.type = 'game'";
elseif($url_type == 'v') $sql_where = "AND s.type = 'voice'";
else $sql_where = '';

// List servers
$result_srv = @mysql_query("SELECT 
                              s.id,
                              s.userid,
                              s.port,
                              s.status,
                              s.description,
                              d.intname,
                              d.name,
                              n.ip 
                            FROM servers AS s 
                            LEFT JOIN default_games AS d ON 
                              s.defid = d.id 
                            LEFT JOIN network AS n ON 
                              s.netid = n.id 
                            WHERE 
                              s.userid = '$gpx_userid' 
                            $sql_where 
                            ORDER BY 
                              s.id DESC,
                              n.ip ASC 
                            LIMIT 30") or die($lang['err_query'].' ('.mysql_error().')');

#while($row_srv  = mysql_fetch_array($result_srv))

$sql_arr  = array();
while($row_srv  = mysql_fetch_assoc($result_srv))
{
    $sql_arr[]  = $row_srv;
}
$row_srv  = '';
unset($row_srv);

#echo '<pre>';
#var_dump($sql_arr);
#echo '</pre>';

// Get server status info
require(DOCROOT.'/includes/classes/servers.php');
$Servers    = new Servers;
$sql_arr    = $Servers->getarr_gamequery($sql_arr);

// Loop through servers
foreach($sql_arr as $row_srv)
{
    $srv_id           = $row_srv['id'];
    $srv_userid       = $row_srv['userid'];
    $srv_ip           = $row_srv['ip'];
    $srv_port         = $row_srv['port'];
    $srv_status       = $row_srv['status'];
    $srv_description  = $row_srv['description'];
    $srv_def_name     = $row_srv['name'];
    $gameq_status     = $row_srv['current_status'];
    $gameq_numplayers = $row_srv['current_numplayers'];
    
    // Use correct status; if complete, show online/offline
    if($srv_status == 'complete')
    {
        // GameQ Server Statuses
        if($gameq_status == 'online') $srv_status = '<font color="green">'.$lang['online'].'</font>';
        elseif($gameq_status == 'offline') $srv_status = '<font color="red">'.$lang['offline'].'</font>';
        else $srv_status = '<font color="orange">'.$lang['unknown'].'!</font>';
        
        $onclick    = ' onClick="javascript:server_tab_info(' . $srv_id . ');"';
    }
    elseif($srv_status == 'installing')
    {
        $srv_status = '<font color="blue">'.$lang['installing'].' ...</font>';
        $onclick    = '';
    }
    elseif($srv_status == 'failed')
    {
        $srv_status = '<font color="red">'.$lang['failed'].'!</font>';
        $onclick    = '';
    }
    elseif($srv_status == 'none')
    {
        $srv_status = '<font color="orange">'.$lang['unknown'].'</font>';
        $onclick    = '';
    }
    
    echo '<tr id="srv_' . $srv_id . '" style="cursor:pointer;"'.$onclick.'>
            <td>' . $srv_def_name . '</td>
            <td>' . $srv_ip . ':' . $srv_port . '</td>
            <td>' . $srv_description . '</td>
            <td>' . $srv_status . '</td>
            <td class="links">'.$lang['manage'].'</td>
          </tr>';
}
?>
</table>

</div>
</div>
