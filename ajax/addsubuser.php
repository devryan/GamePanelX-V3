<?php
$servername = "localhost";
$username = "root";
$password = "flareservers";
$dbname = "gamepaneltest";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
if(isset($_REQUEST['somevar'])){
  $serverid = $_POST['serverid'];
  $UserID2 = $_POST['UserID2'];
if($UserID2==0) {
  echo 'User ID not found';
}
if($serverid==0) {
  echo 'Server ID not found';
}
$insert = mysqli_query($conn , "UPDATE servers SET userid2 = '$UserID2' WHERE id = '$serverid' ");
if(!$insert) {
   echo "Failed";
} else {
   echo "&nbsp; Server Details Updated Successfully";
}
}
?>
