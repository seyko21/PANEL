<?php

class perfilModel extends Model {

    private $_clave;
    private $_usuario;
    private $_idPersona;
    private $_nombres;
    private $_apellidopaterno;
    private $_apellidomaterno;
    private $_direccion;
    private $_email;
    private $_telefono;
            
            
            

    public function __construct() {
        parent::__construct();
        $this->_set();
    }

    private function _set() {
        $this->_nombres = Formulario::getParam(PERF . 'txt_nombres');
        $this->_apellidomaterno = Formulario::getParam(PERF . 'txt_materno');
        $this->_apellidopaterno = Formulario::getParam(PERF . 'txt_apepaterno');
        $this->_direccion = Formulario::getParam(PERF . 'txt_direccion');
        $this->_email = Formulario::getParam(PERF . 'txt_email');
        $this->_telefono = Formulario::getParam(PERF . 'txt_telefonos');
        
        $this->_usuario = Session::get('sys_idUsuario');
        $this->_idPersona = Session::get('sys_idPersona');
    }

    public function findMiPerfil() {
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
            ':idPersona' => $this->_idPersona
        );

        $data = $this->queryOne($query, $parms);
        return $data;
    }

    public function updatePerfil() {
        $query = " 
        UPDATE mae_persona SET 
                nombres = :nombres,
                apellidopaterno = :apellidopaterno,
                apellidomaterno = :apellidomaterno,
                nombrecompleto = :nombrecompleto,
                direccion = :direccion,
                email = :email,
                telefono = :telefono
        WHERE id_persona = :idPerfil; ";
        
        $parms = array(
            ':idPerfil' => $this->_idPersona,
            ':nombres' => $this->_nombres,
            ':apellidopaterno' => $this->_apellidopaterno,
            ':apellidomaterno' => $this->_apellidomaterno,
            ':nombrecompleto' => $this->_apellidopaterno.' '.$this->_apellidomaterno.' '.$this->_nombres,
            ':direccion' => $this->_direccion,
            ':email' => $this->_email,
            ':telefono' => $this->_telefono
        );
        $this->execute($query, $parms);

        //update correo de mae_usuario
        $queryx = " UPDATE mae_usuario SET usuario = :email WHERE id_usuario = :idUsuario ";
        
        $parmsx = array(
            ':idUsuario' => $this->_usuario,
            ':email' => $this->_email
        );
        $this->execute($queryx, $parmsx);
        
        $data = array('result' => 1);
        return $data;
    }

}
