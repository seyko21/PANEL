<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-08-2014 08:08:50 
* Descripcion : clienteController.php
* ---------------------------------------
*/    

class clienteController extends Controller{

    public function __construct() {
        $this->loadModel("cliente");
    }
    
    public function index(){ 
        Obj::run()->View->render("indexCliente");
    }
    
    public function getGridCliente(){
        /*-----------CONFIGURAR DATA PARA GRID---------*/
    }
    
    /*carga formulario (newCliente.phtml) para nuevo registro: Cliente*/
    public function getFormNewCliente(){
        Obj::run()->View->render("formNewCliente");
    }
    
    /*carga formulario (editCliente.phtml) para editar registro: Cliente*/
    public function getFormEditCliente(){
        Obj::run()->View->render("formEditCliente");
    }
    
    /*envia datos para grabar registro: Cliente*/
    public function postNewCliente(){
        $data = Obj::run()->clienteModel->newCliente();
        
        echo json_encode($data);
    }
    
    /*envia datos para editar registro: Cliente*/
    public function postEditCliente(){
        $data = Obj::run()->clienteModel->editCliente();
        
        echo json_encode($data);
    }
    
    /*envia datos para eliminar registro: Cliente*/
    public function postDeleteClienteAll(){
        $data = Obj::run()->clienteModel->deleteClienteAll();
        
        echo json_encode($data);
    }
    
}

?>