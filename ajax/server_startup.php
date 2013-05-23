<?php
require('checkallowed.php'); // No direct access

// Server Startup Items
$url_id = $GPXIN['id'];
$gpx_srvid=$url_id; require(DOCROOT.'/checkallowed.php'); // Check login/ownership

// Don't use cmd-line builder.  Use simplecmd.
if($srvinfo[0]['startup'])
{
    $str_chk  = ' checked';
    $smp_chk  = '';
}
else
{
    $str_chk  = '';
    $smp_chk  = ' checked';
}

// Show Server Tabs
$tab = 'startup';
require('server_tabs.php');

echo '<div class="infobox" style="display:none;"></div>
<input type="hidden" id="sort_list" value="" />

<div id="startup_box" style="display:table;">';

// if($srvinfo[0]['startup'])

echo '
<style type="text/css">
#strtbl tbody
{
    cursor:move;
}
</style>

<div id="startup_box" style="display:table;">

    <div class="box">
    <div class="box_title" id="box_servers_title">' . $lang['startup'] . '</div>
    <div class="box_content" id="box_servers_content">

    <table border="0" cellpadding="0" cellspacing="0" align="center" width="900" class="box_table" style="text-align:left;cursor:default;">
      <tr>
        <td width="200" align="left"><b>'.$lang['item'].'</b></td>
        <td width="200" align="left"><b>'.$lang['value'].'</b></td>';
        
        // Only admins
        if(isset($_SESSION['gpx_admin']))
        {
            echo '<td width="70"><b>'.$lang['user_editable'].'</b></td>
                  <td width="50">&nbsp;</td>';
        }
        
        echo '
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
                                FROM servers_startup AS ds 
                                WHERE 
                                  ds.srvid = '$url_id' 
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
        
        // Users cant sort, use normal cursor
        if(!isset($_SESSION['gpx_admin'])) $usr_cursor = ' style="cursor:default;"';
        else $usr_cursor = '';
        
        // User editable only
        if(isset($_SESSION['gpx_admin']) || $s_usr_edit == '1')
        {
            if($s_usr_edit) $usred_chk  = ' checked';
            else $usred_chk  = '';
            
            echo '<tbody id="sortitm_' . $s_id . '" class="sortable"'.$usr_cursor.'>
                  <tr>
                    <td width="200">
                    <div class="str_itm_ed">';
                    
                    // Admins can edit, users cannot
                    if(isset($_SESSION['gpx_admin'])) echo '<input type="text" class="inputs" id="stritm_' . $s_id . '" value="' . $s_cmd_item . '" />';
                    else echo $s_cmd_item;
                    
                    echo '</div>
                    </td>
                    <td width="200"><div class="str_val_ed"><input type="text" class="inputs" id="strval_' . $s_id . '" value="' . $s_cmd_value . '" /></div></td>';
                    
                    // Only admins
                    if(isset($_SESSION['gpx_admin']))
                    {
                        echo '<td width="70" align="left" style="cursor:default;"><input type="checkbox" id="usred_' . $s_id . '" value="0" '.$usred_chk.'/></td>
                              <td width="50" align="left" style="cursor:default;"><img src="'.$relpath.'images/icons/medium/error.png" width="25" height="25" border="0" title="'.$lang['delete'].'" style="cursor:pointer;" onClick="javascript:server_confirm_del_startup('.$s_id.','.$url_id.');" /></td>';
                    }
                    
                    echo '
                  </tr>
                  </tbody>';
        }
    }
    ?>
    </table>

    </div>
    </div>
</div>

<?php
// Add - Admins only
if(isset($_SESSION['gpx_admin']))
{
    echo '<span onClick="javascript:server_add_startup();" class="links"><img src="../images/icons/medium/add.png" border="0" width="28" height="28" /> '.$lang['add'].'</span>';
    
    # <script type="text/javascript" src="../scripts/jquery-ui-1.8.22.custom.min.js"></script>
?>
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
  // WTF: $("#strtbl").disableSelection();
});
</script>
<?php } ?>

<div class="button" onClick="javascript:server_save_startup(<?php echo $url_id; ?>);"><?php echo $lang['save']; ?></div>
