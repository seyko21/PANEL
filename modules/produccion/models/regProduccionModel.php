<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 05-09-2014 23:09:58 
* Descripcion : regProduccionModel.php
* ---------------------------------------
*/ 

class regProduccionModel extends Model{

    private $_flag;
    private  $_idProduccion;
    public $_cod;
    private $_usuario;
    private $_term;
    private $_fecha;
    private $_idProducto;
    private $_montoTotal;
    private $_observacion;
    private $_idConcepto;
    private $_cantidad;
    private $_precio ;
    private $_chkdel;


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
        $this->_idProduccion   = Aes::de(Formulario::getParam("_idProduccion"));    /*se decifra*/
        $this->_usuario                 = Session::get("sys_idUsuario");
        $this->_term  =   Formulario::getParam(REPRO."_term"); 
        $this->_chkdel  = $this->post(REPRO.'chk_delete');
        $this->_fecha     = Functions::cambiaf_a_mysql(Formulario::getParam(REPRO."txt_fechains"));
        $this->_idProducto     = AesCtr::de(Formulario::getParam(REPRO."txt_idproducto"));
        $this->_montoTotal     = Functions::deleteComa(Formulario::getParam(ORINS."txt_total"));
        $this->_observacion     = Formulario::getParam(REPRO."txt_obs");
        $this->_idConcepto     = Formulario::getParam(ORINS."hhddIdConcepto"); #array
        $this->_cantidad     = Formulario::getParam(ORINS."txt_cantidad");#array
        $this->_precio     = Formulario::getParam(ORINS."txt_precio");#array
        $this->_cod =  Formulario::getParam("_cod"); 
        $this->_iDisplayStart  =   Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength =   Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   =   Formulario::getParam("iSortingCols");
        $this->_sSearch        =   Formulario::getParam("sSearch");
    }
    
    public function getGridProduccion(){
        $aColumns       =   array( 'chk','numero_produccion','u.distrito','fecha','ubicacion','elemento','total_produccion','total_asignado','total_saldo' ); //para la ordenacion y pintado en html
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
        $query = "call sp_prodProduccionGrid(:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ':iDisplayStart' => $this->_iDisplayStart,
            ':iDisplayLength' => $this->_iDisplayLength,
            ':sOrder' => $sOrder,
            ':sSearch' => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        
        return $data; 
       
    }
    
    public function getProductos(){
        $query = "
        SELECT 
                c.`id_producto`,
                c.`ubicacion`
        FROM lgk_catalogo c
        WHERE c.`ubicacion` LIKE CONCAT('%".$this->_term."%') AND c.estado <> '0'
        AND c.`id_producto` NOT IN(SELECT id_producto FROM `prod_produccionpanel` WHERE estado = 'A'); ";
        
        
        $parms = array();
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function newRegProduccion(){
        $query = "CALL sp_prodProduccionMantenimiento("
                . ":flag,"
                . ":idProduccion,"
                . ":fecha,"
                . ":idProducto,"
                . ":montoTotal,"
                . ":obs,"
                . ":idConcepto,"
                . ":cantidad,"
                . ":precio,"
                . ":usuario"
            . "); ";
        
        /*si flag == 2, se elimina detalle para editar*/
        if($this->_flag == 3){
            $parms = array(
                ':flag'=> 3,
                ':idProduccion'=> $this->_idProduccion,
                ':fecha'=> '',
                ':idProducto'=> '',
                ':montoTotal'=> '',
                ':obs'=> '',
                ':idConcepto'=> '',
                ':cantidad'=> '',
                ':precio'=> '',
                ':usuario'=> $this->_usuario
            );
            $data = $this->execute($query,$parms);
        }
        
        $parms = array(
            ':flag'=> 1,
            ':idProduccion'=> $this->_idProduccion,
            ':fecha'=> $this->_fecha,
            ':idProducto'=> $this->_idProducto,
            ':montoTotal'=> $this->_montoTotal,
            ':obs'=> $this->_observacion,
            ':idConcepto'=> '',
            ':cantidad'=> '',
            ':precio'=> '',
            ':usuario'=> $this->_usuario
        );
        $data = $this->queryOne($query,$parms);
        
        $idProduccion = $data['idProduccion'];
        
        if($data['result'] == '1'){
            /*detalle de produccion*/
            foreach ($this->_idConcepto as $key=>$idConcepto) {
                $parmsx = array(
                    ':flag'=> 2,
                    ':idProduccion'=>$idProduccion,
                    ':fecha'=> $this->_fecha,
                    ':idProducto'=> '',
                    ':montoTotal'=> '',
                    ':obs'=> '',
                    ':idConcepto'=> AesCtr::de($idConcepto),
                    ':cantidad'=> Functions::deleteComa($this->_cantidad[$key]),
                    ':precio'=> Functions::deleteComa($this->_precio[$key]),
                    ':usuario'=> $this->_usuario
                );
                $this->execute($query,$parmsx);
            }
        }
        $datax = array('result'=>1);
        return $datax;
    }
    
    public function getProduccion(){
        $query = "
        SELECT 
                p.`numero_produccion`,
                DATE_FORMAT(p.`fecha`,'%d/%m/%Y')AS fecha,
                p.`observacion`,
                pd.`precio`,
                pd.`cantidad`,
                pd.`costo_importe`,
                c.`descripcion` AS concepto,
                ct.`ubicacion`,
                p.`total_produccion`,
                ct.`dimension_alto`,
                ct.`dimension_ancho`,
                ct.`dimesion_area`,
                ub.distrito as ciudad,
                p.imagen
        FROM `prod_produccionpaneld` pd
        INNER JOIN `prod_produccionpanel` p ON p.`id_produccion`=pd.`id_producion`
        INNER JOIN pub_concepto c ON c.`id_concepto`=pd.`id_concepto`
        INNER JOIN lgk_catalogo ct ON ct.`id_producto`=p.`id_producto`     
        INNER JOIN ub_ubigeo ub on ub.id_ubigeo = ct.id_ubigeo
        WHERE pd.`id_producion`=:idProduccion; ";
        
        $parms = array(
            ':idProduccion'=>  $this->_idProduccion
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function anularRegProduccionAll(){
        foreach ($this->_chkdel as $value) {
            $query = "UPDATE prod_produccionpanel SET estado = '0' WHERE id_produccion = :idProduccion; ";
            $parms = array(
                ':idProduccion' => AesCtr::de($value)
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    
    public function findProduccion(){
        $query = "
        SELECT
                p.`id_produccion`,
                p.`id_producto`,
                c.`ubicacion`,
                DATE_FORMAT(p.`fecha`,'%d-%m-%Y')AS fecha,
                p.`observacion`
        FROM `prod_produccionpanel` p
        INNER JOIN lgk_catalogo c ON c.`id_producto`=p.`id_producto`
        WHERE p.`id_produccion`=:idProduccion; ";
        
        $parms = array(
            ':idProduccion'=>  $this->_idProduccion
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function getConceptos(){
        //c.destino = 'P' and 
        $query = "
        SELECT
                pd.`id_concepto`,
                c.`descripcion` AS concepto,
                pd.`precio`,
                pd.`cantidad`,
                pd.`costo_importe`
        FROM `prod_produccionpaneld` pd
        INNER JOIN pub_concepto c ON c.`id_concepto`=pd.`id_concepto`
        WHERE pd.`id_producion` = :idProduccion; ";
        
        $parms = array(
            ':idProduccion'=>  $this->_idProduccion
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getImagen(){
        $query = "
        SELECT
                imagen
        FROM `prod_produccionpanel` 
        WHERE `id_produccion` = :idProduccion; ";
        
        $parms = array(
            ':idProduccion'=>  $this->_idProduccion
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function subirImagen($img){
        $query = "
        UPDATE prod_produccionpanel SET
                imagen = :img
        WHERE `id_produccion` = :idProduccion; ";
        
        $parms = array(
            ':idProduccion'=>  $this->_idProduccion,
            ':img'=>  $img
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }
    
}

?>