<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 29-09-2014 22:09:59 
* Descripcion : panelclienteModel.php
* ---------------------------------------
*/ 

class panelclienteModel extends Model{

    private $_flag;
    private $_idPersona;
    private $_usuario;
    private $_idCaratula;
    
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
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_idPersona               = Session::get("sys_idPersona");
        $this->_idCaratula    = Aes::de(Formulario::getParam("_idCaratula"));
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: Panelcliente*/
    public function getPanelcliente(){
        $aColumns       =   array("codigo","orden_numero","10","12","fecha_inicio","fecha_termino" ); //para la ordenacion y pintado en html
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
        $query = "call sp_catalogoPanelClienteGrid(:idPersona,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":idPersona" => $this->_idPersona,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
   
    public function getGeografico(){
        
        $query = 'SELECT
                c.`id_producto`,
                c.`ubicacion`,
                c.`google_latitud`,
                c.`google_longitud`
              FROM `lgk_catalogo` c 
              INNER JOIN lgk_caratula ct ON ct.`id_producto` = c.`id_producto`
              WHERE ct.`id_caratula` = :idCaratula ';
       
        $parms = array(
            ":idCaratula" => $this->_idCaratula
         );
        $data = $this->queryOne($query, $parms);
        return $data;
        
    }
    
    
}

?>