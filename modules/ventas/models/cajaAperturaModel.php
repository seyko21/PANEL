<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 06-12-2014 21:12:27 
* Descripcion : cajaAperturaModel.php
* ---------------------------------------
*/ 

class cajaAperturaModel extends Model{

    private $_flag;
    private $_idCajaApertura;
    private $_fecha1;
    private $_fecha2;
    private $_usuario;    
    private $_montoInicial;
    
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
        $this->_idCajaApertura   = Aes::de(Formulario::getParam("_idCajaApertura"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_fecha1 = Functions::cambiaf_a_mysql(Formulario::getParam("_fecha1"));
        $this->_fecha2 = Functions::cambiaf_a_mysql(Formulario::getParam("_fecha2"));
        $this->_montoInicial    = Functions::deleteComa(Formulario::getParam(CAJAA.'txt_inicio'));        
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
        $this->_sSearch        = Formulario::getParam("sSearch");
    }
    
    /*data para el grid: CajaApertura*/
    public function getCajaApertura(){
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
        
        $query = "call sp_ventaCajaAperturaGrid(:fecha1,:fecha2,:iDisplayStart,:iDisplayLength,:sOrder);";
        
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
    
    public function postGenerarApertura(){
        $query = "CALL sp_ventaAperturarCaja(:flag, :usuario);";
        $parms = array(
            ':flag' => 1,            
            ':usuario' => $this->_usuario  
        );
        
        $data = $this->queryOne($query,$parms);
        
        return $data;
    }    
    
    /*seleccionar registro a editar: CajaApertura*/
    public function findCajaApertura(){
         $query = "SELECT
            `monto_inicial`
            FROM `ven_movimientos_caja`
            WHERE `id_caja` = :idd ";
        $parms = array(            
            ':idd' => $this->_idCajaApertura  
        );
        
        $data = $this->queryOne($query,$parms);
        
        return $data;
    }
    
    /*editar registro: CajaApertura*/
    public function editCajaApertura(){
       $query = "UPDATE `ven_movimientos_caja` SET
                   monto_inicial  = :monto,
                   `total_saldo` = ( `monto_inicial` + `total_ingresos` ) - `total_egresos`
                WHERE `id_caja` = :idd;";
        $parms = array(
            ':idd' => $this->_idCajaApertura,
            ':monto' => $this->_montoInicial
        );
        $this->execute($query,$parms);
        $data = array('result'=>1);
        return $data;
    }
    
    public function getValidarCaja(){
        $query = "SELECT COUNT(*) as existe
		FROM ven_movimientos_caja
		WHERE `estado` = 'A' AND
			`id_sucursal` = :idSucursal; ";        
        $parms = array(
            ":idSucursal" =>Session::get('sys_idSucursal')
        );
        $data = $this->queryOne($query,$parms);      
        
        return $data;
    }      
    
}

?>