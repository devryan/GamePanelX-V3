<?php
error_reporting(E_ERROR);
session_start();
if(!isset($_SESSION['gpx_userid']) || !isset($_SESSION['gpx_admin'])) die('Please login');

$Plugins->do_action('home_top'); // Plugins

// Debug info
if(GPXDEBUG)
{
    // Get version
    $result_vr    = $GLOBALS['mysqli']->query("SELECT config_value FROM configuration WHERE config_setting = 'version' LIMIT 1");
    $row_vr       = $result_vr->fetch_row();
    $gpx_version  = $row_vr[0];

    echo '<b>NOTICE:</b> Debug mode has been enabled in configuration.php.<br />';
    echo 'DEBUG: Master Version '.$gpx_version.'<br />';
    echo 'DEBUG: Document Root: '.DOCROOT.'<br />';
    if($GLOBALS['mysqli']->error) echo 'DEBUG: Last MySQL error: '.$GLOBALS['mysqli']->error.'<br />';
}
?>
<div class="infobox" style="display:none;"></div>




<form id="SubuserForm" action="#" method="post">
    <input  name="ServerID"  id="ServerID" value="" tabindex="5"  type="text">
    <input  name="UserID2"  id="UserID2" value="" tabindex="5"  type="text">
	<input type="submit" name="Update" id="update" value="Update" />
 </form>






<script>
// Check for system updates
$(document).ready(function(){
    setTimeout("cloud_check_updates()", 500);
});
$("#update").click(function(e) {
  e.preventDefault();
  var serverid = $("#ServerID").val();
  var UserID2 = $("#UserID2").val();
  console.log(serverid);
  console.log(UserID2);
  $.ajax({
    type:'POST',
    data: { serverid : serverid }, { UserID2 : UserID2 },
    url:'../ajax/addsubuser.php',
    success:function(data) {
      alert(data);
    }
  });
});
</script>

<div id="homeic_boxes">
    Hello you tit!
</div>
