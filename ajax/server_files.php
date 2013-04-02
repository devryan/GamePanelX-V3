<?php
require('checkallowed.php'); // No direct access

// URL ID
$url_id = $GPXIN['id'];
$gpx_srvid=$url_id; require(DOCROOT.'/checkallowed.php'); // Check login/ownership

// Show Server Tabs
$tab = 'files';
require('server_tabs.php');

########################################################################

// Unset current dir
$_SESSION['curdir'] = '';
$_SESSION['dirdeep'] = '0';

// Get list of files
require(DOCROOT.'/includes/classes/files.php');
$Files  = new Files;
$file_list  = $Files->file_list($url_id,false);

// Show directory display
$result = $Files->displaydir($file_list,$url_id);

?>
