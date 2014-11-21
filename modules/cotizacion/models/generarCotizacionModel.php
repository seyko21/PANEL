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
    private  $_idCotizacion;
    public  $_numCoti;
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
    private $_observacion;
    private $_campania;


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
        $this->_flag        = Formulario::getParam('_flag');
        $this->_keyPersona  = Aes::de(Formulario::getParam(T8.'txt_idpersona'));     /*el cliente, representante*/
        $this->_ubicacion   = Formulario::getParam(T8.'txt_search');
        $this->_meses       = Formulario::getParam(T8.'txt_meses');                  /*meses de contrato*/
        $this->_oferta      = Formulario::getParam(T8.'txt_oferta');                 /*meses de oferta*/
        $this->_idCotizacion= AesCtr::de(Formulario::getParam('_idCotizacion'));
        $this->_idProducto  = Formulario::getParam(T8.'hhddIdProducto');
        $this->_precio      = Formulario::getParam(T8.'hhddPrecio');
        $this->_produccion  = Formulario::getParam(T8.'hhddProduccion');
        $this->_costoProduccion  = Formulario::getParam(T8.'txt_produccion');
        $this->_total       = Formulario::getParam(T8.'txt_total');
        $this->_igv         = Formulario::getParam(T8.'lst_igv');
        $this->_idPersona   = Session::get('sys_idPersona');
        $this->_validez     = Formulario::getParam(T8.'txt_diasvalidez');
        $this->_usuario     = Session::get('sys_idUsuario');
        $this->_xSearch     = Formulario::getParam(T8.'_term');
        $this->_chkdel  = Formulario::getParam(T8.'chk_delete');
        $this->_observacion  = Formulario::getParam(T8.'txt_obs');
        $this->_campania  = Formulario::getParam(T8.'txt_campania');
        
        $this->_numCoti = Formulario::getParam('_num');
        
        $this->_iDisplayStart  =   Formulario::getParam('iDisplayStart'); 
        $this->_iDisplayLength =   Formulario::getParam('iDisplayLength'); 
        $this->_iSortingCols   =   Formulario::getParam('iSortingCols');
        $this->_sSearch        =   Formulario::getParam('sSearch');
    }
    
    public function getGridCotizacion() {
        $aColumns       =   array( '','cotizacion_numero','nombrecompleto' ,'fechacoti','13','incluyeigv','total','estado'); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( Formulario::getParam( 'bSortable_'.intval(Formulario::getParam('iSortCol_'.$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( Formulario::getParam('iSortCol_'.$i) ) ]." ".
                                (Formulario::getParam('sSortDir_'.$i)==='asc' ? 'asc' : 'desc') .",";
                }
        }
        $sOrder = substr_replace( $sOrder, "", -1 );
        
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
      //  print_r($parms);
        return $data;
    }
    
    public function getMisProductos(){
        $query = "CALL sp_cotiGenerarCotizacionMisProductos(:flag,:idPersona,:ubicacion,:acceso);";
        
        $parms = array(
            ':flag' => 1,
            ':idPersona' => $this->_idPersona,
            ':ubicacion' => $this->_ubicacion,
            ':acceso' => Session::get('sys_all')
        );
        $data = $this->queryAll($query,$parms);            
        return $data;
    }
    
    private function anularUnaCotizacion(){
        $query = "call sp_cotiAnulacion(:idCotizacion,:usuario, :msj);";
        $parms = array(
             ':idCotizacion' => $this->_idCotizacion,
             ':usuario' => $this->_usuario,
             ':msj' => 'Cotización anulada al ser clonada.'
        );
        $this->execute($query,$parms);
    }

    public function generarCotizacion(){
        if($this->_flag == 11){//cuando se clona una cotizacion, hay q anularla
            $this->anularUnaCotizacion();
            $this->_flag = 1; //retorna a 1 para ael SP
        }
        
        $query = "CALL sp_cotiGenerarCotizacion(:flag,:idCotizacion,:idRepresentante,:mesesContrato,:diasOferta,:total,:idCaratula,:precio,:produccion,:usuario,:igv,:validez,:obs,:campania,:porcentaje_acceso,:idPersona);";
        $parms = array(
            ':flag' => $this->_flag,
            ':idCotizacion' => '',
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
            ':campania' => $this->_campania,
            ':porcentaje_acceso' => '',
            ':idPersona'=> $this->_idPersona
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
                    ':precio' => Functions::deleteComa($this->_precio[$key]),
                    ':produccion' => Functions::deleteComa($this->_produccion[$key]),
                    ':usuario' => '',
                    ':igv' => ($this->_igv == '1')?'1':'0',
                    ':validez' => '',
                    ':obs' => '',
                    ':campania' => '',
                    ':porcentaje_acceso' => Session::get('sys_all'),
                    ':idPersona'=> $this->_idPersona
                );
                $this->execute($query,$parms);                      
            }
        }
        return $data;
    }
    
    public function getCotizacion(){
        $query = "
        SELECT  co.id_cotizacion,
                c.`codigo`,
                CONCAT(ct.`ubicacion`,' - ',c.`descripcion`)AS producto,
                cd.`precio`,
                p.`nombrecompleto`,
                p.numerodocumento,
                p.`email`,
                cd.`cantidad_mes`,
                cd.`costo_produccion`,
                co.`cotizacion_numero`,
                cd.`importe`,
                ct.`dimension_alto`,
                ct.`dimension_ancho`,
                ct.`dimesion_area`,
                tp.descripcion AS elemento,
                (SELECT usuario FROM mae_usuario WHERE id_usuario=co.`usuario_creacion`)AS mail_user,
                (SELECT pp.nombrecompleto FROM mae_usuario uu
			INNER JOIN mae_persona pp ON pp.persona = uu.persona
                WHERE id_usuario=co.`usuario_creacion`)AS vendedor,
                (SELECT pp.telefono FROM mae_usuario uu
			INNER JOIN mae_persona pp ON pp.persona = uu.persona
                WHERE id_usuario=co.`usuario_creacion`)AS telefono_vendedor,
                co.nombre_campania,
                (select pp.nombrecompleto from `mae_persona` pp where pp.id_persona = p.`id_personapadre` ) as razonsocial,
                (select pp.numerodocumento from `mae_persona` pp where pp.id_persona = p.`id_personapadre` ) as ruccliente,
                co.`observaciones`,
                co.`incluyeigv`,
                co.fecha_cotizacion,
                co.`subtotal`,
                co.`impuesto`,
                co.`total`,
                (SELECT CAST(valor AS DECIMAL(10,5)) FROM `pub_parametro` WHERE alias = 'IGV') as pigv,
                DATE_ADD(co.`fecha_cotizacion`, INTERVAL co.`validez` DAY)as vencimiento,
                co.estado,
                co.valor_produccion,
                cd.`precio_incigv`,
		cd.`importe_incigv`,
		cd.`porcentaje_igv`,
		cd.`impuesto` as impuestodetalle,
		cd.`produccion_incigv`
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
        $all = '';
        /*si no es SADM o ADM solo se mostrara los clientes del usuario*/
        if(Session::get('sys_all') == 'N'){
            $all = "p.usuarioregistro = '".$this->_usuario."'  AND";
        }
        $query = "
         select t.id_persona, t.nombrecompleto, t.razon_social
         from (
         SELECT 
                p.id_persona,
                p.nombrecompleto,
                (SELECT pp.nombrecompleto 
                FROM mae_persona pp WHERE pp.id_persona=p.id_personapadre) AS razon_social
         FROM mae_persona p
         WHERE  p.estado <> '0' and ".$all." p.id_personapadre <> '' and p.id_empresa = 1 ) as t
         where (
                   t.nombrecompleto LIKE CONCAT('%',:cliente,'%') or
                   t.razon_social LIKE CONCAT('%',:cliente,'%') 
                ); ";
        
        $parms = array(
            ':cliente' => $this->_xSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function anularCotizacion(){
        foreach ($this->_chkdel as $value) {
            $query = "call sp_cotiAnulacion(:idCotizacion,:usuario, :msj);";
            $parms = array(
                ':idCotizacion' => Aes::de($value),
                ':usuario' => $this->_usuario,
                ':msj' => 'Cotización anulada.'
            );
            $this->execute($query,$parms);
           // print_r($parms);
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
        $query = "CALL sp_cotiGenerarCotizacionMisProductos(:flag,:idCotizacion,:ubicacion,:acceso);";
        
        $parms = array(
            ':flag' => 2,
            ':idCotizacion' => $this->_idCotizacion,
            ':ubicacion' => '',
            ':acceso' => ''
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
                c.`valor_produccion`,
                c.incluyeigv as igv,
                c.observaciones,
                c.nombre_campania
        FROM `lgk_cotizacion` c
        INNER JOIN mae_persona p ON p.`id_persona`=c.`id_persona`
        WHERE c.`id_cotizacion`=:idCotizacion";
        $parms = array(
            ':idCotizacion' => $this->_idCotizacion
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function postTiempoCotizacion(){
        $query = "
        INSERT INTO `lgk_tiempocotizacion`(
               `estado`,
               `usuario_creacion`,
               `id_cotizacion`,
               `observacion`
        )
        VALUES (
                :estado,
                :usuario,
                :idCotizacion,
                :observacion
        );";

        $parms = array(
            ':estado' => 'S',
            ':usuario'=>  $this->_usuario,
            ':idCotizacion'=>  $this->_idCotizacion,
            ':observacion'=>'Cotización enviada al cliente'
        );
        $data = $this->execute($query,$parms);            
    }
     public function getPiePagina(){
        $query = "SELECT valor FROM `pub_parametro` WHERE alias = :alias;";
        $parms = array(
            ':alias' => 'PIE'
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }   
}

?>
