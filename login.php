<?php
session_start();

// Check already logged-in
if(isset($_SESSION['gpx_userid']))
{
    header('Location: index.php');
    exit(0);
}

if(!file_exists('configuration.php')) die('Currently down for maintenance.  Please try again soon.');

require('configuration.php');

// Get system settings
require('includes/classes/core.php');
$Core = new Core;
$Core->dbconnect();
$settings = $Core->getsettings();
$cfg_theme      = $settings['theme'];
$cfg_lang       = $settings['language'];
$cfg_company    = $settings['company'];

// Set default language
if(!empty($cfg_lang)) require('languages/'.$cfg_lang.'.php');
else require('languages/english.php');

// Check Install
if(file_exists('install')) die('Currently down for maintenance.  Please try again soon.');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php if(!empty($cfg_company)) echo $cfg_company . ' | '.$lang['game_panel']; else echo $lang['game_panel']; ?></title>
<?php
// Use default system theme
if(!empty($cfg_theme)) echo '<link rel="stylesheet" type="text/css" href="themes/'.$cfg_theme.'/index.css" />';
else echo '<link rel="stylesheet" type="text/css" href="themes/default/index.css" />';
?>
<script type="text/javascript" src="scripts/jquery.min.js"></script>
<script type="text/javascript">var ajaxURL='ajax/ajax.php';</script>
<script src="http://s.codepen.io/assets/libs/modernizr.js" type="text/javascript"></script>
<script type="text/javascript" src="scripts/gpx.js"></script>
<script type="text/javascript" src="scripts/base64.js"></script>
<script type="text/javascript" src="scripts/internal/login.js"></script>
</head>

<body>
<div class="header-11">
<!-- <img src="https://wallpaperscraft.com/image/mountains_beautiful_sky_blurred_87742_1920x1080.jpg" alt=""> -->
<canvas id='canvas'></canvas>
<div id="panel_top_client">The FlarePanel!</div>

<script type="text/javascript">
$(document).ready(function(){
    // Submit Login on enter
    $('.inputs').keypress(function(e) {
        if(e.which == 13) {
            login_user();
        }
    });



    <?php
    // Logged-out msg
    if(isset($_GET['out'])) echo 'infobox(\'s\', \''.$lang['logged_out'].'\');';
    ?>
});
</script>
<div class="container">
  <script type="text/javascript" src="scripts/internal/login.js"></script>
  <div class="card" style="border: none;background: none;box-shadow: none;">
    <div class="infobox" style="width:478px; !important;"></div>

  </div>
  <div class="card">
    <h1 class="title">Login</h1>
    <form>
      <div class="input-container">
        <input type="text" class="inputs" id="login_user"style="
          margin-top: 16px;
          " />
        <label for="login_user">Username</label>
        <div class="bar"></div>
      </div>
      <div class="input-container">
        <input type="password" class="inputs" id="login_pass" style="
          margin-top: 16px;
          "/>
        <label for="login_pass">Password</label>
        <div class="bar"></div>
      </div>
      <div class="button-container">

      </div>
      <div class="footer"><a href="mailto:will@flareservers.co.uk?subject=Password%20Forgotton%FlarePanel">Forgot your password?</a></div>
    </form>
  </div>
</div>

<div align="center">
    <div id="login_box" style="margin-top: -77px;height:10px !important;">

        <input type="button" class="button" id="login_btn" value="<?php echo $lang['login']; ?>" onClick="javascript:login_user();" />
    </div>
</div>

<style media="screen">
body {
background: #e9e9e9;
color: #666666;
font-family: 'RobotoDraft', 'Roboto', sans-serif;
font-size: 14px;
-webkit-font-smoothing: antialiased;
-moz-osx-font-smoothing: grayscale;
}

/* Pen Title */
.pen-title {
padding: 50px 0;
text-align: center;
letter-spacing: 2px;
}
.pen-title h1 {
margin: 0 0 20px;
font-size: 48px;
font-weight: 300;
}
.pen-title span {
font-size: 12px;
}
.pen-title span .fa {
color: #ed2553;
}
.pen-title span a {
color: #ed2553;
font-weight: 600;
text-decoration: none;
}

/* Rerun */
.rerun {
margin: 0 0 30px;
text-align: center;
}
.rerun a {
cursor: pointer;
display: inline-block;
background: #ed2553;
border-radius: 3px;
box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
padding: 10px 20px;
color: #ffffff;
text-decoration: none;
-webkit-transition: 0.3s ease;
transition: 0.3s ease;
}
.rerun a:hover {
box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}

/* Scroll To Bottom */
#codepen, #portfolio {
position: fixed;
bottom: 30px;
right: 30px;
background: #363636;
width: 56px;
height: 56px;
border-radius: 100%;
box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
-webkit-transition: 0.3s ease;
transition: 0.3s ease;
color: #ffffff;
text-align: center;
}
#codepen i, #portfolio i {
line-height: 56px;
}
#codepen:hover, #portfolio:hover {
box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
}

/* CodePen */
#portfolio {
bottom: 96px;
right: 36px;
background: #ed2553;
width: 44px;
height: 44px;
-webkit-animation: buttonFadeInUp 1s ease;
animation: buttonFadeInUp 1s ease;
}
#portfolio i {
line-height: 44px;
}

/* Container */
.container {
position: relative;
max-width: 460px;
width: 100%;
margin: 0 auto 100px;
}
.container.active .card:first-child {
background: #f2f2f2;
margin: 0 15px;
}
.container.active .card:nth-child(2) {
background: #fafafa;
margin: 0 10px;
}
.container.active .card.alt {
top: 20px;
right: 0;
width: 100%;
min-width: 100%;
height: auto;
border-radius: 5px;
padding: 60px 0 40px;
overflow: hidden;
}
.container.active .card.alt .toggle {
position: absolute;
top: 40px;
right: -70px;
box-shadow: none;
-webkit-transform: scale(10);
transform: scale(10);
-webkit-transition: -webkit-transform .3s ease;
transition: -webkit-transform .3s ease;
transition: transform .3s ease;
transition: transform .3s ease, -webkit-transform .3s ease;
}
.container.active .card.alt .toggle:before {
content: '';
}
.container.active .card.alt .title,
.container.active .card.alt .input-container,
.container.active .card.alt .button-container {
left: 0;
opacity: 1;
visibility: visible;
-webkit-transition: .3s ease;
transition: .3s ease;
}
.container.active .card.alt .title {
-webkit-transition-delay: .3s;
        transition-delay: .3s;
}
.container.active .card.alt .input-container {
-webkit-transition-delay: .4s;
        transition-delay: .4s;
}
.container.active .card.alt .input-container:nth-child(2) {
-webkit-transition-delay: .5s;
        transition-delay: .5s;
}
.container.active .card.alt .input-container:nth-child(3) {
-webkit-transition-delay: .6s;
        transition-delay: .6s;
}
.container.active .card.alt .button-container {
-webkit-transition-delay: .7s;
        transition-delay: .7s;
}

/* Card */
.card {
position: relative;
background: #ffffff;
border-radius: 5px;
padding: 60px 0 40px 0;
box-sizing: border-box;
box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
-webkit-transition: .3s ease;
transition: .3s ease;
/* Title */
/* Inputs */
/* Button */
/* Footer */
/* Alt Card */
}
.card:first-child {
background: #fafafa;
height: 10px;
border-radius: 5px 5px 0 0;
margin: 0 10px;
padding: 0;
}
.card .title {
position: relative;
z-index: 1;
border-left: 5px solid #ed2553;
margin: 0 0 35px;
padding: 10px 0 10px 50px;
color: #ed2553;
font-size: 32px;
font-weight: 600;
text-transform: uppercase;
}
.card .input-container {
position: relative;
margin: 0 60px 50px;
}
.card .input-container input {
outline: none;
z-index: 1;
position: relative;
background: none;
width: 100%;
height: 60px;
border: 0;
color: #212121;
font-size: 24px;
font-weight: 400;
}
.header-1{
  background: url('https://wallpaperscraft.com/image/mountains_beautiful_sky_blurred_87742_1920x1080.jpg')
}
.card .input-container input:focus ~ label {
color: #9d9d9d;
-webkit-transform: translate(-12%, -50%) scale(0.75);
        transform: translate(-12%, -50%) scale(0.75);
}
.card .input-container input:focus ~ .bar:before, .card .input-container input:focus ~ .bar:after {
width: 50%;
}
.card .input-container input:valid ~ label {
color: #9d9d9d;
-webkit-transform: translate(-12%, -50%) scale(0.75);
        transform: translate(-12%, -50%) scale(0.75);
}
.card .input-container label {
position: absolute;
top: 0;
left: 0;
color: #757575;
font-size: 24px;
font-weight: 300;
line-height: 60px;
-webkit-transition: 0.2s ease;
transition: 0.2s ease;
}
.card .input-container .bar {
position: absolute;
left: 0;
bottom: 0;
background: #757575;
width: 100%;
height: 1px;
}
.card .input-container .bar:before, .card .input-container .bar:after {
content: '';
position: absolute;
background: #ed2553;
width: 0;
height: 2px;
-webkit-transition: .2s ease;
transition: .2s ease;
}
.card .input-container .bar:before {
left: 50%;
}
.card .input-container .bar:after {
right: 50%;
}
.card .button-container {
margin: 0 60px;
text-align: center;
}
.card .button-container button {
outline: 0;
cursor: pointer;
position: relative;
display: inline-block;
background: 0;
width: 240px;
border: 2px solid #e3e3e3;
padding: 20px 0;
font-size: 24px;
font-weight: 600;
line-height: 1;
text-transform: uppercase;
overflow: hidden;
-webkit-transition: .3s ease;
transition: .3s ease;
}
.card .button-container button span {
position: relative;
z-index: 1;
color: #ddd;
-webkit-transition: .3s ease;
transition: .3s ease;
}
.card .button-container button:before {
content: '';
position: absolute;
top: 50%;
left: 50%;
display: block;
background: #ed2553;
width: 30px;
height: 30px;
border-radius: 100%;
margin: -15px 0 0 -15px;
opacity: 0;
-webkit-transition: .3s ease;
transition: .3s ease;
}
.card .button-container button:hover, .card .button-container button:active, .card .button-container button:focus {
border-color: #ed2553;
}
.card .button-container button:hover span, .card .button-container button:active span, .card .button-container button:focus span {
color: #ed2553;
}
.card .button-container button:active span, .card .button-container button:focus span {
color: #ffffff;
}
.card .button-container button:active:before, .card .button-container button:focus:before {
opacity: 1;
-webkit-transform: scale(10);
transform: scale(10);
}
.card .footer {
margin: 40px 0 0;
color: #d3d3d3;
font-size: 24px;
font-weight: 300;
text-align: center;
}
.card .footer a {
color: inherit;
text-decoration: none;
-webkit-transition: .3s ease;
transition: .3s ease;
}
.card .footer a:hover {
color: #bababa;
}
.card.alt {
position: absolute;
top: 40px;
right: -70px;
z-index: 10;
width: 140px;
height: 140px;
background: none;
border-radius: 100%;
box-shadow: none;
padding: 0;
-webkit-transition: .3s ease;
transition: .3s ease;
/* Toggle */
/* Title */
/* Input */
/* Button */
}
.card.alt .toggle {
position: relative;
background: #ed2553;
width: 140px;
height: 140px;
border-radius: 100%;
box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
color: #ffffff;
font-size: 58px;
line-height: 140px;
text-align: center;
cursor: pointer;
}
.card.alt .toggle:before {
content: '\f040';
display: inline-block;
font: normal normal normal 14px/1 FontAwesome;
font-size: inherit;
text-rendering: auto;
-webkit-font-smoothing: antialiased;
-moz-osx-font-smoothing: grayscale;
-webkit-transform: translate(0, 0);
        transform: translate(0, 0);
}
.card.alt .title,
.card.alt .input-container,
.card.alt .button-container {
left: 100px;
opacity: 0;
visibility: hidden;
}
.card.alt .title {
position: relative;
border-color: #ffffff;
color: #ffffff;
}
.card.alt .title .close {
cursor: pointer;
position: absolute;
top: 0;
right: 60px;
display: inline;
color: #ffffff;
font-size: 58px;
font-weight: 400;
}
.card.alt .title .close:before {
content: '\00d7';
}
.card.alt .input-container input {
color: #ffffff;
}
.card.alt .input-container input:focus ~ label {
color: #ffffff;
}
.card.alt .input-container input:focus ~ .bar:before, .card.alt .input-container input:focus ~ .bar:after {
background: #ffffff;
}
.card.alt .input-container input:valid ~ label {
color: #ffffff;
}
.card.alt .input-container label {
color: rgba(255, 255, 255, 0.8);
}
.card.alt .input-container .bar {
background: rgba(255, 255, 255, 0.8);
}
.card.alt .button-container button {
width: 100%;
background: #ffffff;
border-color: #ffffff;
}
.card.alt .button-container button span {
color: #ed2553;
}
.card.alt .button-container button:hover {
background: rgba(255, 255, 255, 0.9);
}
.card.alt .button-container button:active:before, .card.alt .button-container button:focus:before {
display: none;
}

/* Keyframes */
@-webkit-keyframes buttonFadeInUp {
0% {
  bottom: 30px;
  opacity: 0;
}
}
@keyframes buttonFadeInUp {
0% {
  bottom: 30px;
  opacity: 0;
}
}

</style>
<script type="text/javascript">
$('.toggle').on('click', function() {
$('.container').stop().addClass('active');
});

$('.close').on('click', function() {
$('.container').stop().removeClass('active');
});
</script>

<script type="text/javascript">
function Banner(){

var keyword = "   FlarePanel";
var canvas;
var context;

var bgCanvas;
var bgContext;

var denseness = 10;

//Each particle/icon
var parts = [];

var mouse = {x:-100,y:-100};
var mouseOnScreen = false;

var itercount = 0;
var itertot = 40;

this.initialize = function(canvas_id){
  canvas = document.getElementById(canvas_id);
  context = canvas.getContext('2d');

  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;

  bgCanvas = document.createElement('canvas');
  bgContext = bgCanvas.getContext('2d');

  bgCanvas.width = window.innerWidth;
  bgCanvas.height = window.innerHeight;

  canvas.addEventListener('mousemove', MouseMove, false);
  canvas.addEventListener('mouseout', MouseOut, false);

  start();
}

var start = function(){

  bgContext.fillStyle = "#000000";
  bgContext.font = '300px impact';
  bgContext.fillText(keyword, 85, 275);

  clear();
  getCoords();
}

var getCoords = function(){
  var imageData, pixel, height, width;

  imageData = bgContext.getImageData(0, 0, canvas.width, canvas.height);

  // quickly iterate over all pixels - leaving density gaps
    for(height = 0; height < bgCanvas.height; height += denseness){
          for(width = 0; width < bgCanvas.width; width += denseness){
             pixel = imageData.data[((width + (height * bgCanvas.width)) * 4) - 1];
                //Pixel is black from being drawn on.
                if(pixel == 255) {
                  drawCircle(width, height);
                }
          }
      }

      setInterval( update, 40 );
}

var drawCircle = function(x, y){

  var startx = (Math.random() * canvas.width);
  var starty = (Math.random() * canvas.height);

  var velx = (x - startx) / itertot;
  var vely = (y - starty) / itertot;

  parts.push(
    {c: '#' + (Math.random() * 0x949494 + 0xaaaaaa | 0).toString(16),
     x: x, //goal position
     y: y,
     x2: startx, //start position
     y2: starty,
     r: true, //Released (to fly free!)
     v:{x:velx , y: vely}
    }
  )
}

var update = function(){
  var i, dx, dy, sqrDist, scale;
  itercount++;
  clear();
  for (i = 0; i < parts.length; i++){

    //If the dot has been released
    if (parts[i].r == true){
      //Fly into infinity!!
      parts[i].x2 += parts[i].v.x;
          parts[i].y2 += parts[i].v.y;
    //Perhaps I should check if they are out of screen... and kill them?
    }
    if (itercount == itertot){
      parts[i].v = {x:(Math.random() * 6) * 2 - 6 , y:(Math.random() * 6) * 2 - 6};
      parts[i].r = false;
    }


    //Look into using svg, so there is no mouse tracking.
    //Find distance from mouse/draw!
    dx = parts[i].x - mouse.x;
        dy = parts[i].y - mouse.y;
        sqrDist =  Math.sqrt(dx*dx + dy*dy);

    if (sqrDist < 20){
      parts[i].r = true;
    }

    //Draw the circle
    context.fillStyle = parts[i].c;
    context.beginPath();
    context.arc(parts[i].x2, parts[i].y2, 4 ,0 , Math.PI*2, true);
    context.closePath();
      context.fill();

  }
}

var MouseMove = function(e) {
    if (e.layerX || e.layerX == 0) {
      //Reset particle positions
      mouseOnScreen = true;


        mouse.x = e.layerX - canvas.offsetLeft;
        mouse.y = e.layerY - canvas.offsetTop;
    }
}

var MouseOut = function(e) {
  mouseOnScreen = false;
  mouse.x = -100;
  mouse.y = -100;
}

//Clear the on screen canvas
var clear = function(){
  context.fillStyle = '#333';
  context.beginPath();
    context.rect(0, 0, canvas.width, canvas.height);
  context.closePath();
  context.fill();
}
}

var banner = new Banner();
banner.initialize("canvas");
</script>



</div>
</body>
</html>
