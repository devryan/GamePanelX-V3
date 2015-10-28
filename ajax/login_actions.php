<?php
require('checkallowed.php'); // No direct access
error_reporting(E_ERROR);
require(DOCROOT.'/includes/password_compat/lib/password.php');

// actions
// Note: $url_login* are already auto base64_decoded by includes/classes/core.php.
$url_id           = $GPXIN['id'];
$url_do           = $GPXIN['do']; // Action
$url_login_user   = $GPXIN['user'];
$url_login_pass   = $GPXIN['pass'];

// Admin Login
if($url_do == 'adminlogin')
{
    // Check 3.0.10 passwords
    #$url_pass_oldstyle	= md5($url_login_pass);
    #$url_pass_enc	= base64_encode(sha1('ZzaX'.$url_login_pass.'GPX88'));
    #$sql_checkpass      = "AND (`password` = '$url_pass_enc' OR `password` = '$url_pass_oldstyle')";
    
    // Get user info
    $result_login = @mysql_query("SELECT id,setpass_3010,theme,language,email_address,first_name,password FROM admins WHERE username = '$url_login_user' ORDER BY id ASC LIMIT 1") or die('Failed to check login');
    ### $rows_usrinfo = mysql_fetch_row($result_login);
    ### $totals       = mysql_num_rows($result_login);
    
    // Verify password
    ########## if(!password_verify($url_login_pass, $rows_usrinfo[6])) die($lang['invalid_login']);
    
    // Login good, setup session
    ##### session_start();

    while($row_login  = mysql_fetch_array($result_login))
    {
        $this_userid              = $row_login['id'];
	$this_pass		  = $row_login['password'];
	$gpx_lang		  = $row_login['language'];
	$gpx_username		  = stripslashes($url_login_user);
	$gpx_email		  = stripslashes($row_login['email_address']);
	$gpx_fname		  = stripslashes($row_login['first_name']);
	$gpx_type		  = 'admin';
	$gpx_admin		  = '1';
	$gpx_theme		  = $row_login['theme'];
        # $_SESSION['gpx_userid']   = $this_userid;
        # $_SESSION['gpx_lang']     = $row_login['language'];
        # $_SESSION['gpx_username'] = stripslashes($url_login_user);
        # $_SESSION['gpx_email']    = stripslashes($row_login['email_address']);
        # $_SESSION['gpx_fname']    = stripslashes($row_login['first_name']);
        # $_SESSION['gpx_type']     = 'admin';
        # $_SESSION['gpx_admin']    = '1';
        
        // Default theme
        # if(empty($row_login['theme'])) $_SESSION['gpx_theme'] = 'default';
        # else $_SESSION['gpx_theme']    = $row_login['theme'];
    }

    // Verify password
    if(!password_verify($url_login_pass, $this_pass)) die($lang['invalid_login']);

    // Login good, setup session
    session_start();
    $_SESSION['gpx_userid']   = $this_userid;
    $_SESSION['gpx_lang']     = $gpx_lang;
    $_SESSION['gpx_username'] = $gpx_username;
    $_SESSION['gpx_email']    = $gpx_email;
    $_SESSION['gpx_fname']    = $gpx_fname;
    $_SESSION['gpx_type']     = $gpx_type;
    $_SESSION['gpx_admin']    = $gpx_admin;

    // Default theme
    if(empty($gpx_theme)) $_SESSION['gpx_theme'] = 'default';
    else $_SESSION['gpx_theme']    = $gpx_theme;

    // Check database for active plugins
    $Plugins->reset_session();
    
    // Output
    echo 'success';
}




// User Login
elseif($url_do == 'userlogin')
{
    // Get user info
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
                                    first_name,
				    password
                                  FROM users 
                                  WHERE 
                                    `username` = '$url_login_user' 
                                    AND `deleted` = '0' 
                                  ORDER BY id ASC 
                                  LIMIT 1") or die('Sorry, we were unable to check your login.  Please try again soon.');
    
    $totals       = mysql_num_rows($result_login);
    $rows_usrinfo = mysql_fetch_row($result_login);

    // Verify password
    if(!password_verify($url_login_pass, $rows_usrinfo[11])) die($lang['invalid_login']);
    
    // Login good, setup session
    session_start();
    $perms_arr  = array();
    
    while($row_login  = mysql_fetch_array($result_login))
    {
        // Store in session
        # $_SESSION['gpx_userid']   = $row_login['id'];
        # $_SESSION['gpx_lang']     = $row_login['language'];
        # $_SESSION['gpx_username'] = stripslashes($url_login_user);
        # $_SESSION['gpx_email']    = $row_login['email_address'];
        # $_SESSION['gpx_fname']    = $row_login['first_name'];
        # $_SESSION['gpx_type']     = 'user';
        
	# !!!!!!!!!! This is incomplete, finish this to look like admin section above!
	$this_userid              = $row_login['id'];
        $this_pass                = $row_login['password'];
        $gpx_lang                 = $row_login['language'];
        $gpx_username             = stripslashes($url_login_user);
        $gpx_email                = stripslashes($row_login['email_address']);
        $gpx_fname                = stripslashes($row_login['first_name']);
        $gpx_type                 = 'user';
        $gpx_theme                = $row_login['theme'];

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

// Forgot Password
elseif($url_do == 'forgotpw')
{
	$usr_ip = $_SERVER['REMOTE_ADDR'];
	$chpw_type = $GPXIN['usrtype'];
	if(empty($chpw_type)) die('No user type specified!');

	// Get email address for this user
	if($chpw_type == 'admin') $tblname = 'admins';
	else $tblname = 'users';

	$url_login_user   = base64_decode($url_login_user);
	$result_login = @mysql_query("SELECT id,email_address FROM $tblname WHERE username = '$url_login_user' ORDER BY id ASC LIMIT 1") or die('Failed to check login');
	$row_login    = mysql_fetch_row($result_login);
	$fpw_id       = $row_login[0];
	$fpw_email    = $row_login[1];

	// Generate 24 char token for forgot password link
	$Core = new Core;
	$sys_company = $Core->getsettings('company');
	if(empty($sys_company)) $sys_company = 'Game Control Panel';

	// Store token
	$chpw_token = mysql_real_escape_string($Core->genstring('24'));
	@mysql_query("UPDATE $tblname SET `chpw_token` = '$chpw_token' WHERE id = '$fpw_id'") or die('Failed to store token!');

	// Email user their stuff
	$message = "$sys_company

A Forgot Password request was submitted for the '$url_login_user' account from IP Address $usr_ip.
If you did not request a password reset, you can safely ignore this email.

To reset your password, click this link:

<a href=\"forgotpassword.php?id=$fpw_id&token=$chpw_token\">Click here to reset your password</a>

This email was automatically sent.  Please do not reply to it.";

	$headers = 'From: webmaster@example.com' . "\r\n" .
	    'Reply-To: webmaster@example.com' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();

	if($chpw_type == 'admin') $mail_subj = 'Forgot Password: GamePanelX';
	else $mail_subj = 'Forgot Password: ' . $sys_company;

	mail($fpw_email, $mail_subj, $message, $headers);


	// Why not
	unset($fpw_id);
	unset($fpw_email);
	unset($chpw_token);

}

?>
