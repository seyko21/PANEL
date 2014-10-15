<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 11-10-2014 16:10:11 
* Descripcion : asignarPanelSocioModel.php
* ---------------------------------------
*/ 

class asignarPanelSocioModel extends Model{

    private $_flag;
    private $_idAsignacionPanel;
    private $_xSearch;
    private $_usuario;
    private $_idPersona;
    private $_socio;
    private $_producto;
    private $_ganancia;
    private $_inversiones;
    private $_montoInvertir;
    private $_totalInvertido;

    /*para el grid*/
    public  $_iDisplayStart;
    private $_iDisplayLength;
    private $_iSortingCols;
    private $_sSearch;
    
    public function __construct() {
        parent::__construct();
        $this->_set();
    }
    
    private function _set(){
        $this->_flag        = Formulario::getParam("_flag");
        $this->_idAsignacionPanel   = Aes::de(Formulario::getParam("_idAsignacionPanel"));    /*se decifra*/
        $this->_idPersona   = Aes::de(Formulario::getParam("_idPersona"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_xSearch        = Formulario::getParam(APASO."_term");
        $this->_socio        = Aes::de(Formulario::getParam(APASO."txt_idpersona"));
        $this->_producto        = Aes::de(Formulario::getParam(APASO."txt_idproducto"));
        $this->_ganancia        = Formulario::getParam(APASO."txt_ganancia");
        $this->_inversiones        = Formulario::getParam(APASO."hhidInversion");   /*array*/
        $this->_montoInvertir        = Formulario::getParam(APASO."txt_montoinvertir");  /*array*/
        $this->_totalInvertido        = Formulario::getParam(APASO."txt_montototal");
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: AsignarPanelSocio*/
    public function getAsignarPanelSocio(){
        $aColumns       =   array("nombrecompleto","ubicacion" ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( "bSortable_".intval($this->post("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post("iSortCol_".$i) ) ]." ".
                                ($this->post("sSortDir_".$i)==="asc" ? "asc" : "desc") ." ";
                }
        }
        
        $query = "call sp_prodAsignarPanelSocioGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getSocios(){
       
        $query = "
         SELECT id_persona,nombrecompleto 
         FROM mae_persona WHERE monto_saldo > 0 AND nombrecompleto LIKE CONCAT('%".$this->_xSearch."%') ";
        
        $parms = array();
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getInversiones(){
       
        $query = "
        SELECT 
                id_inversion,
                DATE_FORMAT(fecha_inversion,'%d-%m-%Y')AS fecha,
                monto_saldo
        FROM prod_inversion WHERE monto_saldo > 0 AND id_persona = :idPersona;";
        
        $parms = array(
            ':idPersona'=> $this->_idPersona
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    /*grabar nuevo registro: AsignarPanelSocio*/
    public function newAsignarPanelSocio(){
        $query = "CALL sp_prodAsignarPanelSocioMantenimiento("
                    . ":flag,"
                    . ":idAsignacionPanel,"
                    . ":idPersona,"
                    . ":idProduccion,"
                    . ":idInversion,"
                    . ":montoInvertido,"
                    . ":totalInvertido,"
                    . ":ganancia,"
                    . ":usuario"
                . ");";
        
        /*la cabecera*/
        $parms = array(
            ':flag'=> 1,
            ':idAsignacionPanel'=> '',
            ':idPersona'=> $this->_socio,
            ':idProduccion'=> $this->_producto,
            ':idInversion'=> '',
            ':montoInvertido'=> '',
            ':totalInvertido'=> $this->_totalInvertido,
            ':ganancia'=> $this->_ganancia,
            ':usuario'=> $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        
        $idPanelSocio = $data['idAsignacionPanel'];
        
        if($data['result'] == 1){
            /*el detalle*/
            foreach ($this->_inversiones as $key=>$value) {
                $parms = array(
                    ':flag'=> 2,
                    ':idAsignacionPanel'=> $idPanelSocio,
                    ':idPersona'=> '',
                    ':idProduccion'=> '',
                    ':idInversion'=> AesCtr::de($value),
                    ':montoInvertido'=> $this->_montoInvertir[$key],
                    ':totalInvertido'=> '',
                    ':ganancia'=> '',
                    ':usuario'=> $this->_usuario
                );
                $this->execute($query,$parms);
            }
        }
        return $data;
    }
    
    public function postDeleteAsignarPanelSocio(){
        $query = "CALL sp_prodAsignarPanelSocioMantenimiento("
                    . ":flag,"
                    . ":idAsignacionPanel,"
                    . ":idPersona,"
                    . ":idProduccion,"
                    . ":idInversion,"
                    . ":montoInvertido,"
                    . ":totalInvertido,"
                    . ":ganancia,"
                    . ":usuario"
                . ");";
        $parms = array(
            ':flag'=> 3,
            ':idAsignacionPanel'=> $this->_idAsignacionPanel,
            ':idPersona'=> '',
            ':idProduccion'=> '',
            ':idInversion'=> '',
            ':montoInvertido'=> '',
            ':totalInvertido'=> '',
            ':ganancia'=> '',
            ':usuario'=> $this->_usuario
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
}

?>