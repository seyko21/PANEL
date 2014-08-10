<?php
/*
 * Documento   : Singleton
 * Creado      : 20-ene-2014, 17:13:26
 * Autor       : RDCC
 * Descripcion : Valida que una clase se instancie una sola vez
 */
class Singleton{
    
    private static $_instancia;
    private $_data;
    
    private function __construct() {}    
    /*singleton*/
    public static function getInstancia(){
        if(!self::$_instancia instanceof self){
            self::$_instancia = new Singleton();
        }
        return self::$_instancia;
    }
    
    public function __set($name, $value) {
        $this->_data[$name] = $value;
    }
    
    public function __get($name) {
        if(isset($this->_data[$name])){            
            return $this->_data[$name];
        }else{
            return false;
        }
    }
    
    /*Evita que el objeto se pueda clonar*/
    public function __clone()
    {
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR);
    }
}
?>
