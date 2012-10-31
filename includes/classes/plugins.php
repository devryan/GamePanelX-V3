<?php
/*
* GamePanelX
* 
* Class for plugin setups
* 
* Explanation of execution: 
* setup_actions() is called at the top of each page, and includes all active plugins' main PHP file.
* If that php file calls set_action(), we store their function name and where it is to be called in a global variable.
* When do_action() is called internally, we run all actions for this specific spot.
* 
*/
class Plugins
{
    // Define list of available actions
    public function action_list()
    {
        $action_list  = array(
                            'index_init','index_head','index_body','index_body_end','index_end',
                            'home_top','home_bottom',
                            'settings_top','settings_table',
                            'games_top','games_table','games_bottom',
                            'servers_top','servers_table','servers_bottom',
                            'users_top','users_table','users_bottom'
                        );
        
        return $action_list;
    }
    
    
    // For Plugin Author use
    public function set_action($action_name,$func_name)
    {
        $action_list = $this->action_list();
        if(!in_array($action_name, $action_list))
        {
            if(GPXDEBUG) echo '<b>Plugin Error:</b> SetAction: Invalid action specified ('.$action_name.') on function "'.$func_name.'()"!';
            return false;
        }
        
        global $actions;
        
        // Create an array of function handlers if it doesn't already exist  
        if(!isset($actions[$action_name])) $actions[$action_name] = array();
        global $actions;
        
        // append the current function to the list of function handlers  
        $actions[$action_name][] = $func_name;
    }
    
    
    // For Internal use - run all plugins associated with this action
    public function do_action($action_name)
    {
        #require('../../configuration.php');
        #if(GPXDEBUG) echo "Running through Action: $action_name<br />";
        
        $action_list = $this->action_list();
        
        if(!in_array($action_name, $action_list) || empty($action_name))
        {
            if(GPXDEBUG) echo '<b>Plugin Error:</b> DoAction: Invalid action specified ('.$action_name.')!';
            return false;
        }
        
        global $actions;
  
        #echo '<pre>';
        #var_dump($action_list);
        #echo '</pre>';
        
        if(isset($actions[$action_name]))
        {
            if(GPXDEBUG) echo "There are functions for Action <b>$action_name</b><br />";
            
            // call each function handler associated with this hook  
            foreach($actions[$action_name] as $function)
            {
                if(GPXDEBUG) echo "Calling Function: $function<br />";
                echo call_user_func($function);  
            }  
        }
    }
    
    
    // Include all active function pages
    public function setup_actions()
    {
        if(!defined('DOCROOT')) require('../../configuration.php');
        
        #echo '<pre>';
        #var_dump($_SESSION['gpx_plugins']);
        #echo '</pre>';
        
        // Use session list of plugins
        if(count($_SESSION['gpx_plugins']))
        {
            foreach($_SESSION['gpx_plugins'] as $plugin)
            {
                // Ignore plugins with no proper dir/file.php
                $plugin_loc = DOCROOT . '/plugins/' . $plugin . '/' . $plugin . '.php';
                
                if(!file_exists($plugin_loc))
                {
                    if(GPXDEBUG) echo 'DEBUG: Skipping plugin "'.$plugin.'": No plugin file/dir found ...<br />';
                    
                    continue;
                }
                
                // Include plugin page (use ob_start() to not allow any stray echo's etc)
                ob_start();
                include_once($plugin_loc);
                ob_end_clean();
                
                unset($plugin_loc);
            }
        }
        
        return true;
    }
    
    
    
    
    // Reset session plugin info
    public function reset_session()
    {
        $result_ac  = @mysql_query("SELECT DISTINCT intname FROM plugins WHERE active = '1' ORDER BY name ASC") or die('Failed to query for plugins: '.mysql_error());
        $total_ac   = mysql_num_rows($result_ac);
        
        // Reset sess
        if($total_ac)
        {
            // Store active plugin names in an array for later use
            $_SESSION['gpx_plugins']  = array();
            while($row_ac = mysql_fetch_array($result_ac))
            {
                $_SESSION['gpx_plugins'][]  = $row_ac['intname'];
            }
        }
        // Destroy array
        else
        {
            unset($_SESSION['gpx_plugins']);
            $_SESSION['gpx_plugins']  = array();
        }
    }
}
