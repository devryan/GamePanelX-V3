<?php
// GamePanelX V3 API
error_reporting(E_ERROR);

// Allow temporary swapping between GET/POST
$GPXIN = array();
if(!empty($_GET)) {
        foreach($_GET as $gets => $getval) {
            $GPXIN[$gets] = $getval;
        }
}
elseif(!empty($_POST)) {
        foreach($_POST as $posts => $postval) {
            $GPXIN[$posts] = $postval;
        }
}

$api_class  = $GPXIN['class'];
$api_key    = $GPXIN['key'];
$api_action = $GPXIN['action'];
if(empty($api_key)) die('No API key specified (&key=)');

$allowed_classes  = array('users','servers');
if(empty($api_class)) die('No class given (&class=)');
elseif(!in_array($api_class, $allowed_classes)) die('Invalid class given');
elseif(empty($api_action)) die('No action (&action=) given');

if(!defined('DOCROOT')) require('../configuration.php');

// Check API key
require(DOCROOT.'/includes/classes/core.php');
$Core = new Core;
$Core->dbconnect();
$our_api_key  = $Core->getsettings('api_key');

if($our_api_key != $api_key) die('Invalid API key specified!');

// Setup DB
$Core->dbconnect();

########################################################################

// Automatically escape all user input
if(isset($_GET['action']))  $GPXIN = $Core->escape_inputs($_GET,false);
if(isset($_POST['action'])) $GPXIN = $Core->escape_inputs($_POST,false);

########################################################################

// Require the main class file
if(file_exists($api_class.'.php') require($api_class.'.php');
else die('That API action is not allowed.');

?>
