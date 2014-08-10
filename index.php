<?php
define('DS',DIRECTORY_SEPARATOR);
define('ROOT',  realpath(dirname(__FILE__)) . DS);

require_once (ROOT . 'app' . DS . 'Config.php');
require_once (ROOT . 'app' . DS . 'Constantes.php');
require_once (ROOT . 'app' . DS . 'Labels.php');

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
