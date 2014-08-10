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
    
}

?>