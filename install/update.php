<?php
define('DOCROOT', '../');
require(DOCROOT.'/lang.php');
require('version.php');
session_start();

// Check install
if(!file_exists(DOCROOT.'/configuration.php')) die('No /configuration.php file found!  Check your installation before trying to update.');

require(DOCROOT.'/configuration.php');

// Setup db
@mysql_connect($settings['db_host'], $settings['db_username'], $settings['db_password']) or die('Failed to connect to the database!  Check your settings and try again.');
@mysql_select_db($settings['db_name']) or die('Failed to select the database!  Check your settings and try again.');

// Get current version
$result_cfg   = @mysql_query("SELECT config_value FROM configuration WHERE config_setting = 'version' ORDER BY last_updated DESC LIMIT 1");
$row_cfg      = mysql_fetch_row($result_cfg);
$cur_version  = $row_cfg[0];

// Function to update version number to new version
function update_gpxver($this_ver)
{
    $new_version  = GPX_VERSION;
    
    if(empty($new_version)) die('No new version found!  Check your "install/version.php" file.');
    @mysql_query("UPDATE configuration SET config_value = '$new_version' WHERE config_setting = 'version'") or die('Failed to update version: '.mysql_error());
    
    // Set new version to current
    if($this_ver) $cur_version  = $this_ver;
}

// No version? Start with 3.0.3 (no version was in DB prior to 3.0.5)
if(empty($cur_version))
{
    @mysql_query("INSERT INTO configuration (config_setting,config_value) VALUES('version','3.0.3')");
    $cur_version = '3.0.3';
}

// Already up to date
if(GPX_VERSION == $cur_version) die('You are already up to date (v'.$cur_version.')! <a href="../admin/">Back to Admin Area</a>');

################################################################################################################################################

//
// Incremental Updates - Update database from any previous version all the way to the latest
//


// 3.0.4
if($cur_version < '3.0.4')
{
    // Add `banned_chars` to default_games
    @mysql_query("ALTER TABLE default_games ADD `banned_chars` VARCHAR(64) NOT NULL AFTER pid_file") or die('Failed to add banned_chars: '.mysql_error());
    
    // Add default banned chars to cs series games
    @mysql_query("UPDATE default_games SET banned_chars = '+- ' WHERE intname IN('cs_16','cs_cz','cs_s','cs_go')") or die('Failed to update banned chars: '.mysql_error());
    
    update_gpxver('3.0.4');
}

// 3.0.5
if($cur_version < '3.0.5')
{
    update_gpxver('3.0.5');
}

// 3.0.6
if($cur_version < '3.0.6')
{
    // Minecraft updates
    @mysql_query("UPDATE default_games SET gameq_name = 'minecraft',cloudid = '6',simplecmd = 'java -Xincgc -Xmx1000M -jar craftbukkit.jar nogui' WHERE intname = 'mcraft'") or die('Failed to update Minecraft support: '.mysql_error());
    
    // Counter-Strike updates
    @mysql_query("UPDATE default_games SET update_cmd = './steam -command update -game cstrike -dir .' WHERE intname = 'cs_16'") or die('Failed to update CS 1.6 support: '.mysql_error());
    @mysql_query("UPDATE default_games SET update_cmd = './steam -command update -game czero -dir .' WHERE intname = 'cs_cz'") or die('Failed to update CS CZ support: '.mysql_error());
    @mysql_query("UPDATE default_games SET update_cmd = './steam -command update -game \'Counter-Strike Source\' -dir .' WHERE intname = 'cs_s'") or die('Failed to update CS S support: '.mysql_error());
    @mysql_query("UPDATE default_games SET cloudid = '7' WHERE intname = 'cs_go'") or die('Failed to update CS GO support: '.mysql_error());
    
    // Add language support
    @mysql_query("ALTER TABLE admins ADD `language` VARCHAR(64) NOT NULL DEFAULT 'english' AFTER `password`") or die('Failed to add admin language: '.mysql_error());
    @mysql_query("ALTER TABLE users ADD `language` VARCHAR(64) NOT NULL DEFAULT 'english' AFTER `last_updated`") or die('Failed to add user language: '.mysql_error());
    
    update_gpxver('3.0.6');
}

// 3.0.7
if($cur_version < '3.0.7')
{
    // Add theme support
    @mysql_query("ALTER TABLE admins ADD `theme` VARCHAR(64) NOT NULL DEFAULT 'default' AFTER `password`") or die('Failed to add admin theme: '.mysql_error());
    @mysql_query("ALTER TABLE users ADD `theme` VARCHAR(64) NOT NULL DEFAULT 'default' AFTER `last_updated`") or die('Failed to add user theme: '.mysql_error());
    
    update_gpxver('3.0.7');
}



// Completed
echo '<b>Success!</b> Update completed successfully.  Now delete or rename your "/install" directory, then <a href="../admin/">back to Admin Area</a>';

?>
