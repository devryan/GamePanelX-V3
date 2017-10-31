<?php
define('DOCROOT', '../');
require(DOCROOT.'/lang.php');
require('version.php');
session_start();

$url_action = $_POST['a'];

// Begin installation
if($url_action == 'start')
{
    #####################################################################################
    
    require(DOCROOT.'/includes/classes/core.php');
    $Core = new Core;
    
    // Check system requirements
    if(!isset($_SESSION['install_req']))
    {
        if(!function_exists('mysqli_connect')) die('You do not have <b>MySQL</b> support (mysqli_connect) built into PHP!  Rebuild your PHP install with MySQL support and try again.');
        elseif(!function_exists('curl_init')) die('You do not have <b>Curl</b> support (curl_init) built into PHP!  Rebuild your PHP install with cURL support and try again.');
    }
    
    $_SESSION['install_req']  = 1;
    
    #####################################################################################
    
    $url_language     = $_POST['language'];
    $url_db_host      = $_POST['db_host'];
    $url_db_name      = $_POST['db_name'];
    $url_db_user      = $_POST['db_user'];
    $url_db_pass      = $_POST['db_pass'];
    $url_admin_user   = $_POST['admin_user'];
    $url_admin_pass   = $_POST['admin_pass'];
    $url_admin_email  = $_POST['admin_email'];
    
    // Test DB Connection
    $GLOBALS['mysqli'] = new mysqli($url_db_host, $url_db_user, $url_db_pass) or die('Failed to connect to the database ('.$GLOBALS['mysqli']->error.').  Check your settings and try again.');
    $GLOBALS['mysqli']->select_db($url_db_name) or die('Failed to select the database ('.$GLOBALS['mysqli']->error.').  Check your settings and try again.');
    
    #####################################################################################
    
    // Check PHP Version
    if(!isset($_SESSION['install_phpver']))
    {
        $php_ver  = phpversion();
        
        if($php_ver < '5.0') die('Your PHP version ('.$php_ver.') is below 5.0!  GamePanelX requires PHP 5 or greater.');
    }
    
    $_SESSION['install_phpver']  = 1;
    
    #####################################################################################
    
    // Get docroot
    $this_docroot = getcwd();
    $this_docroot = str_replace('/install', '/', $this_docroot); // Remove '/install' at the end
    
    // Check _SERVERS permissions are webuser:webgroup
    if(!isset($_SESSION['check_srv_perms']))
    {
		#$temp = tmpfile();
		$temp = '/tmp/gpxinstalltmp.txt';
		touch($temp);
		
		$websrv_user    = posix_getpwuid(fileowner($temp));
		$restart_owner  = posix_getpwuid(fileowner($this_docroot.'/_SERVERS/scripts/Restart'));
		$steam_owner	= posix_getpwuid(fileowner($this_docroot.'/_SERVERS/scripts/SteamInstall'));
		
		$websrv_user	= $websrv_user['name'];
		$restart_owner  = $restart_owner['name'];
		$steam_owner	= $steam_owner['name'];
		
		if($websrv_user != $restart_owner || $websrv_user != $steam_owner) die('Invalid permissions on the _SERVERS directory!  You can fix this with "sudo chown '.$websrv_user.': '.$this_docroot.'/_SERVERS -R ; sudo chmod ug+rx '.$this_docroot.'/_SERVERS/scripts/*".');
	}
	
	$_SESSION['check_srv_perms']  = 1;
	
    #####################################################################################
    
    // Create database tables
    $sql_file = 'sql/'.GPX_VERSION.'.sql';
    
    if(!isset($_SESSION['install_tbl']))
    {
        if(file_exists($sql_file))
        {
            // Read SQL file, run each query separately
            $data = file_get_contents($sql_file);
            $arr_data = explode(';', $data);
            
            foreach($arr_data as $query)
            {
                $query  = trim($query);
                if($query) $GLOBALS['mysqli']->query($query) or die('Failed to run SQL: '.$GLOBALS['mysqli']->error);
            }
        }
        else
        {
            die('Failed to find SQL install file ('.$sql_file.')!');
        }
    }
    
    $_SESSION['install_tbl']  = 1;
    
    #####################################################################################
    
    // Create admin
    if(!isset($_SESSION['install_admin']))
    {
        require(DOCROOT.'/includes/classes/admins.php');
        $Admins = new Admins;
        $admin_result = $Admins->create($url_admin_user,$url_admin_pass,$url_admin_email,'','',$url_language);
        
        if($admin_result != 'success') die('Failed to create admin: '.$admin_result);
    }
    
    $_SESSION['install_admin']  = 1;
    
    #####################################################################################
    
    if(!isset($_SESSION['install_config']))
    {
    
    // Generate enc key
    $rand_string  = $Core->genstring(64);
    $api_key      = $Core->genstring(128);
    
    // Get docroot
    #$this_docroot = getcwd();
    #$this_docroot = str_replace('/install', '/', $this_docroot); // Remove '/install' at the end
    
    // Create 'configuration.php' file
    $config_file  = DOCROOT.'/configuration.php';
    
    
    $file_data    = '<?php
// Main GamePanelX Configuration File
$settings[\'db_host\']      = \''.$url_db_host.'\'; // No need to change this
$settings[\'db_name\']      = \''.$url_db_name.'\'; // Your database name
$settings[\'db_username\']  = \''.$url_db_user.'\'; // Your database username
$settings[\'db_password\']  = \''.$url_db_pass.'\'; // Your database password
$settings[\'docroot\']      = \''.$this_docroot.'\'; // Set to the full path to your GamePanelX installation e.g. /home/me/public_html/gpx/
$settings[\'enc_key\']      = \''.$rand_string.'\'; // No need to change this
$settings[\'debug\']        = false;

###################################

/* No need to edit these! */
if(!defined(\'DOCROOT\'))
{
    define(\'DOCROOT\', $settings[\'docroot\']);
    define(\'GPXDEBUG\', $settings[\'debug\']);
}

date_default_timezone_set(\'US/Central\');

if($settings[\'debug\']) error_reporting(E_ALL);
else error_reporting(E_ERROR);

?>';
    
    // Store docroot in session
    $_SESSION['install_docroot']  = $this_docroot;
    
    // Try and create file
    if(!file_exists($config_file))
    {
        touch($config_file) or die('Failed to create "configuration.php".<br />Please rename the "configuration.new.php" file to "configuration.php" and try again.');
    }
    // Check writable
    if(file_exists($config_file) && !is_writable($config_file)) die('"configuration.php" exists, but we were unable to write to it.<br />Check file permissions and ownership and try again.');
    
    // Write to file
    $fh = fopen($config_file, 'w') or die('Failed to open file ('.$config_file.')!<br />Please rename the \'configuration.new.php\' file to \'configuration.php\' and try again.');
    fwrite($fh, $file_data);
    fclose($fh);
    }
    
    $_SESSION['install_config']  = 1;
    
    #####################################################################################
    
    // Insert configuration items
    if(!isset($_SESSION['install_configitems']))
    {
        $gpx_version  = GPX_VERSION;
        $admin_id = $GLOBALS['mysqli']->query("SELECT id FROM admins WHERE username = '$url_admin_user' LIMIT 1");
        $admin_id = $admin_id->fetch_row()[0];
        $GLOBALS['mysqli']->query("INSERT INTO `configuration` (`config_setting`, `config_value`, `last_updated_by`) VALUES('default_email_address', '$url_admin_email',$admin_id),('language', '$url_language',$admin_id),('company', 'GamePanelX',$admin_id),('theme', 'default',$admin_id),('api_key', '$api_key',$admin_id),('version', '$gpx_version',$admin_id),('steam_login_user','',$admin_id),('steam_login_pass','',$admin_id),('steam_auth','',$admin_id)") or die('Failed to insert configuration items: '.$GLOBALS['mysqli']->error);
    }
    
    $_SESSION['install_configitems']  = 1;
    
    #####################################################################################
    
    // Add a default local server for the new people's sanity
    if(!isset($_SESSION['install_addnet']))
    {
		require(DOCROOT.'/includes/classes/network.php');
		$Network = new Network;
		$result_net = $Network->create($_SERVER['SERVER_ADDR'],'1',PHP_OS,'','Auto-Generated Local Server','','','');

		if($result_net != 'success') die('Failed to create default network server: '.$result_net);
	}
	$_SESSION['install_addnet']  = 1;

    #####################################################################################
    
    // Create a default sample user
    if(!isset($_SESSION['install_adduser']))
    {
		$username = 'example';
		$password = $Core->genstring(16);
		$fk_pass  = $Core->genstring(24);
		$enc_key  = $rand_string;
		
		$GLOBALS['mysqli']->query("INSERT INTO users (date_created,sso_user,sso_pass,username,password,first_name,last_name,email_address) VALUES(NOW(),AES_ENCRYPT('$username', '$enc_key'),AES_ENCRYPT('$password', '$enc_key'),'$username',MD5('$fk_pass'),'Example','User','example@example.com')") or die('Failed to create user: '.$GLOBALS['mysqli']->error);
	}
	$_SESSION['install_adduser']  = 1;
	
    #####################################################################################
    
    // Finished, output
    echo 'success';
}
else
{
   die('No action given!');
}

?>
