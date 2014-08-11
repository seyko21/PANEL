<?php
/*
 * Documento   : Rregistry
 * Creado      : 21-ene-2014, 13:56:56
 * Autor       : RDCC
 * Descripcion : registra las clases para luego ser instanciadas con Obj::run()->XXXX
 */
class Registry{
    
    public static $get;

    public static function init() {
        if(!self::$get){
            self::$get = Singleton::getInstancia();
        }
    }

    public static function anonimous($class){
        self::init();
        if(!self::$get->$class){
            /*el objeto se instancia solo una vez*/
            return self::$get->$class = new $class;
        }
    }
    
}
?>
