<?php
$forceadmin = 1; // Admins only
require('checkallowed.php'); // No direct access
?>

<div class="page_title">
    <div class="page_title_icon"><img src="../images/icons/medium/network.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['network']; ?></div>
</div>

<div class="tabs">
    <div class="tab" onClick="javascript:mainpage('networkedit','<?php echo $url_id; ?>');" style="margin-left:0px;<?php if($tab == 'edit') echo 'background:#306EFF;'; ?>">
        <div style="float:left;margin-top:5px;"><img src="../images/icons/medium/edit.png" width="24" height="24" border="0" /></div> <?php echo $lang['edit']; ?>
    </div>
    <?php
    // Allow adding IPs if a parent server
    if($net_parentid == 0)
    {
    ?>
    <div class="tab" onClick="javascript:mainpage('networkips','<?php echo $url_id; ?>');"<?php if($tab == 'ips') echo ' style="background:#306EFF"'; ?>>
        <div style="float:left;margin-top:5px;"><img src="../images/icons/medium/template.png" width="24" height="24" border="0" /></div> <?php echo $lang['ips']; ?>
    </div>
    <?php } ?>
</div>
