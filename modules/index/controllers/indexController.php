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
    
    public function getPanel(){
        Obj::run()->View->render('panel');
    }

    public function getPanelCliente(){
        Obj::run()->View->render('panelCliente');
    }
    
    public function getPanelVendedor(){
        Obj::run()->View->render('panelVendedor');
    }
    
    public function getPanelSocio(){
        Obj::run()->View->render('panelSocio');
    }    
    
    public function getPanelCajero(){
        Obj::run()->View->render('panelCajero');
    }  
    
    public static function getDominios(){
        Obj::run()->View->render('dominios');
    }
    
    public static function getOpcionesUser(){
        Obj::run()->View->render('opcionesUser');
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
    
    public function errorPage(){
        Obj::run()->View->render('errorPage',false);
    }
    
    public function changeFoto(){
        Obj::run()->View->render('formChangeFoto');
    }
    
    public function forgotpassword(){
        Obj::run()->View->render('forgotpassword',false);
    }
    
    public function postFoto() {
        $p = Obj::run()->indexModel->_usuario;
        
        if (!empty($_FILES)) {
            $targetPath = ROOT . 'public' . DS .'files' .DS . 'fotos' . DS;
            $tempFile = $_FILES['file']['tmp_name'];
            
            $file = $p.'_'.$_FILES['file']['name'];
            $targetFile = $targetPath.$file;
            if (move_uploaded_file($tempFile, $targetFile)) {
               $array = array("img" => $targetPath, "thumb" => $targetPath,'archivo'=>$file);
               
               Obj::run()->indexModel->postFoto($file);
            }
            echo json_encode($array);
        }
    }
    
    public function postAnulaCotizacionesVencidas(){
        if(Session::get('sys_defaultRol') == APP_COD_ADM || Session::get('sys_defaultRol') == APP_COD_SADM || Session::get('sys_defaultRol') == APP_COD_VEND){
            $data = Obj::run()->indexModel->anulaCotizacionesVencidas();
            echo json_encode($data);
        }
    }
    
    public function postGenerarUtilidadSocio(){
        if(Session::get('sys_defaultRol') == APP_COD_ADM || Session::get('sys_defaultRol') == APP_COD_SADM){
            $data = Obj::run()->indexModel->generarUtilidadSocio();
            echo json_encode($data);
        }
    }
    
    public function getIndexGraficoIngreso() {
        $data = Obj::run()->indexModel->getIndexGraficoIngreso();
        return $data;
    }
    
    public function getIndexGraficoIngresoSocio() {
        $data = Obj::run()->indexModel->getIndexGraficoIngresoSocio();
        return $data;
    }
       
}

?>
