<?php
if (isset($_POST['txt_modulo'])) {
    $ruta = 'modules/';
    $modulo = trim($_POST['txt_modulo']);
    if ($modulo != '') {
//        $directorio = opendir($ruta); //ruta actual
//        while ($archivo = readdir($directorio)) { //obtenemos un archivo y luego otro sucesivamente
            if(!file_exists($ruta.$modulo)){
                //se crea el modulo
                mkdir($ruta.$modulo, 0700);
                //se crea estructura
                mkdir($ruta.$modulo.'/controllers', 0700);
                mkdir($ruta.$modulo.'/models', 0700);
                mkdir($ruta.$modulo.'/views', 0700);
                mkdir($ruta.$modulo.'/views/js', 0700);
            }else{
                echo 'Modulo => <b>'.$modulo.'</b> ya esta creada.';
            }
//        }
    } else {
        echo 'Modulo vacio.';
    }
}
?>
<form id="formModulo" name="formModulo" action="command.modulo.sw.php" method="post">
    <table width="200" border="1">
        <tr>
            <td>Modulo:</td>
            <td>
                <input type="text" name="txt_modulo" id="txt_modulo" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><button type="submit">Crear modulo</button></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
</form>