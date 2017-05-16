<?php
session_start();

// Check if this user is logged in
if(!isset($_SESSION['gpx_userid']))
{
    header('Location: login.php?try=1');
    exit(0);
}
$gpx_userid = $_SESSION['gpx_userid'];
// Normal Users - Check if this user owns this server
if(!isset($_SESSION['gpx_admin']) && isset($gpx_srvid))
{
    $result_owns  = $GLOBALS['mysqli']->query("SELECT id FROM servers WHERE id = '$gpx_srvid' AND (userid = '$gpx_userid') OR userid2 = '$gpx_userid' LIMIT 1") or die('Failed to check ownership');
    $row_owns     = $result_owns->fetch_row();
} 



if(!defined('DOCROOT')) require('configuration.php');

// Setup Plugins
#require(DOCROOT.'/includes/classes/plugins.php');
#$Plugins = new Plugins;
#$Plugins->setup_actions();

?>