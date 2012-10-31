<?php
require('checkallowed.php'); // No direct access
?>

<div class="tabs">
    <div class="tab" onClick="javascript:mainpage('gamesedit','<?php echo $url_id; ?>');" <?php if($tab == 'settings') echo ' style="background:#306EFF"'; ?>>
        <div style="float:left;margin-top:5px;"><img src="../images/icons/medium/edit.png" width="24" height="24" border="0" /></div> <?php echo $lang['settings']; ?>
    </div>
    <div class="tab" onClick="javascript:mainpage('templates','<?php echo $url_id; ?>');"<?php if($tab == 'templates') echo ' style="background:#306EFF"'; ?>>
        <div style="float:left;margin-top:5px;"><img src="../images/icons/medium/template.png" width="24" height="24" border="0" /></div> <?php echo $lang['server_templates']; ?>
    </div>
    <div class="tab" onClick="javascript:game_tab_startup(<?php echo $url_id; ?>)"<?php if($tab == 'startup') echo ' style="background:#306EFF"'; ?>>
        <div style="float:left;margin-top:5px;"><img src="../images/icons/medium/startup.png" width="24" height="24" border="0" /></div> <?php echo $lang['startup']; ?>
    </div>
</div>
