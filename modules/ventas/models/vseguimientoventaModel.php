<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 18-11-2014 17:11:41 
* Descripcion : vseguimientoventaModel.php
* ---------------------------------------
*/ 

class vseguimientoventaModel extends Model{

    private $_flag;
    private $_idVseguimientoventa;
    private $_montoAsignado;
    private $_fecha;
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
        $this->_idVseguimientoventa   = Aes::de(Formulario::getParam("_idVseguimientoventa"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_montoAsignado     = Functions::deleteComa(Formulario::getParam(VSEVE."txt_monto"));
        $this->_fecha     = Functions::cambiaf_a_mysql(Formulario::getParam(VSEVE."txt_fecha"));
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: Vseguimientoventa*/
    public function getVseguimientoventa(){
        $aColumns       =   array('','codigo_impresion','nombre_descripcion','fecha','moneda','monto_total', 'monto_saldo','estado' ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ventaSeguimientoVentaGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
       
    
    /*editar registro: Vseguimientoventa*/
    public function newPagoVenta(){
         $query = "call sp_ventaPagoMantenimiento(:flag,:key,:pago,:fecha,:usuario,:idSucursal);";
        $parms = array(
            ':flag' => 1,
            ':key' => $this->_idVseguimientoventa,
            ':pago' => $this->_montoAsignado,
            ':fecha' => $this->_fecha,                      
            ':usuario' => $this->_usuario,
            ':idSucursal'=> Session::get('sys_idSucursal')
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
   
    
}

?>