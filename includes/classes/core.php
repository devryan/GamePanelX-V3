<?php
class Core
{
    public function dbconnect()
    {
        if(!isset($config['db_host'])) require(__DIR__.'/../../configuration.php');
        
        $db = @mysql_connect($settings['db_host'],$settings['db_username'],$settings['db_password']) or die('ERROR: Failed to connect to the MySQL database');
        @mysql_select_db($settings['db_name']) or die('ERROR: Failed to select the MySQL database');
        global $db;
        
        return true;
    }
    
    
    
    // Get an array of control panel settings (optionally specify a setting)
    public function getsettings($setting=false)
    {
        // Return a value for a single setting
        if($setting)
        {
            $result_cfg   = @mysql_query("SELECT config_value FROM configuration WHERE config_setting = '$setting' ORDER BY last_updated DESC LIMIT 1");
            $row_cfg      = mysql_fetch_row($result_cfg);
            
            return $row_cfg[0];
        }
        // Get info for all settings
        else
        {
            // Get settings
            $result_cfg   = @mysql_query("SELECT last_updated_by,last_updated,config_setting,config_value FROM configuration ORDER BY config_setting ASC");
            $settings_arr = array();
            
            while($row_cfg = mysql_fetch_array($result_cfg))
            {
                $cfg_setting  = $row_cfg['config_setting'];
                $cfg_value    = $row_cfg['config_value'];
                
                $settings_arr[$cfg_setting] = stripslashes($cfg_value);
            }
            
            // Return array of settings
            return $settings_arr;
        }
    }
    
    
    
    
    
    // Generate random text
    public function genstring($length=false)
    {
        if(!$length) $length = 16;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters))];
        }
        return $string;
    }
}
