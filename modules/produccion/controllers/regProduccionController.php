<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : regProduccionController.php
* ---------------------------------------
*/    

class regProduccionController extends Controller{

    public function __construct() {
        $this->loadModel("regProduccion");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexRegProduccion");
    }
    
    public function getGridRegProduccion(){
        /*-----------CONFIGURAR DATA PARA GRID---------*/
    }
    
    /*carga formulario (newRegProduccion.phtml) para nuevo registro: RegProduccion*/
    public function getFormNewRegProduccion(){
        Obj::run()->View->render("formNewRegProduccion");
    }
    
    /*carga formulario (editRegProduccion.phtml) para editar registro: RegProduccion*/
    public function getFormEditRegProduccion(){
        Obj::run()->View->render("formEditRegProduccion");
    }
    
    /*envia datos para grabar registro: RegProduccion*/
    public function postNewRegProduccion(){
        $data = Obj::run()->regProduccionModel->newRegProduccion();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: RegProduccion*/
    public function postEditRegProduccion(){
        $data = Obj::run()->regProduccionModel->editRegProduccion();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: RegProduccion*/
    public function postDeleteRegProduccionAll(){
        $data = Obj::run()->regProduccionModel->deleteRegProduccionAll();
        
        echo json_encode($data);
    }
    
}

?>