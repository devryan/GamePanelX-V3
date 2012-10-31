<?php
require('checkallowed.php'); // Check logged-in
?>

<div class="page_title">
    <div class="page_title_icon"><img src="../images/icons/medium/edit.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['settings']; ?></div>
</div>

<?php
$url_id = $GPXIN['id'];
$tab = 'settings';
include(DOCROOT.'/ajax/games_tabs.php');
?>

<div class="infobox" style="display:none;"></div>

<div class="box" style="width:750px;">
<div class="box_title" id="box_servers_title"><?php echo $lang['edit']; ?></div>
<div class="box_content" id="box_servers_content">

<?php
// Get game info
$result = @mysql_query("SELECT * 
                        FROM default_games
                        WHERE 
                          id = '$url_id'");

while($row  = mysql_fetch_array($result))
{
    $def_port         = $row['port'];
    $def_startup      = $row['startup'];
    $def_steam        = $row['steam'];
    $def_gameq        = $row['gameq_name'];
    $def_name         = $row['name'];
    $def_steam_name   = $row['steam_name'];
    $def_intname      = $row['intname'];
    $def_working_dir  = $row['working_dir'];
    $def_pid_file     = $row['pid_file'];
    $def_descr        = $row['description'];
    $def_inst_mirrors = $row['install_mirrors'];
    $def_installcmd   = $row['install_cmd'];
    $def_updatecmd    = $row['update_cmd'];
    $def_simplecmd    = $row['simplecmd'];
    $def_bannedchars  = $row['banned_chars'];
}
?>

<table border="0" cellpadding="2" cellspacing="0" width="650" class="cfg_table">
<tr>
  <td width="250"><b><?php echo $lang['name']; ?>:</b></td>
  <td><input type="text" id="name" value="<?php echo $def_name; ?>" class="inputs" style="width:430px;" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['int_name']; ?>:</b></td>
  <td><input type="text" id="intname" value="<?php echo $def_intname; ?>" class="inputs" style="width:430px;" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['desc']; ?>:</b></td>
  <td><input type="text" id="desc" value="<?php echo $def_descr; ?>" class="inputs" style="width:430px;" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['port']; ?>:</b></td>
  <td><input type="text" id="port" value="<?php echo $def_port; ?>" class="inputs" style="width:430px;" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['query_engine']; ?>:</b></td>
  <td>
      <?php
      // INI file parsing
      if(function_exists('parse_ini_file'))
      {
          $ini_arr  = parse_ini_file(DOCROOT.'/includes/gamequery/games.ini', true);
          
          echo '<select id="query_engine" class="dropdown" style="width:430px;">
                <option value="" selected>'.$lang['none'].'</option>';
          
          foreach($ini_arr as $game => $game_val)
          {
              // Show proper selected query engine
              echo '<option value="'.$game.'"';
              if($def_gameq == $game) echo ' selected';
              echo '>'.$game.'</option>';
          }
          
          echo '</select>';
      }
      // No INI parsing; let them enter it manually
      else
      {
          echo '<font color="red">You do not have INI support (parse_ini_file)!<br />  Please enter the engine manually:</font><br />
          <input type="text" id="query_engine" value="'.$def_gameq.'" class="inputs" />';
      }
      ?>
  </td>
</tr>

<tr>
  <td><b><?php echo $lang['command']; ?>:</b></td>
  <td><textarea class="inputs" id="simplecmd" style="width:430px;height:70px;"><?php echo $def_simplecmd; ?></textarea></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b>Steam:</b></td>
  <td>
      <select id="steam_based" class="dropdown" style="width:430px;">
        <option value="0"<?php if(!$def_steam) echo ' selected'; ?>><?php echo $lang['no']; ?></option>
        <option value="1"<?php if($def_steam) echo ' selected'; ?>><?php echo $lang['yes']; ?></option>
      </select>
  </td>
</tr>
<tr>
  <td width="300"><b>Steam <?php echo $lang['name']; ?>:</b> (<?php echo $lang['optional']; ?>)</td>
  <td><input type="text" id="steam_name" value="<?php echo $def_steam_name; ?>" class="inputs" style="width:430px;" /></td>
</tr>
<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['working_dir']; ?></b> (<?php echo $lang['optional']; ?>):</td>
  <td><input type="text" id="working_dir" value="<?php echo $def_working_dir; ?>" class="inputs" style="width:430px;" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['pid_file']; ?></b> (<?php echo $lang['optional']; ?>):</td>
  <td><input type="text" id="pid_file" value="<?php echo $def_pid_file; ?>" class="inputs" style="width:430px;" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['banned_start']; ?></b> (<?php echo $lang['optional']; ?>):</td>
  <td><input type="text" id="banned_chars" value="<?php echo $def_bannedchars; ?>" class="inputs" style="width:430px;" /></td>
</tr>
<tr>
  <td colspan="2"><?php echo $lang['banned_start_desc']; ?></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['install_mirrors']; ?></b> (<?php echo $lang['optional']; ?>):</td>
  <td><input type="text" id="install_mirrors" value="<?php echo $def_inst_mirrors; ?>" style="width:430px;" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['install']; ?> CMD</b> (<?php echo $lang['optional']; ?>):</td>
  <td><textarea class="inputs" id="install_cmd" style="width:430px;height:70px;"><?php echo $def_installcmd; ?></textarea></td>
</tr>
<tr>
  <td><b><?php echo $lang['update_cmd']; ?></b> (<?php echo $lang['optional']; ?>):</td>
  <td><textarea class="inputs" id="update_cmd" style="width:430px;height:70px;"><?php echo $def_updatecmd; ?></textarea></td>
</tr>

<tr>
  <td><b><?php echo $lang['delete']; ?>:</b></td>
  <td><span class="links" onClick="javascript:game_confirm_del(<?php echo $url_id; ?>);"><?php echo $lang['click_here']; ?></span></td>
</tr>
</table>

<br />

<div class="button" onClick="javascript:game_save(<?php echo $url_id; ?>);"><?php echo $lang['save']; ?></div>

</div></div>
