<?php
/*
 * Documento   : loginController
 * Creado      : 30-ene-2014, 19:25:17
 * Autor       : RDCC
 * Descripcion :
 */
class loginController extends Controller{
    
    public function __construct() {
        $this->loadModel(array('modulo'=>'index','modelo'=>'login'));
    }

    public function index() {
        $data = Obj::run()->loginModel->getValidar();
  
        if(isset($data['id_usuario'])){
            Session::set('sys_idUsuario', $data['id_usuario']);
            Session::set('sys_idPersona', $data['id_persona']);
            Session::set('sys_usuario', $data['usuario']);
            Session::set('sys_nombreUsuario', $data['nombrecompleto']);
            Obj::run()->loginModel->postLastLogin();
            /*los roles*/
            Session::set('sys_roles', Obj::run()->loginModel->getRoles());
            
            $rol = Session::get('sys_roles');
            /*asignando rol por defecto*/
            Session::set('sys_defaultRol',$rol[0]['id_rol']);
            
            /*
             * verifico si es SUPER ADMINISTRADOR (001) o ADMINISTRADOR (002)
             * esto servira para los reportes, si es super o adm tendra acceso a toda la informacion
             */
            foreach ($rol as $r) {
                if($r['id_rol'] == APP_COD_SADM || $r['id_rol'] == APP_COD_ADM){
                    Session::set('sys_all','S');
                    break;
                }else{
                    Session::set('sys_all','N');
                }
            }
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
