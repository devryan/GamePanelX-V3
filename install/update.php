<?php
define('DOCROOT', '../');
require(DOCROOT . "includes/db.php");
require(DOCROOT.'/lang.php');
require('version.php');
require(DOCROOT.'/includes/classes/core.php');
$Core = new Core;
session_start();

// Get current version
$result_cfg   = @mysqli_query($connection, "SELECT config_value FROM configuration WHERE config_setting = 'version' ORDER BY last_updated DESC LIMIT 1");
$row_cfg      = mysqli_fetch_row($result_cfg);
$cur_version  = $row_cfg[0];

// Function to update version number to new version
function update_gpxver($this_ver)
{
    $new_version  = GPX_VERSION;
    
    if(empty($new_version)) die('No new version found!  Check your "install/version.php" file.');
    @mysqli_query($this->connection, "UPDATE configuration SET config_value = '$new_version' WHERE config_setting = 'version'") or die('Failed to update version: '.mysqli_error());
    
    // Set new version to current
    if($this_ver) $cur_version  = $this_ver;
}

// No version? Start with 3.0.3 (no version was in DB prior to 3.0.5)
if(empty($cur_version))
{
    @mysqli_query($connection, "INSERT INTO configuration (config_setting,config_value) VALUES('version','3.0.3')");
    $cur_version = '3.0.3';
}

// Already up to date
if(GPX_VERSION == $cur_version) die('You are already up to date (v'.$cur_version.')! <a href="../admin/">Back to Admin Area</a>');

#echo "Updating version ($cur_version) to version (".GPX_VERSION.") ...<br />";

################################################################################################################################################
?>
<!DOCTYPE html>
<html>
<head>
<title>GamePanelX Update</title>
<link rel="stylesheet" type="text/css" href="../themes/default/index.css" />
</head>

<body>
<div align="center">

<div class="box">
<div class="box_title" id="box_servers_title">GamePanelX Update</div>
<div class="box_content" id="box_servers_content">

Welcome to the GamePanelX Update page!<br /><br />

<?php
if(!isset($_GET['go']))
{
?>
<div class="button" onClick="javascript:window.location='update.php?go=1';">Click to Update</div>
<?php
exit;
}

################################################################################################################################################
//
// Incremental Updates - Update database from any previous version all the way to the latest
//


// 3.0.4
#if($cur_version < '3.0.4')
if(version_compare($cur_version, '3.0.4') == -1)
{
	echo 'Updating to 3.0.4 ...<br />';
	
    // Add `banned_chars` to default_games
    @mysqli_query($connection, "ALTER TABLE default_games ADD `banned_chars` VARCHAR(64) NOT NULL AFTER pid_file") or die('Failed to add banned_chars: '.mysqli_error($connection));
    
    // Add default banned chars to cs series games
    @mysqli_query($connection, "UPDATE default_games SET banned_chars = '+- ' WHERE intname IN('cs_16','cs_cz','cs_s','cs_go')") or die('Failed to update banned chars: '.mysqli_error($connection));
    
    update_gpxver('3.0.4');
}

// 3.0.5
#if($cur_version < '3.0.5')
if(version_compare($cur_version, '3.0.5') == -1)
{
	echo 'Updating to 3.0.5 ...<br />';
	
    update_gpxver('3.0.5');
}

// 3.0.6
#if($cur_version < '3.0.6')
if(version_compare($cur_version, '3.0.6') == -1)
{
	echo 'Updating to 3.0.6 ...<br />';
	
    // Minecraft updates
    @mysqli_query($connection, "UPDATE default_games SET gameq_name = 'minecraft',cloudid = '6',simplecmd = 'java -Xincgc -Xmx1000M -jar craftbukkit.jar nogui' WHERE intname = 'mcraft'") or die('Failed to update Minecraft support: '.mysqli_error($connection));
    
    // Counter-Strike updates
    @mysqli_query($connection, "UPDATE default_games SET update_cmd = './steam -command update -game cstrike -dir .' WHERE intname = 'cs_16'") or die('Failed to update CS 1.6 support: '.mysqli_error($connection));
    @mysqli_query($connection, "UPDATE default_games SET update_cmd = './steam -command update -game czero -dir .' WHERE intname = 'cs_cz'") or die('Failed to update CS CZ support: '.mysqli_error($connection));
    @mysqli_query($connection, "UPDATE default_games SET update_cmd = './steam -command update -game \'Counter-Strike Source\' -dir .' WHERE intname = 'cs_s'") or die('Failed to update CS S support: '.mysqli_error($connection));
    @mysqli_query($connection, "UPDATE default_games SET cloudid = '7' WHERE intname = 'cs_go'") or die('Failed to update CS GO support: '.mysqli_error());
    
    // Add language support
    @mysqli_query($connection, "ALTER TABLE admins ADD `language` VARCHAR(64) NOT NULL DEFAULT 'english' AFTER `password`") or die('Failed to add admin language: '.mysqli_error($connection));
    @mysqli_query($connection, "ALTER TABLE users ADD `language` VARCHAR(64) NOT NULL DEFAULT 'english' AFTER `last_updated`") or die('Failed to add user language: '.mysqli_error($connection));
    
    update_gpxver('3.0.6');
}

// 3.0.8
#if($cur_version < '3.0.8')
if(version_compare($cur_version, '3.0.8') == -1)
{
	echo 'Updating to 3.0.8 ...<br />';
	
    // Add theme support
    @mysqli_query($connection, "ALTER TABLE admins ADD `theme` VARCHAR(64) NOT NULL DEFAULT 'default' AFTER `password`") or die('Failed to add admin theme: '.mysqli_error($connection));
    @mysqli_query($connection, "ALTER TABLE users ADD `theme` VARCHAR(64) NOT NULL DEFAULT 'default' AFTER `last_updated`") or die('Failed to add user theme: '.mysqli_error($connection));
    
    // Add new columns to `default_games`
    @mysqli_query($connection, "ALTER TABLE `default_games` 
                    ADD `maxplayers` SMALLINT(4) UNSIGNED NOT NULL AFTER `port`,
                    ADD `cfg_separator` VARCHAR(1) NOT NULL AFTER `steam`,
                    ADD `install_mirrors` VARCHAR(600) NOT NULL AFTER `description`,
                    ADD `install_cmd` VARCHAR(600) NOT NULL AFTER `install_mirrors`,
                    ADD `cfg_ip` VARCHAR(64) NOT NULL AFTER `banned_chars`,
                    ADD `cfg_port` VARCHAR(64) NOT NULL AFTER `cfg_ip`,
                    ADD `cfg_maxplayers` VARCHAR(64) NOT NULL AFTER `cfg_port`,
                    ADD `cfg_map` VARCHAR(64) NOT NULL AFTER `cfg_maxplayers`,
                    ADD `cfg_hostname` VARCHAR(64) NOT NULL AFTER `cfg_map`,
                    ADD `cfg_rcon` VARCHAR(64) NOT NULL AFTER `cfg_hostname`,
                    ADD `cfg_password` VARCHAR(64) NOT NULL AFTER `cfg_rcon`,
                    ADD `map` VARCHAR(255) NOT NULL AFTER `cfg_password`,
                    ADD `hostname` VARCHAR(255) NOT NULL AFTER `map`,
                    ADD `config_file` VARCHAR(255) NOT NULL AFTER `cfg_password`") or die('Failed to add default_games columns: '.mysqli_error($connection));
    
    // Add new columns to `servers`
    @mysqli_query($connection, "ALTER TABLE `servers` 
                    ADD `maxplayers` SMALLINT(4) UNSIGNED NOT NULL AFTER `port`,
                    ADD `map` VARCHAR(255) NOT NULL AFTER `token`,
                    ADD `rcon` VARCHAR(255) NOT NULL AFTER `map`,
                    ADD `hostname` VARCHAR(255) NOT NULL AFTER `rcon`,
                    ADD `sv_password` VARCHAR(255) NOT NULL AFTER `hostname`") or die('Failed to add default_games columns: '.mysqli_error($connection));
    
    // Add local config paths for all games (including working dir)
    @mysqli_query($connection, "UPDATE `default_games` SET `config_file` = CASE `intname` 
                      WHEN 'cs_16' THEN 'cstrike/cfg/server.cfg'
                      WHEN 'cs_cz' THEN 'cstrike/cfg/server.cfg'
                      WHEN 'cs_s' THEN 'cstrike/cfg/server.cfg'
                      WHEN 'cs_go' THEN 'cfg/server.cfg'
                      WHEN 'mcraft' THEN 'Server.Properties'
                      WHEN 'gta_samp' THEN 'server.cfg'
                      WHEN 'bf2' THEN 'mods/bf2/settings/serversettings.con'
                  ELSE `config_file` END") or die('Failed to update default games: '.mysqli_error($connection));
    
    ########
    
    // Update all cfg_* values
    @mysqli_query($connection, "UPDATE `default_games` SET `cfg_separator` = CASE `intname` 
                      WHEN 'cs_16' THEN ' '
                      WHEN 'cs_cz' THEN ' '
                      WHEN 'cs_s' THEN ' '
                      WHEN 'cs_go' THEN ' '
                      WHEN 'mcraft' THEN '='
                      WHEN 'samp' THEN ' '
                      WHEN 'bf2' THEN ' '
                      WHEN 'vent' THEN '='
                  ELSE `cfg_separator` END") or die('Failed to update separators: '.mysqli_error($connection));
    
    @mysqli_query($connection, "UPDATE `default_games` SET `cfg_ip` = CASE `intname` 
                      WHEN 'cs_16' THEN 'ip'
                      WHEN 'cs_cz' THEN 'ip'
                      WHEN 'cs_s' THEN 'ip'
                      WHEN 'cs_go' THEN 'ip'
                      WHEN 'mcraft' THEN 'server-ip'
                      WHEN 'bf2' THEN 'sv.serverIP'
                  ELSE `cfg_ip` END") or die('Failed to update ips: '.mysqli_error($connection));
    
    @mysqli_query($connection, "UPDATE `default_games` SET `cfg_port` = CASE `intname` 
                      WHEN 'cs_16' THEN 'port'
                      WHEN 'cs_cz' THEN 'port'
                      WHEN 'cs_s' THEN 'port'
                      WHEN 'cs_go' THEN 'port'
                      WHEN 'mcraft' THEN 'server-port'
                      WHEN 'gta_samp' THEN 'port'
                      WHEN 'bf2' THEN 'sv.serverPort'
                  ELSE `cfg_port` END") or die('Failed to update ports: '.mysqli_error($connection));
    
    @mysqli_query($connection, "UPDATE `default_games` SET `cfg_maxplayers` = CASE `intname` 
                      WHEN 'cs_16' THEN 'maxplayers'
                      WHEN 'cs_cz' THEN 'maxplayers'
                      WHEN 'cs_s' THEN 'maxplayers'
                      WHEN 'cs_go' THEN 'maxplayers_override'
                      WHEN 'mcraft' THEN 'max-players'
                      WHEN 'gta_samp' THEN 'maxplayers'
                      WHEN 'bf2' THEN 'sv.maxPlayers'
                      WHEN 'vent' THEN 'MaxClients'
                  ELSE `cfg_maxplayers` END") or die('Failed to update maxplayers: '.mysqli_error($connection));
    
    @mysqli_query($connection, "UPDATE `default_games` SET `cfg_map` = CASE `intname` 
                      WHEN 'cs_16' THEN 'map'
                      WHEN 'cs_cz' THEN 'map'
                      WHEN 'cs_s' THEN 'map'
                      WHEN 'cs_go' THEN 'map'
                      WHEN 'mcraft' THEN 'level-name'
                      WHEN 'gta_samp' THEN 'mapname'
                  ELSE `cfg_map` END") or die('Failed to update map: '.mysqli_error($connection));
    
    @mysqli_query($connection, "UPDATE `default_games` SET `cfg_hostname` = CASE `intname` 
                      WHEN 'cs_16' THEN 'hostname'
                      WHEN 'cs_cz' THEN 'hostname'
                      WHEN 'cs_s' THEN 'hostname'
                      WHEN 'cs_go' THEN 'hostname'
                      WHEN 'mcraft' THEN 'motd'
                      WHEN 'gta_samp' THEN 'hostname'
                      WHEN 'bf2' THEN 'sv.serverName'
                      WHEN 'vent' THEN 'Name'
                  ELSE `cfg_hostname` END") or die('Failed to update hostname: '.mysqli_error($connection));
    
    @mysqli_query($connection, "UPDATE `default_games` SET `cfg_rcon` = CASE `intname` 
                      WHEN 'cs_16' THEN 'rcon_password'
                      WHEN 'cs_cz' THEN 'rcon_password'
                      WHEN 'cs_s' THEN 'rcon_password'
                      WHEN 'cs_go' THEN 'rcon_password'
                      WHEN 'mcraft' THEN 'rcon.password'
                      WHEN 'gta_samp' THEN 'rcon_password'
                      WHEN 'vent' THEN 'AdminPassword'
                  ELSE `cfg_rcon` END") or die('Failed to update rcon: '.mysqli_error($connection));
    
    @mysqli_query($connection, "UPDATE `default_games` SET `cfg_password` = CASE `intname` 
                      WHEN 'cs_16' THEN 'sv_password'
                      WHEN 'cs_cz' THEN 'sv_password'
                      WHEN 'cs_s' THEN 'sv_password'
                      WHEN 'cs_go' THEN 'sv_password'
                      WHEN 'mcraft' THEN 'rcon.password'
                      WHEN 'gta_samp' THEN 'rcon_password'
                      WHEN 'vent' THEN 'AdminPassword'
                  ELSE `cfg_password` END") or die('Failed to update rcon: '.mysqli_error($connection));
    
    ########
    
    // Update install config for minecraft
    @mysqli_query($connection, "UPDATE `default_games` SET port = '25565',install_mirrors = 'http://dl.bukkit.org/latest-rb/craftbukkit.jar',install_cmd = 'mv craftbukkit* craftbukkit.jar' WHERE intname = 'mcraft'") or die('Failed to update minecraft: '.mysqli_error($connection));
    
    // Update CS:GO steam name from "csgo" to "740" to use steamcmd app ID
    @mysqli_query($connection, "UPDATE `default_games` SET steam = '2',steam_name = '740' WHERE intname = 'cs_go'") or die('Failed to update csgo: '.mysqli_error($connection));
    
    // Update `startup` to 0 for non-startup games
    @mysqli_query($connection, "UPDATE `default_games` SET `startup` = '0' WHERE intname IN ('vent','bf2','mcraft')") or die('Failed to update startup 0: '.mysqli_error($connection));
    
    // Add steam config items
    @mysqli_query($connection, "INSERT INTO configuration (config_setting,config_value) VALUES('steam_login_user',''),('steam_login_pass',''),('steam_auth','')") or die('Failed to update configuration: '.mysqli_error($connection));
    
    
    
    // Add SA:MP support
    @mysqli_query($connection, "INSERT INTO `default_games` (`id`, `cloudid`, `port`, `startup`, `steam`, `gameq_name`, `name`, `intname`, `working_dir`, `pid_file`, `banned_chars`, `steam_name`, `description`, `install_mirrors`, `install_cmd`, `update_cmd`, `simplecmd`) VALUES('', 9, 7777, 0, 0, 'mtasa', 'GTA: San Andreas MP', 'gta_samp', '', '', '', '', 'Grand Theft Auto: San Andreas - Multiplayer', 'http://files.sa-mp.com/samp03asvr_R4.tar.gz', 'tar -zxvf files.sa-mp.com/samp03asvr_R4.tar.gz; mv samp03/* .; rm -fr samp03 samp03asvr_R4.tar.gz', '', './samp03svr')");
    
    
    update_gpxver('3.0.8');
}

// 3.0.10
#if($cur_version < '3.0.10')
if(version_compare($cur_version, '3.0.10') == -1)
{
	echo 'Updating to 3.0.10 ...<br />';
	
    // Add "type" to `default_games`
    @mysqli_query($connection, "ALTER TABLE `default_games` ADD `type` ENUM('game','voice','other') DEFAULT 'game' NOT NULL AFTER `steam`") or die('Failed to update default games: '.mysqli_error($connection));
    
    // Drop 'type' from `servers` because, it should really be in `default_games` only
    @mysqli_query($connection, "ALTER TABLE `servers` DROP `type`") or die('Failed to update default games (2): '.mysqli_error($connection));
    
    // Set ventrilo as a voice server
    @mysqli_query($connection, "UPDATE `default_games` SET `type` = 'voice' WHERE intname = 'vent'") or die('Failed to update ventrilo: '.mysqli_error($connection));
    
    // Fix case on craftbukkit config
    @mysqli_query($connection, "UPDATE `default_games` SET `config_file` = 'server.properties' WHERE intname = 'mcraft'") or die('Failed to update minecraft: '.mysqli_error($connection));
    
    // Add basic Murmur/Mumble support
    @mysqli_query($connection, "INSERT INTO `default_games` (`id`, `cloudid`, `port`, `maxplayers`, `startup`, `steam`, `type`, `cfg_separator`, `gameq_name`, `name`, `intname`, `working_dir`, `pid_file`, `banned_chars`, `cfg_ip`, `cfg_port`, `cfg_maxplayers`, `cfg_map`, `cfg_hostname`, `cfg_rcon`, `cfg_password`, `map`, `hostname`, `config_file`, `steam_name`, `description`, `install_mirrors`, `install_cmd`, `update_cmd`, `simplecmd`) VALUES('', 10, 64738, 16, 0, 0, 'voice', '=', '', 'Murmur', 'murmur', '', 'murmur.pid', '', 'host', 'port', 'users', '', 'welcometext', '', 'serverpassword', '', 'New GamePanelX Server', 'murmur.ini', '', 'Server for the open source Mumble client', 'http://gamepanelx.com/files/murmur-latest-x86.tar.bz2', 'tar -xvjf murmur-latest-x86.tar.bz2; rm -f murmur-latest-x86.tar.bz2; mv murmur-*/* .; rmdir murmur-static*; sed -i ''s/\\#pidfile\\=/pidfile\\=murmur\\.pid/g'' murmur.ini', '', './murmur.x86 -ini murmur.ini')");
    
    // Add `sso_user` and `sso_pass` BLOB columns to `users` table
    @mysqli_query($connection, "ALTER TABLE users ADD sso_user BLOB NOT NULL AFTER last_updated,
    ADD sso_pass BLOB NOT NULL AFTER sso_user") or die('Failed to add sso columns: '.mysqli_error($connection));
    
    // Increase password, add `setpass_3010` so we can see if the new pass style was used
    @mysqli_query($connection, "ALTER TABLE admins MODIFY `password` VARCHAR(255) NOT NULL") or die('Failed to change admins table (1): '.mysqli_error($connection));
    @mysqli_query($connection, "ALTER TABLE admins ADD `setpass_3010` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `deleted`") or die('Failed to change admins table (2): '.mysqli_error($connection));
    
    // Add `loadavg` table
    @mysqli_query($connection, "CREATE TABLE IF NOT EXISTS `loadavg` (
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `netid` int unsigned NOT NULL,
                    `free_mem` int unsigned NOT NULL,
                    `total_mem` int unsigned NOT NULL,
                    `timestamp` TIMESTAMP NOT NULL,
                    `load_avg` varchar(6) NOT NULL,
                    PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8") or die('Failed to add loadavg table: '.mysqli_error($connection));
    
    // Add `token` to network tbl
    @mysqli_query($connection, "ALTER TABLE network ADD `token` VARCHAR(32) NOT NULL AFTER `ip`") or die('Failed to add token to network table: '.mysqli_error($connection));
    
    // Add `size` to templates tbl
    @mysqli_query($connection, "ALTER TABLE templates ADD `size` VARCHAR(12) NOT NULL AFTER `status`") or die('Failed to add size to templates table: '.mysqli_error($connection));
    
    /*
    // Get original admin user
    $result_origad = @mysql_query("SELECT id,username FROM admins WHERE deleted = '0' ORDER BY id ASC LIMIT 1");
    $row_origad	   = mysql_fetch_row($result_origad);
    $orig_ad_id	   = $row_origad[0];
    $orig_admin	   = $row_origad[1];
    if(empty($orig_admin)) die('No original admin account found!');
    
    // Generate new random password for this admin since we are updating to SSO
    $new_pass = $Core->genstring(12);
    $password = base64_encode(sha1('ZzaX'.$new_pass.'GPX88'));
    
    // Update admin user's password
    @mysql_query("UPDATE admins SET password = '$password' WHERE id = '$orig_ad_id'");
    
    echo '<br /><br /><font color="red"><b>WARNING!!</b> Password security has changed in this release!<br />Admin account "<b>'.$orig_admin.'</b>" password has been reset to: "<b>'.$new_pass.'</b>".  Please login as this admin and change your password(s) accordingly.</font><br /><br />';
    
    #file_put_contents(DOCROOT.'/$_SERVERS/.gpxtmp', "User: $orig_admin, Password: $new_pass");
    */
    
    
    update_gpxver('3.0.10');
}

// 3.0.11
if(version_compare($cur_version, '3.0.11') == -1)
{
	echo 'Updating to 3.0.11 ...<br />';
	
	// No db schema changes, just php bug fixes in this release
	update_gpxver('3.0.11');
}

// 3.0.12
if(version_compare($cur_version, '3.0.12') == -1)
{
        echo 'Updating to 3.0.12 ...<br />';

	// Move all Local Server ip:port user servers to ip.port structure
        if ($handle = opendir(DOCROOT.'/_SERVERS/accounts')) {
          while (false !== ($usrname = readdir($handle))) {
            if(!preg_match('/(^\.|.*\.php$)/', $usrname)) {
              # Loop user dirs
              if ($handle = opendir(DOCROOT."/_SERVERS/accounts/$usrname")) {
                while (false !== ($entry = readdir($handle))) {
                  # Loop over this user's servers
                  if(preg_match('/^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+\:[0-9]+/', $entry)) {
                    $fullpath = DOCROOT."/_SERVERS/accounts/$usrname/$entry";
                    $newpath  = str_replace(':','.', $fullpath);
        
                    # Move ip:port servers to ip.port
                    rename($fullpath,$newpath) or die("ERROR: During the ip:port move to ip.port, failed to move $fullpath to $newpath.  Check your filesystem permissions and try again (webserver user should own)");
                  }
                }
              }
            }
          }
        }

        update_gpxver('3.0.12');
}
?>
<br /><br />

<b>Success!</b> Update completed successfully.<br /><font color="red">Now delete or rename your "/install" directory before clicking below.</font>

<div class="button" onClick="javascript:window.location='../admin/';">Admin Area</div>

</div>
</div></div>
</body>
</html>
