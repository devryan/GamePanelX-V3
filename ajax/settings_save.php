<?php
require('checkallowed.php'); // No direct access


// Save Settings via settings page
$url_lang         = $GPXIN['lang'];
$url_email        = $GPXIN['email'];
$url_company      = $GPXIN['company'];
$url_theme        = $GPXIN['theme'];
$url_local_dir    = $GPXIN['local_dir'];
$url_steam_user   = $GPXIN['steam_login_user'];
$url_steam_pass   = $GPXIN['steam_login_pass'];
$url_steam_auth   = $GPXIN['steam_auth'];

# (done via javascript)
#$Core = new Core;
#$url_steam_user   = $Core->genstring(6) . base64_encode($url_steam_user) . $Core->genstring(6);
#$url_steam_pass   = $Core->genstring(6) . base64_encode($url_steam_pass) . $Core->genstring(6);

$this_userid = $_SESSION['gpx_userid'];

########################################################################

// Update these settings
@mysql_query("UPDATE `configuration` SET 
                `last_updated_by` = '$this_userid',
                `last_updated` = NOW(),
                `config_value` = CASE `config_setting` 
                    WHEN 'language' THEN '$url_lang'
                    WHEN 'default_email_address' THEN '$url_email'
                    WHEN 'company' THEN '$url_company'
                    WHEN 'theme' THEN '$url_theme'
                    WHEN 'local_dir' THEN '$url_local_dir'
                    WHEN 'steam_login_user' THEN '$url_steam_user'
                    WHEN 'steam_login_pass' THEN '$url_steam_pass' 
                    WHEN 'steam_auth' THEN '$url_steam_auth' 
              ELSE `config_value` END") or die('Failed to update settings: '.mysql_error());


/*
 * Older, inefficient (updated in 3.0.7)
 * 
$errmsg = $lang['err_sql_update'] . ' ('.mysql_error().')';
// Run all updates
@mysql_query("UPDATE configuration SET config_value = '$url_lang' WHERE config_setting = 'language'") or die($errmsg);
@mysql_query("UPDATE configuration SET config_value = '$url_email' WHERE config_setting = 'default_email_address'") or die($errmsg);
@mysql_query("UPDATE configuration SET config_value = '$url_company' WHERE config_setting = 'company'") or die($errmsg);
@mysql_query("UPDATE configuration SET config_value = '$url_theme' WHERE config_setting = 'theme'") or die($errmsg);
@mysql_query("UPDATE configuration SET config_value = '$url_local_dir' WHERE config_setting = 'local_dir'") or die($errmsg);
*/

echo 'success';

?>
