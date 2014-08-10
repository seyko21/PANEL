<?php
/*
 * Documento   : configurarMenuController
 * Creado      : 05-jul-2014
 * Autor       : ..... .....
 * Descripcion : 
 */
class configurarMenuController extends Controller{
    
    public function __construct() {
        $this->loadModel('configurarMenu');
    }

    public function index(){
        Obj::run()->View->render('index');
    }
    
    public static function getDominios(){          
        $rResult = Obj::run()->configurarMenuModel->getDominios();
        return $rResult;
    }
    
    public static function getModulos(){          
        $rResult = Obj::run()->configurarMenuModel->getModulos();
        return $rResult;
    }
    
    public static function getMenuPrincipales(){          
        $rResult = Obj::run()->configurarMenuModel->getMenuPrincipales();
        return $rResult;
    }
    
    public static function getOpciones(){          
        $rResult = Obj::run()->configurarMenuModel->getOpciones();
        return $rResult;
    }
    
    public function getListaDominios(){
        Obj::run()->View->render('dominios');
    }
    
    public function getListaModulos(){
        Obj::run()->View->render('modulos');
    }
    
    public function getListaMenuPrincipal(){
        Obj::run()->View->render('menuPrincipales');
    }
    
    public function getListaOpciones(){
        Obj::run()->View->render('opciones');
    }
    
    public function getNuevoDominio(){
        Obj::run()->View->render('nuevoDominio');
    }
    
    public function getNuevoModulo(){
        Obj::run()->View->render('nuevoModulo');
    }
    
    public function getNuevoMenuPrincipal(){
        Obj::run()->View->render('nuevoMenuPrincipal');
    }
    
    public function getNuevaOpcion(){
        Obj::run()->View->render('nuevaOpcion');
    }
    
    public function getEditarDominio(){ 
        Obj::run()->View->_idDominio = $this->post('_idDominio'); 
        Obj::run()->View->render('editarDominio');
    }
    
    public function getEditarModulo(){ 
        Obj::run()->View->_idModulo = $this->post('_idModulo'); 
        Obj::run()->View->render('editarModulo');
    }
    
    public function getEditarMenuPrincipal(){ 
        Obj::run()->View->_idMenuPrincipal = $this->post('_idMenuPrincipal'); 
        Obj::run()->View->render('editarMenuPrincipal');
    }
    
    public function getEditarOpcion(){ 
        Obj::run()->View->_idOpcion = $this->post('_idOpcion'); 
        Obj::run()->View->render('editarOpcion');
    }
    
    public static function getDominio($id){ 
        $data = Obj::run()->configurarMenuModel->menuConsultas(2,$id);
        
        return $data;
    }
    
    public static function getModulo($id){ 
        $data = Obj::run()->configurarMenuModel->menuConsultas(4,$id);
        
        return $data;
    }
    
    public static function getOpcion($id){ 
        $data = Obj::run()->configurarMenuModel->menuConsultas(8,$id);
        
        return $data;
    }
    
    public static function getMenuPrincipal($id){ 
        $data = Obj::run()->configurarMenuModel->menuConsultas(6,$id);
        
        return $data;
    }
    
    public function postDominio(){ 
        $data = Obj::run()->configurarMenuModel->mantenimientoDominio();
        
        echo json_encode($data);
    }
    
    public function postDeleteDominio(){ 
        $data = Obj::run()->configurarMenuModel->mantenimientoDominio();
        
        echo json_encode($data);
    }
    
    public function postModulo(){ 
        $data = Obj::run()->configurarMenuModel->mantenimientoModulo();
        
        echo json_encode($data);
    }
    
    public function postDeleteModulo(){ 
        $data = Obj::run()->configurarMenuModel->mantenimientoModulo();
        
        echo json_encode($data);
    }
    
    public function postMenuPrincipal(){ 
        $data = Obj::run()->configurarMenuModel->mantenimientoMenuPrincipal();
        
        echo json_encode($data);
    }
    
    public function postDeleteMenuPrincipal(){ 
        $data = Obj::run()->configurarMenuModel->mantenimientoMenuPrincipal();
        
        echo json_encode($data);
    }
    
    public function postOpcion(){ 
        $data = Obj::run()->configurarMenuModel->mantenimientoOpcion();
        
        echo json_encode($data);
    }
    
    public function postDeleteOpcion(){ 
        $data = Obj::run()->configurarMenuModel->mantenimientoOpcion();
        
        echo json_encode($data);
    }
    
    public function postSortDominio(){ 
        $data = Obj::run()->configurarMenuModel->mantenimientoDominio();
        
        echo json_encode($data);
    }
    
    public function postOrdenarModulo(){ 
        $data = Obj::run()->configurarMenuModel->mantenimientoModulo();
        
        echo json_encode($data);
    }
    
    public function postOrdenarMenuPrincipal(){ 
        $data = Obj::run()->configurarMenuModel->mantenimientoMenuPrincipal();
        
        echo json_encode($data);
    }
    
    public function postSortOpciones(){ 
        $data = Obj::run()->configurarMenuModel->mantenimientoOpcion();
        
        echo json_encode($data);
    }
    
}
?>
