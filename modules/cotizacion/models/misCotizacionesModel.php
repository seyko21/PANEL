<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-09-2014 23:09:13 
* Descripcion : misCotizacionesModel.php
* ---------------------------------------
*/ 

class misCotizacionesModel extends Model{

    private $_idCotizacion;
    private $_estado;
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
        
        $this->_estado        = Formulario::getParam("_estadocb");
        $this->_idCotizacion   = Aes::de(Formulario::getParam("_idCotizacion"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_idPersona = Session::get("sys_idPersona");
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    public function getGridCotizacion(){
         $aColumns       =   array( 'cotizacion_numero','nombrecompleto' ,'fechacoti','meses_contrato','13','total'); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( Formulario::getParam( "bSortable_".intval(Formulario::getParam("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( Formulario::getParam("iSortCol_".$i) ) ]." ".
                                (Formulario::getParam("sSortDir_".$i)==="asc" ? "asc" : 'desc') .",";
                }
        }        
        $sOrder = substr_replace( $sOrder, "", -1 );
        
        $query = "call sp_cotiMisCotizacionesGrid(:acceso,:idPersona,:usuario,:estado,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':acceso' => Session::get('sys_all'),
            ':idPersona' => $this->_idPersona, 
            ':usuario' => $this->_usuario, 
            ':estado' => $this->_estado,
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data; 
       
    }
    
 
}

?>