<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 11-10-2014 16:10:11 
* Descripcion : asignarPanelSocioModel.php
* ---------------------------------------
*/ 

class asignarPanelSocioModel extends Model{

    private $_flag;
    private $_idAsignacionPanel;
    private $_xSearch;
    private $_usuario;
    private $_idPersona;
    private $_socio;
    private $_producto;
    private $_ganancia;
    private $_inversiones;
    private $_montoInvertir;
    private $_totalInvertido;
    private $_term;

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
        $this->_idAsignacionPanel   = Aes::de(Formulario::getParam("_idAsignacionPanel"));    /*se decifra*/
        $this->_idPersona   = Aes::de(Formulario::getParam("_idPersona"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_xSearch        = Formulario::getParam(APASO."_term");
        $this->_socio        = Aes::de(Formulario::getParam(APASO."txt_idpersona"));
        $this->_producto        = Aes::de(Formulario::getParam(APASO."txt_idproducto"));
        $this->_ganancia        = (Formulario::getParam(APASO."txt_ganancia")/100);
        $this->_inversiones        = Formulario::getParam(APASO."hhidInversion");   /*array*/
        $this->_montoInvertir        = Formulario::getParam(APASO."txt_montoinvertir");  /*array*/
        $this->_totalInvertido        = Formulario::getParam(APASO."txt_montototal");
        $this->_term  =   Formulario::getParam(APASO."_term"); 
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: AsignarPanelSocio*/
    public function getAsignarPanelSocio(){
        $aColumns       =   array("nombrecompleto","ubicacion" ); //para la ordenacion y pintado en html
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
        $query = "call sp_prodAsignarPanelSocioGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getSocios(){
       
        $query = "
        SELECT p.id_persona,p.nombrecompleto,p.monto_invertido,p.monto_saldo
        FROM mae_persona p
        INNER JOIN mae_usuario u ON u.`persona`=p.`persona`
        WHERE p.monto_saldo > 0
        AND (SELECT COUNT(*) FROM `men_usuariorol` WHERE `id_usuario` = u.`id_usuario` AND id_rol='".APP_COD_SOCIO."') > 0
        AND p.nombrecompleto  LIKE CONCAT('%".$this->_xSearch."%')  ";
        
        $parms = array();
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getInversiones(){
       
        $query = "
        SELECT 
                id_inversion,
                DATE_FORMAT(fecha_inversion,'%d/%m/%Y')AS fecha,
                monto_saldo
        FROM prod_inversion WHERE monto_saldo > 0 AND id_persona = :idPersona;";
        
        $parms = array(
            ':idPersona'=> $this->_idPersona
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    /*grabar nuevo registro: AsignarPanelSocio*/
    public function newAsignarPanelSocio(){
        $query = "CALL sp_prodAsignarPanelSocioMantenimiento("
                    . ":flag,"
                    . ":idAsignacionPanel,"
                    . ":idPersona,"
                    . ":idProduccion,"
                    . ":idInversion,"
                    . ":montoInvertido,"
                    . ":totalInvertido,"
                    . ":ganancia,"
                    . ":usuario"
                . ");";
        
        /*la cabecera*/
        $parms = array(
            ':flag'=> 1,
            ':idAsignacionPanel'=> '',
            ':idPersona'=> $this->_socio,
            ':idProduccion'=> $this->_producto,
            ':idInversion'=> '',
            ':montoInvertido'=> '',
            ':totalInvertido'=> $this->_totalInvertido,
            ':ganancia'=> $this->_ganancia,
            ':usuario'=> $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        
        $idPanelSocio = $data['idAsignacionPanel'];
        
        if($data['result'] == 1){
            /*el detalle*/
            foreach ($this->_inversiones as $key=>$value) {
                if ($this->_montoInvertir[$key] > 0){
                    $parms = array(
                        ':flag'=> 2,
                        ':idAsignacionPanel'=> $idPanelSocio,
                        ':idPersona'=> '',
                        ':idProduccion'=> '',
                        ':idInversion'=> AesCtr::de($value),
                        ':montoInvertido'=> $this->_montoInvertir[$key],
                        ':totalInvertido'=> '',
                        ':ganancia'=> '',
                        ':usuario'=> $this->_usuario
                    );
                    $this->execute($query,$parms);
                }
            }
        }
        return $data;
    }
    
    public function postDeleteAsignarPanelSocio(){
        $query = "CALL sp_prodAsignarPanelSocioMantenimiento("
                    . ":flag,"
                    . ":idAsignacionPanel,"
                    . ":idPersona,"
                    . ":idProduccion,"
                    . ":idInversion,"
                    . ":montoInvertido,"
                    . ":totalInvertido,"
                    . ":ganancia,"
                    . ":usuario"
                . ");";
        $parms = array(
            ':flag'=> 3,
            ':idAsignacionPanel'=> $this->_idAsignacionPanel,
            ':idPersona'=> '',
            ':idProduccion'=> '',
            ':idInversion'=> '',
            ':montoInvertido'=> '',
            ':totalInvertido'=> '',
            ':ganancia'=> '',
            ':usuario'=> $this->_usuario
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function getProductos(){
        $query = "
        SELECT 
                pp.`id_produccion` as id_producto,
                c.`ubicacion`,
                pp.total_produccion,
                pp.total_asignado,
                pp.total_saldo,
                (SELECT SUM(`porcentaje_ganacia`) FROM `prod_asignacionpanel` WHERE `id_produccion` = pp.`id_produccion`)AS porcentaje
        FROM prod_produccionpanel pp 
        INNER JOIN lgk_catalogo c ON c.`id_producto`=pp.`id_producto`
        WHERE pp.total_saldo > 0 AND c.`ubicacion` LIKE CONCAT('%".$this->_term."%')
        AND NOT EXISTS(
			SELECT `id_produccion` FROM `prod_asignacionpanel` WHERE `id_produccion` = pp.`id_produccion`
                        AND id_persona = :idSocioSel
			); ";
        
        $parms = array(
            ':idSocioSel'=>  $this->_socio
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
}

?>