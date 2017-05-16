<?php
require('checkallowed.php'); // Check logged-in
?>

<div class="page_title">
    <div class="page_title_icon"><img src="../images/icons/medium/template.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['game_setups']; ?></div>
</div>

<?php $Plugins->do_action('games_top'); // Plugins ?>

<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['game_setups']; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="0" cellspacing="0" align="center" width="900" class="box_table" style="text-align:left;">
  <tr>
    <td width="25">&nbsp;</td>
    <td width="180"><b><?php echo $lang['name']; ?></b></td>
    <td width="200"><b><?php echo $lang['desc']; ?></b></td>
    <td width="50"><b><?php echo $lang['type']; ?></b></td>
    <td width="80"><b><?php echo $lang['templates']; ?></b></td>
    <td width="70">&nbsp;</td>
  </tr>
<?php
$Plugins->do_action('games_table'); // Plugins

// List supported games
$result_def = $GLOBALS['mysqli']->query("SELECT 
                                d.id,
                                d.steam,
                                d.name,
                                d.intname,
                                d.description,
                                d.install_mirrors,
                                d.install_cmd,
                                t.status 
                            FROM default_games AS d 
                            LEFT JOIN templates AS t ON 
                              d.id = t.cfgid 
                              AND (t.status = 'complete' AND t.is_default = '1') 
			    GROUP BY 
				d.id,
				d.intname 
                            ORDER BY 
                              t.is_default DESC,
                              d.name ASC") or die('Failed to query for games: '.$GLOBALS['mysqli']->error);

while($row_def  = $result_def->fetch_array())
{
    $def_gameid   = $row_def['id'];
    $def_steam    = $row_def['steam'];
    $def_inst_mir = $row_def['install_mirrors'];
    $def_inst_cmd = $row_def['install_cmd'];
    $def_name     = stripslashes($row_def['name']);
    $def_intname  = stripslashes($row_def['intname']);
    $tpl_status   = $row_def['status'];
    
    // Truncate long descriptions
    $def_descr  = stripslashes($row_def['description']);
    if(strlen($def_descr) > 50) $def_descr = substr($def_descr, 0, 50) . ' ...';
    
    // Steam / Automatic games
    if($def_steam) $type_img = '<img src="../images/icons/small/steam.png" width="16" height="16" border="0" title="Steam - Can be automatically installed" />';
    elseif(!empty($def_inst_mir) && !empty($def_inst_cmd)) $type_img = '<img src="../images/icons/small/automatic.png" width="16" height="16" border="0" title="Auto Installer - Can be automatically installed" />';
    else $type_img = '';
    
    // Available to be installed
    if($tpl_status) $tpl_show   = '<span style="color:green;font-weight:bold;">'.$lang['yes'].'</span>';
    else $tpl_show   = 'None';
    
    echo '<tr id="game_' . $def_gameid. '" style="cursor:pointer;" onClick="javascript:mainpage(\'gamesedit\',\''.$def_gameid.'\');">
            <td><img src="../images/gameicons/small/' . $def_intname . '.png" width="20" height="20" border="0" /></td>
            <td>' . $def_name . '</td>
            <td style="font-size:9pt;">' . $def_descr . '</td>
            <td>' . $type_img . '</td>
            <td>' . $tpl_show . '</td>
            <td class="links"><span onClick="javascript:mainpage(\'gamesedit\',\''.$def_gameid.'\');">'.$lang['manage'].'</span></td>
          </tr>';
}

?>
</table>

</div>
</div>

<span onClick="javascript:mainpage('gamesadd','');" class="links" style="display:block;"><img src="../images/icons/medium/add.png" border="0" width="28" height="28" /> <?php echo $lang['add']; ?></span>
<span class="links" onClick="javascript:template_show_create(<?php echo $url_id; ?>);" style="display:block;"><img src="../images/icons/medium/add.png" border="0" width="28" height="28" /> <?php echo $lang['create_tp']; ?></span>

<?php $Plugins->do_action('games_bottom'); // Plugins ?>
