<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 06-12-2014 23:12:35 
* Descripcion : cajaCierreModel.php
* ---------------------------------------
*/ 

class cajaCierreModel extends Model{

    private $_flag;
    private $_idCajaCierre;
    private $_usuario;
    private $_fecha1;
    private $_fecha2;
    
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
        $this->_idCajaCierre   = Aes::de(Formulario::getParam("_idCajaCierre"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        
        $this->_fecha1 = Functions::cambiaf_a_mysql(Formulario::getParam("_fecha1"));
        $this->_fecha2 = Functions::cambiaf_a_mysql(Formulario::getParam("_fecha2"));
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: CajaCierre*/
    public function getCajaCierre(){
        $aColumns       =   array("id_caja","fecha_creacion","sigla_moneda","monto_inicial","total_ingresos","total_egresos","total_saldo","estado" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ventaCajaCierreGrid(:fecha1,:fecha2,:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(
            ":fecha1"=> $this->_fecha1,
            ":fecha2"=> $this->_fecha2,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }

 
    /*editar registro: postGenerarCierre*/
    public function postGenerarCierre(){
        $query = "UPDATE `ven_movimientos_caja` SET
                   estado  = :estado,
                   fecha_cierre = NOW(),
                   usuario_cierre = :usuario
                WHERE `estado` = 'A' and 
                       id_sucursal = :idSucursal; ";
        $parms = array(
            ":idSucursal" =>Session::get('sys_idSucursal'),
            ':estado' => 'C',
            ':usuario' => $this->_usuario  
        );
        $this->execute($query,$parms);
        
        $data = array('result'=>1);
        return $data;
    }

    public function postGenerarReajuste(){
        $query = "call sp_ventaReajustarCaja(:flag,:idSucursal,:usuario); ";
        $parms = array(
            ':flag' => 1,
            ":idSucursal" =>Session::get('sys_idSucursal'),           
            ':usuario' => $this->_usuario  
        );
       $data = $this->queryOne($query,$parms);                
       return $data;
    }    
    
    
}

?>