<?php

class perfilModel extends Model{
    
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
    
    public function findMiPerfil(){
        $query = "SELECT 
                    nombrecompleto,
                    nombres,
                    apellidopaterno,
                    apellidomaterno,
                    numerodocumento,
                    id_ubigeo,
                    direccion,
                    tipodocumento,
                    email,
                    sexo,
                    telefono 
                FROM mae_persona WHERE id_persona = :idPersona;";
        
        $parms = array(
            ':idPersona'=>$this->_idPersona
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function updatePerfil(){
        $query = " UPDATE mae_usuario SET clave = :clave WHERE id_usuario = :idUsuario; ";
        $parms = array(
            ':idUsuario' => $this->_usuario,
            ':clave' => md5($this->_clave.APP_PASS_KEY)
        );
        $this->execute($query, $parms);
        
        $data = array('result'=>1);
        return $data;
    }
    
}