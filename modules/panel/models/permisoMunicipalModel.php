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
    private $_idPermisoMunicipal;
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
        $this->_flag                    = Formulario::getParam("_flag");
        $this->_idPermisoMunicipal   = Aes::de(Formulario::getParam("_idPermisomuni"));    /*se decifra*/
        $this->_usuario                 = Session::get("sys_idUsuario");
        
        $this->_idProducto = Aes::de($this->post('_idProducto')); 
        
        $this->_fi = $this->post(PERMU.'txt_fi'); 
        $this->_ff = $this->post(PERMU.'txt_ff'); 
        $this->_monto = $this->post(PERMU.'txt_monto'); 
        $this->_obs = $this->post(PERMU.'txt_observacion'); 
        
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    public function getGridFichaTecnica(){
        $aColumns       =   array( 'chk','u.distrito','ubicacion' ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( "bSortable_".intval($this->post("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post("iSortCol_".$i) ) ]." ".
                                ($this->post("sSortDir_".$i)==="asc" ? "asc" : 'desc') ." ";
                }
        }
        
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
    
    public function mantenimientoPermisoMunicipal(){
        $query = "call sp_catalogoPermisoMunicipalMantenimiento(:flag,:key,:idproducto,				
				:fi, :ff, :monto, :obs, :usuario);";
                
        $parms = array(
            ':flag' => $this->_flag,
            ':key' => $this->_idPermisoMunicipal,
            ':idproducto' => $this->_idProducto,     
            ':fi' => $this->_fi,   
            ':ff' => $this->_ff,   
            ':monto' => $this->_monto,   
            ':obs' => $this->_obs,               
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);            
        return $data;
    }    
    
}

?>