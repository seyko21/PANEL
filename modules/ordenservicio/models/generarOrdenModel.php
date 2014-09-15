<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-09-2014 06:09:13 
* Descripcion : generarOrdenModel.php
* ---------------------------------------
*/ 

class generarOrdenModel extends Model{

    private $_flag;
    private $_idGenerarOrden;
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
        $this->_idGenerarOrden   = Aes::de(Formulario::getParam("_idGenerarOrden"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: GenerarOrden*/
    public function getGenerarOrden(){
        $aColumns       =   array("","2","4","6","11" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordseOrdenServicioGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    /*grabar nuevo registro: GenerarOrden*/
    public function newGenerarOrden(){
        /*-------------------------LOGICA PARA EL INSERT------------------------*/
    }
    
    /*seleccionar registro a editar: GenerarOrden*/
    public function findGenerarOrden(){
        /*-----------------LOGICA PARA SELECT REGISTRO A EDITAR-----------------*/
    }
    
    /*editar registro: GenerarOrden*/
    public function editGenerarOrden(){
        /*-------------------------LOGICA PARA EL UPDATE------------------------*/
    }
    
    /*eliminar varios registros: GenerarOrden*/
    public function deleteGenerarOrdenAll(){
        /*--------------------------LOGICA PARA DELETE--------------------------*/
    }
    
}

?>