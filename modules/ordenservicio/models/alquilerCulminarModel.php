<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 25-09-2014 23:09:26 
* Descripcion : alquilerCulminarModel.php
* ---------------------------------------
*/ 

class alquilerCulminarModel extends Model{

    private $_flag;
    private $_idAlquilerCulminar;
    private $_tipo;
    private $_usuario;
    private $_idPersona;
    
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
        $this->_idAlquilerCulminar   = Aes::de(Formulario::getParam("_idAlquilerCulminar"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_tipo        = Formulario::getParam("_tipo");
        $this->_idPersona = Session::get("sys_idPersona");
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: AlquilerCulminar*/
    public function getAlquilerCulminar(){
        $aColumns       =   array("codigo","ordenin_numero","cliente" ,"fecha_inicio","fecha_termino","meses_contrato","dias_oferta","importe_incigv"); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordSeConsultaAlquilerGrid(:acceso,:idPersona,:tipo,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':acceso' => Session::get('sys_all'),
            ':idPersona' => $this->_idPersona, 
            ":tipo" => $this->_tipo,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
   
}

?>