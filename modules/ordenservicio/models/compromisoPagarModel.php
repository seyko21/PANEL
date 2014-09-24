<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 24-09-2014 00:09:39 
* Descripcion : compromisoPagarModel.php
* ---------------------------------------
*/ 

class compromisoPagarModel extends Model{

    private $_flag;
    private $_idCompromisoPagar;
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
        $this->_idCompromisoPagar   = Aes::de(Formulario::getParam("_idCompromisoPagar"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: CompromisoPagar*/
    public function getCompromisoPagar(){
        $aColumns       =   array("","","REGISTRO_A_ORDENAR" ); //para la ordenacion y pintado en html
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
    
    /*grabar nuevo registro: CompromisoPagar*/
    public function newCompromisoPagar(){
        /*-------------------------LOGICA PARA EL INSERT------------------------*/
    }
    
    /*seleccionar registro a editar: CompromisoPagar*/
    public function findCompromisoPagar(){
        /*-----------------LOGICA PARA SELECT REGISTRO A EDITAR-----------------*/
    }
    
    /*editar registro: CompromisoPagar*/
    public function editCompromisoPagar(){
        /*-------------------------LOGICA PARA EL UPDATE------------------------*/
    }
    
    /*eliminar varios registros: CompromisoPagar*/
    public function deleteCompromisoPagarAll(){
        /*--------------------------LOGICA PARA DELETE--------------------------*/
    }
    
}

?>