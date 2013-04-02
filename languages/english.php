<?php
/*
 * GamePanelX
 * 
 * English Language file
 * Written by Ryan Gehrig
 * 
 * To translate to another language, copy this to a new PHP file named "yourlanguage.php", and translate all the english words on the right (to the right of the = sign).
 * Try and keep the structure of the file the same, and make sure you close all '';
 * 
*/
$lang = array();

########################################################################

// Common Words/Phrases
$lang['yes']                = 'Yes';
$lang['no']                 = 'No';
$lang['active']             = 'Active';
$lang['inactive']           = 'Inactive';
$lang['setup']              = 'Setup';
$lang['settings']           = 'Settings';
$lang['language']           = 'Language';
$lang['default_language']   = 'Default Language';
$lang['email_address']      = 'Email Address';
$lang['company']            = 'Company';
$lang['theme']              = 'Theme';
$lang['save']               = 'Save';
$lang['game']               = 'Game';
$lang['voice']              = 'Voice';
$lang['desc']               = 'Description';
$lang['status']             = 'Status';
$lang['manage']             = 'Manage';
$lang['info']               = 'Info';
$lang['owner']              = 'Owner';
$lang['date_added']         = 'Date Added';
$lang['last_updated']       = 'Last Updated';
$lang['default']            = 'Default';
$lang['delete']             = 'Delete';
$lang['create']             = 'Create';
$lang['add']                = 'Add';
$lang['edit']               = 'Edit';
$lang['failed']             = 'Failed';
$lang['name']               = 'Name';
$lang['first_name']         = 'First Name';
$lang['last_name']          = 'Last Name';
$lang['type']               = 'Type';
$lang['none']               = 'None';
$lang['optional']           = 'Optional';
$lang['complete']           = 'Complete';
$lang['go_back']            = 'Go Back';
$lang['saved']              = 'Saved';

// Tech Words
$lang['server']             = 'Server';
$lang['username']           = 'Username';
$lang['password']           = 'Password';
$lang['newpassword']        = 'New Password';
$lang['newpassword_conf']   = '<b>New Password</b> (confirm)';
$lang['chpassword']         = 'Change Password';
$lang['cur_password']       = 'Current Password';
$lang['network']            = 'Network';
$lang['online']             = 'Online';
$lang['offline']            = 'Offline';
$lang['connect']            = 'Connect';
$lang['startup']            = 'Startup';
$lang['files']              = 'Files';
$lang['command']            = 'Command';
$lang['local_dir']          = 'Local Directory';
$lang['working_dir']        = 'Working Directory';
$lang['pid_file']           = 'PID File';
$lang['ip']                 = 'IP Address';
$lang['ips']                = 'IP Addresses';
$lang['port']               = 'Port';
$lang['login']              = 'Login';
$lang['logout']             = 'Logout';
$lang['logged_out']         = 'Successfully logged out';
$lang['install']            = 'Install';
$lang['installing']         = 'Installing';
$lang['not_installed']      = 'Not Installed';

$lang['unknown']            = 'Unknown';
$lang['click_here']         = 'Click Here';
$lang['documentation']      = 'GamePanelX Documentation';
$lang['update_cmd']         = 'Update CMD';
$lang['banned_start']       = 'Banned Startup Values';
$lang['banned_start_desc']  = '<b>Note:</b> Put all characters you do not want clients to type in the "value" part of their startup editor.';
$lang['plugin']             = 'Plugin';
$lang['plugins']            = 'Plugins';
$lang['del_install']        = 'Please delete the "install" directory before continuing!';
$lang['version']            = 'Version';
$lang['system_update']      = 'A system update is available!';
$lang['invalid_login']      = 'Invalid login!  Please try again.';
$lang['permissions']        = 'Permissions';

// Error messages
$lang['err_query']          = 'Failed to query the database';
$lang['err_sql_update']     = 'Failed to update the database';

// Left Panel
$lang['home']               = 'Home';
$lang['setup']              = 'Setup';
$lang['settings']           = 'Settings';
$lang['game_setups']        = 'Game Setups';
$lang['cloud_games']        = 'Cloud Games';
$lang['server_templates']   = 'Server Templates';
$lang['template']           = 'Template';
$lang['templates']          = 'Templates';
$lang['servers']            = 'Servers';
$lang['all_servers']        = 'All Servers';
$lang['game_servers']       = 'Game Servers';
$lang['voice_servers']      = 'Voice Servers';
$lang['create_server']      = 'Create Server';
$lang['accounts']           = 'Accounts';
$lang['admins']             = 'Admins';
$lang['resellers']          = 'Resellers';
$lang['list_users']         = 'List Users';
$lang['add_user']           = 'Add User';
$lang['list_admins']        = 'List Admins';
$lang['add_admin']          = 'Add Admin';
$lang['list_resellers']     = 'List Resellers';
$lang['add_reseller']       = 'Add Reseller';
$lang['welcome_msg']        = 'Welcome to GamePanelX';
$lang['int_name']           = 'Internal Name';
$lang['int_name_desc']      = 'Internal Name should only contain letters, numbers and underscores, such as "red_1"';
$lang['query_engine']       = 'Query Engine';
$lang['create_network']     = 'Create Network Server';

// Templates
$lang['delete_tp']          = 'Delete this template';
$lang['create_tp']          = 'Create Template';
$lang['file_path']          = 'File Path';
$lang['browse']             = 'Browse';

// Network
$lang['network_server']     = 'Network Server';
$lang['os']                 = 'Operating System';
$lang['location']           = 'Location';
$lang['datacenter']         = 'Datacenter';
$lang['local']              = 'Local';
$lang['local_server']       = 'Local Server';
$lang['remote_server']      = 'Remote Server';
$lang['no_enc_key']         = 'No encryption key found!  Check "/configuration.php".';
$lang['login_user']         = 'Login User';
$lang['login_pass']         = 'Login Password';
$lang['login_port']         = 'Login Port';
$lang['login_homedir']      = 'Home Directory';
$lang['net_showing_ips']    = 'Showing IP Addresses on Network Server';
$lang['srv_using_net']      = 'There are game servers using this Network Server!  Delete the servers first and try again.';
$lang['add_ip']             = 'Add IP Address';
$lang['new_ip']             = 'New IP Address';
$lang['ip_exists']          = 'Sorry, that IP Address already exists!';
$lang['ip_port_used']       = 'Sorry, that IP/Port combination is already in use!';
$lang['srv_using_ip']       = 'There are game servers using this IP Address!  Delete the servers first and try again.';
$lang['invalid_ip']         = 'Invalid IP Address!  Please check and try again.';

// Servers
$lang['create_sv']          = 'Create Server';
$lang['invalid_port']       = 'Invalid port specified!  Please try again.';
$lang['invalid_intname']    = 'Invalid Internal Name specified!  Only letters, numbers, - and _ are accepted.  Please try again.';
$lang['item']               = 'Item';
$lang['value']              = 'Value';
$lang['user_editable']      = 'User Editable';
$lang['restart']            = 'Restart';
$lang['stop']               = 'Stop';
$lang['update']             = 'Update';
$lang['map']                = 'Map';
$lang['hostname']           = 'Hostname';
$lang['players']            = 'Players';
$lang['show_options']       = 'Show all options';
$lang['simple']             = 'Simple';
$lang['advanced']           = 'Advanced';

// Cloud
$lang['cloud_avail']        = 'Games Available via <i>gpx cloud</i>';
$lang['cloud_topmsg']       = 'As more games are added to the GamePanelX Cloud, they will become available here.';

// Games
$lang['games_add_desc']     = 'Use this form to add new game support.  You can then proceed with creating a template for this game.';
$lang['games_up_icon']      = '<b>Note:</b> Upload your 64x64 PNG icon to';
$lang['note_steam_auto']    = '<b>Note:</b> For Steam-based games, leave the File Path empty to use the Steam auto-installer';

// File Manager
$lang['new_filename']       = 'New Filename';
$lang['new_dirname']        = 'New Directory Name';

// User perms
$lang['access_ftp']         = 'Access FTP';
$lang['update_usr_det']     = 'Update User Details';
$lang['user_exists']        = 'Sorry, that username already exists!';

// Home Page hints
$lang['def_adm_step']       = 'Step';
$lang['def_adm_tip_docs']   = 'Please see the full documentation';
$lang['def_adm_tip_accts']  = 'None found!  You should create a user account to create servers.';
$lang['def_adm_tip_net']    = 'No Network Servers found!  You should create one now';
$lang['def_adm_tip_tpl']    = 'No completed templates found!  To create servers, you should pick a game and';
$lang['def_adm_tip_srv1']   = 'You are all ready to create a server!';
$lang['def_adm_tip_srv2']   = 'Complete the steps above to create a game/voice server.';

// Other
$lang['api_key']            = 'API Key';

##############################################################################################################

// 3.0.8
$lang['install_mirrors']    = 'Install Mirrors';
$lang['game_panel']         = 'Game Control Panel';
$lang['show_console_out']   = 'Click to show console output';
$lang['config_file']        = 'Config File';
$lang['modified']           = 'Modified';
$lang['accessed']           = 'Accessed';
$lang['size']               = 'Size';
$lang['maxplayers']         = 'Max Players';
$lang['hostname']           = 'Hostname';

?>
