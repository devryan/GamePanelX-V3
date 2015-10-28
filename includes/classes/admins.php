<?php
// Admin Accounts class
class Admins
{
    // Create a new admin account
    public function create($username,$password,$email,$first_name,$last_name)
    {
        if(empty($username) || empty($password) || empty($email)) return 'Create: Insufficient info provided';
	require(DOCROOT.'/includes/password_compat/lib/password.php');
        
        // Valid usernames
        if(!preg_match('/^[a-zA-Z0-9-_]+$/', $username)) return 'Invalid username specified!  Allowed characters: a-z, 0-9, - _';
        if(strlen($username) < 3) return 'Usernames must be at least 3 characters!';
        
        // Password length (minimum 5 characters)
        if(strlen($password) < 5) return 'Passwords must be at least 5 characters!';
        
        // Check existing username
        $result_ck  = @mysql_query("SELECT id FROM admins WHERE username = '$username' LIMIT 1");
        $row_ck     = mysql_fetch_row($result_ck);
        if($row_ck[0]) return $lang['user_exists'];
        
	# Setup storing of user password
        $pass_safe = password_hash($password, PASSWORD_DEFAULT);

        @mysql_query("INSERT INTO admins (date_created,username,password,email_address,first_name,last_name) VALUES(NOW(),'$username','$pass_safe','$email','$first_name','$last_name')") or die('Failed to create admin: '.mysql_error());
        
        
        // Output
        return 'success';
    }

}
