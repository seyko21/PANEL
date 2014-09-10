<?php
/*
 * Documento   : Session
 * Creado      : 06-ene-2014, 19:12:43
 * Autor       : RDCC
 * Descripcion :
 */
class Session{
    
    public static function init(){
        session_start();
    }
    
    public static function destroy($session = false){
        if($session){
            if(is_array($session)){
                foreach ($session as $se) {
                    if(isset($_SESSION[$se])){
                        unset($_SESSION[$se]);
                    }
                }
            }else{
                if(isset($_SESSION[$session])){
                    unset($_SESSION[$session]);
                }
            }
        }else{
            session_destroy();
        }
    }
    
    public static function set($clave,$valor){
        $_SESSION[$clave] = $valor;
    }
    
    public static function get($clave){
        if(isset($_SESSION[$clave])){
            return $_SESSION[$clave];
        }
    }
    
    public static function getPermiso($clave){
        foreach (Session::get('sys_permisos') as $value) {
            if($value['opcion'] == $clave){
                return array(
                    'accion'=>$value['accion'],
                    'permiso'=>$value['permiso'],
                    'icono'=>$value['icono'],
                    'theme'=>$value['theme']
                );
                exit;
            }
        }
    }
    
}
?>
