<?php

/*
 * Documento   : indexModel
 * Creado      : 30-ene-2014, 17:38:01
 * Autor       : RDCC
 * Descripcion :
 */

class indexModel extends Model {

    public $_usuario;
    
    public function __construct() {
        parent::__construct();
        $this->_usuario = Session::get("sys_idUsuario");
    }

    public function getFoto(){
        $query = "SELECT 
                    foto
                FROM mae_usuario WHERE id_usuario = :idUsuario;";
        
        $parms = array(
            ':idUsuario'=>$this->_usuario
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function postFoto($doc){
        $query = "UPDATE `mae_usuario` SET
                    `foto` = :foto
                WHERE `id_usuario` = :idUsuario;";
        $parms = array(
            ':idUsuario' => $this->_usuario,
            ':foto' => $doc
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }
    
    public function anulaCotizacionesVencidas(){
        $query = "CALL sp_cotiAnularCotizacion(:flag,:criterio);";
        $parms = array(
            ':flag' => 1,
            ':criterio' => ''
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }
    
    public function generarUtilidadSocio(){
        $query = "CALL sp_ordseCalcularUtilidadSocio(:flag,:criterio);";
        $parms = array(
            ':flag' => 1,
            ':criterio' => $this->_usuario
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }
    
}

?>
