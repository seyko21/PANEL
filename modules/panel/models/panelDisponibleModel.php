<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-11-2014 23:11:45 
* Descripcion : panelDisponibleModel.php
* ---------------------------------------
*/ 

class panelDisponibleModel extends Model{

    private $_flag;
    private $_idPanelDisponible;
    public $_ciudad;
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
        $this->_flag        = Formulario::getParam("_flag");
        $this->_idPanelDisponible   = Aes::de(Formulario::getParam("_idPanelDisponible"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_idPersona                 = Session::get("sys_idPersona");
        $this->_ciudad        = Formulario::getParam("_ciudad");        
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: PanelDisponible*/
    public function getPanelDisponible(){
        $aColumns       =   array("","t.ubicacion","t.dimesion_area","t.distrito","t.elemento","t.codigos" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_catalogoRptPanelDisponibleGrid(:acceso,:idPersona,:ciudad,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':acceso' => Session::get('sys_all'),
            ':idPersona' => $this->_idPersona, 
            ':ciudad' => $this->_ciudad, 
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
   public function getCiudad(){
        $query = "call sp_catalogoCiudadPanel(:acceso, :usuario); ";        
        $parms = array(
            ':acceso' => Session::get('sys_all'),
            ':usuario' => $this->_idPersona            
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }   
   
   public function getRptPaneles(){
        $query = "call sp_catalogoRptPanelDisponible(:acceso,:idPersona,:ciudad); ";        
        $parms = array(
            ':acceso' => Session::get('sys_all'),
            ':idPersona' => $this->_idPersona, 
            ':ciudad' => $this->_ciudad, 
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }       
    public function getPiePagina(){
        $query = "SELECT valor FROM `pub_parametro` WHERE alias = :alias;";
        $parms = array(
            ':alias' => 'PIE'
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }       
    public function getAgenteVenta(){
        $query = "SELECT concat(`nombrecompleto`,' - ',`telefono`) as contacto"
                . " FROM `mae_persona` WHERE id_persona = :idd;";
        $parms = array(
            ':idd' => $this->_idPersona
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }     
    
}

?>