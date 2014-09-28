<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 26-09-2014 15:09:21 
* Descripcion : saldoClienteModel.php
* ---------------------------------------
*/ 

class saldoClienteModel extends Model{

    private $_flag;
    private $_idCompromiso;
    private $_estadocb;
    private $_usuario;
    private $_f1;
    private $_f2;
    
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
        $this->_idCompromiso  = Aes::de(Formulario::getParam("_idCompromiso"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_estadocb  = Formulario::getParam("_estadocb"); 
        $this->_f1    = Functions::cambiaf_a_mysql(Formulario::getParam("_f1"));
        $this->_f2    = Functions::cambiaf_a_mysql(Formulario::getParam("_f2")); 
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: SaldoCliente*/
    public function getSaldoCliente(){
        $aColumns       =   array("orden_numero","numero_cuota","fecha_programada","cliente","costo_mora","monto_pago" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_pagoConsultaSaldoClienteGrid(:f1,:f2,:estado,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":f1" => $this->_f1,
            ":f2" => $this->_f2,  
            ":estado"=>$this->_estadocb,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
  
}

?>