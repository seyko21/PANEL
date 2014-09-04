<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 03-09-2014 14:09:13 
* Descripcion : regInversionModel.php
* ---------------------------------------
*/ 

class regInversionModel extends Model{

    private $_flag;
    private $_idInversion;
    private $_usuario;
    private $_idPersona;
    private $_fecha;
    private $_monto;
    
    /*para el grid*/
    private $_iDisplayStart;
    private $_iDisplayLength;
    private $_iSortingCols;
    private $_sSearch;
    
    public function __construct() {
        parent::__construct();
        $this->_set();
    }
    
    private function _set(){
        $this->_flag                    = Formulario::getParam("_flag");
        $this->_idInversion   = Aes::de(Formulario::getParam("_idInversion"));    /*se decifra*/
        $this->_usuario                 = Session::get("sys_idUsuario");
        $this->_idPersona     = Aes::de($this->post('_idPersona'));    /*se decifra*/
        
        $this->_monto =  str_replace(',','',Formulario::getParam(REINV.'txt_monto')); 
        $this->_fecha = $this->post(REINV.'txt_fecha'); 
        
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    public function getGridInversion() {
        $aColumns       =   array( 'chk','fecha_inversion','monto_invertido' ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( 'bSortable_'.intval($this->post('iSortCol_'.$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post('iSortCol_'.$i) ) ]." ".
                                ($this->post('sSortDir_'.$i)==='asc' ? 'asc' : 'desc') .",";
                }
        }
        $sOrder = substr_replace( $sOrder, "", -1 );
        $query = "call sp_prodInversionGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_idPersona
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }    
    
     public function mantenimientoInversion(){
        $query = "call sp_prodInversionMantenimiento(:flag,:key,:idpersona,:fecha, :monto,:usuario);";
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_idInversion,
            ':idpersona' => $this->_idPersona,            
            ':fecha' => Functions::cambiaf_a_mysql($this->_fecha),            
            ':monto' => $this->_monto,                        
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);    
        
        return $data;
    }
}

?>