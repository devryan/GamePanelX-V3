<?php
// Assign the correct language
if(isset($_SESSION['gpx_userid']))
{
    $usr_lang = $_SESSION['gpx_lang'];
        
    if(!file_exists(DOCROOT.'/languages/'.$usr_lang.'.php'))
    {
        if(GPXDEBUG) echo 'Language file "'.$usr_lang.'.php" not found!  Defaulting to English.<br />';
        require(DOCROOT.'/languages/english.php');
    }
    else
    {
        # if(GPXDEBUG) echo 'Setting language to "'.$usr_lang.'" ...<br />';
        unset($lang);
        require(DOCROOT.'/languages/'.$usr_lang.'.php');
    }
}
// Default to english if sess data unavailable
else
{
    require(DOCROOT.'/languages/english.php');
}

?>
