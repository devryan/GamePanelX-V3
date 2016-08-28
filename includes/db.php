<?php
// Check install
if(!file_exists(DOCROOT.'/configuration.php')) die('No /configuration.php file found!  Check your installation before trying to update.');

require(DOCROOT . "configuration.php");

$connection = mysqli_connect($settings['db_host'], $settings['db_username'], $settings['db_password'], $settings['$db_name']) or die('Failed to connect to the database!  Check your settings and try again.');
?>