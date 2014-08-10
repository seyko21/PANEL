<?php
/*
 * --------------------------------------
 * creado por:  RDCC
 * fecha: 03.01.2014
 * View.php
 * --------------------------------------
 */

class View{
    
    private static $_instancias = array();
    
    public function __construct() {
        self::$_instancias[] = $this;
        if(count(self::$_instancias) > 1){
            throw new Exception('Error: class View ya se instancio; para acceder a la instancia ejecutar: Obj::run()->NOMBRE_REGISTRO');
        }
    }

    public function render($vista,$ajax = true){
        
        $rutaLayout = array(
            '_img' => BASE_URL .'theme/' . DEFAULT_LAYOUT . '/img/',
            '_css' => BASE_URL .'theme/' . DEFAULT_LAYOUT . '/css/',
            '_js' => BASE_URL .'theme/' . DEFAULT_LAYOUT . '/js/'
        );
        
        $rutaVista = ROOT . 'modules' . DS . Obj::run()->Request->getModulo() . DS . 'views' . DS . $vista . '.phtml';
        
        if(is_readable($rutaVista)){
            if($ajax){
                /*cuando peticion es via ajax no se necesita el header y el footer*/
                require_once ($rutaVista);
            }else{
                require_once (ROOT . 'theme' . DS . DEFAULT_LAYOUT . DS . 'header.php');
                require_once ($rutaVista);
                require_once (ROOT . 'theme' . DS . DEFAULT_LAYOUT . DS . 'footer.php');
            }
        }else{
            throw new Exception('Error de vista: <b>'.$rutaVista.'</b> no encontrada .');
        }
        
    }
    
}
?>
