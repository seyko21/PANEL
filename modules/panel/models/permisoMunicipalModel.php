<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 23-08-2014 22:08:31 
* Descripcion : permisoMunicipalModel.php
* ---------------------------------------
*/ 

class permisoMunicipalModel extends Model{

    private $_flag;
    private $_idPermisomuni;
    private $_usuario;
    private $_fi, $_ff, $_monto, $_obs;
    
    /*para el grid*/
    private $_iDisplayStart;
    private $_iDisplayLength;
    private $_iSortingCols;
    private $_sSearch;
    
    private $_idProducto;
    
    public function __construct() {
        parent::__construct();
        $this->_set();
    }
    
    private function _set(){
        $this->_flag            = Formulario::getParam("_flag");
        $this->_idPermisomuni   = Aes::de(Formulario::getParam("_idPermisoMuni"));    /*se decifra*/
        $this->_usuario         = Session::get("sys_idUsuario");
        
        $this->_idProducto = Aes::de($this->post('_idProducto')); 
        
        $this->_fi = $this->post(PERMU.'txt_fi'); 
        $this->_ff = $this->post(PERMU.'txt_ff'); 
        $this->_monto = str_replace(',','',Formulario::getParam(PERMU.'txt_monto'));
        $this->_obs = $this->post(PERMU.'txt_observacion'); 
        
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
        
    }
    
    public function getGridFichaTecnica(){
        $aColumns       =   array('distrito','ubicacion','fechaInicio','fechaFin' ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( "bSortable_".intval($this->post("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post("iSortCol_".$i) ) ]." ".
                                ($this->post("sSortDir_".$i)==="asc" ? "asc" : 'desc') .",";
                }
        }
        $sOrder = substr_replace( $sOrder, "", -1 );
        $query = "call sp_catalogoFichaTecnicaPMGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);      
        return $data; 
       
    }
        
    public function getGridPermisoMunicipal(){
        
        $query = "call sp_catalogoPermisoMunicipalGrid(:iDisplayStart,:iDisplayLength,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,            
            ':sSearch' => $this->_idProducto,
        );
        $data = $this->queryAll($query,$parms);
        return $data; 
       
    } 
    
    public function getUbicacion(){
        $query = " SELECT ubicacion,  FORMAT(dimension_alto,0) as dimension_alto, FORMAT(dimension_ancho,0) as dimension_ancho"
                . "  FROM lgk_catalogo WHERE id_producto = :id ";
        $parms = array(
            ':id' => $this->_idProducto,
        );
        $data = $this->queryOne($query,$parms);           
        return $data;
    }       
    
    public function getPermisoMunicipal(){
        $query = "SELECT * FROM lgk_permisomuni WHERE id_permisomuni = :id; ";
                
        $parms = array(
            ':id' => $this->_idPermisomuni
        );
        $data = $this->queryOne($query,$parms);   
//        print_r($parms);
        return $data;
    }
    public function mantenimientoPermisoMunicipal(){
        $query = "call sp_catalogoPermisoMunicipalMantenimiento(:flag,:key,:idproducto,				
				:fi, :ff, :monto, :obs, :usuario);";
                
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_idPermisomuni,
            ':idproducto' => $this->_idProducto,     
            ':fi' => Functions::cambiaf_a_mysql($this->_fi),   
            ':ff' => Functions::cambiaf_a_mysql($this->_ff),   
            ':monto' => $this->_monto,   
            ':obs' => $this->_obs,               
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms); 
//        print_r($parms);
        return $data;
    }    
    
}

?>