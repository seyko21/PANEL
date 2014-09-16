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
        $this->_idOrden   = Aes::de(Formulario::getParam("_idOrden"));    /*se decifra*/
        $this->_idCompromiso   = Aes::de(Formulario::getParam("_idCompromiso"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_fecha  = Functions::cambiaf_a_mysql(Formulario::getParam("_fecha"));
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: SeguimientoPago*/
    public function getSeguimientoPago(){
        $aColumns       =   array("","2","6","11" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordseSeguimientoPagoGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getCronograma(){
        $query = "
        SELECT 
                `id_compromisopago`,
                `numero_cuota`,
                `monto_pago`,
                `fecha_programada`,
                `fecha_pagoreal`,
                estado
        FROM `lgk_compromisopago`
        WHERE `id_ordenservicio` = :idOrden;";
        
        $parms = array(
            ":idOrden" => $this->_idOrden
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function postPagarOrden(){
        $query = "
        UPDATE `lgk_compromisopago` SET
                `fecha_pagoreal` = :fecha,
                `estado` = 'P'
        WHERE `id_compromisopago` = :idCompromiso;";
        
        $parms = array(
            ":idCompromiso" => $this->_idCompromiso,
            ":fecha" => $this->_fecha
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }
    
}

?>