<?php
/*
 * --------------------------------------
 * creado por:  RDCC
 * fecha: 03.01.2014
 * Controller.php
 * --------------------------------------
 */
abstract class Controller{
    
    #obliga a que todas las clases que heredan Controller tengan un metodo index,
    #esto es para la validacion del metodo en Request
    abstract public function index(); 
    
    protected function loadModel($modelo){
        $modelo = $modelo . 'Model';
        $rutaModelo = ROOT . 'modules' . DS . Obj::run()->Request->getModulo() . DS .'models' . DS . $modelo . '.php';
        
        if(is_readable($rutaModelo)){
            require_once ($rutaModelo);
            //$modelo = new $modelo;
            Registry::anonimous($modelo); /*registro del $modelo por unica vez*/
            
            //return Obj::run()->$modelo;
        }else{
            throw new Exception('Error de Modelo: <b>'.$rutaModelo.'</b> no encontrado');
        }
    }
    
    protected function getLibrary($libreria){
        $rutaLibreria = ROOT . 'libs' . DS . $libreria . DS . $libreria.'.php';
        
        if(is_readable($rutaLibreria)){
            require_once ($rutaLibreria);
        }else{
            throw new Exception('Error de Libreria: <b>'.$rutaLibreria.'</b> no encontrada.');
        }
    }
    /*transforma las comillas simples y dobles*/
    protected function getTexto($parametro){
        if(isset($_POST[$parametro]) && !empty($_POST[$parametro])){
            $_POST[$parametro] = htmlspecialchars($_POST[$parametro],ENT_QUOTES); 
            return $_POST[$parametro];
        }else{
            return '';
        }
    }
    
    /*retorna parametros*/
    protected function post($parametro){
        if(isset($_POST[$parametro]) && !empty($_POST[$parametro])){
            return $_POST[$parametro];
        }else{
            return false;
        }
    }
    
    protected function redirect($ruta = false){
        if($ruta){
            header('location:' . BASE_URL . $ruta);
        }else{
            header('location:' . BASE_URL);
        }
    }
    
}
?>
