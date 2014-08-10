<?php
/*
 * Documento   : loginController
 * Creado      : 30-ene-2014, 19:25:17
 * Autor       : RDCC
 * Descripcion :
 */
class loginController extends Controller{
    
    public function __construct() {
        $this->loadModel('login');
    }

    public function index() {
        $data = Obj::run()->loginModel->getValidar();
  
        if(isset($data['id_usuario'])){
            Session::set('sys_idUsuario', $data['id_usuario']);
            Session::set('sys_usuario', $data['usuario']);
            Session::set('sys_nombreUsuario', $data['nombrecompleto']);
            /*los roles*/
            Session::set('sys_roles', Obj::run()->loginModel->getRoles());
            /*asignando rol por defecto*/
            $rol = Session::get('sys_roles');
            Session::set('sys_defaultRol',$rol[0]['id_rol']);
        }
        
        echo json_encode($data);
    }
    
    public function logout(){
        Session::destroy();
        $result = array('result' =>1);
        echo json_encode($result);
    }
    
}
?>
