<?php
// Callback page
// 
// Remote servers can send a GET request to this to update the master when something changes
// Before providing the callback to remote scripts, a token should be auto-generated and stored; the remote should send this back to compare for security
// 
error_reporting(E_ERROR);

if(!isset($_GET['do'])) die('CallBack: No action provided');

require('../configuration.php');
require('classes/core.php');
$Core = new Core;
$Core->dbconnect();

$url_do     = mysql_real_escape_string($_GET['do']);
$url_token  = mysql_real_escape_string($_GET['token']);
$url_id     = mysql_real_escape_string($_GET['id']);
$url_status = mysql_real_escape_string($_GET['status']);


//
// Template creation process now running
// Example: "callback.php?token=xx&do=tpl_status&id=1&status=complete"
//
if($url_do == 'tpl_status')
{
    // Get token
    $token_result = @mysql_query("SELECT token FROM templates WHERE id = '$url_id'");
    $token_row    = mysql_fetch_row($token_result);
    $token_tpl    = $token_row[0];
    
    // Make sure tokens match
    if($token_tpl != $url_token) die('CallBack: Invalid token provided!');
    
    // Update status
    if($url_status == 'complete') $status = 'complete';
    elseif($url_status == 'started') $status = 'running';
    elseif($url_status == 'failed') $status = 'failed';
    else $status = 'tpl_running';
    
    @mysql_query("UPDATE templates SET status = '$status' WHERE id = '$url_id'") or die('Failed to update Steam Percent!');
    
    echo 'success';
}


//
// Update steam progress percentage
// Example: "callback.php?token=xx&do=steam_progress&id=1&percent=50"
//
elseif($url_do == 'steam_progress')
{
    // Get token
    $token_result = @mysql_query("SELECT token FROM templates WHERE id = '$url_id'");
    $token_row    = mysql_fetch_row($token_result);
    $token_tpl    = $token_row[0];
    
    // Make sure tokens match
    if($token_tpl != $url_token) die('CallBack: Invalid token provided!');
    
    $url_percent  = mysql_real_escape_string($_GET['percent']);
    
    // Remove the % sign
    $url_percent  = str_replace('%', '', $url_percent);
    $url_percent  = round($url_percent);
    
    @mysql_query("UPDATE templates SET steam_percent = '$url_percent' WHERE id = '$url_id'") or die('Failed to update Steam Percent!');
    
    echo 'success';
}



//
// Create Server (tar extract) progress
//
elseif($url_do == 'createsrv_status')
{
    // Get token
    $token_result = @mysql_query("SELECT token FROM servers WHERE id = '$url_id'");
    $token_row    = mysql_fetch_row($token_result);
    $token_srv    = $token_row[0];
    
    // Make sure tokens match
    if($token_srv != $url_token) die('CallBack: Invalid token provided!');
    
    @mysql_query("UPDATE servers SET status = '$url_status' WHERE id = '$url_id'") or die('Failed to update Steam Percent!');
    
    echo 'success';
}


?>
