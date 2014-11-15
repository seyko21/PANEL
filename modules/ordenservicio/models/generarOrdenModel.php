<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 09-09-2014 06:09:13 
* Descripcion : generarOrdenModel.php
* ---------------------------------------
*/ 

class generarOrdenModel extends Model{

    private $_flag;
    private $_idOrden;
    public $_numOrden;
    private $_idCuota;
    private $_monto;
    private $_fechaPago;    
    private $_idContrato;
    private $_fechaContrato;
    private $_libreImpuesto;
    private $_usuario;
    private $_chkdel;
    
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
        $this->_idCuota   = Aes::de(Formulario::getParam("_idCuota"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_monto  = str_replace(',','',Formulario::getParam(GNOSE."txt_monto")); 
        $this->_fechaPago  = Functions::cambiaf_a_mysql(Formulario::getParam(GNOSE."txt_fechapago")); 
        $this->_fechaContrato  = Functions::cambiaf_a_mysql(Formulario::getParam(GNOSE."txt_fechacontrato"));
        $this->_idContrato  = Formulario::getParam(GNOSE."lst_contrato"); 
        $this->_libreImpuesto  = (Formulario::getParam(GNOSE."chk_impuesto")==''?'0':'1'); 
        $this->_chkdel  = $this->post(GNOSE.'chk_delete');
        
        $this->_numOrden = Formulario::getParam("_numOrden");
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: GenerarOrden*/
    public function getGenerarOrden(){
        $aColumns       =   array("","orden_numero","cotizacion_numero","cliente","fecha","meses_contrato","monto_total","estado" ); //para la ordenacion y pintado en html
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
        $query = "call sp_ordseOrdenServicioGrid(:acceso,:usuario,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":acceso" => Session::get('sys_all'),
            ":usuario" => $this->_usuario,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function insertCuota(){
        $query = "CALL sp_ordseOrdenServicioCuota(:flag,:idOrden,:monto,:fechaPago,:fechaContrato,:idContrato,:libreImpuesto,:usuario);";
        
        $parms = array(
            ':flag'=>1,
            ':idOrden'=>$this->_idOrden,
            ':monto'=>$this->_monto,
            ':fechaPago'=>$this->_fechaPago,
            ':fechaContrato'=>'',
            ':idContrato'=>'',
            ':libreImpuesto'=>'',
            ':usuario'=>$this->_usuario,
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function getGridCuotas(){
        $aColumns       =   array("numero_cuota"); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ordseOrdenServicioCuotasGrid(:idOrden,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":idOrden" => $this->_idOrden,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function editOrden(){
        $query = "CALL sp_ordseOrdenServicioCuota(:flag,:idOrden,:monto,:fechaPago,:fechaContrato,:idContrato,:libreImpuesto,:usuario);";
        
        $parms = array(
            ':flag'=>2,
            ':idOrden'=>$this->_idOrden,
            ':monto'=>'',
            ':fechaPago'=>$this->_fechaPago,
            ':fechaContrato'=>$this->_fechaContrato,
            ':idContrato'=>$this->_idContrato,
            ':libreImpuesto'=>$this->_libreImpuesto,
            ':usuario'=>$this->_usuario,
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function findOrden(){
        $query = " SELECT fecha_orden,fecha_contrato,id_contrato,`flag_impuesto`,`incluyeigv`,"
                . " `monto_venta`,`monto_impuesto`,`monto_total`, monto_total_final "
                . " FROM lgk_ordenservicio WHERE id_ordenservicio = :idOrden; ";
        
        $parms = array(
            ':idOrden'=>$this->_idOrden
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function postDeleteCuota(){
        $query = "CALL sp_ordseOrdenServicioCuota(:flag,:idCuota,:monto,:fechaPago,:fechaContrato,:idContrato,:libreImpuesto,:usuario);";
        
        $parms = array(
            ':flag'=>3,
            ':idCuota'=>$this->_idCuota,
            ':monto'=>'',
            ':fechaPago'=>'',
            ':fechaContrato'=>'',
            ':idContrato'=>'',
            ':libreImpuesto'=>'',
            ':usuario'=>$this->_usuario,
        );
        
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function getContratos(){
        $v = '';
        /*si no es ADMIN solo vera los contratos visibles*/
        if(Session::get('sys_all') == 'N'){
            $v = " `visible` = 1 AND ";
        }
        $query = " SELECT `id_contrato`,`nombre` FROM `lgk_contrato` WHERE ".$v." `estado`= :estado; ";
        
        $parms = array(
            ':estado'=> 'A'
        );
        
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getContrato(){
        $query = " CALL sp_ordseOrdenServicioConsultas(:flag,:idOrden);";
        
        $parms = array(
            ':flag'=> 1,
            ':idOrden'=> $this->_idOrden
        );
       
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    public function getCronograma(){
        $query = " CALL sp_ordseOrdenServicioConsultas(:flag,:idOrden);";
        
        $parms = array(
            ':flag'=> 2,
            ':idOrden'=> $this->_idOrden
        );
       
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
     public function getCaratula(){
        $query = " CALL sp_ordseOrdenServicioConsultas(:flag,:idOrden);";
        
        $parms = array(
            ':flag'=> 4,
            ':idOrden'=> $this->_idOrden
        );
       
        $data = $this->queryAll($query,$parms);
        return $data;
    } 
    
     public function getCronogramaContrato(){
        $query = " CALL sp_ordseOrdenServicioConsultas(:flag,:idOrden);";
        
        $parms = array(
            ':flag'=> 5,
            ':idOrden'=> $this->_idOrden
        );
       
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function anularOrdenAll(){
        $query = "call sp_ordSeAnulacion(:idOrden,:usuario); ";
                        
        foreach ($this->_chkdel as $value) {
            $parms = array(
                ':idOrden'=>Aes::de($value),
                ':usuario'=> $this->_usuario 
            );
            $this->execute($query,$parms);
        }
        
        $data = array('result'=>1);
        return $data;
    }
    
}

?>