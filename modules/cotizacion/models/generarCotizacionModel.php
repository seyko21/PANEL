<?php
/*
* --------------------------------------
* fecha: 14-08-2014 05:08:11 
* Descripcion : generarCotizacionModel.php
* --------------------------------------
*/ 

class generarCotizacionModel extends Model{

    private $_flag;
    private $_keyPersona;
    private $_idPersona;
    private $_idProducto;
    private $_idCotizacion;
    private $_ubicacion;
    private $_precio;
    private $_produccion;
    private $_meses;
    private $_oferta;
    private $_total;
    private $_usuario;
    
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
        $this->_flag        = $this->post('_flag');
        $this->_keyPersona  = Aes::de($this->post(T8.'txt_idpersona'));     /*el cliente, representante*/
        $this->_ubicacion   = $this->post(T8.'txt_search');
        $this->_meses       = $this->post(T8.'txt_meses');                  /*meses de contrato*/
        $this->_oferta      = $this->post(T8.'txt_oferta');                 /*meses de oferta*/
        $this->_idCotizacion= AesCtr::de($this->post('_idCotizacion'));
        $this->_idProducto  = $this->post(T8.'hhddIdProducto');
        $this->_precio      = $this->post(T8.'hhddPrecio');
        $this->_produccion  = $this->post(T8.'hhddProduccion');
        $this->_total       = $this->post(T8.'txt_total');
        $this->_idPersona   = Session::get('sys_idPersona');
        $this->_usuario     = Session::get('sys_idUsuario');
        
        $this->_iDisplayStart  =   $this->post('iDisplayStart'); 
        $this->_iDisplayLength =   $this->post('iDisplayLength'); 
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch');
    }
    
    public function getGridCotizacion() {
        $aColumns       =   array( 'cotizacion_numero','nombrecompleto' ); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( $this->post( 'bSortable_'.intval($this->post('iSortCol_'.$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( $this->post('iSortCol_'.$i) ) ]." ".
                                ($this->post('sSortDir_'.$i)==='asc' ? 'asc' : 'desc') ." ";
                }
        }
        
        $query = "call sp_cotiGenerarCotizacionGrid(:usuario,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':usuario' => $this->_usuario,
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getMisProductos(){
        $query = "CALL sp_cotiGenerarCotizacionMisProductos(:flag,:idPersona,:ubicacion);";
        
        $parms = array(
            ':flag' => 1,
            ':idPersona' => $this->_idPersona,
            ':ubicacion' => $this->_ubicacion
        );
        $data = $this->queryAll($query,$parms);            
        return $data;
    }
    
    public function generarCotizacion(){
        $query = "CALL sp_cotiGenerarCotizacion(:flag,:idCotizacion,:idRepresentante,:mesesContrato,:diasOferta,:total,:idCaratula,:precio,:produccion,:usuario);";
        
        $parms = array(
            ':flag' => $this->_flag,
            ':idCotizacion' => '',
            ':idRepresentante' => $this->_keyPersona,
            ':mesesContrato' => $this->_meses,
            ':diasOferta' => $this->_oferta,
            ':total' => $this->_total,
            ':idCaratula' => '',
            ':precio' => '',
            ':produccion' => '',
            ':usuario' => $this->_usuario
        );
        $data = $this->queryOne($query,$parms);  
        
        if($data['result'] == 1){
            foreach ($this->_idProducto as $key => $prod) {
                $parms = array(
                    ':flag' => 2,
                    ':idCotizacion' => $data['idCotizacion'],
                    ':idRepresentante' => '',
                    ':mesesContrato' => $this->_meses,
                    ':diasOferta' => '',
                    ':total' => '',
                    ':idCaratula' => AesCtr::de($prod),
                    ':precio' => $this->_precio[$key],
                    ':produccion' => $this->_produccion[$key],
                    ':usuario' => ''
                );
                $this->execute($query,$parms);  
            }
        }
        return $data;
    }
    
    public function getCotizacion(){
        $query = "
        SELECT 
                c.`codigo`,
                CONCAT(c.`descripcion`,' - ',ct.`ubicacion`)AS producto,
                cd.`precio`,
                p.`nombrecompleto`,
                p.`email`,
                cd.`cantidad_mes`,
                cd.`costo_produccion`,
                co.`cotizacion_numero`,
                cd.`importe`,
                ct.`dimension_alto`,
                ct.`dimension_ancho`,
                ct.`dimesion_area`
        FROM `lgk_cotizaciond` cd
        INNER JOIN `lgk_caratula` c ON c.`id_caratula`=cd.`id_caratula`
        INNER JOIN `lgk_cotizacion` co ON co.`id_cotizacion`=cd.`id_cotizacion`
        INNER JOIN `lgk_catalogo` ct ON ct.`id_producto`=c.`id_producto`
        INNER JOIN mae_persona p ON p.`id_persona`=co.`id_representante`
        WHERE cd.`id_cotizacion`=:idCotizacion;";
        
        $parms = array(
            ':idCotizacion' => $this->_idCotizacion
        );
        $data = $this->queryAll($query,$parms);            
        return $data;
    }
    
}

?>