<?php
require('checkallowed.php'); // No direct access
error_reporting(E_ERROR);

// actions
$url_id           = $GPXIN['id'];
$url_do           = $GPXIN['do']; // Action
$url_login_user   = base64_decode($GPXIN['user']);
$url_login_pass   = base64_decode($GPXIN['pass']);


// Admin Login
if($url_do == 'adminlogin')
{
    // Remove hashing
    $url_login_user   = preg_replace('/(^xxz)?(yy$)?/', '', $url_login_user);
    $url_login_pass   = preg_replace('/(^xyy)?(yyx$)?/', '', $url_login_pass);

    // Check login
    $result_login = @mysql_query("SELECT id,theme,language,email_address,first_name FROM admins WHERE username = '$url_login_user' AND password = MD5('$url_login_pass') ORDER BY id ASC LIMIT 1") or die('Failed to check login');
    $totals       = mysql_num_rows($result_login);
    
    // Failed login
    if($totals == 0) die($lang['invalid_login']);
    
    // Login good, setup session
    session_start();
    
    while($row_login  = mysql_fetch_array($result_login))
    {
        // Store in session
        $_SESSION['gpx_userid']   = $row_login['id'];
        $_SESSION['gpx_lang']     = $row_login['language'];
        $_SESSION['gpx_username'] = stripslashes($url_login_user);
        $_SESSION['gpx_email']    = stripslashes($row_login['email_address']);
        $_SESSION['gpx_fname']    = stripslashes($row_login['first_name']);
        $_SESSION['gpx_type']     = 'admin';
        $_SESSION['gpx_admin']    = '1';
        
        // Default theme
        if(empty($row_login['theme'])) $_SESSION['gpx_theme'] = 'default';
        else $_SESSION['gpx_theme']    = $row_login['theme'];
    }
    
    // Check database for active plugins
    #require(DOCROOT.'/includes/classes/plugins.php');
    #$Plugins  = new Plugins;
    $Plugins->reset_session();
    
    // Output
    echo 'success';
}




// User Login
elseif($url_do == 'userlogin')
{
    // Remove hashing
    $url_login_user   = preg_replace('/(^xxff)?(yyuuu$)?/', '', $url_login_user);
    $url_login_pass   = preg_replace('/(^xyd)?(yyd$)?/', '', $url_login_pass);
    
    // Check login
    $result_login = @mysql_query("SELECT 
                                    id,
                                    perm_ftp,
                                    perm_files,
                                    perm_startup,
                                    perm_startup_see,
                                    perm_chpass,
                                    perm_updetails,
                                    theme,
                                    language,
                                    email_address,
                                    first_name 
                                  FROM users 
                                  WHERE 
                                    username = '$url_login_user' AND password = MD5('$url_login_pass') AND deleted = '0' 
                                  ORDER BY id ASC 
                                  LIMIT 1") or die('Failed to check login');
    
    $totals       = mysql_num_rows($result_login);
    
    // Failed login
    if($totals == 0) die($lang['invalid_login']);
    
    // Login good, setup session
    session_start();
    $perms_arr  = array();
    
    while($row_login  = mysql_fetch_array($result_login))
    {
        // Store in session
        $_SESSION['gpx_userid']   = $row_login['id'];
        $_SESSION['gpx_lang']     = $row_login['language'];
        $_SESSION['gpx_username'] = stripslashes($url_login_user);
        $_SESSION['gpx_email']    = $row_login['email_address'];
        $_SESSION['gpx_fname']    = $row_login['first_name'];
        $_SESSION['gpx_type']     = 'user';
        
        // Default theme
        if(empty($row_login['theme'])) $_SESSION['gpx_theme'] = 'default';
        else $_SESSION['gpx_theme']    = $row_login['theme'];
        
        // Add permissions to array
        $perms_arr['perm_ftp']          = $row_login['perm_ftp'];
        $perms_arr['perm_files']        = $row_login['perm_files'];
        $perms_arr['perm_startup']      = $row_login['perm_startup'];
        $perms_arr['perm_startup_see']  = $row_login['perm_startup_see'];
        $perms_arr['perm_chpass']       = $row_login['perm_chpass'];
        $perms_arr['perm_updetails']    = $row_login['perm_updetails'];
    }
    
    // Store perms array in session
    $_SESSION['gpx_perms']  = $perms_arr;
    
    ##################################
    
    
    // Output
    echo 'success';
}

?>
