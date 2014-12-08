<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : generarCotizacionModel.php
* ---------------------------------------
*/ 

class generarCotizacionModel extends Model{

    private $_flag;
    private $_idCotizacion;
    public $_cod;    
    private $_term, $_xSearch;
    private $_idMoneda;
    private $_chkdel;
    
    private $_fecha;
    private $_idProducto;
    private $_montoTotal;
    private $_observacion;
    private $_idPersona;
    private $_moneda;
    private $_usuario;
    
    private $_cantidad1;
    private $_cantidad2;
    private $_precio;

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
        $this->_flag                    = Formulario::getParam("_flag");
        $this->_idCotizacion   = Aes::de(Formulario::getParam("_idCotizacion"));    /*se decifra*/
        $this->_usuario                 = Session::get("sys_idUsuario");
        
        $this->_fecha     = Functions::cambiaf_a_mysql(Formulario::getParam(VCOTI."txt_fecha"));
        $this->_montoTotal     = Functions::deleteComa(Formulario::getParam(VCOTI."txt_total"));
        $this->_observacion     = Formulario::getParam(VCOTI."txt_obs");
        $this->_idPersona     = Aes::de(Formulario::getParam(VCOTI."txt_idpersona"));
        $this->_moneda     = Formulario::getParam(VCOTI."lst_moneda");
        
        $this->_idProducto     = Formulario::getParam(VCOTI."hhddIdProducto"); #array
        $this->_cantidad1     = Formulario::getParam(VCOTI."txt_cantidad1");#array
        $this->_cantidad2     = Formulario::getParam(VCOTI."txt_cantidad2");#array        
        $this->_precio     = Formulario::getParam(VCOTI."txt_precio");#array
        
        $this->_cod =  Formulario::getParam("_cod"); 
        $this->_term  =   Formulario::getParam("_term");         
        $this->_chkdel  = $this->post(VCOTI.'chk_delete');        
        $this->_idMoneda   = Formulario::getParam("_idMoneda");
        
        $this->_xSearch     = Formulario::getParam(VCOTI.'_term');
        
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    public function getGridGenerarCotizacion(){
        $aColumns       =   array( 'chk','codigo','nombre_descripcion','fecha','moneda','monto_total','codigo_venta','estado'); //para la ordenacion y pintado en html
        /*
	 * Ordenando, se verifica por que columna se ordenara
	 */
        $sOrder = "";
        for ( $i=0 ; $i<intval( $this->_iSortingCols ) ; $i++ ){
                if ( Formulario::getParam( "bSortable_".intval(Formulario::getParam("iSortCol_".$i)) ) == "true" ){
                        $sOrder .= " ".$aColumns[ intval( Formulario::getParam("iSortCol_".$i) ) ]." ".
                                (Formulario::getParam("sSortDir_".$i)==="asc" ? "asc" : 'desc') .",";
                }
        }
        $sOrder = substr_replace( $sOrder, "", -1 );
        $query = "call sp_ventaGenerarCotizacionGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        
        return $data; 
       
    }
    
    public function newGenerarCotizacion(){
        if($this->_flag == 3){//cuando se clona una cotizacion, hay q anularla
            $this->anularUnaCotizacion();
            $this->_flag = 1; //retorna a 1 para ael SP
        }
                
        $query = "CALL sp_ventaGenerarCotizacionMantenimiento("
                . ":flag,"
                . ":idCotizacion,"
                . ":fecha,"
                . ":idPersona,"
                . ":moneda,"
                . ":montoTotal,"
                . ":obs,"
                . ":idProducto,"
                . ":cant1,"
                . ":cant2,"
                . ":precio,"
                . ":usuario"
            . "); ";
               
        $parms = array(
            ':flag'=> $this->_flag,
            ':idCotizacion'=> $this->_idCotizacion,
            ':fecha'=>$this->_fecha,
            ':idPersona'=>$this->_idPersona,
            ':moneda'=>$this->_moneda,
            ':montoTotal'=>$this->_montoTotal,
            ':obs' => $this->_observacion,
            ':idProducto' => '',
            ':cant1' => '',                
            ':cant2' => '',
            ':precio' => '',
            ':usuario'=> $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
              
        $idCotizacion = $data['idCotizacion'];
                
        if($data['result'] == '1'){
            /*detalle de produccion*/
            foreach ($this->_idProducto as $key=>$idProducto) {
                $parmsx = array(
                    ':flag'=> 2,
                    ':idCotizacion'=>$idCotizacion,
                    ':fecha'=>'',
                    ':idPersona'=>'',
                    ':moneda'=>'',
                    ':montoTotal'=>'',
                    ':obs' => '',
                    ':idProducto' => AesCtr::de($idProducto),
                    ':cant1' => Functions::deleteComa($this->_cantidad1[$key]),                
                    ':cant2' => Functions::deleteComa($this->_cantidad2[$key]),
                    ':precio'=> Functions::deleteComa($this->_precio[$key]),
                    ':usuario'=> $this->_usuario
                );
                $this->execute($query,$parmsx);
              
            }
        }
        $datax = array('result'=>1);
        return $datax;
    }

    private function anularUnaCotizacion(){
        $query = "call sp_ventaAnulacion(:flag,:idCotizacion,:idSucursal,:usuario);";
        $parms = array(
            ':flag' =>'2',
            ':idCotizacion' => $this->_idCotizacion,
            ':idSucursal'=> Session::get('sys_idSucursal'),
             ':usuario'=> $this->_usuario
        );
        $this->execute($query,$parms);
    }
    
    public function anularGenerarCotizacionAll(){
        foreach ($this->_chkdel as $value) {
            $query = "call sp_ventaAnulacion(:flag,:idCotizacion,:idSucursal,:usuario); ";
            $parms = array(
                ':flag' =>'2',
                ':idCotizacion' => AesCtr::de($value),
                ':idSucursal'=> Session::get('sys_idSucursal'),
                 ':usuario'=> $this->_usuario
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }    
    
    public function getFindProductos(){
        $query = "
        SELECT
            p.`id_producto`,
            p.`descripcion`,
            p.`precio`,
            p.`estado`,
            p.`moneda`,
            (case p.moneda when 'SO' then 'S/.' when 'DO' then '$ USD' end) as monedad,
            u.`sigla` AS unidad_medida,
            p.`id_unidadmedida`,
            u.cantidad_multiple,
            p.incligv,
            (SELECT CAST(valor AS DECIMAL(10,2)) FROM `pub_parametro` WHERE alias = 'IGV') AS igv
          FROM `ven_producto` p
            inner join `ven_unidadmedida` u on u.`id_unidadmedida` = p.`id_unidadmedida`
          WHERE p.estado = 'A' and  p.`moneda` = :moneda; ";
        
        $parms = array(
            ':moneda'=>  $this->_idMoneda
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    } 
    
    public function getFindCotizacion(){
        $query = "
        SELECT
            d.`id_cotizacion`,
            d.`periodo`,
            d.`codigo`,
            DATE_FORMAT(d.`fecha`,'%d/%m/%Y')AS fecha,
            d.`id_persona`,
            (select pp.nombrecompleto from mae_persona pp where pp.id_persona = d.id_persona) as cliente,
            (select concat(`sigla`,' - ',`descripcion`) from pub_moneda mo where mo.id_moneda = d.`moneda`) as descripcion_moneda,
            (select `sigla` from pub_moneda mo where mo.id_moneda = d.`moneda`) as moneda,                        
            d.`observacion`,
            d.estado,
            d.monto_importe,
            d.porcentaje_igv,
            (SELECT usuario FROM mae_usuario WHERE id_usuario= d.`usuario_creacion`)AS mail_user,
            (SELECT pp.nombrecompleto FROM mae_usuario uu
			INNER JOIN mae_persona pp ON pp.persona = uu.persona
                WHERE id_usuario= d.`usuario_creacion`)AS vendedor,
            (SELECT pp.telefono FROM mae_usuario uu
			INNER JOIN mae_persona pp ON pp.persona = uu.persona
                WHERE id_usuario= d.`usuario_creacion`)AS telefono_vendedor,
             p.`nombrecompleto`,
             p.numerodocumento,
             p.`email`
          FROM ven_cotizacion d
            INNER JOIN mae_persona p ON p.`id_persona` = d.`id_persona`
          WHERE d.id_cotizacion = :idCotizacion; ";
        
        $parms = array(
            ':idCotizacion'=>  $this->_idCotizacion
        );
        $data = $this->queryOne($query,$parms);      
        
        return $data;
    }    
    
    public function getFindCotizacionD(){
        $query = "
        SELECT
            dd.`cantidad_1`, dd.`cantidad_2`, dd.`cantidad_real`,
            dd.`id_detalle`, dd.`id_cotizacion`, dd.`id_producto`, dd.`importe`, dd.`precio`,
            p.`descripcion`, u.`sigla`, u.`cantidad_multiple`,
            dd.incligv, dd.importe_afectado, dd.impuesto, dd.total_impuesto, dd.precio_final
        FROM `ven_cotizaciond` dd
                INNER JOIN `ven_producto` p ON p.`id_producto` = dd.`id_producto`
                INNER JOIN `ven_unidadmedida` u ON u.`id_unidadmedida` = p.`id_unidadmedida`
        WHERE dd.id_cotizacion =  :idCotizacion; ";
        
        $parms = array(
            ':idCotizacion'=>  $this->_idCotizacion
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }   
    /*selecciona todos los clientes Registrados*/
    public function getClientes(){
        $query = "
         select t.id_persona, t.nombrecompleto, t.numerodocumento, t.email
         from (
         SELECT 
                p.id_persona,
                p.nombrecompleto,
                p.numerodocumento,
                p.email
         FROM mae_persona p
         WHERE  p.estado <> '0' and p.id_empresa = 2 ) as t
         where (
                   t.nombrecompleto LIKE CONCAT('%',:cliente,'%') or
                   t.numerodocumento LIKE CONCAT('%',:cliente,'%') 
                ); ";
        
        $parms = array(
            ':cliente' => trim($this->_xSearch),
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }

    
}

?>