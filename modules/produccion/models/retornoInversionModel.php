<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 02-10-2014 23:10:16 
* Descripcion : retornoInversionModel.php
* ---------------------------------------
*/ 

class retornoInversionModel extends Model{

    private $_flag;
    private $_idProducto;
    private $_idSocio;    
    private $_idPersona;
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
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_idPersona   = Session::get("sys_idPersona");
        
        $this->_idSocio   = Aes::de(Formulario::getParam("_idSocio"));    /*se decifra*/
        $this->_idProducto   = Aes::de(Formulario::getParam("_idProducto"));    /*se decifra*/
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: RetornoInversion*/
    public function getRetornoInversion(){
       $aColumns       =   array("","socio","codigos","ubicacion","porcentaje_ganacia","inversion","ingresos","roi" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_prodConsultaROIGrid(:acceso, :idPersona, :iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':acceso' => Session::get('sys_all'),
            ':idPersona' => $this->_idPersona,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
 /*data para el grid: RetornoInversion*/
    public function getGridRoiOs(){
       $aColumns       =   array("orden_numero","codigo","fecha","cantidad_mes","importe","impuesto","monto_total","comision_venta","egresos","total_utilidad","monto_utilidad" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_prodConsultaROIOSGrid(:idSocio,:idProducto, :iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(            
            ':idSocio' => $this->_idSocio,
            ':idProducto' => $this->_idProducto,            
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }  
    
}

?>