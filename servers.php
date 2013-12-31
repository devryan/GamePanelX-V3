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

<table border="0" cellpadding="0" cellspacing="0" align="center" width="900" id="srv_table" class="box_table" style="text-align:left;">
  <tr>
    <td width="25">&nbsp;</td>
    <td width="300"><b><?php echo $lang['game']; ?></b></td>
    <td width="240"><b><?php echo $lang['username']; ?></b></td>
    <td width="200"><b><?php echo $lang['network']; ?></b></td>
    <td width="260"><b><?php echo $lang['desc']; ?></b></td>
    <td width="150"><b><?php echo $lang['status']; ?></b></td>
    <td width="80"><b><?php echo $lang['manage']; ?></b></td>
  </tr>
  <?php
  // Game or voice or all
  $url_type = $GPXIN['t'];
  if($url_type == 'g') $sql_where = "AND d.type = 'game'";
  elseif($url_type == 'v') $sql_where = "AND d.type = 'voice'";
  else $sql_where = '';

  // Page number
  $pagenum = $GPXIN['pagenum'];
  $per_page = 15;
  if($pagenum) $sql_limit = $pagenum * $per_page . ',15';
  else $sql_limit = '0,15';

  // Get total servers
  $result_total  = @mysql_query("SELECT 
				     COUNT(*) AS cnt 
				 FROM servers AS s 
				 LEFT JOIN default_games AS d ON 
				     s.defid = d.id
				 WHERE 
				     s.userid = '$gpx_userid' 
				     $sql_where") or die('Failed to count servers: '.mysql_error().'!');

  $row_srv       = mysql_fetch_row($result_total);
  $total_servers = $row_srv[0];

  // List servers
  $result_srv = @mysql_query("SELECT 
                                s.id,
                                s.userid,
                                s.port,
                                s.status,
                                s.description,
                                d.intname,
                                d.gameq_name,
                                d.name,
                                n.ip,
                                u.username 
                              FROM servers AS s 
                              LEFT JOIN default_games AS d ON 
                                s.defid = d.id 
                              LEFT JOIN network AS n ON 
                                s.netid = n.id 
                              LEFT JOIN users AS u ON 
                                s.userid = u.id 
                              WHERE 
                                s.userid = '$gpx_userid' 
                                $sql_where 
                              ORDER BY 
                                s.id DESC,
                                n.ip ASC 
                              LIMIT $sql_limit") or die($lang['err_query'].' ('.mysql_error().')');
  
  $json_arr = array();
  $count_json = 0;
  
  while($row_srv  = mysql_fetch_array($result_srv))
  {
      $srv_id           = $row_srv['id'];
      $srv_userid       = $row_srv['userid'];
      $srv_ip           = $row_srv['ip'];
      $srv_port         = $row_srv['port'];
      $srv_status       = $row_srv['status'];
      $srv_description  = $row_srv['description'];
      $srv_def_name     = $row_srv['name'];
      $srv_def_intname  = $row_srv['intname'];
      $srv_gameq_name   = $row_srv['gameq_name'];
      $srv_username     = $row_srv['username'];
      
      // Add to JSON arry (only if complete)
      if($srv_status == 'complete')
      {
          if($srv_id)               $json_arr[$count_json]['id']    = $srv_id;
          if($srv_ip && $srv_port)  $json_arr[$count_json]['host']  = $srv_ip . ':' . $srv_port;
          if($srv_gameq_name)       $json_arr[$count_json]['type']  = $srv_gameq_name;
      }
      
      // Use correct status; if complete, show online/offline
      if($srv_status == 'installing')
      {
          $srv_status = '<font color="blue">'.$lang['installing'].' ...</font>';
      }
      elseif($srv_status == 'failed')
      {
          $srv_status = '<font color="red">'.$lang['failed'].'!</font>';
      }
      elseif($srv_status == 'none')
      {
          $srv_status = '<font color="orange">'.$lang['unknown'].'</font>';
      }
      
      echo '<tr id="srv_' . $srv_id . '" style="cursor:pointer;" onClick="javascript:server_tab_info(' . $srv_id . ');">
              <td><img src="images/gameicons/small/' . $srv_def_intname . '.png" width="20" height="20" border="0" /></td>
              <td>' . $srv_def_name . '</td>
              <td>' . $srv_username . '</td>
              <td>' . $srv_ip . ':' . $srv_port . '</td>
              <td style="font-size:10pt;">' . $srv_description . '</td>
              
              <td id="statustd_' . $srv_id . '">'.$srv_status;
              
              echo '</td>
              <td class="links">'.$lang['manage'].'</td>
            </tr>';
  
      $count_json++;
  }
  
  $json_str = json_encode($json_arr);
  ?>
  <tr id="srv_table_ld_tr">
    <td colspan="7" align="left" id="srv_table_ld_td">&nbsp;</td>
  </tr>
</table>

<?php
// Server Paging
if($total_servers > 15) {
        $total_pages = round($total_servers / 15);

        if($total_pages > 1) {
                echo '<span style="font-size:8pt;">Page: </span> ';

                for($i=0; $i <= $total_pages; $i++) {
                        if($pagenum == $i) echo '<span style="font-size:8pt;font-style:italic;">'.$i.' </span> ';
                        else echo '<span onClick="javascript:mainpage(\'servers\',\'\',\'&pagenum='.$i.'\');" class="links">'.$i.' </span> ';
                }
        }
}
else {
        echo '<span style="font-size:8pt;">Page: 0</span><br />';
}

echo '<span style="font-size:8pt;">'. $lang['servers'] . ': '.$total_servers.'</span><br /><br />';
?>

</div>
</div>


<script type="text/javascript">
$(document).ready(function(){
    setTimeout("multi_query()", 200);
});
</script>

<input type='hidden' id='json_hid' value='<?php echo $json_str; ?>' />
