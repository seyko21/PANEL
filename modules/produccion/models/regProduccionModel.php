<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : regProduccionModel.php
* ---------------------------------------
*/ 

class regProduccionModel extends Model{

    private $_flag;
    private $_idRegProduccion;
    private $_usuario;
    private $_term;


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
        $this->_idRegProduccion   = Aes::de(Formulario::getParam("_idRegProduccion"));    /*se decifra*/
        $this->_usuario                 = Session::get("sys_idUsuario");
        $this->_term  =   Formulario::getParam("_term"); 
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    public function getGridProduccion(){
        $aColumns       =   array( 'chk','u.distrito','fecha','ubicacion','elemento','total_produccion','total_asignado','total_saldo' ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( Formulario::getParam( "bSortable_".intval(Formulario::getParam("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( Formulario::getParam("iSortCol_".$i) ) ]." ".
                                (Formulario::getParam("sSortDir_".$i)==="asc" ? "asc" : 'desc') .",";
                }
        }
        $sOrder = substr_replace( $sOrder, "", -1 );
        $query = "call sp_prodProduccionGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        
        return $data; 
       
    }
    
    public function getProductos(){
        $query = "
        SELECT 
                c.`id_producto`,
                c.`ubicacion`
        FROM lgk_catalogo c
        WHERE c.`ubicacion` LIKE CONCAT('%".$this->_term."%')
        AND c.`id_producto` NOT IN(SELECT id_producto FROM `prod_produccionpanel`); ";
        
        $parms = array();
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
}

?>