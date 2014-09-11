<?php

/*
 * --------------------------------------
 * creado por:  RDCC
 * fecha: 03.01.2014
 * Bootstrap.php
 * --------------------------------------
 */

class Bootstrap {

    public static function run(Request $peticion) {
        $modulo = $peticion->getModulo();
        $controller = $peticion->getControlador() . 'Controller';
        $rutaControlador = ROOT . 'modules' . DS . $modulo. DS . 'controllers' . DS . $controller . '.php';
        
        $metodo = $peticion->getMetodo(); #se obtiene el metodo
        $args = $peticion->getArgumentos(); #se obtiene los argumentos

        if (is_readable($rutaControlador)) {
            require_once ($rutaControlador);
            Registry::anonimous($controller); /*registro el controlador por unica vez*/

            if (!is_callable(array(Obj::run()->$controller, $metodo))) {
                $metodo = 'index'; #si metodo no existe, index por defecto
            }

            if (isset($args)) {
                call_user_func_array(array(Obj::run()->$controller, $metodo), $args);
            } else {
                call_user_func(array(Obj::run()->$controller, $metodo));
            }
        } else {
            /*error de codumento no encontrado*/
            if($peticion->getControlador() == 'files'){
                header('location:' . BASE_URL.'index/index/errorPage/');
            }else{
                throw new Exception('Error de Controlador: <b>'.$rutaControlador.'</b> no encontrado.');
            }
        }
    }

}
?>
