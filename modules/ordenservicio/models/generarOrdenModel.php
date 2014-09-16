<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-09-2014 06:09:13 
* Descripcion : generarOrdenModel.php
* ---------------------------------------
*/ 

class generarOrdenModel extends Model{

    private $_flag;
    private $_idOrden;
    private $_monto;
    private $_fechaPago;
    private $_descuento;
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
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_monto  = Formulario::getParam(GNOSE."txt_monto"); 
        $this->_descuento  = str_replace(',','',Formulario::getParam(GNOSE."txt_descuento"));
        $this->_fechaPago  = Functions::cambiaf_a_mysql(Formulario::getParam(GNOSE."txt_fechapago")); 
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: GenerarOrden*/
    public function getGenerarOrden(){
        $aColumns       =   array("","2","4","6","11" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordseOrdenServicioGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function insertCuota(){
        $query = "CALL sp_ordseOrdenServicioCuota(:flag,:idOrden,:monto,:fechaPago,:usuario);";
        
        $parms = array(
            ':flag'=>1,
            ':idOrden'=>$this->_idOrden,
            ':monto'=>$this->_monto,
            ':fechaPago'=>$this->_fechaPago,
            ':usuario'=>$this->_usuario,
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function getGridCuotas(){
        $aColumns       =   array("","2","4","6","11" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordseOrdenServicioCuotasGrid(:idOrden,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":idOrden" => $this->_idOrden,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function editOrden(){
        $query = "CALL sp_ordseOrdenServicioCuota(:flag,:idOrden,:monto,:fechaPago,:usuario);";
        
        $parms = array(
            ':flag'=>2,
            ':idOrden'=>$this->_idOrden,
            ':monto'=>$this->_descuento,
            ':fechaPago'=>$this->_fechaPago,
            ':usuario'=>$this->_usuario,
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function findOrden(){
        $query = " SELECT fecha_orden,descuentos FROM lgk_ordenservicio WHERE id_ordenservicio = :idOrden; ";
        
        $parms = array(
            ':idOrden'=>$this->_idOrden
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
}

?>