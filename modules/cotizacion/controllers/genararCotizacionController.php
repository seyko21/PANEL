<?php
/*
* --------------------------------------
* fecha: 14-08-2014 05:08:10 
* Descripcion : genararCotizacionController.php
* --------------------------------------
*/    

class genararCotizacionController extends Controller{

    public function __construct() {
        $this->loadModel('genararCotizacion');
    }
    
    public function index(){ 
        Obj::run()->View->render('indexGenerarCotizacion');
    }
    
    public function getNuevoGenerarCotizacion(){
        Obj::run()->View->render('nuevoGenerarCotizacion'); 
    }
    
}

?>