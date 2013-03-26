<?php
require('checkallowed.php'); // Check logged-in
?>

<div class="infobox" style="display:none;"></div>

<span style="font-size:10pt;"><?php echo $lang['cloud_topmsg']; ?></span><br />

<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['cloud_avail']; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="0" cellspacing="0" align="center" width="900" class="box_table" id="cloudtbl" style="text-align:left;">
  <tr>
    <td width="35">&nbsp;</td>
    <td width="300"><b><?php echo $lang['name']; ?></b></td>
    <td width="350"><b><?php echo $lang['desc']; ?></b></td>
    <td width="120"><b><?php echo $lang['last_updated']; ?></b></td>
    <td width="80">&nbsp;</td>
  </tr>
</table>

<script type="text/javascript">
$(document).ready(function(){
    // Load cloud games
    cloud_getall();
});
</script>
