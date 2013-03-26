<?php
// GamePanelX V3 API
// Users
if(!defined('DOCROOT')) die('No direct access');
require(DOCROOT.'/includes/classes/users.php');
$Users = new Users;

$api_action       = $_GET['action'];
$api_relid        = $_GET['id'];
$usr_userid       = $_GET['userid'];
$usr_username     = $_GET['username'];
$usr_password     = $_GET['password'];
$usr_email        = $_GET['email'];
$usr_first_name   = $_GET['first_name'];
$usr_last_name    = $_GET['last_name'];
$usr_language     = $_GET['language'];
$usr_theme        = $_GET['theme'];

// Create user
if($api_action == 'create')
{
    // Returns a userid if successful
    $result_create  = $Users->create($usr_username,$usr_password,$usr_email,$usr_first_name,$usr_last_name);
    
    if(is_numeric($result_create)) echo 'success';
    else echo $result_create;
}

// Update user details
elseif($api_action == 'update')
{
    echo $Users->update($usr_userid,$usr_username,$usr_password,$usr_email,$usr_first_name,$usr_last_name,$usr_language,$usr_theme);
}

// Delete user
elseif($api_action == 'delete')
{
    echo $Users->delete($usr_userid);
}

// ?
else
{
    die('Unknown API action');
}

?>
