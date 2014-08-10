<?php
/*
* --------------------------------------
* fecha: 10-08-2014 05:08:41 
* Descripcion : registrarVendedorController.php
* --------------------------------------
*/    

class registrarVendedorController extends Controller{

    public function __construct() {
        $this->loadModel('registrarVendedor');
    }
    
    public function index(){ 
        Obj::run()->View->render('indexRegistrarVendedor');
    }
    
}

?>