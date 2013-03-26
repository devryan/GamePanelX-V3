<?php
// Plugin example, to display some text on the home page.
echo "This is a stray echo that will NOT be displayed.  Use functions for echoing things.";

function ryan1()
{
  echo "First plugin! This is some text that can show up on the home page, above the icons.";
}

// We set the action "home_top" to run our function "cool_home_hello".  This makes sure the function runs in the right place.
$this->set_action('home_top','ryan1');

?>
