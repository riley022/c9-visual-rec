<?php
require_once('configuration.php');
if(!isset($_SESSION)) {
  session_start();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Visual Recognition Application</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="<?php echo $url ?>/assets/img/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Visual Recognition Application">
    <link rel="apple-touch-icon-precomposed" href="<?php echo $url ?>/assets/img/ios-desktop.png">

    <script type="text/javascript">
      (function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(chref=d.href).replace(e.href,"").indexOf("#")&&(!/^[a-z\+\.\-]+:/i.test(chref)||chref.indexOf(e.protocol+"//"+e.host)===0)&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone");
    </script>

    <link rel="shortcut icon" href="<?php echo $url ?>/assets/img/favicon.png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css" />
    <!--<link rel="stylesheet" href="<?php echo $url ?>/assets/css/material.blue-indigo.css">-->
    <link rel="stylesheet" href="<?php echo $url ?>/assets/css/material.css">
    <link rel="stylesheet" href="<?php echo $url ?>/assets/css/dropify.css">
    <link rel="stylesheet" href="<?php echo $url ?>/assets/css/style.css">
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
      <header class="mdl-layout__header mdl-color--primary-dark">
        <div class="mdl-layout-icon"></div>
        <div class="mdl-layout__header-row">
          <span class="mdl-layout__title">Accenture SmartVision<!-- <i style="font-size: 60%; vertical-align: middle;">Product Recognition<sup>BI</sup></i>--></span>
          <div class="mdl-layout-spacer"></div>
          <?php
          if(isset($_SESSION['logged_in'])) {
          ?>          
          <nav class="mdl-navigation">
            <a class="mdl-layout__tab" href="<?php echo $url ?>">Home</a>
            <a class="mdl-layout__tab" href="<?php echo $url ?>/product/identify-image">Identify Image/Video</a>
            <!--<a class="mdl-layout__tab" href="<?php echo $url ?>/product/identify-video">Identify Video</a>-->
            <a class="mdl-layout__tab" href="<?php echo $url ?>/usercase">Examples</a>
            <a class="mdl-layout__tab" href="<?php echo $url ?>/user">Profile</a>
            <a class="mdl-layout__tab" href="<?php echo $url ?>/logout">Logout</a>
          </nav>
          <?php
          }
          else {
          ?>
          <nav class="mdl-navigation">
            <a class="mdl-layout__tab" href="<?php echo $url ?>">Home</a>
            <a class="mdl-layout__tab" href="<?php echo $url ?>/register">Register</a>
            <a class="mdl-layout__tab" href="<?php echo $url ?>/login">Login</a>
          </nav>
          <?php
          }
          ?> 
        </div>
      </header>
      <main class="mdl-layout__content">