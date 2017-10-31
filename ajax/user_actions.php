<?php
require('checkallowed.php'); // No direct access

error_reporting(E_ERROR);

// actions
/*
$url_id           = $GPXIN['id'];
$url_do           = $GPXIN['do']; // Action
$url_username     = strip_tags($GPXIN['username']);
$url_password     = $GPXIN['password'];
$url_email        = strip_tags($GPXIN['email']);
$url_first_name   = htmlspecialchars($GPXIN['fname']);
$url_last_name    = htmlspecialchars($GPXIN['lname']);
*/

$url_id           = $GPXIN['id'];
$url_do           = $GPXIN['do']; // Action
$url_username     = $GPXIN['username'];
$url_password     = $GPXIN['password'];
$url_email        = $GPXIN['email'];
$url_first_name   = $GPXIN['fname'];
$url_last_name    = $GPXIN['lname'];
$url_theme        = $GPXIN['theme'];
$url_language     = $GPXIN['language'];

#require(DOCROOT.'/checkallowed.php'); // Check login/ownership

require(DOCROOT.'/includes/classes/users.php');
$Users  = new Users;


// Create
if($url_do == 'create')
{
    if(!isset($_SESSION['gpx_admin'])) die('You are not authorized to do this!');
    
    $result_create  = $Users->create($url_username,$url_password,$url_email,$url_first_name,$url_last_name);
    
    // This outputs the userid created for the API mainly, so print success here
    if(is_numeric($result_create)) echo 'success';
    else echo $result_create;
}

// Save
elseif($url_do == 'save')
{
    if(isset($_SESSION['gpx_admin'])) $use_userid = $url_id;
    else $use_userid  = $gpx_userid;
    
    echo $Users->update($use_userid,$url_username,$url_password,$url_email,$url_first_name,$url_last_name,$url_language,$url_theme);
}

// Delete
elseif($url_do == 'delete')
{
    echo $Users->delete($url_id);
}

// Save Permissions
elseif($url_do == 'save_perms')
{
    $perm_ftp       = $GPXIN['ftp'];
    $perm_files     = $GPXIN['fm'];
    $perm_startup   = $GPXIN['str'];
    $perm_chpass    = $GPXIN['chpass'];
    $perm_updetails = $GPXIN['upd'];
    
    // Admins only
    if(isset($_SESSION['gpx_admin'])) $GLOBALS['mysqli']->query("UPDATE users SET perm_ftp = '$perm_ftp',perm_files = '$perm_files',perm_startup = '$perm_startup',perm_chpass = '$perm_chpass',perm_updetails = '$perm_updetails' WHERE id = '$url_id'") or die('Failed to update permissions!');
    else die('You are not authorized to do this!');
     
    echo 'success';
}

?>
