<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 03-09-2014 14:09:13 
* Descripcion : regInversionController.php
* ---------------------------------------
*/    

class regInversionController extends Controller{

    public function __construct() {
        $this->loadModel("regInversion");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexRegInversion");
    }
    
    public function getGridRegInversion(){
        /*-----------CONFIGURAR DATA PARA GRID---------*/
    }
    
    /*carga formulario (newRegInversion.phtml) para nuevo registro: RegInversion*/
    public function getFormNewRegInversion(){
        Obj::run()->View->render("formNewRegInversion");
    }
    
    /*carga formulario (editRegInversion.phtml) para editar registro: RegInversion*/
    public function getFormEditRegInversion(){
        Obj::run()->View->render("formEditRegInversion");
    }
    
    /*envia datos para grabar registro: RegInversion*/
    public function postNewRegInversion(){
        $data = Obj::run()->regInversionModel->newRegInversion();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: RegInversion*/
    public function postEditRegInversion(){
        $data = Obj::run()->regInversionModel->editRegInversion();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: RegInversion*/
    public function postDeleteRegInversionAll(){
        $data = Obj::run()->regInversionModel->deleteRegInversionAll();
        
        echo json_encode($data);
    }
    
}

?>