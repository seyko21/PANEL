<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 25-09-2014 23:09:15 
* Descripcion : movimientosOSModel.php
* ---------------------------------------
*/ 

class movimientosOSModel extends Model{

    private $_flag;
    private $_idOS;
    private $_f1;
    private $_f2;
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
        $this->_idOS   = Aes::de(Formulario::getParam("_idOS"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_f1    = Functions::cambiaf_a_mysql(Formulario::getParam("_f1"));
        $this->_f2    = Functions::cambiaf_a_mysql(Formulario::getParam("_f2"));
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
    }
    
    /*data para el grid: MovimientosOS*/
    public function getMovimientosOS(){
        $aColumns       =   array("orden_numero","fecha_contrato","monto_total_descuento","monto_impuesto","ingresos","egresos","comision_vendedor","utilidad_principal","otros_ingresos","otros_egresos","utilidad_secundaria"); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordSeConsultaMovimientoOSGrid(:f1,:f2,:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(
            ":f1" => $this->_f1,
            ":f2" => $this->_f2,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    
}

?>