<?php
require('checkallowed.php'); // Check logged-in
?>

<div class="page_title">
    <div class="page_title_icon"><img src="../images/icons/medium/template.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['server_templates']; ?></div>
</div>

<?php
$url_id = $GPXIN['id'];

$tab = 'templates';
include(DOCROOT.'/ajax/games_tabs.php');
?>

<div style="width:100%;height:32px;">
  <div style="width:32px;height:32px;float:left;margin-left:750px;"><img src="../images/icons/medium/add.png" border="0" width="32" height="32" style="cursor:pointer;" onClick="javascript:template_show_create();" /></div>
  <div style="width:120px;height:32px;line-height:32px;float:left;margin-left:10px;"><span class="links" onClick="javascript:template_show_create(<?php echo $url_id; ?>);"><?php echo $lang['create_tp']; ?></span></div>
</div>

<div class="infobox" style="display:none;"></div>

<script type="text/javascript">
$(document).ready(function(){
    // Clear last interval if needed
    if($('#lastrt').val()) clearInterval($('#lastrt').val());
    
    // Start checking for status updates every 5s (store current interval in #lastrt)
    var thisInt = setInterval("tpl_check_statuses()",5000);
    $('#lastrt').val(thisInt);
    
    // Check for completed templates
    template_checkdone();
});
</script>

<div id="results" style="font-size:8pt;margin-bottom:5px;margin-left:5px;"></div>



<div class="box">
<div class="box_title" style="margin-top:0px;" id="box_servers_title"><?php echo $lang['server_templates']; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="0" cellspacing="0" align="center" width="900" class="box_table" style="text-align:left;">
  <tr>
    <td width="300"><b><?php echo $lang['game']; ?></b></td>
    <td width="150"><b><?php echo $lang['date_added']; ?></b></td>
    <td width="120"><b><?php echo $lang['default']; ?></b></td>
    <td width="300"><b><?php echo $lang['status']; ?></b></td>
    <td width="300"><b><?php echo $lang['desc']; ?></b></td>
    <td width="80"><b><?php echo $lang['manage']; ?></b></td>
  </tr>
<?php
// List templates
$result_srv = @mysql_query("SELECT 
                              t.id,
                              DATE_FORMAT(t.date_created, '%m/%d/%Y') AS date_created,
                              t.is_default,
                              t.status,
                              t.description,
                              d.name,
                              n.ip 
                            FROM templates AS t 
                            LEFT JOIN network AS n ON 
                              t.netid = n.id 
                            LEFT JOIN default_games AS d ON 
                              t.cfgid = d.id 
                            WHERE 
                              t.cfgid = '$url_id' 
                            ORDER BY 
                              t.id DESC") or die($lang['err_query'].' ('.mysql_error().')');

while($row_srv  = mysql_fetch_array($result_srv))
{
    $tpl_id           = $row_srv['id'];
    $tpl_date         = $row_srv['date_created'];
    $tpl_default      = $row_srv['is_default'];
    $tpl_status       = $row_srv['status'];
    $tpl_description  = $row_srv['description'];
    $tpl_game         = $row_srv['name'];
    
    if($tpl_default) $tpl_default = $lang['yes'];
    else $tpl_default = $lang['no'];
    
    if($tpl_status == 'complete') $tpl_status = '<font color="green">'.$lang['complete'].'</font>';
    elseif($tpl_status == 'running') $tpl_status = '<font color="orange">'.$lang['installing'].'</font>';
    elseif($tpl_status == 'steam_running') $tpl_status = '<font color="orange">Steam Running</font>';
    elseif($tpl_status == 'failed') $tpl_status = '<font color="red">'.$lang['failed'].'</font>';
    else $tpl_status = '<font color="orange">'.$lang['unknown'].'</font>';
    
    echo '<tr id="tpl_' . $tpl_id . '" style="cursor:pointer;" onClick="javascript:template_edit(' . $tpl_id . ');">
            <td>' . $tpl_game . '</td>
            <td>' . $tpl_date . '</td>
            <td>' . $tpl_default . '</td>
            <td id="status_' . $tpl_id . '">' . $tpl_status . '</td>
            <td>' . $tpl_description . '</td>
            <td class="links">'.$lang['manage'].'</td>
          </tr>';
}

?>
</table>

<input type="hidden" id="tplid" value="<?php echo $url_id; ?>" />
</div>
</div>
