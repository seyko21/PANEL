<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : generarVentaModel.php
* ---------------------------------------
*/ 

class generarVentaModel extends Model{

    private $_flag;
    private $_idVenta;
    public $_cod;    
    private $_term, $_xSearch;
    private $_idMoneda;
    private $_chkdel;
    
    private $_fecha;
    private $_idProducto;
    private $_montoTotal;
    private $_observacion;
    private $_codImpr;
    private $_idPersona;
    private $_moneda;
    private $_tipoDoc;
    private $_tipoMov;    
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
        $this->_idVenta   = Aes::de(Formulario::getParam("_idVenta"));    /*se decifra*/
        $this->_usuario                 = Session::get("sys_idUsuario");
        
        $this->_fecha     = Functions::cambiaf_a_mysql(Formulario::getParam(VGEVE."txt_fecha"));
        $this->_montoTotal     = Functions::deleteComa(Formulario::getParam(VGEVE."txt_total"));
        $this->_observacion     = Formulario::getParam(VGEVE."txt_obs");
        $this->_codImpr     = Formulario::getParam(VGEVE."txt_codImpr");
        $this->_idPersona     = Aes::de(Formulario::getParam(VGEVE."txt_idpersona"));
        $this->_moneda     = Formulario::getParam(VGEVE."lst_moneda");
        $this->_tipoDoc     = Formulario::getParam(VGEVE."lst_tipoDoc");
        $this->_tipoMov     = 'I';
        
        $this->_idProducto     = Formulario::getParam(VGEVE."hhddIdProducto"); #array
        $this->_cantidad1     = Formulario::getParam(VGEVE."txt_cantidad1");#array
        $this->_cantidad2     = Formulario::getParam(VGEVE."txt_cantidad2");#array        
        $this->_precio     = Formulario::getParam(VGEVE."txt_precio");#array
        
        $this->_cod =  Formulario::getParam("_cod"); 
        $this->_term  =   Formulario::getParam("_term");         
        $this->_chkdel  = $this->post(VGEVE.'chk_delete');        
        $this->_idMoneda   = Formulario::getParam("_idMoneda");
        
        $this->_xSearch     = Formulario::getParam(VGEVE.'_term');
        
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    public function getGridGenerarVenta(){
        $aColumns       =   array( 'chk','codigo_impresion','nombre_descripcion','tipo_doc','fecha','moneda','monto_total','estado'); //para la ordenacion y pintado en html
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
        $query = "call sp_ventaGenerarVentaGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        
        return $data; 
       
    }
    
    public function newGenerarVenta(){
        $query = "CALL sp_ventaGenerarVentaMantenimiento("
                . ":flag,"
                . ":idVenta,"
                . ":codigoImpr,"
                . ":fecha,"
                . ":idPersona,"
                . ":moneda,"
                . ":montoTotal,"
                . ":tipoDoc,"
                . ":obs,"
                . ":tipoMov,"
                . ":idProducto,"
                . ":cant1,"
                . ":cant2,"
                . ":precio,"
                . ":usuario"
            . "); ";
        
        /*si flag == 2, se elimina detalle para editar*/
        if($this->_flag == 3){
            $parms = array(
                ':flag'=> 3,
                ':idVenta'=> $this->_idVenta,
                ':codigoImpr'=>'',
                ':fecha'=>'',
                ':nombre'=>'',
                ':moneda'=>'',
                ':montoTotal'=>'',
                ':tipoDoc' => '',
                ':obs' => '',
                ':tipoMov' => '',
                ':idProducto' => '',
                ':cant1' => '',                
                ':cant2' => '',
                ':precio' => '',
                ':usuario'=> $this->_usuario
            );
            $data = $this->execute($query,$parms);
        }
        
        $parms = array(
            ':flag'=> 1,
            ':idVenta'=> $this->_idVenta,
            ':codigoImpr'=>$this->_codImpr,
            ':fecha'=>$this->_fecha,
            ':idPersona'=>$this->_idPersona,
            ':moneda'=>$this->_moneda,
            ':montoTotal'=>$this->_montoTotal,
            ':tipoDoc' => $this->_tipoDoc,
            ':obs' => $this->_observacion,
            ':tipoMov' => $this->_tipoMov,
            ':idProducto' => '',
            ':cant1' => '',                
            ':cant2' => '',
            ':precio' => '',
            ':usuario'=> $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
              
        $idVenta = $data['idVenta'];
        
        if($data['result'] == '1'){
            /*detalle de produccion*/
            foreach ($this->_idProducto as $key=>$idProducto) {
                $parmsx = array(
                    ':flag'=> 2,
                    ':idVenta'=>$idVenta,
                    ':codigoImpr'=>'',
                    ':fecha'=>'',
                    ':idPersona'=>'',
                    ':moneda'=>'',
                    ':montoTotal'=>'',
                    ':tipoDoc' => '',
                    ':obs' => '',
                    ':tipoMov' => '',
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
      
    public function anularGenerarVentaAll(){
        foreach ($this->_chkdel as $value) {
            $query = "UPDATE ven_documento SET estado = 'A' WHERE id_docventa = :idVenta; ";
            $parms = array(
                ':idVenta' => AesCtr::de($value)
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
            u.cantidad_multiple
          FROM `ven_producto` p
            inner join `ven_unidadmedida` u on u.`id_unidadmedida` = p.`id_unidadmedida`
          WHERE p.estado = 'A' and  p.`moneda` = :moneda; ";
        
        $parms = array(
            ':moneda'=>  $this->_idMoneda
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    } 
    
    public function getFindVenta(){
        $query = "
        SELECT
            d.`id_docventa`,
            d.`periodo`,
            d.`codigo_impresion`,
            DATE_FORMAT(d.`fecha`,'%d/%m/%Y')AS fecha,
            d.`id_persona`,
            (select pp.nombrecompleto from mae_persona pp where pp.id_persona = d.id_persona) as cliente,
            d.`moneda`,
            d.`monto_importe`,
            d.`tipo_doc`,
            d.`observacion`
          FROM `ven_documento` d
          WHERE d.`id_docventa` = :idVenta; ";
        
        $parms = array(
            ':idVenta'=>  $this->_idVenta
        );
        $data = $this->queryOne($query,$parms);      
        
        return $data;
    }    
    
    public function getFindVentaD(){
        $query = "
        SELECT
            dd.`cantidad_1`, dd.`cantidad_2`, dd.`cantidad_real`,
            dd.`id_detalle`, dd.`id_docventa`, dd.`id_producto`, dd.`importe`, dd.`precio`,
            p.`descripcion`, u.`sigla`, u.`cantidad_multiple`
        FROM `ven_documentod` dd
                INNER JOIN `ven_producto` p ON p.`id_producto` = dd.`id_producto`
                INNER JOIN `ven_unidadmedida` u ON u.`id_unidadmedida` = p.`id_unidadmedida`
        WHERE dd.`id_docventa` =  :idVenta; ";
        
        $parms = array(
            ':idVenta'=>  $this->_idVenta
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }   
    /*selecciona todos los clientes Registrados*/
    public function getClientes(){
        $query = "
         select t.id_persona, t.nombrecompleto, t.numerodocumento
         from (
         SELECT 
                p.id_persona,
                p.nombrecompleto,
                p.numerodocumento
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