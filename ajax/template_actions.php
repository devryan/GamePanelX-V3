<?php
$forceadmin = 1; // Admins only
require('checkallowed.php'); // No direct access

error_reporting(E_ERROR);

// actions
$url_id       = $GPXIN['id'];
$url_netid    = $GPXIN['netid'];
$url_do       = $GPXIN['do']; // save or delete
$url_descr    = $GPXIN['desc'];
$url_default  = $GPXIN['default'];

require(DOCROOT.'/includes/classes/templates.php');
$Templates  = new Templates;


// Create template
if($url_do == 'create')
{
    $url_gameid      = $GPXIN['gameid'];
    $url_file_path   = $GPXIN['file_path'];
    $url_description = $GPXIN['description'];

    echo $Templates->create($url_netid,$url_gameid,$url_file_path,$url_description,$url_default);
}

// Save template
elseif($url_do == 'save')
{
    // Get this game ID
    $result_gid   = $GLOBALS['mysqli']->query("SELECT cfgid FROM templates WHERE id = '$url_id'");
    $row_gid      = $result_gid->fetch_row();
    $this_gameid  = $row_gid[0];
    
    // If default, make all others not default
    if($url_default) $GLOBALS['mysqli']->query("UPDATE templates SET is_default = '0' WHERE cfgid = '$this_gameid' AND netid = '$url_netid'") or die('Failed to update template settings (1)');
    
    // Update values
    $GLOBALS['mysqli']->query("UPDATE templates SET is_default = '$url_default',description = '$url_descr' WHERE id = '$url_id'") or die('Failed to update template settings (2)');
    
    echo 'success';
}

// Delete Template
elseif($url_do == 'delete')
{
    echo $Templates->delete($url_id);
}

// Get remote statuses of running tpls on each specific server
elseif($url_do == 'checkdone')
{
    require(DOCROOT.'/includes/classes/network.php');
    $Network  = new Network;
    
    // Get list of unfinished templates
    $result_unf = $GLOBALS['mysqli']->query("SELECT id,netid,steam_percent,status FROM templates WHERE status = 'running' OR status = 'steam_running' ORDER BY id ASC");
    $total_rows = $result_unf->num_rows;
    if($total_rows > 1) $total_rows = $total_rows - 1; // Change for array counting
    $cntr       = 1;
    
    $netids     = array();
    $orig_netid = '';
    $tpl_list   = '';
    
    if($total_rows)
    {
        while($row_unf  = $result_unf->fetch_array())
        {
            $this_tpl       = $row_unf['id'];
            $this_netid     = $row_unf['netid'];
            $cur_status     = $row_unf['status'];
            
            // Set orig id first run
            if($cntr == 1) $orig_netid = $this_netid;
            
            // New net server or last in output; SSH into previous and run all tpl ids
            if($orig_netid != $this_netid && $cntr >= 1)
            {
                // SSH into previous net server
                $tpl_list = substr($tpl_list, 0, -1); // Lose last comma
                $net_arr  = $Network->netinfo($orig_netid);
                $net_cmd  = "CheckTemplates -i \"$tpl_list\"";
                
                $cmd_out  = $Network->runcmd($orig_netid,$net_arr,$net_cmd,true);
                
                // Decode JSON response
                $out_arr  = json_decode($cmd_out, true);
                
                foreach($out_arr as $this_tplid => $this_status)
                {
                    if($this_status != 'running' && $this_status != 'complete') continue; // Skip if bad syntax
                    
                    // Only if newer
                    if($cur_status != $this_status)
                    {
                        $updated  = true;
                        $GLOBALS['mysqli']->query("UPDATE templates SET status = '$this_status' WHERE id = '$this_tplid'") or die('Failed to update template check!');
                    }
                }
                
                
                // Save new netid and tplid for later
                $orig_netid = $this_netid;
                $tpl_list  .= $this_tpl . ',';
            }
            elseif($total_rows == $cntr)
            {
                $tpl_list  .= $this_tpl . ',';
                
                // SSH into net server
                $tpl_list = substr($tpl_list, 0, -1); // Lose last comma
                $net_arr  = $Network->netinfo($this_netid);
                $net_cmd  = "CheckTemplates -i \"$tpl_list\"";
                
                $cmd_out  = $Network->runcmd($this_netid,$net_arr,$net_cmd,true);
                
                // Decode JSON response
                $out_arr  = json_decode($cmd_out, true);
                
                foreach($out_arr as $this_tplid => $this_status)
                {
                    if($this_status != 'running' && $this_status != 'complete') continue; // Skip if bad syntax
                    
                    // Only if newer
                    if($cur_status != $this_status)
                    {
                        $updated  = true;
                        $GLOBALS['mysqli']->query("UPDATE templates SET status = '$this_status' WHERE id = '$this_tplid'") or die('Failed to update template check!');
                    }
                }
                
                #echo '<pre>';
                #var_dump($out_arr);
                #echo '</pre>';
            }
            
            $cntr++;
        }
    }
    
    
    // Output
    if($updated) echo 'updated';
    else echo 'success';
}

?>
