<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 04-09-2014 07:09:53 
* Descripcion : seguimientoCotizacionModel.php
* ---------------------------------------
*/ 

class seguimientoCotizacionModel extends Model{

    private $_flag;
    private $_idCotizacion;
    private $_estado;
    private $_estadocb;
    private $_observacion;
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
        $this->_idCotizacion   = Aes::de(Formulario::getParam("_idCotizacion"));    /*se decifra*/
        $this->_estado  = Formulario::getParam("_estado"); 
        $this->_estadocb  = Formulario::getParam("_estadocb"); 
        $this->_observacion  = Formulario::getParam(SEGCO."txt_obs"); 
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: SeguimientoCotizacion*/
    public function getSeguimientoCotizacion(){
        $aColumns       =   array( 'cotizacion_numero','nombrecompleto','creador' ,'fechacoti','meses_contrato','vencimiento','mtotal'); //para la ordenacion y pintado en html
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
        
        $query = "call sp_cotiSeguimientoGrid(:acceso,:estado,:usuario,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":acceso" => Session::get('sys_all'),
            ":estado" => $this->_estadocb,
            ":usuario" => $this->_usuario,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    /*grabar nuevo registro: SeguimientoCotizacion*/
    public function newSeguimientoCotizacion(){
        $query = "CALL sp_cotiSeguimientoMantenimiento(:flag,:idCotizacion,:observacion,:estado,:usuario);" ;
        
        $parms = array(
            ':flag' => $this->_flag,
            ':idCotizacion' => $this->_idCotizacion,
            ':observacion' => $this->_observacion,
            ':estado' => $this->_estado,
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);            
        return $data;
    }
    
}

?>