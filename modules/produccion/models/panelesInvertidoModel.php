<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 02-10-2014 20:10:49 
* Descripcion : panelesInvertidoModel.php
* ---------------------------------------
*/ 

class panelesInvertidoModel extends Model{

    private $_flag;    
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
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_idPersona               = Session::get("sys_idPersona");
        
        $this->_idProducto = Aes::de(Formulario::getParam("_idProducto"));
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: PanelesInvertido*/
    public function getPanelesInvertido(){
        $aColumns       =   array("","socio","numero_produccion","ubicacion","codigos","fecha","porcentaje_ganacia","total_invertido" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_prodConsultaPanelAsignadoGrid(:acceso, :idPersona, :iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':acceso' => Session::get('sys_all'),
            ':idPersona' => $this->_idPersona,
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
                c.`google_longitud`,
                (select ct.imagen from lgk_caratula ct where ct.id_producto = c.id_producto order by rand() limit 1 ) as imagen
              FROM `lgk_catalogo` c               
              WHERE c.`id_producto` = :idProducto ';
       
        $parms = array(
            ":idProducto" => $this->_idProducto
         );
        $data = $this->queryOne($query, $parms);
        return $data;
        
    }
       
}