<?php
/*
* --------------------------------------
* fecha: 10-08-2014 06:08:26 
* Descripcion : registrarVendedorModel.php
* --------------------------------------
*/ 

class registrarVendedorModel extends Model{

    private $_flag;
    private $_idPersona;
    private $_idDepartamento;
    private $_idProvincia;
    private $_apellidoPaterno;
    private $_apellidoMaterno;
    private $_nombres;
    private $_sexo;
    private $_direccion;
    private $_email;
    private $_telefono;
    private $_numeroDoc;
    private $_ubigeo;
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
        $this->_idPersona     = Aes::de($this->post('_idPersona'));    /*se decifra*/
        $this->_idDepartamento = $this->post('_idDepartamento');
        $this->_idProvincia = $this->post('_idProvincia');
        $this->_apellidoPaterno = $this->post(T7.'txt_apellidopaterno');
        $this->_apellidoMaterno = $this->post(T7.'txt_apellidomaterno');
        $this->_nombres = $this->post(T7.'txt_nombres');
        $this->_sexo = $this->post(T7.'rd_sexo');
        $this->_direccion = $this->post(T7.'txt_direccion');
        $this->_email = $this->post(T7.'txt_email');
        $this->_telefono = $this->post(T7.'txt_telefonos');
        $this->_numeroDoc = $this->post(T7.'txt_nrodocumento');
        $this->_ubigeo = $this->post(T7.'lst_ubigeo');
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
        $query = "SELECT id_provincia,provincia FROM `ub_provincia` WHERE LEFT(id_provincia,2) = :idDepartamento ";
        
        $parms = array(
            ':idDepartamento'=>$this->_idDepartamento
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }

    public function getUbigeo(){
        $query = "SELECT id_ubigeo,distrito FROM `ub_ubigeo` WHERE LEFT(id_ubigeo,4) = :idProvincia ";
        
        $parms = array(
            ':idProvincia'=>$this->_idProvincia
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function mantenimientoVendedor(){
        $query = "call sp_perVendedorMantenimiento(
                    :flag,
                    :idPersona,
                    :apellidoPaterno,
                    :apellidoMaterno,
                    :nombres,
                    :sexo,
                    :direccion,
                    :email,
                    :telefono,
                    :numeroDoc,
                    :ubigeo,
                    :usuario
                );";
        $parms = array(
            ':flag' => $this->_flag,
            ':idPersona' => $this->_idPersona,
            ':apellidoPaterno' => $this->_apellidoPaterno,
            ':apellidoMaterno' => $this->_apellidoMaterno,
            ':nombres' => $this->_nombres,
            ':sexo' => $this->_sexo,
            ':direccion' => $this->_direccion,
            ':email' => $this->_email,
            ':telefono' => $this->_telefono,
            ':numeroDoc' => $this->_numeroDoc,
            ':ubigeo' => $this->_ubigeo,
            ':usuario' => $this->_usuario
        );
         
        $data = $this->queryOne($query,$parms);  
        return $data;
    }
    
}

?>