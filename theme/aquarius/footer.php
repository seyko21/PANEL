     </div><!--fin content-aplication-->
</body>  
<script src="<?php echo BASE_URL ?>libs/Aes/js/aes.js"></script>
<script src="<?php echo BASE_URL ?>libs/Aes/js/aesctr.js"></script>
<script src="<?php echo BASE_URL ?>libs/Aes/js/base64.js"></script>
<script src="<?php echo BASE_URL ?>libs/Aes/js/utf8.js"></script>
<script src="<?php echo BASE_URL ?>public/js/alertify.js"></script>
<?php
/* autoload de los js de cada vista */
Obj::run()->Autoload->js('modules/', true);
?>
</html>
