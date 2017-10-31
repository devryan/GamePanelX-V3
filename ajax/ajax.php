<?php
//
// Ajax gateway page to process all user input before sending to actual PHP pages
//
// !! For all user input (POST/GET), use $GPXIN instead !!
//
error_reporting(E_ERROR);
session_start();

// All allowed ajax requests go here
$allowed_reqs = array('login_actions','main_default','main_servers','main_serveradd','main_settings','main_cloudgames','main_templates','main_network','main_networkadd','main_networkedit','main_networkips',
                      'main_users','main_games','main_gamesedit','main_gamesadd','main_viewuser','main_viewadmin','main_userperms','main_admins','settings_save','main_plugins','main_tickets',
                      'server_info','server_settings','server_files','server_startup','server_actions','server_create_form',
                      'cloud_gameinfo','cloud_gameinstall','cloud_actions',
                      'template_edit','template_actions','template_create_form','template_status',
                      'file_actions','file_load_dir',
                      'network_create_form','network_edit','network_actions',
                      'user_create_form','user_edit','user_actions',
                      'admin_actions','admin_create_form',
                      'games_startup','games_actions',
                      'plugin_actions','tickets_actions');

########################################################################

// Use proper request
if(isset($_GET['a'])) $this_request = $_GET['a'];
elseif(isset($_POST['a'])) $this_request = $_POST['a'];

if(!in_array($this_request, $allowed_reqs)) die('ERROR: Invalid ajax action "' . $this_request . '"!');

// Check logged-in
if($this_request != 'login_actions' && !isset($_SESSION['gpx_userid'])) die('You must be logged-in to do that!');

########################################################################

// Setup database and automatically do a real MySQL escape on all GET or POST input
if(!defined('DOCROOT')) require('../configuration.php');

// Check bad docroot
if(!file_exists(DOCROOT.'/ajax/ajax.php')) die('Ajax file not found!<br /><span style="color:red;">Check the "docroot" value in /configuration.php and try again.</span>');

// Connect to the database for ajax requests
require(DOCROOT.'/includes/classes/core.php');
$Core = new Core;
$Core->dbconnect();

// Setup plugins
include_once(DOCROOT.'/includes/classes/plugins.php');
$Plugins  = new Plugins;
$Plugins->setup_actions();
global $Plugins;

// Automatically escape all user input
$GPXIN = array();
if(isset($_GET['a']))  $GPXIN = $Core->escape_inputs($_GET,false);
if(isset($_POST['a'])) $GPXIN = $Core->escape_inputs($_POST,false);

########################################################################

// Setup Language
require(DOCROOT.'/lang.php');
global $lang;

// Set common vars
$gpx_userid = $_SESSION['gpx_userid'];
$url_id     = $GPXIN['id'];


// Set path for images etc
if(isset($_SESSION['gpx_admin'])) $relpath = '../';
else $relpath = '';

// Pages in web root whose actions start with 'main_*'
if(preg_match('/^main_/', $this_request))
{
    $login_type = $_SESSION['gpx_type'];
    $this_request = str_replace('main_','',$this_request);
    
    if($login_type == 'admin') require(DOCROOT.'/admin/'.$this_request.'.php');
    else require(DOCROOT.'/'.$this_request.'.php');
}
// All other pages in /ajax/
else
{
    require($this_request.'.php');
}

?>
