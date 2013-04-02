<?php
// GamePanelX V3 API
error_reporting(E_ERROR);
$api_class  = $_GET['class'];
$api_key    = $_GET['key'];
$api_action = $_GET['action'];
if(empty($api_key)) die('No API key specified (&key=)');

$allowed_classes  = array('users','servers');
if(empty($api_class)) die('No class given (&class=)');
elseif(!in_array($api_class, $allowed_classes)) die('Invalid class given');
elseif(empty($api_action)) die('No action (&action=) given');

if(!defined('DOCROOT')) require('../configuration.php');

// Check API key
require(DOCROOT.'/includes/classes/core.php');
$Core = new Core;
$our_api_key  = $Core->getsettings('api_key');

if($our_api_key != $api_key) die('Invalid API key specified!');

// Setup DB
$Core->dbconnect();

########################################################################

// Require the main class file
require($api_class.'.php');

?>
