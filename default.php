<?php
// Check logged-in
error_reporting(E_ERROR);
session_start();
if(!isset($_SESSION['gpx_userid'])) die('Please login');

// Setup database
require(DOCROOT.'/includes/classes/core.php');
$Core = new Core;
$Core->dbconnect();
?>

<div id="homeic_boxes_client" style="width:650px;">
    <div class="homeic_box_client" onClick="javascript:mainpage('servers','g');">
        <img src="images/icons/medium/servers.png" /><?php echo 'Servers - ALPHA' ?>
    </div>
    <div class="homeic_box_client" onClick="javascript:mainpage('servers','v');">
        <img src="images/icons/medium/servers.png" /><?php echo $lang['voice_servers']; ?>
    </div>
    <div class="homeic_box_client" onClick="javascript:mainpage('settings','');">
        <img src="images/icons/medium/edit.png" /><?php echo 'Settings' ?>
    </div>
    <div class="homeic_box_client" onClick="javascript:window.location='logout.php';">
        <img src="images/icons/medium/logout.png" /><?php echo $lang['logout']; ?>
    </div>
</div>
