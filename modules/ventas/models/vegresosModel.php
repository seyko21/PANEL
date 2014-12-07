<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 22-11-2014 20:11:18 
* Descripcion : vegresosModel.php
* ---------------------------------------
*/ 

class vegresosModel extends Model{

    private $_flag;
    private $_idVegresos;
    private $_fecha;
    private $_chkdel;
    private $_descripcion;
    private $_monto;
    private $_fechaEgreso;
    private $_idMoneda;    
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
        $this->_idVegresos   = Aes::de(Formulario::getParam("_idVegresos"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_fecha = Functions::cambiaf_a_mysql(Formulario::getParam("_fecha"));
        
        $this->_chkdel  = Formulario::getParam(VEGRE.'chk_delete');
        $this->_descripcion     = Formulario::getParam(VEGRE.'txt_descripcion');
        $this->_monto     = str_replace(',','',Formulario::getParam(VEGRE.'txt_monto')); 
        $this->_fechaEgreso     = Functions::cambiaf_a_mysql(Formulario::getParam(VEGRE.'txt_fecha'));
        $this->_idMoneda     = Formulario::getParam(VEGRE.'lst_moneda');        
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: Vegresos*/
    public function getVegresos(){
        $aColumns       =   array("","descripcion","fecha","moneda","monto","estado" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ventaGenerarEgresosGrid(:fecha,:iDisplayStart,:iDisplayLength,:sOrder,:sSearch);";
        
        $parms = array(
            ":fecha" => $this->_fecha,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder,
            ":sSearch" => $this->_sSearch,
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    /*grabar nuevo registro: Vegresos*/
    public function newVegresos(){
        $query = "call sp_ventaGenerarEgresosMantenimiento(:flag,:key,:descripcion,:fecha,:monto,:moneda,:usuario,:idSucursal);";
        $parms = array(
            ':flag' => 1,
            ':key' => $this->_idVegresos,
            ':descripcion' => $this->_descripcion,
            ':fecha' => $this->_fechaEgreso,            
            ':monto' => $this->_monto, 
            ':moneda' => $this->_idMoneda, 
            ':usuario' => $this->_usuario,
            ':idSucursal'=> Session::get('sys_idSucursal')  
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    /*seleccionar registro a editar: Vegresos*/
    public function findVegresos(){
         $query = "SELECT 
                `id_egreso`,
                `descripcion`,
                `fecha`,
                `monto`,
                `moneda`,
                `estado` 
              FROM
                ven_egreso 
              WHERE  id_egreso = :idd; ";
        
        $parms = array(
            ':idd' => $this->_idVegresos
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    /*editar registro: Vegresos*/
    public function editVegresos(){
        $query = "call sp_ventaGenerarEgresosMantenimiento(:flag,:key,:descripcion,:fecha,:monto,:moneda,:usuario,:idSucursal);";
        $parms = array(
            ':flag' => 2,
            ':key' => $this->_idVegresos,
            ':descripcion' => $this->_descripcion,
            ':fecha' => $this->_fechaEgreso,            
            ':monto' => $this->_monto, 
            ':moneda' => $this->_idMoneda, 
            ':usuario' => $this->_usuario,
            ':idSucursal'=> Session::get('sys_idSucursal')  
        );
        $data = $this->queryOne($query,$parms);
        return $data;
    }
    
    /*eliminar varios registros: Vegresos*/
    public function deleteVegresosAll(){
       foreach ($this->_chkdel as $value) {
            $query = "call sp_ventaGenerarEgresosMantenimiento(:flag,:key,:descripcion,:fecha,:monto,:moneda,:usuario,:idSucursal);";
            $parms = array(
                ':flag' => 3,
                ':key' => Aes::de($value),
                ':descripcion' => '',
                ':fecha' => '',   
                ':monto' => '',
                ':moneda' => '',
                ':usuario' => $this->_usuario,
                ':idSucursal'=> Session::get('sys_idSucursal') 
            );
            $this->execute($query,$parms);
        }
        $data = array('result'=>1);
        return $data;
    }
    
}

?>