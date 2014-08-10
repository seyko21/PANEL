<?php
/*
 * --------------------------------------
 * creado por:  RDCC
 * fecha: 03.01.2014
 * indexController.php
 * --------------------------------------
 */
class indexController extends Controller{
    
    public function __construct() {
        $this->loadModel('index');
        $this->loadModel('login');
    }

    public function index(){ 
//        Session::destroy();
        if(Session::get('sys_idUsuario')){  
            Session::set('sys_menu', $this->getMenu());
            Obj::run()->View->render('index',false);
        }else{
            Obj::run()->View->render('login',false);
        }
    }

    private function getMenu(){
        return Obj::run()->loginModel->getMenu();
    }
    
    public function getChangeRol(){
        
        $idRol =  $this->post('_idRol');
         
        foreach (Session::get('sys_roles') as $value) {
            if($value['id_rol'] == $idRol){
                Session::set('sys_defaultRol', $value['id_rol']);
            }
        }
        $result = array('result'=> 1);
        echo json_encode($result);
    }
    
    public static function getAccionesOpcion($opcion){
        return Obj::run()->loginModel->getAccionesOpcion($opcion);
    }
    
    public static function getDominios(){
        Obj::run()->View->render('dominios');
    }
    
    public static function getModulos($dominio=''){
        Obj::run()->View->dominio = $dominio; 
        Obj::run()->View->render('menu');
    }
    
    public function getLock(){
        Session::destroy();
        Obj::run()->View->usuario = Session::get('sys_usuario');
        Obj::run()->View->nameUsuario = Session::get('sys_nombreUsuario');
        Obj::run()->View->render('lock');
    }
    
}

?>
