<?php
// Main GamePanelX Configuration File
$settings['db_host']      = 'localhost'; // No need to change this
$settings['db_name']      = 'gamepaneltest'; // Your database name
$settings['db_username']  = 'root'; // Your database username
$settings['db_password']  = 'flareservers'; // Your database password
$settings['docroot']      = '/var/www/control.flareservers.co.uk/Testpanel/'; // Set to the full path to your GamePanelX installation e.g. /home/me/public_html/gpx/
$settings['enc_key']      = 'zKy4EkiaNH1HzET4rTbgtNUrGodeJ97A8nyJcSQhkFDsyZzR9AGEVZ53HbTrYBO3'; // No need to change this
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