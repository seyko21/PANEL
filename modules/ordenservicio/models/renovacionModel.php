<?php

class renovacionModel extends Model{

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
    
    public function getOrdenes(){
        $aColumns       =   array("","orden_numero","nombrecompleto","fecha_orden"); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordseRenovacionGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
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
    
    /*orden de servicio para la renovacion*/
    public function getOrdenServicio(){
        $query = "
        SELECT 
                os.`id_persona`,
                p.`nombrecompleto`,
                os.`meses_contrato`,
                os.`dias_oferta`,
                c.`validez`,
                c.`valor_produccion`,
                os.`incluyeigv` AS igv,
                c.`observaciones`,
                c.`nombre_campania`
        FROM `lgk_ordenservicio` os
        INNER JOIN mae_persona p ON p.`id_persona`=os.`id_persona`
        INNER JOIN `lgk_cotizacion` c ON c.`cotizacion_numero`=os.`cotizacion_numero`
        WHERE os.`id_ordenservicio` = :idOrden;";
        $parms = array(
            ':idOrden'=> $this->_idOrden
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function getProductosCotizados(){
        $query = "SELECT
                d.`id_caratula`,
                c.`codigo`,
                c.`descripcion`,
                d.`precio`,
                c.`iluminado`,
                k.`ubicacion`,
                k.`dimension_alto` AS alto,
                k.`dimension_ancho` AS ancho,
                k.`dimesion_area` AS aarea,
                k.`dimesion_area`,
                d.`cantidad_mes`,
                d.`costo_produccion`,
                d.`importe`,
                cc.`monto_total` AS total
        FROM `lgk_ordenserviciod` d
        INNER JOIN `lgk_ordenservicio` cc ON cc.`id_ordenservicio`=d.id_ordenservicio
        INNER JOIN `lgk_caratula` c ON c.`id_caratula`=d.`id_caratula`
        INNER JOIN `lgk_catalogo` k ON k.`id_producto`=c.`id_producto`
        WHERE d.id_ordenservicio= :idOrden;";
        
        $parms = array(
            ':idOrden' => $this->_idOrden
        );
        $data = $this->queryAll($query,$parms);            
        return $data;
    }
    
    public function postRenovacion(){
        $query = "CALL sp_ordseRenovacionMantenimiento(:flag,:idOrden,:idRepresentante,:mesesContrato,:diasOferta,:total,:idCaratula,:precio,:produccion,:usuario,:igv,:validez,:obs,:campania);";
        $parms = array(
            ':flag' => 1,
            ':idOrden' => $this->_idOrden,
            ':idRepresentante' => $this->_keyPersona,
            ':mesesContrato' => $this->_meses,
            ':diasOferta' => $this->_oferta,
            ':total' => str_replace(',','', $this->_total),
            ':idCaratula' => '',
            ':precio' => '',
            ':produccion' => $this->_costoProduccion,
            ':usuario' => $this->_usuario,
            ':igv' => ($this->_igv == '1')?'1':'0',
            ':validez' => $this->_validez,
            ':obs' => $this->_observacion,
            ':campania' => $this->_campania
        );
        $data = $this->queryOne($query,$parms);  
        if($data['result'] == 1){
            $item = 0;
            foreach ($this->_idProducto as $key => $prod) {
                $item++;
                $parms = array(
                    ':flag' => 2,
                    ':idOrden' => $data['idOrden'],
                    ':idRepresentante' => '',
                    ':mesesContrato' => $this->_meses,
                    ':diasOferta' => $item,
                    ':total' => '',
                    ':idCaratula' => AesCtr::de($prod),
                    ':precio' => Functions::deleteComa($this->_precio[$key]),
                    ':produccion' => Functions::deleteComa($this->_produccion[$key]),
                    ':usuario' => '',
                    ':igv' => ($this->_igv == '1')?'1':'0',
                    ':validez' => '',
                    ':obs' => '',
                    ':campania' => ''
                );
                $this->execute($query,$parms);                      
            }
        }
        return $data;
    }
    
}