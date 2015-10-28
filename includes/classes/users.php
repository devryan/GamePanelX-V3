<?php
// User Accounts class
class Users
{
    // Create a new user account
    public function create($username,$password,$email,$first_name,$last_name)
    {
        if(empty($username) || empty($password) || empty($email)) return 'Create: Insufficient info provided';
        
        require(DOCROOT.'/lang.php');
        require(DOCROOT.'/includes/password_compat/lib/password.php');

        // No dots in username
        if(preg_match('/\.+/', $username)) return 'Invalid username specified, no dots allowed!';
        
        // Valid usernames
        if(!preg_match('/^[a-zA-Z0-9-_]+$/', $username)) return 'Invalid username specified!  Allowed characters: a-z, 0-9, - _';
        if(strlen($username) < 3) return 'Usernames must be at least 3 characters!';
        
        // Password length (minimum 5 characters)
        if(strlen($password) < 5) return 'Passwords must be at least 5 characters!';
        
        // Check existing username
        $result_ck  = @mysql_query("SELECT id FROM users WHERE username = '$username' AND deleted = '0' LIMIT 1");
        $row_ck     = mysql_fetch_row($result_ck);
        if($row_ck[0]) return $lang['user_exists'];
	
	# Setup storing of user password
	$pass_safe = password_hash($password, PASSWORD_DEFAULT);

        @mysql_query("INSERT INTO users (date_created,username,password,email_address,first_name,last_name) VALUES(NOW(),'$username','$pass_safe','$email','$first_name','$last_name')") or die('Failed to create user: '.mysql_error());
        $this_userid  = mysql_insert_id();
        
        
        // Output
        return $this_userid;
    }
    
    
    // Update a user account
    public function update($userid,$username,$password,$email,$first_name,$last_name,$language,$theme)
    {
	require(DOCROOT.'/includes/password_compat/lib/password.php');

        if(empty($userid)) return 'No User ID given!';
        if(empty($language)) $language = 'english'; // Default to english
        if(empty($theme)) $theme = 'default'; // Default to 'default' theme
        
        if(isset($_SESSION['gpx_admin'])) {
            if(empty($userid) || empty($username)) die('Insufficient info given!');
        }
        if(!isset($settings['db_host'])) require(DOCROOT.'/configuration.php');

	# Setup storing of user password
        if(!empty($password)) {
	    $pass_safe = password_hash($password, PASSWORD_DEFAULT);
	    $sql_pass = ",password='$pass_safe'";
	}
	else {
	    $sql_pass = '';
	}

        // No dots in username
        if(preg_match('/\.+/', $username)) return 'Invalid username specified!';
        
        // No HTML tags
        $email      = strip_tags($email);
        $first_name = strip_tags($first_name);
        $last_name  = strip_tags($last_name);
        $language   = strip_tags($language);
        $theme      = strip_tags($theme);
        
        // Get current username before any changes
        $result_cur = @mysql_query("SELECT username FROM users WHERE id = '$userid' LIMIT 1");
        $row_cur    = mysql_fetch_row($result_cur);
        $cur_username = $row_cur[0];
        
        require(DOCROOT.'/includes/classes/network.php');
        $Network  = new Network;
        
        ################################################################
        
        // Admin updating a user
        if(isset($_SESSION['gpx_admin']))
        {
            if($cur_username != $username || !empty($password)) {
                @mysql_query("UPDATE users SET last_updated = NOW(),theme = '$theme',language = '$language',username = '$username',email_address = '$email',first_name = '$first_name',last_name = '$last_name'$sql_pass WHERE id = '$userid'") or die('Failed to update user');
            }
            // Otherwise update basic settings
            else {
                @mysql_query("UPDATE users SET last_updated = NOW(),theme = '$theme',language = '$language',email_address = '$email',first_name = '$first_name',last_name = '$last_name' WHERE id = '$userid'") or die('Failed to update user');
            }
        }
        
        // User updating their account
        else
        {
            @mysql_query("UPDATE users SET last_updated = NOW(),theme = '$theme',language = '$language',email_address = '$email',first_name = '$first_name',last_name = '$last_name'$sql_pass WHERE id = '$userid'") or die('Failed to update your account!');
            
            // Update session
            $_SESSION['gpx_lang']   = strtolower($language);
            $_SESSION['gpx_theme']  = strtolower($theme);
        }
        
        #############################################
        
        // Change password on network servers
        if(!empty($password))
        {
            $result_net = @mysql_query("SELECT id FROM network WHERE parentid = '0' AND is_local = '0' ORDER BY ip ASC");
            
            // Setup crypt pass
            $crypt_pass = crypt($password);
            
            while($row_net  = mysql_fetch_array($result_net))
            {
                $netid    = $row_net['id'];
                $net_arr  = $Network->netinfo($netid);
                
                // Setup create command
				# WAS $username for -u
                $net_cmd  = "ChangePassword -u '$cur_username' -p '$crypt_pass'";
                $create_result  = $Network->runcmd($netid,$net_arr,$net_cmd,true);
                
                if($create_result != 'success') return 'Failed to change password on network server ('.$netid.'): '.$create_result.'!';
            }
        }
        
        ############################################
        
        // Update userdir on gameserver side
        if($cur_username != $username && isset($_SESSION['gpx_admin']))
        {
            if(empty($cur_username) || empty($username)) return 'A username was left empty!';
            
            // Run this change everywhere
            $result_net = @mysql_query("SELECT id FROM network WHERE parentid = '0' AND is_local = '0' ORDER BY ip ASC");
            
            while($row_net  = mysql_fetch_array($result_net))
            {
                $netid    = $row_net['id'];
                $net_arr  = $Network->netinfo($netid);
                
                // Setup create command
                $net_cmd  = "UsernameChange -o '$cur_username' -n '$username'";
                $create_result  = $Network->runcmd($netid,$net_arr,$net_cmd,true);
                
                if($create_result != 'success') return 'Failed to change username on network server ('.$netid.'): '.$create_result.'!';
            }
        }
        
        return 'success';
    }
    
    
    
    
    
    // Delete a user account
    public function delete($userid)
    {
        if(empty($userid)) return 'No User ID given!';
        
        // Check if user even exists
        $result_uex   = @mysql_query("SELECT username FROM users WHERE id = '$userid' LIMIT 1");
        $row_uex      = mysql_fetch_row($result_uex);
        $uex_username = $row_uex[0];
        if(empty($uex_username)) return 'That user account no longer exists!';
        
        // Not if they have servers
        $result_net = @mysql_query("SELECT netid FROM servers WHERE userid = '$userid' ORDER BY id DESC LIMIT 1");
        $row_net    = mysql_fetch_row($result_net);
        $latest_netid = $row_net[0];
        
        if($latest_netid) return 'This user has server(s) on their account!  Move the server(s) to another user or delete them and try again.';
        
        // Admins only
        if(isset($_SESSION['gpx_admin'])) @mysql_query("UPDATE users SET deleted = '1' WHERE id = '$userid'") or die('Failed to delete the user');
        else return 'You are not authorized to do this!';
        
        #############################################
        
        // Delete SSO account
        $result_net = @mysql_query("SELECT id FROM network WHERE parentid = '0' AND is_local = '0' ORDER BY ip ASC");
        require(DOCROOT.'/includes/classes/network.php');
        $Network  = new Network;
        
        while($row_net  = mysql_fetch_array($result_net))
        {
            $netid    = $row_net['id'];
            $net_arr  = $Network->netinfo($netid);
            
            // Setup delete command
            $net_cmd  = "DeleteUser -u '$uex_username'";
            $delete_result  = $Network->runcmd($netid,$net_arr,$net_cmd,true);
            
            // Account didn't exist...don't warn
            if($delete_result == 'That user does not exist, exiting.') return 'success';
            elseif($delete_result != 'success') return 'Failed to delete user on network server '.$netid.': '.$delete_result;
        }
        
        #############################################
        
        return 'success';
    }
}
