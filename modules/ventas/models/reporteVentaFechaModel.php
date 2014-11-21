<?php
/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-11-2014 00:11:17 
* Descripcion : reporteVentaFechaModel.php
* ---------------------------------------
*/ 

class reporteVentaFechaModel extends Model{

    private $_flag;
    private $_idReporteVentaFecha;     
    private $_usuario;
    private $_f1;
    private $_f2;    
    public  $_fecha;
    private $_moneda;
    
    /*para el grid*/
    public  $_iDisplayStart;
    private $_iDisplayLength;
    private $_iSortingCols;
    
    public function __construct() {
        parent::__construct();
        $this->_set();
    }
    
    private function _set(){
        $this->_flag        = Formulario::getParam("_flag");
        $this->_idReporteVentaFecha   = Aes::de(Formulario::getParam("_idReporteVentaFecha"));    /*se decifra*/
        $this->_usuario     = Session::get("sys_idUsuario");
        $this->_f1    = Functions::cambiaf_a_mysql(Formulario::getParam("_f1"));
        $this->_f2    = Functions::cambiaf_a_mysql(Formulario::getParam("_f2"));          
        
        $this->_fecha        =  Aes::de(Formulario::getParam("_fecha"));
        $this->_moneda        =  Aes::de(Formulario::getParam("_moneda"));
         
        
        $this->_iDisplayStart  = Formulario::getParam("iDisplayStart"); 
        $this->_iDisplayLength = Formulario::getParam("iDisplayLength"); 
        $this->_iSortingCols   = Formulario::getParam("iSortingCols");
    }
    
    /*data para el grid: ReporteVentaFecha*/
    public function getReporteVentaFecha(){
        $aColumns       =   array("","t.fecha","t.numero_doc","t.moneda","t.monto","t.saldo" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ventaRptVentaFechaGrid(:f1,:f2,:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(
            ":f1" => $this->_f1,
            ":f2" => $this->_f2,            
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }
    
    public function getConsultaVentaFecha(){
           $aColumns       =   array('','codigo_impresion','nombre_descripcion','moneda','monto_total', 'monto_asignado','monto_saldo'); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ventaConsultaVentaFechaGrid(:fecha,:moneda,:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(
            ":fecha" => $this->_fecha,
            ":moneda" => $this->_moneda,
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    } 
    
    public function getIndexVentaFecha(){
        $aColumns       =   array("t.fecha","t.numero_doc","t.moneda","t.monto","t.saldo" ); //para la ordenacion y pintado en html
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
        
        $query = "call sp_ventaIndexVentaFechaGrid(:iDisplayStart,:iDisplayLength,:sOrder);";
        
        $parms = array(                    
            ":iDisplayStart" => $this->_iDisplayStart,
            ":iDisplayLength" => $this->_iDisplayLength,
            ":sOrder" => $sOrder
        );
        $data = $this->queryAll($query,$parms);
        return $data;
    }    
  
    public function getListadoVenta(){
        $query = "
        SELECT
            d.`id_docventa`,
            d.`periodo`,
            d.`codigo_impresion`,
            DATE_FORMAT(d.`fecha`,'%d/%m/%Y')AS fecha,
            d.`id_persona`,
            (select pp.nombrecompleto from mae_persona pp where pp.id_persona = d.id_persona) as cliente,
            (select concat(`sigla`,' - ',`descripcion`) from pub_moneda mo where mo.id_moneda = d.`moneda`) as descripcion_moneda,
            (select `sigla` from pub_moneda mo where mo.id_moneda = d.`moneda`) as moneda,
            d.`monto_importe`,
            d.`tipo_doc`,
            d.`observacion`,
            d.estado,
            d.`monto_asignado`,
            d.`monto_saldo`
          FROM `ven_documento` d
          WHERE d.estado = :estado and d.`fecha` = :fecha and d.moneda = :moneda; ";
        
        $parms = array(
            ':estado'=>  "E",
            ':fecha'=>  $this->_fecha,
            ':moneda'=>  $this->_moneda
        );
        $data = $this->queryAll($query,$parms);      
        
        return $data;
    }    
    
   
    
}

?>