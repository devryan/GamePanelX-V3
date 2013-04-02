<?php
require('checkallowed.php'); // No direct access
?>

<div class="page_title">
    <div class="page_title_icon"><img src="<?php echo $relpath; ?>/images/icons/medium/accounts.png" border="0" /></div>
    <div class="page_title_text"><?php echo $usr_usrname; ?></div>
</div>

<div class="tabs">
    <div class="tab" onClick="javascript:mainpage('viewuser',<?php echo $url_id; ?>);" <?php if($tab == 'info') echo ' style="background:#306EFF;margin-left:0px;"'; else echo ' style="margin-left:0px;"'; ?>>
        <div style="float:left;margin-top:5px;"><img src="<?php echo $relpath; ?>/images/icons/medium/info.png" width="24" height="24" border="0" /></div><?php echo $lang['info']; ?>
    </div>
    <div class="tab" onClick="javascript:mainpage('userperms',<?php echo $url_id; ?>);" <?php if($tab == 'perms') echo ' style="background:#306EFF"'; ?>>
        <div style="float:left;margin-top:5px;"><img src="<?php echo $relpath; ?>/images/icons/medium/edit.png" width="24" height="24" border="0" /></div> <?php echo $lang['permissions']; ?>
    </div>
</div>
