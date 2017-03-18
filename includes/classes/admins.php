<?php
// Admin Accounts class
require('../db.php');
class Admins
{
    // Create a new admin account
    public function create($username,$password,$email,$first_name,$last_name)
    {
        global $connection;
        if(empty($username) || empty($password) || empty($email)) return 'Create: Insufficient info provided';
        
        // Valid usernames
        if(!preg_match('/^[a-zA-Z0-9-_]+$/', $username)) return 'Invalid username specified!  Allowed characters: a-z, 0-9, - _';
        if(strlen($username) < 3) return 'Usernames must be at least 3 characters!';
        
        // Password length (minimum 5 characters)
        if(strlen($password) < 5) return 'Passwords must be at least 5 characters!';
        
        // Password strength
        if($password == '123' || $password == '1234' || $password == '12345' || $password == 'password' || $password == 'pass123' || $password == 'pass1234' || $password == 'pass12345') return 'Sorry, please choose a real password!';
        
        // Check existing username
        $result_ck  = @mysqli_query($connection, "SELECT id FROM admins WHERE username = '$username' LIMIT 1");
        $row_ck     = mysqli_fetch_row($result_ck);
        if($row_ck[0]) return $lang['user_exists'];
        
        // Setup pass
        $password = base64_encode(sha1('ZzaX'.$password.'GPX88'));
        
        @mysqli_query($connection, "INSERT INTO admins (date_created,username,password,email_address,first_name,last_name) VALUES(NOW(),'$username','$password','$email','$first_name','$last_name')") or die('Failed to create user: '.mysqli_error($connection));
        
        
        // Output
        return 'success';
    }

}
