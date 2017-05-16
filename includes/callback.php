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

$url_do     = $GLOBALS['mysqli']->real_escape_string($_GET['do']);
$url_token  = $GLOBALS['mysqli']->real_escape_string($_GET['token']);
$url_id     = $GLOBALS['mysqli']->real_escape_string($_GET['id']);
$url_status = $GLOBALS['mysqli']->real_escape_string($_GET['status']);


//
// Template creation process now running
// Example: "callback.php?token=xx&do=tpl_status&id=1&status=complete"
//
if($url_do == 'tpl_status')
{
    // Get token
    $token_result = $GLOBALS['mysqli']->query("SELECT token FROM templates WHERE id = '$url_id'");
    $token_row    = $token_result->fetch_row();
    $token_tpl    = $token_row[0];
    
    // Make sure tokens match
    if($token_tpl != $url_token) die('CallBack: Invalid token provided!');
    
    // Update status
    if($url_status == 'complete') $status = 'complete';
    elseif($url_status == 'started') $status = 'running';
    elseif($url_status == 'failed') $status = 'failed';
    else $status = 'tpl_running';
    
    $url_size  = $GLOBALS['mysqli']->real_escape_string($_GET['size']);
    
    $GLOBALS['mysqli']->query("UPDATE templates SET status = '$status',size = '$url_size' WHERE id = '$url_id'") or die('Failed to update Steam Percent!');
    
    echo 'success';
}


//
// Update steam progress percentage
// Example: "callback.php?token=xx&do=steam_progress&id=1&percent=50"
//
elseif($url_do == 'steam_progress')
{
    // Get token
    $token_result = $GLOBALS['mysqli']->query("SELECT token FROM templates WHERE id = '$url_id'");
    $token_row    = $token_result->fetch_row();
    $token_tpl    = $token_row[0];
    
    // Make sure tokens match
    if($token_tpl != $url_token) die('CallBack: Invalid token provided!');
    
    $url_percent  = $GLOBALS['mysqli']->real_escape_string($_GET['percent']);
    
    // Remove the % sign
    $url_percent  = str_replace('%', '', $url_percent);
    $url_percent  = round($url_percent);
    
    $GLOBALS['mysqli']->query("UPDATE templates SET steam_percent = '$url_percent' WHERE id = '$url_id'") or die('Failed to update Steam Percent!');
    
    echo 'success';
}



//
// Create Server (tar extract) progress
//
elseif($url_do == 'createsrv_status')
{
    // Get token
    $token_result = $GLOBALS['mysqli']->query("SELECT token FROM servers WHERE id = '$url_id'");
    $token_row    = $token_result->fetch_row();
    $token_srv    = $token_row[0];
    
    // Make sure tokens match
    if($token_srv != $url_token) die('CallBack: Invalid token provided!');
    
    $GLOBALS['mysqli']->query("UPDATE servers SET status = '$url_status' WHERE id = '$url_id'") or die('Failed to update Steam Percent!');
    
    echo 'success';
}


//
// Remote Servers - report load info
//
elseif($url_do == 'remote_load')
{
    if(!preg_match('/[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/', $_GET['ip'])) die('Invalid IP Address given!');
    
    $url_ip         = $GLOBALS['mysqli']->real_escape_string($_GET['ip']);
    $url_freemem    = $GLOBALS['mysqli']->real_escape_string($_GET['freemem']);
    $url_totalmem   = $GLOBALS['mysqli']->real_escape_string($_GET['totalmem']);
    $url_loadavg    = $GLOBALS['mysqli']->real_escape_string($_GET['loadavg']);
    
    // Make sure this is a valid token
    $result_ck  = $GLOBALS['mysqli']->query("SELECT id FROM network WHERE token = '$url_token' LIMIT 1") or die('Failed to check valid server IP!');
    $row_ck     = $result_ck->fetch_row();
    $this_netid = $row_ck[0];
    
    if(empty($this_netid)) die('Sorry, do not recognize that token!');
    
    // Cleanup older than 3 days
    $GLOBALS['mysqli']->query("DELETE FROM loadavg WHERE `timestamp` < now() - interval 3 day");
    
    // Add to load avg table (will need to be cleaned periodically) (can be cleaned up here if needed)
    $GLOBALS['mysqli']->query("INSERT INTO loadavg (netid,free_mem,total_mem,load_avg) VALUES('$this_netid','$url_freemem','$url_totalmem','$url_loadavg')") or die('Failed to add load average!');
}

?>
