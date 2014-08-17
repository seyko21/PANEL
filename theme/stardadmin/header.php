<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <title><?php echo APP_NAME; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="Simple FW" />
        <meta name="author" content="RDCC" />
        
        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $rutaLayout['_css']; ?>bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $rutaLayout['_css']; ?>font-awesome.min.css">

        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $rutaLayout['_css']; ?>smartadmin-production.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $rutaLayout['_css']; ?>smartadmin-skins.css">

        <!-- SmartAdmin RTL Support is under construction-->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $rutaLayout['_css']; ?>smartadmin-rtl.css"> 

        <!-- We recommend you use "your_style.css" to override SmartAdmin
             specific styles this will also ensure you retrain your customization with each SmartAdmin update.
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $rutaLayout['_css']; ?>your_style.css"> -->

        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $rutaLayout['_css']; ?>demo.css">

        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo BASE_URL ?>public/css/general.css">
        
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo BASE_URL ?>public/css/scrollTable.css">
        
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $rutaLayout['_css']; ?>lockscreen.css">
        <!-- FAVICONS -->
        <link rel="shortcut icon" href="<?php echo $rutaLayout['_img']; ?>favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo $rutaLayout['_img']; ?>favicon/favicon.ico" type="image/x-icon">

        <!-- GOOGLE FONT -->
        <!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">-->

        <!-- Specifying a Webpage Icon for Web Clip 
                 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
        <link rel="apple-touch-icon" href="<?php echo $rutaLayout['_img']; ?>splash/sptouch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $rutaLayout['_img']; ?>splash/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $rutaLayout['_img']; ?>splash/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $rutaLayout['_img']; ?>splash/touch-icon-ipad-retina.png">

        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- Startup image for web apps -->
        <link rel="apple-touch-startup-image" href="<?php echo $rutaLayout['_img']; ?>splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="<?php echo $rutaLayout['_img']; ?>splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="<?php echo $rutaLayout['_img']; ?>splash/iphone.png" media="screen and (max-device-width: 320px)">
        <script> var stringValue = 'adABKCDLZEFXGHIJ'; </script>
    </head>
    <body <?php if(!Session::get('sys_idUsuario')){ echo 'id="login" class="animated fadeInDown"'; }?>>   

          
                <noscript><p>Para el correcto funcionamiento del aplicativo debe tener habilitado javascript.</p></noscript>
                <?php if (isset($this->error)): ?>
                    <div id="error-content"><?php echo $this->error; ?></div>
                <?php endif; ?>
                <?php if (isset($this->mensaje)): ?>
                    <div id="mensaje-content"><?php echo $this->mensaje; ?></div>
                <?php endif; ?>