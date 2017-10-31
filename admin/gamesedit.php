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

<script>
function gamesedit_showsect(area)
{
    $('.gedit_area').hide();
    $('#gedit_'+area).fadeIn('fast');
    
    //$('.tab_games_edit').removeAttr('style').removeClass().addClass('tab_games_edit');
    //$('#subtab_'+area).removeClass().addClass('tab_games_edit_sel');
}
</script>

<div id="tab_games_edit_enc">
    <div class="tab_games_edit" id="subtab_general" onClick="javascript:gamesedit_showsect('general');">General</div>
    <div class="tab_games_edit" id="subtab_auto" onClick="javascript:gamesedit_showsect('auto');">Automation</div>
    <div class="tab_games_edit" id="subtab_config" onClick="javascript:gamesedit_showsect('config');">Config Items</div>
    <div class="tab_games_edit" id="subtab_misc" onClick="javascript:gamesedit_showsect('misc');">Misc</div>
</div>



<?php
// Get game info
$result = $GLOBALS['mysqli']->query("SELECT * 
                        FROM default_games
                        WHERE 
                          id = '$url_id' 
                        LIMIT 1") or die('Failed to query for games');

while($row  = $result->fetch_array())
{
    $def_cfg_sep      = $row['cfg_separator'];
    $def_cfg_ip       = $row['cfg_ip'];
    $def_cfg_port     = $row['cfg_port'];
    $def_cfg_maxpl    = $row['cfg_maxplayers'];
    $def_cfg_map      = $row['cfg_map'];
    $def_cfg_hostn    = $row['cfg_hostname'];
    $def_cfg_rcon     = $row['cfg_rcon'];
    $def_cfg_passw    = $row['cfg_password'];
    
    $def_cloudid      = $row['cloudid'];
    $def_port         = $row['port'];
    $def_startup      = $row['startup'];
    $def_steam        = $row['steam'];
    $def_steamcmd     = $row['steamcmd'];
    $def_gameq        = $row['gameq_name'];
    $def_name         = $row['name'];
    $def_steam_name   = $row['steam_name'];
    $def_intname      = $row['intname'];
    $def_working_dir  = $row['working_dir'];
    $def_pid_file     = $row['pid_file'];
    $def_config_file  = $row['config_file'];
    $def_descr        = $row['description'];
    $def_inst_mirrors = $row['install_mirrors'];
    $def_installcmd   = $row['install_cmd'];
    $def_updatecmd    = $row['update_cmd'];
    $def_simplecmd    = $row['simplecmd'];
    $def_bannedchars  = $row['banned_chars'];
    $def_map          = $row['map'];
    $def_maxpl        = $row['maxplayers'];
    $def_hostname     = $row['hostname'];
}
?>

<div id="gedit_general" class="gedit_area">
    <div class="box" style="width:750px;">
    <div class="box_title" id="box_servers_title"><?php echo $lang['edit']; ?></div>
    <div class="box_content" id="box_servers_content">
    
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
      <td colspan="2">&nbsp;</td>
    </tr>
    
    <tr>
      <td><b><?php echo $lang['port']; ?>:</b></td>
      <td><input type="text" id="port" value="<?php echo $def_port; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    <tr>
      <td><b><?php echo $lang['map']; ?>:</b></td>
      <td><input type="text" id="def_map" value="<?php echo $def_map; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    <tr>
      <td><b><?php echo $lang['maxplayers']; ?>:</b></td>
      <td><input type="text" id="def_maxplayers" value="<?php echo $def_maxpl; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    <tr>
      <td><b><?php echo $lang['hostname']; ?>:</b></td>
      <td><input type="text" id="def_hostname" value="<?php echo $def_hostname; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    
    
    <tr>
      <td width="250"><b><?php echo $lang['startup']; ?>:</b></td>
      <td>
          <select id="startup" class="dropdown" style="width:430px;">
            <option value="0"<?php if($def_startup == '0') echo ' selected'; ?>><?php echo $lang['no']; ?></option>
            <option value="1"<?php if($def_startup == '1') echo ' selected'; ?>><?php echo $lang['yes']; ?></option>
          </select>
      </td>
    </tr>
    <tr>
      <td><b><?php echo $lang['query_engine']; ?>:</b></td>
      <td>
          <?php
          // Setup GameQ V2 game list
          if(!file_exists(DOCROOT.'/includes/GameQ/GameQ.php')) die('Failed to find the GameQ files!  Is your DOCROOT correct in /configuration.php?');
          require(DOCROOT.'/includes/GameQ/GameQ.php');

          // Setup select menu
          echo '<select id="query_engine" class="dropdown" style="width:430px;">
                <option value="" selected>'.$lang['none'].'</option>';

$handle = opendir(GAMEQ_BASE."gameq/protocols/");

$gameslist = array();
while (false !== ($entry = readdir($handle))) {
	if(preg_match('/\.php$/', $entry)) {
		$game = str_replace('.php', '', $entry);
		$gameslist[] = $game;
	}
}
sort($gameslist);

foreach($gameslist as $game) {
	// Show option
	echo '<option value="'.$game.'"';
	if($def_gameq == $game) echo ' selected';
	echo '>'.$game.'</option>';
}
                        
/*
          $protocols_path = GAMEQ_BASE."gameq/protocols/";
          $dir = dir($protocols_path);
          $gameq_games  = array();

          // Now lets loop the directories
          while (false !== ($entry = $dir->read()))
          {
              if(!is_file($protocols_path.$entry))
              {
                continue;
              }

              // Figure out the class name
              $class_name = 'GameQ_Protocols_'.ucfirst(pathinfo($entry, PATHINFO_FILENAME));
              $reflection = new ReflectionClass($class_name);
              if(!$reflection->IsInstantiable()) continue;

              // Add gameq names to our array
              $class = new $class_name;
              $gameq_games[]  = $class->name();
          }

          // Close the directory
          unset($class, $dir);
          sort($gameq_games);

          foreach($gameq_games as $game)
          {
              // Show option
              echo '<option value="'.$game.'"';
              if($def_gameq == $game) echo ' selected';
              echo '>'.$game.'</option>';
          }
*/

          echo '</select>';





          /*
           * OLD!
           * 
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
          */
          ?>
      </td>
    </tr>

    <tr>
      <td><b><?php echo $lang['command']; ?>:</b></td>
      <td><textarea class="inputs" id="simplecmd" style="width:430px;height:70px;"><?php echo $def_simplecmd; ?></textarea></td>
    </tr>
    <tr>
      <td><b><?php echo $lang['update_cmd']; ?></b> (<?php echo $lang['optional']; ?>):</td>
      <td><textarea class="inputs" id="update_cmd" style="width:430px;height:70px;"><?php echo $def_updatecmd; ?></textarea></td>
    </tr>
    
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    
    <tr>
      <td><b><?php echo $lang['delete']; ?>:</b></td>
      <td><span class="links" onClick="javascript:game_confirm_del(<?php echo $url_id; ?>);"><?php echo $lang['click_here']; ?></span></td>
    </tr>
    </table>
    
    </div></div>
</div>


<div id="gedit_auto" style="display:none;" class="gedit_area">
    <div class="box" style="width:750px;">
    <div class="box_title" id="box_servers_title">Automation</div>
    <div class="box_content" id="box_servers_content">
    
    <table border="0" cellpadding="2" cellspacing="0" width="650" class="cfg_table">
    <tr>
      <td width="250"><b>Steam:</b></td>
      <td>
          <!-- 0 = No steam, 1 = original steam hldsupdatetool.bin method, 2 = newer SteamCMD method -->
          <select id="steam_based" class="dropdown" style="width:430px;">
            <option value="0"<?php if($def_steam == '0') echo ' selected'; ?>><?php echo $lang['no']; ?></option>
            <option value="1"<?php if($def_steam == '1') echo ' selected'; ?>>hldsupdatetool</option>
            <option value="2"<?php if($def_steam == '2') echo ' selected'; ?>>SteamCMD</option>
          </select>
      </td>
    </tr>
    <tr>
      <td><b>Steam <?php echo $lang['name']; ?>:</b> (<?php echo $lang['optional']; ?>)</td>
      <td><input type="text" id="steam_name" value="<?php echo $def_steam_name; ?>" class="inputs" style="width:430px;" /></td>
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
    </table>
    
    </div></div>
</div>

<div id="gedit_config" style="display:none;" class="gedit_area">
    <div class="box" style="width:750px;">
    <div class="box_title" id="box_servers_title">Config Items (<?php echo $lang['optional']; ?>)</div>
    <div class="box_content" id="box_servers_content">
    
    <table border="0" cellpadding="2" cellspacing="0" width="650" class="cfg_table">
    <tr>
      <td width="250"><b>Config Separator:</b></td>
      <td><input type="text" id="cfg_sep" value="<?php echo $def_cfg_sep; ?>" class="inputs" style="width:40px;" maxlength="1" /></td>
    </tr>
    <tr>
      <td width="250"><b><?php echo $lang['ip']; ?>:</b></td>
      <td><input type="text" id="cfg_ip" value="<?php echo $def_cfg_ip; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    <tr>
      <td><b><?php echo $lang['port']; ?>:</b></td>
      <td><input type="text" id="cfg_port" value="<?php echo $def_cfg_port; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    <tr>
      <td><b>Maxplayers:</b></td>
      <td><input type="text" id="cfg_maxplayers" value="<?php echo $def_cfg_maxpl; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    <tr>
      <td><b><?php echo $lang['map']; ?>:</b></td>
      <td><input type="text" id="cfg_map" value="<?php echo $def_cfg_map; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    <tr>
      <td><b>Hostname:</b></td>
      <td><input type="text" id="cfg_hostname" value="<?php echo $def_cfg_hostn; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    <tr>
      <td><b>Rcon:</b></td>
      <td><input type="text" id="cfg_rcon" value="<?php echo $def_cfg_rcon; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    <tr>
      <td><b><?php echo $lang['password']; ?>:</b></td>
      <td><input type="text" id="cfg_password" value="<?php echo $def_cfg_passw; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    </table>
    
    </div></div>
</div>

<div id="gedit_misc" style="display:none;" class="gedit_area">
    <div class="box" style="width:750px;">
    <div class="box_title" id="box_servers_title">Miscellaneous</div>
    <div class="box_content" id="box_servers_content">
    
    <table border="0" cellpadding="2" cellspacing="0" width="650" class="cfg_table">
    <tr>
      <td width="250"><b><?php echo $lang['working_dir']; ?></b> (<?php echo $lang['optional']; ?>):</td>
      <td><input type="text" id="working_dir" value="<?php echo $def_working_dir; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    <tr>
      <td><b><?php echo $lang['pid_file']; ?></b> (<?php echo $lang['optional']; ?>):</td>
      <td><input type="text" id="pid_file" value="<?php echo $def_pid_file; ?>" class="inputs" style="width:430px;" /></td>
    </tr>
    <tr>
      <td><b><?php echo $lang['config_file']; ?></b> (<?php echo $lang['optional']; ?>):</td>
      <td><input type="text" id="config_file" value="<?php echo $def_config_file; ?>" class="inputs" style="width:430px;" /></td>
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
      <td><b><?php echo $lang['cloud_games']; ?></b>:</td>
      <td>
      <?php
      // Allow submission to GPX Cloud Games if not already there
      if(!$def_cloudid) echo '<span class="links" onClick="javascript:game_submit_cloudgames_confirm(' . $url_id . ');">Submit this game to GamePanelx Cloud Games</span>';
      else echo '<i>Already in Cloud Games</i>';
      ?>
      </td>
    </tr>
    </table>
    
    </div></div>
</div>


<br />

<div class="button" onClick="javascript:game_save(<?php echo $url_id; ?>);"><?php echo $lang['save']; ?></div>

</div></div>
