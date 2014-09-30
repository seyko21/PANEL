<?php

class perfilController extends Controller{
    
    public function __construct() {
        $this->loadModel('perfil');        
    }

    public function index(){ 
        Obj::run()->View->render('indexPerfil');
    }
    
    public function findMiPerfil(){
        $data = Obj::run()->perfilModel->findMiPerfil();
        
        return $data;
    }
    
    public function postPerfil(){
        $data = Obj::run()->perfilModel->updatePerfil();
        
        echo json_encode($data);
    }
    
}