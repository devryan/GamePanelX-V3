CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(64) NOT NULL,
  `language` varchar(64) NOT NULL DEFAULT 'english',
  `email_address` varchar(255) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `startup` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `steam` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gameq_name` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `intname` varchar(12) NOT NULL,
  `working_dir` varchar(64) NOT NULL,
  `pid_file` varchar(64) NOT NULL,
  `banned_chars` varchar(64) NOT NULL,
  `steam_name` varchar(255) NOT NULL,
  `description` varchar(600) NOT NULL,
  `update_cmd` varchar(600) NOT NULL,
  `simplecmd` varchar(600) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `intname` (`intname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `network` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(10) unsigned NOT NULL,
  `is_local` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `login_user` blob NOT NULL,
  `login_pass` blob NOT NULL,
  `login_port` blob NOT NULL,
  `ip` varchar(20) NOT NULL,
  `os` varchar(64) NOT NULL,
  `location` varchar(128) NOT NULL,
  `datacenter` varchar(128) NOT NULL,
  `homedir` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `startup` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` enum('game','voice') NOT NULL DEFAULT 'game',
  `status` enum('none','installing','updating','failed','complete') NOT NULL DEFAULT 'none',
  `date_created` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `token` varchar(32) NOT NULL,
  `working_dir` varchar(255) NOT NULL,
  `pid_file` varchar(255) NOT NULL,
  `update_cmd` varchar(600) NOT NULL,
  `simplecmd` varchar(600) NOT NULL,
  `description` varchar(600) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `netid` int(10) unsigned NOT NULL,
  `cfgid` int(10) unsigned NOT NULL,
  `steam_percent` tinyint(3) unsigned NOT NULL,
  `date_created` datetime NOT NULL,
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` enum('none','running','steam_running','failed','tpl_running','complete') NOT NULL DEFAULT 'none',
  `token` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `file_path` varchar(400) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_default` (`is_default`),
  KEY `cfgid` (`cfgid`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `language` varchar(64) NOT NULL DEFAULT 'english',
  `username` varchar(16) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `default_games` (`id`, `cloudid`, `port`, `startup`, `steam`, `gameq_name`, `name`, `intname`, `working_dir`, `pid_file`, `banned_chars`, `steam_name`, `description`, `update_cmd`, `simplecmd`) VALUES
('', 2, 27015, 1, 1, 'source', 'Counter-Strike: 1.6', 'cs_16', '', '', '+- ', 'cstrike', 'The original Counter-Strike', './steam -command update -game cstrike -dir .', './hlds_run -game cstrike +ip %IP% +port %PORT% +map de_dust2 +maxplayers 16'),
('', 4, 3784, 1, 0, 'ventrilo', 'Ventrilo', 'vent', '', 'ventrilo_srv.pid', '', '', 'Voice Communication Server', '', './ventrilo_srv -d'),
('', 3, 27015, 1, 1, 'source', 'Counter-Strike: Condition Zero', 'cs_cz', 'czero', '', '+- ', 'czero', 'Update of CS:1.6 with bots and missions', './steam -command update -game czero -dir .', './hlds_run -game cstrike +ip %IP% +port %PORT% +map de_dust +maxplayers 16'),
('', 1, 27015, 1, 1, 'source', 'Counter-Strike: Source', 'cs_s', 'css', '', '+- ', 'Counter-Strike Source', 'Source version of Counter-Strike', './steam -command update -game \'Counter-Strike Source\' -dir .', './srcds_run -game cstrike -ip %IP% -port %PORT% +maxplayers 12 +map de_dust2 -tickrate 66 +mp_dynamicpricing 0'),
('', 7, 27015, 1, 1, 'source', 'Counter-Strike: GO', 'cs_go', 'csgo', '', '+- ', 'csgo', 'Counter-Strike: Global Offensive', 'export LD_LIBRARY_PATH=linux32/ && STEAMEXE=steamcmd ./steam.sh +runscript update.txt', './srcds_run -game csgo -ip %IP% -port %PORT% +map de_dust2 +sv_pure 0 +mapgroup mg_bomb -tickrate 66 +maxplayers 12 -maxplayers_override 12 +net_public_adr %IP% +game_type 0 +game_mode 1 +sv_steamgroup_exclusive 1 -console -usercon '),
('', 5, 16567, 1, 0, 'bf2', 'Battlefield 2', 'bf2', '', '', '', '', 'Next iteration in the Battlefield series', '', './start.sh'),('', 6, 25585, 1, 0, 'minecraft', 'Minecraft', 'mcraft', '', '', '', '', 'CraftBukkit Minecraft Server', '', 'java -Xincgc -Xmx1000M -jar craftbukkit.jar nogui');

INSERT INTO `default_startup` (`id`, `defid`, `sort_order`, `single`, `usr_edit`, `cmd_item`, `cmd_value`) VALUES
('', 4, 0, 0, 0, './srcds_run', ''),
('', 4, 1, 0, 0, '-game', 'cstrike'),
('', 4, 2, 0, 0, '-ip', '%IP%'),
('', 4, 3, 0, 0, '-port', '%PORT%'),
('', 4, 4, 0, 0, '+maxplayers', '12'),
('', 4, 5, 0, 1, '+map', 'de_dust2'),
('', 4, 6, 0, 0, '-tickrate', '66'),
('', 4, 7, 0, 1, '+mp_dynamicpricing', '0'),
('', 1, 0, 0, 0, './hlds_run', ''),
('', 1, 1, 0, 0, '-game', 'cstrike'),
('', 1, 2, 0, 0, '+ip', '%IP%'),
('', 1, 3, 0, 0, '+port', '%PORT%'),
('', 1, 4, 0, 0, '+maxplayers', '12'),
('', 1, 5, 0, 1, '+map', 'de_dust2'),
('', 5, 0, 0, 0, './srcds_run', ''),
('', 5, 1, 0, 0, '-game', 'csgo'),
('', 5, 2, 0, 0, '-ip', '%IP%'),
('', 5, 3, 0, 0, '-port', '%PORT%'),
('', 5, 4, 0, 1, '+map', 'de_dust2'),
('', 5, 6, 0, 0, '+mapgroup', 'mg_bomb'),
('', 5, 7, 0, 0, '-tickrate', '66'),
('', 5, 8, 0, 0, '+maxplayers', '12'),
('', 5, 9, 0, 0, '-maxplayers_override', '12'),
('', 5, 10, 0, 0, '+net_public_adr', '%IP%'),
('', 5, 11, 0, 0, '+game_type', '0'),
('', 5, 12, 0, 0, '+game_mode', '1'),
('', 5, 13, 0, 0, '+sv_steamgroup_exclusive', '1'),
('', 5, 14, 0, 0, '-console', ''),
('', 5, 15, 0, 0, '-usercon', ''),
('', 5, 5, 0, 0, '+sv_pure', '0');
