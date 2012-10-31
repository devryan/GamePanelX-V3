<?php
require('checkallowed.php'); // No direct access

// Save Settings via settings page
$url_lang       = $GPXIN['lang'];
$url_email      = $GPXIN['email'];
$url_company    = $GPXIN['company'];
$url_theme      = $GPXIN['theme'];
$url_local_dir  = $GPXIN['local_dir'];

########################################################################

$errmsg = $lang['err_sql_update'] . ' ('.mysql_error().')';

// Run all updates
@mysql_query("UPDATE configuration SET config_value = '$url_lang' WHERE config_setting = 'language'") or die($errmsg);
@mysql_query("UPDATE configuration SET config_value = '$url_email' WHERE config_setting = 'default_email_address'") or die($errmsg);
@mysql_query("UPDATE configuration SET config_value = '$url_company' WHERE config_setting = 'company'") or die($errmsg);
@mysql_query("UPDATE configuration SET config_value = '$url_theme' WHERE config_setting = 'theme'") or die($errmsg);
@mysql_query("UPDATE configuration SET config_value = '$url_local_dir' WHERE config_setting = 'local_dir'") or die($errmsg);

echo 'success';

?>
