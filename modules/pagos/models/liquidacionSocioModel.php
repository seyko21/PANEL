<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 30-09-2014 03:09:35 
* Descripcion : liquidacionSocioModel.php
* ---------------------------------------
*/ 

class liquidacionSocioModel extends Model{

    private $_flag;
    private $_idOrden;
    public  $_numOrden;
    private $_f1;
    private $_f2;
    private $_usuario;
    private $_idPersona; 
    private $_idSocio; 
    
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
        $this->_idOrden   = Aes::de(Formulario::getParam("_idOrden"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_numOrden   = Aes::de(Formulario::getParam("_numOrden"));    /*se decifra*/        
        
        $this->_f1    = Functions::cambiaf_a_mysql(Formulario::getParam("_f1"));
        $this->_f2    = Functions::cambiaf_a_mysql(Formulario::getParam("_f2"));
        $this->_idPersona = Session::get("sys_idPersona");
        
        $this->_idSocio = Aes::de(Formulario::getParam("_idSocio"));
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: LiquidacionSocio*/
    public function getLiquidacionSocio(){
        $aColumns       =   array('orden_numero','socio','fecha_contrato','cliente','monto_total','estado' ); //para la ordenacion y pintado en html
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
        $query = "call sp_pagoRptLiquidacionSocioGrid(:acceso,:idPersona,:f1, :f2,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":acceso" => Session::get('sys_all'),
            ":idPersona" => $this->_idPersona,             
            ":f1" => $this->_f1,
            ":f2" => $this->_f2,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }

    public function getRptDetalleOrden(){
        $query = 'SELECT
                os.`orden_numero`,os.`estado`, DATE_FORMAT(os.`fecha_contrato`, "%d/%m/%Y") AS fecha,
                os.`incluyeigv`, tp.`descripcion` AS elemento,ct.`dimension_alto`, ct.`dimension_ancho`, ct.`dimesion_area`,
                osd.`item`, ca.`codigo`,CONCAT(ct.`ubicacion`," - ", ca.`descripcion` ) AS producto,
                p.`nombrecompleto` AS representante, p.`numerodocumento` AS dni,
                (SELECT pp.`direccion` FROM `mae_persona` pp WHERE pp.id_persona = p.`id_personapadre` ) AS direccion,
                (SELECT pp.`nombrecompleto` FROM `mae_persona` pp WHERE pp.id_persona = p.`id_personapadre` ) AS cliente,
                (SELECT pp.`numerodocumento` FROM `mae_persona` pp WHERE pp.id_persona = p.`id_personapadre` ) AS ruc,
                osd.`cantidad_mes`, os.`dias_oferta`,                
                DATE_FORMAT(osd.`fecha_inicio`, "%d/%m/%Y") AS fecha_inicio,
                DATE_FORMAT(osd.`fecha_termino`, "%d/%m/%Y") AS fecha_termino, 
                osd.porcentaje_comision,                
                os.`monto_venta`,os.`monto_impuesto`,(os.`monto_total`) AS total_final,
                (SELECT SUM(`monto_pago`) FROM `lgk_compromisopago` cp 
                WHERE cp.`id_ordenservicio` = os.`id_ordenservicio` AND cp.`estado` = "E" ) AS deuda,
                (SELECT SUM(`monto_pago`) FROM `lgk_compromisopago` cp 
                WHERE cp.`id_ordenservicio` = os.`id_ordenservicio` AND cp.`estado` = "P" ) AS pagado,
                (SELECT ppx.nombrecompleto FROM `mae_persona` ppx WHERE ppx.id_persona = app.`id_persona` ) AS socio,                
                (osd.importe_incigv + osd.`impuesto` )AS importe,
                osd.`impuesto`, 
		oi.`monto_total` AS orden_instalacion,
                osd.`comision_venta`,
		(osd.`comision_venta` + oi.`monto_total` + osd.`impuesto` )AS egresos,				
		((osd.`monto_total`) - (osd.`comision_venta` + oi.`monto_total`  + osd.`impuesto`)) AS utilidad,				
		app.`porcentaje_ganacia`,		
		(SELECT COUNT(*) FROM `lgk_compromisopago` cp WHERE cp.`id_ordenservicio` = os.`id_ordenservicio` AND cp.`estado` IN("E","P") ) AS nrocuotas
              FROM `lgk_ordenserviciod` osd
                INNER JOIN `lgk_ordenservicio` os ON osd.`id_ordenservicio` = os.`id_ordenservicio`
                INNER JOIN `lgk_ordeninstalacion` oi ON oi.`id_ordenserviciod` = osd.`id_ordenserviciod` AND oi.`estado` <> "A"
                INNER JOIN `lgk_caratula` ca ON ca.`id_caratula` = osd.`id_caratula`
                INNER JOIN `lgk_catalogo` ct ON ct.`id_producto` = ca.`id_producto`
                INNER JOIN `lgk_tipopanel` tp ON tp.`id_tipopanel` = ct.`id_tipopanel`
                INNER JOIN `mae_persona` p ON p.`id_persona` = os.`id_persona`
                INNER JOIN `prod_produccionpanel` prod ON prod.`id_producto` = ct.`id_producto` 
                INNER JOIN `prod_asignacionpanel` app ON app.`id_produccion` = prod.`id_produccion`
              WHERE osd.`id_ordenservicio` = :idOS AND app.`id_persona` = :idPersona  ;';
        
        $parms = array(
          ":idOS" => $this->_idOrden,
          ":idPersona" => $this->_idSocio
        );
        
        $data = $this->queryAll($query, $parms);
        return $data;
    }    
    
}

?>