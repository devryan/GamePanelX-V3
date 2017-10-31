<?php
$forceadmin = 1; // Admins only
require('checkallowed.php'); // No direct access
error_reporting(E_ERROR);

// Server actions
$url_id     = $GPXIN['id'];
$url_do     = $GPXIN['do'];

// Update settings on an installed plugin
if($url_do == 'update')
{
    $url_status = $GPXIN['status'];
    
    if(empty($url_id)) die('No ID given!');
    
    // Get intname
    $result_int = $GLOBALS['mysqli']->query("SELECT intname FROM plugins WHERE id = '$url_id'");
    $row_int    = $result_int->fetch_row();
    $intname    = $row_int[0];
    
    
    // Delete a plugin from db
    if($url_status == 'delete')
    {
        $GLOBALS['mysqli']->query("DELETE FROM plugins WHERE id = '$url_id'") or die('Failed to delete the plugin!');
    }
    // Set active
    elseif($url_status == 'active')
    {
        $GLOBALS['mysqli']->query("UPDATE plugins SET active = '1' WHERE id = '$url_id'") or die('Failed to activate the plugin!');
    }
    // Set inactive
    elseif($url_status == 'inactive')
    {
        $GLOBALS['mysqli']->query("UPDATE plugins SET active = '0' WHERE id = '$url_id'") or die('Failed to set the plugin to inactive!');
    }
    
    // Reset plugin session data
    #require(DOCROOT.'/includes/classes/plugins.php');
    #$Plugins  = new Plugins;
    $Plugins->reset_session();
    
    echo 'success';
}

// Install a plugin
elseif($url_do == 'install')
{
    $url_name = stripslashes($GPXIN['name']);
    
    // Get plugin information
    if(file_exists(DOCROOT.'/plugins/'.$url_name.'/plugin.json.txt'))
    {
        // Read JSON file
        $fh = fopen(DOCROOT.'/plugins/'.$url_name.'/plugin.json.txt', 'r') or die("Can't open file");
        $theData = fread($fh, 4096);
        fclose($fh);
        
        // Get plugin JSON info
        $json_info  = json_decode($theData, true);
        $newplg_name    = $json_info['name'];
        $newplg_intname = htmlspecialchars($json_info['intname']);
        $newplg_desc    = htmlspecialchars($json_info['description']);
    }
    else
    {
        $newplg_name    = $url_name;
        $newplg_intname = $url_name;
        $newplg_desc    = '';
    }
    
    // Insert plugin
    $GLOBALS['mysqli']->query("INSERT INTO plugins (active,date_installed,description,intname,name) VALUES('1',NOW(),'$newplg_desc','$newplg_intname','$newplg_name')") or die('Failed to insert the plugin');
    
    $_SESSION['gpx_plugins'][]  = $newplg_intname;
    
    echo 'success';
}

?>
