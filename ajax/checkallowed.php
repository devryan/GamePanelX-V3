<?php
// No direct requests
require('../includes/db.php');
if(!defined('DOCROOT')) die('You must be logged-in to do that!');

// Forcing admin-only usage
if($forceadmin == 1) {
	// Normal clients but not admins
	if(isset($_SESSION['gpx_userid']) && !isset($_SESSION['gpx_admin'])) die('Sorry, you must be an admin to view this page.');

	// No login
	elseif(!isset($_SESSION['gpx_userid'])) die('You must be logged-in to do that!');
}

?>
