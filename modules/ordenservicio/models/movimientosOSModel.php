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
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: MovimientosOS*/
    public function getMovimientosOS(){
        $aColumns       =   array("orden_numero","fecha_contrato","monto_total","monto_impuesto","ingresos","egresos","comision_vendedor","utilidad_principal"); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordSeConsultaMovimientoOSGrid(:f1,:f2,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":f1" => $this->_f1,
            ":f2" => $this->_f2,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getMovInstalacion(){
        $aColumns       =   array("codigo","ordenin_numero","ubicacion","fecha_instalacion","monto_total" ); //para la ordenacion y pintado en html
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
        $query = "call sp_ordseMOVOSInstalacionGrid(:idOrden,:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(
            ":idOrden" => $this->_idOS,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    public function getMovComision(){
        $aColumns       =   array("codigo","ubicacion","cantidad_mes","importe","porcentaje_comision","comision_venta" ); //para la ordenacion y pintado en html
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
        $query = "call sp_ordseMOVOSComisionGrid(:idOrden,:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(
            ":idOrden" => $this->_idOS,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }    
        
}

?>