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
    $url_login_user   = mysql_real_escape_string(preg_replace('/(^xxz)?(yy$)?/', '', $url_login_user));
    $url_login_pass   = preg_replace('/(^xyy)?(yyx$)?/', '', $url_login_pass);
    
    // Check 3.0.10 passwords
    $url_pass_oldstyle	= mysql_real_escape_string(md5($url_login_pass));
    $url_pass_enc	= mysql_real_escape_string(base64_encode(sha1('ZzaX'.$url_login_pass.'GPX88')));
    $sql_checkpass      = "AND (`password` = '$url_pass_enc' OR `password` = '$url_pass_oldstyle')";
    
    // Check login
    $result_login = @mysql_query("SELECT id,setpass_3010,theme,language,email_address,first_name FROM admins WHERE username = '$url_login_user' $sql_checkpass ORDER BY id ASC LIMIT 1") or die('Failed to check login');
    $totals       = mysql_num_rows($result_login);
    
    // Failed login
    if($totals == 0) die($lang['invalid_login']);
    
    // Login good, setup session
    session_start();
    
    while($row_login  = mysql_fetch_array($result_login))
    {
        // Store in session
        $this_userid              = $row_login['id'];
        $_SESSION['gpx_userid']   = $this_userid;
        $_SESSION['gpx_lang']     = $row_login['language'];
        $_SESSION['gpx_username'] = stripslashes($url_login_user);
        $_SESSION['gpx_email']    = stripslashes($row_login['email_address']);
        $_SESSION['gpx_fname']    = stripslashes($row_login['first_name']);
        $_SESSION['gpx_type']     = 'admin';
        $_SESSION['gpx_admin']    = '1';
        
        // Check if password was updated to 3.0.10 style yet
        $pass_upd_3010 = $row_login['setpass_3010'];
        
        // Default theme
        if(empty($row_login['theme'])) $_SESSION['gpx_theme'] = 'default';
        else $_SESSION['gpx_theme']    = $row_login['theme'];
    }
    
    // Update password for 3.0.10 style if needed
    if(!$pass_upd_3010)
    {
		$upd_pass = base64_encode(sha1('ZzaX'.$url_login_pass.'GPX88'));
        @mysql_query("UPDATE admins SET `setpass_3010` = '1',`password` = '$upd_pass' WHERE id = '$this_userid'") or die('Failed to update password security: '.mysql_error());
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
    $url_login_user   = mysql_real_escape_string(preg_replace('/(^xxff)?(yyuuu$)?/', '', $url_login_user));
    $url_login_pass   = mysql_real_escape_string(preg_replace('/(^xyd)?(yyd$)?/', '', $url_login_pass));
    $enc_key   = $settings['enc_key'];
    
    # OLD: $sql_pass  = "AND password = MD5('$url_login_pass')";
    
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
                                    `username` = '$url_login_user' 
                                    AND AES_DECRYPT(sso_pass, '$enc_key') = '$url_login_pass' 
                                    AND `deleted` = '0' 
                                  ORDER BY id ASC 
                                  LIMIT 1") or die('Sorry, we were unable to check your login.  Please try again soon.');
    
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
