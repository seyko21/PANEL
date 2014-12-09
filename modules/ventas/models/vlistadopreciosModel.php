<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 22-11-2014 19:11:31 
* Descripcion : vlistadopreciosModel.php
* ---------------------------------------
*/ 

class vlistadopreciosModel extends Model{

    private $_flag;
    private $_idVlistadoprecios;
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
        $this->_idVlistadoprecios   = Aes::de(Formulario::getParam("_idVlistadoprecios"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: Vlistadoprecios*/
    public function getVlistadoprecios(){
        $aColumns       =   array("id_producto","descripcion","unidad_medida","incligv","descripcion_moneda","precio" ); //para la ordenacion y pintado en html
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
        $query = "call sp_ventaRptListadoPrecioGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getListadoPrecios(){
        $query = "
       SELECT
            p.`id_producto`,
            p.`descripcion`,
            p.`precio`,
            p.`estado`,
            (SELECT m.`sigla` FROM `pub_moneda` m WHERE m.`id_moneda` = p.`moneda`) AS sigla_moneda,
            (SELECT m.`descripcion` FROM `pub_moneda` m WHERE m.`id_moneda` = p.`moneda`) AS descripcion_moneda,
            p.`moneda` AS id_moneda,
            CONCAT(um.`sigla`,' - ',um.`nombre`) AS unidad_medida,
             p.`incligv` 
          FROM `ven_producto` p
                  INNER JOIN `ven_unidadmedida` um ON um.`id_unidadmedida` = p.`id_unidadmedida`
          WHERE p.`estado` <> :estado
          ORDER BY p.`moneda` DESC,  p.`descripcion` ";
        $parms = array(
            ':estado'=>  "0",
        );
        $data = $this->queryAll($query,$parms);      
        
        return $data;
    }        
}

?>