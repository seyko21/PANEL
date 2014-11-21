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
    private $_activo;
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
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: PanelDisponible*/
    public function getPanelDisponible(){
        $aColumns       =   array("","","REGISTRO_A_ORDENAR" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp [NOMBRE_PROCEDIMIENTO_GRID] Grid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    /*grabar nuevo registro: PanelDisponible*/
    public function newPanelDisponible(){
        /*-------------------------LOGICA PARA EL INSERT------------------------*/
    }
    
    /*seleccionar registro a editar: PanelDisponible*/
    public function findPanelDisponible(){
        /*-----------------LOGICA PARA SELECT REGISTRO A EDITAR-----------------*/
    }
    
    /*editar registro: PanelDisponible*/
    public function editPanelDisponible(){
        /*-------------------------LOGICA PARA EL UPDATE------------------------*/
    }
    
    /*eliminar varios registros: PanelDisponible*/
    public function deletePanelDisponibleAll(){
        /*--------------------------LOGICA PARA DELETE--------------------------*/
    }
    
}

?>