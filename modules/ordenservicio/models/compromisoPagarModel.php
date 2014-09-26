<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 24-09-2014 00:09:39 
* Descripcion : compromisoPagarModel.php
* ---------------------------------------
*/ 

class compromisoPagarModel extends Model{

    private $_flag;
    public $_idCompromiso;
    private $_f1;
    private $_f2;
    private $_estado;
    private $_usuario;
    
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
        $this->_idCompromiso   = Aes::de(Formulario::getParam("_idCompromiso"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_estado        = Formulario::getParam("_estadocb");
        $this->_f1    = Functions::cambiaf_a_mysql(Formulario::getParam("_f1"));
        $this->_f2    = Functions::cambiaf_a_mysql(Formulario::getParam("_f2"));
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: CompromisoPagar*/
    public function getCompromisoPagar(){
        $aColumns       =   array("orden_numero","numero_cuota","fecha_programada","7","11","monto_pago","estado" ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( "bSortable_".intval($this->post("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post("iSortCol_".$i) ) ]." ".
                                ($this->post("sSortDir_".$i)==="asc" ? "asc" : "desc") .",";
                }
        }
        $sOrder = substr_replace( $sOrder, "", -1 );
        
        $query = "call sp_ordSeConsultaCronogramaGrid(:f1,:f2,:estado,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":f1" => $this->_f1,
            ":f2" => $this->_f2,
            ":estado" => $this->_estado,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
   
     public function getPagos(){
        $query = "
        SELECT
            p.`id_pago`, 
            p.`numero_documento`,
            p.`serie_documento`,
            p.`tipo_documento`,
            p.`forma_pago`,
            p.`monto_pago`,
            p.`monto_subtotal`,
            p.`monto_impuesto`,
            p.`porcentaje_igv`,
            p.`fecha_pago`,
            p.`estado`,
            cp.`numero_cuota`,
            cp.`fecha_programada`,
            cp.`costo_mora`,
            os.`id_persona`,
            pe.`nombrecompleto`,
            pe.numerodocumento,
            (SELECT pp.nombrecompleto FROM mae_persona pp WHERE pp.`id_persona` = pe.`id_personapadre` ) AS razonsocial,
            (SELECT pp.numerodocumento FROM mae_persona pp WHERE pp.`id_persona` = pe.`id_personapadre` ) AS ruccliente,
            os.`orden_numero`
          FROM `lgk_pago` p
                  INNER JOIN `lgk_compromisopago` cp ON p.`id_compromisopago` = cp.`id_compromisopago`
                  INNER JOIN `lgk_ordenservicio` os ON os.`id_ordenservicio` = cp.`id_ordenservicio`
                  INNER JOIN `mae_persona` pe ON pe.`id_persona` = os.`id_persona`
          WHERE p.id_compromisopago = :idCompromiso AND
                p.estado = :estado;";
        
        $parms = array(
            ':idCompromiso' => $this->_idCompromiso,
            ':estado'=>'P'
        );
        $data = $this->queryAll($query,$parms);            
        return $data;
    }
}

?>