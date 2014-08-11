<?php
/*
* --------------------------------------
* fecha: 10-08-2014 06:08:26 
* Descripcion : registrarVendedorModel.php
* --------------------------------------
*/ 

class registrarVendedorModel extends Model{

    private $_flag;
    private $_key;
    private $_idDepartamento;
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
        $this->_flag    = $this->post('_flag');
        $this->_key     = Aes::de($this->post('_key'));    /*se decifra*/
        $this->_idDepartamento = $this->post('_idDepartamento');
        $this->_usuario = Session::get('sys_idUsuario');
        
        $this->_iDisplayStart  =   $this->post('iDisplayStart'); 
        $this->_iDisplayLength =   $this->post('iDisplayLength'); 
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch');
    }
    
    public function getDepartamentos(){
        $query = "SELECT id_departamento,departamento FROM `ub_departamento` ";
        
        $parms = array();
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getProvincias(){
        $query = "SELECT id_provincia,provincia FROM `ubprovincia` WHERE LEFT(id_provincia,2) = :idDepartamento ";
        
        $parms = array(
            ':idDepartamento'=>$this->_idDepartamento
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }


}

?>