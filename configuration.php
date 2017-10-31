<?php
// Main GamePanelX Configuration File
$settings['db_host']      = 'localhost'; // No need to change this
$settings['db_name']      = 'gpxold'; // Your database name
$settings['db_username']  = 'root'; // Your database username
$settings['db_password']  = 'thorngrove'; // Your database password
$settings['docroot']      = '/var/www/html/gpxold/'; // Set to the full path to your GamePanelX installation e.g. /home/me/public_html/gpx/
$settings['enc_key']      = '0XHak37ur0YUKGzp1cnyzKi6cU0vDeX3gKL4o5Ud8X3qpHnsifdYk0e0d7sY123X'; // No need to change this
$settings['debug']        = false;

###################################

/* No need to edit these! */
if(!defined('DOCROOT'))
{
    define('DOCROOT', $settings['docroot']);
    define('GPXDEBUG', $settings['debug']);
}

date_default_timezone_set('US/Central');

if($settings['debug']) error_reporting(E_ALL);
else error_reporting(E_ERROR);

?>