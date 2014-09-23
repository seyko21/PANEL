<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 21-09-2014 18:09:08 
* Descripcion : comisionVendedorModel.php
* ---------------------------------------
*/ 

class comisionVendedorModel extends Model{

    private $_flag;
    private $_idVendedor;
    private $_idOrden;
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
        $this->_idVendedor   = Aes::de(Formulario::getParam("_idVendedor"));    /*se decifra*/
        $this->_idOrden   = Aes::de(Formulario::getParam("_idOrden"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: ComisionVendedor*/
    public function getComisionVendedor(){
        $aColumns       =   array("nombrecompleto" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordseComisionVendedorGrid(:rolv,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":rolv" => APP_COD_VEND,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
      
    public function getOrdenesServicio(){
        /*solo las caratulas confirmadas y sin comision venta generada*/
        $query = "
        SELECT
                os.`id_ordenservicio`,
                os.`orden_numero`,
                (SELECT COUNT(*) FROM `lgk_ordenserviciod` dd WHERE dd.id_ordenservicio = od.`id_ordenservicio`) AS totales,
                (SELECT COUNT(*) FROM `lgk_ordenserviciod` bb WHERE bb.id_ordenservicio = od.`id_ordenservicio` AND imagen <>'')AS confirmados
        FROM `lgk_ordenserviciod` od
        INNER JOIN `lgk_ordenservicio` os ON os.`id_ordenservicio`=od.`id_ordenservicio`
        INNER JOIN mae_usuario u ON u.`id_usuario`=os.`usuario_creacion`
        INNER JOIN mae_persona p ON p.`persona`=u.`persona`
        WHERE od.`imagen` <> '' AND os.`comision_venta` = 0
        GROUP BY os.`id_ordenservicio`; ";
        $parms = array();
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getImagenesConfirmadas($orden){
        $query = "
        SELECT
                od.`imagen`
        FROM `lgk_ordenserviciod` od
        WHERE od.`id_ordenservicio` = :idOrden AND od.`imagen` <> '';";
        $parms = array(
            ':idOrden'=>$orden
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function generarComisionVendedor(){
        $query = "CALL sp_ordseComisionVendedorMantenimiento(:flag,:idOrden,:usuario);";
        $parms = array(
            ':flag'=> 1,
            ':idOrden'=> $this->_idOrden,
            ':usuario'=> $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
}

?>