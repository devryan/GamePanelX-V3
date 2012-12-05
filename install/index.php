<?php
define('DOCROOT', '../');
require(DOCROOT.'/lang.php');
require('version.php');

// Kill old install data
session_start();
session_destroy();

// Check for current installs
if(file_exists(DOCROOT.'/configuration.php'))
{
    include(DOCROOT.'/configuration.php');
    
    // Check for working config
    if(isset($settings['db_host']) && isset($settings['docroot']))
    {
        // Redirect to update
        header('Location: update.php');
        exit(0);
    }
    
}
?>
<!DOCTYPE html>
<html>
<head>
<title>GamePanelX Installation</title>
<link rel="stylesheet" type="text/css" href="../themes/default/index.css" />
<script type="text/javascript" src="../scripts/jquery.min.js"></script>
<script type="text/javascript">var ajaxURL='../ajax/ajax.php';</script>
<script type="text/javascript" src="../scripts/gpxadmin.js"></script>
<script type="text/javascript" src="../scripts/internal/install.js"></script>
</head>

<body>

<div id="panel_top">
    <div id="panel_top_imgdiv"><img src="../images/logo.png" border="0" /></div>
    <div id="panel_top_txtdiv">Welcome to the installer</div>
</div>


<div align="center">

<div class="infobox" style="display:none;"></div>


<div class="box">
<div class="box_title" id="box_servers_title">Install</div>
<div class="box_content" id="box_servers_content">



<table border="0" cellpadding="2" cellspacing="0" width="600" class="cfg_table">
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
                  $opt_val  = str_replace('.php', '', $entry);
                  
                  // Default to english
                  if($opt_val == 'english') echo '<option value="english" selected>English</option>';
                  else echo '<option value="'.$opt_val.'">'.ucwords($opt_val).'</option>';
              }
          }
          
          closedir($handle);
      }
      ?>
    </select>
  </td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b>Database Host:</b></td>
  <td><input type="text" id="db_host" value="localhost" class="inputs" /></td>
</tr>
<tr>
  <td><b>Database Name:</b></td>
  <td><input type="text" id="db_name" value="" class="inputs" /></td>
</tr>
<tr>
  <td><b>Database Username:</b></td>
  <td><input type="text" id="db_user" value="" class="inputs" /></td>
</tr>
<tr>
  <td><b>Database Password:</b></td>
  <td><input type="password" id="db_pass" value="" class="inputs" /></td>
</tr>

<tr>
  <td colspan="2">&nbsp;</td>
</tr>

<tr>
  <td><b>Admin Username:</b></td>
  <td><input type="text" id="admin_user" value="admin" class="inputs" /></td>
</tr>
<tr>
  <td><b>Admin Email:</b></td>
  <td><input type="text" id="admin_email" value="" class="inputs" /></td>
</tr>
<tr>
  <td><b>Admin Password:</b></td>
  <td><input type="password" id="admin_pass" value="" class="inputs" /></td>
</tr>
<tr>
  <td><b>Admin Password</b> (confirm):</td>
  <td><input type="password" id="admin_pass2" value="" class="inputs" /></td>
</tr>
</table>

<div class="button" onClick="javascript:install_start();">Install</div>


</div>
</div>
</div>


</body>
</html>
