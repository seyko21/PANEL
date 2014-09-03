<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 03-09-2014 14:09:13 
* Descripcion : regInversionModel.php
* ---------------------------------------
*/ 

class regInversionModel extends Model{

    private $_flag;
    private $_idRegInversion;
    private $_usuario;
    
    /*para el grid*/
    private $_iDisplayStart;
    private $_iDisplayLength;
    private $_iSortingCols;
    private $_sSearch;
    
    public function __construct() {
        parent::__construct();
        $this->_set();
    }
    
    private function _set(){
        $this->_flag                    = Formulario::getParam("_flag");
        $this->_idRegInversion   = Aes::de(Formulario::getParam("_idRegInversion"));    /*se decifra*/
        $this->_usuario                 = Session::get("sys_idUsuario");
        
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    /*grabar nuevo registro: RegInversion*/
    public function newRegInversion(){
        /*-------------------------LOGICA PARA EL INSERT------------------------*/
    }
    
    /*seleccionar registro a editar: RegInversion*/
    public function findRegInversion(){
        /*-----------------LOGICA PARA SELECT REGISTRO A EDITAR-----------------*/
    }
    
    /*editar registro: RegInversion*/
    public function editRegInversion(){
        /*-------------------------LOGICA PARA EL UPDATE------------------------*/
    }
    
    /*eliminar varios registros: RegInversion*/
    public function deleteRegInversionAll(){
        /*--------------------------LOGICA PARA DELETE--------------------------*/
    }
    
}

?>