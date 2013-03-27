CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `setpass_3010` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(255) NOT NULL,
  `theme` varchar(64) NOT NULL,
  `language` varchar(64) NOT NULL DEFAULT 'english',
  `email_address` varchar(255) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `configuration` (
  `last_updated_by` int(10) unsigned NOT NULL,
  `last_updated` datetime NOT NULL,
  `config_setting` varchar(64) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  KEY `config_setting` (`config_setting`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `default_games` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cloudid` int(10) unsigned NOT NULL,
  `port` smallint(5) unsigned NOT NULL,
  `maxplayers` smallint(4) unsigned NOT NULL,
  `startup` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `steam` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` enum('game','voice','other') NOT NULL DEFAULT 'game',
  `cfg_separator` varchar(1) NOT NULL,
  `gameq_name` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `intname` varchar(12) NOT NULL,
  `working_dir` varchar(64) NOT NULL,
  `pid_file` varchar(64) NOT NULL,
  `banned_chars` varchar(64) NOT NULL,
  `cfg_ip` varchar(64) NOT NULL,
  `cfg_port` varchar(64) NOT NULL,
  `cfg_maxplayers` varchar(64) NOT NULL,
  `cfg_map` varchar(64) NOT NULL,
  `cfg_hostname` varchar(64) NOT NULL,
  `cfg_rcon` varchar(64) NOT NULL,
  `cfg_password` varchar(64) NOT NULL,
  `map` varchar(255) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `config_file` varchar(255) NOT NULL,
  `steam_name` varchar(255) NOT NULL,
  `description` varchar(600) NOT NULL,
  `install_mirrors` varchar(600) NOT NULL,
  `install_cmd` varchar(600) NOT NULL,
  `update_cmd` varchar(600) NOT NULL,
  `simplecmd` varchar(600) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `intname` (`intname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `default_startup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `defid` int(10) unsigned NOT NULL,
  `sort_order` smallint(5) unsigned NOT NULL,
  `single` tinyint(1) unsigned NOT NULL,
  `usr_edit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cmd_item` varchar(128) NOT NULL,
  `cmd_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `defid` (`defid`),
  KEY `cmd_item` (`cmd_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `loadavg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `netid` int(10) unsigned NOT NULL,
  `free_mem` int(10) unsigned NOT NULL,
  `total_mem` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `load_avg` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `network` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(10) unsigned NOT NULL,
  `is_local` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `login_user` blob NOT NULL,
  `login_pass` blob NOT NULL,
  `login_port` blob NOT NULL,
  `ip` varchar(20) NOT NULL,
  `token` varchar(32) NOT NULL,
  `os` varchar(64) NOT NULL,
  `location` varchar(128) NOT NULL,
  `datacenter` varchar(128) NOT NULL,
  `homedir` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `plugins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `date_installed` datetime NOT NULL,
  `description` text NOT NULL,
  `intname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `resellers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `language` varchar(64) NOT NULL DEFAULT 'english',
  `username` varchar(16) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `servers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `netid` int(10) unsigned NOT NULL,
  `defid` int(10) unsigned NOT NULL,
  `port` smallint(5) unsigned NOT NULL,
  `maxplayers` smallint(4) unsigned NOT NULL,
  `startup` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `status` enum('none','installing','updating','failed','complete') NOT NULL DEFAULT 'none',
  `date_created` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `token` varchar(32) NOT NULL,
  `map` varchar(255) NOT NULL,
  `rcon` varchar(255) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `sv_password` varchar(255) NOT NULL,
  `working_dir` varchar(255) NOT NULL,
  `pid_file` varchar(255) NOT NULL,
  `update_cmd` varchar(600) NOT NULL,
  `simplecmd` varchar(600) NOT NULL,
  `description` varchar(600) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `servers_startup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `srvid` int(10) unsigned NOT NULL,
  `sort_order` smallint(5) unsigned NOT NULL,
  `single` tinyint(1) unsigned NOT NULL,
  `usr_edit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cmd_item` varchar(128) NOT NULL,
  `cmd_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cmd_item` (`cmd_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `netid` int(10) unsigned NOT NULL,
  `cfgid` int(10) unsigned NOT NULL,
  `steam_percent` tinyint(3) unsigned NOT NULL,
  `date_created` datetime NOT NULL,
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` enum('none','running','steam_running','failed','tpl_running','complete') NOT NULL DEFAULT 'none',
  `size` varchar(12) NOT NULL,
  `token` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `file_path` varchar(400) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_default` (`is_default`),
  KEY `cfgid` (`cfgid`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `perm_ftp` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `perm_files` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `perm_startup` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `perm_startup_see` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `perm_chpass` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `perm_updetails` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `date_created` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `sso_user` blob NOT NULL,
  `sso_pass` blob NOT NULL,
  `theme` varchar(64) NOT NULL,
  `language` varchar(64) NOT NULL DEFAULT 'english',
  `username` varchar(16) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `default_games` (`id`, `cloudid`, `port`, `maxplayers`, `startup`, `steam`, `type`, `cfg_separator`, `gameq_name`, `name`, `intname`, `working_dir`, `pid_file`, `banned_chars`, `cfg_ip`, `cfg_port`, `cfg_maxplayers`, `cfg_map`, `cfg_hostname`, `cfg_rcon`, `cfg_password`, `map`, `hostname`, `config_file`, `steam_name`, `description`, `install_mirrors`, `install_cmd`, `update_cmd`, `simplecmd`) VALUES
(1, 2, 27015, 24, 1, 1, 'game', ' ', 'source', 'Counter-Strike: 1.6', 'cs_16', '', '', '+- ', 'ip', 'port', 'maxplayers', 'map', 'hostname', 'rcon_password', 'sv_password', 'de_dust2', 'New GamePanelX Server', 'cstrike/cfg/server.cfg', 'cstrike', 'The original Counter-Strike', '', '', './steam -command update -game cstrike -dir .', './hlds_run -game cstrike +ip %IP% +port %PORT% +map de_dust2 +maxplayers 16'),
(2, 3, 27015, 24, 1, 1, 'game', ' ', 'source', 'Counter-Strike: Condition Zero', 'cs_cz', 'czero', '', '+- ', 'ip', 'port', 'maxplayers', 'map', 'hostname', 'rcon_password', 'sv_password', 'de_dust2_cz', 'New GamePanelX Server', 'czero/cstrike/cfg/server.cfg', 'czero', 'Update of CS:1.6 with bots and missions', '', '', './steam -command update -game czero -dir .', './hlds_run -game cstrike +ip %IP% +port %PORT% +map de_dust +maxplayers 16'),
(3, 7, 27015, 24, 1, 2, 'game', ' ', 'source', 'Counter-Strike: GO', 'cs_go', 'csgo', '', '+- ', 'ip', 'port', 'maxplayers', 'map', 'hostname', 'rcon_password', 'sv_password', 'de_dust2', 'New GamePanelX Server', 'cfg/server.cfg', '740', 'Counter-Strike: Global Offensive', '', '', 'export LD_LIBRARY_PATH=linux32/ && STEAMEXE=steamcmd ./steam.sh +runscript .gpxsteamupdate.txt', './srcds_run -game csgo -ip %IP% -port %PORT% +map de_dust2 +sv_pure 0 +mapgroup mg_bomb -tickrate 66 +maxplayers 12 -maxplayers_override 12 +net_public_adr %IP% +game_type 0 +game_mode 1 +sv_steamgroup_exclusive 1 -console -usercon '),
(4, 1, 27015, 24, 1, 1, 'game', ' ', 'source', 'Counter-Strike: Source', 'cs_s', 'css', '', '+- ', 'ip', 'port', 'maxplayers', 'map', 'hostname', 'rcon_password', 'sv_password', 'de_dust2', 'New GamePanelX Server', 'css/cstrike/cfg/server.cfg', 'Counter-Strike Source', 'Source version of Counter-Strike', '', '', './steam -command update -game ''Counter-Strike Source'' -dir .', './srcds_run -game cstrike -ip %IP% -port %PORT% +maxplayers 12 +map de_dust2 -tickrate 66 +mp_dynamicpricing 0'),
(5, 8, 27015, 24, 1, 1, 'game', ' ', 'tf2', 'Team Fortress 2', 'tf2', 'orangebox', '', '+- ', '', '', '', '', '', '', '', 'cp_badlands', 'New GamePanelX Server', '', 'tf', 'Free to play update of Team Fortress', '', '', './steam -command update -game tf -dir .', './srcds_run -game tf -ip %IP% -port %PORT% -autoupdate -maxplayers 24 +map cp_badlands'),
(6, 5, 16567, 32, 0, 0, 'game', ' ', 'bf2', 'Battlefield 2', 'bf2', '', '', '', 'sv.serverIP', 'sv.serverPort', 'sv.maxPlayers', 'mapList.append', 'sv.serverName', '', 'sv.password', 'strike_at_karkand', 'New GamePanelX Server', '', '', 'Next iteration in the Battlefield series', 'http://www.1337-server.net/bf2/serverfiles/bf2-linuxded-1.1.2965-797.0-installer.sh', 'gunzip bf2-linuxded-1.1.2963-795-installer.sh.gz\nchmod u+x bf2-linuxded-1.1.2963-795-installer.sh\necho accept | ./bf2-linuxded-1.1.2963-795-installer.sh', '', './start.sh'),
(7, 9, 7777, 8, 0, 0, 'game', ' ', 'samp', 'GTA: San Andreas MP', 'gta_samp', '', '', '', '', '', '', '', '', '', '', '', 'New GamePanelX Server', 'server.cfg', '', '', 'http://files.sa-mp.com/samp03asvr_R4.tar.gz', 'tar -zxvf samp03asvr_R4.tar.gz\nrm -f samp03asvr_R4.tar.gz\nmv samp03/* .\nrmdir samp03\nrand_pass=$(tr -dc "[:alpha:]" < /dev/urandom | head -c 8)\nsed -i s/rcon_password\\ changeme/rcon_password\\ \\$rand_pass/g server.cfg\nsed -i s/hostname\\ SA\\-MP\\ 0\\.3\\ Server/hostname\\ New\\ GamePanelX\\ Server/g server.cfg', '', './samp03svr'),
(8, 6, 25565, 8, 0, 0, 'game', '=', 'minecraft', 'Minecraft', 'mcraft', '', '', '', '', '', '', '', '', '', '', '', 'New GamePanelX Server', 'Server.Properties', '', 'CraftBukkit Minecraft Server', 'http://dl.bukkit.org/latest-rb/craftbukkit.jar', 'mv craftbukkit* craftbukkit.jar', '', 'java -Xincgc -Xmx1000M -jar craftbukkit.jar nogui'),
(9, 10, 64738, 16, 0, 0, 'voice', '=', '', 'Murmur', 'murmur', '', 'murmur.pid', '', 'host', 'port', 'users', '', 'welcometext', '', 'serverpassword', '', 'New GamePanelX Server', 'murmur.ini', '', 'Server for the open source Mumble client', 'http://gamepanelx.com/files/murmur-latest-x86.tar.bz2', 'tar -xvjf murmur-latest-x86.tar.bz2\nrm -f murmur-latest-x86.tar.bz2\nmv murmur-*/* .\nrmdir murmur-static*\nsed -i ''s/\\#pidfile\\=/pidfile\\=murmur\\.pid/g'' murmur.ini', '', './murmur.x86 -ini murmur.ini'),
(10, 4, 3784, 8, 0, 0, 'voice', '=', 'ventrilo', 'Ventrilo', 'vent', '', 'ventrilo_srv.pid', '', '', '', '', '', '', '', '', '', '', '', '', 'Voice Communication Server', '', '', '', './ventrilo_srv -d');

INSERT INTO `default_startup` (`defid`, `sort_order`, `single`, `usr_edit`, `cmd_item`, `cmd_value`) VALUES
(1, 0, 0, 0, './hlds_run', ''),
(1, 1, 0, 0, '-game', 'cstrike'),
(1, 2, 0, 0, '+ip', '%IP%'),
(1, 3, 0, 0, '+port', '%PORT%'),
(1, 4, 0, 0, '+maxplayers', '%MAXPLAYERS%'),
(1, 5, 0, 0, '+map', '%MAP%'),
(2, 0, 0, 0, './hlds_run', ''),
(2, 1, 0, 0, '-game', 'czero'),
(2, 2, 0, 0, '+ip', '%IP%'),
(2, 3, 0, 0, '+port', '%PORT%'),
(2, 4, 0, 0, '+maxplayers', '%MAXPLAYERS%'),
(2, 5, 0, 0, '+map', '%MAP%'),
(3, 0, 0, 0, './srcds_run', ''),
(3, 1, 0, 0, '-game', 'csgo'),
(3, 2, 0, 0, '-console', ''),
(3, 3, 0, 0, '-usercon', ''),
(3, 4, 0, 0, '-secure', ''),
(3, 5, 0, 0, '-maxplayers_override', '%MAXPLAYERS%'),
(3, 6, 0, 0, '+sv_pure', '0'),
(3, 7, 0, 0, '+hostport', '%PORT%'),
(3, 8, 0, 0, '+ip', '%IP%'),
(3, 9, 0, 0, '+net_public_adr', '%IP%'),
(3, 10, 0, 0, '+game_type', '0'),
(3, 11, 0, 0, '+game_mode', '0'),
(3, 12, 0, 0, '+mapgroup', 'mg_bomb'),
(3, 13, 0, 0, '+map', '%MAP%'),
(4, 0, 0, 0, './srcds_run', ''),
(4, 1, 0, 0, '-game', 'cstrike'),
(4, 2, 0, 0, '-ip', '%IP%'),
(4, 3, 0, 0, '-port', '%PORT%'),
(4, 4, 0, 0, '+maxplayers', '%MAXPLAYERS%'),
(4, 5, 0, 1, '+map', '%MAP%'),
(4, 6, 0, 0, '+sv_pure', '0'),
(4, 7, 0, 0, '-tickrate', '66'),
(5, 1, 1, 0, './srcds_run', ''),
(5, 2, 0, 0, '-game', 'tf'),
(5, 3, 0, 0, '-ip', '%IP%'),
(5, 4, 0, 0, '-port', '%PORT%'),
(5, 5, 0, 0, '+maxplayers', '%MAXPLAYERS%'),
(5, 6, 0, 0, '+map', '%MAP%');
