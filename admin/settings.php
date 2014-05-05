<?php
require('checkallowed.php'); // Check logged-in
?>

<div class="page_title">
    <div class="page_title_icon"><img src="../images/icons/medium/edit.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['settings']; ?></div>
</div>

<div class="infobox" style="display:none;"></div>

<?php
// Get all control panel settings
if(!$Core)
{
    require('includes/classes/core.php');
    $Core = new Core;
}
$Core->dbconnect();
$settings = $Core->getsettings();

$cfg_email        = $settings['default_email_address'];
$cfg_lang         = $settings['language'];
$cfg_company      = $settings['company'];
$cfg_theme        = $settings['theme'];
$cfg_api_key      = $settings['api_key'];
$cfg_version      = $settings['version'];
$cfg_steam_user   = $settings['steam_login_user'];
$cfg_steam_pass   = $settings['steam_login_pass'];
$cfg_steam_auth   = $settings['steam_auth'];
$cfg_steam_user=substr($cfg_steam_user, 6);$cfg_steam_user=substr($cfg_steam_user, 0, -6);$cfg_steam_user=base64_decode($cfg_steam_user);
$cfg_steam_pass=substr($cfg_steam_pass, 6);$cfg_steam_pass=substr($cfg_steam_pass, 0, -6);$cfg_steam_pass=base64_decode($cfg_steam_pass);


$Plugins->do_action('settings_top'); // Plugins
?>

<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['settings']; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="2" cellspacing="0" width="600" class="cfg_table">
<tr>
  <td width="200"><b><?php echo $lang['version']; ?>:</b></td>
  <td><b><?php echo $cfg_version; ?></b></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b><?php echo $lang['default_language']; ?>:</b></td>
  <td>
    <select id="lang" class="dropdown">
      <?php
      // List everything in the 'languages/' dir
      if ($handle = opendir(DOCROOT.'/languages'))
      {
          while(false !== ($entry = readdir($handle)))
          {
              // Loop over PHP language files
              if(preg_match('/\.php$/i', $entry) && $entry != 'index.php')
              {
                  $cur_item = str_replace('.php', '', $entry);
                  
                  if($cur_item == $cfg_lang) echo '<option value="'.$cur_item.'" selected>'.ucwords($cur_item).'</option>';
                  elseif(empty($cfg_lang) && $cur_item == 'english')  echo '<option value="english" selected>English</option>';
                  else                      echo '<option value="'.$cur_item.'">'.ucwords($cur_item).'</option>';
                  
                  // Default to english
                  #if($opt_val == 'english') echo '<option value="english" selected>English</option>';
                  #else echo '<option value="'.$opt_val.'">'.ucwords($opt_val).'</option>';
              }
          }
          
          closedir($handle);
      }
      ?>
    </select>
  </td>
</tr>
<tr>
  <td><b><?php echo $lang['theme']; ?>:</b></td>
  <td>
    <select id="theme" class="dropdown">
      <?php
      // List everything in the 'themes/' dir
      if ($handle = opendir(DOCROOT.'/themes'))
      {
          while(false !== ($entry = readdir($handle)))
          {
              // Loop over themes
              if($entry != 'index.php' && !preg_match('/^\./', $entry) && !preg_match('/\.css$/i', $entry))
              {
                  if($cfg_theme == $entry) echo '<option value="'.$entry.'" selected>'.ucwords($entry).'</option>';
                  else echo '<option value="'.$entry.'">'.ucwords($entry).'</option>';
              }
          }
          
          closedir($handle);
      }
      ?>
    </select>
  </td>
</tr>

<tr>
  <td><b><?php echo $lang['email_address']; ?>:</b></td>
  <td><input type="text" id="email" value="<?php echo $cfg_email; ?>" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['company']; ?>:</b></td>
  <td><input type="text" id="company" value="<?php echo $cfg_company; ?>" class="inputs" /></td>
</tr>
<tr>
  <td><b><?php echo $lang['api_key']; ?>:</b></td>
  <td><input type="text" id="api_key" value="<?php echo $cfg_api_key; ?>" class="inputs" readonly /></td>
</tr>
<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b>Steam Login User:</b></td>
  <td><input type="text" id="steam_user" value="<?php echo $cfg_steam_user; ?>" class="inputs" /></td>
</tr>
<tr>
  <td><b>Steam Login Password:</b></td>
  <td><input type="password" id="steam_pass" value="<?php echo $cfg_steam_pass; ?>" class="inputs" /></td>
</tr>
<tr>
  <td><b>Steam Auth Code:</b></td>
  <td><input type="text" id="steam_auth" value="<?php echo $cfg_steam_auth; ?>" class="inputs" /></td>
</tr>

<?php $Plugins->do_action('settings_table'); // Plugins ?>
</table>

<div align="center">
    <div class="button" onClick="javascript:settings_save();"><?php echo $lang['save']; ?></div>
</div>

</div>
</div>
