<?php
require('checkallowed.php'); // Check logged-in
?>

<div class="page_title">
    <div class="page_title_icon"><img src="../images/icons/medium/plugins.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['plugins']; ?></div>
</div>

<div class="infobox" style="display:none;"></div>

<?php $Plugins->do_action('plugins_top'); // Plugins ?>

<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['plugins']; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="0" cellspacing="0" align="center" width="800" class="box_table" id="plugins_table" style="text-align:left;">
  <tr>
    <td width="25">&nbsp;</td>
    <td width="180"><b><?php echo $lang['name']; ?></b></td>
    <td width="200"><b><?php echo $lang['desc']; ?></b></td>
    <td width="100"><b><?php echo $lang['status']; ?></b></td>
  </tr>
<?php
$Plugins->do_action('plugins_table'); // Plugins

// List plugins
$result_def = $GLOBALS['mysqli']->query("SELECT 
                                id,
                                active,
                                description,
                                intname,
                                name
                            FROM plugins 
                            ORDER BY 
                                active DESC,
                                name ASC") or die('Failed to query for plugins: '.$GLOBALS['mysqli']->error);

// Array of known plugins
$known  = array();

while($row_def  = $result_def->fetch_array())
{
    $plg_id       = $row_def['id'];
    $plg_active   = $row_def['active'];
    $plg_descr    = htmlspecialchars($row_def['description']);
    $plg_intname  = htmlspecialchars($row_def['intname']);
    $plg_name     = htmlspecialchars($row_def['name']);
    $known[]      = $plg_intname; // Add to array of known plugins
    
    // Active
    if($plg_active)
    {
      $plg_active   = ' selected';
      $plg_inactive = '';
    }
    else
    {
      $plg_active   = '';
      $plg_inactive = ' selected';
    }
    
    // Icons
    if(file_exists('../plugins/'.$plg_intname.'/icon.png')) $plugin_img  = '<img src="../plugins/'.$plg_intname.'/icon.png" width="20" height="20" border="0" />';
    else $plugin_img = '&nbsp;';
    
    echo '<tr id="plugin_' . $plg_id . '">
            <td>'.$plugin_img.'</td>
            <td>' . $plg_name . '</td>
            <td style="font-size:9pt;">' . $plg_descr . '</td>
            <td>
              <select id="plg_'.$plg_id.'_status" class="dropdown" onChange="javascript:plugin_update_active('.$plg_id.');">
                <option value="active"'.$plg_active.'>'.$lang['active'].'</option>
                <option value="inactive"'.$plg_inactive.'>'.$lang['inactive'].'</option>
                <option value="delete">'.$lang['delete'].'</option>
              </select>';
            
            echo '</td>
          </tr>';
}

########################################################################

// Plugins in directory / Waiting plugins (plugins that are not yet installed)
if($handle = opendir(DOCROOT.'/plugins'))
{
    while (false !== ($entry = readdir($handle)))
    {
        // No . dirs, no plugins previously listed above
        if(!preg_match('/^\./', $entry) && !in_array($entry, $known) && !preg_match('/\.php$/', $entry))
        {
            // Icon
            if(file_exists('../plugins/'.$entry.'/icon.png')) $plugin_img  = '<img src="../plugins/'.$entry.'/icon.png" width="20" height="20" border="0" />';
            else $plugin_img = '&nbsp;';
            
            if(file_exists(DOCROOT.'/plugins/'.$entry.'/plugin.json.txt'))
            {
                // Read JSON file
                $fh = fopen(DOCROOT.'/plugins/'.$entry.'/plugin.json.txt', 'r') or die('Unable to open JSON plugin file ('.$entry.')');
                $theData = fread($fh, 8096);
                fclose($fh);
                
                // Get plugin JSON info
                $json_info  = json_decode($theData, true);
                $newplg_name    = $json_info['name'];
                $newplg_intname = htmlspecialchars($json_info['intname']);
                $newplg_desc    = htmlspecialchars($json_info['description']);
                
                #echo '<pre>';
                #var_dump($json_info);
                #echo '</pre>';
            }
            else
            {
                $newplg_name    = $entry;
                $newplg_intname = $entry;
                $newplg_desc    = $lang['unknown'];
            }
            
            // Show available plugin info
            echo '<tr>
                    <td>'.$plugin_img.'</td>
                    <td><b>' . $newplg_name . '</b></td>
                    <td style="font-size:9pt;"><b>' . $newplg_desc . '</b></td>
                    <td>'.$lang['not_installed'].' (<span class="links" onClick="javascript:plugin_install(\'' . $newplg_intname . '\');">'.$lang['install'].'</span>)</td>
                  </tr>';
        }
    }
    
    closedir($handle);
}
?>
</table>

</div>
</div>

<?php $Plugins->do_action('plugins_bottom'); // Plugins ?>
