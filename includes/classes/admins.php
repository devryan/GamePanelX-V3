<?php
// Admin Accounts class
class Admins
{
    // Create a new admin account
    public function create($username,$password,$email,$first_name,$last_name,$language)
    {
        if(empty($username) || empty($password) || empty($email)) return 'Create: Insufficient info provided';
        if(empty($language)) $language = 'english';
        
        // Valid usernames
        if(!preg_match('/^[a-zA-Z0-9-_]+$/', $username)) return 'Invalid username specified!  Allowed characters: a-z, 0-9, - _';
        if(strlen($username) < 3) return 'Usernames must be at least 3 characters!';
        
        // Password length (minimum 5 characters)
        if(strlen($password) < 5) return 'Passwords must be at least 5 characters!';
        
        // Password strength
        if($password == '123' || $password == '1234' || $password == '12345' || $password == 'password' || $password == 'pass123' || $password == 'pass1234' || $password == 'pass12345') return 'Sorry, please choose a real password!';
        
        // Check existing username
        $result_ck  = @mysql_query("SELECT id FROM admins WHERE username = '$username' LIMIT 1");
        $row_ck     = mysql_fetch_row($result_ck);
        if($row_ck[0]) return $lang['user_exists'];
        
        @mysql_query("INSERT INTO admins (date_created,username,password,language,email_address,first_name,last_name) VALUES(NOW(),'$username',MD5('$password'),'$language','$email','$first_name','$last_name')") or die('Failed to create user: '.mysql_error());
        
        
        // Output
        return 'success';
    }

}
