<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <title><?php echo APP_NAME; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="ERP University" />
        <meta name="author" content="ERP University" />
        <!--[if gt IE 8]>
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <![endif]-->


        <link href="<?php echo $rutaLayout['_css']; ?>stylesheets.css" rel="stylesheet" type="text/css" /> 

        <!--[if lt IE 8]>
            <link href="<?php echo $rutaLayout['_css']; ?>ie7.css" rel="stylesheet" type="text/css" />
        <![endif]-->            
        <link rel='stylesheet' type='text/css' href='<?php echo $rutaLayout['_css']; ?>fullcalendar.print.css' media='print' />

        <link rel='stylesheet' type='text/css' href='<?php echo BASE_URL; ?>public/css/alertify.css' />
        
        <link rel='stylesheet' type='text/css' href='<?php echo BASE_URL; ?>public/css/style-rc.css' />
        
        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>jquery-1.7.js'></script>
        <!--<script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>jquery-1.8.js'></script>-->
        <script src="<?php echo BASE_URL ?>public/js/mensajes.js"></script>
        <script src="<?php echo BASE_URL ?>public/js/erpAjax.js"></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/jquery/jquery.mousewheel.min.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/cookie/jquery.cookies.2.2.0.min.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/bootstrap.min.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/charts/excanvas.min.js'></script>
        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/charts/jquery.flot.js'></script>    
        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/charts/jquery.flot.stack.js'></script>    
        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/charts/jquery.flot.pie.js'></script>
        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/charts/jquery.flot.resize.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/sparklines/jquery.sparkline.min.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/fullcalendar/fullcalendar.min.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/select2/select2.min.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/uniform/uniform.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/maskedinput/jquery.maskedinput-1.3.min.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/validation/languages/jquery.validationEngine-en.js' charset='utf-8'></script>
        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/validation/jquery.validationEngine.js' charset='utf-8'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/animatedprogressbar/animated_progressbar.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/qtip/jquery.qtip-1.0.0-rc3.min.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/cleditor/jquery.cleditor.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/dataTables/jquery.dataTables.min.js'></script>    

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins/fancybox/jquery.fancybox.pack.js'></script>

        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>cookies.js'></script>
        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>actions.js'></script>
        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>charts.js'></script>
        <script type='text/javascript' src='<?php echo $rutaLayout['_js']; ?>plugins.js'></script>

    </head>
    <body>   

          
                <noscript><p>Para el correcto funcionamiento del aplicativo debe tener habilitado javascript.</p></noscript>
                <?php if (isset($this->error)): ?>
                    <div id="error-content"><?php echo $this->error; ?></div>
                <?php endif; ?>
                <?php if (isset($this->mensaje)): ?>
                    <div id="mensaje-content"><?php echo $this->mensaje; ?></div>
                <?php endif; ?>

            <div id="content-aplication">