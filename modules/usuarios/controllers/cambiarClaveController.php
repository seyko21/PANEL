<?php
class cambiarClaveController extends Controller{
    
    public function __construct() {
        $this->loadModel('cambiarClave');        
    }

    public function index(){ 
        Obj::run()->View->render('indexCambiarClave');
    }
    
    public function postCambiarClave(){
        $data = Obj::run()->cambiarClaveModel->cambiarClave();
        
        echo json_encode($data);
    }
    
}