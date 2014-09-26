<?php
/*
 * --------------------------------------
 * creado por:  RDCC
 * fecha: 03.01.2014
 * Config.php
 * --------------------------------------
 */
define('BASE_URL','http://'.$_SERVER['HTTP_HOST'].'/PANEL/');#accede a las vistas delusuario
define('DEFAULT_CONTROLLER','index');
define('DEFAULT_LAYOUT','stardadmin');

define('APP_NAME','SISTEMA SEVEND');
define('APP_SLOGAN','SEVEND MARKETING');
define('APP_COMPANY','www.sevend.pe');
define('APP_KEY','adABKCDLZEFXGHIJ');
define('APP_PASS_KEY','99}dF7EZbnbXOkojf&dzvxd5q#guPbPK1spU75Jm|N79Ii7PK');
define('APP_EXPORT_FILES',ROOT . 'public' . DS . 'files' . DS);
define('APP_COD_SADM','0001');
define('APP_COD_ADM','0002');
define('APP_COD_VEND','0003');

define('DB_ENTORNO','D');  /*D=DESARROLLO, P=PRODUCCION*/
define('DB_MOTOR','mysql');

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','beholiac_sevend');

define('DB_PORT','3306');
define('DB_CHARSET','utf8');

define('REG_X_PAGINA',10);
define('ITEM_PAGINADOR',5);

/*__autoload es obsoleta*/
function autoloadCore($class){
    if(file_exists(ROOT . 'app' . DS . $class.'.php')){
        require_once (ROOT . 'app' . DS . $class.'.php');
    }
}

function autoloadLibs($class){
    if(file_exists(ROOT . 'libs' . DS . $class . DS . $class.'.php')){
        require_once (ROOT . 'libs' . DS . $class . DS . $class.'.php');
    }
}

/*se registra la funcion autoload*/
spl_autoload_register('autoloadCore'); 
spl_autoload_register('autoloadLibs');

?>