<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-11-2014 17:11:31 
* Descripcion : reporteProductoMesModel.php
* ---------------------------------------
*/ 

class reporteProductoMesModel extends Model{

    private $_flag;
    private $_idReporteProductoMes;
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
        $this->_idReporteProductoMes   = Aes::de(Formulario::getParam("_idReporteProductoMes"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_f1    = Functions::cambiaf_a_mysql(Formulario::getParam("_f1"));
        $this->_f2    = Functions::cambiaf_a_mysql(Formulario::getParam("_f2")); 
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: ReporteProductoMes*/
    public function getReporteProductoMes(){
        $aColumns       =   array("","t.fecha","t.descripcion","t.cantidad_comprado","t.promedio_cantidad","t.sigla" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ventaRptProductoMasVendidoGrid(:f1,:f2,:iDisplayStart,:iDisplayLength,:sOrder);";
        
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