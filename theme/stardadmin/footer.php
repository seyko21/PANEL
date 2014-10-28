     
<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)
<script data-pace-options='{ "restartOnRequestAfter": true }' src="js/plugin/pace/pace.min.js"></script>-->

<script src="<?php echo $rutaLayout['_js']; ?>plugin/pace/pace.min.js"></script>

<script src="<?php echo $rutaLayout['_js']; ?>libs/jquery-2.0.2.min.js"></script>
<script src="<?php echo $rutaLayout['_js']; ?>libs/jquery-ui-1.10.3.min.js"></script>

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
<script src="<?php echo $rutaLayout['_js']; ?>plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

<!-- BOOTSTRAP JS -->
<script src="<?php echo $rutaLayout['_js']; ?>bootstrap/bootstrap.min.js"></script>

<!-- CUSTOM NOTIFICATION -->
<script src="<?php echo $rutaLayout['_js']; ?>notification/SmartNotification.min.js"></script>

<!-- JARVIS WIDGETS -->
<script src="<?php echo $rutaLayout['_js']; ?>smartwidgets/jarvis.widget.min.js"></script>

<!-- EASY PIE CHARTS -->
<script src="<?php echo $rutaLayout['_js']; ?>plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

<!-- WIZARD -->
<script src="<?php echo $rutaLayout['_js']; ?>plugin/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
<script src="<?php echo $rutaLayout['_js']; ?>plugin/fuelux/wizard/wizard.js"></script>

<!-- SPARKLINES -->
<script src="<?php echo $rutaLayout['_js']; ?>plugin/sparkline/jquery.sparkline.min.js"></script>

<!-- JQUERY VALIDATE -->
<script src="<?php echo $rutaLayout['_js']; ?>plugin/jquery-validate/jquery.validate.min.js"></script>

<!-- JQUERY MASKED INPUT -->
<script src="<?php echo $rutaLayout['_js']; ?>plugin/masked-input/jquery.maskedinput.min.js"></script>

<!-- JQUERY SELECT2 INPUT -->
<script src="<?php echo $rutaLayout['_js']; ?>plugin/select2/select2.min.js"></script>

<!-- JQUERY DROPZONE -->
<script src="<?php echo $rutaLayout['_js']; ?>plugin/dropzone/dropzone.min.js"></script>

<!-- JQUERY UI + Bootstrap Slider -->
<script src="<?php echo $rutaLayout['_js']; ?>plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

<!-- FLOT -->
<script src="<?php echo BASE_URL ?>public/js/amcharts/amcharts.js"></script>
<script src="<?php echo BASE_URL ?>public/js/amcharts/serial.js"></script>
<script src="<?php echo BASE_URL ?>public/js/amcharts/themes/light.js"></script>
<!-- browser msie issue fix -->
<script src="<?php echo $rutaLayout['_js']; ?>plugin/msie-fix/jquery.mb.browser.min.js"></script>

<script src="<?php echo $rutaLayout['_js']; ?>plugin/datatables/jquery.dataTables-cust.js"></script> 
<script src="<?php echo $rutaLayout['_js']; ?>plugin/datatables/ColReorder.min.js"></script> 
<script src="<?php echo $rutaLayout['_js']; ?>plugin/datatables/FixedColumns.min.js"></script> 
<script src="<?php echo $rutaLayout['_js']; ?>plugin/datatables/ColVis.min.js"></script> 
<script src="<?php echo $rutaLayout['_js']; ?>plugin/datatables/ZeroClipboard.js"></script> 
<script src="<?php echo $rutaLayout['_js']; ?>plugin/datatables/media/js/TableTools.min.js"></script> 
<script src="<?php echo $rutaLayout['_js']; ?>plugin/datatables/DT_bootstrap.js"></script> 

<!-- FastClick: For mobile devices: you can disable this in app.js-->
<script src="<?php echo $rutaLayout['_js']; ?>plugin/fastclick/fastclick.js"></script> 

<!--[if IE 7]>

<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

<![endif]-->

<!-- Demo purpose only -->
<script src="<?php echo $rutaLayout['_js']; ?>demo.js"></script>

<!-- MAIN APP JS FILE -->
<script src="<?php echo $rutaLayout['_js']; ?>app.js"></script>


<script src="<?php echo BASE_URL ?>public/js/simpleAjax.js"></script>
<script src="<?php echo BASE_URL ?>public/js/simpleScript.js"></script>
<script src="<?php echo BASE_URL ?>public/js/mensajes.js"></script>
<script src="<?php echo BASE_URL ?>libs/Aes/js/aes.js"></script>
<script src="<?php echo BASE_URL ?>libs/Aes/js/aesctr.js"></script>
<script src="<?php echo BASE_URL ?>libs/Aes/js/base64.js"></script>
<script src="<?php echo BASE_URL ?>libs/Aes/js/utf8.js"></script>
<script src="<?php echo BASE_URL ?>public/js/scrollTable.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
</body>  

<?php
/* autoload de los js de cada vista */
Obj::run()->Autoload->js('modules/', true);
?>
<script>
    pageSetUp();
    $('input[type="checkbox"]#smart-fixed-nav').click();
    $('input[type="checkbox"]#smart-fixed-ribbon').click();
    //$('input[type="checkbox"]#smart-fixed-navigation').click();
</script>

</html>
<?php require_once (ROOT . 'app' . DS . 'ConstantesJsR.php'); ?>
<?php require_once (ROOT . 'app' . DS . 'ConstantesJsD.php'); ?>

<script>
//    $.each($('script'),function(){
//        alert($(this).attr('src'))
//    });

</script>
<?php if(Session::get('sys_usuario')):?>
<script>
    var activityTimeout = 0;
    function test() {
        var activityTimeout = null;
        $(document).mousemove(function(event) {
            if (activityTimeout) {
                clearTimeout(activityTimeout);
            }
            activityTimeout = setTimeout(function() {
                index.inactividad();
            }, 600000);
        });
    }
    test();
</script>
<?php endif; ?>
