<?php
session_start();

// Initial install
if(!defined('DOCROOT'))
{
    if(file_exists('../configuration.php')) require('../configuration.php');
    else die('No /configuration.php file found!  Check your installation and try again.');
}

// Check if this user is logged in as an admin
if(!isset($_SESSION['gpx_userid']))
{
    header('Location: login.php?try=1');
    exit(0);
}

// Logged-in but not an admin
elseif(isset($_SESSION['gpx_userid']) && $_SESSION['gpx_type'] != 'admin')
{
    die('Sorry, you must be an administrator to view this page!');
}

// Setup Plugins
#require(DOCROOT.'/includes/classes/core.php');
#require(DOCROOT.'/includes/classes/plugins.php');
#$Core     = new Core;
#$Plugins  = new Plugins;

#$Core->dbconnect();
#$Plugins->setup_actions();

?>