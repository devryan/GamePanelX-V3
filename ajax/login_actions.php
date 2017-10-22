<?php
require('checkallowed.php'); // No direct access
error_reporting(E_ERROR);

// actions
$url_id           = $GPXIN['id'];
$url_do           = $GPXIN['do']; // Action
$url_login_user   = $GPXIN['user'];
$url_login_pass   = $GPXIN['pass'];

// Admin Login
if($url_do == 'adminlogin')
{
    // Remove hashing
    $url_login_user   = preg_replace('/(^xxz)?(yy$)?/', '', $url_login_user);
    $url_login_pass   = preg_replace('/(^xyy)?(yyx$)?/', '', $url_login_pass);

    // Check 3.0.10 passwords
    $url_pass_oldstyle	= md5($url_login_pass);
    $url_pass_enc	= base64_encode(sha1('ZzaX'.$url_login_pass.'GPX88'));
    $sql_checkpass      = "AND (`password` = '$url_pass_enc' OR `password` = '$url_pass_oldstyle')";


	#echo "User: $url_login_user, pass: $url_login_pass\n";

    // Check login
    $result_login = $GLOBALS['mysqli']->query("SELECT id,setpass_3010,theme,language,email_address,first_name FROM admins WHERE username = '$url_login_user' $sql_checkpass ORDER BY id ASC LIMIT 1") or die('Failed to check login');
    $totals       = $result_login->num_rows;

    // Failed login
    if($totals == 0) die($lang['invalid_login']);

    // Login good, setup session
    session_start();

    while($row_login  = $result_login->fetch_array())
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
        $GLOBALS['mysqli']->query("UPDATE admins SET `setpass_3010` = '1',`password` = '$upd_pass' WHERE id = '$this_userid'") or die('Failed to update password security: '.$GLOBALS['mysqli']->error);
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
    $enc_key   = $settings['enc_key'];

    # OLD: $sql_pass  = "AND password = MD5('$url_login_pass')";

    // Check login
    $result_login = $GLOBALS['mysqli']->query("SELECT
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

    $totals       = $result_login->num_rows;
    $login_attemps = $GLOBALS['mysqli']->query("SELECT
      login_attempts
      FROM users
      WHERE`username` = '$url_login_user'
      ") or die ("Unable To Check Login Attempts Try Again Later");
      $LoginAttempt = $login_attemps->fetch_array();
      $currentattempt = $LoginAttempt['login_attempts'];
      //echo $currentattempt;

    // Failed login
    if($totals == 0 || $currentattempt == 5)
    {

      if($currentattempt > 4){
        echo('Your Account Has Been Locked. To unlock this please contact us');
      }else{

        echo('Login Failed - Check Your Details And Try Again');
        mysqli_query($GLOBALS['mysqli'], "UPDATE users SET login_attempts = login_attempts + 1 WHERE username = '$url_login_user' ");
      }

    }//die($lang['invalid_login']);
    else {
    // Login good, setup session
    session_start();
    $perms_arr  = array();
    mysqli_query($GLOBALS['mysqli'], "UPDATE users SET login_attempts = '0' WHERE username = '$url_login_user' ");

    while($row_login  = $result_login->fetch_array())
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

	$url_login_user   = preg_replace('/(^xxz)?(yy$)?/', '', $url_login_user);
	$result_login = $GLOBALS['mysqli']->query("SELECT id,email_address FROM $tblname WHERE username = '$url_login_user' ORDER BY id ASC LIMIT 1") or die('Failed to check login');
	$row_login    = $result_login->fetch_row();
	$fpw_id       = $row_login[0];
	$fpw_email    = $row_login[1];

	// Generate 24 char token for forgot password link
	$Core = new Core;
	$sys_company = $Core->getsettings('company');
	if(empty($sys_company)) $sys_company = 'Game Control Panel';

	// Store token
	$chpw_token = $GLOBALS['mysqli']->real_escape_string($Core->genstring('24'));
	$GLOBALS['mysqli']->query("UPDATE $tblname SET `chpw_token` = '$chpw_token' WHERE id = '$fpw_id'") or die('Failed to store token!');

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
