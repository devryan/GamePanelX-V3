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
<style media="screen">
.container {
position: absolute;
top: 50%;
left: 50%;
-webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
width: 40rem;
height: 25rem;
background: #3e3e3e;
box-shadow: 0 30px 20px -20px rgba(0, 0, 0, 0.3);
box-sizing: border-box;
}
header {
width: 100%;
height: 3rem;
padding-left: 2rem;
background: -webkit-linear-gradient(45deg, #FF512F, #DD2476);
background: linear-gradient(45deg, #FF512F, #DD2476);
background-size: 300% 300%;
color: #fff;
clear: both;
box-sizing: border-box;
-webkit-animation: coolgrad 6s ease infinite;
        animation: coolgrad 6s ease infinite;
}
header .fa {
font-size: 1.4rem;
height: 3rem;
line-height: 3rem;
float: left;
-webkit-animation: spin 4s ease infinite;
        animation: spin 4s ease infinite;
}
header .title {
position: relative;
height: 3rem;
line-height: 3rem;
font-weight: bold;
float: right;
padding: 0 2rem 0 1rem;
background: rgba(17, 17, 17, 0.35);
}
header .title:after {
content: "";
position: absolute;
right: 100%;
width: 0;
height: 0;
border-left: 1rem solid transparent;
border-bottom: 3rem solid rgba(17, 17, 17, 0.2);
}
header .title:before {
content: "";
position: absolute;
right: 100%;
width: 0;
height: 0;
border-left: 1rem solid transparent;
border-bottom: 3rem solid rgba(25, 25, 25, 0.2);
border-right: 2rem solid rgba(25, 25, 25, 0.2);
}
.content-wrapper {
width: 50%;
position: absolute;
top: 50%;
left: 50%;
-webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
top: calc(54%);
}
.content-wrapper .section {
position: relative;
height: 2rem;
margin-bottom: 1rem;
clear: both;
}
.content-wrapper .section label {
float: left;
height: 2rem;
line-height: 2rem;
}
.content-wrapper .section input[type="checkbox"],
.content-wrapper .section select {
float: right;
}
.content-wrapper .section input[type="checkbox"] {
display: none;
}
.content-wrapper .section input[type="checkbox"] {
display: inline-block;
width: 1.2rem;
height: 2rem;
vertical-align: middle;
background: red;
cursor: pointer;
}
.content-wrapper .section select {
height: 2rem;
padding: 0 1rem;
border-radius: 4px;
box-sizing: border-box;
}
@-webkit-keyframes spin {
from {
  -webkit-transform: rotate(0deg);
          transform: rotate(0deg);
}
to {
  -webkit-transform: rotate(360deg);
          transform: rotate(360deg);
}
}
@keyframes spin {
from {
  -webkit-transform: rotate(0deg);
          transform: rotate(0deg);
}
to {
  -webkit-transform: rotate(360deg);
          transform: rotate(360deg);
}
}
@-webkit-keyframes coolgrad {
0% {
  background-position: 0% 75%;
}
50% {
  background-position: 100% 26%;
}
100% {
  background-position: 0% 75%;
}
}
@keyframes coolgrad {
0% {
  background-position: 0% 75%;
}
50% {
  background-position: 100% 26%;
}
100% {
  background-position: 0% 75%;
}
}

</style>


<div class="container" style="color:white !important;font-size:15px;width:46rm;">

  <header>
    <div class="fa fa-gear"></div>
    <div class="title">Sub-User Settings</div>
  </header>

  <div class="content-wrapper" style="width:76%">
    <form id="SubuserForm" action="#" method="post">
    <div class="section">
      <p>Sub-Users allow one additional owner per server, If you put a serverid and a subowner id if this record already exists it will overwrite.</p>
      <br>
      <br>
    </div>
    <div class="section">
        <label for="ServerID">The Server to give a secondary owner too</label><br><br>
          <input  name="ServerID"  id="ServerID" value="" tabindex="5"  type="text">
          <br>
        </div>
        <br>
        <div class="section">
          <label for="UserID2">The user to be a secondary owner for the server id above</label>
          <input  name="UserID2"  id="UserID2" value="" tabindex="5"  type="text">
     </div>
    <div class="section">
        <input type="submit" name="Update" id="update" value="Update" style="margin-top: 23px;" />
      </form>
      </select>
    </div>
  </div>
</div>



<!--

<form id="SubuserForm" action="#" method="post">
    <input  name="ServerID"  id="ServerID" value="" tabindex="5"  type="text">
    <input  name="UserID2"  id="UserID2" value="" tabindex="5"  type="text">
	<input type="submit" name="Update" id="update" value="Update" />
 </form> -->






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
    data:{ serverid : serverid , UserID2 : UserID2, somevar : "yes"  },
    url:'../ajax/addsubuser.php',
    success:function(data) {
      alert(data);
    }
  });
});
</script>
