<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 16-09-2014 22:09:43 
* Descripcion : seguimientoPagoModel.php
* ---------------------------------------
*/ 

class seguimientoPagoModel extends Model{

    private $_flag;
    private $_idOrden;
    private $_idCompromiso;
    private $_tipoDoc;
    private $_numDoc;
    private $_serieDoc;
    private $_fecha;
    private $_formaPago;
    private $_estadocb;
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
        $this->_idOrden   = Aes::de(Formulario::getParam("_idOrden"));    /*se decifra*/
        $this->_idCompromiso   = Aes::de(Formulario::getParam("_idCompromiso"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_tipoDoc  = Formulario::getParam(SEGPA."lst_tipodoc");
        $this->_numDoc  = Formulario::getParam(SEGPA."txt_seriedoc");
        $this->_serieDoc  = Formulario::getParam(SEGPA."txt_numdoc");
        $this->_fecha  = Functions::cambiaf_a_mysql(Formulario::getParam(SEGPA."txt_fechapago"));
        $this->_formaPago  = Formulario::getParam(SEGPA."lst_formaPago");
        $this->_estadocb  = Formulario::getParam("_estadocb"); 
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: SeguimientoPago*/
    public function getSeguimientoPago(){
        $aColumns       =   array("","2","13","18","12","5" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordseSeguimientoPagoGrid(:estado,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":estado" => $this->_estadocb,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getCronograma(){
        $query = "CALL sp_ordseOrdenServicioConsultas(:flag,:idOrden);";
        
        $parms = array(
            ":flag" => 3,
            ":idOrden" => $this->_idOrden
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function postPagarOrden(){
        $query = "CALL sp_ordseSeguimientoPagoPagarCuota(:flag,:idCompromiso,:tipoDoc,:numDoc,:serieDdoc,:usuario,:fecha,:obs,:perfilVendedor);";
        
        $parms = array(
            ":flag" => 1,
            ":idCompromiso" => $this->_idCompromiso,
            ":tipoDoc" => $this->_tipoDoc,
            ":numDoc" => $this->_numDoc,
            ":serieDdoc" => $this->_serieDoc,
            ":usuario" => $this->_usuario,
            ":fecha" => $this->_fecha,
            ":obs" => $this->_formaPago,
            ":perfilVendedor" => APP_COD_VEND
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
}

?>