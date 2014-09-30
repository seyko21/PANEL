<?php

class cambiarClaveModel extends Model{
    
    private $_clave;
    private $_usuario;

    public function __construct() {
        parent::__construct();
        $this->_set();
    }
    
    private function _set(){
        $this->_clave = Formulario::getParam(CLAV.'txt_clave');    /*se decifra*/
        $this->_usuario = Session::get('sys_idUsuario');
    }
    
    public function cambiarClave(){
        $query = " UPDATE mae_usuario "
                . "SET clave = :clave,  clave_comun = :comun "
                . " WHERE id_usuario = :idUsuario; ";
        $parms = array(
            ':idUsuario' => $this->_usuario,
            ':clave' => md5($this->_clave.APP_PASS_KEY),
            ':comun' => $this->_clave
        );
        $this->execute($query, $parms);
        
        $data = array('result'=>1);
        return $data;
    }
    
}