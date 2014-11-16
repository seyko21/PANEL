<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 27-09-2014 07:09:56 
* Descripcion : liquidacionClienteModel.php
* ---------------------------------------
*/ 

class liquidacionClienteModel extends Model{

    private $_flag;
    private $_idOrden;
    public  $_numOrden;
    private $_f1;
    private $_f2;
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
        $this->_idOrden   = Aes::de(Formulario::getParam("_idOrden"));    /*se decifra*/
        $this->_numOrden   = Aes::de(Formulario::getParam("_numOrden"));    /*se decifra*/        
        
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_f1    = Functions::cambiaf_a_mysql(Formulario::getParam("_f1"));
        $this->_f2    = Functions::cambiaf_a_mysql(Formulario::getParam("_f2"));
        $this->_idPersona = Session::get("sys_idPersona");
                
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: LiquidacionCliente*/
    public function getLiquidacionCliente(){
        $aColumns       =   array('orden_numero','fecha_contrato','cliente','monto_total','estado'); //para la ordenacion y pintado en html
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
        
        $query = "call sp_pagoRptLiquidacionClienteGrid(:acceso,:idPersona,:f1, :f2,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
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
                osd.`precio`,osd.`costo_produccion`,osd.`importe`,
                DATE_FORMAT(osd.`fecha_inicio`, "%d/%m/%Y") AS fecha_inicio,
                DATE_FORMAT(osd.`fecha_termino`, "%d/%m/%Y") AS fecha_termino,                
                osd.`precio_incigv`,osd.`produccion_incigv`, osd.`importe_incigv`,osd.`porcentaje_igv`,
                osd.`impuesto`, osd.`monto_total`,  os.`porcentaje_impuesto`,
                os.`monto_venta`,os.`monto_impuesto`,os.`monto_total_final` as total,
                (SELECT SUM(`monto_pago`) FROM `lgk_compromisopago` cp 
                WHERE cp.`id_ordenservicio` = os.`id_ordenservicio` AND cp.`estado` = "E" ) AS deuda,
                (SELECT SUM(`monto_pago`) FROM `lgk_compromisopago` cp 
                WHERE cp.`id_ordenservicio` = os.`id_ordenservicio` AND cp.`estado` = "P" ) AS pagado,
                os.`observacion` 
              FROM `lgk_ordenserviciod` osd
                INNER JOIN `lgk_ordenservicio` os ON osd.`id_ordenservicio` = os.`id_ordenservicio`
                INNER JOIN `lgk_caratula` ca ON ca.`id_caratula` = osd.`id_caratula`
                INNER JOIN `lgk_catalogo` ct ON ct.`id_producto` = ca.`id_producto`
                INNER JOIN `lgk_tipopanel` tp ON tp.`id_tipopanel` = ct.`id_tipopanel`
                INNER JOIN `mae_persona` p ON p.`id_persona` = os.`id_persona`
              WHERE osd.`id_ordenservicio` = :idOS  ';
        
        $parms = array(
          ":idOS" => $this->_idOrden
        );
        
        $data = $this->queryAll($query, $parms);
        return $data;
    }
    
    
     public function getRptDetalleCronograma(){
        $query = 'SELECT
                cp.`numero_cuota`,
                cp.`monto_pago`,
                cp.`costo_mora`,                
                DATE_FORMAT(cp.`fecha_programada`, "%d/%m/%Y") as fecha_programada,
                DATE_FORMAT(cp.`fecha_pagoreal`, "%d/%m/%Y") as fecha_pagoreal,                
                cp.`estado`,
                cp.`observacion`,
                cp.`id_ordenservicio` 
              FROM `lgk_compromisopago` cp
              WHERE cp.`estado`NOT IN("R") AND cp.id_ordenservicio = :idOS 
              order by 1 ';
        
        $parms = array(
          ":idOS" => $this->_idOrden
        );
        
        $data = $this->queryAll($query, $parms);
        return $data;
    }
}

?>