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
    private $_costoProduccion;
    private $_meses;
    private $_oferta;
    private $_total;
    private $_igv;
    private $_validez;
    private $_usuario;
    private $_chkdel;
    private $_xSearch;
    
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
        $this->_costoProduccion  = $this->post(T8.'txt_produccion');
        $this->_total       = $this->post(T8.'txt_total');
        $this->_igv         = $this->post(T8.'lst_igv');
        $this->_idPersona   = Session::get('sys_idPersona');
        $this->_validez     = $this->post(T8.'txt_diasvalidez');
        $this->_usuario     = Session::get('sys_idUsuario');
        $this->_xSearch     = $this->post(T8.'_term');
        $this->_chkdel  = $this->post(T8.'chk_delete');
        
        $this->_iDisplayStart  =   $this->post('iDisplayStart'); 
        $this->_iDisplayLength =   $this->post('iDisplayLength'); 
        $this->_iSortingCols   =   $this->post('iSortingCols');
        $this->_sSearch        =   $this->post('sSearch');
    }
    
    public function getGridCotizacion() {
        $aColumns       =   array( '','cotizacion_numero','nombrecompleto' ,'fechacoti','meses_contrato','13','total'); //para la ordenacion y pintado en html
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
        
        $query = "call sp_cotiGenerarCotizacionGrid(:acceso,:usuario,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':acceso' => Session::get('sys_all'),
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
    
    private function anularUnaCotizacion(){
        $query = "UPDATE `lgk_cotizacion` SET
                    `estado` = 'A'
                WHERE `id_cotizacion` = :idCotizacion;";
        $parms = array(
            ':idCotizacion' => $this->_idCotizacion
        );
        $this->execute($query,$parms);
    }

    public function generarCotizacion(){
        $query = "CALL sp_cotiGenerarCotizacion(:flag,:idCotizacion,:idRepresentante,:mesesContrato,:diasOferta,:total,:idCaratula,:precio,:produccion,:usuario,:igv,:validez);";
        
        if($this->_flag == 11){//cuando se clona una cotizacion, hay q anularla
            $this->anularUnaCotizacion();
            $this->_flag = 1; //retrorna a 1 para ael SP
        }
        
        $parms = array(
            ':flag' => $this->_flag,
            ':idCotizacion' => '',
            ':idRepresentante' => $this->_keyPersona,
            ':mesesContrato' => $this->_meses,
            ':diasOferta' => $this->_oferta,
            ':total' => $this->_total,
            ':idCaratula' => '',
            ':precio' => '',
            ':produccion' => $this->_costoProduccion,
            ':usuario' => $this->_usuario,
            ':igv' => $this->_igv,
            ':validez' => $this->_validez
        );
        $data = $this->queryOne($query,$parms);  
        
        if($data['result'] == 1){
            $item = 0;
            foreach ($this->_idProducto as $key => $prod) {
                $item++;
                $parms = array(
                    ':flag' => 2,
                    ':idCotizacion' => $data['idCotizacion'],
                    ':idRepresentante' => '',
                    ':mesesContrato' => $this->_meses,
                    ':diasOferta' => $item,
                    ':total' => '',
                    ':idCaratula' => AesCtr::de($prod),
                    ':precio' => str_replace(",","",$this->_precio[$key]),
                    ':produccion' => $this->_produccion[$key],
                    ':usuario' => '',
                    ':igv' => '',
                    ':validez' => ''
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
                CONCAT(ct.`ubicacion`,' - ',c.`descripcion`)AS producto,
                cd.`precio`,
                p.`nombrecompleto`,
                p.`email`,
                cd.`cantidad_mes`,
                cd.`costo_produccion`,
                co.`cotizacion_numero`,
                cd.`importe`,
                ct.`dimension_alto`,
                ct.`dimension_ancho`,
                ct.`dimesion_area`,
                tp.descripcion AS elemento,
                (SELECT usuario FROM mae_usuario WHERE id_usuario=co.`usuario_creacion`)AS mail_user
        FROM `lgk_cotizaciond` cd
        INNER JOIN `lgk_caratula` c ON c.`id_caratula`=cd.`id_caratula`
        INNER JOIN `lgk_cotizacion` co ON co.`id_cotizacion`=cd.`id_cotizacion`
        INNER JOIN `lgk_catalogo` ct ON ct.`id_producto`=c.`id_producto`
        INNER JOIN mae_persona p ON p.`id_persona`=co.`id_persona`
        INNER JOIN lgk_tipopanel tp ON tp.id_tipopanel = ct.id_tipopanel
        WHERE cd.`id_cotizacion`=:idCotizacion;";
        
        $parms = array(
            ':idCotizacion' => $this->_idCotizacion
        );
        $data = $this->queryAll($query,$parms);            
        return $data;
    }
    
    /*selecciona todos los clientes de un vendedor*/
    public function getClientes(){
        $query = "
         SELECT 
                id_persona,
                nombrecompleto
         FROM mae_persona
         WHERE usuarioregistro = :usuario AND id_personapadre <> ''
         AND nombrecompleto LIKE CONCAT('%',:cliente,'%'); ";
        
        $parms = array(
            ':usuario'=> $this->_usuario,
            ':cliente' => $this->_xSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function anularCotizacion(){
        foreach ($this->_chkdel as $value) {
            $query = "UPDATE `lgk_cotizacion` SET
			`estado` = 'A'
                    WHERE `id_cotizacion` = :idCotizacion;";
            $parms = array(
                ':idCotizacion' => Aes::de($value)
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    
    public function getProduccion(){
        $query = "SELECT valor FROM `pub_parametro` WHERE alias = :alias;";
        $parms = array(
            ':alias' => 'COPM2'
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function getProductosCotizados(){
        $query = "CALL sp_cotiGenerarCotizacionMisProductos(:flag,:idCotizacion,:ubicacion);";
        
        $parms = array(
            ':flag' => 2,
            ':idCotizacion' => $this->_idCotizacion,
            ':ubicacion' => ''
        );
        $data = $this->queryAll($query,$parms);            
        return $data;
    }
    
    public function findCotizacion(){
        $query = "SELECT 
                c.`id_persona`,
                p.`nombrecompleto`,
                c.`meses_contrato`,
                c.`dias_oferta`,
                c.`validez`,
                c.`valor_produccion`
        FROM `lgk_cotizacion` c
        INNER JOIN mae_persona p ON p.`id_persona`=c.`id_persona`
        WHERE c.`id_cotizacion`=:idCotizacion";
        $parms = array(
            ':idCotizacion' => $this->_idCotizacion
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
}

?>