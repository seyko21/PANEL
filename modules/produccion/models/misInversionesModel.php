<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 30-09-2014 00:09:45 
* Descripcion : misInversionesModel.php
* ---------------------------------------
*/ 

class misInversionesModel extends Model{

    private $_flag;
    private $_f1;
    private $_f2;
    private $_idPersona;
    private $_usuario;
    private $_idInversion;
    
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
        $this->_f1    = Functions::cambiaf_a_mysql(Formulario::getParam("_f1"));
        $this->_f2    = Functions::cambiaf_a_mysql(Formulario::getParam("_f2")); 
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_idPersona   = Session::get('sys_idPersona');
        $this->_idInversion = Aes::de(Formulario::getParam("_idInversion"));
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: MisInversiones*/
    public function getMisInversiones(){
        $aColumns       =   array("","2","fecha_inversion","monto_invertido","monto_asignado","monto_saldo" ); //para la ordenacion y pintado en html
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
        $query = "call sp_prodConsultaInversionGrid(:f1,:f2,:idPersona,:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(
            ":f1" => $this->_f1,
            ":f2" => $this->_f2,  
            ":idPersona" => $this->_idPersona,  
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
   public function getMisInversioneDetalle(){
        $aColumns       =   array("codigos","ubicacion","dimesion_area","fecha","monto_invertido","total_produccion" ); //para la ordenacion y pintado en html
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
        $sOrder = substr_replace( $sOrder, "", -1 );
        $query = "call sp_prodConsultaInversionDetGrid(:idInversion,:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(
            ":idInversion" => $this->_idInversion,  
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
      
}

?>