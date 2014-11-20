<?php
date_default_timezone_set('America/Lima');
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
define('DS',DIRECTORY_SEPARATOR);
define('ROOT',  realpath(dirname(__FILE__)) . DS);

require_once (ROOT . 'app' . DS . 'Config.php');
require_once (ROOT . 'app' . DS . 'ConstantesD.php');
require_once (ROOT . 'app' . DS . 'ConstantesR.php');
require_once (ROOT . 'app' . DS . 'LabelsD.php');
require_once (ROOT . 'app' . DS . 'LabelsR.php');

Session::init();

try{
    /*registro de clases*/
    Registry::anonimous('Request');
    Registry::anonimous('Database');
    Registry::anonimous('View');    
    Registry::anonimous('Autoload');

    Bootstrap::run(Obj::run()->Request);    
}  
catch (Exception $e){
    echo $e->getMessage();
}
?>
