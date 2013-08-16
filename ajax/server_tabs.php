<?php
require('checkallowed.php'); // No direct access

// Get server info
require(DOCROOT.'/includes/classes/servers.php');
$Servers    = new Servers;
$srvinfo    = $Servers->getinfo($url_id);

// Store gameserver root in session (without $HOME or docroot for compatibility)
#if(!isset($_SESSION['gamesrv_root']) || $_SESSION['gamesrv_id'] != $url_id)
#{
#    $_SESSION['gamesrv_root'] = 'accounts/'.$srvinfo[0]['username'].'/'.$srvinfo[0]['ip'].':'.$srvinfo[0]['port'];
#    $_SESSION['gamesrv_id']   = $url_id;
#}

// Update the session everytime to ensure things are fresh
$_SESSION['gamesrv_root'] = 'accounts/'.$srvinfo[0]['username'].'/'.$srvinfo[0]['ip'].':'.$srvinfo[0]['port'];
$_SESSION['gamesrv_id']   = $url_id;
?>

<div class="page_title">
    <div class="page_title_icon"><img src="<?php echo $relpath; ?>images/icons/medium/servers.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['servers']; ?></div>
</div>

<div class="tabs">
    <div class="tab" onClick="javascript:server_tab_info(<?php echo $url_id; ?>);" <?php if($tab == 'info') echo ' style="background:#306EFF;margin-left:0px;"'; else echo ' style="margin-left:0px;"'; ?>>
        <div style="float:left;margin-top:5px;"><img src="<?php echo $relpath; ?>images/icons/medium/info.png" width="24" height="24" border="0" /></div><?php echo $lang['info']; ?>
    </div>
    <div class="tab" onClick="javascript:server_tab_settings(<?php echo $url_id; ?>);" <?php if($tab == 'settings') echo ' style="background:#306EFF"'; ?>>
        <div style="float:left;margin-top:5px;"><img src="<?php echo $relpath; ?>images/icons/medium/edit.png" width="24" height="24" border="0" /></div> <?php echo $lang['settings']; ?>
    </div>
    
    <?php
    // Check user permission
    if(isset($_SESSION['gpx_admin']) || $_SESSION['gpx_perms']['perm_files'] == '1')
    {
    ?>
    <div class="tab" onClick="javascript:server_tab_files(<?php echo $url_id; ?>);"<?php if($tab == 'files') echo ' style="background:#306EFF"'; ?>>
        <div style="float:left;margin-top:5px;"><img src="<?php echo $relpath; ?>images/icons/medium/files.png" width="24" height="24" border="0" /></div> <?php echo $lang['files']; ?>
    </div>
    <?php
    }
    
    // If startup and perms
    if($srvinfo[0]['startup'])
    {
        // Check user permission
        if(isset($_SESSION['gpx_admin']) || $_SESSION['gpx_perms']['perm_startup'] == '1')
        {
        ?>
        <div class="tab" onClick="javascript:server_tab_startup(<?php echo $url_id; ?>);"<?php if($tab == 'startup') echo ' style="background:#306EFF"'; ?>>
            <div style="float:left;margin-top:5px;"><img src="<?php echo $relpath; ?>images/icons/medium/startup.png" width="24" height="24" border="0" /></div> <?php echo $lang['startup']; ?>
        </div>
        <?php
        }
    }
    ?>
</div>
