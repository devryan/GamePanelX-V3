<?php
require('checkallowed.php'); // No direct access

// Open a directory on a gameserver
$url_id     = $GPXIN['id'];
$directory  = $GPXIN['dir'];
$back_set   = $GPXIN['back'];

// Template Dir Browsing
if(isset($GPXIN['browsetpl'])) $tpl_brws  = '1';
else $tpl_brws  = '0';


// Show tabs
if(!$tpl_brws)
{
    $tab = 'files';
    require('server_tabs.php');
}

// Reset for template browsing if needed
if(isset($GPXIN['reset']))
{
    // Unset current dir
    $_SESSION['curdir'] = '';
    $_SESSION['dirdeep'] = '0';
}


// Setup Files class
require(DOCROOT.'/includes/classes/files.php');
$Files  = new Files;

// First run
if(empty($_SESSION['curdir']))
{
    $_SESSION['curdir'] = $directory;
    $new_dir  = $directory;
}
// Anything after
else
{
    // Back
    if($back_set)
    {
        // Back to homedir, reset everything
        if($_SESSION['dirdeep'] == 1)
        {
            $new_dir = '';
            
            // Reset sess data
            $_SESSION['curdir'] = '';
            $_SESSION['dirdeep'] = '0';
        }
        // Normal back a dir
        else
        {
            $new_dir = dirname($_SESSION['curdir']);
            
            // Update current dir and total dirs deep
            $_SESSION['curdir']   = $new_dir;
            $_SESSION['dirdeep']  = $_SESSION['dirdeep'] - 1;
        }
    }
    // Forward
    else
    {
        $new_dir  = $_SESSION['curdir'] . '/' . $directory;
        $_SESSION['curdir'] = $new_dir;
    }
}

// Update total dirs deep
if(!$back_set) $_SESSION['dirdeep'] = $_SESSION['dirdeep'] + 1;


// Going back in tpl browser, force reset
if($new_dir == '.')
{
    // Unset current dir
    $_SESSION['curdir'] = '';
    $_SESSION['dirdeep'] = '0';
    $new_dir  = '';
}

// Get and display list of files
$file_list  = $Files->file_list($url_id,$new_dir,$tpl_brws);

if(GPXDEBUG && !is_array($file_list)) echo 'DEBUG: '.$file_list.'<br />';

if(GPXDEBUG)
{
    echo 'DEBUG: Dumping file list ...<br />';
    echo '<pre>';
    var_dump($file_list);
    echo '</pre>';
}

$Files->displaydir($file_list,$url_id,$new_dir,$tpl_brws);

?>
