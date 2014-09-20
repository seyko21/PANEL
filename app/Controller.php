<?php
/*
 * --------------------------------------
 * creado por:  RDCC
 * fecha: 03.01.2014
 * Controller.php
 * --------------------------------------
 */
abstract class Controller extends Obj{
    
    /*para crear el onjeto $call;*/
    public function __construct() {
        parent::__construct();
    }

    #obliga a que todas las clases que heredan Controller tengan un metodo index,
    #esto es para la validacion del metodo en Request
    abstract public function index(); 
    
    /*para llamar controladores de un modulo a otro*/
    protected function loadController($control){
        $modulo = $control['modulo'];
        $controller = $control['controller'] . 'Controller';
        $rutaControlador = ROOT . 'modules' . DS . $modulo. DS . 'controllers' . DS . $controller . '.php';
        
        if (is_readable($rutaControlador)) {            
            require_once ($rutaControlador);
            Registry::anonimous($controller); /*registro el controlador por unica vez*/
        } else {            
            echo 'Error de Controlador: <b>'.$rutaControlador.'</b> no encontrado.';
        }
    }

    protected function loadModel($modelo){   
        /*se verifica si es array*/
        if(is_array($modelo)){ /*esto es para cuando modelo se llamara desde un modulo externo*/
            $mod = $modelo['modelo'] . 'Model';
            
            $rutaModelo = ROOT . 'modules' . DS . $modelo['modulo'] . DS .'models' . DS . $mod . '.php';
        }else{/*se carga modelo desde el mismo modulo*/
            $mod = $modelo . 'Model';
            $rutaModelo = ROOT . 'modules' . DS . Obj::run()->Request->getModulo() . DS .'models' . DS . $mod . '.php';
        }
        
        if(is_readable($rutaModelo)){
            require_once ($rutaModelo);
            Registry::anonimous($mod); /*registro del $modelo por unica vez*/
            
        }else{
            throw new Exception('Error de Modelo: <b>'.$rutaModelo.'</b> no encontrado');
        }
    }
    
    protected function getLibrary($libreria){
        $rutaLibreria = ROOT . 'libs' . DS . $libreria . '.php';
        
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
            return htmlspecialchars(trim($_POST[$parametro]),ENT_QUOTES);
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
