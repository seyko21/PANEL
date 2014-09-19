<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 18-09-2014 22:09:09 
* Descripcion : instalacionModel.php
* ---------------------------------------
*/ 

class instalacionModel extends Model{

    private $_flag;
    private $_idInstalacion;
    private $_xSearch;
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
        $this->_idInstalacion   = Aes::de(Formulario::getParam("_idInstalacion"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_xSearch     = $this->post(ORINS.'_term');
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: Instalacion*/
    public function getInstalacion(){
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
    
    public function getCaratulas(){
        /*bueca en las ordenes en estado T o P, pagado parcial o total*/
        $u = '';
        if(!empty($this->_xSearch)){
            $u = " AND o.`orden_numero`='".$this->_xSearch."' ";
        }
        $query = "
         SELECT 
                od.`id_ordenserviciod`,
                c.`codigo`,
                c.`descripcion`,
                ct.`ubicacion`,
                o.`orden_numero`
        FROM `lgk_ordenserviciod` od
        INNER JOIN `lgk_caratula` c ON c.`id_caratula`=od.`id_caratula`
        INNER JOIN `lgk_catalogo` ct ON ct.`id_producto`=c.`id_producto`
        INNER JOIN `lgk_ordenservicio` o ON o.`id_ordenservicio`=od.`id_ordenservicio`
        WHERE (o.`estado`='T' OR o.`estado`='P') ".$u." ; ";
        
        $parms = array();
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getConceptos(){
        $query = "
         SELECT 
                `id_concepto`,
                `descripcion`,
                `precio`
        FROM `pub_concepto` WHERE `estado` = :estado
        ORDER BY descripcion; ";
        
        $parms = array(
            ':estado'=>'A'
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }




    /*grabar nuevo registro: Instalacion*/
    public function newInstalacion(){
        /*-------------------------LOGICA PARA EL INSERT------------------------*/
    }
    
   
    
    
    
   
    
}

?>