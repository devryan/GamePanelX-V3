<?php
$forceadmin = 1; // Admins only
require('checkallowed.php'); // No direct access

error_reporting(E_ERROR);

// Get status info on the 20 most recent templates
#WHERE (status = 'steam_running' OR status = 'running') 
$result = @mysqli_query($connection, "SELECT id,steam_percent,status FROM templates ORDER BY id DESC LIMIT 20") or die('Failed to query: '.mysqli_error($connection));
$newarr = array();

while($row  = mysqli_fetch_array($result))
{
    #$tpl_id     = $row['id'];
    #$tpl_status = $row['status'];
    #$steam_perc = $row['steam_percent'];
    
    $newarr[] = $row;
}

echo json_encode($newarr);

?>
