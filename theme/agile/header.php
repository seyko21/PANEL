<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <title><?php echo APP_NAME; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="ERP University" />
        <meta name="author" content="ERP University" />

        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $rutaLayout['_ico']; ?>apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $rutaLayout['_ico']; ?>apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $rutaLayout['_ico']; ?>apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="<?php echo $rutaLayout['_ico']; ?>apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="<?php echo $rutaLayout['_ico']; ?>favicon.png">

        <!--[if lt IE 7]>
        <link rel="stylesheet" type="text/css" href="<?php echo $rutaLayout['_css']; ?>minified/icons-ie7.min.css">
        <![endif]-->

        
        
        <!-- AgileUI CSS Core -->

        <link rel="stylesheet" type="text/css" href="<?php echo $rutaLayout['_css']; ?>minified/aui-production.min.css">

        <!-- Theme UI -->

        <link rel="stylesheet" type="text/css" href="<?php echo $rutaLayout['_theme']; ?>minified/agileui/color-schemes/layouts/black.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $rutaLayout['_theme']; ?>minified/agileui/color-schemes/elements/default.min.css">

        <!-- AgileUI Responsive -->

        <link rel="stylesheet" type="text/css" href="<?php echo $rutaLayout['_theme']; ?>minified/agileui/responsive.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>public/css/AeroWindow.css">
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>libs/DataGrid/css/scrollTable.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $rutaLayout['_css']; ?>styles.css">
        
        <!--[if IE]> 
            <link rel="stylesheet" type="text/css" href="<?php echo $rutaLayout['_css']; ?>styleie.css">
        <![endif]-->
        
        <!-- AgileUI JS -->
        <script src="<?php echo BASE_URL ?>public/js/erpAjax.js"></script>
        <script src="<?php echo $rutaLayout['_js']; ?>minified/aui-production.min.js"></script>


        <script>
            jQuery(window).load(
                    function() {

                        var wait_loading = window.setTimeout(function() {
                            $('#loading').slideUp('fast');
                            jQuery('body').css('overflow', 'auto');
                        }, 500
                                );

                    });
        </script>
    </head>
    <body <?php echo (isset($this->menu)) ? '' : 'class="close-sidebar"'; ?> style="overflow: hidden;">   
        <div id="loading" class="ui-front loader ui-widget-overlay solid-white opacity-100">
            <img src="<?php echo $rutaLayout['_img']; ?>loader-dark.gif" alt="">
        </div>
        <div id="page-wrapper" class="demo-example">
            
            <?php if(Session::get('sys_usuario')):?>
                <div id="page-sidebar">
                    <div id="header-logo">
                        ERP <i class="opacity-80">University</i>

                        <a href="javascript:;" class="tooltip-button" data-placement="bottom" title="Cerrar sidebar" id="close-sidebar">
                            <i class="glyph-icon icon-align-justify"></i>
                        </a>
                        <a href="javascript:;" class="tooltip-button hidden" data-placement="bottom" title="Abrir sidebar" id="rm-close-sidebar">
                            <i class="glyph-icon icon-align-justify"></i>
                        </a>
                        <a href="javascript:;" class="tooltip-button hidden" title="Navigation Menu" id="responsive-open-menu">
                            <i class="glyph-icon icon-align-justify"></i>
                        </a>
                    </div>
                    <div id="sidebar-menu" class="scrollable-content" style="overflow: hidden;" >
                        <!--  AQUI CARGARAN LOS MODULOS Y OPCIONES
                        <div class="divider mrg5T mobile-hidden"></div>-->
                    </div>
               </div><!--fin page-sidebar -- menu-->
            <?php endif; ?>
            <div id="page-main">
                <noscript><p>Para el correcto funcionamiento del aplicativo debe tener habilitado javascript.</p></noscript>
                <?php if (isset($this->error)): ?>
                    <div id="error-content"><?php echo $this->error; ?></div>
                <?php endif; ?>
                <?php if (isset($this->mensaje)): ?>
                    <div id="mensaje-content"><?php echo $this->mensaje; ?></div>
                <?php endif; ?>


                    <div id="page-main-wrapper">     

                    <div id="page-header" class="clearfix">
                        <div id="page-header-wrapper" class="clearfix">
                            <?php if (Session::get('sys_usuario')): ?>
                                <div class="top-icon-bar dropdown">
                                    <a href="javascript:;" title="" class="user-ico clearfix" data-toggle="dropdown">
                                        <img width="36" src="<?php echo $rutaLayout['_img']; ?>gravatar.jpg" alt="">
                                        <span><?php echo Obj::run()->AesCtr->de(Session::get('sys_nombreUsuario')); ?></span>
                                        <i class="glyph-icon icon-chevron-down"></i>
                                    </a>
                                    <ul class="dropdown-menu float-right">
                                        <li>
                                            <span class="badge badge-absolute float-left radius-all-100 mrg5R gradient-green tooltip-button" title="" data-original-title="You can add badges even to dropdown menus!">7</span>
                                            <a href="javascript:;" title="">
                                                <i class="glyph-icon icon-user mrg5R"></i>
                                                Account Details
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" title="">
                                                <i class="glyph-icon icon-cog mrg5R"></i>
                                                Edit Profile
                                            </a>
                                        </li>
                                        <li>
                                            <a class="font-orange" href="javascript:;" title="">
                                                <i class="glyph-icon icon-flag mrg5R"></i>
                                                Notifications
                                            </a>
                                        </li>
                                        <li>
                                            <a id="lkSalir" href="javascript:;" title="">
                                                <i class="glyph-icon icon-signout font-size-13 mrg5R"></i>
                                                <span class="font-bold">Logout</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="top-icon-bar">
                                    <a href="javascript:;" class="popover-button" data-placement="bottom" title="Messages Widget" data-id="#msg-box">
                                        <span class="badge badge-absolute gradient-orange">18</span>
                                        <i class="glyph-icon icon-envelope-alt"></i>
                                    </a>
                                    <div id="msg-box" class="hide">

                                        <div class="scrollable-content scrollable-small">

                                            <ul class="no-border messages-box">
                                                <li>
                                                    <div class="messages-img">
                                                        <img width="32" src="<?php echo $rutaLayout['_img']; ?>gravatar.jpg" alt="">
                                                    </div>
                                                    <div class="messages-content">
                                                        <div class="messages-title">
                                                            <i class="glyph-icon icon-warning-sign font-red"></i>
                                                            <a href="javascript:;" title="Message title">Important message</a>
                                                            <div class="messages-time">
                                                                3 hr ago
                                                                <span class="glyph-icon icon-time"></span>
                                                            </div>
                                                        </div>
                                                        <div class="messages-text">
                                                            This message must be read immediately because of it's high importance...
                                                        </div>
                                                    </div> 
                                                </li>
                                                <li>
                                                    <div class="messages-img">
                                                        <img width="32" src="<?php echo $rutaLayout['_img']; ?>gravatar.jpg" alt="">
                                                    </div>
                                                    <div class="messages-content">
                                                        <div class="messages-title">
                                                            <i class="glyph-icon icon-tag font-blue"></i>
                                                            <a href="javascript:;" title="Message title">Some random email</a>
                                                            <div class="messages-time">
                                                                3 hr ago
                                                                <span class="glyph-icon icon-time"></span>
                                                            </div>
                                                        </div>
                                                        <div class="messages-text">
                                                            This message must be read immediately because of it's high importance...
                                                        </div>
                                                    </div> 
                                                </li>
                                                <li>
                                                    <div class="messages-img">
                                                        <img width="32" src="<?php echo $rutaLayout['_img']; ?>gravatar.jpg" alt="">
                                                    </div>
                                                    <div class="messages-content">
                                                        <div class="messages-title">
                                                            <a href="javascript:;" class="font-orange" title="Message title">Another received message</a>
                                                            <div class="messages-time">
                                                                3 hr ago
                                                                <span class="glyph-icon icon-time"></span>
                                                            </div>
                                                        </div>
                                                        <div class="messages-text">
                                                            This message must be read immediately because of it's high importance...
                                                        </div>
                                                    </div> 
                                                </li>
                                                <li>
                                                    <div class="messages-img">
                                                        <img width="32" src="<?php echo $rutaLayout['_img']; ?>gravatar.jpg" alt="">
                                                    </div>
                                                    <div class="messages-content">
                                                        <div class="messages-title">
                                                            <i class="glyph-icon icon-warning-sign font-red"></i>
                                                            <a href="javascript:;" title="Message title">Important message</a>
                                                            <div class="messages-time">
                                                                3 hr ago
                                                                <span class="glyph-icon icon-time"></span>
                                                            </div>
                                                        </div>
                                                        <div class="messages-text">
                                                            This message must be read immediately because of it's high importance...
                                                        </div>
                                                    </div> 
                                                </li>
                                            </ul>

                                        </div>
                                        <div class="pad10A button-pane button-pane-alt">
                                            <a href="messaging.html" class="btn small float-left solid-gray">
                                                <span class="button-content text-transform-upr font-size-11">All messages</span>
                                            </a>
                                            <div class="button-group float-right">
                                                <a href="javascript:;" class="btn small primary-bg">
                                                    <i class="glyph-icon icon-star"></i>
                                                </a>
                                                <a href="javascript:;" class="btn small primary-bg">
                                                    <i class="glyph-icon icon-random"></i>
                                                </a>
                                                <a href="javascript:;" class="btn small primary-bg">
                                                    <i class="glyph-icon icon-map-marker"></i>
                                                </a>
                                            </div>
                                            <a href="javascript:;" class="small btn solid-red float-right mrg10R tooltip-button" data-placement="left" title="Remove comment">
                                                <i class="glyph-icon icon-remove"></i>
                                            </a>
                                        </div>

                                    </div>

                                    <a href="javascript:;" class="popover-button" data-placement="bottom" title="" data-id="#notif-box">
                                        <span class="badge badge-absolute gradient-green">5</span>
                                        <i class="glyph-icon icon-bell"></i>
                                    </a>
                                    <div id="notif-box" class="hide">

                                        <div class="popover-title display-block clearfix form-row pad10A">
                                            <div class="form-input">
                                                <div class="form-input-icon">
                                                    <i class="glyph-icon icon-search transparent"></i>
                                                    <input type="text" placeholder="Search notifications..." class="radius-all-100" name="" id="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="scrollable-content scrollable-small">

                                            <ul class="no-border notifications-box">
                                                <li>
                                                    <span class="btn gradient-red icon-notification glyph-icon icon-user"></span>
                                                    <span class="notification-text">This is an error notification</span>
                                                    <div class="notification-time">
                                                        a few seconds ago
                                                        <span class="glyph-icon icon-time"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <span class="btn solid-orange icon-notification glyph-icon icon-user"></span>
                                                    <span class="notification-text">This is a warning notification</span>
                                                    <div class="notification-time">
                                                        <b>15</b> minutes ago
                                                        <span class="glyph-icon icon-time"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <span class="solid-green btn icon-notification glyph-icon icon-user"></span>
                                                    <span class="notification-text font-green font-bold">A success message example.</span>
                                                    <div class="notification-time">
                                                        <b>2 hours</b> ago
                                                        <span class="glyph-icon icon-time"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <span class="btn gradient-red icon-notification glyph-icon icon-user"></span>
                                                    <span class="notification-text">This is an error notification</span>
                                                    <div class="notification-time">
                                                        a few seconds ago
                                                        <span class="glyph-icon icon-time"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <span class="btn solid-orange icon-notification glyph-icon icon-user"></span>
                                                    <span class="notification-text">This is a warning notification</span>
                                                    <div class="notification-time">
                                                        <b>15</b> minutes ago
                                                        <span class="glyph-icon icon-time"></span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <span class="solid-blue btn icon-notification glyph-icon icon-user"></span>
                                                    <span class="notification-text font-blue">Alternate notification styling.</span>
                                                    <div class="notification-time">
                                                        <b>2 hours</b> ago
                                                        <span class="glyph-icon icon-time"></span>
                                                    </div>
                                                </li>
                                            </ul>

                                        </div>
                                        <div class="pad10A button-pane button-pane-alt text-center">
                                            <a href="notifications.html" class="btn medium primary-bg">
                                                <span class="button-content">View all notifications</span>
                                            </a>
                                        </div>

                                    </div>

                                    <a href="javascript:;" class="popover-button" data-placement="bottom" title="" data-id="#prog-box">
                                        <span class="badge badge-absolute gradient-red">21</span>
                                        <i class="glyph-icon icon-tasks"></i>
                                    </a>
                                    <div id="prog-box" class="hide">

                                        <div class="scrollable-content scrollable-small">

                                            <ul class="no-border progress-box">
                                                <li>
                                                    <div class="progress-title">
                                                        Finishing uploading files
                                                        <b>23%</b>
                                                    </div>
                                                    <div class="progressbar-small progressbar" data-value="23">
                                                        <div class="progressbar-value solid-blue">
                                                            <div class="progressbar-overlay"></div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="progress-title">
                                                        Roadmap progress
                                                        <b>91%</b>
                                                    </div>
                                                    <div class="progressbar-small progressbar" data-value="91">
                                                        <div class="progressbar-value primary-bg">
                                                            <div class="progressbar-overlay"></div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="progress-title">
                                                        Images upload
                                                        <b>58%</b>
                                                    </div>
                                                    <div class="progressbar-small progressbar" data-value="58">
                                                        <div class="progressbar-value gradient-green"></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="progress-title">
                                                        WordPress migration
                                                        <b>74%</b>
                                                    </div>
                                                    <div class="progressbar-small progressbar" data-value="74">
                                                        <div class="progressbar-value gradient-red"></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="progress-title">
                                                        Agile development procedures
                                                        <b>91%</b>
                                                    </div>
                                                    <div class="progressbar-small progressbar" data-value="91">
                                                        <div class="progressbar-value primary-bg">
                                                            <div class="progressbar-overlay"></div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="progress-title">
                                                        Systems integration
                                                        <b>58%</b>
                                                    </div>
                                                    <div class="progressbar-small progressbar" data-value="58">
                                                        <div class="progressbar-value gradient-green"></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="progress-title">
                                                        Code optimizations
                                                        <b>97%</b>
                                                    </div>
                                                    <div class="progressbar-small progressbar" data-value="97">
                                                        <div class="progressbar-value gradient-orange"></div>
                                                    </div>
                                                </li>
                                            </ul>

                                        </div>
                                        <div class="pad10A button-pane button-pane-alt text-center">
                                            <a href="notifications.html" class="btn medium ui-state-default">
                                                <span class="button-content">View all</span>
                                            </a>
                                        </div>

                                    </div>
                                </div>

                                <div id="layout-demo" class="button-group dropdown float-right mrg10R">
                                    <a class="btn tooltip-button" data-placement="bottom" href="javascript:;" title="Haga clic en el icono desplegable de la derecha para cambiar de Rol">
                                        <span class="button-content text-center float-none font-size-11 text-transform-upr">
                                            <i class="glyph-icon icon-sitemap float-left"></i>
                                            <?php echo Obj::run()->AesCtr->de(Session::get('sys_nombreRol')); ?>
                                        </span>
                                    </a>
                                    <a class="btn" href="javascript:;" data-toggle="dropdown">
                                        <span class="glyph-icon icon-separator">
                                            <i class="glyph-icon icon-angle-down"></i>
                                        </span>
                                    </a>
                                    <ul class="dropdown-menu float-right">
                                        <li class="header">
                                            Seleccione Rol:
                                        </li>
                                        <?php foreach (Session::get('sys_roles') as $rol): ?>
                                            <?php if($rol['id_rol'] != Session::get('sys_rol')):?>
                                            <li>
                                                <a onclick="index.getNewRol('<?php echo $rol['id_rol']; ?>');" href="javascript:;">
                                                    <?php echo Obj::run()->AesCtr->de($rol['rol']); ?>
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <h3>Autenticación</h3>
                            <?php endif; ?>

                        </div>
                    </div><!-- #page-header -->
                    <div id="content-aplication"><br><br><br><br>