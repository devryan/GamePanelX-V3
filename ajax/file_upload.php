<?php
// Upload a file via ajax
session_start();
require('checklogin.php'); // Make sure they're logged-in

// Make sure they actually own the server they're uploading to
if(!isset($_SESSION['gpx_admin']))
{
    $gpx_srvid  = $_SESSION['gamesrv_id'];
    $gpx_userid = $_SESSION['gpx_userid'];
    
    $result_owns  = @mysql_query("SELECT id FROM servers WHERE id = '$gpx_srvid' AND userid = '$gpx_userid' LIMIT 1") or die('Failed to check ownership');
    $row_owns     = mysql_fetch_row($result_owns);
    if(empty($row_owns[0])) die('You do not have access to this server!');
}


##############################################################

require('../configuration.php');
require(DOCROOT.'/includes/classes/upload.php');
    

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array('jpg', 'jpeg', 'xml', 'png', 'txt', 'cfg');

// max file size in bytes
$sizeLimit = 10 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);

$this_cur_dir = $_SESSION['curdir'];
$this_root    = DOCROOT . '/_SERVERS/' . $_SESSION['gamesrv_root'];

// Upload to gameserver dir
if(isset($_SESSION['gamesrv_root']))
{
    if(isset($_SESSION['curdir'])) $upload_dir = $this_root . '/' . $this_cur_dir . '/';
    else  $upload_dir = $this_root . '/';
}
else
{
    $upload_dir = DOCROOT . '/uploads/';
}

// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
$result = $uploader->handleUpload($upload_dir);



#$cur_dir  = $_SESSION['curdir'];
#echo "CurDir: $cur_dir<br>";


// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

?>
