                 </div><!--fin content-aplication-->
            </div><!--fin page-main-wrapper-->
        </div><!--fin page-main-->
    </div><!--fin page-sidebar-->
</div><!--fin page-wrapper-->



<div id="loader-overlay" class="ui-front hide loader ui-widget-overlay solid-gray opacity-60">
    <img src="<?php echo $rutaLayout['_img']; ?>loader-dark.gif" alt="" />
</div>


</body>  
<script type="text/javascript" src="<?php echo $rutaLayout['_js']; ?>minified/core/raphael.min.js"></script>
<script type="text/javascript" src="<?php echo $rutaLayout['_js']; ?>minified/widgets/charts-morris.min.js"></script>
<script src="<?php echo BASE_URL ?>public/js/jquery.easing.1.3.js"></script>
<script src="<?php echo BASE_URL ?>public/js/jquery-AeroWindow.js"></script>

<script src="<?php echo BASE_URL ?>public/js/mensajes.js"></script>
<script src="<?php echo BASE_URL ?>libs/aes/js/aes.js"></script>
<script src="<?php echo BASE_URL ?>libs/aes/js/aesctr.js"></script>
<script src="<?php echo BASE_URL ?>libs/aes/js/base64.js"></script>
<script src="<?php echo BASE_URL ?>libs/aes/js/utf8.js"></script>
<script src="<?php echo BASE_URL ?>libs/DataGrid/js/scrollTable.js"></script>
<div id="xxx"></div>
<?php
/* autoload de los js de cada vista */
Obj::run()->Autoload->js('modules/', true);
?>
<?php if(Session::get('sys_usuario')):?>
<script>
    var al = ($(window).height() - 52);
    $('#content-aplication').css('height',al);  
    
    $('#xxx').AeroWindow({
            WindowTitle: 'xxx',
            WindowPositionTop: 200,
            WindowPositionLeft: 300,
            WindowOuterWidth: 200,
            WindowOuterHeight: 300,
            WindowAnimationSpeed: 500,
            WindowStatus: 'closed',
            WindowContentClient: '#content-aplication'
        });
        
</script>
<?php endif; ?>
</html>
