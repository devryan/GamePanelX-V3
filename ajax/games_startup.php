<?php
$forceadmin = 1; // Admins only
require('checkallowed.php'); // No direct access
$url_id = $GPXIN['id'];
?>
<div class="page_title">
    <div class="page_title_icon"><img src="../images/icons/medium/startup.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['startup']; ?></div>
</div>

<?php
// Show Server Tabs
$tab = 'startup';
include('games_tabs.php');
?>

<div class="infobox" style="display:none;"></div>
<input type="hidden" id="sort_list" value="" />

<div id="startup_box" style="display:table;">

<style type="text/css">
#strtbl tbody
{
    cursor:move;
}
</style>

<?php
echo '<div id="startup_box" style="display:table;">

    <div class="box">
    <div class="box_title" id="box_servers_title">' . $lang['startup'] . '</div>
    <div class="box_content" id="box_servers_content">

    <table border="0" cellpadding="0" cellspacing="0" align="center" width="900" class="box_table" style="text-align:left;cursor:default;">
      <tr>
        <td width="200" align="left"><b>'.$lang['item'].'</b></td>
        <td width="200" align="left"><b>'.$lang['value'].'</b></td>
        <td width="70"><b>'.$lang['user_editable'].'</b></td>
        <td width="50">&nbsp;</td>
      </tr>
    </table>
    
    <table border="0" cellpadding="0" cellspacing="0" align="center" width="900" class="box_table" id="strtbl" style="text-align:left;">';

    // Get startup options
    $result_str = @mysql_query("SELECT 
                                  ds.id,
                                  ds.sort_order,
                                  ds.single,
                                  ds.usr_edit,
                                  ds.cmd_item,
                                  ds.cmd_value 
                                FROM default_startup AS ds 
                                WHERE 
                                  ds.defid = '$url_id' 
                                ORDER BY 
                                  ds.sort_order ASC 
                                LIMIT 999") or die('Failed to query for startup: '.mysql_error());

    while($row_str  = mysql_fetch_array($result_str))
    {
        $s_id           = $row_str['id'];
        $s_sort         = $row_str['sort_order'];
        $s_single_item  = $row_str['single'];
        $s_usr_edit     = $row_str['usr_edit'];
        $s_cmd_item     = stripslashes($row_str['cmd_item']);
        $s_cmd_value    = stripslashes($row_str['cmd_value']);
        
        if($s_usr_edit) $usred_chk  = ' checked';
        else $usred_chk  = '';
        
        echo '<tbody id="sortitm_' . $s_id . '" class="sortable">
              <tr>
                <td width="200"><div class="str_itm_ed"><input type="text" class="inputs" id="stritm_' . $s_id . '" value="' . $s_cmd_item . '" /></div></td>
                <td width="200"><div class="str_val_ed"><input type="text" class="inputs" id="strval_' . $s_id . '" value="' . $s_cmd_value . '" /></div></td>
                <td width="70" align="left" style="cursor:default;"><input type="checkbox" id="usred_' . $s_id . '" value="0" '.$usred_chk.'/></td>
                <td width="50" align="left" style="cursor:default;"><img src="'.$relpath.'images/icons/medium/error.png" width="25" height="25" border="0" title="'.$lang['delete'].'" style="cursor:pointer;" onClick="javascript:games_confirm_del_startup('.$s_id.','.$url_id.');" /></td>
              </tr>
              </tbody>';
    }
    ?>
    </table>

    </div>
    </div>
</div>

<span onClick="javascript:server_add_startup();" class="links"><img src="../images/icons/medium/add.png" border="0" width="28" height="28" /> <?php echo $lang['add']; ?></span>


<input type="hidden" id="newitemnum" value="0" />
<!-- <script type="text/javascript" src="../scripts/jquery-ui.min.js"></script> -->

<script type="text/javascript">
$(document).ready(function(){
  $("#strtbl").sortable({
      update: function(event, ui) {
        // Update list
        var listOrder = $(this).sortable('toArray').toString();
        
        // Store list
        $('#sort_list').val(listOrder);
			}
  });
  $("#strtbl").disableSelection();
});
</script>

<div class="button" onClick="javascript:games_save_startup(<?php echo $url_id; ?>);"><?php echo $lang['save']; ?></div>
