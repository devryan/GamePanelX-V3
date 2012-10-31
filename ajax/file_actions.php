<?php
require('checkallowed.php'); // No direct access

$url_id     = $GPXIN['id'];
$url_do     = $GPXIN['do'];
$file_name  = $GPXIN['file'];

require(DOCROOT.'/includes/classes/files.php');
$Files  = new Files;


// Delete a file
if($url_do == 'delete')
{
    echo $Files->delete_file($url_id,$file_name);
}

// Delete a directory
elseif($url_do == 'delete_dir')
{
    echo $Files->delete_dir($url_id,$file_name);
}

// Save file contents
elseif($url_do == 'savecontent')
{
    if(empty($url_id) || empty($file_name)) die('No ID or filename given!');
    $file_content = $GPXIN['content'];
    
    echo $Files->save_file($url_id,$file_name,$file_content);
    exit;
}

// Show add file dialog
elseif($url_do == 'show_addfile')
{
    echo '<b>'.$lang['new_filename'].':</b> <input type="text" class="inputs" id="newfilename" /><br />
    <textarea id="filecontent" class="txteditor" style="width:715px;height:370px;white-space:pre;"></textarea><br />
    <div align="center"><div class="button" onClick="javascript:file_savenewfile('.$url_id.');">'.$lang['save'].'</div></div>';
    
    exit;
}

// Show add directory dialog
elseif($url_do == 'show_add_dir')
{
    echo '<b>'.$lang['new_dirname'].':</b> <input type="text" class="inputs" id="new_dirname" /><br />
    <div align="center"><div class="button" onClick="javascript:file_add_newdir('.$url_id.');">'.$lang['save'].'</div></div>';
    
    exit;
}

// Create new directory
elseif($url_do == 'create_newdir')
{
    $dir_name = $GPXIN['dir'];
    
    echo $Files->create_newdir($url_id,$dir_name);
    
    exit;
}

// Save new file
elseif($url_do == 'save_newfile')
{
    $file_content = $GPXIN['content'];
    
    echo $Files->save_newfile($url_id,$file_name,$file_content);
    
    exit;
}

?>
