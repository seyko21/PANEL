<?php
/*
* --------------------------------------
* fecha: 10-08-2014 06:08:26 
* Descripcion : registrarVendedorController.php
* --------------------------------------
*/    

class registrarVendedorController extends Controller{

    public function __construct() {
        $this->loadModel('registrarVendedor');
    }
    
    public function index(){ 
        Obj::run()->View->render('indexVendedor');
    }
    
    public function getNuevoVendedor(){ 
        Obj::run()->View->render('nuevoVendedor');
    }
    
    public static function getDepartamentos(){ 
        $data = Obj::run()->registrarVendedorModel->getDepartamentos();
        
        return $data;
    }
    
    public static function getProvincias(){
        $data = Obj::run()->registrarVendedorModel->getProvincias();
        
        return $data;
    }

    public function postNuevoVendedor(){
        $data = Obj::run()->registrarVendedorModel->mantenimientoregistrarVendedorModel();

        echo json_encode($data);
    }

}

?>